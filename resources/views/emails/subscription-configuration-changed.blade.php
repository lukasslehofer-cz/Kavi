@extends('emails.layouts.kavi')

@php
    $t = fn (string $key, array $replace = []) => __('emails.subscription_configuration_changed.'.$key, $replace, $locale);
    $number = $subscription->subscription_number ?? '#'.$subscription->id;
    $subtitleKey = ($scheduled ? 'subtitle_scheduled' : 'subtitle_applied').($effectiveDate ? '' : '_no_date');
@endphp

@section('title', $t('title'))

@section('subtitle', $t($subtitleKey, ['date' => $effectiveDate]))

@section('content')
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 32px 0;">
    @if($scheduled)
        {{ $lockedDate ? $t('intro_scheduled', ['locked' => $lockedDate, 'number' => $number]) : $t('intro_scheduled_no_date', ['number' => $number]) }}
    @else
        {{ $t('intro_applied', ['number' => $number]) }}
    @endif
</p>

{{-- Nová konfigurace --}}
<div style="font-size: 11px; color: #76716C; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.15em;">
    {{ $t('new') }}
</div>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 2px solid #CA4136; margin: 0 0 32px 0;">
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ $t('subscription') }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $number }}</td>
    </tr>
    @foreach($newRows as $row)
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ $row['label'] }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $row['value'] }}</td>
    </tr>
    @endforeach
    @if($effectiveDate)
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ $t('applies_from') }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $effectiveDate }}</td>
    </tr>
    @endif
    @if($nextPayment)
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ $t('next_payment') }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $nextPayment }}</td>
    </tr>
    @endif
    {{-- Běžící sleva z kupónu platí i pro novou konfiguraci --}}
    @if($discountAmount)
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ $t('box_price') }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $boxPrice }}</td>
    </tr>
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ $t('discount') }}@if($discountCode) ({{ $discountCode }})@endif</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">&minus;{{ $discountAmount }}</td>
    </tr>
    @if($shippingAmount)
    <tr>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ $t('shipping') }}</td>
        <td style="padding: 12px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #1c1c1c; text-align: right;">{{ $shippingAmount }}</td>
    </tr>
    @endif
    @endif
    <tr>
        <td style="padding: 16px 0; font-size: 14px; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.1em;">{{ $t('price') }}</td>
        <td style="padding: 16px 0; font-size: 22px; color: #1c1c1c; text-align: right;">{{ $amount }}</td>
    </tr>
</table>

{{-- Původní konfigurace pro srovnání --}}
@if(!empty($oldRows))
<div style="font-size: 11px; color: #76716C; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.15em;">
    {{ $t('old') }}
</div>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 1px solid #bcbeb1; margin: 0 0 32px 0;">
    @foreach($oldRows as $row)
    <tr>
        <td style="padding: 10px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C;">{{ $row['label'] }}</td>
        <td style="padding: 10px 0; border-bottom: 1px solid #bcbeb1; font-size: 14px; color: #76716C; text-align: right;">{{ $row['value'] }}</td>
    </tr>
    @endforeach
</table>
@endif

<div style="margin: 0 0 32px 0;">
    <a href="{{ $manageUrl }}" style="display: inline-block; background-color: #ca4136; color: #ffffff; padding: 16px 32px; text-decoration: none; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em;">{{ $t('cta') }} &rarr;</a>
</div>

<p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 0;">
    {{ $t('not_you', ['email' => $contactEmail]) }}
</p>
@endsection
