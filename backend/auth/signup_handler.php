<?php
require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/../../helpers/user_helper.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$fname    = $_POST['fname'];
$lname    = $_POST['lname'];
$mi       = $_POST['mi'] ?? null;
$contact  = $_POST['contact'];
$email    = $_POST['email'];
$password = $_POST['password'];
$confirm  = $_POST['confirm_password'];

if ($password !== $confirm) {
    header("Location: /pages/auth/signup.php?error=password_mismatch");
    exit;
}

if (emailExists($email, $conn)) {
    header("Location: /pages/auth/signup.php?error=email_exists");
    exit;
}

if (contactExists($contact, $conn)) {
    header("Location: /pages/auth/signup.php?error=contact_exists");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO users (fname, lname, middle_initial, contact, email, password, is_verified)
    VALUES (?, ?, ?, ?, ?, ?, 0)
");
$stmt->bind_param("ssssss", $fname, $lname, $mi, $contact, $email, $hashedPassword);

if ($stmt->execute()) {

    $otp    = rand(100000, 999999);
    $expiry = gmdate("Y-m-d H:i:s", time() + (5 * 60));

    $stmt2 = $conn->prepare("UPDATE users SET otp_code=?, otp_expiry=? WHERE email=?");
    $stmt2->bind_param("sss", $otp, $expiry, $email);
    $stmt2->execute();

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'jayjayangadok21@gmail.com';   // ← your real Gmail
        $mail->Password   = 'nkqnarvivoomnghf';            // ← your real app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('jayjayangadok21@gmail.com', 'ASCEND System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email';
        $mail->Body    = "
            <div style='font-family:sans-serif'>
                <h2>Email Verification</h2>
                <p>Your OTP code is:</p>
                <h1 style='letter-spacing:5px;'>$otp</h1>
                <p>This expires in 5 minutes.</p>
            </div>
        ";

        $mail->send();

        header("Location: /pages/auth/otp.php?email=" . urlencode($email));
        exit;

    } catch (Exception $e) {
        $del = $conn->prepare("DELETE FROM users WHERE email=?");
        $del->bind_param("s", $email);
        $del->execute();

        header("Location: /pages/auth/signup.php?error=mail_failed");
        exit;
    }

} else {
    header("Location: /pages/auth/signup.php?error=server");
    exit;
}
?>