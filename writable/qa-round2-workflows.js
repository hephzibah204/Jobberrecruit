const { chromium } = require('playwright');
const base = 'http://localhost:8085';

async function signIn(page, email) {
  await page.goto(base + '/login');
  await page.getByLabel('Email address').fill(email);
  await page.getByLabel('Password', { exact: true }).fill('Password123');
  await page.getByRole('button', { name: 'Log in' }).click();
  await page.waitForURL(url => !url.pathname.endsWith('/login'), { timeout: 15000 });
  await page.waitForLoadState('domcontentloaded');
}

(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ viewport: { width: 390, height: 844 } });
  const page = await context.newPage();
  const errors = [];
  page.on('pageerror', e => errors.push(e.message));
  page.on('console', m => { if (m.type() === 'error') errors.push(m.text()); });
  await signIn(page, 'demo.employer@example.com');

  const mobile = [];
  for (const route of ['/employer/dashboard', '/employer/post-job', '/employer/transactions']) {
    await page.goto(base + route, { waitUntil: 'domcontentloaded' });
    await page.waitForTimeout(300);
    mobile.push({
      route,
      url: page.url(),
      overflow: await page.evaluate(() => document.documentElement.scrollWidth > document.documentElement.clientWidth),
      width: await page.evaluate(() => [document.documentElement.clientWidth, document.documentElement.scrollWidth]),
      offenders: await page.evaluate(() => [...document.querySelectorAll('body *')]
        .map(el => ({ el, rect: el.getBoundingClientRect() }))
        .filter(x => x.rect.right > document.documentElement.clientWidth + 1)
        .slice(0, 12)
        .map(x => ({ tag: x.el.tagName, id: x.el.id, class: x.el.className, left: x.rect.left, right: x.rect.right, width: x.rect.width }))),
    });
    await page.screenshot({ path: `writable/qa-round2-mobile-${route.replace(/\W+/g, '-')}.png`, fullPage: true });
  }

  await page.setViewportSize({ width: 1440, height: 1000 });
  await page.goto(base + '/employer/post-job', { waitUntil: 'domcontentloaded' });
  await page.locator('#job-title').fill('QA Persistence Test Engineer');
  await page.locator('#job-type').selectOption('full-time');
  await page.locator('#job-location').selectOption({ index: 1 });
  await page.locator('#work-style').selectOption('remote');
  await page.locator('#industry').selectOption({ index: 1 });
  await page.waitForTimeout(200);
  await page.locator('#job-category').selectOption({ index: 1 });
  await page.locator('#job-desc').fill('This is an isolated quality assurance draft used to verify server-side persistence, validation, navigation, and credit safety. It should remain editable and must never be published automatically.');
  await page.locator('#min-edu').selectOption({ index: 1 });
  await page.locator('#years-exp').selectOption({ index: 1 });
  const invalidBefore = await page.locator('#post-job-form :invalid').evaluateAll(nodes => nodes.map(n => ({ name: n.name, message: n.validationMessage })));
  const draftResponsePromise = page.waitForResponse(r => r.url().includes('/employer/post-job') && r.request().method() === 'POST');
  await page.getByRole('button', { name: 'Save as draft' }).first().click();
  const draftResponse = await draftResponsePromise;
  const draftJson = await draftResponse.json();
  await page.waitForTimeout(1400);

  await page.goto(base + '/employer/post-job');
  await page.locator('#job-title').fill('AI Failure Integrity Test');
  await page.locator('#job-desc').fill('Existing employer text that must remain unchanged when an AI provider request fails or the provider is not configured.');
  const beforeAi = await page.locator('#job-desc').inputValue();
  const aiResponsePromise = page.waitForResponse(r => r.url().includes('/employer/jobs/ai-generate'));
  page.once('dialog', dialog => dialog.accept());
  await page.locator('#ai-generate-btn').click();
  const aiResponse = await aiResponsePromise;
  await page.waitForTimeout(200);
  const afterAi = await page.locator('#job-desc').inputValue();

  console.log(JSON.stringify({
    mobile,
    invalidBefore,
    draft: { status: draftResponse.status(), json: draftJson, finalUrl: page.url() },
    ai: { status: aiResponse.status(), body: await aiResponse.json(), textPreserved: beforeAi === afterAi },
    errors,
  }, null, 2));
  await browser.close();
})().catch(e => { console.error(e); process.exit(1); });
