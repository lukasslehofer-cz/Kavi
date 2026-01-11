<?php

namespace App\Listeners;

use App\Models\EmailLog;
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
            $headers = $message->getHeaders();
            
            // Extract recipient from Symfony Address objects
            $toAddresses = $message->getTo();
            $recipient = null;
            if (!empty($toAddresses)) {
                $firstTo = reset($toAddresses);
                $recipient = $firstTo->getAddress();
            }
            
            // Extract sender from Symfony Address objects
            $fromAddresses = $message->getFrom();
            $sender = null;
            if (!empty($fromAddresses)) {
                $firstFrom = reset($fromAddresses);
                $sender = $firstFrom->getAddress();
            }
            
            // Extract subject
            $subject = $message->getSubject() ?? 'No Subject';
            
            // Get mailable class from custom header
            $mailableClass = $this->getHeader($headers, 'X-Mailable-Class') ?? 'Unknown';
            
            // Get region from custom header
            $region = $this->getHeader($headers, 'X-Mailable-Locale');
            
            // Get related model IDs from custom headers
            $orderId = $this->getHeader($headers, 'X-Order-ID');
            $subscriptionId = $this->getHeader($headers, 'X-Subscription-ID');
            $userId = $this->getHeader($headers, 'X-User-ID');
            
            // Extract HTML body from message
            $bodyHtml = $this->extractHtmlBody($message);
            
            // Create email log
            EmailLog::create([
                'sent_at' => now(),
                'recipient' => $recipient,
                'sender' => $sender,
                'subject' => $subject,
                'mailable_class' => $mailableClass,
                'status' => 'sent',
                'body_html' => $bodyHtml,
                'order_id' => $orderId ? (int) $orderId : null,
                'subscription_id' => $subscriptionId ? (int) $subscriptionId : null,
                'user_id' => $userId ? (int) $userId : null,
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
    
    /**
     * Get a header value from the message headers.
     */
    private function getHeader($headers, string $name): ?string
    {
        if ($headers->has($name)) {
            $header = $headers->get($name);
            return $header ? $header->getBodyAsString() : null;
        }
        return null;
    }
    
    /**
     * Extract HTML body from Symfony Email message.
     */
    private function extractHtmlBody($message): ?string
    {
        try {
            $body = $message->getBody();
            
            if ($body === null) {
                return null;
            }
            
            // If body is a string, return it directly
            if (is_string($body)) {
                return $body;
            }
            
            // Try to get HTML body from message
            if (method_exists($message, 'getHtmlBody')) {
                $html = $message->getHtmlBody();
                if ($html) {
                    return $html;
                }
            }
            
            // Try to get text body as fallback
            if (method_exists($message, 'getTextBody')) {
                $text = $message->getTextBody();
                if ($text) {
                    return '<pre>' . htmlspecialchars($text) . '</pre>';
                }
            }
            
            // Last resort: convert body to string
            return (string) $body;
        } catch (\Exception $e) {
            Log::warning('Could not extract email body', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
