<?php

namespace App\Models;

use App\Services\EmailService;
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
        'last_clicked_at',
        'clicked_ip',
        'rating',
        'google_review_found_at',
        'nudged_at',
    ];

    protected $casts = [
        'email_sent_at' => 'datetime',
        'reminded_at' => 'datetime',
        'clicked_at' => 'datetime',
        'last_clicked_at' => 'datetime',
        'google_review_found_at' => 'datetime',
        'nudged_at' => 'datetime',
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
     * Bez testovacích žádostí z email:test-review-request. Ty se poznají podle
     * prefixu tokenu a nemají zkreslovat statistiky ani spouštět připomínky.
     */
    public function scopeReal($query)
    {
        return $query->where('tracking_token', 'not like', 'test-%');
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
            // Po nalezené recenzi se poslední klik už neposouvá, jinak by
            // pozdější zvědavé kliknutí odtrhlo žádost od její recenze.
            'last_clicked_at' => $this->google_review_found_at ? $this->last_clicked_at : now(),
            'clicked_ip' => $ip,
            'rating' => $rating ?? $this->rating,
        ]);
    }

    /**
     * Každá doména má vlastní Google profil, vybírá se podle jazyka objednávky
     * nebo předplatného - stejně jako jazyk e-mailu. Web bez vlastního profilu
     * (kavibox.sk, dokud nemá odkaz v services.review_links) používá český.
     */
    public function profileLocale(): string
    {
        $locale = match (true) {
            $this->order !== null => EmailService::getLocaleFromOrder($this->order),
            $this->subscription !== null => EmailService::getLocaleFromSubscription($this->subscription),
            default => 'cs',
        };

        return config('services.review_links.'.$locale) ? $locale : 'cs';
    }

    /**
     * Jak dobře klik téhle žádosti sedí na recenzi vzniklou v daný čas.
     *
     * Vrací skóre (nižší = lepší) nebo null, když žádný klik do okna nepadne.
     * Klik před recenzí má vždy přednost před klikem v toleranci po ní: ta
     * pokrývá jen člověka, který po odeslání recenze kliknul v e-mailu znovu.
     */
    public function clickGapTo(\DateTimeInterface $reviewTime, int $windowHours, int $toleranceSeconds = 120): ?int
    {
        $window = $windowHours * 3600;
        $best = null;

        foreach (array_filter([$this->clicked_at, $this->last_clicked_at]) as $click) {
            // Přes timestampy, ať výsledek nezávisí na pásmu ani verzi Carbonu
            $secondsBefore = $reviewTime->getTimestamp() - $click->getTimestamp();

            if ($secondsBefore >= 0 && $secondsBefore <= $window) {
                $score = $secondsBefore;
            } elseif ($secondsBefore < 0 && -$secondsBefore <= $toleranceSeconds) {
                $score = $window - $secondsBefore;
            } else {
                continue;
            }

            $best = $best === null ? $score : min($best, $score);
        }

        return $best;
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
     * Kdo kliknul, dostane od dalších žádostí pokoj na zadanou dobu. Jestli
     * recenzi opravdu dopsal, říká až google_review_found_at - komu chybí,
     * tomu jde jedna výzva k dokončení (reviews:send).
     */
    public static function hasClickedSince(?int $userId, ?string $email, $since): bool
    {
        return static::forIdentity($userId, $email)
            ->whereNotNull('clicked_at')
            ->where('clicked_at', '>=', $since)
            ->exists();
    }

    /**
     * Výzva k dokončení chodí jednou. Dvě kliknuté žádosti téhož člověka
     * (objednávka a předplatné) by jinak znamenaly dvě výzvy.
     */
    public static function wasNudgedSince(?int $userId, ?string $email, $since): bool
    {
        return static::forIdentity($userId, $email)
            ->whereNotNull('nudged_at')
            ->where('nudged_at', '>=', $since)
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
