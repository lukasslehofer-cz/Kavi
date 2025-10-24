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
    <!-- Top Bar - Announcement -->
    <div class="bg-dark-800 text-white text-center py-2 px-4 text-sm">
        <p>🎉 Doprava zdarma při objednávce nad 1000 Kč</p>
    </div>

    <!-- Navigation - Kaffebox style -->
    <nav class="bg-white border-b border-bluegray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center transform group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                        </svg>
                    </div>
                    <span class="font-display text-2xl font-bold text-dark-800">Kavi</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-10">
                    <a href="{{ route('home') }}" class="text-dark-700 hover:text-primary-500 font-medium transition-colors">Domů</a>
                    <a href="{{ route('subscriptions.index') }}" class="text-dark-700 hover:text-primary-500 font-medium transition-colors">Předplatné</a>
                    <a href="{{ route('products.index') }}" class="text-dark-700 hover:text-primary-500 font-medium transition-colors">Obchod</a>
                    
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="text-dark-700 hover:text-primary-500 font-medium transition-colors">Můj účet</a>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="text-dark-700 hover:text-primary-500 font-medium transition-colors">Admin</a>
                        @endif
                    @endauth
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-6">
                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative text-dark-700 hover:text-primary-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-2 -right-2 bg-primary-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold">
                            {{ array_sum(session('cart')) }}
                        </span>
                        @endif
                    </a>

                    <!-- Auth Buttons -->
                    <div class="hidden lg:flex items-center space-x-4">
                        @auth
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-dark-700 hover:text-primary-500 font-medium transition-colors">Odhlásit</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-dark-700 hover:text-primary-500 font-medium transition-colors">Přihlásit se</a>
                            <a href="{{ route('register') }}" class="btn btn-primary !px-6 !py-2.5 text-sm">Začít nyní</a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="lg:hidden text-dark-700 hover:text-primary-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="hidden lg:hidden pb-6 pt-4 border-t border-bluegray-100 mt-4">
                <div class="flex flex-col space-y-4">
                    <a href="{{ route('home') }}" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Domů</a>
                    <a href="{{ route('subscriptions.index') }}" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Předplatné</a>
                    <a href="{{ route('products.index') }}" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Obchod</a>
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Můj účet</a>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Admin</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-dark-700 hover:text-primary-500 font-medium text-left py-2 w-full">Odhlásit</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Přihlásit se</a>
                        <a href="{{ route('register') }}" class="btn btn-primary w-full">Začít nyní</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

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

    <!-- Footer - Kaffebox style -->
    <footer class="bg-bluegray-50 border-t border-bluegray-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-12">
                <!-- Brand Column -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                            </svg>
                        </div>
                        <span class="font-display text-2xl font-bold text-dark-800">Kavi</span>
                    </div>
                    <p class="text-dark-600 mb-6">Prémiová káva s pravidelným předplatným. Objevte nové chutě každý měsíc.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-dark-600 hover:text-primary-500 hover:bg-primary-50 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-dark-600 hover:text-primary-500 hover:bg-primary-50 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Links Columns -->
                <div>
                    <h4 class="font-display font-bold text-dark-800 mb-4">Obchod</h4>
                    <ul class="space-y-3 text-dark-600">
                        <li><a href="{{ route('subscriptions.index') }}" class="hover:text-primary-500 transition-colors">Předplatné</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-primary-500 transition-colors">Všechny produkty</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition-colors">Dárkové poukazy</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-display font-bold text-dark-800 mb-4">Informace</h4>
                    <ul class="space-y-3 text-dark-600">
                        <li><a href="#" class="hover:text-primary-500 transition-colors">O nás</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition-colors">Doprava</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition-colors">Vrácení zboží</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-display font-bold text-dark-800 mb-4">Kontakt</h4>
                    <ul class="space-y-3 text-dark-600">
                        <li>
                            <a href="mailto:info@kavi.cz" class="hover:text-primary-500 transition-colors">info@kavi.cz</a>
                        </li>
                        <li>
                            <a href="tel:+420123456789" class="hover:text-primary-500 transition-colors">+420 123 456 789</a>
                        </li>
                        <li class="pt-2">
                            <p class="text-sm">Po-Pá: 9:00 - 17:00</p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-bluegray-200 pt-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-dark-600 text-sm">&copy; {{ date('Y') }} Kavi Coffee. Všechna práva vyhrazena.</p>
                <div class="flex space-x-6 text-sm text-dark-600">
                    <a href="#" class="hover:text-primary-500 transition-colors">Obchodní podmínky</a>
                    <a href="#" class="hover:text-primary-500 transition-colors">Ochrana soukromí</a>
                    <a href="#" class="hover:text-primary-500 transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu?.classList.toggle('hidden');
        });
    </script>
</body>
</html>


