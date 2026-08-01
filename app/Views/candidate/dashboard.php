<?php $page_title = 'Overview'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/candidate-profile.css') ?>">
<style>
/* Dashboard-specific styles (aligned to candidate-dashboard.html mockup) */
.greet-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  margin-bottom: 24px;
}
.greet h1 {
  font-family: 'Sora', sans-serif;
  font-weight: 800;
  font-size: clamp(1.4rem, 2.8vw, 1.8rem);
  color: var(--brand-deep);
  margin-bottom: 6px;
  line-height: 1.15;
}
.greet p {
  font-size: .9rem;
  color: var(--muted);
  line-height: 1.6;
  max-width: 580px;
}
.greet-date {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: .8rem;
  font-weight: 600;
  color: var(--muted);
  background: #fff;
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 8px 14px;
  flex-shrink: 0;
  min-height: 44px;
}
.greet-date svg { width: 15px; height: 15px; }

/* AI Hero */
.ai-hero {
  position: relative;
  overflow: hidden;
  border-radius: var(--radius-lg);
  color: #fff;
  padding: clamp(22px, 3.2vw, 34px);
  background: radial-gradient(ellipse 60% 90% at 88% 8%, rgba(237,144,32,.22) 0%, transparent 55%), linear-gradient(150deg, #0A2F57 0%, #064A85 55%, #0861A9 100%);
  box-shadow: var(--shadow);
  margin-bottom: 24px;
}
.ai-hero::before {
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
.ai-hero-grid {
  position: relative;
  display: grid;
  grid-template-columns: 1fr auto;
  gap: clamp(20px, 3vw, 40px);
  align-items: center;
}
@media (max-width: 860px) {
  .ai-hero-grid { grid-template-columns: 1fr; }
}
.ai-hero h2 {
  font-size: clamp(1.4rem, 2.8vw, 1.9rem);
  font-weight: 800;
  line-height: 1.15;
  margin-bottom: 9px;
  color: #fff;
}
.ai-hero h2 span { color: var(--accent); }
.ai-sub {
  font-size: .9rem;
  color: rgba(255,255,255,.85);
  max-width: 520px;
  line-height: 1.65;
}
.ai-badge {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: 5px 13px;
  border-radius: 20px;
  background: rgba(237,144,32,.18);
  border: 1px solid rgba(237,144,32,.45);
  color: #FDD9A8;
  margin-bottom: 14px;
}
.ai-badge .pulse {
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
.ai-actions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}
@media (max-width: 860px) {
  .ai-actions { width: 100%; }
  .ai-actions .btn { flex: 1; min-width: 140px; }
}

/* Stats row */
.stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: clamp(10px, 1.4vw, 16px);
  margin-bottom: 24px;
}
@media (max-width: 1100px) { .stats { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 520px) { .stats { grid-template-columns: 1fr; } }
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
.stat-num {
  font-family: 'Sora', sans-serif;
  font-weight: 800;
  font-size: clamp(1.35rem, 2.4vw, 1.75rem);
  color: var(--brand-deep);
  line-height: 1.1;
}
.stat-lbl { font-size: .74rem; color: var(--muted); font-weight: 500; margin-top: 1px; }

/* Mid section (weekly chart + skill match) */
.mid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: clamp(14px, 1.8vw, 20px);
  margin-bottom: 24px;
}
@media (max-width: 960px) { .mid { grid-template-columns: 1fr; } }
.card {
  background: #fff;
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
}
.card-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  padding: 16px 20px;
  border-bottom: 1px solid var(--border);
  flex-wrap: wrap;
}
.card-title {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-family: 'Sora', sans-serif;
  font-weight: 700;
  font-size: .94rem;
  color: var(--brand-deep);
}
.card-title svg { width: 16px; height: 16px; color: var(--brand); }
.card-link {
  font-size: .76rem;
  font-weight: 600;
  color: var(--brand);
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 8px 10px;
  margin: -8px -10px;
  border-radius: 8px;
}
.card-link:hover { background: var(--brand-light); text-decoration: none; }
.card-link svg { width: 13px; height: 13px; }
.card-body { padding: 18px 20px; }

