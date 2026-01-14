@extends('layouts.app')

@section('title', $roastery->getName() . ' - ' . __('roasteries.page_title') . ' - ' . (app()->getLocale() === 'en' ? 'KAVI' : 'KAVI.cz'))

@php
    $currentLocale = app()->getLocale();
    $roasteryDescription = Str::limit(strip_tags($roastery->getShortDescription() ?: $roastery->getFullDescription()), 160);
    $siteUrl = $currentLocale === 'en' ? 'https://kavibox.com' : 'https://kavi.cz';
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
<!-- Minimal Breadcrumb -->
<div class="bg-white py-3 border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <nav class="text-sm">
      <ol class="flex items-center space-x-2 text-gray-500">
        <li><a href="{{ route('home') }}" class="hover:text-gray-900 transition-colors font-light">{{ __('messages.general.home') }}</a></li>
        <li class="text-gray-300">
          <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </li>
        <li><a href="{{ localizedRoute('roasteries.index') }}" class="hover:text-gray-900 transition-colors font-light">{{ __('roasteries.page_title') }}</a></li>
        <li class="text-gray-300">
          <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </li>
        <li class="text-gray-900 font-medium truncate max-w-xs">{{ $roastery->getName() }}</li>
      </ol>
    </nav>
  </div>
</div>

