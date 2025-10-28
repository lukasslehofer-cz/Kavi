@extends('layouts.app')

@section('title', 'Káva měsíce ' . ucfirst($monthName))

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span class="text-sm font-bold text-primary-700">Měsíční výběr</span>
            </div>

            <!-- Main Heading -->
            <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-black text-gray-900">
                Káva měsíce <span class="bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">{{ ucfirst($monthName) }}</span>
            </h1>
            
            <p class="mx-auto max-w-2xl text-lg md:text-xl text-gray-600 mb-8">
                Každý měsíc pro vás vybíráme výjimečné kávy od nejlepších evropských pražíren. <span class="font-semibold text-gray-900">Objevte tento měsíc s námi.</span>
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-white py-12 sm:py-16 lg:py-20">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        
        @if($roasteries->count() > 0 && $coffees->count() > 0)
            
            <!-- Roasteries Section -->
            <div class="mb-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                        {{ $roasteries->count() === 1 ? 'Pražírna měsíce' : 'Pražírny měsíce' }}
                    </h2>
                    <p class="text-lg text-gray-600">
                        {{ $roasteries->count() === 1 ? 'Tentokrát jsme vybrali kávy od této výjimečné pražírny' : 'Tentokrát jsme vybrali kávy od těchto výjimečných pražíren' }}
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($roasteries as $roastery)
                    <div class="group bg-white rounded-2xl overflow-hidden shadow-xl border-2 border-gray-200 hover:shadow-2xl hover:border-primary-300 transition-all duration-300">
                        <!-- Roastery Image -->
                        <a href="{{ route('roasteries.show', $roastery) }}" class="relative block h-64 overflow-hidden">
                            @if($roastery->image)
                            <img src="{{ asset($roastery->image) }}" 
                                 alt="{{ $roastery->name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-100 to-pink-100 flex items-center justify-center">
                                <span class="text-7xl">{{ $roastery->country_flag }}</span>
                            </div>
                            @endif
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            
                            <!-- Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="bg-primary-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                    Pražírna měsíce
                                </span>
                            </div>

                            <!-- Country Flag -->
                            <div class="absolute top-4 right-4 text-4xl">
                                {{ $roastery->country_flag }}
                            </div>
                        </a>

                        <!-- Roastery Info -->
                        <div class="p-6">
                            <a href="{{ route('roasteries.show', $roastery) }}" class="block mb-3">
                                <h3 class="text-2xl font-bold text-gray-900 group-hover:text-primary-600 transition-colors">
                                    {{ $roastery->name }}
                                </h3>
                            </a>

                            <div class="flex items-center gap-2 mb-4 text-gray-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm font-medium">{{ $roastery->country }}@if($roastery->city), {{ $roastery->city }}@endif</span>
                            </div>

                            @if($roastery->short_description)
                            <p class="text-gray-600 mb-6 line-clamp-3">
                                {{ $roastery->short_description }}
                            </p>
                            @endif

                            <div class="flex gap-3">
                                <a href="{{ route('roasteries.show', $roastery) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-primary-500 to-pink-600 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Více o pražírně
                                </a>

                                @if($roastery->website_url)
                                <a href="{{ $roastery->website_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors"
                                   title="Web pražírny">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
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
            <div class="border-t-2 border-gray-200 pt-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                        Kávy měsíce {{ ucfirst($monthName) }}
                    </h2>
                    <p class="text-lg text-gray-600">
                        Tyto výběrové kávy jsou součástí našeho aktuálního předplatného
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($coffees as $coffee)
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-primary-200 hover:shadow-2xl transition-all duration-300">
                        <!-- Coffee Image -->
                        <div class="relative h-80 overflow-hidden cursor-pointer" onclick="openCoffeeModal{{ $coffee->id }}()">
                            @if($coffee->image)
                            <img src="{{ asset($coffee->image) }}" 
                                 alt="{{ $coffee->name }}"
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                            @else
                            <div class="w-full h-full bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                                <svg class="w-32 h-32 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                            </div>
                            @endif

                            <!-- Category Badges -->
                            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                @if(is_array($coffee->category))
                                    @foreach($coffee->category as $cat)
                                        @if($cat === 'espresso')
                                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-amber-500 text-white shadow-lg">Espresso</span>
                                        @elseif($cat === 'filter')
                                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-blue-500 text-white shadow-lg">Filtr</span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            <!-- Overlay with click hint -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-0 hover:opacity-100 transition-opacity duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Coffee Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                {{ $coffee->name }}
                            </h3>

                            @if($coffee->roastery)
                            <p class="text-sm text-gray-500 font-medium mb-3 flex items-center gap-1">
                                <span class="text-lg">{{ $coffee->roastery->country_flag }}</span>
                                <a href="{{ route('roasteries.show', $coffee->roastery) }}" class="hover:text-primary-600 transition-colors">
                                    {{ $coffee->roastery->name }}
                                </a>
                            </p>
                            @endif

                            @if($coffee->short_description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                {{ $coffee->short_description }}
                            </p>
                            @endif

                            <button onclick="openCoffeeModal{{ $coffee->id }}()" 
                                    class="w-full py-3 bg-gradient-to-r from-primary-500 to-pink-600 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                Zobrazit detail
                            </button>

                            <p class="text-xs text-center text-gray-500 mt-3">
                                Tuto kávu nelze zakoupit samostatně
                            </p>
                        </div>
                    </div>

                    <!-- Modal for Coffee Detail -->
                    <div id="coffeeModal{{ $coffee->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closeCoffeeModal{{ $coffee->id }}(event)">
                        <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                            <div class="relative">
                                @if($coffee->image)
                                <img src="{{ asset($coffee->image) }}" 
                                     alt="{{ $coffee->name }}"
                                     class="w-full h-80 object-cover rounded-t-3xl">
                                @endif
                                
                                <button onclick="closeCoffeeModal{{ $coffee->id }}()" 
                                        class="absolute top-4 right-4 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <div class="absolute top-4 left-4">
                                    <span class="bg-primary-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                        Káva měsíce {{ ucfirst($monthName) }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-8">
                                <h3 class="text-3xl font-bold text-gray-900 mb-4">
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
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach($coffee->attributes as $key => $value)
                                            @if($value && !is_array($value))
                                            <div>
                                                <span class="text-sm text-gray-500 block">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                                <span class="text-base font-medium text-gray-900">{{ $value }}</span>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="bg-primary-50 border-2 border-primary-200 rounded-2xl p-6 text-center">
                                    <p class="text-lg font-semibold text-primary-900 mb-2">
                                        Tato káva je součástí aktuálního předplatného
                                    </p>
                                    <p class="text-gray-600 mb-4">
                                        Získejte ji společně s dalšími výběrovými kávami v našem měsíčním předplatném
                                    </p>
                                    <a href="{{ route('subscriptions.index') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-pink-600 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Zjistit více o předplatném
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

                <!-- CTA Section -->
                <div class="bg-gradient-to-r from-primary-600 to-pink-600 rounded-3xl shadow-2xl p-12 text-center text-white">
                    <h2 class="text-4xl font-bold mb-4">
                        Chcete kávy měsíce pravidelně?
                    </h2>
                    <p class="text-xl mb-8 text-primary-100">
                        Přihlaste se k našemu předplatnému a získejte výběrové kávy od nejlepších pražíren přímo domů
                    </p>
                    <a href="{{ route('subscriptions.index') }}" 
                       class="inline-flex items-center px-8 py-4 bg-white text-primary-600 font-bold text-lg rounded-xl hover:bg-gray-100 transition-colors shadow-xl">
                        <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Zjistit více o předplatném
                    </a>
                </div>
            </div>

        @else
            <!-- No Content Message -->
            <div class="bg-white rounded-3xl shadow-xl p-12 text-center border-2 border-gray-200">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Káva měsíce připravujeme
                </h2>
                <p class="text-xl text-gray-600 mb-8">
                    Právě vybíráme výjimečné kávy pro měsíc {{ ucfirst($monthName) }}. Brzy vás překvapíme!
                </p>
                <a href="{{ route('subscriptions.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-pink-600 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    Zjistit více o předplatném
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
