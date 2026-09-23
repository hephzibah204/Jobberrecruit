const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

async function runComprehensiveAudit() {
  const browser = await chromium.launch({ headless: true });
  const outputDir = path.join(__dirname, 'screenshots_audit');
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  console.log('=== AUDIT 1: Candidate Dashboard (Desktop & Mobile) ===');
  // Desktop
  {
    const ctx = await browser.newContext({ viewport: { width: 1280, height: 800 } });
    const page = await ctx.newPage();
    await page.goto('http://localhost:8080/logout');
    await page.goto('http://localhost:8080/login');
    await page.locator('#login-email').fill('demo.candidate@example.com');
    await page.locator('#login-pass').fill('Password123');
    await page.locator('#login-btn').click();
    await page.waitForLoadState('networkidle');
    console.log('Candidate logged in. Current URL:', page.url());

    const finishCard = page.locator('text=Finish Your Profile').locator('xpath=ancestor::div[contains(@class, "card")][1]');
    console.log('Finish Your Profile card found:', await finishCard.count() > 0);
    if (await finishCard.count() > 0) {
      const pill = await finishCard.locator('.pill').innerText();
      console.log('Profile Pill:', pill.trim());
      const tasks = await finishCard.locator('.task').allInnerTexts();
      console.log('Tasks in checklist (' + tasks.length + ' total):');
      tasks.forEach((t, i) => console.log(`  ${i+1}. ${t.replace(/\s+/g, ' ').trim()}`));
    }

    // Check logo
    const logo = page.locator('a[href*="candidate/dashboard"], .topbar a.logo').first();
    console.log('Candidate Logo visible:', await logo.isVisible());

    // Check notifications
    const notifBtn = page.locator('#notif-toggle, .notif-btn, button:has-text("Notifications"), [aria-label*="otif"]').first();
    if (await notifBtn.isVisible()) {
      await notifBtn.click();
      await page.waitForTimeout(400);
      const notifMenu = page.locator('.notif-dropdown, .notif-menu, #notif-menu, .dropdown-menu.show').first();
      console.log('Candidate Notifications dropdown opens:', await notifMenu.isVisible());
    }

    await page.screenshot({ path: path.join(outputDir, '01_candidate_desktop.png'), fullPage: true });
    await ctx.close();
  }

  // Mobile
  {
    const ctx = await browser.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });
    const page = await ctx.newPage();
    await page.goto('http://localhost:8080/login');
    await page.locator('#login-email').fill('demo.candidate@example.com');
    await page.locator('#login-pass').fill('Password123');
    await page.locator('#login-btn').click();
    await page.waitForLoadState('networkidle');
    console.log('Candidate Mobile URL:', page.url());

    const logo = page.locator('a[href*="candidate/dashboard"], .topbar a.logo').first();
    console.log('Candidate Mobile Logo visible:', await logo.isVisible());

    const notifBtn = page.locator('#notif-toggle, .notif-btn, button:has-text("Notifications"), [aria-label*="otif"]').first();
    if (await notifBtn.isVisible()) {
      await notifBtn.click();
      await page.waitForTimeout(400);
      const notifMenu = page.locator('.notif-dropdown, .notif-menu, #notif-menu, .dropdown-menu.show').first();
      console.log('Candidate Mobile Notifications dropdown opens:', await notifMenu.isVisible());
    }

    await page.screenshot({ path: path.join(outputDir, '02_candidate_mobile.png'), fullPage: true });
    await ctx.close();
  }

  console.log('\n=== AUDIT 2: Employer Dashboard (Desktop & Mobile) ===');
  // Employer Desktop
  {
    const ctx = await browser.newContext({ viewport: { width: 1280, height: 800 } });
    const page = await ctx.newPage();
    await page.goto('http://localhost:8080/logout');
    await page.goto('http://localhost:8080/login');
    await page.locator('#login-email').fill('demo.employer@example.com');
    await page.locator('#login-pass').fill('Password123');
    await page.locator('#login-btn').click();
    await page.waitForLoadState('networkidle');
    console.log('Employer logged in. Current URL:', page.url());

    // Check Chatbot count (MUST be 0)
    const chatbot = await page.locator('#chatbot-toggle, #chatbot-window, .chatbot-container').count();
    console.log('Employer Chatbot count (must be 0):', chatbot);

    // Check WhatsApp Floating button
    const wa = page.locator('.emp-wa-float');
    console.log('Employer WhatsApp Button visible:', await wa.isVisible());
    console.log('Employer WhatsApp Href:', await wa.getAttribute('href'));
    console.log('Employer WhatsApp Label:', (await wa.innerText()).replace(/\s+/g, ' ').trim());

    // Check Logo & Notifications
    const logo = page.locator('.tb-logo, .emp-logo, a[href*="employer/dashboard"]').first();
    console.log('Employer Logo visible:', await logo.isVisible());

    const notifBtn = page.locator('.tb-notif, #notif-toggle, [aria-label*="otif"]').first();
    if (await notifBtn.isVisible()) {
      await notifBtn.click();
      await page.waitForTimeout(400);
      const notifMenu = page.locator('.notif-drop, .notif-dropdown, .dropdown-menu.show').first();
      console.log('Employer Notifications dropdown opens:', await notifMenu.isVisible());
    }

    await page.screenshot({ path: path.join(outputDir, '03_employer_desktop.png'), fullPage: true });
    await ctx.close();
  }

  // Employer Mobile
  {
    const ctx = await browser.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });
    const page = await ctx.newPage();
    await page.goto('http://localhost:8080/login');
    await page.locator('#login-email').fill('demo.employer@example.com');
    await page.locator('#login-pass').fill('Password123');
    await page.locator('#login-btn').click();
    await page.waitForLoadState('networkidle');
    console.log('Employer Mobile URL:', page.url());

    // Check Chatbot
    const chatbot = await page.locator('#chatbot-toggle, #chatbot-window').count();
    console.log('Employer Mobile Chatbot count (must be 0):', chatbot);

    // Check WhatsApp
    const wa = page.locator('.emp-wa-float');
    console.log('Employer Mobile WhatsApp visible:', await wa.isVisible());

    // Check Mobile Logo & Notifications
    const logo = page.locator('.tb-logo, .emp-logo, a[href*="employer/dashboard"]').first();
    console.log('Employer Mobile Logo visible:', await logo.isVisible());

    const notifBtn = page.locator('.tb-notif, #notif-toggle').first();
    if (await notifBtn.isVisible()) {
      await notifBtn.click();
      await page.waitForTimeout(400);
      const notifMenu = page.locator('.notif-drop, .notif-dropdown').first();
      console.log('Employer Mobile Notifications dropdown opens:', await notifMenu.isVisible());
    }

    await page.screenshot({ path: path.join(outputDir, '04_employer_mobile.png'), fullPage: true });
    await ctx.close();
  }

  console.log('\n=== AUDIT 3: Admin Dashboard (Desktop & Mobile) ===');
  // Admin Desktop
  {
    const ctx = await browser.newContext({ viewport: { width: 1280, height: 800 } });
    const page = await ctx.newPage();
    await page.goto('http://localhost:8080/logout');
    await page.goto('http://localhost:8080/admin/login');
    await page.locator('input[type="email"], #email, input[name="email"]').fill('admin@jobberrecruit.com');
    await page.locator('input[type="password"], #password, input[name="password"]').fill('AdminPassword123');
    await page.locator('button[type="submit"]').click();
    await page.waitForLoadState('networkidle');
    console.log('Admin logged in. Current URL:', page.url());

    await page.screenshot({ path: path.join(outputDir, '05_admin_desktop.png'), fullPage: true });
    await ctx.close();
  }

  // Admin Mobile
  {
    const ctx = await browser.newContext({ viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });
    const page = await ctx.newPage();
    await page.goto('http://localhost:8080/admin/login');
    await page.locator('input[type="email"], #email, input[name="email"]').fill('admin@jobberrecruit.com');
    await page.locator('input[type="password"], #password, input[name="password"]').fill('AdminPassword123');
    await page.locator('button[type="submit"]').click();
    await page.waitForLoadState('networkidle');
    console.log('Admin Mobile URL:', page.url());

    await page.screenshot({ path: path.join(outputDir, '06_admin_mobile.png'), fullPage: true });
    await ctx.close();
  }

  console.log('\n====================================================');
  console.log('ALL AUDIT TESTS EXECUTED SUCCESSFULLY!');
  console.log('====================================================');

  await browser.close();
}

runComprehensiveAudit().catch(console.error);
