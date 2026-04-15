<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /pages/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ASCEND</title>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="/assets/css/output.css">

    <!-- Google Font: DM Sans -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[url('/assets/images/bg.png')] bg-cover bg-center bg-fixed font-['DM_Sans',Tahoma,Geneva,Verdana,sans-serif] flex items-center justify-center">

    <!-- Wrapper to ensure margins on both sides -->
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8 flex items-center justify-center">

        <!-- Login Card -->
        <div class="w-full max-w-md bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-6 sm:p-8 lg:p-10 text-center">

            <!-- Logo -->
            <div class="flex justify-center mb-4 sm:mb-5">
                <img src="/assets/images/logo.png" alt="Company Logo"
                     class="w-14 h-14 sm:w-16 sm:h-16 object-contain">
            </div>

            <!-- Title -->
            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800 mb-2">
                Public Employment Division
            </h2>
            <p class="text-sm sm:text-base text-gray-600 mb-6">
                Centralized Database System
            </p>

            <?php if (isset($_GET['error'])): ?>
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">
                    <?php
                        switch ($_GET['error']) {
                            case 'empty':
                                echo "Please fill in all fields.";
                                break;
                            case 'invalid':
                                echo "Invalid email or password.";
                                break;
                            case 'not_verified':
                                echo "Please verify your email before logging in.";
                                break;
                            default:
                                echo "An error occurred.";
                        }
                    ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="../../auth/login_handler.php" class="space-y-4 sm:space-y-5 text-left">

                <!-- Email Field -->
                <div>
                    <label for="email"
                           class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="admin@example.com"
                        required
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password"
                           class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    >
                </div>

                <!-- Sign In Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 sm:py-3 text-sm sm:text-base font-semibold rounded-lg hover:bg-blue-700 transition duration-200 shadow-md"
                >
                    Sign In
                </button>
            </form>

            <!-- Sign Up Link -->
            <p class="mt-6 text-xs sm:text-sm text-gray-600">
                Don’t have an account?
                <a href="signup.php"
                   class="text-blue-600 font-semibold hover:underline">
                    Sign Up
                </a>
            </p>
        </div>
    </div>

</body>
</html>