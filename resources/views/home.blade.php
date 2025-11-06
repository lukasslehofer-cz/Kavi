@extends('layouts.app')

@section('title', 'Kávové předplatné | Výběrová káva | KAVI.cz')

@section('content')
<div class="overflow-hidden">

<!-- Hero Section - Clean with Strong Visual -->
<div class="relative h-[90vh] min-h-[600px] max-h-[900px] overflow-hidden bg-gray-50">
    <!-- Background Image/Video -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/images/kavi-intro-video.jpg');"></div>
        
        <style>
            /* Hide play button and video controls on iOS Safari */
            #hero-video {
                pointer-events: none;
            }
            #hero-video::-webkit-media-controls {
                display: none !important;
            }
            #hero-video::-webkit-media-controls-start-playback-button {
                display: none !important;
                -webkit-appearance: none;
            }
        </style>
        
        <video 
            id="hero-video"
            autoplay 
            loop 
            muted 
            playsinline
            preload="auto"
            class="absolute inset-0 w-full h-full object-cover"
        >
            <source src="/images/kavi-intro-video.mp4" type="video/mp4">
        </video>
        
        <script>
            // iOS Safari autoplay fix
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('hero-video');
                if (video) {
                    // Set attributes again to ensure they're recognized
                    video.setAttribute('playsinline', '');
                    video.setAttribute('muted', '');
                    video.muted = true;
                    video.playsInline = true;
                    
                    // Try to play
                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(error => {
                            // Auto-play was prevented, try again on user interaction
                            console.log('Autoplay prevented:', error);
                            document.body.addEventListener('touchstart', function() {
                                video.play();
                            }, { once: true });
                        });
                    }
                }
            });
        </script>
    </div>
    
    <!-- Simple Dark Overlay -->
    <div class="absolute inset-0 bg-gray-900/40"></div>

    <!-- Organic Shape Accent - Top Right -->
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary-500/20 rounded-full blur-3xl"></div>

    <!-- Content -->
    <div class="relative h-full flex items-center px-4 md:px-8">
        <div class="max-w-screen-xl mx-auto w-full">
            <div class="max-w-2xl space-y-8">
                <!-- Small Badge -->
                <div class="inline-flex items-center gap-2 bg-white/95 backdrop-blur-sm rounded-full px-4 py-2">
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                    <span class="text-sm font-medium text-gray-900">Každý měsíc něco nového</span>
                </div>

                <!-- Heading - Clean Typography -->
                <div class="space-y-6">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold leading-tight text-white tracking-tight">
                        Objevte tu nejlepší kávu z celé Evropy
                    </h1>
                    <p class="text-xl md:text-2xl text-white/90 leading-relaxed font-light">
                        Prémiová káva s pravidelným předplatným.<br/>
                        Čerstvě pražená, pečlivě vybraná, doručená přímo k vám.
                    </p>
                </div>

                <!-- CTA Buttons - Minimal -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a href="{{ route('subscriptions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium text-lg px-8 py-4 rounded-full transition-all duration-200">
                        <span>Sestavte si vlastní box</span>
                        <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="{{ route('products.index') }}" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium text-lg px-8 py-4 rounded-full transition-all duration-200">
                        <span>Kávový obchod</span>
                        <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
      </div>

    <!-- Organic Wave Divider -->
    <div class="absolute bottom-[-1px] left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</div>

<!-- Features Section - Minimal & Clean -->
<div class="relative bg-white py-24 sm:py-24 lg:py-32">
  <!-- Organic shape decoration -->
  <div class="absolute top-20 right-0 w-72 h-72 bg-primary-50 rounded-full -translate-y-1/2 translate-x-1/2 opacity-60"></div>
  
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header - Minimal -->
    <div class="mb-20 max-w-2xl">
      <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">Proč si vybrat KAVI?</h2>
      <p class="text-xl text-gray-600 font-light">Víme, co dělá kávu výjimečnou. A rádi se s vámi podělíme.</p>
    </div>

    <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
      <!-- feature - start -->
      <div class="group">
        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-gray-900 text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>

        <h3 class="mb-3 text-2xl font-semibold text-gray-900">Čerstvá káva</h3>
        <p class="text-gray-600 leading-relaxed font-light">Každá pražírna nám dodává čerstvě praženou kávu do vašich kávových boxů.</p>
      </div>
      <!-- feature - end -->

      <!-- feature - start -->
      <div class="group">
        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-primary-500 text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>

        <h3 class="mb-3 text-2xl font-semibold text-gray-900">Výjimečné chutě</h3>
        <p class="text-gray-600 leading-relaxed font-light">Žádné nudné a monotonní kávy. Každý měsíc objevíte nové chutě z různých koutů světa.</p>        
      </div>
      <!-- feature - end -->

      <!-- feature - start -->
      <div class="group">
        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-gray-900 text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
        </div>

        <h3 class="mb-3 text-2xl font-semibold text-gray-900">Doprava zdarma</h3>
        <p class="text-gray-600 leading-relaxed font-light">Doprava zdarma pro všechna předplatná. Ať už si vyberete jakoukoluv velikost boxu.</p>
      </div>
      <!-- feature - end -->
    </div>
  </div>
  
  <!-- Wave Divider -->
  <div class="absolute bottom-[-1px] left-0 right-0">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
      <path d="M0 80L60 75C120 70 240 60 360 55C480 50 600 50 720 53.3C840 56.7 960 63.3 1080 65C1200 66.7 1320 63.3 1380 61.7L1440 60V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#F3F4F6"/>
    </svg>
  </div>
