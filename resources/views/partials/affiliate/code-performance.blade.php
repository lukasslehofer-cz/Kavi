{{--
    Výkon jednotlivých affiliate kódů.

    Očekává: $codePerformance (z AffiliateService::getPartnerCodePerformance)
--}}
@if($codePerformance->isNotEmpty())
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.code') }}</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.clicks') }}</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.conversions') }}</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.conversion_rate') }}</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('affiliate.earned') }}</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @foreach($codePerformance as $row)
            <tr>
                <td class="px-4 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $row->code }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $row->name }}
                        @unless($row->is_active)
                            <span class="ml-1 text-gray-400">({{ __('affiliate.subscription_ended') }})</span>
                        @endunless
                    </div>
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $row->clicks }}</td>
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    {{ $row->conversions }}
                    <span class="text-xs text-gray-500">({{ $row->subscription_conversions }} / {{ $row->order_conversions }})</span>
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $row->conversion_rate !== null ? $row->conversion_rate . ' %' : '—' }}</td>
                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                    {{ \App\Helpers\CurrencyHelper::formatAmount($row->earned) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<p class="text-sm text-gray-500 p-8 text-center">{{ __('affiliate.no_codes') }}</p>
@endif
