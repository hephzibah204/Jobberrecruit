<?php
$scorePct = (int) round($scorePct ?? ($attempt['score_pct'] ?? 0));
$numCorrect = (int) ($attempt['num_correct'] ?? 0);
$numTotal   = (int) ($attempt['num_total'] ?? 0);
$numWrong   = max(0, $numTotal - $numCorrect);
$circumference = 439.8;
$ringOffset = round($circumference - ($circumference * $scorePct / 100), 1);
$testSlug = $test['slug'] ?? '';
$page_title = ($test['title'] ?? 'Aptitude Test') . ' Practice Results';
?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>

/* ── Reset ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ── Brand Tokens ── */
:root {
  color-scheme: light;
  --brand:        #0D609E;
  --brand-dark:   #064A85;
  --brand-deep:   #0A2F57;
  --brand-light:  #E6F0F8;
  --accent:       #ED9020;
  --accent-dark:  #C8770E;
  --text:         #141926;
  --muted:        #5b6577;
  --bg:           #f5f7fb;
  --white:        #ffffff;
  --border:       #e2e8f2;
  --success:      #16a34a;
  --radius:       10px;
  --shadow:       0 2px 14px rgba(10,47,87,.08);
  --shadow-lg:    0 14px 40px rgba(10,47,87,.16);
  --transition:   .18s ease;
}