</div>

<!-- Love Your Coffee Section - Minimal with organic shapes -->
<div class="relative bg-gray-100 py-24 sm:py-24 lg:py-32 overflow-hidden">
  <!-- Organic shape decorations -->
  <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-100/50 rounded-full -translate-x-1/3 translate-y-1/3"></div>

  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="grid lg:grid-cols-2 gap-16 items-center">
      <!-- Image Side - Clean -->
      <div class="relative order-2 lg:order-1">
        <div class="relative h-[500px] rounded-3xl overflow-hidden">
          <img src="/images/kavi-box.jpg" loading="lazy" alt="KAVI box" class="h-full w-full object-cover" />
        </div>
        
        <!-- Floating stat card -->
        <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl p-6 shadow-lg">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <div>
              <div class="text-2xl font-bold text-gray-900">98%</div>
              <div class="text-sm text-gray-600">spokojených zákazníků</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Content Side -->
      <div class="space-y-8 order-1 lg:order-2">
        <div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight tracking-tight">
            Káva, kterou budete milovat
          </h2>
          
          <p class="text-xl text-gray-600 leading-relaxed font-light mb-6">
            Pečlivě vybíráme nejkvalitnější kávu z ověřených pražíren. Každý měsíc objevte nové chutě přímo u vás doma.
          </p>
        </div>

        <!-- Benefits List - Minimal -->
        <div class="space-y-5">
          <div class="flex gap-4 items-start">
            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center mt-1">
              <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Nikde v ČR nekoupíte</h3>
              <p class="text-gray-600 font-light">Dodáváme vám kávu z pražíren, které nejsou dostupné v ČR.</p>
            </div>
          </div>

          <div class="flex gap-4 items-start">
            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center mt-1">
              <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Bezkonkurenční cena</h3>
              <p class="text-gray-600 font-light">Kávy v našich boxech jsou výhodnější než kdekoliv jinde.</p>
            </div>
          </div>

          <div class="flex gap-4 items-start">
            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center mt-1">
              <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Flexibilní předplatné</h3>
              <p class="text-gray-600 font-light">Přizpůsobte množství a frekvenci podle svých potřeb.</p>
            </div>
          </div>
        </div>

        <!-- CTA Button -->
        <div class="pt-4">
          <a href="{{ route('subscriptions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-4 rounded-full transition-all duration-200">
            <span>Začít předplatné</span>
            <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Wave Divider -->
  <div class="absolute bottom-[-1px] left-0 right-0">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
      <path d="M0 80L60 75C120 70 240 60 360 55C480 50 600 50 720 53.3C840 56.7 960 63.3 1080 65C1200 66.7 1320 63.3 1380 61.7L1440 60V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="white"/>
    </svg>
  </div>
</div>

