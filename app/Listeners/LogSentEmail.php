<?php

namespace App\Listeners;

use App\Models\EmailLog;
use App\Mail\LocalizedMailable;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class LogSentEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        try {
            $message = $event->message;
            $mailable = $event->data['__laravel_notification'] ?? null;
            
            // Get the original mailable from the event
            $originalMailable = $event->data['mailable'] ?? null;
            
            // Extract recipient - get first TO address
            $to = $message->getTo();
            $recipient = !empty($to) ? array_key_first($to) : null;
            
            // Extract sender
            $from = $message->getFrom();
            $sender = !empty($from) ? array_key_first($from) : null;
            
            // Extract subject
            $subject = $message->getSubject() ?? 'No Subject';
            
            // Get mailable class name
            $mailableClass = $originalMailable ? get_class($originalMailable) : 'Unknown';
            
            // Extract related models and region from mailable
            $orderId = null;
            $subscriptionId = null;
            $userId = null;
            $region = null;
            
            if ($originalMailable) {
                // Try to extract order
                if (isset($originalMailable->order)) {
                    $orderId = $originalMailable->order->id ?? null;
                    $userId = $originalMailable->order->user_id ?? null;
                }
                
                // Try to extract subscription
                if (isset($originalMailable->subscription)) {
                    $subscriptionId = $originalMailable->subscription->id ?? null;
                    $userId = $userId ?? ($originalMailable->subscription->user_id ?? null);
                }
                
                // Try to extract user directly
                if (isset($originalMailable->user)) {
                    $userId = $userId ?? ($originalMailable->user->id ?? null);
                }
                
                // Try to get region from LocalizedMailable
                if ($originalMailable instanceof LocalizedMailable) {
                    $region = $originalMailable->emailLocale ?? null;
                }
            }
            
            // Create email log
            EmailLog::create([
                'sent_at' => now(),
                'recipient' => $recipient,
                'sender' => $sender,
                'subject' => $subject,
                'mailable_class' => $mailableClass,
                'status' => 'sent',
                'order_id' => $orderId,
                'subscription_id' => $subscriptionId,
                'user_id' => $userId,
                'region' => $region,
            ]);
        } catch (\Exception $e) {
            // Log the error but don't fail the email sending
            Log::error('Failed to log sent email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
