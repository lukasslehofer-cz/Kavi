# 🐛 Bugfix: Guest Subscription s platbou kartou

## Problém

Když host (nepřihlášený uživatel) objednával předplatné s platbou kartou:

- ❌ Nezobrazila se potvrzovací stránka
- ❌ Nedorazil potvrzovací email
- ❌ Subscription se nevytvořila v databázi

## Příčina

**Stripe webhook nepodporoval guest subscription:**

- Webhook handler `handleSubscriptionCreated` vyžadoval `user_id` v metadatech
- Pokud `user_id` chyběl (= host), webhook se zastavil a nic nevytvořil
- Po návratu ze Stripe se subscription nehledala pro hosty

## Řešení

### 1. ✅ Upravený webhook handler

**Soubor:** `app/Services/StripeService.php`

**Změna 1:** Podpora guest subscription (řádek 345-354)

```php
// Předtím:
if (!$userId) {
    return; // Zastavil se!
}

// Nyní:
$guestEmail = $subscriptionData['metadata']['guest_email'] ?? null;

if (!$userId && !$guestEmail) {
    return; // Zastaví se pouze pokud chybí obojí
}
```

**Změna 2:** Bezpečné získání emailu (řádek 414-429)

```php
// Předtím:
$email = $subscription->shipping_address['email'] ?? $subscription->user->email ?? null;
// ^ Způsobilo chybu pokud user_id byl NULL

// Nyní:
$email = $subscription->shipping_address['email'] ?? null;
if (!$email && $subscription->user) {
    $email = $subscription->user->email;
}
```

### 2. ✅ Hledání subscription pro hosty po návratu ze Stripe

**Soubor:** `app/Http/Controllers/SubscriptionController.php`

**Změna:** Najdi subscription i pro hosty (řádek 156-203)

```php
// Předtím:
if (auth()->check()) {
    $subscription = Subscription::where('user_id', auth()->id())->latest()->first();
}
// ^ Pro hosty $subscription zůstalo NULL

// Nyní:
if (auth()->check()) {
    $subscription = Subscription::where('user_id', auth()->id())->latest()->first();
} else {
    // Pro hosty najdi podle emailu v shipping_address
    $guestEmail = session('guest_subscription_email');
    if ($guestEmail) {
        $subscription = Subscription::whereNull('user_id')
            ->whereRaw("JSON_EXTRACT(shipping_address, '$.email') = ?", [$guestEmail])
            ->latest()
            ->first();
    }
}
```

### 3. ✅ Lepší zprávy pro hosty

```php
// Pro hosty se nyní zobrazí:
"Děkujeme za objednávku! Potvrzení a další informace jsme odeslali na {email}."
```

## Testování

### Jak otestovat opravu:

1. **Odhlaste se** z účtu
2. Nakonfigurujte předplatné na `/predplatne`
3. Přejděte na checkout
4. Vyplňte údaje jako **host** (nový email)
5. Vyberte **platbu kartou**
6. Použijte Stripe **test kartu:** `4242 4242 4242 4242`
7. Po úspěšné platbě se **měl by:**
   - ✅ Vytvořit subscription v DB s `user_id = NULL`
   - ✅ Odeslat potvrzovací email
   - ✅ Zobrazit success message

### Kontrola v databázi:

```sql
-- Najdi guest subscriptions
SELECT * FROM subscriptions WHERE user_id IS NULL ORDER BY created_at DESC LIMIT 5;

-- Zkontroluj shipping_address
SELECT id, subscription_number, JSON_EXTRACT(shipping_address, '$.email') as email
FROM subscriptions WHERE user_id IS NULL;
```

### Kontrola v logu:

```bash
tail -f storage/logs/laravel.log | grep -E "handleSubscriptionCreated|guest|Subscription"
```

## Změněné soubory

```
app/Services/StripeService.php
  ├─ handleSubscriptionCreated() - podpora guest subscription
  └─ safe email retrieval pro subscription confirmation

app/Http/Controllers/SubscriptionController.php
  └─ checkout() - najde subscription i pro hosty
```

## Flow po opravě

### Guest Subscription s kartou:

```
1. Host nakonfiguruje předplatné
   ↓
2. Guest info se uloží do session
   ├─ guest_subscription_email
   ├─ guest_subscription_name
   └─ guest_subscription_phone
   ↓
3. Redirect na Stripe Checkout
   └─ metadata obsahuje: guest_email, shipping_address
   ↓
4. Host zaplatí kartou
   ↓
5. Stripe pošle webhook: customer.subscription.created
   ├─ Webhook kontroluje: user_id NEBO guest_email ✅
   ├─ Vytvoří subscription s user_id = NULL ✅
   └─ Pošle confirmation email ✅
   ↓
6. Redirect zpět na /predplatne/pokladna?success=1
   ├─ Najde subscription podle guest_email ✅
   └─ Zobrazí success message ✅
```

## Status

✅ **Opraveno a připraveno k testování**

---

**Datum:** 29. října 2025  
**Opravené v:** response na uživatelovu zpětnou vazbu
