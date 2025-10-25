# ⚡ Stripe Integration - Quick Start

Rychlý návod na zprovoznění Stripe plateb během 5 minut.

---

## 🚀 Krok za krokem

### 1️⃣ Přidat Stripe klíče do `.env`

```bash
# Získejte klíče z: https://dashboard.stripe.com/test/apikeys
nano .env
```

Přidejte:

```env
STRIPE_KEY=pk_test_VAŠE_PUBLISHABLE_KEY
STRIPE_SECRET=sk_test_VAŠE_SECRET_KEY
STRIPE_WEBHOOK_SECRET=    # Necháme zatím prázdné
```

---

### 2️⃣ Vytvořit Stripe produkty

```bash
php artisan stripe:setup-products
```

**Expected output:**

```
✅ Stripe setup completed successfully!
```

---

### 3️⃣ Nastavit webhook (lokální testování)

**Instalace Stripe CLI** (macOS):

```bash
brew install stripe/stripe-cli/stripe
stripe login
```

**Spustit webhook listener**:

```bash
stripe listen --forward-to localhost:8000/webhook/stripe
```

**Zkopírovat webhook secret** z outputu a přidat do `.env`:

```env
STRIPE_WEBHOOK_SECRET=whsec_xxx...
```

---

### 4️⃣ Test!

#### Test předplatného:

1. Otevřete: `http://localhost:8000/predplatne`
2. Nakonfigurujte předplatné
3. Pokračujte na checkout
4. Vyplňte údaje a zvolte **Platba kartou**
5. Stripe otevře platební formulář
6. Použijte test kartu: **4242 4242 4242 4242**
   - Expirace: jakékoliv budoucí datum (např. 12/25)
   - CVC: jakékoliv 3 čísla (např. 123)
7. Po platbě byste měli vidět aktivní předplatné v dashboardu

#### Test jednorázové platby:

1. Přidejte produkt do košíku
2. Přejděte na pokladnu
3. Zvolte platbu kartou
4. Použijte stejnou test kartu
5. Po platbě uvidíte potvrzení objednávky

---

## ✅ Verifikace

### Zkontrolujte Stripe Dashboard:

- **Products** (3 produkty s 3 cenami každý) ✓
- **Customers** (váš test zákazník) ✓
- **Subscriptions** (aktivní předplatné) ✓
- **Payments** (úspěšná platba) ✓

### Zkontrolujte databázi:

```bash
php artisan tinker
```

```php
// Zkontrolovat Price IDs
\App\Models\SubscriptionConfig::where('config_key', 'like', 'stripe_price_id%')->get();

// Zkontrolovat předplatné
\App\Models\Subscription::with('user')->get();
```

---

## 🎨 Testovací karty

| Karta               | Výsledek                 |
| ------------------- | ------------------------ |
| 4242 4242 4242 4242 | ✅ Úspěch                |
| 4000 0000 0000 0002 | ❌ Zamítnuto             |
| 4000 0000 0000 9995 | ❌ Nedostatek prostředků |

---

## 🐛 Problém?

### Webhook nefunguje

```bash
# Restartujte listener
stripe listen --forward-to localhost:8000/webhook/stripe

# Zkontrolujte routes
php artisan route:list | grep webhook
```

### Price ID not found

```bash
# Znovu vytvořte produkty
php artisan stripe:setup-products --force
```

### Logy

```bash
tail -f storage/logs/laravel.log
```

---

## 📚 Další kroky

Po úspěšném testu:

1. **Detailní dokumentace**: `STRIPE_SETUP_GUIDE.md`
2. **Shrnutí implementace**: `STRIPE_IMPLEMENTATION_SUMMARY.md`
3. **Produkce setup**: Přepnout na Live keys a nastavit production webhook

---

## 🎉 Hotovo!

Stripe funguje! Nyní můžete:

- ✅ Přijímat platby za produkty
- ✅ Vytvářet měsíční předplatná
- ✅ Automaticky zpracovávat obnovení předplatného

**Další otázky?** Podívejte se na `STRIPE_SETUP_GUIDE.md`
