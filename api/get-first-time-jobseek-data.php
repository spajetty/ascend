<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

try {
    $query = "
        SELECT
            jobseek_id,
            month,
            year,
            jobseek,
            occ_permit,
            health_card
        FROM firstJobSeek
        ORDER BY
            year DESC,
            FIELD(LOWER(month),
                'january','february','march','april','may','june',
                'july','august','september','october','november','december'
            ) DESC,
            jobseek_id DESC
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
