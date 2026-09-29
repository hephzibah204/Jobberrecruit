const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.goto('http://localhost:8080/temp-login');
  await page.goto('http://localhost:8080/candidate/resumes/build');
  const content = await page.content();
  const lines = content.split('\n');
  for (let i = 1735; i <= 1765; i++) {
    console.log(i + ': ' + lines[i - 1]);
  }
  await browser.close();
})();
