#!/bin/bash
# ═══════════════════════════════════════════════════════════════
# setup-server.sh — Первоначальная настройка сервера
# Запускать от root: sudo ./setup-server.sh
# ═══════════════════════════════════════════════════════════════

set -euo pipefail

echo "▶ Настройка сервера ArtDecor..."
echo "  OS: $(lsb_release -ds 2>/dev/null || cat /etc/os-release 2>/dev/null | head -1)"

# ─── Системные обновления ────────────────────────────────────
echo "▶ Обновление системы..."
apt-get update -qq
apt-get upgrade -y -qq
apt-get autoremove -y -qq

# ─── Установка зависимостей ──────────────────────────────────
echo "▶ Установка PHP 8.3 и расширений..."
apt-get install -y -qq \
  ca-certificates apt-transport-https software-properties-common curl gnupg \
  nginx \
  php8.3-fpm php8.3-cli php8.3-common php8.3-pgsql \
  php8.3-mbstring php8.3-xml php8.3-curl php8.3-gd php8.3-imagick \
  php8.3-zip php8.3-bcmath php8.3-intl php8.3-redis php8.3-bz2 \
  postgresql-client-16 \
  redis-tools \
  supervisor \
  certbot python3-certbot-nginx \
  git unzip htop

# ─── Установка Composer ──────────────────────────────────────
echo "▶ Установка Composer..."
if ! command -v composer &>/dev/null; then
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php composer-setup.php --install-dir=/usr/local/bin --filename=composer
  php -r "unlink('composer-setup.php');"
fi

# ─── Установка Node.js 22 ─────────────────────────────────────
echo "▶ Установка Node.js 22..."
if ! command -v node &>/dev/null; then
  curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
  apt-get install -y -qq nodejs
fi

# ─── Оптимизация PHP ─────────────────────────────────────────
echo "▶ Оптимизация PHP..."
PHP_INI="/etc/php/8.3/fpm/php.ini"
sed -i 's/upload_max_filesize = .*/upload_max_filesize = 20M/' "$PHP_INI"
sed -i 's/post_max_size = .*/post_max_size = 25M/' "$PHP_INI"
sed -i 's/max_execution_time = .*/max_execution_time = 120/' "$PHP_INI"
sed -i 's/memory_limit = .*/memory_limit = 256M/' "$PHP_INI"
sed -i 's/;date.timezone =/date.timezone = Europe\/Moscow/' "$PHP_INI"

# ─── Настройка nginx ──────────────────────────────────────────
echo "▶ Настройка nginx..."
cat > /etc/nginx/nginx.conf << 'EOF'
user www-data;
worker_processes auto;
pid /run/nginx.pid;

events {
    worker_connections 2048;
    multi_accept on;
}

http {
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    types_hash_max_size 2048;
    client_max_body_size 25M;

    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    # Gzip
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml application/json application/javascript text/javascript image/svg+xml;

    # Cache
    open_file_cache max=2000 inactive=20s;
    open_file_cache_valid 60s;
    open_file_cache_min_uses 2;

    # Security
    server_tokens off;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;

    include /etc/nginx/conf.d/*.conf;
    include /etc/nginx/sites-enabled/*;
}
EOF

# ─── Настройка Supervisor для Horizon ────────────────────────
echo "▶ Настройка Supervisor..."
cat > /etc/supervisor/conf.d/horizon.conf << 'EOF'
[program:horizon]
process_name=%(program_name)s
command=php /var/www/artdecor/artisan horizon
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/horizon.log
stopwaitsecs=3600
EOF

# ─── Настройка прав ───────────────────────────────────────────
echo "▶ Настройка прав..."
mkdir -p /var/www
chown -R www-data:www-data /var/www 2>/dev/null || true

# ─── Перезапуск сервисов ─────────────────────────────────────
echo "▶ Перезапуск сервисов..."
systemctl restart php8.3-fpm
systemctl enable php8.3-fpm
systemctl restart nginx
systemctl enable nginx

# ─── Firewall ─────────────────────────────────────────────────
echo "▶ Настройка firewall..."
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw --force enable

# ─── Версии ───────────────────────────────────────────────────
echo ""
echo "═══════════════════════════════════════════════════════"
echo "  Сервер настроен!"
echo "═══════════════════════════════════════════════════════"
php -v | head -1
nginx -v 2>&1
node -v 2>/dev/null || echo "  Node: not installed"
composer --version 2>/dev/null | head -1
echo "  PostgreSQL client: $(psql --version 2>/dev/null | head -1)"
echo "═══════════════════════════════════════════════════════"
