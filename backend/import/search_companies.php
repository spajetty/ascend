<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$query = trim($_GET['q'] ?? '');

if (strlen($query) < 1) {
    echo json_encode(['success' => true, 'companies' => []]);
    exit;
}

try {
    $like = '%' . $query . '%';
    $stmt = $conn->prepare("
        SELECT company_id, company_name
        FROM employers
        WHERE company_name LIKE ?
        ORDER BY company_name ASC
        LIMIT 20
    ");
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
    $companies = [];
    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }
    $stmt->close();

    echo json_encode(['success' => true, 'companies' => $companies]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
