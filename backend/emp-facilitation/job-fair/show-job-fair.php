<?php
$require_auth = __DIR__ . '/../../../includes/auth-check.php';
require_once $require_auth;
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../api');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$db   = $_ENV['DB_NAME'] ?? null;

if (!$db) {
    echo json_encode(['success' => false, 'error' => 'Missing DB_NAME in .env']);
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

set_error_handler(function (int $errno, string $errstr) {
    if (!headers_sent()) header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => "PHP Error ($errno): $errstr"]);
    exit;
});

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

function json_error(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}
function json_ok(mixed $data = null): void {
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

// ─── GET ─────────────────────────────────────────────────────────────────────
// ?year=2026 [&type=LOCAL|OVERSEAS]
//
// Groups by: event (job_fair_events) → employer (jobfair_participants + employers)
// Counts from jobfair → beneficiaries.classification x beneficiaries.sex
// Month/year comes from import_batches linked via jobfair.batch_id
//
// classification values (same convention as jobmatch):
//   Registered, Referred, Interviewed, Qualified, Not Qualified, Placed/Hots, For Further Interview
// sex values: 'Male' / 'Female'
// ─────────────────────────────────────────────────────────────────────────────
if ($method === 'GET') {

    $year       = isset($_GET['year'])  ? (int) $_GET['year']          : (int) date('Y');
    $typeFilter = isset($_GET['type'])  ? strtoupper(trim($_GET['type'])) : '';

    // ── Available years (from batches that have jobfair rows) ─────────────
    $res   = $conn->query("
        SELECT DISTINCT ib.year
        FROM import_batches ib
        INNER JOIN jobfair jf ON jf.batch_id = ib.batch_id
        ORDER BY ib.year DESC
    ");
    $years = [];
    while ($row = $res->fetch_assoc()) $years[] = (int) $row['year'];
    // Always include current + requested year in the dropdown
    $years = array_unique(array_merge($years, [(int) date('Y'), $year]));
    rsort($years);

    // ── Type WHERE clause ─────────────────────────────────────────────────
    $typeWhere = '';
    if ($typeFilter === 'LOCAL') {
        $typeWhere = "AND jfe.job_fair_type = 'LOCAL JOB FAIR'";
    } elseif ($typeFilter === 'OVERSEAS') {
        $typeWhere = "AND jfe.job_fair_type = 'OVERSEAS JOB FAIR'";
    }

    // ── Main query ─────────────────────────────────────────────────────────
    // One row per (event × employer).
    // Counts beneficiaries from jobfair → beneficiaries.classification x sex.
    // Month/year: from import_batches of the jobfair batch for that event+company.
    // Job vacancies: from jobvacancies table (linked to event+company).
    $sql = "
     SELECT
         jfe.jobfairevent_id,
         jfe.job_fair_type,
         jfe.date_start,
         jfe.date_end,
         jfe.venue,
         e.company_id,
         e.company_name,

         -- Month/year from import_batches (use MAX; one batch per event+company import)
         MAX(ib.month) AS batch_month_num,
         MAX(ib.year)  AS batch_year,
         MAX(ib.batch_id) AS batch_id,

            -- Job vacancies
            COALESCE((
                SELECT SUM(jv.vacancy_male)
                FROM jobvacancies jv
                WHERE jv.jobfairevent_id = jfe.jobfairevent_id
                  AND jv.company_id = e.company_id
            ), 0) AS vacancy_male,
            COALESCE((
                SELECT SUM(jv.vacancy_female)
                FROM jobvacancies jv
                WHERE jv.jobfairevent_id = jfe.jobfairevent_id
                  AND jv.company_id = e.company_id
            ), 0) AS vacancy_female,

            -- Counts from beneficiaries.classification x sex, joined via jobfair
            SUM(CASE WHEN b.classification = 'Registered'              AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS reg_m,
            SUM(CASE WHEN b.classification = 'Registered'              AND b.sex = 'Female' THEN 1 ELSE 0 END) AS reg_f,

            SUM(CASE WHEN b.classification = 'Referred'                AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS ref_m,
            SUM(CASE WHEN b.classification = 'Referred'                AND b.sex = 'Female' THEN 1 ELSE 0 END) AS ref_f,

            SUM(CASE WHEN b.classification = 'Interviewed'             AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS int_m,
            SUM(CASE WHEN b.classification = 'Interviewed'             AND b.sex = 'Female' THEN 1 ELSE 0 END) AS int_f,

            SUM(CASE WHEN b.classification = 'Qualified'               AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS qual_m,
            SUM(CASE WHEN b.classification = 'Qualified'               AND b.sex = 'Female' THEN 1 ELSE 0 END) AS qual_f,

            SUM(CASE WHEN b.classification = 'Not Qualified'           AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS nqual_m,
            SUM(CASE WHEN b.classification = 'Not Qualified'           AND b.sex = 'Female' THEN 1 ELSE 0 END) AS nqual_f,

            SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed') AND b.sex = 'Male' THEN 1 ELSE 0 END) AS placed_m,
            SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed') AND b.sex = 'Female' THEN 1 ELSE 0 END) AS placed_f,

            SUM(CASE WHEN b.classification = 'Hired' AND b.sex = 'Male' THEN 1 ELSE 0 END) AS hired_m,
            SUM(CASE WHEN b.classification = 'Hired' AND b.sex = 'Female' THEN 1 ELSE 0 END) AS hired_f,

            SUM(CASE WHEN b.classification = 'For Further Interview'   AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS ffi_m,
            SUM(CASE WHEN b.classification = 'For Further Interview'   AND b.sex = 'Female' THEN 1 ELSE 0 END) AS ffi_f

        FROM job_fair_events jfe
        -- Each employer that participated in this event (deduplicate in case of duplicate participant rows)
        INNER JOIN (
            SELECT DISTINCT jobfairevent_id, company_id
            FROM jobfair_participants
        ) jp ON jp.jobfairevent_id = jfe.jobfairevent_id
        INNER JOIN employers e
            ON e.company_id = jp.company_id

        -- jobfair rows for this event+company → beneficiary + batch
        LEFT JOIN jobfair jf
            ON  jf.jobfairevent_id = jfe.jobfairevent_id
            AND jf.company_id      = e.company_id
        LEFT JOIN import_batches ib
            ON  ib.batch_id = jf.batch_id
            AND ib.year     = ?
        LEFT JOIN beneficiaries b
            ON  b.benef_id = jf.benef_id

        WHERE (ib.year = ? OR ib.year IS NULL)
        $typeWhere

        GROUP BY
            jfe.jobfairevent_id,
            jfe.job_fair_type,
            jfe.date_start,
            jfe.date_end,
            jfe.venue,
            e.company_id,
            e.company_name

        ORDER BY
            jfe.date_start ASC,
            e.company_name ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('ii', $year, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $monthNames = [
        1=>'January',2=>'February',3=>'March',4=>'April',
        5=>'May',6=>'June',7=>'July',8=>'August',
        9=>'September',10=>'October',11=>'November',12=>'December'
    ];

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        // Month: from batch, fall back to event date_start
         $monthNum = (int)($row['batch_month_num'] ?? 0);
         if (!$monthNum && $row['date_start']) {
             $monthNum = (int) date('n', strtotime($row['date_start']));
         }
         $row['month']     = $monthNames[$monthNum] ?? 'Unknown';
         $row['month_num'] = $monthNum;
         $row['year']      = (int)($row['batch_year'] ?? $year);
         $row['batch_id']  = $row['batch_id'] ?? null;

        // Totals
        $row['vacancy_total'] = (int)$row['vacancy_male']  + (int)$row['vacancy_female'];
        $row['reg_total']     = (int)$row['reg_m']         + (int)$row['reg_f'];
        $row['ref_total']     = (int)$row['ref_m']         + (int)$row['ref_f'];
        $row['int_total']     = (int)$row['int_m']         + (int)$row['int_f'];
        $row['qual_total']    = (int)$row['qual_m']        + (int)$row['qual_f'];
        $row['nqual_total']   = (int)$row['nqual_m']       + (int)$row['nqual_f'];
        $row['placed_total'] = (int)$row['placed_m'] + (int)$row['placed_f'];
        $row['hired_total']  = (int)$row['hired_m'] + (int)$row['hired_f'];
        $row['ffi_total']     = (int)$row['ffi_m']         + (int)$row['ffi_f'];

        $rows[] = $row;
    }
    $stmt->close();

    // ── Summary card totals ────────────────────────────────────────────────
    $totals = [
        'job_vacancies' => 0,
        'employers'     => 0,
        'interviewed'   => 0,
        'qualified'     => 0,
        'placed'        => 0,
        'hired'         => 0,
    ];
    $uniqueEmployers = [];
    foreach ($rows as $r) {
        $totals['job_vacancies'] += (int)$r['vacancy_total'];
        $totals['interviewed']   += (int)$r['int_total'];
        $totals['qualified']     += (int)$r['qual_total'];
        $totals['placed'] += (int)$r['placed_total'];
        $totals['hired']  += (int)$r['hired_total'];
        $uniqueEmployers[$r['company_id']] = true;
    }
    $totals['employers'] = count($uniqueEmployers);

    // ── Grand totals (for table footer) ───────────────────────────────────
    $cols = [
        'vacancy_male','vacancy_female','vacancy_total',
        'reg_m','reg_f','reg_total',
        'ref_m','ref_f','ref_total',
        'int_m','int_f','int_total',
        'qual_m','qual_f','qual_total',
        'nqual_m','nqual_f','nqual_total',
        'placed_m','placed_f','placed_total',
        'hired_m', 'hired_f', 'hired_total',
        'ffi_m','ffi_f','ffi_total',
    ];
    $grandTotals = [];
    foreach ($cols as $col) {
        $grandTotals[$col] = array_sum(array_column($rows, $col));
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'grandTotals' => $grandTotals, 'years' => $years]);
}

// ─── PUT — update beneficiary stat counts for a batch ────────────────────────
// Body: { batch_id, reg_m, reg_f, ref_m, ref_f, int_m, int_f,
//          qual_m, qual_f, nqual_m, nqual_f, placed_m, placed_f, ffi_m, ffi_f }
// Strategy: re-write the beneficiaries.classification/sex values linked via
//   jobfair rows for this batch. Since beneficiaries are shared records we
//   don't mutate them; instead we update the counts in a jobfair_batch_stats
//   table if it exists, otherwise we just return success (UI already reflects
//   the edited values in-memory).
// For now: update import_batches month/year only (same as job-match PUT).
if ($method === 'PUT') {
    $body     = json_decode(file_get_contents('php://input'), true);
    $batch_id = isset($body['batch_id']) ? (int) $body['batch_id'] : 0;
    if (!$batch_id) json_error('Missing batch_id');

    $allowed = ['month', 'year'];
    $sets    = [];
    $types   = '';
    $values  = [];

    foreach ($allowed as $col) {
        if (array_key_exists($col, $body)) {
            $sets[]   = "$col = ?";
            $types   .= 'i';
            $values[] = (int) $body[$col];
        }
    }

    // If no month/year changes, still return success (stat edits are UI-only for now)
    if (empty($sets)) {
        json_ok(['updated' => 0]);
    }

    $types   .= 'i';
    $values[] = $batch_id;

    $stmt = $conn->prepare(
        "UPDATE import_batches SET " . implode(', ', $sets) . " WHERE batch_id = ?"
    );
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    json_ok(['updated' => $stmt->affected_rows]);
}

// ─── DELETE — remove participant + their batch data for this event ────────────
if ($method === 'DELETE') {
    $event_id   = isset($_GET['event_id'])   ? (int) $_GET['event_id']   : 0;
    $company_id = isset($_GET['company_id']) ? (int) $_GET['company_id'] : 0;
    $batch_id   = isset($_GET['batch_id'])   ? (int) $_GET['batch_id']   : 0;

    if (!$event_id || !$company_id) json_error('Missing event_id or company_id');

    $conn->begin_transaction();
    try {
        // Remove jobfair import rows for this event+company
        $s1 = $conn->prepare("DELETE FROM jobfair WHERE jobfairevent_id = ? AND company_id = ?");
        $s1->bind_param('ii', $event_id, $company_id);
        $s1->execute();
        $s1->close();

        // Remove batch if provided and now empty
        if ($batch_id) {
            $check = $conn->prepare("SELECT COUNT(*) AS cnt FROM jobfair WHERE batch_id = ?");
            $check->bind_param('i', $batch_id);
            $check->execute();
            $cnt = (int) $check->get_result()->fetch_assoc()['cnt'];
            $check->close();
            if ($cnt === 0) {
                $s2 = $conn->prepare("DELETE FROM import_batches WHERE batch_id = ?");
                $s2->bind_param('i', $batch_id);
                $s2->execute();
                $s2->close();
            }
        }

        // Remove the participant record
        $s3 = $conn->prepare("DELETE FROM jobfair_participants WHERE jobfairevent_id = ? AND company_id = ?");
        $s3->bind_param('ii', $event_id, $company_id);
        $s3->execute();
        $affected = $s3->affected_rows;
        $s3->close();

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        json_error('Delete failed: ' . $e->getMessage());
    }

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);