<style>
    img.header-logo-1 {
        width: 30%;
    }
</style>
<!-- app-header -->
<header class="app-header sticky" id="header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="<?= base_url('admin') ?>" class="header-logo">
                        <img src="<?= base_url('images/favicon.png'); ?>" alt="logo" class="desktop-logo">
                        <img src="<?= base_url('images/favicon.png'); ?>" alt="logo" class="toggle-logo">
                        <img src="<?= base_url('images/favicon.png'); ?>" alt="logo" class="desktop-dark">
                        <img src="<?= base_url('images/favicon.png'); ?>" alt="logo" class="toggle-dark">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element mx-lg-0 mx-2">
                <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
            </div>
            <!-- End::header-element -->

            <div class="header-element  header-search header-search-content d-md-block d-none">
                <!-- Start::header-link -->
                <img src="<?= base_url('images/logo.png'); ?>" alt="logo" class="header-logo-1">
                <div class="admin-global-search position-relative d-inline-block align-middle ms-3">
                    <input type="text" class="header-search-bar form-control" id="admin-global-search-input"
                           placeholder="Search jobs, candidates, employers…" spellcheck="false"
                           autocomplete="off" autocapitalize="off" aria-label="Global admin search">
                    <div class="admin-global-search-results dropdown-menu shadow" id="admin-global-search-results" role="listbox"></div>
                </div>
                <!-- End::header-link -->
            </div>

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <ul class="header-content-right">

            <!-- Start::header-element -->
            <li class="header-element d-md-none d-block">
                <a href="javascript:void(0);" class="header-link" data-bs-toggle="modal" data-bs-target="#header-responsive-search">
                    <!-- Start::header-link-icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" viewBox="0 0 256 256">
                        <rect width="256" height="256" fill="none" />
                        <circle cx="112" cy="112" r="80" opacity="0.2" />
                        <circle cx="112" cy="112" r="80" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        <line x1="168.57" y1="168.57" x2="224" y2="224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                    </svg>
                    <!-- End::header-link-icon -->
                </a>
            </li>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <li class="header-element header-theme-mode">
                <!-- Start::header-link|layout-setting -->
                <a href="javascript:void(0);" class="header-link layout-setting">
                    <span class="light-layout">
                        <!-- Start::header-link-icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <path d="M108.11,28.11A96.09,96.09,0,0,0,227.89,147.89,96,96,0,1,1,108.11,28.11Z" opacity="0.2" />
                            <path d="M108.11,28.11A96.09,96.09,0,0,0,227.89,147.89,96,96,0,1,1,108.11,28.11Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                        <!-- End::header-link-icon -->
                    </span>
                    <span class="dark-layout">
                        <!-- Start::header-link-icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" viewBox="0 0 256 256">
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
                </a>
                <!-- End::header-link|layout-setting -->
            </li>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <li class="header-element header-fullscreen">
                <!-- Start::header-link -->
                <a onclick="openFullscreen();" href="javascript:void(0);" class="header-link">
                    <svg xmlns="http://www.w3.org/2000/svg" class="full-screen-open header-link-icon" viewBox="0 0 256 256">
                        <rect width="256" height="256" fill="none" />
                        <rect x="48" y="48" width="160" height="160" opacity="0.2" />
                        <polyline points="168 48 208 48 208 88" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        <polyline points="88 208 48 208 48 168" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        <polyline points="208 168 208 208 168 208" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        <polyline points="48 88 48 48 88 48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="full-screen-close header-link-icon d-none" viewBox="0 0 256 256">
                        <rect width="256" height="256" fill="none" />
                        <rect x="32" y="32" width="192" height="192" rx="16" opacity="0.2" />
                        <polyline points="160 48 208 48 208 96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        <line x1="144" y1="112" x2="208" y2="48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        <polyline points="96 208 48 208 48 160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        <line x1="112" y1="144" x2="48" y2="208" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                    </svg>
                </a>
                <!-- End::header-link -->
            </li>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <li class="header-element dropdown">
                <!-- Start::header-link|dropdown-toggle -->
                <?php
                    if (!isset($user)) {
                        $user = auth()->loggedIn() ? auth()->user() : null;
                    }
                    if (!isset($admin) && $user !== null) {
                        $adminModel = model(\App\Models\AdminModel::class);
                        $admin = $adminModel->where('user_id', $user->id)->first();
                    }
                    $adminDisplayName = $admin->full_name ?? $user->username ?? 'Admin';
                    $adminInitials = strtoupper(mb_substr($adminDisplayName, 0, 1));
                    if (preg_match('/\s+(\S)/u', $adminDisplayName, $mIni)) {
                        $adminInitials .= strtoupper($mIni[1]);
                    }
                ?>
                <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <span class="avatar avatar-sm avatar-rounded bg-primary text-fixed-white fw-semibold admin-avatar-initials"><?= esc($adminInitials) ?></span>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <div class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                    <div class="p-3 bg-primary text-fixed-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 fs-16">Profile</p>
                            <a href="<?= base_url('admin/profile') ?>" class="text-fixed-white" aria-label="Account settings"><i class="ti ti-settings-cog"></i></a>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="p-3">
                        <div class="d-flex align-items-start gap-2">
                            <div class="lh-1">
                                <span class="avatar avatar-sm bg-primary text-fixed-white avatar-rounded fw-semibold admin-avatar-initials"><?= esc($adminInitials) ?></span>
                            </div>
                            <div>
                                <span class="d-block fw-semibold lh-1"><?= esc($adminDisplayName) ?></span>
                                <span class="text-muted fs-12"><?= esc($user->email ?? '') ?></span>
                                <?php if (!empty($admin->role)): ?>
                                    <span class="badge bg-primary-transparent fs-10 mt-1"><?= esc(ucwords(str_replace('_', ' ', $admin->role))) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <ul class="list-unstyled mb-0 sub-list">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/profile') ?>"><i class="ti ti-user-circle me-2 fs-18"></i>View Profile</a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/profile') ?>"><i class="ti ti-settings-cog me-2 fs-18"></i>Account Settings</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <ul class="list-unstyled mb-0 sub-list">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/features') ?>"><i class="ti ti-adjustments me-2 fs-18"></i>Feature Management</a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/settings') ?>"><i class="ti ti-settings me-2 fs-18"></i>Site Settings</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/logout'); ?>"><i class="ti ti-logout me-2 fs-18"></i>Log Out</a></li>
                    </ul>
                </div>
            </li>
            <!-- End::header-element -->

        </ul>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->

