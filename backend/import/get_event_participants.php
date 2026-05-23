<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$eventId = intval($_GET['event_id'] ?? 0);
if (!$eventId) {
    echo json_encode(['success' => false, 'error' => 'event_id is required.']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT jp.company_id, e.company_name
        FROM jobfair_participants jp
        LEFT JOIN employers e ON e.company_id = jp.company_id
        WHERE jp.jobfairevent_id = ?
        ORDER BY e.company_name ASC
    ");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    $participants = [];
    while ($row = $result->fetch_assoc()) {
        $participants[] = $row;
    }
    $stmt->close();

    echo json_encode(['success' => true, 'participants' => $participants]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
