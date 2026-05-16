<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$gip_id = isset($_POST['gip_id']) ? (int)$_POST['gip_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
$contract_period = isset($_POST['contract_period']) ? trim($_POST['contract_period']) : '';
$school = isset($_POST['school']) ? trim($_POST['school']) : '';
$course = isset($_POST['course']) ? trim($_POST['course']) : '';
$required_hours = isset($_POST['required_hours']) && $_POST['required_hours'] !== '' ? (int)$_POST['required_hours'] : 0;
$college_or_shs = isset($_POST['college_or_shs']) ? trim($_POST['college_or_shs']) : '';
$preferred_org_type = isset($_POST['preferred_org_type']) ? trim($_POST['preferred_org_type']) : '';
$preferred_industry = isset($_POST['preferred_industry']) ? trim($_POST['preferred_industry']) : '';
$is_willing_outside = isset($_POST['is_willing_outside']) ? (int)$_POST['is_willing_outside'] : 0;
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

if ($college_or_shs !== '' && !in_array($college_or_shs, ['college', 'shs'], true)) {
    echo json_encode(['success' => false, 'error' => 'Invalid education level']);
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
            SET contract_period = ?,
                school = ?,
                course = ?,
                required_hours = ?,
                college_or_shs = ?,
                preferred_org_type = ?,
                preferred_industry = ?,
                is_willing_outside = ?,
                office_assignment = ?,
                type = ?
            WHERE gip_id = ? AND benef_id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare update failed: ' . $conn->error);
    }

    $stmt->bind_param(
        'sssisssissii',
        $contract_period,
        $school,
        $course,
        $required_hours,
        $college_or_shs,
        $preferred_org_type,
        $preferred_industry,
        $is_willing_outside,
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
