# Implementace funkce Zapomenuté heslo

## 📋 Přehled

Implementována kompletní funkce pro reset hesla v souladu s designem registračního a přihlašovacího procesu Kavi Coffee.

## 🎯 Implementované součásti

### 1. Controllers

#### ForgotPasswordController

- **Cesta**: `app/Http/Controllers/Auth/ForgotPasswordController.php`
- **Funkce**:
  - `showLinkRequestForm()` - zobrazí formulář pro zadání emailu
  - `sendResetLinkEmail()` - odešle email s reset odkazem

#### ResetPasswordController

- **Cesta**: `app/Http/Controllers/Auth/ResetPasswordController.php`
- **Funkce**:
  - `showResetForm()` - zobrazí formulář pro nastavení nového hesla
  - `reset()` - zpracuje reset hesla

### 2. Views

#### forgot-password.blade.php

- **Cesta**: `resources/views/auth/forgot-password.blade.php`
- **Design**: Kopíruje styl registrace a přihlášení
- **Funkce**:
  - Formulář pro zadání emailu
  - Informační boxy s instrukcemi
  - Success message po odeslání emailu
  - Odkazy zpět na přihlášení a registraci

#### reset-password.blade.php

- **Cesta**: `resources/views/auth/reset-password.blade.php`
- **Design**: Kopíruje styl registrace a přihlášení
- **Funkce**:
  - Formulář pro nastavení nového hesla
  - Potvrzení hesla
  - Požadavky na heslo
  - Bezpečnostní informace

### 3. Routy

#### Nové routy v `routes/auth.php`:

```php
// Password Reset Routes
Route::get('/zapomenute-heslo', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('/zapomenute-heslo', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('/reset-hesla/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-hesla', [ResetPasswordController::class, 'reset'])
    ->name('password.update');
```

### 4. Email šablona

Již existující šablona:

- **Cesta**: `resources/views/emails/reset-password.blade.php`
- **Design**: Profesionální email s instrukcemi pro reset hesla
- **Obsahuje**:
  - Informační boxy s různými barvami
  - Tlačítko pro reset hesla
  - Tipy pro silné heslo
  - Bezpečnostní upozornění

### 5. České překlady

#### `lang/cs/passwords.php`

- Překlady pro všechny stavové hlášky reset hesla:
  - Token je neplatný
  - Uživatel nenalezen
  - Heslo resetováno
  - Email odeslán
  - Throttling

#### `lang/cs/validation.php`

- České validační hlášky pro všechna pole včetně hesla

### 6. Aktualizace existujících souborů

#### login.blade.php

- Aktualizován odkaz "Zapomenuté heslo?" na funkční route:
  ```php
  <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
      Zapomenuté heslo?
  </a>
  ```

## 🔐 Bezpečnostní prvky

1. **Token expirace**: 60 minut (nastaveno v `config/auth.php`)
2. **Throttling**: Omezení počtu pokusů na 60 sekund
3. **Bezpečné zprávy**: Neprozrazujeme, zda email existuje v systému
4. **Hashování hesel**: Automatické pomocí Laravel
5. **Remember token**: Automaticky regenerován po změně hesla
6. **CSRF ochrana**: Na všech formulářích

## 📧 Email notifikace

Systém používá vlastní notifikaci:

- **Třída**: `App\Notifications\ResetPasswordNotification`
- **Email šablona**: `resources/views/emails/reset-password.blade.php`
- **User model**: Metoda `sendPasswordResetNotification()` již implementována

## 🗄️ Databáze

Migrace již existuje:

- **Tabulka**: `password_reset_tokens`
- **Sloupce**:
  - `email` (primary key)
  - `token`
  - `created_at`

## ⚙️ Konfigurace

### config/auth.php

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,      // Token platný 60 minut
        'throttle' => 60,    // Čekací doba mezi požadavky
    ],
],
```

### config/app.php

```php
'locale' => env('APP_LOCALE', 'cs'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'cs'),
```

## 🎨 Design systém

Všechny views používají jednotný design:

- **Barvy**: Primary color (#e6305a) pro tlačítka a odkazy
- **Komponenty**: Zaoblené rohy (rounded-2xl, rounded-xl, rounded-full)
- **Typografie**: Font-light pro text, font-medium pro důležité prvky
- **Ikony**: SVG ikony z Heroicons
- **Layout**: Centrovaný formulář s maximální šířkou 28rem (max-w-md)
- **Responzivní**: Plně responzivní design

## 🚀 Jak to funguje

### Proces resetu hesla:

1. **Uživatel klikne na "Zapomenuté heslo?"** na přihlašovací stránce
2. **Zadá svůj email** na stránce `/zapomenute-heslo`
3. **Systém odešle email** s odkazem pro reset (pokud email existuje)
4. **Uživatel klikne na odkaz** v emailu → přesměrován na `/reset-hesla/{token}`
5. **Zadá nové heslo** a potvrzení hesla
6. **Systém změní heslo** a přesměruje na přihlášení
7. **Uživatel se přihlásí** s novým heslem

### Bezpečnostní opatření:

- Odkaz je platný pouze 60 minut
- Token je použitelný pouze jednou
- Po resetu je vygenerován nový remember token
- Systém neprozradí, zda email existuje v databázi

## ✅ Kontrolní seznam

- [x] ForgotPasswordController vytvořen
- [x] ResetPasswordController vytvořen
- [x] View forgot-password.blade.php vytvořen
- [x] View reset-password.blade.php vytvořen
- [x] Routy přidány do auth.php
- [x] České překlady vytvořeny
- [x] Odkaz v login.blade.php aktualizován
- [x] Email šablona již existuje
- [x] User model již má sendPasswordResetNotification()
- [x] Migrace již existuje
- [x] Konfigurace auth.php již nastavena

## 🧪 Testování

Pro otestování funkce:

1. Spusťte `php artisan serve`
2. Přejděte na `/prihlaseni`
3. Klikněte na "Zapomenuté heslo?"
4. Zadejte existující email
5. Zkontrolujte email v logu nebo v mailhog/mailtrap
6. Klikněte na odkaz v emailu
7. Nastavte nové heslo
8. Přihlaste se s novým heslem

## 📝 Poznámky

- Všechny routy používají české URL slugy (`/zapomenute-heslo`, `/reset-hesla`)
- Design kopíruje styl registračního a přihlašovacího procesu
- Email šablona je plně responzivní a podporuje dark mode
- Systém používá Laravel's vestavěnou Password Reset funkcionalitu
- České překlady zajišťují lokalizované chybové hlášky
