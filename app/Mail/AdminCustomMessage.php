<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Vlastní zpráva napsaná v administraci, odeslaná v Kavi šabloně.
 *
 * Jazyk volí admin ručně (a podle něj se filtrují příjemci), proto je $locale
 * povinný parametr a neodvozuje se z dat uživatele jako u ostatních mailů.
 */
class AdminCustomMessage extends LocalizedMailable
{
    public User $user;

    public string $subjectLine;

    /**
     * Tělo zprávy jako prostý text s lehkým značkováním (**tučně**, odrážky).
     * Do mailu se z administrace záměrně nepouští HTML.
     */
    public string $bodyText;

    /**
     * Odstavce a seznamy těla zprávy, už vyrenderované do bezpečného HTML
     *
     * @var array<int, array<string, mixed>>
     */
    public array $blocks;

    public ?string $buttonLabel;

    public ?string $buttonUrl;

    public function __construct(
        User $user,
        string $subjectLine,
        string $bodyText,
        string $locale,
        ?string $buttonLabel = null,
        ?string $buttonUrl = null
    ) {
        $this->user = $user;
        $this->subjectLine = $subjectLine;
        $this->bodyText = $bodyText;
        $this->blocks = static::parseBlocks($bodyText);
        $this->buttonLabel = $buttonLabel;
        $this->buttonUrl = $buttonUrl;

        $this->setLocale($locale);
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
            view: 'emails.admin-custom-message',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
