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

// ─── GET ?year=2026 ──────────────────────────────────────────────────────────
if ($method === 'GET') {
    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    // Available years for the dropdown
    $res   = $conn->query("SELECT DISTINCT year FROM whip ORDER BY year DESC");
    $years = [];
    while ($row = $res->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }
    if (!in_array($year, $years, true)) {
        $years[] = $year;
        rsort($years);
    }

    $stmt = $conn->prepare("
        SELECT whip_id, month, year, male, female, (male + female) AS total, project_name, created_at
        FROM whip
        WHERE year = ?
        ORDER BY FIELD(month,
            'January','February','March','April','May','June',
            'July','August','September','October','November','December')
    ");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('i', $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();

    // Summary totals for the cards
    $totals = ['total' => 0, 'male' => 0, 'female' => 0, 'projects' => 0];
    foreach ($rows as $r) {
        $totals['male']     += (int) $r['male'];
        $totals['female']   += (int) $r['female'];
        $totals['total']    += (int) $r['total'];
        $totals['projects'] += 1;
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'years' => $years]);
}

// ─── POST (add a new whip row) ───────────────────────────────────────────────
if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);

    $month        = trim($body['month']        ?? '');
    $year         = isset($body['year'])        ? (int) $body['year']   : 0;
    $male         = isset($body['male'])        ? (int) $body['male']   : 0;
    $female       = isset($body['female'])      ? (int) $body['female'] : 0;
    $project_name = trim($body['project_name'] ?? '');

    if (!$month || !$year) json_error('Month and year are required');

    $stmt = $conn->prepare("
        INSERT INTO whip (month, year, male, female, project_name)
        VALUES (?, ?, ?, ?, ?)
    ");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('siiis', $month, $year, $male, $female, $project_name);
    $stmt->execute();
    $newId = $conn->insert_id;
    $stmt->close();

    json_ok(['whip_id' => $newId]);
}

// ─── PUT (edit an existing whip row) ────────────────────────────────────────
if ($method === 'PUT') {
    $body = json_decode(file_get_contents('php://input'), true);
    $id   = isset($body['whip_id']) ? (int) $body['whip_id'] : 0;
    if (!$id) json_error('Missing whip_id');

    $allowed = ['month' => 's', 'year' => 'i', 'male' => 'i', 'female' => 'i', 'project_name' => 's'];
    $sets    = [];
    $types   = '';
    $values  = [];

    foreach ($allowed as $col => $type) {
        if (array_key_exists($col, $body)) {
            $sets[]   = "$col = ?";
            $types   .= $type;
            $values[] = ($type === 'i') ? (int) $body[$col] : trim($body[$col]);
        }
    }

    if (empty($sets)) json_error('Nothing to update');

    $types   .= 'i';
    $values[] = $id;

    $stmt = $conn->prepare("UPDATE whip SET " . implode(', ', $sets) . " WHERE whip_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['updated' => $affected]);
}

// ─── DELETE ?id=5 ────────────────────────────────────────────────────────────
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