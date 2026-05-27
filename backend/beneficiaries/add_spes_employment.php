<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
$company_id = isset($_POST['company_id']) && $_POST['company_id'] !== '' ? (int)$_POST['company_id'] : null;
$store_assignment = isset($_POST['store_assignment']) ? trim($_POST['store_assignment']) : null;
$start_of_contract = isset($_POST['start_of_contract']) && $_POST['start_of_contract'] !== '' ? trim($_POST['start_of_contract']) : null;
$end_of_contract = isset($_POST['end_of_contract']) && $_POST['end_of_contract'] !== '' ? trim($_POST['end_of_contract']) : null;
$days = isset($_POST['days']) && $_POST['days'] !== '' ? (int)$_POST['days'] : null;
$category = isset($_POST['category']) ? trim($_POST['category']) : null;

if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

if ($category !== null && $category !== '' && !in_array(strtolower($category), ['lgu', 'private'], true)) {
    echo json_encode(['success' => false, 'error' => 'Invalid category']);
    exit;
}

try {
    // Find SPES record for this beneficiary
    $sSql = "SELECT spes_id FROM spes WHERE benef_id = ? LIMIT 1";
    $sStmt = $conn->prepare($sSql);
    if (!$sStmt) throw new Exception('Prepare failed: ' . $conn->error);
    $sStmt->bind_param('i', $benef_id);
    if (!$sStmt->execute()) throw new Exception('Execute failed: ' . $sStmt->error);
    $sRes = $sStmt->get_result()->fetch_assoc();
    $sStmt->close();

    if (!$sRes || !isset($sRes['spes_id'])) {
        echo json_encode(['success' => false, 'error' => 'SPES record not found for beneficiary']);
        exit;
    }

    $spes_id = (int)$sRes['spes_id'];

    $sql = "INSERT INTO spes_employment (spes_id, company_id, store_assignment, start_of_contract, end_of_contract, days, category) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception('Prepare insert failed: ' . $conn->error);

    $stmt->bind_param('iisssis', $spes_id, $company_id, $store_assignment, $start_of_contract, $end_of_contract, $days, $category);

    if (!$stmt->execute()) throw new Exception('Execute insert failed: ' . $stmt->error);

    $insertId = $stmt->insert_id;
    $stmt->close();

    echo json_encode(['success' => true, 'employment_id' => $insertId]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
