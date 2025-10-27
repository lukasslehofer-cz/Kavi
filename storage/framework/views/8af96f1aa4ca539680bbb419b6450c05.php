<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Kavi Coffee - Prémiová káva s předplatným'); ?></title>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Top Announcement Banner - Flowrift -->
    <div class="bg-white">
        <div class="flex flex-wrap bg-dark-800 px-4 py-3 sm:flex-nowrap sm:items-center sm:justify-center sm:gap-3 md:px-8">
            <div class="order-1 mb-2 inline-block w-full max-w-screen-sm text-sm text-white sm:order-none sm:mb-0 sm:w-auto md:text-base">
                🎉 Doprava zdarma nad 1000 Kč
            </div>

            <a href="<?php echo e(route('subscriptions.index')); ?>" class="order-last inline-block w-full whitespace-nowrap rounded-lg bg-dark-900 px-4 py-2 text-center text-xs font-semibold text-white outline-none ring-dark-700 transition duration-100 hover:bg-dark-700 focus-visible:ring active:bg-dark-600 sm:order-none sm:w-auto md:text-sm">
                Zjistit více
            </a>
        </div>
    </div>
    <!-- Banner - end -->

    <!-- Navigation - E-shop Header -->
    <header class="bg-white sticky top-0 z-50 border-b mb-8">
        <div class="mx-auto flex max-w-screen-xl items-center justify-between px-4 md:px-8">
            <!-- logo - start -->
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2.5 text-2xl font-bold text-black md:text-3xl" aria-label="logo">
                <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-8 md:h-10 w-auto">
            </a>
            <!-- logo - end -->

            <!-- nav - start -->
            <nav class="hidden gap-12 lg:flex 2xl:ml-16">
                <a href="<?php echo e(route('home')); ?>" class="text-lg font-semibold text-gray-600 transition duration-100 hover:text-primary-500 active:text-primary-700">Domů</a>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="text-lg font-semibold text-gray-600 transition duration-100 hover:text-primary-500 active:text-primary-700">Předplatné</a>
                <a href="<?php echo e(route('products.index')); ?>" class="text-lg font-semibold text-gray-600 transition duration-100 hover:text-primary-500 active:text-primary-700">Obchod</a>
                <a href="#" class="text-lg font-semibold text-gray-600 transition duration-100 hover:text-primary-500 active:text-primary-700">O nás</a>
            </nav>
            <!-- nav - end -->

            <!-- buttons - start -->
            <div class="flex divide-x border-r sm:border-l">
                <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard.index')); ?>" class="flex h-12 w-12 flex-col items-center justify-center gap-1.5 transition duration-100 hover:bg-gray-100 active:bg-gray-200 sm:h-20 sm:w-20 md:h-24 md:w-24">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="hidden text-xs font-semibold text-gray-500 sm:block">Účet</span>
                </a>
                <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="flex h-12 w-12 flex-col items-center justify-center gap-1.5 transition duration-100 hover:bg-gray-100 active:bg-gray-200 sm:h-20 sm:w-20 md:h-24 md:w-24">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="hidden text-xs font-semibold text-gray-500 sm:block">Přihlásit</span>
                </a>
                <?php endif; ?>

                <a href="<?php echo e(route('cart.index')); ?>" class="relative flex h-12 w-12 flex-col items-center justify-center gap-1.5 transition duration-100 hover:bg-gray-100 active:bg-gray-200 sm:h-20 sm:w-20 md:h-24 md:w-24">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <?php if(session('cart') && count(session('cart')) > 0): ?>
                    <span class="absolute top-2 right-2 bg-primary-500 text-white text-xs w-5 h-5 flex items-center justify-center font-bold rounded-full">
                        <?php echo e(array_sum(session('cart'))); ?>

                    </span>
                    <?php endif; ?>
                    <span class="hidden text-xs font-semibold text-gray-500 sm:block">Košík</span>
                </a>

                <button type="button" id="mobile-menu-button" class="flex h-12 w-12 flex-col items-center justify-center gap-1.5 transition duration-100 hover:bg-gray-100 active:bg-gray-200 sm:h-20 sm:w-20 md:h-24 md:w-24 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="hidden text-xs font-semibold text-gray-500 sm:block">Menu</span>
                </button>
            </div>
            <!-- buttons - end -->
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="lg:hidden border-t" style="display: none;">
            <div class="flex flex-col max-w-screen-xl mx-auto px-4 py-4">
                <a href="<?php echo e(route('home')); ?>" class="text-gray-600 hover:text-primary-500 font-semibold py-2 transition-colors">Domů</a>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="text-gray-600 hover:text-primary-500 font-semibold py-2 transition-colors">Předplatné</a>
                <a href="<?php echo e(route('products.index')); ?>" class="text-gray-600 hover:text-primary-500 font-semibold py-2 transition-colors">Obchod</a>
                <a href="#" class="text-gray-600 hover:text-primary-500 font-semibold py-2 transition-colors">O nás</a>
                
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard.index')); ?>" class="block text-gray-600 hover:text-primary-500 font-semibold py-2 transition-colors">Můj účet</a>
                        <?php if(auth()->user()->is_admin): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="block text-gray-600 hover:text-primary-500 font-semibold py-2 transition-colors">Admin</a>
                        <?php endif; ?>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="pt-2">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-gray-600 hover:text-primary-500 font-semibold text-left py-2 w-full">Odhlásit se</button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="block text-gray-600 hover:text-primary-500 font-semibold py-2 transition-colors">Přihlásit se</a>
                        <a href="<?php echo e(route('register')); ?>" class="block bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 font-semibold text-sm transition-all text-center mt-2 rounded-lg">Registrovat</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-4 mt-4 rounded-lg">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-4 mt-4 rounded-lg">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-grow">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <div class="bg-gray-900 mt-12">
      <footer class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="mb-16 grid grid-cols-2 gap-12 pt-10 md:grid-cols-4 lg:grid-cols-6 lg:gap-8 lg:pt-12">
          <div class="col-span-full lg:col-span-2">
            <!-- logo - start -->
            <div class="mb-4 lg:-mt-2">
              <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 text-xl font-bold text-gray-100 md:text-2xl" aria-label="logo">
                <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-8 w-auto brightness-0 invert">
              </a>
            </div>
            <!-- logo - end -->

            <p class="mb-6 text-gray-400 sm:pr-8">Prémiová káva s měsíčními dodávkami přímo k vám domů.</p>

            <!-- social - start -->
            <div class="flex gap-4">
              <a href="#" target="_blank" class="text-gray-400 transition duration-100 hover:text-gray-500 active:text-gray-600">
                <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
              </a>

              <a href="#" target="_blank" class="text-gray-400 transition duration-100 hover:text-gray-500 active:text-gray-600">
                <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
              </a>
            </div>
            <!-- social - end -->
          </div>

          <!-- nav - start -->
          <div>
            <div class="mb-4 font-bold uppercase tracking-widest text-gray-100">Předplatné</div>

            <nav class="flex flex-col gap-4">
              <div>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">Konfigurátor</a>
              </div>

              <div>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">Espresso BOX</a>
              </div>

              <div>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">Filter BOX</a>
              </div>

              <div>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">Dárkové</a>
              </div>
            </nav>
          </div>
          <!-- nav - end -->

          <!-- nav - start -->
          <div>
            <div class="mb-4 font-bold uppercase tracking-widest text-gray-100">Obchod</div>

            <nav class="flex flex-col gap-4">
              <div>
                <a href="<?php echo e(route('products.index')); ?>" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">Všechny produkty</a>
              </div>

              <div>
                <a href="<?php echo e(route('products.index', ['category' => 'coffee'])); ?>" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">Káva</a>
              </div>

              <div>
                <a href="<?php echo e(route('products.index', ['category' => 'equipment'])); ?>" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">Vybavení</a>
              </div>

              <div>
                <a href="<?php echo e(route('products.index', ['category' => 'merch'])); ?>" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">Merch</a>
              </div>
            </nav>
          </div>
          <!-- nav - end -->

          <!-- nav - start -->
          <div>
            <div class="mb-4 font-bold uppercase tracking-widest text-gray-100">Informace</div>

            <nav class="flex flex-col gap-4">
              <div>
                <a href="#" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">O nás</a>
              </div>

              <div>
                <a href="#" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">FAQ</a>
              </div>

              <div>
                <a href="#" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">Blog</a>
              </div>

              <div>
                <a href="#" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">Doprava</a>
              </div>
            </nav>
          </div>
          <!-- nav - end -->

          <!-- nav - start -->
          <div>
            <div class="mb-4 font-bold uppercase tracking-widest text-gray-100">Kontakt</div>

            <nav class="flex flex-col gap-4">
              <div>
                <a href="mailto:info@kavi.cz" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">info@kavi.cz</a>
              </div>

              <div>
                <a href="tel:+420123456789" class="text-gray-400 transition duration-100 hover:text-primary-500 active:text-primary-600">+420 123 456 789</a>
              </div>

              <div>
                <p class="text-gray-400 text-sm">Po-Pá: 9:00-17:00</p>
              </div>
            </nav>
          </div>
          <!-- nav - end -->
        </div>

        <div class="border-t border-gray-800 py-8 text-center text-sm text-gray-400">© <?php echo e(date('Y')); ?> Kavi Coffee. Všechna práva vyhrazena.</div>
      </footer>
    </div>

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
        });
    </script>
</body>
</html>


<?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>