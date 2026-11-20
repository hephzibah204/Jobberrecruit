const fs = require('node:fs');
const { execFileSync } = require('node:child_process');
const { chromium } = require('playwright');
const path = require('node:path');
(async () => {
  const html = execFileSync('C:/Users/hephz/.config/herd/bin/php84/php.exe', ['tests/homepage-render.php'], { encoding: 'utf8' });
  if (html.includes('Warning:') || html.includes('Fatal error:')) throw new Error(html);
  const browser = await chromium.launch({ headless: true, executablePath: 'C:/Users/hephz/AppData/Local/ms-playwright/chromium-1234/chrome-win64/chrome.exe', timeout: 60000 });
  try {
    fs.mkdirSync('scratch', { recursive: true });
    const page = await browser.newPage();
    const pageErrors = [];
    page.on('pageerror', error => pageErrors.push(error.message));
    await page.route('**/*', async route => {
      const url = new URL(route.request().url());
      if (url.hostname !== 'homepage.test') return route.abort();
      if (url.pathname === '/') return route.fulfill({ contentType: 'text/html', body: html });
      if (url.pathname === '/css/homepage.css') return route.fulfill({ status: 404, body: 'Simulated missing stylesheet' });
      if (url.pathname === '/reference') return route.fulfill({ contentType: 'text/html', body: fs.readFileSync('Doc/UI and UX Doc/jobberrecruit HTML Files/homepage.html', 'utf8') });
      if (url.pathname === '/newsletter/subscribe') {
        if (!route.request().postData().includes('csrf_test')) throw new Error('Newsletter CSRF token missing');
        return route.fulfill({ status: 201, contentType: 'application/json', body: JSON.stringify({ message: 'Successfully subscribed to newsletter' }) });
      }
      const local = path.join(process.cwd(), decodeURIComponent(url.pathname));
      if (url.pathname.startsWith('/css/') && fs.existsSync(local)) return route.fulfill({ contentType: 'text/css', body: fs.readFileSync(local) });
      if (url.pathname.startsWith('/images/') && fs.existsSync(local)) return route.fulfill({ body: fs.readFileSync(local) });
      return route.fulfill({ contentType: 'application/json', body: '[]' });
    });
    for (const width of [1440, 768, 390, 320]) {
      await page.setViewportSize({ width, height: 900 });
      await page.goto('http://homepage.test/');
      if (await page.locator('.job-card').count() !== 8) throw new Error('Expected exactly eight homepage cards');
      if (await page.locator('style#homepage-design').count() !== 1) throw new Error('Self-contained design missing');
      if (await page.locator('link[href*="css/homepage.css"]').count()) throw new Error('Homepage still depends on missing stylesheet');
      if (await page.locator('.jobs-more a').getAttribute('href') !== 'http://homepage.test/jobs/featured') throw new Error('Incorrect see-more destination');
      const layout = await page.evaluate(() => ({
        width: document.documentElement.clientWidth,
        scroll: document.documentElement.scrollWidth,
        brand: getComputedStyle(document.documentElement).getPropertyValue('--brand').trim(),
        jobs: getComputedStyle(document.querySelector('.jobs-grid')).gridTemplateColumns.split(' ').length,
        footer: getComputedStyle(document.querySelector('.footer-grid')).gridTemplateColumns.split(' ').length
      }));
      if (layout.scroll > layout.width || layout.brand !== '#0861A9') throw new Error(JSON.stringify({ width, ...layout }));
      if (width <= 580 && (layout.jobs !== 1 || layout.footer !== 1)) throw new Error('Incorrect mobile columns');
      if (width <= 580) {
        const nav = await page.locator('.hamburger').boundingBox();
        if (!nav || nav.x + nav.width > width) throw new Error('Mobile navigation clipped');
      }
      await page.locator('.hero-tabs button').nth(1).click();
      if (!(await page.locator('#hero-employer').isVisible()) || await page.locator('#hero-seeker').isVisible()) throw new Error('Role switch failed');
      await page.locator('.hero-tabs button').first().click();
      if (await page.locator('form[role="search"] select').count() !== 2) throw new Error('Search filters missing');
      const badge = page.locator('.verified-check').first();
      if (!(await badge.isVisible())) throw new Error('Verified employer badge missing');
      if (await page.locator('symbol#i-verified-disc').count() !== 1) throw new Error('Verified badge icon missing');
      await badge.click();
      if (!(await badge.locator('.verified-tip').isVisible())) throw new Error('Verified tooltip missing');
      if (!(await badge.evaluate(el => el.classList.contains('open')))) throw new Error('Verified badge tap failed');
      await page.keyboard.press('Escape');
      if (await badge.evaluate(el => el.classList.contains('open'))) throw new Error('Verified badge dismissal failed');
      await page.locator('.faq-item summary').first().click();
      if (await page.locator('.faq-item').first().getAttribute('open') === null) throw new Error('FAQ did not expand');
      await page.locator('.faq-item summary').first().click();
      await page.locator('#news-email').fill('preview@example.test');
      await page.locator('.newsletter-form button').click();
      await page.locator('.newsletter-feedback').filter({ hasText: 'Successfully subscribed' }).waitFor();
      await page.evaluate(() => { document.documentElement.style.scrollBehavior = 'auto'; window.scrollTo(0, 0); });
      await page.screenshot({ path: `scratch/homepage-${width}.png`, fullPage: true });
      await page.screenshot({ path: `scratch/homepage-top-${width}.png` });
      if (width === 1440 || width === 390) await page.locator('.jobs-grid').screenshot({ path: `scratch/homepage-verified-${width}.png` });
      console.log(`${width}px: no overflow, reference palette, responsive columns and role switching passed`);
    }
    if (pageErrors.length) throw new Error(pageErrors.join('\n'));
    console.log('Eight cards, embedded styling, see-more destination and JavaScript checks passed');
    await page.setViewportSize({ width: 1440, height: 1000 });
    await page.goto('http://homepage.test/reference');
    await page.screenshot({ path: 'scratch/homepage-reference-desktop.png', fullPage: true });
    console.log('FAQ expansion and newsletter submission (mock endpoint) passed');
  } finally { await browser.close(); }
})();
