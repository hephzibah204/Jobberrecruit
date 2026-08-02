<?php
$user = $user ?? auth()->user();
$isEmployer = ($user?->user_type === 'employer');
$defaultImage = 'images/favicon.png';
$profileImage = $defaultImage;
$hasProfileImage = false;
$displayName = 'User';
$email = $user->email ?? '';

if ($isEmployer) {
    $logoPath = isset($employer) && ! empty($employer->logo) ? $employer->logo : '';
    if ($logoPath) {
        if (filter_var($logoPath, FILTER_VALIDATE_URL) || str_starts_with($logoPath, 'http://') || str_starts_with($logoPath, 'https://')) {
            $profileImage = $logoPath;
            $hasProfileImage = true;
        } elseif (file_exists(FCPATH . $logoPath)) {
            $profileImage = $logoPath;
            $hasProfileImage = true;
        }
    }
    $displayName = isset($employer) && ! empty($employer->company_name) ? $employer->company_name : 'Employer';
} else {
    $picPath = isset($candidate) && ! empty($candidate->profile_picture) ? $candidate->profile_picture : '';
    if ($picPath) {
        if (filter_var($picPath, FILTER_VALIDATE_URL) || str_starts_with($picPath, 'http://') || str_starts_with($picPath, 'https://')) {
            $profileImage = $picPath;
            $hasProfileImage = true;
        } elseif (file_exists(FCPATH . $picPath)) {
            $profileImage = $picPath;
            $hasProfileImage = true;
        }
    }
    $displayName = isset($candidate) && ! empty($candidate->full_name) ? $candidate->full_name : 'Candidate';
}

$initials = '';
$words = explode(' ', preg_replace('/\s+/', ' ', trim($displayName)));
if (count($words) >= 2) {
    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
} else {
    $initials = strtoupper(substr($displayName, 0, 2));
}
?>
<div class="header">
    <div class="main-header">

        <!-- Logo -->
        <div class="header-left active">
            <a href="<?= base_url('dashboard') ?>" class="logo logo-normal">
                <img src="<?= base_url('auth/img/logo.png'); ?>" alt="Img">
            </a>
            <a href="<?= base_url('dashboard') ?>" class="logo logo-white">
                <img src="<?= base_url('auth/img/logo.png'); ?>" alt="Img">
            </a>
            <a href="<?= base_url('dashboard') ?>" class="logo-small">
                <img src="<?= base_url('auth/img/favicon.png'); ?>" alt="Img">
            </a>
        </div>
        <!-- /Logo -->

        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <!-- Header Menu -->
        <ul class="nav user-menu">

            <!-- Search -->
            <li class="nav-item nav-searchinputs">
                <div class="top-nav-search"></div>
            </li>
            <!-- /Search -->

            <?php if (env('feature_elearning', 'true') == 'true'): ?>
            <li class="nav-item">
                <a href="<?= base_url('training') ?>" class="btn btn-light btn-md d-inline-flex align-items-center">
                    <i class="ti ti-book me-1"></i>Training
                </a>
            </li>
            <?php endif; ?>

            <?php if ($isEmployer): ?>
            <li class="nav-item pos-nav">
                <a href="<?= base_url('employer/pricing') ?>" class="btn btn-dark btn-md d-inline-flex align-items-center">
                    <i class="ti ti-wallet me-1"></i>Fund Wallet
                </a>
            </li>
            <?php endif; ?>

            <li class="nav-item nav-item-box">
                <a href="javascript:void(0);" id="btnFullscreen">
                    <i class="ti ti-maximize"></i>
                </a>
            </li>



            <!-- /Notifications -->


            <li class="nav-item dropdown has-arrow main-drop profile-nav">
                <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                    <span class="user-info p-0">
                        <span class="user-letter">
                            <?php if ($hasProfileImage): ?>
                                <img src="<?= (str_starts_with($profileImage, 'http://') || str_starts_with($profileImage, 'https://')) ? $profileImage : base_url($profileImage); ?>" alt="Img" class="img-fluid rounded-circle">
                            <?php else: ?>
                                <span class="d-flex align-items-center justify-content-center text-white rounded-circle fw-bold" style="width: 38px; height: 38px; font-size: 14px; background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);">
                                    <?= esc($initials) ?>
                                </span>
                            <?php endif; ?>
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu menu-drop-user">
                    <div class="profileset d-flex align-items-center">
                        <span class="user-img me-2">
                            <?php if ($hasProfileImage): ?>
                                <img src="<?= (str_starts_with($profileImage, 'http://') || str_starts_with($profileImage, 'https://')) ? $profileImage : base_url($profileImage); ?>" alt="Img" class="img-fluid rounded-circle">
                            <?php else: ?>
                                <span class="d-flex align-items-center justify-content-center text-white rounded-circle fw-bold" style="width: 38px; height: 38px; font-size: 14px; background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);">
                                    <?= esc($initials) ?>
                                </span>
                            <?php endif; ?>
                        </span>
                        <div>
                            <h6 class="fw-medium"><?= esc($displayName) ?></h6>
                            <p><?= esc($email) ?></p>
                        </div>
                    </div>
                    <?php if ($isEmployer) : ?>
                        <a class="dropdown-item" href="<?= base_url('employer/profile') ?>"><i class="ti ti-user-circle me-2"></i>My Profile</a>

                    <?php else : ?>
                        <a class="dropdown-item" href="<?= base_url('candidate/profile') ?>"><i class="ti ti-user-circle me-2"></i>My Profile</a>
                    <?php endif; ?>
                    <!-- <a class="dropdown-item" href="general-settings.html"><i class="ti ti-settings me-2"></i>Settings</a> -->
                    <hr class="my-2">
                    <a class="dropdown-item logout pb-0" href="<?= base_url('logout') ?>"><i class="ti ti-logout me-2"></i>Logout</a>
                </div>
            </li>
        </ul>
        <!-- /Header Menu -->

        <!-- Mobile Menu -->
        <div class="dropdown mobile-user-menu">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <?php if ($isEmployer) : ?>
                    <a class="dropdown-item" href="<?= base_url('employer/profile') ?>">My Profile</a>
                <?php else : ?>
                    <a class="dropdown-item" href="<?= base_url('candidate/profile') ?>">My Profile</a>
                <?php endif; ?>

                <a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
        <!-- /Mobile Menu -->
    </div>
</div>
