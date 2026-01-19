@extends('layouts.app')

@section('title', $currentLocale === 'en' ? 'Coffee Subscription | Specialty Coffee | KAVI' : 'Kávové předplatné | Výběrová káva | KAVI.cz')

@section('meta_description')
{{ $currentLocale === 'en' ? 'Discover the best specialty coffee from all over Europe. Freshly roasted, carefully selected, delivered to your door. Flexible subscription with free shipping.' : 'Objevte tu nejlepší výběrovou kávu z celé Evropy. Čerstvě pražená, pečlivě vybraná, doručená přímo k vám. Flexibilní předplatné s dopravou zdarma.' }}
@endsection

@section('og_title')
{{ $currentLocale === 'en' ? 'KAVI - Specialty Coffee Subscription from Europe' : 'KAVI.cz - Kávové předplatné s výběrovou kávou z Evropy' }}
@endsection

@section('og_description')
{{ $currentLocale === 'en' ? 'Premium specialty coffee delivered monthly. Discover unique flavors from the best European roasteries. Free shipping, cancel anytime.' : 'Prémiová výběrová káva doručovaná měsíčně. Objevte jedinečné chutě z nejlepších evropských pražíren. Doprava zdarma, zrušení kdykoliv.' }}
@endsection

@section('structured_data')
@php
    $siteUrl = $currentLocale === 'en' ? 'https://kavibox.com' : 'https://kavi.cz';
    $siteName = $currentLocale === 'en' ? 'KAVI' : 'KAVI.cz';
    $siteDescription = $currentLocale === 'en' 
        ? 'Premium specialty coffee subscription. Freshly roasted coffee from the best European roasteries delivered to your door.'
        : 'Prémiové kávové předplatné s výběrovou kávou. Čerstvě pražená káva z nejlepších evropských pražíren doručená přímo k vám.';
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@graph": [
        {
            "@type": "Organization",
            "@id": "{{ $siteUrl }}/#organization",
            "name": "{{ $siteName }}",
            "url": "{{ $siteUrl }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ $siteUrl }}/images/kavi-logo-black.png",
                "width": 200,
                "height": 60
            },
            "description": "{{ $siteDescription }}",
            "sameAs": [
                "https://www.instagram.com/kavi.cz",
                "https://www.facebook.com/kavovepredplatne"
            ],
            "contactPoint": {
                "@type": "ContactPoint",
                "contactType": "customer service",
                "email": "{{ $currentLocale === 'en' ? 'info@kavibox.com' : 'info@kavi.cz' }}",
                "availableLanguage": ["{{ $currentLocale === 'en' ? 'English' : 'Czech' }}"]
            }
        },
        {
            "@type": "WebSite",
            "@id": "{{ $siteUrl }}/#website",
            "url": "{{ $siteUrl }}",
            "name": "{{ $siteName }}",
            "description": "{{ $siteDescription }}",
            "publisher": {
                "@id": "{{ $siteUrl }}/#organization"
            },
            "inLanguage": "{{ $currentLocale === 'en' ? 'en-US' : 'cs-CZ' }}"
        },
        {
            "@type": "WebPage",
            "@id": "{{ $siteUrl }}/#webpage",
            "url": "{{ $siteUrl }}",
            "name": "{{ $currentLocale === 'en' ? 'Coffee Subscription | Specialty Coffee | KAVI' : 'Kávové předplatné | Výběrová káva | KAVI.cz' }}",
            "isPartOf": {
                "@id": "{{ $siteUrl }}/#website"
            },
            "about": {
                "@id": "{{ $siteUrl }}/#organization"
            },
            "description": "{{ $siteDescription }}",
            "inLanguage": "{{ $currentLocale === 'en' ? 'en-US' : 'cs-CZ' }}"
        },
        {
            "@type": "FAQPage",
            "mainEntity": [
                {
                    "@type": "Question",
                    "name": "{{ $currentLocale === 'en' ? 'How do I choose a subscription plan?' : 'Jak si vyberu plán předplatného?' }}",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "{{ $currentLocale === 'en' ? 'Select coffee quantity and delivery frequency according to your needs. We offer M Box (500g), L Box (750g), and XL Box (1000g).' : 'Zvolte množství kávy a frekvenci dodání podle vašich potřeb. Nabízíme M Box (500g), L Box (750g) a XL Box (1000g).' }}"
                    }
                },
                {
                    "@type": "Question",
                    "name": "{{ $currentLocale === 'en' ? 'Can I personalize my coffee subscription?' : 'Mohu si předplatné personalizovat?' }}",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "{{ $currentLocale === 'en' ? 'Yes! Choose your preferred coffee type (espresso, filter, decaf), brewing method, and delivery address.' : 'Ano! Vyberte si preferovaný typ kávy (espresso, filtr, bezkofeinová), způsob přípravy a doručovací adresu.' }}"
                    }
                },
                {
                    "@type": "Question",
                    "name": "{{ $currentLocale === 'en' ? 'How is the coffee delivered?' : 'Jak je káva doručována?' }}",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "{{ $currentLocale === 'en' ? 'We deliver freshly roasted coffee to your chosen location. Shipping is free for all subscriptions.' : 'Čerstvě praženou kávu doručíme na vámi vybrané místo. Doprava je pro všechna předplatná zdarma.' }}"
                    }
                },
                {
                    "@type": "Question",
                    "name": "{{ $currentLocale === 'en' ? 'Can I cancel my subscription anytime?' : 'Mohu předplatné kdykoliv zrušit?' }}",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "{{ $currentLocale === 'en' ? 'Yes, you can cancel or pause your subscription at any time without any fees.' : 'Ano, předplatné můžete kdykoliv zrušit nebo pozastavit bez jakýchkoliv poplatků.' }}"
                    }
                }
            ]
        }
    ]
}
</script>
@endsection

@section('content')
<div class="overflow-hidden">

