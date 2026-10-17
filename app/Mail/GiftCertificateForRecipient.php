<?php

namespace App\Mail;

use App\Helpers\DateFormat;
use App\Models\GiftPurchase;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Certifikát dárku pro obdarovaného, odeslaný ve dni zvoleném kupujícím
 */
class GiftCertificateForRecipient extends LocalizedMailable
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
            subject: $this->trans('gifts.mail.recipient_subject', ['giver' => $this->gift->giver_name]),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.gift-certificate-recipient',
            with: [
                'gift' => $this->gift,
                'validUntil' => $this->gift->valid_until ? DateFormat::full($this->gift->valid_until, $this->emailLocale) : null,
                'redeemUrl' => $this->gift->redeemUrl(),
                'monthsLabel' => $this->gift->months
                    ? trans_choice('gifts.months', $this->gift->months, ['count' => $this->gift->months], $this->emailLocale)
                    : null,
                'amountLabel' => \App\Helpers\CurrencyHelper::formatByCurrency((float) $this->gift->amount, $this->gift->currency, 0),
                'siteDisplay' => GiftPurchase::siteDisplayFor($this->gift->locale ?: 'cs'),
                'lineupNames' => $this->gift->isSubscription() ? $this->lineupNames() : '',
            ],
        );
    }

    /**
     * Pražírny aktuálního měsíce do odstavce "Co je KAVI" (bez nich se řádek vynechá)
     */
    private function lineupNames(): string
    {
        try {
            return app(\App\Services\CoffeeOfMonthService::class)->lineup()->roasteryNames();
        } catch (\Throwable $e) {
            return '';
        }
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
