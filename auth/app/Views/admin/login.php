<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>JobberRecruit - Administration Portal</title>
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
    <!-- Toastr -->
    <link href="<?= base_url('admin/css/toastr.min.css'); ?>" rel="stylesheet">
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

        /* --- Login Form Column --- */
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
            height: 56px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 16px 16px 16px 48px;
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
            font-size: 0.95rem;
        }

        .form-floating-custom .form-control:focus ~ label,
        .form-floating-custom .form-control:not(:placeholder-shown) ~ label {
            top: 8px;
            transform: translateY(0) scale(0.8);
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

        .form-floating-custom .toggle-pass {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            border: none;
            background: none;
            padding: 4px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: var(--transition-smooth);
        }

        .form-floating-custom .toggle-pass:hover {
            color: var(--brand);
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

        .form-check-input:checked {
            background-color: var(--brand);
            border-color: var(--brand);
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

        /* --- Toastr Theme Overrides --- */
        #toast-container>.toast {
            background-image: none !important;
            border-radius: 12px;
            padding: 16px 16px 16px 54px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            opacity: 1 !important;
            color: #fff !important;
        }
        #toast-container>.toast-success { background-color: #10b981 !important; }
        #toast-container>.toast-error { background-color: #ef4444 !important; }
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
                    <h2>Hi, Welcome back!</h2>
                    <p class="text-muted small mb-0">Please enter your administration credentials.</p>
                </div>

                <form id="adminLoginForm" method="POST" novalidate>
                    <?= csrf_field() ?>
                    
                    <!-- Email -->
                    <div class="form-floating-custom">
                        <input type="email" class="form-control" id="signin-email" name="email" placeholder=" " required autocomplete="email">
                        <label for="signin-email">Admin Email</label>
                        <i class="ri-mail-line input-icon"></i>
                    </div>

                    <!-- Password -->
                    <div class="form-floating-custom">
                        <input type="password" class="form-control" id="signin-password" name="password" placeholder=" " required autocomplete="current-password">
                        <label for="signin-password">Password</label>
                        <i class="ri-lock-line input-icon"></i>
                        <button type="button" class="toggle-pass" onclick="togglePasswordVisibility('signin-password', this)">
                            <i class="ri-eye-off-line" id="eyeIcon"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                            <label class="form-check-label text-muted small" for="rememberMe">
                                Keep me logged in
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-modern-primary" id="loginBtn">
                        <span class="btn-text">Sign In</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </form>

            </div>
        </div>

        <!-- Right Panel: Creative Background Info -->
        <div class="auth-sidebar-panel">
            <div class="sidebar-grid-pattern"></div>
            
            <div class="sidebar-brand">
                <img src="<?= base_url('images/logo-white.png'); ?>" alt="JobberRecruit Logo">
            </div>

            <div class="sidebar-hero">
                <h3>Control the Nigeria's<br>Talent <span>Powerhouse</span>.</h3>
                <p>Verify employers, manage job seekers, monitor wallets, and configure core portal parameters with the highest-level security and analytics tools.</p>
            </div>

            <div class="sidebar-footer">
                <i class="ri-shield-check-line text-success fs-16"></i> Secure Admin Session &bull; JobberRecruit &copy; <?= date('Y') ?>
            </div>
        </div>

    </div>

    <!-- Core Scripts -->
    <script src="<?= base_url('admin/code.jquery.com/jquery-3.6.1.min.js'); ?>"></script>
    <script src="<?= base_url('admin/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- Toastr -->
    <script src="<?= base_url('admin/js/toastr.min.js'); ?>"></script>

    <script>
        // Password toggler helper
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ri-eye-line';
            } else {
                input.type = 'password';
                icon.className = 'ri-eye-off-line';
            }
        }

        $(function() {
            // Toastr options
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 4000
            };

            // AJAX Form Submit
            $('#adminLoginForm').on('submit', function(e) {
                e.preventDefault();

                const $btn = $('#loginBtn');
                const $btnText = $btn.find('.btn-text');
                const $spinner = $btn.find('.spinner-border');

                // Toggle loading state
                $btn.prop('disabled', true);
                $btnText.text('Verifying...');
                $spinner.removeClass('d-none');

                $.ajax({
                    url: window.location.href,
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message || 'Access granted. Redirecting...');
                            setTimeout(() => {
                                window.location.href = response.redirect || "<?= base_url('admin/dashboard'); ?>";
                            }, 1200);
                        } else {
                            toastr.error(response.message || 'Invalid administrator credentials.');
                            // Restore state
                            $btn.prop('disabled', false);
                            $btnText.text('Sign In');
                            $spinner.addClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            Object.values(xhr.responseJSON.errors).forEach(err => {
                                toastr.error(err);
                            });
                        } else {
                            toastr.error('An unexpected authentication error occurred.');
                        }
                        // Restore state
                        $btn.prop('disabled', false);
                        $btnText.text('Sign In');
                        $spinner.addClass('d-none');
                    }
                });
            });
        });
    </script>
</body>

</html>