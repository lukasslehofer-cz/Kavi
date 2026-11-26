@extends('layouts.app')

@php
    $max = \App\Helpers\CorporateHelper::formatNumber($maxDiscount);
    $percent = fn ($value) => \App\Helpers\CorporateHelper::formatNumber($value) . "\u{00A0}%";
    $quantityLabel = fn ($tier, $range, $open) => $tier['to'] !== null
        ? __($range, ['from' => $tier['from'], 'to' => $tier['to']])
        : __($open, ['from' => $tier['from']]);

    // Opakované třídy podle návrhu (designs/Firmy.dc.html)
    $section = 'mx-auto max-w-screen-xl px-4 md:px-8 pt-28 pb-32';
    $h2 = 'font-display text-[36px] sm:text-[48px] leading-[0.95] uppercase tracking-tight text-dark-800 text-balance';
    $lead = 'text-xl leading-[30px] font-light text-warm-500';
    $eyebrow = 'text-xs uppercase tracking-widest';
    $photo = 'block w-full h-full object-cover grayscale';
    $label = 'block mb-2 text-xs uppercase tracking-widest text-warm-600';
    $input = 'block w-full px-4 py-3 bg-white border border-warm-300 rounded-none text-base leading-6 text-dark-800 placeholder-warm-500 shadow-none transition-colors focus:border-primary-500 focus:outline focus:outline-2 focus:outline-offset-0 focus:outline-primary-500 focus:ring-0 aria-[invalid=true]:border-primary-700';
    $segment = 'group relative flex items-center min-w-0 py-3.5 pr-3 pl-4 bg-white border border-warm-300 text-[15px] leading-5 text-dark-800 cursor-pointer transition-colors hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 has-[:focus-visible]:outline has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-0 has-[:focus-visible]:outline-primary-500';
    $segmentBar = 'absolute left-0 inset-y-0 w-1 bg-transparent transition-colors group-has-[:checked]:bg-primary-500';
    $req = '<span class="text-primary-500" aria-hidden="true">*</span>';

    $whyPhotos = [
        ['src' => '/images/kavi-intro-video.jpg', 'position' => 'object-[60%_50%]'],
        ['src' => '/images/cupping.jpg', 'position' => 'object-center'],
        ['src' => '/images/firmy/hrnek.jpg', 'position' => 'object-[35%_50%]'],
    ];
@endphp

@section('title', __('corporate.title', ['min' => $minQuantity]))
@section('meta_description', __('corporate.meta_description', ['min' => $minQuantity, 'max' => $max]))
@section('og_image', url('/images/firmy/mockup-paska-karticka.jpg'))

