<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>Potvrzení předplatného</title>
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
            max-width: 120px !important;
            width: 120px !important;
            height: auto !important;
            display: block !important;
            margin: 0 auto !important;
        }
        
        .content {
            padding: 40px;
            color: #374151;
        }
        
        .success-icon {
            width: 64px;
            height: 64px;
            background-color: #10b981;
            border-radius: 50%;
            margin: 0 auto 24px;
            display: flex;
            align-items: center;
            justify-center;
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
        
        .config-box {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        
        .config-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .config-item:last-child {
            border-bottom: none;
        }
        
        .config-label {
            color: #6b7280;
            font-weight: 300;
            font-size: 14px;
        }
        
        .config-value {
            font-weight: 600;
            color: #111827;
        }
        
        .highlight-box {
            background-color: #fef3c7;
            border-left: 4px solid: #f59e0b;
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
        
        .trust-badges {
            margin: 24px 0;
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            margin: 8px 12px;
            font-size: 13px;
            color: #059669;
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
            
            .logo {
                max-width: 100px !important;
                width: 100px !important;
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
            .info-box, .config-box {
                background-color: #f9fafb !important;
                border: 1px solid #d1d5db !important;
            }
            h1, .info-title, .config-value {
                color: #111827 !important;
            }
            .subtitle, .info-text, .config-label {
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
        [data-ogsc] .info-box, [data-ogsc] .config-box {
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
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="KAVI.cz" class="logo" width="120" style="max-width: 120px !important; width: 120px !important; height: auto !important; display: block !important; margin: 0 auto !important; border: 0; outline: none;">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <!-- Success Icon -->
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; background-color: #10b981 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    ✓
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">Vítejte v KAVI.cz! ☕</h1>
                            <p class="subtitle" style="text-align: center;">Vaše kávové předplatné bylo úspěšně aktivováno.</p>
                            
                            <!-- Subscription Number -->
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">Číslo předplatného</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Subscription Configuration -->
                            <div class="config-box" style="background-color: #f9fafb !important; border: 1px solid #e5e7eb !important;" bgcolor="#f9fafb">
                                <h2 class="info-title">📦 Vaše kávové předplatné</h2>
                                
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
                                
                                <div class="config-item">
                                    <span class="config-label">Cena za dodávku:</span>
                                    @php
                                    // configured_price now contains FULL price (without discount)
                                    // If active discount, subtract it
                                    $activeDiscount = ($subscription->discount_amount > 0 && $subscription->discount_months_remaining > 0) ? $subscription->discount_amount : 0;
                                    $currentPrice = $subscription->configured_price - $activeDiscount;
                                    @endphp
                                    <span class="config-value" style="font-size: 18px; color: #e6305a;">{{ number_format($currentPrice, 0, ',', ' ') }} Kč</span>
                                </div>
                            </div>
                            
                            <!-- Next Shipment -->
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">📅 První doručení</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    <strong>První kávový box vám dorazí:</strong><br>
                                    {{ $subscription->next_shipment_date ? $subscription->next_shipment_date->format('j. n. Y') : 'Brzy' }}
                                </p>
                                @if($subscription->next_billing_date && $subscription->frequency_months > 0)
                                @php
                                    // Calculate next shipment date after next billing
                                    $nextBillingDate = \Carbon\Carbon::parse($subscription->next_billing_date);
                                    $nextShipmentSchedule = \App\Models\ShipmentSchedule::getForMonth($nextBillingDate->year, $nextBillingDate->month);
                                    $nextShipmentAfterBilling = $nextShipmentSchedule ? $nextShipmentSchedule->shipment_date : $nextBillingDate->copy()->day(20);
                                @endphp
                                <p class="info-text" style="color: #1e3a8a; margin-top: 8px;">
                                    <strong>Další platba:</strong> {{ $nextBillingDate->format('j. n. Y') }}<br>
                                    <strong>Další doručení:</strong> cca {{ $nextShipmentAfterBilling->format('j. n. Y') }}
                                </p>
                                @endif
                            </div>
                            
                            <!-- Delivery Info -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📦 Doručení</h3>
                                @if(isset($subscription->packeta_point_name))
                                <p class="info-text"><strong>Výdejní místo:</strong></p>
                                <p class="info-text">{{ $subscription->packeta_point_name }}</p>
                                @if(isset($subscription->packeta_point_address))
                                <p class="info-text" style="color: #6b7280;">{{ $subscription->packeta_point_address }}</p>
                                @endif
                                @endif
                                
                                @if($subscription->delivery_notes)
                                <p class="info-text" style="margin-top: 12px;">
                                    <strong>Poznámka:</strong><br>
                                    {{ $subscription->delivery_notes }}
                                </p>
                                @endif
                            </div>
                            
                            <!-- Billing Info -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📋 Fakturační údaje</h3>
                                <p class="info-text"><strong>{{ $subscription->shipping_address['name'] }}</strong></p>
                                <p class="info-text">{{ $subscription->shipping_address['billing_address'] }}</p>
                                <p class="info-text">{{ $subscription->shipping_address['billing_postal_code'] }} {{ $subscription->shipping_address['billing_city'] }}</p>
                                <p class="info-text" style="margin-top: 8px;">
                                    <strong>Email:</strong> {{ $subscription->shipping_address['email'] }}
                                    @if(isset($subscription->shipping_address['phone']))
                                    <br><strong>Telefon:</strong> {{ $subscription->shipping_address['phone'] }}
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Subscription Status -->
                            @if($subscription->status === 'active')
                            <div class="info-box" style="background-color: #d1fae5 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#d1fae5">
                                <h3 class="info-title" style="color: #065f46;">✓ Stav předplatného</h3>
                                <p class="info-text" style="color: #047857;">
                                    Vaše předplatné je <strong>aktivní</strong>. Další platba proběhne automaticky.
                                </p>
                            </div>
                            @else
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e;">⏳ Stav předplatného</h3>
                                <p class="info-text" style="color: #78350f;">
                                    Vaše předplatné čeká na aktivaci. Po potvrzení platby bude automaticky aktivováno.
                                </p>
                            </div>
                            @endif
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    Spravovat předplatné
                                </a>
                            </div>
                            
                            <!-- Trust Badges -->
                            <div class="trust-badges">
                                <div class="badge">
                                    ✓ Čerstvě pražená káva
                                </div>
                                <div class="badge">
                                    ✓ Flexibilní předplatné
                                </div>
                                <div class="badge">
                                    ✓ Zrušení kdykoliv
                                </div>
                            </div>
                            
                            <!-- Additional Info -->
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                Své předplatné můžete kdykoliv upravit, pozastavit nebo zrušit v zákaznickém účtu. Pokud máte jakékoliv dotazy, kontaktujte nás na 
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
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link" style="color: #e6305a;">Moje předplatné</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                © {{ date('Y') }} KAVI.cz. Všechna práva vyhrazena.
                            </p>
                            <p class="footer-text" style="font-size: 12px;">
                                Tento e-mail byl odeslán na adresu {{ $subscription->shipping_address['email'] }}<br>
                                protože jste si vytvořili kávové předplatné na našem e-shopu.
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

