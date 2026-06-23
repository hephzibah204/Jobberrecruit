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

                <!-- DASHBOARD -->
                <li class="slide <?= isExact('admin/dashboard') ?>">
                    <a href="<?= base_url('admin/dashboard') ?>" class="side-menu__item <?= isExact('admin/dashboard') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <path d="M133.66,34.34a8,8,0,0,0-11.32,0L40,116.69V216h64V152h48v64h64V116.69Z" opacity="0.2" />
                        </svg>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                <!-- PLATFORM MANAGEMENT -->
                <li class="slide has-sub <?= openIf([
                                                'admin/locations',
                                                'admin/categories',
                                                'admin/industries',
                                                'admin/jobs',
                                                'admin/candidates',
                                                'admin/employers',
                                                'admin/applications'
                                            ]) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf([
                                                                                'admin/locations',
                                                                                'admin/categories',
                                                                                'admin/industries',
                                                                                'admin/jobs',
                                                                                'admin/candidates',
                                                                                'admin/employers',
                                                                                'admin/applications'
                                                                            ]) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <path d="M128,144a191.14,191.14,0,0,1-96-25.68V200a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V118.31Z" opacity="0.2" />
                        </svg>
                        <span class="side-menu__label">Platform</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>

                    <ul class="slide-menu child1 double-menu-active">
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

                        <li class="slide <?= isExact('admin/locations') ?>">
                            <a href="<?= base_url('admin/locations') ?>" class="side-menu__item <?= isExact('admin/locations') ?>">Locations</a>
                        </li>

                        <li class="slide <?= isExact('admin/categories') ?>">
                            <a href="<?= base_url('admin/categories') ?>" class="side-menu__item <?= isExact('admin/categories') ?>">Categories</a>
                        </li>

                         <li class="slide <?= isExact('admin/industries') ?>">
                             <a href="<?= base_url('admin/industries') ?>" class="side-menu__item <?= isExact('admin/industries') ?>">Industries</a>
                         </li>

                         <!-- CV Reviews -->
                         <li class="slide <?= isExact('admin/cv-reviews') ?>">
                             <a href="<?= base_url('admin/cv-reviews') ?>" class="side-menu__item <?= isExact('admin/cv-reviews') ?>">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                     <rect width="256" height="256" fill="none" />
                                     <path d="M216,40H40a8,8,0,0,0-8,8V192a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V48A8,8,0,0,0,216,40Z" opacity="0.2" />
                                     <line x1="88" y1="96" x2="168" y2="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                     <line x1="88" y1="128" x2="168" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                     <line x1="88" y1="160" x2="120" y2="160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                 </svg>
                                 CV Reviews
                             </a>
                         </li>
                    </ul>
                </li>

                <!-- TRAINING & WEBINARS -->
                <li class="slide has-sub <?= openIf(['admin/elearning', 'admin/webinars']) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/elearning', 'admin/webinars']) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <path d="M216,40H40a8,8,0,0,0-8,8V192a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V48A8,8,0,0,0,216,40Z" opacity="0.2" />
                            <path d="M216,40H40a8,8,0,0,0-8,8V192a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V48A8,8,0,0,0,216,40Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <span class="side-menu__label">Training & Webinars</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>

                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Training & Webinars</a>
                        </li>

                        <!-- TRAINING -->
                        <li class="slide has-sub <?= openIf(['admin/elearning']) ?>">
                            <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/elearning']) ?>">
                                Training
                                <i class="ri-arrow-right-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <?php 
                                $isCreateActive = isExact('admin/elearning') && (isset($_GET['create']) && $_GET['create'] == '1');
                                $isManageActive = isExact('admin/elearning') && !$isCreateActive;
                                ?>
                                <li class="slide <?= $isManageActive ? 'active' : '' ?>">
                                    <a href="<?= base_url('admin/elearning') ?>" class="side-menu__item <?= $isManageActive ? 'active' : '' ?>">Manage Courses</a>
                                </li>
                                <li class="slide <?= $isCreateActive ? 'active' : '' ?>">
                                    <a href="<?= base_url('admin/elearning?create=1') ?>" class="side-menu__item <?= $isCreateActive ? 'active' : '' ?>">Create Course</a>
                                </li>
                            </ul>
                        </li>

                        <!-- WEBINARS -->
                        <li class="slide has-sub <?= openIf(['admin/webinars']) ?>">
                            <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/webinars']) ?>">
                                Webinars
                                <i class="ri-arrow-right-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <?php 
                                $isWebinarCreateActive = isExact('admin/webinars') && (isset($_GET['create']) && $_GET['create'] == '1');
                                $isWebinarManageActive = isExact('admin/webinars') && !$isWebinarCreateActive;
                                ?>
                                <li class="slide <?= $isWebinarManageActive ? 'active' : '' ?>">
                                    <a href="<?= base_url('admin/webinars') ?>" class="side-menu__item <?= $isWebinarManageActive ? 'active' : '' ?>">Manage Webinars</a>
                                </li>
                                <li class="slide <?= $isWebinarCreateActive ? 'active' : '' ?>">
                                    <a href="<?= base_url('admin/webinars?create=1') ?>" class="side-menu__item <?= $isWebinarCreateActive ? 'active' : '' ?>">Schedule Webinar</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- SUBSCRIPTIONS & CONFIG -->
                <li class="slide has-sub <?= openIf(['admin/plans', 'admin/affiliate/settings', 'admin/features']) ?>">
                    <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/plans', 'admin/affiliate/settings', 'admin/features']) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256">
                            <circle cx="128" cy="128" r="96" opacity="0.2" />
                        </svg>
                        <span class="side-menu__label">Subscriptions</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>

                    <ul class="slide-menu child1">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Subscriptions</a>
                        </li>

                        <li class="slide <?= isExact('admin/plans') ?>">
                            <a href="<?= base_url('admin/plans') ?>" class="side-menu__item <?= isExact('admin/plans') ?>">Subscription Plans</a>
                        </li>

                        <li class="slide <?= isExact('admin/affiliate/settings') ?>">
                            <a href="<?= base_url('admin/affiliate/settings') ?>" class="side-menu__item <?= isExact('admin/affiliate/settings') ?>">Affiliate Program</a>
                        </li>

                        <li class="slide <?= isExact('admin/features') ?>">
                            <a href="<?= base_url('admin/features') ?>" class="side-menu__item <?= isExact('admin/features') ?>">Feature Management</a>
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
                        <li class="slide has-sub <?= openIf(['admin/newsletters']) ?>">
                            <a href="javascript:void(0);" class="side-menu__item <?= openIf(['admin/newsletters']) ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path d="M224,96H32a8,8,0,0,1-8-8V56a8,8,0,0,1,8-8H224a8,8,0,0,1,8,8V88A8,8,0,0,1,224,96Z" opacity="0.2" />
                                    <path d="M224,96H32a8,8,0,0,1-8-8V56a8,8,0,0,1,8-8H224a8,8,0,0,1,8,8V88A8,8,0,0,1,224,96Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <path d="M32,96V200a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <polyline points="32 96 128 152 224 96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                </svg>
                                Newsletters
                                <i class="ri-arrow-right-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child2">
                                <li class="slide <?= isExact('admin/newsletters') ?>">
                                    <a href="<?= base_url('admin/newsletters') ?>" class="side-menu__item <?= isExact('admin/newsletters') ?>">
                                        Campaigns
                                    </a>
                                </li>
                                <li class="slide <?= isExact('admin/newsletters/subscribers') ?>">
                                    <a href="<?= base_url('admin/newsletters/subscribers') ?>" class="side-menu__item <?= isExact('admin/newsletters/subscribers') ?>">
                                        Subscribers
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Chatbot -->
                        <li class="slide <?= isExact('admin/chatbot') ?>">
                            <a href="<?= base_url('admin/chatbot') ?>" class="side-menu__item <?= isExact('admin/chatbot') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path d="M160,72a32,32,0,0,1-32,32h0a32,32,0,0,1-32-32V56a32,32,0,0,1,32-32h0a32,32,0,0,1,32,32Z" opacity="0.2" />
                                    <circle cx="128" cy="176" r="40" opacity="0.2" />
                                    <path d="M200,160v40a8,8,0,0,1-8,8H64a8,8,0,0,1-8-8V160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <line x1="128" y1="208" x2="128" y2="240" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <polyline points="160 232 128 240 96 232" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                </svg>
                                Chatbot
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

                        <!-- CV Reviews -->
                        <li class="slide <?= isExact('admin/cv-reviews') ?>">
                            <a href="<?= base_url('admin/cv-reviews') ?>" class="side-menu__item <?= isExact('admin/cv-reviews') ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu-doublemenu__icon" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path d="M216,40H40a8,8,0,0,0-8,8V192a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V48A8,8,0,0,0,216,40Z" opacity="0.2" />
                                    <line x1="88" y1="96" x2="168" y2="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <line x1="88" y1="128" x2="168" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                    <line x1="88" y1="160" x2="120" y2="160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                                </svg>
                                CV Reviews
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