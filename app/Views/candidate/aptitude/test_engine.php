<?= $this->extend('templates/base') ?>

<?= $this->section('styles') ?>
<style>
/* ══ RESET ══ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* ══ BRAND TOKENS ══ */
:root {
  color-scheme: light;
  --brand:        #0861A9;
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
html, body { background: var(--bg); }
h1, h2, h3, h4 { font-family: 'Sora', 'Inter', sans-serif; letter-spacing: -.02em; }
a { color: var(--brand); text-decoration: none; }
a:hover { text-decoration: underline; }
img { max-width: 100%; height: auto; display: block; }
svg { flex-shrink: 0; }
:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; border-radius: 4px; }

/* ══ BUTTONS ══ */
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
.btn-sm       { padding: 8px 14px; font-size: .78rem; }
.btn-lg       { padding: 14px 32px; font-size: .95rem; }

/* ══ SKIP LINK ══ */
.skip-link {
  position: absolute; top: -50px; left: 16px;
  background: var(--brand); color: var(--white);
  padding: 8px 16px; border-radius: 0 0 6px 6px;
  font-weight: 600; z-index: 9999; transition: top .2s;
}
.skip-link:focus { top: 0; }

/* ══ TEST ENGINE ══ */
body { background: var(--bg); }

/* ── Sticky top bar ── */
.tst-bar {
  position: sticky; top: 0; z-index: 50;
  background: #fff; border-bottom: 1px solid var(--border);
  box-shadow: 0 1px 6px rgba(10,47,87,.05);
}
.tst-bar-in {
  max-width: 880px; margin: 0 auto; padding: 12px 24px;
  display: flex; align-items: center; justify-content: space-between; gap: 16px;
}
.tst-brand {
  display: flex; align-items: center; gap: 10px;
  font-family: 'Sora', sans-serif; font-weight: 800;
  color: var(--brand-deep); font-size: 1rem;
}
.tst-brand .dot {
  width: 30px; height: 30px; border-radius: 8px;
  background: linear-gradient(135deg, var(--brand), var(--brand-deep));
  display: flex; align-items: center; justify-content: center;
}
.tst-brand .dot svg { width: 17px; height: 17px; color: #fff; }
.tst-mode-pill {
  font-size: .66rem; font-weight: 700; letter-spacing: .05em;
  text-transform: uppercase; padding: 4px 10px; border-radius: 20px;
  background: #fff7ed; color: var(--accent-dark); border: 1px solid #fed7aa;
}
.tst-mode-pill.practice { background: var(--brand-light); color: var(--brand); border-color: #cfe0f0; }
.tst-timer {
  display: flex; align-items: center; gap: 8px;
  font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1.05rem;
  color: var(--brand-deep); background: var(--bg); border: 1px solid var(--border);
  border-radius: 10px; padding: 8px 14px; min-width: 104px; justify-content: center;
}
.tst-timer svg { width: 17px; height: 17px; color: var(--brand); }
.tst-timer.warn  { color: #b45309; background: #fef3c7; border-color: #fcd34d; }
.tst-timer.danger { color: #b91c1c; background: #fee2e2; border-color: #fca5a5; animation: pulse 1s infinite; }
@keyframes pulse { 0%,100% { opacity:1 } 50% { opacity:.6 } }

/* ── Progress bar ── */
.tst-progress { max-width: 880px; margin: 0 auto; padding: 0 24px; }
.tst-progress-track { height: 5px; background: var(--border); border-radius: 4px; overflow: hidden; }
.tst-progress-fill  { height: 100%; background: linear-gradient(90deg, var(--brand), var(--accent)); border-radius: 4px; transition: width .3s; }

/* ── Main test wrap ── */
.tst-wrap {
  max-width: 760px; margin: 0 auto; padding: 36px 24px 120px;
  -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;
  -webkit-touch-callout: none;
}

/* ── Anti-cheat warning banner ── */
.tst-warn-banner {
  position: fixed; top: 0; left: 0; right: 0; z-index: 200;
  background: #fef2f2; border-bottom: 1.5px solid #fca5a5; color: #b91c1c;
  padding: 12px 20px; font-size: .9rem; font-weight: 600; text-align: center;
  display: none; align-items: center; justify-content: center; gap: 10px;
  padding-top: calc(12px + env(safe-area-inset-top));
}
.tst-warn-banner.show { display: flex; }
.tst-warn-banner svg { width: 18px; height: 18px; flex-shrink: 0; }

/* ── Identity watermark ── */
.tst-watermark { position: fixed; inset: 0; z-index: 5; pointer-events: none; overflow: hidden; opacity: .06; }
.tst-watermark .row {
  display: flex; gap: 80px; white-space: nowrap; transform: rotate(-24deg);
  font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1rem;
  color: var(--brand-deep); margin: 34px 0;
}

/* ── Blur notice (focus lost) ── */
.tst-qcard { transition: filter .08s; }
body.tst-blurred .tst-qcard { filter: blur(14px); }
body.tst-blurred .tst-options { filter: blur(14px); }
.tst-blur-notice {
  position: fixed; inset: 0; z-index: 60; display: none;
  align-items: center; justify-content: center;
  background: rgba(245,247,251,.9); text-align: center; padding: 30px;
}
body.tst-blurred .tst-blur-notice { display: flex; }
.tst-blur-notice-in { max-width: 340px; }
.tst-blur-notice svg { width: 40px; height: 40px; color: var(--brand); margin-bottom: 14px; }
.tst-blur-notice h3 { font-size: 1.1rem; font-weight: 800; color: var(--brand-deep); margin-bottom: 6px; }
.tst-blur-notice p  { font-size: .9rem; color: var(--muted); line-height: 1.5; }

/* ── Final warning overlay ── */
.tst-final-warn {
  position: fixed; inset: 0; z-index: 250; display: none;
  align-items: center; justify-content: center;
  background: rgba(10,47,87,.55); padding: 24px;
}
.tst-final-warn.show { display: flex; }
.tst-final-warn-card {
  background: #fff; border-radius: 18px; padding: 32px;
  max-width: 420px; width: 100%; text-align: center;
  box-shadow: 0 18px 50px rgba(10,47,87,.25);
}
.tst-final-warn-ic {
  width: 62px; height: 62px; border-radius: 50%; background: #fef2f2;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
}
.tst-final-warn-ic svg { width: 32px; height: 32px; color: #dc2626; }
.tst-final-warn h3 { font-size: 1.3rem; font-weight: 800; color: #b91c1c; margin-bottom: 8px; }
.tst-final-warn p  { font-size: .96rem; color: var(--text); line-height: 1.55; margin-bottom: 22px; }
.tst-final-warn .btn { width: 100%; justify-content: center; min-height: 48px; }

/* ══ HONOUR-CODE START GATE ══ */
.tst-gate {
  position: fixed; inset: 0; z-index: 300; background: var(--bg);
  display: flex; align-items: center; justify-content: center;
  padding: 24px; overflow-y: auto;
}
.tst-gate.hidden { display: none; }
.tst-gate-card {
  background: #fff; border: 1px solid var(--border); border-radius: 18px;
  padding: 34px; max-width: 520px; width: 100%;
  box-shadow: 0 14px 40px rgba(10,47,87,.12);
}
.tst-gate-ic {
  width: 58px; height: 58px; border-radius: 14px; background: var(--brand-light);
  display: flex; align-items: center; justify-content: center; margin-bottom: 18px;
}
.tst-gate-ic svg { width: 30px; height: 30px; color: var(--brand); }
.tst-gate h1 { font-size: 1.5rem; font-weight: 800; color: var(--brand-deep); margin-bottom: 6px; letter-spacing: -.02em; }
.tst-gate .meta { font-size: .9rem; color: var(--muted); margin-bottom: 20px; }
.tst-gate-rules { list-style: none; padding: 0; margin: 0 0 22px; }
.tst-gate-rules li {
  display: flex; align-items: flex-start; gap: 11px;
  font-size: .92rem; color: var(--text); line-height: 1.5; margin-bottom: 12px;
}
.tst-gate-rules li svg { width: 18px; height: 18px; color: var(--brand); flex-shrink: 0; margin-top: 2px; }
.tst-gate-employer {
  display: flex; align-items: flex-start; gap: 11px;
  background: #fff7ed; border: 1px solid #fed7aa; border-radius: 12px;
  padding: 14px 16px; margin-bottom: 18px;
}
.tst-gate-employer[hidden] { display: none; }
.tst-gate-employer svg { width: 20px; height: 20px; color: var(--accent-dark); flex-shrink: 0; margin-top: 1px; }
.tst-gate-employer p  { font-size: .9rem; color: var(--text); line-height: 1.5; margin: 0; }
.tst-gate-employer b  { color: var(--brand-deep); }
.tst-honour {
  display: flex; align-items: flex-start; gap: 11px;
  background: var(--bg); border: 1px solid var(--border); border-radius: 12px;
  padding: 15px; margin-bottom: 20px; cursor: pointer;
}
.tst-honour input { width: 20px; height: 20px; margin-top: 1px; flex-shrink: 0; accent-color: var(--brand); }
.tst-honour label { font-size: .9rem; color: var(--text); line-height: 1.5; cursor: pointer; }
.tst-gate-start { width: 100%; justify-content: center; min-height: 50px; font-size: 1rem; }
.tst-gate-start:disabled { opacity: .5; cursor: not-allowed; }

/* ══ QUESTION AREA ══ */
.tst-qnum {
  font-size: .82rem; font-weight: 700; letter-spacing: .05em;
  text-transform: uppercase; color: var(--brand); margin-bottom: 12px;
}
.tst-qcard {
  background: #fff; border: 1px solid var(--border); border-radius: 18px;
  padding: 32px; box-shadow: 0 4px 18px rgba(10,47,87,.06);
}
.tst-qbody {
  font-size: 1.25rem; font-weight: 600; color: var(--brand-deep);
  line-height: 1.45; margin-bottom: 26px;
}
.tst-options { display: flex; flex-direction: column; gap: 12px; }
.tst-opt {
  display: flex; align-items: center; gap: 14px; padding: 16px 18px;
  border: 1.5px solid var(--border); border-radius: 12px; cursor: pointer;
  transition: .14s; background: #fff; min-height: 44px;
}
.tst-opt:hover { border-color: var(--brand); background: var(--brand-light); }
.tst-opt.selected { border-color: var(--brand); background: var(--brand-light); box-shadow: 0 0 0 3px rgba(8,97,169,.1); }
.tst-opt-key {
  width: 32px; height: 32px; flex-shrink: 0; border-radius: 8px;
  border: 1.5px solid var(--border); display: flex; align-items: center; justify-content: center;
  font-family: 'Sora', sans-serif; font-weight: 700; font-size: .9rem;
  color: var(--muted); transition: .14s;
}
.tst-opt.selected .tst-opt-key { background: var(--brand); color: #fff; border-color: var(--brand); }
.tst-opt-text { font-size: 1rem; color: var(--text); font-weight: 500; }

/* ── Question overview dots ── */
.tst-dots { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 24px; justify-content: center; }
.tst-dot {
  width: 34px; height: 34px; border-radius: 8px; border: 1.5px solid var(--border);
  background: #fff; font-size: .8rem; font-weight: 700; color: var(--muted);
  cursor: pointer; transition: .12s;
}
.tst-dot.answered { background: var(--brand); color: #fff; border-color: var(--brand); }
.tst-dot.current  { border-color: var(--accent); box-shadow: 0 0 0 2px rgba(237,144,32,.3); }
.tst-dot.flagged  { border-color: var(--accent-dark); background: #fff7ed; color: var(--accent-dark); }

/* ── Bottom navigation ── */
.tst-nav {
  position: fixed; bottom: 0; left: 0; right: 0;
  background: #fff; border-top: 1px solid var(--border);
  padding: 14px 24px; padding-bottom: calc(14px + env(safe-area-inset-bottom));
  z-index: 40;
}
.tst-nav-in { max-width: 760px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 14px; }
.tst-nav .btn { min-height: 48px; padding: 12px 26px; }
.tst-flag {
  display: inline-flex; align-items: center; gap: 7px;
  font-size: .86rem; font-weight: 600; color: var(--muted);
  background: none; border: none; cursor: pointer;
}
.tst-flag svg { width: 16px; height: 16px; }
.tst-flag.flagged { color: var(--accent-dark); }

/* ── Submit confirmation modal ── */
.tst-modal {
  position: fixed; inset: 0; background: rgba(10,47,87,.5);
  display: none; align-items: center; justify-content: center;
  z-index: 100; padding: 20px;
}
.tst-modal.show { display: flex; }
.tst-modal-card {
  background: #fff; border-radius: 18px; padding: 30px;
  max-width: 420px; width: 100%; text-align: center;
}
.tst-modal-ic {
  width: 60px; height: 60px; border-radius: 50%; background: #fff7ed;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
}
.tst-modal-ic svg { width: 30px; height: 30px; color: var(--accent); }
.tst-modal h3 { font-size: 1.3rem; font-weight: 800; color: var(--brand-deep); margin-bottom: 8px; }
.tst-modal p  { font-size: .94rem; color: var(--muted); line-height: 1.5; margin-bottom: 22px; }
.tst-modal-actions { display: flex; gap: 12px; }
.tst-modal-actions .btn { flex: 1; justify-content: center; min-height: 46px; }

/* ══ RESPONSIVE ══ */
@media (max-width: 680px) {
  .tst-qcard { padding: 22px; }
  .tst-qbody { font-size: 1.1rem; }
  .tst-wrap  { padding: 24px 16px 110px; }
  .tst-bar-in { padding: 10px 16px; }
  .tst-brand span.full { display: none; }
  input, select, textarea { font-size: 16px !important; }
}
@media (pointer: coarse) {
  .tst-dot { width: 44px; height: 44px; }
}
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ═══ ICON SPRITE ═══ -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false"><defs>
<symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></symbol>
<symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
<symbol id="i-shield" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></symbol>
<symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></symbol>
<symbol id="i-check-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
<symbol id="i-x-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m15 9-6 6m0-6 6 6"/></symbol>
<symbol id="i-bookmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></symbol>
<symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></symbol>
<symbol id="i-building" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="1"/><path d="M9 7h.01M15 7h.01M9 11h.01M15 11h.01M9 15h.01M15 15h.01M10 21v-3h4v3"/></symbol>
<symbol id="i-cap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 9 12 4 2 9l10 5 10-5Z"/><path d="M6 11v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/></symbol>
<symbol id="i-verified-disc" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="currentColor"/><path d="M16.5 9.2l-5.6 5.6-3-3" fill="none" stroke="#fff" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/></symbol>
</defs></svg>

<!-- ═══ HONOUR-CODE START GATE ═══ -->
<div class="tst-gate" id="gate">
  <div class="tst-gate-card">
    <div class="tst-gate-ic"><svg aria-hidden="true"><use href="#i-shield"/></svg></div>
    <h1>Before you begin</h1>
    <p class="meta"><?= esc($test['title'] ?? 'Aptitude Test') ?> · <?= ($attempt['mode'] ?? 'practice') === 'official' ? 'Official' : 'Practice' ?> test · <?= (int) ($test['num_questions'] ?? 5) ?> questions · <?= (int) ($test['duration_mins'] ?? 25) ?> minutes</p>

    <?php if (!empty($employer_name)): ?>
    <div class="tst-gate-employer" id="employer-notice">
      <svg aria-hidden="true"><use href="#i-building"/></svg>
      <p>This test is <b>required by <?= esc($employer_name) ?></b> for the role you're applying to. Your result will be shared with them as part of your application.</p>
    </div>
    <?php endif; ?>

    <ul class="tst-gate-rules">
      <li><svg aria-hidden="true"><use href="#i-clock"/></svg> The timer starts when you begin and cannot be paused. The test submits automatically when time runs out.</li>
      <li><svg aria-hidden="true"><use href="#i-eye"/></svg> Leaving this page, switching tabs, or attempting to copy or capture questions is detected and recorded. After 4 violations your test is submitted automatically and flagged for review.</li>
      <li><svg aria-hidden="true"><use href="#i-shield"/></svg> Your name is watermarked across the test. Any shared or leaked questions are traceable to you.</li>
      <li><svg aria-hidden="true"><use href="#i-verified-disc"/></svg> Your verified score is added to your profile — you control whether it's shown. Flagged attempts are reviewed before any badge is confirmed.</li>
    </ul>

    <div class="tst-honour" onclick="document.getElementById('agree').click()">
      <input type="checkbox" id="agree" onclick="event.stopPropagation();document.getElementById('start-btn').disabled=!this.checked">
      <label for="agree">I confirm this is my own work. I will not photograph, screenshot, copy, or share these questions, or seek outside help. I understand violations may void my results and account.</label>
    </div>
    <button class="btn btn-accent tst-gate-start" id="start-btn" disabled onclick="startTest()">Start <?= ($attempt['mode'] ?? 'practice') === 'official' ? 'official test' : 'practice' ?></button>
  </div>
</div>

<!-- ═══ IDENTITY WATERMARK ═══ -->
<div class="tst-watermark" id="watermark" aria-hidden="true"></div>

<!-- ═══ BLUR NOTICE (FOCUS LOST) ═══ -->
<div class="tst-blur-notice" aria-hidden="true" onclick="blurOff()">
  <div class="tst-blur-notice-in">
    <svg aria-hidden="true"><use href="#i-eye"/></svg>
    <h3>Test paused</h3>
    <p>The test is hidden while this page isn't in focus. Tap here or return to the test to continue — this was recorded.</p>
  </div>
</div>

<!-- ═══ FINAL WARNING OVERLAY ═══ -->
<div class="tst-final-warn" id="final-warn">
  <div class="tst-final-warn-card">
    <div class="tst-final-warn-ic"><svg aria-hidden="true"><use href="#i-shield"/></svg></div>
    <h3>Final warning</h3>
    <p>You have left the test <b id="fw-count">3</b> times. If you leave the test page once more, it will be submitted automatically and flagged for review.</p>
    <button class="btn btn-accent" onclick="document.getElementById('final-warn').classList.remove('show')">I understand</button>
  </div>
</div>

<!-- ═══ ANTI-CHEAT WARNING BANNER ═══ -->
<div class="tst-warn-banner" id="warn-banner">
  <svg aria-hidden="true"><use href="#i-shield"/></svg>
  <span id="warn-text">Leaving the test was detected. This has been recorded.</span>
</div>

<!-- ═══ TOP BAR: BRAND + MODE + TIMER ═══ -->
<div class="tst-bar">
  <div class="tst-bar-in">
    <div class="tst-brand">
      <span class="dot"><svg aria-hidden="true"><use href="#i-cap"/></svg></span>
      <span class="full"><?= esc($test['title'] ?? 'Aptitude Test') ?></span>
    </div>
    <span class="tst-mode-pill <?= ($attempt['mode'] ?? 'practice') === 'practice' ? 'practice' : '' ?>" id="mode-pill"><?= ($attempt['mode'] ?? 'practice') === 'official' ? 'Official test' : 'Practice' ?></span>
    <div class="tst-timer" id="timer"><svg aria-hidden="true"><use href="#i-clock"/></svg><span id="time"><?= sprintf('%02d:%02d', (int) ($test['duration_mins'] ?? 25), 0) ?></span></div>
  </div>
  <div class="tst-progress"><div class="tst-progress-track"><div class="tst-progress-fill" id="prog" style="width:0%"></div></div></div>
</div>

<!-- ═══ MAIN TEST AREA ═══ -->
<main class="tst-wrap">
  <div class="tst-qnum" id="qnum">Loading question…</div>
  <div class="tst-qcard">
    <div class="tst-qbody" id="qbody">Loading question…</div>
    <div class="tst-options" id="opts"></div>
  </div>

  <!-- question overview dots -->
  <div class="tst-dots" id="dots"></div>
</main>

<!-- ═══ BOTTOM NAV ═══ -->
<div class="tst-nav">
  <div class="tst-nav-in">
    <button class="btn btn-outline" id="prev" onclick="go(-1)">Previous</button>
    <button class="tst-flag" id="flag" onclick="toggleFlag()"><svg aria-hidden="true"><use href="#i-bookmark"/></svg> <span>Flag</span></button>
    <button class="btn btn-primary" id="next" onclick="go(1)">Next</button>
  </div>
</div>

<!-- ═══ SUBMIT CONFIRMATION MODAL ═══ -->
<div class="tst-modal" id="modal">
  <div class="tst-modal-card">
    <div class="tst-modal-ic"><svg aria-hidden="true"><use href="#i-check-circle"/></svg></div>
    <h3>Submit your test?</h3>
    <p id="modal-msg">You've answered all questions. Once submitted, your score will be recorded and added to your profile.</p>
    <div class="tst-modal-actions">
      <button class="btn btn-outline" onclick="closeModal()">Keep going</button>
      <button class="btn btn-accent" onclick="submitTest()">Submit test</button>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function(){
  'use strict';

  /* ═══ CONFIG ═══ */
  const MODE       = <?= json_encode($attempt['mode'] ?? 'practice') ?>;
  const attemptId  = <?= json_encode((string)($attempt['id'] ?? '')) ?>;
  const CANDIDATE  = {
    name: <?= json_encode($candidate_name ?? 'Candidate') ?>,
    id:   <?= json_encode($candidate_id ?? '') ?>
  };
  const CSRF_NAME  = <?= json_encode(csrf_token()) ?>;
  const CSRF_HASH  = <?= json_encode(csrf_hash()) ?>;
  const API_BASE   = <?= json_encode(base_url('api/aptitude/attempts')) ?>;
  const RESULT_URL = <?= json_encode(base_url('aptitude/result/')) ?>;

  /* ═══ STATE ═══ */
  let tabSwitches  = 0;
  const SWITCH_LIMIT = 4;
  let lastLeaveAt  = 0;
  let testStarted  = false;
  let QUESTIONS    = [];
  const answers    = {};      // qIndex -> optIndex
  const flags      = {};      // qIndex -> bool
  let cur          = 0;
  const keys       = ['A','B','C','D','E'];
  let remaining    = 0;
  const tEl        = document.getElementById('time');
  const tBox       = document.getElementById('timer');
  let tick         = null;

  /* ═══ ANTI-CHEAT ═══ */
  function buildWatermark(){
    const wm = document.getElementById('watermark');
    const tag = CANDIDATE.name + ' \u00B7 ' + CANDIDATE.id + ' \u00B7 JobberRecruit';
    let rows = '';
    for(var i=0;i<14;i++){
      var cells = '';
      for(var j=0;j<8;j++) cells += '<span>' + tag + '</span>';
      rows += '<div class="row">' + cells + '</div>';
    }
    wm.innerHTML = rows;
  }

  if (MODE === 'official') {
    document.addEventListener('contextmenu', function(e){ e.preventDefault(); });
    ['copy','cut','paste'].forEach(function(evt){
      document.addEventListener(evt, function(e){ e.preventDefault(); });
    });
    document.addEventListener('selectstart', function(e){
      var node = e.target && e.target.nodeType === 3 ? e.target.parentElement : e.target;
      if (node && node.closest && node.closest('.tst-wrap')) e.preventDefault();
    });
    document.addEventListener('dragstart', function(e){ e.preventDefault(); });
    document.addEventListener('keyup', function(e){
      if (e.key === 'PrintScreen') {
        try { navigator.clipboard && navigator.clipboard.writeText(''); } catch(_){}
        onCapture('Screenshot (Print Screen) detected');
      }
    });
    document.addEventListener('keydown', function(e){
      var k = e.key.toLowerCase();
      if ((e.ctrlKey || e.metaKey) && 'c x a s p u'.indexOf(k) !== -1) { e.preventDefault(); return; }
      if (k === 'f12') { e.preventDefault(); return; }
      if ((e.ctrlKey || e.metaKey) && e.shiftKey && 'i j c s'.indexOf(k) !== -1) { e.preventDefault(); }
    });
    document.addEventListener('visibilitychange', function(){
      if (document.hidden) { blurOn(); if(testStarted) onLeave(); }
      else { blurOff(); }
    });
    window.addEventListener('pageshow', function(){ blurOff(); });
    window.addEventListener('focus', function(){ blurOff(); });
  }

  function blurOn(){ if(testStarted) document.body.classList.add('tst-blurred'); }
  function blurOff(){ document.body.classList.remove('tst-blurred'); }

  function onCapture(reason){
    var banner = document.getElementById('warn-banner');
    var text   = document.getElementById('warn-text');
    text.textContent = reason + '. Capturing test questions is recorded and may void your result.';
    banner.classList.add('show');
    setTimeout(function(){ banner.classList.remove('show'); }, 7000);
  }

  function onLeave(){
    var now = Date.now();
    if (now - lastLeaveAt < 2000) return;
    lastLeaveAt = now;
    tabSwitches++;
    var banner = document.getElementById('warn-banner');
    var text   = document.getElementById('warn-text');
    if (tabSwitches >= SWITCH_LIMIT) {
      text.textContent = 'You left the test ' + tabSwitches + ' times. Your test has been submitted and flagged for review.';
      banner.classList.add('show');
      setTimeout(function(){ submitTest(); }, 1800);
      return;
    } else if (tabSwitches === SWITCH_LIMIT - 1) {
      document.getElementById('fw-count').textContent = tabSwitches;
      document.getElementById('final-warn').classList.add('show');
      return;
    } else {
      text.textContent = 'Leaving the test page was detected (' + tabSwitches + ' of ' + SWITCH_LIMIT + '). This is recorded \u2014 please stay on this page.';
    }
    banner.classList.add('show');
    setTimeout(function(){ banner.classList.remove('show'); }, 7000);
  }

  /* ═══ GATE / START ═══ */
  window.startTest = function(){
    testStarted = true;
    document.getElementById('gate').classList.add('hidden');
    buildWatermark();
    loadQuestions();
  };

  /* ═══ LOAD QUESTIONS ═══ */
  function loadQuestions(){
    if (!attemptId) return;
    fetch(API_BASE + '/' + attemptId, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(res){ return res.json(); })
    .then(function(data){
      if (data.status === 'success') {
        QUESTIONS = data.questions.map(function(q){
          return {
            id: q.id,
            q: q.body,
            opts: q.options.map(function(o){ return o.text; }),
            option_ids: q.options.map(function(o){ return o.id; })
          };
        });
        var expiresAt = new Date(data.attempt.expires_at).getTime();
        var serverTime = data.server_time * 1000;
        remaining = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
        if (remaining <= 0 && data.attempt.status !== 'in_progress') {
          window.location.href = RESULT_URL + attemptId;
          return;
        }
        if (QUESTIONS.length > 0) {
          render();
          startTimer();
        }
      }
    })
    .catch(function(err){ console.error('Failed to load questions:', err); });
  }

  /* ═══ RENDER ═══ */
  function render(){
    if (QUESTIONS.length === 0) return;
    var q = QUESTIONS[cur];
    document.getElementById('qnum').textContent = 'Question ' + (cur+1) + ' of ' + QUESTIONS.length;
    document.getElementById('qbody').textContent = q.q;
    document.getElementById('prog').style.width = ((cur+1)/QUESTIONS.length*100)+'%';
    var opts = document.getElementById('opts');
    opts.innerHTML = '';
    q.opts.forEach(function(o,i){
      var sel = answers[cur]===i ? ' selected':'';
      var el = document.createElement('div');
      el.className = 'tst-opt'+sel;
      el.onclick = function(){ select(i); };
      el.innerHTML = '<span class="tst-opt-key">'+keys[i]+'</span><span class="tst-opt-text">'+o+'</span>';
      opts.appendChild(el);
    });
    document.getElementById('prev').style.visibility = cur===0?'hidden':'visible';
    document.getElementById('next').textContent = cur===QUESTIONS.length-1?'Review & submit':'Next';
    var fl = document.getElementById('flag');
    fl.className = 'tst-flag'+(flags[cur]?' flagged':'');
    renderDots();
  }

  function renderDots(){
    var d = document.getElementById('dots');
    d.innerHTML = '';
    QUESTIONS.forEach(function(_,i){
      var b = document.createElement('button');
      var c = 'tst-dot';
      if(answers[i]!==undefined) c+=' answered';
      if(flags[i]) c+=' flagged';
      if(i===cur) c+=' current';
      b.className = c; b.textContent = i+1;
      b.onclick = function(){ cur=i; render(); };
      d.appendChild(b);
    });
  }

  function select(i){
    answers[cur] = i;
    render();
    // autosave
    var q = QUESTIONS[cur];
    var formData = new FormData();
    formData.append('question_id', q.id);
    formData.append('option_ids[]', q.option_ids[i]);
    formData.append(CSRF_NAME, CSRF_HASH);
    fetch(API_BASE + '/' + attemptId + '/answer', {
      method: 'POST', body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).catch(function(){});
  }

  window.go = function(dir){
    if(dir===1 && cur===QUESTIONS.length-1){ openModal(); return; }
    cur = Math.max(0, Math.min(QUESTIONS.length-1, cur+dir));
    render();
  };

  window.toggleFlag = function(){ flags[cur]=!flags[cur]; render(); };

  function openModal(){
    var answered = Object.keys(answers).length;
    var msg = answered<QUESTIONS.length
      ? 'You\u2019ve answered ' + answered + ' of ' + QUESTIONS.length + ' questions. Unanswered questions will be marked incorrect. Submit anyway?'
      : 'You\u2019ve answered all ' + QUESTIONS.length + ' questions. Once submitted, your score will be recorded and added to your profile.';
    document.getElementById('modal-msg').textContent = msg;
    document.getElementById('modal').classList.add('show');
  }

  window.closeModal = function(){ document.getElementById('modal').classList.remove('show'); };

  window.submitTest = function(){
    var formData = new FormData();
    formData.append('tab_switches', tabSwitches);
    formData.append(CSRF_NAME, CSRF_HASH);
    var modal = document.getElementById('modal');
    if (modal) modal.classList.remove('show');
    fetch(API_BASE + '/' + attemptId + '/submit', {
      method: 'POST', body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(res){ return res.json(); })
    .then(function(data){
      if (data.status === 'success') {
        window.location.href = data.redirect || (RESULT_URL + attemptId);
      } else {
        alert(data.message || 'Submission failed. Please try again.');
      }
    })
    .catch(function(){ window.location.href = RESULT_URL + attemptId; });
  };

  /* ═══ TIMER ═══ */
  function startTimer(){
    if(tick) return;
    tick = setInterval(function(){
      remaining--;
      var m = String(Math.floor(remaining/60)).padStart(2,'0');
      var s = String(remaining%60).padStart(2,'0');
      tEl.textContent = m + ':' + s;
      if(remaining<=60) tBox.className='tst-timer danger';
      else if(remaining<=300) tBox.className='tst-timer warn';
      if(remaining<=0){ clearInterval(tick); submitTest(); }
    },1000);
  }

  /* ═══ PRACTICE MODE: skip gate ═══ */
  if (MODE !== 'official') {
    startTest();
  }
})();
</script>
<?= $this->endSection() ?>