html { scroll-behavior: smooth; }
body {
  font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
  background: var(--bg);
  color: var(--text);
  font-size: 15px;
  line-height: 1.7;
  overflow-x: hidden;
  -webkit-font-smoothing: antialiased;
  -webkit-text-size-adjust: 100%;
}
html, body { background: #f5f7fb; }
h1, h2, h3, h4, .nav-logo, .display { font-family: 'Sora', 'Inter', sans-serif; letter-spacing: -.02em; }
a { color: var(--brand); text-decoration: none; }
a:hover { text-decoration: underline; }
img { max-width: 100%; height: auto; display: block; }
svg { flex-shrink: 0; }

/* ── Utility ── */
.container { max-width: 1160px; margin: 0 auto; padding: 0 20px; }
.section   { padding: 76px 0; background-color: #f5f7fb; }
.section.white-bg { background-color: #ffffff; }
.sr-only   { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
.text-center { text-align: center; }
.ic { display: inline-flex; align-items: center; justify-content: center; line-height: 0; }

.section-label {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .72rem; font-weight: 700; letter-spacing: .1em;
  text-transform: uppercase; color: var(--brand);
  background: var(--brand-light); padding: 5px 13px;
  border-radius: 20px; margin-bottom: 14px;
}
.section-label svg { width: 13px; height: 13px; }
.section-title {
  font-size: clamp(1.6rem, 2.9vw, 2.25rem);
  font-weight: 800; line-height: 1.15; margin-bottom: 12px; color: #141926;
}
.section-title span { color: var(--brand); }
.section-sub { color: var(--muted); font-size: .95rem; max-width: 560px; }

/* Buttons */
.btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 7px;
  padding: 11px 22px; border-radius: 8px;
  font-family: 'Inter', sans-serif; font-size: .88rem; font-weight: 600;
  cursor: pointer; border: 1.5px solid transparent;
  transition: var(--transition); text-decoration: none;
  -webkit-tap-highlight-color: transparent; touch-action: manipulation;
}
.btn svg { width: 16px; height: 16px; }
.btn-primary  { background: var(--brand);  color: var(--white); border-color: var(--brand); }
.btn-primary:hover  { background: var(--brand-dark); border-color: var(--brand-dark); text-decoration: none; }
.btn-outline  { background: transparent; color: var(--brand); border-color: var(--border); }
.btn-outline:hover  { background: var(--brand); color: var(--white); border-color: var(--brand); text-decoration: none; }
.btn-accent   { background: var(--accent); color: var(--brand-deep); border-color: var(--accent); }
.btn-accent:hover   { background: var(--accent-dark); border-color: var(--accent-dark); color: var(--brand-deep); text-decoration: none; }
.btn-white    { background: var(--white); color: var(--brand); border-color: var(--white); }
.btn-white:hover    { background: var(--brand-light); text-decoration: none; }
.btn-sm       { padding: 8px 14px; font-size: .78rem; }
.btn-lg       { padding: 14px 32px; font-size: .95rem; }

.badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
.badge svg { width: 12px; height: 12px; }
.badge-blue  { background: var(--brand-light); color: var(--brand); }
.badge-accent { background: #fef3c7; color: var(--accent-dark); }
.badge-green  { background: #ecfdf5; color: #15803d; }

/* Skip link */
.skip-link {
  position: absolute; top: -50px; left: 16px;
  background: var(--brand); color: var(--white);
  padding: 8px 16px; border-radius: 0 0 6px 6px;
  font-weight: 600; z-index: 9999; transition: top .2s;
}
.skip-link:focus { top: 0; }
:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; border-radius: 4px; }


/* RESULT PAGE */
.res-hero{padding:48px 0 40px;text-align:center}
.res-hero.pass{background:radial-gradient(700px 360px at 50% -20%,rgba(22,163,74,.14),transparent 60%),var(--bg)}
.res-hero.fail{background:radial-gradient(700px 360px at 50% -20%,rgba(180,83,9,.12),transparent 60%),var(--bg)}
.res-score-ring{width:160px;height:160px;margin:0 auto 22px;position:relative}
.res-score-ring svg{width:100%;height:100%;transform:rotate(-90deg)}
.res-score-ring .pct{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
.res-score-ring .pct .n{font-family:'Sora',sans-serif;font-size:2.6rem;font-weight:800;color:var(--brand-deep);line-height:1}
.res-score-ring .pct .l{font-size:.74rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-top:2px}
.res-verdict{display:inline-flex;align-items:center;gap:8px;font-size:.8rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;padding:7px 16px;border-radius:30px;margin-bottom:14px}
.res-verdict.pass{background:#dcfce7;color:#15803d}
.res-verdict.fail{background:#fef3c7;color:#b45309}
.res-verdict svg{width:15px;height:15px}
.res-hero h1{font-size:1.7rem;font-weight:800;color:var(--brand-deep);margin-bottom:8px;letter-spacing:-.02em}
.res-hero .sub{font-size:.98rem;color:var(--muted)}
.res-stats{display:flex;gap:0;justify-content:center;margin:26px auto 0;max-width:520px;background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden}
.res-stat{flex:1;padding:18px 12px;text-align:center;border-right:1px solid var(--border)}
.res-stat:last-child{border-right:none}
.res-stat .n{font-family:'Sora',sans-serif;font-size:1.4rem;font-weight:800;color:var(--brand-deep)}
.res-stat .n.ok{color:var(--success)}
.res-stat .n.no{color:#b91c1c}
.res-stat .l{font-size:.74rem;color:var(--muted);margin-top:3px}
.res-wrap{max-width:760px;margin:0 auto;padding:8px 24px 70px}
.res-actions{display:flex;gap:12px;justify-content:center;margin-top:26px;flex-wrap:wrap}
.res-actions .btn{min-width:170px;justify-content:center}

/* verified callout (official) */
.res-verified{max-width:600px;margin:30px auto 0;background:linear-gradient(135deg,#fff7ed,#fffdf9);border:1.5px solid #fed7aa;border-radius:16px;padding:24px;display:flex;align-items:center;gap:18px}
.res-verified .vic{width:54px;height:54px;border-radius:50%;background:#fff;border:2px solid var(--accent);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.res-verified .vic svg{width:28px;height:28px;color:var(--accent)}
.res-verified h3{font-size:1.05rem;font-weight:800;color:var(--brand-deep);margin-bottom:4px}
.res-verified p{font-size:.88rem;color:var(--muted);line-height:1.5}

/* practice breakdown */
.res-sec-title{font-size:1.2rem;font-weight:800;color:var(--brand-deep);margin:34px 0 16px}
.res-q{background:#fff;border:1px solid var(--border);border-radius:14px;padding:22px;margin-bottom:14px}
.res-q-head{display:flex;align-items:flex-start;gap:12px;margin-bottom:14px}
.res-q-badge{width:28px;height:28px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center}
.res-q-badge svg{width:16px;height:16px;color:#fff}
.res-q-badge.ok{background:var(--success)}
.res-q-badge.no{background:#dc2626}
.res-q-body{font-size:1rem;font-weight:600;color:var(--brand-deep);line-height:1.4}
.res-q-opts{display:flex;flex-direction:column;gap:8px;margin-bottom:12px}
.res-q-opt{display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:10px;font-size:.92rem;border:1.5px solid var(--border)}
.res-q-opt .k{width:26px;height:26px;flex-shrink:0;border-radius:7px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.82rem;font-family:'Sora',sans-serif;background:#fff;border:1.5px solid var(--border);color:var(--muted)}
.res-q-opt.correct{background:#f0fdf4;border-color:#86efac}
.res-q-opt.correct .k{background:var(--success);color:#fff;border-color:var(--success)}
.res-q-opt.chosen-wrong{background:#fef2f2;border-color:#fca5a5}
.res-q-opt.chosen-wrong .k{background:#dc2626;color:#fff;border-color:#dc2626}
.res-q-opt .tag{margin-left:auto;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.03em}
.res-q-opt.correct .tag{color:var(--success)}
.res-q-opt.chosen-wrong .tag{color:#dc2626}
.res-q-exp{display:flex;gap:9px;background:var(--brand-light);border-radius:10px;padding:13px 15px;font-size:.88rem;color:var(--text);line-height:1.5}
.res-q-exp svg{width:17px;height:17px;color:var(--brand);flex-shrink:0;margin-top:1px}
.res-q-exp b{color:var(--brand-deep)}

@media(max-width:680px){
  .res-hero h1{font-size:1.4rem}
  .res-stats{flex-wrap:wrap}
  .res-verified{flex-direction:column;text-align:center}
  .res-wrap{padding:8px 16px 60px}
}

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <defs>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
    <symbol id="i-bag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></symbol>
    <symbol id="i-star" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.26 6.88.6-5.2 4.52 1.56 6.72L12 16.9l-6.14 3.7 1.56-6.72-5.2-4.52 6.88-.6z"/></symbol>
    <symbol id="i-bookmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></symbol>
    <symbol id="i-bookmark-fill" viewBox="0 0 24 24" fill="currentColor"><path d="M17 3H7a2 2 0 0 0-2 2v16l7-5 7 5V5a2 2 0 0 0-2-2z"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="i-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
    <symbol id="i-verified-disc" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="currentColor"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></symbol>
    <symbol id="i-x-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m15 9-6 6m0-6 6 6"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></symbol>
    <symbol id="i-spark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4M12 17v4M5 12H1M23 12h-4M6.3 6.3 3.5 3.5M20.5 20.5l-2.8-2.8M17.7 6.3l2.8-2.8M3.5 20.5l2.8-2.8"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-bot" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="8" width="16" height="11" rx="3"/><path d="M12 3v5M9 13h.01M15 13h.01M2 14h2M20 14h2"/></symbol>
    <symbol id="i-mic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="11" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v3"/></symbol>
    <symbol id="i-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M9 14a5 5 0 1 1 6 0c-.7.5-1 1.2-1 2H10c0-.8-.3-1.5-1-2Z"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
    <symbol id="i-cap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 9 12 4 2 9l10 5 10-5Z"/><path d="M6 11v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></symbol>
    <symbol id="i-gift" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M5 12v7a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-7"/><path d="M12 8S11 2 8 2a2.5 2.5 0 0 0 0 5M12 8s1-6 4-6a2.5 2.5 0 0 1 0 5"/></symbol>
    <symbol id="i-chip" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 2v3M15 2v3M9 19v3M15 19v3M2 9h3M2 15h3M19 9h3M19 15h3"/></symbol>
    <symbol id="i-coins" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="9" cy="6" rx="6" ry="3"/><path d="M3 6v6c0 1.7 2.7 3 6 3s6-1.3 6-3V6"/><path d="M15 11c2.5.2 6 1.2 6 3 0 1.7-2.7 3-6 3-1 0-2-.1-3-.3"/><path d="M3 12c0 1.7 2.7 3 6 3"/></symbol>
    <symbol id="i-mega" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1Z"/><path d="M15 8a4 4 0 0 1 0 8M18 5a8 8 0 0 1 0 14"/></symbol>
    <symbol id="i-gear" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/></symbol>
    <symbol id="i-drop" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.7S5 9.5 5 14a7 7 0 0 0 14 0c0-4.5-7-11.3-7-11.3Z"/></symbol>
    <symbol id="i-heart-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.5-1.5 3-3.3 3-5.5A4.5 4.5 0 0 0 12 5.5 4.5 4.5 0 0 0 2 8.5c0 2.2 1.5 4 3 5.5l7 7Z"/><path d="M3.2 12h4l1.5-3 2.5 5 1.5-2h4.5"/></symbol>
    <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></symbol>
    <symbol id="i-handshake" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m11 17 2 2a1 1 0 0 0 1.4 0l3.6-3.6"/><path d="m2 12 3-3 5 5 2-2 5 5 3-3"/><path d="m21 12-3-3-3 3"/><path d="M3 9 6 6l4 4"/></symbol>
    <symbol id="i-bank" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10 12 4l9 6M4 10v8M20 10v8M8 10v8M16 10v8M3 21h18"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18M8 15v3M13 11v7M18 7v11"/></symbol>
    <symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></symbol>
    <symbol id="i-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18Z"/></symbol>
    <symbol id="i-building" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="1"/><path d="M9 7h.01M15 7h.01M9 11h.01M15 11h.01M9 15h.01M15 15h.01M10 21v-3h4v3"/></symbol>
    <symbol id="i-rocket" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13c-1.5.5-3 2.5-3 5 2.5 0 4.5-1.5 5-3"/><path d="M13 7a8 8 0 0 1 7-4 8 8 0 0 1-4 7l-4 3-2-2Z"/><path d="m9 11-3 3 4 4 3-3M15 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6M21 20a5.5 5.5 0 0 0-4-5.3"/></symbol>
    <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
    <symbol id="i-chev-down" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></symbol>
    <symbol id="i-arrow-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></symbol>
  <symbol id="i-sliders" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6"/></symbol>
  <symbol id="i-headset" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 14v-2a9 9 0 0 1 18 0v2"/><path d="M21 16a2 2 0 0 1-2 2h-1v-6h1a2 2 0 0 1 2 2zM3 16a2 2 0 0 0 2 2h1v-6H5a2 2 0 0 0-2 2zM21 18a3 3 0 0 1-3 3h-6"/></symbol>
  <symbol id="i-share-nodes" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/></symbol>
  <symbol id="i-filter" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 3H2l8 9.5V19l4 2v-8.5L22 3Z"/></symbol>
</defs>
</svg>
<section class="res-hero">
  <div class="container">
    <span class="res-verdict pass"><svg aria-hidden="true"><use href="#i-book"/></svg> Practice complete</span>
    <div class="res-score-ring">
  <svg viewBox="0 0 160 160">
    <circle cx="80" cy="80" r="70" fill="none" stroke="#e2e8f2" stroke-width="14"/>
    <circle cx="80" cy="80" r="70" fill="none" stroke="#0D609E" stroke-width="14" stroke-linecap="round" stroke-dasharray="<?= $circumference ?>" stroke-dashoffset="<?= $ringOffset ?>"/>
  </svg>
  <div class="pct"><div class="n"><?= $scorePct ?>%</div><div class="l">Score</div></div>
</div>
    <h1><?= esc($test['title'] ?? 'Aptitude Test') ?></h1>
    <p class="sub">Practice mode · Review your answers below</p>
    <div class="res-stats">
      <div class="res-stat"><div class="n ok"><?= $numCorrect ?></div><div class="l">Correct</div></div>
      <div class="res-stat"><div class="n no"><?= $numWrong ?></div><div class="l">Incorrect</div></div>
      <div class="res-stat"><div class="n"><?= $numTotal ?></div><div class="l">Total</div></div>
    </div>
    <div class="res-actions">
      <a href="<?= base_url('aptitude/' . $testSlug . '/practice') ?>" class="btn btn-outline">Practice again</a>
      <a href="<?= base_url('aptitude/' . $testSlug . '/start') ?>" class="btn btn-accent">Take official test</a>
    </div>
  </div>
</section>
<div class="res-wrap">
  <h2 class="res-sec-title">Answer breakdown</h2>
  <?php foreach ($breakdown as $q): ?>
  <div class="res-q">
    <div class="res-q-head">
      <span class="res-q-badge <?= $q['is_correct'] ? 'ok' : 'no' ?>"><svg aria-hidden="true"><use href="<?= $q['is_correct'] ? '#i-check' : '#i-x-circle' ?>"/></svg></span>
      <div class="res-q-body"><?= $q['number'] ?>. <?= esc($q['body']) ?></div>
    </div>
    <div class="res-q-opts">
      <?php foreach ($q['options'] as $i => $opt): ?>
        <div class="res-q-opt<?= $opt['correct'] ? ' correct' : ($opt['chosen'] ? ' chosen-wrong' : '') ?>">
          <span class="k"><?= chr(65 + $i) ?></span><?= esc($opt['body']) ?>
          <?php if ($opt['correct']): ?><span class="tag">Correct</span>
          <?php elseif ($opt['chosen']): ?><span class="tag">Your answer</span><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if (!empty($q['explanation'])): ?>
    <div class="res-q-exp"><svg aria-hidden="true"><use href="#i-bulb"/></svg><div><b>Why:</b> <?= esc($q['explanation']) ?></div></div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>

<?= $this->endSection() ?>
