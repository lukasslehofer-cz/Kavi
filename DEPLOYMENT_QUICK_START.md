# 🚀 Quick Start - Deployment na Multi-project Server

**Rychlý průvodce pro nasazení Kavi na server, kde už běží jiné projekty.**

---

## 📌 Před začátkem

### ✅ Checklist připravenosti

- [ ] Mám SSH přístup na server
- [ ] Znám IP adresu serveru
- [ ] Mám připravené všechny API klíče (Stripe, Mailgun, Packeta, Fakturoid)
- [ ] Mám zálohované lokální .env hodnoty
- [ ] Vím, jaké projekty už na serveru běží

---

## 🔍 Krok 1: Audit serveru (5 minut)

**NEJDŘÍV zjisti, co na serveru už běží!**

```bash
# 1. Připoj se na server
ssh root@your-server-ip

# 2. Nahraj a spusť audit script
# (z lokálního počítače)
scp server-audit.sh root@your-server-ip:/tmp/
ssh root@your-server-ip "bash /tmp/server-audit.sh > /tmp/audit-report.txt"
ssh root@your-server-ip "cat /tmp/audit-report.txt"
```

**Nebo manuálně:**

```bash
# Na serveru
ls -la /var/www/                    # Existující projekty
php -v                              # PHP verze
systemctl list-units | grep php    # PHP-FPM služby
ls -la /etc/nginx/sites-enabled/   # Nginx konfigurace
mysql -u root -p -e "SHOW DATABASES;" # Databáze
df -h                               # Volné místo
free -h                             # RAM
```

**🎯 Poznamenej si:**

- PHP verzi ostatních projektů: \***\*\_\_\*\***
- Volné místo na disku: \***\*\_\_\*\***
- Názvy existujících databází: \***\*\_\_\*\***

---

## 📦 Krok 2: Příprava struktury (10 minut)

### A) Vytvoř složku pro projekt

```bash
# Na serveru
mkdir -p /var/www/new.kavi.cz
cd /var/www/new.kavi.cz
```

### B) Nahraj kód (vyber jednu metodu)

**Metoda 1: Git (doporučeno)**

```bash
# Na serveru
cd /var/www/new.kavi.cz
git clone https://github.com/tvuj-repo/kavi.git .
```

**Metoda 2: SCP**

```bash
# Z lokálního počítače
cd /Users/lukas/Kavi
tar --exclude='node_modules' --exclude='vendor' --exclude='.git' \
    --exclude='storage/logs/*' --exclude='database/database.sqlite' \
    -czf kavi-deploy.tar.gz .

scp kavi-deploy.tar.gz root@your-server-ip:/var/www/new.kavi.cz/

# Na serveru
cd /var/www/new.kavi.cz
tar -xzf kavi-deploy.tar.gz
rm kavi-deploy.tar.gz
```

**Metoda 3: SFTP/FileZilla**

- Připoj se přes SFTP na server
- Nahraj do `/var/www/new.kavi.cz/`
- Vynech: `node_modules/`, `vendor/`, `.git/`, `database/database.sqlite`

---

## 🗄️ Krok 3: Databáze (5 minut)

```bash
# Na serveru - připoj se k MySQL
mysql -u root -p
```

```sql
-- Zkontroluj existující databáze
SHOW DATABASES;

-- Vytvoř novou databázi
CREATE DATABASE kavi_new CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Vytvoř nového uživatele (POUŽIJ SILNÉ HESLO!)
CREATE USER 'kavi_user'@'localhost' IDENTIFIED BY 'TvojeS1lneHesl0!2024';

-- Dej oprávnění JEN k této databázi
GRANT ALL PRIVILEGES ON kavi_new.* TO 'kavi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Testuj připojení:**

```bash
mysql -u kavi_user -p kavi_new
# Zadej heslo, mělo by fungovat
# EXIT;
```

---

## ⚙️ Krok 4: Instalace závislostí (10 minut)

```bash
cd /var/www/new.kavi.cz

# PHP závislosti
composer install --optimize-autoloader --no-dev

# JavaScript závislosti a build
npm install
npm run build
```

**Pokud chybí Composer nebo Node.js, viz DEPLOYMENT_GUIDE.md, sekce 1.4**

---

## 🔧 Krok 5: Konfigurace .env (10 minut)

```bash
cd /var/www/new.kavi.cz
cp .env.example .env
nano .env
```

**Minimální konfigurace:**

```env
APP_NAME=Kavi
APP_ENV=production
APP_KEY=                        # ← VYGENERUJE SE v dalším kroku!
APP_DEBUG=false
APP_URL=https://new.kavi.cz

# Databáze
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kavi_new
DB_USERNAME=kavi_user
DB_PASSWORD=TvojeS1lneHesl0!2024

# Email - Mailgun
MAIL_MAILER=smtp
MAIL_HOST=smtp.eu.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@mg.kavi.cz
MAIL_PASSWORD=tvoje-mailgun-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="objednavky@kavi.cz"
MAIL_FROM_NAME="Kavi"

MAILGUN_DOMAIN=mg.kavi.cz
MAILGUN_SECRET=tvuj-mailgun-api-key
MAILGUN_ENDPOINT=api.eu.mailgun.net

# Stripe (POUŽIJ LIVE klíče pro produkci!)
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
FAKTUROID_USER_AGENT="Kavi (info@kavi.cz)"
```

**Laravel setup:**

```bash
cd /var/www/new.kavi.cz

