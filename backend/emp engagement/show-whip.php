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

// ─── GET ?year=2026 ──────────────────────────────────────────────────────────
// Returns one row per whip entry with beneficiary name, project name,
// position, date_hired, and the batch month/year.
// ─────────────────────────────────────────────────────────────────────────────
if ($method === 'GET') {
    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    // Available years from import_batches that have whip records
    $res   = $conn->query("
        SELECT DISTINCT ib.year
        FROM whip w
        JOIN import_batches ib ON ib.batch_id = w.batch_id
        ORDER BY ib.year DESC
    ");
    $years = [];
    while ($row = $res->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }
    // Always include current year and requested year
    foreach ([(int) date('Y'), $year] as $y) {
        if (!in_array($y, $years, true)) $years[] = $y;
    }
    rsort($years);

    $monthNames = [
        1=>'January',2=>'February',3=>'March',4=>'April',
        5=>'May',6=>'June',7=>'July',8=>'August',
        9=>'September',10=>'October',11=>'November',12=>'December'
    ];

    // Main query
    $stmt = $conn->prepare("
        SELECT
            w.whip_id,
            w.benef_id,
            w.project_id,
            w.batch_id,
            w.position,
            w.date_hired,
            ib.month,
            ib.year,
            ib.file_name,
            CONCAT(b.first_name, ' ',
                   COALESCE(CONCAT(LEFT(b.middle_name,1),'. '),''),
                   b.last_name,
                   COALESCE(CONCAT(' ',b.suffix),'')) AS full_name,
            b.sex,
            p.project_title
        FROM whip w
        JOIN import_batches ib ON ib.batch_id = w.batch_id
        JOIN beneficiaries  b  ON b.benef_id  = w.benef_id
        JOIN projects       p  ON p.project_id = w.project_id
        WHERE ib.year = ?
        ORDER BY ib.month ASC, b.last_name ASC, b.first_name ASC
    ");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('i', $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $row['month_name'] = $monthNames[(int)$row['month']] ?? 'Unknown';
        $rows[] = $row;
    }
    $stmt->close();

    // Totals
    $male   = 0;
    $female = 0;
    $projectSet = [];
    foreach ($rows as $r) {
        if (strtolower($r['sex'] ?? '') === 'male')   $male++;
        if (strtolower($r['sex'] ?? '') === 'female') $female++;
        $projectSet[$r['project_id']] = true;
    }

    json_ok([
        'rows'   => $rows,
        'years'  => $years,
        'totals' => [
            'total'    => count($rows),
            'male'     => $male,
            'female'   => $female,
            'projects' => count($projectSet),
        ],
    ]);
}

// ─── DELETE ?id=<whip_id> ─────────────────────────────────────────────────────
if ($method === 'DELETE') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if (!$id) json_error('Missing id');

    $stmt = $conn->prepare("DELETE FROM whip WHERE whip_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);