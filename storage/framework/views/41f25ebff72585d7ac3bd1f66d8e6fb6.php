<?php $__env->startSection('title', 'Kavi Coffee - Prémiová káva s předplatným'); ?>

<?php $__env->startSection('content'); ?>

<!-- Hero Section - Modern Dynamic -->
<div class="relative bg-gradient-to-br from-gray-50 via-slate-50 to-gray-100 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-br from-primary-300/10 to-pink-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-[36rem] h-[36rem] bg-gradient-to-tr from-primary-300/10 to-pink-400/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-screen-2xl px-4 md:px-8 py-16 md:py-24 lg:py-32">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="text-center lg:text-left space-y-8 animate-fade-in">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-100 to-pink-100 border border-primary-200 rounded-full px-4 py-2 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    <span class="text-sm font-semibold text-gray-700">Nově praženo každý týden</span>
                </div>

                <!-- Heading -->
                <div class="space-y-4">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold leading-tight">
                        <span class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 bg-clip-text text-transparent">Objevte tu nejlepší</span>
                        <span class="block bg-gradient-to-r from-primary-600 via-pink-600 to-primary-700 bg-clip-text text-transparent animate-gradient">kávu z celé Evropy</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-600 max-w-2xl mx-auto lg:mx-0">
                        Prémiová káva s měsíčním předplatným. Čerstvě pražená, pečlivě vybraná, doručená přímo k vám domů.
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="group relative inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <span>Sestavte si vlastní box</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="<?php echo e(route('products.index')); ?>" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-semibold px-8 py-4 rounded-xl shadow-md hover:shadow-lg border-2 border-gray-200 hover:border-gray-300 transform hover:-translate-y-0.5 transition-all duration-200">
                        <span>Prozkoumat kávy</span>
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- Social Proof -->
                <div class="flex items-center gap-8 justify-center lg:justify-start pt-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900">2500+</div>
                        <div class="text-sm text-gray-600">Spokojených zákazníků</div>
                    </div>
                    <div class="w-px h-12 bg-gray-300"></div>
                    <div class="text-center">
                        <div class="flex items-center gap-1 justify-center">
                            <span class="text-3xl font-bold text-gray-900">4.9</span>
                            <svg class="w-6 h-6 text-primary-500 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        </div>
                        <div class="text-sm text-gray-600">Průměrné hodnocení</div>
                    </div>
                </div>
            </div>

            <!-- Right Image -->
            <div class="relative lg:h-[600px] h-96">
                <!-- Main Image -->
                <div class="absolute inset-0 rounded-3xl overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-500">
                    <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?ixlib=rb-4.1.0&auto=format&fit=crop&q=80&w=1200" alt="Premium Coffee" class="w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                </div>
                
                <!-- Floating Card 1 -->
                <div class="absolute top-8 right-8 bg-white rounded-2xl shadow-xl p-4 backdrop-blur-sm bg-white/95 transform hover:scale-110 transition-transform duration-300">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">100% Arabica</div>
                            <div class="text-xs text-gray-600">Prémiová kvalita</div>
                        </div>
                    </div>
                </div>

                <!-- Floating Card 2 -->
                <div class="absolute bottom-8 left-8 bg-white rounded-2xl shadow-xl p-4 backdrop-blur-sm bg-white/95 transform hover:scale-110 transition-transform duration-300">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">Čerstvé pražení</div>
                            <div class="text-xs text-gray-600">Každý týden</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="bg-gradient-to-b from-white via-gray-50 to-white py-20 sm:py-24 lg:py-28">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header -->
    <div class="text-center mb-16">
      <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Proč si vybrat Kavi?</h2>
      <p class="text-xl text-gray-600 max-w-2xl mx-auto">Víme, co dělá kávu výjimečnou. Každý detail je pečlivě promyšlený.</p>
    </div>

    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
      <!-- feature - start -->
      <div class="group relative bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-gray-200 hover:border-primary-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-400/10 to-emerald-500/10 rounded-bl-full"></div>
        <div class="relative">
          <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-green-400 to-emerald-500 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>

          <h3 class="mb-3 text-xl font-bold text-gray-900">Čerstvá káva</h3>
          <p class="text-gray-600 leading-relaxed">Praženo na objednávku, maximálně 7 dní před expedicí. Vždy čerstvá, vždy výjimečná.</p>
        </div>
      </div>
      <!-- feature - end -->

      <!-- feature - start -->
      <div class="group relative bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-gray-200 hover:border-primary-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary-400/10 to-pink-500/10 rounded-bl-full"></div>
        <div class="relative">
          <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-400 to-pink-500 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>

          <h3 class="mb-3 text-xl font-bold text-gray-900">Prémiová kvalita</h3>
          <p class="text-gray-600 leading-relaxed">100% Arabica z ověřených plantáží. Každá šarže kávy prochází pečlivou kontrolou kvality.</p>        
        </div>
      </div>
      <!-- feature - end -->

      <!-- feature - start -->
      <div class="group relative bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-gray-200 hover:border-primary-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/10 to-indigo-500/10 rounded-bl-full"></div>
        <div class="relative">
          <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </div>

          <h3 class="mb-3 text-xl font-bold text-gray-900">Doprava zdarma</h3>
          <p class="text-gray-600 leading-relaxed">Doprava zdarma při objednávce nad 1000 Kč. Káva dorazí přímo k vašim dveřím.</p>
        </div>
      </div>
      <!-- feature - end -->
    </div>
  </div>
