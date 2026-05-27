<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

try {
    $sql = 'SELECT p.company_id, e.company_name 
            FROM jobfair_participants p
            JOIN employers e ON p.company_id = e.company_id
            WHERE p.jobfairevent_id = ?
            ORDER BY e.company_name ASC';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $companies = [];
    while ($r = $res->fetch_assoc()) {
        $companies[] = $r;
    }
    echo json_encode(['success' => true, 'companies' => $companies]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
