const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.goto('http://localhost:8080/temp-login');
  await page.goto('http://localhost:8080/candidate/resumes/build');
  const funcStr = await page.evaluate(() => refreshAts.toString());
  const lines = funcStr.split('\n');
  lines.forEach((line, idx) => {
    if (line.includes('.filter')) {
      console.log(`Line ${idx + 1}: ${line.trim()}`);
    }
  });
  await browser.close();
})();
