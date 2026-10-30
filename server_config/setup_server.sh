#!/usr/bin/env bash
# ==============================================================================
# Linkora Production Server Setup Script
# Host: Swebetech-IT-Solutions
# Target OS: Ubuntu 22.04 / 24.04 LTS or Debian 12
# Domains: linkora.co.tz, www.linkora.co.tz, api.linkora.co.tz
# ==============================================================================

set -e

echo "=========================================================="
echo " Starting Linkora Server Deployment on Swebetech Server   "
echo "=========================================================="

# 1. Update system packages
apt update && apt upgrade -y

# 2. Install essential tools and web server packages
apt install -y nginx curl git unzip ufw certbot python3-certbot-nginx \
    php8.2-fpm php8.2-cli php8.2-mysql php8.2-curl php8.2-mbstring \
    php8.2-xml php8.2-zip php8.2-bcmath php8.2-intl php8.2-sqlite3

# 3. Install Composer if not installed
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# 4. Prepare Application Directory
WEB_ROOT="/var/www/linkora"
mkdir -p "$WEB_ROOT"
mkdir -p "$WEB_ROOT/public/downloads"
mkdir -p "$WEB_ROOT/storage/framework/cache/data"
mkdir -p "$WEB_ROOT/storage/framework/sessions"
mkdir -p "$WEB_ROOT/storage/framework/views"
mkdir -p "$WEB_ROOT/storage/logs"
mkdir -p "$WEB_ROOT/bootstrap/cache"

# 5. Set correct permissions
echo "Setting permissions for www-data..."
chown -R www-data:www-data "$WEB_ROOT"
chmod -R 775 "$WEB_ROOT/storage"
chmod -R 775 "$WEB_ROOT/bootstrap/cache"
chmod -R 775 "$WEB_ROOT/public/downloads"

# 6. Copy Nginx Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
if [ -f "$SCRIPT_DIR/nginx/linkora.conf" ]; then
    cp "$SCRIPT_DIR/nginx/linkora.conf" /etc/nginx/sites-available/linkora.conf
    ln -sf /etc/nginx/sites-available/linkora.conf /etc/nginx/sites-enabled/
    rm -f /etc/nginx/sites-enabled/default
fi

# 7. Test Nginx Configuration
nginx -t

# 8. Reload Nginx
systemctl reload nginx
systemctl enable nginx
systemctl enable php8.2-fpm
systemctl restart php8.2-fpm

echo "=========================================================="
echo " Next Steps to Complete Setup:                            "
echo " 1. Ensure DNS A-Records point to this server IP:         "
echo "    - linkora.co.tz                                       "
echo "    - www.linkora.co.tz                                   "
echo "    - api.linkora.co.tz                                   "
echo ""
echo " 2. Issue Free SSL Certificates via Certbot:              "
echo "    certbot --nginx -d linkora.co.tz -d www.linkora.co.tz -d api.linkora.co.tz"
echo ""
echo " 3. Copy your compiled Android APK to:                   "
echo "    $WEB_ROOT/public/downloads/linkora.apk                "
echo "=========================================================="
