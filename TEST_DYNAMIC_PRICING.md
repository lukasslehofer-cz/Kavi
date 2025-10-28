# Test Checklist: Dynamické ceny ve Stripe

## Pre-test Setup

### 1. Spusť setup command

```bash
php artisan stripe:setup-products
```

**Očekávaný výstup:**

```
Setting up Stripe Base Product for Dynamic Pricing...

Creating base subscription product...
  ✓ Product created: prod_xxxxx
  ✓ Saved to database: stripe_base_product_id = prod_xxxxx

✅ Stripe setup completed successfully!

ℹ️  Dynamic Pricing Enabled:
  - Prices are now calculated dynamically based on configuration
  - Includes support for decaf surcharges and future add-ons
  - No need to create fixed Price IDs for each variant
```

### 2. Ověř v databázi

```sql
SELECT * FROM subscription_configs WHERE key = 'stripe_base_product_id';
```

Měl by existovat záznam s Product ID.

### 3. Ověř ve Stripe Dashboard

- Přejdi na: https://dashboard.stripe.com/test/products
- Najdi produkt: "Kavi Kávové Předplatné"
- Zkontroluj metadata: `type: configurable_subscription`

---

## Test Scenarios

### ✅ Scenario 1: Základní předplatné (bez příplatků)

**Konfigurace:**

```
- Množství: 2 balení
- Typ: Espresso
- Decaf: NE
- Frekvence: Každý měsíc
```

**Kroky:**

1. Otevři homepage: `http://localhost`
2. Vyber 2 balení → Espresso → Každý měsíc
3. Nezaškrtávej "Chci decaf variantu"
4. Klikni "Pokračovat k objednávce"

**Očekávaný výsledek:**

- ✅ Cena v košíku: **500 Kč/měsíc**
- ✅ Přesměrování na Stripe Checkout
- ✅ V Stripe Checkout vidíš: "500 Kč/měsíc"
- ✅ Po dokončení platby se vytvoří předplatné s cenou 500 Kč

**Verifikace v Stripe:**

```
Product: Kavi Kávové Předplatné
Price: 500 CZK/month
Metadata → configured_price: 500
```

---

### ✅ Scenario 2: Předplatné s decaf příplatkem (+100 Kč)

**Konfigurace:**

```
- Množství: 2 balení
- Typ: Espresso
- Decaf: ANO ✓
- Frekvence: Každý měsíc
```

**Kroky:**

1. Vyber 2 balení → Espresso → Každý měsíc
2. **Zaškrtni** "Chci decaf variantu"
3. Klikni "Pokračovat k objednávce"

**Očekávaný výsledek:**

- ✅ Cena v košíku: **600 Kč/měsíc** (500 + 100 decaf)
- ✅ V Stripe Checkout vidíš: "600 Kč/měsíc"
- ✅ Po dokončení platby se účtuje **600 Kč**, ne 500 Kč!

**Verifikace v Stripe:**

```
Product: Kavi Kávové Předplatné
Price: 600 CZK/month  ← DŮLEŽITÉ! Musí být 600, ne 500
Metadata → configured_price: 600
Metadata → configuration → isDecaf: true
```

---

### ✅ Scenario 3: Různé množství (3 balení + decaf)

**Konfigurace:**

```
- Množství: 3 balení
- Typ: Filter
- Decaf: ANO ✓
- Frekvence: Každý měsíc
```

**Očekávaný výsledek:**

- ✅ Cena: **820 Kč/měsíc** (720 + 100 decaf)

**Verifikace v Stripe:**

```
Price: 820 CZK/month
Metadata → configured_price: 820
```

---

### ✅ Scenario 4: Různá frekvence (každé 2 měsíce)

**Konfigurace:**

```
- Množství: 2 balení
- Typ: Espresso
- Decaf: NE
- Frekvence: Jednou za 2 měsíce
```

**Očekávaný výsledek:**

- ✅ Cena: **500 Kč každé 2 měsíce**
- ✅ Billing interval: `month` s `interval_count: 2`

**Verifikace v Stripe:**

```
Price: 500 CZK
Interval: Every 2 months
```

---

### ✅ Scenario 5: Kombinace (mix kávy)

**Konfigurace:**

```
- Množství: 3 balení
- Typ: Kombinace
  - 2× Espresso
  - 1× Filter
- Frekvence: Každý měsíc
```

