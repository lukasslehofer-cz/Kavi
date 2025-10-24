<?php $__env->startSection('title', 'Obchod - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="bg-white py-16 border-b border-bluegray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h1 class="font-display text-3xl md:text-4xl font-black text-dark-800 mb-4">Brew Shop</h1>
            <p class="text-lg text-dark-600 max-w-2xl mx-auto">Objevte naši pečlivě vybranou kolekci prémiových káv a vybavení</p>
        </div>
        
        <!-- Filters -->
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="<?php echo e(route('products.index')); ?>" 
               class="px-6 py-2.5 font-bold text-sm uppercase tracking-wide transition-all <?php echo e(!request('category') ? 'bg-primary-500 text-white' : 'bg-bluegray-100 text-dark-700 hover:bg-bluegray-200'); ?>">
                Vše
            </a>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('products.index', ['category' => $key])); ?>" 
               class="px-6 py-2.5 font-bold text-sm uppercase tracking-wide transition-all <?php echo e(request('category') == $key ? 'bg-primary-500 text-white' : 'bg-bluegray-100 text-dark-700 hover:bg-bluegray-200'); ?>">
                <?php echo e($label); ?>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a href="<?php echo e(route('products.show', $product)); ?>" class="group bg-white overflow-hidden hover:shadow-xl transition-all duration-300">
            <div class="aspect-square overflow-hidden bg-bluegray-50">
                <?php if($product->image): ?>
                <img src="<?php echo e($product->image); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <?php else: ?>
                <div class="w-full h-full flex flex-col items-center justify-center p-8 bg-gradient-to-br from-bluegray-100 to-bluegray-200">
                    <svg class="w-16 h-16 text-bluegray-400 mb-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                    <p class="text-center text-xs text-dark-600 font-bold"><?php echo e($product->name); ?></p>
                </div>
                <?php endif; ?>
            </div>
            <div class="p-5">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <h3 class="font-bold text-dark-800 mb-1 group-hover:text-primary-500 transition-colors text-base">
                            <?php echo e($product->name); ?>

                        </h3>
                        <?php if($product->short_description): ?>
                        <p class="text-xs text-dark-600 mb-3 line-clamp-2"><?php echo e($product->short_description); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-bluegray-100">
                    <div>
                        <span class="text-xl font-black text-dark-800"><?php echo e(number_format($product->price, 0, ',', ' ')); ?></span>
                        <span class="text-sm text-dark-600"> Kč</span>
                    </div>
                    <?php if($product->isInStock()): ?>
                    <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1">SKLADEM</span>
                    <?php else: ?>
                    <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1">VYPRODÁNO</span>
                    <?php endif; ?>
                </div>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full text-center py-16">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 text-bluegray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-dark-600 text-lg font-bold">Žádné produkty nebyly nalezeny.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($products->hasPages()): ?>
    <div class="flex justify-center">
        <?php echo e($products->links()); ?>

    </div>
    <?php endif; ?>
</div>

<!-- CTA Section -->
<section class="bg-primary-500 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-2xl md:text-3xl font-black mb-4">
            Chcete pravidelnou dodávku kávy?
        </h2>
        <p class="text-lg mb-8 opacity-95">
            Vyzkoušejte naše předplatné a ušetřete až 20%. Čerstvá káva každý měsíc bez starostí.
        </p>
        <a href="<?php echo e(route('subscriptions.index')); ?>" class="btn bg-white text-dark-800 hover:bg-bluegray-50 text-lg">
            Zjistit více o předplatném
        </a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/products/index.blade.php ENDPATH**/ ?>