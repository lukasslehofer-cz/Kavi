<?php $__env->startSection('title', 'Kavi Coffee - Prémiová káva s předplatným'); ?>

<?php $__env->startSection('content'); ?>

<!-- Hero Section - E-shop Style -->
<div class="bg-white">
    <section class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="mb-8 flex flex-wrap justify-between md:mb-16">
            <div class="mb-6 flex w-full flex-col justify-center sm:mb-12 lg:mb-0 lg:w-1/3 lg:pb-24 lg:pt-48">
                <h1 class="mb-4 text-4xl font-bold text-black sm:text-5xl md:mb-8 md:text-6xl">Prémiová káva<br />přímo k vám</h1>

                <p class="max-w-md leading-relaxed text-gray-500 xl:text-lg mb-8">Objevte novou vynikající kávu každý měsíc. Praženo maximálně 7 dní před expedicí. Bez závazků, zrušte kdykoliv.</p>

                <div class="flex flex-col gap-2.5 sm:flex-row sm:justify-center lg:justify-start">
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="inline-block rounded-lg bg-primary-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-primary-600 focus-visible:ring active:bg-primary-700 md:text-base">Začít předplatné</a>

                    <a href="<?php echo e(route('products.index')); ?>" class="inline-block rounded-lg bg-gray-200 px-8 py-3 text-center text-sm font-semibold text-gray-500 outline-none ring-primary-300 transition duration-100 hover:bg-gray-300 focus-visible:ring active:text-gray-700 md:text-base">Prozkoumat obchod</a>
                </div>
            </div>

            <div class="mb-12 flex w-full md:mb-16 lg:w-2/3">
                <div class="relative left-12 top-12 z-10 -ml-12 overflow-hidden rounded-lg bg-gray-100 shadow-lg md:left-16 md:top-16 lg:ml-0">
                    <img src="https://images.unsplash.com/photo-1733860442875-aa8c87757b3a?auto=format&q=75&fit=crop&w=550&h=550" loading="lazy" alt="Kávová zrna" class="h-full w-full object-cover object-center" />
                </div>

                <div class="overflow-hidden rounded-lg bg-gray-100 shadow-lg">
                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&q=75&fit=crop&w=550&h=550" loading="lazy" alt="Šálek kávy" class="h-full w-full object-cover object-center" />
                </div>
            </div>
            
        </div>

        
    </section>
</div>

<!-- Features Section -->
<div class="bg-white py-16 sm:py-16 lg:py-16">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="grid gap-12 sm:grid-cols-2 xl:grid-cols-3 xl:gap-16">
      <!-- feature - start -->
      <div class="flex flex-col items-center">
        <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-primary-500 text-white shadow-lg md:h-14 md:w-14 md:rounded-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>

        <h3 class="mb-2 text-center text-lg font-semibold md:text-xl">Čerstvá káva</h3>
        <p class="mb-2 text-center text-gray-500">Praženo na objednávku, maximálně 7 dní před expedicí. Vždy čerstvá, vždy výjimečná.</p>
        <a href="<?php echo e(route('subscriptions.index')); ?>" class="font-bold text-primary-500 transition duration-100 hover:text-primary-600 active:text-primary-700">Zjistit více</a>
      </div>
      <!-- feature - end -->

      <!-- feature - start -->
      <div class="flex flex-col items-center">
        <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-primary-500 text-white shadow-lg md:h-14 md:w-14 md:rounded-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>

        <h3 class="mb-2 text-center text-lg font-semibold md:text-xl">Prémiová kvalita</h3>
        <p class="mb-2 text-center text-gray-500">100% Arabica z ověřených plantáží. Každá šarže kávy prochází pečlivou kontrolou kvality.</p>
        <a href="<?php echo e(route('products.index')); ?>" class="font-bold text-primary-500 transition duration-100 hover:text-primary-600 active:text-primary-700">Prozkoumat</a>
      </div>
      <!-- feature - end -->

      <!-- feature - start -->
      <div class="flex flex-col items-center">
        <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-primary-500 text-white shadow-lg md:h-14 md:w-14 md:rounded-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
        </div>

        <h3 class="mb-2 text-center text-lg font-semibold md:text-xl">Doprava zdarma</h3>
        <p class="mb-2 text-center text-gray-500">Doprava zdarma při objednávce nad 1000 Kč. Káva dorazí přímo k vašim dveřím.</p>
        <a href="#" class="font-bold text-primary-500 transition duration-100 hover:text-primary-600 active:text-primary-700">Více info</a>
      </div>
      <!-- feature - end -->
    </div>
  </div>
