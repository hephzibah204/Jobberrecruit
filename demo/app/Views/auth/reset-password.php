<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --primary-color: #0D609E;
        --secondary-color: #F0890E;
        --text-dark: #1E293B;
        --text-muted: #64748B;
        --bg-light: #f8f9fb;
        --success-color: #198754;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #0dcaf0;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --text-primary: #212529;
        --text-secondary: #6c757d;
        --border-color: #dee2e6;
        --bg-color: #ffffff;
        --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        --border-radius: 0.5rem;
        --transition: all 0.3s ease;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: #f0f4f8;
        color: var(--text-primary);
        margin: 0;
        padding: 0;
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }

    /* Ribbon Background */
    body::before,
    body::after {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        background: linear-gradient(45deg, rgba(13, 96, 158, 0.08), transparent);
        border-radius: 50%;
        z-index: -2;
        animation: float 15s infinite ease-in-out;
    }

    body::before {
        top: -200px;
        left: -200px;
        background: radial-gradient(circle, rgba(240, 137, 14, 0.1), transparent 70%);
    }

    body::after {
        bottom: -150px;
        right: -150px;
        background: radial-gradient(circle, rgba(13, 96, 158, 0.1), transparent 70%);
        animation-delay: -7.5s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-30px) rotate(5deg);
        }
    }

    /* Ribbon Stripes */
    .ribbon {
        position: absolute;
        height: 200px;
        width: 100%;
        background: linear-gradient(135deg,
                rgba(13, 96, 158, 0.05) 0%,
                rgba(240, 137, 14, 0.05) 50%,
                rgba(13, 96, 158, 0.05) 100%);
        transform: rotate(-3deg);
        top: 15%;
        left: -10%;
        z-index: -1;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .ribbon:nth-child(2) {
        top: 70%;
        transform: rotate(3deg);
        background: linear-gradient(135deg,
                rgba(240, 137, 14, 0.06) 0%,
                rgba(13, 96, 158, 0.06) 50%,
                rgba(240, 137, 14, 0.06) 100%);
        left: -5%;
    }

    /* Center Container */
    .reset-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        position: relative;
        z-index: 1;
    }

    .reset-card {
        width: 100%;
        max-width: 460px;
        background: var(--bg-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        padding: 3rem 2.5rem;
        border: 1px solid var(--border-color);
        position: relative;
        backdrop-filter: blur(10px);
        animation: fadeInUp 0.8s ease forwards;
        text-align: center;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .reset-card .logo {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .reset-card .logo img {
        height: 50px;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        transition: var(--transition);
    }

    .reset-card .logo img:hover {
        transform: scale(1.05);
    }

    .reset-card h4 {
        color: var(--primary-color);
        text-align: center;
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    .reset-card p {
        font-size: 1rem;
        text-align: center;
        color: var(--text-secondary);
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    /* Lock Icon */
    .lock-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 2rem;
        box-shadow: 0 10px 20px rgba(13, 96, 158, 0.2);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 10px 20px rgba(13, 96, 158, 0.2);
        }

        50% {
            box-shadow: 0 10px 30px rgba(13, 96, 158, 0.4);
        }

        100% {
            box-shadow: 0 10px 20px rgba(13, 96, 158, 0.2);
        }
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        text-align: left;
        display: block;
    }

    .form-control {
        border-radius: var(--border-radius);
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border: 1px solid var(--border-color);
        transition: var(--transition);
        background-color: #fcfdff;
        text-align: left;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(13, 96, 158, 0.2);
        outline: none;
    }

    /* Password Strength Meter */
    .password-strength {
        margin-top: 0.25rem;
        height: 4px;
        background: var(--border-color);
        border-radius: 2px;
        overflow: hidden;
        position: relative;
    }

    .password-strength-meter {
        height: 100%;
        width: 0%;
        background: var(--danger-color);
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .strength-text {
        font-size: 0.8rem;
        margin-top: 0.25rem;
        text-align: right;
        color: var(--text-secondary);
    }

    .strength-0 {
        width: 0%;
        background: var(--danger-color);
    }

    .strength-1 {
        width: 25%;
        background: var(--danger-color);
    }

    .strength-2 {
        width: 50%;
        background: var(--warning-color);
    }

    .strength-3 {
        width: 75%;
        background: var(--info-color);
    }

    .strength-4 {
        width: 100%;
        background: var(--success-color);
    }

    /* Password Input Group */
    .password-input-group {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition);
        padding: 0.25rem;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle:hover {
        background-color: rgba(0, 0, 0, 0.05);
        color: var(--primary-color);
    }

    /* Password Requirements */
    .password-requirements {
        background-color: rgba(13, 96, 158, 0.05);
        border-radius: var(--border-radius);
        padding: 1rem;
        margin: 1rem 0;
        text-align: left;
    }

    .password-requirements h6 {
        color: var(--primary-color);
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .requirements-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .requirements-list li {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        transition: var(--transition);
    }

    .requirements-list li.valid {
        color: var(--success-color);
    }

    .requirements-list li i {
        font-size: 0.8rem;
    }

    .requirements-list li.valid i {
        color: var(--success-color);
    }

    /* Submit Button */
    .reset-btn {
        width: 100%;
        border-radius: var(--border-radius);
        padding: 0.85rem;
        font-weight: 600;
        font-size: 1rem;
        background: linear-gradient(135deg, var(--primary-color), #0b5ed7);
        border: none;
        color: white;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        margin-top: 1rem;
    }

    .reset-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(13, 96, 158, 0.3);
    }

    .reset-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }

    /* Success Message */
    .success-message {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.1), rgba(25, 135, 84, 0.05));
        border: 1px solid rgba(25, 135, 84, 0.2);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-message .success-icon {
        font-size: 3rem;
        color: var(--success-color);
        margin-bottom: 1rem;
    }

    .success-message h5 {
        color: var(--success-color);
        margin-bottom: 0.5rem;
    }

    .success-message p {
        color: var(--text-secondary);
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    /* Errors */
    .is-invalid {
        border-color: var(--danger-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    .invalid-feedback {
        font-size: 0.875rem;
        color: var(--danger-color);
        margin-top: 0.25rem;
        text-align: left;
    }

    /* Alert for expired/invalid token */
    .token-alert {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05));
        border: 1px solid rgba(220, 53, 69, 0.2);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .token-alert .alert-icon {
        font-size: 3rem;
        color: var(--danger-color);
        margin-bottom: 1rem;
    }

    .token-alert h5 {
        color: var(--danger-color);
        margin-bottom: 0.5rem;
    }

    /* Back to Login Link */
    .back-to-login {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.95rem;
        color: var(--text-secondary);
    }

    .back-to-login a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
    }

    .back-to-login a:hover {
        text-decoration: underline;
        gap: 0.7rem;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .reset-card {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }

        .ribbon {
            display: none;
        }

        .lock-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .password-requirements {
            padding: 0.75rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="reset-container">
    <div class="ribbon"></div>
    <div class="ribbon"></div>

    <div class="reset-card">
        <!-- Optional Logo -->
        <!-- <div class="logo">
            <img src="<?= base_url('assets/imgs/template/logo.png'); ?>" alt="Company Logo" loading="lazy">
        </div> -->

        <?php if ($valid_token): ?>
            <?php if (session()->getFlashdata('success')): ?>
                <!-- Success Message -->
                <div class="success-message">
                    <div class="success-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h5>Password Reset Successful!</h5>
                    <p><?= esc(session()->getFlashdata('success')) ?></p>
                    <div class="back-to-login">
                        <a href="<?= base_url('login'); ?>">
                            <i class="bi bi-arrow-right"></i>
                            Go to Login
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Reset Password Form -->
                <div class="lock-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>

                <h4>Set New Password</h4>
                <p>Create a new strong password for your account.</p>

                <!-- Display Validation Errors -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Display Flash Error -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Password Requirements -->
                <div class="password-requirements">
                    <h6><i class="bi bi-shield-check"></i> Password Requirements</h6>
                    <ul class="requirements-list" id="requirementsList">
                        <li id="reqLength">
                            <i class="bi bi-circle"></i>
                            At least 8 characters
                        </li>
                        <li id="reqUppercase">
                            <i class="bi bi-circle"></i>
                            At least one uppercase letter
                        </li>
                        <li id="reqLowercase">
                            <i class="bi bi-circle"></i>
                            At least one lowercase letter
                        </li>
                        <li id="reqNumber">
                            <i class="bi bi-circle"></i>
                            At least one number
                        </li>
                        <li id="reqSpecial">
                            <i class="bi bi-circle"></i>
                            At least one special character
                        </li>
                    </ul>
                </div>

                <!-- Reset Password Form -->
                <form id="resetForm" action="<?= base_url('auth/reset-password/' . $token); ?>" method="POST" novalidate>
                    <!-- Hidden token -->
                    <input type="hidden" name="token" value="<?= esc($token) ?>">

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <div class="password-input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter new password"
                                required
                                autocomplete="new-password">
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                        <!-- Password Strength Meter -->
                        <div class="password-strength mt-2">
                            <div class="password-strength-meter" id="strengthMeter"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Password strength</div>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <div class="password-input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                placeholder="Confirm new password"
                                required
                                autocomplete="new-password">
                            <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback"></div>
                        <div id="passwordMatch" class="mt-1" style="font-size: 0.85rem;"></div>
                    </div>

                    <!-- Hidden reCAPTCHA token -->
                    <input type="hidden" name="g-recaptcha-response" id="recaptchaToken">

                    <button id="resetBtn" type="submit" class="reset-btn" disabled>
                        <span class="btn-text">Reset Password</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm" role="status"></span>
                            Resetting...
                        </span>
                    </button>
                </form>

                <div class="back-to-login">
                    <a href="<?= base_url('login'); ?>">
                        <i class="bi bi-arrow-left"></i>
                        Back to Login
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Invalid/Expired Token Message -->
            <div class="token-alert">
                <div class="alert-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h5>Invalid or Expired Link</h5>
                <p class="mb-3">This password reset link is invalid or has expired. Please request a new password reset link.</p>
                <div class="back-to-login">
                    <a href="<?= base_url('forgot-password'); ?>">
                        <i class="bi bi-arrow-clockwise"></i>
                        Request New Reset Link
                    </a>
                </div>
                <div class="back-to-login mt-2">
                    <a href="<?= base_url('login'); ?>">
                        <i class="bi bi-arrow-left"></i>
                        Back to Login
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= env('recaptcha_site_key'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('resetForm');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const resetBtn = document.getElementById('resetBtn');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');
        const strengthMeter = document.getElementById('strengthMeter');
        const strengthText = document.getElementById('strengthText');
        const requirementsList = document.getElementById('requirementsList');
        const passwordMatch = document.getElementById('passwordMatch');

        // Password visibility toggles
        if (togglePasswordBtn) {
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
            });
        }

        if (toggleConfirmPasswordBtn) {
            toggleConfirmPasswordBtn.addEventListener('click', function() {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
            });
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;

            // Length check
            if (password.length >= 8) strength++;

            // Uppercase check
            if (/[A-Z]/.test(password)) strength++;

            // Lowercase check
            if (/[a-z]/.test(password)) strength++;

            // Number check
            if (/[0-9]/.test(password)) strength++;

            // Special character check
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            return strength;
        }

        function updateStrengthMeter(strength) {
            // Update meter
            strengthMeter.className = 'password-strength-meter';
            strengthMeter.classList.add('strength-' + strength);

            // Update text
            const texts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            const colors = ['danger', 'danger', 'warning', 'info', 'success'];
            strengthText.textContent = texts[strength];
            strengthText.style.color = `var(--${colors[strength]}-color)`;

            // Update requirements list
            const requirements = [{
                    id: 'reqLength',
                    test: (p) => p.length >= 8
                },
                {
                    id: 'reqUppercase',
                    test: (p) => /[A-Z]/.test(p)
                },
                {
                    id: 'reqLowercase',
                    test: (p) => /[a-z]/.test(p)
                },
                {
                    id: 'reqNumber',
                    test: (p) => /[0-9]/.test(p)
                },
                {
                    id: 'reqSpecial',
                    test: (p) => /[^A-Za-z0-9]/.test(p)
                }
            ];

            requirements.forEach((req, index) => {
                const element = document.getElementById(req.id);
                const icon = element.querySelector('i');
                if (req.test(passwordInput.value)) {
                    element.classList.add('valid');
                    icon.className = 'bi bi-check-circle-fill';
                } else {
                    element.classList.remove('valid');
                    icon.className = 'bi bi-circle';
                }
            });
        }

        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirm = confirmPasswordInput.value;

            if (confirm === '') {
                passwordMatch.textContent = '';
                passwordMatch.style.color = '';
                return false;
            }

            if (password === confirm) {
                passwordMatch.innerHTML = '<i class="bi bi-check-circle-fill"></i> Passwords match';
                passwordMatch.style.color = 'var(--success-color)';
                return true;
            } else {
                passwordMatch.innerHTML = '<i class="bi bi-exclamation-circle-fill"></i> Passwords do not match';
                passwordMatch.style.color = 'var(--danger-color)';
                return false;
            }
        }

        function validateForm() {
            const password = passwordInput.value;
            const confirm = confirmPasswordInput.value;

            // Check password strength
            const strength = checkPasswordStrength(password);
            const isStrongEnough = strength >= 3; // Require at least "Good" strength

            // Check if passwords match
            const passwordsMatch = checkPasswordMatch();

            // Enable/disable submit button
            resetBtn.disabled = !(password && confirm && isStrongEnough && passwordsMatch);

            return isStrongEnough && passwordsMatch;
        }

        // Real-time validation
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const strength = checkPasswordStrength(this.value);
                updateStrengthMeter(strength);
                checkPasswordMatch();
                validateForm();
            });
        }

        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                checkPasswordMatch();
                validateForm();
            });
        }

        // Form submission
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                clearErrors();

                // Validate password strength
                const strength = checkPasswordStrength(passwordInput.value);
                if (strength < 3) {
                    showFieldError(passwordInput, 'Password is too weak. Please choose a stronger password.');
                    return;
                }

                // Validate password match
                if (!checkPasswordMatch()) {
                    showFieldError(confirmPasswordInput, 'Passwords do not match.');
                    return;
                }

                // Get reCAPTCHA token
                grecaptcha.ready(function() {
                    grecaptcha.execute('<?= env('recaptcha_site_key'); ?>', {
                        action: 'reset_password'
                    }).then(function(token) {
                        document.getElementById('recaptchaToken').value = token;
                        submitForm();
                    });
                });
            });
        }

        // Submit form function
        async function submitForm() {
            setLoading(true);

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.status === 'success') {
                    // Show success message and reload page to show success state
                    toastr?.success(data.message || 'Password reset successfully!');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1500);
                } else {
                    handleServerErrors(data);
                }
            } catch (error) {
                toastr?.error('Network error. Please try again.');
            } finally {
                setLoading(false);
            }
        }

        // Utility functions
        function showFieldError(input, msg) {
            input.classList.add('is-invalid');
            const fb = input.parentNode.querySelector('.invalid-feedback');
            if (fb) fb.textContent = msg;
        }

        function clearError(input) {
            input.classList.remove('is-invalid');
            const fb = input.parentNode.querySelector('.invalid-feedback');
            if (fb) fb.textContent = '';
        }

        function clearErrors() {
            const invalidElements = form?.querySelectorAll('.is-invalid');
            if (invalidElements) {
                invalidElements.forEach(el => {
                    el.classList.remove('is-invalid');
                    const fb = el.parentNode.querySelector('.invalid-feedback');
                    if (fb) fb.textContent = '';
                });
            }
        }

        function handleServerErrors(data) {
            if (data.errors) {
                Object.entries(data.errors).forEach(([key, msg]) => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) showFieldError(input, msg);
                });
            }
            toastr?.error(data.message || 'Please correct the errors.');
        }

        function setLoading(loading) {
            if (!resetBtn) return;

            const text = resetBtn.querySelector('.btn-text');
            const loadingEl = resetBtn.querySelector('.btn-loading');
            resetBtn.disabled = loading;
            text.classList.toggle('d-none', loading);
            loadingEl.classList.toggle('d-none', !loading);
        }

        // Initial validation
        validateForm();
    });
</script>
<?= $this->endSection() ?>