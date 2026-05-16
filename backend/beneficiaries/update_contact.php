<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$benefId = (int)($data['benef_id'] ?? 0);

if ($benefId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid beneficiary id']);
    exit;
}

$contact = trim($data['contact'] ?? '');
$email = trim($data['email'] ?? '');

try {
    $stmt = $conn->prepare('UPDATE beneficiaries SET contact = ?, email = ? WHERE benef_id = ?');
    if ($stmt === false) throw new Exception($conn->error);
    $stmt->bind_param('ssi', $contact, $email, $benefId);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    echo json_encode(['success' => true, 'affected' => $affected]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
