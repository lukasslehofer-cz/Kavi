@extends('layouts.app')

@section('title', 'Stránka nenalezena | KAVI.cz')

@section('content')
<div class="relative min-h-[70vh] flex items-center justify-center overflow-hidden bg-white">
    <!-- Organic shape decorations -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary-100 rounded-full translate-x-1/2 -translate-y-1/2 opacity-60"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-gray-100 rounded-full -translate-x-1/2 translate-y-1/2"></div>

    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8 py-16 sm:py-20">
        <div class="mx-auto max-w-2xl text-center">
            <!-- Decorative Badge -->
            <div class="inline-flex items-center gap-2 bg-primary-50 rounded-full px-4 py-2 mb-8">
                <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-primary-600">Chyba 404</span>
            </div>

            <!-- Large Icon -->
            <div class="relative mb-8">
                <svg class="w-32 h-32 sm:w-40 sm:h-40 mx-auto text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Heading -->
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight tracking-tight">
                Tuto stránku jsme nenašli
            </h2>

            <!-- Description -->
            <p class="text-lg sm:text-xl text-gray-600 leading-relaxed font-light mb-10 max-w-xl mx-auto">
                Stránka, kterou hledáte, neexistuje nebo byla přesunuta. Možná jste se překlépli v adrese, nebo odkaz už není platný.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <a href="{{ route('home') }}" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium text-lg px-8 py-4 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Zpět na úvodní stránku</span>
                </a>
                
                <a href="{{ route('subscriptions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium text-lg px-8 py-4 rounded-full border border-gray-200 transition-all duration-200">
                    <span>Kávové boxy</span>
                    <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <!-- Quick Links -->
            <div class="pt-8 border-t border-gray-100">
                <p class="text-sm text-gray-500 font-light mb-4">Možná hledáte:</p>
                <div class="flex flex-wrap gap-3 justify-center">
                    <a href="{{ route('products.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Obchod
                    </a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('roasteries.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Pražírny
                    </a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('monthly-feature.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Káva měsíce
                    </a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('how-it-works') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Jak to funguje
                    </a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('contact') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Kontakt
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

