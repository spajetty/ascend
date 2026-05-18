<?php
require_once __DIR__ . '../../includes/auth-check.php';
require_once __DIR__ . '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$db   = $_ENV['DB_NAME'] ?? null;

if (!$db) { echo json_encode(['success' => false, 'error' => 'Missing DB_NAME in .env']); exit; }

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { echo json_encode(['success' => false, 'error' => 'DB connection failed: ' . $conn->connect_error]); exit; }

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

// ─── GET ?year=2026&type=college&search=davao ────────────────────────────────
if ($method === 'GET') {
    global $conn;

    $year   = isset($_GET['year'])   ? (int)   $_GET['year']   : (int) date('Y');
    $type   = isset($_GET['type'])   ? trim($_GET['type'])     : '';   // 'college' | 'shs' | ''
    $search = isset($_GET['search']) ? trim($_GET['search'])   : '';

    // Available years — derived from contract_period string e.g. "January – March 2026"
    // We extract the year at the end of the contract_period string
    $res   = $conn->query("SELECT DISTINCT SUBSTRING(contract_period, -4) AS yr FROM gip ORDER BY yr DESC");
    $years = [];
    while ($row = $res->fetch_assoc()) {
        $y = (int) $row['yr'];
        if ($y > 2000) $years[] = $y;
    }
    $years = array_unique($years);
    rsort($years);
    if (!in_array($year, $years, true)) { $years[] = $year; rsort($years); }

    // M/F counts come from beneficiaries linked to the GIP program via apply_benef
    // classification values: 'Participants', 'Inquired', 'Referred', 'Interviewed',
    //                        'PESO-Accepted', 'Privately-Accepted', 'Not Proceeded'
    $sql = "
        SELECT
            g.gip_id,
            g.contract_period,
            g.school,
            g.college_or_shs,
            g.course,
            g.office_assignment,
            g.required_hours,
            -- Participants
            SUM(CASE WHEN ab.classification = 'Participants'        AND b.gender = 'M' THEN 1 ELSE 0 END) AS part_m,
            SUM(CASE WHEN ab.classification = 'Participants'        AND b.gender = 'F' THEN 1 ELSE 0 END) AS part_f,
            -- Inquired
            SUM(CASE WHEN ab.classification = 'Inquired'            AND b.gender = 'M' THEN 1 ELSE 0 END) AS inq_m,
            SUM(CASE WHEN ab.classification = 'Inquired'            AND b.gender = 'F' THEN 1 ELSE 0 END) AS inq_f,
            -- Referred
            SUM(CASE WHEN ab.classification = 'Referred'            AND b.gender = 'M' THEN 1 ELSE 0 END) AS ref_m,
            SUM(CASE WHEN ab.classification = 'Referred'            AND b.gender = 'F' THEN 1 ELSE 0 END) AS ref_f,
            -- Interviewed
            SUM(CASE WHEN ab.classification = 'Interviewed'         AND b.gender = 'M' THEN 1 ELSE 0 END) AS int_m,
            SUM(CASE WHEN ab.classification = 'Interviewed'         AND b.gender = 'F' THEN 1 ELSE 0 END) AS int_f,
            -- PESO-Accepted
            SUM(CASE WHEN ab.classification = 'PESO-Accepted'       AND b.gender = 'M' THEN 1 ELSE 0 END) AS peso_m,
            SUM(CASE WHEN ab.classification = 'PESO-Accepted'       AND b.gender = 'F' THEN 1 ELSE 0 END) AS peso_f,
            -- Privately-Accepted
            SUM(CASE WHEN ab.classification = 'Privately-Accepted'  AND b.gender = 'M' THEN 1 ELSE 0 END) AS priv_m,
            SUM(CASE WHEN ab.classification = 'Privately-Accepted'  AND b.gender = 'F' THEN 1 ELSE 0 END) AS priv_f,
            -- Not Proceeded
            SUM(CASE WHEN ab.classification = 'Not Proceeded'       AND b.gender = 'M' THEN 1 ELSE 0 END) AS notp_m,
            SUM(CASE WHEN ab.classification = 'Not Proceeded'       AND b.gender = 'F' THEN 1 ELSE 0 END) AS notp_f
        FROM gip g
        LEFT JOIN apply_benef ab ON ab.program_id = (
            SELECT program_id FROM programs WHERE name = 'GIP' LIMIT 1
        )
        LEFT JOIN beneficiaries b
            ON  b.benef_id         = ab.benef_id
            AND g.school           = (SELECT school_name FROM apply_benef ab2
                                       JOIN beneficiaries b2 ON b2.benef_id = ab2.benef_id
                                       WHERE b2.benef_id = b.benef_id LIMIT 1)
        WHERE SUBSTRING(g.contract_period, -4) = ?
    ";

    $params = [(string) $year];
    $types  = 's';

    if ($type !== '') {
        $sql    .= " AND LOWER(g.college_or_shs) = ?";
        $types  .= 's';
        $params[] = strtolower($type);
    }

    if ($search !== '') {
        $sql    .= " AND g.school LIKE ?";
        $types  .= 's';
        $params[] = "%$search%";
    }

    $sql .= " GROUP BY g.gip_id, g.contract_period, g.school, g.college_or_shs, g.course, g.office_assignment, g.required_hours
              ORDER BY g.contract_period ASC, g.school ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    $stmt->close();

    // Card totals
    $totals = [
        'participants' => ['m' => 0, 'f' => 0],
        'inquired'     => ['m' => 0, 'f' => 0],
        'referred'     => ['m' => 0, 'f' => 0],
        'interviewed'  => ['m' => 0, 'f' => 0],
        'peso'         => ['m' => 0, 'f' => 0],
        'private'      => ['m' => 0, 'f' => 0],
        'not_proceeded'=> ['m' => 0, 'f' => 0],
    ];
    foreach ($rows as $r) {
        $totals['participants']['m'] += (int) $r['part_m']; $totals['participants']['f'] += (int) $r['part_f'];
        $totals['inquired']['m']     += (int) $r['inq_m'];  $totals['inquired']['f']     += (int) $r['inq_f'];
        $totals['referred']['m']     += (int) $r['ref_m'];  $totals['referred']['f']     += (int) $r['ref_f'];
        $totals['interviewed']['m']  += (int) $r['int_m'];  $totals['interviewed']['f']  += (int) $r['int_f'];
        $totals['peso']['m']         += (int) $r['peso_m']; $totals['peso']['f']         += (int) $r['peso_f'];
        $totals['private']['m']      += (int) $r['priv_m']; $totals['private']['f']      += (int) $r['priv_f'];
        $totals['not_proceeded']['m']+= (int) $r['notp_m']; $totals['not_proceeded']['f']+= (int) $r['notp_f'];
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'years' => $years]);
}

// ─── PUT — edit a gip row ────────────────────────────────────────────────────
if ($method === 'PUT') {
    global $conn;

    $body = json_decode(file_get_contents('php://input'), true);
    $id   = isset($body['gip_id']) ? (int) $body['gip_id'] : 0;
    if (!$id) json_error('Missing gip_id');

    $allowed = ['contract_period', 'school', 'college_or_shs', 'course', 'office_assignment', 'required_hours'];
    $sets = []; $types = ''; $values = [];

    foreach ($allowed as $col) {
        if (array_key_exists($col, $body)) {
            $sets[]   = "$col = ?";
            $types   .= ($col === 'required_hours') ? 'i' : 's';
            $values[] = $body[$col];
        }
    }

    if (empty($sets)) json_error('Nothing to update');

    $types   .= 'i';
    $values[] = $id;

    $stmt = $conn->prepare("UPDATE gip SET " . implode(', ', $sets) . " WHERE gip_id = ?");
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

    $stmt = $conn->prepare("DELETE FROM gip WHERE gip_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);