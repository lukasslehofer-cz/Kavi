<?php $__env->startSection('title', 'Správa předplatných - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Správa předplatných</h1>
                <p class="text-coffee-600">Přehled a správa všech předplatných</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('admin.subscriptions.shipments')); ?>" class="btn btn-primary">
                    <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                    Rozesílka
                </a>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline">
                    ← Zpět na dashboard
                </a>
            </div>
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
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-coffee-900"><?php echo e($stats['total']); ?></p>
            <p class="text-sm text-coffee-600">Celkem</p>
        </div>
        <div class="card p-4 text-center border-l-4 border-green-500">
            <p class="text-2xl font-bold text-green-700"><?php echo e($stats['active']); ?></p>
            <p class="text-sm text-coffee-600">Aktivní</p>
        </div>
        <div class="card p-4 text-center border-l-4 border-yellow-500">
            <p class="text-2xl font-bold text-yellow-700"><?php echo e($stats['pending']); ?></p>
            <p class="text-sm text-coffee-600">Čeká</p>
        </div>
        <div class="card p-4 text-center border-l-4 border-blue-500">
            <p class="text-2xl font-bold text-blue-700"><?php echo e($stats['trialing']); ?></p>
            <p class="text-sm text-coffee-600">Zkušební</p>
        </div>
        <div class="card p-4 text-center border-l-4 border-orange-500">
            <p class="text-2xl font-bold text-orange-700"><?php echo e($stats['past_due']); ?></p>
            <p class="text-sm text-coffee-600">Po splatnosti</p>
        </div>
        <div class="card p-4 text-center border-l-4 border-red-500">
            <p class="text-2xl font-bold text-red-700"><?php echo e($stats['canceled']); ?></p>
            <p class="text-sm text-coffee-600">Zrušeno</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6 mb-6">
        <form action="<?php echo e(route('admin.subscriptions.index')); ?>" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-coffee-700 mb-2">Hledat</label>
                <input 
                    type="text" 
                    name="search" 
                    value="<?php echo e(request('search')); ?>" 
                    placeholder="Jméno nebo email zákazníka"
                    class="input"
                >
            </div>
            
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-coffee-700 mb-2">Stav</label>
                <select name="status" class="input">
                    <option value="all" <?php echo e(request('status') === 'all' ? 'selected' : ''); ?>>Všechny</option>
                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Aktivní</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Čeká</option>
                    <option value="trialing" <?php echo e(request('status') === 'trialing' ? 'selected' : ''); ?>>Zkušební</option>
                    <option value="past_due" <?php echo e(request('status') === 'past_due' ? 'selected' : ''); ?>>Po splatnosti</option>
                    <option value="canceled" <?php echo e(request('status') === 'canceled' ? 'selected' : ''); ?>>Zrušeno</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="btn btn-primary">Filtrovat</button>
                <?php if(request('search') || request('status')): ?>
                <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="btn btn-outline ml-2">Zrušit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Subscriptions Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-cream-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Plán / Konfigurace</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Další dodávka</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Vytvořeno</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-cream-200">
                    <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-cream-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-medium">#<?php echo e($subscription->id); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <?php if($subscription->user): ?>
                                <div class="font-medium text-coffee-900"><?php echo e($subscription->user->name); ?></div>
                                <div class="text-coffee-500"><?php echo e($subscription->user->email); ?></div>
                                <?php else: ?>
                                <div class="text-coffee-500 italic">Host</div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <?php if($subscription->plan): ?>
                                <div class="font-medium text-coffee-900"><?php echo e($subscription->plan->name); ?></div>
                                <?php else: ?>
                                <div class="font-medium text-coffee-900">Vlastní konfigurace</div>
                                <?php if($subscription->configuration): ?>
                                    <?php
                                        $config = is_string($subscription->configuration) 
                                            ? json_decode($subscription->configuration, true) 
                                            : $subscription->configuration;
                                    ?>
                                    <div class="text-xs text-coffee-600">
                                        <?php echo e($config['amount'] ?? ''); ?>× balení, 
                                        <?php echo e(ucfirst($config['type'] ?? '')); ?>

                                    </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-coffee-900">
                                <?php echo e(number_format($subscription->configured_price ?? $subscription->plan->price ?? 0, 0, ',', ' ')); ?> Kč
                            </span>
                            <div class="text-xs text-coffee-600">
                                / <?php echo e($subscription->frequency_months ?? 1); ?>M
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($subscription->status === 'active'): ?>
                            <span class="badge badge-success">Aktivní</span>
                            <?php elseif($subscription->status === 'pending'): ?>
                            <span class="badge badge-warning">Čeká</span>
                            <?php elseif($subscription->status === 'trialing'): ?>
                            <span class="badge" style="background-color: #dbeafe; color: #1e40af;">Zkušební</span>
                            <?php elseif($subscription->status === 'past_due'): ?>
                            <span class="badge" style="background-color: #fed7aa; color: #c2410c;">Po splatnosti</span>
                            <?php elseif($subscription->status === 'canceled'): ?>
                            <span class="badge" style="background-color: #fee2e2; color: #991b1b;">Zrušeno</span>
                            <?php else: ?>
                            <span class="badge"><?php echo e($subscription->status); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-coffee-600">
                            <?php if($subscription->next_billing_date): ?>
                            <?php echo e($subscription->next_billing_date->format('d.m.Y')); ?>

                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-coffee-600">
                            <?php echo e($subscription->created_at->format('d.m.Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.subscriptions.show', $subscription)); ?>" class="text-coffee-700 hover:text-coffee-900 font-medium underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-coffee-600">
                            Žádná předplatná nenalezena
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($subscriptions->hasPages()): ?>
        <div class="p-6 border-t border-cream-200">
            <?php echo e($subscriptions->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/subscriptions/index.blade.php ENDPATH**/ ?>