# Guest Checkout & Magic Link Přihlášení

Tento dokument popisuje nové funkce implementované v Kavi eshopu pro snížení bariér při nákupu.

## 🎯 Co bylo implementováno

### 1. **Guest Checkout pro běžné objednávky**

Zákazníci nyní mohou dokončit objednávku **bez registrace** - stejně jako u předplatného.

**Změny:**

- ✅ Odstraněn `auth` middleware z checkout rout
- ✅ CheckoutController nyní podporuje guest checkout
- ✅ Kontrola existence emailu při guest checkoutu
- ✅ Objednávky s `user_id = null` pro hosty
- ✅ Upravená checkout stránka s možností přihlášení pro hosty
- ✅ Telefon je volitelný pro hosty, povinný pro přihlášené

### 2. **Magic Link Přihlášení**

Moderní způsob přihlášení **bez hesla** - stačí email a link.

**Jak to funguje:**

1. Uživatel zadá email
2. Systém pošle email s jedinečným přihlašovacím odkazem
3. Kliknutí na odkaz = automatické přihlášení
4. Token je platný **15 minut** a použitelný pouze **jednou**

**Komponenty:**

- ✅ Migrace pro `login_tokens` tabulku
- ✅ `MagicLinkController` pro generování a validaci tokenů
- ✅ Email šablona v Kavi stylu
- ✅ Integrace do login stránky
- ✅ Integrace do checkout stránky (modal)
- ✅ Command pro čištění starých tokenů
- ✅ Automatické spouštění každý den v 3:00

## 📦 Instalace a spuštění

### Krok 1: Spustit migraci

```bash
php artisan migrate
```

To vytvoří tabulku `login_tokens` s těmito sloupci:

- `id` - primární klíč
- `email` - email uživatele
- `token` - hashovaný token (SHA-256)
- `expires_at` - čas expirace
- `used_at` - čas použití (nullable)
- `created_at`, `updated_at`

### Krok 2: Nastavení scheduleru (pro automatické čištění tokenů)

V produkčním prostředí přidejte do cronu:

```bash
* * * * * cd /path-to-kavi && php artisan schedule:run >> /dev/null 2>&1
```

Nebo spouštějte manuálně čištění:

```bash
php artisan auth:cleanup-login-tokens
```

### Krok 3: Testování

#### Test Guest Checkout:

1. Odhlaste se z účtu (pokud jste přihlášeni)
2. Přidejte produkt do košíku
3. Přejděte na `/pokladna`
4. Vyplňte formulář jako host (bez přihlášení)
5. Dokončete objednávku

#### Test Magic Link:

1. Přejděte na `/prihlaseni`
2. Zadejte email existujícího uživatele
3. Klikněte na "Přihlásit se odkazem z emailu"
4. Zkontrolujte email (nebo log)
5. Klikněte na odkaz v emailu
6. Měli byste být automaticky přihlášeni

#### Test Magic Link z Checkout:

1. Odhlaste se z účtu
2. Přejděte na `/pokladna`
3. Klikněte na "Poslat přihlašovací odkaz"
4. Zadejte email
5. Po přihlášení budete přesměrováni zpět na checkout

## 🔐 Bezpečnost

### Magic Link

- **Tokeny jsou hashovány** pomocí SHA-256 před uložením do DB
- **Jednorázové použití** - po použití je token označen jako použitý
- **Časově omezené** - platnost 15 minut
- **Ochrana proti email enumeration** - vždy stejná zpráva bez ohledu na existenci účtu

### Guest Checkout

- **Kontrola duplicitních emailů** - pokud email již existuje, vyžaduje přihlášení
- **Ochrana dat** - guest objednávky nemají přístup k uživatelským funkcím
- **Validace** - stejná validace jako pro přihlášené uživatele

## 📁 Nové soubory