@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(fn ($item) => [
        '@type' => 'Question',
        'name' => $item['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
    ], $faq),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
</script>
@endsection

@section('content')
<div class="bg-warm-200 text-dark-800">

    {{-- 1. Hero --}}
    <section aria-label="{{ __('corporate.hero.label') }}">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8 pt-24 pb-28 flex flex-wrap items-center gap-16">
            <div class="flex-[7_1_520px] min-w-0 flex flex-col gap-10">
                @if($christmas)
                <p class="self-start flex flex-wrap border border-primary-500 bg-white">
                    <span class="flex items-center px-3 py-2 bg-primary-500 text-white {{ $eyebrow }} font-medium">{{ __('corporate.hero.xmas', ['year' => $christmas['year']]) }}</span>
                    <span class="flex items-center px-3.5 py-2 text-[15px] leading-[22px] text-dark-800">{{ __('corporate.hero.xmas_inquiry') }}&nbsp;<b class="font-medium">{{ $christmas['inquiry_by'] }}</b>, {{ __('corporate.hero.xmas_deliver') }}&nbsp;<b class="font-medium">{{ $christmas['deliver_by'] }}</b></span>
                </p>
                @endif

                <h1 class="font-display text-[clamp(40px,5vw,72px)] leading-[0.95] uppercase tracking-tight text-dark-800 text-balance">
                    {{ __('corporate.hero.title') }} <span class="text-primary-500">{{ __('corporate.hero.title_accent') }}</span>
                </h1>

                <p class="max-w-[540px] {{ $lead }}">{{ __('corporate.hero.lead', ['min' => $minQuantity]) }}</p>

                <div class="flex flex-wrap items-center gap-x-8 gap-y-4">
                    <a href="#poptavka" class="btn-fill">{{ __('corporate.hero.cta') }} <span aria-hidden="true">&rarr;</span></a>
                    <a href="#varianty" class="group inline-flex items-center gap-2 font-display text-sm uppercase tracking-widest text-dark-800 transition-colors duration-200 hover:text-primary-500">
                        <span>{{ __('corporate.hero.variants') }}</span>
                        <span class="transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">&darr;</span>
                    </a>
                </div>

                <div class="flex flex-wrap gap-x-6 gap-y-2 max-w-[600px] pt-6 border-t border-dark-800/15 {{ $eyebrow }} text-warm-600">
                    @foreach(__('corporate.hero.trust') as $trust)
                    <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 flex-shrink-0 bg-primary-500" aria-hidden="true"></span>{{ $trust }}</span>
                    @endforeach
                </div>
            </div>

            <div class="flex-[5_1_380px] min-w-0">
                <div class="relative aspect-square overflow-hidden bg-warm-300">
                    <img src="/images/kavi-box.jpg" alt="{{ __('corporate.hero.image_alt') }}" width="1200" height="1200" class="{{ $photo }}" fetchpriority="high">
                    <div class="grain-overlay"></div>
                    <div class="absolute left-0 top-0 w-12 h-[40%] min-h-[176px] bg-primary-500 flex items-center justify-center">
                        <span class="font-display text-sm uppercase tracking-[0.2em] text-dark-800 whitespace-nowrap -rotate-90">{{ __('corporate.hero.image_label') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Proč káva --}}
    <section aria-label="{{ __('corporate.why.label') }}" class="border-t border-dark-800/10">
        <div class="{{ $section }}">
            <h2 class="{{ $h2 }} max-w-[720px] mb-16">{{ __('corporate.why.title') }}</h2>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(min(300px,100%),1fr))] gap-12">
                @foreach(__('corporate.why.items') as $i => $item)
                <div>
                    <div class="relative aspect-[4/3] overflow-hidden bg-warm-300">
                        <img src="{{ $whyPhotos[$i]['src'] }}" alt="{{ $item['alt'] }}" loading="lazy" class="{{ $photo }} {{ $whyPhotos[$i]['position'] }}">
                        <div class="grain-overlay"></div>
                    </div>
                    <div class="mt-8 pt-6 border-t-2 border-primary-500">
                        <h3 class="mb-3 text-xl leading-7 uppercase tracking-wide text-dark-800">{{ $item['t'] }}</h3>
                        <p class="text-base leading-[26px] font-light text-warm-500">{{ $item['d'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 3. Varianty --}}
    <section id="varianty" aria-label="{{ __('corporate.variants.label') }}" class="scroll-mt-[88px] border-t border-dark-800/10">
        <div class="{{ $section }}">
            <h2 class="{{ $h2 }} max-w-[760px] mb-16">{{ __('corporate.variants.title') }}</h2>

            <div class="grid grid-cols-[repeat(auto-fit,minmax(min(400px,100%),1fr))] items-stretch gap-6">
                {{-- Dárkový box (zvýrazněná karta) --}}
                <div class="flex flex-col px-8 pt-8 pb-9 bg-warm-300/40 border-t-4 border-primary-500">
                    <h3 class="mb-5 text-[28px] leading-8 uppercase text-dark-800">{{ __('corporate.variants.box.name') }}</h3>
                    <p class="mb-1.5 {{ $eyebrow }} text-warm-600">{{ __('corporate.variants.ideal_for') }}</p>
                    <p class="mb-4 font-display text-xl leading-[26px] uppercase tracking-wide text-primary-500">{{ __('corporate.variants.box.for') }}</p>
                    <p class="text-base leading-[26px] font-light text-dark-700">{{ __('corporate.variants.box.desc') }}</p>
                    <div role="list" aria-label="{{ __('corporate.variants.box.sizes_label') }}" class="grid grid-cols-3 gap-2 mt-7">
                        @foreach(__('corporate.variants.box.sizes') as $size)
                        <div role="listitem" class="relative py-3.5 pr-3 pl-4 bg-white border border-warm-300">
                            @if(! empty($size['badge']))
                            <span class="absolute left-0 inset-y-0 w-1 bg-primary-500" aria-hidden="true"></span>
                            <span class="absolute -top-2.5 right-2 px-2 py-0.5 bg-primary-500 text-white text-[11px] leading-4 uppercase tracking-widest font-medium">{{ $size['badge'] }}</span>
                            @endif
                            <span class="block font-display text-[22px] leading-[26px] text-dark-800">{{ $size['code'] }}</span>
                            <span class="block mt-0.5 text-[13px] leading-[18px] text-warm-600">{{ $size['desc'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-auto pt-8">
                        <p class="pt-4 border-t border-warm-300 text-sm leading-[22px] font-light text-dark-700">{{ __('corporate.variants.box.note') }}</p>
                    </div>
                </div>

                {{-- Dárkové předplatné --}}
                <div class="flex flex-col px-8 pt-8 pb-9 border-t-2 border-primary-500">
                    <h3 class="mb-5 text-[28px] leading-8 uppercase text-dark-800">{{ __('corporate.variants.subscription.name') }}</h3>
                    <p class="mb-1.5 {{ $eyebrow }} text-warm-600">{{ __('corporate.variants.ideal_for') }}</p>
                    <p class="mb-4 font-display text-xl leading-[26px] uppercase tracking-wide text-primary-500">{{ __('corporate.variants.subscription.for') }}</p>
                    <p class="text-base leading-[26px] font-light text-dark-700">{{ __('corporate.variants.subscription.desc') }}</p>
                    <div class="mt-auto pt-8">
                        <div role="list" aria-label="{{ __('corporate.variants.subscription.lengths_label') }}" class="grid grid-cols-2 gap-2 mb-8">
                            @foreach(__('corporate.variants.subscription.lengths') as $length)
                            <div role="listitem" class="relative py-3.5 pr-3 pl-4 bg-white border border-warm-300">
                                <span class="block font-display text-[22px] leading-[26px] text-dark-800">{{ $length['t'] }}</span>
                                <span class="block mt-0.5 text-[13px] leading-[18px] text-warm-600">{{ $length['d'] }}</span>
                            </div>
                            @endforeach
                        </div>
                        <p class="pt-4 border-t border-warm-300 text-sm leading-[22px] font-light text-dark-700">{{ __('corporate.variants.subscription.note') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-x-12 gap-y-6 mt-12">
                <p class="{{ $eyebrow }} text-warm-600">{{ __('corporate.variants.offer') }}</p>
                <a href="#poptavka" class="btn-fill">{{ __('corporate.hero.cta') }} <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
    </section>

    {{-- 4. Personalizace a doručení --}}
    <section aria-label="{{ __('corporate.custom.label') }}" class="border-t border-dark-800/10">
        <div class="{{ $section }}">
            <h2 class="{{ $h2 }} max-w-[760px] mb-16">{{ __('corporate.custom.title') }}</h2>
            <div class="flex flex-wrap items-start gap-x-16 gap-y-12">
                <figure class="flex-[5_1_400px] min-w-0">
                    <div class="relative aspect-square overflow-hidden bg-warm-300">
                        <img src="/images/firmy/mockup-paska-karticka.jpg" alt="{{ __('corporate.custom.image_alt') }}" loading="lazy" width="1200" height="1200" class="block w-full h-full object-cover">
                    </div>
                    <figcaption class="mt-3 {{ $eyebrow }} text-warm-600">{{ __('corporate.custom.caption') }}</figcaption>
                </figure>
                <div class="flex-[7_1_480px] min-w-0 flex flex-col gap-12">
                    @foreach(__('corporate.custom.blocks') as $block)
                    <div class="pt-6 border-t-2 border-primary-500">
                        <h3 class="mb-4 text-2xl leading-8 uppercase text-dark-800">{{ $block['t'] }}</h3>
                        <div class="border-b border-warm-300">
                            @foreach($block['rows'] as $row)
                            <div class="flex items-start justify-between gap-6 py-5 border-t border-warm-300">
                                <div>
                                    <p class="mb-1 font-display text-lg leading-6 uppercase tracking-wide text-dark-800">{{ $row['t'] }}</p>
                                    <p class="text-sm leading-[22px] font-light text-warm-600">{{ $row['d'] }}</p>
                                </div>
                                @if(! empty($row['tag']))
                                <span class="flex-shrink-0 px-2 py-1 bg-warm-300 {{ $eyebrow }} text-dark-800">{{ $row['tag'] }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- 5. Množstevní ceny --}}
    <section aria-label="{{ __('corporate.pricing.label') }}" class="bg-dark-800 text-white">
        <div class="{{ $section }}">
            <h2 class="{{ $h2 }} !text-white max-w-[760px] mb-16">{{ __('corporate.pricing.title') }}</h2>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(min(240px,100%),1fr))] gap-12">
                @foreach($tiers as $tier)
                <div class="pt-6 border-t-2 border-primary-500">
                    <span class="block font-display text-[72px] sm:text-8xl leading-none tracking-[-0.03em] text-primary-500">{{ $percent($tier['percent']) }}</span>
                    <p class="mt-4 mb-1 font-display text-xl leading-7 uppercase tracking-wide text-white">{{ $quantityLabel($tier, 'corporate.pricing.range', 'corporate.pricing.range_open') }}</p>
                    <p class="text-sm leading-[22px] font-light text-warm-300">{{ __('corporate.pricing.sub') }}</p>
                </div>
                @endforeach
            </div>
            <div class="flex flex-wrap items-center justify-between gap-x-12 gap-y-6 mt-16 pt-8 border-t border-white/[0.18]">
                <p class="max-w-[640px] text-base leading-[26px] font-light text-warm-300">{{ __('corporate.pricing.note') }} <br class="hidden sm:inline">{{ __('corporate.pricing.note_end') }}</p>
                <a href="#poptavka" class="btn-fill">{{ __('corporate.pricing.cta') }} <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
    </section>

    {{-- 6. Jak to funguje --}}
    <section aria-label="{{ __('corporate.how.label') }}">
        <div class="{{ $section }}">
            <h2 class="{{ $h2 }} max-w-[760px] mb-16">{{ __('corporate.how.title') }}</h2>
            <div class="grid grid-cols-[repeat(auto-fit,minmax(min(230px,100%),1fr))] gap-12">
                @foreach(__('corporate.how.steps') as $i => $step)
                <div class="pt-8 border-t-2 border-primary-500">
                    <span class="block mb-4 font-display text-5xl leading-none text-primary-500">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="mb-3 text-xl leading-7 uppercase tracking-wide text-dark-800">{{ $step['t'] }}</h3>
                    <p class="text-base leading-[26px] font-light text-warm-500">{{ $step['d'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 7. Pro HR --}}
    <section id="hr" aria-label="{{ __('corporate.hr.label') }}" class="scroll-mt-[88px] flex flex-wrap">
        <div class="flex-[1_1_560px] min-w-0 px-10 py-8 bg-primary-500">
            <div class="relative aspect-[5/4] overflow-hidden bg-warm-300">
                <img src="/images/firmy/tym.jpg" alt="{{ __('corporate.hr.image_alt') }}" loading="lazy" width="1200" height="800" class="{{ $photo }} object-[55%_50%]">
                <div class="grain-overlay"></div>
            </div>
        </div>
        <div class="flex-[1_1_560px] min-w-0 flex flex-col justify-center gap-8 px-4 py-10 sm:p-16">
            <div>
                <p class="mb-4 {{ $eyebrow }} text-warm-600">{{ __('corporate.hr.eyebrow') }}</p>
                <h2 class="{{ $h2 }} !leading-[1.05] mb-6">{{ __('corporate.hr.title') }}</h2>
                <p class="{{ $lead }}">{{ __('corporate.hr.lead') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach(__('corporate.hr.chips') as $chip)
                <span class="px-2.5 py-1.5 bg-primary-500 text-black {{ $eyebrow }}">{{ $chip }}</span>
                @endforeach
            </div>
            <div>
                <a href="#poptavka" class="btn-outline" data-corporate-audience="zamestnanci">{{ __('corporate.hr.cta') }} <span aria-hidden="true">&rarr;</span></a>
            </div>
        </div>
    </section>

    {{-- 8. Časté dotazy --}}
    <x-faq :items="$faq" :title="__('corporate.faq_title')" :accordion="true" padding="pt-28 pb-32" title-class="text-[36px] sm:text-[48px] leading-[0.95]" />

    {{-- 9. Poptávka --}}
    <section id="poptavka" aria-label="{{ __('corporate.form.label') }}" class="scroll-mt-[88px] border-t border-dark-800/10">
        <div class="{{ $section }} flex flex-wrap gap-x-16 gap-y-12">
            <div class="flex-[5_1_360px] min-w-0">
                <h2 class="{{ $h2 }} !leading-[1.05] mb-6">{{ __('corporate.form.title') }}</h2>
                <p class="mb-10 {{ $lead }}">{{ __('corporate.form.lead') }}</p>
                <div class="flex flex-col gap-4">
                    @foreach(__('corporate.form.arrows') as $arrow)
                    <div class="flex items-start gap-3">
                        <span class="text-primary-500" aria-hidden="true">&rarr;</span>
                        <p class="font-light text-warm-500">{{ str_replace([':min', ':max'], [$minQuantity, $max], $arrow) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex-[7_1_520px] min-w-0">
                <form method="POST" action="{{ route('corporate.send') }}" class="flex flex-col gap-6 pt-8 border-t-2 border-primary-500" novalidate data-corporate-form>
                    @csrf
                    <div class="grid grid-cols-[repeat(auto-fit,minmax(min(240px,100%),1fr))] gap-x-4 gap-y-5">
                        <div>
                            <label for="corporate-name" class="{{ $label }}">{{ __('corporate.form.name') }} {!! $req !!}</label>
                            <input id="corporate-name" name="name" type="text" class="{{ $input }}" placeholder="{{ __('corporate.form.name_placeholder') }}" autocomplete="name" maxlength="255" required aria-required="true">
                        </div>
                        <div>
                            <label for="corporate-company" class="{{ $label }}">{{ __('corporate.form.company') }} {!! $req !!}</label>
                            <input id="corporate-company" name="company" type="text" class="{{ $input }}" placeholder="{{ __('corporate.form.company_placeholder') }}" autocomplete="organization" maxlength="255" required aria-required="true">
                        </div>
                        <div>
                            <label for="corporate-email" class="{{ $label }}">{{ __('corporate.form.email') }} {!! $req !!}</label>
                            <input id="corporate-email" name="email" type="email" class="{{ $input }}" placeholder="{{ __('corporate.form.email_placeholder') }}" autocomplete="email" maxlength="255" required aria-required="true">
                        </div>
                        <div>
                            <label for="corporate-phone" class="{{ $label }}">{{ __('corporate.form.phone') }}</label>
                            <input id="corporate-phone" name="phone" type="tel" class="{{ $input }}" placeholder="{{ __('corporate.form.phone_placeholder') }}" autocomplete="tel" maxlength="50">
                        </div>
                    </div>

                    <div>
                        <span id="corporate-audience-label" class="{{ $label }}">{{ __('corporate.form.audience') }} {!! $req !!}</span>
                        <div role="radiogroup" aria-labelledby="corporate-audience-label" aria-required="true" class="grid grid-cols-3 gap-2" data-corporate-group="audience">
                            @foreach($audiences as $value)
                            <label class="{{ $segment }}">
                                <input type="radio" name="audience" value="{{ $value }}" class="sr-only" @checked($presetAudience === $value)>
                                <span class="{{ $segmentBar }}" aria-hidden="true"></span>
                                <span class="min-w-0 break-words">{{ __('corporate.form.audiences.' . $value) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <span id="corporate-quantity-label" class="{{ $label }}">{{ __('corporate.form.quantity') }} {!! $req !!}</span>
                        <div role="radiogroup" aria-labelledby="corporate-quantity-label" aria-required="true" class="grid grid-cols-3 gap-2" data-corporate-group="quantity">
                            @foreach($tiers as $tier)
                            <label class="{{ $segment }}">
                                <input type="radio" name="quantity" value="{{ $tier['value'] }}" class="sr-only">
                                <span class="{{ $segmentBar }}" aria-hidden="true"></span>
                                <span class="min-w-0">{{ $quantityLabel($tier, 'corporate.form.quantity_range', 'corporate.form.quantity_open') }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-[repeat(auto-fill,minmax(min(240px,100%),1fr))] gap-x-4 gap-y-5">
                        <div>
                            <label for="corporate-date" class="{{ $label }}">{{ __('corporate.form.delivery_date') }}</label>
                            <input id="corporate-date" name="delivery_date" type="date" class="{{ $input }} min-h-[50px]" min="{{ now()->toDateString() }}">
                        </div>
                    </div>

                    <div>
                        <label for="corporate-note" class="{{ $label }}">{{ __('corporate.form.note') }}</label>
                        <textarea id="corporate-note" name="note" rows="4" maxlength="2000" class="{{ $input }} resize-y" placeholder="{{ __('corporate.form.note_placeholder') }}"></textarea>
                    </div>

                    {{-- Honeypot: člověk pole nevidí, robot ho vyplní --}}
                    <div class="absolute -left-[9999px] w-px h-px overflow-hidden" aria-hidden="true">
                        <label for="corporate-website">{{ __('corporate.form.honeypot') }}</label>
                        <input id="corporate-website" name="website" type="text" tabindex="-1" autocomplete="off">
                    </div>

                    <p role="alert" class="text-sm leading-5 text-primary-700 empty:hidden" data-corporate-error></p>

                    <button type="submit" class="w-full py-4 bg-dark-800 text-white font-display text-lg uppercase tracking-wide transition-colors duration-200 hover:bg-black disabled:cursor-wait disabled:opacity-70 flex items-center justify-center gap-3">
                        <span data-corporate-submit-label>{{ __('corporate.form.submit') }}</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </button>

                    <p class="text-center text-sm leading-[22px] font-light text-warm-600">{{ __('corporate.form.email_alt') }} <a href="mailto:{{ $contactEmail }}" class="text-dark-800 underline underline-offset-2 hover:text-primary-500 transition-colors">{{ $contactEmail }}</a>.</p>
                </form>

                <div role="status" class="pt-8 border-t-2 border-primary-500" hidden data-corporate-sent>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-olive-500 text-white text-xs uppercase tracking-[0.05em]"><span aria-hidden="true">&#10003;</span>{{ __('corporate.form.sent_badge') }}</span>
                    <h3 class="mt-6 mb-3 text-[36px] leading-10 uppercase text-dark-800 focus:outline-none" tabindex="-1">{{ __('corporate.form.sent_title') }}</h3>
                    <p class="{{ $lead }}">{{ __('corporate.form.sent_lead') }}</p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('[data-corporate-form]');
    if (!form) return;

    const errorBox = form.querySelector('[data-corporate-error]');
    const sent = document.querySelector('[data-corporate-sent]');
    const button = form.querySelector('button[type="submit"]');
    const buttonLabel = form.querySelector('[data-corporate-submit-label]');
    const recaptchaKey = @json(config('services.recaptcha.site_key'));
    const trackingParams = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'gclid', 'fbclid'];
    const text = {
        prefix: @json(__('corporate.form.error_prefix')),
        missing: @json(__('corporate.form.missing')),
        generic: @json(__('corporate.form.error_generic', ['email' => $contactEmail])),
        throttled: @json(__('corporate.form.error_throttled', ['email' => $contactEmail])),
        submit: @json(__('corporate.form.submit')),
        sending: @json(__('corporate.form.sending')),
    };

    const field = (name) => form.elements[name];
    const group = (name) => form.querySelector('[data-corporate-group="' + name + '"]');
    // Prvek, na který patří aria-invalid (u radio skupin celá skupina)
    const target = (name) => group(name) || field(name);
    const radioValue = (name) => (form.querySelector('input[name="' + name + '"]:checked') || {}).value || '';

    function clearError() {
        errorBox.textContent = '';
        form.querySelectorAll('[aria-invalid]').forEach((el) => el.removeAttribute('aria-invalid'));
    }

    function showMessage(message) {
        errorBox.textContent = message;
    }

    // Jedna věta podle chybějících polí: "Doplňte prosím jméno, firmu, platný e-mail."
    function showMissing(names) {
        names = names.filter((name) => text.missing[name]);
        if (!names.length) {
            showMessage(text.generic);
            return;
        }
        showMessage(text.prefix + ' ' + names.map((name) => text.missing[name]).join(', ') + '.');
        names.forEach((name) => {
            const el = target(name);
            if (el) el.setAttribute('aria-invalid', 'true');
        });
    }

    function validate() {
        const missing = [];
        if (!field('name').value.trim()) missing.push('name');
        if (!field('company').value.trim()) missing.push('company');
        if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(field('email').value.trim())) missing.push('email');
        if (!radioValue('audience')) missing.push('audience');
        if (!radioValue('quantity')) missing.push('quantity');
        const date = field('delivery_date');
        if (date.value && date.min && date.value < date.min) missing.push('delivery_date');
        return missing;
    }

    function pickAudience(value) {
        const input = form.querySelector('input[name="audience"][value="' + value + '"]');
        if (input) {
            input.checked = true;
            clearError();
        }
    }

    // "Poptat dárky pro tým" předvybere Zaměstnance (stejně jako ?pro=zamestnanci)
    document.querySelectorAll('[data-corporate-audience]').forEach((link) => {
        link.addEventListener('click', () => pickAudience(link.dataset.corporateAudience));
    });

    form.addEventListener('input', clearError);
    form.addEventListener('change', clearError);

    function recaptchaToken() {
        if (!recaptchaKey || typeof grecaptcha === 'undefined') return Promise.resolve(null);
        return new Promise((resolve) => {
            grecaptcha.ready(() => {
                grecaptcha.execute(recaptchaKey, { action: 'corporate' }).then(resolve, () => resolve(null));
            });
        });
    }

    function utm() {
        const params = new URLSearchParams(window.location.search);
        const result = {};
        trackingParams.forEach((key) => {
            if (params.get(key)) result[key] = params.get(key);
        });
        return result;
    }

    function success(data, payload) {
        // Formulář má třídu flex, která by atribut hidden přebila
        form.classList.add('hidden');
        sent.hidden = false;
        sent.querySelector('h3').focus();

        // Měření jen u skutečně uložené poptávky (honeypot event_id nevrací)
        if (!data.event_id) return;
        gtag('event', 'generate_lead', { form: 'corporate', audience: payload.audience, quantity: payload.quantity });
        if (typeof fbq !== 'undefined') {
            fbq('track', 'Lead', { content_name: 'Firemní dárky' }, { eventID: data.event_id });
        }
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (button.disabled) return;

        clearError();
        const missing = validate();
        if (missing.length) {
            showMissing(missing);
            return;
        }

        button.disabled = true;
        buttonLabel.textContent = text.sending + '…';

        const payload = {
            name: field('name').value.trim(),
            company: field('company').value.trim(),
            email: field('email').value.trim(),
            phone: field('phone').value.trim(),
            audience: radioValue('audience'),
            quantity: radioValue('quantity'),
            delivery_date: field('delivery_date').value,
            note: field('note').value.trim(),
            website: field('website').value,
            utm: utm(),
        };

        recaptchaToken()
            .then((token) => {
                payload.recaptcha_token = token;
                return fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });
            })
            .then((response) => response.json().catch(() => ({})).then((data) => ({ response, data })))
            .then(({ response, data }) => {
                if (response.ok && data.success) {
                    success(data, payload);
                } else if (response.status === 422 && data.errors) {
                    showMissing(Object.keys(data.errors));
                } else if (response.status === 429) {
                    showMessage(text.throttled);
                } else {
                    showMessage(data.message || text.generic);
                }
            })
            .catch(() => showMessage(text.generic))
            .finally(() => {
                button.disabled = false;
                buttonLabel.textContent = text.submit;
            });
    });
});
</script>
@endpush
