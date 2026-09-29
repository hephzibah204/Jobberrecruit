const { chromium } = require('playwright');

(async () => {
    console.log('--- DIRECT API TEST FOR WALLET UNLOCK ---');

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();
    const page = await context.newPage();

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

    console.log('Logging in...');
    await page.goto('http://localhost:8080/login');
    await page.fill('#login-email', 'demo.employer@example.com');
    await page.fill('#login-pass', 'Password123!');
    await page.click('#login-btn');
    await page.waitForTimeout(2000);

    console.log('Executing unlock POST via fetch on page...');
    const result = await page.evaluate(async () => {
        const formData = new FormData();
        formData.append('candidate_id', 2);
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
        formData.append('csrf_test_name', token);

        const res = await fetch('http://localhost:8080/employer/candidates/unlock', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token
            },
            body: formData
        });
        return await res.json();
    });

    console.log('RESULT OF UNLOCK API:', result);

    await browser.close();
    console.log('--- TEST FINISHED ---');
})();
