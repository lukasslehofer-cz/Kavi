# 🚀 Rychlý start - Guest Checkout & Magic Link

## ✅ Co je hotové

Implementovány byly **oba požadované kroky**:

### 1️⃣ Guest Checkout pro běžné objednávky

- Zákazníci mohou nakupovat **bez registrace**
- Sjednoceno s předplatným
- Minimální bariéry při nákupu

### 2️⃣ Magic Link přihlášení

- Přihlášení **bez hesla**
- Stačí email → dostaneš odkaz → klikneš → přihlášen ✨
- Moderní UX jako Slack, Medium, Notion...

---

## 🏃 Rychlé spuštění (3 kroky)

### 1. Spustit migraci

```bash
cd /Users/lukas/Kavi
php artisan migrate
```

### 2. Otestovat Guest Checkout

```bash
# V prohlížeči:
# 1. Odhlásit se
# 2. Přidat produkt do košíku
# 3. Jít na checkout → vyplnit jako host → objednat
```

### 3. Otestovat Magic Link

```bash
# V prohlížeči:
# 1. Jít na /prihlaseni
# 2. Zadat email
# 3. Kliknout "Přihlásit se odkazem z emailu"
# 4. Zkontrolovat email nebo log
```

---

## 📧 Kde zkontrolovat email

V **development módu** se emaily ukládají do logů:

```bash
tail -f storage/logs/laravel.log
```

Nebo použijte **Mailtrap/MailHog** pokud máte nastavené.

---

## 🎯 Klíčové funkce

### Guest Checkout

- ✅ Nákup bez registrace
- ✅ Telefon volitelný pro hosty
- ✅ Kontrola duplicitních emailů
- ✅ Možnost přihlášení kdykoliv
- ✅ Email s potvrzením objednávky

### Magic Link

- ✅ Přihlášení bez hesla
- ✅ Platnost 15 minut
- ✅ Jednorázové použití
- ✅ Bezpečné (hashované tokeny)
- ✅ Automatické čištění starých tokenů
- ✅ Redirect na původní stránku

---

## 📂 Nové soubory

```
✨ Migrace
database/migrations/2025_10_29_012212_create_login_tokens_table.php

✨ Controllers
app/Http/Controllers/Auth/MagicLinkController.php

✨ Mail
app/Mail/MagicLoginLink.php

✨ Views
resources/views/emails/magic-login-link.blade.php

✨ Commands
app/Console/Commands/CleanupExpiredLoginTokens.php

✨ Dokumentace
GUEST_CHECKOUT_AND_MAGIC_LINK.md (podrobná dokumentace)
QUICKSTART_NEW_FEATURES.md (tento soubor)
```

---

## 🔄 Upravené soubory

```
routes/web.php                           (guest checkout routes)
routes/auth.php                          (magic link routes)
app/Http/Controllers/CheckoutController.php
app/Http/Controllers/Auth/LoginController.php
app/Console/Kernel.php                   (scheduled cleanup)
resources/views/auth/login.blade.php
resources/views/checkout/index.blade.php
```

---

## 🧪 Test checklist

- [ ] Migrace úspěšně proběhla
- [ ] Guest checkout funguje (nová objednávka)
- [ ] Guest checkout blokuje existující email
- [ ] Magic link se posílá (zkontrolovat log/email)
- [ ] Magic link přihlásí uživatele
- [ ] Magic link vyprší po 15 minutách
- [ ] Magic link nefunguje po použití
- [ ] Cleanup command smaže staré tokeny
- [ ] Redirect funguje po přihlášení

---

## 💡 Tipy pro produkci

### 1. Nastavit Cron pro cleanup

```bash
* * * * * cd /path-to-kavi && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Nebo spouštět manuálně

```bash
php artisan auth:cleanup-login-tokens
```

### 3. Monitorovat logy

```bash
tail -f storage/logs/laravel.log | grep -i "magic\|login\|token"
```

---

## 🎨 UX Flow příklady

### Nový zákazník

```
1. Najde produkt
2. Přidá do košíku
3. Checkout → NEMUSÍ se registrovat! ✨
4. Vyplní email a adresu
5. Objedná
6. Dostane email s potvrzením
```

### Existující zákazník (zapomněl heslo)

```
1. Checkout → "Poslat přihlašovací odkaz"
2. Zadá email
3. Dostane magic link
4. Klikne → automaticky přihlášen ✨
5. Pokračuje v objednávce
```

### Čistě magic link přihlášení

```
1. Login page
2. Zadá email
3. Klikne "Přihlásit se odkazem z emailu"
4. Dostane email
5. Klikne na link
6. Přihlášen bez hesla! ✨
```

---

## 🐛 Debugging

### Email se neposílá?

```bash
# Zkontroluj .env
MAIL_MAILER=log  # pro development
# nebo
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...

# Restart cache
php artisan config:cache
```

### Migrace selhala?

```bash
# Zkontroluj připojení k DB
php artisan tinker
>>> DB::connection()->getPdo();

# Nebo použij SQLite pro testování
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

### Magic link nefunguje?

```bash
# Zkontroluj tokeny v DB
php artisan tinker
>>> DB::table('login_tokens')->get();

# Zkontroluj logy
tail -f storage/logs/laravel.log
```

---

## 📞 Máš problém?

1. **Zkontroluj logy:** `storage/logs/laravel.log`
2. **Přečti full dokumentaci:** `GUEST_CHECKOUT_AND_MAGIC_LINK.md`
3. **Zkontroluj .env:** Jsou všechny proměnné nastavené?
4. **Cache clear:** `php artisan cache:clear && php artisan config:cache`

---

## 🎉 Hotovo!

Tvůj eshop má teď:

- ✅ **Minimální bariéry** při nákupu (guest checkout)
- ✅ **Moderní přihlášení** bez hesla (magic link)
- ✅ **Lepší UX** pro zákazníky
- ✅ **Vyšší konverzi** díky snadnému checkoutu

**Enjoy!** 🚀☕

---

_Implementováno: 29. října 2025_
