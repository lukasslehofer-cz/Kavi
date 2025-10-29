# Deployment Guide - new.kavi.cz

Návod pro nasazení nové verze Kavi projektu na Hostinger VPS server jako subdoména `new.kavi.cz`.

## 📋 Přehled

- **Cílová doména:** new.kavi.cz (staging)
- **Finální doména:** kavi.cz (po úspěšném testování)
- **Server:** Hostinger VPS s SSH root přístupem
- **Existující projekty:** kavi.cz (WordPress) + další Laravel projekty - MUSÍ zůstat nedotčeny!
- **Stack:** Laravel 10, PHP 8.2+, MySQL, Nginx, Node.js

---

## ⚠️ DŮLEŽITÉ - Multi-project server

**Pokud na serveru už běží jiné projekty, buď VELMI opatrný!**

### Co NIKDY nedělat:
- ❌ Neměň globální PHP konfiguraci (`php.ini`) bez analýzy dopadů na ostatní projekty
- ❌ Neupgraduj PHP verzi bez konzultace s ostatními projekty
- ❌ Nerestaruj služby bez nutnosti
- ❌ Neměň MySQL konfiguraci bez důkladného zvážení
- ❌ Nemažej žádné existující adresáře nebo konfigurace

### Co dělat:
- ✅ Vytvoř izolovaný adresář pro Kavi (`/var/www/new.kavi.cz`)
- ✅ Používej separátní Nginx konfiguraci
- ✅ Vytvoř samostatnou databázi
- ✅ Používej stejnou PHP verzi jako ostatní projekty (nebo vyšší, pokud je kompatibilní)
- ✅ Testuj na staging prostředí (new.kavi.cz) před jakýmikoli změnami

---

## 🚀 Fáze 1: Příprava serveru - Multi-project bezpečný setup

### 1.1 Připojení na server

```bash
ssh root@your-server-ip
```

### 1.2 Kontrola existujícího prostředí

**🔍 NEJDŘÍV ZJISTI, CO UŽ NA SERVERU BĚŽÍ!**

```bash
# Zkontroluj existující projekty
ls -la /var/www/

# Zkontroluj aktuální PHP verzi
php -v
php-fpm -v  # nebo php8.1-fpm -v, php8.2-fpm -v atd.

# Zkontroluj běžící PHP-FPM verze
systemctl list-units | grep php

# Zkontroluj existující Nginx konfigurace
ls -la /etc/nginx/sites-enabled/

# Zkontroluj MySQL/MariaDB
mysql --version
systemctl status mysql || systemctl status mariadb

# Zkontroluj databáze
mysql -u root -p -e "SHOW DATABASES;"

# Zkontroluj disk space
df -h

# Zkontroluj RAM
free -h
```

**📝 Poznamenej si:**
- Jaké projekty už běží a v jakých složkách
- Jakou PHP verzi používají existující projekty
- Jaké databáze už existují
- Kolik volného místa máš k dispozici

### 1.3 Aktualizace systému (OPATRNĚ!)

```bash
# Aktualizuj balíčky, ale NERESTARUJ služby automaticky
apt update

# Zkontroluj, co by se aktualizovalo
apt list --upgradable

# Pokud tam není nic kritického (PHP, MySQL), můžeš upgradovat
apt upgrade -y

# NEBO bezpečnější varianta - aktualizuj jen security updaty
apt upgrade -y --with-new-pkgs
```

### 1.4 Instalace požadovaného software (KONTROLUJ PŘED INSTALACÍ!)

#### PHP 8.2 a rozšíření

**⚠️ POZOR: Pokud ostatní projekty běží na PHP 7.x nebo 8.0/8.1, NESTAHUJ je!**

```bash
# Nejdřív zkontroluj, jaká PHP verze je nainstalovaná
php -v
dpkg -l | grep php

# Pokud je PHP 8.2 už nainstalované, přeskoč instalaci
# Pokud je PHP 8.1, můžeš nainstalovat 8.2 VEDLE (nenahrazuje 8.1)
# Pokud je starší verze (7.x), ZVAŽUJ upgrade nebo použij existující verzi

# Instalace PHP 8.2 VEDLE existující verze (neruší starou)
apt install software-properties-common -y
add-apt-repository ppa:ondrej/php -y
apt update

# Instalace PHP 8.2 - NEOVLIVNÍ existující PHP verze
apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-mysql \
  php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml \
  php8.2-bcmath php8.2-sqlite3 php8.2-redis

# Ověření - měly by běžet OBOJE verze vedle sebe
php -v  # Ukáže výchozí verzi
php8.2 -v  # Ukáže konkrétně 8.2
systemctl status php8.2-fpm  # Mělo by běžet

# Pokud měly běžet i jiné verze, zkontroluj, že stále běží
systemctl status php8.1-fpm  # Pokud máš 8.1
systemctl status php7.4-fpm  # Pokud máš 7.4
```

