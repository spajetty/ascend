<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$benefId = (int)($data['benef_id'] ?? 0);
$userId = isset($data['user_id']) ? (int)$data['user_id'] : (isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0);
$date = $data['date_of_record'] ?? date('Y-m-d');

if ($benefId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid beneficiary id']);
    exit;
}

try {
    // Determine current max visit_number
    $stmt = $conn->prepare('SELECT MAX(visit_number) AS max_visit FROM beneficiary_activity_history WHERE benef_id = ? AND classification = "PESO_VISIT"');
    $stmt->bind_param('i', $benefId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $nextVisit = ((int)($row['max_visit'] ?? 0)) + 1;

    // Insert new PESO_VISIT row
    $createdAt = date('Y-m-d H:i:s');
    $classification = 'PESO_VISIT';
    $ins = $conn->prepare('INSERT INTO beneficiary_activity_history (user_id, benef_id, classification, date_of_record, created_at, visit_number) VALUES (?, ?, ?, ?, ?, ?)');
    $ins->bind_param('iisssi', $userId, $benefId, $classification, $date, $createdAt, $nextVisit);
    $ins->execute();
    $insertId = (int)$ins->insert_id;
    $ins->close();

    echo json_encode(['success' => true, 'history_id' => $insertId, 'visit_number' => $nextVisit]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
