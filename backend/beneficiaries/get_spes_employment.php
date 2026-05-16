<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$benef_id = isset($_GET['benef_id']) ? (int)$_GET['benef_id'] : 0;
if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

try {
    $sql = "SELECT se.employment_id, se.spes_id, se.company_id, se.store_assignment, se.start_of_contract, se.end_of_contract, se.days, se.category,
                   e.company_name
            FROM spes_employment se
            LEFT JOIN employers e ON e.company_id = se.company_id
            WHERE se.spes_id IN (SELECT spes_id FROM spes WHERE benef_id = ?)
            ORDER BY se.start_of_contract DESC, se.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $benef_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }

    echo json_encode(['success' => true, 'records' => $records]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
