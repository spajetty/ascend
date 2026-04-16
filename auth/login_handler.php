<?php
session_start();
require_once __DIR__ . '/../api/db.php';

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
?>