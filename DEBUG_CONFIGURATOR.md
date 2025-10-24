# Debug průvodce pro konfigurátor předplatného

## Provedené změny

1. **Opravena deklarace proměnných** - `configureBtn` a `nextToStep3Btn` jsou nyní deklarovány na začátku funkce `initSubscriptionConfigurator()`, aby byly dostupné ve všech částech kódu
2. **Přidán rozsáhlý debugging** - Console.logy na všech kritických místech

## Jak testovat

### Krok 1: Otevři stránku

Jdi na: `http://localhost/predplatne`

### Krok 2: Otevři Developer Console

- **Chrome/Edge**: F12 nebo Ctrl+Shift+J (Windows) / Cmd+Option+J (Mac)
- **Firefox**: F12 nebo Ctrl+Shift+K (Windows) / Cmd+Option+K (Mac)
- **Safari**: Cmd+Option+C (Mac)

### Krok 3: Zkontroluj inicializaci

Hned po načtení stránky by ses měl v konzoli vidět tyto zprávy:

```
Initializing subscription configurator...
Found configure button: <button...>
Found next button: <button...>
Setting up configure button event listener. Button: <button...>
```

**Pokud vidíš:**

- ✅ Všechny tři zprávy → Konfigurátor se inicializoval správně
- ❌ Žádnou zprávu → JavaScript se nenačetl nebo je cache problém
- ❌ "Configure button not found!" → Tlačítko nemá správné ID

### Krok 4: Projdi konfigurátor

#### 4.1 Vyber množství kávy (Krok 1)

- Klikni na libovolné množství (2-4 šálky)
- Měl bys automaticky přejít na Krok 2

#### 4.2 Vyber typ kávy (Krok 2)

- Vyber "Espresso", "Filter" nebo "Mix"
- Pokud vybereš "Mix", nastav posuvníky

#### 4.3 Klikni "Pokračovat" (do Kroku 3)

#### 4.4 Vyber frekvenci (Krok 3)

- Klikni na frekvenci (každý měsíc, každé 2 měsíce, atd.)
- **V konzoli by ses měl objevit:**
  ```
  Frequency selected: 1 "každý měsíc"
  Configure button enabled!
  ```
- **Tlačítko "Pokračovat k objednávce" by se mělo:**
  - Přestat být šedé (opacity-50 odstraněn)
  - Kurzor by se měl změnit z "not-allowed" na normální
  - Tlačítko by už nemělo mít `disabled` atribut

### Krok 5: Klikni na "Pokračovat k objednávce"

**V konzoli by ses měl vidět:**

```
=== Configure button clicked! ===
Button element: <button...>
Button disabled: false
Current config: {
  "amount": 2,
  "cups": "2-3",
  "type": "espresso",
  ...
}
Form created: <form...>
Added field: amount = 2
Added field: cups = 2-3
Added field: type = espresso
Added field: isDecaf = 0
Added mix field: mix[espresso] = 0
(... více polí ...)
Added field: frequency = 1
Added field: frequencyText = každý měsíc
Appending form to body...
Form appended. Submitting...
Form submitted!
```

**Co se může stát:**

1. ✅ **Přesměrování na:** `http://localhost/predplatne/pokladna` - Úspěch!
2. ❌ **Reload na stejnou stránku s červeným error boxem** - Validační chyby (uvidíš je nahoře)
3. ❌ **Reload bez chyb** - Zkontroluj `storage/logs/laravel.log`

## Možné problémy a řešení

### Problém 1: Žádné console.logy se nezobrazují

**Příčina:** Cache starých assetů  
**Řešení:**

1. V Chrome/Firefox: Ctrl+Shift+R (Windows) nebo Cmd+Shift+R (Mac) - hard refresh
2. Nebo vyčisti cache prohlížeče
3. Nebo zkontroluj, že se načítá správná verze JS: zkontroluj v záložce Network, že se načítá `app-BwAy-HDi.js`

### Problém 2: "Configure button not found!"

**Příčina:** ID tlačítka neodpovídá  
**Řešení:** V HTML hledej tlačítko a zkontroluj, že má `id="configure-subscription"`

### Problém 3: Tlačítko se neklikne (zůstává disabled)

**Příčina:** Frekvence se neuložila správně  
**Řešení:** Zkontroluj v konzoli, jestli vidíš "Frequency selected: ..."

### Problém 4: Kliknutí se zaznamená, ale nepřesměruje

**Příčina:** Možná chyba na backendu nebo CSRF token  
**Řešení:**

1. Zkontroluj v záložce Network, jestli se odeslal POST request na `/predplatne/konfigurator/checkout`
2. Zkontroluj response - možná je tam chyba 419 (CSRF) nebo 500

### Problém 5: "Žádné chyby v konzoli nevidím"

**Příčina:** Konzole může být filtrována  
**Řešení:** V konzoli zkontroluj, že:

- Není aktivní žádný filtr (mělo by být "All" nebo "Verbose")
- Není zaškrtnuté "Hide network messages"
- Console není vyčištěná (nezobrazuje se žádná historická zpráva)

## Kam poslat výstup?

Pokud problém přetrvává, pošli mi prosím:

1. **Screenshot celé console** (včetně všech zpráv)
2. **Screenshot záložky Network** (filtruj na "Fetch/XHR")
3. **Odpověz na tyto otázky:**
   - Vidíš zprávu "Initializing subscription configurator..."?
   - Vidíš zprávu "Configure button enabled!"?
   - Vidíš zprávu "=== Configure button clicked! ==="?
   - Změní se tlačítko po výběru frekvence?
   - Co se stane po kliknutí na tlačítko?
