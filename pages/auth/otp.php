<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - ASCEND</title>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="/assets/css/output.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-[url('/assets/images/bg.png')] bg-cover bg-center bg-fixed font-['DM_Sans',Tahoma,Geneva,Verdana,sans-serif] flex items-center justify-center">

<div class="w-full px-4 sm:px-6 lg:px-8 py-10 flex items-center justify-center">

    <!-- Card -->
    <div class="w-full max-w-md bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-6 sm:p-8 lg:p-10">

        <!-- Header -->
        <div class="text-center mb-6">
            <div class="flex justify-center mb-4">
                <img src="/assets/images/logo.png" alt="Logo" class="w-16 h-16 object-contain">
            </div>

            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">
                Verify Your Email
            </h2>

            <p class="text-sm sm:text-base text-gray-600 mt-1">
                Enter the 6-digit code sent to your email
            </p>
        </div>

        <!-- OTP Boxes -->
        <div class="flex justify-between gap-2 mb-6">
            <input class="otp-box" maxlength="1">
            <input class="otp-box" maxlength="1">
            <input class="otp-box" maxlength="1">
            <input class="otp-box" maxlength="1">
            <input class="otp-box" maxlength="1">
            <input class="otp-box" maxlength="1">
        </div>

        <!-- Verify Button -->
        <button id="verifyBtn"
            class="w-full bg-blue-600 text-white py-3 rounded-lg flex items-center justify-center gap-2">
            <span id="btnText">Verify Code</span>

            <svg id="spinner" class="hidden animate-spin h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
        </button>

        <p class="text-center mt-4 text-sm text-gray-600">
            Didn’t receive the code?
            <button id="resendBtn" class="text-blue-600 font-semibold hover:underline disabled:text-gray-400">
                Resend OTP
            </button>
        </p>

        <p id="resendTimer" class="text-center text-xs text-gray-500 mt-1"></p>

        <!-- Message -->
        <p id="message" class="text-center mt-4 text-sm"></p>

        <!-- Back to login -->
        <p class="mt-6 text-center text-sm text-gray-600">
            Already verified?
            <a href="login.php" class="text-blue-600 font-semibold hover:underline">
                Sign In
            </a>
        </p>

    </div>
</div>

<style>
.otp-box {
    width: 45px;
    height: 55px;
    text-align: center;
    font-size: 20px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
}
.otp-box:focus {
    outline: none;
    border-color: #2563eb;
}
</style>

<script>
    const inputs = document.querySelectorAll(".otp-box");

    // focus first box on load
    window.addEventListener("DOMContentLoaded", () => {
        inputs[0].focus();
    });

    // typing behavior
    inputs.forEach((input, index) => {

        input.addEventListener("input", () => {
            input.value = input.value.replace(/[^0-9]/g, '');

            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace") {
                if (!input.value && index > 0) {
                    inputs[index - 1].focus();
                } else {
                    input.value = '';
                }
            }
        });
    });

    // get email from URL
    const email = new URLSearchParams(window.location.search).get("email");

    // verify
    document.getElementById("verifyBtn").addEventListener("click", () => {

        let otp = "";
        inputs.forEach(i => otp += i.value);

        if (otp.length !== 6) {
            showMessage("Please enter complete OTP", "error");
            return;
        }

        const btn = document.getElementById("verifyBtn");
        const text = document.getElementById("btnText");
        const spinner = document.getElementById("spinner");

        btn.disabled = true;
        btn.classList.add("opacity-70");
        spinner.classList.remove("hidden");
        text.textContent = "Verifying...";

        fetch("/backend/auth/verify-otp.php", {
            method: "POST",
            body: new URLSearchParams({ email, otp })
        })
        .then(res => res.json())
        .then(data => {

            if (data.success) {
                showMessage("Verified! Redirecting...", "success");

                setTimeout(() => {
                    window.location.href = "login.php";
                }, 1500);

            } else {
                showMessage(data.message, "error");

                btn.disabled = false;
                btn.classList.remove("opacity-70");
                spinner.classList.add("hidden");
                text.textContent = "Verify Code";
            }
        });
    });

    // helper
    function showMessage(text, type) {
        const msg = document.getElementById("message");
        msg.textContent = text;
        msg.className = "text-center mt-4 text-sm " + 
            (type === "success" ? "text-green-600" : "text-red-500");
    }
    const resendBtn = document.getElementById("resendBtn");
    const resendTimer = document.getElementById("resendTimer");

    let cooldown = 30;
    let timerInterval = null;

    function startCooldown() {
        resendBtn.disabled = true;
        resendBtn.classList.add("opacity-50", "cursor-not-allowed");

        cooldown = 30;
        resendTimer.textContent = `Resend available in ${cooldown}s`;

        timerInterval = setInterval(() => {
            cooldown--;

            if (cooldown > 0) {
                resendTimer.textContent = `Resend available in ${cooldown}s`;
            } else {
                clearInterval(timerInterval);
                resendTimer.textContent = "";
                resendBtn.disabled = false;
                resendBtn.classList.remove("opacity-50", "cursor-not-allowed");
            }
        }, 1000);
    }

    resendBtn.addEventListener("click", () => {

        resendBtn.disabled = true;

        fetch("/backend/auth/verify-otp.php", {
            method: "POST",
            body: new URLSearchParams({ email, otp })
        })
        .then(res => res.json())
        .then(data => {

            if (data.success) {
                showMessage("Verified! Redirecting...", "success");
                setTimeout(() => {
                    window.location.href = "login.php";
                }, 1500);
            } else {
                showMessage(data.message, "error");
                btn.disabled = false;
                btn.classList.remove("opacity-70");
                spinner.classList.add("hidden");
                text.textContent = "Verify Code";
            }

        })
        .catch(err => {
            // This fires when PHP crashes and returns non-JSON (HTML error page)
            showMessage("Server error. Check PHP logs.", "error");
            btn.disabled = false;
            btn.classList.remove("opacity-70");
            spinner.classList.add("hidden");
            text.textContent = "Verify Code";
        });
    });

    window.addEventListener("DOMContentLoaded", () => {
        startCooldown();
    });
</script>

</body>
</html>