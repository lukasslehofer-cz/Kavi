<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.order_shipped.title', [], $locale) }}</title>
    <style>
        /* Reset styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        
        /* Swiss Style Base */
        body { 
            margin: 0; 
            padding: 0; 
            width: 100%; 
            background-color: #bcbeb1; 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        
        .email-container { 
            max-width: 600px; 
            margin: 0 auto; 
            background-color: #e5e6df; 
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) { 
            .content { padding: 32px 24px !important; } 
            h1 { font-size: 26px !important; } 
            .header, .footer { padding: 32px 24px !important; } 
        }
        
        /* Dark mode override */
        @media (prefers-color-scheme: dark) { 
            body { background-color: #bcbeb1 !important; } 
            .email-container { background-color: #e5e6df !important; } 
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #bcbeb1; padding: 32px 16px;">
        <tr>
            <td align="center">
                <!--[if mso]><table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"><tr><td><![endif]-->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" width="100%" style="width: 100%; max-width: 600px; background-color: #e5e6df !important;" bgcolor="#e5e6df">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1c1c1c; padding: 32px 40px; text-align: left;">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="{{ $siteName }}" width="80" style="max-width: 80px !important; width: 80px !important; height: auto !important; display: block !important; border: 0; outline: none;">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 48px 40px; color: #4a4a4a; background-color: #e5e6df;">
                            
                            <!-- Title -->
                            <h1 style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 400; color: #1c1c1c; margin: 0 0 8px 0; line-height: 1.1; letter-spacing: -0.02em; text-transform: uppercase;">
                                {{ __('emails.order_shipped.title', [], $locale) }}
                            </h1>
                            <p style="font-size: 14px; color: #76716C; margin: 0 0 40px 0; font-weight: 400; text-transform: uppercase; letter-spacing: 0.1em;">
                                {{ __('emails.order_shipped.subtitle', [], $locale) }}
                            </p>
                            
                            <!-- Order Number -->
                            <div style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
                                <div style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.order_shipped.order_number', [], $locale) }}
                                </div>
                                <div style="font-size: 28px; font-weight: 400; color: #1c1c1c; letter-spacing: -0.02em;">
                                    {{ $order->order_number }}
                                </div>
                            </div>
                            
                            <!-- Tracking Info -->
                            @if($order->packeta_tracking_url)
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #CA4136; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 12px;">
                                    {{ __('emails.order_shipped.track_package', [], $locale) }}
                                </div>
                                <p style="font-size: 14px; color: #5a5a5a; margin: 0 0 16px 0;">
                                    {{ $locale === 'cs' ? 'Zásilku můžete sledovat kliknutím na tlačítko níže:' : 'You can track your package by clicking the button below:' }}
                                </p>
                                <a href="{{ $order->packeta_tracking_url }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 14px 28px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                    {{ __('emails.order_shipped.track_package', [], $locale) }} →
                                </a>
                                @if($order->packeta_packet_id)
                                <p style="font-size: 12px; color: #76716C; margin: 16px 0 0 0;">
                                    <span style="text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.order_shipped.tracking_number', [], $locale) }}:</span> {{ $order->packeta_packet_id }}
                                </p>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Pickup Point -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.order_shipped.pickup_point', [], $locale) }}
                                </div>
                                @if(isset($order->shipping_address['packeta_point_name']))
                                <p style="font-size: 14px; color: #1c1c1c; line-height: 1.6; margin: 4px 0;">
                                    <span style="color: #CA4136;">→</span> {{ $order->shipping_address['packeta_point_name'] }}
                                </p>
                                @if(isset($order->shipping_address['packeta_point_address']))
                                <p style="font-size: 13px; color: #5a5a5a; line-height: 1.6; margin: 4px 0 4px 18px;">{{ $order->shipping_address['packeta_point_address'] }}</p>
                                @endif
                                @endif
                            </div>
                            
                            <!-- Order Items -->
                            <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 32px 0 20px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                {{ __('emails.order_shipped.order_contents', [], $locale) }}
                            </div>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 1px solid #bcbeb1;">
                                @foreach($order->items as $index => $item)
                                <tr>
                                    <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1; vertical-align: top; width: 32px;">
                                        <span style="font-size: 11px; color: #CA4136; font-weight: 400;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1; vertical-align: top;">
                                        <div style="font-weight: 400; color: #1c1c1c; margin-bottom: 2px; font-size: 15px;">{{ $item->product_name }}</div>
                                        @if($item->product && $item->product->roastery)
                                        <div style="font-size: 12px; color: #5a5a5a; font-weight: 400; margin-bottom: 4px;">{{ $item->product->roastery->name }}</div>
                                        @endif
                                        <div style="font-size: 13px; color: #76716C; font-weight: 400;">{{ $item->quantity }}× {{ \App\Helpers\CurrencyHelper::formatByCurrency($item->price, $order->currency, 0) }}</div>
                                    </td>
                                    <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1; vertical-align: top; text-align: right;">
                                        <span style="font-weight: 400; color: #1c1c1c; white-space: nowrap; font-size: 15px;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($item->quantity * $item->price, $order->currency, 0) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            
                            <!-- What's Next -->
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 12px;">
                                    {{ $locale === 'cs' ? 'Co se stane dále' : 'What happens next' }}
                                </div>
                                <p style="font-size: 14px; color: #5a5a5a; margin: 0; line-height: 1.8;">
                                    @if($locale === 'cs')
                                    <span style="color: #CA4136;">01</span> Zásilka dorazí na výdejní místo během 1-2 dnů<br>
                                    <span style="color: #CA4136;">02</span> Dostanete SMS s kódem pro vyzvednutí<br>
                                    <span style="color: #CA4136;">03</span> Vyzvedněte si balík na výdejním místě<br>
                                    <span style="color: #CA4136;">04</span> Vychutnejte si čerstvou kávu
                                    @else
                                    <span style="color: #CA4136;">01</span> The package will arrive at the pickup point within 1-2 days<br>
                                    <span style="color: #CA4136;">02</span> You will receive an SMS with the pickup code<br>
                                    <span style="color: #CA4136;">03</span> Pick up your package at the pickup point<br>
                                    <span style="color: #CA4136;">04</span> Enjoy your fresh coffee
                                    @endif
                                </p>
                            </div>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 40px 0;">
                                <a href="{{ route('dashboard.order.detail', $order->id) }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                    {{ __('emails.order_confirmation.view_order', [], $locale) }} →
                                </a>
                            </div>
                            
                            <!-- Features -->
                            <div style="margin: 40px 0 32px 0; padding-top: 24px; border-top: 1px solid #bcbeb1;">
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ __('emails.common.freshly_roasted', [], $locale) }}
                                </span>
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ __('emails.common.delivery_time', [], $locale) }}
                                </span>
                            </div>
                            
                            <!-- Help Text -->
                            <p style="font-size: 13px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ __('emails.common.questions', [], $locale) }} 
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 13px; color: #5a5a5a; margin-top: 24px;">
                                {{ __('emails.common.regards', [], $locale) }},<br>
                                <span style="color: #1c1c1c;">{{ __('emails.common.team', [], $locale) }}</span>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #d5d7ca; padding: 40px; text-align: center; color: #5a5a5a; font-size: 12px;">
                            <p style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin: 0 0 4px 0;">
                                {{ $siteName }}
                            </p>
                            <p style="font-size: 12px; color: #5a5a5a; margin: 0 0 24px 0;">
                                {{ __('emails.common.tagline', [], $locale) }}
                            </p>
                            <div style="margin: 20px 0;">
                                <a href="{{ route('home') }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ route('products.index') }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ route('dashboard.index') }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.my_account', [], $locale) }}</a>
                            </div>
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #bcbeb1; font-size: 11px; color: #76716C;">
                                <p style="margin: 0;">{{ __('emails.common.copyright', ['year' => date('Y')], $locale) }}</p>
                            </div>
                        </td>
                    </tr>
                    
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
