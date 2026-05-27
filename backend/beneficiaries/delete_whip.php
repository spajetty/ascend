<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$whip_id = isset($input['whip_id']) ? (int)$input['whip_id'] : 0;

if (!$whip_id) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $sql = 'DELETE FROM whip WHERE whip_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $whip_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Project assignment deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete record']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
