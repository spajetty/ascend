<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ASCEND</title>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="/assets/css/output.css">

    <!-- Google Font: DM Sans -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[url('/assets/images/bg.png')] bg-cover bg-center bg-fixed font-['DM_Sans',Tahoma,Geneva,Verdana,sans-serif] flex items-center justify-center">

    <!-- Wrapper for consistent margins -->
    <div class="w-full px-4 sm:px-6 lg:px-8 py-10 flex items-center justify-center">

        <!-- Sign Up Card -->
        <div class="w-full max-w-2xl bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-6 sm:p-8 lg:p-10">

            <!-- Header -->
            <div class="text-center mb-6">
                <div class="flex justify-center mb-4">
                    <img src="/assets/images/logo.png" alt="Company Logo" class="w-16 h-16 object-contain">
                </div>
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">
                    Public Employment Division
                </h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">
                    Centralized Database System for Employment Management
                </p>
            </div>

            <!-- Sign Up Form -->
            <form method="POST" action="../../backend/auth/signup_handler.php" class="space-y-5">

                <!-- Row 1: First Name, Last Name, Middle Initial -->
                <div class="space-y-4">
                    
                    <!-- First Name (Full width on mobile, part of grid on larger screens) -->
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        
                        <!-- First Name -->
                        <div class="sm:col-span-1">
                            <label for="fname" class="block text-sm font-medium text-gray-700 mb-1">
                                First Name
                            </label>
                            <input
                                type="text"
                                id="fname"
                                name="fname"
                                required
                                class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg
                                    focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <!-- Last Name and Middle Initial -->
                        <div class="grid grid-cols-5 gap-4 sm:col-span-2 mt-4 sm:mt-0">
                            
                            <!-- Last Name (80%) -->
                            <div class="col-span-4">
                                <label for="lname" class="block text-sm font-medium text-gray-700 mb-1">
                                    Last Name
                                </label>
                                <input
                                    type="text"
                                    id="lname"
                                    name="lname"
                                    required
                                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg
                                        focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>

                            <!-- Middle Initial (20%) -->
                            <div class="col-span-1">
                                <label for="mi" class="block text-sm font-medium text-gray-700 mb-1">
                                    M.I.
                                </label>
                                <input
                                    type="text"
                                    id="mi"
                                    name="mi"
                                    maxlength="1"
                                    class="w-full px-4 py-2 text-sm text-center border border-gray-300 rounded-lg
                                        focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Contact Number and Email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <!-- Contact -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Contact Number
                        </label>

                        <input type="tel" name="contact" placeholder="09XXXXXXXXX" required
                            class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <p id="contactError" class="text-xs text-red-500 mt-1 hidden"></p>

                        <p class="text-xs text-gray-500 mt-1">Format: 09XXXXXXXXX</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>

                        <input type="email" name="email" required
                            class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <p id="emailError" class="text-xs text-red-500 mt-1 hidden"></p>
                    </div>

                </div>

                <!-- Row 3: Password and Confirm Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    
    <!-- Password Field -->
    <div>
        <div class="flex items-center justify-between mb-1">
            <label for="password" class="block text-sm font-medium text-gray-700">
                Password
            </label>
            <!-- Info Icon -->
            <button type="button" onclick="openModal()" 
                class="text-blue-600 hover:text-blue-800" aria-label="Password requirements">
                <!-- Info Icon SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>
            </button>
        </div>

       <div class="relative">
    <input type="password" name="password" id="password" required
        class="w-full px-4 py-2 pr-11 text-sm border border-gray-300 rounded-lg
                focus:outline-none focus:ring-2 focus:ring-blue-500">

    <button type="button" id="togglePassword"
        class="absolute right-3 top-0 h-full flex items-center z-10 text-gray-500 hover:text-gray-700">
        
        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5
                   c4.478 0 8.268 2.943 9.542 7
                   -1.274 4.057-5.064 7-9.542 7
                   -4.477 0-8.268-2.943-9.542-7z"/>
        </svg>

        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.94 17.94A10.94 10.94 0 0112 19
                   c-5 0-9.27-3.11-11-7
                   a11.83 11.83 0 012.92-4.36" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.9 4.24A10.94 10.94 0 0112 4
                   c5 0 9.27 3.11 11 7
                   a11.82 11.82 0 01-1.67 2.68" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M14.12 14.12A3 3 0 019.88 9.88" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3l18 18" />
        </svg>
    </button>
