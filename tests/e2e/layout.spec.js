import { test, expect } from '@playwright/test';
import { STATIC_PATHS, collectErrors, expectNoHorizontalOverflow, skipCookieBanner } from './helpers';

/**
 * Percorre todas as páginas públicas (a partir dos links do próprio site) e verifica, em cada
 * tamanho de tela: sem rolagem horizontal, sem erros de JS/rede, imagens carregando e
 * âncoras (#id) apontando para elementos que existem.
 */
test('todas as páginas: layout sem vazamentos, sem erros e sem links/imagens quebrados', async ({ page, baseURL }, testInfo) => {
    test.setTimeout(240_000);
    await skipCookieBanner(page);

    const widths = testInfo.project.name === 'desktop-chrome' ? [768, 1024, 1280] : [page.viewportSize().width];
    const root = new URL(baseURL);
    const normalize = (href) => {
        const u = new URL(href, baseURL);
        u.hash = '';
        u.search = '';
        u.pathname = u.pathname.replace(/\/$/, '') || '/';
        return u.href;
    };

    const queue = STATIC_PATHS.map((p) => normalize(new URL(p, baseURL).href));
    const seen = new Set();
    const images = new Set();
    const anchors = new Map(); // página → ids exigidos por links de outras páginas
    const ids = new Map();

    while (queue.length) {
        const url = queue.shift();
        if (seen.has(url)) continue;
        seen.add(url);

        const errors = collectErrors(page);
        for (const width of widths) {
            await page.setViewportSize({ width, height: 900 });
            const response = await page.goto(url, { waitUntil: 'networkidle' });
            expect.soft(response.status(), url).toBe(200);
            await test.step(`${url} @ ${width}px`, async () => {
                await expectNoHorizontalOverflow(page).catch((e) => expect.soft(null, `${url} @ ${width}px: ${e.message}`).toBeTruthy());
            });
        }
        expect.soft(errors, `erros em ${url}`).toEqual([]);

        const info = await page.evaluate(() => ({
            links: [...document.querySelectorAll('a[href]')].map((a) => a.href),
            imgs: [...document.images].map((i) => i.currentSrc || i.src).filter(Boolean),
            ids: [...document.querySelectorAll('[id]')].map((e) => e.id),
            h1: document.querySelectorAll('h1').length,
            title: document.title,
        }));
        ids.set(url, new Set(info.ids));
        info.imgs.forEach((src) => images.add(src));
        expect.soft(info.h1, `${url} deve ter exatamente um <h1>`).toBe(1);
        expect.soft(info.title, `${url} sem <title>`).not.toBe('');

        for (const href of info.links) {
            const u = new URL(href);
            if (u.origin !== root.origin || !u.pathname.startsWith(root.pathname.replace(/\/$/, '')) || u.pathname.includes('/storage/')) continue;
            const target = normalize(href);
            if (u.hash.length > 1) {
                if (!anchors.has(target)) anchors.set(target, new Map());
                anchors.get(target).set(decodeURIComponent(u.hash.slice(1)), url);
            }
            if (!seen.has(target)) queue.push(target);
        }
    }

    expect(seen.size).toBeGreaterThan(20);

    for (const [target, wanted] of anchors) {
        for (const [id, from] of wanted) {
            expect.soft(ids.get(target)?.has(id), `link ${target}#${id} (em ${from}) aponta para âncora inexistente`).toBe(true);
        }
    }

    for (const src of images) {
        const res = await page.request.get(src);
        expect.soft(res.status(), `imagem quebrada: ${src}`).toBe(200);
    }
});

test('a página 404 é amigável e não é indexada', async ({ page }) => {
    await skipCookieBanner(page);
    const response = await page.goto('pagina-que-nao-existe');
    expect(response.status()).toBe(404);
    await expect(page.getByRole('heading', { level: 1 })).toHaveText('Esta página não foi encontrada');
    await expect(page.locator('meta[name="robots"]')).toHaveAttribute('content', /noindex/);
    await expect(page.getByRole('link', { name: 'Ir para o início' })).toBeVisible();
});

test('o botão flutuante do WhatsApp não cobre o rodapé no fim da página', async ({ page }) => {
    await skipCookieBanner(page);
    await page.goto('./');
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    const float = page.getByRole('link', { name: 'Conversar pelo WhatsApp' });
    await expect(float).toBeInViewport();
    const fb = await float.boundingBox();

    const covered = await page.evaluate(({ x, y, w, h }) => {
        const out = [];
        for (const el of document.querySelectorAll('footer a, footer button')) {
            const r = el.getBoundingClientRect();
            if (r.right > x && r.left < x + w && r.bottom > y && r.top < y + h) out.push(el.textContent.trim());
        }
        return out;
    }, { x: fb.x, y: fb.y, w: fb.width, h: fb.height });
    expect(covered, 'links do rodapé ficam escondidos atrás do botão flutuante').toEqual([]);
});
