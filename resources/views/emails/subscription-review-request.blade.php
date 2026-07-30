<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.review_request.title', [], $locale) }}</title>
    <style>
        /* Reset styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }

        /* Swiss Style Base */
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: #bcbeb1;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #e5e6df;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .content { padding: 32px 24px !important; }
            h1 { font-size: 26px !important; }
            .header, .footer { padding: 32px 24px !important; }
        }

        /* Dark mode override */
        @media (prefers-color-scheme: dark) {
            body { background-color: #bcbeb1 !important; }
            .email-container { background-color: #e5e6df !important; }
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #bcbeb1; padding: 32px 16px;">
        <tr>
            <td align="center">
                <!--[if mso]><table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"><tr><td><![endif]-->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" width="100%" style="width: 100%; max-width: 600px; background-color: #e5e6df !important;" bgcolor="#e5e6df">

                    <!-- Header -->
                    <tr>
                        <td class="header" style="background-color: #1c1c1c; padding: 32px 40px; text-align: left;">
                            <img src="{{ asset('images/kavi-logo-white.png') }}" alt="{{ $siteName }}" width="80" style="max-width: 80px !important; width: 80px !important; height: auto !important; display: block !important; border: 0; outline: none;">
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content" style="padding: 48px 40px; color: #4a4a4a; background-color: #e5e6df;">

                            <!-- Title -->
                            <h1 style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 400; color: #1c1c1c; margin: 0 0 8px 0; line-height: 1.1; letter-spacing: -0.02em; text-transform: uppercase;">
                                {{ __('emails.subscription_review.title', [], $locale) }}
                            </h1>
                            <p style="font-size: 14px; color: #76716C; margin: 0 0 40px 0; font-weight: 400; text-transform: uppercase; letter-spacing: 0.1em;">
                                {{ __('emails.subscription_review.subtitle', [], $locale) }}
                            </p>

                            <!-- Subscription Number -->
                            <div style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
                                <div style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
                                    {{ __('emails.subscription_review.subscription_number', [], $locale) }}
                                </div>
                                <div style="font-size: 28px; font-weight: 400; color: #1c1c1c; letter-spacing: -0.02em;">
                                    {{ $subscription->subscription_number }}
                                </div>
                            </div>

                            <!-- What they've received -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 1px solid #bcbeb1;">
                                @if($deliveredOrdersCount > 0)
                                <tr>
                                    <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1; vertical-align: top;">
                                        <div style="font-size: 11px; color: #76716C; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 4px;">
                                            {{ $locale === 'cs' ? 'Doručené boxy' : 'Boxes delivered' }}
                                        </div>
                                        <div style="font-size: 15px; color: #1c1c1c;">{{ $deliveredOrdersCount }}</div>
                                    </td>
                                </tr>
                                @endif
                                @if($subscription->starts_at)
                                <tr>
                                    <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1; vertical-align: top;">
                                        <div style="font-size: 11px; color: #76716C; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 4px;">
                                            {{ $locale === 'cs' ? 'Členem od' : 'Member since' }}
                                        </div>
                                        <div style="font-size: 15px; color: #1c1c1c;">{{ $subscription->starts_at->format($locale === 'cs' ? 'd.m.Y' : 'M d, Y') }}</div>
                                    </td>
                                </tr>
                                @endif
                            </table>

                            @include('emails.partials.review-stars')

                            <!-- Explore More -->
                            <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
                                <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em; text-align: center;">
                                    {{ $locale === 'cs' ? 'Objevujte další kávy' : 'Discover more coffees' }}
                                </div>
                                <div style="text-align: center;">
                                    <a href="{{ route('products.index') }}" style="display: inline-block; background-color: transparent; color: #1c1c1c !important; text-decoration: none; padding: 14px 28px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; border: 2px solid #1c1c1c;">
                                        {{ $locale === 'cs' ? 'Prohlédnout nabídku' : 'Browse selection' }} &rarr;
                                    </a>
                                </div>
                            </div>

                            <!-- Help Text -->
                            <p style="font-size: 13px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ __('emails.common.questions', [], $locale) }}
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>

                            <p style="font-size: 13px; color: #5a5a5a; margin-top: 24px;">
                                {{ $locale === 'cs' ? 'Děkujeme za vaši důvěru' : 'Thank you for your trust' }},<br>
                                <span style="color: #1c1c1c;">{{ __('emails.common.team', [], $locale) }}</span>
                            </p>

                            <div style="border-top: 1px solid #bcbeb1; margin-top: 28px; padding-top: 16px; font-size: 12px; color: #76716C;">
                                {{ __('emails.review_request.ignore_note', [], $locale) }}
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer" style="background-color: #d5d7ca; padding: 40px; text-align: center; color: #5a5a5a; font-size: 12px;">
                            <p style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin: 0 0 4px 0;">
                                {{ $siteName }}
                            </p>
                            <p style="font-size: 12px; color: #5a5a5a; margin: 0 0 24px 0;">
                                {{ __('emails.common.tagline', [], $locale) }}
                            </p>
                            <div style="margin: 20px 0;">
                                <a href="{{ route('home') }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ route('products.index') }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.shop', [], $locale) }}</a>
                                <a href="{{ route('dashboard.index') }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.my_account', [], $locale) }}</a>
                            </div>
                            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #bcbeb1; font-size: 11px; color: #76716C;">
                                <p style="margin: 0;">{{ __('emails.common.copyright', ['year' => date('Y')], $locale) }}</p>
                            </div>
                        </td>
                    </tr>

                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
