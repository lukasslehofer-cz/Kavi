@extends('emails.layouts.kavi')

@section('title', __('gifts.mail.buyer_title', [], $locale))

@section('content')
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 24px 0;">
    {{ __('gifts.mail.buyer_intro', ['order' => $orderNumber, 'recipient' => $gift->recipient_name], $locale) }}
</p>

@if($gift->delivery === 'recipient' && $gift->recipient_email && $sendAt)
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 24px 0;">
    {{ __('gifts.mail.buyer_scheduled', ['date' => $sendAt, 'email' => $gift->recipient_email], $locale) }}
</p>
@endif

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0; background-color: #1c1c1c;">
    <tr>
        <td style="padding: 16px 20px; font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; color: #bcbeb1;">{{ __('gifts.mail.buyer_code', [], $locale) }}</td>
        <td style="padding: 16px 20px; text-align: right; font-size: 20px; letter-spacing: 0.08em; color: #ffffff; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">{{ $gift->code }}</td>
    </tr>
</table>

@if($validUntil)
<p style="font-size: 14px; color: #5f5b57; line-height: 1.6; margin: 0 0 32px 0;">
    {{ __('gifts.mail.buyer_valid', ['date' => $validUntil, 'url' => $redeemDisplay], $locale) }}
</p>
@endif

<a href="{{ $downloadUrl }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff; padding: 14px 28px; text-decoration: none; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('gifts.cart.download', [], $locale) }}</a>
@endsection
