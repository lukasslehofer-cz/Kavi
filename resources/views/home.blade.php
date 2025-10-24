@extends('layouts.app')

@section('title', 'Kavi Coffee - Prémiová káva s předplatným')

@section('content')

<!-- Hero Section - KaffeBox Inspired -->
<section class="relative overflow-hidden bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[600px] py-12 lg:py-0">
            <!-- Hero Content -->
            <div class="order-2 lg:order-1">
                <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-black text-dark-800 mb-6 leading-tight">
                    Čerstvě pražená káva<br/>
                    <span class="text-primary-500">doručená přímo<br/>k vám</span>
                </h1>
                <div class="space-y-4 mb-8">
                    <p class="text-lg text-dark-600 flex items-center">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Objevte novou prémiovou kávu každý měsíc
                    </p>
                    <p class="text-xl text-dark-600 flex items-center">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Praženo maximálně 7 dní před expedicí
                    </p>
                    <p class="text-xl text-dark-600 flex items-center">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Bez závazků, zrušte kdykoli
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-primary text-lg">
                        Začít předplatné
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline text-lg">
                        Prozkoumat obchod
                    </a>
                </div>
            </div>
            
            <!-- Hero Image -->
            <div class="order-1 lg:order-2">
                <div class="aspect-square img-placeholder rounded-lg overflow-hidden shadow-2xl">
                    <div class="w-full h-full bg-gradient-to-br from-bluegray-200 to-bluegray-300 flex items-center justify-center p-12">
                        <div class="text-center">
                            <svg class="w-32 h-32 text-dark-800 mx-auto mb-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                            </svg>
                            <p class="font-bold text-dark-800">Balení Kavi kávy<br/>s kávovými zrny</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<section class="bg-primary-500 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
            <div>
                <p class="text-5xl font-black mb-2">1900+</p>
                <p class="text-sm font-bold uppercase tracking-widest">Zákazníků</p>
            </div>
            <div>
                <p class="text-5xl font-black mb-2">100%</p>
                <p class="text-sm font-bold uppercase tracking-widest">Kvalita</p>
            </div>
            <div>
                <p class="text-5xl font-black mb-2">24/7</p>
                <p class="text-sm font-bold uppercase tracking-widest">Podpora</p>
            </div>
            <div>
                <p class="text-5xl font-black mb-2">0 Kč</p>
                <p class="text-sm font-bold uppercase tracking-widest">Doprava</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section - Clean Blocks -->
<section class="section bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 flex items-center justify-center mx-auto mb-8 shadow-xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-3xl font-black mb-4 uppercase tracking-wide">Čerstvost</h3>
                <p class="text-dark-600 font-bold text-lg">Praženo na objednávku, maxim álně 7 dní před expedicí</p>
            </div>
            
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 flex items-center justify-center mx-auto mb-8 shadow-xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <h3 class="font-display text-3xl font-black mb-4 uppercase tracking-wide">Doprava</h3>
                <p class="text-dark-600 font-bold text-lg">Zdarma při objednávce nad 1000 Kč přímo do vašich dveří</p>
            </div>
            
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 flex items-center justify-center mx-auto mb-8 shadow-xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-3xl font-black mb-4 uppercase tracking-wide">Kvalita</h3>
                <p class="text-dark-600 font-bold text-lg">100% Arabica z ověřených plantáží po celém světě</p>
            </div>
        </div>
    </div>
</section>

<!-- Photo Section with Split -->
<section class="py-0">
    <div class="grid grid-cols-1 lg:grid-cols-2">
        <!-- Photo -->
        <div class="h-[500px] lg:h-[700px] img-placeholder">
            <div class="w-full h-full bg-gradient-to-br from-bluegray-300 to-bluegray-400 flex items-center justify-center p-12">
                <div class="text-center">
                    <svg class="w-32 h-32 text-dark-800 mx-auto mb-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4z"/>
                    </svg>
                    <p class="font-black uppercase text-2xl text-dark-800 tracking-widest">FOTO:<br/>Detail balení kávy<br/>s kávovými zrny</p>
                </div>
            </div>
        </div>
        <!-- Content -->
        <div class="bg-bluegray-50 p-12 lg:p-20 flex items-center">
            <div>
                <h2 class="font-display text-5xl md:text-6xl font-black uppercase text-dark-800 mb-8 leading-tight">
                    Káva,<br/>kterou<br/>budete<br/>milovat
                </h2>
                <p class="text-xl text-dark-700 font-bold mb-8 leading-relaxed">
                    Pečlivě vybíráme nejkvalitnější kávu z malých pražíren. Každý měsíc nové chutě přímo k vám domů.
                </p>
                <a href="{{ route('subscriptions.index') }}" class="btn btn-primary text-lg">Zjistit více →</a>
            </div>
        </div>
    </div>
</section>

