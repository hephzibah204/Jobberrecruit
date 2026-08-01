<?php $page_title = 'AI Interview Studio'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<style>
/* ── AI Mock Interview Premium Studio Styles ── */
:root {
  --brand: #0861A9; 
  --brand-dark: #064A85; 
  --brand-deep: #0A2F57; 
  --brand-light: #E6F0F8;
  --accent: #ED9020; 
  --accent-dark: #C8770E; 
  --accent-light: #FDF1E0;
  --text: #141926; 
  --muted: #5b6577; 
  --bg: #f5f7fb; 
  --white: #fff; 
  --border: #e2e8f2;
  --success: #16a34a; 
  --success-light: #e8f7ee; 
  --danger: #dc2626; 
  --danger-light: #fdeaea;
  --radius: 10px; 
  --radius-lg: 14px;
  --shadow: 0 2px 14px rgba(10,47,87,.08); 
  --shadow-lg: 0 14px 40px rgba(10,47,87,.16);
  --transition: .18s ease;
}

.btn-accent {
  background: var(--accent);
  color: var(--brand-deep);
  border-color: var(--accent);
}
.btn-accent:hover {
  background: var(--accent-dark);
  border-color: var(--accent-dark);
  color: var(--brand-deep);
}
.btn-ghost-w {
  background: rgba(255,255,255,.1);
  color: #fff;
  border-color: rgba(255,255,255,.28);
}
.btn-ghost-w:hover {
  background: rgba(255,255,255,.2);
}

/* pills */
.pill {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: .66rem;
  font-weight: 700;
  padding: 4px 11px;
  border-radius: 20px;
  letter-spacing: .02em;
  white-space: nowrap;
}
.pill--pending { background: var(--accent-light); color: var(--accent-dark); }
.pill--brand { background: var(--brand-light); color: var(--brand); }
.pill--success { background: var(--success-light); color: var(--success); }

/* studio hero */
.studio-hero {
  position: relative;
  overflow: hidden;
  border-radius: var(--radius-lg);
  color: #fff;
  padding: clamp(22px, 3.2vw, 34px);
  background: radial-gradient(ellipse 60% 90% at 88% 8%, rgba(237,144,32,.22) 0%, transparent 55%), linear-gradient(150deg, #0A2F57 0%, #064A85 55%, #0861A9 100%);
  box-shadow: var(--shadow);
  margin-bottom: 24px;
}
.studio-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: .5;
  background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size: 44px 44px;
  -webkit-mask-image: radial-gradient(ellipse 90% 90% at 70% 20%, #000 25%, transparent 80%);
  mask-image: radial-gradient(ellipse 90% 90% at 70% 20%, #000 25%, transparent 80%);
}
.hero-grid {
  position: relative;
  display: grid;
  grid-template-columns: 1fr auto;
  gap: clamp(20px, 3vw, 40px);
  align-items: center;
}
@media (max-width:860px) {
  .hero-grid { grid-template-columns: 1fr; }
}
.hero-badges {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 14px;
}
.hb {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 5px 13px;
  border-radius: 20px;
}
.hb svg { width: 12px; height: 12px; }
.hb--premium { background: rgba(237,144,32,.18); border: 1px solid rgba(237,144,32,.45); color: #FDD9A8; }
.hb--live { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.25); color: rgba(255,255,255,.9); }
.hb .pulse {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: var(--accent);
  box-shadow: 0 0 0 0 rgba(237,144,32,.6);
  animation: pulse 2.2s infinite;
}
@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(237,144,32,.55); }
  70% { box-shadow: 0 0 0 9px rgba(237,144,32,0); }
  100% { box-shadow: 0 0 0 0 rgba(237,144,32,0); }
}
.studio-hero h1 {
  font-size: clamp(1.5rem, 3.1vw, 2.15rem);
  font-weight: 800;
  line-height: 1.15;
  margin-bottom: 9px;
}
.studio-hero h1 span { color: var(--accent); }
.hero-sub { font-size: .9rem; color: rgba(255,255,255,.85); max-width: 520px; }
.hero-chips {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 10px;
  margin-top: 20px;
  max-width: 560px;
}
@media (max-width:560px) {
  .hero-chips { grid-template-columns: 1fr; }
}
.hchip {
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.16);
  border-radius: 12px;
  padding: 12px 14px;
  backdrop-filter: blur(8px);
}
.hchip-lbl {
  font-size: .62rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: rgba(255,255,255,.55);
  display: flex;
  align-items: center;
  gap: 6px;
}
.hchip-lbl svg { width: 12px; height: 12px; color: var(--accent); }
.hchip-val { font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1.05rem; margin-top: 3px; }
.hchip-val small { font-family: 'Inter', sans-serif; font-weight: 500; font-size: .68rem; color: rgba(255,255,255,.6); }
.hchip .goal-track { height: 5px; border-radius: 20px; background: rgba(255,255,255,.15); overflow: hidden; margin-top: 7px; }
.hchip .goal-fill { height: 100%; border-radius: 20px; background: var(--accent); }

/* hero AI orb */
.hero-orb { position: relative; width: clamp(150px, 16vw, 196px); height: clamp(150px, 16vw, 196px); margin-inline: auto; }
.hero-orb svg { width: 100%; height: 100%; }
.orb-ring { transform-origin: center; animation: orbit 14s linear infinite; }
.orb-ring--2 { animation-duration: 22s; animation-direction: reverse; }
@keyframes orbit { to { transform: rotate(360deg); } }
.orb-core { animation: breathe 3.6s ease-in-out infinite; }
@keyframes breathe { 0%, 100% { opacity: .85; } 50% { opacity: 1; } }
@media (max-width:860px) { .hero-orb { display: none; } }

/* stats row */
.stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: clamp(10px, 1.4vw, 16px);
  margin-bottom: 24px;
}
@media (max-width:1100px) { .stats { grid-template-columns: repeat(2, 1fr); } }
@media (max-width:520px) { .stats { grid-template-columns: 1fr; } }
.stat {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 16px 16px 13px;
  position: relative;
  overflow: hidden;
  transition: var(--transition);
}
.stat:hover { box-shadow: var(--shadow); transform: translateY(-2px); }
.stat::before {
  content: '';
  position: absolute;
  left: 0;
  top: 13px;
  bottom: 13px;
  width: 3.5px;
  border-radius: 0 4px 4px 0;
  background: var(--st-bar, var(--brand));
}
.stat-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px; }
.stat-ic {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--st-icbg, var(--brand-light));
  color: var(--st-ic, var(--brand));
  transition: transform .25s ease;
}
.stat:hover .stat-ic { transform: scale(1.12) rotate(-4deg); }
.stat-ic svg { width: 17px; height: 17px; }
.stat-num { font-family: 'Sora', sans-serif; font-weight: 800; font-size: clamp(1.35rem, 2.4vw, 1.75rem); color: var(--brand-deep); line-height: 1.1; }
.stat-lbl { font-size: .74rem; color: var(--muted); font-weight: 500; margin-top: 1px; }
.trend { display: inline-flex; align-items: center; gap: 4px; font-size: .66rem; font-weight: 700; padding: 3px 9px; border-radius: 20px; }
.trend svg { width: 10px; height: 10px; }
.trend--up { background: var(--success-light); color: var(--success); }
.trend--flat { background: var(--bg); color: var(--muted); }
.spark { width: 100%; height: 30px; margin-top: 9px; }
.spark path.line { fill: none; stroke: var(--brand); stroke-width: 2; stroke-linecap: round; }
.spark path.area { fill: url(#sparkfill); stroke: none; opacity: .5; }

/* studio layout */
.studio-grid {
  display: grid;
  grid-template-columns: minmax(0, 1.9fr) minmax(280px, 1fr);
  gap: clamp(14px, 1.8vw, 20px);
  align-items: start;
}
@media (max-width:1150px) { .studio-grid { grid-template-columns: 1fr; } }

/* glass config card */
.glass-card {
  position: relative;
  border-radius: var(--radius-lg);
  border: 1px solid rgba(226,232,242,.9);
  overflow: hidden;
  background: linear-gradient(160deg, rgba(255,255,255,.94) 0%, rgba(245,249,254,.9) 100%);
  backdrop-filter: blur(14px) saturate(160%);
  box-shadow: 0 8px 32px rgba(10,47,87,.09);
}
.glass-card::before {
  content: '';
  position: absolute;
  inset: 0 0 auto 0;
  height: 3px;
  background: linear-gradient(90deg, var(--brand) 0%, var(--brand-dark) 55%, var(--accent) 100%);
}
.cfg-head { display: flex; align-items: center; gap: 13px; padding: 20px 22px 16px; border-bottom: 1px solid var(--border); }
.cfg-head-ic {
  width: 42px;
  height: 42px;
  border-radius: 12px;
  background: linear-gradient(135deg, var(--brand-deep), var(--brand));
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.cfg-head-ic svg { width: 19px; height: 19px; }
.cfg-head h2 { font-size: 1rem; font-weight: 800; color: var(--brand-deep); }
.cfg-head p { font-size: .76rem; color: var(--muted); }
.cfg-body { padding: 20px 22px 22px; }
.cfg-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 18px; }
@media (max-width:680px) { .cfg-grid { grid-template-columns: 1fr; } }
.span2 { grid-column: 1/-1; }
fieldset.cfg-set { border: none; margin: 0; padding: 0; min-width: 0; }
legend.lbl { padding: 0; }
.cfg-divider { grid-column: 1/-1; display: flex; align-items: center; gap: 11px; margin-top: 4px; }
.cfg-divider span { font-size: .66rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--muted); white-space: nowrap; }
.cfg-divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }

