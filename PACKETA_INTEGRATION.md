# Packeta API Integrace

Tato dokumentace popisuje integraci Packeta (Zásilkovna) API do Kavi e-shopu s kávovým předplatným.

## Přehled implementace

Integrace umožňuje:

- ✅ Zadání fakturační adresy v pokladně (místo doručovací adresy)
- ✅ Výběr výdejního místa Zásilkovny pomocí interaktivní mapy
- ✅ Filtrování dopravců na mapě (volitelné)
- ✅ Zobrazení vybraného výdejního místa po výběru
- ✅ Možnost změny výdejního místa
- ✅ Uložení výdejního místa pro opakované použití v předplatném
- ✅ Správa výdejního místa v uživatelském dashboardu

## Nastavení

### 1. Prostředí (.env)

Přidejte následující konfiguraci do `.env` souboru:

```env
# Packeta API konfigurace
PACKETA_API_KEY=your_api_key_here
PACKETA_API_PASSWORD=your_api_password_here
PACKETA_SENDER_ID=your_sender_id_here
PACKETA_WIDGET_KEY=your_widget_key_here
```

**Kde získat credentials:**

- API Key a Password: [Packeta zákaznická sekce](https://client.packeta.com/) → Nastavení → API
- Sender ID: V zákaznické sekci v sekci Odesílatel
- Widget Key: V zákaznické sekci v sekci Widget / API klíč pro widget

### 2. Databázová migrace

Spusťte migraci pro přidání Packeta polí:

```bash
php artisan migrate
```

Tato migrace přidá následující pole:

- Do tabulky `users`: `packeta_point_id`, `packeta_point_name`, `packeta_point_address`
- Do tabulky `subscriptions`: `packeta_point_id`, `packeta_point_name`, `packeta_point_address`

## Použití

### Pokladna - Standardní objednávka

1. Uživatel vyplní kontaktní údaje
2. Vyplní **fakturační adresu** (ulice, město, PSČ)
3. Klikne na tlačítko "Vybrat výdejní místo Zásilkovna"
4. Otevře se interaktivní mapa s výdejními místy
5. Po výběru místa se zobrazí název a adresa výdejního místa
6. Uživatel může výdejní místo změnit kliknutím na "Změnit"
7. Po dokončení objednávky se výdejní místo uloží k uživatelskému účtu

### Pokladna - Předplatné

Stejný proces jako u standardní objednávky, s tím rozdílem, že:

- Výdejní místo se uloží k předplatnému
- Při každé další dodávce předplatného se použije toto výdejní místo
- Uživatel může kdykoliv změnit výdejní místo v dashboardu

### Změna výdejního místa v dashboardu

1. Uživatel přejde do Dashboard → Předplatné
2. V sekci "Výdejní místo Zásilkovna" klikne na "Změnit"
3. Vybere nové výdejní místo
4. Změna se automaticky uloží a použije se pro další dodávky

## Filtrování dopravců

Pro omezení zobrazených dopravců na mapě upravte parametr `vendors` v JavaScript kódu:

### Checkout view (`resources/views/checkout/index.blade.php`)

Řádek cca 425:

```javascript
Packeta.Widget.pick(
  packetaApiKey,
  function (point) {
    // ...
  },
  {
    country: "cz",
    language: "cs",
    vendors: ["packeta", "zasilkovna"], // Přidejte kódy dopravců
  }
);
```

### Subscription checkout view (`resources/views/subscriptions/checkout.blade.php`)

Řádek cca 498:

```javascript
Packeta.Widget.pick(
  packetaApiKey,
  function (point) {
    // ...
  },
  {
    country: "cz",
    language: "cs",
    vendors: ["packeta", "zasilkovna"], // Přidejte kódy dopravců
  }
);
```

### Dostupné dopravci

Nejčastější kódy dopravců:

- `packeta` - Zásilkovna (Z-BOX)
- `zasilkovna` - Výdejní místa Zásilkovny
- `ppl` - PPL
- `cp` - Česká pošta
- `dpd` - DPD

Úplný seznam najdete v [Packeta API dokumentaci](https://www.zasilkovna.cz/api/dokumentace).

## Implementované soubory

### Backend

1. **Migrace**: `database/migrations/2025_10_24_195510_add_packeta_fields_to_users_and_subscriptions.php`
2. **Konfigurace**: `config/services.php` - přidána sekce `packeta`
3. **Service**: `app/Services/PacketaService.php` - komunikace s Packeta API
4. **Modely**:
   - `app/Models/User.php` - přidána pole pro Packeta
   - `app/Models/Subscription.php` - přidána pole pro Packeta
5. **Kontrolery**:
   - `app/Http/Controllers/CheckoutController.php` - upravena validace a ukládání
   - `app/Http/Controllers/SubscriptionController.php` - upravena validace a ukládání
   - `app/Http/Controllers/DashboardController.php` - přidána metoda `updatePacketaPoint()`
6. **Routy**: `routes/web.php` - přidána route pro aktualizaci výdejního místa

### Frontend

1. **Checkout view**: `resources/views/checkout/index.blade.php`
   - Změna "Dodací adresa" na "Fakturační adresa"
   - Přidána sekce pro výběr výdejního místa Packeta
   - JavaScript pro Packeta widget
2. **Subscription checkout view**: `resources/views/subscriptions/checkout.blade.php`
   - Stejné změny jako v checkout view
3. **Dashboard subscription view**: `resources/views/dashboard/subscription.blade.php`
   - Přidána sekce zobrazující výdejní místo
   - Možnost změny výdejního místa
   - JavaScript pro Packeta widget a AJAX aktualizaci

## PacketaService metody

### `getWidgetKey(): string`

Vrací API klíč pro inicializaci Packeta widgetu.

### `getSenderId(): string`

Vrací ID odesílatele.

### `createPacket(array $data): ?array`

Vytvoří zásilku v Packeta systému. Použijte po potvrzení objednávky.

**Parametry:**

```php
[
    'name' => 'Jan',
    'surname' => 'Novák',
    'email' => 'jan@example.com',
    'phone' => '+420123456789',
    'packeta_point_id' => '12345',
    'value' => 500.00,
    'weight' => 0.5,
    'order_number' => 'ORD-001',
]
```

### `getPickupPoint(string $pointId): ?array`

Získá detailní informace o výdejním místě.

### `getTrackingInfo(string $packetId): ?array`

Získá informace o sledování zásilky.

## Budoucí rozšíření

1. **Automatické vytváření zásilek**: Při potvrzení objednávky automaticky vytvořit zásilku přes `PacketaService::createPacket()`
2. **Sledování zásilek**: Přidat tracking informace do detailu objednávky
3. **Tisk štítků**: Integrace generování a tisku štítků pro zásilky
4. **Webhooks**: Implementace webhooks pro aktualizaci stavu zásilek

## Troubleshooting

### Widget se nezobrazuje

1. Zkontrolujte, zda je v `.env` správně nastaven `PACKETA_WIDGET_KEY`
2. Otevřete konzoli prohlížeče (F12) a zkontrolujte chyby
3. Ověřte, že je načten script `https://widget.packeta.com/v6/www/js/library.js`

### API volání selhávají

1. Zkontrolujte `PACKETA_API_KEY` a `PACKETA_API_PASSWORD` v `.env`
2. Ověřte, že máte aktivní API přístup v Packeta zákaznické sekci
3. Zkontrolujte logy v `storage/logs/laravel.log`

### Výdejní místo se neuloží

1. Zkontrolujte, že byla spuštěna migrace (`php artisan migrate`)
2. Ověřte, že jsou pole přidána do `$fillable` v modelech `User` a `Subscription`
3. Zkontrolujte validaci v kontrolerech

## Kontakt a podpora

Pro podporu kontaktujte:

- Packeta: [podpora@packeta.com](mailto:podpora@packeta.com)
- Dokumentace API: [https://www.zasilkovna.cz/api/dokumentace](https://www.zasilkovna.cz/api/dokumentace)

