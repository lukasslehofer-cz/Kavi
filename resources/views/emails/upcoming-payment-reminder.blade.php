<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.upcoming_payment.title', [], $locale) }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100%; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #111827; padding: 32px 40px; text-align: center; }
        .logo { max-width: 150px; height: auto; }
        .content { padding: 40px; color: #374151; }
        h1 { font-size: 28px; font-weight: 700; color: #111827; margin: 0 0 12px 0; line-height: 1.2; }
        .subtitle { font-size: 16px; color: #6b7280; margin: 0 0 32px 0; font-weight: 300; }
        .info-box { background-color: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin: 24px 0; }
        .info-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 12px 0; }
        .info-text { font-size: 14px; color: #4b5563; line-height: 1.6; margin: 4px 0; }
        .button { display: inline-block; background-color: #e6305a; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 9999px; font-weight: 600; font-size: 15px; margin: 24px 0; text-align: center; }
        .footer { background-color: #f9fafb; padding: 32px 40px; text-align: center; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb; }
        .footer-text { margin: 8px 0; font-weight: 300; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #e6305a; text-decoration: none; margin: 0 8px; }
        @media only screen and (max-width: 600px) { .content { padding: 24px !important; } h1 { font-size: 24px !important; } .header, .footer { padding: 24px !important; } }
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
                            <h1 style="text-align: center;">{{ __('emails.upcoming_payment.title', [], $locale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.upcoming_payment.subtitle', [], $locale) }}</p>
                            
                            <!-- Payment Info -->
                            <div style="background-color: #dbeafe !important; border: 1px solid #93c5fd !important; border-radius: 12px; padding: 20px; margin: 24px 0;" bgcolor="#dbeafe">
                                <h3 style="font-size: 18px; font-weight: 600; color: #1e40af; margin: 0 0 12px 0;">💳 {{ $locale === 'cs' ? 'Informace o platbě' : 'Payment information' }}</h3>
                                <p style="font-size: 16px; color: #1e3a8a; line-height: 1.6; margin: 8px 0;">
                                    <strong>{{ $locale === 'cs' ? 'Datum platby' : 'Payment date' }}:</strong><br>
                                    <span style="font-size: 20px; font-weight: 700;">{{ $subscription->next_billing_date->format($locale === 'cs' ? 'j. n. Y' : 'M d, Y') }}</span>
                                </p>
                                <p style="font-size: 16px; color: #1e3a8a; margin: 12px 0 0 0;">
                                    @php
                                    $activeDiscount = ($subscription->discount_amount > 0 && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0)) ? $subscription->discount_amount : 0;
                                    $paymentAmount = $subscription->configured_price - $activeDiscount;
                                    @endphp
                                    <strong>{{ $locale === 'cs' ? 'Částka' : 'Amount' }}:</strong> <span style="font-size: 20px; font-weight: 700; color: #e6305a;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($paymentAmount, $subscription->currency, 0) }}</span>
                                </p>
                            </div>
                            
                            <!-- Subscription Details -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">📦 {{ $locale === 'cs' ? 'Vaše předplatné' : 'Your subscription' }}</h3>
                                <p class="info-text">
                                    <strong>{{ $locale === 'cs' ? 'Číslo předplatného' : 'Subscription number' }}:</strong> {{ $subscription->subscription_number }}<br>
                                    <strong>{{ $locale === 'cs' ? 'Typ kávy' : 'Coffee type' }}:</strong> 
                                    @if($subscription->configuration['type'] === 'espresso')
                                        Espresso
                                    @elseif($subscription->configuration['type'] === 'filter')
                                        {{ $locale === 'cs' ? 'Filtr' : 'Filter' }}
                                    @else
                                        Mix ({{ $subscription->configuration['mix']['espresso'] ?? 0 }}× Espresso, {{ $subscription->configuration['mix']['filter'] ?? 0 }}× {{ $locale === 'cs' ? 'Filtr' : 'Filter' }})
                                    @endif
                                    @if($subscription->configuration['isDecaf'] ?? false)
                                        • Decaf
                                    @endif
                                    <br>
                                    <strong>{{ $locale === 'cs' ? 'Množství' : 'Quantity' }}:</strong> {{ $subscription->configuration['amount'] }}× 250g<br>
                                    <strong>{{ $locale === 'cs' ? 'Frekvence' : 'Frequency' }}:</strong> 
                                    @if($subscription->frequency_months == 1)
                                        {{ $locale === 'cs' ? 'Každý měsíc' : 'Every month' }}
                                    @elseif($subscription->frequency_months == 2)
                                        {{ $locale === 'cs' ? 'Každé 2 měsíce' : 'Every 2 months' }}
                                    @else
                                        {{ $locale === 'cs' ? 'Každé 3 měsíce' : 'Every 3 months' }}
                                    @endif
                                </p>
                            </div>
                            
                            <!-- What happens next -->
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">✓ {{ $locale === 'cs' ? 'Co se stane dále?' : 'What happens next?' }}</h3>
                                @php
                                    $billingDate = \Carbon\Carbon::parse($subscription->next_billing_date);
                                    $shipmentSchedule = \App\Models\ShipmentSchedule::getForMonth($billingDate->year, $billingDate->month);
                                    $shipmentDate = $shipmentSchedule ? $shipmentSchedule->shipment_date : $billingDate->copy()->day(20);
                                    $deliveryStart = $shipmentDate->copy()->addDays(1);
                                    $deliveryEnd = $shipmentDate->copy()->addDays(2);
                                @endphp
                                <p class="info-text" style="color: #047857;">
                                    @if($locale === 'cs')
                                    1. <strong>{{ $billingDate->format('j. n. Y') }}</strong> - Automatická platba<br>
                                    2. <strong>{{ $shipmentDate->format('j. n. Y') }}</strong> - Pražení a expedice vaší kávy<br>
                                    3. <strong>{{ $deliveryStart->format('j. n. Y') }} - {{ $deliveryEnd->format('j. n. Y') }}</strong> - Doručení na výdejní místo<br>
                                    4. Čerstvá káva přímo k vám!
                                    @else
                                    1. <strong>{{ $billingDate->format('M d, Y') }}</strong> - Automatic payment<br>
                                    2. <strong>{{ $shipmentDate->format('M d, Y') }}</strong> - Roasting and shipping your coffee<br>
                                    3. <strong>{{ $deliveryStart->format('M d, Y') }} - {{ $deliveryEnd->format('M d, Y') }}</strong> - Delivery to pickup point<br>
                                    4. Fresh coffee delivered to you!
                                    @endif
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    {{ __('emails.subscription_paused.manage_subscription', [], $locale) }}
                                </a>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                {{ $locale === 'cs' ? 'Pokud potřebujete upravit platební údaje, pozastavit nebo zrušit předplatné, můžete tak učinit ve svém zákaznickém účtu.' : 'If you need to update payment details, pause or cancel your subscription, you can do so in your customer account.' }}
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 16px; font-weight: 300;">
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
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link" style="color: #e6305a;">{{ __('emails.common.my_subscription', [], $locale) }}</a>
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
