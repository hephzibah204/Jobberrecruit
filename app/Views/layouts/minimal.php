<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SECURITY SEO: Prevent Indexing of Dashboard Pages -->
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">

    <!-- Page Title -->
    <title><?= $title ?? 'Interview Session' ?> - JobberRecruit</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('auth/img/favicon.png'); ?>" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('auth/img/apple-touch-icon.png'); ?>">

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
    <link rel="stylesheet" href="<?= base_url('auth/plugins/fontawesome/css/fontawesome.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/fontawesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/daterangepicker/daterangepicker.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/tabler-icons/tabler-icons.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/jvectormap/jquery-jvectormap-2.0.5.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/simonwep/pickr/themes/nano.min.css'); ?>">

    <!-- Main Theme -->
    <link rel="stylesheet" href="<?= base_url('auth/css/style.css'); ?>">
    <?php if (isset($activeTheme) && $activeTheme === 'midnight-aura'): ?>
        <link id="midnight-aura-css" href="<?= base_url('css/midnight-aura.css'); ?>" rel="stylesheet">
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <?= $this->renderSection('styles') ?>

</head>

<body class="<?= $bodyClass ?? '' ?>">
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>
    
    <div class="main-wrapper d-flex flex-column" style="min-height: 100vh;">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- jQuery -->
    <script src="<?= base_url('auth/js/jquery-3.7.1.min.js'); ?>" type="text/javascript"></script>

    <!-- Feather Icon JS -->
    <script src="<?= base_url('auth/js/feather.min.js'); ?>" type="text/javascript"></script>

    <!-- Slimscroll JS -->
    <script src="<?= base_url('auth/js/jquery.slimscroll.min.js'); ?>" type="text/javascript"></script>

    <!-- Bootstrap Core JS -->
    <script src="<?= base_url('auth/js/bootstrap.bundle.min.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('auth/js/toastr.min.js'); ?>" type="text/javascript"></script>

    <!-- Custom JS -->
    <script src="<?= base_url('auth/js/theme-colorpicker.js'); ?>" type="text/javascript"></script>
    <script src="<?= base_url('auth/js/script.js'); ?>" type="text/javascript"></script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>
