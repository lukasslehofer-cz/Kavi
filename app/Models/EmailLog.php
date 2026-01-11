<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_at',
        'recipient',
        'sender',
        'subject',
        'mailable_class',
        'status',
        'error_message',
        'order_id',
        'subscription_id',
        'user_id',
        'region',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Get the user associated with the email log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with the email log.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the subscription associated with the email log.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get friendly name for the mailable class.
     */
    public function getMailableNameAttribute()
    {
        $class = class_basename($this->mailable_class);
        return preg_replace('/(?<!^)[A-Z]/', ' $0', $class);
    }
}
