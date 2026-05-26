<?php

require_once __DIR__ . '/../../../includes/auth-check.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

// Caching removed: fetch fresh LMI data directly from DB

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
    $stmt = $conn->prepare("\n        SELECT
            l.lmi_id,
            l.date_of_conduct,
            l.grade_level,
            l.participants_male,
            l.participants_female,
            l.approval_letter,
            l.created_at,
            s.school_id,
            s.school_name,
            s.congressional_district,
            s.grades_offered
        FROM lmi l
        LEFT JOIN schools s
        ON l.school_id = s.school_id
        WHERE YEAR(l.date_of_conduct) = ?
        ORDER BY l.date_of_conduct ASC
    ");

    $stmt->bind_param('i', $year);
    $stmt->execute();

    $result = $stmt->get_result();
    $rows = [];
    $totals = [
        'sessions' => 0,
        'total_m' => 0,
        'total_f' => 0,
        'total' => 0,
    ];

    while ($row = $result->fetch_assoc()) {
        $row['participants_male'] = (int) $row['participants_male'];
        $row['participants_female'] = (int) $row['participants_female'];
        $row['total'] = $row['participants_male'] + $row['participants_female'];
        $rows[] = $row;
        $totals['sessions']++;
        $totals['total_m'] += $row['participants_male'];
        $totals['total_f'] += $row['participants_female'];
        $totals['total'] += $row['total'];
    }
    $result->free();
    $stmt->close();

    $years = [];
    $yearQuery = $conn->query("\n        SELECT DISTINCT YEAR(date_of_conduct) AS yr\n        FROM lmi\n        WHERE date_of_conduct IS NOT NULL\n        ORDER BY yr DESC\n    ");
    while ($row = $yearQuery->fetch_assoc()) {
        $years[] = (int) $row['yr'];
    }
    $yearQuery->free();

    $payload = [
        'rows' => $rows,
        'totals' => $totals,
        'years' => $years,
        'year' => $year,
    ];

    echo json_encode(['success' => true, 'data' => $payload], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}