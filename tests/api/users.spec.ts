import { test, expect } from '@playwright/test';
import { ApiClient } from '../../src/utils/api-client';

test.describe('Users API', () => {
  let apiClient: ApiClient;

  test.beforeEach(() => {
    apiClient = new ApiClient();
  });

  test.afterEach(async () => {
    await apiClient.dispose();
  });

  test('GET /users returns a list of users', async () => {
    const response = await apiClient.get('/users');

    expect(response.ok()).toBeTruthy();
    const body = await response.json();
    expect(Array.isArray(body)).toBeTruthy();
  });

  test('POST /users creates a new user', async () => {
    const response = await apiClient.post('/users', {
      name: 'Test User',
      email: 'test.user@example.com',
    });

    expect(response.status()).toBe(201);
  });
});
