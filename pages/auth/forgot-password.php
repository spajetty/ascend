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
    <title>Forgot Password - ASCEND</title>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="/assets/css/output.css">

    <!-- Google Font: DM Sans -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        .otp-box {
            width: 45px;
            height: 55px;
            text-align: center;
            font-size: 20px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.15s;
        }
        .otp-box:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }

        /* Step transition */
        .step { display: none; }
        .step.active { display: block; }

        /* Fade-in animation */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .step.active { animation: fadeSlideUp 0.35s ease both; }

        /* Strength bar */
        #strengthBar {
            transition: width 0.3s ease, background-color 0.3s ease;
        }
    </style>
</head>
<body class="min-h-screen bg-[url('/assets/images/bg.png')] bg-cover bg-center bg-fixed font-['DM_Sans',Tahoma,Geneva,Verdana,sans-serif] flex items-center justify-center">

    <div class="w-full px-4 sm:px-6 lg:px-8 py-8 flex items-center justify-center">

        <!-- Card -->
        <div class="w-full max-w-md bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-6 sm:p-8 lg:p-10 text-center">

            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="/assets/images/logo.png" alt="Company Logo" class="w-14 h-14 sm:w-16 sm:h-16 object-contain">
            </div>

            <!-- ─────────────────────────────────────────────
                 STEP 1 — Enter Email
            ───────────────────────────────────────────── -->
            <div id="step1" class="step active">
                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800 mb-1">Forgot Password</h2>
                <p class="text-sm text-gray-500 mb-6">Enter your registered email to receive a verification code.</p>

                <div class="text-left space-y-4">
                    <div>
                        <label for="emailInput" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="emailInput"
                            placeholder="admin@example.com"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                    </div>

                    <p id="step1Error" class="text-sm text-red-500 hidden"></p>

                    <button id="sendOtpBtn"
                        class="w-full bg-blue-600 text-white py-2.5 text-sm font-semibold rounded-lg hover:bg-blue-700 transition duration-200 shadow-md flex items-center justify-center gap-2">
                        <span id="sendOtpText">Send Verification Code</span>
                        <svg id="sendOtpSpinner" class="hidden animate-spin h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </button>
                </div>

                <p class="mt-6 text-xs sm:text-sm text-gray-600">
                    Remember your password?
                    <a href="login.php" class="text-blue-600 font-semibold hover:underline">Sign In</a>
                </p>
            </div>

            <!-- ─────────────────────────────────────────────
                 STEP 2 — Verify OTP
            ───────────────────────────────────────────── -->
            <div id="step2" class="step">
                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800 mb-1">Verify Your Email</h2>
                <p class="text-sm text-gray-500 mb-1">Enter the 6-digit code sent to</p>
                <p id="emailDisplay" class="text-sm font-semibold text-blue-600 mb-6"></p>

                <!-- OTP Boxes -->
                <div class="flex justify-between gap-2 mb-6">
                    <input class="otp-box" maxlength="1">
                    <input class="otp-box" maxlength="1">
                    <input class="otp-box" maxlength="1">
                    <input class="otp-box" maxlength="1">
                    <input class="otp-box" maxlength="1">
                    <input class="otp-box" maxlength="1">
                </div>

                <p id="step2Error" class="text-sm text-red-500 mb-3 hidden"></p>

                <button id="verifyOtpBtn"
                    class="w-full bg-blue-600 text-white py-2.5 text-sm font-semibold rounded-lg hover:bg-blue-700 transition duration-200 shadow-md flex items-center justify-center gap-2">
                    <span id="verifyOtpText">Verify Code</span>
                    <svg id="verifyOtpSpinner" class="hidden animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                </button>

                <p class="text-center mt-4 text-sm text-gray-600">
                    Didn't receive the code?
                    <button id="resendBtn" class="text-blue-600 font-semibold hover:underline">Resend OTP</button>
                </p>
                <p id="resendTimer" class="text-center text-xs text-gray-500 mt-1"></p>
            </div>

            <!-- ─────────────────────────────────────────────
                 STEP 3 — New Password
            ───────────────────────────────────────────── -->
            <div id="step3" class="step">
                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800 mb-1">Set New Password</h2>
                <p class="text-sm text-gray-500 mb-6">Choose a strong password for your account.</p>

                <div class="text-left space-y-4">

                    <!-- New Password -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="newPassword"
                                placeholder="Enter new password"
                                class="w-full px-4 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            >
                            <button type="button" id="toggleNew"
                                class="absolute right-3 top-0 h-full flex items-center text-gray-500 hover:text-gray-700">
                                <svg class="eye-open h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="eye-closed h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.223 6.223A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.132 5.411"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 00-3-3"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Strength indicator -->
                        <div class="mt-2 h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                            <div id="strengthBar" class="h-full w-0 rounded-full bg-red-400"></div>
                        </div>
                        <p id="strengthLabel" class="text-xs text-gray-400 mt-1"></p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="confirmPassword"
                                placeholder="Re-enter new password"
                                class="w-full px-4 py-2.5 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            >
                            <button type="button" id="toggleConfirm"
                                class="absolute right-3 top-0 h-full flex items-center text-gray-500 hover:text-gray-700">
                                <svg class="eye-open h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="eye-closed h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.223 6.223A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.132 5.411"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 00-3-3"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        <p id="matchError" class="text-xs text-red-500 mt-1 hidden">Passwords do not match.</p>
                    </div>

                    <p id="step3Error" class="text-sm text-red-500 hidden"></p>

                    <button id="resetBtn"
                        class="w-full bg-blue-600 text-white py-2.5 text-sm font-semibold rounded-lg hover:bg-blue-700 transition duration-200 shadow-md flex items-center justify-center gap-2">
                        <span id="resetText">Reset Password</span>
                        <svg id="resetSpinner" class="hidden animate-spin h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- ─────────────────────────────────────────────
                 STEP 4 — Success
            ───────────────────────────────────────────── -->
            <div id="step4" class="step">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Password Reset!</h2>
                <p class="text-sm text-gray-500 mb-6">Your password has been updated successfully. You can now sign in with your new password.</p>
                <a href="login.php"
                    class="block w-full bg-blue-600 text-white py-2.5 text-sm font-semibold rounded-lg hover:bg-blue-700 transition duration-200 shadow-md text-center">
                    Go to Sign In
                </a>
            </div>

        </div>
    </div>

    <script>
        // ── State ──────────────────────────────────────────
        let currentEmail = '';

        // ── Helpers ────────────────────────────────────────
        function goToStep(n) {
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.getElementById('step' + n).classList.add('active');
        }

        function showError(id, msg) {
            const el = document.getElementById(id);
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        function clearError(id) {
            const el = document.getElementById(id);
            el.textContent = '';
            el.classList.add('hidden');
        }

        function setLoading(btnId, textId, spinnerId, loading, label = '') {
            const btn  = document.getElementById(btnId);
            const text = document.getElementById(textId);
            const spin = document.getElementById(spinnerId);
            btn.disabled = loading;
            btn.classList.toggle('opacity-70', loading);
            btn.classList.toggle('cursor-not-allowed', loading);
            spin.classList.toggle('hidden', !loading);
            if (label) text.textContent = label;
        }

        // ── STEP 1 — Send OTP ─────────────────────────────
        document.getElementById('sendOtpBtn').addEventListener('click', async () => {
            clearError('step1Error');
            const email = document.getElementById('emailInput').value.trim();

            if (!email) {
                showError('step1Error', 'Please enter your email address.');
                return;
            }

            setLoading('sendOtpBtn', 'sendOtpText', 'sendOtpSpinner', true, 'Sending...');

            try {
                const res  = await fetch('/backend/auth/forgot-pass.php', {
                    method: 'POST',
                    body: new URLSearchParams({ action: 'send_otp', email })
                });
                const data = await res.json();

                if (data.success) {
                    currentEmail = email;
                    document.getElementById('emailDisplay').textContent = email;
                    goToStep(2);
                    startResendCooldown();
                } else {
                    showError('step1Error', data.message || 'Failed to send code.');
                }
            } catch {
                showError('step1Error', 'Server error. Please try again.');
            } finally {
                setLoading('sendOtpBtn', 'sendOtpText', 'sendOtpSpinner', false, 'Send Verification Code');
            }
        });

        // ── STEP 2 — OTP boxes ───────────────────────────
        const otpInputs = document.querySelectorAll('.otp-box');

        otpInputs.forEach((input, i) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/[^0-9]/g, '');
                if (input.value && i < otpInputs.length - 1) otpInputs[i + 1].focus();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    if (!input.value && i > 0) otpInputs[i - 1].focus();
                    else input.value = '';
                }
            });
        });

        document.getElementById('step2').addEventListener('animationend', () => {
            otpInputs[0].focus();
        });

        document.getElementById('verifyOtpBtn').addEventListener('click', async () => {
            clearError('step2Error');
            let otp = '';
            otpInputs.forEach(i => otp += i.value);

            if (otp.length !== 6) {
                showError('step2Error', 'Please enter the complete 6-digit code.');
                return;
            }

            setLoading('verifyOtpBtn', 'verifyOtpText', 'verifyOtpSpinner', true, 'Verifying...');

            try {
                const res  = await fetch('/backend/auth/forgot-pass.php', {
                    method: 'POST',
                    body: new URLSearchParams({ action: 'verify_otp', email: currentEmail, otp })
                });
                const data = await res.json();

                if (data.success) {
                    goToStep(3);
                } else {
                    showError('step2Error', data.message || 'Invalid or expired code.');
                }
            } catch {
                showError('step2Error', 'Server error. Please try again.');
            } finally {
                setLoading('verifyOtpBtn', 'verifyOtpText', 'verifyOtpSpinner', false, 'Verify Code');
            }
        });

        // Resend OTP
        let cooldownInterval;

        function startResendCooldown() {
            const btn   = document.getElementById('resendBtn');
            const timer = document.getElementById('resendTimer');
            let secs    = 30;

            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            timer.textContent = `Resend available in ${secs}s`;

            cooldownInterval = setInterval(() => {
                secs--;
                if (secs > 0) {
                    timer.textContent = `Resend available in ${secs}s`;
                } else {
                    clearInterval(cooldownInterval);
                    timer.textContent = '';
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }, 1000);
        }

        document.getElementById('resendBtn').addEventListener('click', async () => {
            clearError('step2Error');

            try {
                const res  = await fetch('/backend/auth/forgot-pass.php', {
                    method: 'POST',
                    body: new URLSearchParams({ action: 'send_otp', email: currentEmail })
                });
                const data = await res.json();

                if (data.success) {
                    otpInputs.forEach(i => i.value = '');
                    otpInputs[0].focus();
                    startResendCooldown();
                } else {
                    showError('step2Error', data.message || 'Failed to resend code.');
                }
            } catch {
                showError('step2Error', 'Server error. Please try again.');
            }
        });

        // ── STEP 3 — Password toggles & strength ────────
        function setupToggle(btnId, inputId) {
            document.getElementById(btnId).addEventListener('click', () => {
                const input    = document.getElementById(inputId);
                const btn      = document.getElementById(btnId);
                const isPass   = input.type === 'password';
                input.type     = isPass ? 'text' : 'password';
                btn.querySelector('.eye-open').classList.toggle('hidden', isPass);
                btn.querySelector('.eye-closed').classList.toggle('hidden', !isPass);
            });
        }
        setupToggle('toggleNew',     'newPassword');
        setupToggle('toggleConfirm', 'confirmPassword');

        document.getElementById('newPassword').addEventListener('input', function () {
            const val  = this.value;
            const bar  = document.getElementById('strengthBar');
            const lbl  = document.getElementById('strengthLabel');
            let score  = 0;

            if (val.length >= 8)              score++;
            if (/[A-Z]/.test(val))            score++;
            if (/[0-9]/.test(val))            score++;
            if (/[^A-Za-z0-9]/.test(val))     score++;

            const levels = [
                { w: '0%',   color: '',                    text: '' },
                { w: '25%',  color: 'bg-red-400',          text: 'Weak' },
                { w: '50%',  color: 'bg-orange-400',       text: 'Fair' },
                { w: '75%',  color: 'bg-yellow-400',       text: 'Good' },
                { w: '100%', color: 'bg-green-500',        text: 'Strong' },
            ];

            const lvl = val.length === 0 ? levels[0] : levels[score];
            bar.style.width = lvl.w;
            bar.className   = 'h-full rounded-full transition-all duration-300 ' + lvl.color;
            lbl.textContent = lvl.text;
        });

        document.getElementById('confirmPassword').addEventListener('input', function () {
            const match = document.getElementById('matchError');
            if (this.value && this.value !== document.getElementById('newPassword').value) {
                match.classList.remove('hidden');
            } else {
                match.classList.add('hidden');
            }
        });

        document.getElementById('resetBtn').addEventListener('click', async () => {
            clearError('step3Error');
            const newPass     = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmPassword').value;

            if (!newPass || newPass.length < 8) {
                showError('step3Error', 'Password must be at least 8 characters.');
                return;
            }
            if (newPass !== confirmPass) {
                showError('step3Error', 'Passwords do not match.');
                return;
            }

            setLoading('resetBtn', 'resetText', 'resetSpinner', true, 'Resetting...');

            try {
                const res  = await fetch('/backend/auth/forgot-pass.php', {
                    method: 'POST',
                    body: new URLSearchParams({ action: 'reset_password', email: currentEmail, password: newPass })
                });
                const data = await res.json();

                if (data.success) {
                    goToStep(4);
                } else {
                    showError('step3Error', data.message || 'Failed to reset password.');
                }
            } catch {
                showError('step3Error', 'Server error. Please try again.');
            } finally {
                setLoading('resetBtn', 'resetText', 'resetSpinner', false, 'Reset Password');
            }
        });
    </script>
</body>
</html>