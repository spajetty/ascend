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
           login_attempts, locked_until
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

// ── Check if account is currently locked ──────────────────────────────────
if ($user['locked_until']) {
    $lockedUntil = new DateTime($user['locked_until'], new DateTimeZone('UTC'));

    if ($now < $lockedUntil) {
        // Still locked — calculate remaining time
        $remaining = $now->diff($lockedUntil);
        $mins      = ($remaining->h * 60) + $remaining->i;
        $secs      = $remaining->s;

        $waitMsg = $mins > 0
            ? "Account locked. Try again in {$mins} minute(s)."
            : "Account locked. Try again in {$secs} second(s).";

        header("Location: /pages/auth/login.php?error=locked&wait=" . urlencode($waitMsg));
        exit;
    }

    // Lock has expired — reset the counter
    $reset = $conn->prepare("
        UPDATE users
        SET login_attempts = 0,
            last_failed_at = NULL,
            locked_until   = NULL
        WHERE email = ?
    ");
    $reset->bind_param("s", $email);
    $reset->execute();

    $user['login_attempts'] = 0;
    $user['locked_until']   = null;
}

// ── Verify password ───────────────────────────────────────────────────────
if (!password_verify($password, $user['password'])) {

    $attempts = $user['login_attempts'] + 1;

    if ($attempts >= 3) {
        // Lock the account for 1 hour
        $lockExpiry = gmdate("Y-m-d H:i:s", time() + 3600);

        $stmt = $conn->prepare("
            UPDATE users
            SET login_attempts = ?,
                last_failed_at = UTC_TIMESTAMP(),
                locked_until   = ?
            WHERE email = ?
        ");
        $stmt->bind_param("iss", $attempts, $lockExpiry, $email);
        $stmt->execute();

        header("Location: /pages/auth/login.php?error=locked&wait=" . urlencode("Account locked for 1 hour due to too many failed attempts."));
        exit;
    }

    // Not yet locked — increment attempts
    $left = 3 - $attempts;

    $stmt = $conn->prepare("
        UPDATE users
        SET login_attempts = ?,
            last_failed_at = UTC_TIMESTAMP()
        WHERE email = ?
    ");
    $stmt->bind_param("is", $attempts, $email);
    $stmt->execute();

    header("Location: /pages/auth/login.php?error=invalid&left={$left}");
    exit;
}

// ── Check email verification ───────────────────────────────────────────────
if (!$user['is_verified']) {
    header("Location: /pages/auth/login.php?error=not_verified");
    exit;
}

// ── Success — reset lock counters and start session ───────────────────────
$reset = $conn->prepare("
    UPDATE users
    SET login_attempts = 0,
        last_failed_at = NULL,
        locked_until   = NULL
    WHERE email = ?
");
$reset->bind_param("s", $email);
$reset->execute();

$_SESSION['user_id']   = $user['user_id'];
$_SESSION['user_name'] = strtok($user['fname'], ' ') . ' ' . $user['lname'];

header("Location: /pages/dashboard/dashboard.php");
exit;
?>