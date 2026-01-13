@extends('layouts.app')

@section('title', ($currentLocale === 'en' ? 'Shop' : 'Obchod') . ' - ' . ($currentLocale === 'en' ? 'KAVI' : 'KAVI.cz'))

@section('meta_description')
{{ $currentLocale === 'en' ? 'Shop specialty coffee from the best European roasteries. Espresso, filter, decaf and accessories. Fresh, quality, exceptional. Free shipping available.' : 'Nakupujte výběrovou kávu z nejlepších evropských pražíren. Espresso, filtr, bezkofeinová káva a příslušenství. Čerstvá, kvalitní, výjimečná. Doprava zdarma.' }}
@endsection

@section('og_title')
{{ $currentLocale === 'en' ? 'Coffee Shop | KAVI' : 'Kávový obchod | KAVI.cz' }}
@endsection

@section('og_description')
{{ $currentLocale === 'en' ? 'Carefully selected specialty coffee from the best European roasteries. Fresh, quality, exceptional.' : 'Pečlivě vybraná výběrová káva z nejlepších evropských pražíren. Čerstvá, kvalitní, výjimečná.' }}
@endsection

@section('content')
<!-- Hero Header Section - Minimal -->
<div class="relative bg-gray-100 py-12 sm:py-16 md:py-20 overflow-hidden">
  <!-- Subtle Organic Shapes -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary-100 rounded-full"></div>
    <div class="absolute -bottom-32 -left-32 w-[36rem] h-[36rem] bg-primary-50 rounded-full hidden md:block"></div>
  </div>
  
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="text-center max-w-3xl mx-auto">
      <!-- Minimal Badge -->
      <div class="inline-flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 mb-6">
        <svg class="w-4 h-4 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <span class="text-sm font-medium text-gray-900">{{ $currentLocale === 'en' ? 'Specialty coffee & accessories' : 'Výběrová káva a doplňky' }}</span>
      </div>

      <!-- Clean Heading -->
      <h1 class="mb-6 text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
        {{ $currentLocale === 'en' ? 'Discover our coffee collection' : 'Objevte naši kávovou kolekci' }}
      </h1>
      
      <p class="mx-auto max-w-2xl text-base sm:text-lg text-gray-600 font-light">
        {{ $currentLocale === 'en' ? 'Carefully selected coffee from the best European roasteries. Fresh, quality, exceptional.' : 'Pečlivě vybraná káva z nejlepších evropských pražíren. Čerstvá, kvalitní, výjimečná.' }}
      </p>
    </div>
  </div>
  
  <!-- Wave Divider -->
  <div class="absolute bottom-[-1px] left-0 right-0">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
      <path d="M0 80L60 73C120 67 240 53 360 48C480 43 600 47 720 53C840 59 960 67 1080 69C1200 71 1320 67 1380 65L1440 63V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#ffffff"/>
    </svg>
  </div>
</div>

