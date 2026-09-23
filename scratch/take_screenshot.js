const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch({
    headless: "new",
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });
  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 960 });

  console.log('Navigating to candidate pricing page...');
  try {
    await page.goto('http://localhost:8080/candidate/subscription/pricing', {
      waitUntil: 'networkidle2',
      timeout: 15000
    });
    
    // Take a screenshot of the main body
    const path = 'C:\\Users\\hephz\\.gemini\\antigravity\\brain\\64f756de-75ca-4670-a966-2feaed2f6859\\pricing_preview.png';
    await page.screenshot({ path: path, fullPage: true });
    console.log('Screenshot successfully saved to: ' + path);
  } catch (error) {
    console.error('Error taking screenshot:', error);
  } finally {
    await browser.close();
  }
})();
