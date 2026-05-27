<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$jobfair_id = isset($input['jobfair_id']) ? (int)$input['jobfair_id'] : 0;
$jobfairevent_id = isset($input['jobfairevent_id']) ? (int)$input['jobfairevent_id'] : 0;
$company_id = isset($input['company_id']) ? (int)$input['company_id'] : 0;
$position = isset($input['position']) ? trim($input['position']) : '';

if (!$jobfair_id || !$jobfairevent_id || !$company_id) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $sql = 'UPDATE jobfair SET jobfairevent_id = ?, company_id = ?, position = ? WHERE jobfair_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iisi', $jobfairevent_id, $company_id, $position, $jobfair_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Job fair record updated successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update record']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
