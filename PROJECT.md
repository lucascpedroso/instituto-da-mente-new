# Instituto da Mente — PROJECT.md

Documento vivo que acompanha o PDF **"Instituto da Mente — Arquitetura do Site" (v0.1)**, em `docs/briefing/`.
Atualize a cada decisão tomada.

## Resumo

Site institucional completo, em português, com os dois pilares do Instituto:

- **Clínica**: terapias e atendimentos, com agendamento por formulário e WhatsApp.
- **Formação**: cursos, com formulário de inscrição e WhatsApp. O cliente apontou a venda de cursos como possível objetivo nº 1.

O site também tem Livros, Blog, Profissionais, Depoimentos, FAQ, Contato, páginas LGPD e o painel `/admin`.

## Stack (decisão final)

| Camada | Escolha | Observação |
|---|---|---|
| Linguagem / framework | **PHP 8.3 + Laravel 12** | Roda na hospedagem compartilhada **Premium** da Hostinger |
| Banco de dados | **MySQL/MariaDB da Hostinger** | Mesmo servidor, `DB_HOST=localhost` |
| Painel admin | **Filament 4** (`/admin`) | Editor rico baseado em Tiptap, uploads, perfis |
| Frontend | Blade + **Tailwind CSS 4** + Alpine.js | Build com Vite **local**; o servidor não precisa de Node |
| Imagens | Disco da Hostinger + otimização WebP (Intervention/GD) | Substitui o Cloudinary |
| E-mail | SMTP da Hostinger | Notificação de cada novo lead |
| Fontes | Cormorant Garamond + Inter, auto-hospedadas | Sem chamadas ao Google Fonts (LGPD) |
| Testes | Pest (57 testes) | Rodam em SQLite e MySQL |

### Por que mudou em relação ao PDF (seção 5)

O PDF recomendava Next.js na Vercel + Cloudinary + MySQL remoto na Hostinger. Duas decisões mudaram isso:

1. **Tudo deve ficar na Hostinger**, inclusive o banco (pedido do cliente/equipe).
2. O plano contratado é o **Premium/Single**, que **não roda Node.js**. Só PHP + MySQL.

Laravel + Filament entrega **todas as funcionalidades do PDF** nesse plano, sem Vercel, sem MySQL remoto e sem serviços externos pagos:

- CRUD completo de Livros e Blog, com editor visual, rascunho, agendamento e SEO.
- Módulos recomendados: Profissionais, aprovação de Depoimentos e caixa de Mensagens/Leads.
- Perfis de acesso: Administrador (tudo) e Editor (somente Blog e Livros).
- SEO completo: meta tags, Open Graph, JSON-LD, sitemap e robots.

## Decisões registradas

| Data | Decisão |
|---|---|
| 28/09/2026 | Hospedagem: Hostinger Premium (PHP), conta **diferente** da conectada ao MCP |
| 28/09/2026 | Stack Laravel + Filament em vez de Next.js/Vercel (motivo acima) |
| 28/09/2026 | Cursos: venda via **formulário de interesse + WhatsApp** no lançamento. Cada curso tem um campo opcional "Link de pagamento" (Hotmart, Mercado Pago…) que troca o botão para checkout, sem código |
| 28/09/2026 | Terapias: **uma página por especialidade** (`/terapias/{slug}`), para servir de página de destino de Google/Meta Ads |
| 28/09/2026 | Valores de sessões **não são públicos** ("por sessão ou pacote, sob consulta"), conforme o formulário do cliente |
| 28/09/2026 | Rastreamento (Meta Pixel, GA4, Google Ads) e mapa só carregam **após consentimento de cookies** (LGPD) |
| 28/09/2026 | Formulários não pedem dados de saúde (minimização de dados sensíveis LGPD) |
| 28/09/2026 | Posts iniciais do blog entram como **rascunho em produção**, para revisão do Ricardo |

## Mapa do site (implementado)

| URL | Página |
|---|---|
| `/` | Início |
| `/sobre` | Sobre o Instituto (história, missão, visão, valores, diferenciais) |
| `/terapias`, `/terapias/{slug}` | Terapias e atendimentos (12 especialidades) |
| `/formacao`, `/formacao/{slug}` | Formação em Psicanálise + 5 cursos |
| `/profissionais`, `/profissionais/{slug}` | Equipe |
| `/livros`, `/livros/{slug}` | Livros |
| `/blog`, `/blog/categoria/{slug}`, `/blog/{slug}` | Blog (busca, categorias, paginação) |
| `/depoimentos` | Depoimentos + formulário de envio (moderado) |
| `/duvidas` | FAQ |
| `/contato` | Contato / agendamento + mapa |
| `/obrigado` | Página de conversão (dispara Lead/generate_lead) |
| `/politica-de-privacidade`, `/termos-de-uso` | LGPD |
| `/sitemap.xml`, `/robots.txt` | SEO |
| `/admin` | Painel (Filament) |

## Painel `/admin`

| Menu | O que faz |
|---|---|
| Contatos recebidos | Todos os leads (contato, agendamento, inscrição), com status, anotações, origem (UTM/Google Ads/Meta Ads), exportação CSV e exclusão (pedidos LGPD) |
| Depoimentos | Aprovar ou rejeitar depoimentos enviados pelo site; destaque na home |
| Blog / Categorias / Tags | Posts com editor visual, capa, rascunho/publicado/agendado, SEO |
| Livros | Capa, sinopse, link de compra, preço, status (publicado/rascunho/esgotado), destaque |
| Cursos e formação | Cursos, carga horária, formato, certificação, link de pagamento opcional |
| Terapias | Especialidades, ícone, textos, visibilidade |
| Profissionais | Equipe, foto, especialidades, registro (CRP), WhatsApp próprio opcional |
| Dúvidas frequentes | Perguntas por grupo, exibição na home |
| Configurações | WhatsApp, telefone, e-mail, endereço, horários, CNPJ, IDs de Pixel/GA4/Google Ads |
| Usuários do painel | Administradores e editores |

## Estrutura do código

```
app/
  Http/Controllers/Site/   páginas públicas, leads, sitemap
  Http/Middleware/CaptureUtm.php   origem da visita → leads
  Http/Requests/LeadRequest.php    validação dos formulários
  Models/                  Book, Course, Faq, Lead, Post, Professional, Setting, Testimonial, Therapy…
  Filament/                recursos do painel, página de Configurações, widgets
  Services/ImageOptimizer.php      WebP + miniatura
  Support/Site.php         WhatsApp, JSON-LD
  Console/Commands/BackupDatabase.php
resources/views/
  components/layouts/site.blade.php   layout (SEO, pixels, cookies)
  components/site/*        header, footer, formulários, cards…
  site/*                   páginas
database/seeders/ContentSeeder.php    conteúdo real do briefing
```

## Desenvolvimento local

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed     # SQLite por padrão; cria admin@example.com / password
npm run dev                    # em outro terminal: php artisan serve
php artisan test
```

## Identidade visual

A paleta do manual do cliente está em `resources/css/app.css` (`@theme`). Os logos estão em `public/images/` e os originais em `resources/brand/`.
O laranja `#F17A20` é usado com texto escuro, porque texto branco sobre ele não atinge o contraste AA. Para textos laranja sobre fundo claro, o site usa a variação `laranja-escuro` `#B3530C`.

Veja também: `PENDENCIAS.md` e `DEPLOY-HOSTINGER.md`.
