<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AdminOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $subscription;
    public $type;

    /**
     * Create a new message instance.
     * 
     * @param Order|Subscription $entity
     */
    public function __construct($entity)
    {
        if ($entity instanceof Order) {
            $this->order = $entity;
            $this->subscription = null;
            $this->type = 'order';
        } elseif ($entity instanceof Subscription) {
            $this->subscription = $entity;
            $this->order = null;
            $this->type = 'subscription';
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->type === 'order') {
            $subject = '[KAVI] Nová objednávka ' . $this->order->order_number;
        } else {
            $subject = '[KAVI] Nové předplatné ' . ($this->subscription->subscription_number ?? $this->subscription->id);
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-order-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Build the message with custom headers for email logging.
     */
    public function build()
    {
        return $this->withSymfonyMessage(function ($message) {
            $headers = $message->getHeaders();
            $headers->addTextHeader('X-Mailable-Class', static::class);
            $headers->addTextHeader('X-Mailable-Locale', 'cs'); // Admin notifications are always in Czech
            
            if ($this->order) {
                $headers->addTextHeader('X-Order-ID', (string) $this->order->id);
            }
            if ($this->subscription) {
                $headers->addTextHeader('X-Subscription-ID', (string) $this->subscription->id);
            }
        });
    }

    /**
     * Send notification to all admin users.
     * 
     * @param Order|Subscription $entity
     */
    public static function notifyAdmins($entity): void
    {
        try {
            $admins = User::where('is_admin', true)->get();
            
            if ($admins->isEmpty()) {
                \Log::warning('No admin users found for order notification');
                return;
            }

            foreach ($admins as $admin) {
                try {
                    Mail::to($admin->email)->send(new self($entity));
                    
                    \Log::info('Admin order notification sent', [
                        'admin_email' => $admin->email,
                        'type' => $entity instanceof Order ? 'order' : 'subscription',
                        'id' => $entity->id,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send admin order notification', [
                        'admin_email' => $admin->email,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to notify admins about order', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}

