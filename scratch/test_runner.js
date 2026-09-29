const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const BASE_URL = 'http://localhost:8085';

(async () => {
  console.log('Running Final Verification Playwright Audit Sweep...\n');
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();

  page.on('dialog', async dialog => await dialog.accept());

  const results = {
    public: [],
    candidate: [],
    employer: [],
    admin: []
  };

  async function checkPage(category, pathUrl) {
    try {
      const res = await page.goto(BASE_URL + pathUrl, { waitUntil: 'domcontentloaded', timeout: 15000 });
      const status = res ? res.status() : 'No Response';
      console.log(`[${category.toUpperCase()}] ${pathUrl} -> ${status}`);
      results[category].push({ url: pathUrl, status });
    } catch (err) {
      console.error(`[${category.toUpperCase()} FAIL] ${pathUrl} -> ${err.message}`);
      results[category].push({ url: pathUrl, error: err.message });
    }
    await page.waitForTimeout(300);
  }

  // 1. PUBLIC PAGES
  console.log('=== 1. PUBLIC PAGES ===');
  const publicPages = [
    '/',
    '/jobs',
    '/employers',
    '/career-advice',
    '/training',
    '/cv-review',
    '/webinars',
    '/candidates',
    '/about-us',
    '/contact-us',
    '/blog',
    '/privacy-policy',
    '/terms-and-conditions',
    '/faq',
    '/recruitment',
    '/job-ads',
    '/login',
    '/register',
    '/forgot-password',
    '/admin/login'
  ];

  for (const p of publicPages) {
    await checkPage('public', p);
  }

  // 2. CANDIDATE DASHBOARD
  console.log('\n=== 2. CANDIDATE DASHBOARD ===');
  await page.goto(BASE_URL + '/login');
  await page.fill('#login-email', 'candidate@test.com');
  await page.fill('#login-pass', 'Password123!');
  await page.click('#login-btn');
  await page.waitForTimeout(2000);

  const candidatePages = [
    '/candidate/dashboard',
    '/candidate/applications',
    '/candidate/saved-jobs',
    '/candidate/notifications',
    '/candidate/profile',
    '/candidate/profile/edit',
    '/candidate/settings/security',
    '/candidate/resumes',
    '/candidate/resumes/build',
    '/candidate/career-tools',
    '/candidate/my-courses',
    '/candidate/certificates',
    '/candidate/wallet',
    '/candidate/transactions',
    '/candidate/referrals'
  ];

  for (const p of candidatePages) {
    await checkPage('candidate', p);
  }

  await page.goto(BASE_URL + '/logout');
  await page.waitForTimeout(1000);

  // 3. EMPLOYER DASHBOARD
  console.log('\n=== 3. EMPLOYER DASHBOARD ===');
  await page.goto(BASE_URL + '/login');
  await page.fill('#login-email', 'employer@test.com');
  await page.fill('#login-pass', 'Password123!');
  await page.click('#login-btn');
  await page.waitForTimeout(2000);

  const employerPages = [
    '/employer/dashboard',
    '/employer/jobs',
    '/employer/post-job',
    '/employer/applications',
    '/employer/profile',
    '/employer/pricing',
    '/employer/bundles',
    '/employer/candidates',
    '/employer/messages',
    '/employer/settings',
    '/employer/wallet',
    '/employer/transactions'
  ];

  for (const p of employerPages) {
    await checkPage('employer', p);
  }

  await page.goto(BASE_URL + '/logout');
  await page.waitForTimeout(1000);

  // 4. ADMIN DASHBOARD
  console.log('\n=== 4. ADMIN DASHBOARD ===');
  await page.goto(BASE_URL + '/admin/login');
  await page.fill('#signin-email', 'admin@test.com');
  await page.fill('#signin-password', 'Password123!');
  await page.click('#loginBtn');
  await page.waitForTimeout(2000);

  const adminPages = [
    '/admin/dashboard',
    '/admin/users',
    '/admin/jobs',
    '/admin/applications',
    '/admin/candidates',
    '/admin/employers',
    '/admin/blogs',
    '/admin/elearning',
    '/admin/cv-reviews',
    '/admin/aptitude',
    '/admin/newsletters',
    '/admin/webinars',
    '/admin/locations',
    '/admin/categories',
    '/admin/industries',
    '/admin/bundles',
    '/admin/plans',
    '/admin/chatbot',
    '/admin/testimonials'
  ];

  for (const p of adminPages) {
    await checkPage('admin', p);
  }

  await browser.close();

  fs.writeFileSync('scratch/final_verification_report.json', JSON.stringify(results, null, 2));
  console.log('\nFinal Verification Complete! Summary saved to scratch/final_verification_report.json');
})();
