<?php $__env->startSection('title', 'Správa produktů - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Správa produktů</h1>
            <p class="text-coffee-600">Spravujte produkty ve vašem eshopu</p>
        </div>
        <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Přidat produkt
        </a>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-cream-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Název</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Kategorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Sklad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-cream-200">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-cream-100 rounded-lg mr-3 flex-shrink-0 flex items-center justify-center">
                                    <?php if($product->image): ?>
                                    <img src="<?php echo e(asset($product->image)); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover rounded-lg">
                                    <?php else: ?>
                                    <svg class="w-6 h-6 text-cream-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <?php endif; ?>
                                </div>
                                <span class="font-medium text-coffee-900"><?php echo e($product->name); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-coffee-600"><?php echo e(ucfirst($product->category)); ?></td>
                        <td class="px-6 py-4 text-sm font-bold text-coffee-900"><?php echo e(number_format($product->price, 0, ',', ' ')); ?> Kč</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="<?php echo e($product->stock > 0 ? 'text-green-600' : 'text-red-600'); ?> font-medium">
                                <?php echo e($product->stock); ?> ks
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($product->is_active): ?>
                            <span class="badge badge-success">Aktivní</span>
                            <?php else: ?>
                            <span class="badge">Neaktivní</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="text-coffee-700 hover:text-coffee-900 underline">
                                Upravit
                            </a>
                            <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" onclick="return confirm('Opravdu smazat?')" class="text-red-600 hover:text-red-800 underline">
                                    Smazat
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-coffee-600">
                            Žádné produkty. <a href="<?php echo e(route('admin.products.create')); ?>" class="text-coffee-700 underline">Přidat první produkt</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($products->hasPages()): ?>
    <div class="mt-8 flex justify-center">
        <?php echo e($products->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/products/index.blade.php ENDPATH**/ ?>