const fs = require('node:fs');
const path = require('node:path');
const { execFileSync } = require('node:child_process');
const { chromium } = require('playwright');
const render = (...args) => execFileSync('C:/Users/hephz/.config/herd/bin/php84/php.exe', ['tests/location-hub-render.php', ...args], { encoding: 'utf8' });
(async () => {
  const browser = await chromium.launch({ headless: true, channel: 'chrome' });
  try {
    const page = await browser.newPage();
    let html = render('', 'signed-in');
    if (/Warning:|Fatal error:/.test(html)) throw new Error(html);
    let saves = 0;
    await page.route('**/*', route => {
      const url = new URL(route.request().url());
      if (url.hostname !== 'homepage.test') return route.abort();
      if (url.pathname === '/') return route.fulfill({ contentType: 'text/html', body: html });
      if (url.pathname === '/reference') return route.fulfill({ contentType: 'text/html', body: fs.readFileSync('Doc/UI and UX Doc/jobberrecruit HTML Files/jobberrecruit-location-lagos.html', 'utf8') });
      if (url.pathname.startsWith('/jobs/toggle-save/')) {
        if (!route.request().postData().includes('csrf_test')) throw new Error('Save token missing');
        return route.fulfill({ contentType: 'application/json', body: JSON.stringify({ success: true, saved: ++saves % 2 === 1, csrfHash: 'updated-token' }) });
      }
      const local = path.join(process.cwd(), decodeURIComponent(url.pathname));
      if (url.pathname.startsWith('/css/') && fs.existsSync(local)) return route.fulfill({ contentType: 'text/css', body: fs.readFileSync(local) });
      if (url.pathname.startsWith('/images/') && fs.existsSync(local)) return route.fulfill({ body: fs.readFileSync(local) });
      return route.fulfill({ contentType: 'application/json', body: '[]' });
    });
    for (const width of [1440, 768, 390, 320]) {
      await page.setViewportSize({ width, height: 900 });
      await page.goto('http://homepage.test/');
      const metrics = await page.evaluate(() => ({ overflow: document.documentElement.scrollWidth > innerWidth, brand: getComputedStyle(document.documentElement).getPropertyValue('--brand').trim(), columns: getComputedStyle(document.querySelector('.jobs-grid')).gridTemplateColumns }));
      if (metrics.overflow || metrics.brand !== '#0861A9') throw new Error(JSON.stringify(metrics));
      if (await page.locator('.job-card').count() !== 6) throw new Error('Missing jobs');
      if (await page.locator('.job-card').nth(1).locator('.job-company').innerText() !== 'Confidential Employer') throw new Error('Anonymous company exposed');
      if (await page.locator('.crosslink-grid [aria-current="page"]').count() !== 1) throw new Error('Current location missing');
      const hero = await page.locator('.land-hero').evaluate(el => ({ padding: getComputedStyle(el).padding, font: getComputedStyle(el.querySelector('h1')).fontSize }));
      await page.screenshot({ path: `scratch/location-${width}.png`, fullPage: true });
      await page.goto('http://homepage.test/reference');
      const reference = await page.locator('.land-hero').evaluate(el => ({ padding: getComputedStyle(el).padding, font: getComputedStyle(el.querySelector('h1')).fontSize }));
      if (JSON.stringify(hero) !== JSON.stringify(reference)) throw new Error('Hero differs from reference at ' + width);
      console.log(`${width}px: reference hero dimensions, responsive layout, live cards and location links passed`);
    }
    await page.goto('http://homepage.test/');
    const button = page.locator('.save-btn').first();
    await button.click();
    await page.waitForFunction(() => document.querySelector('.job-save-feedback').textContent === 'Job saved.');
    if (await page.locator('.job-save-form input').first().inputValue() !== 'updated-token') throw new Error('Token not refreshed');
    await button.click();
    await page.waitForFunction(() => document.querySelector('.job-save-feedback').textContent === 'Job removed from saved jobs.');
    html = render('empty');
    await page.goto('http://homepage.test/');
    if (await page.locator('.job-card').count() || !(await page.locator('.land-empty').isVisible())) throw new Error('Empty state failed');
    html = render('other');
    await page.goto('http://homepage.test/');
    if (!(await page.locator('h1').innerText()).includes('Rivers')) throw new Error('Location hard-coded');
    console.log('Save/unsave, CSRF refresh, empty state and alternative location passed');
  } finally { await browser.close(); }
})();
