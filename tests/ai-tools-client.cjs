const fs = require('fs');
const assert = require('node:assert/strict');
const {chromium} = require('playwright');
function functionSource(path, name) {
  const source = fs.readFileSync(path, 'utf8').replace(/<\?=[\s\S]*?\?>/g, '/candidate/career-tools/test');
  const scripts = [...source.matchAll(/<script>([\s\S]*?)<\/script>/g)].map(m => m[1]).join('\n');
  const start=scripts.indexOf('function '+name+'(');
  assert(start>=0, 'Missing '+name);
  const prefix=scripts.slice(Math.max(0,start-6),start)==='async ' ? start-6 : start;
  const end=scripts.indexOf('\n}',start)+2;
  assert(end>start,'Missing function end');
  return scripts.slice(prefix,end);
}
(async () => {
  const browser = await chromium.launch({headless:true, executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
  try {
    const page = await browser.newPage();
    await page.route('http://example.test/**', route => route.fulfill({body:'<h1 id="done-title">Done</h1><span id="score-num">65</span>'}));
    await page.goto('http://example.test/');
    let security = fs.readFileSync('app/Views/partials/ai_request_security.php','utf8').replace(/^<script>|<\/script>\s*$/g,'')
      .replace(/<\?= json_encode\(csrf_header\(\)\) \?>/g, '"X-CSRF-TOKEN"').replace(/<\?= json_encode\(csrf_hash\(\)\) \?>/g, '"valid-token"');
    await page.evaluate(() => {
      window.calls=[]; window.failNext=true;
      window.fetch=async (url, opts) => {
        calls.push({url, headers:Object.fromEntries(new Headers(opts.headers)),body:Object.fromEntries(opts.body)});
        if(failNext){failNext=false;return {ok:false,json:async()=>({message:'Save failed'})};}
        return {ok:true,json:async()=>({success:true,saved_session:{id:7},xp_awarded:200})};
      };
    });
    await page.addScriptTag({content:security});
    await page.addScriptTag({content:`var MEMORY=[{question:'Describe your work',answer:'I built the reporting service.'}];var completionKey='${'a'.repeat(64)}';var completionSaved=false;var elapsed=75;var CFG={job:'Engineer',diff:'medium',field:'general',imode:'text',itype:'mixed',persona:'hr'};` + functionSource('app/Views/candidate/career-tools/mock-interview-session.php','saveInterviewCompletion')});
    await page.evaluate(() => saveInterviewCompletion());
    assert.match(await page.locator('#interview-save-status').innerText(), /Save failed/);
    assert.equal(await page.getByRole('button',{name:'Retry saving',exact:true}).count(),1);
    await page.getByRole('button',{name:'Retry saving',exact:true}).click();
    await page.waitForFunction(() => completionSaved);
    assert.match(await page.locator('#interview-save-status').innerText(), /saved.*200 Career XP/i);
    await page.evaluate(() => saveInterviewCompletion());
    const calls = await page.evaluate(() => calls);
    assert.equal(calls.length,2);
    assert.equal(calls[0].headers['x-csrf-token'],'valid-token');
    assert.equal(calls[0].body.completion_key,calls[1].body.completion_key);
    assert.equal(JSON.parse(calls[0].body.history)[1].message,'I built the reporting service.');
    await page.evaluate(() => {window.S={job:'Engineer',offer:100000,target:150000,lastAiNumber:120000,setupStyle:'corporate',diff:'medium',round:2,completionKey:'b'.repeat(64),conversation:[{sender:'model',message:'What are you seeking?'},{sender:'user',message:'150000 based on my responsibilities.'}]};window.toastMessages=[];window.toast=m=>toastMessages.push(m);failNext=true;});
    await page.addScriptTag({content:functionSource('app/Views/candidate/career-tools/salary-negotiation.php','saveSession')});
    await page.evaluate(() => saveSession(65,70,60,{Confidence:70},'practice'));
    await page.waitForSelector('#retry-save-session');
    await page.locator('#retry-save-session').click();
    await page.waitForFunction(() => toastMessages.includes('Report saved to your history.'));
    const negotiationCalls=await page.evaluate(()=>calls.slice(2));
    assert.equal(negotiationCalls.length,2);
    assert.equal(negotiationCalls[0].body.completion_key,negotiationCalls[1].body.completion_key);
    assert.equal(JSON.parse(negotiationCalls[0].body.transcript_json)[0].message,'What are you seeking?');
    await page.evaluate(() => {
      var report=document.createElement('div');report.id='phase-report';report.textContent='Your practice score: 65';document.body.appendChild(report);
      window.printedReport=null;
      window.open=()=>{var doc=document.implementation.createHTMLDocument();return {document:doc,focus(){},print(){window.printedReport=doc.body.textContent}}};
    });
    await page.addScriptTag({content:functionSource('app/Views/candidate/career-tools/salary-negotiation.php','printSalaryReport')});
    await page.evaluate(() => printSalaryReport());
    const report=await page.evaluate(()=>printedReport);
    assert.match(report,/Your practice score: 65/);
    assert.match(report,/150000 based on my responsibilities/);
    await page.evaluate(()=>{window.open=()=>null;printSalaryReport()});
    assert.match(await page.evaluate(()=>toastMessages.at(-1)),/Allow pop-ups/);
    console.log('PASS: real-browser CSRF, save/retry/XP, stable completion keys, actual transcripts, printable salary report and blocked-popup feedback.');
  } finally {await browser.close();}
})().catch(error=>{console.error(error);process.exit(1)});
