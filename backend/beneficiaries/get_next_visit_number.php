<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$benefId = (int)($_GET['id'] ?? 0);
if ($benefId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid beneficiary id']);
    exit;
}

try {
    $stmt = $conn->prepare('SELECT COALESCE(MAX(visit_number), 0) + 1 AS next_visit FROM beneficiary_activity_history WHERE benef_id = ? AND classification = "PESO_VISIT"');
    $stmt->bind_param('i', $benefId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $next = (int)($row['next_visit'] ?? 1);
    echo json_encode(['success' => true, 'next' => $next]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
