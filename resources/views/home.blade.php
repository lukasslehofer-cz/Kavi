@extends('layouts.app')

@section('title', $currentLocale === 'en' ? 'Coffee Subscription | Specialty Coffee | KAVI' : 'Kávové předplatné | Výběrová káva | KAVI.cz')

@section('meta_description')
{{ $currentLocale === 'en' ? 'Discover the best specialty coffee from all over Europe. Freshly roasted, carefully selected, delivered to your door. Flexible subscription, shipped with care.' : 'Objevte tu nejlepší výběrovou kávu z celé Evropy. Čerstvě pražená, pečlivě vybraná, doručená přímo k vám. Flexibilní předplatné s dopravou zdarma.' }}
@endsection

@section('og_title')
{{ $currentLocale === 'en' ? 'KAVI - Specialty Coffee Subscription from Europe' : 'KAVI.cz - Kávové předplatné s výběrovou kávou z Evropy' }}
@endsection

@section('og_description')
{{ $currentLocale === 'en' ? 'Premium specialty coffee delivered monthly. Discover unique flavors from the best European roasteries. Shipped with care, cancel anytime.' : 'Prémiová výběrová káva doručovaná měsíčně. Objevte jedinečné chutě z nejlepších evropských pražíren. Doprava zdarma, zrušení kdykoliv.' }}
@endsection

@section('structured_data')
@php
    $siteUrl = $currentLocale === 'en' ? 'https://kavibox.com' : 'https://kavi.cz';
    $siteName = $currentLocale === 'en' ? 'KAVI' : 'KAVI.cz';
    $siteDescription = $currentLocale === 'en' 
        ? 'Premium specialty coffee subscription. Freshly roasted coffee from the best European roasteries delivered to your door.'
        : 'Prémiové kávové předplatné s výběrovou kávou. Čerstvě pražená káva z nejlepších evropských pražíren doručená přímo k vám.';
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@graph": [
        {
            "@type": "Organization",
            "@id": "{{ $siteUrl }}/#organization",
            "name": "{{ $siteName }}",
            "url": "{{ $siteUrl }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ $siteUrl }}/images/kavi-logo-black.png",
                "width": 200,
                "height": 60
            },
            "description": "{{ $siteDescription }}",
            "sameAs": [
                "https://www.instagram.com/kavi.cz",
                "https://www.facebook.com/kavovepredplatne"
            ],
            "contactPoint": {
                "@type": "ContactPoint",
                "contactType": "customer service",
                "email": "{{ $currentLocale === 'en' ? 'info@kavibox.com' : 'info@kavi.cz' }}",
                "availableLanguage": ["{{ $currentLocale === 'en' ? 'English' : 'Czech' }}"]
            }
        },
        {
            "@type": "WebSite",
            "@id": "{{ $siteUrl }}/#website",
            "url": "{{ $siteUrl }}",
            "name": "{{ $siteName }}",
            "description": "{{ $siteDescription }}",
            "publisher": {
                "@id": "{{ $siteUrl }}/#organization"
            },
            "inLanguage": "{{ $currentLocale === 'en' ? 'en-US' : 'cs-CZ' }}"
        },
        {
            "@type": "WebPage",
            "@id": "{{ $siteUrl }}/#webpage",
            "url": "{{ $siteUrl }}",
            "name": "{{ $currentLocale === 'en' ? 'Coffee Subscription | Specialty Coffee | KAVI' : 'Kávové předplatné | Výběrová káva | KAVI.cz' }}",
            "isPartOf": {
                "@id": "{{ $siteUrl }}/#website"
            },
            "about": {
                "@id": "{{ $siteUrl }}/#organization"
            },
            "description": "{{ $siteDescription }}",
            "inLanguage": "{{ $currentLocale === 'en' ? 'en-US' : 'cs-CZ' }}"
        },
        {!! json_encode([
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
            ], $faq),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
    ]
}
</script>
@endsection

@section('content')
<div class="overflow-hidden">

