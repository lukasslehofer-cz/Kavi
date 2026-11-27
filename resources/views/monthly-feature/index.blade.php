@extends('layouts.app')

@section('title', __('monthly.title', ['month' => $monthNameWithYear]))

@section('meta_description')
{{ __('monthly.meta_description', ['month' => $monthNameWithYear]) }}
@endsection

@section('og_title')
{{ __('monthly.og_title', ['month' => $monthNameWithYear, 'site' => \App\Helpers\Site::current()->name()]) }}
@endsection

@section('og_description')
{{ __('monthly.og_description', ['month' => $monthNameWithYear]) }}
@endsection

@section('content')
<!-- Hero Header Section - Editorial Layout -->
<div class="relative" style="background-color: #e5e6df;">
  
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    
    <!-- Main Heading - Large Editorial Typography, Left aligned -->
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
      <span class="text-dark-800">{{ __('monthly.heading_line1') }}</span><br>
      <span class="text-primary-500">{{ __('monthly.heading_line2') }}</span>
            </h1>
            
    <!-- Description - Right aligned -->
    <div class="flex justify-end">
      <div class="max-w-md text-right">
        <p class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight mb-4">
          {{ $monthNameWithYear }}
        </p>
        <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-500 leading-relaxed">
          {{ __('monthly.intro') }}
            </p>
        </div>
    </div>
    
    </div>
</div>

