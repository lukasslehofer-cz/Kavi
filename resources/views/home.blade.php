@extends('layouts.app')

@section('title', __('home.meta.title'))

@section('meta_description')
{{ __('home.meta.description') }}
@endsection

@section('og_title')
{{ __('home.meta.og_title') }}
@endsection

@section('og_description')
{{ __('home.meta.og_description') }}
@endsection

@section('structured_data')
@php
    $siteUrl = \App\Helpers\Site::current()->url();
    $siteName = \App\Helpers\Site::current()->name();
    $siteDescription = __('home.schema.description');
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
                "{{ __('layout.social.instagram') }}",
                "https://www.facebook.com/kavovepredplatne"
            ],
            "contactPoint": {
                "@type": "ContactPoint",
                "contactType": "customer service",
                "email": "{{ \App\Helpers\Site::current()->contactEmail() }}",
                "availableLanguage": ["{{ __('home.schema.language') }}"]
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
            "inLanguage": "{{ __('home.schema.in_language') }}"
        },
        {
            "@type": "WebPage",
            "@id": "{{ $siteUrl }}/#webpage",
            "url": "{{ $siteUrl }}",
            "name": "{{ __('home.meta.title') }}",
            "isPartOf": {
                "@id": "{{ $siteUrl }}/#website"
            },
            "about": {
                "@id": "{{ $siteUrl }}/#organization"
            },
            "description": "{{ $siteDescription }}",
            "inLanguage": "{{ __('home.schema.in_language') }}"
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
            /* Grain overlay: .grain-overlay v resources/css/app.css */
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
                {{ __('home.hero.tagline') }}
                @endif
            </div>
        </div>

        <!-- Center - Main typography -->
        <div class="flex-1 flex flex-col justify-center py-6">
            <!-- Kicker = jediné H1 stránky (SEO), headline pod ním je jen vizuální -->
            <h1 class="mb-6 text-xs uppercase tracking-widest text-white/60 leading-5">
                {{ __('home.hero.kicker') }}
            </h1>

            <!-- Large headline -->
            <p class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal text-white uppercase leading-[0.95] sm:leading-[0.9] tracking-tight">
                {{ __('home.hero.headline_top') }}<br/>
                <span class="text-primary-500">{{ __('home.hero.headline_accent') }}</span><br/>
                {{ __('home.hero.headline_bottom') }}
            </p>

            <p class="mt-6 text-xs uppercase tracking-widest text-white/60 max-w-[280px] leading-5">
                {{ __('home.hero.lead') }}
            </p>
        </div>

        <!-- Bottom row - CTA vlevo, hodnocení z Googlu vpravo -->
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-6">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-8">
                <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-white font-display uppercase tracking-widest hover:text-primary-500 transition-all">
                    <span>{{ __('home.hero.cta_box') }}</span>
                    <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
                </a>
                @if($giftsEnabled)
                <a href="{{ localizedRoute('gifts.index') }}" class="group inline-flex items-center gap-2 text-white/60 font-display uppercase tracking-widest hover:text-white transition-all">
                    <span>{{ __('home.hero.cta_gift') }}</span>
                    <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
                </a>
                @else
                <a href="{{ localizedRoute('products.index') }}" class="group inline-flex items-center gap-2 text-white/60 font-display uppercase tracking-widest hover:text-white transition-all">
                    <span>{{ __('home.hero.cta_shop') }}</span>
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
          <img src="/images/kavi-box.jpg" loading="lazy" alt="{{ __('home.box.image_alt') }}" class="h-full w-full object-cover grayscale" />
          <div class="absolute inset-0 opacity-30 mix-blend-overlay pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 200 200%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%275%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>
        </div>
      </div>
    </div>

    <div class="bg-[#e5e6df] flex flex-col justify-between p-6 sm:p-8 lg:p-12 xl:p-16">
      <div class="space-y-8">
        <div>
          <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-6 leading-tight tracking-tight uppercase">
            {{ __('home.box.title') }}
          </h2>
          <p class="text-lg sm:text-xl text-warm-500 leading-relaxed font-light">
            {{ __('home.box.lead') }}
          </p>
        </div>

        @php
          $homeArrows = __('home.box.items');
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
            <span>{{ __('home.box.cta_box') }}</span>
            <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
          </a>
          <a href="{{ $lineup->isEmpty() ? localizedRoute('monthly-feature.index') : '#kava-mesice' }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
            <span>{{ __('home.box.cta_coffee_of_month') }}</span>
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
      <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-4 tracking-tight uppercase leading-tight sm:leading-[0.95]">{{ __('home.how.title') }}</h2>
      <p class="text-lg sm:text-xl text-warm-500 font-light">{{ __('home.how.subtitle') }}</p>
          </div>
          
    <!-- Steps Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
      
      <!-- Step 1 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">01</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ __('home.how.steps.1.title') }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ __('home.how.steps.1.text') }}</p>
        </div>
        
      <!-- Step 2 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">02</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ __('home.how.steps.2.title') }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ __('home.how.steps.2.text') }}</p>
        </div>

      <!-- Step 3 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">03</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ __('home.how.steps.3.title') }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ __('home.how.steps.3.text') }}</p>
      </div>

      <!-- Step 4 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">04</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ __('home.how.steps.4.title') }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ __('home.how.steps.4.text') }}</p>
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
  $typeParam = __('home.gifts.query_param');
  $giftOr = ' ' . __('home.gifts.or') . ' ';
  if ($giftCurrency === 'EUR') {
      $voucherList = implode(', ', array_map($formatGift, array_slice($voucherAmounts, 0, -1))) . $giftOr . $formatGift(end($voucherAmounts));
  } else {
      $voucherList = implode(', ', array_map(fn ($a) => number_format($a, 0, ',', ' '), array_slice($voucherAmounts, 0, -1))) . $giftOr . $formatGift(end($voucherAmounts));
  }
  $giftFromLabel = fn ($price) => __('home.gifts.from', ['price' => $formatGift($price)]);
  $giftRows = [
      [
          'name' => __('home.gifts.months_3'),
          'desc' => __('home.gifts.months_3_desc'),
          'right' => $giftFromLabel($giftFrom['subscription'] * 3),
          'href' => localizedRoute('gifts.index') . '?' . $typeParam . '=3m',
      ],
      [
          'name' => __('home.gifts.months_6'),
          'desc' => __('home.gifts.months_6_desc'),
          'right' => $giftFromLabel($giftFrom['subscription'] * 6),
          'href' => localizedRoute('gifts.index') . '?' . $typeParam . '=6m',
      ],
      [
          'name' => __('home.gifts.voucher'),
          'desc' => __('home.gifts.voucher_desc', ['amounts' => $voucherList]),
          'right' => $giftFromLabel($giftFrom['voucher']),
          'href' => localizedRoute('gifts.index') . '?' . $typeParam . '=' . __('home.gifts.query_voucher'),
      ],
  ];
  // Firemní dárky jen na kavi.cz
  if (\App\Helpers\CorporateHelper::enabled()) {
      $giftRows[] = [
          'name' => __('corporate.links.home_name'),
          'desc' => __('corporate.links.home_desc'),
          'right' => __('corporate.links.home_from', ['min' => config('corporate.min_quantity', 10)]),
          'href' => route('corporate.index'),
      ];
  }
@endphp
<section aria-label="{{ __('home.gifts.aria') }}" class="bg-dark-800 text-white">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8 py-24 lg:py-32 flex flex-wrap items-end gap-x-24 gap-y-16">
    <div class="flex-[1_1_400px] min-w-0">
      @if($isGiftSeason)
      <p class="text-xs uppercase tracking-widest text-warm-300 mb-6">{{ __('home.gifts.season', ['year' => now()->year]) }}</p>
      @endif
      <h2 class="font-display uppercase text-[64px] sm:text-[96px] leading-[0.9] tracking-tight text-white">
        {{ __('home.gifts.title') }}<br>
        <span class="text-primary-500">{{ __('home.gifts.title_accent') }}</span>
      </h2>
      <p class="mt-8 max-w-[440px] text-lg sm:text-xl leading-relaxed font-light text-warm-300">
        {{ __('home.gifts.text', ['season_note' => $isGiftSeason ? __('home.gifts.season_note') : '']) }}
      </p>
    </div>

    <div class="flex-[1_1_560px] min-w-0 border-b border-white/20">
      @foreach($giftRows as $row)
      <a href="{{ $row['href'] }}"
         class="group grid grid-cols-[minmax(0,1fr)_auto] sm:grid-cols-[minmax(216px,1fr)_minmax(0,1.2fr)_150px] items-baseline gap-x-6 gap-y-2 py-7 border-t border-white/20 text-white hover:text-primary-500 transition-colors">
        <span class="font-display uppercase tracking-tight text-[28px] sm:text-[40px] leading-none">{{ $row['name'] }}</span>
        <span class="col-span-full row-start-2 sm:col-span-1 sm:row-start-auto text-sm leading-[22px] font-light text-warm-300">{{ $row['desc'] }}</span>
        <span class="font-display justify-self-end whitespace-nowrap text-lg sm:text-[22px] leading-none">
          {{ $row['right'] }}<span class="inline-block ml-2 transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
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
        {{ __('home.impact.mission') }}
          </p>
        </div>

    <!-- Center - Large Headline -->
    <div class="my-auto py-8 sm:py-12">
      <h2 class="font-display text-primary-500 text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-normal uppercase leading-[0.95] tracking-tight max-w-3xl">
        {{ __('home.impact.headline') }}
      </h2>
      </div>

    <!-- Bottom Bar - Metadata -->
    <div class="flex flex-wrap items-center justify-between gap-4 border-t-2 border-primary-500 pt-4">
      <div class="flex flex-wrap items-center gap-6 sm:gap-10 text-xs uppercase tracking-widest text-primary-500">
        <a href="https://water.org" target="_blank" rel="noopener" class="font-display hover:text-primary-600 transition-colors">Water.org</a>
        <span>{{ __('home.impact.water_months') }}</span>
        <span>{{ __('home.impact.direct') }}</span>
      </div>
      <div class="flex items-center gap-6 sm:gap-10 text-xs uppercase tracking-widest text-primary-500">
        <span>kavi.cz</span>
    </div>
  </div>
  
  </div>
</div>

<!-- Časté dotazy -->
<x-faq :items="$faq" :lead="__('components.faq.contact', ['email' => \App\Helpers\Site::current()->contactEmail()])" />

</div>
@endsection
