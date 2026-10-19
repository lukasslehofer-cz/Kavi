@extends('layouts.app')

@section('title', __('gifts.page.title'))
@section('meta_description', __('gifts.page.meta_description'))

@php
    $fmt = fn ($amount) => \App\Helpers\CurrencyHelper::formatByCurrency($amount, $currency, 0);
    $isSubInitial = $initial['type'] === 'subscription';
    $oldType = old('type', $initial['type']);
    $oldMonths = (int) old('months', $initial['months']);
    $oldBags = (int) old('bags', $initial['bags']);
    $oldAmount = (float) old('voucher_amount', $initial['voucher_amount']);
    $oldDelivery = old('delivery', 'buyer');
    $initialTotal = $oldType === 'subscription' ? \App\Helpers\BoxPricing::giftSubscriptionPrice($oldBags, $oldMonths, $currency) : $oldAmount;

    // Řádek s výběrem (stejný vzhled jako v konfigurátoru)
    $rowClass = 'group relative flex items-center justify-between gap-4 py-5 pl-4 pr-2 cursor-pointer border-b border-dark-800/10 transition-colors hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 has-[:focus-visible]:outline has-[:focus-visible]:outline-2 has-[:focus-visible]:-outline-offset-2 has-[:focus-visible]:outline-primary-500';
    $barClass = 'absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors';
    $labelClass = 'block mb-2 text-xs uppercase tracking-widest text-warm-600';
    $inputClass = 'w-full px-4 py-3 bg-white border border-warm-300 text-dark-800 placeholder-warm-400 focus:outline-none focus:border-dark-800 focus:ring-0 transition-colors';

    $howSteps = [];
    foreach (array_keys(__('gifts.page.how_steps')) as $i) {
        $howSteps[] = [
            't' => __("gifts.page.how_steps.$i.t", ['day' => $cycle->shipmentDate->day]),
            'd' => __("gifts.page.how_steps.$i.d", ['url' => \App\Models\GiftPurchase::redeemDisplayFor($currentLocale), 'cutoff_day' => $cycle->cutoffAt->day]),
        ];
    }
@endphp