<!-- Hero Section - Editorial Magazine Style -->
<div class="relative h-[85vh] sm:h-[90vh] overflow-hidden bg-dark-800">
    <!-- Background Image/Video -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-cover bg-center grayscale" style="background-image: url('/images/kavi-intro-video.jpg');"></div>
        
        <style>
            #hero-video {
                pointer-events: none;
                filter: grayscale(100%);
            }
            #hero-video::-webkit-media-controls {
                display: none !important;
            }
            #hero-video::-webkit-media-controls-start-playback-button {
                display: none !important;
                -webkit-appearance: none;
            }
            /* Grain overlay */
            .grain-overlay {
                position: absolute;
                inset: 0;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='5' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
                opacity: 0.35;
                pointer-events: none;
                mix-blend-mode: overlay;
            }
        </style>
        
        <video 
            id="hero-video"
            autoplay 
            loop 
            muted 
            playsinline
            preload="auto"
            class="absolute inset-0 w-full h-full object-cover grayscale"
        >
            <source src="/images/kavi-intro-video.mp4" type="video/mp4">
        </video>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('hero-video');
                if (video) {
                    video.setAttribute('playsinline', '');
                    video.setAttribute('muted', '');
                    video.muted = true;
                    video.playsInline = true;
                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(error => {
                            document.body.addEventListener('touchstart', function() {
                                video.play();
                            }, { once: true });
                        });
                    }
                }
            });
        </script>
    </div>
    
    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black/60"></div>

    <!-- Grain texture overlay -->
    <div class="grain-overlay"></div>

    <!-- Content - Full screen editorial layout -->
    <div class="relative h-full flex flex-col justify-between p-6 sm:p-8 lg:p-10">
        
        <!-- Top row - only right side text -->
        <div class="flex justify-end">
            <div class="text-xs uppercase tracking-widest text-white/60 text-right max-w-[200px]">
                {{ $currentLocale === 'en' ? 'Specialty coffee subscription from Europe\'s finest roasters.' : 'Předplatné výběrové kávy z nejlepších evropských pražíren.' }}
            </div>
                </div>

        <!-- Center - Main typography -->
        <div class="flex-1 flex flex-col justify-center py-6">
            <!-- Large headline -->
            <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal text-white uppercase leading-[0.95] sm:leading-[0.9] tracking-tight">
                        @if($currentLocale === 'en')
                Discover<br/>
                <span class="text-primary-500">the best</span> coffee<br/>
                from Europe
                        @else
                Objevte<br/>
                <span class="text-primary-500">tu nejlepší</span> kávu<br/>
                z Evropy
                        @endif
            </h1>
            
            <!-- Side description -->
            <div class="mt-6 flex flex-col sm:flex-row gap-6 sm:gap-12">
                <p class="text-xs uppercase tracking-widest text-white/60 max-w-[250px] leading-relaxed">
                    {{ $currentLocale === 'en' ? 'Premium coffee with regular subscription. Freshly roasted, carefully selected.' : 'Prémiová káva s pravidelným předplatným. Čerstvě pražená, pečlivě vybraná.' }}
                </p>
                <p class="text-xs uppercase tracking-widest text-white/60 max-w-[250px] leading-relaxed">
                    {{ $currentLocale === 'en' ? 'Each month a new selection from exceptional European roasteries.' : 'Každý měsíc nový výběr z výjimečných evropských pražíren.' }}
                </p>
            </div>
                </div>

        <!-- Bottom row - CTAs -->
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-6">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-8">
                <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-white font-display uppercase tracking-widest hover:text-primary-500 transition-all">
                    <span>{{ $currentLocale === 'en' ? 'Start subscription' : 'Začít předplatné' }}</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>
                <a href="{{ localizedRoute('products.index') }}" class="group inline-flex items-center gap-2 text-white/60 font-display uppercase tracking-widest hover:text-white transition-all">
                    <span>{{ $currentLocale === 'en' ? 'Shop coffee' : 'Obchod' }}</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>
                </div>
            
            
      </div>

    </div>
</div>

<!-- Features Section -->
<div class="relative py-20 sm:py-24 md:py-28 lg:py-36" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header -->
    <div class="mb-16 sm:mb-20 max-w-2xl">
      <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-4 tracking-tight uppercase leading-tight sm:leading-[0.95]">{{ $currentLocale === 'en' ? 'Why choose KAVI?' : 'Proč si vybrat KAVI?' }}</h2>
      <p class="text-lg sm:text-xl text-warm-500 font-light">{{ $currentLocale === 'en' ? 'We know what makes coffee exceptional. And we love to share it with you.' : 'Víme, co dělá kávu výjimečnou. A rádi se s vámi podělíme.' }}</p>
    </div>

    <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
      <!-- feature - start -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">01</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Fresh Coffee' : 'Čerstvá káva' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'Every roastery delivers freshly roasted coffee to your coffee boxes.' : 'Každá pražírna nám dodává čerstvě praženou kávu do vašich kávových boxů.' }}</p>
      </div>
      <!-- feature - end -->

      <!-- feature - start -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">02</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Exceptional Flavors' : 'Výjimečné chutě' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'No boring and monotonous coffees. Every month you discover new flavors from different corners of the world.' : 'Žádné nudné a monotonní kávy. Každý měsíc objevíte nové chutě z různých koutů světa.' }}</p>        
      </div>
      <!-- feature - end -->

      <!-- feature - start -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">03</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Shipped with Care' : 'Doprava zdarma' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'Like a clockwork, coffee is lovingly packed by our team, and delivered right to your door.' : 'Doprava zdarma pro všechna předplatná. Ať už si vyberete jakoukoliv velikost boxu.' }}</p>
      </div>
      <!-- feature - end -->
    </div>
  </div>
</div>

