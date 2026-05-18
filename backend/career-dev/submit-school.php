<?php

require_once __DIR__ . '/../../includes/auth-check.php';
require_once __DIR__ . '/../../vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed'
    ]);

    exit;
}

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

    $data = json_decode(file_get_contents("php://input"), true);

    $school_name = trim($data['school_name'] ?? '');
    $district    = (int) ($data['congressional_district'] ?? 0);
    $grades      = trim($data['grades_offered'] ?? '');

    if (!$school_name) {
        throw new Exception("School name is required");
    }

    // CHECK DUPLICATES
    $check = $conn->prepare("
        SELECT school_id
        FROM schools
        WHERE school_name = ?
        LIMIT 1
    ");

    $check->bind_param("s", $school_name);
    $check->execute();

    $existing = $check->get_result()->fetch_assoc();

    if ($existing) {

        echo json_encode([
            'success' => true,
            'school_id' => $existing['school_id'],
            'message' => 'School already exists'
        ]);

        exit;
    }

    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO schools (
            school_name,
            congressional_district,
            grades_offered
        )

        VALUES (?, ?, ?)
    ");

    $stmt->bind_param(
        "sis",
        $school_name,
        $district,
        $grades
    );

    $stmt->execute();

    echo json_encode([
        'success' => true,
        'school_id' => $conn->insert_id,
        'message' => 'School added successfully'
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}