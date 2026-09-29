const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');
const vm = require('vm');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const ctx     = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page    = await ctx.newPage();

  // Login
  await page.goto('http://localhost:8080/login');
  await page.fill('input[type="email"], #email', 'candidate@test.com');
  await page.fill('input[type="password"], #password', 'Password123!');
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle' }),
    page.click('button[type="submit"]')
  ]);

  // Navigate to builder
  await page.goto('http://localhost:8080/candidate/resumes/build', { waitUntil: 'networkidle' });
  const html = await page.content();
  const htmlPath = path.join(__dirname, 'live_rendered.html');
  fs.writeFileSync(htmlPath, html, 'utf8');
  console.log('Saved rendered HTML to:', htmlPath);

  // Extract scripts from rendered HTML
  const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gi;
  let match;
  let idx = 0;
  while ((match = scriptRegex.exec(html)) !== null) {
    const code = match[1];
    if (!code.trim()) continue;
    try {
      new vm.Script(code, { filename: `inline_script_${idx}.js` });
      console.log(`inline_script_${idx}.js: OK`);
    } catch(e) {
      console.log(`inline_script_${idx}.js SYNTAX ERROR:`, e.message);
      const lines = code.split('\n');
      // find line
      console.log('Code snippet near error:\n');
      lines.forEach((l, i) => {
        if (l.includes('}') || l.includes('function') || i < 50) {
          // console.log((i+1) + ': ' + l);
        }
      });
      // Test line by line
      let accumulated = '';
      lines.forEach((line, lNo) => {
        accumulated += line + '\n';
        try {
          // check if valid syntax up to here (or snippet)
        } catch(err) {}
      });
      // Print first 50 lines of failing script
      console.log('=== Failing Script Start ===');
      console.log(lines.slice(0, 30).join('\n'));
      console.log('... Total lines in script:', lines.length);
      console.log('=== Failing Script End ===');
    }
    idx++;
  }

  await browser.close();
})();