</header>
<!-- /app-header -->

<script>
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('admin-global-search-input');
        var panel = document.getElementById('admin-global-search-results');
        if (!input || !panel) return;

        var timer = null;
        var activeIndex = -1;

        function close() { panel.classList.remove('show'); panel.innerHTML = ''; activeIndex = -1; }

        function render(results) {
            if (!results.length) {
                panel.innerHTML = '<div class="dropdown-item-text text-muted fs-12">No matches</div>';
                panel.classList.add('show');
                return;
            }
            var html = '';
            var lastGroup = null;
            results.forEach(function (r, i) {
                if (r.group !== lastGroup) {
                    html += '<h6 class="dropdown-header py-1">' + r.group + '</h6>';
                    lastGroup = r.group;
                }
                html += '<a class="dropdown-item d-flex flex-column py-1" data-idx="' + i + '" href="' + r.url + '">'
                      + '<span class="fw-semibold fs-13 text-truncate">' + escapeHtml(r.label) + '</span>'
                      + (r.sub ? '<span class="text-muted fs-11 text-truncate">' + escapeHtml(r.sub) + '</span>' : '')
                      + '</a>';
            });
            panel.innerHTML = html;
            panel.classList.add('show');
        }

        function escapeHtml(s) {
            return String(s || '').replace(/[&<>"']/g, function (c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
            });
        }

        input.addEventListener('input', function () {
            clearTimeout(timer);
            var q = input.value.trim();
            if (q.length < 2) { close(); return; }
            timer = setTimeout(function () {
                fetch('<?= base_url('admin/search') ?>?q=' + encodeURIComponent(q), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function (r) { return r.json(); })
                .then(function (data) { render(data.results || []); })
                .catch(function () { close(); });
            }, 250);
        });

        input.addEventListener('keydown', function (e) {
            var items = panel.querySelectorAll('.dropdown-item');
            if (!items.length) return;
            if (e.key === 'ArrowDown') { e.preventDefault(); activeIndex = Math.min(activeIndex + 1, items.length - 1); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); activeIndex = Math.max(activeIndex - 1, 0); }
            else if (e.key === 'Enter' && activeIndex >= 0) { e.preventDefault(); items[activeIndex].click(); return; }
            else if (e.key === 'Escape') { close(); return; }
            else { return; }
            items.forEach(function (el, i) { el.classList.toggle('active', i === activeIndex); });
        });

        document.addEventListener('click', function (e) {
            if (!panel.contains(e.target) && e.target !== input) close();
        });
    });
})();
</script>