</div>

<!-- CTA Section -->
<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="flex flex-col overflow-hidden rounded-lg bg-gray-100 sm:flex-row md:h-80">
      <!-- image - start -->
      <div class="order-first h-48 w-full bg-gray-300 sm:order-none sm:h-auto sm:w-1/2 lg:w-3/5">
        <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&q=75&fit=crop&w=1000" loading="lazy" alt="Kávová zrna" class="h-full w-full object-cover object-center" />
      </div>
      <!-- image - end -->

      <!-- content - start -->
      <div class="flex w-full flex-col p-12 sm:w-1/2 sm:p-12 lg:w-2/5">
        <h2 class="mb-4 text-xl font-bold text-gray-800 md:text-2xl lg:text-4xl">Káva, kterou budete milovat</h2>

        <p class="mb-8 max-w-md text-gray-600">Pečlivě vybíráme nejkvalitnější kávu z ověřených pražíren. Každý měsíc objevte nové chutě přímo u vás doma.</p>

        <div class="mt-auto">
          <a href="<?php echo e(route('subscriptions.index')); ?>" class="inline-block rounded-lg bg-primary-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-primary-600 focus-visible:ring active:bg-primary-700 md:text-base">Začít předplatné</a>
        </div>
      </div>
      <!-- content - end -->
    </div>
  </div>
</div>

