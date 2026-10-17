@extends('layouts.app')

@section('title', __('gifts.redeem.title'))
@section('meta_description', __('gifts.redeem.meta_description'))
@section('robots', 'noindex, follow')

@php
    $rowClass = 'group relative flex items-center justify-between gap-4 py-5 pl-4 pr-2 cursor-pointer border-b border-dark-800/10 transition-colors hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 has-[:focus-visible]:outline has-[:focus-visible]:outline-2 has-[:focus-visible]:-outline-offset-2 has-[:focus-visible]:outline-primary-500';
    $barClass = 'absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors';
@endphp

@section('content')
<div style="background-color: #e5e6df;">

    {{-- Hero --}}
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
        <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
            <span class="text-dark-800">{{ __('gifts.redeem.hero_title')[0] }}</span><br>
            <span class="text-primary-500">{{ __('gifts.redeem.hero_title')[1] }}</span>
        </h1>
        <div class="flex justify-end">
            <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-600 max-w-md text-right leading-relaxed">{{ __('gifts.redeem.hero_text') }}</p>
        </div>
    </div>

    <section class="py-12 sm:py-16 lg:py-20" data-gift-redeem data-redeem-config='@json($jsConfig)' data-prefill="{{ $prefill }}">
        <div class="max-w-screen-xl mx-auto px-4 md:px-8">
            @if(session('error'))
            <div class="mb-8 bg-white border border-primary-700 px-6 py-4 text-primary-700 text-sm" role="alert">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                {{-- Levý sloupec: před ověřením návod, po ověření karta dárku --}}
                <aside class="lg:col-span-5 order-2 lg:order-none" data-redeem-aside aria-live="polite">
                    <div class="lg:sticky lg:top-[88px]">
                        <div data-redeem-intro>
                            <p class="text-xs uppercase tracking-widest text-warm-600 mb-4">{{ __('gifts.redeem.intro_eyebrow') }}</p>
                            <h2 class="font-display text-[36px] min-[481px]:text-5xl uppercase leading-[0.95] tracking-tight text-dark-800 mb-6">
                                {{ __('gifts.redeem.intro_title')[0] }}<br>
                                <span class="text-primary-500">{{ __('gifts.redeem.intro_title')[1] }}</span>
                            </h2>
                            <p class="text-warm-600 font-light leading-relaxed mb-10">{{ __('gifts.redeem.intro_text') }}</p>
                            <div class="space-y-6">
                                @foreach(__('gifts.redeem.intro_steps') as $i => $step)
                                <div class="flex gap-5">
                                    <span class="font-display text-[28px] leading-none text-primary-500">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div>
                                        <p class="font-display text-lg uppercase text-dark-800">{{ $step['t'] }}</p>
                                        <p class="text-sm text-warm-600 font-light">{{ $step['d'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div data-redeem-card hidden>
                            <div class="flex items-center gap-3 mb-6">
                                <span class="inline-flex items-center gap-1.5 bg-olive-500 text-white text-xs uppercase tracking-widest px-2 py-1"><span aria-hidden="true">&#10003;</span>{{ __('gifts.redeem.verified') }}</span>
                                <span class="text-xs uppercase tracking-widest text-warm-600" data-redeem-field="code"></span>
                            </div>
                            <h2 class="font-display text-[36px] min-[481px]:text-5xl uppercase leading-[0.95] tracking-tight text-dark-800 mb-8">
                                <span data-redeem-field="title0"></span><br>
                                <span class="text-primary-500" data-redeem-field="title1"></span>
                            </h2>
                            <div data-redeem-wish hidden>
                                <div class="relative bg-dark-800 p-6 sm:p-8">
                                    <p class="text-xs uppercase tracking-widest text-white/50 mb-3">{{ __('gifts.redeem.wish') }}</p>
                                    <p class="text-white text-xl leading-relaxed font-light">{{ __('gifts.certificate.quote_open') }}<span data-redeem-field="note"></span>{{ __('gifts.certificate.quote_close') }}</p>
                                    <div class="absolute -bottom-4 left-6 w-8 h-4 bg-dark-800" style="clip-path: polygon(0 0, 100% 0, 50% 100%);" aria-hidden="true"></div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-3 px-6 pt-8 pb-6">
                                <span class="text-xs uppercase tracking-widest text-warm-600">{{ __('gifts.redeem.from') }}</span>
                                <span class="font-display text-lg uppercase text-dark-800" data-redeem-field="giver"></span>
                            </div>
                            <dl class="border-t border-warm-300" data-redeem-rows></dl>
                        </div>
                    </div>
                </aside>

                {{-- Pravý sloupec: kroky --}}
                <div class="lg:col-span-7">

                    {{-- 01 Kód z certifikátu --}}
                    <div class="mb-12 border-t-2 border-primary-500 pt-6">
                        <div class="mb-6">
                            <div class="flex items-baseline gap-4 mb-1">
                                <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl">01</span>
                                <h2 class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.redeem.step_code') }}</h2>
                            </div>
                            <p class="text-sm text-warm-600 font-light">{{ __('gifts.redeem.step_code_sub') }}</p>
                        </div>
                        <form data-redeem-verify class="flex flex-col sm:flex-row sm:items-end gap-3" novalidate>
                            <div class="flex-1">
                                <label for="redeem-code" class="block mb-2 text-xs uppercase tracking-widest text-warm-600">{{ __('gifts.redeem.code_label') }}</label>
                                <input id="redeem-code" name="code" type="text" value="{{ $prefill }}" placeholder="XXXX-XXXX" autocomplete="off" autocapitalize="characters" spellcheck="false" maxlength="40"
                                       aria-describedby="redeem-code-message"
                                       class="w-full px-4 py-3 bg-white border border-warm-300 text-dark-800 font-display text-[22px] uppercase tracking-[0.08em] placeholder-warm-400 focus:outline-none focus:border-dark-800 focus:ring-0">
                            </div>
                            <button type="submit" class="px-6 py-[15px] bg-dark-800 hover:bg-black text-white font-display uppercase tracking-wide transition-colors whitespace-nowrap disabled:opacity-60" data-redeem-verify-button>{{ __('gifts.redeem.verify') }}</button>
                        </form>
                        <div id="redeem-code-message" class="mt-4 min-h-[1.5rem]">
                            <p class="hidden items-center gap-2 text-sm text-dark-800" data-redeem-ok><span class="inline-flex items-center justify-center w-4 h-4 bg-olive-500 text-white text-[10px]" aria-hidden="true">&#10003;</span><span data-redeem-ok-text></span></p>
                            <p class="hidden text-sm text-primary-700" role="alert" data-redeem-error></p>
                        </div>
                    </div>

                    {{-- Před ověřením jen šedé nadpisy --}}
                    <div data-redeem-placeholder class="opacity-40" aria-hidden="true">
                        <div class="mb-12 border-t-2 border-primary-500 pt-6"><div class="flex items-baseline gap-4"><span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl">02</span><h2 class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.redeem.step_type') }}</h2></div></div>
                        <div class="mb-12 border-t-2 border-primary-500 pt-6"><div class="flex items-baseline gap-4"><span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl">03</span><h2 class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.redeem.step_start') }}</h2></div></div>
                    </div>

                    {{-- Předplatné: 02 typ kávy, 03 kdy začít, shrnutí --}}
                    <form method="POST" action="{{ localizedRoute('gifts.redeem.prepare') }}" data-redeem-subscription hidden>
                        @csrf
                        <input type="hidden" name="mix_espresso" value="1" data-redeem-mix-input="espresso">
                        <input type="hidden" name="mix_filter" value="1" data-redeem-mix-input="filter">

                        <fieldset class="mb-12 border-t-2 border-primary-500 pt-6">
                            <div class="mb-6">
                                <div class="flex items-baseline gap-4 mb-1">
                                    <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl">02</span>
                                    <legend class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.redeem.step_type') }}</legend>
                                </div>
                                <p class="text-sm text-warm-600 font-light">{{ __('gifts.redeem.step_type_sub') }}</p>
                            </div>
                            @foreach(__('gifts.redeem.types') as $key => $type)
                            <label class="{{ $rowClass }}">
                                <input type="radio" name="type" value="{{ $key }}" class="sr-only" @checked($key === 'espresso')>
                                <span class="{{ $barClass }}" aria-hidden="true"></span>
                                <span class="flex-1 min-w-0">
                                    <span class="block font-display text-2xl uppercase text-dark-800">{{ $type['name'] }}</span>
                                    <span class="block text-xs font-light text-warm-600">{{ $type['desc'] }}</span>
                                </span>
                            </label>
                            @endforeach

                            <div class="mt-6 p-5 border border-warm-300" data-redeem-mix hidden>
                                <h3 class="font-display text-lg uppercase mb-4">{{ __('gifts.redeem.mix_title') }}</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach(['espresso' => __('gifts.redeem.types.espresso.name'), 'filter' => __('gifts.redeem.types.filter.name')] as $mixKey => $mixLabel)
                                    <div class="flex flex-col p-4 bg-white">
                                        <span class="mb-2 text-sm font-medium">{{ $mixLabel }}</span>
                                        <div class="flex items-center justify-center gap-3">
                                            <button type="button" class="w-10 h-10 bg-stone-200 hover:bg-stone-300 text-xl" data-redeem-mix-step="{{ $mixKey }}" data-delta="-1" aria-label="{{ __('gifts.redeem.mix_less') }}: {{ $mixLabel }}">&minus;</button>
                                            <span class="w-10 text-center font-display text-xl" data-redeem-mix-value="{{ $mixKey }}">1</span>
                                            <button type="button" class="w-10 h-10 bg-stone-200 hover:bg-stone-300 text-xl" data-redeem-mix-step="{{ $mixKey }}" data-delta="1" aria-label="{{ __('gifts.redeem.mix_more') }}: {{ $mixLabel }}">+</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mb-12 border-t-2 border-primary-500 pt-6">
                            <div class="mb-6">
                                <div class="flex items-baseline gap-4 mb-1">
                                    <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl">03</span>
                                    <legend class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.redeem.step_start') }}</legend>
                                </div>
                                <p class="text-sm text-warm-600 font-light">{{ __('gifts.redeem.step_start_sub', ['cutoff_day' => $cycle->cutoffAt->day, 'ship_day' => $cycle->shipmentDate->day]) }}</p>
                            </div>
                            <div data-redeem-starts></div>
                        </fieldset>

                        <div class="border-t-2 border-primary-500 pt-6">
                            <h3 class="font-display text-4xl uppercase tracking-tight text-dark-800 mb-6">{{ __('gifts.redeem.summary') }}</h3>
                            <dl class="mb-6">
                                @foreach(['box' => __('gifts.redeem.summary_box'), 'type' => __('gifts.redeem.summary_type'), 'count' => __('gifts.redeem.summary_count'), 'first' => __('gifts.redeem.summary_first'), 'last' => __('gifts.redeem.summary_last')] as $key => $label)
                                <div class="flex justify-between gap-4 py-2 border-b border-dark-800/10">
                                    <dt class="text-xs uppercase tracking-widest text-warm-600">{{ $label }}</dt>
                                    <dd class="font-display text-lg text-dark-800 text-right" data-redeem-summary="{{ $key }}">-</dd>
                                </div>
                                @endforeach
                            </dl>
                            <button type="submit" class="w-full py-4 bg-dark-800 text-white font-display text-lg uppercase tracking-wide hover:bg-black transition-colors flex items-center justify-center gap-3">
                                <span>{{ __('gifts.redeem.continue') }}</span>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </button>
                            <p class="mt-4 text-center text-xs text-warm-600">{{ __('gifts.redeem.continue_note') }}</p>
                        </div>
                    </form>

                    {{-- Poukaz: 02 jak poukaz použít --}}
                    <div data-redeem-voucher hidden>
                        <div class="mb-12 border-t-2 border-primary-500 pt-6">
                            <div class="mb-6">
                                <div class="flex items-baseline gap-4 mb-1">
                                    <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl">02</span>
                                    <h2 class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.redeem.voucher_step') }}</h2>
                                </div>
                                <p class="text-sm text-warm-600 font-light">{{ __('gifts.redeem.voucher_step_sub') }}</p>
                            </div>
                            <a href="{{ localizedRoute('subscriptions.index') }}" class="group flex items-center justify-between gap-4 py-5 pl-4 pr-2 border-b border-dark-800/10 hover:bg-warm-300/30 transition-colors">
                                <span>
                                    <span class="block font-display text-2xl uppercase text-dark-800">{{ __('gifts.redeem.voucher_box') }}</span>
                                    <span class="block text-xs font-light text-warm-600">{{ __('gifts.redeem.voucher_box_desc', ['price' => \App\Helpers\CurrencyHelper::formatAmount($cheapest, 0)]) }}</span>
                                </span>
                                <span class="inline-flex items-center gap-2 text-sm font-display uppercase tracking-widest text-dark-800 group-hover:text-primary-500 whitespace-nowrap">{{ __('gifts.redeem.voucher_box_link') }} <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span></span>
                            </a>
                            <a href="{{ localizedRoute('products.index') }}" class="group flex items-center justify-between gap-4 py-5 pl-4 pr-2 border-b border-dark-800/10 hover:bg-warm-300/30 transition-colors">
                                <span>
                                    <span class="block font-display text-2xl uppercase text-dark-800">{{ __('gifts.redeem.voucher_shop') }}</span>
                                    <span class="block text-xs font-light text-warm-600">{{ __('gifts.redeem.voucher_shop_desc') }}</span>
                                </span>
                                <span class="inline-flex items-center gap-2 text-sm font-display uppercase tracking-widest text-dark-800 group-hover:text-primary-500 whitespace-nowrap">{{ __('gifts.redeem.voucher_shop_link') }} <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span></span>
                            </a>
                            <p class="mt-4 text-sm leading-[22px] text-warm-600" data-redeem-field="rules"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-faq :items="$faq" :lead="__('components.faq.contact', ['email' => $email])" />

</div>
@endsection