<!-- Love Your Coffee Section - Editorial Magazine Style -->
<div class="relative overflow-hidden">
  
  <!-- Two-tone background -->
  <div class="grid lg:grid-cols-2">
    
    <!-- Left Side - Image with Red Accent -->
    <div class="relative bg-primary-500">
      <!-- Top metadata -->
      <div class="flex items-center justify-between text-xs uppercase tracking-widest px-4 sm:px-6 lg:px-10 pt-6 sm:pt-8 text-dark-800">
        <span class="font-display">{{ $currentLocale === 'en' ? 'Subscription' : 'Předplatné' }}</span>
        <span>{{ now()->format('m/Y') }}</span>
        </div>
        
      <!-- Photo container - reduced height, grayscale with grain -->
      <div class="px-4 sm:px-6 lg:px-10 py-4 pb-6 sm:pb-8">
        <div class="relative aspect-[5/4] overflow-hidden">
          <img src="/images/kavi-box.jpg" loading="lazy" alt="KAVI box" class="h-full w-full object-cover grayscale" />
          <!-- Grain overlay for image -->
          <div class="absolute inset-0 opacity-30 mix-blend-overlay pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 200 200%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%275%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>
          </div>
        </div>
      </div>

    <!-- Right Side - Content on light background -->
    <div class="bg-[#e5e6df] flex flex-col justify-between p-6 sm:p-8 lg:p-12 xl:p-16">
      
      <!-- Main content -->
      <div class="space-y-8">
        <div>
          <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-6 leading-tight tracking-tight uppercase">
            {{ $currentLocale === 'en' ? 'Coffee you will love' : 'Káva, kterou budete milovat' }}
          </h2>
          
          <p class="text-lg sm:text-xl text-warm-500 leading-relaxed font-light">
            {{ $currentLocale === 'en' ? 'We carefully select the highest quality coffee from trusted roasteries. Discover new flavors every month right at your home.' : 'Pečlivě vybíráme nejkvalitnější kávu z ověřených pražíren. Každý měsíc objevte nové chutě přímo u vás doma.' }}
          </p>
        </div>

        <!-- Benefits List -->
        <div class="space-y-4">
          <div class="flex gap-3 items-start">
            <span class="text-primary-500">→</span>
            <p class="text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Exclusive European roasters not available elsewhere' : 'Exkluzivní evropské pražírny nedostupné jinde' }}</p>
            </div>
          <div class="flex gap-3 items-start">
            <span class="text-primary-500">→</span>
            <p class="text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Unbeatable value for specialty coffee' : 'Bezkonkurenční cena za výběrovou kávu' }}</p>
            </div>
          <div class="flex gap-3 items-start">
            <span class="text-primary-500">→</span>
            <p class="text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Flexible subscription, cancel anytime' : 'Flexibilní předplatné, zrušení kdykoliv' }}</p>
          </div>
            </div>
          </div>

      <!-- Bottom section with links -->
      <div class="mt-auto pt-12 border-t border-stone-300">
        <div class="flex items-center justify-between">
          <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
            <span>{{ $currentLocale === 'en' ? 'Subscription' : 'Předplatné' }}</span>
            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
          </a>
          <a href="{{ localizedRoute('products.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
            <span>{{ $currentLocale === 'en' ? 'Shop' : 'Obchod' }}</span>
            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
          </a>
          </div>
        </div>

  </div>
  
  </div>
</div>

<!-- Subscription Plans Section - Horizontal Tiers -->
<div class="relative overflow-hidden" style="background-color: #e5e6df;">
  
  <!-- Section Header -->
  <div class="pt-20 sm:pt-24 lg:pt-28 pb-12 sm:pb-16 border-b border-dark-800/10">
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 text-center">
      <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-4 tracking-tight uppercase leading-tight sm:leading-[0.95]">{{ $currentLocale === 'en' ? 'Choose your ideal coffee box' : 'Vyberte si ideální kávový box' }}</h2>
      <p class="text-lg text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Flexible subscription tailored to your needs. Cancel anytime without fee.' : 'Flexibilní předplatné přizpůsobené vašim potřebám. Zrušte kdykoliv bez poplatku.' }}</p>
    </div>
          </div>

  <!-- Tier 1: M Box - Light background -->
  <div class="border-b border-dark-800/10" style="background-color: #e5e6df;">
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-12 sm:py-16">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
        <!-- Left: Large title + price -->
        <div class="lg:w-1/3">
          <div class="flex items-baseline gap-2">
            <span class="font-display text-7xl sm:text-8xl lg:text-9xl font-normal text-dark-800 leading-none tracking-tight">M</span>
            <span class="font-display text-3xl sm:text-4xl lg:text-5xl font-normal text-dark-800 uppercase">Box</span>
            </div>
          <p class="text-warm-500 uppercase tracking-widest text-sm mt-2">500g · 2 {{ $currentLocale === 'en' ? 'bags' : 'balíčky' }}</p>
            </div>

        <!-- Middle: Features -->
        <div class="lg:w-1/3 space-y-2">
          <p class="text-dark-700 font-light">{{ $currentLocale === 'en' ? '2 types of specialty coffee' : '2 druhy výběrové kávy' }}</p>
          <p class="text-dark-700 font-light">{{ $currentLocale === 'en' ? 'Free shipping' : 'Doprava zdarma' }}</p>
          <p class="text-dark-700 font-light">{{ $currentLocale === 'en' ? 'Cancel or pause anytime' : 'Zrušení nebo přestávka kdykoliv' }}</p>
            </div>

        <!-- Right: Price + CTA -->
        <div class="lg:w-1/3 lg:text-right">
          <div class="flex items-baseline gap-1 lg:justify-end mb-4">
            @if($currentLocale === 'en')
              <span class="font-display text-3xl font-normal text-dark-800">€{{ number_format($subscriptionPricing['2'], 0, '.', ' ') }}</span>
            @else
              <span class="font-display text-3xl font-normal text-dark-800">{{ number_format($subscriptionPricing['2'], 0, ',', ' ') }} Kč</span>
            @endif
            <span class="text-warm-500 font-light">/box</span>
          </div>
          <a href="{{ localizedRoute('subscriptions.index', ['plan' => 2]) }}" class="inline-block border border-dark-800 hover:bg-dark-800 hover:text-white text-dark-800 font-medium px-8 py-3 transition-all duration-200">
            {{ $currentLocale === 'en' ? 'Select M Box' : 'Vybrat M Box' }} →
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Tier 2: L Box - Subtle highlight (Most Popular) -->
  <div class="border-b border-dark-800/10 relative" style="background-color: #e5e6df;">
    <!-- Subtle left accent -->
    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500"></div>
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-12 sm:py-16">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
        <!-- Left: Large title + price -->
        <div class="lg:w-1/3">
          <span class="inline-block bg-primary-500 text-white text-xs font-medium uppercase tracking-widest px-3 py-1 mb-4">
            {{ $currentLocale === 'en' ? 'Most Popular' : 'Nejoblíbenější' }}
          </span>
          <div class="flex items-baseline gap-2">
            <span class="font-display text-7xl sm:text-8xl lg:text-9xl font-normal text-dark-800 leading-none tracking-tight">L</span>
            <span class="font-display text-3xl sm:text-4xl lg:text-5xl font-normal text-dark-800 uppercase">Box</span>
        </div>
          <p class="text-warm-500 uppercase tracking-widest text-sm mt-2">750g · 3 {{ $currentLocale === 'en' ? 'bags' : 'balíčky' }}</p>
          </div>

        <!-- Middle: Features -->
        <div class="lg:w-1/3 space-y-2">
          <p class="text-dark-700 font-light">{{ $currentLocale === 'en' ? '3 types of specialty coffee' : '3 druhy výběrové kávy' }}</p>
          <p class="text-dark-700 font-light">{{ $currentLocale === 'en' ? 'Free shipping' : 'Doprava zdarma' }}</p>
          <p class="text-dark-700 font-light">{{ $currentLocale === 'en' ? 'Cancel or pause anytime' : 'Zrušení nebo přestávka kdykoliv' }}</p>
            </div>

        <!-- Right: Price + CTA -->
        <div class="lg:w-1/3 lg:text-right">
          <div class="flex items-baseline gap-1 lg:justify-end mb-4">
            @if($currentLocale === 'en')
              <span class="font-display text-3xl font-normal text-dark-800">€{{ number_format($subscriptionPricing['3'], 0, '.', ' ') }}</span>
            @else
              <span class="font-display text-3xl font-normal text-dark-800">{{ number_format($subscriptionPricing['3'], 0, ',', ' ') }} Kč</span>
            @endif
            <span class="text-warm-500 font-light">/box</span>
          </div>
          <a href="{{ localizedRoute('subscriptions.index', ['plan' => 3]) }}" class="inline-block bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-3 transition-all duration-200">
            {{ $currentLocale === 'en' ? 'Select L Box' : 'Vybrat L Box' }} →
          </a>
        </div>
      </div>
          </div>
            </div>

  <!-- Tier 3: XL Box -->
  <div style="background-color: #e5e6df;">
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-12 sm:py-16">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
        <!-- Left: Large title + price -->
        <div class="lg:w-1/3">
          <div class="flex items-baseline gap-2">
            <span class="font-display text-7xl sm:text-8xl lg:text-9xl font-normal text-dark-800 leading-none tracking-tight">XL</span>
            <span class="font-display text-3xl sm:text-4xl lg:text-5xl font-normal text-dark-800 uppercase">Box</span>
            </div>
          <p class="text-warm-500 uppercase tracking-widest text-sm mt-2">1000g · 4 {{ $currentLocale === 'en' ? 'bags' : 'balíčky' }}</p>
            </div>

        <!-- Middle: Features -->
        <div class="lg:w-1/3 space-y-2">
          <p class="text-dark-700 font-light">{{ $currentLocale === 'en' ? '3 types of specialty coffee' : '3 druhy výběrové kávy' }}</p>
          <p class="text-dark-700 font-light">{{ $currentLocale === 'en' ? 'Free shipping' : 'Doprava zdarma' }}</p>
          <p class="text-dark-700 font-light">{{ $currentLocale === 'en' ? 'Cancel or pause anytime' : 'Zrušení nebo přestávka kdykoliv' }}</p>
        </div>

        <!-- Right: Price + CTA -->
        <div class="lg:w-1/3 lg:text-right">
          <div class="flex items-baseline gap-1 lg:justify-end mb-4">
            @if($currentLocale === 'en')
              <span class="font-display text-3xl font-normal text-dark-800">€{{ number_format($subscriptionPricing['4'], 0, '.', ' ') }}</span>
            @else
              <span class="font-display text-3xl font-normal text-dark-800">{{ number_format($subscriptionPricing['4'], 0, ',', ' ') }} Kč</span>
            @endif
            <span class="text-warm-500 font-light">/box</span>
          </div>
          <a href="{{ localizedRoute('subscriptions.index', ['plan' => 4]) }}" class="inline-block border border-dark-800 hover:bg-dark-800 hover:text-white text-dark-800 font-medium px-8 py-3 transition-all duration-200">
            {{ $currentLocale === 'en' ? 'Select XL Box' : 'Vybrat XL Box' }} →
          </a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Bottom note -->
  <div class="py-8 border-t border-dark-800/10" style="background-color: #e5e6df;">
    <div class="max-w-screen-xl mx-auto px-4 md:px-8">
      <p class="text-center text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Further customization of your coffee subscription follows in the next step.' : 'Další nastavení vašeho kávového předplatného následuje v dalším kroku.' }}</p>
    </div>
  </div>
