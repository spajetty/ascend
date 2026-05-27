<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: [];
$assignment_id = isset($data['assignment_id']) ? (int)$data['assignment_id'] : 0;
$benef_id = isset($data['benef_id']) ? (int)$data['benef_id'] : 0;

if ($assignment_id <= 0 || $benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

try {
    // Ownership check
    $checkSql = "SELECT a.id FROM wiirp_assignment_details a JOIN wiirp w ON a.work_immersion_id = w.work_immersion_id WHERE a.id = ? AND w.benef_id = ? LIMIT 1";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('ii', $assignment_id, $benef_id);
    $checkStmt->execute();
    $res = $checkStmt->get_result();
    if (!$res || !$res->num_rows) {
        echo json_encode(['success' => false, 'error' => 'Assignment not found or access denied']);
        exit;
    }

    $delSql = "DELETE FROM wiirp_assignment_details WHERE id = ?";
    $delStmt = $conn->prepare($delSql);
    $delStmt->bind_param('i', $assignment_id);
    if ($delStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Assignment deleted']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete assignment']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
