const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  
  // Visit temp-login to establish session
  await page.goto('http://localhost:8080/temp-login');
  console.log('Login page URL:', page.url());
  console.log('Login body text:', await page.$eval('body', el => el.innerText));
  
  // Now visit resumes page
  await page.goto('http://localhost:8080/candidate/resumes');
  console.log('Resumes page URL:', page.url());
  console.log('Resumes page title:', await page.title());
  
  const bodyText = await page.$eval('body', el => el.innerText);
  console.log('Body Text Snippet:', bodyText.substring(0, 500));
  
  await browser.close();
})();
