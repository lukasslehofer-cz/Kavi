# 🚀 Deployment Checklist - new.kavi.cz

Použij tento checklist pro systematický deployment a testování.

---

## 📋 Před deploymentem

### Lokální příprava
- [ ] Všechny změny jsou committnuté
- [ ] Kód je otestovaný lokálně
- [ ] Build assets funguje (`npm run build`)
- [ ] `.env.example` je aktuální
- [ ] Dokumentace je aktuální

### API klíče a přístupy
- [ ] Stripe LIVE API klíče (pk_live_, sk_live_)
- [ ] Stripe webhook secret pro produkci
- [ ] Mailgun SMTP credentials
- [ ] Mailgun API key
- [ ] Packeta API key a password
- [ ] Fakturoid Client ID a Secret
- [ ] MySQL heslo pro produkční DB

---

## 🖥️ Server setup

### Systém
- [ ] Server je aktualizovaný (`apt update && apt upgrade`)
- [ ] PHP 8.2+ je nainstalováno
- [ ] Composer je nainstalovaný
- [ ] Node.js 18+ je nainstalovaný
- [ ] MySQL/MariaDB je nainstalovaný a zabezpečený
- [ ] Nginx je nainstalovaný a běží

### Databáze
- [ ] Databáze `kavi_new` je vytvořená
- [ ] Uživatel `kavi_user` má správná oprávnění
- [ ] Připojení k DB funguje

### Adresáře
- [ ] `/var/www/new.kavi.cz` existuje
- [ ] Všechny soubory jsou nahrané
- [ ] `node_modules/` a `vendor/` jsou vynechané (instalují se na serveru)

---

## ⚙️ Konfigurace aplikace

### .env soubor
- [ ] `.env` soubor je vytvořený z `.env.example`
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://new.kavi.cz`
- [ ] `DB_*` údaje jsou správně
- [ ] Všechny API klíče jsou vyplněné
- [ ] MAIL_* konfigurace je správná

### Laravel setup
- [ ] `composer install --optimize-autoloader --no-dev` proběhl úspěšně
- [ ] `npm install && npm run build` proběhl úspěšně
- [ ] `php artisan key:generate` vygeneroval APP_KEY
- [ ] `php artisan migrate --force` proběhl bez chyb
- [ ] `php artisan storage:link` vytvořil symbolic link
- [ ] Cache je vygenerovaná (`config:cache`, `route:cache`, `view:cache`)

### Oprávnění
- [ ] Vlastník je `www-data:www-data`
- [ ] `storage/` má oprávnění 775
- [ ] `bootstrap/cache/` má oprávnění 775
- [ ] Ostatní soubory mají 755

---

## 🌐 Nginx a DNS

### DNS konfigurace
- [ ] A záznam `new` ukazuje na IP serveru
- [ ] DNS se propagoval (`ping new.kavi.cz` funguje)
- [ ] `nslookup new.kavi.cz` vrací správnou IP

### Nginx konfigurace
- [ ] `/etc/nginx/sites-available/new.kavi.cz` je vytvořený
- [ ] Symbolic link v `sites-enabled/` existuje
- [ ] `nginx -t` prošel bez chyb
- [ ] Nginx byl restartovaný (`systemctl restart nginx`)
- [ ] Port 80 je otevřený a web se načítá (i bez HTTPS zatím)

