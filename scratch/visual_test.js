const { chromium } = require('playwright');
const path = require('path');

const artifactDir = 'C:\\Users\\hephz\\.gemini\\antigravity\\brain\\0b64dfb4-8adc-47f5-b10f-9ed1f47d5b44';

(async () => {
    console.log('--- STARTING VISUAL TESTING ---');
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({
        viewport: { width: 1280, height: 800 }
    });
    const page = await context.newPage();

    // 1. LOGIN
    console.log('Navigating to login page...');
    await page.goto('http://localhost:8080/login');
    await page.fill('#login-email', 'demo.employer@example.com');
    await page.fill('#login-pass', 'Password123!');
    await page.click('#login-btn');
    await page.waitForTimeout(3000);

    // 2. DASHBOARD SCREENSHOT
    console.log('Capturing Employer Dashboard screenshot...');
    await page.screenshot({ path: path.join(artifactDir, 'employer_dashboard_visual.png'), fullPage: false });

    // 3. PRICING & BILLING SCREENSHOT
    console.log('Capturing Pricing & Plans screenshot...');
    await page.goto('http://localhost:8080/employer/pricing');
    await page.waitForTimeout(1500);
    await page.screenshot({ path: path.join(artifactDir, 'pricing_unlimited_notice_visual.png'), fullPage: false });

    // 4. CANDIDATES SEARCH SCREENSHOT
    console.log('Capturing Candidates Search screenshot...');
    await page.goto('http://localhost:8080/employer/candidates');
    await page.waitForTimeout(1500);
    await page.screenshot({ path: path.join(artifactDir, 'candidates_search_visual.png'), fullPage: false });

    // 5. UNLOCK MODAL SCREENSHOT
    console.log('Opening Candidate Unlock modal...');
    const unlockButtons = await page.$$('[data-unlock-id]');
    if (unlockButtons.length > 0) {
        await unlockButtons[0].click();
        await page.waitForTimeout(1000);
        await page.screenshot({ path: path.join(artifactDir, 'candidate_unlock_modal_visual.png'), fullPage: false });
    }

    await browser.close();
    console.log('--- VISUAL TESTING COMPLETED ---');
})();