<!-- Subscription Plans Section -->
<section class="section bg-bluegray-50" id="subscriptions">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-display text-3xl md:text-4xl font-black text-dark-800 mb-4">
                Kávové předplatné
            </h2>
            <p class="text-lg text-dark-600 max-w-2xl mx-auto">
                Vyberte si množství, které vám vyhovuje. Kdykoli můžete změnit, pozastavit nebo zrušit.
            </p>
        </div>

        <!-- Subscription Tiers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 max-w-5xl mx-auto">
            <!-- 500g Plan -->
            <a href="{{ route('subscriptions.index') }}" class="bg-white hover:bg-bluegray-50 transition-all text-center group shadow-lg hover:shadow-xl border-2 border-bluegray-200 hover:border-primary-500 p-6">
                <div class="text-4xl font-black text-primary-500 group-hover:scale-110 transition-transform mb-3">500 G</div>
                <div class="text-2xl font-black text-dark-800 mb-4">{{ number_format($subscriptionPricing['2'], 0, ',', ' ') }} Kč měsíčně</div>
                <div class="text-sm font-bold mb-4">250g × 2 balíčky</div>
                <ul class="text-left space-y-2 text-sm mb-4">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>2 typy výběrové kávy z nejlepších evropských pražíren</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>KAVI přispěje na 6 měsíců čisté vody</span>
                    </li>
                </ul>
            </a>
            
            <!-- 750g Plan - Popular -->
            <a href="{{ route('subscriptions.index') }}" class="bg-white hover:bg-primary-50 transition-all text-center group shadow-xl border-4 border-primary-500 relative p-6">
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-primary-500 text-white text-xs font-black px-3 py-1">OBLÍBENÉ</span>
                </div>
                <div class="text-4xl font-black text-primary-500 group-hover:scale-110 transition-transform mb-3">750 G</div>
                <div class="text-2xl font-black text-dark-800 mb-4">{{ number_format($subscriptionPricing['3'], 0, ',', ' ') }} Kč měsíčně</div>
                <div class="text-sm font-bold mb-4">250g × 3 balíčky</div>
                <ul class="text-left space-y-2 text-sm mb-4">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>3 typy výběrové kávy z nejlepších evropských pražíren</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>KAVI přispěje na 9 měsíců čisté vody</span>
                    </li>
                </ul>
            </a>
            
            <!-- 1000g Plan -->
            <a href="{{ route('subscriptions.index') }}" class="bg-white hover:bg-bluegray-50 transition-all text-center group shadow-lg hover:shadow-xl border-2 border-bluegray-200 hover:border-primary-500 p-6">
                <div class="text-4xl font-black text-primary-500 group-hover:scale-110 transition-transform mb-3">1000 G</div>
                <div class="text-2xl font-black text-dark-800 mb-4">{{ number_format($subscriptionPricing['4'], 0, ',', ' ') }} Kč měsíčně</div>
                <div class="text-sm font-bold mb-4">250g × 4 balíčky</div>
                <ul class="text-left space-y-2 text-sm mb-4">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>4 typy výběrové kávy z nejlepších evropských pražíren</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>KAVI přispěje na 12 měsíců čisté vody</span>
                    </li>
                </ul>
            </a>
        </div>

        <div class="text-center">
            <p class="text-dark-600 mb-4">Chcete více možností? Přizpůsobte si předplatné přesně na míru</p>
            <a href="{{ route('subscriptions.index') }}" class="text-primary-500 hover:text-primary-600 font-bold inline-flex items-center">
                Konfigurátor předplatného
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-display text-3xl md:text-4xl font-black text-dark-800 mb-4">
                Co říkají naši zákazníci
            </h2>
            <p class="text-lg text-dark-600">1900+ spokojených milovníků kávy</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-bluegray-50 p-8 rounded-lg">
                <div class="flex items-center mb-4">
                    <div class="flex text-primary-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-dark-700 mb-6 italic leading-relaxed">
                    "Nejlepší káva, kterou jsem kdy měla! Pravidelné dodávky znamenají, že nikdy nedojdu a kvalita je vždy konzistentní."
                </p>
                <div>
                    <p class="font-bold text-dark-800">Jana Nováková</p>
                    <p class="text-sm text-dark-600">Zákaznice 2+ let</p>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-bluegray-50 p-8 rounded-lg">
                <div class="flex items-center mb-4">
                    <div class="flex text-primary-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-dark-700 mb-6 italic leading-relaxed">
                    "Skvělý servis a prvotřídní káva. Flexibilita předplatného je skvělá - můžu kdykoli změnit množství nebo typ kávy."
                </p>
                <div>
                    <p class="font-bold text-dark-800">Petr Dvořák</p>
                    <p class="text-sm text-dark-600">Zákazník 1+ rok</p>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-bluegray-50 p-8 rounded-lg">
                <div class="flex items-center mb-4">
                    <div class="flex text-primary-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-dark-700 mb-6 italic leading-relaxed">
                    "Konečně káva, která chutn  á jako z kavárny! Čerstvost je znát a výběr různých druhů je skvělý."
                </p>
                <div>
                    <p class="font-bold text-dark-800">Marie Horáková</p>
                    <p class="text-sm text-dark-600">Zákaznice 6+ měsíců</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Large Photo Section -->
