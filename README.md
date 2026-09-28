# Instituto da Mente — site institucional

Site em português do **Instituto da Mente** (Campinas/SP): clínica de psicanálise e terapia para toda a família, e instituto de formação de psicanalistas.

- **Stack:** Laravel 12 · Filament 4 (painel `/admin`) · Tailwind CSS 4 · Alpine.js · MySQL
- **Hospedagem:** Hostinger (compartilhada Premium, PHP + MySQL)

| Documento | Conteúdo |
|---|---|
| [PROJECT.md](PROJECT.md) | Arquitetura, decisões, mapa do site, painel |
| [DEPLOY-HOSTINGER.md](DEPLOY-HOSTINGER.md) | Passo a passo de publicação e manutenção |
| [PENDENCIAS.md](PENDENCIAS.md) | Conteúdos e dados que faltam do cliente |
| `docs/briefing/` | PDFs originais (arquitetura, formulário, missão/visão/valores) |

## Rodando localmente

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed          # admin local: admin@example.com / password
composer run dev                    # servidor + Vite
php artisan test
```

## Pré-visualização no GitHub Pages

A cada push na `main`, o workflow `.github/workflows/pages-preview.yml` publica uma **versão estática** do site em
`https://lucascpedroso.github.io/instituto-da-mente-new/` para o cliente revisar layout e textos.
Nela os formulários são trocados por botões de WhatsApp, não há `/admin` e as páginas têm `noindex`.
Requer **Settings › Pages › Source: GitHub Actions**. O site oficial continua na Hostinger (`DEPLOY-HOSTINGER.md`).
