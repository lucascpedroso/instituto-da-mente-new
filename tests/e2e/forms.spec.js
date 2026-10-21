import { test, expect } from '@playwright/test';
import { isStaticPreview, skipCookieBanner } from './helpers';

test.beforeEach(async ({ page }) => {
    await skipCookieBanner(page);
});

/** O topo do elemento precisa estar visível abaixo do cabeçalho fixo. */
async function expectBelowStickyHeader(page, locator) {
    await expect(locator).toBeInViewport();
    const header = await page.locator('body > header').boundingBox();
    const box = await locator.boundingBox();
    expect(box.y, 'o início do formulário ficou escondido atrás do cabeçalho').toBeGreaterThanOrEqual(header.y + header.height - 1);
}

test.describe('formulário de contato', () => {
    test.beforeEach(async ({ page }) => {
        await page.goto('contato');
        test.skip(await isStaticPreview(page), 'a pré-visualização estática não tem formulários');
    });

    const form = (page) => page.locator('form#agendar-form');

    test('mostra os erros no próprio formulário, sem ficar atrás do cabeçalho', async ({ page }) => {
        await form(page).getByRole('button', { name: 'Enviar' }).click();

        await expect(page).toHaveURL(/#agendar-form$/);
        await expect(form(page).getByText('O campo nome é obrigatório.')).toBeVisible();
        await expect(form(page).getByText('Informe um telefone com DDD', { exact: false }).or(form(page).getByText('telefone/WhatsApp'))).toBeVisible();
        await expect(form(page).getByText('Para continuar, é preciso concordar')).toBeVisible();
        await expect(form(page).getByLabel('Nome')).toHaveAttribute('aria-invalid', 'true');
        await expectBelowStickyHeader(page, form(page));
    });

    test('mantém o que foi digitado quando há erro', async ({ page }) => {
        await form(page).getByLabel('Nome').fill('Maria');
        await form(page).getByLabel('Cursos e formação').check();
        await form(page).getByRole('button', { name: 'Enviar' }).click();

        await expect(form(page).getByLabel('Nome')).toHaveValue('Maria');
        await expect(form(page).getByLabel('Cursos e formação')).toBeChecked();
        await expect(form(page).getByLabel('Curso de interesse')).toBeVisible();
    });

    test('mostra só os campos do assunto escolhido', async ({ page }) => {
        const therapy = form(page).getByLabel('Tipo de atendimento');
        const course = form(page).getByLabel('Curso de interesse');
        const modality = form(page).getByText('Modalidade de preferência');

        await expect(therapy).toBeVisible();
        await expect(modality).toBeVisible();
        await expect(course).toBeHidden();

        await form(page).getByLabel('Cursos e formação').check();
        await expect(course).toBeVisible();
        await expect(course).toBeEnabled();
        await expect(therapy).toBeHidden();
        await expect(therapy).toBeDisabled();
        await expect(modality).toBeHidden();

        await form(page).getByLabel('Outro assunto').check();
        await expect(course).toBeHidden();
        await expect(therapy).toBeHidden();
    });

    test('formata o telefone enquanto o visitante digita', async ({ page }) => {
        const phone = form(page).getByLabel('WhatsApp / telefone');
        await phone.pressSequentially('19991234567');
        await expect(phone).toHaveValue('(19) 99123-4567');

        await phone.fill('');
        await phone.pressSequentially('1932345678');
        await expect(phone).toHaveValue('(19) 3234-5678');
    });

    test('aceita número colado com +55 (copiado do WhatsApp)', async ({ page }) => {
        const phone = form(page).getByLabel('WhatsApp / telefone');
        await phone.fill('+55 19 99123-4567');
        await phone.dispatchEvent('input');
        await expect(phone).toHaveValue('(19) 99123-4567');
    });

    test('envia e leva para a página de obrigado', async ({ page }) => {
        await form(page).getByLabel('Nome').fill('Maria da Silva');
        await form(page).getByLabel('WhatsApp / telefone').pressSequentially('19991234567');
        await form(page).getByLabel('Tipo de atendimento').selectOption({ label: 'Terapia de casal' });
        await form(page).getByLabel('Online').check();
        await form(page).getByRole('checkbox').check();
        await form(page).getByRole('button', { name: 'Enviar' }).click();

        await expect(page).toHaveURL(/\/obrigado$/);
        await expect(page.getByRole('heading', { level: 1 })).toHaveText('Recebemos sua mensagem!');
        await expect(page.getByText('combinar o melhor horário')).toBeVisible();

        // recarregar não mostra a página de conversão de novo
        await page.reload();
        await expect(page).toHaveURL(/\/$/);
    });
});

test('inscrição em curso a partir da página do curso', async ({ page }) => {
    await page.goto('formacao/formacao-em-psicanalise-clinica');
    test.skip(await isStaticPreview(page), 'a pré-visualização estática não tem formulários');

    await page.getByRole('link', { name: 'Quero me inscrever' }).first().click();
    const form = page.locator('form#inscricao-form');
    await expect(page.locator('#inscricao')).toBeInViewport();

    await form.getByRole('button', { name: 'Quero me inscrever' }).click();
    await expect(page).toHaveURL(/#inscricao-form$/);
    await expectBelowStickyHeader(page, form);

    await form.getByLabel('Nome').fill('João Souza');
    await form.getByLabel('WhatsApp / telefone').pressSequentially('11987654321');
    await form.getByRole('checkbox').check();
    await form.getByRole('button', { name: 'Quero me inscrever' }).click();
    await expect(page.getByText('Obrigado pelo interesse em nossa formação')).toBeVisible();
});

test.describe('depoimentos', () => {
    test.beforeEach(async ({ page }) => {
        await page.goto('depoimentos');
        test.skip(await isStaticPreview(page), 'a pré-visualização estática não tem formulários');
    });

    const section = (page) => page.locator('#deixe-seu-depoimento');

    test('erros de validação aparecem na tela (volta para o formulário)', async ({ page }) => {
        // texto curto demais: só o servidor valida o mínimo de 20 caracteres
        await section(page).getByLabel('Nome ou iniciais').fill('A. S.');
        await section(page).getByLabel('Seu depoimento').fill('Muito bom.');
        await section(page).getByRole('checkbox').check();
        await section(page).getByRole('button', { name: 'Enviar depoimento' }).click();

        const error = section(page).getByText('Conte um pouco mais sobre a sua experiência');
        await expect(error).toBeVisible();
        await expect(error).toBeInViewport();
        await expect(section(page).getByLabel('Nome ou iniciais')).toHaveValue('A. S.');
        await expect(section(page).getByLabel('Seu depoimento')).toHaveValue('Muito bom.');
    });

    test('confirmação de envio aparece na tela', async ({ page }) => {
        await section(page).getByLabel('Nome ou iniciais').fill('A. S.');
        await section(page).getByLabel('Você é').selectOption('aluno');
        await section(page).getByLabel('Seu depoimento').fill('A formação mudou a forma como eu atendo meus pacientes.');
        await section(page).getByRole('checkbox').check();
        await section(page).getByRole('button', { name: 'Enviar depoimento' }).click();

        const ok = page.getByRole('status').filter({ hasText: 'Obrigado pelo seu depoimento!' });
        await expect(ok).toBeVisible();
        await expect(ok).toBeInViewport();
    });
});

test('pré-visualização: formulários viram WhatsApp', async ({ page }) => {
    await page.goto('contato');
    test.skip(!(await isStaticPreview(page)), 'somente na pré-visualização estática');
    await expect(page.locator('form[method="POST"]')).toHaveCount(0);
    await expect(page.locator('#agendar').getByRole('link', { name: 'Conversar no WhatsApp' })).toHaveAttribute('href', /^https:\/\/wa\.me\/\d+\?text=/);
});
