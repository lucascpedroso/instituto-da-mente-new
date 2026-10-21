import { test, expect } from '@playwright/test';
import { isMobile, skipCookieBanner } from './helpers';

const NAV_LABELS = ['Início', 'O Instituto', 'Terapias', 'Formação', 'Profissionais', 'Livros', 'Blog', 'Contato'];

const menu = (page) => page.getByRole('dialog', { name: 'Menu' });
const openMenu = async (page) => {
    await page.getByRole('button', { name: 'Abrir menu' }).click();
    await expect(menu(page)).toBeVisible();
    // aguarda o fim da animação de entrada (painel encostado na borda direita)
    const panel = menu(page).getByRole('navigation');
    await expect.poll(async () => {
        const box = await panel.boundingBox();
        return Math.round(box.x + box.width);
    }).toBe(page.viewportSize().width);
};

test('menu mobile fica acima do aviso de cookies', async ({ page }, testInfo) => {
    test.skip(!isMobile(testInfo), 'somente em telas pequenas');
    await page.goto('./');
    await expect(page.getByRole('dialog', { name: 'Aviso de cookies' })).toBeVisible();

    await openMenu(page);
    const cta = menu(page).getByRole('link', { name: 'Falar no WhatsApp' });
    await expect(cta).toBeInViewport();
    // o elemento no ponto central do botão deve ser o próprio botão (nada por cima)
    const box = await cta.boundingBox();
    const onTop = await page.evaluate(([x, y]) => document.elementFromPoint(x, y)?.closest('a')?.textContent?.trim(), [box.x + box.width / 2, box.y + box.height / 2]);
    expect(onTop).toContain('Falar no WhatsApp');
});

test.describe('menu mobile', () => {
    test.beforeEach(async ({ page }, testInfo) => {
        test.skip(!isMobile(testInfo), 'somente em telas pequenas');
        await skipCookieBanner(page);
        await page.goto('./');
    });

    test('abre ocupando a tela inteira, com todos os links visíveis', async ({ page }) => {
        await openMenu(page);

        const viewport = page.viewportSize();
        const panel = await menu(page).getByRole('navigation').boundingBox();
        expect(panel.y).toBe(0);
        expect(panel.height).toBe(viewport.height);

        for (const label of NAV_LABELS) {
            const item = menu(page).getByRole(['Terapias', 'Formação'].includes(label) ? 'button' : 'link', { name: label, exact: true });
            await expect(item).toBeInViewport();
        }
        await expect(menu(page).getByRole('link', { name: 'Agendar sessão' })).toBeInViewport();
    });

    test('bloqueia a rolagem e o conteúdo atrás enquanto está aberto', async ({ page }) => {
        const pageScrollLocked = () => page.evaluate(() => getComputedStyle(document.documentElement).overflow === 'hidden');
        // leitores de tela só "enxergam" o menu enquanto ele está aberto
        const behindIsInert = () => page.evaluate(() => ['body > header', 'main', 'body > footer'].every((s) => document.querySelector(s).getAttribute('aria-hidden') === 'true'));

        await openMenu(page);
        expect(await pageScrollLocked()).toBe(true);
        expect(await behindIsInert()).toBe(true);

        await page.keyboard.press('Escape');
        await expect(menu(page)).toBeHidden();
        await expect.poll(pageScrollLocked).toBe(false);
        await expect.poll(behindIsInert).toBe(false);
    });

    test('fecha sozinho se a tela passar para o tamanho desktop (ex.: girar o tablet)', async ({ page }) => {
        await openMenu(page);
        await page.setViewportSize({ width: 1100, height: 800 });
        await expect(menu(page)).toBeHidden();
        await expect.poll(() => page.evaluate(() => getComputedStyle(document.documentElement).overflow)).not.toBe('hidden');
    });

    test('fecha com Esc, pelo botão X e tocando fora', async ({ page }) => {
        await openMenu(page);
        await page.keyboard.press('Escape');
        await expect(menu(page)).toBeHidden();

        await openMenu(page);
        await menu(page).getByRole('button', { name: 'Fechar menu' }).click();
        await expect(menu(page)).toBeHidden();

        await openMenu(page);
        await page.mouse.click(10, page.viewportSize().height / 2);
        await expect(menu(page)).toBeHidden();
    });

    test('leva o foco para dentro do menu e devolve ao fechar (teclado)', async ({ page }) => {
        await page.getByRole('button', { name: 'Abrir menu' }).focus();
        await page.keyboard.press('Enter');
        await expect(menu(page)).toBeVisible();
        await expect(menu(page).getByRole('button', { name: 'Fechar menu' })).toBeFocused();

        await page.keyboard.press('Escape');
        await expect(page.getByRole('button', { name: 'Abrir menu' })).toBeFocused();
    });

    test('expande os submenus de Terapias e Formação', async ({ page }) => {
        await openMenu(page);
        const toggle = menu(page).getByRole('button', { name: 'Terapias', exact: true });
        await expect(toggle).toHaveAttribute('aria-expanded', 'false');
        await toggle.click();
        await expect(toggle).toHaveAttribute('aria-expanded', 'true');

        const child = menu(page).getByRole('link', { name: 'Terapia de casal' });
        await child.scrollIntoViewIfNeeded();
        await expect(child).toBeVisible();
        await child.click();
        await expect(page).toHaveURL(/terapias\/terapia-de-casal\/?$/);
        await expect(page.getByRole('heading', { level: 1 })).toHaveText('Terapia de casal');
    });

    test('fecha ao tocar num link para a mesma página (âncora)', async ({ page }) => {
        await page.goto('contato');
        await openMenu(page);
        await menu(page).getByRole('link', { name: 'Agendar sessão' }).click();
        await expect(menu(page)).toBeHidden();
        await expect(page.locator('#agendar')).toBeInViewport();
    });

    test('navega para cada página do menu', async ({ page }) => {
        for (const label of ['O Instituto', 'Profissionais', 'Livros', 'Blog', 'Contato']) {
            await openMenu(page);
            await menu(page).getByRole('link', { name: label, exact: true }).click();
            await expect(menu(page)).toBeHidden();
            await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
        }
    });
});

