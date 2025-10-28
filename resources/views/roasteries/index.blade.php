@extends('layouts.app')

@section('title', 'Naše pražírny - Kavi Coffee')

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
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        <span class="text-sm font-bold text-primary-700">Prémiové pražírny</span>
      </div>

      <!-- Main Heading -->
      <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-black text-gray-900">
        Naše <span class="bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">pražírny</span>
      </h1>
      
      <p class="mx-auto max-w-2xl text-lg md:text-xl text-gray-600 mb-8">
        Spolupracujeme s těmi nejlepšími pražírnami z celé Evropy. <span class="font-semibold text-gray-900">Kvalita, tradice a láska ke kávě.</span>
      </p>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="bg-white py-12 sm:py-16 lg:py-20">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">

    <!-- Country Filters - start -->
    <div class="mb-12">
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Filtrovat podle země</h2>
        <p class="text-gray-600">Vyberte zemi, ze které chcete vidět pražírny</p>
      </div>
      
      <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ route('roasteries.index') }}" 
           class="group relative inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-bold transition-all duration-200 {{ !$selectedCountry ? 'bg-gradient-to-r from-primary-500 to-pink-600 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5' : 'bg-white border-2 border-gray-200 text-gray-700 hover:border-primary-300 shadow-md hover:shadow-lg' }}">
          @if(!$selectedCountry)
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          @endif
          <span>Všechny země</span>
        </a>
        
        @foreach($countries as $country => $flag)
        <a href="{{ route('roasteries.index', ['country' => $country]) }}" 
           class="group relative inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-bold transition-all duration-200 {{ $selectedCountry == $country ? 'bg-gradient-to-r from-primary-500 to-pink-600 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5' : 'bg-white border-2 border-gray-200 text-gray-700 hover:border-primary-300 shadow-md hover:shadow-lg' }}">
          @if($selectedCountry == $country)
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          @endif
          <span class="text-2xl">{{ $flag }}</span>
          <span>{{ $country }}</span>
        </a>
        @endforeach
      </div>
    </div>
    <!-- Country Filters - end -->

    <!-- Roasteries Grid -->
    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
      @forelse($roasteries as $roastery)
      <!-- roastery - start -->
      <div class="group relative bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-gray-200 hover:border-primary-300">
        <!-- Image Container -->
        <a href="{{ route('roasteries.show', $roastery) }}" class="relative block h-64 overflow-hidden">
          @if($roastery->image)
          <img src="{{ asset($roastery->image) }}" loading="lazy" alt="{{ $roastery->name }}" class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-110" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
          @else
          <div class="h-full w-full flex flex-col items-center justify-center p-8 bg-gradient-to-br from-primary-100 to-pink-100">
            <svg class="w-20 h-20 text-primary-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
          </div>
          @endif

          <!-- Country Flag - Top Right -->
          <div class="absolute right-4 top-4">
            <span class="text-5xl drop-shadow-2xl">{{ $roastery->country_flag }}</span>
          </div>
          
          <!-- Coffee Count - Top Left -->
          <div class="absolute left-4 top-4 bg-primary-600 text-white rounded-lg px-3 py-1.5 text-xs font-bold shadow-lg">
            {{ $roastery->products()->count() }} {{ $roastery->products()->count() == 1 ? 'káva' : 'káv' }}
          </div>
        </a>

        <!-- Content -->
        <div class="p-6">
          <a href="{{ route('roasteries.show', $roastery) }}" class="block mb-3">
            <h3 class="text-2xl font-black text-gray-900 mb-1 group-hover:text-primary-600 transition-colors">
              {{ $roastery->name }}
            </h3>
            <div class="flex items-center gap-2 text-sm text-gray-600">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span class="font-medium">{{ $roastery->city ?? $roastery->country }}</span>
            </div>
          </a>

          @if($roastery->short_description)
          <p class="text-gray-700 text-sm mb-4 line-clamp-3">
            {{ $roastery->short_description }}
          </p>
          @endif

          <!-- Links -->
          <div class="flex items-center gap-3 mb-4">
            @if($roastery->website_url)
            <a href="{{ $roastery->website_url }}" target="_blank" rel="noopener" class="text-gray-600 hover:text-primary-600 transition-colors" title="Web">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
              </svg>
            </a>
            @endif
            
            @if($roastery->instagram)
            <a href="https://instagram.com/{{ str_replace('@', '', $roastery->instagram) }}" target="_blank" rel="noopener" class="text-gray-600 hover:text-pink-600 transition-colors" title="Instagram">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
            </a>
            @endif
          </div>

          <!-- CTA Button -->
          <a href="{{ route('roasteries.show', $roastery) }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-pink-600 text-white font-bold py-3 px-6 rounded-xl hover:from-primary-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full justify-center">
            <span>Zobrazit detail</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
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

