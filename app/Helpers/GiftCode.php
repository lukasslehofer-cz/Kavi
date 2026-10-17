<?php

namespace App\Helpers;

/**
 * Kódy dárků ve tvaru KAVI-XXXX-XXXX.
 *
 * Abeceda bez zaměnitelných znaků (0/O, 1/I/L), 31^8 ≈ 8,5 × 10^11 kombinací.
 * Typ dárku (předplatné / poukaz) se bere z databáze, ne z kódu.
 */
class GiftCode
{
    private const ALPHABET = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

    public static function generate(): string
    {
        $chars = '';
        $max = strlen(self::ALPHABET) - 1;

        for ($i = 0; $i < 8; $i++) {
            $chars .= self::ALPHABET[random_int(0, $max)];
        }

        return 'KAVI-' . substr($chars, 0, 4) . '-' . substr($chars, 4, 4);
    }

    /**
     * Sjednotí zápis z formuláře: velká písmena, bez mezer, s pomlčkami na svém místě.
     * Kódy, které tvarem dárkové nejsou, vrátí jen ořezané a velkými písmeny.
     */
    public static function normalize(string $input): string
    {
        $code = strtoupper(trim($input));
        $compact = preg_replace('/[\s\-–]+/u', '', $code);

        if (preg_match('/^KAVI([A-Z0-9]{4})([A-Z0-9]{4})$/', $compact, $m)) {
            return 'KAVI-' . $m[1] . '-' . $m[2];
        }

        return $code;
    }

    public static function looksLikeGiftCode(string $input): bool
    {
        return (bool) preg_match('/^KAVI-[A-Z0-9]{4}-[A-Z0-9]{4}$/', self::normalize($input));
    }
}
