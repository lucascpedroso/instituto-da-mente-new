# Deploy na Hostinger (hospedagem compartilhada Premium/Business)

Tudo roda na Hostinger: aplicação PHP (Laravel), banco MySQL, e-mail e arquivos enviados pelo painel.
O servidor **não precisa de Node.js**: os assets são gerados na sua máquina e enviados prontos.

> Use a conta Hostinger **do cliente**. Não é a conta ligada ao MCP neste ambiente.

---

## 1. Preparar o hPanel (uma única vez)

1. **Sites › Adicionar site** com o domínio do cliente. Aguarde a criação terminar.
2. **Avançado › Configuração do PHP**:
   - Versão: **PHP 8.3**
   - Extensões ativas: `intl`, `gd`, `pdo_mysql`, `mbstring`, `fileinfo`, `zip`, `exif`, `bcmath`, `openssl`
   - `memory_limit` ≥ 256M e `upload_max_filesize` / `post_max_size` ≥ 16M
3. **Segurança › SSL**: instale o SSL gratuito e ative "Forçar HTTPS".
4. **Avançado › Acesso SSH**: ative o SSH e anote **IP, porta (65002) e usuário (`u…`)**. Recomendado: adicione sua chave SSH pública.
5. **Bancos de dados › MySQL**: crie o banco e o usuário com uma **senha forte** e anote os três dados.
   O app se conecta em `localhost`. Não é preciso liberar "MySQL remoto".
6. **E-mails**: crie `contato@<domínio>` (é o remetente e quem recebe os avisos de novos contatos).

## 2. Estrutura de pastas no servidor

```
/home/u000000000/domains/<domínio>/
├── app/                 ← aplicação Laravel (enviada pelo deploy.sh)
│   ├── public/          ← pasta pública real
│   ├── storage/         ← uploads, logs, backups (nunca apagada pelo deploy)
│   └── .env             ← configurações de produção (criado à mão, nunca versionado)
└── public_html  →  app/public   (link simbólico)
```

Via SSH:

```bash
ssh -p 65002 u000000000@IP
cd ~/domains/<domínio>
mkdir -p app
mv public_html public_html_backup_$(date +%F)     # guarda o conteúdo padrão da Hostinger
ln -s app/public public_html
```

> **Se a Hostinger não aceitar o link simbólico** (raro): deixe o app dentro de `public_html/app`, crie `public_html/.htaccess` com
> `RewriteEngine On` e `RewriteRule ^(.*)$ app/public/$1 [L]`, e bloqueie o acesso direto às outras pastas.

## 3. Primeiro deploy

Na sua máquina:

```bash
cp .deploy.env.example .deploy.env      # preencha IP, porta, usuário e caminho
```

No servidor, crie o `.env` de produção:

```bash
cd ~/domains/<domínio>/app
# (na primeira vez a pasta está vazia: rode o deploy abaixo uma vez; ele para pedindo o .env)
cp .env.production.example .env
nano .env             # preencha APP_URL, DB_*, MAIL_*
php artisan key:generate
```

Na sua máquina:

```bash
./deploy.sh --seed    # primeiro deploy: cria as tabelas e o conteúdo inicial
```

Crie o administrador (no servidor):

```bash
cd ~/domains/<domínio>/app
php artisan make:filament-user      # nome, e-mail e senha forte do Ricardo/equipe
```

Acesse `https://<domínio>/admin`.

## 4. Deploys seguintes

```bash
./deploy.sh
```

O script:

1. Roda os testes.
2. Gera os assets.
3. Coloca o site em manutenção.
4. Envia os arquivos via rsync (sem tocar em `.env` e `storage/`).
5. Roda `composer install --no-dev`, as migrações e os caches.
6. Tira o site da manutenção.

## 5. Cron (tarefas agendadas)

**Avançado › Cron Jobs › Personalizado**, a cada minuto:

```
/usr/bin/php /home/u000000000/domains/<domínio>/app/artisan schedule:run
```

(Se o PHP do cron não for 8.3, use `/opt/alt/php83/usr/bin/php`.)

O cron executa:

- **03:00**: backup do banco em `storage/app/backups` (`.sql.gz`, mantém os 14 mais recentes).
- **Diário**: exclusão de contatos sem interação há mais de 2 anos (retenção LGPD).

O agendamento de posts do blog **não** depende do cron.

## 6. Checklist pós-publicação

- [ ] `https://<domínio>` abre com cadeado (SSL) e `http://` redireciona para `https://`.
- [ ] Enviar um formulário de teste em `/contato`. Ele deve chegar no e-mail e aparecer em Painel › Contatos recebidos.
- [ ] Painel › Configurações: conferir WhatsApp, e-mail, endereço e horários, e preencher Pixel/GA4/Ads.
- [ ] Subir uma imagem de teste (ex.: capa de livro) e ver se aparece no site.
- [ ] `https://<domínio>/robots.txt` mostra `Sitemap:` (em produção) e `/sitemap.xml` lista as páginas.
- [ ] Enviar o sitemap ao Google Search Console.
- [ ] `APP_DEBUG=false` no `.env`.

## 7. Problemas comuns

| Sintoma | Solução |
|---|---|
| Erro 500 logo após o deploy | `tail -50 storage/logs/laravel-*.log`; confira `.env` e permissões (`chmod -R 775 storage bootstrap/cache`) |
| Imagens enviadas não aparecem | Confirme o link `public/storage → ../storage/app/public` (`ls -la public/storage`) |
| E-mails não chegam | Confira `MAIL_*` no `.env` (smtps, porta 465, senha da caixa). Os leads **continuam salvos** no painel mesmo se o e-mail falhar |
| `composer` usa outra versão de PHP | Em `.deploy.env`: `DEPLOY_COMPOSER="/opt/alt/php83/usr/bin/php /usr/local/bin/composer"` |
| Alterou o `.env` e nada mudou | `php artisan optimize` (recria o cache de configuração) |
| Painel sem estilos | `php artisan filament:assets` |

## Restaurar um backup

```bash
gunzip -c storage/app/backups/backup-AAAA-MM-DD-HHMMSS.sql.gz | mysql -u USUARIO -p NOME_DO_BANCO
```

Também é possível importar o `.sql` (descompactado) pelo **phpMyAdmin** do hPanel.
Além disso, a Hostinger mantém backups automáticos da conta em **Arquivos › Backups**.
