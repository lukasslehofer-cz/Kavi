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
<!-- Hero Header Section - Editorial Layout -->
<div class="relative" style="background-color: #e5e6df;">
  
  
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    
    <!-- Main Heading - Large Editorial Typography, Left aligned -->
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
      <span class="text-dark-800">{{ $currentLocale === 'en' ? 'Coffee' : 'Objevte naši' }}</span><br>
      <span class="text-primary-500">{{ $currentLocale === 'en' ? 'shop' : 'kávovou kolekci' }}</span>
      </h1>
      
    <!-- Description - Right aligned -->
    <div class="flex justify-end">
      <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-500 max-w-md text-right leading-relaxed">
        {{ $currentLocale === 'en' ? 'Carefully selected specialty coffee from the best European roasteries. Fresh, quality, exceptional.' : 'Pečlivě vybraná výběrová káva z nejlepších evropských pražíren. Čerstvá, kvalitní, výjimečná.' }}
      </p>
  </div>
  
  </div>
</div>

<!-- Main Content -->
<div class="py-10 sm:py-12 md:py-16 lg:py-20" style="background-color: #e5e6df;">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">

    <!-- Filters - Minimalist Tab Bar -->
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
    <div class="mb-10 sm:mb-12">
      <div class="border-t border-b border-dark-800 py-4">
        <div class="flex flex-wrap items-center gap-x-8 gap-y-2">
        <a href="{{ localizedRoute('products.index') }}" 
             class="text-sm uppercase tracking-widest transition-colors {{ !request('category') ? 'text-primary-500 border-b border-primary-500 pb-0.5' : 'text-warm-500 hover:text-dark-800' }}">
            {{ $currentLocale === 'en' ? 'All' : 'Vše' }}<sup class="ml-0.5 text-[10px]">{{ str_pad($totalCount, 2, '0', STR_PAD_LEFT) }}</sup>
        </a>
        @foreach($categories as $key => $categoryData)
        <a href="{{ localizedRoute('products.index', ['category' => $key]) }}" 
             class="text-sm uppercase tracking-widest transition-colors {{ request('category') == $key ? 'text-primary-500 border-b border-primary-500 pb-0.5' : 'text-warm-500 hover:text-dark-800' }}">
            {{ $categoryLabelsLocalized[$key] ?? $categoryData['name'] }}<sup class="ml-0.5 text-[10px]">{{ str_pad($categoryData['count'], 2, '0', STR_PAD_LEFT) }}</sup>
        </a>
        @endforeach
        </div>
      </div>
    </div>
    <!-- Filters - end -->

    <div class="grid gap-x-4 sm:gap-x-8 lg:gap-x-10 gap-y-8 sm:gap-y-10 lg:gap-y-12 grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @forelse($products as $product)
      <!-- product - start -->
      <a href="{{ localizedRoute('products.show', $product) }}" class="group block">
        <!-- Image Container - No Frame -->
        <div class="relative aspect-square overflow-hidden mb-4">
          @if($product->image)
          <img src="{{ asset($product->image) }}" loading="lazy" alt="{{ $product->getName() }}" class="h-full w-full object-contain object-center transition duration-500 group-hover:scale-105" />
          @else
          <div class="h-full w-full flex flex-col items-center justify-center p-8">
            <span class="font-display text-4xl text-warm-300">{{ substr($product->getName(), 0, 1) }}</span>
          </div>
          @endif

          <!-- Category Tag - Museum Catalog Code -->
            @php
            $categoryTags = [
              'espresso' => 'ESP',
              'filter' => 'FLT',
              'decaf' => 'DCF',
              'accessories' => 'ACC',
            ];
          @endphp
          @if(is_array($product->category) && !empty($product->category))
          <div class="absolute top-0 left-0 flex flex-col gap-1">
            @foreach($product->category as $cat)
              @if(isset($categoryTags[$cat]))
                @php
                  $tagColors = [
                    'espresso' => 'border-amber-500',
                    'filter' => 'border-blue-500',
                    'decaf' => 'border-green-500',
                    'accessories' => 'border-purple-500',
                  ];
            @endphp
                <span class="text-[10px] uppercase tracking-widest text-dark-800 bg-[#e5e6df] px-2 py-1 border-b-2 {{ $tagColors[$cat] ?? 'border-dark-800' }}">{{ $categoryTags[$cat] }}</span>
              @endif
            @endforeach
          </div>
          @endif

          <!-- Discount Badge -->
          @if($product->shouldShowDiscountPercentage())
          <div class="absolute top-0 right-0">
            <span class="text-[10px] uppercase tracking-widest text-white bg-primary-500 px-2 py-1">-{{ $product->getDiscountPercentage() }}%</span>
          </div>
          @endif
        </div>

        <!-- Product Info - Minimal Typography -->
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1 min-w-0">
            <!-- Price - Technical Label Above Name -->
            <p class="text-xs uppercase tracking-widest text-warm-500 mb-1">
              @if($product->isOnSale())
              <span class="text-primary-500">{{ $product->getFormattedPrice() }}</span> —
              @else
              {{ $product->getFormattedPrice() }} —
              @endif
            </p>
            
            <!-- Product Name -->
            <h3 class="font-display text-base sm:text-lg font-normal text-dark-800 uppercase tracking-tight pt-[5px] pb-[8px] group-hover:text-primary-500 transition-colors line-clamp-3 sm:line-clamp-2" style="line-height: 1.25;">{{ $product->getName() }}</h3>
            
            <!-- Technical Info: Roastery · Flavor -->
            <p class="text-xs uppercase tracking-widest text-warm-500 leading-tight {{ is_array($product->category) && in_array('accessories', $product->category) && $product->getShortDescription() ? 'line-clamp-3' : '' }}">
            @if($product->roastery)
                {{ $product->roastery->getName() }}
              @php
                $flavorNotes = $product->getTranslatedAttribute('flavor_notes') ?? $product->getTranslatedAttribute('flavor_profile');
              @endphp
              @if($flavorNotes)
                  · {{ $flavorNotes }}
                @endif
              @elseif(!empty($product->attributes['roaster']))
                {{ $product->attributes['roaster'] }}
              @elseif(!empty($product->attributes['manufacturer']))
                {{ $product->attributes['manufacturer'] }}
              @elseif(is_array($product->category) && in_array('accessories', $product->category) && $product->getShortDescription())
                {{ $product->getShortDescription() }}
              @endif
            </p>
          </div>

          <!-- Arrow - Minimalist -->
          <div class="flex-shrink-0 mt-[26px]">
            <svg class="w-5 h-5 text-warm-400 group-hover:text-dark-800 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </div>
        </div>
      </a>
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

    <!-- Pagination - Technical Indexing -->
    @if($products->hasPages())
    <div class="mt-16 border-t border-dark-800 pt-6">
      <div class="flex items-center justify-end gap-6">
        <!-- Page Counter -->
        <span class="text-xs uppercase tracking-widest text-warm-500">
          {{ $currentLocale === 'en' ? 'Page' : 'Strana' }} {{ str_pad($products->currentPage(), 2, '0', STR_PAD_LEFT) }} / {{ str_pad($products->lastPage(), 2, '0', STR_PAD_LEFT) }}
          </span>
        
        <!-- Navigation -->
        <div class="flex items-center">
          @if($products->onFirstPage())
            <span class="text-xs uppercase tracking-widest text-warm-300 cursor-not-allowed">
              {{ $currentLocale === 'en' ? 'Previous' : 'Předchozí' }}
            </span>
          @else
            <a href="{{ $products->previousPageUrl() }}" class="text-xs uppercase tracking-widest text-warm-500 hover:text-dark-800 transition-colors">
              {{ $currentLocale === 'en' ? 'Previous' : 'Předchozí' }}
            </a>
          @endif
          
          <span class="mx-4 text-warm-300">—</span>

        @if($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}" class="text-xs uppercase tracking-widest text-warm-500 hover:text-dark-800 transition-colors">
              {{ $currentLocale === 'en' ? 'Next' : 'Další' }}
          </a>
        @else
            <span class="text-xs uppercase tracking-widest text-warm-300 cursor-not-allowed">
              {{ $currentLocale === 'en' ? 'Next' : 'Další' }}
          </span>
        @endif
        </div>
      </div>
    </div>
    @endif
  </div>
