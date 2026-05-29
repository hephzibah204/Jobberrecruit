<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --primary-color: #005DA8;
        --secondary-color: #F5A623;
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
    .forgot-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        position: relative;
        z-index: 1;
    }

    .forgot-card {
        width: 100%;
        max-width: 440px;
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

    .forgot-card .logo {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .forgot-card .logo img {
        height: 50px;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        transition: var(--transition);
    }

    .forgot-card .logo img:hover {
        transform: scale(1.05);
    }

    .forgot-card h4 {
        color: var(--primary-color);
        text-align: center;
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    .forgot-card p {
        font-size: 1rem;
        text-align: center;
        color: var(--text-secondary);
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    /* Info Icon */
    .info-icon {
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

    /* Submit Button */
    .submit-btn {
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

    .submit-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(13, 96, 158, 0.3);
    }

    .submit-btn:disabled {
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
        margin-bottom: 0;
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

    /* Additional Instructions */
    .instructions {
        background-color: rgba(13, 96, 158, 0.05);
        border-left: 4px solid var(--primary-color);
        padding: 1rem;
        margin-bottom: 1.5rem;
        text-align: left;
        border-radius: 0 var(--border-radius) var(--border-radius) 0;
    }

    .instructions h6 {
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .instructions ul {
        margin: 0;
        padding-left: 1.2rem;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .instructions li {
        margin-bottom: 0.25rem;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .forgot-card {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }

        .ribbon {
            display: none;
        }

        .info-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="forgot-container">
    <div class="ribbon"></div>
    <div class="ribbon"></div>

    <div class="forgot-card">
        <!-- Optional Logo -->
        <!-- <div class="logo">
            <img src="<?= base_url('assets/imgs/template/logo.png'); ?>" alt="Company Logo" loading="lazy">
        </div> -->

        <div class="info-icon">
            <i class="bi bi-key"></i>
        </div>

        <h4>Reset Your Password</h4>
        <p>Enter your email address and we'll send you instructions to reset your password.</p>

        <!-- Display Success Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="success-message">
                <div class="success-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h5>Email Sent!</h5>
                <p><?= esc(session()->getFlashdata('success')) ?></p>
                <p class="mt-2" style="font-size: 0.85rem;">
                    <i class="bi bi-info-circle me-1"></i>
                    If you don't see the email, check your spam folder.
                </p>
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

        <!-- Display Flash Error -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!session()->getFlashdata('success')): ?>
            <!-- Instructions -->
            <div class="instructions">
                <h6>What to expect:</h6>
                <ul>
                    <li>A password reset link will be sent to your email</li>
                    <li>The link expires in 1 hour for security</li>
                    <li>Check your spam folder if you don't see the email</li>
                </ul>
            </div>

            <!-- Forgot Password Form -->
            <form id="forgotForm" action="<?= base_url('forgot-password/send'); ?>" method="POST" novalidate>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="Enter your registered email"
                        value="<?= old('email') ?>"
                        required>
                    <div class="invalid-feedback"></div>
                </div>

                <!-- Hidden reCAPTCHA token -->
                <input type="hidden" name="g-recaptcha-response" id="recaptchaToken">

                <button id="submitBtn" type="submit" class="submit-btn">
                    <span class="btn-text">Send Reset Instructions</span>
                    <span class="btn-loading d-none">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                        Sending...
                    </span>
                </button>
            </form>
        <?php endif; ?>

        <div class="back-to-login">
            <a href="<?= base_url('login'); ?>">
                <i class="bi bi-arrow-left"></i>
                Back to Login
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= env('recaptcha_site_key'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('forgotForm');
        const emailInput = document.getElementById('email');
        const submitBtn = document.getElementById('submitBtn');
        const recaptchaTokenInput = document.getElementById('recaptchaToken');

        // Auto-focus email field
        if (emailInput) {
            emailInput.focus();
        }

        // Form submission
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                clearErrors();

                // Validate email
                const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
                if (!emailValid) {
                    showFieldError(emailInput, 'Please enter a valid email address.');
                    return;
                }

                // Get reCAPTCHA token
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.ready(function() {
                        grecaptcha.execute('<?= env('recaptcha_site_key'); ?>', {
                            action: 'forgot_password'
                        }).then(function(token) {
                            recaptchaTokenInput.value = token;
                            submitForm();
                        });
                    });
                } else {
                    recaptchaTokenInput.value = 'dev-bypass';
                    submitForm();
                }
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
                    toastr.success(data.message || 'Reset instructions sent!');
                    // Clear input
                    emailInput.value = '';
                    // Reload page
                    // setTimeout(() => {
                    //     window.location.reload();
                    // }, 1500);
                } else {
                    handleServerErrors(data);
                }
            } catch (error) {
                toastr.error('Network error. Please try again.');
            } finally {
                setLoading(false);
            }
        }

        // Clear errors on input
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                clearError(this);
            });
        }

        // Enter key submits
        if (emailInput) {
            emailInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && form) {
                    form.dispatchEvent(new Event('submit'));
                }
            });
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
            if (!submitBtn) return;

            const text = submitBtn.querySelector('.btn-text');
            const loadingEl = submitBtn.querySelector('.btn-loading');
            submitBtn.disabled = loading;
            text.classList.toggle('d-none', loading);
            loadingEl.classList.toggle('d-none', !loading);
        }
    });
</script>
<?= $this->endSection() ?>