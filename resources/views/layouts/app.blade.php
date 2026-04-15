<!DOCTYPE html>
<html lang="{{ $currentLocale ?? 'cs' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($currentLocale ?? 'cs') === 'en' ? 'Coffee Subscription | Specialty Coffee | KAVI' : 'Kávové předplatné | Výběrová káva | KAVI.cz')</title>
    
    <!-- Meta Description -->
    @php
        $defaultMetaDescription = ($currentLocale ?? 'cs') === 'en' 
            ? 'Premium specialty coffee subscription. Freshly roasted, carefully selected coffee from the best European roasteries delivered to your door.'
            : 'Prémiové kávové předplatné s výběrovou kávou. Čerstvě pražená káva z nejlepších evropských pražíren doručená přímo k vám domů.';
        $defaultOgTitle = ($currentLocale ?? 'cs') === 'en' 
            ? 'KAVI - Specialty Coffee Subscription'
            : 'KAVI.cz - Kávové předplatné s výběrovou kávou';
        $siteUrl = ($currentLocale ?? 'cs') === 'en' ? 'https://kavibox.com' : 'https://kavi.cz';
        $defaultOgImage = $siteUrl . '/images/og-image.jpg';
    @endphp
    <meta name="description" content="@yield('meta_description', $defaultMetaDescription)">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', $defaultOgTitle)">
    <meta property="og:description" content="@yield('og_description', $defaultMetaDescription)">
    <meta property="og:image" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ ($currentLocale ?? 'cs') === 'en' ? 'KAVI' : 'KAVI.cz' }}">
    <meta property="og:locale" content="{{ ($currentLocale ?? 'cs') === 'en' ? 'en_US' : 'cs_CZ' }}">
    @if(config('services.facebook.app_id'))
    <meta property="fb:app_id" content="{{ config('services.facebook.app_id') }}">
    @endif
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', $defaultOgTitle)">
    <meta name="twitter:description" content="@yield('og_description', $defaultMetaDescription)">
    <meta name="twitter:image" content="@yield('og_image', $defaultOgImage)">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Google Search Console Verification -->
    @if(config('services.google.search_console_verification'))
    <meta name="google-site-verification" content="{{ config('services.google.search_console_verification') }}">
    @endif

    <!-- Structured Data (JSON-LD) -->
    @yield('structured_data')
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.svg">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Consent Mode v2 - Default State (MUST be before GTM) -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}

        // Set default consent to denied (GDPR compliant)
        gtag('consent', 'default', {
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'analytics_storage': 'denied',
            'wait_for_update': 500
        });
    </script>

    <!-- Google Ads / GA4 (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-96W0CFYXP1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-96W0CFYXP1');
    </script>

    @if(config('services.facebook.pixel_id'))
    <!-- Meta Pixel -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window,document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ config('services.facebook.pixel_id') }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ config('services.facebook.pixel_id') }}&ev=PageView&noscript=1"/></noscript>
    @endif

    <!-- Cookie Consent Configuration -->
    <script>
        // Cookie consent config (loaded by Laravel)
        window.cookieConsentConfig = {!! \App\Helpers\CookieConsentHelper::getConfigJson($currentLocale ?? 'cs') !!};
    </script>
    <!-- End Cookie Consent Configuration -->
    
    @if(($currentLocale ?? 'cs') === 'en')
    <!-- TrustBox script - only for EN version -->
    <script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
    @endif
