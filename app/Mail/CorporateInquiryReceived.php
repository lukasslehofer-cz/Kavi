<?php

namespace App\Mail;

use App\Models\CorporateInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Poptávka firemních dárků pro KAVI (info@kavi.cz). Odpověď jde rovnou zákazníkovi.
 * Zákazníkovi se potvrzení neposílá (handoff Pro firmy, kap. 5).
 */
class CorporateInquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CorporateInquiry $inquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Poptávka firemních dárků: ' . $this->inquiry->company . ' (' . $this->inquiry->quantityLabel() . ' ks)',
            replyTo: [new Address($this->inquiry->email, $this->inquiry->name)],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.corporate-inquiry');
    }

    public function build()
    {
        return $this->withSymfonyMessage(function ($message) {
            $headers = $message->getHeaders();
            $headers->addTextHeader('X-Mailable-Class', static::class);
            $headers->addTextHeader('X-Mailable-Locale', 'cs');
        });
    }
}
