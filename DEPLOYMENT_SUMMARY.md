# 🎯 Kavi Deployment - Stručný přehled

> **TL;DR** - Vše co potřebuješ vědět o nasazení Kavi na `new.kavi.cz`

---

## 🚨 KRITICKÁ UPOZORNĚNÍ

### ⚠️ Multi-project server

Na serveru už běží jiné projekty → **NIKDY:**

- ❌ Neměň PHP verze bez kontroly
- ❌ Nemazej cizí konfigurace
- ❌ Nepoužívej cizí databáze
- ❌ Nerestaruj služby zbytečně

### ✅ Co dělat správně:

- ✓ Vytvoř novou složku `/var/www/new.kavi.cz`
- ✓ Vytvoř novou databázi `kavi_new`
- ✓ Vytvoř nového MySQL uživatele
- ✓ Použij separátní Nginx config
- ✓ Testuj na `new.kavi.cz` před přepnutím

---

## 📦 Deployment balíček (máš připravený)

```
/Users/lukas/Kavi/
├── 📖 DEPLOYMENT_README.md          ← START HERE!
├── ⚡ DEPLOYMENT_QUICK_START.md     ← 30 minut průvodce
├── 📚 DEPLOYMENT_GUIDE.md           ← Detaily (735 řádků)
├── ✅ DEPLOYMENT_CHECKLIST.md       ← Kontrolní seznam
├── 📊 DEPLOYMENT_SUMMARY.md         ← Tento soubor
│
├── 🔍 server-audit.sh               ← Spusť PRVNÍ!
├── 🚀 deploy.sh                     ← Pro aktualizace
├── 💾 backup-script.sh              ← Automatické zálohy
├── 📦 prepare-deployment.sh         ← Příprava balíčku
│
├── 🌐 nginx-new-kavi.conf           ← Nginx konfigurace
└── ⚙️  .env.example                 ← Environment template
```

---

## ⚡ Quick Deploy (8 kroků, 60 minut)

### 1. Příprava lokálně (10 min)

```bash
cd /Users/lukas/Kavi

# Připrav deployment package
./prepare-deployment.sh

# Vytvoří složku deployment-package/ s vším potřebným
```

### 2. Audit serveru (5 min)

```bash
# Nahraj audit script
scp server-audit.sh root@your-server-ip:/tmp/

# Spusť audit
ssh root@your-server-ip "bash /tmp/server-audit.sh > /tmp/audit.txt"
ssh root@your-server-ip "cat /tmp/audit.txt"

# Zkontroluj výstup! Poznamenej si:
# - PHP verzi
# - Volné místo na disku
# - Existující projekty/databáze
```

### 3. DNS konfigurace (5 min + čekání)

V Hostinger DNS:

- **A záznam:** `new` → `IP serveru`
- Počkej 5-10 min
- Ověř: `ping new.kavi.cz`

### 4. Upload kódu (10 min)

```bash
# Varianta A: SCP (z lokálního PC)
cd /Users/lukas/Kavi/deployment-package
scp kavi-application.tar.gz root@your-server-ip:/tmp/

# Na serveru
ssh root@your-server-ip
mkdir -p /var/www/new.kavi.cz
cd /var/www/new.kavi.cz
tar -xzf /tmp/kavi-application.tar.gz
rm /tmp/kavi-application.tar.gz

# Varianta B: Git (pokud používáš)
cd /var/www/new.kavi.cz
git clone https://github.com/your-repo/kavi.git .
```

### 5. Databáze (5 min)

```sql
mysql -u root -p

SHOW DATABASES;  -- Zkontroluj existující
CREATE DATABASE kavi_new CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kavi_user'@'localhost' IDENTIFIED BY 'VeryStr0ng-P@ssw0rd!';
GRANT ALL PRIVILEGES ON kavi_new.* TO 'kavi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 6. Konfigurace aplikace (15 min)

```bash
cd /var/www/new.kavi.cz

# Závislosti
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Environment
cp .env.example .env
nano .env  # Vyplň všechny hodnoty!

# Laravel setup
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Oprávnění
chown -R www-data:www-data /var/www/new.kavi.cz
chmod -R 755 /var/www/new.kavi.cz
chmod -R 775 storage bootstrap/cache
```

### 7. Nginx + SSL (10 min)

```bash
# Nginx konfigurace
nano /etc/nginx/sites-available/new.kavi.cz
# Zkopíruj z nginx-new-kavi.conf nebo použij template z guide

# Aktivuj
ln -s /etc/nginx/sites-available/new.kavi.cz /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx

# SSL certifikát
certbot --nginx -d new.kavi.cz
```

### 8. Testování (30+ min)

```bash
# Zkontroluj logy
tail -f /var/www/new.kavi.cz/storage/logs/laravel.log

