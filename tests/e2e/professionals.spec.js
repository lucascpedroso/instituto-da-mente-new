import { test, expect } from '@playwright/test';
import { expectNoHorizontalOverflow, isMobile, skipCookieBanner } from './helpers';

const lightbox = (page) => page.getByRole('dialog').filter({ has: page.getByRole('button', { name: 'Fechar perfil' }) });
const cards = (page) => page.getByRole('list', { name: 'Profissionais do Instituto' }).getByRole('button');
const slugFromHash = (page) => page.evaluate(() => decodeURIComponent(location.hash.slice(1)));

test.beforeEach(async ({ page }) => {
    await skipCookieBanner(page);
    await page.goto('profissionais');
});

test('carrossel mostra todos os profissionais sem vazar da página', async ({ page }) => {
    await expect(cards(page).first()).toBeVisible();
    const count = await cards(page).count();
    expect(count).toBeGreaterThan(0);
    await expectNoHorizontalOverflow(page);

    for (let i = 0; i < count; i++) {
        await expect(cards(page).nth(i)).toContainText(/Conhecer \S+/);
    }
});

test('setas e indicadores rolam o carrossel', async ({ page }) => {
    const track = page.getByRole('list', { name: 'Profissionais do Instituto' });
    const scrollable = await track.evaluate((t) => t.scrollWidth > t.clientWidth + 4);
    test.skip(!scrollable, 'todos os profissionais cabem na tela');

    const prev = page.getByRole('button', { name: 'Profissionais anteriores' });
    const next = page.getByRole('button', { name: 'Próximos profissionais' });
    await expect(prev).toBeDisabled();
    await expect(next).toBeEnabled();

    await next.click();
    await expect.poll(() => track.evaluate((t) => t.scrollLeft)).toBeGreaterThan(0);
    await expect(prev).toBeEnabled();

    // o último indicador leva ao fim e desativa a seta "próximos"
    const last = await cards(page).count();
    await page.getByRole('button', { name: /^Ir para / }).nth(last - 1).click();
    await expect(next).toBeDisabled();
    await expect(cards(page).last()).toBeInViewport();
});

test('clicar no card abre o perfil em lightbox, com link próprio', async ({ page }) => {
    const card = cards(page).first();
    const slug = await card.getAttribute('data-professional');
    const name = (await card.locator('span.font-serif').textContent()).trim();
    // pelo teclado: é quem depende do foco (o Safari não foca botões no clique do mouse)
    await card.focus();
    await page.keyboard.press('Enter');

    const box = lightbox(page);
    await expect(box).toBeVisible();
    await expect(box.getByRole('heading', { level: 2, name })).toBeVisible();
    await expect(box).toHaveAccessibleName(name);
    expect(await slugFromHash(page)).toBe(slug);

    const book = box.getByRole('link', { name: /^Agendar com / });
    await expect(book).toBeVisible();
    await expect(book).toHaveAttribute('href', new RegExp(`/profissionais/${slug}#agendar$`));
    await expect(box.getByRole('link', { name: 'WhatsApp' })).toHaveAttribute('href', /^https:\/\/wa\.me\/\d+\?text=/);
    await expect(box.getByRole('button', { name: 'Fechar perfil' })).toBeFocused();

    // o lightbox cabe na tela, e a página atrás não rola
    const vp = page.viewportSize();
    const article = box.locator('article:visible');
    await expect.poll(async () => {
        const r = await article.boundingBox(); // espera o fim da animação de entrada
        return r.y >= 0 && r.y + r.height <= vp.height + 1;
    }).toBe(true);
    expect(await page.evaluate(() => getComputedStyle(document.documentElement).overflow)).toBe('hidden');

    await page.keyboard.press('Escape');
    await expect(box).toBeHidden();
    expect(await slugFromHash(page)).toBe('');
    await expect(card).toBeFocused();
});

test('fecha pelo X, tocando fora e com o botão "voltar" do navegador', async ({ page }) => {
    await cards(page).first().click();
    await lightbox(page).getByRole('button', { name: 'Fechar perfil' }).click();
    await expect(lightbox(page)).toBeHidden();

    await cards(page).first().click();
    await expect(lightbox(page)).toBeVisible();
    await page.mouse.click(4, 4);
    await expect(lightbox(page)).toBeHidden();

    await cards(page).first().click();
    await expect(lightbox(page)).toBeVisible();
    await page.goBack();
    await expect(lightbox(page)).toBeHidden();
    await expect(page).toHaveURL(/\/profissionais$/);
});

test('navega entre perfis dentro do lightbox (botões e setas do teclado)', async ({ page }) => {
    const total = await cards(page).count();
    test.skip(total < 2, 'só há um profissional');
    const slugs = await cards(page).evaluateAll((bs) => bs.map((b) => b.dataset.professional));

    await cards(page).first().click();
    const box = lightbox(page);
    await expect(box.getByText(`1 de ${total}`)).toBeVisible();

    await box.getByRole('button', { name: 'Próximo' }).click();
    await expect(box.getByText(`2 de ${total}`)).toBeVisible();
    expect(await slugFromHash(page)).toBe(slugs[1]);
    // o foco do teclado acompanha o perfil novo (não fica preso no perfil que sumiu)
    await expect.poll(() => page.evaluate(() => document.activeElement?.closest('[data-profile]')?.dataset.profile)).toBe(slugs[1]);

    await page.keyboard.press('ArrowLeft');
    await expect(box.getByText(`1 de ${total}`)).toBeVisible();
    await page.keyboard.press('ArrowLeft');
    await expect(box.getByText(`${total} de ${total}`)).toBeVisible(); // volta ao último
    expect(await slugFromHash(page)).toBe(slugs[total - 1]);

    // fechar depois de navegar não deixa resto de #hash no endereço
    await page.keyboard.press('Escape');
    await expect(box).toBeHidden();
    expect(await slugFromHash(page)).toBe('');
});

test('link direto para um perfil abre o lightbox', async ({ page }) => {
    const card = cards(page).last();
    const slug = await card.getAttribute('data-professional');
    await page.goto(`profissionais#${slug}`);
    await page.reload();

    await expect(lightbox(page)).toBeVisible();
    await expect(lightbox(page).locator(`[data-profile="${slug}"]`)).toBeVisible();
    await page.keyboard.press('Escape');
    await expect(lightbox(page)).toBeHidden();
    await expect(page).toHaveURL(/\/profissionais$/);
});

test('no celular, o perfil abre como painel vindo de baixo e rola por dentro', async ({ page }, testInfo) => {
    test.skip(!isMobile(testInfo), 'somente em telas pequenas');
    await cards(page).first().click();
    const article = lightbox(page).locator('article:visible');
    await expect.poll(async () => {
        const r = await article.boundingBox();
        return Math.round(r.y + r.height);
    }).toBe(page.viewportSize().height);

    const scrolls = await article.evaluate((a) => a.scrollHeight > a.clientHeight);
    if (scrolls) {
        await article.evaluate((a) => a.scrollTo(0, a.scrollHeight));
        await expect(lightbox(page).getByRole('link', { name: /^Agendar com / })).toBeInViewport();
        await expect(lightbox(page).getByRole('button', { name: 'Fechar perfil' })).toBeInViewport();
    }
});
