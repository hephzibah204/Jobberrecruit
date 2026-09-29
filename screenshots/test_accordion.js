const { chromium } = require('playwright');
const path = require('path');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const ctx     = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page    = await ctx.newPage();

  page.on('console', msg => console.log('LOG:', msg.text()));
  page.on('pageerror', err => console.log('ERROR:', err.message));

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
  await page.waitForTimeout(500);

  // Click Personal Information header
  console.log('Clicking Personal Information header...');
  await page.click('#sec-info .ed-head');
  await page.waitForTimeout(300);

  // Click Work Experience header
  console.log('Clicking Work Experience header...');
  await page.click('#sec-xp .ed-head');
  await page.waitForTimeout(300);

  // Click Education header
  console.log('Clicking Education header...');
  await page.click('#sec-edu .ed-head');
  await page.waitForTimeout(300);

  // Click Skills & Certs header
  console.log('Clicking Skills & Certs header...');
  await page.click('#sec-skills .ed-head');
  await page.waitForTimeout(300);

  // Take full screenshot after clicking accordions
  await page.screenshot({ path: path.join(__dirname, 'accordion-test.png'), fullPage: true });
  console.log('Saved accordion-test.png');

  await browser.close();
})();
