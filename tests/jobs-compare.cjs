const fs = require('node:fs');
const path = require('node:path');
const { pathToFileURL } = require('node:url');
const { execFileSync } = require('node:child_process');
const { chromium } = require('playwright');
(async () => {
  const html = execFileSync('C:/Users/hephz/.config/herd/bin/php84/php.exe', ['tests/jobs-render.php'], { encoding: 'utf8' });
  if (/Warning:|Fatal error:/.test(html)) throw Error(html.slice(0, 2000));
  const out = path.resolve('scratch/jobs-comparison'); fs.mkdirSync(out, { recursive: true });
  const browser = await chromium.launch({ headless: true, executablePath: 'C:/Users/hephz/AppData/Local/ms-playwright/chromium-1234/chrome-win64/chrome.exe', timeout: 60000 });
  try {
    for (const [name, url] of [
      ['mockup', pathToFileURL(path.resolve('Doc/UI and UX Doc/jobberrecruit HTML Files/jobberrecruit-jobs (1).html')).href],
      ['php-local', 'http://jobs.test/jobs'],
      ['php-live', 'https://jobberrecruit.com/jobs'],
    ]) {
      const page = await browser.newPage();
      await page.route('**/*', route => {
        const target = new URL(route.request().url());
        if (target.hostname === 'jobs.test') {
          if (target.pathname.startsWith('/images/')) {
            const file = path.join(process.cwd(), target.pathname);
            if (fs.existsSync(file)) return route.fulfill({ body: fs.readFileSync(file) });
          }
          return route.fulfill({ contentType: 'text/html', body: html });
        }
        if (/googlesyndication|googletagmanager|google-analytics|doubleclick/.test(target.hostname)) return route.abort();
        return route.continue();
      });
      for (const width of [1440, 390]) {
        await page.setViewportSize({ width, height: 1000 });
        const response = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 60000 });
        await Promise.race([page.evaluate(() => document.fonts.ready), new Promise(resolve => setTimeout(resolve, 10000))]);
        await page.evaluate(() => { document.documentElement.style.scrollBehavior = 'auto'; scrollTo(0, 0); });
        const metrics = await page.evaluate(() => {
          const q = sel => document.querySelector(sel);
          const text = sel => q(sel)?.textContent.trim();
          const box = sel => { const r = q(sel)?.getBoundingClientRect(); return r ? { x: Math.round(r.x), y: Math.round(r.y), w: Math.round(r.width), h: Math.round(r.height) } : null; };
          return {
            title: document.title, viewport: innerWidth, documentWidth: document.documentElement.scrollWidth,
            heading: text('.jobs-hero h1'), intro: text('.jobs-hero p'), hero: box('.jobs-hero'), search: box('.jobs-hero .search-card'),
            layout: box('.jobs-layout'), toolbar: box('.results-toolbar'), firstCard: box('.job-card'),
            nav: [...document.querySelectorAll('.nav-links > li')].map(el => el.textContent.trim().replace(/\s+/g, ' ')),
            cards: document.querySelectorAll('.job-card').length, promoted: document.querySelectorAll('.job-card--featured').length,
            closed: document.querySelectorAll('.job-card--closed, .job-card.is-closed, .job-card.closed').length,
            dates: [...document.querySelectorAll('.job-card .job-meta')].slice(0, 2).map(el => el.textContent.trim().replace(/\s+/g, ' ')),
            adverts: document.querySelectorAll('.results-ad').length,
            types: [...document.querySelectorAll('.filter-check')].map(el => el.textContent.trim().replace(/\s+/g, ' ')),
            sorts: [...document.querySelectorAll('.results-sort select option')].map(el => el.textContent.trim()),
            filtersApply: text('#applyFilters') || text('.filters-actions .btn-primary'),
            filterHidden: q('#filters') ? getComputedStyle(q('#filters')).transform : null,
            newsletter: !!q('.newsletter-form'), cta: text('.dual-cta'),
            font: getComputedStyle(q('.jobs-hero h1') || document.body).fontFamily,
          };
        });
        console.log(JSON.stringify({ name, width, status: response?.status(), metrics }));
        await page.screenshot({ path: path.join(out, `${name}-${width}.png`), fullPage: true, timeout: 30000 });
        await page.screenshot({ path: path.join(out, `${name}-top-${width}.png`), timeout: 30000 });
        if (width === 390 && await page.locator('#filters-open').count()) {
          await page.locator('#filters-open').click();
          await page.waitForFunction(() => Math.abs(document.querySelector('#filters').getBoundingClientRect().left) < 1, { timeout: 5000 }).catch(() => {});
          await page.screenshot({ path: path.join(out, `${name}-filters-${width}.png`) });
          console.log(JSON.stringify({ name, drawer: await page.locator('#filters').evaluate(el => ({ open: el.classList.contains('open'), width: el.getBoundingClientRect().width, backdrop: getComputedStyle(document.querySelector('#filters-overlay')).backdropFilter })) }));
        }
      }
      await page.close();
    }
  } finally { await browser.close(); }
})().catch(e => { console.error(e); process.exitCode = 1; });
