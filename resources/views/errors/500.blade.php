@extends('layouts.app')

@section('title', 'Něco se pokazilo | KAVI.cz')

@section('content')
<div class="relative min-h-[70vh] flex items-center justify-center overflow-hidden bg-white">
    <!-- Organic shape decorations -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-red-100 rounded-full translate-x-1/2 -translate-y-1/2 opacity-60"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-gray-100 rounded-full -translate-x-1/2 translate-y-1/2"></div>

    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8 py-16 sm:py-20">
        <div class="mx-auto max-w-2xl text-center">
            <!-- Decorative Badge -->
            <div class="inline-flex items-center gap-2 bg-red-50 rounded-full px-4 py-2 mb-8">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="text-sm font-medium text-red-600">Chyba 500</span>
            </div>

            <!-- Large Icon -->
            <div class="relative mb-8">
                <div class="inline-flex items-center justify-center w-40 h-40 sm:w-48 sm:h-48 rounded-full bg-gradient-to-br from-red-50 to-red-100">
                    <svg class="w-24 h-24 sm:w-32 sm:h-32 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Heading -->
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight tracking-tight">
                Ups, něco se pokazilo
            </h2>

            <!-- Description -->
            <p class="text-lg sm:text-xl text-gray-600 leading-relaxed font-light mb-10 max-w-xl mx-auto">
                Omlouváme se, ale na serveru došlo k chybě. Náš tým už na tom pracuje. Zkuste to prosím za chvíli znovu.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <button onclick="window.location.reload()" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium text-lg px-8 py-4 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Zkusit znovu</span>
                </button>
                
                <a href="{{ route('home') }}" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium text-lg px-8 py-4 rounded-full border border-gray-200 transition-all duration-200">
                    <span>Zpět na úvodní stránku</span>
                </a>
            </div>

            <!-- Contact Support -->
            <div class="pt-8 border-t border-gray-100">
                <p class="text-sm text-gray-600 font-light mb-4">
                    Pokud problém přetrvává, kontaktujte nás:
                </p>
                <a href="mailto:info@kavi.cz" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>info@kavi.cz</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

