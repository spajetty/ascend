<?php

require_once __DIR__ . '/../../../includes/auth-check.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

header('Content-Type: application/json');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../api/');
$dotenv->load();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'error' => $conn->connect_error
    ]);
    exit;
}

set_error_handler(function ($errno, $errstr) {

    if (!headers_sent()) {
        header('Content-Type: application/json');
    }

    echo json_encode([
        'success' => false,
        'error' => "PHP Error ($errno): $errstr"
    ]);

    exit;
});

// ─────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────

function json_error(string $msg, int $code = 400): void {

    http_response_code($code);

    echo json_encode([
        'success' => false,
        'error' => $msg
    ]);

    exit;
}

function json_ok($data = null): void {

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {

    // ─────────────────────────────────────────
    // GET
    // ─────────────────────────────────────────

    if ($method === 'GET') {

        $year = isset($_GET['year'])
            ? (int) $_GET['year']
            : (int) date('Y');

        // AVAILABLE YEARS

        $years = [];

        $yearRes = $conn->query("
            SELECT DISTINCT year
            FROM employers_accreditations
            ORDER BY year DESC
        ");

        while ($row = $yearRes->fetch_assoc()) {
            $years[] = (int) $row['year'];
        }

        if (!in_array($year, $years, true)) {
            $years[] = $year;
            rsort($years);
        }

        // FETCH EMPLOYERS

        $stmt = $conn->prepare("
            SELECT
                e.company_id,
                e.company_name,
                e.est_type,
                e.industry,
                e.city,
                e.created_at,

                ea.accreditation_id,
                ea.status AS accreditation,
                ea.month,
                ea.year

            FROM employers e

            LEFT JOIN (
                SELECT ea1.*
                FROM employers_accreditations ea1
                INNER JOIN (
                    SELECT company_id, MAX(accreditation_id) AS max_id
                    FROM employers_accreditations
                    GROUP BY company_id
                ) latest
                ON ea1.accreditation_id = latest.max_id
            ) ea
            ON e.company_id = ea.company_id

            ORDER BY
                e.company_name ASC
        ");

        $stmt->execute();

        $result = $stmt->get_result();

        $rows = [];

        $newCount = 0;
        $renewCount = 0;

        $activeSet = [];

        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        while ($row = $result->fetch_assoc()) {

            $monthNumber = isset($row['month']) ? (int)$row['month'] : null;

            $row['month_number'] = $monthNumber;
            $row['month'] = $monthNumber
                ? $monthNames[$monthNumber] ?? null
                : null;

            $row['month_number'] = $monthNumber;

            $rows[] = $row;

            if ($row['accreditation'] === 'new') {
                $newCount++;
            }

            if ($row['accreditation'] === 'renew') {
                $renewCount++;
            }

            if (!empty($row['accreditation'])) {
                $activeSet[$row['company_name']] = true;
            }
        }

        // TOTAL UNIQUE COMPANIES

        $totalRes = $conn->query("
            SELECT COUNT(DISTINCT company_name) AS cnt
            FROM employers
        ");

        $totalRow = $totalRes->fetch_assoc();

        $totalUnique = (int) $totalRow['cnt'];

        json_ok([
            'rows' => $rows,
            'years' => $years,
            'totals' => [
                'total' => $totalUnique,
                'new' => $newCount,
                'renewed' => $renewCount,
                'active' => count($activeSet)
            ]
        ]);
    }

    // ─────────────────────────────────────────
    // POST
    // ─────────────────────────────────────────

    if ($method === 'POST') {

        $body = json_decode(file_get_contents('php://input'), true);

        $required = [
            'company_name',
            'est_type',
            'industry',
            'city',
            'month',
            'year',
            'accreditation'
        ];

        foreach ($required as $field) {

            if (empty($body[$field]) && $body[$field] !== 0) {
                json_error("Missing required field: $field");
            }
        }

        $validAccreditation = ['new', 'renew'];

        if (!in_array($body['accreditation'], $validAccreditation, true)) {
            json_error('accreditation must be "new" or "renew"');
        }

        $conn->begin_transaction();

        // INSERT EMPLOYER

        $stmt = $conn->prepare("
            INSERT INTO employers (
                company_name,
                est_type,
                industry,
                city
            )

            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssss",
            $body['company_name'],
            $body['est_type'],
            $body['industry'],
            $body['city']
        );

        $stmt->execute();

        $company_id = $conn->insert_id;

        // INSERT ACCREDITATION

        $stmt2 = $conn->prepare("
            INSERT INTO employers_accreditations (
                company_id,
                status,
                month,
                year
            )

            VALUES (?, ?, ?, ?)
        ");

        $stmt2->bind_param(
            "isii",
            $company_id,
            $body['accreditation'],
            $body['month'],
            $body['year']
        );

        $stmt2->execute();

        $conn->commit();

        json_ok([
            'company_id' => $company_id
        ]);
    }

    // ─────────────────────────────────────────
    // PUT
    // ─────────────────────────────────────────

    if ($method === 'PUT') {

        $body = json_decode(file_get_contents('php://input'), true);

        $company_id = isset($body['company_id'])
            ? (int) $body['company_id']
            : 0;

        $accreditation_id = isset($body['accreditation_id'])
            ? (int) $body['accreditation_id']
            : 0;

        if (!$company_id) {
            json_error('Missing company_id');
        }

        if (!$accreditation_id) {
            json_error('Missing accreditation_id');
        }

        $conn->begin_transaction();

        // UPDATE EMPLOYERS TABLE

        $stmt = $conn->prepare("
            UPDATE employers
            SET
                company_name = ?,
                est_type = ?,
                industry = ?,
                city = ?
            WHERE company_id = ?
        ");

        $stmt->bind_param(
            "ssssi",
            $body['company_name'],
            $body['est_type'],
            $body['industry'],
            $body['city'],
            $company_id
        );

        $stmt->execute();

        // UPDATE ACCREDITATION TABLE

        $stmt2 = $conn->prepare("
            UPDATE employers_accreditations
            SET
                status = ?,
                month = ?,
                year = ?
            WHERE accreditation_id = ?
        ");

        $stmt2->bind_param(
            "siii",
            $body['accreditation'],
            $body['month'],
            $body['year'],
            $accreditation_id
        );

        $stmt2->execute();

        $conn->commit();

        json_ok([
            'updated' => true
        ]);
    }

    // ─────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────

    if ($method === 'DELETE') {

        $company_id = isset($_GET['id'])
            ? (int) $_GET['id']
            : 0;

        if (!$company_id) {
            json_error('Missing id');
        }

        $stmt = $conn->prepare("
            DELETE FROM employers
            WHERE company_id = ?
        ");

        $stmt->bind_param("i", $company_id);

        $stmt->execute();

        json_ok([
            'deleted' => $stmt->affected_rows
        ]);
    }

    json_error('Method not allowed', 405);

} catch (Exception $e) {

    if ($conn->errno) {
        $conn->rollback();
    }

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}