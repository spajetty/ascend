<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$benef_id = isset($input['benef_id']) ? (int)$input['benef_id'] : 0;
$project_id = isset($input['project_id']) ? (int)$input['project_id'] : 0;
$position = isset($input['position']) ? trim($input['position']) : '';
$date_hired = isset($input['date_hired']) && trim($input['date_hired']) !== '' ? trim($input['date_hired']) : null;

if (!$benef_id || !$project_id) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $sql = 'INSERT INTO whip (benef_id, project_id, position, date_hired, created_at) VALUES (?, ?, ?, ?, NOW())';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiss', $benef_id, $project_id, $position, $date_hired);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Project assignment added successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add project assignment']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
