# Test funkce Zapomenuté heslo

## 🧪 Rychlý test

### Předpoklady

1. Aplikace běží (`php artisan serve`)
2. Databáze je migrována (`php artisan migrate`)
3. Máte nastavený email driver (log, mailhog, mailtrap, nebo mailgun)

### Kontrola email driveru

V `.env` souboru zkontrolujte:

```env
MAIL_MAILER=log  # Pro testování - emaily se uloží do storage/logs/laravel.log
# nebo
MAIL_MAILER=mailhog  # Pokud používáte MailHog
# nebo
MAIL_MAILER=mailtrap  # Pokud používáte Mailtrap
```

### Testovací postup

#### 1. Otevřete přihlašovací stránku

```
http://localhost:8000/prihlaseni
```

#### 2. Klikněte na "Zapomenuté heslo?"

Měli byste být přesměrováni na:

```
http://localhost:8000/zapomenute-heslo
```

#### 3. Zadejte existující email

- Např. email uživatele z databáze
- Klikněte na "Odeslat odkaz pro reset hesla"

#### 4. Zkontrolujte email

**Pro MAIL_MAILER=log:**

```bash
tail -f storage/logs/laravel.log
```

Najděte URL s reset linkem, bude vypadat jako:

```
http://localhost:8000/reset-hesla/TOKEN?email=user@example.com
```

**Pro MailHog:**
Otevřete http://localhost:8025 a najděte email

**Pro Mailtrap:**
Přihlaste se na mailtrap.io a otevřete inbox

#### 5. Klikněte na reset odkaz

- Zkopírujte URL z emailu
- Vložte do prohlížeče
- Měli byste vidět formulář pro nastavení nového hesla

#### 6. Nastavte nové heslo

- Zadejte nové heslo (min. 8 znaků)
- Potvrďte heslo
- Klikněte na "Nastavit nové heslo"

#### 7. Přihlaste se

- Měli byste být přesměrováni na `/prihlaseni` s hláškou o úspěchu
- Přihlaste se s novým heslem

## ✅ Co testovat

### Pozitivní scénáře

- [x] Zobrazení formuláře zapomenutého hesla
- [x] Odeslání emailu s platným emailem
- [x] Zobrazení success zprávy
- [x] Přijetí emailu s reset linkem
- [x] Otevření reset formuláře z linku
- [x] Nastavení nového hesla
- [x] Přihlášení s novým heslem

### Negativní scénáře

- [x] Zadání neexistujícího emailu (měl by zobrazit stejnou success zprávu)
- [x] Použití vypršelého tokenu (error: "Token je neplatný")
- [x] Použití již použitého tokenu (error: "Token je neplatný")
- [x] Zadání příliš krátkého hesla (error: "Pole password musí mít alespoň 8 znaků")
- [x] Hesla se neshodují (error: "Potvrzení pole password se neshoduje")

## 🐛 Troubleshooting

### Email se neposílá

**Problém**: Po odeslání formuláře se nic nestane

**Řešení**:

1. Zkontrolujte `.env` konfiguraci:

   ```env
   MAIL_MAILER=log
   ```

2. Zkontrolujte `config/mail.php`:

   ```bash
   php artisan config:cache
   ```

3. Zkontrolujte log:
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Token je neplatný

**Problém**: Po kliknutí na odkaz se zobrazí "Token je neplatný"

**Možné příčiny**:

1. Token vypršel (platnost 60 minut)
2. Token byl již použit
3. Časové razítko v databázi není správné

**Řešení**:

```bash
# Vyčistěte staré tokeny
php artisan tinker
>>> DB::table('password_reset_tokens')->truncate();
>>> exit

# Zkuste znovu
```

### Routy nefungují

**Problém**: 404 Not Found

**Řešení**:

```bash
php artisan route:clear
php artisan route:cache
php artisan route:list | grep password
```

### České překlady se nezobrazují

**Problém**: Chybové hlášky jsou v angličtině

**Řešení**:

1. Zkontrolujte `.env`:

   ```env
   APP_LOCALE=cs
   ```

2. Vyčistěte cache:
   ```bash
   php artisan config:cache
   php artisan cache:clear
   ```

## 📧 Ukázka emailu

Email obsahuje:

- ✅ Logo Kavi Coffee
- ✅ Ikonu zámku
- ✅ Nadpis "Reset hesla"
- ✅ Informační boxy s instrukcemi
- ✅ Tlačítko "Nastavit nové heslo"
- ✅ Bezpečnostní upozornění
- ✅ Tipy pro silné heslo
- ✅ Alternativní URL odkaz

## 🔒 Bezpečnostní kontrola

- [x] Token je platný pouze 60 minut
- [x] Token lze použít pouze jednou
- [x] Systém neprozradí neexistující email
- [x] CSRF ochrana na formulářích
- [x] Heslo je hashováno
- [x] Remember token je regenerován po změně hesla

## 🎨 Design kontrola

- [x] Formuláře mají stejný styl jako login/register
- [x] Barvy odpovídají brand identity (primary #e6305a)
- [x] Ikony jsou z Heroicons
- [x] Zaoblené rohy (rounded-xl, rounded-full)
- [x] Responzivní design
- [x] Font weights: light, medium, bold
- [x] Info boxy s různými barvami

## 📊 SQL dotazy pro debugging

### Zobrazit všechny reset tokeny

```sql
SELECT * FROM password_reset_tokens;
```

### Smazat vypršelé tokeny

```sql
DELETE FROM password_reset_tokens
WHERE created_at < NOW() - INTERVAL 60 MINUTE;
```

### Zobrazit uživatele podle emailu

```sql
SELECT id, name, email, created_at
FROM users
WHERE email = 'user@example.com';
```

## 💡 Tipy

1. **Pro rychlejší testování** použijte `MAIL_MAILER=log` a sledujte log file
2. **MailHog** je skvělý nástroj pro lokální testování emailů
3. **Mailtrap** je dobrý pro staging prostředí
4. Pro produkční prostředí použijte **Mailgun** (už nakonfigurováno)

## 🚀 Deploy checklist

Před nasazením do produkce:

- [ ] Změňte `MAIL_MAILER` na produkční driver (mailgun)
- [ ] Zkontrolujte `MAIL_FROM_ADDRESS` a `MAIL_FROM_NAME`
- [ ] Otestujte s reálným emailem
- [ ] Zkontrolujte, že migrace běží na produkci
- [ ] Vyčistěte cache: `php artisan config:cache`
- [ ] Zkontrolujte SSL certifikát pro bezpečné reset odkazy