<!-- Subscription Plans Section - Minimal & Clean -->
<div class="relative bg-white py-24 sm:py-24 lg:py-32 overflow-hidden">
  <!-- Organic shape decoration -->
  <div class="absolute top-1/2 right-0 w-80 h-80 bg-gray-100 rounded-full translate-x-1/2 -translate-y-1/2"></div>

  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header - Minimal -->
    <div class="mb-20 max-w-2xl mx-auto text-center">
      <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">Vyberte si ideální kávový box</h2>
      <p class="text-xl text-gray-600 font-light">Flexibilní předplatné přizpůsobené vašim potřebám. Zrušte kdykoliv bez poplatku.</p>
    </div>

    <div class="mb-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
      <!-- plan - start -->
      <div class="group flex flex-col bg-white rounded-3xl p-10 border border-gray-200 hover:border-gray-300 transition-all duration-200">
        <div class="mb-8">
          <div class="mb-6">
            <span class="text-xl font-medium text-gray-500 mb-2 block">500g</span>
            <div class="text-5xl font-bold text-gray-900">M Box</div>
          </div>

          <p class="text-gray-600 mb-8 leading-relaxed font-light">Ideální pro jednotlivce nebo domácnosti s&nbsp;menší spotřebou kávy</p>

          <div class="space-y-3">
            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">2 balíčky po 250g</span>
            </div>

            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">2 druhy výběrové kávy</span>
            </div>

            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">Doprava zdarma</span>
            </div>

            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">Zrušení nebo přestávka kdykoliv</span>
            </div>
          </div>
        </div>

        <div class="mt-auto pt-8 border-t border-gray-100">
          <div class="flex items-baseline gap-1 mb-6">
            <span class="text-4xl font-bold text-gray-900">{{ number_format($subscriptionPricing['2'], 0, ',', ' ') }}</span>
            <span class="text-lg text-gray-500 font-light">Kč/box</span>
          </div>

          <a href="{{ route('subscriptions.index', ['plan' => 2]) }}" class="block w-full bg-gray-900 hover:bg-gray-800 text-white font-medium px-6 py-3 rounded-full transition-all duration-200 text-center">
            Vybrat M Box
          </a>
        </div>
      </div>
      <!-- plan - end -->

      <!-- plan - start - POPULAR -->
      <div class="group relative flex flex-col bg-white rounded-3xl p-10 border-2 border-primary-500 transition-all duration-200">
        <!-- Popular Badge -->
          <div class="absolute inset-x-0 -top-3 flex justify-center">
          <span class="px-4 py-1 bg-primary-500 rounded-full text-xs font-medium text-white">
            Nejoblíbenější
          </span>
        </div>

        <div class="mb-8 pt-2">
          <div class="mb-6">
            <span class="text-xl font-medium text-primary-500 mb-2 block">750g</span>
            <div class="text-5xl font-bold text-gray-900">L Box</div>
          </div>

          <p class="text-gray-600 mb-8 leading-relaxed font-light">Nejpopulárnější volba pro milovníky výběrové kávy</p>

          <div class="space-y-3">
            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">3 balíčky po 250g</span>
            </div>

            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">3 druhy výběrové kávy</span>
            </div>

            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">Doprava zdarma</span>
            </div>

            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">Zrušení nebo přestávka kdykoliv</span>
            </div>
            
          </div>
        </div>

        <div class="mt-auto pt-8 border-t border-gray-100">
          <div class="flex items-baseline gap-1 mb-6">
            <span class="text-4xl font-bold text-gray-900">{{ number_format($subscriptionPricing['3'], 0, ',', ' ') }}</span>
            <span class="text-lg text-gray-500 font-light">Kč/box</span>
          </div>

          <a href="{{ route('subscriptions.index', ['plan' => 3]) }}" class="group/btn flex items-center justify-center gap-2 w-full bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200 text-center">
            <span>Vybrat L Box</span>
            <svg class="w-4 h-4 group-hover/btn:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </a>
        </div>
      </div>
      <!-- plan - end -->

      <!-- plan - start -->
      <div class="group flex flex-col bg-white rounded-3xl p-10 border border-gray-200 hover:border-gray-300 transition-all duration-200">
        <div class="mb-8">
          <div class="mb-6">
            <span class="text-xl font-medium text-gray-500 mb-2 block">1000 g</span>
            <div class="text-5xl font-bold text-gray-900">XL Box</div>
          </div>

          <p class="text-gray-600 mb-8 leading-relaxed font-light">Pro kávové nadšence a větší domácnosti</p>

          <div class="space-y-3">
            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">4 balíčky po 250g</span>
            </div>

            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">3 druhy výběrové kávy</span>
            </div>

            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">Doprava zdarma</span>
            </div>

            <div class="flex gap-3 items-center">
              <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-gray-700 font-light">Zrušení nebo přestávka kdykoliv</span>
            </div>
          </div>
        </div>

        <div class="mt-auto pt-8 border-t border-gray-100">
          <div class="flex items-baseline gap-1 mb-6">
            <span class="text-4xl font-bold text-gray-900">{{ number_format($subscriptionPricing['4'], 0, ',', ' ') }}</span>
            <span class="text-lg text-gray-500 font-light">Kč/box</span>
          </div>

          <a href="{{ route('subscriptions.index', ['plan' => 4]) }}" class="block w-full bg-gray-900 hover:bg-gray-800 text-white font-medium px-6 py-3 rounded-full transition-all duration-200 text-center">
            Vybrat XL Box
          </a>
        </div>
      </div>
      <!-- plan - end -->
    </div>

    <div class="text-center text-gray-500 font-light mt-8">Další nastavení vašeho kávového předplatného následuje v dalším kroku.</div>
  </div>
  
  <!-- Wave Divider -->
  <div class="absolute bottom-[-1px] left-0 right-0">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
      <path d="M0 80L60 73C120 66 240 52 360 48C480 44 600 50 720 53C840 56 960 56 1080 58C1200 60 1320 64 1380 66L1440 68V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#F9FAFB"/>
    </svg>
  </div>
