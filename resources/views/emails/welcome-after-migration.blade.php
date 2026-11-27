<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.welcome_migration.title', [], $locale) }}</title>
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
                            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" width="80" style="max-width: 80px !important; width: 80px !important; height: auto !important; display: block !important; border: 0; outline: none;">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 48px 40px; color: #4a4a4a; background-color: #e5e6df;">
                            
                            <h1 style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 400; color: #1c1c1c; margin: 0 0 8px 0; line-height: 1.1; letter-spacing: -0.02em; text-transform: uppercase;">
                                {{ __('emails.welcome_migration.title', [], $locale) }}
                            </h1>
                            <p style="font-size: 14px; color: #76716C; margin: 0 0 40px 0; font-weight: 400; text-transform: uppercase; letter-spacing: 0.1em;">
                                {{ __('emails.welcome_migration.subtitle', [], $locale) }}
                            </p>
                            
                            <!-- Good news -->
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ __('emails.welcome_migration.great_news', [], $locale) }}
                                </div>
                                <p style="font-size: 15px; color: #4a6741; margin: 0; line-height: 1.6;">
                                    {{ __('emails.welcome_migration.migrated_text', [], $locale) }}
                                </p>
                            </div>
                            
                            <!-- What's new -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.welcome_migration.what_it_means', [], $locale) }}
                                </div>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.8; margin: 4px 0;">
                                    <span style="color: #4a6741;">→</span> {{ __('emails.welcome_migration.means_1', [], $locale) }}<br>
                                    <span style="color: #4a6741;">→</span> {{ __('emails.welcome_migration.means_2', [], $locale) }}<br>
                                    <span style="color: #4a6741;">→</span> {{ __('emails.welcome_migration.means_3', [], $locale) }}<br>
                                    <span style="color: #4a6741;">→</span> {{ __('emails.welcome_migration.means_4', [], $locale) }}
                                </p>
                            </div>
                            
                            @if($subscription)
                            <!-- Subscription details -->
                            <div style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
                                <div style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.welcome_migration.your_subscription', [], $locale) }}
                                </div>
                                @php
                                $breakdown = \App\Helpers\SubscriptionPricing::forRecurringPayment($subscription);
                                @endphp
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ __('emails.welcome_migration.status', [], $locale) }}:</td>
                                        <td style="padding: 8px 0; font-size: 14px; text-align: right;">
                                            @if($subscription->status === 'active')
                                                <span style="color: #4a6741;">{{ __('emails.welcome_migration.status_active', [], $locale) }}</span>
                                            @elseif($subscription->status === 'paused')
                                                <span style="color: #CA4136;">{{ __('emails.welcome_migration.status_paused', [], $locale) }}</span>
                                            @else
                                                <span style="color: #1c1c1c;">{{ ucfirst($subscription->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ __('emails.welcome_migration.price', [], $locale) }}:</td>
                                        <td style="padding: 8px 0; font-size: 14px; color: #1c1c1c; text-align: right;">{{ \App\Helpers\CurrencyHelper::formatByCurrency($breakdown->total, $breakdown->currency) }} / {{ __('emails.welcome_migration.per_month', [], $locale) }}</td>
                                    </tr>
                                    @if($subscription->next_billing_date)
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ __('emails.welcome_migration.next_payment', [], $locale) }}:</td>
                                        <td style="padding: 8px 0; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $subscription->next_billing_date->format('j. n. Y') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ __('emails.welcome_migration.frequency', [], $locale) }}:</td>
                                        <td style="padding: 8px 0; font-size: 14px; color: #1c1c1c; text-align: right;">
                                            @if($subscription->frequency_months == 1)
                                                {{ __('emails.frequency.monthly', [], $locale) }}
                                            @elseif($subscription->frequency_months == 3)
                                                {{ __('emails.frequency.quarterly', [], $locale) }}
                                            @else
                                                {{ __('emails.welcome_migration.every_n_months', ['count' => $subscription->frequency_months], $locale) }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @endif
                            
                            <!-- Action required -->
                            <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #CA4136; background-color: #d5d7ca;">
                                <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
                                    {{ __('emails.welcome_migration.help_title', [], $locale) }}
                                </div>
                                <p style="font-size: 15px; color: #5a5a5a; margin: 0; line-height: 1.6;">
                                    {{ __('emails.welcome_migration.help_text', [], $locale) }}
                                </p>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.8; margin: 12px 0 0 0;">
                                    <span style="color: #CA4136;">01</span> {{ __('emails.welcome_migration.step1', [], $locale) }}<br>
                                    <span style="color: #CA4136;">02</span> {{ __('emails.welcome_migration.step2', [], $locale) }}<br>
                                    <span style="color: #CA4136;">03</span> {{ __('emails.welcome_migration.step3', [], $locale) }}
                                </p>
                            </div>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 40px 0;">
                                <a href="{{ $passwordSetUrl }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                    {{ __('emails.welcome_migration.set_password', [], $locale) }} →
                                </a>
                            </div>
                            
                            <!-- Alternative link -->
                            <div style="margin: 32px 0; padding: 16px 20px; background-color: #d5d7ca;">
                                <p style="font-size: 11px; color: #76716C; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.1em;">
                                    {{ __('emails.welcome_migration.copy_link', [], $locale) }}
                                </p>
                                <p style="font-size: 12px; color: #1c1c1c; word-break: break-all; margin: 0; font-family: monospace;">
                                    {{ $passwordSetUrl }}
                                </p>
                            </div>
                            
                            <!-- FAQ -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.welcome_migration.faq', [], $locale) }}
                                </div>
                                <p style="font-size: 15px; color: #1c1c1c; line-height: 1.8; margin: 4px 0;">
                                    <strong>{{ __('emails.welcome_migration.faq_card_q', [], $locale) }}</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">{{ __('emails.welcome_migration.faq_card_a', [], $locale) }}</span><br><br>
                                    <strong>{{ __('emails.welcome_migration.faq_date_q', [], $locale) }}</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">{{ __('emails.welcome_migration.faq_date_a', [], $locale) }}</span><br><br>
                                    <strong>{{ __('emails.welcome_migration.faq_email_q', [], $locale) }}</strong><br>
                                    <span style="font-size: 14px; color: #5a5a5a;">{{ $user->email }}</span><br><br>
                                    <strong>{{ __('emails.welcome_migration.faq_link_q', [], $locale) }}</strong><br>
                                    {{-- Text s uvozovkami byl v šabloně bez escapování, proto {!! !!} --}}
                                    <span style="font-size: 14px; color: #5a5a5a;">{!! __('emails.welcome_migration.faq_link_a', [], $locale) !!}</span>
                                </p>
                            </div>
                            
                            <!-- Features -->
                            <div style="margin: 40px 0 32px 0; padding-top: 24px; border-top: 1px solid #bcbeb1;">
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ __('emails.common.freshly_roasted', [], $locale) }}
                                </span>
                                <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
                                    <span style="color: #CA4136;">→</span> {{ __('emails.welcome_migration.feature_new_system', [], $locale) }}
                                </span>
                            </div>
                            
                            <!-- Help Text -->
                            <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ __('emails.common.questions', [], $locale) }}
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 15px; color: #1c1c1c; line-height: 1.6; margin-top: 24px; text-align: center;">
                                <strong>{{ __('emails.welcome_migration.thanks', [], $locale) }}</strong>
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
                                <a href="{{ $homeUrl }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ $shopUrl }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ $subscriptionsUrl }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.subscription', [], $locale) }}</a>
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
