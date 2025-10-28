# Stripe Dynamická Cenotvorba pro Předplatné

## Přehled

Systém nyní používá **dynamické ceny** místo předem definovaných Price ID ve Stripe. To umožňuje neomezený počet cenových variant bez nutnosti vytvářet desítky Price ID ve Stripe.

## Co se změnilo

### ✅ Před (Fixed Price IDs)

```php
// Problém: Každá varianta potřebovala vlastní Price ID
- 2 balení (500 Kč) → price_xxx
- 2 balení + decaf (600 Kč) → CHYBĚL PRICE ID! ❌
- 3 balení (720 Kč) → price_yyy
- 3 balení + decaf (820 Kč) → CHYBĚL PRICE ID! ❌
// atd... (18+ kombinací)
```

**Výsledek:** Decaf příplatek se ignoroval a účtovala se špatná cena.

### ✅ Po (Dynamic Pricing)

```php
// Řešení: Cena se vytváří dynamicky při checkoutu
- Jakákoliv cena (500 Kč, 600 Kč, 820 Kč, 1000 Kč...)
- Jakákoliv konfigurace (decaf, budoucí příplatky...)
- Jeden univerzální Product ID ve Stripe
```

**Výsledek:** ✅ Cena je vždy přesně taková, jakou vypočítáš v PHP.

## Jak to funguje

### 1. Základní Produkt

Jeden univerzální produkt ve Stripe:

- **Název:** "Kavi Kávové Předplatné"
- **ID:** Uložen v `subscription_configs.stripe_base_product_id`

### 2. Dynamické Price Objects

Při každém checkoutu se vytvoří **nový Price object** s přesnou cenou:

```php
'price_data' => [
    'currency' => 'czk',
    'product' => $productId,
    'recurring' => [
        'interval' => 'month',
        'interval_count' => $frequency, // 1, 2, nebo 3
    ],
    'unit_amount' => (int)($price * 100), // Přesná cena v haléřích
]
```

## Implementované změny

### StripeService.php

#### 1. Nová metoda: `getOrCreateBaseSubscriptionProduct()`

```php
/**
 * Get or create base subscription product in Stripe
 * Used for all dynamic subscription pricing
 */
private function getOrCreateBaseSubscriptionProduct(): string
```

- Vytvoří nebo získá základní produkt ze Stripe
- Uloží Product ID do databáze pro budoucí použití
- Automaticky ověřuje, že produkt existuje

#### 2. Upraveno: `createConfiguredSubscriptionCheckoutSession()`

```php
// PŘED:
'line_items' => [[
    'price' => $this->getStripePriceIdForConfiguration($configuration),
    'quantity' => 1,
]]

// PO:
'line_items' => [[
    'price_data' => [
        'currency' => 'czk',
        'product' => $productId,
        'recurring' => [
            'interval' => 'month',
            'interval_count' => $configuration['frequency'] ?? 1,
        ],
        'unit_amount' => (int)($price * 100),
    ],
    'quantity' => 1,
]]
```

#### 3. Upraveno: `createSubscriptionWithPaymentMethod()`

Stejná logika - vytváří Price object dynamicky místo použití fixního Price ID.

#### 4. Deprecated: `getStripePriceIdForConfiguration()`

Metoda je označena jako deprecated a již se nepoužívá.

### SetupStripeProducts Command

```bash
php artisan stripe:setup-products
```

**Před:**

- Vytvářel 18+ Price ID (3 množství × 3 frekvence × 2 varianty)
- Musel se spustit znovu po každé změně ceny

**Po:**

- Vytváří pouze 1 základní produkt
- Není potřeba spouštět při změně cen
- Ceny se berou z databáze (SubscriptionConfig)

## Výhody

### ✅ Spolehlivost

- **Přesné ceny:** Cena je vždy přesně taková, jakou vypočítáš
- **Decaf příplatek:** Správně zahrnut v ceně (+100 Kč)
- **Žádné chybějící Price ID:** Všechny kombinace fungují

### ✅ Flexibilita

- **Neomezené varianty:** Přidej jakýkoliv příplatek
- **Snadné změny:** Upravíš cenu v PHP, hotovo
- **Budoucí rozšíření:** Nové příplatky bez práce ve Stripe

### ✅ Údržba

- **Méně kódu:** Jednodušší implementace
- **Méně objektů:** Jeden produkt místo desítek Price ID
- **Automatické:** Produkty se vytvoří při prvním použití

## Testování

### 1. Setup (jednorázově)

