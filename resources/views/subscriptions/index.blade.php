@extends('layouts.app')

@section('title', $currentLocale === 'en' ? 'Coffee Subscription - KAVI' : 'Kávové předplatné - KAVI.cz')

@section('meta_description')
{{ $currentLocale === 'en' ? 'Build your custom coffee subscription box. Choose quantity, coffee type and delivery frequency. Flexible, no commitment, free shipping. Premium specialty coffee from Europe.' : 'Sestavte si vlastní kávový box. Vyberte množství, typ kávy a frekvenci dodání. Flexibilní, bez závazků, doprava zdarma. Prémiová výběrová káva z Evropy.' }}
@endsection

@section('og_title')
{{ $currentLocale === 'en' ? 'Build Your Coffee Subscription Box | KAVI' : 'Sestavte si svůj kávový box | KAVI.cz' }}
@endsection

@section('og_description')
{{ $currentLocale === 'en' ? 'Flexible coffee subscription with free shipping. Choose your box size, coffee type, and delivery frequency. Cancel anytime.' : 'Flexibilní kávové předplatné s dopravou zdarma. Vyberte si velikost boxu, typ kávy a frekvenci dodání. Zrušte kdykoliv.' }}
@endsection

@section('content')
<!-- Hero Header Section - Quiet Luxury -->
<div class="relative py-16 sm:py-20 md:py-24" style="background-color: #F5F5F0;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="text-center max-w-3xl mx-auto">
      <!-- Badge -->
      <div class="inline-flex items-center bg-olive-500 px-5 py-2.5 mb-8">
        <span class="text-sm font-light text-white uppercase tracking-widest">{{ $currentLocale === 'en' ? 'Coffee Subscription' : 'Kávové předplatné' }}</span>
      </div>

      <!-- Heading - Editorial Typography -->
      <h1 class="font-display text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-normal text-dark-800 leading-tight tracking-tight uppercase mb-6">
        {{ $currentLocale === 'en' ? 'Build your coffee box' : 'Sestavte si svůj kávový box' }}
      </h1>
      
      <p class="mx-auto max-w-2xl text-base sm:text-lg text-warm-500 font-light mb-10">
        {{ $currentLocale === 'en' ? 'Choose the quantity, coffee type and delivery frequency. Simple and no commitment.' : 'Vyberte si množství, typ kávy a frekvenci dodání. Jednoduše a bez závazků.' }}
      </p>

      <!-- Features - Clean Text -->
      <div class="flex flex-wrap items-center justify-center gap-8">
        <div class="flex items-center gap-3">
          <span class="w-1.5 h-1.5 bg-primary-500"></span>
          <span class="text-sm font-light text-dark-700">{{ $currentLocale === 'en' ? 'Like clockwork' : 'Doprava zdarma' }}</span>
        </div>
        <div class="flex items-center gap-3">
          <span class="w-1.5 h-1.5 bg-primary-500"></span>
          <span class="text-sm font-light text-dark-700">{{ $currentLocale === 'en' ? 'Flexible payment' : 'Flexibilní platba' }}</span>
        </div>
        <div class="flex items-center gap-3">
          <span class="w-1.5 h-1.5 bg-primary-500"></span>
          <span class="text-sm font-light text-dark-700">{{ $currentLocale === 'en' ? 'Cancel anytime' : 'Zrušení kdykoliv' }}</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Content Container - 2 Column Layout -->