<!-- Main Content -->
<div class="bg-white py-10 sm:py-12 md:py-16 lg:py-20">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">

    <!-- Filters - Minimal -->
    @php
      $categoryLabelsLocalized = $currentLocale === 'en' ? [
        'espresso' => 'Espresso',
        'filter' => 'Filter',
        'decaf' => 'Decaf',
        'accessories' => 'Accessories',
      ] : [
        'espresso' => 'Espresso',
        'filter' => 'Filtr',
        'decaf' => 'Bezkofeinová',
        'accessories' => 'Příslušenství',
      ];
    @endphp
    <div class="mb-8 sm:mb-10">
      <div class="flex flex-wrap justify-center gap-2">
        <a href="{{ localizedRoute('products.index') }}" 
           class="inline-flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-medium transition-all duration-200 {{ !request('category') ? 'bg-primary-500 text-white hover:bg-primary-600' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
          @if(!request('category'))
          <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          @endif
          <span>{{ $currentLocale === 'en' ? 'All' : 'Vše' }}</span>
        </a>
        @foreach($categories as $key => $label)
        <a href="{{ localizedRoute('products.index', ['category' => $key]) }}" 
           class="inline-flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-medium transition-all duration-200 {{ request('category') == $key ? 'bg-primary-500 text-white hover:bg-primary-600' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
          @if(request('category') == $key)
          <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          @endif
          <span>{{ $categoryLabelsLocalized[$key] ?? $label }}</span>
        </a>
        @endforeach
      </div>
    </div>
    <!-- Filters - end -->

    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @forelse($products as $product)
      <!-- product - start -->
      <div class="group relative bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-gray-300 transition-all duration-200">
        <!-- Image Container -->
        <a href="{{ localizedRoute('products.show', $product) }}" class="relative block h-64 overflow-hidden bg-gray-50">
          @if($product->image)
          <img src="{{ asset($product->image) }}" loading="lazy" alt="{{ $product->getName() }}" class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105" />
          @else
          <div class="h-full w-full flex flex-col items-center justify-center p-8 bg-gray-100">
            <svg class="w-16 h-16 text-gray-300 mb-3" fill="currentColor" viewBox="0 0 24 24">
              <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
            </svg>
            <p class="text-center text-xs font-light text-gray-500">{{ $product->getName() }}</p>
          </div>
          @endif

          <!-- Category Tags - Minimal -->
          <div class="absolute left-3 top-3 flex flex-wrap gap-1.5">
            @php
              $categoryBadges = $currentLocale === 'en' ? [
                'espresso' => ['label' => 'Espresso', 'color' => 'bg-amber-500'],
                'filter' => ['label' => 'Filter', 'color' => 'bg-blue-500'],
                'decaf' => ['label' => 'Decaf', 'color' => 'bg-green-500'],
                'accessories' => ['label' => 'Accessories', 'color' => 'bg-purple-500'],
              ] : [
                'espresso' => ['label' => 'Espresso', 'color' => 'bg-amber-500'],
                'filter' => ['label' => 'Filtr', 'color' => 'bg-blue-500'],
                'decaf' => ['label' => 'Bezkofeinová', 'color' => 'bg-green-500'],
                'accessories' => ['label' => 'Příslušenství', 'color' => 'bg-purple-500'],
              ];
              
              if (is_array($product->category) && !empty($product->category)) {
                foreach ($product->category as $cat) {
                  if (isset($categoryBadges[$cat])) {
                    $catData = $categoryBadges[$cat];
                    echo '<span class="px-2.5 py-1 rounded-full text-xs font-medium ' . $catData['color'] . ' text-white">' . $catData['label'] . '</span>';
                  }
                }
              }
            @endphp
          </div>

          <!-- Discount Badge - Minimal -->
          @if($product->shouldShowDiscountPercentage())
          <div class="absolute right-3 top-3 bg-red-500 rounded-full px-2.5 py-1">
            <span class="text-xs font-medium text-white">-{{ $product->getDiscountPercentage() }}%</span>
          </div>
          @endif
        </a>

        <!-- Product Info - Minimal -->
        <div class="p-4">
          <div class="mb-2.5">
            <a href="{{ localizedRoute('products.show', $product) }}" class="block">
              <h3 class="text-base font-semibold text-gray-900 group-hover:text-gray-600 transition-colors mb-2 line-clamp-2">{{ $product->getName() }}</h3>
            </a>
            
            <!-- Roaster / Manufacturer -->
            @if($product->roastery)
            <p class="text-sm text-gray-500 font-light mb-2 flex items-center gap-1">
              <span class="text-base">{{ $product->roastery->country_flag }}</span>
              <a href="{{ localizedRoute('roasteries.show', $product->roastery) }}" class="hover:text-gray-900 transition-colors">
                {{ $product->roastery->getName() }}
              </a>
            </p>
            @elseif(!empty($product->attributes['roaster']))
            <p class="text-sm text-gray-500 font-light mb-2 flex items-center gap-1">
              <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              {{ $product->attributes['roaster'] }}
            </p>
            @elseif(!empty($product->attributes['manufacturer']))
            <p class="text-sm text-gray-500 font-light mb-2 flex items-center gap-1">
              <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
              </svg>
              {{ $product->attributes['manufacturer'] }}
            </p>
            @endif
            
            <!-- Flavor Tones / Short Description -->
            @if(is_array($product->category) && in_array('accessories', $product->category))
              <!-- For accessories, show short description -->
              @if($product->getShortDescription())
              <div class="text-xs">              
                <span class="text-gray-600 font-light">{{ $product->getShortDescription() }}</span>
              </div>
              @endif
            @else
              <!-- For coffee products, show flavor tones -->
              @php
                $flavorNotes = $product->getTranslatedAttribute('flavor_notes') ?? $product->getTranslatedAttribute('flavor_profile');
              @endphp
              @if($flavorNotes)
              <div class="text-xs">              
                <span class="text-gray-600 font-light">{{ $flavorNotes }}</span>
              </div>
              @endif
            @endif
          </div>

          <!-- Price - Minimal -->
          <div class="pt-2.5 border-t border-gray-100">
            <div class="flex items-center justify-between mb-3">
              <div>
                @if($product->isOnSale())
                <span class="text-lg font-bold text-red-600">{{ $product->getFormattedPrice() }}</span>
                <div class="text-xs text-gray-500 line-through font-light">{{ $product->getFormattedOriginalPrice() }}</div>
                @else
                <span class="text-lg font-bold text-gray-900">{{ $product->getFormattedPrice() }}</span>
                @endif
              </div>
            </div>
            
            <!-- View Detail Button -->
            <a href="{{ localizedRoute('products.show', $product) }}" class="w-full py-2 bg-gray-900 text-white font-medium rounded-full hover:bg-gray-800 transition-all duration-200 text-sm flex items-center justify-center">
              {{ $currentLocale === 'en' ? 'View details' : 'Zobrazit detail' }}
            </a>
          </div>
        </div>
      </div>
      <!-- product - end -->
      @empty
      <div class="col-span-full text-center py-16">
        <div class="max-w-md mx-auto">
          <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
          </svg>
          <p class="text-gray-600 text-lg font-bold">{{ $currentLocale === 'en' ? 'No products found.' : 'Žádné produkty nebyly nalezeny.' }}</p>
        </div>
      </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-12 flex flex-col items-center gap-4">
      <!-- <p class="text-sm text-gray-500">
        {{ $currentLocale === 'en' ? 'Showing' : 'Zobrazeno' }} {{ $products->firstItem() }} - {{ $products->lastItem() }} {{ $currentLocale === 'en' ? 'of' : 'z' }} {{ $products->total() }} {{ $currentLocale === 'en' ? 'products' : 'produktů' }}
      </p> -->
      <nav class="flex items-center gap-1">
        {{-- Previous Page Link --}}
        @if($products->onFirstPage())
          <span class="px-4 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-full cursor-not-allowed">
            &laquo; {{ $currentLocale === 'en' ? 'Previous' : 'Předchozí' }}
          </span>
        @else
          <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
            &laquo; {{ $currentLocale === 'en' ? 'Previous' : 'Předchozí' }}
          </a>
        @endif

        {{-- Page Numbers --}}
        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
          @if($page == $products->currentPage())
            <span class="px-4 py-2 text-sm font-medium text-white bg-primary-500 border border-primary-500 rounded-full">
              {{ $page }}
            </span>
          @else
            <a href="{{ $url }}" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
              {{ $page }}
            </a>
          @endif
        @endforeach

        {{-- Next Page Link --}}
        @if($products->hasMorePages())
          <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
            {{ $currentLocale === 'en' ? 'Next' : 'Další' }} &raquo;
          </a>
        @else
          <span class="px-4 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-full cursor-not-allowed">
            {{ $currentLocale === 'en' ? 'Next' : 'Další' }} &raquo;
          </span>
        @endif
      </nav>
    </div>
    @endif
  </div>
