<?php

namespace App\Helpers;

/**
 * Jazykový web (kavi.cz / kavibox.com / kavibox.sk) podle config/sites.php.
 *
 * Jediné místo, které ví, která doména patří ke kterému jazyku, měně a odesílateli.
 * Místo podmínek typu `locale === 'en' ? kavibox : kavi.cz` používej Site::forLocale().
 */
final class Site
{
    public const PRIMARY_LOCALE = 'cs';

    private function __construct(
        public readonly string $locale,
        private readonly array $config,
    ) {
    }

    /**
     * Všechny weby v pořadí z configu.
     *
     * @return array<string, self>
     */
    public static function all(bool $enabledOnly = false): array
    {
        $sites = [];
        foreach ((array) config('sites', []) as $locale => $config) {
            $site = new self($locale, $config);
            if ($enabledOnly && ! $site->enabled()) {
                continue;
            }
            $sites[$locale] = $site;
        }

        return $sites;
    }

    /**
     * Kódy jazyků všech webů (cs, en, sk).
     *
     * @return list<string>
     */
    public static function locales(bool $enabledOnly = false): array
    {
        return array_keys(self::all($enabledOnly));
    }

    public static function exists(?string $locale): bool
    {
        return $locale !== null && is_array(config("sites.{$locale}"));
    }

    /**
     * Web pro daný jazyk; neznámý jazyk padá na český web.
     */
    public static function forLocale(?string $locale): self
    {
        $locale = self::exists($locale) ? $locale : self::PRIMARY_LOCALE;

        return new self($locale, config("sites.{$locale}"));
    }

    /**
     * Web podle hostitele (i www. a subdomény), null když doména není známá.
     */
    public static function forHost(string $host): ?self
    {
        $host = strtolower($host);
        foreach (self::all() as $site) {
            foreach ($site->domains() as $domain) {
                if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                    return $site;
                }
            }
        }

        return null;
    }

    /**
     * Web aktuálního requestu (podle locale nastaveného v DetectRegion).
     */
    public static function current(): self
    {
        return self::forLocale(app()->getLocale());
    }

    /**
     * Jazyk pro nově vznikající objednávku / předplatné v dané měně.
     *
     * Na webu se stejnou měnou je to jazyk aktuálního webu (kavibox.sk → sk). Mimo něj
     * (webhook Stripe na kavi.cz s EUR platbou, cron) jazyk podle měny jako dřív;
     * webhooky proto předávají jazyk v metadatech checkoutu.
     */
    public static function localeForNewRecord(?string $currency): string
    {
        $currency = strtoupper((string) $currency) ?: 'CZK';
        $current = self::current();

        if ($current->currency() === $currency) {
            return $current->locale;
        }

        return $currency === 'EUR' ? 'en' : self::PRIMARY_LOCALE;
    }

    public function isPrimary(): bool
    {
        return $this->locale === self::PRIMARY_LOCALE;
    }

    /** @return list<string> */
    public function domains(): array
    {
        return (array) ($this->config['domains'] ?? []);
    }

    public function url(): string
    {
        return rtrim((string) $this->config['url'], '/');
    }

    public function currency(): string
    {
        return $this->config['currency'];
    }

    public function region(): string
    {
        return $this->config['region'];
    }

    public function name(): string
    {
        return $this->config['name'];
    }

    public function label(): string
    {
        return $this->config['label'];
    }

    public function languageName(): string
    {
        return $this->config['language_name'];
    }

    public function ogLocale(): string
    {
        return $this->config['og_locale'];
    }

    public function contactEmail(): string
    {
        return $this->config['contact_email'];
    }

    public function mailer(): string
    {
        return $this->config['mailer'];
    }

    /** @return array{address: string, name: string} */
    public function mailFrom(): array
    {
        return [
            'address' => $this->config['mail_from_address'],
            'name' => $this->config['mail_from_name'],
        ];
    }

    public function defaultCountry(): string
    {
        return $this->config['default_country'];
    }

    public function enabled(): bool
    {
        return (bool) ($this->config['enabled'] ?? true);
    }
}
