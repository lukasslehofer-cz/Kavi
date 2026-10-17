{{--
    Promo karta měsíce: čtvercová promo fotka, červený pruh s měsícem a řádky pražíren.

    lineup: App\Services\MonthlyLineup
    fallbackImage: fotka, když měsíc v adminu promo fotku nemá
    more: zobrazit spodní řádek s popisem a odkazem "Více info" (konfigurátor)
--}}
@props([
    'lineup',
    'fallbackImage' => 'images/kavi-november-25.jpg',
    'more' => false,
])

@php
    $promoImage = $lineup->promoImage() ?? $fallbackImage;
    // Starší nahrané fotky leží ve storage, novější přímo v public/images/promo-images
    $imageSrc = str_starts_with($promoImage, 'promo-images/') ? asset('storage/' . $promoImage) : asset($promoImage);
    $roasteries = $lineup->displayRoasteries(3);
@endphp

<div {{ $attributes }}>
    <div class="relative">
        <div class="relative aspect-square overflow-hidden" style="background-color: #e5e6df;">
            <img src="{{ $imageSrc }}" alt="{{ __('components.promo_card.alt', ['month' => $lineup->monthName('adjective')]) }}" class="block w-full h-full object-cover">
        </div>
        <div class="absolute left-0 top-0 w-10 sm:w-12 bg-primary-500 flex items-center justify-center z-10" style="height: 40%;">
            <span class="font-display text-dark-800 text-xs sm:text-sm uppercase tracking-[0.2em] -rotate-90 whitespace-nowrap">{{ $lineup->monthWithYear() }}</span>
        </div>
    </div>

    <div class="bg-warm-200">
        @foreach($roasteries as $roastery)
        <div class="flex items-center justify-between gap-4 py-3 px-6 {{ ! $loop->last || $more ? 'border-b border-warm-300' : '' }}">
            <span class="font-display text-lg sm:text-xl md:text-2xl font-normal text-dark-800 uppercase tracking-tight">{{ $roastery->getName() }}</span>
            <span class="font-display text-lg sm:text-xl md:text-2xl font-normal text-warm-500 uppercase tracking-tight text-right">{{ $roastery->getCountry() }}</span>
        </div>
        @endforeach

        @if($more)
        <div class="flex items-end justify-between gap-4 px-6 py-5">
            <p class="text-xs text-warm-600 max-w-[60%] leading-relaxed font-light">{{ __('components.promo_card.description') }}</p>
            <a href="{{ localizedRoute('monthly-feature.index') }}" class="text-sm text-dark-800 hover:text-primary-500 transition-colors whitespace-nowrap flex items-center gap-1">
                {{ __('components.promo_card.more') }} <span class="text-lg" aria-hidden="true">&rarr;</span>
            </a>
        </div>
        @endif
    </div>
</div>
