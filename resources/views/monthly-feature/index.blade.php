@extends('layouts.app')

@section('title', 'Káva měsíce ' . $monthNameWithYear)

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span class="text-sm font-medium text-gray-900">Pražírny a kávy měsíce</span>
            </div>

            <!-- Clean Heading -->
            <h1 class="mb-6 text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                {{ $monthNameWithYear }}
            </h1>
            
            <p class="mx-auto max-w-2xl text-base sm:text-lg text-gray-600 font-light">
                Každý měsíc pro vás vybíráme výjimečné kávy z nejlepších evropských pražíren. Co&nbsp;vás čeká tento měsíc?
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
        
        @if($roasteries->count() > 0 && $coffees->count() > 0)
            
            <!-- Roasteries Section -->
            <div class="mb-12 sm:mb-16">
                <div class="text-center mb-8 sm:mb-10">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 tracking-tight">
                        {{ $roasteries->count() === 1 ? 'Pražírna měsíce' : 'Pražírny měsíce' }}
                    </h2>
                    <p class="text-base text-gray-600 font-light">
                        {{ $roasteries->count() === 1 ? 'Tentokrát jsme vybrali kávy od této výjimečné pražírny' : 'Tentokrát jsme vybrali kávy od těchto výjimečných pražíren' }}
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($roasteries as $roastery)
                    <div class="group bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-gray-300 transition-all duration-200">
                        <!-- Roastery Image -->
                        <a href="{{ route('roasteries.show', $roastery) }}" class="relative block aspect-[4/3] md:aspect-square overflow-hidden bg-gray-50">
                            @if($roastery->image)
                            <img src="{{ asset($roastery->image) }}" 
                                 alt="{{ $roastery->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <span class="text-6xl">{{ $roastery->country_flag }}</span>
                            </div>
                            @endif
                            
                            <!-- Minimal Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="bg-gray-900 text-white text-xs font-medium px-3 py-1.5 rounded-full">
                                    Pražírna měsíce
                                </span>
                            </div>

                            <!-- Country Flag -->
                            <div class="absolute top-3 right-3 text-3xl">
                                {{ $roastery->country_flag }}
                            </div>
                        </a>

                        <!-- Roastery Info -->
                        <div class="p-5">
                            <a href="{{ route('roasteries.show', $roastery) }}" class="block mb-2">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-gray-600 transition-colors">
                                    {{ $roastery->name }}
                                </h3>
                            </a>

                            <div class="flex items-center gap-1.5 mb-4 text-gray-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm font-light">{{ $roastery->country }}@if($roastery->city), {{ $roastery->city }}@endif</span>
                            </div>

                            @if($roastery->short_description)
                            <p class="text-gray-600 mb-5 line-clamp-3 text-sm font-light leading-relaxed">
                                {{ $roastery->short_description }}
                            </p>
                            @endif

                            <div class="flex gap-2">
                                <a href="{{ route('roasteries.show', $roastery) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-900 text-white font-medium rounded-full hover:bg-gray-800 transition-all duration-200 text-sm">
                                    Více o pražírně
                                </a>

                                @if($roastery->website_url)
                                <a href="{{ $roastery->website_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center w-12 h-12 sm:w-10 sm:h-10 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors"
                                   title="Web pražírny">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                </a>
                                @endif

                                @if($roastery->instagram)
                                <a href="https://instagram.com/{{ str_replace('@', '', $roastery->instagram) }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center w-12 h-12 sm:w-10 sm:h-10 bg-gray-100 text-gray-700 rounded-full hover:bg-pink-100 hover:text-pink-600 transition-colors"
                                   title="Instagram">
                                    <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
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

            <!-- Coffees Section -->
            <div class="border-t border-gray-100 pt-12 sm:pt-16">
                <div class="text-center mb-8 sm:mb-10">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2 tracking-tight">
                        Kávy měsíce
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 font-light">
                        Tyto výběrové kávy jsou součástí našeho aktuálního předplatného
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach($coffees as $coffee)
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-gray-300 transition-all duration-200">
                        <!-- Coffee Image -->
                        <div class="relative aspect-[4/3] md:aspect-square overflow-hidden cursor-pointer bg-gray-50" onclick="openCoffeeModal{{ $coffee->id }}()">
                            @if($coffee->image)
                            <img src="{{ asset($coffee->image) }}" 
                                 alt="{{ $coffee->name }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                            @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                            </div>
                            @endif

                            <!-- Category Badges -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                @if(is_array($coffee->category))
                                    @foreach($coffee->category as $cat)
                                        @if($cat === 'espresso')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-500 text-white">Espresso</span>
                                        @elseif($cat === 'filter')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-500 text-white">Filtr</span>
                                        @elseif($cat === 'decaf')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-500 text-white">Decaf</span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Coffee Info -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                {{ $coffee->name }}
                            </h3>

                            @if($coffee->roastery)
                            <p class="text-sm text-gray-500 font-light mb-3 flex items-center gap-1">
                                <span class="text-base">{{ $coffee->roastery->country_flag }}</span>
                                <a href="{{ route('roasteries.show', $coffee->roastery) }}" class="hover:text-gray-900 transition-colors">
                                    {{ $coffee->roastery->name }}
                                </a>
                            </p>
                            @endif

                            <!-- Flavor Tones -->
                            @if(!empty($coffee->attributes['flavor_profile']) || !empty($coffee->attributes['flavor_notes']))
                            <div class="text-sm mb-4">                                
                                <span class="text-gray-600 font-light">{{ $coffee->attributes['flavor_profile'] ?? $coffee->attributes['flavor_notes'] }}</span>
                            </div>
                            @endif

                            <button onclick="openCoffeeModal{{ $coffee->id }}()" 
                                    class="w-full py-2.5 bg-gray-900 text-white font-medium rounded-full hover:bg-gray-800 transition-all duration-200 text-sm">
                                Zobrazit detail
                            </button>

                            <p class="text-xs text-center text-gray-500 mt-2.5 font-light">
                                Tuto kávu nelze zakoupit samostatně
                            </p>
                        </div>
                    </div>

                    <!-- Modal for Coffee Detail -->
                    <div id="coffeeModal{{ $coffee->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closeCoffeeModal{{ $coffee->id }}(event)">
                        <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                            <div class="relative">
                                @if($coffee->image)
                                <div class="aspect-square w-full overflow-hidden rounded-t-3xl bg-gray-100">
                                  <img src="{{ asset($coffee->image) }}" 
                                       alt="{{ $coffee->name }}"
                                       class="w-full h-full object-cover">
                                </div>
                                @endif
                                
                                <button onclick="closeCoffeeModal{{ $coffee->id }}()" 
                                        class="absolute top-4 right-4 bg-white rounded-full p-2.5 sm:p-2 shadow-lg hover:bg-gray-100 transition-colors">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <div class="absolute top-4 left-4">
                                    <span class="bg-gray-900 text-white text-xs font-medium px-3 py-1.5 rounded-full">
                                        Káva měsíce {{ $monthNameWithYear }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 sm:p-6 md:p-8">
                                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">
                                    {{ $coffee->name }}
                                </h3>

                                @if($coffee->roastery)
                                <p class="text-lg text-gray-600 font-medium mb-6 flex items-center gap-2">
                                    <span class="text-2xl">{{ $coffee->roastery->country_flag }}</span>
                                    <a href="{{ route('roasteries.show', $coffee->roastery) }}" class="hover:text-primary-600 transition-colors font-semibold">
                                        {{ $coffee->roastery->name }}
                                    </a>
                                </p>
                                @endif

                                @if($coffee->description)
                                <div class="prose max-w-none mb-6">
                                    {!! nl2br(e($coffee->description)) !!}
                                </div>
                                @endif

                                @if($coffee->attributes && is_array($coffee->attributes) && count($coffee->attributes) > 0)
                                <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Parametry kávy</h4>
                                    
                                    @php
                                        $mainAttributes = ['origin' => 'Původ', 'altitude' => 'Nadmořská výška', 'processing' => 'Zpracování', 'variety' => 'Odrůda', 'flavor_notes' => 'Chuťové tóny'];
                                    @endphp
                                    
                                    <!-- Main Attributes -->
                                    <div class="space-y-3 mb-4">
                                        @foreach($mainAttributes as $key => $label)
                                            @if(isset($coffee->attributes[$key]) && !empty($coffee->attributes[$key]))
                                            <div>
                                                <span class="text-sm text-gray-500 block">{{ $label }}</span>
                                                <span class="text-base font-medium text-gray-900">{{ $coffee->attributes[$key] }}</span>
                                            </div>
                                            @endif
                                        @endforeach
                                        
                                        <!-- Other attributes -->
                                        @foreach($coffee->attributes as $key => $value)
                                            @if($value && !is_array($value) && !in_array($key, ['origin', 'altitude', 'processing', 'variety', 'flavor_notes', 'weight', 'roast_date']))
                                            <div>
                                                <span class="text-sm text-gray-500 block">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                                <span class="text-base font-medium text-gray-900">{{ $value }}</span>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    <!-- Additional Info -->
                                    @if(isset($coffee->attributes['weight']) || isset($coffee->attributes['roast_date']))
                                    <div class="pt-4 border-t border-gray-200">
                                        <h5 class="text-sm font-semibold text-gray-700 mb-3">Doplňkové informace</h5>
                                        <div class="grid grid-cols-2 gap-4">
                                            @if(isset($coffee->attributes['weight']) && !empty($coffee->attributes['weight']))
                                            <div>
                                                <span class="text-sm text-gray-500 block">Hmotnost</span>
                                                <span class="text-base font-medium text-gray-900">{{ $coffee->attributes['weight'] }} g</span>
                                            </div>
                                            @endif
                                            
                                            @if(isset($coffee->attributes['roast_date']) && !empty($coffee->attributes['roast_date']))
                                            <div>
                                                <span class="text-sm text-gray-500 block">Datum pražení</span>
                                                <span class="text-base font-medium text-gray-900">
                                                    {{ \Carbon\Carbon::parse($coffee->attributes['roast_date'])->format('d.m.Y') }}
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
                                        Tato káva je součástí aktuálního předplatného
                                    </p>
                                    <p class="text-gray-600 mb-4 text-sm font-light">
                                        Získejte ji společně s dalšími výběrovými kávami v našem měsíčním předplatném
                                    </p>
                                    <a href="{{ route('subscriptions.index') }}" 
                                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary-500 text-white font-medium rounded-full hover:bg-primary-600 transition-all duration-200 text-sm">
                                        <span>Zjistit více o předplatném</span>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
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

                <!-- CTA Section - Minimal -->
                <div class="relative bg-gray-100 rounded-3xl p-10 md:p-12 text-center overflow-hidden">
                    <!-- Organic shape -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary-100 rounded-full translate-x-1/2 -translate-y-1/2"></div>
                    
                    <div class="relative">
                        <h2 class="text-3xl md:text-4xl font-bold mb-3 text-gray-900 tracking-tight">
                            Chcete dostávat podobné kávy pravidelně?
                        </h2>
                        <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto font-light">
                            Přihlaste se k našemu předplatnému a box s vybranými kávovými speciály na vás bude pravidelně čekat ve výdejním místě.
                        </p>
                        <a href="{{ route('subscriptions.index') }}" 
                           class="inline-flex items-center gap-2 px-8 py-3 bg-primary-500 text-white font-medium rounded-full hover:bg-primary-600 transition-all">
                            <span>Zjistit více o předplatném</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        @else
            <!-- No Content Message - Minimal -->
            <div class="bg-gray-50 rounded-3xl p-12 text-center border border-gray-200">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-3xl font-bold text-gray-900 mb-3 tracking-tight">
                    Káva měsíce připravujeme
                </h2>
                <p class="text-lg text-gray-600 mb-8 font-light">
                    Právě vybíráme výjimečné kávy pro měsíc {{ $monthNameWithYear }}. Brzy vás překvapíme!
                </p>
                <a href="{{ route('subscriptions.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 text-white font-medium rounded-full hover:bg-primary-600 transition-all duration-200">
                    <span>Zjistit více o předplatném</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
