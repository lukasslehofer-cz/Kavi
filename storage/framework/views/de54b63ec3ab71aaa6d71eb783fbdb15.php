<?php $__env->startSection('title', 'Admin Dashboard - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Admin Dashboard</h1>
        <p class="text-coffee-600">Přehled vašeho e-shopu</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-coffee-600 text-sm font-medium">Celkem objednávek</p>
                    <p class="text-3xl font-bold text-coffee-900 mt-2"><?php echo e($stats['total_orders']); ?></p>
                </div>
                <div class="w-12 h-12 bg-coffee-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-coffee-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-coffee-600 text-sm font-medium">Čekající objednávky</p>
                    <p class="text-3xl font-bold text-coffee-900 mt-2"><?php echo e($stats['pending_orders']); ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-coffee-600 text-sm font-medium">Celkové tržby</p>
                    <p class="text-3xl font-bold text-coffee-900 mt-2"><?php echo e(number_format($stats['total_revenue'], 0, ',', ' ')); ?> Kč</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-coffee-600 text-sm font-medium">Aktivní předplatné</p>
                    <p class="text-3xl font-bold text-coffee-900 mt-2"><?php echo e($stats['active_subscriptions']); ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-coffee-600 text-sm font-medium">Produkty</p>
                    <p class="text-3xl font-bold text-coffee-900 mt-2"><?php echo e($stats['total_products']); ?></p>
                </div>
                <div class="w-12 h-12 bg-coffee-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-coffee-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-coffee-600 text-sm font-medium">Zákazníci</p>
                    <p class="text-3xl font-bold text-coffee-900 mt-2"><?php echo e($stats['total_customers']); ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card p-6 mb-12">
        <h2 class="font-display text-2xl font-bold text-coffee-900 mb-6">Rychlé akce</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Přidat produkt
            </a>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline text-center">
                Spravovat produkty
            </a>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-outline text-center">
                Zobrazit objednávky
            </a>
            <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="btn btn-outline text-center">
                Spravovat předplatná
            </a>
            <a href="<?php echo e(route('admin.subscriptions.shipments')); ?>" class="btn btn-primary text-center">
                <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                </svg>
                Rozesílka předplatných
            </a>
            <a href="<?php echo e(route('admin.subscription-config.index')); ?>" class="btn btn-outline text-center">
                Nastavení konfiguratoru
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card">
        <div class="p-6 border-b border-cream-200">
            <h2 class="font-display text-2xl font-bold text-coffee-900">Poslední objednávky</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-cream-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Číslo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Celkem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-cream-200">
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm"><?php echo e($order->order_number); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-coffee-900"><?php echo e($order->user->name ?? 'Host'); ?></span>
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
                            <?php else: ?>
                            <span class="badge"><?php echo e($order->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-coffee-600">
                            <?php echo e($order->created_at->format('d.m.Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-coffee-700 hover:text-coffee-900 underline">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-coffee-600">
                            Žádné objednávky
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Subscriptions -->
    <div class="card mt-12">
        <div class="p-6 border-b border-cream-200">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-2xl font-bold text-coffee-900">Poslední předplatná</h2>
                <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="text-coffee-700 hover:text-coffee-900 font-semibold">
                    Zobrazit všechny →
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-cream-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Plán</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-cream-200">
                    <?php $__empty_1 = true; $__currentLoopData = $recentSubscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm">#<?php echo e($subscription->id); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-coffee-900"><?php echo e($subscription->user->name ?? 'Host'); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-coffee-900">
                                <?php echo e($subscription->plan ? $subscription->plan->name : 'Vlastní'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-coffee-900">
                                <?php echo e(number_format($subscription->configured_price ?? $subscription->plan->price ?? 0, 0, ',', ' ')); ?> Kč
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($subscription->status === 'active'): ?>
                            <span class="badge badge-success">Aktivní</span>
                            <?php elseif($subscription->status === 'pending'): ?>
                            <span class="badge badge-warning">Čeká</span>
                            <?php elseif($subscription->status === 'canceled'): ?>
                            <span class="badge" style="background-color: #fee2e2; color: #991b1b;">Zrušeno</span>
                            <?php else: ?>
                            <span class="badge"><?php echo e($subscription->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-coffee-600">
                            <?php echo e($subscription->created_at->format('d.m.Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.subscriptions.show', $subscription)); ?>" class="text-coffee-700 hover:text-coffee-900 underline">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-coffee-600">
                            Žádná předplatná
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>