</div>

<!-- Testimonials Section - Minimal -->
<div class="relative bg-gray-50 py-24 sm:py-24 lg:py-32">
  <!-- Organic shape decoration -->
  <div class="absolute top-0 left-0 w-72 h-72 bg-primary-50 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
  
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header - Minimal -->
    <div class="mb-20">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 max-w-full">
        <div class="max-w-2xl">
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">Co říkají naši zákazníci</h2>
          <p class="text-xl text-gray-600 font-light">Přidejte se k řadě spokojených milovníků kávy</p>
        </div>
        
        <!-- TrustBox widget - Review Collector -->
        <div class="w-full md:w-auto md:flex-shrink-0">
          <div class="trustpilot-widget" data-locale="cs-CZ" data-template-id="56278e9abfbbba0bdcd568bc" data-businessunit-id="69092043c7aae452ccbb5a2e" data-style-height="52px" data-style-width="100%" data-token="8f005bc4-b948-4d5e-84f8-7a589f14404d">
            <a href="https://www.trustpilot.com/review/kavi.cz" target="_blank" rel="noopener">Trustpilot</a>
          </div>
        </div>
        <!-- End TrustBox widget -->
      </div>
    </div>

    <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
      <!-- quote - start -->
      <div>
        <div class="mb-6">
          <div class="flex gap-1 mb-4">
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
          </div>
          <p class="text-lg text-gray-700 leading-relaxed font-light mb-6">"Jsem členem KAVI předplatného už skoro rok a každá jedna zásilka kávy byla skvělá!"</p>
        </div>

        <div class="flex items-center gap-3">
          <div class="h-12 w-12 overflow-hidden rounded-full">
            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&q=75&fit=crop&w=112" loading="lazy" alt="Jana Nováková" class="h-full w-full object-cover object-center" />
          </div>
          <div>
            <div class="font-semibold text-gray-900">Eva V.</div>
            <p class="text-sm text-gray-500 font-light">Zákaznice 1 rok</p>
          </div>
        </div>
      </div>
      <!-- quote - end -->

      <!-- quote - start -->
      <div>
        <div class="mb-6">
          <div class="flex gap-1 mb-4">
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
          </div>
          <p class="text-lg text-gray-700 leading-relaxed font-light mb-6">"Skvělý servis a prvotřídní káva. Flexibilita předplatného je skvělá - můžu kdykoli změnit množství nebo typ kávy."</p>
        </div>

        <div class="flex items-center gap-3">
          <div class="h-12 w-12 overflow-hidden rounded-full">
            <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&q=75&fit=crop&w=112" loading="lazy" alt="Petr Dvořák" class="h-full w-full object-cover object-center" />
          </div>
          <div>
            <div class="font-semibold text-gray-900">Petr D.</div>
            <p class="text-sm text-gray-500 font-light">Zákazník 6 měsíců</p>
          </div>
        </div>
      </div>
      <!-- quote - end -->

      <!-- quote - start -->
      <div>
        <div class="mb-6">
          <div class="flex gap-1 mb-4">
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-4 h-4 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
          </div>
          <p class="text-lg text-gray-700 leading-relaxed font-light mb-6">"Miluju ochutnávat kávy z Evropských pražíren! Čerstvost kávy a výběr jsou skvělé."</p>
        </div>

        <div class="flex items-center gap-3">
          <div class="h-12 w-12 overflow-hidden rounded-full">
            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&q=75&fit=crop&w=112" loading="lazy" alt="Marie Horáková" class="h-full w-full object-cover object-center" />
          </div>
          <div>
            <div class="font-semibold text-gray-900">Marie H.</div>
            <p class="text-sm text-gray-500 font-light">Zákaznice 6+ měsíců</p>
          </div>
        </div>
      </div>
      <!-- quote - end -->
    </div>
  </div>
  
  <!-- Wave Divider -->
  <div class="absolute bottom-[-1px] left-0 right-0">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
      <path d="M0 80L60 76C120 72 240 64 360 62C480 60 600 64 720 66C840 68 960 68 1080 64C1200 60 1320 52 1380 48L1440 44V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="white"/>
    </svg>
  </div>
