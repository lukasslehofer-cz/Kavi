@extends('layouts.app')

@section('title', 'Platnost stránky vypršela | KAVI.cz')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-primary-600">Chyba 419</span>
            </div>

            <!-- Large Clock Icon -->
            <div class="relative mb-8">
                <div class="inline-flex items-center justify-center w-40 h-40 sm:w-48 sm:h-48 rounded-full bg-gradient-to-br from-primary-50 to-primary-100">
                    <svg class="w-24 h-24 sm:w-32 sm:h-32 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Heading -->
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight tracking-tight">
                Platnost stránky vypršela
            </h2>

            <!-- Description -->
            <p class="text-lg sm:text-xl text-gray-600 leading-relaxed font-light mb-10 max-w-xl mx-auto">
                Vaše relace vypršela z bezpečnostních důvodů. Obnovte prosím stránku a zkuste to znovu.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <button onclick="window.location.reload()" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium text-lg px-8 py-4 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Obnovit stránku</span>
                </button>
                
                <a href="{{ route('home') }}" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium text-lg px-8 py-4 rounded-full border border-gray-200 transition-all duration-200">
                    <span>Zpět na úvodní stránku</span>
                </a>
            </div>

            <!-- Help Text -->
            <div class="pt-8 border-t border-gray-100">
                <p class="text-sm text-gray-600 font-light">
                    K této chybě dochází, když stránka zůstane otevřená příliš dlouho. Stačí stránku obnovit.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

