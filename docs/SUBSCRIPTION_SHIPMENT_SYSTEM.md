# Systém předplatného a rozesílek

Tento dokument popisuje architekturu a logiku systému předplatného v aplikaci Kavi.

## Přehled architektury

Systém rozesílek je centralizován kolem tabulky `subscription_shipments`, která slouží jako **jediný zdroj pravdy** (single source of truth) pro všechny informace o minulých, aktuálních i budoucích rozesílkách.

### Tok dat

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              PLATEBNÍ TOK                                    │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  [Uživatel platí] ──► [StripeService] ──► [SubscriptionPayment] ──┐        │
│                                                                    │        │
│                         linkPaymentToShipment() ◄─────────────────┘        │
│                                  │                                          │
│                                  ▼                                          │
│                     [SubscriptionShipment: pending]                         │
│                                                                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                              TOK ROZESÍLKY                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  [Admin: Odeslat do Packeta] ──► markAsShipped() ──┐                       │
│                                                     │                       │
│                                                     ▼                       │
│                            [SubscriptionShipment: sent]                     │
│                                                     │                       │
│                     ensurePendingShipmentExists() ◄─┘                       │
│                                  │                                          │
│                                  ▼                                          │
│                     [SubscriptionShipment: pending] (další měsíc)           │
│                                                                             │
├─────────────────────────────────────────────────────────────────────────────┤
│                              TOK PAUZY                                       │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  [Uživatel: Pozastavit] ──► pauseSubscription() ──┐                        │
│                                                    │                        │
│            ┌───────────────────────────────────────┘                        │
│            │                                                                │
│            ├──► [Zachovat zaplacené pending rozesílky]                      │
│            │                                                                │
│            ├──► [Vytvořit skipped záznamy pro období pauzy]                 │
│            │                                                                │
│            └──► [Vytvořit pending pro první rozesílku po pauze]             │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## Klíčové koncepty

### 1. Billing Date vs Shipment Date

| Termín | Význam | Výchozí den |
|--------|--------|-------------|
| **Billing Date** | Den stržení platby za předplatné | 15. den měsíce |
| **Shipment Date** | Den odeslání zásilky | 20. den měsíce |

**Důležité pravidlo**: Pokud je `billing_date < paused_until_date`, celý měsíc je přeskočen (skipped).

Tabulka `shipment_schedules` umožňuje nastavit různé datumy pro různé měsíce:

```php
// Získání datumů pro konkrétní měsíc
$schedule = ShipmentSchedule::getForMonth(2026, 1);
$billingDate = $schedule->billing_date;   // např. 15.1.2026
$shipmentDate = $schedule->shipment_date; // např. 20.1.2026
```

### 2. Statusy rozesílek

| Status | Význam |
|--------|--------|
| `pending` | Naplánovaná rozesílka, čeká na odeslání |
| `sent` | Odeslána přes Packeta |
| `delivered` | Doručena (aktualizováno z Packeta) |
| `skipped` | Přeskočena kvůli pauze |
| `cancelled` | Zrušena |

### 3. Pokrytí platbou

Platba pokrývá rozesílku, pokud:

```php
period_start <= billing_date < period_end
```

**Pozor**: `period_end` je **EXKLUZIVNÍ** - použít `>`, ne `>=`:

```php
// SPRÁVNĚ
->whereDate('period_end', '>', $billingDate)

// CHYBNĚ
->whereDate('period_end', '>=', $billingDate)
```

---

## Tabulka `subscription_shipments`

### Schema

```sql
CREATE TABLE subscription_shipments (
    id BIGINT PRIMARY KEY,
    subscription_id BIGINT NOT NULL,
    shipment_schedule_id BIGINT NULL,
    subscription_payment_id BIGINT NULL,
    shipment_date DATE NOT NULL,
    status ENUM('pending','sent','delivered','skipped','cancelled'),
    packeta_packet_id VARCHAR(255) NULL,
    packeta_tracking_url VARCHAR(255) NULL,
    sent_at TIMESTAMP NULL,
    notes TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Vztahy

```php
// V modelu SubscriptionShipment
public function subscription() { return $this->belongsTo(Subscription::class); }
public function payment() { return $this->belongsTo(SubscriptionPayment::class, 'subscription_payment_id'); }
public function schedule() { return $this->belongsTo(ShipmentSchedule::class, 'shipment_schedule_id'); }

// V modelu Subscription
public function shipments() { return $this->hasMany(SubscriptionShipment::class)->orderBy('shipment_date'); }
```

---

## Centrální služba: `SubscriptionShipmentService`

Všechny operace s rozesílkami procházejí přes tuto službu.

### Hlavní metody

#### Získání informací

```php
$service = app(SubscriptionShipmentService::class);

// Kompletní info o předplatném
$info = $service->getShipmentInfo($subscription);
$info->lastSentDate();        // Datum poslední odeslané
$info->nextShipmentDate();    // Datum další naplánované
$info->isPaused();            // Je pozastaveno?
$info->isNextShipmentPaid();  // Je další rozesílka zaplacená?

// Jednotlivé údaje
$lastSent = $service->getLastSentShipment($subscription);
$nextPending = $service->getNextShipment($subscription);
$nextDate = $service->getNextShipmentDate($subscription);

// Pro modal pauzy - první NEzaplacená rozesílka
$firstUnpaid = $service->getFirstUnpaidShipmentDate($subscription);
```

#### Správa předplatného

```php
// Pozastavení (respektuje zaplacené rozesílky)
$service->pauseSubscription($subscription, iterations: 2, reason: 'vacation');

