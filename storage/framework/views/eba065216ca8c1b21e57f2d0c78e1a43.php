<?php $__env->startSection('title', 'Správa objednávek'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Správa objednávek</h1>
        <p class="text-gray-600 mt-1">Přehled a správa všech objednávek</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total']); ?></p>
            <p class="text-sm text-gray-600 mt-1">Celkem</p>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-4 text-center shadow-sm border border-amber-200">
            <p class="text-3xl font-bold text-amber-700"><?php echo e($stats['pending']); ?></p>
            <p class="text-sm text-amber-600 mt-1">Čeká</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 text-center shadow-sm border border-blue-200">
            <p class="text-3xl font-bold text-blue-700"><?php echo e($stats['processing']); ?></p>
            <p class="text-sm text-blue-600 mt-1">Zpracovává se</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 text-center shadow-sm border border-purple-200">
            <p class="text-3xl font-bold text-purple-700"><?php echo e($stats['shipped']); ?></p>
            <p class="text-sm text-purple-600 mt-1">Odesláno</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 text-center shadow-sm border border-green-200">
            <p class="text-3xl font-bold text-green-700"><?php echo e($stats['delivered']); ?></p>
            <p class="text-sm text-green-600 mt-1">Doručeno</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-200">
        <form action="<?php echo e(route('admin.orders.index')); ?>" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hledat</label>
                <input 
                    type="text" 
                    name="search" 
                    value="<?php echo e(request('search')); ?>" 
                    placeholder="Číslo objednávky nebo jméno zákazníka"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Stav</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all" <?php echo e(request('status') === 'all' ? 'selected' : ''); ?>>Všechny</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Čeká</option>
                    <option value="processing" <?php echo e(request('status') === 'processing' ? 'selected' : ''); ?>>Zpracovává se</option>
                    <option value="shipped" <?php echo e(request('status') === 'shipped' ? 'selected' : ''); ?>>Odesláno</option>
                    <option value="delivered" <?php echo e(request('status') === 'delivered' ? 'selected' : ''); ?>>Doručeno</option>
                    <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Zrušeno</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Filtrovat
                </button>
                <?php if(request('search') || request('status')): ?>
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Zrušit
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Číslo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celkem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platba</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-medium text-gray-900"><?php echo e($order->order_number); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900"><?php echo e($order->user->name ?? 'Host'); ?></div>
                                <div class="text-gray-500"><?php echo e($order->user->email ?? $order->email); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900"><?php echo e(number_format($order->total, 0, ',', ' ')); ?> Kč</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($order->status === 'pending'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Čeká</span>
                            <?php elseif($order->status === 'processing'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Zpracovává se</span>
                            <?php elseif($order->status === 'shipped'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Odesláno</span>
                            <?php elseif($order->status === 'delivered'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Doručeno</span>
                            <?php elseif($order->status === 'cancelled'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Zrušeno</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><?php echo e($order->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($order->payment_status === 'paid'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Zaplaceno</span>
                            <?php elseif($order->payment_status === 'pending'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Čeká</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><?php echo e($order->payment_status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <?php echo e($order->created_at->format('d.m.Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Žádné objednávky nenalezeny
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($orders->hasPages()): ?>
        <div class="p-6 border-t border-gray-200">
            <?php echo e($orders->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>