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
// Returns all workImmersion_internshipReferral rows, with beneficiary pivot
// counts per classification/gender, filtered by year extracted from
// contract_period (e.g. "January 2026", "April - June 2026").
if ($method === 'GET') {
    global $conn;

    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    // Available years — extracted from contract_period which may be e.g. "January 2026"
    // or "April - June 2026". We pull the 4-digit year from the string.
    $res   = $conn->query("
        SELECT DISTINCT CAST(REGEXP_SUBSTR(contract_period, '[0-9]{4}') AS UNSIGNED) AS yr
        FROM workImmersion_internshipReferral
        ORDER BY yr DESC
    ");
    $years = [];
    while ($row = $res->fetch_assoc()) {
        if ($row['yr']) $years[] = (int) $row['yr'];
    }
    if (!in_array($year, $years, true)) { $years[] = $year; rsort($years); }

    // Main pivot query.
    // Beneficiary counts are derived from apply_benef + beneficiaries linked to
    // the 'Work Immersion and Internship Referral' program.
    // We match by work_immersion_id stored in apply_benef.inquiry_type (adjust if
    // your schema links them differently — e.g. via a join column).
    //
    // NOTE: The current schema has no direct FK between workImmersion_internshipReferral
    // and apply_benef. The most practical approach is to match beneficiaries whose
    // apply_benef.program_id = Work Immersion program AND whose created_at year
    // matches the year in the contract_period string. Classification labels match
    // the card labels used in the original work-imm.php.
    $sql = "
        SELECT
            wi.work_immersion_id,
            wi.contract_period,
            wi.school,
            wi.education_level,
            wi.course,
            wi.office_assignment,
            wi.required_hours,

            SUM(CASE WHEN ab.classification = 'Participants'          AND b.gender = 'M' THEN 1 ELSE 0 END) AS part_m,
            SUM(CASE WHEN ab.classification = 'Participants'          AND b.gender = 'F' THEN 1 ELSE 0 END) AS part_f,
            SUM(CASE WHEN ab.classification = 'Inquired'              AND b.gender = 'M' THEN 1 ELSE 0 END) AS inq_m,
            SUM(CASE WHEN ab.classification = 'Inquired'              AND b.gender = 'F' THEN 1 ELSE 0 END) AS inq_f,
            SUM(CASE WHEN ab.classification = 'Referred'              AND b.gender = 'M' THEN 1 ELSE 0 END) AS ref_m,
            SUM(CASE WHEN ab.classification = 'Referred'              AND b.gender = 'F' THEN 1 ELSE 0 END) AS ref_f,
            SUM(CASE WHEN ab.classification = 'Interviewed'           AND b.gender = 'M' THEN 1 ELSE 0 END) AS int_m,
            SUM(CASE WHEN ab.classification = 'Interviewed'           AND b.gender = 'F' THEN 1 ELSE 0 END) AS int_f,
            SUM(CASE WHEN ab.classification = 'PESO-Accepted'         AND b.gender = 'M' THEN 1 ELSE 0 END) AS peso_m,
            SUM(CASE WHEN ab.classification = 'PESO-Accepted'         AND b.gender = 'F' THEN 1 ELSE 0 END) AS peso_f,
            SUM(CASE WHEN ab.classification = 'Privately-Accepted'    AND b.gender = 'M' THEN 1 ELSE 0 END) AS priv_m,
            SUM(CASE WHEN ab.classification = 'Privately-Accepted'    AND b.gender = 'F' THEN 1 ELSE 0 END) AS priv_f,
            SUM(CASE WHEN ab.classification = 'Not Proceeded'         AND b.gender = 'M' THEN 1 ELSE 0 END) AS notpr_m,
            SUM(CASE WHEN ab.classification = 'Not Proceeded'         AND b.gender = 'F' THEN 1 ELSE 0 END) AS notpr_f

        FROM workImmersion_internshipReferral wi
        LEFT JOIN apply_benef ab ON ab.program_id = (
            SELECT program_id FROM programs
            WHERE name = 'Work Immersion and Internship Referral' LIMIT 1
        )
        LEFT JOIN beneficiaries b
            ON  b.benef_id = ab.benef_id
            AND YEAR(b.created_at) = ?
        WHERE CAST(REGEXP_SUBSTR(wi.contract_period, '[0-9]{4}') AS UNSIGNED) = ?
        GROUP BY
            wi.work_immersion_id, wi.contract_period, wi.school,
            wi.education_level, wi.course, wi.office_assignment, wi.required_hours
        ORDER BY wi.work_immersion_id
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param('ii', $year, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $row['part_total']  = (int)$row['part_m']  + (int)$row['part_f'];
        $row['inq_total']   = (int)$row['inq_m']   + (int)$row['inq_f'];
        $row['ref_total']   = (int)$row['ref_m']   + (int)$row['ref_f'];
        $row['int_total']   = (int)$row['int_m']   + (int)$row['int_f'];
        $row['peso_total']  = (int)$row['peso_m']  + (int)$row['peso_f'];
        $row['priv_total']  = (int)$row['priv_m']  + (int)$row['priv_f'];
        $row['notpr_total'] = (int)$row['notpr_m'] + (int)$row['notpr_f'];
        $rows[] = $row;
    }
    $stmt->close();

    // Card totals
    $totals = [
        'part_m'  => 0, 'part_f'  => 0, 'part_total'  => 0,
        'inq_m'   => 0, 'inq_f'   => 0, 'inq_total'   => 0,
        'ref_m'   => 0, 'ref_f'   => 0, 'ref_total'   => 0,
        'int_m'   => 0, 'int_f'   => 0, 'int_total'   => 0,
        'peso_m'  => 0, 'peso_f'  => 0, 'peso_total'  => 0,
        'priv_m'  => 0, 'priv_f'  => 0, 'priv_total'  => 0,
        'notpr_m' => 0, 'notpr_f' => 0, 'notpr_total' => 0,
    ];
    foreach ($rows as $r) {
        foreach (array_keys($totals) as $k) {
            $totals[$k] += (int)($r[$k] ?? 0);
        }
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'years' => $years]);
}

// ─── PUT (edit a workImmersion row) ──────────────────────────────────────────
// Editable fields: contract_period, school, education_level, course,
//                  office_assignment, required_hours
// Beneficiary counts are read-only (come from apply_benef).
if ($method === 'PUT') {
    global $conn;

    $body = json_decode(file_get_contents('php://input'), true);
    $id   = isset($body['work_immersion_id']) ? (int) $body['work_immersion_id'] : 0;
    if (!$id) json_error('Missing work_immersion_id');

    $allowed   = ['contract_period', 'school', 'education_level', 'course', 'office_assignment', 'required_hours'];
    $intFields = ['required_hours'];
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

    $stmt = $conn->prepare(
        "UPDATE workImmersion_internshipReferral SET " . implode(', ', $sets) . " WHERE work_immersion_id = ?"
    );
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

    $stmt = $conn->prepare("DELETE FROM workImmersion_internshipReferral WHERE work_immersion_id = ?");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);