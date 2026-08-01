<?php $page_title = esc($course->title) . ' – Classroom'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
:root {
  color-scheme: light;
  --brand:#0861A9; --brand-dark:#064A85; --brand-deep:#0A2F57; --brand-light:#E6F0F8;
  --accent:#ED9020; --accent-dark:#C8770E; --accent-light:#FDF1E0;
  --text:#141926; --muted:#5b6577; --bg:#f5f7fb; --white:#fff; --border:#e2e8f2;
  --success:#16a34a; --success-light:#e8f7ee; --danger:#dc2626; --danger-light:#fdeaea;
  --radius:10px; --radius-lg:14px;
  --shadow:0 2px 14px rgba(10,47,87,.08); --shadow-lg:0 14px 40px rgba(10,47,87,.16);
  --transition:.18s ease;
}

/* iOS Safari font visibility fixes */
html { color-scheme: light only; -webkit-text-fill-color: currentColor; }
input, textarea, select, button { color-scheme: light; -webkit-text-fill-color: currentColor; }
input, textarea { background: #fff!important; color: var(--text)!important; -webkit-text-fill-color: var(--text)!important; }

/* ── entry motion ── */
@keyframes gentle-rise { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

/* Classroom Grid Layout */
.cls-wrap { display: flex; flex-direction: column; gap: clamp(16px, 2vw, 22px); animation: gentle-rise .34s ease both; }

/* Hero Banner */
.cls-hero {
  position: relative; overflow: hidden; border-radius: 18px; color: #fff; padding: clamp(20px, 2.8vw, 30px);
  background: linear-gradient(135deg, var(--brand-deep), var(--brand));
}
.cls-hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 85% -20%, rgba(237,144,32,.35), transparent 55%); pointer-events: none; }
.cls-hero.done { background: linear-gradient(135deg, var(--brand-deep), var(--brand)); }
.cls-hero.done::before { background: radial-gradient(circle at 88% -30%, rgba(237,144,32,.4), transparent 50%); }

.cls-hero-grid { position: relative; display: grid; grid-template-columns: 1fr auto; gap: 24px; align-items: center; }
@media (max-width: 720px) { .cls-hero-grid { grid-template-columns: 1fr; text-align: center; } }

.cls-hero .eyebrow { font-size: .7rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; opacity: .85; display: inline-flex; align-items: center; gap: 8px; }
.cls-hero .eyebrow svg { width: 14px; height: 14px; flex-shrink: 0; }
.cls-hero svg { max-width: 22px; max-height: 22px; }
.cls-hero .ring svg, .cls-hero .ring-done svg { max-width: 60px; max-height: 60px; }

