#!/usr/bin/env bash
# Deploy agency-platform to testagency.online.pr via SSH.
# Run from repo root: ./deploy.sh
# Optional: SSH_PASS='yourpassword' ./deploy.sh

set -e

REMOTE_USER="root"
REMOTE_HOST="88.99.210.44"
REMOTE_PATH="/home/online-testagency/htdocs/testagency.online.pr"
LOCAL_PATH="$(cd "$(dirname "$0")" && pwd)"

echo "Deploying from $LOCAL_PATH to $REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH"

SSH_OPTS="${SSH_KEY:-}"
if [ -n "${SSH_PASS:-}" ]; then
  command -v sshpass >/dev/null 2>&1 || { echo "SSH_PASS set but sshpass not installed. brew install sshpass"; exit 1; }
  RSYNC_RSH="sshpass -p $SSH_PASS ssh"
else
  RSYNC_RSH="ssh"
fi

# Sync files (exclude dev and generated). Protect storage/installed from --delete.
RSYNC_RSH="$RSYNC_RSH" rsync -avz --delete \
  --exclude='.git' \
  --exclude='node_modules' \
  --exclude='vendor' \
  --exclude='.env' \
  --exclude='.env.local' \
  --exclude='storage/logs/*' \
  --exclude='storage/framework/cache/data/*' \
  --exclude='storage/framework/sessions/*' \
  --exclude='storage/framework/views/*' \
  --exclude='bootstrap/cache/*' \
  --exclude='.phpunit.cache' \
  --exclude='public/hot' \
  --filter='P storage/installed' \
  "$LOCAL_PATH/" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/"

# Run on server
REMOTE_CMD="cd $REMOTE_PATH && \
  export PATH=\"\$PATH:/usr/local/bin\" && \
  (test -f .env || { cp .env.example .env; php artisan key:generate --force; echo 'Created .env'; }) && \
  sed -i 's/^CACHE_STORE=.*/CACHE_STORE=file/' .env 2>/dev/null || true && \
  sed -i 's/^SESSION_DRIVER=.*/SESSION_DRIVER=file/' .env 2>/dev/null || true && \
  (grep -q '^CACHE_STORE=' .env || echo 'CACHE_STORE=file' >> .env) && \
  (grep -q '^SESSION_DRIVER=' .env || echo 'SESSION_DRIVER=file' >> .env) && \
  php -v && \
  composer install --no-dev --optimize-autoloader --no-interaction && \
  npm ci 2>/dev/null || npm install && \
  npm run build && \
  php artisan package:discover && \
  php artisan config:cache && \
  php artisan route:cache && \
  php artisan view:cache && \
  mkdir -p storage/app/public && \
  php artisan storage:link 2>/dev/null || true && \
  chown -R online-testagency:online-testagency storage bootstrap/cache database public/storage 2>/dev/null || true && \
  chmod -R 775 storage bootstrap/cache database && \
  echo 'Deploy done.'"

if [ -n "${SSH_PASS:-}" ]; then
  sshpass -p "$SSH_PASS" ssh -o StrictHostKeyChecking=accept-new "$REMOTE_USER@$REMOTE_HOST" "$REMOTE_CMD"
else
  ssh $SSH_OPTS "$REMOTE_USER@$REMOTE_HOST" "$REMOTE_CMD"
fi

echo "Deployment finished. Site: https://testagency.online.pr"
