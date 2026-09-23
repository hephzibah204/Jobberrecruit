const { chromium } = require('playwright');

async function testNotifDropdown() {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({
    viewport: { width: 390, height: 844 },
    isMobile: true,
    hasTouch: true
  });
  const page = await context.newPage();

  await page.goto('http://localhost:8080/login');
  await page.locator('#login-email').fill('demo.candidate@example.com');
  await page.locator('#login-pass').fill('Password123');
  await page.locator('#login-btn').click();
  await page.waitForURL('**/candidate/dashboard', { timeout: 15000 });

  const info = await page.evaluate(() => {
    const main = document.querySelector('main.content');
    const matched = [];
    for (const sheet of document.styleSheets) {
      try {
        for (const rule of sheet.cssRules) {
          if (rule.selectorText && main.matches(rule.selectorText)) {
            if (rule.style.transform || rule.style.zIndex || rule.style.overflow) {
              matched.push({
                href: sheet.href,
                selector: rule.selectorText,
                transform: rule.style.transform,
                zIndex: rule.style.zIndex,
                overflow: rule.style.overflow
              });
            }
          }
        }
      } catch (e) {}
    }
    return matched;
  });

  console.log('Matched rules on main.content:', info);
  await browser.close();
}

testNotifDropdown().catch(console.error);
