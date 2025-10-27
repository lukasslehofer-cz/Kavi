<?php $__env->startSection('title', 'Nákupní košík - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-bluegray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <ol class="flex items-center space-x-2 text-dark-600">
                <li><a href="<?php echo e(route('home')); ?>" class="hover:text-primary-500 transition-colors">Domů</a></li>
                <li class="text-dark-400">/</li>
                <li class="text-dark-800 font-medium">Nákupní košík</li>
            </ol>
        </nav>

        <h1 class="font-display text-4xl md:text-5xl font-bold text-dark-800 mb-2">Nákupní košík</h1>
        <p class="text-dark-600"><?php echo e(count($cartItems ?? [])); ?> <?php echo e(count($cartItems ?? []) === 1 ? 'položka' : 'položky'); ?> v košíku</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php if(empty($cartItems)): ?>
    <!-- Empty Cart -->
    <div class="max-w-2xl mx-auto">
        <div class="card p-12 text-center">
            <div class="w-32 h-32 bg-bluegray-100  flex items-center justify-center mx-auto mb-6">
                <svg class="w-16 h-16 text-bluegray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h2 class="font-display text-3xl font-bold text-dark-800 mb-4">Váš košík je prázdný</h2>
            <p class="text-dark-600 text-lg mb-8">Objevte naši nabídku prémiových káv a vyberte si tu pravou pro vás.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary">Procházet kávy</a>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="btn btn-outline">Zobrazit předplatné</a>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="space-y-6">
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card p-6 hover:shadow-lg transition-shadow">
                    <div class="flex gap-6">
                        <!-- Product Image -->
                        <div class="w-32 h-32  overflow-hidden flex-shrink-0 img-placeholder">
                            <?php if($item['product']->image): ?>
                            <img src="<?php echo e(asset($item['product']->image)); ?>" alt="<?php echo e($item['product']->name); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center p-4">
                                <svg class="w-12 h-12 text-bluegray-300 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                                </svg>
                                <p class="text-xs text-center"><?php echo e($item['product']->name); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Product Info -->
                        <div class="flex-grow">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-display text-xl font-bold text-dark-800 mb-1">
                                        <a href="<?php echo e(route('products.show', $item['product'])); ?>" class="hover:text-primary-500 transition-colors">
                                            <?php echo e($item['product']->name); ?>

                                        </a>
                                    </h3>
                                    <p class="text-dark-600"><?php echo e(number_format($item['product']->price, 0, ',', ' ')); ?> Kč / ks</p>
                                </div>
                                <p class="text-2xl font-bold text-dark-800"><?php echo e(number_format($item['subtotal'], 0, ',', ' ')); ?> Kč</p>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-4 mt-4">
                                <form action="<?php echo e(route('cart.update', $item['product']->id)); ?>" method="POST" class="flex items-center gap-3">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <div class="flex items-center border-2 border-bluegray-200  overflow-hidden bg-white">
                                        <button type="button" class="px-4 py-2 text-dark-700 hover:bg-bluegray-50 transition-colors font-semibold">-</button>
                                        <input type="number" name="quantity" value="<?php echo e($item['quantity']); ?>" min="1" max="<?php echo e($item['product']->stock); ?>" 
                                               class="w-16 text-center text-lg font-semibold border-0 focus:ring-0 py-2" data-quantity-input>
                                        <button type="button" class="px-4 py-2 text-dark-700 hover:bg-bluegray-50 transition-colors font-semibold">+</button>
                                    </div>
                                    <button type="submit" class="text-sm text-primary-500 hover:text-primary-600 font-semibold underline">
                                        Aktualizovat
                                    </button>
                                </form>

                                <!-- Remove Button -->
                                <form action="<?php echo e(route('cart.remove', $item['product']->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 text-red-500 hover:text-red-600 hover:bg-red-50  transition-colors">
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

            <!-- Clear Cart -->
            <div class="mt-6">
                <form action="<?php echo e(route('cart.clear')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-semibold flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1v3M4 7h16"/>
                        </svg>
                        Vyprázdnit celý košík
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="card p-8 sticky top-24">
                <h3 class="font-display text-2xl font-bold text-dark-800 mb-6">Souhrn objednávky</h3>
                
                <dl class="space-y-4 mb-6">
                    <div class="flex justify-between items-center">
                        <dt class="text-dark-600">Mezisoučet:</dt>
                        <dd class="font-semibold text-dark-800 text-lg"><?php echo e(number_format($total, 0, ',', ' ')); ?> Kč</dd>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <dt class="text-dark-600">Doprava:</dt>
                        <dd class="font-semibold text-lg">
                            <?php if($total >= 1000): ?>
                            <span class="text-green-600">Zdarma</span>
                            <?php else: ?>
                            <span class="text-dark-800">99 Kč</span>
                            <?php endif; ?>
                        </dd>
                    </div>

                    <?php if($total < 1000): ?>
                    <div class="bg-primary-50 border border-primary-200 p-4 ">
                        <p class="text-sm text-primary-700 font-medium">
                            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Do dopravy zdarma vám chybí <strong><?php echo e(number_format(1000 - $total, 0, ',', ' ')); ?> Kč</strong>
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="bg-green-50 border border-green-200 p-4 ">
                        <p class="text-sm text-green-700 font-medium">
                            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Získali jste dopravu zdarma!
                        </p>
                    </div>
                    <?php endif; ?>

                    <div class="border-t-2 border-bluegray-200 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <dt class="font-display font-bold text-dark-800 text-lg">Celkem:</dt>
                            <dd class="font-bold text-primary-500 text-3xl">
                                <?php echo e(number_format($total >= 1000 ? $total : $total + 99, 0, ',', ' ')); ?> Kč
                            </dd>
                        </div>
                    </div>
                </dl>

                <a href="<?php echo e(route('checkout.index')); ?>" class="btn btn-primary w-full text-center mb-3">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pokračovat k pokladně
                </a>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline w-full text-center">
                    Pokračovat v nákupu
                </a>

                <!-- Trust Badges -->
                <div class="mt-6 pt-6 border-t border-bluegray-200 space-y-3">
                    <div class="flex items-center text-sm text-dark-600">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Bezpečná platba</span>
                    </div>
                    <div class="flex items-center text-sm text-dark-600">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>14 dní na vrácení</span>
                    </div>
                    <div class="flex items-center text-sm text-dark-600">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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

<!-- Recommendations -->
<?php if(!empty($cartItems)): ?>
<section class="bg-bluegray-50 py-16 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-3xl md:text-4xl font-bold text-dark-800 mb-8 text-center">
            Mohlo by vás také zajímat
        </h2>
        <p class="text-center text-dark-600 mb-12">Doplňte si objednávku o další produkty</p>
        <!-- Placeholder for recommendations -->
        <div class="text-center text-dark-400">
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline">Procházet další produkty</a>
        </div>
    </div>
</section>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/cart/index.blade.php ENDPATH**/ ?>