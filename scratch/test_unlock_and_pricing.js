const { chromium } = require('playwright');

(async () => {
    console.log('--- STARTING PLAYWRIGHT END-TO-END TEST ---');
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();
    const page = await context.newPage();

    page.on('console', msg => console.log(`[Browser Console ${msg.type()}] ${msg.text()}`));
    page.on('pageerror', err => console.error(`[Browser Page Error]`, err));

    page.on('response', async (response) => {
        const url = response.url();
        const method = response.request().method();
        const status = response.status();
        if (url.includes('/unlock') || url.includes('/initiate-payment')) {
            console.log(`[Response] ${method} ${status} ${url}`);
            try {
                const body = await response.json();
                console.log('[Response JSON]', JSON.stringify(body));
            } catch (e) {}
        }
    });

    // 1. LOGIN
    console.log('\nStep 1: Navigating to login...');
    await page.goto('http://localhost:8080/login');
    await page.fill('#login-email', 'demo.employer@example.com');
    await page.fill('#login-pass', 'Password123!');
    await page.click('#login-btn');
    await page.waitForTimeout(3000);
    console.log('Current URL after login:', page.url());

    // 2. CHECK PRICING PAGE
    console.log('\nStep 2: Navigating to Pricing & Plans...');
    await page.goto('http://localhost:8080/employer/pricing');
    await page.waitForTimeout(1000);
    const noticeText = await page.innerText('.notice');
    console.log('Notice text on pricing page:', noticeText.trim());

    // 3. CHECK CANDIDATES PAGE
    console.log('\nStep 3: Navigating to Candidates Search...');
    await page.goto('http://localhost:8080/employer/candidates');
    await page.waitForTimeout(1000);

    // 4. CLICK UNLOCK BUTTON ON CANDIDATE
    console.log('\nStep 4: Clicking unlock on candidate card...');
    const unlockButtons = await page.$$('[data-unlock-id]');
    console.log(`Found ${unlockButtons.length} candidates with unlock buttons.`);

    if (unlockButtons.length > 0) {
        await unlockButtons[0].click();
        await page.waitForTimeout(500);

        const isModalVisible = await page.isVisible('#unlock-scrim');
        console.log('Modal visible:', isModalVisible);

        const walletWarn = await page.isVisible('#unlock-warn');
        if (walletWarn) {
            const warnText = await page.innerText('#unlock-warn');
            console.log('Unlock modal warning text:', warnText.trim());
        }

        // Test clicking "Pay with Paystack (₦5,000)"
        console.log('\nStep 5: Testing "Pay with Paystack" button...');
        const paystackBtn = await page.$('#unlock-paystack-btn');
        if (paystackBtn) {
            console.log('Clicking Paystack button...');
            await paystackBtn.click();
            await page.waitForTimeout(2000);
            console.log('Paystack button text after click:', await paystackBtn.innerText());
        }

        // Test clicking "Use Wallet" button if present
        const confirmWalletBtn = await page.$('#unlock-confirm');
        if (confirmWalletBtn) {
            console.log('\nStep 6: Testing "Use Wallet" button...');
            await confirmWalletBtn.click();
            await page.waitForTimeout(2000);
            console.log('Wallet button text after click:', await confirmWalletBtn.innerText());
        }
    } else {
        console.log('No locked candidate buttons found on page. Checking candidate view page...');
        await page.goto('http://localhost:8080/employer/candidates/view/1');
        await page.waitForTimeout(1000);
        const detailUnlockBtn = await page.$('[data-unlock]');
        if (detailUnlockBtn) {
            console.log('Found unlock button on candidate detail page. Clicking...');
            await detailUnlockBtn.click();
            await page.waitForTimeout(500);
            const modalVis = await page.isVisible('#unlock-scrim');
            console.log('Modal visible on detail page:', modalVis);
            const confirmBtn = await page.$('#confirm-unlock-btn');
            if (confirmBtn) {
                console.log('Clicking confirm-unlock-btn...');
                await confirmBtn.click();
                await page.waitForTimeout(2000);
                console.log('Button text after click:', await confirmBtn.innerText());
            }
        }
    }

    await browser.close();
    console.log('\n--- TEST COMPLETE ---');
})();
