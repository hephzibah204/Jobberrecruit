const fs = require('fs');
const assert = require('node:assert/strict');
const {chromium} = require('playwright');
(async () => {
  const browser = await chromium.launch({headless:true,executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
  try {
    const page = await browser.newPage();
    const errors = [], alerts = [];
    page.on('pageerror',error=>errors.push(error.message));
    page.on('dialog',async dialog=>{alerts.push(dialog.message());await dialog.accept()});
    const html = `<h2 id="sessions-h"></h2><div id="wcount"></div><p id="wcount-live"></p>
      <input id="wsearch" oninput="applyFilters()"><select id="wsort" onchange="applyFilters()"><option value="date">Date</option><option value="popular">Popular</option><option value="price">Free first</option></select>
      <button data-filter="all" onclick="doFilter('all',this)">All</button><button data-filter="cv" onclick="doFilter('cv',this)">CV</button>
      <button class="view-tab" id="upcoming" onclick="setView('upcoming',this)">Upcoming</button><button class="view-tab" id="ondemand" onclick="setView('ondemand',this)">On-demand</button>
      <div id="wgrid"><article class="wb-card" id="first" data-start="2099-01-01T12:00:00+01:00" data-cat="cv remote" data-search="CV writing by Jane" data-price="1000" data-pop="2">CV writing</article>
      <article class="wb-card" id="second" data-start="2099-01-02T12:00:00+01:00" data-cat="tech" data-search="Coding with Bob" data-price="0" data-pop="10">Coding</article>
      <div id="empty-state"><h3></h3><p></p></div></div>
      <div class="faq-item"><button id="faq" onclick="toggleFaq(this)">FAQ</button><div class="faq-a">Answer</div></div>
      <button class="btn-register" data-id="3">Register</button>
      <script>window.webinarPage={registerUrl:'/register/',loginUrl:'/login',csrfHeader:'X-CSRF-TOKEN',csrfToken:'test-token'};</script>
      <script>${fs.readFileSync('js/webinars.js','utf8')}</script>`;
    await page.route('http://example.test/**',route=>route.fulfill({body:html,contentType:'text/html'}));
    await page.goto('http://example.test/');
    await page.locator('#wsearch').fill('Jane');
    assert.equal(await page.locator('.wb-card:visible').count(),1);
    await page.locator('#wsearch').fill('');
    await page.getByRole('button',{name:'CV',exact:true}).click();
    assert.equal(await page.locator('.wb-card:visible').count(),1);
    await page.getByRole('button',{name:'All',exact:true}).click();
    await page.locator('#wsort').selectOption('popular');
    assert.equal(await page.locator('.wb-card').first().getAttribute('id'),'second');
    await page.locator('#wsort').selectOption('date');
    assert.equal(await page.locator('.wb-card').first().getAttribute('id'),'first');
    await page.locator('#ondemand').click();
    assert.equal(await page.locator('.wb-card:visible').count(),0);
    assert.match(await page.locator('#empty-state').innerText(),/No recordings available/);
    await page.locator('#upcoming').click();
    await page.locator('#faq').click(); assert.equal(await page.locator('#faq').getAttribute('aria-expanded'),'true');
    await page.locator('#faq').click(); assert.equal(await page.locator('#faq').getAttribute('aria-expanded'),'false');
    await page.evaluate(()=>{document.getElementById('first').dataset.start='2020-01-01T12:00:00+01:00';refreshWebinarDates()});
    assert.equal(await page.locator('#first').count(),0);
    await page.route('http://example.test/register/3',route=>{
      assert.equal(route.request().headers()['x-csrf-token'],'test-token');
      return route.fulfill({status:409,contentType:'application/json',body:JSON.stringify({messages:{error:'Registration is closed'}})});
    });
    await page.getByRole('button',{name:'Register',exact:true}).click();
    await page.waitForFunction(()=>!document.querySelector('.btn-register').disabled);
    assert.match(alerts.at(-1),/Registration is closed/);
    assert.equal(page.url(),'http://example.test/');
    await page.route('http://example.test/register/3',route=>route.abort());
    await page.getByRole('button',{name:'Register',exact:true}).click();
    await page.waitForFunction(()=>!document.querySelector('.btn-register').disabled);
    assert.equal(page.url(),'http://example.test/');
    assert.deepEqual(errors,[]);
    await page.route('http://example.test/register/3',route=>route.fulfill({contentType:'application/json',body:JSON.stringify({success:true,redirect:'http://example.test/ticket'})}));
    await Promise.all([page.waitForURL('http://example.test/ticket'),page.getByRole('button',{name:'Register',exact:true}).click()]);
    assert.equal(page.url(),'http://example.test/ticket');
    console.log('PASS: browser search, topics, sorting, on-demand empty state, FAQ, expiry, CSRF and failed/network registration feedback without false confirmation.');
  } finally {await browser.close()}
})().catch(error=>{console.error(error);process.exit(1)});
