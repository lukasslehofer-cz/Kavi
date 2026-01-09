@extends('layouts.dashboard')

@section('title', 'Affiliate Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ __('affiliate.dashboard_title') }}</h1>
        <p class="text-base text-gray-600 font-light">{{ __('affiliate.dashboard_subtitle') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::formatAmount($statistics['total_earned']) }}</p>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium mb-1">{{ __('affiliate.pending') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::formatAmount($statistics['pending_amount']) }}</p>
                    <p class="text-xs text-gray-500">{{ $statistics['pending_count'] }} {{ __('affiliate.rewards') }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::formatAmount($statistics['paid_amount']) }}</p>
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
                    <p class="text-sm text-gray-600 font-medium mb-1">{{ __('affiliate.clicks') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_clicks'] }}</p>
                    <p class="text-xs text-gray-500">{{ $statistics['total_conversions'] }} {{ __('affiliate.conversions') }}</p>
                </div>
            </div>
        </div>
    </div>

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
                                {{ \App\Helpers\CurrencyHelper::formatAmount($coupon->getAffiliateSubscriptionRewardValue()) }}
                                {{ __('affiliate.per_payment') }}
                                @if($coupon->affiliate_reward_subscription_months)
                                    ({{ $coupon->affiliate_reward_subscription_months }}x)
                                @endif
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
                                @if($coupon->affiliate_reward_order_min_value)
                                    <span class="text-xs text-gray-600">(min. {{ \App\Helpers\CurrencyHelper::formatAmount($coupon->affiliate_reward_order_min_value) }})</span>
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
                                onclick="copyToClipboard('{{ $link->getFullUrl() }}')" 
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
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900">{{ __('affiliate.recent_rewards') }}</h2>
                <div class="flex gap-2">
                    <a href="{{ localizedRoute('dashboard.affiliate') }}" class="px-3 py-1.5 text-sm {{ !$statusFilter ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600' }} rounded-lg">
                        {{ __('affiliate.all') }}
                    </a>
                    <a href="{{ localizedRoute('dashboard.affiliate') }}?status=pending" class="px-3 py-1.5 text-sm {{ $statusFilter === 'pending' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600' }} rounded-lg">
                        {{ __('affiliate.pending') }}
                    </a>
                    <a href="{{ localizedRoute('dashboard.affiliate') }}?status=paid" class="px-3 py-1.5 text-sm {{ $statusFilter === 'paid' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600' }} rounded-lg">
                        {{ __('affiliate.paid') }}
                    </a>
                </div>
            </div>
        </div>

        @if($rewards->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            {{ __('affiliate.date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            {{ __('affiliate.type') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            {{ __('affiliate.code') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            {{ __('affiliate.amount') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            {{ __('affiliate.status') }}
                        </th>
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
                                @if($reward->subscription_payment_number)
                                    ({{ $reward->subscription_payment_number }}.)
                                @endif
                            @else
                                {{ __('affiliate.order') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $reward->coupon->code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $reward->getFormattedAmount() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($reward->status === 'pending')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    {{ __('affiliate.status_pending') }}
                                </span>
                            @elseif($reward->status === 'approved')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ __('affiliate.status_approved') }}
                                </span>
                            @elseif($reward->status === 'paid')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800">
                                    {{ __('affiliate.status_paid') }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-gray-100 text-gray-800">
                                    {{ $reward->status }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('{{ __('affiliate.link_copied') }}');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>
@endsection
