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
    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        position: relative;
        z-index: 1;
    }

    .register-card {
        width: 100%;
        max-width: 480px;
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

    .register-card .logo {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .register-card .logo img {
        height: 50px;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        transition: var(--transition);
    }

    .register-card .logo img:hover {
        transform: scale(1.05);
    }

    .register-card h4 {
        color: var(--primary-color);
        text-align: center;
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    .register-card p {
        font-size: 0.95rem;
        text-align: center;
        color: var(--text-secondary);
        margin-bottom: 2rem;
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

    /* Company Field */
    #company-field {
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transition: var(--transition);
        margin-bottom: 0;
    }

    #company-field.show {
        opacity: 1;
        max-height: 100px;
        margin-bottom: 1rem;
    }

    /* Checkbox */
    .cb-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }

    .cb-container input[type="checkbox"] {
        accent-color: var(--primary-color);
        margin-right: 0.5rem;
    }

    .cb-container a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    .cb-container a:hover {
        text-decoration: underline;
    }

    /* Submit Button */
    .register-btn {
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

    .register-btn:hover:not(:disabled) {
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

    /* Login Link */
    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.95rem;
        color: var(--text-secondary);
    }

    .login-link a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .register-card {
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

    /* Terms Modal */
    .terms-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1050;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .terms-content {
        background: #fff;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(40px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .terms-content h5 {
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-weight: 700;
        text-align: center;
    }

    .terms-scroll {
        flex-grow: 1;
        overflow-y: auto;
        padding: 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        background: #f8f9fb;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .terms-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .terms-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(13, 96, 158, 0.3);
        border-radius: 5px;
    }

    .terms-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
        gap: 0.5rem;
    }

    .terms-actions .btn {
        border-radius: var(--border-radius);
        padding: 0.6rem 1rem;
        font-weight: 600;
        cursor: pointer;
    }

    .terms-actions .btn-primary {
        background-color: var(--primary-color);
        color: #fff;
        border: none;
    }

    .terms-actions .btn-primary:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .terms-actions .btn-secondary {
        background-color: var(--text-muted);
        color: #fff;
        border: none;
    }

    .terms-scroll {
        box-shadow: inset 0 -8px 10px -10px rgba(0, 0, 0, 0.3);
        transition: box-shadow 0.3s ease;
    }

    .terms-scroll:after {
        content: '';
        position: sticky;
        bottom: 0;
        display: block;
        height: 20px;
        background: linear-gradient(to bottom, transparent, #f8f9fb);
    }

    .google-login-btn {
        display: flex;
        align-items: center;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        color: #fff;
        width: 100%;
        overflow: hidden;
        border-radius: 5px;
        background-color: #d6523e;
        cursor: pointer;
    }

    .google-login-btn .icon {
        display: inline-flex;
        height: 100%;
        padding: 15px 20px;
        align-items: center;
        justify-content: center;
        background-color: #cf412c;
        margin-right: 15px;
    }

    .google-login-btn .icon svg {
        fill: #fff;
    }

    .google-login-btn:hover {
        background-color: #d44a36;
    }

    .google-login-btn:hover .icon {
        background-color: #c63f2a;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="register-container">
    <div class="ribbon"></div>
    <div class="ribbon"></div>

    <div class="register-card">
        <!-- <div class="logo">
            <img src="<?= base_url('assets/imgs/template/logo.png'); ?>" alt="Company Logo" loading="lazy">
        </div> -->
        <h4>Sign Up for Free</h4>
        <p>Fill in the details below to get started.</p>

        <button class="social-login" type="button" onclick="window.location.href='<?= base_url('auth/google'); ?>'">
            <img src="<?= base_url('assets/imgs/template/icons/icon-google.svg'); ?>" alt="Google">
            <strong>Sign up with Google</strong>
        </button>

        <button class="social-login" type="button" onclick="window.location.href='<?= base_url('auth/linkedin'); ?>'">
            <img src="<?= base_url('assets/imgs/template/icons/linkedin.svg'); ?>" alt="LinkedIn">
            <strong>Sign up with LinkedIn</strong>
        </button>

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

        <form id="registerForm" action="<?= base_url('register'); ?>" method="POST" novalidate>
            <input type="hidden" name="referral_code" value="<?= esc(request()->getGet('ref')) ?>">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter your full name" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="user_type" class="form-label">Account Type</label>
                <select class="form-control" id="user_type" name="user_type" required>
                    <option value="" disabled selected>-- Choose --</option>
                    <option value="job_seeker">Job Seeker</option>
                    <option value="employer">Employer</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3" id="company-field">
                <label for="company_name" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter your company name" required>
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

            <div class="mb-3 position-relative">
                <label for="password_confirm" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm your password" required>
                    <span class="input-group-text password-toggle" style="cursor:pointer">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
                <div class="invalid-feedback"></div>
            </div>

            <div class="cb-container">
                <div>
                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label for="agree_terms">Agree to our terms and policy</label>
                </div>
                <a href="#" target="_blank">Learn more</a>
            </div>

            <button id="registerBtn" type="submit" class="register-btn">
                <span class="btn-text">Register</span>
                <span class="btn-loading d-none">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    Creating Account...
                </span>
            </button>
        </form>

        <div class="login-link">
            Already have an account? <a href="<?= base_url('login'); ?>">Sign in</a>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div id="termsModal" class="terms-modal">
    <div class="terms-content">
        <h5>Terms of Service & Privacy Policy</h5>

        <div class="terms-scroll">

            <p>
                Welcome to <strong>JobberRecruit</strong> — a trusted platform connecting employers with talented job seekers.
                Before proceeding, please review the following terms and privacy conditions carefully.
            </p>

            <p>
                By clicking <strong>“Accept & Continue,”</strong> you confirm that you have read, understood,
                and agree to abide by these Terms and our Privacy Policy.<br>
                <strong>Please scroll to the end to activate the Accept button.</strong>
            </p>

            <p><strong>Last Updated:</strong> 01/12/2025</p>

            <p>
                JobberRecruit (“we,” “our,” or “the Platform”) provides recruitment-related services designed to help employers find qualified candidates and assist job seekers in accessing verified employment opportunities.
                These Terms of Service & Privacy Policy (“Terms”) govern your relationship with us when accessing, registering,
                or using our platform, whether as an employer, job seeker, or visitor.
            </p>

            <p>By using JobberRecruit, you agree to these Terms. If you do not agree, do not use the Platform.</p>

            <h6>1. DEFINITIONS</h6>
            <ul>
                <li><strong>Platform / Service:</strong> All products, features, tools, and communication channels provided by JobberRecruit.</li>
                <li><strong>User:</strong> Anyone accessing or using the platform.</li>
                <li><strong>Employer:</strong> Any organization posting jobs or recruiting talent.</li>
                <li><strong>Job Seeker:</strong> Any individual viewing or applying for jobs.</li>
                <li><strong>Content:</strong> Job posts, CVs, profiles, messages, and all information processed through the platform.</li>
                <li><strong>Third Parties:</strong> External tools, services, advertisers, or partners.</li>
            </ul>

            <h6>2. GENERAL TERMS (Applies to All Users)</h6>
            <p><strong>2.1 Use of the Platform</strong></p>
            <ul>
                <li>You must be at least 18 years old to use JobberRecruit.</li>
                <li>You agree to provide accurate and truthful information.</li>
                <li>You must not use the platform for fraudulent or unlawful activity.</li>
                <li>We may modify or discontinue services at any time.</li>
            </ul>

            <p><strong>2.2 Third-Party Content</strong></p>
            <ul>
                <li>Some job listings or company data are provided by employers.</li>
                <li>We do not guarantee the accuracy of third-party information.</li>
                <li>We are not liable for hiring decisions or external website content.</li>
            </ul>

            <p><strong>2.3 No Employment Guarantee</strong></p>
            <ul>
                <li>We do not guarantee job placement, interviews, or offers.</li>
                <li>Employers control their own recruitment process.</li>
            </ul>

            <p><strong>2.4 Service Availability</strong></p>
            <ul>
                <li>Notifications may depend on external email or SMS providers.</li>
                <li>We are not responsible for undelivered or delayed alerts.</li>
            </ul>

            <p><strong>2.5 Intellectual Property</strong></p>
            <ul>
                <li>All platform designs, trademarks, and technology belong to JobberRecruit.</li>
                <li>Do not copy, distribute, or reproduce platform material without permission.</li>
            </ul>

            <p><strong>2.6 Termination</strong></p>
            <ul>
                <li>We may suspend or terminate accounts that violate these Terms.</li>
            </ul>

            <h6>3. EMPLOYER TERMS</h6>

            <p><strong>3.1 Job Posting Standards</strong></p>
            <ul>
                <li>Job posts must be accurate, legal, and non-discriminatory.</li>
                <li>Only real and currently available roles may be posted.</li>
            </ul>

            <p><strong>3.2 Employer Responsibilities</strong></p>
            <ul>
                <li>Employers are fully responsible for candidate screening and hiring decisions.</li>
                <li>Compliance with labour laws is the employer’s responsibility.</li>
            </ul>

            <p><strong>3.3 Payments & Subscriptions</strong></p>
            <ul>
                <li>Paid features require full payment before activation.</li>
                <li>Fees are non-refundable unless stated otherwise.</li>
            </ul>

            <p><strong>3.4 Prohibited Employer Activities</strong></p>
            <ul>
                <li>Requesting money from job seekers.</li>
                <li>Posting deceptive, scam, or misleading opportunities.</li>
                <li>Misusing job seeker data for non-recruitment purposes.</li>
            </ul>

            <h6>4. JOB SEEKER TERMS</h6>

            <p><strong>4.1 Use of Job Alerts & Applications</strong></p>
            <ul>
                <li>We do not guarantee job responses or interview invitations.</li>
            </ul>

            <p><strong>4.2 CV & Profile Information</strong></p>
            <ul>
                <li>All information you submit must be accurate.</li>
                <li>Employers may access this information solely for recruitment.</li>
            </ul>

            <p><strong>4.3 Prohibited Job Seeker Activities</strong></p>
            <ul>
                <li>Submitting false or misleading information.</li>
                <li>Uploading harmful files.</li>
                <li>Contacting employers for unsolicited services.</li>
            </ul>

            <h6>5. PRIVACY POLICY</h6>

            <p><strong>5.1 Information We Collect</strong></p>
            <ul>
                <li>Personal details (name, email, phone).</li>
                <li>CV and professional background.</li>
                <li>Company data (for employers).</li>
                <li>Usage data (IP address, browser type, device info).</li>
                <li>Messages and submissions made through the platform.</li>
            </ul>

            <p><strong>5.2 How We Use Your Information</strong></p>
            <ul>
                <li>Deliver recruitment services and job alerts.</li>
                <li>Improve performance, security, and user experience.</li>
                <li>Verify activity and prevent fraud.</li>
                <li>Allow employers to evaluate applications.</li>
                <li>Meet legal or regulatory obligations.</li>
            </ul>

            <p><strong>5.3 Sharing of Information</strong></p>
            <ul>
                <li>Employers receive applicant data for hiring purposes.</li>
                <li>Trusted third-party partners may assist service delivery.</li>
                <li>Government agencies when required by law.</li>
                <li>We do <strong>not</strong> sell user data.</li>
            </ul>

            <p><strong>5.4 Data Storage & Security</strong></p>
            <ul>
                <li>We use industry-standard security protocols.</li>
                <li>No platform can guarantee 100% protection.</li>
            </ul>

            <p><strong>5.5 Cookies & Tracking</strong></p>
            <ul>
                <li>We use cookies to personalize content and analyze usage.</li>
            </ul>

            <p><strong>5.6 Data Retention</strong></p>
            <ul>
                <li>Data is retained as long as required for service or legal compliance.</li>
                <li>Users may request deletion of their data.</li>
            </ul>

            <h6>6. LIMITATION OF LIABILITY</h6>
            <ul>
                <li>We are not responsible for employer decisions or job seeker actions.</li>
                <li>We are not liable for indirect, special, or economic loss.</li>
                <li>Our maximum liability is limited to fees paid in the month of the incident.</li>
            </ul>

            <h6>7. USER INDEMNITY</h6>
            <p>
                You agree to indemnify and hold JobberRecruit harmless from any claims arising from your misuse of the Platform or violation of these Terms.
            </p>

            <h6>8. GOVERNING LAW</h6>
            <p>
                These Terms are governed by the laws of the Federal Republic of Nigeria.
            </p>

            <h6>9. ACCEPTANCE OF TERMS</h6>
            <p>
                By clicking <strong>“Accept & Continue,”</strong> you confirm that you agree to these Terms and our Privacy Policy.
                If you do not agree, discontinue use immediately.
            </p>

        </div>

        <div class="terms-actions">
            <button type="button" class="btn btn-secondary" id="closeTerms">Close</button>
            <button type="button" class="btn btn-primary" id="acceptTerms" disabled>Accept & Continue</button>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= env('recaptcha_site_key'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registerForm');
        const fullNameInput = document.getElementById('full_name');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const userTypeSelect = document.getElementById('user_type');
        const companyField = document.getElementById('company-field');
        const companyInput = document.getElementById('company_name');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirm');
        const agreeTerms = document.getElementById('agree_terms');
        const registerBtn = document.getElementById('registerBtn');

        fullNameInput.focus();

        // 1️⃣ Show/Hide Company Field
        userTypeSelect.addEventListener('change', function() {
            if (this.value === 'employer') {
                companyField.classList.add('show');
                companyInput.required = true;
            } else {
                companyField.classList.remove('show');
                companyInput.required = false;
                companyInput.value = '';
                clearError(companyInput);
            }
        });

        // 2️⃣ Validation Fields
        const fields = [fullNameInput, emailInput, phoneInput, companyInput, passwordInput, confirmPasswordInput, userTypeSelect];

        fields.forEach(field => {
            field.addEventListener('input', function() {
                clearError(this);
                validateField(this);
            });
            field.addEventListener('blur', function() {
                validateField(this);
            });
        });

        [passwordInput, confirmPasswordInput].forEach(input => {
            input.addEventListener('input', validatePasswordMatch);
        });

        function validateField(field) {
            const value = field.value.trim();
            let errorMsg = '';

            if (field === fullNameInput) {
                if (!value) {
                    errorMsg = 'Full name is required.';
                } else if (!/^[a-zA-Z\s]{2,}$/.test(value)) {
                    errorMsg = 'Full name must contain only letters and spaces.';
                } else {
                    const names = value.split(/\s+/);
                    if (names.length < 2) errorMsg = 'Please include both first and last names.';
                }
            } else if (field === emailInput) {
                if (!value) errorMsg = 'Email address is required.';
                else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value))
                    errorMsg = 'Enter a valid email address.';
            } else if (field === phoneInput) {
                if (!value) errorMsg = 'Phone number is required.';
                else if (!/^\+?[\d\s\-\(\)]{10,}$/.test(value))
                    errorMsg = 'Enter a valid phone number.';
            } else if (field === userTypeSelect && !value) {
                errorMsg = 'Please select an account type.';
            } else if (field === companyInput && companyInput.required) {
                if (!value) errorMsg = 'Company name is required.';
            } else if (field === passwordInput) {
                if (!value) errorMsg = 'Password is required.';
                else if (value.length < 6)
                    errorMsg = 'Password must be at least 6 characters.';
            } else if (field === confirmPasswordInput) {
                return validatePasswordMatch();
            }

            if (errorMsg) {
                showFieldError(field, errorMsg);
                return false;
            } else {
                clearError(field);
                return true;
            }
        }

        function validatePasswordMatch() {
            const pwd = passwordInput.value.trim();
            const cnf = confirmPasswordInput.value.trim();
            clearError(confirmPasswordInput);

            if (cnf && pwd !== cnf) {
                showFieldError(confirmPasswordInput, 'Passwords do not match.');
                return false;
            }
            return true;
        }

        // ------------------------------------------------------------------
        // 🔐 FINAL RECAPTCHA v3 IMPLEMENTATION
        // ------------------------------------------------------------------
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            grecaptcha.ready(async function() {
                try {
                    const token = await grecaptcha.execute('<?= env('recaptcha_site_key'); ?>', {
                        action: 'register'
                    });

                    // Append token to form
                    let tokenInput = document.querySelector('input[name="g-recaptcha-response"]');
                    if (!tokenInput) {
                        tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = 'g-recaptcha-response';
                        form.appendChild(tokenInput);
                    }
                    tokenInput.value = token;

                    // Submit form through AJAX
                    submitRegisterForm();

                } catch (err) {
                    toastr?.error("Security verification failed. Please refresh the page.");
                    console.error(err);
                }
            });
        });

        // ------------------------------------------------------------------
        // 📌 AJAX REGISTRATION FUNCTION
        // ------------------------------------------------------------------
        async function submitRegisterForm() {
            setLoading(true);

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                const data = await response.json();

                if (data.status === "success") {
                    toastr.success(data.message || "Registered successfully!");
                    setTimeout(() => {
                        window.location.href = data.redirect_url || "<?= base_url('login'); ?>";
                    }, 1200);
                } else {
                    handleServerErrors(data);
                }

            } catch (error) {
                toastr.error("Network error. Please check your connection.");
                console.error(error);
            } finally {
                setLoading(false);
            }
        }


        // 4️⃣ Helpers
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
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) showFieldError(input, msg);
                });
            }
            toastr?.error(data.message || 'Please fix the errors.');
        }

        // ------------------------------------------------------------------
        // 🔧 UTILITY: Button Loading State
        // ------------------------------------------------------------------
        function setLoading(loading) {
            const txt = registerBtn.querySelector('.btn-text');
            const ld = registerBtn.querySelector('.btn-loading');
            registerBtn.disabled = loading;
            txt.classList.toggle('d-none', loading);
            ld.classList.toggle('d-none', !loading);
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

        // 6️⃣ Terms Modal Logic
        const termsLink = document.querySelector('.cb-container a');
        const termsModal = document.getElementById('termsModal');
        const termsScroll = document.querySelector('.terms-scroll');
        const acceptBtn = document.getElementById('acceptTerms');
        const closeBtn = document.getElementById('closeTerms');
        const agreeCheckbox = document.getElementById('agree_terms');

        // Show modal
        termsLink.addEventListener('click', function(e) {
            e.preventDefault();
            termsModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        // Enable "Accept" button after scrolling to bottom
        termsScroll.addEventListener('scroll', function() {
            const nearBottom = this.scrollTop + this.clientHeight >= this.scrollHeight - 5;
            if (nearBottom) {
                acceptBtn.disabled = false;
                acceptBtn.textContent = 'Accept & Continue';
            }
        });

        // Close modal (no accept)
        closeBtn.addEventListener('click', function() {
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // Accept terms
        acceptBtn.addEventListener('click', function() {
            agreeCheckbox.checked = true;
            clearError(agreeCheckbox);
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            toastr?.success('You have accepted the terms and policy.');
        });
    });
</script>
<?= $this->endSection() ?>