</head>
<body class="min-h-screen flex flex-col">
    <!-- Top Announcement Banner - Terracotta -->
    @php $announcementBanner = \App\Models\AnnouncementBanner::getCurrent(); @endphp
    @if($announcementBanner)
    <div class="bg-dark-800">
        <div class="flex items-center justify-center gap-3 px-4 py-2.5">
            <span class="w-1.5 h-1.5 bg-white"></span>
            <div class="text-sm text-white font-light tracking-wide">
                {!! $announcementBanner->getMessage($currentLocale ?? 'cs') !!}
            </div>
        </div>
    </div>
    @endif
    <!-- Banner - end -->

    <!-- Navigation - Quiet Luxury -->
    <header class="sticky top-0 z-50 border-b border-black" style="background-color: #e5e6df;">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center" aria-label="logo">
                    <img src="/images/kavi-logo-black.png" alt="KAVI.cz" class="h-9 w-auto">
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-8">
                    <!-- <a href="{{ route('home') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Home' : 'Úvod' }}
                    </a> -->
                    <a href="{{ localizedRoute('subscriptions.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Coffee Boxes' : 'Kávové boxy' }}
                    </a>
                    <a href="{{ localizedRoute('monthly-feature.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Coffee of the Month' : 'Káva měsíce' }}
                    </a>
                    <a href="{{ localizedRoute('products.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Shop' : 'Obchod' }}
                    </a>
                    <a href="{{ localizedRoute('roasteries.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Our Roasteries' : 'Naše pražírny' }}
                    </a>
                    @auth
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        Admin
                    </a>
                    @endif
                    @endauth
                </nav>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">

                    <!-- User Account -->
                    @auth
                    <a href="{{ localizedRoute('dashboard.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200" title="{{ ($currentLocale ?? 'cs') === 'en' ? 'My Account' : 'Můj účet' }}">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Account' : 'Účet' }}
                    </a>
                    @else
                    <a href="{{ localizedRoute('login') }}" class="hidden md:inline-flex text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Sign In' : 'Přihlásit' }}
                    </a>
                    @endauth

                    <!-- Cart -->
                    <a href="{{ localizedRoute('cart.index') }}" class="relative text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200 group" title="{{ ($currentLocale ?? 'cs') === 'en' ? 'Cart' : 'Košík' }}">
                        <span>{{ ($currentLocale ?? 'cs') === 'en' ? 'Cart' : 'Košík' }}</span>
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="text-primary-500 font-medium">({{ array_sum(session('cart')) }})</span>
                        @endif
                    </a>
                    
                    <!-- CTA Button - Desktop -->
                    <a href="{{ localizedRoute('subscriptions.index') }}" class="hidden lg:inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white text-sm px-5 py-2.5 uppercase tracking-wide transition-all duration-200">
                        <span>{{ ($currentLocale ?? 'cs') === 'en' ? 'Build Your Box' : 'Sestavte si box' }}</span>
                        <span>&rarr;</span>
                    </a>

                    <!-- Mobile Menu Button -->
                    <button type="button" id="mobile-menu-button" class="lg:hidden flex items-center justify-center w-9 h-9 text-dark-800 hover:text-primary-500 transition-colors duration-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="lg:hidden absolute top-full left-0 right-0 border-t border-black" style="display: none; background-color: #e5e6df;">
            <div class="px-6 py-6 space-y-1">
                <!-- Navigation Links -->
                <a href="{{ route('home') }}" class="block text-dark-800 hover:text-primary-500 py-3 border-b border-warm-100 uppercase tracking-wide transition-colors duration-200">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Home' : 'Domů' }}
                </a>
                <a href="{{ localizedRoute('subscriptions.index') }}" class="block text-dark-800 hover:text-primary-500 py-3 border-b border-warm-100 uppercase tracking-wide transition-colors duration-200">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Coffee Boxes' : 'Kávové boxy' }}
                </a>
                <a href="{{ localizedRoute('monthly-feature.index') }}" class="block text-dark-800 hover:text-primary-500 py-3 border-b border-warm-100 uppercase tracking-wide transition-colors duration-200">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Coffee of the Month' : 'Káva měsíce' }}
                </a>
                <a href="{{ localizedRoute('products.index') }}" class="block text-dark-800 hover:text-primary-500 py-3 border-b border-warm-100 uppercase tracking-wide transition-colors duration-200">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Shop' : 'Obchod' }}
                </a>
                <a href="{{ localizedRoute('roasteries.index') }}" class="block text-dark-800 hover:text-primary-500 py-3 border-b border-warm-100 uppercase tracking-wide transition-colors duration-200">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Our Roasteries' : 'Naše pražírny' }}
                </a>
                
                <!-- Mobile CTA Button -->
                <div class="pt-6">
                    <a href="{{ localizedRoute('subscriptions.index') }}" class="flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white px-6 py-4 uppercase tracking-wide transition-all duration-200">
                        <span>{{ ($currentLocale ?? 'cs') === 'en' ? 'Build Your Box' : 'Sestavte si box' }}</span>
                        <span>&rarr;</span>
                    </a>
                </div>
                
                <!-- Account Section -->
                <div class="pt-6 mt-4 border-t border-warm-200 space-y-1">
                    @auth
                        <a href="{{ localizedRoute('dashboard.index') }}" class="block text-dark-800 hover:text-primary-500 py-3 transition-colors duration-200">
                            {{ ($currentLocale ?? 'cs') === 'en' ? 'My Account' : 'Můj účet' }}
                        </a>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block text-dark-800 hover:text-primary-500 py-3 transition-colors duration-200">
                            Admin
                        </a>
                        @endif
                        <form action="{{ localizedRoute('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 text-red-600 hover:text-red-700 hover:bg-red-50 font-medium py-3 px-4 rounded-full transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>{{ ($currentLocale ?? 'cs') === 'en' ? 'Sign Out' : 'Odhlásit se' }}</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ localizedRoute('login') }}" class="block text-dark-800 hover:text-primary-500 py-3 transition-colors duration-200">
                            {{ ($currentLocale ?? 'cs') === 'en' ? 'Sign In' : 'Přihlásit se' }}
                        </a>
                        <a href="{{ localizedRoute('register') }}" class="flex items-center justify-center gap-2 bg-dark-800 hover:bg-black text-white px-6 py-4 transition-all duration-200 text-center mt-4">
                            {{ ($currentLocale ?? 'cs') === 'en' ? 'Register' : 'Registrovat' }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @section('flash-messages')
    @if(session('success'))
    <div class="px-4 py-4" style="background-color: #e5e6df;">
        <div class="bg-olive-500 text-white px-4 py-3">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="px-4 py-4" style="background-color: #e5e6df;">
        <div class="bg-primary-500 text-white px-4 py-3">
            {{ session('error') }}
        </div>
    </div>
    @endif
    @show

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer - Quiet Luxury -->
    <footer class="relative bg-black text-white">

      <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-10 pt-16 pb-12">
          <!-- Brand Section -->
          <div class="lg:col-span-2">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6" aria-label="logo">
              <img src="/images/kavi-logo-white.png" alt="{{ ($currentLocale ?? 'cs') === 'en' ? 'KAVI' : 'KAVI.cz' }}" class="h-9 w-auto">
            </a>
            
            <p class="text-white/60 mb-6 leading-relaxed max-w-sm text-sm font-light">
              {{ ($currentLocale ?? 'cs') === 'en' ? 'Specialty coffee with regular deliveries straight to you. Discover a world of flavors from the best European roasteries.' : 'Výběrová káva s pravidelnými dodávkami přímo k vám. Objevte svět chutí z nejlepších pražíren Evropy.' }}
            </p>

            <!-- Social Links -->
            <div class="flex gap-4">
              <a href="https://www.instagram.com/kavi.cz" target="_blank" class="text-white/60 hover:text-primary-500 transition-colors duration-200">
                Instagram
              </a>
              <a href="https://www.facebook.com/kavovepredplatne" target="_blank" class="text-white/60 hover:text-primary-500 transition-colors duration-200">
                Facebook
              </a>
            </div>
          </div>

          <!-- Subscription -->
          <div>
            <h3 class="text-white font-normal text-sm mb-5 uppercase tracking-widest">{{ ($currentLocale ?? 'cs') === 'en' ? 'Subscription' : 'Předplatné' }}</h3>
            <nav class="space-y-3">
              <a href="{{ localizedRoute('subscriptions.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ ($currentLocale ?? 'cs') === 'en' ? 'Configurator' : 'Konfigurátor' }}</a>
              <a href="{{ localizedRoute('subscriptions.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ ($currentLocale ?? 'cs') === 'en' ? 'One-time Box' : 'Jednorázový box' }}</a>
              <a href="{{ localizedRoute('monthly-feature.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ ($currentLocale ?? 'cs') === 'en' ? 'Coffee of the Month' : 'Káva měsíce' }}</a>        
            </nav>
          </div>

          <!-- Shop -->
          <div>
            <h3 class="text-white font-normal text-sm mb-5 uppercase tracking-widest">{{ ($currentLocale ?? 'cs') === 'en' ? 'Shop' : 'Obchod' }}</h3>
            <nav class="space-y-3">
              <a href="{{ localizedRoute('products.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ ($currentLocale ?? 'cs') === 'en' ? 'All Products' : 'Všechny produkty' }}</a>
              <a href="{{ localizedRoute('products.index', ['category' => 'espresso']) }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ ($currentLocale ?? 'cs') === 'en' ? 'Espresso Coffee' : 'Espresso káva' }}</a>
              <a href="{{ localizedRoute('products.index', ['category' => 'filter']) }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ ($currentLocale ?? 'cs') === 'en' ? 'Filter Coffee' : 'Filtrovaná káva' }}</a>
              <a href="{{ localizedRoute('products.index', ['category' => 'decaf']) }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ ($currentLocale ?? 'cs') === 'en' ? 'Decaf Coffee' : 'Bezkofeinová káva' }}</a>
            </nav>
          </div>

          <!-- Information -->
          <div>
            <h3 class="text-white font-normal text-sm mb-5 uppercase tracking-widest">{{ ($currentLocale ?? 'cs') === 'en' ? 'Information' : 'Informace' }}</h3>
            <nav class="space-y-3">
              <a href="{{ localizedRoute('how-it-works') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ ($currentLocale ?? 'cs') === 'en' ? 'How It Works' : 'Jak to funguje' }}</a>
              <a href="{{ localizedRoute('about') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ ($currentLocale ?? 'cs') === 'en' ? 'About Us' : 'O nás' }}</a>
              <a href="{{ localizedRoute('how-it-works') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">FAQ</a>
              <a href="{{ localizedRoute('blog.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">Blog</a>
            </nav>
          </div>

          <!-- Contact -->
          <div>
            <h3 class="text-white font-normal text-sm mb-5 uppercase tracking-widest">{{ ($currentLocale ?? 'cs') === 'en' ? 'Contact' : 'Kontakt' }}</h3>
            <nav class="space-y-3">
              <a href="{{ localizedRoute('contact') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">
                {{ ($currentLocale ?? 'cs') === 'en' ? 'Write Us' : 'Napište nám' }}
              </a>
              @php $footerEmail = ($currentLocale ?? 'cs') === 'en' ? 'info@kavibox.com' : 'info@kavi.cz'; @endphp
              <a href="mailto:{{ $footerEmail }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">
                {{ $footerEmail }}
              </a>
            </nav>
          </div>
        </div>

        <!-- Newsletter Section -->
        <div class="border-t border-white/10 py-12">
          <div class="max-w-xl mx-auto text-center">
            <h3 class="font-display text-2xl font-normal text-white mb-3 uppercase tracking-tight">{{ ($currentLocale ?? 'cs') === 'en' ? 'Subscribe to Our Newsletter' : 'Přihlaste se k odběru novinek' }}</h3>
            <p class="text-white/60 mb-8 text-sm font-light">{{ ($currentLocale ?? 'cs') === 'en' ? 'Be the first to learn about new coffees and news from the world of specialty coffee' : 'Buďte první, kdo se dozví o nových kávách a novinkách ze světa výběrové kávy' }}</p>
            <form id="newsletter-form" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
              @csrf
              <input 
                type="email" 
                name="email" 
                id="newsletter-email"
                placeholder="{{ ($currentLocale ?? 'cs') === 'en' ? 'Your email' : 'Váš e-mail' }}" 
                required
                class="flex-1 px-5 py-3 bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:border-white/40 transition-all text-sm"
              >
              <button type="submit" class="px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white transition-all duration-200 whitespace-nowrap text-sm">
                {{ ($currentLocale ?? 'cs') === 'en' ? 'Subscribe' : 'Odebírat' }}
              </button>
            </form>
            <div id="newsletter-message" class="mt-4 text-sm hidden"></div>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-white/10 py-6">
          <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-white/40 font-light">
            <p>© {{ date('Y') }} {{ ($currentLocale ?? 'cs') === 'en' ? 'KAVI' : 'KAVI.cz' }}. {{ ($currentLocale ?? 'cs') === 'en' ? 'All rights reserved.' : 'Všechna práva vyhrazena.' }}</p>
            <div class="flex gap-6">
              <a href="{{ localizedRoute('terms-of-service') }}" class="hover:text-white transition-colors duration-200">{{ ($currentLocale ?? 'cs') === 'en' ? 'Terms of Service' : 'Obchodní podmínky' }}</a>
              <a href="{{ localizedRoute('privacy-policy') }}" class="hover:text-white transition-colors duration-200">{{ ($currentLocale ?? 'cs') === 'en' ? 'Privacy Policy' : 'Ochrana osobních údajů' }}</a>              
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
            const isEnglish = '{{ ($currentLocale ?? 'cs') }}' === 'en';

            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(newsletterForm);
                    const submitButton = newsletterForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.textContent;

                    // Disable button and show loading state
                    submitButton.disabled = true;
                    submitButton.textContent = isEnglish ? 'Sending...' : 'Odesílám...';

                    fetch('{{ localizedRoute('newsletter.subscribe') }}', {
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

                            // Tracking: Lead (dataLayer + Meta Pixel)
                            window.dataLayer = window.dataLayer || [];
                            window.dataLayer.push({'event': 'generate_lead'});
                            if (typeof fbq !== 'undefined') {
                                fbq('track', 'Lead', {content_name: 'Newsletter'});
                            }
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
                        newsletterMessage.textContent = isEnglish ? 'An error occurred. Please try again.' : 'Došlo k chybě. Zkuste to prosím znovu.';
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


