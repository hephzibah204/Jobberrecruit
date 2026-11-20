const {chromium}=require('playwright');const fs=require('fs');const assert=require('node:assert/strict');
(async()=>{const browser=await chromium.launch({headless:true});try{
const source=fs.readFileSync('app/Views/home/view_job.php','utf8');
const styles=[...source.matchAll(/<style>([\s\S]*?)<\/style>/g)].map(m=>m[1]).join('\n');
const button=source.match(/<button class="desc-expand-btn"[\s\S]*?<\/button>/)[0];
const start=source.indexOf("  document.getElementById('desc-expand-btn')?.addEventListener");
const script=source.slice(start,source.indexOf('\n});',start));
const page=await browser.newPage({viewport:{width:390,height:844}});
for(const [title,description] of [['Software engineer','<p>Build and maintain applications.</p>'.repeat(30)],['Accountant','<h3>Requirements</h3><ul>'+ '<li>Prepare financial reports and review records.</li>'.repeat(30)+'</ul>'],['Customer support','<p>Help customers resolve enquiries.</p>'.repeat(20)+'<h3>Application guidelines</h3><p>Submit your CV.</p>']]){
await page.setContent('<style>'+styles+'</style><h1>'+title+'</h1><div class="detail-body desc-collapsed" id="detail-body">'+description+'</div>'+button+'<script>'+script+'</script>');
const btn=page.locator('#desc-expand-btn');await btn.click();assert.equal(await btn.getAttribute('aria-expanded'),'true');assert.equal(await page.locator('#detail-body').evaluate(e=>getComputedStyle(e).maxHeight),'none');await btn.click();assert.equal(await btn.getAttribute('aria-expanded'),'false');assert.equal(await page.locator('#detail-body').evaluate(e=>getComputedStyle(e).maxHeight),'260px');await btn.focus();await page.keyboard.press('Enter');assert.equal(await btn.getAttribute('aria-expanded'),'true');await page.setViewportSize({width:1280,height:900});assert(await btn.isHidden());await page.setViewportSize({width:390,height:844});console.log('PASS template fixture:',title);
}
}finally{await browser.close()}})().catch(e=>{console.error(e);process.exit(1)});
