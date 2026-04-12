<?php
include 'config.php';

$username = "admin";
$plainPassword = "admin123";

// Hash the password securely
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Prevent duplicate users
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "User already exists!";
} else {
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);
    $stmt->execute();
    echo "User created successfully!<br>";
    echo "Username: admin<br>Password: admin123";
}

$stmt->close();
$conn->close();
?>