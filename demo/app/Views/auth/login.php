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

    /* Ribbon Background - Same as Registration */
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
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        position: relative;
        z-index: 1;
    }

    .login-card {
        width: 100%;
        max-width: 420px;
        background: var(--bg-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        padding: 3rem 2.5rem;
        border: 1px solid var(--border-color);
        position: relative;
        backdrop-filter: blur(10px);
        animation: fadeInUp 0.8s ease forwards;
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

    .login-card .logo {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .login-card .logo img {
        height: 50px;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        transition: var(--transition);
    }

    .login-card .logo img:hover {
        transform: scale(1.05);
    }

    .login-card h4 {
        color: var(--primary-color);
        text-align: center;
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    .login-card p {
        font-size: 0.95rem;
        text-align: center;
        color: var(--text-secondary);
        margin-bottom: 2rem;
    }

    /* Social Button */
    .social-login {
        width: 100%;
        border-radius: var(--border-radius);
        padding: 0.75rem;
        font-weight: 600;
        font-size: 1rem;
        border: 1px solid var(--border-color);
        background: #fff;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        transition: var(--transition);
    }

    .social-login:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .social-login img {
        width: 20px;
        height: 20px;
    }

    /* Divider */
    .divider-text-center {
        position: relative;
        text-align: center;
        margin: 1.5rem 0;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .divider-text-center::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: var(--border-color);
    }

    .divider-text-center span {
        background: var(--bg-color);
        padding: 0 1rem;
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control {
        border-radius: var(--border-radius);
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border: 1px solid var(--border-color);
        transition: var(--transition);
        background-color: #fcfdff;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(13, 96, 158, 0.2);
        outline: none;
    }

    /* Checkbox & Forgot Password */
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .form-check-input {
        accent-color: var(--primary-color);
        margin-top: 0;
    }

    .forgot-password {
        font-size: 0.9rem;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
    }

    .forgot-password:hover {
        text-decoration: underline;
        color: #0b5ed7;
    }

    /* Submit Button */
    .login-btn {
        width: 100%;
        border-radius: var(--border-radius);
        padding: 0.85rem;
        font-weight: 600;
        font-size: 1rem;
        background: var(--primary-color);
        border: none;
        color: white;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .login-btn:hover:not(:disabled) {
        background: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(13, 96, 158, 0.3);
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

    /* Errors */
    .is-invalid {
        border-color: var(--danger-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    .invalid-feedback {
        font-size: 0.875rem;
        color: var(--danger-color);
        margin-top: 0.25rem;
    }

    /* Register Link */
    .register-link {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.95rem;
        color: var(--text-secondary);
    }

    .register-link a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .login-card {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }

        .ribbon {
            display: none;
        }
    }

    /* Password Toggle Animation */
    .password-toggle {
        transition: transform 0.25s ease, opacity 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle i {
        font-size: 1.1rem;
        color: var(--text-muted);
        transition: transform 0.25s ease, opacity 0.25s ease;
    }

    .password-toggle:hover i {
        color: var(--primary-color);
    }

    /* Animate icon rotation and fade */
    .password-toggle.active i {
        transform: rotate(180deg);
        opacity: 0.8;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="login-container">
    <div class="ribbon"></div>
    <div class="ribbon"></div>

    <div class="login-card">
        <!-- <div class="logo">
            <img src="<?= base_url('assets/imgs/template/logo.png'); ?>" alt="Company Logo" loading="lazy">
        </div> -->
        <h4>Sign In to Your Account</h4>
        <p>Enter your credentials below to get started.</p>

        <button class="social-login" type="button" onclick="window.location.href='<?= base_url('auth/google'); ?>'">
            <img src="<?= base_url('assets/imgs/template/icons/icon-google.svg'); ?>" alt="Google">
            <strong>Sign up with Google</strong>
        </button>

        <button class="social-login" type="button" onclick="window.location.href='<?= base_url('auth/linkedin'); ?>'">
            <img src="<?= base_url('assets/imgs/template/icons/linkedin.svg'); ?>" alt="LinkedIn">
            <strong>Sign in with LinkedIn</strong>
        </button>

        <!-- <button class="social-login" type="button" onclick="window.location.href='<?= base_url('auth/facebook'); ?>'">
            <img src="<?= base_url('assets/imgs/template/icons/icon-facebook.svg'); ?>" alt="Facebook">
            <strong>Sign in with Facebook</strong>
        </button> -->

        <div class="divider-text-center">
            <span>Or continue with</span>
        </div>

        <!-- Display Flash Error (e.g., reCAPTCHA failure) -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

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

        <form id="loginForm" action="<?= base_url('login'); ?>" method="POST" novalidate>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    <span class="input-group-text password-toggle" style="cursor:pointer">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <a href="<?= base_url('forgot-password'); ?>" class="forgot-password">Forgot password?</a>
            </div>

            <button id="loginBtn" type="submit" class="login-btn">
                <span class="btn-text">Sign In</span>
                <span class="btn-loading d-none">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    Signing In...
                </span>
            </button>
        </form>

        <div class="register-link">
            Don’t have an account? <a href="<?= base_url('register'); ?>">Create one now</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= env('recaptcha_site_key'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const loginBtn = document.getElementById('loginBtn');

        // Auto-focus email
        emailInput.focus();

        // Form submit
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            clearErrors();

            grecaptcha.ready(function() {
                grecaptcha.execute('<?= env('recaptcha_site_key'); ?>', {
                    action: 'login'
                }).then(function(token) {

                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = 'g-recaptcha-response';
                    tokenInput.value = token;
                    form.appendChild(tokenInput);

                    submitLoginForm();
                });
            });

            // const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
            // const passwordValid = passwordInput.value.length >= 6;

            // if (!emailValid) {
            //     showFieldError(emailInput, 'Please enter a valid email address.');
            //     return;
            // }

            // if (!passwordValid) {
            //     showFieldError(passwordInput, 'Password must be at least 6 characters.');
            //     return;
            // }

            // setLoading(true);

            // try {
            //     const formData = new FormData(form);
            //     const response = await fetch(form.action, {
            //         method: 'POST',
            //         body: formData,
            //         headers: {
            //             'X-Requested-With': 'XMLHttpRequest'
            //         }
            //     });

            //     const data = await response.json();

            //     if (data.status === 'success') {
            //         toastr?.success(data.message || 'Login successful!');
            //         setTimeout(() => {
            //             window.location.href = data.redirect_url || '/dashboard';
            //         }, 1500);
            //     } else {
            //         handleServerErrors(data);
            //     }
            // } catch (error) {
            //     toastr?.error('Network error. Please try again.');
            // } finally {
            //     setLoading(false);
            // }
        });

        async function submitLoginForm() {
            const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
            const passwordValid = passwordInput.value.length >= 6;

            if (!emailValid) {
                showFieldError(emailInput, 'Please enter a valid email address.');
                return;
            }

            if (!passwordValid) {
                showFieldError(passwordInput, 'Password must be at least 6 characters.');
                return;
            }

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
                    toastr?.success(data.message || 'Login successful!');
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '/dashboard';
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


        // Clear errors on input
        [emailInput, passwordInput].forEach(input => {
            input.addEventListener('input', function() {
                clearError(this);
            });
        });

        // Enter key submits
        passwordInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') form.dispatchEvent(new Event('submit'));
        });

        // Utilities
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
            form.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
                const fb = el.parentNode.querySelector('.invalid-feedback');
                if (fb) fb.textContent = '';
            });
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
            const text = loginBtn.querySelector('.btn-text');
            const loadingEl = loginBtn.querySelector('.btn-loading');
            loginBtn.disabled = loading;
            text.classList.toggle('d-none', loading);
            loadingEl.classList.toggle('d-none', !loading);
        }

        // 5️⃣ Password visibility toggle (animated)
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';
                toggle.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
                icon.classList.toggle('bi-eye', isPassword);
                icon.classList.toggle('bi-eye-slash', !isPassword);

                // Add smooth animation
                this.classList.add('active');
                setTimeout(() => this.classList.remove('active'), 250);
            });
        });
    });
</script>
<?= $this->endSection() ?>