<div class="bg-white">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

    <!-- Roastery Detail - Info LEFT, Image RIGHT -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 mb-20">
        <!-- Roastery Info - Minimal -->
        <div>
            <!-- Country Flag -->
            <div class="mb-5">
              <span class="text-7xl">{{ $roastery->country_flag }}</span>
            </div>
            
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight tracking-tight">
                {{ $roastery->getName() }}
            </h1>
            
            <!-- Location - Minimal -->
            <div class="mb-5">
              <p class="text-base text-gray-600 font-light flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="font-medium">{{ $roastery->getCountry() }}</span>
                @if($roastery->getCity())
                <span class="text-gray-400">•</span>
                <span>{{ $roastery->getCity() }}</span>
                @endif
              </p>
              @if($roastery->address)
              <p class="text-sm text-gray-500 ml-6 mt-1 font-light">{{ $roastery->address }}</p>
              @endif
            </div>
            
            @if($roastery->getShortDescription())
            <p class="text-lg text-gray-600 mb-8 leading-relaxed font-light">{{ $roastery->getShortDescription() }}</p>
            @endif

            <!-- Mobile Image - shown only on mobile, right after perex -->
            <div class="lg:hidden mb-8">
                <div class="relative aspect-square rounded-2xl overflow-hidden border border-gray-200 bg-gray-50">
                    @if($roastery->image)
                    <img src="{{ asset($roastery->image) }}" alt="{{ $roastery->getName() }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex flex-col items-center justify-center p-12 bg-gray-100">
                        <svg class="w-32 h-32 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <p class="text-center text-gray-600 font-medium">{{ $roastery->getName() }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Links - Minimal -->
            <div class="flex flex-wrap items-center gap-3 mb-8">
              @if($roastery->website_url)
              <a href="{{ $roastery->website_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-medium py-2.5 px-5 rounded-full transition-all duration-200 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                <span>{{ __('roasteries.visit_website') }}</span>
              </a>
              @endif
              
              @if($roastery->instagram)
              <a href="https://instagram.com/{{ str_replace('@', '', $roastery->instagram) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-900 border border-gray-200 font-medium py-2.5 px-5 rounded-full transition-all duration-200 text-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                <span>{{ $roastery->instagram }}</span>
              </a>
              @endif
            </div>
            
            <!-- Full Description with gallery images mixed in -->
            @if($roastery->getFullDescription())
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
              @php
                $paragraphs = explode("\n", $roastery->getFullDescription());
                $galleryImages = $roastery->gallery ?? [];
                $totalParagraphs = count($paragraphs);
                $galleryIndex = 0;
              @endphp
              
              @foreach($paragraphs as $index => $paragraph)
                @if(trim($paragraph))
                <p class="mb-6">{{ $paragraph }}</p>
                @endif
                
                <!-- Insert gallery image after every 2 paragraphs -->
                @if($galleryImages && $galleryIndex < count($galleryImages) && ($index + 1) % 2 == 0 && $index > 0)
                <div class="my-8 {{ $galleryIndex % 2 == 0 ? 'float-right ml-6 mb-6' : 'float-left mr-6 mb-6' }} w-full sm:w-1/2 lg:w-2/5">
                  <img src="{{ asset($galleryImages[$galleryIndex]) }}" alt="{{ $roastery->getName() }}" class="w-full rounded-2xl shadow-xl">
                </div>
                @php $galleryIndex++; @endphp
                @endif
              @endforeach
              
              <!-- Show remaining gallery images at the end -->
              @if($galleryImages && $galleryIndex < count($galleryImages))
              <div class="clear-both pt-8 grid grid-cols-2 gap-4">
                @for($i = $galleryIndex; $i < count($galleryImages); $i++)
                <div class="relative aspect-square rounded-xl overflow-hidden shadow-lg">
                  <img src="{{ asset($galleryImages[$i]) }}" alt="{{ $roastery->getName() }}" class="w-full h-full object-cover">
                </div>
                @endfor
              </div>
              @endif
              
              <div class="clear-both"></div>
            </div>
            @endif
        </div>

        <!-- Roastery Image (RIGHT side, sticky) - Desktop only -->
        <div class="hidden lg:block lg:sticky lg:top-24 h-fit">
            <div class="relative aspect-square rounded-2xl overflow-hidden border border-gray-200 bg-gray-50">
                @if($roastery->image)
                <img src="{{ asset($roastery->image) }}" alt="{{ $roastery->getName() }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex flex-col items-center justify-center p-12 bg-gray-100">
                    <svg class="w-32 h-32 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-center text-gray-600 font-medium">{{ $roastery->getName() }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Coffees Section - Minimal -->
    <div>
      <div class="text-center mb-10">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3 tracking-tight">
          {{ __('roasteries.our_coffees_from', ['name' => $roastery->getName()]) }}
        </h2>
        <p class="text-lg text-gray-600 font-light">
          {{ __('roasteries.coffees_description') }}
        </p>
      </div>

      <!-- Coffee of Month Products + Subscription Promo (Priority #1) -->
      @if($coffeeOfMonthProducts->count() > 0)
      <div class="mb-14">
        <div class="flex items-center gap-2.5 mb-6">
          <div class="flex-shrink-0 w-8 h-8 bg-gray-900 rounded-full flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">{{ __('roasteries.in_current_subscription') }}</h3>
        </div>
        
        <!-- Coffees Grid - Minimal -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
          @foreach($coffeeOfMonthProducts as $product)
          <!-- Coffee of Month Card - Minimal -->
          <div class="bg-white rounded-2xl border border-gray-200 hover:border-gray-300 overflow-hidden transition-all duration-200">
            <!-- Coffee Image - Minimal -->
            <div class="relative h-64 overflow-hidden cursor-pointer bg-gray-50" onclick="openCoffeeModal{{ $product->id }}()">
              @if($product->image)
              <img src="{{ asset($product->image) }}" 
                   alt="{{ $product->getName() }}"
                   class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
              @else
              <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                <svg class="w-20 h-20 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                </svg>
              </div>
              @endif

              <!-- Category Badges - Minimal -->
              <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                @if(is_array($product->category))
                  @foreach($product->category as $cat)
                    @if($cat === 'espresso')
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-500 text-white">{{ __('messages.category_espresso') }}</span>
                    @elseif($cat === 'filter')
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-500 text-white">{{ __('messages.category_filter') }}</span>
                    @elseif($cat === 'decaf')
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-500 text-white">{{ __('messages.category_decaf') }}</span>
                    @endif
                  @endforeach
                @endif
              </div>
            </div>

            <!-- Coffee Info - Minimal -->
            <div class="p-5">
              <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                {{ $product->getName() }}
              </h3>

              @if($product->getShortDescription())
              <p class="text-sm text-gray-600 mb-4 line-clamp-3 font-light">
                {{ $product->getShortDescription() }}
              </p>
              @endif

              <button onclick="openCoffeeModal{{ $product->id }}()" 
                      class="w-full py-2 bg-gray-900 text-white font-medium rounded-full hover:bg-gray-800 transition-all duration-200 text-sm">
                {{ __('messages.view_detail') }}
              </button>

              <p class="text-xs text-center text-gray-500 mt-3 font-light">
                {{ __('roasteries.cannot_buy_separately') }}
              </p>
            </div>
          </div>

          <!-- Modal for Coffee Detail -->
          <div id="coffeeModal{{ $product->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closeCoffeeModal{{ $product->id }}(event)">
              <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                  <div class="relative">
                      @if($product->image)
                      <div class="aspect-square w-full overflow-hidden rounded-t-3xl bg-gray-100">
                        <img src="{{ asset($product->image) }}" 
                             alt="{{ $product->getName() }}"
                             class="w-full h-full object-cover">
                      </div>
                      @endif
                      
                      <button onclick="closeCoffeeModal{{ $product->id }}()" 
                              class="absolute top-4 right-4 bg-white rounded-full p-2.5 sm:p-2 shadow-lg hover:bg-gray-100 transition-colors">
                          <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                      </button>

                      <div class="absolute top-4 left-4">
                          <span class="bg-gray-900 text-white text-xs font-medium px-3 py-1.5 rounded-full">
                              {{ __('roasteries.in_current_subscription') }}
                          </span>
                      </div>
                  </div>

                  <div class="p-4 sm:p-6 md:p-8">
                      <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">
                          {{ $product->getName() }}
                      </h3>

                      @if($product->roastery)
                      <p class="text-lg text-gray-600 font-medium mb-6 flex items-center gap-2">
                          <span class="text-2xl">{{ $product->roastery->country_flag }}</span>
                          <a href="{{ localizedRoute('roasteries.show', $product->roastery) }}" class="hover:text-primary-600 transition-colors font-semibold">
                              {{ $product->roastery->getName() }}
                          </a>
                      </p>
                      @endif

                      @if($product->getShortDescription())
                      <p class="text-lg text-gray-700 mb-4 leading-relaxed font-medium">
                          {{ $product->getShortDescription() }}
                      </p>
                      @endif

                      @if($product->getDescription())
                      <div class="prose max-w-none mb-6 text-gray-600 font-light">
                          {!! nl2br(e($product->getDescription())) !!}
                      </div>
                      @endif

                      @if($product->attributes && is_array($product->attributes) && count($product->attributes) > 0)
                      <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                          <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.coffee_parameters') }}</h4>
                          
                          @php
                              $mainAttributes = app()->getLocale() === 'en' ? 
                                  ['origin' => 'Origin', 'altitude' => 'Altitude', 'processing' => 'Processing', 'variety' => 'Variety', 'flavor_notes' => 'Flavor notes'] :
                                  ['origin' => 'Původ', 'altitude' => 'Nadmořská výška', 'processing' => 'Zpracování', 'variety' => 'Odrůda', 'flavor_notes' => 'Chuťové tóny'];
                          @endphp
                          
                          <!-- Main Attributes -->
                          <div class="space-y-3 mb-4">
                              @foreach($mainAttributes as $key => $label)
                                  @php
                                      $attrValue = $product->getTranslatedAttribute($key);
                                  @endphp
                                  @if($attrValue)
                                  <div>
                                      <span class="text-sm text-gray-500 block">{{ $label }}</span>
                                      <span class="text-base font-medium text-gray-900">{{ $attrValue }}</span>
                                  </div>
                                  @endif
                              @endforeach
                              
                              <!-- Other attributes -->
                              @foreach($product->attributes as $key => $value)
                                  @if($value && !is_array($value) && !in_array($key, ['origin', 'altitude', 'processing', 'variety', 'flavor_notes', 'weight', 'roast_date']) && !str_ends_with($key, '_en'))
                                  <div>
                                      <span class="text-sm text-gray-500 block">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                      <span class="text-base font-medium text-gray-900">{{ $value }}</span>
                                  </div>
                                  @endif
                              @endforeach
                          </div>
                          
                          <!-- Additional Info -->
                          @if(isset($product->attributes['weight']) || isset($product->attributes['roast_date']))
                          <div class="pt-4 border-t border-gray-200">
                              <h5 class="text-sm font-semibold text-gray-700 mb-3">{{ __('messages.additional_info') }}</h5>
                              <div class="grid grid-cols-2 gap-4">
                                  @if(isset($product->attributes['weight']) && !empty($product->attributes['weight']))
                                  <div>
                                      <span class="text-sm text-gray-500 block">{{ __('messages.weight') }}</span>
                                      <span class="text-base font-medium text-gray-900">{{ $product->attributes['weight'] }} g</span>
                                  </div>
                                  @endif
                                  
                                  @if(isset($product->attributes['roast_date']) && !empty($product->attributes['roast_date']))
                                  <div>
                                      <span class="text-sm text-gray-500 block">{{ __('messages.roast_date') }}</span>
                                      <span class="text-base font-medium text-gray-900">
                                          {{ \Carbon\Carbon::parse($product->attributes['roast_date'])->format(app()->getLocale() === 'en' ? 'm/d/Y' : 'd.m.Y') }}
                                      </span>
                                  </div>
                                  @endif
                              </div>
                          </div>
                          @endif
                      </div>
                      @endif

                      <div class="bg-gray-50 rounded-2xl p-6 text-center">
                          <p class="text-base font-semibold text-gray-900 mb-2">
                              {{ __('roasteries.coffee_in_subscription') }}
                          </p>
                          <p class="text-gray-600 mb-4 text-sm font-light">
                              {{ __('roasteries.modal_subscription_text') }}
                          </p>
                          <a href="{{ localizedRoute('subscriptions.index') }}" 
                             class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-500 text-white font-medium rounded-full hover:bg-primary-600 transition-all duration-200 text-sm">
                              <span>{{ __('roasteries.choose_subscription') }}</span>
                              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                              </svg>
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
        
        <!-- Subscription Promo Banner - Minimal -->
        <div class="relative bg-gray-100 rounded-2xl p-10 md:p-12 text-center overflow-hidden">
          <!-- Organic shape -->
          <div class="absolute top-0 right-0 w-64 h-64 bg-primary-100 rounded-full translate-x-1/2 -translate-y-1/2"></div>
          
          <div class="relative max-w-3xl mx-auto">
            <h3 class="text-3xl md:text-4xl font-bold mb-4 text-gray-900 tracking-tight">{{ $coffeeOfMonthProducts->count() === 1 ? __('roasteries.want_to_try_this_coffee') : __('roasteries.want_to_try_these_coffees') }}</h3>
            <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-2xl mx-auto font-light">
              {{ __('roasteries.subscription_promo_text', ['name' => $roastery->getName()]) }}
            </p>
            <a href="{{ localizedRoute('subscriptions.index') }}" class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium py-3 px-8 rounded-full transition-all duration-200">
              <span>{{ __('roasteries.choose_subscription') }}</span>
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </a>
          </div>
        </div>
      </div>
      @endif

      <!-- Other Coffees (Active + Historical in one section) -->
      @if($activeProducts->count() > 0 || $historicalProducts->count() > 0)
      <div>
        @php
          $allOtherProducts = $activeProducts->concat($historicalProducts);
        @endphp
        
        <div class="flex items-center gap-2.5 mb-6">
          <div class="flex-shrink-0 w-8 h-8 bg-gray-900 rounded-full flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">{{ __('roasteries.other_coffees_from', ['name' => $roastery->getName()]) }}</h3>
        </div>
        
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          @foreach($allOtherProducts as $product)
          @include('partials.product-card', ['product' => $product, 'historical' => !$product->is_active])
          @endforeach
        </div>
      </div>
      @endif

      @if($coffeeOfMonthProducts->count() == 0 && $activeProducts->count() == 0 && $historicalProducts->count() == 0)
      <div class="text-center py-16 bg-gray-100 rounded-2xl border border-gray-200">
        <svg class="mx-auto h-20 w-20 text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('roasteries.no_coffees_yet') }}</h3>
        <p class="text-gray-600 font-light">{{ __('roasteries.no_coffees_description') }}</p>
      </div>
      @endif
    </div>

  </div>
</div>

@endsection

