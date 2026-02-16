<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.subscription_confirmation.title', [], $locale) }}</title>
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
        
        /* Dark mode override - keep light */
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
                                {{ __('emails.subscription_confirmation.title', [], $locale) }}
                            </h1>
                            <p style="font-size: 14px; color: #76716C; margin: 0 0 40px 0; font-weight: 400; text-transform: uppercase; letter-spacing: 0.1em;">
                                {{ __('emails.subscription_confirmation.subtitle', [], $locale) }}
                            </p>
                            
                            <!-- Subscription Number -->
                            <div style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
                                <div style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.subscription_confirmation.subscription_number', [], $locale) }}
                                </div>
                                <div style="font-size: 28px; font-weight: 400; color: #1c1c1c; letter-spacing: -0.02em;">
                                    {{ $subscription->subscription_number }}
                                </div>
                            </div>
                            
                            <!-- Subscription Configuration -->
                            <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 32px 0 20px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                {{ __('emails.subscription_confirmation.your_config', [], $locale) }}
                            </div>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 1px solid #bcbeb1;">
                                <tr>
                                    <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1;">
                                        <span style="font-size: 14px; color: #76716C;">{{ $locale === 'cs' ? 'Typ kávy' : 'Coffee Type' }}</span><br>
                                        <span style="font-size: 16px; color: #1c1c1c;">
                                            @if($subscription->configuration['type'] === 'espresso')
                                                Espresso
                                            @elseif($subscription->configuration['type'] === 'filter')
                                                {{ $locale === 'cs' ? 'Filtr' : 'Filter' }}
                                            @else
                                                Mix ({{ $subscription->configuration['mix']['espresso'] ?? 0 }}x Espresso, {{ $subscription->configuration['mix']['filter'] ?? 0 }}x {{ $locale === 'cs' ? 'Filtr' : 'Filter' }})
                                            @endif
                                            @if($subscription->configuration['isDecaf'] ?? false)
                                                <span style="color: #5a5a5a;"> / Decaf</span>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1;">
                                        <span style="font-size: 14px; color: #76716C;">{{ __('emails.subscription_confirmation.bags_count', [], $locale) }}</span><br>
                                        <span style="font-size: 16px; color: #1c1c1c;">{{ $subscription->configuration['amount'] }}x 250g</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1;">
                                        <span style="font-size: 14px; color: #76716C;">{{ __('emails.subscription_confirmation.frequency', [], $locale) }}</span><br>
                                        <span style="font-size: 16px; color: #1c1c1c;">
                                            @if($subscription->frequency_months == 0)
                                                {{ $locale === 'cs' ? 'Jednorázový box' : 'One-time box' }}
                                            @elseif($subscription->frequency_months == 1)
                                                {{ __('emails.frequency.monthly', [], $locale) }}
                                            @elseif($subscription->frequency_months == 2)
                                                {{ __('emails.frequency.bimonthly', [], $locale) }}
                                            @else
                                                {{ __('emails.frequency.quarterly', [], $locale) }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Price Summary -->
                            @php
                            $activeDiscount = ($subscription->discount_amount > 0 && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0)) ? $subscription->discount_amount : 0;
                            $currentPrice = $subscription->configured_price - $activeDiscount;
                            @endphp
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #1c1c1c;">
                                @if($activeDiscount > 0)
                                <tr>
                                    <td style="padding: 6px 0; font-size: 14px; color: #5a5a5a;">{{ __('emails.subscription_confirmation.price', [], $locale) }}:</td>
                                    <td style="padding: 6px 0; font-size: 14px; color: #5a5a5a; text-align: right; text-decoration: line-through;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($subscription->configured_price, $subscription->currency, 0) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px 0; font-size: 14px; color: #5a5a5a;">{{ __('emails.order_confirmation.discount', [], $locale) }}{{ $subscription->coupon_code ? ' (' . $subscription->coupon_code . ')' : '' }}:</td>
                                    <td style="padding: 6px 0; font-size: 13px; color: #4a6741; text-align: right;">-{{ \App\Helpers\CurrencyHelper::formatByCurrency($activeDiscount, $subscription->currency, 0) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 20px 0 6px 0; font-size: 11px; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em;">{{ $activeDiscount > 0 ? ($locale === 'cs' ? 'Cena po slevě' : 'Price after discount') : __('emails.subscription_confirmation.price', [], $locale) }}:</td>
                                    <td style="padding: 20px 0 6px 0; font-size: 28px; color: #1c1c1c; text-align: right; letter-spacing: -0.02em;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($currentPrice, $subscription->currency, 0) }}</td>
                                </tr>
                            </table>
                            
                            <!-- Next Shipment -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ $subscription->frequency_months == 0 ? ($locale === 'cs' ? 'Rozesílka' : 'Shipment') : __('emails.subscription_confirmation.next_delivery', [], $locale) }}
                                </div>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.6; margin: 4px 0;">
                                    <span style="color: #CA4136;">→</span> {{ $subscription->next_shipment_date ? $subscription->next_shipment_date->format('j. n. Y') : ($locale === 'cs' ? 'Brzy' : 'Soon') }}
                                </p>
                                @if($subscription->frequency_months > 0 && $subscription->next_billing_date)
                                @php
                                    $nextBillingDate = \Carbon\Carbon::parse($subscription->next_billing_date);
                                    $nextShipmentSchedule = \App\Models\ShipmentSchedule::getForMonth($nextBillingDate->year, $nextBillingDate->month);
                                    $nextShipmentAfterBilling = $nextShipmentSchedule ? $nextShipmentSchedule->shipment_date : $nextBillingDate->copy()->day(20);
                                @endphp
                                <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 12px 0 4px 18px;">
                                    {{ $locale === 'cs' ? 'Další platba' : 'Next payment' }}: {{ $nextBillingDate->format('j. n. Y') }}<br>
                                    {{ $locale === 'cs' ? 'Další doručení' : 'Next delivery' }}: {{ $locale === 'cs' ? 'cca' : 'approx.' }} {{ $nextShipmentAfterBilling->format('j. n. Y') }}
                                </p>
                                @endif
                            </div>
                            
                            <!-- Delivery Info -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.order_confirmation.delivery', [], $locale) }}
                                </div>
                                @if(isset($subscription->packeta_point_name))
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.6; margin: 4px 0;">
                                    <span style="color: #CA4136;">→</span> {{ $subscription->packeta_point_name }}
                                </p>
                                @if(isset($subscription->packeta_point_address))
                                <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 4px 0 4px 18px;">{{ $subscription->packeta_point_address }}</p>
                                @endif
                                @endif
                                @if($subscription->delivery_notes)
                                <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 12px 0 4px 18px;">
                                    {{ $locale === 'cs' ? 'Poznámka' : 'Note' }}: {{ $subscription->delivery_notes }}
                                </p>
                                @endif
                            </div>
                            
                            <!-- Billing Info -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.order_confirmation.billing_info', [], $locale) }}
                                </div>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.6; margin: 4px 0;">
                                    <span style="color: #CA4136;">→</span> {{ $subscription->shipping_address['name'] }}
                                </p>
                                <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 4px 0 4px 18px;">
                                    {{ $subscription->shipping_address['billing_address'] }}<br>
                                    {{ $subscription->shipping_address['billing_postal_code'] }} {{ $subscription->shipping_address['billing_city'] }}
                                </p>
                                <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 12px 0 4px 18px;">
                                    {{ $subscription->shipping_address['email'] }}
                                    @if(isset($subscription->shipping_address['phone']))
                                    <br>{{ $subscription->shipping_address['phone'] }}
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Subscription Status -->
                            @if($subscription->frequency_months == 0)
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ $locale === 'cs' ? 'Stav objednávky' : 'Order Status' }}
                                </div>
                                <p style="font-size: 15px; color: #4a6741; margin: 0;">
                                    {{ $locale === 'cs' ? 'Vaše objednávka byla potvrzena. Jedná se o jednorázový nákup bez dalších plateb.' : 'Your order has been confirmed. This is a one-time purchase with no recurring payments.' }}
                                </p>
                            </div>
                            @elseif($subscription->status === 'active')
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ $locale === 'cs' ? 'Stav předplatného' : 'Subscription Status' }}
                                </div>
                                <p style="font-size: 15px; color: #4a6741; margin: 0;">
                                    {{ $locale === 'cs' ? 'Vaše předplatné je aktivní. Další platba proběhne automaticky.' : 'Your subscription is active. The next payment will be processed automatically.' }}
                                </p>
                            </div>
                            @else
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #CA4136; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ $locale === 'cs' ? 'Stav předplatného' : 'Subscription Status' }}
                                </div>
                                <p style="font-size: 15px; color: #5a5a5a; margin: 0;">
                                    {{ $locale === 'cs' ? 'Vaše předplatné čeká na aktivaci. Po potvrzení platby bude automaticky aktivováno.' : 'Your subscription is pending activation. It will be activated automatically once payment is confirmed.' }}
                                </p>
                            </div>
                            @endif
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 40px 0;">
                                <a href="{{ route('dashboard.subscription') }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                    {{ __('emails.subscription_confirmation.manage_button', [], $locale) }} →
                                </a>
                            </div>
                            
                            <!-- Features -->
                            <div style="margin: 40px 0 32px 0; padding-top: 24px; border-top: 1px solid #bcbeb1;">
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ __('emails.common.freshly_roasted', [], $locale) }}
                                </span>
                                @if($subscription->frequency_months == 0)
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ $locale === 'cs' ? 'Jednorázový nákup' : 'One-time purchase' }}
                                </span>
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ $locale === 'cs' ? 'Bez závazku' : 'No commitment' }}
                                </span>
                                @else
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ $locale === 'cs' ? 'Flexibilní předplatné' : 'Flexible subscription' }}
                                </span>
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ $locale === 'cs' ? 'Zrušení kdykoliv' : 'Cancel anytime' }}
                                </span>
                                @endif
                            </div>
                            
                            <!-- Help Text -->
                            <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ __('emails.subscription_confirmation.manage_subscription', [], $locale) }} {{ __('emails.subscription_confirmation.help_text', [], $locale) }} 
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 14px; color: #5a5a5a; margin-top: 24px;">
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
                                <a href="{{ route('dashboard.subscription') }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.subscription', [], $locale) }}</a>
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
