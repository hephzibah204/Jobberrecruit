import { test, expect } from '@playwright/test';

test.describe('End-to-End Functionality Audit: Candidate Journey', () => {
  const candidateEmail = 'demo.candidate@example.com';
  const candidatePassword = 'Password123';

  test('Candidate Login & Dashboard Navigation', async ({ page }) => {
    // 1. Visit login page
    await page.goto('/login');
    await expect(page.locator('#login-form')).toBeVisible();

    // 2. Perform authentication
    await page.locator('#login-email').fill(candidateEmail);
    await page.locator('#login-pass').fill(candidatePassword);
    await page.locator('#login-btn').click();

    // 3. Verify successful redirect to candidate dashboard
    await page.waitForURL(/\/candidate\/dashboard/, { timeout: 15000 });
    await expect(page.locator('body')).toBeVisible();

    // 4. Verify Candidate Navigation Tabs
    const dashboardTitle = page.locator('h1, h2, .dash-header, .page-title').first();
    await expect(dashboardTitle).toBeVisible();

    // 5. Navigate to Candidate Profile
    await page.goto('/candidate/profile');
    await expect(page).toHaveURL(/\/candidate\/profile/);
    await expect(page.locator('body')).toBeVisible();

    // 6. Navigate to Candidate Applications
    await page.goto('/candidate/applications');
    await expect(page).toHaveURL(/\/candidate\/applications/);
    await expect(page.locator('body')).toBeVisible();

    // 7. Navigate to Saved Jobs
    await page.goto('/candidate/saved-jobs');
    await expect(page).toHaveURL(/\/candidate\/saved-jobs/);
    await expect(page.locator('body')).toBeVisible();
  });

  test('Candidate Job Discovery & Search Filters', async ({ page }) => {
    // 1. Visit jobs search page
    await page.goto('/jobs');
    await expect(page).toHaveTitle(/Jobs/i);

    // 2. Verify Jobs Grid Container and Job Cards
    await expect(page.locator('#jobCardsContainer')).toBeVisible();
    const jobCard = page.locator('.job-card, article[aria-label]').first();
    await expect(jobCard).toBeVisible();

    // 3. Test search interaction
    const searchInput = page.locator('input[name="keyword"], input#job-keyword, input[type="search"]').first();
    if (await searchInput.isVisible()) {
      await searchInput.fill('Manager');
      await page.keyboard.press('Enter');
      await page.waitForTimeout(1000);
      await expect(page.locator('#jobCardsContainer')).toBeVisible();
    }
  });

  test('Candidate Career Tools & Aptitude Tests', async ({ page }) => {
    // Log in first
    await page.goto('/login');
    await page.locator('#login-email').fill(candidateEmail);
    await page.locator('#login-pass').fill(candidatePassword);
    await page.locator('#login-btn').click();
    await page.waitForURL(/\/candidate\/dashboard/, { timeout: 15000 });

    // 1. Visit Aptitude practice
    await page.goto('/candidate/aptitude/practice');
    await expect(page.locator('body')).toBeVisible();

    // 2. Visit Mock Interview
    await page.goto('/candidate/career-tools/mock-interview');
    await expect(page.locator('body')).toBeVisible();

    // 3. Visit Pricing / Premium Upgrades
    await page.goto('/candidate/pricing');
    await expect(page.locator('body')).toBeVisible();
    await expect(page.locator('.prem-grid, .pricing-grid, .plan').first()).toBeVisible();
  });
});

test.describe('End-to-End Functionality Audit: Employer Journey', () => {
  const employerEmail = 'demo.employer@example.com';
  const employerPassword = 'Password123';

  test('Employer Login & Dashboard Metrics', async ({ page }) => {
    // 1. Log in as employer
    await page.goto('/login');
    await page.locator('#login-email').fill(employerEmail);
    await page.locator('#login-pass').fill(employerPassword);
    await page.locator('#login-btn').click();

    // 2. Verify redirect to employer dashboard
    await page.waitForURL(/\/employer\/dashboard/, { timeout: 15000 });
    await expect(page.locator('body')).toBeVisible();

    // 3. Verify Employer Navigation Elements
    await expect(page.locator('h1, h2, .page-header, .dash-header').first()).toBeVisible();
  });

  test('Employer Job Posting & Management Flow', async ({ page }) => {
    // Log in
    await page.goto('/login');
    await page.locator('#login-email').fill(employerEmail);
    await page.locator('#login-pass').fill(employerPassword);
    await page.locator('#login-btn').click();
    await page.waitForURL(/\/employer\/dashboard/, { timeout: 15000 });

    // 1. Navigate to Post a Job
    await page.goto('/employer/jobs/create');
    const jobForm = page.locator('#post-job-form, #job-post-form, form[action*="jobs"], form:visible').first();
    await expect(jobForm).toBeVisible();

    // Verify key job posting fields exist
    const titleInput = page.locator('input[name="title"], #job-title, input[placeholder*="Job Title"]').first();
    await expect(titleInput).toBeVisible();

    // 2. Navigate to Manage Jobs
    await page.goto('/employer/jobs');
    await expect(page.locator('body')).toBeVisible();

    // 3. Navigate to Applicants Pipeline
    await page.goto('/employer/applications');
    await expect(page.locator('body')).toBeVisible();
  });
});

test.describe('End-to-End Functionality Audit: Security & Auth Guards', () => {
  test('Unauthenticated User Redirect on Protected Routes', async ({ page }) => {
    // Clear cookies / context
    await page.context().clearCookies();

    // 1. Try accessing candidate dashboard directly
    await page.goto('/candidate/dashboard');
    await page.waitForURL(/\/login/, { timeout: 10000 });
    await expect(page).toHaveURL(/\/login/);

    // 2. Try accessing employer dashboard directly
    await page.goto('/employer/dashboard');
    await page.waitForURL(/\/login/, { timeout: 10000 });
    await expect(page).toHaveURL(/\/login/);
  });

  test('Password Reset Request Flow', async ({ page }) => {
    await page.goto('/forgot-password');
    const resetForm = page.locator('#forgotForm, form[action*="forgot-password"]').first();
    await expect(resetForm).toBeVisible();

    const emailInput = resetForm.locator('input[type="email"]').first();
    await expect(emailInput).toBeVisible();

    const submitBtn = resetForm.locator('button[type="submit"]').first();
    await expect(submitBtn).toBeVisible();
  });
});
