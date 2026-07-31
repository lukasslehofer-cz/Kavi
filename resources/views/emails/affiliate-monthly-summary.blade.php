@extends('emails.layouts.kavi')

@section('title', __('emails.affiliate_monthly_summary.title', [], $locale))
@section('subtitle', $monthLabel)

@section('content')
    @php
        $fmt = fn ($amount) => \App\Helpers\CurrencyHelper::formatByCurrency($amount, $summary['currency'], 0);
    @endphp

    <!-- Výdělek za měsíc -->
    <div style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
        <div style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
            {{ __('emails.affiliate_monthly_summary.earned_label', [], $locale) }}
        </div>
        <div style="font-size: 32px; font-weight: 400; color: #1c1c1c; letter-spacing: -0.02em;">
            {{ $fmt($summary['earned']) }}
        </div>
    </div>

    <!-- Čísla za měsíc -->
    <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
        <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
            {{ __('emails.affiliate_monthly_summary.this_month', [], $locale) }}
        </div>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ __('emails.affiliate_monthly_summary.new_customers', [], $locale) }}:</td>
                <td style="padding: 8px 0; font-size: 16px; color: #1c1c1c; text-align: right;">{{ $summary['new_conversions'] }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ __('emails.affiliate_monthly_summary.rewarded_shipments', [], $locale) }}:</td>
                <td style="padding: 8px 0; font-size: 16px; color: #1c1c1c; text-align: right;">{{ $summary['rewards_count'] }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ __('emails.affiliate_monthly_summary.clicks', [], $locale) }}:</td>
                <td style="padding: 8px 0; font-size: 16px; color: #1c1c1c; text-align: right;">{{ $summary['clicks'] }}</td>
            </tr>
        </table>
    </div>

    <!-- Aktivní předplatná -->
    @if($summary['active_subscriptions'] > 0)
    <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
        <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
            {{ __('emails.affiliate_monthly_summary.active_label', [], $locale) }}
        </div>
        <p style="font-size: 15px; color: #4a6741; margin: 0;">
            {{ trans_choice('emails.affiliate_monthly_summary.active_text', $summary['active_subscriptions'], ['count' => $summary['active_subscriptions'], 'amount' => $fmt($summary['estimated_monthly_income'])], $locale) }}
        </p>
    </div>
    @endif

    <!-- Zůstatek -->
    <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
        <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
            {{ __('emails.affiliate_monthly_summary.balance', [], $locale) }}
        </div>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ __('emails.affiliate_monthly_summary.payable', [], $locale) }}:</td>
                <td style="padding: 8px 0; font-size: 18px; color: #1c1c1c; text-align: right;">{{ $fmt($summary['payable_amount']) }}</td>
            </tr>
        </table>
        @if($summary['threshold_enabled'] ?? true)
        <p style="font-size: 14px; color: #5a5a5a; line-height: 1.7; margin: 16px 0 0 0;">
            @if($summary['threshold_reached'])
                {{ __('emails.affiliate_monthly_summary.threshold_reached', [], $locale) }}
            @else
                {{ __('emails.affiliate_monthly_summary.threshold_remaining', ['amount' => $fmt(max(0, $summary['threshold'] - $summary['payable_amount']))], $locale) }}
            @endif
        </p>
        @endif
    </div>

    <!-- CTA Button -->
    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ $affiliateUrl }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
            {{ __('emails.affiliate_common.open_section', [], $locale) }} →
        </a>
    </div>
@endsection
