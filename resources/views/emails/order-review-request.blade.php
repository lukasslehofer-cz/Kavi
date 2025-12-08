<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light only">
    <meta name="supported-color-schemes" content="light">
    <title>{{ __('emails.review_request.title', [], $locale) }}</title>
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
        .item-name { font-weight: 600; color: #111827; font-size: 14px; }
        .item-roastery { font-size: 13px; color: #6b7280; margin-top: 2px; }
        .button { display: inline-block; background-color: #e6305a; color: #ffffff !important; text-decoration: none; padding: 14px 32px; border-radius: 9999px; font-weight: 600; font-size: 15px; margin: 24px 0; text-align: center; }
        .footer { background-color: #f9fafb; padding: 32px 40px; text-align: center; color: #6b7280; font-size: 14px; border-top: 1px solid #e5e7eb; }
        .footer-text { margin: 8px 0; font-weight: 300; }
        .footer-links { margin: 16px 0; }
        .footer-link { color: #e6305a; text-decoration: none; margin: 0 8px; }
        @media only screen and (max-width: 600px) { .content { padding: 24px !important; } h1 { font-size: 24px !important; } .header, .footer { padding: 24px !important; } .logo { max-width: 100px !important; width: 100px !important; } }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6 !important; padding: 20px 0;">
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
                                <div style="width: 64px; height: 64px; background-color: #fbbf24 !important; border-radius: 50%; margin: 0 auto; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; line-height: 64px;">
                                    ⭐
                                </div>
                            </div>
                            
                            <h1 style="text-align: center;">{{ __('emails.review_request.title', [], $locale) }}</h1>
                            <p class="subtitle" style="text-align: center;">{{ __('emails.review_request.subtitle', [], $locale) }}</p>
                            
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 14px; color: #6b7280; font-weight: 500; margin-bottom: 4px;">{{ $locale === 'cs' ? 'Objednávka' : 'Order' }}</div>
                                <div style="font-size: 20px; font-weight: 700; color: #111827;">{{ $order->order_number }}</div>
                            </div>
                            
                            <!-- What was ordered -->
                            <div class="info-box" style="background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important;" bgcolor="#f3f4f6">
                                <h3 class="info-title">☕ {{ $locale === 'cs' ? 'Co jste ochutnali' : 'What you tasted' }}</h3>
                                @foreach($order->items as $item)
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                                    <tr>
                                        <td style="padding: 0; vertical-align: top;">
                                            <div class="item-name" style="font-weight: 600; color: #111827; font-size: 14px;">{{ $item->product_name }}</div>
                                            @if($item->product && $item->product->roastery)
                                            <div class="item-roastery" style="font-size: 13px; color: #6b7280; margin-top: 2px;">{{ $item->product->roastery->name }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                @endforeach
                            </div>
                            
                            <!-- Rating Request -->
                            <div class="info-box" style="background-color: #fef3c7 !important; border: 1px solid #fcd34d !important; border-left: 4px solid #fbbf24 !important;" bgcolor="#fef3c7">
                                <h3 class="info-title" style="color: #92400e; text-align: center;">{{ $locale === 'cs' ? 'Ohodnoťte svou zkušenost' : 'Rate your experience' }}</h3>
                                <p class="info-text" style="color: #78350f; text-align: center; margin-bottom: 16px;">
                                    {{ $locale === 'cs' ? 'Jak byste ohodnotili kvalitu kávy a naše služby?' : 'How would you rate the coffee quality and our services?' }}
                                </p>
                            </div>
                            
                            <!-- Benefits -->
                            <div class="info-box" style="background-color: #f0fdf4 !important; border: 1px solid #86efac !important; border-left: 4px solid #10b981 !important;" bgcolor="#f0fdf4">
                                <h3 class="info-title" style="color: #065f46;">💚 {{ $locale === 'cs' ? 'Proč je vaše hodnocení důležité' : 'Why your review matters' }}</h3>
                                <p class="info-text" style="color: #047857;">
                                    @if($locale === 'cs')
                                    • Pomáháte ostatním kávovarům s výběrem<br>
                                    • Dáváte nám vědět, co děláme dobře<br>
                                    • Inspirujete nás ke zlepšení<br>
                                    • Vaše hodnocení je transparentní a veřejné
                                    @else
                                    • You help other coffee lovers choose<br>
                                    • You let us know what we're doing well<br>
                                    • You inspire us to improve<br>
                                    • Your review is transparent and public
                                    @endif
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ $trustpilotLink }}" class="button" target="_blank" rel="noopener">
                                    {{ $locale === 'cs' ? 'Hodnotit na Trustpilot' : 'Review on Trustpilot' }}
                                </a>
                                <p style="font-size: 12px; color: #6b7280; margin-top: 12px; font-weight: 300;">
                                    {{ $locale === 'cs' ? 'Budete přesměrováni na Trustpilot.com' : 'You will be redirected to Trustpilot.com' }}
                                </p>
                            </div>
                            
                            <!-- Explore More -->
                            <div style="background-color: #f9fafb; border-radius: 12px; padding: 20px; margin: 32px 0; text-align: center;">
                                <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0 0 12px 0;">{{ $locale === 'cs' ? 'Objevujte další kávy' : 'Discover more coffees' }}</h3>
                                <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px; font-weight: 300;">
                                    {{ $locale === 'cs' ? 'Máme pro vás desítky dalších výběrových káv' : 'We have dozens of specialty coffees for you' }}
                                </p>
                                <a href="{{ route('products.index') }}" style="display: inline-block; background-color: #111827; color: #ffffff !important; text-decoration: none; padding: 12px 24px; border-radius: 9999px; font-weight: 600; font-size: 14px;">
                                    {{ $locale === 'cs' ? 'Prohlédnout nabídku' : 'Browse selection' }}
                                </a>
                            </div>
                            
                            <p style="font-size: 14px; color: #6b7280; line-height: 1.6; margin-top: 32px; font-weight: 300;">
                                {{ __('emails.common.questions', [], $locale) }} 
                                <a href="mailto:{{ $contactEmail }}" style="color: #e6305a; text-decoration: none;">{{ $contactEmail }}</a>
                            </p>
                            
                            <p style="font-size: 14px; color: #6b7280; margin-top: 24px; font-weight: 300;">
                                {{ $locale === 'cs' ? 'Děkujeme za vaši důvěru' : 'Thank you for your trust' }},<br>
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
                                <a href="{{ route('dashboard.index') }}" class="footer-link" style="color: #e6305a;">{{ __('emails.common.my_account', [], $locale) }}</a>
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
