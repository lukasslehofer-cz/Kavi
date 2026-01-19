@extends('layouts.app')

@section('title', ($currentLocale === 'en' ? 'Coffee of the Month ' : 'Káva měsíce ') . $monthNameWithYear)

@section('meta_description')
{{ $currentLocale === 'en' ? 'Discover our coffee selection for ' . $monthNameWithYear . '. Exceptional specialty coffees from the best European roasters, freshly roasted and delivered to your door.' : 'Objevte náš kávový výběr na ' . $monthNameWithYear . '. Výjimečné výběrové kávy z nejlepších evropských pražíren, čerstvě pražené a doručené až k vám.' }}
@endsection

@section('og_title')
{{ $currentLocale === 'en' ? 'Coffee of the Month ' . $monthNameWithYear . ' | KAVI' : 'Káva měsíce ' . $monthNameWithYear . ' | KAVI.cz' }}
@endsection

@section('og_description')
{{ $currentLocale === 'en' ? 'Every month we select exceptional coffees from the best European roasters. Discover what awaits you in ' . $monthNameWithYear . '.' : 'Každý měsíc pro vás vybíráme výjimečné kávy z nejlepších evropských pražíren. Objevte, co vás čeká v ' . $monthNameWithYear . '.' }}
@endsection

@section('content')
<!-- Hero Header Section - Editorial Layout -->
<div class="relative" style="background-color: #e5e6df;">
  
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    
    <!-- Main Heading - Large Editorial Typography, Left aligned -->
    <h1 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
      <span class="text-dark-800">{{ $currentLocale === 'en' ? 'Coffee of' : 'Pražírny a kávy' }}</span><br>
      <span class="text-primary-500">{{ $currentLocale === 'en' ? 'the month' : 'měsíce' }}</span>
            </h1>
            
    <!-- Description - Right aligned -->
    <div class="flex justify-end">
      <div class="max-w-md text-right">
        <p class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight mb-4">
          {{ $monthNameWithYear }}
        </p>
        <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-500 leading-relaxed">
          {{ $currentLocale === 'en' ? 'Every month we select exceptional coffees from the best European roasters. Discover what awaits you.' : 'Každý měsíc pro vás vybíráme výjimečné kávy z nejlepších evropských pražíren. Objevte, co vás čeká.' }}
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
                    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 uppercase tracking-tight">
                        @if($currentLocale === 'en')
                            {{ $roasteries->count() === 1 ? 'Roaster of the Month' : 'Roasters of the Month' }}
                        @else
                            {{ $roasteries->count() === 1 ? 'Pražírna měsíce' : 'Pražírny měsíce' }}
                        @endif
                    </h2>
                    <p class="text-sm uppercase tracking-widest text-warm-500 mt-3">
                        @if($currentLocale === 'en')
                            {{ $roasteries->count() === 1 ? 'This time we selected coffees from this exceptional roaster' : 'This time we selected coffees from these exceptional roasters' }}
                        @else
                            {{ $roasteries->count() === 1 ? 'Tentokrát jsme vybrali kávy od této výjimečné pražírny' : 'Tentokrát jsme vybrali kávy od těchto výjimečných pražíren' }}
                        @endif
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

                            <div class="flex items-center gap-6">
                                <a href="{{ localizedRoute('roasteries.show', $roastery) }}" 
                                   class="group/link inline-flex items-center gap-2 text-dark-800 text-sm uppercase tracking-widest hover:text-primary-500 transition-colors">
                                    <span>{{ $currentLocale === 'en' ? 'More about roaster' : 'Více o pražírně' }}</span>
                                    <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>

                                @if($roastery->website_url)
                                <a href="{{ $roastery->website_url }}" target="_blank" rel="noopener noreferrer"
                                   class="text-warm-400 hover:text-dark-800 transition-colors"
                                   title="Web pražírny">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                </a>
                                @endif

                                @if($roastery->instagram)
                                <a href="https://instagram.com/{{ str_replace('@', '', $roastery->instagram) }}" target="_blank" rel="noopener noreferrer"
                                   class="text-warm-400 hover:text-dark-800 transition-colors"
                                   title="Instagram">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                                @endif
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
                    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 uppercase tracking-tight">
                        {{ $currentLocale === 'en' ? 'Coffees of the Month' : 'Kávy měsíce' }}
                    </h2>
                    <p class="text-sm uppercase tracking-widest text-warm-500 mt-3">
                        {{ $currentLocale === 'en' ? 'These specialty coffees are part of our current subscription' : 'Tyto výběrové kávy jsou součástí našeho aktuálního předplatného' }}
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
                                 alt="{{ $coffee->name }}"
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

                                    @if(!empty($coffee->attributes['altitude']))
                                    <span>{{ $coffee->attributes['altitude'] }}</span>
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
                                       alt="{{ $coffee->name }}"
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
                                        {{ $currentLocale === 'en' ? 'Coffee of the Month' : 'Káva měsíce' }}
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
                                    <h4 class="font-display text-lg font-normal text-dark-800 uppercase tracking-widest mb-6">{{ $currentLocale === 'en' ? 'Parameters' : 'Parametry' }}</h4>
                                    
                                    @php
                                        $mainAttributes = $currentLocale === 'en' ? 
                                            ['origin' => 'Origin', 'altitude' => 'Altitude', 'processing' => 'Processing', 'variety' => 'Variety', 'flavor_notes' => 'Flavor notes'] :
                                            ['origin' => 'Původ', 'altitude' => 'Nadmořská výška', 'processing' => 'Zpracování', 'variety' => 'Odrůda', 'flavor_notes' => 'Chuťové tóny'];
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
                                            @if($value && !is_array($value) && !in_array($key, ['origin', 'altitude', 'processing', 'variety', 'flavor_notes', 'weight', 'roast_date', 'origin_en', 'altitude_en', 'processing_en', 'variety_en', 'flavor_notes_en']))
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
                                                <span class="text-xs uppercase tracking-widest text-warm-500 block mb-1">{{ $currentLocale === 'en' ? 'Weight' : 'Hmotnost' }}</span>
                                                <span class="text-sm font-medium text-dark-800">{{ $coffee->attributes['weight'] }} g</span>
                                            </div>
                                            @endif
                                            
                                            @if(isset($coffee->attributes['roast_date']) && !empty($coffee->attributes['roast_date']))
                                            <div>
                                                <span class="text-xs uppercase tracking-widest text-warm-500 block mb-1">{{ $currentLocale === 'en' ? 'Roast date' : 'Datum pražení' }}</span>
                                                <span class="text-sm font-medium text-dark-800">
                                                    {{ \Carbon\Carbon::parse($coffee->attributes['roast_date'])->format($currentLocale === 'en' ? 'm/d/Y' : 'd.m.Y') }}
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
                                        {{ $currentLocale === 'en' ? 'Part of the current subscription' : 'Součástí aktuálního předplatného' }}
                                    </p>
                                    <p class="text-warm-500 mb-6 text-sm">
                                        {{ $currentLocale === 'en' ? 'Get it together with other specialty coffees' : 'Získejte ji společně s dalšími výběrovými kávami' }}
                                    </p>
                                    <a href="{{ localizedRoute('subscriptions.index') }}" 
                                       class="inline-flex items-center gap-3 px-8 py-3 bg-dark-800 text-white font-display uppercase tracking-widest hover:bg-dark-900 transition-all duration-200 text-sm">
                                        <span>{{ $currentLocale === 'en' ? 'Learn more' : 'Zjistit více' }}</span>
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
                    {{ $currentLocale === 'en' ? 'Coffee of the Month coming soon' : 'Káva měsíce připravujeme' }}
                </h2>
                <p class="text-lg text-gray-600 mb-8 font-light">
                    {{ $currentLocale === 'en' ? 'We are currently selecting exceptional coffees for ' . $monthNameWithYear . '. We will surprise you soon!' : 'Právě vybíráme výjimečné kávy pro měsíc ' . $monthNameWithYear . '. Brzy vás překvapíme!' }}
                </p>
                <a href="{{ localizedRoute('subscriptions.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 text-white font-medium rounded-full hover:bg-primary-600 transition-all duration-200">
                    <span>{{ $currentLocale === 'en' ? 'Learn more about subscription' : 'Zjistit více o předplatném' }}</span>
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
      <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase">
        {{ $currentLocale === 'en' ? 'Want to receive similar coffees regularly?' : 'Chcete dostávat podobné kávy pravidelně?' }}
      </h2>

      <p class="mb-10 sm:mb-12 text-lg sm:text-xl text-warm-500 max-w-2xl leading-relaxed font-light">
        {{ $currentLocale === 'en' ? 'Subscribe to our subscription and a box with selected coffee specials will be waiting for you at the pickup point.' : 'Přihlaste se k našemu předplatnému a box s vybranými kávovými speciály na vás bude pravidelně čekat ve výdejním místě.' }}
      </p>

      <!-- CTA Link -->
      <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
        <span>{{ $currentLocale === 'en' ? 'Learn more about subscription' : 'Zjistit více o předplatném' }}</span>
        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
      </a>
    </div>
  </div>
</div>
@endif
@endsection