</div>

<!-- Coffee of the Month Teaser -->
@if($roasteriesOfMonth->count() > 0 || $coffeesOfMonth->count() > 0)
@php
  $displayMonth = now()->day >= 16 ? now()->addMonthNoOverflow() : now();
  // Get month name in nominative case (Říjen, not října)
  $monthsNominative = [
    1 => 'leden', 2 => 'únor', 3 => 'březen', 4 => 'duben',
    5 => 'květen', 6 => 'červen', 7 => 'červenec', 8 => 'srpen',
    9 => 'září', 10 => 'říjen', 11 => 'listopad', 12 => 'prosinec'
  ];
  $monthName = $monthsNominative[$displayMonth->month];
  $displayYear = $displayMonth->year;
@endphp

<div class="relative bg-white py-24 sm:py-24 lg:py-32">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    
    <!-- Section Header -->
    <div class="mb-16 text-center">
      <div class="inline-flex items-center gap-2 bg-gray-100 px-4 py-2 rounded-full mb-6">
        <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
        <span class="text-sm font-medium text-gray-700" style="text-transform: capitalize;">{{ $monthName }} {{ $displayYear }}</span>
      </div>
      <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">Káva měsíce</h2>
      <p class="text-xl text-gray-600 font-light max-w-2xl mx-auto">Každý měsíc přinášíme výběr káv od vybraných pražíren</p>
    </div>

    <div class="grid lg:grid-cols-2 gap-16">
      
      <!-- Left Side: Roasteries - Zigzag Layout -->
      @if($roasteriesOfMonth->count() > 0)
      <div class="flex flex-col justify-between h-[400px]">
        @foreach($roasteriesOfMonth as $index => $roastery)
        @php
          // Zigzag pattern: 1st and 3rd offset right, 2nd stays left (only on desktop)
          $offsetClass = ($index % 2 === 0) ? 'lg:ml-16' : 'ml-0';
          // Middle roastery (index 1) reversed layout
          $flexDirection = ($index === 1) ? 'flex-row-reverse' : '';
          // Text alignment for middle roastery
          $textAlign = ($index === 1) ? 'text-right' : '';
          $flexJustify = ($index === 1) ? 'justify-end' : '';
        @endphp
        
        <div class="flex items-center gap-6 {{ $offsetClass }} {{ $flexDirection }}">
          <!-- Roastery Image - Clean -->
          <div class="relative w-28 h-28 flex-shrink-0 rounded-2xl overflow-hidden">
            @if($roastery->image)
            <img src="{{ asset($roastery->image) }}" alt="{{ $roastery->name }}" class="w-full h-full object-cover" />
            @else
            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
              <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
              </svg>
            </div>
            @endif
          </div>

          <!-- Roastery Info -->
          <div class="flex-1 min-w-0 {{ $textAlign }}">
            <div class="flex items-center gap-3 mb-2 {{ $flexJustify }}">
              @if($index === 1 && $roastery->country_flag)
              <span class="text-3xl flex-shrink-0">{{ $roastery->country_flag }}</span>
              @endif
              <h3 class="text-3xl font-bold text-gray-900">{{ $roastery->name }}</h3>
              @if($index !== 1 && $roastery->country_flag)
              <span class="text-3xl flex-shrink-0">{{ $roastery->country_flag }}</span>
              @endif
            </div>
            <p class="text-sm text-gray-600 font-light">
              @if($roastery->city && $roastery->country)
                {{ $roastery->city }}, {{ $roastery->country }}
              @elseif($roastery->city)
                {{ $roastery->city }}
              @elseif($roastery->country)
                {{ $roastery->country }}
              @endif
            </p>
          </div>
        </div>
        @endforeach
      </div>
      @endif

      <!-- Right Side: Coffee Grid -->
      @if($coffeesOfMonth->count() > 0)
      @php
        // Shuffle coffees randomly
        $shuffledCoffees = $coffeesOfMonth->shuffle();
        $coffeeCount = $shuffledCoffees->count();
      @endphp
      
      <div class="relative lg:h-[400px]">
        @if($coffeeCount >= 6)
          <!-- Grid 3x2 for 6+ photos -->
          <div class="grid grid-cols-3 gap-3">
            @foreach($shuffledCoffees->take(6) as $coffee)
            <div class="relative aspect-square rounded-2xl overflow-hidden bg-gray-100">
              @if($coffee->image)
              <img src="{{ asset($coffee->image) }}" alt="{{ $coffee->name }}" class="w-full h-full object-cover" />
              @else
              <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                </svg>
              </div>
              @endif
            </div>
            @endforeach
          </div>
        @else
          <!-- Layout for less than 6: one large (2x2) + 2 small (1x1 stacked) -->
          <div class="grid grid-cols-3 gap-3 auto-rows-fr">
            <!-- Large photo (2x2) -->
            @if($shuffledCoffees->count() > 0)
            <div class="col-span-2 row-span-2 relative aspect-square rounded-2xl overflow-hidden bg-gray-100">
              @if($shuffledCoffees[0]->image)
              <img src="{{ asset($shuffledCoffees[0]->image) }}" alt="{{ $shuffledCoffees[0]->name }}" class="w-full h-full object-cover" />
              @else
              <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                </svg>
              </div>
              @endif
            </div>
            @endif
            
            <!-- Small photo 1 (1x1) -->
            @if($shuffledCoffees->count() > 1)
            <div class="relative aspect-square rounded-2xl overflow-hidden bg-gray-100">
              @if($shuffledCoffees[1]->image)
              <img src="{{ asset($shuffledCoffees[1]->image) }}" alt="{{ $shuffledCoffees[1]->name }}" class="w-full h-full object-cover" />
              @else
              <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                </svg>
              </div>
              @endif
            </div>
            @endif
            
            <!-- Small photo 2 (1x1) -->
            @if($shuffledCoffees->count() > 2)
            <div class="relative aspect-square rounded-2xl overflow-hidden bg-gray-100">
              @if($shuffledCoffees[2]->image)
              <img src="{{ asset($shuffledCoffees[2]->image) }}" alt="{{ $shuffledCoffees[2]->name }}" class="w-full h-full object-cover" />
              @else
              <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                </svg>
              </div>
              @endif
            </div>
            @endif
          </div>
        @endif
      </div>
      @endif
    </div>

    <!-- CTA Button -->
    <div class="mt-16 text-center">
      <a href="{{ route('monthly-feature.index') }}" class="group inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-4 rounded-full transition-all duration-200">
        <span>Prozkoumat kávy na {{ $monthName }}</span>
        <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
        </svg>
      </a>
    </div>

  </div>
