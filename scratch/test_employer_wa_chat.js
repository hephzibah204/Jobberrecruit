const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

async function testEmployerWhatsAppChat() {
  const browser = await chromium.launch({ headless: true });
  const outputDir = path.join(__dirname, 'screenshots_employer_wa');
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  // 1. Desktop Test
  console.log('=== 1. Testing Employer Dashboard on Desktop (1280x800) ===');
  const desktopContext = await browser.newContext({
    viewport: { width: 1280, height: 800 }
  });
  const desktopPage = await desktopContext.newPage();
  
  await desktopPage.goto('http://localhost:8080/logout');
  await desktopPage.goto('http://localhost:8080/login');
  await desktopPage.locator('#login-email').fill('demo.employer@example.com');
  await desktopPage.locator('#login-pass').fill('Password123');
  await desktopPage.locator('#login-btn').click();
  await desktopPage.waitForURL('**/employer/dashboard', { timeout: 15000 });

  const desktopChatbot = await desktopPage.locator('#chatbot-toggle').count();
  console.log('Desktop Chatbot Toggle count (should be 0):', desktopChatbot);

  const desktopWa = desktopPage.locator('.emp-wa-float');
  console.log('Desktop WhatsApp button visible?', await desktopWa.isVisible());
  console.log('Desktop WhatsApp href:', await desktopWa.getAttribute('href'));
  console.log('Desktop WhatsApp label text:', await desktopWa.innerText());

  await desktopPage.screenshot({ path: path.join(outputDir, '01_employer_dashboard_desktop_wa.png'), fullPage: false });
  await desktopContext.close();

  // 2. Mobile Test
  console.log('\n=== 2. Testing Employer Dashboard on Mobile (390x844) ===');
  const mobileContext = await browser.newContext({
    viewport: { width: 390, height: 844 },
    isMobile: true,
    hasTouch: true
  });
  const mobilePage = await mobileContext.newPage();

  await mobilePage.goto('http://localhost:8080/login');
  await mobilePage.locator('#login-email').fill('demo.employer@example.com');
  await mobilePage.locator('#login-pass').fill('Password123');
  await mobilePage.locator('#login-btn').click();
  await mobilePage.waitForURL('**/employer/dashboard', { timeout: 15000 });

  const mobileChatbot = await mobilePage.locator('#chatbot-toggle').count();
  console.log('Mobile Chatbot Toggle count (should be 0):', mobileChatbot);

  const mobileWa = mobilePage.locator('.emp-wa-float');
  console.log('Mobile WhatsApp button visible?', await mobileWa.isVisible());
  console.log('Mobile WhatsApp href:', await mobileWa.getAttribute('href'));

  await mobilePage.screenshot({ path: path.join(outputDir, '02_employer_dashboard_mobile_wa.png'), fullPage: false });
  await mobileContext.close();

  console.log('\nAll tests completed successfully!');
  await browser.close();
}

testEmployerWhatsAppChat().catch(console.error);
