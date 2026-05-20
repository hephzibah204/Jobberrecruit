<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="transparent" data-width="fullwidth" data-menu-styles="transparent" data-page-style="flat" data-toggled="close" data-vertical-style="doublemenu" data-toggled="double-menu-open">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> <?= $title ?> | JobberRecruit Admin</title>
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

    <!-- Midnight Aura Theme (toggled via JS) -->
    <!-- Midnight Aura Theme -->
    <?php if (isset($activeTheme) && $activeTheme === 'midnight-aura'): ?>
        <link id="midnight-aura-css" href="<?= base_url('css/midnight-aura.css'); ?>" rel="stylesheet">
    <?php else: ?>
        <link id="midnight-aura-css" href="<?= base_url('css/midnight-aura.css'); ?>" rel="stylesheet" disabled>
    <?php endif; ?>
    <?= $this->renderSection('styles') ?>


</head>

<body>
    <div class="progress-top-bar"></div>

    <!-- Start Switcher -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="switcher-canvas" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header border-bottom d-block p-0">
            <div class="d-flex align-items-center justify-content-between p-3">
                <h5 class="offcanvas-title text-default" id="offcanvasRightLabel">Switcher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <nav class="border-top border-block-start-dashed">
                <div class="nav nav-tabs nav-justified" id="switcher-main-tab" role="tablist">
                    <button class="nav-link active" id="switcher-home-tab" data-bs-toggle="tab"
                        data-bs-target="#switcher-home" type="button" role="tab" aria-controls="switcher-home"
                        aria-selected="true">Theme Styles</button>
                    <button class="nav-link" id="switcher-profile-tab" data-bs-toggle="tab"
                        data-bs-target="#switcher-profile" type="button" role="tab" aria-controls="switcher-profile"
                        aria-selected="false">Theme Colors</button>
                </div>
            </nav>
        </div>
        <div class="offcanvas-body">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active border-0" id="switcher-home" role="tabpanel"
                    aria-labelledby="switcher-home-tab" tabindex="0">
                    <div class="">
                        <p class="switcher-style-head">Theme Color Mode:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-light-theme">
                                        Light
                                    </label>
                                    <input class="form-check-input" type="radio" name="theme-style"
                                        id="switcher-light-theme" checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-dark-theme">
                                        Dark
                                    </label>
                                    <input class="form-check-input" type="radio" name="theme-style"
                                        id="switcher-dark-theme">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Midnight Aura Theme Toggle -->
                    <div class="border-top pt-3 mt-1">
                        <p class="switcher-style-head d-flex align-items-center gap-2">
                            Midnight Aura
                            <span class="badge fs-10 fw-semibold px-2 py-1" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8);color:#fff;border-radius:50px;letter-spacing:.5px;">PREMIUM</span>
                        </p>
                        <div class="switcher-style pb-2">
                            <p class="fs-12 text-muted mb-2">Enable the deep dark glassmorphic theme across the entire admin dashboard.</p>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="switcher-midnight-aura" <?= (isset($activeTheme) && $activeTheme === 'midnight-aura') ? 'checked' : '' ?> style="width:2.5rem;height:1.25rem;cursor:pointer;">
                                    <label class="form-check-label fw-semibold" for="switcher-midnight-aura" style="cursor:pointer;">Enable Midnight Aura</label>
                                </div>
                            </div>
                            <div id="midnight-aura-preview" class="mt-3 rounded-3 p-3 d-none" style="background:linear-gradient(135deg,#020617 0%,#0f172a 50%,#1e293b 100%);border:1px solid rgba(14,165,233,.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div style="width:10px;height:10px;border-radius:50%;background:#0ea5e9;box-shadow:0 0 8px #0ea5e9;"></div>
                                    <span class="fs-11 fw-semibold" style="color:#f8fafc;">Midnight Aura Active</span>
                                </div>
                                <p class="fs-11 mb-0" style="color:#94a3b8;">Deep dark glassmorphism with vibrant blue accents and smooth animations.</p>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Directions:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-ltr">
                                        LTR
                                    </label>
                                    <input class="form-check-input" type="radio" name="direction" id="switcher-ltr" checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-rtl">
                                        RTL
                                    </label>
                                    <input class="form-check-input" type="radio" name="direction" id="switcher-rtl">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Navigation Styles:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-vertical">
                                        Vertical
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-style"
                                        id="switcher-vertical" checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-horizontal">
                                        Horizontal
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-style"
                                        id="switcher-horizontal">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="navigation-menu-styles">
                        <p class="switcher-style-head">Vertical & Horizontal Menu Styles:</p>
                        <div class="row switcher-style gx-0 pb-2 gy-2">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-click">
                                        Menu Click
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-menu-click">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-hover">
                                        Menu Hover
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-menu-hover">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icon-click">
                                        Icon Click
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-icon-click">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icon-hover">
                                        Icon Hover
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-icon-hover">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidemenu-layout-styles">
                        <p class="switcher-style-head">Sidemenu Layout Styles:</p>
                        <div class="row switcher-style gx-0 pb-2 gy-2">
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-default-menu">
                                        Default Menu
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-default-menu">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-closed-menu">
                                        Closed Menu
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-closed-menu">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icontext-menu">
                                        Icon Text
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-icontext-menu">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icon-overlay">
                                        Icon Overlay
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-icon-overlay">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-detached">
                                        Detached
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-detached">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-double-menu">
                                        Double Menu
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-double-menu" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Page Styles:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-xl-3 col-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-regular">
                                        Regular
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-regular">
                                </div>
                            </div>
                            <div class="col-xl-3 col-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-classic">
                                        Classic
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-classic">
                                </div>
                            </div>
                            <div class="col-xl-3 col-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-modern">
                                        Modern
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-modern">
                                </div>
                            </div>
                            <div class="col-xl-3 col-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-flat">
                                        Flat
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-flat" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Layout Width Styles:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-default-width">
                                        Default
                                    </label>
                                    <input class="form-check-input" type="radio" name="layout-width"
                                        id="switcher-default-width">
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-full-width">
                                        Full Width
                                    </label>
                                    <input class="form-check-input" type="radio" name="layout-width"
                                        id="switcher-full-width" checked>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-boxed">
                                        Boxed
                                    </label>
                                    <input class="form-check-input" type="radio" name="layout-width" id="switcher-boxed">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Menu Positions:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-fixed">
                                        Fixed
                                    </label>
                                    <input class="form-check-input" type="radio" name="menu-positions"
                                        id="switcher-menu-fixed" checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-scroll">
                                        Scrollable
                                    </label>
                                    <input class="form-check-input" type="radio" name="menu-positions"
                                        id="switcher-menu-scroll">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Header Positions:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-header-fixed">
                                        Fixed
                                    </label>
                                    <input class="form-check-input" type="radio" name="header-positions"
                                        id="switcher-header-fixed" checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-header-scroll">
                                        Scrollable
                                    </label>
                                    <input class="form-check-input" type="radio" name="header-positions"
                                        id="switcher-header-scroll">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Loader:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-loader-enable">
                                        Enable
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-loader"
                                        id="switcher-loader-enable">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-loader-disable">
                                        Disable
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-loader"
                                        id="switcher-loader-disable" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade border-0" id="switcher-profile" role="tabpanel"
                    aria-labelledby="switcher-profile-tab" tabindex="0">
                    <div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Menu Colors:</p>
                            <div class="d-flex switcher-style pb-2">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Light Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-light" checked>
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Dark Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-dark">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Color Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Gradient Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-gradient">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-transparent" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Transparent Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-transparent">
                                </div>
                            </div>
                            <div class="px-4 pb-3 text-muted fs-11">Note:If you want to change color Menu dynamically change
                                from below Theme Primary color picker</div>
                        </div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Header Colors:</p>
                            <div class="d-flex switcher-style pb-2">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Light Header" type="radio" name="header-colors"
                                        id="switcher-header-light">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Dark Header" type="radio" name="header-colors"
                                        id="switcher-header-dark">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Color Header" type="radio" name="header-colors"
                                        id="switcher-header-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Gradient Header" type="radio" name="header-colors"
                                        id="switcher-header-gradient">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-transparent" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Transparent Header" type="radio" name="header-colors"
                                        id="switcher-header-transparent" checked>
                                </div>
                            </div>
                            <div class="px-4 pb-3 text-muted fs-11">Note:If you want to change color Header dynamically
                                change from below Theme Primary color picker</div>
                        </div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Theme Primary:</p>
                            <div class="d-flex flex-wrap align-items-center switcher-style">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-1" type="radio"
                                        name="theme-primary" id="switcher-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-2" type="radio"
                                        name="theme-primary" id="switcher-primary1">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-3" type="radio"
                                        name="theme-primary" id="switcher-primary2">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-4" type="radio"
                                        name="theme-primary" id="switcher-primary3">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-5" type="radio"
                                        name="theme-primary" id="switcher-primary4">
                                </div>
                                <div class="form-check switch-select ps-0 mt-1 color-primary-light">
                                    <div class="theme-container-primary"></div>
                                    <div class="pickr-container-primary" onchange="updateChartColor(this.value)"></div>
                                </div>
                            </div>
                        </div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Theme Background:</p>
                            <div class="d-flex flex-wrap align-items-center switcher-style">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-1" type="radio"
                                        name="theme-background" id="switcher-background">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-2" type="radio"
                                        name="theme-background" id="switcher-background1">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-3" type="radio"
                                        name="theme-background" id="switcher-background2">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-4" type="radio"
                                        name="theme-background" id="switcher-background3">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-5" type="radio"
                                        name="theme-background" id="switcher-background4">
                                </div>
                                <div class="form-check switch-select ps-0 mt-1 tooltip-static-demo color-bg-transparent">
                                    <div class="theme-container-background"></div>
                                    <div class="pickr-container-background"></div>
                                </div>
                            </div>
                        </div>
                        <div class="menu-image mb-3">
                            <p class="switcher-style-head">Menu With Background Image:</p>
                            <div class="d-flex flex-wrap align-items-center switcher-style">
                                <div class="form-check switch-select menu-img-select m-2">
                                    <input class="form-check-input bgimage-input bg-img1" type="radio"
                                        name="menu-background" id="switcher-bg-img">
                                    <div class="bg-img-container">
                                        <img src="../assets/images/menu-bg-images/bg-img1.jpg" alt="">
                                    </div>
                                </div>
                                <div class="form-check switch-select menu-img-select m-2">
                                    <input class="form-check-input bgimage-input bg-img2" type="radio"
                                        name="menu-background" id="switcher-bg-img1">
                                    <div class="bg-img-container">
                                        <img src="../assets/images/menu-bg-images/bg-img2.jpg" alt="">
                                    </div>
                                </div>
                                <div class="form-check switch-select menu-img-select m-2">
                                    <input class="form-check-input bgimage-input bg-img3" type="radio" name="menu-background"
                                        id="switcher-bg-img2">
                                    <div class="bg-img-container">
                                        <img src="../assets/images/menu-bg-images/bg-img3.jpg" alt="">
                                    </div>
                                </div>
                                <div class="form-check switch-select menu-img-select m-2">
                                    <input class="form-check-input bgimage-input bg-img4" type="radio"
                                        name="menu-background" id="switcher-bg-img3">
                                    <div class="bg-img-container">
                                        <img src="../assets/images/menu-bg-images/bg-img4.jpg" alt="">
                                    </div>
                                </div>
                                <div class="form-check switch-select menu-img-select m-2">
                                    <input class="form-check-input bgimage-input bg-img5" type="radio"
                                        name="menu-background" id="switcher-bg-img4">
                                    <div class="bg-img-container">
                                        <img src="../assets/images/menu-bg-images/bg-img5.jpg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center canvas-footer flex-wrap">
                    <a href="javascript:void(0);" id="reset-all" class="btn btn-danger w-100">Reset</a>
                </div>
            </div>
        </div>
    </div>
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
            <?= $this->renderSection('section') ?>
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
                    alert('Failed to toggle theme');
                    toggle.checked = !toggle.checked; // Revert
                    toggle.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error while toggling theme');
                toggle.checked = !toggle.checked; // Revert
                toggle.disabled = false;
            });
        });
    })();
    </script>

    <?= $this->include('partials/chatbot'); ?>
    <?= $this->renderSection('scripts') ?>

</body>

</html>