</div>
@endif

<!-- How It Works Section - 4 Steps with Arrows -->
<div class="relative bg-gray-100 py-24 sm:py-24 lg:py-32 overflow-hidden">
  <!-- Wave Divider Top -->
  <div class="absolute top-[-1px] left-0 right-0 bg-white">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
      <path d="M0 80L60 75C120 70 240 60 360 55C480 50 600 50 720 53.3C840 56.7 960 63.3 1080 65C1200 66.7 1320 63.3 1380 61.7L1440 60V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#f3f4f6"/>
    </svg>
  </div>

  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header -->
    <div class="mb-16 text-center">
      <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">Jak to funguje</h2>
      <p class="text-xl text-gray-600 font-light max-w-2xl mx-auto">Čtyři jednoduché kroky k perfektní kávě</p>
    </div>

    <!-- Steps Grid with Arrows -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 items-start">
      
      <!-- Step 1 -->
      <div class="relative">
        <div class="text-center">
          <!-- Number Circle -->
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-500 text-white text-2xl font-bold mb-6">
            1
          </div>
          
          <!-- Title -->
          <h3 class="text-xl font-bold text-gray-900 mb-3">Vyberte si plán</h3>
          
          <!-- Description -->
          <p class="text-gray-600 font-light leading-relaxed">Zvolte množství kávy a frekvenci dodání podle vašich potřeb</p>
        </div>
        
        <!-- Arrow - Hidden on mobile, visible on large screens between items -->
        <div class="hidden lg:block absolute top-8 -right-4 text-gray-400">
          <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
          </svg>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="relative">
        <div class="text-center">
          <!-- Number Circle -->
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-500 text-white text-2xl font-bold mb-6">
            2
          </div>
          
          <!-- Title -->
          <h3 class="text-xl font-bold text-gray-900 mb-3">Personalizujte</h3>
          
          <!-- Description -->
          <p class="text-gray-600 font-light leading-relaxed">Vyberte typ kávy, způsob přípravy a doručovací adresu</p>
        </div>
        
        <!-- Arrow -->
        <div class="hidden lg:block absolute top-8 -right-4 text-gray-400">
          <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
          </svg>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="relative">
        <div class="text-center">
          <!-- Number Circle -->
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-500 text-white text-2xl font-bold mb-6">
            3
          </div>
          
          <!-- Title -->
          <h3 class="text-xl font-bold text-gray-900 mb-3">Vyzvedněte si box</h3>
          
          <!-- Description -->
          <p class="text-gray-600 font-light leading-relaxed">Čerstvě praženou kávu doručíme na vámi vybrané místo</p>
        </div>
        
        <!-- Arrow -->
        <div class="hidden lg:block absolute top-8 -right-4 text-gray-400">
          <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
          </svg>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="relative">
        <div class="text-center">
          <!-- Number Circle -->
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-500 text-white text-2xl font-bold mb-6">
            4
          </div>
          
          <!-- Title -->
          <h3 class="text-xl font-bold text-gray-900 mb-3">Vychutnejte si</h3>
          
          <!-- Description -->
          <p class="text-gray-600 font-light leading-relaxed">Užijte si skvělou výběrovou kávu a těšte se na další kávový box</p>
        </div>
      </div>

    </div>
  </div>
  
  <!-- Wave Divider Bottom -->
  <div class="absolute bottom-[-1px] left-0 right-0 bg-gray-100">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
      <path d="M0 80L60 75C120 70 240 60 360 55C480 50 600 50 720 53.3C840 56.7 960 63.3 1080 65C1200 66.7 1320 63.3 1380 61.7L1440 60V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="white"/>
    </svg>
  </div>
