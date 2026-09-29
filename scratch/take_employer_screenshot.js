const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch({
    headless: "new",
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });
  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 960 });

  try {
    console.log('Navigating to login page...');
    await page.goto('http://localhost:8080/login', { waitUntil: 'networkidle2' });

    console.log('Attempting login...');
    await page.type('input[name="email"]', 'employer@test.com');
    await page.type('input[name="password"]', 'Password123!');
    
    await Promise.all([
      page.click('button[type="submit"]'),
      page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 15000 })
    ]);

    const url = page.url();
    console.log('Logged in. Current URL:', url);

    console.log('Navigating to employer dashboard...');
    await page.goto('http://localhost:8080/employer', { waitUntil: 'networkidle2' });

    // Wait a brief moment for dynamic charts or assets to load
    await new Promise(resolve => setTimeout(resolve, 2000));

    const path = 'C:\\Users\\hephz\\.gemini\\antigravity\\brain\\64f756de-75ca-4670-a966-2feaed2f6859\\employer_dashboard_preview.png';
    await page.screenshot({ path: path, fullPage: true });
    console.log('Screenshot successfully saved to: ' + path);
  } catch (error) {
    console.error('Error during execution:', error);
  } finally {
    await browser.close();
  }
})();
