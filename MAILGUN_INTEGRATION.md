# Mailgun Integration - Odesílání e-mailů

## Co bylo uděláno

✅ **E-mailová třída** - `app/Mail/OrderConfirmation.php`
✅ **HTML šablona** - `resources/views/emails/order-confirmation.blade.php` (design jako web)
✅ **Automatické odesílání** - po vytvoření objednávky v CheckoutController
✅ **Testovací command** - `php artisan email:test-order email@example.com`
✅ **Konfigurace** - `config/mail.php` a ENV proměnné

E-maily se **odesílají automaticky** při každé objednávce! 🎉

## Co bylo implementováno

### 1. Konfigurace Mailgun

- **config/mail.php** - Kompletní konfigurace Laravel Mail systému s podporou Mailgun SMTP
- Podpora pro SMTP i Mailgun API transport
- Fallback mechanismus při selhání

### 2. E-mailová třída (Mailable)

- **app/Mail/OrderConfirmation.php** - Třída pro odesílání potvrzení objednávky
- Automatické načítání dat objednávky
- Profesionální předmět e-mailu

### 3. E-mailová šablona

- **resources/views/emails/order-confirmation.blade.php** - Krásně designovaná HTML šablona
- Responzivní design pro všechna zařízení
- Shodný vizuální styl jako web Kavi Coffee:
  - Minimalistický, čistý design
  - Zaoblené rohy a moderní layout
  - Primary barva (oranžová/amber) pro CTA prvky
  - Light/bold typografie
  - Důvěryhodné prvky (trust badges)

### 4. Integrace do CheckoutController

- Automatické odesílání e-mailu po vytvoření objednávky
- Error handling - neúspěšné odeslání e-mailu neovlivní dokončení objednávky
- Logování chyb pro debugging

## Nastavení Mailgun

### Krok 1: Vytvoření účtu na Mailgun

