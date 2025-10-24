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
    <!-- Top Announcement Bar -->
    <div class="bg-primary-500 text-white text-center py-3 px-4">
        <p class="font-bold text-sm">Doprava zdarma nad 1000 Kč • 1900+ spokojených zákazníků • Bez závazků</p>
    </div>

    <!-- Navigation - KaffeBox Inspired -->
    <nav class="bg-white sticky top-0 z-50 shadow-sm border-b border-bluegray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-8 md:h-10 w-auto">
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="{{ route('subscriptions.index') }}" class="text-dark-700 hover:text-primary-500 font-bold text-sm transition-colors py-2 border-b-2 border-transparent hover:border-primary-500">Předplatné</a>
                    <a href="{{ route('products.index') }}" class="text-dark-700 hover:text-primary-500 font-bold text-sm transition-colors py-2 border-b-2 border-transparent hover:border-primary-500">Brew Shop</a>
                    
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="text-dark-700 hover:text-primary-500 font-bold text-sm transition-colors py-2 border-b-2 border-transparent hover:border-primary-500">Můj účet</a>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="text-dark-700 hover:text-primary-500 font-bold text-sm transition-colors py-2 border-b-2 border-transparent hover:border-primary-500">Admin</a>
                        @endif
                    @endauth
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Auth Buttons Desktop -->
                    <div class="hidden lg:flex items-center space-x-4">
                        @auth
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-dark-700 hover:text-primary-500 font-bold text-sm transition-colors">Odhlásit se</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-dark-700 hover:text-primary-500 font-bold text-sm transition-colors">Přihlásit se</a>
                            <a href="{{ route('register') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 font-bold text-sm transition-all">Začít</a>
                        @endauth
                    </div>

                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 hover:text-primary-500 transition-colors">
                        <svg class="w-6 h-6 text-dark-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 bg-primary-500 text-white text-xs w-5 h-5 flex items-center justify-center font-bold rounded-full">
                            {{ array_sum(session('cart')) }}
                        </span>
                        @endif
                    </a>

                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="lg:hidden p-2 hover:text-primary-500 transition-colors">
                        <svg class="w-6 h-6 text-dark-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="hidden lg:hidden pb-6 pt-4 border-t border-bluegray-100">
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('subscriptions.index') }}" class="text-dark-700 hover:text-primary-500 font-bold py-2 transition-colors">Předplatné</a>
                    <a href="{{ route('products.index') }}" class="text-dark-700 hover:text-primary-500 font-bold py-2 transition-colors">Brew Shop</a>
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="text-dark-700 hover:text-primary-500 font-bold py-2 transition-colors">Můj účet</a>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="text-dark-700 hover:text-primary-500 font-bold py-2 transition-colors">Admin</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="pt-2">
                            @csrf
                            <button type="submit" class="text-dark-700 hover:text-primary-500 font-bold text-left py-2 w-full">Odhlásit se</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-dark-700 hover:text-primary-500 font-bold py-2 transition-colors">Přihlásit se</a>
                        <a href="{{ route('register') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 font-bold text-sm transition-all text-center mt-2">Začít</a>
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

    <!-- Footer - KaffeBox Inspired -->
    <footer class="bg-dark-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-12">
                <!-- Brand Column -->
                <div class="lg:col-span-1">
                    <div class="mb-6">
                        <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-10 w-auto mb-4 brightness-0 invert">
                    </div>
                    <p class="text-bluegray-300 text-sm mb-6 leading-relaxed">
                        Prémiová káva s měsíčními dodávkami přímo k vám domů.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#" class="w-10 h-10 bg-bluegray-700 hover:bg-primary-500 flex items-center justify-center transition-all rounded">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-bluegray-700 hover:bg-primary-500 flex items-center justify-center transition-all rounded">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Subscriptions -->
                <div>
                    <h4 class="font-bold text-white mb-4 text-sm">Předplatné</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('subscriptions.index') }}" class="text-bluegray-300 hover:text-white transition-colors">Konfigurátor předplatného</a></li>
                        <li><a href="{{ route('subscriptions.index') }}" class="text-bluegray-300 hover:text-white transition-colors">Espresso BOX</a></li>
                        <li><a href="{{ route('subscriptions.index') }}" class="text-bluegray-300 hover:text-white transition-colors">Filter BOX</a></li>
                        <li><a href="{{ route('subscriptions.index') }}" class="text-bluegray-300 hover:text-white transition-colors">Dárkové předplatné</a></li>
                    </ul>
                </div>

                <!-- Shop -->
                <div>
                    <h4 class="font-bold text-white mb-4 text-sm">Obchod</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.index') }}" class="text-bluegray-300 hover:text-white transition-colors">Všechny produkty</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'coffee']) }}" class="text-bluegray-300 hover:text-white transition-colors">Káva</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'equipment']) }}" class="text-bluegray-300 hover:text-white transition-colors">Vybavení</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'merch']) }}" class="text-bluegray-300 hover:text-white transition-colors">Merch</a></li>
                    </ul>
                </div>

                <!-- Info -->
                <div>
                    <h4 class="font-bold text-white mb-4 text-sm">Informace</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-bluegray-300 hover:text-white transition-colors">O nás</a></li>
                        <li><a href="#" class="text-bluegray-300 hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="text-bluegray-300 hover:text-white transition-colors">Doprava a platba</a></li>
                        <li><a href="#" class="text-bluegray-300 hover:text-white transition-colors">Reklamace a vrácení</a></li>
                        <li><a href="#" class="text-bluegray-300 hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-bold text-white mb-4 text-sm">Kontakt</h4>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="mailto:info@kavi.cz" class="text-bluegray-300 hover:text-white transition-colors">info@kavi.cz</a>
                        </li>
                        <li>
                            <a href="tel:+420123456789" class="text-bluegray-300 hover:text-white transition-colors">+420 123 456 789</a>
                        </li>
                        <li class="pt-2">
                            <p class="text-bluegray-400 text-xs">Po-Pá: 9:00 - 17:00</p>
                        </li>
                        <li class="pt-4">
                            <p class="text-bluegray-300 text-xs">
                                Kavi Coffee s.r.o.<br/>
                                Praha, Česká republika
                            </p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-bluegray-700">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <p class="text-bluegray-400 text-sm">&copy; {{ date('Y') }} Kavi Coffee. Všechna práva vyhrazena.</p>
                    <div class="flex space-x-6 text-sm">
                        <a href="#" class="text-bluegray-400 hover:text-white transition-colors">Obchodní podmínky</a>
                        <a href="#" class="text-bluegray-400 hover:text-white transition-colors">Ochrana soukromí</a>
                        <a href="#" class="text-bluegray-400 hover:text-white transition-colors">Cookies</a>
                    </div>
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