</div>

<!-- Testimonials Section - Card Style -->
<div class="relative">
  
  <!-- Top section with cards -->
  <div class="pt-16 sm:pt-20 lg:pt-24" style="background-color: #e5e6df;">
    <div class="max-w-screen-xl mx-auto px-4 md:px-8">
      
      <!-- Section Header -->
      <div class="text-center mb-12 sm:mb-16">
        <!-- <p class="text-xs uppercase tracking-widest text-warm-500 mb-4">{{ $currentLocale === 'en' ? 'Testimonials' : 'Reference' }}</p> -->
        <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 tracking-tight uppercase leading-tight sm:leading-[0.95]">
          {{ $currentLocale === 'en' ? 'What our customers say' : 'Co říkají naši zákazníci' }}
        </h2>
        </div>
        
      <!-- 3 Testimonial Cards -->
      <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Card 1 -->
        <div class="flex flex-col relative z-10">
          <!-- Dark section with label + quote + beak -->
          <div class="bg-dark-800 p-6 sm:p-8 flex-grow relative">
            <p class="text-xs uppercase tracking-widest text-white/50 mb-4">{{ $currentLocale === 'en' ? 'Customer review' : 'Recenze zákazníka' }}</p>
            <p class="text-white text-lg sm:text-xl leading-relaxed font-light">
              "{{ $currentLocale === 'en' ? 'I\'ve been a KAVI subscriber for almost a year and every single coffee delivery has been amazing!' : 'Jsem členkou KAVI předplatného už skoro rok a každá jedna zásilka kávy byla skvělá!' }}"
            </p>
            <!-- Beak/triangle -->
            <div class="absolute -bottom-4 left-6 w-8 h-4 bg-dark-800" style="clip-path: polygon(0 0, 100% 0, 50% 100%);"></div>
            </div>
          <!-- Light section with photo -->
          <div class="p-6 sm:p-8 pt-10 pb-0 flex items-center gap-4">
            <div class="w-20 h-20 sm:w-24 sm:h-24 overflow-hidden flex-shrink-0">
              <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&q=75&fit=crop&w=200" loading="lazy" alt="Eva V." class="h-full w-full object-cover grayscale" />
              </div>
            <div>
              <div class="font-display text-dark-800 uppercase tracking-wide">Eva V.</div>
              <p class="text-sm text-warm-500">{{ $currentLocale === 'en' ? 'Customer for 1 year' : 'Zákaznice 1 rok' }}</p>
        </div>
      </div>
          <!-- Stars - positioned to overlap red bar -->
          <div class="relative z-20 px-6 sm:px-8 mb-[-2rem]">
            <div class="inline-flex px-4 py-3" style="background-color: #e5e6df;">
              <div class="flex gap-0.5 text-lg">
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
    </div>
          </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="flex flex-col relative z-10">
          <!-- Dark section with label + quote + beak -->
          <div class="bg-dark-800 p-6 sm:p-8 flex-grow relative">
            <p class="text-xs uppercase tracking-widest text-white/50 mb-4">{{ $currentLocale === 'en' ? 'Customer review' : 'Recenze zákazníka' }}</p>
            <p class="text-white text-lg sm:text-xl leading-relaxed font-light">
              "{{ $currentLocale === 'en' ? 'Great service and top-notch coffee. The flexibility is great - I can change the quantity anytime.' : 'Skvělý servis a prvotřídní káva. Flexibilita je skvělá - můžu kdykoli změnit množství.' }}"
            </p>
            <!-- Beak/triangle -->
            <div class="absolute -bottom-4 left-6 w-8 h-4 bg-dark-800" style="clip-path: polygon(0 0, 100% 0, 50% 100%);"></div>
          </div>
          <!-- Light section with photo -->
          <div class="p-6 sm:p-8 pt-10 pb-0 flex items-center gap-4">
            <div class="w-20 h-20 sm:w-24 sm:h-24 overflow-hidden flex-shrink-0">
              <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&q=75&fit=crop&w=200" loading="lazy" alt="Petr D." class="h-full w-full object-cover grayscale" />
          </div>
          <div>
              <div class="font-display text-dark-800 uppercase tracking-wide">Petr D.</div>
              <p class="text-sm text-warm-500">{{ $currentLocale === 'en' ? 'Customer for 6 months' : 'Zákazník 6 měsíců' }}</p>
          </div>
        </div>
          <!-- Stars - positioned to overlap red bar -->
          <div class="relative z-20 px-6 sm:px-8 mb-[-2rem]">
            <div class="inline-flex px-4 py-3" style="background-color: #e5e6df;">
              <div class="flex gap-0.5 text-lg">
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
      </div>
          </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="flex flex-col relative z-10">
          <!-- Dark section with label + quote + beak -->
          <div class="bg-dark-800 p-6 sm:p-8 flex-grow relative">
            <p class="text-xs uppercase tracking-widest text-white/50 mb-4">{{ $currentLocale === 'en' ? 'Customer review' : 'Recenze zákazníka' }}</p>
            <p class="text-white text-lg sm:text-xl leading-relaxed font-light">
              "{{ $currentLocale === 'en' ? 'I love tasting coffees from European roasters! The freshness and selection are amazing.' : 'Miluju ochutnávat kávy z evropských pražíren! Čerstvost a výběr jsou skvělé.' }}"
            </p>
            <!-- Beak/triangle -->
            <div class="absolute -bottom-4 left-6 w-8 h-4 bg-dark-800" style="clip-path: polygon(0 0, 100% 0, 50% 100%);"></div>
          </div>
          <!-- Light section with photo -->
          <div class="p-6 sm:p-8 pt-10 pb-0 flex items-center gap-4">
            <div class="w-20 h-20 sm:w-24 sm:h-24 overflow-hidden flex-shrink-0">
              <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&q=75&fit=crop&w=200" loading="lazy" alt="Marie H." class="h-full w-full object-cover grayscale" />
          </div>
          <div>
              <div class="font-display text-dark-800 uppercase tracking-wide">Marie H.</div>
              <p class="text-sm text-warm-500">{{ $currentLocale === 'en' ? 'Customer for 6+ months' : 'Zákaznice 6+ měsíců' }}</p>
          </div>
        </div>
          <!-- Stars - positioned to overlap red bar -->
          <div class="relative z-20 px-6 sm:px-8 mb-[-2rem]">
            <div class="inline-flex px-4 py-3" style="background-color: #e5e6df;">
              <div class="flex gap-0.5 text-lg">
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
                <span class="text-dark-800">★</span>
      </div>
          </div>
          </div>
        </div>

          </div>
      
          </div>
        </div>
  
  <!-- Full-width red bar with CTA - overlaps with stars -->
  <div class="bg-primary-500 pt-16 pb-8 sm:pt-20 sm:pb-10">
    <div class="max-w-screen-xl mx-auto px-4 md:px-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <p class="text-xs uppercase tracking-widest text-dark-800">
        {{ $currentLocale === 'en' ? 'Join our community of coffee lovers' : 'Přidejte se k řadě spokojených milovníků kávy' }}
      </p>
      @if($currentLocale === 'en')
        <a href="https://www.trustpilot.com/review/kavi.cz" target="_blank" rel="noopener" class="text-xs uppercase tracking-widest text-dark-800 hover:text-white transition-colors">
          Trustpilot →
        </a>
      @else
        <a href="https://g.page/r/CUKHHPAV65MnEBM/review" target="_blank" rel="noopener" class="text-xs uppercase tracking-widest text-dark-800 hover:text-white transition-colors">
          Google Reviews →
        </a>
      @endif
    </div>
  </div>
  
