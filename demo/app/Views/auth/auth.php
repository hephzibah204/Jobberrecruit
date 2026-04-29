<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Prevent search engines from indexing login/register pages -->
    <meta name="robots" content="noindex, nofollow">

    <!-- Dynamic Page Title -->
    <title><?= $title ?? 'Account Access – JobberRecruit'; ?></title>

    <!-- Description (Minimal SEO because auth pages shouldn’t rank) -->
    <meta name="description" content="<?= $meta_description ?? 'Securely access your JobberRecruit account to apply for jobs or manage your recruiter dashboard.'; ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= current_url(); ?>">

    <!-- Branding -->
    <meta name="theme-color" content="#0D609E">
    <meta name="application-name" content="JobberRecruit">

    <!-- Favicons -->
    <link rel="shortcut icon" href="<?= base_url('assets/imgs/template/favicon.png'); ?>" type="image/png">

    <!-- Social Preview (minimal only) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $title ?? 'JobberRecruit – Account Login'; ?>">
    <meta property="og:description" content="<?= $meta_description ?? 'Login securely to JobberRecruit.'; ?>">
    <meta property="og:image" content="<?= base_url('assets/imgs/template/og-image-auth.png'); ?>">
    <meta property="og:url" content="<?= current_url(); ?>">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?= $title ?? 'JobberRecruit Account Access'; ?>">
    <meta name="twitter:description" content="<?= $meta_description ?? 'Access your JobberRecruit account.'; ?>">

    <!-- Styles -->
    <link href="<?= base_url('assets/css/jobberrecruit.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/toastr.min.css'); ?>" rel="stylesheet">

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