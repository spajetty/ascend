<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$history_id = isset($_POST['history_id']) ? (int)$_POST['history_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
$company_id = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$date_of_record = isset($_POST['date_of_record']) ? trim($_POST['date_of_record']) : '';
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

if ($history_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid employment record id']);
    exit;
}

if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

if ($company_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Company is required']);
    exit;
}

if (!$status) {
    echo json_encode(['success' => false, 'error' => 'Status is required']);
    exit;
}

if (!$date_of_record) {
    echo json_encode(['success' => false, 'error' => 'Date is required']);
    exit;
}

try {
    $check = $conn->prepare('SELECT history_id FROM emphistory WHERE history_id = ? AND benef_id = ?');
    if (!$check) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    $check->bind_param('ii', $history_id, $benef_id);
    $check->execute();
    $exists = $check->get_result()->fetch_assoc();
    $check->close();

    if (!$exists) {
        echo json_encode(['success' => false, 'error' => 'Employment record not found']);
        exit;
    }

    $sql = "UPDATE emphistory
            SET company_id = ?, classification = ?, date_of_record = ?, notes = ?
            WHERE history_id = ? AND benef_id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('isssii', $company_id, $status, $date_of_record, $notes, $history_id, $benef_id);

    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    $stmt->close();

    echo json_encode([
        'success' => true,
        'message' => 'Employment record updated successfully'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>