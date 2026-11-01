#!/usr/bin/env bash
# ==============================================================================
# Linkora Automated Server Deployment Script
# Automatically pulls changes, runs migrations, provisions tenant databases,
# rebuilds application caches, and reloads web services.
# ==============================================================================

set -e

# Mark git directories as safe to prevent "dubious ownership" warnings
git config --global --add safe.directory "*" 2>/dev/null || true

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

# Ensure we operate from repository root for git pull
cd "$REPO_DIR"

echo "=========================================================="
echo " Starting Linkora Automated Server Update at $(date)       "
echo " Repository Directory: $REPO_DIR                          "
echo "=========================================================="

# 1. Pull Latest Changes from Git
echo ""
echo "[1/5] Fetching latest code from origin/main..."
if [ -d ".git" ]; then
    git fetch origin main
    git reset --hard origin/main
else
    echo "Warning: .git directory not found in $REPO_DIR, skipping git reset."
fi

# Locate the Laravel backend directory
BACKEND_DIR="$REPO_DIR"
if [ -d "$REPO_DIR/backend" ] && [ -f "$REPO_DIR/backend/artisan" ]; then
    BACKEND_DIR="$REPO_DIR/backend"
fi

cd "$BACKEND_DIR"
echo "Active Backend Directory: $BACKEND_DIR"

# Ensure .env exists
if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    echo "No .env found, copying from .env.example..."
    cp .env.example .env
    php artisan key:generate --force || true
fi

# 2. Update Composer Dependencies
echo ""
echo "[2/5] Updating Composer autoloader and dependencies..."
if command -v composer &> /dev/null; then
    composer install --no-dev --optimize-autoloader --no-interaction
else
    echo "Warning: Composer not found in PATH, skipping composer install."
fi

# 3. Run Automated Platform Update (Migrations, Tenant Databases, Caches)
echo ""
echo "[3/5] Running platform migrations and tenant database provisioning..."
php artisan app:update --force

# 4. Fix Storage, Cache, and Downloads Permissions
echo ""
echo "[4/5] Verifying permissions for www-data..."
chown -R www-data:www-data storage bootstrap/cache public/downloads 2>/dev/null || true
chmod -R 775 storage bootstrap/cache public/downloads 2>/dev/null || true

# 5. Gracefully Reload Web Server and PHP-FPM
echo ""
echo "[5/5] Reloading web server services..."
for PHP_VER in php8.4-fpm php8.3-fpm php8.2-fpm; do
    if systemctl is-active --quiet "$PHP_VER" 2>/dev/null; then
        echo "Reloading $PHP_VER..."
        systemctl reload "$PHP_VER" || true
    fi
done

if systemctl is-active --quiet nginx 2>/dev/null; then
    echo "Reloading Nginx..."
    systemctl reload nginx || true
elif systemctl is-active --quiet apache2 2>/dev/null; then
    echo "Reloading Apache2..."
    systemctl reload apache2 || true
fi

echo ""
echo "=========================================================="
echo " Linkora Update Completed Successfully!                  "
echo "=========================================================="
