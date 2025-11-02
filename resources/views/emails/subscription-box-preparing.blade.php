<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>Připravujeme váš kávový box</title>
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
        
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background-color: #f3f4f6 !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Header */
        .header {
            background-color: #111827 !important;
            padding: 32px;
            text-align: center;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
        }
        
        /* Content */
        .content {
            padding: 40px 32px;
            background-color: #ffffff !important;
        }
        
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 16px 0;
            line-height: 1.3;
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
            margin: 8px 0;
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
        
        /* Footer */
        .footer {
            background-color: #f9fafb !important;
            padding: 32px;
            text-align: center;
        }
        
        .footer-text {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            margin: 8px 0;
        }
        
        .footer-links {
            margin: 16px 0;
        }
        
        .footer-link {
            color: #e6305a;
            text-decoration: none;
            margin: 0 12px;
            font-size: 14px;
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
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6 !important; padding: 20px 0;" bgcolor="#f3f4f6">
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
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="KAVI.cz" class="logo">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <!-- Icon -->
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; background-color: #f59e0b !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    📦
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">Připravujeme váš kávový box! ☕</h1>
                            <p class="subtitle" style="text-align: center;">Káva z celé Evropy dorazila a balíme ji speciálně pro vás.</p>
                            
                            <!-- Subscription Number -->
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">Předplatné</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Timeline -->
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e;">⏱️ Co se právě děje</h3>
                                <p class="info-text" style="color: #78350f;">
                                    <strong>1. ✓ Výběr kávy</strong> - Pečlivě jsme vybrali kávy z nejlepších pražíren<br>
                                    <strong>2. ✓ Káva dorazila</strong> - Vzorky z celé Evropy jsou na místě<br>
                                    <strong>3. → Balení boxu</strong> - Právě teď balíme váš unikátní box<br>
                                    <strong>4. Expedice</strong> - Za cca 5 dní odešleme k vám
                                </p>
                            </div>
                            
                            <!-- What's inside -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">☕ Co vás čeká v boxu</h3>
                                <p class="info-text">
                                    <strong>Typ kávy:</strong> 
                                    @if($subscription->configuration['type'] === 'espresso')
                                        Espresso
                                    @elseif($subscription->configuration['type'] === 'filter')
                                        Filtrovaná káva
                                    @else
                                        Mix Espresso & Filter
                                    @endif
                                    <br>
                                    
                                    <strong>Množství:</strong> {{ $subscription->configuration['quantity'] ?? 2 }} vzorky<br>
                                    
                                    @if(isset($subscription->configuration['roast_level']))
                                    <strong>Intenzita pražení:</strong> 
                                    @if($subscription->configuration['roast_level'] === 'light')
                                        Světlé pražení
                                    @elseif($subscription->configuration['roast_level'] === 'medium')
                                        Střední pražení
                                    @else
                                        Tmavé pražení
                                    @endif
                                    <br>
                                    @endif
                                    
                                    <strong>Frekvence:</strong> 
                                    @if($subscription->configuration['frequency'] === 'weekly')
                                        Týdně
                                    @elseif($subscription->configuration['frequency'] === 'biweekly')
                                        Každé 2 týdny
                                    @else
                                        Měsíčně
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Expected shipment -->
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">📅 Očekávaná expedice</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    Váš box odešleme přibližně za <strong>5 dní</strong>.<br>
                                    O expedici vás budeme informovat dalším e-mailem s tracking číslem.
                                </p>
                            </div>
                            
                            <!-- What makes it special -->
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">✨ Proč je váš box výjimečný</h3>
                                <p class="info-text" style="color: #047857;">
                                    • <strong>Čerstvě pražená káva</strong> z malých pražíren<br>
                                    • <strong>Vybrána speciálně</strong> podle vašich preferencí<br>
                                    • <strong>Vzorky z celé Evropy</strong> - objevujte nové chutě<br>
                                    • <strong>Baleno s láskou</strong> náš tým pro vás
                                </p>
                            </div>
                            
                            <!-- CTA -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    Spravovat předplatné
                                </a>
                            </div>
                            
                            <!-- Additional Info -->
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                Pokud máte jakékoliv dotazy ohledně vašeho předplatného, neváhejte nás kontaktovat na
                                <a href="mailto:info@kavi.cz" style="color: #e6305a; text-decoration: none;">info@kavi.cz</a>
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px;">
                                S láskou k dobré kávě,<br>
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
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link">Moje předplatné</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                © {{ date('Y') }} KAVI.cz. Všechna práva vyhrazena.
                            </p>
                            <p class="footer-text" style="font-size: 12px; margin-top: 8px; color: #9ca3af;">
                                Tento e-mail byl odeslán na adresu {{ $subscription->shipping_address['email'] }}<br>
                                protože máte aktivní kávové předplatné.
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

