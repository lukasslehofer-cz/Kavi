@extends('layouts.app')

@php
    $seoSiteUrl = \App\Helpers\Site::current()->url();
    // Konfigurátor necílí na „kávové předplatné“, na ten dotaz má Google ukazovat HP
    $seoTitle = __('subscriptions.builder.seo.title');
    $seoDescription = __('subscriptions.builder.seo.description');
    // Stálá fotka boxu místo promo fotky měsíce z hero (tu si jinak Google bere jako náhled)
    $seoImage = $seoSiteUrl . '/images/kavi-box.jpg';

    // Příplatek za 1× decaf i za jednorázový box v měně webu (100 Kč / 5 €, stejně jako SubscriptionController)
    $surchargeAmount = \App\Helpers\CurrencyHelper::price(100, 5);
    $surchargeLabel = \App\Helpers\CurrencyHelper::formatAmount($surchargeAmount, 0);

    // Texty pro skript konfigurátoru (shrnutí se skládá v JS)
    $builderTexts = [
        'soldOut' => __('subscriptions.builder.summary.sold_out'),
        'bags' => __('subscriptions.builder.summary.bags'),
        'types' => [
            'espresso' => __('subscriptions.builder.type.espresso'),
            'filter' => __('subscriptions.builder.type.filter'),
            'mix' => __('subscriptions.builder.type.mix'),
        ],
        'frequencies' => [
            0 => __('subscriptions.builder.frequency.one_time_summary'),
            1 => __('subscriptions.builder.frequency.monthly'),
            2 => __('subscriptions.builder.frequency.bimonthly'),
            3 => __('subscriptions.builder.frequency.quarterly'),
        ],
        'frequencyNotes' => __('subscriptions.builder.summary.frequency_notes'),
        'orderTitle' => __('subscriptions.builder.summary.order_title'),
        'subscriptionTitle' => __('subscriptions.builder.summary.subscription_title'),
        'totalPrice' => __('subscriptions.builder.summary.total_price'),
        'pricePerBox' => __('subscriptions.builder.summary.price_per_box'),
        'perDelivery' => __('subscriptions.builder.summary.per_delivery'),
    ];
@endphp

@section('title', $seoTitle)

@section('meta_description')
{{ $seoDescription }}
@endsection

@section('og_image', $seoImage)

