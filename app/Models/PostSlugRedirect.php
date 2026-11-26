<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Starý slug blogového článku → 301 na aktuální slug (BlogController::show).
 */
class PostSlugRedirect extends Model
{
    protected $fillable = [
        'post_id',
        'old_slug',
        'locale',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
