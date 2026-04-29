<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="msapplication-TileColor" content="#0E0E0E">
    <meta name="template-color" content="#0E0E0E">
    <link rel="manifest" href="manifest.html" crossorigin>
    <meta name="msapplication-config" content="browserconfig.html">
    <meta name="description" content="Jobber Recruit - Job Portal Platform">
    <meta name="author" content="BITBIZ NIG LIMITED">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/imgs/template/favicon.png'); ?>">
    <link href="<?= base_url('assets/css/jobberrecruit.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/toastr.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="<?= base_url('css/midnight-aura.css'); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title><?= $title ?? 'JobberRecruit' ?> - Job Portal Platform </title>
    <?= $this->renderSection('styles'); ?>
</head>

<body>
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="text-center"><img src="<?= base_url('assets/imgs/template/loading.gif'); ?>" alt="jobberRecruit"></div>
            </div>
        </div>
    </div>

    <?= $this->include('templates/header') ?>

    <main class="main">
        <div class="bg-homepage4"></div>
        <?= $this->renderSection('content'); ?>
    </main>

    <?= $this->include('templates/footer'); ?>

    <script src="<?= base_url('assets/js/vendor/modernizr-3.6.0.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/vendor/jquery-3.6.0.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/vendor/jquery-migrate-3.3.0.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/vendor/bootstrap.bundle.min.js'); ?>"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="<?= base_url('assets/js/toastr.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/waypoints.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/wow.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/magnific-popup.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/perfect-scrollbar.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/select2.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/isotope.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/scrollup.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/swiper-bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/main8c94.js?v=4.1'); ?>"></script>
    <?= $this->renderSection('scripts'); ?>
</body>

</html>