# Generuj APP_KEY
php artisan key:generate

# Spusť migrace
php artisan migrate --force

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link

# Oprávnění
chown -R www-data:www-data /var/www/new.kavi.cz
chmod -R 755 /var/www/new.kavi.cz
chmod -R 775 /var/www/new.kavi.cz/storage
chmod -R 775 /var/www/new.kavi.cz/bootstrap/cache
```

---

## 🌐 Krok 6: Nginx konfigurace (10 minut)

### A) DNS setup

**V Hostinger (nebo kde máš DNS):**

1. Přidej A záznam:

   - **Host:** `new`
   - **Ukazuje na:** IP tvého VPS serveru
   - **TTL:** 14400

2. Počkej 5-10 minut a testuj:

```bash
ping new.kavi.cz
nslookup new.kavi.cz
```

### B) Nginx config

```bash
# Na serveru
nano /etc/nginx/sites-available/new.kavi.cz
```

**Zkopíruj obsah z `nginx-new-kavi.conf` (v projektu) nebo použij tento základní:**

```nginx
server {
    listen 80;
    server_name new.kavi.cz;
    root /var/www/new.kavi.cz/public;
    index index.php;

    access_log /var/log/nginx/new.kavi.cz-access.log;
    error_log /var/log/nginx/new.kavi.cz-error.log;

    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;  # ← Zkontroluj verzi PHP!
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Aktivace:**

```bash
# Vytvoř symlink
ln -s /etc/nginx/sites-available/new.kavi.cz /etc/nginx/sites-enabled/

# Testuj konfiguraci
nginx -t

# Pokud je OK, restartuj Nginx
systemctl reload nginx
```

---

## 🔒 Krok 7: SSL certifikát (5 minut)

```bash
# Nainstaluj Certbot (pokud není)
apt install certbot python3-certbot-nginx -y

# Získej SSL certifikát
certbot --nginx -d new.kavi.cz

# Certbot automaticky upraví Nginx config a restartuje službu
```

**Testuj:**

- Otevři https://new.kavi.cz
- Měl by se zobrazit zelený zámek (SSL funguje)

---

## ✅ Krok 8: Testování (30+ minut)

### Základní funkce

```bash
# Zkontroluj logy
tail -f /var/www/new.kavi.cz/storage/logs/laravel.log
tail -f /var/log/nginx/new.kavi.cz-error.log
```

### Web checklist

- [ ] https://new.kavi.cz se načítá
- [ ] CSS a JavaScript funguje
- [ ] Registrace nového uživatele
- [ ] Přihlášení
- [ ] Produkty se zobrazují
- [ ] Košík funguje
- [ ] **TEST platba** kartou 4242 4242 4242 4242 (Stripe test mode)
- [ ] Email potvrzení dorazí
- [ ] Admin panel je přístupný

### Webhook setup

**Stripe webhook:**

1. Jdi na https://dashboard.stripe.com/webhooks
2. Přidej endpoint: `https://new.kavi.cz/stripe/webhook`
3. Vyber události: `checkout.session.completed`, `invoice.*`, `customer.subscription.*`
4. Zkopíruj webhook secret do `.env` jako `STRIPE_WEBHOOK_SECRET`
5. Restartuj cache: `php artisan config:clear && php artisan config:cache`

---

## 🎯 Hotovo!

Teď máš běžící:

- ✅ **new.kavi.cz** - nová Laravel aplikace (staging)
- ✅ **kavi.cz** - stávající WordPress (nedotčený)
- ✅ **Ostatní projekty** - stále fungují

---

## 📊 Monitoring

```bash
# Sleduj logy
tail -f /var/www/new.kavi.cz/storage/logs/laravel.log

# Zkontroluj služby
systemctl status nginx
systemctl status php8.2-fpm
systemctl status mysql

# Zkontroluj disk space
df -h

# Zkontroluj výkon
htop
```

---

## 🔄 Aktualizace v budoucnu

Použij deployment script:

```bash
cd /var/www/new.kavi.cz
bash deploy.sh
```

Nebo manuálně:

```bash
cd /var/www/new.kavi.cz
php artisan down
git pull origin main
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

---

## 🆘 Problémy?

### 500 Error

```bash
tail -100 /var/www/new.kavi.cz/storage/logs/laravel.log
# Často: špatná oprávnění nebo chybná .env konfigurace
```

### Stránka se nenačítá

```bash
tail -100 /var/log/nginx/new.kavi.cz-error.log
nginx -t  # Zkontroluj syntax
systemctl status php8.2-fpm  # Běží PHP-FPM?
```

### Databázové chyby

```bash
php artisan tinker
>>> DB::connection()->getPdo();  # Testuj připojení
```

### Assets se nenačítají

```bash
npm run build
php artisan storage:link
ls -la /var/www/new.kavi.cz/public/storage  # Měl by být symlink
```

---

## 📚 Další zdroje

- **Kompletní guide:** `DEPLOYMENT_GUIDE.md`
- **Checklist:** `DEPLOYMENT_CHECKLIST.md`
- **Nginx config:** `nginx-new-kavi.conf`
- **Backup script:** `backup-script.sh`
- **Deployment script:** `deploy.sh`

---

**🎉 Úspěšný deployment! Nezapomeň důkladně otestovat před přepnutím na produkci.**
