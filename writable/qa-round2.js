const { chromium } = require('playwright');

const base = 'http://localhost:8085';

async function login(page, email) {
  await page.goto(base + '/login', { waitUntil: 'domcontentloaded', timeout: 30000 });
  await page.getByLabel('Email address').fill(email);
  await page.getByLabel('Password', { exact: true }).fill('Password123');
  await Promise.all([
    page.waitForLoadState('domcontentloaded'),
    page.getByRole('button', { name: 'Log in' }).click(),
  ]);
  return page.url();
}

async function auditRole(browser, role, email, routes) {
  const context = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
  const page = await context.newPage();
  const failures = [];
  page.on('pageerror', error => failures.push({ type: 'pageerror', message: error.message }));
  page.on('console', message => {
    if (message.type() === 'error') failures.push({ type: 'console', message: message.text() });
  });
  const loginUrl = await login(page, email);
  const results = [];

  for (const route of routes) {
    const response = await page.goto(base + route, { waitUntil: 'domcontentloaded', timeout: 30000 });
    await page.waitForTimeout(500);
    const bodyText = (await page.locator('body').innerText()).trim();
    const overlay = await page.locator('[data-nextjs-dialog], .vite-error-overlay, #webpack-dev-server-client-overlay').count();
    results.push({
      route,
      status: response ? response.status() : null,
      finalUrl: page.url(),
      title: await page.title(),
      contentLength: bodyText.length,
      overlay,
      headings: await page.locator('h1').allTextContents(),
    });
    await page.screenshot({ path: `writable/qa-round2-${role}-${route.replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '')}.png`, fullPage: true });
  }

  if (role === 'employer') {
    await page.goto(base + '/employer/post-job', { waitUntil: 'domcontentloaded' });
    const hasPostForm = await page.locator('#post-job-form').count() > 0;
    const industryOptions = hasPostForm ? await page.locator('#industry option').allTextContents() : [];
    const categoryOptions = hasPostForm ? await page.locator('#job-category option').allTextContents() : [];
    results.push({
      check: 'post-job-options',
      encodedEntities: [...industryOptions, ...categoryOptions].filter(text => /&(?:amp|#\d+|[a-z]+);/i.test(text)),
      slugLabels: industryOptions.filter(text => /^[a-z0-9]+(?:[-_][a-z0-9]+)+$/.test(text.trim())),
    });
    if (hasPostForm) {
      await page.locator('#post-job-form').evaluate(form => form.requestSubmit());
      await page.waitForTimeout(300);
    }
    results.push({
      check: 'post-job-validation',
      hasPostForm,
      validationVisible: hasPostForm ? await page.locator('#client-validation-errors').isVisible() : false,
      invalidCount: hasPostForm ? await page.locator('#post-job-form :invalid').count() : 0,
    });
  }

  await context.close();
  return { role, loginUrl, results, failures };
}

(async () => {
  const browser = await chromium.launch({ headless: true });
  const report = [];
  report.push(await auditRole(browser, 'employer', 'demo.employer@example.com', [
    '/employer/dashboard', '/employer/post-job', '/employer/jobs', '/employer/applications',
    '/employer/messages', '/employer/transactions', '/employer/wallet', '/employer/profile'
  ]));
  report.push(await auditRole(browser, 'candidate', 'demo.candidate@example.com', [
    '/candidate/dashboard', '/candidate/profile', '/candidate/applications', '/candidate/saved-jobs',
    '/candidate/messages', '/candidate/notifications', '/candidate/wallet', '/candidate/settings'
  ]));
  await browser.close();
  process.stdout.write(JSON.stringify(report, null, 2));
})().catch(error => {
  console.error(error);
  process.exit(1);
});