<div class="bg-white py-12 sm:py-16 lg:py-20">
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
      
      <!-- LEFT COLUMN - Product Photo + Link -->
      <div class="lg:col-span-5">
        <div class="lg:sticky lg:top-24">
          <!-- Product Photo -->
          <div class="relative aspect-[1/1] sm:aspect-square overflow-hidden mb-6">
            @php
              // For backward compatibility: old images start with 'promo-images/' (storage), new ones with 'images/promo-images/' (public)
              $imageSrc = str_starts_with($promoImage, 'promo-images/') 
                  ? asset('storage/' . $promoImage) 
                  : asset($promoImage);
            @endphp
            <img src="{{ $imageSrc }}" 
                 alt="Kávový box" 
                 class="w-full h-full object-cover">
          </div>
          
          <!-- Roasteries for Current Month -->
          <div class="p-5 bg-stone-100">
            <div class="flex items-center gap-2 mb-4">
              <span class="w-2 h-2 bg-primary-500"></span>
              <h3 class="font-display text-sm font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Roasters for ' . $monthName . ' ' . $displayYear : 'Pražírny na ' . $monthName . ' ' . $displayYear }}</h3>
            </div>
            
            @if($roasteriesOfMonth && $roasteriesOfMonth->count() > 0)
              <div class="space-y-2 mb-4">
                @foreach($roasteriesOfMonth as $roastery)
                  <div class="flex items-center gap-2">
                    <span class="text-lg">{{ $roastery->country_flag }}</span>
                    <div class="flex-1">
                      <span class="text-sm text-dark-700 font-medium">{{ $roastery->getName() }}</span>
                      <span class="text-xs text-warm-500 font-light ml-1">{{ $roastery->getCity() ? $roastery->getCity() . ', ' : '' }}{{ $roastery->getCountry() }}</span>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
            
            <a href="{{ localizedRoute('monthly-feature.index') }}" 
               class="block w-full py-2.5 bg-dark-800 text-white font-medium hover:bg-dark-700 transition-all duration-200 text-sm text-center">
              {{ $currentLocale === 'en' ? 'View detailed information' : 'Zobrazit detailní informace' }}
            </a>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN - Form -->
      <div class="lg:col-span-7">
        @if($availability['allSoldOut'])
        <!-- Sold Out Message -->
        <div class="bg-white border border-warm-300 p-8 text-center">
          <div class="mb-6">
            <span class="font-display text-6xl text-warm-400">☕</span>
          </div>
          
          <h2 class="font-display text-3xl font-normal text-dark-800 mb-4 uppercase">
            {{ $currentLocale === 'en' ? 'Sorry, our coffees are sold out this month' : 'Omlouváme se, tento měsíc jsou naše kávy vyprodané' }}
          </h2>
          
          <p class="text-lg text-warm-500 mb-6 max-w-2xl mx-auto font-light">
            {{ $currentLocale === 'en' ? 'Thank you for your interest! All our coffees for this month are unfortunately already taken.' : 'Děkujeme za velký zájem! Všechny naše kávy pro tento měsíc jsou bohužel již rozebrané.' }}
          </p>
          
          <div class="border border-primary-300 p-6 mb-8 max-w-2xl mx-auto" style="background-color: #F5F5F0;">
            <p class="text-base text-dark-800 font-medium mb-2">
              {{ $currentLocale === 'en' ? 'New coffees will be available from' : 'Nové kávy budou k dispozici od' }} <strong>{{ $currentLocale === 'en' ? $nextAvailableDate->format('m/d/Y') : $nextAvailableDate->format('d.m.Y') }}</strong>
            </p>
            <p class="text-sm text-warm-500 font-light">
              ({{ $nextAvailableMonthName }} {{ $nextAvailableDate->year }})
            </p>
          </div>
          
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ localizedRoute('products.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-dark-800 text-white font-medium hover:bg-dark-700 transition-all duration-200">
              {{ $currentLocale === 'en' ? 'Browse one-time products' : 'Prohlédnout jednorázové produkty' }}
              <span class="ml-2">&rarr;</span>
            </a>
            
            <a href="{{ route('home') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-white text-dark-800 font-medium border border-warm-400 hover:border-dark-800 transition-all duration-200">
              {{ $currentLocale === 'en' ? 'Back to homepage' : 'Zpět na hlavní stránku' }}
            </a>
          </div>
        </div>
        @else
        <form id="subscription-configurator" method="POST" action="{{ localizedRoute('subscriptions.configure.checkout') }}">
          @csrf
          
          <!-- Hidden inputy pro mix rozdělení -->
          <input type="hidden" name="mix[espresso]" id="mix-espresso-value" value="0">
          <input type="hidden" name="mix[filter]" id="mix-filter-value" value="0">
          
          <!-- KROK 1 - Množství kávy -->
          <div class="mb-12 border-t border-warm-300 pt-8">
            <div class="mb-6">
              <span class="font-display text-5xl font-normal text-dark-800 mb-4 block">01</span>
              <h2 class="font-display text-2xl font-normal text-dark-800 uppercase tracking-wide mb-2">{{ $currentLocale === 'en' ? 'Coffee quantity' : 'Množství kávy' }}</h2>
              <p class="text-sm text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Choose the package that suits you' : 'Vyberte balíček, který vám vyhovuje' }}</p>
            </div>

            <div class="divide-y divide-stone-200">
              <!-- 500g plán -->
              <label class="group flex items-center justify-between py-6 cursor-pointer transition-all border-l-4 border-l-transparent has-[:checked]:border-l-dark-800 has-[:checked]:bg-stone-50 px-4 -mx-4">
                <input type="radio" name="amount" value="2" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="font-display text-2xl font-normal text-dark-800 uppercase">M Box</div>
                    <p class="text-xs text-warm-500 font-light">{{ $currentLocale === 'en' ? '2 bags of 250g' : '2 balíčky po 250g' }}</p>
                  </div>
                  <div class="hidden sm:block flex-1">
                    <p class="text-sm text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Ideal for individuals or couples' : 'Ideální pro jednotlivce nebo páry' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  @if($currentLocale === 'en')
                    <span class="text-sm text-warm-500 mr-1">€</span><span class="font-display text-2xl font-normal text-dark-800">{{ number_format($subscriptionPricing['2'], 0, '.', ' ') }}</span>
                  @else
                    <span class="font-display text-2xl font-normal text-dark-800">{{ number_format($subscriptionPricing['2'], 0, ',', ' ') }}</span><span class="text-sm text-warm-500 ml-1">Kč</span>
                  @endif
                </div>
              </label>

              <!-- 750g plán -->
              <label class="group flex items-center justify-between py-6 cursor-pointer transition-all border-l-4 border-l-transparent has-[:checked]:border-l-dark-800 has-[:checked]:bg-stone-50 px-4 -mx-4">
                <input type="radio" name="amount" value="3" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="font-display text-2xl font-normal text-dark-800 uppercase">L Box</div>
                    <p class="text-xs text-warm-500 font-light">{{ $currentLocale === 'en' ? '3 bags of 250g' : '3 balíčky po 250g' }}</p>
                  </div>
                  <div class="hidden sm:block flex-1">
                    <p class="text-sm text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Most popular choice' : 'Nejpopulárnější volba' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  @if($currentLocale === 'en')
                    <span class="text-sm text-warm-500 mr-1">€</span><span class="font-display text-2xl font-normal text-dark-800">{{ number_format($subscriptionPricing['3'], 0, '.', ' ') }}</span>
                  @else
                    <span class="font-display text-2xl font-normal text-dark-800">{{ number_format($subscriptionPricing['3'], 0, ',', ' ') }}</span><span class="text-sm text-warm-500 ml-1">Kč</span>
                  @endif
                </div>
              </label>

              <!-- 1000g plán -->
              <label class="group flex items-center justify-between py-6 cursor-pointer transition-all border-l-4 border-l-transparent has-[:checked]:border-l-dark-800 has-[:checked]:bg-stone-50 px-4 -mx-4">
                <input type="radio" name="amount" value="4" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="font-display text-2xl font-normal text-dark-800 uppercase">XL Box</div>
                    <p class="text-xs text-warm-500 font-light">{{ $currentLocale === 'en' ? '4 bags of 250g' : '4 balíčky po 250g' }}</p>
                  </div>
                  <div class="hidden sm:block flex-1">
                    <p class="text-sm text-warm-500 font-light">{{ $currentLocale === 'en' ? 'For coffee enthusiasts' : 'Pro kávové nadšence' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  @if($currentLocale === 'en')
                    <span class="text-sm text-warm-500 mr-1">€</span><span class="font-display text-2xl font-normal text-dark-800">{{ number_format($subscriptionPricing['4'], 0, '.', ' ') }}</span>
                  @else
                    <span class="font-display text-2xl font-normal text-dark-800">{{ number_format($subscriptionPricing['4'], 0, ',', ' ') }}</span><span class="text-sm text-warm-500 ml-1">Kč</span>
                  @endif
                </div>
              </label>
            </div>
          </div>

          <!-- KROK 2 - Typ kávy -->
          <div class="mb-12 border-t border-warm-300 pt-8">
            <div class="mb-6">
              <span class="font-display text-5xl font-normal text-dark-800 mb-4 block">02</span>
              <h2 class="font-display text-2xl font-normal text-dark-800 uppercase tracking-wide mb-2">{{ $currentLocale === 'en' ? 'Preferred coffee type' : 'Preferovaný typ kávy' }}</h2>
              <p class="text-sm text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Choose your favorite brewing method' : 'Vyberte si váš oblíbený způsob přípravy' }}</p>
            </div>

            <div class="divide-y divide-stone-200">
              <!-- Espresso -->
              <label class="group flex items-center justify-between py-6 cursor-pointer transition-all border-l-4 border-l-transparent has-[:checked]:border-l-dark-800 has-[:checked]:bg-stone-50 px-4 -mx-4">
                <input type="radio" name="type" value="espresso" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="font-display text-xl font-normal text-dark-800 uppercase">Espresso</div>
                    <p class="text-xs text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Full body, darker roast' : 'Plné tělo, tmavší pražení' }}</p>
                  </div>
                </div>
                <label class="flex items-center gap-2 px-3 py-1.5 bg-stone-100 hover:bg-stone-200 transition-colors text-sm" onclick="event.stopPropagation();">
                  <input type="checkbox" name="isDecaf" value="1" class="w-4 h-4 text-primary-500">
                  <span class="font-medium text-dark-700">{{ $currentLocale === 'en' ? '1x decaf' : '1x bez kofeinu' }}<br class="sm:hidden"> (+{{ $currentLocale === 'en' ? '€5' : '100 Kč' }})</span>
                </label>
              </label>

              <!-- Filtr -->
              <label class="group flex items-center justify-between py-6 cursor-pointer transition-all border-l-4 border-l-transparent has-[:checked]:border-l-dark-800 has-[:checked]:bg-stone-50 px-4 -mx-4">
                <input type="radio" name="type" value="filter" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="font-display text-xl font-normal text-dark-800 uppercase">{{ $currentLocale === 'en' ? 'Filter' : 'Filtr' }}</div>
                    <p class="text-xs text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Lighter body, lighter roast' : 'Lehčí tělo, světlejší pražení' }}</p>
                  </div>
                </div>
                <label class="flex items-center gap-2 px-3 py-1.5 bg-stone-100 hover:bg-stone-200 transition-colors text-sm" onclick="event.stopPropagation();">
                  <input type="checkbox" name="isDecaf" value="1" class="w-4 h-4 text-primary-500">
                  <span class="font-medium text-dark-700">{{ $currentLocale === 'en' ? '1x decaf' : '1x bez kofeinu' }}<br class="sm:hidden"> (+{{ $currentLocale === 'en' ? '€5' : '100 Kč' }})</span>
                </label>
              </label>

              <!-- Kombinace -->
              <label class="group flex items-center justify-between py-6 cursor-pointer transition-all border-l-4 border-l-transparent has-[:checked]:border-l-dark-800 has-[:checked]:bg-stone-50 px-4 -mx-4">
                <input type="radio" name="type" value="mix" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="font-display text-xl font-normal text-dark-800 uppercase">{{ $currentLocale === 'en' ? 'Mix' : 'Kombinace' }}</div>
                    <p class="text-xs text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Espresso and filter' : 'Espresso i filtr' }}</p>
                  </div>
                </div>
                <label class="flex items-center gap-2 px-3 py-1.5 bg-stone-100 hover:bg-stone-200 transition-colors text-sm" onclick="event.stopPropagation();">
                  <input type="checkbox" name="isDecaf" value="1" class="w-4 h-4 text-primary-500">
                  <span class="font-medium text-dark-700">{{ $currentLocale === 'en' ? '1x decaf' : '1x bez kofeinu' }}<br class="sm:hidden"> (+{{ $currentLocale === 'en' ? '€5' : '100 Kč' }})</span>
                </label>
              </label>
            </div>

            <!-- Rozdělení kávy pro mix -->
            <div id="caffeine-distribution" class="hidden mt-6 p-5 bg-stone-100">
              <h4 class="font-display text-lg font-normal text-dark-800 mb-4 uppercase">{{ $currentLocale === 'en' ? 'Coffee distribution' : 'Rozdělení kávy' }}</h4>
              
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
          <div class="mb-12 border-t border-warm-300 pt-8">
            <div class="mb-6">
              <span class="font-display text-5xl font-normal text-dark-800 mb-4 block">03</span>
              <h2 class="font-display text-2xl font-normal text-dark-800 uppercase tracking-wide mb-2">{{ $currentLocale === 'en' ? 'Delivery frequency' : 'Frekvence dodání' }}</h2>
              <p class="text-sm text-warm-500 font-light">{{ $currentLocale === 'en' ? 'How often do you want to receive coffee?' : 'Jak často chcete kávu dostávat?' }}</p>
            </div>

            <div class="divide-y divide-stone-200">
              <!-- Každý měsíc -->
              <label class="group flex items-center justify-between py-6 cursor-pointer transition-all border-l-4 border-l-transparent has-[:checked]:border-l-dark-800 has-[:checked]:bg-stone-50 px-4 -mx-4">
                <input type="radio" name="frequency" value="1" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="font-display text-xl font-normal text-dark-800 uppercase">{{ $currentLocale === 'en' ? 'Every month' : 'Každý měsíc' }}</div>
                    <p class="text-xs text-warm-500 font-light">{{ $currentLocale === 'en' ? 'For regular consumption' : 'Pro pravidelnou spotřebu' }}</p>
                  </div>
                </div>
              </label>

              <!-- Jednou za 2 měsíce -->
              <label class="group flex items-center justify-between py-6 cursor-pointer transition-all border-l-4 border-l-transparent has-[:checked]:border-l-dark-800 has-[:checked]:bg-stone-50 px-4 -mx-4">
                <input type="radio" name="frequency" value="2" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="font-display text-xl font-normal text-dark-800 uppercase">{{ $currentLocale === 'en' ? 'Every 2 months' : 'Jednou za 2 měsíce' }}</div>
                    <p class="text-xs text-warm-500 font-light">{{ $currentLocale === 'en' ? 'For medium consumption' : 'Pro střední spotřebu' }}</p>
                  </div>
                </div>
              </label>

              <!-- Jednou za 3 měsíce -->
              <label class="group flex items-center justify-between py-6 cursor-pointer transition-all border-l-4 border-l-transparent has-[:checked]:border-l-dark-800 has-[:checked]:bg-stone-50 px-4 -mx-4">
                <input type="radio" name="frequency" value="3" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="font-display text-xl font-normal text-dark-800 uppercase">{{ $currentLocale === 'en' ? 'Every 3 months' : 'Jednou za 3 měsíce' }}</div>
                    <p class="text-xs text-warm-500 font-light">{{ $currentLocale === 'en' ? 'For occasional drinking' : 'Pro občasné pití' }}</p>
                  </div>
                </div>
              </label>

              <!-- Jednorázově (bez předplatného) -->
              <label class="group flex items-center justify-between py-6 cursor-pointer transition-all border-l-4 border-l-transparent has-[:checked]:border-l-dark-800 has-[:checked]:bg-stone-50 px-4 -mx-4">
                <input type="radio" name="frequency" value="0" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="flex items-center gap-2">
                      <span class="font-display text-xl font-normal text-dark-800 uppercase">{{ $currentLocale === 'en' ? 'One-time' : 'Jednorázově' }}</span>
                      <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-olive-500 text-white text-xs font-medium">
                        <span>{{ $currentLocale === 'en' ? 'Try it' : 'Zkuste to' }}</span>
                      </span>
                    </div>
                    <p class="text-xs text-warm-500 font-light">{{ $currentLocale === 'en' ? 'No subscription' : 'Bez předplatného' }}</p>
                  </div>
                </div>
                <span class="px-3 py-1.5 bg-stone-100 text-sm font-medium text-dark-700">+{{ $currentLocale === 'en' ? '€5' : '100 Kč' }}</span>
              </label>
            </div>
          </div>

          <!-- Shrnutí a Submit -->
          <div class="p-5 sm:p-6 bg-stone-100 mt-8">
            <h3 id="summary-title" class="font-display text-lg sm:text-xl font-normal text-dark-800 mb-4 uppercase">{{ $currentLocale === 'en' ? 'Order summary' : 'Shrnutí objednávky' }}</h3>
            
            <div class="space-y-2 mb-6">
              <div class="flex justify-between text-sm">
                <span class="text-warm-500">{{ $currentLocale === 'en' ? 'Quantity:' : 'Množství:' }}</span>
                <span class="font-medium text-dark-800" id="summary-amount">-</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-warm-500">{{ $currentLocale === 'en' ? 'Coffee type:' : 'Typ kávy:' }}</span>
                <span class="font-medium text-dark-800" id="summary-type">-</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-warm-500">{{ $currentLocale === 'en' ? 'Frequency:' : 'Frekvence:' }}</span>
                <span class="font-medium text-dark-800" id="summary-frequency">-</span>
              </div>
              <div id="onetime-surcharge" class="hidden flex justify-between text-sm">
                <span class="text-warm-500">{{ $currentLocale === 'en' ? 'One-time surcharge:' : 'Příplatek jednorázový:' }}</span>
                <span class="font-medium text-dark-800">+{{ $currentLocale === 'en' ? '€5' : '100 Kč' }}</span>
              </div>
              <div class="flex justify-between items-center pt-4 border-t border-stone-300">
                <span class="font-display text-lg font-normal text-dark-800 uppercase">{{ $currentLocale === 'en' ? 'Total price:' : 'Celková cena:' }}</span>
                <div class="text-right">
                  @if($currentLocale === 'en')
                    <span class="text-sm text-warm-500 mr-1">€</span><span class="font-display text-3xl font-normal text-dark-800" id="summary-price">-</span>
                  @else
                    <span class="font-display text-3xl font-normal text-dark-800" id="summary-price">-</span><span class="text-sm text-warm-500 ml-1">Kč</span>
                  @endif
                  <p id="summary-price-note" class="text-xs text-warm-500 mt-1">{{ $currentLocale === 'en' ? 'per delivery' : 'při každé dodávce' }}</p>
                </div>
              </div>
            </div>

            <button type="submit" id="submit-button" 
                    class="w-full py-3 bg-dark-800 text-white font-medium hover:bg-dark-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2" 
                    disabled>
              <span>{{ $currentLocale === 'en' ? 'Continue to checkout' : 'Pokračovat k objednávce' }}</span>
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </button>

            <div class="flex items-center justify-center gap-6 mt-4 text-xs text-warm-500">
              <span class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500"></span>
                {{ $currentLocale === 'en' ? 'No commitment' : 'Bez závazků' }}
              </span>
              <span class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500"></span>
                {{ $currentLocale === 'en' ? 'Cancel anytime' : 'Kdykoli zrušte' }}
              </span>
            </div>
          </div>
        </form>
        @endif

        <!-- Shipping Date Info -->
        <div class="mt-8 p-5 bg-stone-100">
          <h3 class="font-display text-lg font-normal text-dark-800 mb-2 uppercase">{{ $currentLocale === 'en' ? 'Next shipping date' : 'Termín následující rozesílky' }}</h3>
          <p class="text-sm text-dark-800 font-medium mb-2">{{ $shippingInfo['cutoff_message'] }}</p>
          <p class="text-sm text-warm-500 font-light">
            @if($currentLocale === 'en')
            Orders close on the <strong class="text-dark-800">15th of each month at midnight</strong>. 
            Coffee shipping usually happens on the <strong class="text-dark-800">20th of each month</strong>. 
            @else
            Objednávky uzavíráme <strong class="text-dark-800">15. dne v měsíci o půlnoci</strong>. 
            Rozesílka kávy probíhá nejčastěji <strong class="text-dark-800">20. dne v měsíci</strong>. 
            @endif
          </p>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pricing = @json($subscriptionPricing);
    const currentLocale = '{{ $currentLocale }}';
    const currencySymbol = currentLocale === 'en' ? '€' : 'Kč';
    
    // Load availability data from backend
    const availability = @json($availability);
    
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
                mixEspressoCount = Math.floor(selectedAmount / 2);
                mixFilterCount = selectedAmount - mixEspressoCount;
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
                mixEspressoCount = Math.floor(selectedAmount / 2);
                mixFilterCount = selectedAmount - mixEspressoCount;
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
        
        if (mixEspressoPlus) mixEspressoPlus.disabled = mixFilterCount <= 0;
        if (mixEspressoMinus) mixEspressoMinus.disabled = mixEspressoCount <= 0;
        if (mixFilterPlus) mixFilterPlus.disabled = mixEspressoCount <= 0;
        if (mixFilterMinus) mixFilterMinus.disabled = mixFilterCount <= 0;
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
    
    // Function to update UI based on availability
    function updateAvailabilityUI() {
        console.log('Updating availability UI:', availability);
        
        // Find radio buttons by their name/value attributes
        const espressoRadio = document.querySelector('input[name="type"][value="espresso"]');
        const filterRadio = document.querySelector('input[name="type"][value="filter"]');
        const mixRadio = document.querySelector('input[name="type"][value="mix"]');
        
        // Get parent label elements
        const espressoLabel = espressoRadio?.closest('label.group');
        const filterLabel = filterRadio?.closest('label.group');
        const mixLabel = mixRadio?.closest('label.group');
        
        // Find all decaf checkboxes (there are 3 - one for each type)
        const decafCheckboxes = document.querySelectorAll('input[name="isDecaf"]');

        // Handle Espresso availability
        if (!availability.espresso && espressoLabel) {
            console.log('Disabling espresso option');
            espressoLabel.classList.add('cursor-not-allowed');
            espressoLabel.style.pointerEvents = 'none';
            espressoLabel.style.position = 'relative';
            espressoLabel.style.backgroundColor = '#f3f4f6'; // gray-100
            
            // Add opacity for content
            const contentDivs = espressoLabel.querySelectorAll('div:not(.sold-out-label)');
            contentDivs.forEach(div => {
                div.style.opacity = '0.5';
            });
            
            // Disable the radio button
            if (espressoRadio) {
                espressoRadio.disabled = true;
                if (espressoRadio.checked) {
                    espressoRadio.checked = false;
                    selectedType = null;
                }
            }
            
            // Add sold out badge if not already present
            if (!espressoLabel.querySelector('.sold-out-label')) {
                const badge = document.createElement('div');
                badge.className = 'sold-out-label absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-md shadow-md';
                badge.style.opacity = '1'; // Ensure full opacity
                badge.textContent = currentLocale === 'en' ? 'Sold out this month' : 'Tento měsíc vyprodáno';
                espressoLabel.appendChild(badge);
            }
        }

        // Handle Filter availability
        if (!availability.filter && filterLabel) {
            console.log('Disabling filter option');
            filterLabel.classList.add('cursor-not-allowed');
            filterLabel.style.pointerEvents = 'none';
            filterLabel.style.position = 'relative';
            filterLabel.style.backgroundColor = '#f3f4f6'; // gray-100
            
            // Add opacity for content
            const contentDivs = filterLabel.querySelectorAll('div:not(.sold-out-label)');
            contentDivs.forEach(div => {
                div.style.opacity = '0.5';
            });
            
            // Disable the radio button
            if (filterRadio) {
                filterRadio.disabled = true;
                if (filterRadio.checked) {
                    filterRadio.checked = false;
                    selectedType = null;
                }
            }
            
            // Add sold out badge if not already present
            if (!filterLabel.querySelector('.sold-out-label')) {
                const badge = document.createElement('div');
                badge.className = 'sold-out-label absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-md shadow-md';
                badge.style.opacity = '1'; // Ensure full opacity
                badge.textContent = currentLocale === 'en' ? 'Sold out this month' : 'Tento měsíc vyprodáno';
                filterLabel.appendChild(badge);
            }
        }

        // Handle Mix availability
        if (!availability.mix && mixLabel) {
            console.log('Disabling mix option');
            mixLabel.classList.add('cursor-not-allowed');
            mixLabel.style.pointerEvents = 'none';
            mixLabel.style.position = 'relative';
            mixLabel.style.backgroundColor = '#f3f4f6'; // gray-100
            
            // Add opacity for content
            const contentDivs = mixLabel.querySelectorAll('div:not(.sold-out-label)');
            contentDivs.forEach(div => {
                div.style.opacity = '0.5';
            });
            
            // Disable the radio button
            if (mixRadio) {
                mixRadio.disabled = true;
                if (mixRadio.checked) {
                    mixRadio.checked = false;
                    selectedType = null;
                }
            }
            
            // Add sold out badge if not already present
            if (!mixLabel.querySelector('.sold-out-label')) {
                const badge = document.createElement('div');
                badge.className = 'sold-out-label absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-md shadow-md';
                badge.style.opacity = '1'; // Ensure full opacity
                badge.textContent = currentLocale === 'en' ? 'Sold out this month' : 'Tento měsíc vyprodáno';
                mixLabel.appendChild(badge);
            }
        }

        // Handle Decaf availability - disable ALL decaf checkboxes
        if (!availability.decaf && decafCheckboxes.length > 0) {
            console.log('Disabling decaf options, found checkboxes:', decafCheckboxes.length);
            decafCheckboxes.forEach((checkbox) => {
                // Find the parent label container for this checkbox
                const decafContainer = checkbox.closest('label');
                
                if (decafContainer) {
                    decafContainer.classList.add('opacity-50', 'cursor-not-allowed');
                    decafContainer.style.pointerEvents = 'none';
                }
                
                // Disable and uncheck the checkbox
                checkbox.disabled = true;
                if (checkbox.checked) {
                    checkbox.checked = false;
                    isDecaf = false;
                }
            });
            
            // Add note after the type options container (only once)
            const typeOptionsContainer = document.querySelector('.space-y-3');
            if (typeOptionsContainer && !typeOptionsContainer.querySelector('.decaf-unavailable-note')) {
                const note = document.createElement('div');
                note.className = 'decaf-unavailable-note mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700';
                note.innerHTML = currentLocale === 'en' 
                    ? '<strong>Notice:</strong> Decaf coffee is not available this month'
                    : '<strong>Upozornění:</strong> Bezkofeinová káva již není tento měsíc k dispozici';
                
                // Insert after the last coffee type option
                const lastTypeOption = typeOptionsContainer.querySelector('label.group:last-child');
                if (lastTypeOption) {
                    lastTypeOption.insertAdjacentElement('afterend', note);
                }
            }
        }
    }
    
    function updateSummary() {
        // Množství
        if (selectedAmount) {
            const grams = selectedAmount * 250;
            const bagsText = currentLocale === 'en' ? ' bags' : ' balení';
            document.getElementById('summary-amount').textContent = grams + 'g (' + selectedAmount + bagsText + ')';
        } else {
            document.getElementById('summary-amount').textContent = '-';
        }
        
        // Typ
        let typeText = '-';
        if (selectedType) {
            const types = currentLocale === 'en' ? {
                'espresso': 'Espresso',
                'filter': 'Filter',
                'mix': 'Mix'
            } : {
                'espresso': 'Espresso',
                'filter': 'Filtr',
                'mix': 'Kombinace'
            };
            typeText = types[selectedType];
            
            const filterLabel = currentLocale === 'en' ? 'Filter' : 'Filtr';
            if (selectedType === 'mix' && (mixEspressoCount > 0 || mixFilterCount > 0)) {
                typeText += ` (${mixEspressoCount}x Espresso, ${mixFilterCount}x ${filterLabel})`;
            }
            
            if (isDecaf) {
                const decafSurcharge = currentLocale === 'en' ? '+€5' : '+100 Kč';
                typeText += ` (1x decaf) ${decafSurcharge}`;
            }
        }
        document.getElementById('summary-type').textContent = typeText;
        
        // Frekvence
        const frequencies = currentLocale === 'en' ? {
            0: 'One-time (no subscription)',
            1: 'Every month',
            2: 'Every 2 months',
            3: 'Every 3 months'
        } : {
            0: 'Jednorázově (bez předplatného)',
            1: 'Každý měsíc',
            2: 'Jednou za 2 měsíce',
            3: 'Jednou za 3 měsíce'
        };
        document.getElementById('summary-frequency').textContent = frequencies[selectedFrequency] || '-';
        
        // Dynamicky změnit nadpis a texty podle typu objednávky
        const summaryTitle = document.getElementById('summary-title');
        const summaryPriceNote = document.getElementById('summary-price-note');
        const onetimeSurcharge = document.getElementById('onetime-surcharge');
        
        if (selectedFrequency === 0) {
            // Jednorázová objednávka
            summaryTitle.textContent = currentLocale === 'en' ? 'Order summary' : 'Shrnutí objednávky';
            summaryPriceNote.textContent = currentLocale === 'en' ? 'one-time' : 'jednorázově';
            onetimeSurcharge.classList.remove('hidden');
        } else {
            // Předplatné
            summaryTitle.textContent = currentLocale === 'en' ? 'Subscription summary' : 'Shrnutí předplatného';
            summaryPriceNote.textContent = currentLocale === 'en' ? 'per delivery' : 'při každé dodávce';
            onetimeSurcharge.classList.add('hidden');
        }
        
        // Cena
        if (selectedAmount) {
            let price = pricing[selectedAmount] || 0;
            if (isDecaf) {
                price += currentLocale === 'en' ? 5 : 100;
            }
            // Přidat příplatek pro jednorázový box
            if (selectedFrequency === 0) {
                price += currentLocale === 'en' ? 5 : 100;
            }
            document.getElementById('summary-price').textContent = price.toLocaleString(currentLocale === 'en' ? 'en-US' : 'cs-CZ');
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
});
</script>
@endsection
