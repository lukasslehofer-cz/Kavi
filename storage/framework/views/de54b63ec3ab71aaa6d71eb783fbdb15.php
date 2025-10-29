<?php $__env->startSection('title', 'Dashboard - Admin Panel'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-1">Vítejte zpět, přehled vašeho e-shopu</p>
    </div>

    <!-- Stats Grid with Modern Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Orders -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm hover:shadow-lg transition-shadow">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-white/80 text-sm font-medium mb-1">Celkem objednávek</p>
                <p class="text-4xl font-bold"><?php echo e($stats['total_orders']); ?></p>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        </div>

        <!-- Pending Orders -->
        <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-sm hover:shadow-lg transition-shadow">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-white/80 text-sm font-medium mb-1">Čekající objednávky</p>
                <p class="text-4xl font-bold"><?php echo e($stats['pending_orders']); ?></p>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        </div>

        <!-- Total Revenue -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-sm hover:shadow-lg transition-shadow">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-white/80 text-sm font-medium mb-1">Celkové tržby</p>
                <p class="text-4xl font-bold"><?php echo e(number_format($stats['total_revenue'], 0, ',', ' ')); ?><span class="text-2xl"> Kč</span></p>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        </div>

        <!-- Active Subscriptions -->
        <div class="relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-sm hover:shadow-lg transition-shadow">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                </div>
                <p class="text-white/80 text-sm font-medium mb-1">Aktivní předplatné</p>
                <p class="text-4xl font-bold"><?php echo e($stats['active_subscriptions']); ?></p>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        </div>

        <!-- Products -->
        <div class="relative overflow-hidden bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl shadow-sm hover:shadow-lg transition-shadow">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <p class="text-white/80 text-sm font-medium mb-1">Produkty</p>
                <p class="text-4xl font-bold"><?php echo e($stats['total_products']); ?></p>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        </div>

        <!-- Customers -->
        <div class="relative overflow-hidden bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl shadow-sm hover:shadow-lg transition-shadow">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-white/80 text-sm font-medium mb-1">Zákazníci</p>
                <p class="text-4xl font-bold"><?php echo e($stats['total_customers']); ?></p>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <!-- Add Product -->
        <a href="<?php echo e(route('admin.products.create')); ?>" class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-gray-200 hover:border-gray-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-900 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Přidat produkt</p>
                    <p class="text-sm text-gray-500">Nový produkt do obchodu</p>
                </div>
            </div>
        </a>

        <!-- Shipments -->
        <a href="<?php echo e(route('admin.subscriptions.shipments')); ?>" class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-gray-200 hover:border-gray-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Rozesílka</p>
                    <p class="text-sm text-gray-500">Odeslat předplatná</p>
                </div>
            </div>
        </a>

        <!-- Configurator Settings -->
        <a href="<?php echo e(route('admin.subscription-config.index')); ?>" class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-gray-200 hover:border-gray-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Konfigurátor</p>
                    <p class="text-sm text-gray-500">Nastavení předplatného</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Poslední objednávky</h2>
                <p class="text-sm text-gray-500 mt-1">Přehled posledních objednávek</p>
            </div>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                Zobrazit vše →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Číslo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celkem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-medium text-gray-900"><?php echo e($order->order_number); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900"><?php echo e($order->user->name ?? 'Host'); ?></span>
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
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><?php echo e($order->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <?php echo e($order->created_at->format('d.m.Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="text-blue-600 hover:text-blue-800 font-medium">Detail →</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Žádné objednávky
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Subscriptions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-8">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Poslední předplatná</h2>
                <p class="text-sm text-gray-500 mt-1">Přehled posledních předplatných</p>
            </div>
            <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                Zobrazit vše →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plán</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $recentSubscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-medium text-gray-900">#<?php echo e($subscription->id); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900"><?php echo e($subscription->user->name ?? 'Host'); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">
                                <?php echo e($subscription->plan ? $subscription->plan->name : 'Vlastní'); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">
                                <?php echo e(number_format($subscription->configured_price ?? $subscription->plan->price ?? 0, 0, ',', ' ')); ?> Kč
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($subscription->status === 'active'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktivní</span>
                            <?php elseif($subscription->status === 'pending'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Čeká</span>
                            <?php elseif($subscription->status === 'canceled'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Zrušeno</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><?php echo e($subscription->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <?php echo e($subscription->created_at->format('d.m.Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.subscriptions.show', $subscription)); ?>" class="text-blue-600 hover:text-blue-800 font-medium">Detail →</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
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



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>