<?php
require_once __DIR__ . '/../../includes/auth-check.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../api');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$db   = $_ENV['DB_NAME'] ?? null;

if (!$db) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Missing DB_NAME in .env']);
    exit;
}

/* ── PDO connection ── */
try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB connection failed: ' . $e->getMessage()]);
    exit;
}

set_error_handler(function (int $errno, string $errstr) {
    if (!headers_sent()) header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => "PHP Error ($errno): $errstr"]);
    exit;
});

header('Content-Type: application/json');

$currentYear = (int) date('Y');
$previousResetCount = 0;

try {

    /* ═══════════════════════════════════════════════════════════════
     * NAME LOOKUPS — sections and programs tables
     * Used when injecting special-source programs into the payload.
     * ═══════════════════════════════════════════════════════════════ */
    $sectionNameMap = [];
    foreach ($pdo->query("SELECT section_id, name FROM sections")->fetchAll() as $r) {
        $sectionNameMap[(int) $r['section_id']] = $r['name'];
    }

    $programNameMap = [];
    foreach ($pdo->query("SELECT program_id, name FROM programs")->fetchAll() as $r) {
        $programNameMap[(int) $r['program_id']] = $r['name'];
    }

    /* ═══════════════════════════════════════════════════════════════
     * 1. BENEFICIARIES — per section per program
     * ═══════════════════════════════════════════════════════════════ */

    /* ── 1A. Sex counts per section × program (with names) ── */
    $sexStmt = $pdo->query("
        SELECT
            p.section_id,
            s.name       AS section_name,
            b.program_id,
            p.name       AS program_name,
            SUM(CASE WHEN b.sex = 'Male'   THEN 1 ELSE 0 END) AS total_male,
            SUM(CASE WHEN b.sex = 'Female' THEN 1 ELSE 0 END) AS total_female
        FROM beneficiaries b
        JOIN programs  p ON p.program_id = b.program_id
        JOIN sections  s ON s.section_id = p.section_id
        GROUP BY p.section_id, s.name, b.program_id, p.name
    ");
    $sexRows = $sexStmt->fetchAll();

    /* ── 1B. Upsert into total_benef (one row per section × program) ── */
    $checkBenef = $pdo->prepare("
        SELECT COUNT(*) FROM total_benef
        WHERE section_id = :section_id AND program_id = :program_id
    ");
    $insBenef = $pdo->prepare("
        INSERT INTO total_benef (section_id, program_id, total_male, total_female)
        VALUES (:section_id, :program_id, :male, :female)
    ");
    $updBenef = $pdo->prepare("
        UPDATE total_benef
           SET total_male   = :male,
               total_female = :female
         WHERE section_id = :section_id
           AND program_id  = :program_id
    ");

    foreach ($sexRows as $row) {
        $checkBenef->execute([
            ':section_id' => $row['section_id'],
            ':program_id' => $row['program_id'],
        ]);
        $exists = (int) $checkBenef->fetchColumn();

        $params = [
            ':section_id' => $row['section_id'],
            ':program_id' => $row['program_id'],
            ':male'       => (int) $row['total_male'],
            ':female'     => (int) $row['total_female'],
        ];

        $exists > 0 ? $updBenef->execute($params) : $insBenef->execute($params);
    }

    /* ── 1C. Classification counts per section × program × month × year ── */
    $classStmt = $pdo->query("
        SELECT
            p.section_id,
            b.program_id,
            DATE_FORMAT(b.created_at, '%M') AS month,
            YEAR(b.created_at)              AS year,
            SUM(CASE WHEN b.classification = 'Registered'                        THEN 1 ELSE 0 END) AS total_registered,
            SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed','Hired')   THEN 1 ELSE 0 END) AS total_hired
        FROM beneficiaries b
        JOIN programs p ON p.program_id = b.program_id
        WHERE b.classification IS NOT NULL
        GROUP BY p.section_id, b.program_id, YEAR(b.created_at), MONTH(b.created_at), DATE_FORMAT(b.created_at, '%M')
        ORDER BY p.section_id, b.program_id, YEAR(b.created_at), MONTH(b.created_at)
    ");
    $classRows = $classStmt->fetchAll();
    
        /* ── 1C. Classification counts per import batch month × year ── */
        $monthRows = $pdo->query("
            SELECT
                x.year_num,
                x.month_num,
                SUM(x.total_registered) AS total_registered,
                SUM(x.total_hired)      AS total_hired
            FROM (
                SELECT
                    ib.year  AS year_num,
                    ib.month AS month_num,
                    SUM(CASE WHEN b.classification = 'Registered' THEN 1 ELSE 0 END) AS total_registered,
                    SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed','Hired') THEN 1 ELSE 0 END) AS total_hired
                FROM beneficiaries b
                JOIN jobmatch jm ON jm.benef_id = b.benef_id
                JOIN import_batches ib ON ib.batch_id = jm.batch_id
                WHERE b.classification IS NOT NULL
                GROUP BY ib.year, ib.month

                UNION ALL

                SELECT
                    ib.year  AS year_num,
                    ib.month AS month_num,
                    SUM(CASE WHEN b.classification = 'Registered' THEN 1 ELSE 0 END) AS total_registered,
                    SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed','Hired') THEN 1 ELSE 0 END) AS total_hired
                FROM beneficiaries b
                JOIN firstjobseek fjs ON fjs.benef_id = b.benef_id
                JOIN import_batches ib ON ib.batch_id = fjs.batch_id
                WHERE b.classification IS NOT NULL
                GROUP BY ib.year, ib.month

                UNION ALL

                SELECT
                    ib.year  AS year_num,
                    ib.month AS month_num,
                    SUM(CASE WHEN b.classification = 'Registered' THEN 1 ELSE 0 END) AS total_registered,
                    SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed','Hired') THEN 1 ELSE 0 END) AS total_hired
                FROM beneficiaries b
                JOIN jobfair jf ON jf.benef_id = b.benef_id
                JOIN import_batches ib ON ib.batch_id = jf.batch_id
                WHERE b.classification IS NOT NULL
                GROUP BY ib.year, ib.month

                UNION ALL

                SELECT
                    ib.year  AS year_num,
                    ib.month AS month_num,
                    SUM(CASE WHEN b.classification = 'Registered' THEN 1 ELSE 0 END) AS total_registered,
                    SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed','Hired') THEN 1 ELSE 0 END) AS total_hired
                FROM beneficiaries b
                JOIN whip w ON w.benef_id = b.benef_id
                JOIN import_batches ib ON ib.batch_id = w.batch_id
                WHERE b.classification IS NOT NULL
                GROUP BY ib.year, ib.month

                UNION ALL

                SELECT
                    ib.year  AS year_num,
                    ib.month AS month_num,
                    SUM(CASE WHEN b.classification = 'Registered' THEN 1 ELSE 0 END) AS total_registered,
                    SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed','Hired') THEN 1 ELSE 0 END) AS total_hired
                FROM beneficiaries b
                JOIN spes s ON s.benef_id = b.benef_id
                JOIN import_batches ib ON ib.batch_id = s.batch_id
                WHERE b.classification IS NOT NULL
                GROUP BY ib.year, ib.month

                UNION ALL

                SELECT
                    ib.year  AS year_num,
                    ib.month AS month_num,
                    SUM(CASE WHEN b.classification = 'Registered' THEN 1 ELSE 0 END) AS total_registered,
                    SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed','Hired') THEN 1 ELSE 0 END) AS total_hired
                FROM beneficiaries b
                JOIN gip g ON g.benef_id = b.benef_id
                JOIN import_batches ib ON ib.batch_id = g.batch_id
                WHERE b.classification IS NOT NULL
                GROUP BY ib.year, ib.month

                UNION ALL

                SELECT
                    ib.year  AS year_num,
                    ib.month AS month_num,
                    SUM(CASE WHEN b.classification = 'Registered' THEN 1 ELSE 0 END) AS total_registered,
                    SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed','Hired') THEN 1 ELSE 0 END) AS total_hired
                FROM beneficiaries b
                JOIN wiirp w ON w.benef_id = b.benef_id
                JOIN import_batches ib ON ib.batch_id = w.batch_id
                WHERE b.classification IS NOT NULL
                GROUP BY ib.year, ib.month
            ) x
            GROUP BY x.year_num, x.month_num
            ORDER BY x.year_num, x.month_num
        ")->fetchAll();

        /* ── 1D. Roll up to month × year for total_comparison ── */
        $monthTotals = [];
        foreach ($monthRows as $row) {
            $monthNum = (int) $row['month_num'];
            $yearNum  = (int) $row['year_num'];
            $monthName = date('F', mktime(0, 0, 0, $monthNum, 1));
            $key = $yearNum . '-' . $monthNum;

            if (!isset($monthTotals[$key])) {
                $monthTotals[$key] = [
                    'month'            => $monthName,
                    'month_num'        => $monthNum,
                    'year'             => $yearNum,
                    'month_label'      => $monthName . ' ' . $yearNum,
                    'total_registered' => 0,
                    'total_hired'      => 0,
                ];
            }

            $monthTotals[$key]['total_registered'] += (int) $row['total_registered'];
            $monthTotals[$key]['total_hired']      += (int) $row['total_hired'];
        }

    $checkComp = $pdo->prepare("
        SELECT COUNT(*) FROM total_comparison WHERE month = :month AND year = :year
    ");
    $insComp = $pdo->prepare("
        INSERT INTO total_comparison (month, year, total_registered, total_hired)
        VALUES (:month, :year, :registered, :hired)
    ");
    $updComp = $pdo->prepare("
        UPDATE total_comparison
           SET total_registered = :registered,
               total_hired      = :hired
         WHERE month = :month AND year = :year
    ");

    foreach ($monthTotals as $entry) {
        $checkComp->execute([':month' => $entry['month'], ':year' => $entry['year']]);
        $exists = (int) $checkComp->fetchColumn();

        $params = [
            ':month'      => $entry['month'],
            ':year'       => $entry['year'],
            ':registered' => $entry['total_registered'],
            ':hired'      => $entry['total_hired'],
        ];
        $exists > 0 ? $updComp->execute($params) : $insComp->execute($params);
    }

    /* ═══════════════════════════════════════════════════════════════
     * 2. EMPLOYERS & VACANCIES
     * ═══════════════════════════════════════════════════════════════ */

    $totalEmployers = (int) $pdo->query("SELECT COUNT(*) FROM employers")->fetchColumn();

    $vacStmt = $pdo->prepare("
        SELECT
            COALESCE(SUM(vacancy_male),   0) AS sum_male,
            COALESCE(SUM(vacancy_female), 0) AS sum_female
        FROM jobvacancies
        WHERE year = :year
    ");
    $vacStmt->execute([':year' => $currentYear]);
    $vacRow = $vacStmt->fetch();

    $totalVacancies = (int) $vacRow['sum_male'] + (int) $vacRow['sum_female'];

    /* ── Upsert into total_employers (single summary row) ── */
    $existsEmp = (int) $pdo->query("SELECT COUNT(*) FROM total_employers")->fetchColumn();

    if ($existsEmp > 0) {
        $pdo->prepare("
            UPDATE total_employers
               SET total_employers = :employers,
                   total_vacancies = :vacancies
             LIMIT 1
        ")->execute([':employers' => $totalEmployers, ':vacancies' => $totalVacancies]);
    } else {
        $pdo->prepare("
            INSERT INTO total_employers (total_employers, total_vacancies)
            VALUES (:employers, :vacancies)
        ")->execute([':employers' => $totalEmployers, ':vacancies' => $totalVacancies]);
    }

    /* ═══════════════════════════════════════════════════════════════
     * 3. CAREER DEVELOPMENT — special tables (section 4)
     *
     *  program 9  → careerdev  (Career Development Support Program)
     *  program 10 → lmi        (LMI Orientation)
     *
     *  Both use participants_male + participants_female as their total.
     *  These records are NOT in the beneficiaries table.
     * ═══════════════════════════════════════════════════════════════ */

    $careerdevTotal = (int) $pdo->query("
        SELECT COALESCE(SUM(participants_male + participants_female), 0) FROM careerdev
    ")->fetchColumn();

    $lmiTotal = (int) $pdo->query("
        SELECT COALESCE(SUM(participants_male + participants_female), 0) FROM lmi
    ")->fetchColumn();

    /* ═══════════════════════════════════════════════════════════════
     * 4. BUILD PAYLOAD
     * ═══════════════════════════════════════════════════════════════ */

    $classByProgram = [];
    foreach ($classRows as $row) {
        $sid = (int) $row['section_id'];
        $pid = (int) $row['program_id'];
        if (!isset($classByProgram[$sid][$pid])) {
            $classByProgram[$sid][$pid] = ['total_registered' => 0, 'total_hired' => 0];
        }
        $classByProgram[$sid][$pid]['total_registered'] += (int) $row['total_registered'];
        $classByProgram[$sid][$pid]['total_hired']      += (int) $row['total_hired'];
    }

    $programList = [];
    $bySection   = [];
    $grandMale = $grandFemale = $grandRegistered = $grandHired = 0;

    /* Programs whose totals come from a source other than beneficiaries.
     * Skipped in the main loop and injected explicitly below.           */
    $specialPrograms = [4, 9, 10];

    /* ── Build from programs table directly (not just sexRows) ── */
    $allPrograms = $pdo->query("
        SELECT
            p.program_id,
            p.name       AS program_name,
            p.section_id,
            s.name       AS section_name
        FROM programs p
        JOIN sections s ON s.section_id = p.section_id
        ORDER BY p.section_id, p.program_id
    ")->fetchAll();

    foreach ($allPrograms as $row) {
        $sid = (int) $row['section_id'];
        $pid = (int) $row['program_id'];

        if (in_array($pid, $specialPrograms, true)) {
            continue;
        }

        $sectionName = $row['section_name'];
        $programName = $row['program_name'];

        // Pull sex counts from sexRows if they exist, otherwise 0
        $sexKey = null;
        foreach ($sexRows as $sr) {
            if ((int)$sr['section_id'] === $sid && (int)$sr['program_id'] === $pid) {
                $sexKey = $sr;
                break;
            }
        }
        $male = $sexKey ? (int)$sexKey['total_male']   : 0;
        $fem  = $sexKey ? (int)$sexKey['total_female']  : 0;
        $reg  = $classByProgram[$sid][$pid]['total_registered'] ?? 0;
        $hired = $classByProgram[$sid][$pid]['total_hired']     ?? 0;

        $programList[] = [
            'section_id'       => $sid,
            'section_name'     => $sectionName,
            'program_id'       => $pid,
            'program_name'     => $programName,
            'total_male'       => $male,
            'total_female'     => $fem,
            'total'            => $male + $fem,
            'total_registered' => $reg,
            'total_hired'      => $hired,
        ];

        if (!isset($bySection[$sid])) {
            $bySection[$sid] = [
                'section_id'       => $sid,
                'section_name'     => $sectionName,
                'total_male'       => 0,
                'total_female'     => 0,
                'total'            => 0,
                'total_registered' => 0,
                'total_hired'      => 0,
            ];
        }
        $bySection[$sid]['total_male']       += $male;
        $bySection[$sid]['total_female']     += $fem;
        $bySection[$sid]['total']            += $male + $fem;
        $bySection[$sid]['total_registered'] += $reg;
        $bySection[$sid]['total_hired']      += $hired;

        $grandMale       += $male;
        $grandFemale     += $fem;
        $grandRegistered += $reg;
        $grandHired      += $hired;
    }

    /* ── Helper: ensure a bySection entry exists for a given section id ── */
    $ensureSection = function (int $sid) use (&$bySection, $sectionNameMap): void {
        if (!isset($bySection[$sid])) {
            $bySection[$sid] = [
                'section_id'       => $sid,
                'section_name'     => $sectionNameMap[$sid] ?? "Section $sid",
                'total_male'       => 0,
                'total_female'     => 0,
                'total'            => 0,
                'total_registered' => 0,
                'total_hired'      => 0,
            ];
        }
    };

    /* ── Special: program 4 — Employers Accreditation (section 2)
     *  Total = COUNT(*) from employers table, not beneficiaries.     ── */
    $ensureSection(2);
    $programList[] = [
        'section_id'       => 2,
        'section_name'     => $sectionNameMap[2] ?? 'Employers Engagement',
        'program_id'       => 4,
        'program_name'     => $programNameMap[4] ?? 'Employers Accreditation',
        'total_male'       => 0,
        'total_female'     => 0,
        'total'            => $totalEmployers,
        'total_registered' => 0,
        'total_hired'      => 0,
    ];
    $bySection[2]['total'] += $totalEmployers;

    /* ── Special: program 9 — Career Development Support Program (section 4)
     *  Total = SUM(participants_male + participants_female) from careerdev. ── */
    $ensureSection(4);
    $programList[] = [
        'section_id'       => 4,
        'section_name'     => $sectionNameMap[4] ?? 'Career Development',
        'program_id'       => 9,
        'program_name'     => $programNameMap[9] ?? 'Career Development Support Program',
        'total_male'       => 0,
        'total_female'     => 0,
        'total'            => $careerdevTotal,
        'total_registered' => 0,
        'total_hired'      => 0,
    ];
    $bySection[4]['total'] += $careerdevTotal;

    /* ── Special: program 10 — LMI Orientation (section 4)
     *  Total = SUM(participants_male + participants_female) from lmi.       ── */
    $programList[] = [
        'section_id'       => 4,
        'section_name'     => $sectionNameMap[4] ?? 'Career Development',
        'program_id'       => 10,
        'program_name'     => $programNameMap[10] ?? 'LMI Orientation',
        'total_male'       => 0,
        'total_female'     => 0,
        'total'            => $lmiTotal,
        'total_registered' => 0,
        'total_hired'      => 0,
    ];
    $bySection[4]['total'] += $lmiTotal;

    /* ── Sort by section_id then program_id for consistent ordering ── */
    usort($programList, fn($a, $b) =>
        $a['section_id'] <=> $b['section_id'] ?: $a['program_id'] <=> $b['program_id']
    );

    $payload = [
        'generated_at'             => date('Y-m-d H:i:s'),
        'cache_reset_count'        => $previousResetCount + 1,
        'cache_refreshed_at'       => date('Y-m-d H:i:s'),
        'beneficiaries_totals'     => [
            'total_male'       => $grandMale,
            'total_female'     => $grandFemale,
            'total_registered' => $grandRegistered,
            'total_hired'      => $grandHired,
        ],
        'beneficiaries_by_section' => array_values($bySection),
        'beneficiaries_by_program' => $programList,
        'comparison_by_month'      => array_values($monthTotals),
        'employers'                => [
            'total_employers' => $totalEmployers,
            'total_vacancies' => $totalVacancies,
        ],
    ];

    echo json_encode(['success' => true, 'data' => $payload], JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}