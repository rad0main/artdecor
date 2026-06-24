#!/bin/bash
# ═══════════════════════════════════════════════════════════════
# deploy.sh — Деплой ArtDecor (запускать из корня проекта)
# ═══════════════════════════════════════════════════════════════

set -euo pipefail

APP_DIR="$(cd "$(dirname "$0")/.." && pwd)"
echo "▶ Деплой ArtDecor в $APP_DIR"

cd "$APP_DIR"

# ─── Проверка .env ───────────────────────────────────────────
if [ ! -f .env ]; then
    echo "❌ Файл .env не найден!"
    echo "   Скопируйте .env.example в .env и настройте параметры"
    exit 1
fi

# ─── PHP dependencies ────────────────────────────────────────
echo "▶ Установка PHP-зависимостей..."
composer install --no-dev --optimize-autoloader --no-interaction --quiet

# ─── Frontend build ──────────────────────────────────────────
echo "▶ Сборка фронтенда..."
if [ -d node_modules ]; then
    npm ci --silent 2>/dev/null || npm install --silent
else
    npm ci --silent || npm install
fi
npm run build --silent

# ─── Laravel initialization ──────────────────────────────────
echo "▶ Инициализация Laravel..."

# Key generation
if ! grep -q "APP_KEY=" .env || [ -z "$(grep 'APP_KEY=' .env | cut -d= -f2)" ]; then
    php artisan key:generate --force --quiet
    echo "  APP_KEY сгенерирован"
fi

# Storage link
php artisan storage:link --force --quiet 2>/dev/null || true

# ─── Cache ────────────────────────────────────────────────────
echo "▶ Кэширование..."
php artisan config:cache --quiet
php artisan route:cache --quiet
php artisan view:cache --quiet

# ─── Migrations ───────────────────────────────────────────────
echo "▶ Миграции..."
php artisan migrate --force --quiet

# ─── Horizon ──────────────────────────────────────────────────
echo "▶ Настройка Horizon..."
php artisan horizon:publish --quiet 2>/dev/null || true

# ─── Restart queues ──────────────────────────────────────────
echo "▶ Перезапуск очередей..."
php artisan horizon:terminate 2>/dev/null || true
supervisorctl reread 2>/dev/null || true
supervisorctl update 2>/dev/null || true
supervisorctl start horizon 2>/dev/null || true

# ─── Restart PHP-FPM ─────────────────────────────────────────
echo "▶ Перезапуск PHP-FPM..."
if command -v systemctl &>/dev/null; then
    systemctl reload php8.3-fpm 2>/dev/null || systemctl restart php8.3-fpm 2>/dev/null || true
fi

# ─── Health check ────────────────────────────────────────────
echo ""
echo "▶ Проверка..."
if command -v curl &>/dev/null; then
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/ 2>/dev/null || echo "000")
    echo "  Главная: HTTP $HTTP_CODE"
    API_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/api/catalog/categories 2>/dev/null || echo "000")
    echo "  API:     HTTP $API_CODE"
fi

echo ""
echo "═══════════════════════════════════════════════════════"
echo "  Деплой завершён!"
echo "  Проверьте сайт: https://artdecor.ru"
echo "  Админка:        https://artdecor.ru/admin"
echo "═══════════════════════════════════════════════════════"
