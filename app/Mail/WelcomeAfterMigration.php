<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Password;

class WelcomeAfterMigration extends LocalizedMailable
{
    public User $user;
    public ?Subscription $subscription;
    public string $passwordSetUrl;

    public function __construct(User $user, ?Subscription $subscription = null, ?string $locale = null)
    {
        $this->user = $user;
        $this->subscription = $subscription ?? $user->activeSubscription;
        
        // Determine locale from subscription or user
        if ($this->subscription) {
            $this->setLocale($locale ?? EmailService::getLocaleFromSubscription($this->subscription));
        } else {
            $this->setLocale($locale ?? EmailService::getLocaleFromUser($user));
        }
        
        // Generate password reset token
        $token = Password::createToken($user);
        $this->passwordSetUrl = route('password.reset', [
            'token' => $token,
        ]) . '?email=' . urlencode($user->email);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->getFromAddress(),
            subject: $this->trans('emails.welcome_migration.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-after-migration',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