1. Přejděte na [https://www.mailgun.com/](https://www.mailgun.com/)
2. Zaregistrujte se (můžete začít s bezplatným plánem)
3. Ověřte svůj účet

### Krok 2: Nastavení domény

1. V Mailgun dashboardu přejděte na **Sending** → **Domains**
2. Klikněte na **Add New Domain**
3. Zadejte subdoménu pro odesílání e-mailů (např. `mg.kavi.cz`)
4. Postupujte podle instrukcí pro nastavení DNS záznamů:

#### DNS záznamy pro ověření domény:

```
TXT záznam:
Hostname: mg.kavi.cz
Value: v=spf1 include:mailgun.org ~all

TXT záznam (DKIM):
Hostname: k1._domainkey.mg.kavi.cz
Value: (zkopírujte hodnotu z Mailgun)

MX záznamy:
Priority: 10
Hostname: mg.kavi.cz
Value: mxa.eu.mailgun.org

Priority: 10
Hostname: mg.kavi.cz
Value: mxb.eu.mailgun.org

CNAME záznam (tracking):
Hostname: email.mg.kavi.cz
Value: eu.mailgun.org
```

5. Počkejte na ověření DNS záznamů (může trvat až 48 hodin, obvykle pár minut)

### Krok 3: Získání přihlašovacích údajů

#### Pro SMTP (doporučeno pro začátek):

1. V Mailgun dashboardu přejděte na **Sending** → **Domain settings** → vaše doména
2. Klikněte na **SMTP credentials**
3. Vytvořte nebo použijte existující SMTP credentials
4. Poznamenejte si:
   - **Login**: např. `postmaster@mg.kavi.cz`
   - **Password**: vygenerované heslo (uložte si ho bezpečně!)

#### Pro Mailgun API:

1. V Mailgun dashboardu přejděte na **Settings** → **API Keys**
2. Zkopírujte **Private API key** (začíná `key-...`)

### Krok 4: Konfigurace .env souboru

Otevřete váš `.env` soubor a aktualizujte následující hodnoty:

```env
# E-mail konfigurace (SMTP přes Mailgun)
MAIL_MAILER=smtp
MAIL_HOST=smtp.eu.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@mg.kavi.cz
MAIL_PASSWORD=your-smtp-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="objednavky@kavi.cz"
MAIL_FROM_NAME="Kavi Coffee"

# Mailgun API konfigurace
MAILGUN_DOMAIN=mg.kavi.cz
MAILGUN_SECRET=key-your-api-key-here
MAILGUN_ENDPOINT=api.eu.mailgun.net
```

**Důležité poznámky:**

- Pokud používáte Mailgun v **EU regionu**, použijte `smtp.eu.mailgun.org` a `api.eu.mailgun.net`
- Pokud používáte Mailgun v **US regionu**, použijte `smtp.mailgun.org` a `api.mailgun.net`
- `MAIL_FROM_ADDRESS` by měla být platná e-mailová adresa na vaší ověřené doméně

## Testování e-mailů

### V development prostředí:

Pro testování v lokálním prostředí můžete použít:

1. **Mailtrap** (doporučeno pro development):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

2. **Laravel Log** (e-maily se ukládají do logu místo odesílání):

```env
MAIL_MAILER=log
```

### Testování na produkci:

1. Ujistěte se, že všechny DNS záznamy jsou ověřené
2. Vytvořte testovací objednávku
3. Zkontrolujte, zda e-mail dorazil
4. V Mailgun dashboardu můžete sledovat:
   - **Logs** - záznamy o odeslaných e-mailech
   - **Analytics** - statistiky doručování

## Obsah potvrzovacího e-mailu

E-mail s potvrzením objednávky obsahuje:

✅ **Profesionální hlavička** s logem Kavi Coffee  
✅ **Potvrzení přijetí objednávky** s číslem objednávky  
✅ **Seznam objednaných produktů** s cenami a množstvím  
✅ **Celkovou cenu** včetně DPH a dopravy  
✅ **Informace o doručení** (výdejní místo Zásilkovna)  
✅ **Fakturační údaje** zákazníka  
✅ **Status platby**  
✅ **CTA tlačítko** pro zobrazení detailu objednávky  
✅ **Trust badges** (čerstvá káva, rychlá doprava, zákaznická podpora)  
✅ **Kontaktní informace**  
✅ **Profesionální footer** s odkazy

## Struktura kódu

### Mailable třída

```php
// app/Mail/OrderConfirmation.php
class OrderConfirmation extends Mailable
{
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // Automatické nastavení předmětu a view
}
```

### Použití v controlleru

```php
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;

// Po vytvoření objednávky
Mail::to($request->email)->send(new OrderConfirmation($order));
```

### Blade šablona

E-mailová šablona používá inline CSS pro maximální kompatibilitu s e-mailovými klienty. Design je responzivní a testovaný na:

- Gmail (web, iOS, Android)
- Apple Mail (macOS, iOS)
- Outlook (web, desktop)
- Yahoo Mail
- Seznam Email

## Rozšíření systému

### Přidání dalších e-mailů

Pro přidání nových typů e-mailů:

1. **Vytvořte novou Mailable třídu:**

```bash
php artisan make:mail WelcomeEmail
```

2. **Vytvořte Blade šablonu:**

```bash
resources/views/emails/welcome.blade.php
```

3. **Odešlete e-mail:**

```php
Mail::to($user->email)->send(new WelcomeEmail($user));
```

### Možné typy e-mailů pro budoucí implementaci:

- **Vítací e-mail** po registraci
- **Reset hesla** (už má Laravel built-in)
- **Potvrzení expedice** objednávky
- **Informace o doručení** objednávky
- **Připomínka nedokončené objednávky** (abandoned cart)
- **Newsletter** s novými kávami
- **Potvrzení předplatného** a informace o příštím boxu
- **Faktura** jako příloha e-mailu
- **Žádost o review** po doručení

## Queue (fronty) pro e-maily

Pro lepší výkon v produkci doporučujeme používat fronty:

1. **Nastavte queue driver v .env:**

```env
QUEUE_CONNECTION=redis
```

2. **Upravte Mailable třídu:**

```php
class OrderConfirmation extends Mailable implements ShouldQueue
{
    use Queueable;
    // ...
}
```

3. **Spusťte queue worker:**

```bash
php artisan queue:work
```

## Monitorování a statistiky

### Mailgun Dashboard

V Mailgun dashboardu můžete sledovat:

- **Delivery rate** - míra doručení
- **Open rate** - míra otevření e-mailů
- **Click rate** - míra kliknutí na odkazy
- **Bounce rate** - míra odmítnutých e-mailů
- **Complaints** - spam stížnosti

### Doporučené nastavení

1. **Webhooks** pro sledování stavů e-mailů:

   - Delivered
   - Opened
   - Clicked
   - Bounced
   - Complained

2. **Suppressions** - automatické potlačení odesílání na:
   - Bounced adresy
   - Unsubscribed uživatele
   - Spam complaints

## Nejčastější problémy

### E-maily se neodesílají

1. **Zkontrolujte DNS záznamy** - musí být všechny ověřené
2. **Zkontrolujte .env konfiguraci** - správný SMTP host a credentials
3. **Zkontrolujte logy:** `storage/logs/laravel.log`
4. **Zkontrolujte Mailgun logs** v dashboardu

### E-maily končí ve spamu

1. **SPF a DKIM záznamy** musí být správně nastavené
2. **Warming up** - začněte s menším objemem e-mailů a postupně zvyšujte
3. **Obsah e-mailů** - vyhněte se spam triggerům
4. **Authenticita** - používejte ověřenou doménu

### Rate limiting

Bezplatný plán Mailgun má limity:

- **5,000 e-mailů měsíčně zdarma**
- Pro vyšší objem zvažte placený plán

## Bezpečnost

- **Nikdy necommitujte .env** soubor do gitu
- **Používejte silná hesla** pro Mailgun účet
- **Aktivujte 2FA** na Mailgun účtu
- **Pravidelně rotujte API klíče**
- **Monitorujte podezřelou aktivitu** v Mailgun logu

## Docker poznámky

Pokud běžíte na Dockeru, ujistěte se že:

1. PHP kontejner má přístup k internetu
2. Port 587 není blokovaný firewallem
3. PHP má povolený `openssl` extension pro TLS

## Support

Při problémech kontaktujte:

- **Mailgun Support:** [https://help.mailgun.com/](https://help.mailgun.com/)
- **Laravel Mail dokumentace:** [https://laravel.com/docs/mail](https://laravel.com/docs/mail)

## Závěr

Mailgun integrace je nyní plně funkční a připravená k použití. Po správném nastavení DNS a credentials by měly e-maily fungovat automaticky při každé dokončené objednávce.

**Důležité:** Před spuštěním na produkci vždy otestujte e-maily na testovacích adresách!
