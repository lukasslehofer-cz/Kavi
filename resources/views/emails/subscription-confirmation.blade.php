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
        body { margin: 0; padding: 0; width: 100%; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #111827; padding: 32px 40px; text-align: center; }
        .logo { max-width: 120px !important; width: 120px !important; height: auto !important; display: block !important; margin: 0 auto !important; }
        .content { padding: 40px; color: #374151; }
        h1 { font-size: 28px; font-weight: 700; color: #111827; margin: 0 0 12px 0; line-height: 1.2; }
        .subtitle { font-size: 16px; color: #6b7280; margin: 0 0 32px 0; font-weight: 300; }
        .info-box { background-color: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin: 24px 0; }
        .info-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px 0; }
        .info-text { font-size: 14px; color: #4b5563; line-height: 1.6; margin: 4px 0; }
        .config-box { background-color: #f9fafb; border-radius: 12px; padding: 20px; margin: 24px 0; }
        .config-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
        .config-item:last-child { border-bottom: none; }
        .config-label { color: #6b7280; font-weight: 300; font-size: 14px; }
        .config-value { font-weight: 600; color: #111827; }
        .button { display: inline-block; background-color: #e6305a; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 9999px; font-weight: 600; font-size: 15px; margin: 24px 0; text-align: center; }
        .footer { background-color: #f9fafb; padding: 32px 40px; text-align: center; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb; }
        .footer-text { margin: 8px 0; font-weight: 300; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #e6305a; text-decoration: none; margin: 0 8px; }
        .trust-badges { margin: 24px 0; text-align: center; }
        .badge { display: inline-block; margin: 8px 12px; font-size: 13px; color: #059669; }
        @media only screen and (max-width: 600px) { .content { padding: 24px !important; } h1 { font-size: 24px !important; } .header, .footer { padding: 24px !important; } .logo { max-width: 100px !important; width: 100px !important; } }
        @media (prefers-color-scheme: dark) { body { background-color: #1a1a1a !important; } .email-container { background-color: #ffffff !important; border: 1px solid #d1d5db !important; } .info-box, .config-box { background-color: #f9fafb !important; border: 1px solid #d1d5db !important; } h1, .info-title, .config-value { color: #111827 !important; } .subtitle, .info-text, .config-label { color: #4b5563 !important; } .header { background-color: #111827 !important; } }
        [data-ogsc] .email-container { background-color: #ffffff !important; border: 1px solid #d1d5db !important; }
        [data-ogsc] .info-box, [data-ogsc] .config-box { background-color: #f9fafb !important; border: 1px solid #d1d5db !important; }
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
                            
                            <h1 style="text-align: center;">{{ __('emails.subscription_confirmation.title', [], $locale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.subscription_confirmation.subtitle', [], $locale) }}</p>
                            
                            <!-- Subscription Number -->
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">{{ __('emails.subscription_confirmation.subscription_number', [], $locale) }}</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Subscription Configuration -->
                            <div class="config-box" style="background-color: #f9fafb !important; border: 1px solid #e5e7eb !important;" bgcolor="#f9fafb">
                                <h2 class="info-title">📦 {{ __('emails.subscription_confirmation.your_config', [], $locale) }}</h2>
                                
                                <div class="config-item">
                                    <span class="config-label">{{ $locale === 'cs' ? 'Typ kávy' : 'Coffee Type' }}:</span>
                                    <span class="config-value">
                                        @if($subscription->configuration['type'] === 'espresso')
                                            Espresso
                                        @elseif($subscription->configuration['type'] === 'filter')
                                            {{ $locale === 'cs' ? 'Filtr' : 'Filter' }}
                                        @else
                                            Mix ({{ $subscription->configuration['mix']['espresso'] ?? 0 }}× Espresso, {{ $subscription->configuration['mix']['filter'] ?? 0 }}× {{ $locale === 'cs' ? 'Filtr' : 'Filter' }})
                                        @endif
                                        @if($subscription->configuration['isDecaf'] ?? false)
                                            <span style="color: #059669;"> • Decaf</span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="config-item">
                                    <span class="config-label">{{ __('emails.subscription_confirmation.bags_count', [], $locale) }}:</span>
                                    <span class="config-value">{{ $subscription->configuration['amount'] }}× 250g</span>
                                </div>
                                
                                <div class="config-item">
                                    <span class="config-label">{{ __('emails.subscription_confirmation.frequency', [], $locale) }}:</span>
                                    <span class="config-value">
                                        @if($subscription->frequency_months == 1)
                                            {{ __('emails.frequency.monthly', [], $locale) }}
                                        @elseif($subscription->frequency_months == 2)
                                            {{ __('emails.frequency.bimonthly', [], $locale) }}
                                        @else
                                            {{ __('emails.frequency.quarterly', [], $locale) }}
                                        @endif
                                    </span>
                                </div>
                                
                                @php
                                $activeDiscount = ($subscription->discount_amount > 0 && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0)) ? $subscription->discount_amount : 0;
                                $currentPrice = $subscription->configured_price - $activeDiscount;
                                @endphp
                                
                                @if($activeDiscount > 0)
                                <div class="config-item">
                                    <span class="config-label">{{ __('emails.subscription_confirmation.price', [], $locale) }}:</span>
                                    <span class="config-value">{{ \App\Helpers\CurrencyHelper::formatByCurrency($subscription->configured_price, $subscription->currency, 0) }}</span>
                                </div>
                                <div class="config-item">
                                    <span class="config-label">{{ __('emails.order_confirmation.discount', [], $locale) }}{{ $subscription->coupon_code ? ' (' . $subscription->coupon_code . ')' : '' }}:</span>
                                    <span class="config-value" style="color: #059669;">-{{ \App\Helpers\CurrencyHelper::formatByCurrency($activeDiscount, $subscription->currency, 0) }}</span>
                                </div>
                                <div class="config-item">
                                    <span class="config-label">{{ __('emails.subscription_confirmation.price', [], $locale) }}:</span>
                                    <span class="config-value" style="font-size: 18px; color: #e6305a;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($currentPrice, $subscription->currency, 0) }}</span>
                                </div>
                                @else
                                <div class="config-item">
                                    <span class="config-label">{{ __('emails.subscription_confirmation.price', [], $locale) }}:</span>
                                    <span class="config-value" style="font-size: 18px; color: #e6305a;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($currentPrice, $subscription->currency, 0) }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Next Shipment -->
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">📅 {{ __('emails.subscription_confirmation.next_delivery', [], $locale) }}</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    {{ $subscription->next_shipment_date ? $subscription->next_shipment_date->format('j. n. Y') : ($locale === 'cs' ? 'Brzy' : 'Soon') }}
                                </p>
                                @if($subscription->next_billing_date && $subscription->frequency_months > 0)
                                @php
                                    $nextBillingDate = \Carbon\Carbon::parse($subscription->next_billing_date);
                                    $nextShipmentSchedule = \App\Models\ShipmentSchedule::getForMonth($nextBillingDate->year, $nextBillingDate->month);
                                    $nextShipmentAfterBilling = $nextShipmentSchedule ? $nextShipmentSchedule->shipment_date : $nextBillingDate->copy()->day(20);
                                @endphp
                                <p class="info-text" style="color: #1e3a8a; margin-top: 8px;">
                                    <strong>{{ $locale === 'cs' ? 'Další platba' : 'Next payment' }}:</strong> {{ $nextBillingDate->format('j. n. Y') }}<br>
                                    <strong>{{ $locale === 'cs' ? 'Další doručení' : 'Next delivery' }}:</strong> {{ $locale === 'cs' ? 'cca' : 'approx.' }} {{ $nextShipmentAfterBilling->format('j. n. Y') }}
                                </p>
                                @endif
                            </div>
                            
                            <!-- Delivery Info -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📦 {{ __('emails.order_confirmation.delivery', [], $locale) }}</h3>
                                @if(isset($subscription->packeta_point_name))
                                <p class="info-text"><strong>{{ __('emails.order_confirmation.pickup_point', [], $locale) }}:</strong></p>
                                <p class="info-text">{{ $subscription->packeta_point_name }}</p>
                                @if(isset($subscription->packeta_point_address))
                                <p class="info-text" style="color: #6b7280;">{{ $subscription->packeta_point_address }}</p>
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
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📋 {{ __('emails.order_confirmation.billing_info', [], $locale) }}</h3>
                                <p class="info-text"><strong>{{ $subscription->shipping_address['name'] }}</strong></p>
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
                            <div class="info-box" style="background-color: #d1fae5 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#d1fae5">
                                <h3 class="info-title" style="color: #065f46;">✓ {{ $locale === 'cs' ? 'Stav předplatného' : 'Subscription Status' }}</h3>
                                <p class="info-text" style="color: #047857;">
                                    {{ $locale === 'cs' ? 'Vaše předplatné je aktivní. Další platba proběhne automaticky.' : 'Your subscription is active. The next payment will be processed automatically.' }}
                                </p>
                            </div>
                            @else
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e;">⏳ {{ $locale === 'cs' ? 'Stav předplatného' : 'Subscription Status' }}</h3>
                                <p class="info-text" style="color: #78350f;">
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
                            <div class="trust-badges">
                                <div class="badge">✓ {{ __('emails.common.freshly_roasted', [], $locale) }}</div>
                                <div class="badge">✓ {{ $locale === 'cs' ? 'Flexibilní předplatné' : 'Flexible subscription' }}</div>
                                <div class="badge">✓ {{ $locale === 'cs' ? 'Zrušení kdykoliv' : 'Cancel anytime' }}</div>
                            </div>
                            
                            <!-- Additional Info -->
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                {{ __('emails.subscription_confirmation.manage_subscription', [], $locale) }} {{ __('emails.subscription_confirmation.help_text', [], $locale) }} 
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
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link" style="color: #e6305a;">{{ __('emails.common.subscription', [], $locale) }}</a>
                            </div>
                            <p class="footer-text" style="font-size: 12px; margin-top: 16px;">
                                {{ __('emails.common.copyright', ['year' => date('Y')], $locale) }}
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
