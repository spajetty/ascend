<?php
require_once __DIR__ . '/../../api/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$response = ["exists" => false];

if (isset($data['email'])) {
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $data['email']);
    $stmt->execute();
    $stmt->store_result();

    $response["exists"] = $stmt->num_rows > 0;
    $stmt->close();
}

if (isset($data['contact'])) {
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE contact = ?");
    $stmt->bind_param("s", $data['contact']);
    $stmt->execute();
    $stmt->store_result();

    $response["exists"] = $stmt->num_rows > 0;
    $stmt->close();
}

echo json_encode($response);
?>