```
app/
├── Console/Commands/
│   └── CleanupExpiredLoginTokens.php    (command pro čištění)
├── Http/Controllers/Auth/
│   └── MagicLinkController.php          (magic link logika)
└── Mail/
    └── MagicLoginLink.php                (mail třída)

database/migrations/
└── 2025_10_29_012212_create_login_tokens_table.php

resources/views/emails/
└── magic-login-link.blade.php            (email šablona)

routes/
└── auth.php                               (+ magic link routes)
```

## 🔄 Upravené soubory

```
routes/web.php                             (odstranění auth middleware z checkoutu)
app/Http/Controllers/CheckoutController.php (guest checkout podpora)
app/Http/Controllers/Auth/LoginController.php (redirect podpora)
app/Console/Kernel.php                     (naplánovaný cleanup)
resources/views/auth/login.blade.php       (magic link UI)
resources/views/checkout/index.blade.php   (guest info + magic link modal)
```

## 🎨 UX Flow

### Guest Checkout Flow

```
Produkt → Košík → Checkout (bez přihlášení)
                     ↓
            ┌────────┴────────┐
            │                 │
     [Guest mode]      [Přihlásit se]
            │                 │
            ↓                 ↓
      Vyplní údaje      Login page
            │                 │
            ↓                 │
       Objednávka  ←──────────┘
            │
            ↓
     Email s potvrzením
```

### Magic Link Flow

```
Login page → Zadá email → Klikne "Poslat odkaz"
                              ↓
                    Email s magic linkem
                              ↓
                    Klikne na odkaz (15 min)
                              ↓
                    Automatické přihlášení
                              ↓
                    Redirect na cíl (nebo dashboard)
```

## 📊 Databázový model

### login_tokens

```sql
CREATE TABLE login_tokens (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (email)
);
```

### orders

```sql
-- user_id je již nullable
user_id BIGINT UNSIGNED NULL
```

## 🧪 Testovací scénáře

### 1. Guest Checkout - Nový zákazník

- ✅ Přidat produkt do košíku
- ✅ Checkout bez registrace
- ✅ Email s potvrzením doručen
- ✅ Objednávka vytvořena s `user_id = null`

### 2. Guest Checkout - Existující email

- ✅ Použít email existujícího účtu
- ✅ Zobrazí se chyba: "Účet již existuje, přihlaste se"

### 3. Magic Link - Nový token

- ✅ Poslat magic link
- ✅ Email doručen
- ✅ Token uložen v DB (hashovaný)
- ✅ Klik na link = přihlášení

### 4. Magic Link - Expirovaný token

- ✅ Použít token starší 15 minut
- ✅ Zobrazí se chyba: "Odkaz vypršel"

### 5. Magic Link - Použitý token

- ✅ Použít token podruhé
- ✅ Zobrazí se chyba: "Odkaz je neplatný"

### 6. Magic Link - Neexistující uživatel

- ✅ Poslat na neexistující email
- ✅ Stejná zpráva jako pro existující (bezpečnost)
- ✅ Žádný email odeslán

### 7. Cleanup Command

- ✅ Spustit `php artisan auth:cleanup-login-tokens`
- ✅ Smazány expirované tokeny
- ✅ Smazány použité tokeny starší 24h

## 🚀 Další možná vylepšení

1. **Post-checkout registrace**

   - Po guest objednávce nabídnout vytvoření účtu
   - Email již máme, stačí přidat heslo nebo poslat magic link

2. **Throttling**

   - Omezit počet magic link requestů per IP/email
   - Ochrana proti spamu

3. **Statistiky**

   - Tracking konverze guest vs. registered
   - Počet použití magic link

4. **Remember Device**
   - Uložit zařízení jako důvěryhodné
   - Automatické přihlášení na známých zařízeních

## 📞 Podpora

Máte-li dotazy nebo problémy:

- Zkontrolujte logy: `storage/logs/laravel.log`
- Spusťte testy: `php artisan test`
- Kontaktujte vývojáře

---

**Implementováno:** 29. října 2025  
**Autor:** AI Assistant (Claude Sonnet 4.5)  
**Status:** ✅ Hotovo a připraveno k testování
