import { test, expect } from '@playwright/test';

test.describe('Authentication Flow', () => {
  test('should load the login page', async ({ page }) => {
    await page.goto('/login');
    
    // Expect the page to have the login form specifically
    await expect(page.locator('#login-form')).toBeVisible();
    
    // Check for email and password fields within the login form
    const loginForm = page.locator('#login-form');
    await expect(loginForm.locator('input[type="email"]')).toBeVisible();
    await expect(loginForm.locator('input[type="password"]')).toBeVisible();
    
    // Check for submit button
    await expect(loginForm.locator('button[type="submit"]')).toBeVisible();
  });

  test('should show error on invalid login', async ({ page }) => {
    await page.goto('/login');
    
    const loginForm = page.locator('#login-form');
    await loginForm.locator('input[type="email"]').fill('invalid@example.com');
    await loginForm.locator('input[type="password"]').fill('wrongpassword');
    await loginForm.locator('button[type="submit"]').click();
    
    // Toastr notification or inline alert
    const errorMessage = page.locator('#toast-container, .toast-error, .toast-message, .auth-alert, .alert-danger, .error-msg, .field-msg.show');
    await expect(errorMessage.first()).toBeVisible({ timeout: 7000 });
  });
});
