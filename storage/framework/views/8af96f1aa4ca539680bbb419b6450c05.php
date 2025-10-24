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
    <!-- Top Bar - Clean -->
    <div class="bg-primary-500 text-white text-center py-4 px-4">
        <p class="font-black uppercase tracking-widest text-sm">Doprava zdarma nad 1000 Kč • 1900+ spokojených zákazníků</p>
    </div>

    <!-- Navigation - Clean & Modern -->
    <nav class="bg-white sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <!-- Logo -->
                <a href="<?php echo e(route('home')); ?>" class="flex items-center group">
                    <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-12 md:h-16 w-auto transform group-hover:scale-105 transition-transform">
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-12">
                    <a href="<?php echo e(route('home')); ?>" class="text-dark-800 hover:text-primary-500 font-black uppercase text-sm tracking-widest transition-colors">Domů</a>
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="text-dark-800 hover:text-primary-500 font-black uppercase text-sm tracking-widest transition-colors">Předplatné</a>
                    <a href="<?php echo e(route('products.index')); ?>" class="text-dark-800 hover:text-primary-500 font-black uppercase text-sm tracking-widest transition-colors">Obchod</a>
                    
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard.index')); ?>" class="text-dark-800 hover:text-primary-500 font-black uppercase text-sm tracking-widest transition-colors">Účet</a>
                        <?php if(auth()->user()->is_admin): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-dark-800 hover:text-primary-500 font-black uppercase text-sm tracking-widest transition-colors">Admin</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-6">
                    <!-- Cart Icon -->
                    <a href="<?php echo e(route('cart.index')); ?>" class="relative p-4 bg-bluegray-100 hover:bg-primary-500 transition-all group">
                        <svg class="w-7 h-7 text-dark-800 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <?php if(session('cart') && count(session('cart')) > 0): ?>
                        <span class="absolute -top-2 -right-2 bg-primary-500 text-white text-xs w-7 h-7 flex items-center justify-center font-black">
                            <?php echo e(array_sum(session('cart'))); ?>

                        </span>
                        <?php endif; ?>
                    </a>

                    <!-- Auth Buttons -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <?php if(auth()->guard()->check()): ?>
                            <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-dark-800 hover:text-primary-500 font-black uppercase text-sm tracking-widest transition-colors">Odhlásit</button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-dark-800 hover:text-primary-500 font-black uppercase text-sm tracking-widest transition-colors">Přihlásit</a>
                            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary !px-8 !py-3 text-xs">Začít →</a>
                        <?php endif; ?>
                    </div>

                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="lg:hidden p-4 bg-dark-800 hover:bg-primary-500 transition-all">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="hidden lg:hidden pb-6 pt-4 border-t border-bluegray-100 mt-4">
                <div class="flex flex-col space-y-4">
                    <a href="<?php echo e(route('home')); ?>" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Domů</a>
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Předplatné</a>
                    <a href="<?php echo e(route('products.index')); ?>" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Obchod</a>
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard.index')); ?>" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Můj účet</a>
                        <?php if(auth()->user()->is_admin): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Admin</a>
                        <?php endif; ?>
                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-dark-700 hover:text-primary-500 font-medium text-left py-2 w-full">Odhlásit</button>
                        </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-dark-700 hover:text-primary-500 font-medium py-2 transition-colors">Přihlásit se</a>
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-primary w-full">Začít nyní</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

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

    <!-- Footer - Clean & Modern -->
    <footer class="bg-dark-800 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-16">
                <!-- Brand Column -->
                <div class="lg:col-span-1">
                    <div class="mb-8">
                        <img src="/images/kavi-logo-black.png" alt="Kavi Coffee" class="h-16 w-auto mb-6 brightness-0 invert">
                    </div>
                    <p class="text-bluegray-200 font-bold mb-8 uppercase text-sm tracking-widest">Prémiová káva<br/>Měsíční dodávky</p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-14 h-14 bg-bluegray-700 hover:bg-primary-500 flex items-center justify-center transition-all">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-14 h-14 bg-bluegray-700 hover:bg-primary-500 flex items-center justify-center transition-all">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Links Columns -->
                <div>
                    <h4 class="font-display font-black uppercase text-white mb-8 text-sm tracking-widest">Obchod</h4>
                    <ul class="space-y-4">
                        <li><a href="<?php echo e(route('subscriptions.index')); ?>" class="text-bluegray-300 hover:text-white font-bold uppercase text-sm tracking-widest transition-colors">Předplatné</a></li>
                        <li><a href="<?php echo e(route('products.index')); ?>" class="text-bluegray-300 hover:text-white font-bold uppercase text-sm tracking-widest transition-colors">Produkty</a></li>
                        <li><a href="#" class="text-bluegray-300 hover:text-white font-bold uppercase text-sm tracking-widest transition-colors">Dárky</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-display font-black uppercase text-white mb-8 text-sm tracking-widest">Info</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-bluegray-300 hover:text-white font-bold uppercase text-sm tracking-widest transition-colors">O nás</a></li>
                        <li><a href="#" class="text-bluegray-300 hover:text-white font-bold uppercase text-sm tracking-widest transition-colors">FAQ</a></li>
                        <li><a href="#" class="text-bluegray-300 hover:text-white font-bold uppercase text-sm tracking-widest transition-colors">Doprava</a></li>
                        <li><a href="#" class="text-bluegray-300 hover:text-white font-bold uppercase text-sm tracking-widest transition-colors">Vrácení</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-display font-black uppercase text-white mb-8 text-sm tracking-widest">Kontakt</h4>
                    <ul class="space-y-4">
                        <li>
                            <a href="mailto:info@kavi.cz" class="text-bluegray-300 hover:text-white font-bold uppercase text-sm tracking-widest transition-colors">info@kavi.cz</a>
                        </li>
                        <li>
                            <a href="tel:+420123456789" class="text-bluegray-300 hover:text-white font-bold uppercase text-sm tracking-widest transition-colors">+420 123 456 789</a>
                        </li>
                        <li class="pt-2">
                            <p class="text-bluegray-400 text-xs font-bold uppercase">Po-Pá: 9-17</p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-bluegray-400 text-sm font-bold uppercase tracking-widest">&copy; <?php echo e(date('Y')); ?> Kavi • Všechna práva vyhrazena</p>
                <div class="flex space-x-8 text-sm">
                    <a href="#" class="text-bluegray-400 hover:text-white font-bold uppercase tracking-widest transition-colors">Podmínky</a>
                    <a href="#" class="text-bluegray-400 hover:text-white font-bold uppercase tracking-widest transition-colors">Soukromí</a>
                    <a href="#" class="text-bluegray-400 hover:text-white font-bold uppercase tracking-widest transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu?.classList.toggle('hidden');
        });
    </script>
</body>
</html>


<?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>