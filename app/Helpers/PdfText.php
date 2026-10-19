<?php

namespace App\Helpers;

/**
 * Řádkování textu pro dompdf, aby PDF certifikátu vypadalo stejně jako náhled v prohlížeči.
 *
 * Změřeno na fontech z resources/fonts/pdf: dompdf bere line-height vynásobené
 * výškou fontu × 1,1 (fontHeightRatio), tedy 1,575 u TeX Gyre Heros a 1,289 u Roboto,
 * a první účaří kreslí v 0,8 takového řádku. Prohlížeč má účaří v polovině řádku
 * + (ascent − descent) / 2 × velikost písma.
 *
 * V PDF se proto line-height vydělí poměrem fontu (rozestup řádků pak sedí)
 * a zbytek rozdílu prvního účaří se dorovná přes position: relative na vnitřním <span>.
 */
class PdfText
{
    private const FONTS = [
        // b = (ascent − descent) × 1,1, c = (ascent + descent) / 2 (descent záporný)
        'heros' => ['b' => 1.5752, 'c' => 0.432],
        'roboto' => ['b' => 1.2892, 'c' => 0.342],
    ];

    /**
     * CSS line-height pro blok s textem
     */
    public static function lineHeight(bool $isPdf, float $size, float $lineHeight, string $family = 'heros'): string
    {
        $value = $isPdf ? round($lineHeight / self::font($family)['b'], 3) : $lineHeight;

        return 'line-height: ' . $value . 'px;';
    }

    /**
     * CSS pro vnitřní <span>, který v PDF dorovná polohu prvního řádku; mimo PDF prázdné
     */
    public static function shift(bool $isPdf, float $size, float $lineHeight, string $family = 'heros'): string
    {
        if (! $isPdf) {
            return '';
        }

        $offset = round(0.3 * $lineHeight - self::font($family)['c'] * $size, 2);

        return $offset == 0.0 ? '' : 'position: relative; top: ' . (-$offset) . 'px;';
    }

    private static function font(string $family): array
    {
        return self::FONTS[$family] ?? self::FONTS['heros'];
    }
}