<!-- Hero Section - Editorial Magazine Style -->
<div class="relative h-[85vh] sm:h-[90vh] overflow-hidden bg-dark-800">
    <!-- Background Image/Video -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-cover bg-center grayscale" style="background-image: url('/images/kavi-intro-video.jpg');"></div>
        
        <style>
            #hero-video {
                pointer-events: none;
                filter: grayscale(100%);
            }
            #hero-video::-webkit-media-controls {
                display: none !important;
            }
            #hero-video::-webkit-media-controls-start-playback-button {
                display: none !important;
                -webkit-appearance: none;
            }
            /* Grain overlay */
            .grain-overlay {
                position: absolute;
                inset: 0;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='5' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
                opacity: 0.35;
                pointer-events: none;
                mix-blend-mode: overlay;
            }
        </style>
        
        <video 
            id="hero-video"
            autoplay 
            loop 
            muted 
            playsinline
            preload="auto"
            class="absolute inset-0 w-full h-full object-cover grayscale"
        >
            <source src="/images/kavi-intro-video.mp4" type="video/mp4">
        </video>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('hero-video');
                if (video) {
                    video.setAttribute('playsinline', '');
                    video.setAttribute('muted', '');
                    video.muted = true;
                    video.playsInline = true;
                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(error => {
                            document.body.addEventListener('touchstart', function() {
                                video.play();
                            }, { once: true });
                        });
                    }
                }
            });
        </script>
    </div>
    
    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black/60"></div>

    <!-- Grain texture overlay -->
    <div class="grain-overlay"></div>

    <!-- Content - Full screen editorial layout -->
    <div class="relative h-full flex flex-col justify-between p-6 sm:p-8 lg:p-10">
        
        <!-- Top row - box měsíce a jeho pražírny -->
        <div class="flex justify-end">
            <div class="text-xs uppercase tracking-widest text-white/60 text-right max-w-[220px] leading-5">
                @if(! $lineup->isEmpty())
                {{ \Illuminate\Support\Str::ucfirst($lineup->monthName('adjective')) }} box: {{ $lineup->roasteryNames() }}
                @else
                {{ $currentLocale === 'en' ? 'Specialty coffee subscription from Europe\'s finest roasters.' : 'Předplatné výběrové kávy z nejlepších evropských pražíren.' }}
                @endif
            </div>
        </div>

        <!-- Center - Main typography -->
        <div class="flex-1 flex flex-col justify-center py-6">
            <!-- Large headline -->
            <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal text-white uppercase leading-[0.95] sm:leading-[0.9] tracking-tight">
                @if($currentLocale === 'en')
                Three European<br/>
                <span class="text-primary-500">roasters</span><br/>
                every month
                @else
                Tři evropské<br/>
                <span class="text-primary-500">pražírny</span><br/>
                každý měsíc
                @endif
            </h1>

            <p class="mt-6 text-xs uppercase tracking-widest text-white/60 max-w-[280px] leading-5">
                {{ $currentLocale === 'en'
                    ? 'Specialty coffee from micro-roasters you rarely find in local shops. No commitment.'
                    : 'Výběrová káva z mikropražíren, které v Česku běžně nekoupíte. Doprava zdarma, bez závazku.' }}
            </p>
        </div>

        <!-- Bottom row - CTA vlevo, hodnocení z Googlu vpravo -->
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-6">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-8">
                <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-white font-display uppercase tracking-widest hover:text-primary-500 transition-all">
                    <span>{{ $currentLocale === 'en' ? 'Build your box' : 'Sestavit box' }}</span>
                    <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
                </a>
                @if($giftsEnabled)
                <a href="{{ localizedRoute('gifts.index') }}" class="group inline-flex items-center gap-2 text-white/60 font-display uppercase tracking-widest hover:text-white transition-all">
                    <span>{{ $currentLocale === 'en' ? 'Give a subscription' : 'Darovat předplatné' }}</span>
                    <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
                </a>
                @else
                <a href="{{ localizedRoute('products.index') }}" class="group inline-flex items-center gap-2 text-white/60 font-display uppercase tracking-widest hover:text-white transition-all">
                    <span>{{ $currentLocale === 'en' ? 'Shop coffee' : 'Obchod' }}</span>
                    <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
                </a>
                @endif
            </div>

            @if($rating)
            @php $ratingText = \App\Helpers\RatingFormat::average($rating['average']); @endphp
            <a href="#recenze" class="flex items-center gap-4" aria-label="{{ __('components.rating.aria', ['rating' => $ratingText, 'count' => $rating['count']]) }}">
                <span class="font-display text-5xl sm:text-[56px] leading-none tracking-tight text-white">{{ $ratingText }}</span>
                <span class="flex flex-col gap-1.5" aria-hidden="true">
                    <span class="text-xl leading-none tracking-[3px]">
                        @for($i = 1; $i <= 5; $i++)<span class="{{ $i <= round($rating['average']) ? 'text-primary-500' : 'text-white/30' }}">&#9733;</span>@endfor
                    </span>
                    <span class="text-xs uppercase tracking-widest text-white/80">{{ trans_choice('components.rating.count', $rating['count'], ['count' => $rating['count']]) }}</span>
                </span>
            </a>
            @endif
        </div>

    </div>
