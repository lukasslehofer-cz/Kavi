<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionPaymentSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription,
        public SubscriptionPayment $payment
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Platba předplatného úspěšně provedena ✓ - ' . ($this->subscription->subscription_number ?? 'KAVI.cz'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-payment-success',
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        
        // Attach invoice PDF if it exists
        if ($this->payment->invoice_pdf_path && \Storage::exists($this->payment->invoice_pdf_path)) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorage($this->payment->invoice_pdf_path)
                ->as('Faktura-' . ($this->subscription->subscription_number ?? 'predplatne') . '.pdf')
                ->withMime('application/pdf');
        }
        
        return $attachments;
    }
}

