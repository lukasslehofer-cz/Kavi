<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        // Generate password reset URL
        // If password.reset route doesn't exist, use a fallback URL
        try {
            $url = url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        } catch (\Exception $e) {
            // Fallback URL for testing or custom implementation
            $url = url('/reset-password/' . $this->token . '?email=' . urlencode($notifiable->getEmailForPasswordReset()));
        }

        return (new MailMessage)
            ->subject('Reset hesla - KAVI.cz')
            ->view('emails.reset-password', [
                'url' => $url,
                'user' => $notifiable,
                'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')
            ]);
    }
}

