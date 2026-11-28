<!DOCTYPE html>
<html lang="{{ $currentLocale ?? 'cs' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('layout.meta.title'))</title>

    <!-- Meta Description -->
    @php
        $defaultMetaDescription = __('layout.meta.description');
        $defaultOgTitle = __('layout.meta.og_title');
        $siteUrl = \App\Helpers\Site::current()->url();
        $defaultOgImage = $siteUrl . '/images/og-image.jpg';
    @endphp
    <meta name="description" content="@yield('meta_description', $defaultMetaDescription)">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', $defaultOgTitle)">
    <meta property="og:description" content="@yield('og_description', $defaultMetaDescription)">
    <meta property="og:image" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ \App\Helpers\Site::current()->name() }}">
    <meta property="og:locale" content="{{ \App\Helpers\Site::current()->ogLocale() }}">
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
    @foreach(\App\Helpers\HreflangHelper::alternates() as $hreflangLocale => $hreflangUrl)
    <link rel="alternate" hreflang="{{ $hreflangLocale }}" href="{{ $hreflangUrl }}">
    @endforeach
    @hasSection('robots')
    <meta name="robots" content="@yield('robots')">
    @endif

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

    @php $gaMeasurementId = \App\Helpers\GoogleAnalyticsHelper::currentMeasurementId(); @endphp
    @if($gaMeasurementId)
    <!-- Google Ads / GA4 (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaMeasurementId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaMeasurementId }}');
    </script>
    @endif

    @php $metaPixelId = \App\Helpers\MetaPixelHelper::currentPixelId(); @endphp
    @if($metaPixelId)
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
        fbq('init', '{{ $metaPixelId }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $metaPixelId }}&ev=PageView&noscript=1"/></noscript>
    @endif

    <!-- Cookie Consent Configuration -->
    <script>
        // Cookie consent config (loaded by Laravel)
        window.cookieConsentConfig = {!! \App\Helpers\CookieConsentHelper::getConfigJson($currentLocale ?? 'cs') !!};
    </script>
    <!-- End Cookie Consent Configuration -->
    
    @if(! \App\Helpers\Site::current()->isPrimary())
    <!-- TrustBox script - only for EN version -->
    @endif

    @stack('head')
</head>
<body class="min-h-screen flex flex-col">
    @hasSection('minimal_chrome')
    {{-- Landing page z reklamy: jen logo a CTA, bez menu a hlášek --}}
    @include('partials.header-minimal')
    @else
    <!-- Top Announcement Banner - Terracotta -->
    @php $announcementBanner = \App\Models\AnnouncementBanner::getCurrent(); @endphp
    @if($announcementBanner)
    <div class="bg-dark-800">
        <div class="flex items-center justify-center gap-3 px-4 py-2.5">
            <svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $announcementBanner->getIconPath() }}" />
            </svg>
            <div class="text-sm text-white font-light tracking-wide">
                {{ $announcementBanner->getMessage($currentLocale ?? 'cs') }}
                @if($bannerLink = $announcementBanner->getLink($currentLocale ?? 'cs'))
                <a href="{{ $bannerLink['url'] }}" class="ml-1 text-white underline underline-offset-2 hover:text-primary-400 transition-colors duration-200">{{ $bannerLink['label'] }}</a>
                @endif
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
                        {{ __('layout.nav.home') }}
                    </a> -->
                    <a href="{{ localizedRoute('subscriptions.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ __('layout.nav.boxes') }}
                    </a>
                    <a href="{{ localizedRoute('monthly-feature.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ __('layout.nav.coffee_of_month') }}
                    </a>
                    <a href="{{ localizedRoute('products.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ __('layout.nav.shop') }}
                    </a>
                    <a href="{{ localizedRoute('roasteries.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ __('layout.nav.roasteries') }}
                    </a>
                    @if(\App\Helpers\GiftHelper::enabled())
                    <a href="{{ localizedRoute('gifts.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ __('layout.nav.gifts') }}
                    </a>
                    @endif
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
                    <a href="{{ localizedRoute('dashboard.index') }}" class="text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200" title="{{ __('layout.nav.my_account') }}">
                        {{ __('layout.nav.account') }}
                    </a>
                    @else
                    <a href="{{ localizedRoute('login') }}" class="hidden md:inline-flex text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200">
                        {{ __('layout.nav.sign_in') }}
                    </a>
                    @endauth

                    <!-- Cart -->
                    <a href="{{ localizedRoute('cart.index') }}" class="relative text-sm text-dark-800 hover:text-primary-500 tracking-wide uppercase transition-colors duration-200 group" title="{{ __('layout.nav.cart') }}">
                        <span>{{ __('layout.nav.cart') }}</span>
                        @php $cartCount = array_sum(session('cart', [])) + count(session('gift_cart', [])); @endphp
                        @if($cartCount > 0)
                        <span class="text-primary-500 font-medium">({{ $cartCount }})</span>
                        @endif
                    </a>
                    
                    <!-- CTA Button - Desktop -->
                    <a href="{{ localizedRoute('subscriptions.index') }}" class="btn-type hidden lg:inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white text-sm px-5 py-2.5 transition-all duration-200">
                        <span>{{ __('layout.nav.build_box') }}</span>
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
                    {{ __('layout.nav.home_mobile') }}
                </a>
                <a href="{{ localizedRoute('subscriptions.index') }}" class="block text-dark-800 hover:text-primary-500 py-3 border-b border-warm-100 uppercase tracking-wide transition-colors duration-200">
                    {{ __('layout.nav.boxes') }}
                </a>
                <a href="{{ localizedRoute('monthly-feature.index') }}" class="block text-dark-800 hover:text-primary-500 py-3 border-b border-warm-100 uppercase tracking-wide transition-colors duration-200">
                    {{ __('layout.nav.coffee_of_month') }}
                </a>
                <a href="{{ localizedRoute('products.index') }}" class="block text-dark-800 hover:text-primary-500 py-3 border-b border-warm-100 uppercase tracking-wide transition-colors duration-200">
                    {{ __('layout.nav.shop') }}
                </a>
                <a href="{{ localizedRoute('roasteries.index') }}" class="block text-dark-800 hover:text-primary-500 py-3 border-b border-warm-100 uppercase tracking-wide transition-colors duration-200">
                    {{ __('layout.nav.roasteries') }}
                </a>
                @if(\App\Helpers\GiftHelper::enabled())
                <a href="{{ localizedRoute('gifts.index') }}" class="block text-dark-800 hover:text-primary-500 py-3 border-b border-warm-100 uppercase tracking-wide transition-colors duration-200">
                    {{ __('layout.nav.gifts') }}
                </a>
                @endif

                <!-- Mobile CTA Button -->
                <div class="pt-6">
                    <a href="{{ localizedRoute('subscriptions.index') }}" class="btn-type flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white px-6 py-4 transition-all duration-200">
                        <span>{{ __('layout.nav.build_box') }}</span>
                        <span>&rarr;</span>
                    </a>
                </div>
                
                <!-- Account Section -->
                <div class="pt-6 mt-4 border-t border-warm-200 space-y-1">
                    @auth
                        <a href="{{ localizedRoute('dashboard.index') }}" class="btn-outline flex w-full px-6 py-4">
                            {{ __('layout.nav.my_account') }}
                        </a>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block text-dark-800 hover:text-primary-500 py-3 mt-2 uppercase tracking-wide transition-colors duration-200">
                            Admin
                        </a>
                        @endif
                        <form action="{{ localizedRoute('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 text-red-600 hover:text-red-700 py-3 uppercase tracking-wide transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>{{ __('layout.nav.sign_out') }}</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ localizedRoute('login') }}" class="btn-outline flex w-full px-6 py-4">
                            {{ __('layout.nav.sign_in_mobile') }}
                        </a>
                        <a href="{{ localizedRoute('register') }}" class="btn-type flex items-center justify-center gap-2 bg-dark-800 hover:bg-black text-white px-6 py-4 transition-all duration-200 text-center mt-3">
                            {{ __('layout.nav.register') }}
                        </a>
                    @endauth
                </div>

                <!-- Language Switcher - Mobile (poslední položka menu) -->
                <div class="pt-3 mt-4 border-t border-warm-200">
                    @include('partials.language-switcher', ['variant' => 'menu'])
                </div>
            </div>
        </div>
    </header>
    @endif

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

    @hasSection('minimal_chrome')
    @include('partials.footer-minimal')
    @else
    <!-- Footer - Quiet Luxury -->
    <footer class="relative bg-black text-white">

      <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-10 pt-16 pb-12">
          <!-- Brand Section -->
          <div class="lg:col-span-2">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6" aria-label="logo">
              <img src="/images/kavi-logo-white.png" alt="{{ \App\Helpers\Site::current()->name() }}" class="h-9 w-auto">
            </a>
            
            <p class="text-white/60 mb-6 leading-relaxed max-w-sm text-sm font-light">
              {{ __('layout.footer.tagline') }}
            </p>

            <!-- Social Links -->
            <div class="flex gap-4">
              <a href="{{ __('layout.social.instagram') }}" target="_blank" class="text-white/60 hover:text-primary-500 transition-colors duration-200">
                Instagram
              </a>
              <a href="https://www.facebook.com/kavovepredplatne" target="_blank" class="text-white/60 hover:text-primary-500 transition-colors duration-200">
                Facebook
              </a>
            </div>
          </div>

          <!-- Subscription -->
          <div>
            <h3 class="text-white font-normal text-sm mb-5 uppercase tracking-widest">{{ __('layout.footer.subscription') }}</h3>
            <nav class="space-y-3">
              <a href="{{ localizedRoute('subscriptions.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.configurator') }}</a>
              <a href="{{ localizedRoute('subscriptions.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.one_time_box') }}</a>
              @if(\App\Helpers\GiftHelper::enabled())
              <a href="{{ localizedRoute('gifts.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.gift_subscription') }}</a>
              @endif
              @if(\App\Helpers\CorporateHelper::enabled())
              <a href="{{ route('corporate.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('corporate.links.footer') }}</a>
              @endif
              <a href="{{ localizedRoute('monthly-feature.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.coffee_of_month') }}</a>        
            </nav>
          </div>

          <!-- Shop -->
          <div>
            <h3 class="text-white font-normal text-sm mb-5 uppercase tracking-widest">{{ __('layout.footer.shop') }}</h3>
            <nav class="space-y-3">
              <a href="{{ localizedRoute('products.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.all_products') }}</a>
              <a href="{{ localizedRoute('products.index', ['category' => 'espresso']) }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.espresso') }}</a>
              <a href="{{ localizedRoute('products.index', ['category' => 'filter']) }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.filter') }}</a>
              <a href="{{ localizedRoute('products.index', ['category' => 'decaf']) }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.decaf') }}</a>
            </nav>
          </div>

          <!-- Coffee guide (interní odkazy na články, které chceme posílit) -->
          @php
            $footerGuide = __('layout.footer.guide_articles');
          @endphp
          <div>
            <h3 class="text-white font-normal text-sm mb-5 uppercase tracking-widest">{{ __('layout.footer.guide') }}</h3>
            <nav class="space-y-3">
              @foreach($footerGuide as $guideSlug => $guideLabel)
              @if(request()->routeIs('blog.show') && request()->route('post') === $guideSlug)
              {{-- Článek neodkazuje sám na sebe --}}
              <span class="block text-white/60 text-sm font-light" aria-current="page">{{ $guideLabel }}</span>
              @else
              <a href="{{ localizedRoute('blog.show', ['post' => $guideSlug]) }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ $guideLabel }}</a>
              @endif
              @endforeach
              <a href="{{ localizedRoute('blog.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.all_articles') }}</a>
            </nav>
          </div>

          <!-- Information (včetně kontaktu) -->
          <div>
            <h3 class="text-white font-normal text-sm mb-5 uppercase tracking-widest">{{ __('layout.footer.information') }}</h3>
            <nav class="space-y-3">
              <a href="{{ localizedRoute('how-it-works') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.how_it_works') }}</a>
              <a href="{{ localizedRoute('about') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">{{ __('layout.footer.about') }}</a>
              <a href="{{ localizedRoute('how-it-works') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">FAQ</a>
              <a href="{{ localizedRoute('blog.index') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">Blog</a>
              <a href="{{ localizedRoute('contact') }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">
                {{ __('layout.footer.write_us') }}
              </a>
              @php $footerEmail = \App\Helpers\Site::current()->contactEmail(); @endphp
              <a href="mailto:{{ $footerEmail }}" class="block text-white/60 hover:text-primary-500 transition-colors duration-200 text-sm font-light">
                {{ $footerEmail }}
              </a>
            </nav>
          </div>
        </div>

        <!-- Newsletter Section -->
        <div class="border-t border-white/10 py-12">
          <div class="max-w-xl mx-auto text-center">
            <h3 class="font-display text-2xl font-normal text-white mb-3 uppercase tracking-tight">{{ __('layout.newsletter.title') }}</h3>
            <p class="text-white/60 mb-8 text-sm font-light">{{ __('layout.newsletter.text') }}</p>
            <form id="newsletter-form" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
              @csrf
              <input 
                type="email" 
                name="email" 
                id="newsletter-email"
                placeholder="{{ __('layout.newsletter.placeholder') }}" 
                required
                class="flex-1 px-5 py-3 bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:border-white/40 transition-all text-sm"
              >
              <button type="submit" class="btn-type px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white transition-all duration-200 whitespace-nowrap text-sm">
                {{ __('layout.newsletter.submit') }}
              </button>
            </form>
            <div id="newsletter-message" class="mt-4 text-sm hidden"></div>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-white/10 py-6">
          <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-white/40 font-light">
            {{-- Vlevo copyright a právní odkazy, vpravo samotný přepínač jazyků --}}
            <div class="flex flex-col md:flex-row items-center gap-x-6 gap-y-3">
              <p>© {{ date('Y') }} {{ \App\Helpers\Site::current()->name() }}. {{ __('layout.footer.rights') }}</p>
              <div class="flex flex-wrap justify-center items-center gap-6">
                <a href="{{ localizedRoute('terms-of-service') }}" class="hover:text-white transition-colors duration-200">{{ __('layout.footer.terms') }}</a>
                <a href="{{ localizedRoute('privacy-policy') }}" class="hover:text-white transition-colors duration-200">{{ __('layout.footer.privacy') }}</a>
              </div>
            </div>
            @include('partials.language-switcher', ['variant' => 'footer'])
          </div>
        </div>
      </div>
    </footer>
    @endif

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
                    submitButton.textContent = @json(__('layout.newsletter.sending'));

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

                            // Tracking: Lead (Google tag + Meta Pixel)
                            gtag('event', 'generate_lead', {'form': 'newsletter'});
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
                        newsletterMessage.textContent = @json(__('layout.newsletter.error'));
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
    @stack('scripts')
</body>
</html>


