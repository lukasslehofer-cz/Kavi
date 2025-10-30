@extends('layouts.app')

@section('title', 'Kávové předplatné - Kavi Coffee')

@section('content')
<!-- Hero Header Section - Minimal -->
<div class="relative bg-gray-100 py-12 md:py-16 overflow-hidden">
  <!-- Subtle Organic Shapes -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary-100 rounded-full"></div>
    <div class="absolute -bottom-32 -left-32 w-[36rem] h-[36rem] bg-primary-50 rounded-full hidden md:block"></div>
  </div>
  
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="text-center max-w-3xl mx-auto">
      <!-- Minimal Badge -->
      <div class="inline-flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 mb-6">
        <svg class="w-4 h-4 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        <span class="text-sm font-medium text-gray-900">Konfigurátor předplatného</span>
      </div>

      <!-- Clean Heading -->
      <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
        Sestavte si své kávové předplatné
      </h1>
      
      <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light mb-8">
        Vyberte si množství, typ kávy a frekvenci dodání. Jednoduše a bez závazků.
      </p>

      <!-- Features Pills - Minimal -->
      <div class="flex flex-wrap items-center justify-center gap-3">
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-gray-200">
          <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span class="text-sm font-medium text-gray-700">Doprava zdarma</span>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-gray-200">
          <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="text-sm font-medium text-gray-700">Flexibilní platba</span>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-gray-200">
          <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          <span class="text-sm font-medium text-gray-700">Zrušení kdykoliv</span>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Wave Divider -->
  <div class="absolute bottom-[-1px] left-0 right-0">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
      <path d="M0 80L60 73C120 67 240 53 360 48C480 43 600 47 720 53C840 59 960 67 1080 69C1200 71 1320 67 1380 65L1440 63V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#ffffff"/>
    </svg>
  </div>
</div>

