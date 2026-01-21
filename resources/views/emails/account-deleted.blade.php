<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.account_deleted.title', [], $locale) }}</title>
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
                                {{ __('emails.account_deleted.title', [], $locale) }}
                            </h1>
                            <p style="font-size: 14px; color: #76716C; margin: 0 0 40px 0; font-weight: 400; text-transform: uppercase; letter-spacing: 0.1em;">
                                {{ __('emails.account_deleted.subtitle', [], $locale) }}
                            </p>
                            
                            <!-- Deleted email -->
                            <div style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
                                <div style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ $locale === 'cs' ? 'Smazaný email' : 'Deleted email' }}
                                </div>
                                <div style="font-size: 28px; font-weight: 400; color: #1c1c1c; letter-spacing: -0.02em;">
                                    {{ $userEmail }}
                                </div>
                            </div>
                            
                            <!-- Confirmation -->
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #5a5a5a; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ $locale === 'cs' ? 'Potvrzeno' : 'Confirmed' }}
                                </div>
                                <p style="font-size: 15px; color: #5a5a5a; margin: 0;">
                                    {{ $locale === 'cs' ? 'Váš účet byl úspěšně smazán podle vaší žádosti.' : 'Your account has been successfully deleted as requested.' }}
                                </p>
                            </div>
                            
                            <!-- What was deleted -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ $locale === 'cs' ? 'Co bylo smazáno' : 'What was deleted' }}
                                </div>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.8; margin: 4px 0;">
                                    @if($locale === 'cs')
                                    <span style="color: #CA4136;">→</span> Všechna osobní data (jméno, telefon, adresa)<br>
                                    <span style="color: #CA4136;">→</span> Přihlašovací údaje<br>
                                    <span style="color: #CA4136;">→</span> Všechny uložené platební metody<br>
                                    <span style="color: #CA4136;">→</span> Aktivní předplatná (zrušena)<br>
                                    <span style="color: #CA4136;">→</span> Nezaplacené objednávky<br>
                                    <span style="color: #CA4136;">→</span> Odběr newsletteru
                                    @else
                                    <span style="color: #CA4136;">→</span> All personal data (name, phone, address)<br>
                                    <span style="color: #CA4136;">→</span> Login credentials<br>
                                    <span style="color: #CA4136;">→</span> All saved payment methods<br>
                                    <span style="color: #CA4136;">→</span> Active subscriptions (cancelled)<br>
                                    <span style="color: #CA4136;">→</span> Unpaid orders<br>
                                    <span style="color: #CA4136;">→</span> Newsletter subscription
                                    @endif
                                </p>
                            </div>
                            
                            <!-- What was kept -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ $locale === 'cs' ? 'Co jsme zachovali (zákonná povinnost)' : 'What we retained (legal requirement)' }}
                                </div>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.8; margin: 4px 0;">
                                    @if($locale === 'cs')
                                    <span style="color: #4a6741;">→</span> Faktury a platební historii (uchováváme 10 let dle zákona)<br>
                                    <span style="color: #4a6741;">→</span> Anonymizovaná data objednávek<br>
                                    <span style="color: #4a6741;">→</span> Historie předplatných (anonymizovaná)
                                    @else
                                    <span style="color: #4a6741;">→</span> Invoices and payment history (retained for 10 years by law)<br>
                                    <span style="color: #4a6741;">→</span> Anonymized order data<br>
                                    <span style="color: #4a6741;">→</span> Subscription history (anonymized)
                                    @endif
                                </p>
                                <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 16px 0 4px 0; font-style: italic;">
                                    {{ $locale === 'cs' ? 'Všechny zachované údaje jsou plně anonymizované a nelze z nich zjistit vaši totožnost.' : 'All retained data is fully anonymized and cannot be used to identify you.' }}
                                </p>
                            </div>
                            
                            <!-- Thank you -->
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ $locale === 'cs' ? 'Děkujeme' : 'Thank you' }}
                                </div>
                                <p style="font-size: 15px; color: #4a6741; margin: 0;">
                                    @if($locale === 'cs')
                                    Děkujeme, že jste byli součástí {{ $siteName }} komunity! Pokud si to někdy rozmyslíte, budeme rádi, když se k nám znovu vrátíte.
                                    @else
                                    Thank you for being part of the {{ $siteName }} community! If you ever change your mind, we'd love to have you back.
                                    @endif
                                </p>
                            </div>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 40px 0;">
                                <a href="{{ route('home') }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                    {{ $locale === 'cs' ? 'Přejít na ' . $siteName : 'Go to ' . $siteName }} →
                                </a>
                            </div>
                            
                            <!-- Features -->
                            <div style="margin: 40px 0 32px 0; padding-top: 24px; border-top: 1px solid #bcbeb1;">
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ __('emails.common.freshly_roasted', [], $locale) }}
                                </span>
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ $locale === 'cs' ? 'Vždy vítáni zpět' : 'Always welcome back' }}
                                </span>
                            </div>
                            
                            <!-- Help Text -->
                            <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ __('emails.common.questions', [], $locale) }}
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 14px; color: #5a5a5a; margin-top: 24px;">
                                {{ __('emails.common.regards', [], $locale) }},<br>
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
