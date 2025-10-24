<?php $__env->startSection('title', $product->name . ' - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-8">
        <ol class="flex items-center space-x-2 text-dark-600">
            <li><a href="<?php echo e(route('home')); ?>" class="hover:text-primary-500 transition-colors">Domů</a></li>
            <li class="text-dark-400">/</li>
            <li><a href="<?php echo e(route('products.index')); ?>" class="hover:text-primary-500 transition-colors">Obchod</a></li>
            <li class="text-dark-400">/</li>
            <li class="text-dark-800 font-medium"><?php echo e($product->name); ?></li>
        </ol>
    </nav>

    <!-- Product Detail -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 mb-20">
        <!-- Product Image -->
        <div class="sticky top-24">
            <div class="aspect-square  overflow-hidden shadow-xl img-placeholder">
                <?php if($product->image): ?>
                <img src="<?php echo e($product->image); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                <div class="w-full h-full flex flex-col items-center justify-center p-12">
                    <svg class="w-32 h-32 text-bluegray-300 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                    <p class="text-center">Detail balení prémiové kávy: <?php echo e($product->name); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <div class="mb-4">
                <span class="badge badge-primary"><?php echo e($product->category ?? 'Káva'); ?></span>
            </div>
            
            <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-dark-800 mb-6 leading-tight">
                <?php echo e($product->name); ?>

            </h1>
            
            <?php if($product->short_description): ?>
            <p class="text-xl text-dark-600 mb-8 leading-relaxed"><?php echo e($product->short_description); ?></p>
            <?php endif; ?>

            <div class="flex items-baseline gap-3 mb-8">
                <span class="text-5xl font-bold text-dark-800"><?php echo e(number_format($product->price, 0, ',', ' ')); ?></span>
                <span class="text-2xl text-dark-600">Kč</span>
            </div>

            <?php if($product->isInStock()): ?>
            <form action="<?php echo e(route('cart.add', $product)); ?>" method="POST" class="mb-10">
                <?php echo csrf_field(); ?>
                <div class="bg-bluegray-50  p-6 mb-6">
                    <label class="block text-dark-700 font-semibold mb-3">Množství:</label>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center border-2 border-bluegray-200  overflow-hidden bg-white">
                            <button type="button" class="px-6 py-4 text-dark-700 hover:bg-bluegray-100 transition-colors font-semibold">-</button>
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo e($product->stock); ?>" 
                                   class="w-20 text-center text-lg font-semibold border-0 focus:ring-0 bg-transparent" data-quantity-input>
                            <button type="button" class="px-6 py-4 text-dark-700 hover:bg-bluegray-100 transition-colors font-semibold">+</button>
                        </div>
                        <span class="text-dark-600">
                            <span class="font-semibold text-dark-800"><?php echo e($product->stock); ?></span> ks skladem
                        </span>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary text-lg w-full mb-4">
                    <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Přidat do košíku
                </button>
                
                <div class="flex items-center justify-center gap-6 text-sm text-dark-600">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Doprava zdarma nad 1000 Kč
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Čerstvé pražení
                    </span>
                </div>
            </form>
            <?php else: ?>
            <div class="bg-red-50 border-2 border-red-200 text-red-700 px-6 py-4  mb-8">
                <p class="font-semibold">Tento produkt je momentálně vyprodaný.</p>
            </div>
            <?php endif; ?>

            <!-- Product Description -->
            <div class="mb-10">
                <h3 class="font-display text-2xl font-bold text-dark-800 mb-4">O produktu</h3>
                <div class="text-dark-700 leading-relaxed prose max-w-none">
                    <?php echo nl2br(e($product->description)); ?>

                </div>
            </div>

            <!-- Product Attributes -->
            <?php if($product->attributes): ?>
            <div class="bg-bluegray-50  p-8">
                <h3 class="font-display text-xl font-bold text-dark-800 mb-6">Specifikace</h3>
                <dl class="space-y-4">
                    <?php $__currentLoopData = $product->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex justify-between border-b border-bluegray-200 pb-3">
                        <dt class="text-dark-600 font-medium"><?php echo e(ucfirst($key)); ?>:</dt>
                        <dd class="text-dark-800 font-semibold"><?php echo e($value); ?></dd>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </dl>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Products -->
    <?php if($relatedProducts->count() > 0): ?>
    <section class="mt-20">
        <h2 class="font-display text-3xl md:text-4xl font-bold text-dark-800 mb-10 text-center">Mohlo by vás také zajímat</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('products.show', $relatedProduct)); ?>" class="card card-hover group">
                <div class="aspect-square overflow-hidden img-placeholder">
                    <?php if($relatedProduct->image): ?>
                    <img src="<?php echo e($relatedProduct->image); ?>" alt="<?php echo e($relatedProduct->name); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                    <div class="w-full h-full flex flex-col items-center justify-center p-6">
                        <svg class="w-16 h-16 text-bluegray-300 mb-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                        </svg>
                        <p class="text-center text-xs"><?php echo e($relatedProduct->name); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="p-5">
                    <h3 class="font-display text-lg font-bold text-dark-800 mb-2 group-hover:text-primary-500 transition-colors line-clamp-1">
                        <?php echo e($relatedProduct->name); ?>

                    </h3>
                    <span class="text-xl font-bold text-dark-800"><?php echo e(number_format($relatedProduct->price, 0, ',', ' ')); ?> Kč</span>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/products/show.blade.php ENDPATH**/ ?>