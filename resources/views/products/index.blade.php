@extends('layouts.app')

@section('title', 'Obchod - Kavi Coffee')

@section('content')
<!-- Hero Header Section -->
<div class="relative bg-gradient-to-br from-gray-50 via-slate-50 to-gray-100 py-16 md:py-20 overflow-hidden">
  <!-- Background Decoration -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-br from-primary-300/10 to-pink-400/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-[36rem] h-[36rem] bg-gradient-to-tr from-primary-300/10 to-pink-400/10 rounded-full blur-3xl"></div>
  </div>
  
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="text-center max-w-3xl mx-auto">
      <!-- Badge -->
      <div class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-100 to-pink-100 rounded-full px-4 py-2 mb-6">
        <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <span class="text-sm font-bold text-primary-700">Prémiový obchod</span>
      </div>

      <!-- Main Heading -->
      <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-black text-gray-900">
        Objevte naši <span class="bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">kávovou kolekci</span>
      </h1>
      
      <p class="mx-auto max-w-2xl text-lg md:text-xl text-gray-600 mb-8">
        Pečlivě vybraná káva z nejlepších evropských pražíren. <span class="font-semibold text-gray-900">Čerstvá, kvalitní, výjimečná.</span>
      </p>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="bg-white py-12 sm:py-16 lg:py-20">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">

    <!-- Filters - start -->
    <div class="mb-12">
      <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ route('products.index') }}" 
           class="group relative inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-bold transition-all duration-200 {{ !request('category') ? 'bg-gradient-to-r from-primary-500 to-pink-600 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5' : 'bg-white border-2 border-gray-200 text-gray-700 hover:border-primary-300 shadow-md hover:shadow-lg' }}">
          @if(!request('category'))
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          @endif
          <span>Vše</span>
        </a>
        @foreach($categories as $key => $label)
        <a href="{{ route('products.index', ['category' => $key]) }}" 
           class="group relative inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-bold transition-all duration-200 {{ request('category') == $key ? 'bg-gradient-to-r from-primary-500 to-pink-600 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5' : 'bg-white border-2 border-gray-200 text-gray-700 hover:border-primary-300 shadow-md hover:shadow-lg' }}">
          @if(request('category') == $key)
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          @endif
          <span>{{ $label }}</span>
        </a>
        @endforeach
      </div>
    </div>
    <!-- Filters - end -->

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @forelse($products as $product)
      <!-- product - start -->
      <div class="group relative bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-gray-200 hover:border-primary-300">
        <!-- Image Container -->
        <a href="{{ route('products.show', $product) }}" class="relative block h-80 overflow-hidden">
          @if($product->image)
          <img src="{{ asset($product->image) }}" loading="lazy" alt="{{ $product->name }}" class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-110" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          @else
          <div class="h-full w-full flex flex-col items-center justify-center p-8 bg-gradient-to-br from-primary-100 to-pink-100">
            <svg class="w-20 h-20 text-primary-400 mb-3" fill="currentColor" viewBox="0 0 24 24">
              <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
            </svg>
            <p class="text-center text-sm font-bold text-primary-600">{{ $product->name }}</p>
          </div>
          @endif

          <!-- Category/Type Tags - Top Left -->
          <div class="absolute left-3 top-3 flex flex-wrap gap-2">
            @php
              $categoryLabels = [
                'espresso' => ['label' => 'Espresso', 'color' => 'bg-amber-500'],
                'filter' => ['label' => 'Filtr', 'color' => 'bg-blue-500'],
                'accessories' => ['label' => 'Příslušenství', 'color' => 'bg-purple-500'],
                'merch' => ['label' => 'Merch', 'color' => 'bg-green-500'],
              ];
              
              // Zobrazíme všechny kategorie produktu
              if (is_array($product->category) && !empty($product->category)) {
                foreach ($product->category as $cat) {
                  if (isset($categoryLabels[$cat])) {
                    $catData = $categoryLabels[$cat];
                    echo '<span class="px-3 py-1 rounded-lg text-xs font-bold ' . $catData['color'] . ' text-white shadow-lg">' . $catData['label'] . '</span>';
                  }
                }
              }
            @endphp
          </div>

          <!-- Discount Badge - Top Right -->
          @if($product->discount_percentage ?? false)
          <div class="absolute right-3 top-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg px-3 py-1.5 shadow-lg">
            <span class="text-xs font-bold uppercase tracking-wider text-white">-{{ $product->discount_percentage }}%</span>
          </div>
          @endif

          <!-- Quick View Button -->
          <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
            <span class="flex items-center justify-center gap-2 w-full bg-white/95 backdrop-blur-sm text-gray-900 font-bold py-3 rounded-xl shadow-lg hover:bg-white transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <span>Zobrazit detail</span>
            </span>
          </div>
        </a>

        <!-- Product Info -->
        <div class="p-5">
          <div class="mb-3">
            <a href="{{ route('products.show', $product) }}" class="block">
              <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-2 line-clamp-2">{{ $product->name }}</h3>
            </a>
            
            <!-- Roaster / Manufacturer -->
            @if($product->roastery)
            <p class="text-sm text-gray-500 font-medium mb-2 flex items-center gap-1">
              <span class="text-lg">{{ $product->roastery->country_flag }}</span>
              <a href="{{ route('roasteries.show', $product->roastery) }}" class="hover:text-primary-600 transition-colors">
                {{ $product->roastery->name }}
              </a>
            </p>
            @elseif(!empty($product->attributes['roaster']))
            <p class="text-sm text-gray-500 font-medium mb-2 flex items-center gap-1">
              <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              {{ $product->attributes['roaster'] }}
            </p>
            @elseif(!empty($product->attributes['manufacturer']))
            <p class="text-sm text-gray-500 font-medium mb-2 flex items-center gap-1">
              <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
              </svg>
              {{ $product->attributes['manufacturer'] }}
            </p>
            @endif
            
            <!-- Flavor Profile (for coffee) or Short Description (for accessories) -->
            @if(!empty($product->attributes['flavor_profile']))
            <p class="text-xs text-gray-600 line-clamp-2 italic">
              {{ $product->attributes['flavor_profile'] }}
            </p>
            @elseif($product->short_description)
            <p class="text-xs text-gray-600 line-clamp-2">
              {{ $product->short_description }}
            </p>
            @endif
          </div>

          <!-- Price -->
          <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <div>
              <span class="text-2xl font-black bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">{{ number_format($product->price, 0, ',', ' ') }}</span>
              <span class="text-sm text-gray-600 font-semibold ml-1">Kč</span>
              @if($product->original_price ?? false)
              <div class="text-sm text-red-500 line-through font-medium">{{ number_format($product->original_price, 0, ',', ' ') }} Kč</div>
              @endif
            </div>
            
            <!-- Add to Cart Button -->
            <button class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-200">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </button>
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
          <p class="text-gray-600 text-lg font-bold">Žádné produkty nebyly nalezeny.</p>
        </div>
      </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-12 flex justify-center">
      {{ $products->links() }}
    </div>
    @endif
  </div>
