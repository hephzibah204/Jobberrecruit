const { chromium } = require('playwright');
const path = require('path');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await ctx.newPage();
  
  const errors = [];
  page.on('pageerror', err => errors.push('PAGE ERROR: ' + err.message));
  page.on('console', msg => {
    if (msg.type() === 'error') errors.push('CONSOLE ERROR: ' + msg.text());
  });

  try {
    // 1. Login
    await page.goto('http://localhost:8080/temp-login');
    console.log('Logged in successfully');

    // 2. Go to resumes page
    await page.goto('http://localhost:8080/candidate/resumes', { waitUntil: 'networkidle', timeout: 15000 });
    console.log('Resumes page URL:', page.url());
    
    // Find edit button or card
    const cardSelector = 'a[href*="resumes/build"], .rz-card, [data-edit]';
    const cards = await page.$$(cardSelector);
    console.log('Found ' + cards.length + ' resume cards');
    
    if (cards.length > 0) {
      // Find the first resume edit link
      let href = await page.$eval('a[href*="resumes/build"]', el => el.getAttribute('href'));
      if (!href.startsWith('http')) {
        href = 'http://localhost:8080' + href;
      }
      console.log('Navigating to builder URL:', href);
      await page.goto(href, { waitUntil: 'networkidle', timeout: 15000 });
    } else {
      // Try direct navigation to build/1
      console.log('No cards found, navigating directly to build/1');
      await page.goto('http://localhost:8080/candidate/resumes/build/1', { waitUntil: 'networkidle', timeout: 15000 });
    }
    
    await page.waitForTimeout(3000);
    console.log('Builder page URL:', page.url());
    console.log('Builder page Title:', await page.title());

    // Check preview column & doc elements
    const previewCol = await page.$('.rb-preview-col');
    const docEl = await page.$('#doc');
    const editorCol = await page.$('.rb-editor-col');
    
    console.log('\n=== BUILDER ELEMENT CHECK ===');
    console.log('Preview column found:', !!previewCol);
    console.log('Document element (#doc) found:', !!docEl);
    console.log('Editor column found:', !!editorCol);

    if (docEl) {
      const docHtml = await docEl.innerHTML();
      console.log('Doc HTML length:', docHtml.length);
      console.log('Doc HTML preview check (has content):', docHtml.length > 100);
    }

    // Check ed-head elements
    const edHeads = await page.$$('.ed-head');
    console.log('\nTotal ed-head elements:', edHeads.length);
    
    let withOnclick = 0;
    for (const head of edHeads) {
      const onclick = await head.getAttribute('onclick');
      if (onclick) withOnclick++;
    }
    console.log('Ed-head with onclick attribute:', withOnclick);

    // Save screenshots to artifacts directory so user can see them
    const screenshotsDir = 'C:\\Users\\hephz\\.gemini\\antigravity\\brain\\0a9d148c-5330-43b5-81b3-50646f878b08';
    await page.screenshot({ path: path.join(screenshotsDir, 'live-builder-final.png'), fullPage: true });
    console.log('Saved live-builder-final.png screenshot to artifacts');

    console.log('\n=== JS ERRORS ===');
    if (errors.length === 0) {
      console.log('No JavaScript errors detected!');
    } else {
      errors.forEach(e => console.log(e));
    }

  } catch (err) {
    console.error('Verification script failure:', err.message);
  }

  await browser.close();
})();
