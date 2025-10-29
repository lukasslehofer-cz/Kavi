<?php $__env->startSection('title', 'Statistiky kupónu - Admin Panel'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <a href="<?php echo e(route('admin.coupons.index')); ?>" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Zpět na kupóny
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Statistiky kupónu</h1>
        <p class="text-gray-600 mt-1">Kód: <span class="font-mono font-bold text-blue-600"><?php echo e($coupon->code); ?></span> - <?php echo e($coupon->name); ?></p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
            <p class="text-white/80 text-sm font-medium mb-1">Celkové použití</p>
            <p class="text-4xl font-bold"><?php echo e($stats['total_uses']); ?>×</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-white/80 text-sm font-medium mb-1">Celková sleva</p>
            <p class="text-4xl font-bold"><?php echo e(number_format($stats['total_discount'], 0, ',', ' ')); ?><span class="text-xl"> Kč</span></p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <p class="text-white/80 text-sm font-medium mb-1">Unikátní uživatelé</p>
            <p class="text-4xl font-bold"><?php echo e($stats['unique_users']); ?></p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-white/80 text-sm font-medium mb-1">Typ použití</p>
            <p class="text-xl font-bold">📦 <?php echo e($stats['order_uses']); ?>× / 🔄 <?php echo e($stats['subscription_uses']); ?>×</p>
        </div>
    </div>

    <!-- Usage History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Historie použití</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Typ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uživatel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objednávka / Předplatné</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sleva</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $coupon->usages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo e($usage->created_at->format('d.m.Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($usage->usage_type === 'order'): ?>
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">📦 Objednávka</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">🔄 Předplatné</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <?php if($usage->user): ?>
                                <div>
                                    <div class="font-medium text-gray-900"><?php echo e($usage->user->name); ?></div>
                                    <div class="text-gray-500"><?php echo e($usage->user->email); ?></div>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400">Host</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <?php if($usage->order): ?>
                                <a href="<?php echo e(route('admin.orders.show', $usage->order)); ?>" class="text-blue-600 hover:text-blue-900 font-mono">
                                    <?php echo e($usage->order->order_number); ?>

                                </a>
                            <?php elseif($usage->subscription): ?>
                                <a href="<?php echo e(route('admin.subscriptions.show', $usage->subscription)); ?>" class="text-purple-600 hover:text-purple-900 font-mono">
                                    <?php echo e($usage->subscription->subscription_number); ?>

                                </a>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                            -<?php echo e(number_format($usage->discount_amount, 0, ',', ' ')); ?> Kč
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <p>Tento kupón ještě nebyl použit</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/coupons/stats.blade.php ENDPATH**/ ?>