</div>

<!-- Love Your Coffee Section -->
<div class="relative bg-gradient-to-br from-gray-50 via-slate-50 to-gray-50 py-20 sm:py-24 lg:py-28 overflow-hidden">
  <!-- Background Decoration -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-br from-primary-300/10 to-pink-400/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-gradient-to-tr from-gray-300/15 to-slate-300/15 rounded-full blur-3xl"></div>
  </div>

  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <!-- Image Side with Coffee Montage -->
      <div class="relative order-2 lg:order-1">
        <div class="grid grid-cols-2 gap-4">
          <!-- Main Large Image -->
          <div class="col-span-2 relative h-100 rounded-3xl overflow-hidden shadow-2xl group">
            <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&q=80&fit=crop&w=800" loading="lazy" alt="Kávová zrna" class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-700" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
            
            <!-- Floating Badge on Image -->
            <div class="absolute bottom-6 left-6 right-6">
              <div class="bg-white/95 backdrop-blur-sm rounded-2xl p-4 shadow-xl">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center">
                      <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                      </svg>
                    </div>
                    <div>
                      <div class="font-black text-gray-900">98% spokojených</div>
                      <div class="text-sm text-gray-600">zákazníků</div>
                    </div>
                  </div>
                  <div class="flex gap-1">
                    <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                    <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                    <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                    <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                    <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Two Smaller Images -->
          <div class="relative h-48 rounded-2xl overflow-hidden shadow-lg group">
            <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&q=80&fit=crop&w=400" loading="lazy" alt="Káva" class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
          </div>
          <div class="relative h-48 rounded-2xl overflow-hidden shadow-lg group">
            <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&q=80&fit=crop&w=400" loading="lazy" alt="Káva" class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
          </div>
        </div>
      </div>

      <!-- Content Side -->
      <div class="space-y-8 order-1 lg:order-2">
        <div>
          <div class="inline-flex items-center gap-2 bg-white border border-primary-200 rounded-full px-4 py-2 shadow-sm mb-6">
            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
            </svg>
            <span class="text-sm font-semibold text-primary-900">Prémiová kvalita</span>
          </div>
          
          <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-6 leading-tight">
            Káva, kterou budete <span class="bg-gradient-to-r from-primary-500 to-pink-600 bg-clip-text text-transparent">milovat</span>
          </h2>
          
          <p class="text-xl text-gray-700 leading-relaxed mb-6">
            Pečlivě vybíráme nejkvalitnější kávu z ověřených pražíren. Každý měsíc objevte nové chutě přímo u vás doma.
          </p>

          <p class="text-lg text-gray-600 leading-relaxed">
            Naše káva je čerstvě pražená, eticky získaná a dodávaná s láskou. Žádné kompromisy, jen ta nejlepší chuť v každé šálce.
          </p>
        </div>

        <!-- Benefits List -->
        <div class="space-y-4">
          <div class="flex gap-4 items-start">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div>
              <h3 class="font-bold text-gray-900 mb-1">Měsíční překvapení</h3>
              <p class="text-gray-600">Každý měsíc nové druhy kávy z různých koutů světa</p>
            </div>
          </div>

          <div class="flex gap-4 items-start">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div>
              <h3 class="font-bold text-gray-900 mb-1">Praženo čerstvě</h3>
              <p class="text-gray-600">Maximálně 7 dní před expedicí k vám domů</p>
            </div>
          </div>

          <div class="flex gap-4 items-start">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <div>
              <h3 class="font-bold text-gray-900 mb-1">Flexibilní předplatné</h3>
              <p class="text-gray-600">Přizpůsobte množství a frekvenci podle svých potřeb</p>
            </div>
          </div>
        </div>

        <!-- CTA Button -->
        <div class="pt-4">
          <a href="<?php echo e(route('subscriptions.index')); ?>" class="group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-10 py-5 rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-200">
            <span class="text-lg">Začít předplatné</span>
            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Subscription Plans Section -->