</div>

        <!-- Password Strength Bar -->
        <div class="mt-2">
            <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                <div id="strengthBar"
                    class="h-full w-0 bg-red-500 transition-all duration-300"></div>
            </div>
            <p id="strengthText" class="text-xs mt-1 text-gray-600">
                Password strength: Too weak
            </p>
        </div>
    </div>

    <!-- Confirm Password Field -->
    <div>
        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
            Confirm Password
        </label>

        <div class="relative">
    <input type="password" name="confirm_password" id="confirm_password" required
        class="w-full px-4 py-2 pr-11 text-sm border border-gray-300 rounded-lg
                focus:outline-none focus:ring-2 focus:ring-blue-500">

    <button type="button" id="toggleConfirmPassword"
        class="absolute right-3 top-0 h-full flex items-center z-10 text-gray-500 hover:text-gray-700">

        <svg id="eyeOpenConfirm" xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5
                   c4.478 0 8.268 2.943 9.542 7
                   -1.274 4.057-5.064 7-9.542 7
                   -4.477 0-8.268-2.943-9.542-7z"/>
        </svg>

        <svg id="eyeClosedConfirm" xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17.94 17.94A10.94 10.94 0 0112 19
                   c-5 0-9.27-3.11-11-7
                   a11.83 11.83 0 012.92-4.36" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.9 4.24A10.94 10.94 0 0112 4
                   c5 0 9.27 3.11 11 7
                   a11.82 11.82 0 01-1.67 2.68" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M14.12 14.12A3 3 0 019.88 9.88" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3l18 18" />
        </svg>
    </button>
</div>

        <p id="passwordError" class="text-xs text-red-500 mt-1 hidden">
            Passwords do not match.
        </p>
    </div>
</div>

<!-- Submit Button -->
<button id="submitBtn" type="submit"
    class="w-full bg-blue-600 text-white py-3 rounded-lg opacity-50 cursor-not-allowed flex items-center justify-center gap-2"
    disabled>
    <span id="signupText">Create Account & Verify Email</span>
    <svg id="signupSpinner" class="hidden animate-spin h-5 w-5 text-white"
        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10"
            stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8v8z"></path>
    </svg>
