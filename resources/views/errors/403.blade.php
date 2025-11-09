@extends('layouts.app')

@section('title', 'Přístup odepřen | KAVI.cz')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span class="text-sm font-medium text-red-600">Chyba 403</span>
            </div>

            <!-- Large Icon -->
            <div class="relative mb-8">
                <div class="inline-flex items-center justify-center w-40 h-40 sm:w-48 sm:h-48 rounded-full bg-gradient-to-br from-red-50 to-red-100">
                    <svg class="w-24 h-24 sm:w-32 sm:h-32 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <!-- Heading -->
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight tracking-tight">
                Přístup odepřen
            </h2>

            <!-- Description -->
            <p class="text-lg sm:text-xl text-gray-600 leading-relaxed font-light mb-10 max-w-xl mx-auto">
                K této stránce nemáte oprávnění. Pokud si myslíte, že by jste měli mít přístup, přihlaste se nebo kontaktujte podporu.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                @guest
                <a href="{{ route('login') }}" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium text-lg px-8 py-4 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Přihlásit se</span>
                </a>
                @endguest
                
                <a href="{{ route('home') }}" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium text-lg px-8 py-4 rounded-full border border-gray-200 transition-all duration-200">
                    <span>Zpět na úvodní stránku</span>
                </a>
            </div>

            <!-- Contact Support -->
            <div class="pt-8 border-t border-gray-100">
                <p class="text-sm text-gray-600 font-light mb-4">
                    Myslíte si, že by jste měli mít přístup?
                </p>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span>Kontaktovat podporu</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

