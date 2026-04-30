<?php
require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/../../helpers/user_helper.php';

// Get POST data
$fname   = $_POST['fname'];
$lname   = $_POST['lname'];
$mi      = $_POST['mi'] ?? null;  // ✅ Place it here
$contact = $_POST['contact'];
$email   = $_POST['email'];
$password = $_POST['password'];
$confirm  = $_POST['confirm_password'];

// Basic validation
if ($password !== $confirm) {
    header("Location: /pages/auth/signup.php?error=password_mismatch");
    exit;
}

// Check duplicates
if (emailExists($email, $conn)) {
    header("Location: /pages/auth/signup.php?error=email_exists");
    exit;
}

if (contactExists($contact, $conn)) {
    header("Location: /pages/auth/signup.php?error=contact_exists");
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $conn->prepare("
    INSERT INTO users (fname, lname, middle_initial, contact, email, password, is_verified)
    VALUES (?, ?, ?, ?, ?, ?, 0)
");

$stmt->bind_param("ssssss", $fname, $lname, $mi, $contact, $email, $hashedPassword);

if ($stmt->execute()) {
    header("Location: /pages/auth/login.php?signup=success");
    exit;
} else {
    header("Location: /pages/auth/signup.php?error=server");
    exit;
}
?>