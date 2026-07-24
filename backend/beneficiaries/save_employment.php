<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
$company_id = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$date_of_record = isset($_POST['date_of_record']) ? trim($_POST['date_of_record']) : '';
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

session_start();
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

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

if ($user_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Unauthenticated user']);
    exit;
}

try {
    $sql = "INSERT INTO emphistory (user_id, benef_id, company_id, classification, date_of_record, notes, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('iiisss', $user_id, $benef_id, $company_id, $status, $date_of_record, $notes);
    
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    $stmt->close();

    echo json_encode([
        'success' => true,
        'message' => 'Employment record added successfully'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
