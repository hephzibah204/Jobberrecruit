const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const BASE_URL = 'http://localhost:8085';
const SCREENSHOT_DIR = path.join(__dirname, 'screenshots');

const auditResults = {
  candidate: [],
  employer: [],
  admin: [],
  globalErrors: []
};

(async () => {
  const browser = await chromium.launch({ headless: true });

  // 1. AUDIT CANDIDATE DASHBOARD
  console.log('\n--- CANDIDATE DASHBOARD AUDIT ---');
  const candContext = await browser.newContext();
  const candPage = await candContext.newPage();
  
  candPage.on('console', msg => {
    if (msg.type() === 'error') auditResults.globalErrors.push({ role: 'candidate', url: candPage.url(), text: msg.text() });
  });

  await candPage.goto(`${BASE_URL}/login`);
  await candPage.fill('#login-email', 'candidate@test.com');
  await candPage.fill('#login-pass', 'Password123!');
  await candPage.click('#login-btn');
  await candPage.waitForTimeout(3000);

  const candUrls = [
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

  for (const u of candUrls) {
    try {
      const res = await candPage.goto(`${BASE_URL}${u}`, { waitUntil: 'domcontentloaded', timeout: 10000 });
      const status = res ? res.status() : 'No Response';
      console.log(`[Candidate] ${u} -> Status ${status}`);
      auditResults.candidate.push({ path: u, status, finalUrl: candPage.url() });
      await candPage.screenshot({ path: path.join(SCREENSHOT_DIR, `cand_${u.replace(/\//g, '_')}.png`) });
    } catch (e) {
      console.error(`[Candidate Error] ${u} -> ${e.message}`);
      auditResults.candidate.push({ path: u, error: e.message });
    }
  }

  // 2. AUDIT EMPLOYER DASHBOARD
  console.log('\n--- EMPLOYER DASHBOARD AUDIT ---');
  const empContext = await browser.newContext();
  const empPage = await empContext.newPage();

  empPage.on('console', msg => {
    if (msg.type() === 'error') auditResults.globalErrors.push({ role: 'employer', url: empPage.url(), text: msg.text() });
  });

  await empPage.goto(`${BASE_URL}/login`);
  await empPage.fill('#login-email', 'employer@test.com');
  await empPage.fill('#login-pass', 'Password123!');
  await empPage.click('#login-btn');
  await empPage.waitForTimeout(3000);

  const empUrls = [
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

  for (const u of empUrls) {
    try {
      const res = await empPage.goto(`${BASE_URL}${u}`, { waitUntil: 'domcontentloaded', timeout: 10000 });
      const status = res ? res.status() : 'No Response';
      console.log(`[Employer] ${u} -> Status ${status}`);
      auditResults.employer.push({ path: u, status, finalUrl: empPage.url() });
      await empPage.screenshot({ path: path.join(SCREENSHOT_DIR, `emp_${u.replace(/\//g, '_')}.png`) });
    } catch (e) {
      console.error(`[Employer Error] ${u} -> ${e.message}`);
      auditResults.employer.push({ path: u, error: e.message });
    }
  }

  // 3. AUDIT ADMIN DASHBOARD
  console.log('\n--- ADMIN DASHBOARD AUDIT ---');
  const adminContext = await browser.newContext();
  const adminPage = await adminContext.newPage();

  adminPage.on('console', msg => {
    if (msg.type() === 'error') auditResults.globalErrors.push({ role: 'admin', url: adminPage.url(), text: msg.text() });
  });

  await adminPage.goto(`${BASE_URL}/admin/login`);
  await adminPage.fill('#signin-email', 'admin@test.com');
  await adminPage.fill('#signin-password', 'Password123!');
  await adminPage.click('#loginBtn');
  await adminPage.waitForTimeout(3000);

  const adminUrls = [
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

  for (const u of adminUrls) {
    try {
      const res = await adminPage.goto(`${BASE_URL}${u}`, { waitUntil: 'domcontentloaded', timeout: 10000 });
      const status = res ? res.status() : 'No Response';
      console.log(`[Admin] ${u} -> Status ${status}`);
      auditResults.admin.push({ path: u, status, finalUrl: adminPage.url() });
      await adminPage.screenshot({ path: path.join(SCREENSHOT_DIR, `admin_${u.replace(/\//g, '_')}.png`) });
    } catch (e) {
      console.error(`[Admin Error] ${u} -> ${e.message}`);
      auditResults.admin.push({ path: u, error: e.message });
    }
  }

  await browser.close();
  fs.writeFileSync(path.join(__dirname, 'dashboard_audit.json'), JSON.stringify(auditResults, null, 2));
  console.log('\nDashboard Audit Complete!');
})();
