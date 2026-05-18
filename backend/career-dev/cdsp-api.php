<?php
require_once __DIR__ . '../../includes/auth-check.php';
require_once __DIR__ . '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
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
if ($method === 'GET') {
    global $conn;

    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    // Available years for dropdown
    $res   = $conn->query("SELECT DISTINCT YEAR(date) AS yr FROM careerdev ORDER BY yr DESC");
    $years = [];
    while ($row = $res->fetch_assoc()) $years[] = (int) $row['yr'];
    if (!in_array($year, $years, true)) { $years[] = $year; rsort($years); }

    // All rows for the selected year, ordered by date
    $stmt = $conn->prepare("
        SELECT cdsp_id, date, school, cdsp_m, cdsp_f, (cdsp_m + cdsp_f) AS total
        FROM careerdev
        WHERE YEAR(date) = ?
        ORDER BY date ASC
    ");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param('i', $year);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    $stmt->close();

    // Summary card totals
    $totals = [
        'sessions' => count($rows),
        'total_m'  => 0,
        'total_f'  => 0,
        'total'    => 0,
    ];
    foreach ($rows as $r) {
        $totals['total_m'] += (int) $r['cdsp_m'];
        $totals['total_f'] += (int) $r['cdsp_f'];
        $totals['total']   += (int) $r['total'];
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'years' => $years]);
}

// ─── PUT (edit a careerdev row) ───────────────────────────────────────────────
if ($method === 'PUT') {
    global $conn;

    $body = json_decode(file_get_contents('php://input'), true);
    $id   = isset($body['cdsp_id']) ? (int) $body['cdsp_id'] : 0;
    if (!$id) json_error('Missing cdsp_id');

    $allowed   = ['date', 'school', 'cdsp_m', 'cdsp_f'];
    $intFields = ['cdsp_m', 'cdsp_f'];
    $sets = []; $types = ''; $values = [];

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

    $stmt = $conn->prepare("UPDATE careerdev SET " . implode(', ', $sets) . " WHERE cdsp_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['updated' => $affected]);
}

// ─── DELETE ?id=5 ─────────────────────────────────────────────────────────────
if ($method === 'DELETE') {
    global $conn;

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if (!$id) json_error('Missing id');

    $stmt = $conn->prepare("DELETE FROM careerdev WHERE cdsp_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);