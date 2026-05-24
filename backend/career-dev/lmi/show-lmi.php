<?php

require_once __DIR__ . '/../../../includes/auth-check.php';
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../cache-refresh.php';

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

$year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');
$cachePath = __DIR__ . '/../../../cache/fetch-lmi.json';

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

try {
    echo json_encode(refreshLmiCache($conn, $year), JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}