/* Bar chart */
.bar-chart { width: 100%; height: auto; display: block; }
.grid-line { stroke: var(--border); stroke-width: 1; }
.axis-lbl { font-family: 'Inter', sans-serif; font-size: 10px; font-weight: 600; fill: var(--muted); }
.val-lbl { font-family: 'Sora', sans-serif; font-weight: 700; font-size: 11px; }
.bar-chart rect { transition: opacity .2s ease; }
.bar-chart rect:hover { opacity: .8; }

/* Donut + skill match */
.donut-wrap { display: flex; flex-direction: column; align-items: center; gap: 16px; }
.donut {
  position: relative;
  width: 140px;
  height: 140px;
}
.donut svg { width: 140px; height: 140px; transform: rotate(-90deg); }
.donut circle.track { fill: none; stroke: var(--border); stroke-width: 12; }
.donut circle.prog { fill: none; stroke-width: 12; stroke-linecap: round; stroke-dasharray: 320; }
.donut .c {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.donut .c b { font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1.6rem; color: var(--brand-deep); line-height: 1; }
.donut .c i { font-style: normal; font-size: .68rem; font-weight: 600; color: var(--muted); letter-spacing: .04em; }
.leg { list-style: none; display: flex; flex-direction: column; gap: 8px; padding: 0; font-size: .76rem; color: var(--text); }
.leg li { display: flex; align-items: center; gap: 9px; }
.leg li i { width: 10px; height: 10px; border-radius: 2px; flex-shrink: 0; }

.mcat { margin-top: 18px; }
.mcat > div { margin-bottom: 14px; }
.mcat > div:last-child { margin-bottom: 0; }
.mcat-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 5px; }
.mcat-row span { font-size: .78rem; font-weight: 500; color: var(--text); }
.mcat-row b { font-family: 'Sora', sans-serif; font-weight: 700; font-size: .82rem; color: var(--brand-deep); }
.mcat-track { height: 6px; border-radius: 20px; background: var(--bg); overflow: hidden; }
.mcat-fill { height: 100%; border-radius: 20px; background: var(--brand); transition: width .6s cubic-bezier(.4,0,.2,1); }

