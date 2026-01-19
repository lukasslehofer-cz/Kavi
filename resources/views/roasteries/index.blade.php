@extends('layouts.app')

@section('title', $currentLocale === 'en' ? 'Our Roasters - KAVI' : 'Naše pražírny - KAVI.cz')

@section('meta_description')
{{ $currentLocale === 'en' ? 'Discover our partner roasters from across Europe. Premium specialty coffee from Belgium, Poland, Germany and more. Quality, tradition and love for coffee.' : 'Objevte naše partnerské pražírny z celé Evropy. Prémiová výběrová káva z Belgie, Polska, Německa a dalších zemí. Kvalita, tradice a láska ke kávě.' }}
@endsection

@section('og_title')
{{ $currentLocale === 'en' ? 'Our Partner Roasters | KAVI' : 'Naše partnerské pražírny | KAVI.cz' }}
@endsection

@section('og_description')
{{ $currentLocale === 'en' ? 'We work with the best roasters from all over Europe. Discover premium specialty coffee from Belgium, Poland, Germany and more.' : 'Spolupracujeme s nejlepšími pražírnami z celé Evropy. Objevte prémiovou výběrovou kávu z Belgie, Polska, Německa a dalších zemí.' }}
@endsection

@section('content')
<!-- Hero Header Section - Editorial Layout -->
<div class="relative" style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    
    <!-- Main Heading - Large Editorial Typography, Left aligned -->
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
      <span class="text-dark-800">{{ $currentLocale === 'en' ? 'Our' : 'Naše' }}</span><br>
      <span class="text-primary-500">{{ $currentLocale === 'en' ? 'roasters' : 'pražírny' }}</span>
      </h1>
      
    <!-- Description - Right aligned -->
    <div class="flex justify-end">
      <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-500 max-w-md text-right leading-relaxed">
        {{ $currentLocale === 'en' ? 'We work with the best roasters from all over Europe. Quality, tradition and love for coffee.' : 'Spolupracujeme s těmi nejlepšími pražírnami z celé Evropy. Kvalita, tradice a láska ke kávě.' }}
      </p>
  </div>
  
  </div>
</div>

