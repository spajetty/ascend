<?php
require_once __DIR__ . '/../../api/db.php';

header("Content-Type: application/json");

$email = $_POST['email'] ?? null;
$otp   = $_POST['otp'] ?? null;

if (!$email || !$otp) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

$stmt = $conn->prepare("SELECT otp_code, otp_expiry FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

// Check OTP
if ($user['otp_code'] !== $otp) {
    echo json_encode(["success" => false, "message" => "Invalid OTP"]);
    exit;
}

// Check expiry
$now = gmdate("Y-m-d H:i:s");

if ($user['otp_expiry'] < $now) {
    echo json_encode(["success" => false, "message" => "OTP expired"]);
    exit;
}

// Mark verified
$stmt = $conn->prepare("UPDATE users SET is_verified=1, otp_code=NULL WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

echo json_encode(["success" => true]);