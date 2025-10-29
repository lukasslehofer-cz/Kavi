# Implementace systému pro zpracování neuhrazených plateb předplatného

## 📋 Přehled změn

Byl implementován kompletní systém pro zpracování selhání plateb u předplatných včetně notifikací, sledování a možnosti manuální platby.

## 🎯 Implementované funkce

### 1. **Nový stav předplatného: "unpaid"**

- Přidán nový status `unpaid` pro předplatná s neuhrazenou platbou
- Status se automaticky nastaví při selhání platby přes Stripe webhook
- Předplatná v tomto stavu nebudou automaticky zasílána (filtrováno v `SubscriptionHelper`)

### 2. **Sledování selhání plateb**

- **Nová pole v databázi (`subscriptions` tabulka):**
  - `payment_failure_count` - počet pokusů o platbu
  - `last_payment_failure_at` - timestamp posledního selhání
  - `last_payment_failure_reason` - důvod selhání platby
  - `pending_invoice_id` - Stripe invoice ID čekající na platbu
  - `pending_invoice_amount` - částka k úhradě

### 3. **Automatické odeslání notifikačního emailu**

- Email se automaticky posílá při selhání platby
- Obsahuje:
  - ⚠️ Upozornění na problém s platbou
  - Číslo předplatného
  - Důvod selhání (z Stripe)
  - Možné příčiny (nedostatek prostředků, expirovaná karta, atd.)
  - Grace period (7 dní na opravu)
  - Instrukce jak problém vyřešit
  - Kontaktní údaje na podporu
- Email template: `resources/views/emails/subscription-payment-failed.blade.php`

### 4. **Správné mapování Stripe statusů**

- `past_due` → `unpaid` (místo původního `active`)
- `unpaid` → `unpaid` (místo původního `paused`)
- Status `unpaid` se automaticky vyčistí při úspěšné platbě

### 5. **Manuální platba přes Stripe Checkout**

- **Nová metoda:** `StripeService::createInvoicePaymentSession()`

  - Vytvoří Stripe Checkout session pro zaplacení konkrétní faktury
  - Zkontroluje, zda faktura stále existuje a není zaplacená
  - Vrací URL pro redirect na platební bránu

- **Nový endpoint:** `POST /dashboard/predplatne/{subscription}/zaplatit`

  - Dostupný pouze pro přihlášené uživatele
  - Ověří oprávnění (subscription musí patřit uživateli)
  - Přesměruje na Stripe Checkout

- **Zpracování webhook:**
  - Upraveno `handlePaymentSuccess()` pro detekci manuálních plateb
  - Automaticky zaplatí fakturu ve Stripe po úspěšné platbě
  - Další zpracování probíhá standardně přes `invoice.payment_succeeded` webhook

### 6. **Uživatelský dashboard - zvýraznění problému**

- **Status badge:** Červený badge "⚠️ Neuhrazená platba"
- **Warning box:**
  - Červený alert s detaily o selhání platby
  - Datum a důvod selhání
  - Částka k úhradě
  - Výrazný **"Zaplatit nyní"** button
  - Info o bezpečné platbě kartou přes Stripe
- **Blokace:** Jasná informace, že další box nepřijde, dokud nebude platba uhrazena

### 7. **Admin sekce - monitoring a správa**

**Index view (`admin/subscriptions/index.blade.php`):**

- Nová statistika v horní části: **"⚠️ Neuhrazeno"**
  - Zvýrazněná červeným rámečkem
  - Ring efekt pokud existují neuhrazená předplatná
- Filtr: Přidána možnost filtrovat podle `unpaid` statusu
- Tabulka:
  - Řádky s `unpaid` statusem zvýrazněny červeným pozadím
  - Červený border-left pro lepší viditelnost
  - Badge zobrazuje počet pokusů o platbu

**Detail view (`admin/subscriptions/show.blade.php`):**

- Přidán `unpaid` status do selectu pro změnu stavu
- Status badge: **"⚠️ Neuhrazeno"**
- **Warning box s detaily:**
  - Částka k úhradě
  - Počet pokusů o platbu
  - Datum posledního pokusu
  - Důvod selhání
  - Stripe invoice ID

## 📁 Upravené soubory

### Database

- ✅ `database/migrations/2025_10_29_165106_add_payment_failure_tracking_to_subscriptions.php`

### Models

- ✅ `app/Models/Subscription.php`
  - Nové fillable fields
  - Nové casts
  - Nové helper metody: `isUnpaid()`, `hasPaymentIssue()`

### Services

- ✅ `app/Services/StripeService.php`
  - `handleInvoicePaymentFailed()` - kompletní implementace
  - `handleInvoicePaymentSucceeded()` - vyčištění failure tracking
  - `handleSubscriptionUpdated()` - správné mapování statusů
  - `handlePaymentSuccess()` - podpora manuálních plateb
  - `createInvoicePaymentSession()` - NOVÁ metoda

### Controllers

- ✅ `app/Http/Controllers/SubscriptionController.php`
  - `payInvoice()` - NOVÁ metoda
- ✅ `app/Http/Controllers/Admin/SubscriptionController.php`
  - Přidána `unpaid` statistika
  - Aktualizovaná validace statusů

