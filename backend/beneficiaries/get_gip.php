<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$benef_id = isset($_GET['benef_id']) ? (int)$_GET['benef_id'] : 0;
if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

try {
    $sql = "SELECT gip_id, benef_id, student_type, highest_educ, course, school, 
                   start_of_contract, end_of_contract, days, office_assignment, type
            FROM gip
            WHERE benef_id = ?
            ORDER BY created_at DESC, gip_id DESC
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $benef_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'record' => $record
    ]);
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