test.describe('menu desktop', () => {
    test.beforeEach(async ({ page }, testInfo) => {
        test.skip(isMobile(testInfo), 'somente em telas grandes');
        await skipCookieBanner(page);
    });

    for (const width of [1024, 1280, 1440]) {
        test(`cabe em uma linha a ${width}px`, async ({ page }) => {
            await page.setViewportSize({ width, height: 800 });
            await page.goto('./');
            const nav = page.getByRole('navigation', { name: 'Menu principal' });
            await expect(nav).toBeVisible();
            await expect(page.getByRole('button', { name: 'Abrir menu' })).toBeHidden();

            const tops = await nav.locator(':scope > ul > li').evaluateAll((lis) => lis.map((li) => Math.round(li.getBoundingClientRect().top)));
            expect(new Set(tops).size, 'itens do menu quebraram linha').toBe(1);

            const header = await page.locator('body > header').boundingBox();
            expect(header.height).toBeLessThanOrEqual(80);
            const cta = await page.locator('body > header').getByRole('link', { name: 'Agendar sessão' }).boundingBox();
            expect(cta.x + cta.width).toBeLessThanOrEqual(width);
        });
    }

    test('submenu abre ao passar o mouse e fecha com Esc ou ao sair pelo teclado', async ({ page }) => {
        await page.goto('./');
        const nav = page.getByRole('navigation', { name: 'Menu principal' });
        const toggle = nav.getByRole('button', { name: 'Terapias' });

        await toggle.hover();
        await expect(nav.getByRole('link', { name: 'Terapia de casal' })).toBeVisible();
        await page.mouse.move(5, 500);
        await expect(nav.getByRole('link', { name: 'Terapia de casal' })).toBeHidden();

        await toggle.focus();
        await page.keyboard.press('Enter');
        await expect(toggle).toHaveAttribute('aria-expanded', 'true');
        await page.keyboard.press('Escape');
        await expect(toggle).toHaveAttribute('aria-expanded', 'false');
        await expect(toggle).toBeFocused();

        // abre e sai do submenu com Tab: ele não pode ficar aberto atrás do foco
        await page.keyboard.press('Enter');
        const links = await nav.locator('li:has(> button:text("Terapias")) a').count();
        for (let i = 0; i <= links; i++) await page.keyboard.press('Tab');
        await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    });

    test('marca a página atual no menu', async ({ page }) => {
        await page.goto('blog');
        await expect(page.getByRole('navigation', { name: 'Menu principal' }).getByRole('link', { name: 'Blog' })).toHaveAttribute('aria-current', 'page');
    });
});
