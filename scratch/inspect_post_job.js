const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();

  console.log('Logging in as employer...');
  await page.goto('http://localhost:8085/login');
  await page.fill('#login-email', 'employer@test.com');
  await page.fill('#login-pass', 'Password123!');
  await page.click('#login-btn');
  await page.waitForNavigation({ waitUntil: 'domcontentloaded' }).catch(() => {});

  console.log('Navigating to /employer/post-job...');
  const res = await page.goto('http://localhost:8085/employer/post-job');
  console.log('Status:', res.status());
  if (res.status() !== 200) {
    const text = await res.text();
    console.log('Error content snippet:', text.substring(0, 1000));
  }

  await browser.close();
})();
