<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Jednorázové získání refresh tokenu pro Business Profile API.
 *
 * Service account použít nelze, API vyžaduje souhlas konkrétního uživatele.
 * Token se získá jednou, uloží do .env a dál si jím cron obnovuje access token.
 *
 * Tenhle krok NEVYŽADUJE schválený přístup k API - consent flow běží nezávisle.
 * Ověřit, že token opravdu čte recenze, půjde až po schválení.
 */
class GoogleReviewsAuth extends Command
{
    protected $signature = 'reviews:google-auth
                            {--redirect=http://localhost : Redirect URI, musí přesně odpovídat tomu v Cloud Console}';

    protected $description = 'Provede OAuth souhlas a vypíše refresh token pro Google Business Profile';

    protected const SCOPE = 'https://www.googleapis.com/auth/business.manage';

    public function handle(): int
    {
        $clientId = config('services.google_reviews.client_id');
        $clientSecret = config('services.google_reviews.client_secret');
        $redirect = $this->option('redirect');

        if (blank($clientId) || blank($clientSecret)) {
            $this->error('Chybí GOOGLE_REVIEWS_CLIENT_ID nebo GOOGLE_REVIEWS_CLIENT_SECRET v .env.');
            $this->newLine();
            $this->line('Vytvoř je v Google Cloud Console → APIs & Services → Credentials →');
            $this->line('Create credentials → OAuth client ID → typ "Web application".');
            $this->line("Do Authorized redirect URIs přidej přesně: {$redirect}");

            return Command::FAILURE;
        }

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirect,
            'response_type' => 'code',
            'scope' => self::SCOPE,
            // offline + consent = Google vystaví refresh token i při opakované autorizaci
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        $this->info('1. Otevři tuhle adresu v prohlížeči, kde jsi přihlášený jako vlastník profilu:');
        $this->newLine();
        $this->line($authUrl);
        $this->newLine();
        $this->line('2. Odsouhlas přístup. Google tě pak přesměruje na '.$redirect.'?code=...');
        $this->line('   Stránka se nenačte, to nevadí - potřebujeme jen hodnotu code z adresního řádku.');
        $this->newLine();

        $code = $this->ask('3. Vlož sem hodnotu parametru code');

        if (blank($code)) {
            $this->error('Nic jsi nevložil.');

            return Command::FAILURE;
        }

        // Prohlížeč parametr URL-enkóduje, tohle to vrátí zpět.
        $code = urldecode(trim($code));

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirect,
            'grant_type' => 'authorization_code',
        ]);

        if ($response->failed()) {
            $this->error('Výměna kódu selhala ('.$response->status().'):');
            $this->line('  '.$response->body());
            $this->newLine();
            $this->line('Nejčastější příčiny:');
            $this->line('  - redirect_uri nesouhlasí přesně s tím v Cloud Console');
            $this->line('  - kód už byl použitý nebo vypršel (platí pár minut), zopakuj od kroku 1');

            return Command::FAILURE;
        }

        $refreshToken = $response->json('refresh_token');

        if (blank($refreshToken)) {
            $this->error('Google refresh token nevrátil.');
            $this->line('Stává se to, když už byl souhlas jednou udělený. Odeber přístup na');
            $this->line('https://myaccount.google.com/permissions a spusť příkaz znovu.');

            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('Hotovo. Vlož do .env:');
        $this->newLine();
        $this->line('GOOGLE_REVIEWS_REFRESH_TOKEN='.$refreshToken);
        $this->newLine();
        $this->line('Token nemá pevnou platnost, zneplatní ho až 6 měsíců nepoužití nebo odebrání');
        $this->line('přístupu. Denní cron ho drží živý.');
        $this->newLine();
        $this->warn('Consent screen musí být v režimu "In production". V "Testing" vyprší token za 7 dnů.');
        $this->newLine();
        $this->line('Dál: php artisan reviews:refresh-google --discover');

        return Command::SUCCESS;
    }
}
