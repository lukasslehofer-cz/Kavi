@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
            <div>
                <a href="{{ route('admin.affiliate.partners.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    &larr; {{ __('affiliate.back_to_partners') }}
                </a>
                <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $partner->name }}</h1>
                <p class="text-sm text-gray-500">
                    {{ $partner->email }}
                    @if($partner->affiliate_activated_at)
                        &middot; {{ __('affiliate.partner_since') }} {{ $partner->affiliate_activated_at->format('d.m.Y') }}
                    @endif
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.affiliate', ['view_as' => $partner->id]) }}" target="_blank"
                   class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-sm">
                    {{ __('affiliate.view_as_partner') }}
                </a>
                <a href="{{ route('admin.affiliate.rewards.index', ['partner' => $partner->id]) }}"
                   class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-sm">
                    {{ __('affiliate.rewards_title') }}
                </a>
            </div>
        </div>

        <!-- Statistiky -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs text-gray-500 uppercase tracking-wide">{{ __('affiliate.total_earned') }}</div>
                <div class="text-2xl font-bold text-gray-900 mt-1">
                    {{ \App\Helpers\CurrencyHelper::formatByCurrency($statistics['total_earned'], $statistics['payout_currency']) }}
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs text-gray-500 uppercase tracking-wide">{{ __('affiliate.payable') }}</div>
                <div class="text-2xl font-bold text-yellow-600 mt-1">
                    {{ \App\Helpers\CurrencyHelper::formatByCurrency($statistics['payable_amount'], $statistics['payout_currency']) }}
                </div>
                <div class="text-xs text-gray-500 mt-1">{{ $statistics['pending_count'] + $statistics['approved_count'] }} {{ __('affiliate.rewards') }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs text-gray-500 uppercase tracking-wide">{{ __('affiliate.paid') }}</div>
                <div class="text-2xl font-bold text-green-600 mt-1">
                    {{ \App\Helpers\CurrencyHelper::formatByCurrency($statistics['paid_amount'], $statistics['payout_currency']) }}
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs text-gray-500 uppercase tracking-wide">{{ __('affiliate.clicks') }}</div>
                <div class="text-2xl font-bold text-gray-900 mt-1">{{ $statistics['total_clicks'] }}</div>
                <div class="text-xs text-gray-500 mt-1">
                    {{ $statistics['total_conversions'] }} {{ __('affiliate.conversions') }}@if($statistics['conversion_rate'] !== null) ({{ $statistics['conversion_rate'] }} %)@endif
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-xs text-gray-500 uppercase tracking-wide">{{ __('affiliate.active_subscriptions') }}</div>
                <div class="text-2xl font-bold text-gray-900 mt-1">{{ $statistics['active_subscriptions_count'] }}</div>
                <div class="text-xs text-gray-500 mt-1">
                    {{ __('affiliate.estimated_income') }}
                    {{ \App\Helpers\CurrencyHelper::formatByCurrency($statistics['estimated_monthly_income'], $statistics['payout_currency']) }}
                </div>
            </div>
        </div>

        <!-- Vývoj po měsících -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900">{{ __('affiliate.monthly_development') }}</h2>
            <p class="text-sm text-gray-500 mb-4">{{ __('affiliate.monthly_development_subtitle') }}</p>
            @include('partials.affiliate.monthly-chart', ['currency' => $statistics['payout_currency']])
        </div>

        <!-- Nastavení partnera -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('affiliate.partner_settings') }}</h2>
            <form method="POST" action="{{ route('admin.affiliate.partners.settings', $partner) }}" class="flex flex-wrap items-end gap-4">
                @csrf
                <div>
                    <label for="affiliate_payout_threshold" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('affiliate.payout_threshold') }} ({{ $statistics['payout_currency'] }})
                    </label>
                    <input type="number" step="0.01" min="0"
                           name="affiliate_payout_threshold" id="affiliate_payout_threshold"
                           value="{{ old('affiliate_payout_threshold', $partner->affiliate_payout_threshold) }}"
                           placeholder="{{ $statistics['payout_threshold'] }}"
                           class="rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    <p class="text-xs text-gray-500 mt-1 max-w-md">{{ __('affiliate.payout_threshold_help') }}</p>
                </div>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                    {{ __('affiliate.save') }}
                </button>

                @if(! $statistics['payout_threshold_enabled'])
                    <span class="text-sm text-gray-500 pb-2">{{ __('affiliate.payout_threshold_disabled') }}</span>
                @elseif($statistics['threshold_reached'])
                    <span class="text-sm text-green-700 pb-2">
                        {{ __('affiliate.payout_progress_text', [
                            'amount' => \App\Helpers\CurrencyHelper::formatByCurrency($statistics['payable_amount'], $statistics['payout_currency']),
                            'threshold' => \App\Helpers\CurrencyHelper::formatByCurrency($statistics['payout_threshold'], $statistics['payout_currency']),
                        ]) }}
                    </span>
                @endif
            </form>

            @if($statistics['approved_count'] > 0)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <form method="POST" action="{{ route('admin.affiliate.partners.payout-approved', $partner) }}"
                      onsubmit="return confirm('{{ __('affiliate.mark_all_approved_paid_confirm') }}')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        {{ __('affiliate.mark_all_approved_paid') }}
                        ({{ \App\Helpers\CurrencyHelper::formatByCurrency($statistics['approved_amount'], $statistics['payout_currency']) }})
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Předplatná -->
        <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
            <div class="p-6 pb-4">
                <h2 class="text-lg font-bold text-gray-900">{{ __('affiliate.your_subscriptions') }}</h2>
                <p class="text-sm text-gray-500">{{ __('affiliate.your_subscriptions_subtitle') }}</p>
            </div>
            @include('partials.affiliate.subscriptions-table')
        </div>

        <!-- Výkon kódů -->
        <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
            <div class="p-6 pb-4">
                <h2 class="text-lg font-bold text-gray-900">{{ __('affiliate.code_performance') }}</h2>
                <p class="text-sm text-gray-500">{{ __('affiliate.code_performance_subtitle') }}</p>
            </div>
            @include('partials.affiliate.code-performance')
        </div>

        <!-- Odměny -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 pb-4 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-gray-900">{{ __('affiliate.rewards_title') }}</h2>
                <div class="flex gap-2 text-sm">
                    @foreach(['' => __('affiliate.all'), 'pending' => __('affiliate.status_pending'), 'approved' => __('affiliate.status_approved'), 'paid' => __('affiliate.status_paid'), 'cancelled' => __('affiliate.status_cancelled')] as $value => $label)
                        <a href="{{ route('admin.affiliate.partners.show', array_filter(['user' => $partner->id, 'status' => $value])) }}"
                           class="px-3 py-1 rounded-full {{ (string) $statusFilter === (string) $value ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if($rewards->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.date') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.type') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.code') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.amount') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($rewards as $reward)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $reward->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                @if($reward->reward_type === 'subscription')
                                    {{ __('affiliate.subscription') }}
                                    @if($reward->subscription)
                                        <span class="text-gray-500">{{ $reward->subscription->subscription_number }}</span>
                                    @endif
                                    <span class="text-xs text-gray-500">({{ __('affiliate.payment_number') }} {{ $reward->subscription_payment_number }})</span>
                                @else
                                    {{ __('affiliate.order') }}
                                @endif
                                @if($reward->getTierLabel())
                                    <div class="text-xs {{ $reward->reward_tier === 'followup' ? 'text-blue-600' : 'text-green-600' }}">{{ $reward->getTierLabel() }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $reward->coupon?->code }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-right">{{ $reward->getFormattedAmount() }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $badge = match($reward->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-blue-100 text-blue-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $badge }}">{{ $reward->getStatusLabel() }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $rewards->links() }}
            </div>
            @else
            <p class="text-sm text-gray-500 p-8 text-center">{{ __('affiliate.no_rewards_admin') }}</p>
            @endif
        </div>

    </div>
</div>
@endsection
