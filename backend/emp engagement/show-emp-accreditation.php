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
// One row per accreditation entry (employers_accreditations JOIN employers).
// ─────────────────────────────────────────────────────────────────────────────
if ($method === 'GET') {
    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    // Available years from employers_accreditations
    $res   = $conn->query("SELECT DISTINCT year FROM employers_accreditations ORDER BY year DESC");
    $years = [];
    while ($row = $res->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }
    $years = array_unique(array_merge($years, [(int) date('Y'), $year]));
    rsort($years);

    // Main query — all accreditation records for the selected year
    $stmt = $conn->prepare("
        SELECT
            ea.accreditation_id,
            ea.company_id,
            ea.status,
            ea.month,
            ea.year,
            ea.created_at,
            e.company_name,
            e.est_type,
            e.industry,
            e.city
        FROM employers_accreditations ea
        INNER JOIN employers e ON e.company_id = ea.company_id
        WHERE ea.year = ?
        ORDER BY ea.month ASC, e.company_name ASC
    ");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('i', $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $monthNames = [
        1=>'January',2=>'February',3=>'March',4=>'April',
        5=>'May',6=>'June',7=>'July',8=>'August',
        9=>'September',10=>'October',11=>'November',12=>'December'
    ];

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $row['month_name'] = $monthNames[(int)$row['month']] ?? 'Unknown';
        $rows[] = $row;
    }
    $stmt->close();

    // Card totals for selected year
    $newCount   = 0;
    $renewCount = 0;
    $activeSet  = [];
    foreach ($rows as $r) {
        if ($r['status'] === 'new')   $newCount++;
        if ($r['status'] === 'renew') $renewCount++;
        $activeSet[$r['company_id']] = true;
    }

    // Total unique employers ever accredited (all years)
    $totalRes    = $conn->query("SELECT COUNT(DISTINCT company_id) AS cnt FROM employers_accreditations");
    $totalUnique = (int) $totalRes->fetch_assoc()['cnt'];

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

// ─── POST — add new accreditation record ─────────────────────────────────────
// Body: { company_id, status, month, year }
//   OR: { company_name, est_type, industry, city, status, month, year }
//       (if company_name is provided without company_id, look up or create the employer first)
// ─────────────────────────────────────────────────────────────────────────────
if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);

    // Resolve company_id
    $company_id = isset($body['company_id']) ? (int) $body['company_id'] : 0;

    if (!$company_id && !empty($body['company_name'])) {
        // Look up by name first
        $ls = $conn->prepare("SELECT company_id FROM employers WHERE company_name = ? LIMIT 1");
        $ls->bind_param('s', $body['company_name']);
        $ls->execute();
        $lr = $ls->get_result()->fetch_assoc();
        $ls->close();

        if ($lr) {
            $company_id = (int) $lr['company_id'];
        } else {
            // Insert new employer
            $ins = $conn->prepare(
                "INSERT INTO employers (company_name, est_type, industry, city) VALUES (?, ?, ?, ?)"
            );
            $estType  = $body['est_type']  ?? null;
            $industry = $body['industry']  ?? null;
            $city     = $body['city']      ?? null;
            $ins->bind_param('ssss', $body['company_name'], $estType, $industry, $city);
            $ins->execute();
            $company_id = $conn->insert_id;
            $ins->close();
        }
    }

    if (!$company_id) json_error('Missing company_id or company_name');

    $status = strtolower($body['status'] ?? '');
    if (!in_array($status, ['new', 'renew'], true)) json_error('status must be "new" or "renew"');

    $month = isset($body['month']) ? (int) $body['month'] : 0;
    $year  = isset($body['year'])  ? (int) $body['year']  : 0;
    if ($month < 1 || $month > 12) json_error('Invalid month (1–12)');
    if ($year  < 2000)             json_error('Invalid year');

    $stmt = $conn->prepare(
        "INSERT INTO employers_accreditations (company_id, status, month, year) VALUES (?, ?, ?, ?)"
    );
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('isii', $company_id, $status, $month, $year);
    $stmt->execute();
    $newId = $conn->insert_id;
    $stmt->close();

    json_ok(['accreditation_id' => $newId, 'company_id' => $company_id]);
}

// ─── PUT — edit an accreditation record ──────────────────────────────────────
// Body: { accreditation_id, status?, month?, year? }
// Also allows updating employer fields: { est_type?, industry?, city? }
// ─────────────────────────────────────────────────────────────────────────────
if ($method === 'PUT') {
    $body = json_decode(file_get_contents('php://input'), true);
    $id   = isset($body['accreditation_id']) ? (int) $body['accreditation_id'] : 0;
    if (!$id) json_error('Missing accreditation_id');

    // Update employers_accreditations fields
    $eaAllowed = ['status', 'month', 'year'];
    $eaSets = []; $eaTypes = ''; $eaValues = [];
    foreach ($eaAllowed as $col) {
        if (array_key_exists($col, $body)) {
            $eaSets[]   = "$col = ?";
            $eaTypes   .= ($col === 'status') ? 's' : 'i';
            $eaValues[] = ($col === 'status') ? strtolower($body[$col]) : (int)$body[$col];
        }
    }
    if (!empty($eaSets)) {
        $eaTypes   .= 'i';
        $eaValues[] = $id;
        $stmt = $conn->prepare(
            "UPDATE employers_accreditations SET " . implode(', ', $eaSets) . " WHERE accreditation_id = ?"
        );
        if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
        $stmt->bind_param($eaTypes, ...$eaValues);
        $stmt->execute();
        $stmt->close();
    }

    // Update employer fields if provided (need company_id)
    $empAllowed = ['company_name', 'est_type', 'industry', 'city'];
    $empSets = []; $empTypes = ''; $empValues = [];
    foreach ($empAllowed as $col) {
        if (array_key_exists($col, $body)) {
            $empSets[]   = "$col = ?";
            $empTypes   .= 's';
            $empValues[] = $body[$col];
        }
    }
    if (!empty($empSets) && !empty($body['company_id'])) {
        $cid = (int) $body['company_id'];
        $empTypes   .= 'i';
        $empValues[] = $cid;
        $stmt = $conn->prepare(
            "UPDATE employers SET " . implode(', ', $empSets) . " WHERE company_id = ?"
        );
        if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
        $stmt->bind_param($empTypes, ...$empValues);
        $stmt->execute();
        $stmt->close();
    }

    if (empty($eaSets) && empty($empSets)) json_error('Nothing to update');

    json_ok(['updated' => $id]);
}

// ─── DELETE ?id=<accreditation_id> ───────────────────────────────────────────
if ($method === 'DELETE') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if (!$id) json_error('Missing id');

    $stmt = $conn->prepare("DELETE FROM employers_accreditations WHERE accreditation_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);