import { test, expect } from '../../src/fixtures/test-fixtures';
import { users } from '../../src/data/test-data';

test.describe('Login', () => {
  test('successful login with valid credentials', async ({ loginPage, page }) => {
    await loginPage.goto('/login');
    await loginPage.login(users.validUser.email, users.validUser.password);

    await expect(page).toHaveURL(/dashboard/);
  });

  test('shows error with invalid credentials', async ({ loginPage, page }) => {
    await loginPage.goto('/login');
    await loginPage.login(users.invalidUser.email, users.invalidUser.password);

    await expect(page.getByText(/invalid credentials/i)).toBeVisible();
  });
});