</div>

<!-- Káva měsíce -->
<x-coffee-of-month :lineup="$lineup" />

<!-- Co přijde domů - two-tone -->
<div class="relative overflow-hidden bg-[#e5e6df]">
  <div class="grid lg:grid-cols-2 max-w-[2400px] mx-auto">

    <div class="relative bg-primary-500">
      <div class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8">
        <div class="relative aspect-[5/4] overflow-hidden">
          <img src="/images/kavi-box.jpg" loading="lazy" alt="{{ $currentLocale === 'en' ? 'An open KAVI box with three bags of coffee' : 'Otevřený KAVI box se třemi balíčky kávy' }}" class="h-full w-full object-cover grayscale" />
          <div class="absolute inset-0 opacity-30 mix-blend-overlay pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 200 200%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%275%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>
        </div>
      </div>
    </div>

    <div class="bg-[#e5e6df] flex flex-col justify-between p-6 sm:p-8 lg:p-12 xl:p-16">
      <div class="space-y-8">
        <div>
          <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-6 leading-tight tracking-tight uppercase">
            {{ $currentLocale === 'en' ? 'What arrives at your door' : 'Co přijde domů' }}
          </h2>
          <p class="text-lg sm:text-xl text-warm-500 leading-relaxed font-light">
            {{ $currentLocale === 'en'
              ? 'Whole-bean coffee from European roasters, freshly roasted and delivered to your pickup point.'
              : 'Zrnková káva z evropských pražíren, čerstvě pražená a doručená na výdejní místo.' }}
          </p>
        </div>

        @php
          $homeArrows = $currentLocale === 'en' ? [
            '2 to 4 bags of 250 g, depending on the box size',
            'Coffees from three European roasters, different every month',
            'A postcard with the coffee and roaster profile for every coffee',
            'Delivered by Packeta, pause or cancel with one click',
          ] : [
            '2 až 4 balíčky po 250 g podle velikosti boxu',
            'Kávy ze tří evropských pražíren, každý měsíc jiných',
            'Pohlednice s profilem kávy a pražírny ke každé kávě',
            'Doprava Zásilkovnou zdarma, pauza nebo zrušení jedním klikem',
          ];
        @endphp
        <div class="space-y-4">
          @foreach($homeArrows as $arrow)
          <div class="flex gap-3 items-start">
            <span class="text-primary-500" aria-hidden="true">→</span>
            <p class="text-warm-500 font-light">{{ $arrow }}</p>
          </div>
          @endforeach
        </div>
      </div>

      <div class="mt-auto pt-12 border-t border-stone-300">
        <div class="flex items-center justify-between gap-4">
          <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
            <span>{{ $currentLocale === 'en' ? 'Build your box' : 'Sestavit box' }}</span>
            <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
          </a>
          <a href="{{ $lineup->isEmpty() ? localizedRoute('monthly-feature.index') : '#kava-mesice' }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
            <span>{{ $currentLocale === 'en' ? 'Coffee of the Month' : 'Káva měsíce' }}</span>
            <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Tarify M / L / XL -->
<x-tiers :tiers="$tiers" />

