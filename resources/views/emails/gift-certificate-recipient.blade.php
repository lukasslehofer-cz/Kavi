@extends('emails.layouts.kavi')

@section('title', __('gifts.mail.recipient_title', [], $locale))
@section('subtitle', __('gifts.mail.recipient_subtitle', [], $locale))

@section('content')
@php
    $isSubscription = $gift->isSubscription();
    $steps = __($isSubscription ? 'gifts.mail.recipient_steps_subscription' : 'gifts.mail.recipient_steps_voucher', [], $locale);

    $rows = [
        [__('gifts.redeem.row_for', [], $locale), $gift->recipient_name],
        [__('gifts.redeem.from', [], $locale), $gift->giver_name],
    ];
    if ($isSubscription) {
        $rows[] = [__('gifts.redeem.row_box', [], $locale), $gift->boxLabel($locale)];
        $rows[] = [__('gifts.redeem.row_count', [], $locale), __('gifts.redeem.row_count_value', ['count' => $gift->months], $locale)];
    } else {
        $rows[] = [__('gifts.redeem.row_valid_on', [], $locale), __('gifts.redeem.row_valid_on_value', ['site' => $siteDisplay], $locale)];
    }
    if ($validUntil) {
        $rows[] = [__('gifts.redeem.row_valid_until', [], $locale), $validUntil];
    }
@endphp

<!-- Intro -->
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 32px 0;">
    @if($isSubscription)
    {{ __('gifts.mail.recipient_intro_subscription', ['giver' => $gift->giver_name, 'months' => $monthsLabel], $locale) }}
    @else
    {{ __('gifts.mail.recipient_intro_voucher', ['giver' => $gift->giver_name, 'amount' => $amountLabel], $locale) }}
    @endif
</p>

<!-- Wish -->
@if($gift->note)
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 32px 0; background-color: #1c1c1c;">
    <tr>
        <td style="padding: 24px;">
            <p style="margin: 0 0 12px 0; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; color: #bcbeb1;">{{ __('gifts.redeem.wish', [], $locale) }}</p>
            <p style="margin: 0; font-size: 18px; line-height: 1.5; color: #ffffff; font-weight: 300;">{{ __('gifts.certificate.quote_open', [], $locale) }}{{ $gift->note }}{{ __('gifts.certificate.quote_close', [], $locale) }}</p>
        </td>
    </tr>
</table>
@endif

<!-- Gift -->
<div style="margin: 32px 0 0 0; padding-top: 24px; border-top: 2px solid #CA4136;">
    <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
        {{ __('gifts.mail.recipient_section_gift', [], $locale) }}
    </div>
</div>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 1px solid #bcbeb1;">
    @foreach($rows as [$label, $value])
    <tr>
        <td style="padding: 14px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C; vertical-align: top;">{{ $label }}</td>
        <td style="padding: 14px 0; border-bottom: 1px solid #bcbeb1; font-size: 15px; color: #1c1c1c; text-align: right; vertical-align: top;">{{ $value }}</td>
    </tr>
    @endforeach
</table>

<!-- Code -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0 0 0; background-color: #1c1c1c;">
    <tr>
        <td style="padding: 16px 20px; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; color: #bcbeb1;">{{ __('gifts.mail.buyer_code', [], $locale) }}</td>
        <td style="padding: 16px 20px; text-align: right; font-size: 22px; letter-spacing: 0.08em; color: #ffffff; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">{{ $gift->code }}</td>
    </tr>
</table>

<!-- Steps -->
<div style="margin: 40px 0 0 0; padding-top: 24px; border-top: 2px solid #CA4136;">
    <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
        {{ __('gifts.mail.recipient_section_steps', [], $locale) }}
    </div>
</div>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 1px solid #bcbeb1;">
    @foreach($steps as $index => $step)
    <tr>
        <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1; vertical-align: top; width: 36px;">
            <span style="font-size: 11px; color: #CA4136; font-weight: 400;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
        </td>
        <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1; vertical-align: top;">
            <div style="font-size: 15px; color: #1c1c1c; margin-bottom: 4px;">{{ $step['t'] }}</div>
            <div style="font-size: 14px; color: #5a5a5a; line-height: 1.5;">{{ $step['d'] }}</div>
        </td>
    </tr>
    @endforeach
</table>

<!-- CTA Button -->
<div style="text-align: center; margin: 40px 0 0 0;">
    <a href="{{ $redeemUrl }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
        {{ __('gifts.mail.recipient_cta', [], $locale) }} →
    </a>
</div>
<p style="font-size: 13px; color: #76716C; text-align: center; margin: 12px 0 40px 0;">
    {{ __('gifts.mail.recipient_pdf_note', [], $locale) }}
</p>

<!-- Validity -->
@if($validUntil)
<div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
    <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
        {{ __('gifts.mail.recipient_valid_title', [], $locale) }}
    </div>
    <p style="font-size: 14px; color: #4a6741; line-height: 1.6; margin: 0;">
        @if($isSubscription)
        {{ __('gifts.mail.recipient_valid_subscription', ['date' => $validUntil], $locale) }}
        @else
        {{ __('gifts.redeem.voucher_rules', ['amount' => $amountLabel, 'date' => $validUntil], $locale) }}
        @endif
    </p>
</div>
@endif

<!-- About KAVI -->
<div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
    <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
        {{ __('gifts.mail.recipient_about_title', [], $locale) }}
    </div>
    <p style="font-size: 15px; color: #1c1c1c; line-height: 1.6; margin: 0;">
        {{ __($isSubscription ? 'gifts.mail.recipient_about' : 'gifts.mail.recipient_about_voucher', [], $locale) }}
    </p>
    @if($lineupNames)
    <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 12px 0 0 0;">
        <span style="color: #CA4136;">→</span> {{ __('gifts.mail.recipient_lineup', ['names' => $lineupNames], $locale) }}
    </p>
    @endif
</div>

<!-- Features -->
<div style="margin: 40px 0 0 0; padding-top: 24px; border-top: 1px solid #bcbeb1;">
    @foreach(__('gifts.mail.recipient_features', [], $locale) as $feature)
    <span style="display: inline-block; margin: 0 24px 8px 0; font-size: 12px; color: #5a5a5a;">
        <span style="color: #CA4136;">→</span> {{ $feature }}
    </span>
    @endforeach
</div>
@endsection