#### Composer

```bash
# Zkontroluj, jestli už není nainstalovaný
composer --version

# Pokud není, nainstaluj
if ! command -v composer &> /dev/null; then
    cd /tmp
    curl -sS https://getcomposer.org/installer -o composer-setup.php
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    composer --version
else
    echo "✅ Composer je už nainstalovaný"
fi
```

#### Node.js a npm (pro Vite build)

```bash
# Zkontroluj existující instalaci
node -v
npm -v

# Pokud není nainstalovaný nebo je verze < 18, nainstaluj
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt install -y nodejs
    node -v
    npm -v
else
    echo "✅ Node.js je už nainstalovaný: $(node -v)"
fi
```

#### MySQL (pokud ještě není nainstalován)

```bash
# Zkontroluj, jestli už běží
systemctl status mysql || systemctl status mariadb

# Pokud ne, nainstaluj (ALE pravděpodobně už máš, pokud běží jiné projekty!)
if ! systemctl is-active --quiet mysql && ! systemctl is-active --quiet mariadb; then
    apt install mysql-server -y
    mysql_secure_installation
else
    echo "✅ MySQL/MariaDB už běží"
fi
```

#### Vytvoření samostatné databáze pro Kavi

**⚠️ DŮLEŽITÉ: Vytvoř NOVOU databázi a NOVÉHO uživatele!**  
**NIKDY nepoužívej existující databáze jiných projektů!**

```bash
# Připoj se k MySQL
mysql -u root -p
```

V MySQL konzoli:

```sql
-- Zkontroluj existující databáze (abys nepřepsal něco důležitého)
SHOW DATABASES;

-- Zkontroluj existující uživatele
SELECT User, Host FROM mysql.user;

-- Vytvoř NOVOU databázi pro Kavi
CREATE DATABASE kavi_new CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Vytvoř NOVÉHO uživatele s SILNÝM heslem
CREATE USER 'kavi_user'@'localhost' IDENTIFIED BY 'very-Strong-P@ssw0rd-2024!';

-- Dej mu oprávnění JEN k databázi kavi_new (ne k ostatním!)
GRANT ALL PRIVILEGES ON kavi_new.* TO 'kavi_user'@'localhost';

-- Aplikuj změny
FLUSH PRIVILEGES;

-- Ověř, že to funguje
USE kavi_new;
SELECT DATABASE();

EXIT;
```

**Testování připojení:**
```bash
# Otestuj, že nový uživatel se může připojit
mysql -u kavi_user -p kavi_new
# Zadej heslo, mělo by tě to pustit dovnitř
# Pak EXIT;
```

#### Nginx (pokud ještě není nainstalován)

```bash
# Zkontroluj status
systemctl status nginx

# Pokud neběží, nainstaluj
if ! systemctl is-active --quiet nginx; then
    apt install nginx -y
    systemctl start nginx
    systemctl enable nginx
else
    echo "✅ Nginx už běží"
fi
```

---

## 🗂️ Fáze 2: Nahrání projektu

### 2.1 Vytvoření adresářové struktury

```bash
# Vytvoření hlavního adresáře pro nový projekt
mkdir -p /var/www/new.kavi.cz
cd /var/www/new.kavi.cz
```

### 2.2 Nahrání kódu

**Varianta A: Přes Git (doporučeno)**

```bash
# Na serveru
cd /var/www/new.kavi.cz
git clone https://github.com/your-username/kavi.git .
# nebo pokud používáš privátní repo, budeš potřebovat SSH klíč
```

**Varianta B: Přes SCP (z tvého lokálního počítače)**

```bash
# Z lokálního počítače
cd /Users/lukas/Kavi

# Vytvoř tarball (vynechá node_modules, vendor, atd.)
tar --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='.git' \
    --exclude='database/database.sqlite' \
    -czf kavi-new.tar.gz .

# Nahraj na server
scp kavi-new.tar.gz root@your-server-ip:/var/www/new.kavi.cz/

# Na serveru rozbal
ssh root@your-server-ip
cd /var/www/new.kavi.cz
tar -xzf kavi-new.tar.gz
rm kavi-new.tar.gz
```