<!-- Subscription Plans Section -->
<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <h2 class="mb-4 text-center text-2xl font-bold text-gray-800 md:mb-8 lg:text-3xl xl:mb-12">Vyberte si ideální kávový box pro vás</h2>

    <div class="mb-6 grid gap-6 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 lg:gap-8">
      <!-- plan - start -->
      <div class="flex flex-col rounded-lg border p-4 pt-6">
        <div class="mb-12">
          <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">500g</div>

          <p class="mx-auto mb-8 px-8 text-center text-gray-500">Ideální pro jednotlivce nebo páry, které si chtějí vychutnat kvalitní kávu</p>

          <div class="space-y-2">
            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">2 balíčky po 250g</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">2 druhy prémiové kávy</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">Doprava zdarma</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">Zrušení kdykoliv</span>
            </div>
            <!-- check - end -->
          </div>
        </div>

        <div class="mt-auto flex flex-col gap-8">
          <div class="flex items-end justify-center gap-1">
            <span class="text-4xl font-bold text-gray-800"><?php echo e(number_format($subscriptionPricing['2'], 0, ',', ' ')); ?>,-</span>
            <span class="text-gray-500">Kč/box</span>
          </div>

          <a href="<?php echo e(route('subscriptions.index')); ?>" class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base">Vybrat plán</a>
        </div>
      </div>
      <!-- plan - end -->

      <!-- plan - start -->
      <div class="relative flex flex-col rounded-lg border-2 p-4 pt-6">
        <div class="mb-12">
          <div class="absolute inset-x-0 -top-3 flex justify-center">
            <span class="flex h-6 items-center justify-center rounded-full bg-primary-500 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-white">nejoblíbenější</span>
          </div>

          <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">750g</div>

          <p class="mx-auto mb-8 px-8 text-center text-gray-500">Nejpopulárnější volba pro pravidelné milovníky kávy</p>

          <div class="space-y-2">
            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">3 balíčky po 250g</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">3 druhy prémiové kávy</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">Doprava zdarma</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">Zrušení kdykoliv</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">Prioritní podpora</span>
            </div>
            <!-- check - end -->
          </div>
        </div>

        <div class="mt-auto flex flex-col gap-8">
          <div class="flex items-end justify-center gap-1">
            <span class="text-4xl font-bold text-gray-800"><?php echo e(number_format($subscriptionPricing['3'], 0, ',', ' ')); ?>,-</span>
            <span class="text-gray-500">Kč/box</span>
          </div>

          <a href="<?php echo e(route('subscriptions.index')); ?>" class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-primary-600 focus-visible:ring active:bg-primary-700 md:text-base">Vybrat plán</a>
        </div>
      </div>
      <!-- plan - end -->

      <!-- plan - start -->
      <div class="flex flex-col rounded-lg border p-4 pt-6">
        <div class="mb-12">
          <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">1000g</div>

          <p class="mx-auto mb-8 px-8 text-center text-gray-500">Pro kávové nadšence a větší domácnosti</p>

          <div class="space-y-2">
            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">4 balíčky po 250g</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">4 druhy prémiové kávy</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">Doprava zdarma</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">Zrušení kdykoliv</span>
            </div>
            <!-- check - end -->

            <!-- check - start -->
            <div class="flex gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>

              <span class="text-gray-600">VIP podpora</span>
            </div>
            <!-- check - end -->
          </div>
        </div>

        <div class="mt-auto flex flex-col gap-8">
          <div class="flex items-end justify-center gap-1">
            <span class="text-4xl font-bold text-gray-800"><?php echo e(number_format($subscriptionPricing['4'], 0, ',', ' ')); ?>,-</span>
            <span class="text-gray-500">Kč/box</span>
          </div>

          <a href="<?php echo e(route('subscriptions.index')); ?>" class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base">Vybrat plán</a>
        </div>
      </div>
      <!-- plan - end -->
    </div>

    <div class="text-center text-sm text-gray-500 sm:text-base">Další nastavení vašeho kávového předplatného následuje v dalším kroku.</div>
  </div>
</div>

<!-- Testimonials Section -->
<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <h2 class="mb-8 text-center text-2xl font-bold text-gray-800 md:mb-12 lg:text-3xl">Co říkají naši zákazníci</h2>

    <div class="grid gap-y-10 sm:grid-cols-2 sm:gap-y-12 lg:grid-cols-3 lg:divide-x">
      <!-- quote - start -->
      <div class="flex flex-col items-center gap-4 sm:px-4 md:gap-6 lg:px-8">
        <div class="text-center text-gray-600">"Nejlepší káva, kterou jsem kdy měla! Pravidelné dodávky znamenají, že nikdy nedojdu a kvalita je vždy konzistentní."</div>

        <div class="flex flex-col items-center gap-2 sm:flex-row md:gap-3">
          <div class="h-12 w-12 overflow-hidden rounded-full bg-gray-100 shadow-lg md:h-14 md:w-14">
            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&q=75&fit=crop&w=112" loading="lazy" alt="Jana Nováková" class="h-full w-full object-cover object-center" />
          </div>

          <div>
            <div class="text-center text-sm font-bold text-primary-500 sm:text-left md:text-base">Jana Nováková</div>
            <p class="text-center text-sm text-gray-500 sm:text-left md:text-sm">Zákaznice 2+ roky</p>
          </div>
        </div>
      </div>
      <!-- quote - end -->

      <!-- quote - start -->
      <div class="flex flex-col items-center gap-4 sm:px-4 md:gap-6 lg:px-8">
        <div class="text-center text-gray-600">"Skvělý servis a prvotřídní káva. Flexibilita předplatného je skvělá - můžu kdykoli změnit množství nebo typ kávy."</div>

        <div class="flex flex-col items-center gap-2 sm:flex-row md:gap-3">
          <div class="h-12 w-12 overflow-hidden rounded-full bg-gray-100 shadow-lg md:h-14 md:w-14">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&q=75&fit=crop&w=112" loading="lazy" alt="Petr Dvořák" class="h-full w-full object-cover object-center" />
          </div>

          <div>
            <div class="text-center text-sm font-bold text-primary-500 sm:text-left md:text-base">Petr Dvořák</div>
            <p class="text-center text-sm text-gray-500 sm:text-left md:text-sm">Zákazník 1+ rok</p>
          </div>
        </div>
      </div>
      <!-- quote - end -->

      <!-- quote - start -->
      <div class="flex flex-col items-center gap-4 sm:px-4 md:gap-6 lg:px-8">
        <div class="text-center text-gray-600">"Konečně káva, která chutná jako z kavárny! Čerstvost je znát a výběr různých druhů je skvělý."</div>

        <div class="flex flex-col items-center gap-2 sm:flex-row md:gap-3">
          <div class="h-12 w-12 overflow-hidden rounded-full bg-gray-100 shadow-lg md:h-14 md:w-14">
            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&q=75&fit=crop&w=112" loading="lazy" alt="Marie Horáková" class="h-full w-full object-cover object-center" />
          </div>

          <div>
            <div class="text-center text-sm font-bold text-primary-500 sm:text-left md:text-base">Marie Horáková</div>
            <p class="text-center text-sm text-gray-500 sm:text-left md:text-sm">Zákaznice 6+ měsíců</p>
          </div>
        </div>
      </div>
      <!-- quote - end -->
    </div>
  </div>
