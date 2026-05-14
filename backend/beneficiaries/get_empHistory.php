<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../api/db.php';

$response = ['success' => false, 'employment' => []];

$benefId = (int)($_GET['id'] ?? 0);
if ($benefId <= 0) {
    $response['message'] = 'Missing or invalid beneficiary id.';
    echo json_encode($response);
    exit;
}

try {
    // ── Employment history ───────────────────────────────────────────────────
    $empSql = "
        SELECT
            eh.history_id,
            eh.classification  AS status,
            eh.date_of_record,
            DATE_FORMAT(eh.date_of_record, '%b %d, %Y') AS date_formatted,
            eh.notes,
            e.company_name
        FROM   emphistory eh
        LEFT JOIN employers e ON e.company_id = eh.company_id
        WHERE  eh.benef_id = ?
        ORDER  BY eh.date_of_record DESC, eh.created_at DESC
    ";
    $stmt = $conn->prepare($empSql);
    $stmt->bind_param('i', $benefId);
    $stmt->execute();
    $empRows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $response['success']    = true;
    $response['employment'] = $empRows;

} catch (Throwable $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
