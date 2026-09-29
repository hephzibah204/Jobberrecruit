const { chromium } = require('playwright');
const execSync = require('child_process').execSync;

(async () => {
    console.log('--- TESTING WALLET UNLOCK END-TO-END ---');

    execSync(`php -r "
        \\\$pdo = new PDO('mysql:host=127.0.0.1;dbname=jobberrecruit;charset=utf8mb4', 'root', '');
        \\\$pdo->exec('INSERT INTO wallets (user_id, balance) VALUES (36, 10000.00) ON DUPLICATE KEY UPDATE balance = 10000.00');
        \\\$pdo->exec('DELETE FROM candidate_unlocks WHERE employer_id = 13');
        \\\$pdo->exec('UPDATE employers SET unlimited_access = 0 WHERE id = 13');
        echo 'Wallet balance set to 10000 for user 36 and candidate unlocks cleared.\\n';
    "`);

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();
    const page = await context.newPage();

    page.on('console', msg => console.log(`[Browser Console ${msg.type()}] ${msg.text()}`));

    page.on('response', async (response) => {
        const url = response.url();
        if (url.includes('/unlock')) {
            console.log(`[Response] ${response.request().method()} ${response.status()} ${url}`);
            try {
                const body = await response.json();
                console.log('[Response JSON]', JSON.stringify(body));
            } catch (e) {}
        }
    });

    console.log('Logging in as demo.employer@example.com...');
    await page.goto('http://localhost:8080/login');
    await page.fill('#login-email', 'demo.employer@example.com');
    await page.fill('#login-pass', 'Password123!');
    await page.click('#login-btn');
    await page.waitForTimeout(2500);

    console.log('Navigating to Candidate Detail page (/employer/candidates/view/2)...');
    await page.goto('http://localhost:8080/employer/candidates/view/2');
    await page.waitForTimeout(1000);

    const detailUnlockBtn = await page.$('[data-unlock]');
    if (detailUnlockBtn) {
        console.log('Found unlock button on candidate detail page. Opening modal...');
        await detailUnlockBtn.click();
        await page.waitForTimeout(500);

        const confirmBtn = await page.$('#confirm-unlock-btn');
        if (confirmBtn) {
            console.log('Clicking #confirm-unlock-btn...');
            await confirmBtn.click();
            await page.waitForTimeout(3000);
        } else {
            console.log('No #confirm-unlock-btn found!');
        }
    } else {
        console.log('No unlock button found on candidate detail page!');
    }

    await browser.close();
    console.log('--- TEST COMPLETED ---');
})();
