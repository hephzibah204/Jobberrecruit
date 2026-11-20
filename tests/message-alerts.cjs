const { chromium } = require('playwright');
const fs = require('fs');
const assert = require('node:assert/strict');
(async () => {
 const browser = await chromium.launch({headless:true});
 const page = await browser.newPage();
 let payload = {count:1,items:[{id:1,conversation_id:8,sender_name:'Ada Candidate',message:'Hello <script>bad()</script>',url:'https://qa.test/employer/messages/conversation/8'}]};
 await page.route('https://qa.test/employer/messages/alerts', route => route.fulfill({json:payload}));
 let html = fs.readFileSync('app/Views/messages/alerts.php','utf8').replace(/<\?php[\s\S]*?\?>/,'');
 html = html.replace(/<\?= json_encode\(base_url\(\$alertPrefix \. '\/messages\/alerts'\)\) \?>/g, '"https://qa.test/employer/messages/alerts"')
 .replace(/<\?= json_encode\(base_url\(\$alertPrefix \. '\/messages'\)\) \?>/g, '"https://qa.test/employer/messages"')
 .replace(/<\?= json_encode\('jr-message-alert-' \. \$alertUser->id\) \?>/g, '"test-user-1"')
 .replace(/<\?= base_url\(\$alertPrefix \. '\/messages'\) \?>/g, 'https://qa.test/employer/messages');
 await page.route('https://qa.test/', route => route.fulfill({contentType:'text/html',body:'<title>Dashboard</title><a class="sb-link" href="https://qa.test/employer/messages">Messages</a><div id="notif-drop"><button>Notifications</button><div class="tn-list"></div></div>'+html}));
 await page.goto('https://qa.test/');
 await page.locator('#jr-msg-toast:not([hidden])').waitFor();
 assert.equal(await page.locator('#jr-msg-preview p').textContent(),payload.items[0].message);
 assert.equal(await page.locator('#jr-msg-preview script').count(),0);
 assert.equal(await page.locator('a.sb-link .jr-msg-badge').textContent(),'1');
 await page.locator('#jr-msg-toast button').click();
 await page.evaluate(() => window.dispatchEvent(new Event('focus')));
 await page.waitForTimeout(250);
 assert(await page.locator('#jr-msg-toast').isHidden());
 await page.locator('#jr-msg-launch').click();
 assert(await page.locator('#jr-msg-panel').isVisible());
 assert.equal(await page.locator('#jr-msg-list a').getAttribute('href'),payload.items[0].url);
 payload={count:0,items:[]};
 await page.waitForTimeout(300);
 await page.evaluate(() => window.dispatchEvent(new Event('focus')));
 await page.waitForFunction(() => document.querySelector('a.sb-link .jr-msg-badge').hidden);
 assert.equal(await page.title(),'Dashboard');
 await page.setViewportSize({width:375,height:812});
 const bounds = await page.locator('#jr-msg-panel').boundingBox();
 assert(bounds.x >= 0 && bounds.x + bounds.width <= 375);
 await browser.close();
 console.log('PASS: unread badges, popup, safe text, dismissal, conversation link, read-count clearing, mobile layout');
})().catch(e => {console.error(e);process.exit(1)});
