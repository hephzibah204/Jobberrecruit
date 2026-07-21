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
?>
<!DOCTYPE html>
<html lang="en-NG">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#0A2F57">
<meta name="color-scheme" content="light">
<meta name="robots" content="noindex, nofollow, noarchive">
<meta name="csrf-token" content="<?= csrf_hash() ?>">
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
<link rel="stylesheet" href="<?= base_url('css/employer-shell.css') ?>">
<!-- Tabler Icons (needed for some CI4 view remnants) -->
<link rel="stylesheet" href="<?= base_url('auth/plugins/tabler-icons/tabler-icons.min.css') ?>">

<!-- Page-level styles -->
<?= $this->renderSection('styles') ?>
</head>

<body class="emp-shell">
<a class="skip-link" href="#main-content">Skip to main content</a>

<!-- ══ SVG SPRITE ══ -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <defs>
    <symbol id="i-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></symbol>
    <symbol id="i-briefcase" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
    <symbol id="i-search-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="8" r="4"/><path d="M3 20a7 7 0 0 1 11-5.5"/><circle cx="17.5" cy="16.5" r="3.5"/><path d="m20 19 2.5 2.5"/></symbol>
    <symbol id="i-building" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="1"/><path d="M9 7h.01M15 7h.01M9 11h.01M15 11h.01M9 15h.01M15 15h.01M10 21v-3h4v3"/></symbol>
    <symbol id="i-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></symbol>
    <symbol id="i-share" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/></symbol>
    <symbol id="i-card" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></symbol>
    <symbol id="i-receipt" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2.5-1.5L9 22l2.5-1.5L14 22l2.5-1.5L19 22V2l-2.5 1.5L14 2l-2.5 1.5L9 2 6.5 3.5Z"/><path d="M8 8h7M8 12h7"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.9 1.9 0 0 0 3.4 0"/></symbol>
    <symbol id="i-cog" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h0a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h0a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v0a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/></symbol>
    <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V4a2 2 0 0 0-2-2H6.5A2.5 2.5 0 0 0 4 4.5Z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/></symbol>
    <symbol id="i-wallet" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6M21 20a5.5 5.5 0 0 0-4-5.3"/></symbol>
    <symbol id="i-user-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="4"/><path d="M2 21a7 7 0 0 1 13-3.5"/><path d="m15 18 2.5 2.5L22 16"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5Z"/></symbol>
    <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></symbol>
    <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></symbol>
    <symbol id="i-arrow-r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
    <symbol id="i-arrow-l" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></symbol>
    <symbol id="i-arrow-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></symbol>
    <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4-4"/></symbol>
    <symbol id="i-spark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M18.4 5.6l-2.1 2.1M7.7 16.3l-2.1 2.1"/></symbol>
    <symbol id="i-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.4 1 2.3h6c0-.9.4-1.8 1-2.3A7 7 0 0 0 12 2Z"/></symbol>
    <symbol id="i-check-c" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="i-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></symbol>
    <symbol id="i-funnel" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h18l-7 8v6l-4 2v-8Z"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18M8 15v3M13 11v7M18 7v11"/></symbol>
    <symbol id="i-tag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.3 9.3a1.9 1.9 0 0 0 2.7 0l7.3-7.3a1.9 1.9 0 0 0 0-2.7Z"/><path d="M7 7h.01"/></symbol>
    <symbol id="i-gift" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></symbol>
    <symbol id="i-link" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></symbol>
    <symbol id="i-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></symbol>
    <symbol id="i-send" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></symbol>
    <symbol id="i-phone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.63 3.42a2 2 0 0 1 1.99-1.86h3a2 2 0 0 1 2 1.72c.13.96.36 1.91.69 2.83a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.18 6.18l.97-1.37a2 2 0 0 1 2.11-.45c.92.33 1.87.56 2.83.69a2 2 0 0 1 1.72 2.02Z"/></symbol>
    <symbol id="i-filter" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></symbol>
    <symbol id="i-star" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></symbol>
    <symbol id="i-refresh" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></symbol>
    <symbol id="i-zap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></symbol>
    <symbol id="i-pause" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></symbol>
    <symbol id="i-note" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h4"/></symbol>
  </defs>
</svg>

<!-- ══ MOBILE SCRIM ══ -->
<div class="emp-scrim" id="emp-scrim" hidden></div>

