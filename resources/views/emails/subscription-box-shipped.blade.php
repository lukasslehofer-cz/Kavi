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
        body { margin: 0; padding: 0; width: 100%; background-color: #bcbeb1; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #e5e6df; }
        @media only screen and (max-width: 600px) { .content { padding: 32px 24px !important; } h1 { font-size: 26px !important; } .header, .footer { padding: 32px 24px !important; } }
        @media (prefers-color-scheme: dark) { body { background-color: #bcbeb1 !important; } .email-container { background-color: #e5e6df !important; } }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #bcbeb1; padding: 32px 16px;">
        <tr>
            <td align="center">
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
                            
                            <h1 style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 400; color: #1c1c1c; margin: 0 0 8px 0; line-height: 1.1; letter-spacing: -0.02em; text-transform: uppercase;">
                                {{ __('emails.subscription_box_shipped.title', [], $locale) }}
                            </h1>
                            <p style="font-size: 14px; color: #76716C; margin: 0 0 40px 0; font-weight: 400; text-transform: uppercase; letter-spacing: 0.1em;">
                                {{ __('emails.subscription_box_shipped.subtitle', [], $locale) }}
                            </p>
                            
                            <!-- Subscription Number -->
                            <div style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
                                <div style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.subscription_box_shipped.subscription_number', [], $locale) }}
                                </div>
                                <div style="font-size: 28px; font-weight: 400; color: #1c1c1c; letter-spacing: -0.02em;">
                                    {{ $subscription->subscription_number }}
                                </div>
                            </div>
                            
                            <!-- Tracking -->
                            @if($subscription->packeta_tracking_url)
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.subscription_box_shipped.track_package', [], $locale) }}
                                </div>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.6; margin: 4px 0;">
                                    {{ $locale === 'cs' ? 'Zásilku můžete sledovat v reálném čase:' : 'You can track your package in real-time:' }}
                                </p>
                                <div style="margin: 16px 0;">
                                    <a href="{{ $subscription->packeta_tracking_url }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 12px 24px; font-weight: 400; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                        {{ __('emails.subscription_box_shipped.track_package', [], $locale) }} →
                                    </a>
                                </div>
                                @if($subscription->packeta_packet_id)
                                <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 12px 0 0 0;">
                                    {{ __('emails.subscription_box_shipped.tracking_number', [], $locale) }}: <strong style="color: #1c1c1c;">{{ $subscription->packeta_packet_id }}</strong>
                                </p>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Pickup point -->
                            @if(isset($subscription->packeta_point_name))
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.subscription_box_shipped.pickup_point', [], $locale) }}
                                </div>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.6; margin: 4px 0;">
                                    <span style="color: #CA4136;">→</span> {{ $subscription->packeta_point_name }}
                                </p>
                                @if(isset($subscription->packeta_point_address))
                                <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 4px 0 4px 18px;">{{ $subscription->packeta_point_address }}</p>
                                @endif
                            </div>
                            @endif
                            
                            <!-- What's next -->
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 12px;">
                                    {{ $locale === 'cs' ? 'Co se stane dále' : 'What happens next' }}
                                </div>
                                <p style="font-size: 15px; color: #5a5a5a; margin: 0; line-height: 1.8;">
                                    @if($locale === 'cs')
                                    <span style="color: #CA4136;">01</span> Zásilka dorazí na výdejní místo během 1-3 pracovních dnů<br>                            
                                    <span style="color: #CA4136;">02</span> Vyzvedněte si balík na výdejním místě<br>
                                    <span style="color: #CA4136;">03</span> Vychutnejte si čerstvou kávu
                                    @else
                                    <span style="color: #CA4136;">01</span> The box will arrive at the pickup point within 2-4 business days<br>                                    
                                    <span style="color: #CA4136;">02</span> Pick up your box at the pickup point<br>
                                    <span style="color: #CA4136;">03</span> Enjoy your fresh coffee
                                    @endif
                                </p>
                            </div>
                            
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
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ __('emails.common.delivery_time', [], $locale) }}
                                </span>
                            </div>
                            
                            <!-- Help Text -->
                            <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ $locale === 'cs' ? 'Těšíme se na vaši zpětnou vazbu! Napište nám na' : 'We look forward to your feedback! Write to us at' }}
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 14px; color: #5a5a5a; margin-top: 24px;">
                                {{ __('emails.welcome.with_love', [], $locale) }},<br>
                                <span style="color: #1c1c1c;">{{ __('emails.common.team', [], $locale) }}</span>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #d5d7ca; padding: 40px; text-align: center; color: #5a5a5a; font-size: 12px;">
                            <p style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin: 0 0 4px 0;">{{ $siteName }}</p>
                            <p style="font-size: 12px; color: #5a5a5a; margin: 0 0 24px 0;">{{ __('emails.common.tagline', [], $locale) }}</p>
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
            </td>
        </tr>
    </table>
</body>
</html>
