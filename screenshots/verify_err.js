const { chromium } = require('playwright');
const path = require('path');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await ctx.newPage();
  
  const errors = [];
  page.on('pageerror', err => {
    errors.push('PAGE ERROR: ' + err.message + '\nStack: ' + err.stack);
  });
  page.on('console', msg => {
    if (msg.type() === 'error') errors.push('CONSOLE ERROR: ' + msg.text());
  });

  try {
    await page.goto('http://localhost:8080/temp-login');
    await page.goto('http://localhost:8080/candidate/resumes/build', { waitUntil: 'networkidle', timeout: 15000 });
    await page.waitForTimeout(2000);

    console.log('\n=== JS ERRORS ===');
    if (errors.length === 0) {
      console.log('No JavaScript errors detected!');
    } else {
      errors.forEach(e => console.log(e));
    }

  } catch (err) {
    console.error('Error:', err.message);
  }

  await browser.close();
})();
