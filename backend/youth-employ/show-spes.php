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

// ─── GET ?year=2026&search=company ───────────────────────────────────────────
if ($method === 'GET') {

    $year   = isset($_GET['year'])   ? (int) $_GET['year']   : (int) date('Y');
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Available years from import_batches linked to spes
    $res = $conn->query("
        SELECT DISTINCT ib.year
        FROM import_batches ib
        INNER JOIN spes s ON s.batch_id = ib.batch_id
        ORDER BY ib.year DESC
    ");
    $years = [];
    while ($row = $res->fetch_assoc()) $years[] = (int) $row['year'];

    $currentYear = (int) date('Y');
    $years = array_unique(array_merge($years, [$currentYear, $year]));
    rsort($years);

    // ── Main SPES rows ───────────────────────────────────────────────────────
    // Count by beneficiaries.classification (same logic as job-match).
    // SPES classification values: Registered, Referred, Placed.
    // SPES Baby = student_type = 'student'.
    // 4Ps / PWD from beneficiaries flags.
    // Employer / contract dates from spes_employment.
    // Job vacancies from jobvacancies table for matching month/year.
    // ─────────────────────────────────────────────────────────────────────────

    $sql = "
        SELECT
            ib.batch_id                                                 AS spes_id,
            ib.month,
            ib.year,
            CASE ib.month
                WHEN 1  THEN 'January'   WHEN 2  THEN 'February'  WHEN 3  THEN 'March'
                WHEN 4  THEN 'April'     WHEN 5  THEN 'May'        WHEN 6  THEN 'June'
                WHEN 7  THEN 'July'      WHEN 8  THEN 'August'     WHEN 9  THEN 'September'
                WHEN 10 THEN 'October'   WHEN 11 THEN 'November'   WHEN 12 THEN 'December'
                ELSE ''
            END                                                         AS month_reported,

            -- Most common employer in this batch
            (
                SELECT e2.company_name
                FROM spes_employment se2
                INNER JOIN spes s2 ON s2.spes_id = se2.spes_id
                INNER JOIN employers e2 ON e2.company_id = se2.company_id
                WHERE s2.batch_id = ib.batch_id
                GROUP BY e2.company_name
                ORDER BY COUNT(*) DESC
                LIMIT 1
            )                                                           AS employer,

            -- Contract dates from spes_employment
            MIN(se.start_of_contract)                                   AS start_of_contract,
            MAX(se.end_of_contract)                                     AS end_of_contract,
            MAX(se.days)                                                AS days,

            -- Registered — classification = 'Registered'
            SUM(CASE WHEN b.classification = 'Registered' AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS reg_m,
            SUM(CASE WHEN b.classification = 'Registered' AND b.sex = 'Female' THEN 1 ELSE 0 END) AS reg_f,

            -- Referred — classification = 'Referred'
            SUM(CASE WHEN b.classification = 'Referred'   AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS ref_m,
            SUM(CASE WHEN b.classification = 'Referred'   AND b.sex = 'Female' THEN 1 ELSE 0 END) AS ref_f,

            -- Placed — classification = 'Placed' or 'Placed/Hots'
            SUM(CASE WHEN b.classification IN ('Placed','Placed/Hots') AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS placed_m,
            SUM(CASE WHEN b.classification IN ('Placed','Placed/Hots') AND b.sex = 'Female' THEN 1 ELSE 0 END) AS placed_f,

            -- Job Vacancies from jobvacancies table for same month/year
            COALESCE((
                SELECT SUM(jv.vacancy_male)
                FROM jobvacancies jv
                WHERE jv.month = ib.month AND jv.year = ib.year
            ), 0)                                                       AS vac_m,
            COALESCE((
                SELECT SUM(jv.vacancy_female)
                FROM jobvacancies jv
                WHERE jv.month = ib.month AND jv.year = ib.year
            ), 0)                                                       AS vac_f,

            -- SPES Baby = student_type = 'student'
            SUM(CASE WHEN s.student_type = 'student' AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS spes_baby_m,
            SUM(CASE WHEN s.student_type = 'student' AND b.sex = 'Female' THEN 1 ELSE 0 END) AS spes_baby_f,

            -- 4Ps from beneficiaries.is_4ps
            SUM(CASE WHEN b.is_4ps = 1 AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS fourps_m,
            SUM(CASE WHEN b.is_4ps = 1 AND b.sex = 'Female' THEN 1 ELSE 0 END) AS fourps_f,

            -- PWD from beneficiaries.is_pwd
            SUM(CASE WHEN b.is_pwd = 1 AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS pwd_m,
            SUM(CASE WHEN b.is_pwd = 1 AND b.sex = 'Female' THEN 1 ELSE 0 END) AS pwd_f

        FROM import_batches ib

        INNER JOIN spes s
            ON s.batch_id = ib.batch_id

        INNER JOIN beneficiaries b
            ON b.benef_id = s.benef_id

        LEFT JOIN spes_employment se
            ON se.spes_id = s.spes_id

        WHERE ib.year = ?
    ";

    $params = [$year];
    $types  = 'i';

    if ($search !== '') {
        $sql .= "
            AND (
                SELECT e3.company_name
                FROM spes_employment se3
                INNER JOIN spes s3 ON s3.spes_id = se3.spes_id
                INNER JOIN employers e3 ON e3.company_id = se3.company_id
                WHERE s3.batch_id = ib.batch_id
                LIMIT 1
            ) LIKE ?
        ";
        $types   .= 's';
        $params[] = "%$search%";
    }

    $sql .= "
        GROUP BY ib.batch_id, ib.month, ib.year
        ORDER BY ib.month ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $row['vac_total'] = (int)$row['vac_m'] + (int)$row['vac_f'];
        $rows[] = $row;
    }
    $stmt->close();

    // ── Monthly LGU / Private placed summary ────────────────────────────────
    $sumSql = "
        SELECT
            CASE ib.month
                WHEN 1  THEN 'January'   WHEN 2  THEN 'February'  WHEN 3  THEN 'March'
                WHEN 4  THEN 'April'     WHEN 5  THEN 'May'        WHEN 6  THEN 'June'
                WHEN 7  THEN 'July'      WHEN 8  THEN 'August'     WHEN 9  THEN 'September'
                WHEN 10 THEN 'October'   WHEN 11 THEN 'November'   WHEN 12 THEN 'December'
                ELSE ''
            END                                                             AS month_reported,
            SUM(CASE WHEN se.category = 'lgu'     AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS lgu_m,
            SUM(CASE WHEN se.category = 'lgu'     AND b.sex = 'Female' THEN 1 ELSE 0 END) AS lgu_f,
            SUM(CASE WHEN se.category = 'private' AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS priv_m,
            SUM(CASE WHEN se.category = 'private' AND b.sex = 'Female' THEN 1 ELSE 0 END) AS priv_f
        FROM import_batches ib
        INNER JOIN spes s          ON s.batch_id  = ib.batch_id
        INNER JOIN beneficiaries b ON b.benef_id  = s.benef_id
        INNER JOIN spes_employment se ON se.spes_id = s.spes_id
        WHERE ib.year = ?
        GROUP BY ib.batch_id, ib.month
        ORDER BY ib.month ASC
    ";
    $stmt2 = $conn->prepare($sumSql);
    if (!$stmt2) json_error('Summary query prepare failed: ' . $conn->error);
    $stmt2->bind_param('i', $year);
    $stmt2->execute();
    $sumResult = $stmt2->get_result();
    $summary = [];
    while ($row = $sumResult->fetch_assoc()) $summary[] = $row;
    $stmt2->close();

    // ── Card totals ──────────────────────────────────────────────────────────
    $totals = [
        'registered' => 0, 'referred' => 0, 'placed'    => 0,
        'vacancies'  => 0, 'spes_baby' => 0, 'fourps'   => 0, 'pwd' => 0,
    ];
    foreach ($rows as $r) {
        $totals['registered'] += (int)$r['reg_m']       + (int)$r['reg_f'];
        $totals['referred']   += (int)$r['ref_m']       + (int)$r['ref_f'];
        $totals['placed']     += (int)$r['placed_m']    + (int)$r['placed_f'];
        $totals['vacancies']  += (int)$r['vac_total'];
        $totals['spes_baby']  += (int)$r['spes_baby_m'] + (int)$r['spes_baby_f'];
        $totals['fourps']     += (int)$r['fourps_m']    + (int)$r['fourps_f'];
        $totals['pwd']        += (int)$r['pwd_m']       + (int)$r['pwd_f'];
    }

    json_ok(['rows' => $rows, 'summary' => $summary, 'totals' => $totals, 'years' => $years]);
}

// ─── PUT — edit batch month/year ─────────────────────────────────────────────
if ($method === 'PUT') {

    $body = json_decode(file_get_contents('php://input'), true);
    $id   = isset($body['spes_id']) ? (int) $body['spes_id'] : 0;
    if (!$id) json_error('Missing spes_id');

    $allowed = ['month', 'year'];
    $sets = []; $types = ''; $values = [];

    foreach ($allowed as $col) {
        if (array_key_exists($col, $body)) {
            $sets[]   = "$col = ?";
            $types   .= 'i';
            $values[] = (int) $body[$col];
        }
    }

    if (empty($sets)) json_error('Nothing to update');

    $types   .= 'i';
    $values[] = $id;

    $stmt = $conn->prepare("UPDATE import_batches SET " . implode(', ', $sets) . " WHERE batch_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['updated' => $affected]);
}

// ─── DELETE ?id=<batch_id> ───────────────────────────────────────────────────
if ($method === 'DELETE') {

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if (!$id) json_error('Missing id');

    $conn->begin_transaction();
    try {
        $s1 = $conn->prepare("UPDATE spes SET batch_id = NULL WHERE batch_id = ?");
        $s1->bind_param('i', $id);
        $s1->execute();
        $s1->close();

        $s2 = $conn->prepare("DELETE FROM import_batches WHERE batch_id = ?");
        $s2->bind_param('i', $id);
        $s2->execute();
        $affected = $s2->affected_rows;
        $s2->close();

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        json_error('Delete failed: ' . $e->getMessage());
    }

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);