@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    '@id' => url()->current() . '#webpage',
    'url' => url()->current(),
    'name' => $seoTitle,
    'description' => $seoDescription,
    'isPartOf' => ['@id' => $seoSiteUrl . '/#website'],
    'primaryImageOfPage' => [
        '@type' => 'ImageObject',
        'url' => $seoImage,
        'width' => 1000,
        'height' => 1000,
    ],
    'inLanguage' => __('subscriptions.builder.seo.in_language'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
</script>
@endsection

@section('og_title')
{{ __('subscriptions.builder.seo.og_title') }}
@endsection

@section('og_description')
{{ __('subscriptions.builder.seo.og_description') }}
@endsection

@section('content')
<!-- Hero Header Section - Editorial Layout -->
<div style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    
    <!-- Main Heading - Large Editorial Typography, Left aligned -->
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
      <span class="text-dark-800">{{ __('subscriptions.builder.hero.title_1') }}</span><br>
      <span class="text-primary-500">{{ __('subscriptions.builder.hero.title_2') }}</span>
      </h1>

    <!-- Úvodní odstavec vlevo (pro příchozí z Googlu odkaz na HP), popis vpravo -->
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
      <p class="text-base text-warm-600 leading-relaxed font-light max-w-xl">
        {{ __('subscriptions.builder.hero.intro') }}
        <a href="{{ localizedRoute('home') }}" class="text-primary-500 underline underline-offset-2 hover:text-primary-600 transition-colors duration-200">{{ __('subscriptions.builder.hero.intro_link') }} &rarr;</a>
      </p>
      <div class="flex justify-end">
        <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-500 max-w-md text-right leading-relaxed">
          {{ __('subscriptions.builder.hero.lead') }}
        </p>
      </div>
  </div>
  
  </div>
</div>

<!-- Main Content Container - 2 Column Layout -->
<div class="py-12 sm:py-16 lg:py-20" style="background-color: #e5e6df;">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">

    <!-- Error Messages -->
    @if($errors->any())
    <div class="mb-6">
      <div class="bg-red-50 border border-red-300 text-red-700 px-6 py-4">
        <p class="font-medium mb-2">Chyba při zpracování konfigurace:</p>
        <ul class="list-disc list-inside space-y-1 font-light">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6">
      <div class="bg-red-50 border border-red-300 text-red-700 px-6 py-4">
        <p class="font-medium">{{ session('error') }}</p>
      </div>
    </div>
    @endif

    <!-- 2-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
      
      <!-- LEFT COLUMN - Odpočet do uzávěrky + promo karta měsíce -->
      <div class="lg:col-span-5">
        <div class="lg:sticky lg:top-[72px]">
          @if(! $availability['allSoldOut'])
          <x-countdown :cycle="$cycle" />
          @endif
          <x-promo-card :lineup="$lineup" :more="true" />
        </div>
      </div>

      <!-- RIGHT COLUMN - Form -->
      <div class="lg:col-span-7">
        @if($availability['allSoldOut'])
        @if($availability['preparingNewCoffees'] ?? false)
        <!-- Preparing New Coffees Message -->
        <div class="p-6">
          <h2 class="font-display text-3xl font-normal text-primary-500 mb-4 uppercase">
            {{ __('subscriptions.builder.preparing.title') }}
          </h2>

          <p class="text-lg text-warm-500 mb-6 max-w-2xl font-light">
            {{ __('subscriptions.builder.preparing.text') }}
          </p>

          <div class="flex flex-col sm:flex-row gap-6">
            <a href="{{ localizedRoute('products.index') }}"
               class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
              <span>{{ __('subscriptions.builder.browse_shop') }}</span>
              <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
            </a>
          </div>
        </div>
        @else
        <!-- Sold Out Message -->
        <div class="p-6">
          <h2 class="font-display text-3xl font-normal text-primary-500 mb-4 uppercase">
            {{ __('subscriptions.builder.sold_out.title') }}
          </h2>

          <p class="text-lg text-warm-500 mb-6 max-w-2xl font-light">
            {{ __('subscriptions.builder.sold_out.text') }}
          </p>

          <div class="bg-warm-300 p-6 mb-8">
            <p class="font-display text-lg text-dark-800 uppercase tracking-wide">
              {{ __('subscriptions.builder.sold_out.next_month') }}
            </p>
            <p class="font-display text-2xl text-dark-800 uppercase tracking-tight mt-2">
              {{ $nextAvailableDate->format(__('subscriptions.builder.sold_out.date_format')) }}
            </p>
          </div>

          <div class="flex flex-col sm:flex-row gap-6">
            <a href="{{ localizedRoute('products.index') }}"
               class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
              <span>{{ __('subscriptions.builder.browse_shop') }}</span>
              <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
            </a>
          </div>
        </div>
        @endif
        @else
        <form id="subscription-configurator" method="POST" action="{{ localizedRoute('subscriptions.configure.checkout') }}">
          @csrf
          
          <!-- Hidden inputy pro mix rozdělení -->
          <input type="hidden" name="mix[espresso]" id="mix-espresso-value" value="0">
          <input type="hidden" name="mix[filter]" id="mix-filter-value" value="0">
          
          <!-- KROK 1 - Množství kávy -->
          <div class="mb-12 border-t-2 border-primary-500 pt-6 pr-[5px]">
            <div class="mb-6">
              <div class="flex items-baseline gap-4 mb-1">
                <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">01</span>
                <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('subscriptions.builder.amount.title') }}</h2>
              </div>
              <p class="text-sm text-warm-500 font-light">{{ __('subscriptions.builder.amount.subtitle') }}</p>
            </div>

            <div class="space-y-0">
              <!-- M Box -->
              <label class="group flex flex-col sm:flex-row sm:items-center justify-between py-6 pr-2 cursor-pointer transition-all border-b border-dark-800/10 hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 relative">
                <input type="radio" name="amount" value="2" class="hidden" required>
                <!-- Left accent when selected -->
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors"></div>
                <div class="flex items-baseline gap-2 pl-4 w-[140px] sm:w-[160px] flex-shrink-0">
                  <span class="font-display text-5xl sm:text-6xl font-normal text-dark-800 leading-none tracking-tight">M</span>
                  <span class="font-display text-2xl font-normal text-dark-800 uppercase">Box</span>
                  </div>
                <div class="pl-4 sm:pl-0 sm:flex-1 sm:pl-6">
                  <p class="text-warm-500 uppercase tracking-widest text-xs">500g · 2 {{ __('subscriptions.builder.amount.bags') }}</p>
                  <p class="text-sm text-dark-700 font-light hidden sm:block">{{ __('subscriptions.builder.amount.m_desc') }}</p>
                  </div>
                <div class="pl-4 sm:pl-0 mt-2 sm:mt-0 flex flex-col sm:items-end">
                  <div class="flex items-baseline gap-1">
                    <span class="font-display text-2xl font-normal text-dark-800">{{ \App\Helpers\CurrencyHelper::formatAmount($subscriptionPricing['2'], 0) }}</span>
                    <span class="text-warm-500 font-light text-sm">/box</span>
                  </div>
                  <span class="text-xs leading-4 text-warm-600">{{ \App\Helpers\BoxPricing::formatUnitPrice(2) }}</span>
                </div>
              </label>

              <!-- L Box - Most Popular -->
              <label class="group flex flex-col sm:flex-row sm:items-center justify-between py-6 pr-2 cursor-pointer transition-all border-b border-dark-800/10 hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 relative">
                <input type="radio" name="amount" value="3" class="hidden" required>
                <!-- Left accent when selected -->
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors"></div>
                <div class="flex items-baseline gap-2 pl-4 w-[140px] sm:w-[160px] flex-shrink-0">
                  <span class="font-display text-5xl sm:text-6xl font-normal text-dark-800 leading-none tracking-tight">L</span>
                  <span class="font-display text-2xl font-normal text-dark-800 uppercase">Box</span>
                  </div>
                <div class="pl-4 sm:pl-0 sm:flex-1 sm:pl-6">
                  <p class="text-warm-500 uppercase tracking-widest text-xs flex items-center gap-3">
                    750g · 3 {{ __('subscriptions.builder.amount.bags') }}
                    <span class="px-2 py-0.5 bg-primary-500 text-white text-xs uppercase tracking-wider">{{ __('subscriptions.builder.amount.popular') }}</span>
                  </p>
                  <p class="text-sm text-dark-700 font-light hidden sm:block">{{ __('subscriptions.builder.amount.l_desc') }}</p>
                  </div>
                <div class="pl-4 sm:pl-0 mt-2 sm:mt-0 flex flex-col sm:items-end">
                  <div class="flex items-baseline gap-1">
                    <span class="font-display text-2xl font-normal text-dark-800">{{ \App\Helpers\CurrencyHelper::formatAmount($subscriptionPricing['3'], 0) }}</span>
                    <span class="text-warm-500 font-light text-sm">/box</span>
                  </div>
                  <span class="text-xs leading-4 text-warm-600">{{ \App\Helpers\BoxPricing::formatUnitPrice(3) }}</span>
                </div>
              </label>

              <!-- XL Box -->
              <label class="group flex flex-col sm:flex-row sm:items-center justify-between py-6 pr-2 cursor-pointer transition-all border-b border-dark-800/10 hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 relative">
                <input type="radio" name="amount" value="4" class="hidden" required>
                <!-- Left accent when selected -->
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors"></div>
                <div class="flex items-baseline gap-2 pl-4 w-[140px] sm:w-[160px] flex-shrink-0">
                  <span class="font-display text-5xl sm:text-6xl font-normal text-dark-800 leading-none tracking-tight">XL</span>
                  <span class="font-display text-2xl font-normal text-dark-800 uppercase">Box</span>
                  </div>
                <div class="pl-4 sm:pl-0 sm:flex-1 sm:pl-6">
                  <p class="text-warm-500 uppercase tracking-widest text-xs">1000g · 4 {{ __('subscriptions.builder.amount.bags') }}</p>
                  <p class="text-sm text-dark-700 font-light hidden sm:block">{{ __('subscriptions.builder.amount.xl_desc') }}</p>
                  </div>
                <div class="pl-4 sm:pl-0 mt-2 sm:mt-0 flex flex-col sm:items-end">
                  <div class="flex items-baseline gap-1">
                    <span class="font-display text-2xl font-normal text-dark-800">{{ \App\Helpers\CurrencyHelper::formatAmount($subscriptionPricing['4'], 0) }}</span>
                    <span class="text-warm-500 font-light text-sm">/box</span>
                  </div>
                  <span class="text-xs leading-4 text-warm-600">{{ \App\Helpers\BoxPricing::formatUnitPrice(4) }}</span>
                </div>
              </label>
            </div>
          </div>

          <!-- KROK 2 - Typ kávy -->
          <div class="mb-12 border-t-2 border-primary-500 pt-6 pr-[5px]">
            <div class="mb-6">
              <div class="flex items-baseline gap-4 mb-1">
                <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">02</span>
                <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('subscriptions.builder.type.title') }}</h2>
              </div>
              <p class="text-sm text-warm-500 font-light">{{ __('subscriptions.builder.type.subtitle') }}</p>
            </div>

            <div class="space-y-0">
              <!-- Espresso -->
              <label class="group flex items-center justify-between py-5 cursor-pointer transition-all border-b border-dark-800/10 hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 relative pl-4">
                <input type="radio" name="type" value="espresso" class="hidden" required>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors"></div>
                <div class="flex-1">
                  <div class="font-display text-2xl font-normal text-dark-800 uppercase">Espresso</div>
                  <p class="text-xs text-warm-500 font-light">{{ __('subscriptions.builder.type.espresso_desc') }}</p>
                  </div>
                <label class="flex items-center gap-2 px-3 py-1.5 bg-[#BCBEB1] hover:bg-[#a8ab9e] transition-colors text-sm" onclick="event.stopPropagation();">
                  <input type="checkbox" name="isDecaf" value="1" class="w-4 h-4 text-primary-500">
                  <span class="font-medium text-dark-700">{{ __('subscriptions.builder.type.decaf') }} <span class="block sm:inline">(+{{ $surchargeLabel }})</span></span>
                  </label>
              </label>

              <!-- Filtr -->
              <label class="group flex items-center justify-between py-5 cursor-pointer transition-all border-b border-dark-800/10 hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 relative pl-4">
                <input type="radio" name="type" value="filter" class="hidden" required>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors"></div>
                <div class="flex-1">
                  <div class="font-display text-2xl font-normal text-dark-800 uppercase">{{ __('subscriptions.builder.type.filter') }}</div>
                  <p class="text-xs text-warm-500 font-light">{{ __('subscriptions.builder.type.filter_desc') }}</p>
                  </div>
                <label class="flex items-center gap-2 px-3 py-1.5 bg-[#BCBEB1] hover:bg-[#a8ab9e] transition-colors text-sm" onclick="event.stopPropagation();">
                  <input type="checkbox" name="isDecaf" value="1" class="w-4 h-4 text-primary-500">
                  <span class="font-medium text-dark-700">{{ __('subscriptions.builder.type.decaf') }} <span class="block sm:inline">(+{{ $surchargeLabel }})</span></span>
                  </label>
              </label>

              <!-- Kombinace -->
              <label class="group flex items-center justify-between py-5 cursor-pointer transition-all border-b border-dark-800/10 hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 relative pl-4">
                <input type="radio" name="type" value="mix" class="hidden" required>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors"></div>
                <div class="flex-1">
                  <div class="font-display text-2xl font-normal text-dark-800 uppercase">{{ __('subscriptions.builder.type.mix') }}</div>
                  <p class="text-xs text-warm-500 font-light">{{ __('subscriptions.builder.type.mix_desc') }}</p>
                  </div>
                <label class="flex items-center gap-2 px-3 py-1.5 bg-[#BCBEB1] hover:bg-[#a8ab9e] transition-colors text-sm" onclick="event.stopPropagation();">
                  <input type="checkbox" name="isDecaf" value="1" class="w-4 h-4 text-primary-500">
                  <span class="font-medium text-dark-700">{{ __('subscriptions.builder.type.decaf') }} <span class="block sm:inline">(+{{ $surchargeLabel }})</span></span>
                  </label>
              </label>
            </div>

            <!-- Rozdělení kávy pro mix -->
            <div id="caffeine-distribution" class="hidden mt-6 p-5 bg-warm-200">
              <h4 class="font-display text-lg font-normal text-dark-800 mb-4 uppercase">{{ __('subscriptions.builder.type.distribution') }}</h4>
              
              <div id="mix-no-decaf-distribution" class="hidden grid grid-cols-2 gap-4">
                <!-- Espresso -->
                <div class="flex flex-col p-4 bg-white">
                  <span class="font-medium text-dark-800 mb-2 text-sm">Espresso</span>
                  <div class="flex items-center justify-center gap-3">
                    <button type="button" id="mix-espresso-minus" class="w-10 h-10 flex items-center justify-center bg-stone-200 hover:bg-stone-300 active:bg-stone-400 transition-colors">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-10 text-center font-display text-xl font-normal" id="mix-espresso-count">0</span>
                    <button type="button" id="mix-espresso-plus" class="w-10 h-10 flex items-center justify-center bg-stone-200 hover:bg-stone-300 active:bg-stone-400 transition-colors">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
                
                <!-- Filtr -->
                <div class="flex flex-col p-4 bg-white">
                  <span class="font-medium text-dark-800 mb-2 text-sm">Filtr</span>
                  <div class="flex items-center justify-center gap-3">
                    <button type="button" id="mix-filter-minus" class="w-10 h-10 flex items-center justify-center bg-stone-200 hover:bg-stone-300 active:bg-stone-400 transition-colors">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-10 text-center font-display text-xl font-normal" id="mix-filter-count">0</span>
                    <button type="button" id="mix-filter-plus" class="w-10 h-10 flex items-center justify-center bg-stone-200 hover:bg-stone-300 active:bg-stone-400 transition-colors">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- KROK 3 - Frekvence -->
          <div class="mb-12 border-t-2 border-primary-500 pt-6 pr-[5px]">
            <div class="mb-6">
              <div class="flex items-baseline gap-4 mb-1">
                <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">03</span>
                <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('subscriptions.builder.frequency.title') }}</h2>
              </div>
              <p class="text-sm text-warm-500 font-light">{{ __('subscriptions.builder.frequency.subtitle') }}</p>
            </div>

            <div class="space-y-0">
              <!-- Každý měsíc -->
              <label class="group flex items-center justify-between py-5 pr-2 cursor-pointer transition-all border-b border-dark-800/10 hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 relative pl-4">
                <input type="radio" name="frequency" value="1" class="hidden" required>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors"></div>
                <div class="flex-1">
                  <div class="font-display text-2xl font-normal text-dark-800 uppercase">{{ __('subscriptions.builder.frequency.monthly') }}</div>
                  <p class="text-xs text-warm-500 font-light">{{ __('subscriptions.builder.frequency.monthly_desc') }}</p>
                </div>
              </label>

              <!-- Jednou za 2 měsíce -->
              <label class="group flex items-center justify-between py-5 pr-2 cursor-pointer transition-all border-b border-dark-800/10 hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 relative pl-4">
                <input type="radio" name="frequency" value="2" class="hidden" required>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors"></div>
                <div class="flex-1">
                  <div class="font-display text-2xl font-normal text-dark-800 uppercase">{{ __('subscriptions.builder.frequency.bimonthly') }}</div>
                  <p class="text-xs text-warm-500 font-light">{{ __('subscriptions.builder.frequency.bimonthly_desc') }}</p>
                </div>
              </label>

              <!-- Jednou za 3 měsíce -->
              <label class="group flex items-center justify-between py-5 pr-2 cursor-pointer transition-all border-b border-dark-800/10 hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 relative pl-4">
                <input type="radio" name="frequency" value="3" class="hidden" required>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors"></div>
                <div class="flex-1">
                  <div class="font-display text-2xl font-normal text-dark-800 uppercase">{{ __('subscriptions.builder.frequency.quarterly') }}</div>
                  <p class="text-xs text-warm-500 font-light">{{ __('subscriptions.builder.frequency.quarterly_desc') }}</p>
                </div>
              </label>

              <!-- Jednorázově (bez předplatného) -->
              <label class="group flex items-center justify-between py-5 pr-2 cursor-pointer transition-all border-b border-dark-800/10 hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 relative pl-4">
                <input type="radio" name="frequency" value="0" class="hidden" required>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors"></div>
                <div class="flex-1">
                <div class="flex items-center gap-3">
                    <span class="font-display text-2xl font-normal text-dark-800 uppercase">{{ __('subscriptions.builder.frequency.one_time') }}</span>
                    <span class="px-2 py-0.5 bg-olive-500 text-white text-xs uppercase tracking-wider">{{ __('subscriptions.builder.frequency.one_time_badge') }}</span>
                  </div>
                  <p class="text-xs text-warm-500 font-light">{{ __('subscriptions.builder.frequency.one_time_desc') }}</p>
                </div>
                <span class="font-display text-lg text-dark-800">+{{ $surchargeLabel }}</span>
              </label>
            </div>

            <!-- Po výběru "Jednorázově": nabídka předplatného -->
            <div id="onetime-upsell" class="hidden mt-4 px-5 py-4 bg-warm-300/40 flex flex-wrap items-center justify-between gap-x-6 gap-y-3">
              <span class="flex-[1_1_300px] text-sm leading-[22px] text-dark-800">
                {{ __('subscriptions.builder.frequency.upsell', ['amount' => $surchargeLabel]) }}
              </span>
              <button type="button" id="switch-to-subscription" class="group inline-flex items-center gap-2 text-sm text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-colors">
                <span>{{ __('subscriptions.builder.frequency.switch') }}</span>
                <span class="group-hover:translate-x-1 transition-transform" aria-hidden="true">&rarr;</span>
              </button>
            </div>
          </div>

          <!-- Shrnutí a Submit -->
          <div class="border-t-2 border-primary-500 pt-6 mt-8">
            <h3 id="summary-title" class="font-display text-4xl font-normal text-dark-800 mb-6 uppercase tracking-tight">{{ __('subscriptions.builder.summary.order_title') }}</h3>

            <div class="space-y-3 mb-6">
              <div class="flex justify-between py-2 border-b border-dark-800/10">
                <span class="text-warm-500 uppercase tracking-widest text-xs">{{ __('subscriptions.builder.summary.quantity') }}</span>
                <span class="font-display text-lg text-dark-800" id="summary-amount">-</span>
              </div>
              <div class="flex justify-between py-2 border-b border-dark-800/10">
                <span class="text-warm-500 uppercase tracking-widest text-xs">{{ __('subscriptions.builder.summary.type') }}</span>
                <span class="font-display text-lg text-dark-800" id="summary-type">-</span>
              </div>
              <div class="flex justify-between py-2 border-b border-dark-800/10">
                <span class="text-warm-500 uppercase tracking-widest text-xs">{{ __('subscriptions.builder.summary.frequency') }}</span>
                <span class="font-display text-lg text-dark-800" id="summary-frequency">-</span>
              </div>
              <div id="onetime-surcharge" class="hidden flex justify-between py-2 border-b border-dark-800/10">
                <span class="text-warm-500 uppercase tracking-widest text-xs">{{ __('subscriptions.builder.summary.one_time_surcharge') }}</span>
                <span class="font-display text-lg text-dark-800">+{{ $surchargeLabel }}</span>
              </div>
            </div>

            <div class="flex justify-between items-end py-4 border-t-2 border-primary-500 mb-6">
              <span id="summary-total-label" class="text-primary-500 font-display text-xl font-normal uppercase">{{ __('subscriptions.builder.summary.price_per_box') }}</span>
                <div class="text-right">
                  <span class="font-display text-5xl font-normal text-dark-800" id="summary-price">-</span><span class="text-lg text-warm-500 ml-1">{{ \App\Helpers\CurrencyHelper::symbol() }}</span>
                <p id="summary-price-note" class="text-xs text-warm-600 mt-1 uppercase tracking-widest">{{ __('subscriptions.builder.summary.per_delivery') }}</p>
              </div>
            </div>

            <button type="submit" id="submit-button"
                    class="w-full py-4 bg-dark-800 text-white font-display text-lg uppercase tracking-wide hover:bg-dark-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3"
                    disabled>
              <span>{{ __('subscriptions.builder.summary.submit') }}</span>
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </button>

            <div class="flex items-center justify-center gap-6 mt-4 text-xs text-warm-500 uppercase tracking-widest">
              <span class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500"></span>
                {{ __('subscriptions.builder.summary.no_commitment') }}
              </span>
              <span class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500"></span>
                {{ __('subscriptions.builder.summary.cancel_anytime') }}
              </span>
            </div>
          </div>
        </form>
        @endif

        <!-- Shipping Date Info -->
        @if(!$availability['allSoldOut'])
        <div class="mt-8 p-5 bg-warm-200">
          <h3 class="font-display text-lg font-normal text-dark-800 mb-2 uppercase">{{ __('subscriptions.builder.shipping.title') }}</h3>
          <p class="text-sm text-dark-800 font-medium mb-2">{{ $shippingInfo['cutoff_message'] }}</p>
          <p class="text-sm text-warm-600 font-light">
            {!! __('subscriptions.builder.shipping.text', ['cutoff' => e($cycle->cutoffShort()), 'shipment' => e($cycle->shipmentShort())]) !!}
              </p>
        </div>
        @endif
      </div>
    </div>

  </div>
