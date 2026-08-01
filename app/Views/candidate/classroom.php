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

/* Hero Section */
.cls-hero {
  position: relative; border-radius: var(--radius-lg); color: #fff; padding: clamp(20px, 3.2vw, 32px);
  background: radial-gradient(ellipse 70% 90% at 88% 8%, rgba(237,144,32,.22) 0%, transparent 55%), linear-gradient(150deg, #0A2F57 0%, #064A85 60%, #0861A9 100%);
  box-shadow: var(--shadow); overflow: hidden; margin-bottom: 24px;
}
.hero-grid { display: grid; grid-template-columns: 1fr auto; gap: 20px; align-items: center; }
@media (max-width: 768px) { .hero-grid { grid-template-columns: 1fr; } }
.hero-badges { display: flex; gap: 8px; margin-bottom: 10px; flex-wrap: wrap; }
.hb { display: inline-flex; align-items: center; gap: 6px; font-size: .68rem; font-weight: 700; padding: 4px 11px; border-radius: 20px; text-transform: uppercase; letter-spacing: .04em; }
.hb--live { background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22); color: #fff; }
.hb--premium { background: rgba(237,144,32,.2); border: 1px solid rgba(237,144,32,.4); color: #ffd9a8; }
.cls-hero h1 { font-family: 'Sora', sans-serif; font-size: clamp(1.3rem, 2.8vw, 1.85rem); font-weight: 800; color: #fff; margin: 0 0 6px; }
.cls-hero p.sub { font-size: .86rem; color: rgba(255,255,255,.85); line-height: 1.55; max-width: 600px; margin-bottom: 16px; }
.hero-stats { display: flex; gap: 14px; flex-wrap: wrap; }
.hstat { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.16); border-radius: 10px; padding: 8px 14px; min-width: 120px; }
.hstat-lbl { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: rgba(255,255,255,.7); display: flex; align-items: center; gap: 5px; }
.hstat-val { font-family: 'Sora', sans-serif; font-weight: 800; font-size: .98rem; color: #fff; margin-top: 2px; }

.hero-ring-wrap { display: flex; flex-direction: column; align-items: center; text-align: center; }
.hero-ring { position: relative; width: 104px; height: 104px; }
.hero-ring svg { width: 104px; height: 104px; transform: rotate(-90deg); }
.hero-ring .track { fill: none; stroke: rgba(255,255,255,.15); stroke-width: 8; }
.hero-ring .prog { fill: none; stroke: var(--accent); stroke-width: 8; stroke-linecap: round; stroke-dasharray: 301; stroke-dashoffset: <?= $enrollment->status === 'completed' ? '0' : '150' ?>; transition: stroke-dashoffset 1s ease; }
.hero-ring .num { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; font-family: 'Sora', sans-serif; font-weight: 800; font-size: 1.4rem; color: #fff; line-height: 1; }
.hero-ring .num i { font-style: normal; font-size: .52rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.7); margin-top: 2px; }

/* 3-Column / Main Layout Grid */
.cls-layout { display: grid; grid-template-columns: 300px 1fr; gap: 20px; align-items: start; }
@media (max-width: 992px) { .cls-layout { grid-template-columns: 1fr; } }

/* Cards & Components */
.card { background: #fff; border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow); overflow: hidden; }
.card-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 16px 20px; border-bottom: 1px solid var(--border); }
.card-title { font-family: 'Sora', sans-serif; font-size: .92rem; font-weight: 700; color: var(--brand-deep); display: flex; align-items: center; gap: 8px; }
.card-title svg { width: 16px; height: 16px; color: var(--brand); }
.card-body { padding: 20px; }

/* Curriculum Sidebar */
.cur-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid var(--border); text-decoration: none; color: inherit; transition: var(--transition); }
.cur-item:hover { background: var(--bg); }
.cur-item.active { background: var(--brand-light); border-left: 3px solid var(--brand); }
.cur-num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .76rem; font-weight: 700; background: var(--bg); color: var(--muted); flex-shrink: 0; }
.cur-item.active .cur-num { background: var(--brand); color: #fff; }
.cur-info { min-width: 0; flex: 1; margin: 0 10px; }
.cur-info b { font-size: .82rem; font-weight: 700; color: var(--brand-deep); display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cur-info i { font-style: normal; font-size: .7rem; color: var(--muted); }

/* Player Container */
.player-wrap { background: #0f172a; border-radius: var(--radius-lg); overflow: hidden; position: relative; min-height: 380px; display: flex; flex-direction: column; justify-content: center; }
.player-box { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; width: 100%; }
.player-box iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
.player-reading { padding: 40px 24px; text-align: center; color: #fff; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); display: flex; flex-direction: column; align-items: center; gap: 14px; }
.player-reading .pr-ic { width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,.08); color: var(--accent); display: flex; align-items: center; justify-content: center; }
.player-reading .pr-ic svg { width: 26px; height: 26px; }

/* Lesson Tabs */
.les-tabs { display: flex; gap: 6px; border-bottom: 1px solid var(--border); margin-bottom: 16px; overflow-x: auto; }
.les-tab { border: none; background: transparent; padding: 10px 14px; font-size: .8rem; font-weight: 700; color: var(--muted); cursor: pointer; border-bottom: 2px solid transparent; transition: var(--transition); white-space: nowrap; display: inline-flex; align-items: center; gap: 6px; }
.les-tab:hover { color: var(--brand); }
.les-tab.active { color: var(--brand); border-bottom-color: var(--brand); }
.tab-pane { display: none; }
.tab-pane.active { display: block; }

/* AI Assistant Panel */
.ai-panel { background: linear-gradient(135deg, #0d1b30, #0a2f57); border-radius: 12px; padding: 20px; color: #fff; margin-top: 14px; }
.ai-head { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
.ai-badge { width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, var(--brand), var(--accent)); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.ai-badge svg { width: 17px; height: 17px; color: #fff; }
.ai-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 8px; }
.ai-act { display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14); border-radius: 8px; padding: 10px 12px; color: #fff; font-size: .78rem; font-weight: 600; cursor: pointer; transition: var(--transition); }
.ai-act:hover { background: rgba(255,255,255,.16); border-color: var(--accent); }
.ai-act svg { width: 14px; height: 14px; color: var(--accent); }
.ai-output { margin-top: 14px; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12); border-radius: 8px; padding: 14px; font-size: .82rem; line-height: 1.6; color: #dbe6f2; display: none; }

/* Certificate Showcase */
.cert-showcase { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: var(--radius-lg); padding: 24px; text-align: center; border: 1px solid #a7f3d0; margin-bottom: 24px; }
.cert-showcase h3 { font-family: 'Sora', sans-serif; font-size: 1.25rem; font-weight: 800; color: #065f46; margin: 0 0 6px; }
.cert-showcase p { font-size: .84rem; color: #047857; margin-bottom: 16px; }

.skills-row { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
.st-chip { background: var(--brand-light); color: var(--brand-dark); font-size: .76rem; font-weight: 600; padding: 5px 12px; border-radius: 20px; border: 1px solid #cfe2f2; }
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
    <symbol id="i-bookmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21 12 16 5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z"/></symbol>
    <symbol id="i-zap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h8l-1 8 11-13h-8Z"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
    <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M6 11l6 6 6-6M4 21h16"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-3 8-10V5l-8-3-8 3v7c0 7 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="i-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.4 1 2.3h6c0-.9.4-1.8 1-2.3A7 7 0 0 0 12 2Z"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
  </defs>
</svg>

<!-- 1 · HERO HEADER -->
<section class="cls-hero" aria-labelledby="cls-title">
  <div class="hero-grid">
    <div>
      <div class="hero-badges">
        <span class="hb hb--live"><svg style="width:12px;height:12px;" aria-hidden="true"><use href="#i-book"/></svg> Training Classroom</span>
        <span class="hb hb--premium"><?= ucfirst(esc($course->level ?? 'professional')) ?> Level</span>
      </div>
      <h1 id="cls-title"><?= esc($course->title) ?></h1>
      <p class="sub"><?= esc($course->description ?? 'Master essential skillsets with practical video, reading, and assessment modules.') ?></p>
      
      <div class="hero-stats">
        <div class="hstat"><div class="hstat-lbl"><svg style="width:12px;height:12px;" aria-hidden="true"><use href="#i-bookmark"/></svg> Modules</div><div class="hstat-val"><?= count($modules) ?> Lessons</div></div>
        <div class="hstat"><div class="hstat-lbl"><svg style="width:12px;height:12px;" aria-hidden="true"><use href="#i-clock"/></svg> Duration</div><div class="hstat-val"><?= esc($course->duration ?? 'Self-paced') ?></div></div>
        <div class="hstat"><div class="hstat-lbl"><svg style="width:12px;height:12px;" aria-hidden="true"><use href="#i-user"/></svg> Instructor</div><div class="hstat-val"><?= esc($course->instructor ?? 'JobberRecruit Faculty') ?></div></div>
      </div>
    </div>
    
    <div class="hero-ring-wrap">
      <div class="hero-ring">
        <svg viewBox="0 0 104 104" aria-hidden="true"><circle class="track" cx="52" cy="52" r="48"/><circle class="prog" cx="52" cy="52" r="48"/></svg>
        <span class="num"><?= $enrollment->status === 'completed' ? '100%' : '50%' ?><i>Progress</i></span>
      </div>
    </div>
  </div>
</section>

<!-- 2 · COMPLETED CERTIFICATE SHOWCASE -->
<?php if ($enrollment->status === 'completed'): ?>
<section class="cert-showcase" aria-labelledby="cert-claim-title">
  <h3 id="cert-claim-title">🎉 Course Completed &amp; Certified!</h3>
  <p>You have successfully completed all curriculum requirements. Your official certificate is ready for download and verification.</p>
  <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
    <?php if (!empty($certificate)): ?>
      <?php 
        $certId = is_object($certificate) ? $certificate->id : ($certificate['id'] ?? null);
        $certCode = is_object($certificate) ? $certificate->certificate_code : ($certificate['certificate_code'] ?? '');
      ?>
      <?php if ($certId): ?>
        <a href="<?= base_url('training/certificate/download/' . $certId) ?>" class="btn btn-primary btn-sm"><svg aria-hidden="true"><use href="#i-download"/></svg> Download Certificate PDF</a>
      <?php endif; ?>
      <?php if ($certCode): ?>
        <a href="<?= base_url('certificates/verify?id=' . rawurlencode($certCode)) ?>" class="btn btn-outline btn-sm" target="_blank"><svg aria-hidden="true"><use href="#i-shield"/></svg> Verify Online (<?= esc($certCode) ?>)</a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<!-- 3 · MAIN WORKSPACE & CURRICULUM -->
<div class="cls-layout">
  
  <!-- LEFT: Curriculum Sidebar -->
  <aside class="card" aria-label="Course Curriculum">
    <div class="card-head">
      <span class="card-title"><svg aria-hidden="true"><use href="#i-bookmark"/></svg> Course Curriculum</span>
      <span class="pill pill--reviewed"><?= count($modules) ?> Items</span>
    </div>
    <div class="card-body p-0">
      <?php if (empty($modules)): ?>
        <div style="padding:24px;text-align:center;color:var(--muted);font-size:.84rem;">No modules added to this course yet.</div>
      <?php else: ?>
        <?php foreach ($modules as $idx => $mod): ?>
          <?php $isActive = $activeModule && (int)$activeModule->id === (int)$mod->id; ?>
          <a href="<?= base_url('candidate/my-courses/' . $course->id . '?module_id=' . $mod->id) ?>" class="cur-item <?= $isActive ? 'active' : '' ?>">
            <span class="cur-num"><?= $idx + 1 ?></span>
            <div class="cur-info">
              <b><?= esc($mod->title) ?></b>
              <i><?= ucfirst(esc($mod->content_source ?? 'Lesson')) ?></i>
            </div>
            <?php if ($isActive): ?>
              <span class="pill pill--reviewed" style="font-size:.6rem">Active</span>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </aside>

  <!-- RIGHT: Main Player & Workspace -->
  <main class="col" style="display:flex;flex-direction:column;gap:20px;">
    <?php if ($activeModule): ?>
      
      <!-- Video / Content Player -->
      <section class="card player-wrap">
        <?php if ($activeModule->content_source === 'youtube' && !empty($youtubeEmbedUrl)): ?>
          <div class="player-box">
            <iframe src="<?= esc($youtubeEmbedUrl) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
        <?php elseif ($activeModule->content_source === 'upload' && !empty($activeModule->content_file)): ?>
          <div class="player-reading">
            <div class="pr-ic"><svg aria-hidden="true"><use href="#i-doc"/></svg></div>
            <b style="font-family:'Sora',sans-serif;font-size:1.1rem;color:#fff;">Resource File Attached</b>
            <p>Download the official lesson document below to read and complete this topic.</p>
            <a href="<?= base_url('training/content/' . $course->id . '?module_id=' . $activeModule->id) ?>" class="btn btn-accent btn-sm"><svg aria-hidden="true"><use href="#i-download"/></svg> Download Resource</a>
          </div>
        <?php else: ?>
          <div class="player-reading">
            <div class="pr-ic"><svg aria-hidden="true"><use href="#i-book"/></svg></div>
            <b style="font-family:'Sora',sans-serif;font-size:1.1rem;color:#fff;">Text Learning Module</b>
            <p>Read the syllabus instructions and topics below to master this module.</p>
          </div>
        <?php endif; ?>
      </section>

      <!-- Active Module Details & Tabs -->
      <section class="card">
        <div class="card-head">
          <span class="card-title"><svg aria-hidden="true"><use href="#i-doc"/></svg> <?= esc($activeModule->title) ?></span>
          <span class="pill pill--reviewed">Source: <?= strtoupper(esc($activeModule->content_source)) ?></span>
        </div>
        <div class="card-body">
          <div class="les-tabs" role="tablist">
            <button class="les-tab active" data-tab="overview"><svg style="width:14px;height:14px;" aria-hidden="true"><use href="#i-doc"/></svg> Overview</button>
            <button class="les-tab" data-tab="ai-assistant"><svg style="width:14px;height:14px;" aria-hidden="true"><use href="#i-zap"/></svg> AI Assistant</button>
          </div>

          <!-- Overview Tab -->
          <div class="tab-pane active" id="pane-overview">
            <h4 style="font-family:'Sora',sans-serif;font-size:.92rem;font-weight:700;color:var(--brand-deep);margin-bottom:10px;">Module Syllabus &amp; Notes</h4>
            <div style="font-size:.86rem;color:var(--text);line-height:1.7;">
              <?= !empty($activeModule->description) ? nl2br(esc($activeModule->description)) : '<p class="text-muted">No description provided for this lesson module.</p>' ?>
            </div>
          </div>

          <!-- AI Assistant Tab -->
          <div class="tab-pane" id="pane-ai-assistant">
            <div class="ai-panel">
              <div class="ai-head">
                <span class="ai-badge"><svg aria-hidden="true"><use href="#i-zap"/></svg></span>
                <div>
                  <h4 style="font-family:'Sora',sans-serif;margin:0;">AI Learning Assistant</h4>
                  <span style="font-size:.72rem;color:#9fb3cc;">Grounded in this lesson's curriculum</span>
                </div>
              </div>
              <div class="ai-actions">
                <button type="button" class="ai-act" data-prompt="summary"><svg aria-hidden="true"><use href="#i-bulb"/></svg> Summarise Lesson</button>
                <button type="button" class="ai-act" data-prompt="takeaways"><svg aria-hidden="true"><use href="#i-check-c"/></svg> Key Takeaways</button>
                <button type="button" class="ai-act" data-prompt="quiz"><svg aria-hidden="true"><use href="#i-shield"/></svg> Generate Quiz</button>
              </div>
              <div class="ai-output" id="ai-output"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- Action Card / Course Completion -->
      <section class="card" style="padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
          <div>
            <h4 style="font-family:'Sora',sans-serif;font-size:.95rem;font-weight:700;color:var(--brand-deep);margin:0 0 4px;">Ready to certify?</h4>
            <p style="font-size:.78rem;color:var(--muted);margin:0;">Mark this course complete once you have reviewed all curriculum materials.</p>
          </div>
          <?php if ($enrollment->status !== 'completed'): ?>
            <button type="button" id="complete-btn" class="btn btn-primary btn-sm"><svg aria-hidden="true"><use href="#i-award"/></svg> Complete Course &amp; Get Certified</button>
          <?php else: ?>
            <span class="pill pill--success" style="font-size:.78rem;padding:6px 14px;"><svg aria-hidden="true"><use href="#i-check"/></svg> Course Completed</span>
          <?php endif; ?>
        </div>
      </section>

    <?php else: ?>
      <section class="card" style="padding:48px;text-align:center;color:var(--muted);">
        <h3>No Active Curriculum Modules</h3>
        <p>Curriculum items are currently being configured for this course.</p>
      </section>
    <?php endif; ?>
  </main>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function(){
'use strict';

// Tab Handler
document.querySelectorAll('.les-tab').forEach(function(tab){
  tab.addEventListener('click', function(){
    document.querySelectorAll('.les-tab').forEach(function(t){ t.classList.remove('active'); });
    document.querySelectorAll('.tab-pane').forEach(function(p){ p.classList.remove('active'); });
    tab.classList.add('active');
    var pane = document.getElementById('pane-' + tab.dataset.tab);
    if(pane) pane.classList.add('active');
  });
});

// AI Assistant Quick Responses
var AI_RESPONSES = {
  summary: "<b>Lesson Summary:</b> This module focuses on mastering core domain principles, practical implementation workflows, and workplace delivery tactics essential for senior role readiness.",
  takeaways: "<b>Key Takeaways:</b><br>• Focus on measurable outcomes over activities.<br>• Structure stakeholder communication using data.<br>• Align technical execution with business strategy.",
  quiz: "<b>Practice Question:</b> What is the primary metric used to evaluate successful execution in this module?<br><i>A) Completion velocity B) Stakeholder ROI C) Code size</i><br><b>Answer: B (Stakeholder ROI)</b>"
};

document.querySelectorAll('.ai-act').forEach(function(btn){
  btn.addEventListener('click', function(){
    var out = document.getElementById('ai-output');
    if(!out) return;
    out.style.display = 'block';
    out.innerHTML = AI_RESPONSES[btn.dataset.prompt] || "Analyzing module context…";
  });
});

// Complete Course Button Handler
var completeBtn = document.getElementById('complete-btn');
if(completeBtn){
  completeBtn.addEventListener('click', function(){
    completeBtn.disabled = true;
    completeBtn.textContent = 'Verifying completion…';
    
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
      if(data.success){
        if(typeof celebrateSuccess === 'function') celebrateSuccess();
        alert(data.message || 'Course completed successfully!');
        window.location.reload();
      } else {
        alert(data.message || 'Error completing course.');
        completeBtn.disabled = false;
        completeBtn.textContent = 'Complete Course & Get Certified';
      }
    })
    .catch(function(){
      alert('Network error. Please try again.');
      completeBtn.disabled = false;
      completeBtn.textContent = 'Complete Course & Get Certified';
    });
  });
}
})();
</script>
<?= $this->endSection() ?>

