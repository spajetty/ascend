<?php
require_once __DIR__ . '/../../includes/auth-check.php';
require_once __DIR__ . '/../../vendor/autoload.php';

// .env lives at C:\laragon\www\ascend\api\
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

// ─── Helpers ───────────────────────────────────────────────────────
function json_error(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

function json_ok(mixed $data = null): void {
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

// ───────────────────────────────────────────────────────────────────
// GET  ?year=2026
// One row per import batch (month + year).
// Counts beneficiaries by classification x sex via jobmatch.
//
// NOTE: sex stored as 'Male'/'Female', classification as 'Placed/Hots' etc.
// ───────────────────────────────────────────────────────────────────
if ($method === 'GET') {

    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    // Available years that actually have jobmatch data
    $res = $conn->query("
        SELECT DISTINCT ib.year
        FROM import_batches ib
        INNER JOIN jobmatch jm ON jm.batch_id = ib.batch_id
        ORDER BY ib.year DESC
    ");

    $years = [];
    while ($row = $res->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }

    // Always include the current year and the requested year in the filter options
    $currentYear = (int) date('Y');
    $years = array_merge($years, [$currentYear, $year]);
    $years = array_unique($years);
    rsort($years);

    // LPAD ensures '11' not '11' causes issues; STR_TO_DATE needs zero-padded month
    // sex values: 'Male' / 'Female'
    // classification values from Excel: Registered, Referred, Interviewed,
    //   Qualified, Not Qualified, Placed/Hots, For Further Interview
    $sql = "
        SELECT
            ib.batch_id,
            ib.month,
            ib.year,
            CASE ib.month
                WHEN 1 THEN 'January'
                WHEN 2 THEN 'February'
                WHEN 3 THEN 'March'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'May'
                WHEN 6 THEN 'June'
                WHEN 7 THEN 'July'
                WHEN 8 THEN 'August'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'October'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'December'
                ELSE ''
            END AS month_name,

            SUM(CASE WHEN b.classification = 'Registered'            AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS reg_m,
            SUM(CASE WHEN b.classification = 'Registered'            AND b.sex = 'Female' THEN 1 ELSE 0 END) AS reg_f,

            SUM(CASE WHEN b.classification = 'Referred'              AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS ref_m,
            SUM(CASE WHEN b.classification = 'Referred'              AND b.sex = 'Female' THEN 1 ELSE 0 END) AS ref_f,

            SUM(CASE WHEN b.classification = 'Interviewed'           AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS int_m,
            SUM(CASE WHEN b.classification = 'Interviewed'           AND b.sex = 'Female' THEN 1 ELSE 0 END) AS int_f,

            SUM(CASE WHEN b.classification = 'Qualified'             AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS qual_m,
            SUM(CASE WHEN b.classification = 'Qualified'             AND b.sex = 'Female' THEN 1 ELSE 0 END) AS qual_f,

            SUM(CASE WHEN b.classification = 'Not Qualified'         AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS nqual_m,
            SUM(CASE WHEN b.classification = 'Not Qualified'         AND b.sex = 'Female' THEN 1 ELSE 0 END) AS nqual_f,

            SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed') AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS placed_m,
            SUM(CASE WHEN b.classification IN ('Placed/Hots','Placed') AND b.sex = 'Female' THEN 1 ELSE 0 END) AS placed_f,

            SUM(CASE WHEN b.classification = 'For Further Interview' AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS ffi_m,
            SUM(CASE WHEN b.classification = 'For Further Interview' AND b.sex = 'Female' THEN 1 ELSE 0 END) AS ffi_f

        FROM import_batches ib

        INNER JOIN jobmatch jm
            ON jm.batch_id = ib.batch_id

        INNER JOIN beneficiaries b
            ON b.benef_id = jm.benef_id

        WHERE ib.year = ?

        GROUP BY ib.batch_id, ib.month, ib.year

        ORDER BY ib.month ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('i', $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $row['jobmatch_id'] = (int) $row['batch_id'];
        $row['month']       = $row['month_name'] . ' ' . $row['year'];
        $rows[] = $row;
    }
    $stmt->close();

    // Summary card totals
    $totals = ['registered' => 0, 'referred' => 0, 'interviewed' => 0, 'placed' => 0];
    foreach ($rows as $r) {
        $totals['registered']  += $r['reg_m']    + $r['reg_f'];
        $totals['referred']    += $r['ref_m']    + $r['ref_f'];
        $totals['interviewed'] += $r['int_m']    + $r['int_f'];
        $totals['placed']      += $r['placed_m'] + $r['placed_f'];
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'years' => $years]);
}

// ───────────────────────────────────────────────────────────────────
// PUT  — update batch month/year
// Body: { jobmatch_id: <batch_id>, month: N, year: N }
// ───────────────────────────────────────────────────────────────────
if ($method === 'PUT') {

    $body     = json_decode(file_get_contents('php://input'), true);
    $batch_id = isset($body['jobmatch_id']) ? (int) $body['jobmatch_id'] : 0;
    if (!$batch_id) json_error('Missing jobmatch_id');

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

    if (empty($sets)) json_error('Nothing to update');

    $types   .= 'i';
    $values[] = $batch_id;

    $stmt = $conn->prepare(
        "UPDATE import_batches SET " . implode(', ', $sets) . " WHERE batch_id = ?"
    );
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['updated' => $affected]);
}

// ───────────────────────────────────────────────────────────────────
// DELETE  ?id=<batch_id>
// Nulls jobmatch.batch_id first (no cascade FK), then deletes batch.
// ───────────────────────────────────────────────────────────────────
if ($method === 'DELETE') {

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if (!$id) json_error('Missing id');

    $conn->begin_transaction();
    try {
        $s1 = $conn->prepare("UPDATE jobmatch SET batch_id = NULL WHERE batch_id = ?");
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