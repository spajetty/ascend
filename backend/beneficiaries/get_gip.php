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
                   start_of_contract, end_of_contract, days, office_assignment, type,
                   proponent, status, gsis_beneficiary, relationship, gsis_benef_contact_no
            FROM gip
            WHERE benef_id = ?
            ORDER BY start_of_contract DESC, gip_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $benef_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }

    echo json_encode([
        'success' => true,
        'records' => $records
    ]);
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>