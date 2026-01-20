<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.subscription_confirmation.title', [], $locale) }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100%; background-color: #bcbeb1; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #e5e6df; }
        .header { background-color: #1c1c1c; padding: 32px 40px 24px 40px; text-align: left; border-bottom: 2px solid #CA4136; }
        .logo { max-width: 100px !important; width: 100px !important; height: auto !important; display: block !important; margin: 0 !important; border: 0; outline: none; }
        .content { padding: 40px; color: #1c1c1c; }
        h1 { font-size: 24px; font-weight: 700; color: #1c1c1c; margin: 0 0 12px 0; line-height: 1.3; text-transform: uppercase; letter-spacing: 1px; }
        .subtitle { font-size: 15px; color: #5a5a5a; margin: 0 0 32px 0; font-weight: 400; }
        .section-title { font-size: 13px; font-weight: 700; color: #1c1c1c; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 2px solid #1c1c1c; padding-bottom: 8px; display: inline-block; }
        .info-box { background-color: #d5d7ca; border-radius: 0; padding: 20px; margin: 24px 0; }
        .info-title { font-size: 13px; font-weight: 700; color: #1c1c1c; margin: 0 0 12px 0; text-transform: uppercase; letter-spacing: 1px; }
        .info-text { font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 4px 0; }
        .config-box { background-color: #d5d7ca; border-radius: 0; padding: 20px; margin: 24px 0; }
        .config-item { padding: 12px 0; border-bottom: 1px solid #bcbeb1; }
        .config-item:last-child { border-bottom: none; }
        .config-label { color: #5a5a5a; font-weight: 400; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
        .config-value { font-weight: 600; color: #1c1c1c; font-size: 14px; }
        .highlight-box { background-color: #1c1c1c; color: #ffffff; padding: 20px; margin: 24px 0; }
        .highlight-box .info-title { color: #ffffff; }
        .highlight-box .info-text { color: #bcbeb1; }
        .accent-box { border-left: 4px solid #CA4136; background-color: #d5d7ca; padding: 20px; margin: 24px 0; }
        .success-box { border-left: 4px solid #4a6741; background-color: #d5d7ca; padding: 20px; margin: 24px 0; }
        .button { display: inline-block; background-color: #CA4136; color: #ffffff !important; text-decoration: none; padding: 14px 32px; font-weight: 600; font-size: 13px; margin: 24px 0; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
        .button:hover { background-color: #a8352d; }
        .footer { background-color: #d5d7ca; padding: 32px 40px; text-align: center; color: #5a5a5a; font-size: 13px; border-top: 1px solid #bcbeb1; }
        .footer-text { margin: 8px 0; font-weight: 400; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #CA4136; text-decoration: none; margin: 0 12px; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 600; }
        .trust-badge { display: inline-block; margin: 8px 16px; font-size: 12px; color: #4a6741; text-transform: uppercase; letter-spacing: 0.5px; }
        .number-badge { display: inline-block; width: 24px; height: 24px; background-color: #CA4136; color: #ffffff; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700; margin-right: 12px; }
        @media only screen and (max-width: 600px) { 
            .content { padding: 24px !important; } 
            h1 { font-size: 20px !important; } 
            .header, .footer { padding: 24px !important; } 
            .logo { max-width: 80px !important; width: 80px !important; } 
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #bcbeb1; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" width="100%" style="width: 100%; max-width: 600px; background-color: #e5e6df !important;" bgcolor="#e5e6df">
                    
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="{{ $siteName }}" class="logo" width="100">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <h1>{{ __('emails.subscription_confirmation.title', [], $locale) }}</h1>
                            <p class="subtitle">{{ __('emails.subscription_confirmation.subtitle', [], $locale) }}</p>
                            
                            <!-- Subscription Number -->
                            <div class="highlight-box">
                                <div style="font-size: 11px; color: #bcbeb1; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">{{ __('emails.subscription_confirmation.subscription_number', [], $locale) }}</div>
                                <div style="font-size: 24px; font-weight: 700; color: #ffffff; letter-spacing: 2px;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Subscription Configuration -->
                            <div class="section-title">{{ __('emails.subscription_confirmation.your_config', [], $locale) }}</div>
                            <div class="config-box">
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                        <td class="config-item" style="padding: 12px 0; border-bottom: 1px solid #bcbeb1;">
                                            <span class="config-label">{{ $locale === 'cs' ? 'Typ kávy' : 'Coffee Type' }}</span><br>
                                            <span class="config-value">
                                                @if($subscription->configuration['type'] === 'espresso')
                                                    Espresso
                                                @elseif($subscription->configuration['type'] === 'filter')
                                                    {{ $locale === 'cs' ? 'Filtr' : 'Filter' }}
                                                @else
                                                    Mix ({{ $subscription->configuration['mix']['espresso'] ?? 0 }}x Espresso, {{ $subscription->configuration['mix']['filter'] ?? 0 }}x {{ $locale === 'cs' ? 'Filtr' : 'Filter' }})
                                                @endif
                                                @if($subscription->configuration['isDecaf'] ?? false)
                                                    <span style="color: #4a6741;"> / Decaf</span>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="config-item" style="padding: 12px 0; border-bottom: 1px solid #bcbeb1;">
                                            <span class="config-label">{{ __('emails.subscription_confirmation.bags_count', [], $locale) }}</span><br>
                                            <span class="config-value">{{ $subscription->configuration['amount'] }}x 250g</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="config-item" style="padding: 12px 0; border-bottom: 1px solid #bcbeb1;">
                                            <span class="config-label">{{ __('emails.subscription_confirmation.frequency', [], $locale) }}</span><br>
                                            <span class="config-value">
                                                @if($subscription->frequency_months == 1)
                                                    {{ __('emails.frequency.monthly', [], $locale) }}
                                                @elseif($subscription->frequency_months == 2)
                                                    {{ __('emails.frequency.bimonthly', [], $locale) }}
                                                @else
                                                    {{ __('emails.frequency.quarterly', [], $locale) }}
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    @php
                                    $activeDiscount = ($subscription->discount_amount > 0 && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0)) ? $subscription->discount_amount : 0;
                                    $currentPrice = $subscription->configured_price - $activeDiscount;
                                    @endphp
                                    @if($activeDiscount > 0)
                                    <tr>
                                        <td class="config-item" style="padding: 12px 0; border-bottom: 1px solid #bcbeb1;">
                                            <span class="config-label">{{ __('emails.subscription_confirmation.price', [], $locale) }}</span><br>
                                            <span class="config-value" style="text-decoration: line-through; color: #5a5a5a;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($subscription->configured_price, $subscription->currency, 0) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="config-item" style="padding: 12px 0; border-bottom: 1px solid #bcbeb1;">
                                            <span class="config-label">{{ __('emails.order_confirmation.discount', [], $locale) }}{{ $subscription->coupon_code ? ' (' . $subscription->coupon_code . ')' : '' }}</span><br>
                                            <span class="config-value" style="color: #4a6741;">-{{ \App\Helpers\CurrencyHelper::formatByCurrency($activeDiscount, $subscription->currency, 0) }}</span>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="config-item" style="padding: 12px 0;">
                                            <span class="config-label">{{ $activeDiscount > 0 ? ($locale === 'cs' ? 'Cena po slevě' : 'Price after discount') : __('emails.subscription_confirmation.price', [], $locale) }}</span><br>
                                            <span class="config-value" style="font-size: 20px; color: #CA4136;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($currentPrice, $subscription->currency, 0) }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Next Shipment -->
                            <div class="accent-box">
                                <div class="info-title">{{ __('emails.subscription_confirmation.next_delivery', [], $locale) }}</div>
                                <p class="info-text" style="color: #1c1c1c; font-size: 16px; font-weight: 600;">
                                    {{ $subscription->next_shipment_date ? $subscription->next_shipment_date->format('j. n. Y') : ($locale === 'cs' ? 'Brzy' : 'Soon') }}
                                </p>
                                @if($subscription->next_billing_date && $subscription->frequency_months > 0)
                                @php
                                    $nextBillingDate = \Carbon\Carbon::parse($subscription->next_billing_date);
                                    $nextShipmentSchedule = \App\Models\ShipmentSchedule::getForMonth($nextBillingDate->year, $nextBillingDate->month);
                                    $nextShipmentAfterBilling = $nextShipmentSchedule ? $nextShipmentSchedule->shipment_date : $nextBillingDate->copy()->day(20);
                                @endphp
                                <p class="info-text" style="margin-top: 12px;">
                                    <strong>{{ $locale === 'cs' ? 'Další platba' : 'Next payment' }}:</strong> {{ $nextBillingDate->format('j. n. Y') }}<br>
                                    <strong>{{ $locale === 'cs' ? 'Další doručení' : 'Next delivery' }}:</strong> {{ $locale === 'cs' ? 'cca' : 'approx.' }} {{ $nextShipmentAfterBilling->format('j. n. Y') }}
                                </p>
                                @endif
                            </div>
                            
                            <!-- Delivery Info -->
                            <div class="info-box">
                                <div class="info-title">{{ __('emails.order_confirmation.delivery', [], $locale) }}</div>
                                @if(isset($subscription->packeta_point_name))
                                <p class="info-text"><strong>{{ __('emails.order_confirmation.pickup_point', [], $locale) }}:</strong></p>
                                <p class="info-text" style="color: #1c1c1c;">{{ $subscription->packeta_point_name }}</p>
                                @if(isset($subscription->packeta_point_address))
                                <p class="info-text">{{ $subscription->packeta_point_address }}</p>
                                @endif
                                @endif
                                @if($subscription->delivery_notes)
                                <p class="info-text" style="margin-top: 12px;">
                                    <strong>{{ $locale === 'cs' ? 'Poznámka' : 'Note' }}:</strong><br>
                                    {{ $subscription->delivery_notes }}
                                </p>
                                @endif
                            </div>
                            
                            <!-- Billing Info -->
                            <div class="info-box">
                                <div class="info-title">{{ __('emails.order_confirmation.billing_info', [], $locale) }}</div>
                                <p class="info-text" style="color: #1c1c1c;"><strong>{{ $subscription->shipping_address['name'] }}</strong></p>
                                <p class="info-text">{{ $subscription->shipping_address['billing_address'] }}</p>
                                <p class="info-text">{{ $subscription->shipping_address['billing_postal_code'] }} {{ $subscription->shipping_address['billing_city'] }}</p>
                                <p class="info-text" style="margin-top: 8px;">
                                    <strong>{{ __('emails.order_confirmation.email_label', [], $locale) }}:</strong> {{ $subscription->shipping_address['email'] }}
                                    @if(isset($subscription->shipping_address['phone']))
                                    <br><strong>{{ __('emails.order_confirmation.phone_label', [], $locale) }}:</strong> {{ $subscription->shipping_address['phone'] }}
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Subscription Status -->
                            @if($subscription->status === 'active')
                            <div class="success-box">
                                <div class="info-title" style="color: #4a6741;">{{ $locale === 'cs' ? 'Stav předplatného' : 'Subscription Status' }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    {{ $locale === 'cs' ? 'Vaše předplatné je aktivní. Další platba proběhne automaticky.' : 'Your subscription is active. The next payment will be processed automatically.' }}
                                </p>
                            </div>
                            @else
                            <div class="accent-box">
                                <div class="info-title">{{ $locale === 'cs' ? 'Stav předplatného' : 'Subscription Status' }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    {{ $locale === 'cs' ? 'Vaše předplatné čeká na aktivaci. Po potvrzení platby bude automaticky aktivováno.' : 'Your subscription is pending activation. It will be activated automatically once payment is confirmed.' }}
                                </p>
                            </div>
                            @endif
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    {{ __('emails.subscription_confirmation.manage_button', [], $locale) }}
                                </a>
                            </div>
                            
                            <!-- Trust Badges -->
                            <div style="text-align: center; margin: 24px 0; padding: 16px 0; border-top: 1px solid #bcbeb1; border-bottom: 1px solid #bcbeb1;">
                                <span class="trust-badge">{{ __('emails.common.freshly_roasted', [], $locale) }}</span>
                                <span class="trust-badge">{{ $locale === 'cs' ? 'Flexibilní předplatné' : 'Flexible subscription' }}</span>
                                <span class="trust-badge">{{ $locale === 'cs' ? 'Zrušení kdykoliv' : 'Cancel anytime' }}</span>
                            </div>
                            
                            <!-- Additional Info -->
                            <p style="font-size: 13px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ __('emails.subscription_confirmation.manage_subscription', [], $locale) }} {{ __('emails.subscription_confirmation.help_text', [], $locale) }} 
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none; font-weight: 600;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 13px; color: #5a5a5a; margin-top: 24px;">
                                {{ __('emails.common.regards', [], $locale) }},<br>
                                <strong style="color: #1c1c1c;">{{ __('emails.common.team', [], $locale) }}</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #1c1c1c;">{{ $siteName }}</strong><br>
                                {{ __('emails.common.tagline', [], $locale) }}
                            </p>
                            <div class="footer-links">
                                <a href="{{ route('home') }}" class="footer-link">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ route('products.index') }}" class="footer-link">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link">{{ __('emails.common.subscription', [], $locale) }}</a>
                            </div>
                            <p class="footer-text" style="font-size: 11px; margin-top: 16px; color: #76716c;">
                                {{ __('emails.common.copyright', ['year' => date('Y')], $locale) }}
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
