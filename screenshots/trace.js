const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const ctx     = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page    = await ctx.newPage();

  page.on('pageerror', err => {
    console.log('=== PAGE ERROR ===');
    console.log(err.name + ': ' + err.message);
    console.log(err.stack);
  });

  page.on('console', msg => {
    if (msg.type() === 'error') {
      console.log('=== CONSOLE ERROR ===');
      console.log(msg.text());
    }
  });

  // Login
  await page.goto('http://localhost:8080/login');
  await page.fill('input[type="email"], #email', 'candidate@test.com');
  await page.fill('input[type="password"], #password', 'Password123!');
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle' }),
    page.click('button[type="submit"]')
  ]);

  // Navigate to builder
  await page.goto('http://localhost:8080/candidate/resumes/build', { waitUntil: 'networkidle' });
  await page.waitForTimeout(1000);

  await browser.close();
})();