<div class="relative bg-gradient-to-b from-white via-gray-50 to-white py-20 sm:py-24 lg:py-28 overflow-hidden">
  <!-- Background Decoration -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-1/4 right-0 w-96 h-96 bg-gradient-to-br from-primary-200/10 to-pink-300/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/4 left-0 w-96 h-96 bg-gradient-to-tr from-gray-200/20 to-slate-200/20 rounded-full blur-3xl"></div>
  </div>

  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header -->
    <div class="text-center mb-16">
      <div class="inline-block mb-4 px-4 py-2 bg-gradient-to-r from-primary-100 to-pink-100 rounded-full">
        <span class="text-sm font-semibold text-primary-900">Předplatné</span>
      </div>
      <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Vyberte si ideální kávový box</h2>
      <p class="text-xl text-gray-600 max-w-2xl mx-auto">Flexibilní plány přizpůsobené vašim potřebám. Zrušte kdykoliv bez poplatku.</p>
    </div>

    <div class="mb-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
      <!-- plan - start -->
      <div class="group relative flex flex-col bg-white rounded-3xl p-8 border-2 border-gray-200 hover:border-primary-300 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">        
        
        <div class="mb-8">
          <div class="mb-4 text-center">
            <span class="inline-block px-4 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold mb-4">Starter</span>
            <div class="text-5xl font-black bg-gradient-to-r from-primary-500 to-pink-600 bg-clip-text text-transparent">500g</div>
          </div>

          <p class="text-center text-gray-600 mb-8 leading-relaxed">Ideální pro jednotlivce nebo páry, které si chtějí vychutnat kvalitní kávu</p>

          <div class="space-y-4">
            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-700 font-medium">2 balíčky po 250g</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-700 font-medium">2 druhy prémiové kávy</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-700 font-medium">Doprava zdarma</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-700 font-medium">Zrušení kdykoliv</span>
            </div>
            <!-- check - end -->
          </div>
        </div>

        <div class="mt-auto pt-8 border-t border-gray-200">
          <div class="flex items-baseline justify-center gap-2 mb-6">
            <span class="text-5xl font-black text-gray-900"><?php echo e(number_format($subscriptionPricing['2'], 0, ',', ' ')); ?></span>
            <span class="text-xl text-gray-600 font-medium">Kč/box</span>
          </div>

          <a href="<?php echo e(route('subscriptions.index')); ?>" class="group/btn relative block w-full bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-center overflow-hidden">
            <span class="relative z-10">Vybrat plán</span>
            <div class="absolute inset-0 bg-gradient-to-r from-primary-500 to-pink-600 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
          </a>
        </div>
      </div>
      <!-- plan - end -->

      <!-- plan - start - POPULAR -->
      <div class="group relative flex flex-col bg-gradient-to-br from-white via-gray-50 to-white rounded-3xl p-8 border-4 border-primary-500 shadow-2xl hover:shadow-3xl transition-all duration-300 transform scale-105 hover:scale-110">
        <!-- Popular Badge -->
        <div class="absolute inset-x-0 -top-4 flex justify-center">
          <span class="flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-primary-500 to-pink-500 rounded-full text-sm font-bold uppercase tracking-wider text-white shadow-lg">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            Nejoblíbenější
          </span>
        </div>

        <div class="mb-8 relative z-10">
          <div class="mb-4 text-center pt-4">
            <span class="inline-block px-4 py-1 bg-gradient-to-r from-primary-500 to-pink-500 text-white rounded-full text-sm font-bold mb-4">Popular</span>
            <div class="text-6xl font-black text-gray-900">750g</div>
          </div>

          <p class="text-center text-gray-700 font-medium mb-8 leading-relaxed">Nejpopulárnější volba pro pravidelné milovníky kávy</p>

          <div class="space-y-4">
            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-800 font-semibold">3 balíčky po 250g</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-800 font-semibold">3 druhy prémiové kávy</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-800 font-semibold">Doprava zdarma</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-800 font-semibold">Zrušení kdykoliv</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-800 font-semibold">Prioritní podpora</span>
            </div>
            <!-- check - end -->
          </div>
        </div>

        <div class="mt-auto pt-8 border-t-2 border-gray-200">
          <div class="flex items-baseline justify-center gap-2 mb-6">
            <span class="text-5xl font-black bg-gradient-to-r from-primary-500 to-pink-600 bg-clip-text text-transparent"><?php echo e(number_format($subscriptionPricing['3'], 0, ',', ' ')); ?></span>
            <span class="text-xl text-gray-700 font-bold">Kč/box</span>
          </div>

          <a href="<?php echo e(route('subscriptions.index')); ?>" class="group/btn relative block w-full bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-8 py-4 rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 transition-all duration-200 text-center overflow-hidden">
            <span class="relative z-10 flex items-center justify-center gap-2">
              Vybrat plán
              <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </span>
          </a>
        </div>
      </div>
      <!-- plan - end -->

      <!-- plan - start -->
      <div class="group relative flex flex-col bg-white rounded-3xl p-8 border-2 border-gray-200 hover:border-indigo-300 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">        
        
        <div class="mb-8">
          <div class="mb-4 text-center">
            <span class="inline-block px-4 py-1 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-semibold mb-4">Premium</span>
            <div class="text-5xl font-black bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">1000g</div>
          </div>

          <p class="text-center text-gray-600 mb-8 leading-relaxed">Pro kávové nadšence a větší domácnosti</p>

          <div class="space-y-4">
            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-700 font-medium">4 balíčky po 250g</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-700 font-medium">4 druhy prémiové kávy</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-700 font-medium">Doprava zdarma</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-700 font-medium">Zrušení kdykoliv</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-3 items-start">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-700 font-medium">VIP podpora</span>
            </div>
            <!-- check - end -->
          </div>
        </div>

        <div class="mt-auto pt-8 border-t border-gray-200">
          <div class="flex items-baseline justify-center gap-2 mb-6">
            <span class="text-5xl font-black text-gray-900"><?php echo e(number_format($subscriptionPricing['4'], 0, ',', ' ')); ?></span>
            <span class="text-xl text-gray-600 font-medium">Kč/box</span>
          </div>

          <a href="<?php echo e(route('subscriptions.index')); ?>" class="group/btn relative block w-full bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-center overflow-hidden">
            <span class="relative z-10">Vybrat plán</span>
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
          </a>
        </div>
      </div>
      <!-- plan - end -->
    </div>

    <div class="text-center text-sm text-gray-500 sm:text-base">Další nastavení vašeho kávového předplatného následuje v dalším kroku.</div>
  </div>
