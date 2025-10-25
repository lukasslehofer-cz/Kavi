<?php $__env->startSection('title', 'Detail předplatného - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Předplatné #<?php echo e($subscription->id); ?></h1>
                <p class="text-coffee-600"><?php echo e($subscription->created_at->format('d.m.Y H:i')); ?></p>
            </div>
            <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="btn btn-outline">
                ← Zpět na seznam
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Subscription Details -->
            <div class="card">
                <div class="p-6 border-b border-cream-200">
                    <h2 class="font-display text-2xl font-bold text-coffee-900">Detail předplatného</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <?php if($subscription->plan): ?>
                                <h3 class="text-xl font-bold text-coffee-900"><?php echo e($subscription->plan->name); ?></h3>
                                <p class="text-coffee-600"><?php echo e($subscription->plan->description); ?></p>
                                <?php else: ?>
                                <h3 class="text-xl font-bold text-coffee-900">Vlastní konfigurace</h3>
                                <p class="text-coffee-600">Kávové předplatné podle přání zákazníka</p>
                                <?php endif; ?>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-coffee-900">
                                    <?php echo e(number_format($subscription->configured_price ?? $subscription->plan->price ?? 0, 0, ',', ' ')); ?> Kč
                                </div>
                                <div class="text-sm text-coffee-600">
                                    / <?php echo e($subscription->frequency_months == 1 ? 'měsíc' : ($subscription->frequency_months . ' měsíce')); ?>

                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-coffee-600">Začátek:</span>
                                    <span class="font-medium text-coffee-900 ml-2">
                                        <?php echo e($subscription->starts_at ? $subscription->starts_at->format('d.m.Y') : '-'); ?>

                                    </span>
                                </div>
                                <div>
                                    <span class="text-coffee-600">Další dodávka:</span>
                                    <span class="font-medium text-coffee-900 ml-2">
                                        <?php echo e($subscription->next_billing_date ? $subscription->next_billing_date->format('d.m.Y') : '-'); ?>

                                    </span>
                                </div>
                                <?php if($subscription->trial_ends_at): ?>
                                <div>
                                    <span class="text-coffee-600">Konec zkušebního období:</span>
                                    <span class="font-medium text-coffee-900 ml-2">
                                        <?php echo e(\Carbon\Carbon::parse($subscription->trial_ends_at)->format('d.m.Y')); ?>

                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            <?php if($subscription->configuration): ?>
            <div class="card">
                <div class="p-6 border-b border-cream-200">
                    <h2 class="font-display text-xl font-bold text-coffee-900">Konfigurace předplatného</h2>
                </div>
                <div class="p-6">
                    <?php
                        $config = is_string($subscription->configuration) 
                            ? json_decode($subscription->configuration, true) 
                            : $subscription->configuration;
                    ?>

                    <div class="space-y-3">
                        <?php if(isset($config['amount'])): ?>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-coffee-600">Množství:</span>
                            <span class="font-semibold text-coffee-900"><?php echo e($config['amount']); ?> balení</span>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($config['cups'])): ?>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-coffee-600">Spotřeba:</span>
                            <span class="font-semibold text-coffee-900"><?php echo e($config['cups']); ?> šálky denně</span>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($config['type'])): ?>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-coffee-600">Typ kávy:</span>
                            <span class="font-semibold text-coffee-900">
                                <?php if($config['type'] === 'espresso'): ?>
                                    Espresso
                                <?php elseif($config['type'] === 'filter'): ?>
                                    Filter
                                <?php else: ?>
                                    Mix
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($config['mix']) && ($config['type'] === 'mix' || $config['isDecaf'])): ?>
                        <div class="py-2 border-b">
                            <span class="text-coffee-600 block mb-2">Rozložení:</span>
                            <div class="space-y-1 ml-4">
                                <?php if(isset($config['mix']['espresso']) && $config['mix']['espresso'] > 0): ?>
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    <span><?php echo e($config['mix']['espresso']); ?>× Espresso</span>
                                </div>
                                <?php endif; ?>
                                <?php if(isset($config['mix']['espressoDecaf']) && $config['mix']['espressoDecaf'] > 0): ?>
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    <span><?php echo e($config['mix']['espressoDecaf']); ?>× Espresso Decaf</span>
                                </div>
                                <?php endif; ?>
                                <?php if(isset($config['mix']['filter']) && $config['mix']['filter'] > 0): ?>
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    <span><?php echo e($config['mix']['filter']); ?>× Filter</span>
                                </div>
                                <?php endif; ?>
                                <?php if(isset($config['mix']['filterDecaf']) && $config['mix']['filterDecaf'] > 0): ?>
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    <span><?php echo e($config['mix']['filterDecaf']); ?>× Filter Decaf</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if(isset($config['frequencyText'])): ?>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-coffee-600">Frekvence:</span>
                            <span class="font-semibold text-coffee-900"><?php echo e($config['frequencyText']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Shipping & Packeta -->
            <div class="card">
                <div class="p-6 border-b border-cream-200">
                    <h2 class="font-display text-xl font-bold text-coffee-900">Dodací údaje</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Billing Address -->
                        <?php if($subscription->shipping_address): ?>
                        <div>
                            <h3 class="font-semibold text-coffee-900 mb-2">Fakturační adresa</h3>
                            <?php
                                $address = is_string($subscription->shipping_address) 
                                    ? json_decode($subscription->shipping_address, true) 
                                    : $subscription->shipping_address;
                            ?>
                            <div class="text-sm text-coffee-700 space-y-1">
                                <p class="font-medium"><?php echo e($address['name'] ?? ''); ?></p>
                                <?php if(isset($address['billing_address'])): ?>
                                <p><?php echo e($address['billing_address']); ?></p>
                                <p><?php echo e($address['billing_postal_code']); ?> <?php echo e($address['billing_city']); ?></p>
                                <?php else: ?>
                                <p><?php echo e($address['address'] ?? ''); ?></p>
                                <p><?php echo e($address['postal_code'] ?? ''); ?> <?php echo e($address['city'] ?? ''); ?></p>
                                <?php endif; ?>
                                <?php if(isset($address['phone'])): ?>
                                <p class="mt-2">Tel: <?php echo e($address['phone']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Packeta -->
                        <div>
                            <h3 class="font-semibold text-coffee-900 mb-2">Výdejní místo Zásilkovna</h3>
                            <?php if($subscription->packeta_point_id): ?>
                            <div class="text-sm text-coffee-700">
                                <p class="font-medium"><?php echo e($subscription->packeta_point_name); ?></p>
                                <p class="text-coffee-600 mt-1"><?php echo e($subscription->packeta_point_address); ?></p>
                                <p class="text-xs text-coffee-500 mt-1">ID: <?php echo e($subscription->packeta_point_id); ?></p>
                            </div>
                            <?php else: ?>
                            <p class="text-sm text-coffee-600">Není nastaveno</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($subscription->payment_method): ?>
                    <div class="mt-4 pt-4 border-t">
                        <h3 class="font-semibold text-coffee-900 mb-2">Platební metoda</h3>
                        <p class="text-sm text-coffee-700">
                            <?php if($subscription->payment_method === 'card'): ?>
                                Platební karta
                            <?php elseif($subscription->payment_method === 'transfer'): ?>
                                Bankovní převod
                            <?php else: ?>
                                <?php echo e($subscription->payment_method); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                    <?php endif; ?>

                    <?php if($subscription->delivery_notes): ?>
                    <div class="mt-4 pt-4 border-t">
                        <h3 class="font-semibold text-coffee-900 mb-2">Poznámka k dodání</h3>
                        <p class="text-sm text-coffee-700"><?php echo e($subscription->delivery_notes); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="card p-6">
                <h3 class="font-display text-xl font-bold text-coffee-900 mb-4">Správa stavu</h3>
                
                <form action="<?php echo e(route('admin.subscriptions.update', $subscription)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-coffee-700 mb-2">Stav předplatného</label>
                        <select name="status" class="input" onchange="this.form.submit()">
                            <option value="pending" <?php echo e($subscription->status === 'pending' ? 'selected' : ''); ?>>Čeká</option>
                            <option value="active" <?php echo e($subscription->status === 'active' ? 'selected' : ''); ?>>Aktivní</option>
                            <option value="trialing" <?php echo e($subscription->status === 'trialing' ? 'selected' : ''); ?>>Zkušební období</option>
                            <option value="past_due" <?php echo e($subscription->status === 'past_due' ? 'selected' : ''); ?>>Po splatnosti</option>
                            <option value="canceled" <?php echo e($subscription->status === 'canceled' ? 'selected' : ''); ?>>Zrušeno</option>
                        </select>
                    </div>
                </form>
                
                <div class="pt-4 border-t border-cream-200">
                    <div class="text-sm text-coffee-600 mb-2">Aktuální stav:</div>
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
                    <?php endif; ?>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card p-6">
                <h3 class="font-display text-xl font-bold text-coffee-900 mb-4">Informace o zákazníkovi</h3>
                <div class="space-y-3 text-sm">
                    <?php if($subscription->user): ?>
                    <div>
                        <div class="text-coffee-600">Jméno:</div>
                        <div class="font-medium text-coffee-900"><?php echo e($subscription->user->name); ?></div>
                    </div>
                    <div>
                        <div class="text-coffee-600">Email:</div>
                        <div class="font-medium text-coffee-900"><?php echo e($subscription->user->email); ?></div>
                    </div>
                    <div>
                        <div class="text-coffee-600">Registrován:</div>
                        <div class="font-medium text-coffee-900"><?php echo e($subscription->user->created_at->format('d.m.Y')); ?></div>
                    </div>
                    <div class="pt-3 border-t">
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            Zobrazit všechny objednávky zákazníka →
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="text-coffee-600 italic">
                        Předplatné vytvořeno bez registrace
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Stripe Info -->
            <?php if($subscription->stripe_subscription_id): ?>
            <div class="card p-6">
                <h3 class="font-display text-xl font-bold text-coffee-900 mb-4">Stripe informace</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <div class="text-coffee-600">Subscription ID:</div>
                        <div class="font-mono text-xs text-coffee-900 break-all"><?php echo e($subscription->stripe_subscription_id); ?></div>
                    </div>
                    <?php if($subscription->stripe_customer_id): ?>
                    <div>
                        <div class="text-coffee-600">Customer ID:</div>
                        <div class="font-mono text-xs text-coffee-900 break-all"><?php echo e($subscription->stripe_customer_id); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Cancel Subscription -->
            <?php if($subscription->status !== 'canceled'): ?>
            <form action="<?php echo e(route('admin.subscriptions.destroy', $subscription)); ?>" method="POST" onsubmit="return confirm('Opravdu chcete zrušit toto předplatné?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn w-full bg-red-600 text-white hover:bg-red-700">
                    Zrušit předplatné
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/subscriptions/show.blade.php ENDPATH**/ ?>