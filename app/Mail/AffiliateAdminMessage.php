<?php

namespace App\Mail;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Collection;

class AffiliateAdminMessage extends LocalizedMailable
{
    public User $user;

    public string $subjectLine;

    /**
     * Tělo zprávy jako prostý text – šablona ho escapuje a rozdělí na odstavce.
     * Do mailu se z administrace záměrně nepouští HTML.
     */
    public string $bodyText;

    /**
     * Aktivní affiliate kódy partnera i s odkazy – doplňují se do patičky zprávy
     */
    public Collection $coupons;

    public string $affiliateUrl;

    /**
     * Odstavce těla zprávy
     *
     * @var array<int, string>
     */
    public array $paragraphs;

    public function __construct(User $partner, string $subjectLine, string $bodyText, ?string $locale = null)
    {
        $this->user = $partner;
        $this->subjectLine = $subjectLine;
        $this->bodyText = $bodyText;
        $this->paragraphs = static::splitParagraphs($bodyText);
        $this->setLocale($locale ?? EmailService::getLocaleFromUser($partner));
        $this->affiliateUrl = $this->localizedRouteFor('dashboard.affiliate');

        $this->coupons = $partner->affiliateCoupons()
            ->where('is_active', true)
            ->where('affiliate_code_enabled', true)
            ->with('affiliateLinks')
            ->get();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.affiliate-admin-message',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
