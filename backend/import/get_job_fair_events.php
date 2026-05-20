<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$month = trim($_GET['month'] ?? '');
$year  = trim($_GET['year'] ?? '');

try {
    if ($month && $year) {
        // Filtered by month + year (month may be a name like "January" or a number)
        $monthNum = is_numeric($month) ? $month : date('m', strtotime("$month 1 2000"));

        $sql = "SELECT jobfairevent_id, job_fair_type, venue, date_start, date_end
                FROM job_fair_events
                WHERE MONTH(date_start) = ? AND YEAR(date_start) = ?
                ORDER BY date_start ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $monthNum, $year);
    } else {
        // No filter — return all events (used to populate the import dropdown)
        $sql = "SELECT jobfairevent_id, job_fair_type, venue, date_start, date_end
                FROM job_fair_events
                ORDER BY date_start DESC";
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode(['success' => true, 'events' => $events]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}