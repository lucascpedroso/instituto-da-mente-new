import { expect } from '@playwright/test';

/** Páginas públicas usadas nas verificações de layout (as de detalhe vêm do sitemap/links). */
export const STATIC_PATHS = ['', 'sobre', 'terapias', 'formacao', 'profissionais', 'livros', 'blog', 'depoimentos', 'duvidas', 'contato', 'politica-de-privacidade', 'termos-de-uso'];

/** Esconde o aviso de cookies (como se o visitante já tivesse escolhido). */
export async function skipCookieBanner(page, value = 'denied') {
    await page.addInitScript((v) => {
        try {
            localStorage.setItem('idm-consent', v);
        } catch {}
    }, value);
}

export const isMobile = (testInfo) => testInfo.project.name.startsWith('mobile');

/** A pré-visualização estática (GitHub Pages) não tem formulários. */
export async function isStaticPreview(page) {
    return (await page.getByText('Pré-visualização', { exact: true }).count()) > 0;
}

/** Coleta erros de JS, erros de console e respostas 4xx/5xx da página. */
export function collectErrors(page) {
    const errors = [];
    page.on('pageerror', (e) => errors.push(`JS: ${e.message}`));
    page.on('console', (m) => m.type() === 'error' && errors.push(`console: ${m.text()}`));
    page.on('response', (r) => r.status() >= 400 && errors.push(`${r.status()} ${r.url()}`));
    return errors;
}

export async function expectNoHorizontalOverflow(page) {
    const { scrollWidth, clientWidth, culprits } = await page.evaluate(() => {
        const vw = document.documentElement.clientWidth;
        const culprits = [...document.querySelectorAll('body *')]
            .filter((el) => {
                const r = el.getBoundingClientRect();
                return r.width > 0 && r.right > vw + 1 && !el.closest('.overflow-hidden, [aria-hidden="true"], [x-cloak]');
            })
            .slice(0, 3)
            .map((el) => `<${el.tagName.toLowerCase()} class="${el.getAttribute('class') ?? ''}">`);
        return { scrollWidth: document.documentElement.scrollWidth, clientWidth: vw, culprits };
    });
    expect(scrollWidth, `conteúdo vazando na horizontal: ${culprits.join(', ')}`).toBeLessThanOrEqual(clientWidth);
}
