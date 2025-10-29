<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kavi Coffee - Prémiová káva s předplatným')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">
    <!-- Top Announcement Banner - Dark Minimal -->
    <div class="bg-gray-900">
        <div class="flex items-center justify-center gap-2 px-4 py-2.5">
            <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <div class="text-sm text-white font-light">
                Doprava zdarma nad 1000 Kč
            </div>
        </div>
    </div>
    <!-- Banner - end -->

    <!-- Compact Transparent Navigation -->
    <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-lg border-b border-gray-200/50">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group" aria-label="logo">
                    <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-10 w-auto transform group-hover:scale-105 transition-transform duration-200">
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-2">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50 px-4 py-2 rounded-full transition-all duration-200">
                        Domů
                    </a>
                    <a href="{{ route('subscriptions.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50 px-4 py-2 rounded-full transition-all duration-200">
                        Předplatné
                    </a>
                    <a href="{{ route('monthly-feature.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50 px-4 py-2 rounded-full transition-all duration-200">
                        Káva měsíce
                    </a>
                    <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50 px-4 py-2 rounded-full transition-all duration-200">
                        Obchod
                    </a>
                    <a href="{{ route('roasteries.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50 px-4 py-2 rounded-full transition-all duration-200">
                        Naše pražírny
                    </a>
                </nav>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-3">

                    <!-- User Account -->
                    @auth
                    <a href="{{ route('dashboard.index') }}" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors duration-200" title="Můj účet">
                        <svg class="w-4 h-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="hidden md:inline-flex items-center gap-1.5 text-sm font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50 px-4 py-2 rounded-full transition-all duration-200">
                        <span>Přihlásit</span>
                    </a>
                    @endauth

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 hover:bg-primary-50 transition-colors duration-200 group" title="Košík">
                        <svg class="w-4 h-4 text-gray-700 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 bg-primary-500 text-white text-xs w-4 h-4 flex items-center justify-center font-bold rounded-full">
                            {{ array_sum(session('cart')) }}
                        </span>
                        @endif
                    </a>
                    
                    <!-- CTA Button - Desktop -->
                    <a href="{{ route('subscriptions.index') }}" class="hidden lg:inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium text-sm px-5 py-2 rounded-full transition-all duration-200">
                        <span>Vybrat předplatné</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- Mobile Menu Button -->
                    <button type="button" id="mobile-menu-button" class="lg:hidden flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                        <svg class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="lg:hidden absolute top-full left-0 right-0 bg-white/90 backdrop-blur-lg border-t border-gray-200/50 shadow-lg" style="display: none;">
            <div class="px-6 py-6 space-y-2">
                <!-- Navigation Links -->
                <a href="{{ route('home') }}" class="block text-gray-900 hover:text-primary-600 hover:bg-gray-50 font-medium py-3 px-4 rounded-full transition-all duration-200">
                    Domů
                </a>
                <a href="{{ route('subscriptions.index') }}" class="block text-gray-900 hover:text-primary-600 hover:bg-gray-50 font-medium py-3 px-4 rounded-full transition-all duration-200">
                    Předplatné
                </a>
                <a href="{{ route('monthly-feature.index') }}" class="block text-gray-900 hover:text-primary-600 hover:bg-gray-50 font-medium py-3 px-4 rounded-full transition-all duration-200">
                    Káva měsíce
                </a>
                <a href="{{ route('products.index') }}" class="block text-gray-900 hover:text-primary-600 hover:bg-gray-50 font-medium py-3 px-4 rounded-full transition-all duration-200">
                    Obchod
                </a>
                <a href="{{ route('roasteries.index') }}" class="block text-gray-900 hover:text-primary-600 hover:bg-gray-50 font-medium py-3 px-4 rounded-full transition-all duration-200">
                    Naše pražírny
                </a>
                <a href="{{ route('how-it-works') }}" class="block text-gray-900 hover:text-primary-600 hover:bg-gray-50 font-medium py-3 px-4 rounded-full transition-all duration-200">
                    Jak to funguje
                </a>
                
                <!-- Mobile CTA Button -->
                <div class="pt-4">
                    <a href="{{ route('subscriptions.index') }}" class="flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                        <span>Vybrat předplatné</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                
                <!-- Account Section -->
                <div class="pt-4 mt-4 border-t border-gray-100 space-y-2">
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 text-gray-900 hover:text-primary-600 hover:bg-gray-50 font-medium py-3 px-4 rounded-full transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Můj účet</span>
                        </a>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-gray-900 hover:text-primary-600 hover:bg-gray-50 font-medium py-3 px-4 rounded-full transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Admin</span>
                        </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 text-red-600 hover:text-red-700 hover:bg-red-50 font-medium py-3 px-4 rounded-full transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Odhlásit se</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-3 text-gray-900 hover:text-primary-600 hover:bg-gray-50 font-medium py-3 px-4 rounded-full transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Přihlásit se</span>
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 font-medium transition-all text-center rounded-full">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>Registrovat</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-4 mt-4 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-4 mt-4 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Minimal Footer -->
    <footer class="relative bg-white border-t border-gray-100">

      <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-10 pt-12 pb-10">
          <!-- Brand Section -->
          <div class="lg:col-span-2">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-5 group" aria-label="logo">
              <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-9 w-auto transform group-hover:scale-105 transition-transform duration-200">
            </a>
            
            <p class="text-gray-600 mb-5 leading-relaxed max-w-sm text-sm font-light">
              Prémiová káva s měsíčními dodávkami přímo k vám domů. Objevte svět chutí z nejlepších pražíren Evropy.
            </p>

            <!-- Social Links -->
            <div class="flex gap-2">
              <a href="#" target="_blank" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-900 text-gray-600 hover:text-white transition-all duration-200">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
              </a>
              <a href="#" target="_blank" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-900 text-gray-600 hover:text-white transition-all duration-200">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
              </a>
              <a href="#" target="_blank" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-900 text-gray-600 hover:text-white transition-all duration-200">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
              </a>
            </div>
          </div>

          <!-- Předplatné -->
          <div>
            <h3 class="text-gray-900 font-semibold text-sm mb-4">Předplatné</h3>
            <nav class="space-y-2.5">
              <a href="{{ route('subscriptions.index') }}" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">Konfigurátor</a>
              <a href="{{ route('subscriptions.index') }}" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">Espresso BOX</a>
              <a href="{{ route('subscriptions.index') }}" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">Filter BOX</a>
              <a href="{{ route('subscriptions.index') }}" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">Dárkové</a>
            </nav>
          </div>

          <!-- Obchod -->
          <div>
            <h3 class="text-gray-900 font-semibold text-sm mb-4">Obchod</h3>
            <nav class="space-y-2.5">
              <a href="{{ route('products.index') }}" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">Všechny produkty</a>
              <a href="{{ route('products.index', ['category' => 'coffee']) }}" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">Káva</a>
              <a href="{{ route('products.index', ['category' => 'equipment']) }}" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">Vybavení</a>
              <a href="{{ route('products.index', ['category' => 'merch']) }}" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">Merch</a>
            </nav>
          </div>

          <!-- Informace -->
          <div>
            <h3 class="text-gray-900 font-semibold text-sm mb-4">Informace</h3>
            <nav class="space-y-2.5">
              <a href="{{ route('how-it-works') }}" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">Jak to funguje</a>
              <a href="#" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">O nás</a>
              <a href="{{ route('how-it-works') }}" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">FAQ</a>
              <a href="#" class="block text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">Blog</a>
            </nav>
          </div>

          <!-- Kontakt -->
          <div>
            <h3 class="text-gray-900 font-semibold text-sm mb-4">Kontakt</h3>
            <nav class="space-y-3">
              <a href="mailto:info@kavi.cz" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>info@kavi.cz</span>
              </a>
              <a href="tel:+420123456789" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors duration-200 text-sm font-light">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>+420 123 456 789</span>
              </a>
              <div class="flex items-center gap-2 text-gray-600 text-sm font-light">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Po-Pá: 9:00-17:00</span>
              </div>
            </nav>
          </div>
        </div>

        <!-- Newsletter Section -->
        <div class="border-t border-gray-100 py-10">
          <div class="max-w-xl mx-auto text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Přihlaste se k odběru novinek</h3>
            <p class="text-gray-600 mb-6 text-sm font-light">Získejte slevu 10% na první objednávku a buďte první, kdo se dozví o nových kávách</p>
            <form id="newsletter-form" class="flex flex-col sm:flex-row gap-2 max-w-md mx-auto">
              @csrf
              <input 
                type="email" 
                name="email" 
                id="newsletter-email"
                placeholder="Váš e-mail" 
                required
                class="flex-1 px-5 py-2.5 rounded-full bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-gray-300 focus:ring-1 focus:ring-gray-300 transition-all text-sm"
              >
              <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-full transition-all duration-200 whitespace-nowrap text-sm">
                Odebírat
              </button>
            </form>
            <div id="newsletter-message" class="mt-4 text-sm hidden"></div>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-100 py-6">
          <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500 font-light">
            <p>© {{ date('Y') }} Kavi Coffee. Všechna práva vyhrazena.</p>
            <div class="flex gap-6">
              <a href="{{ route('terms-of-service') }}" class="hover:text-gray-900 transition-colors duration-200">Obchodní podmínky</a>
              <a href="{{ route('privacy-policy') }}" class="hover:text-gray-900 transition-colors duration-200">Ochrana osobních údajů</a>              
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Toggle display style
                    if (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') {
                        mobileMenu.style.display = 'block';
                    } else {
                        mobileMenu.style.display = 'none';
                    }
                });
            }

            // Newsletter form
            const newsletterForm = document.getElementById('newsletter-form');
            const newsletterMessage = document.getElementById('newsletter-message');
            const newsletterEmail = document.getElementById('newsletter-email');

            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(newsletterForm);
                    const submitButton = newsletterForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.textContent;

                    // Disable button and show loading state
                    submitButton.disabled = true;
                    submitButton.textContent = 'Odesílám...';

                    fetch('{{ route("newsletter.subscribe") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            email: formData.get('email')
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        newsletterMessage.classList.remove('hidden');
                        
                        if (data.success) {
                            newsletterMessage.className = 'mt-4 text-sm p-3 rounded-lg bg-green-100 text-green-800 border border-green-200';
                            newsletterMessage.textContent = data.message;
                            newsletterEmail.value = '';
                        } else {
                            newsletterMessage.className = 'mt-4 text-sm p-3 rounded-lg bg-red-100 text-red-800 border border-red-200';
                            newsletterMessage.textContent = data.message;
                        }

                        // Hide message after 5 seconds
                        setTimeout(() => {
                            newsletterMessage.classList.add('hidden');
                        }, 5000);
                    })
                    .catch(error => {
                        newsletterMessage.classList.remove('hidden');
                        newsletterMessage.className = 'mt-4 text-sm p-3 rounded-lg bg-red-100 text-red-800 border border-red-200';
                        newsletterMessage.textContent = 'Došlo k chybě. Zkuste to prosím znovu.';
                    })
                    .finally(() => {
                        // Re-enable button
                        submitButton.disabled = false;
                        submitButton.textContent = originalButtonText;
                    });
                });
            }
        });
    </script>
</body>
</html>