### SSL certifikát
- [ ] Certbot je nainstalovaný
- [ ] `certbot --nginx -d new.kavi.cz` úspěšně získal certifikát
- [ ] HTTPS funguje (https://new.kavi.cz)
- [ ] Automatické obnovení je nastavené (`certbot renew --dry-run`)

---

## 🔒 Zabezpečení

### Firewall
- [ ] UFW je nainstalovaný a aktivní
- [ ] SSH port je povolen
- [ ] HTTP/HTTPS porty jsou povolené (80, 443)
- [ ] Ostatní nepotřebné porty jsou zavřené

### Další zabezpečení
- [ ] `mysql_secure_installation` byl spuštěn
- [ ] Fail2Ban je nainstalovaný a nakonfigurovaný
- [ ] Root login přes SSH je zakázaný (nebo používáš klíče)

---

## 🔗 Integrace a webhooks

### Stripe
- [ ] Webhook endpoint je vytvořený: `https://new.kavi.cz/stripe/webhook`
- [ ] Všechny potřebné události jsou vybrané:
  - [ ] `checkout.session.completed`
  - [ ] `invoice.payment_succeeded`
  - [ ] `invoice.payment_failed`
  - [ ] `customer.subscription.created`
  - [ ] `customer.subscription.updated`
  - [ ] `customer.subscription.deleted`
- [ ] Webhook signing secret je v `.env`
- [ ] Webhook endpoint odpovídá (vrací 200)

### Mailgun
- [ ] DNS záznamy jsou nastavené (SPF, DKIM, CNAME)
- [ ] Domain je ověřená v Mailgun
- [ ] SMTP credentials jsou správné v `.env`
- [ ] Testovací email byl odeslán úspěšně

### Packeta
- [ ] API credentials jsou v `.env`
- [ ] Test API volání funguje

### Fakturoid
- [ ] OAuth credentials jsou v `.env`
- [ ] Test vytvoření faktury funguje (na testovacím účtu)

---

## ✅ Funkční testování

### Základní funkce
- [ ] Homepage se načítá (https://new.kavi.cz)
- [ ] Stylování je správné (CSS se načítá)
- [ ] JavaScript funguje
- [ ] Obrázky se načítají

### Autentizace
- [ ] Registrace nového uživatele funguje
- [ ] Email potvrzení dorazí
- [ ] Přihlášení funguje
- [ ] Odhlášení funguje
- [ ] Reset hesla funguje
- [ ] Magic login link funguje

### E-shop
- [ ] Seznam produktů se zobrazuje
- [ ] Detail produktu funguje
- [ ] Přidání do košíku funguje
- [ ] Košík se zobrazuje správně
- [ ] Checkout formulář funguje

### Platby (TESTOVACÍ REŽIM NEJDŘÍV!)
- [ ] Test platba kartou 4242 4242 4242 4242 funguje
- [ ] Potvrzovací email po objednávce dorazí
- [ ] Objednávka se zobrazí v admin panelu
- [ ] Objednávka se zobrazí v zákaznickém účtu
- [ ] Faktura se vytvoří v Fakturoid

### Předplatné
- [ ] Vytvoření nového předplatného funguje
- [ ] Platba předplatného funguje
- [ ] Email potvrzení předplatného dorazí
- [ ] Předplatné se zobrazí v zákaznickém účtu
- [ ] Úprava předplatného funguje
- [ ] Zrušení předplatného funguje

### Admin panel
- [ ] Admin přihlášení funguje
- [ ] Dashboard se načítá
- [ ] Seznam objednávek funguje
- [ ] Seznam předplatných funguje
- [ ] Seznam uživatelů funguje
- [ ] Newsletter management funguje

### Newsletter
- [ ] Subscribe formulář funguje
- [ ] Potvrzovací email dorazí
- [ ] Unsubscribe funguje
- [ ] Admin může posílat newsletter

---

## 📊 Monitoring a logy

### Logování
- [ ] Laravel logy se zapisují (`storage/logs/laravel.log`)
- [ ] Nginx access log funguje (`/var/log/nginx/new.kavi.cz-access.log`)
- [ ] Nginx error log funguje (`/var/log/nginx/new.kavi.cz-error.log`)
- [ ] Logrotate je nakonfigurovaný

### Výkon
- [ ] Stránky se načítají rychle (< 2s)
- [ ] Server využívá přiměřené zdroje (RAM, CPU)
- [ ] Databázové queries jsou optimalizované

---

## 🔄 Údržba

### Automatizace
- [ ] Deployment script (`deploy.sh`) je vytvořený a funkční
- [ ] Cron job pro backupy je nastavený (denně ve 2:00)
- [ ] Cron job pro Laravel scheduler je nastavený (pokud používáš)

### Zálohy
- [ ] Databázové zálohy fungují automaticky
- [ ] Zálohy se ukládají mimo web root
- [ ] Staré zálohy se automaticky mažou (> 7 dní)
- [ ] Zálohy jsou testované (obnovení funguje)

---

## 🎯 Před přepnutím na produkci (kavi.cz)

### Finální kontrola
- [ ] Všechny funkce jsou důkladně otestované na new.kavi.cz
- [ ] Skutečné platby byly testované (malé částky)
- [ ] Všechny emaily chodí správně
- [ ] Performance je dobrá
- [ ] Žádné chyby v logách
- [ ] Zákazníci/testeři poskytli pozitivní feedback

### LIVE API klíče
- [ ] Stripe webhook je aktualizovaný na novou URL
- [ ] Všechny integrace byly překonfigurované na produkční doménu

### WordPress backup
- [ ] Kompletní backup stávajícího kavi.cz WordPressu
- [ ] Backup databáze WordPressu
- [ ] Plán B připravený (jak se vrátit k WP, pokud by bylo třeba)

---

## 📝 Poznámky

**Datum deploymentu:** ______________

**Deployed by:** ______________

**Verze aplikace:** ______________

**Speciální poznámky:**
```
___________________________________________________________
___________________________________________________________
___________________________________________________________
```

---

## 🆘 V případě problémů

### Rychlá diagnostika
```bash
# Zkontroluj logy
tail -100 /var/www/new.kavi.cz/storage/logs/laravel.log
tail -100 /var/log/nginx/new.kavi.cz-error.log

# Zkontroluj služby
systemctl status nginx
systemctl status php8.2-fpm
systemctl status mysql

# Zkontroluj disk space
df -h

# Zkontroluj oprávnění
ls -la /var/www/new.kavi.cz/storage
```

### Rollback plán
1. Zapnout maintenance mode: `php artisan down`
2. Obnovit předchozí verzi kódu
3. Obnovit databázi ze zálohy (pokud migrace selhala)
4. Clear cache: `php artisan config:clear`
5. Vypnout maintenance mode: `php artisan up`

---

**✅ Deployment dokončen: ______ / ______ / ________**

