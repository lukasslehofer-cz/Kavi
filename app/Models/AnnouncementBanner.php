<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AnnouncementBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_cs',
        'message_en',
        'title_cs',
        'title_en',
        'icon',
        'show_in_header',
        'show_in_checkout',
        'show_in_subscription_checkout',
        'active_from',
        'active_until',
        'is_active',
    ];

    protected $casts = [
        'active_from' => 'datetime',
        'active_until' => 'datetime',
        'is_active' => 'boolean',
        'show_in_header' => 'boolean',
        'show_in_checkout' => 'boolean',
        'show_in_subscription_checkout' => 'boolean',
    ];

    /**
     * Where a banner can be displayed (column name => admin label)
     */
    public const PLACEMENT_HEADER = 'show_in_header';
    public const PLACEMENT_CHECKOUT = 'show_in_checkout';
    public const PLACEMENT_SUBSCRIPTION_CHECKOUT = 'show_in_subscription_checkout';

    public const PLACEMENTS = [
        self::PLACEMENT_HEADER => 'Záhlaví webu',
        self::PLACEMENT_CHECKOUT => 'Pokladna – jednorázový nákup',
        self::PLACEMENT_SUBSCRIPTION_CHECKOUT => 'Pokladna – předplatné',
    ];

    /** Kratší varianta pro přehledovou tabulku */
    public const PLACEMENTS_SHORT = [
        self::PLACEMENT_HEADER => 'Záhlaví',
        self::PLACEMENT_CHECKOUT => 'Pokladna',
        self::PLACEMENT_SUBSCRIPTION_CHECKOUT => 'Předplatné',
    ];

    /**
     * Available icon types with their SVG paths
     */
    public const ICONS = [
        'check' => [
            'name' => 'Fajfka',
            'path' => 'M5 13l4 4L19 7',
        ],
        'gift' => [
            'name' => 'Dárek',
            'path' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7',
        ],
        'percent' => [
            'name' => 'Procenta',
            'path' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z',
        ],
        'info' => [
            'name' => 'Info',
            'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'star' => [
            'name' => 'Hvězda',
            'path' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
        ],
        'truck' => [
            'name' => 'Doprava',
            'path' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V4H5a2 2 0 00-2 2v9a1 1 0 001 1h1m8-1h6a1 1 0 001-1v-4l-3-4h-4v9z',
        ],
        'heart' => [
            'name' => 'Srdce',
            'path' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
        ],
        'tag' => [
            'name' => 'Štítek',
            'path' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
        ],
    ];

    /**
     * Scope to get currently active banners
     */
    public function scopeActive(Builder $query): Builder
    {
        $now = now();
        
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('active_from')
                    ->orWhere('active_from', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('active_until')
                    ->orWhere('active_until', '>=', $now);
            });
    }

    /**
     * Scope to banners shown at a given placement
     */
    public function scopeForPlacement(Builder $query, string $placement): Builder
    {
        // $placement se používá jako název sloupce - povolit jen známé hodnoty
        if (! array_key_exists($placement, self::PLACEMENTS)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where($placement, true);
    }

    /**
     * Get the currently active banner for a placement (most recently created if multiple)
     */
    public static function getCurrentFor(string $placement): ?self
    {
        return static::active()
            ->forPlacement($placement)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get the currently active banner for the site header
     */
    public static function getCurrent(): ?self
    {
        return static::getCurrentFor(self::PLACEMENT_HEADER);
    }

    /**
     * Get the message for the current locale
     */
    public function getMessage(string $locale = 'cs'): string
    {
        if ($locale === 'en' && !empty($this->message_en)) {
            return $this->message_en;
        }
        
        return $this->message_cs;
    }

    /**
     * Get the optional title for the current locale (checkout only)
     */
    public function getTitle(string $locale = 'cs'): ?string
    {
        if ($locale === 'en' && ! empty($this->title_en)) {
            return $this->title_en;
        }

        return ! empty($this->title_cs) ? $this->title_cs : null;
    }

    /**
     * Get the SVG path for the icon
     */
    public function getIconPath(): string
    {
        return self::ICONS[$this->icon]['path'] ?? self::ICONS['check']['path'];
    }

    /**
     * Check if banner is currently active (considering dates)
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->active_from && $this->active_from > $now) {
            return false;
        }

        if ($this->active_until && $this->active_until < $now) {
            return false;
        }

        return true;
    }

    /**
     * Get status label for admin display
     */
    public function getStatusLabel(): string
    {
        if (!$this->is_active) {
            return 'Vypnuto';
        }

        $now = now();

        if ($this->active_from && $this->active_from > $now) {
            return 'Naplánováno';
        }

        if ($this->active_until && $this->active_until < $now) {
            return 'Vypršelo';
        }

        return 'Aktivní';
    }

    /**
     * Get status color for admin display
     */
    public function getStatusColor(): string
    {
        $status = $this->getStatusLabel();
        
        return match($status) {
            'Aktivní' => 'green',
            'Naplánováno' => 'blue',
            'Vypršelo' => 'gray',
            default => 'red',
        };
    }
}

