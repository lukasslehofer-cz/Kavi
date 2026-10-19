<?php

namespace App\Mail;

use App\Helpers\DateFormat;
use App\Models\GiftPurchase;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\URL;

/**
 * Certifikát dárku pro kupujícího (PDF v příloze), hned po zaplacení
 */
class GiftCertificateForBuyer extends LocalizedMailable
{
    public GiftPurchase $gift;

    protected string $pdfPath;

    public function __construct(GiftPurchase $gift, string $pdfPath)
    {
        $this->gift = $gift;
        $this->pdfPath = $pdfPath;
        $this->setLocale($gift->locale ?: 'cs');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('gifts.mail.buyer_subject'),
        );
    }

    public function content(): Content
    {
        $gift = $this->gift;

        return new Content(
            view: 'emails.gift-certificate-buyer',
            with: [
                'gift' => $gift,
                'orderNumber' => $gift->order?->order_number,
                'validUntil' => $gift->valid_until ? DateFormat::full($gift->valid_until, $this->emailLocale) : null,
                'sendAt' => $gift->send_at ? DateFormat::full($gift->send_at, $this->emailLocale) : null,
                'redeemDisplay' => $gift->redeemDisplay(),
                'downloadUrl' => $this->siteUrl . URL::temporarySignedRoute(
                    $this->emailLocale === 'en' ? 'gifts.certificate.en' : 'gifts.certificate',
                    now()->addDays(60),
                    ['gift' => $gift->id],
                    false
                ),
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorage($this->pdfPath)
                ->as(app(\App\Services\GiftCertificateService::class)->fileName($this->gift))
                ->withMime('application/pdf'),
        ];
    }
}