</div>

<!-- Coffee of the Month Teaser -->
@if($roasteriesOfMonth->count() > 0 || $coffeesOfMonth->count() > 0)
@php
  $today = now();
  
  // Get billing_date for current month from ShipmentSchedule
  $currentSchedule = \App\Models\ShipmentSchedule::getForMonth($today->year, $today->month);
  
  // Determine display cutoff date (billing_date + 1 day)
  if ($currentSchedule && $currentSchedule->billing_date) {
      $cutoffDate = $currentSchedule->billing_date->copy()->addDay();
  } else {
      // Fallback to 16th if no schedule configured
      $cutoffDate = $today->copy()->day(16);
  }
  
  // If today is on or after cutoff date, show next month
  $displayMonth = $today->greaterThanOrEqualTo($cutoffDate) ? $today->copy()->addMonthNoOverflow() : $today->copy();
  
  // Get month name in nominative case
  $monthsNominative = $currentLocale === 'en' ? [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
  ] : [
    1 => 'leden', 2 => 'únor', 3 => 'březen', 4 => 'duben',
    5 => 'květen', 6 => 'červen', 7 => 'červenec', 8 => 'srpen',
    9 => 'září', 10 => 'říjen', 11 => 'listopad', 12 => 'prosinec'
  ];
  $monthName = $monthsNominative[$displayMonth->month];
  $displayYear = $displayMonth->year;
@endphp