<!-- How It Works Section - Same style as Features -->
<div class="relative py-20 sm:py-24 md:py-28 lg:py-36 overflow-hidden" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header -->
    <div class="mb-16 sm:mb-20 max-w-2xl">
      <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-4 tracking-tight uppercase leading-tight sm:leading-[0.95]">{{ $currentLocale === 'en' ? 'How it works' : 'Jak to funguje' }}</h2>
      <p class="text-lg sm:text-xl text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Four simple steps to perfect coffee' : 'Čtyři jednoduché kroky k perfektní kávě' }}</p>
          </div>
          
    <!-- Steps Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
      
      <!-- Step 1 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">01</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Choose your plan' : 'Vyberte si plán' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'Select coffee quantity and delivery frequency according to your needs' : 'Zvolte množství kávy a frekvenci dodání podle vašich potřeb' }}</p>
        </div>
        
      <!-- Step 2 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">02</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Personalize' : 'Personalizujte' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'Choose coffee type, brewing method and delivery address' : 'Vyberte typ kávy, způsob přípravy a doručovací adresu' }}</p>
        </div>

      <!-- Step 3 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">03</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Pick up your box' : 'Vyzvedněte si box' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'We deliver freshly roasted coffee to your chosen location' : 'Čerstvě praženou kávu doručíme na vámi vybrané místo' }}</p>
      </div>

      <!-- Step 4 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">04</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Enjoy' : 'Vychutnejte si' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'Enjoy great specialty coffee and look forward to your next coffee box' : 'Užijte si skvělou výběrovou kávu a těšte se na další kávový box' }}</p>
          </div>
          
        </div>
    </div>
  </div>
  
<!-- Recenze -->
<x-reviews :reviews="$testimonials" :summary="$rating" />

@if($giftsEnabled)
<!-- Darujte kávu -->
@php
  $giftCurrency = \App\Helpers\CurrencyHelper::code();
  $formatGift = fn ($amount) => \App\Helpers\CurrencyHelper::formatByCurrency($amount, $giftCurrency, 0);
  $voucherAmounts = config('gifts.voucher_amounts.' . $giftCurrency, []);
  $isGiftSeason = now()->month >= 10 && now()->lte(now()->setDate(now()->year, 12, 24)->endOfDay());
  $typeParam = $currentLocale === 'en' ? 'type' : 'typ';
  if ($giftCurrency === 'EUR') {
      $voucherList = implode(', ', array_map($formatGift, array_slice($voucherAmounts, 0, -1))) . ' or ' . $formatGift(end($voucherAmounts));
  } else {
      $voucherList = implode(', ', array_map(fn ($a) => number_format($a, 0, ',', ' '), array_slice($voucherAmounts, 0, -1))) . ' nebo ' . $formatGift(end($voucherAmounts));
  }
  $giftRows = [
      [
          'name' => $currentLocale === 'en' ? '3 months' : '3 měsíce',
          'desc' => $currentLocale === 'en' ? '3 boxes, one every month' : '3 boxy, jeden každý měsíc',
          'price' => $giftFrom['subscription'] * 3,
          'param' => '3m',
      ],
      [
          'name' => $currentLocale === 'en' ? '6 months' : '6 měsíců',
          'desc' => $currentLocale === 'en' ? '6 boxes, one every month' : '6 boxů, jeden každý měsíc',
          'price' => $giftFrom['subscription'] * 6,
          'param' => '6m',
      ],
      [
          'name' => $currentLocale === 'en' ? 'Voucher' : 'Poukaz',
          'desc' => $currentLocale === 'en' ? $voucherList . ' for the shop and boxes' : $voucherList . ' na e-shop i boxy',
          'price' => $giftFrom['voucher'],
          'param' => $currentLocale === 'en' ? 'voucher' : 'poukaz',
      ],
  ];
