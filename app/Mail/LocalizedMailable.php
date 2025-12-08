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
     */
    public string $locale;

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
        $this->locale = $locale;
        $this->siteName = EmailService::getSiteName($locale);
        $this->contactEmail = EmailService::getContactEmail($locale);
    }

    /**
     * Get the "from" address based on locale
     */
    protected function getFromAddress(): Address
    {
        $from = EmailService::getFromAddress($this->locale);
        return new Address($from['address'], $from['name']);
    }

    /**
     * Build the message with locale-aware from address
     */
    public function build()
    {
        return $this->from($this->getFromAddress());
    }

    /**
     * Translate a key with the current locale
     */
    protected function trans(string $key, array $replace = []): string
    {
        return __($key, $replace, $this->locale);
    }
}

