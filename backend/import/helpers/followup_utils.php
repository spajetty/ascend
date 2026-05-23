<?php

require_once __DIR__ . '/db_utils.php';

function recordImportFollowup(mysqli $conn, int $batchId, string $program, array $payload): bool {
    if ($batchId <= 0 || trim($program) === '' || !tableExists($conn, 'import_followups')) {
        return false;
    }

    $payloadJson = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($payloadJson === false) {
        return false;
    }

    $stmt = $conn->prepare('INSERT INTO import_followups (batch_id, program, payload, status) VALUES (?, ?, ?, "pending")');
    $stmt->bind_param('iss', $batchId, $program, $payloadJson);
    $stmt->execute();
    $stmt->close();

    return true;
}

function completeImportFollowup(mysqli $conn, int $batchId): bool {
    if ($batchId <= 0 || !tableExists($conn, 'import_followups')) {
        return false;
    }

    $stmt = $conn->prepare('DELETE FROM import_followups WHERE batch_id = ? AND status = "pending"');
    $stmt->bind_param('i', $batchId);
    $stmt->execute();
    $updated = $stmt->affected_rows > 0;
    $stmt->close();

    return $updated;
}

function getLatestPendingImportFollowupForUser(mysqli $conn, int $userId): ?array {
    if ($userId <= 0 || !tableExists($conn, 'import_followups') || !tableExists($conn, 'import_batches') || !tableHasColumn($conn, 'import_batches', 'uploaded_by')) {
        return null;
    }

    $stmt = $conn->prepare('
        SELECT f.id, f.batch_id, f.program, f.payload, f.created_at, f.updated_at
        FROM import_followups f
        INNER JOIN import_batches b ON b.batch_id = f.batch_id
        WHERE f.status = "pending" AND b.uploaded_by = ?
        ORDER BY f.created_at DESC, f.id DESC
        LIMIT 1
    ');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row) {
        return null;
    }

    $payload = json_decode((string)($row['payload'] ?? ''), true);
    if (!is_array($payload)) {
        $payload = [];
    }

    return [
        'followup_id' => (int)$row['id'],
        'batch_id' => (int)$row['batch_id'],
        'program' => (string)$row['program'],
        'created_at' => (string)($row['created_at'] ?? ''),
        'updated_at' => (string)($row['updated_at'] ?? ''),
        'payload' => $payload,
    ];
}