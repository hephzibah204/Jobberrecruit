<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>JobberRecruit - Two-Factor Authentication</title>
    <meta name="Description" content="JobberRecruit - Administration Dashboard Portal">
    <meta name="Author" content="BITBIZ NIG LIMITED">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('images/favicon.png'); ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= base_url('images/favicon.png'); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Css -->
    <link id="style" href="<?= base_url('admin/libs/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Icons Css -->
    <link href="<?= base_url('admin/css/icons.css'); ?>" rel="stylesheet">

    <style>
        :root {
            --brand: #0D609E;
            --brand-dark: #064A85;
            --brand-deep: #0A2F57;
            --brand-light: #E6F0F8;
            --accent: #F08F1A;
            --accent-dark: #C8770E;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --bg-glass: rgba(255, 255, 255, 0.85);
            --border-glass: rgba(255, 255, 255, 0.6);
            --shadow-premium: 0 20px 40px -15px rgba(10, 47, 87, 0.12);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--text-dark);
            background-color: #f8fafc;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .auth-container {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            min-height: 100vh;
            width: 100vw;
        }

        @media (max-width: 991.98px) {
            .auth-container {
                grid-template-columns: 1fr;
            }
            .auth-sidebar-panel {
                display: none !important;
            }
        }

        /* --- Form Column --- */
        .auth-form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: radial-gradient(circle at 10% 20%, rgba(230, 240, 248, 0.4) 0%, rgba(255, 255, 255, 0.4) 90%);
            position: relative;
        }

        .auth-form-card {
            width: 100%;
            max-width: 440px;
            background: var(--bg-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            padding: 40px;
            box-shadow: var(--shadow-premium);
            transition: var(--transition-smooth);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-brand-mobile {
            display: none;
            margin-bottom: 32px;
        }

        @media (max-width: 991.98px) {
            .auth-brand-mobile {
                display: block;
            }
        }

        .auth-header h2 {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            color: var(--brand-deep);
            font-size: 1.75rem;
            letter-spacing: -0.02em;
        }

        /* --- Input Fields --- */
        .form-floating-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .form-floating-custom .form-control {
            height: 58px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 22px 16px 6px 48px;
            font-size: 0.95rem;
            color: var(--text-dark);
            background-color: #fff;
            transition: var(--transition-smooth);
        }

        .form-floating-custom .form-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(13, 96, 158, 0.12);
            background-color: #fff;
        }

        .form-floating-custom label {
            position: absolute;
            left: 48px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            transition: var(--transition-smooth);
            font-size: 0.92rem;
            transform-origin: left top;
        }

        .form-floating-custom .form-control:focus ~ label,
        .form-floating-custom .form-control:not(:placeholder-shown) ~ label {
            top: 14px;
            transform: translateY(-50%) scale(0.75);
            left: 48px;
            color: var(--brand);
            font-weight: 600;
        }

        .form-floating-custom .form-control:focus ~ .input-icon,
        .form-floating-custom .form-control:not(:placeholder-shown) ~ .input-icon {
            color: var(--brand);
        }

        .form-floating-custom .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.25rem;
            transition: var(--transition-smooth);
            pointer-events: none;
        }

        .form-floating-custom .code-input {
            letter-spacing: 0.35em;
            font-weight: 600;
        }

        /* --- Buttons --- */
        .btn-modern-primary {
            height: 54px;
            border-radius: 12px;
            font-weight: 600;
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            border: none;
            color: #fff;
            box-shadow: 0 8px 20px -6px rgba(13, 96, 158, 0.4);
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }

        .btn-modern-primary:hover {
            background: linear-gradient(135deg, var(--brand-dark) 0%, var(--brand-deep) 100%);
            transform: translateY(-1.5px);
            box-shadow: 0 10px 24px -6px rgba(10, 47, 87, 0.5);
            color: #fff;
        }

        .btn-modern-primary:active {
            transform: translateY(0.5px);
        }

        /* --- Sidebar Panel --- */
        .auth-sidebar-panel {
            background: radial-gradient(circle at 100% 0%, rgba(240, 143, 26, 0.15) 0%, transparent 60%),
                        radial-gradient(circle at 0% 100%, rgba(13, 96, 158, 0.25) 0%, transparent 60%),
                        linear-gradient(145deg, var(--brand-deep) 0%, #081d36 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .sidebar-grid-pattern {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(circle at 50% 50%, black, transparent 80%);
            pointer-events: none;
        }

        .sidebar-brand img {
            height: 48px;
            width: auto;
        }

        .sidebar-hero {
            z-index: 2;
            position: relative;
        }

        .sidebar-hero h3 {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 2.2rem;
            line-height: 1.25;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
        }

        .sidebar-hero h3 span {
            color: var(--accent);
        }

        .sidebar-hero p {
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.6;
        }

        .sidebar-footer {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
            display: flex;
            align-items: center;
            gap: 6px;
        }
    </style>
</head>

<body>

    <div class="auth-container">

        <!-- Left Panel: Form -->
        <div class="auth-form-panel">
            <div class="auth-form-card">

                <!-- Brand logo for mobile -->
                <div class="auth-brand-mobile text-center">
                    <img src="<?= base_url('images/logo.png'); ?>" alt="logo" height="42">
                </div>

                <div class="auth-header mb-4">
                    <h2>Two-Factor Authentication</h2>
                    <p class="text-muted small mb-0">Enter the 6-digit code from your authenticator app to finish signing in.</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger py-2" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success py-2" role="alert"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('admin/2fa') ?>">
                    <?= csrf_field() ?>

                    <!-- Code -->
                    <div class="form-floating-custom">
                        <input type="text" class="form-control code-input" id="totp-code" name="code" placeholder=" " required autocomplete="one-time-code" inputmode="numeric" pattern="[0-9 ]*" maxlength="7" autofocus>
                        <label for="totp-code">Authentication Code</label>
                        <i class="ri-shield-check-line input-icon"></i>
                    </div>

                    <button type="submit" class="btn btn-modern-primary">
                        <span class="btn-text">Verify</span>
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="<?= base_url('admin/logout') ?>" class="small text-muted">Sign out and use another account</a>
                </div>

            </div>
        </div>

        <!-- Right Panel: Creative Background Info -->
        <div class="auth-sidebar-panel">
            <div class="sidebar-grid-pattern"></div>

            <div class="sidebar-brand">
                <img src="<?= base_url('images/logo-white.png'); ?>" alt="JobberRecruit Logo">
            </div>

            <div class="sidebar-hero">
                <h3>One more step to<br>keep the <span>Powerhouse</span> safe.</h3>
                <p>Your account is protected with two-factor authentication. Codes refresh every 30 seconds in your authenticator app.</p>
            </div>

            <div class="sidebar-footer">
                <i class="ri-shield-check-line text-success fs-16"></i> Secure Admin Session &bull; JobberRecruit &copy; <?= date('Y') ?>
            </div>
        </div>

    </div>

</body>

</html>
