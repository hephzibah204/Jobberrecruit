<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> JobberRecruit - Login </title>
    <meta name="Description" content="JobberRecruit - Job Portal Platform">
    <meta name="Author" content="BITBIZ NIG LIMITED">
    <meta name="keywords" content="jobs in Nigeria, African job portal, find jobs, hire talent, recruitment platform, jobber recruit, employment portal">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('images/favicon.png'); ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= base_url('images/favicon.png'); ?>">

    <!-- Main Theme Js -->
    <script src="<?= base_url('admin/js/authentication-main.js'); ?>"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="<?= base_url('admin/libs/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Toastr -->
    <link href="<?= base_url('admin/css/toastr.min.css'); ?>" rel="stylesheet">

    <!-- Style Css -->
    <link href="<?= base_url('admin/css/styles.css'); ?>" rel="stylesheet">

    <!-- Icons Css -->
    <link href="<?= base_url('admin/css/icons.css'); ?>" rel="stylesheet">

    <style>
        /* ---------- TOASTR THEME FIX ---------- */

        #toast-container>.toast {
            background-image: none !important;
            color: #ffffff !important;
            opacity: 1 !important;
        }

        #toast-container>.toast-success {
            background-color: var(--bs-success) !important;
        }

        #toast-container>.toast-error {
            background-color: var(--bs-danger) !important;
        }

        #toast-container>.toast-warning {
            background-color: var(--bs-warning) !important;
            color: #000 !important;
        }

        #toast-container>.toast-info {
            background-color: var(--bs-primary) !important;
        }


        /* Message text */
        #toast-container .toast-message {
            color: inherit !important;
        }

        /* Close button */
        #toast-container .toast-close-button {
            color: #ffffff !important;
            opacity: 0.9;
        }
    </style>

</head>

<body class="bg-white">

    <div class="row authentication authentication-cover-main mx-0">
        <div class="col-xxl-9 col-xl-9">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-6 col-sm-8 col-12">
                    <div class="card custom-card border-0 shadow-none my-4">
                        <form class="card-body p-5" method="POST">
                            <div>
                                <h4 class="mb-1 fw-semibold">Hi,Welcome back!</h4>
                                <p class="mb-4 text-muted fw-normal">Please enter your credentials</p>
                            </div>
                            <div class="row gy-3">
                                <div class="col-xl-12">
                                    <label for="signin-email" class="form-label text-default">Email</label>
                                    <input type="text" class="form-control" id="signin-email" placeholder="Enter Email" name="email" required>
                                </div>
                                <div class="col-xl-12 mb-2">
                                    <label for="signin-password" class="form-label text-default d-block">Password</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" id="signin-password" name="password" placeholder="Enter Password" required>
                                        <a href="javascript:void(0);" class="show-password-button text-muted" onclick="createpassword('signin-password',this)" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></a>
                                    </div>
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                            <label class="form-check-label" for="defaultCheck1">
                                                Remember me
                                            </label>
                                            <!-- <a href="reset-password-basic.html" class="float-end link-danger fw-medium fs-12">Forget password ?</a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="loginBtn">
                                <span class="btn-text">Sign In</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-3 col-lg-12 d-xl-block d-none px-0">
            <div class="authentication-cover overflow-hidden">
                <div class="authentication-cover-logo">
                    <a href="<?= base_url('admin') ?>">
                        <img src="<?= base_url('assets/imgs/template/logo.png'); ?>" alt="logo" class="desktop-dark">
                    </a>
                </div>
                <div class="authentication-cover-background">
                    <img src="<?= base_url('images/lg.jpg'); ?>" alt="">
                </div>
                <div class="authentication-cover-content">
                    <div class="p-5">
                        <h3 class="fw-semibold lh-base">Welcome to Dashboard</h3>
                        <p class="mb-0 text-muted fw-medium">
                            Sign in to continue
                        </p>
                    </div>
                    <div>
                        <img src="<?= base_url('images/hero-banner.png'); ?>" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('admin/code.jquery.com/jquery-3.6.1.min.js'); ?>"></script>
    <script src="<?= base_url('admin/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- Toastr -->
    <script src="<?= base_url('admin/js/toastr.min.js'); ?>"></script>

    <!-- Show Password JS -->
    <script src="<?= base_url('admin/js/show-password.js'); ?>"></script>

    <script>
        $(function() {

            // Toastr global config
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 4000
            };

            $('form').on('submit', function(e) {
                e.preventDefault();

                const $btn = $('#loginBtn');
                const $btnText = $btn.find('.btn-text');
                const $spinner = $btn.find('.spinner-border');

                // Button loading state
                $btn.prop('disabled', true);
                $btnText.text('Signing in...');
                $spinner.removeClass('d-none');

                $.ajax({
                    url: window.location.href,
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",

                    success: function(response) {

                        if (response.success) {
                            toastr.success(response.message || 'Login successful');

                            // Redirect after short delay
                            setTimeout(() => {
                                window.location.href = response.redirect || "<?= base_url('admin/dashboard'); ?>";
                            }, 1200);

                        } else {
                            toastr.error(response.message || 'Invalid login credentials');
                        }
                    },

                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            Object.values(xhr.responseJSON.errors).forEach(err => {
                                toastr.error(err);
                            });
                        } else {
                            toastr.error('An unexpected error occurred. Please try again.');
                        }
                    },

                    complete: function() {
                        // Restore button
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