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
        .info-text { font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 8px 0; }
        .highlight-box { background-color: #1c1c1c; color: #ffffff; padding: 20px; margin: 24px 0; }
        .accent-box { border-left: 4px solid #CA4136; background-color: #d5d7ca; padding: 20px; margin: 24px 0; }
        .success-box { border-left: 4px solid #4a6741; background-color: #d5d7ca; padding: 20px; margin: 24px 0; }
        .button { display: inline-block; background-color: #CA4136; color: #ffffff !important; text-decoration: none; padding: 14px 32px; font-weight: 600; font-size: 13px; margin: 24px 0; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
        .footer { background-color: #d5d7ca; padding: 32px 40px; text-align: center; color: #5a5a5a; font-size: 13px; border-top: 1px solid #bcbeb1; }
        .footer-text { margin: 8px 0; font-weight: 400; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #CA4136; text-decoration: none; margin: 0 12px; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 600; }
        .step-number { display: inline-block; width: 24px; height: 24px; background-color: #CA4136; color: #ffffff; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700; margin-right: 12px; }
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
                    
                    <tr>
                        <td class="header">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="{{ $siteName }}" class="logo" width="100">
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="content">
                            <h1>{{ __('emails.upcoming_payment.title', [], $locale) }}</h1>
                            <p class="subtitle">{{ __('emails.upcoming_payment.subtitle', [], $locale) }}</p>
                            
                            <!-- Payment Info -->
                            <div class="accent-box">
                                <div class="info-title">{{ $locale === 'cs' ? 'Informace o platbě' : 'Payment information' }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    <strong>{{ $locale === 'cs' ? 'Datum platby' : 'Payment date' }}:</strong><br>
                                    <span style="font-size: 20px; font-weight: 700;">{{ $subscription->next_billing_date->format($locale === 'cs' ? 'j. n. Y' : 'M d, Y') }}</span>
                                </p>
                                <p class="info-text" style="color: #1c1c1c; margin-top: 12px;">
                                    @php
                                    $activeDiscount = ($subscription->discount_amount > 0 && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0)) ? $subscription->discount_amount : 0;
                                    $paymentAmount = $subscription->configured_price - $activeDiscount;
                                    @endphp
                                    <strong>{{ $locale === 'cs' ? 'Částka' : 'Amount' }}:</strong> <span style="font-size: 20px; font-weight: 700; color: #CA4136;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($paymentAmount, $subscription->currency, 0) }}</span>
                                </p>
                            </div>
                            
                            <!-- Subscription Details -->
                            <div class="section-title">{{ $locale === 'cs' ? 'Vaše předplatné' : 'Your subscription' }}</div>
                            <div class="info-box">
                                <p class="info-text" style="color: #1c1c1c;">
                                    <strong>{{ $locale === 'cs' ? 'Číslo předplatného' : 'Subscription number' }}:</strong> {{ $subscription->subscription_number }}<br>
                                    <strong>{{ $locale === 'cs' ? 'Typ kávy' : 'Coffee type' }}:</strong> 
                                    @if($subscription->configuration['type'] === 'espresso')
                                        Espresso
                                    @elseif($subscription->configuration['type'] === 'filter')
                                        {{ $locale === 'cs' ? 'Filtr' : 'Filter' }}
                                    @else
                                        Mix ({{ $subscription->configuration['mix']['espresso'] ?? 0 }}x Espresso, {{ $subscription->configuration['mix']['filter'] ?? 0 }}x {{ $locale === 'cs' ? 'Filtr' : 'Filter' }})
                                    @endif
                                    @if($subscription->configuration['isDecaf'] ?? false)
                                        / Decaf
                                    @endif
                                    <br>
                                    <strong>{{ $locale === 'cs' ? 'Množství' : 'Quantity' }}:</strong> {{ $subscription->configuration['amount'] }}x 250g<br>
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
                            <div class="success-box">
                                <div class="info-title" style="color: #4a6741;">{{ $locale === 'cs' ? 'Co se stane dále?' : 'What happens next?' }}</div>
                                @php
                                    $billingDate = \Carbon\Carbon::parse($subscription->next_billing_date);
                                    $shipmentSchedule = \App\Models\ShipmentSchedule::getForMonth($billingDate->year, $billingDate->month);
                                    $shipmentDate = $shipmentSchedule ? $shipmentSchedule->shipment_date : $billingDate->copy()->day(20);
                                    $deliveryStart = $shipmentDate->copy()->addDays(1);
                                    $deliveryEnd = $shipmentDate->copy()->addDays(2);
                                @endphp
                                <p class="info-text" style="color: #1c1c1c;">
                                    @if($locale === 'cs')
                                    <span class="step-number">1</span> <strong>{{ $billingDate->format('j. n. Y') }}</strong> – Automatická platba<br><br>
                                    <span class="step-number">2</span> <strong>{{ $shipmentDate->format('j. n. Y') }}</strong> – Pražení a expedice vaší kávy<br><br>
                                    <span class="step-number">3</span> <strong>{{ $deliveryStart->format('j. n. Y') }} - {{ $deliveryEnd->format('j. n. Y') }}</strong> – Doručení na výdejní místo<br><br>
                                    <span class="step-number">4</span> Čerstvá káva přímo k vám!
                                    @else
                                    <span class="step-number">1</span> <strong>{{ $billingDate->format('M d, Y') }}</strong> – Automatic payment<br><br>
                                    <span class="step-number">2</span> <strong>{{ $shipmentDate->format('M d, Y') }}</strong> – Roasting and shipping your coffee<br><br>
                                    <span class="step-number">3</span> <strong>{{ $deliveryStart->format('M d, Y') }} - {{ $deliveryEnd->format('M d, Y') }}</strong> – Delivery to pickup point<br><br>
                                    <span class="step-number">4</span> Fresh coffee delivered to you!
                                    @endif
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    {{ __('emails.subscription_paused.manage_subscription', [], $locale) }}
                                </a>
                            </div>
                            
                            <p style="font-size: 13px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ $locale === 'cs' ? 'Pokud potřebujete upravit platební údaje, pozastavit nebo zrušit předplatné, můžete tak učinit ve svém zákaznickém účtu.' : 'If you need to update payment details, pause or cancel your subscription, you can do so in your customer account.' }}
                            </p>
                            
                            <p style="font-size: 13px; color: #5a5a5a; line-height: 1.6; margin-top: 16px;">
                                {{ __('emails.common.questions', [], $locale) }} 
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none; font-weight: 600;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 13px; color: #5a5a5a; margin-top: 24px;">
                                {{ __('emails.common.regards', [], $locale) }},<br>
                                <strong style="color: #1c1c1c;">{{ __('emails.common.team', [], $locale) }}</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="footer">
                            <p class="footer-text">
                                <strong style="color: #1c1c1c;">{{ $siteName }}</strong><br>
                                {{ __('emails.common.tagline', [], $locale) }}
                            </p>
                            <div class="footer-links">
                                <a href="{{ route('home') }}" class="footer-link">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ route('products.index') }}" class="footer-link">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ route('dashboard.subscription') }}" class="footer-link">{{ __('emails.common.my_subscription', [], $locale) }}</a>
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
