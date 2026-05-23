<?php
session_start();
require_once __DIR__ . '/../../api/db.php';

$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    header("Location: /pages/auth/login.php?error=empty");
    exit;
}

$stmt = $conn->prepare("
    SELECT user_id, fname, lname, password, is_verified,
           login_attempts, locked_until, role, access
    FROM users
    WHERE email = ?
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: /pages/auth/login.php?error=invalid");
    exit;
}

$user = $result->fetch_assoc();
$now  = new DateTime('now', new DateTimeZone('UTC'));

// ── Locked? ───────────────────────────────────────────────────────────────
if ($user['locked_until']) {
    $lockedUntil = new DateTime($user['locked_until'], new DateTimeZone('UTC'));

    if ($now < $lockedUntil) {
        $remaining = $now->diff($lockedUntil);
        $mins      = ($remaining->h * 60) + $remaining->i;
        $secs      = $remaining->s;

        $waitMsg = $mins > 0
            ? "Account locked. Try again in {$mins} minute(s)."
            : "Account locked. Try again in {$secs} second(s).";

        header("Location: /pages/auth/login.php?error=locked&wait=" . urlencode($waitMsg));
        exit;
    }

    $reset = $conn->prepare("
        UPDATE users
        SET login_attempts = 0, last_failed_at = NULL, locked_until = NULL
        WHERE email = ?
    ");
    $reset->bind_param("s", $email);
    $reset->execute();

    $user['login_attempts'] = 0;
    $user['locked_until']   = null;
}

// ── Password ──────────────────────────────────────────────────────────────
if (!password_verify($password, $user['password'])) {

    $attempts = $user['login_attempts'] + 1;

    if ($attempts >= 3) {
        $lockExpiry = gmdate("Y-m-d H:i:s", time() + 3600);

        $stmt = $conn->prepare("
            UPDATE users
            SET login_attempts = ?, last_failed_at = UTC_TIMESTAMP(), locked_until = ?
            WHERE email = ?
        ");
        $stmt->bind_param("iss", $attempts, $lockExpiry, $email);
        $stmt->execute();

        header("Location: /pages/auth/login.php?error=locked&wait=" . urlencode("Account locked for 1 hour due to too many failed attempts."));
        exit;
    }

    $left = 3 - $attempts;

    $stmt = $conn->prepare("
        UPDATE users
        SET login_attempts = ?, last_failed_at = UTC_TIMESTAMP()
        WHERE email = ?
    ");
    $stmt->bind_param("is", $attempts, $email);
    $stmt->execute();

    header("Location: /pages/auth/login.php?error=invalid&left={$left}");
    exit;
}

// ── Email verified? ───────────────────────────────────────────────────────
if (!$user['is_verified']) {
    header("Location: /pages/auth/login.php?error=not_verified");
    exit;
}

// ── Access check ──────────────────────────────────────────────────────────
if ($user['access'] === 'Pending') {
    header("Location: /pages/auth/login.php?error=pending");
    exit;
}

if ($user['access'] === 'Declined') {
    header("Location: /pages/auth/login.php?error=declined");
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

header("Location: /pages/dashboard/dashboard.php");
exit;
?>