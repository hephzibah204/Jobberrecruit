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
  const outputDir = path.join(__dirname, 'scratch', 'screenshots_mobile_verified');
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  console.log('=== 1. Testing Candidate Dashboard on Mobile ===');
  await page.goto('http://localhost:8080/logout');
  await page.goto('http://localhost:8080/login');
  await page.locator('#login-email').fill('demo.candidate@example.com');
  await page.locator('#login-pass').fill('Password123');
  await page.locator('#login-btn').click();
  await page.waitForURL('**/candidate/dashboard', { timeout: 15000 });
  await page.screenshot({ path: path.join(outputDir, '01_candidate_dashboard_mobile.png'), fullPage: false });

  // 1a. Test Mobile Logo in Candidate Topbar
  const candMobLogo = page.locator('.emp-topbar .tb-mob-logo');
  console.log('Candidate topbar mob logo visible?', await candMobLogo.isVisible());
  if (await candMobLogo.isVisible()) {
    console.log('Candidate mob logo href:', await candMobLogo.getAttribute('href'));
    await candMobLogo.click();
    await page.waitForTimeout(1000);
    console.log('URL after candidate topbar logo click:', page.url());
  }

  // 1b. Test Notification Dropdown on Mobile
  const candNotifBtn = page.locator('.emp-topbar #notif-drop button.tb-icon');
  console.log('Candidate notif button visible?', await candNotifBtn.isVisible());
  await candNotifBtn.click();
  await page.waitForTimeout(600);
  const candNotifMenu = page.locator('.emp-topbar #notif-drop .tb-menu');
  console.log('Candidate notif menu visible after click?', await candNotifMenu.isVisible());
  await page.screenshot({ path: path.join(outputDir, '02_candidate_notif_dropdown_mobile.png'), fullPage: false });

  // 1c. Click "Open Notifications Center" inside dropdown
  const candNotifLink = page.locator('.emp-topbar #notif-drop .tb-menu a[href*="notifications"]').first();
  await candNotifLink.click();
  await page.waitForURL('**/candidate/notifications', { timeout: 10000 });
  console.log('URL after clicking notifications link:', page.url());
  await page.screenshot({ path: path.join(outputDir, '03_candidate_notifications_page_mobile.png'), fullPage: false });

  // 1d. Test Candidate Sidebar Logo
  await page.goto('http://localhost:8080/candidate/dashboard');
  const candHamburger = page.locator('#emp-hamburger');
  await candHamburger.click();
  await page.waitForTimeout(600);
  const candSidebarLogo = page.locator('.emp-sidebar .sb-head a');
  console.log('Candidate sidebar logo visible?', await candSidebarLogo.isVisible());
  console.log('Candidate sidebar logo href:', await candSidebarLogo.getAttribute('href'));
  await page.screenshot({ path: path.join(outputDir, '04_candidate_sidebar_open_mobile.png'), fullPage: false });


  console.log('\n=== 2. Testing Employer Dashboard on Mobile ===');
  await page.goto('http://localhost:8080/logout');
  await page.goto('http://localhost:8080/login');
  await page.locator('#login-email').fill('demo.employer@example.com');
  await page.locator('#login-pass').fill('Password123');
  await page.locator('#login-btn').click();
  await page.waitForURL('**/employer/dashboard', { timeout: 15000 });
  await page.screenshot({ path: path.join(outputDir, '05_employer_dashboard_mobile.png'), fullPage: false });

  // 2a. Test Mobile Logo in Employer Topbar
  const empMobLogo = page.locator('.emp-topbar .tb-mob-logo');
  console.log('Employer topbar mob logo visible?', await empMobLogo.isVisible());
  if (await empMobLogo.isVisible()) {
    console.log('Employer mob logo href:', await empMobLogo.getAttribute('href'));
    await empMobLogo.click();
    await page.waitForTimeout(1000);
    console.log('URL after employer topbar logo click:', page.url());
  }

  // 2b. Test Notification Dropdown on Mobile
  const empNotifBtn = page.locator('.emp-topbar #notif-drop button.tb-icon');
  console.log('Employer notif button visible?', await empNotifBtn.isVisible());
  await empNotifBtn.click();
  await page.waitForTimeout(600);
  const empNotifMenu = page.locator('.emp-topbar #notif-drop .tb-menu');
  console.log('Employer notif menu visible after click?', await empNotifMenu.isVisible());
  await page.screenshot({ path: path.join(outputDir, '06_employer_notif_dropdown_mobile.png'), fullPage: false });

  // 2c. Click "View all alerts" inside dropdown
  const empNotifLink = page.locator('.emp-topbar #notif-drop .tb-menu a[href*="notifications"]').first();
  await empNotifLink.click();
  await page.waitForURL('**/employer/notifications', { timeout: 10000 });
  console.log('URL after clicking employer notifications link:', page.url());
  await page.screenshot({ path: path.join(outputDir, '07_employer_notifications_page_mobile.png'), fullPage: false });

  // 2d. Test Employer Sidebar Logo
  await page.goto('http://localhost:8080/employer/dashboard');
  const empHamburger = page.locator('#emp-hamburger');
  await empHamburger.click();
  await page.waitForTimeout(600);
  const empSidebarLogo = page.locator('.emp-sidebar .sb-head a');
  console.log('Employer sidebar logo visible?', await empSidebarLogo.isVisible());
  console.log('Employer sidebar logo href:', await empSidebarLogo.getAttribute('href'));
  await page.screenshot({ path: path.join(outputDir, '08_employer_sidebar_open_mobile.png'), fullPage: false });


  console.log('\n=== 3. Testing Admin Dashboard on Mobile ===');
  await page.goto('http://localhost:8080/admin/logout');
  await page.goto('http://localhost:8080/admin/login');
  await page.locator('#signin-email').fill('admin@test.com');
  await page.locator('#signin-password').fill('Password123');
  await page.locator('#loginBtn').click();
  await page.waitForURL('**/admin/dashboard', { timeout: 15000 });
  console.log('Admin URL after login:', page.url());
  await page.screenshot({ path: path.join(outputDir, '09_admin_dashboard_mobile.png'), fullPage: false });

  // 3a. Admin Mobile Logo in Topbar
  const adminMobileLogo = page.locator('header a[href*="admin/dashboard"] img');
  console.log('Admin mobile logo count:', await adminMobileLogo.count());
  for (let i = 0; i < await adminMobileLogo.count(); i++) {
    const isVis = await adminMobileLogo.nth(i).isVisible();
    const src = await adminMobileLogo.nth(i).getAttribute('src');
    console.log(`Admin logo ${i}: src=${src}, visible=${isVis}`);
  }

  // 3b. Admin Notifications Dropdown
  const adminNotifBtn = page.locator('#mainHeaderNotification');
  console.log('Admin notif button visible?', await adminNotifBtn.isVisible());
  if (await adminNotifBtn.isVisible()) {
    await adminNotifBtn.click();
    await page.waitForTimeout(600);
    const adminNotifMenu = page.locator('.main-header-dropdown[aria-labelledby="mainHeaderNotification"]');
    console.log('Admin notif dropdown menu visible after click?', await adminNotifMenu.isVisible());
    await page.screenshot({ path: path.join(outputDir, '10_admin_notif_dropdown_mobile.png'), fullPage: false });
  }

  console.log('\nALL MOBILE TESTS COMPLETED SUCCESSFULLY!');
  await browser.close();
}

testMobileDashboards().catch(console.error);
