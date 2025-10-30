# 🚀 QUICKSTART: Správa platebních metod

## ⚡ Rychlá aktivace (5 minut)

### Krok 1: Aktivovat Stripe Customer Portal

1. **Otevřete Stripe Dashboard:**

   ```
   https://dashboard.stripe.com/test/settings/billing/portal
   ```

2. **Klikněte "Activate test link"**

3. **Nastavte oprávnění:**

   - ✅ **Payment methods** → Enable
   - ✅ **Update payment method** → Enable
   - ❌ **Cancel subscriptions** → Disable (máte vlastní)
   - ❌ **Pause subscriptions** → Disable (máte vlastní)

4. **Vyplňte Business information:**

   ```
   Business name: Kavi Coffee
   Support email: info@kavi.cz
   Privacy policy: https://vase-domena.cz/ochrana-osobnich-udaju
   Terms of service: https://vase-domena.cz/obchodni-podminky
   ```

5. **Uložte (Save)**

✅ **Hotovo!** Portal je aktivní.

---

## 🧪 Test

### Test 1: Uživatel s platební metodou

```bash
1. Přihlaste se jako uživatel s předplatným
2. Jděte na: /dashboard/profil
3. Měli byste vidět sekci "Platební metody" s vaší kartou
4. Klikněte "Změnit platební metodu"
5. Budete na Stripe portálu
6. Přidejte/změňte kartu (testovací: 4242 4242 4242 4242)
7. Klikněte "Done" → vrátíte se na profil
```

### Test 2: Uživatel bez platební metody

```bash
1. Vytvořte nového uživatele (bez předplatného)
2. Jděte na: /dashboard/profil
3. Měli byste vidět: "Zatím nemáte nastavenou žádnou platební metodu"
4. Vytvořte předplatné
5. Refreshněte profil → měla by se zobrazit karta
```

---

## 🎨 Co uživatel vidí

### S platební metodou:

```
┌─────────────────────────────────┐
│  Platební metody                │
├─────────────────────────────────┤
│  💳 Visa •••• 4242              │
│     Vyprší 12/2025              │
│                                 │
│  [Změnit platební metodu]      │
└─────────────────────────────────┘
```

### Bez platební metody:

```
┌─────────────────────────────────┐
│  Platební metody                │
├─────────────────────────────────┤
│  ℹ️  Zatím nemáte nastavenou    │
│     žádnou platební metodu.     │
│     Při první platbě bude karta│
│     automaticky uložena.        │
└─────────────────────────────────┘
```

---

## 🔍 Troubleshooting

### ❌ Chyba: "Nepodařilo se otevřít správu"

→ **Aktivujte Customer Portal** ve Stripe Dashboard

### ❌ Platební metoda se nezobrazuje

→ Zkontrolujte v Stripe Dashboard, že customer má payment method

### ❌ Portal je v angličtině

→ Přidejte `'locale' => 'cs'` v `StripeService::createCustomerPortalSession()`

---

## 📱 Pro produkci

Před spuštěním na LIVE:

1. **Aktivujte portal v LIVE mode:**

   ```
   https://dashboard.stripe.com/settings/billing/portal
   ```

2. **Ověřte .env:**

   ```
   STRIPE_KEY=pk_live_...
   STRIPE_SECRET=sk_live_...
   ```

3. **Test s reálnou kartou**

---

## 📚 Více informací

→ Viz `PAYMENT_METHODS_IMPLEMENTATION.md` pro kompletní dokumentaci

---

**Ready to go!** 🎉
