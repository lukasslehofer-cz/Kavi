# Fakturoid API Integrace - Dokumentace

## Přehled

Tato integrace automaticky vytváří faktury ve Fakturoidu po úspěšné platbě přes Stripe a ukládá PDF faktury pro stažení zákazníkům v jejich dashboardu.

## Co integrace dělá

1. **Při úspěšné Stripe platbě:**

   - Automaticky vytvoří fakturu ve Fakturoidu
   - Vytvoří nebo najde kontakt (subject) ve Fakturoidu podle údajů z objednávky
   - Označí fakturu jako odeslanou a zaplacenou
   - Stáhne PDF faktury na server
   - Uloží cestu k PDF do databáze

2. **V dashboardu zákazníka:**
   - Zobrazí sekci "Faktura" na stránce detailu objednávky
   - Umožní stažení PDF faktury jedním kliknutím

## Instalace a nastavení

### 1. Získání Fakturoid přístupů (OAuth 2.0)

1. Přihlaste se do vašeho Fakturoid účtu na https://app.fakturoid.cz/
2. Přejděte do **Nastavení → Integrace → API aplikace**
3. Klikněte na **Vytvořit aplikaci** (nebo použijte existující)
4. Po vytvoření se vám zobrazí:
   - **Client ID** (např. `e408067c409fe6557a088c63539abbdb96fcc939`)
   - **Client Secret** (např. `31d475d1fe9f5c8af6c26c59517b846cec212a28`)
5. Poznamenejte si také:
   - **Account Slug**: název účtu v URL (např. `digimix` z `app.fakturoid.cz/digimix`)

#### Nastavení vlastní číselné řady (volitelné)

Pokud chcete používat vlastní číselnou řadu (např. oddělenou pro e-shop faktury):

1. V Fakturoidu přejděte do **Nastavení → Číselné řady**
2. Klikněte na **Přidat číselnou řadu** nebo upravte existující
3. Vytvořte řadu např. s názvem "E-shop" a formátem `ESHOP{YYYY}{00000}`
4. Po uložení klikněte na řadu a v URL uvidíte ID (např. `...number_formats/123/edit`)
5. Poznamenejte si toto **ID číselné řady**

**Alternativně získání ID přes Artisan příkaz:**

Po nastavení základních Fakturoid credentials (CLIENT_ID, CLIENT_SECRET, SLUG) spusťte:

```bash
php artisan fakturoid:list-number-formats
```

Nebo v Dockeru:

```bash
docker-compose exec app php artisan fakturoid:list-number-formats
```

Příkaz automaticky:

- Provede OAuth 2.0 autentizaci
- Získá všechny dostupné číselné řady
- Vypíše je v přehledné tabulce s ID

### 2. Nastavení .env souboru

Přidejte do souboru `.env` následující hodnoty:

```bash
# Fakturoid Configuration (OAuth 2.0)
FAKTUROID_CLIENT_ID=e408067c409fe6557a088c63539abbdb96fcc939
FAKTUROID_CLIENT_SECRET=31d475d1fe9f5c8af6c26c59517b846cec212a28
FAKTUROID_SLUG=digimix
FAKTUROID_NUMBER_FORMAT=1284290
FAKTUROID_USER_AGENT="Kavi (info@kavi.cz)"
```

**Parametry:**

- `FAKTUROID_CLIENT_ID` - Client ID z API aplikace (povinné)
- `FAKTUROID_CLIENT_SECRET` - Client Secret z API aplikace (povinné)
- `FAKTUROID_SLUG` - Název účtu v URL (povinné)
- `FAKTUROID_NUMBER_FORMAT` - ID číselné řady (volitelné, pokud není nastaveno použije se výchozí)
- `FAKTUROID_USER_AGENT` - User agent pro API requesty (volitelné)

### 3. Spuštění migrace

Spusťte migraci pro přidání sloupců do tabulky `orders`:

```bash
php artisan migrate
```

Nebo pokud používáte Docker:

```bash
docker-compose exec app php artisan migrate
```

### 4. Ověření konfigurace bankovního účtu

**DŮLEŽITÉ:** Fakturoid vyžaduje, aby ve vašem účtu byl nastaven bankovní účet. Bez něj nelze vytvářet faktury přes API.

1. Přihlaste se do Fakturoidu
2. Přejděte do **Nastavení → Bankovní účty**
3. Přidejte alespoň jeden bankovní účet

## Testování

### Test OAuth autentizace

Nejprve ověřte, že autentizace funguje:

```bash
docker-compose exec app php artisan fakturoid:list-number-formats
```

Pokud se zobrazí tabulka s číselnými řadami, autentizace funguje správně.

### Ruční test vytvoření faktury

Pro otestování můžete použít tinker:

```bash
docker-compose exec app php artisan tinker
```

Následně:

```php
// Najděte nějakou zaplacenu objednávku
$order = App\Models\Order::where('payment_status', 'paid')->first();

// Vytvořte fakturu
$service = app(App\Services\FakturoidService::class);
$result = $service->processInvoiceForOrder($order);

// Zkontrolujte výsledek
if ($result) {
    echo "Faktura úspěšně vytvořena!\n";
    echo "Fakturoid Invoice ID: " . $order->fakturoid_invoice_id . "\n";
    echo "PDF Path: " . $order->invoice_pdf_path . "\n";
} else {
    echo "Chyba při vytváření faktury. Zkontrolujte logy.\n";
}
```

