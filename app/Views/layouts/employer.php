<?php
/**
 * ════════════════════════════════════════════════════════════════════════
 *  JOBBERRECRUIT — PREMIUM EMPLOYER LAYOUT  v2.0
 *  Extended by all employer dashboard views.
 *  Includes: premium sidebar, frosted-glass topbar, SVG sprite,
 *            mobile scrim, mobile sticky CTA bar, slim footer.
 * ════════════════════════════════════════════════════════════════════════
 */

// ── Resolve user / employer data ──────────────────────────────────────
$user        = $user ?? auth()->user();
$isEmployer  = ($user?->user_type === 'employer');
$currentUri  = trim(uri_string(), '/');

function empIsActive(string $path): string {
    return trim($path, '/') === trim(uri_string(), '/') ? 'page' : '';
}
function empIsActiveStart(string $path): string {
    return str_starts_with(trim(uri_string(), '/'), trim($path, '/')) ? 'page' : '';
}

// Logo / display name
$displayName = 'Employer';
$email       = $user->email ?? '';
$logoPath    = '';
$hasLogo     = false;

if ($isEmployer && isset($employer)) {
    $displayName = !empty($employer->company_name) ? $employer->company_name : 'Employer';
    $logoPath    = $employer->logo ?? '';
}

// Initials
$initials = '';
$words    = explode(' ', preg_replace('/\s+/', ' ', trim($displayName)));
$initials = count($words) >= 2
    ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
    : strtoupper(substr($displayName, 0, 2));

// Profile image resolution
if ($logoPath) {
    if (filter_var($logoPath, FILTER_VALIDATE_URL) || str_starts_with($logoPath, 'http')) {
        $hasLogo     = true;
    } elseif (file_exists(FCPATH . $logoPath)) {
        $hasLogo     = true;
    }
}
$logoSrc = $hasLogo
    ? ((str_starts_with($logoPath, 'http')) ? $logoPath : base_url($logoPath))
    : null;

// Wallet balance
$walletBalance = 0;
if ($isEmployer && isset($user)) {
    try {
        $walletRow     = model(\App\Models\WalletModel::class)
            ->where('user_id', $user->id)->first();
        $walletBalance = $walletRow ? (float) $walletRow->balance : 0;
    } catch (\Throwable $e) {
        $walletBalance = 0;
    }
}
$walletFormatted = '₦' . number_format($walletBalance, 2);

// Pending application count for sidebar badge
$pendingCount = $pendingApps ?? 0;

// Notifications resolution
$unreadNotifsCount = 0;
$recentNotifs = [];
if ($isEmployer && isset($user)) {
    try {
        $empModel = model(\App\Models\EmployerModel::class)->where('user_id', $user->id)->first();
        if ($empModel) {
            $jobNotifModel = model(\App\Models\JobNotificationModel::class);
            $unreadNotifsCount = $jobNotifModel->where('employer_id', $empModel->id)->where('is_read', 0)->countAllResults();
            $recentNotifs = $jobNotifModel->where('employer_id', $empModel->id)->orderBy('created_at', 'DESC')->limit(4)->findAll();
        }
    } catch (\Throwable $e) {
        $unreadNotifsCount = 0;
        $recentNotifs = [];
    }
}

