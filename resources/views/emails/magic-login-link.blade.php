<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlašovací odkaz</title>
    <style>
        /* Reset styles */
        body, table, td, a { 
            -webkit-text-size-adjust: 100%; 
            -ms-text-size-adjust: 100%; 
        }
        table, td { 
            mso-table-lspace: 0pt; 
            mso-table-rspace: 0pt; 
        }
        img { 
            -ms-interpolation-mode: bicubic; 
            border: 0; 
            height: auto; 
            line-height: 100%; 
            outline: none; 
            text-decoration: none; 
        }
        
        /* Base styles */
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        /* Header */
        .header {
            background-color: #111827;
            padding: 32px 40px;
            text-align: center;
        }
        
        .logo {
            max-width: 120px !important;
            width: 120px !important;
            height: auto !important;
            display: block !important;
            margin: 0 auto !important;
        }
        
        /* Content */
        .content {
            padding: 40px;
            color: #374151;
        }
        
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 12px 0;
            line-height: 1.2;
        }
        
        .subtitle {
            font-size: 16px;
            color: #6b7280;
            margin: 0 0 32px 0;
            font-weight: 300;
        }
        
        /* Button */
        .button {
            display: inline-block;
            background-color: #e6305a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 15px;
            margin: 24px 0;
            text-align: center;
        }
        
        .button:hover {
            background-color: #d12a51;
        }
        
        /* Info boxes */
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        
        .info-title {
            font-size: 16px;
            font-weight: 600;
            color: #92400e;
            margin: 0 0 8px 0;
        }
        
        .info-text {
            font-size: 14px;
            color: #78350f;
            line-height: 1.6;
            margin: 4px 0;
        }
        
        .note-box {
            background-color: #f3f4f6;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        
        .note-text {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.6;
            margin: 4px 0;
        }
        
        /* Footer */
        .footer {
            background-color: #f9fafb;
            padding: 32px 40px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-text {
            margin: 8px 0;
            font-weight: 300;
        }
        
        .footer-links {
            margin: 16px 0;
        }
        
        .footer-link {
            color: #e6305a;
            text-decoration: none;
            margin: 0 8px;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .content {
                padding: 24px !important;
            }
            
            h1 {
                font-size: 24px !important;
            }
            
            .header {
                padding: 24px !important;
            }
            
            .footer {
                padding: 24px !important;
            }
            
            .logo {
                max-width: 100px !important;
                width: 100px !important;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6; padding: 20px 0;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="KAVI.cz" class="logo" width="120" style="max-width: 120px !important; width: 120px !important; height: auto !important; display: block !important; margin: 0 auto !important; border: 0; outline: none; filter: invert(1) brightness(2);">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <!-- Icon -->
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; background-color: #e6305a; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center;">
                                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#ffffff" style="stroke-width: 2;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">Přihlašovací odkaz</h1>
                            <p class="subtitle" style="text-align: center;">Obdrželi jste tento email, protože byl požadován přihlašovací odkaz pro váš účet na KAVI.cz.</p>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ $loginUrl }}" class="button">
                                    Přihlásit se do účtu
                                </a>
                            </div>
                            
                            <!-- Warning Box -->
                            <div class="info-box">
                                <p class="info-title">⏱️ Platnost odkazu</p>
                                <p class="info-text">
                                    Tento odkaz je platný po dobu <strong>{{ $expiresInMinutes }} minut</strong>. Po vypršení platnosti si budete muset vyžádat nový odkaz.
                                </p>
                            </div>
                            
                            <!-- Security Note -->
                            <div class="note-box">
                                <p class="note-text" style="margin-bottom: 12px;">
                                    <strong>🔒 Z bezpečnostních důvodů:</strong>
                                </p>
                                <p class="note-text">
                                    • Odkaz funguje pouze jednou<br>
                                    • Pokud jste nepožadovali přihlášení, ignorujte tento email<br>
                                    • Nikdy nesdílejte tento odkaz s nikým jiným
                                </p>
                            </div>
                            
                            <!-- Alternative Link -->
                            <div style="background-color: #f9fafb; border-radius: 8px; padding: 16px; margin-top: 24px;">
                                <p style="font-size: 13px; color: #6b7280; margin: 0 0 8px 0; font-weight: 500;">
                                    Nefunguje tlačítko? Zkopírujte tento odkaz:
                                </p>
                                <p style="font-size: 12px; color: #111827; word-break: break-all; margin: 0; font-family: monospace;">
                                    {{ $loginUrl }}
                                </p>
                            </div>
                            
                            <!-- Additional Info -->
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                Pokud máte jakékoliv problémy s přihlášením, neváhejte nás kontaktovat na 
                                <a href="mailto:info@kavi.cz" style="color: #e6305a; text-decoration: none;">info@kavi.cz</a>                                 
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px; font-weight: 300;">
                                S pozdravem,<br>
                                <strong style="color: #111827;">Tým KAVI.cz</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #111827;">KAVI.cz</strong><br>
                                Prémiová káva s předplatným
                            </p>
                            <div class="footer-links">
                                <a href="{{ route('home') }}" class="footer-link" style="color: #e6305a;">Domů</a>
                                <a href="{{ route('products.index') }}" class="footer-link" style="color: #e6305a;">Obchod</a>
                                <a href="{{ route('dashboard.index') }}" class="footer-link" style="color: #e6305a;">Můj účet</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                © {{ date('Y') }} KAVI.cz. Všechna práva vyhrazena.
                            </p>
                            <p class="footer-text" style="font-size: 12px;">
                                Tento e-mail byl odeslán, protože jste si vyžádali přihlašovací odkaz<br>
                                pro svůj účet na KAVI.cz.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
