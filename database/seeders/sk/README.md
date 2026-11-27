# Slovenské překlady obsahu (kavibox.sk)

Úvodní překlad katalogu a blogu do slovenštiny pro `SlovakContentSeeder`. Další obsah se překládá v adminu (DeepL).

- **Zdroj:** produkční DB (`kavi_new`), jen SELECT, export 27. 11. 2026 v 16:27 CET. Všechny řádky včetně neaktivních produktů a kávy měsíce.
- **Překlad:** cs → sk, Claude. Zachované jsou HTML značky, atributy a `href` (relativní odkazy zůstávají na českých cestách), čísla, jednotky a vlastní jména.

## Formát

```json
[{"id": 12, "fields": {"name_sk": {"src": "<sha1>", "text": "…"}, "attributes.origin_sk": {"src": "…", "text": "…"}}}]
```

- `src` = sha1 přesné české hodnoty v DB (UTF-8, NULL → `""`). U `attributes.{key}_sk` jde o sha1 hodnoty `attributes.{key}`. Všechny přeložené hodnoty v `attributes` jsou řetězce. U `slug_sk` jde o sha1 hodnoty `slug_cs`.
- `slug_sk` = `Str::slug(title_sk | name_sk)`, v rámci souboru unikátní.
- Pole chybí, když je zdroj prázdný nebo je překlad shodný se zdrojem (vlastní jména, „Rotterdam“, „Bourbon“…). Řádek bez jediného pole v souboru není.
- Konce řádků (`\r\n`) a okrajové bílé znaky odpovídají zdroji.
- Přeložené klíče `attributes`: `origin`, `processing`, `variety`, `flavor_notes` a vlastní parametry `{key}_label` + `{key}`. `altitude`, `weight` a `roast_date` se nepřekládají.

## Počty

| soubor | záznamy / řádky v DB | přeložená pole |
|---|---|---|
| `products.json` | 150 / 156 | `name_sk` 21, `short_description_sk` 144, `description_sk` 150, `attributes.*_sk` 363 |
| `roasteries.json` | 46 / 46 | `country_sk` 37, `city_sk` 9, `short_description_sk` 46, `full_description_sk` 46 (`name_sk` 0) |
| `posts.json` | 22 / 22 | `title_sk` 22, `slug_sk` 21, `perex_sk` 22, `content_sk` 22 |
| `tags.json` | 1 / 3 | `name_sk` 1 (`slug_sk` 0) |
| `announcement_banners.json` | 7 / 7 | `message_sk` 7, `title_sk` 1 (`link_label_cs` je všude prázdné) |

Celkem 467 792 znaků slovenského textu (zdroj 450 708 znaků).

Kde pole chybí:

- Produkty #39–42, #44, #45: popis je jen název produktu a nemají atributy.
- 135 názvů produktů a 216 hodnot atributů je stejných i ve slovenštině.
- Země u 9 pražíren je ve slovenštině stejná (Lotyšsko, Portugalsko, Rumunsko, Švédsko, Litva, Slovinsko).
- Názvy všech pražíren jsou stejné.
- `slug_sk` článku #1 vyšel stejně jako `slug_cs`.
- Štítky „Tipy“ a „Recepty“ jsou ve slovenštině stejné. Slug štítku „Výberová káva“ zůstává `vyberova-kava`.

## Úpravy oproti doslovnému překladu

- **Doprava zdarma:** sliby jsou vypuštěné nebo nahrazené doručením přes Packetu, a to v článcích #1, #2, #14–#18, #20–#22. Obecné rady typu „porovnávajte cenu vrátane dopravy“ zůstaly.
- **Ceny v Kč** jsou převedené na EUR podle kavibox.com: box M 35 €, předplatné o 5 € levnější, poukaz od 25 €, tříměsíční dárkové předplatné 105 €. Kde EN verze ceny neuvádí (#1, #2, #10), je přepočet zhruba 25 Kč = 1 €.
- **Lokalizace pro Slovensko:** u zmínek o domácím trhu („v Česku zaplatíte“, „v Česku nekoupíte“) je místo Česka Slovensko, nebo země úplně vypadla.
- Zásilkovna je Packeta.

## Na co pamatovat v seederu / adminu

- Články #1–#3 mají 6 absolutních odkazů `https://kavi.cz/…` přímo ve zdroji. Ty zůstaly beze změny.
- Banner #5 („pozastavujeme dodávky mimo ČR“) se na kavibox.sk nehodí.
- **Chyby v českém zdroji**, které stojí za opravu i v češtině:
  - Produkt #128: rozbitý krátký popis „…káEtiopská…“.
  - Produkt #130: v popisu je zbloudilý řádek „kofio“.
  - Produkt #84: odrůda „ink Bourbon“.
  - Znaky U+FFFC (zbytky po kopírování) jsou v produktech #20, #23, #25 a v pražírnách #8–#10 a #12–#15. Ve slovenštině jsou zachované na stejných místech.