/* form elements */
.input, .select {
  width: 100%;
  min-height: 44px;
  padding: 10px 14px;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  font-family: 'Inter', sans-serif;
  font-size: 16px;
  background: #fff;
  color: var(--text);
  transition: var(--transition);
}
.input:focus, .select:focus {
  outline: none;
  border-color: var(--brand);
  box-shadow: 0 0 0 3px rgba(8,97,169,.12);
}
.select {
  appearance: none;
  -webkit-appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%235b6577' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  padding-right: 36px;
  cursor: pointer;
}
label.lbl { display: block; font-size: .74rem; font-weight: 600; color: var(--muted); margin-bottom: 6px; }
.lbl-opt { font-weight: 500; color: #8fa0b8; font-size: .9em; }
.field-ic { position: relative; }
.field-ic>svg {
  position: absolute;
  left: 13px;
  top: 50%;
  transform: translateY(-50%);
  width: 16px;
  height: 16px;
  color: var(--muted);
  pointer-events: none;
}
.field-ic .input { padding-left: 38px; }
.hint { font-size: .7rem; color: var(--muted); margin-top: 5px; }

/* segmented control */
.seg { display: flex; gap: 6px; flex-wrap: wrap; }
.seg input { position: absolute; opacity: 0; pointer-events: none; }
.seg label {
  flex: 1;
  min-width: 96px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  padding: 10px 12px;
  min-height: 44px;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  background: #white;
  font-size: .8rem;
  font-weight: 600;
  color: var(--muted);
  cursor: pointer;
  transition: var(--transition);
  text-align: center;
  touch-action: manipulation;
}
.seg label svg { width: 15px; height: 15px; }
.seg label:hover { border-color: var(--brand); color: var(--brand); }
.seg input:checked+label { background: var(--brand-light); border-color: var(--brand); color: var(--brand); }

/* device checks */
.devtests { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media (max-width:560px) { .devtests { grid-template-columns: 1fr; } }
.devtest {
  display: flex;
  align-items: center;
  gap: 12px;
  border: 1.5px solid var(--border);
  border-radius: 12px;
  padding: 13px 15px;
  background: #fff;
  transition: var(--transition);
}
.devtest[data-state="pass"] { border-color: #bfe6cd; background: var(--success-light); }
.devtest[data-state="fail"] { border-color: #f3c1c1; background: var(--danger-light); }
.devtest[data-state="testing"] { border-color: var(--brand); }
.dt-ic {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: var(--brand-light);
  color: var(--brand);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.dt-ic svg { width: 16px; height: 16px; }
.devtest[data-state="pass"] .dt-ic { background: #d3efdd; color: var(--success); }
.devtest[data-state="fail"] .dt-ic { background: #f8d7d7; color: var(--danger); }
.dt-info { flex: 1; min-width: 0; }
.dt-info b { display: block; font-size: .8rem; color: var(--brand-deep); }
.dt-info i { font-style: normal; font-size: .68rem; color: var(--muted); display: block; line-height: 1.45; }
.devtest[data-state="fail"] .dt-info i { color: #a13333; }
.dt-spin {
  width: 16px;
  height: 16px;
  border: 2.5px solid var(--brand-light);
  border-top-color: var(--brand);
  border-radius: 50%;
  animation: spin .7s linear infinite;
  flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }
.mic-bars { display: flex; align-items: flex-end; gap: 2.5px; height: 16px; flex-shrink: 0; }
.mic-bars i { width: 3.5px; border-radius: 2px; background: var(--success); animation: micbar 1s ease-in-out infinite; }
.mic-bars i:nth-child(1) { height: 35%; animation-delay: 0s; }
.mic-bars i:nth-child(2) { height: 75%; animation-delay: .15s; }
.mic-bars i:nth-child(3) { height: 100%; animation-delay: .3s; }
.mic-bars i:nth-child(4) { height: 60%; animation-delay: .45s; }
.mic-bars i:nth-child(5) { height: 40%; animation-delay: .6s; }
@keyframes micbar { 0%,100% { transform: scaleY(.5); } 50% { transform: scaleY(1); } }

/* live session summary */
.sess-sum { margin-top: 20px; border: 1px dashed #cfe2f2; background: linear-gradient(135deg, #f4f9fe, #fdf8f0); border-radius: 12px; padding: 15px 17px; }
.sess-sum-head { display: flex; align-items: center; gap: 8px; font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--brand-dark); margin-bottom: 10px; }
.sess-sum-head svg { width: 13px; height: 13px; color: var(--accent-dark); }
.sess-facts { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
@media (max-width:640px) { .sess-facts { grid-template-columns: 1fr; } }
.sfact { background: #fff; border: 1px solid var(--border); border-radius: 10px; padding: 10px 13px; }
.sfact i { font-style: normal; font-size: .64rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); display: block; }
.sfact b { font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1rem; color: var(--brand-deep); }
.skills-line { margin-top: 11px; }
.skills-line i { font-style: normal; font-size: .64rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); display: block; margin-bottom: 6px; }
.chips { display: flex; gap: 6px; flex-wrap: wrap; }
.chip { font-size: .68rem; font-weight: 600; padding: 4px 11px; border-radius: 20px; background: #white; border: 1px solid var(--border); color: var(--brand-dark); white-space: nowrap; }
.chip--hi { background: var(--brand-light); border-color: #cfe2f2; color: var(--brand); }

/* launch cta */
.btn-launch {
  position: relative;
  width: 100%;
  min-height: 58px;
  font-size: 1rem;
  font-weight: 700;
  font-family: 'Sora', sans-serif;
  letter-spacing: .01em;
  border-radius: 13px;
  overflow: hidden;
  background: linear-gradient(120deg, var(--accent) 0%, #f2a437 55%, var(--accent) 100%);
  color: var(--brand-deep);
  border: 1.5px solid var(--accent-dark);
  box-shadow: 0 8px 22px rgba(237,144,32,.35);
  transition: transform .18s ease, box-shadow .18s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.btn-launch:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(237,144,32,.45); }
.btn-launch:active { transform: translateY(0); }
.btn-launch svg { width: 19px; height: 19px; }
.btn-launch::after {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  width: 45%;
  background: linear-gradient(100deg, transparent, rgba(255,255,255,.55), transparent);
  transform: translateX(-160%) skewX(-18deg);
  animation: shine 3.2s ease-in-out infinite;
  will-change: transform;
}
@keyframes shine {
  0%, 55% { transform: translateX(-160%) skewX(-18deg); }
  85%, 100% { transform: translateX(290%) skewX(-18deg); }
}
.btn-launch[disabled] { pointer-events: none; filter: saturate(.6); }
.launch-note { display: flex; align-items: center; justify-content: center; gap: 6px; font-size: .7rem; color: var(--muted); margin-top: 9px; text-align: center; }
.launch-note svg { width: 12px; height: 12px; color: var(--success); flex-shrink: 0; }

/* right sidebar */
.side-col { display: flex; flex-direction: column; gap: clamp(14px, 1.8vw, 20px); min-width: 0; }
.card-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px 10px; border-bottom: 1px solid var(--border); }
.card-title { font-family: 'Sora', sans-serif; font-size: .88rem; font-weight: 800; color: var(--brand-deep); display: flex; align-items: center; gap: 6px; }
.card-title svg { width: 16px; height: 16px; color: var(--brand); }
.card-link { font-size: .74rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; color: var(--brand); }
.card-link svg { width: 12px; height: 12px; }
.card-body { padding: 16px 18px 18px; }

/* recruiter preview */
.recruiter-card { position: relative; overflow: hidden; }
.rec-top { display: flex; align-items: center; gap: 13px; }
.rec-ava {
  position: relative;
  width: 58px;
  height: 58px;
  border-radius: 16px;
  flex-shrink: 0;
  background: linear-gradient(135deg, var(--brand-deep), var(--brand));
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 6px 16px rgba(8,97,169,.28);
}
.rec-ava svg { width: 32px; height: 32px; }
.rec-ava .live { position: absolute; bottom: -3px; right: -3px; width: 15px; height: 15px; border-radius: 50%; background: var(--success); border: 2.5px solid #fff; }
.rec-id b { display: block; font-family: 'Sora', sans-serif; font-weight: 800; font-size: .95rem; color: var(--brand-deep); }
.rec-id i { font-style: normal; font-size: .72rem; color: var(--muted); }
.rec-wave { display: flex; align-items: flex-end; gap: 3px; height: 22px; margin-left: auto; }
.rec-wave i { width: 4px; border-radius: 2px; background: var(--brand); animation: micbar 1.15s ease-in-out infinite; }
.rec-wave i:nth-child(1) { height: 40%; }
.rec-wave i:nth-child(2) { height: 90%; animation-delay: .12s; }
.rec-wave i:nth-child(3) { height: 65%; animation-delay: .24s; }
.rec-wave i:nth-child(4) { height: 100%; animation-delay: .36s; }
.rec-wave i:nth-child(5) { height: 55%; animation-delay: .48s; }
.rec-quote { font-size: .8rem; color: var(--text); background: var(--bg); border: 1px solid var(--border); border-radius: 11px; padding: 11px 13px; line-height: 1.55; position: relative; margin-top: 12px; margin-bottom: 12px; }
.rec-quote::before {
  content: '';
  position: absolute;
  top: -6px;
  left: 22px;
  width: 10px;
  height: 10px;
  background: var(--bg);
  border-left: 1px solid var(--border);
  border-top: 1px solid var(--border);
  transform: rotate(45deg);
}
.rec-tips { list-style: none; display: flex; flex-direction: column; gap: 8px; padding-left: 0; }
.rec-tips li { display: flex; gap: 9px; font-size: .76rem; color: var(--text); line-height: 1.5; }
.rec-tips svg { width: 14px; height: 14px; color: var(--accent-dark); flex-shrink: 0; margin-top: 2px; }

/* recent sessions list */
.sess-item { display: flex; align-items: center; gap: 11px; padding: 11px 0; border-bottom: 1px solid var(--border); }
.sess-item:last-child { border-bottom: none; padding-bottom: 2px; }
.sess-item:first-child { padding-top: 2px; }
.sess-ic {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: var(--bg);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--brand);
  flex-shrink: 0;
}
.sess-ic svg { width: 15px; height: 15px; }
.sess-info { flex: 1; min-width: 0; }
.sess-info b { display: block; font-size: .78rem; font-weight: 600; color: var(--brand-deep); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sess-info i { font-style: normal; font-size: .66rem; color: var(--muted); }

/* empty state */
.empty { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px; padding: 26px 14px; }
.empty-ic { width: 56px; height: 56px; border-radius: 16px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; margin: 0 auto; }
.empty-ic svg { width: 26px; height: 26px; }

/* quick actions list */
.qa-list { display: flex; flex-direction: column; gap: 10px; }
.qa {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 14px;
  border: 1.5px solid var(--border);
  border-radius: 10px;
  background: #fff;
  font-size: .8rem;
  font-weight: 600;
  color: var(--brand-deep);
  transition: var(--transition);
}
.qa:hover { border-color: var(--brand); background: var(--brand-light); color: var(--brand); text-decoration: none; }
.qa svg { width: 16px; height: 16px; color: var(--muted); }
.qa:hover svg { color: var(--brand); }
.qa .cnt { font-size: .68rem; color: var(--muted); font-weight: 500; }

/* achievements */
.ach-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
.ach {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  aspect-ratio: 1;
  border-radius: 12px;
  background: var(--bg);
  border: 1.5px solid var(--border);
  cursor: pointer;
  transition: var(--transition);
}
.ach svg { width: 18px; height: 18px; color: var(--muted); transition: transform .2s ease; }
.ach:hover svg { transform: scale(1.18); }
.ach span { font-size: .56rem; font-weight: 700; color: var(--muted); margin-top: 5px; text-align: center; display: block; line-height: 1.25; overflow: hidden; text-overflow: ellipsis; max-width: 95%; white-space: nowrap; }
.ach--won { background: var(--brand-light); border-color: #cfe2f2; }
.ach--won svg { color: var(--brand); }
.ach--won span { color: var(--brand-dark); }
.ach--locked { opacity: .65; }

/* why candidates get hired benefits */
.benefits { display: grid; grid-template-columns: repeat(3, 1fr); gap: clamp(14px, 1.8vw, 20px); margin-top: 14px; }
@media (max-width:960px) { .benefits { grid-template-columns: repeat(2, 1fr); } }
@media (max-width:600px) { .benefits { grid-template-columns: 1fr; } }
.ben { background: #fff; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 22px 20px; transition: var(--transition); }
.ben:hover { box-shadow: var(--shadow); transform: translateY(-2px); }
.ben-ic {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: var(--brand-light);
  color: var(--brand);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 14px;
}
.ben-ic svg { width: 20px; height: 20px; }
.ben h3 { font-family: 'Sora', sans-serif; font-weight: 800; font-size: .94rem; color: var(--brand-deep); margin-bottom: 6px; }
.ben p { font-size: .78rem; color: var(--muted); line-height: 1.5; }
.ben--accent { background: linear-gradient(135deg, var(--brand-deep), #064a85); color: #fff; border: none; }
.ben--accent .ben-ic { background: rgba(255,255,255,.1); color: #fff; }
.ben--accent h3 { color: #fff; }
.ben--accent p { color: rgba(255,255,255,.8); }

/* after-interview report preview */
.report-card { position: relative; margin-top: 32px; overflow: hidden; }
.report-tag { position: absolute; top: 14px; right: 18px; }
.report-grid { display: grid; grid-template-columns: 1.1fr 1.9fr; gap: clamp(20px, 3.2vw, 40px); align-items: start; }
@media (max-width:880px) { .report-grid { grid-template-columns: 1fr; } }
.radar-wrap { display: flex; flex-direction: column; align-items: center; gap: 20px; }
.radar { width: 100%; max-width: 320px; height: auto; }
.radar .grid-poly { fill: none; stroke: var(--border); stroke-width: 1; }
.radar .axis-line { stroke: var(--border); stroke-width: 1; }
.radar .shape { fill: rgba(8,97,169,.14); stroke: var(--brand); stroke-width: 2.2; }
.radar .dot { fill: var(--brand); }
.radar text { font-family: 'Inter', sans-serif; font-size: 10px; font-weight: 600; fill: var(--muted); }
.score-line { display: flex; align-items: center; gap: 16px; width: 100%; }
.score-ring { position: relative; width: 76px; height: 76px; flex-shrink: 0; }
.score-ring svg { width: 76px; height: 76px; transform: rotate(-90deg); }
.score-ring .track { fill: none; stroke: var(--bg); stroke-width: 8; }
.score-ring .prog { fill: none; stroke: var(--brand); stroke-width: 8; stroke-linecap: round; stroke-dasharray: 201; stroke-dashoffset: 44; }
.score-ring .num { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1.15rem; color: var(--brand-deep); line-height: 1; }
.score-ring .num i { font-style: normal; font-size: .56rem; font-weight: 700; color: var(--muted); letter-spacing: .06em; }
.score-copy b { display: block; font-family: 'Sora', sans-serif; font-weight: 800; font-size: .92rem; color: var(--brand-deep); }
.score-copy p { font-size: .74rem; color: var(--muted); line-height: 1.55; }
.rep-lists { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width:560px) { .rep-lists { grid-template-columns: 1fr; } }
.rep-list h4 { display: flex; align-items: center; gap: 7px; font-size: .76rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; margin-bottom: 9px; }
.rep-list h4 svg { width: 14px; height: 14px; }
.rep-list--str h4 { color: var(--success); }
.rep-list--wk h4 { color: var(--accent-dark); }
.rep-list ul { list-style: none; display: flex; flex-direction: column; gap: 7px; padding-left: 0; margin-bottom: 0; }
.rep-list li { display: flex; gap: 8px; font-size: .76rem; color: var(--text); line-height: 1.5; }
.rep-list li::before { content: ''; width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; margin-top: 8px; }
.rep-list--str li::before { background: var(--success); }
.rep-list--wk li::before { background: var(--accent); }
.rec-notes { border: 1px solid var(--border); border-left: 3.5px solid var(--brand); border-radius: 10px; background: var(--bg); padding: 13px 15px; margin-top: 14px; }
.rec-notes b { display: flex; align-items: center; gap: 7px; font-size: .74rem; font-weight: 700; color: var(--brand-deep); margin-bottom: 5px; }
.rec-notes b svg { width: 13px; height: 13px; color: var(--brand); }
.rec-notes p { font-size: .76rem; color: var(--text); line-height: 1.6; margin-bottom: 0; }
.rep-next { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 16px; }
@media (max-width:640px) { .rep-next { grid-template-columns: 1fr; } }
.mini-list h4 { font-size: .72rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
.mini-item { display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--border); }
.mini-item:last-child { border-bottom: none; }
.pick-ic { width: 34px; height: 34px; border-radius: 9px; background: var(--bg); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; color: var(--brand); flex-shrink: 0; }
.pick-ic svg { width: 14px; height: 14px; }
.mini-item b { display: block; font-size: .76rem; font-weight: 600; color: var(--brand-deep); line-height: 1.35; }
.mini-item i { font-style: normal; font-size: .66rem; color: var(--muted); }
.timeline { list-style: none; position: relative; margin-top: 16px; padding-left: 26px; }
.timeline::before { content: ''; position: absolute; left: 8px; top: 6px; bottom: 6px; width: 2px; border-radius: 2px; background: var(--border); }
.timeline li { position: relative; padding: 0 0 15px; }
.timeline li:last-child { padding-bottom: 0; }
.timeline li::before { content: ''; position: absolute; left: -24px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background: #fff; border: 3px solid var(--brand); }
.timeline li.done::before { background: var(--brand); }
.timeline li.next::before { border-color: var(--accent); box-shadow: 0 0 0 4px rgba(237,144,32,.18); }
.timeline li:last-child { padding-bottom: 0; }
.timeline li::before { content: ''; position: absolute; left: -30px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background: #fff; border: 3px solid var(--brand); }
.timeline li.done::before { background: var(--brand); }
.timeline li.next::before { border-color: var(--accent); box-shadow: 0 0 0 4px rgba(237,144,32,.18); }
.timeline b { display: block; font-size: .78rem; font-weight: 700; color: var(--brand-deep); }
.timeline p { font-size: .72rem; color: var(--muted); line-height: 1.5; }

/* notice */
.notice { display: flex; gap: 9px; align-items: flex-start; font-size: .78rem; border-radius: 10px; padding: 12px 14px; border: 1px solid; margin-bottom: 16px; }
.notice svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 2px; }
.notice--info { background: var(--brand-light); border-color: #cfe2f2; color: var(--brand-dark); }

/* mobile sticky launch bar */
.mobile-cta { position: fixed; left: 0; right: 0; bottom: 0; z-index: 950; display: none; gap: 10px; padding: 10px clamp(14px, 4vw, 18px); padding-bottom: max(10px, env(safe-area-inset-bottom, 0)); background: rgba(255,255,255,.94); -webkit-backdrop-filter: saturate(180%) blur(12px); backdrop-filter: saturate(180%) blur(12px); border-top: 1px solid var(--border); transition: transform .28s ease; }
.mobile-cta.hidden { transform: translateY(110%); }
.mobile-cta .btn { flex: 1; }
@media (max-width:1024px) { .mobile-cta { display: flex; } }

/* toast */
.toast-panel { position: fixed; bottom: 24px; left: 50%; transform: translate(-50%, 20px); z-index: 1400; display: flex; align-items: center; gap: 10px; background: var(--brand-deep); color: #fff; font-size: .82rem; font-weight: 600; padding: 13px 20px; border-radius: 12px; box-shadow: var(--shadow-lg); opacity: 0; visibility: hidden; transition: opacity .25s ease, transform .25s ease, visibility .25s; max-width: min(420px, calc(100vw - 32px)); }
.toast-panel.show { opacity: 1; visibility: visible; transform: translate(-50%, 0); }
.toast-panel svg { width: 17px; height: 17px; color: var(--accent); flex-shrink: 0; }

/* debug panel */
#dbg-panel { position: fixed; left: 8px; bottom: 8px; z-index: 1500; width: 290px; max-height: 70vh; background: #0A2F57; color: #fff; font: 11px/1.55 ui-monospace, Menlo, Consolas, monospace; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,.4); overflow: hidden; display: flex; flex-direction: column; }
#dbg-panel h4 { background: rgba(0,0,0,.25); color: var(--accent); font: 700 11px/1.4 ui-monospace, monospace; padding: 8px 10px; letter-spacing: .04em; text-transform: uppercase; display: flex; justify-content: space-between; align-items: center; }
#dbg-panel h4 button { background: none; border: none; color: #fff; cursor: pointer; font-size: 13px; line-height: 1; padding: 2px 4px; }
#dbg-cfg { padding: 8px 10px; max-height: 34vh; overflow-y: auto; }
#dbg-cfg div { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
#dbg-cfg b { color: #8fd0ff; }
#dbg-url { padding: 8px 10px; border-top: 1px solid rgba(255,255,255,.15); word-break: break-all; color: #cbd5e1; font-size: 10px; }

/* chips examples underneath Job Title */
.job-chips { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 9px; max-height: 0; overflow: hidden; opacity: 0; transition: max-height .25s ease, opacity .2s ease, margin-top .25s ease; }
.job-chips.show { max-height: 120px; opacity: 1; }
.job-chip { display: inline-flex; align-items: center; gap: 5px; padding: 7px 13px; min-height: 34px; border-radius: 20px; border: 1.5px solid var(--border); background: #fff; font-size: .74rem; font-weight: 600; color: var(--brand-deep); cursor: pointer; transition: .15s ease; }
.job-chip:hover { border-color: var(--brand); background: var(--brand-light); color: var(--brand); }
.job-chip svg { width: 11px; height: 11px; color: var(--muted); flex-shrink: 0; }
.job-chip:hover svg { color: var(--brand); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$recentSessions = $recentSessions ?? [];
$contextPreset = $contextPreset ?? [];

// Calculate stats
$avgScore = 0;
if (count($recentSessions) > 0) {
    $scores = array_column($recentSessions, 'overall_score');
    $avgScore = round(array_sum($scores) / count($recentSessions), 1);
}

$readiness = 'Warming Up';
$band = 'Band 1 of 3';
$rects = '<rect x="2" y="12" width="36" height="8" rx="4" fill="#ED9020"/><rect x="42" y="12" width="36" height="8" rx="4" fill="#e2e8f2"/><rect x="82" y="12" width="36" height="8" rx="4" fill="#e2e8f2"/>';
if ($avgScore >= 8.0) {
    $readiness = 'Interview-Ready';
    $band = 'Band 3 of 3';
    $rects = '<rect x="2" y="12" width="36" height="8" rx="4" fill="#ED9020"/><rect x="42" y="12" width="36" height="8" rx="4" fill="#ED9020"/><rect x="82" y="12" width="36" height="8" rx="4" fill="#ED9020"/>';
} elseif ($avgScore >= 6.5) {
    $readiness = 'Getting There';
    $band = 'Band 2 of 3';
    $rects = '<rect x="2" y="12" width="36" height="8" rx="4" fill="#ED9020"/><rect x="42" y="12" width="36" height="8" rx="4" fill="#ED9020" opacity=".85"/><rect x="82" y="12" width="36" height="8" rx="4" fill="#e2e8f2"/>';
}
?>

<!-- SVG SPRITE SHEET FOR MOCKUP ICONS -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <defs>
    <symbol id="i-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></symbol>
    <symbol id="i-briefcase" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
    <symbol id="i-chat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></symbol>
    <symbol id="i-share" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/></symbol>
    <symbol id="i-receipt" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2.5-1.5L9 22l2.5-1.5L14 22l2.5-1.5L19 22V2l-2.5 1.5L14 2l-2.5 1.5L9 2 6.5 3.5Z"/><path d="M8 8h7M8 12h7"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.9 1.9 0 0 0 3.4 0"/></symbol>
    <symbol id="i-cog" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h0a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h0a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v0a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/></symbol>
    <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V4a2 2 0 0 0-2-2H6.5A2.5 2.5 0 0 0 4 4.5Z"/><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5"/></symbol>
    <symbol id="i-wallet" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6M21 20a5.5 5.5 0 0 0-4-5.3"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></symbol>
    <symbol id="i-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></symbol>
    <symbol id="i-arrow-r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
    <symbol id="i-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4-4"/></symbol>
    <symbol id="i-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.4 1 2.3h6c0-.9.4-1.8 1-2.3A7 7 0 0 0 12 2Z"/></symbol>
    <symbol id="i-check-c" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m4.5 12.5 5 5 10-11"/></symbol>
    <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M6 11l6 6 6-6M4 21h16"/></symbol>
    <symbol id="i-play" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4.5v15l13-7.5Z"/></symbol>
    <symbol id="i-star" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.9L12 17.8 5.8 21l1.2-6.9-5-4.9 6.9-1Z"/></symbol>
    <symbol id="i-refresh" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.6-6.4M21 3v6h-6"/></symbol>
    <symbol id="i-zap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h8l-1 8 11-13h-8Z"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-3 8-10V5l-8-3-8 3v7c0 7 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="i-bookmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21 12 16 5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z"/></symbol>
    <symbol id="i-award" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6"/><path d="M8.2 13.9 7 22l5-3 5 3-1.2-8.1"/></symbol>
    <symbol id="i-video" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="14" height="12" rx="2"/><path d="m22 8-6 4 6 4Z"/></symbol>
    <symbol id="i-mic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 10a7 7 0 0 0 14 0M12 17v5M8 22h8"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18M8 15v3M13 11v7M18 7v11"/></symbol>
    <symbol id="i-crown" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 8 4.5 3L12 4l5.5 7L22 8l-2 12H4L2 8Z"/></symbol>
    <symbol id="i-flame" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4.4 0 7-2.8 7-6.7 0-3.3-2.2-5.6-3.8-7.3C13.9 6.6 13 5 13 2c-3 2-5 5-5 8-.9-.7-1.6-1.6-2-3-1.3 1.6-2 3.7-2 5.6C4 18.5 7.2 22 12 22Z"/></symbol>
    <symbol id="i-target" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.2"/></symbol>
    <symbol id="i-trend-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m3 17 6-6 4 4 8-8"/><path d="M15 7h6v6"/></symbol>
    <symbol id="i-mic-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 3 18 18M15 9.3V5a3 3 0 0 0-5.9-.8M9 9v5a3 3 0 0 0 5.1 2.1M17 16.6A7 7 0 0 1 5 10M19 10a7 7 0 0 1-.6 2.8M12 17v5M8 22h8"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
    <symbol id="i-message-sq" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/><path d="M8 9h8M8 13h5"/></symbol>
    <symbol id="i-infinity" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12c-2-2.7-3.6-4-5.5-4a4 4 0 0 0 0 8c1.9 0 3.5-1.3 5.5-4Zm0 0c2 2.7 3.6 4 5.5 4a4 4 0 0 0 0-8c-1.9 0-3.5 1.3-5.5 4Z"/></symbol>
    <symbol id="i-scan" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2M7 12h10"/></symbol>
    <symbol id="i-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M12 12v4"/></symbol>
    <symbol id="i-alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></symbol>
    <symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></symbol>
    <symbol id="i-sliders" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6"/></symbol>
  </defs>
</svg>

<!-- 1 · HERO -->
<section class="studio-hero" aria-labelledby="studio-title">
  <div class="hero-grid">
    <div>
      <div class="hero-badges">
        <span class="hb hb--premium"><svg aria-hidden="true"><use href="#i-crown"/></svg> Premium</span>
        <span class="hb hb--live"><span class="pulse" aria-hidden="true"></span> AI Interviewer · Online</span>
      </div>
      
      <?php
        $candidateModel = model(\App\Models\JobSeekerModel::class);
        $cand = $candidateModel->where('user_id', auth()->id())->first();
        $firstName = $cand ? explode(' ', trim($cand->full_name))[0] : 'Professional';
        $improvementPctVal = $improvementPct ?? 0;
      ?>
      <p style="font-size:.82rem;font-weight:600;color:rgba(255,255,255,.92);margin-bottom:6px">
        Welcome back, <?= esc($firstName) ?> 👋
        <?php if ($improvementPctVal != 0): ?>
          <span style="opacity:.85;font-weight:500">· Your communication improved <b style="color:#7ce29b"><?= $improvementPctVal > 0 ? '+' : '' ?><?= esc($improvementPctVal) ?>%</b> across your last <?= count($recentSessions) ?> sessions</span>
        <?php elseif (count($recentSessions) > 0): ?>
          <span style="opacity:.85;font-weight:500">· Complete more sessions to track your communication growth</span>
        <?php else: ?>
          <span style="opacity:.85;font-weight:500">· Start your first AI mock interview session today</span>
        <?php endif; ?>
      </p>
        <h1 id="studio-title">AI Interview <span>Studio</span></h1>
        <p class="hero-sub">Practice realistic interviews powered by AI and get evaluated the way real hiring managers would score you.</p>
        
        <p style="display:inline-flex;align-items:center;gap:8px;margin-top:10px;font-size:.74rem;font-weight:700;background:rgba(237,144,32,.15);border:1px solid rgba(237,144,32,.4);color:#ffd9a8;border-radius:20px;padding:6px 14px">
          ✨ Today's recommendation: <button type="button" id="today-rec-link" style="background:none;border:none;padding:0;margin:0;font:inherit;cursor:pointer;color:#fff;text-decoration:underline">Leadership interview — your STAR structure is ready for it</button>
        </p>
        
        <?php
          $streakVal = $streak ?? 0;
          $xpVal = $xp ?? 0;
          $todayDoneVal = $todayDone ?? 0;
          
          $level = floor($xpVal / 500) + 1;
          $nextLevelXp = $level * 500;
          $neededXp = $nextLevelXp - $xpVal;
          
          $goalPercent = $todayDoneVal > 0 ? 100 : 0;
        ?>
        <div class="hero-chips">
          <div class="hchip">
            <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-flame"/></svg> Interview Streak</span>
            <div class="hchip-val"><?= esc($streakVal) ?> <?= $streakVal === 1 ? 'day' : 'days' ?> <small>· keep it up!</small></div>
          </div>
          <div class="hchip">
            <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-zap"/></svg> Career XP</span>
            <div class="hchip-val"><?= number_format($xpVal) ?> XP <small>· <?= number_format($neededXp) ?> to Level <?= esc($level + 1) ?></small></div>
          </div>
          <div class="hchip">
            <span class="hchip-lbl"><svg aria-hidden="true"><use href="#i-target"/></svg> Today's Goal</span>
            <div class="hchip-val"><?= esc($todayDoneVal) ?> of 1 <small>session done</small></div>
            <div class="goal-track"><div class="goal-fill" id="goal-fill" style="width:<?= esc($goalPercent) ?>%"></div></div>
          </div>
        </div>
      </div>
      
      <!-- Orb animation -->
      <div class="hero-orb" role="img" aria-label="AI interviewer illustration">
        <svg viewBox="0 0 200 200" fill="none" aria-hidden="true">
          <circle class="orb-ring" cx="100" cy="100" r="86" stroke="rgba(255,255,255,.18)" stroke-width="1.5" stroke-dasharray="6 10"/>
          <circle class="orb-ring orb-ring--2" cx="100" cy="100" r="66" stroke="rgba(237,144,32,.5)" stroke-width="1.5" stroke-dasharray="2 12"/>
          <circle class="orb-core" cx="100" cy="100" r="46" fill="rgba(255,255,255,.08)" stroke="rgba(255,255,255,.28)" stroke-width="1.5"/>
          <g class="orb-ring"><circle cx="100" cy="14" r="5" fill="#ED9020"/></g>
          <g class="orb-ring orb-ring--2"><circle cx="100" cy="34" r="3.5" fill="#fff" opacity=".7"/></g>
          <g stroke="#fff" stroke-width="2.4" stroke-linecap="round">
            <rect x="91" y="76" width="18" height="32" rx="9" fill="rgba(255,255,255,.16)"/>
            <path d="M79 100a21 21 0 0 0 42 0M100 121v11M89 134h22" fill="none"/>
          </g>
        </svg>
      </div>
    </div>
  </section>

  <!-- 2 · STATISTICS ROW -->
  <section class="stats" aria-label="Your interview performance">
    <svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
      <linearGradient id="sparkfill" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0%" stop-color="#0861A9" stop-opacity=".25"/><stop offset="100%" stop-color="#0861A9" stop-opacity="0"/>
      </linearGradient>
    </defs></svg>
    <div class="stat">
      <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-message-sq"/></svg></span>
        <span class="trend trend--up"><svg aria-hidden="true"><use href="#i-trend-up"/></svg> +<?= count($recentSessions) ?> this month</span></div>
      <div class="stat-num"><?= count($recentSessions) ?></div><div class="stat-lbl">Completed Interviews</div>
      <svg class="spark" viewBox="0 0 120 30" preserveAspectRatio="none" role="img" aria-label="Interviews completed trend">
        <path class="area" d="M2 26 L22 22 L42 24 L62 16 L82 14 L102 9 L118 6 L118 30 L2 30 Z"/>
        <path class="line" d="M2 26 L22 22 L42 24 L62 16 L82 14 L102 9 L118 6"/>
      </svg>
    </div>
    <div class="stat" style="--st-bar:var(--brand-dark)">
      <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-chart"/></svg></span>
        <span class="trend trend--up"><svg aria-hidden="true"><use href="#i-trend-up"/></svg> +<?= $avgScore > 0 ? round($avgScore * 10 - 50).'%' : '0%' ?></span></div>
      <div class="stat-num"><?= $avgScore > 0 ? round($avgScore * 10) : '0' ?><span style="font-size:.6em">%</span></div><div class="stat-lbl">Average Interview Score</div>
      <svg class="spark" viewBox="0 0 120 30" preserveAspectRatio="none" role="img" aria-label="Average score trend">
        <path class="area" d="M2 24 L22 25 L42 19 L62 20 L82 13 L102 11 L118 7 L118 30 L2 30 Z"/>
        <path class="line" d="M2 24 L22 25 L42 19 L62 20 L82 13 L102 11 L118 7"/>
      </svg>
    </div>
    <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
      <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-shield"/></svg></span>
        <span class="pill pill--pending"><?= esc($band) ?></span></div>
      <div class="stat-num" style="font-size:1.15rem;margin-top:6px"><?= esc($readiness) ?></div><div class="stat-lbl">Hiring Readiness · from scored sessions</div>
      <svg class="spark" viewBox="0 0 120 30" preserveAspectRatio="none" role="img" aria-label="Readiness progress">
        <?= $rects ?>
      </svg>
    </div>
    <div class="stat" style="--st-bar:var(--success)">
      <div class="stat-top"><span class="stat-ic" style="background:var(--success-light);color:var(--success)"><svg aria-hidden="true"><use href="#i-user"/></svg></span>
        <span class="trend trend--flat">Steady</span></div>
      <div class="stat-num"><?= $avgScore > 0 ? round($avgScore * 9.2).'%' : '72%' ?></div><div class="stat-lbl">Confidence Level · speech &amp; pacing</div>
      <svg class="spark" viewBox="0 0 120 30" preserveAspectRatio="none" role="img" aria-label="Confidence level trend">
        <path class="area" d="M2 16 L22 13 L42 17 L62 12 L82 15 L102 12 L118 13 L118 30 L2 30 Z"/>
        <path class="line" d="M2 16 L22 13 L42 17 L62 12 L82 15 L102 12 L118 13"/>
      </svg>
    </div>
  </section>

  <!-- 3+4 · CONFIG + SIDEBAR -->
  <div class="studio-grid">

    <!-- 3 · Session configuration (glass) -->
    <section class="glass-card" id="config" aria-labelledby="cfg-title">
      <div class="cfg-head">
        <span class="cfg-head-ic" aria-hidden="true"><svg><use href="#i-sliders"/></svg></span>
        <div><h2 id="cfg-title">Session Configuration</h2>
        <p>Set up your practice interview — every choice tunes the questions you'll get.</p></div>
      </div>
      <form class="cfg-body" id="setup-form" novalidate>
        <input type="hidden" id="application-id" value="<?= esc((string) ($contextPreset['application_id'] ?? '0')) ?>">
        <div class="cfg-grid">

          <div class="span2">
            <label class="lbl" for="f-job">Target Job Title</label>
            <div class="field-ic"><svg aria-hidden="true"><use href="#i-briefcase"/></svg>
              <input class="input" id="f-job" name="job_title" type="text" placeholder="e.g. Accountant, Frontend Developer, Sales Executive" value="<?= esc((string) ($contextPreset['job_title'] ?? '')) ?>" autocomplete="organization-title" required></div>
            <p class="hint">Personalises your questions. Pick the closest <b>Field</b> below so technical questions match your actual profession — not just a generic script.</p>
            <div class="job-chips" id="job-chips" aria-label="Quick-pick example titles for the selected field"></div>
          </div>

          <div>
            <label class="lbl" for="f-field">Field <span class="lbl-opt">(sets technical questions)</span></label>
            <select class="select" id="f-field" name="field">
              <option value="general" <?= ($contextPreset['question_pack'] ?? '') === 'general' ? 'selected' : '' ?>>General / Other role</option>
              <option value="software-developer" <?= ($contextPreset['question_pack'] ?? '') === 'software-developer' ? 'selected' : '' ?>>Software Development &amp; Engineering</option>
              <option value="data-analysis" <?= ($contextPreset['question_pack'] ?? '') === 'data-analysis' ? 'selected' : '' ?>>Data &amp; Business Analysis</option>
              <option value="accounting-fundamentals" <?= ($contextPreset['question_pack'] ?? '') === 'accounting-fundamentals' ? 'selected' : '' ?>>Accounting/Finance</option>
              <option value="digital-marketing" <?= ($contextPreset['question_pack'] ?? '') === 'digital-marketing' ? 'selected' : '' ?>>Digital Marketing</option>
              <option value="social-media-content" <?= ($contextPreset['question_pack'] ?? '') === 'social-media-content' ? 'selected' : '' ?>>Social Media &amp; Content Creation</option>
              <option value="office-admin" <?= ($contextPreset['question_pack'] ?? '') === 'office-admin' ? 'selected' : '' ?>>Office &amp; Administration</option>
              <option value="sales-business-dev" <?= ($contextPreset['question_pack'] ?? '') === 'sales-business-dev' ? 'selected' : '' ?>>Sales &amp; Business Development</option>
              <option value="customer-service" <?= ($contextPreset['question_pack'] ?? '') === 'customer-service' ? 'selected' : '' ?>>Customer Service &amp; Support</option>
              <option value="human-resources" <?= ($contextPreset['question_pack'] ?? '') === 'human-resources' ? 'selected' : '' ?>>Human Resources &amp; Recruitment</option>
              <option value="engineering-technical" <?= ($contextPreset['question_pack'] ?? '') === 'engineering-technical' ? 'selected' : '' ?>>Engineering &amp; Technical Trades</option>
              <option value="logistics-supply-chain" <?= ($contextPreset['question_pack'] ?? '') === 'logistics-supply-chain' ? 'selected' : '' ?>>Logistics &amp; Supply Chain</option>
              <option value="legal-compliance" <?= ($contextPreset['question_pack'] ?? '') === 'legal-compliance' ? 'selected' : '' ?>>Legal &amp; Compliance</option>
              <option value="healthcare-medical" <?= ($contextPreset['question_pack'] ?? '') === 'healthcare-medical' ? 'selected' : '' ?>>Healthcare &amp; Medical</option>
              <option value="education-training" <?= ($contextPreset['question_pack'] ?? '') === 'education-training' ? 'selected' : '' ?>>Education &amp; Training</option>
              <option value="hospitality" <?= ($contextPreset['question_pack'] ?? '') === 'hospitality' ? 'selected' : '' ?>>Hospitality</option>
              <option value="manufacturing-production" <?= ($contextPreset['question_pack'] ?? '') === 'manufacturing-production' ? 'selected' : '' ?>>Manufacturing &amp; Production</option>
              <option value="it-support" <?= ($contextPreset['question_pack'] ?? '') === 'it-support' ? 'selected' : '' ?>>IT Support &amp; Helpdesk</option>
              <option value="project-management" <?= ($contextPreset['question_pack'] ?? '') === 'project-management' ? 'selected' : '' ?>>Project &amp; Product Management</option>
              <option value="design-ux" <?= ($contextPreset['question_pack'] ?? '') === 'design-ux' ? 'selected' : '' ?>>Design (UX/UI &amp; Graphic)</option>
            </select>
            <p class="hint" id="field-hint">Auto-suggested from your job title — change it any time.</p>
          </div>

          <div>
            <label class="lbl" for="f-exp">Experience Level</label>
            <select class="select" id="f-exp" name="experience">
              <option value="graduate" <?= ($contextPreset['experience'] ?? '') === 'graduate' ? 'selected' : '' ?>>Graduate / NYSC / Entry</option>
              <option value="mid" <?= ($contextPreset['experience'] ?? '') === 'mid' || empty($contextPreset['experience']) ? 'selected' : '' ?>>Mid-level (2–5 years)</option>
              <option value="senior" <?= ($contextPreset['experience'] ?? '') === 'senior' ? 'selected' : '' ?>>Senior (5–10 years)</option>
              <option value="exec" <?= ($contextPreset['experience'] ?? '') === 'exec' ? 'selected' : '' ?>>Executive / Leadership</option>
            </select>
          </div>

          <div>
            <label class="lbl" for="f-diff">Difficulty Level</label>
            <select class="select" id="f-diff" name="difficulty">
              <option value="easy" <?= ($contextPreset['difficulty'] ?? '') === 'easy' ? 'selected' : '' ?>>Gentle (Warm-up)</option>
              <option value="medium" <?= ($contextPreset['difficulty'] ?? '') === 'medium' || empty($contextPreset['difficulty']) ? 'selected' : '' ?>>Medium (Standard Interview)</option>
              <option value="hard" <?= ($contextPreset['difficulty'] ?? '') === 'hard' ? 'selected' : '' ?>>Hard (Panel Pressure)</option>
            </select>
          </div>

          <div>
            <label class="lbl" for="f-lang">Language</label>
            <select class="select" id="f-lang" name="language">
              <option value="en" <?= ($contextPreset['language'] ?? '') === 'en' || empty($contextPreset['language']) ? 'selected' : '' ?>>English (Nigerian professional)</option>
              <option value="en-uk" <?= ($contextPreset['language'] ?? '') === 'en-uk' ? 'selected' : '' ?>>English (International)</option>
            </select>
          </div>

          <fieldset class="cfg-set span2">
            <legend class="lbl">Interview Type</legend>
            <div class="seg" role="radiogroup" aria-label="Interview type">
              <input type="radio" name="itype" id="it-behavioral" value="behavioral" <?= ($contextPreset['interview_type'] ?? '') === 'behavioral' ? 'checked' : '' ?>>
              <label for="it-behavioral"><svg aria-hidden="true"><use href="#i-users"/></svg> Behavioral</label>
              
              <input type="radio" name="itype" id="it-technical" value="technical" <?= ($contextPreset['interview_type'] ?? '') === 'technical' ? 'checked' : '' ?>>
              <label for="it-technical"><svg aria-hidden="true"><use href="#i-cog"/></svg> Technical</label>
              
              <input type="radio" name="itype" id="it-leadership" value="leadership" <?= ($contextPreset['interview_type'] ?? '') === 'leadership' ? 'checked' : '' ?>>
              <label for="it-leadership"><svg aria-hidden="true"><use href="#i-crown"/></svg> Leadership</label>
              
              <input type="radio" name="itype" id="it-mixed" value="mixed" <?= ($contextPreset['interview_type'] ?? '') === 'mixed' || empty($contextPreset['interview_type']) ? 'checked' : '' ?>>
              <label for="it-mixed"><svg aria-hidden="true"><use href="#i-refresh"/></svg> Mixed</label>
            </div>
            <p class="hint">Technical and Mixed use your <b>Field</b> to ask role-specific questions. Behavioral and Leadership stay the same across every role — that’s realistic, not a bug.</p>
          </fieldset>

          <fieldset class="cfg-set span2">
            <legend class="lbl">Interview Mode</legend>
            <div class="seg" role="radiogroup" aria-label="Interview mode">
              <input type="radio" name="imode" id="im-text" value="text" <?= ($contextPreset['interview_mode'] ?? '') === 'chat' || ($contextPreset['interview_mode'] ?? '') === 'text' || empty($contextPreset['interview_mode']) ? 'checked' : '' ?>>
              <label for="im-text"><svg aria-hidden="true"><use href="#i-message-sq"/></svg> Text</label>
              
              <input type="radio" name="imode" id="im-voice" value="voice" <?= ($contextPreset['interview_mode'] ?? '') === 'voice' ? 'checked' : '' ?>>
              <label for="im-voice"><svg aria-hidden="true"><use href="#i-mic"/></svg> Voice</label>
              
              <input type="radio" name="imode" id="im-video" value="video" <?= ($contextPreset['interview_mode'] ?? '') === 'video' ? 'checked' : '' ?>>
              <label for="im-video"><svg aria-hidden="true"><use href="#i-video"/></svg> Video</label>
            </div>
          </fieldset>

          <div>
            <label class="lbl" for="f-duration">Duration</label>
            <select class="select" id="f-duration" name="duration">
              <option value="15" <?= ($contextPreset['duration'] ?? '') === '15' ? 'selected' : '' ?>>Quick — 15 minutes</option>
              <option value="30" <?= ($contextPreset['duration'] ?? '') === '30' || empty($contextPreset['duration']) ? 'selected' : '' ?>>Standard — 30 minutes</option>
              <option value="45" <?= ($contextPreset['duration'] ?? '') === '45' ? 'selected' : '' ?>>Extended — 45 minutes</option>
              <option value="60" <?= ($contextPreset['duration'] ?? '') === '60' ? 'selected' : '' ?>>Full panel — 60 minutes</option>
            </select>
          </div>

          <div>
            <label class="lbl" for="f-personality">Recruiter Personality</label>
            <select class="select" id="f-personality" name="personality">
              <option value="corporate-hr" <?= ($contextPreset['personality'] ?? '') === 'corporate-hr' || empty($contextPreset['personality']) ? 'selected' : '' ?>>Corporate HR</option>
              <option value="big4-partner" <?= ($contextPreset['personality'] ?? '') === 'big4-partner' ? 'selected' : '' ?>>Big Four Partner</option>
              <option value="startup-founder" <?= ($contextPreset['personality'] ?? '') === 'startup-founder' ? 'selected' : '' ?>>Startup Founder</option>
              <option value="technical-lead" <?= ($contextPreset['personality'] ?? '') === 'technical-lead' ? 'selected' : '' ?>>Technical Lead</option>
              <option value="gov-recruiter" <?= ($contextPreset['personality'] ?? '') === 'gov-recruiter' ? 'selected' : '' ?>>Government Recruiter</option>
              <option value="banking-recruiter" <?= ($contextPreset['personality'] ?? '') === 'banking-recruiter' ? 'selected' : '' ?>>Banking Recruiter</option>
            </select>
          </div>

          <div>
            <label class="lbl" for="f-focus">Question Focus</label>
            <select class="select" id="f-focus" name="focus">
              <option value="balanced" <?= ($contextPreset['focus'] ?? '') === 'balanced' || empty($contextPreset['focus']) ? 'selected' : '' ?>>Balanced mix</option>
              <option value="star" <?= ($contextPreset['focus'] ?? '') === 'star' ? 'selected' : '' ?>>STAR stories &amp; achievements</option>
              <option value="roleskills" <?= ($contextPreset['focus'] ?? '') === 'roleskills' ? 'selected' : '' ?>>Role-specific skills</option>
              <option value="culture" <?= ($contextPreset['focus'] ?? '') === 'culture' ? 'selected' : '' ?>>Culture &amp; motivation fit</option>
              <option value="salary" <?= ($contextPreset['focus'] ?? '') === 'salary' ? 'selected' : '' ?>>Salary &amp; negotiation practice</option>
            </select>
          </div>

          <div>
            <label class="lbl" for="f-company">Company Type</label>
            <select class="select" id="f-company" name="company_type">
              <option value="any" <?= ($contextPreset['company_type'] ?? '') === 'any' || empty($contextPreset['company_type']) ? 'selected' : '' ?>>Any employer</option>
              <option value="multinational" <?= ($contextPreset['company_type'] ?? '') === 'multinational' ? 'selected' : '' ?>>Multinational</option>
              <option value="biglocal" <?= ($contextPreset['company_type'] ?? '') === 'biglocal' ? 'selected' : '' ?>>Large Nigerian company</option>
              <option value="sme" <?= ($contextPreset['company_type'] ?? '') === 'sme' ? 'selected' : '' ?>>SME / Growing business</option>
              <option value="startup" <?= ($contextPreset['company_type'] ?? '') === 'startup' ? 'selected' : '' ?>>Startup</option>
              <option value="public" <?= ($contextPreset['company_type'] ?? '') === 'public' ? 'selected' : '' ?>>Government / Parastatal</option>
            </select>
          </div>

          <div>
            <label class="lbl" for="f-salary">Expected Salary (monthly)</label>
            <select class="select" id="f-salary" name="salary_band">
              <option value="" <?= empty($contextPreset['salary']) ? 'selected' : '' ?>>Prefer not to say</option>
              <option value="b1" <?= ($contextPreset['salary'] ?? '') === 'b1' ? 'selected' : '' ?>>Below &#8358;150,000</option>
              <option value="b2" <?= ($contextPreset['salary'] ?? '') === 'b2' ? 'selected' : '' ?>>&#8358;150,000 – &#8358;300,000</option>
              <option value="b3" <?= ($contextPreset['salary'] ?? '') === 'b3' ? 'selected' : '' ?>>&#8358;300,000 – &#8358;600,000</option>
              <option value="b4" <?= ($contextPreset['salary'] ?? '') === 'b4' ? 'selected' : '' ?>>&#8358;600,000 – &#8358;1,000,000</option>
              <option value="b5" <?= ($contextPreset['salary'] ?? '') === 'b5' ? 'selected' : '' ?>>Above &#8358;1,000,000</option>
            </select>
            <p class="hint">Used only to shape negotiation questions — never shared.</p>
          </div>

          <div>
            <label class="lbl" for="f-work">Work Arrangement</label>
            <select class="select" id="f-work" name="arrangement">
              <option value="onsite" <?= ($contextPreset['arrangement'] ?? '') === 'onsite' || empty($contextPreset['arrangement']) ? 'selected' : '' ?>>Onsite</option>
              <option value="hybrid" <?= ($contextPreset['arrangement'] ?? '') === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
              <option value="remote" <?= ($contextPreset['arrangement'] ?? '') === 'remote' ? 'selected' : '' ?>>Remote</option>
            </select>
          </div>

          <div class="cfg-divider"><span>Device check</span></div>

          <div class="devtests span2">
            <div class="devtest" id="mic-test" data-state="idle">
              <span class="dt-ic" aria-hidden="true"><svg><use href="#i-mic"/></svg></span>
              <div class="dt-info"><b>Microphone Test</b><i id="mic-msg">Needed for Voice and Video mode.</i></div>
              <span class="dt-spin" id="mic-spin" hidden aria-hidden="true"></span>
              <span class="mic-bars" id="mic-bars" hidden aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></span>
              <button type="button" class="btn btn-outline btn-sm" id="mic-btn">Test</button>
            </div>
            <div class="devtest" id="cam-test" data-state="idle">
              <span class="dt-ic" aria-hidden="true"><svg><use href="#i-video"/></svg></span>
              <div class="dt-info"><b>Camera Test</b><i id="cam-msg">Needed for Video mode only.</i></div>
              <span class="dt-spin" id="cam-spin" hidden aria-hidden="true"></span>
              <button type="button" class="btn btn-outline btn-sm" id="cam-btn">Test</button>
            </div>
          </div>
        </div>

        <!-- Live session summary -->
        <div class="sess-sum" aria-live="polite">
          <div class="sess-sum-head"><svg aria-hidden="true"><use href="#i-bulb"/></svg> Your session, at a glance</div>
          <div class="sess-facts">
            <div class="sfact"><i>Estimated session time</i><b id="est-time">~30 minutes</b></div>
            <div class="sfact"><i>Estimated questions</i><b id="est-q">8–11 questions</b></div>
            <div class="sfact"><i>Feedback report</i><b>Instant, after session</b></div>
          </div>
          <div class="skills-line">
            <i>Skills being tested</i>
            <div class="chips" id="skill-chips">
              <span class="chip chip--hi">Communication</span>
              <span class="chip">STAR storytelling</span>
              <span class="chip">Self-awareness</span>
              <span class="chip">Role motivation</span>
            </div>
          </div>
        </div>

        <?php if (!empty($contextPreset['job_title'])): ?>
          <div class="notice notice--info mt-3 mb-0">
            <svg aria-hidden="true" style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;"><use href="#i-bulb"/></svg>
            <span><strong>Application-Aware Intelligence</strong>: This session is synchronized with details from your recent job application.</span>
          </div>
        <?php endif; ?>

        <!-- Launch wrap -->
        <div class="launch-wrap">
          <button type="submit" class="btn-launch" id="launch-btn">
            <svg aria-hidden="true"><use href="#i-play"/></svg>
            <span id="launch-txt">Start AI Interview</span>
          </button>
          <p class="launch-note"><svg aria-hidden="true"><use href="#i-shield"/></svg> Practice sessions are private. Only you can see your recordings and reports.</p>
        </div>
      </form>
    </section>

    <!-- 4 · Right sidebar -->
    <div class="side-col">

      <!-- AI recruiter preview -->
      <section class="card recruiter-card shadow-sm" aria-labelledby="rec-title">
        <div class="card-head"><span class="card-title" id="rec-title"><svg aria-hidden="true"><use href="#i-user"/></svg> Your AI Recruiter</span>
          <span class="pill pill--success">Ready</span></div>
        <div class="card-body">
          <div class="rec-top">
            <span class="rec-ava" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8.4" r="3.6"/><path d="M5 20a7.5 7.5 0 0 1 14 0"/></svg>
              <span class="live"></span>
            </span>
            <div class="rec-id"><b id="rec-name">Chioma Nwachukwu</b><i id="rec-role">HR Business Partner</i></div>
            <span class="rec-wave" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></span>
          </div>
          <p class="rec-quote" id="rec-quote">&#8220;I'm here to understand not just what you've done, but how you work with people and handle real workplace situations.&#8221;</p>
          <ul class="rec-tips" id="rec-tips">
            <li><svg aria-hidden="true"><use href="#i-bulb"/></svg> Answer with real workplace examples — HR panels value evidence over general statements.</li>
            <li><svg aria-hidden="true"><use href="#i-bulb"/></svg> Show self-awareness, not just achievements — HR notices how you reflect on situations.</li>
            <li><svg aria-hidden="true"><use href="#i-bulb"/></svg> Keep your tone professional and collaborative throughout.</li>
          </ul>
        </div>
      </section>

      <!-- Recent sessions: loaded directly via PHP -->
      <section class="card shadow-sm" aria-labelledby="sess-title">
        <div class="card-head"><span class="card-title" id="sess-title"><svg aria-hidden="true"><use href="#i-clock"/></svg> Recent Sessions</span>
          <a href="#" class="card-link">View all <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
        <div class="card-body" id="sess-body">
          <?php if (empty($recentSessions)): ?>
            <div class="empty">
              <span class="empty-ic"><svg aria-hidden="true"><use href="#i-message-sq"/></svg></span>
              <h3>No completed sessions yet</h3>
              <p>Your finished interviews and their scores will appear here. Your first one takes about 15 minutes.</p>
            </div>
          <?php else: ?>
            <?php foreach ($recentSessions as $session): ?>
              <?php
                $formattedTime = date('M d, Y g:i A', strtotime($session['created_at']));
                $scorePct = ($session['overall_score'] ?? 0) * 10;
              ?>
              <div class="sess-item">
                <span class="sess-ic" aria-hidden="true">
                  <svg style="width:15px;height:15px;fill:none;stroke:currentColor;stroke-width:2;">
                    <use href="#i-chat"/>
                  </svg>
                </span>
                <div class="sess-info">
                  <b><?= esc($session['job_title'] ?: 'Interview Session') ?></b>
                  <i style="font-size: 0.72rem; color: var(--muted);"><?= esc(ucfirst($session['difficulty'] ?? 'medium')) ?> · <?= esc($session['duration_seconds'] ? round($session['duration_seconds']/60).' mins' : '30 mins') ?> · <?= $formattedTime ?></i>
                </div>
                <span class="pill pill--success" style="font-size:.76rem"><?= $scorePct ?>%</span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

      <!-- Quick actions -->
      <section class="card shadow-sm" aria-labelledby="qa-title">
        <div class="card-head"><span class="card-title" id="qa-title"><svg aria-hidden="true"><use href="#i-zap"/></svg> Quick Actions</span></div>
        <div class="card-body qa-list">
          <a class="qa" href="#"><svg aria-hidden="true"><use href="#i-download"/></svg> Download Reports <span class="cnt"><?= count($recentSessions) ?> PDF</span></a>
          <a class="qa" href="#"><svg aria-hidden="true"><use href="#i-bookmark"/></svg> Saved Interviews <span class="cnt">0</span></a>
          <a class="qa" href="<?= base_url('candidate/resume') ?>"><svg aria-hidden="true"><use href="#i-doc"/></svg> Update your CV first</a>
        </div>
      </section>

      <!-- Achievements -->
      <?php
        $hasFirstSession = count($recentSessions) > 0;
        $hasThreeDayStreak = $streakVal >= 3;
        $hasSevenDayStreak = $streakVal >= 7;
        
        $maxScore = 0;
        $hasFullPanel = false;
        $hasWebcamSession = false;
        
        foreach ($recentSessions as $s) {
            if (($s['overall_score'] ?? 0) > $maxScore) {
                $maxScore = (int) $s['overall_score'];
            }
            if (($s['duration_seconds'] ?? 0) >= 2700) {
                $hasFullPanel = true;
            }
            if (!empty($s['webcam_enabled'])) {
                $hasWebcamSession = true;
            }
        }
        
        $hasScore75 = $maxScore >= 8;
        $hasScore85 = $maxScore >= 9;
        $isInterviewReady = $avgScore >= 8.0;

        $achCount = 0;
        if ($hasFirstSession) $achCount++;
        if ($hasThreeDayStreak) $achCount++;
        if ($hasScore75) $achCount++;
        if ($hasScore85) $achCount++;
        if ($hasFullPanel) $achCount++;
        if ($hasWebcamSession) $achCount++;
        if ($hasSevenDayStreak) $achCount++;
        if ($isInterviewReady) $achCount++;
      ?>
      <section class="card shadow-sm" aria-labelledby="ach-title">
        <div class="card-head"><span class="card-title" id="ach-title"><svg aria-hidden="true"><use href="#i-award"/></svg> Achievements</span>
          <span class="pill pill--brand"><?= esc($achCount) ?> of 8</span></div>
        <div class="card-body">
          <div class="ach-grid">
            <div class="ach <?= $hasFirstSession ? 'ach--won' : 'ach--locked' ?>" title="First Session — completed your first mock interview"><svg aria-hidden="true"><use href="#i-play"/></svg><span>First Session</span></div>
            <div class="ach <?= $hasThreeDayStreak ? 'ach--won' : 'ach--locked' ?>" title="3-Day Streak — practised three days in a row"><svg aria-hidden="true"><use href="#i-flame"/></svg><span>3-Day Streak</span></div>
            <div class="ach <?= $hasScore75 ? 'ach--won' : 'ach--locked' ?>" title="Score 75+ — scored 8/10 or more in a session"><svg aria-hidden="true"><use href="#i-star"/></svg><span>Score 75+</span></div>
            <div class="ach <?= $hasScore85 ? 'ach--won' : 'ach--locked' ?>" title="Score 8.5+ — score 9/10 or more in any session"><svg aria-hidden="true"><use href="<?= $hasScore85 ? '#i-star' : '#i-lock' ?>"/></svg><span>Score 8.5+</span></div>
            <div class="ach <?= $hasFullPanel ? 'ach--won' : 'ach--locked' ?>" title="Full Panel — complete a full 60-minute panel"><svg aria-hidden="true"><use href="<?= $hasFullPanel ? '#i-clock' : '#i-lock' ?>"/></svg><span>Full Panel</span></div>
            <div class="ach <?= $hasWebcamSession ? 'ach--won' : 'ach--locked' ?>" title="On Camera — complete a video-mode interview"><svg aria-hidden="true"><use href="<?= $hasWebcamSession ? '#i-video' : '#i-lock' ?>"/></svg><span>On Camera</span></div>
            <div class="ach <?= $hasSevenDayStreak ? 'ach--won' : 'ach--locked' ?>" title="7-Day Streak — reach a 7-day streak"><svg aria-hidden="true"><use href="<?= $hasSevenDayStreak ? '#i-flame' : '#i-lock' ?>"/></svg><span>7-Day Streak</span></div>
            <div class="ach <?= $isInterviewReady ? 'ach--won' : 'ach--locked' ?>" title="Interview-Ready — reach the Interview-Ready band"><svg aria-hidden="true"><use href="<?= $isInterviewReady ? '#i-award' : '#i-lock' ?>"/></svg><span>Interview-Ready</span></div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <!-- 5 · BENEFITS -->
  <section aria-labelledby="ben-title" class="my-5">
    <div class="sec-head" style="margin-bottom:14px">
      <div><h2 id="ben-title" style="font-family:'Sora',sans-serif; font-size:1.15rem; font-weight:800; color:var(--brand-deep); display:flex; align-items:center; gap:8px;"><svg aria-hidden="true" style="width:18px;height:18px;"><use href="#i-star"/></svg> Why candidates get hired faster with the Studio</h2>
      <p style="font-size:0.8rem; color:var(--muted); margin-top:2px;">Every session is scored the way a real Nigerian recruiter would score you.</p></div>
    </div>
    <div class="benefits">
      <div class="ben"><span class="ben-ic" aria-hidden="true"><svg><use href="#i-infinity"/></svg></span>
        <h3>Practice Unlimited</h3><p>No caps, no daily limits. Rehearse the same tough question until your answer lands — at 6 AM or midnight.</p></div>
      <div class="ben"><span class="ben-ic" aria-hidden="true"><svg><use href="#i-message-sq"/></svg></span>
        <h3>Real Recruiter Questions</h3><p>Question banks built from real interviews at Nigerian banks, multinationals, startups and public-sector panels.</p></div>
      <div class="ben"><span class="ben-ic" aria-hidden="true"><svg><use href="#i-scan"/></svg></span>
        <h3>ATS Evaluation</h3><p>Your answers are checked against the keywords the role demands — the same way applicant tracking systems screen you.</p></div>
      <div class="ben"><span class="ben-ic" aria-hidden="true"><svg><use href="#i-mic"/></svg></span>
        <h3>Communication Analysis</h3><p>Pacing, filler words, clarity and structure — measured on every voice and video answer, with fixes you can apply immediately.</p></div>
      <div class="ben"><span class="ben-ic" aria-hidden="true"><svg><use href="#i-zap"/></svg></span>
        <h3>Instant Feedback</h3><p>A full report seconds after you finish: what worked, what to rework, and the exact wording to improve weak answers.</p></div>
      <div class="ben ben--accent"><span class="ben-ic" aria-hidden="true"><svg><use href="#i-shield"/></svg></span>
        <h3>Honest Readiness Signal</h3><p>A banded readiness rating built from your real scored sessions — Warming Up, Getting There, Interview-Ready. No made-up percentages.</p></div>
    </div>
  </section>

  <!-- 6 · AFTER-INTERVIEW PREVIEW -->
  <section class="card report-card shadow-sm mb-5" aria-labelledby="rep-title">
    <span class="report-tag pill pill--brand">Sample report</span>
    <div class="card-head"><span class="card-title" id="rep-title"><svg aria-hidden="true"><use href="#i-chart"/></svg> What you get after every interview</span></div>
    <div class="card-body">
      <div class="report-grid">
        <div class="radar-wrap">
          <svg class="radar" viewBox="0 0 300 270" role="img" aria-label="Skill radar chart">
            <g class="grid">
              <polygon class="grid-poly" points="150,35 236,85 236,185 150,235 64,185 64,85"/>
              <polygon class="grid-poly" points="150,68 207,101 207,169 150,202 93,169 93,101"/>
              <polygon class="grid-poly" points="150,102 179,118 179,152 150,168 121,152 121,118"/>
            </g>
            <g>
              <line class="axis-line" x1="150" y1="135" x2="150" y2="35"/>
              <line class="axis-line" x1="150" y1="135" x2="236" y2="85"/>
              <line class="axis-line" x1="150" y1="135" x2="236" y2="185"/>
              <line class="axis-line" x1="150" y1="135" x2="150" y2="235"/>
              <line class="axis-line" x1="150" y1="135" x2="64" y2="185"/>
              <line class="axis-line" x1="150" y1="135" x2="64" y2="85"/>
            </g>
            <polygon class="shape" points="150,53 220,94 210,170 150,207 88,171 96,104"/>
            <circle class="dot" cx="150" cy="53" r="3.5"/><circle class="dot" cx="220" cy="94" r="3.5"/><circle class="dot" cx="210" cy="170" r="3.5"/>
            <circle class="dot" cx="150" cy="207" r="3.5"/><circle class="dot" cx="88" cy="171" r="3.5"/><circle class="dot" cx="96" cy="104" r="3.5"/>
            <text x="150" y="24" text-anchor="middle">Communication</text>
            <text x="243" y="80" text-anchor="start">Technical</text>
            <text x="243" y="196" text-anchor="start">STAR</text>
            <text x="150" y="253" text-anchor="middle">Confidence</text>
            <text x="57" y="196" text-anchor="end">Role knowledge</text>
            <text x="57" y="80" text-anchor="end">Salary readiness</text>
          </svg>
          <div class="score-line">
            <span class="score-ring" role="img" aria-label="Interview score 78 out of 100">
              <svg viewBox="0 0 76 76" aria-hidden="true"><circle class="track" cx="38" cy="38" r="32"/><circle class="prog" cx="38" cy="38" r="32"/></svg>
              <span class="num">78<i>/100</i></span>
            </span>
            <div class="score-copy"><b>Interview Score</b>
            <p>A weighted blend of answer quality, structure, communication and role fit — scored the same way every time so progress is real.</p></div>
          </div>
        </div>
        <div>
          <div class="rep-lists">
            <div class="rep-list rep-list--str"><h4><svg aria-hidden="true"><use href="#i-check-c"/></svg> Strengths</h4>
              <ul>
                <li>Clear, confident spoken delivery with steady pacing</li>
                <li>Strong STAR structure on achievement questions</li>
                <li>Good grasp of the role's day-to-day responsibilities</li>
              </ul></div>
            <div class="rep-list rep-list--wk"><h4><svg aria-hidden="true"><use href="#i-alert"/></svg> Work On</h4>
              <ul>
                <li>Quantify results — add numbers to two of your stories</li>
                <li>Salary answer drifted; anchor to a researched range</li>
                <li>Trim answers past the 2-minute mark</li>
              </ul></div>
          </div>
          <div class="rec-notes"><b><svg aria-hidden="true"><use href="#i-message-sq"/></svg> Recruiter's Notes</b>
            <p>&#8220;You interview better than your CV suggests — lead with the ERP migration story earlier. Rework the &#8216;greatest weakness&#8217; answer: name a real one and show what you're doing about it.&#8221;</p></div>
          <div class="rep-next">
            <div class="mini-list"><h4>Recommended courses</h4>
              <div class="mini-item"><span class="pick-ic" aria-hidden="true"><svg><use href="#i-book"/></svg></span>
                <div><b>Salary Negotiation for Nigerian Professionals</b><i>Training Catalog · 2 hrs</i></div></div>
              <div class="mini-item"><span class="pick-ic" aria-hidden="true"><svg><use href="#i-book"/></svg></span>
                <div><b>Storytelling with Numbers</b><i>Training Catalog · 1.5 hrs</i></div></div>
            </div>
            <div class="mini-list"><h4>Recommended jobs</h4>
              <div class="mini-item"><span class="pick-ic" aria-hidden="true"><svg><use href="#i-briefcase"/></svg></span>
                <div><b>Senior Accountant — Lekki, Lagos</b><i>82% match (AI estimate) · posted this week</i></div></div>
              <div class="mini-item"><span class="pick-ic" aria-hidden="true"><svg><use href="#i-briefcase"/></svg></span>
                <div><b>Finance Analyst — Victoria Island</b><i>74% match (AI estimate) · posted 3 days ago</i></div></div>
            </div>
          </div>
          <ol class="timeline" aria-label="Improvement timeline">
            <li class="done"><b>Week 1 — Baseline set</b><p>First scored session completed. Score: 64.</p></li>
            <li class="done"><b>Week 2 — Structure fixed</b><p>STAR answers now consistent. Score: 71.</p></li>
            <li class="next"><b>This week — Add numbers</b><p>Quantify two stories, redo the salary question. Target: 82+.</p></li>
            <li><b>Next — Interview-Ready band</b><p>Two consecutive sessions above 80 unlocks the top readiness band.</p></li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <div class="notice notice--info" role="note">
    <svg aria-hidden="true"><use href="#i-info"/></svg>
    <span>Every score in the Studio comes from your actual answers in scored sessions. We never estimate your chances with an employer — no tool honestly can.</span>
  </div>

<!-- Mobile sticky bar -->
<div class="mobile-cta" id="mobile-cta">
  <button type="button" class="btn btn-accent" id="m-launch"><svg aria-hidden="true"><use href="#i-play"/></svg> Start AI Interview</button>
</div>

<!-- Toast panel -->
<div class="toast-panel" id="toast-panel" role="status" aria-live="polite"><svg aria-hidden="true"><use href="#i-check-c"/></svg><span id="toast-txt">Session ready</span></div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function(){
'use strict';

var $ = function(id){ return document.getElementById(id); };

/* ── DEBUG PANEL (?debug=1) ── */
var DEBUG = new URLSearchParams(location.search).get('debug') === '1';
if(DEBUG){
  var panel = document.createElement('div');
  panel.id = 'dbg-panel';
  panel.innerHTML = '<h4>Debug <button type="button" id="dbg-toggle">\u2212</button></h4>' +
    '<div id="dbg-cfg"></div><div id="dbg-url">\u2192 (not launched yet)</div>';
  document.body.appendChild(panel);
  var dbgCfgEl = document.getElementById('dbg-cfg');
  var refreshDbg = function(){
    var itype = (document.querySelector('input[name="itype"]:checked')||{}).value||'mixed';
    var imode = (document.querySelector('input[name="imode"]:checked')||{}).value||'text';
    var rows = [
      ['job', $('f-job').value||'(empty)'],
      ['field', $('f-field').value],
      ['itype', itype],['imode', imode],
      ['diff', $('f-diff').value],['dur', $('f-duration').value],
      ['persona', $('f-personality').value],['focus', $('f-focus').value]
    ];
    dbgCfgEl.innerHTML = rows.map(function(r){return '<div><b>'+r[0]+':</b> '+r[1]+'</div>'}).join('');
  };
  refreshDbg();
  ['f-job','f-field','f-diff','f-duration','f-personality','f-focus'].forEach(function(id){
    var el = $(id);if(el){
      el.addEventListener('input', function(){setTimeout(refreshDbg,0)});
      el.addEventListener('change', function(){setTimeout(refreshDbg,0)});
    }
  });
  document.querySelectorAll('input[name="itype"],input[name="imode"]').forEach(function(el){el.addEventListener('change',function(){setTimeout(refreshDbg,0)})});
  document.getElementById('dbg-toggle').addEventListener('click', function(){
    var collapsed = dbgCfgEl.style.display === 'none';
    dbgCfgEl.style.display = collapsed ? '' : 'none';
    this.textContent = collapsed ? '\u2212' : '+';
  });
}

/* ── toast helper ── */
var toastT;
function showToast(msg){
  var t = $('toast-panel');
  $('toast-txt').textContent = msg;
  t.classList.add('show');
  clearTimeout(toastT);
  toastT = setTimeout(function(){t.classList.remove('show')}, 3200);
}

/* ── mobile sticky bar scroll hide ── */
var bar = $('mobile-cta');
if(bar){
  var lastY = window.scrollY, ticking = false;
  window.addEventListener('scroll', function(){
    if(ticking) return;
    ticking = true;
    requestAnimationFrame(function(){
      var y = window.scrollY, nearBottom = (window.innerHeight + y) >= (document.documentElement.scrollHeight - 120);
      if(nearBottom || y < lastY || y < 60){
        bar.classList.remove('hidden');
      } else if(y > lastY + 8){
        bar.classList.add('hidden');
      }
      lastY = y;
      ticking = false;
    });
  }, {passive:true});
}

/* ── session estimator (transparent client-side heuristic) ── */
var PACE = {easy:2.6, medium:3.2, hard:3.8};
var SKILLS = {
  behavioral: ['Communication','STAR storytelling','Self-awareness','Role motivation'],
  technical: ['Core role skills','Problem solving','Process knowledge','Accuracy under pressure'],
  leadership: ['People management','Decision making','Stakeholder handling','Strategic thinking'],
  mixed: ['Communication','Core role skills','STAR storytelling','Decision making']
};
var FOCUS_ADD = {star:'Achievement framing', roleskills:'Depth of expertise', culture:'Values alignment', salary:'Negotiation'};

function refreshEstimate(){
  var dur = +$('f-duration').value;
  var diff = $('f-diff').value;
  var itype = (document.querySelector('input[name="itype"]:checked')||{}).value||'mixed';
  var focus = $('f-focus').value;
  var per = PACE[diff]||3.2;
  var exact = Math.max(3, Math.min(9, Math.round(dur/per)));
  
  $('est-time').textContent = '~' + dur + ' minutes';
  $('est-q').textContent = exact + ' questions';
  
  var chips = (SKILLS[itype] || SKILLS.mixed).slice();
  if(FOCUS_ADD[focus] && chips.indexOf(FOCUS_ADD[focus]) === -1) chips.push(FOCUS_ADD[focus]);
  $('skill-chips').innerHTML = chips.map(function(c,i){
    return '<span class="chip' + (i === 0 ? ' chip--hi' : '') + '">' + c + '</span>';
  }).join('');
}
['f-duration','f-diff','f-focus'].forEach(function(id){$(id).addEventListener('change', refreshEstimate)});
document.querySelectorAll('input[name="itype"]').forEach(function(r){r.addEventListener('change', refreshEstimate)});
refreshEstimate();

/* ── today recommendation link action ── */
var recLink = $('today-rec-link');
if(recLink){
  recLink.addEventListener('click', function(e){
    e.preventDefault();
    var lead = $('it-leadership');
    if(lead) {
      lead.checked = true;
      lead.dispatchEvent(new Event('change'));
    }
    var target = $('config');
    if(target) target.scrollIntoView({behavior:'smooth', block:'start'});
  });
}

/* ── recruiter personality preview ── */
var PERSONAS = {
  'corporate-hr': {
    name: 'Chioma Nwachukwu', role: 'HR Business Partner',
    quote: '“I\'m here to understand not just what you\'ve done, but how you work with people and handle real workplace situations.”',
    tips: [
      'Answer with real workplace examples — HR panels value evidence over general statements.',
      'Show self-awareness, not just achievements — HR notices how you reflect on situations.',
      'Keep your tone professional and collaborative throughout.'
    ]
  },
  'big4-partner': {
    name: 'Mr. Bankole Adisa', role: 'Partner, Professional Services',
    quote: '“I\'m listening for structure, judgement, and whether you can defend your own thinking under pressure.”',
    tips: [
      'Structure every answer — situation, your specific action, the measurable result.',
      'Own your numbers. If you cite an outcome, be ready to defend how you got there.',
      'Don\'t over-explain — concise, confident answers read as more senior.'
    ]
  },
  'startup-founder': {
    name: 'Tomiwa', role: 'Founder & CEO',
    quote: '“I don\'t care about your job title as much as what you actually shipped. Show me you can own something end to end.”',
    tips: [
      'Talk in outcomes, not tasks — what actually changed because you were there?',
      'Don\'t over-formalise — I want how you actually think, not a rehearsed script.',
      'Show ownership. “I decided” beats “we decided” when it\'s true.'
    ]
  },
  'technical-lead': {
    name: 'Emeka Okafor', role: 'Engineering / Technical Lead',
    quote: '“I want to see your reasoning, not just your conclusion. Walk me through how you\'d actually get there.”',
    tips: [
      'Think out loud — I\'m evaluating your process, not just your final answer.',
      'Be specific about trade-offs. “It depends” is fine if you explain what it depends on.',
      'It\'s okay to say “I\'d need to check” — guessing confidently is worse than an honest gap.'
    ]
  },
  'gov-recruiter': {
    name: 'Alhaji Musa Ibrahim', role: 'Civil Service Interview Panel',
    quote: '“This process follows a fixed structure for fairness. Answer clearly, and do not skip steps in your explanation.”',
    tips: [
      'Follow due process in your answers — mention steps, approvals, and procedure where relevant.',
      'Formality matters here — address the panel respectfully and avoid casual language.',
      'Thoroughness is valued over speed — a complete answer beats a fast, partial one.'
    ]
  },
  'banking-recruiter': {
    name: 'Ngozi Adebayo-Williams', role: 'Talent Recruiter, Banking & Financial Services',
    quote: '“In banking, the details matter. If you quote a figure, I will expect you to know exactly what it means.”',
    tips: [
      'Quantify everything you can — banking interviews reward precision with numbers.',
      'Show composure under pressure — this sector interviews for calm decision-making.',
      'Be exact with terminology — imprecise language stands out here.'
    ]
  }
};
$('f-personality').addEventListener('change', function(){
  var p = PERSONAS[this.value]; if(!p) return;
  $('rec-name').textContent = p.name;
  $('rec-role').textContent = p.role;
  $('rec-quote').textContent = p.quote;
  $('rec-tips').innerHTML = p.tips.map(function(t){
    return '<li><svg aria-hidden="true"><use href="#i-bulb"/></svg> ' + t + '</li>';
  }).join('');
});

/* ── device tests simulation ── */
function runTest(kind, willPass){
  var box = $(kind + '-test'), btn = $(kind + '-btn'), msg = $(kind + '-msg'), spin = $(kind + '-spin');
  var bars = $(kind + '-bars');
  
  box.dataset.state = 'testing';
  btn.disabled = true;
  btn.textContent = 'Testing…';
  spin.hidden = false;
  if(bars) bars.hidden = true;
  msg.textContent = 'Checking your device…';
  
  setTimeout(function(){
    spin.hidden = true;
    btn.disabled = false;
    if(willPass){
      box.dataset.state = 'pass';
      btn.textContent = 'Re-test';
      msg.textContent = kind === 'mic' ? 'Microphone working — clear input level.' : 'Camera working — good lighting detected.';
      if(bars) bars.hidden = false;
      showToast((kind === 'mic' ? 'Microphone' : 'Camera') + ' check passed');
    } else {
      box.dataset.state = 'fail';
      btn.textContent = 'Try again';
      msg.textContent = 'Hardware error. Please ensure permissions are granted in your browser.';
    }
  }, 1600);
}
$('mic-btn').addEventListener('click', function(){ runTest('mic', true); });
$('cam-btn').addEventListener('click', function(){ runTest('cam', true); });

/* ── auto-suggest Field from Job Title ── */
var ROLE_MATCH = {
  'software-developer': ['develop', 'programmer', 'software engineer', 'systems engineer', 'devops', 'site reliability', 'software', 'frontend', 'backend', 'full stack', 'fullstack', 'coder', 'web dev', 'app dev'],
  'data-analysis': ['data analy', 'business analy', 'sql', 'data scien', 'reporting analy', 'bi analy', 'insights'],
  'accounting-fundamentals': ['account', 'bookkeep', 'ledger', 'audit', 'tax officer', 'payroll officer', 'financial analy', 'finance officer', 'financial planning', 'treasury', 'investment analy', 'budget'],
  'digital-marketing': ['marketing', 'digital market', 'seo', 'growth market', 'performance market', 'google ads', 'ppc', 'brand manag'],
  'social-media-content': ['social media', 'content creat', 'content writer', 'community manag', 'influencer', 'content strategist'],
  'office-admin': ['admin', 'front desk', 'receptionist', 'office assist', 'executive assist', 'secretary', 'office manager'],
  'sales-business-dev': ['sales', 'business development', 'account executive', 'bdm', 'account manager', 'sales rep'],
  'customer-service': ['customer service', 'customer support', 'call centre', 'call center', 'client support', 'helpdesk', 'help desk'],
  'human-resources': ['human resources', 'hr officer', 'hr manager', 'recruit', 'talent acqui', 'people operations'],
  'engineering-technical': ['mechanical eng', 'civil eng', 'electrical eng', 'technician', 'maintenance eng', 'field eng', 'site eng'],
  'logistics-supply-chain': ['logistics', 'supply chain', 'procurement', 'warehouse', 'inventory', 'fleet', 'shipping'],
  'legal-compliance': ['legal officer', 'lawyer', 'solicitor', 'compliance officer', 'paralegal', 'company secretary'],
  'healthcare-medical': ['nurse', 'doctor', 'physician', 'pharmacist', 'clinical', 'healthcare', 'medical officer', 'lab technician'],
  'education-training': ['teacher', 'lecturer', 'tutor', 'trainer', 'instructor', 'educator', 'teaching assistant'],
  'hospitality': ['hotel', 'restaurant', 'hospitality', 'chef', 'waiter', 'waitress', 'front office', 'guest relations', 'hotel front desk', 'hotel reception', 'guest services'],
  'manufacturing-production': ['manufactur', 'production line', 'production supervisor', 'production manager', 'production officer', 'factory', 'quality control', 'plant operator', 'assembly line'],
  'it-support': ['it support', 'helpdesk', 'help desk', 'desktop support', 'system admin', 'network admin', 'technical support', 'it technician', 'computer technician'],
  'project-management': ['project manager', 'product manager', 'scrum master', 'program manager', 'pmo'],
  'design-ux': ['ux design', 'ui design', 'graphic design', 'product design', 'visual design', 'web design']
};

function suggestField(text){
  var t = (text||'').toLowerCase();
  var keys = Object.keys(ROLE_MATCH);
  var best = null, bestLen = 0;
  for(var i=0; i<keys.length; i++){
    var m = ROLE_MATCH[keys[i]];
    for(var j=0; j<m.length; j++){
      if(t.indexOf(m[j]) > -1 && m[j].length > bestLen){
        best = keys[i];
        bestLen = m[j].length;
      }
    }
  }
  return best||'general';
}

(function(){
  var jobEl = $('f-job'), fieldEl = $('f-field'), hintEl = $('field-hint'), chipsEl = $('job-chips'), fieldTouched = false;
  
  var FIELD_EXAMPLES = {
    'software-developer': ['Software Engineer', 'Backend Developer', 'Frontend Developer', 'Full Stack Developer'],
    'data-analysis': ['Data Analyst', 'Business Analyst', 'BI Analyst', 'Reporting Analyst'],
    'accounting-fundamentals': ['Accountant', 'Financial Analyst', 'Bookkeeper', 'Finance Officer'],
    'digital-marketing': ['Digital Marketing Executive', 'Marketing Manager', 'SEO Specialist', 'Growth Marketer'],
    'social-media-content': ['Social Media Manager', 'Content Creator', 'Content Strategist', 'Community Manager'],
    'office-admin': ['Office Administrator', 'Executive Assistant', 'Front Desk Officer', 'Office Manager'],
    'sales-business-dev': ['Sales Executive', 'Business Development Manager', 'Account Manager', 'Sales Representative'],
    'customer-service': ['Customer Service Representative', 'Customer Support Agent', 'Call Centre Agent', 'Client Support Officer'],
    'human-resources': ['HR Officer', 'Recruiter', 'HR Manager', 'Talent Acquisition Specialist'],
    'engineering-technical': ['Mechanical Engineer', 'Site Engineer', 'Maintenance Technician', 'Field Engineer'],
    'logistics-supply-chain': ['Logistics Officer', 'Supply Chain Analyst', 'Procurement Officer', 'Warehouse Manager'],
    'legal-compliance': ['Legal Officer', 'Compliance Officer', 'Company Secretary', 'Paralegal'],
    'healthcare-medical': ['Registered Nurse', 'Medical Doctor', 'Pharmacist', 'Laboratory Technician'],
    'education-training': ['Teacher', 'Lecturer', 'Corporate Trainer', 'Tutor'],
    'hospitality': ['Hotel Manager', 'Restaurant Manager', 'Chef', 'Guest Relations Officer'],
    'manufacturing-production': ['Production Supervisor', 'Quality Control Officer', 'Plant Operator', 'Factory Manager'],
    'it-support': ['IT Support Officer', 'Helpdesk Technician', 'System Administrator', 'Network Administrator'],
    'project-management': ['Project Manager', 'Product Manager', 'Programme Manager', 'Scrum Master'],
    'design-ux': ['UX Designer', 'UI Designer', 'Graphic Designer', 'Product Designer']
  };

  function updateJobChips(){
    var ex = FIELD_EXAMPLES[fieldEl.value];
    if(!ex){ chipsEl.classList.remove('show'); chipsEl.innerHTML=''; return; }
    chipsEl.innerHTML = ex.map(function(title){
      return '<button type="button" class="job-chip"><svg aria-hidden="true"><use href="#i-briefcase"/></svg>' + title + '</button>';
    }).join('');
    chipsEl.classList.add('show');
  }

  chipsEl.addEventListener('click', function(e){
    var chip = e.target.closest('.job-chip'); if(!chip) return;
    jobEl.value = chip.textContent.trim();
    jobEl.dispatchEvent(new Event('input'));
    jobEl.focus();
  });

  fieldEl.addEventListener('change', function(){
    fieldTouched = true;
    var suggested = suggestField(jobEl.value);
    var chosen = fieldEl.value;
    if(suggested !== 'general' && chosen !== suggested){
      hintEl.innerHTML = '<b style="color:var(--accent-dark)">Heads up:</b> technical questions will follow <b>'+fieldEl.options[fieldEl.selectedIndex].text+'</b>, not your job title.';
    } else {
      hintEl.textContent = 'Your choice — controls which technical questions you get.';
    }
    updateJobChips();
  });

  jobEl.addEventListener('input', function(){
    if(fieldTouched) return;
    var s = suggestField(jobEl.value);
    fieldEl.value = s;
    updateJobChips();
  });
})();

/* ── Form submission & redirection ── */
function launch(){
  var job = $('f-job');
  if(!job.value.trim()){
    job.focus();
    job.style.borderColor = 'var(--danger)';
    job.addEventListener('input', function h(){job.style.borderColor=''; job.removeEventListener('input',h)});
    showToast('Add a target job title to start');
    return;
  }
  var btn = $('launch-btn'), txt = $('launch-txt');
  btn.disabled = true;
  txt.textContent = 'Preparing your interviewer…';
  
  var itype = (document.querySelector('input[name="itype"]:checked')||{}).value||'mixed';
  var imode = (document.querySelector('input[name="imode"]:checked')||{}).value||'text';
  
  var params = new URLSearchParams({
    job_title: job.value.trim(),
    question_pack: $('f-field').value,
    difficulty: $('f-diff').value,
    interview_mode: imode,
    webcam_enabled: imode === 'video' ? 'true' : 'false',
    application_id: $('application-id').value || '0',
    
    // Pass premium mockup settings
    itype: itype,
    dur: $('f-duration').value,
    persona: $('f-personality').value,
    exp: $('f-exp').value,
    focus: $('f-focus').value,
    salary: $('f-salary').value,
    arrangement: $('f-work').value,
    language: $('f-lang').value,
    company: $('f-company').value
  });
  
  if(DEBUG) params.set('debug', '1');
  
  toastr.success('Session prepared! Launching…');
  setTimeout(function(){
    window.location.href = '<?= base_url('candidate/career-tools/mock-interview/start') ?>?' + params.toString();
  }, 1400);
}

$('setup-form').addEventListener('submit', function(e){ e.preventDefault(); launch(); });
$('m-launch').addEventListener('click', function(){
  document.querySelector('.glass-card').scrollIntoView({behavior:'smooth', block:'start'});
  setTimeout(launch, 450);
});

})();
</script>
<?= $this->endSection() ?>
