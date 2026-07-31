{{--
    Sloupcový graf výdělku po měsících.

    V projektu není žádná grafová knihovna, sloupce jsou proto ruční divy –
    stejný princip jako pruhy v admin/review-stats/index.blade.php.

    Očekává: $monthlyBreakdown (z AffiliateService::getMonthlyBreakdown)
    Volitelně: $currency
--}}
@php
    $currency = $currency ?? \App\Helpers\CurrencyHelper::code();
    $maxEarned = collect($monthlyBreakdown)->max('earned') ?: 0;
    $hasData = collect($monthlyBreakdown)->sum('earned') > 0 || collect($monthlyBreakdown)->sum('clicks') > 0;
@endphp

@if($hasData)
    <div class="flex items-end gap-1 sm:gap-2 h-40">
        @foreach($monthlyBreakdown as $month)
            @php
                $height = $maxEarned > 0 ? max(2, round($month['earned'] / $maxEarned * 100)) : 2;
            @endphp
            <div class="flex-1 flex flex-col items-center justify-end h-full group">
                <div class="text-[10px] text-gray-500 mb-1 opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                    {{ \App\Helpers\CurrencyHelper::formatByCurrency($month['earned'], $currency, 0) }}
                </div>
                <div class="w-full bg-amber-400 hover:bg-amber-500 transition rounded-t"
                     style="height: {{ $height }}%"
                     title="{{ $month['month'] }}: {{ \App\Helpers\CurrencyHelper::formatByCurrency($month['earned'], $currency, 0) }} / {{ $month['clicks'] }} {{ __('affiliate.clicks') }}"></div>
            </div>
        @endforeach
    </div>
    <div class="flex gap-1 sm:gap-2 mt-2">
        @foreach($monthlyBreakdown as $month)
            <div class="flex-1 text-center text-[10px] text-gray-500">{{ $month['label'] }}</div>
        @endforeach
    </div>
@else
    <p class="text-sm text-gray-500 py-8 text-center">{{ __('affiliate.no_monthly_data') }}</p>
@endif
