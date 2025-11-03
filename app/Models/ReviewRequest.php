<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReviewRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'subscription_id',
        'review_type',
        'tracking_token',
        'email_sent_at',
        'clicked_at',
        'clicked_ip',
    ];

    protected $casts = [
        'email_sent_at' => 'datetime',
        'clicked_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Scopes
     */
    public function scopeClicked($query)
    {
        return $query->whereNotNull('clicked_at');
    }

    public function scopeNotClicked($query)
    {
        return $query->whereNull('clicked_at');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Helper methods
     */
    public function hasClicked(): bool
    {
        return !is_null($this->clicked_at);
    }

    public function markAsClicked(string $ip = null): void
    {
        $this->update([
            'clicked_at' => now(),
            'clicked_ip' => $ip,
        ]);
    }

    /**
     * Generate unique tracking token
     */
    public static function generateTrackingToken(): string
    {
        do {
            $token = Str::random(64);
        } while (static::where('tracking_token', $token)->exists());

        return $token;
    }

    /**
     * Check if user has clicked any review request
     */
    public static function userHasClickedAnyReview(int $userId): bool
    {
        return static::where('user_id', $userId)
            ->whereNotNull('clicked_at')
            ->exists();
    }

    /**
     * Get count of sent review requests for user by type
     */
    public static function getSentCountForUser(int $userId, string $type): int
    {
        return static::where('user_id', $userId)
            ->where('review_type', $type)
            ->whereNotNull('email_sent_at')
            ->count();
    }
}