</div>

<!-- Testimonials Section -->
<div class="bg-gradient-to-b from-white via-gray-50 to-white py-20 sm:py-24 lg:py-28">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header -->
    <div class="text-center mb-16">
      <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Co říkají naši zákazníci</h2>
      <p class="text-xl text-gray-600 max-w-2xl mx-auto">Přidejte se k tisícům spokojených milovníků kávy</p>
    </div>

    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
      <!-- quote - start -->
      <div class="group relative bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-gray-200 hover:border-primary-300">
        <!-- Quote Icon -->
        <div class="absolute -top-4 left-8">
          <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center shadow-lg">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
            </svg>
          </div>
        </div>

        <div class="mt-6 mb-6">
          <!-- Stars -->
          <div class="flex gap-1 mb-4">
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
          </div>
          <p class="text-gray-700 leading-relaxed italic">"Nejlepší káva, kterou jsem kdy měla! Pravidelné dodávky znamenají, že nikdy nedojdu a kvalita je vždy konzistentní."</p>
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
          <div class="h-14 w-14 overflow-hidden rounded-full ring-2 ring-primary-200">
            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&q=75&fit=crop&w=112" loading="lazy" alt="Jana Nováková" class="h-full w-full object-cover object-center" />
          </div>
          <div>
            <div class="font-bold text-gray-900">Jana Nováková</div>
            <p class="text-sm text-gray-600">Zákaznice 2+ roky</p>
          </div>
        </div>
      </div>
      <!-- quote - end -->

      <!-- quote - start -->
      <div class="group relative bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-gray-200 hover:border-primary-300">
        <!-- Quote Icon -->
        <div class="absolute -top-4 left-8">
          <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-lg">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
            </svg>
          </div>
        </div>

        <div class="mt-6 mb-6">
          <!-- Stars -->
          <div class="flex gap-1 mb-4">
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
          </div>
          <p class="text-gray-700 leading-relaxed italic">"Skvělý servis a prvotřídní káva. Flexibilita předplatného je skvělá - můžu kdykoli změnit množství nebo typ kávy."</p>
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
          <div class="h-14 w-14 overflow-hidden rounded-full ring-2 ring-blue-200">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&q=75&fit=crop&w=112" loading="lazy" alt="Petr Dvořák" class="h-full w-full object-cover object-center" />
          </div>
          <div>
            <div class="font-bold text-gray-900">Petr Dvořák</div>
            <p class="text-sm text-gray-600">Zákazník 1+ rok</p>
          </div>
        </div>
      </div>
      <!-- quote - end -->

      <!-- quote - start -->
      <div class="group relative bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-gray-200 hover:border-primary-300">
        <!-- Quote Icon -->
        <div class="absolute -top-4 left-8">
          <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center shadow-lg">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
            </svg>
          </div>
        </div>

        <div class="mt-6 mb-6">
          <!-- Stars -->
          <div class="flex gap-1 mb-4">
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            <svg class="w-5 h-5 text-primary-500 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
          </div>
          <p class="text-gray-700 leading-relaxed italic">"Konečně káva, která chutná jako z kavárny! Čerstvost je znát a výběr různých druhů je skvělý."</p>
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
          <div class="h-14 w-14 overflow-hidden rounded-full ring-2 ring-green-200">
            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&q=75&fit=crop&w=112" loading="lazy" alt="Marie Horáková" class="h-full w-full object-cover object-center" />
          </div>
          <div>
            <div class="font-bold text-gray-900">Marie Horáková</div>
            <p class="text-sm text-gray-600">Zákaznice 6+ měsíců</p>
          </div>
        </div>
      </div>
      <!-- quote - end -->
    </div>
  </div>