@section('content')
<div style="background-color: #e5e6df;">

    {{-- Hero --}}
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
        <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
            <span class="text-dark-800">{{ __('gifts.page.hero_title')[0] }}</span><br>
            <span class="text-primary-500">{{ __('gifts.page.hero_title')[1] }}</span>
        </h1>
        <div class="flex justify-end">
            <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-600 max-w-md text-right leading-relaxed">{{ __('gifts.page.hero_text') }}</p>
        </div>
    </div>

    {{-- Sestavení dárku --}}
    <form id="gift-form" method="POST" action="{{ localizedRoute('gifts.cart.add') }}" data-gift-form data-gift-config='@json($jsConfig)' class="py-12 sm:py-16 lg:py-20" novalidate>
        @csrf
        <div class="max-w-screen-xl mx-auto px-4 md:px-8">

            @if($errors->any())
            <div class="mb-8 bg-white border border-primary-700 px-6 py-4 text-primary-700" role="alert">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex flex-wrap items-start gap-12">

                {{-- Levý sloupec: náhled certifikátu, cena, tlačítko --}}
                <aside class="flex-[5_1_380px] min-w-0 min-[1012px]:sticky min-[1012px]:top-[88px]" aria-label="{{ __('gifts.page.preview_label') }}">
                    <div class="flex justify-between gap-4 mb-3 text-xs uppercase tracking-widest text-warm-600">
                        <span>{{ __('gifts.page.preview_label') }}</span>
                        <span>{{ __('gifts.page.preview_format') }}</span>
                    </div>

                    <x-gift-certificate :subscription="$previewSubscription" :voucher="$previewVoucher" :type="$oldType" />

                    <div class="mt-8 pt-6 border-t-2 border-primary-500">
                        <span class="block font-display text-xl uppercase text-primary-500">{{ __('gifts.page.total') }}</span>
                        <div class="mt-2 flex items-baseline gap-1">
                            <span class="font-display text-5xl leading-none text-dark-800" data-gift-total>{{ $fmt($initialTotal) }}</span>
                        </div>
                        <p class="mt-2 text-xs uppercase tracking-widest text-warm-600" data-gift-total-note>
                            @if($oldType === 'subscription')
                            {{ __('gifts.page.total_note_subscription', ['box' => \App\Helpers\BoxPricing::SIZES[$oldBags], 'price' => $fmt(\App\Helpers\BoxPricing::boxPrice($oldBags, $currency)), 'months' => $oldMonths]) }}
                            @else
                            {{ __('gifts.page.total_note_voucher') }}
                            @endif
                        </p>
                    </div>

                    <button type="submit" class="mt-6 w-full py-4 bg-dark-800 text-white font-display text-lg uppercase tracking-wide hover:bg-black transition-colors duration-200 flex items-center justify-center gap-3">
                        <span>{{ __('gifts.page.cta') }}</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </button>
                    <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 mt-4 text-xs text-warm-600 uppercase tracking-widest">
                        @foreach(__('gifts.page.bullets') as $bullet)
                        <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-primary-500" aria-hidden="true"></span>{{ $bullet }}</span>
                        @endforeach
                    </div>
                </aside>

                {{-- Pravý sloupec: kroky --}}
                <div class="flex-[7_1_520px] min-w-0">

                    {{-- 01 Co chcete darovat --}}
                    <fieldset class="mb-12 border-t-2 border-primary-500 pt-6">
                        <div class="mb-6">
                            <div class="flex items-baseline gap-4 mb-1">
                                <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl">01</span>
                                <legend class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.page.step_type') }}</legend>
                            </div>
                            <p class="text-sm text-warm-600 font-light">{{ __('gifts.page.step_type_sub') }}</p>
                        </div>
                        <label class="{{ $rowClass }}">
                            <input type="radio" name="type" value="subscription" class="sr-only" @checked($oldType === 'subscription')>
                            <span class="{{ $barClass }}" aria-hidden="true"></span>
                            <span class="flex-1 min-w-0">
                                <span class="block font-display text-2xl uppercase text-dark-800">{{ __('gifts.page.type_subscription') }}</span>
                                <span class="block text-xs font-light text-warm-600">{{ __('gifts.page.type_subscription_desc') }}</span>
                            </span>
                            <span class="font-display text-lg text-dark-800 whitespace-nowrap">{{ __('gifts.page.from', ['price' => $fmt($cheapest * min($months))]) }}</span>
                        </label>
                        <label class="{{ $rowClass }}">
                            <input type="radio" name="type" value="voucher" class="sr-only" @checked($oldType === 'voucher')>
                            <span class="{{ $barClass }}" aria-hidden="true"></span>
                            <span class="flex-1 min-w-0">
                                <span class="block font-display text-2xl uppercase text-dark-800">{{ __('gifts.page.type_voucher') }}</span>
                                <span class="block text-xs font-light text-warm-600">{{ __('gifts.page.type_voucher_desc') }}</span>
                            </span>
                            <span class="font-display text-lg text-dark-800 whitespace-nowrap">{{ __('gifts.page.from', ['price' => $fmt($amountOptions[0]['value'] ?? 0)]) }}</span>
                        </label>
                    </fieldset>

                    {{-- Dárkové předplatné: 02 délka, 03 velikost boxu --}}
                    <div data-gift-group="subscription" @if($oldType !== 'subscription') hidden @endif>
                        <fieldset class="mb-12 border-t-2 border-primary-500 pt-6">
                            <div class="mb-6">
                                <div class="flex items-baseline gap-4 mb-1">
                                    <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl">02</span>
                                    <legend class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.page.step_length') }}</legend>
                                </div>
                                <p class="text-sm text-warm-600 font-light">{{ __('gifts.page.step_length_sub') }}</p>
                            </div>
                            @foreach($months as $m)
                            <label class="{{ $rowClass }}">
                                <input type="radio" name="months" value="{{ $m }}" class="sr-only" @checked($oldMonths === (int) $m)>
                                <span class="{{ $barClass }}" aria-hidden="true"></span>
                                <span class="flex-1 min-w-0">
                                    <span class="block font-display text-2xl uppercase text-dark-800">{{ trans_choice('gifts.months', $m, ['count' => $m]) }}</span>
                                    <span class="block text-xs font-light text-warm-600">{{ __('gifts.page.length_desc', ['boxes' => trans_choice('gifts.boxes', $m, ['count' => $m])]) }}</span>
                                </span>
                                <span class="font-display text-lg text-dark-800 whitespace-nowrap" data-gift-length-price="{{ $m }}">{{ $fmt(\App\Helpers\BoxPricing::giftSubscriptionPrice($oldBags, $m, $currency)) }}</span>
                            </label>
                            @endforeach
                        </fieldset>

                        <fieldset class="mb-12 border-t-2 border-primary-500 pt-6">
                            <div class="mb-6">
                                <div class="flex items-baseline gap-4 mb-1">
                                    <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl">03</span>
                                    <legend class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.page.step_size') }}</legend>
                                </div>
                                <p class="text-sm text-warm-600 font-light">{{ __('gifts.page.step_size_sub') }}</p>
                            </div>
                            @foreach($tiers as $tier)
                            <label class="{{ $rowClass }} !py-4">
                                <input type="radio" name="bags" value="{{ $tier['bags'] }}" class="sr-only" @checked($oldBags === $tier['bags'])>
                                <span class="{{ $barClass }}" aria-hidden="true"></span>
                                <span class="flex items-baseline gap-1.5 w-[96px] sm:w-[110px] flex-shrink-0">
                                    <span class="font-display text-5xl leading-none tracking-tight text-dark-800">{{ $tier['code'] }}</span>
                                    <span class="font-display text-lg uppercase text-dark-800">Box</span>
                                </span>
                                <span class="flex-1 min-w-0">
                                    <span class="block text-xs uppercase tracking-widest text-warm-600">{{ __('components.tiers.bags', ['grams' => $tier['grams'], 'count' => $tier['bags']]) }}</span>
                                    <span class="block text-sm font-light text-dark-700">{{ __('gifts.page.size_desc.' . $tier['bags']) }}</span>
                                </span>
                                <span class="flex items-baseline gap-1 whitespace-nowrap">
                                    <span class="font-display text-xl text-dark-800">{{ $fmt($tier['price']) }}</span>
                                    <span class="text-sm font-light text-warm-500">/box</span>
                                </span>
                            </label>
                            @endforeach
                        </fieldset>
                    </div>

                    {{-- Poukaz: 02 hodnota --}}
                    <div data-gift-group="voucher" @if($oldType !== 'voucher') hidden @endif>
                        <fieldset class="mb-12 border-t-2 border-primary-500 pt-6">
                            <div class="mb-6">
                                <div class="flex items-baseline gap-4 mb-1">
                                    <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl">02</span>
                                    <legend class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.page.step_amount') }}</legend>
                                </div>
                                <p class="text-sm text-warm-600 font-light">{{ __('gifts.page.step_amount_sub') }}</p>
                            </div>
                            @foreach($amountOptions as $option)
                            <label class="{{ $rowClass }}">
                                <input type="radio" name="voucher_amount" value="{{ $option['value'] }}" class="sr-only" @checked((float) $oldAmount === (float) $option['value'])>
                                <span class="{{ $barClass }}" aria-hidden="true"></span>
                                <span class="flex-1 min-w-0">
                                    <span class="block font-display text-2xl uppercase text-dark-800">{{ $option['label'] }}</span>
                                    <span class="block text-xs font-light text-warm-600">{{ $option['desc'] }}</span>
                                </span>
                            </label>
                            @endforeach
                            <p class="mt-4 text-sm leading-[22px] text-warm-600">{{ __('gifts.page.amount_note') }}</p>
                        </fieldset>
                    </div>

                    {{-- Pro koho --}}
                    <fieldset class="mb-4 border-t-2 border-primary-500 pt-6">
                        <div class="mb-6">
                            <div class="flex items-baseline gap-4 mb-1">
                                <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl" data-gift-last-step>{{ $oldType === 'subscription' ? '04' : '03' }}</span>
                                <legend class="font-display text-2xl sm:text-3xl md:text-4xl uppercase tracking-tight text-dark-800">{{ __('gifts.page.step_whom') }}</legend>
                            </div>
                            <p class="text-sm text-warm-600 font-light">{{ __('gifts.page.step_whom_sub') }}</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="gift-recipient" class="{{ $labelClass }}">{{ __('gifts.page.recipient_name') }}</label>
                                <input id="gift-recipient" name="recipient_name" type="text" required maxlength="{{ config('gifts.name_max_length') }}" value="{{ old('recipient_name') }}" placeholder="{{ __('gifts.certificate.sample.recipient') }}" data-gift-input="recipient" class="{{ $inputClass }}" autocomplete="off">
                            </div>
                            <div>
                                <label for="gift-giver" class="{{ $labelClass }}">{{ __('gifts.page.giver_name') }}</label>
                                <input id="gift-giver" name="giver_name" type="text" required maxlength="{{ config('gifts.name_max_length') }}" value="{{ old('giver_name') }}" placeholder="{{ __('gifts.certificate.sample.giver') }}" data-gift-input="giver" class="{{ $inputClass }}" autocomplete="name">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="gift-note" class="{{ $labelClass }}">{{ __('gifts.page.note') }}</label>
                            <input id="gift-note" name="note" type="text" maxlength="{{ config('gifts.note_max_length') }}" value="{{ old('note') }}" placeholder="{{ __('gifts.page.note_placeholder') }}" data-gift-input="note" class="{{ $inputClass }}" autocomplete="off">
                        </div>

                        <div role="radiogroup" aria-label="{{ __('gifts.page.step_whom') }}">
                            <label class="{{ $rowClass }}">
                                <input type="radio" name="delivery" value="buyer" class="sr-only" @checked($oldDelivery === 'buyer')>
                                <span class="{{ $barClass }}" aria-hidden="true"></span>
                                <span class="flex-1 min-w-0">
                                    <span class="block font-display text-xl uppercase text-dark-800">{{ __('gifts.page.delivery_buyer') }}</span>
                                    <span class="block text-xs font-light text-warm-600">{{ __('gifts.page.delivery_buyer_desc') }}</span>
                                </span>
                            </label>
                            <label class="{{ $rowClass }}">
                                <input type="radio" name="delivery" value="recipient" class="sr-only" @checked($oldDelivery === 'recipient')>
                                <span class="{{ $barClass }}" aria-hidden="true"></span>
                                <span class="flex-1 min-w-0">
                                    <span class="block font-display text-xl uppercase text-dark-800">{{ __('gifts.page.delivery_recipient') }}</span>
                                    <span class="block text-xs font-light text-warm-600">{{ __('gifts.page.delivery_recipient_desc') }}</span>
                                </span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)] gap-4 mt-6" data-gift-recipient-fields @if($oldDelivery !== 'recipient') hidden @endif>
                            <div>
                                <label for="gift-recipient-email" class="{{ $labelClass }}">{{ __('gifts.page.recipient_email') }}</label>
                                <input id="gift-recipient-email" name="recipient_email" type="email" value="{{ old('recipient_email') }}" placeholder="{{ __('gifts.page.recipient_email_placeholder') }}" class="{{ $inputClass }}" autocomplete="off">
                            </div>
                            <div>
                                <label for="gift-send-at" class="{{ $labelClass }}">{{ __('gifts.page.send_at') }}</label>
                                <input id="gift-send-at" name="send_at" type="date" value="{{ old('send_at', $defaultSendDate) }}" min="{{ now()->toDateString() }}" max="{{ now()->addYear()->toDateString() }}" class="{{ $inputClass }}">
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </form>

    {{-- Co obdarovaný dostane --}}
    <section aria-label="{{ __('gifts.page.gets_title') }}" class="relative overflow-hidden">
        <div class="grid lg:grid-cols-2 max-w-[2400px] mx-auto">
            <div class="relative bg-primary-500">
                <div class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8">
                    <div class="relative aspect-[5/4] overflow-hidden">
                        <img src="/images/kavi-box.jpg" loading="lazy" alt="{{ $currentLocale === 'en' ? 'An open KAVI box with three bags of coffee' : 'Otevřený KAVI box se třemi balíčky kávy' }}" class="h-full w-full object-cover grayscale" />
                        <div class="absolute inset-0 opacity-30 mix-blend-overlay pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 200 200%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%275%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-between p-6 sm:p-8 lg:p-12 xl:p-16">
                <div class="space-y-8">
                    <div>
                        <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-6 leading-tight tracking-tight uppercase">{{ __('gifts.page.gets_title') }}</h2>
                        <p class="text-lg sm:text-xl text-warm-500 leading-relaxed font-light">{{ __('gifts.page.gets_lead') }}</p>
                    </div>
                    <div class="space-y-4">
                        @foreach(__('gifts.page.gets_arrows') as $arrow)
                        <div class="flex gap-3 items-start">
                            <span class="text-primary-500" aria-hidden="true">→</span>
                            <p class="text-warm-500 font-light">{{ $arrow }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-12 pt-12 border-t border-stone-300 flex items-center justify-between gap-4">
                    <a href="{{ localizedRoute('monthly-feature.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
                        <span>{{ __('gifts.page.gets_coffee') }}</span>
                        <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
                        <span>{{ __('gifts.page.gets_self') }}</span>
                        <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Jak to funguje --}}
    <section aria-label="{{ __('gifts.page.how_title') }}" class="pt-20 sm:pt-24 lg:pt-36 pb-20 lg:pb-28">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="mb-16 sm:mb-20 max-w-2xl">
                <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-4 tracking-tight uppercase leading-tight sm:leading-[0.95]">{{ __('gifts.page.how_title') }}</h2>
                <p class="text-lg sm:text-xl text-warm-500 font-light">{{ __('gifts.page.how_lead') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                @foreach($howSteps as $i => $step)
                <div class="border-t-2 border-primary-500 pt-8">
                    <span class="block mb-4 font-display text-5xl font-normal text-primary-500">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $step['t'] }}</h3>
                    <p class="text-warm-500 leading-relaxed font-light">{{ $step['d'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Firemní dárky --}}
    <div class="bg-primary-500 py-10">
        <div class="max-w-screen-xl mx-auto px-4 md:px-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <p class="text-xs uppercase tracking-widest text-dark-800">{{ __('gifts.page.corporate') }}</p>
            <a href="mailto:{{ $contactEmail }}" class="text-xs uppercase tracking-widest text-dark-800 hover:text-white transition-colors">{{ __('gifts.page.corporate_cta', ['email' => $contactEmail]) }} &rarr;</a>
        </div>
    </div>

    {{-- Časté dotazy k dárkům --}}
    <x-faq :items="$faq" :title="__('gifts.page.faq_title')" :lead="__('components.faq.contact', ['email' => $contactEmail])" />

</div>
@endsection
