@extends('layouts.app')

@section('title', __('landing.europe.title'))
@section('meta_description', __('landing.europe.meta_description'))
@section('robots', 'noindex, follow')
@section('minimal_chrome', '1')
@section('minimal_cta_href', $boxUrl())

@php
    $t = fn (string $key, array $extra = []) => __('landing.europe.' . $key, $extra + $replace);
    $planUrl = fn (int $bags) => $boxUrl(['plan' => $bags]);
    $heroTitle = __('landing.europe.hero.title');
@endphp

@section('content')
<div class="overflow-hidden" style="background-color: #e5e6df;">

{{-- 1. Hero: nadpis, fakta (cena, hodnocení, uzávěrka) a promo karta měsíce --}}
<section aria-label="{{ implode(' ', $heroTitle) }}">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8 pt-16 lg:pt-24 pb-20 lg:pb-28 flex flex-wrap items-center gap-16">
        <div class="flex-[7_1_520px] min-w-0 flex flex-col gap-10">
            <h1 class="font-display uppercase text-[clamp(48px,6.7vw,96px)] leading-[0.9] tracking-tight">
                <span class="text-dark-800">{{ $heroTitle[0] }}<br>{{ $heroTitle[1] }}</span><br>
                <span class="text-primary-500">{{ $heroTitle[2] }}</span>
            </h1>

            <p class="max-w-[480px] text-lg sm:text-xl leading-[30px] font-light text-warm-500">{{ $t('hero.lead') }}</p>

            <div class="grid grid-cols-[repeat(auto-fit,minmax(min(150px,100%),1fr))] gap-6 max-w-[560px] pt-6 border-t border-dark-800/15">
                <div>
                    <span class="block font-display text-[32px] leading-none">{{ $t('hero.price', ['price' => $fromPrice]) }}</span>
                    <span class="block mt-2 text-xs uppercase tracking-widest text-warm-600">{{ $t('hero.price_label') }}</span>
                </div>
                @if($rating)
                @php $ratingText = \App\Helpers\RatingFormat::average($rating['average']); @endphp
                <a href="#recenze" class="block" aria-label="{{ __('components.rating.aria', ['rating' => $ratingText, 'count' => $rating['count']]) }}">
                    <span class="flex items-baseline gap-2 font-display text-[32px] leading-none text-dark-800">
                        {{ $ratingText }}
                        <span class="text-sm tracking-[2px] text-primary-500" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                    </span>
                    <span class="block mt-2 text-xs uppercase tracking-widest text-warm-600">{{ trans_choice('components.rating.count', $rating['count'], ['count' => $rating['count']]) }}</span>
                </a>
                @endif
                <div>
                    <span class="block font-display text-[32px] leading-none">{{ $cycle->cutoffShort() }}</span>
                    <span class="block mt-2 text-xs uppercase tracking-widest text-warm-600">{{ $t('hero.cutoff_label') }}</span>
                </div>
            </div>

            <div>
                <a href="{{ $boxUrl() }}" class="inline-block bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-3 transition-colors duration-200">{{ $t('hero.cta') }} &rarr;</a>
            </div>
        </div>

        <div class="flex-[5_1_380px] min-w-0">
            <x-promo-card :lineup="$lineup" />
        </div>
    </div>
</section>

