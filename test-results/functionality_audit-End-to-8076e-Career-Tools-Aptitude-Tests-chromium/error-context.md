# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: functionality_audit.spec.ts >> End-to-End Functionality Audit: Candidate Journey >> Candidate Career Tools & Aptitude Tests
- Location: tests\functionality_audit.spec.ts:61:7

# Error details

```
Error: page.goto: net::ERR_CONNECTION_REFUSED at http://localhost:8080/login
Call log:
  - navigating to "http://localhost:8080/login", waiting until "load"

```

# Test source

```ts
  1   | import { test, expect } from '@playwright/test';
  2   | 
  3   | test.describe('End-to-End Functionality Audit: Candidate Journey', () => {
  4   |   const candidateEmail = 'demo.candidate@example.com';
  5   |   const candidatePassword = 'Password123';
  6   | 
  7   |   test('Candidate Login & Dashboard Navigation', async ({ page }) => {
  8   |     // 1. Visit login page
  9   |     await page.goto('/login');
  10  |     await expect(page.locator('#login-form')).toBeVisible();
  11  | 
  12  |     // 2. Perform authentication
  13  |     await page.locator('#login-email').fill(candidateEmail);
  14  |     await page.locator('#login-pass').fill(candidatePassword);
  15  |     await page.locator('#login-btn').click();
  16  | 
  17  |     // 3. Verify successful redirect to candidate dashboard
  18  |     await page.waitForURL(/\/candidate\/dashboard/, { timeout: 15000 });
  19  |     await expect(page.locator('body')).toBeVisible();
  20  | 
  21  |     // 4. Verify Candidate Navigation Tabs
  22  |     const dashboardTitle = page.locator('h1, h2, .dash-header, .page-title').first();
  23  |     await expect(dashboardTitle).toBeVisible();
  24  | 
  25  |     // 5. Navigate to Candidate Profile
  26  |     await page.goto('/candidate/profile');
  27  |     await expect(page).toHaveURL(/\/candidate\/profile/);
  28  |     await expect(page.locator('body')).toBeVisible();
  29  | 
  30  |     // 6. Navigate to Candidate Applications
  31  |     await page.goto('/candidate/applications');
  32  |     await expect(page).toHaveURL(/\/candidate\/applications/);
  33  |     await expect(page.locator('body')).toBeVisible();
  34  | 
  35  |     // 7. Navigate to Saved Jobs
  36  |     await page.goto('/candidate/saved-jobs');
  37  |     await expect(page).toHaveURL(/\/candidate\/saved-jobs/);
  38  |     await expect(page.locator('body')).toBeVisible();
  39  |   });
  40  | 
  41  |   test('Candidate Job Discovery & Search Filters', async ({ page }) => {
  42  |     // 1. Visit jobs search page
  43  |     await page.goto('/jobs');
  44  |     await expect(page).toHaveTitle(/Jobs/i);
  45  | 
  46  |     // 2. Verify Jobs Grid Container and Job Cards
  47  |     await expect(page.locator('#jobCardsContainer')).toBeVisible();
  48  |     const jobCard = page.locator('.job-card, article[aria-label]').first();
  49  |     await expect(jobCard).toBeVisible();
  50  | 
  51  |     // 3. Test search interaction
  52  |     const searchInput = page.locator('input[name="keyword"], input#job-keyword, input[type="search"]').first();
  53  |     if (await searchInput.isVisible()) {
  54  |       await searchInput.fill('Manager');
  55  |       await page.keyboard.press('Enter');
  56  |       await page.waitForTimeout(1000);
  57  |       await expect(page.locator('#jobCardsContainer')).toBeVisible();
  58  |     }
  59  |   });
  60  | 
  61  |   test('Candidate Career Tools & Aptitude Tests', async ({ page }) => {
  62  |     // Log in first
> 63  |     await page.goto('/login');
      |                ^ Error: page.goto: net::ERR_CONNECTION_REFUSED at http://localhost:8080/login
  64  |     await page.locator('#login-email').fill(candidateEmail);
  65  |     await page.locator('#login-pass').fill(candidatePassword);
  66  |     await page.locator('#login-btn').click();
  67  |     await page.waitForURL(/\/candidate\/dashboard/, { timeout: 15000 });
  68  | 
  69  |     // 1. Visit Aptitude practice
  70  |     await page.goto('/candidate/aptitude/practice');
  71  |     await expect(page.locator('body')).toBeVisible();
  72  | 
  73  |     // 2. Visit Mock Interview
  74  |     await page.goto('/candidate/career-tools/mock-interview');
  75  |     await expect(page.locator('body')).toBeVisible();
  76  | 
  77  |     // 3. Visit Pricing / Premium Upgrades
  78  |     await page.goto('/candidate/pricing');
  79  |     await expect(page.locator('body')).toBeVisible();
  80  |     await expect(page.locator('.prem-grid, .pricing-grid, .plan').first()).toBeVisible();
  81  |   });
  82  | });
  83  | 
  84  | test.describe('End-to-End Functionality Audit: Employer Journey', () => {
  85  |   const employerEmail = 'demo.employer@example.com';
  86  |   const employerPassword = 'Password123';
  87  | 
  88  |   test('Employer Login & Dashboard Metrics', async ({ page }) => {
  89  |     // 1. Log in as employer
  90  |     await page.goto('/login');
  91  |     await page.locator('#login-email').fill(employerEmail);
  92  |     await page.locator('#login-pass').fill(employerPassword);
  93  |     await page.locator('#login-btn').click();
  94  | 
  95  |     // 2. Verify redirect to employer dashboard
  96  |     await page.waitForURL(/\/employer\/dashboard/, { timeout: 15000 });
  97  |     await expect(page.locator('body')).toBeVisible();
  98  | 
  99  |     // 3. Verify Employer Navigation Elements
  100 |     await expect(page.locator('h1, h2, .page-header, .dash-header').first()).toBeVisible();
  101 |   });
  102 | 
  103 |   test('Employer Job Posting & Management Flow', async ({ page }) => {
  104 |     // Log in
  105 |     await page.goto('/login');
  106 |     await page.locator('#login-email').fill(employerEmail);
  107 |     await page.locator('#login-pass').fill(employerPassword);
  108 |     await page.locator('#login-btn').click();
  109 |     await page.waitForURL(/\/employer\/dashboard/, { timeout: 15000 });
  110 | 
  111 |     // 1. Navigate to Post a Job
  112 |     await page.goto('/employer/jobs/create');
  113 |     const jobForm = page.locator('#post-job-form, #job-post-form, form[action*="jobs"], form:visible').first();
  114 |     await expect(jobForm).toBeVisible();
  115 | 
  116 |     // Verify key job posting fields exist
  117 |     const titleInput = page.locator('input[name="title"], #job-title, input[placeholder*="Job Title"]').first();
  118 |     await expect(titleInput).toBeVisible();
  119 | 
  120 |     // 2. Navigate to Manage Jobs
  121 |     await page.goto('/employer/jobs');
  122 |     await expect(page.locator('body')).toBeVisible();
  123 | 
  124 |     // 3. Navigate to Applicants Pipeline
  125 |     await page.goto('/employer/applications');
  126 |     await expect(page.locator('body')).toBeVisible();
  127 |   });
  128 | });
  129 | 
  130 | test.describe('End-to-End Functionality Audit: Security & Auth Guards', () => {
  131 |   test('Unauthenticated User Redirect on Protected Routes', async ({ page }) => {
  132 |     // Clear cookies / context
  133 |     await page.context().clearCookies();
  134 | 
  135 |     // 1. Try accessing candidate dashboard directly
  136 |     await page.goto('/candidate/dashboard');
  137 |     await page.waitForURL(/\/login/, { timeout: 10000 });
  138 |     await expect(page).toHaveURL(/\/login/);
  139 | 
  140 |     // 2. Try accessing employer dashboard directly
  141 |     await page.goto('/employer/dashboard');
  142 |     await page.waitForURL(/\/login/, { timeout: 10000 });
  143 |     await expect(page).toHaveURL(/\/login/);
  144 |   });
  145 | 
  146 |   test('Password Reset Request Flow', async ({ page }) => {
  147 |     await page.goto('/forgot-password');
  148 |     const resetForm = page.locator('#forgotForm, form[action*="forgot-password"]').first();
  149 |     await expect(resetForm).toBeVisible();
  150 | 
  151 |     const emailInput = resetForm.locator('input[type="email"]').first();
  152 |     await expect(emailInput).toBeVisible();
  153 | 
  154 |     const submitBtn = resetForm.locator('button[type="submit"]').first();
  155 |     await expect(submitBtn).toBeVisible();
  156 |   });
  157 | });
  158 | 
```