<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

try {
    $query = "
        SELECT
            s.spes_id,
            s.month_reported,
            s.employer,
            s.start_of_contract,
            s.end_of_contract,
            s.days
        FROM spes s
        ORDER BY s.start_of_contract DESC
    ";

    $result = $conn->query($query);
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $data]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
