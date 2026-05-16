<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$history_id = isset($_POST['history_id']) ? (int)$_POST['history_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;

if ($history_id <= 0 || $benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

try {
    // Verify the record belongs to this beneficiary before deleting
    $verifySql = "SELECT history_id FROM emphistory WHERE history_id = ? AND benef_id = ?";
    $verifyStmt = $conn->prepare($verifySql);
    $verifyStmt->bind_param('ii', $history_id, $benef_id);
    $verifyStmt->execute();
    $verifyResult = $verifyStmt->get_result();
    
    if ($verifyResult->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Record not found']);
        exit;
    }
    
    $verifyStmt->close();

    // Delete the record
    $deleteSql = "DELETE FROM emphistory WHERE history_id = ? AND benef_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param('ii', $history_id, $benef_id);
    $deleteStmt->execute();
    
    if ($deleteStmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Employment record deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete record']);
    }
    
    $deleteStmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
