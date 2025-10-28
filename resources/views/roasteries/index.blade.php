@extends('layouts.app')

@section('title', 'Naše pražírny - Kavi Coffee')

@section('content')
<!-- Hero Header Section - Minimal -->
<div class="relative bg-gray-100 py-16 md:py-20 overflow-hidden">
  <!-- Subtle Organic Shapes -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary-100 rounded-full"></div>
    <div class="absolute -bottom-32 -left-32 w-[36rem] h-[36rem] bg-primary-50 rounded-full"></div>
  </div>
  
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="text-center max-w-3xl mx-auto">
      <!-- Minimal Badge -->
      <div class="inline-flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 mb-6">
        <svg class="w-4 h-4 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        <span class="text-sm font-medium text-gray-900">Prémiové pražírny</span>
      </div>

      <!-- Clean Heading -->
      <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
        Naše pražírny
      </h1>
      
      <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light">
        Spolupracujeme s těmi nejlepšími pražírnami z celé Evropy. Kvalita, tradice a láska ke kávě.
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
<div class="bg-white py-12 sm:py-16 lg:py-20">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">

    <!-- Country Filters - Minimal -->
    <div class="mb-10">
      <div class="text-center mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-1">Filtrovat podle země</h2>
        <p class="text-gray-600 text-sm font-light">Vyberte zemi, ze které chcete vidět pražírny</p>
      </div>
      
      <div class="flex flex-wrap justify-center gap-2">
        <a href="{{ route('roasteries.index') }}" 
           class="inline-flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-medium transition-all duration-200 {{ !$selectedCountry ? 'bg-primary-500 text-white hover:bg-primary-600' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
          @if(!$selectedCountry)
          <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          @endif
          <span>Všechny země</span>
        </a>
        
        @foreach($countries as $country => $flag)
        <a href="{{ route('roasteries.index', ['country' => $country]) }}" 
           class="inline-flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-medium transition-all duration-200 {{ $selectedCountry == $country ? 'bg-primary-500 text-white hover:bg-primary-600' : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
          @if($selectedCountry == $country)
          <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          @endif
          <span class="text-lg">{{ $flag }}</span>
          <span>{{ $country }}</span>
        </a>
        @endforeach
      </div>
    </div>
    <!-- Country Filters - end -->

    <!-- Roasteries Grid - Minimal -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @forelse($roasteries as $roastery)
      <!-- roastery - start -->
      <div class="group relative bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-gray-300 transition-all duration-200">
        <!-- Image Container -->
        <a href="{{ route('roasteries.show', $roastery) }}" class="relative block h-56 overflow-hidden bg-gray-50">
          @if($roastery->image)
          <img src="{{ asset($roastery->image) }}" loading="lazy" alt="{{ $roastery->name }}" class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105" />
          @else
          <div class="h-full w-full flex flex-col items-center justify-center p-8 bg-gray-100">
            <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
          @endif

          <!-- Country Flag -->
          <div class="absolute right-3 top-3 text-3xl">
            {{ $roastery->country_flag }}
          </div>
          
          <!-- Coffee Count -->
          <div class="absolute left-3 top-3 bg-gray-900 text-white rounded-full px-2.5 py-1 text-xs font-medium">
            {{ $roastery->products()->count() }} {{ $roastery->products()->count() == 1 ? 'káva' : 'káv' }}
          </div>
        </a>

        <!-- Content - Minimal -->
        <div class="p-5">
          <a href="{{ route('roasteries.show', $roastery) }}" class="block mb-2">
            <h3 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-gray-600 transition-colors">
              {{ $roastery->name }}
            </h3>
            <div class="flex items-center gap-1.5 text-sm text-gray-500">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span class="font-light">{{ $roastery->city ?? $roastery->country }}</span>
            </div>
          </a>

          @if($roastery->short_description)
          <p class="text-gray-600 text-sm mb-4 line-clamp-3 font-light">
            {{ $roastery->short_description }}
          </p>
          @endif

          <!-- Links - Minimal -->
          <div class="flex items-center gap-2 mb-4">
            @if($roastery->website_url)
            <a href="{{ $roastery->website_url }}" target="_blank" rel="noopener" class="text-gray-500 hover:text-gray-900 transition-colors" title="Web">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
              </svg>
            </a>
            @endif
            
            @if($roastery->instagram)
            <a href="https://instagram.com/{{ str_replace('@', '', $roastery->instagram) }}" target="_blank" rel="noopener" class="text-gray-500 hover:text-pink-600 transition-colors" title="Instagram">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
            </a>
            @endif
          </div>

          <!-- CTA Button - Minimal -->
          <a href="{{ route('roasteries.show', $roastery) }}" class="w-full py-2 bg-gray-900 text-white font-medium rounded-full hover:bg-gray-800 transition-all duration-200 text-sm flex items-center justify-center">
            Zobrazit detail
          </a>
        </div>
      </div>
      <!-- roastery - end -->
      @empty
      <!-- Empty state -->
      <div class="col-span-full">
        <div class="text-center py-16">
          <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Žádné pražírny</h3>
          <p class="text-gray-600 mb-6">
            @if($selectedCountry)
              Pro zemi {{ $selectedCountry }} nemáme žádné pražírny. Zkuste vybrat jinou zemi.
            @else
              Momentálně nemáme žádné pražírny v nabídce.
            @endif
          </p>
          @if($selectedCountry)
          <a href="{{ route('roasteries.index') }}" class="inline-flex items-center gap-2 bg-primary-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-primary-700 transition-colors">
            <span>Zobrazit všechny pražírny</span>
          </a>
          @endif
        </div>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endsection

