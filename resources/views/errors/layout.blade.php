<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chyba | KAVI.cz')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-white">
    <!-- Simplified Header for Error Pages -->
    <header class="sticky top-0 z-50 bg-white/70 backdrop-blur-lg border-b border-gray-200/50">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group" aria-label="logo">
                    <img src="/images/kavi-logo-black.png" alt="KAVI.cz" class="h-10 w-auto transform group-hover:scale-105 transition-transform duration-200">
                </a>

                <!-- Simple Navigation -->
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50 px-4 py-2 rounded-full transition-all duration-200">
                        Úvod
                    </a>
                    <a href="{{ route('subscriptions.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50 px-4 py-2 rounded-full transition-all duration-200">
                        Kávové boxy
                    </a>
                    <a href="{{ route('contact') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50 px-4 py-2 rounded-full transition-all duration-200">
                        Kontakt
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Simplified Footer -->
    <footer class="bg-white border-t border-gray-100">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="py-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <p>© {{ date('Y') }} KAVI.cz. Všechna práva vyhrazena.</p>
                <div class="flex gap-6">
                    <a href="{{ route('terms-of-service') }}" class="hover:text-gray-900 transition-colors">Obchodní podmínky</a>
                    <a href="{{ route('privacy-policy') }}" class="hover:text-gray-900 transition-colors">Ochrana osobních údajů</a>
                    <a href="{{ route('contact') }}" class="hover:text-gray-900 transition-colors">Kontakt</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

