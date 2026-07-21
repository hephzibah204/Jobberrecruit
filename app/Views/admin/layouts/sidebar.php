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

                <!-- OVERVIEW -->
                <li class="slide__category">
                    <span class="category-name">Overview</span>
                </li>
                <li class="slide <?= isExact('admin/dashboard') ?>">
                    <a href="<?= base_url('admin/dashboard') ?>" class="side-menu__item <?= isExact('admin/dashboard') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <path d="M128,24,24,104v112a8,8,0,0,0,8,8H96V144h64v80h64a8,8,0,0,0,8-8V104Z" opacity="0.2" />
                            <path d="M128,24,24,104v112a8,8,0,0,0,8,8H96V144h64v80h64a8,8,0,0,0,8-8V104Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                <!-- PLATFORM -->
                <li class="slide__category">
                    <span class="category-name">Platform</span>
                </li>
                <li class="slide has-sub <?= openIf(['admin/jobs', 'admin/candidates', 'admin/employers', 'admin/applications', 'admin/users']) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/jobs', 'admin/candidates', 'admin/employers', 'admin/applications', 'admin/users']) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <circle cx="128" cy="128" r="96" opacity="0.2" />
                            <circle cx="128" cy="128" r="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <path d="M64,152a64,64,0,0,1,128,0" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <circle cx="128" cy="96" r="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Platform</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Platform</a>
                        </li>
                        <li class="slide <?= isStartsWith('admin/jobs') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/jobs') ?>" class="side-menu__item <?= isStartsWith('admin/jobs') ? 'active' : '' ?>">Jobs</a>
                        </li>
                        <li class="slide <?= isExact('admin/candidates') ?>">
                            <a href="<?= base_url('admin/candidates') ?>" class="side-menu__item <?= isExact('admin/candidates') ?>">Candidates</a>
                        </li>
                        <li class="slide <?= isExact('admin/employers') ?>">
                            <a href="<?= base_url('admin/employers') ?>" class="side-menu__item <?= isExact('admin/employers') ?>">Employers</a>
                        </li>
                        <li class="slide <?= isExact('admin/applications') ?>">
                            <a href="<?= base_url('admin/applications') ?>" class="side-menu__item <?= isExact('admin/applications') ?>">Applications</a>
                        </li>
                        <li class="slide <?= isExact('admin/users') ?>">
                            <a href="<?= base_url('admin/users') ?>" class="side-menu__item <?= isExact('admin/users') ?>">Users</a>
                        </li>
                    </ul>
                </li>

                <!-- TAXONOMY -->
                <li class="slide__category">
                    <span class="category-name">Taxonomy</span>
                </li>
                <li class="slide has-sub <?= openIf(['admin/categories', 'admin/industries', 'admin/locations', 'admin/qualifications']) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/categories', 'admin/industries', 'admin/locations', 'admin/qualifications']) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <circle cx="64" cy="64" r="24" opacity="0.2" />
                            <circle cx="192" cy="192" r="24" opacity="0.2" />
                            <circle cx="64" cy="64" r="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <circle cx="192" cy="192" r="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <circle cx="192" cy="64" r="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <line x1="88" y1="64" x2="168" y2="64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <line x1="192" y1="88" x2="192" y2="168" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Taxonomy</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Taxonomy</a>
                        </li>
                        <li class="slide <?= isExact('admin/categories') ?>">
                            <a href="<?= base_url('admin/categories') ?>" class="side-menu__item <?= isExact('admin/categories') ?>">Categories</a>
                        </li>
                        <li class="slide <?= isExact('admin/industries') ?>">
                            <a href="<?= base_url('admin/industries') ?>" class="side-menu__item <?= isExact('admin/industries') ?>">Industries</a>
                        </li>
                        <li class="slide <?= isExact('admin/locations') ?>">
                            <a href="<?= base_url('admin/locations') ?>" class="side-menu__item <?= isExact('admin/locations') ?>">Locations</a>
                        </li>
                        <li class="slide <?= isExact('admin/qualifications') ?>">
                            <a href="<?= base_url('admin/qualifications') ?>" class="side-menu__item <?= isExact('admin/qualifications') ?>">Qualifications</a>
                        </li>
                    </ul>
                </li>

                <!-- LEARNING -->
                <li class="slide__category">
                    <span class="category-name">Learning</span>
                </li>

                <li class="slide has-sub <?= openIf(['admin/elearning', 'admin/webinars', 'admin/aptitude']) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/elearning', 'admin/webinars', 'admin/aptitude']) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <path d="M216,40H40a8,8,0,0,0-8,8V192a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V48A8,8,0,0,0,216,40Z" opacity="0.2" />
                            <path d="M216,40H40a8,8,0,0,0-8,8V192a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V48A8,8,0,0,0,216,40Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Learning</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Learning</a>
                        </li>
                        <li class="slide <?= isExact('admin/elearning') && !(isset($_GET['create']) && $_GET['create'] == '1') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/elearning') ?>" class="side-menu__item <?= isExact('admin/elearning') && !(isset($_GET['create']) && $_GET['create'] == '1') ? 'active' : '' ?>">Courses</a>
                        </li>
                        <li class="slide <?= isExact('admin/elearning/certificates') ?>">
                            <a href="<?= base_url('admin/elearning/certificates') ?>" class="side-menu__item <?= isExact('admin/elearning/certificates') ?>">Certificates</a>
                        </li>
                        <li class="slide <?= isExact('admin/elearning/certificates/settings') ?>">
                            <a href="<?= base_url('admin/elearning/certificates/settings') ?>" class="side-menu__item <?= isExact('admin/elearning/certificates/settings') ?>">Certificate Settings</a>
                        </li>
                        <li class="slide <?= isExact('admin/elearning/certificates/editor') ?>">
                            <a href="<?= base_url('admin/elearning/certificates/editor') ?>" class="side-menu__item <?= isExact('admin/elearning/certificates/editor') ?>">Certificate Layout Editor</a>
                        </li>
                        <li class="slide <?= isExact('admin/webinars') && !(isset($_GET['create']) && $_GET['create'] == '1') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/webinars') ?>" class="side-menu__item <?= isExact('admin/webinars') && !(isset($_GET['create']) && $_GET['create'] == '1') ? 'active' : '' ?>">Webinars</a>
                        </li>
                        <li class="slide <?= isStartsWith('admin/aptitude') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/aptitude') ?>" class="side-menu__item <?= isStartsWith('admin/aptitude') ? 'active' : '' ?>">Aptitude Tests</a>
                        </li>
                    </ul>
                </li>
                <!-- CONTENT -->
                <li class="slide__category">
                    <span class="category-name">Content</span>
                </li>
                <li class="slide has-sub <?= openIf(['admin/blogs', 'admin/testimonials', 'admin/newsletters', 'admin/chatbot']) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/blogs', 'admin/testimonials', 'admin/newsletters', 'admin/chatbot']) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <polygon points="152 32 152 88 208 88 152 32" opacity="0.2" />
                            <path d="M200,224H56a8,8,0,0,1-8-8V40a8,8,0,0,1,8-8h96l56,56V216A8,8,0,0,1,200,224Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <polyline points="152 32 152 88 208 88" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Content</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Content</a>
                        </li>
                        <li class="slide <?= isExact('admin/blogs') ?>">
                            <a href="<?= base_url('admin/blogs') ?>" class="side-menu__item <?= isExact('admin/blogs') ?>">Blog</a>
                        </li>
                        <li class="slide <?= isExact('admin/testimonials') ?>">
                            <a href="<?= base_url('admin/testimonials') ?>" class="side-menu__item <?= isExact('admin/testimonials') ?>">Testimonials</a>
                        </li>
                        <li class="slide <?= isExact('admin/newsletters') ?>">
                            <a href="<?= base_url('admin/newsletters') ?>" class="side-menu__item <?= isExact('admin/newsletters') ?>">Newsletters</a>
                        </li>
                        <li class="slide <?= isExact('admin/chatbot') ?>">
                            <a href="<?= base_url('admin/chatbot') ?>" class="side-menu__item <?= isExact('admin/chatbot') ?>">Chatbot</a>
                        </li>
                    </ul>
                </li>

                <!-- FINANCE -->
                <li class="slide__category">
                    <span class="category-name">Finance</span>
                </li>
                <li class="slide has-sub <?= openIf(['admin/plans', 'admin/subscriptions', 'admin/transactions', 'admin/bundles', 'admin/affiliate/settings']) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/plans', 'admin/subscriptions', 'admin/transactions', 'admin/bundles', 'admin/affiliate/settings']) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <circle cx="128" cy="128" r="96" opacity="0.2" />
                            <path d="M128,88a24,24,0,0,1,24,24c0,16-24,24-24,24V88Zm0,80V136s24,8,24,24A24,24,0,0,1,128,168Z" opacity="0.2" />
                            <circle cx="128" cy="128" r="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <path d="M128,88a24,24,0,0,1,24,24c0,16-24,24-24,24V88Zm0,80V136s24,8,24,24A24,24,0,0,1,128,168Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <line x1="128" y1="72" x2="128" y2="88" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <line x1="128" y1="168" x2="128" y2="184" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Finance</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Finance</a>
                        </li>
                        <li class="slide <?= isExact('admin/plans') ?>">
                            <a href="<?= base_url('admin/plans') ?>" class="side-menu__item <?= isExact('admin/plans') ?>">Plans</a>
                        </li>
                        <li class="slide <?= isExact('admin/subscriptions') ?>">
                            <a href="<?= base_url('admin/subscriptions') ?>" class="side-menu__item <?= isExact('admin/subscriptions') ?>">Subscriptions</a>
                        </li>
                        <li class="slide <?= isExact('admin/transactions') ?>">
                            <a href="<?= base_url('admin/transactions') ?>" class="side-menu__item <?= isExact('admin/transactions') ?>">Transactions</a>
                        </li>
                        <li class="slide <?= isExact('admin/bundles') ?>">
                            <a href="<?= base_url('admin/bundles') ?>" class="side-menu__item <?= isExact('admin/bundles') ?>">Bundles</a>
                        </li>
                        <li class="slide <?= isExact('admin/affiliate/settings') ?>">
                            <a href="<?= base_url('admin/affiliate/settings') ?>" class="side-menu__item <?= isExact('admin/affiliate/settings') ?>">Affiliates</a>
                        </li>
                    </ul>
                </li>

                <!-- SYSTEM -->
                <li class="slide__category">
                    <span class="category-name">System</span>
                </li>
                <li class="slide has-sub <?= openIf(['admin/features', 'admin/cv-reviews', 'admin/reports', 'admin/settings']) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/features', 'admin/cv-reviews', 'admin/reports', 'admin/settings']) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <circle cx="128" cy="128" r="48" opacity="0.2" />
                            <circle cx="128" cy="128" r="48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <path d="M197.4,113.5a16.2,16.2,0,0,0,14.6-21.5L200.7,60a16.2,16.2,0,0,0-25.1-6.1L156.4,68.1a16.2,16.2,0,0,1-23.3,0l-1.3-1.3a16.2,16.2,0,0,1,0-23.3L144.1,23.3a16.2,16.2,0,0,0-6.1-25.1L106,6.9a16.2,16.2,0,0,0-21.5,14.6L82.1,43.6a16.2,16.2,0,0,1-23.3,0l-1.3-1.3a16.2,16.2,0,0,1,0-23.3L70.1,6.9a16.2,16.2,0,0,0-25.1-6.1L13,13.5a16.2,16.2,0,0,0-6.1,25.1L23.3,56.4a16.2,16.2,0,0,1,0,23.3l-1.3,1.3a16.2,16.2,0,0,1-23.3,0L6.9,68.1A16.2,16.2,0,0,0,.8,93.2L13,126a16.2,16.2,0,0,0,25.1,6.1L56.4,113.5a16.2,16.2,0,0,1,23.3,0l1.3,1.3a16.2,16.2,0,0,1,0,23.3L68.1,156.4a16.2,16.2,0,0,0,6.1,25.1L106,200.7a16.2,16.2,0,0,0,21.5-14.6l2.4-22.1a16.2,16.2,0,0,1,23.3,0l1.3,1.3a16.2,16.2,0,0,1,0,23.3l-12.3,12.3a16.2,16.2,0,0,0,6.1,25.1L186,220.7a16.2,16.2,0,0,0,21.5-14.6l2.4-22.1a16.2,16.2,0,0,1,23.3,0l1.3,1.3a16.2,16.2,0,0,1,0,23.3l-12.3,12.3a16.2,16.2,0,0,0,6.1,25.1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">System</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">System</a>
                        </li>
                        <li class="slide <?= isExact('admin/features') ?>">
                            <a href="<?= base_url('admin/features') ?>" class="side-menu__item <?= isExact('admin/features') ?>">Feature Management</a>
                        </li>
                        <li class="slide <?= isExact('admin/cv-reviews') ?>">
                            <a href="<?= base_url('admin/cv-reviews') ?>" class="side-menu__item <?= isExact('admin/cv-reviews') ?>">CV Reviews</a>
                        </li>
                        <li class="slide <?= isExact('admin/reports') ?>">
                            <a href="<?= base_url('admin/reports') ?>" class="side-menu__item <?= isExact('admin/reports') ?>">Reports</a>
                        </li>
                        <li class="slide <?= isExact('admin/settings') ?>">
                            <a href="<?= base_url('admin/settings') ?>" class="side-menu__item <?= isExact('admin/settings') ?>">Settings</a>
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