### Testování Stripe webhooku

1. Vytvořte testovací objednávku
2. Proveďte platbu přes Stripe (testovací mód)
3. Stripe webhook automaticky:

   - Označí objednávku jako zaplacenou
   - Vytvoří fakturu ve Fakturoidu
   - Stáhne PDF faktury

4. Zkontrolujte:
   - Laravel logy: `storage/logs/laravel.log`
   - Dashboard objednávky: `/dashboard/objednavka/{id}`
   - Měla by se zobrazit sekce s fakturou ke stažení

## Struktura souborů

### Nové soubory:

- `app/Services/FakturoidService.php` - Služba pro komunikaci s Fakturoid API
- `database/migrations/2024_10_28_000000_add_fakturoid_fields_to_orders_table.php` - Migrace

### Upravené soubory:

- `app/Models/Order.php` - Přidány sloupce `fakturoid_invoice_id` a `invoice_pdf_path`
- `app/Services/StripeService.php` - Integrace volání FakturoidService po platbě
- `app/Http/Controllers/DashboardController.php` - Přidána metoda `downloadInvoice()`
- `routes/web.php` - Přidána route pro stažení faktury
- `resources/views/dashboard/order-detail.blade.php` - Přidána sekce s fakturou
- `config/services.php` - Přidána Fakturoid konfigurace

## Fakturoid API endpoints

Integrace používá následující Fakturoid API v3 endpoints:

### Autentizace (OAuth 2.0)

- `POST /api/v3/oauth/token` - Získání access tokenu

### API volání

- `POST /api/v3/accounts/{slug}/subjects.json` - Vytvoření kontaktu
- `GET /api/v3/accounts/{slug}/subjects.json` - Vyhledání kontaktu
- `GET /api/v3/accounts/{slug}/number_formats.json` - Získání číselných řad (pro setup)
- `POST /api/v3/accounts/{slug}/invoices.json` - Vytvoření faktury
- `POST /api/v3/accounts/{slug}/invoices/{id}/fire.json` - Akce s fakturou (odeslání)
- `POST /api/v3/accounts/{slug}/invoices/{id}/payments.json` - Označení jako zaplaceno
- `GET /api/v3/accounts/{slug}/invoices/{id}/download.pdf` - Stažení PDF

**Autentizace:**
Integrace používá OAuth 2.0 Client Credentials flow:

1. Získá access token pomocí `client_id` a `client_secret`
2. Použije token pro všechna API volání
3. Token je cachován v rámci jednoho requestu

Dokumentace Fakturoid API: https://www.fakturoid.cz/api/v3

## Ukládání PDF faktur

PDF faktury jsou ukládány v:

```
storage/app/invoices/order_{order_id}_invoice_{fakturoid_invoice_id}.pdf
```

Pro produkci doporučujeme nastavit symlink:

```bash
php artisan storage:link
```

## Chybové stavy

### Faktura se nevytváří

1. Zkontrolujte logy: `storage/logs/laravel.log`
2. Ověřte OAuth credentials v `.env` (CLIENT_ID, CLIENT_SECRET, SLUG)
3. Otestujte autentizaci: `php artisan fakturoid:list-number-formats`
4. Zkontrolujte, že máte ve Fakturoidu nastaven bankovní účet

### Authentication failed (401)

- Špatný CLIENT_ID nebo CLIENT_SECRET
- Zkontrolujte hodnoty v `.env`
- Vytvořte novou API aplikaci ve Fakturoidu pokud potřeba

### PDF se nestahuje

1. Ověřte, že existuje `fakturoid_invoice_id` v databázi
2. Zkontrolujte permissions na složku `storage/app/invoices/`
3. PDF může chvíli trvat, než se vygeneruje (integrace zkouší 5× s 2s pausou)

### 403 Forbidden při vytváření faktury

- Ve Fakturoidu není nastaven bankovní účet
- Přidejte bankovní účet v nastavení Fakturoidu

## Bezpečnost

- OAuth credentials (CLIENT_ID, CLIENT_SECRET) jsou uloženy v `.env` a nikdy by neměly být commitnuty do git
- Access token je cachován pouze v rámci jednoho requestu, není persistentně ukládán
- PDF faktury jsou přístupné pouze přihlášeným uživatelům, kterým objednávka patří
- Kontrola vlastnictví objednávky je v `DashboardController::downloadInvoice()`

## Produkční nasazení

1. Ujistěte se, že máte v `.env` produkční Fakturoid credentials (CLIENT_ID, CLIENT_SECRET, SLUG)
2. Spusťte migraci: `php artisan migrate --force`
3. Zkontrolujte permissions na složce `storage/app/invoices/`
4. Otestujte autentizaci: `php artisan fakturoid:list-number-formats`
5. Otestujte webhook s produkčními Stripe daty

## Support

Pro problémy s Fakturoid API:

- Dokumentace: https://www.fakturoid.cz/api/v3
- Support: podpora@fakturoid.cz

Pro problémy s integrací:

- Zkontrolujte Laravel logy
- Použijte `php artisan tinker` pro manuální testování
