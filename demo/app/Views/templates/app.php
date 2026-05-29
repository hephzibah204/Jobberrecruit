<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="msapplication-TileColor" content="#0E0E0E">
    <meta name="template-color" content="#0E0E0E">
    <link rel="manifest" href="<?= base_url('manifest.json') ?>" crossorigin>
    <meta name="msapplication-config" content="<?= base_url('browserconfig.xml') ?>">
    <meta name="description" content="<?= esc($meta_description ?? 'Jobber Recruit - Job Portal Platform') ?>">
    <meta name="author" content="JobberRecruit">
    <meta name="robots" content="<?= (isset($noindex) && $noindex) ? 'noindex, nofollow' : 'index, follow' ?>">
    <link rel="canonical" href="<?= current_url(); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('images/favicon.png'); ?>">
    <link href="<?= base_url('assets/css/jobberrecruit.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/toastr.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title><?= esc($title ?? 'JobberRecruit') ?> - Job Portal Platform </title>

    <!-- Open Graph / Social Preview -->
    <meta property="og:title" content="<?= esc($og_title ?? $title ?? 'JobberRecruit — Hire Top Talent in Nigeria') ?>">
    <meta property="og:description" content="<?= esc($og_description ?? $meta_description ?? 'Find verified jobs across Nigeria on JobberRecruit. Browse thousands of opportunities in Lagos, Abuja, and more.') ?>">
    <meta property="og:type" content="<?= $og_type ?? 'website' ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:site_name" content="JobberRecruit">
    <meta property="og:image" content="<?= $og_image ?? base_url('images/default-og-image.jpg') ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($twitter_title ?? $title ?? 'JobberRecruit') ?>">
    <meta name="twitter:description" content="<?= esc($twitter_description ?? $meta_description ?? 'Find verified jobs across Nigeria on JobberRecruit.') ?>">
    <meta name="twitter:image" content="<?= $og_image ?? base_url('images/default-og-image.jpg') ?>">

    <?= $this->renderSection('styles'); ?>
</head>

<body>
    <a href="#main-content" class="visually-hidden-focusable" style="position:absolute;top:0;left:0;z-index:10000;padding:0.5rem 1rem;background:#0ea5e9;color:#fff;text-decoration:none;">Skip to main content</a>
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="text-center"><img src="<?= base_url('assets/imgs/template/loading.gif'); ?>" alt="Loading JobberRecruit"></div>
            </div>
        </div>
    </div>

    <?= $this->include('templates/header') ?>

    <main class="main" id="main-content">
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
    <?= $this->include('partials/cookie_consent'); ?>
    <?= $this->renderSection('scripts'); ?>
</body>

</html>