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
        'email',
        'order_id',
        'subscription_id',
        'review_type',
        'milestone',
        'tracking_token',
        'email_sent_at',
        'reminded_at',
        'clicked_at',
        'clicked_ip',
        'rating',
    ];

    protected $casts = [
        'email_sent_at' => 'datetime',
        'reminded_at' => 'datetime',
        'clicked_at' => 'datetime',
        'milestone' => 'integer',
        'rating' => 'integer',
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

    public function markAsClicked(?string $ip = null, ?int $rating = null): void
    {
        $this->update([
            'clicked_at' => $this->clicked_at ?? now(),
            'clicked_ip' => $ip,
            'rating' => $rating ?? $this->rating,
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
     * Dotaz omezený na jednu identitu. Hosté nemají účet, takže je nutné umět
     * párovat i podle e-mailu.
     */
    public static function forIdentity(?int $userId, ?string $email)
    {
        if (! $userId && ! $email) {
            return static::query()->whereRaw('1 = 0');
        }

        return static::query()->where(function ($query) use ($userId, $email) {
            if ($userId) {
                $query->orWhere('user_id', $userId);
            }

            if ($email) {
                $query->orWhere('email', $email);
            }
        });
    }

    /**
     * Kliknutí je jediný signál, který o spokojenosti máme - recenze žijí na
     * Googlu, takže nevíme, jestli ji člověk opravdu napsal. Kdo kliknul,
     * dostane pokoj na zadanou dobu.
     */
    public static function hasClickedSince(?int $userId, ?string $email, $since): bool
    {
        return static::forIdentity($userId, $email)
            ->whereNotNull('clicked_at')
            ->where('clicked_at', '>=', $since)
            ->exists();
    }

    /**
     * Poslední odeslaná žádost - aby na sebe dvě žádosti nenavazovaly příliš
     * rychle (např. objednávka a zásilka předplatného ve stejném týdnu).
     */
    public static function lastSentAt(?int $userId, ?string $email)
    {
        return static::forIdentity($userId, $email)->max('email_sent_at');
    }

    /**
     * Byla už žádost pro tenhle konkrétní milník odeslaná?
     */
    public static function existsForMilestone(
        ?int $userId,
        ?string $email,
        string $type,
        int $milestone,
        ?int $subscriptionId = null
    ): bool {
        $query = static::forIdentity($userId, $email)
            ->where('review_type', $type)
            ->where('milestone', $milestone);

        if ($subscriptionId) {
            $query->where('subscription_id', $subscriptionId);
        }

        return $query->exists();
    }
}
