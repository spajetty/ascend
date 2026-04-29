<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /pages/dashboard/dashboard.php");
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
            <form method="POST" action="../../backend/auth/login_handler.php" class="space-y-4 sm:space-y-5 text-left">

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

    <div class="relative w-full">
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter your password"
            required
            class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-10 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
        >

        <button
            type="button"
            id="togglePassword"
            class="absolute right-3 top-0 h-full flex items-center text-gray-500 hover:text-gray-700"
        >
            <!-- Eye Open -->
            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5
                       c4.478 0 8.268 2.943 9.542 7
                       -1.274 4.057-5.064 7-9.542 7
                       -4.477 0-8.268-2.943-9.542-7z"/>
            </svg>

            <!-- Eye Closed -->
            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 hidden" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19
                       c-4.478 0-8.268-2.943-9.542-7
                       a9.956 9.956 0 012.293-3.95"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6.223 6.223A9.953 9.953 0 0112 5
                       c4.478 0 8.268 2.943 9.542 7
                       a9.97 9.97 0 01-4.132 5.411"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 00-3-3"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3l18 18"/>
            </svg>
        </button>
    </div>
</div>

                <!-- Sign In Button -->
                <button
                    id="loginBtn"
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 sm:py-3 text-sm sm:text-base font-semibold rounded-lg hover:bg-blue-700 transition duration-200 shadow-md flex items-center justify-center gap-2"
                >
                    <span id="loginText">Sign In</span>
                    <svg id="loginSpinner" class="hidden animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
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

    <script>

            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';

        eyeOpen.classList.toggle('hidden', isPassword);
        eyeClosed.classList.toggle('hidden', !isPassword);
    });

        document.addEventListener("DOMContentLoaded", function () {
            const loginForm = document.querySelector('form');
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loginSpinner = document.getElementById('loginSpinner');

            loginForm.addEventListener('submit', function (e) {
                e.preventDefault(); // Prevent immediate submission

                // Disable button and show loading state
                loginBtn.disabled = true;
                loginBtn.classList.add('opacity-70', 'cursor-not-allowed');
                loginText.textContent = 'Signing In...';
                loginSpinner.classList.remove('hidden');

                setTimeout(() => {
                    loginForm.submit();
                }, 3000);
            });
        });
    </script>
</body>
</html>

