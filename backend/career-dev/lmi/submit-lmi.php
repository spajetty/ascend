<?php

require_once __DIR__ . '/../../../includes/auth-check.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed'
    ]);

    exit;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../api/');
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

    $school_id            = (int) ($data['school_id'] ?? 0);
    $date_of_conduct      = trim($data['date_of_conduct'] ?? '');
    $grade_level          = trim($data['grade_level'] ?? '');
    $participants_male    = (int) ($data['participants_male'] ?? 0);
    $participants_female  = (int) ($data['participants_female'] ?? 0);
    $approval_letter      = isset($data['approval_letter']) ? 1 : 0;

    if (!$school_id) {
        throw new Exception("School is required");
    }

    if (!$date_of_conduct) {
        throw new Exception("Date conducted is required");
    }

    $stmt = $conn->prepare("
        INSERT INTO lmi (
            school_id,
            date_of_conduct,
            grade_level,
            participants_male,
            participants_female,
            approval_letter
        )

        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "issiii",
        $school_id,
        $date_of_conduct,
        $grade_level,
        $participants_male,
        $participants_female,
        $approval_letter
    );

    $stmt->execute();

    // Caching removed: no cache refresh performed

    ob_start();
    include __DIR__ . '/../../dashboard/fetch-details.php';
    ob_end_clean();

    echo json_encode([
        'success' => true,
        'message' => 'LMI entry added successfully'
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}