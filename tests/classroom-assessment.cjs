const fs = require('node:fs');
const { chromium } = require('playwright');

// Offline behavioral check of the real classroom submission script.
(async () => {
  const source = fs.readFileSync('app/Views/candidate/classroom.php', 'utf8');
  const start = source.indexOf('  // ── Final Assessment Gate Logic');
  const end = source.indexOf('  // Continue learning link trigger', start);
  if (start < 0 || end < 0) throw new Error('Classroom assessment script not found');
  const script = source.slice(start, end).replace(/<\?[\s\S]*?\?>/g, 'audit');
  const browser = await chromium.launch({ headless: true, channel: 'chrome' });
  try {
    const page = await browser.newPage();
    page.setDefaultTimeout(5000);
    const ids = [...new Set([...script.matchAll(/getElementById\("([^"]+)"\)/g)].map(m => m[1]))];
    await page.setContent(ids.filter(id => id !== 'assess-body').map(id =>
      `<div id="${id}"><svg><use/></svg></div>`).join('') +
      '<div id="assess-body"><div class="q-block"><input type="radio" data-question="0" value="0" checked></div></div>');
    await page.evaluate(() => {
      window.toast = () => {};
      window.celebrateSuccess = () => {};
      window.isCompleted = false;
      window.currentCertId = null;
      window.currentCertCode = null;
      window.crypto.randomUUID = () => 'offline_assessment_1234';
      window.fetch = () => Promise.reject(new Error('Offline'));
    });
    await page.addScriptTag({ content: script });
    await page.locator('#assess-submit').click();
    await page.waitForFunction(() => !document.getElementById('assess-body').hidden && !document.getElementById('assess-submit').disabled, null, { timeout: 5000 });
    if (await page.evaluate(() => window.isCompleted)) throw new Error('Offline submission unlocked certificate');
    await page.evaluate(() => {
      window.fetch = () => Promise.resolve({ ok: true, json: () => Promise.resolve({ success: false, score: 0, attempts: 3, remaining_attempts: 0 }) });
    });
    await page.locator('#assess-submit').click();
    await page.waitForFunction(() => !document.getElementById('assess-submit').disabled && !document.getElementById('assess-fail').hidden, null, { timeout: 5000 });
    if (await page.evaluate(() => window.isCompleted)) throw new Error('Failing grade unlocked certificate');
    if (!await page.evaluate(() => document.getElementById('assess-retry').disabled)) throw new Error('Retry limit not reflected');
    await page.evaluate(() => {
      window.fetch = () => Promise.resolve({ ok: true, json: () => Promise.resolve({ success: true, score: 100, certificate_id: 55, certificate_code: 'CERT-AUDIT' }) });
    });
    await page.locator('#assess-submit').click();
    await page.waitForFunction(() => window.isCompleted === true, null, { timeout: 5000 });
    if (await page.locator('#pass-score').textContent() !== '100%') throw new Error('Server score not displayed');
    console.log('PASS: offline failure, failing grades, retry exhaustion, and verified server success behave correctly.');
  } finally {
    await browser.close();
  }
})().catch(error => { console.error(error.message); process.exitCode = 1; });
