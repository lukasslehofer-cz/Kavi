<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateLinkClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_link_id',
        'ip_address',
        'user_agent',
        'referrer',
        'session_id',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * Vztah k affiliate linku
     */
    public function affiliateLink()
    {
        return $this->belongsTo(AffiliateLink::class);
    }
}
