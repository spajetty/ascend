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
        'error'   => $conn->connect_error
    ]);
    exit;
}

set_error_handler(function ($errno, $errstr) {
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode([
        'success' => false,
        'error'   => "PHP Error ($errno): $errstr"
    ]);
    exit;
});

function json_error(string $msg, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

function json_ok($data = null): void
{
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

function refreshWhipCacheFresh($yearFilter): array
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
        return refreshWhipCache($freshConn, $yearFilter);
    } finally {
        $freshConn->close();
    }
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $yearFilter = $_GET['year'] ?? 'all';
        $cachePath = __DIR__ . '/../../../cache/fetch-whip.json';

        if (file_exists($cachePath)) {
            $cached = json_decode((string) file_get_contents($cachePath), true);
            if (
                json_last_error() === JSON_ERROR_NONE &&
                !empty($cached['success']) &&
                isset($cached['data']['year']) &&
                (string) $cached['data']['year'] === (string) $yearFilter
            ) {
                echo json_encode($cached, JSON_PRETTY_PRINT);
                exit;
            }
        }

        echo json_encode(refreshWhipCache($conn, $yearFilter), JSON_PRETTY_PRINT);
        exit;
    }

    if ($method === 'PUT') {
        $body = json_decode(file_get_contents('php://input'), true);

        $whip_id = (int) ($body['whip_id'] ?? 0);
        if (!$whip_id) {
            json_error('Missing whip_id');
        }

        $yearStmt = $conn->prepare("\n            SELECT YEAR(date_hired) AS yr\n            FROM whip\n            WHERE whip_id = ?\n            LIMIT 1\n        ");
        $yearStmt->bind_param('i', $whip_id);
        $yearStmt->execute();
        $yearResult = $yearStmt->get_result();
        $yearRow = $yearResult->fetch_assoc();
        $yearResult->free();
        $yearStmt->close();
        $cacheYear = isset($yearRow['yr']) ? (int) $yearRow['yr'] : (int) date('Y');

        $position   = trim($body['position'] ?? '');
        $date_hired = trim($body['date_hired'] ?? '');

        if ($date_hired && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_hired)) {
            json_error('Invalid date format');
        }

        $stmt = $conn->prepare("\n            UPDATE whip\n            SET position = ?, date_hired = ?\n            WHERE whip_id = ?\n        ");

        $stmt->bind_param('ssi', $position, $date_hired, $whip_id);
        $stmt->execute();

        if ($date_hired) {
            $cacheYear = (int) date('Y', strtotime($date_hired));
        }

        $stmt->close();

        refreshWhipCacheFresh($cacheYear);

        json_ok(['updated' => $stmt->affected_rows]);
    }

    if ($method === 'DELETE') {
        $whip_id = (int) ($_GET['id'] ?? 0);
        if (!$whip_id) {
            json_error('Missing id');
        }

        $yearStmt = $conn->prepare("\n            SELECT YEAR(date_hired) AS yr\n            FROM whip\n            WHERE whip_id = ?\n            LIMIT 1\n        ");
        $yearStmt->bind_param('i', $whip_id);
        $yearStmt->execute();
        $yearResult = $yearStmt->get_result();
        $yearRow = $yearResult->fetch_assoc();
        $yearResult->free();
        $yearStmt->close();
        $cacheYear = isset($yearRow['yr']) ? (int) $yearRow['yr'] : (int) date('Y');

        $stmt = $conn->prepare("\n            DELETE FROM whip WHERE whip_id = ?\n        ");

        $stmt->bind_param('i', $whip_id);
        $stmt->execute();

        $stmt->close();

        refreshWhipCacheFresh($cacheYear);

        json_ok(['deleted' => $stmt->affected_rows]);
    }

    json_error('Method not allowed', 405);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}