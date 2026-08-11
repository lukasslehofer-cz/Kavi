{{--
    Sdílený layout pro affiliate e-maily.

    Ostatní (starší) šablony v resources/views/emails jsou samostatné HTML dokumenty
    s kopírovanou hlavičkou a patičkou. Nové affiliate maily používají tento layout,
    aby se stejný obal nekopíroval počtvrté. Vzhled je 1:1 podle
    emails/subscription-payment-success.blade.php.

    Sekce: @section('title'), @section('subtitle'), @section('content')
    Volitelné proměnné: $affiliateUrl (přepne poslední odkaz v patičce)

    $logoUrl, $homeUrl, $shopUrl dodává LocalizedMailable – míří na doménu
    regionu příjemce (kavi.cz / kavibox.com). Nepoužívat tu route() ani
    asset(), ty vezmou hostitele z requestu, takže EN mail odeslaný
    z administrace na kavi.cz by odkazoval na kavi.cz.
--}}
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>@yield('title')</title>
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
                        <td class="content" style="padding: 48px 40px; color: #4a4a4a; background-color: #e5e6df;">

                            <h1 style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 400; color: #1c1c1c; margin: 0 0 8px 0; line-height: 1.1; letter-spacing: -0.02em; text-transform: uppercase;">
                                @yield('title')
                            </h1>
                            @hasSection('subtitle')
                            <p style="font-size: 14px; color: #76716C; margin: 0 0 40px 0; font-weight: 400; text-transform: uppercase; letter-spacing: 0.1em;">
                                @yield('subtitle')
                            </p>
                            @endif

                            @yield('content')

                            <!-- Help Text -->
                            <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin-top: 32px;">
                                {{ __('emails.common.questions', [], $locale) }}
                                <a href="mailto:{{ $contactEmail }}" style="color: #CA4136; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>

                            <p style="font-size: 14px; color: #5a5a5a; margin-top: 24px;">
                                {{ __('emails.common.regards', [], $locale) }},<br>
                                <span style="color: #1c1c1c;">{{ __('emails.common.team', [], $locale) }}</span>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer" style="background-color: #d5d7ca; padding: 40px; text-align: center; color: #5a5a5a; font-size: 12px;">
                            <p style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin: 0 0 4px 0;">{{ $siteName }}</p>
                            <p style="font-size: 12px; color: #5a5a5a; margin: 0 0 24px 0;">{{ __('emails.common.tagline', [], $locale) }}</p>
                            <div style="margin: 20px 0;">
                                <a href="{{ $homeUrl }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.home', [], $locale) }}</a>
                                <a href="{{ $shopUrl }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.common.shop', [], $locale) }}</a>
                                @isset($affiliateUrl)
                                <a href="{{ $affiliateUrl }}" style="color: #1c1c1c; text-decoration: none; margin: 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.affiliate_common.section', [], $locale) }}</a>
                                @endisset
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
