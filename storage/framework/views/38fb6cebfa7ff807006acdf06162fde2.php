<?php $__env->startSection('title', 'Správa předplatných'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Správa předplatných</h1>
            <p class="text-gray-600 mt-1">Přehled a správa všech předplatných</p>
        </div>
        <a href="<?php echo e(route('admin.subscriptions.shipments')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
            </svg>
            Rozesílka
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-7 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total']); ?></p>
            <p class="text-sm text-gray-600 mt-1">Celkem</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 text-center shadow-sm border border-green-200">
            <p class="text-3xl font-bold text-green-700"><?php echo e($stats['active']); ?></p>
            <p class="text-sm text-green-600 mt-1">Aktivní</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 text-center shadow-sm border-2 border-red-300 <?php echo e(($stats['unpaid'] ?? 0) > 0 ? 'ring-2 ring-red-500 ring-offset-2' : ''); ?>">
            <p class="text-3xl font-bold text-red-700"><?php echo e($stats['unpaid'] ?? 0); ?></p>
            <p class="text-sm text-red-600 mt-1 font-semibold">⚠️ Neuhrazeno</p>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-4 text-center shadow-sm border border-amber-200">
            <p class="text-3xl font-bold text-amber-700"><?php echo e($stats['pending']); ?></p>
            <p class="text-sm text-amber-600 mt-1">Čeká</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 text-center shadow-sm border border-blue-200">
            <p class="text-3xl font-bold text-blue-700"><?php echo e($stats['trialing']); ?></p>
            <p class="text-sm text-blue-600 mt-1">Zkušební</p>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 text-center shadow-sm border border-orange-200">
            <p class="text-3xl font-bold text-orange-700"><?php echo e($stats['past_due']); ?></p>
            <p class="text-sm text-orange-600 mt-1">Po splatnosti</p>
        </div>
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 text-center shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-700"><?php echo e($stats['canceled']); ?></p>
            <p class="text-sm text-gray-600 mt-1">Zrušeno</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-200">
        <form action="<?php echo e(route('admin.subscriptions.index')); ?>" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hledat</label>
                <input 
                    type="text" 
                    name="search" 
                    value="<?php echo e(request('search')); ?>" 
                    placeholder="Jméno nebo email zákazníka"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Stav</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all" <?php echo e(request('status') === 'all' ? 'selected' : ''); ?>>Všechny</option>
                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Aktivní</option>
                    <option value="unpaid" <?php echo e(request('status') === 'unpaid' ? 'selected' : ''); ?>>⚠️ Neuhrazeno</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Čeká</option>
                    <option value="trialing" <?php echo e(request('status') === 'trialing' ? 'selected' : ''); ?>>Zkušební</option>
                    <option value="past_due" <?php echo e(request('status') === 'past_due' ? 'selected' : ''); ?>>Po splatnosti</option>
                    <option value="canceled" <?php echo e(request('status') === 'canceled' ? 'selected' : ''); ?>>Zrušeno</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Filtrovat
                </button>
                <?php if(request('search') || request('status')): ?>
                <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Zrušit
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plán / Konfigurace</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Další dodávka</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vytvořeno</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors <?php echo e($subscription->status === 'unpaid' ? 'bg-red-50 border-l-4 border-red-500' : ''); ?>">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-medium text-gray-900">#<?php echo e($subscription->id); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <?php if($subscription->user): ?>
                                <div class="font-medium text-gray-900"><?php echo e($subscription->user->name); ?></div>
                                <div class="text-gray-500"><?php echo e($subscription->user->email); ?></div>
                                <?php else: ?>
                                <div class="text-gray-500 italic">Host</div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <?php if($subscription->plan): ?>
                                <div class="font-medium text-gray-900"><?php echo e($subscription->plan->name); ?></div>
                                <?php else: ?>
                                <div class="font-medium text-gray-900">Vlastní konfigurace</div>
                                <?php if($subscription->configuration): ?>
                                    <?php
                                        $config = is_string($subscription->configuration) 
                                            ? json_decode($subscription->configuration, true) 
                                            : $subscription->configuration;
                                    ?>
                                    <div class="text-xs text-gray-500">
                                        <?php echo e($config['amount'] ?? ''); ?>× balení, 
                                        <?php echo e(ucfirst($config['type'] ?? '')); ?>

                                    </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">
                                <?php echo e(number_format($subscription->configured_price ?? $subscription->plan->price ?? 0, 0, ',', ' ')); ?> Kč
                            </span>
                            <div class="text-xs text-gray-500">
                                / <?php echo e($subscription->frequency_months ?? 1); ?>M
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($subscription->status === 'active'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktivní</span>
                            <?php elseif($subscription->status === 'unpaid'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                                ⚠️ Neuhrazeno
                            </span>
                            <?php if($subscription->payment_failure_count > 0): ?>
                            <div class="text-xs text-red-700 mt-1">
                                Pokusů: <?php echo e($subscription->payment_failure_count); ?>

                            </div>
                            <?php endif; ?>
                            <?php elseif($subscription->status === 'pending'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Čeká</span>
                            <?php elseif($subscription->status === 'trialing'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Zkušební</span>
                            <?php elseif($subscription->status === 'past_due'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Po splatnosti</span>
                            <?php elseif($subscription->status === 'canceled'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Zrušeno</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><?php echo e($subscription->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <?php if($subscription->next_billing_date): ?>
                            <?php echo e($subscription->next_billing_date->format('d.m.Y')); ?>

                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <?php echo e($subscription->created_at->format('d.m.Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.subscriptions.show', $subscription)); ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Žádná předplatná nenalezena
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($subscriptions->hasPages()): ?>
        <div class="p-6 border-t border-gray-200">
            <?php echo e($subscriptions->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/subscriptions/index.blade.php ENDPATH**/ ?>