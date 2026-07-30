<?php

namespace App\Console\Commands;

use App\Services\GoogleReviewsService;
use Illuminate\Console\Command;

class RefreshGoogleReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:refresh-google
                            {--locale= : Jen jeden profil (cs|en), jinak všechny nakonfigurované}
                            {--discover : Vypíše dostupné účty a provozovny místo stahování recenzí}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stáhne Google recenze z Business Profile API do cache';

    public function handle(GoogleReviewsService $service): int
    {
        if ($this->option('discover')) {
            return $this->discover($service);
        }

        $locales = $this->option('locale')
            ? [$this->option('locale')]
            : $service->configuredLocales();

        if (empty($locales)) {
            $this->error('Žádný profil není nakonfigurovaný. Doplň GOOGLE_REVIEWS_* proměnné do .env.');
            $this->line('Přístup k API se schvaluje ručně: https://developers.google.com/my-business/content/prereqs');

            return Command::FAILURE;
        }

        $failed = false;

        foreach ($locales as $locale) {
            $this->info("Profil {$locale}");

            $result = $service->refresh($locale);

            if (! $result['ok']) {
                $this->error('  '.$result['message']);
                $failed = true;

                continue;
            }

            $this->line("  Uloženo {$result['count']} recenzí, staženo ".$service->fetchedAt($locale)?->format('d.m.Y H:i'));
        }

        $this->newLine();
        $this->line('Data se podle pravidel Googlu smí zobrazovat nejdéle 30 dnů od stažení.');

        return $failed ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * account_id a location_id nejsou v rozhraní Googlu po ruce, tohle je vypíše.
     */
    protected function discover(GoogleReviewsService $service): int
    {
        $locale = $this->option('locale') ?: 'cs';

        $this->info("Hledám účty a provozovny dostupné tokenu profilu '{$locale}'...");

        try {
            $locations = $service->discoverLocations($locale);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        if (empty($locations)) {
            $this->warn('Nenalezena žádná provozovna. Má účet roli owner nebo manager na profilu?');

            return Command::FAILURE;
        }

        $this->table(
            ['account_id', 'location_id', 'název'],
            array_map(fn ($l) => [$l['account_id'], $l['location_id'], $l['title'] ?? $l['account_name']], $locations)
        );

        $this->line('Tyhle hodnoty patří do GOOGLE_REVIEWS_{CS,EN}_ACCOUNT_ID a _LOCATION_ID.');

        return Command::SUCCESS;
    }
}
