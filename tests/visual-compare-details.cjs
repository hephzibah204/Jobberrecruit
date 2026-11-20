const fs = require('node:fs');
const path = require('node:path');
const { execFileSync } = require('node:child_process');
const { chromium } = require('playwright');

(async () => {
  const phpRendered = execFileSync('C:/Users/hephz/.config/herd/bin/php84/php.exe', ['tests/jobs-render.php'], { encoding: 'utf8' });
  const mockupHtml = fs.readFileSync('Doc/UI and UX Doc/jobberrecruit HTML Files/jobberrecruit-jobs (1).html', 'utf8');

  const dir = 'scratch/visual-compare';
  fs.mkdirSync(dir, { recursive: true });

  const browser = await chromium.launch({
    headless: true,
    executablePath: 'C:/Users/hephz/AppData/Local/ms-playwright/chromium-1234/chrome-win64/chrome.exe'
  });

  const page = await browser.newPage();

  await page.route('**/*', route => {
    const url = new URL(route.request().url());
    if (url.hostname !== 'jobs.test') return route.abort();
    if (url.pathname === '/mockup') return route.fulfill({ contentType: 'text/html', body: mockupHtml });
    if (url.pathname === '/php-view') return route.fulfill({ contentType: 'text/html', body: phpRendered });
    if (url.pathname.startsWith('/images/') || url.pathname.startsWith('/assets/')) {
      const localP = path.join(process.cwd(), url.pathname.replace(/^\/assets\//, '/images/'));
      if (fs.existsSync(localP)) return route.fulfill({ body: fs.readFileSync(localP) });
      const orig = path.join(process.cwd(), url.pathname);
      if (fs.existsSync(orig)) return route.fulfill({ body: fs.readFileSync(orig) });
    }
    return route.fulfill({ contentType: 'text/html', body: '' });
  });

  await page.setViewportSize({ width: 1440, height: 1000 });

  // 1. Capture sections from Mockup
  await page.goto('http://jobs.test/mockup');
  const mockupNavbar = await page.locator('.navbar').boundingBox();
  const mockupHero = await page.locator('.jobs-hero').boundingBox();
  const mockupFilters = await page.locator('.filters').boundingBox();
  const mockupToolbar = await page.locator('.results-toolbar').boundingBox();
  const mockupFirstCard = await page.locator('.job-card').first().boundingBox();
  const mockupAd = await page.locator('.results-ad').boundingBox();
  const mockupPagination = await page.locator('.pagination').boundingBox();
  const mockupDualCta = await page.locator('.dual-cta').boundingBox();
  const mockupFooter = await page.locator('.footer').boundingBox();

  await page.locator('.navbar').screenshot({ path: path.join(dir, 'mockup-navbar.png') });
  await page.locator('.jobs-hero').screenshot({ path: path.join(dir, 'mockup-hero.png') });
  await page.locator('.filters').screenshot({ path: path.join(dir, 'mockup-filters.png') });
  await page.locator('.results-toolbar').screenshot({ path: path.join(dir, 'mockup-toolbar.png') });
  await page.locator('.job-card').first().screenshot({ path: path.join(dir, 'mockup-firstcard.png') });
  await page.locator('.results-ad').screenshot({ path: path.join(dir, 'mockup-ad.png') });
  await page.locator('.pagination').screenshot({ path: path.join(dir, 'mockup-pagination.png') });
  await page.locator('.dual-cta').screenshot({ path: path.join(dir, 'mockup-dualcta.png') });
  await page.locator('.footer').screenshot({ path: path.join(dir, 'mockup-footer.png') });

  // 2. Capture sections from PHP View
  await page.goto('http://jobs.test/php-view');
  const phpNavbar = await page.locator('.navbar').boundingBox();
  const phpHero = await page.locator('.jobs-hero').boundingBox();
  const phpFilters = await page.locator('.filters').boundingBox();
  const phpToolbar = await page.locator('.results-toolbar').boundingBox();
  const phpFirstCard = await page.locator('.job-card').first().boundingBox();
  const phpAd = await page.locator('.results-ad').boundingBox();
  const phpPagination = await page.locator('.pagination').boundingBox();
  const phpDualCta = await page.locator('.dual-cta').boundingBox();
  const phpFooter = await page.locator('.footer').boundingBox();

  await page.locator('.navbar').screenshot({ path: path.join(dir, 'php-navbar.png') });
  await page.locator('.jobs-hero').screenshot({ path: path.join(dir, 'php-hero.png') });
  await page.locator('.filters').screenshot({ path: path.join(dir, 'php-filters.png') });
  await page.locator('.results-toolbar').screenshot({ path: path.join(dir, 'php-toolbar.png') });
  await page.locator('.job-card').first().screenshot({ path: path.join(dir, 'php-firstcard.png') });
  await page.locator('.results-ad').screenshot({ path: path.join(dir, 'php-ad.png') });
  await page.locator('.pagination').screenshot({ path: path.join(dir, 'php-pagination.png') });
  await page.locator('.dual-cta').screenshot({ path: path.join(dir, 'php-dualcta.png') });
  await page.locator('.footer').screenshot({ path: path.join(dir, 'php-footer.png') });

  // Compare List view on both
  await page.goto('http://jobs.test/mockup');
  await page.locator('[data-view="list"]').click();
  await page.locator('.job-card').first().screenshot({ path: path.join(dir, 'mockup-firstcard-list.png') });

  await page.goto('http://jobs.test/php-view');
  await page.locator('[data-view="list"]').click();
  await page.locator('.job-card').first().screenshot({ path: path.join(dir, 'php-firstcard-list.png') });

  // Mobile comparisons (390px)
  await page.setViewportSize({ width: 390, height: 844 });
  await page.goto('http://jobs.test/mockup');
  await page.screenshot({ path: path.join(dir, 'mockup-mobile-top.png') });
  await page.locator('#filters-open').click();
  await page.screenshot({ path: path.join(dir, 'mockup-mobile-drawer.png') });

  await page.goto('http://jobs.test/php-view');
  await page.screenshot({ path: path.join(dir, 'php-mobile-top.png') });
  await page.locator('#filters-open').click();
  await page.screenshot({ path: path.join(dir, 'php-mobile-drawer.png') });

  const summary = {
    navbar: { mockup: mockupNavbar, php: phpNavbar },
    hero: { mockup: mockupHero, php: phpHero },
    filters: { mockup: mockupFilters, php: phpFilters },
    toolbar: { mockup: mockupToolbar, php: phpToolbar },
    firstCard: { mockup: mockupFirstCard, php: phpFirstCard },
    ad: { mockup: mockupAd, php: phpAd },
    pagination: { mockup: mockupPagination, php: phpPagination },
    dualCta: { mockup: mockupDualCta, php: phpDualCta },
    footer: { mockup: mockupFooter, php: phpFooter }
  };

  fs.writeFileSync(path.join(dir, 'dimensions-summary.json'), JSON.stringify(summary, null, 2));
  console.log('DIMENSIONS_SUMMARY:', JSON.stringify(summary, null, 2));

  await browser.close();
})();
