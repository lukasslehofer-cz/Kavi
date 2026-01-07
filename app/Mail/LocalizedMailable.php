<?php

namespace App\Mail;

use App\Services\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

abstract class LocalizedMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The locale for this email (cs or en)
     * Note: Named $emailLocale to avoid conflict with Laravel 11's Mailable::$locale
     */
    public string $emailLocale;

    /**
     * The site name based on locale
     */
    public string $siteName;

    /**
     * The contact email based on locale
     */
    public string $contactEmail;

    /**
     * Set the locale and related properties
     */
    protected function setLocale(string $locale): void
    {
        $this->emailLocale = $locale;
        $this->siteName = EmailService::getSiteName($locale);
        $this->contactEmail = EmailService::getContactEmail($locale);
        $this->mailer = EmailService::getMailer($locale);
    }

    /**
     * Get the "from" address based on locale
     */
    protected function getFromAddress(): Address
    {
        $from = EmailService::getFromAddress($this->emailLocale);
        return new Address($from['address'], $from['name']);
    }

    /**
     * Build the message with locale-aware from address and mailer
     */
    public function build()
    {
        return $this
            ->mailer(EmailService::getMailer($this->emailLocale))
            ->from($this->getFromAddress());
    }

    /**
     * Get the view data for the message.
     * Adds $locale for backward compatibility with Blade templates.
     */
    public function buildViewData(): array
    {
        $data = parent::buildViewData();
        
        // Add $locale as alias for $emailLocale for backward compatibility
        $data['locale'] = $this->emailLocale;
        
        return $data;
    }

    /**
     * Translate a key with the current locale
     */
    protected function trans(string $key, array $replace = []): string
    {
        return __($key, $replace, $this->emailLocale);
    }
}

