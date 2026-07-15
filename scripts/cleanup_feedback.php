<?php

require_once __DIR__ . '/../api/db.php';
require_once __DIR__ . '/lib/feedback_helpers.php';

$cutoff = date('Y-m-d H:i:s', strtotime('-90 days'));

echo "[" . date('Y-m-d H:i:s') . "] Starting feedback cleanup (cutoff: {$cutoff})\n";

$stmt = $conn->prepare("
    SELECT id
    FROM feedback
    WHERE status IN ('resolved', 'closed')
      AND created_at < ?
");
$stmt->bind_param("s", $cutoff);
$stmt->execute();
$result = $stmt->get_result();

$deletedCount = 0;

while ($row = $result->fetch_assoc()) {
    if (deleteFeedbackEntry($conn, $row['id'])) {
        $deletedCount++;
    } else {
        error_log("[cleanup_feedback] Failed to delete feedback id {$row['id']}");
    }
}

echo "[" . date('Y-m-d H:i:s') . "] Cleanup done. Deleted {$deletedCount} feedback entr" . ($deletedCount === 1 ? 'y' : 'ies') . ".\n";