.cls-hero h1 { font-family: 'Sora', sans-serif; font-size: clamp(1.4rem, 2.6vw, 2rem); line-height: 1.15; margin: 10px 0 6px; color: #fff; }
.cls-hero .meta { display: flex; flex-wrap: wrap; gap: 16px; font-size: .82rem; opacity: .92; margin-top: 12px; }
@media (max-width: 720px) { .cls-hero .meta { justify-content: center; } }
.cls-hero .meta span { display: inline-flex; align-items: center; gap: 6px; }
.cls-hero .meta svg { width: 15px; height: 15px; }
.cls-hero-cta { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 20px; }
@media (max-width: 720px) { .cls-hero-cta { justify-content: center; } }

.btn-on-dark { background: #fff; color: var(--brand-deep); border: none; font-weight: 700; }
.btn-on-dark:hover { background: #f0f5fb; transform: translateY(-1px); }
.btn-ghost-dark { background: rgba(255,255,255,.12); color: #fff; border: 1px solid rgba(255,255,255,.35); font-weight: 600; }
.btn-ghost-dark:hover { background: rgba(255,255,255,.2); }

/* Progress Ring */
.ring { --p: 0; width: 118px; height: 118px; border-radius: 50%; display: grid; place-items: center; background: conic-gradient(var(--accent) calc(var(--p) * 1%), rgba(255,255,255,.22) 0); flex-shrink: 0; position: relative; }
.ring::before { content: ''; position: absolute; width: 92px; height: 92px; border-radius: 50%; background: rgba(10,47,87,.9); backdrop-filter: blur(2px); }
.cls-hero.done .ring::before { background: rgba(10,47,87,.9); }
.ring b { position: relative; font-family: 'Sora', sans-serif; font-size: 1.5rem; color: #fff; }
.ring i { position: relative; font-style: normal; font-size: .62rem; letter-spacing: .1em; text-transform: uppercase; opacity: .85; color: #fff; }
.ring-wrap { display: flex; flex-direction: column; align-items: center; gap: 8px; }
.ring-done { width: 118px; height: 118px; border-radius: 50%; background: rgba(255,255,255,.16); display: grid; place-items: center; flex-shrink: 0; }
.ring-done svg { width: 52px; height: 52px; color: #fff; }

.verify-chip { display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.28); border-radius: 30px; padding: 7px 15px; font-size: .76rem; margin-top: 16px; }
.verify-chip b { font-family: 'Sora', sans-serif; letter-spacing: .03em; }
.verify-chip .vv { width: 7px; height: 7px; border-radius: 50%; background: #5fe0a8; box-shadow: 0 0 0 3px rgba(95,224,168,.25); }

/* Floating glass stats */
.glass-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 20px; }
@media (max-width: 720px) { .glass-row { grid-template-columns: repeat(2, 1fr); } }
.glass-card { background: rgba(255,255,255,.12); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,.22); border-radius: 12px; padding: 13px 15px; text-align: left; }
.glass-card .gc-ic { width: 28px; height: 28px; border-radius: 8px; background: rgba(255,255,255,.18); display: grid; place-items: center; margin-bottom: 8px; }
.glass-card .gc-ic svg { width: 15px; height: 15px; color: #fff; }
.glass-card b { font-family: 'Sora', sans-serif; font-size: 1.1rem; color: #fff; display: block; line-height: 1; }
.glass-card i { font-style: normal; font-size: .68rem; opacity: .85; letter-spacing: .04em; text-transform: uppercase; color: #fff; }

/* Grid Layout */
.cls-body { display: grid; grid-template-columns: 300px 1fr 288px; gap: 18px; align-items: start; }
@media (max-width: 1200px) {
  .cls-body { grid-template-columns: 270px 1fr; }
  .cls-right { grid-column: 1 / -1; display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
}
@media (max-width: 860px) {
  .cls-body { grid-template-columns: 1fr; }
  .cls-right { grid-template-columns: 1fr; }
}

/* Sidebar Curriculum */
.cur-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; overflow: hidden; position: sticky; top: 84px; }
@media (max-width: 860px) { .cur-card { position: static; } }
.cur-head { padding: 15px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.cur-head b { font-family: 'Sora', sans-serif; font-size: .9rem; color: var(--brand-deep); display: inline-flex; align-items: center; gap: 8px; }
.cur-head svg { width: 16px; height: 16px; color: var(--brand); }
.cur-search { padding: 11px 14px; border-bottom: 1px solid var(--border); }
.cur-search input { width: 100%; border: 1px solid var(--border); border-radius: 9px; padding: 8px 11px; font-size: .8rem; font-family: inherit; }
.cur-search input:focus { outline: 2px solid var(--brand-light); border-color: var(--brand); }

.les { display: flex; gap: 12px; align-items: flex-start; padding: 13px 15px; border-bottom: 1px solid var(--border); cursor: pointer; transition: background .15s; position: relative; text-decoration: none; color: inherit; }
.les:last-child { border-bottom: none; }
.les:hover { background: var(--bg); }
.les.active { background: var(--brand-light); }
.les.active::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: var(--brand); }
.les-num { width: 26px; height: 26px; border-radius: 50%; background: var(--bg); color: var(--muted); font-size: .76rem; font-weight: 700; display: grid; place-items: center; flex-shrink: 0; border: 1px solid var(--border); }
.les.active .les-num { background: var(--brand); color: #fff; border-color: var(--brand); }
.les.done .les-num { background: var(--success-light); color: var(--success); border-color: transparent; }
.les.locked { opacity: .55; cursor: not-allowed; }
.les-main { flex: 1; min-width: 0; }
.les-main b { font-size: .82rem; color: var(--text); display: block; line-height: 1.3; font-weight: 600; }
.les.active .les-main b { color: var(--brand-deep); }
.les-meta { display: flex; align-items: center; gap: 10px; margin-top: 4px; font-size: .68rem; color: var(--muted); }
.les-meta span { display: inline-flex; align-items: center; gap: 4px; }
.les-meta svg { width: 11px; height: 11px; }
.les-type { font-size: .6rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; padding: 2px 7px; border-radius: 20px; background: var(--brand-light); color: var(--brand); }
.les-type.video { background: #fdecd8; color: var(--accent-dark); }
.les-type.quiz { background: #e9e2fb; color: #6b46c1; }
.les-badge { flex-shrink: 0; }
.les-badge svg { width: 16px; height: 16px; }
.les.done .les-badge svg { color: var(--success); }
.les.locked .les-badge svg { color: var(--muted); }

.cur-foot { padding: 14px 16px; background: var(--bg); }
.cur-prog-lbl { display: flex; justify-content: space-between; font-size: .72rem; color: var(--muted); margin-bottom: 6px; font-weight: 600; }
.cur-prog-lbl b { color: var(--success); font-family: 'Sora', sans-serif; }
.cur-bar { height: 7px; border-radius: 20px; background: var(--border); overflow: hidden; }
.cur-fill { height: 100%; border-radius: 20px; background: linear-gradient(90deg, var(--brand), var(--accent)); transition: width .5s; }

/* Player Container */
.les-view { display: flex; flex-direction: column; gap: 16px; min-width: 0; }
.player { position: relative; aspect-ratio: 16/9; background: #0e1420; border-radius: 14px; overflow: hidden; display: grid; place-items: center; }
.player iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
.player-empty { text-align: center; color: #9aa4b5; padding: 20px; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; }
.player-empty .pe-ic { width: 64px; height: 64px; border-radius: 50%; background: rgba(255,255,255,.06); display: grid; place-items: center; margin: 0 auto 14px; }
.player-empty .pe-ic svg { width: 28px; height: 28px; color: #6b7688; }
.player-empty b { display: block; font-family: 'Sora', sans-serif; font-size: 1rem; color: #c9d1dc; margin-bottom: 4px; }
.player-empty p { font-size: .82rem; max-width: 320px; margin: 0 auto 16px; }
.player-expand { position: absolute; right: 14px; bottom: 14px; z-index: 10; width: 36px; height: 36px; border-radius: 8px; border: none; background: rgba(10,25,45,.75); color: #fff; cursor: pointer; display: grid; place-items: center; transition: var(--transition); }
.player-expand:hover { background: rgba(10,25,45,.95); }
.player-expand svg { width: 16px; height: 16px; }

/* Fullscreen expanded state fallback */
.player--expanded { position: fixed!important; inset: 0!important; z-index: 9999!important; width: 100vw!important; height: 100vh!important; border-radius: 0!important; aspect-ratio: auto!important; }

/* Guided reading module */
.player-reading { padding: 40px 24px; text-align: center; color: #fff; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); display: flex; flex-direction: column; align-items: center; gap: 14px; width: 100%; height: 100%; justify-content: center; }
.player-reading .pr-ic { width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,.08); color: var(--accent); display: flex; align-items: center; justify-content: center; }
.player-reading .pr-ic svg { width: 26px; height: 26px; }
.player-reading b { display: block; font-family: 'Sora', sans-serif; font-size: 1.1rem; color: #fff; }
.player-reading p { font-size: .86rem; color: #94a3b8; max-width: 380px; margin-bottom: 8px; }
.pr-actions { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; }

/* Buttons inside player reading */
.btn-on-brand { background: var(--brand); color: #fff; border: none; }
.btn-on-brand:hover { background: var(--brand-dark); }
.btn-outline-light { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.3); }
.btn-outline-light:hover { background: rgba(255,255,255,.1); }

/* Lesson Details & Workspace Tabs */
.les-nav { display: flex; justify-content: space-between; gap: 10px; }
.les-nav button { flex: 1; max-width: 200px; }
.les-nav button:disabled { opacity: .4; cursor: not-allowed; }

.les-detail { background: #white; border: 1px solid var(--border); border-radius: 14px; padding: clamp(16px, 2.4vw, 24px); background: #fff; }
.les-src { display: inline-flex; align-items: center; gap: 8px; font-size: .64rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); border: 1px solid var(--border); border-radius: 20px; padding: 5px 12px; margin-bottom: 14px; }
.les-src svg { width: 12px; height: 12px; color: var(--accent-dark); }
.les-detail h2 { font-family: 'Sora', sans-serif; font-size: 1.25rem; color: var(--brand-deep); margin-bottom: 14px; }
.les-detail h3 { font-size: .82rem; font-weight: 700; color: var(--brand-deep); letter-spacing: .02em; margin: 20px 0 9px; display: flex; align-items: center; gap: 8px; }
.les-detail h3 svg { width: 15px; height: 15px; color: var(--brand); }
.les-detail p { font-size: .9rem; line-height: 1.65; color: var(--text); }

.les-tabs { display: flex; flex-wrap: wrap; gap: 6px 4px; border-bottom: 1px solid var(--border); margin-bottom: 16px; overflow-x: auto; }
.les-tab { border: none; background: none; padding: 9px 12px; font-size: .8rem; font-weight: 600; color: var(--muted); cursor: pointer; white-space: nowrap; border-bottom: 2px solid transparent; transition: var(--transition); display: inline-flex; align-items: center; gap: 6px; flex-shrink: 0; }
@media (max-width: 480px) {
  .les-tabs { gap: 6px; }
  .les-tab { padding: 9px 10px; font-size: .76rem; }
  .les-tab svg { width: 12px; height: 12px; }
}
.les-tab svg { width: 14px; height: 14px; }
.les-tab:hover { color: var(--brand); }
.les-tab.on { color: var(--brand); border-bottom-color: var(--brand); }
.les-tab .ai-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--accent); }
.tab-panel { display: none; animation: gentle-rise .28s ease; }
.tab-panel.on { display: block; }

.obj-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; }
.obj-list li { display: flex; gap: 8px; align-items: flex-start; font-size: .86rem; color: var(--text); line-height: 1.5; }
.obj-list svg { width: 15px; height: 15px; color: var(--success); flex-shrink: 0; margin-top: 2px; }

.res-list { display: flex; flex-direction: column; gap: 8px; margin-top: 4px; }
.res-item { display: flex; align-items: center; gap: 12px; border: 1px solid var(--border); border-radius: 10px; padding: 11px 13px; transition: var(--transition); }
.res-item:hover { border-color: var(--brand); background: var(--brand-light); }
.res-ic { width: 34px; height: 34px; border-radius: 8px; background: var(--brand-light); color: var(--brand); display: grid; place-items: center; flex-shrink: 0; }
.res-ic svg { width: 16px; height: 16px; }
.res-item b { font-size: .82rem; color: var(--brand-deep); display: block; }
.res-item i { font-style: normal; font-size: .7rem; color: var(--muted); }
.res-item .btn { margin-left: auto; }
.mark-done { margin-top: 20px; }

/* AI Assistant */
.ai-panel { background: linear-gradient(135deg, #0d1b30, #0a2f57); border-radius: 14px; padding: clamp(16px, 2.4vw, 22px); color: #fff; text-align: left; }
.ai-panel .ai-head { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; }
.ai-panel .ai-badge { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, var(--brand), var(--accent)); display: grid; place-items: center; flex-shrink: 0; }
.ai-panel .ai-badge svg { width: 18px; height: 18px; color: #fff; }
.ai-panel h4 { font-family: 'Sora', sans-serif; font-size: .95rem; color: #fff; margin: 0; }
.ai-panel .ai-sub { font-size: .74rem; color: #9fb3cc; }
.ai-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 8px; }
.ai-act { display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14); border-radius: 10px; padding: 11px 13px; color: #fff; font-size: .8rem; font-weight: 600; cursor: pointer; transition: var(--transition); text-align: left; border: none; }
.ai-act:hover { background: rgba(255,255,255,.16); border-color: var(--accent); }
.ai-act svg { width: 15px; height: 15px; color: var(--accent); flex-shrink: 0; }
.ai-output { margin-top: 14px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12); border-radius: 10px; padding: 14px; font-size: .84rem; line-height: 1.6; color: #dbe6f2; display: none; }
.ai-output.show { display: block; animation: gentle-rise .3s ease; }

/* Right Column Cards */
.cls-right { display: flex; flex-direction: column; gap: 16px; }
.dash-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 16px; text-align: left; }
.dash-card h4 { font-family: 'Sora', sans-serif; font-size: .8rem; color: var(--brand-deep); margin-bottom: 13px; display: flex; align-items: center; gap: 8px; margin-top: 0; }
.dash-card h4 svg { width: 15px; height: 15px; color: var(--brand); }
.stat-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--border); font-size: .82rem; }
.stat-row:last-child { border-bottom: none; }
.stat-row span { color: var(--muted); }
.stat-row b { font-family: 'Sora', sans-serif; color: var(--brand-deep); }
.ach-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.ach { border: 1px solid var(--border); border-radius: 10px; padding: 11px; text-align: center; transition: var(--transition); }
.ach.earned { border-color: var(--accent); background: var(--accent-light); }
.ach.locked { opacity: .5; }
.ach-ic { width: 32px; height: 32px; border-radius: 8px; background: var(--bg); color: var(--muted); display: grid; place-items: center; margin: 0 auto 7px; }
.ach.earned .ach-ic { background: var(--accent); color: #fff; }
.ach-ic svg { width: 16px; height: 16px; }
.ach b { font-size: .68rem; color: var(--text); display: block; line-height: 1.25; }

.cert-check { display: flex; align-items: center; gap: 8px; font-size: .82rem; padding: 7px 0; color: var(--text); }
.cert-check svg { width: 16px; height: 16px; color: var(--success); flex-shrink: 0; }
.cert-check.pending svg { color: var(--muted); }
.cert-locked { margin-top: 12px; }
.cl-note { font-size: .76rem; color: var(--muted); display: flex; gap: 6px; align-items: flex-start; line-height: 1.4; margin-bottom: 10px; }
.cl-note svg { width: 14px; height: 14px; color: var(--accent-dark); flex-shrink: 0; margin-top: 2px; }
.cert-dl { width: 100%; margin-top: 13px; background: var(--accent); color: var(--brand-deep); border: none; font-weight: 700; justify-content: center; }
.cert-dl:hover { background: var(--accent-dark); color: #fff; }

/* Skills You'll Gain */
.info-card { background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: clamp(16px, 2.4vw, 24px); text-align: left; }
.info-card h3 { font-family: 'Sora', sans-serif; font-size: 1rem; color: var(--brand-deep); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; margin-top: 0; }
.info-card h3 svg { width: 17px; height: 17px; color: var(--brand); }
.skill-tags { display: flex; flex-wrap: wrap; gap: 8px; }
.skill-tag { display: inline-flex; align-items: center; gap: 8px; background: var(--brand-light); color: var(--brand-deep); border-radius: 9px; padding: 8px 13px; font-size: .82rem; font-weight: 600; }
.skill-tag svg { width: 13px; height: 13px; color: var(--brand); }

.outcome-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 12px; margin-top: 6px; }
.outcome { border: 1px solid var(--border); border-radius: 12px; padding: 15px; transition: var(--transition); }
.outcome:hover { border-color: var(--brand); box-shadow: var(--shadow); }
.outcome b { font-family: 'Sora', sans-serif; font-size: .9rem; color: var(--brand-deep); display: block; }
.outcome .sal { font-size: .8rem; color: var(--success); font-weight: 700; margin-top: 5px; display: inline-flex; align-items: center; gap: 4px; }
.outcome .sal svg { width: 13px; height: 13px; }
.outcome i { display: block; font-style: normal; font-size: .7rem; color: var(--muted); margin-top: 4px; }

/* Career Intelligence */
.career-strip { background: linear-gradient(135deg, var(--brand-light), #white); background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: clamp(18px, 2.6vw, 26px); text-align: left; }
.career-strip .cs-head { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.cs-badge { width: 38px; height: 38px; border-radius: 10px; background: var(--brand); color: #fff; display: grid; place-items: center; flex-shrink: 0; }
.cs-badge svg { width: 19px; height: 19px; }
.career-strip h3 { font-family: 'Sora', sans-serif; font-size: 1.05rem; color: var(--brand-deep); margin: 0; }
.career-strip .cs-sub { font-size: .8rem; color: var(--muted); margin-top: 2px; }

.ci-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(155px, 1fr)); gap: 12px; }
.ci-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 15px; transition: var(--transition); }
.ci-card:hover { box-shadow: var(--shadow); transform: translateY(-2px); }
.ci-card .ci-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.ci-card .ci-lbl { font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; }
.ci-card .ci-ic { width: 26px; height: 26px; border-radius: 7px; background: var(--brand-light); color: var(--brand); display: grid; place-items: center; }
.ci-card .ci-ic svg { width: 14px; height: 14px; }
.ci-val { font-family: 'Sora', sans-serif; font-size: 1.4rem; color: var(--brand-deep); line-height: 1; }
.ci-track { height: 5px; border-radius: 20px; background: var(--bg); overflow: hidden; margin-top: 9px; }
.ci-fill { height: 100%; border-radius: 20px; background: linear-gradient(90deg, var(--brand), var(--accent)); width: 0; transition: width 1s cubic-bezier(.4, 0, .2, 1); }
.ci-card.next { background: linear-gradient(135deg, var(--brand-light), #fff); border-color: var(--brand); }
.ci-card.next b { font-family: 'Sora', sans-serif; font-size: .86rem; color: var(--brand-deep); display: block; }
.ci-card.next p { font-size: .72rem; color: var(--muted); margin: 4px 0 9px; }

/* Job matchings */
.jobs-scroll { display: flex; gap: 16px; overflow-x: auto; padding-bottom: 8px; scroll-snap-type: x mandatory; scrollbar-width: thin; }
.job-card { flex: 0 0 268px; scroll-snap-align: start; background: #fff; border: 1px solid var(--border); border-radius: 14px; padding: 16px; transition: var(--transition); }
.job-card:hover { border-color: var(--brand); box-shadow: var(--shadow); }
.job-card .jc-top { display: flex; align-items: center; gap: 12px; margin-bottom: 11px; }
.jc-logo { width: 42px; height: 42px; border-radius: 10px; background: var(--brand-light); color: var(--brand); display: grid; place-items: center; font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1rem; flex-shrink: 0; }
.job-card b { font-size: .88rem; color: var(--brand-deep); display: block; line-height: 1.3; }
.job-card .jc-co { font-size: .74rem; color: var(--muted); }
.jc-meta { display: flex; flex-wrap: wrap; gap: 8px; margin: 11px 0; }
.jc-meta span { font-size: .7rem; color: var(--muted); display: inline-flex; align-items: center; gap: 4px; }
.jc-meta svg { width: 11px; height: 11px; }
.jc-sal { font-size: .82rem; font-weight: 700; color: var(--success); margin-bottom: 11px; }
.jc-actions { display: flex; gap: 8px; }
.jc-actions .btn { flex: 1; justify-content: center; }
.jc-save { flex: 0 0 auto!important; padding: 8px 11px; }

/* Certificate Showcase */
.cert-showcase { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: center; }
@media (max-width: 800px) { .cert-showcase { grid-template-columns: 1fr; } }
.cert-preview { border: 1px solid var(--border); border-radius: 14px; overflow: hidden; background: linear-gradient(135deg, #faf7f0, #fff); box-shadow: var(--shadow-lg); position: relative; aspect-ratio: 1.414 / 1; }
.cert-preview .cp-inner { position: absolute; inset: 14px; border: 2px solid var(--brand); border-radius: 8px; padding: 18px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
.cert-preview .cp-seal { width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, var(--brand), var(--accent)); display: grid; place-items: center; margin-bottom: 10px; }
.cert-preview .cp-seal svg { width: 22px; height: 22px; color: #fff; }
.cert-preview h5 { font-family: 'Sora', sans-serif; font-size: .82rem; color: var(--brand-deep); margin-bottom: 4px; }
.cert-preview .cp-name { font-family: Georgia, serif; font-size: 1.1rem; color: var(--brand); font-weight: 700; margin: 6px 0; }
.cert-preview .cp-course { font-size: .7rem; color: var(--muted); }
.cert-preview .cp-code { position: absolute; bottom: 20px; font-size: .56rem; color: #aeb6c2; letter-spacing: .05em; }
.cert-info h3 { font-family: 'Sora', sans-serif; font-size: 1.3rem; color: var(--brand-deep); margin-bottom: 8px; }
.cert-info p { font-size: .88rem; color: var(--muted); line-height: 1.6; margin-bottom: 16px; }
.cert-actions { display: flex; flex-wrap: wrap; gap: 10px; }

/* Career tools footer */
.cs-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; }
.cs-tool { display: flex; align-items: flex-start; gap: 12px; background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 14px; transition: var(--transition); cursor: pointer; text-decoration: none; color: inherit; }
.cs-tool:hover { border-color: var(--brand); transform: translateY(-2px); box-shadow: var(--shadow); }
.cs-tool-ic { width: 38px; height: 38px; border-radius: 10px; background: var(--brand-light); color: var(--brand); display: grid; place-items: center; flex-shrink: 0; }
.cs-tool-ic svg { width: 18px; height: 18px; }
.cs-tool b { font-size: .86rem; color: var(--brand-deep); display: block; margin-bottom: 2px; }
.cs-tool p { font-size: .72rem; color: var(--muted); line-height: 1.4; margin: 0; }
.cs-tool .cs-arrow { margin-left: auto; color: var(--muted); }
.cs-tool:hover .cs-arrow { color: var(--brand); }

/* Assessment Gate Panel */
.assess-card { background: #fff; border: 1.5px solid var(--brand-deep); border-radius: 14px; padding: clamp(16px, 2.5vw, 26px); box-shadow: var(--shadow-lg); text-align: left; }
.assess-head { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid var(--border); padding-bottom: 16px; margin-bottom: 20px; }
.assess-eyebrow { font-size: .7rem; font-weight: 700; color: var(--brand); text-transform: uppercase; letter-spacing: .08em; display: inline-flex; align-items: center; gap: 6px; }
.assess-eyebrow svg { width: 14px; height: 14px; }
.assess-head h3 { font-family: 'Sora', sans-serif; font-size: 1.15rem; color: var(--brand-deep); margin: 6px 0 4px; }
.assess-head p { font-size: .82rem; color: var(--muted); margin: 0; }
.q-block { margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 20px; }
.q-block:last-of-type { border-bottom: none; }
.q-text { font-size: .9rem; font-weight: 600; color: var(--brand-deep); margin-bottom: 12px; }
.q-opt { display: flex; align-items: center; gap: 8px; font-size: .86rem; color: var(--text); padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 9px; margin-bottom: 8px; cursor: pointer; transition: var(--transition); }
.q-opt:hover { border-color: var(--brand); background: var(--brand-light); }
.q-opt input { margin: 0; width: 16px; height: 16px; cursor: pointer; }

.assess-result { text-align: center; padding: 24px 16px; }
.ar-ic { width: 64px; height: 64px; border-radius: 50%; display: grid; place-items: center; margin: 0 auto 16px; }
.ar-ic.pass { background: var(--success-light); color: var(--success); }
.ar-ic.fail { background: var(--danger-light); color: var(--danger); }
.ar-ic svg { width: 32px; height: 32px; }
.assess-result h3 { font-family: 'Sora', sans-serif; font-size: 1.25rem; margin-bottom: 8px; }
.assess-result p { font-size: .86rem; color: var(--muted); margin-bottom: 20px; }

/* Confetti System */
#confetti-root { position: fixed; inset: 0; z-index: 9999; pointer-events: none; }
.confetti-piece { position: absolute; width: 10px; height: 10px; background: var(--accent); opacity: 0; border-radius: 2px; }
@keyframes confetti-fall {
  0% { transform: translateY(-50px) rotate(0deg); opacity: 1; }
  100% { transform: translateY(105vh) rotate(360deg); opacity: 0; }
}
#confetti-root.burst .confetti-piece {
  animation: confetti-fall 1.5s ease-out forwards;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- SVG SPRITE SHEET -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <defs>
    <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V4a2 2 0 0 0-2-2H6.5A2.5 2.5 0 0 0 4 4.5Z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/></symbol>
    <symbol id="i-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6"/><path d="M8.2 13.9 7 22l5-3 5 3-1.2-8.1"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></symbol>
    <symbol id="i-check-c" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m4.5 12.5 5 5 10-11"/></symbol>
    <symbol id="i-arrow-l" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></symbol>
    <symbol id="i-arrow-r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l6 6-6 6"/></symbol>
    <symbol id="i-bookmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21 12 16 5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z"/></symbol>
    <symbol id="i-zap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h8l-1 8 11-13h-8Z"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
    <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M6 11l6 6 6-6M4 21h16"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-3 8-10V5l-8-3-8 3v7c0 7 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="i-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.4 1 2.3h6c0-.9.4-1.8 1-2.3A7 7 0 0 0 12 2Z"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
    <symbol id="i-star" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></symbol>
    <symbol id="i-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></symbol>
    <symbol id="i-mic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v4M8 23h8"/></symbol>
    <symbol id="i-circle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></symbol>
    <symbol id="i-naira" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 19V5h12v14M6 10h12M6 14h12"/></symbol>
    <symbol id="i-building" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="9" y1="22" x2="9" y2="16"/><line x1="15" y1="22" x2="15" y2="16"/><path d="M9 16h6M8 6h2M8 10h2M14 6h2M14 10h2"/></symbol>
    <symbol id="i-link" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></symbol>
    <symbol id="i-briefcase" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></symbol>
    <symbol id="i-user-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-refresh" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></symbol>
    <symbol id="i-crown" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7z"/></symbol>
    <symbol id="i-grad" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2.5 3 6 3s6-1 6-3v-5"/></symbol>
    <symbol id="i-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></symbol>
  </defs>
</svg>

<div class="content">

  <div class="page-head">
    <div>
      <h1><svg aria-hidden="true"><use href="#i-book"/></svg> Classroom: <?= esc($course->title) ?></h1>
      <p>Interactive classroom environment and verified skills center.</p>
    </div>
    <div class="page-actions">
      <a href="<?= base_url('candidate/my-courses') ?>" class="btn btn-outline btn-sm">
        <svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Back to List
      </a>
    </div>
  </div>

  <div class="cls-wrap">
    
    <!-- 1 · HERO HEADER -->
    <section class="cls-hero <?= $enrollment->status === 'completed' ? 'done' : '' ?>" id="cls-hero">
      <div class="cls-hero-grid">
        <div>
          <div class="eyebrow"><svg aria-hidden="true"><use href="#i-zap"/></svg> Interactive Course Portal</div>
          <h1><?= esc($course->title) ?></h1>
          
          <div class="meta">
            <span class="hero-rating"><svg aria-hidden="true"><use href="#i-star"/></svg> 4.8 (84 reviews)</span>
            <span><svg aria-hidden="true"><use href="#i-user"/></svg> <?= esc($course->instructor ?? 'JobberRecruit Faculty') ?></span>
            <span><svg aria-hidden="true"><use href="#i-clock"/></svg> <?= esc($course->duration ?? 'Self-paced') ?></span>
            <span><svg aria-hidden="true"><use href="#i-shield"/></svg> Verified Certification</span>
          </div>

          <div class="hero-badges">
            <span class="hero-badge"><svg aria-hidden="true"><use href="#i-zap"/></svg> <?= ucfirst(esc($course->level ?? 'professional')) ?></span>
            <span class="hero-badge"><svg aria-hidden="true"><use href="#i-book"/></svg> English (NG)</span>
            <span class="hero-badge"><svg aria-hidden="true"><use href="#i-clock"/></svg> Last updated <?= date('M Y') ?></span>
          </div>

          <!-- Glass Floating stats row -->
          <div class="glass-row">
            <div class="glass-card"><div class="gc-ic"><svg aria-hidden="true"><use href="#i-book"/></svg></div><b><?= count($modules) ?></b><i>Lessons</i></div>
            <div class="glass-card"><div class="gc-ic"><svg aria-hidden="true"><use href="#i-clock"/></svg></div><b><?= esc($course->duration ?? 'Self-paced') ?></b><i>Study Hrs</i></div>
            <div class="glass-card"><div class="gc-ic"><svg aria-hidden="true"><use href="#i-zap"/></svg></div><b>Direct</b><i>Skill gains</i></div>
            <div class="glass-card"><div class="gc-ic"><svg aria-hidden="true"><use href="#i-award"/></svg></div><b>Verified</b><i>Certificate</i></div>
          </div>
        </div>

        <div class="ring-wrap">
          <?php if ($enrollment->status === 'completed'): ?>
            <div class="ring-done" id="progress-indicator"><svg aria-hidden="true"><use href="#i-award"/></svg></div>
            <div class="verify-chip" id="verify-badge"><span class="vv"></span><b>Verified Complete</b></div>
          <?php else: ?>
            <div class="ring" id="progress-indicator" style="--p: 50">
              <b>50%</b>
              <i>Progress</i>
            </div>
            <div class="cls-hero-cta">
              <button class="btn btn-on-dark" id="continue-btn"><svg aria-hidden="true"><use href="#i-zap"/></svg> Continue Learning</button>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- 2 · THREE-COLUMN BODY -->
    <div class="cls-body">
      
      <!-- LEFT Column: Curriculum sidebar -->
      <aside class="cur-card" aria-label="Curriculum Sidebar">
        <div class="cur-head">
          <b><svg aria-hidden="true"><use href="#i-bookmark"/></svg> Course Modules</b>
          <span class="pill pill--reviewed"><?= count($modules) ?> Lessons</span>
        </div>
        <div class="cur-search">
          <input type="search" placeholder="Search curriculum…" id="cur-search">
        </div>
        <div class="cur-scroll" id="curriculum-list">
          <?php if (empty($modules)): ?>
            <div style="padding: 24px; text-align: center; color: var(--muted); font-size: 0.86rem;">
              No lessons have been configured for this course yet.
            </div>
          <?php else: ?>
            <?php foreach ($modules as $idx => $mod): ?>
              <?php 
                $isActive = $activeModule && (int)$activeModule->id === (int)$mod->id;
                $isDone = ($enrollment->status === 'completed' || $idx === 0);
                $typeClass = ($mod->content_source === 'youtube') ? 'video' : 'reading';
              ?>
              <a href="<?= base_url('candidate/my-courses/' . $course->id . '?module_id=' . $mod->id) ?>" 
                 class="les <?= $isActive ? 'active' : '' ?> <?= $isDone ? 'done' : '' ?>"
                 data-les="<?= $idx ?>">
                <span class="les-num"><?= $idx + 1 ?></span>
                <div class="les-main">
                  <b><?= esc($mod->title) ?></b>
                  <div class="les-meta">
                    <span class="les-type <?= $typeClass ?>"><?= ucfirst(esc($mod->content_source)) ?></span>
                    <span><svg aria-hidden="true"><use href="#i-clock"/></svg> 45 mins</span>
                  </div>
                </div>
                <div class="les-badge">
                  <svg aria-hidden="true"><use href="<?= $isDone ? '#i-check-c' : '#i-circle' ?>"/></svg>
                </div>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="cur-foot">
          <div class="cur-prog-lbl">Curriculum Progress <b id="foot-progress-pct"><?= $enrollment->status === 'completed' ? '100%' : '50%' ?></b></div>
          <div class="cur-bar">
            <div class="cur-fill" id="foot-progress-fill" style="width: <?= $enrollment->status === 'completed' ? '100' : '50' ?>%"></div>
          </div>
        </div>
      </aside>

      <!-- CENTER Column: Main Player & Details Workspace -->
      <main class="les-view">
        
        <!-- Interactive Player Canvas -->
        <div class="player" id="player">
          <!-- Multi-state Content boxes managed by script selector -->
          <div class="player-empty" id="player-empty" hidden>
            <div class="pe-ic"><svg aria-hidden="true"><use href="#i-x"/></svg></div>
            <b>Content Unavailable</b>
            <p>This module content is not ready to view. Please try another segment.</p>
            <button class="btn btn-sm btn-ghost-dark" id="retry-video"><svg aria-hidden="true"><use href="#i-refresh"/></svg> Retry reload</button>
          </div>

          <div class="player-reading" id="player-reading" hidden>
            <div class="pr-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></div>
            <b>Guided reading module attached</b>
            <p>This lesson is a guided study. Open the PDF download link below to review at your own pace.</p>
            <div class="pr-actions">
              <button class="btn btn-sm btn-on-brand"><svg aria-hidden="true"><use href="#i-book"/></svg> Read lesson material</button>
              <?php if ($activeModule && $activeModule->content_source === 'upload' && !empty($activeModule->content_file)): ?>
                <a href="<?= base_url('training/content/' . $course->id . '?module_id=' . $activeModule->id) ?>" class="btn btn-sm btn-outline-light"><svg aria-hidden="true"><use href="#i-download"/></svg> Download Syllabus PDF</a>
              <?php endif; ?>
            </div>
          </div>

          <button class="player-expand" id="player-expand" hidden aria-label="Expand video canvas"><svg aria-hidden="true"><use href="#i-crown"/></svg></button>
        </div>

        <div class="les-nav">
          <button class="btn btn-outline" id="prev-les" disabled><svg aria-hidden="true"><use href="#i-arrow-l"/></svg> Previous</button>
          <button class="btn btn-outline" id="next-les">Next lesson <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></button>
        </div>

        <div class="les-detail">
          <span class="les-src"><svg aria-hidden="true"><use href="#i-zap"/></svg> Source channel: <?= esc($activeModule ? strtoupper($activeModule->content_source) : 'N/A') ?></span>
          <h2 id="les-title"><?= esc($activeModule ? $activeModule->title : 'Select a lesson') ?></h2>

          <div class="les-tabs" role="tablist">
            <button class="les-tab on" data-tab="overview" role="tab"><svg aria-hidden="true"><use href="#i-book"/></svg> Overview</button>
            <button class="les-tab" data-tab="notes" role="tab"><svg aria-hidden="true"><use href="#i-doc"/></svg> Notes</button>
            <button class="les-tab" data-tab="transcript" role="tab"><svg aria-hidden="true"><use href="#i-book"/></svg> Transcript</button>
            <button class="les-tab" data-tab="downloads" role="tab"><svg aria-hidden="true"><use href="#i-download"/></svg> Downloads</button>
            <button class="les-tab" data-tab="discussion" role="tab"><svg aria-hidden="true"><use href="#i-chat"/></svg> Discussion</button>
            <button class="les-tab" data-tab="ai" role="tab"><span class="ai-dot" aria-hidden="true"></span> AI Summary</button>
          </div>

          <div class="tab-panel on" data-panel="overview">
            <h3><svg aria-hidden="true"><use href="#i-bulb"/></svg> Learning objectives</h3>
            <ul class="obj-list">
              <li><svg aria-hidden="true"><use href="#i-check"/></svg> Understand core domain concepts and target structures</li>
              <li><svg aria-hidden="true"><use href="#i-check"/></svg> Apply standard industry methodologies to workspace tasks</li>
              <li><svg aria-hidden="true"><use href="#i-check"/></svg> Measure results and build portfolio evidence</li>
            </ul>
            <h3><svg aria-hidden="true"><use href="#i-doc"/></svg> Module Overview &amp; Description</h3>
            <p><?= esc($activeModule && !empty($activeModule->description) ? $activeModule->description : 'No description provided for this lesson module.') ?></p>
          </div>

          <div class="tab-panel" data-panel="notes">
            <p style="color:var(--muted);font-size:.86rem">Your personal learning notes. Text edits autosave locally.</p>
            <textarea style="width:100%;min-height:140px;border:1px solid var(--border);border-radius:10px;padding:12px;font-family:inherit;font-size:.86rem;margin-top:10px" placeholder="Type your session notes here…"></textarea>
          </div>

          <div class="tab-panel" data-panel="transcript">
            <p style="font-size:.86rem;line-height:1.7;color:var(--text)">This lesson segment covers core architectures. We build layout components, map data bindings, configure state machines, and prepare structural modules to support responsive operations…</p>
          </div>

          <div class="tab-panel" data-panel="downloads">
            <div class="res-list">
              <div class="res-item">
                <span class="res-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></span>
                <div><b>Course syllabus workbook</b><i>PDF · 1.5 MB</i></div>
                <button class="btn btn-outline btn-sm">Download</button>
              </div>
              <div class="res-item">
                <span class="res-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></span>
                <div><b>Platform deployment checklist</b><i>PDF · 820 KB</i></div>
                <button class="btn btn-outline btn-sm">Download</button>
              </div>
            </div>
          </div>

          <div class="tab-panel" data-panel="discussion">
            <p style="color:var(--muted);font-size:.86rem;margin-bottom:12px">Contribute to the conversation or post questions.</p>
            <div style="display:flex;gap:10px;margin-bottom:16px">
              <input style="flex:1;border:1px solid var(--border);border-radius:10px;padding:10px 12px;font-family:inherit;font-size:.84rem" placeholder="Ask a question or post a comment…">
              <button class="btn btn-primary btn-sm">Post</button>
            </div>
            <div style="display:flex;gap:12px;padding:12px 0;border-top:1px solid var(--border)">
              <span style="width:34px;height:34px;border-radius:50%;background:var(--brand-light);color:var(--brand);display:grid;place-items:center;font-weight:700;font-size:.78rem;flex-shrink:0">AO</span>
              <div><b style="font-size:.82rem;color:var(--brand-deep)">Adaeze O.</b> <span style="font-size:.7rem;color:var(--muted)">· 2 days ago</span><p style="font-size:.84rem;color:var(--text);margin-top:3px">Great clarity on this module structure! Extremely helpful for review.</p></div>
            </div>
          </div>

          <div class="tab-panel" data-panel="ai">
            <div class="ai-panel">
              <div class="ai-head">
                <span class="ai-badge"><svg aria-hidden="true"><use href="#i-zap"/></svg></span>
                <div><h4>AI Learning Assistant</h4><span class="ai-sub">Powered by JobberRecruit AI · Grounded in course context</span></div>
              </div>
              <div class="ai-actions">
                <button type="button" class="ai-act" data-ai="summarize"><svg aria-hidden="true"><use href="#i-doc"/></svg> Summarize Lesson</button>
                <button type="button" class="ai-act" data-ai="explain"><svg aria-hidden="true"><use href="#i-bulb"/></svg> Explain Topic</button>
                <button type="button" class="ai-act" data-ai="quiz"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Practice Quiz</button>
                <button type="button" class="ai-act" data-ai="takeaways"><svg aria-hidden="true"><use href="#i-star"/></svg> Takeaways</button>
                <button type="button" class="ai-act" data-ai="flashcards"><svg aria-hidden="true"><use href="#i-copy"/></svg> Flashcards</button>
                <button type="button" class="ai-act" data-ai="interview"><svg aria-hidden="true"><use href="#i-mic"/></svg> Mock Prep</button>
              </div>
              <div class="ai-output" id="ai-output"></div>
            </div>
          </div>

          <button class="btn btn-primary mark-done" id="mark-done"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Mark Lesson Complete</button>
        </div>

        <!-- ═══ FINAL ASSESSMENT CARD ═══ -->
        <section class="assess-card" id="assess-card" hidden>
          <div class="assess-head">
            <div>
              <span class="assess-eyebrow"><svg aria-hidden="true"><use href="#i-shield"/></svg> Final Assessment</span>
              <h3>Prove your knowledge to earn certification</h3>
              <p>Answer the multi-choice questions below. You need at least <b>70%</b> to pass. You can retry as many times as needed.</p>
            </div>
            <button class="btn btn-outline btn-sm" id="assess-close" aria-label="Close assessment"><svg aria-hidden="true"><use href="#i-x"/></svg></button>
          </div>

          <div class="assess-body" id="assess-body">
            <div class="q-block">
              <p class="q-text"><b>1.</b> What is the most critical metric indicating learning application?</p>
              <label class="q-opt"><input type="radio" name="q1" value="a"> Length of study hours</label>
              <label class="q-opt"><input type="radio" name="q1" value="b"> Practical conversion and verifiable skill outcomes</label>
              <label class="q-opt"><input type="radio" name="q1" value="c"> Number of total sessions started</label>
            </div>
            <div class="q-block">
              <p class="q-text"><b>2.</b> How should you tailor career application portfolios?</p>
              <label class="q-opt"><input type="radio" name="q2" value="a"> Grounding arguments in target role demands and market metrics</label>
              <label class="q-opt"><input type="radio" name="q2" value="b"> Submitting uniform CV entries to all listings</label>
              <label class="q-opt"><input type="radio" name="q2" value="c"> Relying purely on visual layout styling overrides</label>
            </div>
            <div class="q-block">
              <p class="q-text"><b>3.</b> What is the ideal way to address base salary caps?</p>
              <label class="q-opt"><input type="radio" name="q3" value="a"> Accept the cap silently without further questions</label>
              <label class="q-opt"><input type="radio" name="q3" value="b"> Leverage alternative benefits, review times, and performance bonuses</label>
              <label class="q-opt"><input type="radio" name="q3" value="c"> End the conversation immediately in protest</label>
            </div>
            <button class="btn btn-primary btn-block" id="assess-submit"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Submit Answers</button>
          </div>

          <!-- pass/fail displays -->
          <div class="assess-result pass" id="assess-pass" hidden>
            <div class="ar-ic pass"><svg aria-hidden="true"><use href="#i-check-c"/></svg></div>
            <h3>Assessment Passed! 🎉</h3>
            <p>Excellent score! You got <b id="pass-score">100%</b>. Your verified certificate has been unlocked.</p>
            <button class="btn btn-primary" id="assess-claim"><svg aria-hidden="true"><use href="#i-download"/></svg> Claim verified certificate</button>
          </div>
          <div class="assess-result fail" id="assess-fail" hidden>
            <div class="ar-ic fail"><svg aria-hidden="true"><use href="#i-refresh"/></svg></div>
            <h3>Not quite ready yet</h3>
            <p>You scored <b id="fail-score">33%</b>. (Required: 70%). Review modules and retry when ready.</p>
            <button class="btn btn-primary" id="assess-retry"><svg aria-hidden="true"><use href="#i-refresh"/></svg> Retake Assessment</button>
          </div>
        </section>

      </main>

      <!-- RIGHT Column: Dashboard telemetry -->
      <aside class="cls-right">
        
        <div class="dash-card">
          <h4><svg aria-hidden="true"><use href="#i-chart"/></svg> Learning telemetry</h4>
          <div class="stat-row"><span>Status</span><b style="color:var(--success)" id="stat-enrollment-status"><?= ucfirst(esc($enrollment->status)) ?></b></div>
          <div class="stat-row"><span>Lessons completed</span><b id="stat-lessons-count">1 of <?= count($modules) ?></b></div>
          <div class="stat-row"><span>Hours studied</span><b>5.4</b></div>
          <div class="stat-row"><span>Average accuracy</span><b>82%</b></div>
        </div>

        <div class="dash-card">
          <h4><svg aria-hidden="true"><use href="#i-award"/></svg> Achievements</h4>
          <div class="ach-grid">
            <div class="ach <?= $enrollment->status === 'completed' ? 'earned' : 'locked' ?>" id="ach-completed"><div class="ach-ic"><svg aria-hidden="true"><use href="#i-grad"/></svg></div><b>Completed</b></div>
            <div class="ach locked" id="ach-assessment"><div class="ach-ic"><svg aria-hidden="true"><use href="#i-shield"/></svg></div><b>Passed</b></div>
            <div class="ach earned"><div class="ach-ic"><svg aria-hidden="true"><use href="#i-zap"/></svg></div><b>Streak</b></div>
            <div class="ach locked"><div class="ach-ic"><svg aria-hidden="true"><use href="#i-crown"/></svg></div><b>Top tier</b></div>
          </div>
        </div>

        <div class="dash-card">
          <h4><svg aria-hidden="true"><use href="#i-shield"/></svg> Certificate progress</h4>
          <div class="cert-check done"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Core lessons complete</div>
          <div class="cert-check <?= $enrollment->status === 'completed' ? 'done' : 'pending' ?>" id="chk-assessment"><svg aria-hidden="true"><use href="<?= $enrollment->status === 'completed' ? '#i-check-c' : '#i-circle' ?>"/></svg> Final assessment passed</div>
          <div class="cert-check <?= $enrollment->status === 'completed' ? 'done' : 'pending' ?>" id="chk-ready"><svg aria-hidden="true"><use href="<?= $enrollment->status === 'completed' ? '#i-check-c' : '#i-circle' ?>"/></svg> Certificate ready</div>
          
          <div class="cert-locked" id="cert-locked" <?= $enrollment->status === 'completed' ? 'hidden' : '' ?>>
            <p class="cl-note"><svg aria-hidden="true"><use href="#i-shield"/></svg> Complete the final assessment to unlock your verified certificate.</p>
            <button class="btn btn-primary cert-dl" id="start-assessment"><svg aria-hidden="true"><use href="#i-award"/></svg> Take Final Assessment</button>
          </div>
          
          <button class="btn cert-dl" id="dl-cert-2" <?= $enrollment->status === 'completed' ? '' : 'hidden' ?>><svg aria-hidden="true"><use href="#i-download"/></svg> Download Verified PDF</button>
        </div>

      </aside>

    </div>

    <!-- 3 · SKILLS AND OUTCOMES -->
    <section class="info-card">
      <h3><svg aria-hidden="true"><use href="#i-zap"/></svg> Skills you'll gain</h3>
      <div class="skill-tags">
        <span class="skill-tag"><svg aria-hidden="true"><use href="#i-check"/></svg> Industry strategy</span>
        <span class="skill-tag"><svg aria-hidden="true"><use href="#i-check"/></svg> Analytics modeling</span>
        <span class="skill-tag"><svg aria-hidden="true"><use href="#i-check"/></svg> Content optimization</span>
        <span class="skill-tag"><svg aria-hidden="true"><use href="#i-check"/></svg> Brand building</span>
        <span class="skill-tag"><svg aria-hidden="true"><use href="#i-check"/></svg> Direct negotiation</span>
      </div>
      <h3 style="margin-top:22px"><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Typical Career Outcomes</h3>
      <p style="font-size:.84rem;color:var(--muted);margin-bottom:6px">Prepares you for the following positions in the regional market:</p>
      <div class="outcome-grid">
        <div class="outcome"><b>Lead Domain Manager</b><span class="sal"><svg aria-hidden="true"><use href="#i-naira"/></svg> 300k – 500k / mo</span><i>Lagos area average</i></div>
        <div class="outcome"><b>Digital Operations Strategist</b><span class="sal"><svg aria-hidden="true"><use href="#i-naira"/></svg> 250k – 450k / mo</span><i>Lagos area average</i></div>
        <div class="outcome"><b>Brand Strategic Planner</b><span class="sal"><svg aria-hidden="true"><use href="#i-naira"/></svg> 350k – 600k / mo</span><i>Lagos area average</i></div>
        <div class="outcome"><b>Executive Content Consultant</b><span class="sal"><svg aria-hidden="true"><use href="#i-naira"/></svg> 200k – 380k / mo</span><i>Lagos area average</i></div>
      </div>
      <p style="font-size:.7rem;color:var(--muted);margin-top:12px;font-style:italic">Salary ranges represent broad industry estimates for guidance only.</p>
    </section>

    <!-- 4 · CAREER INTELLIGENCE -->
    <section class="career-strip">
      <div class="cs-head">
        <span class="cs-badge" aria-hidden="true"><svg aria-hidden="true"><use href="#i-chart"/></svg></span>
        <div>
          <h3>Your Career Intelligence score</h3>
          <p class="cs-sub">Track how this course matches up to your job application scores.</p>
        </div>
      </div>
      <div class="ci-grid">
        <div class="ci-card"><div class="ci-top"><span class="ci-lbl">Career Readiness</span><span class="ci-ic"><svg aria-hidden="true"><use href="#i-user-check"/></svg></span></div><div class="ci-val">76%</div><div class="ci-track"><div class="ci-fill" data-fill="76"></div></div></div>
        <div class="ci-card"><div class="ci-top"><span class="ci-lbl">Resume Score</span><span class="ci-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></span></div><div class="ci-val">70%</div><div class="ci-track"><div class="ci-fill" data-fill="70"></div></div></div>
        <div class="ci-card"><div class="ci-top"><span class="ci-lbl">ATS Match</span><span class="ci-ic"><svg aria-hidden="true"><use href="#i-shield"/></svg></span></div><div class="ci-val">84%</div><div class="ci-track"><div class="ci-fill" data-fill="84"></div></div></div>
        <div class="ci-card"><div class="ci-top"><span class="ci-lbl">Interview prep</span><span class="ci-ic"><svg aria-hidden="true"><use href="#i-mic"/></svg></span></div><div class="ci-val">68%</div><div class="ci-track"><div class="ci-fill" data-fill="68"></div></div></div>
        <div class="ci-card"><div class="ci-top"><span class="ci-lbl">Employability</span><span class="ci-ic"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></span></div><div class="ci-val">72%</div><div class="ci-track"><div class="ci-fill" data-fill="72"></div></div></div>
        <div class="ci-card"><div class="ci-top"><span class="ci-lbl">Skill Growth</span><span class="ci-ic"><svg aria-hidden="true"><use href="#i-zap"/></svg></span></div><div class="ci-val">+20%</div><div class="ci-track"><div class="ci-fill" data-fill="90"></div></div></div>
        <div class="ci-card next"><b>Next course recommendation</b><p>Platform marketing ad strategies</p><a href="<?= base_url('candidate/my-courses') ?>" class="btn btn-primary btn-sm">View details</a></div>
      </div>
    </section>

    <!-- 5 · RECOMMENDED JOBS -->
    <section class="info-card">
      <h3><svg aria-hidden="true"><use href="#i-briefcase"/></svg> Open roles matched to this course</h3>
      <div class="jobs-scroll">
        <div class="job-card">
          <div class="jc-top"><span class="jc-logo">PL</span><div><b>Social Media Manager</b><span class="jc-co">Paylode · Lagos</span></div></div>
          <div class="jc-meta"><span><svg aria-hidden="true"><use href="#i-building"/></svg> On-site</span><span><svg aria-hidden="true"><use href="#i-clock"/></svg> Full-time</span></div>
          <div class="jc-sal">₦300k – ₦450k / mo</div>
          <div class="jc-actions"><a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm">Apply</a><button class="btn btn-outline btn-sm jc-save" aria-label="Save job"><svg aria-hidden="true"><use href="#i-bookmark"/></svg></button></div>
        </div>
        <div class="job-card">
          <div class="jc-top"><span class="jc-logo">MH</span><div><b>Digital Marketing Executive</b><span class="jc-co">MediaHive · Remote</span></div></div>
          <div class="jc-meta"><span><svg aria-hidden="true"><use href="#i-link"/></svg> Remote</span><span><svg aria-hidden="true"><use href="#i-clock"/></svg> Full-time</span></div>
          <div class="jc-sal">₦250k – ₦400k / mo</div>
          <div class="jc-actions"><a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm">Apply</a><button class="btn btn-outline btn-sm jc-save" aria-label="Save job"><svg aria-hidden="true"><use href="#i-bookmark"/></svg></button></div>
        </div>
        <div class="job-card">
          <div class="jc-top"><span class="jc-logo">BR</span><div><b>Brand &amp; Content Strategist</b><span class="jc-co">Brandr Co · Abuja</span></div></div>
          <div class="jc-meta"><span><svg aria-hidden="true"><use href="#i-building"/></svg> Hybrid</span><span><svg aria-hidden="true"><use href="#i-clock"/></svg> Contract</span></div>
          <div class="jc-sal">₦350k – ₦500k / mo</div>
          <div class="jc-actions"><a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm">Apply</a><button class="btn btn-outline btn-sm jc-save" aria-label="Save job"><svg aria-hidden="true"><use href="#i-bookmark"/></svg></button></div>
        </div>
      </div>
    </section>

    <!-- 6 · CERTIFICATE SHOWCASE -->
    <section class="info-card">
      <div class="cert-showcase">
        <div class="cert-preview" aria-label="Certificate preview mockup">
          <div class="cp-inner">
            <span class="cp-seal"><svg aria-hidden="true"><use href="#i-award"/></svg></span>
            <h5>CERTIFICATE OF COMPLETION</h5>
            <div class="cp-name"><?= esc($candidateName) ?></div>
            <div class="cp-course"><?= esc($course->title) ?></div>
            <span class="cp-code" id="mock-cert-code">Verification Code: Pending completion</span>
          </div>
        </div>
        <div class="cert-info">
          <h3>Your verified certificate</h3>
          <p>Upon passing, this certification carries a unique verification identifier code employers can check on JobberRecruit — verified proof of your training.</p>
          <div class="cert-actions">
            <button class="btn btn-primary" id="btn-cert-view-main" disabled><svg aria-hidden="true"><use href="#i-eye"/></svg> View Certificate</button>
            <button class="btn btn-outline" id="dl-cert-3" disabled><svg aria-hidden="true"><use href="#i-download"/></svg> Download PDF</button>
          </div>
        </div>
      </div>
    </section>

    <!-- 7 · CAREER TOOLS FOOTER STRIP -->
    <section class="career-strip">
      <div class="cs-head">
        <span class="cs-badge" aria-hidden="true"><svg aria-hidden="true"><use href="#i-zap"/></svg></span>
        <div>
          <h3>Turn this course into a career move</h3>
          <p class="cs-sub">Put your certifications to work with our career guidance tools.</p>
        </div>
      </div>
      <div class="cs-grid">
        <a href="<?= base_url('candidate/resume-builder') ?>" class="cs-tool">
          <span class="cs-tool-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></span>
          <div><b>AI Resume Builder</b><p>Import your certifications directly into your CV.</p></div>
          <span class="cs-arrow" aria-hidden="true"><svg style="width:15px;height:15px"><use href="#i-arrow-r"/></svg></span>
        </a>
        <a href="<?= base_url('candidate/resume-builder') ?>" class="cs-tool">
          <span class="cs-tool-ic"><svg aria-hidden="true"><use href="#i-shield"/></svg></span>
          <div><b>ATS CV Auditor</b><p>Analyze how applicant tracking systems read your files.</p></div>
          <span class="cs-arrow" aria-hidden="true"><svg style="width:15px;height:15px"><use href="#i-arrow-r"/></svg></span>
        </a>
        <a href="<?= base_url('candidate/career-tools/salary-negotiation') ?>" class="cs-tool">
          <span class="cs-tool-ic"><svg aria-hidden="true"><use href="#i-chat"/></svg></span>
          <div><b>Salary Negotiation</b><p>Practice board negotiations before signing agreements.</p></div>
          <span class="cs-arrow" aria-hidden="true"><svg style="width:15px;height:15px"><use href="#i-arrow-r"/></svg></span>
        </a>
        <a href="<?= base_url('jobs') ?>" class="cs-tool">
          <span class="cs-tool-ic"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></span>
          <div><b>Recommended Jobs</b><p>Browse positions looking for your verified credentials.</p></div>
          <span class="cs-arrow" aria-hidden="true"><svg style="width:15px;height:15px"><use href="#i-arrow-r"/></svg></span>
        </a>
      </div>
    </section>

  </div>
</div>

<div id="confetti-root" aria-hidden="true"></div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function(){
  'use strict';
  
  // ── Toast Helper ──
  function toast(msg){
    var t = document.createElement("div");
    t.style.cssText = "position:fixed;left:50%;bottom:26px;transform:translateX(-50%) translateY(8px);background:var(--brand-deep);color:#fff;font-size:.82rem;font-weight:600;padding:11px 20px;border-radius:10px;box-shadow:var(--shadow-lg);z-index:2000;opacity:0;transition:opacity .25s,transform .25s";
    t.textContent = msg; document.body.appendChild(t);
    requestAnimationFrame(function(){ t.style.opacity=1; t.style.transform="translateX(-50%) translateY(0)"; });
    setTimeout(function(){ t.style.opacity=0; setTimeout(function(){ t.remove(); }, 300); }, 2600);
  }

  // ── Confetti Burst ──
  function celebrateSuccess(){
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    var root = document.getElementById('confetti-root');
    if (!root) {
      root = document.createElement('div');
      root.id = 'confetti-root';
      document.body.appendChild(root);
    }
    root.innerHTML = '';
    var colors = ['#0861A9','#ED9020','#16a34a','#0A2F57'];
    for (var i = 0; i < 32; i++){
      var p = document.createElement('span');
      p.className = 'confetti-piece';
      p.style.left = (Math.random()*100) + '%';
      p.style.background = colors[i % colors.length];
      p.style.animationDelay = (Math.random()*0.3) + 's';
      p.style.animationDuration = (1.2 + Math.random()*0.6) + 's';
      root.appendChild(p);
    }
    root.classList.add('burst');
    setTimeout(function(){ root.classList.remove('burst'); root.innerHTML=''; }, 1900);
  }

  // ── Setup Curriculum Data ──
  var LESSONS = [];
  <?php if (!empty($modules)): ?>
    <?php foreach ($modules as $idx => $mod): ?>
      LESSONS.push({
        id: <?= (int)$mod->id ?>,
        title: <?= json_encode($mod->title) ?>,
        src: <?= json_encode(ucfirst($mod->content_source)) ?>,
        type: <?= json_encode($mod->content_source) ?>,
        videoId: <?= json_encode(($mod->content_source === 'youtube' && !empty($mod->youtube_url)) ? $this->getYoutubeEmbedUrl($mod->youtube_url) : null) ?>
      });
    <?php endforeach; ?>
  <?php endif; ?>

  var curLes = 0;
  var completedLessons = new Set([0]); // Mark first module as complete by default

  // ── Player Controls ──
  function renderPlayer(i){
    var l = LESSONS[i];
    if (!l) return;

    var playerContainer = document.getElementById("player");
    var plEmpty = document.getElementById("player-empty");
    var plReading = document.getElementById("player-reading");
    var plExpand = document.getElementById("player-expand");

    // Remove existing iframe
    var existingIframe = playerContainer.querySelector("iframe");
    if (existingIframe) existingIframe.remove();

    plEmpty.hidden = true;
    plReading.hidden = true;
    plExpand.hidden = true;

    if (l.type === 'youtube') {
      if (l.videoId) {
        var f = document.createElement("iframe");
        f.src = l.videoId + "?rel=0&modestbranding=1&playsinline=1";
        f.setAttribute("allow", "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture");
        f.setAttribute("allowfullscreen", "");
        f.style.cssText = "position:absolute;inset:0;width:100%;height:100%;border:0;";
        playerContainer.insertBefore(f, playerContainer.firstChild);
        plExpand.hidden = false;
      } else {
        plEmpty.hidden = false;
      }
    } else if (l.type === 'upload' || l.type === 'text') {
      plReading.hidden = false;
    } else {
      plEmpty.hidden = false;
    }
  }

  function selectLesson(i){
    if (i < 0 || i >= LESSONS.length) return;
    curLes = i;

    // Update active UI classes
    document.querySelectorAll(".les").forEach(function(el){
      el.classList.toggle("active", parseInt(el.dataset.les, 10) === i);
    });

    document.getElementById("les-title").textContent = LESSONS[i].title;
    document.getElementById("prev-les").disabled = (i === 0);
    document.getElementById("next-les").disabled = (i === LESSONS.length - 1);

    renderPlayer(i);
  }

  // Bind lesson click triggers
  document.querySelectorAll(".les").forEach(function(el){
    el.addEventListener("click", function(e){
      e.preventDefault();
      selectLesson(parseInt(el.dataset.les, 10));
    });
  });

  document.getElementById("prev-les").addEventListener("click", function(){ selectLesson(curLes - 1); });
  document.getElementById("next-les").addEventListener("click", function(){ selectLesson(curLes + 1); });

  // ── Curriculum search filtering ──
  var searchInput = document.getElementById("cur-search");
  if (searchInput) {
    searchInput.addEventListener("input", function(){
      var query = this.value.toLowerCase();
      document.querySelectorAll(".les").forEach(function(el){
        var title = el.querySelector(".les-main b").textContent.toLowerCase();
        el.style.display = (title.indexOf(query) !== -1) ? "flex" : "none";
      });
    });
  }

  // ── Tab panels switcher ──
  document.querySelectorAll(".les-tab").forEach(function(tab){
    tab.addEventListener("click", function(){
      document.querySelectorAll(".les-tab").forEach(function(t){ t.classList.remove("on"); });
      document.querySelectorAll(".tab-panel").forEach(function(p){ p.classList.remove("on"); });
      tab.classList.add("on");
      var panel = document.querySelector('.tab-panel[data-panel="'+tab.dataset.tab+'"]');
      if (panel) panel.classList.add("on");
    });
  });

  // ── Mark Lesson Complete ──
  var markDoneBtn = document.getElementById("mark-done");
  if (markDoneBtn) {
    markDoneBtn.addEventListener("click", function(){
      completedLessons.add(curLes);
      var currentLesItem = document.querySelector('.les[data-les="'+curLes+'"]');
      if (currentLesItem) {
        currentLesItem.classList.add("done");
        var iconUse = currentLesItem.querySelector(".les-badge use");
        if (iconUse) iconUse.setAttribute("href", "#i-check-c");
      }

      // Calculate progress percentage
      var pct = Math.round((completedLessons.size / LESSONS.length) * 100);
      
      // Update UI displays
      var progIndicator = document.getElementById("progress-indicator");
      if (progIndicator) {
        if (progIndicator.classList.contains("ring")) {
          progIndicator.style.setProperty("--p", pct);
          progIndicator.querySelector("b").textContent = pct + "%";
        }
      }
      document.getElementById("foot-progress-pct").textContent = pct + "%";
      document.getElementById("foot-progress-fill").style.width = pct + "%";
      document.getElementById("stat-lessons-count").textContent = completedLessons.size + " of " + LESSONS.length;

      toast("Lesson marked complete!");
    });
  }

  // ── AI assistant grounded responses ──
  var AI_RESPONSES = {
    summarize: "This lesson introduces the core parameters of this domain topic. It highlights architectural workflows, standard industry guidelines, and direct evaluation steps necessary for success.",
    explain: "Explain topic triggered. Grounded in this curriculum, we prioritize step-by-step structure mapping, clean data integrations, and responsive visual grid templates.",
    quiz: "Practice Quiz: (1) What is the pass target for this course final assessment? (2) Name the primary benefit of certified credentials in Lagos recruiter searches.",
    takeaways: "• Complete all curriculum modules prior to starting the final exam.\n• Align CV listings with target role keyword checks.\n• Always prioritize evidence outcomes over generic task descriptions.",
    flashcards: "Card 1: Setup mode? A: Stage to specify base parameters.\nCard 2: Assessment gate? A: Prove domain mastery to unlock Verified Certificates.",
    interview: "Sample Question: Describe a situation where you had to structure a layout grid layout in a project context. What were your specific metrics?"
  };

  document.querySelectorAll(".ai-act").forEach(function(btn){
    btn.addEventListener("click", function(){
      var out = document.getElementById("ai-output");
      out.classList.add("show");
      out.textContent = "Grounding AI response…";
      setTimeout(function(){
        out.textContent = AI_RESPONSES[btn.dataset.ai] || "AI support is analyzing module context…";
      }, 400);
    });
  });

  // ── Career Intelligence animations ──
  var ciObserver = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if (e.isIntersecting){
        e.target.querySelectorAll(".ci-fill").forEach(function(f){ f.style.width = f.dataset.fill + "%"; });
        ciObserver.unobserve(e.target);
      }
    });
  }, { threshold: 0.2 });
  var ciGrid = document.querySelector(".ci-grid");
  if (ciGrid) ciObserver.observe(ciGrid);

  // ── Fullscreen video expansion ──
  var expandBtn = document.getElementById("player-expand");
  if (expandBtn) {
    expandBtn.addEventListener("click", function(){
      var pl = document.getElementById("player");
      var fs = document.fullscreenElement || document.webkitFullscreenElement;
      if (!fs) {
        var req = pl.requestFullscreen || pl.webkitRequestFullscreen;
        if (req) {
          req.call(pl).catch(function(){ pl.classList.toggle("player--expanded", true); });
        } else {
          pl.classList.toggle("player--expanded", true);
        }
      } else {
        (document.exitFullscreen || document.webkitExitFullscreen || function(){}).call(document);
      }
    });
  }

  // ── Final Assessment Gate Logic ──
  var startAssessmentBtn = document.getElementById("start-assessment");
  if (startAssessmentBtn) {
    startAssessmentBtn.addEventListener("click", function(){
      var card = document.getElementById("assess-card");
      card.hidden = false;
      document.getElementById("assess-body").hidden = false;
      document.getElementById("assess-pass").hidden = true;
      document.getElementById("assess-fail").hidden = true;
      card.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  }

  var closeAssessmentBtn = document.getElementById("assess-close");
  if (closeAssessmentBtn) {
    closeAssessmentBtn.addEventListener("click", function(){
      document.getElementById("assess-card").hidden = true;
    });
  }

  var submitAssessmentBtn = document.getElementById("assess-submit");
  if (submitAssessmentBtn) {
    submitAssessmentBtn.addEventListener("click", function(){
      var answers = { q1: "b", q2: "a", q3: "b" };
      var correct = 0;
      var total = Object.keys(answers).length;

      for (var q in answers) {
        var selected = document.querySelector('input[name="'+q+'"]:checked');
        if (selected && selected.value === answers[q]) {
          correct++;
        }
      }

      var score = Math.round((correct / total) * 100);
      document.getElementById("assess-body").hidden = true;

      if (score >= 70) {
        // Pass result
        document.getElementById("pass-score").textContent = score + "%";
        document.getElementById("assess-pass").hidden = false;

        // Perform AJAX request to finalize database enrollment status and issue certificate
        fetch('<?= base_url("training/complete/" . $course->id) ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
        })
        .then(function(res){ return res.json(); })
        .then(function(data){
          if (data.success) {
            // Update sidebar elements
            var chkAss = document.getElementById("chk-assessment");
            if (chkAss) {
              chkAss.classList.remove("pending");
              chkAss.classList.add("done");
              chkAss.querySelector("use").setAttribute("href", "#i-check-c");
            }
            var chkRdy = document.getElementById("chk-ready");
            if (chkRdy) {
              chkRdy.classList.remove("pending");
              chkRdy.classList.add("done");
              chkRdy.querySelector("use").setAttribute("href", "#i-check-c");
            }

            var lockedCard = document.getElementById("cert-locked");
            if (lockedCard) lockedCard.hidden = true;

            var downloadBtn2 = document.getElementById("dl-cert-2");
            if (downloadBtn2) downloadBtn2.hidden = false;

            // Update Certificate Preview panel
            var previewCodeText = document.getElementById("mock-cert-code");
            if (previewCodeText) {
              previewCodeText.textContent = "Verification Code: " + data.certificate_code;
            }

            // Enable view certificate button
            var viewCertBtn = document.getElementById("btn-cert-view-main");
            if (viewCertBtn) {
              viewCertBtn.disabled = false;
              viewCertBtn.addEventListener("click", function(){
                window.location.href = '<?= base_url("training/certificate/view/") ?>' + '/' + data.certificate_id;
              });
            }

            // Enable download pdf button
            var dlCertBtn = document.getElementById("dl-cert-3");
            if (dlCertBtn) {
              dlCertBtn.disabled = false;
              dlCertBtn.addEventListener("click", function(){
                window.location.href = '<?= base_url("training/certificate/download/") ?>' + '/' + data.certificate_id;
              });
            }

            // Update status indicator
            document.getElementById("stat-enrollment-status").textContent = "Completed";
            document.getElementById("ach-completed").classList.remove("locked");
            document.getElementById("ach-completed").classList.add("earned");
            document.getElementById("ach-assessment").classList.remove("locked");
            document.getElementById("ach-assessment").classList.add("earned");

            // Radial Ring completes
            var hero = document.getElementById("cls-hero");
            if (hero) hero.classList.add("done");

            var indicator = document.getElementById("progress-indicator");
            if (indicator) {
              // Convert indicator element to Completed crown
              indicator.className = "ring-done";
              indicator.innerHTML = '<svg aria-hidden="true"><use href="#i-award"/></svg>';
              
              // Add verified badge under completion ring
              var badgeDiv = document.createElement("div");
              badgeDiv.className = "verify-chip";
              badgeDiv.innerHTML = '<span class="vv"></span><b>Verified Complete</b>';
              indicator.parentNode.appendChild(badgeDiv);
            }

            celebrateSuccess();
            toast("Congratulations! Course verified and certificate unlocked.");
          } else {
            toast(data.message || "Failed to issue course certificate.");
          }
        })
        .catch(function(){
          toast("Network error while completing certification.");
        });
      } else {
        // Fail result
        document.getElementById("fail-score").textContent = score + "%";
        document.getElementById("assess-fail").hidden = false;
      }
    });
  }

  var retryBtn = document.getElementById("assess-retry");
  if (retryBtn) {
    retryBtn.addEventListener("click", function(){
      document.getElementById("assess-fail").hidden = true;
      document.getElementById("assess-body").hidden = false;
    });
  }

  // Continue learning link trigger
  var continueBtn = document.getElementById("continue-btn");
  if (continueBtn) {
    continueBtn.addEventListener("click", function(){
      selectLesson(0);
    });
  }

  // Initial render on load
  if (LESSONS.length > 0) {
    selectLesson(0);
  }
})();
</script>
<?= $this->endSection() ?>