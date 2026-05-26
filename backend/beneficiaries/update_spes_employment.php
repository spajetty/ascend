<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$employment_id = isset($_POST['employment_id']) ? (int)$_POST['employment_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
$company_id = isset($_POST['company_id']) && $_POST['company_id'] !== '' ? (int)$_POST['company_id'] : null;
$store_assignment = isset($_POST['store_assignment']) ? trim($_POST['store_assignment']) : '';
$start_of_contract = isset($_POST['start_of_contract']) && $_POST['start_of_contract'] !== '' ? trim($_POST['start_of_contract']) : null;
$end_of_contract = isset($_POST['end_of_contract']) && $_POST['end_of_contract'] !== '' ? trim($_POST['end_of_contract']) : null;
$days = isset($_POST['days']) && $_POST['days'] !== '' ? (int)$_POST['days'] : null;
$category = isset($_POST['category']) ? trim($_POST['category']) : '';

if ($employment_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid employment id']);
    exit;
}
if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

if ($category !== '' && !in_array(strtolower($category), ['lgu', 'private'], true)) {
    echo json_encode(['success' => false, 'error' => 'Invalid category']);
    exit;
}

$category = $category !== '' ? strtolower($category) : null;

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

    $sql = "UPDATE spes_employment SET company_id = ?, store_assignment = ?, start_of_contract = ?, end_of_contract = ?, days = ?, category = ? WHERE employment_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception('Prepare update failed: ' . $conn->error);

    $stmt->bind_param(
        'isssisi',
        $company_id,
        $store_assignment,
        $start_of_contract,
        $end_of_contract,
        $days,
        $category,
        $employment_id
    );

    if (!$stmt->execute()) throw new Exception('Execute update failed: ' . $stmt->error);

    $stmt->close();
    echo json_encode(['success' => true, 'message' => 'OJT employment updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
