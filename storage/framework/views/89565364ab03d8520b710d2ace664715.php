<?php $__env->startSection('title', $product->name . ' - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<!-- Minimal Breadcrumb -->
<div class="bg-white py-3 border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <nav class="text-sm">
        <ol class="flex items-center space-x-2 text-gray-500">
            <li><a href="<?php echo e(route('home')); ?>" class="hover:text-gray-900 transition-colors font-light">Domů</a></li>
            <li class="text-gray-300">
              <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </li>
            <li><a href="<?php echo e(route('products.index')); ?>" class="hover:text-gray-900 transition-colors font-light">Obchod</a></li>
            <li class="text-gray-300">
              <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </li>
            <li class="text-gray-900 font-medium truncate max-w-xs"><?php echo e($product->name); ?></li>
        </ol>
    </nav>
  </div>
</div>

<div class="bg-white">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

    <!-- Product Detail -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 mb-20">
        <!-- Product Image - Minimal -->
        <div class="lg:sticky lg:top-24 h-fit">
            <div class="relative aspect-square rounded-2xl overflow-hidden border border-gray-200 bg-gray-50">
                <?php if($product->image): ?>
                <img src="<?php echo e(asset($product->image)); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                <div class="w-full h-full flex flex-col items-center justify-center p-12 bg-gray-100">
                    <svg class="w-24 h-24 text-gray-300 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                    <p class="text-center text-gray-500 font-light text-sm"><?php echo e($product->name); ?></p>
                </div>
                <?php endif; ?>
                
                <!-- Category Tags - Minimal -->
                <div class="absolute left-3 top-3 flex flex-wrap gap-1.5">
                  <?php
                    $categoryLabels = [
                      'espresso' => ['label' => 'Espresso', 'color' => 'bg-amber-500'],
                      'filter' => ['label' => 'Filtr', 'color' => 'bg-blue-500'],
                      'accessories' => ['label' => 'Příslušenství', 'color' => 'bg-purple-500'],
                      'merch' => ['label' => 'Merch', 'color' => 'bg-green-500'],
                    ];
                    
                    if (is_array($product->category) && !empty($product->category)) {
                      foreach ($product->category as $cat) {
                        if (isset($categoryLabels[$cat])) {
                          $catData = $categoryLabels[$cat];
                          echo '<span class="px-2.5 py-1 rounded-full text-xs font-medium ' . $catData['color'] . ' text-white">' . $catData['label'] . '</span>';
                        }
                      }
                    }
                  ?>
                </div>

                <!-- Featured Badge - Minimal -->
                <?php if($product->is_featured): ?>
                <div class="absolute top-3 right-3 bg-gray-900 rounded-full px-3 py-1">
                  <span class="text-xs font-medium text-white flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Featured
                  </span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <!-- Stock Status - Minimal -->
            <div class="flex flex-wrap items-center gap-2 mb-5">
              <?php if($product->stock > 0): ?>
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                Na skladě
              </span>
              <?php else: ?>
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                Vyprodáno
              </span>
              <?php endif; ?>
            </div>
            
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight tracking-tight">
                <?php echo e($product->name); ?>

            </h1>
            
            <!-- Roaster / Manufacturer - Minimal -->
            <?php if($product->roastery): ?>
            <p class="text-base text-gray-600 font-light mb-5 flex items-center gap-2">
              <span class="text-xl"><?php echo e($product->roastery->country_flag); ?></span>
              <a href="<?php echo e(route('roasteries.show', $product->roastery)); ?>" class="hover:text-gray-900 transition-colors">
                <?php echo e($product->roastery->name); ?>

              </a>
            </p>
            <?php elseif(!empty($product->attributes['roaster'])): ?>
            <p class="text-base text-gray-600 font-light mb-5 flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <?php echo e($product->attributes['roaster']); ?>

            </p>
            <?php elseif(!empty($product->attributes['manufacturer'])): ?>
            <p class="text-base text-gray-600 font-light mb-5 flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
              </svg>
              <?php echo e($product->attributes['manufacturer']); ?>

            </p>
            <?php endif; ?>
            
            <?php if($product->short_description): ?>
            <p class="text-lg text-gray-600 mb-8 leading-relaxed font-light"><?php echo e($product->short_description); ?></p>
            <?php endif; ?>
            
            <!-- Flavor Profile - Minimal -->
            <?php if(!empty($product->attributes['flavor_profile'])): ?>
            <div class="bg-gray-50 rounded-2xl p-5 mb-8 border border-gray-200">
              <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                  <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Chuťový profil</h3>
                  <p class="text-base font-medium text-gray-900"><?php echo e($product->attributes['flavor_profile']); ?></p>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <div class="flex items-baseline gap-2 mb-8 pb-8 border-b border-gray-200">
                <span class="text-5xl font-bold text-gray-900"><?php echo e(number_format($product->price, 0, ',', ' ')); ?></span>
                <span class="text-xl text-gray-500 font-light">Kč</span>
            </div>

            <?php if($product->isInStock()): ?>
            <form action="<?php echo e(route('cart.add', $product)); ?>" method="POST" class="mb-10">
                <?php echo csrf_field(); ?>
                <div class="bg-gray-50 rounded-2xl p-5 mb-5 border border-gray-200">
                    <label class="block text-gray-900 font-semibold mb-3 text-sm">
                      Množství:
                    </label>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-gray-300 rounded-full overflow-hidden bg-white">
                            <button type="button" class="px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors font-medium">-</button>
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo e($product->stock); ?>" 
                                   class="w-16 text-center font-medium border-0 focus:ring-0 bg-transparent text-gray-900" data-quantity-input>
                            <button type="button" class="px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors font-medium">+</button>
                        </div>
                        <span class="text-gray-500 font-light text-sm">
                            <span class="font-medium text-gray-900"><?php echo e($product->stock); ?></span> ks skladem
                        </span>
                    </div>
                </div>
                
                <button type="submit" class="group w-full bg-gray-900 hover:bg-gray-800 text-white font-medium px-8 py-3.5 rounded-full transition-all duration-200 mb-5 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Přidat do košíku</span>
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div class="flex items-center gap-2 bg-green-50 rounded-full px-3 py-2 border border-green-200">
                        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-medium text-green-800">Doprava zdarma</span>
                    </div>
                    <div class="flex items-center gap-2 bg-blue-50 rounded-full px-3 py-2 border border-blue-200">
                        <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-medium text-blue-800">Čerstvé pražení</span>
                    </div>
                </div>
            </form>
            <?php else: ?>
            <div class="bg-red-50 border border-red-200 rounded-2xl text-red-700 px-5 py-4 mb-8 flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                  <p class="font-semibold text-base">Momentálně vyprodáno</p>
                  <p class="text-sm text-red-600 font-light">Tento produkt se brzy vrátí na sklad.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Product Description - Minimal -->
            <div class="mb-8 bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <div class="flex items-center gap-2.5 mb-4">
                  <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <h3 class="text-xl font-bold text-gray-900">O produktu</h3>
                </div>
                <div class="text-gray-700 leading-relaxed prose max-w-none font-light">
                    <?php echo nl2br(e($product->description)); ?>

                </div>
            </div>

            <!-- Product Attributes - Minimal -->
            <?php if($product->attributes): ?>
            <div class="bg-white rounded-2xl p-6 border border-gray-200">
                <div class="flex items-center gap-2.5 mb-4">
                  <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                  </div>
                  <h3 class="text-xl font-bold text-gray-900">Specifikace</h3>
                </div>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <?php $__currentLoopData = $product->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!in_array($key, ['roaster', 'flavor_profile', 'preparation_methods'])): ?>
                    <div class="bg-gray-50 rounded-xl p-3.5 border border-gray-100">
                        <dt class="text-gray-600 font-medium text-xs uppercase tracking-wide mb-1"><?php echo e(str_replace('_', ' ', ucfirst($key))); ?></dt>
                        <dd class="text-gray-900 font-semibold">
                          <?php if(is_array($value)): ?>
                            <?php echo e(implode(', ', $value)); ?>

                          <?php else: ?>
                            <?php echo e($value); ?>

                          <?php endif; ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </dl>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Products - Minimal -->
    <?php if($relatedProducts->count() > 0): ?>
    <section class="mt-20 pt-16 border-t border-gray-100">
        <div class="text-center mb-10">
          <div class="inline-flex items-center gap-2 bg-gray-100 rounded-full px-3 py-1.5 mb-3">
            <svg class="w-4 h-4 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
            <span class="text-xs font-medium text-gray-900">Další doporučení</span>
          </div>
          <h2 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">
            Mohlo by vás také zajímat
          </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group relative bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-gray-300 transition-all duration-200">
                <a href="<?php echo e(route('products.show', $relatedProduct)); ?>" class="relative block h-56 overflow-hidden bg-gray-50">
                    <?php if($relatedProduct->image): ?>
                    <img src="<?php echo e(asset($relatedProduct->image)); ?>" alt="<?php echo e($relatedProduct->name); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                    <div class="w-full h-full flex flex-col items-center justify-center p-6 bg-gray-100">
                        <svg class="w-12 h-12 text-gray-300 mb-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                        </svg>
                        <p class="text-center text-xs font-light text-gray-500"><?php echo e($relatedProduct->name); ?></p>
                    </div>
                    <?php endif; ?>
                </a>
                <div class="p-4">
                    <a href="<?php echo e(route('products.show', $relatedProduct)); ?>" class="block">
                      <h3 class="text-base font-semibold text-gray-900 group-hover:text-gray-600 transition-colors mb-2 line-clamp-2">
                          <?php echo e($relatedProduct->name); ?>

                      </h3>
                    </a>
                    <div class="flex items-center justify-between pt-2.5 border-t border-gray-100">
                      <span class="text-lg font-bold text-gray-900"><?php echo e(number_format($relatedProduct->price, 0, ',', ' ')); ?> Kč</span>
                      <a href="<?php echo e(route('products.show', $relatedProduct)); ?>" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-900 hover:bg-gray-800 text-white transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                      </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
    <?php endif; ?>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/products/show.blade.php ENDPATH**/ ?>