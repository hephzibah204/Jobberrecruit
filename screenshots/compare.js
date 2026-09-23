/**
 * Renders both the mockup HTML and a standalone static render of the
 * PHP index.php content (using the same CSS from the project), then
 * takes side-by-side screenshots for visual diffing.
 */
const { chromium } = require('playwright');
const path = require('path');
const fs   = require('fs');

const OUT     = path.resolve(__dirname);
const CSS_DIR = path.resolve(__dirname, '..', 'css');
const AUTH_CSS_DIR = path.resolve(__dirname, '..', 'auth', 'css');

// ── Read project CSS files ────────────────────────────────────────────────
function readCss(file, dir) {
  try { return fs.readFileSync(path.join(dir || CSS_DIR, file), 'utf8'); }
  catch { return ''; }
}

const shellCss       = readCss('employer-shell.css');
const globalCoreCss  = readCss('global-core.css');
const jobberCss      = readCss('jobber-recruit.css');

// ── Static render of the index.php list view (PHP already pre-rendered) ──
// We simulate two resume cards + the new card, same markup as index.php
const listHtml = /* html */`<!DOCTYPE html>
<html lang="en-NG">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>AI Resume Builder – Static Render</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ── Project global shell (employer-shell.css) ── */
${shellCss}
/* ── Global core ── */
${globalCoreCss}
/* ── Jobber recruit ── */
${jobberCss}

/* ── index.php inline styles (exactly as written in the view) ── */
.rz-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:clamp(12px,1.6vw,18px);margin-top:4px}
@media(max-width:1100px){.rz-grid{grid-template-columns:1fr 1fr}}
@media(max-width:640px){.rz-grid{grid-template-columns:1fr}}
.rz-card{display:flex;flex-direction:column;padding:18px;transition:.18s ease;cursor:pointer;border:1px solid var(--border,#e2e8f2);border-radius:14px;background:#fff;text-decoration:none}
.rz-card:hover{box-shadow:0 2px 14px rgba(10,47,87,.08);transform:translateY(-2px)}
.rz-top{display:flex;align-items:flex-start;gap:11px;margin-bottom:12px}
.rz-ic{width:42px;height:42px;border-radius:11px;background:var(--brand-light,#E6F0F8);color:var(--brand,#0861A9);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.rz-ic svg{width:18px;height:18px}
.rz-name{font-family:'Sora',sans-serif;font-weight:700;font-size:.92rem;color:var(--brand-deep,#0A2F57);line-height:1.35}
.rz-meta{font-size:.7rem;color:var(--muted,#5b6577);margin-top:2px}
.rz-score{margin-left:auto;flex-shrink:0;font-family:'Sora',sans-serif;font-weight:800;font-size:.78rem;padding:5px 11px;border-radius:20px}
.rz-score.hi{background:#e8f7ee;color:#16a34a}
.rz-score.mid{background:#FDF1E0;color:#C8770E}
.rz-acts{display:flex;gap:7px;margin-top:auto;padding-top:12px;border-top:1px solid var(--border,#e2e8f2)}
.rz-new{border-style:dashed;border-width:1.5px;border-color:var(--border,#e2e8f2);align-items:center;justify-content:center;text-align:center;gap:10px;min-height:170px;flex-direction:column}
.rz-new .rz-ic{width:52px;height:52px}
.rz-mini{width:46px;height:60px;border-radius:5px;background:#fff;border:1px solid var(--border,#e2e8f2);box-shadow:0 2px 14px rgba(10,47,87,.08);padding:6px 5px;display:flex;flex-direction:column;gap:3px;flex-shrink:0}
.rz-mini i{display:block;height:3px;border-radius:2px;background:var(--border,#e2e8f2)}
.rz-mini .a{width:70%;height:5px;background:var(--mini-acc,#0861A9)}
.rz-mini .b{width:45%;background:var(--mini-acc,#0861A9);opacity:.85}
.rz-mini .w80{width:80%}
.notice{display:flex;gap:9px;align-items:flex-start;font-size:.78rem;border-radius:10px;padding:12px 14px;border:1px solid}
.notice svg{width:15px;height:15px;flex-shrink:0;margin-top:2px}
.notice--info{background:#E6F0F8;border-color:#cfe2f2;color:#064A85}
.ic-btn{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:8px;border:1.5px solid var(--border,#e2e8f2);background:#fff;color:var(--muted,#5b6577);cursor:pointer;transition:.18s ease;flex-shrink:0}
.ic-btn svg{width:15px;height:15px}
/* wrap content like the shell does */
body{margin:0;background:#f5f7fb;font-family:'Inter',sans-serif;font-size:15px}
.preview-wrap{max-width:1100px;margin:40px auto;padding:0 24px}
.page-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;gap:12px;flex-wrap:wrap}
.page-head h1{font-family:'Sora',sans-serif;font-size:1.5rem;font-weight:800;color:#0A2F57;margin:0;display:flex;align-items:center;gap:10px}
.page-head p{color:#5b6577;font-size:.85rem;margin:4px 0 0}
.page-actions{display:flex;gap:8px;align-items:center;margin-top:4px}
.btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:9px;font-weight:600;font-size:.85rem;border:none;cursor:pointer;text-decoration:none;transition:.18s ease}
.btn-accent{background:#ED9020;color:#fff}
.btn-outline{background:#fff;border:1.5px solid #e2e8f2;color:#0A2F57}
.btn-sm{padding:6px 13px;font-size:.78rem}
</style>

<!-- SVG Sprite -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
  <symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></symbol>
  <symbol id="i-plus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></symbol>
  <symbol id="i-zap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></symbol>
  <symbol id="i-edit" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></symbol>
  <symbol id="i-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></symbol>
  <symbol id="i-trash" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></symbol>
</defs></svg>
</head>
<body>
<div class="preview-wrap">

  <!-- Page Header -->
  <div class="page-head">
    <div>
      <h1>
        <svg aria-hidden="true" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2"><use href="#i-doc"/></svg>
        AI Resume Builder
      </h1>
      <p>Built from your profile — tailored to every job. No retyping.</p>
    </div>
    <div class="page-actions">
      <a href="#" class="btn btn-accent">
        <svg aria-hidden="true" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2.2"><use href="#i-plus"/></svg>
        New Resume
      </a>
    </div>
  </div>

  <!-- Notice -->
  <div class="notice notice--info" role="note" style="margin-bottom:20px">
    <svg aria-hidden="true"><use href="#i-zap"/></svg>
    <span>Your <a href="#" style="font-weight:600;text-decoration:underline;">profile</a> is the single source of truth — resumes start pre-filled from it. Edits here never change your profile.</span>
  </div>

  <!-- Grid -->
  <div class="rz-grid">

    <!-- Card 1 — Executive, score 60 mid -->
    <section class="card rz-card" tabindex="0" role="button" aria-label="Open My Professional Resume">
      <div class="rz-top">
        <div class="rz-mini" aria-hidden="true" style="--mini-acc:#0A2F57">
          <i class="a"></i><i class="w80"></i><i></i><i class="b w80"></i><i></i><i class="w80"></i><i></i>
        </div>
        <div style="min-width:0;flex:1">
          <div class="rz-name">Accountant — General</div>
          <div class="rz-meta">Edited today · Executive</div>
        </div>
        <span class="rz-score mid" title="ATS readiness score">60</span>
      </div>
      <div class="rz-acts">
        <a href="#" class="btn btn-outline btn-sm">
          <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2"><use href="#i-edit"/></svg>
          Edit
        </a>
        <button class="ic-btn" title="Duplicate"><svg aria-hidden="true"><use href="#i-copy"/></svg></button>
        <button class="ic-btn" title="Delete" style="color:#dc2626;border-color:#fecaca"><svg aria-hidden="true"><use href="#i-trash"/></svg></button>
      </div>
    </section>

    <!-- Card 2 — Elegant Serif, score 75 hi -->
    <section class="card rz-card" tabindex="0" role="button" aria-label="Open Senior Accountant Banking">
      <div class="rz-top">
        <div class="rz-mini" aria-hidden="true" style="--mini-acc:#0A2F57">
          <i class="a"></i><i class="w80"></i><i></i><i class="b w80"></i><i></i><i class="w80"></i><i></i>
        </div>
        <div style="min-width:0;flex:1">
          <div class="rz-name">Senior Accountant — Banking</div>
          <div class="rz-meta">Edited 2 days ago · Elegant Serif</div>
        </div>
        <span class="rz-score hi" title="ATS readiness score">75</span>
      </div>
      <div class="rz-acts">
        <a href="#" class="btn btn-outline btn-sm">
          <svg aria-hidden="true" style="width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2"><use href="#i-edit"/></svg>
          Edit
        </a>
        <button class="ic-btn" title="Duplicate"><svg aria-hidden="true"><use href="#i-copy"/></svg></button>
        <button class="ic-btn" title="Delete" style="color:#dc2626;border-color:#fecaca"><svg aria-hidden="true"><use href="#i-trash"/></svg></button>
      </div>
    </section>

    <!-- New Card (dashed) -->
    <a href="#" class="rz-card rz-new card" aria-label="Create new resume from profile">
      <span class="rz-ic" aria-hidden="true">
        <svg style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2.2"><use href="#i-plus"/></svg>
      </span>
      <div>
        <b style="font-family:'Sora',sans-serif;color:#0A2F57">New resume from profile</b>
        <p style="font-size:.74rem;color:#5b6577;margin-top:3px">Pre-filled from your profile in one click.</p>
      </div>
    </a>

  </div>
</div>
</body></html>`;

