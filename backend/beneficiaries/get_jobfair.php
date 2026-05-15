<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$benef_id = isset($_GET['benef_id']) ? (int)$_GET['benef_id'] : 0;
if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

try {
    $sql = "SELECT jf.jobfair_id, jf.benef_id, jf.company_id, jf.position, jf.created_at, jf.batch_id, jf.jobfairevent_id,
                   e.jobfairevent_id as event_id, e.job_fair_type, e.date_start, e.date_end, e.venue
            FROM jobfair jf
            LEFT JOIN job_fair_events e ON jf.jobfairevent_id = e.jobfairevent_id
            WHERE jf.benef_id = ?
            ORDER BY jf.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $benef_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
    }

    echo json_encode(['success' => true, 'records' => $rows]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>
