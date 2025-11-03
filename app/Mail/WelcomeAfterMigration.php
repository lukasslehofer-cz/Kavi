<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Password;

class WelcomeAfterMigration extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public ?Subscription $subscription;
    public string $passwordSetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, ?Subscription $subscription = null)
    {
        $this->user = $user;
        $this->subscription = $subscription ?? $user->activeSubscription;
        
        // Generate password reset token
        $token = Password::createToken($user);
        $this->passwordSetUrl = route('password.reset', [
            'token' => $token,
        ]) . '?email=' . urlencode($user->email);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '☕ Vítejte v novém Kavi obchodě!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-after-migration',
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
}


