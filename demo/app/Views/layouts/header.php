<?php
$user = $user ?? auth()->user();
$isEmployer = ($user?->user_type === 'employer');
$profileImage = 'images/favicon.png';
$displayName = 'User';
$email = $user->email ?? '';

if ($isEmployer) {
    $profileImage = isset($employer) && ! empty($employer->logo) ? $employer->logo : $profileImage;
    $displayName = isset($employer) && ! empty($employer->company_name) ? $employer->company_name : 'Employer';
} else {
    $profileImage = isset($candidate) && ! empty($candidate->profile_picture) ? $candidate->profile_picture : $profileImage;
    $displayName = isset($candidate) && ! empty($candidate->full_name) ? $candidate->full_name : 'Candidate';
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

            <!-- <li class="nav-item dropdown link-nav">
                <a href="javascript:void(0);" class="btn btn-primary btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                    <i class="ti ti-circle-plus me-1"></i>Add New
                </a>
                <div class="dropdown-menu dropdown-xl dropdown-menu-center">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <a href="category-list.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-brand-codepen"></i>
                                </span>
                                <p>Category</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="add-product.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-square-plus"></i>
                                </span>
                                <p>Product</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="category-list.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-shopping-bag"></i>
                                </span>
                                <p>Purchase</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="online-orders.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-shopping-cart"></i>
                                </span>
                                <p>Sale</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="expense-list.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-file-text"></i>
                                </span>
                                <p>Expense</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="quotation-list.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-device-floppy"></i>
                                </span>
                                <p>Quotation</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="sales-returns.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-copy"></i>
                                </span>
                                <p>Return</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="users.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-user"></i>
                                </span>
                                <p>User</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="customers.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-users"></i>
                                </span>
                                <p>Customer</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="sales-report.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-shield"></i>
                                </span>
                                <p>Biller</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="suppliers.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-user-check"></i>
                                </span>
                                <p>Supplier</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="stock-transfer.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-truck"></i>
                                </span>
                                <p>Transfer</p>
                            </a>
                        </div>
                    </div>
                </div>
            </li> -->

            <li class="nav-item pos-nav">
                <a href="<?= base_url('employer/pricing') ?>" class="btn btn-dark btn-md d-inline-flex align-items-center">
                    <i class="ti ti-wallet me-1"></i>Fund Wallet
                </a>
            </li>

            <li class="nav-item nav-item-box">
                <a href="javascript:void(0);" id="btnFullscreen">
                    <i class="ti ti-maximize"></i>
                </a>
            </li>
            <!-- <li class="nav-item nav-item-box">
                <a href="email.html">
                    <i class="ti ti-mail"></i>
                    <span class="badge rounded-pill">1</span>
                </a>
            </li> -->
            <!-- Notifications -->
            <!-- <li class="nav-item dropdown nav-item-box">
                <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <i class="ti ti-bell"></i>
                    <span class="badge rounded-pill">1</span>
                </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <h5 class="notification-title">Notifications</h5>
                        <a href="javascript:void(0)" class="clear-noti">Mark all as read</a>
                    </div>
                    <div class="noti-content">
                        <ul class="notification-list">
                            <li class="notification-message">
                                <a href="activities.html">
                                    <div class="media d-flex">
                                        <span class="avatar flex-shrink-0">
                                            <img alt="Img" src="assets/img/profiles/avatar-13.jpg">
                                        </span>
                                        <div class="flex-grow-1">
                                            <p class="noti-details"><span class="noti-title">James Kirwin</span> confirmed his order. Order No: #78901.Estimated delivery: 2 days</p>
                                            <p class="noti-time">4 mins ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="activities.html">
                                    <div class="media d-flex">
                                        <span class="avatar flex-shrink-0">
                                            <img alt="Img" src="assets/img/profiles/avatar-03.jpg">
                                        </span>
                                        <div class="flex-grow-1">
                                            <p class="noti-details"><span class="noti-title">Leo Kelly</span> cancelled his order scheduled for 17 Jan 2025</p>
                                            <p class="noti-time">10 mins ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="activities.html" class="recent-msg">
                                    <div class="media d-flex">
                                        <span class="avatar flex-shrink-0">
                                            <img alt="Img" src="assets/img/profiles/avatar-17.jpg">
                                        </span>
                                        <div class="flex-grow-1">
                                            <p class="noti-details">Payment of $50 received for Order #67890 from <span class="noti-title">Antonio Engle</span></p>
                                            <p class="noti-time">05 mins ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="activities.html" class="recent-msg">
                                    <div class="media d-flex">
                                        <span class="avatar flex-shrink-0">
                                            <img alt="Img" src="assets/img/profiles/avatar-02.jpg">
                                        </span>
                                        <div class="flex-grow-1">
                                            <p class="noti-details"><span class="noti-title">Andrea</span> confirmed his order. Order No: #73401.Estimated delivery: 3 days</p>
                                            <p class="noti-time">4 mins ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="topnav-dropdown-footer d-flex align-items-center gap-3">
                        <a href="#" class="btn btn-secondary btn-md w-100">Cancel</a>
                        <a href="activities.html" class="btn btn-primary btn-md w-100">View all</a>
                    </div>
                </div>
            </li> -->
            <!-- /Notifications -->

            <!-- <li class="nav-item nav-item-box">
                <a href="general-settings.html"><i class="ti ti-settings"></i></a>
            </li> -->
            <li class="nav-item dropdown has-arrow main-drop profile-nav">
                <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                    <span class="user-info p-0">
                        <span class="user-letter">
                            <img src="<?= base_url($profileImage); ?>" alt="Img" class="img-fluid">
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu menu-drop-user">
                    <div class="profileset d-flex align-items-center">
                        <span class="user-img me-2">
                            <img src="<?= base_url($profileImage); ?>" alt="Img" class="img-fluid">
                        </span>
                        <div>
                            <h6 class="fw-medium"><?= esc($displayName) ?></h6>
                            <p><?= esc($email) ?></p>
                        </div>
                    </div>
                    <?php if ($isEmployer) : ?>
                        <a class="dropdown-item" href="<?= base_url('employer/profile') ?>"><i class="ti ti-user-circle me-2"></i>My Profile</a>
                        <!-- <a class="dropdown-item" href="sales-report.html"><i class="ti ti-file-text me-2"></i>Reports</a> -->
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
                <!-- <a class="dropdown-item" href="general-settings.html">Settings</a> -->
                <a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
        <!-- /Mobile Menu -->
    </div>
</div>
