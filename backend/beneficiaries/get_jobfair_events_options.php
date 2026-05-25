<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

try {
    $sql = 'SELECT jobfairevent_id, job_fair_type, venue, date_start FROM job_fair_events ORDER BY date_start DESC, created_at DESC';
    $res = $conn->query($sql);
    $events = [];
    while ($r = $res->fetch_assoc()) {
        $events[] = $r;
    }
    echo json_encode(['success' => true, 'events' => $events]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
