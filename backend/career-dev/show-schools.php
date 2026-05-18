<?php

require_once __DIR__ . '/../../includes/auth-check.php';
require_once __DIR__ . '/../../vendor/autoload.php';

header('Content-Type: application/json');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../api/');
$dotenv->load();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

try {

    $query = trim($_GET['q'] ?? '');

    if (!$query) {

        echo json_encode([
            'success' => true,
            'data' => []
        ]);

        exit;
    }

    $search = '%' . $query . '%';

    $stmt = $conn->prepare("
        SELECT
            school_id,
            school_name,
            congressional_district,
            grades_offered

        FROM schools

        WHERE school_name LIKE ?

        ORDER BY school_name ASC

        LIMIT 10
    ");

    $stmt->bind_param("s", $search);
    $stmt->execute();

    $result = $stmt->get_result();

    $schools = [];

    while ($row = $result->fetch_assoc()) {
        $schools[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => $schools
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}