{{--
    Tabulka předplatných, která partnerovi vydělávají.

    Očekává: $referredSubscriptions (z AffiliateService::getPartnerSubscriptions)
    Volitelně: $customers – mapa subscription_id => jméno zákazníka.
               Předává se JEN v administraci; partner osobní údaje nevidí.
--}}
@php
    $customers = $customers ?? collect();
@endphp

@if($referredSubscriptions->isNotEmpty())
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.subscription_number') }}</th>
                @if($customers->isNotEmpty())
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.customer') }}</th>
                @endif
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.since') }}</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.rewarded_shipments') }}</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.earned') }}</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.current_rate') }}</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.next_reward') }}</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.status') }}</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @foreach($referredSubscriptions as $item)
            <tr>
                <td class="px-4 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $item->subscription_number }}</div>
                    <div class="text-xs text-gray-500">{{ $item->coupon_code }}</div>
                </td>
                @if($customers->isNotEmpty())
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $customers->get($item->subscription_id) ?? '—' }}
                </td>
                @endif
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $item->started_at?->format('d.m.Y') }}
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    {{ $item->rewarded_payments }}
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                    {{ \App\Helpers\CurrencyHelper::formatByCurrency($item->total_earned, $item->currency, 0) }}
                </td>
                <td class="px-4 py-4 whitespace-nowrap">
                    @if($item->current_tier)
                        <div class="text-sm text-gray-900">
                            {{ \App\Helpers\CurrencyHelper::formatByCurrency($item->current_rate, $item->currency, 0) }}
                        </div>
                        <div class="text-xs {{ $item->current_tier === 'followup' ? 'text-blue-600' : 'text-green-600' }}">
                            @if($item->current_tier === 'initial' && $item->remaining_initial)
                                {{ __('affiliate.remaining_at_initial', ['count' => $item->remaining_initial]) }}
                            @else
                                {{ $item->current_tier === 'followup' ? __('affiliate.tier_followup') : __('affiliate.tier_initial') }}
                            @endif
                        </div>
                    @else
                        <span class="text-sm text-gray-400">{{ __('affiliate.no_further_reward') }}</span>
                    @endif
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $item->next_reward_date?->format('d.m.Y') ?? '—' }}
                </td>
                <td class="px-4 py-4 whitespace-nowrap">
                    @if($item->is_active)
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ __('affiliate.subscription_active') }}</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">{{ __('affiliate.subscription_ended') }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="p-8 text-center">
    <p class="text-gray-900 font-medium">{{ __('affiliate.no_subscriptions') }}</p>
    <p class="text-sm text-gray-500 mt-1">{{ __('affiliate.no_subscriptions_text') }}</p>
</div>
@endif
