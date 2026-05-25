<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$jobfair_id = isset($input['jobfair_id']) ? (int)$input['jobfair_id'] : 0;

if (!$jobfair_id) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $sql = 'DELETE FROM jobfair WHERE jobfair_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $jobfair_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Job fair record deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete record']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
