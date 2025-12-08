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
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f3f4f6 !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .email-container { max-width: 600px; margin: 0 auto; }
        .header { background-color: #111827 !important; padding: 32px; text-align: center; }
        .logo { max-width: 120px !important; width: 120px !important; height: auto !important; display: block !important; margin: 0 auto !important; }
        .content { padding: 40px 32px; background-color: #ffffff !important; }
        h1 { font-size: 28px; font-weight: 700; color: #111827; margin: 0 0 16px 0; line-height: 1.3; }
        .subtitle { font-size: 16px; color: #6b7280; margin: 0 0 32px 0; font-weight: 300; }
        .info-box { background-color: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin: 24px 0; }
        .info-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px 0; }
        .info-text { font-size: 14px; color: #4b5563; line-height: 1.6; margin: 8px 0; }
        .button { display: inline-block; background-color: #e6305a; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 9999px; font-weight: 600; font-size: 15px; margin: 24px 0; text-align: center; }
        .footer { background-color: #f9fafb !important; padding: 32px; text-align: center; }
        .footer-text { font-size: 14px; color: #6b7280; line-height: 1.6; margin: 8px 0; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #e6305a; text-decoration: none; margin: 0 12px; font-size: 14px; }
        @media only screen and (max-width: 600px) { .content { padding: 24px !important; } h1 { font-size: 24px !important; } .header, .footer { padding: 24px !important; } .logo { max-width: 100px !important; width: 100px !important; } }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6 !important; padding: 20px 0;" bgcolor="#f3f4f6">
        <tr>
            <td align="center">
                <!--[if mso]><table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"><tr><td><![endif]-->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" width="100%" style="width: 100%; max-width: 600px; background-color: #ffffff !important; border: 1px solid #e5e7eb !important; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);" bgcolor="#ffffff">
                    <tr>
                        <td class="header">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="{{ $siteName }}" class="logo" width="120" style="max-width: 120px !important; width: 120px !important; height: auto !important; display: block !important; margin: 0 auto !important; border: 0; outline: none;">
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; background-color: #ef4444 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    ⚠️
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">{{ __('emails.subscription_payment_failed.title', [], $locale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.subscription_payment_failed.subtitle', [], $locale) }}</p>
                            
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">{{ __('emails.subscription_payment_failed.subscription_number', [], $locale) }}</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Problem -->
                            <div class="info-box" style="background-color: #fee2e2 !important; border: 1px solid #fca5a5 !important; border-left: 4px solid #ef4444 !important;" bgcolor="#fee2e2">
                                <h3 class="info-title" style="color: #991b1b;">❌ {{ $locale === 'cs' ? 'Co se stalo?' : 'What happened?' }}</h3>
                                <p class="info-text" style="color: #7f1d1d;">
                                    @if($failureReason)
                                    <strong>{{ __('emails.subscription_payment_failed.reason', [], $locale) }}:</strong> {{ $failureReason }}<br><br>
                                    @endif
                                    @if($locale === 'cs')
                                    Platba za vaše předplatné se nezdařila. Nejčastější příčiny:
                                    <br><br>
                                    • Nedostatek prostředků na účtu/kartě<br>
                                    • Vypršela platnost karty<br>
                                    • Banka odmítla transakci<br>
                                    • Platební údaje se změnily
                                    @else
                                    Payment for your subscription failed. Common causes:
                                    <br><br>
                                    • Insufficient funds<br>
                                    • Expired card<br>
                                    • Transaction declined by bank<br>
                                    • Payment details have changed
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Next steps -->
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e;">⏰ {{ $locale === 'cs' ? 'Co je potřeba udělat?' : 'What needs to be done?' }}</h3>
                                <p class="info-text" style="color: #78350f;">
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
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">🔧 {{ $locale === 'cs' ? 'Jak to opravit?' : 'How to fix it?' }}</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    @if($locale === 'cs')
                                    <strong>1. Zkontrolujte platební údaje</strong><br>
                                    Ověřte, že vaše karta je platná a má dostatek prostředků.<br><br>
                                    <strong>2. Aktualizujte kartu</strong><br>
                                    Přihlaste se do svého účtu a aktualizujte platební údaje.<br><br>
                                    <strong>3. Zkuste platbu znovu</strong><br>
                                    Systém automaticky zkusí platbu znovu během 24 hodin.
                                    @else
                                    <strong>1. Check your payment details</strong><br>
                                    Verify that your card is valid and has sufficient funds.<br><br>
                                    <strong>2. Update your card</strong><br>
                                    Log into your account and update your payment details.<br><br>
                                    <strong>3. Retry payment</strong><br>
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
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">💬 {{ __('emails.welcome.need_help', [], $locale) }}</h3>
                                <p class="info-text" style="color: #047857;">
                                    {{ __('emails.subscription_payment_failed.help_text', [], $locale) }}<br><br>
                                    📧 <strong>{{ __('emails.welcome.email', [], $locale) }}:</strong> <a href="mailto:{{ $contactEmail }}" style="color: #e6305a;">{{ $contactEmail }}</a>
                                </p>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px;">
                                {{ __('emails.common.regards', [], $locale) }},<br>
                                <strong style="color: #111827;">{{ __('emails.common.team', [], $locale) }}</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #111827;">{{ $siteName }}</strong><br>
                                {{ __('emails.common.tagline', [], $locale) }}
                            </p>
                            <div class="footer-links">
                                <a href="{{ route('home') }}" class="footer-link">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ route('products.index') }}" class="footer-link">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link">{{ __('emails.common.subscription', [], $locale) }}</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                {{ __('emails.common.copyright', ['year' => date('Y')], $locale) }}
                            </p>
                        </td>
                    </tr>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
