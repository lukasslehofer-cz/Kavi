<?php $__env->startSection('title', 'Nákupní košík - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Header - Minimal -->
<div class="relative bg-gray-100 py-12 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 tracking-tight">Nákupní košík</h1>
                <p class="text-lg text-gray-600 font-light">
                    <span class="font-medium"><?php echo e(count($cartItems ?? [])); ?></span> 
                    <?php echo e(count($cartItems ?? []) === 1 ? 'položka' : (count($cartItems ?? []) < 5 ? 'položky' : 'položek')); ?>

                </p>
            </div>
            <div class="hidden md:block">
                <div class="w-14 h-14 rounded-full bg-gray-900 flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php if(empty($cartItems)): ?>
    <!-- Empty Cart - Minimal -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl p-12 text-center border border-gray-200">
            <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4 tracking-tight">Váš košík je prázdný</h2>
            <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto font-light">Objevte naši nabídku prémiových káv a vyberte si tu pravou pro vás.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-3 rounded-full transition-all duration-200">
                    <span>Procházet kávy</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium px-8 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200">
                    Zobrazit předplatné
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="space-y-4">
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:border-gray-300 transition-all duration-200">
                    <div class="flex gap-6">
                        <!-- Product Image - Minimal -->
                        <div class="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0 bg-gray-50">
                            <?php if($item['product']->image): ?>
                            <img src="<?php echo e(asset($item['product']->image)); ?>" alt="<?php echo e($item['product']->name); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center p-4 bg-gray-100">
                                <svg class="w-10 h-10 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                                </svg>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Product Info -->
                        <div class="flex-grow">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                        <a href="<?php echo e(route('products.show', $item['product'])); ?>" class="hover:text-gray-600 transition-colors">
                                            <?php echo e($item['product']->name); ?>

                                        </a>
                                    </h3>
                                    <p class="text-gray-600 font-light"><?php echo e(number_format($item['product']->price, 0, ',', ' ')); ?> Kč / ks</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-gray-900"><?php echo e(number_format($item['subtotal'], 0, ',', ' ')); ?> Kč</p>
                                </div>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center justify-between gap-4 mt-4 pt-4 border-t border-gray-100">
                                <form action="<?php echo e(route('cart.update', $item['product']->id)); ?>" method="POST" class="flex items-center gap-3">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <div class="flex items-center border border-gray-200 rounded-full overflow-hidden bg-white">
                                        <button type="button" class="px-3 py-1.5 text-gray-700 hover:bg-gray-100 transition-colors font-medium text-sm">-</button>
                                        <input type="number" name="quantity" value="<?php echo e($item['quantity']); ?>" min="1" max="<?php echo e($item['product']->stock); ?>" 
                                               class="w-12 text-center text-sm font-medium border-0 focus:ring-0 py-1.5" data-quantity-input>
                                        <button type="button" class="px-3 py-1.5 text-gray-700 hover:bg-gray-100 transition-colors font-medium text-sm">+</button>
                                    </div>
                                    <button type="submit" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-900 font-medium px-4 py-1.5 rounded-full transition-colors">
                                        Aktualizovat
                                    </button>
                                </form>

                                <!-- Remove Button -->
                                <form action="<?php echo e(route('cart.remove', $item['product']->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-full transition-colors group" title="Odebrat z košíku">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Clear Cart - Minimal -->
            <div class="mt-6">
                <form action="<?php echo e(route('cart.clear')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="inline-flex items-center gap-2 text-red-600 hover:bg-red-50 text-sm font-medium px-4 py-2 rounded-full transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1v3M4 7h16"/>
                        </svg>
                        Vyprázdnit celý košík
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary - Minimal -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-6 sticky top-24 border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Souhrn objednávky</h3>
                </div>
                
                <dl class="space-y-4 mb-6">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <dt class="text-gray-600 font-light">Mezisoučet:</dt>
                        <dd class="font-bold text-gray-900"><?php echo e(number_format($total, 0, ',', ' ')); ?> Kč</dd>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <dt class="text-gray-600 font-light">Doprava:</dt>
                        <dd class="font-bold">
                            <?php if($total >= 1000): ?>
                            <span class="text-green-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Zdarma
                            </span>
                            <?php else: ?>
                            <span class="text-gray-900">99 Kč</span>
                            <?php endif; ?>
                        </dd>
                    </div>

                    <?php if($total < 1000): ?>
                    <div class="bg-primary-50 border border-primary-200 rounded-xl p-3">
                        <p class="text-sm text-primary-700 font-medium flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span>Do dopravy zdarma vám chybí <strong><?php echo e(number_format(1000 - $total, 0, ',', ' ')); ?> Kč</strong></span>
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-3">
                        <p class="text-sm text-green-700 font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Získali jste dopravu zdarma!
                        </p>
                    </div>
                    <?php endif; ?>

                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <dt class="font-bold text-gray-900 text-lg">Celkem:</dt>
                            <dd class="text-3xl font-bold text-gray-900">
                                <?php echo e(number_format($total >= 1000 ? $total : $total + 99, 0, ',', ' ')); ?> Kč
                            </dd>
                        </div>
                    </div>
                </dl>

                <a href="<?php echo e(route('checkout.index')); ?>" class="group w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200 mb-3">
                    <span>Pokračovat k pokladně</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
                <a href="<?php echo e(route('products.index')); ?>" class="block w-full text-center bg-white hover:bg-gray-50 text-gray-900 font-medium px-6 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200">
                    Pokračovat v nákupu
                </a>

                <!-- Trust Badges - Minimal -->
                <div class="mt-6 pt-6 border-t border-gray-100 space-y-2.5">
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Bezpečná platba</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>14 dní na vrácení</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Zákaznická podpora 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Recommendations - Minimal -->
<?php if(!empty($cartItems)): ?>
<section class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3 text-center tracking-tight">
            Mohlo by vás také zajímat
        </h2>
        <p class="text-center text-gray-600 mb-12 font-light">Doplňte si objednávku o další produkty</p>
        <!-- Placeholder for recommendations -->
        <div class="text-center">
            <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium px-6 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200">
                Procházet další produkty
            </a>
        </div>
    </div>
</section>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/cart/index.blade.php ENDPATH**/ ?>