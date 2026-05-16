<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$benef_id = isset($_GET['benef_id']) ? (int)$_GET['benef_id'] : 0;
if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

try {
    $sql = "SELECT COUNT(*) as count
            FROM beneficiary_activity_history
            WHERE benef_id = ? AND classification = 'PESO_VISIT'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $benef_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'count' => (int)($row['count'] ?? 0)
    ]);
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
