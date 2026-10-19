@extends('emails.layouts.kavi')

@section('title', __('emails.subscription_skipped_payment.title', [], $locale))

@section('subtitle', __('emails.subscription_skipped_payment.subtitle', [], $locale))

@section('content')
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 24px 0;">
    {{ __('emails.subscription_skipped_payment.intro', ['number' => $subscription->subscription_number, 'date' => $shipmentDate], $locale) }}
</p>

<div style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
    <div style="font-size: 11px; color: #76716C; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
        {{ __('emails.subscription_skipped_payment.shipment', [], $locale) }}
    </div>
    <div style="font-size: 20px; color: #1c1c1c; margin-bottom: 20px;">{{ $shipmentDate }}</div>
    <div style="font-size: 11px; color: #76716C; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
        {{ __('emails.subscription_skipped_payment.amount', [], $locale) }}
    </div>
    <div style="font-size: 20px; color: #1c1c1c;">{{ $amount }}</div>
    @if($subscription->last_payment_failure_reason)
    <div style="font-size: 11px; color: #76716C; margin: 20px 0 8px 0; text-transform: uppercase; letter-spacing: 0.15em;">
        {{ __('emails.subscription_skipped_payment.reason', [], $locale) }}
    </div>
    <div style="font-size: 15px; color: #5a5a5a;">{{ $subscription->last_payment_failure_reason }}</div>
    @endif
</div>

<a href="{{ $payUrl }}" style="display: inline-block; background-color: #ca4136; color: #ffffff; padding: 14px 28px; text-decoration: none; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.subscription_skipped_payment.cta', [], $locale) }} &rarr;</a>

<p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 32px 0 8px 0;">
    {{ __('emails.subscription_skipped_payment.valid_until', ['date' => $validUntil], $locale) }}
</p>
<p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 0;">
    {{ __('emails.subscription_skipped_payment.card_note', [], $locale) }}
</p>
@endsection
