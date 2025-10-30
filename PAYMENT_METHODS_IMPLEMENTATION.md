# Implementace správy platebních metod v profilu uživatele

## 📋 Přehled

Přidáno zobrazení aktuální platební metody a možnost její změny v `/dashboard/profil` pomocí **Stripe Customer Portal**.

---

## ✅ Co bylo implementováno

### 1. **Backend - StripeService** (`app/Services/StripeService.php`)

Přidány nové metody:

#### `getPaymentMethodDetails(string $customerId): ?array`

- Načte detaily o platební metodě uživatele ze Stripe
- Vrací: brand karty (Visa, Mastercard), poslední 4 čísla, datum expirace
- Bezpečně řeší chyby a vrací `null` pokud metoda neexistuje

#### `createCustomerPortalSession(User $user, string $returnUrl): string`

- Vytvoří Stripe Customer Portal session pro správu platebních metod
- Vrací URL pro přesměrování na Stripe portal
- Portal je zabezpečený a má časový limit (30 minut)

---

### 2. **Backend - DashboardController** (`app/Http/Controllers/DashboardController.php`)

#### Upravená metoda `profile()`

```php
public function profile()
{
    $user = auth()->user();
    $paymentMethod = null;

    // Get payment method details if user has Stripe customer ID
    if ($user->stripe_customer_id) {
        $stripeService = app(\App\Services\StripeService::class);
        $paymentMethod = $stripeService->getPaymentMethodDetails($user->stripe_customer_id);
    }

    return view('dashboard.profile', compact('paymentMethod'));
}
```

#### Nová metoda `managePaymentMethods()`

```php
public function managePaymentMethods()
{
    // Vytvoří Stripe Portal session a přesměruje uživatele
    // Po dokončení se uživatel vrátí na /dashboard/profil
}
```

---

### 3. **Routes** (`routes/web.php`)

Přidána nová route:

```php
Route::get('/platebni-metody/spravovat', [DashboardController::class, 'managePaymentMethods'])
    ->name('payment-methods.manage');
```

**Celá URL:** `https://vase-domena.cz/dashboard/platebni-metody/spravovat`

---

### 4. **Frontend - Profil** (`resources/views/dashboard/profile.blade.php`)

Přidána nová sekce **"Platební metody"** mezi "Osobní údaje" a "Fakturační adresa".

#### Když uživatel MÁ platební metodu:

- Zobrazí kartu s brandou (Visa/Mastercard/jiné)
- Zobrazí poslední 4 čísla a datum expirace
- Upozornění pokud karta brzy vyprší (< 30 dní) nebo už vypršela
- Tlačítko **"Změnit platební metodu"** → přesměruje na Stripe Portal

#### Když uživatel NEMÁ platební metodu:

- Info box: "Zatím nemáte nastavenou žádnou platební metodu"
- Vysvětlení: "Při vytvoření předplatného bude karta automaticky uložena"
- Pokud má `stripe_customer_id`: tlačítko **"Přidat platební metodu"**

---

## 🎨 UI/UX Features

### Indikátory expirace karty:

- ✅ **Zelená** - karta je platná (> 30 dní do expirace)
- 🟠 **Oranžová** badge "Brzy vyprší" - méně než 30 dní
- 🔴 **Červená** badge "Karta vypršela" - karta je neplatná

### Ikony karet:

- Visa - bílý text "VISA"
- Mastercard - červeno-oranžové kruhy
- Ostatní - generická ikona kreditní karty

---

## 🔧 Jak to funguje

```
┌──────────────┐
│   Uživatel   │
└──────┬───────┘
       │ 1. Klikne "Změnit platební metodu"
       ▼
┌──────────────────────────┐
│  DashboardController     │
│  managePaymentMethods()  │
└──────────┬───────────────┘
           │ 2. Vytvoří Portal Session
           ▼
┌──────────────────┐
│  StripeService   │
│  Stripe API      │
└──────────┬───────┘
           │ 3. Vrátí Portal URL
           ▼
┌───────────────────────────┐
│  Stripe Customer Portal   │
│  (stripe.com)             │
└──────────┬────────────────┘
           │ 4. Uživatel upraví kartu
           ▼
┌───────────────────────────┐
│  Redirect zpět na profil  │
│  /dashboard/profil        │
└───────────────────────────┘
```

