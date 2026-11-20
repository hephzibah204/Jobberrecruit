const { chromium } = require('playwright');
const fs = require('node:fs');
(async () => {
  const browser = await chromium.launch({ headless: true, executablePath: 'C:/Users/hephz/AppData/Local/ms-playwright/chromium-1234/chrome-win64/chrome.exe', timeout: 60000 });
  try {
    fs.mkdirSync('scratch', { recursive: true });
    const context = await browser.newContext();
    const page = await context.newPage();
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const response = await page.goto('https://jobberrecruit.com/', { waitUntil: 'domcontentloaded', timeout: 60000 });
    console.log(JSON.stringify({ initialStatus: response.status(), title: await page.title(), text: (await page.locator('body').innerText()).slice(0, 1000) }));
    console.log(JSON.stringify({ stylesheets: await page.locator('link[rel="stylesheet"]').evaluateAll(els => els.map(el => ({ url: el.href, loaded: !!el.sheet }))) }));
    const cssResponse = await context.request.get('https://jobberrecruit.com/css/homepage.css', { timeout: 30000 });
    console.log(JSON.stringify({ homepageCssStatus: cssResponse.status(), contentType: cssResponse.headers()['content-type'], cssStart: (await cssResponse.text()).slice(0, 150) }));
    if (process.argv.includes('--preview-fix')) {
      await page.addStyleTag({ content: fs.readFileSync('css/homepage.css', 'utf8') });
      console.log('PREVIEW ONLY: local stylesheet applied in this browser to verify the fix against live content. Server files are unchanged.');
    }
    await page.screenshot({ path: 'scratch/homepage-live-initial.png', fullPage: true });
    if (!(await page.locator('.job-card').count())) {
      await Promise.all(['/ai-tools/resume-builder', '/ai-tools/mock-interview', '/training/cv-revamp', '/referral', '/jobs/featured', '/cv-review', '/blog'].map(async path => {
        try {
          const r = await context.request.get('https://jobberrecruit.com' + path, { timeout: 30000 });
          console.log(JSON.stringify({ path, status: r.status(), finalUrl: r.url() }));
        } catch (e) { console.log(JSON.stringify({ path, error: e.message.slice(0, 140) })); }
      }));
      return;
    }
    await page.evaluate(() => document.fonts.ready);
    console.log(JSON.stringify({ homepageStatus: response.status(), url: page.url(), cards: await page.locator('.job-card').count(), companies: await page.locator('.job-company-name').allTextContents(), badges: await page.locator('.verified-check').count(), moreLinks: await page.locator('a').evaluateAll(els => els.filter(e => /featured jobs/i.test(e.textContent)).map(e => ({ text: e.textContent.trim(), href: e.href }))) }));
    const hrefs = await page.locator('a[href]').evaluateAll(els => [...new Set(els.map(e => e.href).filter(h => h.startsWith(location.origin) && !h.includes('#') && !/\/job\/view\//.test(h)))]);
    for (let i = 0; !process.argv.includes('--layout-only') && i < hrefs.length; i += 5) {
      await Promise.all(hrefs.slice(i, i + 5).map(async href => {
        try { const r = await context.request.get(href, { timeout: 30000 }); console.log(JSON.stringify({ link: href, status: r.status(), finalUrl: r.url() })); }
        catch (e) { console.log(JSON.stringify({ link: href, error: e.message.slice(0, 180) })); }
      }));
    }
    for (const width of [1440, 390, 320]) {
      await page.setViewportSize({ width, height: 950 });
      await page.screenshot({ path: `scratch/homepage-live-${width}.png`, fullPage: true, timeout: 30000 });
      console.log(JSON.stringify({ width, layout: await page.evaluate(() => ({ viewport: innerWidth, documentWidth: document.documentElement.scrollWidth, cardCount: document.querySelectorAll('.job-card').length, brand: getComputedStyle(document.documentElement).getPropertyValue('--brand').trim() })) }));
    }
    await page.locator('.hero-tabs button').nth(1).click();
    console.log(JSON.stringify({ employerTab: await page.locator('#hero-employer').isVisible(), seekerHidden: !(await page.locator('#hero-seeker').isVisible()) }));
    await page.locator('.hero-tabs button').first().click();
    await page.locator('.faq-item summary').first().click();
    console.log(JSON.stringify({ faqOpen: await page.locator('.faq-item').first().getAttribute('open') !== null }));
    if (await page.locator('.verified-check').count()) {
      const badge = page.locator('.verified-check').first();
      await badge.click();
      console.log(JSON.stringify({ badgeVisible: await badge.isVisible(), tooltipVisible: await badge.locator('.verified-tip').isVisible() }));
    }
    for (const path of (process.argv.includes('--layout-only') ? [] : ['/ai-tools/resume-builder', '/ai-tools/mock-interview', '/training/cv-revamp', '/referral', '/jobs/featured'])) {
      const r = await context.request.get('https://jobberrecruit.com' + path, { timeout: 30000 });
      console.log(JSON.stringify({ legacyPath: path, status: r.status(), finalUrl: r.url() }));
    }
    console.log(JSON.stringify({ javascriptErrors: errors }));
  } finally { await browser.close(); }
})().catch(e => { console.error(e); process.exitCode = 1; });
