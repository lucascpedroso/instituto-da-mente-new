#!/usr/bin/env bash
# Deploy do Instituto da Mente para a Hostinger (hospedagem compartilhada, via SSH + rsync).
# Uso: ./deploy.sh            -> deploy normal
#      ./deploy.sh --seed     -> primeiro deploy: também popula o conteúdo inicial
set -euo pipefail

cd "$(dirname "$0")"

if [[ ! -f .deploy.env ]]; then
    echo "Crie o arquivo .deploy.env a partir de .deploy.env.example" >&2
    exit 1
fi
# shellcheck disable=SC1091
source .deploy.env

SSH="ssh -p ${DEPLOY_PORT} ${DEPLOY_USER}@${DEPLOY_HOST}"
ARTISAN="${DEPLOY_PHP} ${DEPLOY_PATH}/artisan"

echo "==> Testes"
php artisan test --compact

echo "==> Build dos assets (Vite)"
npm ci --no-audit --no-fund
npm run build

echo "==> Modo manutenção"
$SSH "test -f ${DEPLOY_PATH}/artisan && ${ARTISAN} down --retry=30 || true"

echo "==> Enviando arquivos"
$SSH "mkdir -p ${DEPLOY_PATH}"
rsync -az --delete \
    -e "ssh -p ${DEPLOY_PORT}" \
    --exclude='.git/' \
    --exclude='.env' \
    --exclude='.deploy.env' \
    --exclude='node_modules/' \
    --exclude='vendor/' \
    --exclude='/storage/' \
    --exclude='/public/storage' \
    --exclude='/public/hot' \
    --exclude='/tests/' \
    --exclude='/docs/' \
    --exclude='/database/database.sqlite' \
    --exclude='/*.pdf' \
    --exclude='/Logo Instituto*' \
    --exclude='/Paleta Cores*' \
    ./ "${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}/"

echo "==> Instalando dependências e atualizando o banco"
$SSH bash -s <<REMOTE
set -euo pipefail
cd "${DEPLOY_PATH}"
mkdir -p storage/app/public storage/app/backups storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
if [[ ! -f .env ]]; then
    echo "ERRO: crie ${DEPLOY_PATH}/.env a partir de .env.production.example antes do deploy." >&2
    exit 1
fi
${DEPLOY_COMPOSER} install --no-dev --optimize-autoloader --no-interaction --no-progress
${DEPLOY_PHP} artisan migrate --force
if [[ "${1:-}" == "--seed" ]]; then ${DEPLOY_PHP} artisan db:seed --force; fi
[[ -L public/storage ]] || ln -s ../storage/app/public public/storage
${DEPLOY_PHP} artisan optimize:clear
${DEPLOY_PHP} artisan optimize
${DEPLOY_PHP} artisan filament:optimize
${DEPLOY_PHP} artisan up
REMOTE

echo "==> Deploy concluído"