### Routes

- ✅ `routes/web.php`
  - Nový route: `POST /dashboard/predplatne/{subscription}/zaplatit`

### Views

**User Dashboard:**

- ✅ `resources/views/dashboard/subscription.blade.php`
  - Status badge pro `unpaid`
  - Warning box s detaily
  - Button "Zaplatit nyní"

**Admin:**

- ✅ `resources/views/admin/subscriptions/index.blade.php`

  - Statistika `unpaid` s zvýrazněním
  - Filtr pro `unpaid` status
  - Zvýraznění řádků v tabulce
  - Badge s počtem pokusů

- ✅ `resources/views/admin/subscriptions/show.blade.php`
  - Status select s `unpaid`
  - Status badge
  - Warning box s detaily

**Email:**

- ✅ `resources/views/emails/subscription-payment-failed.blade.php` (již existoval)

## 🔄 Workflow při selhání platby

1. **Stripe pokusí o platbu** (recurring billing)
2. **Platba selže** → Stripe pošle webhook `invoice.payment_failed`
3. **Webhook zpracování:**
   - Najde předplatné podle Stripe subscription ID
   - Nastaví status na `unpaid`
   - Zvýší `payment_failure_count`
   - Uloží důvod selhání a částku
   - Uloží pending invoice ID
4. **Odeslání emailu** zákazníkovi s detaily
5. **Zákazník vidí** v dashboardu červený alert s možností zaplatit
6. **Zákazník klikne** na "Zaplatit nyní"
7. **Přesměrování** na Stripe Checkout
8. **Po úspěšné platbě:**
   - Webhook `checkout.session.completed` → zaplatí fakturu
   - Webhook `invoice.payment_succeeded` → nastaví status `active` a vyčistí failure tracking
9. **Předplatné je opět aktivní** a další zásilka proběhne standardně

## 🔒 Bezpečnost

- ✅ Manuální platba dostupná pouze pro vlastníka předplatného
- ✅ Ověření, že předplatné má status `unpaid` a pending invoice
- ✅ Kontrola ve Stripe, zda faktura stále existuje a není zaplacená
- ✅ Všechny platby probíhají přes Stripe Checkout (PCI compliant)

## 📊 Automatická filtrace v rozesílce

- ✅ `SubscriptionHelper::shouldShipInNextBatch()` automaticky filtruje předplatná s jakýmkoliv jiným statusem než `active` nebo `paused`
- ✅ Předplatná se statusem `unpaid` **nebudou** zahrnuta do rozesílky
- ✅ Po uhrazení platby a změně na `active` se automaticky zařadí do další rozesílky

## 🎨 UI/UX vylepšení

### Uživatelský dashboard:

- 🔴 Červený alert s ikonou varování
- 📊 Přehledné zobrazení částky k úhradě
- 💳 Výrazný CTA button "Zaplatit nyní"
- ℹ️ Informace o bezpečné platbě

### Admin panel:

- 📈 Statistika s vizuálním zvýrazněním (ring efekt)
- 🔍 Snadná filtrace problematických předplatných
- 🎯 Zvýrazněné řádky v tabulce
- 📝 Detailní informace v detailu předplatného

## 🧪 Testování

Pro testování můžete použít:

```bash
# Odeslat testovací email
php artisan tinker

$subscription = Subscription::first();
Mail::to('test@example.com')->send(new \App\Mail\SubscriptionPaymentFailed($subscription, 'Test důvod'));
```

Pro testování selhání platby ve Stripe:

1. Použijte testovací kartu: `4000000000000341` (Attach succeeds, charge fails)
2. Nebo ručně nastavte status: `$subscription->update(['status' => 'unpaid', 'pending_invoice_amount' => 500]);`

## 📌 Poznámky

### Stripe Smart Retries

- Stripe automaticky zkouší platbu opakovat podle svého algoritmu
- První pokus obvykle do 24 hodin
- Může poslat vlastní notifikační emaily (pokud jsou povolené v Stripe dashboard)
- Naše implementace funguje souběžně s tímto systémem

### Grace Period

- V email template je zmíněno 7 dní grace period
- Tato doba není zatím enforced v kódu (předplatné zůstane `unpaid` do manuální platby)
- Pokud chcete automaticky zrušit po X dnech, je potřeba přidat CRON job

### Budoucí vylepšení

- [ ] CRON job pro automatické zrušení po X dnech neplacení
- [ ] Dashboard notifikace pro admin při selhání platby
- [ ] Statistiky počtu selhaných plateb za období
- [ ] Export neuhrazených předplatných do CSV
- [ ] Možnost poslat reminder email ručně z admin panelu

## ✅ Závěr

Systém je plně funkční a připravený na produkci. Všechny komponenty jsou implementovány podle požadavků:

- ✅ Automatický email při selhání
- ✅ Status "unpaid" místo "active" nebo "paused"
- ✅ Zvýraznění v admin i user dashboardu
- ✅ Možnost manuální platby
- ✅ Automatická obnova předplatného po úhradě
- ✅ Blokace rozesílky pro neuhrazená předplatná
