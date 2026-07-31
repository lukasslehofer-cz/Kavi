@extends('layouts.dashboard')

@section('title', 'Affiliate Dashboard')

@section('content')
@php
    $currency = $statistics['payout_currency'];
    $money = fn ($amount) => \App\Helpers\CurrencyHelper::formatByCurrency($amount, $currency, 0);
    $viewAsParam = request()->has('view_as') ? '&view_as=' . request()->get('view_as') : '';
@endphp

<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ __('affiliate.dashboard_title') }}</h1>
        <p class="text-base text-gray-600 font-light">{{ __('affiliate.dashboard_subtitle') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Earned -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">{{ __('affiliate.total_earned') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $money($statistics['total_earned']) }}</p>
                    <p class="text-xs text-gray-500">{{ __('affiliate.this_month') }}: {{ $money($statistics['this_month_earned']) }}</p>
                </div>
            </div>
        </div>

        <!-- Payable -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">{{ __('affiliate.payable') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $money($statistics['payable_amount']) }}</p>
                    <p class="text-xs text-gray-500">{{ $statistics['pending_count'] + $statistics['approved_count'] }} {{ __('affiliate.rewards') }}</p>
                </div>
            </div>
        </div>

        <!-- Paid -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">{{ __('affiliate.paid') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $money($statistics['paid_amount']) }}</p>
                    <p class="text-xs text-gray-500">{{ $statistics['paid_count'] }} {{ __('affiliate.rewards') }}</p>
                </div>
            </div>
        </div>

        <!-- Clicks & Conversions -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">{{ __('affiliate.conversions') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_conversions'] }}</p>
                    <p class="text-xs text-gray-500">
                        {{ __('affiliate.from_clicks', ['count' => $statistics['total_clicks']]) }}@if($statistics['conversion_rate'] !== null) &middot; {{ $statistics['conversion_rate'] }} %@endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Hranice nastavená na 0 znamená "neřešit" – celý blok se pak nezobrazuje --}}
    @if($statistics['payout_threshold_enabled'])
    <!-- Postup k fakturační hranici -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <div class="flex flex-wrap justify-between items-baseline gap-2 mb-3">
            <h2 class="text-lg font-bold text-gray-900">{{ __('affiliate.payout_progress') }}</h2>
            <span class="text-sm text-gray-600">
                {{ __('affiliate.payout_progress_text', [
                    'amount' => $money($statistics['payable_amount']),
                    'threshold' => $money($statistics['payout_threshold']),
                ]) }}
            </span>
        </div>

        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
            <div class="{{ $statistics['threshold_reached'] ? 'bg-green-500' : 'bg-amber-400' }} h-3 rounded-full transition-all"
                 style="width: {{ $statistics['threshold_progress'] }}%"></div>
        </div>

        <p class="text-sm {{ $statistics['threshold_reached'] ? 'text-green-700' : 'text-gray-600' }} mt-3">
            @if($statistics['threshold_reached'])
                {{ __('affiliate.payout_reached', ['email' => \App\Services\EmailService::getContactEmail(app()->getLocale())]) }}
            @else
                {{ __('affiliate.payout_remaining', ['amount' => $money(max(0, $statistics['payout_threshold'] - $statistics['payable_amount']))]) }}
            @endif
        </p>
    </div>
    @endif

    <!-- Vývoj po měsících -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <h2 class="text-lg font-bold text-gray-900">{{ __('affiliate.monthly_development') }}</h2>
        <p class="text-sm text-gray-600 font-light mb-4">{{ __('affiliate.monthly_development_subtitle') }}</p>
        @include('partials.affiliate.monthly-chart')
    </div>

    <!-- Předplatná, která vydělávají -->
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">{{ __('affiliate.your_subscriptions') }}</h2>
            <p class="text-sm text-gray-600 font-light">{{ __('affiliate.your_subscriptions_subtitle') }}</p>
        </div>
        @include('partials.affiliate.subscriptions-table')
    </div>

    <!-- Výkon kódů -->
    @if($codePerformance->isNotEmpty())
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">{{ __('affiliate.code_performance') }}</h2>
            <p class="text-sm text-gray-600 font-light">{{ __('affiliate.code_performance_subtitle') }}</p>
        </div>
        @include('partials.affiliate.code-performance')
    </div>
    @endif

    <!-- Affiliate Codes & Links -->
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">{{ __('affiliate.your_codes') }}</h2>
        </div>

        @if($coupons->count() > 0)
        <div class="p-6 space-y-4">
            @foreach($coupons as $coupon)
            <div class="border border-gray-200 rounded-xl p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $coupon->code }}</h3>
                        @if($coupon->name)
                        <p class="text-sm text-gray-600">{{ $coupon->name }}</p>
                        @endif
                    </div>
                    @if($coupon->is_active)
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ __('affiliate.active') }}
                    </span>
                    @endif
                </div>

                <!-- Discount Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">{{ __('affiliate.customer_discount') }}</p>

                        @if($coupon->hasSubscriptionDiscount())
                        <div class="mb-2">
                            <p class="text-xs font-medium text-purple-700 mb-0.5">{{ __('affiliate.subscription') }}:</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $coupon->getSubscriptionDiscountDescription() }}
                            </p>
                        </div>
                        @endif

                        @if($coupon->hasOrderDiscount())
                        <div>
                            <p class="text-xs font-medium text-blue-700 mb-0.5">{{ __('affiliate.order') }}:</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $coupon->getOrderDiscountDescription() }}
                            </p>
                        </div>
                        @endif

                        @if(!$coupon->hasSubscriptionDiscount() && !$coupon->hasOrderDiscount())
                        <p class="text-sm text-gray-500">{{ __('affiliate.no_discount') }}</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">{{ __('affiliate.your_reward') }}</p>

                        @if($coupon->hasAffiliateSubscriptionReward())
                        <div class="mb-2">
                            <p class="text-xs font-medium text-purple-700 mb-0.5">{{ __('affiliate.subscription') }}:</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $coupon->getAffiliateSubscriptionRewardDescription() }}
                            </p>
                        </div>
                        @endif

                        @if($coupon->hasAffiliateOrderReward())
                        <div>
                            <p class="text-xs font-medium text-blue-700 mb-0.5">{{ __('affiliate.order') }}:</p>
                            <p class="text-sm font-medium text-gray-900">
                                @if($coupon->affiliate_reward_order_type === 'percentage')
                                    {{ $coupon->affiliate_reward_order_value }}%
                                @else
                                    {{ \App\Helpers\CurrencyHelper::formatAmount($coupon->getAffiliateOrderRewardValue()) }}
                                @endif
                                @if($coupon->getAffiliateOrderMinValue())
                                    <span class="text-xs text-gray-600">(min. {{ \App\Helpers\CurrencyHelper::formatAmount($coupon->getAffiliateOrderMinValue()) }})</span>
                                @endif
                            </p>
                        </div>
                        @endif

                        @if(!$coupon->hasAffiliateSubscriptionReward() && !$coupon->hasAffiliateOrderReward())
                        <p class="text-sm text-gray-500">{{ __('affiliate.no_rewards') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Affiliate Links -->
                @foreach($coupon->affiliateLinks as $link)
                <div class="bg-blue-50 rounded-lg p-3 mb-2">
                    <div class="flex justify-between items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-600 mb-1">{{ __('affiliate.your_link') }}</p>
                            <p class="text-sm font-mono text-gray-900 truncate">{{ $link->getFullUrl() }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-600">{{ $link->clicks_count }} {{ __('affiliate.clicks') }}</span>
                            <button
                                type="button"
                                onclick="copyToClipboard('{{ $link->getFullUrl() }}', this)"
                                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors"
                            >
                                {{ __('affiliate.copy') }}
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 px-4">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('affiliate.no_codes') }}</h3>
            <p class="text-gray-600 font-light">{{ __('affiliate.no_codes_text') }}</p>
        </div>
        @endif
    </div>

    <!-- Recent Rewards -->
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <div class="flex flex-wrap justify-between items-center gap-3">
                <h2 class="text-xl font-bold text-gray-900">{{ __('affiliate.recent_rewards') }}</h2>
                <div class="flex gap-2">
                    @foreach(['' => __('affiliate.all'), 'pending' => __('affiliate.status_pending'), 'approved' => __('affiliate.status_approved'), 'paid' => __('affiliate.status_paid')] as $value => $label)
                        @php
                            $query = $value ? '?status=' . $value . $viewAsParam : ($viewAsParam ? '?' . ltrim($viewAsParam, '&') : '');
                        @endphp
                        <a href="{{ localizedRoute('dashboard.affiliate') }}{{ $query }}"
                           class="px-3 py-1.5 text-sm {{ (string) $statusFilter === (string) $value ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600' }} rounded-lg">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        @if($rewards->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">{{ __('affiliate.date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">{{ __('affiliate.type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">{{ __('affiliate.code') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">{{ __('affiliate.amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">{{ __('affiliate.status') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($rewards as $reward)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-light">
                            {{ $reward->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($reward->reward_type === 'subscription')
                                {{ __('affiliate.subscription') }}
                                @if($reward->subscription)
                                    {{ \Illuminate\Support\Str::after($reward->subscription->subscription_number ?? '#' . $reward->subscription->id, 'KVS-') }}
                                @endif
                                @if($reward->subscription_payment_number)
                                    ({{ $reward->subscription_payment_number }}.)
                                @endif
                                @if($reward->getTierLabel())
                                    <div class="text-xs {{ $reward->reward_tier === 'followup' ? 'text-blue-600' : 'text-green-600' }}">{{ $reward->getTierLabel() }}</div>
                                @endif
                            @else
                                {{ __('affiliate.order') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $reward->coupon?->code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                            {{ $reward->getFormattedAmount() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $badge = match($reward->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-blue-100 text-blue-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full {{ $badge }}">
                                {{ $reward->getStatusLabel() }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($rewards->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $rewards->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12 px-4">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('affiliate.no_rewards') }}</h3>
            <p class="text-gray-600 font-light">{{ __('affiliate.no_rewards_text') }}</p>
        </div>
        @endif
    </div>
</div>

<script>
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(() => {
        // Nevtíravé potvrzení přímo na tlačítku místo alert()
        const original = button.textContent;
        button.textContent = @json(__('affiliate.link_copied'));
        button.classList.add('bg-green-600');
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');

        setTimeout(() => {
            button.textContent = original;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>
@endsection
