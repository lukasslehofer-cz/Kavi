# Kavi Coffee - Moderní kávový e-shop s předplatným

Moderní webová aplikace pro prodej kávy a kávových předplatných postavená na **Laravel 10**, **PHP 8.2+**, **MySQL 8.0**, **Redis** a **Tailwind CSS**.

![Kavi Coffee](https://via.placeholder.com/1200x400/5d4537/ffffff?text=Kavi+Coffee)

## 🎯 Vlastnosti

### E-shop

- ✅ Moderní responzivní design s Tailwind CSS
- ✅ Správa produktů (káva, příslušenství, merch)
- ✅ Nákupní košík s přehledným souhrnem
- ✅ Platby kartou přes Stripe
- ✅ Doprava zdarma nad 1000 Kč
- ✅ Real-time správa skladu

### Předplatné kávy

- ✅ Flexibilní předplatné (měsíční/čtvrtletní/roční)
- ✅ Různé balíčky (Espresso BOX, Filtr BOX)
- ✅ Automatické měsíční platby přes Stripe
- ✅ Správa předplatného v uživatelském dashboardu

### Admin panel

- ✅ Přehledný dashboard s metrikami
- ✅ Správa produktů (CRUD operace)
- ✅ Správa objednávek
- ✅ Správa předplatných
- ✅ Sledování tržeb a zákazníků

### Technické vlastnosti

- ✅ Moderní Laravel 10 framework
- ✅ Docker kontejnerizace pro snadné nasazení
- ✅ Redis pro cache a queue
- ✅ MySQL 8.0 databáze
- ✅ Stripe platební brána
- ✅ Bezpečnostní best practices

## 📋 Požadavky

- **Docker Desktop** (doporučeno) - [Stáhnout zde](https://www.docker.com/products/docker-desktop/)
  - Zajistěte, že Docker Desktop je **spuštěný** před instalací
- nebo **PHP 8.2+**, **MySQL 8.0**, **Redis**, **Composer**, **Node.js 18+**

## 🚀 Instalace

### Krok 1: Klonování projektu

```bash
cd /Users/lukas/Kavi
```

### Krok 2: Konfigurace prostředí

```bash
# Vytvořte .env soubor
cp .env.example .env

# Upravte následující hodnoty v .env:
# - DB_* (databáze)
# - REDIS_* (redis)
# - STRIPE_KEY a STRIPE_SECRET (Stripe API klíče)
```

### Krok 3: Spuštění Docker Desktop

**DŮLEŽITÉ:** Před spuštěním příkazů se ujistěte, že Docker Desktop běží!

```bash
# Na macOS spusťte Docker Desktop
open -a Docker

# Počkejte cca 30 sekund, až se Docker spustí
```

### Krok 4: Spuštění s Dockerem (doporučeno)

```bash
# Sestavení a spuštění kontejnerů
docker-compose up -d

# Instalace PHP závislostí
docker-compose exec php composer install

# Vygenerování aplikačního klíče
docker-compose exec php php artisan key:generate

# Spuštění migrací
docker-compose exec php php artisan migrate

# Instalace NPM závislostí a build assets
npm install
npm run build
```

### Krok 5: Vytvoření admin uživatele

```bash
docker-compose exec php php artisan tinker
```

Pak v Tinkeru:

```php
$admin = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@kavi.cz',
    'password' => bcrypt('password'),
    'is_admin' => true
]);
```

### Krok 6: (Volitelné) Seed testovacích dat

```bash
docker-compose exec php php artisan db:seed
```

## 🌐 Přístup k aplikaci

- **Web aplikace**: http://localhost
- **Admin panel**: http://localhost/admin
- **MailHog** (testování emailů): http://localhost:8025

### Výchozí přihlašovací údaje:

- Email: `admin@kavi.cz`
- Heslo: `password`

## 📦 Struktura projektu

```
Kavi/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controllers (Home, Product, Cart, Admin)
│   │   └── Middleware/       # Custom middleware (AdminMiddleware)
│   ├── Models/               # Eloquent modely (User, Product, Order, etc.)
│   └── Services/             # Business logika (StripeService)
├── database/
│   └── migrations/           # Databázové migrace
├── resources/
│   ├── css/                  # Tailwind CSS styly
│   ├── js/                   # JavaScript
│   └── views/                # Blade šablony
├── routes/
│   ├── web.php              # Web routes
│   └── auth.php             # Auth routes
├── docker/                   # Docker konfigurace
├── docker-compose.yml        # Docker Compose setup
└── README.md                # Tato dokumentace
```

## 🎨 Design

Aplikace používá **Tailwind CSS** s vlastní kávově-inspirovanou barevnou paletou:

- **Coffee tóny**: `coffee-50` až `coffee-900` (hnědé odstíny)
- **Cream tóny**: `cream-50` až `cream-900` (béžové odstíny)
- **Font**: Inter (sans-serif) + Playfair Display (serif pro titulky)

## 💳 Stripe integrace

### Nastavení Stripe

1. Vytvořte účet na [stripe.com](https://stripe.com)
2. Získejte API klíče (Dashboard → Developers → API keys)
3. Přidejte klíče do `.env`:

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

### Nastavení webhooků

1. V Stripe Dashboard → Developers → Webhooks
2. Přidejte endpoint: `https://your-domain.com/webhook/stripe`
3. Vyberte události:
   - `checkout.session.completed`
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`

## 🔧 Vývoj

### Spuštění development serveru

```bash
# S Dockerem
docker-compose up

# Bez Dockeru
php artisan serve
npm run dev
```

### Queue worker (pro asynchronní úlohy)

```bash
docker-compose exec php php artisan queue:work
```

### Spuštění testů

```bash
docker-compose exec php php artisan test
```

### Code style (Pint)

```bash
docker-compose exec php ./vendor/bin/pint
```

## 📊 Databázový model

### Hlavní tabulky

- `users` - Uživatelé (zákazníci a admin)
- `products` - Produkty (káva, příslušenství)
- `subscription_plans` - Plány předplatného
- `subscriptions` - Aktivní předplatné uživatelů
- `orders` - Objednávky
- `order_items` - Položky objednávek

## 🔐 Bezpečnost

- ✅ CSRF ochrana (Laravel built-in)
- ✅ XSS ochrana (Blade automatic escaping)
- ✅ SQL injection ochrana (Eloquent ORM)
- ✅ Bcrypt hashování hesel
- ✅ Middleware pro admin přístup
- ✅ Rate limiting
- ✅ HTTPS ready

## 📝 Důležité příkazy

```bash
# Vyčištění cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimalizace pro produkci
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Spuštění migrací
php artisan migrate

# Rollback migrací
php artisan migrate:rollback

# Fresh install s seeders
php artisan migrate:fresh --seed
```

## 🚢 Nasazení do produkce

### 1. Příprava

```bash
# Build production assets
npm run build

# Optimalizace
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Nastavení .env

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://kavi.cz

# Produkční databáze
DB_HOST=your-production-host
DB_DATABASE=kavi_production
DB_USERNAME=kavi_user
DB_PASSWORD=strong-password

# Produkční Redis
REDIS_HOST=your-redis-host

# Produkční Stripe
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
```

### 3. Web server konfigurace

**Nginx příklad** (již obsažen v `docker/nginx/default.conf`)

### 4. Supervisor pro queue worker

```ini
[program:kavi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/kavi/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/kavi/worker.log
```

## 🤝 Technologický stack

- **Backend**: PHP 8.2+, Laravel 10
- **Database**: MySQL 8.0
- **Cache/Queue**: Redis (Alpine)
- **Web Server**: Nginx (Alpine)
- **Frontend**: Blade Templates + Tailwind CSS
- **JavaScript**: Vanilla JS + Axios
- **Payment**: Stripe API
- **Containerization**: Docker & Docker Compose

## 📧 Kontakt a podpora

Pro otázky nebo podporu kontaktujte:

- Email: info@kavi.cz
- Tel: +420 123 456 789

## 📄 Licence

MIT License - Volně použitelné pro komerční i nekomerční účely.

---

**Vytvořeno s ❤️ pro milovníky kávy**
