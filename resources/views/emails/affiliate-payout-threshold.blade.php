@extends('emails.layouts.kavi')

@section('title', __('emails.affiliate_payout_threshold.title', [], $locale))
@section('subtitle', __('emails.affiliate_payout_threshold.subtitle', [], $locale))

@section('content')
    <!-- Částka k výplatě -->
    <div style="margin: 32px 0; padding: 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
        <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
            {{ __('emails.affiliate_payout_threshold.amount_label', [], $locale) }}
        </div>
        <p style="font-size: 32px; color: #4a6741; margin: 0 0 8px 0; letter-spacing: -0.02em;">
            {{ \App\Helpers\CurrencyHelper::formatByCurrency($amount, $currency) }}
        </p>
        <p style="font-size: 14px; color: #4a6741; margin: 0;">
            {{ __('emails.affiliate_payout_threshold.threshold_note', ['threshold' => \App\Helpers\CurrencyHelper::formatByCurrency($threshold, $currency)], $locale) }}
        </p>
    </div>

    <p style="font-size: 15px; color: #4a4a4a; line-height: 1.7; margin: 24px 0;">
        {{ __('emails.affiliate_payout_threshold.intro', [], $locale) }}
    </p>

    <!-- Jak si peníze vybrat -->
    <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
        <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
            {{ __('emails.affiliate_payout_threshold.how_to', [], $locale) }}
        </div>
        <p style="font-size: 15px; color: #1c1c1c; line-height: 1.8; margin: 4px 0;">
            <span style="color: #CA4136;">01</span> {{ __('emails.affiliate_payout_threshold.step_1', [], $locale) }}<br>
            <span style="color: #CA4136;">02</span> {{ __('emails.affiliate_payout_threshold.step_2', ['email' => $contactEmail], $locale) }}<br>
            <span style="color: #CA4136;">03</span> {{ __('emails.affiliate_payout_threshold.step_3', [], $locale) }}
        </p>
    </div>

    <p style="font-size: 14px; color: #5a5a5a; line-height: 1.7; margin: 24px 0;">
        {{ __('emails.affiliate_payout_threshold.keep_earning', [], $locale) }}
    </p>

    <!-- CTA Button -->
    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ $affiliateUrl }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
            {{ __('emails.affiliate_common.open_section', [], $locale) }} →
        </a>
    </div>
@endsection
