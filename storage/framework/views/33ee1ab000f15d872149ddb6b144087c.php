<?php $__env->startSection('title', 'Rozesílka předplatných - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Rozesílka <?php echo e($targetDate->format('d.m.Y')); ?></h1>
                <p class="text-coffee-600">Seznam předplatných k rozeslání</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('admin.subscriptions.index')); ?>" class="btn btn-outline">
                    Všechna předplatná
                </a>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline">
                    ← Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if(session('success')): ?>
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <div class="flex">
            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-green-800"><?php echo e(session('success')); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('warning')): ?>
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
        <div class="flex">
            <svg class="w-5 h-5 text-yellow-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <p class="text-yellow-800 font-medium"><?php echo e(session('warning')); ?></p>
                <?php if(session('errors')): ?>
                <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                    <?php $__currentLoopData = session('errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <div class="flex">
            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-800"><?php echo e(session('error')); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card p-6 text-center border-l-4 border-primary-500">
            <p class="text-3xl font-bold text-primary-700"><?php echo e($stats['total']); ?></p>
            <p class="text-sm text-coffee-600 mt-1">Celkem k rozeslání</p>
        </div>
        <div class="card p-6 text-center">
            <p class="text-2xl font-bold text-coffee-900"><?php echo e($stats['monthly']); ?></p>
            <p class="text-sm text-coffee-600 mt-1">Měsíční</p>
        </div>
        <div class="card p-6 text-center">
            <p class="text-2xl font-bold text-coffee-900"><?php echo e($stats['bimonthly']); ?></p>
            <p class="text-sm text-coffee-600 mt-1">Jednou za 2 měsíce</p>
        </div>
        <div class="card p-6 text-center">
            <p class="text-2xl font-bold text-coffee-900"><?php echo e($stats['quarterly']); ?></p>
            <p class="text-sm text-coffee-600 mt-1">Jednou za 3 měsíce</p>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="card p-6 mb-6">
        <form action="<?php echo e(route('admin.subscriptions.shipments')); ?>" method="GET" class="flex items-end gap-4">
            <div class="flex-1 max-w-xs">
                <label class="block text-sm font-medium text-coffee-700 mb-2">Zobrazit rozesílku pro:</label>
                <input 
                    type="date" 
                    name="date" 
                    value="<?php echo e($targetDate->format('Y-m-d')); ?>" 
                    class="input"
                >
            </div>
            <button type="submit" class="btn btn-primary">Zobrazit</button>
        </form>
        <p class="text-xs text-coffee-600 mt-3">
            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            Rozesílka probíhá vždy <strong>20. den v měsíci</strong>. Zobrazují se jen předplatná, která mají být odeslána v daném termínu podle frekvence.
        </p>
    </div>

    <!-- Subscriptions Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <form id="shipments-form" action="<?php echo e(route('admin.subscriptions.send-to-packeta')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="target_date" value="<?php echo e($targetDate->format('Y-m-d')); ?>">
                <table class="w-full">
                    <thead class="bg-cream-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Zákazník</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Konfigurace</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Frekvence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Výdejní místo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Stav</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Poslední dodávka</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Akce</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-cream-200">
                        <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-cream-50 <?php echo e($subscription->packeta_shipment_status === 'sent' ? 'bg-green-50' : ''); ?>">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($subscription->packeta_shipment_status !== 'sent'): ?>
                                <input type="checkbox" name="subscription_ids[]" value="<?php echo e($subscription->id); ?>" class="shipment-checkbox w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-medium">#<?php echo e($subscription->id); ?></span>
                            </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <?php if($subscription->user): ?>
                                <div class="font-medium text-coffee-900"><?php echo e($subscription->user->name); ?></div>
                                <div class="text-coffee-500 text-xs"><?php echo e($subscription->user->email); ?></div>
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
                                <?php if($subscription->configuration): ?>
                                    <?php
                                        $config = is_string($subscription->configuration) 
                                            ? json_decode($subscription->configuration, true) 
                                            : $subscription->configuration;
                                    ?>
                                    <div class="font-medium text-coffee-900"><?php echo e($config['amount'] ?? ''); ?>× balení</div>
                                    <div class="text-xs text-coffee-600">
                                        <?php if($config['type'] === 'espresso'): ?>
                                            Espresso
                                        <?php elseif($config['type'] === 'filter'): ?>
                                            Filter
                                        <?php else: ?>
                                            Mix
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <?php if($subscription->frequency_months == 1): ?>
                            <span class="badge badge-success">Měsíční</span>
                            <?php elseif($subscription->frequency_months == 2): ?>
                            <span class="badge" style="background-color: #dbeafe; color: #1e40af;">2 měsíce</span>
                            <?php elseif($subscription->frequency_months == 3): ?>
                            <span class="badge" style="background-color: #e0e7ff; color: #4338ca;">3 měsíce</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($subscription->packeta_point_name): ?>
                            <div class="text-sm">
                                <div class="font-medium text-coffee-900"><?php echo e($subscription->packeta_point_name); ?></div>
                                <div class="text-xs text-coffee-600">ID: <?php echo e($subscription->packeta_point_id); ?></div>
                            </div>
                            <?php else: ?>
                            <span class="text-sm text-coffee-500">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($subscription->packeta_shipment_status === 'sent'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Podáno
                            </span>
                            <?php if($subscription->packeta_packet_id): ?>
                            <div class="text-xs text-coffee-600 mt-1">ID: <?php echo e($subscription->packeta_packet_id); ?></div>
                            <?php endif; ?>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Čeká
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-coffee-600">
                            <?php if($subscription->last_shipment_date): ?>
                            <?php echo e($subscription->last_shipment_date->format('d.m.Y')); ?>

                            <?php else: ?>
                            <span class="text-coffee-500 italic">První dodávka</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('admin.subscriptions.show', $subscription)); ?>" class="text-coffee-700 hover:text-coffee-900 font-medium underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-coffee-600">
                                Pro toto datum není naplánována žádná rozesílka
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <?php if($subscriptions->isNotEmpty()): ?>
    <!-- Export/Print Options -->
    <div class="mt-6 card p-6">
        <h3 class="font-semibold text-coffee-900 mb-3">Akce</h3>
        <div class="flex gap-3 flex-wrap">
            <button type="button" id="send-to-packeta-btn" class="btn btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Odeslat vybrané do Packety
            </button>
            <button onclick="window.print()" class="btn btn-outline">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Tisknout seznam
            </button>
            <button class="btn btn-outline" onclick="alert('Export do CSV bude implementován')">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportovat CSV
            </button>
        </div>
        <p class="text-sm text-coffee-600 mt-3">
            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            Zaškrtněte zásilky, které chcete odeslat do systému Packeta. Po odeslání budou označeny jako "Podáno".
        </p>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const shipmentCheckboxes = document.querySelectorAll('.shipment-checkbox');
    const sendButton = document.getElementById('send-to-packeta-btn');
    const shipmentsForm = document.getElementById('shipments-form');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            shipmentCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    }

    // Send to Packeta button
    if (sendButton) {
        sendButton.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.shipment-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                alert('Prosím vyberte alespoň jednu zásilku k odeslání.');
                return;
            }

            const count = checkedBoxes.length;
            const message = `Opravdu chcete odeslat ${count} ${count === 1 ? 'zásilku' : (count < 5 ? 'zásilky' : 'zásilek')} do systému Packeta?`;
            
            if (confirm(message)) {
                // Show loading state
                sendButton.disabled = true;
                sendButton.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Odesílání...';
                
                shipmentsForm.submit();
            }
        });
    }
});
</script>

<style>
@media print {
    .btn, nav, header, footer {
        display: none !important;
    }
}
</style>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/subscriptions/shipments.blade.php ENDPATH**/ ?>