<!-- Coffee of the Month - Editorial Grid Layout -->
<div class="relative pt-20 sm:pt-24 lg:pt-28 pb-12 sm:pb-16 lg:pb-20 overflow-hidden" style="background-color: #e5e6df;">
  
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    
    <!-- Top Section: Heading + Date on left, CTA on right -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 lg:gap-16 mb-8 sm:mb-10">
      
      <!-- Left: Heading + Date -->
      <div>
        <h2 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-normal text-primary-500 leading-[0.9] tracking-tight uppercase">
          {{ $currentLocale === 'en' ? 'Coffee of the Month' : 'Káva měsíce' }}
        </h2>
        <p class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-normal leading-[0.9] tracking-tight uppercase mt-2" style="color: #BCBEB1;">
          {{ $monthName }} {{ $displayYear }}
        </p>
      </div>
      
      <!-- Right: CTA Link -->
      <div class="lg:pt-4">
        <a href="{{ localizedRoute('monthly-feature.index') }}" class="group inline-flex items-center gap-3 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all text-sm">
          <span>{{ $currentLocale === 'en' ? 'Explore coffees for ' . $monthName : 'Prozkoumat kávy na ' . $monthName }}</span>
          <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
    </div>

    </div>
      
    @if($coffeesOfMonth->count() > 0)
    @php
      // Don't shuffle - use consistent order
      $allCoffees = $coffeesOfMonth->take(7);
      $coffeeCount = $allCoffees->count();
      
      // Collect unique roasteries (max 3)
      $roasteries = $allCoffees->pluck('roastery')->filter()->unique('id')->take(3);
        @endphp
        
    <!-- Mobile: Roasteries + Coffee Grid -->
    <div class="lg:hidden">
      <!-- Mobile roasteries with images -->
      <div class="mb-6 grid grid-cols-3 gap-3">
        @foreach($roasteries as $roastery)
        <div class="relative overflow-hidden">
            @if($roastery->image)
          <img src="{{ asset($roastery->image) }}" alt="{{ $roastery->name }}" class="w-full aspect-[4/5] object-cover grayscale" />
            @else
          <div class="w-full aspect-[4/5] bg-warm-300"></div>
            @endif
          <!-- Overlay text at top -->
          <div class="absolute top-0 left-0 right-0 p-2">
            <span class="inline-block bg-primary-500 px-1.5 py-0.5 font-display text-xs font-normal text-black uppercase tracking-tight leading-tight">{{ $roastery->name }}</span>
            <br>
            <span class="inline-block bg-primary-500 px-1.5 py-0.5 text-[10px] text-black uppercase tracking-widest mt-0.5">{{ $roastery->getCountry() ?? '' }}</span>
          </div>
            </div>
        @endforeach
      </div>
      <!-- Mobile coffee grid -->
      <div class="grid grid-cols-3 gap-4">
        @foreach($allCoffees as $index => $coffee)
        @php $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT); @endphp
        <div class="flex flex-col">
          <span class="text-xs text-warm-400 tracking-widest mb-1">{{ $num }}</span>
          <div class="w-full">
            @if($coffee->image)
            <img src="{{ asset($coffee->image) }}" alt="{{ $coffee->name }}" class="w-full h-auto object-contain" />
            @else
            <div class="w-full aspect-[3/4] bg-warm-300 flex items-center justify-center">
              <span class="text-warm-500 font-display text-lg">{{ substr($coffee->name, 0, 1) }}</span>
            </div>
              @endif
          </div>
          <p class="mt-1 text-[10px] font-medium text-dark-800 uppercase tracking-wide leading-tight">{{ $coffee->name }}</p>
        </div>
        @endforeach
      </div>
    </div>
    
    <!-- Desktop: Scattered grid layout -->
    <div class="hidden lg:block">
      <div class="grid grid-cols-10 gap-x-6 gap-y-2">
        
        <!-- Row 1: Coffee 01 + Roastery 1 + Coffee 02 -->
        <div class="col-span-2 col-start-1">
          @php $coffee = $allCoffees->get(0); $num = '01'; @endphp
          @if($coffee)
          <span class="text-xs text-warm-400 tracking-widest mb-2 block">{{ $num }}</span>
              @if($coffee->image)
          <img src="{{ asset($coffee->image) }}" alt="{{ $coffee->name }}" class="w-full h-auto object-contain" />
              @else
          <div class="w-full aspect-[3/4] bg-warm-300"></div>
          @endif
              @endif
            </div>
        
        <!-- Roastery 1 - offset down -->
        @if($roasteries->count() >= 1)
        @php $r1 = $roasteries->first(); @endphp
        <div class="col-span-2 col-start-4 mt-16 relative">
          @if($r1->image)
          <img src="{{ asset($r1->image) }}" alt="{{ $r1->name }}" class="w-full aspect-[4/5] object-cover grayscale" />
        @else
          <div class="w-full aspect-[4/5] bg-warm-300"></div>
              @endif
          <div class="absolute top-0 left-0 right-0 p-3">
            <span class="inline-block bg-primary-500 px-2 py-1 font-display text-base font-normal text-black uppercase tracking-tight leading-tight">{{ $r1->name }}</span>
            <br>
            <span class="inline-block bg-primary-500 px-2 py-0.5 text-xs text-black uppercase tracking-widest mt-1">{{ $r1->getCountry() ?? '' }}</span>
          </div>
            </div>
            @endif
            
        <div class="col-span-2 col-start-7 mt-8">
          @php $coffee = $allCoffees->get(1); $num = '02'; @endphp
          @if($coffee)
          <span class="text-xs text-warm-400 tracking-widest mb-2 block">{{ $num }}</span>
          @if($coffee->image)
          <img src="{{ asset($coffee->image) }}" alt="{{ $coffee->name }}" class="w-full h-auto object-contain" />
              @else
          <div class="w-full aspect-[3/4] bg-warm-300"></div>
              @endif
            @endif
        </div>
        
        <div class="col-span-2 col-start-9">
          @php $coffee = $allCoffees->get(2); $num = '03'; @endphp
          @if($coffee)
          <span class="text-xs text-warm-400 tracking-widest mb-2 block">{{ $num }}</span>
          @if($coffee->image)
          <img src="{{ asset($coffee->image) }}" alt="{{ $coffee->name }}" class="w-full h-auto object-contain" />
              @else
          <div class="w-full aspect-[3/4] bg-warm-300"></div>
              @endif
            @endif
          </div>
        
        <!-- Row 2: Coffee 04 + Roastery 2 + Coffees 05-06 -->
        <div class="col-span-2 col-start-2 -mt-24">
          @php $coffee = $allCoffees->get(3); $num = '04'; @endphp
          @if($coffee)
          <span class="text-xs text-warm-400 tracking-widest mb-2 block">{{ $num }}</span>
          @if($coffee->image)
          <img src="{{ asset($coffee->image) }}" alt="{{ $coffee->name }}" class="w-full h-auto object-contain" />
          @else
          <div class="w-full aspect-[3/4] bg-warm-300"></div>
        @endif
      @endif
    </div>

        <div class="col-span-2 col-start-5 -mt-8">
          @php $coffee = $allCoffees->get(4); $num = '05'; @endphp
          @if($coffee)
          <span class="text-xs text-warm-400 tracking-widest mb-2 block">{{ $num }}</span>
          @if($coffee->image)
          <img src="{{ asset($coffee->image) }}" alt="{{ $coffee->name }}" class="w-full h-auto object-contain" />
          @else
          <div class="w-full aspect-[3/4] bg-warm-300"></div>
          @endif
          @endif
    </div>

        <!-- Roastery 2 -->
        @if($roasteries->count() >= 2)
        @php $r2 = $roasteries->skip(1)->first(); @endphp
        <div class="col-span-2 col-start-8 -mt-32 relative">
          @if($r2->image)
          <img src="{{ asset($r2->image) }}" alt="{{ $r2->name }}" class="w-full aspect-[4/5] object-cover grayscale" />
          @else
          <div class="w-full aspect-[4/5] bg-warm-300"></div>
          @endif
          <div class="absolute top-0 left-0 right-0 p-3">
            <span class="inline-block bg-primary-500 px-2 py-1 font-display text-base font-normal text-black uppercase tracking-tight leading-tight">{{ $r2->name }}</span>
            <br>
            <span class="inline-block bg-primary-500 px-2 py-0.5 text-xs text-black uppercase tracking-widest mt-1">{{ $r2->getCountry() ?? '' }}</span>
  </div>
