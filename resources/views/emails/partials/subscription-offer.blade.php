{{--
    Nabídka předplatného v e-mailu o doručení objednávky z e-shopu. Vykresluje se jen
    kupcům bez běžícího předplatného ($subscriptionOffer z App\Mail\OrderDelivered).

    $subscriptionOffer: url, price ("890 Kč"), lineup (věta o pražírnách měsíce nebo null),
                        has_coffee (objednávka obsahovala kávu -> nadpis "až tahle káva dojde")
--}}
@php($offerVariant = $subscriptionOffer['has_coffee'] ? '' : '_generic')
<div style="margin: 40px 0 0 0; padding: 28px 24px; border: 2px solid #CA4136; background-color: #f3e3df;">
    <div style="font-size: 11px; font-weight: 600; color: #CA4136; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
        {{ __('emails.subscription_offer.label'.$offerVariant, [], $locale) }}
    </div>
    <div style="font-size: 24px; color: #1c1c1c; text-transform: uppercase; letter-spacing: -0.01em; line-height: 1.15; margin-bottom: 12px;">
        {{ __('emails.subscription_offer.title'.$offerVariant, [], $locale) }}
    </div>
    <p style="font-size: 14px; color: #4a4a4a; line-height: 1.6; margin: 0 0 20px 0;">
        {{ __('emails.subscription_offer.text', [], $locale) }}
        @if($subscriptionOffer['lineup']){{ $subscriptionOffer['lineup'] }}@endif
        {{ __('emails.subscription_offer.terms', ['price' => $subscriptionOffer['price']], $locale) }}
    </p>
    <a href="{{ $subscriptionOffer['url'] }}" style="display: inline-block; background-color: #ca4136; color: #ffffff !important; padding: 16px 32px; text-decoration: none; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">{{ __('emails.subscription_offer.cta', [], $locale) }} &rarr;</a>
</div>
