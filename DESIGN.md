# Kavi – Design System

**Design filozofie:** „Quiet Luxury" — tlumená elegance, ostré rohy (`border-radius: 0`), zemité tóny.

## Barvy

### Primary — terakotová červená (hlavní akcent)

| Odstín | HEX | Použití |
|--------|-----|---------|
| 50 | `#fef7f6` | |
| 100 | `#fceeed` | |
| 200 | `#f9d5d2` | |
| 300 | `#f3aea8` | |
| 400 | `#e07a71` | |
| **500** | `#CA4136` | **Hlavní terakota** – tlačítka, odkazy, akcenty |
| 600 | `#b53a30` | Hover stav |
| 700 | `#972f27` | |
| 800 | `#7d2820` | |
| 900 | `#68251f` | |

### Olive — olivová zelená (sekundární)

| Odstín | HEX | Použití |
|--------|-----|---------|
| 50 | `#f6f7f4` | |
| 100 | `#eaebe4` | |
| 200 | `#d5d8ca` | |
| 300 | `#b8bda8` | |
| 400 | `#969c82` | |
| **500** | `#636747` | **Hlavní olivová** |
| 600 | `#565a3e` | |
| 700 | `#464934` | |
| 800 | `#3a3c2c` | |
| 900 | `#323427` | |

### Warm — teplé neutrály (pozadí, borders, text)

| Odstín | HEX | Použití |
|--------|-----|---------|
| 50 | `#fafaf8` | |
| 100 | `#f5f5f2` | |
| **200** | `#E5E6DF` | **Světlé teplé pozadí** |
| **300** | `#BCBEB1` | **Teplý neutrál / borders** |
| 400 | `#9a9c8f` | |
| **500** | `#76716C` | **Teplá šedá** (sekundární text) |
| 600 | `#5f5b57` | |
| 700 | `#4d4a47` | |
| 800 | `#403e3b` | |
| 900 | `#363433` | |

### Dark — text a černá

| Odstín | HEX | Použití |
|--------|-----|---------|
| 50 | `#f5f5f5` | |
| 100 | `#e0e0e0` | |
| 200 | `#bdbdbd` | |
| 300 | `#9e9e9e` | |
| 400 | `#757575` | |
| 500 | `#616161` | |
| 600 | `#424242` | |
| 700 | `#2d2d2d` | |
| **800** | `#1c1c1c` | **Hlavní barva textu** |
| **900** | `#000000` | Čistá černá |

## Fonty

| Role | Font | Fallback | Váhy |
|------|------|----------|------|
| **Nadpisy (display)** | `TexGyreHeros` | Helvetica Neue, Arial, sans-serif | 400, 700 (+ italic) |
| **Text (sans)** | `Roboto` | system-ui, sans-serif | 300, 400, 500, 700, 900 |

- Nadpisy `h1–h6`: `font-display`, `font-normal`, `tracking-tight` (letter-spacing −0.025em), často **UPPERCASE**
- TexGyreHeros je lokální (`/fonts/texgyreheros-*.woff2`), Roboto z Google Fonts

## Klíčové designové prvky

- **Rohy:** ostré, `border-radius: 0` napříč celým webem (tlačítka, karty, inputy, modály)
- **Body:** `bg-white`, text `dark-800`, antialiased
- **Tlačítka (`.btn-primary`):** `bg-primary-500` → hover `primary-600`, bílý text, `px-6 py-3`, `font-semibold`
- **Inputy (`.input`):** border `warm-300`, focus ring `primary-500`
- **Borders (luxury):** `1px solid #BCBEB1`
- **Stíny (modály):** `0 20px 25px -5px rgba(0,0,0,0.15), 0 10px 10px -5px rgba(0,0,0,0.08)`

## Animace

| Název | Efekt |
|-------|-------|
| `float` | 6s vertikální plovoucí pohyb (±20px), nekonečně |
| `fade-in` | 1s fade + posun zdola (20px) |
| `gradient` | 3s animace pozice pozadí gradientu |

## Multi-domain / lokalizace

- `kavi.cz` → čeština (cs), měna **CZK**
- `kavibox.com` → angličtina (en), měna **EUR**

---

*Zdroje: `tailwind.config.js`, `resources/css/app.css`*
