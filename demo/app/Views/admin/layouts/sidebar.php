<?php
$uri = trim(uri_string(), '/');

function isExact($path)
{
    return trim($path, '/') === trim(uri_string(), '/') ? 'active' : '';
}

function isStartsWith($path)
{
    return str_starts_with(trim(uri_string(), '/'), trim($path, '/'));
}

function openIf(array $paths)
{
    foreach ($paths as $path) {
        if (isStartsWith($path)) {
            return 'active open';
        }
    }
    return '';
}
?>

<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">

    <div class="main-sidebar-header">
        <a href="<?= base_url('admin/dashboard') ?>" class="header-logo">
            <img src="<?= base_url('assets/imgs/template/logo.png'); ?>" class="desktop-logo">
            <img src="<?= base_url('images/favicon.png'); ?>" class="toggle-dark">
            <img src="<?= base_url('images/favicon.png'); ?>" class="desktop-dark">
            <img src="<?= base_url('images/favicon.png'); ?>" class="toggle-logo">
        </a>
    </div>

    <div class="main-sidebar" id="sidebar-scroll">
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">

                <li class="slide__category">
                    <span class="category-name">Main</span>
                </li>

                <!-- DASHBOARDS -->
                <li class="slide has-sub <?= openIf([
                                                'admin/dashboard',
                                                'admin/locations',
                                                'admin/categories',
                                                'admin/industries',
                                                'admin/jobs',
                                                'admin/candidates',
                                                'admin/employers',
                                                'admin/applications',
                                                'admin/plans'
                                            ]) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf([
                                                                                'admin/dashboard',
                                                                                'admin/locations',
                                                                                'admin/categories',
                                                                                'admin/industries',
                                                                                'admin/jobs',
                                                                                'admin/candidates',
                                                                                'admin/employers',
                                                                                'admin/applications',
                                                                                'admin/plans'
                                                                            ]) ?>">
                        <!-- ICON PRESERVED -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <path d="M133.66,34.34a8,8,0,0,0-11.32,0L40,116.69V216h64V152h48v64h64V116.69Z" opacity="0.2" />
                        </svg>
                        <span class="side-menu__label">Dashboards</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>

                    <ul class="slide-menu child1 double-menu-active">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Dashboards</a>
                        </li>

                        <!-- JOBS -->
                        <li class="slide has-sub <?= openIf([
                                                        'admin/dashboard',
                                                        'admin/locations',
                                                        'admin/categories',
                                                        'admin/industries',
                                                        'admin/jobs',
                                                        'admin/candidates',
                                                        'admin/employers',
                                                        'admin/applications'
                                                    ]) ?>">
                            <a href="javascript:void(0);" class="side-menu__item <?= openIf([
                                                                                        'admin/dashboard',
                                                                                        'admin/locations',
                                                                                        'admin/categories',
                                                                                        'admin/industries',
                                                                                        'admin/jobs',
                                                                                        'admin/candidates',
                                                                                        'admin/employers',
                                                                                        'admin/applications'
                                                                                    ]) ?>">
                                <!-- ICON PRESERVED -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <path d="M128,144a191.14,191.14,0,0,1-96-25.68V200a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V118.31Z" opacity="0.2" />
                                </svg>
                                Main
                                <i class="ri-arrow-right-s-line side-menu__angle"></i>
                            </a>

                            <ul class="slide-menu child2">
                                <li class="slide <?= isExact('admin/dashboard') ?>">
                                    <a href="<?= base_url('admin/dashboard') ?>" class="side-menu__item <?= isExact('admin/dashboard') ?>">
                                        Dashboard
                                    </a>
                                </li>

                                <li class="slide <?= isExact('admin/locations') ?>">
                                    <a href="<?= base_url('admin/locations') ?>" class="side-menu__item <?= isExact('admin/locations') ?>">
                                        Locations
                                    </a>
                                </li>

                                <li class="slide <?= isExact('admin/categories') ?>">
                                    <a href="<?= base_url('admin/categories') ?>" class="side-menu__item <?= isExact('admin/categories') ?>">
                                        Categories
                                    </a>
                                </li>

                                <li class="slide <?= isExact('admin/industries') ?>">
                                    <a href="<?= base_url('admin/industries') ?>" class="side-menu__item <?= isExact('admin/industries') ?>">
                                        Industries
                                    </a>
                                </li>

                                <li class="slide <?= isStartsWith('admin/jobs') ? 'active' : '' ?>">
                                    <a href="<?= base_url('admin/jobs') ?>" class="side-menu__item <?= isStartsWith('admin/jobs') ? 'active' : '' ?>">
                                        Jobs
                                    </a>
                                </li>

                                <li class="slide <?= isExact('admin/candidates') ?>">
                                    <a href="<?= base_url('admin/candidates') ?>" class="side-menu__item <?= isExact('admin/candidates') ?>">
                                        Candidates
                                    </a>
                                </li>

                                <li class="slide <?= isExact('admin/employers') ?>">
                                    <a href="<?= base_url('admin/employers') ?>" class="side-menu__item <?= isExact('admin/employers') ?>">
                                        Employers
                                    </a>
                                </li>

                                <li class="slide <?= isExact('admin/applications') ?>">
                                    <a href="<?= base_url('admin/applications') ?>" class="side-menu__item <?= isExact('admin/applications') ?>">
                                        Applications
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- SUBSCRIPTION PLANS -->
                        <li class="slide <?= isExact('admin/plans') ?>">
                            <a href="<?= base_url('admin/plans') ?>" class="side-menu__item <?= isExact('admin/plans') ?>">
                                <!-- ICON PRESERVED -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <circle cx="128" cy="128" r="96" opacity="0.2" />
                                </svg>
                                Subscription Plans
                            </a>
                        </li>

                        <!-- AFFILIATE PROGRAM -->
                        <li class="slide <?= isExact('admin/affiliate/settings') ?>">
                            <a href="<?= base_url('admin/affiliate/settings') ?>" class="side-menu__item <?= isExact('admin/affiliate/settings') ?>">
                                <!-- ICON PRESERVED -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path d="M128,128,80,224l48-32,48,32Z" opacity="0.2" />
                                </svg>
                                Affiliate Program
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- CONTENTS -->
                <!-- Start::slide__category -->
                <li class="slide__category">
                    <span class="category-name">Contents</span>
                </li>
                <!-- End::slide__category -->

                <li class="slide has-sub <?= openIf([
                                                'admin/blog',
                                                'admin/testimonials',
                                                'admin/team',
                                                'admin/terms',
                                                'admin/privacy'
                                            ]) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf([
                                                                                'admin/blog',
                                                                                'admin/testimonials',
                                                                                'admin/team',
                                                                                'admin/terms',
                                                                                'admin/privacy'
                                                                            ]) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <polygon points="152 32 152 88 208 88 152 32" opacity="0.2" />
                            <path d="M200,224H56a8,8,0,0,1-8-8V40a8,8,0,0,1,8-8h96l56,56V216A8,8,0,0,1,200,224Z"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <polyline points="152 32 152 88 208 88"
                                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Contents</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>

                    <ul class="slide-menu child1 pages-ul">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Contents</a>
                        </li>

                        <!-- Blog -->
                        <li class="slide <?= isExact('admin/blogs') ?>">
                            <a href="<?= base_url('admin/blogs') ?>" class="side-menu__item <?= isExact('admin/blogs') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path d="M32,96H224V56a8,8,0,0,0-8-8H40a8,8,0,0,0-8,8Z" opacity="0.2" />
                                    <rect x="32" y="48" width="192" height="160" rx="8"
                                        fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="16" />
                                    <line x1="32" y1="96" x2="224" y2="96"
                                        fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="16" />
                                </svg>
                                Blog
                            </a>
                        </li>

                        <!-- Testimonials -->
                        <li class="slide <?= isExact('admin/testimonials') ?>">
                            <a href="<?= base_url('admin/testimonials') ?>"
                                class="side-menu__item <?= isExact('admin/testimonials') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path d="M105.07,192l16,28a8,8,0,0,0,13.9,0l16-28H216a8,8,0,0,0,8-8V56a8,8,0,0,0-8-8H40a8,8,0,0,0-8,8V184a8,8,0,0,0,8,8Z"
                                        opacity="0.2" />
                                    <line x1="96" y1="104" x2="160" y2="104"
                                        fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="16" />
                                    <line x1="96" y1="136" x2="160" y2="136"
                                        fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="16" />
                                </svg>
                                Testimonials
                            </a>
                        </li>

                        <!-- Newsletters -->
                        <li class="slide <?= isExact('admin/newsletters') ?>">
                            <a href="<?= base_url('admin/newsletters') ?>"
                                class="side-menu__item <?= isExact('admin/newsletters') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path d="M224,96H32a8,8,0,0,1-8-8V56a8,8,0,0,1,8-8H224a8,8,0,0,1,8,8V88A8,8,0,0,1,224,96Z" opacity="0.2" />
                                    <path d="M224,96H32a8,8,0,0,1-8-8V56a8,8,0,0,1,8-8H224a8,8,0,0,1,8,8V88A8,8,0,0,1,224,96Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <path d="M32,96V200a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <polyline points="32 96 128 152 224 96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                </svg>
                                Newsletters
                            </a>
                        </li>

                        <!-- Fraud Reports -->
                        <li class="slide <?= isExact('admin/reports') ?>">
                            <a href="<?= base_url('admin/reports') ?>"
                                class="side-menu__item <?= isExact('admin/reports') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path d="M128,24,40,72v56a104,104,0,0,0,88,102.86A104,104,0,0,0,216,128V72Z" opacity="0.2" />
                                    <path d="M128,24,40,72v56a104,104,0,0,0,88,102.86A104,104,0,0,0,216,128V72Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <line x1="128" y1="80" x2="128" y2="136" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <circle cx="128" cy="172" r="12" fill="currentColor" />
                                </svg>
                                Fraud Reports
                            </a>
                        </li>

                        <!-- E-Learning -->
                        <li class="slide <?= isExact('admin/elearning') ?>">
                            <a href="<?= base_url('admin/elearning') ?>"
                                class="side-menu__item <?= isExact('admin/elearning') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path d="M216,40H40a8,8,0,0,0-8,8V192a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V48A8,8,0,0,0,216,40Z" opacity="0.2" />
                                    <path d="M216,40H40a8,8,0,0,0-8,8V192a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V48A8,8,0,0,0,216,40Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <line x1="32" y1="160" x2="224" y2="160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <path d="M112,160a32,32,0,0,0,32,32" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <line x1="80" y1="40" x2="80" y2="160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <line x1="176" y1="40" x2="176" y2="160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                </svg>
                                E-Learning
                            </a>
                        </li>

                        <!-- Team -->
                        <!-- <li class="slide <?= isExact('admin/team') ?>">
                            <a href="<?= base_url('admin/team') ?>" class="side-menu__item <?= isExact('admin/team') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <circle cx="128" cy="144" r="40" opacity="0.2" />
                                    <circle cx="64" cy="88" r="32" opacity="0.2" />
                                    <circle cx="192" cy="88" r="32" opacity="0.2" />
                                    <path d="M72,216a65,65,0,0,1,112,0"
                                        fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="16" />
                                </svg>
                                Team
                            </a>
                        </li> -->

                    </ul>
                </li>

                <li>
                    <ul class="slide-menu child1 doublemenu_slide-menu">
                        <li class="text-center p-3 text-fixed-white">
                            <div class="doublemenu_slide-menu-background">
                                <img src="<?= base_url('admin/images/media/backgrounds/13.png') ?>" alt="">
                            </div>
                            <div class="d-flex flex-column align-items-center justify-content-between h-100">
                                <div class="fs-15 fw-medium">Extra Features?</div>
                                <div>
                                    <span class="avatar avatar-lg p-1">
                                        <img src="<?= base_url('admin/images/bitbiz.png') ?>" alt="">
                                        <span class="top-right"></span>
                                        <span class="bottom-right"></span>
                                    </span>
                                </div>
                                <div class="d-grid w-100">
                                    <a href="https://bitbiz.ng" class="btn btn-light border-0">Reach Out</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="doublemenu_bottom-menu main-menu mb-0 border-top">
                <!-- Start::slide -->
                <li class="slide">
                    <a href="javascript:void(0);" class="side-menu__item layout-setting-doublemenu">
                        <span class="light-layout">
                            <!-- Start::header-link-icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                                <rect width="256" height="256" fill="none" />
                                <path d="M108.11,28.11A96.09,96.09,0,0,0,227.89,147.89,96,96,0,1,1,108.11,28.11Z" opacity="0.2" />
                                <path d="M108.11,28.11A96.09,96.09,0,0,0,227.89,147.89,96,96,0,1,1,108.11,28.11Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            </svg>
                            <!-- End::header-link-icon -->
                        </span>
                        <span class="dark-layout">
                            <!-- Start::header-link-icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                                <rect width="256" height="256" fill="none" />
                                <circle cx="128" cy="128" r="56" opacity="0.2" />
                                <line x1="128" y1="40" x2="128" y2="32" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                <circle cx="128" cy="128" r="56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                <line x1="64" y1="64" x2="56" y2="56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                <line x1="64" y1="192" x2="56" y2="200" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                <line x1="192" y1="64" x2="200" y2="56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                <line x1="192" y1="192" x2="200" y2="200" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                <line x1="40" y1="128" x2="32" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                <line x1="128" y1="216" x2="128" y2="224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                <line x1="216" y1="128" x2="224" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            </svg>
                            <!-- End::header-link-icon -->
                        </span>
                        <span class="side-menu__label">Theme Settings</span>
                    </a>
                </li>
                <!-- End::slide -->
                <!-- Start::slide -->
                <li class="slide">
                    <a href="<?= base_url('admin/profile') ?>" class="side-menu__item p-1 rounded-circle mb-0">
                        <span class="avatar avatar-md avatar-rounded">
                            <img src="<?= base_url('images/favicon.png') ?>" alt="">
                        </span>
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="<?= base_url('admin/logout') ?>" class="side-menu__item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <path d="M48,40H208a16,16,0,0,1,16,16V200a16,16,0,0,1-16,16H48a0,0,0,0,1,0,0V40A0,0,0,0,1,48,40Z" opacity="0.2" />
                            <polyline points="112 40 48 40 48 216 112 216" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <line x1="112" y1="128" x2="224" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <polyline points="184 88 224 128 184 168" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Logout</span>
                    </a>
                </li>
                <!-- End::slide -->
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav>
    </div>
</aside>
<!-- End::app-sidebar -->