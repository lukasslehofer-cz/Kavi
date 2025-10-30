<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>Objednávka byla odeslána</title>
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
            max-width: 150px;
            height: auto;
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
        
        /* Order items */
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .item-name {
            font-weight: 600;
            color: #111827;
            font-size: 14px;
        }
        
        .item-quantity {
            font-size: 13px;
            color: #6b7280;
            margin-top: 2px;
        }
        
        .item-price {
            font-weight: 700;
            color: #111827;
            white-space: nowrap;
            margin-left: 16px;
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
        
        .button:hover { background-color: #d12a51; }
        
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
            h1, .info-title, .item-name {
                color: #111827 !important;
            }
            .subtitle, .info-text, .item-quantity {
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
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6 !important; padding: 20px 0;">
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
                            <!-- Icon -->
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; background-color: #3b82f6 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    📦
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">Vaše káva je na cestě! 📦</h1>
                            <p class="subtitle" style="text-align: center;">Objednávka byla odeslána a brzy dorazí na výdejní místo.</p>
                            
                            <!-- Order Number -->
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">Číslo objednávky</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $order->order_number }}</div>
                            </div>
                            
                            <!-- Tracking Info -->
                            @if($order->packeta_tracking_url)
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">📍 Sledování zásilky</h3>
                                <p class="info-text" style="color: #1e3a8a; margin-bottom: 12px;">
                                    Zásilku můžete sledovat kliknutím na tlačítko níže:
                                </p>
                                <div style="text-align: center;">
                                    <a href="{{ $order->packeta_tracking_url }}" class="button" style="display: inline-block; margin: 8px 0;">
                                        Sledovat zásilku
                                    </a>
                                </div>
                                @if($order->packeta_packet_id)
                                <p class="info-text" style="color: #1e3a8a; margin-top: 12px; font-size: 13px;">
                                    <strong>Číslo balíku:</strong> {{ $order->packeta_packet_id }}
                                </p>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Pickup Point -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📦 Výdejní místo</h3>
                                @if(isset($order->shipping_address['packeta_point_name']))
                                <p class="info-text"><strong>{{ $order->shipping_address['packeta_point_name'] }}</strong></p>
                                @if(isset($order->shipping_address['packeta_point_address']))
                                <p class="info-text" style="color: #6b7280;">{{ $order->shipping_address['packeta_point_address'] }}</p>
                                @endif
                                <p class="info-text" style="margin-top: 12px; color: #059669;">
                                    ✓ Zásilku vyzvedněte do 7 dnů od doručení
                                </p>
                                @endif
                            </div>
                            
                            <!-- Order Items -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">☕ Co vám posíláme</h3>
                                @foreach($order->items as $item)
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                                    <tr>
                                        <td style="padding: 0; vertical-align: top;">
                                            <div class="item-name" style="font-weight: 600; color: #111827; font-size: 14px;">{{ $item->product_name }}</div>
                                            <div class="item-quantity" style="font-size: 13px; color: #6b7280; margin-top: 2px;">{{ $item->quantity }}× {{ number_format($item->price, 0, ',', ' ') }} Kč</div>
                                        </td>
                                        <td align="right" valign="top" style="padding: 0 0 0 16px; white-space: nowrap;">
                                            <div class="item-price" style="font-weight: 700; color: #111827;">{{ number_format($item->quantity * $item->price, 0, ',', ' ') }} Kč</div>
                                        </td>
                                    </tr>
                                </table>
                                @endforeach
                            </div>
                            
                            <!-- What's Next -->
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">✓ Co se stane dále?</h3>
                                <p class="info-text" style="color: #047857;">
                                    1. Zásilka dorazí na výdejní místo během 1-2 dnů<br>
                                    2. Dostanete SMS s kódem pro vyzvednutí<br>
                                    3. Vyzvedněte si balík na výdejním místě<br>
                                    4. Vychutnejte si čerstvou kávu!
                                </p>
                            </div>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.order.detail', $order->id) }}" class="button">
                                    Zobrazit detail objednávky
                                </a>
                            </div>
                            
                            <!-- Additional Info -->
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
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
                                <a href="{{ route('dashboard.index') }}" class="footer-link" style="color: #e6305a;">Můj účet</a>
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