**Varianta C: Přes SFTP/FileZilla**
- Připoj se k serveru přes SFTP
- Nahraj všechny soubory do `/var/www/new.kavi.cz/`
- **Vynech:** `node_modules/`, `vendor/`, `.git/`, `database/database.sqlite`

---

## ⚙️ Fáze 3: Konfigurace projektu

### 3.1 Instalace závislostí

```bash
cd /var/www/new.kavi.cz

# PHP závislosti
composer install --optimize-autoloader --no-dev

# JavaScript závislosti a build
npm install
npm run build
```

### 3.2 Konfigurace .env

```bash
cd /var/www/new.kavi.cz
cp ENV_EXAMPLE_CONTENT.txt .env
nano .env
```

Upravit tyto klíčové hodnoty:

```env
APP_NAME=Kavi
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://new.kavi.cz

# Databáze
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kavi_new
DB_USERNAME=kavi_user
DB_PASSWORD=tvoje-silne-heslo

# Email (použij své Mailgun údaje)
MAIL_MAILER=smtp
MAIL_HOST=smtp.eu.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@mg.kavi.cz
MAIL_PASSWORD=tvoje-mailgun-heslo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="objednavky@kavi.cz"
MAIL_FROM_NAME="Kavi"

MAILGUN_DOMAIN=mg.kavi.cz
MAILGUN_SECRET=tvuj-mailgun-api-key
MAILGUN_ENDPOINT=api.eu.mailgun.net

# Stripe (použij LIVE klíče pro produkci!)
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Packeta
PACKETA_API_KEY=tvuj-api-key
PACKETA_API_PASSWORD=tvoje-heslo

# Fakturoid
FAKTUROID_CLIENT_ID=tvuj-client-id
FAKTUROID_CLIENT_SECRET=tvuj-secret
FAKTUROID_SLUG=tvuj-slug
FAKTUROID_NUMBER_FORMAT=
FAKTUROID_USER_AGENT="Kavi (info@kavi.cz)"
```

### 3.3 Generování klíče a nastavení

```bash
cd /var/www/new.kavi.cz

# Generování APP_KEY
php artisan key:generate

# Spuštění migrací
php artisan migrate --force

# Cache optimalizace
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Vytvoření symbolic link pro storage
php artisan storage:link
```

### 3.4 Nastavení oprávnění

```bash
cd /var/www/new.kavi.cz

# Nastavení vlastníka
chown -R www-data:www-data /var/www/new.kavi.cz

# Nastavení oprávnění
chmod -R 755 /var/www/new.kavi.cz
chmod -R 775 /var/www/new.kavi.cz/storage
chmod -R 775 /var/www/new.kavi.cz/bootstrap/cache

# Vytvoření adresářů pokud neexistují
mkdir -p storage/app/public/images
mkdir -p storage/app/invoices
chmod -R 775 storage/app
```

---

## 🌐 Fáze 4: Konfigurace Nginx

### 4.1 Vytvoření Nginx konfigurace pro new.kavi.cz

```bash
nano /etc/nginx/sites-available/new.kavi.cz
```

Obsah konfigurace:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name new.kavi.cz;
    
    # Přesměrování na HTTPS (po získání SSL)
    # return 301 https://$server_name$request_uri;

    root /var/www/new.kavi.cz/public;
    index index.php index.html index.htm;

    # Logování
    access_log /var/log/nginx/new.kavi.cz-access.log;
    error_log /var/log/nginx/new.kavi.cz-error.log;

    # Limity
    client_max_body_size 20M;

    # Hlavní location blok
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP handling
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Timeouts pro Stripe webhooks
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
    }

    # Statické soubory
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Bezpečnost - deny přístup k citlivým souborům
    location ~ /\.(?!well-known).* {
        deny all;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # Deny přístup k vendor a dalším adresářům
    location ~ ^/(vendor|storage|database|tests|bootstrap|config|routes|app) {
        deny all;
        return 404;
    }
}
```

### 4.2 Aktivace konfigurace

```bash
# Vytvoření symbolického linku
ln -s /etc/nginx/sites-available/new.kavi.cz /etc/nginx/sites-enabled/

# Testování konfigurace
nginx -t

