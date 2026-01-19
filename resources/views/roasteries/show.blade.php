@extends('layouts.app')

@section('title', $roastery->getName() . ' - ' . __('roasteries.page_title') . ' - ' . (app()->getLocale() === 'en' ? 'KAVI' : 'KAVI.cz'))

@php
    $currentLocale = app()->getLocale();
    $roasteryDescription = Str::limit(strip_tags($roastery->getShortDescription() ?: $roastery->getFullDescription()), 160);
    $siteUrl = $currentLocale === 'en' ? 'https://kavibox.com' : 'https://kavi.cz';
    
    // Country codes mapping
    $countryCodes = [
        'Belgie' => 'BE', 'Belgium' => 'BE',
        'Německo' => 'DE', 'Germany' => 'DE',
        'Polsko' => 'PL', 'Poland' => 'PL',
        'Portugalsko' => 'PT', 'Portugal' => 'PT',
        'Rakousko' => 'AT', 'Austria' => 'AT',
        'Nizozemsko' => 'NL', 'Netherlands' => 'NL',
        'Francie' => 'FR', 'France' => 'FR',
        'Španělsko' => 'ES', 'Spain' => 'ES',
        'Chorvatsko' => 'HR', 'Croatia' => 'HR',
        'Rumunsko' => 'RO', 'Romania' => 'RO',
        'Lotyšsko' => 'LV', 'Latvia' => 'LV',
        'Velká Británie' => 'GB', 'United Kingdom' => 'GB',
    ];
    $countryCode = $countryCodes[$roastery->country] ?? strtoupper(substr($roastery->country, 0, 2));
@endphp

@section('meta_description', $roasteryDescription ?: ($currentLocale === 'en' ? 'Discover ' . $roastery->getName() . ' - premium coffee roaster from ' . $roastery->country . '. Explore their exceptional coffees at KAVI.' : 'Objevte ' . $roastery->getName() . ' - prémiovou pražírnu z ' . $roastery->country . '. Prozkoumejte jejich výjimečné kávy na KAVI.cz.'))

@section('og_title')
{{ $roastery->getName() }} {{ $roastery->country_flag }} | {{ $currentLocale === 'en' ? 'KAVI' : 'KAVI.cz' }}
@endsection

@section('og_description', $roasteryDescription ?: ($currentLocale === 'en' ? 'Premium coffee roaster from ' . $roastery->country . '. Discover their exceptional specialty coffees.' : 'Prémiová pražírna z ' . $roastery->country . '. Objevte jejich výjimečné výběrové kávy.'))

@section('og_image')
{{ $roastery->image ? url($roastery->image) : $siteUrl . '/images/og-image.jpg' }}
@endsection

@section('structured_data')
@php
    $roasteryImageUrl = $roastery->image ? url($roastery->image) : $siteUrl . '/images/og-image.jpg';
    $roasteryDescriptionFull = strip_tags($roastery->getShortDescription() ?: $roastery->getFullDescription());
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@graph": [
        {
            "@type": "Organization",
            "@id": "{{ url()->current() }}#organization",
            "name": "{{ $roastery->getName() }}",
            "description": "{{ Str::limit($roasteryDescriptionFull, 300) }}",
            "image": "{{ $roasteryImageUrl }}",
            "logo": "{{ $roasteryImageUrl }}"
            @if($roastery->website_url)
            ,"url": "{{ $roastery->website_url }}"
            @endif
            @if($roastery->instagram)
            ,"sameAs": ["https://instagram.com/{{ str_replace('@', '', $roastery->instagram) }}"]
            @endif
            @if($roastery->getCountry() || $roastery->getCity())
            ,"address": {
                "@type": "PostalAddress"
                @if($roastery->getCity())
                ,"addressLocality": "{{ $roastery->getCity() }}"
                @endif
                @if($roastery->getCountry())
                ,"addressCountry": "{{ $roastery->getCountry() }}"
                @endif
                @if($roastery->address)
                ,"streetAddress": "{{ $roastery->address }}"
                @endif
            }
            @endif
        },
        {
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "{{ $currentLocale === 'en' ? 'Home' : 'Domů' }}",
                    "item": "{{ $siteUrl }}"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "{{ __('roasteries.page_title') }}",
                    "item": "{{ localizedRoute('roasteries.index') }}"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "{{ $roastery->getName() }}",
                    "item": "{{ url()->current() }}"
                }
            ]
        }
    ]
}
</script>
@endsection

