<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$benefId = (int)($data['benef_id'] ?? 0);
$notes = isset($data['notes']) ? trim($data['notes']) : null; // allow empty string

if ($benefId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid beneficiary id']);
    exit;
}

try {
    $stmt = $conn->prepare('UPDATE beneficiaries SET notes = ? WHERE benef_id = ?');
    if ($stmt === false) throw new Exception($conn->error);
    $stmt->bind_param('si', $notes, $benefId);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    echo json_encode(['success' => true, 'affected' => $affected]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
