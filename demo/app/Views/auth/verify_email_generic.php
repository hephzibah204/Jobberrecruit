<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --primary-color: #0D609E;
        --secondary-color: #F0890E;
        --text-dark: #1E293B;
        --text-muted: #64748B;
        --bg-light: #f8f9fb;
        --border-radius: 0.5rem;
        --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    }

    body {
        background-color: #f0f4f8;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        min-height: 100vh;
        position: relative;
        padding: 0;
        margin: 0;
    }

    /* Background ribbons */
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
        animation-delay: -7s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-25px);
        }
    }

    .verify-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem 1rem;
        min-height: 100vh;
        position: relative;
        z-index: 1;
    }

    .verify-card {
        width: 100%;
        max-width: 480px;
        background: #fff;
        border-radius: var(--border-radius);
        padding: 2.5rem 2rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid #e3e6ea;
        text-align: center;
        animation: fadeInUp 0.8s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(25px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .verify-card h3 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .verify-card p {
        color: var(--text-muted);
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .email-text {
        display: inline-block;
        background: #eef6ff;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius);
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1.3rem;
    }

    .btn-resend {
        background-color: var(--primary-color);
        color: #fff;
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: var(--border-radius);
        font-weight: 600;
        transition: 0.25s ease;
        width: 100%;
        margin-bottom: 1rem;
    }

    .btn-resend:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(13, 96, 158, 0.3);
    }

    .small-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
        margin-top: 0.8rem;
    }

    .small-link:hover {
        text-decoration: underline;
    }
</style>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="verify-container">
    <div class="verify-card">

        <h3>Email Verification Required</h3>

        <p>
            A verification link has been sent to your email address.
            <br>Click the link in your inbox to activate your account.
        </p>

        <?php if (!empty($email)) : ?>
            <div class="email-text"><?= esc($email) ?></div>
        <?php endif; ?>

        <button id="btnResend" class="btn-resend">
            Resend Verification Email
        </button>

        <a href="<?= base_url('login'); ?>" class="small-link">
            Back to Login
        </a>

    </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    document.getElementById('btnResend')?.addEventListener('click', function() {
        this.disabled = true;
        this.textContent = "Sending...";

        fetch("<?= base_url('auth/resend-verification'); ?>")
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    alert("Verification email sent successfully.");
                } else {
                    alert(data.message || "Unable to resend email.");
                }
            })
            .finally(() => {
                this.disabled = false;
                this.textContent = "Resend Verification Email";
            });
    });
</script>
<?= $this->endSection() ?>