</div>

<!-- CTA Section - Minimal -->
<div class="relative bg-gray-100 py-16 sm:py-20 overflow-hidden">
  <!-- Organic shape -->
  <div class="absolute top-0 right-0 w-96 h-96 bg-primary-100 rounded-full translate-x-1/2 -translate-y-1/2"></div>

  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-2xl flex-col items-center text-center">
      <h2 class="mb-4 text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 tracking-tight">
        {{ $currentLocale === 'en' ? 'Want regular coffee delivery?' : 'Chcete pravidelnou dodávku kávy?' }}
      </h2>
      
      <p class="text-base sm:text-lg text-gray-600 mb-8 max-w-xl font-light">
        {{ $currentLocale === 'en' ? 'With our subscription, you save time and money. Fresh coffee delivered to your door, cancel anytime.' : 'S naším předplatným ušetříte čas i peníze. Čerstvá káva přímo k vám domů, kdykoliv zrušitelné.' }}
      </p>

      <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-3 rounded-full transition-all duration-200 mb-8">
        <span>{{ $currentLocale === 'en' ? 'Learn more about subscription' : 'Zjistit více o předplatném' }}</span>
        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </a>

      <!-- Trust Indicators - Minimal -->
      <div class="flex flex-wrap items-center justify-center gap-4">
        <div class="flex items-center gap-1.5 text-gray-600">
          <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span class="text-sm font-light">{{ $currentLocale === 'en' ? 'Freshly roasted' : 'Doprava zdarma' }}</span>
        </div>
        <div class="flex items-center gap-1.5 text-gray-600">
          <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span class="text-sm font-light">{{ $currentLocale === 'en' ? 'No commitment' : 'Bez závazků' }}</span>
        </div>
        <div class="flex items-center gap-1.5 text-gray-600">
          <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span class="text-sm font-light">{{ $currentLocale === 'en' ? 'Premium quality' : 'Prémiová kvalita' }}</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
