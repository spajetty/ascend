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

// ─── GET ?year=2026&search=alfamart ─────────────────────────────────────────
// Returns: spes rows + monthly LGU/Private summary + card totals + years
if ($method === 'GET') {
    global $conn;

    $year   = isset($_GET['year'])   ? (int) $_GET['year']              : (int) date('Y');
    $search = isset($_GET['search']) ? trim($_GET['search'])            : '';

    // Available years — derived from start_of_contract year
    $res   = $conn->query("SELECT DISTINCT YEAR(start_of_contract) AS yr FROM spes WHERE start_of_contract IS NOT NULL ORDER BY yr DESC");
    $years = [];
    while ($row = $res->fetch_assoc()) if ($row['yr']) $years[] = (int) $row['yr'];
    if (!in_array($year, $years, true)) { $years[] = $year; rsort($years); }

    // ── Main SPES rows ──────────────────────────────────────────────────────
    // The spes table stores: month_reported, employer, start/end_of_contract, days
    // M/F counts come from beneficiaries linked via apply_benef to the SPES program
    // and matched by employer (company name) and month_reported
    $sql = "
        SELECT
            s.spes_id,
            s.month_reported,
            s.employer,
            s.start_of_contract,
            s.end_of_contract,
            s.days,
            -- Registered M/F
            SUM(CASE WHEN ab.classification = 'Registered' AND b.gender = 'M' THEN 1 ELSE 0 END) AS reg_m,
            SUM(CASE WHEN ab.classification = 'Registered' AND b.gender = 'F' THEN 1 ELSE 0 END) AS reg_f,
            -- Referred M/F
            SUM(CASE WHEN ab.classification = 'Referred'   AND b.gender = 'M' THEN 1 ELSE 0 END) AS ref_m,
            SUM(CASE WHEN ab.classification = 'Referred'   AND b.gender = 'F' THEN 1 ELSE 0 END) AS ref_f,
            -- Placed M/F
            SUM(CASE WHEN ab.classification = 'Placed'     AND b.gender = 'M' THEN 1 ELSE 0 END) AS placed_m,
            SUM(CASE WHEN ab.classification = 'Placed'     AND b.gender = 'F' THEN 1 ELSE 0 END) AS placed_f,
            -- Job Vacancies (stored in employers table linked via company name)
            COALESCE((
                SELECT SUM(jf.vacancy_male + jf.vacancy_female)
                FROM jobFair jf
                JOIN employers e ON e.company_id = jf.company_id
                WHERE e.company_name = s.employer
                  AND MONTHNAME(jf.created_at) = s.month_reported
            ), 0) AS vac_total,
            COALESCE((
                SELECT SUM(jf.vacancy_male)
                FROM jobFair jf
                JOIN employers e ON e.company_id = jf.company_id
                WHERE e.company_name = s.employer
                  AND MONTHNAME(jf.created_at) = s.month_reported
            ), 0) AS vac_m,
            COALESCE((
                SELECT SUM(jf.vacancy_female)
                FROM jobFair jf
                JOIN employers e ON e.company_id = jf.company_id
                WHERE e.company_name = s.employer
                  AND MONTHNAME(jf.created_at) = s.month_reported
            ), 0) AS vac_f,
            -- SPES Baby (inquiry_type = 'SPES Baby')
            SUM(CASE WHEN ab.inquiry_type = 'SPES Baby' AND b.gender = 'M' THEN 1 ELSE 0 END) AS spes_baby_m,
            SUM(CASE WHEN ab.inquiry_type = 'SPES Baby' AND b.gender = 'F' THEN 1 ELSE 0 END) AS spes_baby_f,
            -- 4Ps beneficiaries (stored in apply_benef or a flag — using classification prefix '4Ps')
            SUM(CASE WHEN ab.classification LIKE '4Ps%' AND b.gender = 'M' THEN 1 ELSE 0 END) AS fourps_m,
            SUM(CASE WHEN ab.classification LIKE '4Ps%' AND b.gender = 'F' THEN 1 ELSE 0 END) AS fourps_f,
            -- PWD
            SUM(CASE WHEN ab.classification LIKE 'PWD%' AND b.gender = 'M' THEN 1 ELSE 0 END) AS pwd_m,
            SUM(CASE WHEN ab.classification LIKE 'PWD%' AND b.gender = 'F' THEN 1 ELSE 0 END) AS pwd_f
        FROM spes s
        LEFT JOIN apply_benef ab ON ab.program_id = (
            SELECT program_id FROM programs WHERE name = 'SPES' LIMIT 1
        )
        LEFT JOIN beneficiaries b
            ON  b.benef_id              = ab.benef_id
            AND MONTHNAME(b.created_at) = s.month_reported
            AND YEAR(b.created_at)      = ?
        WHERE YEAR(s.start_of_contract) = ?
    ";

    $params = [$year, $year];
    $types  = 'ii';

    if ($search !== '') {
        $sql    .= " AND s.employer LIKE ?";
        $types  .= 's';
        $params[] = "%$search%";
    }

    $sql .= "
        GROUP BY s.spes_id, s.month_reported, s.employer, s.start_of_contract, s.end_of_contract, s.days
        ORDER BY s.start_of_contract ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    $stmt->close();

    // ── Monthly LGU / Private summary ───────────────────────────────────────
    // Placed counts split by employer type from employers table
    $sumSql = "
        SELECT
            s.month_reported,
            SUM(CASE WHEN e.est_type = 'LGU'     AND b.gender = 'M' AND ab.classification = 'Placed' THEN 1 ELSE 0 END) AS lgu_m,
            SUM(CASE WHEN e.est_type = 'LGU'     AND b.gender = 'F' AND ab.classification = 'Placed' THEN 1 ELSE 0 END) AS lgu_f,
            SUM(CASE WHEN e.est_type != 'LGU'    AND b.gender = 'M' AND ab.classification = 'Placed' THEN 1 ELSE 0 END) AS priv_m,
            SUM(CASE WHEN e.est_type != 'LGU'    AND b.gender = 'F' AND ab.classification = 'Placed' THEN 1 ELSE 0 END) AS priv_f
        FROM spes s
        LEFT JOIN employers e   ON e.company_name = s.employer
        LEFT JOIN apply_benef ab ON ab.program_id = (
            SELECT program_id FROM programs WHERE name = 'SPES' LIMIT 1
        )
        LEFT JOIN beneficiaries b
            ON  b.benef_id              = ab.benef_id
            AND MONTHNAME(b.created_at) = s.month_reported
            AND YEAR(b.created_at)      = ?
        WHERE YEAR(s.start_of_contract) = ?
        GROUP BY s.month_reported
        ORDER BY FIELD(s.month_reported,
            'January','February','March','April','May','June',
            'July','August','September','October','November','December')
    ";
    $stmt2 = $conn->prepare($sumSql);
    if (!$stmt2) json_error('Summary query prepare failed: ' . $conn->error);
    $stmt2->bind_param('ii', $year, $year);
    $stmt2->execute();
    $sumResult = $stmt2->get_result();
    $summary = [];
    while ($row = $sumResult->fetch_assoc()) $summary[] = $row;
    $stmt2->close();

    // ── Card totals ─────────────────────────────────────────────────────────
    $totals = ['registered' => 0, 'referred' => 0, 'placed' => 0, 'vacancies' => 0,
               'spes_baby' => 0, 'fourps' => 0, 'pwd' => 0];
    foreach ($rows as $r) {
        $totals['registered'] += $r['reg_m']      + $r['reg_f'];
        $totals['referred']   += $r['ref_m']      + $r['ref_f'];
        $totals['placed']     += $r['placed_m']   + $r['placed_f'];
        $totals['vacancies']  += $r['vac_total'];
        $totals['spes_baby']  += $r['spes_baby_m']+ $r['spes_baby_f'];
        $totals['fourps']     += $r['fourps_m']   + $r['fourps_f'];
        $totals['pwd']        += $r['pwd_m']       + $r['pwd_f'];
    }

    json_ok(['rows' => $rows, 'summary' => $summary, 'totals' => $totals, 'years' => $years]);
}

// ─── PUT — edit a spes row ───────────────────────────────────────────────────
if ($method === 'PUT') {
    global $conn;

    $body = json_decode(file_get_contents('php://input'), true);
    $id   = isset($body['spes_id']) ? (int) $body['spes_id'] : 0;
    if (!$id) json_error('Missing spes_id');

    $allowed = ['month_reported', 'employer', 'start_of_contract', 'end_of_contract', 'days'];
    $sets = []; $types = ''; $values = [];

    foreach ($allowed as $col) {
        if (array_key_exists($col, $body)) {
            $sets[]   = "$col = ?";
            $types   .= ($col === 'days') ? 'i' : 's';
            $values[] = $body[$col];
        }
    }

    if (empty($sets)) json_error('Nothing to update');

    $types   .= 'i';
    $values[] = $id;

    $stmt = $conn->prepare("UPDATE spes SET " . implode(', ', $sets) . " WHERE spes_id = ?");
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

    $stmt = $conn->prepare("DELETE FROM spes WHERE spes_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);