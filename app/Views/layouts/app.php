<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('jr-theme');
            const preferDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (preferDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-theme-mode', theme);
        })();
    </script>

    <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, viewport-fit=cover">
    <meta name="theme-color" content="#0D609E">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- SECURITY SEO: Prevent Indexing of Dashboard Pages -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">

    <!-- Page Title -->
    <title><?= $title ?? 'Dashboard' ?> - JobberRecruit</title>

    <!-- Canonical (Optional but Good for Cleanliness) -->
    <link rel="canonical" href="<?= current_url(); ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('auth/img/favicon.png'); ?>" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('auth/img/apple-touch-icon.png'); ?>">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= base_url('auth/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/css/toastr.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/css/bootstrap-datetimepicker.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/css/animate.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/css/feather.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/quill/quill.snow.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/select2/css/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/intltelinput/css/intlTelInput.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/css/dataTables.bootstrap5.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/daterangepicker/daterangepicker.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/tabler-icons/tabler-icons.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/jvectormap/jquery-jvectormap-2.0.5.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/simonwep/pickr/themes/nano.min.css'); ?>">

    <!-- Main Theme -->
    <link rel="stylesheet" href="<?= base_url('auth/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('css/global-core.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('css/mobile-app.css'); ?>">
    <!-- Midnight Aura Theme -->
    <?php if (isset($activeTheme) && $activeTheme === 'midnight-aura'): ?>
        <link id="midnight-aura-css" href="<?= base_url('css/midnight-aura.css'); ?>" rel="stylesheet">
    <?php endif; ?>
    <!-- Unified Typography (Sora & Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Page-Level Styles -->
    <?= $this->renderSection('styles') ?>

</head>


<body>
    <?= $this->include('partials/svg_sprites') ?>
    <?= $this->include('partials/splash_screen') ?>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        <?= $this->include('layouts/header'); ?>
        <!-- /Header -->

        <!-- Sidebar -->
        <?= $this->include('layouts/sidebar'); ?>
        <!-- /Sidebar -->

        <div class="page-wrapper">
            <?= $this->renderSection('content') ?>
            <?= $this->include('layouts/footer'); ?>
            
            <!-- Mobile Bottom App Navigation -->
            <?= $this->include('partials/mobile_bottom_nav') ?>
        </div>


    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="<?= base_url('auth/js/jquery-3.7.1.min.js'); ?>" type="text/javascript"></script>

    <!-- Feather Icon JS -->
    <script src="<?= base_url('auth/js/feather.min.js'); ?>" type="text/javascript"></script>

    <!-- Slimscroll JS -->
    <script src="<?= base_url('auth/js/jquery.slimscroll.min.js'); ?>" type="text/javascript"></script>

    <!-- Datatable JS -->
    <script src="<?= base_url('auth/js/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('auth/js/dataTables.bootstrap5.min.js'); ?>" type="text/javascript"></script>

    <!-- Bootstrap Core JS -->
    <script src="<?= base_url('auth/js/bootstrap.bundle.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('auth/js/toastr.min.js'); ?>" type="text/javascript"></script>

    <!-- Chart JS -->
    <script src="<?= base_url('auth/plugins/apexchart/apexcharts.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('auth/plugins/apexchart/chart-data.js'); ?>" type="text/javascript"></script>

    <!-- Select2 JS -->
    <script src="<?= base_url('auth/plugins/select2/js/select2.min.js'); ?>" type="text/javascript"></script>

    <!-- Quill JS -->
    <script src="<?= base_url('auth/plugins/quill/quill.min.js'); ?>" type="text/javascript"></script>

    <!-- Daterangepikcer JS -->
    <script src="<?= base_url('auth/js/moment.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('auth/plugins/daterangepicker/daterangepicker.js'); ?>" type="text/javascript"></script>

    <!-- Color Picker JS -->
    <script src="<?= base_url('auth/plugins/simonwep/pickr/pickr.es5.min.js'); ?>" type="text/javascript"></script>

    <!-- International Telephone Input JS -->
    <script src="<?= base_url('auth/plugins/intltelinput/js/intlTelInput.js'); ?>" type="text/javascript"></script>

    <!-- Custom JS -->
    <script src="<?= base_url('auth/js/theme-colorpicker.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('auth/js/script.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/theme-toggle.js'); ?>" type="text/javascript"></script>



    <?= $this->include('partials/chatbot'); ?>
    <?= $this->include('partials/cookie_consent'); ?>
    <?= $this->renderSection('scripts') ?>
    
    <!-- Mobile App Scripts & Nav -->
    <?= $this->include('partials/mobile_bottom_nav') ?>
    <script src="<?= base_url('assets/js/mobile-app.js?v=1.0'); ?>"></script>
    
    <!-- Register Service Worker -->
    <script>
        // Service Worker disabled during development - causes slow/scattered loads
        // Re-enable in production when cache is properly configured
        /*
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then((registration) => {
                    console.log('[ServiceWorker] Registration successful with scope: ', registration.scope);
                }).catch((err) => {
                    console.log('[ServiceWorker] Registration failed: ', err);
                });
            });
        }
        */
    </script>
</body>

</html>
