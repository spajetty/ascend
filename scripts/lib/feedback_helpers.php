<?php

function deleteFeedbackImage(mysqli $conn, int $imageId, string $imagePath): bool
{
    // adjust this to match your actual upload folder
    $fullPath = __DIR__ . '/../../uploads/feedback/' . basename($imagePath);

    if (file_exists($fullPath)) {
        if (!unlink($fullPath)) {
            error_log("[feedback_helpers] Failed to delete file: {$fullPath}");
            return false;
        }
    }

    $stmt = $conn->prepare("DELETE FROM feedback_images WHERE id = ?");
    $stmt->bind_param("i", $imageId);
    return $stmt->execute();
}

/**
 * Deletes an entire feedback entry: all its images, then the feedback row itself.
 */
function deleteFeedbackEntry(mysqli $conn, int $feedbackId): bool
{
    $stmt = $conn->prepare("SELECT id, image_path FROM feedback_images WHERE feedback_id = ?");
    $stmt->bind_param("i", $feedbackId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        deleteFeedbackImage($conn, $row['id'], $row['image_path']);
    }

    $stmt = $conn->prepare("DELETE FROM feedback WHERE id = ?");
    $stmt->bind_param("i", $feedbackId);
    return $stmt->execute();
}