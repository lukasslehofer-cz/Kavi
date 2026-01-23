@php
    // Detect locale from domain for error pages (middleware may not run for some errors)
    $host = request()->getHost();
    $detectedLocale = $currentLocale ?? (str_contains($host, 'kavibox.com') ? 'en' : 'cs');
@endphp
<!DOCTYPE html>
<html lang="{{ $detectedLocale === 'en' ? 'en' : 'cs' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $detectedLocale === 'en' ? 'Error | KAVI' : 'Chyba | KAVI.cz')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-[#e5e6df] text-[#1c1c1c]">
    <!-- Swiss Style Header -->
    <header class="border-b border-[#1c1c1c]">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center" aria-label="logo">
                    <img src="/images/kavi-logo-black.png" alt="KAVI" class="h-8 w-auto">
                </a>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ $detectedLocale === 'en' ? 'Home' : 'Úvod' }}
                    </a>
                    <a href="{{ localizedRoute('subscriptions.index') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ $detectedLocale === 'en' ? 'Coffee Boxes' : 'Kávové boxy' }}
                    </a>
                    <a href="{{ localizedRoute('products.index') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ $detectedLocale === 'en' ? 'Shop' : 'Obchod' }}
                    </a>
                    <a href="{{ localizedRoute('contact') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ $detectedLocale === 'en' ? 'Contact' : 'Kontakt' }}
                    </a>
                </nav>

                <!-- Mobile menu button -->
                <a href="{{ route('home') }}" class="md:hidden text-xs uppercase tracking-widest border-b border-[#1c1c1c]">
                    {{ $detectedLocale === 'en' ? 'Home' : 'Úvod' }}
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Swiss Style Footer -->
    <footer class="border-t border-[#1c1c1c]">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="py-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs uppercase tracking-widest">© {{ date('Y') }} KAVI</p>
                <div class="flex gap-6">
                    <a href="{{ localizedRoute('terms-of-service') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ $detectedLocale === 'en' ? 'Terms' : 'Podmínky' }}
                    </a>
                    <a href="{{ localizedRoute('privacy-policy') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ $detectedLocale === 'en' ? 'Privacy' : 'Ochrana údajů' }}
                    </a>
                    <a href="{{ localizedRoute('contact') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ $detectedLocale === 'en' ? 'Contact' : 'Kontakt' }}
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
