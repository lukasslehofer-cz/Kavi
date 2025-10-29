# 🎯 Automatické vytváření účtů pro hosty

## Problém

Když host (nepřihlášený uživatel) objednal předplatné nebo produkt:

- ❌ **Nevytvořil se** mu uživatelský účet
- ❌ **Magic link nefungoval** (vyžaduje existující účet)
- ❌ **Nemohl spravovat** objednávky nebo předplatné
- ❌ Musel se **manuálně registrovat** s novým heslem

## Řešení

### ✅ Automatické vytvoření účtu po první objednávce

Po úspěšném dokončení objednávky (běžné nebo předplatné) se **automaticky vytvoří uživatelský účet** s:

- Emailem z objednávky
- Jménem z objednávky
- **Náhodným heslem** (32 znaků)
- Všemi údaji z objednávky (adresa, telefon, Packeta bod)

### 🔐 Přihlášení pomocí Magic Link

Protože účet má náhodné heslo, uživatel se **přihlásí pomocí magic linku**:

1. Na login stránce zadá email
2. Klikne "Přihlásit se odkazem z emailu"
3. Dostane email s magic linkem
4. Klikne na link → automaticky přihlášen ✨

### 📧 Kde se to děje

#### Pro předplatné (Stripe webhook)

**Soubor:** `app/Services/StripeService.php` (řádek 412-445)

```php
// Create user account for guest subscriptions
if (!$userId && $guestEmail) {
    try {
        $existingUser = User::where('email', $guestEmail)->first();

        if (!$existingUser) {
            $newUser = User::create([
                'name' => $subscription->shipping_address['name'] ?? 'Zákazník',
                'email' => $guestEmail,
                'password' => \Hash::make(\Str::random(32)), // Random password
                'phone' => $subscription->shipping_address['phone'] ?? null,
                'address' => $subscription->shipping_address['billing_address'] ?? null,
                // ... další údaje
            ]);

            // Link subscription to the new user
            $subscription->update(['user_id' => $newUser->id]);
        }
    } catch (\Exception $e) {
        \Log::error('Failed to create user account for guest subscription');
    }
}
```

#### Pro běžné objednávky

**Soubor:** `app/Http/Controllers/CheckoutController.php` (řádek 166-196)

```php
// Create user account for guest orders
if (!auth()->check()) {
    try {
        $existingUser = \App\Models\User::where('email', $request->email)->first();

        if (!$existingUser) {
            $newUser = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make(\Str::random(32)),
                // ... další údaje
            ]);

            // Link order to the new user
            $order->update(['user_id' => $newUser->id]);
        }
    } catch (\Exception $e) {
        \Log::error('Failed to create user account for guest order');
    }
}
```

## 🔄 Flow po implementaci

### První objednávka jako host:

```
1. Host nakonfiguruje objednávku
   ↓
2. Vyplní email (např. lukas.slehofer@gmail.com)
   ↓
3. Dokončí platbu
   ↓
4. ✨ AUTOMATICKY se vytvoří účet:
   ├─ Email: lukas.slehofer@gmail.com
   ├─ Jméno: z objednávky
   ├─ Heslo: náhodné (např. "kJ8n2Lp9mQ4wR3xY5vZ7...")
   └─ Údaje: adresa, telefon, Packeta bod
   ↓
5. Objednávka/Subscription se propojí s novým účtem
   ├─ order.user_id = newUser.id
   └─ subscription.user_id = newUser.id
```

### Přihlášení po objednávce:

```
1. Uživatel jde na /prihlaseni
   ↓
2. Zadá email: lukas.slehofer@gmail.com
   ↓
3. Klikne "Přihlásit se odkazem z emailu"
   ↓
4. Dostane email s magic linkem ✨
   (Protože účet už existuje!)
   ↓
5. Klikne na link → přihlášen
   ↓
6. Vidí dashboard s objednávkami a předplatným 🎉
```

## 🔒 Bezpečnost

### Duplicita emailů

```php
// Vždy se kontroluje, jestli účet už neexistuje
$existingUser = User::where('email', $email)->first();

if (!$existingUser) {
    // Vytvoř nový účet
}
```

### Náhodné heslo

```php
// Heslo je 32 znaků náhodných
'password' => \Hash::make(\Str::random(32))
// Příklad: "kJ8n2Lp9mQ4wR3xY5vZ7tB6nC4hG2dF1"
```

Uživatel **neví** toto heslo, takže **musí** použít:

- Magic link
- Nebo "Zapomenuté heslo" pro nastavení nového

## 📊 Výhody

### Pro zákazníka:

- ✅ **Automatický účet** po první objednávce
- ✅ **Snadné přihlášení** pomocí magic linku
- ✅ **Vidí historii** objednávek a předplatných
- ✅ **Může spravovat** předplatné
- ✅ **Žádné heslo k zapamatování**

### Pro eshop:

- ✅ **Vyšší retention** - zákazníci mají účet
- ✅ **Lepší CRM** - všichni zákazníci jsou v databázi
- ✅ **Email marketing** - můžete posílat newslettery
- ✅ **Jednodušší podpora** - vidíte historii zákazníka

## 🧪 Testování

### Test 1: Guest objednávka → Automatický účet

1. Odhlaste se
2. Objednejte jako host (nový email)
3. Zkontrolujte v DB:

```sql
SELECT * FROM users WHERE email = 'test@example.com';
-- Měl by existovat!
```

### Test 2: Magic link funguje

1. Jděte na `/prihlaseni`
2. Zadejte email z guest objednávky
3. Klikněte "Přihlásit se odkazem z emailu"
4. Email by měl dorazit! ✨

### Test 3: Dashboard přístup

1. Přihlaste se pomocí magic linku
2. Jděte na `/dashboard`
3. Měli byste vidět objednávku/předplatné! 🎉

### Test 4: Duplicita se neděje

1. Objednejte s emailem `lukas@test.cz`
2. Zkontrolujte DB - 1 uživatel
3. Objednejte znovu s `lukas@test.cz`
4. Zkontrolujte DB - stále 1 uživatel (ne 2!)

## 📝 Poznámky

### Pro existující hosty

Pokud někdo **již objednal jako host** (před touto implementací):

- Nemá ještě účet
- **Musí se registrovat** manuálně na `/registrace`
- Nebo počkat na další objednávku (pak se účet vytvoří)

### Propojení starých objednávek

Můžete manuálně propojit staré objednávky:

```sql
-- Najít guest objednávky
SELECT * FROM orders WHERE user_id IS NULL;
SELECT * FROM subscriptions WHERE user_id IS NULL;

-- Propojit s uživatelem
UPDATE orders SET user_id = 123 WHERE email = 'user@example.com' AND user_id IS NULL;
UPDATE subscriptions SET user_id = 123 WHERE JSON_EXTRACT(shipping_address, '$.email') = 'user@example.com' AND user_id IS NULL;
```

## 🎯 Další vylepšení (volitelné)

### 1. Email s welcome zprávou

Po vytvoření účtu poslat email:

- "Váš účet byl vytvořen!"
- "Přihlaste se pomocí magic linku"
- Link na `/prihlaseni`

### 2. Automatické přihlášení

Po objednávce automaticky přihlásit uživatele:

```php
Auth::login($newUser);
```

### 3. Možnost nastavit si heslo

Na confirmation stránce nabídnout:

- "Nastavit si heslo" tlačítko
- Formulář pro vytvoření hesla

---

**Implementováno:** 29. října 2025  
**Status:** ✅ Hotovo a funkční
