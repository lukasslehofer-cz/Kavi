@extends('emails.layouts.kavi')

@section('title', __('emails.affiliate_code_used.title', [], $locale))
@section('subtitle', __('emails.affiliate_code_used.subtitle', [], $locale))

@section('content')
    @php
        $isSubscription = $reward->reward_type === 'subscription';
        $coupon = $reward->coupon;
        $rewardScheme = $isSubscription && $coupon
            ? $coupon->getAffiliateSubscriptionRewardDescription($reward->currency, $locale)
            : null;
    @endphp

    <!-- Použitý kód -->
    <div style="border-top: 2px solid #CA4136; padding: 24px 0; margin: 32px 0;">
        <div style="font-size: 11px; color: #76716C; font-weight: 400; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.15em;">
            {{ __('emails.affiliate_common.code', [], $locale) }}
        </div>
        <div style="font-size: 28px; font-weight: 400; color: #1c1c1c; letter-spacing: -0.02em;">
            {{ $coupon->code ?? '' }}
        </div>
    </div>

    <!-- Odměna -->
    <div style="margin: 32px 0; padding: 20px 24px; border-left: 3px solid #4a6741; background-color: #d5d7ca;">
        <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
            {{ __('emails.affiliate_code_used.reward_label', [], $locale) }}
        </div>
        <p style="font-size: 24px; color: #4a6741; margin: 0 0 8px 0;">
            {{ \App\Helpers\CurrencyHelper::formatByCurrency($reward->reward_amount, $reward->currency) }}
        </p>
        <p style="font-size: 14px; color: #4a6741; margin: 0;">
            {{ __('emails.affiliate_code_used.reward_pending', [], $locale) }}
        </p>
    </div>

    <!-- Detaily konverze -->
    <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
        <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
            {{ __('emails.affiliate_code_used.details', [], $locale) }}
        </div>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ __('emails.affiliate_common.type', [], $locale) }}:</td>
                <td style="padding: 8px 0; font-size: 14px; color: #1c1c1c; text-align: right;">
                    {{ $isSubscription ? __('emails.affiliate_common.type_subscription', [], $locale) : __('emails.affiliate_common.type_order', [], $locale) }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-size: 14px; color: #76716C;">{{ __('emails.affiliate_common.date', [], $locale) }}:</td>
                <td style="padding: 8px 0; font-size: 14px; color: #1c1c1c; text-align: right;">
                    {{ $reward->created_at->format('j. n. Y') }}
                </td>
            </tr>
        </table>
    </div>

    @if($rewardScheme)
    <!-- Jak bude odměna pokračovat -->
    <div style="margin: 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
        <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
            {{ __('emails.affiliate_code_used.whats_next', [], $locale) }}
        </div>
        <p style="font-size: 15px; color: #1c1c1c; line-height: 1.8; margin: 4px 0;">
            <span style="color: #CA4136;">→</span> {{ $rewardScheme }}<br>
            <span style="color: #CA4136;">→</span> {{ __('emails.affiliate_code_used.next_monthly', [], $locale) }}
        </p>
    </div>
    @endif

    <!-- CTA Button -->
    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ $affiliateUrl }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
            {{ __('emails.affiliate_common.open_section', [], $locale) }} →
        </a>
    </div>
@endsection
