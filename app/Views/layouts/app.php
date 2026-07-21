<?php
/**
 * ════════════════════════════════════════════════════════════════════════
 *  JOBBERRECRUIT — PREMIUM DASHBOARD LAYOUT (candidate + shared views)
 *  Same design system & shell as layouts/employer.php (employer-shell.css).
 *  Keeps the legacy JS/CSS stack loaded because content views still use
 *  Bootstrap components, DataTables, Select2, Quill, toastr, etc.
 * ════════════════════════════════════════════════════════════════════════
 */

// ── Resolve user / role data ──────────────────────────────────────────
$user       = $user ?? auth()->user();
$isEmployer = ($user?->user_type === 'employer');

function dashIsActive(string $path): string {
    return trim($path, '/') === trim(uri_string(), '/') ? 'page' : '';
}
function dashIsActiveStart(string $path): string {
    return str_starts_with(trim(uri_string(), '/'), trim($path, '/')) ? 'page' : '';
}

// Display name + avatar
$email       = $user->email ?? '';
$displayName = $isEmployer ? 'Employer' : 'Candidate';
$imagePath   = '';

if ($isEmployer) {
    $displayName = isset($employer) && !empty($employer->company_name) ? $employer->company_name : 'Employer';
    $imagePath   = isset($employer) && !empty($employer->logo) ? $employer->logo : '';
} else {
    $displayName = isset($candidate) && !empty($candidate->full_name) ? $candidate->full_name : 'Candidate';
    $imagePath   = isset($candidate) && !empty($candidate->profile_picture) ? $candidate->profile_picture : '';
}

$words    = explode(' ', preg_replace('/\s+/', ' ', trim($displayName)));
$initials = count($words) >= 2
    ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
    : strtoupper(substr($displayName, 0, 2));

$hasImage = false;
if ($imagePath) {
    if (filter_var($imagePath, FILTER_VALIDATE_URL) || str_starts_with($imagePath, 'http')) {
        $hasImage = true;
    } elseif (file_exists(FCPATH . $imagePath)) {
        $hasImage = true;
    }
}
$imageSrc = $hasImage
    ? (str_starts_with($imagePath, 'http') ? $imagePath : base_url($imagePath))
    : null;

// Wallet balance
$walletBalance = 0;
if (isset($user) && $user) {
    try {
        $walletRow     = model(\App\Models\WalletModel::class)->where('user_id', $user->id)->first();
        $walletBalance = $walletRow ? (float) $walletRow->balance : 0;
    } catch (\Throwable $e) {
        $walletBalance = 0;
    }
}
$walletFormatted = '₦' . number_format($walletBalance, 2);
$walletUrl       = $isEmployer ? base_url('employer/wallet') : base_url('candidate/wallet');

// Saved jobs count (candidate sidebar badge)
$savedJobsCount = 0;
if (!$isEmployer && isset($user) && $user) {
    try {
        $savedJobsCount = model(\App\Models\SavedJobModel::class)->where('user_id', $user->id)->countAllResults();
    } catch (\Throwable $e) {
        $savedJobsCount = 0;
    }
}

// Pending applications (employer badge, when provided by controller)
$pendingCount = $pendingApps ?? 0;
?>
<!DOCTYPE html>
<html lang="en-NG">
<head>
<script>
    (function() {
        // Enforcing light mode globally for the dashboard
        document.documentElement.setAttribute('data-theme', 'light');
        document.documentElement.setAttribute('data-theme-mode', 'light');
    })();
</script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#0A2F57">
<meta name="color-scheme" content="light">
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<link rel="shortcut icon" href="<?= base_url('auth/img/favicon.png') ?>" type="image/x-icon">
<link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('auth/img/apple-touch-icon.png') ?>">

<title><?= esc($title ?? 'Dashboard') ?> - JobberRecruit</title>
<link rel="canonical" href="<?= current_url(); ?>">

<!-- Fonts (non-render-blocking) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>