</div>

<!-- Large Photo Section -->
<div class="relative bg-white py-16 sm:py-20 lg:py-24">
  <!-- Background Image -->
  <div class="absolute inset-0 z-0">
    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&q=75&fit=crop&w=2000" alt="Káva pozadí" class="h-full w-full object-cover" />
    <div class="absolute inset-0 bg-black opacity-60"></div>
  </div>

  <!-- Content -->
  <div class="relative z-10 mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-xl flex-col items-center text-center">
      <p class="mb-4 font-semibold text-primary-400 md:mb-6 md:text-lg xl:text-xl">Káva, která vás nadchne</p>

      <h1 class="mb-8 text-3xl font-bold text-white sm:text-4xl md:mb-12 md:text-5xl">Objevte svět prémiové kávy</h1>

      <div class="flex w-full flex-col gap-2.5 sm:flex-row sm:justify-center">
        <a href="<?php echo e(route('subscriptions.index')); ?>" class="inline-block rounded-lg bg-primary-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-primary-600 focus-visible:ring active:bg-primary-700 md:text-base">Začít předplatné</a>

        <a href="<?php echo e(route('products.index')); ?>" class="inline-block rounded-lg bg-white px-8 py-3 text-center text-sm font-semibold text-gray-800 outline-none ring-primary-300 transition duration-100 hover:bg-gray-100 focus-visible:ring active:bg-gray-200 md:text-base">Prozkoumat kávy</a>
      </div>
    </div>
  </div>
</div>