</div>

<!-- CTA Section - Editorial Style -->
<div class="relative pt-12 sm:pt-16 lg:pt-20 pb-20 sm:pb-24 lg:pb-28" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
      <!-- Large Editorial Heading -->
      <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase leading-tight sm:leading-[0.95]">
        {{ $currentLocale === 'en' ? 'Want regular coffee delivery?' : 'Chcete pravidelnou dodávku kávy?' }}
      </h2>
      
      <p class="mb-10 sm:mb-12 text-lg sm:text-xl text-warm-500 max-w-2xl leading-relaxed font-light">
        {{ $currentLocale === 'en' ? 'With our subscription, you save time and money. Fresh coffee delivered to your door, cancel anytime.' : 'S naším předplatným ušetříte čas i peníze. Čerstvá káva přímo k vám domů, kdykoliv zrušitelné.' }}
      </p>

      <!-- CTA Link -->
      <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
        <span>{{ $currentLocale === 'en' ? 'Learn more about subscription' : 'Zjistit více o předplatném' }}</span>
        <span class="text-primary-500 group-hover:translate-x-1 transition-transform">→</span>
      </a>
    </div>
  </div>
</div>

{{-- Tracking: ViewContent / view_item_list (dataLayer + Meta Pixel) --}}
<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'event': 'view_item_list',
        'ecommerce': {
            'currency': '{{ \App\Helpers\CurrencyHelper::code() }}',
            'items': [
                @foreach($products as $index => $product)
                {
                    'item_id': '{{ $product->id }}',
                    'item_name': '{{ addslashes($product->getName()) }}',
                    'price': {{ $product->getPrice() }},
                    'index': {{ $index }}
                    @if($product->roastery)
                    ,'item_brand': '{{ addslashes($product->roastery->getName()) }}'
                    @endif
                    @if(is_array($product->category) && !empty($product->category))
                    ,'item_category': '{{ $product->category[0] }}'
                    @endif
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
    });

    // Meta Pixel - ViewContent
    if (typeof fbq !== 'undefined') {
        fbq('track', 'ViewContent', {
            content_type: 'product_group'
        });
    }
</script>
@endsection
