# 🚀 Kavi - Deployment na Hostinger VPS

> Nasazení Laravel aplikace na server s již běžícími projekty

---

## 📁 Deployment soubory

Tento repozitář obsahuje kompletní deployment package:

| Soubor                        | Popis                      | Kdy použít                                 |
| ----------------------------- | -------------------------- | ------------------------------------------ |
| **DEPLOYMENT_QUICK_START.md** | ⚡ Rychlý start guide      | První nasazení - začni tady!               |
| **DEPLOYMENT_GUIDE.md**       | 📖 Detailní průvodce       | Potřebuješ podrobnosti nebo řešíš problémy |
| **DEPLOYMENT_CHECKLIST.md**   | ✅ Kontrolní seznam        | Průběžně během deploymentu                 |
| **server-audit.sh**           | 🔍 Audit script            | PŘED začátkem instalace                    |
| **deploy.sh**                 | 🔄 Deployment automatizace | Při aktualizacích projektu                 |
| **backup-script.sh**          | 💾 Zálohovací script       | Nastavit jako cron job                     |
| **nginx-new-kavi.conf**       | 🌐 Nginx konfigurace       | Při konfiguraci web serveru                |
| **.env.example**              | ⚙️ Environment šablona     | Při vytváření .env na serveru              |

---

## 🎯 Quick Start (30 minut)

### 1️⃣ Audit serveru (5 min)

```bash
# Z lokálního počítače
scp server-audit.sh root@your-server-ip:/tmp/
ssh root@your-server-ip "bash /tmp/server-audit.sh"
```

📝 **Poznamenej si:**

- PHP verzi ostatních projektů
- Volné místo na disku
- Existující databáze

### 2️⃣ Příprava API klíčů (10 min)

Připrav si tyto údaje (budeš je potřebovat):

**Stripe:**

- [ ] STRIPE*KEY (pk_live*...)
- [ ] STRIPE*SECRET (sk_live*...)
- [ ] STRIPE_WEBHOOK_SECRET (nastavíš po deploymentu)

**Mailgun:**

- [ ] MAILGUN_DOMAIN (mg.kavi.cz)
- [ ] MAILGUN_SECRET (key-...)
- [ ] MAIL_USERNAME (postmaster@mg.kavi.cz)
- [ ] MAIL_PASSWORD

**Packeta:**

- [ ] PACKETA_API_KEY
- [ ] PACKETA_API_PASSWORD

**Fakturoid:**

- [ ] FAKTUROID_CLIENT_ID
- [ ] FAKTUROID_CLIENT_SECRET
- [ ] FAKTUROID_SLUG

**MySQL:**

- [ ] Silné heslo pro databázového uživatele (vygeneruj)

### 3️⃣ DNS konfigurace (5 min + čekání)

V Hostinger DNS managementu:

1. Přidej **A záznam**:

   - Host: `new`
   - Ukazuje na: `IP tvého VPS`
   - TTL: 14400

2. Počkaj 5-10 minut na propagaci

3. Ověř:

```bash
ping new.kavi.cz
nslookup new.kavi.cz
```

### 4️⃣ Deployment (30 min)

Následuj: **DEPLOYMENT_QUICK_START.md**

Rychlý přehled:

```bash
# 1. Vytvoř složku
mkdir -p /var/www/new.kavi.cz

# 2. Nahraj kód (git / scp / sftp)
cd /var/www/new.kavi.cz
git clone https://github.com/your-repo/kavi.git .

# 3. Databáze
mysql -u root -p
CREATE DATABASE kavi_new...

# 4. Závislosti
composer install --no-dev
npm install && npm run build

# 5. Konfigurace
cp .env.example .env
nano .env
php artisan key:generate
php artisan migrate --force
php artisan config:cache

# 6. Oprávnění
chown -R www-data:www-data /var/www/new.kavi.cz
chmod -R 775 storage bootstrap/cache

# 7. Nginx
nano /etc/nginx/sites-available/new.kavi.cz
ln -s /etc/nginx/sites-available/new.kavi.cz /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx

# 8. SSL
certbot --nginx -d new.kavi.cz
```

### 5️⃣ Testování (30+ min)

- [ ] https://new.kavi.cz se načítá ✅
- [ ] Registrace funguje
- [ ] Přihlášení funguje
- [ ] Produkty se zobrazují
- [ ] Test platba (karta: 4242 4242 4242 4242)
- [ ] Email potvrzení dorazí
- [ ] Admin panel je přístupný

---

## ⚠️ DŮLEŽITÉ - Multi-project server

**Pokud na serveru už běží jiné projekty:**

### ✅ VŽDY:

- Zkontroluj existující prostředí před instalací
- Používej izolované adresáře (`/var/www/new.kavi.cz`)
- Vytvoř samostatnou databázi (`kavi_new`)
- Používej separátní Nginx konfiguraci
- Testuj na `new.kavi.cz` před přepnutím na produkci

### ❌ NIKDY:

- Neměň globální PHP konfiguraci bez analýzy
- Neupgraduj PHP bez kontroly kompatibility ostatních projektů
- Nemazej existující konfigurace
- Nepoužívej cizí databáze
- Nerestaruj služby bez nutnosti

---

## 🏗️ Architektura po deploymentu

