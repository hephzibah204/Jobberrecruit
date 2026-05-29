<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --primary-color: #005DA8;
        --secondary-color: #F5A623;
        --text-dark: #1E293B;
        --text-muted: #64748B;
        --bg-light: #f8f9fb;
        --border-color: #dee2e6;
        --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        --border-radius: 0.5rem;
        --transition: all 0.3s ease;
    }

    body {
        background-color: #f0f4f8;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        min-height: 100vh;
        margin: 0;
        position: relative;
        overflow-x: hidden;
        color: var(--text-dark);
    }

    /* Ribbon Background */
    body::before,
    body::after {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
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
        animation-delay: -7s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-30px);
        }
    }

    .ribbon {
        position: absolute;
        width: 100%;
        height: 180px;
        left: -10%;
        transform: rotate(-3deg);
        background: linear-gradient(135deg,
                rgba(13, 96, 158, 0.05),
                rgba(240, 137, 14, 0.05),
                rgba(13, 96, 158, 0.05));
        top: 20%;
        z-index: -1;
    }

    .ribbon:nth-child(2) {
        top: 75%;
        transform: rotate(3deg);
        background: linear-gradient(135deg,
                rgba(240, 137, 14, 0.06),
                rgba(13, 96, 158, 0.06),
                rgba(240, 137, 14, 0.06));
    }

    /* Center Card */
    .social-container {
        min-height: 100vh;
        padding: 2rem 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .social-card {
        width: 100%;
        max-width: 480px;
        background: #fff;
        padding: 3rem 2.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
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

    h4 {
        color: var(--primary-color);
        font-size: 1.7rem;
        font-weight: 700;
        text-align: center;
    }

    p.sub-title {
        text-align: center;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
    }

    /* Form Inputs */
    .form-label {
        font-weight: 600;
        margin-bottom: 4px;
    }

    .form-control {
        padding: 0.75rem;
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
        background: #fcfdff;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(13, 96, 158, 0.2);
    }

    /* Company field animation */
    #company-field {
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transition: var(--transition);
    }

    #company-field.show {
        opacity: 1;
        max-height: 100px;
        margin-bottom: 1rem;
    }

    /* Terms */
    .terms-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        color: var(--text-muted);
    }

    .terms-row a {
        color: var(--primary-color);
        font-weight: 500;
        cursor: pointer;
    }

    /* Button */
    .continue-btn {
        width: 100%;
        padding: 0.9rem;
        font-size: 1rem;
        font-weight: 600;
        background: var(--primary-color);
        color: #fff;
        border: none;
        border-radius: var(--border-radius);
        transition: var(--transition);
    }

    .continue-btn:hover:not(:disabled) {
        background: #0b5ed7;
        transform: translateY(-2px);
    }

    .btn-loading.spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }

    /* Modal */
    .terms-modal {
        position: fixed;
        inset: 0;
        display: none;
        background: rgba(0, 0, 0, 0.55);
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .terms-content {
        background: #fff;
        width: 90%;
        max-width: 600px;
        border-radius: var(--border-radius);
        padding: 1.2rem;
        box-shadow: var(--shadow-lg);
        display: flex;
        flex-direction: column;
    }

    .terms-scroll {
        height: 300px;
        overflow-y: auto;
        padding: 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        background: #f8f9fb;
    }
</style>
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<div class="social-container">
    <div class="ribbon"></div>
    <div class="ribbon"></div>

    <div class="social-card">

        <h4>Complete Your Registration</h4>
        <p class="sub-title">Just one more step to finish setting up your account.</p>

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

        <form id="socialForm" action="<?= base_url('/auth/social/finalize') ?>" method="POST" novalidate>

            <!-- Full Name -->
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="full_name" value="<?= esc($social['full_name']) ?>" readonly>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" value="<?= esc($social['email']) ?>" readonly>
            </div>

            <!-- User Type -->
            <div class="mb-3">
                <label class="form-label">Account Type</label>
                <select class="form-control" name="user_type" id="user_type" required>
                    <option value="" disabled selected>-- Select --</option>
                    <option value="job_seeker">Job Seeker</option>
                    <option value="employer">Employer</option>
                </select>
            </div>

            <!-- Company Name -->
            <div class="mb-3" id="company-field">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-control" name="company_name" placeholder="Enter your company name">
            </div>

            <!-- Terms -->
            <div class="terms-row">
                <div>
                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label for="agree_terms">I agree to the Terms</label>
                </div>
                <a href="#" id="openTerms">Read Terms</a>
            </div>

            <!-- Continue -->
            <button id="continueBtn" class="continue-btn" type="submit">
                <span class="btn-text">Finish Registration</span>
                <span class="btn-loading d-none">
                    <span class="spinner-border spinner-border-sm"></span>
                    Processing...
                </span>
            </button>

        </form>
    </div>
</div>

<!-- Terms Modal -->
<div id="termsModal" class="terms-modal">
    <div class="terms-content">
        <h5 class="text-center mb-2" style="color: var(--primary-color);">Terms of Service & Privacy Policy</h5>

        <div class="terms-scroll">
            <?= view('auth/terms_content'); ?>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-secondary me-2" id="closeTerms">Close</button>
            <button class="btn btn-primary" id="acceptTerms" disabled>Accept</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const userType = document.getElementById('user_type');
        const companyField = document.getElementById('company-field');
        const companyInput = companyField.querySelector('input');

        userType.addEventListener('change', function() {
            if (this.value === 'employer') {
                companyField.classList.add('show');
                companyInput.required = true;
            } else {
                companyField.classList.remove('show');
                companyInput.required = false;
            }
        });

        // Modal logic
        const modal = document.getElementById('termsModal');
        const openBtn = document.getElementById('openTerms');
        const closeBtn = document.getElementById('closeTerms');
        const acceptBtn = document.getElementById('acceptTerms');
        const agreeTerms = document.getElementById('agree_terms');

        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        acceptBtn.addEventListener('click', () => {
            agreeTerms.checked = true;
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // Scroll unlock Accept button
        document.querySelector('.terms-scroll')
            .addEventListener('scroll', function() {
                if (this.scrollTop + this.clientHeight >= this.scrollHeight - 5) {
                    acceptBtn.disabled = false;
                }
            });

        // Loading state
        const form = document.getElementById('socialForm');
        const continueBtn = document.getElementById('continueBtn');

        form.addEventListener('submit', function() {
            continueBtn.disabled = true;
            continueBtn.querySelector('.btn-text').classList.add('d-none');
            continueBtn.querySelector('.btn-loading').classList.remove('d-none');
        });
    });
</script>
<?= $this->endSection() ?>