# Restart Nginx
systemctl restart nginx
```

---

## 🔒 Fáze 5: SSL certifikát (Let's Encrypt)

### 5.1 Instalace Certbot

```bash
apt install certbot python3-certbot-nginx -y
```

### 5.2 DNS konfigurace

**⚠️ DŮLEŽITÉ: Před získáním SSL musíš nastavit DNS!**

V Hostinger DNS panelu (nebo kde máš DNS):
1. Přidej A záznam:
   - **Typ:** A
   - **Host:** new
   - **IP:** IP adresa tvého VPS serveru
   - **TTL:** 14400 (nebo výchozí)

Počkej 5-10 minut na propagaci DNS a ověř:
```bash
ping new.kavi.cz
nslookup new.kavi.cz
```

### 5.3 Získání SSL certifikátu

```bash
# Získání certifikátu
certbot --nginx -d new.kavi.cz

# Automatické obnovení (nastaví se automaticky)
certbot renew --dry-run
```

Certbot automaticky upraví tvou Nginx konfiguraci a přidá HTTPS.

---

## 🔐 Fáze 6: Zabezpečení

### 6.1 Firewall (UFW)

```bash
# Instalace a nastavení
apt install ufw -y
ufw default deny incoming
ufw default allow outgoing

# Povolení služeb
ufw allow ssh
ufw allow 'Nginx Full'
ufw allow 3306  # MySQL (pouze pokud potřebuješ vzdálený přístup)

# Aktivace
ufw enable
ufw status
```

### 6.2 Zabezpečení MySQL

```bash
# Pokud ještě nebylo spuštěno
mysql_secure_installation
```

### 6.3 Fail2Ban (ochrana před brute-force)

```bash
apt install fail2ban -y

# Vytvoření konfigurace pro Nginx
cat > /etc/fail2ban/jail.local << EOF
[nginx-http-auth]
enabled = true
filter = nginx-http-auth
port = http,https
logpath = /var/log/nginx/error.log

[nginx-limit-req]
enabled = true
filter = nginx-limit-req
port = http,https
logpath = /var/log/nginx/error.log
EOF

systemctl restart fail2ban
```

---

## 🎯 Fáze 7: Webhooks a Integrace

### 7.1 Stripe Webhook

V Stripe Dashboard (https://dashboard.stripe.com/webhooks):

1. Přidej nový webhook endpoint:
   ```
   https://new.kavi.cz/stripe/webhook
   ```

2. Vyber tyto události:
   - `checkout.session.completed`
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`

3. Zkopíruj webhook signing secret a přidej do `.env`:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_...
   ```

### 7.2 Mailgun DNS

Ujisti se, že máš v DNS správně nastavené Mailgun záznamy pro `mg.kavi.cz` nebo `kavi.cz`:
- SPF
- DKIM
- CNAME tracking records

### 7.3 Packeta API

Ověř, že API klíče v `.env` jsou správné a testuj na staging prostředí.

---

## ✅ Fáze 8: Testování

### 8.1 Základní kontroly

```bash
# Zkontroluj logování
tail -f /var/log/nginx/new.kavi.cz-error.log
tail -f /var/www/new.kavi.cz/storage/logs/laravel.log

# Zkontroluj PHP-FPM
systemctl status php8.2-fpm

# Zkontroluj Nginx
systemctl status nginx

# Zkontroluj MySQL
systemctl status mysql
```

### 8.2 Checklist testování

- [ ] Otevřít https://new.kavi.cz - zobrazí se homepage
- [ ] Registrace nového uživatele
- [ ] Přihlášení
- [ ] Procházení produktů
- [ ] Přidání produktu do košíku
- [ ] Checkout proces
- [ ] **Testovací platba** přes Stripe (použij test kartu: 4242 4242 4242 4242)
- [ ] Email potvrzení objednávky
- [ ] Admin panel přístup
- [ ] Vytvoření předplatného
- [ ] Newsletter subscription
- [ ] Magic login link

### 8.3 Monitorování výkonu

```bash
# Instalace htop pro monitoring
apt install htop -y

# Kontrola využití zdrojů
htop

# Kontrola disk space
df -h

# Kontrola MySQL
mysql -u kavi_user -p kavi_new -e "SHOW TABLES;"
```

---

## 🔄 Fáze 9: Údržba a aktualizace

### 9.1 Deployment skript

Vytvoř si deployment skript pro snadné aktualizace:

```bash
nano /var/www/new.kavi.cz/deploy.sh
```

```bash
#!/bin/bash

echo "🚀 Starting deployment..."

cd /var/www/new.kavi.cz