</div>

<!-- Coffee Origins Section -->
<div class="relative bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 py-20 sm:py-24 lg:py-28 overflow-hidden">
  <!-- Background Decorative Elements -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute top-1/4 -right-32 w-96 h-96 bg-gradient-to-br from-primary-300/10 to-pink-400/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/4 -left-32 w-96 h-96 bg-gradient-to-tr from-slate-300/15 to-gray-300/15 rounded-full blur-3xl"></div>
  </div>

  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <!-- Image Side -->
      <div class="relative h-96 lg:h-[600px] rounded-3xl overflow-hidden shadow-2xl group">
        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&q=80&fit=crop&w=1200" alt="Káva pozadí" class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-700" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
        
        <!-- Floating Stats -->
        <div class="absolute bottom-8 left-8 right-8">
          <div class="grid grid-cols-3 gap-4">
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 text-center border border-white/20">
              <div class="text-2xl font-black text-white mb-1">12+</div>
              <div class="text-xs text-white/90">Zemí původu</div>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 text-center border border-white/20">
              <div class="text-2xl font-black text-white mb-1">50+</div>
              <div class="text-xs text-white/90">Druhů kávy</div>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 text-center border border-white/20">
              <div class="text-2xl font-black text-white mb-1">15+</div>
              <div class="text-xs text-white/90">Pražíren</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Content Side -->
      <div class="space-y-8">
        <div>
          <div class="inline-block mb-4 px-4 py-2 bg-gradient-to-r from-primary-100 to-pink-100 rounded-full">
            <span class="text-sm font-semibold text-primary-900">Naše káva</span>
          </div>
          <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
            Objevte svět prémiové kávy
          </h2>
          <p class="text-xl text-gray-600 leading-relaxed">
            Káva, která vás nadchne. Importujeme pouze nejkvalitnější zrna z etických plantáží po celém světě. Od Etiopie po Kolumbii, každá šálka vypráví příběh.
          </p>
        </div>

        <!-- Features -->
        <div class="space-y-4">
          <div class="flex gap-4 items-start">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-primary-400 to-pink-500 flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-bold text-gray-900 mb-1">Celosvětový původ</h3>
              <p class="text-gray-600">Dovážíme kávu z více než 12 zemí napříč 3 kontinenty</p>
            </div>
          </div>

          <div class="flex gap-4 items-start">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-bold text-gray-900 mb-1">Certifikovaná kvalita</h3>
              <p class="text-gray-600">Všechna zrna jsou certifikována a procházejí přísnou kontrolou</p>
            </div>
          </div>

          <div class="flex gap-4 items-start">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-lg">
              <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-bold text-gray-900 mb-1">Férový obchod</h3>
              <p class="text-gray-600">Podporujeme farmáře férovou cenou a udržitelným zemědělstvím</p>
            </div>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-4">
          <a href="<?php echo e(route('subscriptions.index')); ?>" class="group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            <span>Začít předplatné</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>

          <a href="<?php echo e(route('products.index')); ?>" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-semibold px-8 py-4 rounded-xl shadow-md hover:shadow-lg border-2 border-gray-200 hover:border-gray-300 transform hover:-translate-y-0.5 transition-all duration-200">
            <span>Prozkoumat kávy</span>
            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Featured Products Section -->
