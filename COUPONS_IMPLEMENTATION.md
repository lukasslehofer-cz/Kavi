# Implementace systému slevových kupónů

## Přehled

Byl implementován kompletní systém slevových kupónů pro projekt Kavi. Systém podporuje:

- ✅ Slevy pro jednorázové objednávky
- ✅ Slevy pro předplatné (časově omezené i neomezené)
- ✅ Dopravu zdarma
- ✅ Aktivaci kupónu přes link (např. kavi.cz/code/SLEVA2025)
- ✅ Administraci kupónů s detailními statistikami
- ✅ Integraci se Stripe pro automatickou úpravu cen po vypršení slevy

## Databázová struktura

### Nové tabulky

1. **`coupons`** - Definice slevových kupónů
2. **`coupon_usages`** - Historie použití kupónů

### Upravené tabulky

- **`orders`** - přidány pole: `coupon_id`, `coupon_code`, `discount_amount`
- **`subscriptions`** - přidány pole: `coupon_id`, `coupon_code`, `discount_amount`, `discount_months_remaining`, `discount_months_total`

## Typy slev

### 1. Jednorázová sleva

- **Procenta** (např. 10% sleva)
- **Pevná částka** (např. 100 Kč sleva)
- **Doprava zdarma** (samostatný typ)

### 2. Sleva pro předplatné

- **Procenta nebo pevná částka**
- **Časově omezená** (např. sleva na první 3 měsíce)
- **Neomezená** (sleva trvá po celou dobu předplatného)

### 3. Kombinovaná sleva

Jeden kupón může nabízet slevu jak pro jednorázové nákupy, tak pro předplatné současně.

## Parametry kupónu

- **Kód** - unikátní identifikátor (automaticky převeden na velká písmena)
- **Název a popis** - interní informace pro admin
- **Hodnota slevy** - procenta nebo pevná částka
- **Minimální hodnota objednávky** - volitelné omezení
- **Platnost od-do** - časové omezení platnosti
- **Limit použití celkem** - maximální počet použití
- **Limit použití na uživatele** - maximální počet použití jedním uživatelem
- **Status** - aktivní/neaktivní

## Použití kupónu

### Aktivace přes link

```
https://kavi.cz/code/SLEVA2025
```

- Kupón se automaticky uloží do cookie (platnost 7 dní)
- Při návštěvě pokladny se automaticky aplikuje

### Manuální zadání

- Uživatel zadá kód v sekci "Mám slevový kupón" v pokladně
- Kód se okamžitě validuje a aplikuje

## Administrace

### Přístup

```
/admin/coupons
```

### Funkce

1. **Seznam kupónů** - přehled všech kupónů s filtry
2. **Vytvoření kupónu** - formulář s validací
3. **Úprava kupónu** - možnost změnit parametry (s varováním pokud byl použit)
4. **Statistiky** - detailní přehled použití kupónu
5. **Smazání kupónu**

### Statistiky kupónu

- Celkový počet použití
- Celková poskytnutá sleva v Kč
- Počet unikátních uživatelů
- Rozdělení použití (objednávky vs. předplatné)
- Historie všech použití s detaily

## Integrace se Stripe

### Předplatné se slevou

1. Při vytvoření předplatného se sleva aplikuje na první platbu
2. Stripe subscription metadata obsahují info o kupónu
3. Po každé platbě se automaticky snižuje počet zbývajících měsíců slevy
4. Po vypršení slevy se automaticky vytvoří nový Price objekt v Stripe s plnou cenou

### Příklad workflow

```
Kupón: 100 Kč sleva na 3 měsíce předplatného za 500 Kč

Měsíc 1: Stripe platba 400 Kč (500 - 100)
Měsíc 2: Stripe platba 400 Kč (500 - 100)
Měsíc 3: Stripe platba 400 Kč (500 - 100)
Měsíc 4: Stripe automaticky přepne na 500 Kč (plná cena)
```

## API routes

```php
// Public
GET  /code/{code}                    - Aktivace kupónu z linku
POST /kupony/validovat               - AJAX validace kupónu

// Admin (require auth + admin middleware)
GET    /admin/coupons                - Seznam kupónů
GET    /admin/coupons/create         - Formulář pro vytvoření
POST   /admin/coupons                - Uložení nového kupónu
GET    /admin/coupons/{id}/edit      - Formulář pro úpravu
PUT    /admin/coupons/{id}           - Aktualizace kupónu
DELETE /admin/coupons/{id}           - Smazání kupónu
GET    /admin/coupons/{id}/stats     - Statistiky kupónu
```

## Soubory

### Models

- `app/Models/Coupon.php`
- `app/Models/CouponUsage.php`

### Controllers

- `app/Http/Controllers/CouponController.php`
- `app/Http/Controllers/Admin/CouponController.php`
- Upraveno: `CheckoutController.php`, `SubscriptionController.php`

### Services

- `app/Services/CouponService.php`
- Upraveno: `app/Services/StripeService.php`

### Views

- `resources/views/admin/coupons/index.blade.php`
- `resources/views/admin/coupons/create.blade.php`
- `resources/views/admin/coupons/edit.blade.php`
- `resources/views/admin/coupons/stats.blade.php`
- `resources/views/admin/coupons/_form.blade.php`
- Upraveno: `checkout/index.blade.php`, `subscriptions/checkout.blade.php`

### Migrations

- `2025_10_29_160000_create_coupons_table.php`
- `2025_10_29_160001_create_coupon_usages_table.php`
- `2025_10_29_160002_add_coupon_fields_to_orders_table.php`
- `2025_10_29_160003_add_coupon_fields_to_subscriptions_table.php`

## Testování

### Základní flow - Jednorázová objednávka

1. Vytvořte testovací kupón v admin panelu
2. Přidejte produkty do košíku
3. V pokladně zadejte kód kupónu
4. Ověřte, že sleva je správně aplikována
5. Dokončete objednávku
6. Zkontrolujte statistiky kupónu v adminu

### Předplatné s časovou slevou

1. Vytvořte kupón se slevou na 3 měsíce předplatného
2. Nakonfigurujte předplatné
3. Aplikujte kupón v pokladně
4. Dokončete objednávku kartou (Stripe)
5. Ověřte, že první platba má slevou
6. Po 3 měsících ověřte, že 4. platba je bez slevy

### Aktivace přes link

1. Vytvořte kupón
2. Otevřete `https://kavi.cz/code/VASEKOD`
3. Ověřte, že se zobrazí zpráva o aktivaci
4. Přejděte do pokladny - kupón by měl být automaticky aplikován

## Poznámky

- Kupóny lze kombinovat pouze jeden najednou
- Cookie s kupónem má platnost 7 dní
- Admin může sledovat detailní statistiky každého kupónu
- Systém automaticky validuje platnost a limity kupónů
- Stripe subscription automaticky upraví cenu po vypršení časově omezené slevy