@section('content')
<div style="background-color: #e5e6df;">

  <!-- Microscopic Breadcrumbs -->
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-4">
    <nav>
      <ol class="flex items-center gap-1 text-warm-400" style="font-size: 10px; letter-spacing: 0.1em;">
        <li><a href="{{ route('home') }}" class="uppercase hover:text-dark-800 transition-colors">{{ __('messages.general.home') }}</a></li>
        <li>/</li>
        <li><a href="{{ localizedRoute('roasteries.index') }}" class="uppercase hover:text-dark-800 transition-colors">{{ __('roasteries.page_title') }}</a></li>
        <li>/</li>
        <li class="text-dark-800 uppercase">{{ $roastery->getName() }}</li>
      </ol>
    </nav>
  </div>

  <!-- Hero Section - Radical Split-Screen -->
  <div class="relative">
    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[70vh]">
      
      <!-- Left Side - Name & Technical Data (40%) -->
      <div class="lg:col-span-5 flex flex-col justify-end px-4 md:px-8 lg:pl-8 xl:pl-12 pb-12 lg:pb-20 pt-8 lg:pt-16">
        
        <!-- Country Code -->
        <div class="mb-4">
          <span class="text-xs uppercase tracking-widest"><span class="text-primary-500">[</span> <span class="text-warm-500">{{ $countryCode }}</span> <span class="text-primary-500">]</span></span>
        </div>
        
        <!-- Giant Roastery Name -->
        <h1 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal text-dark-800 uppercase tracking-tight leading-[0.9] mb-6">
          {{ $roastery->getName() }}
        </h1>
        
        <!-- Technical Data -->
        <div class="flex flex-wrap items-center gap-x-2 text-xs uppercase tracking-widest text-warm-500">
          <span>{{ $roastery->getCountry() }}</span>
          @if($roastery->getCity())
          <span>/</span>
          <span>{{ $roastery->getCity() }}</span>
          @endif
          <span>/</span>
          <span>EST. 2017</span>
        </div>
        
        <!-- Links -->
        <div class="flex items-center gap-6 mt-8">
          @if($roastery->website_url)
          <a href="{{ $roastery->website_url }}" target="_blank" rel="noopener noreferrer"
             class="text-xs uppercase tracking-widest text-dark-800 hover:text-primary-500 transition-colors">
            WWW
          </a>
          @endif
          @if($roastery->instagram)
          <a href="https://instagram.com/{{ str_replace('@', '', $roastery->instagram) }}" target="_blank" rel="noopener noreferrer"
             class="text-xs uppercase tracking-widest text-dark-800 hover:text-primary-500 transition-colors">
            IG
          </a>
          @endif
        </div>
      </div>
      
      <!-- Right Side - Edge-to-Edge Photo (60%) -->
      <div class="lg:col-span-7 relative lg:absolute lg:right-0 lg:top-[-64px] lg:bottom-0 lg:w-[58.333%]">
        @if($roastery->image)
        <div class="aspect-[4/3] lg:aspect-auto lg:h-[calc(100%+64px)] w-full overflow-hidden">
          <img src="{{ asset($roastery->image) }}" 
               alt="{{ $roastery->getName() }}" 
               class="w-full h-full object-cover">
        </div>
        @else
        <div class="aspect-[4/3] lg:aspect-auto lg:h-[calc(100%+64px)] w-full bg-warm-200 flex items-center justify-center">
          <span class="font-display text-4xl text-warm-400 uppercase tracking-tight">{{ $roastery->getName() }}</span>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- About Text - Magazine Style Two Columns -->
  @if($roastery->getShortDescription() || $roastery->getFullDescription())
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-24 lg:pt-32 pb-16 lg:pb-24">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">
      @if($roastery->getShortDescription())
      <div>
        <p class="text-lg lg:text-xl text-dark-700 font-light leading-relaxed">
          {{ $roastery->getShortDescription() }}
        </p>
      </div>
      @endif
      
      @if($roastery->getFullDescription())
      <div>
        @php
          $paragraphs = explode("\n", $roastery->getFullDescription());
        @endphp
        @foreach($paragraphs as $paragraph)
          @if(trim($paragraph))
          <p class="text-base text-dark-600 font-light leading-relaxed mb-4 last:mb-0">{{ $paragraph }}</p>
          @endif
        @endforeach
      </div>
      @endif
    </div>
  </div>
  @endif

  <!-- Contact Info - The Fact Sheet -->
  @if($roastery->address || $roastery->website_url || $roastery->instagram)
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pb-16 lg:pb-24">
    <div class="border-t border-b border-dark-800 py-8">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8">
        @if($roastery->address)
        <div>
          <span class="block text-xs uppercase tracking-widest text-warm-500 mb-2">
            {{ $currentLocale === 'en' ? 'Address' : 'Adresa' }}
          </span>
          <span class="text-sm uppercase tracking-widest text-dark-800">{{ $roastery->address }}</span>
        </div>
        @endif
        @if($roastery->website_url)
        <div>
          <span class="block text-xs uppercase tracking-widest text-warm-500 mb-2">
            WWW
          </span>
          <a href="{{ $roastery->website_url }}" target="_blank" rel="noopener noreferrer" class="text-sm uppercase tracking-widest text-dark-800 hover:text-primary-500 transition-colors">
            {{ str_replace(['https://', 'http://', 'www.'], '', rtrim($roastery->website_url, '/')) }}
          </a>
        </div>
        @endif
        @if($roastery->instagram)
        <div>
          <span class="block text-xs uppercase tracking-widest text-warm-500 mb-2">
            INSTAGRAM
          </span>
          <a href="https://instagram.com/{{ str_replace('@', '', $roastery->instagram) }}" target="_blank" rel="noopener noreferrer" class="text-sm uppercase tracking-widest text-dark-800 hover:text-primary-500 transition-colors">
            {{ $roastery->instagram }}
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>
  @endif

  <!-- Coffees Section - Linear Index -->
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pb-16 lg:pb-24">
    
    <!-- Section Heading -->
    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 uppercase tracking-tight mb-10">
      {{ $currentLocale === 'en' ? 'Coffees from' : 'Kávy od' }} <span class="text-primary-500">{{ $roastery->getName() }}</span>
    </h2>

    @php $sectionNumber = 1; @endphp

    <!-- Coffee of Month Products -->
    @if($coffeeOfMonthProducts->count() > 0)
    <div class="mb-12">
      <!-- Section Label -->
      <div class="mb-2">
        <span class="text-xs uppercase text-primary-500" style="letter-spacing: 0.2em;">{{ str_pad($sectionNumber, 2, '0', STR_PAD_LEFT) }}</span>
        <span class="text-xs uppercase text-dark-800 ml-2" style="letter-spacing: 0.2em;">/ {{ $currentLocale === 'en' ? 'CURRENT EDIT' : 'V AKTUÁLNÍM PŘEDPLATNÉM' }}</span>
      </div>
      @php $sectionNumber++; @endphp
      
      <!-- Linear Coffee Index -->
      <div class="border-t border-dark-800">
        @foreach($coffeeOfMonthProducts as $product)
        <div class="border-b border-warm-300 py-6 flex flex-col sm:flex-row sm:items-center gap-4 cursor-pointer group hover:bg-warm-300/30 transition-colors" onclick="openCoffeeModal{{ $product->id }}()">
          
          <!-- Coffee Image -->
          <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 overflow-hidden">
            @if($product->image)
            <img src="{{ asset($product->image) }}" alt="{{ $product->getName() }}" class="w-full h-full object-contain">
            @endif
          </div>
          
          <!-- Coffee Info -->
          <div class="flex-grow">
            <!-- Coffee Name -->
            <span class="font-display text-xl sm:text-2xl text-dark-800 uppercase tracking-tight group-hover:text-primary-500 transition-colors block mb-2">
              {{ $product->getName() }}
            </span>
            
            <!-- Flavor Profile -->
            @php
              $flavorNotes = $product->getTranslatedAttribute('flavor_notes');
            @endphp
            @if($flavorNotes)
            <p class="text-xs uppercase tracking-widest text-warm-500">{{ $flavorNotes }}</p>
            @endif
          </div>
          
          <!-- Category Tags - Right aligned -->
          <div class="flex flex-wrap gap-3 sm:flex-shrink-0">
            @if(is_array($product->category))
              @foreach($product->category as $cat)
                @if($cat === 'espresso')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 pb-1 border-b-2 border-amber-500">ESP</span>
                @elseif($cat === 'filter')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 pb-1 border-b-2 border-blue-500">FLT</span>
                @elseif($cat === 'decaf')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 pb-1 border-b-2 border-green-500">DCF</span>
                @endif
              @endforeach
            @endif
          </div>
          
          <!-- Arrow -->
          <div class="hidden sm:flex items-center flex-shrink-0">
            <svg class="w-6 h-6 text-warm-400 group-hover:text-dark-800 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </div>
        </div>

        <!-- Modal for Coffee Detail -->
        <div id="coffeeModal{{ $product->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closeCoffeeModal{{ $product->id }}(event)">
          <div class="bg-[#e5e6df] max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="relative">
              @if($product->image)
              <div class="aspect-[4/3] w-full overflow-hidden">
                <img src="{{ asset($product->image) }}" 
                     alt="{{ $product->getName() }}"
                     class="w-full h-full object-contain">
              </div>
              @endif
              
              <button onclick="closeCoffeeModal{{ $product->id }}()" 
                      class="absolute top-4 right-4 bg-[#e5e6df] p-2 hover:bg-warm-200 transition-colors">
                <svg class="w-6 h-6 text-dark-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div class="p-6 sm:p-8">
              <h3 class="font-display text-3xl sm:text-4xl font-normal text-dark-800 uppercase tracking-tight mb-4">
                {{ $product->getName() }}
              </h3>

              <a href="{{ localizedRoute('roasteries.show', $roastery) }}" class="text-sm uppercase tracking-widest text-warm-500 hover:text-dark-800 transition-colors">
                {{ $roastery->getName() }}
              </a>

              @if($product->getShortDescription())
              <p class="text-base text-dark-700 font-light leading-relaxed mt-6">
                {{ $product->getShortDescription() }}
              </p>
              @endif

              @if($product->getDescription())
              <p class="text-sm text-dark-600 font-light leading-relaxed mt-4">
                {{ $product->getDescription() }}
              </p>
              @endif

              <!-- Parameters -->
              @if($product->attributes && is_array($product->attributes))
              <div class="border-t border-b border-dark-800 py-6 sm:py-8 mt-8 grid grid-cols-2 gap-y-4">
                @php
                  $mainAttributes = $currentLocale === 'en' ? 
                    ['origin' => 'Origin', 'altitude' => 'Altitude', 'processing' => 'Processing', 'variety' => 'Variety', 'flavor_notes' => 'Flavor notes'] :
                    ['origin' => 'Původ', 'altitude' => 'Nadmořská výška', 'processing' => 'Zpracování', 'variety' => 'Odrůda', 'flavor_notes' => 'Chuťové tóny'];
                @endphp
                @foreach($mainAttributes as $key => $label)
                  @php $attrValue = $product->getTranslatedAttribute($key); @endphp
                  @if($attrValue)
                  <div>
                    <span class="block text-xs uppercase tracking-widest text-warm-500 mb-1">{{ $label }}</span>
                    <span class="text-sm text-dark-800 uppercase tracking-wide">{{ $attrValue }}</span>
                  </div>
                  @endif
                @endforeach
              </div>
              @endif

              <!-- CTA -->
              <div class="border-t border-primary-500 pt-6 sm:pt-8 mt-6">
                <p class="text-sm text-dark-600 mb-4 font-light">
                  {{ __('roasteries.modal_subscription_text') }}
                </p>
                <a href="{{ localizedRoute('subscriptions.index') }}" 
                   class="block w-full py-3 bg-dark-800 text-white font-display uppercase tracking-widest text-center hover:bg-dark-900 transition-colors">
                  {{ __('roasteries.choose_subscription') }}
                </a>
              </div>
            </div>
          </div>
        </div>

        <script>
          function openCoffeeModal{{ $product->id }}() {
            document.getElementById('coffeeModal{{ $product->id }}').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
          }

          function closeCoffeeModal{{ $product->id }}(event) {
            if (event) {
              event.stopPropagation();
            }
            document.getElementById('coffeeModal{{ $product->id }}').classList.add('hidden');
            document.body.style.overflow = 'auto';
          }
        </script>
        @endforeach
      </div>
    </div>
    @endif

    <!-- Other Active Coffees -->
    @if($activeProducts->count() > 0)
    <div class="mb-12">
      <!-- Section Label -->
      <div class="mb-2">
        <span class="text-xs uppercase text-primary-500" style="letter-spacing: 0.2em;">{{ str_pad($sectionNumber, 2, '0', STR_PAD_LEFT) }}</span>
        <span class="text-xs uppercase text-dark-800 ml-2" style="letter-spacing: 0.2em;">/ {{ $currentLocale === 'en' ? 'SHOP COLLECTION' : 'DALŠÍ KÁVY' }}</span>
      </div>
      @php $sectionNumber++; @endphp
      
      <!-- Linear Coffee Index -->
      <div class="border-t border-dark-800">
        @foreach($activeProducts as $product)
        <a href="{{ localizedRoute('products.show', $product) }}" class="border-b border-warm-300 py-6 flex flex-col sm:flex-row sm:items-center gap-4 group block hover:bg-warm-300/30 transition-colors">
          
          <!-- Coffee Image -->
          <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 overflow-hidden">
            @if($product->image)
            <img src="{{ asset($product->image) }}" alt="{{ $product->getName() }}" class="w-full h-full object-contain">
            @endif
          </div>
          
          <!-- Coffee Info -->
          <div class="flex-grow">
            <!-- Coffee Name -->
            <span class="font-display text-xl sm:text-2xl text-dark-800 uppercase tracking-tight group-hover:text-primary-500 transition-colors block mb-2">
              {{ $product->getName() }}
            </span>
            
            <!-- Flavor Profile -->
            @php
              $flavorNotes = $product->getTranslatedAttribute('flavor_notes');
            @endphp
            @if($flavorNotes)
            <p class="text-xs uppercase tracking-widest text-warm-500">{{ $flavorNotes }}</p>
            @endif
            
            <!-- Price -->
            <p class="text-xs uppercase tracking-widest text-dark-800 mt-2">{{ $product->getFormattedPrice() }} —</p>
          </div>
          
          <!-- Category Tags - Right aligned -->
          <div class="flex flex-wrap gap-3 sm:flex-shrink-0">
            @if(is_array($product->category))
              @foreach($product->category as $cat)
                @if($cat === 'espresso')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 pb-1 border-b-2 border-amber-500">ESP</span>
                @elseif($cat === 'filter')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 pb-1 border-b-2 border-blue-500">FLT</span>
                @elseif($cat === 'decaf')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 pb-1 border-b-2 border-green-500">DCF</span>
                @endif
              @endforeach
            @endif
          </div>
          
          <!-- Arrow -->
          <div class="hidden sm:flex items-center flex-shrink-0">
            <svg class="w-6 h-6 text-warm-400 group-hover:text-dark-800 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </div>
        </a>
        @endforeach
      </div>
    </div>
    @endif

    <!-- Historical Coffees (not clickable) -->
    @if($historicalProducts->count() > 0)
    <div>
      <!-- Section Label -->
      <div class="mb-2">
        <span class="text-xs uppercase text-primary-500" style="letter-spacing: 0.2em;">{{ str_pad($sectionNumber, 2, '0', STR_PAD_LEFT) }}</span>
        <span class="text-xs uppercase text-dark-800 ml-2" style="letter-spacing: 0.2em;">/ {{ $currentLocale === 'en' ? 'PAST CURATIONS' : 'MINULÉ KÁVY' }}</span>
      </div>
      @php $sectionNumber++; @endphp
      
      <!-- Linear Coffee Index - Non-clickable -->
      <div class="border-t border-dark-800">
        @foreach($historicalProducts as $product)
        <div class="border-b border-warm-300 py-6 flex flex-col sm:flex-row sm:items-center gap-4 opacity-60">
          
          <!-- Coffee Image -->
          <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 overflow-hidden grayscale">
            @if($product->image)
            <img src="{{ asset($product->image) }}" alt="{{ $product->getName() }}" class="w-full h-full object-contain">
            @endif
          </div>
          
          <!-- Coffee Info -->
          <div class="flex-grow">
            <!-- Coffee Name -->
            <span class="font-display text-xl sm:text-2xl text-dark-800 uppercase tracking-tight block mb-2">
              {{ $product->getName() }}
            </span>
            
            <!-- Flavor Profile -->
            @php
              $flavorNotes = $product->getTranslatedAttribute('flavor_notes');
            @endphp
            @if($flavorNotes)
            <p class="text-xs uppercase tracking-widest text-warm-500">{{ $flavorNotes }}</p>
            @endif
          </div>
          
          <!-- Category Tags - Right aligned -->
          <div class="flex flex-wrap gap-3 sm:flex-shrink-0">
            @if(is_array($product->category))
              @foreach($product->category as $cat)
                @if($cat === 'espresso')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 pb-1 border-b-2 border-amber-500">ESP</span>
                @elseif($cat === 'filter')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 pb-1 border-b-2 border-blue-500">FLT</span>
                @elseif($cat === 'decaf')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 pb-1 border-b-2 border-green-500">DCF</span>
                @endif
              @endforeach
            @endif
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <!-- Empty State -->
    @if($coffeeOfMonthProducts->count() == 0 && $activeProducts->count() == 0 && $historicalProducts->count() == 0)
    <div class="border-t border-b border-dark-800 py-16 text-center">
      <p class="text-sm uppercase tracking-widest text-warm-500">{{ __('roasteries.no_coffees_yet') }}</p>
    </div>
    @endif
  </div>

  <!-- Bottom CTA - Same style as /produkty -->
  <div class="relative pt-8 sm:pt-10 lg:pt-14 pb-20 sm:pb-24 lg:pb-28" style="background-color: #e5e6df;">
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
      <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
        <!-- Large Editorial Heading -->
        <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase">
          {{ $currentLocale === 'en' ? 'Want to taste coffees from ' . $roastery->getName() . '?' : 'Chcete ochutnat kávy od ' . $roastery->getName() . '?' }}
        </h2>

        <p class="mb-10 sm:mb-12 text-lg sm:text-xl text-warm-500 max-w-2xl leading-relaxed font-light">
          {{ $currentLocale === 'en' ? 'With our subscription, you can taste coffees from various European roasters every month. Fresh specialty coffee delivered right to your door.' : 'S naším předplatným můžete každý měsíc ochutnat kávy od různých evropských pražíren. Čerstvá výběrová káva přímo k vám domů.' }}
        </p>

        <!-- CTA Link -->
        <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
          <span>{{ $currentLocale === 'en' ? 'Learn more about subscription' : 'Zjistit více o předplatném' }}</span>
          <span class="text-primary-500 group-hover:translate-x-1 transition-transform">→</span>
        </a>
      </div>
    </div>
  </div>

</div>
@endsection
