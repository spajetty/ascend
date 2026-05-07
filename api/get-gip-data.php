<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

try {
    $query = "
        SELECT
            gip_id,
            contract_period,
            school,
            college_or_shs,
            course,
            office_assignment,
            required_hours
        FROM gip
        ORDER BY contract_period DESC
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