// Obnovení (správně počítá další rozesílku)
$service->resumeSubscription($subscription);

// Zrušení
$service->cancelSubscription($subscription);
```

#### Zpracování rozesílek

```php
// Označit jako odeslanou + vytvořit další pending
$service->markAsShipped($shipment, $packetaId, $trackingUrl);

// Propojit platbu s rozesílkou
$service->linkPaymentToShipment($payment, $subscription);

// Zajistit existenci pending záznamu
$service->ensurePendingShipmentExists($subscription);
```

---

## Scénáře

### 1. Měsíční předplatné

```
Leden:   pending → [platba 15.1.] → [odeslání 20.1.] → sent
Únor:    pending (vytvořen automaticky po odeslání ledna)
Březen:  ...
```

### 2. Dvouměsíční předplatné (frequency_months = 2)

```
Leden:   pending → [platba 15.1.] → [odeslání 20.1.] → sent
Březen:  pending (vytvořen automaticky, přeskočen únor)
Květen:  ...
```

### 3. Tříměsíční předplatné (frequency_months = 3)

```
Leden:   pending → [platba 15.1.] → [odeslání 20.1.] → sent
Duben:   pending (vytvořen automaticky, přeskočeny únor a březen)
Červenec: ...
```

### 4. Pauza na 2 rozesílky (měsíční předplatné)

**Aktuální stav**: Leden zaplacen a čeká na odeslání

```
Leden:   pending (zaplaceno) → zůstane pending, ODEŠLE SE!
Únor:    skipped (pauza začíná od první nezaplacené)
Březen:  skipped
Duben:   pending (první po pauze)
```

### 5. Předčasné obnovení z pauzy

**Původní pauza**: Únor-Duben skipped, Květen pending

**Obnovení v březnu**:
```
Únor:    skipped (již proběhlo)
Březen:  DELETE (smazán, již minulost)
Duben:   DELETE (smazán, byl skipped)
Květen:  DELETE (smazán starý pending)
         → ensurePendingShipmentExists() → nový pending na Duben
```

---

## Integrace s platbami (StripeService)

### Při první platbě (checkout)

```php
// V handlePaymentSuccess() nebo handleCustomBillingSubscriptionPayment()
$subscription = Subscription::create([...]);
$payment = SubscriptionPayment::create([...]);

// Vytvoří/propojí pending shipment
$shipmentService->linkPaymentToShipment($payment, $subscription);
```

### Při opakované platbě (cron)

```php
// V chargeSubscriptionPayment()
$payment = SubscriptionPayment::create([...]);

// Propojí s existujícím pending shipment
$shipmentService->linkPaymentToShipment($payment, $subscription);
```

### Po odeslání

```php
// V Admin\SubscriptionController::sendToPacketa()
$shipmentService->markAsShipped($shipment, $packetId, $trackingUrl);
// Automaticky vytvoří další pending pro příští měsíc
```

---

## Migrace a nasazení

### Nové tabulky/sloupce

1. `subscription_shipments.status` - přidán status `skipped`
2. `subscription_shipments.shipment_schedule_id` - FK na `shipment_schedules`

### Datová migrace

Migrace `2026_01_02_100001_populate_subscription_shipments_from_existing_data.php`:

1. Pro každé předplatné s `last_shipment_date` vytvoří `sent` záznamy
2. Pro aktivní předplatné vytvoří `pending` záznam
3. Pro pozastavené vytvoří `skipped` + `pending` po pauze

### Rollback

```bash
php artisan migrate:rollback --step=2
```

---

## Debugging

### Logování

Všechny operace jsou logovány:

```php
\Log::info('Subscription paused', [
    'subscription_id' => $subscription->id,
    'iterations' => $iterations,
    'paid_shipments_preserved' => [...],
    'skipped_dates' => [...],
    'resume_date' => '...',
]);
```

### Kontrola stavu

```php
// V tinkeru
$sub = Subscription::find(123);
$sub->shipments->each(fn($s) => dump($s->shipment_date->format('d.m.Y') . ' - ' . $s->status));
```

### Opravné skripty

Pro jednorázové opravy dat existují skripty v `scripts/`:

- `fix_paused_shipments.php` - oprava špatně nastavených pauz
- `fix_payment_shipment_links.php` - oprava vazeb platba-rozesílka

---

## Časté problémy

### 1. Rozesílka se zobrazuje jako nezaplacená

**Příčina**: `period_end` platby je rovno `billing_date` (mělo by být >)

**Řešení**: Opravit logiku v `findPaymentForShipment()` - použít `>` místo `>=`

### 2. Pauza začíná od již zaplacené rozesílky

**Příčina**: `pauseSubscription()` nekontroluje existující platby

**Řešení**: Metoda nyní hledá `pending` záznamy s `subscription_payment_id IS NOT NULL` a ty přeskakuje

### 3. Po obnovení z pauzy špatné datum další rozesílky

**Příčina**: `resumeSubscription()` nemazala budoucí `skipped` záznamy

**Řešení**: Metoda nyní smaže všechny budoucí `skipped` a `pending` a vytvoří nový správný `pending`

---

## Závislosti

- `App\Models\Subscription`
- `App\Models\SubscriptionShipment`
- `App\Models\SubscriptionPayment`
- `App\Models\ShipmentSchedule`
- `App\Services\SubscriptionShipmentService`
- `App\Services\StripeService`

