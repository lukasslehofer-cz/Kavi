<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\PostSlugRedirect;

class PostObserver
{
    /**
     * Při změně slugu uloží ten starý, aby z něj šlo přesměrovat (301).
     */
    public function updated(Post $post): void
    {
        foreach (\App\Helpers\Site::locales() as $locale) {
            $column = 'slug_' . $locale;
            if (! $post->wasChanged($column)) {
                continue;
            }

            $oldSlug = $post->getOriginal($column);

            if (! empty($oldSlug)) {
                PostSlugRedirect::updateOrCreate(
                    ['old_slug' => $oldSlug],
                    ['post_id' => $post->id, 'locale' => $locale]
                );
            }
        }

        // Slug, který se znovu stal aktuálním, už se nepřesměrovává
        $currentSlugs = array_filter(array_map(fn ($column) => $post->getAttribute($column), Post::slugColumns()));
        if ($currentSlugs) {
            PostSlugRedirect::whereIn('old_slug', $currentSlugs)->delete();
        }
    }
}