<!-- Legacy component stack (content views still use these) -->
<link rel="stylesheet" href="<?= base_url('auth/css/bootstrap.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('auth/css/toastr.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('auth/css/bootstrap-datetimepicker.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('auth/plugins/quill/quill.snow.css'); ?>">
<link rel="stylesheet" href="<?= base_url('auth/plugins/select2/css/select2.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('auth/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css'); ?>">
<link rel="stylesheet" href="<?= base_url('auth/plugins/intltelinput/css/intlTelInput.css'); ?>">
<link rel="stylesheet" href="<?= base_url('auth/css/dataTables.bootstrap5.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('auth/plugins/fontawesome/css/all.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('auth/plugins/daterangepicker/daterangepicker.css'); ?>">
<link rel="stylesheet" href="<?= base_url('auth/plugins/tabler-icons/tabler-icons.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('css/bootstrap-icons.css'); ?>">
<link rel="stylesheet" href="<?= base_url('css/global-core.css'); ?>">
<link rel="stylesheet" href="<?= base_url('css/mobile-app.css'); ?>">

<!-- Dashboard Design System shell (loaded last so it wins) -->
<link rel="stylesheet" href="<?= base_url('css/employer-shell.css') ?>">

<!-- Page-Level Styles -->
<?= $this->renderSection('styles') ?>
</head>

<body class="emp-shell">
<a class="skip-link" href="#main-content">Skip to main content</a>

<?= $this->include('partials/svg_sprites') ?>

<!-- ══ SHELL SVG SPRITE ══ -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <defs>
    <symbol id="i-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></symbol>
    <symbol id="i-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></symbol>
    <symbol id="i-share" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/></symbol>
    <symbol id="i-card" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></symbol>
    <symbol id="i-receipt" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2.5-1.5L9 22l2.5-1.5L14 22l2.5-1.5L19 22V2l-2.5 1.5L14 2l-2.5 1.5L9 2 6.5 3.5Z"/><path d="M8 8h7M8 12h7"/></symbol>
    <symbol id="i-cog" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h0a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h0a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v0a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/></symbol>
    <symbol id="i-wallet" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></symbol>
    <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></symbol>
    <symbol id="i-zap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></symbol>
    <symbol id="i-check-c" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
    <symbol id="i-crown" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18h20M3 18 2 7l5.5 4L12 4l4.5 7L22 7l-1 11"/></symbol>
    <symbol id="i-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6"/><path d="M8.7 14 7 22l5-3 5 3-1.7-8"/></symbol>
    <symbol id="i-video" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="14" height="12" rx="2"/><path d="m22 8-6 4 6 4Z"/></symbol>
    <symbol id="i-search-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="8" r="4"/><path d="M3 20a7 7 0 0 1 11-5.5"/><circle cx="17.5" cy="16.5" r="3.5"/><path d="m20 19 2.5 2.5"/></symbol>
    <symbol id="i-arrow-r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></symbol>
    <symbol id="i-arrow-l" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 16v-5M12 16V8M17 16v-3"/></symbol>
    <symbol id="i-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/></symbol>
    <symbol id="i-grad" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5"/></symbol>
    <symbol id="i-note" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M8 13h8M8 17h6"/></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
    <symbol id="i-refresh" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7L21 8"/><path d="M21 3v5h-5"/></symbol>
    <symbol id="i-sliders" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6"/></symbol>
    <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></symbol>
    <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></symbol>
    <symbol id="i-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"/></symbol>
    <symbol id="i-link" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></symbol>
    <symbol id="i-gift" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7M7.5 8a2.5 2.5 0 0 1 0-5A4.5 4.5 0 0 1 12 8a4.5 4.5 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5Z"/></symbol>
    <symbol id="i-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></symbol>
    <symbol id="i-whatsapp" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></symbol>
    <symbol id="i-x-social" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l11.733 16h4.267l-11.733 -16zM4 20l6.768 -6.768M20 4l-6.768 6.768"/></symbol>
    <symbol id="i-linkedin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></symbol>
    <symbol id="i-mic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 10a7 7 0 0 0 14 0M12 19v3M9 22h6"/></symbol>
    <symbol id="i-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 5 11.9V15a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-1.1A7 7 0 0 1 12 2Z"/><path d="M9 17v1a3 3 0 0 0 6 0v-1"/></symbol>
    <symbol id="i-flame" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2s5 4 5 9a5 5 0 0 1-10 0c0-1.5.6-2.8 1.4-3.8.4 1.8 1.6 2.3 2.6 1.3.9-.9.5-2.4 0-3.5-.3-.7-.3-1.4 0-2 0-.6.5-1 1-1Z"/></symbol>
    <symbol id="i-play" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="6 3 20 12 6 21 6 3"/></symbol>
    <symbol id="i-message-sq" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></symbol>
    <symbol id="i-infinity" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12c-2-2.7-4-4-6-4a4 4 0 0 0 0 8c2 0 4-1.3 6-4Zm0 0c2 2.7 4 4 6 4a4 4 0 0 0 0-8c-2 0-4 1.3-6 4Z"/></symbol>
    <symbol id="i-scan" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2M7 12h10"/></symbol>
  </defs>
