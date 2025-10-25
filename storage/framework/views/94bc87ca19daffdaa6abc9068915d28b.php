<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="<?php echo e(route('dashboard.orders')); ?>" class="text-blue-600 hover:text-blue-700 font-medium mb-4 inline-block">
            ← Zpět na objednávky
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail objednávky #<?php echo e($order->id); ?></h1>
        <p class="mt-2 text-gray-600">Vytvořeno: <?php echo e($order->created_at->format('d.m.Y H:i')); ?></p>
    </div>

    <!-- Order Status -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Stav objednávky</h2>
        <div class="flex items-center">
            <?php if($order->status === 'completed'): ?>
                <span class="px-4 py-2 text-base font-semibold rounded-full bg-green-100 text-green-800">
                    ✓ Dokončeno
                </span>
            <?php elseif($order->status === 'pending'): ?>
                <span class="px-4 py-2 text-base font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    ⏱ Čeká na zpracování
                </span>
            <?php elseif($order->status === 'processing'): ?>
                <span class="px-4 py-2 text-base font-semibold rounded-full bg-blue-100 text-blue-800">
                    🔄 Zpracovává se
                </span>
            <?php elseif($order->status === 'cancelled'): ?>
                <span class="px-4 py-2 text-base font-semibold rounded-full bg-red-100 text-red-800">
                    ✕ Zrušeno
                </span>
            <?php else: ?>
                <span class="px-4 py-2 text-base font-semibold rounded-full bg-gray-100 text-gray-800">
                    <?php echo e(ucfirst($order->status)); ?>

                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Položky objednávky</h2>
        <div class="divide-y divide-gray-200">
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="py-4 flex justify-between items-center">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900"><?php echo e($item->product_name); ?></h3>
                    <p class="text-sm text-gray-600">
                        Množství: <?php echo e($item->quantity); ?> × <?php echo e(number_format($item->price, 2, ',', ' ')); ?> Kč
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-gray-900">
                        <?php echo e(number_format($item->price * $item->quantity, 2, ',', ' ')); ?> Kč
                    </p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Souhrn objednávky</h2>
        <div class="space-y-2">
            <div class="flex justify-between text-gray-600">
                <span>Mezisoučet:</span>
                <span><?php echo e(number_format($order->subtotal, 2, ',', ' ')); ?> Kč</span>
            </div>
            <?php if($order->tax > 0): ?>
            <div class="flex justify-between text-gray-600">
                <span>DPH:</span>
                <span><?php echo e(number_format($order->tax, 2, ',', ' ')); ?> Kč</span>
            </div>
            <?php endif; ?>
            <?php if($order->shipping > 0): ?>
            <div class="flex justify-between text-gray-600">
                <span>Doprava:</span>
                <span><?php echo e(number_format($order->shipping, 2, ',', ' ')); ?> Kč</span>
            </div>
            <?php endif; ?>
            <div class="border-t pt-2 mt-2">
                <div class="flex justify-between text-xl font-bold text-gray-900">
                    <span>Celkem:</span>
                    <span><?php echo e(number_format($order->total, 2, ',', ' ')); ?> Kč</span>
                </div>
            </div>
        </div>
    </div>

    <?php if($order->stripe_payment_intent_id): ?>
    <div class="bg-gray-50 rounded-lg p-4 mt-6">
        <p class="text-sm text-gray-600">
            <span class="font-semibold">ID platby:</span> <?php echo e($order->stripe_payment_intent_id); ?>

        </p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/order-detail.blade.php ENDPATH**/ ?>