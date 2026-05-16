<?php
header('Content-Type: application/json');

// Include database connection
require_once __DIR__ . '/../../api/db.php';

// Get JSON request
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($data['benef_id']) || !isset($data['history_id'])) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
  exit;
}

$benef_id = (int) $data['benef_id'];
$history_id = (int) $data['history_id'];

if ($benef_id <= 0 || $history_id <= 0) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Invalid beneficiary or history ID.']);
  exit;
}

// Delete from database - verify beneficiary ownership
$stmt = $conn->prepare('
  DELETE FROM beneficiary_activity_history 
  WHERE history_id = ? AND benef_id = ?
');

if (!$stmt) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
  exit;
}

$stmt->bind_param('ii', $history_id, $benef_id);
if (!$stmt->execute()) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Failed to delete timeline record: ' . $stmt->error]);
  $stmt->close();
  exit;
}

// Check if row was deleted
if ($stmt->affected_rows > 0) {
  echo json_encode(['success' => true, 'message' => 'Timeline record deleted successfully.']);
} else {
  http_response_code(404);
  echo json_encode(['success' => false, 'message' => 'Timeline record not found.']);
}

$stmt->close();