<!-- Featured Products Section -->
<?php if($featuredProducts->count() > 0): ?>
<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mb-6 flex items-end justify-between gap-4">
      <h2 class="text-2xl font-bold text-gray-800 lg:text-3xl">Naše kávy</h2>

      <a href="<?php echo e(route('products.index')); ?>" class="inline-block rounded-lg border bg-white px-4 py-2 text-center text-sm font-semibold text-gray-500 outline-none ring-primary-300 transition duration-100 hover:bg-gray-100 focus-visible:ring active:bg-gray-200 md:px-8 md:py-3 md:text-base">Zobrazit více</a>
    </div>

    <div class="grid gap-x-4 gap-y-8 sm:grid-cols-2 md:gap-x-6 lg:grid-cols-3 xl:grid-cols-4">
      <?php $__currentLoopData = $featuredProducts->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <!-- product - start -->
      <div>
        <a href="<?php echo e(route('products.show', $product)); ?>" class="group relative mb-2 block h-80 overflow-hidden rounded-lg bg-gray-100 lg:mb-3">
          <?php if($product->image): ?>
          <img src="<?php echo e(asset($product->image)); ?>" loading="lazy" alt="<?php echo e($product->name); ?>" class="h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />
          <?php else: ?>
          <div class="h-full w-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center p-8">
            <div class="text-center">
              <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
              </svg>
              <p class="text-xs font-semibold text-gray-500"><?php echo e($product->name); ?></p>
            </div>
          </div>
          <?php endif; ?>

          <?php if($product->is_on_sale ?? false): ?>
          <span class="absolute left-0 top-0 rounded-br-lg bg-red-500 px-3 py-1.5 text-sm uppercase tracking-wider text-white">sleva</span>
          <?php endif; ?>
        </a>

        <div>
          <a href="<?php echo e(route('products.show', $product)); ?>" class="hover:text-gray-800 mb-1 text-gray-500 transition duration-100 lg:text-lg"><?php echo e($product->name); ?></a>

          <div class="flex items-end gap-2">
            <span class="font-bold text-gray-800 lg:text-lg"><?php echo e(number_format($product->price, 0, ',', ' ')); ?> Kč</span>
            <?php if($product->original_price ?? false): ?>
            <span class="mb-0.5 text-red-500 line-through"><?php echo e(number_format($product->original_price, 0, ',', ' ')); ?> Kč</span>
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
<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="flex flex-col overflow-hidden rounded-lg bg-gray-900 sm:flex-row">
      <!-- content - start -->
      <div class="flex w-full flex-col p-12 sm:w-1/2 sm:p-16 lg:w-2/5">
        <h2 class="mb-4 text-xl font-bold text-white md:text-2xl lg:text-4xl">Káva s dopadem</h2>

        <p class="mb-8 max-w-md text-gray-400">Každé balení kávy z Kavi podporuje udržitelný rozvoj kávových komunit. Spolupracujeme pouze s pražírnami, které nakupují přímo od farmářů za férové ceny a podporují lokální komunity.</p>

        <div class="mt-auto">
          <a href="#" class="inline-block rounded-lg bg-white px-8 py-3 text-center text-sm font-semibold text-gray-800 outline-none ring-primary-300 transition duration-100 hover:bg-gray-100 focus-visible:ring active:bg-gray-200 md:text-base">Více informací</a>
        </div>
      </div>
      <!-- content - end -->

      <!-- image - start -->
      <div class="order-first h-48 w-full bg-gray-700 sm:order-none sm:h-auto sm:w-1/2 lg:w-3/5">
        <img src="https://images.unsplash.com/photo-1523805009345-7448845a9e53?auto=format&q=75&fit=crop&crop=center&w=1000&h=500" loading="lazy" alt="Africká káva" class="h-full w-full object-cover object-center" />
      </div>
      <!-- image - end -->
    </div>
  </div>
</div>

<!-- CTA Section -->
<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-xl flex-col items-center text-center">
      <p class="mb-4 font-semibold text-primary-500 md:mb-6 md:text-lg xl:text-xl">Připojte se k nám</p>

      <h1 class="mb-8 text-3xl font-bold text-black sm:text-4xl md:mb-12 md:text-5xl">Začněte svou kávovou cestu ještě dnes</h1>

      <div class="flex w-full flex-col gap-2.5 sm:flex-row sm:justify-center">
        <a href="<?php echo e(route('subscriptions.index')); ?>" class="inline-block rounded-lg bg-primary-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-primary-600 focus-visible:ring active:bg-primary-700 md:text-base">Vybrat předplatné</a>

        <a href="<?php echo e(route('products.index')); ?>" class="inline-block rounded-lg bg-gray-200 px-8 py-3 text-center text-sm font-semibold text-gray-500 outline-none ring-primary-300 transition duration-100 hover:bg-gray-300 focus-visible:ring active:text-gray-700 md:text-base">Procházet kávy</a>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/home.blade.php ENDPATH**/ ?>