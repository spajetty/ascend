<?php
session_start();

require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");

$email = $_POST['email'] ?? null;

if (!$email) {
    echo json_encode(["success" => false, "message" => "Email required"]);
    exit;
}

if (isset($_SESSION['otp_last_sent'])) {
    if (time() - $_SESSION['otp_last_sent'] < 30) {
        echo json_encode(["success" => false, "message" => "Please wait before resending OTP"]);
        exit;
    }
}

$otp    = rand(100000, 999999);
$expiry = gmdate("Y-m-d H:i:s", time() + (5 * 60));

$stmt = $conn->prepare("UPDATE users SET otp_code=?, otp_expiry=? WHERE email=?");
$stmt->bind_param("sss", $otp, $expiry, $email);
$stmt->execute();

$_SESSION['otp_last_sent'] = time();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jayjayangadok21@gmail.com';
    $mail->Password   = 'nkqnarvivoomnghf';
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