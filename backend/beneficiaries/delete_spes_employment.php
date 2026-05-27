<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$employment_id = isset($_POST['employment_id']) ? (int)$_POST['employment_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;

if ($employment_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid employment id']);
    exit;
}
if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

try {
    // Verify the employment record belongs to a SPES record for this beneficiary
    $checkSql = "SELECT se.employment_id FROM spes_employment se JOIN spes s ON se.spes_id = s.spes_id WHERE se.employment_id = ? AND s.benef_id = ? LIMIT 1";
    $check = $conn->prepare($checkSql);
    if (!$check) throw new Exception('Prepare check failed: ' . $conn->error);
    $check->bind_param('ii', $employment_id, $benef_id);
    if (!$check->execute()) throw new Exception('Execute check failed: ' . $check->error);
    $exists = $check->get_result()->fetch_assoc();
    $check->close();

    if (!$exists) {
        echo json_encode(['success' => false, 'error' => 'OJT employment record not found']);
        exit;
    }

    $delSql = "DELETE FROM spes_employment WHERE employment_id = ?";
    $del = $conn->prepare($delSql);
    if (!$del) throw new Exception('Prepare delete failed: ' . $conn->error);
    $del->bind_param('i', $employment_id);
    if (!$del->execute()) throw new Exception('Execute delete failed: ' . $del->error);
    $del->close();

    echo json_encode(['success' => true, 'message' => 'OJT employment deleted']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
