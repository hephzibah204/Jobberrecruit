const { chromium } = require('playwright');
const path = require('path');
const fs   = require('fs');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const ctx     = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page    = await ctx.newPage();

  page.on('console', msg => console.log('BROWSER LOG:', msg.text()));
  page.on('pageerror', err => console.log('BROWSER ERROR:', err.message));

  // Login
  await page.goto('http://localhost:8080/login');
  await page.fill('input[type="email"], #email', 'candidate@test.com');
  await page.fill('input[type="password"], #password', 'Password123!');
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle' }),
    page.click('button[type="submit"]')
  ]);

  // Go to builder
  await page.goto('http://localhost:8080/candidate/resumes/build', { waitUntil: 'networkidle' });
  await page.waitForTimeout(1000);

  // Inspect layout elements
  const info = await page.evaluate(() => {
    const split = document.getElementById('rb-split');
    const ed    = document.getElementById('rb-editor-col');
    const pv    = document.getElementById('rb-preview-col');
    const doc   = document.getElementById('doc');
    return {
      splitClass: split ? split.className : null,
      splitWidth: split ? split.getBoundingClientRect().width : 0,
      edVisible:  ed ? window.getComputedStyle(ed).display : null,
      edWidth:    ed ? ed.getBoundingClientRect().width : 0,
      pvVisible:  pv ? window.getComputedStyle(pv).display : null,
      pvWidth:    pv ? pv.getBoundingClientRect().width : 0,
      docHtml:    doc ? doc.innerHTML.substring(0, 100) : null
    };
  });

  console.log('\n--- DIAGNOSTICS ---');
  console.log(JSON.stringify(info, null, 2));

  await page.screenshot({ path: path.join(__dirname, 'builder-debug.png') });
  await browser.close();
})();
