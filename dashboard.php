<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ASCEND Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! 🎉</h2>
    <p>You have successfully logged in to the ASCEND system.</p>
    <a href="logout.php">Logout</a>
</body>
</html>