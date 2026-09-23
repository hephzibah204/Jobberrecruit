const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  
  page.on('requestfailed', req => {
    console.log('Failed request:', req.url(), '-> Error:', req.failure().errorText);
  });

  console.log('Navigating to homepage...');
  await page.goto('http://localhost:8085/', { waitUntil: 'networkidle' });
  await browser.close();
})();
