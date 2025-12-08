<!DOCTYPE html>
<html lang="{{ $locale ?? 'cs' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.reset_password.title', [], $locale ?? 'cs') }}</title>
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
@php
    $currentLocale = $locale ?? 'cs';
    $siteName = $currentLocale === 'en' ? 'KAVIbox.com' : 'KAVI.cz';
    $contactEmail = $currentLocale === 'en' ? 'info@kavibox.com' : 'info@kavi.cz';
@endphp
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
                                    🔑
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">{{ __('emails.reset_password.title', [], $currentLocale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.reset_password.subtitle', [], $currentLocale) }}</p>
                            
                            <!-- Request info -->
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">🔐 {{ $currentLocale === 'cs' ? 'Žádost o reset' : 'Reset request' }}</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    {{ $currentLocale === 'cs' ? 'Někdo (doufáme, že vy) požádal o reset hesla k účtu spojenému s touto emailovou adresou.' : 'Someone (hopefully you) requested a password reset for the account associated with this email address.' }}
                                </p>
                            </div>
                            
                            <!-- Reset button -->
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">✓ {{ $currentLocale === 'cs' ? 'Reset hesla' : 'Reset password' }}</h3>
                                <p class="info-text" style="color: #047857;">
                                    @if($currentLocale === 'cs')
                                    Pro nastavení nového hesla klikněte na tlačítko níže. Tento odkaz je platný <strong>{{ $count }} minut</strong>.
                                    @else
                                    To set a new password, click the button below. This link is valid for <strong>{{ $count }} minutes</strong>.
                                    @endif
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ $url }}" class="button">
                                    {{ $currentLocale === 'cs' ? 'Nastavit nové heslo' : 'Set new password' }}
                                </a>
                            </div>
                            
                            <!-- Security warning -->
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e;">⚠️ {{ $currentLocale === 'cs' ? 'Důležité' : 'Important' }}</h3>
                                <p class="info-text" style="color: #78350f;">
                                    @if($currentLocale === 'cs')
                                    Pokud jste o reset hesla <strong>nežádali</strong>, NEKLIKEJTE na tlačítko výše a ignorujte tento email. Vaše heslo zůstane beze změny.<br><br>
                                    Pro zvýšení bezpečnosti doporučujeme pravidelně měnit heslo a nepoužívat stejné heslo na více místech.
                                    @else
                                    If you did <strong>not request</strong> a password reset, DO NOT click the button above and ignore this email. Your password will remain unchanged.<br><br>
                                    For better security, we recommend regularly changing your password and not using the same password across multiple sites.
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Tips for strong password -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">💡 {{ $currentLocale === 'cs' ? 'Tipy pro silné heslo' : 'Tips for a strong password' }}</h3>
                                <p class="info-text">
                                    @if($currentLocale === 'cs')
                                    • Použijte alespoň 8 znaků<br>
                                    • Kombinujte velká a malá písmena<br>
                                    • Přidejte čísla a speciální znaky<br>
                                    • Nepoužívejte běžná slova<br>
                                    • Zvažte použití správce hesel
                                    @else
                                    • Use at least 8 characters<br>
                                    • Combine uppercase and lowercase letters<br>
                                    • Add numbers and special characters<br>
                                    • Avoid common words<br>
                                    • Consider using a password manager
                                    @endif
                                </p>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                {{ $currentLocale === 'cs' ? 'Pokud nefunguje tlačítko výše, zkopírujte tento odkaz do prohlížeče:' : 'If the button above doesn\'t work, copy this link to your browser:' }}
                            </p>
                            <p style="font-size: 12px; color: #9ca3af; word-break: break-all; background-color: #f9fafb; padding: 12px; border-radius: 8px; margin: 8px 0;">
                                {{ $url }}
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px;">
                                {{ __('emails.common.regards', [], $currentLocale) }},<br>
                                <strong style="color: #111827;">{{ __('emails.common.team', [], $currentLocale) }}</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #111827;">{{ $siteName }}</strong><br>
                                {{ __('emails.common.tagline', [], $currentLocale) }}
                            </p>
                            <div class="footer-links">
                                <a href="{{ route('home') }}" class="footer-link">{{ __('emails.common.home', [], $currentLocale) }}</a>
                                <a href="{{ route('login') }}" class="footer-link">{{ $currentLocale === 'cs' ? 'Přihlášení' : 'Login' }}</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                {{ __('emails.common.copyright', ['year' => date('Y')], $currentLocale) }}
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
