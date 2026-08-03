import { test, expect } from '@playwright/test';

test.describe('Employer Flow', () => {
  // We use a predefined test employer for end-to-end testing.
  // It is recommended to seed this in your DB before running tests.
  const employerEmail = 'employer@test.com';
  const employerPassword = 'Password123!';

  test.beforeEach(async ({ page }) => {
    // Log in before each test in this block
    await page.goto('/login');
    const loginForm = page.locator('#login-form');
    await loginForm.locator('input[type="email"]').fill(employerEmail);
    await loginForm.locator('input[type="password"]').fill(employerPassword);
    await loginForm.locator('button[type="submit"]').click();
    await page.waitForURL('**/employer/dashboard', { timeout: 10000 });
  });

  test('should load the employer dashboard after login', async ({ page }) => {
    // Check for a dashboard specific heading or class
    await expect(page.locator('h1').first()).toBeVisible();
    
    // Check that the sidebar is visible
    await expect(page.locator('.sidebar, nav, aside').first()).toBeVisible();
  });

  test('should navigate to post a job page', async ({ page }) => {
    await page.goto('/employer/jobs/create');
    
    // Wait for the form to appear
    await expect(page.locator('form').first()).toBeVisible();
    
    // Check for the Job Title input
    await expect(page.locator('input[name="title"], input[id="job-title"]')).toBeVisible();
    
    // Check for the Publish button
    await expect(page.locator('button[type="submit"]').first()).toBeVisible();
  });
});
