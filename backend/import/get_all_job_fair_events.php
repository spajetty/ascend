<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

try {
    // Check if event_name column exists
    $hasEventName = false;
    $colRes = $conn->query("SHOW COLUMNS FROM job_fair_events LIKE 'event_name'");
    if ($colRes && $colRes->num_rows > 0) {
        $hasEventName = true;
    }

    $selectCols = $hasEventName ? "jobfairevent_id, event_name, venue, date_start" : "jobfairevent_id, venue, date_start";

    $sql = "SELECT $selectCols
            FROM job_fair_events
            ORDER BY date_start DESC";
    $result = $conn->query($sql);
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    echo json_encode(['success' => true, 'events' => $events]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