<?php if($featuredProducts->count() > 0): ?>
<div class="bg-white py-20 sm:py-24 lg:py-28">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- Section Header -->
    <div class="mb-12 flex flex-col md:flex-row items-start md:items-end justify-between gap-6">
      <div>
        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">Naše kávy</h2>
        <p class="text-xl text-gray-600">Ručně vybrané z nejlepších pražíren Evropy</p>
      </div>
      <a href="<?php echo e(route('products.index')); ?>" class="group inline-flex items-center gap-2 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
        <span>Zobrazit více</span>
        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </a>
    </div>

    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <?php $__currentLoopData = $featuredProducts->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <!-- product - start -->
      <div class="group">
        <a href="<?php echo e(route('products.show', $product)); ?>" class="relative block mb-4 h-80 overflow-hidden rounded-2xl bg-gray-100 shadow-xl hover:shadow-2xl transition-all duration-300">
          <?php if($product->image): ?>
          <img src="<?php echo e(asset($product->image)); ?>" loading="lazy" alt="<?php echo e($product->name); ?>" class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-110" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <?php else: ?>
          <div class="h-full w-full bg-gradient-to-br from-primary-100 to-pink-100 flex items-center justify-center p-8">
            <div class="text-center">
              <svg class="w-16 h-16 text-amber-400 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
              </svg>
              <p class="text-sm font-semibold text-primary-600"><?php echo e($product->name); ?></p>
            </div>
          </div>
          <?php endif; ?>

          <?php if($product->is_on_sale ?? false): ?>
          <span class="absolute left-4 top-4 flex items-center gap-2 rounded-full bg-gradient-to-r from-red-500 to-red-600 px-4 py-2 text-sm font-bold uppercase tracking-wider text-white shadow-lg">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
            </svg>
            sleva
          </span>
          <?php endif; ?>

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
          <a href="<?php echo e(route('products.show', $product)); ?>" class="block">
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-1"><?php echo e($product->name); ?></h3>
          </a>
          
          <!-- Roaster/Manufacturer -->
          <?php if(!empty($product->attributes['roaster'])): ?>
          <p class="text-sm text-gray-500 font-medium flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <?php echo e($product->attributes['roaster']); ?>

          </p>
          <?php elseif(!empty($product->attributes['manufacturer'])): ?>
          <p class="text-sm text-gray-500 font-medium flex items-center gap-1">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <?php echo e($product->attributes['manufacturer']); ?>

          </p>
          <?php endif; ?>

          <div class="flex items-baseline gap-2 pt-1">
            <span class="text-2xl font-black text-gray-900"><?php echo e(number_format($product->price, 0, ',', ' ')); ?> Kč</span>
            <?php if($product->original_price ?? false): ?>
            <span class="text-sm text-gray-500 line-through"><?php echo e(number_format($product->original_price, 0, ',', ' ')); ?> Kč</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!-- product - end -->
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Impact Section -->
<div class="bg-gradient-to-b from-white to-gray-50 py-20 sm:py-24 lg:py-28">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="relative overflow-hidden rounded-3xl shadow-2xl">
      <div class="flex flex-col lg:flex-row">
        <!-- content - start -->
        <div class="relative flex w-full flex-col justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 p-12 lg:w-1/2 lg:p-16">
          <!-- Decorative Elements -->
          <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-full blur-3xl"></div>
          <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-green-500/10 to-emerald-500/10 rounded-full blur-3xl"></div>

          <div class="relative z-10 space-y-6">
            <div class="inline-block px-4 py-2 bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/30 rounded-full backdrop-blur-sm">
              <span class="text-sm font-bold text-amber-300">Udržitelnost</span>
            </div>

            <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">
              Káva s pozitivním dopadem
            </h2>

            <p class="text-lg text-gray-300 leading-relaxed">
              Každé balení kávy z Kavi podporuje udržitelný rozvoj kávových komunit. Spolupracujeme pouze s pražírnami, které nakupují přímo od farmářů za férové ceny a podporují lokální komunity.
            </p>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-6 pt-4">
              <div class="space-y-2">
                <div class="text-3xl font-black text-amber-400">500+</div>
                <div class="text-sm text-gray-400">Podpořených farmářů</div>
              </div>
              <div class="space-y-2">
                <div class="text-3xl font-black text-green-400">100%</div>
                <div class="text-sm text-gray-400">Férový obchod</div>
              </div>
            </div>

            <div class="pt-4">
              <a href="#" class="group inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-900 font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <span>Více informací</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </a>
            </div>
          </div>
        </div>
        <!-- content - end -->

        <!-- image - start -->
        <div class="relative h-96 w-full lg:h-auto lg:w-1/2">
          <img src="https://images.unsplash.com/photo-1523805009345-7448845a9e53?auto=format&q=80&fit=crop&crop=center&w=1000&h=800" loading="lazy" alt="Africká káva" class="h-full w-full object-cover object-center" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent lg:bg-gradient-to-r"></div>
          
          <!-- Floating Badge -->
          <div class="absolute bottom-8 right-8 bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-2xl max-w-xs">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <div class="font-black text-gray-900 text-lg">Fair Trade</div>
                <div class="text-sm text-gray-600">Certifikováno</div>
              </div>
            </div>
          </div>
        </div>
        <!-- image - end -->
      </div>
    </div>
  </div>
