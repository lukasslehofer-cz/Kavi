<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.welcome_migration.title', [], $locale) }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100%; background-color: #bcbeb1; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #e5e6df; }
        @media only screen and (max-width: 600px) { .content { padding: 32px 24px !important; } h1 { font-size: 26px !important; } .header, .footer { padding: 32px 24px !important; } }
        @media (prefers-color-scheme: dark) { body { background-color: #bcbeb1 !important; } .email-container { background-color: #e5e6df !important; } }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #bcbeb1; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" width="100%" style="width: 100%; max-width: 600px; background-color: #e5e6df !important;" bgcolor="#e5e6df">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1c1c1c; padding: 32px 40px; text-align: left;">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="{{ $siteName }}" width="80" style="max-width: 80px !important; width: 80px !important; height: auto !important; display: block !important; border: 0; outline: none;">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 48px 40px; color: #4a4a4a; background-color: #e5e6df;">
                            
                            <h1 style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 400; color: #1c1c1c; margin: 0 0 8px 0; line-height: 1.1; letter-spacing: -0.02em; text-transform: uppercase;">
                                {{ __('emails.welcome_migration.title', [], $locale) }}
                            </h1>
                            <p style="font-size: 14px; color: #76716C; margin: 0 0 40px 0; font-weight: 400; text-transform: uppercase; letter-spacing: 0.1em;">
                                {{ __('emails.welcome_migration.subtitle', [], $locale) }}
                            </p>
                            
                            <!-- Good news -->
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ $locale === 'cs' ? 'Skvělé zprávy' : 'Great news' }}
                                </div>
                                <p style="font-size: 15px; color: #4a6741; margin: 0; line-height: 1.6;">
                                    @if($locale === 'cs')
                                    Právě jsme úspěšně převedli Váš účet a předplatné do našeho nového systému.
                                    @else
                                    We have successfully migrated your account and subscription to our new system.
                                    @endif
                                </p>
                            </div>
                            
                            <!-- What's new -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ $locale === 'cs' ? 'Co to pro vás znamená' : 'What this means for you' }}
                                </div>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.8; margin: 4px 0;">
                                    @if($locale === 'cs')
                                    <span style="color: #4a6741;">→</span> Vaše stávající předplatné a způsob platby zůstávají aktivní<br>
                                    <span style="color: #4a6741;">→</span> Nové možnosti nákupu doplňkového sortimentu<br>
                                    <span style="color: #4a6741;">→</span> Nové varianty kávových boxů<br>
                                    <span style="color: #4a6741;">→</span> Stejné ceny jako dříve
                                    @else
                                    <span style="color: #4a6741;">→</span> Your existing subscription and payment method remain active<br>
                                    <span style="color: #4a6741;">→</span> New shopping options for additional products<br>
                                    <span style="color: #4a6741;">→</span> New coffee box variants<br>
                                    <span style="color: #4a6741;">→</span> Same prices as before
                                    @endif
                                </p>
                            </div>
                            
                            @if($subscription)
                            <!-- Subscription details -->
                            <div style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
                                <div style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ $locale === 'cs' ? 'Vaše předplatné' : 'Your subscription' }}
                                </div>
                                @php
                                $breakdown = \App\Helpers\SubscriptionPricing::forRecurringPayment($subscription);
                                @endphp
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ $locale === 'cs' ? 'Stav' : 'Status' }}:</td>
                                        <td style="padding: 8px 0; font-size: 14px; text-align: right;">
                                            @if($subscription->status === 'active')
                                                <span style="color: #4a6741;">{{ $locale === 'cs' ? 'Aktivní' : 'Active' }}</span>
                                            @elseif($subscription->status === 'paused')
                                                <span style="color: #CA4136;">{{ $locale === 'cs' ? 'Pozastaveno' : 'Paused' }}</span>
                                            @else
                                                <span style="color: #1c1c1c;">{{ ucfirst($subscription->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ $locale === 'cs' ? 'Cena' : 'Price' }}:</td>
                                        <td style="padding: 8px 0; font-size: 14px; color: #1c1c1c; text-align: right;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($breakdown->total, $breakdown->currency) }} / {{ $locale === 'cs' ? 'měsíc' : 'month' }}</td>
                                    </tr>
                                    @if($subscription->next_billing_date)
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ $locale === 'cs' ? 'Další platba' : 'Next payment' }}:</td>
                                        <td style="padding: 8px 0; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $subscription->next_billing_date->format('j. n. Y') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ $locale === 'cs' ? 'Frekvence' : 'Frequency' }}:</td>
                                        <td style="padding: 8px 0; font-size: 14px; color: #1c1c1c; text-align: right;">
                                            @if($subscription->frequency_months == 1)
                                                {{ $locale === 'cs' ? 'Měsíčně' : 'Monthly' }}
                                            @elseif($subscription->frequency_months == 3)
                                                {{ $locale === 'cs' ? 'Čtvrtletně' : 'Quarterly' }}
                                            @else
                                                {{ $locale === 'cs' ? 'Každých ' . $subscription->frequency_months . ' měsíců' : 'Every ' . $subscription->frequency_months . ' months' }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @endif
                            
                            <!-- Action required -->
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #CA4136; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ $locale === 'cs' ? 'Potřebujeme vaši pomoc' : 'We need your help' }}
                                </div>
                                <p style="font-size: 15px; color: #5a5a5a; margin: 0; line-height: 1.6;">
                                    @if($locale === 'cs')
                                    Abyste se mohli přihlásit do nového systému, potřebujete si nastavit heslo:
                                    @else
                                    To log in to the new system, you need to set a password:
                                    @endif
                                </p>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.8; margin: 12px 0 0 0;">
                                    <span style="color: #CA4136;">01</span> {{ $locale === 'cs' ? 'Klikněte na tlačítko níže' : 'Click the button below' }}<br>
                                    <span style="color: #CA4136;">02</span> {{ $locale === 'cs' ? 'Nastavte si nové heslo' : 'Set your new password' }}<br>
                                    <span style="color: #CA4136;">03</span> {{ $locale === 'cs' ? 'Přihlaste se a zkontrolujte své předplatné' : 'Log in and check your subscription' }}
                                </p>
                            </div>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 40px 0;">
                                <a href="{{ $passwordSetUrl }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                    {{ $locale === 'cs' ? 'Nastavit heslo' : 'Set password' }} →
                                </a>
                            </div>
                            
                            <!-- Alternative link -->
                            <div style="margin: 32px 0; padding: 16px 20px; background-color: #d5d7ca;">
                                <p style="font-size: 11px; color: #76716C; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.1em;">
                                    {{ $locale === 'cs' ? 'Nebo zkopírujte tento odkaz do prohlížeče' : 'Or copy this link to your browser' }}
                                </p>
                                <p style="font-size: 12px; color: #1c1c1c; word-break: break-all; margin: 0; font-family: monospace;">
                                    {{ $passwordSetUrl }}
                                </p>
                            </div>
                            
                            <!-- FAQ -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ $locale === 'cs' ? 'Časté otázky' : 'FAQ' }}
                                </div>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.8; margin: 4px 0;">
                                    @if($locale === 'cs')
                                    <strong>Musím zadávat platební kartu znovu?</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">Ne, vaše karta zůstává uložená a funguje dál automaticky.</span><br><br>
                                    <strong>Změní se mi datum platby?</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">Ne, datum další platby zůstává stejné.</span><br><br>
                                    <strong>Váš email pro přihlášení:</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">{{ $user->email }}</span><br><br>
                                    <strong>Jak dlouho je odkaz platný?</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">Odkaz je platný 7 dní. Pokud vyprší, použijte "Zapomenuté heslo".</span>
                                    @else
                                    <strong>Do I need to enter my payment card again?</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">No, your card remains saved and continues to work automatically.</span><br><br>
                                    <strong>Will my payment date change?</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">No, your next payment date remains the same.</span><br><br>
                                    <strong>Your login email:</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">{{ $user->email }}</span><br><br>
                                    <strong>How long is the link valid?</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">The link is valid for 7 days. If it expires, use "Forgot password".</span>
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Features -->
                            <div style="margin: 40px 0 32px 0; padding-top: 24px; border-top: 1px solid #bcbeb1;">
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ __('emails.common.freshly_roasted', [], $locale) }}
                                </span>
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ $locale === 'cs' ? 'Nový systém' : 'New system' }}
                                </span>
                            </div>
                            
                            <!-- Help Text -->
                            <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ __('emails.common.questions', [], $locale) }}
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 15px; color: #1c1c1c; line-height: 1.6; margin-top: 24px; text-align: center;">
                                <strong>{{ $locale === 'cs' ? 'Děkujeme za důvěru a těšíme se na mnoho dalších společných kávových zážitků!' : 'Thank you for your trust and we look forward to many more coffee experiences together!' }}</strong>
                            </p>
                            
                            <p style="font-size: 14px; color: #5a5a5a; margin-top: 24px;">
                                {{ __('emails.welcome.with_love', [], $locale) }},<br>
                                <span style="color: #1c1c1c;">{{ __('emails.common.team', [], $locale) }}</span>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #d5d7ca; padding: 40px; text-align: center; color: #5a5a5a; font-size: 12px;">
                            <p style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin: 0 0 4px 0;">{{ $siteName }}</p>
                            <p style="font-size: 12px; color: #5a5a5a; margin: 0 0 24px 0;">{{ __('emails.common.tagline', [], $locale) }}</p>
                            <div style="margin: 20px 0;">
                                <a href="{{ route('home') }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ route('products.index') }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ route('subscriptions.index') }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.subscription', [], $locale) }}</a>
                            </div>
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #bcbeb1; font-size: 11px; color: #76716C;">
                                <p style="margin: 0;">{{ __('emails.common.copyright', ['year' => date('Y')], $locale) }}</p>
                            </div>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
