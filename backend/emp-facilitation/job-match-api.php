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
    echo json_encode([
        'success' => false,
        'error' => 'Missing DB_NAME in .env'
    ]);
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'error' => 'DB connection failed: ' . $conn->connect_error
    ]);
    exit;
}

// ─── Catch PHP warnings/errors as JSON ─────────────────────────────
set_error_handler(function (int $errno, string $errstr) {
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }

    echo json_encode([
        'success' => false,
        'error' => "PHP Error ($errno): $errstr"
    ]);

    exit;
});

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// ─── Helpers ───────────────────────────────────────────────────────
function json_error(string $msg, int $code = 400): void {
    http_response_code($code);

    echo json_encode([
        'success' => false,
        'error' => $msg
    ]);

    exit;
}

function json_ok(mixed $data = null): void {
    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

    exit;
}

// ───────────────────────────────────────────────────────────────────
// GET
// Example: api.php?year=2026
// ───────────────────────────────────────────────────────────────────
if ($method === 'GET') {

    $year = isset($_GET['year'])
        ? (int) $_GET['year']
        : (int) date('Y');

    // Available years
    $res = $conn->query("
        SELECT DISTINCT YEAR(created_at) AS year
        FROM jobMatch
        ORDER BY year DESC
    ");

    $years = [];

    while ($row = $res->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }

    if (!in_array($year, $years, true)) {
        $years[] = $year;
        rsort($years);
    }

    // Main query
    $sql = "
        SELECT
            jm.jobmatch_id,

            MONTHNAME(jm.created_at) AS month,
            YEAR(jm.created_at) AS year,

            SUM(CASE 
                WHEN ab.classification = 'Registered'
                AND b.sex = 'M'
                THEN 1 ELSE 0
            END) AS reg_m,

            SUM(CASE 
                WHEN ab.classification = 'Registered'
                AND b.sex = 'F'
                THEN 1 ELSE 0
            END) AS reg_f,

            SUM(CASE 
                WHEN ab.classification = 'Referred'
                AND b.sex = 'M'
                THEN 1 ELSE 0
            END) AS ref_m,

            SUM(CASE 
                WHEN ab.classification = 'Referred'
                AND b.sex = 'F'
                THEN 1 ELSE 0
            END) AS ref_f,

            SUM(CASE 
                WHEN ab.classification = 'Interviewed'
                AND b.sex = 'M'
                THEN 1 ELSE 0
            END) AS int_m,

            SUM(CASE 
                WHEN ab.classification = 'Interviewed'
                AND b.sex = 'F'
                THEN 1 ELSE 0
            END) AS int_f,

            SUM(CASE 
                WHEN ab.classification = 'Qualified'
                AND b.sex = 'M'
                THEN 1 ELSE 0
            END) AS qual_m,

            SUM(CASE 
                WHEN ab.classification = 'Qualified'
                AND b.sex = 'F'
                THEN 1 ELSE 0
            END) AS qual_f,

            SUM(CASE 
                WHEN ab.classification = 'Not Qualified'
                AND b.sex = 'M'
                THEN 1 ELSE 0
            END) AS nqual_m,

            SUM(CASE 
                WHEN ab.classification = 'Not Qualified'
                AND b.sex = 'F'
                THEN 1 ELSE 0
            END) AS nqual_f,

            SUM(CASE 
                WHEN ab.classification = 'Placed'
                AND b.sex = 'M'
                THEN 1 ELSE 0
            END) AS placed_m,

            SUM(CASE 
                WHEN ab.classification = 'Placed'
                AND b.sex = 'F'
                THEN 1 ELSE 0
            END) AS placed_f,

            SUM(CASE 
                WHEN ab.classification = 'For Further Interview'
                AND b.sex = 'M'
                THEN 1 ELSE 0
            END) AS ffi_m,

            SUM(CASE 
                WHEN ab.classification = 'For Further Interview'
                AND b.sex = 'F'
                THEN 1 ELSE 0
            END) AS ffi_f

        FROM jobMatch jm

        LEFT JOIN apply_benef ab
            ON ab.program_id = (
                SELECT program_id
                FROM programs
                WHERE name = 'Job Matching and Referral'
                LIMIT 1
            )

        LEFT JOIN beneficiaries b
            ON b.benef_id = ab.benef_id
            AND MONTH(b.created_at) = MONTH(jm.created_at)
            AND YEAR(b.created_at) = YEAR(jm.created_at)

        WHERE YEAR(jm.created_at) = ?

        GROUP BY jm.jobmatch_id

        ORDER BY MONTH(jm.created_at)
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        json_error('Query prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('i', $year);

    $stmt->execute();

    $result = $stmt->get_result();

    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    $stmt->close();

    // Totals
    $totals = [
        'registered'  => 0,
        'referred'    => 0,
        'interviewed' => 0,
        'placed'      => 0
    ];

    foreach ($rows as $r) {

        $totals['registered'] +=
            $r['reg_m'] + $r['reg_f'];

        $totals['referred'] +=
            $r['ref_m'] + $r['ref_f'];

        $totals['interviewed'] +=
            $r['int_m'] + $r['int_f'];

        $totals['placed'] +=
            $r['placed_m'] + $r['placed_f'];
    }

    json_ok([
        'rows'   => $rows,
        'totals' => $totals,
        'years'  => $years
    ]);
}

// ───────────────────────────────────────────────────────────────────
// PUT
// ───────────────────────────────────────────────────────────────────
if ($method === 'PUT') {

    $body = json_decode(
        file_get_contents('php://input'),
        true
    );

    $id = isset($body['jobmatch_id'])
        ? (int) $body['jobmatch_id']
        : 0;

    if (!$id) {
        json_error('Missing jobmatch_id');
    }

    $allowed = ['created_at'];

    $sets   = [];
    $types  = '';
    $values = [];

    foreach ($allowed as $col) {

        if (array_key_exists($col, $body)) {

            $sets[] = "$col = ?";

            $types .= 's';

            $values[] = $body[$col];
        }
    }

    if (empty($sets)) {
        json_error('Nothing to update');
    }

    $types .= 'i';
    $values[] = $id;

    $stmt = $conn->prepare("
        UPDATE jobMatch
        SET " . implode(', ', $sets) . "
        WHERE jobmatch_id = ?
    ");

    if (!$stmt) {
        json_error('Query prepare failed: ' . $conn->error);
    }

    $stmt->bind_param($types, ...$values);

    $stmt->execute();

    $affected = $stmt->affected_rows;

    $stmt->close();

    json_ok([
        'updated' => $affected
    ]);
}

// ───────────────────────────────────────────────────────────────────
// DELETE
// Example: ?id=5
// ───────────────────────────────────────────────────────────────────
if ($method === 'DELETE') {

    $id = isset($_GET['id'])
        ? (int) $_GET['id']
        : 0;

    if (!$id) {
        json_error('Missing id');
    }

    $stmt = $conn->prepare("
        DELETE FROM jobMatch
        WHERE jobmatch_id = ?
    ");

    if (!$stmt) {
        json_error('Query prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('i', $id);

    $stmt->execute();

    $affected = $stmt->affected_rows;

    $stmt->close();

    json_ok([
        'deleted' => $affected
    ]);
}

json_error('Method not allowed', 405);