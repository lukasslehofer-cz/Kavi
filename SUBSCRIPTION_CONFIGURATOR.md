# Konfigurátor kávového předplatného

## Přehled

Interaktivní 3-krokový konfigurátor předplatného umístěný na homepage místo původní sekce s předplatnými plány.

## Funkce

### Krok 1: Množství kávy

- Výběr podle počtu šálků denně:
  - **1-2 šálky** → 2 balení
  - **3-4 šálky** → 3 balení
  - **5+ šálků** → 4 balení
- Automatický přechod na další krok po výběru

### Krok 2: Typ kávy

Tři možnosti:

- **Espresso** - Intenzivní a plné chutě
- **Filter** - Jemné a čisté chutě
- **Kombinace** - Mix podle preferencí

#### Decaf varianta

Při výběru **Espresso** nebo **Filter** se zobrazí:

**Checkbox:**

- ✓ **Chci decaf variantu** - Aktivuje možnost výběru decaf balení

**Po zaškrtnutí checkboxu:**

- Zobrazí se **posuvník** pro výběr počtu decaf balení (1 až celkový počet)
- **Automatický dopočet**: Zbytek balení se automaticky doplní jako běžná káva
- Např. při 3 balení celkem:
  - Posuvník: 1× Espresso Decaf → Automaticky: 2× Espresso
  - Posuvník: 2× Espresso Decaf → Automaticky: 1× Espresso
  - Posuvník: 3× Espresso Decaf → Automaticky: 0× Espresso

Decaf kávy se liší mezi espresso a filter metodami přípravy, proto jsou volby oddělené.

#### Režim Kombinace

Když uživatel vybere "Kombinace", zobrazí se:

**Progress indikátor:**

- Velké číslo zobrazující aktuální/požadovaný počet balení
- Progress bar s gradientem
- Dynamická zpráva:
  - "Začněte výběrem káv pomocí posuvníků" (prázdný stav)
  - "Ještě musíte vybrat X balení" (chybí balení) - animovaný text
  - "⚠️ Máte vybráno o X balení více!" (přebývají balení)
  - "✓ Perfektní! Můžete pokračovat" (správný počet)

**Interaktivní slidery pro 4 typy káv:**

- Espresso (🎯)
- Espresso Decaf (🎯🌙)
- Filter (💧)
- Filter Decaf (💧🌙)

Validace: Celkový počet balení musí přesně odpovídat vybranému množství z kroku 1.

### Krok 3: Frekvence dodávky

Tři možnosti:

- **Každý měsíc** - Pro pravidelnou spotřebu
- **Jednou za 2 měsíce** - Pro střední spotřebu
- **Jednou za 3 měsíce** - Pro občasné pití

### Shrnutí & Cena

Zobrazuje:

- Množství balení a počet šálků
- Typ kávy (včetně detailu kombinace)
- Frekvence dodávky
- **Celková cena** - 250 Kč za balení

## Technické detaily

### Soubory

- **View**: `/resources/views/home.blade.php` (řádky 111-354)
- **CSS**: `/resources/css/app.css` (řádky 98-178)
- **JavaScript**: `/resources/js/app.js` (řádky 40-292)

### Styly

Přidané CSS třídy:

- `.config-step` - Kontejner pro jednotlivé kroky
- `.step-indicator` - Vizuální indikátor kroků
- `.coffee-amount-option`, `.coffee-type-option`, `.frequency-option` - Tlačítka pro výběr
- `.selected` - Označení vybrané možnosti
- `.slider` - Styling pro range input slidery

### JavaScript API

#### Konfigurace

```javascript
{
  amount: 2-4,                    // Počet balení
  cups: "1-2"|"3-4"|"5+",        // Počet šálků
  type: "espresso"|"filter"|"mix", // Typ kávy
  isDecaf: true|false,            // Decaf varianta (pro espresso/filter)
  mix: {                          // Mix konfigurace (pouze pro type="mix")
    espresso: 0-4,
    espressoDecaf: 0-4,
    filter: 0-4,
    filterDecaf: 0-4
  },
  frequency: 1|2|3,               // Interval v měsících
  frequencyText: string           // Popisný text
}
```

#### LocalStorage

Konfigurace se ukládá do `localStorage` pod klíčem `subscriptionConfig`:

```javascript
localStorage.getItem("subscriptionConfig");
```

## Budoucí integrace (Backend)

Pro plnou funkčnost bude potřeba:

1. **Vytvořit route** pro checkout:

   ```php
   Route::post('/subscriptions/checkout', [SubscriptionController::class, 'checkout']);
   ```

2. **Controller metoda**:

   ```php
   public function checkout(Request $request)
   {
       $config = $request->validate([
           'amount' => 'required|integer|between:2,4',
           'type' => 'required|in:espresso,filter,mix',
           'isDecaf' => 'boolean',
           'mix' => 'array',
           'mix.espresso' => 'integer|min:0',
           'mix.espressoDecaf' => 'integer|min:0',
           'mix.filter' => 'integer|min:0',
           'mix.filterDecaf' => 'integer|min:0',
           'frequency' => 'required|integer|in:1,2,3',
       ]);

       // Vytvořit předplatné
       // Přesměrovat na Stripe checkout
   }
   ```

3. **Upravit JavaScript** (řádek 290):

   ```javascript
   window.location.href =
     "/subscriptions/checkout?" + new URLSearchParams(config).toString();
   ```

4. **Databáze** - Rozšířit `subscriptions` tabulku o:
   - `config_json` (JSON) - Pro uložení celé konfigurace
   - nebo jednotlivé sloupce pro amount, type, mix_config, frequency

## UX Features

✓ **Progress bar** - Vizuální indikace průběhu mezi kroky  
✓ **Auto-advance** - Automatický posun po výběru množství  
✓ **Validace** - Real-time kontrola správnosti kombinace  
✓ **Mix progress bar** - Grafický ukazatel vybraných balení v režimu kombinace  
✓ **Dynamické zprávy** - Živé informace o stavu výběru (kolik ještě zbývá)  
✓ **Decaf checkbox + slider** - Nejprve checkbox, pak posuvník pro počet decaf balení  
✓ **Automatický dopočet** - Zbylé balení se automaticky doplní jako normální káva  
✓ **4 typy káv v mixu** - Kombinace espresso, espresso decaf, filter, filter decaf  
✓ **Animace** - Plynulé přechody mezi kroky a pulzující texty  
✓ **Responsive** - Funguje na všech zařízeních  
✓ **Přehledné shrnutí** - Jasné zobrazení celé konfigurace před objednávkou

## Cena

- **Základní cena**: 250 Kč za balení
- Cenu lze upravit změnou konstanty `PRICE_PER_BAG` v `app.js` (řádek 59)

## Testování

Pro otestování:

1. Otevřete homepage: `http://localhost`
2. Scrollujte k sekci "Vyberte si své předplatné"
3. Projděte všechny kroky konfiguratoru
4. Po kliknutí na "Pokračovat k objednávce" se konfigurace vypíše do konzole

---

**Vytvořeno**: 24. října 2025  
**Status**: Frontend kompletní ✓ | Backend TODO