---

## 🚀 Instalace a aktivace

### Krok 1: Aktivovat Customer Portal ve Stripe Dashboard

⚠️ **DŮLEŽITÉ:** Customer Portal musí být aktivovaný ve Stripe Dashboard!

1. Přihlaste se na https://dashboard.stripe.com
2. Přejděte na **Settings** → **Billing** → **Customer portal**
3. Klikněte **"Activate test link"** (pro test mode)
4. Nastavte:
   - ✅ **Payment methods** - umožnit správu platebních metod
   - ✅ **Update payment method** - povolit aktualizaci
   - ❌ **Cancel subscriptions** - ZAKÁZAT (máte vlastní logiku)
   - ❌ **Pause subscriptions** - ZAKÁZAT (máte vlastní logiku)
5. V **Business information** vyplňte:
   - Business name: `Kavi Coffee`
   - Support email: váš email
   - Privacy policy: `https://vase-domena.cz/ochrana-osobnich-udaju`
   - Terms of service: `https://vase-domena.cz/obchodni-podminky`
6. Uložte změny

### Krok 2: Nastavit češtinu (volitelné)

Stripe Customer Portal podporuje češtinu! Jazyk se nastaví automaticky podle prohlížeče uživatele.

Pro vynucení češtiny můžete upravit `StripeService::createCustomerPortalSession()`:

```php
$session = \Stripe\BillingPortal\Session::create([
    'customer' => $customerId,
    'return_url' => $returnUrl,
    'locale' => 'cs', // Vynucení češtiny
]);
```

### Krok 3: Testování

1. Přihlaste se jako uživatel s předplatným
2. Přejděte na `/dashboard/profil`
3. Klikněte **"Změnit platební metodu"**
4. Budete přesměrováni na Stripe Customer Portal
5. Upravte platební metodu (testovací karty: 4242 4242 4242 4242)
6. Po dokončení se vrátíte na profil s aktualizovanými údaji

---

## 📊 Datový tok

### Načtení platební metody:

```
User → has stripe_customer_id?
  ├─ Ano → StripeService::getPaymentMethodDetails()
  │         └─ Stripe API → PaymentMethod
  │                       └─ Return [brand, last4, exp_month, exp_year]
  └─ Ne  → paymentMethod = null
```

### Změna platební metody:

```
User clicks button
  ↓
Route: dashboard.payment-methods.manage
  ↓
DashboardController::managePaymentMethods()
  ↓
StripeService::createCustomerPortalSession()
  ↓
Stripe API creates Portal Session (30 min validity)
  ↓
Redirect to stripe.com/billing-portal/...
  ↓
User updates card
  ↓
Stripe processes changes
  ↓
Redirect back to: dashboard.profile
  ↓
Updated payment method shown
```

---

## 🔐 Bezpečnost

✅ **PCI DSS Compliance** - Všechny citlivé údaje o kartách spravuje Stripe  
✅ **Autentizace** - Pouze přihlášení uživatelé (middleware `auth`)  
✅ **Autorizace** - Customer ID je svázán s User ID  
✅ **Časový limit** - Portal Session vyprší po 30 minutách  
✅ **Šifrování** - Veškerá komunikace přes HTTPS  
✅ **Audit log** - Všechny akce logované v `storage/logs/laravel.log`

---

## 📝 Uživatelské stavy

### Stav 1: Nový uživatel (bez předplatného)

- Nemá `stripe_customer_id`
- Zobrazí se: "Zatím nemáte nastavenou žádnou platební metodu"
- **Žádné tlačítko** (nemá smysl otevírat portal)

