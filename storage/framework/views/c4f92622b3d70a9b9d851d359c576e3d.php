<?php $__env->startSection('title', 'Správa produktů'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Správa produktů</h1>
            <p class="text-gray-600 mt-1">Spravujte produkty ve vašem eshopu</p>
        </div>
        <a href="<?php echo e(route('admin.products.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Přidat produkt
        </a>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Název</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sklad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    <?php if($product->image): ?>
                                    <img src="<?php echo e(asset($product->image)); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <?php endif; ?>
                                </div>
                                <span class="font-medium text-gray-900"><?php echo e($product->name); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex flex-wrap gap-1">
                                <?php
                                    $categoryLabels = [
                                        'espresso' => 'Espresso',
                                        'filter' => 'Filtr',
                                        'accessories' => 'Příslušenství',
                                        'merch' => 'Merch',
                                    ];
                                    $categories = is_array($product->category) ? $product->category : [$product->category];
                                ?>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <?php echo e($categoryLabels[$cat] ?? ucfirst($cat)); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900"><?php echo e(number_format($product->price, 0, ',', ' ')); ?> Kč</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($product->stock); ?> ks
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($product->is_active): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktivní</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Neaktivní</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Upravit
                                </a>
                                <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" onclick="return confirm('Opravdu smazat?')" class="text-red-600 hover:text-red-800 font-medium">
                                        Smazat
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="mb-2">Žádné produkty</p>
                            <a href="<?php echo e(route('admin.products.create')); ?>" class="text-blue-600 hover:text-blue-800 font-medium">Přidat první produkt</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($products->hasPages()): ?>
    <div class="mt-6">
        <?php echo e($products->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/products/index.blade.php ENDPATH**/ ?>