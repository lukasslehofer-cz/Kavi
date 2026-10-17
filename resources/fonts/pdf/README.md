# Fonty pro PDF (dárkový certifikát)

dompdf potřebuje TrueType fonty, WOFF v `public/fonts` mají CFF obrysy, které nevloží.

| Soubor | Zdroj | Licence |
|---|---|---|
| `texgyreheros-regular.ttf`, `texgyreheros-bold.ttf` | TeX Gyre Heros 2.004 z CTAN (`fonts/tex-gyre/opentype`), převedeno z OTF na TrueType obrysy (fontTools, cu2qu) | GUST Font License, viz `GUST-FONT-LICENSE.txt` |
| `roboto-light.ttf`, `roboto-regular.ttf`, `roboto-medium.ttf` | Roboto z Google Fonts (fonts.gstatic.com, verze v51) | SIL Open Font License 1.1 |

Používá je `resources/views/gifts/certificate-pdf.blade.php`. Cache metrik fontů zapisuje dompdf do `storage/app/gifts/font-cache`.
