<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

try {
    $query = "
        SELECT
            company_id,
            company_name,
            month,
            year,
            accreditation,
            est_type,
            industry,
            city
        FROM employers
        ORDER BY year DESC, month DESC, company_name ASC
    ";

    $result = $conn->query($query);
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Get summary stats
    $totalResult = $conn->query("SELECT COUNT(*) as total FROM employers");
    $newResult = $conn->query("SELECT COUNT(*) as total FROM employers WHERE accreditation = 'new'");
    $renewResult = $conn->query("SELECT COUNT(*) as total FROM employers WHERE accreditation = 'renew'");

    $stats = [
        'total' => $totalResult->fetch_assoc()['total'] ?? 0,
        'new' => $newResult->fetch_assoc()['total'] ?? 0,
        'renew' => $renewResult->fetch_assoc()['total'] ?? 0
    ];

    echo json_encode(['success' => true, 'data' => $data, 'stats' => $stats]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