</div>
@endif

        <!-- Row 3: Coffees 06-07 + Roastery 3 -->
        <div class="col-span-2 col-start-1 -mt-16">
          @php $coffee = $allCoffees->get(5); $num = '06'; @endphp
          @if($coffee)
          <span class="text-xs text-warm-400 tracking-widest mb-2 block">{{ $num }}</span>
          @if($coffee->image)
          <img src="{{ asset($coffee->image) }}" alt="{{ $coffee->name }}" class="w-full h-auto object-contain" />
          @else
          <div class="w-full aspect-[3/4] bg-warm-300"></div>
          @endif
          @endif
  </div>

        <!-- Roastery 3 -->
        @if($roasteries->count() >= 3)
        @php $r3 = $roasteries->skip(2)->first(); @endphp
        <div class="col-span-2 col-start-4 -mt-8 relative">
          @if($r3->image)
          <img src="{{ asset($r3->image) }}" alt="{{ $r3->name }}" class="w-full aspect-[4/5] object-cover grayscale" />
          @else
          <div class="w-full aspect-[4/5] bg-warm-300"></div>
          @endif
          <div class="absolute top-0 left-0 right-0 p-3">
            <span class="inline-block bg-primary-500 px-2 py-1 font-display text-base font-normal text-black uppercase tracking-tight leading-tight">{{ $r3->name }}</span>
            <br>
            <span class="inline-block bg-primary-500 px-2 py-0.5 text-xs text-black uppercase tracking-widest mt-1">{{ $r3->getCountry() ?? '' }}</span>
    </div>
          </div>
        @endif
        
        <div class="col-span-2 col-start-7 -mt-24">
          @php $coffee = $allCoffees->get(6); $num = '07'; @endphp
          @if($coffee)
          <span class="text-xs text-warm-400 tracking-widest mb-2 block">{{ $num }}</span>
          @if($coffee->image)
          <img src="{{ asset($coffee->image) }}" alt="{{ $coffee->name }}" class="w-full h-auto object-contain" />
          @else
          <div class="w-full aspect-[3/4] bg-warm-300"></div>
          @endif
          @endif
        </div>
        
        </div>
      </div>
    @endif
    

        </div>
        </div>
@endif

<!-- How It Works Section - Same style as Features -->
<div class="relative py-20 sm:py-24 md:py-28 lg:py-36 overflow-hidden" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header -->
    <div class="mb-16 sm:mb-20 max-w-2xl">
      <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-4 tracking-tight uppercase leading-tight sm:leading-[0.95]">{{ $currentLocale === 'en' ? 'How it works' : 'Jak to funguje' }}</h2>
      <p class="text-lg sm:text-xl text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Four simple steps to perfect coffee' : 'Čtyři jednoduché kroky k perfektní kávě' }}</p>
          </div>
          
    <!-- Steps Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
      
      <!-- Step 1 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">01</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Choose your plan' : 'Vyberte si plán' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'Select coffee quantity and delivery frequency according to your needs' : 'Zvolte množství kávy a frekvenci dodání podle vašich potřeb' }}</p>
        </div>
        
      <!-- Step 2 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">02</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Personalize' : 'Personalizujte' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'Choose coffee type, brewing method and delivery address' : 'Vyberte typ kávy, způsob přípravy a doručovací adresu' }}</p>
        </div>

      <!-- Step 3 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">03</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Pick up your box' : 'Vyzvedněte si box' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'We deliver freshly roasted coffee to your chosen location' : 'Čerstvě praženou kávu doručíme na vámi vybrané místo' }}</p>
      </div>

      <!-- Step 4 -->
      <div class="group border-t-2 border-primary-500 pt-8">
        <span class="text-primary-500 font-display text-5xl font-normal mb-4 block">04</span>
        <h3 class="mb-3 text-xl font-normal text-dark-800 uppercase tracking-wide">{{ $currentLocale === 'en' ? 'Enjoy' : 'Vychutnejte si' }}</h3>
        <p class="text-warm-500 leading-relaxed font-light">{{ $currentLocale === 'en' ? 'Enjoy great specialty coffee and look forward to your next coffee box' : 'Užijte si skvělou výběrovou kávu a těšte se na další kávový box' }}</p>
          </div>
          
        </div>
    </div>
  </div>
  
