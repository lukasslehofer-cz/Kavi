<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Google recenze z Business Profile API pro zobrazení ve vlastním designu.
 *
 * Každá doména má vlastní profil - kavi.cz pod locale "cs", kavibox.com pod "en".
 * Recenze se drží odděleně, ať se na anglickém webu neobjeví české.
 *
 * Pravidla, která tahle třída musí dodržet (developers.google.com/my-business/content/policies):
 *
 *  - "It must be stored temporarily for no more than 30 calendar days" - proto tvrdý
 *    strop v isFresh(). Po jeho překročení se nezobrazuje nic, i kdyby data v cache byla.
 *  - "It cannot be manipulated or aggregated in any way" - proto tu není žádný výpočet
 *    průměrného hodnocení a nefiltruje se podle počtu hvězdiček.
 *  - Atribuci nesmíme měnit, takže jméno autora i jeho fotka jdou ven tak, jak přijdou.
 *
 * Filtrování negativních recenzí navíc zakazuje i zákon 634/1992 Sb. po novele Omnibus
 * účinné od 6. 1. 2023 - je to klamavá obchodní praktika za všech okolností.
 *
 * Recenze jsou dostupné jen na legacy v4; nová rozdělená v1 API je neobsahují.
 */
class GoogleReviewsService
{
    /**
     * Policy strop. Data starší se nesmí zobrazit ani použít.
     */
    protected const MAX_AGE_DAYS = 30;

    protected const ENDPOINT = 'https://mybusiness.googleapis.com/v4';

    protected const TOKEN_ENDPOINT = 'https://oauth2.googleapis.com/token';

    /**
     * Recenze k zobrazení, od nejnovějších.
     *
     * Záměrně se neřadí ani nevybírá podle hvězdiček - viz pravidla v hlavičce třídy.
     *
     * @return array<int, array<string, mixed>>
     */
    public function latest(int $limit = 3, ?string $locale = null): array
    {
        $cached = Cache::get($this->cacheKey($this->resolveLocale($locale)));

        if (! $this->isFresh($cached)) {
            return [];
        }

        return array_slice($cached['reviews'], 0, $limit);
    }

    /**
     * Stáhne recenze jednoho profilu a uloží je do cache.
     *
     * @return array{ok: bool, count: int, message: string}
     */
    public function refresh(?string $locale = null): array
    {
        $locale = $this->resolveLocale($locale);

        if (! $this->isConfigured($locale)) {
            return ['ok' => false, 'count' => 0, 'message' => "Profil pro '{$locale}' není nakonfigurovaný"];
        }

        try {
            $token = $this->accessToken($locale);

            if (! $token) {
                return ['ok' => false, 'count' => 0, 'message' => 'Nepodařilo se obnovit access token z refresh tokenu'];
            }

            $reviews = $this->fetchAll($token, $locale);
        } catch (\Throwable $e) {
            Log::error('Stažení Google recenzí selhalo', ['locale' => $locale, 'error' => $e->getMessage()]);

            return ['ok' => false, 'count' => 0, 'message' => $e->getMessage()];
        }

        Cache::put($this->cacheKey($locale), [
            'fetched_at' => now()->toIso8601String(),
            'reviews' => $reviews,
        ], now()->addDays(self::MAX_AGE_DAYS));

        return ['ok' => true, 'count' => count($reviews), 'message' => 'OK'];
    }

    /**
     * Locale, pro které je profil vyplněný.
     *
     * @return array<int, string>
     */
    public function configuredLocales(): array
    {
        return array_values(array_filter(
            array_keys(config('services.google_reviews.profiles', [])),
            fn (string $locale) => $this->isConfigured($locale)
        ));
    }

    public function isConfigured(?string $locale = null): bool
    {
        $locale = $this->resolveLocale($locale);

        if (blank(config('services.google_reviews.client_id')) || blank(config('services.google_reviews.client_secret'))) {
            return false;
        }

        if (blank($this->refreshToken($locale))) {
            return false;
        }

        return filled(config("services.google_reviews.profiles.{$locale}.account_id"))
            && filled(config("services.google_reviews.profiles.{$locale}.location_id"));
    }

    /**
     * Kdy naposledy proběhlo stažení. Pro diagnostiku v příkazu.
     */
    public function fetchedAt(?string $locale = null): ?Carbon
    {
        $cached = Cache::get($this->cacheKey($this->resolveLocale($locale)));

        return isset($cached['fetched_at']) ? Carbon::parse($cached['fetched_at']) : null;
    }

    /**
     * Vypíše účty a provozovny, ke kterým má token přístup. Slouží ke zjištění
     * account_id a location_id, které jinde v rozhraní Googlu nejsou po ruce.
     *
     * @return array<int, array<string, mixed>>
     */
    public function discoverLocations(?string $locale = null): array
    {
        $locale = $this->resolveLocale($locale);
        $token = $this->accessToken($locale);

        if (! $token) {
            throw new \RuntimeException('Nepodařilo se získat access token. Zkontroluj client_id, client_secret a refresh_token.');
        }

        $accounts = Http::withToken($token)
            ->get('https://mybusinessaccountmanagement.googleapis.com/v1/accounts');

        if ($accounts->failed()) {
            throw new \RuntimeException('accounts.list vrátilo '.$accounts->status().': '.$accounts->body());
        }

        $found = [];

        foreach ($accounts->json('accounts', []) as $account) {
            // name chodí jako "accounts/123456789"
            $accountId = basename($account['name'] ?? '');

            $locations = Http::withToken($token)->get(
                "https://mybusinessbusinessinformation.googleapis.com/v1/accounts/{$accountId}/locations",
                ['readMask' => 'name,title,storefrontAddress', 'pageSize' => 100]
            );

            foreach ($locations->json('locations', []) as $location) {
                $found[] = [
                    'account_id' => $accountId,
                    'account_name' => $account['accountName'] ?? null,
                    'location_id' => basename($location['name'] ?? ''),
                    'title' => $location['title'] ?? null,
                ];
            }
        }

        return $found;
    }