{{-- 2. Proč si vybrat KAVI --}}
<section aria-label="{{ $t('why.title') }}" class="bg-dark-800 text-white">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8 pt-20 sm:pt-24 lg:pt-28 pb-24 lg:pb-32">
        <div class="max-w-2xl mb-16 lg:mb-20">
            <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal uppercase tracking-tight leading-tight sm:leading-[0.95] text-white mb-4">{{ $t('why.title') }}</h2>
            <p class="text-lg sm:text-xl font-light text-warm-300">{{ $t('why.lead') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            @foreach(__('landing.europe.why.cards') as $i => $card)
            <div class="border-t-2 border-primary-500 pt-8">
                <span class="block mb-4 font-display text-5xl font-normal text-primary-500">{{ $t("why.cards.$i.n") }}</span>
                <h3 class="mb-3 text-xl font-normal uppercase tracking-wide text-white">{{ $t("why.cards.$i.t") }}</h3>
                <p class="leading-relaxed font-light text-warm-300">{{ $t("why.cards.$i.d") }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 3. Káva měsíce --}}
<x-coffee-of-month :lineup="$lineup" />

{{-- 4. Vybíráme za vás --}}
<section aria-label="{{ $t('curated.title') }}" class="relative overflow-hidden">
    <div class="grid lg:grid-cols-2 max-w-[2400px] mx-auto">
        <div class="relative bg-primary-500">
            <div class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8">
                <div class="relative aspect-[5/4] overflow-hidden">
                    <img src="/images/cupping.jpg" loading="lazy" alt="{{ $t('curated.image_alt') }}" class="h-full w-full object-cover grayscale" />
                    <div class="absolute inset-0 opacity-30 mix-blend-overlay pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 200 200%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%275%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>
                </div>
            </div>
        </div>

        <div class="flex flex-col justify-between p-6 sm:p-8 lg:p-12 xl:p-16">
            <div class="space-y-8">
                <div>
                    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-6 leading-tight tracking-tight uppercase">{{ $t('curated.title') }}</h2>
                    <p class="text-lg sm:text-xl text-warm-500 leading-relaxed font-light">{{ $t('curated.lead') }}</p>
                </div>
                <div class="space-y-4">
                    @foreach(__('landing.europe.curated.arrows') as $i => $arrow)
                    <div class="flex gap-3 items-start">
                        <span class="text-primary-500" aria-hidden="true">→</span>
                        <p class="text-warm-500 font-light">{{ $t("curated.arrows.$i") }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-12 pt-12 border-t border-stone-300 flex items-center justify-between gap-4">
                <a href="{{ $boxUrl() }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
                    <span>{{ $t('curated.cta') }}</span>
                    <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
                </a>
                <a href="#ceny" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
                    <span>{{ $t('curated.prices') }}</span>
                    <span aria-hidden="true">&darr;</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- 5. Velikost boxu --}}
<x-tiers :tiers="$tiers" id="ceny" :title="$t('tiers_title')" :href="$planUrl" />

{{-- 6. Recenze --}}
<x-reviews :reviews="$reviews" :summary="$rating" />

{{-- 7. Jak to funguje --}}
<section aria-label="{{ $t('how.title') }}" class="pt-20 sm:pt-24 lg:pt-36 pb-16 lg:pb-28">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="mb-16 sm:mb-20 max-w-2xl">
            <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-4 tracking-tight uppercase leading-tight sm:leading-[0.95]">{{ $t('how.title') }}</h2>
            <p class="text-lg sm:text-xl text-warm-500 font-light">{{ $t('how.lead') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @foreach(__('landing.europe.how.steps') as $i => $step)
            <div class="border-t-2 border-primary-500 pt-8">
                <span class="block mb-4 font-display text-5xl font-normal text-primary-500">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $t("how.steps.$i.t") }}</h3>
                <p class="text-warm-500 leading-relaxed font-light">{{ $t("how.steps.$i.d") }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 8. Než se rozhodnete --}}
<x-faq :items="$faq" :title="$t('faq.title')" padding="pt-8 pb-20 lg:pb-28" />

{{-- 9. Závěrečná výzva --}}
<section aria-label="{{ $t('final.title') }}" class="pt-24 pb-28 lg:pb-32 border-t border-dark-800/10">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8 flex flex-col items-center text-center">
        <h2 class="font-display mb-8 max-w-4xl text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 uppercase tracking-tight leading-[1.05]">{{ $t('final.title') }}</h2>
        <p class="mb-12 max-w-2xl text-lg sm:text-xl leading-relaxed font-light text-warm-500">{{ $t('final.lead') }}</p>
        <div class="flex flex-wrap justify-center items-center gap-x-10 gap-y-6">
            <a href="{{ $boxUrl() }}" class="inline-block bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-3 transition-colors duration-200">{{ $t('final.cta') }} &rarr;</a>
            <a href="#ceny" class="group inline-flex items-center gap-2 font-display uppercase tracking-widest text-warm-600 hover:text-dark-800 transition-colors">
                <span>{{ $t('final.compare') }}</span>
                <span aria-hidden="true">&uarr;</span>
            </a>
        </div>
    </div>
</section>

</div>
@endsection
