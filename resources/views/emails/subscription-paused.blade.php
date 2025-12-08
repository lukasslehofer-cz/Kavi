<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.subscription_paused.title', [], $locale) }}</title>
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
                                <div style="width: 64px; height: 64px; background-color: #f59e0b !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    ⏸️
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">{{ __('emails.subscription_paused.title', [], $locale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.subscription_paused.subtitle', [], $locale) }}</p>
                            
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">{{ __('emails.common.subscription', [], $locale) }}</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            @if($subscription->paused_until_date)
                            <div class="info-box" style="background-color: #eef2ff !important; border: 1px solid #c7d2fe !important; border-left: 4px solid #6366f1 !important;" bgcolor="#eef2ff">
                                <h3 class="info-title" style="color: #3730a3;">🗓 {{ $locale === 'cs' ? 'Pauza aktivní do' : 'Paused until' }}</h3>
                                <p class="info-text" style="color: #3730a3;">
                                    @if($locale === 'cs')
                                    Pauza je nastavena do <strong>{{ $subscription->paused_until_date->format('d.m.Y') }}</strong>.
                                    @else
                                    Pause is set until <strong>{{ $subscription->paused_until_date->format('M d, Y') }}</strong>.
                                    @endif
                                </p>
                            </div>
                            @endif

                            <!-- Reason -->
                            @if($pauseReason === 'payment_failed')
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e;">💳 {{ $locale === 'cs' ? 'Důvod pozastavení' : 'Pause reason' }}</h3>
                                <p class="info-text" style="color: #78350f;">
                                    @if($locale === 'cs')
                                    Vaše předplatné bylo pozastaveno kvůli <strong>neúspěšné platbě</strong>.<br><br>
                                    Nebyli jsme schopni zpracovat platbu a uplynula lhůta pro opravu platebních údajů.
                                    @else
                                    Your subscription was paused due to a <strong>failed payment</strong>.<br><br>
                                    We were unable to process the payment and the deadline for updating payment details has passed.
                                    @endif
                                </p>
                            </div>
                            @else
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">✓ {{ $locale === 'cs' ? 'Důvod pozastavení' : 'Pause reason' }}</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    @if($locale === 'cs')
                                    Předplatné bylo pozastaveno <strong>na vaši žádost</strong>.<br><br>
                                    Během pauzy nebudete dostávat žádné kávové boxy ani vám nebudou účtovány platby.
                                    @else
                                    Subscription was paused <strong>at your request</strong>.<br><br>
                                    During the pause, you won't receive any coffee boxes and no payments will be charged.
                                    @endif
                                </p>
                            </div>
                            @endif
                            
                            <!-- What happens now -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📋 {{ $locale === 'cs' ? 'Co to znamená?' : 'What does this mean?' }}</h3>
                                <p class="info-text">
                                    @if($locale === 'cs')
                                    • <strong>Žádné další boxy</strong> - Nebudete dostávat kávové boxy<br>
                                    • <strong>Žádné platby</strong> - Nebudeme vám účtovat žádné poplatky<br>
                                    • <strong>Zachování nastavení</strong> - Vaše preference zůstávají uloženy<br>
                                    • <strong>Kdykoliv obnovit</strong> - Předplatné můžete znovu aktivovat
                                    @else
                                    • <strong>No more boxes</strong> - You won't receive coffee boxes<br>
                                    • <strong>No payments</strong> - No charges will be made<br>
                                    • <strong>Settings preserved</strong> - Your preferences remain saved<br>
                                    • <strong>Resume anytime</strong> - You can reactivate anytime
                                    @endif
                                </p>
                            </div>
                            
                            <!-- How to resume -->
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">🔄 {{ $locale === 'cs' ? 'Jak obnovit předplatné?' : 'How to resume subscription?' }}</h3>
                                <p class="info-text" style="color: #047857;">
                                    @if($pauseReason === 'payment_failed')
                                        @if($locale === 'cs')
                                        <strong>1. Aktualizujte platební údaje</strong><br>
                                        Přihlaste se do svého účtu a zadejte platnou kartu.<br><br>
                                        <strong>2. Obnovte předplatné</strong><br>
                                        Klikněte na tlačítko "Obnovit předplatné" ve svém účtu.<br><br>
                                        <strong>3. První box odešleme</strong><br>
                                        Po obnovení ihned začneme připravovat váš box!
                                        @else
                                        <strong>1. Update payment details</strong><br>
                                        Log in to your account and enter a valid card.<br><br>
                                        <strong>2. Resume subscription</strong><br>
                                        Click "Resume subscription" in your account.<br><br>
                                        <strong>3. We'll send your first box</strong><br>
                                        After resuming, we'll start preparing your box right away!
                                        @endif
                                    @else
                                        @if($locale === 'cs')
                                        Kdykoli se můžete vrátit a předplatné znovu aktivovat v sekci "Moje předplatné".<br><br>
                                        Po obnovení vám přijde první box podle vašeho zvoleneného intervalu.
                                        @else
                                        You can return anytime and reactivate your subscription in "My Subscription" section.<br><br>
                                        After resuming, your first box will arrive according to your chosen interval.
                                        @endif
                                    @endif
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    {{ __('emails.subscription_paused.manage_subscription', [], $locale) }}
                                </a>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                {{ $locale === 'cs' ? 'Budeme se těšit na váš návrat!' : 'We look forward to your return!' }} {{ __('emails.common.questions', [], $locale) }}
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