</div>

<!-- Final CTA Section -->
<div class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20 sm:py-24 lg:py-32 overflow-hidden border-t-4 border-primary-500">
  <!-- Animated Background Elements with Pink Accents -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-gradient-to-br from-primary-500/20 to-pink-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-gradient-to-tr from-pink-500/10 to-primary-500/10 rounded-full blur-3xl"></div>
  </div>

  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-3xl flex-col items-center text-center">
      <!-- Badge -->
      <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-4 py-2 shadow-lg mb-6">
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        <span class="text-sm font-bold text-white">Připojte se k nám</span>
      </div>

      <!-- Heading -->
      <h2 class="mb-8 text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
        Začněte svou kávovou cestu ještě dnes
      </h2>

      <p class="mb-12 text-xl md:text-2xl text-white/90 max-w-2xl leading-relaxed">
        Získejte přístup k nejlepší kávě z celé Evropy. Flexibilní předplatné, bez závazků, zrušení kdykoliv.
      </p>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto justify-center">
          <a href="<?php echo e(route('subscriptions.index')); ?>" class="group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-10 py-5 rounded-xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 transition-all duration-200">
          <span class="text-lg">Vybrat předplatné</span>
          <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </a>

        <a href="<?php echo e(route('products.index')); ?>" class="group inline-flex items-center justify-center gap-2 bg-transparent hover:bg-white/10 text-white font-bold px-10 py-5 rounded-xl border-2 border-white/50 hover:border-white shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-200 backdrop-blur-sm">
          <span class="text-lg">Procházet kávy</span>
          <svg class="w-6 h-6 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </a>
      </div>

      <!-- Trust Indicators -->
      <div class="mt-16 grid grid-cols-3 gap-8 w-full max-w-2xl">
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-black text-white mb-2">14 dní</div>
          <div class="text-sm text-white/80">Vrácení peněz</div>
        </div>
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-black text-white mb-2">100%</div>
          <div class="text-sm text-white/80">Spokojenost</div>
        </div>
        <div class="text-center">
          <div class="text-3xl md:text-4xl font-black text-white mb-2">24/7</div>
          <div class="text-sm text-white/80">Podpora</div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/home.blade.php ENDPATH**/ ?>