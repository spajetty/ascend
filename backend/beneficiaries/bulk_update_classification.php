<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/../import/helpers/formatting_utils.php';

// ── Parse request ─────────────────────────────────────────────────────────────
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['ids']) || !is_array($data['ids'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No beneficiary IDs provided.']);
    exit;
}

// Sanitise IDs
$ids = array_values(array_filter(array_map('intval', $data['ids']), fn($id) => $id > 0));
if (empty($ids)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No valid IDs provided.']);
    exit;
}

$status = isset($data['status']) ? trim($data['status']) : '';

if ($status === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No status provided.']);
    exit;
}

// ── Normalise status to title case (e.g. "Not Qualified" -> "Not Qualified")
$status = titleCase($status);

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types        = 's' . str_repeat('i', count($ids));
$params       = array_merge([$status], $ids);

$stmt = $conn->prepare(
    "UPDATE beneficiaries SET classification = ? WHERE benef_id IN ($placeholders)"
);
$stmt->bind_param($types, ...$params);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
    $stmt->close();
    exit;
}

$updated = $stmt->affected_rows;
$stmt->close();

echo json_encode([
    'success' => true,
    'updated' => $updated,
    'message' => "$updated beneficiar" . ($updated === 1 ? 'y' : 'ies') . ' updated successfully.',
]);
