<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="<?php echo e(route('dashboard.index')); ?>" class="text-blue-600 hover:text-blue-700 font-medium mb-4 inline-block">
            ← Zpět na dashboard
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Správa předplatných</h1>
        <p class="mt-2 text-gray-600">Přehled všech vašich aktivních předplatných (<?php echo e($subscriptions->count()); ?>)</p>
    </div>

    <?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-lg shadow-md mb-8 overflow-hidden" id="subscription-<?php echo e($subscription->id); ?>">
        <!-- Subscription Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
            <div class="flex justify-between items-start">
                <div>
                    <?php if($subscription->plan): ?>
                        <h2 class="text-2xl font-bold"><?php echo e($subscription->plan->name); ?></h2>
                        <p class="text-blue-100 mt-1"><?php echo e($subscription->plan->description); ?></p>
                    <?php else: ?>
                        <h2 class="text-2xl font-bold">Kávové předplatné #<?php echo e($subscription->id); ?></h2>
                        <p class="text-blue-100 mt-1">Váš vlastní konfigurace</p>
                    <?php endif; ?>
                    
                    <div class="mt-3">
                        <?php if($subscription->status === 'active'): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-500 text-white">
                                <span class="w-2 h-2 bg-white rounded-full mr-2"></span>
                                Aktivní
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-500 text-white">
                                <?php echo e(ucfirst($subscription->status)); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold">
                        <?php echo e(number_format($subscription->configured_price ?? $subscription->plan->price, 0, ',', ' ')); ?> Kč
                    </div>
                    <div class="text-sm text-blue-100">
                        <?php if($subscription->frequency_months): ?>
                            / <?php echo e($subscription->frequency_months == 1 ? 'měsíc' : ($subscription->frequency_months . ' měsíce')); ?>

                        <?php else: ?>
                            / <?php echo e($subscription->plan->billing_period === 'monthly' ? 'měsíc' : 'rok'); ?>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Body -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Dates -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Důležité datumy</h3>
                        <div class="space-y-2 text-sm">
                            <?php if($subscription->starts_at || $subscription->current_period_start): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Začátek období:</span>
                                <span class="font-medium"><?php echo e(($subscription->starts_at ?? \Carbon\Carbon::parse($subscription->current_period_start))->format('d.m.Y')); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($subscription->next_billing_date || $subscription->current_period_end): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Další dodávka:</span>
                                <span class="font-medium text-blue-600"><?php echo e(($subscription->next_billing_date ?? \Carbon\Carbon::parse($subscription->current_period_end))->format('d.m.Y')); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Configuration -->
                    <?php if($subscription->configuration): ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Konfigurace</h3>
                        <div class="space-y-2 text-sm">
                            <?php
                                $config = is_string($subscription->configuration) 
                                    ? json_decode($subscription->configuration, true) 
                                    : $subscription->configuration;
                            ?>

                            <?php if(isset($config['amount'])): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Množství:</span>
                                <span class="font-medium"><?php echo e($config['amount']); ?> balení</span>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($config['type'])): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Typ kávy:</span>
                                <span class="font-medium">
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
                            <div class="pt-2 border-t">
                                <span class="text-gray-600 block mb-2">Rozložení:</span>
                                <div class="space-y-1 ml-2">
                                    <?php if(isset($config['mix']['espresso']) && $config['mix']['espresso'] > 0): ?>
                                    <div class="flex items-center text-gray-700">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span><?php echo e($config['mix']['espresso']); ?>× Espresso</span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(isset($config['mix']['espressoDecaf']) && $config['mix']['espressoDecaf'] > 0): ?>
                                    <div class="flex items-center text-gray-700">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span><?php echo e($config['mix']['espressoDecaf']); ?>× Espresso Decaf</span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(isset($config['mix']['filter']) && $config['mix']['filter'] > 0): ?>
                                    <div class="flex items-center text-gray-700">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span><?php echo e($config['mix']['filter']); ?>× Filter</span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if(isset($config['mix']['filterDecaf']) && $config['mix']['filterDecaf'] > 0): ?>
                                    <div class="flex items-center text-gray-700">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span><?php echo e($config['mix']['filterDecaf']); ?>× Filter Decaf</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if(isset($config['frequencyText'])): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Frekvence:</span>
                                <span class="font-medium"><?php echo e($config['frequencyText']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Shipping Address -->
                    <?php if($subscription->shipping_address): ?>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Dodací adresa</h3>
                        <?php
                            $address = is_string($subscription->shipping_address) 
                                ? json_decode($subscription->shipping_address, true) 
                                : $subscription->shipping_address;
                        ?>
                        <div class="text-sm text-gray-700 space-y-1">
                            <p class="font-medium"><?php echo e($address['name'] ?? ''); ?></p>
                            <?php if(isset($address['billing_address'])): ?>
                            <p><?php echo e($address['billing_address'] ?? ''); ?></p>
                            <p><?php echo e($address['billing_postal_code'] ?? ''); ?> <?php echo e($address['billing_city'] ?? ''); ?></p>
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
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Packeta Pickup Point -->
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-semibold text-gray-900">Výdejní místo Zásilkovna</h3>
                            <button type="button" 
                                    class="change-packeta-point text-sm text-blue-600 hover:text-blue-700 underline"
                                    data-subscription-id="<?php echo e($subscription->id); ?>"
                                    data-current-id="<?php echo e($subscription->packeta_point_id); ?>"
                                    data-current-name="<?php echo e($subscription->packeta_point_name); ?>"
                                    data-current-address="<?php echo e($subscription->packeta_point_address); ?>">
                                Změnit
                            </button>
                        </div>
                        
                        <?php if($subscription->packeta_point_id): ?>
                        <div class="flex items-start text-sm">
                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-gray-700">
                                <p class="font-medium text-gray-900 point-name-<?php echo e($subscription->id); ?>"><?php echo e($subscription->packeta_point_name); ?></p>
                                <p class="text-gray-600 mt-1 point-address-<?php echo e($subscription->id); ?>"><?php echo e($subscription->packeta_point_address); ?></p>
                                <p class="text-xs text-gray-500 mt-1">ID: <?php echo e($subscription->packeta_point_id); ?></p>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="text-sm text-gray-600">
                            <p>Výdejní místo není nastaveno</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Actions -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Akce</h3>
                        <div class="space-y-2">
                            <?php if($subscription->status === 'active'): ?>
                            <button type="button" class="w-full text-center px-4 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                Pozastavit předplatné
                            </button>

                            <button type="button" 
                                    class="w-full text-center px-4 py-2 text-sm border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition font-medium" 
                                    onclick="return confirm('Opravdu chcete zrušit toto předplatné?')">
                                Zrušit předplatné
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Add New Subscription -->
    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 text-center">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Chcete přidat další předplatné?</h3>
        <p class="text-gray-600 mb-4">Nakonfigurujte si další předplatné podle svých potřeb</p>
        <a href="<?php echo e(route('subscriptions.index')); ?>" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Přidat nové předplatné
        </a>
    </div>

    <!-- Help Section -->
    <div class="mt-8 p-4 bg-gray-50 rounded-lg text-center">
        <p class="text-sm text-gray-600">
            <strong>Potřebujete pomoc?</strong> Kontaktujte naši zákaznickou podporu na 
            <a href="mailto:podpora@kavi.cz" class="text-blue-600 hover:text-blue-700">podpora@kavi.cz</a>
        </p>
    </div>
</div>

<script src="https://widget.packeta.com/v6/www/js/library.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const packetaApiKey = '<?php echo e(config("services.packeta.widget_key")); ?>';
    
    function openPacketaWidget(subscriptionId) {
        if (!packetaApiKey) {
            alert('Packeta widget není správně nakonfigurován. Kontaktujte administrátora.');
            return;
        }

        Packeta.Widget.pick(packetaApiKey, function(point) {
            if (point) {
                // Format address
                let address = point.street;
                if (point.city) {
                    address += ', ' + (point.zip ? point.zip + ' ' : '') + point.city;
                }

                // Send update to server
                fetch('<?php echo e(route("dashboard.subscription.update-packeta")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        subscription_id: subscriptionId,
                        packeta_point_id: point.id,
                        packeta_point_name: point.name,
                        packeta_point_address: address
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the UI
                        const nameEl = document.querySelector('.point-name-' + subscriptionId);
                        const addressEl = document.querySelector('.point-address-' + subscriptionId);
                        
                        if (nameEl) nameEl.textContent = point.name;
                        if (addressEl) addressEl.textContent = address;
                        
                        alert('Výdejní místo bylo úspěšně změněno!');
                    } else {
                        alert('Nepodařilo se změnit výdejní místo. Zkuste to prosím znovu.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Došlo k chybě při ukládání výdejního místa.');
                });
            }
        }, {
            country: 'cz',
            language: 'cs',
        });
    }

    // Event listeners for all change buttons
    document.querySelectorAll('.change-packeta-point').forEach(button => {
        button.addEventListener('click', function() {
            const subscriptionId = this.dataset.subscriptionId;
            openPacketaWidget(subscriptionId);
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/subscription.blade.php ENDPATH**/ ?>