<!-- Main Content -->
<div class="py-10 sm:py-12 md:py-16 lg:py-20" style="background-color: #e5e6df;">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">

    <!-- Country Filters - Swiss Style -->
    <div class="mb-10 sm:mb-12">
      <div class="border-t border-b border-dark-800 py-4">
        <div class="flex flex-wrap items-center gap-x-8 gap-y-2">
        <a href="{{ localizedRoute('roasteries.index') }}" 
             class="text-sm uppercase tracking-widest transition-colors {{ !$selectedCountry ? 'text-primary-500 border-b border-primary-500 pb-0.5' : 'text-warm-500 hover:text-dark-800' }}">
            {{ $currentLocale === 'en' ? 'All' : 'Vše' }}<sup class="ml-0.5 text-[10px]">{{ str_pad($totalRoasteriesCount, 2, '0', STR_PAD_LEFT) }}</sup>
        </a>
        @foreach($countries as $country => $countryData)
        <a href="{{ localizedRoute('roasteries.index', ['country' => $country]) }}" 
             class="text-sm uppercase tracking-widest transition-colors {{ $selectedCountry == $country ? 'text-primary-500 border-b border-primary-500 pb-0.5' : 'text-warm-500 hover:text-dark-800' }}">
            {{ $countryData['name'] }}<sup class="ml-0.5 text-[10px]">{{ str_pad($countryData['count'], 2, '0', STR_PAD_LEFT) }}</sup>
        </a>
        @endforeach
        </div>
      </div>
    </div>
    <!-- Country Filters - end -->

    <!-- Roasteries Grid - Editorial Style (like /kava-mesice) -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">
      @forelse($roasteries as $roastery)
      <!-- roastery - start -->
      <div class="group">
        <!-- Roastery Image - No Card, Levitating -->
        <a href="{{ localizedRoute('roasteries.show', $roastery) }}" class="relative block aspect-square overflow-hidden mb-6">
          @if($roastery->image)
          <img src="{{ asset($roastery->image) }}" 
               loading="lazy" 
               alt="{{ $roastery->getName() }}"
               class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          @else
          <div class="w-full h-full bg-warm-100 flex items-center justify-center">
            <span class="text-8xl">{{ $roastery->country_flag }}</span>
          </div>
          @endif
          
          <!-- Coffee Count - Top Left (like product tags) -->
          <div class="absolute top-0 left-0">
            @php
              $coffeeCount = $roastery->products()->count();
              if ($currentLocale === 'en') {
                $coffeeWord = $coffeeCount === 1 ? 'coffee' : 'coffees';
              } else {
                $coffeeWord = match(true) {
                  $coffeeCount === 1 => 'káva',
                  $coffeeCount >= 2 && $coffeeCount <= 4 => 'kávy',
                  default => 'káv'
                };
              }
            @endphp
            <span class="text-[10px] uppercase tracking-widest text-dark-800 bg-[rgb(245,245,244)] px-2 py-1 border-b-2 border-dark-800">
            {{ $coffeeCount }} {{ $coffeeWord }}
            </span>
          </div>
        </a>

        <!-- Roastery Info - Clean Typography -->
        <div>
          <a href="{{ localizedRoute('roasteries.show', $roastery) }}" class="block mb-2">
            <h3 class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight group-hover:text-primary-500 transition-colors">
              {{ $roastery->getName() }}
            </h3>
          </a>

          <p class="text-xs uppercase tracking-widest text-warm-500 mb-4">
            {{ $roastery->getCountry() }}@if($roastery->getCity()), {{ $roastery->getCity() }}@endif
          </p>

          @if($roastery->getShortDescription())
          <p class="text-warm-600 mb-6 line-clamp-3 text-sm font-light leading-relaxed">
            {{ $roastery->getShortDescription() }}
          </p>
          @endif

          <div class="flex items-center justify-between">
            <a href="{{ localizedRoute('roasteries.show', $roastery) }}" 
               class="group/link inline-flex items-center gap-2 text-dark-800 text-sm uppercase tracking-widest hover:text-primary-500 transition-colors">
              <span>{{ $currentLocale === 'en' ? 'More about roaster' : 'Více o pražírně' }}</span>
              <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </a>

            <div class="flex items-center gap-4">
            @if($roastery->website_url)
            <a href="{{ $roastery->website_url }}" target="_blank" rel="noopener noreferrer"
                 class="text-xs uppercase tracking-widest text-warm-400 hover:text-dark-800 transition-colors">
                WWW
            </a>
            @endif

            @if($roastery->instagram)
            <a href="https://instagram.com/{{ str_replace('@', '', $roastery->instagram) }}" target="_blank" rel="noopener noreferrer"
                 class="text-xs uppercase tracking-widest text-warm-400 hover:text-dark-800 transition-colors">
                IG
            </a>
            @endif
            </div>
          </div>
        </div>
      </div>
      <!-- roastery - end -->
      @empty
      <!-- Empty state -->
      <div class="col-span-full">
        <div class="text-center py-16">
          <svg class="mx-auto h-24 w-24 text-warm-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
          <h3 class="font-display text-2xl font-normal text-dark-800 uppercase tracking-tight mb-2">{{ $currentLocale === 'en' ? 'No roasters' : 'Žádné pražírny' }}</h3>
          <p class="text-warm-500 mb-6 text-sm uppercase tracking-widest">
            @if($selectedCountry)
              {{ $currentLocale === 'en' ? 'We have no roasters from ' . ($selectedCountryName ?? $selectedCountry) . '. Try selecting another country.' : 'Pro zemi ' . ($selectedCountryName ?? $selectedCountry) . ' nemáme žádné pražírny. Zkuste vybrat jinou zemi.' }}
            @else
              {{ $currentLocale === 'en' ? 'We currently have no roasters available.' : 'Momentálně nemáme žádné pražírny v nabídce.' }}
            @endif
          </p>
          @if($selectedCountry)
          <a href="{{ localizedRoute('roasteries.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
            <span>{{ $currentLocale === 'en' ? 'Show all roasters' : 'Zobrazit všechny pražírny' }}</span>
            <span class="text-primary-500 group-hover:translate-x-1 transition-transform">→</span>
          </a>
          @endif
        </div>
      </div>
      @endforelse
    </div>

    <!-- Pagination - Technical Indexing Style -->
    @if($roasteries->hasPages())
    <div class="mt-16 border-t border-dark-800 pt-6">
      <div class="flex items-center justify-end gap-6">
        <!-- Page Counter -->
        <span class="text-xs uppercase tracking-widest text-warm-500">
          {{ $currentLocale === 'en' ? 'Page' : 'Strana' }} {{ str_pad($roasteries->currentPage(), 2, '0', STR_PAD_LEFT) }} / {{ str_pad($roasteries->lastPage(), 2, '0', STR_PAD_LEFT) }}
        </span>
        
        <!-- Navigation -->
        <div class="flex items-center">
          @if($roasteries->onFirstPage())
            <span class="text-xs uppercase tracking-widest text-warm-300 cursor-not-allowed">
              {{ $currentLocale === 'en' ? 'Previous' : 'Předchozí' }}
            </span>
          @else
            <a href="{{ $roasteries->previousPageUrl() }}" class="text-xs uppercase tracking-widest text-warm-500 hover:text-dark-800 transition-colors">
              {{ $currentLocale === 'en' ? 'Previous' : 'Předchozí' }}
            </a>
          @endif
          
          <span class="mx-4 text-warm-300">—</span>
          
          @if($roasteries->hasMorePages())
            <a href="{{ $roasteries->nextPageUrl() }}" class="text-xs uppercase tracking-widest text-warm-500 hover:text-dark-800 transition-colors">
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

<!-- CTA Banner - Editorial Style -->
<div class="relative pt-12 sm:pt-16 lg:pt-20 pb-20 sm:pb-24 lg:pb-28" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
      <!-- Large Editorial Heading -->
      <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase leading-tight sm:leading-[0.95]">
        {{ $currentLocale === 'en' ? 'Want to try their coffees?' : 'Chcete ochutnat jejich kávy?' }}
      </h2>

      <p class="mb-10 sm:mb-12 text-lg sm:text-xl text-warm-500 max-w-2xl leading-relaxed font-light">
        {{ $currentLocale === 'en' ? 'With our subscription, you can taste coffees from various European roasters every month. Fresh specialty coffee delivered to your door.' : 'S naším předplatným můžete každý měsíc ochutnat kávy od různých evropských pražíren. Čerstvá výběrová káva přímo k vám domů.' }}
      </p>

      <!-- CTA Link -->
      <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
        <span>{{ $currentLocale === 'en' ? 'Learn more about subscription' : 'Zjistit více o předplatném' }}</span>
        <span class="text-primary-500 group-hover:translate-x-1 transition-transform">→</span>
      </a>
    </div>
  </div>
</div>
@endsection
