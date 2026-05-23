<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$month = trim($_GET['month'] ?? '');
$year  = trim($_GET['year'] ?? '');

try {
    if ($month && $year) {
        $monthNum = date('m', strtotime("$month 1 2000"));
        $sql = "SELECT jobfairevent_id, venue, date_start, date_end 
                FROM job_fair_events 
                WHERE MONTH(date_start) = ? AND YEAR(date_start) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $monthNum, $year);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT jobfairevent_id, venue, date_start, date_end 
                FROM job_fair_events 
                ORDER BY date_start DESC";
        $result = $conn->query($sql);
    }
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode(['success' => true, 'events' => $events]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}