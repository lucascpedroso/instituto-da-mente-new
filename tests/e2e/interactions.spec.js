import { test, expect } from '@playwright/test';
import { isStaticPreview, skipCookieBanner } from './helpers';

test.describe('aviso de cookies (LGPD)', () => {
    const banner = (page) => page.getByRole('dialog', { name: 'Aviso de cookies' });

    test('aparece na primeira visita e lembra a escolha', async ({ page }) => {
        await page.goto('./');
        await expect(banner(page)).toBeVisible();
        await banner(page).getByRole('button', { name: 'Somente essenciais' }).click();
        await expect(banner(page)).toBeHidden();
        expect(await page.evaluate(() => localStorage.getItem('idm-consent'))).toBe('denied');

        await page.goto('sobre');
        await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
        await expect(banner(page)).toBeHidden();
        expect(await page.evaluate(() => window.__trackersLoaded)).toBeFalsy();
    });

    test('pode ser reaberto pelo rodapé e aceitar libera o mapa', async ({ page }) => {
        await skipCookieBanner(page);
        await page.goto('contato');
        const map = page.getByTitle('Mapa: localização do Instituto da Mente');
        await expect(map).toBeHidden();
        await expect(map).not.toHaveAttribute('src', /.+/);

        await page.getByRole('button', { name: 'Preferências de cookies' }).click();
        await expect(banner(page)).toBeVisible();
        await banner(page).getByRole('button', { name: 'Aceitar todos' }).click();
        await expect(banner(page)).toBeHidden();
        await expect(map).toHaveAttribute('src', /google\.com\/maps/);
        await expect(map).toBeVisible();
        expect(await page.evaluate(() => localStorage.getItem('idm-consent'))).toBe('granted');
    });

    test('"Permitir cookies" no mapa abre o aviso', async ({ page }) => {
        await skipCookieBanner(page);
        await page.goto('contato');
        await page.getByRole('button', { name: 'Permitir cookies' }).click();
        await expect(banner(page)).toBeVisible();
    });
});

test.describe('componentes', () => {
    test.beforeEach(async ({ page }) => skipCookieBanner(page));

    test('perguntas frequentes abrem e fecham', async ({ page }) => {
        await page.goto('duvidas');
        await expect(page.locator('main').getByRole('button', { expanded: true }).first()).toBeVisible();

        const item = page.locator('main [x-data]:has(> h3 > button)').nth(1);
        const q = item.getByRole('button');
        const answer = item.locator('p');
        await expect(answer).toBeHidden();
        await q.click();
        await expect(q).toHaveAttribute('aria-expanded', 'true');
        await expect(answer).toBeVisible();
        await q.click();
        await expect(answer).toBeHidden();
    });

    test('links de WhatsApp têm número e mensagem', async ({ page }) => {
        await page.goto('./');
        const hrefs = await page.locator('a[href*="wa.me"]').evaluateAll((as) => as.map((a) => a.href));
        expect(hrefs.length).toBeGreaterThan(2);
        for (const href of hrefs) {
            expect(href).toMatch(/^https:\/\/wa\.me\/55\d{10,11}\?text=.+/);
        }
    });

    test('busca do blog', async ({ page }) => {
        await page.goto('blog');
        test.skip(await isStaticPreview(page), 'sem busca na pré-visualização estática');
        const search = page.getByRole('searchbox', { name: 'Buscar no blog' });

        await search.fill('sessão');
        await search.press('Enter');
        await expect(page).toHaveURL(/busca=/);
        await expect(page.locator('main article')).not.toHaveCount(0);

        await search.fill('xyzinexistente');
        await search.press('Enter');
        await expect(page.getByText('Nenhum artigo encontrado para “xyzinexistente”.')).toBeVisible();
        await page.getByRole('link', { name: 'Ver todos os artigos' }).click();
        await expect(page.locator('main article')).not.toHaveCount(0);
    });

    test('link "Pular para o conteúdo" aparece com o teclado', async ({ page }, testInfo) => {
        test.skip(testInfo.project.name === 'mobile-safari', 'WebKit não foca links com Tab por padrão');
        await page.goto('./');
        await page.keyboard.press('Tab');
        const skip = page.getByRole('link', { name: 'Pular para o conteúdo' });
        await expect(skip).toBeFocused();
        await expect(skip).toBeInViewport();
    });
});
