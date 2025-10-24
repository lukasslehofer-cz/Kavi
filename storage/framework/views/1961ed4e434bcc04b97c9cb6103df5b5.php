<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="<?php echo e(route('dashboard.index')); ?>" class="text-blue-600 hover:text-blue-700 font-medium mb-4 inline-block">
            ← Zpět na dashboard
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Správa předplatného</h1>
        <p class="mt-2 text-gray-600">Spravujte své aktivní předplatné</p>
    </div>

    <!-- Subscription Details -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <?php if($subscription->plan): ?>
                    <h2 class="text-2xl font-bold text-gray-900"><?php echo e($subscription->plan->name); ?></h2>
                    <p class="text-gray-600 mt-1"><?php echo e($subscription->plan->description); ?></p>
                <?php else: ?>
                    <h2 class="text-2xl font-bold text-gray-900">Kávové předplatné</h2>
                    <p class="text-gray-600 mt-1">Váš vlastní konfigurace kávového předplatného</p>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-blue-600">
                    <?php echo e(number_format($subscription->configured_price ?? $subscription->plan->price, 0, ',', ' ')); ?> Kč
                </div>
                <div class="text-sm text-gray-500">
                    <?php if($subscription->frequency_months): ?>
                        / <?php echo e($subscription->frequency_months == 1 ? 'měsíc' : ($subscription->frequency_months . ' měsíce')); ?>

                    <?php else: ?>
                        / <?php echo e($subscription->plan->billing_period === 'monthly' ? 'měsíc' : 'rok'); ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="border-t pt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Stav</h3>
                    <p class="mt-1">
                        <?php if($subscription->status === 'active'): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Aktivní
                            </span>
                        <?php elseif($subscription->status === 'trialing'): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                Zkušební období
                            </span>
                        <?php elseif($subscription->status === 'past_due'): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                Čeká na platbu
                            </span>
                        <?php elseif($subscription->status === 'canceled'): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                Zrušeno
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                                <?php echo e(ucfirst($subscription->status)); ?>

                            </span>
                        <?php endif; ?>
                    </p>
                </div>

                <?php if($subscription->starts_at || $subscription->current_period_start): ?>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Začátek období</h3>
                    <p class="mt-1 text-gray-900">
                        <?php echo e(($subscription->starts_at ?? \Carbon\Carbon::parse($subscription->current_period_start))->format('d.m.Y')); ?>

                    </p>
                </div>
                <?php endif; ?>

                <?php if($subscription->next_billing_date || $subscription->current_period_end): ?>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Další dodávka</h3>
                    <p class="mt-1 text-gray-900">
                        <?php echo e(($subscription->next_billing_date ?? \Carbon\Carbon::parse($subscription->current_period_end))->format('d.m.Y')); ?>

                    </p>
                </div>
                <?php endif; ?>

                <?php if($subscription->trial_ends_at): ?>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Konec zkušebního období</h3>
                    <p class="mt-1 text-gray-900">
                        <?php echo e(\Carbon\Carbon::parse($subscription->trial_ends_at)->format('d.m.Y')); ?>

                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Subscription Configuration -->
    <?php if($subscription->configuration): ?>
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Konfigurace předplatného</h2>
        <div class="space-y-3">
            <?php
                $config = is_string($subscription->configuration) 
                    ? json_decode($subscription->configuration, true) 
                    : $subscription->configuration;
            ?>

            <?php if(isset($config['amount'])): ?>
            <div class="flex items-center justify-between py-2 border-b">
                <span class="text-gray-600">Množství:</span>
                <span class="font-semibold text-gray-900"><?php echo e($config['amount']); ?> balení <?php if(isset($config['cups'])): ?>(<?php echo e($config['cups']); ?> šálky denně)<?php endif; ?></span>
            </div>
            <?php endif; ?>

            <?php if(isset($config['type'])): ?>
            <div class="flex items-center justify-between py-2 border-b">
                <span class="text-gray-600">Typ kávy:</span>
                <span class="font-semibold text-gray-900">
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
                <span class="text-gray-600 block mb-2">Rozložení:</span>
                <div class="space-y-1">
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
            <div class="flex items-center justify-between py-2 border-b">
                <span class="text-gray-600">Frekvence dodávky:</span>
                <span class="font-semibold text-gray-900"><?php echo e($config['frequencyText']); ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Shipping Address -->
    <?php if($subscription->shipping_address): ?>
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Dodací adresa</h2>
        <?php
            $address = is_string($subscription->shipping_address) 
                ? json_decode($subscription->shipping_address, true) 
                : $subscription->shipping_address;
        ?>
        <div class="text-gray-700 space-y-1">
            <p class="font-semibold"><?php echo e($address['name'] ?? ''); ?></p>
            <p><?php echo e($address['address'] ?? ''); ?></p>
            <p><?php echo e($address['postal_code'] ?? ''); ?> <?php echo e($address['city'] ?? ''); ?></p>
            <?php if(isset($address['phone'])): ?>
            <p class="mt-2">Tel: <?php echo e($address['phone']); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Akce</h2>
        <div class="space-y-3">
            <?php if($subscription->stripe_subscription_id): ?>
            <a href="#" class="block w-full text-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition font-semibold">
                Změnit platební metodu
            </a>
            <?php endif; ?>

            <?php if($subscription->status === 'active'): ?>
            <button type="button" class="block w-full text-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">
                Pozastavit předplatné
            </button>

            <button type="button" class="block w-full text-center px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition font-semibold" 
                    onclick="return confirm('Opravdu chcete zrušit své předplatné?')">
                Zrušit předplatné
            </button>
            <?php endif; ?>
        </div>

        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600">
                <strong>Potřebujete pomoc?</strong> Kontaktujte naši zákaznickou podporu na 
                <a href="mailto:podpora@kavi.cz" class="text-blue-600 hover:text-blue-700">podpora@kavi.cz</a>
            </p>
        </div>
    </div>

    <?php if($subscription->stripe_subscription_id): ?>
    <div class="bg-gray-50 rounded-lg p-4 mt-6">
        <p class="text-sm text-gray-600">
            <span class="font-semibold">ID předplatného:</span> <?php echo e($subscription->stripe_subscription_id); ?>

        </p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/subscription.blade.php ENDPATH**/ ?>