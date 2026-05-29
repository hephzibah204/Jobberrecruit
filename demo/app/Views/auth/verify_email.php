<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<style>
    body {
        background-color: #f0f4f8;
        min-height: 100vh;
        position: relative;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
        overflow-x: hidden;
    }

    /* Ribbons Background */
    body::before,
    body::after {
        content: "";
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
        background: radial-gradient(circle, rgba(13, 96, 158, 0.12), transparent 70%);
    }

    body::after {
        bottom: -150px;
        right: -150px;
        background: radial-gradient(circle, rgba(240, 137, 14, 0.15), transparent 70%);
        animation-delay: -7s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    .verify-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 1.5rem;
    }

    .verify-card {
        background: #fff;
        max-width: 480px;
        width: 100%;
        padding: 3rem 2.5rem;
        border-radius: 12px;
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        text-align: center;
        position: relative;
    }

    .verify-card h3 {
        color: #005DA8;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .verify-card p {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        width: 100%;
        padding: 0.85rem;
        background-color: #005DA8;
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        display: inline-block;
        transition: 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #0b4f85;
    }

    .btn-resend {
        width: 80%;
        padding: 0.85rem;
        background-color: #F5A623;
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
    }

    .resend-link:hover {
        text-decoration: underline;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="verify-container">
    <div class="verify-card">

        <h3>Email Verification Required</h3>

        <p>
            A verification email has been sent to:<br>
            <strong><?= esc($email) ?></strong><br><br>
            Please check your inbox and click the verification link to continue.
        </p>

        <button id="btnResend" class="btn-resend">
            Resend Verification Email
        </button>

        <br><br>

        <a href="<?= base_url('/login') ?>" class="btn-primary">Return to Login</a>

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
                    toastr.success("Verification email sent successfully.");
                } else {
                    toastr.error(data.message || "Unable to resend email.");
                }
            })
            .finally(() => {
                this.disabled = false;
                this.textContent = "Resend Verification Email";
            });
    });
</script>
<?= $this->endSection() ?>