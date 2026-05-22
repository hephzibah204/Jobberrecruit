<?php
$user = $user ?? auth()->user();
$isEmployer = ($user?->user_type === 'employer');
$displayName = 'User';
$email = $user->email ?? '';

if ($isEmployer) {
    $displayName = isset($employer) && ! empty($employer->company_name) ? $employer->company_name : 'Employer';
} else {
    $displayName = isset($candidate) && ! empty($candidate->full_name) ? $candidate->full_name : 'Candidate';
}
?>
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="<?= base_url('dashboard') ?>" class="logo logo-normal">
            <img src="<?= base_url('auth/img/logo.png'); ?>" alt="Logo">
        </a>
        <a href="<?= base_url('dashboard') ?>" class="logo-small">
            <img src="<?= base_url('auth/img/favicon.png'); ?>" alt="Small Logo">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <!-- /Logo -->

    <!-- Profile Summary -->
    <div class="modern-profile p-3 pb-0">
        <div class="text-center rounded bg-light p-3 mb-4 user-profile">
            <div class="avatar avatar-lg online mb-3">
                <img src="<?= base_url('assets/img/customer/customer15.jpg'); ?>" alt="Img" class="img-fluid rounded-circle">
            </div>
            <h6 class="fs-14 fw-bold mb-1">
                <?= esc($displayName) ?>
            </h6>
            <p class="fs-12 mb-0"><?= esc($email) ?></p>
            <?php if (!$isEmployer): ?>
                <div class="mt-3">
                    <a href="<?= base_url('candidate/subscription/pricing') ?>" class="btn btn-sm btn-gradient-warning text-white fw-bold w-100 rounded-pill py-2 px-3 shadow" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                        <i class="ti ti-crown me-1"></i> Upgrade Plan
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- /Profile Summary -->

    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <!-- ===== MAIN MENU ===== -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Menu</h6>
                    <ul>
                        <?php if ($isEmployer): ?>
                            <li <?= uri_string() === 'employer/dashboard' ? 'class="active"' : '' ?>>
                                <a href="<?= base_url('employer/dashboard') ?>">
                                    <i data-feather="grid"></i><span>Dashboard</span>
                                </a>
                            </li>
                            <li <?= uri_string() === 'employer/jobs' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/jobs') ?>"><i class="ti ti-briefcase fs-16 me-2"></i><span>My Jobs</span></a></li>
                            <li <?= uri_string() === 'employer/applications' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/applications') ?>"><i class="ti ti-file-description fs-16 me-2"></i><span>Applications</span></a></li>
                            <li <?= uri_string() === 'employer/candidates' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/candidates') ?>"><i class="ti ti-users fs-16 me-2"></i><span>Candidates Search</span></a></li>
                            <li <?= uri_string() === 'employer/profile' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/profile') ?>"><i class="ti ti-building fs-16 me-2"></i><span>Company Profile</span></a></li>
                            <?php if (env('feature_messaging', 'true') === 'true'): ?>
                            <li <?= uri_string() === 'employer/messages' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/messages') ?>"><i class="ti ti-message-dots fs-16 me-2"></i><span>Messages</span></a></li>
                            <?php endif; ?>
                            <li <?= uri_string() === 'employer/pricing' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/pricing') ?>"><i class="ti ti-credit-card fs-16 me-2"></i><span>Billing & Plans</span></a></li>
                            <li <?= uri_string() === 'employer/transactions' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/transactions') ?>"><i class="ti ti-receipt fs-16 me-2"></i><span>Transactions</span></a></li>
                            <?php if (env('feature_referrals', 'true') === 'true'): ?>
                            <li <?= uri_string() === 'employer/referrals' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/referrals') ?>"><i class="ti ti-share fs-16 me-2"></i><span>Referral Program</span></a></li>
                            <?php endif; ?>
                            <li <?= uri_string() === 'employer/notifications' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/notifications') ?>"><i class="ti ti-bell fs-16 me-2"></i><span>Job Alerts</span></a></li>
                        <?php else: ?>
                            <li class="active">
                                <a href="<?= base_url('candidate/dashboard') ?>">
                                    <i data-feather="grid"></i><span>Dashboard</span>
                                </a>
                            </li>
                            <li <?= uri_string() === 'candidate/jobs' ? 'class="active"' : '' ?>><a href="<?= base_url('jobs') ?>"><i class="ti ti-briefcase fs-16 me-2"></i><span>Browse Jobs</span></a></li>
                            <li <?= uri_string() === 'candidate/applications' ? 'class="active"' : '' ?>><a href="<?= base_url('candidate/applications') ?>"><i class="ti ti-file-check fs-16 me-2"></i><span>My Applications</span></a></li>
                            <li <?= uri_string() === 'candidate/profile' ? 'class="active"' : '' ?>><a href="<?= base_url('candidate/profile') ?>"><i class="ti ti-user fs-16 me-2"></i><span>My Profile</span></a></li>
                            <?php if (env('feature_ai_resume', 'true') === 'true'): ?>
                            <li <?= strpos(uri_string(), 'candidate/resumes') !== false ? 'class="active"' : '' ?>><a href="<?= base_url('candidate/resumes') ?>"><i class="ti ti-file-spark fs-16 me-2"></i><span>AI Resume Builder</span></a></li>
                            <?php endif; ?>
                            <?php if (env('feature_ai_career_tools', 'true') === 'true'): ?>
                            <li <?= strpos(uri_string(), 'candidate/career-tools') !== false ? 'class="active"' : '' ?>><a href="<?= base_url('candidate/career-tools') ?>"><i class="ti ti-sparkles fs-16 me-2"></i><span>AI Career Tools</span></a></li>
                            <?php endif; ?>
                            <li <?= strpos(uri_string(), 'candidate/subscription') !== false ? 'class="active"' : '' ?>><a href="<?= base_url('candidate/subscription/pricing') ?>"><i class="ti ti-crown fs-16 me-2"></i><span>Premium Plans</span></a></li>
                            <li <?= uri_string() === 'candidate/my-courses' ? 'class="active"' : '' ?>><a href="<?= base_url('candidate/my-courses') ?>"><i class="ti ti-book fs-16 me-2"></i><span>My Courses</span></a></li>
                            <li <?= uri_string() === 'training/certificates' ? 'class="active"' : '' ?>><a href="<?= base_url('training/certificates') ?>"><i class="ti ti-award fs-16 me-2"></i><span>Certificates</span></a></li>
                            <li <?= uri_string() === 'candidate/transactions' ? 'class="active"' : '' ?>><a href="<?= base_url('candidate/transactions') ?>"><i class="ti ti-receipt fs-16 me-2"></i><span>Transactions</span></a></li>
                            <?php if (env('feature_messaging', 'true') === 'true'): ?>
                            <li <?= uri_string() === 'candidate/messages' ? 'class="active"' : '' ?>><a href="<?= base_url('candidate/messages') ?>"><i class="ti ti-message-circle fs-16 me-2"></i><span>Messages</span></a></li>
                            <?php endif; ?>
                            <?php if (env('feature_referrals', 'true') === 'true'): ?>
                            <li <?= uri_string() === 'candidate/referrals' ? 'class="active"' : '' ?>><a href="<?= base_url('candidate/referrals') ?>"><i class="ti ti-share fs-16 me-2"></i><span>Referral Program</span></a></li>
                            <?php endif; ?>
                            <li <?= uri_string() === 'candidate/notifications' ? 'class="active"' : '' ?>><a href="<?= base_url('candidate/notifications') ?>"><i class="ti ti-bell fs-16 me-2"></i><span>Job Alerts</span></a></li>
                            <?php if (env('feature_webinars', 'true') === 'true'): ?>
                            <li <?= uri_string() === 'webinars' ? 'class="active"' : '' ?>><a href="<?= base_url('webinars') ?>"><i class="ti ti-video fs-16 me-2"></i><span>Career Webinars</span></a></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </li>

                <!-- ===== REPORTS ===== -->
                <?php if ($isEmployer): ?>
                    <!-- <li class="submenu-open">
                        <h6 class="submenu-hdr">Reports</h6>
                        <ul>
                            <li <?= uri_string() === 'employer/jobs' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/reports/analytics') ?>"><i class="ti ti-file-analytics fs-16 me-2"></i><span>Analytics Report</span></a></li>
                            <li <?= uri_string() === 'employer/jobs' ? 'class="active"' : '' ?>><a href="<?= base_url('employer/interviews') ?>"><i class="ti ti-calendar-event fs-16 me-2"></i><span>Interview Scheduler</span></a></li>
                        </ul>
                    </li> -->
                <?php endif; ?>

                <!-- ===== SETTINGS ===== -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-settings fs-16 me-2"></i><span>General Settings</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <?php if ($isEmployer): ?>
                                    <li><a href="<?= base_url('employer/settings/security') ?>">Security</a></li>
                                <?php else: ?>
                                    <li><a href="<?= base_url('candidate/settings/security') ?>">Security</a></li>
                                <?php endif; ?>
                                <!-- <li><a href="<?= base_url('settings/job-alerts') ?>">Job Alert Preferences</a></li>
                                <li><a href="<?= base_url('settings/notifications') ?>">Notifications</a></li>
                                <li><a href="<?= base_url('settings/connected-apps') ?>">Connected Apps</a></li> -->
                            </ul>
                        </li>
                        <li>
                            <a href="<?= base_url('logout') ?>">
                                <i class="ti ti-logout fs-16 me-2"></i><span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ===== HELP ===== -->
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Help</h6>
                    <ul>
                        <!-- <li><a href="<?= base_url('support') ?>"><i class="ti ti-help fs-16 me-2"></i><span>Support/Help Center</span></a></li> -->
                        <li>
                            <a href="javascript:void(0);">
                                <i class="ti ti-exchange fs-16 me-2"></i>
                                <span>Changelog</span>
                                <span class="badge bg-primary badge-xs text-white fs-10 ms-2">v1.0.0</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