<!-- Main Content -->
<div class="py-10 sm:py-12 md:py-16 lg:py-20" style="background-color: #e5e6df;">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        
        @if($roasteries->count() > 0 && $coffees->count() > 0)
            
            <!-- Roasteries Section - Editorial Grid -->
            <div class="mb-16 sm:mb-24">
                <!-- Section Header - Large Editorial Typography -->
            <div class="mb-12 sm:mb-16">
                    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 uppercase tracking-tight leading-tight sm:leading-[0.95]">
                        {{ trans_choice('monthly.roasteries.heading', $roasteries->count()) }}
                    </h2>
                    <p class="text-sm uppercase tracking-widest text-warm-500 mt-3">
                        {{ trans_choice('monthly.roasteries.subheading', $roasteries->count()) }}
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">
                    @foreach($roasteries as $roastery)
                    <div class="group">
                        <!-- Roastery Image - No Card, Levitating -->
                        <a href="{{ localizedRoute('roasteries.show', $roastery) }}" class="relative block aspect-square overflow-hidden mb-6">
                            @if($roastery->image)
                            <img src="{{ asset($roastery->image) }}" 
                                 alt="{{ $roastery->getName() }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                            <div class="w-full h-full bg-warm-100 flex items-center justify-center">
                                <span class="text-8xl">{{ $roastery->country_flag }}</span>
                            </div>
                            @endif
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
                                    <span>{{ __('monthly.roasteries.more_about') }}</span>
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
                    @endforeach
                </div>
            </div>

            <!-- Coffees Section - Technical Index -->
            <div class="pt-12 sm:pt-16">
                <!-- Section Header - Large Editorial Typography -->
                <div class="mb-12 sm:mb-16">
                    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 uppercase tracking-tight leading-tight sm:leading-[0.95]">
                        {{ __('monthly.coffees.heading') }}
                    </h2>
                    <p class="text-sm uppercase tracking-widest text-warm-500 mt-3">
                        {{ __('monthly.coffees.subheading') }}
                    </p>
                </div>

                <!-- Coffee Index - Horizontal Rows -->
                <div class="mb-6">
                    @foreach($coffees as $coffee)
                    <div class="group py-6 sm:py-8 cursor-pointer hover:bg-warm-300/30 transition-colors border-b border-warm-300 {{ $loop->first ? 'border-t border-t-warm-300' : '' }}" onclick="openCoffeeModal{{ $coffee->id }}()">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-8">
                            <!-- Coffee Image - Small, Crisp -->
                            <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 overflow-hidden">
                            @if($coffee->image)
                            <img src="{{ asset($coffee->image) }}" 
                                 alt="{{ $coffee->getName() }}"
                                     class="w-full h-full object-contain">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="font-display text-2xl text-warm-400">{{ substr($coffee->getName(), 0, 1) }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Coffee Info - Center -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-baseline gap-x-3 mb-2">
                                    <h3 class="font-display text-xl sm:text-2xl font-normal text-dark-800 uppercase tracking-tight group-hover:text-primary-500 transition-colors">
                                        {{ $coffee->getName() }}
                                    </h3>
                                    @if($coffee->roastery)
                                    <span class="font-display text-xl sm:text-2xl font-normal text-warm-400 uppercase tracking-tight">
                                        {{ $coffee->roastery->getName() }}
                                    </span>
                                    @endif
                                </div>

                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs uppercase tracking-widest text-warm-500">
                                    @if(!empty($coffee->attributes['processing']))
                                    <span>{{ $coffee->getTranslatedAttribute('processing') ?? $coffee->attributes['processing'] }}</span>
                                    <span class="text-warm-300">·</span>
                                    @endif
                                    
                                    @if(!empty($coffee->attributes['flavor_notes']))
                                    <span>{{ $coffee->getTranslatedAttribute('flavor_notes') ?? $coffee->attributes['flavor_notes'] }}</span>
                                    <span class="text-warm-300">·</span>
                            @endif

                                    @php $altitudeFormatted = $coffee->getTranslatedAttribute('altitude'); @endphp
                                    @if(!empty($altitudeFormatted))
                                    <span>{{ $altitudeFormatted }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Category Labels - Museum Catalog Codes -->
                            <div class="flex flex-wrap gap-3 sm:flex-shrink-0">
                                @if(is_array($coffee->category))
                                    @foreach($coffee->category as $cat)
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

                            <!-- Detail Icon - Minimalist -->
                            <div class="hidden sm:flex items-center flex-shrink-0">
                                <svg class="w-6 h-6 text-warm-400 group-hover:text-dark-800 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Coffee Detail - Editorial Style -->
                    <div id="coffeeModal{{ $coffee->id }}" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" onclick="closeCoffeeModal{{ $coffee->id }}(event)">
                        <div class="bg-[#e5e6df] max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                            <div class="relative">
                                @if($coffee->image)
                                <div class="aspect-[4/3] w-full overflow-hidden">
                                  <img src="{{ asset($coffee->image) }}" 
                                       alt="{{ $coffee->getName() }}"
                                       class="w-full h-full object-contain">
                                </div>
                                @endif
                                
                                <button onclick="closeCoffeeModal{{ $coffee->id }}()" 
                                        class="absolute top-4 right-4 bg-[#e5e6df] p-2 hover:bg-warm-200 transition-colors">
                                    <svg class="w-6 h-6 text-dark-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <div class="absolute top-4 left-4">
                                    <span class="bg-dark-800 text-white text-xs uppercase tracking-widest px-3 py-1.5">
                                        {{ __('monthly.modal.badge') }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 sm:p-8">
                                <!-- Coffee Name & Roastery -->
                                <div class="border-b border-dark-800 pb-6 mb-6">
                                    <h3 class="font-display text-3xl sm:text-4xl font-normal text-dark-800 uppercase tracking-tight mb-2">
                                    {{ $coffee->getName() }}
                                </h3>

                                @if($coffee->roastery)
                                    <a href="{{ localizedRoute('roasteries.show', $coffee->roastery) }}" class="font-display text-xl sm:text-2xl font-normal text-warm-400 uppercase tracking-tight hover:text-primary-500 transition-colors">
                                        {{ $coffee->roastery->getName() }}
                                    </a>
                                @endif
                                </div>

                                @if($coffee->getShortDescription())
                                <p class="text-base text-dark-700 mb-6 leading-relaxed">
                                    {{ $coffee->getShortDescription() }}
                                </p>
                                @endif

                                @if($coffee->getDescription())
                                <div class="prose max-w-none mb-6 text-warm-600 font-light text-sm leading-relaxed">
                                    {!! nl2br(e($coffee->getDescription())) !!}
                                </div>
                                @endif

                                @if($coffee->attributes && is_array($coffee->attributes) && count($coffee->attributes) > 0)
                                <div class="border-t border-dark-800 pt-6 mb-6">
                                    <h4 class="font-display text-lg font-normal text-dark-800 uppercase tracking-widest mb-6">{{ __('monthly.modal.parameters') }}</h4>

                                    @php
                                        $mainAttributes = [
                                            'origin' => __('monthly.modal.attributes.origin'),
                                            'altitude' => __('monthly.modal.attributes.altitude'),
                                            'processing' => __('monthly.modal.attributes.processing'),
                                            'variety' => __('monthly.modal.attributes.variety'),
                                            'flavor_notes' => __('monthly.modal.attributes.flavor_notes'),
                                        ];
                                    @endphp
                                    
                                    <!-- Main Attributes - Grid Layout -->
                                    <div class="grid grid-cols-2 gap-x-6 gap-y-4 mb-4">
                                        @foreach($mainAttributes as $key => $label)
                                            @php
                                                $attrValue = $coffee->getTranslatedAttribute($key);
                                            @endphp
                                            @if($attrValue)
                                            <div class="{{ $key === 'flavor_notes' ? 'col-span-2' : '' }}">
                                                <span class="text-xs uppercase tracking-widest text-warm-500 block mb-1">{{ $label }}</span>
                                                <span class="text-sm font-medium text-dark-800 {{ $key === 'flavor_notes' ? 'uppercase' : '' }}">{{ $attrValue }}</span>
                                            </div>
                                            @endif
                                        @endforeach
                                        
                                        <!-- Other attributes -->
                                        @foreach($coffee->attributes as $key => $value)
                                            @if($value && !is_array($value) && !in_array($key, ['origin', 'altitude', 'processing', 'variety', 'flavor_notes', 'weight', 'roast_date']) && !preg_match('/_(en|sk)$/', $key))
                                            <div>
                                                <span class="text-xs uppercase tracking-widest text-warm-500 block mb-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                                <span class="text-sm font-medium text-dark-800">{{ $value }}</span>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    <!-- Additional Info -->
                                    @if(isset($coffee->attributes['weight']) || isset($coffee->attributes['roast_date']))
                                    <div class="pt-4 border-t border-warm-300">
                                        <div class="grid grid-cols-2 gap-4">
                                            @if(isset($coffee->attributes['weight']) && !empty($coffee->attributes['weight']))
                                            <div>
                                                <span class="text-xs uppercase tracking-widest text-warm-500 block mb-1">{{ __('monthly.modal.weight') }}</span>
                                                <span class="text-sm font-medium text-dark-800">{{ $coffee->attributes['weight'] }} g</span>
                                            </div>
                                            @endif
                                            
                                            @if(isset($coffee->attributes['roast_date']) && !empty($coffee->attributes['roast_date']))
                                            <div>
                                                <span class="text-xs uppercase tracking-widest text-warm-500 block mb-1">{{ __('monthly.modal.roast_date') }}</span>
                                                <span class="text-sm font-medium text-dark-800">
                                                    {{ \Carbon\Carbon::parse($coffee->attributes['roast_date'])->format(__('monthly.modal.roast_date_format')) }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <!-- CTA Section -->
                                <div class="border-t-2 border-primary-500 pt-6 text-center">
                                    <p class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight mb-2">
                                        {{ __('monthly.modal.cta_heading') }}
                                    </p>
                                    <p class="text-warm-500 mb-6 text-sm">
                                        {{ __('monthly.modal.cta_text') }}
                                    </p>
                                    <a href="{{ localizedRoute('subscriptions.index') }}" 
                                       class="inline-flex items-center gap-3 px-8 py-3 bg-dark-800 text-white font-display uppercase tracking-widest hover:bg-dark-900 transition-all duration-200 text-sm">
                                        <span>{{ __('monthly.modal.cta_link') }}</span>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function openCoffeeModal{{ $coffee->id }}() {
                            document.getElementById('coffeeModal{{ $coffee->id }}').classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        }

                        function closeCoffeeModal{{ $coffee->id }}(event) {
                            if (event) {
                                event.stopPropagation();
                            }
                            document.getElementById('coffeeModal{{ $coffee->id }}').classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }
                    </script>
                    @endforeach
                </div>
            </div>

        @else
            <!-- No Content Message - Minimal -->
            <div class="bg-gray-50 rounded-3xl p-12 text-center border border-gray-200">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-3xl font-bold text-gray-900 mb-3 tracking-tight">
                    {{ __('monthly.empty.heading') }}
                </h2>
                <p class="text-lg text-gray-600 mb-8 font-light">
                    {{ __('monthly.empty.text', ['month' => $monthNameWithYear]) }}
                </p>
                <a href="{{ localizedRoute('home') }}"
                   class="btn-fill">
                    <span>{{ __('monthly.learn_more_subscription') }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Final CTA Section - Clean Typographic Block -->
@if($roasteries->count() > 0 && $coffees->count() > 0)
<div class="relative pt-10 sm:pt-12 lg:pt-16 pb-16 sm:pb-20 lg:pb-24" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
      <!-- Large Editorial Heading -->
      <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase leading-tight sm:leading-[0.95]">
        {{ __('monthly.cta.heading') }}
      </h2>

      <p class="mb-10 sm:mb-12 text-lg sm:text-xl text-warm-500 max-w-2xl leading-relaxed font-light">
        {{ __('monthly.cta.text') }}
      </p>

      <!-- CTA Link -->
      <a href="{{ localizedRoute('home') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
        <span>{{ __('monthly.learn_more_subscription') }}</span>
        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
      </a>
    </div>
  </div>
</div>
@endif
@endsection
