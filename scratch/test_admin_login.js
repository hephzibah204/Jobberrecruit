const { chromium } = require('playwright');
const path = require('path');

async function testAdminLoginAndDashboard() {
  const browser = await chromium.launch({ headless: true });
  const outputDir = path.join(__dirname, 'screenshots_audit');

  // Desktop
  console.log('=== Testing Admin Dashboard on Desktop ===');
  const desktopContext = await browser.newContext({ viewport: { width: 1280, height: 800 } });
  const desktopPage = await desktopContext.newPage();

  await desktopPage.goto('http://localhost:8080/logout');
  await desktopPage.goto('http://localhost:8080/admin/login');
  await desktopPage.locator('#signin-email').fill('admin@jobberrecruit.com');
  await desktopPage.locator('#signin-password').fill('AdminPassword123');
  await desktopPage.locator('#loginBtn').click();
  await desktopPage.waitForURL('**/admin/dashboard', { timeout: 15000 });
  console.log('Admin Desktop URL:', desktopPage.url());

  await desktopPage.screenshot({ path: path.join(outputDir, '05_admin_dashboard_desktop.png'), fullPage: true });
  await desktopContext.close();

  // Mobile
  console.log('\n=== Testing Admin Dashboard on Mobile ===');
  const mobileContext = await browser.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });
  const mobilePage = await mobileContext.newPage();

  await mobilePage.goto('http://localhost:8080/admin/login');
  await mobilePage.locator('#signin-email').fill('admin@jobberrecruit.com');
  await mobilePage.locator('#signin-password').fill('AdminPassword123');
  await mobilePage.locator('#loginBtn').click();
  await mobilePage.waitForURL('**/admin/dashboard', { timeout: 15000 });
  console.log('Admin Mobile URL:', mobilePage.url());

  await mobilePage.screenshot({ path: path.join(outputDir, '06_admin_dashboard_mobile.png'), fullPage: true });
  await mobileContext.close();

  console.log('\nAdmin Dashboard tested successfully!');
  await browser.close();
}

testAdminLoginAndDashboard().catch(console.error);
