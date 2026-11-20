const fs = require('fs');
const vm = require('vm');
const assert = require('node:assert/strict');
const {chromium} = require('playwright');
const source = name => fs.readFileSync('app/Views/candidate/' + name, 'utf8');
const strip = text => text.replace(/<\?=[\s\S]*?\?>/g, '0').replace(/<\?php[\s\S]*?\?>/g, '');
const scripts = html => [...strip(html).matchAll(/<script\b[^>]*>([\s\S]*?)<\/script>/g)].map(m=>m[1]);
(async()=>{
  for(const name of ['settings.php','edit_profile.php','saved_jobs.php','notifications.php','resume/builder.php','career-tools/salary-negotiation.php','career-tools/mock-interview-session.php']) {
    scripts(source(name)).forEach(script=>new vm.Script(script, {filename:name}));
  }
  const browser=await chromium.launch({headless:true,executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
  try {
    const page=await browser.newPage({viewport:{width:390,height:844}});
    await page.route('http://candidate.test/**',route=>route.fulfill({body:'<html><body></body></html>'}));
    await page.goto('http://candidate.test/');
    await page.setContent(strip(source('settings.php')).replace(/<script\b[^>]*>[\s\S]*?<\/script>/g,''));
    await page.evaluate(()=>{
      window.requests=[]; window.toastr={success(){},error(){}};
      window.fetch=async(url,options)=>{requests.push({url,method:options.method,body:String(options.body)});return {json:async()=>({success:true})};};
    });
    for(const script of scripts(source('settings.php')))await page.addScriptTag({content:script});
    await page.evaluate(()=>document.querySelector('#set-notif-form').dispatchEvent(new Event('submit',{bubbles:true,cancelable:true})));
    const request=await page.evaluate(()=>requests[0]);
    assert.equal(request.method,'POST');assert.match(request.body,/notify_marketing=/);
    assert.equal(await page.locator('#set-notif-form').getAttribute('method'),'post');
    await page.setContent(strip(source('edit_profile.php')).replace(/<script\b[^>]*>[\s\S]*?<\/script>/g,''));
    assert.equal(await page.locator('template#exp-template').count(),1);
    assert.equal(await page.locator('input[name="exp_is_current[]"]').count(),await page.locator('#exp-list .rep-row').count());
    page.on('dialog',dialog=>dialog.accept());
    for(const [name,selector] of [['saved_jobs.php','.unsave-job'],['notifications.php','.delete-alert']]) {
      await page.setContent(strip(source(name)).replace(/<script\b[^>]*>[\s\S]*?<\/script>/g,''));
      await page.addScriptTag({path:'auth/js/jquery-3.7.1.min.js'});
      await page.evaluate(()=>{
        window.requests=[];
        $.post=(url,data,done)=>{requests.push({url,data});done({success:true});};
        window.fetch=async(url,options)=>{requests.push({url,method:options.method});return {json:async()=>({success:true})};};
      });
      for(const script of scripts(source(name)))await page.addScriptTag({content:script});
      await page.locator(selector).first().click();
      assert.equal(await page.evaluate(()=>requests.length),1,name+' must submit removal');
      if(name==='saved_jobs.php')assert.equal(await page.locator('.unsave-job').count(),0);
    }
    await page.evaluate(()=>{
      document.querySelector('.edit-alert').dataset.alert=JSON.stringify({id:12,keyword:'Sales Manager',location_id:'',frequency:'daily',delivery_time:'08:00',channel:'email'});
      $.ajax=options=>{requests.push({body:options.data});};
    });
    await page.locator('.edit-alert').first().click();
    assert.equal(await page.locator('#ja-id').inputValue(),'12');
    await page.locator('#ja-freq').selectOption('weekly');
    await page.locator('#ja-submit').click();
    assert.match(await page.evaluate(()=>requests.at(-1).body),/frequency=weekly/);
    assert.match(await page.evaluate(()=>requests.at(-1).body),/id=12/);
    await page.locator('#ja-cancel').click();
    assert.equal(await page.locator('#ja-id').inputValue(),'');
    console.log('PASS: candidate scripts compile; settings sends preferences via POST; profile has no orphaned experience controls; Unsave and Delete submit successfully at 390px.');
  } finally {await browser.close();}
})().catch(e=>{console.error(e);process.exit(1)});
