const puppeteer = require('puppeteer-core');
const fs = require('fs');
const path = require('path');

(async () => {
    console.log("Starting browser...");
    const chromePaths = [
        'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe'
    ];
    let executablePath = null;
    for (const p of chromePaths) {
        if (fs.existsSync(p)) { executablePath = p; break; }
    }
    
    const browser = await puppeteer.launch({ executablePath, headless: true });
    const page = await browser.newPage();
    
    page.on('console', msg => {
        console.log(`[PAGE CONSOLE] [${msg.type()}] ${msg.text()}`);
    });
    
    page.on('pageerror', err => {
        console.error('[PAGE ERROR]', err.stack);
    });
    
    page.on('requestfailed', request => {
        console.log(`[REQUEST FAILED] ${request.url()} - ${request.failure() ? request.failure().errorText : 'unknown'}`);
    });

    console.log("Navigating...");
    await page.goto('http://localhost:8081/debug-login?auto_test=1', { waitUntil: 'networkidle2' });
    
    console.log("URL:", page.url());
    
    const search = await page.evaluate(() => window.location.search);
    console.log("window.location.search:", search);
    
    // Check if the script exists on page
    const hasScript = await page.evaluate(() => {
        const scripts = Array.from(document.querySelectorAll('script'));
        return scripts.map(s => s.innerText).some(text => text.includes('AUTO-TEST'));
    });
    console.log("Page has AUTO-TEST script:", hasScript);
    
    // Let's print the last 100 lines of the page script if it's there
    const scriptContent = await page.evaluate(() => {
        const scripts = Array.from(document.querySelectorAll('script'));
        const autoTestScript = scripts.find(s => s.innerText.includes('AUTO-TEST'));
        return autoTestScript ? autoTestScript.innerText : 'Not found';
    });
    
    console.log("=== AUTO-TEST Script Content length ===", scriptContent.length);
    if (scriptContent !== 'Not found') {
        const lines = scriptContent.split('\n');
        console.log("Last 20 lines of script:");
        console.log(lines.slice(-40).join('\n'));
    }
    
    await browser.close();
})();