# Zapnutí maintenance mode
php artisan down

# Git pull (pokud používáš Git)
git pull origin main

# Update závislostí
composer install --optimize-autoloader --no-dev

# NPM build
npm install
npm run build

# Migrace databáze
php artisan migrate --force

# Clear & cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Vypnutí maintenance mode
php artisan up

echo "✅ Deployment complete!"
```

```bash
chmod +x /var/www/new.kavi.cz/deploy.sh
```

### 9.2 Zálohy databáze

Automatické denní zálohy:

```bash
nano /root/backup-kavi.sh
```

```bash
#!/bin/bash

BACKUP_DIR="/root/backups/kavi"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u kavi_user -p'tvoje-heslo' kavi_new > $BACKUP_DIR/kavi_new_$DATE.sql

# Delete backups older than 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete

echo "Backup completed: kavi_new_$DATE.sql"
```

```bash
chmod +x /root/backup-kavi.sh

# Přidej do crontab (denně ve 2:00)
crontab -e
# Přidej řádek:
0 2 * * * /root/backup-kavi.sh >> /var/log/kavi-backup.log 2>&1
```

### 9.3 Logrotate

```bash
nano /etc/logrotate.d/kavi
```

```
/var/www/new.kavi.cz/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

---

## 🎊 Fáze 10: Přepnutí na produkci (po testování)

Až budeš spokojen s testováním na `new.kavi.cz`, můžeš přepnout na `kavi.cz`:

### 10.1 Záloha WordPressu

```bash
# Zálohuj stávající WordPress
cp -r /var/www/kavi.cz /var/www/kavi.cz.wordpress-backup
```

### 10.2 DNS změna

Možnosti:
- **A) Přejmenování:** Přesuň Laravel na `kavi.cz` a WordPress na `old.kavi.cz`
- **B) Subdoména:** WordPress přesuň na `blog.kavi.cz`

### 10.3 Aktualizace Nginx

Přejmenuj konfigurace:
```bash
mv /etc/nginx/sites-available/kavi.cz /etc/nginx/sites-available/old.kavi.cz
mv /etc/nginx/sites-available/new.kavi.cz /etc/nginx/sites-available/kavi.cz

# Uprav server_name v konfiguracích
nano /etc/nginx/sites-available/kavi.cz
# Změň: server_name new.kavi.cz; → server_name kavi.cz www.kavi.cz;
```

### 10.4 SSL pro novou doménu

```bash
certbot --nginx -d kavi.cz -d www.kavi.cz
```

### 10.5 Aktualizace .env

```bash
nano /var/www/kavi.cz/.env
# Změň APP_URL=https://kavi.cz
php artisan config:clear
php artisan config:cache
```

### 10.6 Aktualizace Stripe webhook

V Stripe Dashboard změň webhook URL z `new.kavi.cz` na `kavi.cz`.

---

## 🆘 Troubleshooting

### 500 Internal Server Error
```bash
# Zkontroluj logy
tail -100 /var/www/new.kavi.cz/storage/logs/laravel.log
tail -100 /var/log/nginx/new.kavi.cz-error.log

# Zkontroluj oprávnění
chown -R www-data:www-data /var/www/new.kavi.cz
chmod -R 775 storage bootstrap/cache
```

### Problémy s databází
```bash
# Otestuj připojení
php artisan tinker
>>> DB::connection()->getPdo();
```

### Assets se nenačítají
```bash
cd /var/www/new.kavi.cz
npm run build
php artisan storage:link
```

### Email nefunguje
```bash
# Testovací email
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('tvuj@email.cz')->subject('Test'); });
```

---

## 📞 Kontakty a zdroje

- **Laravel Docs:** https://laravel.com/docs/10.x
- **Stripe Docs:** https://stripe.com/docs
- **Mailgun Docs:** https://documentation.mailgun.com/
- **Certbot:** https://certbot.eff.org/

---

## 🎉 Hotovo!

Teď máš běžící:
- ✅ `new.kavi.cz` - nová Laravel aplikace (staging/testování)
- ✅ `kavi.cz` - stávající WordPress (nedotčený)

Po důkladném testování můžeš přepnout na produkční doménu podle Fáze 10.

**Poznámky:**
- Použij LIVE API klíče pro produkci (Stripe, Mailgun, Packeta, Fakturoid)
- Zapni backupy
- Monitoruj logy první týden
- Testuj všechny platební scénáře

Hodně štěstí s nasazením! 🚀

