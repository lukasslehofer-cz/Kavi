{{--
    Odpočet do uzávěrky objednávek (konfigurátor).

    cycle: App\Helpers\ShipmentCycle, typicky ShipmentCycles::current()
    Hodnoty se vykreslí už na serveru, JS (resources/js/components/countdown.js) je
    pak přepočítává každou sekundu podle času serveru, ne podle hodin v prohlížeči.
--}}
@props(['cycle'])

@php
    $now = now();
    $left = max(0, $cycle->cutoffAt->getTimestamp() - $now->getTimestamp());
    $values = [
        'days' => intdiv($left, 86400),
        'hours' => intdiv($left % 86400, 3600),
        'minutes' => intdiv($left % 3600, 60),
        'seconds' => $left % 60,
    ];
    $plural = __('components.countdown.plural');
    $pluralize = function (string $unit, int $n) use ($plural, $currentLocale) {
        $forms = $plural[$unit];
        $index = $n === 1 ? 0 : (($currentLocale !== 'en' && $n >= 2 && $n <= 4) ? 1 : 2);
        return $n . ' ' . $forms[$index];
    };
    $ariaLabel = __('components.countdown.aria', [
        'days' => $pluralize('days', $values['days']),
        'hours' => $pluralize('hours', $values['hours']),
        'minutes' => $pluralize('minutes', $values['minutes']),
    ]);
@endphp

<div role="timer"
     aria-label="{{ $ariaLabel }}"
     data-countdown
     data-deadline="{{ $cycle->cutoffAt->toIso8601String() }}"
     data-server-now="{{ $now->toIso8601String() }}"
     data-locale="{{ $currentLocale }}"
     data-aria-template="{{ __('components.countdown.aria') }}"
     data-plural='@json($plural)'
     data-passed="{{ __('components.countdown.passed') }}"
     {{ $attributes->merge(['class' => 'flex items-stretch w-fit max-w-full']) }}>
    <div class="w-10 sm:w-12 flex-shrink-0 bg-primary-500 flex items-center justify-center">
        <svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1c1c1c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6v6l4 2M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z"></path></svg>
    </div>
    <div class="min-w-0 bg-dark-800 text-white pt-4 pr-6 pb-3.5 pl-5 flex flex-col gap-2.5">
        <span class="text-xs uppercase tracking-widest text-warm-300" data-countdown-label>{{ __('components.countdown.cutoff', ['date' => $cycle->cutoffShort()]) }}</span>
        <div class="flex items-start gap-1.5" aria-hidden="true">
            @foreach($values as $unit => $value)
            <div class="flex flex-col items-center min-w-[42px] min-[481px]:min-w-[60px]">
                <span class="font-display text-[32px] min-[481px]:text-[44px] leading-none tracking-[-0.02em] tabular-nums" data-countdown-unit="{{ $unit }}">{{ str_pad($value, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="mt-1.5 text-[11px] leading-4 uppercase tracking-widest text-warm-300">{{ __('components.countdown.units.' . $unit) }}</span>
            </div>
            @if(! $loop->last)
            <span class="font-display text-[32px] min-[481px]:text-[44px] leading-none text-primary-500">:</span>
            @endif
            @endforeach
        </div>
    </div>
</div>
