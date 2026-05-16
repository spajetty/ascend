<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$spes_id = isset($_POST['spes_id']) ? (int)$_POST['spes_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
$student_type = isset($_POST['student_type']) ? strtolower(trim($_POST['student_type'])) : '';
$highest_educ = isset($_POST['highest_educ']) ? trim($_POST['highest_educ']) : '';
$course = isset($_POST['course']) ? trim($_POST['course']) : '';
$school = isset($_POST['school']) ? trim($_POST['school']) : '';

if ($spes_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid SPES record id']);
    exit;
}

if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

if ($student_type !== '' && !in_array($student_type, ['student', 'osy'], true)) {
    echo json_encode(['success' => false, 'error' => 'Invalid student type']);
    exit;
}

if ($student_type === '') {
    $student_type = 'student';
}

try {
    $check = $conn->prepare('SELECT spes_id FROM spes WHERE spes_id = ? AND benef_id = ? LIMIT 1');
    if (!$check) {
        throw new Exception('Prepare check failed: ' . $conn->error);
    }

    $check->bind_param('ii', $spes_id, $benef_id);
    if (!$check->execute()) {
        throw new Exception('Execute check failed: ' . $check->error);
    }

    $exists = $check->get_result()->fetch_assoc();
    $check->close();

    if (!$exists) {
        echo json_encode(['success' => false, 'error' => 'SPES record not found']);
        exit;
    }

    $stmt = $conn->prepare('UPDATE spes SET student_type = ?, highest_educ = ?, course = ?, school = ? WHERE spes_id = ? AND benef_id = ?');
    if (!$stmt) {
        throw new Exception('Prepare update failed: ' . $conn->error);
    }

    $stmt->bind_param('ssssii', $student_type, $highest_educ, $course, $school, $spes_id, $benef_id);
    if (!$stmt->execute()) {
        throw new Exception('Execute update failed: ' . $stmt->error);
    }

    $stmt->close();
    echo json_encode(['success' => true, 'message' => 'SPES student information updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