</svg>

<!-- ══ MOBILE SCRIM ══ -->
<div class="emp-scrim" id="emp-scrim" hidden></div>

<div class="emp-shell-wrap">

  <!-- ════════ SIDEBAR ════════ -->
  <aside class="emp-sidebar" id="emp-sidebar" aria-label="Dashboard navigation">
    <div class="sb-head">
      <a href="<?= base_url('/') ?>" aria-label="JobberRecruit home">
        <img src="<?= base_url('auth/img/logo.png') ?>" alt="JobberRecruit" class="sb-logo-img">
      </a>
      <button class="sb-close" id="sb-close" aria-label="Close menu">
        <svg aria-hidden="true"><use href="#i-x"/></svg>
      </button>
    </div>

    <?php if (!$isEmployer): ?>
    <nav class="sb-scroll" aria-label="Candidate menu">

      <!-- CORE CAREER -->
      <div class="sb-group">
        <div class="sb-label">Core Career</div>
        <a class="sb-link" href="<?= base_url('candidate/dashboard') ?>"
           <?= dashIsActive('candidate/dashboard') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-grid"/></svg> Dashboard
        </a>
        <a class="sb-link" href="<?= base_url('jobs') ?>"
           <?= dashIsActiveStart('jobs') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-search"/></svg> Browse Jobs
        </a>
        <a class="sb-link" href="<?= base_url('candidate/applications') ?>"
           <?= dashIsActiveStart('candidate/applications') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-doc"/></svg> My Applications
        </a>
        <a class="sb-link" href="<?= base_url('candidate/saved-jobs') ?>"
           <?= dashIsActive('candidate/saved-jobs') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-bookmark"/></svg> Saved Jobs
          <?php if ($savedJobsCount > 0): ?><span class="sb-count"><?= $savedJobsCount ?></span><?php endif; ?>
        </a>
        <a class="sb-link" href="<?= base_url('candidate/profile') ?>"
           <?= dashIsActive('candidate/profile') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-user"/></svg> My Profile
        </a>
        <?php if (env('feature_messaging', 'true') == 'true'): ?>
        <a class="sb-link" href="<?= base_url('candidate/messages') ?>"
           <?= dashIsActive('candidate/messages') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-chat"/></svg> Messages
        </a>
        <?php endif; ?>
      </div>

      <!-- AI COGNITIVE TOOLS -->
      <?php if (env('feature_ai_resume', 'true') == 'true' || env('feature_ai_career_tools', 'true') == 'true'): ?>
      <div class="sb-group">
        <div class="sb-label">AI Cognitive Tools</div>
        <?php if (env('feature_ai_resume', 'true') == 'true'): ?>
        <a class="sb-link" href="<?= base_url('candidate/resumes') ?>"
           <?= dashIsActiveStart('candidate/resumes') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-doc"/></svg> AI Resume Builder
        </a>
        <?php endif; ?>
        <?php if (env('feature_ai_career_tools', 'true') == 'true'): ?>
        <a class="sb-link" href="<?= base_url('candidate/career-tools') ?>"
           <?= dashIsActiveStart('candidate/career-tools') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-zap"/></svg> AI Career Tools
        </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- LEARNING & TRAINING -->
      <?php if (env('feature_elearning', 'true') == 'true' || env('feature_webinars', 'true') == 'true'): ?>
      <div class="sb-group">
        <div class="sb-label">Learning &amp; Training</div>
        <?php if (env('feature_elearning', 'true') == 'true'): ?>
        <a class="sb-link" href="<?= base_url('training') ?>"
           <?= (dashIsActiveStart('training') && !dashIsActiveStart('training/certificates')) ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-book"/></svg> Training Catalog
        </a>
        <a class="sb-link" href="<?= base_url('candidate/my-courses') ?>"
           <?= dashIsActive('candidate/my-courses') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-bookmark"/></svg> My Courses
        </a>
        <a class="sb-link" href="<?= base_url('aptitude') ?>"
           <?= dashIsActiveStart('aptitude') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-check-c"/></svg> Aptitude Tests
        </a>
        <a class="sb-link" href="<?= base_url('training/certificates') ?>"
           <?= dashIsActiveStart('training/certificates') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-award"/></svg> Certificates
        </a>
        <?php endif; ?>
        <?php if (env('feature_webinars', 'true') == 'true'): ?>
        <a class="sb-link" href="<?= base_url('webinars') ?>"
           <?= dashIsActiveStart('webinars') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-video"/></svg> Career Webinars
        </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- BILLING & NETWORK -->
      <div class="sb-group">
        <div class="sb-label">Billing &amp; Network</div>
        <a class="sb-link" href="<?= base_url('candidate/subscription/pricing') ?>"
           <?= dashIsActiveStart('candidate/subscription') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-crown"/></svg> Premium Plans
        </a>
        <?php if (env('feature_referrals', 'true') == 'true'): ?>
        <a class="sb-link" href="<?= base_url('candidate/referrals') ?>"
           <?= dashIsActive('candidate/referrals') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-share"/></svg> Referral Program
        </a>
        <?php endif; ?>
        <a class="sb-link" href="<?= base_url('candidate/transactions') ?>"
           <?= dashIsActiveStart('candidate/transactions') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-receipt"/></svg> Transactions
        </a>
        <a class="sb-link" href="<?= base_url('candidate/notifications') ?>"
           <?= dashIsActive('candidate/notifications') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-bell"/></svg> Job Alerts
        </a>
      </div>

      <!-- SETTINGS -->
      <div class="sb-group">
        <div class="sb-label">Settings</div>
        <a class="sb-link" href="<?= base_url('candidate/settings/security') ?>"
           <?= dashIsActiveStart('candidate/settings') ? 'aria-current="page"' : '' ?>>
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
        <a href="<?= $walletUrl ?>" class="emp-btn emp-btn-ghost-w emp-btn-sm">
          <svg aria-hidden="true"><use href="#i-wallet"/></svg> Fund Wallet
        </a>
      </div>

    </nav>
    <?php else: ?>
    <nav class="sb-scroll" aria-label="Employer menu">

      <div class="sb-group">
        <div class="sb-label">Recruitment Hub</div>
        <a class="sb-link" href="<?= base_url('employer/dashboard') ?>"
           <?= dashIsActive('employer/dashboard') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-grid"/></svg> Dashboard
        </a>
        <a class="sb-link" href="<?= base_url('employer/jobs') ?>"
           <?= dashIsActiveStart('employer/jobs') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-briefcase"/></svg> My Jobs
        </a>
        <a class="sb-link" href="<?= base_url('employer/applications') ?>"
           <?= dashIsActiveStart('employer/applications') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-doc"/></svg> Applications
          <?php if ($pendingCount > 0): ?><span class="sb-count"><?= $pendingCount ?></span><?php endif; ?>
        </a>
        <a class="sb-link" href="<?= base_url('employer/candidates') ?>"
           <?= dashIsActive('employer/candidates') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-search-user"/></svg> Candidates Search
        </a>
      </div>

      <div class="sb-group">
        <div class="sb-label">Organization Space</div>
        <a class="sb-link" href="<?= base_url('employer/profile') ?>"
           <?= dashIsActive('employer/profile') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-building"/></svg> Company Profile
        </a>
        <?php if (env('feature_messaging', 'true') == 'true'): ?>
        <a class="sb-link" href="<?= base_url('employer/messages') ?>"
           <?= dashIsActive('employer/messages') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-chat"/></svg> Messages
        </a>
        <?php endif; ?>
        <?php if (env('feature_referrals', 'true') == 'true'): ?>
        <a class="sb-link" href="<?= base_url('employer/referrals') ?>"
           <?= dashIsActive('employer/referrals') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-share"/></svg> Referral Program
        </a>
        <?php endif; ?>
      </div>

      <div class="sb-group">
        <div class="sb-label">Billing &amp; Alerts</div>
        <a class="sb-link" href="<?= base_url('employer/pricing') ?>"
           <?= dashIsActive('employer/pricing') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-card"/></svg> Billing &amp; Plans
        </a>
        <a class="sb-link" href="<?= base_url('employer/transactions') ?>"
           <?= dashIsActiveStart('employer/transactions') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-receipt"/></svg> Transactions
        </a>
        <a class="sb-link" href="<?= base_url('employer/notifications') ?>"
           <?= dashIsActive('employer/notifications') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-bell"/></svg> Candidate Alerts
        </a>
      </div>

      <?php if (env('feature_elearning', 'true') == 'true'): ?>
      <div class="sb-group">
        <div class="sb-label">Training</div>
        <a class="sb-link" href="<?= base_url('training') ?>"
           <?= dashIsActiveStart('training') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-book"/></svg> Training Centre
        </a>
      </div>
      <?php endif; ?>

      <div class="sb-group">
        <div class="sb-label">Settings</div>
        <a class="sb-link" href="<?= base_url('employer/settings/security') ?>"
           <?= dashIsActiveStart('employer/settings') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-cog"/></svg> General Settings
        </a>
        <a class="sb-link" href="<?= base_url('logout') ?>">
          <svg aria-hidden="true"><use href="#i-logout"/></svg> Sign Out
        </a>
      </div>

      <div class="sb-wallet">
        <div class="sb-wallet-label">Wallet balance</div>
        <div class="sb-wallet-amt"><?= esc($walletFormatted) ?></div>
        <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-ghost-w emp-btn-sm">
          <svg aria-hidden="true"><use href="#i-wallet"/></svg> Fund Wallet
        </a>
      </div>

    </nav>
    <?php endif; ?>
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

        <form class="tb-search" role="search" action="<?= base_url('jobs') ?>" method="get">
          <svg aria-hidden="true"><use href="#i-search"/></svg>
          <input type="search" name="keywords" placeholder="Search jobs…" aria-label="Search jobs">
        </form>

        <div class="tb-right">
          <!-- Wallet chip -->
          <a href="<?= $walletUrl ?>" class="tb-wallet" aria-label="Wallet balance <?= esc($walletFormatted) ?>">
            <svg aria-hidden="true"><use href="#i-wallet"/></svg>
            <span class="lbl">Wallet</span> <b><?= esc($walletFormatted) ?></b>
          </a>

          <!-- Notifications -->
          <a class="tb-icon" href="<?= $isEmployer ? base_url('employer/notifications') : base_url('candidate/notifications') ?>" aria-label="Notifications">
            <svg aria-hidden="true"><use href="#i-bell"/></svg>
          </a>

          <!-- Account dropdown -->
          <div class="tb-drop" id="account-drop">
            <button class="tb-avatar" aria-label="Account menu — <?= esc($displayName) ?>" aria-haspopup="true" aria-expanded="false">
              <?php if ($imageSrc): ?>
                <img src="<?= esc($imageSrc) ?>" alt="<?= esc($displayName) ?>" style="width:44px;height:44px;border-radius:50%;object-fit:cover;">
              <?php else: ?>
                <?= esc($initials) ?>
              <?php endif; ?>
            </button>
            <div class="tb-menu" role="menu" aria-label="Account">
              <div class="tm-head">
                <div class="tm-name"><?= esc($displayName) ?></div>
                <div class="tm-mail"><?= esc($email) ?></div>
              </div>
              <?php if ($isEmployer): ?>
                <a href="<?= base_url('employer/profile') ?>" role="menuitem"><svg aria-hidden="true"><use href="#i-building"/></svg> Company Profile</a>
                <a href="<?= base_url('employer/pricing') ?>" role="menuitem"><svg aria-hidden="true"><use href="#i-card"/></svg> Billing &amp; Plans</a>
                <a href="<?= base_url('employer/settings/security') ?>" role="menuitem"><svg aria-hidden="true"><use href="#i-cog"/></svg> General Settings</a>
              <?php else: ?>
                <a href="<?= base_url('candidate/profile') ?>" role="menuitem"><svg aria-hidden="true"><use href="#i-user"/></svg> My Profile</a>
                <a href="<?= base_url('candidate/subscription/pricing') ?>" role="menuitem"><svg aria-hidden="true"><use href="#i-crown"/></svg> Premium Plans</a>
                <a href="<?= base_url('candidate/settings/security') ?>" role="menuitem"><svg aria-hidden="true"><use href="#i-cog"/></svg> General Settings</a>
              <?php endif; ?>
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
        <a href="<?= base_url('terms-of-service') ?>">Terms</a>
        <a href="<?= base_url('contact-us') ?>">Contact</a>
      </nav>
    </footer>

  </div><!-- /.emp-main -->
