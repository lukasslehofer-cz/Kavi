<?php $__env->startSection('title', 'Obchod - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white pb-6 sm:pb-8 lg:pb-12">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <!-- text - start -->
    <div class="mb-10 md:mb-16">
      <h2 class="mb-4 text-center text-2xl font-bold text-gray-800 md:mb-6 lg:text-4xl">Náš obchod</h2>

      <p class="mx-auto max-w-screen-md text-center text-gray-500 md:text-lg">Objevte naši pečlivě vybranou kolekci prémiových káv z nejlepších evropských pražíren a vybavení pro domácí přípravu.</p>
    </div>
    <!-- text - end -->

    <!-- Filters - start -->
    <div class="mb-10 flex flex-wrap justify-center gap-2">
      <a href="<?php echo e(route('products.index')); ?>" 
         class="inline-block rounded-lg px-6 py-2.5 text-center text-sm font-semibold outline-none ring-primary-300 transition duration-100 focus-visible:ring <?php echo e(!request('category') ? 'bg-primary-500 text-white hover:bg-primary-600 active:bg-primary-700' : 'bg-gray-200 text-gray-500 hover:bg-gray-300 active:text-gray-700'); ?>">
        Vše
      </a>
      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('products.index', ['category' => $key])); ?>" 
         class="inline-block rounded-lg px-6 py-2.5 text-center text-sm font-semibold outline-none ring-primary-300 transition duration-100 focus-visible:ring <?php echo e(request('category') == $key ? 'bg-primary-500 text-white hover:bg-primary-600 active:bg-primary-700' : 'bg-gray-200 text-gray-500 hover:bg-gray-300 active:text-gray-700'); ?>">
        <?php echo e($label); ?>

      </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <!-- Filters - end -->

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <!-- product - start -->
      <div>
        <a href="<?php echo e(route('products.show', $product)); ?>" class="group relative block h-96 overflow-hidden rounded-t-lg bg-gray-100">
          <?php if($product->image): ?>
          <img src="<?php echo e(asset($product->image)); ?>" loading="lazy" alt="<?php echo e($product->name); ?>" class="h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />
          <?php else: ?>
          <div class="h-full w-full flex flex-col items-center justify-center p-8 bg-gradient-to-br from-gray-200 to-gray-300">
            <svg class="w-16 h-16 text-gray-400 mb-3" fill="currentColor" viewBox="0 0 24 24">
              <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
            </svg>
            <p class="text-center text-xs font-semibold text-gray-500"><?php echo e($product->name); ?></p>
          </div>
          <?php endif; ?>

          <?php if($product->discount_percentage ?? false): ?>
          <span class="absolute left-0 top-3 rounded-r-lg bg-red-500 px-3 py-1.5 text-sm font-semibold uppercase tracking-wider text-white">-<?php echo e($product->discount_percentage); ?>%</span>
          <?php endif; ?>
        </a>

        <div class="flex items-start justify-between gap-2 rounded-b-lg bg-gray-100 p-4">
          <div class="flex flex-col">
            <a href="<?php echo e(route('products.show', $product)); ?>" class="font-bold text-gray-800 transition duration-100 hover:text-gray-500 lg:text-lg"><?php echo e($product->name); ?></a>
            <?php if($product->roaster ?? false): ?>
            <span class="text-sm text-gray-500 lg:text-base"><?php echo e($product->roaster); ?></span>
            <?php endif; ?>
          </div>

          <div class="flex flex-col items-end">
            <span class="font-bold text-gray-600 lg:text-lg"><?php echo e(number_format($product->price, 0, ',', ' ')); ?> Kč</span>
            <?php if($product->original_price ?? false): ?>
            <span class="text-sm text-red-500 line-through"><?php echo e(number_format($product->original_price, 0, ',', ' ')); ?> Kč</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!-- product - end -->
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="col-span-full text-center py-16">
        <div class="max-w-md mx-auto">
          <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
          </svg>
          <p class="text-gray-600 text-lg font-bold">Žádné produkty nebyly nalezeny.</p>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($products->hasPages()): ?>
    <div class="mt-12 flex justify-center">
      <?php echo e($products->links()); ?>

    </div>
    <?php endif; ?>
  </div>
</div>

<!-- CTA Section -->
<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-xl flex-col items-center text-center">
      <p class="mb-4 font-semibold text-primary-500 md:mb-6 md:text-lg xl:text-xl">Ušetřete až 20%</p>

      <h1 class="mb-8 text-3xl font-bold text-black sm:text-4xl md:mb-12 md:text-5xl">Chcete pravidelnou dodávku kávy?</h1>

      <div class="flex w-full flex-col gap-2.5 sm:flex-row sm:justify-center">
        <a href="<?php echo e(route('subscriptions.index')); ?>" class="inline-block rounded-lg bg-primary-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-primary-600 focus-visible:ring active:bg-primary-700 md:text-base">Zjistit více o předplatném</a>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/products/index.blade.php ENDPATH**/ ?>