// Dashboard alone uses global search in the reference design. Every other
// employer screen uses a route-aware breadcrumb.
$dashboardPath = trim(uri_string(), '/');
$employerTitles = [
    'employer/applications/view' => 'Application Details',
    'employer/candidates/view'   => 'Candidate Profile',
    'employer/profile/edit'      => 'Edit Company Profile',
    'employer/post-job'          => 'Post a Job',
    'employer/jobs/create'       => 'Post a Job',
    'employer/candidate-alerts'  => 'Candidate Alerts',
    'employer/applications'      => 'Applications',
    'employer/transactions'      => 'Transactions',
    'employer/candidates'        => 'Candidates Search',
    'employer/referrals'         => 'Referral Program',
    'employer/notifications'     => 'Candidate Alerts',
    'employer/settings/security' => 'Security Settings',
    'employer/settings'          => 'General Settings',
    'employer/profile'           => 'Company Profile',
    'employer/pricing'           => 'Billing & Plans',
    'employer/bundles'           => 'Billing & Plans',
    'employer/messages'          => 'Messages',
    'employer/jobs'              => 'My Jobs',
];
$resolvedPageTitle = '';
if ($dashboardPath !== 'employer' && $dashboardPath !== 'employer/dashboard') {
    foreach ($employerTitles as $routePrefix => $routeTitle) {
        if ($dashboardPath === $routePrefix || str_starts_with($dashboardPath, $routePrefix . '/')) {
            $resolvedPageTitle = $routeTitle;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en-NG">
<head>
<script>
    // Polyfill modern APIs for backward browser support
    if (!window.Promise || !window.fetch || !Object.assign || !Array.from) {
        document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.6/es6-shim.min.js"><\/script>');
        document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/3.6.20/fetch.min.js"><\/script>');
    }
</script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#0A2F57">
<meta name="color-scheme" content="light">
<meta name="robots" content="noindex, nofollow, noarchive">
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-header" content="<?= csrf_token() ?>">
<link rel="shortcut icon" href="<?= base_url('auth/img/favicon.png') ?>" type="image/x-icon">
<link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('auth/img/apple-touch-icon.png') ?>">

<title><?= esc($title ?? 'Dashboard') ?> – JobberRecruit</title>

<!-- Fonts (non-render-blocking) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>

<!-- Employer Design System -->
<link rel="stylesheet" href="<?= base_url('css/employer-shell.css') ?>?v=<?= time() ?>">
<!-- Section 8 — Native Mobile App Feel -->
<link rel="stylesheet" href="<?= base_url('css/mobile-app.css') ?>?v=<?= time() ?>">
<!-- Tabler Icons (needed for some CI4 view remnants) -->
<link rel="stylesheet" href="<?= base_url('auth/plugins/tabler-icons/tabler-icons.min.css') ?>">
<!-- toastr (used by AJAX-driven employer forms) -->
<link rel="stylesheet" href="<?= base_url('auth/css/toastr.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/modal-scroll.css') ?>?v=<?= time() ?>">

<!-- Page-level styles -->
<?= $this->renderSection('styles') ?>
</head>

<body class="emp-shell">
<a class="skip-link" href="#main-content">Skip to main content</a>

<!-- ══ SVG SPRITE ══ -->
<?= $this->include('partials/svg_sprites') ?>

<!-- ══ MOBILE SCRIM ══ -->
<div class="emp-scrim" id="emp-scrim" hidden></div>

<div class="emp-shell-wrap">

  <!-- ════════ SIDEBAR ════════ -->
  <aside class="emp-sidebar" id="emp-sidebar" aria-label="Employer navigation">
    <div class="sb-head">
      <a href="<?= base_url('employer/dashboard') ?>" class="sb-logo" aria-label="JobberRecruit dashboard">
        <img src="<?= base_url('images/logo.png') ?>" alt="JobberRecruit" class="sb-logo-img">
      </a>
      <button class="sb-close" id="sb-close" aria-label="Close menu">
        <svg aria-hidden="true"><use href="#i-x"/></svg>
      </button>
    </div>

    <nav class="sb-scroll" aria-label="Employer menu">

      <!-- RECRUITMENT HUB -->
      <div class="sb-group">
        <div class="sb-label">Recruitment Hub</div>
        <a class="sb-link" href="<?= base_url('employer/dashboard') ?>"
           <?= empIsActive('employer/dashboard') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-grid"/></svg> Dashboard
        </a>
        <a class="sb-link" href="<?= base_url('employer/jobs') ?>"
           <?= empIsActiveStart('employer/jobs') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-briefcase"/></svg> My Jobs
        </a>
        <a class="sb-link" href="<?= base_url('employer/applications') ?>"
           <?= empIsActiveStart('employer/applications') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-doc"/></svg> Applications
          <?php if ($pendingCount > 0): ?>
            <span class="sb-count"><?= $pendingCount ?></span>
          <?php endif; ?>
        </a>
        <a class="sb-link" href="<?= base_url('employer/candidates') ?>"
           <?= empIsActive('employer/candidates') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-search-user"/></svg> Candidates Search
        </a>
        <a class="sb-link" href="<?= base_url('employer/tests') ?>"
           <?= empIsActiveStart('employer/tests') || empIsActiveStart('employer/aptitude-tests') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-share"/></svg> Screening Tests
        </a>
      </div>

      <!-- ORGANIZATION SPACE -->
      <div class="sb-group">
        <div class="sb-label">Organization Space</div>
        <a class="sb-link" href="<?= base_url('employer/profile') ?>"
           <?= empIsActiveStart('employer/profile') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-building"/></svg> Company Profile
        </a>
        <?php if (get_site_setting('feature_messaging', true)): ?>
        <a class="sb-link" href="<?= base_url('employer/messages') ?>"
           <?= empIsActive('employer/messages') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-chat"/></svg> Messages
        </a>
        <?php endif; ?>
        <?php if (get_site_setting('feature_referrals', true)): ?>
        <a class="sb-link" href="<?= base_url('employer/referrals') ?>"
           <?= empIsActive('employer/referrals') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-share"/></svg> Referral Program
        </a>
        <?php endif; ?>
      </div>

      <!-- BILLING & ALERTS -->
      <div class="sb-group">
        <div class="sb-label">Billing &amp; Alerts</div>
        <a class="sb-link" href="<?= base_url('employer/pricing') ?>"
           <?= empIsActive('employer/pricing') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-card"/></svg> Billing &amp; Plans
        </a>
        <a class="sb-link" href="<?= base_url('employer/transactions') ?>"
           <?= empIsActiveStart('employer/transactions') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-receipt"/></svg> Transactions
        </a>
        <a class="sb-link" href="<?= base_url('employer/notifications') ?>"
           <?= empIsActive('employer/notifications') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-bell"/></svg> Notifications
        </a>
      </div>

      <!-- TRAINING -->
      <?php if (get_site_setting('feature_elearning', true)): ?>
      <div class="sb-group">
        <div class="sb-label">Training</div>
        <a class="sb-link" href="<?= base_url('training') ?>"
           <?= empIsActiveStart('training') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-book"/></svg> Training Centre
        </a>
      </div>
      <?php endif; ?>

      <!-- SETTINGS -->
      <!-- SUPPORT -->
      <div class="sb-group">
        <div class="sb-label">Support</div>
        <a class="sb-link" href="https://wa.me/2349014808902?text=Hello%20JobberRecruit%20Support%2C%20I%20am%20an%20employer%20and%20need%20assistance." target="_blank" rel="noopener noreferrer" style="color: #22c55e !important; font-weight: 600;">
          <svg aria-hidden="true" style="color: #22c55e !important; fill: currentColor;"><use href="#i-whatsapp"/></svg> WhatsApp Support
        </a>
      </div>

      <!-- SETTINGS -->
      <div class="sb-group">
        <div class="sb-label">Settings</div>
        <a class="sb-link" href="<?= base_url('employer/settings') ?>"
           <?= empIsActive('employer/settings') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-cog"/></svg> General Settings
        </a>
        <a class="sb-link" href="<?= base_url('logout') ?>">
          <svg aria-hidden="true"><use href="#i-logout"/></svg> Sign Out
        </a>
      </div>

      <!-- WALLET CARD -->
      <div class="sb-wallet">
        <div class="sb-wallet-label">Wallet balance</div>
        <div class="sb-wallet-amt"><?= esc($walletFormatted) ?></div>
        <a href="<?= base_url('employer/wallet') ?>" class="emp-btn emp-btn-ghost-w emp-btn-sm">
          <svg aria-hidden="true"><use href="#i-wallet"/></svg> Fund Wallet
        </a>
      </div>

    </nav>
    <div class="sb-foot">&copy; <?= date('Y') ?> JobberRecruit</div>
  </aside>

  <!-- ════════ MAIN AREA ════════ -->
  <div class="emp-main">

    <!-- TOPBAR -->
    <header class="emp-topbar" role="banner">
      <div class="topbar-inner">
        <button class="hamburger" id="emp-hamburger" aria-label="Open menu" aria-controls="emp-sidebar" aria-expanded="false">
          <svg aria-hidden="true"><use href="#i-menu"/></svg>
        </button>

        <!-- Mobile Company / Brand Logo -->
        <a href="<?= base_url('employer/dashboard') ?>" class="tb-mob-logo" aria-label="JobberRecruit dashboard">
          <img src="<?= base_url('images/logo.png') ?>" alt="JobberRecruit" style="height:28px; width:auto; object-fit:contain;">
        </a>

        <!-- Breadcrumb (tb-crumb) shown when page_title is set, otherwise fallback to search -->
        <?php if ($resolvedPageTitle !== ''): ?>
          <nav class="tb-crumb" aria-label="Breadcrumb">
            <a href="<?= base_url('employer/dashboard') ?>">Dashboard</a>
            <svg aria-hidden="true"><use href="#i-arrow-r"/></svg>
            <b><?= esc($resolvedPageTitle) ?></b>
          </nav>
        <?php else: ?>
          <div class="tb-search" role="search">
            <svg aria-hidden="true"><use href="#i-search"/></svg>
            <input type="search" placeholder="Search jobs, applicants, candidates…" aria-label="Search dashboard">
          </div>
        <?php endif; ?>

        <div class="tb-right">
          <!-- Wallet chip -->
          <a href="<?= base_url('employer/wallet') ?>" class="tb-wallet" aria-label="Wallet balance <?= esc($walletFormatted) ?>">
            <svg aria-hidden="true"><use href="#i-wallet"/></svg>
            <span class="lbl">Wallet</span> <b><?= esc($walletFormatted) ?></b>
          </a>

          <!-- Notifications dropdown -->
          <div class="tb-drop tb-drop--notif" id="notif-drop">
            <button type="button" class="tb-icon" aria-label="Notifications" aria-haspopup="true" aria-expanded="false">
              <svg aria-hidden="true"><use href="#i-bell"/></svg>
              <?php if (($pendingCount > 0) || (!empty($unreadNotifsCount) && $unreadNotifsCount > 0)): ?><span class="tb-dot" aria-hidden="true"></span><?php endif; ?>
            </button>
            <div class="tb-menu tb-menu--notif" role="menu" aria-label="Notifications">
              <div class="tm-head" style="display:flex;align-items:center;justify-content:space-between;">
                <div class="tm-name">Notifications</div>
                <a href="<?= base_url('employer/notifications') ?>" style="font-size:0.75rem;font-weight:600;padding:0;min-height:auto;color:var(--brand);">View all</a>
              </div>
              <div class="tn-list">
                <?php if ($pendingCount > 0): ?>
                  <a href="<?= base_url('employer/applications') ?>" class="tn-item" role="menuitem">
                    <span class="tn-ic" aria-hidden="true"><svg><use href="#i-doc"/></svg></span>
                    <div><b><?= $pendingCount ?> pending application<?= $pendingCount > 1 ? 's' : '' ?></b><i>Require your review</i></div>
                  </a>
                <?php endif; ?>
                <?php if (!empty($recentNotifs)): ?>
                  <?php foreach ($recentNotifs as $n): ?>
                    <a href="<?= base_url('employer/notifications') ?>" class="tn-item" role="menuitem">
                      <span class="tn-ic" aria-hidden="true"><svg><use href="#i-bell"/></svg></span>
                      <div><b><?= esc($n->title ?? $n['title'] ?? 'Notification') ?></b><i><?= esc($n->message ?? $n['message'] ?? '') ?></i></div>
                    </a>
                  <?php endforeach; ?>
                <?php elseif ($pendingCount <= 0): ?>
                  <div style="padding:14px 12px;font-size:.82rem;color:var(--muted);text-align:center;">No new notifications</div>
                <?php endif; ?>
              </div>
              <hr>
              <a href="<?= base_url('employer/notifications') ?>" role="menuitem" style="justify-content:center;font-weight:600;color:var(--brand)">View all alerts &rarr;</a>
            </div>
          </div>

          <!-- Account dropdown -->
          <div class="tb-drop" id="account-drop">
            <button type="button" class="tb-avatar" aria-label="Account menu — <?= esc($displayName) ?>" aria-haspopup="true" aria-expanded="false">
              <?php if ($logoSrc): ?>
                <img src="<?= esc($logoSrc) ?>" alt="<?= esc($displayName) ?>" style="width:44px;height:44px;border-radius:50%;object-fit:cover;">
              <?php else: ?>
                <?= esc($initials) ?>
              <?php endif; ?>
            </button>
            <div class="tb-menu" role="menu" aria-label="Account">
              <div class="tm-head">
                <div class="tm-name"><?= esc($displayName) ?></div>
                <div class="tm-mail"><?= esc($email) ?></div>
              </div>
              <a href="<?= base_url('employer/profile') ?>" role="menuitem">
                <svg aria-hidden="true"><use href="#i-building"/></svg> Company Profile
              </a>
              <a href="<?= base_url('employer/profile/edit') ?>" role="menuitem">
                <svg aria-hidden="true"><use href="#i-note"/></svg> Edit Company Profile
              </a>
              <a href="<?= base_url('employer/pricing') ?>" role="menuitem">
                <svg aria-hidden="true"><use href="#i-card"/></svg> Billing &amp; Plans
              </a>
              <a href="<?= base_url('employer/settings') ?>" role="menuitem">
                <svg aria-hidden="true"><use href="#i-cog"/></svg> General Settings
              </a>
              <hr>
              <a href="<?= base_url('logout') ?>" class="tm-out" role="menuitem">
                <svg aria-hidden="true"><use href="#i-logout"/></svg> Sign Out
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="emp-content" id="main-content">
      <?= $this->renderSection('content') ?>
    </main>

    <!-- SLIM FOOTER -->
    <footer class="dash-foot">
      <span>&copy; <?= date('Y') ?> JobberRecruit &middot; Jobber Recruit Ltd</span>
      <nav aria-label="Footer links">
        <a href="<?= base_url('faq') ?>">Help Centre</a>
        <a href="<?= base_url('privacy-policy') ?>">Privacy</a>
        <a href="<?= base_url('terms-and-conditions') ?>">Terms</a>
        <a href="<?= base_url('contact-us') ?>">Contact</a>
      </nav>
    </footer>

  </div><!-- /.emp-main -->
</div><!-- /.emp-shell-wrap -->

<!-- MOBILE STICKY CTA BAR -->
<div class="mobile-cta" id="mobile-cta">
  <?= $this->renderSection('mobile_cta') ?>
</div>

<!-- Floating WhatsApp Support Button -->
<a href="https://wa.me/2349014808902?text=Hello%20JobberRecruit%20Support%2C%20I%20am%20an%20employer%20and%20need%20assistance." target="_blank" rel="noopener noreferrer" class="emp-wa-float" title="Chat with JobberRecruit Employer Support on WhatsApp" aria-label="WhatsApp Support">
  <svg class="emp-wa-icon" viewBox="0 0 24 24" aria-hidden="true">
    <path fill="currentColor" d="M17.472 14.382c-.301-.15-1.781-.879-2.057-.98-.276-.1-.477-.15-.677.15-.2.301-.777.98-.952 1.18-.176.2-.351.226-.652.075-.301-.15-1.272-.469-2.423-1.496-.895-.799-1.5-1.786-1.675-2.087-.176-.301-.019-.464.132-.614.136-.135.301-.351.451-.527.151-.175.201-.301.301-.501.101-.2.05-.376-.025-.526-.075-.15-.677-1.633-.927-2.235-.244-.587-.492-.507-.677-.517-.175-.008-.376-.01-.576-.01-.201 0-.526.075-.802.376-.276.301-1.053 1.028-1.053 2.508 0 1.48 1.078 2.909 1.229 3.109.15.2 2.122 3.24 5.14 4.544.718.31 1.279.496 1.716.635.722.23 1.379.197 1.9.12.58-.087 1.781-.727 2.032-1.43.25-.702.25-1.304.175-1.43-.075-.125-.276-.2-.576-.35zm-5.435 7.618a9.948 9.948 0 0 1-5.074-1.39l-.364-.216-3.771.989 1.006-3.676-.237-.378a9.957 9.957 0 0 1-1.527-5.329c0-5.514 4.486-10 10-10 2.671 0 5.182 1.04 7.071 2.929 1.889 1.889 2.929 4.4 2.929 7.071 0 5.514-4.486 10-10 10zm8.485-18.485C18.27 1.263 15.247 0 12.037 0 5.4 0 0 5.4 0 12.037c0 2.12.553 4.188 1.604 6.01L0 24l6.113-1.604a12.007 12.007 0 0 0 5.924 1.564h.005c6.637 0 12.037-5.4 12.037-12.037 0-3.21-1.263-6.233-3.559-8.485z"/>
  </svg>
  <span class="emp-wa-label">Contact Us on WhatsApp</span>
</a>

<style>
.publish-bar {
  z-index: 1060 !important;
}

.emp-wa-float {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 1050;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #25D366;
  color: #ffffff !important;
  padding: 12px 18px;
  border-radius: 50px;
  box-shadow: 0 4px 18px rgba(37, 211, 102, 0.45);
  text-decoration: none !important;
  font-family: 'Inter', -apple-system, sans-serif;
  font-weight: 600;
  font-size: 0.88rem;
  line-height: 1;
  transition: bottom 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s ease, background-color 0.2s ease;
  -webkit-tap-highlight-color: transparent;
}
.emp-wa-float:hover {
  background: #20BA5A;
  transform: translateY(-2px) scale(1.02);
  box-shadow: 0 6px 22px rgba(37, 211, 102, 0.6);
  color: #ffffff !important;
}
.emp-wa-icon {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
  fill: #ffffff;
}

/* Elevate WhatsApp support button above publish-bar and mobile-cta so it never covers buttons */
body:has(.publish-bar) .emp-wa-float,
body.has-publish-bar .emp-wa-float {
  bottom: calc(88px + env(safe-area-inset-bottom, 0px)) !important;
}

body:has(.publish-bar.bar-hidden) .emp-wa-float,
body.has-publish-bar-hidden .emp-wa-float {
  bottom: 24px !important;
}

@media (max-width: 1024px) {
  body:has(#mobile-cta:not(.hidden):not(:empty)) .emp-wa-float,
  body.has-mobile-cta .emp-wa-float {
    bottom: calc(78px + env(safe-area-inset-bottom, 0px)) !important;
  }
}

@media (max-width: 768px) {
  .emp-wa-float {
    bottom: calc(74px + env(safe-area-inset-bottom, 0px));
    right: 16px;
    left: auto;
    padding: 11px;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(37, 211, 102, 0.5);
  }
  .emp-wa-float .emp-wa-label {
    display: none;
  }
  .emp-wa-icon {
    width: 26px;
    height: 26px;
  }
  body:has(#mobile-cta:not(.hidden):not(:empty)) .emp-wa-float,
  body.has-mobile-cta .emp-wa-float {
    bottom: calc(82px + env(safe-area-inset-bottom, 0px)) !important;
  }
}

/* iOS Safari & Chrome anti-blur rendering fix & touch dropdowns */
@supports (-webkit-touch-callout: none) {
  html, body.emp-shell {
    -webkit-font-smoothing: subpixel-antialiased !important;
    text-rendering: optimizeLegibility !important;
  }
  .ai-hero::before, .emp-sidebar::before {
    -webkit-mask-image: none !important;
    mask-image: none !important;
    opacity: .25 !important;
  }
  @media (max-width: 1024px) {
    .emp-sidebar:not(.open) {
      visibility: hidden !important;
      pointer-events: none !important;
    }
  }
  .card, .stat, .stat-card, .ai-hero, .greet-row, .notice, .table-card {
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
  }
}

body.emp-shell, .emp-shell-wrap, .emp-main, .emp-content, .topbar-inner, .emp-topbar {
  -webkit-filter: none !important;
  filter: none !important;
  -webkit-perspective: none !important;
  perspective: none !important;
  -webkit-backdrop-filter: none !important;
  backdrop-filter: none !important;
}
.tb-icon, .tb-avatar {
  cursor: pointer !important;
  touch-action: manipulation !important;
  -webkit-tap-highlight-color: transparent !important;
}
.tb-avatar {
  font-size: 0.86rem !important;
  line-height: 1 !important;
}
.tb-icon svg, .tb-avatar * {
  pointer-events: none !important;
}
.tb-drop:not(.open) .tb-menu {
  display: none !important;
  opacity: 0 !important;
  visibility: hidden !important;
  pointer-events: none !important;
}
.tb-drop.open .tb-menu {
  display: block !important;
  opacity: 1 !important;
  visibility: visible !important;
  pointer-events: auto !important;
  z-index: 99999 !important;
  transform: translateY(0) !important;
}
/* Guarantee the dropdown panel is never clipped by an ancestor overflow */
.emp-topbar,
.topbar-inner,
.tb-right,
.tb-drop {
  overflow: visible !important;
}
</style>

  <!-- Mobile Bottom App Navigation (Hidden on Employer Dashboard) -->
  <?php // echo $this->include('partials/mobile_bottom_nav'); ?>

<!-- SIDEBAR & UI SCRIPTS -->
<script>
(function() {
  'use strict';
  var sidebar  = document.getElementById('emp-sidebar'),
      scrim    = document.getElementById('emp-scrim'),
      burger   = document.getElementById('emp-hamburger'),
      closeBtn = document.getElementById('sb-close');

  function toggleMobileMenu(forceState) {
    if (!sidebar) return;
    var isOpen = typeof forceState === 'boolean' ? forceState : !sidebar.classList.contains('open');
    
    if (isOpen) {
      sidebar.classList.add('open');
      if (scrim) {
        scrim.removeAttribute('hidden');
        scrim.classList.add('show');
      }
      if (burger) burger.setAttribute('aria-expanded', 'true');
      document.documentElement.classList.add('menu-open');
      document.body.classList.add('menu-open');
    } else {
      sidebar.classList.remove('open');
      if (scrim) {
        scrim.classList.remove('show');
      }
      if (burger) burger.setAttribute('aria-expanded', 'false');
      document.documentElement.classList.remove('menu-open');
      document.body.classList.remove('menu-open');
    }
  }

  window.toggleEmployerSidebar = toggleMobileMenu;
  window.toggleMobileMenu = toggleMobileMenu;

  function handleOpen(e) {
    if (e) e.preventDefault();
    toggleMobileMenu(true);
  }

  function handleClose(e) {
    if (e) e.preventDefault();
    toggleMobileMenu(false);
  }

  if (burger) {
    burger.addEventListener('click', handleOpen);
  }
  if (closeBtn) {
    closeBtn.addEventListener('click', handleClose);
  }
  if (scrim) {
    scrim.addEventListener('click', handleClose);
    scrim.addEventListener('touchstart', handleClose, { passive: true });
  }
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && sidebar && sidebar.classList.contains('open')) {
      toggleMobileMenu(false);
    }
  });

  // Mobile CTA: hide on scroll down, show on scroll up
  var bar = document.getElementById('mobile-cta'),
      lastY = window.scrollY, ticking = false;
  if (bar) {
    window.addEventListener('scroll', function() {
      if (ticking) return; ticking = true;
      requestAnimationFrame(function() {
        var y = window.scrollY,
            nearBottom = (window.innerHeight + y) >= (document.documentElement.scrollHeight - 120);
        if (nearBottom || y < lastY || y < 60) { bar.classList.remove('hidden'); }
        else if (y > lastY + 8) { bar.classList.add('hidden'); }
        lastY = y; ticking = false;
      });
    }, { passive: true });
  }
})();

// Topbar dropdowns — touch-safe for mobile (iOS/Android) + desktop click
(function() {
  'use strict';
  var drops = document.querySelectorAll('.tb-drop');
  if (!drops.length) return;

  // Flag set while a touch interaction is inside a drop; prevents the
  // document-level touchend listener from immediately closing the dropdown
  // that the button touchstart just opened (a race on iOS Safari).
  var touchInsideDrop = false;

  function closeAllDrops() {
    drops.forEach(function(d) {
      d.classList.remove('open');
      var b = d.querySelector('button, .tb-icon, .tb-avatar');
      if (b) b.setAttribute('aria-expanded', 'false');
    });
  }

  drops.forEach(function(d) {
    var btn = d.querySelector('button, .tb-icon, .tb-avatar');
    if (!btn) return;

    var menu = d.querySelector('.tb-menu');

    // Keep clicks/touches inside the open menu from closing it immediately.
    if (menu) {
      menu.addEventListener('click', function(e) { e.stopPropagation(); });
      menu.addEventListener('touchstart', function() { touchInsideDrop = true; }, { passive: true });
      menu.addEventListener('touchend',   function() { touchInsideDrop = false; }, { passive: true });
    }

    var lastToggleTime = 0;

    function handleToggle(e) {
      if (e) { e.preventDefault(); e.stopPropagation(); }
      var wasOpen = d.classList.contains('open');
      closeAllDrops();
      if (!wasOpen) {
        d.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
        touchInsideDrop = true;   // mark: we are now inside an open drop
      }
      lastToggleTime = Date.now();
    }

    // Touch: open on touchstart so it feels instant on mobile.
    btn.addEventListener('touchstart', function(e) {
      touchInsideDrop = true;
      handleToggle(e);
    }, { passive: false });

    btn.addEventListener('touchend', function() {
      // Short grace period so the document touchend doesn't close right away.
      setTimeout(function() { touchInsideDrop = false; }, 300);
    }, { passive: true });

    // Mouse click (desktop): guard against the synthetic click fired after
    // touchstart on mobile (which would double-toggle).
    btn.addEventListener('click', function(e) {
      if (Date.now() - lastToggleTime < 400) {
        e.preventDefault();
        e.stopPropagation();
        return;
      }
      handleToggle(e);
    });
  });

  // Close when touching/clicking anywhere outside a tb-drop.
  document.addEventListener('touchend', function() {
    if (touchInsideDrop) { touchInsideDrop = false; return; }
    closeAllDrops();
  }, { passive: true });

  document.addEventListener('click', function(e) {
    if (e.target && e.target.closest && e.target.closest('.tb-drop')) return;
    closeAllDrops();
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAllDrops();
  });
})();

// Dynamically elevate WhatsApp floating button above sticky publish-bar and mobile-cta
(function() {
  var wa = document.querySelector('.emp-wa-float');
  if (!wa) return;
  var pubBar = document.querySelector('.publish-bar');
  var mobileCta = document.getElementById('mobile-cta');

  function syncWaPosition() {
    var isDesktop = window.innerWidth > 1024;
    var pubVisible = pubBar && isDesktop && !pubBar.classList.contains('bar-hidden');
    var mobVisible = mobileCta && !isDesktop && mobileCta.children.length > 0 && !mobileCta.classList.contains('hidden');

    if (pubVisible) {
      document.body.classList.add('has-publish-bar');
      document.body.classList.remove('has-publish-bar-hidden', 'has-mobile-cta');
      wa.style.bottom = 'calc(88px + env(safe-area-inset-bottom, 0px))';
    } else if (mobVisible) {
      document.body.classList.add('has-mobile-cta');
      document.body.classList.remove('has-publish-bar', 'has-publish-bar-hidden');
      wa.style.bottom = 'calc(80px + env(safe-area-inset-bottom, 0px))';
    } else {
      document.body.classList.remove('has-publish-bar', 'has-mobile-cta');
      if (pubBar && isDesktop && pubBar.classList.contains('bar-hidden')) {
        document.body.classList.add('has-publish-bar-hidden');
      }
      wa.style.bottom = '';
    }
  }

  syncWaPosition();
  window.addEventListener('resize', syncWaPosition);
  window.addEventListener('scroll', syncWaPosition, { passive: true });
  if (window.MutationObserver) {
    var observer = new MutationObserver(syncWaPosition);
    if (pubBar) observer.observe(pubBar, { attributes: true, attributeFilter: ['class', 'style'] });
    if (mobileCta) observer.observe(mobileCta, { attributes: true, childList: true, attributeFilter: ['class', 'style'] });
  }
})();
</script>

<!-- jQuery + Bootstrap + toastr (required by AJAX-driven employer forms and modals) -->
<script src="<?= base_url('auth/js/jquery-3.7.1.min.js') ?>"></script>
<script src="<?= base_url('auth/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('auth/js/toastr.min.js') ?>"></script>
<script src="<?= base_url('assets/js/autosave.js') ?>"></script>
<!-- Page-level scripts -->
<?= $this->renderSection('scripts') ?>
</body>
</html>
