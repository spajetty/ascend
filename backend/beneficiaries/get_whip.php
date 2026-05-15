<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$benef_id = isset($_GET['benef_id']) ? (int)$_GET['benef_id'] : 0;
if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

try {
    $sql = "SELECT w.whip_id, w.benef_id, w.project_id, w.position, w.date_hired,
                   p.project_title, p.duration, p.budget, p.contractor
            FROM whip w
            LEFT JOIN projects p ON p.project_id = w.project_id
            WHERE w.benef_id = ?
            ORDER BY w.date_hired DESC, w.created_at DESC";

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
