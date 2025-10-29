<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>Potvrďte změnu emailu</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f3f4f6 !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .email-container { max-width: 600px; margin: 0 auto; }
        .header { background-color: #111827 !important; padding: 32px; text-align: center; }
        .logo { max-width: 180px; height: auto; }
        .content { padding: 40px 32px; background-color: #ffffff !important; }
        h1 { font-size: 28px; font-weight: 700; color: #111827; margin: 0 0 16px 0; line-height: 1.3; }
        .subtitle { font-size: 16px; color: #6b7280; margin: 0 0 32px 0; font-weight: 300; }
        .info-box { background-color: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin: 24px 0; }
        .info-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px 0; }
        .info-text { font-size: 14px; color: #4b5563; line-height: 1.6; margin: 8px 0; }
        .button { display: inline-block; background-color: #e6305a; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 9999px; font-weight: 600; font-size: 15px; margin: 24px 0; text-align: center; }
        .button:hover { background-color: #d12a51; }
        .footer { background-color: #f9fafb !important; padding: 32px; text-align: center; }
        .footer-text { font-size: 14px; color: #6b7280; line-height: 1.6; margin: 8px 0; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #e6305a; text-decoration: none; margin: 0 12px; font-size: 14px; }
        @media only screen and (max-width: 600px) { .content { padding: 24px !important; } h1 { font-size: 24px !important; } .header { padding: 24px !important; } .footer { padding: 24px !important; } }
        @media (prefers-color-scheme: dark) { body { background-color: #1a1a1a !important; } .email-container { background-color: #ffffff !important; border: 1px solid #d1d5db !important; } .info-box { background-color: #f9fafb !important; border: 1px solid #d1d5db !important; } h1, .info-title { color: #111827 !important; } .subtitle, .info-text { color: #4b5563 !important; } .header { background-color: #111827 !important; } }
        [data-ogsc] .email-container { background-color: #ffffff !important; border: 1px solid #d1d5db !important; }
        [data-ogsc] .info-box { background-color: #f9fafb !important; border: 1px solid #d1d5db !important; }
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
                            <img src="{{ url('images/kavi-logo-white.png') }}" alt="Kavi Coffee" class="logo">
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; background-color: #3b82f6 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    📧
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">Potvrďte změnu emailu</h1>
                            <p class="subtitle" style="text-align: center;">Obdrželi jsme žádost o změnu vaší emailové adresy.</p>
                            
                            <!-- Current vs New -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📋 Změna emailu</h3>
                                <p class="info-text">
                                    <strong>Současný email:</strong><br>
                                    {{ $user->email }}<br><br>
                                    
                                    <strong>Nový email:</strong><br>
                                    {{ $newEmail }}
                                </p>
                            </div>
                            
                            <!-- Security warning -->
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e;">🔒 Důležité pro bezpečnost</h3>
                                <p class="info-text" style="color: #78350f;">
                                    Pokud jste o tuto změnu <strong>nežádali</strong>, NEKLIKEJTE na tlačítko níže a okamžitě nás kontaktujte na <a href="mailto:info@kavi.cz" style="color: #e6305a;">info@kavi.cz</a>
                                </p>
                            </div>
                            
                            <!-- Confirm -->
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">✓ Potvrďte změnu</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    Pro dokončení změny klikněte na tlačítko níže. Tento odkaz je platný <strong>24 hodin</strong>.
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ url('/verify-email-change/' . $confirmationToken) }}" class="button">
                                    Potvrdit změnu emailu
                                </a>
                            </div>
                            
                            <!-- Didn't request -->
                            <div class="info-box" style="background-color: #fee2e2 !important; border: 1px solid #fca5a5 !important; border-left: 4px solid #ef4444 !important;" bgcolor="#fee2e2">
                                <h3 class="info-title" style="color: #991b1b;">⚠️ Nebylo to od vás?</h3>
                                <p class="info-text" style="color: #7f1d1d;">
                                    Pokud jste o změnu emailu nežádali, ignorujte tento email a okamžitě:<br><br>
                                    
                                    1. Změňte si heslo k účtu<br>
                                    2. Kontaktujte náš tým na <a href="mailto:info@kavi.cz" style="color: #e6305a;">info@kavi.cz</a><br>
                                    3. Zkontrolujte aktivitu na svém účtu
                                </p>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                Pokud nefunguje tlačítko výše, zkopírujte tento odkaz do prohlížeče:
                            </p>
                            <p style="font-size: 12px; color: #9ca3af; word-break: break-all; background-color: #f9fafb; padding: 12px; border-radius: 8px; margin: 8px 0;">
                                {{ url('/verify-email-change/' . $confirmationToken) }}
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px;">
                                S pozdravem,<br>
                                <strong style="color: #111827;">Tým Kavi Coffee</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #111827;">Kavi Coffee</strong><br>
                                Prémiová káva s předplatným
                            </p>
                            <div class="footer-links">
                                <a href="{{ route('home') }}" class="footer-link">Domů</a>
                                <a href="{{ route('dashboard.profile') }}" class="footer-link">Můj účet</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                © {{ date('Y') }} Kavi Coffee. Všechna práva vyhrazena.
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