<div class="emp-shell-wrap">

  <!-- ════════ SIDEBAR ════════ -->
  <aside class="emp-sidebar" id="emp-sidebar" aria-label="Employer navigation">
    <div class="sb-head">
      <a href="<?= base_url('/') ?>" aria-label="JobberRecruit home">
        <img src="<?= base_url('auth/img/logo.png') ?>" alt="JobberRecruit" class="sb-logo-img">
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
      </div>

      <!-- ORGANIZATION SPACE -->
      <div class="sb-group">
        <div class="sb-label">Organization Space</div>
        <a class="sb-link" href="<?= base_url('employer/profile') ?>"
           <?= empIsActive('employer/profile') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-building"/></svg> Company Profile
        </a>
        <?php if (env('feature_messaging', 'true') == 'true'): ?>
        <a class="sb-link" href="<?= base_url('employer/messages') ?>"
           <?= empIsActive('employer/messages') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-chat"/></svg> Messages
        </a>
        <?php endif; ?>
        <?php if (env('feature_referrals', 'true') == 'true'): ?>
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
          <svg aria-hidden="true"><use href="#i-bell"/></svg> Candidate Alerts
        </a>
      </div>

      <!-- TRAINING -->
      <?php if (env('feature_elearning', 'true') == 'true'): ?>
      <div class="sb-group">
        <div class="sb-label">Training</div>
        <a class="sb-link" href="<?= base_url('training') ?>"
           <?= empIsActiveStart('training') ? 'aria-current="page"' : '' ?>>
          <svg aria-hidden="true"><use href="#i-book"/></svg> Training Centre
        </a>
      </div>
      <?php endif; ?>

      <!-- SETTINGS -->
      <div class="sb-group">
        <div class="sb-label">Settings</div>
        <a class="sb-link" href="<?= base_url('employer/settings/security') ?>"
           <?= empIsActiveStart('employer/settings') ? 'aria-current="page"' : '' ?>>
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
        <a href="<?= base_url('employer/pricing') ?>" class="emp-btn emp-btn-ghost-w emp-btn-sm">
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

        <div class="tb-search" role="search">
          <svg aria-hidden="true"><use href="#i-search"/></svg>
          <input type="search" placeholder="Search jobs, applicants, candidates…" aria-label="Search dashboard">
        </div>

        <div class="tb-right">
          <!-- Wallet chip -->
          <a href="<?= base_url('employer/pricing') ?>" class="tb-wallet" aria-label="Wallet balance <?= esc($walletFormatted) ?>">
            <svg aria-hidden="true"><use href="#i-wallet"/></svg>
            <span class="lbl">Wallet</span> <b><?= esc($walletFormatted) ?></b>
          </a>

          <!-- Notifications dropdown -->
          <div class="tb-drop" id="notif-drop">
            <button class="tb-icon" aria-label="Notifications" aria-haspopup="true" aria-expanded="false">
              <svg aria-hidden="true"><use href="#i-bell"/></svg>
              <?php if ($pendingCount > 0): ?><span class="tb-dot" aria-hidden="true"></span><?php endif; ?>
            </button>
            <div class="tb-menu tb-menu--notif" role="menu" aria-label="Notifications">
              <?php if ($pendingCount > 0): ?>
              <a href="<?= base_url('employer/applications') ?>" class="tn-item" role="menuitem">
                <span class="tn-ic" aria-hidden="true"><svg><use href="#i-doc"/></svg></span>
                <span><b><?= $pendingCount ?> pending application<?= $pendingCount > 1 ? 's' : '' ?> need review</b><i>Today</i></span>
              </a>
              <?php else: ?>
              <div style="padding:14px 12px;font-size:.82rem;color:var(--muted);text-align:center;">No new notifications</div>
              <?php endif; ?>
              <hr>
              <a href="<?= base_url('employer/notifications') ?>" role="menuitem" style="justify-content:center;font-weight:600;color:var(--brand)">View all alerts</a>
            </div>
          </div>

          <!-- Account dropdown -->
          <div class="tb-drop" id="account-drop">
            <button class="tb-avatar" aria-label="Account menu — <?= esc($displayName) ?>" aria-haspopup="true" aria-expanded="false">
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
              <a href="<?= base_url('employer/pricing') ?>" role="menuitem">
                <svg aria-hidden="true"><use href="#i-card"/></svg> Billing &amp; Plans
              </a>
              <a href="<?= base_url('employer/settings/security') ?>" role="menuitem">
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

<!-- SIDEBAR & UI SCRIPTS -->
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

<!-- Page-level scripts -->
<?= $this->renderSection('scripts') ?>
</body>
</html>
