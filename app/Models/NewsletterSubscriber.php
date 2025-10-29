<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'source',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the newsletter subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the subscriber is a customer.
     */
    public function isCustomer(): bool
    {
        return $this->source === 'customer';
    }

    /**
     * Check if the subscriber is from form.
     */
    public function isFromForm(): bool
    {
        return $this->source === 'form';
    }
}