<section class="py-0">
    <div class="h-[600px] lg:h-[800px] relative img-placeholder">
        <div class="absolute inset-0 bg-gradient-to-r from-dark-900 via-dark-800 to-dark-900 flex items-center justify-center">
            <div class="text-center p-12">
                <svg class="w-40 h-40 text-bluegray-700 mx-auto mb-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                </svg>
                <p class="text-bluegray-500 text-3xl font-black uppercase tracking-widest">VELKOPLOŠNÁ FOTO:<br/>Lifestyle záběr s kávou<br/>(člověk s kávou, kavárna, interiér)</p>
            </div>
        </div>
        <div class="absolute inset-0 flex items-center justify-center text-white text-center">
            <div class="max-w-4xl px-4">
                <h2 class="font-display text-5xl md:text-7xl font-black uppercase mb-8 leading-tight">
                    Objevte svět<br/>prémiové kávy
                </h2>
                <a href="{{ route('products.index') }}" class="btn btn-white text-lg shadow-2xl">Prozkoumat kávy →</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="section bg-bluegray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-20">
            <div class="inline-block bg-primary-500 px-8 py-4 shadow-xl mb-8">
                <span class="font-black uppercase tracking-widest text-white">Naše kávy</span>
            </div>
            <h2 class="font-display text-6xl md:text-7xl font-black uppercase text-dark-800 leading-none">
                Premium<br/>selection
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
            @foreach($featuredProducts as $product)
            <a href="{{ route('products.show', $product) }}" class="card card-hover group">
                <div class="aspect-square overflow-hidden img-placeholder">
                    @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-bluegray-200 to-bluegray-300 flex items-center justify-center p-12">
                        <div class="text-center">
                            <svg class="w-24 h-24 text-dark-800 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4z"/>
                            </svg>
                            <p class="font-black uppercase text-sm text-dark-800">FOTO:<br/>{{ $product->name }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="p-8">
                    <div class="mb-3">
                        <span class="badge badge-primary text-xs">{{ $product->category ?? 'KÁVA' }}</span>
                    </div>
                    <h3 class="font-display text-2xl font-black uppercase mb-3 group-hover:text-primary-500 transition-colors">
                        {{ $product->name }}
                    </h3>
                    @if($product->short_description)
                    <p class="font-bold text-sm mb-6 line-clamp-2 text-dark-600">{{ $product->short_description }}</p>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-4xl font-black text-dark-800">{{ number_format($product->price, 0, ',', ' ') }}</span>
                        <span class="badge badge-success text-xs">SKLADEM</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-16">
            <a href="{{ route('products.index') }}" class="btn btn-secondary text-lg">Všechny produkty →</a>
        </div>
    </div>
</section>
@endif

<!-- Impact Section -->
<section class="section bg-primary-500 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="font-display text-3xl md:text-4xl font-black mb-6">
                    Káva s dopadem
                </h2>
                <p class="text-lg mb-8 opacity-95 leading-relaxed">
                    Každé balení kávy z Kavi podporuje udržitelný rozvoj kávových komunit. Spolupracujeme pouze s pražírnami, které nakupují přímo od farmářů za férové ceny a podporují lokální komunity.
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <div class="text-5xl font-black mb-2">100%</div>
                        <p class="text-sm opacity-90">Fair Trade káva</p>
                    </div>
                    <div>
                        <div class="text-5xl font-black mb-2">50+</div>
                        <p class="text-sm opacity-90">Podpořených komunit</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 p-12 rounded-lg backdrop-blur-sm">
                <div class="text-center">
                    <div class="text-7xl font-black mb-4">1900+</div>
                    <p class="text-2xl font-bold mb-4">spokojených zákazníků</p>
                    <p class="opacity-90">
                        Připojte se k naší komunitě milovníků prémiové kávy a podporujte udržitelné pěstování kávy po celém světě.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section bg-bluegray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-3xl md:text-4xl font-black text-dark-800 mb-6">
            Začněte svou kávovou cestu ještě dnes
        </h2>
        <p class="text-lg text-dark-600 mb-10">
            Připojte se k tisícům spokojených zákazníků • Bez závazků
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('subscriptions.index') }}" class="btn btn-primary text-lg">
                Vybrat předplatné
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline text-lg">
                Procházet kávy
            </a>
        </div>
    </div>
</section>

@endsection
