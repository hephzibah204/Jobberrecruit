const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  page.on('console', msg => console.log(`[Browser Console ${msg.type()}]`, msg.text()));

  // Intercept ALL responses
  page.on('response', async (response) => {
    const url = response.url();
    const method = response.request().method();
    const status = response.status();
    if (!url.includes('favicon') && !url.includes('.css') && !url.includes('.js') && !url.includes('.png') && !url.includes('.jpg')) {
      console.log(`[Response] ${method} ${status} ${url}`);
    }
    if (url.includes('/login') && method === 'POST') {
      try {
        const body = await response.json();
        console.log('[Login Response JSON]', JSON.stringify(body));
      } catch (e) {}
    }
  });

  console.log('Navigating to login page...');
  await page.goto('http://localhost:8080/login');

  await page.fill('#login-email', 'demo.employer@example.com');
  await page.fill('#login-pass', 'Password123!');
  
  console.log('Submitting login form...');
  await page.click('#login-btn');

  await page.waitForTimeout(4000);
  console.log('Current URL after login:', page.url());
  console.log('Page title:', await page.title());

  await browser.close();
})();