</div>

<!-- CTA Section -->
<div class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-16 sm:py-20 lg:py-24 overflow-hidden border-t-4 border-primary-500">
  <!-- Background Elements -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-gradient-to-br from-primary-500/20 to-pink-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-gradient-to-tr from-pink-500/10 to-primary-500/10 rounded-full blur-3xl"></div>
  </div>

  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-3xl flex-col items-center text-center">
      <!-- Badge -->
      <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-4 py-2 shadow-lg mb-6">
        <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="text-sm font-bold text-white">Ušetřete až 20%</span>
      </div>

      <h2 class="mb-6 text-3xl sm:text-4xl lg:text-5xl font-black text-white">
        Chcete pravidelnou dodávku kávy?
      </h2>
      
      <p class="text-lg text-gray-300 mb-8 max-w-2xl">
        S naším předplatným ušetříte čas i peníze. Čerstvá káva přímo k vám domů, <span class="text-white font-semibold">kdykoliv zrušitelné</span>.
      </p>

      <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('subscriptions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-10 py-5 rounded-xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 transition-all duration-200">
          <span class="text-lg">Zjistit více o předplatném</span>
          <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </a>
      </div>

      <!-- Trust Indicators -->
      <div class="flex flex-wrap items-center justify-center gap-6 mt-10 pt-10 border-t border-white/10">
        <div class="flex items-center gap-2 text-white/80">
          <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span class="text-sm font-medium">Doprava zdarma</span>
        </div>
        <div class="flex items-center gap-2 text-white/80">
          <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span class="text-sm font-medium">Bez závazků</span>
        </div>
        <div class="flex items-center gap-2 text-white/80">
          <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span class="text-sm font-medium">Prémiová kvalita</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
