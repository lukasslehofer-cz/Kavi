<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.order_delivered.title', [], $locale) }}</title>
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
                                {{ __('emails.order_delivered.title', [], $locale) }}
                            </h1>
                            <p style="font-size: 14px; color: #76716C; margin: 0 0 40px 0; font-weight: 400; text-transform: uppercase; letter-spacing: 0.1em;">
                                {{ __('emails.order_delivered.subtitle', [], $locale) }}
                            </p>
                            
                            <!-- Order Number -->
                            <div style="border-top: 2px solid #4a6741; padding: 24px 0; margin: 32px 0;">
                                <div style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.order_delivered.order_number', [], $locale) }}
                                </div>
                                <div style="font-size: 28px; font-weight: 400; color: #1c1c1c; letter-spacing: -0.02em;">
                                    {{ $order->order_number }}
                                </div>
                            </div>
                            
                            <!-- Thank You Message -->
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ $locale === 'cs' ? 'Děkujeme za nákup' : 'Thank you for your purchase' }}
                                </div>
                                <p style="font-size: 14px; color: #4a6741; margin: 0;">
                                    @if($locale === 'cs')
                                    Doufáme, že si kávu vychutnáte! Pokud budete mít jakékoli dotazy nebo připomínky, neváhejte nás kontaktovat.
                                    @else
                                    We hope you enjoy your coffee! If you have any questions or feedback, don't hesitate to contact us.
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Order Items -->
                            <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 32px 0 20px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                {{ $locale === 'cs' ? 'Co jste si vyzvedli' : 'What you picked up' }}
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