</div>

<!-- Featured Products Section - Minimal -->
@if($featuredProducts->count() > 0)
<div class="relative bg-gray-50 py-24 sm:py-24 lg:py-32">
  <!-- Organic shape decoration -->
  <div class="absolute top-0 right-0 w-80 h-80 bg-primary-50 rounded-full translate-x-1/2 -translate-y-1/2"></div>
  
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header - Minimal -->
    <div class="mb-16 flex flex-col md:flex-row items-start md:items-end justify-between gap-6">
      <div>
        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 tracking-tight">Naše kávy</h2>
        <p class="text-xl text-gray-600 font-light">Ručně vybrané z nejlepších pražíren Evropy</p>
      </div>
      <a href="{{ route('products.index') }}" class="group inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
        <span>Zobrazit více</span>
        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </a>
    </div>

    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @foreach($featuredProducts->take(4) as $product)
      <!-- product - start -->
      <div class="group">
        <a href="{{ route('products.show', $product) }}" class="relative block mb-4 h-80 overflow-hidden rounded-2xl bg-gray-100 shadow-xl hover:shadow-2xl transition-all duration-300">
          @if($product->image)
          <img src="{{ asset($product->image) }}" loading="lazy" alt="{{ $product->name }}" class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-110" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          @else
          <div class="h-full w-full bg-gradient-to-br from-primary-100 to-pink-100 flex items-center justify-center p-8">
            <div class="text-center">
              <svg class="w-16 h-16 text-amber-400 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
              </svg>
              <p class="text-sm font-semibold text-primary-600">{{ $product->name }}</p>
            </div>
          </div>
          @endif

          @if($product->is_on_sale ?? false)
          <span class="absolute left-4 top-4 flex items-center gap-2 rounded-full bg-gradient-to-r from-red-500 to-red-600 px-4 py-2 text-sm font-bold uppercase tracking-wider text-white shadow-lg">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
            </svg>
            sleva
          </span>
          @endif

          <!-- Quick Add Button (shown on hover) -->
          <div class="absolute inset-x-0 bottom-0 p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
            <button class="w-full bg-white hover:bg-amber-50 text-gray-900 font-bold py-3 px-4 rounded-xl shadow-lg backdrop-blur-sm flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
              Zobrazit detail
            </button>
          </div>
        </a>

        <div class="space-y-2">
          <a href="{{ route('products.show', $product) }}" class="block">
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-1">{{ $product->name }}</h3>
          </a>
          
          <!-- Roaster/Manufacturer -->
          @if(!empty($product->attributes['roaster']))
          <p class="text-sm text-gray-500 font-medium flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            {{ $product->attributes['roaster'] }}
          </p>
          @elseif(!empty($product->attributes['manufacturer']))
          <p class="text-sm text-gray-500 font-medium flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            {{ $product->attributes['manufacturer'] }}
          </p>
          @endif

          <div class="flex items-baseline gap-2 pt-1">
            <span class="text-2xl font-black text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} Kč</span>
            @if($product->original_price ?? false)
            <span class="text-sm text-gray-500 line-through">{{ number_format($product->original_price, 0, ',', ' ') }} Kč</span>
            @endif
          </div>
        </div>
      </div>
      <!-- product - end -->
      @endforeach
    </div>
  </div>
  
  <!-- Wave Divider -->
  <div class="absolute bottom-[-1px] left-0 right-0">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
      <path d="M0 80L60 72C120 64 240 48 360 44C480 40 600 48 720 52C840 56 960 56 1080 58C1200 60 1320 64 1380 66L1440 68V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="white"/>
    </svg>
  </div>
