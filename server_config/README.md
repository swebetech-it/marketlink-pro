# Mwongozo wa Kuweka Linkora Kwenye Seva ya Apache (Swebetech-IT-Solutions)

Mwongozo huu unaelekeza hatua kwa hatua jinsi ya kusanidi **Linkora** (`linkora.co.tz`) na **API Gateway** (`api.linkora.co.tz`) kwenye seva inayotumia **Apache 2 (`apache2`)**.

---

## 1. Mipangilio ya DNS A-Records
Kwenye dashibodi ya msajili wa domain yako (`.co.tz`), hakikisha A-Records hizi zimeelekezwa kwenye IP ya seva yako:

| Rekodi | Jina (Host) | Aina | IP ya Seva |
|---|---|---|---|
| Domain Kuu (Landing Page) | `@` (linkora.co.tz) | A | `IP_YA_SEVA_YAKO` |
| Subdomain ya Web | `www` (www.linkora.co.tz) | A | `IP_YA_SEVA_YAKO` |
| API Gateway | `api` (api.linkora.co.tz) | A | `IP_YA_SEVA_YAKO` |

---

## 2. Amri za Kuendesha Seva: `root@Swebetech-IT-Solutions:~#`

### Hatua ya 1: Sakinisha Apache, Moduli Zake na PHP
```bash
apt update && apt upgrade -y
apt install -y apache2 certbot python3-certbot-apache curl git unzip \
    php8.2 php8.2-cli php8.2-mysql php8.2-curl php8.2-mbstring \
    php8.2-xml php8.2-zip php8.2-bcmath php8.2-intl php8.2-sqlite3 libapache2-mod-php8.2
```

### Hatua ya 2: Washa Moduli Muhimu za Apache (Modules)
Laravel inahitaji `mod_rewrite` ili njia (routes) zifanye kazi, pamoja na `mod_headers` na `mod_ssl`:
```bash
a2enmod rewrite
a2enmod headers
a2enmod ssl
a2enmod deflate
a2enmod http2
```

### Hatua ya 3: Weka Faili za Mfumo Seva
```bash
# Tengeneza folda ya mfumo
mkdir -p /var/www/linkora

# Hamisha faili za backend kwenye /var/www/linkora
cd /var/www/linkora

# Sakinisha Composer dependencies
composer install --no-dev --optimize-autoloader

# Weka .env
cp .env.example .env
nano .env
```
*(Hakikisha umeweka `APP_URL=https://linkora.co.tz`, `API_URL=https://api.linkora.co.tz`, na maelezo ya database)*

```bash
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Hatua ya 4: Ruhusa za Apache (Permissions) & Folda ya APK
```bash
# Folda ya kupakulia Android APK
mkdir -p /var/www/linkora/public/downloads

# Ruhusa za www-data
chown -R www-data:www-data /var/www/linkora
chmod -R 775 /var/www/linkora/storage
chmod -R 775 /var/www/linkora/bootstrap/cache
chmod -R 775 /var/www/linkora/public/downloads
```

### Hatua ya 5: Weka Faili ya APK ya Android
Baada ya ku-compile APK kwenye Android Studio:
Nakili faili ya `linkora.apk` moja kwa moja kwenye:
```bash
/var/www/linkora/public/downloads/linkora.apk
```
Watumiaji wakitembelea `linkora.co.tz` au kubofya kitufe cha **"Pakua APK"**, faili hii itapakuliwa papo hapo kwenye simu zao.

---

## 3. Kusanidi VirtualHost ya Apache

1. Weka faili ya `linkora.conf` kwenye Apache:
```bash
cp /var/www/linkora/server_config/apache/linkora.conf /etc/apache2/sites-available/linkora.conf
```

2. Washa tovuti ya Linkora na uzime tovuti ya awali ya Apache:
```bash
a2ensite linkora.conf
a2dissite 000-default.conf
```

3. Hakiki muundo wa Apache (Test Configuration):
```bash
apache2ctl configtest
# Unapaswa kuona: "Syntax OK"
```

4. Anzisha upya Apache:
```bash
systemctl restart apache2
systemctl enable apache2
```

---

## 4. Kufunga Cheti cha Bure cha SSL (Let's Encrypt / HTTPS)

Endesha amri hii moja ya Certbot ya Apache:
```bash
certbot --apache -d linkora.co.tz -d www.linkora.co.tz -d api.linkora.co.tz
```
- Certbot itagundua VirtualHosts kiotomatiki.
- Chagua kuelekeza kila kitu kwenye HTTPS (Redirect HTTP to HTTPS).
- Certbot itasasisha faili za Apache na kureload huduma kiotomatiki!

---

## 5. Jaribu Kuangalia Kwenye Kivinjari (Testing)
1. **Landing Page:** Fungua `https://linkora.co.tz` &rarr; Itaonyesha ukurasa mkuu wa ajira za muda mfupi na mrefu, Gemini AI, na kitufe cha kupakua APK.
2. **Pakua APK:** Fungua `https://linkora.co.tz/download` &rarr; Inapakua faili ya `linkora.apk`.
3. **API Gateway:** Fungua `https://api.linkora.co.tz` &rarr; Itarudisha JSON ya hali ya API (`"status": "online"`).
4. **Admin Portal:** Fungua `https://linkora.co.tz/admin` &rarr; Itaonyesha dashibodi ya usimamizi.
