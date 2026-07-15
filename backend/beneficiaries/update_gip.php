<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$gip_id = isset($_POST['gip_id']) ? (int)$_POST['gip_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
$student_type = isset($_POST['student_type']) ? trim($_POST['student_type']) : '';
$school = isset($_POST['school']) ? trim($_POST['school']) : '';
$course = isset($_POST['course']) ? trim($_POST['course']) : '';
$highest_educ = isset($_POST['highest_educ']) ? trim($_POST['highest_educ']) : '';
$start_of_contract = isset($_POST['start_of_contract']) ? trim($_POST['start_of_contract']) : '';
$end_of_contract = isset($_POST['end_of_contract']) ? trim($_POST['end_of_contract']) : '';
$days = isset($_POST['days']) && $_POST['days'] !== '' ? (int)$_POST['days'] : 0;
$office_assignment = isset($_POST['office_assignment']) ? trim($_POST['office_assignment']) : '';
$type = isset($_POST['type']) ? trim($_POST['type']) : '';

if ($gip_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid GIP record id']);
    exit;
}

if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

if ($type !== '' && !in_array($type, ['DOLE', 'LGU'], true)) {
    echo json_encode(['success' => false, 'error' => 'Invalid placement type']);
    exit;
}

if ($student_type !== '' && !in_array($student_type, ['student', 'osy'], true)) {
    echo json_encode(['success' => false, 'error' => 'Invalid student type']);
    exit;
}

try {
    $check = $conn->prepare('SELECT gip_id FROM gip WHERE gip_id = ? AND benef_id = ? LIMIT 1');
    if (!$check) {
        throw new Exception('Prepare check failed: ' . $conn->error);
    }

    $check->bind_param('ii', $gip_id, $benef_id);
    if (!$check->execute()) {
        throw new Exception('Execute check failed: ' . $check->error);
    }

    $exists = $check->get_result()->fetch_assoc();
    $check->close();

    if (!$exists) {
        echo json_encode(['success' => false, 'error' => 'GIP record not found']);
        exit;
    }

    $sql = "UPDATE gip
            SET student_type = ?,
                school = ?,
                course = ?,
                highest_educ = ?,
                start_of_contract = ?,
                end_of_contract = ?,
                days = ?,
                office_assignment = ?,
                type = ?
            WHERE gip_id = ? AND benef_id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare update failed: ' . $conn->error);
    }

    $stmt->bind_param(
        'ssssssissii',
        $student_type,
        $school,
        $course,
        $highest_educ,
        $start_of_contract,
        $end_of_contract,
        $days,
        $office_assignment,
        $type,
        $gip_id,
        $benef_id
    );

    if (!$stmt->execute()) {
        throw new Exception('Execute update failed: ' . $stmt->error);
    }

    $stmt->close();
    echo json_encode(['success' => true, 'message' => 'GIP record updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
