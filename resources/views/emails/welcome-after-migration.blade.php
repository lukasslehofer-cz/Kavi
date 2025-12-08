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
                                <div style="width: 64px; height: 64px; background-color: #10b981 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    🚀
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">{{ __('emails.welcome_migration.title', [], $locale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.welcome_migration.subtitle', [], $locale) }}</p>
                            
                            <!-- Good news -->
                            <div class="info-box" style="background-color: #d1fae5 !important; border: 1px solid #6ee7b7 !important; border-left: 4px solid #10b981 !important;" bgcolor="#d1fae5">
                                <h3 class="info-title" style="color: #065f46;">✅ {{ $locale === 'cs' ? 'Skvělé zprávy!' : 'Great news!' }}</h3>
                                <p class="info-text" style="color: #047857;">
                                    @if($locale === 'cs')
                                    Právě jsme <strong>úspěšně převedli</strong> Váš účet a předplatné do našeho nového, modernějšího systému. Co to pro Vás znamená?<br><br>
                                    ✓ <strong>Vaše stávající předplatné a způsob platby zůstávají aktivní</strong><br>
                                    ✓ <strong>Nové možnosti nákupu</strong> doplňkového sortimentu<br>
                                    ✓ <strong>Nové varianty kávových boxů</strong><br>
                                    ✓ <strong>Stejné ceny</strong> jako dříve
                                    @else
                                    We have <strong>successfully migrated</strong> your account and subscription to our new, more modern system. What does this mean for you?<br><br>
                                    ✓ <strong>Your existing subscription and payment method remain active</strong><br>
                                    ✓ <strong>New shopping options</strong> for additional products<br>
                                    ✓ <strong>New coffee box variants</strong><br>
                                    ✓ <strong>Same prices</strong> as before
                                    @endif
                                </p>
                            </div>

                            @if($subscription)
                            <!-- Subscription details -->
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">📦 {{ $locale === 'cs' ? 'Vaše předplatné' : 'Your subscription' }}</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    <strong>{{ $locale === 'cs' ? 'Stav' : 'Status' }}:</strong> 
                                    @if($subscription->status === 'active')
                                        <span style="color: #059669;">✓ {{ $locale === 'cs' ? 'Aktivní' : 'Active' }}</span>
                                    @elseif($subscription->status === 'paused')
                                        <span style="color: #d97706;">⏸ {{ $locale === 'cs' ? 'Pozastaveno' : 'Paused' }}</span>
                                    @else
                                        {{ ucfirst($subscription->status) }}
                                    @endif
                                    <br>
                                    @php
                                    $activeDiscount = ($subscription->discount_amount > 0 && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0)) ? $subscription->discount_amount : 0;
                                    $displayPrice = ($subscription->configured_price ?? 0) - $activeDiscount;
                                    @endphp
                                    <strong>{{ $locale === 'cs' ? 'Cena' : 'Price' }}:</strong> {{ \App\Helpers\CurrencyHelper::formatByCurrency($displayPrice, $subscription->currency, 0) }} / {{ $locale === 'cs' ? 'měsíc' : 'month' }}<br>
                                    
                                    @if($subscription->next_billing_date)
                                    <strong>{{ $locale === 'cs' ? 'Další platba' : 'Next payment' }}:</strong> {{ $subscription->next_billing_date->format($locale === 'cs' ? 'd.m.Y' : 'M d, Y') }}<br>
                                    @endif
                                    
                                    <strong>{{ $locale === 'cs' ? 'Frekvence' : 'Frequency' }}:</strong> 
                                    @if($subscription->frequency_months == 1)
                                        {{ $locale === 'cs' ? 'Měsíčně' : 'Monthly' }}
                                    @elseif($subscription->frequency_months == 3)
                                        {{ $locale === 'cs' ? 'Čtvrtletně' : 'Quarterly' }}
                                    @else
                                        {{ $locale === 'cs' ? 'Každých ' . $subscription->frequency_months . ' měsíců' : 'Every ' . $subscription->frequency_months . ' months' }}
                                    @endif
                                </p>
                            </div>
                            @endif
                            
                            <!-- Action required -->
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e;">🔑 {{ $locale === 'cs' ? 'Potřebujeme Vaši pomoc' : 'We need your help' }}</h3>
                                <p class="info-text" style="color: #78350f;">
                                    @if($locale === 'cs')
                                    Abyste se mohli přihlásit do nového systému, potřebujete si <strong>nastavit heslo</strong>. Je to jednoduché a zabere to jen chvíli:<br><br>
                                    <strong>1.</strong> Klikněte na tlačítko níže<br>
                                    <strong>2.</strong> Nastavte si nové heslo<br>
                                    <strong>3.</strong> Přihlaste se a zkontrolujte své předplatné
                                    @else
                                    To log in to the new system, you need to <strong>set a password</strong>. It's simple and only takes a moment:<br><br>
                                    <strong>1.</strong> Click the button below<br>
                                    <strong>2.</strong> Set your new password<br>
                                    <strong>3.</strong> Log in and check your subscription
                                    @endif
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ $passwordSetUrl }}" class="button">
                                    {{ $locale === 'cs' ? 'Nastavit heslo' : 'Set password' }}
                                </a>
                            </div>
                            
                            <p style="text-align: center; color: #6b7280; font-size: 13px; margin-top: 16px;">
                                {{ $locale === 'cs' ? 'Nebo zkopírujte tento odkaz do prohlížeče:' : 'Or copy this link to your browser:' }}<br>
                                <a href="{{ $passwordSetUrl }}" style="color: #e6305a; word-break: break-all; font-size: 12px;">{{ $passwordSetUrl }}</a>
                            </p>
                            
                            <!-- FAQ -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">❓ {{ $locale === 'cs' ? 'Časté otázky' : 'FAQ' }}</h3>
                                <p class="info-text">
                                    @if($locale === 'cs')
                                    <strong>Q: Musím zadávat platební kartu znovu?</strong><br>
                                    A: Ne! Vaše karta zůstává uložená a funguje dál automaticky.<br><br>
                                    <strong>Q: Změní se mi datum platby?</strong><br>
                                    A: Ne, datum další platby zůstává stejné jako předtím.<br><br>
                                    <strong>Q: Váš email pro přihlášení:</strong><br>
                                    A: {{ $user->email }}<br><br>
                                    <strong>Q: Jak dlouho je odkaz platný?</strong><br>
                                    A: Odkaz je platný 7 dní. Pokud vyprší, můžete si vyžádat nový pomocí funkce "Zapomenuté heslo".
                                    @else
                                    <strong>Q: Do I need to enter my payment card again?</strong><br>
                                    A: No! Your card remains saved and continues to work automatically.<br><br>
                                    <strong>Q: Will my payment date change?</strong><br>
                                    A: No, your next payment date remains the same.<br><br>
                                    <strong>Q: Your login email:</strong><br>
                                    A: {{ $user->email }}<br><br>
                                    <strong>Q: How long is the link valid?</strong><br>
                                    A: The link is valid for 7 days. If it expires, you can request a new one using "Forgot password".
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Support -->
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">💬 {{ __('emails.welcome.need_help', [], $locale) }}</h3>
                                <p class="info-text" style="color: #047857;">
                                    {{ __('emails.common.questions', [], $locale) }}<br><br>
                                    📧 <strong>{{ __('emails.welcome.email', [], $locale) }}:</strong> <a href="mailto:{{ $contactEmail }}" style="color: #e6305a;">{{ $contactEmail }}</a>
                                </p>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300; text-align: center;">
                                <strong>{{ $locale === 'cs' ? 'Děkujeme za důvěru a těšíme se na mnoho dalších společných kávových zážitků!' : 'Thank you for your trust and we look forward to many more coffee experiences together!' }}</strong>
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px;">
                                {{ __('emails.welcome.with_love', [], $locale) }},<br>
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
                                <a href="{{ route('subscriptions.index') }}" class="footer-link">{{ __('emails.common.subscription', [], $locale) }}</a>
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