```
Server (Hostinger VPS)
├── /var/www/
│   ├── kavi.cz/              ← WordPress (nedotčený)
│   ├── new.kavi.cz/          ← NOVÁ Laravel aplikace
│   └── other-project/        ← Ostatní projekty (nedotčené)
│
├── PHP-FPM
│   ├── php7.4-fpm (pokud používají staré projekty)
│   ├── php8.1-fpm (pokud používají jiné projekty)
│   └── php8.2-fpm ← PRO KAVI (nový)
│
├── Nginx
│   ├── kavi.cz.conf          ← WordPress
│   ├── new.kavi.cz.conf      ← NOVÁ konfigurace
│   └── other-project.conf    ← Ostatní
│
└── MySQL
    ├── wordpress_db          ← WordPress databáze
    ├── kavi_new              ← NOVÁ databáze
    └── other_db              ← Ostatní databáze
```

---

## 🔄 Workflow

### První nasazení (new.kavi.cz)

1. Sleduj **DEPLOYMENT_QUICK_START.md**
2. Nasaď na `new.kavi.cz`
3. Testuj vše důkladně
4. Nech běžet minimálně 1 týden

### Testovací fáze (1-2 týdny)

1. Testuj všechny funkce
2. Sleduj logy: `tail -f storage/logs/laravel.log`
3. Monitoruj výkon
4. Sbírej feedback od uživatelů/testerů
5. Dolaď problémy

### Přepnutí na produkci (kavi.cz)

1. Zálohuj WordPress: `cp -r /var/www/kavi.cz /var/www/kavi.cz.backup`
2. Přesuň WordPress na subdoménu (např. `blog.kavi.cz`)
3. Přejmenuj `new.kavi.cz` → `kavi.cz` v Nginx konfiguraci
4. Aktualizuj DNS
5. Získej nový SSL certifikát pro `kavi.cz`
6. Aktualizuj `.env`: `APP_URL=https://kavi.cz`
7. Aktualizuj Stripe webhook URL

---

## 📊 Monitoring a údržba

### Denní monitoring

```bash
# Zkontroluj logy
tail -100 /var/www/new.kavi.cz/storage/logs/laravel.log

# Zkontroluj služby
systemctl status nginx php8.2-fpm mysql

# Zkontroluj disk space
df -h
```

### Týdenní údržba

- Zkontroluj zálohy v `/root/backups/kavi/`
- Projdi error logy
- Aktualizuj balíčky: `apt update && apt upgrade`

### Aktualizace aplikace

```bash
cd /var/www/new.kavi.cz
bash deploy.sh
```

### Automatické zálohy

```bash
# Nastav cron job
crontab -e

# Přidej řádek (backup každý den ve 2:00)
0 2 * * * /root/backup-kavi.sh >> /var/log/kavi-backup.log 2>&1
```

---

## 🆘 Troubleshooting

### 500 Internal Server Error

```bash
# Zkontroluj Laravel logy
tail -100 /var/www/new.kavi.cz/storage/logs/laravel.log

# Zkontroluj Nginx logy
tail -100 /var/log/nginx/new.kavi.cz-error.log

# Zkontroluj oprávnění
ls -la /var/www/new.kavi.cz/storage
chown -R www-data:www-data /var/www/new.kavi.cz
```

### Stránka se nenačítá (502/504)

```bash
# PHP-FPM běží?
systemctl status php8.2-fpm
systemctl restart php8.2-fpm

# Nginx syntax OK?
nginx -t
systemctl reload nginx
```

### Databázové chyby

```bash
# Testuj připojení
php artisan tinker
>>> DB::connection()->getPdo();

# Zkontroluj .env hodnoty
cat .env | grep DB_
```

### Assets se nenačítají

```bash
npm run build
php artisan storage:link
ls -la public/storage  # Měl by být symlink
```

### Email nefunguje

```bash
# Testuj email
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));

# Zkontroluj Mailgun nastavení
cat .env | grep MAIL
```

---

## 📚 Další zdroje

- **Laravel Docs:** https://laravel.com/docs/10.x/deployment
- **Nginx Docs:** https://nginx.org/en/docs/
- **Stripe Webhooks:** https://stripe.com/docs/webhooks
- **Mailgun Docs:** https://documentation.mailgun.com/
- **Let's Encrypt:** https://letsencrypt.org/getting-started/

---

## ✅ Checklist úspěšného deploymentu

- [ ] Server audit dokončen (`server-audit.sh`)
- [ ] DNS je nakonfigurované a propagované
- [ ] Kód je nahrán na server
- [ ] Databáze vytvořena a migrace proběhly
- [ ] `.env` je správně nakonfigurovaný
- [ ] Composer a npm závislosti nainstalovány
- [ ] Oprávnění jsou správně nastavená
- [ ] Nginx konfigurace funguje
- [ ] SSL certifikát je aktivní
- [ ] Všechny API integrace jsou nastavené
- [ ] Stripe webhook je nakonfigurovaný
- [ ] Automatické zálohy jsou nastavené
- [ ] Monitoring a logy fungují
- [ ] Základní funkce otestovány
- [ ] Platby otestovány (test mode)
- [ ] Email notifikace fungují
- [ ] Admin panel je přístupný

---

## 🎉 Po úspěšném nasazení

**Gratuluji! Tvoje aplikace běží na `new.kavi.cz`**

### Co dál?

1. 📊 **Sleduj výkon** první týden
2. 🐛 **Dolaď problémy** které objevíš
3. 👥 **Sbírej feedback** od prvních uživatelů
4. 🔒 **Zapni LIVE API klíče** když jsi připraven
5. 🚀 **Přepni na kavi.cz** když je vše stabilní

### Kontakty v případě problémů

- Laravel komunita: https://laracasts.com/discuss
- Hostinger support: https://www.hostinger.com/contact
- Stack Overflow: `[laravel] [deployment]`

---

**Hodně štěstí s nasazením! 🚀**

_Vytvořeno: 2025-10-29_
