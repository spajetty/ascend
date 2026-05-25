<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../api/db.php';

// ── Parse request ─────────────────────────────────────────────────────────────
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['ids']) || !is_array($data['ids'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No beneficiary IDs provided.']);
    exit;
}

// Sanitise: keep only positive integers
$ids = array_values(array_filter(array_map('intval', $data['ids']), fn($id) => $id > 0));

if (empty($ids)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No valid IDs provided.']);
    exit;
}

// ── Build a safe IN (?,?,…) placeholder ───────────────────────────────────────
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types        = str_repeat('i', count($ids));

// ── Run inside a transaction ───────────────────────────────────────────────────
$conn->begin_transaction();

try {
    // Tables that reference beneficiaries via benef_id (delete children first)
    // Canonical child tables that reference beneficiaries by `benef_id`.
    // Keep this list minimal and canonical to avoid confusion with legacy/variant names.
    $childTables = [
        'docs_benef',
        'emphistory',
        'firstjobseek',
        'beneficiary_activity_history',
        'jobfair',
        'jobmatch',
        'gip',
        'spes',
        'wiirp',
        'whip',
    ];

    foreach ($childTables as $table) {
        // Check the table exists before attempting delete (graceful degradation)
        $check = $conn->query("SHOW TABLES LIKE '$table'");
        if ($check && $check->num_rows > 0) {
            $stmt = $conn->prepare("DELETE FROM `$table` WHERE benef_id IN ($placeholders)");
            $stmt->bind_param($types, ...$ids);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Finally delete the beneficiaries themselves
    $stmt = $conn->prepare("DELETE FROM beneficiaries WHERE benef_id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $deleted = $stmt->affected_rows;
    $stmt->close();

    $conn->commit();

    echo json_encode([
        'success'  => true,
        'deleted'  => $deleted,
        'message'  => "$deleted beneficiar" . ($deleted === 1 ? 'y' : 'ies') . ' deleted successfully.',
    ]);

} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()]);
}
