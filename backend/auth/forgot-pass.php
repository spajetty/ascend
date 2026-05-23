<?php
session_start();
require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");

$action = $_POST['action'] ?? null;
$email  = trim($_POST['email'] ?? '');

if (!$action || !$email) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

// ──────────────────────────────────────────────────────────────────────────────
// ACTION: send_otp
// Verifies the email exists, generates an OTP, and emails it to the user.
// ──────────────────────────────────────────────────────────────────────────────
if ($action === 'send_otp') {

    // Rate-limit: 30 seconds between sends (keyed by email to survive page refresh)
    $sessionKey = 'fp_otp_last_sent_' . md5($email);
    if (isset($_SESSION[$sessionKey]) && time() - $_SESSION[$sessionKey] < 5) {
        echo json_encode(["success" => false, "message" => "Please wait before requesting another code."]);
        exit;
    }

    // Check email exists and is verified
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND is_verified = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Generic message to avoid user enumeration
        echo json_encode(["success" => false, "message" => "No verified account found with that email address."]);
        exit;
    }

    $otp    = rand(100000, 999999);
    $expiry = gmdate("Y-m-d H:i:s", time() + (5 * 60)); // 5 minutes

    $stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $otp, $expiry, $email);
    $stmt->execute();

    $_SESSION[$sessionKey] = time();

    // Send email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ascend.ped@gmail.com';
        $mail->Password   = 'ydmpejzmkxlrivof';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('ascend.ped@gmail.com', 'ASCEND System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Code - ASCEND';
        $mail->Body    = "
            <div style='font-family:sans-serif;max-width:480px;margin:auto;padding:32px;'>
                <h2 style='color:#1e40af;margin-bottom:8px;'>Password Reset</h2>
                <p style='color:#374151;'>You requested to reset your ASCEND account password. Use the code below:</p>
                <div style='margin:24px 0;text-align:center;'>
                    <span style='font-size:36px;font-weight:700;letter-spacing:8px;color:#1d4ed8;'>$otp</span>
                </div>
                <p style='color:#6b7280;font-size:14px;'>This code expires in <strong>5 minutes</strong>.</p>
                <p style='color:#6b7280;font-size:14px;'>If you did not request a password reset, please ignore this email.</p>
            </div>
        ";
        $mail->AltBody = "Your ASCEND password reset code is: $otp  (expires in 5 minutes)";

        $mail->SMTPDebug = 2; // Remove after fixing
        $mail->Debugoutput = function($str, $level) {
            error_log("PHPMailer: $str");
        };

        $mail->send();

        echo json_encode(["success" => true]);

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Failed to send email: " . $mail->ErrorInfo]);
    }
    exit;
}

// ──────────────────────────────────────────────────────────────────────────────
// ACTION: verify_otp
// Validates the OTP for the password-reset flow WITHOUT marking the account
// as verified (that's only for the signup flow).
// Sets a session flag so reset_password knows OTP was cleared.
// ──────────────────────────────────────────────────────────────────────────────
if ($action === 'verify_otp') {
    $otp = trim($_POST['otp'] ?? '');

    if (!$otp) {
        echo json_encode(["success" => false, "message" => "OTP is required."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT otp_code, otp_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    if (!$user) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    if ($user['otp_code'] !== $otp) {
        echo json_encode(["success" => false, "message" => "Invalid verification code."]);
        exit;
    }

    $now = gmdate("Y-m-d H:i:s");
    if ($user['otp_expiry'] < $now) {
        echo json_encode(["success" => false, "message" => "Verification code has expired. Please request a new one."]);
        exit;
    }

    // Clear OTP — user must now proceed to reset_password
    $stmt = $conn->prepare("UPDATE users SET otp_code = NULL, otp_expiry = NULL WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Store a short-lived reset token in session so reset_password can't be
    // called without completing the OTP step first
    $_SESSION['fp_otp_verified_' . md5($email)] = time();

    echo json_encode(["success" => true]);
    exit;
}

// ──────────────────────────────────────────────────────────────────────────────
// ACTION: reset_password
// Updates the user's password. Requires the OTP step to have been completed.
// ──────────────────────────────────────────────────────────────────────────────
if ($action === 'reset_password') {
    $password = $_POST['password'] ?? '';

    // Guard: OTP must have been verified in this session
    $sessionKey = 'fp_otp_verified_' . md5($email);
    if (!isset($_SESSION[$sessionKey])) {
        echo json_encode(["success" => false, "message" => "Unauthorized. Please complete email verification first."]);
        exit;
    }

    // Token expires after 15 minutes
    if (time() - $_SESSION[$sessionKey] > 900) {
        unset($_SESSION[$sessionKey]);
        echo json_encode(["success" => false, "message" => "Your reset session has expired. Please start over."]);
        exit;
    }

    if (strlen($password) < 8) {
        echo json_encode(["success" => false, "message" => "Password must be at least 8 characters."]);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed, $email);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        echo json_encode(["success" => false, "message" => "Failed to update password. Please try again."]);
        exit;
    }

    // Clean up session flag
    unset($_SESSION[$sessionKey]);

    echo json_encode(["success" => true]);
    exit;
}

// Unknown action
echo json_encode(["success" => false, "message" => "Unknown action."]);
?>