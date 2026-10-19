{{--
    Řádky tarifů M / L / XL s cenou za gram.

    tiers: výstup App\Helpers\BoxPricing::tiers()
    href: callable(int $bags): string, odkaz tlačítka (výchozí konfigurátor s ?plan=)
--}}
@props([
    'tiers' => [],
    'title' => null,
    'id' => null,
    'href' => null,
])

@php
    $title = $title ?? ($currentLocale === 'en' ? 'Choose your ideal coffee box' : 'Vyberte si ideální kávový box');
    $href = $href ?? fn (int $bags) => localizedRoute('subscriptions.index', ['plan' => $bags]);
    $currency = \App\Helpers\CurrencyHelper::code();
    $leadShipping = __('components.tiers.lead_shipping');
@endphp

<section @if($id) id="{{ $id }}" @endif aria-label="{{ $title }}" class="relative overflow-hidden" style="background-color: #e5e6df;">

    <div class="pt-20 sm:pt-24 lg:pt-28 pb-12 sm:pb-16 border-b border-dark-800/10">
        <div class="max-w-screen-xl mx-auto px-4 md:px-8 text-center">
            <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-4 tracking-tight uppercase leading-tight sm:leading-[0.95]">{{ $title }}</h2>
            <p class="text-lg text-warm-500 font-light">
                {{ __('components.tiers.lead') }}
                @if($leadShipping)
                <br><span class="text-dark-800">{{ $leadShipping }}</span>
                @endif
            </p>
        </div>
    </div>

    @foreach($tiers as $tier)
    <div class="relative {{ $loop->last ? '' : 'border-b border-dark-800/10' }}">
        @if($tier['popular'])
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500" aria-hidden="true"></div>
        @endif
        <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-12 sm:py-16">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="lg:w-1/3">
                    @if($tier['popular'])
                    <span class="inline-block bg-primary-500 text-white text-xs font-medium uppercase tracking-widest px-3 py-1 mb-4">{{ __('components.tiers.popular') }}</span>
                    @endif
                    <div class="flex items-baseline gap-2">
                        <span class="font-display text-7xl sm:text-8xl lg:text-9xl font-normal text-dark-800 leading-none tracking-tight">{{ $tier['code'] }}</span>
                        <span class="font-display text-3xl sm:text-4xl lg:text-5xl font-normal text-dark-800 uppercase">Box</span>
                    </div>
                    <p class="text-warm-600 uppercase tracking-widest text-sm mt-2">{{ __('components.tiers.bags', ['grams' => $tier['grams'], 'count' => $tier['bags']]) }}</p>
                </div>

                <div class="lg:w-1/3 space-y-2">
                    <p class="text-dark-700 font-light">{{ __('components.tiers.kinds.' . $tier['bags']) }}</p>
                    <p class="text-dark-700 font-light">{{ __('components.tiers.shipping_unit', ['unit' => $tier['unit_price']]) }}</p>
                    <p class="text-dark-700 font-light">{{ __('components.tiers.cancel') }}</p>
                </div>

                <div class="lg:w-1/3 lg:text-right">
                    <div class="flex items-baseline gap-1 lg:justify-end mb-4">
                        <span class="font-display text-3xl font-normal text-dark-800">{{ \App\Helpers\CurrencyHelper::formatByCurrency($tier['price'], $currency, 0) }}</span>
                        <span class="text-warm-500 font-light">{{ __('components.tiers.per_box') }}</span>
                    </div>
                    <a href="{{ $href($tier['bags']) }}"
                       class="inline-block font-medium px-8 py-3 transition-all duration-200 {{ $tier['popular'] ? 'bg-primary-500 hover:bg-primary-600 text-white' : 'border border-dark-800 hover:bg-dark-800 hover:text-white text-dark-800' }}">
                        {{ __('components.tiers.select', ['box' => $tier['code']]) }} &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="py-8 border-t border-dark-800/10">
        <div class="max-w-screen-xl mx-auto px-4 md:px-8">
            <p class="text-center text-warm-500 font-light">
                {{ __('components.tiers.note', ['surcharge' => \App\Helpers\CurrencyHelper::formatByCurrency(\App\Helpers\BoxPricing::oneTimeSurcharge($currency), $currency, 0)]) }}
            </p>
        </div>
    </div>
</section>
