import { APIRequestContext, request } from '@playwright/test';

export class ApiClient {
  private context: APIRequestContext | null = null;
  private readonly baseURL: string;

  constructor(baseURL: string = process.env.BASE_URL ?? '') {
    this.baseURL = baseURL;
  }

  private async getContext(): Promise<APIRequestContext> {
    if (!this.context) {
      this.context = await request.newContext({ baseURL: this.baseURL });
    }
    return this.context;
  }

  async get(endpoint: string) {
    const context = await this.getContext();
    return context.get(endpoint);
  }

  async post(endpoint: string, data: Record<string, unknown>) {
    const context = await this.getContext();
    return context.post(endpoint, { data });
  }

  async dispose() {
    await this.context?.dispose();
    this.context = null;
  }
}
