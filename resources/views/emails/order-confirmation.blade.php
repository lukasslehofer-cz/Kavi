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
        
        /* Header - Dark with white logo */
        .header { 
            background-color: #1c1c1c; 
            padding: 32px 40px; 
            text-align: left;
        }
        
        .logo { 
            max-width: 80px !important; 
            width: 80px !important; 
            height: auto !important; 
            display: block !important; 
            margin: 0 !important; 
        }
        
        /* Content Area */
        .content { 
            padding: 48px 40px; 
            color: #4a4a4a; 
        }
        
        /* Typography - Swiss Style */
        h1 { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 32px; 
            font-weight: 400; 
            color: #1c1c1c; 
            margin: 0 0 8px 0; 
            line-height: 1.1;
            letter-spacing: -0.02em;
            text-transform: uppercase;
        }
        
        .subtitle { 
            font-size: 14px; 
            color: #76716C; 
            margin: 0 0 40px 0; 
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        /* Order Number - Prominent Display */
        .order-number { 
            border-top: 2px solid #CA4136;
            padding: 24px 0; 
            margin: 32px 0; 
        }
        
        .order-number-label { 
            font-size: 11px; 
            color: #76716C; 
            font-weight: 400; 
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }
        
        .order-number-value { 
            font-size: 28px; 
            font-weight: 400; 
            color: #1c1c1c;
            letter-spacing: -0.02em;
        }
        
        /* Section Title */
        .section-title { 
            font-size: 11px; 
            font-weight: 400; 
            color: #76716C; 
            margin: 0 0 20px 0;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }
        
        /* Order Items - Swiss Grid Style */
        .order-items { 
            margin: 32px 0;
            border-top: 1px solid #bcbeb1;
        }
        
        .item { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start;
            padding: 16px 0; 
            border-bottom: 1px solid #bcbeb1; 
        }
        
        .item-number {
            font-size: 11px;
            color: #CA4136;
            font-weight: 400;
            min-width: 32px;
            padding-top: 2px;
        }
        
        .item-content {
            flex: 1;
        }
        
        .item-name { 
            font-weight: 400; 
            color: #1c1c1c; 
            margin-bottom: 4px;
            font-size: 15px;
        }
        
        .item-details { 
            font-size: 13px; 
            color: #76716C; 
            font-weight: 400; 
        }
        
        .item-price { 
            font-weight: 400; 
            color: #1c1c1c; 
            white-space: nowrap; 
            margin-left: 16px;
            font-size: 15px;
        }
        
        /* Totals - Clean Lines */
        .totals { 
            margin: 32px 0; 
            padding-top: 24px;
            border-top: 2px solid #1c1c1c;
        }
        
        .total-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 6px 0; 
            font-size: 13px; 
        }
        
        .total-row.highlight { 
            padding-top: 16px; 
            margin-top: 12px; 
        }
        
        .total-label { 
            color: #5a5a5a; 
            font-weight: 400; 
        }
        
        .total-value { 
            font-weight: 400; 
            color: #1c1c1c; 
        }
        
        .total-row.highlight .total-label { 
            font-weight: 400; 
            color: #1c1c1c; 
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }
        
        .total-row.highlight .total-value { 
            font-size: 28px;
            letter-spacing: -0.02em;
        }
        
        /* Info Sections - Swiss Grid */
        .info-section {
            margin: 32px 0;
            padding-top: 24px;
            border-top: 2px solid #CA4136;
        }
        
        .info-title { 
            font-size: 11px; 
            font-weight: 400; 
            color: #76716C; 
            margin: 0 0 16px 0;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }
        
        .info-text { 
            font-size: 14px; 
            color: #1c1c1c; 
            line-height: 1.6; 
            margin: 4px 0; 
        }
        
        .info-text-secondary {
            font-size: 13px;
            color: #5a5a5a;
            line-height: 1.6;
            margin: 4px 0;
        }
        
        /* Payment Status */
        .payment-status {
            margin: 32px 0;
            padding: 20px 24px;
            border-left: 3px solid #CA4136;
            background-color: #d5d7ca;
        }
        
        .payment-status.success {
            border-left-color: #636747;
        }
        
        .payment-status-title {
            font-size: 11px;
            font-weight: 400;
            color: #1c1c1c;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 8px;
        }
        
        .payment-status-text {
            font-size: 14px;
            color: #5a5a5a;
        }
        
        /* CTA Button - Swiss Square */
        .button { 
            display: inline-block; 
            background-color: #1c1c1c; 
            color: #ffffff !important; 
            text-decoration: none; 
            padding: 16px 32px; 
            font-weight: 400; 
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin: 32px 0;
        }
        
        .button:hover {
            background-color: #CA4136;
        }
        
        .button-arrow {
            margin-left: 8px;
        }
        
        /* Features - Swiss Style */
        .features {
            margin: 40px 0 32px 0;
            padding-top: 24px;
            border-top: 1px solid #bcbeb1;
        }
        
        .feature {
            display: inline-block;
            margin: 0 24px 8px 0;
            font-size: 12px;
            color: #5a5a5a;
        }
        
        .feature-arrow {
            color: #CA4136;
            margin-right: 6px;
        }
        
        /* Footer - Minimal */
        .footer { 
            background-color: #d5d7ca; 
            padding: 40px; 
            text-align: center; 
            color: #5a5a5a; 
            font-size: 12px;
        }
        
        .footer-brand {
            font-size: 11px;
            font-weight: 400;
            color: #1c1c1c;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 4px;
        }
        
        .footer-tagline {
            font-size: 12px;
            color: #5a5a5a;
            margin-bottom: 24px;
        }
        
        .footer-links { 
            margin: 20px 0; 
        }
        
        .footer-link { 
            color: #1c1c1c; 
            text-decoration: none; 
            margin: 0 12px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .footer-link:hover {
            color: #CA4136;
        }
        
        .footer-copyright { 
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #bcbeb1;
            font-size: 11px;
            color: #76716C;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) { 
            .content { padding: 32px 24px !important; } 
            h1 { font-size: 26px !important; } 
            .header, .footer { padding: 32px 24px !important; } 
            .logo { max-width: 80px !important; width: 80px !important; }
            .order-number-value { font-size: 24px !important; }
            .total-row.highlight .total-value { font-size: 24px !important; }
        }
        
        /* Dark mode override - keep light */
        @media (prefers-color-scheme: dark) { 
            body { background-color: #bcbeb1 !important; } 
            .email-container { background-color: #e5e6df !important; } 
            .header { background-color: #1c1c1c !important; }
            h1, .item-name, .total-value, .info-text { color: #1c1c1c !important; }
            .footer { background-color: #d5d7ca !important; }
        }
        
        [data-ogsc] .email-container { background-color: #e5e6df !important; }
        [data-ogsc] .header { background-color: #1c1c1c !important; }
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
                        <td class="header" style="background-color: #1c1c1c; padding: 32px 40px; text-align: left;">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="{{ $siteName }}" class="logo" width="80" style="max-width: 80px !important; width: 80px !important; height: auto !important; display: block !important; border: 0; outline: none;">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content" style="padding: 48px 40px; color: #4a4a4a; background-color: #e5e6df;">
                            
                            <!-- Title -->
                            <h1 style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 400; color: #1c1c1c; margin: 0 0 8px 0; line-height: 1.1; letter-spacing: -0.02em; text-transform: uppercase;">
                                {{ __('emails.order_confirmation.title', [], $locale) }}
                            </h1>
                            <p class="subtitle" style="font-size: 14px; color: #76716C; margin: 0 0 40px 0; font-weight: 400; text-transform: uppercase; letter-spacing: 0.1em;">
                                {{ __('emails.order_confirmation.subtitle', [], $locale) }}
                            </p>
                            
                            <!-- Order Number -->
                            <div class="order-number" style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
                                <div class="order-number-label" style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.order_confirmation.order_number', [], $locale) }}
                                </div>
                                <div class="order-number-value" style="font-size: 28px; font-weight: 400; color: #1c1c1c; letter-spacing: -0.02em;">
                                    {{ $order->order_number }}
                                </div>
                            </div>
                            
                            <!-- Order Items -->
                            <div class="section-title" style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 20px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                {{ __('emails.order_confirmation.order_contents', [], $locale) }}
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
                                        <span style="font-weight: 400; color: #1c1c1c; white-space: nowrap; font-size: 15px;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($item->total, $order->currency, 0) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            
                            <!-- Totals -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #1c1c1c;">
                                <tr>
                                    <td style="padding: 6px 0; font-size: 13px; color: #5a5a5a;">{{ __('emails.order_confirmation.subtotal_without_vat', [], $locale) }}:</td>
                                    <td style="padding: 6px 0; font-size: 13px; color: #1c1c1c; text-align: right;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->subtotal / 1.21, $order->currency, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; font-size: 13px; color: #5a5a5a;">{{ __('emails.order_confirmation.vat', [], $locale) }}:</td>
                                    <td style="padding: 6px 0; font-size: 13px; color: #1c1c1c; text-align: right;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->tax, $order->currency, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; font-size: 13px; color: #5a5a5a;">{{ __('emails.order_confirmation.shipping', [], $locale) }}:</td>
                                    <td style="padding: 6px 0; font-size: 13px; text-align: right;">
                                        @if($order->shipping == 0)
                                        <span style="color: #4a6741; text-transform: uppercase; font-size: 11px; letter-spacing: 0.1em;">{{ __('emails.common.free', [], $locale) }}</span>
                                        @else
                                        <span style="color: #1c1c1c;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->shipping, $order->currency, 0) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td style="padding: 6px 0; font-size: 13px; color: #5a5a5a;">{{ __('emails.order_confirmation.discount', [], $locale) }}{{ $order->coupon_code ? ' (' . $order->coupon_code . ')' : '' }}:</td>
                                    <td style="padding: 6px 0; font-size: 13px; color: #4a6741; text-align: right;">-{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->discount_amount, $order->currency, 0) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 20px 0 6px 0; font-size: 11px; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em;">{{ __('emails.order_confirmation.total', [], $locale) }}:</td>
                                    <td style="padding: 20px 0 6px 0; font-size: 28px; color: #1c1c1c; text-align: right; letter-spacing: -0.02em;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->total, $order->currency, 0) }}</td>
                                </tr>
                            </table>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 40px 0;">
                                <a href="{{ route('dashboard.order.detail', $order->id) }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                    {{ __('emails.order_confirmation.view_order', [], $locale) }} →
                                </a>
                            </div>
                            
                            <!-- Delivery Info -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.order_confirmation.delivery', [], $locale) }}
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
                            
                            <!-- Billing Info -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.order_confirmation.billing_info', [], $locale) }}
                                </div>
                                <p style="font-size: 14px; color: #1c1c1c; line-height: 1.6; margin: 4px 0;">
                                    <span style="color: #CA4136;">→</span> {{ $order->shipping_address['name'] }}
                                </p>
                                <p style="font-size: 13px; color: #5a5a5a; line-height: 1.6; margin: 4px 0 4px 18px;">
                                    {{ $order->shipping_address['billing_address'] }}<br>
                                    {{ $order->shipping_address['billing_postal_code'] }} {{ $order->shipping_address['billing_city'] }}
                                </p>
                                <p style="font-size: 13px; color: #5a5a5a; line-height: 1.6; margin: 12px 0 4px 18px;">
                                    {{ $order->shipping_address['email'] }}<br>
                                    {{ $order->shipping_address['phone'] }}
                                </p>
                            </div>
                            
                            <!-- Payment Status -->
                            @if($order->payment_status === 'paid')
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ __('emails.order_confirmation.payment', [], $locale) }}
                                </div>
                                <p style="font-size: 14px; color: #4a6741; margin: 0;">
                                    {{ __('emails.order_confirmation.payment_received', [], $locale) }}
                                </p>
                            </div>
                            @else
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #CA4136; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ __('emails.order_confirmation.payment', [], $locale) }}
                                </div>
                                <p style="font-size: 14px; color: #5a5a5a; margin: 0;">
                                    {{ __('emails.order_confirmation.payment_pending', [], $locale) }}
                                </p>
                            </div>
                            @endif
                            
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
                                {{ __('emails.order_confirmation.help_text', [], $locale) }} 
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
                        <td class="footer" style="background-color: #d5d7ca; padding: 40px; text-align: center; color: #5a5a5a; font-size: 12px;">
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
                                <p style="margin: 0 0 8px 0;">{{ __('emails.common.copyright', ['year' => date('Y')], $locale) }}</p>
                                <p style="margin: 0;">{{ __('emails.order_confirmation.footer_text', ['email' => $order->shipping_address['email']], $locale) }}</p>
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
