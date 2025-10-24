# 🚀 Rychlý Start Guide - Kavi Coffee

Tento dokument vás provede prvotním nastavením aplikace Kavi Coffee.

## ⚡ Rychlá instalace (5 minut)

### 0. Spuštění Docker Desktop

**DŮLEŽITÉ: Před pokračováním spusťte Docker Desktop!**

```bash
# Na macOS
open -a Docker

# Nebo spusťte Docker Desktop manuálně z Applications
```

Počkejte cca 30 sekund, až se Docker spustí (ikona v menu baru bude zelená).

### 1. Spuštění Docker kontejnerů

```bash
cd /Users/lukas/Kavi
docker-compose up -d
```

Počkejte, než se všechny kontejnery spustí (cca 1-2 minuty).

### 2. Instalace závislostí

```bash
# PHP závislosti
docker-compose exec php composer install

# Node.js závislosti
npm install
```

### 3. Konfigurace aplikace

```bash
# Vygenerování APP_KEY
docker-compose exec php php artisan key:generate

# Vytvoření symbolického odkazu pro storage
docker-compose exec php php artisan storage:link
```

### 4. Databáze

```bash
# Spuštění migrací
docker-compose exec php php artisan migrate

# (Volitelné) Naplnění testovacími daty
docker-compose exec php php artisan db:seed
```

### 5. Build frontend assets

```bash
npm run build
```

### 6. Otevřete aplikaci

Otevřete v prohlížeči: **http://localhost**

## 👤 Přihlašovací údaje

Po seedování jsou vytvořeny následující účty:

**Admin:**

- Email: `admin@kavi.cz`
- Heslo: `password`

**Test zákazník:**

- Email: `zakaznik@example.com`
- Heslo: `password`

## 📋 Checklist po instalaci

- [ ] Web je dostupný na http://localhost
- [ ] Můžete se přihlásit jako admin
- [ ] Admin panel funguje (http://localhost/admin)
- [ ] Produkty jsou viditelné
- [ ] Košík funguje

## 🔧 Časté problémy

### Docker daemon neběží

**Chyba:** `Cannot connect to the Docker daemon`

**Řešení:**

```bash
# Spusťte Docker Desktop
open -a Docker

# Počkejte, až se spustí (ikona v menu baru)
# Pak zkuste příkaz znovu
```

### Port 80 je obsazený

Změňte v `docker-compose.yml`:

```yaml
ports:
  - "8080:80" # Změňte z "80:80"
```

### Chyba oprávnění

```bash
sudo chmod -R 777 storage bootstrap/cache
```

### Composer install selhává

```bash
docker-compose exec php composer install --no-scripts
docker-compose exec php composer dump-autoload
```

### NPM install selhává

Zkuste použít:

```bash
npm install --legacy-peer-deps
```

## 📧 Testování emailů

MailHog je dostupný na: **http://localhost:8025**

Všechny emaily odeslané z aplikace se objeví v MailHog rozhraní.

## 🎨 Development

### Spuštění Vite dev serveru (pro hot reload)

```bash
npm run dev
```

### Sledování queue jobů

```bash
docker-compose exec php php artisan queue:work
```

### Vyčištění cache

```bash
docker-compose exec php php artisan cache:clear
docker-compose exec php php artisan config:clear
docker-compose exec php php artisan view:clear
```

## 🛑 Zastavení aplikace

```bash
docker-compose down
```

Pro smazání i dat:

```bash
docker-compose down -v
```

## 📞 Potřebujete pomoc?

Zkontrolujte hlavní README.md nebo logy:

```bash
# Aplikační logy
tail -f storage/logs/laravel.log

# Docker logy
docker-compose logs -f php
docker-compose logs -f nginx
```

---

**Gratulujeme! Vaše Kavi Coffee aplikace je připravena k použití! ☕**
