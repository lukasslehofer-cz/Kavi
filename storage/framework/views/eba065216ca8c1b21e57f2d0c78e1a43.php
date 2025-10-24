<?php $__env->startSection('title', 'Správa objednávek - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Správa objednávek</h1>
                <p class="text-coffee-600">Přehled a správa všech objednávek</p>
            </div>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline">
                ← Zpět na dashboard
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-coffee-900"><?php echo e($stats['total']); ?></p>
            <p class="text-sm text-coffee-600">Celkem</p>
        </div>
        <div class="card p-4 text-center border-l-4 border-yellow-500">
            <p class="text-2xl font-bold text-yellow-700"><?php echo e($stats['pending']); ?></p>
            <p class="text-sm text-coffee-600">Čeká</p>
        </div>
        <div class="card p-4 text-center border-l-4 border-blue-500">
            <p class="text-2xl font-bold text-blue-700"><?php echo e($stats['processing']); ?></p>
            <p class="text-sm text-coffee-600">Zpracovává se</p>
        </div>
        <div class="card p-4 text-center border-l-4 border-purple-500">
            <p class="text-2xl font-bold text-purple-700"><?php echo e($stats['shipped']); ?></p>
            <p class="text-sm text-coffee-600">Odesláno</p>
        </div>
        <div class="card p-4 text-center border-l-4 border-green-500">
            <p class="text-2xl font-bold text-green-700"><?php echo e($stats['delivered']); ?></p>
            <p class="text-sm text-coffee-600">Doručeno</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6 mb-6">
        <form action="<?php echo e(route('admin.orders.index')); ?>" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-coffee-700 mb-2">Hledat</label>
                <input 
                    type="text" 
                    name="search" 
                    value="<?php echo e(request('search')); ?>" 
                    placeholder="Číslo objednávky nebo jméno zákazníka"
                    class="input"
                >
            </div>
            
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-coffee-700 mb-2">Stav</label>
                <select name="status" class="input">
                    <option value="all" <?php echo e(request('status') === 'all' ? 'selected' : ''); ?>>Všechny</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Čeká</option>
                    <option value="processing" <?php echo e(request('status') === 'processing' ? 'selected' : ''); ?>>Zpracovává se</option>
                    <option value="shipped" <?php echo e(request('status') === 'shipped' ? 'selected' : ''); ?>>Odesláno</option>
                    <option value="delivered" <?php echo e(request('status') === 'delivered' ? 'selected' : ''); ?>>Doručeno</option>
                    <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Zrušeno</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="btn btn-primary">Filtrovat</button>
                <?php if(request('search') || request('status')): ?>
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-outline ml-2">Zrušit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-cream-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Číslo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Celkem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Platba</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-cream-200">
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-cream-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-medium"><?php echo e($order->order_number); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-medium text-coffee-900"><?php echo e($order->user->name ?? 'Host'); ?></div>
                                <div class="text-coffee-500"><?php echo e($order->user->email ?? $order->email); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-coffee-900"><?php echo e(number_format($order->total, 0, ',', ' ')); ?> Kč</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($order->status === 'pending'): ?>
                            <span class="badge badge-warning">Čeká</span>
                            <?php elseif($order->status === 'processing'): ?>
                            <span class="badge" style="background-color: #dbeafe; color: #1e40af;">Zpracovává se</span>
                            <?php elseif($order->status === 'shipped'): ?>
                            <span class="badge" style="background-color: #e0e7ff; color: #4338ca;">Odesláno</span>
                            <?php elseif($order->status === 'delivered'): ?>
                            <span class="badge badge-success">Doručeno</span>
                            <?php elseif($order->status === 'cancelled'): ?>
                            <span class="badge" style="background-color: #fee2e2; color: #991b1b;">Zrušeno</span>
                            <?php else: ?>
                            <span class="badge"><?php echo e($order->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($order->payment_status === 'paid'): ?>
                            <span class="text-green-600 text-sm font-medium">Zaplaceno</span>
                            <?php elseif($order->payment_status === 'pending'): ?>
                            <span class="text-yellow-600 text-sm font-medium">Čeká</span>
                            <?php else: ?>
                            <span class="text-gray-600 text-sm font-medium"><?php echo e($order->payment_status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-coffee-600">
                            <?php echo e($order->created_at->format('d.m.Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-coffee-700 hover:text-coffee-900 font-medium underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-coffee-600">
                            Žádné objednávky nenalezeny
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($orders->hasPages()): ?>
        <div class="p-6 border-t border-cream-200">
            <?php echo e($orders->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>