const { chromium } = require('playwright');

async function testAdmin() {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({
    viewport: { width: 390, height: 844 },
    isMobile: true,
    hasTouch: true
  });
  const page = await context.newPage();

  page.on('console', msg => console.log('PAGE CONSOLE:', msg.type(), msg.text()));
  page.on('response', async res => {
    if (res.url().includes('login') || res.url().includes('admin')) {
      try {
        const text = await res.text();
        console.log(`RES ${res.status()} ${res.url()}:`, text.substring(0, 300));
      } catch (e) {}
    }
  });

  await page.goto('http://localhost:8080/admin/logout');
  await page.goto('http://localhost:8080/admin/login');
  await page.locator('#signin-email').fill('admin@test.com');
  await page.locator('#signin-password').fill('Password123');
  await page.locator('#loginBtn').click();
  await page.waitForTimeout(3000);
  console.log('Final URL:', page.url());

  await browser.close();
}

testAdmin().catch(console.error);
