<?php
header('Content-Type: application/json');

// Include database connection
require_once __DIR__ . '/../../api/db.php';

// Get JSON request
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($data['benef_id']) || !isset($data['field']) || !isset($data['doc_url'])) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
  exit;
}

$benef_id = (int) $data['benef_id'];
$field = trim($data['field']);
$doc_url = trim($data['doc_url']);

// Validate field name (allowed document fields)
$allowed_fields = ['proof_of_residency', 'latest_credential', 'letter_of_intent', 'reco_letter', 'resume', 'tor', 'brgy_clearance', 'nbi_clearance', 'birth_cert', 'tesda_cert'];
if (!in_array($field, $allowed_fields)) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Invalid document field.']);
  exit;
}

// Validate URL
if (empty($doc_url) || !filter_var($doc_url, FILTER_VALIDATE_URL)) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Invalid URL format.']);
  exit;
}

// Update in database - use dynamic column name
$query = 'UPDATE docs_benef SET ' . $field . ' = ? WHERE benef_id = ?';
$stmt = $conn->prepare($query);

if (!$stmt) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
  exit;
}

$stmt->bind_param('si', $doc_url, $benef_id);
if (!$stmt->execute()) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Failed to update document: ' . $stmt->error]);
  $stmt->close();
  exit;
}

// Check if row was updated
if ($stmt->affected_rows > 0) {
  echo json_encode(['success' => true, 'message' => 'Document updated successfully.']);
} else {
  http_response_code(404);
  echo json_encode(['success' => false, 'message' => 'Document not found.']);
}

$stmt->close();
