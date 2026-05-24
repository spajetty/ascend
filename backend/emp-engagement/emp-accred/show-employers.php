<?php

require_once __DIR__ . '/../../../includes/auth-check.php';
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../career-dev/cache-refresh.php';

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

function json_error(string $msg, int $code = 400): void
{
    http_response_code($code);
    echo json_encode([
        'success' => false,
        'error' => $msg
    ]);
    exit;
}

function json_ok($data = null): void
{
    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
    exit;
}

function refreshEmployersCacheFresh(int $year): array
{
    $freshConn = new mysqli(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
    );

    if ($freshConn->connect_error) {
        throw new Exception($freshConn->connect_error);
    }

    try {
        return refreshEmployersCache($freshConn, $year);
    } finally {
        $freshConn->close();
    }
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');
        $cachePath = __DIR__ . '/../../../cache/fetch-employers.json';

        if (file_exists($cachePath)) {
            $cached = json_decode((string) file_get_contents($cachePath), true);
            if (
                json_last_error() === JSON_ERROR_NONE &&
                !empty($cached['success']) &&
                isset($cached['data']['year']) &&
                (int) $cached['data']['year'] === $year
            ) {
                echo json_encode($cached, JSON_PRETTY_PRINT);
                exit;
            }
        }

        echo json_encode(refreshEmployersCache($conn, $year), JSON_PRETTY_PRINT);
        exit;
    }

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

        if (!in_array($body['accreditation'], ['new', 'renew'], true)) {
            json_error('accreditation must be "new" or "renew"');
        }

        $conn->begin_transaction();

        $stmt = $conn->prepare("\n            INSERT INTO employers (company_name, est_type, industry, city)\n            VALUES (?, ?, ?, ?)\n        ");
        $stmt->bind_param('ssss', $body['company_name'], $body['est_type'], $body['industry'], $body['city']);
        $stmt->execute();

        $company_id = $conn->insert_id;

        $stmt2 = $conn->prepare("\n            INSERT INTO employers_accreditations (company_id, status, month, year)\n            VALUES (?, ?, ?, ?)\n        ");
        $stmt2->bind_param('isii', $company_id, $body['accreditation'], $body['month'], $body['year']);
        $stmt2->execute();

        $conn->commit();

        $stmt2->close();
        $stmt->close();

        refreshEmployersCacheFresh((int) $body['year']);

        json_ok(['company_id' => $company_id]);
    }

    if ($method === 'PUT') {
        $body = json_decode(file_get_contents('php://input'), true);

        $company_id = isset($body['company_id']) ? (int) $body['company_id'] : 0;
        $accreditation_id = isset($body['accreditation_id']) ? (int) $body['accreditation_id'] : 0;

        if (!$company_id) {
            json_error('Missing company_id');
        }

        if (!$accreditation_id) {
            json_error('Missing accreditation_id');
        }

        $conn->begin_transaction();

        $stmt = $conn->prepare("\n            UPDATE employers\n            SET company_name = ?, est_type = ?, industry = ?, city = ?\n            WHERE company_id = ?\n        ");
        $stmt->bind_param('ssssi', $body['company_name'], $body['est_type'], $body['industry'], $body['city'], $company_id);
        $stmt->execute();

        $stmt2 = $conn->prepare("\n            UPDATE employers_accreditations\n            SET status = ?, month = ?, year = ?\n            WHERE accreditation_id = ?\n        ");
        $stmt2->bind_param('siii', $body['accreditation'], $body['month'], $body['year'], $accreditation_id);
        $stmt2->execute();

        $conn->commit();

        $stmt2->close();
        $stmt->close();

        refreshEmployersCacheFresh((int) $body['year']);

        json_ok(['updated' => true]);
    }

    if ($method === 'DELETE') {
        $company_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if (!$company_id) {
            json_error('Missing id');
        }

        $yearStmt = $conn->prepare("\n            SELECT year\n            FROM employers_accreditations\n            WHERE company_id = ?\n            ORDER BY accreditation_id DESC\n            LIMIT 1\n        ");
        $yearStmt->bind_param('i', $company_id);
        $yearStmt->execute();
        $yearResult = $yearStmt->get_result();
        $yearRow = $yearResult->fetch_assoc();
        $yearResult->free();
        $yearStmt->close();
        $cacheYear = isset($yearRow['year']) ? (int) $yearRow['year'] : (int) date('Y');

        $stmt = $conn->prepare("\n            DELETE FROM employers\n            WHERE company_id = ?\n        ");
        $stmt->bind_param('i', $company_id);
        $stmt->execute();

        $stmt->close();

        refreshEmployersCacheFresh($cacheYear);

        json_ok(['deleted' => $stmt->affected_rows]);
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