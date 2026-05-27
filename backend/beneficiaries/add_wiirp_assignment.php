<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$work_immersion_id = isset($_POST['work_immersion_id']) ? (int)$_POST['work_immersion_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
if ($work_immersion_id <= 0 || $benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

$start_date = isset($_POST['start_date']) && $_POST['start_date'] !== '' ? trim($_POST['start_date']) : null;
$end_date = isset($_POST['end_date']) && $_POST['end_date'] !== '' ? trim($_POST['end_date']) : null;
$required_hours = isset($_POST['required_hours']) && $_POST['required_hours'] !== '' ? (int)$_POST['required_hours'] : null;
$office_assignment = isset($_POST['office_assignment']) ? trim($_POST['office_assignment']) : '';
$endorsement_1 = isset($_POST['endorsement_1']) ? trim($_POST['endorsement_1']) : '';
$endorsement_2 = isset($_POST['endorsement_2']) ? trim($_POST['endorsement_2']) : '';

try {
    // Verify WIIRP ownership
    $checkSql = "SELECT work_immersion_id FROM wiirp WHERE work_immersion_id = ? AND benef_id = ? LIMIT 1";
    $check = $conn->prepare($checkSql);
    $check->bind_param('ii', $work_immersion_id, $benef_id);
    $check->execute();
    $res = $check->get_result();
    if (!$res || !$res->num_rows) {
        echo json_encode(['success' => false, 'error' => 'WIIRP record not found or access denied']);
        exit;
    }

    $sql = "INSERT INTO wiirp_assignment_details (work_immersion_id, start_date, end_date, required_hours, office_assignment, endorsement_1, endorsement_2) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param('issiiss', $work_immersion_id, $start_date, $end_date, $required_hours, $office_assignment, $endorsement_1, $endorsement_2);
    if ($stmt->execute()) {
        $insertId = $stmt->insert_id;
        echo json_encode(['success' => true, 'assignment_id' => $insertId]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to insert assignment: ' . $stmt->error]);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
