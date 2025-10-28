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
        <div class="flex flex-wrap bg-dark-800 px-4 py-2 sm:flex-nowrap sm:items-center sm:justify-center sm:gap-3 md:px-8">
            <div class="order-1 mb-2 inline-block w-full max-w-screen-sm text-sm text-white sm:order-none sm:mb-0 sm:w-auto md:text-base">
                🎉 Doprava zdarma nad 1000 Kč
            </div>
        </div>
    </div>
    <!-- Banner - end -->

    <!-- Modern Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="mx-auto max-w-screen-xl px-4 md:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3 group" aria-label="logo">
                    <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-10 w-auto transform group-hover:scale-105 transition-transform duration-200">
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="<?php echo e(route('home')); ?>" class="text-base font-semibold text-gray-700 hover:text-primary-600 transition-colors duration-200 relative group">
                        Domů
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="text-base font-semibold text-gray-700 hover:text-primary-600 transition-colors duration-200 relative group">
                        Předplatné
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="<?php echo e(route('monthly-feature.index')); ?>" class="text-base font-semibold text-gray-700 hover:text-primary-600 transition-colors duration-200 relative group">
                        Káva měsíce
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="<?php echo e(route('products.index')); ?>" class="text-base font-semibold text-gray-700 hover:text-primary-600 transition-colors duration-200 relative group">
                        Obchod
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="<?php echo e(route('roasteries.index')); ?>" class="text-base font-semibold text-gray-700 hover:text-primary-600 transition-colors duration-200 relative group">
                        Naše pražírny
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="#" class="text-base font-semibold text-gray-700 hover:text-primary-600 transition-colors duration-200 relative group">
                        O nás
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-pink-600 group-hover:w-full transition-all duration-300"></span>
                    </a>
                </nav>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <!-- CTA Button - Desktop -->
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="hidden lg:inline-flex items-center gap-2 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Vybrat předplatné</span>
                    </a>

                    <!-- User Account -->
                    <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard.index')); ?>" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors duration-200" title="Můj účet">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                    <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="hidden md:flex items-center gap-2 text-gray-700 hover:text-primary-600 font-semibold transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>Přihlásit</span>
                    </a>
                    <?php endif; ?>

                    <!-- Cart -->
                    <a href="<?php echo e(route('cart.index')); ?>" class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-primary-100 transition-colors duration-200 group" title="Košík">
                        <svg class="w-5 h-5 text-gray-700 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <?php if(session('cart') && count(session('cart')) > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-gradient-to-r from-primary-500 to-pink-500 text-white text-xs w-5 h-5 flex items-center justify-center font-bold rounded-full shadow-lg animate-pulse">
                            <?php echo e(array_sum(session('cart'))); ?>

                        </span>
                        <?php endif; ?>
                    </a>

                    <!-- Mobile Menu Button -->
                    <button type="button" id="mobile-menu-button" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                        <svg class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="lg:hidden absolute top-full left-0 right-0 bg-gradient-to-br from-white via-gray-50 to-white backdrop-blur-xl shadow-2xl border-t-2 border-gray-200" style="display: none;">
            <div class="max-w-screen-xl mx-auto px-6 py-8 space-y-2">
                <!-- Navigation Links -->
                <a href="<?php echo e(route('home')); ?>" class="group flex items-center justify-between text-gray-900 hover:text-primary-600 font-bold py-3.5 px-4 rounded-xl hover:bg-gradient-to-r hover:from-primary-50 hover:to-pink-50 transition-all duration-200">
                    <span>Domů</span>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="group flex items-center justify-between text-gray-900 hover:text-primary-600 font-bold py-3.5 px-4 rounded-xl hover:bg-gradient-to-r hover:from-primary-50 hover:to-pink-50 transition-all duration-200">
                    <span>Předplatné</span>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="<?php echo e(route('monthly-feature.index')); ?>" class="group flex items-center justify-between text-gray-900 hover:text-primary-600 font-bold py-3.5 px-4 rounded-xl hover:bg-gradient-to-r hover:from-primary-50 hover:to-pink-50 transition-all duration-200">
                    <span>Káva měsíce</span>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="<?php echo e(route('products.index')); ?>" class="group flex items-center justify-between text-gray-900 hover:text-primary-600 font-bold py-3.5 px-4 rounded-xl hover:bg-gradient-to-r hover:from-primary-50 hover:to-pink-50 transition-all duration-200">
                    <span>Obchod</span>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="<?php echo e(route('roasteries.index')); ?>" class="group flex items-center justify-between text-gray-900 hover:text-primary-600 font-bold py-3.5 px-4 rounded-xl hover:bg-gradient-to-r hover:from-primary-50 hover:to-pink-50 transition-all duration-200">
                    <span>Naše pražírny</span>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="#" class="group flex items-center justify-between text-gray-900 hover:text-primary-600 font-bold py-3.5 px-4 rounded-xl hover:bg-gradient-to-r hover:from-primary-50 hover:to-pink-50 transition-all duration-200">
                    <span>O nás</span>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                
                <!-- Mobile CTA Button -->
                <div class="pt-4">
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="flex items-center justify-center gap-2 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-6 py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Vybrat předplatné</span>
                    </a>
                </div>
                
                <!-- Account Section -->
                <div class="pt-6 mt-4 border-t-2 border-gray-200 space-y-2">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard.index')); ?>" class="flex items-center gap-3 text-gray-900 hover:text-primary-600 font-semibold py-3 px-4 rounded-xl hover:bg-gray-100 transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Můj účet</span>
                        </a>
                        <?php if(auth()->user()->is_admin): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 text-gray-900 hover:text-primary-600 font-semibold py-3 px-4 rounded-xl hover:bg-gray-100 transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Admin</span>
                        </a>
                        <?php endif; ?>
                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full flex items-center gap-3 text-red-600 hover:text-red-700 font-semibold py-3 px-4 rounded-xl hover:bg-red-50 transition-all">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Odhlásit se</span>
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="flex items-center gap-3 text-gray-900 hover:text-primary-600 font-semibold py-3 px-4 rounded-xl hover:bg-gray-100 transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Přihlásit se</span>
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-6 py-3.5 font-bold transition-all text-center rounded-xl shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>Registrovat</span>
                        </a>
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

    <!-- Modern Footer -->
    <footer class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 border-t-4 border-primary-500">

      <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-12 pt-16 pb-12">
          <!-- Brand Section -->
          <div class="lg:col-span-2">
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-2 mb-6 group" aria-label="logo">
              <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-10 w-auto brightness-0 invert transform group-hover:scale-105 transition-transform duration-200">
            </a>
            
            <p class="text-gray-400 mb-6 leading-relaxed max-w-sm">
              Prémiová káva s měsíčními dodávkami přímo k vám domů. Objevte svět chutí z nejlepších pražíren Evropy.
            </p>

            <!-- Social Links -->
            <div class="flex gap-3">
              <a href="#" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-800 hover:bg-gradient-to-r hover:from-amber-600 hover:to-orange-600 text-gray-400 hover:text-white transition-all duration-300 transform hover:-translate-y-1">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
              </a>
              <a href="#" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-800 hover:bg-gradient-to-r hover:from-amber-600 hover:to-orange-600 text-gray-400 hover:text-white transition-all duration-300 transform hover:-translate-y-1">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
              </a>
              <a href="#" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-800 hover:bg-gradient-to-r hover:from-amber-600 hover:to-orange-600 text-gray-400 hover:text-white transition-all duration-300 transform hover:-translate-y-1">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
              </a>
            </div>
          </div>

          <!-- Předplatné -->
          <div>
            <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-6">Předplatné</h3>
            <nav class="space-y-3">
              <a href="<?php echo e(route('subscriptions.index')); ?>" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">Konfigurátor</a>
              <a href="<?php echo e(route('subscriptions.index')); ?>" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">Espresso BOX</a>
              <a href="<?php echo e(route('subscriptions.index')); ?>" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">Filter BOX</a>
              <a href="<?php echo e(route('subscriptions.index')); ?>" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">Dárkové</a>
            </nav>
          </div>

          <!-- Obchod -->
          <div>
            <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-6">Obchod</h3>
            <nav class="space-y-3">
              <a href="<?php echo e(route('products.index')); ?>" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">Všechny produkty</a>
              <a href="<?php echo e(route('products.index', ['category' => 'coffee'])); ?>" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">Káva</a>
              <a href="<?php echo e(route('products.index', ['category' => 'equipment'])); ?>" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">Vybavení</a>
              <a href="<?php echo e(route('products.index', ['category' => 'merch'])); ?>" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">Merch</a>
            </nav>
          </div>

          <!-- Informace -->
          <div>
            <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-6">Informace</h3>
            <nav class="space-y-3">
              <a href="#" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">O nás</a>
              <a href="#" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">FAQ</a>
              <a href="#" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">Blog</a>
              <a href="#" class="block text-gray-400 hover:text-primary-400 transition-colors duration-200">Doprava</a>
            </nav>
          </div>

          <!-- Kontakt -->
          <div>
            <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-6">Kontakt</h3>
            <nav class="space-y-4">
              <a href="mailto:info@kavi.cz" class="flex items-center gap-2 text-gray-400 hover:text-primary-400 transition-colors duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>info@kavi.cz</span>
              </a>
              <a href="tel:+420123456789" class="flex items-center gap-2 text-gray-400 hover:text-primary-400 transition-colors duration-200">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>+420 123 456 789</span>
              </a>
              <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm">Po-Pá: 9:00-17:00</span>
              </div>
            </nav>
          </div>
        </div>

        <!-- Newsletter Section -->
        <div class="border-t border-gray-800 py-12">
          <div class="max-w-2xl mx-auto text-center">
            <h3 class="text-2xl font-bold text-white mb-3">Přihlaste se k odběru novinek</h3>
            <p class="text-gray-400 mb-6">Získejte slevu 10% na první objednávku a buďte první, kdo se dozví o nových kávách</p>
            <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
              <input type="email" placeholder="Váš e-mail" class="flex-1 px-6 py-3 rounded-xl bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/50 transition-all">
              <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 whitespace-nowrap">
                Odebírat
              </button>
            </form>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 py-8">
          <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-400">
            <p>© <?php echo e(date('Y')); ?> Kavi Coffee. Všechna práva vyhrazena.</p>
            <div class="flex gap-6">
              <a href="#" class="hover:text-primary-400 transition-colors duration-200">Obchodní podmínky</a>
              <a href="#" class="hover:text-primary-400 transition-colors duration-200">Ochrana osobních údajů</a>
              <a href="#" class="hover:text-primary-400 transition-colors duration-200">Cookies</a>
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
        });
    </script>
</body>
</html>


<?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>