### Stav 2: Uživatel s předplatným (má platební metodu)

- Má `stripe_customer_id` + platební metodu
- Zobrazí se: Karta Visa ••••4242, vyprší 12/2025
- Tlačítko: **"Změnit platební metodu"**

### Stav 3: Uživatel s předplatným (nemá platební metodu)

- Má `stripe_customer_id`, ale nemá uloženou kartu
- Zobrazí se: Info box + vysvětlení
- Tlačítko: **"Přidat platební metodu"**

### Stav 4: Karta brzy vyprší

- Zobrazí se oranžový badge "Brzy vyprší"
- Uživatel by měl aktualizovat kartu

### Stav 5: Karta vypršela

- Zobrazí se červený badge "Karta vypršela"
- Platby selžou, nutno aktualizovat!

---

## 🧪 Testovací karty

Pro testování použijte tyto karty:

| Karta               | Použití                       |
| ------------------- | ----------------------------- |
| 4242 4242 4242 4242 | Úspěšná platba (Visa)         |
| 5555 5555 5555 4444 | Úspěšná platba (Mastercard)   |
| 4000 0000 0000 0341 | Karta vyžaduje autentizaci    |
| 4000 0000 0000 9995 | Selhání platby (nedostatek $) |

Expirace: jakékoliv budoucí datum (např. 12/25)  
CVC: jakékoliv 3 číslice (např. 123)

---

## 🐛 Troubleshooting

### Problém: "Nepodařilo se otevřít správu platebních metod"

**Řešení:**

1. Zkontrolujte, že je Customer Portal aktivovaný ve Stripe Dashboard
2. Zkontrolujte `storage/logs/laravel.log` pro detaily chyby
3. Ověřte Stripe API klíč v `.env` (STRIPE_SECRET)

### Problém: Platební metoda se nezobrazuje

**Řešení:**

1. Ověřte, že uživatel má `stripe_customer_id` v databázi
2. Zkontrolujte v Stripe Dashboard, že customer má přiřazenou platební metodu
3. Zkontrolujte logy: `\Log::info()` v `StripeService::getPaymentMethodDetails()`

### Problém: Portal je v angličtině místo češtiny

**Řešení:**

1. Přidejte `'locale' => 'cs'` do `createCustomerPortalSession()`
2. Nebo nastavte jazyk prohlížeče na češtinu (Portal respektuje Accept-Language header)

---

## 🔄 Maintenance & Updates

### Když přidáváte nový typ platební metody (např. Apple Pay):

1. Upravte `StripeService::getPaymentMethodDetails()` pro podporu nových typů
2. Přidejte ikonu do `dashboard/profile.blade.php`
3. Otestujte zobrazení

### Když chcete přidat více funkcí do portálu:

1. Upravte nastavení v Stripe Dashboard → Customer Portal
2. Žádné změny v kódu nejsou potřeba!

---

## 📚 Dokumentace

- **Stripe Customer Portal:** https://stripe.com/docs/billing/subscriptions/integrating-customer-portal
- **Stripe Payment Methods:** https://stripe.com/docs/payments/payment-methods
- **Stripe PHP Library:** https://stripe.com/docs/api/php

---

## ✅ Checklist pro produkci

- [ ] Aktivovat Customer Portal ve Stripe Dashboard (LIVE mode)
- [ ] Vyplnit Business information v Customer Portal settings
- [ ] Otestovat flow s testovacími kartami
- [ ] Ověřit, že uživatel se vrací správně zpět na profil
- [ ] Zkontrolovat, že změna karty se projeví okamžitě
- [ ] Nastavit monitoring pro chyby při vytváření Portal Session
- [ ] Přidat Google Analytics tracking pro "Změnit platební metodu" akci (volitelné)

---

**Implementováno:** 30. října 2025  
**Autor:** AI Cursor Assistant  
**Verze:** 1.0
