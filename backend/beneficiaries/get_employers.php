<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../api/db.php';

try {
    $result = $conn->query('SELECT company_id, company_name, est_type, industry, city FROM employers ORDER BY company_name ASC');

    $employers = [];
    while ($row = $result->fetch_assoc()) {
        $employers[] = [
            'company_id' => (int)$row['company_id'],
            'company_name' => (string)$row['company_name'],
            'est_type' => (string)($row['est_type'] ?? ''),
            'industry' => (string)($row['industry'] ?? ''),
            'city' => (string)($row['city'] ?? ''),
        ];
    }

    echo json_encode(['success' => true, 'employers' => $employers]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
