{{--
    Platba za další box a pod ní akce v účtu (data z UpcomingBoxMailable::boxViewData()).

    $paymentNote  věta pod tabulkou platby (volitelná)
    $pauseLabel   text tlačítka: "Přeskočit tento box" / "Prodloužit pauzu"
    $deadlineText do kdy akce jdou (volitelné)

    $pauseUrl a $changeUrl jsou null, když účet akci zákazníkovi nedovolí - tlačítko
    se pak nevykreslí a zůstane jen odkaz na správu předplatného.
--}}
@if($paymentDate)
<div style="font-size: 11px; color: #76716C; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.15em;">
    {{ __('emails.upcoming_box.payment_label', [], $locale) }}
</div>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 2px solid #CA4136; margin: 0 0 12px 0;">
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ __('emails.common.subscription', [], $locale) }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $subscription->subscription_number }}</td>
    </tr>
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ __('emails.upcoming_box.payment_date', [], $locale) }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $paymentDate }}</td>
    </tr>
    @if($shipping)
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ __('emails.upcoming_box.shipping', [], $locale) }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $shipping }}</td>
    </tr>
    @endif
    <tr>
        <td style="padding: 16px 0; font-size: 14px; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.upcoming_box.amount', [], $locale) }}</td>
        <td style="padding: 16px 0; font-size: 22px; color: #1c1c1c; text-align: right;">{{ $amount }}</td>
    </tr>
</table>
@if(! empty($paymentNote))
<p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 0;">
    {{ $paymentNote }}
</p>
@endif
@endif

@if($pauseUrl || $changeUrl)
<div style="font-size: 11px; color: #76716C; margin: 40px 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
    {{ __('emails.upcoming_box.actions_label', [], $locale) }}
</div>
<div>
    @if($pauseUrl)
    <a href="{{ $pauseUrl }}" style="display: inline-block; margin: 0 8px 12px 0; background-color: transparent; border: 2px solid #1c1c1c; color: #1c1c1c; padding: 14px 24px; text-decoration: none; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em;">{{ $pauseLabel }}</a>
    @endif
    @if($changeUrl)
    <a href="{{ $changeUrl }}" style="display: inline-block; margin: 0 8px 12px 0; background-color: transparent; border: 2px solid #1c1c1c; color: #1c1c1c; padding: 14px 24px; text-decoration: none; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em;">{{ __('emails.upcoming_box.change', [], $locale) }}</a>
    @endif
</div>
@if(! empty($deadlineText))
<p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 4px 0 0 0;">
    {{ $deadlineText }}
</p>
@endif
@endif

<p style="font-size: 14px; line-height: 1.6; margin: 32px 0 0 0;">
    <a href="{{ $manageUrl }}" style="color: #CA4136; text-decoration: none;">{{ __('emails.upcoming_box.manage', [], $locale) }} &rarr;</a>
</p>
