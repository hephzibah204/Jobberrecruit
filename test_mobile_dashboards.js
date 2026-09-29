const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

async function testMobileDashboards() {
  const browser = await chromium.launch({ headless: true });
  
  // Mobile device viewport (iPhone 12 / Modern mobile: 390x844)
  const context = await browser.newContext({
    viewport: { width: 390, height: 844 },
    userAgent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1',
    isMobile: true,
    hasTouch: true
  });
  
  const page = await context.newPage();
  const outputDir = path.join(__dirname, 'scratch', 'screenshots_mobile_test');
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  console.log('--- 1. Testing Candidate Dashboard on Mobile ---');
  await page.goto('http://localhost:8080/login');
  await page.locator('#login-email').fill('demo.candidate@example.com');
  await page.locator('#login-pass').fill('Password123');
  await page.locator('#login-btn').click();
  await page.waitForURL('**/candidate/dashboard', { timeout: 15000 });
  await page.screenshot({ path: path.join(outputDir, '01_candidate_dashboard_mobile.png'), fullPage: false });

  // Check topbar items on mobile
  const topbar = page.locator('.emp-topbar');
  console.log('Candidate topbar HTML on mobile:', await topbar.innerHTML());

  // Check notification icon
  const notifIcon = page.locator('.tb-icon[aria-label="Notifications"], a[href*="notifications"]');
  console.log('Candidate notif icon count:', await notifIcon.count());
  if (await notifIcon.count() > 0) {
    console.log('Candidate notif visible?', await notifIcon.first().isVisible());
    console.log('Candidate notif href:', await notifIcon.first().getAttribute('href'));
    console.log('Candidate notif boundingBox:', await notifIcon.first().boundingBox());
  }

  // Click notification icon
  if (await notifIcon.count() > 0 && await notifIcon.first().isVisible()) {
    console.log('Clicking candidate notif icon...');
    await notifIcon.first().click();
    await page.waitForTimeout(1500);
    console.log('URL after notif click:', page.url());
    await page.screenshot({ path: path.join(outputDir, '02_candidate_after_notif_click.png'), fullPage: false });
  }

  // Go back to candidate dashboard
  await page.goto('http://localhost:8080/candidate/dashboard');
  
  // Check logo on mobile
  const logos = page.locator('img[alt*="JobberRecruit"], .sb-logo-img, .header-logo, .auth-logo, a[aria-label*="home"]');
  console.log('Candidate logos count on mobile:', await logos.count());
  for (let i = 0; i < await logos.count(); i++) {
    const el = logos.nth(i);
    console.log(`Logo ${i}: tag=${await el.evaluate(e => e.tagName)}, visible=${await el.isVisible()}, box=${JSON.stringify(await el.boundingBox())}`);
  }

  console.log('\n--- 2. Testing Employer Dashboard on Mobile ---');
  await page.goto('http://localhost:8080/login');
  await page.locator('#login-email').fill('demo.employer@example.com');
  await page.locator('#login-pass').fill('Password123');
  await page.locator('#login-btn').click();
  await page.waitForURL('**/employer/dashboard', { timeout: 15000 });
  await page.screenshot({ path: path.join(outputDir, '03_employer_dashboard_mobile.png'), fullPage: false });

  const empTopbar = page.locator('.emp-topbar');
  console.log('Employer topbar HTML on mobile:', await empTopbar.innerHTML());

  const empNotifIcon = page.locator('.tb-icon[aria-label="Notifications"], a[href*="notifications"]');
  console.log('Employer notif icon count:', await empNotifIcon.count());
  if (await empNotifIcon.count() > 0) {
    console.log('Employer notif visible?', await empNotifIcon.first().isVisible());
    console.log('Employer notif href:', await empNotifIcon.first().getAttribute('href'));
    console.log('Employer notif boundingBox:', await empNotifIcon.first().boundingBox());
  }

  // Click employer notification icon
  if (await empNotifIcon.count() > 0 && await empNotifIcon.first().isVisible()) {
    console.log('Clicking employer notif icon...');
    await empNotifIcon.first().click();
    await page.waitForTimeout(1500);
    console.log('URL after employer notif click:', page.url());
    await page.screenshot({ path: path.join(outputDir, '04_employer_after_notif_click.png'), fullPage: false });
  }

  await browser.close();
}

testMobileDashboards().catch(console.error);
