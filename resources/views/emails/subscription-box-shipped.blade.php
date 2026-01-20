<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.subscription_box_shipped.title', [], $locale) }}</title>
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
        .button-secondary { display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 14px 32px; font-weight: 600; font-size: 13px; margin: 24px 0; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
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
                            <h1>{{ __('emails.subscription_box_shipped.title', [], $locale) }}</h1>
                            <p class="subtitle">{{ __('emails.subscription_box_shipped.subtitle', [], $locale) }}</p>
                            
                            <!-- Subscription Number -->
                            <div class="highlight-box">
                                <div style="font-size: 11px; color: #bcbeb1; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">{{ __('emails.subscription_box_shipped.subscription_number', [], $locale) }}</div>
                                <div style="font-size: 24px; font-weight: 700; color: #ffffff; letter-spacing: 2px;">{{ $subscription->subscription_number }}</div>
                            </div>
                            
                            <!-- Tracking -->
                            @if($subscription->packeta_tracking_url)
                            <div class="accent-box">
                                <div class="info-title">{{ __('emails.subscription_box_shipped.track_package', [], $locale) }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    {{ $locale === 'cs' ? 'Zásilku můžete sledovat v reálném čase pomocí Zásilkovny:' : 'You can track your package in real-time:' }}
                                </p>
                                <div style="text-align: center; margin: 16px 0;">
                                    <a href="{{ $subscription->packeta_tracking_url }}" class="button-secondary">
                                        {{ __('emails.subscription_box_shipped.track_package', [], $locale) }}
                                    </a>
                                </div>
                                @if($subscription->packeta_packet_id)
                                <p class="info-text" style="color: #1c1c1c;">
                                    <strong>{{ __('emails.subscription_box_shipped.tracking_number', [], $locale) }}:</strong> {{ $subscription->packeta_packet_id }}
                                </p>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Pickup point -->
                            @if(isset($subscription->packeta_point_name))
                            <div class="info-box">
                                <div class="info-title">{{ __('emails.subscription_box_shipped.pickup_point', [], $locale) }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    <strong>{{ $subscription->packeta_point_name }}</strong><br>
                                    @if(isset($subscription->packeta_point_address))
                                    {{ $subscription->packeta_point_address }}
                                    @endif
                                </p>
                            </div>
                            @endif
                            
                            <!-- What's next -->
                            <div class="section-title">{{ $locale === 'cs' ? 'Co se stane dále?' : 'What happens next?' }}</div>
                            <div class="success-box">
                                <p class="info-text" style="color: #1c1c1c; margin: 0;">
                                    @if($locale === 'cs')
                                    <span class="step-number">1</span> <strong>Doručení</strong> – Box dorazí na výdejní místo během 1-2 dnů<br><br>
                                    <span class="step-number">2</span> <strong>SMS/Email</strong> – Zásilkovna vás informuje o doručení<br><br>
                                    <span class="step-number">3</span> <strong>Vyzvednutí</strong> – Vyzvedněte si box na výdejním místě<br><br>
                                    <span class="step-number">4</span> <strong>Užívejte si kávu</strong> – A dejte nám vědět, jak vám chutná!
                                    @else
                                    <span class="step-number">1</span> <strong>Delivery</strong> – The box will arrive at the pickup point within 1-2 days<br><br>
                                    <span class="step-number">2</span> <strong>SMS/Email</strong> – The carrier will notify you about delivery<br><br>
                                    <span class="step-number">3</span> <strong>Pickup</strong> – Pick up your box at the pickup point<br><br>
                                    <span class="step-number">4</span> <strong>Enjoy your coffee</strong> – And let us know how you like it!
                                    @endif
                                </p>
                            </div>
                            
                            <!-- What's inside -->
                            <div class="warning-box">
                                <div class="info-title">{{ __('emails.subscription_box_shipped.whats_inside', [], $locale) }}</div>
                                <p class="info-text" style="color: #1c1c1c;">
                                    {{ __('emails.subscription_box_shipped.inside_text', [], $locale) }}
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ route('dashboard.subscription') }}" class="button">
                                    {{ __('emails.subscription_confirmation.manage_button', [], $locale) }}
                                </a>
                            </div>
                            
                            <p style="font-size: 13px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ $locale === 'cs' ? 'Těšíme se na vaši zpětnou vazbu! Napište nám na' : 'We look forward to your feedback! Write to us at' }}
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