fs.writeFileSync(path.join(OUT, 'php-list-render.html'), listHtml, 'utf8');
console.log('HTML render written');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const ctx     = await browser.newContext({ viewport: { width: 1440, height: 900 } });

  // ── Mockup list view ──────────────────────────────────────────────────
  const p1 = await ctx.newPage();
  const mockupUrl = 'file:///C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/Doc/UI%20and%20UX%20Doc/jobberrecruit%20HTML%20Files/candidate-dashboard/candidate-AI%20Resume%20Builder.html';
  await p1.goto(mockupUrl, { waitUntil: 'networkidle' });
  // Ensure list view is shown (it is by default)
  await p1.waitForTimeout(800);
  // Crop to just the main content area (excluding sidebar)
  await p1.screenshot({ path: path.join(OUT, 'mockup-list.png'), fullPage: false });
  console.log('✅  mockup-list.png');
  await p1.close();

  // ── PHP static render ─────────────────────────────────────────────────
  const p2 = await ctx.newPage();
  await p2.goto('file:///' + path.join(OUT, 'php-list-render.html').replace(/\\/g,'/'), { waitUntil: 'networkidle' });
  await p2.waitForTimeout(800);
  await p2.screenshot({ path: path.join(OUT, 'php-list-render.png'), fullPage: false });
  console.log('✅  php-list-render.png');
  await p2.close();

  // ── Mockup builder view ───────────────────────────────────────────────
  const p3 = await ctx.newPage();
  await p3.goto(mockupUrl, { waitUntil: 'networkidle' });
  await p3.evaluate(() => {
    document.getElementById('view-list')?.classList.remove('on');
    document.getElementById('view-builder')?.classList.add('on');
    if (typeof resumes !== 'undefined' && typeof openBuilder === 'function') {
      openBuilder(resumes[0]);
    }
  });
  await p3.waitForTimeout(900);
  await p3.screenshot({ path: path.join(OUT, 'mockup-builder.png'), fullPage: false });
  console.log('✅  mockup-builder.png');
  await p3.close();

  await browser.close();

  console.log('\nAll screenshots saved to:', OUT);
})();
