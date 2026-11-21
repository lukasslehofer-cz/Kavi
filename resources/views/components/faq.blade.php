{{--
    Časté dotazy: řádky "číslo / otázka / odpověď".

    items: [['q' => 'Otázka', 'a' => 'Odpověď', 'link' => ['label' => '…', 'href' => '…'] | null], …]
    Odpovědi jsou čistý text (escapuje se), odkaz je volitelný a přidá se za odpověď.
    Stejné pole se dá poslat i do JSON-LD FAQPage, takže se texty nerozejdou.

    accordion: otázky jako <details>/<summary> (první otevřená, ikona +/−), bez JS
    a ovladatelné klávesnicí. Bez něj jsou odpovědi vidět rovnou vedle otázek.
--}}
@props([
    'items' => [],
    'title' => null,
    'lead' => null,
    'id' => null,
    'padding' => 'pt-20 sm:pt-24 lg:pt-36 pb-20 sm:pb-24 lg:pb-36',
    'accordion' => false,
    // Velikost nadpisu, když ho stránka má jinou (Pro firmy: 36/48 px)
    'titleClass' => 'text-3xl sm:text-4xl md:text-5xl leading-tight sm:leading-[0.95]',
])

@php $title = $title ?? __('components.faq.title'); @endphp

<section @if($id) id="{{ $id }}" @endif aria-label="{{ $title }}" class="{{ $padding }}" style="background-color: #e5e6df;">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="max-w-2xl {{ $lead ? 'mb-12 sm:mb-16' : 'mb-10 sm:mb-12' }}">
            <h2 class="font-display {{ $titleClass }} font-normal text-dark-800 tracking-tight uppercase {{ $lead ? 'mb-4' : '' }}">{{ $title }}</h2>
            @if($lead)
            <p class="text-lg sm:text-xl text-warm-500 font-light">{{ $lead }}</p>
            @endif
        </div>

        <div class="border-b border-warm-300">
            @foreach($items as $item)
            @if($accordion)
            <details class="group border-t border-warm-300" @if($loop->first) open @endif>
                <summary class="group/q grid grid-cols-[36px_minmax(0,1fr)_24px] sm:grid-cols-[64px_minmax(0,1fr)_32px] items-baseline gap-3 sm:gap-6 py-7 cursor-pointer list-none [&::-webkit-details-marker]:hidden focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500">
                    <span class="text-xs uppercase tracking-widest text-primary-500">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="font-display text-lg leading-7 uppercase text-dark-800 transition-colors duration-200 group-hover/q:text-primary-500">{{ $item['q'] }}</span>
                    <span class="font-display text-[28px] leading-7 text-right text-primary-500" aria-hidden="true"><span class="group-open:hidden">+</span><span class="hidden group-open:inline">&minus;</span></span>
                </summary>
                <p class="max-w-[904px] pb-6 pl-12 sm:pb-7 sm:pl-[88px] sm:pr-14 text-base leading-[26px] font-light text-warm-600">
                    {{ $item['a'] }}
                    @if(! empty($item['link']))
                    <a href="{{ $item['link']['href'] }}" class="text-primary-500 underline underline-offset-2 hover:text-primary-600 transition-colors duration-200">{{ $item['link']['label'] }}</a>
                    @endif
                </p>
            </details>
            @else
            <div class="grid grid-cols-1 md:grid-cols-[64px_minmax(0,4fr)_minmax(0,7fr)] gap-2 md:gap-6 py-8 border-t border-warm-300">
                <span class="text-xs uppercase tracking-widest text-primary-500">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <h3 class="font-display text-lg leading-7 uppercase text-dark-800">{{ $item['q'] }}</h3>
                <p class="text-sm leading-[23px] text-warm-600">
                    {{ $item['a'] }}
                    @if(! empty($item['link']))
                    <a href="{{ $item['link']['href'] }}" class="text-primary-500 underline underline-offset-2 hover:text-primary-600 transition-colors duration-200">{{ $item['link']['label'] }}</a>
                    @endif
                </p>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
