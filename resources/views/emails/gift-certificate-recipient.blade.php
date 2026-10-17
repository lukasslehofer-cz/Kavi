@extends('emails.layouts.kavi')

@section('title', __('gifts.mail.recipient_title', [], $locale))

@section('content')
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 24px 0;">
    {{ __('gifts.mail.recipient_intro', ['giver' => $gift->giver_name], $locale) }}
</p>

@if($gift->note)
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0; background-color: #1c1c1c;">
    <tr>
        <td style="padding: 24px;">
            <p style="margin: 0 0 12px 0; font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; color: rgba(255,255,255,0.5);">{{ __('gifts.redeem.wish', [], $locale) }}</p>
            <p style="margin: 0; font-size: 18px; line-height: 1.5; color: #ffffff; font-weight: 300;">{{ __('gifts.certificate.quote_open', [], $locale) }}{{ $gift->note }}{{ __('gifts.certificate.quote_close', [], $locale) }}</p>
        </td>
    </tr>
</table>
@endif

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0; border-top: 1px solid #bcbeb1;">
    <tr>
        <td style="padding: 12px 0; font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; color: #5f5b57;">{{ __('gifts.mail.buyer_code', [], $locale) }}</td>
        <td style="padding: 12px 0; text-align: right; font-size: 18px; letter-spacing: 0.08em; color: #1c1c1c;">{{ $gift->code }}</td>
    </tr>
    @if($validUntil)
    <tr>
        <td style="padding: 12px 0; border-top: 1px solid #bcbeb1; font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; color: #5f5b57;">{{ __('gifts.redeem.row_valid_until', [], $locale) }}</td>
        <td style="padding: 12px 0; border-top: 1px solid #bcbeb1; text-align: right; font-size: 16px; color: #1c1c1c;">{{ $validUntil }}</td>
    </tr>
    @endif
</table>

<a href="{{ $redeemUrl }}" style="display: inline-block; background-color: #ca4136; color: #ffffff; padding: 14px 28px; text-decoration: none; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('gifts.mail.recipient_cta', [], $locale) }} &rarr;</a>
@endsection
