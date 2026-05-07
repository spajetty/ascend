<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

try {
    $query = "
        SELECT
            jobmatch_id,
            month,
            year
        FROM jobMatch
        GROUP BY month, year
        ORDER BY year DESC, month DESC
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
