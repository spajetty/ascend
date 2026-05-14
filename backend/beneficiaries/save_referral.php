<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../api/db.php';

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$benefId = (int)($data['benef_id'] ?? 0);
$companyId = (int)($data['company_id'] ?? 0);
$userId = isset($data['user_id']) ? (int)$data['user_id'] : (isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0);
$date = $data['date_of_record'] ?? date('Y-m-d');
$position = trim((string)($data['position'] ?? ''));
$status = strtoupper(trim((string)($data['referral_status'] ?? 'PENDING')));

if ($benefId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid beneficiary id']);
    exit;
}

if ($companyId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Please select an employer']);
    exit;
}

if ($position === '') {
    echo json_encode(['success' => false, 'message' => 'Please enter a position']);
    exit;
}

try {
    $createdAt = date('Y-m-d H:i:s');
    $classification = 'REFERRAL';

    $stmt = $conn->prepare('INSERT INTO beneficiary_activity_history (user_id, benef_id, classification, date_of_record, created_at, company_id, position, referral_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('iisssiss', $userId, $benefId, $classification, $date, $createdAt, $companyId, $position, $status);
    $stmt->execute();
    $insertId = (int)$stmt->insert_id;
    $stmt->close();

    echo json_encode(['success' => true, 'history_id' => $insertId]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
