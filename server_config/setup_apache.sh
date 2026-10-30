#!/usr/bin/env bash
# ==============================================================================
# Linkora Apache Production Deployment Script
# Host: Swebetech-IT-Solutions
# Target OS: Ubuntu 22.04 / 24.04 LTS or Debian 12
# Web Server: Apache 2 (httpd)
# Domains: linkora.co.tz, www.linkora.co.tz, api.linkora.co.tz
# ==============================================================================

set -e

echo "=========================================================="
echo " Starting Linkora Apache Setup on Swebetech-IT-Solutions  "
echo "=========================================================="

# 1. Update system packages
apt update && apt upgrade -y

# 2. Install Apache, Certbot for Apache, and PHP extensions
apt install -y apache2 certbot python3-certbot-apache curl git unzip \
    php8.2 php8.2-cli php8.2-mysql php8.2-curl php8.2-mbstring \
    php8.2-xml php8.2-zip php8.2-bcmath php8.2-intl php8.2-sqlite3 libapache2-mod-php8.2

# 3. Enable essential Apache modules for Laravel & SSL
echo "Enabling Apache modules..."
a2enmod rewrite
a2enmod ssl
a2enmod headers
a2enmod deflate
a2enmod http2

# 4. Install Composer if not installed
if ! command -v composer &> /dev/null; then
    echo "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# 5. Prepare Web Root Directory
WEB_ROOT="/var/www/linkora"
mkdir -p "$WEB_ROOT"
mkdir -p "$WEB_ROOT/public/downloads"
mkdir -p "$WEB_ROOT/storage/framework/cache/data"
mkdir -p "$WEB_ROOT/storage/framework/sessions"
mkdir -p "$WEB_ROOT/storage/framework/views"
mkdir -p "$WEB_ROOT/storage/logs"
mkdir -p "$WEB_ROOT/bootstrap/cache"

# 6. Set Linux Permissions for Apache (www-data)
echo "Setting permissions for www-data..."
chown -R www-data:www-data "$WEB_ROOT"
chmod -R 775 "$WEB_ROOT/storage"
chmod -R 775 "$WEB_ROOT/bootstrap/cache"
chmod -R 775 "$WEB_ROOT/public/downloads"

# 7. Configure Apache VirtualHost
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
if [ -f "$SCRIPT_DIR/apache/linkora.conf" ]; then
    cp "$SCRIPT_DIR/apache/linkora.conf" /etc/apache2/sites-available/linkora.conf
    a2ensite linkora.conf
    a2dissite 000-default.conf || true
fi

# 8. Test Apache Configuration
apache2ctl configtest

# 9. Restart Apache
systemctl restart apache2
systemctl enable apache2

echo "=========================================================="
echo " Apache is successfully running!                          "
echo ""
echo " Run Certbot to activate SSL (HTTPS) for all domains:     "
echo " certbot --apache -d linkora.co.tz -d www.linkora.co.tz -d api.linkora.co.tz"
echo ""
echo " Place your compiled APK file at:                         "
echo " $WEB_ROOT/public/downloads/linkora.apk                   "
echo "=========================================================="