</div>
@endif

<!-- Impact Section - Minimal -->
<div class="relative bg-white py-24 sm:py-24 lg:py-32">
  <!-- Organic shape decoration -->
  <div class="absolute top-0 left-0 w-72 h-72 bg-primary-50 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
  
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="grid lg:grid-cols-2 gap-16 items-center">
      <!-- Content Side -->
      <div class="space-y-8">
        <div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight tracking-tight">
            Káva s pozitivním dopadem
          </h2>
          <p class="text-xl text-gray-600 leading-relaxed font-light mb-8">
            V KAVI věříme, že výběrová káva může měnit svět. Z každého kávového boxu proto věnujeme 5 Kč organizaci <a href="https://water.org" target="_blank" class="text-primary-500 hover:text-primary-600 underline">Water.org</a>, která zajišťuje přístup k čisté vodě v rozvojových zemích. <b>Každý box tak poskytne jednomu člověku vodu na půl roku</b. Když si vychutnáváte naši kávu, pomáháte zároveň lidem po celém světě získat to nejzákladnější – bezpečnou vodu.
          </p>
        </div>

        <!-- Stats - Minimal -->
        <div class="grid grid-cols-2 gap-8">
          <div>
            <div class="text-4xl font-bold text-gray-900 mb-1">6 měsíců</div>
            <div class="text-sm text-gray-600 font-light">vody za jeden box</div>
          </div>
          <div>
            <div class="text-4xl font-bold text-gray-900 mb-1">Water.org</div>
            <div class="text-sm text-gray-600 font-light">darujeme napřímo</div>
          </div>
        </div>
      </div>

      <!-- Image Side -->
      <div class="relative h-[500px] rounded-3xl overflow-hidden">
        <img src="/images/water-org.jpg" loading="lazy" alt="Water.org" class="h-full w-full object-cover object-center" />
      </div>
    </div>
  </div>
  
  <!-- Wave Divider -->
  <div class="absolute bottom-[-1px] left-0 right-0">
    <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
      <path d="M0 80L60 70C120 60 240 40 360 32C480 24 600 28 720 36C840 44 960 56 1080 62C1200 68 1320 68 1380 68L1440 68V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#F3F4F6"/>
    </svg>
  </div>
</div>

<!-- Final CTA Section - Clean Minimal -->
<div class="relative bg-gray-100 py-20 lg:py-24">
  <!-- Subtle organic shape decoration -->
  <div class="absolute top-0 right-0 w-96 h-96 bg-primary-100 rounded-full translate-x-1/2 -translate-y-1/2"></div>

  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-2xl flex-col items-center text-center">
      <!-- Heading -->
      <h2 class="mb-6 text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight tracking-tight">
        Začněte svou kávovou cestu ještě dnes
      </h2>

      <p class="mb-10 text-lg text-gray-600 max-w-xl leading-relaxed font-light">
        Získejte přístup k nejlepší kávě z celé Evropy. Flexibilní předplatné, bez závazků.
      </p>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('subscriptions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-3 rounded-full transition-all duration-200">
          <span>Vybrat předplatné</span>
          <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </a>

        <a href="{{ route('products.index') }}" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium px-8 py-3 rounded-full border border-gray-200 transition-all duration-200">
          <span>Procházet kávy</span>
        </a>
      </div>

      
    </div>
  </div>
</div>

</div>
@endsection