</div>


{{-- Kávy měsíce, které mohou přijít v boxu --}}
<x-coffee-of-month
    :lineup="$lineup"
    variant="h2"
    id="v-boxu"
    :title="__('subscriptions.builder.lineup.title', ['month' => $lineup->monthName('adjective_locative')])"
    :lead="__('subscriptions.builder.lineup.lead')"
    :link-label="__('subscriptions.builder.lineup.link')"
    padding="pt-20 sm:pt-24 lg:pt-28 pb-20 sm:pb-24 lg:pb-28" />

<x-faq :items="__('subscriptions.faq')" padding="pt-8 pb-20 sm:pb-24 lg:pb-28" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pricing = @json($subscriptionPricing);
    const currencySymbol = @json(\App\Helpers\CurrencyHelper::symbol());
    // Texty shrnutí, příplatek (decaf / jednorázový box) a formát čísel podle jazyka a měny webu
    const texts = @json($builderTexts);
    const surcharge = @json($surchargeAmount);
    const surchargeLabel = @json($surchargeLabel);
    const numberLocale = @json(__('subscriptions.builder.summary.number_locale'));

    // Load availability data from backend
    const availability = @json($availability);

    // Per box size / decaf / mix split availability - the type flags above cannot
    // tell that e.g. an L filter box is sold out while an M filter box is not
    const availabilityMatrix = @json($availabilityMatrix);

    let selectedAmount = null;
    let selectedType = null;
    let selectedFrequency = null;
    let isDecaf = false;
    
    // Pro Mix rozdělení
    let mixEspressoCount = 0;
    let mixFilterCount = 0;
    
    // Karty množství
    document.querySelectorAll('input[name="amount"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedAmount = parseInt(this.value);
            
            // Pokud je vybraný mix, přepočítej počty a zobraz sekci
            if (selectedType === 'mix') {
                mixFilterCount = defaultMixFilterCount(selectedAmount);
                mixEspressoCount = selectedAmount - mixFilterCount;
                updateMixNoDecafDisplay();
                showDistributionLayout();
            }
            
            updateSummary();
        });
    });
    
    // Typ kávy
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedType = this.value;
            
            // Odškrtnout všechny decaf checkboxy kromě toho ve vybraném bloku
            document.querySelectorAll('input[name="isDecaf"]').forEach(checkbox => {
                const checkboxLabel = checkbox.closest('label.group');
                const radioLabel = this.closest('label.group');
                if (checkboxLabel !== radioLabel) {
                    checkbox.checked = false;
                }
            });
            
            // Aktualizovat isDecaf stav
            const currentDecafCheckbox = this.closest('label.group').querySelector('input[name="isDecaf"]');
            isDecaf = currentDecafCheckbox ? currentDecafCheckbox.checked : false;
            
            // Zobrazit/skrýt sekci Rozdělení kávy
            showDistributionLayout();
            
            updateSummary();
        });
    });
    
    // Decaf checkboxy
    document.querySelectorAll('input[name="isDecaf"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const radioButton = this.closest('label.group').querySelector('input[name="type"]');
            
            if (this.checked && radioButton) {
                radioButton.checked = true;
                radioButton.dispatchEvent(new Event('change'));
            } else {
                isDecaf = this.checked;
                updateSummary();
            }
        });
    });
    
    // Frekvence
    document.querySelectorAll('input[name="frequency"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedFrequency = parseInt(this.value);
            updateSummary();
        });
    });
    
    // Funkce pro zobrazení/skrytí sekce Rozdělení kávy
    function showDistributionLayout() {
        const caffeineDistribution = document.getElementById('caffeine-distribution');
        const mixNoDecafDistribution = document.getElementById('mix-no-decaf-distribution');
        
        if (selectedType === 'mix') {
            caffeineDistribution.classList.remove('hidden');
            mixNoDecafDistribution.classList.remove('hidden');
            
            if (selectedAmount && (mixEspressoCount === 0 && mixFilterCount === 0)) {
                mixFilterCount = defaultMixFilterCount(selectedAmount);
                mixEspressoCount = selectedAmount - mixFilterCount;
                updateMixNoDecafDisplay();
            }
        } else {
            caffeineDistribution.classList.add('hidden');
            mixNoDecafDistribution.classList.add('hidden');
        }
    }
    
    // Funkce pro update Mix distribution
    function updateMixNoDecafDisplay() {
        document.getElementById('mix-espresso-count').textContent = mixEspressoCount;
        document.getElementById('mix-filter-count').textContent = mixFilterCount;
        
        document.getElementById('mix-espresso-value').value = mixEspressoCount;
        document.getElementById('mix-filter-value').value = mixFilterCount;
        
        const mixEspressoPlus = document.getElementById('mix-espresso-plus');
        const mixEspressoMinus = document.getElementById('mix-espresso-minus');
        const mixFilterPlus = document.getElementById('mix-filter-plus');
        const mixFilterMinus = document.getElementById('mix-filter-minus');
        
        // Block splits that cannot be shipped, otherwise moving a bag across would
        // silently invalidate - and clear - the chosen box size
        const splitOk = filterCount => !selectedAmount || mixSplitAvailable(selectedAmount, filterCount);

        if (mixEspressoPlus) mixEspressoPlus.disabled = mixFilterCount <= 0 || !splitOk(mixFilterCount - 1);
        if (mixEspressoMinus) mixEspressoMinus.disabled = mixEspressoCount <= 0 || !splitOk(mixFilterCount + 1);
        if (mixFilterPlus) mixFilterPlus.disabled = mixEspressoCount <= 0 || !splitOk(mixFilterCount + 1);
        if (mixFilterMinus) mixFilterMinus.disabled = mixFilterCount <= 0 || !splitOk(mixFilterCount - 1);
    }
    
    // Event listenery pro Mix distribution tlačítka
    const mixEspressoPlus = document.getElementById('mix-espresso-plus');
    const mixEspressoMinus = document.getElementById('mix-espresso-minus');
    const mixFilterPlus = document.getElementById('mix-filter-plus');
    const mixFilterMinus = document.getElementById('mix-filter-minus');
    
    if (mixEspressoPlus) {
        mixEspressoPlus.addEventListener('click', function() {
            if (mixFilterCount > 0) {
                mixEspressoCount++;
                mixFilterCount--;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    if (mixEspressoMinus) {
        mixEspressoMinus.addEventListener('click', function() {
            if (mixEspressoCount > 0) {
                mixEspressoCount--;
                mixFilterCount++;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    if (mixFilterPlus) {
        mixFilterPlus.addEventListener('click', function() {
            if (mixEspressoCount > 0) {
                mixFilterCount++;
                mixEspressoCount--;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    if (mixFilterMinus) {
        mixFilterMinus.addEventListener('click', function() {
            if (mixFilterCount > 0) {
                mixFilterCount--;
                mixEspressoCount++;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    const ALL_TYPES = ['espresso', 'filter', 'mix'];
    const ALL_AMOUNTS = [2, 3, 4];

    // Mark an option as sold out, or restore it. Selections can become invalid as
    // the customer changes type or decaf, so this has to work in both directions.
    function setSoldOut(label, input, soldOut) {
        if (!label) {
            return;
        }

        if (soldOut) {
            label.classList.add('cursor-not-allowed');
            label.style.pointerEvents = 'none';
            label.style.position = 'relative';
            label.style.backgroundColor = '#f3f4f6'; // gray-100
            // Direct children only - the decaf pill is a label, not a div, and
            // nested divs would otherwise stack their opacity
            label.querySelectorAll(':scope > *:not(.sold-out-label)').forEach(child => {
                child.style.opacity = '0.5';
            });

            if (input) {
                input.disabled = true;
                input.checked = false;
            }

            if (!label.querySelector('.sold-out-label')) {
                const badge = document.createElement('div');
                badge.className = 'sold-out-label absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-md shadow-md';
                badge.style.opacity = '1'; // Ensure full opacity
                badge.textContent = texts.soldOut;
                label.appendChild(badge);
            }
        } else {
            label.classList.remove('cursor-not-allowed');
            label.style.pointerEvents = '';
            label.style.backgroundColor = '';
            label.querySelectorAll(':scope > *:not(.sold-out-label)').forEach(child => {
                child.style.opacity = '';
            });

            if (input) {
                input.disabled = false;
            }

            const badge = label.querySelector('.sold-out-label');
            if (badge) {
                badge.remove();
            }
        }
    }

    // Can a mix box be shipped with this many filter bags?
    function mixSplitAvailable(amount, filterCount) {
        const branch = availabilityMatrix.mix && availabilityMatrix.mix[amount];

        return branch ? !!branch[isDecaf ? 'decaf' : 'plain'][filterCount] : false;
    }

    // Balanced split by default, nudged to the nearest one that can be shipped
    function defaultMixFilterCount(amount) {
        const balanced = amount - Math.floor(amount / 2);

        for (let delta = 0; delta <= amount; delta++) {
            for (const filterCount of [balanced - delta, balanced + delta]) {
                if (filterCount >= 0 && filterCount <= amount && mixSplitAvailable(amount, filterCount)) {
                    return filterCount;
                }
            }
        }

        return balanced;
    }

    // Can this exact combination be shipped this month?
    function combinationOk(type, amount) {
        const branch = availabilityMatrix[type] && availabilityMatrix[type][amount];
        if (!branch) {
            return false;
        }

        const variant = branch[isDecaf ? 'decaf' : 'plain'];

        if (type !== 'mix') {
            return !!variant;
        }

        // For mix the split matters. Until the customer has picked one, any
        // workable split keeps the option open.
        const splitChosen = selectedType === 'mix'
            && selectedAmount === amount
            && (mixEspressoCount + mixFilterCount) === amount;

        return splitChosen
            ? mixSplitAvailable(amount, mixFilterCount)
            : Object.values(variant).some(Boolean);
    }

    // Function to update UI based on availability
    function updateAvailabilityUI() {
        // Drop a box size that the current type no longer supports, otherwise the
        // stale pair would grey each other out and leave nothing selectable
        if (selectedType && selectedAmount && !combinationOk(selectedType, selectedAmount)) {
            const checkedAmount = document.querySelector('input[name="amount"]:checked');
            if (checkedAmount) {
                checkedAmount.checked = false;
            }
            selectedAmount = null;
        }

        // A coffee type is offered when it works with the chosen box size,
        // or with any size while none is chosen yet
        ALL_TYPES.forEach(type => {
            const radio = document.querySelector(`input[name="type"][value="${type}"]`);
            const amounts = selectedAmount ? [selectedAmount] : ALL_AMOUNTS;
            const available = amounts.some(amount => combinationOk(type, amount));

            setSoldOut(radio?.closest('label.group'), radio, !available);

            if (!available && selectedType === type) {
                selectedType = null;
            }
        });

        // Same the other way round for the box sizes
        ALL_AMOUNTS.forEach(amount => {
            const radio = document.querySelector(`input[name="amount"][value="${amount}"]`);
            const types = selectedType ? [selectedType] : ALL_TYPES;
            const available = types.some(type => combinationOk(type, amount));

            setSoldOut(radio?.closest('label.group'), radio, !available);

            if (!available && selectedAmount === amount) {
                selectedAmount = null;
            }
        });

        // Decaf availability is handled in app.js

        // The split section follows the type, and its steppers follow the matrix -
        // both the box size and the decaf toggle move it
        showDistributionLayout();

        if (selectedType === 'mix') {
            updateMixNoDecafDisplay();
        }
    }

    
    function updateSummary() {
        // Availability depends on the whole combination, so re-check it on every
        // change - a size can become unavailable once the type or decaf changes
        updateAvailabilityUI();

        // Množství
        if (selectedAmount) {
            const grams = selectedAmount * 250;
            const bagsText = ' ' + texts.bags;
            document.getElementById('summary-amount').textContent = grams + 'g (' + selectedAmount + bagsText + ')';
        } else {
            document.getElementById('summary-amount').textContent = '-';
        }

        // Typ
        let typeText = '-';
        if (selectedType) {
            const types = texts.types;
            typeText = types[selectedType];

            const filterLabel = texts.types.filter;
            if (selectedType === 'mix' && (mixEspressoCount > 0 || mixFilterCount > 0)) {
                typeText += ` (${mixEspressoCount}x Espresso, ${mixFilterCount}x ${filterLabel})`;
            }

            if (isDecaf) {
                const decafSurcharge = '+' + surchargeLabel;
                typeText += ` (1x decaf) ${decafSurcharge}`;
            }
        }
        document.getElementById('summary-type').textContent = typeText;

        // Frekvence
        const frequencies = texts.frequencies;
        document.getElementById('summary-frequency').textContent = frequencies[selectedFrequency] || '-';
        
        // Dynamicky změnit nadpis a texty podle typu objednávky
        const summaryTitle = document.getElementById('summary-title');
        const summaryPriceNote = document.getElementById('summary-price-note');
        const onetimeSurcharge = document.getElementById('onetime-surcharge');
        
        const summaryTotalLabel = document.getElementById('summary-total-label');
        const onetimeUpsell = document.getElementById('onetime-upsell');
        // Pod cenou: frekvence (+ doprava zdarma na kavi.cz)
        const frequencyNotes = texts.frequencyNotes;

        if (selectedFrequency === 0) {
            // Jednorázová objednávka
            summaryTitle.textContent = texts.orderTitle;
            summaryTotalLabel.textContent = texts.totalPrice;
            onetimeSurcharge.classList.remove('hidden');
            onetimeUpsell.classList.remove('hidden');
        } else {
            // Předplatné
            summaryTitle.textContent = texts.subscriptionTitle;
            summaryTotalLabel.textContent = texts.pricePerBox;
            onetimeSurcharge.classList.add('hidden');
            onetimeUpsell.classList.add('hidden');
        }
        summaryPriceNote.textContent = selectedFrequency !== null
            ? frequencyNotes[selectedFrequency]
            : texts.perDelivery;

        // Cena
        if (selectedAmount) {
            let price = Number(pricing[selectedAmount]) || 0;
            if (isDecaf) {
                price += surcharge;
            }
            // Přidat příplatek pro jednorázový box
            if (selectedFrequency === 0) {
                price += surcharge;
            }
            document.getElementById('summary-price').textContent = price.toLocaleString(numberLocale);
        } else {
            document.getElementById('summary-price').textContent = '-';
        }
        
        // Povolit tlačítko submit
        const submitButton = document.getElementById('submit-button');
        // Kontrola: selectedFrequency může být 0 (jednorázově), takže musíme kontrolovat !== null
        if (selectedAmount && selectedType && selectedFrequency !== null) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }
    
    // "Přepnout na předplatné" v nabídce pod jednorázovým boxem
    document.getElementById('switch-to-subscription')?.addEventListener('click', function() {
        const monthly = document.querySelector('input[name="frequency"][value="1"]');
        if (monthly) {
            monthly.checked = true;
            monthly.dispatchEvent(new Event('change'));
        }
    });

    // Automatický výběr plánu z URL parametru
    // (Musí být až ZA definicí všech event listenerů!)
    const urlParams = new URLSearchParams(window.location.search);
    const planParam = urlParams.get('plan');
    
    if (planParam) {
        // Najít a označit správný radio button
        const planRadio = document.querySelector(`input[name="amount"][value="${planParam}"]`);
        if (planRadio) {
            planRadio.checked = true;
            planRadio.dispatchEvent(new Event('change'));
            
            // Odscrollovat k sekci "Množství kávy" s malým offsetem
            setTimeout(() => {
                const amountSection = planRadio.closest('.mb-10');
                if (amountSection) {
                    const yOffset = -20; // Offset od horní hrany (může být 0 nebo menší záporné číslo)
                    const y = amountSection.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            }, 100);
        }
    }
    
    // Apply availability restrictions on page load
    updateAvailabilityUI();
    
    // Tracking: add_to_cart (Google tag + Meta Pixel)
    document.getElementById('subscription-configurator')?.addEventListener('submit', function() {
        var amount = document.querySelector('input[name="amount"]:checked')?.value || '3';
        var boxNames = {'2': 'M Box', '3': 'L Box', '4': 'XL Box'};
        var price = Number(pricing[amount]) || 0;
        var currency = @json(\App\Helpers\CurrencyHelper::code());
        var itemId = 'subscription-' + amount;

        gtag('event', 'add_to_cart', {
            'currency': currency,
            'value': price,
            'items': [{
                'item_id': itemId,
                'item_name': 'Subscription ' + boxNames[amount],
                'price': price,
                'quantity': 1,
                'item_category': 'subscription'
            }]
        });

        // Meta Pixel - AddToCart
        if (typeof fbq !== 'undefined') {
            fbq('track', 'AddToCart', {
                content_ids: [itemId],
                content_type: 'product',
                value: price,
                currency: currency
            });
        }
    });
});
</script>
@endsection
