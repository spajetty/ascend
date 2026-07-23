<?php
session_start();

require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../api/RateLimiter.php';

header("Content-Type: application/json");

$email = $_POST['email'] ?? null;

if (!$email) {
    echo json_encode(["success" => false, "message" => "Email required"]);
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!RateLimiter::check($conn, 'send_otp_ip', $ip, 10, 3600)) {
    echo json_encode(["success" => false, "message" => "Too many OTP requests from your IP. Try again later."]);
    exit;
}

if (!RateLimiter::check($conn, 'send_otp_email', $email, 1, 60)) {
    echo json_encode(["success" => false, "message" => "Please wait before resending OTP."]);
    exit;
}

$otp    = rand(100000, 999999);
$expiry = gmdate("Y-m-d H:i:s", time() + (5 * 60));

$stmt = $conn->prepare("UPDATE users SET otp_code=?, otp_expiry=? WHERE email=?");
$stmt->bind_param("sss", $otp, $expiry, $email);
$stmt->execute();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ascend.ped@gmail.com';
    $mail->Password   = 'ydmpejzmkxlrivof';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('jayjayangadok21@gmail.com', 'ASCEND System');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = "
        <h2>Email Verification</h2>
        <p>Your OTP code is:</p>
        <h1 style='letter-spacing:4px;'>$otp</h1>
        <p>This expires in 5 minutes.</p>
    ";

    $mail->send();

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $mail->ErrorInfo]);
}
?>