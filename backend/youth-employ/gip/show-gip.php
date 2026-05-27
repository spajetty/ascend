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

// ─── GET ?year=2026&month=3&type=college&search=school ───────────────────────
if ($method === 'GET') {

    $year   = isset($_GET['year'])   ? (int)  $_GET['year']   : (int) date('Y');
    $month  = isset($_GET['month'])  ? (int)  $_GET['month']  : 0;
    $search = isset($_GET['search']) ? trim($_GET['search'])  : '';

    // Available years
    $res   = $conn->query("
        SELECT DISTINCT ib.year
        FROM import_batches ib
        INNER JOIN gip g ON g.batch_id = ib.batch_id
        ORDER BY ib.year DESC
    ");
    $years = [];
    while ($row = $res->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }
    $currentYear = (int) date('Y');
    $years = array_unique(array_merge($years, [$currentYear, $year]));
    rsort($years);

    // Base query — one row per grouped GIP bucket.
    $sql = "
        SELECT
            SHA1(CONCAT(
                ib.year, '|', ib.month,
                '|', LOWER(COALESCE(g.school, '')),
                '|', LOWER(COALESCE(g.office_assignment, '')),
                '|', LOWER(COALESCE(g.type, ''))
            )) AS group_key,
            GROUP_CONCAT(g.gip_id ORDER BY g.gip_id SEPARATOR ',') AS gip_ids,
            ib.month,
            ib.year,
            g.school,
            g.office_assignment,
            LOWER(g.type) AS gip_type,

            SUM(CASE WHEN LOWER(COALESCE(b.sex, '')) = 'male'   THEN 1 ELSE 0 END) AS part_m,
            SUM(CASE WHEN LOWER(COALESCE(b.sex, '')) = 'female' THEN 1 ELSE 0 END) AS part_f

        FROM gip g
        INNER JOIN import_batches ib ON ib.batch_id = g.batch_id
        INNER JOIN beneficiaries b   ON b.benef_id  = g.benef_id
        WHERE ib.year = ?
    ";

    $params = [$year];
    $types  = 'i';

    if ($month > 0) {
        $sql    .= " AND ib.month = ?";
        $types  .= 'i';
        $params[] = $month;
    }

    if ($search !== '') {
        $sql    .= " AND g.school LIKE ?";
        $types  .= 's';
        $params[] = "%$search%";
    }

    $sql .= "
        GROUP BY
            ib.year,
            ib.month,
            g.school,
            g.office_assignment,
            g.type
        ORDER BY ib.month ASC, g.school ASC, g.office_assignment ASC, g.type ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $row['month_num'] = (int) $row['month'];
        $rows[] = $row;
    }
    $stmt->close();

    // Summary cards for the simplified view
    $totals = [
        'participants' => ['m' => 0, 'f' => 0],
        'records'      => 0,
        'lgu'          => ['m' => 0, 'f' => 0, 'records' => 0],
        'dole'         => ['m' => 0, 'f' => 0, 'records' => 0],
    ];
    foreach ($rows as $r) {
        $totals['participants']['m']  += (int) $r['part_m']; $totals['participants']['f']  += (int) $r['part_f'];
        $totals['records']++;
        $bucket = strtolower(trim((string) ($r['gip_type'] ?? '')));
        if ($bucket === 'lgu') {
            $totals['lgu']['m'] += (int) $r['part_m'];
            $totals['lgu']['f'] += (int) $r['part_f'];
            $totals['lgu']['records']++;
        } elseif ($bucket === 'dole') {
            $totals['dole']['m'] += (int) $r['part_m'];
            $totals['dole']['f'] += (int) $r['part_f'];
            $totals['dole']['records']++;
        }
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'years' => $years]);
}

// ─── PUT — edit visible GIP row fields and batch month/year ─────────────────
if ($method === 'PUT') {

    $body = json_decode(file_get_contents('php://input'), true);
    $id   = isset($body['gip_id']) ? (int) $body['gip_id'] : 0;
    if (!$id) json_error('Missing gip_id');

    $allowed = ['month', 'year', 'school', 'office_assignment'];
    $sets = [];
    $types = '';
    $values = [];

    foreach ($allowed as $col) {
        if (array_key_exists($col, $body)) {
            $sets[] = "$col = ?";
            if ($col === 'month' || $col === 'year') {
                $types .= 'i';
                $values[] = (int) $body[$col];
            } else {
                $types .= 's';
                $values[] = trim((string) $body[$col]);
            }
        }
    }

    if (empty($sets)) json_error('Nothing to update');

    $types .= 'i';
    $values[] = $id;

    $stmt = $conn->prepare("UPDATE gip SET " . implode(', ', $sets) . " WHERE gip_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['updated' => $affected]);
}

// ─── DELETE ?id=5 ─────────────────────────────────────────────────────────
if ($method === 'DELETE') {

    $ids = $_GET['ids'] ?? '';
    $ids = is_array($ids) ? $ids : explode(',', (string) $ids);
    $ids = array_values(array_filter(array_map('intval', $ids)));
    if (empty($ids)) json_error('Missing ids');

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $bindTypes = str_repeat('i', count($ids));

    $stmt = $conn->prepare("DELETE FROM gip WHERE gip_id IN ($placeholders)");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($bindTypes, ...$ids);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);