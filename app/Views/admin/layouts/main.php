<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="transparent" data-width="fullwidth" data-menu-styles="transparent" data-page-style="flat">

<head>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('jr-theme');
            const preferDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (preferDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= esc($title ?? 'Dashboard') ?> | JobberRecruit Admin</title>
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin dashboard html,admin html template,admin panel bootstrap template,admin panel html template,admin template html,bootstrap admin panel,bootstrap html template,bootstrap template,bootstrap with html,dashboard html template,dashboards ui,html admin dashboard,html bootstrap,html dashboard template,html template">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('images/favicon.png'); ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= base_url('images/favicon.png'); ?>">

    <!-- Bootstrap Css -->
    <link id="style" href="<?= base_url('admin/libs/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Toastr -->
    <link href="<?= base_url('admin/css/toastr.min.css'); ?>" rel="stylesheet">

    <!-- Icons Css -->
    <link href="<?= base_url('admin/css/icons.css'); ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('auth/css/feather.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/quill/quill.snow.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/select2/css/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('auth/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css'); ?>">

    <!-- Node Waves Css -->
    <link href="<?= base_url('admin/libs/node-waves/waves.min.css'); ?>" rel="stylesheet">

    <!-- Simplebar Css -->
    <link href="<?= base_url('admin/libs/simplebar/simplebar.min.css'); ?>" rel="stylesheet">

    <!-- Color Picker Css -->
    <link rel="stylesheet" href="<?= base_url('admin/libs/flatpickr/flatpickr.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('admin/libs/%40simonwep/pickr/themes/nano.min.css'); ?>">

    <!-- Choices Css -->
    <link rel="stylesheet" href="<?= base_url('admin/libs/choices.js/public/assets/styles/choices.min.css'); ?>">

    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="<?= base_url('admin/libs/flatpickr/flatpickr.min.css'); ?>">

    <!-- Auto Complete CSS -->
    <link rel="stylesheet" href="<?= base_url('admin/libs/%40tarekraafat/autocomplete.js/css/autoComplete.css'); ?>">

    <!-- Style Css -->
    <link href="<?= base_url('admin/css/styles.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/global-core.css'); ?>">

    <!-- Midnight Aura Theme (toggled via JS) -->
    <!-- Midnight Aura Theme -->
    <?php if (isset($activeTheme) && $activeTheme === 'midnight-aura'): ?>
        <link id="midnight-aura-css" href="<?= base_url('css/midnight-aura.css'); ?>" rel="stylesheet">
    <?php else: ?>
        <link id="midnight-aura-css" href="<?= base_url('css/midnight-aura.css'); ?>" rel="stylesheet" disabled>
    <?php endif; ?>
    <!-- Brand Styles Overlay -->
    <link href="<?= base_url('admin/css/admin-brand.css'); ?>" rel="stylesheet">
    <?= $this->renderSection('styles') ?>


</head>

<body>
    <?= $this->include('partials/svg_sprites') ?>
    <div class="progress-top-bar"></div>

    <!-- Start Switcher -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="switcher-canvas" aria-labelledby="offcanvasRightLabel" style="display:none !important;"></div>
    <!-- End Switcher -->

    <!-- Loader -->
    <div id="loader">
        <img src="<?= base_url('assets/imgs/template/logo.png'); ?>" alt="">
    </div>
    <!-- Loader -->

    <div class="page">
        <!-- Header -->
        <?= $this->include('admin/layouts/header') ?>

        <!-- Sidebar -->
        <?= $this->include('admin/layouts/sidebar') ?>

        <!-- Content -->
        <div class="main-content app-content">
            <?= $this->renderSection('content') ?>
        </div>

        <!-- Footer -->
        <?= $this->include('admin/layouts/footer') ?>
    </div>


    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow lh-1"><i class="ti ti-arrow-big-up fs-18"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <!-- Scroll To Top -->

    <!-- jQuery -->
    <script src="<?= base_url('auth/js/jquery-3.7.1.min.js'); ?>" type="text/javascript"></script>
    <!-- Popper JS -->
    <script src="<?= base_url('admin/libs/%40popperjs/core/umd/popper.min.js'); ?>"></script>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('admin/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- Choices JS -->
    <script src="<?= base_url('admin/libs/choices.js/public/assets/scripts/choices.min.js'); ?>"></script>

    <!-- Toastr -->
    <script src="<?= base_url('admin/js/toastr.min.js'); ?>"></script>

    <!-- Defaultmenu JS -->
    <script src="<?= base_url('admin/js/defaultmenu.min.js'); ?>"></script>

    <!-- Node Waves JS-->
    <script src="<?= base_url('admin/libs/node-waves/waves.min.js'); ?>"></script>

    <!-- Sticky JS -->
    <script src="<?= base_url('admin/js/sticky.js'); ?>"></script>

    <!-- Simplebar JS -->
    <script src="<?= base_url('admin/libs/simplebar/simplebar.min.js'); ?>"></script>
    <script src="<?= base_url('admin/js/simplebar.js'); ?>"></script>

    <!-- Auto Complete JS -->
    <script src="<?= base_url('admin/libs/%40tarekraafat/autocomplete.js/autoComplete.min.js'); ?>"></script>

    <!-- Color Picker JS -->
    <script src="<?= base_url('admin/libs/%40simonwep/pickr/pickr.es5.min.js'); ?>"></script>

    <!-- Date & Time Picker JS -->
    <script src="<?= base_url('admin/libs/flatpickr/flatpickr.min.js'); ?>"></script>


    <!-- Apex Charts JS -->
    <script src="<?= base_url('admin/libs/apexcharts/apexcharts.min.js'); ?>"></script>

    <script src="<?= base_url('admin/libs/nouislider/nouislider.min.js'); ?>"></script>
    <script src="<?= base_url('admin/libs/wnumb/wNumb.min.js'); ?>"></script>

    <!-- Select2 JS -->
    <script src="<?= base_url('auth/plugins/select2/js/select2.min.js'); ?>" type="text/javascript"></script>

    <!-- Quill JS -->
    <script src="<?= base_url('auth/plugins/quill/quill.min.js'); ?>" type="text/javascript"></script>

    <!-- Candidate -->
    <script src="<?= base_url('admin/js/job-search-candidate.js'); ?>"></script>

    <!-- Main Theme Js -->
    <script src="<?= base_url('admin/js/main.js'); ?>"></script>

    <!-- Custom JS -->
    <script src="<?= base_url('admin/js/custom.js'); ?>"></script>

    <!-- Custom-Switcher JS -->
    <script src="<?= base_url('admin/js/custom-switcher.min.js'); ?>"></script>

    <!-- Global Midnight Aura Theme Switcher Logic -->
    <script>
    (function () {
        const toggle = document.getElementById('switcher-midnight-aura');
        
        toggle.addEventListener('change', function () {
            // Disable toggle temporarily to prevent double clicks
            toggle.disabled = true;
            
            fetch('<?= base_url('admin/theme/toggle') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>' // Might need CSRF if enabled, but fetch API works best here. In CI4, maybe we need it in body or header.
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to apply the global theme to all components and avoid partial state
                    window.location.reload();
                } else {
                    toastr.error('Failed to toggle theme');
                    toggle.checked = !toggle.checked; // Revert
                    toggle.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Network error while toggling theme');
                toggle.checked = !toggle.checked; // Revert
                toggle.disabled = false;
            });
        });
    })();
    </script>

    <?= $this->include('partials/chatbot'); ?>
    <?= $this->renderSection('scripts') ?>

    <script src="<?= base_url('js/theme-toggle.js'); ?>" type="text/javascript"></script>
</body>

</html>
