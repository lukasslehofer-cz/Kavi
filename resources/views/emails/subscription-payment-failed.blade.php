<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.subscription_payment_failed.title', [], $locale) }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100%; background-color: #bcbeb1; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #e5e6df; }
        .header { background-color: #1c1c1c; padding: 32px 40px 24px 40px; text-align: left; border-bottom: 2px solid #CA4136; }
        .logo { max-width: 100px !important; width: 100px !important; height: auto !important; display: block !important; margin: 0 !important; border: 0; outline: none; }
        .content { padding: 40px; color: #1c1c1c; }
        h1 { font-size: 24px; font-weight: 700; color: #1c1c1c; margin: 0 0 12px 0; line-height: 1.3; text-transform: uppercase; letter-spacing: 1px; }
        .subtitle { font-size: 15px; color: #5a5a5a; margin: 0 0 32px 0; font-weight: 400; }
        .info-box { background-color: #d5d7ca; border-radius: 0; padding: 20px; margin: 24px 0; }
        .info-title { font-size: 13px; font-weight: 700; color: #1c1c1c; margin: 0 0 12px 0; text-transform: uppercase; letter-spacing: 1px; }
        .info-text { font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 8px 0; }
        .highlight-box { background-color: #1c1c1c; color: #ffffff; padding: 20px; margin: 24px 0; }
        .accent-box { border-left: 4px solid #CA4136; background-color: #d5d7ca; padding: 20px; margin: 24px 0; }
        .success-box { border-left: 4px solid #4a6741; background-color: #d5d7ca; padding: 20px; margin: 24px 0; }
        .warning-box { border-left: 4px solid #b8860b; background-color: #d5d7ca; padding: 20px; margin: 24px 0; }
        .error-box { border-left: 4px solid #CA4136; background-color: #d5d7ca; padding: 20px; margin: 24px 0; }
        .button { display: inline-block; background-color: #CA4136; color: #ffffff !important; text-decoration: none; padding: 14px 32px; font-weight: 600; font-size: 13px; margin: 24px 0; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
        .footer { background-color: #d5d7ca; padding: 32px 40px; text-align: center; color: #5a5a5a; font-size: 13px; border-top: 1px solid #bcbeb1; }
        .footer-text { margin: 8px 0; font-weight: 400; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #CA4136; text-decoration: none; margin: 0 12px; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 600; }
        .step-number { display: inline-block; width: 24px; height: 24px; background-color: #CA4136; color: #ffffff; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700; margin-right: 12px; }
        @media only screen and (max-width: 600px) { 
            .content { padding: 24px !important; } 
            h1 { font-size: 20px !important; } 
            .header, .footer { padding: 24px !important; } 
            .logo { max-width: 80px !important; width: 80px !important; } 
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #bcbeb1; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" width="100%" style="width: 100%; max-width: 600px; background-color: #e5e6df !important;" bgcolor="#e5e6df">
                    
                    <tr>
                        <td class="header">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="{{ $siteName }}" class="logo" width="100">
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="content">
                            <h1>{{ __('emails.subscription_payment_failed.title', [], $locale) }}</h1>
                            <p class="subtitle">{{ __('emails.subscription_payment_failed.subtitle', [], $locale) }}</p>
                            
                            <!-- Subscription Number -->
                            <div class="highlight-box">
                                <div style="font-size: 11px; color: #bcbeb1; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">{{ __('emails.subscription_payment_failed.subscription_number', [], $locale) }}</div>
                                <div style="font-size: 24px; font-weight: 700; color: #ffffff; letter-spacing: 2px;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Problem -->
                            <div class="error-box">
                                <div class="info-title" style="color: #CA4136;">{{ $locale === 'cs' ? 'Co se stalo?' : 'What happened?' }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    @if($failureReason)
                                    <strong>{{ __('emails.subscription_payment_failed.reason', [], $locale) }}:</strong> {{ $failureReason }}<br><br>
                                    @endif
                                    @if($locale === 'cs')
                                    Platba za vaše předplatné se nezdařila. Nejčastější příčiny:<br><br>
                                    - Nedostatek prostředků na účtu/kartě<br>
                                    - Vypršela platnost karty<br>
                                    - Banka odmítla transakci<br>
                                    - Platební údaje se změnily
                                    @else
                                    Payment for your subscription failed. Common causes:<br><br>
                                    - Insufficient funds<br>
                                    - Expired card<br>
                                    - Transaction declined by bank<br>
                                    - Payment details have changed
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Next steps -->
                            <div class="warning-box">
                                <div class="info-title">{{ $locale === 'cs' ? 'Co je potřeba udělat?' : 'What needs to be done?' }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    @if($locale === 'cs')
                                    <strong>Máte {{ $subscription->grace_period_days ?? 7 }} dní</strong> na opravu platebních údajů.<br><br>
                                    Po této době bude vaše předplatné automaticky pozastaveno a neobdržíte další kávový box.
                                    @else
                                    <strong>You have {{ $subscription->grace_period_days ?? 7 }} days</strong> to update your payment details.<br><br>
                                    After this period, your subscription will be automatically paused and you will not receive your next coffee box.
                                    @endif
                                </p>
                            </div>
                            
                            <!-- How to fix -->
                            <div class="accent-box">
                                <div class="info-title">{{ $locale === 'cs' ? 'Jak to opravit?' : 'How to fix it?' }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    @if($locale === 'cs')
                                    <span class="step-number">1</span> <strong>Zkontrolujte platební údaje</strong><br>
                                    Ověřte, že vaše karta je platná a má dostatek prostředků.<br><br>
                                    <span class="step-number">2</span> <strong>Aktualizujte kartu</strong><br>
                                    Přihlaste se do svého účtu a aktualizujte platební údaje.<br><br>
                                    <span class="step-number">3</span> <strong>Zkuste platbu znovu</strong><br>
                                    Systém automaticky zkusí platbu znovu během 24 hodin.
                                    @else
                                    <span class="step-number">1</span> <strong>Check your payment details</strong><br>
                                    Verify that your card is valid and has sufficient funds.<br><br>
                                    <span class="step-number">2</span> <strong>Update your card</strong><br>
                                    Log into your account and update your payment details.<br><br>
                                    <span class="step-number">3</span> <strong>Retry payment</strong><br>
                                    The system will automatically retry the payment within 24 hours.
                                    @endif
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    {{ __('emails.subscription_payment_failed.update_payment', [], $locale) }}
                                </a>
                            </div>
                            
                            <!-- Support -->
                            <div class="success-box">
                                <div class="info-title" style="color: #4a6741;">{{ __('emails.welcome.need_help', [], $locale) }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    {{ __('emails.subscription_payment_failed.help_text', [], $locale) }}<br><br>
                                    <strong>{{ __('emails.welcome.email', [], $locale) }}:</strong> <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; font-weight: 600;">{{ $contactEmail }}</a>
                                </p>
                            </div>
                            
                            <p style="font-size: 13px; color: #5a5a5a; margin-top: 24px;">
                                {{ __('emails.common.regards', [], $locale) }},<br>
                                <strong style="color: #1c1c1c;">{{ __('emails.common.team', [], $locale) }}</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #1c1c1c;">{{ $siteName }}</strong><br>
                                {{ __('emails.common.tagline', [], $locale) }}
                            </p>
                            <div class="footer-links">
                                <a href="{{ route('home') }}" class="footer-link">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ route('products.index') }}" class="footer-link">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link">{{ __('emails.common.subscription', [], $locale) }}</a>
                            </div>
                            <p class="footer-text" style="font-size: 11px; margin-top: 16px; color: #76716c;">
                                {{ __('emails.common.copyright', ['year' => date('Y')], $locale) }}
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
