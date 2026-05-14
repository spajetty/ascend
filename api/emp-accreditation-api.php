<?php
require_once __DIR__ . '/../includes/auth-check.php';
require_once __DIR__ . '/../vendor/autoload.php';

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

// ─── Helpers ─────────────────────────────────────────────────────────────────
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
    $res   = $conn->query("SELECT DISTINCT year FROM employers ORDER BY year DESC");
    $years = [];
    while ($row = $res->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }
    if (!in_array($year, $years, true)) {
        $years[] = $year;
        rsort($years);
    }

    // Fetch all employers for the selected year
    $stmt = $conn->prepare("
        SELECT
            company_id,
            company_name,
            month,
            year,
            accreditation,
            est_type,
            industry,
            city,
            created_at
        FROM employers
        WHERE year = ?
        ORDER BY FIELD(month,
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'),
            company_name
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

    // Card counts for selected year
    $newCount     = 0;
    $renewCount   = 0;
    $activeSet    = [];
    foreach ($rows as $r) {
        if ($r['accreditation'] === 'new')   $newCount++;
        if ($r['accreditation'] === 'renew') $renewCount++;
        $activeSet[$r['company_name']] = true;
    }

    // Total unique companies across ALL years
    $totalRes    = $conn->query("SELECT COUNT(DISTINCT company_name) AS cnt FROM employers");
    $totalRow    = $totalRes->fetch_assoc();
    $totalUnique = (int) $totalRow['cnt'];

    json_ok([
        'rows'   => $rows,
        'years'  => $years,
        'totals' => [
            'total'   => $totalUnique,
            'new'     => $newCount,
            'renewed' => $renewCount,
            'active'  => count($activeSet),
        ],
    ]);
}

// ─── POST (add new employer) ──────────────────────────────────────────────────
if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);

    $required = ['company_name', 'month', 'year', 'accreditation', 'est_type', 'industry', 'city'];
    foreach ($required as $field) {
        if (empty($body[$field])) json_error("Missing required field: $field");
    }

    $validAccreditation = ['new', 'renew'];
    if (!in_array(strtolower($body['accreditation']), $validAccreditation, true)) {
        json_error('accreditation must be "new" or "renew"');
    }

    $stmt = $conn->prepare("
        INSERT INTO employers (company_name, month, year, accreditation, est_type, industry, city)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param(
        'ssissss',
        $body['company_name'],
        $body['month'],
        $body['year'],
        $body['accreditation'],
        $body['est_type'],
        $body['industry'],
        $body['city']
    );
    $stmt->execute();
    $newId = $conn->insert_id;
    $stmt->close();

    json_ok(['company_id' => $newId]);
}

// ─── PUT (edit an employer) ───────────────────────────────────────────────────
if ($method === 'PUT') {
    $body = json_decode(file_get_contents('php://input'), true);
    $id   = isset($body['company_id']) ? (int) $body['company_id'] : 0;
    if (!$id) json_error('Missing company_id');

    $allowed = ['company_name', 'month', 'year', 'accreditation', 'est_type', 'industry', 'city'];
    $sets    = [];
    $types   = '';
    $values  = [];

    foreach ($allowed as $col) {
        if (array_key_exists($col, $body)) {
            $sets[]   = "$col = ?";
            $types   .= ($col === 'year') ? 'i' : 's';
            $values[] = $body[$col];
        }
    }

    if (empty($sets)) json_error('Nothing to update');

    $types   .= 'i';
    $values[] = $id;

    $stmt = $conn->prepare("UPDATE employers SET " . implode(', ', $sets) . " WHERE company_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['updated' => $affected]);
}

// ─── DELETE ?id=5 ─────────────────────────────────────────────────────────────
if ($method === 'DELETE') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if (!$id) json_error('Missing id');

    $stmt = $conn->prepare("DELETE FROM employers WHERE company_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);