@endphp
<section aria-label="{{ $currentLocale === 'en' ? 'Gifts' : 'Dárky' }}" class="bg-dark-800 text-white">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8 py-24 lg:py-32 flex flex-wrap items-end gap-x-24 gap-y-16">
    <div class="flex-[1_1_400px] min-w-0">
      @if($isGiftSeason)
      <p class="text-xs uppercase tracking-widest text-warm-300 mb-6">{{ $currentLocale === 'en' ? 'Christmas' : 'Vánoce' }} {{ now()->year }}</p>
      @endif
      <h2 class="font-display uppercase text-[64px] sm:text-[96px] leading-[0.9] tracking-tight text-white">
        {{ $currentLocale === 'en' ? 'Give' : 'Darujte' }}<br>
        <span class="text-primary-500">{{ $currentLocale === 'en' ? 'coffee' : 'kávu' }}</span>
      </h2>
      <p class="mt-8 max-w-[440px] text-lg sm:text-xl leading-relaxed font-light text-warm-300">
        @if($currentLocale === 'en')
        The certificate arrives by email straight away as a printable PDF{{ $isGiftSeason ? ', in time even on Christmas Eve' : '' }}. You pay once, nothing renews by itself.
        @else
        Certifikát přijde hned e-mailem v PDF k tisku{{ $isGiftSeason ? ', stihnete ho i na Štědrý den' : '' }}. Platíte jednou, nic se samo neobnoví.
        @endif
      </p>
    </div>

    <div class="flex-[1_1_560px] min-w-0 border-b border-white/20">
      @foreach($giftRows as $row)
      <a href="{{ localizedRoute('gifts.index') }}?{{ $typeParam }}={{ $row['param'] }}"
         class="group grid grid-cols-[minmax(0,1fr)_auto] sm:grid-cols-[minmax(0,1fr)_minmax(0,1.2fr)_160px] items-baseline gap-x-8 gap-y-2 py-7 border-t border-white/20 text-white hover:text-primary-500 transition-colors">
        <span class="font-display uppercase tracking-tight text-[28px] sm:text-[40px] leading-none">{{ $row['name'] }}</span>
        <span class="col-span-full row-start-2 sm:col-span-1 sm:row-start-auto text-sm leading-[22px] font-light text-warm-300">{{ $row['desc'] }}</span>
        <span class="font-display justify-self-end whitespace-nowrap text-lg sm:text-[22px] leading-none">
          {{ $currentLocale === 'en' ? 'from' : 'od' }} {{ $formatGift($row['price']) }}<span class="inline-block ml-2 transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
        </span>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- Impact Section - Editorial Magazine Style -->
<div class="relative min-h-[85vh] lg:min-h-[90vh] overflow-hidden">
  
  <!-- Full-bleed Background Image -->
  <div class="absolute inset-0">
    <img src="/images/water-org.jpg" loading="lazy" alt="Water.org" class="h-full w-full object-cover object-center" />
  </div>
  
  <!-- Content Overlay -->
  <div class="relative min-h-[85vh] lg:min-h-[90vh] flex flex-col justify-between p-6 sm:p-8 lg:p-12">
    
    <!-- Top Text - Mission Statement -->
    <div class="max-w-3xl">
      <p class="text-primary-500 text-xs sm:text-sm font-display uppercase leading-relaxed tracking-wide">
            @if($currentLocale === 'en')
        At KAVI, we believe specialty coffee can change the world. From every coffee box, we donate to Water.org, which provides access to clean water in developing countries.
            @else
        V KAVI věříme, že výběrová káva může měnit svět. Z každého kávového boxu věnujeme 5 Kč organizaci Water.org, která zajišťuje přístup k čisté vodě v rozvojových zemích.
            @endif
          </p>
        </div>

    <!-- Center - Large Headline -->
    <div class="my-auto py-8 sm:py-12">
      <h2 class="font-display text-primary-500 text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-normal uppercase leading-[0.95] tracking-tight max-w-3xl">
        @if($currentLocale === 'en')
        Every box provides one person with water for half a year
        @else
        Každý box poskytne jednomu člověku vodu na půl roku
        @endif
      </h2>
      </div>

    <!-- Bottom Bar - Metadata -->
    <div class="flex flex-wrap items-center justify-between gap-4 border-t-2 border-primary-500 pt-4">
      <div class="flex flex-wrap items-center gap-6 sm:gap-10 text-xs uppercase tracking-widest text-primary-500">
        <a href="https://water.org" target="_blank" rel="noopener" class="font-display hover:text-primary-600 transition-colors">Water.org</a>
        <span>{{ $currentLocale === 'en' ? '6 months of water' : '6 měsíců vody' }}</span>
        <span>{{ $currentLocale === 'en' ? 'Direct donation' : 'Přímá podpora' }}</span>
      </div>
      <div class="flex items-center gap-6 sm:gap-10 text-xs uppercase tracking-widest text-primary-500">
        <span>kavi.cz</span>
    </div>
  </div>
  
  </div>
</div>

<!-- Časté dotazy -->
<x-faq :items="$faq" :lead="__('components.faq.contact', ['email' => $currentLocale === 'en' ? 'info@kavibox.com' : 'info@kavi.cz'])" />

</div>
@endsection
