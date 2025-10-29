<?php $__env->startSection('title', 'Slevové kupóny - Admin Panel'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Slevové kupóny</h1>
            <p class="text-gray-600 mt-1">Správa slevových kupónů pro objednávky a předplatné</p>
        </div>
        <a href="<?php echo e(route('admin.coupons.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nový kupón
        </a>
    </div>

    <!-- Success Message -->
    <?php if(session('success')): ?>
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <!-- Coupons Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kód</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Název</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Typ slevy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platnost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Použití</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono font-bold text-blue-600"><?php echo e($coupon->code); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($coupon->name ?? '-'); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm space-y-1">
                                <?php if($coupon->hasOrderDiscount()): ?>
                                    <div class="text-gray-900">📦 <?php echo e($coupon->getOrderDiscountDescription()); ?></div>
                                <?php endif; ?>
                                <?php if($coupon->hasSubscriptionDiscount()): ?>
                                    <div class="text-gray-900">🔄 <?php echo e($coupon->getSubscriptionDiscountDescription()); ?></div>
                                <?php endif; ?>
                                <?php if(!$coupon->hasOrderDiscount() && !$coupon->hasSubscriptionDiscount()): ?>
                                    <span class="text-gray-400">Žádná sleva</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                <?php if($coupon->valid_from && $coupon->valid_until): ?>
                                    <?php echo e($coupon->valid_from->format('d.m.Y')); ?> - <?php echo e($coupon->valid_until->format('d.m.Y')); ?>

                                <?php elseif($coupon->valid_from): ?>
                                    Od <?php echo e($coupon->valid_from->format('d.m.Y')); ?>

                                <?php elseif($coupon->valid_until): ?>
                                    Do <?php echo e($coupon->valid_until->format('d.m.Y')); ?>

                                <?php else: ?>
                                    <span class="text-green-600">Neomezená</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900"><?php echo e($coupon->times_used); ?>× použito</div>
                                <?php if($coupon->usage_limit_total): ?>
                                    <div class="text-gray-500 text-xs">Max: <?php echo e($coupon->usage_limit_total); ?>×</div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($coupon->is_active && $coupon->isValid()): ?>
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Aktivní</span>
                            <?php elseif($coupon->is_active): ?>
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Neplatný</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Neaktivní</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="<?php echo e(route('admin.coupons.stats', $coupon)); ?>" class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Statistiky
                            </a>
                            <a href="<?php echo e(route('admin.coupons.edit', $coupon)); ?>" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Upravit
                            </a>
                            <form action="<?php echo e(route('admin.coupons.destroy', $coupon)); ?>" method="POST" class="inline" onsubmit="return confirm('Opravdu chcete smazat tento kupón?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Smazat
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-lg font-medium mb-1">Žádné kupóny</p>
                                <p class="text-sm">Vytvořte první slevový kupón</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($coupons->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($coupons->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/coupons/index.blade.php ENDPATH**/ ?>