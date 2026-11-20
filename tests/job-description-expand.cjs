const { chromium } = require('playwright');
const assert = require('node:assert/strict');
(async () => {
 const browser = await chromium.launch({headless:true});
 try {
 const page = await browser.newPage({viewport:{width:390,height:844}});
 await page.goto('http://localhost:8080/jobs',{waitUntil:'domcontentloaded'});
 const links = await page.locator('a[href]').evaluateAll(nodes => [...new Set(nodes.map(a=>a.href).filter(h=>/\/(?:jobs\/[^/?#]+|job\/view\/\d+)$/.test(h)))].slice(0,12));
 let tested=0;
 for(const url of links){
  await page.goto(url,{waitUntil:'domcontentloaded'});
  const button=page.locator('#desc-expand-btn');
  if(!await button.count())continue;
  await button.click();
  assert.equal(await button.getAttribute('aria-expanded'),'true');
  assert.equal(await page.locator('#detail-body').evaluate(e=>e.classList.contains('desc-collapsed')),false);
  assert.equal(await page.locator('#detail-body').evaluate(e=>getComputedStyle(e).maxHeight),'none');
  assert.match(await button.textContent(),/Show less/);
  await button.click();
  assert.equal(await button.getAttribute('aria-expanded'),'false');
  assert.equal(await page.locator('#detail-body').evaluate(e=>getComputedStyle(e).maxHeight),'260px');
  await button.focus();await page.keyboard.press('Enter');
  assert.equal(await button.getAttribute('aria-expanded'),'true');
  await page.setViewportSize({width:1280,height:900});
  assert(await button.isHidden());
  assert.equal(await page.locator('#detail-body').evaluate(e=>getComputedStyle(e).maxHeight),'none');
  await page.setViewportSize({width:390,height:844});
  console.log('PASS:',url);tested++;
  if(tested===3)break;
 }
 assert.equal(tested,3,'Must verify three actual job pages');
 }finally{await browser.close();}
})().catch(e=>{console.error(e);process.exit(1)});
