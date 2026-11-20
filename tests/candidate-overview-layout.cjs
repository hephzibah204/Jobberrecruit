const assert=require('node:assert/strict');
const fs=require('node:fs');
const {execFileSync}=require('node:child_process');
const {chromium}=require('playwright');
const render=match=>execFileSync('C:/Users/hephz/.config/herd/bin/php84/php.exe',['tests/candidate-overview-render.php',String(match)],{encoding:'utf8'});
(async()=>{
    const browser=await chromium.launch({headless:true,executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
    try{
        const page=await browser.newPage();
        await page.route('**/*',r=>r.abort());
        for(const match of [0,10,100]){
            const html=render(match);assert.doesNotMatch(html,/Warning:|Fatal error:|Customer Service Excellence/);
            await page.setContent(html);
            assert.equal(await page.locator('main').count(),1);
            assert.equal(await page.locator('#main-content').count(),1);
            assert.match(await page.locator('h1').textContent(),/Professional/);
            const segments=await page.locator('.donut circle.prog').evaluateAll(nodes=>nodes.map(n=>({length:Number(n.getAttribute('stroke-dasharray').split(' ')[0]),pathLength:n.getAttribute('pathLength')})));
            assert.ok(Math.abs(segments.reduce((n,s)=>n+s.length,0)-match)<0.001);
            segments.forEach(s=>assert.equal(s.pathLength,'100'));
            assert.equal(await page.locator('.pill--shortlisted').count(),1);
            assert.equal(await page.locator('.pill--reviewed').count(),1);
            assert.equal(await page.locator('.bar-chart rect[height="0"]').count(),6);
            for(const width of [320,390,768,1440]){
                await page.setViewportSize({width,height:1000});
                const result=await page.evaluate(()=>{
                    const ring=document.querySelector('.ring-sm').getBoundingClientRect(),pct=document.querySelector('.ring-sm .pct').getBoundingClientRect();
                    const card=document.querySelector('.dash-invitation-row').getBoundingClientRect(),action=document.querySelector('.dash-invitation-action').getBoundingClientRect();
                    return {overflow:document.documentElement.scrollWidth>innerWidth+1,center:Math.abs((ring.x+ring.width/2)-(pct.x+pct.width/2)),actionFits:action.right<=card.right+1&&action.left>=card.left-1};
                });
                assert.equal(result.overflow,false,'Overflow at '+width);
                assert.ok(result.center<2,'Ring label not centered');
                assert.ok(result.actionFits,'Assessment action clipped at '+width);
            }
        }
        const profileSource=fs.readFileSync('app/Views/candidate/edit_profile.php','utf8');
        const start=profileSource.indexOf('    function openProfileSectionFromHash()');
        const marker="    window.addEventListener('hashchange', openProfileSectionFromHash);";
        const deepLinkScript=profileSource.slice(start,profileSource.indexOf(marker,start)+marker.length);
        await page.setContent('<details class="cv-card" id="sec-education"><summary>Education</summary><input></details><details class="cv-card" id="sec-skills"><summary>Skills</summary><input></details>');
        await page.evaluate(()=>{location.hash='#sec-education';});
        await page.addScriptTag({content:deepLinkScript});
        assert.equal(await page.locator('#sec-education').getAttribute('open'),'');
        await page.evaluate(()=>{location.hash='#sec-skills';});
        await page.waitForFunction(()=>document.getElementById('sec-skills').open);
        console.log('PASS: actual dashboard markup, 0/10/100% arcs, single landmark, greeting/statuses, empty activity, centered progress and assessment actions at 320/390/768/1440px.');
    }finally{await browser.close();}
})().catch(e=>{console.error(e);process.exit(1)});
