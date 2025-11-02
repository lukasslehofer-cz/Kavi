<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>Účet smazán</title>
    <style>
        /* Reset styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        
        /* Base styles */
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        .header {
            background-color: #111827;
            padding: 32px 40px;
            text-align: center;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
        }
        
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
        
        .info-box {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        
        .info-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 12px 0;
        }
        
        .info-text {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.6;
            margin: 4px 0;
        }
        
        .info-list {
            margin: 12px 0;
            padding-left: 0;
            list-style: none;
        }
        
        .info-list li {
            padding: 6px 0;
            font-size: 14px;
            color: #4b5563;
            line-height: 1.6;
        }
        
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
        }
        
        /* Force light mode - prevent auto color inversion */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1a1a1a !important;
            }
            .email-container {
                background-color: #ffffff !important;
                border: 1px solid #d1d5db !important;
            }
            .info-box {
                background-color: #f9fafb !important;
                border: 1px solid #d1d5db !important;
            }
            h1, .info-title {
                color: #111827 !important;
            }
            .subtitle, .info-text {
                color: #4b5563 !important;
            }
            .header {
                background-color: #111827 !important;
            }
        }
        
        /* Prevent Gmail/Outlook.com dark mode auto-inversion */
        [data-ogsc] .email-container {
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
        }
        [data-ogsc] .info-box {
            background-color: #f9fafb !important;
            border: 1px solid #d1d5db !important;
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6; padding: 20px 0;">
        <tr>
            <td align="center">
                <!--[if mso]>
                <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600">
                <tr>
                <td>
                <![endif]-->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" width="100%" style="width: 100%; max-width: 600px; background-color: #ffffff !important; border: 1px solid #e5e7eb !important; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);" bgcolor="#ffffff">
                    
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <img src="{{ url('images/kavi-logo-white.png') }}" alt="KAVI.cz" class="logo">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <!-- Icon -->
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; background-color: #6b7280 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    ✓
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">Váš účet byl smazán</h1>
                            <p class="subtitle" style="text-align: center;">Potvrzujeme úspěšné smazání vašeho účtu.</p>
                            
                            <!-- Email Display -->
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">Smazaný email</div>
                                <div style="font-size: 16px; font-weight: 600; color: #111827;">{{ $userEmail }}</div>
                            </div>
                            
                            <!-- What was deleted -->
                            <div class="info-box" style="background-color: #fee2e2 !important; border: 1px solid #fecaca !important; border-left: 4px solid #dc2626 !important;" bgcolor="#fee2e2">
                                <h3 class="info-title" style="color: #991b1b;">✗ Co bylo smazáno</h3>
                                <ul class="info-list">
                                    <li style="color: #7f1d1d;">• Všechna osobní data (jméno, telefon, adresa)</li>
                                    <li style="color: #7f1d1d;">• Přihlašovací údaje - už se nebudete moci přihlásit</li>
                                    <li style="color: #7f1d1d;">• Všechny uložené platební metody</li>
                                    <li style="color: #7f1d1d;">• Aktivní předplatná (zrušena)</li>
                                    <li style="color: #7f1d1d;">• Nezaplacené objednávky</li>
                                    <li style="color: #7f1d1d;">• Odběr newsletteru</li>
                                </ul>
                            </div>
                            
                            <!-- What was kept -->
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">📄 Co jsme zachovali (zákonná povinnost)</h3>
                                <ul class="info-list">
                                    <li style="color: #1e3a8a;"><strong>• Faktury a platební historii</strong> - uchováváme 10 let dle zákona o účetnictví</li>
                                    <li style="color: #1e3a8a;"><strong>• Anonymizovaná data</strong> - historie objednávek (bez osobních údajů)</li>
                                    <li style="color: #1e3a8a;"><strong>• Historie předplatných</strong> (anonymizovaná) - pro účetní evidenci</li>
                                </ul>
                                <p class="info-text" style="color: #1e3a8a; margin-top: 12px; font-style: italic;">
                                    Všechny zachované údaje jsou plně anonymizované a nelze z nich zjistit vaši totožnost.
                                </p>
                            </div>
                            
                            <!-- Thank you message -->
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">💙 Děkujeme</h3>
                                <p class="info-text" style="color: #047857;">
                                    <strong>Děkujeme, že jste byli součástí KAVI.cz komunity!</strong>
                                </p>
                                <p class="info-text" style="color: #047857; margin-top: 12px;">
                                    Pokud si to někdy rozmyslíte, budeme rádi, když se k nám znovu vrátíte. 
                                    Jednoduše si vytvořte nový účet na našem webu.
                                </p>
                            </div>
                            
                            <!-- Contact info -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">❓ Máte dotazy?</h3>
                                <p class="info-text">
                                    Pokud máte jakékoliv dotazy ohledně smazání účtu nebo zachovaných dat, 
                                    neváhejte nás kontaktovat na 
                                    <a href="mailto:info@kavi.cz" style="color: #e6305a; text-decoration: none; font-weight: 600;">info@kavi.cz</a>
                                </p>
                            </div>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('home') }}" class="button">
                                    Přejít na KAVI.cz
                                </a>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px; font-weight: 300; text-align: center;">
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
                                <a href="{{ route('home') }}" class="footer-link">Domů</a>
                                <a href="{{ route('products.index') }}" class="footer-link">Obchod</a>
                                <a href="{{ route('subscriptions.index') }}" class="footer-link">Předplatné</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                © {{ date('Y') }} KAVI.cz. Všechna práva vyhrazena.
                            </p>
                            <p class="footer-text" style="font-size: 12px;">
                                Tento e-mail byl odeslán jako potvrzení smazání vašeho účtu.
                            </p>
                        </td>
                    </tr>
                    
                </table>
                <!--[if mso]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
