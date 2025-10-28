<!-- Product Card -->
<div class="group relative bg-white rounded-2xl overflow-hidden shadow-xl transition-all duration-300 border-2 border-gray-200 <?php echo e($historical ?? false ? 'opacity-60 grayscale' : 'hover:shadow-2xl transform hover:-translate-y-2 hover:border-primary-300'); ?>">
  <!-- Image Container -->
  <?php if($historical ?? false): ?>
  <!-- Historical product - no link -->
  <div class="relative block h-80 overflow-hidden cursor-default">
  <?php else: ?>
  <!-- Active product - with link -->
  <a href="<?php echo e(route('products.show', $product)); ?>" class="relative block h-80 overflow-hidden">
  <?php endif; ?>
    <?php if($product->image): ?>
    <img src="<?php echo e(asset($product->image)); ?>" loading="lazy" alt="<?php echo e($product->name); ?>" class="h-full w-full object-cover object-center transition duration-500 <?php echo e($historical ?? false ? '' : 'group-hover:scale-110'); ?>" />
    <?php if(!($historical ?? false)): ?>
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    <?php endif; ?>
    <?php else: ?>
    <div class="h-full w-full flex flex-col items-center justify-center p-8 bg-gradient-to-br from-primary-100 to-pink-100">
      <svg class="w-20 h-20 text-primary-400 mb-3" fill="currentColor" viewBox="0 0 24 24">
        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
      </svg>
      <p class="text-center text-sm font-bold text-primary-600"><?php echo e($product->name); ?></p>
    </div>
    <?php endif; ?>

    <!-- Category/Type Tags - Top Left -->
    <div class="absolute left-3 top-3 flex flex-wrap gap-2">
      <?php if(isset($badge) && $badge === 'subscription'): ?>
      <span class="px-3 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-primary-500 to-pink-600 text-white shadow-lg flex items-center gap-1">
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
        Káva měsíce
      </span>
      <?php elseif($historical ?? false): ?>
      <span class="px-3 py-1 rounded-lg text-xs font-bold bg-gray-500 text-white shadow-lg">
        Historická
      </span>
      <?php else: ?>
      <?php
        // Pro kávy zobrazíme preparation methods
        if (!empty($product->attributes['preparation_methods'])) {
          $methods = $product->attributes['preparation_methods'];
          foreach ($methods as $method) {
            if ($method === 'espresso') {
              echo '<span class="px-3 py-1 rounded-lg text-xs font-bold bg-amber-500 text-white shadow-lg">Espresso</span>';
            } elseif ($method === 'filter') {
              echo '<span class="px-3 py-1 rounded-lg text-xs font-bold bg-blue-500 text-white shadow-lg">Filtr</span>';
            }
          }
        } 
        // Pro ostatní kategorie zobrazíme kategorii
        else {
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
                echo '<span class="px-3 py-1 rounded-lg text-xs font-bold ' . $catData['color'] . ' text-white shadow-lg">' . $catData['label'] . '</span>';
              }
            }
          }
        }
      ?>
      <?php endif; ?>
    </div>

    <!-- Stock Status - Top Right (only for active products) -->
    <?php if(!($historical ?? false) && !isset($badge)): ?>
    <?php if($product->stock > 0): ?>
    <div class="absolute right-3 top-3 bg-green-500 rounded-lg px-3 py-1.5 shadow-lg">
      <span class="text-xs font-bold text-white flex items-center gap-1">
        <span class="w-2 h-2 bg-white rounded-full"></span>
        Skladem
      </span>
    </div>
    <?php else: ?>
    <div class="absolute right-3 top-3 bg-red-500 rounded-lg px-3 py-1.5 shadow-lg">
      <span class="text-xs font-bold text-white">Vyprodáno</span>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <!-- Quick View Button (only for active products) -->
    <?php if(!($historical ?? false)): ?>
    <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
      <span class="flex items-center justify-center gap-2 w-full bg-white/95 backdrop-blur-sm text-gray-900 font-bold py-3 rounded-xl shadow-lg hover:bg-white transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <span>Zobrazit detail</span>
      </span>
    </div>
    <?php endif; ?>
  <?php if($historical ?? false): ?>
  </div>
  <?php else: ?>
  </a>
  <?php endif; ?>

  <!-- Product Info -->
  <div class="p-5">
    <div class="mb-3">
      <?php if($historical ?? false): ?>
      <div class="block">
        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2"><?php echo e($product->name); ?></h3>
      </div>
      <?php else: ?>
      <a href="<?php echo e(route('products.show', $product)); ?>" class="block">
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-2 line-clamp-2"><?php echo e($product->name); ?></h3>
      </a>
      <?php endif; ?>
      
      <!-- Roaster / Manufacturer -->
      <?php if($product->roastery): ?>
      <p class="text-sm text-gray-500 font-medium mb-2 flex items-center gap-1">
        <span class="text-lg"><?php echo e($product->roastery->country_flag); ?></span>
        <a href="<?php echo e(route('roasteries.show', $product->roastery)); ?>" class="hover:text-primary-600 transition-colors">
          <?php echo e($product->roastery->name); ?>

        </a>
      </p>
      <?php elseif(!empty($product->attributes['roaster'])): ?>
      <p class="text-sm text-gray-500 font-medium mb-2 flex items-center gap-1">
        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        <?php echo e($product->attributes['roaster']); ?>

      </p>
      <?php elseif(!empty($product->attributes['manufacturer'])): ?>
      <p class="text-sm text-gray-500 font-medium mb-2 flex items-center gap-1">
        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
        </svg>
        <?php echo e($product->attributes['manufacturer']); ?>

      </p>
      <?php endif; ?>
      
      <!-- Flavor Profile (for coffee) or Short Description -->
      <?php if(!empty($product->attributes['flavor_profile'])): ?>
      <p class="text-xs text-gray-600 line-clamp-2 italic">
        <?php echo e($product->attributes['flavor_profile']); ?>

      </p>
      <?php elseif($product->short_description): ?>
      <p class="text-xs text-gray-600 line-clamp-2">
        <?php echo e($product->short_description); ?>

      </p>
      <?php endif; ?>
    </div>

    <?php if(!($historical ?? false) && !isset($badge)): ?>
    <!-- Price & Add to Cart (only for active products not in subscription) -->
    <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-200">
      <div>
        <p class="text-2xl font-black text-gray-900">
          <?php echo e(number_format($product->price, 0, ',', ' ')); ?> Kč
        </p>
      </div>
      
      <?php if($product->stock > 0): ?>
      <form action="<?php echo e(route('cart.add', $product)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-600 to-pink-600 hover:from-primary-700 hover:to-pink-700 text-white font-bold py-2 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          <span>Do košíku</span>
        </button>
      </form>
      <?php else: ?>
      <span class="text-sm font-bold text-red-600">Nedostupné</span>
      <?php endif; ?>
    </div>
    <?php elseif($historical ?? false): ?>
    <!-- Historical Product - No Price/Cart -->
    <div class="pt-4 border-t border-gray-200">
      <p class="text-sm text-gray-500 italic">Tato káva již není v nabídce</p>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php /**PATH /var/www/html/resources/views/partials/product-card.blade.php ENDPATH**/ ?>