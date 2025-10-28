@extends('layouts.app')

@section('title', $product->name . ' - Kavi Coffee')

@section('content')
<!-- Hero with Breadcrumb -->
<div class="relative bg-gradient-to-br from-gray-50 via-slate-50 to-gray-100 py-8 border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="text-sm">
        <ol class="flex items-center space-x-2 text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors font-medium">Domů</a></li>
            <li class="text-gray-400">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </li>
            <li><a href="{{ route('products.index') }}" class="hover:text-primary-600 transition-colors font-medium">Obchod</a></li>
            <li class="text-gray-400">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </li>
            <li class="text-gray-900 font-bold truncate max-w-xs">{{ $product->name }}</li>
        </ol>
    </nav>
  </div>
</div>

<div class="bg-white">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

    <!-- Product Detail -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 mb-20">
        <!-- Product Image -->
        <div class="lg:sticky lg:top-24 h-fit">
            <div class="relative aspect-square rounded-3xl overflow-hidden shadow-2xl border-2 border-gray-200 bg-gradient-to-br from-gray-100 to-gray-50">
                @if($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex flex-col items-center justify-center p-12 bg-gradient-to-br from-primary-100 to-pink-100">
                    <svg class="w-32 h-32 text-primary-400 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                    <p class="text-center text-primary-700 font-semibold">{{ $product->name }}</p>
                </div>
                @endif
                
                <!-- Category/Type Tags - Top Left -->
                <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                  @php
                    $categoryLabels = [
                      'espresso' => ['label' => 'Espresso', 'color' => 'bg-amber-500'],
                      'filter' => ['label' => 'Filtr', 'color' => 'bg-blue-500'],
                      'accessories' => ['label' => 'Příslušenství', 'color' => 'bg-purple-500'],
                      'merch' => ['label' => 'Merch', 'color' => 'bg-green-500'],
                    ];
                    
                    // Pro kávy zobrazíme preparation methods
                    if (!empty($product->attributes['preparation_methods'])) {
                      $methods = $product->attributes['preparation_methods'];
                      foreach ($methods as $method) {
                        if ($method === 'espresso') {
                          echo '<span class="px-4 py-2 rounded-lg text-sm font-bold bg-amber-500 text-white shadow-lg">Espresso</span>';
                        } elseif ($method === 'filter') {
                          echo '<span class="px-4 py-2 rounded-lg text-sm font-bold bg-blue-500 text-white shadow-lg">Filtr</span>';
                        }
                      }
                    } 
                    // Pro ostatní kategorie zobrazíme kategorii
                    elseif (isset($categoryLabels[$product->category])) {
                      $cat = $categoryLabels[$product->category];
                      echo '<span class="px-4 py-2 rounded-lg text-sm font-bold ' . $cat['color'] . ' text-white shadow-lg">' . $cat['label'] . '</span>';
                    }
                  @endphp
                </div>

                <!-- Featured Badge - Top Right -->
                @if($product->is_featured)
                <div class="absolute top-4 right-4 bg-gradient-to-r from-primary-500 to-pink-600 rounded-lg px-4 py-2 shadow-lg">
                  <span class="text-xs font-bold uppercase tracking-wider text-white flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Featured
                  </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <!-- Stock Status -->
            <div class="flex flex-wrap items-center gap-2 mb-6">
              @if($product->stock > 0)
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                Na skladě
              </span>
              @else
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                Vyprodáno
              </span>
              @endif
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-4 leading-tight">
                {{ $product->name }}
            </h1>
            
            <!-- Roaster / Manufacturer -->
            @if($product->roastery)
            <p class="text-lg text-gray-600 font-medium mb-6 flex items-center gap-2">
              <span class="text-2xl">{{ $product->roastery->country_flag }}</span>
              <a href="{{ route('roasteries.show', $product->roastery) }}" class="hover:text-primary-600 transition-colors font-semibold">
                {{ $product->roastery->name }}
              </a>
            </p>
            @elseif(!empty($product->attributes['roaster']))
            <p class="text-lg text-gray-600 font-medium mb-6 flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              {{ $product->attributes['roaster'] }}
            </p>
            @elseif(!empty($product->attributes['manufacturer']))
            <p class="text-lg text-gray-600 font-medium mb-6 flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
              </svg>
              {{ $product->attributes['manufacturer'] }}
            </p>
            @endif
            
            @if($product->short_description)
            <p class="text-xl text-gray-600 mb-8 leading-relaxed">{{ $product->short_description }}</p>
            @endif
            
            <!-- Flavor Profile Highlight -->
            @if(!empty($product->attributes['flavor_profile']))
            <div class="bg-gradient-to-r from-primary-50 to-pink-50 rounded-2xl p-6 mb-8 border-2 border-primary-100">
              <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-pink-600 flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Chuťový profil</h3>
                  <p class="text-lg font-semibold text-gray-900">{{ $product->attributes['flavor_profile'] }}</p>
                </div>
              </div>
            </div>
            @endif

            <div class="flex items-baseline gap-3 mb-8 pb-8 border-b-2 border-gray-200">
                <span class="text-6xl font-black bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">{{ number_format($product->price, 0, ',', ' ') }}</span>
                <span class="text-2xl text-gray-600 font-bold">Kč</span>
            </div>

            @if($product->isInStock())
            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-10">
                @csrf
                <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-2xl p-6 mb-6 border-2 border-gray-200">
                    <label class="block text-gray-900 font-bold mb-4 flex items-center gap-2">
                      <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                      </svg>
                      Množství:
                    </label>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center border-2 border-gray-300 rounded-xl overflow-hidden bg-white shadow-md">
                            <button type="button" class="px-6 py-4 text-gray-700 hover:bg-primary-500 hover:text-white transition-colors font-bold text-xl">-</button>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                   class="w-20 text-center text-xl font-bold border-0 focus:ring-0 bg-transparent text-gray-900" data-quantity-input>
                            <button type="button" class="px-6 py-4 text-gray-700 hover:bg-primary-500 hover:text-white transition-colors font-bold text-xl">+</button>
                        </div>
                        <span class="text-gray-600 font-medium">
                            <span class="font-bold text-gray-900">{{ $product->stock }}</span> ks skladem
                        </span>
                    </div>
                </div>
                
                <button type="submit" class="group w-full bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold text-lg px-8 py-5 rounded-xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 transition-all duration-200 mb-6 flex items-center justify-center gap-3">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Přidat do košíku</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex items-center gap-3 bg-green-50 rounded-xl px-4 py-3 border border-green-200">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-semibold text-green-800">Doprava zdarma</span>
                    </div>
                    <div class="flex items-center gap-3 bg-blue-50 rounded-xl px-4 py-3 border border-blue-200">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-semibold text-blue-800">Čerstvé pražení</span>
                    </div>
                </div>
            </form>
            @else
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border-2 border-red-300 rounded-2xl text-red-700 px-6 py-5 mb-8 flex items-center gap-4 shadow-lg">
                <svg class="w-8 h-8 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                  <p class="font-bold text-lg">Momentálně vyprodáno</p>
                  <p class="text-sm text-red-600">Tento produkt se brzy vrátí na sklad.</p>
                </div>
            </div>
            @endif

            <!-- Product Description -->
            <div class="mb-10 bg-gradient-to-br from-gray-50 to-slate-50 rounded-2xl p-8 border-2 border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-pink-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <h3 class="text-2xl font-black text-gray-900">O produktu</h3>
                </div>
                <div class="text-gray-700 leading-relaxed text-lg prose max-w-none">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            <!-- Product Attributes -->
            @if($product->attributes)
            <div class="bg-white rounded-2xl p-8 border-2 border-gray-200 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                  </div>
                  <h3 class="text-2xl font-black text-gray-900">Specifikace</h3>
                </div>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($product->attributes as $key => $value)
                    @if(!in_array($key, ['roaster', 'flavor_profile', 'preparation_methods']))
                    <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl p-4 border border-gray-200">
                        <dt class="text-gray-600 font-semibold text-sm uppercase tracking-wide mb-1">{{ str_replace('_', ' ', ucfirst($key)) }}</dt>
                        <dd class="text-gray-900 font-bold text-lg">
                          @if(is_array($value))
                            {{ implode(', ', $value) }}
                          @else
                            {{ $value }}
                          @endif
                        </dd>
                    </div>
                    @endif
                    @endforeach
                </dl>
            </div>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="mt-20 pt-20 border-t-4 border-gray-200">
        <div class="text-center mb-12">
          <div class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-100 to-pink-100 rounded-full px-4 py-2 mb-4">
            <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
            <span class="text-sm font-bold text-primary-700">Další doporučení</span>
          </div>
          <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900">
            Mohlo by vás <span class="bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">také zajímat</span>
          </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="group relative bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-gray-200 hover:border-primary-300">
                <a href="{{ route('products.show', $relatedProduct) }}" class="relative block h-64 overflow-hidden">
                    @if($relatedProduct->image)
                    <img src="{{ asset($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    @else
                    <div class="w-full h-full flex flex-col items-center justify-center p-6 bg-gradient-to-br from-primary-100 to-pink-100">
                        <svg class="w-16 h-16 text-primary-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                        </svg>
                        <p class="text-center text-xs font-semibold text-primary-600">{{ $relatedProduct->name }}</p>
                    </div>
                    @endif
                </a>
                <div class="p-5">
                    <a href="{{ route('products.show', $relatedProduct) }}" class="block">
                      <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-2 line-clamp-2">
                          {{ $relatedProduct->name }}
                      </h3>
                    </a>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                      <span class="text-xl font-black bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">{{ number_format($relatedProduct->price, 0, ',', ' ') }} Kč</span>
                      <a href="{{ route('products.show', $relatedProduct) }}" class="flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                      </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
</div>
@endsection
