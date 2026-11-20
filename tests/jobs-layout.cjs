const fs = require('node:fs');
const path = require('node:path');
const { execFileSync } = require('node:child_process');
const { chromium } = require('playwright');
(async () => {
  const html = execFileSync('C:/Users/hephz/.config/herd/bin/php84/php.exe', ['tests/jobs-render.php'], { encoding: 'utf8' });
  if (/Warning:|Fatal error:/.test(html)) throw Error(html.slice(0, 2500));
  fs.mkdirSync('scratch', { recursive: true });
  const browser = await chromium.launch({ headless: true, executablePath: 'C:/Users/hephz/AppData/Local/ms-playwright/chromium-1234/chrome-win64/chrome.exe', timeout: 60000 });
  try {
    const page = await browser.newPage();
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.route('**/*', route => {
      const url = new URL(route.request().url());
      if (url.hostname !== 'jobs.test') return route.abort();
      if (url.pathname.startsWith('/images/')) {
        const file = path.join(process.cwd(), url.pathname);
        if (fs.existsSync(file)) return route.fulfill({ body: fs.readFileSync(file) });
      }
      return route.fulfill({ contentType: 'text/html', body: html });
    });
    for (const width of [1440, 1024, 768, 390, 320]) {
      await page.setViewportSize({ width, height: 950 });
      await page.goto('http://jobs.test/jobs');
      const chrome = await page.evaluate(() => ({
        header: document.querySelector('header.navbar').getBoundingClientRect().height,
        heroTop: document.querySelector('.jobs-hero').getBoundingClientRect().top,
        searchWidth: document.querySelector('.search-card').getBoundingClientRect().width,
        footerColor: getComputedStyle(document.querySelector('.footer')).backgroundColor,
        iconWidth: document.querySelector('.footer-socials svg').getBoundingClientRect().width,
        mobileMenuHidden: getComputedStyle(document.querySelector('#mob-nav')).display === 'none'
      }));
      if (chrome.header > 90 || chrome.heroTop > 100 || chrome.iconWidth > 24 || chrome.footerColor !== 'rgb(10, 47, 87)' || !chrome.mobileMenuHidden) throw Error('Shared header/footer regression: ' + JSON.stringify(chrome));
      if (width === 1440 && Math.round(chrome.searchWidth) !== 820) throw Error('Search bar does not match reference width');
      if (!(await page.locator('.nav-links').innerText()).includes('Career advice')) throw Error('Reference navigation missing Career advice');
      const overflow = await page.evaluate(() => document.documentElement.scrollWidth > innerWidth);
      if (overflow) throw Error('Overflow at ' + width);
      if (await page.locator('.job-card').count() !== 8) throw Error('Missing cards');
      await page.locator('[data-view="list"]').click();
      if (await page.locator('.grid-only').first().isVisible()) throw Error('Duplicate list/grid content');
      if (!(await page.locator('.list-body').first().isVisible())) throw Error('Missing list layout');
      await page.screenshot({ path: `scratch/jobs-list-${width}.png`, fullPage: true });
      await page.locator('[data-view="grid"]').click();
      if (width <= 900) {
        if (width <= 860) {
          await page.locator('.hamburger').click();
          if (!(await page.locator('#mob-nav').isVisible())) throw Error('Mobile navigation did not open');
          await page.locator('.hamburger').click();
          if (await page.locator('#mob-nav').isVisible()) throw Error('Mobile navigation did not close');
        }
        if (await page.locator('#filters').evaluate(el => el.classList.contains('open'))) throw Error('Filters opened by themselves');
        await page.locator('#filters-open').click();
        if (!(await page.locator('#filters').evaluate(el => el.classList.contains('open')))) throw Error('Drawer did not open');
        await page.waitForFunction(() => Math.abs(document.querySelector('#filters').getBoundingClientRect().left) < 1);
        const close = await page.locator('#filters-close').boundingBox();
        if (!close || close.x < 0 || close.x + close.width > width) throw Error('Drawer close button clipped');
        await page.screenshot({ path: `scratch/jobs-filters-${width}.png` });
        await page.keyboard.press('Escape');
        if (await page.locator('#filters').evaluate(el => el.classList.contains('open'))) throw Error('Escape did not close filters');
        await page.locator('#filters-open').click();
        await page.locator('#filters-close').click();
        await page.evaluate(() => window.scrollBy(0, 1000));
        const stickyBtnBox = await page.locator('#filters-open').boundingBox();
        if (!stickyBtnBox || Math.round(stickyBtnBox.y) !== 78) throw Error('Mobile filter button is not sticky at 78px on scroll: ' + JSON.stringify(stickyBtnBox));
        const stickyNavBox = await page.locator('header.navbar').boundingBox();
        if (!stickyNavBox || Math.round(stickyNavBox.y) !== 0) throw Error('Navbar is not sticky at top on scroll: ' + JSON.stringify(stickyNavBox));
      }
      await page.evaluate(() => { document.documentElement.style.scrollBehavior = 'auto'; scrollTo(0, 0); });
      await page.screenshot({ path: `scratch/jobs-grid-${width}.png`, fullPage: true });
      await page.screenshot({ path: `scratch/jobs-top-${width}.png` });
      console.log(`${width}px: grid/list, no overflow and filter drawer passed`);
    }
    await page.setViewportSize({ width: 1440, height: 1000 });
    await page.goto('http://jobs.test/jobs');
    await page.locator('#f-keywords').fill('Data');
    if (await page.locator('#searchKeyword').inputValue() !== 'Data') throw Error('Keyword fields not synchronized');
    await page.locator('#f-location').selectOption('2');
    await page.locator('label:has(#remoteFilter)').click();
    if (!(await page.locator('#remoteFilter').isChecked())) throw Error('Remote control is not selectable');
    if (await page.locator('#locationFilter').inputValue() !== '2') throw Error('Location fields not synchronized');
    await page.locator('#applyFilters').click();
    await page.waitForURL('**/*keywords=Data*');
    const params = new URL(page.url()).searchParams;
    if (params.get('state_id') !== '2') throw Error('Incorrect location request');
    if (params.get('work_arrangement') !== 'remote') throw Error('Remote filter did not submit');
    await page.locator('#active-filters button').first().waitFor();
    await page.locator('#sortBy').selectOption('relevance');
    await page.waitForURL('**/*sort_by=relevance*');
    if (new URL(page.url()).searchParams.get('sort_by') !== 'relevance') throw Error('Relevance sort not submitted');
    await page.goto('http://jobs.test/jobs?keywords=%3Cimg%20src%3Dx%20onerror%3Dalert(1)%3E');
    if (await page.locator('#active-filters img').count()) throw Error('Filter chips contain untrusted HTML');
    console.log('Header/footer geometry, Remote/relevance requests, synchronized inputs and safe filter chips passed');
    if (errors.length) throw Error(errors.join('\n'));
  } finally { await browser.close(); }
})().catch(e => { console.error(e); process.exitCode = 1; });
