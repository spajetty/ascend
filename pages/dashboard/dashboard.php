<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /pages/auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ASCEND</title>
    <link rel="stylesheet" href="/assets/css/output.css">
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="bg-white shadow-xl rounded-2xl p-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">
            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! 🎉
        </h1>
        <p class="text-gray-600 mb-6">
            This is your temporary dashboard. You are now successfully logged in.
        </p>

        <a href="/auth/logout.php"
           class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition">
            Logout
        </a>
    </div>

</body>
</html>