/* Tri section (profile tasks, picks, learning) */
.tri {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: clamp(14px, 1.8vw, 20px);
  margin-bottom: 24px;
}
@media (max-width: 1000px) { .tri { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .tri { grid-template-columns: 1fr; } }

.task {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 10px;
  font-size: .82rem;
  color: var(--text);
  transition: var(--transition);
  margin-bottom: 8px;
}
.task:last-child { margin-bottom: 0; }
.task svg { width: 16px; height: 16px; flex-shrink: 0; }
.task.done { background: var(--success-light); color: var(--success); }
.task.done svg { color: var(--success); }
.task.todo { background: var(--bg); color: var(--muted); }
.task.todo svg { color: var(--muted); }
.task a { color: var(--brand); font-weight: 600; }
.task a:hover { text-decoration: underline; }

.pick { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border); }
.pick:last-child { border-bottom: none; padding-bottom: 2px; }
.pick:first-child { padding-top: 2px; }
.pick-ic {
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
.pick-ic svg { width: 16px; height: 16px; }
.pick-info { flex: 1; min-width: 0; }
.pick-title { font-size: .82rem; font-weight: 600; color: var(--brand-deep); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px; }
.pick-title a { color: var(--brand-deep); }
.pick-title a:hover { color: var(--brand); }
.pick-sub { font-size: .7rem; color: var(--muted); display: flex; align-items: center; gap: 5px; }
.pick-sub svg { width: 11px; height: 11px; }
.match-badge { font-size: .66rem; font-weight: 700; padding: 3px 9px; border-radius: 20px; background: var(--brand-light); color: var(--brand); border: 1px solid #cfe2f2; white-space: nowrap; }
.match-badge--hot { background: var(--accent-light); color: var(--accent-dark); border-color: #f3d9ae; }

.learn-strip { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px; }
.learn-strip .pick-ic { width: 44px; height: 44px; border-radius: 12px; }
.grow { flex: 1; min-width: 0; }
.grow b { display: block; font-size: .82rem; color: var(--brand-deep); margin-bottom: 2px; }
.grow p { font-size: .76rem; color: var(--muted); margin: 0; }
.btn-outline { background: #fff; color: var(--brand); border-color: var(--border); }
.btn-outline:hover { background: var(--brand); color: #fff; border-color: var(--brand); }
.btn-sm { padding: 8px 14px; font-size: .78rem; min-height: 38px; }
.btn-block { width: 100%; }

/* Recent applications table */
.tbl-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.tbl { width: 100%; border-collapse: collapse; min-width: 640px; }
.tbl th { font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); text-align: left; padding: 11px 14px; background: var(--bg); border-bottom: 1px solid var(--border); white-space: nowrap; }
.tbl td { padding: 13px 14px; border-bottom: 1px solid var(--border); font-size: .84rem; vertical-align: middle; }
.tbl tr:last-child td { border-bottom: none; }
.tbl tr:hover td { background: #fafbfe; }

.pill { display: inline-flex; align-items: center; gap: 5px; font-size: .66rem; font-weight: 700; padding: 4px 11px; border-radius: 20px; letter-spacing: .02em; white-space: nowrap; }
.pill svg { width: 11px; height: 11px; }
.pill--pending { background: var(--accent-light); color: var(--accent-dark); }
.pill--rejected { background: var(--danger-light); color: var(--danger); }
.pill--hired, .pill--accepted, .pill--success { background: var(--success-light); color: var(--success); }

.empty { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px; padding: 34px 18px; }
.empty-ic { width: 56px; height: 56px; border-radius: 16px; background: var(--brand-light); color: var(--brand); display: flex; align-items: center; justify-content: center; margin: 0 auto; }
.empty-ic svg { width: 26px; height: 26px; }
.empty h3 { font-size: .94rem; font-weight: 700; color: var(--brand-deep); }
.empty p { font-size: .8rem; color: var(--muted); max-width: 360px; }

/* Profile ring (reused from original) */
.ring-sm { width: 52px; height: 52px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
.ring-sm svg { width: 52px; height: 52px; }
.ring-sm .track { fill: none; stroke: var(--border); stroke-width: 6; }
.ring-sm .prog { fill: none; stroke: var(--success); stroke-width: 6; stroke-linecap: round; stroke-dasharray: 138; transition: stroke-dashoffset .6s cubic-bezier(.4,0,.2,1); }
.ring-sm .pct { position: absolute; font-family: 'Sora', sans-serif; font-weight: 800; font-size: .9rem; color: var(--brand-deep); line-height: 1; }

/* Tri section (3-column cards: Finish Profile, Picks, Keep Learning) */
.tri {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: clamp(14px, 1.8vw, 20px);
  margin-bottom: 24px;
}
@media (max-width: 1100px) { .tri { grid-template-columns: 1fr; } }

.task {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 9px;
  font-size: .82rem;
  font-weight: 500;
  margin-bottom: 6px;
  transition: var(--transition);
}
.task svg { width: 16px; height: 16px; flex-shrink: 0; }
.task.done { background: var(--success-light); color: var(--success); font-weight: 600; }
.task.done svg { color: var(--success); }
.task.todo { background: var(--bg); color: var(--text); border: 1px dashed var(--border); }
.task.todo svg { color: var(--muted); }
.task.todo a { color: var(--brand); font-weight: 600; text-decoration: underline; }

.pick {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 11px 0;
  border-bottom: 1px solid var(--border);
}
.pick:last-child { border-bottom: none; }
.pick-ic {
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
.pick-ic svg { width: 18px; height: 18px; }
.pick-info { flex: 1; min-width: 0; }
.pick-title { font-size: .84rem; font-weight: 700; line-height: 1.35; }
.pick-title a { color: var(--brand-deep); }
.pick-title a:hover { color: var(--brand); }
.pick-sub { font-size: .72rem; color: var(--muted); margin-top: 2px; display: flex; align-items: center; gap: 4px; }
.pick-sub svg { width: 12px; height: 12px; }

.match-badge {
  display: inline-flex;
  align-items: center;
  font-size: .66rem;
  font-weight: 700;
  padding: 4px 9px;
  border-radius: 20px;
  background: var(--brand-light);
  color: var(--brand);
  white-space: nowrap;
}
.match-badge--hot { background: var(--accent-light); color: var(--accent-dark); }

.learn-strip { display: flex; align-items: center; gap: 12px; }
.grow { flex: 1; min-width: 0; }
.grow b { display: block; font-size: .84rem; color: var(--brand-deep); }
.grow p { font-size: .74rem; color: var(--muted); margin: 1px 0 0; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="content" id="main-content">

  <div class="greet-row">
    <div class="greet">
      <?php
        $hourOfDay = (int) date('G');
        $greeting  = $hourOfDay < 12 ? 'Good morning' : ($hourOfDay < 17 ? 'Good afternoon' : 'Good evening');
        $firstName = explode(' ', trim($candidate->full_name ?? ''))[0] ?? 'Professional';
      ?>
      <h1><?= $greeting ?>, <?= esc($firstName) ?> 👋</h1>
      <p>
        <?php if ($totalApplications > 0): ?>
          You have submitted <strong><?= $totalApplications ?></strong> <?= $totalApplications == 1 ? 'application' : 'applications' ?>
          <?php if (!empty($savedJobs) && $savedJobs > 0): ?> and <strong><?= $savedJobs ?></strong> saved <?= $savedJobs == 1 ? 'job' : 'jobs' ?> waiting for your action.<?php endif; ?>
        <?php else: ?>
          Welcome! Start by browsing jobs and applying — your applications will be tracked here.
        <?php endif; ?>
      </p>
    </div>
    <span class="greet-date"><svg aria-hidden="true"><use href="#i-calendar"/></svg> <?= date('l, d F Y') ?></span>
  </div>

  <!-- AI engine hero -->
  <section class="ai-hero" aria-labelledby="ai-title">
    <div class="ai-hero-grid">
      <div>
        <span class="ai-badge"><span class="pulse" aria-hidden="true"></span> JobberRecruit AI Engine · Active</span>
        <h2 id="ai-title">Let's accelerate your <span>career path.</span></h2>
        <p class="ai-sub">
          <?php
            $pending = array_filter($applications ?? [], fn($a) => strtolower($a->status ?? '') === 'pending');
            $pendingCount = count($pending);
          ?>
          <?php if ($pendingCount > 0): ?>
            You have <strong><?= $pendingCount ?></strong> pending <?= $pendingCount == 1 ? 'application' : 'applications' ?> awaiting employer response<?= !empty($savedJobs) && $savedJobs > 0 ? ' and <strong>' . $savedJobs . '</strong> saved ' . ($savedJobs == 1 ? 'job' : 'jobs') . ' to revisit' : '' ?>.
          <?php else: ?>
            Your AI engine is ready. Browse jobs, apply, and track every employer response in real time.
          <?php endif; ?>
        </p>
      </div>
      <div class="ai-actions">
        <a href="<?= base_url('jobs') ?>" class="btn btn-accent"><svg aria-hidden="true"><use href="#i-search"/></svg> Browse Jobs</a>
        <a href="<?= base_url('candidate/applications') ?>" class="btn btn-ghost-w"><svg aria-hidden="true"><use href="#i-doc"/></svg> My Applications</a>
        <?php if (env('feature_ai_career_tools', 'true') == 'true'): ?>
        <a href="<?= base_url('candidate/career-tools') ?>" class="btn btn-ghost-w"><svg aria-hidden="true"><use href="#i-zap"/></svg> AI Career Tools</a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- KPIs stats grid -->
  <section class="stats" aria-label="Your activity">
    <div class="stat">
      <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></span></div>
      <div class="stat-num"><?= $totalApplications ?></div>
      <div class="stat-lbl">Total Applications</div>
    </div>
    <div class="stat" style="--st-bar:var(--accent);--st-icbg:var(--accent-light);--st-ic:var(--accent-dark)">
      <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-bookmark"/></svg></span></div>
      <div class="stat-num"><?= $savedJobs ?></div>
      <div class="stat-lbl">Saved Jobs</div>
    </div>
    <div class="stat" style="--st-bar:var(--brand-dark)">
      <div class="stat-top"><span class="stat-ic"><svg aria-hidden="true"><use href="#i-eye"/></svg></span></div>
      <div class="stat-num"><?= $jobsViewed ?></div>
      <div class="stat-lbl">Jobs Viewed</div>
    </div>
    <div class="stat" style="--st-bar:var(--success)">
      <div class="stat-top">
        <span class="ring-sm" role="img" aria-label="Profile <?= $profileCompletion ?> percent complete">
          <svg viewBox="0 0 52 52" aria-hidden="true"><circle class="track" cx="26" cy="26" r="22"/><circle class="prog" cx="26" cy="26" r="22" stroke-dashoffset="<?= 138 - (138 * $profileCompletion / 100) ?>"/></svg>
          <span class="pct"><?= $profileCompletion ?>%</span>
        </span>
      </div>
      <div class="stat-num" style="font-size:1.05rem;margin-top:2px">
        <?php if ($profileCompletion == 100): ?>Complete ✓
        <?php else: ?><?= $profileCompletion ?>% done<?php endif; ?>
      </div>
      <div class="stat-lbl">Profile Completion<?= $profileCompletion < 100 ? ' — <a href="' . base_url('candidate/profile') . '" style="font-size:.68rem;">improve</a>' : '' ?></div>
    </div>
  </section>

  <!-- weekly engagement + skill match -->
  <section class="mid" aria-label="Engagement and skill match">
    <div class="card">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-chart"/></svg> Your Week at a Glance</span>
        <span style="font-size:.72rem;color:var(--muted);font-weight:500">Jobs viewed · last 7 days</span></div>
      <div class="card-body">
        <?php
        $weeklyActivity = $weeklyChartData ?? [1, 2, 4, 3, 5, 8, 0];
        $maxVal = max(1, max($weeklyActivity));
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        ?>
        <svg class="bar-chart" viewBox="0 0 560 140" role="img" aria-label="Bar chart of jobs viewed this week.">
          <line class="grid-line" x1="30" y1="8" x2="552" y2="8"/><line class="grid-line" x1="30" y1="56" x2="552" y2="56"/><line class="grid-line" x1="30" y1="104" x2="552" y2="104"/>
          <text class="axis-lbl" x="24" y="12" text-anchor="end"><?= $maxVal ?></text><text class="axis-lbl" x="24" y="60" text-anchor="end"><?= round($maxVal/2) ?></text><text class="axis-lbl" x="24" y="108" text-anchor="end">0</text>
          
          <?php foreach ($weeklyActivity as $i => $val): 
              $h = round($val / $maxVal * 96);
              $y = 104 - $h;
              $x = 44 + ($i * 74);
              $isMax = ($val === max($weeklyActivity) && $val > 0);
          ?>
            <rect x="<?= $x ?>" y="<?= $y ?>" width="34" height="<?= max(4, $h) ?>" rx="5" fill="<?= $isMax ? '#ED9020' : '#0861A9' ?>"><title><?= $days[$i] ?> · <?= $val ?></title></rect>
            <?php if ($isMax): ?>
              <text class="val-lbl" x="<?= $x + 17 ?>" y="<?= $y + 12 ?>" text-anchor="middle" fill="#fff"><?= $val ?></text>
            <?php endif; ?>
          <?php endforeach; ?>
          
          <?php foreach ($days as $i => $day): ?>
            <text class="axis-lbl" x="<?= 61 + ($i * 74) ?>" y="124" text-anchor="middle"><?= $day ?></text>
          <?php endforeach; ?>
        </svg>
        <p style="font-size:.74rem;color:var(--muted);margin-top:10px;display:flex;gap:6px;align-items:center"><svg aria-hidden="true"><use href="#i-bulb"/></svg> The most active candidates are first in line when new jobs drop.</p>
      </div>
    </div>

    <div class="card">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-zap"/></svg> Skill Match Hub</span></div>
      <div class="card-body">
        <div class="donut-wrap">
          <div class="donut" role="img" aria-label="Overall match rate 85 percent">
            <svg viewBox="0 0 140 140" aria-hidden="true">
              <circle class="track" cx="70" cy="70" r="58"/>
              <circle class="prog" cx="70" cy="70" r="58" stroke="#0861A9" stroke-dasharray="128 320" stroke-dashoffset="0" stroke-linecap="butt"/>
              <circle class="prog" cx="70" cy="70" r="58" stroke="#16a34a" stroke-dasharray="64 320" stroke-dashoffset="-128" stroke-linecap="butt"/>
              <circle class="prog" cx="70" cy="70" r="58" stroke="#ED9020" stroke-dasharray="48 320" stroke-dashoffset="-192" stroke-linecap="butt"/>
              <circle class="prog" cx="70" cy="70" r="58" stroke="#8b5cf6" stroke-dasharray="32 320" stroke-dashoffset="-240" stroke-linecap="butt"/>
            </svg>
            <span class="c"><b>85%</b><i>Match rate</i></span>
          </div>
          <ul class="leg">
            <li><i style="background:#0861A9"></i>Tech skills</li>
            <li><i style="background:#16a34a"></i>Soft skills</li>
            <li><i style="background:#ED9020"></i>Domain knowledge</li>
            <li><i style="background:#8b5cf6"></i>Other matches</li>
          </ul>
        </div>
        <div class="mcat" style="margin-top:18px">
          <?php if (!empty($skillCategories)): ?>
              <?php foreach ($skillCategories as $cat): ?>
                  <div>
                    <div class="mcat-row"><span><?= esc($cat->name) ?></span><b><?= $cat->match ?>%</b></div>
                    <div class="mcat-track"><div class="mcat-fill" style="width:<?= $cat->match ?>%;background:#0861A9"></div></div>
                  </div>
              <?php endforeach; ?>
          <?php else: ?>
              <div>
                <div class="mcat-row"><span>Software Development</span><b>85%</b></div>
                <div class="mcat-track"><div class="mcat-fill" style="width:85%;background:#0861A9"></div></div>
              </div>
              <div>
                <div class="mcat-row"><span>UI/UX Product Design</span><b>75%</b></div>
                <div class="mcat-track"><div class="mcat-fill" style="width:75%;background:#064A85"></div></div>
              </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- picks · profile tasks · learning -->
  <section class="tri" aria-label="Recommendations and next steps">
    <div class="card">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Finish Your Profile</span>
        <span class="pill <?= $profileCompletion == 100 ? 'pill--success' : 'pill--pending' ?>"><?= $profileCompletion == 100 ? 'Completed' : 'Incomplete' ?></span></div>
      <div class="card-body">
        <div class="task <?= !empty($candidate->full_name) && !empty($candidate->phone) ? 'done' : 'todo' ?>">
          <svg aria-hidden="true"><use href="<?= !empty($candidate->full_name) && !empty($candidate->phone) ? '#i-check-c' : '#i-circle' ?>"/></svg> Personal details added
        </div>
        <div class="task <?= !empty($candidate->resume) ? 'done' : 'todo' ?>">
          <svg aria-hidden="true"><use href="<?= !empty($candidate->resume) ? '#i-check-c' : '#i-circle' ?>"/></svg> CV uploaded
        </div>
        <div class="task <?= !empty($candidate->skills) ? 'done' : 'todo' ?>">
          <svg aria-hidden="true"><use href="<?= !empty($candidate->skills) ? '#i-check-c' : '#i-circle' ?>"/></svg> Skills &amp; preferences set
        </div>
        <div class="task <?= !empty($candidate->photo) ? 'done' : 'todo' ?>">
          <svg aria-hidden="true"><use href="<?= !empty($candidate->photo) ? '#i-check-c' : '#i-circle' ?>"/></svg> 
          <a href="<?= base_url('candidate/profile/edit') ?>">Upload a profile photo</a>
        </div>
        <p style="font-size:.72rem;color:var(--muted);margin-top:10px">&#8358;200 earned at the 60% milestone. Finish all steps to reach 100% and earn <b style="color:var(--accent-dark)">&#8358;500 more</b> — credited straight to your wallet.</p>
      </div>
    </div>

    <div class="card">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-star"/></svg> Today's Picks for You</span>
        <a href="<?= base_url('jobs') ?>" class="card-link">All jobs <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
      <div class="card-body">
        <?php if (empty($recommendedJobs)): ?>
            <p class="text-muted text-center py-4 mb-0">No job matches found for your current profile. Update your preferences to see recommendations.</p>
        <?php else: ?>
            <?php foreach ($recommendedJobs as $job): ?>
                <?php $matchScore = (int) ($job->match_score ?? 0); ?>
                <div class="pick">
                  <span class="pick-ic" aria-hidden="true"><svg aria-hidden="true"><use href="#i-briefcase"/></svg></span>
                  <div class="pick-info">
                    <div class="pick-title"><a href="<?= base_url('job/view/' . $job->id) ?>"><?= esc($job->title) ?></a></div>
                    <div class="pick-sub"><svg aria-hidden="true"><use href="#i-clock"/></svg> Posted <?= date('d M', strtotime($job->created_at)) ?> · <?= esc($job->location) ?></div>
                  </div>
                  <span class="match-badge<?= $matchScore >= 80 ? ' match-badge--hot' : '' ?>"><?= $matchScore ?>% match</span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="card">
      <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-book"/></svg> Keep Learning</span>
        <a href="<?= base_url('candidate/my-courses') ?>" class="card-link">My courses <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
      <div class="card-body">
        <div class="learn-strip">
          <span class="pick-ic" aria-hidden="true" style="width:44px;height:44px"><svg aria-hidden="true"><use href="#i-award"/></svg></span>
          <div class="grow"><b>Certificates &amp; Badges 🎉</b>
            <p>Acquire career ready skills</p></div>
        </div>
        <hr style="border:none;border-top:1px solid var(--border);margin:14px 0">
        <b style="font-size:.82rem;color:var(--brand-deep)">Suggested next course</b>
        <p style="font-size:.76rem;color:var(--muted);margin:3px 0 10px">Customer Service Excellence — pairs well with your skill profile.</p>
        <a href="<?= base_url('training') ?>" class="btn btn-outline btn-sm btn-block">Browse Training Catalog</a>
      </div>
    </div>
  </section>

  <!-- recent applications -->
  <section class="card" aria-label="Recent applications">
    <div class="card-head"><span class="card-title"><svg aria-hidden="true"><use href="#i-clock"/></svg> Recent Applications</span>
      <a href="<?= base_url('candidate/applications') ?>" class="card-link">View all <svg aria-hidden="true"><use href="#i-arrow-r"/></svg></a></div>
    
    <?php if (empty($recentApplications)): ?>
        <div class="empty">
          <span class="empty-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></span>
          <h3>You haven't applied to any jobs yet</h3>
          <p>Your applications and their status will appear here. Start with today's matches — it takes about 2 minutes to apply.</p>
          <a href="<?= base_url('jobs') ?>" class="btn btn-primary btn-sm"><svg aria-hidden="true"><use href="#i-search"/></svg> Browse Jobs</a>
        </div>
    <?php else: ?>
        <div class="card-body p-0">
          <div class="tbl-wrap">
            <table class="tbl">
              <thead>
                <tr>
                  <th>Job Title</th>
                  <th>Applied On</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($recentApplications as $app): ?>
                  <tr>
                    <td><b><?= esc($app->job_title) ?></b></td>
                    <td><?= date('M d, Y', strtotime($app->created_at)) ?></td>
                    <td>
                      <span class="pill <?= $app->status == 'hired' || $app->status == 'accepted' ? 'pill--hired' : ($app->status == 'rejected' ? 'pill--rejected' : 'pill--pending') ?>">
                        <?= ucfirst($app->status) ?>
                      </span>
                    </td>
                    <td><a href="<?= base_url('candidate/applications/view/' . $app->id) ?>" class="btn btn-outline btn-sm">View Details</a></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
    <?php endif; ?>
  </section>

</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- No extra chart JS scripts needed, using clean server-rendered SVG charts -->
<?= $this->endSection() ?>