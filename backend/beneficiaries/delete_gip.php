<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$gip_id = isset($_POST['gip_id']) ? (int) $_POST['gip_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int) $_POST['benef_id'] : 0;

if ($gip_id <= 0 || $benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid gip_id or benef_id']);
    exit;
}

try {
    $sql = "DELETE FROM gip WHERE gip_id = ? AND benef_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $gip_id, $benef_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Record not found']);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>