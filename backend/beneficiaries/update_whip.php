<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$whip_id = isset($input['whip_id']) ? (int)$input['whip_id'] : 0;
$project_id = isset($input['project_id']) ? (int)$input['project_id'] : 0;
$position = isset($input['position']) ? trim($input['position']) : '';
$date_hired = isset($input['date_hired']) && trim($input['date_hired']) !== '' ? trim($input['date_hired']) : null;

if (!$whip_id || !$project_id) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $sql = 'UPDATE whip SET project_id = ?, position = ?, date_hired = ? WHERE whip_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issi', $project_id, $position, $date_hired, $whip_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Project assignment updated successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update record']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
