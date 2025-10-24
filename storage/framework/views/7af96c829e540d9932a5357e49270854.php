<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-2 text-gray-600">Vítejte zpět, <?php echo e(auth()->user()->name); ?>!</p>
    </div>

    <!-- Active Subscription -->
    <?php if($activeSubscription): ?>
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-8 text-white">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold mb-2">Vaše aktivní předplatné</h2>
                <p class="text-blue-100 text-lg">
                    <?php echo e($activeSubscription->plan ? $activeSubscription->plan->name : 'Kávové předplatné'); ?>

                </p>
                <?php if($activeSubscription->configured_price): ?>
                <p class="text-blue-100 mt-1">
                    <span class="font-semibold">Cena:</span> <?php echo e(number_format($activeSubscription->configured_price, 0, ',', ' ')); ?> Kč 
                    / <?php echo e($activeSubscription->frequency_months == 1 ? 'měsíc' : ($activeSubscription->frequency_months . ' měsíce')); ?>

                </p>
                <?php endif; ?>
                <p class="text-blue-100 mt-2">
                    <span class="font-semibold">Stav:</span> 
                    <?php if($activeSubscription->status === 'active'): ?>
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm">Aktivní</span>
                    <?php elseif($activeSubscription->status === 'pending'): ?>
                        <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm">Čeká na aktivaci</span>
                    <?php else: ?>
                        <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm"><?php echo e(ucfirst($activeSubscription->status)); ?></span>
                    <?php endif; ?>
                </p>
                <?php if($activeSubscription->next_billing_date || $activeSubscription->current_period_end): ?>
                <p class="text-blue-100 mt-1">
                    <span class="font-semibold">Další dodávka:</span> 
                    <?php echo e(($activeSubscription->next_billing_date ?? \Carbon\Carbon::parse($activeSubscription->current_period_end))->format('d.m.Y')); ?>

                </p>
                <?php endif; ?>
            </div>
            <a href="<?php echo e(route('dashboard.subscription')); ?>" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition font-semibold">
                Spravovat předplatné
            </a>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-8">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-yellow-700 font-medium">Nemáte aktivní předplatné</p>
                <p class="text-yellow-600 text-sm mt-1">
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="underline hover:text-yellow-800">Prohlédněte si naše předplatné plány</a>
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Poslední objednávky</h2>
            <a href="<?php echo e(route('dashboard.orders')); ?>" class="text-blue-600 hover:text-blue-700 font-semibold">
                Zobrazit všechny →
            </a>
        </div>

        <?php if($orders->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Číslo objednávky
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Datum
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stav
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Celkem
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Akce
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #<?php echo e($order->id); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($order->created_at->format('d.m.Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($order->status === 'completed'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Dokončeno
                                </span>
                            <?php elseif($order->status === 'pending'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Čeká
                                </span>
                            <?php elseif($order->status === 'processing'): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Zpracovává se
                                </span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <?php echo e(ucfirst($order->status)); ?>

                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                            <?php echo e(number_format($order->total, 2, ',', ' ')); ?> Kč
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('dashboard.order.detail', $order)); ?>" class="text-blue-600 hover:text-blue-900">
                                Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <?php echo e($orders->links()); ?>

        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Žádné objednávky</h3>
            <p class="mt-1 text-sm text-gray-500">Zatím jste neprovedli žádnou objednávku.</p>
            <div class="mt-6">
                <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Prohlédnout produkty
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/index.blade.php ENDPATH**/ ?>