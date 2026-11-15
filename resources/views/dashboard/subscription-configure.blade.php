@extends('layouts.dashboard')

@section('title', __('dashboard.config_change.title'))

@section('content')
@php
    $current = $form['current'];
    $currency = $form['currency'];
    $frequencyLocked = $form['eligibility']['frequency_locked'];
    $frequencyBlockedByAddons = $form['frequency_blocked_by_addons'];

    // Po neúspěšném uložení se vrací to, co zákazník zvolil (withInput), jinak stávající stav
    $selected = [
        'amount' => (int) old('amount', $current['amount']),
        'type' => old('type', $current['type']),
        'isDecaf' => old('isDecaf', $current['isDecaf'] ? '1' : '0') === '1',
        'frequency' => (int) old('frequency', $form['current_frequency']),
        'mixEspresso' => (int) old('mix.espresso', $current['mix']['espresso'] ?? 0),
        'mixFilter' => (int) old('mix.filter', $current['mix']['filter'] ?? 0),
    ];

    $detail = fn (string $key, array $replace = []) => __('confirmation.subscription_details.'.$key, $replace);
    $currentRows = \App\Helpers\SubscriptionSummary::configRows($current, $form['current_frequency']);
    $currentPrice = $form['prices'][$current['amount'].'-'.(int) $current['isDecaf']];
    $backUrl = localizedRoute('dashboard.subscription', $viewAsQuery).'#subscription-'.$subscription->id;

    // Data pro skript níže - skládají se tady, @json snese jen hotovou proměnnou
    $jsData = [
        'prices' => $form['prices'],
        'dates' => $form['dates'],
        'current' => [
            'amount' => $current['amount'],
            'type' => $current['type'],
            'isDecaf' => $current['isDecaf'],
            'mixEspresso' => $current['mix']['espresso'] ?? 0,
            'mixFilter' => $current['mix']['filter'] ?? 0,
            'frequency' => $form['current_frequency'],
        ],
        'labels' => [
            'types' => collect(['espresso', 'filter', 'mix'])->mapWithKeys(fn ($type) => [$type => $detail($type)])->all(),
            'decaf' => $detail('incl_decaf'),
            'bags' => collect(\App\Helpers\BoxPricing::SIZES)->keys()->mapWithKeys(fn ($bags) => [
                $bags => $detail('bags_format', ['count' => $bags, 'grams' => \App\Helpers\BoxPricing::grams($bags)]),
            ])->all(),
            'frequencies' => collect(\App\Services\SubscriptionConfigurationService::FREQUENCIES)
                ->mapWithKeys(fn ($frequency) => [$frequency => $detail('frequency_'.$frequency)])->all(),
        ],
    ];

    $optionClass = 'relative flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 cursor-pointer transition hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50 has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-primary-500 has-[:disabled]:cursor-not-allowed has-[:disabled]:opacity-50 has-[:disabled]:hover:border-gray-200';
@endphp