<!-- Featured Products Section - Quiet Luxury -->
@if($featuredProducts->count() > 0)
<div class="relative py-20 sm:py-24 md:py-28 lg:py-36" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header -->
    <div class="mb-12 sm:mb-16 flex flex-col md:flex-row items-start md:items-end justify-between gap-6">
      <div>
        <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 mb-2 tracking-tight uppercase leading-tight sm:leading-[0.95]">{{ $currentLocale === 'en' ? 'Our Coffees' : 'Naše kávy' }}</h2>
        <p class="text-lg sm:text-xl text-warm-500 font-light">{{ $currentLocale === 'en' ? 'Hand-picked from the best European roasters' : 'Ručně vybrané z nejlepších pražíren Evropy' }}</p>
      </div>
      <a href="{{ localizedRoute('products.index') }}" class="group inline-flex items-center gap-3 border border-dark-800 hover:bg-dark-800 hover:text-white text-dark-800 font-medium px-6 py-3 transition-all duration-200">
        <span>{{ $currentLocale === 'en' ? 'View more' : 'Zobrazit více' }}</span>
        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
      </a>
    </div>

    <div class="grid gap-6 sm:gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @foreach($featuredProducts->take(4) as $product)
      <!-- product - start -->
      <div class="group">
        <a href="{{ localizedRoute('products.show', $product) }}" class="relative block mb-4 h-80 overflow-hidden bg-warm-200 transition-all duration-300">
          @if($product->image)
          <img src="{{ asset($product->image) }}" loading="lazy" alt="{{ $product->getName() }}" class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105" />
          @else
          <div class="h-full w-full bg-warm-200 flex items-center justify-center p-8">
            <span class="font-display text-3xl text-warm-400">{{ substr($product->getName(), 0, 1) }}</span>
          </div>
          @endif

          @if($product->is_on_sale ?? false)
          <span class="absolute left-0 top-4 bg-primary-500 px-4 py-2 text-xs font-medium uppercase tracking-widest text-white">
            {{ $currentLocale === 'en' ? 'sale' : 'sleva' }}
          </span>
          @endif
        </a>

        <div class="space-y-2">
          <a href="{{ localizedRoute('products.show', $product) }}" class="block">
            <h3 class="text-lg font-normal text-dark-800 group-hover:text-primary-500 transition-colors mb-1">{{ $product->getName() }}</h3>
          </a>
          
          <!-- Roaster/Manufacturer -->
          @if(!empty($product->attributes['roaster']))
          <p class="text-sm text-warm-500 font-light">
            {{ $product->attributes['roaster'] }}
          </p>
          @elseif(!empty($product->attributes['manufacturer']))
          <p class="text-sm text-warm-500 font-light">
            {{ $product->attributes['manufacturer'] }}
          </p>
          @endif

          <div class="flex items-baseline gap-2 pt-1">
            <span class="font-display text-xl font-normal text-dark-800">{{ $product->getFormattedPrice() }}</span>
            @if($product->original_price ?? false)
            <span class="text-sm text-warm-500 line-through">{{ number_format($product->original_price, 0, ',', ' ') }} {{ $currentLocale === 'en' ? '€' : 'Kč' }}</span>
            @endif
          </div>
        </div>
      </div>
      <!-- product - end -->
      @endforeach
    </div>
  </div>
</div>
@endif

<!-- Impact Section - Editorial Magazine Style -->
<div class="relative min-h-[85vh] lg:min-h-[90vh] overflow-hidden">
  
  <!-- Full-bleed Background Image -->
  <div class="absolute inset-0">
    <img src="/images/water-org.jpg" loading="lazy" alt="Water.org" class="h-full w-full object-cover object-center" />
  </div>
  
  <!-- Content Overlay -->
  <div class="relative min-h-[85vh] lg:min-h-[90vh] flex flex-col justify-between p-6 sm:p-8 lg:p-12">
    
    <!-- Top Text - Mission Statement -->
    <div class="max-w-3xl">
      <p class="text-primary-500 text-xs sm:text-sm font-display uppercase leading-relaxed tracking-wide">
            @if($currentLocale === 'en')
        At KAVI, we believe specialty coffee can change the world. From every coffee box, we donate to Water.org, which provides access to clean water in developing countries.
            @else
        V KAVI věříme, že výběrová káva může měnit svět. Z každého kávového boxu věnujeme 5 Kč organizaci Water.org, která zajišťuje přístup k čisté vodě v rozvojových zemích.
            @endif
          </p>
        </div>

    <!-- Center - Large Headline -->
    <div class="my-auto py-8 sm:py-12">
      <h2 class="font-display text-primary-500 text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-normal uppercase leading-[0.95] tracking-tight max-w-3xl">
        @if($currentLocale === 'en')
        Every box provides one person with water for half a year
        @else
        Každý box poskytne jednomu člověku vodu na půl roku
        @endif
      </h2>
      </div>

    <!-- Bottom Bar - Metadata -->
    <div class="flex flex-wrap items-center justify-between gap-4 border-t-2 border-primary-500 pt-4">
      <div class="flex flex-wrap items-center gap-6 sm:gap-10 text-xs uppercase tracking-widest text-primary-500">
        <a href="https://water.org" target="_blank" rel="noopener" class="font-display hover:text-primary-600 transition-colors">Water.org</a>
        <span>{{ $currentLocale === 'en' ? '6 months of water' : '6 měsíců vody' }}</span>
        <span>{{ $currentLocale === 'en' ? 'Direct donation' : 'Přímá podpora' }}</span>
      </div>
      <div class="flex items-center gap-6 sm:gap-10 text-xs uppercase tracking-widest text-primary-500">
        <span>kavi.cz</span>
    </div>
  </div>
  
  </div>
</div>

<!-- Final CTA Section - Terracotta Background -->
<div class="relative py-20 sm:py-24 lg:py-32" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
      <!-- Large Editorial Heading -->
      <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase leading-tight sm:leading-[0.95]">
        {{ $currentLocale === 'en' ? 'Start your coffee journey today' : 'Začněte svou kávovou cestu ještě dnes' }}
      </h2>

      <p class="mb-10 sm:mb-12 text-lg sm:text-xl text-warm-500 max-w-2xl leading-relaxed font-light">
        {{ $currentLocale === 'en' ? 'Get access to the best coffee from all over Europe. Flexible subscription, no commitment.' : 'Získejte přístup k nejlepší kávě z celé Evropy. Flexibilní předplatné, bez závazků.' }}
      </p>

      <!-- CTA Links -->
      <div class="flex flex-col sm:flex-row gap-6 sm:gap-10">
        <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
          <span>{{ $currentLocale === 'en' ? 'Choose subscription' : 'Vybrat předplatné' }}</span>
          <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>

        <a href="{{ localizedRoute('products.index') }}" class="group inline-flex items-center gap-2 text-warm-400 font-display uppercase tracking-widest hover:text-dark-800 transition-all">
          <span>{{ $currentLocale === 'en' ? 'Browse coffees' : 'Procházet kávy' }}</span>
          <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
      </div>
    </div>
  </div>
</div>

</div>
@endsection

