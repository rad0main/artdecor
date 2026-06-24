# Развёртывание ArtDecor в Yandex Cloud

> Дата: 2026-06-24  
> Стек: nginx + PHP 8.3-FPM + PostgreSQL 15 + Redis + Laravel 11

---

## Содержание

1. [Создание инфраструктуры](#1-создание-инфраструктуры)
2. [Первоначальная настройка сервера](#2-первоначальная-настройка-сервера)
3. [Деплой приложения](#3-деплой-приложения)
4. [Настройка GitHub Actions (CI/CD)](#4-настройка-github-actions-cicd)
5. [Мониторинг и бэкапы](#5-мониторинг-и-бэкапы)

---

## 1. Создание инфраструктуры

### 1.1. Установите Yandex Cloud CLI

```bash
# macOS / Linux
curl -sSL https://storage.yandexcloud.net/yandexcloud-yc/install.sh | bash

# Windows PowerShell
iex "& { $(irm https://storage.yandexcloud.net/yandexcloud-yc/install.ps1) }"
```

### 1.2. Аутентификация

```bash
yc init
```

### 1.3. Создайте ресурсы через скрипт

```bash
# Сделайте скрипт исполняемым
chmod +x deploy/setup-infra.sh

# Запустите (укажите ваш ID каталога)
./deploy/setup-infra.sh <folder-id>
```

Скрипт создаст:
- **VM** (2 vCPU, 4 ГБ RAM, Ubuntu 24.04) с публичным IP
- **Managed PostgreSQL** (1 vCPU, 2 ГБ RAM, 5 ГБ HDD)
- **Managed Redis** (1 vCPU, 1 ГБ RAM)
- **Service account + Object Storage** для изображений
- **DNS-запись** A для домена

### 1.4. Или создайте вручную через веб-консоль

1. **VM**: Compute Cloud → 2 vCPU, 4 ГБ RAM, Ubuntu 24.04 LTS, публичный IP
2. **PostgreSQL**: Managed Service for PostgreSQL → 1 vCPU, 2 ГБ, 5 ГБ
3. **Redis**: Managed Service for Redis → 1 vCPU, 1 ГБ
4. **Object Storage**: Создайте бакет `artdecor-images` → публичный доступ через CDN
5. **DNS**: Добавьте A-запись для домена

---

## 2. Первоначальная настройка сервера

Подключитесь к VM по SSH:

```bash
ssh -i ~/.ssh/yc_key ubuntu@<PUBLIC_IP>
```

Затем запустите скрипт настройки сервера:

```bash
# Скопируйте скрипт на сервер
scp -i ~/.ssh/yc_key deploy/setup-server.sh ubuntu@<PUBLIC_IP>:~/

# Запустите на сервере
ssh -i ~/.ssh/yc_key ubuntu@<PUBLIC_IP>
chmod +x setup-server.sh
sudo ./setup-server.sh
```

Скрипт установит:
- nginx + PHP 8.3-FPM + расширения
- PostgreSQL 15 + Redis
- Composer + Node.js 22
- Supervisor (для Horizon)
- Certbot (SSL)
- Настроит и оптимизирует все компоненты

---

## 3. Деплой приложения

### 3.1. Клонируйте репозиторий

```bash
cd /var/www
sudo git clone https://github.com/rad0main/artdecor.git
sudo chown -R ubuntu:ubuntu artdecor
cd artdecor
```

### 3.2. Настройте .env

```bash
cp .env.example .env
# Отредактируйте .env — укажите данные БД, Redis, Object Storage
nano .env
```

Минимальные обязательные параметры:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://artdecor.ru

DB_CONNECTION=pgsql
DB_HOST=<POSTGRESQL_HOST>
DB_PORT=5432
DB_DATABASE=artdecor
DB_USERNAME=artdecor
DB_PASSWORD=<PASSWORD>

REDIS_HOST=<REDIS_HOST>
REDIS_PORT=6379

YC_KEY_ID=<KEY_ID>
YC_KEY_SECRET=<KEY_SECRET>
YC_BUCKET=artdecor-images

MAIL_USERNAME=info@skinali.moscow
MAIL_PASSWORD=<SMTP_PASSWORD>
```

### 3.3. Запустите деплой

```bash
chmod +x deploy/deploy.sh
./deploy/deploy.sh
```

Скрипт выполнит:
- `composer install --no-dev --optimize-autoloader`
- `npm ci && npm run build`
- `php artisan key:generate`
- `php artisan migrate --force`
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`
- `php artisan storage:link`
- Настройку supervisor для Horizon
- Перезагрузку PHP-FPM

### 3.4. Настройте nginx

```bash
sudo cp deploy/nginx.conf /etc/nginx/sites-available/artdecor
sudo ln -sf /etc/nginx/sites-available/artdecor /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### 3.5. Получите SSL-сертификат

```bash
sudo certbot --nginx -d artdecor.ru -d www.artdecor.ru
```

### 3.6. Настройте cron

```bash
crontab -e
# Добавьте:
* * * * * cd /var/www/artdecor && php artisan schedule:run >> /dev/null 2>&1
```

---

## 4. Настройка GitHub Actions (CI/CD)

Создайте файл `.github/workflows/deploy.yml`:

```yaml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Deploy to Yandex Cloud
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.YC_HOST }}
          username: ubuntu
          key: ${{ secrets.YC_SSH_KEY }}
          script: |
            cd /var/www/artdecor
            git pull origin main
            ./deploy/deploy.sh
```

Добавьте секреты в GitHub:
- `YC_HOST` — публичный IP сервера
- `YC_SSH_KEY` — приватный SSH-ключ

---

## 5. Мониторинг и бэкапы

### 5.1. Laravel Horizon

```bash
# Статус очередей
php artisan horizon:status

# Мониторинг (доступен /admin/horizon для администраторов)
```

### 5.2. Бэкапы БД

```bash
# Создайте бэкап вручную
pg_dump -h <PG_HOST> -U artdecor artdecor > backup_$(date +%Y%m%d).sql

# Автоматический бэкап (через cron)
0 3 * * * pg_dump -h <PG_HOST> -U artdecor artdecor | gzip > /backups/artdecor_$(date +%Y%m%d).sql.gz
```

### 5.3. Мониторинг Yandex Cloud

В консоли Yandex Cloud настройте:
- **Yandex Monitoring**: алерты по CPU/RAM на VM
- **Yandex Logging**: сбор логов nginx и PHP
- **Telescope**: `https://artdecor.ru/telescope` (только dev-окружение)

### 5.4. Проверка деплоя

```bash
# Проверьте healthcheck
curl https://artdecor.ru/up

# Проверьте страницы
curl -I https://artdecor.ru
curl -I https://artdecor.ru/izobrazheniya
curl -I https://artdecor.ru/primerka

# Проверьте API
curl https://artdecor.ru/api/catalog/categories
curl https://artdecor.ru/api/catalog/colors
```
