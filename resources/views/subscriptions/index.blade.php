@extends('layouts.app')

@section('title', 'Kávové předplatné - Kavi Coffee')

@section('content')
<!-- Subscription Configurator Section -->
<section class="section bg-bluegray-50">
    <div class="container-custom">
        <div class="text-center mb-16">
            <h2 class="font-display text-3xl md:text-4xl font-black text-dark-800 mb-4">
                Konfigurátor předplatného
            </h2>
            <p class="text-lg text-dark-600 max-w-2xl mx-auto">
                Přizpůsobte si předplatné přesně podle svých potřeb. Flexibilně, bez závazků.
            </p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="max-w-4xl mx-auto mb-6">
            <div class="bg-red-100 border-2 border-red-400 text-red-700 px-6 py-4">
                <p class="font-bold mb-2">⚠️ Chyba při zpracování konfigurace:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-4xl mx-auto mb-6">
            <div class="bg-red-100 border-2 border-red-400 text-red-700 px-6 py-4">
                <p class="font-bold">⚠️ {{ session('error') }}</p>
            </div>
                </div>
                @endif
                
        <!-- Configurator Card -->
        <div class="max-w-4xl mx-auto" id="subscription-configurator" data-pricing='@json($subscriptionPricing)'>
            <div class="card p-8 md:p-12">
                <!-- Progress Indicator -->
                <div class="mb-12">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-2 step-indicator active" data-step="1">
                            <div class="step-number">1</div>
                            <span class="hidden md:inline text-sm font-medium">Množství</span>
                        </div>
                        <div class="flex-1 h-1 bg-bluegray-200 mx-2 step-line"></div>
                        <div class="flex items-center space-x-2 step-indicator" data-step="2">
                            <div class="step-number">2</div>
                            <span class="hidden md:inline text-sm font-medium">Typ kávy</span>
                        </div>
                        <div class="flex-1 h-1 bg-bluegray-200 mx-2 step-line"></div>
                        <div class="flex items-center space-x-2 step-indicator" data-step="3">
                            <div class="step-number">3</div>
                            <span class="hidden md:inline text-sm font-medium">Frekvence</span>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Množství kávy -->
                <div id="step-1" class="config-step active">
                    <h3 class="font-display text-2xl md:text-3xl font-bold text-dark-800 mb-3 text-center">
                        Vyberte množství kávy
                    </h3>
                    <p class="text-dark-600 text-center mb-8">Zvolte balíček, který vám vyhovuje</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                        <!-- 500g Option -->
                        <button type="button" class="coffee-amount-option p-6 bg-white hover:bg-primary-500 hover:text-white transition-all text-center group shadow-lg hover:shadow-xl border-2 border-bluegray-200 hover:border-primary-500" data-amount="2" data-cups="500g" data-price="{{ $subscriptionPricing['2'] }}">
                            <div class="text-4xl font-black text-primary-500 group-hover:text-white mb-3">500 G</div>
                            <div class="text-2xl font-black text-dark-800 group-hover:text-white mb-4">{{ number_format($subscriptionPricing['2'], 0, ',', ' ') }} Kč měsíčně</div>
                            <div class="text-sm font-bold mb-4">250g × 2 balíčky</div>
                            <ul class="text-left space-y-2 text-sm mb-4">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-primary-500 group-hover:text-white mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>2 typy výběrové kávy z nejlepších evropských pražíren</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-primary-500 group-hover:text-white mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>KAVI přispěje na 6 měsíců čisté vody každý měsíc předplatného</span>
                                </li>
                            </ul>
                        </button>
                        
                        <!-- 750g Option - Popular -->
                        <button type="button" class="coffee-amount-option p-6 bg-white hover:bg-primary-500 hover:text-white transition-all text-center group shadow-xl border-4 border-primary-500 relative" data-amount="3" data-cups="750g" data-price="{{ $subscriptionPricing['3'] }}">
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span class="bg-primary-500 text-white text-xs font-black px-3 py-1">OBLÍBENÉ</span>
                            </div>
                            <div class="text-4xl font-black text-primary-500 group-hover:text-white mb-3">750 G</div>
                            <div class="text-2xl font-black text-dark-800 group-hover:text-white mb-4">{{ number_format($subscriptionPricing['3'], 0, ',', ' ') }} Kč měsíčně</div>
                            <div class="text-sm font-bold mb-4">250g × 3 balíčky</div>
                            <ul class="text-left space-y-2 text-sm mb-4">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-primary-500 group-hover:text-white mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>3 typy výběrové kávy z nejlepších evropských pražíren</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-primary-500 group-hover:text-white mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>KAVI přispěje na 9 měsíců čisté vody každý měsíc předplatného</span>
                                </li>
                            </ul>
                        </button>
                        
                        <!-- 1000g Option -->
                        <button type="button" class="coffee-amount-option p-6 bg-white hover:bg-primary-500 hover:text-white transition-all text-center group shadow-lg hover:shadow-xl border-2 border-bluegray-200 hover:border-primary-500" data-amount="4" data-cups="1000g" data-price="{{ $subscriptionPricing['4'] }}">
                            <div class="text-4xl font-black text-primary-500 group-hover:text-white mb-3">1000 G</div>
                            <div class="text-2xl font-black text-dark-800 group-hover:text-white mb-4">{{ number_format($subscriptionPricing['4'], 0, ',', ' ') }} Kč měsíčně</div>
                            <div class="text-sm font-bold mb-4">250g × 4 balíčky</div>
                            <ul class="text-left space-y-2 text-sm mb-4">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-primary-500 group-hover:text-white mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>4 typy výběrové kávy z nejlepších evropských pražíren</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-primary-500 group-hover:text-white mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>KAVI přispěje na 12 měsíců čisté vody každý měsíc předplatného</span>
                                </li>
                            </ul>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Typ kávy -->
                <div id="step-2" class="config-step">
                    <h3 class="font-display text-2xl md:text-3xl font-bold text-dark-800 mb-3 text-center">
                        Jaký typ kávy preferujete?
                    </h3>
                    <p class="text-dark-600 text-center mb-8">Vyberte si váš oblíbený způsob přípravy</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto mb-8">
                        <button type="button" class="coffee-type-option p-8 bg-bluegray-100 hover:bg-primary-500 hover:text-white transition-all text-center group shadow-lg hover:shadow-xl transform hover:-translate-y-1" data-type="espresso">
                            <div class="text-5xl mb-4">🎯</div>
                            <div class="font-black text-xl mb-2 uppercase tracking-wider">Espresso</div>
                            <div class="text-sm font-bold uppercase tracking-widest opacity-70">Intenzivní a plné chutě</div>
                        </button>
                        <button type="button" class="coffee-type-option p-8 bg-bluegray-100 hover:bg-primary-500 hover:text-white transition-all text-center group shadow-lg hover:shadow-xl transform hover:-translate-y-1" data-type="filter">
                            <div class="text-5xl mb-4">💧</div>
                            <div class="font-black text-xl mb-2 uppercase tracking-wider">Filter</div>
                            <div class="text-sm font-bold uppercase tracking-widest opacity-70">Jemné a čisté chutě</div>
                        </button>
                        <button type="button" class="coffee-type-option p-8 bg-bluegray-100 hover:bg-primary-500 hover:text-white transition-all text-center group shadow-lg hover:shadow-xl transform hover:-translate-y-1" data-type="mix">
                            <div class="text-5xl mb-4">🎨</div>
                            <div class="font-black text-xl mb-2 uppercase tracking-wider">Kombinace</div>
                            <div class="text-sm font-bold uppercase tracking-widest opacity-70">Mix podle vašich preferencí</div>
                        </button>
                    </div>

                    <!-- Decaf Option (shown for espresso or filter) -->
                    <div id="decaf-option" class="hidden max-w-2xl mx-auto mb-8">
                        <div class="bg-bluegray-50 p-8 shadow-lg">
                            <h4 class="font-black text-xl mb-6 text-center uppercase tracking-wider">Chcete přidat decaf variantu?</h4>
                            <p class="text-sm text-dark-600 text-center mb-8 font-bold">
                                Posouváním slideru doprava zvýšíte množství decaf kávy
                            </p>
                            
                            <div class="space-y-6">
                                <!-- Labels -->
                                <div class="flex justify-between items-center px-2">
                                    <div class="text-center">
                                        <div class="text-2xl mb-1">🌙</div>
                                        <span class="font-bold text-sm uppercase tracking-wider">Decaf</span>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl mb-1" id="single-normal-icon-top">🎯</div>
                                        <span class="font-bold text-sm uppercase tracking-wider" id="single-normal-label-top">Espresso</span>
                                    </div>
                                </div>
                                
                                <!-- Slider -->
                                <div class="relative">
                                    <input type="range" id="single-decaf-slider" min="0" max="4" value="0" class="w-full h-4 bg-gradient-to-r from-blue-200 to-primary-200 appearance-none cursor-pointer slider rounded-full">
                                </div>
                                
                                <!-- Counts Display -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-4 rounded text-center border-2 border-blue-200">
                                        <div class="text-3xl font-black text-blue-600 mb-1"><span id="single-decaf-count">0</span></div>
                                        <div class="text-xs font-bold uppercase tracking-wider text-dark-600">
                                            <span id="single-decaf-label">Decaf</span> balení
                                        </div>
                                    </div>
                                    <div class="bg-primary-50 p-4 rounded text-center border-2 border-primary-200">
                                        <div class="text-3xl font-black text-primary-600 mb-1"><span id="single-normal-count">0</span></div>
                                        <div class="text-xs font-bold uppercase tracking-wider text-dark-600">
                                            <span id="single-normal-label">Espresso</span> balení
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mix Options (shown only when "mix" is selected) -->
                    <div id="mix-options" class="hidden max-w-2xl mx-auto">
                        <div class="bg-bluegray-50 p-8 shadow-lg">
                            <h4 class="font-black text-2xl mb-6 text-center uppercase tracking-wider">Jak chcete kombinovat?</h4>
                            
                            <!-- Progress indicator -->
                            <div class="bg-white p-6 mb-8 shadow-md">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-dark-600 font-black uppercase tracking-widest text-sm">Vybráno balení:</span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-4xl font-black text-primary-500" id="total-bags">0</span>
                                        <span class="text-2xl text-dark-600 font-black">/</span>
                                        <span class="text-3xl font-black text-dark-800" id="required-bags-display">0</span>
                                    </div>
                                </div>
                                <div class="w-full bg-bluegray-200 h-4 overflow-hidden">
                                    <div id="mix-progress-bar" class="bg-gradient-to-r from-primary-400 to-primary-600 h-4 transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <div id="mix-status" class="text-center mt-4 font-black uppercase tracking-widest text-sm"></div>
                            </div>
                            
                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="font-bold flex items-center uppercase tracking-wider">
                                            <span class="text-3xl mr-3">🎯</span>
                                            <span>Espresso</span>
                                        </label>
                                        <span class="font-black text-primary-500 text-lg"><span id="espresso-count">0</span> BALENÍ</span>
                                    </div>
                                    <input type="range" id="espresso-slider" min="0" max="4" value="0" class="w-full h-3 bg-bluegray-200 appearance-none cursor-pointer slider">
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="font-bold flex items-center uppercase tracking-wider">
                                            <span class="text-3xl mr-3">🎯🌙</span>
                                            <span>Espresso Decaf</span>
                                        </label>
                                        <span class="font-black text-primary-500 text-lg"><span id="espresso-decaf-count">0</span> BALENÍ</span>
                                    </div>
                                    <input type="range" id="espresso-decaf-slider" min="0" max="4" value="0" class="w-full h-3 bg-bluegray-200 appearance-none cursor-pointer slider">
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="font-bold flex items-center uppercase tracking-wider">
                                            <span class="text-3xl mr-3">💧</span>
                                            <span>Filter</span>
                                        </label>
                                        <span class="font-black text-primary-500 text-lg"><span id="filter-count">0</span> BALENÍ</span>
                                    </div>
                                    <input type="range" id="filter-slider" min="0" max="4" value="0" class="w-full h-3 bg-bluegray-200 appearance-none cursor-pointer slider">
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="font-bold flex items-center uppercase tracking-wider">
                                            <span class="text-3xl mr-3">💧🌙</span>
                                            <span>Filter Decaf</span>
                                        </label>
                                        <span class="font-black text-primary-500 text-lg"><span id="filter-decaf-count">0</span> BALENÍ</span>
                                    </div>
                                    <input type="range" id="filter-decaf-slider" min="0" max="4" value="0" class="w-full h-3 bg-bluegray-200 appearance-none cursor-pointer slider">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" id="back-to-step-1" class="btn btn-outline !px-6 !py-3">
                            ← Zpět
                        </button>
                        <button type="button" id="next-to-step-3" class="btn btn-primary !px-6 !py-3" disabled>
                            Pokračovat →
                        </button>
                    </div>
                </div>

                <!-- Step 3: Frekvence -->
                <div id="step-3" class="config-step">
                    <h3 class="font-display text-2xl md:text-3xl font-bold text-dark-800 mb-3 text-center">
                        Jak často chcete kávu dostávat?
                    </h3>
                    <p class="text-dark-600 text-center mb-8">Vyberte interval dodávky</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto mb-8">
                        <button type="button" class="frequency-option p-8 bg-bluegray-100 hover:bg-primary-500 hover:text-white transition-all text-center group shadow-lg hover:shadow-xl transform hover:-translate-y-1" data-frequency="1" data-frequency-text="Každý měsíc">
                            <div class="text-5xl mb-4">📦</div>
                            <div class="font-black text-xl mb-2 uppercase tracking-wider">Každý měsíc</div>
                            <div class="text-sm font-bold uppercase tracking-widest opacity-70">Pro pravidelnou spotřebu</div>
                        </button>
                        <button type="button" class="frequency-option p-8 bg-bluegray-100 hover:bg-primary-500 hover:text-white transition-all text-center group shadow-lg hover:shadow-xl transform hover:-translate-y-1" data-frequency="2" data-frequency-text="Jednou za 2 měsíce">
                            <div class="text-5xl mb-4">📦📦</div>
                            <div class="font-black text-xl mb-2 uppercase tracking-wider">Jednou za 2 měsíce</div>
                            <div class="text-sm font-bold uppercase tracking-widest opacity-70">Pro střední spotřebu</div>
                        </button>
                        <button type="button" class="frequency-option p-8 bg-bluegray-100 hover:bg-primary-500 hover:text-white transition-all text-center group shadow-lg hover:shadow-xl transform hover:-translate-y-1" data-frequency="3" data-frequency-text="Jednou za 3 měsíce">
                            <div class="text-5xl mb-4">📦📦📦</div>
                            <div class="font-black text-xl mb-2 uppercase tracking-wider">Jednou za 3 měsíce</div>
                            <div class="text-sm font-bold uppercase tracking-widest opacity-70">Pro občasné pití</div>
                        </button>
                    </div>

                    <!-- Summary & Price -->
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 p-10 max-w-2xl mx-auto shadow-lg">
                        <h4 class="font-display text-3xl font-black text-center mb-8 uppercase tracking-wider">Shrnutí vašeho předplatného</h4>
                        
                        <div class="bg-white p-8 mb-6 shadow-md">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-dark-600 font-bold uppercase tracking-widest text-sm">Množství:</span>
                                    <span class="font-black text-lg" id="summary-amount">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-dark-600 font-bold uppercase tracking-widest text-sm">Typ kávy:</span>
                                    <span class="font-black text-lg" id="summary-type">-</span>
                                </div>
                                <div id="summary-mix" class="hidden pl-4 text-sm text-dark-600">
                                    <div id="summary-mix-details"></div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-dark-600 font-bold uppercase tracking-widest text-sm">Frekvence:</span>
                                    <span class="font-black text-lg" id="summary-frequency">-</span>
                                </div>
                                <div class="border-t-4 border-primary-200 pt-4 mt-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-black uppercase tracking-widest">Cena:</span>
                                        <span class="text-4xl font-black text-primary-500" id="summary-price">-</span>
                                    </div>
                                    <p class="text-xs text-dark-600 text-right mt-2 font-bold uppercase tracking-widest">při každé dodávce</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="button" id="back-to-step-2" class="btn btn-outline !px-6 !py-3 flex-1">
                                ← Zpět
                            </button>
                            <button type="button" id="configure-subscription" class="btn btn-primary !px-6 !py-3 flex-1 opacity-50 cursor-not-allowed" disabled>
                                Pokračovat k objednávce →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-12 text-center">
            <p class="text-dark-600 mb-4">Máte otázky? <a href="#" class="text-primary-500 hover:text-primary-600 font-semibold">Kontaktujte nás</a></p>
            <div class="flex flex-wrap justify-center items-center gap-4 md:gap-8 text-sm text-dark-600">
                <span class="flex items-center">✓ Bez závazků</span>
                <span class="flex items-center">✓ Kdykoli zrušte</span>
                <span class="flex items-center">✓ Doprava zdarma</span>
            </div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="section section-gray">
    <div class="container-custom">
        <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 text-center mb-16">Jak to funguje?</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 max-w-6xl mx-auto">
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 text-white flex items-center justify-center mx-auto mb-6 text-4xl font-black shadow-xl">
                    1
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Vyberte balíček</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Zvolte předplatné, které vám nejvíce vyhovuje podle vašich preferencí</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 text-white flex items-center justify-center mx-auto mb-6 text-4xl font-black shadow-xl">
                    2
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Pražíme na míru</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Kávu pražíme čerstvě podle vašeho výběru a preferencí</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 text-white flex items-center justify-center mx-auto mb-6 text-4xl font-black shadow-xl">
                    3
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Doručíme domů</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Každý měsíc dostanete čerstvou kávu zdarma až domů</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 text-white flex items-center justify-center mx-auto mb-6 text-4xl font-black shadow-xl">
                    4
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Vychutnáte si</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Užijte si tu nejlepší kávu, kterou jste kdy měli</p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="section bg-white">
    <div class="container-custom">
        <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 text-center mb-16">Proč předplatné?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-100 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Výhodná cena</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Ušetřete až 20% oproti jednorázovým nákupům a získejte dopravu zdarma</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-100 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Bez starosti</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Nikdy vám nedojde káva díky pravidelným měsíčním dodávkám</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-100 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Flexibilní</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Změňte, pozastavte nebo zrušte kdykoli bez jakýchkoliv poplatků</p>
            </div>
        </div>
    </div>
