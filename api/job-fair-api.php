<?php
require_once __DIR__ . '/../includes/auth-check.php';
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST']     ?? 'localhost';
$user = $_ENV['DB_USER']     ?? 'root';
$pass = $_ENV['DB_PASS']     ?? '';
$db   = $_ENV['DB_NAME']     ?? null;

if (!$db) {
    echo json_encode(['success' => false, 'error' => 'Missing DB_DATABASE in .env']);
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

set_error_handler(function (int $errno, string $errstr) {
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode(['success' => false, 'error' => "PHP Error ($errno): $errstr"]);
    exit;
});

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// ─── Helpers ────────────────────────────────────────────────────────────────
function json_error(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

function json_ok(mixed $data = null): void {
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

// ─── GET ?year=2026 ─────────────────────────────────────────────────────────
// Returns all jobFair rows for the given year, with beneficiary pivot counts
// per employer per month, plus summary card totals.
if ($method === 'GET') {
    global $conn;

    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    // Available years for the dropdown
    $res   = $conn->query("SELECT DISTINCT year FROM jobFair ORDER BY year DESC");
    $years = [];
    while ($row = $res->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }
    if (!in_array($year, $years, true)) {
        $years[] = $year;
        rsort($years);
    }

    // Main query: one row per jobFair record (per employer per month).
    // Beneficiary counts (interviewed, qualified, etc.) are pivoted from
    // apply_benef + beneficiaries, matched by month/year of beneficiary creation.
    $sql = "
        SELECT
            jf.jobfair_id,
            jf.month,
            jf.year,
            jf.company_name,
            jf.vacancy_male,
            jf.vacancy_female,
            (jf.vacancy_male + jf.vacancy_female) AS vacancy_total,

            SUM(CASE WHEN ab.classification = 'Interviewed'          AND b.gender = 'M' THEN 1 ELSE 0 END) AS int_m,
            SUM(CASE WHEN ab.classification = 'Interviewed'          AND b.gender = 'F' THEN 1 ELSE 0 END) AS int_f,

            SUM(CASE WHEN ab.classification = 'Qualified'            AND b.gender = 'M' THEN 1 ELSE 0 END) AS qual_m,
            SUM(CASE WHEN ab.classification = 'Qualified'            AND b.gender = 'F' THEN 1 ELSE 0 END) AS qual_f,

            SUM(CASE WHEN ab.classification = 'Not Qualified'        AND b.gender = 'M' THEN 1 ELSE 0 END) AS nqual_m,
            SUM(CASE WHEN ab.classification = 'Not Qualified'        AND b.gender = 'F' THEN 1 ELSE 0 END) AS nqual_f,

            SUM(CASE WHEN ab.classification = 'Placed'               AND b.gender = 'M' THEN 1 ELSE 0 END) AS placed_m,
            SUM(CASE WHEN ab.classification = 'Placed'               AND b.gender = 'F' THEN 1 ELSE 0 END) AS placed_f,

            SUM(CASE WHEN ab.classification = 'For Further Interview' AND b.gender = 'M' THEN 1 ELSE 0 END) AS ffi_m,
            SUM(CASE WHEN ab.classification = 'For Further Interview' AND b.gender = 'F' THEN 1 ELSE 0 END) AS ffi_f

        FROM jobFair jf
        LEFT JOIN apply_benef ab ON ab.program_id = (
            SELECT program_id FROM programs
            WHERE name = 'Job Fair' LIMIT 1
        )
        LEFT JOIN beneficiaries b
            ON  b.benef_id              = ab.benef_id
            AND MONTHNAME(b.created_at) = jf.month
            AND YEAR(b.created_at)      = jf.year
        WHERE jf.year = ?
        GROUP BY
            jf.jobfair_id, jf.month, jf.year,
            jf.company_name, jf.vacancy_male, jf.vacancy_female
        ORDER BY
            FIELD(jf.month,
                'January','February','March','April','May','June',
                'July','August','September','October','November','December'),
            jf.company_name
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('i', $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        // Compute totals for each category
        $row['int_total']    = (int)$row['int_m']    + (int)$row['int_f'];
        $row['qual_total']   = (int)$row['qual_m']   + (int)$row['qual_f'];
        $row['nqual_total']  = (int)$row['nqual_m']  + (int)$row['nqual_f'];
        $row['placed_total'] = (int)$row['placed_m'] + (int)$row['placed_f'];
        $row['ffi_total']    = (int)$row['ffi_m']    + (int)$row['ffi_f'];
        $rows[] = $row;
    }
    $stmt->close();

    // Summary card totals
    // Job Vacancies = sum of vacancy_male + vacancy_female from jobFair table
    // Employers     = distinct company_id count for the year
    $totals = [
        'job_vacancies' => 0,
        'employers'     => 0,
        'interviewed'   => 0,
        'qualified'     => 0,
        'placed'        => 0,
    ];

    foreach ($rows as $r) {
        $totals['job_vacancies'] += (int)$r['vacancy_total'];
        $totals['interviewed']   += (int)$r['int_total'];
        $totals['qualified']     += (int)$r['qual_total'];
        $totals['placed']        += (int)$r['placed_total'];
    }

    // Distinct employers participating this year
    $empStmt = $conn->prepare(
        "SELECT COUNT(DISTINCT company_id) AS cnt FROM jobFair WHERE year = ? AND company_id IS NOT NULL"
    );
    if ($empStmt) {
        $empStmt->bind_param('i', $year);
        $empStmt->execute();
        $empRow = $empStmt->get_result()->fetch_assoc();
        $totals['employers'] = (int)($empRow['cnt'] ?? 0);
        $empStmt->close();
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'years' => $years]);
}

// ─── PUT (edit a jobFair row) ────────────────────────────────────────────────
// Accepts: jobfair_id, and any of: month, year, company_name,
//          vacancy_male, vacancy_female
if ($method === 'PUT') {
    global $conn;

    $body = json_decode(file_get_contents('php://input'), true);
    $id   = isset($body['jobfair_id']) ? (int) $body['jobfair_id'] : 0;
    if (!$id) json_error('Missing jobfair_id');

    $allowed      = ['month', 'year', 'company_name', 'vacancy_male', 'vacancy_female'];
    $intFields    = ['year', 'vacancy_male', 'vacancy_female'];
    $sets         = [];
    $types        = '';
    $values       = [];

    foreach ($allowed as $col) {
        if (array_key_exists($col, $body)) {
            $sets[]   = "`$col` = ?";
            $types   .= in_array($col, $intFields) ? 'i' : 's';
            $values[] = in_array($col, $intFields) ? (int) $body[$col] : $body[$col];
        }
    }

    if (empty($sets)) json_error('Nothing to update');

    $types   .= 'i';
    $values[] = $id;

    $stmt = $conn->prepare(
        "UPDATE jobFair SET " . implode(', ', $sets) . " WHERE jobfair_id = ?"
    );
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['updated' => $affected]);
}

// ─── DELETE ?id=5 ────────────────────────────────────────────────────────────
if ($method === 'DELETE') {
    global $conn;

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if (!$id) json_error('Missing id');

    $stmt = $conn->prepare("DELETE FROM jobFair WHERE jobfair_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);