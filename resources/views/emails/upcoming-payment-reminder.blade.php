<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>Připomínka platby</title>
    <style>
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
        
        .highlight-box {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
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
            .info-box, .date-container {
                background-color: #f9fafb !important;
                border: 1px solid #d1d5db !important;
            }
            h1, .info-title, .date-box {
                color: #111827 !important;
            }
            .subtitle, .info-text, .date-label {
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
        [data-ogsc] .info-box, [data-ogsc] .date-container {
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
                            <img src="{{ url('images/kavi-logo-white.png') }}" alt="Kavi Coffee" class="logo">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <h1 style="text-align: center;">Blíží se platba předplatného ☕</h1>
                            <p class="subtitle" style="text-align: center;">Za 3 dny proběhne automatická platba za vaše kávové předplatné.</p>
                            
                            <!-- Payment Info -->
                            <div class="highlight-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important;" bgcolor="#dbeafe">
                                <h3 style="font-size: 18px; font-weight: 600; color: #1e40af; margin: 0 0 12px 0;">💳 Informace o platbě</h3>
                                <p style="font-size: 16px; color: #1e3a8a; line-height: 1.6; margin: 8px 0;">
                                    <strong>Datum platby:</strong><br>
                                    <span style="font-size: 20px; font-weight: 700;">{{ $subscription->next_billing_date->format('j. n. Y') }}</span>
                                </p>
                                <p style="font-size: 16px; color: #1e3a8a; margin: 12px 0 0 0;">
                                    <strong>Částka:</strong> <span style="font-size: 20px; font-weight: 700; color: #e6305a;">{{ number_format($subscription->configured_price, 0, ',', ' ') }} Kč</span>
                                </p>
                            </div>
                            
                            <!-- Subscription Details -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📦 Vaše předplatné</h3>
                                <p class="info-text">
                                    <strong>Číslo předplatného:</strong> {{ $subscription->subscription_number }}<br>
                                    <strong>Typ kávy:</strong> 
                                    @if($subscription->configuration['type'] === 'espresso')
                                        Espresso
                                    @elseif($subscription->configuration['type'] === 'filter')
                                        Filter
                                    @else
                                        Mix ({{ $subscription->configuration['mix']['espresso'] ?? 0 }}× Espresso, {{ $subscription->configuration['mix']['filter'] ?? 0 }}× Filter)
                                    @endif
                                    @if($subscription->configuration['isDecaf'] ?? false)
                                        • Decaf
                                    @endif
                                    <br>
                                    <strong>Množství:</strong> {{ $subscription->configuration['amount'] }}× balení po 250g<br>
                                    <strong>Frekvence:</strong> 
                                    @if($subscription->frequency_months == 1)
                                        Každý měsíc
                                    @elseif($subscription->frequency_months == 2)
                                        Každé 2 měsíce
                                    @else
                                        Každé 3 měsíce
                                    @endif
                                </p>
                            </div>
                            
                            <!-- What happens next -->
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">✓ Co se stane dále?</h3>
                                <p class="info-text" style="color: #047857;">
                                    1. <strong>{{ $subscription->next_billing_date->format('j. n. Y') }}</strong> - Automatická platba<br>
                                    2. Pražení vaší kávy na míru<br>
                                    3. Expedice k vašemu výdejnímu místu<br>
                                    4. Čerstvá káva přímo k vám!
                                </p>
                            </div>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    Spravovat předplatné
                                </a>
                            </div>
                            
                            <!-- Additional Info -->
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                Pokud potřebujete upravit platební údaje, pozastavit nebo zrušit předplatné, můžete tak učinit ve svém zákaznickém účtu.
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 16px; font-weight: 300;">
                                Máte dotazy? Kontaktujte nás na 
                                <a href="mailto:info@kavi.cz" style="color: #e6305a; text-decoration: none;">info@kavi.cz</a>
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px; font-weight: 300;">
                                S pozdravem,<br>
                                <strong style="color: #111827;">Tým Kavi Coffee</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #111827;">Kavi Coffee</strong><br>
                                Prémiová káva s předplatným
                            </p>
                            <div class="footer-links">
                                <a href="{{ route('home') }}" class="footer-link" style="color: #e6305a;">Domů</a>
                                <a href="{{ route('products.index') }}" class="footer-link" style="color: #e6305a;">Obchod</a>
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link" style="color: #e6305a;">Moje předplatné</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                © {{ date('Y') }} Kavi Coffee. Všechna práva vyhrazena.
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

