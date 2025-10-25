# 🚀 Stripe Integration - Setup Guide

Tento návod vás provede kompletním nastavením Stripe plateb pro Kavi e-shop.

## 📋 Co bylo implementováno

### ✅ Funkcionality

1. **Jednorázové platby** - Pro běžné objednávky produktů
2. **Stripe Subscriptions** - Pro kávové předplatné
3. **Konfigurátor support** - Dynamické předplatné podle konfigurace (2-4 balení, různé frekvence)
4. **Webhook handling** - Automatické zpracování plateb a předplatných
5. **Guest checkout** - Možnost objednat předplatné i bez účtu

### 🔧 Technické komponenty

- `StripeService` - Hlavní service pro komunikaci se Stripe API
- `PaymentController` - Handling plateb a webhooků
- `SetupStripeProducts` - Artisan command pro vytvoření produktů
- Routes pro platby a webhook endpoint
- Integrace do `SubscriptionController`

---

## 🎯 Krok 1: Získání Stripe klíčů

1. Přihlaste se na [Stripe Dashboard](https://dashboard.stripe.com)
2. Přepněte do **Test Mode** (přepínač vpravo nahoře)
3. Přejděte na: **Developers → API keys**
4. Zkopírujte:
   - **Publishable key** (začíná `pk_test_...`)
   - **Secret key** (začíná `sk_test_...`)

---

## 🔐 Krok 2: Konfigurace ENV

Přidejte následující do vašeho `.env` souboru:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_XXXXXXXXXXXXXXXXXXXXX
STRIPE_SECRET=sk_test_XXXXXXXXXXXXXXXXXXXXX
STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXXXXXXX
```

> **Poznámka**: `STRIPE_WEBHOOK_SECRET` získáte v kroku 4 po nastavení webhooku.

---

## 🛠️ Krok 3: Vytvoření Stripe produktů a cen

Spusťte Artisan command pro automatické vytvoření produktů ve Stripe:

```bash
php artisan stripe:setup-products
```

Tento příkaz:

- ✅ Vytvoří 3 produkty (2, 3, 4 balení kávy)
- ✅ Pro každý vytvoří 3 ceny (měsíční, dvouměsíční, čtvrtletní frekvence)
- ✅ Uloží Price IDs do databáze (`subscription_configs` tabulky)

**Output by měl vypadat takto:**

```
Setting up Stripe Products and Prices...

Current pricing from database:
  2 bags: 500 CZK
  3 bags: 720 CZK
  4 bags: 920 CZK

Creating product: Kavi Předplatné - 2 balení
  ✓ Product created: prod_XXXXXXXXXXXXX
  ✓ Monthly price created: price_XXXXXXXXXXXXX (500 CZK/month)
  ✓ Bi-monthly price created: price_XXXXXXXXXXXXX (500 CZK/2 months)
  ✓ Quarterly price created: price_XXXXXXXXXXXXX (500 CZK/3 months)
  ✓ Saved to database: stripe_price_id_2_bags

...

✅ Stripe setup completed successfully!
```

### ♻️ Přegenerování produktů

Pokud potřebujete vytvořit produkty znovu:

```bash
php artisan stripe:setup-products --force
```

---

## 🔔 Krok 4: Nastavení Webhook

Webhooky umožňují Stripe automaticky informovat vaši aplikaci o událostech (platby, předplatné, atd.)

### Pro lokální vývoj (Stripe CLI)

1. Nainstalujte [Stripe CLI](https://stripe.com/docs/stripe-cli)
2. Přihlaste se:
   ```bash
   stripe login
   ```
3. Naslouchejte webhookům:
   ```bash
   stripe listen --forward-to localhost:8000/webhook/stripe
   ```
4. CLI vám zobrazí webhook secret - přidejte ho do `.env`:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_xxx...
   ```

### Pro produkci

1. V Stripe Dashboard: **Developers → Webhooks → Add endpoint**
2. Zadejte URL:
   ```
   https://vase-domena.cz/webhook/stripe
   ```
3. Vyberte události:
   - ✅ `checkout.session.completed`
   - ✅ `customer.subscription.created`
   - ✅ `customer.subscription.updated`
   - ✅ `customer.subscription.deleted`
   - ✅ `invoice.payment_succeeded`
   - ✅ `invoice.payment_failed`
4. Uložte a zkopírujte **Signing secret** do `.env`

---

## 🧪 Krok 5: Testování

### Test 1: Jednorázová platba (produkty)

1. Přidejte produkty do košíku
2. Přejděte na pokladnu
3. Zvolte platbu kartou
4. Budete přesměrováni na Stripe Checkout
5. Použijte testovací kartu: `4242 4242 4242 4242`
   - Jakékoliv budoucí datum expirace
   - Jakékoliv CVC
6. Po úspěšné platbě byste měli být přesměrováni na potvrzení objednávky

### Test 2: Předplatné přes konfigurátor

1. Přejděte na `/predplatne`
2. Nakonfigurujte předplatné (množství, typ, frekvence)
3. Pokračujte na pokladnu
4. Vyplňte údaje a zvolte platbu kartou
5. Stripe Checkout otevře okno pro zadání karty
6. Použijte testovací kartu: `4242 4242 4242 4242`
7. Po úspěšné platbě se vytvoří Stripe Subscription
8. Webhook aktivuje předplatné v databázi

### Test 3: Webhook (lokální)

S běžícím `stripe listen`:

```bash
stripe trigger checkout.session.completed
stripe trigger customer.subscription.created
```

Zkontrolujte logy:

```bash
tail -f storage/logs/laravel.log
```

---

## 🔍 Verifikace

### 1. Zkontrolujte Stripe Dashboard

- **Products**: Měli byste vidět 3 produkty
- **Prices**: Každý produkt by měl mít 3 ceny
- **Customers**: Po test platbě se vytvoří zákazník
- **Subscriptions**: Po test předplatném vidíte aktivní subscription

### 2. Zkontrolujte databázi

```sql
-- Zkontrolujte Price IDs
SELECT config_key, config_value
FROM subscription_configs
WHERE config_key LIKE 'stripe_price_id%';

-- Zkontrolujte předplatné
SELECT id, user_id, stripe_subscription_id, status, configured_price
FROM subscriptions;
```

---

## 🎨 Testovací karty

| Číslo karty         | Scénář                   |
| ------------------- | ------------------------ |
| 4242 4242 4242 4242 | ✅ Úspěšná platba        |
| 4000 0000 0000 0002 | ❌ Zamítnutá karta       |
| 4000 0000 0000 9995 | ❌ Nedostatek prostředků |
| 4000 0025 0000 3155 | ⚠️ Vyžaduje 3D Secure    |

[Kompletní seznam](https://stripe.com/docs/testing)

---

## 🚨 Troubleshooting

### Webhook nefunguje

**Problém**: Webhook endpoint vrací 404 nebo CSRF error

**Řešení**:

- ✅ Zkontrolujte, že route existuje: `php artisan route:list | grep webhook`
- ✅ Ověřte CSRF exclude: `app/Http/Middleware/VerifyCsrfToken.php` obsahuje `'webhook/*'`
- ✅ Restartujte server

### Price ID not found

**Problém**: Chyba "Stripe Price ID not found for configuration"

**Řešení**:

```bash
php artisan stripe:setup-products --force
```

### Předplatné se nevytvoří po platbě

**Problém**: Platba proběhla, ale předplatné chybí v databázi

**Řešení**:

- ✅ Zkontrolujte logy: `tail -f storage/logs/laravel.log`
- ✅ Ověřte, že webhook endpoint je dostupný
- ✅ V Stripe Dashboard → Webhooks zkontrolujte failed requests

### Test mode problémy

**Řešení**:

- ✅ Ujistěte se, že používáte `pk_test_` a `sk_test_` klíče (ne production)
- ✅ V Stripe Dashboard přepněte do Test Mode

---

## 📊 Monitoring v produkci

Po nasazení do produkce:

1. **Přepněte na Live keys**:

   ```env
   STRIPE_KEY=pk_live_XXXXXXXXXXXXXXXXXXXXX
   STRIPE_SECRET=sk_live_XXXXXXXXXXXXXXXXXXXXX
   ```

2. **Vytvořte produkty v Live mode**:

   ```bash
   php artisan stripe:setup-products
   ```

3. **Nastavte webhook pro produkční URL**

4. **Monitorujte**:
   - Stripe Dashboard → Payments
   - Stripe Dashboard → Subscriptions
   - Laravel logs: `storage/logs/laravel.log`

---

## 🎉 Hotovo!

Stripe integrace je nyní plně funkční. Můžete:

- ✅ Přijímat platby za produkty
- ✅ Vytvářet a spravovat předplatná
- ✅ Automaticky zpracovávat platby přes webhooky
- ✅ Podporovat různé konfigurace předplatného

---

## 📞 Potřebujete pomoc?

- [Stripe Documentation](https://stripe.com/docs)
- [Stripe Support](https://support.stripe.com/)
- [Laravel Cashier](https://laravel.com/docs/billing) (pro pokročilé případy)

---

## 🔜 Další kroky (volitelné)

1. **Customer Portal** - Umožnit zákazníkům spravovat karty a předplatné
2. **Email notifikace** - Odesílat emaily při platbách a problémech
3. **Invoice PDF** - Generovat faktury pro objednávky
4. **Refunds** - Implementovat možnost vrácení peněz
5. **Trial periods** - Zkušební období pro předplatné