</button>
            </form>

            <!-- Sign In Link -->
            <p class="mt-6 text-center text-sm text-gray-600">
                Already have an account?
                <a href="login.php" class="text-blue-600 font-semibold hover:underline">
                    Sign In
                </a>
            </p>
        </div>
    </div>

    <!-- Password Requirements Modal -->
    <div id="passwordModal"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-11/12 max-w-md relative">

            <!-- Close Button -->
            <button onclick="closeModal()"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
                    aria-label="Close modal">
                ✖
            </button>

            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Password Requirements
            </h3>

            <ul class="space-y-2 text-sm">
                <li id="req-length" class="flex items-center text-gray-600">
                    <span class="mr-2">❌</span> At least 8 characters
                </li>
                <li id="req-uppercase" class="flex items-center text-gray-600">
                    <span class="mr-2">❌</span> One uppercase letter
                </li>
                <li id="req-lowercase" class="flex items-center text-gray-600">
                    <span class="mr-2">❌</span> One lowercase letter
                </li>
                <li id="req-number" class="flex items-center text-gray-600">
                    <span class="mr-2">❌</span> One number
                </li>
                <li id="req-special" class="flex items-center text-gray-600">
                    <span class="mr-2">❌</span> One special character (@$!%*?&)
                </li>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            emailValid = false;
            contactValid = false;
            updateButton();
        });
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const passwordError = document.getElementById('passwordError');
        const modal = document.getElementById('passwordModal');

        const requirements = {
            length: document.getElementById('req-length'),
            uppercase: document.getElementById('req-uppercase'),
            lowercase: document.getElementById('req-lowercase'),
            number: document.getElementById('req-number'),
            special: document.getElementById('req-special')
        };

        function updateRequirement(element, isValid) {
            element.querySelector('span').textContent = isValid ? '✔️' : '❌';
            element.classList.toggle('text-green-600', isValid);
            element.classList.toggle('text-gray-600', !isValid);
        }

        function evaluatePassword() {
            const value = password.value;

            const checks = {
                length: value.length >= 8,
                uppercase: /[A-Z]/.test(value),
                lowercase: /[a-z]/.test(value),
                number: /\d/.test(value),
                special: /[@$!%*?&-_.,]/.test(value)
            };

            let score = 0;
            Object.keys(checks).forEach(key => {
                updateRequirement(requirements[key], checks[key]);
                if (checks[key]) score++;
            });

            // Strength Levels
            const levels = [
                { width: '0%',   color: 'bg-red-500',    text: 'Too weak' },
                { width: '25%',  color: 'bg-red-500',    text: 'Weak' },
                { width: '50%',  color: 'bg-yellow-500', text: 'Fair' },
                { width: '75%',  color: 'bg-blue-500',   text: 'Strong' },
                { width: '100%', color: 'bg-green-500',  text: 'Very strong' }
            ];

            const level = levels[Math.min(score, 4)];
            strengthBar.style.width = level.width;
            strengthBar.className = `h-full transition-all duration-300 ${level.color}`;
            strengthText.textContent = `Password strength: ${level.text}`;
        }

        function validatePasswordMatch() {
            if (confirmPassword.value && password.value !== confirmPassword.value) {
                passwordError.classList.remove('hidden');
                confirmPassword.classList.add('border-red-500');
            } else {
                passwordError.classList.add('hidden');
                confirmPassword.classList.remove('border-red-500');
            }
        }

        // Modal Controls
        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            evaluatePassword(); 
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        // Event Listeners
        password.addEventListener('input', () => {
            evaluatePassword();
            validatePasswordMatch();
            updateButton(); 
        });

        confirmPassword.addEventListener('input', () => {
            validatePasswordMatch();
            updateButton(); 
        });

        confirmPassword.addEventListener('input', validatePasswordMatch);

        const email = document.querySelector('input[name="email"]');
        const contact = document.querySelector('input[name="contact"]');
        const submitBtn = document.getElementById('submitBtn');

        const emailError = document.getElementById('emailError');
        const contactError = document.getElementById('contactError');

        let emailValid = false;
        let contactValid = false;

        let emailChecked = false;
        let contactChecked = false;

        function debounce(fn, delay) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => fn(...args), delay);
            };
        }

        // check email format
        function isValidEmailFormat(email) {
            return /^[^\s@]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com)$/.test(email);
        }

        // check contact format
        function isValidContact(contact) {
            return /^09\d{9}$/.test(contact);
        }

        // API CHECK EMAIL
        async function checkEmail(emailValue) {
            const res = await fetch('../../backend/auth/check_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email.value })
            });

            return await res.json();
        }

        // API CHECK CONTACT
        async function checkContact(contactValue) {
            const res = await fetch('../../backend/auth/check_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ contact: contactValue })
            });

            return await res.json();
        }

        // EMAIL VALIDATION
        email.addEventListener('input', debounce(async () => {

            console.log("📧 Email typing:", email.value);

            emailChecked = false;

            emailError.classList.add('hidden');
            emailError.textContent = "";

            if (!isValidEmailFormat(email.value)) {
                console.log("❌ Invalid email format");

                emailValid = false;
                updateButton();

                emailError.textContent = "Invalid email provider";
                emailError.classList.remove('hidden');
                return;
            }

            console.log("⏳ Checking email in database...");

            const res = await fetch('../../backend/auth/check_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email.value })
            });

            const data = await res.json();
            console.log("📩 Email API response:", data);

            emailChecked = true;

            if (data.exists) {
                emailValid = false;
                emailError.textContent = "Email already used";
                emailError.classList.remove('hidden');
            } else {
                emailValid = true;
                emailError.classList.add('hidden');
            }

            updateButton();

        }, 500));

        // CONTACT VALIDATION
        contact.addEventListener('input', debounce(async () => {

            console.log("📱 Contact typing:", contact.value);

            contactChecked = false;

            contactError.classList.add('hidden');
            contactError.textContent = "";

            if (!isValidContact(contact.value)) {
                console.log("❌ Invalid contact format");

                contactValid = false;
                updateButton();

                contactError.textContent = "Invalid contact number";
                contactError.classList.remove('hidden');
                return;
            }

            console.log("⏳ Checking contact in database...");

            const res = await fetch('../../backend/auth/check_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ contact: contact.value })
            });

            const data = await res.json();
            console.log("📩 Contact API response:", data);

            contactChecked = true;

            if (data.exists) {
                contactValid = false;
                contactError.textContent = "Contact already used";
                contactError.classList.remove('hidden');
            } else {
                contactValid = true;
                contactError.classList.add('hidden');
            }

            updateButton();

        }, 500));

        function updateButton() {
            const passwordMatch = password.value === confirmPassword.value;
            const passwordNotEmpty = password.value.length > 0;

            const canSubmit =
                emailValid &&
                contactValid &&
                passwordNotEmpty &&
                passwordMatch;

            submitBtn.disabled = !canSubmit;
            submitBtn.classList.toggle("opacity-50", !canSubmit);
            submitBtn.classList.toggle("cursor-not-allowed", !canSubmit);
        }

        const signupForm = document.querySelector('form');
        const signupText = document.getElementById('signupText');
        const signupSpinner = document.getElementById('signupSpinner');

        signupForm.addEventListener('submit', function (e) {
            e.preventDefault(); 

            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');

            signupText.textContent = 'Creating Account...';
            signupSpinner.classList.remove('hidden');

            setTimeout(() => {
                signupForm.submit();
            }, 3000);
        });

            document.getElementById('togglePassword').addEventListener('click', function () {
    const input = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';

    eyeOpen.classList.toggle('hidden', isPassword);
    eyeClosed.classList.toggle('hidden', !isPassword);
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
    const input = document.getElementById('confirm_password');
    const eyeOpen = document.getElementById('eyeOpenConfirm');
    const eyeClosed = document.getElementById('eyeClosedConfirm');

    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';

    eyeOpen.classList.toggle('hidden', isPassword);
    eyeClosed.classList.toggle('hidden', !isPassword);
});
    </script>

</body>
</html>

