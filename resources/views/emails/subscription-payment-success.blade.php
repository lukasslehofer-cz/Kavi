<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>Platba předplatného úspěšně provedena</title>
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
        .config-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
        .config-item:last-child { border-bottom: none; }
        .config-label { color: #6b7280; font-weight: 300; font-size: 14px; }
        .config-value { font-weight: 600; color: #111827; }
        .button { display: inline-block; background-color: #e6305a; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 9999px; font-weight: 600; font-size: 15px; margin: 24px 0; text-align: center; }
        .button:hover { background-color: #d12a51; }
        .footer { background-color: #f9fafb !important; padding: 32px; text-align: center; }
        .footer-text { font-size: 14px; color: #6b7280; line-height: 1.6; margin: 8px 0; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #e6305a; text-decoration: none; margin: 0 12px; font-size: 14px; }
        @media only screen and (max-width: 600px) { .content { padding: 24px !important; } h1 { font-size: 24px !important; } .header { padding: 24px !important; } .footer { padding: 24px !important; } .logo { max-width: 100px !important; width: 100px !important; } }
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
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="KAVI.cz" class="logo" width="120" style="max-width: 120px !important; width: 120px !important; height: auto !important; display: block !important; margin: 0 auto !important; border: 0; outline: none;">
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; background-color: #10b981 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px; color: #ffffff;">
                                    ✓
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">Platba úspěšně provedena! ☕</h1>
                            <p class="subtitle" style="text-align: center;">Vaše pravidelná platba za kávové předplatné byla zpracována.</p>
                            
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">Předplatné</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Payment Details -->
                            <div class="info-box" style="background-color: #d1fae5 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#d1fae5">
                                <h3 class="info-title" style="color: #065f46;">✓ Potvrzení platby</h3>
                                <div class="config-item" style="border-bottom: 1px solid #bbf7d0;">
                                    <span class="config-label" style="color: #047857;">Částka:</span>
                                    <span class="config-value" style="color: #065f46; font-size: 18px;">{{ number_format($payment->amount, 0, ',', ' ') }} Kč</span>
                                </div>
                                <div class="config-item" style="border-bottom: 1px solid #bbf7d0;">
                                    <span class="config-label" style="color: #047857;">Datum platby:</span>
                                    <span class="config-value" style="color: #065f46;">{{ $payment->paid_at->format('j. n. Y') }}</span>
                                </div>
                                <div class="config-item" style="border-bottom: none;">
                                    <span class="config-label" style="color: #047857;">Status:</span>
                                    <span class="config-value" style="color: #065f46;">Zaplaceno ✓</span>
                                </div>
                            </div>
                            
                            <!-- Next Billing -->
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">📅 Další platba</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    <strong>Datum další platby:</strong><br>
                                    {{ $subscription->next_billing_date ? $subscription->next_billing_date->format('j. n. Y') : 'Bude upřesněno' }}
                                </p>
                                <p class="info-text" style="color: #1e3a8a; margin-top: 8px; font-size: 13px;">
                                    Platba bude automaticky stržena z vaší uložené platební karty.<br>
                                    3 dny před platbou vám pošleme připomínku.
                                </p>
                            </div>
                            
                            <!-- Subscription Summary -->
                            <div class="info-box" style="background-color: #f9fafb !important; border: 1px solid #e5e7eb !important;" bgcolor="#f9fafb">
                                <h3 class="info-title">📦 Vaše předplatné</h3>
                                
                                <div class="config-item">
                                    <span class="config-label">Typ kávy:</span>
                                    <span class="config-value">
                                        @if($subscription->configuration['type'] === 'espresso')
                                            Espresso
                                        @elseif($subscription->configuration['type'] === 'filter')
                                            Filter
                                        @else
                                            Mix ({{ $subscription->configuration['mix']['espresso'] ?? 0 }}× Espresso, {{ $subscription->configuration['mix']['filter'] ?? 0 }}× Filter)
                                        @endif
                                        @if($subscription->configuration['isDecaf'] ?? false)
                                            <span style="color: #059669;"> • Decaf</span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="config-item">
                                    <span class="config-label">Množství:</span>
                                    <span class="config-value">{{ $subscription->configuration['amount'] }}× balení po 250g</span>
                                </div>
                                
                                <div class="config-item">
                                    <span class="config-label">Frekvence:</span>
                                    <span class="config-value">
                                        @if($subscription->frequency_months == 1)
                                            Každý měsíc
                                        @elseif($subscription->frequency_months == 2)
                                            Každé 2 měsíce
                                        @else
                                            Každé 3 měsíce
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Invoice Info -->
                            @if($payment->invoice_pdf_path)
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e;">📄 Faktura</h3>
                                <p class="info-text" style="color: #78350f;">
                                    Faktura je přiložena k tomuto emailu jako PDF příloha.<br>
                                    Fakturu také najdete ve svém zákaznickém účtu v sekci "Předplatné".
                                </p>
                            </div>
                            @else
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">📄 Faktura</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    Faktura se připravuje a bude vám odeslána v samostatném emailu.
                                </p>
                            </div>
                            @endif
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    Zobrazit předplatné
                                </a>
                            </div>
                            
                            <!-- Help Section -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">💡 Potřebujete změnu?</h3>
                                <p class="info-text">
                                    V zákaznickém účtu můžete kdykoliv:<br><br>
                                    
                                    • ⏸️ <strong>Pozastavit</strong> předplatné na dovolenou<br>
                                    • 🔄 <strong>Změnit</strong> frekvenci nebo typ kávy<br>
                                    • 💳 <strong>Aktualizovat</strong> platební údaje<br>
                                    • 📦 <strong>Upravit</strong> doručovací adresu<br>
                                    • ❌ <strong>Zrušit</strong> předplatné kdykoliv
                                </p>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                Děkujeme, že jste s námi! Pokud máte jakékoliv dotazy, kontaktujte nás na 
                                <a href="mailto:info@kavi.cz" style="color: #e6305a; text-decoration: none;">info@kavi.cz</a>
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px; font-weight: 300;">
                                S pozdravem,<br>
                                <strong style="color: #111827;">Tým KAVI.cz</strong>
                            </p>
                        </td>
                    </tr>
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
                            <p class="footer-text" style="font-size: 12px;">
                                Tento e-mail byl odeslán na adresu {{ $subscription->shipping_address['email'] ?? $subscription->user?->email }}<br>
                                protože máte aktivní kávové předplatné.
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

