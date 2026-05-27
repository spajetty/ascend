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

// ─── GET ?year=2026&type=college&search=davao ─────────────────────────────
if ($method === 'GET') {

    $year   = isset($_GET['year'])   ? (int)   $_GET['year']   : (int) date('Y');
    $type   = isset($_GET['type'])   ? trim($_GET['type'])     : '';   // 'college' | 'shs' | ''
    $search = isset($_GET['search']) ? trim($_GET['search'])   : '';

    // Available years derived from import_batches linked to gip rows
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

    // Each gip row = one beneficiary. We join beneficiaries to get:
    //   - sex (M/F) for the M/F columns
    //   - classification to determine which count column gets the 1
    //
    // classification values stored in beneficiaries.classification:
    //   Participants, Inquired, Referred, Interviewed,
    //   PESO-Accepted, Privately-Accepted, Not Proceeded
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
            SUM(CASE WHEN b.classification = 'Participants'       AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS part_m,
            SUM(CASE WHEN b.classification = 'Participants'       AND b.sex = 'Female' THEN 1 ELSE 0 END) AS part_f,
            -- Inquired
            SUM(CASE WHEN b.classification = 'Inquired'           AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS inq_m,
            SUM(CASE WHEN b.classification = 'Inquired'           AND b.sex = 'Female' THEN 1 ELSE 0 END) AS inq_f,
            -- Referred
            SUM(CASE WHEN b.classification = 'Referred'           AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS ref_m,
            SUM(CASE WHEN b.classification = 'Referred'           AND b.sex = 'Female' THEN 1 ELSE 0 END) AS ref_f,
            -- Interviewed
            SUM(CASE WHEN b.classification = 'Interviewed'        AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS int_m,
            SUM(CASE WHEN b.classification = 'Interviewed'        AND b.sex = 'Female' THEN 1 ELSE 0 END) AS int_f,
            -- PESO-Accepted
            SUM(CASE WHEN b.classification = 'PESO-Accepted'      AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS peso_m,
            SUM(CASE WHEN b.classification = 'PESO-Accepted'      AND b.sex = 'Female' THEN 1 ELSE 0 END) AS peso_f,
            -- Privately-Accepted
            SUM(CASE WHEN b.classification = 'Privately-Accepted' AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS priv_m,
            SUM(CASE WHEN b.classification = 'Privately-Accepted' AND b.sex = 'Female' THEN 1 ELSE 0 END) AS priv_f,
            -- Not Proceeded
            SUM(CASE WHEN b.classification = 'Not Proceeded'      AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS notp_m,
            SUM(CASE WHEN b.classification = 'Not Proceeded'      AND b.sex = 'Female' THEN 1 ELSE 0 END) AS notp_f

        FROM gip g

        INNER JOIN import_batches ib
            ON ib.batch_id = g.batch_id

        INNER JOIN beneficiaries b
            ON b.benef_id = g.benef_id

        WHERE ib.year = ?
    ";

    $params = [$year];
    $types  = 'i';

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

    $sql .= "
        GROUP BY
            g.gip_id,
            g.contract_period,
            g.school,
            g.college_or_shs,
            g.course,
            g.office_assignment,
            g.required_hours,
            ib.month,
            ib.year
        ORDER BY ib.month ASC, g.school ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();

    // Card totals
    $totals = [
        'participants'  => ['m' => 0, 'f' => 0],
        'inquired'      => ['m' => 0, 'f' => 0],
        'referred'      => ['m' => 0, 'f' => 0],
        'interviewed'   => ['m' => 0, 'f' => 0],
        'peso'          => ['m' => 0, 'f' => 0],
        'private'       => ['m' => 0, 'f' => 0],
        'not_proceeded' => ['m' => 0, 'f' => 0],
    ];
    foreach ($rows as $r) {
        $totals['participants']['m']  += (int) $r['part_m']; $totals['participants']['f']  += (int) $r['part_f'];
        $totals['inquired']['m']      += (int) $r['inq_m'];  $totals['inquired']['f']      += (int) $r['inq_f'];
        $totals['referred']['m']      += (int) $r['ref_m'];  $totals['referred']['f']      += (int) $r['ref_f'];
        $totals['interviewed']['m']   += (int) $r['int_m'];  $totals['interviewed']['f']   += (int) $r['int_f'];
        $totals['peso']['m']          += (int) $r['peso_m']; $totals['peso']['f']          += (int) $r['peso_f'];
        $totals['private']['m']       += (int) $r['priv_m']; $totals['private']['f']       += (int) $r['priv_f'];
        $totals['not_proceeded']['m'] += (int) $r['notp_m']; $totals['not_proceeded']['f'] += (int) $r['notp_f'];
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'years' => $years]);
}

// ─── PUT — edit a gip row ─────────────────────────────────────────────────
if ($method === 'PUT') {

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

// ─── DELETE ?id=5 ─────────────────────────────────────────────────────────
if ($method === 'DELETE') {

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