# 🚀 Quick Start - Zapomenuté heslo

## Okamžité testování (2 minuty)

### 1. Ujistěte se, že aplikace běží

```bash
php artisan serve
```

### 2. Otevřete přihlašovací stránku

```
http://localhost:8000/prihlaseni
```

### 3. Klikněte na "Zapomenuté heslo?"

Budete přesměrováni na:

```
http://localhost:8000/zapomenute-heslo
```

### 4. Pro testování emailů

**NEJRYCHLEJŠÍ ZPŮSOB - Log soubor:**

V `.env` nastavte:

```env
MAIL_MAILER=log
```

Pak spusťte v novém terminálu:

```bash
tail -f storage/logs/laravel.log
```

### 5. Zadejte testovací email

Použijte email existujícího uživatele z databáze:

```bash
# Zobrazit uživatele
php artisan tinker
>>> User::all(['email'])->pluck('email');
>>> exit
```

Nebo vytvořte nového:

```bash
php artisan tinker
>>> User::create(['name' => 'Test User', 'email' => 'test@kavi.cz', 'password' => bcrypt('password123')]);
>>> exit
```

### 6. Najděte reset link v logu

V `storage/logs/laravel.log` najděte řádek obsahující URL jako:

```
http://localhost:8000/reset-hesla/DLOUHÝ_TOKEN?email=test@kavi.cz
```

### 7. Otevřete link v prohlížeči

Zkopírujte celou URL a vložte do prohlížeče.

### 8. Nastavte nové heslo

- Zadejte nové heslo (min. 8 znaků)
- Potvrďte heslo
- Klikněte "Nastavit nové heslo"

### 9. Přihlaste se

Budete přesměrováni na přihlášení s hláškou o úspěchu.
Přihlaste se s novým heslem!

## 🎉 Hotovo!

Funkce zapomenutého hesla plně funguje!

---

## 🐛 Problémy?

### Email se neposílá?

```bash
# Zkontrolujte .env
cat .env | grep MAIL

# Vyčistěte cache
php artisan config:cache
```

### Token je neplatný?

```bash
# Vymažte staré tokeny
php artisan tinker
>>> DB::table('password_reset_tokens')->truncate();
>>> exit
```

### 404 Not Found?

```bash
# Vyčistěte route cache
php artisan route:clear
php artisan route:cache
```

---

## 📖 Více informací

- **Kompletní dokumentace**: `PASSWORD_RESET_IMPLEMENTATION.md`
- **Testovací scénáře**: `TEST_PASSWORD_RESET.md`

## 🎨 Vizuální kontrola

Stránky mají stejný design jako:

- `/prihlaseni` - přihlášení
- `/registrace` - registrace

S těmito prvky:

- ✅ Zaoblené rohy (rounded-2xl, rounded-full)
- ✅ Primary color (#e6305a) pro tlačítka
- ✅ Ikony z Heroicons
- ✅ Info boxy s různými barvami
- ✅ Responzivní design
- ✅ Font light/medium/bold

## 🔗 Užitečné odkazy

Po spuštění aplikace:

- Login: http://localhost:8000/prihlaseni
- Zapomenuté heslo: http://localhost:8000/zapomenute-heslo
- Dashboard: http://localhost:8000/dashboard

## ⚡ Produkční nastavení

Před nasazením změňte v `.env`:

```env
MAIL_MAILER=mailgun
MAIL_FROM_ADDRESS=noreply@kavi.cz
MAIL_FROM_NAME="${APP_NAME}"
```

A vyčistěte cache:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
