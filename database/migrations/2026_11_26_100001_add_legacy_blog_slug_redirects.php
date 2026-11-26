<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Staré slugy, které vrací 404 a Google je zná (SEO fáze 1, bod 6c).
     * Cíl se dohledá podle aktuálního slug_cs, chybějící cíl se přeskočí.
     */
    private const REDIRECTS = [
        'bezkofeinova-myty-a-fakta-pro-rok-2026' => 'bezkofeinova-kava-myty-a-fakta-pro-rok-2026',
        'bezkofeinova-kava-myty-a-fakta-for-2026' => 'bezkofeinova-kava-myty-a-fakta-pro-rok-2026',
        'kava-bez-kofeinu-myty-a-fakta-pro-rok-2026' => 'bezkofeinova-kava-myty-a-fakta-pro-rok-2026',
        'kava-nebo-na-filtr' => 'kava-na-espresso-nebo-na-filtr',
    ];

    public function up(): void
    {
        foreach (self::REDIRECTS as $oldSlug => $targetSlug) {
            $postId = DB::table('posts')->where('slug_cs', $targetSlug)->value('id');

            if (! $postId) {
                $this->note("  Přeskočeno {$oldSlug}: článek {$targetSlug} neexistuje");
                continue;
            }

            // Starý slug, který je dnes slugem jiného článku, nepřesměrováváme
            $inUse = DB::table('posts')->where('slug_cs', $oldSlug)->orWhere('slug_en', $oldSlug)->exists();
            if ($inUse) {
                $this->note("  Přeskočeno {$oldSlug}: je to aktuální slug článku");
                continue;
            }

            DB::table('post_slug_redirects')->updateOrInsert(
                ['old_slug' => $oldSlug],
                ['post_id' => $postId, 'locale' => 'cs', 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    private function note(string $message): void
    {
        if (! app()->runningUnitTests()) {
            echo $message . "\n";
        }
    }

    public function down(): void
    {
        DB::table('post_slug_redirects')->whereIn('old_slug', array_keys(self::REDIRECTS))->delete();
    }
};