```bash
php artisan stripe:setup-products
```

Vytvoří základní produkt a uloží ID do databáze.

### 2. Test Scenarios

#### ✅ Základní předplatné (2 balení, bez decaf)

```
Konfigurace: {amount: 2, type: "espresso", isDecaf: false, frequency: 1}
Očekávaná cena: 500 Kč
✅ Stripe bude účtovat: 500 Kč
```

#### ✅ S decaf příplatkem

```
Konfigurace: {amount: 2, type: "espresso", isDecaf: true, frequency: 1}
Očekávaná cena: 600 Kč (500 + 100 decaf)
✅ Stripe bude účtovat: 600 Kč
```

#### ✅ Různé frekvence

```
Konfigurace: {amount: 3, type: "filter", isDecaf: false, frequency: 2}
Očekávaná cena: 720 Kč každé 2 měsíce
✅ Stripe bude účtovat: 720 Kč každé 2 měsíce
```

### 3. Verifikace v Stripe Dashboard

1. Přejdi na https://dashboard.stripe.com/subscriptions
2. Vyber předplatné
3. Zkontroluj:
   - ✅ Cena odpovídá konfiguraci
   - ✅ Frekvence je správná (1, 2, nebo 3 měsíce)
   - ✅ Metadata obsahují `configured_price`

## Pricing Logic (SubscriptionController.php)

Cena se stále počítá v controlleru:

```php
// Základní cena podle množství
$pricingKey = 'price_' . $validated['amount'] . '_bags';
$totalPriceWithVat = SubscriptionConfig::get($pricingKey, 500);

// Příplatky
if ($validated['isDecaf']) {
    $totalPriceWithVat += 100; // +100 Kč za decaf
}

// Budoucí příplatky
// if ($validated['premium']) {
//     $totalPriceWithVat += 50;
// }
```

## Přidání nových příplatků

Chceš přidat nový příplatek? Je to jednoduché:

### 1. Přidej do formuláře (frontend)

```html
<input type="checkbox" name="isPremium" value="1" />
```

### 2. Přidaj validaci (SubscriptionController.php)

```php
$validated = $request->validate([
    // ... existující
    'isPremium' => 'nullable|in:0,1',
]);
```

### 3. Přidej do cenotvorby

```php
if ($validated['isPremium']) {
    $totalPriceWithVat += 50; // +50 Kč za premium
}
```

**Hotovo!** Není potřeba nic měnit ve Stripe.

## Troubleshooting

### Chyba: "Product not found"

```bash
# Znovu vytvoř základní produkt
php artisan stripe:setup-products --force
```

### Chyba: "Invalid price amount"

Zkontroluj, že cena je správně převedena na haléře (× 100):

```php
'unit_amount' => (int)($price * 100), // ✅
'unit_amount' => $price, // ❌
```

### Webhook nepřijímá správnou cenu

Metadata jsou správně nastavena v `subscription_data.metadata`:

```php
'configured_price' => $price, // ✅ Viditelné ve webhook
```

## Migrace existujících předplatných

Existující předplatná s fixními Price ID **fungují dál beze změn**.

Pro nové předplatné používají automaticky dynamické ceny.

Pokud chceš migrovat staré předplatné:

1. Zruš staré předplatné
2. Vytvoř nové s dynamickou cenou
3. Přenes payment method

## Limity a poznámky

### ⚠️ Stripe Rate Limits

- Každý checkout vytváří nový Price object
- Stripe má limit ~100 requests/sekundu
- Pro běžný e-shop to není problém

### ℹ️ Price Objects v Dashboardu

- V dashboardu uvidíš mnoho Price objektů
- To je normální a očekávané chování
- Každé předplatné má svůj Price object

### ℹ️ Reporting

Pro reporting používej metadata:

- `configured_price` - skutečná cena
- `configuration` - kompletní konfigurace

## Budoucí možnosti

S dynamickými cenami můžeš snadno přidat:

- 🎁 **Slevové kupóny** (před vytvořením session)
- 📦 **Balíčky** (různé kombinace produktů)
- 🌟 **Premium features** (zlaté zrna, limitky...)
- 🎯 **Personalizované ceny** (věrnostní slevy)
- 🌍 **Multi-currency** (EUR, USD...)

Vše bez nutnosti vytvářet nové Price ID!

---

**Implementováno:** 28. října 2025
**Status:** ✅ Produkčně ready
**Testing:** Doporučeno otestovat všechny varianty před nasazením
