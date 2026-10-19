{{--
    Káva měsíce: hlavička + karty pražíren (ČB fotka, červené štítky) a pod nimi kávy.

    lineup: App\Services\MonthlyLineup (CoffeeOfMonthService::lineup())
    variant: 'display' = velká hlavička "Káva měsíce / říjen 2026" (homepage, landing),
             'h2'      = nadpis 48px s leadem (konfigurátor)
    Bez dat (před nastavením měsíce v adminu) se sekce nevykreslí.
--}}
@props([
    'lineup',
    'variant' => 'display',
    'title' => null,
    'lead' => null,
    'linkLabel' => null,
    'linkHref' => null,
    'id' => 'kava-mesice',
    'padding' => 'pt-20 sm:pt-24 lg:pt-28 pb-24 sm:pb-28 lg:pb-36',
])

@if(! $lineup->isEmpty())
@php
    $roasteries = $lineup->displayRoasteries(3);
    $linkHref = $linkHref ?? localizedRoute('monthly-feature.index');
@endphp

<section id="{{ $id }}" aria-label="{{ $title ?? __('components.coffee_of_month.title') }}" class="{{ $padding }} scroll-mt-20 {{ $variant === 'h2' ? 'border-t border-dark-800/10' : '' }}" style="background-color: #e5e6df;">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">

        @if($variant === 'h2')
        <div class="flex flex-wrap items-end justify-between gap-x-12 gap-y-4 mb-12 sm:mb-16">
            <div class="max-w-2xl">
                <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 tracking-tight uppercase leading-tight sm:leading-[0.95] mb-4">{{ $title }}</h2>
                @if($lead)
                <p class="text-lg sm:text-xl text-warm-500 font-light">{{ $lead }}</p>
                @endif
            </div>
            <a href="{{ $linkHref }}" class="group inline-flex items-center gap-2 text-sm text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-colors">
                <span>{{ $linkLabel }}</span>
                <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
            </a>
        </div>
        @else
        <div class="flex flex-wrap items-start justify-between gap-x-16 gap-y-6 mb-12 sm:mb-16">
            <div>
                <h2 class="font-display text-5xl sm:text-6xl lg:text-7xl font-normal text-primary-500 uppercase leading-[0.9] tracking-tight">{{ $title ?? __('components.coffee_of_month.title') }}</h2>
                <p class="font-display text-5xl sm:text-6xl lg:text-7xl font-normal text-warm-300 uppercase leading-[0.9] tracking-tight mt-2">{{ $lineup->monthWithYear() }}</p>
            </div>
            <div class="max-w-[340px] sm:pt-4 flex flex-col gap-5">
                <p class="text-xs uppercase tracking-widest leading-5 text-warm-600">{{ __('components.coffee_of_month.eyebrow') }}</p>
                <a href="{{ $linkHref }}" class="group inline-flex items-center gap-2 text-sm text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-colors">
                    <span>{{ $linkLabel ?? __('components.coffee_of_month.explore', ['month' => $lineup->monthName('nominative')]) }}</span>
                    <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-[repeat(auto-fit,minmax(min(280px,100%),1fr))] gap-x-16 gap-y-12 items-start">
            @foreach($roasteries as $roastery)
            @php $coffees = $lineup->coffeesFor($roastery); @endphp
            <div>
                <a href="{{ localizedRoute('roasteries.show', $roastery) }}" class="group relative block aspect-square overflow-hidden mb-6 bg-warm-300">
                    @if($roastery->image)
                    <img src="{{ asset($roastery->image) }}" alt="{{ $roastery->getName() }}" loading="lazy"
                         class="block w-full h-full object-cover grayscale group-hover:scale-105 transition-transform duration-500">
                    @endif
                    <div class="absolute top-0 left-0 p-3">
                        <span class="inline-block bg-primary-500 text-black uppercase font-display text-base leading-5 tracking-tight px-2 py-1">{{ $roastery->getName() }}</span><br>
                        @if($roastery->getCountry())
                        <span class="inline-block bg-primary-500 text-black uppercase text-xs tracking-widest px-2 py-0.5 mt-1">{{ $roastery->getCountry() }}</span>
                        @endif
                    </div>
                </a>

                @if($coffees->isNotEmpty())
                <div class="border-t border-warm-300">
                    @foreach($coffees as $coffee)
                    @php
                        $isDecaf = is_array($coffee->category) && in_array('decaf', $coffee->category, true);
                        $meta = array_filter([
                            $coffee->getTranslatedAttribute('origin'),
                            $isDecaf ? __('components.coffee_of_month.decaf') : $coffee->getTranslatedAttribute('processing'),
                        ]);
                    @endphp
                    <div class="flex items-center gap-4 py-3 border-b border-warm-300">
                        @if($coffee->image)
                        <img src="{{ asset($coffee->image) }}" alt="" loading="lazy" class="w-[72px] h-[72px] object-contain flex-shrink-0">
                        @else
                        <div class="w-[72px] h-[72px] flex-shrink-0" aria-hidden="true"></div>
                        @endif
                        <div class="min-w-0">
                            <div class="font-display text-lg leading-6 uppercase tracking-tight text-dark-800">{{ $coffee->getName() }}</div>
                            @if($meta)
                            <div class="text-xs uppercase tracking-widest text-warm-600">{{ implode(' · ', $meta) }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
