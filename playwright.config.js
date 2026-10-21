import { defineConfig, devices } from '@playwright/test';
import path from 'node:path';

/**
 * Testes de frontend (navegador real).
 *
 *   npm run test:e2e                         → sobe o site local com um banco SQLite descartável
 *   E2E_BASE_URL=https://... npm run test:e2e → roda contra um site já publicado (ex.: a pré-visualização do GitHub Pages)
 *
 * Requer `npm run build` antes (o servidor local usa os assets compilados).
 */
const PORT = 8123;
const external = process.env.E2E_BASE_URL;
const baseURL = (external || `http://127.0.0.1:${PORT}`).replace(/\/?$/, '/');
const db = path.resolve('database/e2e.sqlite');

export default defineConfig({
    testDir: 'tests/e2e',
    fullyParallel: true,
    forbidOnly: !!process.env.CI,
    retries: process.env.CI ? 1 : 0,
    workers: process.env.CI ? 2 : 4,
    reporter: process.env.CI ? [['github'], ['list']] : 'list',
    use: {
        baseURL,
        locale: 'pt-BR',
        trace: 'retain-on-failure',
        screenshot: 'only-on-failure',
    },
    projects: [
        { name: 'mobile-chrome', use: { ...devices['Pixel 7'] } },
        { name: 'mobile-safari', use: { ...devices['iPhone 13'] } },
        { name: 'desktop-chrome', use: { ...devices['Desktop Chrome'], viewport: { width: 1280, height: 800 } } },
    ],
    webServer: external
        ? undefined
        : {
              command: `rm -f "${db}" && touch "${db}" && php artisan migrate:fresh --seed --force --no-interaction && cd public && php -S 127.0.0.1:${PORT} ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php`,
              url: `${baseURL}up`,
              reuseExistingServer: !process.env.CI,
              timeout: 120_000,
              stderr: 'ignore', // log de requisições do `php -S`; erros do Laravel ficam em storage/logs
              env: {
                  APP_ENV: 'local',
                  APP_DEBUG: 'true',
                  APP_URL: `http://127.0.0.1:${PORT}`,
                  DB_CONNECTION: 'sqlite',
                  DB_DATABASE: db,
                  CACHE_STORE: 'array',
                  SESSION_DRIVER: 'file',
                  MAIL_MAILER: 'array',
                  QUEUE_CONNECTION: 'sync',
                  HONEYPOT_SECONDS: '0', // o robô do teste preenche o formulário em menos de 1 s

                  PHP_CLI_SERVER_WORKERS: '4',
              },
          },
});
