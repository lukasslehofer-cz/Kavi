<?php

namespace App\Mail;

use App\Services\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

abstract class LocalizedMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The locale for this email (cs or en)
     * Note: Named $emailLocale to avoid conflict with Laravel 11's Mailable::$locale
     */
    public string $emailLocale;

    /**
     * The site name based on locale
     */
    public string $siteName;

    /**
     * The contact email based on locale
     */
    public string $contactEmail;

    /**
     * Základ URL pro region e-mailu, bez koncového lomítka
     */
    public string $siteUrl;

    /**
     * Odkazy do patičky sdíleného layoutu – musí sedět doména i cesta
     */
    public string $homeUrl;

    public string $shopUrl;

    public string $logoUrl;

    /**
     * Set the locale and related properties
     */
    protected function setLocale(string $locale): void
    {
        $this->emailLocale = $locale;
        $this->siteName = EmailService::getSiteName($locale);
        $this->contactEmail = EmailService::getContactEmail($locale);
        $this->mailer = EmailService::getMailer($locale);

        $this->siteUrl = EmailService::getSiteUrl($locale);
        $this->homeUrl = $this->mailUrl('home');
        $this->shopUrl = $this->mailUrl('products.index');
        $this->logoUrl = $this->siteUrl.'/images/kavi-logo-white.png';
    }

    /**
     * Get the "from" address based on locale
     */
    protected function getFromAddress(): Address
    {
        $from = EmailService::getFromAddress($this->emailLocale);
        return new Address($from['address'], $from['name']);
    }

    /**
     * Build the message with locale-aware from address and mailer
     */
    public function build()
    {
        return $this
            ->mailer(EmailService::getMailer($this->emailLocale))
            ->from($this->getFromAddress())
            ->withSymfonyMessage(function ($message) {
                $headers = $message->getHeaders();
                $headers->addTextHeader('X-Mailable-Class', static::class);
                $headers->addTextHeader('X-Mailable-Locale', $this->emailLocale);
                
                // Add related model IDs if available
                if (isset($this->order) && $this->order) {
                    $headers->addTextHeader('X-Order-ID', (string) $this->order->id);
                }
                if (isset($this->subscription) && $this->subscription) {
                    $headers->addTextHeader('X-Subscription-ID', (string) $this->subscription->id);
                }
                if (isset($this->user) && $this->user) {
                    $headers->addTextHeader('X-User-ID', (string) $this->user->id);
                }
            });
    }

    /**
     * Get the view data for the message.
     * Adds $locale for backward compatibility with Blade templates.
     */
    public function buildViewData(): array
    {
        $data = parent::buildViewData();
        
        // Add $locale as alias for $emailLocale for backward compatibility
        $data['locale'] = $this->emailLocale;
        
        return $data;
    }

    /**
     * Translate a key with the current locale
     */
    protected function trans(string $key, array $replace = []): string
    {
        return __($key, $replace, $this->emailLocale);
    }

    /**
     * Absolutní URL do e-mailu: doména podle regionu příjemce, cesta podle
     * jazykové varianty routy.
     *
     * route() bere hostitele z requestu (admin posílá EN mail z kavi.cz) nebo
     * z APP_URL (cron) – obojí je špatně. Doména i cesta musí sedět dohromady,
     * jinak LocalizedUrlRedirect udělá 301 zpátky na druhou jazykovou variantu.
     */
    protected function mailUrl(string $name, array $parameters = []): string
    {
        $localized = "{$name}.{$this->emailLocale}";

        $path = \Illuminate\Support\Facades\Route::has($localized)
            ? route($localized, $parameters, false)
            : route($name, $parameters, false);

        return $this->siteUrl.$path;
    }

    /**
     * URL na lokalizovanou routu podle jazyka e-mailu, ne podle jazyka requestu.
     * E-mail se často renderuje z cronu, kde app()->getLocale() nic neříká.
     */
    protected function localizedRouteFor(string $name, array $parameters = []): string
    {
        return $this->mailUrl($name, $parameters);
    }

    /**
     * Rozdělí prostý text na odstavce (prázdný řádek = nový odstavec).
     * Používají maily, jejichž tělo píše admin v administraci.
     *
     * @return array<int, string>
     */
    public static function splitParagraphs(string $bodyText): array
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", trim($bodyText));

        return array_values(array_filter(
            array_map('trim', preg_split("/\n{2,}/", $normalized) ?: []),
            fn ($paragraph) => $paragraph !== ''
        ));
    }

    /**
     * Rozdělí tělo zprávy na bloky (odstavce a seznamy) a rovnou je vyrenderuje
     * do bezpečného HTML. Používají maily, jejichž tělo píše admin.
     *
     * Podporované značky:
     *   prázdný řádek  = nový odstavec
     *   **text**       = tučně
     *   "- " / "* "    = odrážka
     *   "1. " / "1) "  = číslovaný seznam
     *
     * @return array<int, array{type: string, ordered?: bool, html?: string, items?: array<int, string>}>
     */
    public static function parseBlocks(string $bodyText): array
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", trim($bodyText));

        $blocks = [];
        $current = null;

        $flush = function () use (&$blocks, &$current) {
            if ($current !== null) {
                $blocks[] = $current;
                $current = null;
            }
        };

        foreach (preg_split("/\n{2,}/", $normalized) ?: [] as $chunk) {
            $flush();

            foreach (explode("\n", $chunk) as $line) {
                $line = trim($line);

                if ($line === '') {
                    continue;
                }

                // "* " se od **tučně** liší mezerou za značkou
                if (preg_match('~^[-*•]\s+(.+)$~u', $line, $m)) {
                    $ordered = false;
                    $item = $m[1];
                } elseif (preg_match('~^\d+[.)]\s+(.+)$~u', $line, $m)) {
                    $ordered = true;
                    $item = $m[1];
                } else {
                    $item = null;
                }

                if ($item !== null) {
                    if ($current === null || $current['type'] !== 'list' || $current['ordered'] !== $ordered) {
                        $flush();
                        $current = ['type' => 'list', 'ordered' => $ordered, 'items' => []];
                    }

                    $current['items'][] = self::inlineHtml($item);

                    continue;
                }

                if ($current === null || $current['type'] !== 'paragraph') {
                    $flush();
                    $current = ['type' => 'paragraph', 'lines' => []];
                }

                $current['lines'][] = self::inlineHtml($line);
            }
        }

        $flush();

        return array_map(function (array $block) {
            if ($block['type'] === 'paragraph') {
                $block['html'] = implode('<br>', $block['lines']);
                unset($block['lines']);
            }

            return $block;
        }, $blocks);
    }

    /**
     * Formátování jednoho řádku. Text se nejdřív escapuje, teprve pak se
     * doplní značky – z administrace se do mailu záměrně nepouští HTML.
     */
    private static function inlineHtml(string $text): string
    {
        $html = e($text);

        $html = preg_replace(
            '~\*\*(.+?)\*\*~u',
            '<strong style="font-weight: 600; color: #1c1c1c;">$1</strong>',
            $html
        );

        // Poslední znak URL nesmí být interpunkce, jinak by se do odkazu
        // vzala i tečka na konci věty
        return preg_replace(
            '~(https?://[^\s<]*[^\s<.,;:!?)\]])~',
            '<a href="$1" style="color: #CA4136; text-decoration: none;">$1</a>',
            $html
        );
    }
}