<!-- Main Content Container - 2 Column Layout -->
<div class="bg-white py-12 sm:py-16 lg:py-20">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">

    <!-- Error Messages -->
    @if($errors->any())
    <div class="mb-6">
      <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg">
        <p class="font-bold mb-2">⚠️ Chyba při zpracování konfigurace:</p>
        <ul class="list-disc list-inside space-y-1">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6">
      <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg">
        <p class="font-bold">⚠️ {{ session('error') }}</p>
      </div>
    </div>
    @endif

    <!-- 2-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
      
      <!-- LEFT COLUMN - Product Photo + Link -->
      <div class="lg:col-span-5">
        <div class="lg:sticky lg:top-24">
          <!-- Product Photo -->
          <div class="relative aspect-square rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 mb-6">
            @php
              // Check if promo image is from storage or public directory
              $imageSrc = str_starts_with($promoImage, 'promo-images/') 
                  ? asset('storage/' . $promoImage) 
                  : asset($promoImage);
            @endphp
            <img src="{{ $imageSrc }}" 
                 alt="Kávový box" 
                 class="w-full h-full object-cover">
          </div>
          
          <!-- Roasteries for Current Month -->
          <div class="p-5 bg-gray-100 rounded-xl border border-gray-200">
            <div class="flex items-center gap-2 mb-4">
              <svg class="w-5 h-5 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
              <h3 class="text-sm font-bold text-gray-900">Pražírny na {{ $monthName }} {{ $displayYear }}</h3>
            </div>
            
            @if($roasteriesOfMonth && $roasteriesOfMonth->count() > 0)
              <div class="space-y-2 mb-4">
                @foreach($roasteriesOfMonth as $roastery)
                  <div class="flex items-center gap-2">
                    <span class="text-lg">{{ $roastery->country_flag }}</span>
                    <div class="flex-1">
                      <span class="text-sm text-gray-700 font-medium">{{ $roastery->name }}</span>
                      <span class="text-xs text-gray-500 font-light ml-1">{{ $roastery->city ? $roastery->city . ', ' : '' }}{{ $roastery->country }}</span>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
            
            <a href="{{ route('monthly-feature.index') }}" 
               class="block w-full py-2.5 bg-gray-900 text-white font-medium rounded-full hover:bg-gray-800 transition-all duration-200 text-sm text-center">
              Zobrazit detailní informace
            </a>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN - Form -->
      <div class="lg:col-span-7">
        <form method="POST" action="{{ route('subscriptions.configure.checkout') }}">
          @csrf
          
          <!-- Hidden inputy pro mix rozdělení -->
          <input type="hidden" name="mix[espresso]" id="mix-espresso-value" value="0">
          <input type="hidden" name="mix[filter]" id="mix-filter-value" value="0">
          
          <!-- KROK 1 - Množství kávy -->
          <div class="mb-10">
            <div class="flex items-center gap-3 mb-6">
              <div class="flex-shrink-0 w-8 h-8 bg-gray-900 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-bold">1</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Množství kávy</h2>
                <p class="text-sm text-gray-600 font-light">Vyberte balíček, který vám vyhovuje</p>
              </div>
            </div>

            <div class="space-y-3">
              <!-- 500g plán -->
              <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 has-[:checked]:border-primary-500 cursor-pointer transition-all bg-white">
                <input type="radio" name="amount" value="2" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="text-2xl font-bold text-gray-900">500g</div>
                    <p class="text-xs text-gray-500 font-light">2 balíčky po 250g</p>
                  </div>
                  <div class="hidden sm:block flex-1">
                    <p class="text-sm text-gray-600 font-light">Ideální pro jednotlivce nebo páry</p>
                  </div>
                </div>
                <div class="flex items-center gap-4">
                  <div class="text-right">
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($subscriptionPricing['2'], 0, ',', ' ') }}</span>
                    <span class="text-sm text-gray-500 ml-1">Kč</span>
                  </div>
                  <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-primary-500 group-has-[:checked]:bg-primary-500 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white opacity-0 group-has-[:checked]:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                </div>
              </label>

              <!-- 750g plán -->
              <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 has-[:checked]:border-primary-500 cursor-pointer transition-all bg-white">
                <input type="radio" name="amount" value="3" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="text-2xl font-bold text-gray-900">750g</div>
                    <p class="text-xs text-gray-500 font-light">3 balíčky po 250g</p>
                  </div>
                  <div class="hidden sm:block flex-1">
                    <p class="text-sm text-gray-600 font-light">Nejpopulárnější volba</p>
                  </div>
                </div>
                <div class="flex items-center gap-4">
                  <div class="text-right">
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($subscriptionPricing['3'], 0, ',', ' ') }}</span>
                    <span class="text-sm text-gray-500 ml-1">Kč</span>
                  </div>
                  <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-primary-500 group-has-[:checked]:bg-primary-500 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white opacity-0 group-has-[:checked]:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                </div>
              </label>

              <!-- 1000g plán -->
              <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 has-[:checked]:border-primary-500 cursor-pointer transition-all bg-white">
                <input type="radio" name="amount" value="4" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="text-2xl font-bold text-gray-900">1000g</div>
                    <p class="text-xs text-gray-500 font-light">4 balíčky po 250g</p>
                  </div>
                  <div class="hidden sm:block flex-1">
                    <p class="text-sm text-gray-600 font-light">Pro kávové nadšence</p>
                  </div>
                </div>
                <div class="flex items-center gap-4">
                  <div class="text-right">
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($subscriptionPricing['4'], 0, ',', ' ') }}</span>
                    <span class="text-sm text-gray-500 ml-1">Kč</span>
                  </div>
                  <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-primary-500 group-has-[:checked]:bg-primary-500 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white opacity-0 group-has-[:checked]:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                </div>
              </label>
            </div>
          </div>

          <!-- KROK 2 - Typ kávy -->
          <div class="mb-10">
            <div class="flex items-center gap-3 mb-6">
              <div class="flex-shrink-0 w-8 h-8 bg-gray-900 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-bold">2</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Preferovaný typ kávy</h2>
                <p class="text-sm text-gray-600 font-light">Vyberte si váš oblíbený způsob přípravy</p>
              </div>
            </div>

            <div class="space-y-3">
              <!-- Espresso -->
              <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 has-[:checked]:border-primary-500 cursor-pointer transition-all bg-white">
                <input type="radio" name="type" value="espresso" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="text-xl font-bold text-gray-900">Espresso</div>
                    <p class="text-xs text-gray-500 font-light">Plné tělo, tmavší pražení</p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <label class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors text-sm" onclick="event.stopPropagation();">
                    <input type="checkbox" name="isDecaf" value="1" class="w-4 h-4 text-primary-500 rounded">
                    <span class="font-medium text-gray-700">+ Decaf</span>
                  </label>
                  <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-primary-500 group-has-[:checked]:bg-primary-500 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white opacity-0 group-has-[:checked]:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                </div>
              </label>

              <!-- Filtr -->
              <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 has-[:checked]:border-primary-500 cursor-pointer transition-all bg-white">
                <input type="radio" name="type" value="filter" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="text-xl font-bold text-gray-900">Filtr</div>
                    <p class="text-xs text-gray-500 font-light">Lehčí tělo, světlejší pražení</p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <label class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors text-sm" onclick="event.stopPropagation();">
                    <input type="checkbox" name="isDecaf" value="1" class="w-4 h-4 text-primary-500 rounded">
                    <span class="font-medium text-gray-700">+ Decaf</span>
                  </label>
                  <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-primary-500 group-has-[:checked]:bg-primary-500 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white opacity-0 group-has-[:checked]:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                </div>
              </label>

              <!-- Kombinace -->
              <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 has-[:checked]:border-primary-500 cursor-pointer transition-all bg-white">
                <input type="radio" name="type" value="mix" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="text-xl font-bold text-gray-900">Kombinace</div>
                    <p class="text-xs text-gray-500 font-light">Espresso i filtr</p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <label class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors text-sm" onclick="event.stopPropagation();">
                    <input type="checkbox" name="isDecaf" value="1" class="w-4 h-4 text-primary-500 rounded">
                    <span class="font-medium text-gray-700">+ Decaf</span>
                  </label>
                  <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-primary-500 group-has-[:checked]:bg-primary-500 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white opacity-0 group-has-[:checked]:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                </div>
              </label>
            </div>

            <!-- Rozdělení kávy pro mix -->
            <div id="caffeine-distribution" class="hidden mt-6 p-4 bg-gray-100 rounded-xl border border-gray-200">
              <h4 class="text-lg font-semibold text-gray-900 mb-4">Rozdělení kávy</h4>
              
              <div id="mix-no-decaf-distribution" class="hidden grid grid-cols-2 gap-4">
                <!-- Espresso -->
                <div class="flex flex-col p-4 bg-white rounded-lg border border-gray-200">
                  <span class="font-medium text-gray-900 mb-2 text-sm">Espresso</span>
                  <div class="flex items-center justify-center gap-3">
                    <button type="button" id="mix-espresso-minus" class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-300 hover:bg-gray-100">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-8 text-center text-xl font-bold" id="mix-espresso-count">0</span>
                    <button type="button" id="mix-espresso-plus" class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-300 hover:bg-gray-100">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
                
                <!-- Filtr -->
                <div class="flex flex-col p-4 bg-white rounded-lg border border-gray-200">
                  <span class="font-medium text-gray-900 mb-2 text-sm">Filtr</span>
                  <div class="flex items-center justify-center gap-3">
                    <button type="button" id="mix-filter-minus" class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-300 hover:bg-gray-100">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-8 text-center text-xl font-bold" id="mix-filter-count">0</span>
                    <button type="button" id="mix-filter-plus" class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-300 hover:bg-gray-100">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- KROK 3 - Frekvence -->
          <div class="mb-10">
            <div class="flex items-center gap-3 mb-6">
              <div class="flex-shrink-0 w-8 h-8 bg-gray-900 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-bold">3</span>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Frekvence dodání</h2>
                <p class="text-sm text-gray-600 font-light">Jak často chcete kávu dostávat?</p>
              </div>
            </div>

            <div class="space-y-3">
              <!-- Každý měsíc -->
              <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 has-[:checked]:border-primary-500 cursor-pointer transition-all bg-white">
                <input type="radio" name="frequency" value="1" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="text-xl font-bold text-gray-900">Každý měsíc</div>
                    <p class="text-xs text-gray-500 font-light">Pro pravidelnou spotřebu</p>
                  </div>
                </div>
                <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-primary-500 group-has-[:checked]:bg-primary-500 flex items-center justify-center">
                  <svg class="w-3 h-3 text-white opacity-0 group-has-[:checked]:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </label>

              <!-- Jednou za 2 měsíce -->
              <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 has-[:checked]:border-primary-500 cursor-pointer transition-all bg-white">
                <input type="radio" name="frequency" value="2" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="text-xl font-bold text-gray-900">Jednou za 2 měsíce</div>
                    <p class="text-xs text-gray-500 font-light">Pro střední spotřebu</p>
                  </div>
                </div>
                <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-primary-500 group-has-[:checked]:bg-primary-500 flex items-center justify-center">
                  <svg class="w-3 h-3 text-white opacity-0 group-has-[:checked]:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </label>

              <!-- Jednou za 3 měsíce -->
              <label class="group flex items-center justify-between p-4 rounded-xl border border-gray-200 hover:border-gray-300 has-[:checked]:border-primary-500 cursor-pointer transition-all bg-white">
                <input type="radio" name="frequency" value="3" class="hidden" required>
                <div class="flex items-center gap-4 flex-1">
                  <div class="flex-shrink-0">
                    <div class="text-xl font-bold text-gray-900">Jednou za 3 měsíce</div>
                    <p class="text-xs text-gray-500 font-light">Pro občasné pití</p>
                  </div>
                </div>
                <div class="w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-primary-500 group-has-[:checked]:bg-primary-500 flex items-center justify-center">
                  <svg class="w-3 h-3 text-white opacity-0 group-has-[:checked]:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </label>
            </div>
          </div>

          <!-- Shrnutí a Submit -->
          <div class="p-6 bg-gray-100 rounded-2xl border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Shrnutí předplatného</h3>
            
            <div class="space-y-2 mb-6">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Množství:</span>
                <span class="font-medium text-gray-900" id="summary-amount">-</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Typ kávy:</span>
                <span class="font-medium text-gray-900" id="summary-type">-</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Frekvence:</span>
                <span class="font-medium text-gray-900" id="summary-frequency">-</span>
              </div>
              <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <span class="text-lg font-bold text-gray-900">Celková cena:</span>
                <div class="text-right">
                  <span class="text-3xl font-bold text-gray-900" id="summary-price">-</span>
                  <span class="text-sm text-gray-500 ml-1">Kč</span>
                  <p class="text-xs text-gray-500 mt-1">při každé dodávce</p>
                </div>
              </div>
            </div>

            <button type="submit" id="submit-button" 
                    class="w-full py-3 bg-primary-500 text-white font-medium rounded-full hover:bg-primary-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2" 
                    disabled>
              <span>Pokračovat k objednávce</span>
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </button>

            <div class="flex items-center justify-center gap-4 mt-4 text-xs text-gray-600">
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Bez závazků
              </span>
              <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Kdykoli zrušte
              </span>
            </div>
          </div>
        </form>

        <!-- Shipping Date Info -->
        <div class="mt-8 p-6 bg-gray-100 rounded-xl border border-gray-200">
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
              <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
            </div>
            <div>
              <h3 class="font-bold text-lg text-gray-900 mb-2">Termín následující rozesílky</h3>
              <p class="text-sm text-gray-900 font-medium mb-2">{{ $shippingInfo['cutoff_message'] }}</p>
              <p class="text-sm text-gray-600 font-light">
                Rozesílka kávy probíhá vždy <strong>20. dne v měsíci</strong>. 
                Objednávky uzavíráme <strong>15. dne v měsíci o půlnoci</strong>.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pricing = @json($subscriptionPricing);
    
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
    
    function updateSummary() {
        // Množství
        if (selectedAmount) {
            const grams = selectedAmount * 250;
            document.getElementById('summary-amount').textContent = grams + 'g (' + selectedAmount + ' balení)';
        } else {
            document.getElementById('summary-amount').textContent = '-';
        }
        
        // Typ
        let typeText = '-';
        if (selectedType) {
            const types = {
                'espresso': 'Espresso',
                'filter': 'Filtr',
                'mix': 'Kombinace'
            };
            typeText = types[selectedType];
            
            if (selectedType === 'mix' && (mixEspressoCount > 0 || mixFilterCount > 0)) {
                typeText += ` (${mixEspressoCount}x Espresso, ${mixFilterCount}x Filtr)`;
            }
            
            if (isDecaf) {
                typeText += ' + 1x decaf';
            }
        }
        document.getElementById('summary-type').textContent = typeText;
        
        // Frekvence
        const frequencies = {
            1: 'Každý měsíc',
            2: 'Jednou za 2 měsíce',
            3: 'Jednou za 3 měsíce'
        };
        document.getElementById('summary-frequency').textContent = frequencies[selectedFrequency] || '-';
        
        // Cena
        if (selectedAmount) {
            let price = pricing[selectedAmount] || 0;
            if (isDecaf) {
                price += 100;
            }
            document.getElementById('summary-price').textContent = price.toLocaleString('cs-CZ');
        } else {
            document.getElementById('summary-price').textContent = '-';
        }
        
        // Povolit tlačítko submit
        const submitButton = document.getElementById('submit-button');
        if (selectedAmount && selectedType && selectedFrequency) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    }
});
</script>
@endsection
