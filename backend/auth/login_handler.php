<?php
session_start();
require_once __DIR__ . '/../../api/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: /pages/auth/login.php?error=empty");
    exit;
}

$stmt = $conn->prepare("
    SELECT user_id, fname, lname, password, is_verified
    FROM users
    WHERE email = ?
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        header("Location: /pages/auth/login.php?error=invalid");
        exit;
    }

    if (!$user['is_verified']) {
        header("Location: /pages/auth/login.php?error=not_verified");
        exit;
    }

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = strtok($user['fname'], ' ') . ' ' . $user['lname'];

    header("Location: /pages/dashboard/dashboard.php");
    exit;
} else {
    header("Location: /pages/auth/login.php?error=invalid");
    exit;
}

// ── Success ───────────────────────────────────────────────────────────────
$reset = $conn->prepare("
    UPDATE users
    SET login_attempts = 0, last_failed_at = NULL, locked_until = NULL
    WHERE email = ?
");
$reset->bind_param("s", $email);
$reset->execute();

$_SESSION['user_id']   = $user['user_id'];
$_SESSION['user_name'] = strtok($user['fname'], ' ') . ' ' . $user['lname'];
$_SESSION['user_role'] = $user['role'];   // 'Admin' or 'Staff'

/*
function warmDashboardCache(): void {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $sessionId = session_id();

    $url = $scheme . '://' . $host . '/backend/dashboard/fetch-details.php';

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        if ($ch === false) {
            return;
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTPHEADER     => [
                'Cookie: ' . session_name() . '=' . $sessionId,
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false || $response === null) {
            error_log('[login_handler] Failed to warm fetch-details cache after login.');
        }
        return;
    }

    $context = stream_context_create([
        'http' => [
            'method'  => 'GET',
            'header'  => 'Cookie: ' . session_name() . '=' . $sessionId . "\r\n",
            'timeout' => 30,
        ],
        'https' => [
            'method'  => 'GET',
            'header'  => 'Cookie: ' . session_name() . '=' . $sessionId . "\r\n",
            'timeout' => 30,
        ],
    ]);

    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        error_log('[login_handler] Failed to warm fetch-details cache after login.');
    }
}

warmDashboardCache();
*/

header("Location: /pages/dashboard/dashboard.php");
exit;
?>