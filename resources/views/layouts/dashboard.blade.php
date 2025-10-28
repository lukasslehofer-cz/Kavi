<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Kavi Coffee')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-bluegray-50">
    <!-- Top Announcement Banner -->
    <div class="bg-white">
        <div class="flex flex-wrap bg-dark-800 px-4 py-2 sm:flex-nowrap sm:items-center sm:justify-center sm:gap-3 md:px-8">
            <div class="order-1 mb-2 inline-block w-full max-w-screen-sm text-sm text-white sm:order-none sm:mb-0 sm:w-auto md:text-base">
                🎉 Doprava zdarma nad 1000 Kč
            </div>
        </div>
    </div>

    <!-- Modern Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group" aria-label="logo">
                    <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-10 w-auto transform group-hover:scale-105 transition-transform duration-200">
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-base font-semibold text-gray-700 hover:text-primary-600 transition-colors duration-200 relative group">
                        Domů
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="{{ route('subscriptions.index') }}" class="text-base font-semibold text-gray-700 hover:text-primary-600 transition-colors duration-200 relative group">
                        Předplatné
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="{{ route('products.index') }}" class="text-base font-semibold text-gray-700 hover:text-primary-600 transition-colors duration-200 relative group">
                        Obchod
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                </nav>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <!-- User Account -->
                    <a href="{{ route('dashboard.index') }}" class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 transition-all duration-200 shadow-lg" title="Můj účet">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-primary-100 transition-colors duration-200 group" title="Košík">
                        <svg class="w-5 h-5 text-gray-700 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 bg-gradient-to-r from-primary-500 to-pink-500 text-white text-xs w-5 h-5 flex items-center justify-center font-bold rounded-full shadow-lg animate-pulse">
                            {{ array_sum(session('cart')) }}
                        </span>
                        @endif
                    </a>

                    <!-- Mobile Menu Button -->
                    <button type="button" id="mobile-menu-button" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                        <svg class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="lg:hidden absolute top-full left-0 right-0 bg-white backdrop-blur-xl shadow-2xl border-t-2 border-gray-200" style="display: none;">
            <div class="max-w-screen-xl mx-auto px-6 py-8 space-y-2">
                <a href="{{ route('home') }}" class="block text-gray-900 hover:text-primary-600 font-bold py-3.5 px-4 rounded-xl hover:bg-primary-50 transition-all">
                    Domů
                </a>
                <a href="{{ route('subscriptions.index') }}" class="block text-gray-900 hover:text-primary-600 font-bold py-3.5 px-4 rounded-xl hover:bg-primary-50 transition-all">
                    Předplatné
                </a>
                <a href="{{ route('products.index') }}" class="block text-gray-900 hover:text-primary-600 font-bold py-3.5 px-4 rounded-xl hover:bg-primary-50 transition-all">
                    Obchod
                </a>
                
                <div class="pt-6 mt-4 border-t-2 border-gray-200 space-y-2">
                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 text-gray-900 hover:text-primary-600 font-semibold py-3 px-4 rounded-xl hover:bg-gray-100 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Můj účet</span>
                    </a>
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-gray-900 hover:text-primary-600 font-semibold py-3 px-4 rounded-xl hover:bg-gray-100 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Admin</span>
                    </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 text-red-600 hover:text-red-700 font-semibold py-3 px-4 rounded-xl hover:bg-red-50 transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Odhlásit se</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 mt-4">
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 mt-4">
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Dashboard Container -->
    <div class="flex-grow flex">
        <div class="mx-auto max-w-screen-xl w-full px-4 md:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Navigation -->
                <aside class="lg:w-64 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-28">
                        <!-- User Info -->
                        <div class="flex items-center gap-4 pb-6 border-b border-gray-200 mb-6">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-pink-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-dark-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-dark-600 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        <!-- Navigation Menu -->
                        <nav class="space-y-2">
                            <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.index') ? 'bg-primary-50 text-primary-600 shadow-md border-l-4 border-primary-500' : 'text-dark-700 hover:bg-bluegray-100' }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span class="font-semibold">Nástěnka</span>
                            </a>

                            <a href="{{ route('dashboard.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.profile') ? 'bg-primary-50 text-primary-600 shadow-md border-l-4 border-primary-500' : 'text-dark-700 hover:bg-bluegray-100' }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-semibold">Můj profil</span>
                            </a>

                            <a href="{{ route('dashboard.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.orders') || request()->routeIs('dashboard.order.detail') ? 'bg-primary-50 text-primary-600 shadow-md border-l-4 border-primary-500' : 'text-dark-700 hover:bg-bluegray-100' }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span class="font-semibold">Objednávky</span>
                            </a>

                            <a href="{{ route('dashboard.subscription') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.subscription') ? 'bg-primary-50 text-primary-600 shadow-md border-l-4 border-primary-500' : 'text-dark-700 hover:bg-bluegray-100' }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <span class="font-semibold">Předplatné</span>
                            </a>

                            <a href="{{ route('dashboard.notifications') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.notifications') ? 'bg-primary-50 text-primary-600 shadow-md border-l-4 border-primary-500' : 'text-dark-700 hover:bg-bluegray-100' }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="font-semibold">Notifikace</span>
                                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                                <span class="ml-auto bg-primary-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                    {{ $unreadNotifications }}
                                </span>
                                @endif
                            </a>
                        </nav>

                        <!-- Logout -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all duration-200 w-full">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span class="font-semibold">Odhlásit se</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="flex-1 min-w-0">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 border-t-4 border-primary-500 mt-auto">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="py-8 text-center">
                <p class="text-sm text-gray-400">© {{ date('Y') }} Kavi Coffee. Všechna práva vyhrazena.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (mobileMenu.style.display === 'none' || mobileMenu.style.display === '') {
                        mobileMenu.style.display = 'block';
                    } else {
                        mobileMenu.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>