# Otevři v browseru
# https://new.kavi.cz
```

**Test checklist:**

- [ ] Web se načítá
- [ ] Registrace funguje
- [ ] Test platba: `4242 4242 4242 4242`
- [ ] Email dorazil
- [ ] Admin panel dostupný

---

## 🔑 Potřebné API klíče

Připrav si PŘED deploymentem:

### Stripe (LIVE pro produkci!)

```env
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...  # Nastavíš po deploymentu
```

### Mailgun

```env
MAILGUN_DOMAIN=mg.kavi.cz
MAILGUN_SECRET=key-...
MAIL_USERNAME=postmaster@mg.kavi.cz
MAIL_PASSWORD=...
```

### Packeta

```env
PACKETA_API_KEY=...
PACKETA_API_PASSWORD=...
```

### Fakturoid

```env
FAKTUROID_CLIENT_ID=...
FAKTUROID_CLIENT_SECRET=...
FAKTUROID_SLUG=...
```

### MySQL

```env
DB_DATABASE=kavi_new
DB_USERNAME=kavi_user
DB_PASSWORD=...  # Vygeneruj silné heslo!
```

---

## 🎯 Po deploymentu

### Okamžitě:

1. Nastav Stripe webhook: `https://new.kavi.cz/stripe/webhook`
2. Zkontroluj logy: `tail -f storage/logs/laravel.log`
3. Testuj všechny funkce (viz checklist)

### První týden:

- Sleduj logy denně
- Monitoruj výkon (`htop`, `df -h`)
- Sbírej feedback
- Dolaď problémy

### Po 1-2 týdnech (když je stabilní):

- Přepni na `kavi.cz` (viz guide, fáze 10)
- Aktualizuj Stripe webhook URL
- Přesuň WordPress na subdoménu

---

## 📊 Maintenance

### Aktualizace aplikace

```bash
cd /var/www/new.kavi.cz
bash deploy.sh  # Nebo manuálně: git pull, composer, npm build, migrate
```

### Automatické zálohy

```bash
# Nahraj backup script
scp backup-script.sh root@your-server:/root/

# Nastav cron
crontab -e
# Přidej: 0 2 * * * /root/backup-script.sh >> /var/log/kavi-backup.log 2>&1
```

### Monitoring

```bash
# Logy
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/new.kavi.cz-error.log

# Služby
systemctl status nginx php8.2-fpm mysql

# Výkon
htop
df -h
```

---

## 🆘 Časté problémy

| Problém             | Řešení                                                       |
| ------------------- | ------------------------------------------------------------ |
| **500 Error**       | Zkontroluj `storage/logs/laravel.log` a oprávnění `storage/` |
| **502/504**         | `systemctl restart php8.2-fpm`                               |
| **DB Error**        | Ověř `.env` hodnoty: `DB_*`                                  |
| **Assets chybí**    | `npm run build && php artisan storage:link`                  |
| **Email nefunguje** | Zkontroluj Mailgun credentials v `.env`                      |
| **SSL nefunguje**   | `certbot --nginx -d new.kavi.cz`                             |

**Další help:**

```bash
# Laravel log
tail -100 storage/logs/laravel.log

# Nginx log
tail -100 /var/log/nginx/new.kavi.cz-error.log

# Test DB připojení
php artisan tinker
>>> DB::connection()->getPdo();

# Test email
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@email.cz'));
```

---

## 📈 Timeline

| Čas           | Co se děje                          |
| ------------- | ----------------------------------- |
| **Den 0**     | Deployment na `new.kavi.cz`         |
| **Den 1-7**   | Intenzivní testování a sledování    |
| **Týden 2-3** | Beta testování s reálnými uživateli |
| **Týden 4**   | Přepnutí na `kavi.cz` (produkce)    |

---

## ✅ Checklist připravenosti

### Před začátkem deploymentu:

- [ ] Mám SSH přístup na server
- [ ] Znám IP adresu serveru
- [ ] Mám všechny API klíče připravené
- [ ] Provedl jsem server audit
- [ ] DNS je nakonfigurované
- [ ] Backup WordPressu je hotový

### Po deploymentu:

- [ ] Web se načítá na `https://new.kavi.cz`
- [ ] Všechny funkce jsou otestované
- [ ] Stripe webhook je nastavený
- [ ] Automatické zálohy běží
- [ ] Monitoring je aktivní

---

## 📞 Zdroje

- **📖 Detailní guides:** `DEPLOYMENT_*.md` soubory
- **🔧 Laravel Docs:** https://laravel.com/docs/10.x
- **💳 Stripe Docs:** https://stripe.com/docs
- **📧 Mailgun Docs:** https://documentation.mailgun.com
- **🔒 Let's Encrypt:** https://letsencrypt.org

---

## 🎉 To je vše!

**3 nejdůležitější věci:**

1. 🔍 **Audit nejdřív** - spusť `server-audit.sh`
2. 🧪 **Testuj na staging** - `new.kavi.cz`
3. 🛡️ **Izoluj od ostatních** - vlastní složka, DB, konfigurace

**Jsi připraven?** → Začni s `DEPLOYMENT_QUICK_START.md`

---

_Poslední aktualizace: 2025-10-29_
_Vytvořeno pro: Kavi - Kávový eshop a předplatné_
