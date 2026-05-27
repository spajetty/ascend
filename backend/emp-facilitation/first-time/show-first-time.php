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
// Counts beneficiaries by classification x sex via firstjobseek.
// occ_permit and health_card are summed per batch from firstjobseek.
// ───────────────────────────────────────────────────────────────────
if ($method === 'GET') {

    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    // Available years that actually have firstjobseek data
    $res = $conn->query("
        SELECT DISTINCT ib.year
        FROM import_batches ib
        INNER JOIN firstjobseek fj ON fj.batch_id = ib.batch_id
        ORDER BY ib.year DESC
    ");

    $years = [];
    while ($row = $res->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }

    // Always include current year and the requested year
    $currentYear = (int) date('Y');
    $years = array_merge($years, [$currentYear, $year]);
    $years = array_unique($years);
    rsort($years);

    // One row per batch, counts from beneficiaries joined via firstjobseek
    // occ_permit and health_card are stored per-row in firstjobseek and summed
    $sql = "
        SELECT
            ib.batch_id,
            ib.month,
            ib.year,
            CASE ib.month
                WHEN 1  THEN 'January'
                WHEN 2  THEN 'February'
                WHEN 3  THEN 'March'
                WHEN 4  THEN 'April'
                WHEN 5  THEN 'May'
                WHEN 6  THEN 'June'
                WHEN 7  THEN 'July'
                WHEN 8  THEN 'August'
                WHEN 9  THEN 'September'
                WHEN 10 THEN 'October'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'December'
                ELSE ''
            END AS month_name,

            -- occ_permit and health_card are flags (0/1) per firstjobseek row
            SUM(fj.occ_permit)  AS occ_permit,
            SUM(fj.health_card) AS health_card,

            -- Classification x sex counts from beneficiaries
            SUM(CASE WHEN b.sex = 'Male'   THEN 1 ELSE 0 END) AS reg_m,
            SUM(CASE WHEN b.sex = 'Female' THEN 1 ELSE 0 END) AS reg_f,

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

        INNER JOIN firstjobseek fj
            ON fj.batch_id = ib.batch_id

        INNER JOIN beneficiaries b
            ON b.benef_id = fj.benef_id

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
        // Match the field names expected by first-time.php frontend
        $row['jobseek_id'] = (int) $row['batch_id'];
        $row['month']      = $row['month_name'];
        $rows[] = $row;
    }
    $stmt->close();

    // Summary card totals
    $totals = ['jobseekers' => 0, 'occ_permit' => 0, 'health_card' => 0, 'placed' => 0];
    foreach ($rows as $r) {
        $totals['jobseekers'] += (int)$r['reg_m']    + (int)$r['reg_f'];
        $totals['occ_permit'] += (int)$r['occ_permit'];
        $totals['health_card']+= (int)$r['health_card'];
        $totals['placed']     += (int)$r['placed_m'] + (int)$r['placed_f'];
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'years' => $years]);
}

// ───────────────────────────────────────────────────────────────────
// PUT  — update occ_permit / health_card on all firstjobseek rows
//         belonging to the given batch, OR update batch month/year.
// Body: { jobseek_id: <batch_id>, occ_permit: N, health_card: N }
//   or: { jobseek_id: <batch_id>, month: N, year: N }
// ───────────────────────────────────────────────────────────────────
if ($method === 'PUT') {

    $body     = json_decode(file_get_contents('php://input'), true);
    $batch_id = isset($body['jobseek_id']) ? (int) $body['jobseek_id'] : 0;
    if (!$batch_id) json_error('Missing jobseek_id');

    $affected = 0;

    // Update occ_permit / health_card on all firstjobseek rows for this batch
    $fjAllowed = ['occ_permit', 'health_card'];
    $fjSets = []; $fjTypes = ''; $fjValues = [];

    foreach ($fjAllowed as $col) {
        if (array_key_exists($col, $body)) {
            $fjSets[]   = "$col = ?";
            $fjTypes   .= 'i';
            $fjValues[] = (int) $body[$col];
        }
    }

    if (!empty($fjSets)) {
        $fjTypes   .= 'i';
        $fjValues[] = $batch_id;
        $stmt = $conn->prepare(
            "UPDATE firstjobseek SET " . implode(', ', $fjSets) . " WHERE batch_id = ?"
        );
        if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
        $stmt->bind_param($fjTypes, ...$fjValues);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
    }

    // Update month/year on import_batches if provided
    $ibAllowed = ['month', 'year'];
    $ibSets = []; $ibTypes = ''; $ibValues = [];

    foreach ($ibAllowed as $col) {
        if (array_key_exists($col, $body)) {
            $ibSets[]   = "$col = ?";
            $ibTypes   .= 'i';
            $ibValues[] = (int) $body[$col];
        }
    }

    if (!empty($ibSets)) {
        $ibTypes   .= 'i';
        $ibValues[] = $batch_id;
        $stmt = $conn->prepare(
            "UPDATE import_batches SET " . implode(', ', $ibSets) . " WHERE batch_id = ?"
        );
        if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
        $stmt->bind_param($ibTypes, ...$ibValues);
        $stmt->execute();
        $affected += $stmt->affected_rows;
        $stmt->close();
    }

    if (empty($fjSets) && empty($ibSets)) json_error('Nothing to update');

    json_ok(['updated' => $affected]);
}

// ───────────────────────────────────────────────────────────────────
// DELETE  ?id=<batch_id>
// Nulls firstjobseek.batch_id first, then deletes the batch.
// ───────────────────────────────────────────────────────────────────
if ($method === 'DELETE') {

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if (!$id) json_error('Missing id');

    $conn->begin_transaction();
    try {
        $s1 = $conn->prepare("UPDATE firstjobseek SET batch_id = NULL WHERE batch_id = ?");
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