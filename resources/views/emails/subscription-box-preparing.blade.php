<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.subscription_box_preparing.title', [], $locale) }}</title>
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
        .warning-box { border-left: 4px solid #b8860b; background-color: #d5d7ca; padding: 20px; margin: 24px 0; }
        .button { display: inline-block; background-color: #CA4136; color: #ffffff !important; text-decoration: none; padding: 14px 32px; font-weight: 600; font-size: 13px; margin: 24px 0; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
        .footer { background-color: #d5d7ca; padding: 32px 40px; text-align: center; color: #5a5a5a; font-size: 13px; border-top: 1px solid #bcbeb1; }
        .footer-text { margin: 8px 0; font-weight: 400; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #CA4136; text-decoration: none; margin: 0 12px; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 600; }
        .step-number { display: inline-block; width: 24px; height: 24px; background-color: #CA4136; color: #ffffff; text-align: center; line-height: 24px; font-size: 12px; font-weight: 700; margin-right: 12px; }
        .step-done { background-color: #4a6741; }
        .step-current { background-color: #CA4136; }
        .step-pending { background-color: #bcbeb1; color: #5a5a5a; }
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
                            <h1>{{ __('emails.subscription_box_preparing.title', [], $locale) }}</h1>
                            <p class="subtitle">{{ __('emails.subscription_box_preparing.subtitle', [], $locale) }}</p>
                            
                            <!-- Subscription Number -->
                            <div class="highlight-box">
                                <div style="font-size: 11px; color: #bcbeb1; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">{{ __('emails.common.subscription', [], $locale) }}</div>
                                <div style="font-size: 24px; font-weight: 700; color: #ffffff; letter-spacing: 2px;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Timeline -->
                            <div class="section-title">{{ $locale === 'cs' ? 'Postup přípravy' : 'Preparation progress' }}</div>
                            <div class="warning-box">
                                <p class="info-text" style="color: #1c1c1c; margin: 0;">
                                    @if($locale === 'cs')
                                    <span class="step-number step-done">1</span> <strong>Výběr kávy</strong> – Pečlivě jsme vybrali kávy z nejlepších pražíren<br><br>
                                    <span class="step-number step-done">2</span> <strong>Káva dorazila</strong> – Balíčky z celé Evropy jsou na místě<br><br>
                                    <span class="step-number step-current">3</span> <strong>Balení boxu</strong> – Právě teď balíme váš unikátní box<br><br>
                                    <span class="step-number step-pending">4</span> <strong>Expedice</strong> – Za cca 5 dní odešleme k vám
                                    @else
                                    <span class="step-number step-done">1</span> <strong>Coffee selection</strong> – We carefully selected coffees from the best roasteries<br><br>
                                    <span class="step-number step-done">2</span> <strong>Coffee arrived</strong> – Samples from across Europe are here<br><br>
                                    <span class="step-number step-current">3</span> <strong>Packing your box</strong> – We're packing your unique box right now<br><br>
                                    <span class="step-number step-pending">4</span> <strong>Shipping</strong> – We'll ship to you in about 5 days
                                    @endif
                                </p>
                            </div>
                            
                            <!-- What's inside -->
                            <div class="info-box">
                                <div class="info-title">{{ $locale === 'cs' ? 'Co vás čeká v boxu' : 'What\'s in your box' }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    <strong>{{ $locale === 'cs' ? 'Typ kávy' : 'Coffee type' }}:</strong> 
                                    @if($subscription->configuration['type'] === 'espresso')
                                        Espresso
                                    @elseif($subscription->configuration['type'] === 'filter')
                                        {{ $locale === 'cs' ? 'Filtrovaná káva' : 'Filter coffee' }}
                                    @else
                                        Mix Espresso & {{ $locale === 'cs' ? 'Filtr' : 'Filter' }}
                                    @endif
                                    <br>
                                    <strong>{{ $locale === 'cs' ? 'Množství' : 'Quantity' }}:</strong> {{ $subscription->configuration['quantity'] ?? $subscription->configuration['amount'] ?? 2 }} {{ $locale === 'cs' ? 'balíčky' : 'packages' }}
                                </p>
                            </div>
                            
                            <!-- Expected shipment -->
                            <div class="accent-box">
                                <div class="info-title">{{ $locale === 'cs' ? 'Očekávaná expedice' : 'Expected shipment' }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    @if($locale === 'cs')
                                    Váš box odešleme přibližně za <strong>5 dní</strong>.<br>
                                    O expedici vás budeme informovat dalším e-mailem s tracking číslem.
                                    @else
                                    Your box will be shipped in approximately <strong>5 days</strong>.<br>
                                    We'll notify you with another email containing the tracking number.
                                    @endif
                                </p>
                            </div>
                            
                            <!-- What makes it special -->
                            <div class="success-box">
                                <div class="info-title" style="color: #4a6741;">{{ $locale === 'cs' ? 'Proč je váš box výjimečný' : 'Why your box is special' }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    @if($locale === 'cs')
                                    <strong>Čerstvě pražená káva</strong> z malých pražíren<br>
                                    <strong>Vybrána speciálně</strong> podle vašich preferencí<br>
                                    <strong>Káva z celé Evropy</strong> – objevujte nové chutě<br>
                                    <strong>Baleno s láskou</strong> náš tým pro vás
                                    @else
                                    <strong>Freshly roasted coffee</strong> from small roasteries<br>
                                    <strong>Selected especially</strong> according to your preferences<br>
                                    <strong>Samples from all over Europe</strong> – discover new flavors<br>
                                    <strong>Packed with love</strong> by our team for you
                                    @endif
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    {{ __('emails.subscription_paused.manage_subscription', [], $locale) }}
                                </a>
                            </div>
                            
                            <p style="font-size: 13px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ __('emails.common.questions', [], $locale) }}
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none; font-weight: 600;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 13px; color: #5a5a5a; margin-top: 24px;">
                                {{ __('emails.welcome.with_love', [], $locale) }},<br>
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
