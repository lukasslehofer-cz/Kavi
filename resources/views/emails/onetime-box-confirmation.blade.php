<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.onetime_box.title', [], $locale) }}</title>
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
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6; padding: 20px 0;">
        <tr>
            <td align="center">
                <!--[if mso]><table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"><tr><td><![endif]-->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" width="100%" style="width: 100%; max-width: 600px; background-color: #ffffff !important; border: 1px solid #e5e7eb !important; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);" bgcolor="#ffffff">
                    
                    <tr>
                        <td class="header">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="{{ $siteName }}" class="logo" width="120" style="max-width: 120px !important; width: 120px !important; height: auto !important; display: block !important; margin: 0 auto !important; border: 0; outline: none;">
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="content">
                            <div style="text-align: center; margin-bottom: 24px;">
                                <div style="width: 64px; height: 64px; background-color: #10b981 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    ✓
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">{{ __('emails.onetime_box.title', [], $locale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.onetime_box.subtitle', [], $locale) }}</p>
                            
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">{{ __('emails.onetime_box.box_number', [], $locale) }}</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- One-time Info Box -->
                            <div style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #f59e0b !important; border-radius: 12px; padding: 20px; margin: 24px 0;" bgcolor="#fef3c7">
                                <div style="display: flex; align-items: start; gap: 12px;">
                                    <div style="font-size: 24px; line-height: 1;">⭐</div>
                                    <div>
                                        <h3 class="info-title" style="color: #92400e; margin-bottom: 8px;">{{ $locale === 'cs' ? 'Jednorázový nákup bez předplatného' : 'One-time purchase, no subscription' }}</h3>
                                        <p class="info-text" style="color: #78350f;">
                                            {{ $locale === 'cs' ? 'Tento box vám doručíme jednorázově. Žádné další platby ani dodávky neproběhnou. Nemusíte nic rušit ani odhlašovat.' : 'This box will be delivered once. No further payments or deliveries will occur. You don\'t need to cancel anything.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Box Configuration -->
                            <div class="config-box" style="background-color: #f9fafb !important; border: 1px solid #e5e7eb !important;" bgcolor="#f9fafb">
                                <h2 class="info-title">📦 {{ __('emails.onetime_box.your_config', [], $locale) }}</h2>
                                
                                <div class="config-item">
                                    <span class="config-label">{{ $locale === 'cs' ? 'Typ kávy' : 'Coffee type' }}:</span>
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
                                    <span class="config-label">{{ __('emails.onetime_box.bags_count', [], $locale) }}:</span>
                                    <span class="config-value">{{ $subscription->configuration['amount'] }}× 250g</span>
                                </div>
                                
                                @if($subscription->discount_amount > 0)
                                <div class="config-item">
                                    <span class="config-label">{{ __('emails.onetime_box.price', [], $locale) }}:</span>
                                    <span class="config-value">{{ \App\Helpers\CurrencyHelper::formatByCurrency($subscription->configured_price, $subscription->currency, 0) }}</span>
                                </div>
                                <div class="config-item">
                                    <span class="config-label">{{ __('emails.order_confirmation.discount', [], $locale) }}{{ $subscription->coupon_code ? ' (' . $subscription->coupon_code . ')' : '' }}:</span>
                                    <span class="config-value" style="color: #059669;">-{{ \App\Helpers\CurrencyHelper::formatByCurrency($subscription->discount_amount, $subscription->currency, 0) }}</span>
                                </div>
                                <div class="config-item">
                                    <span class="config-label">{{ __('emails.order_confirmation.total', [], $locale) }}:</span>
                                    <span class="config-value" style="font-size: 18px; color: #e6305a;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($subscription->configured_price - $subscription->discount_amount, $subscription->currency, 0) }}</span>
                                </div>
                                @else
                                <div class="config-item">
                                    <span class="config-label">{{ __('emails.order_confirmation.total', [], $locale) }}:</span>
                                    <span class="config-value" style="font-size: 18px; color: #e6305a;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($subscription->configured_price, $subscription->currency, 0) }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Delivery Info -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📦 {{ __('emails.onetime_box.delivery', [], $locale) }}</h3>
                                @if(isset($subscription->packeta_point_name))
                                <p class="info-text"><strong>{{ $subscription->packeta_point_name }}</strong></p>
                                @if(isset($subscription->packeta_point_address))
                                <p class="info-text" style="color: #6b7280;">{{ $subscription->packeta_point_address }}</p>
                                @endif
                                @endif
                            </div>
                            
                            <!-- What's Next -->
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">📅 {{ __('emails.onetime_box.what_next', [], $locale) }}</h3>
                                <p class="info-text" style="color: #1e3a8a;">
                                    1. {{ __('emails.onetime_box.step1', [], $locale) }}<br>
                                    2. {{ __('emails.onetime_box.step2', [], $locale) }}<br>
                                    3. {{ __('emails.onetime_box.step3', [], $locale) }}
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    {{ __('emails.onetime_box.view_order', [], $locale) }}
                                </a>
                            </div>
                            
                            <div class="trust-badges">
                                <div class="badge">✓ {{ __('emails.common.freshly_roasted', [], $locale) }}</div>
                                <div class="badge">✓ {{ $locale === 'cs' ? 'Jednorázový nákup' : 'One-time purchase' }}</div>
                                <div class="badge">✓ {{ $locale === 'cs' ? 'Žádné závazky' : 'No commitment' }}</div>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                {{ __('emails.common.questions', [], $locale) }} 
                                <a href="mailto:{{ $contactEmail }}" style="color: #e6305a; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px; font-weight: 300;">
                                {{ __('emails.common.regards', [], $locale) }},<br>
                                <strong style="color: #111827;">{{ __('emails.common.team', [], $locale) }}</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #111827;">{{ $siteName }}</strong><br>
                                {{ __('emails.common.tagline', [], $locale) }}
                            </p>
                            <div class="footer-links">
                                <a href="{{ route('home') }}" class="footer-link" style="color: #e6305a;">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ route('products.index') }}" class="footer-link" style="color: #e6305a;">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link" style="color: #e6305a;">{{ __('emails.common.my_account', [], $locale) }}</a>
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
