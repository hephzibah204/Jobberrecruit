import { test, expect } from '@playwright/test';

test.describe('Candidate Flow', () => {
  // Predefined candidate credentials from database
  const candidateEmail = 'candidate@test.com';
  const candidatePassword = 'Password123!';

  test.beforeEach(async ({ page }) => {
    await page.goto('/login');
    const loginForm = page.locator('#login-form');
    await loginForm.locator('input[type="email"]').fill(candidateEmail);
    await loginForm.locator('input[type="password"]').fill(candidatePassword);
    await loginForm.locator('button[type="submit"]').click();
    await page.waitForURL('**/candidate/dashboard', { timeout: 10000 });
  });

  test('should load the candidate dashboard', async ({ page }) => {
    // Should navigate to candidate dashboard
    await page.waitForURL('**/candidate/dashboard', { timeout: 10000 });
    
    // Check for visibility of dashboard elements
    await expect(page.locator('.sidebar, nav, aside').first()).toBeVisible();
    await expect(page.locator('h1').first()).toBeVisible();
  });
  
  test('should load the mock interview session page', async ({ page }) => {
    await page.goto('/candidate/career-tools/mock-interview/start');
    
    // Make sure the mock interview session doesn't throw a 500 error
    await expect(page.locator('body')).toBeVisible();
    // Verify the page title or header
    await expect(page).toHaveTitle(/Mock Interview|Session/i, { timeout: 10000 });
  });
});