</section>

<!-- Visual Section with Placeholder -->
<section class="section section-gray">
    <div class="container-custom">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 mb-6">
                    Kvalita, které můžete věřit
                </h2>
                <p class="text-xl text-dark-600 mb-8 leading-relaxed">
                    Spolupracujeme s nejlepšími pražírnami v České republice. Každá káva je pečlivě vybrána 
                    a čerstvě pražena, aby vám přinesla ten nejlepší zážitek.
                </p>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-dark-700"><strong>100% Arabica</strong> z etických zdrojů</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-dark-700"><strong>Čerstvě pražená</strong> maximálně 7 dní před expedicí</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-dark-700"><strong>Ekologické balení</strong> s možností recyklace</span>
                    </li>
                </ul>
            </div>
            <div class="aspect-square overflow-hidden shadow-2xl img-placeholder">
                <div class="w-full h-full flex flex-col items-center justify-center p-12">
                    <svg class="w-32 h-32 text-bluegray-300 mb-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                    <p class="text-center leading-relaxed font-bold uppercase tracking-widest text-sm">Lifestyle fotografie: Osoba vychutnává si kávu z předplatného v útulném prostředí</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section bg-gradient-to-br from-primary-500 to-primary-700 text-white">
    <div class="container-custom text-center">
        <h2 class="font-display text-4xl md:text-5xl font-bold mb-6">
            Začněte svou kávovou cestu ještě dnes
        </h2>
        <p class="text-xl mb-10 max-w-2xl mx-auto opacity-95">
            Připojte se k tisícům spokojených zákazníků a objevte svět prémiové kávy
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#plans" class="btn btn-white text-lg">
                Vybrat předplatné
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary text-lg">
                Procházet kávy
            </a>
        </div>
    </div>
</section>
@endsection
