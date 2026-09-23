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
            <?php
            // Flat one-click menu: category headers with pages listed directly.
            // match: 'exact' highlights only that URI, 'prefix' highlights sub-pages too.
            $adminMenu = [
                'Overview' => [
                    ['Dashboard',           'admin/dashboard',              'ti-layout-dashboard',  'exact'],
                ],
                'Platform' => [
                    ['Jobs',                'admin/jobs',                   'ti-briefcase',         'prefix'],
                    ['Candidates',          'admin/candidates',             'ti-users',             'prefix'],
                    ['Employers',           'admin/employers',              'ti-building',          'prefix'],
                    ['Applications',        'admin/applications',           'ti-file-description',  'prefix'],
                    ['Users',               'admin/users',                  'ti-user-cog',          'prefix'],
                ],
                'Taxonomy' => [
                    ['Categories',          'admin/categories',             'ti-category',          'exact'],
                    ['Industries',          'admin/industries',             'ti-topology-star-3',   'exact'],
                    ['Locations',           'admin/locations',              'ti-map-pin',           'exact'],
                ],
                'Learning' => [
                    ['Courses',             'admin/elearning',              'ti-book',              'exact'],
                    ['Certificates',        'admin/elearning/certificates', 'ti-certificate',       'prefix'],
                    ['Webinars',            'admin/webinars',               'ti-device-desktop',    'prefix'],
                    ['Aptitude Tests',      'admin/aptitude',               'ti-brain',             'prefix'],
                ],
                'Content' => [
                    ['Blog',                'admin/blogs',                  'ti-news',              'prefix'],
                    ['Testimonials',        'admin/testimonials',           'ti-message-star',      'exact'],
                    ['Newsletters',         'admin/newsletters',            'ti-mail',              'prefix'],
                    ['Chatbot',             'admin/chatbot',                'ti-message-chatbot',   'exact'],
                ],
                'Finance' => [
                    ['Plans',               'admin/plans',                  'ti-crown',             'prefix'],
                    ['Bundles',             'admin/bundles',                'ti-packages',          'exact'],
                    ['Affiliates',          'admin/affiliate/settings',     'ti-share',             'exact'],
                ],
                'System' => [
                    ['Feature Management',  'admin/features',               'ti-adjustments',       'exact'],
                    ['CV Reviews',          'admin/cv-reviews',             'ti-file-text',         'prefix'],
                    ['Reports',             'admin/reports',                'ti-flag',              'prefix'],
                    ['Settings',            'admin/settings',               'ti-settings',          'exact'],
                ],
            ];
            ?>
            <ul class="main-menu">
                <?php foreach ($adminMenu as $category => $items): ?>
                    <li class="slide__category">
                        <span class="category-name"><?= esc($category) ?></span>
                    </li>
                    <?php foreach ($items as [$label, $path, $icon, $match]): ?>
                        <?php $active = $match === 'prefix' ? (isStartsWith($path) ? 'active' : '') : isExact($path); ?>
                        <li class="slide <?= $active ?>">
                            <a href="<?= base_url($path) ?>" class="side-menu__item <?= $active ?>">
                                <i class="ti <?= $icon ?> side-menu__icon"></i>
                                <span class="side-menu__label"><?= esc($label) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
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