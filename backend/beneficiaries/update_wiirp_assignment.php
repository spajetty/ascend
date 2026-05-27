<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$assignment_id = isset($_POST['assignment_id']) ? (int)$_POST['assignment_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
if ($assignment_id <= 0 || $benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

// Normalize inputs: treat empty values as NULL where appropriate
$start_date = isset($_POST['start_date']) && $_POST['start_date'] !== '' ? trim($_POST['start_date']) : null;
$end_date = isset($_POST['end_date']) && $_POST['end_date'] !== '' ? trim($_POST['end_date']) : null;
$required_hours = isset($_POST['required_hours']) && $_POST['required_hours'] !== '' ? (int)$_POST['required_hours'] : null;
$office_assignment = isset($_POST['office_assignment']) ? trim($_POST['office_assignment']) : '';
$endorsement_1 = isset($_POST['endorsement_1']) ? trim($_POST['endorsement_1']) : '';
$endorsement_2 = isset($_POST['endorsement_2']) ? trim($_POST['endorsement_2']) : '';

try {
    // Ownership check: ensure the assignment belongs to a WIIRP record for this beneficiary
    $checkSql = "SELECT a.id FROM wiirp_assignment_details a JOIN wiirp w ON a.work_immersion_id = w.work_immersion_id WHERE a.id = ? AND w.benef_id = ? LIMIT 1";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('ii', $assignment_id, $benef_id);
    $checkStmt->execute();
    $res = $checkStmt->get_result();
    if (!$res || !$res->num_rows) {
        echo json_encode(['success' => false, 'error' => 'Assignment not found or access denied']);
        exit;
    }

    $sql = "UPDATE wiirp_assignment_details SET start_date = ?, end_date = ?, required_hours = ?, office_assignment = ?, endorsement_1 = ?, endorsement_2 = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    // types: s=start_date, s=end_date, i=required_hours, s=office_assignment, s=endorsement_1, s=endorsement_2, i=assignment_id
    $stmt->bind_param('ssisssi', $start_date, $end_date, $required_hours, $office_assignment, $endorsement_1, $endorsement_2, $assignment_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Assignment updated']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update assignment: ' . $stmt->error]);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

