/**
 * Visual comparison — logs into the live app as a candidate,
 * screenshots the resume list + builder, then also screenshots
 * the mockup HTML for side-by-side diffing.
 *
 * Usage:
 *   node screenshots/visual-compare.js [candidate-email] [password]
 *
 * Defaults: reads CAND_EMAIL and CAND_PASS env vars.
 */
const { chromium } = require('playwright');
const path = require('path');
const fs   = require('fs');

const OUT       = path.resolve(__dirname);
const EMAIL     = process.argv[2] || process.env.CAND_EMAIL || '';
const PASSWORD  = process.argv[3] || process.env.CAND_PASS  || '';
const BASE      = 'http://localhost:8080';
const MOCKUP    = 'file:///C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/Doc/UI%20and%20UX%20Doc/jobberrecruit%20HTML%20Files/candidate-dashboard/candidate-AI%20Resume%20Builder.html';

(async () => {
  const browser = await chromium.launch({ headless: true });
  const ctx     = await browser.newContext({ viewport: { width: 1440, height: 900 } });

  // ════════════════════════════════════════════════════
  //  1. MOCKUP screenshots
  // ════════════════════════════════════════════════════
  console.log('\n── Mockup screenshots ──');
  const mp = await ctx.newPage();
  await mp.goto(MOCKUP, { waitUntil: 'networkidle' });
  await mp.waitForTimeout(600);

  // List view (default)
  await mp.screenshot({ path: path.join(OUT, 'mockup-list.png') });
  console.log('✅  mockup-list.png');

  // Builder view — click first card
  await mp.evaluate(() => {
    const vl = document.getElementById('view-list');
    const vb = document.getElementById('view-builder');
    if (vl) vl.classList.remove('on');
    if (vb) vb.classList.add('on');
    if (typeof resumes !== 'undefined' && typeof openBuilder === 'function') {
      openBuilder(resumes[0]);
    }
  });
  await mp.waitForTimeout(900);
  await mp.screenshot({ path: path.join(OUT, 'mockup-builder.png') });
  console.log('✅  mockup-builder.png');
  await mp.close();

  // ════════════════════════════════════════════════════
  //  2. LIVE app screenshots  (login required)
  // ════════════════════════════════════════════════════
  console.log('\n── Live app screenshots ──');
  const lp = await ctx.newPage();

  // ── Login ──────────────────────────────────────────
  await lp.goto(`${BASE}/login`, { waitUntil: 'networkidle' });

  if (EMAIL && PASSWORD) {
    // Fill email field
    const emailSel = lp.locator('input[type="email"], input[name="email"], #email').first();
    await emailSel.fill(EMAIL);

    // Fill password field
    const passSel = lp.locator('input[type="password"], input[name="password"], #password').first();
    await passSel.fill(PASSWORD);

    // Submit
    await Promise.all([
      lp.waitForNavigation({ waitUntil: 'networkidle', timeout: 20000 }),
      lp.locator('button[type="submit"], input[type="submit"]').first().click(),
    ]);
    console.log('   Logged in as:', EMAIL);
    console.log('   Current URL:', lp.url());
  } else {
    console.warn('⚠️  No credentials supplied — skipping login, trying direct URL access...');
  }

  // ── Resume list page ────────────────────────────────
  await lp.goto(`${BASE}/candidate/resumes`, { waitUntil: 'networkidle', timeout: 20000 });
  await lp.waitForTimeout(700);
  await lp.screenshot({ path: path.join(OUT, 'live-list.png') });
  console.log('✅  live-list.png  →', lp.url());

  // ── Builder page ────────────────────────────────────
  // Try to click "New Resume" or first Edit button, else go direct
  const editBtn = lp.locator('a.btn:has-text("Edit"), .btn-accent:has-text("New Resume")').first();
  if (await editBtn.count()) {
    await Promise.all([
      lp.waitForNavigation({ waitUntil: 'networkidle', timeout: 20000 }).catch(() => {}),
      editBtn.click(),
    ]);
  } else {
    await lp.goto(`${BASE}/candidate/resumes/build`, { waitUntil: 'networkidle', timeout: 20000 });
  }
  await lp.waitForTimeout(900);
  await lp.screenshot({ path: path.join(OUT, 'live-builder.png') });
  console.log('✅  live-builder.png →', lp.url());

  await lp.close();
  await browser.close();

  console.log('\nAll screenshots saved to:', OUT);
  console.log('\nFiles:');
  ['mockup-list.png','mockup-builder.png','live-list.png','live-builder.png'].forEach(f => {
    const fp = path.join(OUT, f);
    const ok = fs.existsSync(fp);
    console.log(' ', ok ? '✅' : '❌', f, ok ? `(${Math.round(fs.statSync(fp).size/1024)}KB)` : '');
  });
})();
