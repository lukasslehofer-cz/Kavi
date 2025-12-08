<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.order_confirmation.title', [], $locale) }}</title>
    <style>
        /* Reset styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100%; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #111827; padding: 32px 40px; text-align: center; }
        .logo { max-width: 120px !important; width: 120px !important; height: auto !important; display: block !important; margin: 0 auto !important; }
        .content { padding: 40px; color: #374151; }
        h1 { font-size: 28px; font-weight: 700; color: #111827; margin: 0 0 12px 0; line-height: 1.2; }
        .subtitle { font-size: 16px; color: #6b7280; margin: 0 0 32px 0; font-weight: 300; }
        .order-number { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center; }
        .order-number-label { font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px; }
        .order-number-value { font-size: 20px; font-weight: 700; color: #111827; }
        .order-items { background-color: #f9fafb; border-radius: 12px; padding: 20px; margin: 24px 0; }
        .section-title { font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 16px 0; }
        .item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
        .item:last-child { border-bottom: none; }
        .item-name { font-weight: 500; color: #111827; margin-bottom: 4px; }
        .item-details { font-size: 14px; color: #6b7280; font-weight: 300; }
        .item-price { font-weight: 700; color: #111827; white-space: nowrap; margin-left: 16px; }
        .totals { margin: 24px 0; padding-top: 16px; }
        .total-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; }
        .total-row.highlight { padding-top: 16px; border-top: 2px solid #e5e7eb; margin-top: 8px; }
        .total-label { color: #6b7280; font-weight: 300; }
        .total-value { font-weight: 700; color: #111827; }
        .total-row.highlight .total-label { font-weight: 700; color: #111827; font-size: 16px; }
        .total-row.highlight .total-value { font-size: 24px; }
        .info-box { background-color: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin: 24px 0; }
        .info-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px 0; }
        .info-text { font-size: 14px; color: #4b5563; line-height: 1.6; margin: 4px 0; }
        .button { display: inline-block; background-color: #e6305a; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 9999px; font-weight: 600; font-size: 15px; margin: 24px 0; text-align: center; }
        .footer { background-color: #f9fafb; padding: 32px 40px; text-align: center; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb; }
        .footer-text { margin: 8px 0; font-weight: 300; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #e6305a; text-decoration: none; margin: 0 8px; }
        .trust-badges { margin: 24px 0; text-align: center; }
        .badge { display: inline-block; margin: 8px 12px; font-size: 13px; color: #059669; }
        @media only screen and (max-width: 600px) { .content { padding: 24px !important; } h1 { font-size: 24px !important; } .header, .footer { padding: 24px !important; } .logo { max-width: 100px !important; width: 100px !important; } }
        @media (prefers-color-scheme: dark) { body { background-color: #1a1a1a !important; } .email-container { background-color: #ffffff !important; border: 1px solid #d1d5db !important; } .info-box, .order-items { background-color: #f9fafb !important; border: 1px solid #d1d5db !important; } h1, .info-title, .section-title, .item-name { color: #111827 !important; } .subtitle, .info-text, .item-details { color: #4b5563 !important; } .header { background-color: #111827 !important; } }
        [data-ogsc] .email-container { background-color: #ffffff !important; border: 1px solid #d1d5db !important; }
        [data-ogsc] .info-box, [data-ogsc] .order-items { background-color: #f9fafb !important; border: 1px solid #d1d5db !important; }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6; padding: 20px 0;">
        <tr>
            <td align="center">
                <!--[if mso]><table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"><tr><td><![endif]-->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" width="100%" style="width: 100%; max-width: 600px; background-color: #ffffff !important; border: 1px solid #e5e7eb !important; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);" bgcolor="#ffffff">
                    
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="{{ $siteName }}" class="logo" width="120" style="max-width: 120px !important; width: 120px !important; height: auto !important; display: block !important; margin: 0 auto !important; border: 0; outline: none;">
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
                            
                            <h1 style="text-align: center;">{{ __('emails.order_confirmation.title', [], $locale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.order_confirmation.subtitle', [], $locale) }}</p>
                            
                            <!-- Order Number -->
                            <div class="order-number">
                                <div class="order-number-label">{{ __('emails.order_confirmation.order_number', [], $locale) }}</div>
                                <div class="order-number-value">{{ $order->order_number }}</div>
                            </div>
                            
                            <!-- Order Items -->
                            <div class="order-items">
                                <h2 class="section-title">{{ __('emails.order_confirmation.order_contents', [], $locale) }}</h2>
                                @foreach($order->items as $item)
                                <div class="item">
                                    <div style="flex: 1;">
                                        <div class="item-name">{{ $item->product_name }}</div>
                                        <div class="item-details">{{ $item->quantity }}× {{ \App\Helpers\CurrencyHelper::formatByCurrency($item->price, $order->currency, 0) }}</div>
                                    </div>
                                    <div class="item-price">{{ \App\Helpers\CurrencyHelper::formatByCurrency($item->total, $order->currency, 0) }}</div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Totals -->
                            <div class="totals">
                                <div class="total-row">
                                    <span class="total-label">{{ __('emails.order_confirmation.subtotal_without_vat', [], $locale) }}:</span>
                                    <span class="total-value">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->subtotal / 1.21, $order->currency, 2) }}</span>
                                </div>
                                <div class="total-row">
                                    <span class="total-label">{{ __('emails.order_confirmation.vat', [], $locale) }}:</span>
                                    <span class="total-value">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->tax, $order->currency, 2) }}</span>
                                </div>
                                <div class="total-row">
                                    <span class="total-label">{{ __('emails.order_confirmation.shipping', [], $locale) }}:</span>
                                    <span class="total-value">
                                        @if($order->shipping == 0)
                                        <span style="color: #059669;">{{ __('emails.common.free', [], $locale) }}</span>
                                        @else
                                        {{ \App\Helpers\CurrencyHelper::formatByCurrency($order->shipping, $order->currency, 0) }}
                                        @endif
                                    </span>
                                </div>
                                @if($order->discount_amount > 0)
                                <div class="total-row">
                                    <span class="total-label">{{ __('emails.order_confirmation.discount', [], $locale) }}{{ $order->coupon_code ? ' (' . $order->coupon_code . ')' : '' }}:</span>
                                    <span class="total-value" style="color: #059669;">-{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->discount_amount, $order->currency, 0) }}</span>
                                </div>
                                @endif
                                <div class="total-row highlight">
                                    <span class="total-label">{{ __('emails.order_confirmation.total', [], $locale) }}:</span>
                                    <span class="total-value">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->total, $order->currency, 0) }}</span>
                                </div>
                            </div>
                            
                            <!-- Shipping Info -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📦 {{ __('emails.order_confirmation.delivery', [], $locale) }}</h3>
                                @if(isset($order->shipping_address['packeta_point_name']))
                                <p class="info-text"><strong>{{ __('emails.order_confirmation.pickup_point', [], $locale) }}:</strong></p>
                                <p class="info-text">{{ $order->shipping_address['packeta_point_name'] }}</p>
                                @if(isset($order->shipping_address['packeta_point_address']))
                                <p class="info-text" style="color: #6b7280;">{{ $order->shipping_address['packeta_point_address'] }}</p>
                                @endif
                                @endif
                            </div>
                            
                            <!-- Billing Info -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📋 {{ __('emails.order_confirmation.billing_info', [], $locale) }}</h3>
                                <p class="info-text"><strong>{{ $order->shipping_address['name'] }}</strong></p>
                                <p class="info-text">{{ $order->shipping_address['billing_address'] }}</p>
                                <p class="info-text">{{ $order->shipping_address['billing_postal_code'] }} {{ $order->shipping_address['billing_city'] }}</p>
                                <p class="info-text" style="margin-top: 8px;">
                                    <strong>{{ __('emails.order_confirmation.email_label', [], $locale) }}:</strong> {{ $order->shipping_address['email'] }}<br>
                                    <strong>{{ __('emails.order_confirmation.phone_label', [], $locale) }}:</strong> {{ $order->shipping_address['phone'] }}
                                </p>
                            </div>
                            
                            <!-- Payment Status -->
                            @if($order->payment_status === 'paid')
                            <div class="info-box" style="background-color: #d1fae5 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#d1fae5">
                                <h3 class="info-title" style="color: #065f46;">💳 {{ __('emails.order_confirmation.payment', [], $locale) }}</h3>
                                <p class="info-text" style="color: #047857;">
                                    {{ __('emails.order_confirmation.payment_received', [], $locale) }}
                                </p>
                            </div>
                            @else
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e;">💳 {{ __('emails.order_confirmation.payment', [], $locale) }}</h3>
                                <p class="info-text" style="color: #78350f;">
                                    {{ __('emails.order_confirmation.payment_pending', [], $locale) }}
                                </p>
                            </div>
                            @endif
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.order.detail', $order->id) }}" class="button">
                                    {{ __('emails.order_confirmation.view_order', [], $locale) }}
                                </a>
                            </div>
                            
                            <!-- Trust Badges -->
                            <div class="trust-badges">
                                <div class="badge">✓ {{ __('emails.common.freshly_roasted', [], $locale) }}</div>
                                <div class="badge">✓ {{ __('emails.common.delivery_time', [], $locale) }}</div>
                                <div class="badge">✓ {{ __('emails.common.support_24_7', [], $locale) }}</div>
                            </div>
                            
                            <!-- Additional Info -->
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                {{ __('emails.order_confirmation.help_text', [], $locale) }} 
                                <a href="mailto:{{ $contactEmail }}" style="color: #e6305a; text-decoration: none;">{{ $contactEmail }}</a>                                 
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px; font-weight: 300;">
                                {{ __('emails.common.regards', [], $locale) }},<br>
                                <strong style="color: #111827;">{{ __('emails.common.team', [], $locale) }}</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #111827;">{{ $siteName }}</strong><br>
                                {{ __('emails.common.tagline', [], $locale) }}
                            </p>
                            <div class="footer-links">
                                <a href="{{ route('home') }}" class="footer-link" style="color: #e6305a;">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ route('products.index') }}" class="footer-link" style="color: #e6305a;">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ route('dashboard.index') }}" class="footer-link" style="color: #e6305a;">{{ __('emails.common.my_account', [], $locale) }}</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                {{ __('emails.common.copyright', ['year' => date('Y')], $locale) }}
                            </p>
                            <p class="footer-text" style="font-size: 12px;">
                                {{ __('emails.order_confirmation.footer_text', ['email' => $order->shipping_address['email']], $locale) }}
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
