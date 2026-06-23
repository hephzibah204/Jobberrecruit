<?php
$user = $user ?? auth()->user();
$isEmployer = ($user?->user_type === 'employer');
$displayName = 'User';
$email = $user->email ?? '';
$currentUri = trim(uri_string(), '/');

function isActive($path)
{
    return trim($path, '/') === trim(uri_string(), '/') ? 'active' : '';
}

function isActiveStartsWith($path)
{
    return str_starts_with(trim(uri_string(), '/'), trim($path, '/')) ? 'active' : '';
}

if ($isEmployer) {
    $displayName = isset($employer) && ! empty($employer->company_name) ? $employer->company_name : 'Employer';
} else {
    $displayName = isset($candidate) && ! empty($candidate->full_name) ? $candidate->full_name : 'Candidate';
}
?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="<?= base_url('dashboard') ?>" class="logo logo-normal">
            <img src="<?= base_url('auth/img/logo.png'); ?>" alt="Logo">
        </a>
        <a href="<?= base_url('dashboard') ?>" class="logo-small">
            <img src="<?= base_url('auth/img/favicon.png'); ?>" alt="Small Logo">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i class="ti ti-chevrons-left feather-16"></i>
        </a>
    </div>

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
                    <a href="<?= base_url('candidate/subscription/pricing') ?>" class="btn btn-sm fw-bold w-100 rounded-pill py-2 px-3 shadow" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; color: #fff;">
                        <i class="ti ti-crown me-1"></i> Upgrade Plan
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <?php if ($isEmployer): ?>
                    <!-- ===== RECRUITMENT HUB ===== -->
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Recruitment Hub</h6>
                        <ul>
                            <li class="<?= isActive('employer/dashboard') ?>">
                                <a href="<?= base_url('employer/dashboard') ?>">
                                    <i class="ti ti-grid-dots fs-16 me-2"></i><span>Dashboard</span>
                                </a>
                            </li>
                            <li class="<?= isActiveStartsWith('employer/jobs') ?>">
                                <a href="<?= base_url('employer/jobs') ?>">
                                    <i class="ti ti-briefcase fs-16 me-2"></i><span>My Jobs</span>
                                </a>
                            </li>
                            <li class="<?= isActiveStartsWith('employer/applications') ?>">
                                <a href="<?= base_url('employer/applications') ?>">
                                    <i class="ti ti-file-description fs-16 me-2"></i><span>Applications</span>
                                </a>
                            </li>
                            <li class="<?= isActive('employer/candidates') ?>">
                                <a href="<?= base_url('employer/candidates') ?>">
                                    <i class="ti ti-users fs-16 me-2"></i><span>Candidates Search</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- ===== ORGANIZATION SPACE ===== -->
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Organization Space</h6>
                        <ul>
                            <li class="<?= isActive('employer/profile') ?>">
                                <a href="<?= base_url('employer/profile') ?>">
                                    <i class="ti ti-building fs-16 me-2"></i><span>Company Profile</span>
                                </a>
                            </li>
                            <?php if (env('feature_messaging', 'true') == 'true'): ?>
                            <li class="<?= isActive('employer/messages') ?>">
                                <a href="<?= base_url('employer/messages') ?>">
                                    <i class="ti ti-message-dots fs-16 me-2"></i><span>Messages</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (env('feature_referrals', 'true') == 'true'): ?>
                            <li class="<?= isActive('employer/referrals') ?>">
                                <a href="<?= base_url('employer/referrals') ?>">
                                    <i class="ti ti-share fs-16 me-2"></i><span>Referral Program</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <!-- ===== BILLING & ALERTS ===== -->
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Billing & Alerts</h6>
                        <ul>
                            <li class="<?= isActive('employer/pricing') ?>">
                                <a href="<?= base_url('employer/pricing') ?>">
                                    <i class="ti ti-credit-card fs-16 me-2"></i><span>Billing & Plans</span>
                                </a>
                            </li>
                            <li class="<?= isActiveStartsWith('employer/transactions') ?>">
                                <a href="<?= base_url('employer/transactions') ?>">
                                    <i class="ti ti-receipt fs-16 me-2"></i><span>Transactions</span>
                                </a>
                            </li>
                            <li class="<?= isActive('employer/notifications') ?>">
                                <a href="<?= base_url('employer/notifications') ?>">
                                    <i class="ti ti-bell fs-16 me-2"></i><span>Job Alerts</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php else: ?>
                    <!-- ===== CORE CAREER ===== -->
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Core Career</h6>
                        <ul>
                            <li class="<?= isActive('candidate/dashboard') ?>">
                                <a href="<?= base_url('candidate/dashboard') ?>">
                                    <i class="ti ti-grid-dots fs-16 me-2"></i><span>Dashboard</span>
                                </a>
                            </li>
                            <li class="<?= isActiveStartsWith('jobs') || isActiveStartsWith('candidate/jobs') ? 'active' : '' ?>">
                                <a href="<?= base_url('jobs') ?>">
                                    <i class="ti ti-briefcase fs-16 me-2"></i><span>Browse Jobs</span>
                                </a>
                            </li>
                            <li class="<?= isActiveStartsWith('candidate/applications') ?>">
                                <a href="<?= base_url('candidate/applications') ?>">
                                    <i class="ti ti-file-check fs-16 me-2"></i><span>My Applications</span>
                                </a>
                            </li>
                            <li class="<?= isActive('candidate/profile') ?>">
                                <a href="<?= base_url('candidate/profile') ?>">
                                    <i class="ti ti-user fs-16 me-2"></i><span>My Profile</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- ===== AI COGNITIVE TOOLS ===== -->
                    <?php if (env('feature_ai_resume', 'true') == 'true' || env('feature_ai_career_tools', 'true') == 'true'): ?>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">AI Cognitive Tools</h6>
                        <ul>
                            <?php if (env('feature_ai_resume', 'true') == 'true'): ?>
                            <li class="<?= isActiveStartsWith('candidate/resumes') ?>">
                                <a href="<?= base_url('candidate/resumes') ?>">
                                    <i class="ti ti-file-spark fs-16 me-2"></i><span>AI Resume Builder</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (env('feature_ai_career_tools', 'true') == 'true'): ?>
                            <li class="<?= isActiveStartsWith('candidate/career-tools') ?>">
                                <a href="<?= base_url('candidate/career-tools') ?>">
                                    <i class="ti ti-sparkles fs-16 me-2"></i><span>AI Career Tools</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- ===== LEARNING & TRAINING ===== -->
                    <?php if (env('feature_elearning', 'true') == 'true' || env('feature_webinars', 'true') == 'true'): ?>
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Learning & Training</h6>
                        <ul>
                            <?php if (env('feature_elearning', 'true') == 'true'): ?>
                            <li class="<?= isActiveStartsWith('training') && !isActiveStartsWith('training/certificates') ? 'active' : '' ?>">
                                <a href="<?= base_url('training') ?>">
                                    <i class="ti ti-book fs-16 me-2"></i><span>Training Catalog</span>
                                </a>
                            </li>
                            <li class="<?= isActive('candidate/my-courses') ?>">
                                <a href="<?= base_url('candidate/my-courses') ?>">
                                    <i class="ti ti-bookmark fs-16 me-2"></i><span>My Courses</span>
                                </a>
                            </li>
                            <li class="<?= isActiveStartsWith('training/certificates') ?>">
                                <a href="<?= base_url('training/certificates') ?>">
                                    <i class="ti ti-award fs-16 me-2"></i><span>Certificates</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (env('feature_webinars', 'true') == 'true'): ?>
                            <li class="<?= isActiveStartsWith('webinars') ?>">
                                <a href="<?= base_url('webinars') ?>">
                                    <i class="ti ti-video fs-16 me-2"></i><span>Career Webinars</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- ===== BILLING & NETWORK ===== -->
                    <li class="submenu-open">
                        <h6 class="submenu-hdr">Billing & Network</h6>
                        <ul>
                            <li class="<?= isActiveStartsWith('candidate/subscription') ?>">
                                <a href="<?= base_url('candidate/subscription/pricing') ?>">
                                    <i class="ti ti-crown fs-16 me-2"></i><span>Premium Plans</span>
                                </a>
                            </li>
                            <li class="<?= isActiveStartsWith('candidate/transactions') ?>">
                                <a href="<?= base_url('candidate/transactions') ?>">
                                    <i class="ti ti-receipt fs-16 me-2"></i><span>Transactions</span>
                                </a>
                            </li>
                            <?php if (env('feature_messaging', 'true') == 'true'): ?>
                            <li class="<?= isActive('candidate/messages') ?>">
                                <a href="<?= base_url('candidate/messages') ?>">
                                    <i class="ti ti-message-circle fs-16 me-2"></i><span>Messages</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (env('feature_referrals', 'true') == 'true'): ?>
                            <li class="<?= isActive('candidate/referrals') ?>">
                                <a href="<?= base_url('candidate/referrals') ?>">
                                    <i class="ti ti-share fs-16 me-2"></i><span>Referral Program</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="<?= isActive('candidate/notifications') ?>">
                                <a href="<?= base_url('candidate/notifications') ?>">
                                    <i class="ti ti-bell fs-16 me-2"></i><span>Job Alerts</span>
                                </a>
                            </li>
                        </ul>
                    </li>
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
