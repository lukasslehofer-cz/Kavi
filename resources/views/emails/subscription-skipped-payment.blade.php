@extends('emails.layouts.kavi')

@section('title', __('emails.subscription_skipped_payment.title', [], $locale))

@section('subtitle', __('emails.subscription_skipped_payment.subtitle', [], $locale))

@section('content')
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 32px 0;">
    {{ __('emails.subscription_skipped_payment.intro', ['number' => $subscription->subscription_number, 'date' => $shipmentDate], $locale) }}
</p>

{{-- Termín: platba musí proběhnout ještě dnes --}}
<div style="margin: 0 0 32px 0; padding: 24px; border: 2px solid #CA4136; background-color: #f3e3df;">
    <div style="font-size: 11px; font-weight: 600; color: #CA4136; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
        {{ __('emails.subscription_skipped_payment.deadline_label', [], $locale) }}
    </div>
    <div style="font-size: 26px; color: #1c1c1c; text-transform: uppercase; letter-spacing: -0.01em; margin-bottom: 12px;">
        {{ __('emails.subscription_skipped_payment.deadline_value', [], $locale) }}
    </div>
    <p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 0 0 20px 0;">
        {{ __('emails.subscription_skipped_payment.deadline', ['date' => $today, 'shipment' => $shipmentDate], $locale) }}
    </p>
    <a href="{{ $payUrl }}" style="display: inline-block; background-color: #ca4136; color: #ffffff; padding: 16px 32px; text-decoration: none; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.subscription_skipped_payment.cta', [], $locale) }} &rarr;</a>
</div>

{{-- Shrnutí předplatného --}}
<div style="font-size: 11px; color: #76716C; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.15em;">
    {{ __('emails.subscription_skipped_payment.summary', [], $locale) }}
</div>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 2px solid #CA4136; margin: 0 0 32px 0;">
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ __('emails.subscription_skipped_payment.subscription', [], $locale) }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $subscription->subscription_number }}</td>
    </tr>
    @foreach($summaryRows as $row)
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ $row['label'] }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $row['value'] }}</td>
    </tr>
    @endforeach
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ __('emails.subscription_skipped_payment.shipment', [], $locale) }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $shipmentDate }}</td>
    </tr>
    @if($subscription->last_payment_failure_reason)
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ __('emails.subscription_skipped_payment.reason', [], $locale) }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $subscription->last_payment_failure_reason }}</td>
    </tr>
    @endif
    <tr>
        <td style="padding: 16px 0; font-size: 14px; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('emails.subscription_skipped_payment.amount', [], $locale) }}</td>
        <td style="padding: 16px 0; font-size: 22px; color: #1c1c1c; text-align: right;">{{ $amount }}</td>
    </tr>
</table>

<p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 0;">
    {{ __('emails.subscription_skipped_payment.card_note', [], $locale) }}
</p>
@endsection
