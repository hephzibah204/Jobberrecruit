const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();

  page.on('console', msg => console.log(`[Browser Console ${msg.type()}]`, msg.text()));

  await page.goto('http://localhost:8085/login');
  await page.fill('#login-email', 'candidate@test.com');
  await page.fill('#login-pass', 'Password123!');
  await page.click('#login-btn');
  await page.waitForTimeout(3000);

  const html = await page.content();
  console.log('HTML snippet around alert/error:');
  if (html.includes('auth-alert')) {
    console.log('Alert text:', await page.$eval('.auth-alert', el => el.innerText));
  } else {
    console.log('No auth-alert found.');
  }

  await browser.close();
})();
