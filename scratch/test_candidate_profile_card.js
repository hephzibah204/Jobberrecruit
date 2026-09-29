const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

async function testCandidateProfileCard() {
  const browser = await chromium.launch({ headless: true });
  const outputDir = path.join(__dirname, 'screenshots_profile_card');
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  // 1. Mobile view test (390x844)
  console.log('=== Testing Candidate Dashboard Profile Card on Mobile ===');
  const mobileContext = await browser.newContext({
    viewport: { width: 390, height: 844 },
    isMobile: true,
    hasTouch: true
  });
  const mobilePage = await mobileContext.newPage();

  await mobilePage.goto('http://localhost:8080/logout');
  await mobilePage.goto('http://localhost:8080/login');
  await mobilePage.locator('#login-email').fill('demo.candidate@example.com');
  await mobilePage.locator('#login-pass').fill('Password123');
  await mobilePage.locator('#login-btn').click();
  await mobilePage.waitForURL('**/candidate/dashboard', { timeout: 15000 });

  // Scroll to Finish Your Profile card
  const profileCard = mobilePage.locator('.tri .card').first();
  await profileCard.scrollIntoViewIfNeeded();
  await mobilePage.waitForTimeout(600);

  await mobilePage.screenshot({ path: path.join(outputDir, '01_candidate_profile_card_mobile.png'), fullPage: false });

  // 2. Desktop view test (1280x800)
  console.log('\n=== Testing Candidate Dashboard Profile Card on Desktop ===');
  const desktopContext = await browser.newContext({
    viewport: { width: 1280, height: 800 }
  });
  const desktopPage = await desktopContext.newPage();

  await desktopPage.goto('http://localhost:8080/login');
  await desktopPage.locator('#login-email').fill('demo.candidate@example.com');
  await desktopPage.locator('#login-pass').fill('Password123');
  await desktopPage.locator('#login-btn').click();
  await desktopPage.waitForURL('**/candidate/dashboard', { timeout: 15000 });

  const desktopCard = desktopPage.locator('.tri .card').first();
  await desktopCard.scrollIntoViewIfNeeded();
  await desktopPage.waitForTimeout(600);

  await desktopPage.screenshot({ path: path.join(outputDir, '02_candidate_profile_card_desktop.png'), fullPage: false });

  console.log('Screenshots saved to scratch/screenshots_profile_card/');
  await browser.close();
}

testCandidateProfileCard().catch(console.error);
