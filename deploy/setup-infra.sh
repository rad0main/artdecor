#!/bin/bash
# ═══════════════════════════════════════════════════════════════
# setup-infra.sh — Создание инфраструктуры в Yandex Cloud
# Использование: ./setup-infra.sh <folder-id>
# ═══════════════════════════════════════════════════════════════

set -euo pipefail

YANDEX_FOLDER=${1:?"Укажите ID каталога: ./setup-infra.sh <folder-id>"}
VM_NAME="artdecor-vm"
DB_NAME="artdecor-db"
REDIS_NAME="artdecor-redis"
SA_NAME="artdecor-sa"
BUCKET_NAME="artdecor-images"

echo "▶ Создание инфраструктуры ArtDecor в Yandex Cloud..."
echo "  Каталог: $YANDEX_FOLDER"

# ─── Service Account ──────────────────────────────────────────
echo "▶ Создание сервисного аккаунта..."
SA_ID=$(yc iam service-account create \
  --name "$SA_NAME" \
  --folder-id "$YANDEX_FOLDER" \
  --format json | jq -r '.id')

yc resource-manager folder add-access-binding \
  --id "$YANDEX_FOLDER" \
  --role storage.editor \
  --subject serviceAccount:"$SA_ID"

yc iam key create \
  --service-account-id "$SA_ID" \
  --output key.json 2>/dev/null

echo "  SA ID: $SA_ID"
echo "  Ключ сохранён в key.json"

# ─── Object Storage ──────────────────────────────────────────
echo "▶ Создание бакета Object Storage..."
yc storage bucket create \
  --name "$BUCKET_NAME" \
  --folder-id "$YANDEX_FOLDER" \
  --default-storage-class standard \
  --max-size 10737418240 2>/dev/null || echo "  Бакет уже существует"

yc storage bucket update \
  --name "$BUCKET_NAME" \
  --public-read 2>/dev/null || true

echo "  Бакет: $BUCKET_NAME"

# ─── Managed PostgreSQL ──────────────────────────────────────
echo "▶ Создание Managed PostgreSQL..."
PG_ID=$(yc managed-postgresql cluster create \
  --name "$DB_NAME" \
  --folder-id "$YANDEX_FOLDER" \
  --environment production \
  --network-name default \
  --host zone-id=ru-central1-b,subnet-id=default \
  --resource-preset s2.micro \
  --disk-size 10737418240 \
  --disk-type network-ssd \
  --postgresql-version 15 \
  --database name=artdecor \
  --user name=artdecor,password="$(openssl rand -base64 24)" \
  --format json 2>&1 | tail -1 | jq -r '.id')

PG_HOST=$(yc managed-postgresql cluster list-hosts "$PG_ID" \
  --folder-id "$YANDEX_FOLDER" \
  --format json | jq -r '.[0].name // "localhost"')

echo "  PostgreSQL: $PG_HOST"

# ─── Managed Redis ───────────────────────────────────────────
echo "▶ Создание Managed Redis..."
REDIS_ID=$(yc managed-redis cluster create \
  --name "$REDIS_NAME" \
  --folder-id "$YANDEX_FOLDER" \
  --environment production \
  --network-name default \
  --host zone-id=ru-central1-b,subnet-id=default \
  --resource-presist b1.medium \
  --disk-size 10737418240 \
  --redis-version 7 \
  --format json 2>&1 | tail -1 | jq -r '.id')

REDIS_HOST=$(yc managed-redis cluster list-hosts "$REDIS_ID" \
  --folder-id "$YANDEX_FOLDER" \
  --format json | jq -r '.[0].name // "localhost"')

echo "  Redis: $REDIS_HOST"

# ─── VM ───────────────────────────────────────────────────────
echo "▶ Создание VM..."
SSH_KEY="${HOME}/.ssh/yc_artdecor"
if [ ! -f "$SSH_KEY" ]; then
  ssh-keygen -t ed25519 -f "$SSH_KEY" -N "" -C "artdecor"
fi

VM_ID=$(yc compute instance create \
  --name "$VM_NAME" \
  --folder-id "$YANDEX_FOLDER" \
  --zone ru-central1-b \
  --platform standard-v3 \
  --cores 2 \
  --memory 4 \
  --disk size=30,type=network-ssd \
  --preemptible \
  --network-interface subnet-name=default,nat-ip-version=ipv4 \
  --create-boot-disk image-folder-id=standard-images,image-family=ubuntu-2404-lts \
  --ssh-key "$SSH_KEY.pub" \
  --format json | jq -r '.id')

VM_IP=$(yc compute instance get "$VM_ID" \
  --folder-id "$YANDEX_FOLDER" \
  --format json | jq -r '.network_interfaces[0].primary_v4_address.one_to_one_nat.address')

echo "  VM: $VM_NAME ($VM_IP)"

# ─── Вывод информации ────────────────────────────────────────
echo ""
echo "═══════════════════════════════════════════════════════"
echo "  Инфраструктура готова!"
echo "═══════════════════════════════════════════════════════"
echo "  VM IP:      $VM_IP"
echo "  SSH ключ:   $SSH_KEY"
echo "  Подключение: ssh -i ${SSH_KEY} ubuntu@${VM_IP}"
echo ""
echo "  PostgreSQL: $PG_HOST"
echo "  Redis:      $REDIS_HOST"
echo "  Bucket:     $BUCKET_NAME"
echo ""
echo "  Далее:"
echo "  1. scp -i ${SSH_KEY} deploy/setup-server.sh ubuntu@${VM_IP}:~/"
echo "  2. ssh -i ${SSH_KEY} ubuntu@${VM_IP}"
echo "  3. sudo ./setup-server.sh"
echo "  4. scp -i ${SSH_KEY} .env ubuntu@${VM_IP}:/var/www/artdecor/"
echo "  5. ssh -i ${SSH_KEY} ubuntu@${VM_IP} 'cd /var/www/artdecor && ./deploy/deploy.sh'"
echo "═══════════════════════════════════════════════════════"
