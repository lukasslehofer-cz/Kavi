<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.subscription_payment_success.title', [], $locale) }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f3f4f6 !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .email-container { max-width: 600px; margin: 0 auto; }
        .header { background-color: #111827 !important; padding: 32px; text-align: center; }
        .logo { max-width: 120px !important; width: 120px !important; height: auto !important; display: block !important; margin: 0 auto !important; }
        .content { padding: 40px 32px; background-color: #ffffff !important; }
        h1 { font-size: 28px; font-weight: 700; color: #111827; margin: 0 0 16px 0; line-height: 1.3; }
        .subtitle { font-size: 16px; color: #6b7280; margin: 0 0 32px 0; font-weight: 300; }
        .info-box { background-color: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin: 24px 0; }
        .info-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px 0; }
        .info-text { font-size: 14px; color: #4b5563; line-height: 1.6; margin: 8px 0; }
        .config-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
        .config-item:last-child { border-bottom: none; }
        .config-label { color: #6b7280; font-weight: 300; font-size: 14px; }
        .config-value { font-weight: 600; color: #111827; }
        .button { display: inline-block; background-color: #e6305a; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 9999px; font-weight: 600; font-size: 15px; margin: 24px 0; text-align: center; }
        .footer { background-color: #f9fafb !important; padding: 32px; text-align: center; }
        .footer-text { font-size: 14px; color: #6b7280; line-height: 1.6; margin: 8px 0; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #e6305a; text-decoration: none; margin: 0 12px; font-size: 14px; }
        @media only screen and (max-width: 600px) { .content { padding: 24px !important; } h1 { font-size: 24px !important; } .header, .footer { padding: 24px !important; } .logo { max-width: 100px !important; width: 100px !important; } }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6 !important; padding: 20px 0;" bgcolor="#f3f4f6">
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
                                <div style="width: 64px; height: 64px; background-color: #10b981 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px; color: #ffffff;">
                                    ✓
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">{{ __('emails.subscription_payment_success.title', [], $locale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.subscription_payment_success.subtitle', [], $locale) }}</p>
                            
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">{{ __('emails.subscription_payment_success.subscription_number', [], $locale) }}</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Payment Details -->
                            <div class="info-box" style="background-color: #d1fae5 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#d1fae5">
                                <h3 class="info-title" style="color: #065f46;">✓ {{ $locale === 'cs' ? 'Potvrzení platby' : 'Payment Confirmation' }}</h3>
                                <div class="config-item" style="border-bottom: 1px solid #bbf7d0;">
                                    <span class="config-label" style="color: #047857;">{{ __('emails.subscription_payment_success.amount', [], $locale) }}:</span>
                                    <span class="config-value" style="color: #065f46; font-size: 18px;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($payment->amount, $subscription->currency, 0) }}</span>
                                </div>
                                <div class="config-item" style="border-bottom: 1px solid #bbf7d0;">
                                    <span class="config-label" style="color: #047857;">{{ __('emails.subscription_payment_success.payment_date', [], $locale) }}:</span>
                                    <span class="config-value" style="color: #065f46;">{{ $payment->paid_at->format('j. n. Y') }}</span>
                                </div>
                                <div class="config-item" style="border-bottom: none;">
                                    <span class="config-label" style="color: #047857;">Status:</span>
                                    <span class="config-value" style="color: #065f46;">{{ $locale === 'cs' ? 'Zaplaceno' : 'Paid' }} ✓</span>
                                </div>
                            </div>
                            
                            <!-- Next Billing -->
                            <div class="info-box" style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-left: 4px solid #3b82f6 !important;" bgcolor="#dbeafe">
                                <h3 class="info-title" style="color: #1e40af;">📅 {{ __('emails.subscription_payment_success.next_payment', [], $locale) }}</h3>
                                @if($subscription->next_billing_date)
                                @php
                                    $nextBillingDate = \Carbon\Carbon::parse($subscription->next_billing_date);
                                    $nextShipmentSchedule = \App\Models\ShipmentSchedule::getForMonth($nextBillingDate->year, $nextBillingDate->month);
                                    $nextShipmentDate = $nextShipmentSchedule ? $nextShipmentSchedule->shipment_date : $nextBillingDate->copy()->day(20);
                                @endphp
                                <p class="info-text" style="color: #1e3a8a;">
                                    <strong>{{ $locale === 'cs' ? 'Datum další platby' : 'Next payment date' }}:</strong> {{ $nextBillingDate->format('j. n. Y') }}<br>
                                    <strong>{{ $locale === 'cs' ? 'Datum další rozesílky' : 'Next shipment date' }}:</strong> {{ $locale === 'cs' ? 'cca' : 'approx.' }} {{ $nextShipmentDate->format('j. n. Y') }}
                                </p>
                                @else
                                <p class="info-text" style="color: #1e3a8a;">
                                    {{ $locale === 'cs' ? 'Datum bude upřesněno' : 'Date to be confirmed' }}
                                </p>
                                @endif
                            </div>
                            
                            <!-- Subscription Summary -->
                            <div class="info-box" style="background-color: #f9fafb !important; border: 1px solid #e5e7eb !important;" bgcolor="#f9fafb">
                                <h3 class="info-title">📦 {{ __('emails.subscription_confirmation.your_config', [], $locale) }}</h3>
                                
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
                            </div>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    {{ __('emails.subscription_payment_success.view_subscription', [], $locale) }}
                                </a>
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
                                <a href="{{ route('home') }}" class="footer-link">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ route('products.index') }}" class="footer-link">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link">{{ __('emails.common.subscription', [], $locale) }}</a>
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
