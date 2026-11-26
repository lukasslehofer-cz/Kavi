<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\BacksUpPostContent;
use App\Models\Post;
use Illuminate\Console\Command;

/**
 * SEO fáze 1b, bod 8: obalí existující slova v textu článků odkazem na jiný článek.
 *
 * - Jen content_cs, text článku se nemění (jen se kolem slov přidá <a>).
 * - Odkaz jde na první vyhovující výskyt v <p>/<li>, nikdy do nadpisu (ani otázky
 *   ve FAQ, ty jsou nadpisy), do existujícího odkazu ani do CTA bloku (<blockquote>).
 * - Max. 1 odkaz na každý cíl v jednom článku; když už tam je, pravidlo se přeskočí.
 *   Díky tomu je opakované spuštění bez účinku.
 */
class AddBlogInternalLinks extends Command
{
    use BacksUpPostContent;

    protected $signature = 'blog:add-internal-links
                            {--force : Skutečně zapsat; bez tohoto přepínače jde jen o výpis}';

    protected $description = 'Vloží interní odkazy mezi blogovými články (SEO fáze 1b, bod 8)';

    /**
     * [zdrojový slug_cs, fráze ze zadání, cílový slug_cs, kontext, anchor]
     *
     * kontext: úryvek HTML, ve kterém fráze leží, když první výskyt není ten zamýšlený
     * anchor:  skutečně obalený text, když přesná fráze v textu není (uvede se v reportu)
     */
    private const RULES = [
        ['vyberova-kava-vs-klasicka-kava', 'metodami zpracování', 'metody-zpracovani-kavy', null, null],
        ['vyberova-kava-vs-klasicka-kava', 'světle pražené kávy', 'svetle-vs-tmave-prazeni-kavy', null, null],
        ['vyberova-kava-vs-klasicka-kava', 'kyselost', 'chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit', null, null],
        ['bezkofeinova-kava-myty-a-fakta-pro-rok-2026', 'stejné množství kofeinu', 'kolik-kofeinu-ma-kava', null, null],
        ['chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit', 'promytou metodou', 'metody-zpracovani-kavy', null, null],
        ['chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit', 'výběrové kávy', 'vyberova-kava-vs-klasicka-kava', null, null],
        ['chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit', 'zrna pražená před měsíci', 'jak-skladovat-kavu-cerstvost', null, null],
        ['arabica-vs-robusta-rozdil', 'výběrových káv', 'vyberova-kava-vs-klasicka-kava', null, null],
        ['arabica-vs-robusta-rozdil', 'obsahem kofeinu', 'kolik-kofeinu-ma-kava', null, null],
        ['arabica-vs-robusta-rozdil', 'způsob zpracování', 'metody-zpracovani-kavy', null, null],
        ['zrnkova-vs-mleta-kava', 'v čerstvosti', 'jak-skladovat-kavu-cerstvost', 'v <strong>čerstvosti</strong>', 'čerstvosti'],
        ['zrnkova-vs-mleta-kava', 'Správná hrubost mletí', 'jak-pripravit-kavu-doma', null, null],
        ['zrnkova-vs-mleta-kava', 'espresso', 'jak-pripravit-espresso', null, null],
        ['jak-pripravit-kavu-doma', 'espresso kávovaru', 'jak-pripravit-espresso', null, null],
        ['jak-pripravit-kavu-doma', 'čerstvá výběrová zrna', 'vyberova-kava-vs-klasicka-kava', null, null],
        ['jak-pripravit-kavu-doma', 'melte až těsně před přípravou', 'zrnkova-vs-mleta-kava', null, null],
        ['svetle-vs-tmave-prazeni-kavy', 'temně hořké espresso', 'kava-na-espresso-nebo-na-filtr', null, null],
        ['svetle-vs-tmave-prazeni-kavy', 'filtrované metody (V60, Aeropress)', 'jak-pripravit-kavu-doma', null, null],
        ['svetle-vs-tmave-prazeni-kavy', 'neznamená více kofeinu', 'kolik-kofeinu-ma-kava', null, null],
        ['svetle-vs-tmave-prazeni-kavy', 'kyselost', 'chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit', null, null],
        ['jak-skladovat-kavu-cerstvost', 'celá zrna vydrží čerstvá výrazně déle než mletá', 'zrnkova-vs-mleta-kava', null, null],
        ['metody-zpracovani-kavy', 'stupeň pražení', 'svetle-vs-tmave-prazeni-kavy', null, null],
        ['jak-pripravit-espresso', 'Čerstvá zrna', 'jak-skladovat-kavu-cerstvost', null, null],
        ['jak-pripravit-espresso', 'Espresso je kyselé', 'chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit', null, null],
        ['kava-na-espresso-nebo-na-filtr', 'kyselost', 'chutovy-profil-kavy-proc-vas-kysela-kava-nemusi-desit', null, null],
        ['kava-na-espresso-nebo-na-filtr', 'jemně namletou kávou', 'zrnkova-vs-mleta-kava', null, null],
        ['kolik-kofeinu-ma-kava', 'espresso', 'jak-pripravit-espresso', null, null],
    ];

