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

try {

    $year = isset($_GET['year'])
        ? (int) $_GET['year']
        : (int) date('Y');

    // ─────────────────────────────────────────
    // AVAILABLE YEARS
    // ─────────────────────────────────────────

    $years = [];

    $yearQuery = $conn->query("
        SELECT DISTINCT YEAR(date_of_conduct) AS yr
        FROM lmi
        WHERE date_of_conduct IS NOT NULL
        ORDER BY yr DESC
    ");

    while ($row = $yearQuery->fetch_assoc()) {
        $years[] = (int) $row['yr'];
    }

    if (!in_array($year, $years)) {
        $years[] = $year;
        rsort($years);
    }

    // ─────────────────────────────────────────
    // FETCH RECORDS
    // ─────────────────────────────────────────

    $stmt = $conn->prepare("
        SELECT
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

    $stmt->bind_param("i", $year);
    $stmt->execute();

    $result = $stmt->get_result();

    $rows = [];

    $totals = [
        'sessions' => 0,
        'total_m' => 0,
        'total_f' => 0,
        'total' => 0
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

    echo json_encode([
        'success' => true,
        'data' => [
            'rows' => $rows,
            'totals' => $totals,
            'years' => $years
        ]
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}