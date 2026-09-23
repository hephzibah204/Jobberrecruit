<?php $page_title = 'AI Resume Builder'; ?>
<?= $this->extend('layouts/app') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('css/jobber-recruit.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" href="<?= base_url('auth/plugins/tabler-icons/tabler-icons.min.css') ?>">
<?= $this->include('candidate/resume/ai_replies_css') ?>
<style>
@media print {
    #print-root .wm { display: flex !important; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <defs>
    <symbol id="jr-mark" viewBox="0 0 925.5 1269.15"><path fill="#F08F1C" stroke="#F08F1C" stroke-width="5.25" stroke-miterlimit="2.61313" d="M292.08 333.8c-199.04,44 -324.72,241.02 -280.72,440.05 44,199.04 241.02,324.72 440.05,280.72 50.72,-11.21 96.67,-32.38 136.27,-60.97l259.28 271.83 74.82 -70.68 -259.88 -272.45c65.88,-83.89 95.06,-195.51 70.24,-307.79 -44,-199.03 -241.02,-324.72 -440.05,-280.72zm23.48 106.2c-140.39,31.03 -229.04,169.99 -198,310.38 31.03,140.39 169.99,229.04 310.38,198.01 140.39,-31.04 229.04,-170 198,-310.39 -31.03,-140.39 -169.99,-229.04 -310.38,-198z"/><path fill="#F08F1C" d="M372.31 0c76.1,0 137.78,61.69 137.78,137.79 0,76.1 -61.69,137.78 -137.78,137.78 -76.09,0 -137.78,-61.69 -137.78,-137.78 0,-76.1 61.69,-137.79 137.78,-137.79z"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
    <symbol id="i-arrow-l" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></symbol>
    <symbol id="i-arrow-r" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l6 6-6 6"/></symbol>
    <symbol id="i-check-c" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m4.5 12.5 5 5 10-11"/></symbol>
    <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
    <symbol id="i-download" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12M6 11l6 6 6-6M4 21h16"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></symbol>
    <symbol id="i-zap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h8l-1 8 11-13h-8Z"/></symbol>
    <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></symbol>
    <symbol id="i-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
    <symbol id="i-briefcase" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></symbol>
    <symbol id="i-grad" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2.5 3 6 3s6-1 6-3v-5"/></symbol>
    <symbol id="i-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-3 8-10V5l-8-3-8 3v7c0 7 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="i-bookmark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21 12 16 5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z"/></symbol>
    <symbol id="i-x" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></symbol>
    <symbol id="i-refresh" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></symbol>
    <symbol id="i-sparkles" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3Z"/></symbol>
    <symbol id="i-cloud-upload" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M12 12v9"/><path d="m16 16-4-4-4 4"/></symbol>
    <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></symbol>
  </defs>
</svg>
<!-- ═══════ MOCKUP LAYOUT: rb-bar + design-bar + rb-tabs + rb-split ═══════ -->
<div class="rb-bar" role="toolbar" aria-label="Resume tools">
  <a href="<?= site_url('candidate/resumes') ?>" class="ic-btn" id="back-list" title="Back to my resumes" style="text-decoration:none;" aria-label="Back to my resumes">
    <svg aria-hidden="true"><use href="#i-arrow-l"/></svg>
  </a>
  <input type="text" class="rb-name-in" value="<?= esc($resume->title ?? 'Untitled Resume') ?>" id="resume-name-input" name="title" aria-label="Resume name">
  <span class="autosave" id="autosave"><svg aria-hidden="true"><use href="#i-check-c"/></svg> <span id="autosave-t">Saved</span></span>
  <span class="pagepill" id="pagepill" aria-live="polite"><svg aria-hidden="true"><use href="#i-doc"/></svg> <b id="pagepill-n">1 page</b></span>
  <div class="rb-bar-right">
    <select id="template-select-top" class="select" aria-label="Template" onchange="selectTemplate(this.value)">
      <option value="t-exec" <?= ($resume->template_id ?? '') === 't-exec' ? 'selected' : '' ?>>Executive</option>
      <option value="t-pro" <?= ($resume->template_id ?? '') === 't-pro' ? 'selected' : '' ?>>Professional</option>
      <option value="t-modern" <?= ($resume->template_id ?? '') === 't-modern' || empty($resume->template_id) ? 'selected' : '' ?>>Modern</option>
      <option value="t-serif" <?= ($resume->template_id ?? '') === 't-serif' ? 'selected' : '' ?>>Elegant Serif</option>
      <option value="t-tech" <?= ($resume->template_id ?? '') === 't-tech' ? 'selected' : '' ?>>Tech / Startup</option>
      <option value="t-classic" <?= ($resume->template_id ?? '') === 't-classic' ? 'selected' : '' ?>>Classic</option>
      <option value="t-minimal" <?= ($resume->template_id ?? '') === 't-minimal' ? 'selected' : '' ?>>Minimal</option>
    </select>
    <button type="button" class="btn btn-outline btn-sm me-1" id="btn-import-profile-top" onclick="importFromProfile()"><svg aria-hidden="true"><use href="#i-user"/></svg> Auto-fill from Profile</button>
    <button type="button" class="btn btn-outline btn-sm me-1" id="btn-cover-top"><svg aria-hidden="true"><use href="#i-mail"/></svg> Cover Letter</button>
    <button type="button" class="btn btn-outline btn-sm me-1" id="btn-delete-resume" title="Delete this resume" data-id="<?= esc($resume->id ?? '') ?>" style="color:#ef4444;border-color:rgba(239,68,68,0.3);"><svg aria-hidden="true" style="color:#ef4444;"><use href="#i-trash"/></svg> Delete</button>
    <div class="export-dropdown" style="position:relative;display:inline-flex;">
      <a href="<?= site_url('candidate/resumes/download/' . ($resume->id ?? '')) ?>" class="btn btn-accent btn-sm download-pdf-btn" style="border-top-right-radius:0;border-bottom-right-radius:0;"><svg aria-hidden="true"><use href="#i-download"/></svg> PDF</a>
      <button type="button" class="btn btn-accent btn-sm dropdown-toggle" style="border-left:1px solid rgba(255,255,255,0.3);padding:8px 8px;border-top-left-radius:0;border-bottom-left-radius:0;" onclick="var m=document.getElementById('export-menu');m.style.display=(m.style.display==='none'||!m.style.display)?'block':'none';" aria-label="Export options">▾</button>
      <div class="export-menu" id="export-menu" style="display:none;position:absolute;top:100%;right:0;margin-top:6px;background:#fff;border:1px solid var(--border);border-radius:10px;box-shadow:0 10px 25px rgba(10,47,87,.15);min-width:200px;z-index:1000;overflow:hidden;">
        <a href="<?= site_url('candidate/resumes/download/' . ($resume->id ?? '')) ?>" class="export-opt" style="display:flex;align-items:center;gap:8px;padding:10px 14px;color:var(--text);font-size:.82rem;font-weight:600;border-bottom:1px solid var(--border);text-decoration:none;"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><use href="#i-doc"/></svg> PDF Document (.pdf)</a>
        <a href="<?= site_url('candidate/resumes/download-docx/' . ($resume->id ?? '')) ?>" class="export-opt" style="display:flex;align-items:center;gap:8px;padding:10px 14px;color:var(--text);font-size:.82rem;font-weight:600;border-bottom:1px solid var(--border);text-decoration:none;"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><use href="#i-doc"/></svg> Word Document (.docx)</a>
        <a href="<?= site_url('candidate/resumes/download-txt/' . ($resume->id ?? '')) ?>" class="export-opt" style="display:flex;align-items:center;gap:8px;padding:10px 14px;color:var(--text);font-size:.82rem;font-weight:600;border-bottom:1px solid var(--border);text-decoration:none;"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><use href="#i-doc"/></svg> Plain Text / ATS (.txt)</a>
        <a href="<?= site_url('candidate/resumes/download-json/' . ($resume->id ?? '')) ?>" class="export-opt" style="display:flex;align-items:center;gap:8px;padding:10px 14px;color:var(--text);font-size:.82rem;font-weight:600;text-decoration:none;"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><use href="#i-doc"/></svg> JSON Resume (.json)</a>
      </div>
    </div>
  </div>
</div>

<div class="design-bar" aria-label="Design controls">
  <span class="lbl">Accent</span>
  <div class="swatches" role="group" aria-label="Accent colour">
    <button class="sw on" data-acc="#0861A9" data-acc2="#ED9020" style="background:linear-gradient(135deg,#0861A9 50%,#ED9020 50%)" onclick="setAccentColor('#0861A9','#ED9020',this)" aria-label="JobberRecruit brand"></button>
    <button class="sw" data-acc="#0861A9" style="background:#0861A9" onclick="setAccentColor('#0861A9','#0861A9',this)" aria-label="Brand blue"></button>
    <button class="sw" data-acc="#0A2F57" style="background:#0A2F57" onclick="setAccentColor('#0A2F57','#0A2F57',this)" aria-label="Navy"></button>
    <button class="sw" data-acc="#0e7a5f" style="background:#0e7a5f" onclick="setAccentColor('#0e7a5f','#0e7a5f',this)" aria-label="Teal"></button>
    <button class="sw" data-acc="#7a1f3d" style="background:#7a1f3d" onclick="setAccentColor('#7a1f3d','#7a1f3d',this)" aria-label="Burgundy"></button>
    <button class="sw" data-acc="#3c4657" style="background:#3c4657" onclick="setAccentColor('#3c4657','#3c4657',this)" aria-label="Graphite"></button>
  </div>
  <span class="lbl">Font</span>
  <select id="font-select" class="select" aria-label="Font pairing" onchange="setFontFamily(this.value)">
    <option value="" selected>Sora + Inter</option>
    <option value="f-serif">Georgia serif</option>
    <option value="f-clean">System clean</option>
  </select>
  <span class="lbl" title="Space between lines and sections">Spacing</span>
  <div class="dens" role="group" aria-label="Line and section spacing">
    <button type="button" id="spacing-roomy-btn" class="on" data-dense="0" title="More breathing room" onclick="setSpacingMode('roomy', this)">Roomy</button>
    <button type="button" id="spacing-tight-btn" data-dense="1" title="Tighter lines and sections" onclick="setSpacingMode('tight', this)">Tight</button>
  </div>
  <span class="wm-note" title="Every resume carries the JobberRecruit mark as proof it was professionally built on the platform"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> Verified by JobberRecruit</span>
</div>

<div class="rb-tabs" role="tablist">
  <button type="button" class="rb-tab on" data-tab="edit" onclick="switchMobileTab('edit',this)" role="tab" aria-selected="true">Edit</button>
  <button type="button" class="rb-tab" data-tab="view" onclick="switchMobileTab('preview',this)" role="tab" aria-selected="false">Preview</button>
</div>

<div class="rb-split tab-edit" id="rb-split">
  <div class="rb-editor-col" id="rb-editor-col">
<?php if (!$resume): ?>
<!-- =================== RESUME ONBOARDING GATEWAY MODAL =================== -->
<style>
    .onboarding-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        animation: fadeInOverlay 0.4s ease forwards;
        overflow-y: auto;
        padding: 4rem 1rem 2rem 1rem;
    }
    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    .onboarding-card-wrap {
        width: 100%;
        max-width: 800px;
        background: linear-gradient(135deg, #0f0c29 0%, #1a1040 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }
    .onboarding-header {
        text-align: center;
        margin-bottom: 1.25rem;
    }
    .onboarding-header .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(13, 96, 158, 0.15);
        border: 1px solid rgba(13, 96, 158, 0.4);
        color: #a5b4fc;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        margin-bottom: 0.5rem;
    }
    .onboarding-header h2 {
        font-size: clamp(1.4rem, 3.5vw, 1.85rem);
        font-weight: 800;
        color: #f8fafc;
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }
    .onboarding-header h2 span {
        background: linear-gradient(90deg, #818cf8, #c084fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .onboarding-header p {
        color: #94a3b8;
        font-size: 0.9rem;
        max-width: 500px;
        margin: 0 auto;
    }
    .ob-options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    .ob-option-card {
        background: rgba(255,255,255,0.04);
        border: 1.5px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: block;
        backdrop-filter: blur(10px);
    }
    .ob-option-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--ob-glow);
        opacity: 0;
        transition: opacity 0.3s;
        border-radius: 12px;
    }
    .ob-option-card:hover::before { opacity: 1; }
    .ob-option-card:hover {
        transform: translateY(-4px) scale(1.01);
        border-color: var(--ob-border);
        box-shadow: 0 15px 40px var(--ob-shadow);
    }
    .ob-option-card.ob-scratch {
        --ob-glow: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, rgba(168,85,247,0.06) 100%);
        --ob-border: rgba(99,102,241,0.5);
        --ob-shadow: rgba(99,102,241,0.25);
    }
    .ob-option-card.ob-profile {
        --ob-glow: linear-gradient(135deg, rgba(16,185,129,0.08) 0%, rgba(6,182,212,0.06) 100%);
        --ob-border: rgba(16,185,129,0.5);
        --ob-shadow: rgba(16,185,129,0.25);
    }
    .ob-option-card.ob-clone {
        --ob-glow: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(239,68,68,0.06) 100%);
        --ob-border: rgba(245,158,11,0.5);
        --ob-shadow: rgba(245,158,11,0.25);
    }
    .ob-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        font-size: 1.4rem;
    }
    .ob-scratch .ob-icon-wrap { background: linear-gradient(135deg, #0d609e, #8b5cf6); }
    .ob-profile .ob-icon-wrap { background: linear-gradient(135deg, #10b981, #06b6d4); }
    .ob-clone   .ob-icon-wrap { background: linear-gradient(135deg, #f59e0b, #ef4444); }
    .ob-icon-wrap i { color: white; }
    .ob-option-card h4 {
        color: #f1f5f9;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.4rem;
    }
    .ob-option-card p {
        color: #94a3b8;
        font-size: 0.78rem;
        line-height: 1.5;
        margin: 0;
    }
    .ob-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 50px;
        font-weight: 700;
        letter-spacing: 0.04em;
    }
    .ob-scratch .ob-badge { background: rgba(99,102,241,0.2); color: #818cf8; }
    .ob-profile .ob-badge { background: rgba(16,185,129,0.2); color: #34d399; }
    .ob-clone   .ob-badge { background: rgba(245,158,11,0.2); color: #fbbf24; }
    .ob-arrow {
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 600;
        transition: color 0.2s, gap 0.2s;
        gap: 6px;
    }
    .ob-option-card:hover .ob-arrow { color: #a5b4fc; gap: 10px; }
    .ob-clone .ob-option-card:hover .ob-arrow { color: #fbbf24; }
    /* Clone picker panel */
    #ob-clone-panel {
        display: none;
        margin-top: 2rem;
        background: rgba(255,255,255,0.04);
        border: 1.5px solid rgba(245,158,11,0.25);
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(8px);
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity:0; transform:translateY(-10px); }
        to   { opacity:1; transform:translateY(0); }
    }
    #ob-clone-panel h6 {
        color: #fbbf24;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .clone-resume-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-radius: 12px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        margin-bottom: 0.75rem;
        transition: all 0.2s;
    }
    .clone-resume-item:hover {
        background: rgba(245,158,11,0.08);
        border-color: rgba(245,158,11,0.3);
    }
    .clone-resume-item .resume-name {
        color: #f1f5f9;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .clone-resume-item .resume-date {
        color: #64748b;
        font-size: 0.78rem;
        margin-top: 2px;
    }
    .clone-resume-item .btn-clone-pick {
        background: linear-gradient(135deg, #f59e0b, #ef4444);
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .clone-resume-item .btn-clone-pick:hover {
        opacity: 0.85;
        transform: scale(1.04);
        color: white;
    }
    .ob-no-resumes {
        text-align: center;
        color: #64748b;
        padding: 1.5rem;
        font-size: 0.9rem;
    }
    /* Profile CV card — disabled state when no file uploaded */
    .ob-option-card.ob-profile-disabled {
        opacity: 0.65;
        cursor: default;
    }
    .ob-option-card.ob-profile-disabled:hover {
        transform: none;
        box-shadow: none;
    }
    .ob-profile-no-cv {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 1rem;
        font-size: 0.8rem;
        color: #ef4444;
        font-weight: 600;
        background: rgba(239,68,68,0.1);
        padding: 5px 12px;
        border-radius: 8px;
        border: 1px solid rgba(239,68,68,0.25);
    }
    .ob-profile-has-cv {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 1rem;
        font-size: 0.8rem;
        color: #34d399;
        font-weight: 600;
        background: rgba(52,211,153,0.08);
        padding: 5px 12px;
        border-radius: 8px;
        border: 1px solid rgba(52,211,153,0.2);
    }
    .ob-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        font-size: 0.85rem;
        text-decoration: none;
        margin-top: 1.25rem;
        transition: color 0.2s;
    }
    .ob-back-link:hover { color: #94a3b8; }
</style>

<div class="onboarding-overlay" id="resumeOnboardingOverlay">
    <div class="onboarding-card-wrap">
        <!-- Header -->
        <div class="onboarding-header">
            <div class="badge-pill">
                <i class="ti ti-sparkles"></i>
                AI Resume Builder
            </div>
            <h2>How would you like to <span>get started?</span></h2>
            <p>Choose the best starting point for your new professional resume.</p>
        </div>

        <!-- Option Cards -->
        <div class="ob-options-grid">

            <!-- Card 1: Start from Scratch -->
            <div class="ob-option-card ob-scratch" id="ob-scratch-card" onclick="startFromScratch()">
                <span class="ob-badge">Quick Start</span>
                <div class="ob-icon-wrap">
                    <i class="ti ti-file-plus"></i>
                </div>
                <h4>Start from Scratch</h4>
                <p>Build a completely new resume with a clean slate. Ideal if you want full creative control from the ground up.</p>
                <div class="ob-arrow">
                    Get started <i class="ti ti-arrow-right"></i>
                </div>
            </div>

            <!-- Card 2: Import from Profile / Uploaded CV -->
            <?php $hasUploadedCv = !empty($candidate?->resume); ?>
            <div class="ob-option-card ob-profile <?= !$hasUploadedCv ? 'ob-profile-disabled' : '' ?>"
                 id="ob-profile-card"
                 <?php if ($hasUploadedCv): ?>onclick="importFromProfile()"<?php endif; ?>>
                <span class="ob-badge">Recommended</span>
                <div class="ob-icon-wrap">
                    <i class="ti ti-cloud-upload"></i>
                </div>
                <h4>Use Uploaded CV</h4>
                <p>Pre-fill your resume automatically using your profile information — skills, job title, education, and bio.</p>
                <?php if ($hasUploadedCv): ?>
                    <div class="ob-profile-has-cv">
                        <i class="ti ti-circle-check"></i> CV on file — ready to import
                    </div>
                <?php else: ?>
                    <div class="ob-profile-no-cv">
                        <i class="ti ti-alert-circle"></i> No CV uploaded yet —
                        <a href="<?= site_url('candidate/profile/edit') ?>" style="color:#f87171; font-weight:700;">Upload in Profile</a>
                    </div>
                <?php endif; ?>
                <div class="ob-arrow">
                    <?= $hasUploadedCv ? 'Import & Continue' : 'Upload first' ?> <i class="ti ti-arrow-right"></i>
                </div>
            </div>

            <!-- Card 3: Clone Existing Resume -->
            <div class="ob-option-card ob-clone" id="ob-clone-card" onclick="toggleClonePanel()">
                <span class="ob-badge">Fast Copy</span>
                <div class="ob-icon-wrap">
                    <i class="ti ti-copy"></i>
                </div>
                <h4>Clone Existing Resume</h4>
                <p>Duplicate one of your saved resumes and tailor it for a new opportunity without starting over.</p>
                <div class="ob-arrow">
                    Choose a resume <i class="ti ti-arrow-right"></i>
                </div>
            </div>
        </div>

        <!-- Clone Picker Panel (hidden by default) -->
        <div id="ob-clone-panel">
            <h6><i class="ti ti-copy"></i> Select a resume to clone</h6>
            <?php if (!empty($allResumes)): ?>
                <?php foreach ($allResumes as $r): ?>
                    <div class="clone-resume-item">
                        <div>
                            <div class="resume-name"><?= esc($r->title) ?></div>
                            <div class="resume-date">Last updated: <?= date('M d, Y', strtotime($r->updated_at)) ?></div>
                        </div>
                        <a href="<?= site_url('candidate/resumes/clone/' . $r->id) ?>" class="btn-clone-pick">
                            <i class="ti ti-copy me-1"></i>Clone This
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="ob-no-resumes">
                    <i class="ti ti-file-off" style="font-size:2rem; display:block; margin-bottom:0.5rem; color:#475569;"></i>
                    You don't have any saved resumes to clone yet.
                </div>
            <?php endif; ?>
        </div>

        <!-- Back link -->
        <div class="text-center">
            <a href="<?= site_url('candidate/resumes') ?>" class="ob-back-link">
                <i class="ti ti-arrow-left"></i> Back to My Resumes
            </a>
        </div>
    </div>
</div>

<!-- Hidden form for importing from profile (POST) -->
<form id="import-profile-form" method="POST" action="<?= site_url('candidate/resumes/import-profile') ?>" style="display:none;">
    <?= csrf_field() ?>
</form>

<script>
    function startFromScratch() {
        // Close the overlay and let the builder load normally
        document.getElementById('resumeOnboardingOverlay').style.animation = 'fadeOutOverlay 0.3s ease forwards';
        setTimeout(() => {
            document.getElementById('resumeOnboardingOverlay').remove();
        }, 300);
    }

    function importFromProfile() {
        const form = document.getElementById('import-profile-form');
        const card = document.getElementById('ob-profile-card');
        if (form && card) {
            card.innerHTML = '<div style="text-align:center;padding:2rem;"><div class="spinner" role="status"></div><p style="color:#94a3b8;margin-top:1rem;font-size:0.9rem;">Creating your resume from profile...</p></div>';
            form.submit();
            return;
        }
        if (typeof window.performProfileAutofill === 'function') {
            window.performProfileAutofill();
        }
    }

    function toggleClonePanel() {
        const panel = document.getElementById('ob-clone-panel');
        const isVisible = panel.style.display === 'block';
        panel.style.display = isVisible ? 'none' : 'block';
        document.getElementById('ob-clone-card').style.borderColor = isVisible ? '' : 'rgba(245,158,11,0.5)';
    }

    // Add fade-out keyframe dynamically
    const style = document.createElement('style');
    style.textContent = '@keyframes fadeOutOverlay { from { opacity:1; } to { opacity:0; } }';
    document.head.appendChild(style);
</script>
<!-- =================== END ONBOARDING MODAL =================== -->

<?php endif; ?>

            <!-- ATS Score panel -->
            <div class="score-card" aria-label="ATS score">
                <div class="score-top">
                    <div class="gauge" role="img" aria-label="ATS score" id="gauge-wrap">
                        <svg viewBox="0 0 74 74" aria-hidden="true">
                            <circle class="t" cx="37" cy="37" r="33"></circle>
                            <circle class="p" id="gauge-p" cx="37" cy="37" r="33"></circle>
                        </svg>
                        <b id="ats-num">0</b>
                    </div>
                    <div class="score-info">
                        <b>Resume Intelligence</b>
                        <p>ATS readiness plus recruiter-grade writing checks — all recalculated as you type.</p>
                    </div>
                </div>
                
                <!-- 6 Metric Gauges Grid -->
                <div class="met-grid" id="met-grid">
                    <!-- Dynamically populated from JS -->
                </div>
                
                <!-- Writing review issues header -->
                <ul class="score-list" id="ats-list">
                    <!-- Dynamic Checklist items go here -->
                </ul>
            </div>

            <form id="resume-form" onsubmit="return false;">
                <input type="hidden" name="id" value="<?= $resume->id ?? '' ?>">
                <?= csrf_field() ?>
                
                <!-- job tailoring -->
                <div class="ed-sec open" id="sec-jd">
                    <div class="ed-head" onclick="toggleEdSec(this)"><span class="ed-title" style="display:inline-flex;gap:8px;align-items:center"><svg style="width:15px;height:15px;color:var(--brand)" aria-hidden="true"><use href="#i-zap"/></svg> Tailor to a Job</span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span>
                    </div>
                    <div class="ed-body">
                        <label class="lbl" for="job-pick">Pick a JobberRecruit listing</label>
                        <select class="select" id="job-pick" aria-label="Tailor to a listed job">
                            <option value="">— Choose a live job on JobberRecruit —</option>
                            <?php foreach ($tailorJobs ?? [] as $tj): ?>
                                <option value="<?= $tj->id ?>" data-desc="<?= esc($tj->description ?? '') ?>"><?= esc($tj->title) ?><?= !empty($tj->company_name) ? ' — ' . esc($tj->company_name) : '' ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label class="lbl" for="jd">Or paste any job description</label>
                        <textarea class="input" id="jd" rows="4" placeholder="Paste the job advert here and we'll score the match and surface missing keywords…"></textarea>
                        <div class="ai-row" style="margin-top:10px;">
                            <button type="button" class="btn-ai" id="btn-ai-tailor"><svg aria-hidden="true"><use href="#i-sparkles"/></svg> Tailor Resume with AI</button>
                        </div>
                        <div id="match-wrap" hidden>
                            <div class="score-top" style="margin-top:12px">
                                <div class="gauge"><svg viewBox="0 0 74 74" aria-hidden="true"><circle class="t" cx="37" cy="37" r="33"/><circle class="p" id="match-p" cx="37" cy="37" r="33" style="stroke:var(--brand)"/></svg><b id="match-num">0%</b></div>
                                <div class="score-info"><b>Job Match Score</b><p>Keyword overlap between this resume and the job description.</p></div>
                            </div>
                            <div class="lbl" style="margin-top:12px">Missing keywords — tap to add to skills</div>
                            <div class="kw-chips" id="kw-chips"></div>
                        </div>
                    </div>
                </div>

                <!-- import existing CV -->
                <div class="ed-sec open" id="sec-import">
                    <div class="ed-head" onclick="toggleEdSec(this)">
                        <span class="ed-title" style="display:inline-flex;gap:9px;align-items:center"><span class="imp-badge" aria-hidden="true"><svg><use href="#i-download"/></svg></span> Import Existing CV <span class="pill-new">New</span></span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span>
                    </div>
                    <div class="ed-body">
                        <p class="hint" style="font-size:.74rem;color:var(--muted);margin-top:12px">Already have a CV? Import it — we keep your original safe so you can always come back to it, and use it to fill this resume's sections.</p>
                        <label class="dropzone" for="cv-file">
                            <span class="dz-ic" aria-hidden="true"><svg><use href="#i-download"/></svg></span>
                            <b>Tap to upload your CV</b>
                            <i>.pdf, .doc, .docx or .txt — your original stays untouched</i>
                            <span class="dz-file" id="dz-file"><svg aria-hidden="true"><use href="#i-doc"/></svg><span id="dz-name"></span></span>
                            <input type="file" id="cv-file" accept=".txt,.pdf,.doc,.docx" aria-label="Upload existing CV">
                        </label>
                        <div id="import-note" hidden></div>
                        <div id="import-orig" hidden>
                            <div class="lbl" style="margin-top:14px">Your original CV — preserved, never modified</div>
                            <textarea class="input" id="orig-txt" rows="6" readonly></textarea>
                            <div class="ai-row">
                                <button type="button" class="btn-ai" id="orig-fill"><svg aria-hidden="true"><use href="#i-zap"/></svg> Fill sections from this CV</button>
                                <button type="button" class="btn btn-outline btn-sm" id="orig-copy"><svg aria-hidden="true"><use href="#i-copy"/></svg> Copy Original</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step: Basic Information -->
                <div class="ed-sec" id="sec-info" data-step="info">
                    <div class="ed-head" onclick="toggleEdSec(this)" role="button" tabindex="0" aria-expanded="true">
                        <span class="ed-grip" title="Drag to reorder" aria-hidden="true"><svg><use href="#i-menu"/></svg></span>
                        <span class="ed-title">Personal Information <span class="ed-tag">Required</span></span>
                        <span class="ic-btn" data-mv="-1" role="button" tabindex="0" aria-label="Move Personal Information up" style="width:30px;height:30px"><svg style="transform:rotate(90deg)" aria-hidden="true"><use href="#i-arrow-l"/></svg></span>
                        <span class="ic-btn" data-mv="1" role="button" tabindex="0" aria-label="Move Personal Information down" style="width:30px;height:30px"><svg style="transform:rotate(-90deg)" aria-hidden="true"><use href="#i-arrow-l"/></svg></span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span>
                    </div>
                    <div class="ed-body">
                        <label class="lbl">Resume Title (Internal) <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="input" value="<?= esc($resume->title ?? 'My Professional Resume') ?>" placeholder="e.g. Senior Software Engineer Resume" style="margin-bottom:12px;">

                        <label class="lbl" for="f-name">Full name</label>
                        <input type="text" name="full_name" id="f-name" class="input" value="<?= esc($resume->full_name ?? $candidate->full_name ?? auth()->user()->username ?? '') ?>" placeholder="Your Full Name">

                        <div class="xp-entry" style="border:none;padding:0;margin-top:0">
                            <div class="row2">
                                <div>
                                    <label class="lbl" for="f-email">Email</label>
                                    <input type="email" name="email" id="f-email" class="input" value="<?= esc($resume->email ?? auth()->user()->email ?? '') ?>" placeholder="Your Email Address">
                                </div>
                                <div>
                                    <label class="lbl" for="f-phone">Phone</label>
                                    <input type="text" name="phone" id="f-phone" class="input" value="<?= esc($resume->phone ?? $candidate->phone ?? '') ?>" placeholder="e.g. +1 234 567 890">
                                </div>
                            </div>
                        </div>

                        <div class="xp-entry" style="border:none;padding:0;margin-top:0">
                            <div class="row2">
                                <div>
                                    <label class="lbl" for="f-loc">Location</label>
                                    <input type="text" name="location" id="f-loc" class="input" value="<?= esc($resume->location ?? $candidate->location ?? '') ?>" placeholder="e.g. New York, USA">
                                </div>
                                <div>
                                    <label class="lbl" for="f-linkedin">LinkedIn Profile URL</label>
                                    <input type="text" name="linkedin" id="f-linkedin" class="input" value="<?= esc($linkedin ?? '') ?>" placeholder="e.g. https://linkedin.com/in/yourprofile">
                                </div>
                            </div>
                        </div>
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec info -->

                <!-- NOTE: Professional Summary is intentionally shown after Experience/Education/Skills in the input flow
                     so AI generation can use the entered data. The summary will still appear at the top in exported resumes. -->

                <!-- Step: Experience -->
                <div class="ed-sec" id="sec-experience" data-step="experience">
                    <div class="ed-head" onclick="toggleEdSec(this)" role="button" tabindex="0" aria-expanded="false">
                        <span class="ed-grip" title="Drag to reorder" aria-hidden="true"><svg><use href="#i-menu"/></svg></span>
                        <span class="ed-title">Work Experience</span>
                        <span class="ic-btn" data-mv="-1" role="button" tabindex="0" aria-label="Move Work Experience up" style="width:30px;height:30px"><svg style="transform:rotate(90deg)" aria-hidden="true"><use href="#i-arrow-l"/></svg></span>
                        <span class="ic-btn" data-mv="1" role="button" tabindex="0" aria-label="Move Work Experience down" style="width:30px;height:30px"><svg style="transform:rotate(-90deg)" aria-hidden="true"><use href="#i-arrow-l"/></svg></span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span>
                    </div>
                    <div class="ed-body" id="experience-container">
                        <!-- Loop through and render existing experiences -->
                        <?php if (empty($experiences)): ?>
                            <div class="text-center py-4 text-muted no-items" style="font-size: 0.85rem;">
                                <p>No experience added yet. Click "Add Experience" to start.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($experiences as $index => $exp): ?>
                                <div class="xp-entry position-relative experience-item">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                    <div class="row2">
                                        <div>
                                            <label class="lbl">Role</label>
                                            <input type="text" name="exp_position[]" class="input" placeholder="Job Position" value="<?= esc($exp->position ?? '') ?>">
                                        </div>
                                        <div>
                                            <label class="lbl">Dates (Start - End)</label>
                                            <div style="display:flex; gap:6px; align-items:center;">
                                                <input type="date" name="exp_start_date[]" class="input" value="<?= esc($exp->start_date ?? '') ?>" style="padding-left:4px; padding-right:4px;">
                                                <span class="exp-end-date-col" style="<?= !empty($exp->is_current) ? 'display: none;' : '' ?>">-</span>
                                                <input type="date" name="exp_end_date[]" class="input exp-end-date-col" value="<?= esc($exp->end_date ?? '') ?>" style="<?= !empty($exp->is_current) ? 'display: none;' : '' ?> padding-left:4px; padding-right:4px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <div style="flex:1;">
                                            <label class="lbl">Company</label>
                                            <input type="text" name="exp_company[]" class="input" placeholder="Company Name" value="<?= esc($exp->company ?? '') ?>">
                                        </div>
                                        <div class="form-check" style="margin-left:15px; margin-top:20px;">
                                            <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="<?= $index ?>" <?= !empty($exp->is_current) ? 'checked' : '' ?> id="exp_current_<?= $index ?>">
                                            <label class="form-check-label lbl" for="exp_current_<?= $index ?>" style="cursor:pointer; display:inline-block; margin-left:4px; margin-bottom:0;">Current</label>
                                        </div>
                                    </div>
                                    <label class="lbl">Achievements — one per line</label>
                                    <textarea name="exp_description[]" class="input" rows="4" placeholder="Describe your responsibilities and achievements..."><?= esc($exp->description ?? '') ?></textarea>
                                    <div class="ai-row">
                                        <button type="button" class="btn-ai improve-desc-ai"><svg aria-hidden="true"><use href="#i-zap"/></svg> Strengthen achievements</button>
                                        <button type="button" class="btn-ai generate-bullets-ai"><svg aria-hidden="true"><use href="#i-edit"/></svg> Generate Bullets</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <button type="button" class="btn btn-outline btn-sm btn-addxp add-experience" style="margin-top:15px; width: 100%;"><svg aria-hidden="true"><use href="#i-plus"/></svg> Add another role</button>
                        <p class="hint" style="font-size:.7rem;color:var(--muted);margin-top:10px;text-align:center;">Strong bullets answer: what problem was solved, what improved, what impact was made.</p>
                        
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec experience -->
                       <!-- Step: Education -->
                <div class="ed-sec" id="sec-education" data-step="education">
                    <div class="ed-head" onclick="toggleEdSec(this)" role="button" tabindex="0" aria-expanded="false">
                        <span class="ed-grip" title="Drag to reorder" aria-hidden="true"><svg><use href="#i-menu"/></svg></span>
                        <span class="ed-title">Education</span>
                        <span class="ic-btn" data-mv="-1" role="button" tabindex="0" aria-label="Move Education up" style="width:30px;height:30px"><svg style="transform:rotate(90deg)" aria-hidden="true"><use href="#i-arrow-l"/></svg></span>
                        <span class="ic-btn" data-mv="1" role="button" tabindex="0" aria-label="Move Education down" style="width:30px;height:30px"><svg style="transform:rotate(-90deg)" aria-hidden="true"><use href="#i-arrow-l"/></svg></span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span>
                    </div>
                    <div class="ed-body" id="education-container">
                        <!-- Loop through and render existing education -->
                        <?php if (empty($education)): ?>
                            <div class="text-center py-4 text-muted no-items" style="font-size: 0.85rem;">
                                <p>No education added yet. Click "Add Education" to start.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($education as $edu): ?>
                                <div class="xp-entry position-relative education-item">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                    <div class="row2">
                                        <div>
                                            <label class="lbl">School / University</label>
                                            <input type="text" name="edu_school[]" class="input" placeholder="School / University" value="<?= esc($edu->institution ?? '') ?>">
                                        </div>
                                        <div>
                                            <label class="lbl">Degree</label>
                                            <select name="edu_degree[]" class="input select">
                                                <option value="">Select Degree / Qualification</option>
                                                <option value="B.Sc." <?= in_array(($edu->degree ?? ''), ['B.Sc.', 'B.Sc', 'Bachelor', "Bachelor's Degree"]) ? 'selected' : '' ?>>B.Sc. / Bachelor of Science</option>
                                                <option value="B.A." <?= in_array(($edu->degree ?? ''), ['B.A.', 'B.A']) ? 'selected' : '' ?>>B.A. / Bachelor of Arts</option>
                                                <option value="B.Eng." <?= in_array(($edu->degree ?? ''), ['B.Eng.', 'B.Eng', 'B.Tech.', 'B.Tech']) ? 'selected' : '' ?>>B.Eng. / B.Tech. (Engineering &amp; Tech)</option>
                                                <option value="LL.B" <?= ($edu->degree ?? '') === 'LL.B' ? 'selected' : '' ?>>LL.B / Law Degree</option>
                                                <option value="MBBS" <?= in_array(($edu->degree ?? ''), ['MBBS', 'MB.BS']) ? 'selected' : '' ?>>MBBS / Medicine &amp; Surgery</option>
                                                <option value="HND" <?= ($edu->degree ?? '') === 'HND' ? 'selected' : '' ?>>HND / Higher National Diploma</option>
                                                <option value="OND / ND" <?= in_array(($edu->degree ?? ''), ['OND / ND', 'OND', 'ND', 'National Diploma']) ? 'selected' : '' ?>>OND / ND (National Diploma)</option>
                                                <option value="NCE" <?= ($edu->degree ?? '') === 'NCE' ? 'selected' : '' ?>>NCE / Nigeria Certificate in Education</option>
                                                <option value="M.Sc." <?= in_array(($edu->degree ?? ''), ['M.Sc.', 'M.Sc', 'Master', "Master's Degree", 'M.A.', 'M.A']) ? 'selected' : '' ?>>M.Sc. / M.A. (Master's Degree)</option>
                                                <option value="MBA" <?= ($edu->degree ?? '') === 'MBA' ? 'selected' : '' ?>>MBA / Master of Business Admin</option>
                                                <option value="PhD" <?= in_array(($edu->degree ?? ''), ['PhD', 'Ph.D.', 'Doctorate']) ? 'selected' : '' ?>>Ph.D. / Doctorate</option>
                                                <option value="PGD" <?= in_array(($edu->degree ?? ''), ['PGD', 'Postgraduate Diploma']) ? 'selected' : '' ?>>PGD / Postgraduate Diploma</option>
                                                <option value="SSCE / WAEC" <?= in_array(($edu->degree ?? ''), ['SSCE / WAEC', 'WAEC', 'NECO', 'High School', 'GCE']) ? 'selected' : '' ?>>SSCE / WAEC / NECO</option>
                                                <option value="Associate" <?= ($edu->degree ?? '') === 'Associate' ? 'selected' : '' ?>>Associate Degree</option>
                                                <option value="Certificate" <?= ($edu->degree ?? '') === 'Certificate' ? 'selected' : '' ?>>Professional Certificate</option>
                                                <option value="Other" <?= ($edu->degree ?? '') === 'Other' ? 'selected' : '' ?>>Other Qualification</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row2" style="margin-top: 10px;">
                                        <div>
                                            <label class="lbl">Field of Study</label>
                                            <input type="text" name="edu_field[]" class="input" placeholder="Field of Study" value="<?= esc($edu->field_of_study ?? '') ?>">
                                        </div>
                                        <div>
                                            <label class="lbl">Graduation Year</label>
                                            <?php 
                                                $gradYear = !empty($edu->graduation_date) ? date('Y', strtotime($edu->graduation_date)) : '';
                                            ?>
                                            <input type="text" name="edu_year[]" class="input" placeholder="YYYY" value="<?= esc($gradYear) ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <button type="button" class="btn btn-outline btn-sm btn-addxp add-education" style="margin-top:15px; width: 100%;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add Education</button>
                        
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec education -->

                <!-- Step: Skills -->
                <div class="ed-sec" id="sec-skills" data-step="skills">
                    <div class="ed-head" onclick="toggleEdSec(this)" role="button" tabindex="0" aria-expanded="false">
                        <span class="ed-grip" title="Drag to reorder" aria-hidden="true"><svg><use href="#i-menu"/></svg></span>
                        <span class="ed-title">Skills & Certs</span>
                        <span class="ic-btn" data-mv="-1" role="button" tabindex="0" aria-label="Move Skills & Certs up" style="width:30px;height:30px"><svg style="transform:rotate(90deg)" aria-hidden="true"><use href="#i-arrow-l"/></svg></span>
                        <span class="ic-btn" data-mv="1" role="button" tabindex="0" aria-label="Move Skills & Certs down" style="width:30px;height:30px"><svg style="transform:rotate(-90deg)" aria-hidden="true"><use href="#i-arrow-l"/></svg></span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span>
                    </div>
                    <div class="ed-body">

                        <label class="lbl">Skills — comma separated</label>
                        <?php 
                            $skillsList = [];
                            if (!empty($skills)) {
                                foreach ($skills as $skill) {
                                    $skillsList[] = $skill->skill_name;
                                }
                            }
                            $skillsVal = implode(', ', $skillsList);
                        ?>
                        <textarea name="skills" class="input" rows="2" placeholder="e.g. PHP, JavaScript, Project Management"><?= esc($skillsVal) ?></textarea>
                        <p class="hint" style="font-size:.7rem;color:var(--muted);margin-top:8px; margin-bottom: 15px;">Aim for 6–10 skills that mirror the job description wording.</p>

                        <label class="lbl">Certifications — one per line</label>
                        <textarea name="certs" class="input" rows="3" placeholder="e.g. Project Management Professional (PMP)&#10;ICAN Chartered Accountant"><?= esc($certs ?? '') ?></textarea>
                        <p class="hint" style="font-size:.7rem;color:var(--muted);margin-top:8px; margin-bottom: 15px;">Your JobberRecruit certificates are verifiable — the code lets employers confirm them online.</p>

                        <label class="lbl">Languages</label>
                        <input type="text" name="languages" class="input" value="<?= esc($languages ?? '') ?>" placeholder="e.g. English, French, Spanish">
                        
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec skills -->

                <!-- Step: Summary (moved after Skills so AI can use experience/education/skills) -->
                <div class="ed-sec" id="sec-summary" data-step="summary">
                    <div class="ed-head" onclick="toggleEdSec(this)" role="button" tabindex="0" aria-expanded="false">
                        <span class="ed-grip" title="Drag to reorder" aria-hidden="true"><svg><use href="#i-menu"/></svg></span>
                        <span class="ed-title">Professional Summary</span>
                        <span class="ic-btn" data-mv="-1" role="button" tabindex="0" aria-label="Move Professional Summary up" style="width:30px;height:30px"><svg style="transform:rotate(90deg)" aria-hidden="true"><use href="#i-arrow-l"/></svg></span>
                        <span class="ic-btn" data-mv="1" role="button" tabindex="0" aria-label="Move Professional Summary down" style="width:30px;height:30px"><svg style="transform:rotate(-90deg)" aria-hidden="true"><use href="#i-arrow-l"/></svg></span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span>
                    </div>
                    <div class="ed-body">
                        <label class="lbl" for="resume-summary">Summary</label>
                        <textarea name="summary" id="resume-summary" class="input" rows="4" placeholder="A brief overview of your professional background and key achievements..."><?= esc($resume->summary ?? '') ?></textarea>
                        <div class="ai-row" style="margin-top: 10px;">
                            <button type="button" class="btn-ai" id="generate-summary-ai"><svg aria-hidden="true"><use href="#i-zap"/></svg> Improve with AI</button>
                        </div>
                        
                    </div><!-- /ed-body -->
                </div><!-- /ed-sec summary -->

                <!-- writing review (auto) -->
                <div class="ed-sec open" id="sec-review">
                    <div class="ed-head" onclick="toggleEdSec(this)"><span class="ed-title" style="display:inline-flex;gap:8px;align-items:center"><svg style="width:15px;height:15px;color:var(--brand)" aria-hidden="true"><use href="#i-eye"/></svg> Writing Review</span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span></div>
                    <div class="ed-body"><div id="issues"></div></div>
                </div>

                <!-- recruiter view -->
                <div class="ed-sec" id="sec-recruiter">
                    <div class="ed-head" onclick="toggleEdSec(this)"><span class="ed-title" style="display:inline-flex;gap:8px;align-items:center"><svg style="width:15px;height:15px;color:var(--brand)" aria-hidden="true"><use href="#i-users"/></svg> Recruiter View</span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span></div>
                    <div class="ed-body" id="recruiter-body"></div>
                </div>

                <!-- more AI outputs -->
                <div class="ed-sec" id="sec-outputs">
                    <div class="ed-head" onclick="toggleEdSec(this)"><span class="ed-title" style="display:inline-flex;gap:8px;align-items:center"><svg style="width:15px;height:15px;color:var(--brand)" aria-hidden="true"><use href="#i-zap"/></svg> More AI Outputs</span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span></div>
                    <div class="ed-body">
                        <div class="out-btns">
                            <button class="btn-ai" data-out="headline">Professional Headline</button>
                            <button class="btn-ai" data-out="pitch">Elevator Pitch</button>
                            <button class="btn-ai" data-out="about">LinkedIn About</button>
                            <button class="btn-ai" data-out="bio">Executive Bio</button>
                        </div>
                        <div class="out-txt" id="out-wrap" hidden>
                            <textarea class="input" id="out-txt" rows="7" aria-label="Generated output"></textarea>
                            <div class="ai-row"><button class="btn btn-outline btn-sm" id="out-copy"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Copy</button></div>
                        </div>
                        <p class="hint" style="font-size:.7rem;color:var(--muted);margin-top:10px">Generated from this resume's facts only — nothing is invented.</p>
                    </div>
                </div>

                <!-- career tools -->
                <div class="ed-sec" id="sec-career">
                    <div class="ed-head" onclick="toggleEdSec(this)"><span class="ed-title" style="display:inline-flex;gap:8px;align-items:center"><svg style="width:15px;height:15px;color:var(--brand)" aria-hidden="true"><use href="#i-award"/></svg> Career Tools</span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span></div>
                    <div class="ed-body">
                        <label class="lbl" for="industry-pick">Tailor tone to industry</label>
                        <select class="select" id="industry-pick" aria-label="Target industry">
                            <option value="">General</option>
                            <option value="Tech">Tech</option>
                            <option value="Finance">Finance</option>
                            <option value="Healthcare">Healthcare</option>
                            <option value="Creative">Creative</option>
                            <option value="Marketing">Marketing</option>
                        </select>
                        <div class="out-btns" style="margin-top:12px">
                            <button type="button" class="btn-ai" data-career="interview">Interview Questions</button>
                            <button type="button" class="btn-ai" data-career="salary">Salary Negotiation</button>
                        </div>
                        <div class="career-out d-none mt-3" id="career-out"></div>
                    </div>
                </div>

                <!-- version history -->
                <div class="ed-sec" id="sec-history">
                    <div class="ed-head" onclick="toggleEdSec(this)"><span class="ed-title" style="display:inline-flex;gap:8px;align-items:center"><svg style="width:15px;height:15px;color:var(--brand)" aria-hidden="true"><use href="#i-clock"/></svg> Version History</span>
                        <span class="ed-chev" aria-hidden="true"><svg><use href="#i-chev-d"/></svg></span></div>
                    <div class="ed-body"><div id="hist-list"><p class="hint" style="font-size:.74rem;color:var(--muted);margin-top:10px">Snapshots are captured automatically as you edit.</p></div></div>
                </div>

                <!-- Expert Review Upsell & Save Actions (Retained for backend functionality) -->
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border); display: flex; justify-content: center; gap: 10px;">
                    <button type="button" id="save-resume-btn" class="btn btn-accent px-4 py-2">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Save Resume
                    </button>
                    <button type="button" id="open-revisions-btn" class="btn btn-outline px-3 py-2" title="Revision History">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Revisions
                    </button>
                </div>
            </form><!-- /resume-form -->

    </div><!-- /rb-editor-col -->

    <!-- ══ PREVIEW ══ -->
    <div class="rb-preview-col" id="rb-preview-col">
        <!-- Hidden tpl select sync for topbar select -->
        <select id="tpl-select" style="display:none;">
            <option value="t-exec">Executive</option>
            <option value="t-pro">Professional</option>
            <option value="t-modern">Modern</option>
            <option value="t-serif">Elegant Serif</option>
            <option value="t-tech">Tech / Startup</option>
            <option value="t-classic">Classic</option>
            <option value="t-minimal">Minimal</option>
        </select>

        <div class="pv-shell"><article class="doc t-modern guides wm-tile" id="doc" aria-label="Resume preview"></article></div>
        <p class="pv-hint" id="pv-hint" hidden>The <b>orange line</b> marks where page 2 begins on A4.</p>
        <div class="fit-row" id="fit-row" hidden><span class="fit-dot" id="fit-dot" aria-hidden="true"></span><span id="fit-txt"></span></div>
    </div><!-- /rb-preview-col -->
</div><!-- /rb-split -->

<!-- Human Expert Review Banner -->
<div class="review-up">
    <span class="rz-ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3.5"/><path d="M3 20a6 6 0 0 1 12 0M16 5a3 3 0 0 1 0 6M21 20a5.5 5.5 0 0 0-4-5.3"/></svg></span>
    <div class="grow"><b>Want human eyes on it?</b><p>Get a professional review from the JobberRecruit team before you send it out.</p></div>
    <a href="/cv-review" class="btn btn-outline btn-sm">Get Expert Review</a>
</div>

<!-- AI Modal Loader -->
<div class="modal fade" id="aiLoaderModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-5 border-0 bg-transparent">
            <div class="spinner mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
            <h4 class="text-white fw-bold">AI is generating content...</h4>
            <p class="text-white-50">Preparing your personalized professional text.</p>
        </div>
    </div>
</div>

<!-- AI Preview Modal -->
<div class="modal fade ai-preview-modal" id="aiPreviewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">AI Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ai-preview-render" id="aiPreviewRender">
        <!-- Rendered AI HTML will appear here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-outline-primary" id="aiCopyPlainBtn">Copy as Plain Text</button>
        <button type="button" class="btn btn-outline-info" id="aiApplyActiveBtn">Apply to Active Field</button>
        <button type="button" class="btn btn-primary" id="aiApplyBtn">Apply to Summary</button>
      </div>
    </div>
  </div>
</div>

<!-- AI Resume Coach FAB Trigger -->
<button type="button" class="ai-coach-fab" data-bs-toggle="offcanvas" data-bs-target="#aiResumeCoachDrawer" aria-controls="aiResumeCoachDrawer" id="open-ai-coach-btn">
    <div class="pulse-ring"></div>
    <i class="ti ti-sparkles"></i>
</button>

<!-- AI Resume Coach Offcanvas Sidebar -->
<div class="offcanvas offcanvas-end custom-coach-offcanvas" tabindex="-1" id="aiResumeCoachDrawer" aria-labelledby="aiResumeCoachDrawerLabel" data-bs-scroll="true" data-bs-backdrop="false">
    <div class="offcanvas-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-md bg-primary-transparent me-2" style="width: 35px; height: 35px; background: rgba(13, 96, 158, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="ti ti-sparkles text-primary fs-18"></i>
            </div>
            <div>
                <h5 class="offcanvas-title mb-0" id="aiResumeCoachDrawerLabel">ResumeAI Coach</h5>
                <span class="badge bg-success bg-opacity-20 text-success fs-10 fw-bold">Active Coaching</span>
            </div>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="coach-chat-container">
        <!-- Messages Log -->
        <div class="coach-messages-area" id="coach-chat-window">
            <div id="coach-chat-messages" class="d-flex flex-column gap-3">
                <!-- Loaded dynamically -->
            </div>
        </div>
        
        <!-- Input Form Area -->
        <div class="coach-input-area">
            <form id="coach-chat-form" onsubmit="return false;">
                <div class="coach-input-group">
                    <input type="text" id="coach-chat-input" class="coach-input-field" placeholder="Type your message to ResumeAI..." autocomplete="off">
                    <button class="coach-send-btn" type="submit" id="btn-coach-send" aria-label="Action">
    <i class="ti ti-send"></i>
</button>
                </div>
            </form>
        </div><!-- /coach-input-area -->
    </div><!-- /coach-chat-container -->
</div><!-- /aiResumeCoachDrawer -->

<!-- Cover Letter Generator Modal -->
<div class="modal fade" id="coverLetterModal" tabindex="-1" aria-labelledby="coverLetterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2" id="coverLetterModalLabel">
          <svg style="width:20px;height:20px;color:var(--primary,#0861a9)" aria-hidden="true"><use href="#i-mail"/></svg>
          AI Cover Letter Generator
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="coverLetterForm" onsubmit="return false;">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="cl-job-title">Target Job Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="cl-job-title" placeholder="e.g. Senior Product Designer" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" for="cl-company-name">Company Name (Optional)</label>
              <input type="text" class="form-control" id="cl-company-name" placeholder="e.g. Acme Fintech Ltd">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold" for="cl-job-desc">Job Description / Requirements (Optional)</label>
              <textarea class="form-control" id="cl-job-desc" rows="3" placeholder="Paste requirements or key duties here to tailor specifically to this opportunity..."></textarea>
            </div>
            <div class="col-12 text-end">
              <button type="button" class="btn btn-primary" id="btn-generate-cl">
                <svg style="width:16px;height:16px;margin-right:6px;vertical-align:-2px" aria-hidden="true"><use href="#i-zap"/></svg> Generate Cover Letter
              </button>
            </div>
          </div>
        </form>

        <div id="cl-result-wrap" class="mt-4 pt-3 border-top d-none">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label fw-bold mb-0 text-dark">Generated Cover Letter</label>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-copy-cl">
                <svg style="width:14px;height:14px;margin-right:4px;vertical-align:-1px" aria-hidden="true"><use href="#i-copy"/></svg> Copy Text
              </button>
            </div>
          </div>
          <textarea class="form-control font-monospace" id="cl-result-text" rows="12" style="font-size:0.88rem;line-height:1.6"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?= $this->include('candidate/resume/partials/revisions_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // ═══ MOCKUP: Accordion toggle ═══
    function toggleEdSec(head) {
        var sec = head.closest('.ed-sec');
        if (sec) {
            sec.classList.toggle('open');
        }
    }

    // ═══ MOCKUP: Mobile tab switcher ═══
    function switchMobileTab(mode, el) {
        document.querySelectorAll('.rb-tab').forEach(function(b) {
            b.classList.remove('on', 'active');
            b.setAttribute('aria-selected', 'false');
        });
        if (el) {
            el.classList.add('on', 'active');
            el.setAttribute('aria-selected', 'true');
        }
        var split = document.getElementById('rb-split') || document.querySelector('.rb-split');
        if (split) {
            split.className = 'rb-split tab-' + (mode === 'edit' ? 'edit' : 'view');
        }
    }

    // ═══ MOCKUP: Design-bar accent color ═══
    function setAccentColor(acc, acc2, el) {
        if (typeof acc2 === 'object' || acc2 instanceof HTMLElement) {
            el = acc2;
            acc2 = acc;
        }
        document.querySelectorAll('.swatches .sw').forEach(function(s) { s.classList.remove('active', 'on'); });
        if (el) el.classList.add('active', 'on');
        var doc = document.querySelector('#doc');
        if (doc) {
            doc.style.setProperty('--acc', acc);
            doc.style.setProperty('--acc2', acc2 || acc);
        }
    }

    // ═══ MOCKUP: Design-bar font family ═══
    function setFontFamily(fontClass) {
        var doc = document.querySelector('#doc');
        if (doc) {
            doc.classList.remove('f-serif', 'f-clean');
            if (fontClass) doc.classList.add(fontClass);
        }
    }

    // ═══ MOCKUP: Select template from topbar ═══
    window.selectTemplate = function(val) {
        $('#tpl-select').val(val).trigger('change');
    };

    // ═══ MOCKUP: Design-bar spacing toggle ═══
    function setSpacing(mode, el) {
        document.querySelectorAll('.dens button').forEach(function(b) { b.classList.remove('active', 'on'); });
        if (el) el.classList.add('active', 'on');
        var doc = document.querySelector('#doc');
        if (doc) {
            doc.classList.remove('spacing-roomy', 'spacing-tight');
            doc.classList.add('spacing-' + (mode === 'tight' || mode === true ? 'tight' : 'roomy'));
        }
    }
    // Alias: HTML calls setSpacingMode but function is named setSpacing
    window.setSpacingMode = setSpacing;

    // ═══ MOCKUP: Accordion open on next/prev click ═══
    function openEdSec(step) {
        var sec = document.querySelector('.ed-sec[data-step="' + step + '"]');
        if (sec) {
            $('.ed-sec').removeClass('open');
            sec.classList.add('open');
            sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
            var firstInput = sec.querySelector('input:not([type="hidden"]), textarea');
            if (firstInput) {
                try { firstInput.focus(); } catch(e){}
            }
        }
    }

    $(document).ready(function() {
        // Delete Resume via AJAX with confirmation dialog
        $('#btn-delete-resume').on('click', function(e) {
            e.preventDefault();
            var resumeId = $(this).data('id') || $('input[name="id"]').val();
            if (!resumeId) {
                if (typeof toastr !== 'undefined') toastr.error('No resume ID found to delete.');
                else alert('No resume ID found to delete.');
                return;
            }
            if (!confirm('Are you sure you want to delete this resume? This cannot be undone.')) {
                return;
            }
            var $btn = $(this);
            $btn.prop('disabled', true);

            $.ajax({
                url: '<?= site_url("candidate/resumes/delete") ?>/' + resumeId,
                type: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(res) {
                    if (res && res.status === 'success') {
                        if (typeof toastr !== 'undefined') toastr.success(res.message || 'Resume deleted successfully.');
                        setTimeout(function() {
                            window.location.href = '<?= site_url("candidate/resumes") ?>';
                        }, 500);
                    } else {
                        var msg = (res && res.message) ? res.message : 'Could not delete resume.';
                        if (typeof toastr !== 'undefined') toastr.error(msg);
                        else alert(msg);
                        $btn.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    if (typeof toastr !== 'undefined') toastr.error('Network error. Please try again.');
                    else alert('Network error. Please try again.');
                    $btn.prop('disabled', false);
                }
            });
        });

        // Cover letter modal trigger & handler
        $('#btn-cover-top').on('click', function() {
            var currentTitle = $('input[name="title"]').val() || '';
            if (currentTitle && !$('#cl-job-title').val()) {
                $('#cl-job-title').val(currentTitle);
            }
            var activeJd = $('#jd').val() || '';
            if (activeJd && !$('#cl-job-desc').val()) {
                $('#cl-job-desc').val(activeJd);
            }
            $('#coverLetterModal').modal('show');
        });

        // Cover letter generation
        $('#btn-generate-cl').on('click', function() {
            var jobTitle = $('#cl-job-title').val().trim();
            if (!jobTitle) {
                alert('Please enter a target job title.');
                $('#cl-job-title').focus();
                return;
            }
            var btn = $(this);
            var origText = btn.html();
            btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1" role="status"></i> Generating Cover Letter...');

            $.post('<?= site_url("candidate/resumes/generate-cover-letter") ?>', {
                job_title: jobTitle,
                company_name: $('#cl-company-name').val().trim(),
                job_description: $('#cl-job-desc').val().trim(),
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }, function(res) {
                btn.prop('disabled', false).html(origText);
                if (res && res.cover_letter) {
                    $('#cl-result-text').val(res.cover_letter);
                    $('#cl-result-wrap').removeAttr('hidden').removeClass('d-none');
                } else {
                    alert('Could not generate cover letter. Please try again.');
                }
            }).fail(function(err) {
                btn.prop('disabled', false).html(origText);
                alert(err.responseJSON?.message || 'Failed to generate cover letter.');
            });
        });

        // Cover letter copy button
        $('#btn-copy-cl').on('click', function() {
            var txt = $('#cl-result-text').val();
            if (!txt) return;
            navigator.clipboard.writeText(txt).then(function() {
                if (typeof toastr !== 'undefined') {
                    toastr.success('Cover letter copied to clipboard!');
                } else {
                    alert('Cover letter copied to clipboard!');
                }
            });
        });

        // Universal modal and offcanvas close handler
        $(document).on('click', '[data-bs-dismiss="modal"], [data-dismiss="modal"], .modal .btn-close, .modal .close-btn', function() {
            var $modal = $(this).closest('.modal');
            if ($modal.length) {
                $modal.modal('hide');
                $modal.removeClass('show').css('display', 'none');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
            }
        });

        $(document).on('click', '[data-bs-dismiss="offcanvas"], .offcanvas .btn-close', function() {
            var $off = $(this).closest('.offcanvas');
            if ($off.length) {
                $off.removeClass('show');
                $('.offcanvas-backdrop').remove();
                $('body').css('overflow', '');
            }
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.modal.show, .modal[style*="display: block"]').modal('hide').removeClass('show').css('display', 'none');
                $('.offcanvas.show').removeClass('show');
                $('.modal-backdrop, .offcanvas-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
            }
        });

        // More AI Outputs copy button
        $('#out-copy').on('click', function() {
            var txt = $('#out-txt').val();
            if (!txt) return;
            navigator.clipboard.writeText(txt).then(function() {
                if (typeof toastr !== 'undefined') {
                    toastr.success('Copied to clipboard!');
                } else {
                    alert('Copied to clipboard!');
                }
            });
        });

        // Utility: escape HTML for safe insertion into preview
        function escapeHtml(str) {
            return String(str || '').replace(/[&<>"']/g, function (s) {
                return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[s]);
            });
        }
        window.escapeHtml = escapeHtml;

        var CLICHES = ["results-driven","results driven","highly motivated","dynamic professional","proven track record","passionate professional","detail-oriented","detail oriented","team player","self-starter","self starter","go-getter","go getter","hardworking individual","think outside the box","synergy"];
        var STRONG_VERBS = ["led","built","cut","grew","launched","delivered","reduced","improved","designed","owned","negotiated","recovered","streamlined","automated","prepared","produced","rebuilt","cleared","processed","reconciled","managed","implemented","run","ran","handle","handled","maintain","maintained","supported","created","trained"];
        var IMPACT_WORDS = ["cut","reduced","grew","saved","improved","increased","delivered","cleared","recovered","shortened","eliminated","doubled"];

        if (typeof renderLivePreview === 'function') {
            renderLivePreview();
        }
        if (typeof refreshAts === 'function') {
            refreshAts();
        }

        // Matches PHP's date('M Y', strtotime($date)) used by the download templates
        function formatMonthYear(dateStr) {
            if (!dateStr) return '';
            var d = new Date(dateStr + 'T00:00:00');
            if (isNaN(d.getTime())) return dateStr;
            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return months[d.getMonth()] + ' ' + d.getFullYear();
        }

        // --- NEW AI AJAX HANDLERS ---
        const gatherResumeData = () => {
            let exp = [];
            $('.experience-item').each(function() {
                exp.push({
                    position: $(this).find('input[name="exp_position[]"]').val(),
                    company: $(this).find('input[name="exp_company[]"]').val(),
                    description: $(this).find('textarea[name="exp_description[]"]').val()
                });
            });
            let skills = ($('textarea[name="skills"], input[name="skills"]').val() || '').split(',').map(s => s.trim()).filter(Boolean);

            return {
                title: $('input[name="title"]').val(),
                summary: $('#resume-summary').val(),
                experience: exp,
                skills: skills,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            };
        };

        // More AI Outputs
        $('[data-out]').on('click', function(e) {
            e.preventDefault();
            let btn = $(this);
            let type = btn.data('out');
            let data = gatherResumeData();
            data.type = type;
            
            let originalText = btn.html();
            btn.html('<i class="ti ti-loader fa-spin"></i> Generating...').prop('disabled', true);
            
            $.post('<?= site_url("candidate/resumes/ai/generate-output") ?>', data, function(res) {
                $('#out-wrap').removeAttr('hidden').removeClass('d-none');
                $('#out-txt').val(res.output);
                btn.html(originalText).prop('disabled', false);
            }).fail(function(err) {
                alert(err.responseJSON?.message || 'Failed to generate output.');
                btn.html(originalText).prop('disabled', false);
            });
        });

        // Career Tools
        $('[data-career]').on('click', function(e) {
            e.preventDefault();
            let btn = $(this);
            let toolType = btn.data('career');
            let data = gatherResumeData();
            data.tool_type = toolType;
            data.industry = $('#industry-pick').val() || 'general';
            
            let originalText = btn.html();
            btn.html('<i class="ti ti-loader fa-spin"></i> Loading...').prop('disabled', true);
            
            $.post('<?= site_url("candidate/resumes/ai/career-tools") ?>', data, function(res) {
                $('#career-out').removeClass('d-none').html(res.output);
                btn.html(originalText).prop('disabled', false);
            }).fail(function(err) {
                alert(err.responseJSON?.message || 'Failed to load career tools.');
                btn.html(originalText).prop('disabled', false);
            });
        });

        // Writing Review trigger (could be on section open)
        // Deferred via setTimeout so this always reads the state AFTER the
        // inline onclick="toggleEdSec(this)" on the same header has toggled
        // the 'open' class — the two handlers otherwise race and this one
        // can see the stale pre-toggle state.
        $('#sec-review .ed-head').on('click', function() {
            let sec = $(this).closest('.ed-sec');
            setTimeout(function() {
                if (sec.hasClass('open') && $('#issues').is(':empty')) {
                    $('#issues').html('<p class="text-muted"><i class="ti ti-loader fa-spin"></i> Analyzing writing style...</p>');
                    $.post('<?= site_url("candidate/resumes/ai/writing-review") ?>', gatherResumeData(), function(res) {
                        $('#issues').html(res.review);
                    }).fail(function() {
                        $('#issues').html('<p class="text-danger">Failed to analyze.</p>');
                    });
                }
            }, 0);
        });

        // Recruiter View trigger
        $('#sec-recruiter .ed-head').on('click', function() {
            let sec = $(this).closest('.ed-sec');
            setTimeout(function() {
                if (sec.hasClass('open') && $('#recruiter-body').is(':empty')) {
                    $('#recruiter-body').html('<p class="text-muted"><i class="ti ti-loader fa-spin"></i> Evaluating ATS score...</p>');
                    $.post('<?= site_url("candidate/resumes/ai/recruiter-view") ?>', gatherResumeData(), function(res) {
                        $('#recruiter-body').html(res.recruiter_view);
                    }).fail(function() {
                        $('#recruiter-body').html('<p class="text-danger">Failed to evaluate.</p>');
                    });
                }
            }, 0);
        });

        // Import CV upload & extraction handler
        $('#cv-file').on('change', function(e) {
            let file = e.target.files[0];
            if (!file) return;
            $('#import-note').removeClass('d-none').html('<p class="text-primary"><i class="spinner-border spinner-border-sm me-1" role="status"></i> Uploading and extracting CV with AI...</p>');
            
            let fd = new FormData();
            fd.append('cv', file);
            fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            $.ajax({
                url: '<?= site_url("candidate/resumes/parse-cv-file") ?>',
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(res) {
                    $('#import-note').addClass('d-none');
                    if (res && res.success && res.data) {
                        $('#import-orig').removeClass('d-none');
                        $('#dz-name').text(file.name);
                        var d = res.data;
                        if (d.full_name) $('input[name="full_name"]').val(d.full_name);
                        if (d.email) $('input[name="email"]').val(d.email);
                        if (d.phone) $('input[name="phone"]').val(d.phone);
                        if (d.location) $('input[name="location"]').val(d.location);
                        if (d.linkedin) $('input[name="linkedin"]').val(d.linkedin);
                        if (d.job_title && !$('input[name="title"]').val()) $('input[name="title"]').val(d.job_title + ' Resume');
                        if (d.summary) {
                            $('#resume-summary').val(d.summary);
                            $('textarea[name="summary"]').val(d.summary);
                        }
                        if (d.skills && Array.isArray(d.skills)) {
                            $('textarea[name="skills"], input[name="skills"]').val(d.skills.join(', '));
                        }
                        if (d.certifications) {
                            $('textarea[name="certs"]').val(Array.isArray(d.certifications) ? d.certifications.join('\n') : d.certifications);
                        }
                        if (d.languages) {
                            $('input[name="languages"]').val(Array.isArray(d.languages) ? d.languages.join(', ') : d.languages);
                        }
                        // Fill experiences
                        if (d.experiences && Array.isArray(d.experiences) && d.experiences.length > 0) {
                            $('#experience-container').find('.experience-item').remove();
                            d.experiences.forEach(function(exp) {
                                if (typeof addExperienceItem === 'function') {
                                    addExperienceItem(exp.company || '', exp.position || exp.job_title || '', exp.start_date || '', exp.end_date || '', exp.description || '', exp.is_current || false);
                                }
                            });
                        }
                        // Fill education
                        if (d.education && Array.isArray(d.education) && d.education.length > 0) {
                            $('#education-container').find('.education-item').remove();
                            d.education.forEach(function(edu) {
                                if (typeof addEducationItem === 'function') {
                                    addEducationItem(edu.school || edu.institution || '', edu.degree || '', edu.field || edu.field_of_study || '', edu.year || '');
                                }
                            });
                        }
                        renderLivePreview();
                        refreshAts();
                        if (typeof toastr !== 'undefined') {
                            toastr.success('CV extracted and loaded into builder!');
                        }
                    } else {
                        alert(res.message || 'Could not parse CV file automatically. Please enter details manually.');
                    }
                },
                error: function() {
                    $('#import-note').addClass('d-none');
                    alert('Error connecting to CV extraction service. Please check your connection.');
                }
            });
        });

        // Version History (Local Snapshots)
        function takeSnapshot() {
            let data = gatherResumeData();
            let snapshots = JSON.parse(localStorage.getItem('resume_snapshots_' + $('input[name="id"]').val()) || '[]');
            let time = new Date().toLocaleTimeString();
            snapshots.push({ time: time, data: data });
            if (snapshots.length > 10) snapshots.shift(); // Keep last 10
            localStorage.setItem('resume_snapshots_' + $('input[name="id"]').val(), JSON.stringify(snapshots));
            renderSnapshots(snapshots);
        }

        function renderSnapshots(snapshots) {
            let html = '<p class="text-muted mb-3" style="font-size:.75rem;">Snapshots are captured automatically as you edit.</p>';
            if (snapshots.length === 0) {
                html += '<p class="text-muted fst-italic">No snapshots yet.</p>';
            } else {
                html += '<div class="list-group list-group-flush">';
                snapshots.reverse().forEach((s, i) => {
                    html += `<button type="button" class="list-group-item list-group-item-action py-2 px-1" style="font-size:0.85rem;">
                                <i class="ti ti-clock me-2 text-primary"></i> Snapshot at ${s.time}
                             </button>`;
                });
                html += '</div>';
            }
            $('#hist-list').html(html);
        }

        // Trigger snapshot periodically if changes were made, or just when the section opens
        $('#sec-history .ed-head').on('click', function() {
            let sec = $(this).closest('.ed-sec');
            if (sec.hasClass('open')) {
                takeSnapshot();
            }
        });

        // ── LIVE PREVIEW GENERATION (Truthful candidate data only, no fake defaults) ──
        function renderLivePreview() {
            var rawName = $('input[name="full_name"]').val() || '';
            var rawTitle = $('input[name="title"]').val() || '';
            var rawEmail = $('input[name="email"]').val() || '';
            var rawPhone = $('input[name="phone"]').val() || '';
            var rawLoc = $('input[name="location"]').val() || '';
            var rawLinkedin = $('input[name="linkedin"]').val() || '';
            var rawSummary = $('#resume-summary').val() || '';

            var name = rawName.trim();
            var title = rawTitle.trim();
            var email = rawEmail.trim();
            var phone = rawPhone.trim();
            var locationStr = rawLoc.trim();
            var linkedin = rawLinkedin.trim();
            var summary = rawSummary.trim();
            
            // Selected layout & density
            var tpl = $('#template-select-top').val() || $('#tpl-select').val() || 't-modern';
            if (!tpl.startsWith('t-')) {
                var tplMap = { 'classic':'t-classic', 'modern':'t-modern', 'creative':'t-creative', 'executive':'t-exec', 'minimalist':'t-minimal' };
                tpl = tplMap[tpl] || ('t-' + tpl);
            }
            var spacing = $('#spacing-roomy-btn').hasClass('on') ? 'spacing-roomy' : 'spacing-tight';
            
            // Container update
            var $doc = $('#doc');
            $doc.removeClass().addClass('doc ' + tpl + ' ' + spacing + ' wm-tile guides');
            
            var contactHtml = '';
            if (email) contactHtml += '<span>' + escapeHtml(email) + '</span>';
            if (phone) contactHtml += '<span>' + escapeHtml(phone) + '</span>';
            if (locationStr) contactHtml += '<span>' + escapeHtml(locationStr) + '</span>';
            if (linkedin) contactHtml += '<span>' + escapeHtml(linkedin.replace(/^https?:\/\/(www\.)?/, '')) + '</span>';
            
            var html = '';
            if (name || title || contactHtml) {
                html += '<header class="d-head"><h1>' + escapeHtml(name || 'Resume Header') + '</h1>';
                if (title) html += '<div class="d-title">' + escapeHtml(title) + '</div>';
                if (contactHtml) html += '<div class="d-contact">' + contactHtml + '</div>';
                html += '</header>';
            }
            
            // Professional Summary
            if (summary) {
                html += '<div class="d-sec"><h2>Professional Summary</h2><p>' + escapeHtml(summary).replace(/\n/g, '<br>') + '</p></div>';
            }
            
            // Experience List
            var experienceHtml = '';
            $('.experience-item').each(function() {
                var role = $(this).find('input[name="exp_position[]"]').val() || '';
                var company = $(this).find('input[name="exp_company[]"]').val() || '';
                var start = $(this).find('input[name="exp_start_date[]"]').val() || '';
                var end = $(this).find('input[name="exp_end_date[]"]').val() || '';
                var current = $(this).find('.exp-current-check').is(':checked');
                var desc = $(this).find('textarea[name="exp_description[]"]').val() || '';
                
                var dates = formatMonthYear(start) + ' – ' + (current ? 'Present' : (end ? formatMonthYear(end) : ''));
                if (role || company || desc) {
                    var bulletPoints = desc.split('\n').map(s => s.trim()).filter(Boolean);
                    var bulletsUl = '';
                    if (bulletPoints.length) {
                        bulletsUl = '<ul>' + bulletPoints.map(b => '<li>' + escapeHtml(b) + '</li>').join('') + '</ul>';
                    }
                    experienceHtml += '<div class="d-xp"><div class="d-xp-h"><b>' + escapeHtml(role) + '</b><i>' + escapeHtml(dates) + '</i></div>' +
                        (company ? '<p class="co">' + escapeHtml(company) + '</p>' : '') + bulletsUl + '</div>';
                }
            });

            if (experienceHtml) {
                html += '<div class="d-sec"><h2>Work Experience</h2>' + experienceHtml + '</div>';
            }
            
            // Education List
            var educationHtml = '';
            $('.education-item').each(function() {
                var school = $(this).find('input[name="edu_school[]"]').val() || '';
                var degree = $(this).find('select[name="edu_degree[]"]').val() || '';
                var field = $(this).find('input[name="edu_field[]"]').val() || '';
                var year = $(this).find('input[name="edu_year[]"]').val() || '';
                
                if (school || degree || field) {
                    educationHtml += '<div class="d-xp"><div class="d-xp-h"><b>' + escapeHtml((degree ? degree + ' in ' : '') + field) + '</b><i>' + escapeHtml(year) + '</i></div>' +
                        (school ? '<p class="co">' + escapeHtml(school) + '</p>' : '') + '</div>';
                }
            });

            if (educationHtml) {
                html += '<div class="d-sec"><h2>Education</h2>' + educationHtml + '</div>';
            }
            
            // Skills List
            var skillsStr = $('textarea[name="skills"], input[name="skills"]').val() || '';
            var skills = skillsStr.split(',').map(s => s.trim()).filter(Boolean);
            if (skills.length) {
                var skillsLi = skills.map(s => '<li>' + escapeHtml(s) + '</li>').join('');
                html += '<div class="d-sec"><h2>Skills</h2><ul class="d-skills">' + skillsLi + '</ul></div>';
            }

            // Certifications List
            var certsStr = $('textarea[name="certs"]').val() || '';
            var certs = certsStr.split('\n').map(s => s.trim()).filter(Boolean);
            if (certs.length) {
                var certsLi = certs.map(c => '<li>' + escapeHtml(c) + '</li>').join('');
                html += '<div class="d-sec"><h2>Certifications</h2><ul class="d-skills">' + certsLi + '</ul></div>';
            }

            // Languages List
            var languagesStr = $('input[name="languages"]').val() || '';
            var languages = languagesStr.split(',').map(s => s.trim()).filter(Boolean);
            if (languages.length) {
                var languagesLi = languages.map(l => '<li>' + escapeHtml(l) + '</li>').join('');
                html += '<div class="d-sec"><h2>Languages</h2><ul class="d-skills">' + languagesLi + '</ul></div>';
            }
            
            // Watermark anti-crop element
            html += '<div class="wm" aria-hidden="true">' +
                '<svg class="wm-ic" viewBox="0 0 925.5 1269.15"><use href="#jr-mark"/></svg>' +
                '<span class="wm-tx">www.JobberRecruit.com</span>' +
                '</div>';
                
            $doc.html(html);
            
            // Adjust layout for Executive template
            if (tpl === 't-exec') {
                var head = $doc.find('.d-head')[0];
                var wm = $doc.find('.wm')[0];
                var secs = $doc.find('.d-sec').toArray();
                var sideKeys = ["Certifications", "Skills", "Languages"];
                
                var side = document.createElement("div"); side.className = "exec-side";
                var main = document.createElement("div"); main.className = "exec-main";
                
                secs.forEach(function(sec) {
                    var titleText = $(sec).find('h2').text() || '';
                    if (sideKeys.indexOf(titleText.trim()) > -1) {
                        side.appendChild(sec);
                    } else {
                        main.appendChild(sec);
                    }
                });
                
                $doc.html('');
                if (head) $doc.append(head);
                $doc.append(side); $doc.append(main);
                if (wm) $doc.append(wm);
            }
            
            // Check fit pages & dynamic page count indicator
            var scrollHeight = $doc[0].scrollHeight;
            var maxOnePageHeight = 1074;
            var fitDot = $('.fit-dot');
            var totalPages = Math.max(1, Math.ceil(scrollHeight / maxOnePageHeight));
            $('#pagepill-n').text(totalPages + (totalPages === 1 ? ' page' : ' pages'));
            if (totalPages > 1) {
                fitDot.removeClass('ok').addClass('over');
                $('.pv-hint').removeAttr('hidden').html('Spanning <b>' + totalPages + ' Pages</b> on A4. Switch Spacing to <b>Tight</b> or trim text to fit 1 page.');
            } else {
                fitDot.removeClass('over').addClass('ok');
                $('.pv-hint').removeAttr('hidden').html('Perfect! Fits cleanly on <b>1 Page</b>.');
            }
        }
        
        var STOP = "the and for with our you your this that will have has are was were from into able out not can may all any per who what when they them their its it's more than been being to of in on at as by an or a is be we do if so".split(" ");
        var GENERIC = ("finance financial expense expenses reporting compliance vendor vendors monthly "
          + "leadership statutory filings operations operation duties duty support seeking seek strong "
          + "willingness encouraged provided discipline graduate graduates trainee programme role roles "
          + "team teams company companies business businesses department departments process processes "
          + "environment environments candidate candidates requirement requirements responsibility "
          + "responsibilities experience experienced years year work working ability abilities knowledge "
          + "understanding including related general overall various multiple wide range level high "
          + "excellent good great proven demonstrated across throughout applicants applicant apply "
          + "position positions salary benefits location office hours schedule").split(" ");

        function explicitSkillList(txt) {
            var m = String(txt).match(/(?:skills|requirements|competencies)\s*[:\-]\s*([^.]+)\./i);
            if (!m) return [];
            return m[1].split(/,|;|\u2022|\u00b7/).map(function(s){ return s.trim().toLowerCase(); })
                .filter(function(s){ return s.length > 2 && s.length < 40; });
        }

        function skillBigrams(txt) {
            var words = String(txt).toLowerCase().replace(/[^a-z\s-]/g," ").split(/\s+/).filter(Boolean);
            var out = {};
            for (var i = 0; i < words.length - 1; i++){
                var a = words[i], b = words[i+1];
                if (a.length > 3 && b.length > 3 && STOP.indexOf(a) === -1 && STOP.indexOf(b) === -1
                    && GENERIC.indexOf(a) === -1 && GENERIC.indexOf(b) === -1){
                    var phrase = a + " " + b;
                    out[phrase] = (out[phrase]||0) + 1;
                }
            }
            return Object.keys(out).sort(function(x,y){return out[y]-out[x]});
        }

        function keywords(txt) {
            var explicit = explicitSkillList(txt);
            if (explicit.length) return explicit.slice(0, 14);

            var bigrams = skillBigrams(txt).slice(0, 8);

            var f = {};
            String(txt).toLowerCase().replace(/[^a-z\s-]/g," ").split(/\s+/).forEach(function(w){
                if (w.length > 3 && STOP.indexOf(w) === -1 && GENERIC.indexOf(w) === -1) f[w] = (f[w]||0)+1;
            });
            var singles = Object.keys(f).sort(function(a,b){return f[b]-f[a]});

            var combined = bigrams.concat(singles).slice(0, 14);
            return combined;
        }

        // ── ATS SCAN CHECKLIST & INTELLIGENCE ENGINE ──
        var SYN = {
            reconciliation:["reconcile","reconciled","reconciling","bank reconciliation"],
            accounting:["accounts","accountant","bookkeeping","ledger"],
            reporting:["reports","management accounts","financial reporting"],
            payroll:["paye","salaries","wages"], tax:["vat","paye","firs","taxation","filings"],
            excel:["spreadsheet","spreadsheets","microsoft excel"],
            audit:["auditing","audits","auditor"], budgeting:["budget","budgets","forecasting"],
            compliance:["regulatory","statutory","filings","firs","lirs"], invoicing:["invoices","billing","receivables"],
            nysc:["national youth service","corps member","youth service"],
            ican:["chartered accountant","aca","icaen"], acca:["chartered certified accountant"],
            qualification:["b.sc","bsc","hnd","ond","degree","certified"],
            payables:["payable","vendors","suppliers"], leadership:["led","managed","supervised","mentored"],
            communication:["stakeholder","presented","liaised"], analysis:["analysed","analyzed","analytical","insights"]
        };
        function pct(n, d) { return d ? Math.round(n / d * 100) : 0; }
        
        function semHit(kw, txt) {
            if (txt.indexOf(kw) > -1) return true;
            if (SYN[kw]) {
                for (var i = 0; i < SYN[kw].length; i++) {
                    if (txt.indexOf(SYN[kw][i]) > -1) return true;
                }
            }
            for (var base in SYN) {
                if (SYN[base].indexOf(kw) > -1 && (txt.indexOf(base) > -1 || SYN[base].some(s => txt.indexOf(s) > -1))) return true;
            }
            return false;
        }

        function metBar(label, val) {
            var cls = val === null ? "" : (val >= 70 ? "" : (val >= 45 ? " warn" : " bad"));
            return '<div class="met' + cls + '"><div class="met-h"><span>' + label + '</span><b>' + (val === null ? "—" : val + "%") + '</b></div>'
                + '<div class="met-t"><div class="met-f" style="width:' + (val || 0) + '%"></div></div></div>';
        }

        function refreshAts() {
            var name = $('input[name="full_name"]').val() || '';
            var email = $('input[name="email"]').val() || '';
            var phone = $('input[name="phone"]').val() || '';
            var locationStr = $('input[name="location"]').val() || '';
            var linkedin = $('input[name="linkedin"]').val() || '';
            var summary = $('#resume-summary').val() || '';
            var certsStr = $('textarea[name="certs"]').val() || '';
            var languagesStr = $('input[name="languages"]').val() || '';
            var skillsStr = $('input[name="skills"]').val() || '';
            
            var skills = skillsStr.split(',').map(s => s.trim()).filter(Boolean);
            
            var experiences = [];
            $('.experience-item').each(function() {
                experiences.push({
                    role: $(this).find('input[name="exp_position[]"]').val() || '',
                    company: $(this).find('input[name="exp_company[]"]').val() || '',
                    start_date: $(this).find('input[name="exp_start_date[]"]').val() || '',
                    end_date: $(this).find('input[name="exp_end_date[]"]').val() || '',
                    is_current: $(this).find('.exp-current-check').is(':checked'),
                    bullets: $(this).find('textarea[name="exp_description[]"]').val() || ''
                });
            });

            var education = [];
            $('.education-item').each(function() {
                education.push({
                    school: $(this).find('input[name="edu_school[]"]').val() || '',
                    degree: $(this).find('select[name="edu_degree[]"]').val() || '',
                    field: $(this).find('input[name="edu_field[]"]').val() || '',
                    year: $(this).find('input[name="edu_year[]"]').val() || ''
                });
            });

            var allB = experiences.map(x => x.bullets).join('\n');
            var bullets = allB.split('\n').map(s => s.trim()).filter(Boolean);
            var txt = (summary + ' ' + allB + ' ' + skills.join(' ') + ' ' + certsStr).toLowerCase();

            // Perform 17 checklist audits
            var checks = [];

            // 1. Contact details complete
            var okContact = !!(name && email && phone && locationStr);
            checks.push({ ok: okContact, pts: 15, label: "Contact details complete", section: "info" });

            // 2. Summary length (35-120 words)
            var sw = summary.trim().split(/\s+/).filter(Boolean).length;
            checks.push({ ok: sw >= 35 && sw <= 120, pts: 15, label: "Summary is 35–120 words (" + sw + ")", section: "summary" });

            // 3. Achievements include numbers
            var hasNumbers = /\d/.test(allB);
            checks.push({ ok: hasNumbers, pts: 15, label: "Achievements include numbers", section: "experience" });

            // 4. 5+ achievement bullets
            checks.push({ ok: bullets.length >= 5, pts: 10, label: "5+ achievement bullets listed", section: "experience" });

            // 5. 6+ skills listed
            checks.push({ ok: skills.length >= 6, pts: 15, label: "6+ skills listed", section: "skills" });

            // 6. Education included
            var hasEdu = education.some(e => e.school.trim());
            checks.push({ ok: hasEdu, pts: 10, label: "Education included", section: "education" });

            // 7. Certifications included
            checks.push({ ok: certsStr.trim().length > 0, pts: 10, label: "Certifications included", section: "skills" });

            // 8. Substantive content length
            var totalContentWords = (summary + ' ' + allB).trim().split(/\s+/).filter(Boolean).length;
            checks.push({ ok: totalContentWords >= 120, pts: 10, label: "Enough content to rank (>120 words)", section: "experience" });

            // Calculate ATS Score
            var atsScoreValue = checks.reduce((a, c) => a + (c.ok ? c.pts : 0), 0);

            // 9. Missing LinkedIn URL
            checks.push({ ok: linkedin.trim().length > 0, pts: 0, label: "LinkedIn profile link added", section: "info" });

            // 10. Missing Location
            checks.push({ ok: locationStr.trim().length > 0, pts: 0, label: "Location city added", section: "info" });

            // 11. Overlong summary
            checks.push({ ok: sw <= 60, pts: 0, label: "Summary is concise (<=60 words)", section: "summary" });

            // 12. Filler words
            var FILLER = ["very","really","various","several","successfully","effectively","in order to","a number of","responsible for"];
            var fhit = FILLER.filter(f => txt.indexOf(f) > -1);
            checks.push({ ok: fhit.length === 0, pts: 0, label: "No generic filler words used", section: "experience" });

            // 13. Buzzword overload
            var BUZZ = ["synergy","leverage","spearheaded","utilize","utilized","facilitate","streamline"];
            var bz = BUZZ.filter(w => txt.indexOf(w) > -1);
            checks.push({ ok: bz.length < 2, pts: 0, label: "No corporate buzzword overload", section: "experience" });

            // 14. Consistent Date Format (Always consistent since forms enforce date picker)
            checks.push({ ok: true, pts: 0, label: "Consistent date formats", section: "experience" });

            // 15. Capitalization of bullets
            var lowerStart = bullets.filter(x => { var c=x.trim()[0]; return c && c===c.toLowerCase() && c!==c.toUpperCase(); }).length;
            checks.push({ ok: lowerStart === 0, pts: 0, label: "All bullets capitalized", section: "experience" });

            // 16. Punctuation consistency
            var withDot = bullets.filter(x => /[.]$/.test(x.trim())).length;
            var okPunct = (bullets.length < 3 || withDot === 0 || withDot === bullets.length);
            checks.push({ ok: okPunct, pts: 0, label: "Consistent bullet punctuation", section: "experience" });

            // 17. Current role tense consistency
            var okTense = true;
            if (experiences.length >= 2) {
                var cur0 = experiences[0];
                var isCurrent = cur0.is_current || /present|current/i.test(cur0.end_date);
                var pastVerbs = /(ed|led|built|ran|made|kept)\b/i;
                if (isCurrent && cur0.bullets.trim() && cur0.bullets.split('\n').filter(Boolean).every(l => pastVerbs.test(l.trim().split(/\s+/)[0] || ""))) {
                    okTense = false;
                }
            }
            checks.push({ ok: okTense, pts: 0, label: "Current role uses present tense", section: "experience" });

            // Calculate Intel sub-metrics
            var cl = CLICHES.filter(c => txt.indexOf(c) > -1);
            var leads = {};
            var rep = [];
            bullets.forEach(x => { var v = x.toLowerCase().split(/\s+/)[0]; leads[v] = (leads[v] || 0) + 1; });
            for (var v in leads) {
                if (leads[v] >= 3) rep.push(v);
            }
            var human = Math.max(0, 100 - cl.length * 18 - rep.length * 12);

            var av = bullets.filter(x => STRONG_VERBS.indexOf(x.toLowerCase().split(/\s+/)[0]) > -1);
            var verbs = pct(av.length, bullets.length);

            var imp = bullets.filter(x => { var l = x.toLowerCase(); return /\d/.test(x) || IMPACT_WORDS.some(w => l.indexOf(w) > -1); });
            var impact = pct(imp.length, bullets.length);

            var rd = bullets.filter(x => { var w = x.split(/\s+/).length; return w >= 4 && w <= 24; });
            var read = pct(rd.length, bullets.length);

            var cover = null;
            var jd = $('#jd').length ? $('#jd').val().trim() : '';
            if (jd.length >= 60) {
                var kws = keywords(jd);
                cover = pct(kws.filter(k => semHit(k, txt)).length, kws.length);
            }

            var parts = [atsScoreValue, human, verbs, impact, read];
            if (cover !== null) parts.push(cover);
            var recruiterScore = Math.round(parts.reduce((a, x) => a + x, 0) / parts.length);

            // Update gauges & checklist UI
            $('#ats-num').text(atsScoreValue);
            var g = $('#gauge-p');
            if (g.length) {
                g.css('stroke-dashoffset', 207 * (1 - atsScoreValue / 100));
                g.css('stroke', atsScoreValue >= 75 ? 'var(--success)' : (atsScoreValue >= 50 ? 'var(--accent)' : 'var(--danger)'));
            }

            // Render 6 metrics grid
            $('#met-grid').html(
                metBar("Recruiter Score", recruiterScore) +
                metBar("Human Writing", human) +
                metBar("Impact", impact) +
                metBar("Action Verbs", verbs) +
                metBar("Readability", read) +
                (cover !== null ? metBar("Keyword Coverage", cover) : "")
            );

            // Render Checklist items
            var listHtml = '';
            checks.forEach(function(c) {
                var icon = c.ok ? 'ti-circle-check-filled text-success' : 'ti-circle text-muted';
                var btn = c.ok ? '' : '<button type="button" class="fix-ats btn-link text-decoration-none border-0 bg-transparent text-primary ms-auto" data-target="' + c.section + '" style="font-size: 0.74rem; font-weight:600;">Fix</button>';
                listHtml += '<li class="' + (c.ok ? 'ok' : 'no') + ' d-flex align-items-center mb-2" style="font-size: 0.8rem;"><i class="ti ' + icon + ' me-2 fs-5"></i><span>' + c.label + '</span>' + btn + '</li>';
            });
            $('#ats-list').html(listHtml);

        }

        // Delegated listener for ATS fix buttons
        $(document).on('click', '.fix-ats', function() {
            var target = $(this).data('target');
            openEdSec(target);
        });

        // Input change listeners
        $(document).on('input change keyup', '#resume-form input, #resume-form textarea, #resume-form select', function() {
            renderLivePreview();
            refreshAts();
        });

        function resumeTextForMatch() {
            var summary = $('#resume-summary').val() || '';
            var skills = ($('input[name="skills"]').val() || '').split(',').map(s => s.trim()).filter(Boolean);
            var certs = $('textarea[name="certs"]').val() || '';
            var allBullets = [];
            $('textarea[name="exp_description[]"]').each(function() { allBullets.push($(this).val() || ''); });
            return (summary + ' ' + allBullets.join(' ') + ' ' + skills.join(' ') + ' ' + certs).toLowerCase();
        }

        function runMatch() {
            var jd = $('#jd').val().trim();
            var matchWrap = $('#match-wrap');
            if (jd.length < 60) { matchWrap.addClass('d-none'); refreshAts(); return; }
            matchWrap.removeClass('d-none');
            var kws = keywords(jd);
            var rt = resumeTextForMatch();
            var hit = kws.filter(k => semHit(k, rt));
            var score = kws.length ? Math.round(hit.length / kws.length * 100) : 0;
            $('#match-num').text(score + '%');
            $('#match-p').css('stroke-dashoffset', 207 * (1 - score / 100));
            var miss = kws.filter(k => !semHit(k, rt)).slice(0, 8);
            $('#kw-chips').html(miss.length
                ? miss.map(k => '<button class="btn btn-sm rounded-pill kw-chip" data-kw="' + k + '" style="border:1.5px solid var(--primary,#0861a9);color:var(--primary,#0861a9);background:#f0f6ff;font-size:.74rem;padding:3px 12px;transition:all .2s;">+ ' + k + '</button>').join('')
                : '<span class="text-success fw-semibold" style="font-size:.78rem;">Great coverage — no obvious keyword gaps.</span>');
            // keyword chip click-to-add
            $('.kw-chip').off('click').on('click', function() {
                var kw = $(this).data('kw');
                var pretty = kw.replace(/\b\w/g, c => c.toUpperCase());
                var skillsInput = $('input[name="skills"]');
                var current = skillsInput.val().split(',').map(s => s.trim()).filter(Boolean);
                if (current.indexOf(pretty) === -1) {
                    current.push(pretty);
                    skillsInput.val(current.join(', '));
                }
                $(this).css({background:'#d1fae5', borderColor:'#10b981', color:'#065f46'}).text('✓ ' + pretty).prop('disabled', true);
                renderLivePreview(); refreshAts(); runMatch();
            });
            // also re-run refreshAts so cover metric updates
            refreshAts();
        }

        // JD textarea auto-run match
        $('#jd').on('input', function() {
            clearTimeout(window._jdTimer);
            window._jdTimer = setTimeout(runMatch, 400);
        });

        // Job picker pre-fill JD
        $('#job-pick').on('change', function() {
            var val = $(this).val();
            var desc = $(this).find('option:selected').data('desc');
            if (val && desc) {
                $('#jd').val(desc);
                runMatch();
            } else {
                $('#jd').val('');
                $('#match-wrap').addClass('d-none');
            }
        });

        // AI Tailor Resume button
        $(document).on('click', '#btn-ai-tailor', function() {
            var jd = $('#jd').val().trim();
            if (!jd) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning('Please paste a job description or select a job first.');
                } else {
                    alert('Please paste a job description or select a job first.');
                }
                $('#jd').focus();
                return;
            }

            var $btn = $(this);
            var origHtml = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Tailoring with AI...');

            // Build structured resume JSON
            var resumeData = {
                id: $('input[name="id"]').val() || null,
                title: $('input[name="title"]').val() || '',
                full_name: $('input[name="full_name"]').val() || '',
                email: $('input[name="email"]').val() || '',
                phone: $('input[name="phone"]').val() || '',
                location: $('input[name="location"]').val() || '',
                summary: $('#resume-summary').val() || '',
                template_id: $('#template-select-top').val() || $('#template_id').val() || 't-modern',
                experiences: [],
                education: [],
                skills: $('input[name="skills"]').val() || '',
                linkedin: $('input[name="linkedin"]').val() || '',
                certs: $('textarea[name="certs"]').val() || '',
                languages: $('input[name="languages"]').val() || ''
            };

            $('.experience-item').each(function() {
                resumeData.experiences.push({
                    company: $(this).find('input[name="exp_company[]"]').val() || '',
                    position: $(this).find('input[name="exp_position[]"]').val() || '',
                    description: $(this).find('textarea[name="exp_description[]"]').val() || '',
                    start_date: $(this).find('input[name="exp_start_date[]"]').val() || '',
                    end_date: $(this).find('input[name="exp_end_date[]"]').val() || '',
                    is_current: $(this).find('.exp-current-check').is(':checked') ? 1 : 0
                });
            });

            $('.education-item').each(function() {
                resumeData.education.push({
                    institution: $(this).find('input[name="edu_school[]"]').val() || '',
                    degree: $(this).find('select[name="edu_degree[]"]').val() || '',
                    field_of_study: $(this).find('input[name="edu_field[]"]').val() || '',
                    graduation_year: $(this).find('input[name="edu_year[]"]').val() || ''
                });
            });

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/tailor-resume") ?>',
                type: 'POST',
                data: {
                    resume_json: JSON.stringify(resumeData),
                    job_description: jd,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(res) {
                    $btn.prop('disabled', false).html(origHtml);
                    if (res && res.status === 'success' && res.tailored) {
                        var snap = res.tailored;
                        if (typeof snap === 'string') {
                            try { snap = JSON.parse(snap); } catch(e) {}
                        }

                        if (snap.title !== undefined) $('input[name="title"]').val(snap.title).trigger('input');
                        if (snap.full_name !== undefined && snap.full_name) $('input[name="full_name"]').val(snap.full_name).trigger('input');
                        if (snap.summary !== undefined) $('#resume-summary').val(snap.summary).trigger('input');
                        if (snap.skills !== undefined) {
                            var skillsVal = Array.isArray(snap.skills) ? snap.skills.join(', ') : snap.skills;
                            $('input[name="skills"]').val(skillsVal).trigger('input');
                        }
                        if (snap.certs !== undefined) $('textarea[name="certs"]').val(snap.certs).trigger('input');

                        // Update or rebuild experiences if provided in tailored response
                        if (Array.isArray(snap.experiences) && snap.experiences.length > 0) {
                            var $expItems = $('.experience-item');
                            if ($expItems.length === snap.experiences.length) {
                                snap.experiences.forEach(function(e, i) {
                                    var $row = $expItems.eq(i);
                                    if (e.position) $row.find('input[name="exp_position[]"]').val(e.position).trigger('input');
                                    if (e.company) $row.find('input[name="exp_company[]"]').val(e.company).trigger('input');
                                    var desc = Array.isArray(e.description) ? e.description.join("\n") : (e.description || '');
                                    $row.find('textarea[name="exp_description[]"]').val(desc).trigger('input');
                                });
                            } else {
                                var $expContainer = $('#experience-container');
                                $expContainer.find('.experience-item').remove();
                                $expContainer.find('.no-items').remove();
                                snap.experiences.forEach(function(e, idx) {
                                    var desc = Array.isArray(e.description) ? e.description.join("\n") : (e.description || '');
                                    var html = `
                                        <div class="xp-entry position-relative experience-item">
                                            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                            <div class="row2">
                                                <div>
                                                    <label class="lbl">Role</label>
                                                    <input type="text" name="exp_position[]" class="input" placeholder="Job Position" value="${escapeHtml(e.position || '')}">
                                                </div>
                                                <div>
                                                    <label class="lbl">Dates (Start - End)</label>
                                                    <div style="display:flex; gap:6px; align-items:center;">
                                                        <input type="date" name="exp_start_date[]" class="input" value="${escapeHtml(e.start_date || '')}" style="padding-left:4px; padding-right:4px;">
                                                        <span class="exp-end-date-col" style="${e.is_current ? 'display: none;' : ''}">-</span>
                                                        <input type="date" name="exp_end_date[]" class="input exp-end-date-col" value="${escapeHtml(e.end_date || '')}" style="${e.is_current ? 'display: none;' : ''} padding-left:4px; padding-right:4px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                                <div style="flex:1;">
                                                    <label class="lbl">Company</label>
                                                    <input type="text" name="exp_company[]" class="input" placeholder="Company Name" value="${escapeHtml(e.company || '')}">
                                                </div>
                                                <div class="form-check" style="margin-left:15px; margin-top:20px;">
                                                    <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="${idx}" ${e.is_current ? 'checked' : ''} id="exp_current_${idx}">
                                                    <label class="form-check-label lbl" for="exp_current_${idx}" style="cursor:pointer; display:inline-block; margin-left:4px; margin-bottom:0;">Current</label>
                                                </div>
                                            </div>
                                            <label class="lbl">Achievements — one per line</label>
                                            <textarea name="exp_description[]" class="input" rows="4" placeholder="Describe your responsibilities and achievements...">${escapeHtml(desc)}</textarea>
                                            <div class="ai-row">
                                                <button type="button" class="btn-ai improve-desc-ai"><svg aria-hidden="true"><use href="#i-zap"/></svg> Strengthen achievements</button>
                                                <button type="button" class="btn-ai generate-bullets-ai"><svg aria-hidden="true"><use href="#i-edit"/></svg> Generate Bullets</button>
                                            </div>
                                        </div>
                                    `;
                                    $expContainer.append(html);
                                });
                            }
                        }

                        if (typeof renderLivePreview === 'function') renderLivePreview();
                        if (typeof refreshAts === 'function') refreshAts();
                        if (typeof runMatch === 'function') runMatch();

                        if (typeof toastr !== 'undefined') {
                            toastr.success('Resume tailored to the job description successfully!');
                        }
                    } else {
                        var msg = (res && res.message) ? res.message : 'Failed to tailor resume.';
                        if (typeof toastr !== 'undefined') toastr.error(msg);
                        else alert(msg);
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html(origHtml);
                    var msg = 'An error occurred while tailoring the resume. Please try again.';
                    if (typeof toastr !== 'undefined') toastr.error(msg);
                    else alert(msg);
                }
            });
        });

        // Template Selection mapping
        var tplMap = {
            'classic': 't-classic',
            'modern': 't-modern',
            'creative': 't-creative',
            'executive': 't-exec',
            'minimalist': 't-minimal'
        };
        var revTplMap = {
            't-classic': 'classic',
            't-modern': 'modern',
            't-creative': 'creative',
            't-exec': 'executive',
            't-minimal': 'minimalist'
        };

        // Template Selection changes
        $('#tpl-select').on('change', function() {
            var selected = $(this).val();
            var rawTpl = revTplMap[selected] || selected.replace('t-', '');
            $('#template_id').val(rawTpl);
            $('#template-select-top').val(selected);
            // trigger active template card highlight
            $('.template-choice').removeClass('active border-primary border-2 shadow-sm');
            $('.template-choice[data-template="' + rawTpl + '"]').addClass('active border-primary border-2 shadow-sm');
            renderLivePreview();
        });

        $('.template-choice').on('click', function() {
            var tpl = $(this).data('template');
            var selectVal = tplMap[tpl] || 't-' + tpl;
            $('#tpl-select').val(selectVal).trigger('change');
        });

        // Spacing Selection changes
        $('#spacing-roomy-btn, #spacing-tight-btn').on('click', function() {
            $('#spacing-roomy-btn, #spacing-tight-btn').removeClass('on');
            $(this).addClass('on');
            renderLivePreview();
        });

        // Trigger on load
        setTimeout(function() {
            var dbTpl = $('#template_id').val() || 'classic';
            var selectVal = tplMap[dbTpl] || 't-' + dbTpl;
            $('#tpl-select').val(selectVal).trigger('change');
            renderLivePreview();
            refreshAts();
        }, 300);

        // Step Navigation (accordion-aware)
        $('.step-item').on('click', function() {
            const step = $(this).data('step');
            $('.step-item').removeClass('active');
            $(this).addClass('active');
            // Open the target accordion section
            openEdSec(step);
            // On mobile, scroll to the form so the user sees it
            if (window.innerWidth < 992) {
                const formEl = $('#resume-form');
                if (formEl.length && formEl.is(':visible') && formEl.offset()) {
                    const formOffset = formEl.offset().top - 80;
                    window.scrollTo({ top: formOffset, behavior: 'smooth' });
                }
            }
        });

        // Next/Prev Buttons Navigation
        $(document).on('click', '.next-step, .prev-step', function(e) {
            e.preventDefault();
            const target = $(this).data('step-target');
            
            if (target === 'finish') {
                // Focus on download buttons or trigger save
                $('#save-resume-btn').trigger('click');
                const saveBtn = $('#save-resume-btn');
                if (saveBtn.length && saveBtn.is(':visible') && saveBtn.offset()) {
                    const formOffset = saveBtn.offset().top - 150;
                    window.scrollTo({ top: formOffset, behavior: 'smooth' });
                }
                return;
            }
            
            // Direct state update
            $('.step-item').removeClass('active');
            $('.step-item[data-step="' + target + '"]').addClass('active');

            $('.step-content').addClass('d-none');
            $('#step-' + target).removeClass('d-none');

            // Scroll to form to avoid showing the top sidebar again on mobile
            const formEl = $('#resume-form');
            if (formEl.length && formEl.is(':visible') && formEl.offset()) {
                const formOffset = formEl.offset().top - 80;
                window.scrollTo({ top: formOffset, behavior: 'smooth' });
            }
        });

        // Click Event for Template Choice
        $(document).on('click', '.template-choice', function() {
            $('.template-choice').removeClass('active');
            $(this).addClass('active');
            $('#template_id').val($(this).data('template'));
        });

        // AI Summary Generation
        $('#generate-summary-ai').on('click', function() {
            const btn = $(this);
            const currentSummary = $('#resume-summary').val() || '';
            const experiences = [];
            const education = [];
            const skills = $('input[name="skills"]').val() || '';

            lastFocusedTextarea = $('#resume-summary');

            // Extract experience details from inputs to build rich prompt
            $('.experience-item, .xp-entry').each(function() {
                const company = $(this).find('input[name="exp_company[]"]').val();
                const position = $(this).find('input[name="exp_position[]"]').val();
                const desc = $(this).find('textarea[name="exp_description[]"]').val();
                if (company || position || desc) {
                    experiences.push({ company, position, description: desc });
                }
            });

            // Collect education entries
            $('.education-item').each(function() {
                const school = $(this).find('input[name="edu_school[]"]').val();
                const degree = $(this).find('select[name="edu_degree[]"], input[name="edu_degree[]"]').val();
                const field = $(this).find('input[name="edu_field[]"]').val();
                if (school || degree || field) {
                    education.push({ school, degree, field });
                }
            });

            btn.prop('disabled', true);
            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/generate-summary") ?>',
                type: 'POST',
                data: {
                    current_summary: currentSummary,
                    experiences: experiences,
                    education: education,
                    skills: skills,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.summary) {
                        // Show preview modal with sanitized HTML (server already sanitized)
                        $('#aiPreviewRender').html(response.summary.replace(/\n/g, '<br>'));
                        $('#aiPreviewModal').modal('show');
                        // store raw in the preview container for apply action
                        $('#aiPreviewRender').data('raw', response.summary);
                    } else {
                        toastr.error('AI returned no content.');
                    }
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                },
                error: function() {
                    toastr.error('AI generation failed. Please try again.');
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                }
            });
        });

        // Add Experience Item (Dynamic)
        $('.add-experience').on('click', function() {
            $('#experience-container .no-items').hide();
            
            // Generate next available index for exp_current value tracking
            const count = $('.experience-item').length;
            
            const html = `
                <div class="xp-entry position-relative experience-item" style="display: none;">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                    <div class="row2">
                        <div>
                            <label class="lbl">Role</label>
                            <input type="text" name="exp_position[]" class="input" placeholder="Job Position">
                        </div>
                        <div>
                            <label class="lbl">Dates (Start - End)</label>
                            <div style="display:flex; gap:6px; align-items:center;">
                                <input type="date" name="exp_start_date[]" class="input" style="padding-left:4px; padding-right:4px;">
                                <span class="exp-end-date-col">-</span>
                                <input type="date" name="exp_end_date[]" class="input exp-end-date-col" style="padding-left:4px; padding-right:4px;">
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="flex:1;">
                            <label class="lbl">Company</label>
                            <input type="text" name="exp_company[]" class="input" placeholder="Company Name">
                        </div>
                        <div class="form-check" style="margin-left:15px; margin-top:20px;">
                            <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="${count}" id="exp_current_new_${count}">
                            <label class="form-check-label lbl" for="exp_current_new_${count}" style="cursor:pointer; display:inline-block; margin-left:4px; margin-bottom:0;">Current</label>
                        </div>
                    </div>
                    <label class="lbl">Achievements — one per line</label>
                    <textarea name="exp_description[]" class="input" rows="4" placeholder="Describe your responsibilities and achievements..."></textarea>
                    <div class="ai-row">
                        <button type="button" class="btn-ai improve-desc-ai"><svg aria-hidden="true"><use href="#i-zap"/></svg> Strengthen achievements</button>
                        <button type="button" class="btn-ai generate-bullets-ai"><svg aria-hidden="true"><use href="#i-edit"/></svg> Generate Bullets</button>
                    </div>
                </div>
            `;
            
            const $newItem = $(html);
            $('#experience-container').append($newItem);
            $newItem.slideDown(200);
        });

        // Add Education Item (Dynamic)
        $('.add-education').on('click', function() {
            $('#education-container .no-items').hide();
            
            const html = `
                <div class="xp-entry position-relative education-item" style="display: none;">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                    <div class="row2">
                        <div>
                            <label class="lbl">School / University</label>
                            <input type="text" name="edu_school[]" class="input" placeholder="School / University">
                        </div>
                        <div>
                            <label class="lbl">Degree</label>
                            <select name="edu_degree[]" class="input select">
                                <option value="">Select Degree / Qualification</option>
                                <option value="B.Sc.">B.Sc. / Bachelor of Science</option>
                                <option value="B.A.">B.A. / Bachelor of Arts</option>
                                <option value="B.Eng.">B.Eng. / B.Tech. (Engineering &amp; Tech)</option>
                                <option value="LL.B">LL.B / Law Degree</option>
                                <option value="MBBS">MBBS / Medicine &amp; Surgery</option>
                                <option value="HND">HND / Higher National Diploma</option>
                                <option value="OND / ND">OND / ND (National Diploma)</option>
                                <option value="NCE">NCE / Nigeria Certificate in Education</option>
                                <option value="M.Sc.">M.Sc. / M.A. (Master's Degree)</option>
                                <option value="MBA">MBA / Master of Business Admin</option>
                                <option value="PhD">Ph.D. / Doctorate</option>
                                <option value="PGD">PGD / Postgraduate Diploma</option>
                                <option value="SSCE / WAEC">SSCE / WAEC / NECO</option>
                                <option value="Associate">Associate Degree</option>
                                <option value="Certificate">Professional Certificate</option>
                                <option value="Other">Other Qualification</option>
                            </select>
                        </div>
                    </div>
                    <div class="row2" style="margin-top: 10px;">
                        <div>
                            <label class="lbl">Field of Study</label>
                            <input type="text" name="edu_field[]" class="input" placeholder="Field of Study">
                        </div>
                        <div>
                            <label class="lbl">Graduation Year</label>
                            <input type="text" name="edu_year[]" class="input" placeholder="YYYY">
                        </div>
                    </div>
                </div>
            `;
            
            const $newItem = $(html);
            $('#education-container').append($newItem);
            $newItem.slideDown(200);
        });

        // Dynamic deletion handler for items
        $(document).on('click', '.remove-item-btn', function() {
            const $item = $(this).closest('.experience-item, .education-item');
            const $container = $item.parent();
            
            $item.fadeOut(250, function() {
                $item.remove();
                if ($container.find('.experience-item, .education-item').length === 0) {
                    $container.find('.no-items').fadeIn(200);
                }
                // Re-index exp_current[] values so they still match each row's position —
                // save() matches checked values against the submitted exp_company[] array index.
                $('#experience-container .exp-current-check').each(function(idx) {
                    $(this).val(idx);
                });
                if (typeof renderLivePreview === 'function') {
                    renderLivePreview();
                }
                if (typeof refreshAts === 'function') {
                    refreshAts();
                }
            });
        });

        // Dynamic change handler for 'Currently Work Here' checkbox
        $(document).on('change', '.exp-current-check', function() {
            const $endDateCol = $(this).closest('.experience-item').find('.exp-end-date-col');
            if ($(this).is(':checked')) {
                $endDateCol.slideUp(200).find('input').val('');
            } else {
                $endDateCol.slideDown(200);
            }
        });

        // Improve Description with AI
        $(document).on('click', '.improve-desc-ai', function() {
            const btn = $(this);
            const textarea = btn.closest('.xp-entry, .experience-item').find('textarea[name="exp_description[]"]');
            if (textarea.length) {
                lastFocusedTextarea = textarea;
            }
            const description = textarea.length ? textarea.val() : '';

            if (!description || !description.trim()) {
                toastr.warning('Please enter a description first.');
                return;
            }

            btn.prop('disabled', true);
            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/improve-description") ?>',
                type: 'POST',
                data: {
                    description: description,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.description) {
                        // Show preview modal with suggested bullets or description
                        $('#aiPreviewRender').html(response.description.replace(/\n/g, '<br>'));
                        $('#aiPreviewRender').data('raw', response.description);
                        $('#aiPreviewModal').modal('show');
                    }
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                },
                error: function() {
                    toastr.error('AI improvement failed.');
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                }
            });
        });

        // Generate bullets for experience
        $(document).on('click', '.generate-bullets-ai', function() {
            const btn = $(this);
            const textarea = btn.closest('.xp-entry, .experience-item').find('textarea[name="exp_description[]"]');
            if (textarea.length) {
                lastFocusedTextarea = textarea;
            }
            const description = textarea.length ? textarea.val() : '';
            const position = btn.closest('.experience-item').find('input[name="exp_position[]"]').val() || '';

            if (!description || !description.trim()) {
                toastr.warning('Please enter an experience description first.');
                return;
            }

            btn.prop('disabled', true);
            $('#aiLoaderModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/generate-bullets") ?>',
                type: 'POST',
                data: {
                    description: description,
                    job_title: position,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.bullets) {
                        // Show preview modal with bullets
                        // convert newlines to <li> list for better UX
                        const bulletsHtml = response.bullets.split(/\r?\n/).filter(Boolean).map(b => '<li>' + escapeHtml(b.trim()) + '</li>').join('');
                        const html = '<div class="ai-card"><h3>Suggested Bullets</h3><ul>' + bulletsHtml + '</ul></div>';
                        $('#aiPreviewRender').html(html);
                        $('#aiPreviewRender').data('raw', response.bullets);
                        $('#aiPreviewModal').modal('show');
                    }
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                },
                error: function() {
                    toastr.error('Failed to generate bullets.');
                    $('#aiLoaderModal').modal('hide');
                    btn.prop('disabled', false);
                }
            });
        });

        // Save Resume
        $('#save-resume-btn').on('click', function() {
            // Clear temporary undo data on save
            $('#resume-summary').removeData('prev');
            $('textarea[name="exp_description[]"]').each(function() { $(this).removeData('prev'); });

            // Dynamically set checkbox values to their actual array index prior to serialization
            $('.experience-item').each(function(index) {
                $(this).find('.exp-current-check').val(index);
            });

            const formData = $('#resume-form').serialize();
            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner spinner-sm"></span> Saving...');

            $.ajax({
                url: '<?= site_url("candidate/resumes/save") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success('Resume saved successfully!');
                    if (response.id) {
                        $('input[name="id"]').val(response.id);
                    }
                    // If this save was kicked off by a restore+save, create a snapshot autosave for history
                    if (window.restoreSavePending) {
                        try {
                            // Convert to structured snapshot similar to doAutosave
                            const snapshot = { experiences: [], education: [] };
                            snapshot.id = $('input[name="id"]').val() || null;
                            snapshot.title = $('input[name="title"]').val() || '';
                            snapshot.summary = $('#resume-summary').val() || '';
                            snapshot.template_id = $('#template_id').val() || 'classic';
                            snapshot.skills = $('input[name="skills"]').val() || '';
                            snapshot.linkedin = $('input[name="linkedin"]').val() || '';
                            snapshot.certs = $('textarea[name="certs"]').val() || '';
                            snapshot.languages = $('input[name="languages"]').val() || '';

                            $('.experience-item').each(function() {
                                snapshot.experiences.push({
                                    company: $(this).find('input[name="exp_company[]"]').val() || '',
                                    position: $(this).find('input[name="exp_position[]"]').val() || '',
                                    description: $(this).find('textarea[name="exp_description[]"]').val() || '',
                                    start_date: $(this).find('input[name="exp_start_date[]"]').val() || '',
                                    end_date: $(this).find('input[name="exp_end_date[]"]').val() || '',
                                    is_current: $(this).find('.exp-current-check').is(':checked') ? 1 : 0
                                });
                            });

                            $('.education-item').each(function() {
                                snapshot.education.push({
                                    institution: $(this).find('input[name="edu_school[]"]').val() || '',
                                    degree: $(this).find('select[name="edu_degree[]"]').val() || '',
                                    field_of_study: $(this).find('input[name="edu_field[]"]').val() || '',
                                    graduation_year: $(this).find('input[name="edu_year[]"]').val() || ''
                                });
                            });

                            $.ajax({
                                url: '<?= site_url("candidate/resumes/autosave") ?>',
                                type: 'POST',
                                data: { snapshot: JSON.stringify(snapshot), id: snapshot.id, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' }
                            });
                        } catch (e) {
                            // ignore autosave snapshot errors
                        }
                        window.restoreSavePending = false;
                        // Visual confirmation for restore+save
                        toastr.success('Revision restored and saved successfully.');
                    }
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>Save Resume');
                },
                error: function() {
                    toastr.error('Failed to save resume.');
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i>Save Resume');
                }
            });
        });

        // Autosave: debounce per-field and periodic full autosave
        let autosaveTimer = null;
        let debounceTimers = new Map();
        const AUTOSAVE_INTERVAL = 30000; // 30s
        const FIELD_DEBOUNCE = 500; // 500ms

        function scheduleAutosave() {
            if (autosaveTimer) clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(doAutosave, AUTOSAVE_INTERVAL);
        }

        function doAutosave() {
            const form = $('#resume-form');
            // Build structured snapshot JSON from current form state
            const snapshot = {
                id: $('input[name="id"]').val() || null,
                title: $('input[name="title"]').val() || '',
                summary: $('#resume-summary').val() || '',
                template_id: $('#template_id').val() || 'classic',
                experiences: [],
                education: [],
                skills: $('input[name="skills"]').val() || '',
                linkedin: $('input[name="linkedin"]').val() || '',
                certs: $('textarea[name="certs"]').val() || '',
                languages: $('input[name="languages"]').val() || ''
            };

            $('.experience-item').each(function() {
                snapshot.experiences.push({
                    company: $(this).find('input[name="exp_company[]"]').val() || '',
                    position: $(this).find('input[name="exp_position[]"]').val() || '',
                    description: $(this).find('textarea[name="exp_description[]"]').val() || '',
                    start_date: $(this).find('input[name="exp_start_date[]"]').val() || '',
                    end_date: $(this).find('input[name="exp_end_date[]"]').val() || '',
                    is_current: $(this).find('.exp-current-check').is(':checked') ? 1 : 0
                });
            });

            $('.education-item').each(function() {
                snapshot.education.push({
                    institution: $(this).find('input[name="edu_school[]"]').val() || '',
                    degree: $(this).find('select[name="edu_degree[]"]').val() || '',
                    field_of_study: $(this).find('input[name="edu_field[]"]').val() || '',
                    graduation_year: $(this).find('input[name="edu_year[]"]').val() || ''
                });
            });

            $.ajax({
                url: '<?= site_url("candidate/resumes/autosave") ?>',
                type: 'POST',
                data: {
                    snapshot: JSON.stringify(snapshot),
                    id: $('input[name="id"]').val(),
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(resp) {
                    if (resp.id) {
                        $('input[name="id"]').val(resp.id);
                    }
                    const ts = new Date().toLocaleTimeString();
                    $('#autosave-indicator').remove();
                    
                    const header = $('.page-title').length ? $('.page-title') : $('.page-header');
                    if (header.length) {
                        header.append('<span id="autosave-indicator" class="text-muted ms-3" style="font-size:12px;">Autosaved at ' + ts + '</span>');
                    }
                }
            });
        }

        // Track per-field changes
        $(document).on('input change', '#resume-form input, #resume-form textarea, #resume-form select', function() {
            const el = this;
            // Generate a unique reference using index suffix to avoid debounce key collisions in arrays
            const name = $(el).attr('name') || '';
            let key = el; // default to element DOM reference
            if (name.includes('[]')) {
                const index = $('[name="' + name + '"]').index(el);
                key = name + '_' + index;
            } else if ($(el).attr('id')) {
                key = $(el).attr('id');
            }
            if (debounceTimers.has(key)) clearTimeout(debounceTimers.get(key));
            debounceTimers.set(key, setTimeout(function() {
                scheduleAutosave();
                debounceTimers.delete(key);
            }, FIELD_DEBOUNCE));
        });

        // Also autosave on page unload
        $(window).on('beforeunload', function() {
            // synchronous navigator sendBeacon unavailable for form data; attempt quick ajax
            navigator.sendBeacon && navigator.sendBeacon('<?= site_url("candidate/resumes/autosave") ?>', new URLSearchParams({
                id: $('input[name="id"]').val() || '',
                payload: $('#resume-form').serialize() || ''
            }));
        });

        // Utility escapeHtml is defined earlier; ensure it's available for template building

        // Revision History UI: open modal and load recent autosaves
        $('#open-revisions-btn').on('click', function() {
            const resumeId = $('input[name="id"]').val();
            if (!resumeId) {
                toastr.info('Please save your resume once to enable revisions.');
                return;
            }

            $('#revisions-list').html('<div class="text-muted">Loading revisions...</div>');
            $('#revisionsModal').modal('show');

            $.ajax({
                url: '<?= site_url("candidate/resumes/") ?>' + resumeId + '/autosaves',
                type: 'GET',
                success: function(resp) {
                    if (!resp.autosaves || resp.autosaves.length === 0) {
                        $('#revisions-list').html('<div class="text-muted">No revisions found.</div>');
                        return;
                    }

                    const items = resp.autosaves.map(function(a) {
                        const created = new Date(a.created_at).toLocaleString();
                        const summ = a.preview && a.preview.summary ? a.preview.summary : '';
                        const exps = a.preview && a.preview.experiences ? a.preview.experiences.map(e => (e.position || '') + (e.company ? ' at ' + e.company : '')).join('; ') : '';
                        const previewHtml = '<div class="fw-semibold">' + created + '</div>' +
                            (summ ? '<div class="text-muted small mt-1">' + escapeHtml(summ) + '</div>' : '') +
                            (exps ? '<div class="text-muted small mt-1"><strong>Experiences:</strong> ' + escapeHtml(exps) + '</div>' : '');

                        return `<div class="revision-item border rounded p-2 mb-2 d-flex justify-content-between align-items-start">
                            <div style="max-width: 75%;">
                                ${previewHtml}
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary restore-autosave-btn" data-id="${a.id}">Restore</button>
                                <button class="btn btn-sm btn-primary restore-save-autosave-btn" data-id="${a.id}">Restore & Save</button>
                            </div>
                        </div>`;
                    }).join('');

                    $('#revisions-list').html(items);
                },
                error: function() {
                    $('#revisions-list').html('<div class="text-danger">Failed to load revisions.</div>');
                }
            });
        });

        // Restore autosave from revisions modal (structured snapshot restore)
        $(document).on('click', '.restore-autosave-btn', function() {
            const autosaveId = $(this).data('id');
            const resumeId = $('input[name="id"]').val();
            if (!resumeId) return;

            const btn = $(this);
            btn.prop('disabled', true).text('Restoring...');

            $.ajax({
                url: '<?= site_url("candidate/resumes/") ?>' + resumeId + '/restore-autosave',
                type: 'POST',
                data: {
                    autosave_id: autosaveId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(resp) {
                    if (resp.payload) {
                        // load structured JSON snapshot into form and reconstruct repeated groups
                        const snap = resp.payload;
                        if (snap.title !== undefined) $('input[name="title"]').val(snap.title);
                        if (snap.summary !== undefined) $('#resume-summary').val(snap.summary);
                        if (snap.template_id !== undefined) $('#template_id').val(snap.template_id);
                        if (snap.skills !== undefined) $('input[name="skills"]').val(snap.skills);
                        if (snap.linkedin !== undefined) $('input[name="linkedin"]').val(snap.linkedin);
                        if (snap.certs !== undefined) $('textarea[name="certs"]').val(snap.certs);
                        if (snap.languages !== undefined) $('input[name="languages"]').val(snap.languages);

                        // Rebuild experiences section
                        const $expContainer = $('#experience-container');
                        $expContainer.find('.experience-item').remove();
                        if (Array.isArray(snap.experiences)) {
                            snap.experiences.forEach(function(e, idx) {
                                const html = `
                                    <div class="xp-entry position-relative experience-item">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                        <div class="row2">
                                            <div>
                                                <label class="lbl">Role</label>
                                                <input type="text" name="exp_position[]" class="input" placeholder="Job Position" value="${escapeHtml(e.position || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Dates (Start - End)</label>
                                                <div style="display:flex; gap:6px; align-items:center;">
                                                    <input type="date" name="exp_start_date[]" class="input" value="${escapeHtml(e.start_date || '')}" style="padding-left:4px; padding-right:4px;">
                                                    <span class="exp-end-date-col" style="${e.is_current ? 'display: none;' : ''}">-</span>
                                                    <input type="date" name="exp_end_date[]" class="input exp-end-date-col" value="${escapeHtml(e.end_date || '')}" style="${e.is_current ? 'display: none;' : ''} padding-left:4px; padding-right:4px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display:flex; justify-content:space-between; align-items:center;">
                                            <div style="flex:1;">
                                                <label class="lbl">Company</label>
                                                <input type="text" name="exp_company[]" class="input" placeholder="Company Name" value="${escapeHtml(e.company || '')}">
                                            </div>
                                            <div class="form-check" style="margin-left:15px; margin-top:20px;">
                                                <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="${idx}" ${e.is_current ? 'checked' : ''} id="exp_current_${idx}">
                                                <label class="form-check-label lbl" for="exp_current_${idx}" style="cursor:pointer; display:inline-block; margin-left:4px; margin-bottom:0;">Current</label>
                                            </div>
                                        </div>
                                        <label class="lbl">Achievements — one per line</label>
                                        <textarea name="exp_description[]" class="input" rows="4" placeholder="Describe your responsibilities and achievements...">${escapeHtml(e.description || '')}</textarea>
                                        <div class="ai-row">
                                            <button type="button" class="btn-ai improve-desc-ai"><svg aria-hidden="true"><use href="#i-zap"/></svg> Strengthen achievements</button>
                                            <button type="button" class="btn-ai generate-bullets-ai"><svg aria-hidden="true"><use href="#i-edit"/></svg> Generate Bullets</button>
                                        </div>
                                    </div>
                                `;
                                $expContainer.append(html);
                            });
                        }

                        // Rebuild education section
                        const $eduContainer = $('#education-container');
                        $eduContainer.find('.education-item').remove();
                        if (Array.isArray(snap.education)) {
                            snap.education.forEach(function(ed) {
                                const html = `
                                    <div class="xp-entry position-relative education-item">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                        <div class="row2">
                                            <div>
                                                <label class="lbl">School / University</label>
                                                <input type="text" name="edu_school[]" class="input" placeholder="School / University" value="${escapeHtml(ed.institution || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Degree</label>
                                                <select name="edu_degree[]" class="input select">
                                                    <option value="">Select Degree / Qualification</option>
                                                    <option value="B.Sc." ${ed.degree === 'B.Sc.' || ed.degree === 'Bachelor' ? 'selected' : ''}>B.Sc. / Bachelor of Science</option>
                                                    <option value="B.A." ${ed.degree === 'B.A.' ? 'selected' : ''}>B.A. / Bachelor of Arts</option>
                                                    <option value="B.Eng." ${ed.degree === 'B.Eng.' || ed.degree === 'B.Tech.' ? 'selected' : ''}>B.Eng. / B.Tech. (Engineering &amp; Tech)</option>
                                                    <option value="LL.B" ${ed.degree === 'LL.B' ? 'selected' : ''}>LL.B / Law Degree</option>
                                                    <option value="MBBS" ${ed.degree === 'MBBS' ? 'selected' : ''}>MBBS / Medicine &amp; Surgery</option>
                                                    <option value="HND" ${ed.degree === 'HND' ? 'selected' : ''}>HND / Higher National Diploma</option>
                                                    <option value="OND / ND" ${ed.degree === 'OND / ND' || ed.degree === 'OND' ? 'selected' : ''}>OND / ND (National Diploma)</option>
                                                    <option value="NCE" ${ed.degree === 'NCE' ? 'selected' : ''}>NCE / Nigeria Certificate in Education</option>
                                                    <option value="M.Sc." ${ed.degree === 'M.Sc.' || ed.degree === 'Master' ? 'selected' : ''}>M.Sc. / M.A. (Master's Degree)</option>
                                                    <option value="MBA" ${ed.degree === 'MBA' ? 'selected' : ''}>MBA / Master of Business Admin</option>
                                                    <option value="PhD" ${ed.degree === 'PhD' || ed.degree === 'Ph.D.' ? 'selected' : ''}>Ph.D. / Doctorate</option>
                                                    <option value="PGD" ${ed.degree === 'PGD' ? 'selected' : ''}>PGD / Postgraduate Diploma</option>
                                                    <option value="SSCE / WAEC" ${ed.degree === 'SSCE / WAEC' || ed.degree === 'High School' ? 'selected' : ''}>SSCE / WAEC / NECO</option>
                                                    <option value="Associate" ${ed.degree === 'Associate' ? 'selected' : ''}>Associate Degree</option>
                                                    <option value="Certificate" ${ed.degree === 'Certificate' ? 'selected' : ''}>Professional Certificate</option>
                                                    <option value="Other" ${ed.degree === 'Other' ? 'selected' : ''}>Other Qualification</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row2" style="margin-top: 10px;">
                                            <div>
                                                <label class="lbl">Field of Study</label>
                                                <input type="text" name="edu_field[]" class="input" placeholder="Field of Study" value="${escapeHtml(ed.field_of_study || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Graduation Year</label>
                                                <input type="text" name="edu_year[]" class="input" placeholder="YYYY" value="${escapeHtml(ed.graduation_year || '')}">
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $eduContainer.append(html);
                            });
                        }

                        toastr.success('Revision restored into the form. Please review changes and Save to persist.');
                        $('#revisionsModal').modal('hide');
                    } else {
                        toastr.error('Invalid autosave payload');
                    }
                },
                error: function() {
                    toastr.error('Failed to restore revision.');
                    btn.prop('disabled', false).text('Restore');
                }
            });
        });

        // Restore & Save action
        $(document).on('click', '.restore-save-autosave-btn', function() {
            const autosaveId = $(this).data('id');
            const resumeId = $('input[name="id"]').val();
            if (!resumeId) return;

            const btn = $(this);
            btn.prop('disabled', true).text('Restoring...');

            $.ajax({
                url: '<?= site_url("candidate/resumes/") ?>' + resumeId + '/restore-autosave',
                type: 'POST',
                data: {
                    autosave_id: autosaveId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(resp) {
                    if (resp.payload) {
                        const snap = resp.payload;
                        if (snap.title !== undefined) $('input[name="title"]').val(snap.title);
                        if (snap.summary !== undefined) $('#resume-summary').val(snap.summary);
                        if (snap.template_id !== undefined) $('#template_id').val(snap.template_id);
                        if (snap.skills !== undefined) $('input[name="skills"]').val(snap.skills);

                        // Rebuild experiences and education same as restore
                        const $expContainer = $('#experience-container');
                        $expContainer.find('.experience-item').remove();
                        if (Array.isArray(snap.experiences)) {
                            snap.experiences.forEach(function(e, idx) {
                                const html = `
                                    <div class="xp-entry position-relative experience-item">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                        <div class="row2">
                                            <div>
                                                <label class="lbl">Role</label>
                                                <input type="text" name="exp_position[]" class="input" placeholder="Job Position" value="${escapeHtml(e.position || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Dates (Start - End)</label>
                                                <div style="display:flex; gap:6px; align-items:center;">
                                                    <input type="date" name="exp_start_date[]" class="input" value="${escapeHtml(e.start_date || '')}" style="padding-left:4px; padding-right:4px;">
                                                    <span class="exp-end-date-col" style="${e.is_current ? 'display: none;' : ''}">-</span>
                                                    <input type="date" name="exp_end_date[]" class="input exp-end-date-col" value="${escapeHtml(e.end_date || '')}" style="${e.is_current ? 'display: none;' : ''} padding-left:4px; padding-right:4px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display:flex; justify-content:space-between; align-items:center;">
                                            <div style="flex:1;">
                                                <label class="lbl">Company</label>
                                                <input type="text" name="exp_company[]" class="input" placeholder="Company Name" value="${escapeHtml(e.company || '')}">
                                            </div>
                                            <div class="form-check" style="margin-left:15px; margin-top:20px;">
                                                <input class="form-check-input exp-current-check" type="checkbox" name="exp_current[]" value="${idx}" ${e.is_current ? 'checked' : ''} id="exp_current_${idx}">
                                                <label class="form-check-label lbl" for="exp_current_${idx}" style="cursor:pointer; display:inline-block; margin-left:4px; margin-bottom:0;">Current</label>
                                            </div>
                                        </div>
                                        <label class="lbl">Achievements — one per line</label>
                                        <textarea name="exp_description[]" class="input" rows="4" placeholder="Describe your responsibilities and achievements...">${escapeHtml(e.description || '')}</textarea>
                                        <div class="ai-row">
                                            <button type="button" class="btn-ai improve-desc-ai"><svg aria-hidden="true"><use href="#i-zap"/></svg> Strengthen achievements</button>
                                            <button type="button" class="btn-ai generate-bullets-ai"><svg aria-hidden="true"><use href="#i-edit"/></svg> Generate Bullets</button>
                                        </div>
                                    </div>
                                `;
                                $expContainer.append(html);
                            });
                        }

                        const $eduContainer = $('#education-container');
                        $eduContainer.find('.education-item').remove();
                        if (Array.isArray(snap.education)) {
                            snap.education.forEach(function(ed) {
                                const html = `
                                    <div class="xp-entry position-relative education-item">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-item-btn" style="font-size: 0.7rem; opacity: 0.6; z-index: 10;"></button>
                                        <div class="row2">
                                            <div>
                                                <label class="lbl">School / University</label>
                                                <input type="text" name="edu_school[]" class="input" placeholder="School / University" value="${escapeHtml(ed.institution || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Degree</label>
                                                <select name="edu_degree[]" class="input select">
                                                    <option value="">Select Degree / Qualification</option>
                                                    <option value="B.Sc." ${ed.degree === 'B.Sc.' || ed.degree === 'Bachelor' ? 'selected' : ''}>B.Sc. / Bachelor of Science</option>
                                                    <option value="B.A." ${ed.degree === 'B.A.' ? 'selected' : ''}>B.A. / Bachelor of Arts</option>
                                                    <option value="B.Eng." ${ed.degree === 'B.Eng.' || ed.degree === 'B.Tech.' ? 'selected' : ''}>B.Eng. / B.Tech. (Engineering &amp; Tech)</option>
                                                    <option value="LL.B" ${ed.degree === 'LL.B' ? 'selected' : ''}>LL.B / Law Degree</option>
                                                    <option value="MBBS" ${ed.degree === 'MBBS' ? 'selected' : ''}>MBBS / Medicine &amp; Surgery</option>
                                                    <option value="HND" ${ed.degree === 'HND' ? 'selected' : ''}>HND / Higher National Diploma</option>
                                                    <option value="OND / ND" ${ed.degree === 'OND / ND' || ed.degree === 'OND' ? 'selected' : ''}>OND / ND (National Diploma)</option>
                                                    <option value="NCE" ${ed.degree === 'NCE' ? 'selected' : ''}>NCE / Nigeria Certificate in Education</option>
                                                    <option value="M.Sc." ${ed.degree === 'M.Sc.' || ed.degree === 'Master' ? 'selected' : ''}>M.Sc. / M.A. (Master's Degree)</option>
                                                    <option value="MBA" ${ed.degree === 'MBA' ? 'selected' : ''}>MBA / Master of Business Admin</option>
                                                    <option value="PhD" ${ed.degree === 'PhD' || ed.degree === 'Ph.D.' ? 'selected' : ''}>Ph.D. / Doctorate</option>
                                                    <option value="PGD" ${ed.degree === 'PGD' ? 'selected' : ''}>PGD / Postgraduate Diploma</option>
                                                    <option value="SSCE / WAEC" ${ed.degree === 'SSCE / WAEC' || ed.degree === 'High School' ? 'selected' : ''}>SSCE / WAEC / NECO</option>
                                                    <option value="Associate" ${ed.degree === 'Associate' ? 'selected' : ''}>Associate Degree</option>
                                                    <option value="Certificate" ${ed.degree === 'Certificate' ? 'selected' : ''}>Professional Certificate</option>
                                                    <option value="Other" ${ed.degree === 'Other' ? 'selected' : ''}>Other Qualification</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row2" style="margin-top: 10px;">
                                            <div>
                                                <label class="lbl">Field of Study</label>
                                                <input type="text" name="edu_field[]" class="input" placeholder="Field of Study" value="${escapeHtml(ed.field_of_study || '')}">
                                            </div>
                                            <div>
                                                <label class="lbl">Graduation Year</label>
                                                <input type="text" name="edu_year[]" class="input" placeholder="YYYY" value="${escapeHtml(ed.graduation_year || '')}">
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $eduContainer.append(html);
                            });
                        }

                        // After rebuilding, trigger save (and snapshot)
                        window.restoreSavePending = true;
                        $('#save-resume-btn').trigger('click');
                        $('#revisionsModal').modal('hide');
                    } else {
                        toastr.error('Invalid autosave payload');
                        btn.prop('disabled', false).text('Restore & Save');
                    }
                },
                error: function() {
                    toastr.error('Failed to restore revision.');
                    btn.prop('disabled', false).text('Restore & Save');
                }
            });
        });

        // Helper: Save resume before download
        function saveResumeBeforeDownload(onSuccess) {
            $('.experience-item').each(function(index) {
                $(this).find('.exp-current-check').val(index);
            });

            const formData = $('#resume-form').serialize();

            $.ajax({
                url: '<?= site_url("candidate/resumes/save") ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.id) {
                        $('input[name="id"]').val(response.id);
                        onSuccess(response.id);
                    } else {
                        const existingId = $('input[name="id"]').val();
                        if (existingId) {
                            onSuccess(existingId);
                        } else {
                            toastr.error('Could not determine resume ID for download.');
                        }
                    }
                },
                error: function() {
                    toastr.error('Failed to save latest changes. Trying to download anyway...');
                    const existingId = $('input[name="id"]').val();
                    if (existingId) {
                        onSuccess(existingId);
                    } else {
                        toastr.error('Please save your resume first.');
                    }
                }
            });
        }

        // PDF Download Click Handler
        $(document).on('click', '.download-pdf-btn', function() {
            const btn = $(this);
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<span class="spinner spinner-sm"></span> Preparing PDF...');
            
            saveResumeBeforeDownload(function(id) {
                btn.prop('disabled', false).html(originalHtml);
                window.location.href = '<?= site_url("candidate/resumes/download/") ?>' + id;
            });
        });

        // DOCX Download Click Handler
        $(document).on('click', '.download-docx-btn', function() {
            const btn = $(this);
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<span class="spinner spinner-sm"></span> Preparing Word...');
            
            saveResumeBeforeDownload(function(id) {
                btn.prop('disabled', false).html(originalHtml);
                window.location.href = '<?= site_url("candidate/resumes/download-docx/") ?>' + id;
            });
        });

        // ==========================================
        // AI RESUME COACH DRAWER INTEGRATION
        // ==========================================
        let coachHistory = [];
        let lastFocusedTextarea = null;

        // Keep track of focused inputs in the builder form to paste content
        $(document).on('focus', '#resume-form textarea, #resume-form input[type="text"]', function() {
            lastFocusedTextarea = $(this);
        });

        // Toggle / show coach offcanvas event
        $('#aiResumeCoachDrawer').on('shown.bs.offcanvas', function () {
            if ($('#coach-chat-messages').children().length === 0) {
                // Seed initial message from AI Coach (plain text, no markdown)
                showCoachMessage('coach', 'Hello, I am ResumeAI, your resume consultant. To get started, what is your target role and industry?');
            }
        });

        // Submit message form
        $('#coach-chat-form').on('submit', function(e) {
            e.preventDefault();
            sendCoachMessage();
        });

        function sendCoachMessage() {
            const inputField = $('#coach-chat-input');
            const message = inputField.val().trim();
            if (!message) return;

            // Append user bubble
            showCoachMessage('user', message);
            inputField.val('');

            // Append typing indicator
            showTypingIndicator();

            // Send AJAX request
            $.ajax({
                url: '<?= site_url("candidate/resumes/ai/chat") ?>',
                type: 'POST',
                data: {
                    message: message,
                    history: JSON.stringify(coachHistory),
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    removeTypingIndicator();
                    if (response.reply) {
                        showCoachMessage('coach', response.reply);
                        // Save in local history array
                        coachHistory.push({sender: 'user', message: message});
                        coachHistory.push({sender: 'model', message: response.reply});
                    } else {
                        showCoachMessage('coach', 'I experienced an issue parsing the coaching response. Let\'s continue our session.');
                    }
                },
                error: function() {
                    removeTypingIndicator();
                    showCoachMessage('coach', 'Sorry, I am having trouble connecting right now. Let\'s continue.');
                }
            });
        }

        function showCoachMessage(sender, text) {
            const container = $('#coach-chat-messages');
            
            // Helper to escape HTML for user messages
            function escapeHtml(str) {
                return String(str).replace(/[&<>"']/g, function (s) {
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[s]);
                });
            }

            // For coach messages we accept simple HTML from the server (server sanitizes). For user messages escape HTML.
            let formattedText;
            if (sender === 'coach') {
                // preserve simple HTML returned by server; normalize line endings
                formattedText = String(text).replace(/\r/g, '');
            } else {
                formattedText = escapeHtml(text).replace(/\n\n/g, '<br><br>').replace(/\n/g, '<br>');
            }

            const bubbleId = 'bubble-' + Date.now();
            let html = `
                <div class="coach-bubble ${sender}" id="${bubbleId}">
                    <div>${formattedText}</div>
            `;

            // If coach, add action pasting toolbar helpers
            if (sender === 'coach') {
                html += `
                    <div class="mt-2 d-flex flex-wrap gap-1 border-top border-secondary border-opacity-10 pt-2">
                        <button type="button" class="coach-apply-btn apply-to-summary-btn" data-text-id="${bubbleId}-text" style="font-size: 10px; padding: 3px 8px; border-radius: 12px;">
                            <i class="ti ti-blockquote me-1"></i> Apply to Summary
                        </button>
                        <button type="button" class="coach-apply-btn apply-to-active-btn" data-text-id="${bubbleId}-text" style="font-size: 10px; padding: 3px 8px; border-radius: 12px;">
                            <i class="ti ti-edit me-1"></i> Apply to Active Field
                        </button>
                    </div>
                `;
            }

            html += `</div>`;
            container.append(html);
            
            // Store raw text in a hidden element inside the bubble for precise extraction
            if (sender === 'coach') {
                $(`#${bubbleId}`).append(`<div id="${bubbleId}-text" style="display:none;"></div>`);
                // Use jQuery data to keep raw payload (may include HTML)
                $(`#${bubbleId}-text`).data('raw', text);
            }

            // Scroll chat to bottom
            const chatWindow = document.getElementById('coach-chat-window');
            if (chatWindow) {
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }
        }

        function showTypingIndicator() {
            removeTypingIndicator();
            const container = $('#coach-chat-messages');
            const html = `
                <div class="coach-bubble coach typing-indicator-bubble align-self-start" id="coach-typing-indicator" style="background-color: #1e293b; border: 1px solid #334155; border-top-left-radius: 4px; max-width: 85%;">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            `;
            container.append(html);
            const chatWindow = document.getElementById('coach-chat-window');
            if (chatWindow) {
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }
        }

        function removeTypingIndicator() {
            $('#coach-typing-indicator').remove();
        }

        // Apply to Professional Summary action handler
        $(document).on('click', '.apply-to-summary-btn', function() {
            const textId = $(this).data('text-id');
            const $hidden = $('#' + textId);
            const rawText = $hidden.length && $hidden.data('raw') ? $hidden.data('raw') : $hidden.text();
            const polishedText = extractResumeContent(rawText);

            // Store previous value for undo
            const prev = $('#resume-summary').val();
            $('#resume-summary').data('prev', prev);

            $('#resume-summary').val(polishedText).trigger('input');
            if (typeof renderLivePreview === 'function') {
                renderLivePreview();
            }
            toastr.success('Applied to Professional Summary!');
            
            // Scroll to the professional summary element
            const targetEl = $("#resume-summary");
            if (targetEl.length && targetEl.is(':visible') && targetEl.offset()) {
                $('html, body').animate({
                    scrollTop: targetEl.offset().top - 120
                }, 300);
            }
        });

        // Apply to Active/Last Focused text input or textarea
        $(document).on('click', '.apply-to-active-btn', function() {
            const textId = $(this).data('text-id');
            const $hidden = $('#' + textId);
            const rawText = $hidden.length && $hidden.data('raw') ? $hidden.data('raw') : $hidden.text();
            const polishedText = extractResumeContent(rawText);

            if (lastFocusedTextarea && lastFocusedTextarea.length > 0) {
                // store previous for undo
                lastFocusedTextarea.data('prev', lastFocusedTextarea.val());
                lastFocusedTextarea.val(polishedText).trigger('input');
                if (typeof renderLivePreview === 'function') {
                    renderLivePreview();
                }
                toastr.success('Applied to the active input field!');
                
                // Focus it and flash it
                lastFocusedTextarea.focus();
                lastFocusedTextarea.css('border-color', '#0d609e');
                setTimeout(function() {
                    lastFocusedTextarea.css('border-color', '');
                }, 1000);
            } else {
                // Fallback to first work experience description block
                const firstExpDesc = $('textarea[name="exp_description[]"]').first();
                if (firstExpDesc.length > 0) {
                    // store previous for undo
                    firstExpDesc.data('prev', firstExpDesc.val());
                    firstExpDesc.val(polishedText).trigger('input');
                    if (typeof renderLivePreview === 'function') {
                        renderLivePreview();
                    }
                    toastr.info('No active input was selected. Applied to first work experience description.');
                    
                    if (firstExpDesc.is(':visible') && firstExpDesc.offset()) {
                        $('html, body').animate({
                            scrollTop: firstExpDesc.offset().top - 120
                        }, 300);
                    }
                    firstExpDesc.focus();
                } else {
                    // Otherwise default to summary
                    const targetSummary = $('#resume-summary');
                    targetSummary.val(polishedText).trigger('input');
                    if (typeof renderLivePreview === 'function') {
                        renderLivePreview();
                    }
                    toastr.info('No active input was selected. Applied to Professional Summary.');
                    
                    if (targetSummary.is(':visible') && targetSummary.offset()) {
                        $('html, body').animate({
                            scrollTop: targetSummary.offset().top - 120
                        }, 300);
                    }
                }
            }
        });

        // Generic Apply Suggestion handler for any suggestion buttons
        $(document).on('click', '.apply-suggestion, .apply-suggestion-btn', function() {
            const textId = $(this).data('text-id');
            const targetSel = $(this).data('target');
            let content = '';
            if (textId) {
                const $hidden = $('#' + textId);
                content = $hidden.length && $hidden.data('raw') ? $hidden.data('raw') : $hidden.text();
            } else {
                content = $(this).data('suggestion') || $(this).closest('.suggestion-item, .coach-bubble').find('.suggestion-text, .coach-text').text() || '';
            }
            const polished = extractResumeContent(content);
            const $target = targetSel ? $(targetSel) : (lastFocusedTextarea && lastFocusedTextarea.length ? lastFocusedTextarea : $('#resume-summary'));
            if ($target.length) {
                $target.data('prev', $target.val());
                $target.val(polished).trigger('input');
                if (typeof renderLivePreview === 'function') {
                    renderLivePreview();
                }
                if (typeof toastr !== 'undefined') toastr.success('Applied suggestion!');
            }
        });

        // Utility: Extract and clean raw markdown, HTML tags or blockquoted suggestions inside AI messages
        function extractResumeContent(text) {
            if (!text || typeof text !== 'string') {
                if (text === null || text === undefined) return '';
                text = String(text);
            }
            let extracted = text;
            
            // 1. Extract content from code block if present
            const codeBlockRegex = /```(?:[a-zA-Z]+)?\n([\s\S]+?)\n```/;
            const codeMatch = text.match(codeBlockRegex);
            if (codeMatch && codeMatch[1]) {
                extracted = codeMatch[1];
            } else {
                // 2. Extract blockquote block if present
                const quoteRegex = /(?:^|\n)>\s*([\s\S]+?)(?:\n\n|\n$|$)/;
                const quoteMatch = text.match(quoteRegex);
                if (quoteMatch && quoteMatch[1]) {
                    extracted = quoteMatch[1];
                }
            }
            
            // Strip any remaining HTML tags and markdown markers for clean resume placement
            return extracted
                .replace(/<[^>]*>/g, '') // strip HTML tags
                .replace(/^>\s*/gm, '')  // remove leading blockquote carrots
                .replace(/[*#`]/g, '')   // strip asterisks, pound headers, and backticks
                .trim();
        }

        // AI Preview modal actions
        $('#aiApplyBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').text() || $('#aiPreviewRender').html() || '';
            const polished = extractResumeContent(raw);
            const target = $('#resume-summary');
            if (target.length) {
                target.data('prev', target.val());
                target.val(polished).trigger('input');
                if (typeof renderLivePreview === 'function') {
                    renderLivePreview();
                }
                // Scroll to summary section if hidden/collapsed
                const secSummary = $('#sec-summary');
                if (secSummary.length && !secSummary.hasClass('open')) {
                    secSummary.addClass('open');
                }
            }
            $('#aiPreviewModal').modal('hide');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('overflow', '');
            toastr.success('Applied AI content to Professional Summary');
        });

        $('#aiCopyPlainBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').text() || '';
            const plain = extractResumeContent(raw);
            navigator.clipboard.writeText(plain).then(function() {
                toastr.success('Copied plain text to clipboard');
            }, function() {
                toastr.info('Copy failed — you can manually copy from the preview.');
            });
        });

        // Apply preview content to last-focused input/textarea (or reasonable fallback)
        $('#aiApplyActiveBtn').on('click', function() {
            const raw = $('#aiPreviewRender').data('raw') || $('#aiPreviewRender').text() || '';
            const polished = extractResumeContent(raw);

            if (lastFocusedTextarea && lastFocusedTextarea.length > 0) {
                // store previous for undo
                lastFocusedTextarea.data('prev', lastFocusedTextarea.val());
                lastFocusedTextarea.val(polished).trigger('input');
                if (typeof renderLivePreview === 'function') {
                    renderLivePreview();
                }
                $('#aiPreviewModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
                toastr.success('Applied to the active input field!');
                lastFocusedTextarea.focus();
                lastFocusedTextarea.css('border-color', '#0d609e');
                setTimeout(function() { lastFocusedTextarea.css('border-color', ''); }, 1000);
                return;
            }

            // Fallback to first work experience description
            const firstExpDesc = $('textarea[name="exp_description[]"]').first();
            if (firstExpDesc.length > 0) {
                firstExpDesc.data('prev', firstExpDesc.val());
                firstExpDesc.val(polished).trigger('input');
                if (typeof renderLivePreview === 'function') {
                    renderLivePreview();
                }
                $('#aiPreviewModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '');
                toastr.info('No active input was selected. Applied to first work experience description.');
                if (firstExpDesc.is(':visible') && firstExpDesc.offset()) {
                    $('html, body').animate({ scrollTop: firstExpDesc.offset().top - 120 }, 300);
                }
                firstExpDesc.focus();
                return;
            }

            // Otherwise default to summary
            const targetSummary = $('#resume-summary');
            targetSummary.data('prev', targetSummary.val());
            targetSummary.val(polished).trigger('input');
            if (typeof renderLivePreview === 'function') {
                renderLivePreview();
            }
            $('#aiPreviewModal').modal('hide');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('overflow', '');
            toastr.info('No active input was selected. Applied to Professional Summary.');
            if (targetSummary.length && targetSummary.is(':visible') && targetSummary.offset()) {
                $('html, body').animate({ scrollTop: targetSummary.offset().top - 120 }, 300);
            }
        });

        // Undo last AI apply for summary or focused field
        $(document).on('click', '#undo-ai-apply', function() {
            const $summary = $('#resume-summary');
            const prev = $summary.data('prev');
            if (typeof prev !== 'undefined') {
                $summary.val(prev);
                $summary.removeData('prev');
                toastr.success('Undo applied');
                return;
            }

            if (lastFocusedTextarea && lastFocusedTextarea.length > 0) {
                const prevField = lastFocusedTextarea.data('prev');
                if (typeof prevField !== 'undefined') {
                    lastFocusedTextarea.val(prevField);
                    lastFocusedTextarea.removeData('prev');
                    toastr.success('Undo applied to active field');
                    return;
                }
            }

            toastr.info('Nothing to undo');
        });

    // ── PRINT ARCHITECTURE ──
    // beforeprint moves #doc out of the preview wrappers (which may have
    // transforms / overflow: hidden that clip the printed output).
    // afterprint restores the DOM to its live state.
    var _printRoot = document.createElement('div');
    _printRoot.id = 'print-root';
    _printRoot.style.display = 'none';
    document.body.appendChild(_printRoot);
    var _docHome = null;

    function _toPrintRoot() {
        var doc = document.getElementById('doc');
        if (doc && doc.parentElement !== _printRoot) {
            _docHome = doc.parentElement;
            // Clone SVG defs containing symbol #jr-mark so watermark renders during print
            var markSymbol = document.getElementById('jr-mark');
            if (markSymbol) {
                var svgParent = markSymbol.closest('svg');
                if (svgParent && !_printRoot.querySelector('#print-svg-defs')) {
                    var clonedSvg = svgParent.cloneNode(true);
                    clonedSvg.id = 'print-svg-defs';
                    _printRoot.appendChild(clonedSvg);
                }
            }
            _printRoot.appendChild(doc);
        }
    }
    function _fromPrintRoot() {
        var doc = document.getElementById('doc');
        if (_docHome && doc && doc.parentElement === _printRoot) {
            _docHome.appendChild(doc);
            _docHome = null;
        }
        var printSvg = _printRoot.querySelector('#print-svg-defs');
        if (printSvg) {
            printSvg.remove();
        }
    }

    window.addEventListener('beforeprint', function () {
        _toPrintRoot();
        window._prevDocTitle = document.title;
        // Use candidate's full name as the print-dialog/PDF filename
        var nameInput = document.querySelector('input[name="full_name"]');
        if (nameInput && nameInput.value.trim()) {
            document.title = nameInput.value.trim() + ' — Resume';
        }
    });
    window.addEventListener('afterprint', function () {
        _fromPrintRoot();
        if (window._prevDocTitle) { document.title = window._prevDocTitle; }
    });

    // Download-as-PDF shortcut via browser print dialog
    $(document).on('click', '.btn-print-pdf', function () {
        if (typeof toastr !== 'undefined') {
            toastr.info(
                'In the print dialog: set <b>Destination → Save as PDF</b>, ' +
                'open <b>More settings</b> and untick <b>Headers and footers</b>.',
                'Saving as PDF', { timeOut: 6000, extendedTimeOut: 2000 }
            );
        }
        window.print();
    });

    // ── 1-CLICK AUTO-FILL FROM CANDIDATE PROFILE ──
    window.performProfileAutofill = function($btn) {
        var origHtml = $btn ? $btn.html() : '';
        if ($btn) $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Filling...');

        $.ajax({
            url: '<?= base_url('candidate/resumes/profile-data') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success && res.data) {
                    var d = res.data;
                    if (d.full_name) $('input[name="full_name"]').val(d.full_name);
                    if (d.email) $('input[name="email"]').val(d.email);
                    if (d.phone) $('input[name="phone"]').val(d.phone);
                    if (d.location) $('input[name="location"]').val(d.location);
                    if (d.job_title && !$('input[name="title"]').val()) {
                        $('input[name="title"]').val(d.job_title + ' Resume');
                    }
                    if (d.bio) {
                        $('#resume-summary').val(d.bio);
                        $('textarea[name="summary"]').val(d.bio);
                    }

                    // Auto-fill skills
                    if (d.skills && d.skills.length > 0) {
                        $('textarea[name="skills"], input[name="skills"]').val(d.skills.join(', '));
                    }

                    // Auto-fill experiences if container is empty
                    if (d.experiences && d.experiences.length > 0) {
                        $('#experience-container').find('.experience-item').remove();
                        d.experiences.forEach(function(exp) {
                            if (typeof addExperienceItem === 'function') {
                                addExperienceItem(exp.company || '', exp.job_title || exp.position || '', exp.start_date || '', exp.end_date || '', exp.description || '', exp.is_current || false);
                            }
                        });
                    }

                    // Auto-fill education if container is empty
                    if (d.education && d.education.length > 0) {
                        $('#education-container').find('.education-item').remove();
                        d.education.forEach(function(edu) {
                            if (typeof addEducationItem === 'function') {
                                addEducationItem(edu.school || edu.institution || '', edu.degree || '', edu.field_of_study || edu.field || '', edu.end_year ? edu.end_year + '-12-31' : (edu.year || ''));
                            }
                        });
                    }

                    renderLivePreview();
                    refreshAts();
                    if (typeof toastr !== 'undefined') toastr.success('CV auto-filled with your profile info!');
                } else {
                    if (typeof toastr !== 'undefined') toastr.warning(res.message || 'Could not fetch profile info.');
                }
            },
            error: function() {
                if (typeof toastr !== 'undefined') toastr.error('Server error pulling profile data.');
            },
            complete: function() {
                if ($btn) $btn.prop('disabled', false).html(origHtml);
            }
        });
    };

    $(document).on('click', '#btn-autofill-profile, #btn-import-profile-top', function(e) {
        e.preventDefault();
        window.performProfileAutofill($(this));
    });
});
</script>
<?= $this->endSection() ?>