<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <a href="{{ $backUrl }}" class="text-primary-600 hover:text-primary-700 font-medium mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('dashboard.config_change.back') }}
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2">{{ __('dashboard.config_change.title') }}</h1>
        @if($subscription->subscription_number)
        <p class="mt-1 text-sm text-gray-500">{{ $subscription->subscription_number }}</p>
        @endif

        @if($form['mode'] === 'scheduled')
        {{-- Zaplacený box odejde v původní podobě, změna platí až od dalšího --}}
        <div class="mt-4 flex items-start gap-2 rounded-xl bg-amber-50 border border-amber-200 p-3">
            <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-amber-800">
                {{ __('dashboard.config_change.locked_box_notice', ['date' => $form['locked_shipment_date']?->format('j. n. Y')]) }}
            </p>
        </div>
        @else
        <p class="mt-3 text-gray-600 font-light">{{ __('dashboard.config_change.intro_immediate') }}</p>
        @endif

        @if($form['scheduled_change'])
        <p class="mt-3 text-sm text-gray-600">{{ __('dashboard.config_change.scheduled_exists') }}</p>
        @endif
    </div>

    <form id="subscription-change-form" method="POST"
          action="{{ localizedRoute('dashboard.subscription.configure.update', ['subscription' => $subscription->id]) }}">
        @csrf
        @foreach($viewAsQuery as $name => $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <input type="hidden" name="mix[espresso]" id="change-mix-espresso" value="{{ $selected['mixEspresso'] }}">
        <input type="hidden" name="mix[filter]" id="change-mix-filter" value="{{ $selected['mixFilter'] }}">

        {{-- Souhrn vedle formuláře až od xl - vedle menu účtu je na užším displeji sloupec moc úzký --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
            <div class="xl:col-span-2 space-y-6">
                <!-- Velikost boxu -->
                <fieldset class="bg-white rounded-2xl border border-gray-200 p-6">
                    <legend class="sr-only">{{ __('dashboard.config_change.size_title') }}</legend>
                    <h2 class="text-base font-bold text-gray-900 mb-4" aria-hidden="true">{{ __('dashboard.config_change.size_title') }}</h2>
                    <div class="space-y-3">
                        @foreach(\App\Helpers\BoxPricing::SIZES as $bags => $code)
                        <label class="{{ $optionClass }}">
                            <input type="radio" name="amount" value="{{ $bags }}" class="sr-only" @checked($selected['amount'] === $bags) required>
                            <span class="flex items-baseline gap-3">
                                <span class="font-display text-2xl text-gray-900 w-10">{{ $code }}</span>
                                <span class="text-sm text-gray-600">{{ __('dashboard.config_change.size_bags', ['count' => $bags, 'grams' => \App\Helpers\BoxPricing::grams($bags)]) }}</span>
                            </span>
                            <span class="text-sm text-gray-900 whitespace-nowrap">
                                <span class="font-semibold" data-size-price="{{ $bags }}">{{ $form['prices'][$bags.'-'.(int) $selected['isDecaf']]['box_formatted'] }}</span>
                                <span class="text-gray-500">{{ __('dashboard.config_change.per_box') }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </fieldset>

                <!-- Druh kávy -->
                <fieldset class="bg-white rounded-2xl border border-gray-200 p-6">
                    <legend class="sr-only">{{ __('dashboard.config_change.type_title') }}</legend>
                    <h2 class="text-base font-bold text-gray-900 mb-4" aria-hidden="true">{{ __('dashboard.config_change.type_title') }}</h2>
                    <div class="space-y-3">
                        @foreach(['espresso', 'filter', 'mix'] as $type)
                        <label class="{{ $optionClass }}">
                            <input type="radio" name="type" value="{{ $type }}" class="sr-only" @checked($selected['type'] === $type) required>
                            <span>
                                <span class="block font-semibold text-gray-900">{{ $detail($type) }}</span>
                                <span class="block text-sm text-gray-600">{{ __('dashboard.config_change.type_'.$type.'_hint') }}</span>
                            </span>
                        </label>
                        @endforeach
                    </div>

                    <!-- Rozdělení kombinace -->
                    <div id="change-mix-split" class="mt-4 rounded-xl bg-gray-50 border border-gray-200 p-4 {{ $selected['type'] === 'mix' ? '' : 'hidden' }}">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('dashboard.config_change.mix_title') }}</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach(['espresso', 'filter'] as $part)
                            <div class="rounded-xl bg-white border border-gray-200 p-3">
                                <span class="block text-sm text-gray-700 mb-2">{{ $detail($part) }}</span>
                                <div class="flex items-center justify-between gap-2">
                                    <button type="button" data-mix-step="{{ $part }}" data-mix-delta="-1"
                                            aria-label="{{ $detail($part) }} −1"
                                            class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="text-lg font-semibold text-gray-900 tabular-nums" data-mix-count="{{ $part }}" aria-live="polite">0</span>
                                    <button type="button" data-mix-step="{{ $part }}" data-mix-delta="1"
                                            aria-label="{{ $detail($part) }} +1"
                                            class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Decaf -->
                    <input type="hidden" name="isDecaf" value="0">
                    <label class="mt-4 flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 cursor-pointer transition hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                        <span class="flex items-center gap-3">
                            <input type="checkbox" name="isDecaf" value="1" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500" @checked($selected['isDecaf'])>
                            <span class="text-sm font-medium text-gray-900">{{ __('dashboard.config_change.decaf_label') }}</span>
                        </span>
                        <span class="text-sm text-gray-600 whitespace-nowrap">+{{ $form['decaf_surcharge_formatted'] }}</span>
                    </label>
                </fieldset>

                <!-- Frekvence -->
                <fieldset class="bg-white rounded-2xl border border-gray-200 p-6">
                    <legend class="sr-only">{{ __('dashboard.config_change.frequency_title') }}</legend>
                    <h2 class="text-base font-bold text-gray-900 mb-4" aria-hidden="true">{{ __('dashboard.config_change.frequency_title') }}</h2>

                    @if($frequencyLocked || $frequencyBlockedByAddons)
                    {{-- Vypnuté radio se neodesílá, frekvenci proto nese skryté pole --}}
                    <input type="hidden" name="frequency" value="{{ $form['current_frequency'] }}">
                    @endif

                    <div class="space-y-3">
                        @foreach(\App\Services\SubscriptionConfigurationService::FREQUENCIES as $frequency)
                        @php $isCurrent = $frequency === $form['current_frequency']; @endphp
                        <label class="{{ $optionClass }}">
                            <input type="radio" name="frequency" value="{{ $frequency }}" class="sr-only"
                                   @checked(($frequencyLocked || $frequencyBlockedByAddons) ? $isCurrent : $selected['frequency'] === $frequency)
                                   @disabled(($frequencyLocked || $frequencyBlockedByAddons) && ! $isCurrent)>
                            <span class="font-semibold text-gray-900">{{ $detail('frequency_'.$frequency) }}</span>
                        </label>
                        @endforeach
                    </div>

                    @if($frequencyLocked)
                    <p class="mt-3 text-sm text-gray-600">{{ __('dashboard.config_change.frequency_locked_paused') }}</p>
                    @elseif($frequencyBlockedByAddons)
                    <p class="mt-3 text-sm text-gray-600">{{ __('dashboard.config_change.frequency_blocked_addons') }}</p>
                    @endif
                </fieldset>
            </div>

            <!-- Souhrn -->
            <div class="xl:sticky xl:top-6 bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4">{{ __('dashboard.config_change.summary_title') }}</h2>

                <div class="text-sm">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">{{ __('dashboard.config_change.summary_current') }}</p>
                    <dl class="space-y-1">
                        @foreach($currentRows as $row)
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-600">{{ $row['label'] }}</dt>
                            <dd class="text-gray-900 text-right">{{ $row['value'] }}</dd>
                        </div>
                        @endforeach
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-600">{{ __('dashboard.total') }}</dt>
                            <dd class="text-gray-900 text-right tabular-nums">{{ $currentPrice['total_formatted'] }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="mt-5 pt-5 border-t border-gray-200 text-sm" aria-live="polite">
                    <p class="text-xs uppercase tracking-wide text-primary-600 font-semibold mb-2">{{ __('dashboard.config_change.summary_new') }}</p>
                    <dl class="space-y-1">
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-600">{{ $detail('coffee_type') }}</dt>
                            <dd class="text-gray-900 text-right font-medium" data-summary="type"></dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-600">{{ $detail('quantity') }}</dt>
                            <dd class="text-gray-900 text-right font-medium" data-summary="amount"></dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-600">{{ $detail('frequency') }}</dt>
                            <dd class="text-gray-900 text-right font-medium" data-summary="frequency"></dd>
                        </div>
                    </dl>

                    <dl class="mt-4 pt-4 border-t border-gray-200 space-y-1">
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-600">{{ __('dashboard.subscription_price') }}</dt>
                            <dd class="text-gray-900 text-right tabular-nums" data-summary="box"></dd>
                        </div>
                        <div class="flex justify-between gap-4" data-summary-row="discount">
                            <dt class="text-gray-600">{{ __('dashboard.discount') }}</dt>
                            <dd class="text-green-700 text-right tabular-nums" data-summary="discount"></dd>
                        </div>
                        <div class="flex justify-between gap-4" data-summary-row="shipping">
                            <dt class="text-gray-600">{{ __('dashboard.shipping_price') }}</dt>
                            <dd class="text-gray-900 text-right tabular-nums" data-summary="shipping"></dd>
                        </div>
                        <div class="flex justify-between gap-4 pt-2 border-t border-gray-200">
                            <dt class="text-gray-900 font-semibold">{{ __('dashboard.total') }}</dt>
                            <dd class="text-lg font-bold text-primary-600 text-right tabular-nums" data-summary="total"></dd>
                        </div>
                    </dl>

                    <dl class="mt-4 pt-4 border-t border-gray-200 space-y-1">
                        <div class="flex justify-between gap-4" data-summary-row="box-date">
                            <dt class="text-gray-600">{{ __('dashboard.config_change.applies_from') }}</dt>
                            <dd class="text-gray-900 text-right font-medium" data-summary="box-date"></dd>
                        </div>
                        <div class="flex justify-between gap-4" data-summary-row="billing-date">
                            <dt class="text-gray-600">{{ __('dashboard.config_change.next_payment') }}</dt>
                            <dd class="text-gray-900 text-right font-medium" data-summary="billing-date"></dd>
                        </div>
                    </dl>
                </div>

                <button type="submit" id="change-submit"
                        class="mt-6 w-full text-center px-4 py-3 text-sm bg-primary-600 text-white rounded-full hover:bg-primary-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-primary-600">
                    {{ __('dashboard.config_change.submit') }}
                </button>
                <p id="change-no-changes" class="mt-2 text-xs text-gray-500 text-center">{{ __('dashboard.config_change.no_changes') }}</p>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('subscription-change-form');
    if (!form) return;

    // Ceny a termíny jsou předpočítané na serveru (měna předplatného, ceník, kalendář
    // rozesílek) - tady se z nich jen vybírá podle zvolených hodnot.
    const data = @json($jsData);

    const mixEspressoInput = document.getElementById('change-mix-espresso');
    const mixFilterInput = document.getElementById('change-mix-filter');
    const mixBlock = document.getElementById('change-mix-split');
    const submit = document.getElementById('change-submit');
    const noChanges = document.getElementById('change-no-changes');

    let mixEspresso = parseInt(mixEspressoInput.value, 10) || 0;
    let mixFilter = parseInt(mixFilterInput.value, 10) || 0;

    const checkedValue = name => form.querySelector('input[name="' + name + '"]:checked')?.value;
    const summary = key => form.querySelector('[data-summary="' + key + '"]');
    const summaryRow = key => form.querySelector('[data-summary-row="' + key + '"]');

    function state() {
        return {
            amount: parseInt(checkedValue('amount'), 10),
            type: checkedValue('type'),
            isDecaf: form.querySelector('input[type="checkbox"][name="isDecaf"]').checked,
            frequency: parseInt(checkedValue('frequency'), 10),
        };
    }

    // Kombinace musí dát dohromady velikost boxu a obsahovat obě kávy
    function fitMix(amount) {
        if (mixEspresso < 1 || mixFilter < 1 || mixEspresso + mixFilter !== amount) {
            mixEspresso = Math.min(Math.max(mixEspresso || Math.ceil(amount / 2), 1), amount - 1);
            mixFilter = amount - mixEspresso;
        }
    }

    function render() {
        const s = state();
        const isMix = s.type === 'mix';

        if (isMix) {
            fitMix(s.amount);
        }

        mixBlock.classList.toggle('hidden', !isMix);
        mixEspressoInput.value = isMix ? mixEspresso : 0;
        mixFilterInput.value = isMix ? mixFilter : 0;

        form.querySelector('[data-mix-count="espresso"]').textContent = mixEspresso;
        form.querySelector('[data-mix-count="filter"]').textContent = mixFilter;
        form.querySelectorAll('[data-mix-step]').forEach(button => {
            const delta = parseInt(button.dataset.mixDelta, 10);
            const own = button.dataset.mixStep === 'espresso' ? mixEspresso : mixFilter;
            const other = button.dataset.mixStep === 'espresso' ? mixFilter : mixEspresso;
            button.disabled = delta > 0 ? other <= 1 : own <= 1;
        });

        // Ceny u velikostí se mění s decaf
        form.querySelectorAll('[data-size-price]').forEach(el => {
            el.textContent = data.prices[el.dataset.sizePrice + '-' + (s.isDecaf ? 1 : 0)].box_formatted;
        });

        let typeLabel = data.labels.types[s.type];
        if (isMix) {
            typeLabel += ' (' + mixEspresso + '× ' + data.labels.types.espresso + ', ' + mixFilter + '× ' + data.labels.types.filter + ')';
        }
        if (s.isDecaf) {
            typeLabel += ' ' + data.labels.decaf;
        }

        const price = data.prices[s.amount + '-' + (s.isDecaf ? 1 : 0)];
        const dates = data.dates[s.frequency] || {};

        summary('type').textContent = typeLabel;
        summary('amount').textContent = data.labels.bags[s.amount];
        summary('frequency').textContent = data.labels.frequencies[s.frequency];
        summary('box').textContent = price.box_formatted;
        summary('discount').textContent = '−' + price.discount_formatted;
        summary('shipping').textContent = price.shipping_formatted;
        summary('total').textContent = price.total_formatted;
        summaryRow('discount').classList.toggle('hidden', !(price.discount > 0));
        summaryRow('shipping').classList.toggle('hidden', !(price.shipping > 0));
        summary('box-date').textContent = dates.box || '';
        summary('billing-date').textContent = dates.billing || '';
        summaryRow('box-date').classList.toggle('hidden', !dates.box);
        summaryRow('billing-date').classList.toggle('hidden', !dates.billing);

        const c = data.current;
        const changed = s.amount !== c.amount
            || s.type !== c.type
            || s.isDecaf !== c.isDecaf
            || s.frequency !== c.frequency
            || (isMix && (mixEspresso !== c.mixEspresso || mixFilter !== c.mixFilter));

        submit.disabled = !changed;
        noChanges.classList.toggle('hidden', changed);
    }

    form.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
        input.addEventListener('change', render);
    });

    form.querySelectorAll('[data-mix-step]').forEach(button => {
        button.addEventListener('click', function () {
            const delta = parseInt(this.dataset.mixDelta, 10);
            const espressoDelta = this.dataset.mixStep === 'espresso' ? delta : -delta;

            if (mixEspresso + espressoDelta < 1 || mixFilter - espressoDelta < 1) {
                return;
            }

            mixEspresso += espressoDelta;
            mixFilter -= espressoDelta;
            render();
        });
    });

    render();
});
</script>
@endsection
