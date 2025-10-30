# 🚀 QUICKSTART: Nastavení hesla pro účty bez registrace

## Co bylo implementováno?

Uživatelé s auto-created účty (guest checkout) nyní **mohou si v profilu nastavit vlastní heslo** bez znalosti současného hesla.

## ⚡ Rychlý test (5 minut)

### Krok 1: Spustit migraci ✅

```bash
php artisan migrate
```

**Výstup:**

```
✔ 2025_10_30_171433_add_password_set_by_user_to_users_table ... DONE
```

### Krok 2: Test guest checkout

1. **Odhlaste se** (pokud jste přihlášeni)

2. **Objednejte jako host:**

   - Jděte na `/produkty`
   - Přidejte produkt do košíku
   - V pokladně vyplňte email (např. `test@example.com`)
   - Dokončete objednávku

3. **Přihlaste se přes magic link:**
   - Jděte na `/prihlaseni`
   - Zadejte email `test@example.com`
   - Klikněte "Přihlásit se odkazem z emailu"
   - Otevřete MailHog: http://localhost:8025
   - Klikněte na magic link

### Krok 3: Ověřte profil

Jděte na `/dashboard/profil` a posuňte se k sekci hesla.

**✅ Měli byste vidět:**

```
┌─────────────────────────────────────────┐
│ Nastavit heslo                          │
│ Nastavte si vlastní heslo pro přihlášení│
├─────────────────────────────────────────┤
│ ℹ️ Přihlašujete se přes magic link      │
│                                         │
│ Zatím nemáte nastavené vlastní heslo.   │
│ Můžete si ho nastavit zde, nebo...     │
├─────────────────────────────────────────┤
│ Nové heslo: [________]                 │
│ Potvrdit heslo: [________]             │
│                                         │
│ [Nastavit heslo]                       │
└─────────────────────────────────────────┘
```

**❌ NEMĚLI byste vidět:** Pole "Současné heslo"

### Krok 4: Nastavte heslo

1. Zadejte nové heslo (minimálně 8 znaků)
2. Potvrďte heslo
3. Klikněte **"Nastavit heslo"**

**Úspěch:** Měli byste vidět: "Heslo bylo úspěšně změněno."

### Krok 5: Ověřte změnu

Obnovte stránku `/dashboard/profil`.

**✅ Nyní byste měli vidět:**

```
┌─────────────────────────────────────────┐
│ Změna hesla                             │
├─────────────────────────────────────────┤
│ Současné heslo: [________]             │ ← NOVÉ!
│                                         │
│ Nové heslo: [________]                 │
│ Potvrdit nové heslo: [________]        │
│                                         │
│ [Změnit heslo]                         │
└─────────────────────────────────────────┘
```

## 🎯 Validace v databázi

### Před nastavením hesla:

```bash
php artisan tinker
```

```php
$user = User::where('email', 'test@example.com')->first();
$user->password_set_by_user; // false (0)
```

### Po nastavení hesla:

```php
$user->refresh();
$user->password_set_by_user; // true (1)
```

## 🔄 Další testovací scénáře

### Test 2: Manuální registrace

1. Jděte na `/registrace`
2. Zaregistrujte se s novým emailem
3. Jděte na `/dashboard/profil`
4. **Měli byste vidět:** "Změna hesla" S polem "Současné heslo"

### Test 3: Existující uživatelé

```bash
php artisan tinker
```

```php
// Zkontrolujte všechny uživatele
User::all()->pluck('email', 'password_set_by_user');

// Všichni existující uživatelé by měli mít password_set_by_user = true
```

## 🐛 Troubleshooting

### Problém: Migrace selhala

**Řešení:**

```bash
php artisan migrate:rollback --step=1
php artisan migrate
```

### Problém: Stále vidím "Současné heslo" pro guest účet

**Příčina:** Cache nebo neaktualizovaný model

**Řešení:**

1. Odhlaste se a znovu přihlaste
2. Zkontrolujte DB:
   ```sql
   SELECT email, password_set_by_user FROM users WHERE email = 'your@email.com';
   ```
3. Pokud je `password_set_by_user = 1`, zkuste:
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

### Problém: Magic link nefunguje

**Řešení:**

1. Zkontrolujte, že migrace pro `login_tokens` tabulku proběhla:
   ```bash
   php artisan migrate:status
   ```
2. Zkontrolujte MailHog: http://localhost:8025
3. Zkontrolujte logy:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## 📊 SQL kontroly

### Zkontrolovat strukturu tabulky:

```sql
DESCRIBE users;
-- Měli byste vidět sloupec: password_set_by_user (tinyint)
```

### Zkontrolovat hodnoty:

```sql
SELECT
    email,
    password_set_by_user,
    created_at
FROM users
ORDER BY created_at DESC
LIMIT 10;
```

### Reset test uživatele:

```sql
-- Pokud chcete znovu otestovat s existujícím uživatelem
UPDATE users
SET password_set_by_user = 0
WHERE email = 'test@example.com';
```

## ✅ Checklist

- [ ] Migrace proběhla úspěšně
- [ ] Guest checkout vytvoří účet s `password_set_by_user = false`
- [ ] V profilu vidím "Nastavit heslo" (bez pole "Současné heslo")
- [ ] Po nastavení hesla se `password_set_by_user` změní na `true`
- [ ] V profilu vidím "Změna hesla" (S polem "Současné heslo")
- [ ] Manuální registrace vytvoří účet s `password_set_by_user = true`
- [ ] Password reset nastaví `password_set_by_user = true`
- [ ] Existující uživatelé mají `password_set_by_user = true`

## 📞 Potřebujete pomoc?

Zkontrolujte:

1. Logy: `storage/logs/laravel.log`
2. Databázi: `SELECT * FROM users;`
3. Migrační status: `php artisan migrate:status`

Vše funguje? **Gratulujeme! 🎉**

---

**Vytvořeno:** 30. října 2025  
**Dokumentace:** [PASSWORD_SET_IMPLEMENTATION.md](PASSWORD_SET_IMPLEMENTATION.md)