</div><!-- /.emp-shell-wrap -->

<!-- Mobile Bottom App Navigation -->
<?= $this->include('partials/mobile_bottom_nav') ?>

<!-- ══ Legacy component scripts (content views still use these) ══ -->
<script src="<?= base_url('auth/js/jquery-3.7.1.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/js/feather.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/js/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/js/dataTables.bootstrap5.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/js/bootstrap.bundle.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/js/toastr.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/plugins/apexchart/apexcharts.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/plugins/select2/js/select2.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/plugins/quill/quill.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/js/moment.min.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/plugins/daterangepicker/daterangepicker.js'); ?>" type="text/javascript"></script>
<script src="<?= base_url('auth/plugins/intltelinput/js/intlTelInput.js'); ?>" type="text/javascript"></script>

<!-- ══ Shell interactions ══ -->
<script>
(function() {
  'use strict';
  var sidebar  = document.getElementById('emp-sidebar'),
      scrim    = document.getElementById('emp-scrim'),
      burger   = document.getElementById('emp-hamburger'),
      closeBtn = document.getElementById('sb-close');

  function openMenu() {
    sidebar.classList.add('open');
    scrim.hidden = false;
    requestAnimationFrame(function() { scrim.classList.add('show'); });
    burger.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    sidebar.classList.remove('open');
    scrim.classList.remove('show');
    burger.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    setTimeout(function() { if (!scrim.classList.contains('show')) scrim.hidden = true; }, 240);
  }

  if (burger)   burger.addEventListener('click', openMenu);
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  if (scrim)    scrim.addEventListener('click', closeMenu);
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && sidebar.classList.contains('open')) closeMenu();
  });
})();

// Topbar dropdowns
(function() {
  var drops = document.querySelectorAll('.tb-drop');
  drops.forEach(function(d) {
    var btn = d.querySelector('button');
    if (!btn) return;
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      var was = d.classList.contains('open');
      drops.forEach(function(x) {
        x.classList.remove('open');
        var b = x.querySelector('button'); if (b) b.setAttribute('aria-expanded', 'false');
      });
      if (!was) { d.classList.add('open'); btn.setAttribute('aria-expanded', 'true'); }
    });
  });
  document.addEventListener('click', function() {
    drops.forEach(function(d) {
      d.classList.remove('open');
      var b = d.querySelector('button'); if (b) b.setAttribute('aria-expanded', 'false');
    });
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') drops.forEach(function(d) {
      d.classList.remove('open');
      var b = d.querySelector('button'); if (b) b.setAttribute('aria-expanded', 'false');
    });
  });
})();
</script>

<?= $this->include('partials/chatbot'); ?>
<?= $this->include('partials/cookie_consent'); ?>
<?= $this->renderSection('scripts') ?>
</body>
</html>
