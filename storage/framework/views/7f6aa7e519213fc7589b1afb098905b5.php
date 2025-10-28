<?php $__env->startSection('title', $roastery->name . ' - Naše pražírny - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero with Breadcrumb -->
<div class="relative bg-gradient-to-br from-gray-50 via-slate-50 to-gray-100 py-8 border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="text-sm">
      <ol class="flex items-center space-x-2 text-gray-600">
        <li><a href="<?php echo e(route('home')); ?>" class="hover:text-primary-600 transition-colors font-medium">Domů</a></li>
        <li class="text-gray-400">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </li>
        <li><a href="<?php echo e(route('roasteries.index')); ?>" class="hover:text-primary-600 transition-colors font-medium">Naše pražírny</a></li>
        <li class="text-gray-400">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </li>
        <li class="text-gray-900 font-bold truncate max-w-xs"><?php echo e($roastery->name); ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="bg-white">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

    <!-- Roastery Detail - Info LEFT, Image RIGHT -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 mb-20">
        <!-- Roastery Info -->
        <div>
            <!-- Country Flag - simple, no box -->
            <div class="mb-6">
              <span class="text-8xl"><?php echo e($roastery->country_flag); ?></span>
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-4 leading-tight">
                <?php echo e($roastery->name); ?>

            </h1>
            
            <!-- Location -->
            <div class="mb-6">
              <p class="text-lg text-gray-600 font-medium flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="font-bold"><?php echo e($roastery->country); ?></span>
                <?php if($roastery->city): ?>
                <span class="text-gray-400">•</span>
                <span><?php echo e($roastery->city); ?></span>
                <?php endif; ?>
              </p>
              <?php if($roastery->address): ?>
              <p class="text-sm text-gray-500 ml-7 mt-1"><?php echo e($roastery->address); ?></p>
              <?php endif; ?>
            </div>
            
            <?php if($roastery->short_description): ?>
            <p class="text-xl text-gray-600 mb-8 leading-relaxed"><?php echo e($roastery->short_description); ?></p>
            <?php endif; ?>
            
            <!-- Links -->
            <div class="flex flex-wrap items-center gap-4 mb-8">
              <?php if($roastery->website_url): ?>
              <a href="<?php echo e($roastery->website_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                <span>Navštívit web</span>
              </a>
              <?php endif; ?>
              
              <?php if($roastery->instagram): ?>
              <a href="https://instagram.com/<?php echo e(str_replace('@', '', $roastery->instagram)); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                <span><?php echo e($roastery->instagram); ?></span>
              </a>
              <?php endif; ?>
            </div>
            
            <!-- Full Description with gallery images mixed in -->
            <?php if($roastery->full_description): ?>
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
              <?php
                $paragraphs = explode("\n", $roastery->full_description);
                $galleryImages = $roastery->gallery ?? [];
                $totalParagraphs = count($paragraphs);
                $galleryIndex = 0;
              ?>
              
              <?php $__currentLoopData = $paragraphs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(trim($paragraph)): ?>
                <p class="mb-6"><?php echo e($paragraph); ?></p>
                <?php endif; ?>
                
                <!-- Insert gallery image after every 2 paragraphs -->
                <?php if($galleryImages && $galleryIndex < count($galleryImages) && ($index + 1) % 2 == 0 && $index > 0): ?>
                <div class="my-8 <?php echo e($galleryIndex % 2 == 0 ? 'float-right ml-6 mb-6' : 'float-left mr-6 mb-6'); ?> w-full sm:w-1/2 lg:w-2/5">
                  <img src="<?php echo e(asset($galleryImages[$galleryIndex])); ?>" alt="<?php echo e($roastery->name); ?>" class="w-full rounded-2xl shadow-xl">
                </div>
                <?php $galleryIndex++; ?>
                <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              
              <!-- Show remaining gallery images at the end -->
              <?php if($galleryImages && $galleryIndex < count($galleryImages)): ?>
              <div class="clear-both pt-8 grid grid-cols-2 gap-4">
                <?php for($i = $galleryIndex; $i < count($galleryImages); $i++): ?>
                <div class="relative aspect-square rounded-xl overflow-hidden shadow-lg">
                  <img src="<?php echo e(asset($galleryImages[$i])); ?>" alt="<?php echo e($roastery->name); ?>" class="w-full h-full object-cover">
                </div>
                <?php endfor; ?>
              </div>
              <?php endif; ?>
              
              <div class="clear-both"></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Roastery Image (RIGHT side, sticky) -->
        <div class="lg:sticky lg:top-24 h-fit">
            <div class="relative aspect-square rounded-3xl overflow-hidden shadow-2xl border-2 border-gray-200 bg-gradient-to-br from-gray-100 to-gray-50">
                <?php if($roastery->image): ?>
                <img src="<?php echo e(asset($roastery->image)); ?>" alt="<?php echo e($roastery->name); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                <div class="w-full h-full flex flex-col items-center justify-center p-12 bg-gradient-to-br from-primary-100 to-pink-100">
                    <svg class="w-32 h-32 text-primary-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-center text-primary-700 font-semibold"><?php echo e($roastery->name); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Coffees Section -->
    <div>
      <div class="text-center mb-12">
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
          Naše kávy od <span class="bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent"><?php echo e($roastery->name); ?></span>
        </h2>
        <p class="text-xl text-gray-600">
          Prozkoumejte všechny kávy, které jsme od této pražírny měli nebo momentálně máme v nabídce
        </p>
      </div>

      <!-- Coffee of Month Products + Subscription Promo (Priority #1) -->
      <?php if($coffeeOfMonthProducts->count() > 0): ?>
      <div class="mb-16">
        <div class="flex items-center gap-3 mb-6">
          <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-pink-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </div>
          <h3 class="text-2xl font-black text-gray-900">V aktuálním předplatném</h3>
        </div>
        
        <!-- Coffees Grid (max 3 columns for wider cards) -->
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 mb-8">
          <?php $__currentLoopData = $coffeeOfMonthProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <!-- Coffee of Month Card (no price, opens modal) -->
          <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-primary-200 hover:shadow-2xl transition-all duration-300">
            <!-- Coffee Image -->
            <div class="relative h-80 overflow-hidden cursor-pointer" onclick="openCoffeeModal(<?php echo e($product->id); ?>, '<?php echo e(addslashes($product->name)); ?>', '<?php echo e($product->image ? asset($product->image) : ''); ?>', '<?php echo e(addslashes($product->short_description ?? '')); ?>', <?php echo e(json_encode($product->attributes ?? [])); ?>)">
              <?php if($product->image): ?>
              <img src="<?php echo e(asset($product->image)); ?>" 
                   alt="<?php echo e($product->name); ?>"
                   class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
              <?php else: ?>
              <div class="w-full h-full bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                <svg class="w-32 h-32 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                </svg>
              </div>
              <?php endif; ?>

              <!-- Category Badges -->
              <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                <?php if(is_array($product->category)): ?>
                  <?php $__currentLoopData = $product->category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($cat === 'espresso'): ?>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-amber-500 text-white shadow-lg">Espresso</span>
                    <?php elseif($cat === 'filter'): ?>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-blue-500 text-white shadow-lg">Filtr</span>
                    <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
              </div>

              <!-- Overlay with click hint -->
              <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                <svg class="w-16 h-16 text-white opacity-0 hover:opacity-100 transition-opacity duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </div>
            </div>

            <!-- Coffee Info -->
            <div class="p-6">
              <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                <?php echo e($product->name); ?>

              </h3>

              <?php if($product->short_description): ?>
              <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                <?php echo e($product->short_description); ?>

              </p>
              <?php endif; ?>

              <button onclick="openCoffeeModal(<?php echo e($product->id); ?>, '<?php echo e(addslashes($product->name)); ?>', '<?php echo e($product->image ? asset($product->image) : ''); ?>', '<?php echo e(addslashes($product->short_description ?? '')); ?>', <?php echo e(json_encode($product->attributes ?? [])); ?>)" 
                      class="w-full py-3 bg-gradient-to-r from-primary-500 to-pink-600 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                Zobrazit detail
              </button>

              <p class="text-xs text-center text-gray-500 mt-3">
                Tuto kávu nelze zakoupit samostatně
              </p>
            </div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Subscription Promo Banner (below coffee cards) -->
        <div class="bg-gradient-to-r from-primary-600 to-pink-600 rounded-3xl shadow-2xl p-8 md:p-12 text-white">
          <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm mb-6">
              <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>
            <h3 class="text-3xl md:text-4xl font-black mb-4"><?php echo e($coffeeOfMonthProducts->count() === 1 ? 'Chcete tuto kávu vyzkoušet?' : 'Chcete tyto kávy vyzkoušet?'); ?></h3>
            <p class="text-lg md:text-xl text-primary-100 mb-8 leading-relaxed max-w-2xl mx-auto">
              Připojte se k našemu předplatnému a dostávejte každý měsíc exkluzivní kávy přímo od <?php echo e($roastery->name); ?> a dalších skvělých pražíren!
            </p>
            <a href="<?php echo e(route('subscriptions.index')); ?>" class="inline-flex items-center gap-2 bg-white text-primary-600 hover:bg-gray-100 font-bold py-4 px-8 rounded-xl transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
              <span>Vybrat předplatné</span>
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Other Coffees (Active + Historical in one section) -->
      <?php if($activeProducts->count() > 0 || $historicalProducts->count() > 0): ?>
      <div>
        <?php
          $allOtherProducts = $activeProducts->concat($historicalProducts);
        ?>
        
        <div class="flex items-center gap-3 mb-6">
          <div class="flex-shrink-0 w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </div>
          <h3 class="text-2xl font-black text-gray-900">Další kávy od <?php echo e($roastery->name); ?></h3>
        </div>
        
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          <?php $__currentLoopData = $allOtherProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php echo $__env->make('partials.product-card', ['product' => $product, 'historical' => !$product->is_active], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if($coffeeOfMonthProducts->count() == 0 && $activeProducts->count() == 0 && $historicalProducts->count() == 0): ?>
      <div class="text-center py-16 bg-gray-50 rounded-3xl">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Zatím žádné kávy</h3>
        <p class="text-gray-600">Od této pražírny momentálně nemáme žádné kávy v nabídce.</p>
      </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<!-- Coffee Modal -->
<div id="coffeeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" onclick="closeCoffeeModal(event)">
  <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl" onclick="event.stopPropagation()">
    <div class="relative">
      <!-- Close Button -->
      <button onclick="closeCoffeeModal()" class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-white/90 hover:bg-white text-gray-700 hover:text-gray-900 transition-colors shadow-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
      
      <!-- Coffee Image -->
      <div id="modalImage" class="w-full h-72 bg-gradient-to-br from-primary-100 to-pink-100 rounded-t-3xl overflow-hidden"></div>
      
      <!-- Content -->
      <div class="p-8">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-100 to-pink-100 rounded-full px-4 py-2 mb-4">
          <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <span class="text-sm font-bold text-primary-700">V aktuálním předplatném</span>
        </div>
        
        <h2 id="modalTitle" class="text-3xl md:text-4xl font-black text-gray-900 mb-4"></h2>
        
        <div id="modalDescription" class="text-lg text-gray-600 mb-6"></div>
        
        <!-- Attributes -->
        <div id="modalAttributes" class="space-y-3 mb-8"></div>
        
        <!-- CTA -->
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border-2 border-gray-200">
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-primary-500 to-pink-600 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
              </svg>
            </div>
            <div class="flex-1">
              <h3 class="font-bold text-gray-900 mb-2">Chcete tuto kávu vyzkoušet?</h3>
              <p class="text-sm text-gray-600 mb-4">Tato káva je dostupná pouze v našem měsíčním předplatném. Připojte se a každý měsíc objevujte nové chuti!</p>
              <a href="<?php echo e(route('subscriptions.index')); ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-pink-600 hover:from-primary-700 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span>Vybrat předplatné</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function openCoffeeModal(id, name, image, description, attributes) {
  const modal = document.getElementById('coffeeModal');
  const modalImage = document.getElementById('modalImage');
  const modalTitle = document.getElementById('modalTitle');
  const modalDescription = document.getElementById('modalDescription');
  const modalAttributes = document.getElementById('modalAttributes');
  
  // Set title
  modalTitle.textContent = name;
  
  // Set image
  if (image) {
    modalImage.innerHTML = `<img src="${image}" alt="${name}" class="w-full h-full object-cover">`;
  } else {
    modalImage.innerHTML = `
      <div class="w-full h-full flex items-center justify-center">
        <svg class="w-20 h-20 text-primary-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
        </svg>
      </div>
    `;
  }
  
  // Set description
  modalDescription.textContent = description || '';
  
  // Set attributes
  modalAttributes.innerHTML = '';
  if (attributes) {
    if (attributes.flavor_profile) {
      modalAttributes.innerHTML += `
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-primary-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
          </svg>
          <div>
            <p class="font-semibold text-gray-900">Chuťový profil</p>
            <p class="text-gray-700">${attributes.flavor_profile}</p>
          </div>
        </div>
      `;
    }
    if (attributes.origin) {
      modalAttributes.innerHTML += `
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-primary-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <div>
            <p class="font-semibold text-gray-900">Původ</p>
            <p class="text-gray-700">${attributes.origin}</p>
          </div>
        </div>
      `;
    }
    if (attributes.process) {
      modalAttributes.innerHTML += `
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-primary-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
          </svg>
          <div>
            <p class="font-semibold text-gray-900">Zpracování</p>
            <p class="text-gray-700">${attributes.process}</p>
          </div>
        </div>
      `;
    }
  }
  
  // Show modal
  modal.classList.remove('hidden');
  modal.classList.add('flex');
  document.body.style.overflow = 'hidden';
}

function closeCoffeeModal(event) {
  if (event && event.target !== event.currentTarget) return;
  
  const modal = document.getElementById('coffeeModal');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
  document.body.style.overflow = '';
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeCoffeeModal();
  }
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/roasteries/show.blade.php ENDPATH**/ ?>