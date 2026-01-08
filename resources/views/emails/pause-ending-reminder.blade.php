<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.pause_ending_reminder.title', [], $locale) }}</title>
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
        .button-secondary { display: inline-block; background-color: #f3f4f6; color: #374151 !important; text-decoration: none; padding: 12px 24px; border-radius: 9999px; font-weight: 500; font-size: 14px; margin: 8px 4px; text-align: center; border: 1px solid #d1d5db; }
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
                                <div style="width: 64px; height: 64px; background-color: #3b82f6 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    ▶️
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">{{ __('emails.pause_ending_reminder.title', [], $locale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.pause_ending_reminder.subtitle', [], $locale) }}</p>
                            
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">{{ __('emails.common.subscription', [], $locale) }}</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Pause end date -->
                            <div class="info-box" style="background-color: #eef2ff !important; border: 1px solid #c7d2fe !important; border-left: 4px solid #6366f1 !important;" bgcolor="#eef2ff">
                                <h3 class="info-title" style="color: #3730a3;">🗓 {{ __('emails.pause_ending_reminder.pause_ends', [], $locale) }}</h3>
                                <p class="info-text" style="color: #3730a3; font-size: 18px; font-weight: 600;">
                                    {{ $subscription->paused_until_date->format($locale === 'cs' ? 'd.m.Y' : 'M d, Y') }}
                                </p>
                            </div>
                            
                            <!-- What happens next -->
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">📦 {{ __('emails.pause_ending_reminder.what_happens', [], $locale) }}</h3>
                                <p class="info-text" style="color: #047857;">
                                    @if($locale === 'cs')
                                    • <strong>Předplatné se automaticky obnoví</strong> - po skončení pauzy<br>
                                    • <strong>Platba bude stržena</strong> - dle vašeho nastaveného intervalu<br>
                                    • <strong>Další box přijde</strong> - při nejbližší rozesílce
                                    @else
                                    • <strong>Subscription will resume automatically</strong> - after pause ends<br>
                                    • <strong>Payment will be charged</strong> - according to your billing interval<br>
                                    • <strong>Next box will arrive</strong> - with the nearest shipment
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Options -->
                            <div class="info-box" style="background-color: #fefce8 !important; border: 1px solid #fde047 !important; border-left: 4px solid #eab308 !important;" bgcolor="#fefce8">
                                <h3 class="info-title" style="color: #854d0e;">💡 {{ __('emails.pause_ending_reminder.options_title', [], $locale) }}</h3>
                                <p class="info-text" style="color: #713f12;">
                                    @if($locale === 'cs')
                                    Pokud ještě nechcete obnovit předplatné, můžete:<br><br>
                                    • <strong>Prodloužit pauzu</strong> - přeskočit další rozesílky<br>
                                    • <strong>Zrušit předplatné</strong> - pokud již nemáte zájem
                                    @else
                                    If you're not ready to resume yet, you can:<br><br>
                                    • <strong>Extend the pause</strong> - skip more shipments<br>
                                    • <strong>Cancel subscription</strong> - if you no longer wish to continue
                                    @endif
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    {{ __('emails.pause_ending_reminder.manage_button', [], $locale) }}
                                </a>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                {{ __('emails.common.questions', [], $locale) }}
                                <a href="mailto:{{ $contactEmail }}" style="color: #e6305a; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>
                            
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
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link">{{ __('emails.common.my_subscription', [], $locale) }}</a>
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