    protected function resolveLocale(?string $locale): string
    {
        $locale = $locale ?: app()->getLocale();

        return array_key_exists($locale, config('services.google_reviews.profiles', [])) ? $locale : 'cs';
    }

    protected function cacheKey(string $locale): string
    {
        return "google_reviews:{$locale}";
    }

    /**
     * Token profilu má přednost před sdíleným.
     */
    protected function refreshToken(string $locale): ?string
    {
        return config("services.google_reviews.profiles.{$locale}.refresh_token")
            ?: config('services.google_reviews.refresh_token');
    }

    /**
     * Data starší než 30 dnů se podle policy nesmí použít, i když v cache leží.
     */
    protected function isFresh(mixed $cached): bool
    {
        if (! is_array($cached) || empty($cached['reviews']) || empty($cached['fetched_at'])) {
            return false;
        }

        return Carbon::parse($cached['fetched_at'])->diffInDays(now()) < self::MAX_AGE_DAYS;
    }

    /**
     * Access token z uloženého refresh tokenu. Service account nelze použít,
     * Business Profile API vyžaduje OAuth se souhlasem uživatele.
     */
    protected function accessToken(string $locale): ?string
    {
        $response = Http::asForm()->post(self::TOKEN_ENDPOINT, [
            'client_id' => config('services.google_reviews.client_id'),
            'client_secret' => config('services.google_reviews.client_secret'),
            'refresh_token' => $this->refreshToken($locale),
            'grant_type' => 'refresh_token',
        ]);

        if ($response->failed()) {
            Log::error('Obnova Google access tokenu selhala', [
                'locale' => $locale,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        return $response->json('access_token');
    }

    /**
     * Projde všechny stránky recenzí. pageSize má tvrdý strop 50.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function fetchAll(string $token, string $locale): array
    {
        $url = sprintf(
            '%s/accounts/%s/locations/%s/reviews',
            self::ENDPOINT,
            config("services.google_reviews.profiles.{$locale}.account_id"),
            config("services.google_reviews.profiles.{$locale}.location_id")
        );

        $reviews = [];
        $pageToken = null;
        $guard = 0;

        do {
            $response = Http::withToken($token)->get($url, array_filter([
                'pageSize' => 50,
                'orderBy' => 'updateTime desc',
                'pageToken' => $pageToken,
            ]));

            if ($response->failed()) {
                throw new \RuntimeException('Business Profile API vrátilo '.$response->status().': '.$response->body());
            }

            foreach ($response->json('reviews', []) as $review) {
                $reviews[] = $this->normalize($review);
            }

            $pageToken = $response->json('nextPageToken');
        } while ($pageToken && ++$guard < 40);

        return $reviews;
    }

    /**
     * Převede odpověď API do tvaru pro šablonu.
     *
     * Jde jen o změnu formátu, ne o zásah do obsahu: text recenze ani jméno
     * autora se nijak neupravují.
     *
     * @param  array<string, mixed>  $review
     * @return array<string, mixed>
     */
    protected function normalize(array $review): array
    {
        $anonymous = (bool) ($review['reviewer']['isAnonymous'] ?? false);

        return [
            'id' => $review['reviewId'] ?? null,
            'author' => $anonymous ? null : ($review['reviewer']['displayName'] ?? null),
            // Fotky se hotlinkují z Google CDN. Lokální kopie by spadala pod
            // stejný 30denní limit jako zbytek obsahu.
            'photo' => $anonymous ? null : ($review['reviewer']['profilePhotoUrl'] ?? null),
            'is_anonymous' => $anonymous,
            'rating' => $this->starRating($review['starRating'] ?? null),
            'text' => $this->originalComment($review['comment'] ?? null),
            'created_at' => isset($review['createTime']) ? Carbon::parse($review['createTime']) : null,
        ];
    }

    /**
     * Google k recenzi přilepí vlastní strojový překlad, takže na českém webu
     * svítí i anglická verze téhož textu. Vracíme jen to, co zákazník opravdu
     * napsal - autorova slova zůstávají nedotčená, odstraňuje se jen to, co
     * k nim přidal Google.
     *
     * Chodí to ve dvou tvarech:
     *   "originál\n\n(Translated by Google)\npřeklad"
     *   "(Translated by Google)\npřeklad\n\n(Original)\noriginál"
     */
    protected function originalComment(?string $comment): ?string
    {
        if (blank($comment)) {
            return null;
        }

        if (str_contains($comment, '(Original)')) {
            $comment = substr($comment, strpos($comment, '(Original)') + strlen('(Original)'));
        } elseif (str_contains($comment, '(Translated by Google)')) {
            $comment = substr($comment, 0, strpos($comment, '(Translated by Google)'));
        }

        return trim($comment) ?: null;
    }

    /**
     * starRating chodí jako enum, ne jako číslo.
     */
    protected function starRating(?string $value): ?int
    {
        return match ($value) {
            'ONE' => 1,
            'TWO' => 2,
            'THREE' => 3,
            'FOUR' => 4,
            'FIVE' => 5,
            default => null,
        };
    }
}
