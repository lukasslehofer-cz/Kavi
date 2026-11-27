@php
    // Detect locale from domain for error pages (middleware may not run for some errors)
    $host = request()->getHost();
    $detectedLocale = $currentLocale ?? (\App\Helpers\Site::forHost($host)?->locale ?? 'cs');
    // Odkazy v jazyce webu i bez middleware (localizedRoute() by vzal jazyk aplikace)
    $routeFor = fn (string $name) => route(\App\Services\EmailService::routeName($detectedLocale, $name));
@endphp
<!DOCTYPE html>
<html lang="{{ $detectedLocale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('errors.layout.title', [], $detectedLocale))</title>

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
                        {{ __('errors.links.home', [], $detectedLocale) }}
                    </a>
                    <a href="{{ $routeFor('subscriptions.index') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ __('errors.links.coffee_boxes', [], $detectedLocale) }}
                    </a>
                    <a href="{{ $routeFor('products.index') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ __('errors.links.shop', [], $detectedLocale) }}
                    </a>
                    @if(\App\Helpers\GiftHelper::enabled())
                    <a href="{{ $routeFor('gifts.index') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ __('errors.links.gifts', [], $detectedLocale) }}
                    </a>
                    @endif
                    <a href="{{ $routeFor('contact') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ __('errors.links.contact', [], $detectedLocale) }}
                    </a>
                </nav>

                <!-- Mobile menu button -->
                <a href="{{ route('home') }}" class="md:hidden text-xs uppercase tracking-widest border-b border-[#1c1c1c]">
                    {{ __('errors.links.home', [], $detectedLocale) }}
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
                <div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
                    <a href="{{ $routeFor('terms-of-service') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ __('errors.links.terms', [], $detectedLocale) }}
                    </a>
                    <a href="{{ $routeFor('privacy-policy') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ __('errors.links.privacy', [], $detectedLocale) }}
                    </a>
                    @if(\App\Helpers\GiftHelper::enabled())
                    <a href="{{ $routeFor('gifts.index') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ __('errors.links.gifts', [], $detectedLocale) }}
                    </a>
                    @endif
                    <a href="{{ $routeFor('contact') }}" class="text-xs uppercase tracking-widest hover:text-red-600 transition-colors">
                        {{ __('errors.links.contact', [], $detectedLocale) }}
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