    public function handle(): int
    {
        $force = (bool) $this->option('force');

        if (! $force) {
            $this->warn('DRY-RUN – nic se nezapisuje. Pro zápis přidej --force.');
        }

        $posts = Post::all()->keyBy('slug_cs');
        $contents = $posts->map(fn (Post $post) => (string) $post->content_cs)->all();

        $rows = [];
        $added = 0;

        foreach (self::RULES as [$source, $phrase, $target, $context, $anchor]) {
            if (! isset($contents[$source]) || ! isset($contents[$target])) {
                $rows[] = [$source, $phrase, $target, 'článek nenalezen', ''];
                continue;
            }

            $html = $contents[$source];

            if ($this->linksTo($html, $target)) {
                $rows[] = [$source, $phrase, $target, 'přeskočeno: už odkazuje', ''];
                continue;
            }

            $match = $this->findOccurrence($html, $phrase, $context, $anchor);

            if (! $match) {
                $rows[] = [$source, $phrase, $target, 'NENALEZENO', ''];
                continue;
            }

            [$offset, $text] = $match;
            $link = '<a href="/blog/' . $target . '">' . $text . '</a>';
            $contents[$source] = substr_replace($html, $link, $offset, strlen($text));
            $added++;

            $status = $text === $phrase ? 'přidáno' : 'přidáno, jiná fráze: „' . $text . '“';
            $rows[] = [$source, $phrase, $target, $status, $this->snippet($html, $offset, strlen($text))];
        }

        $this->table(['Zdroj', 'Fráze', 'Cíl', 'Stav', 'Úryvek'], $rows);

        $original = [];
        foreach ($contents as $slug => $html) {
            if ($html !== (string) $posts[$slug]->content_cs) {
                $original[$posts[$slug]->id] = ['content_cs' => (string) $posts[$slug]->content_cs];
            }
        }

        $this->line($added . ' nových odkazů v ' . count($original) . ' článcích.');

        if ($added === 0 || ! $force) {
            if (! $force && $added > 0) {
                $this->warn('Nic se neuložilo. Pro zápis spusť znovu s --force.');
            }

            return self::SUCCESS;
        }

        $backupPath = $this->backupPostContent('add-internal-links', $original);

        foreach ($original as $postId => $_) {
            $post = $posts->firstWhere('id', $postId);
            $post->content_cs = $contents[$post->slug_cs];
            $post->save();
        }

        $this->info('Uloženo. Záloha původního obsahu: ' . $backupPath);

        return self::SUCCESS;
    }

    /**
     * Odkazuje už HTML na daný článek (relativně i absolutně)?
     */
    private function linksTo(string $html, string $slug): bool
    {
        $pattern = '~href\s*=\s*["\'](?:https?://(?:www\.)?(?:kavi\.cz|kavibox\.com))?/blog/' . preg_quote($slug, '~') . '/?["\'#?]~i';

        return (bool) preg_match($pattern, $html);
    }

    /**
     * První povolený výskyt fráze: [bajtový offset, obalený text] nebo null.
     */
    private function findOccurrence(string $html, string $phrase, ?string $context, ?string $anchor): ?array
    {
        $text = $anchor ?? $phrase;
        $quoted = preg_quote($text, '~');

        if ($context !== null) {
            $offsets = [];
            $from = 0;
            while (($pos = strpos($html, $context, $from)) !== false) {
                $inner = strpos($context, $text);
                if ($inner !== false) {
                    $offsets[] = $pos + $inner;
                }
                $from = $pos + 1;
            }
        } else {
            // Celé slovo, aby se neobalila jen část delšího slova (kyselost × kyselosti)
            preg_match_all('~(?<![\p{L}\p{N}])' . $quoted . '(?![\p{L}\p{N}])~u', $html, $m, PREG_OFFSET_CAPTURE);
            $offsets = array_column($m[0], 1);
        }

        foreach ($offsets as $offset) {
            if ($this->isAllowedPosition($html, $offset)) {
                return [$offset, $text];
            }
        }

        return null;
    }

    /**
     * Leží pozice v textu odstavce nebo položky seznamu, mimo nadpis, odkaz a CTA blok?
     */
    private function isAllowedPosition(string $html, int $offset): bool
    {
        $before = substr($html, 0, $offset);

        // Uvnitř HTML značky (např. v atributu)
        if (strrpos($before, '<') > (int) strrpos($before, '>')) {
            return false;
        }

        foreach (['a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote'] as $tag) {
            if ($this->isOpen($before, $tag)) {
                return false;
            }
        }

        return $this->isOpen($before, 'p') || $this->isOpen($before, 'li');
    }

    private function isOpen(string $before, string $tag): bool
    {
        preg_match_all('~<' . $tag . '(?=[\s>/])~i', $before, $open, PREG_OFFSET_CAPTURE);
        preg_match_all('~</' . $tag . '\s*>~i', $before, $close, PREG_OFFSET_CAPTURE);

        $lastOpen = $open[0] ? end($open[0])[1] : -1;
        $lastClose = $close[0] ? end($close[0])[1] : -1;

        return $lastOpen > $lastClose;
    }

    private function snippet(string $html, int $offset, int $length): string
    {
        $start = max(0, $offset - 70);
        $chunk = mb_strcut($html, $start, $length + 140, 'UTF-8');
        $plain = preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($chunk), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        return '…' . trim($plain) . '…';
    }
}