**Očekávaný výsledek:**

- ✅ Cena: **720 Kč/měsíc**

**Verifikace v Stripe:**

```
Price: 720 CZK/month
Metadata → configuration → type: "mix"
Metadata → configuration → mix: {espresso: 2, filter: 1, ...}
```

---

## Critical Tests (Must Pass! 🚨)

### 🔴 CRITICAL 1: Decaf příplatek se účtuje

**Test:**

1. Vytvoř předplatné BEZ decaf → cena 500 Kč
2. Vytvoř předplatné S decaf → cena 600 Kč

**Výsledek:**

```
❌ FAIL: Obě předplatná mají 500 Kč
✅ PASS: První má 500 Kč, druhé má 600 Kč
```

### 🔴 CRITICAL 2: Různé ceny vytvářejí různé Price objekty

**Test:**

1. Vytvoř 3 různá předplatná s různými cenami
2. V Stripe Dashboard → Products → Kavi Kávové Předplatné → Prices

**Výsledek:**

```
✅ PASS: Vidíš 3 různé Price objekty s různými cenami
❌ FAIL: Vidíš pouze 1 Price objekt nebo ceny jsou stejné
```

### 🔴 CRITICAL 3: Metadata obsahují správnou cenu

**Test:**

1. Vytvoř předplatné s decaf (600 Kč)
2. V Stripe Dashboard → Subscriptions → vyber předplatné → Metadata

**Výsledek:**

```
✅ PASS: configured_price = 600
❌ FAIL: configured_price = 500 nebo chybí
```

---

## Webhook Test

### Test invoice.payment_succeeded

**Setup:**

1. Vytvoř testovací předplatné
2. V Stripe Dashboard → Events
3. Najdi event `invoice.payment_succeeded`
4. Zkontroluj webhook payload

**Verifikace:**

```json
{
  "type": "invoice.payment_succeeded",
  "data": {
    "object": {
      "amount_paid": 60000, // 600 Kč v haléřích
      "subscription": "sub_xxx",
      "metadata": {
        "configured_price": "600"
      }
    }
  }
}
```

---

## Regression Tests

### ✅ Existující funkce stále fungují

**Test:**

1. **Jednorázové objednávky** (ne předplatné)

   - Přidej produkty do košíku
   - Proveď checkout
   - ✅ Platba funguje normálně

2. **Predefined Subscription Plans** (pokud existují)
   - Vyber pevný plán (např. "Espresso BOX")
   - ✅ Stále používá fixní Price ID
   - ✅ Funguje beze změn

---

## Performance Test

### Test: Rychlost vytvoření checkout session

**Benchmark:**

```bash
# Před implementací
Average response time: ~500ms

# Po implementaci
Average response time: ~800ms (akceptovatelné)
```

**Důvod:** Vytvoření Price objektu přidává ~200-300ms latenci.

**Akceptovatelné:** Ano, 800ms je stále rychlé pro checkout.

---

## Rollback Plan

Pokud něco selže, můžeš vrátit změny:

### 1. Git revert

```bash
git diff HEAD app/Services/StripeService.php
git checkout HEAD -- app/Services/StripeService.php
```

### 2. Použít starý kód s fixními Price ID

Odkomentuj v `StripeService.php`:

```php
// Starý způsob (fallback)
'line_items' => [[
    'price' => $this->getStripePriceIdForConfiguration($configuration),
    'quantity' => 1,
]]
```

Ale **nebude fungovat decaf příplatek!**

---

## Post-test Checklist

Po úspěšném otestování:

- [ ] Všechny CRITICAL testy prošly
- [ ] Decaf příplatek se správně účtuje
- [ ] Různé ceny vytvářejí různé Price objekty
- [ ] Metadata obsahují správné hodnoty
- [ ] Webhook správně přijímá události
- [ ] Existující funkce stále fungují
- [ ] Response time je přijatelný (<1s)

---

## Známé Limity

### ⚠️ Nelze změnit cenu existujícího předplatného

Stripe neumožňuje změnit cenu aktivního předplatného.

**Řešení:** Musíš vytvořit nové předplatné s novou cenou.

### ℹ️ Mnoho Price objektů v Dashboardu

Každé předplatné má svůj Price objekt.

**Je to OK:** To je normální chování s dynamickými cenami.

---

**Vytvořeno:** 28. října 2025
**Status:** Ready for testing


