<?php $__env->startSection('title', 'Detail objednávky'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Objednávka <?php echo e($order->order_number); ?></h1>
            <p class="text-gray-600 mt-1"><?php echo e($order->created_at->format('d.m.Y H:i')); ?></p>
        </div>
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            ← Zpět na seznam
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Položky objednávky</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Produkt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Cena</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Množství</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Celkem</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <?php if($item->product_image): ?>
                                        <img src="<?php echo e(Storage::url($item->product_image)); ?>" alt="<?php echo e($item->product_name); ?>" class="w-12 h-12 object-cover rounded mr-4">
                                        <?php endif; ?>
                                        <span class="font-medium text-gray-900"><?php echo e($item->product_name); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e(number_format($item->price, 0, ',', ' ')); ?> Kč
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($item->quantity); ?>×
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    <?php echo e(number_format($item->price * $item->quantity, 0, ',', ' ')); ?> Kč
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-gray-900">
                                    Mezisoučet:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    <?php echo e(number_format($order->subtotal, 0, ',', ' ')); ?> Kč
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-gray-900">
                                    Doprava:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    <?php echo e(number_format($order->shipping, 0, ',', ' ')); ?> Kč
                                </td>
                            </tr>
                            <?php if($order->discount_amount > 0 && $order->coupon): ?>
                            <tr class="bg-green-50">
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-green-700">
                                    Sleva (<?php echo e($order->coupon_code); ?>):
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-700">
                                    -<?php echo e(number_format($order->discount_amount, 0, ',', ' ')); ?> Kč
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-gray-900">
                                    DPH (21%):
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    <?php echo e(number_format($order->tax, 2, ',', ' ')); ?> Kč
                                </td>
                            </tr>
                            <tr class="border-t-2 border-gray-900">
                                <td colspan="3" class="px-6 py-4 text-right text-lg font-bold text-gray-900">
                                    Celkem (včetně DPH):
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-900">
                                    <?php echo e(number_format($order->total, 0, ',', ' ')); ?> Kč
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Doručovací adresa</h3>
                <div class="text-gray-700 space-y-4">
                    <div>
                        <p class="font-medium"><?php echo e($order->shipping_address['name'] ?? 'N/A'); ?></p>
                        <?php if(!empty($order->shipping_address['email'])): ?>
                        <p class="text-sm"><?php echo e($order->shipping_address['email']); ?></p>
                        <?php endif; ?>
                        <?php if(!empty($order->shipping_address['phone'])): ?>
                        <p class="text-sm">Tel: <?php echo e($order->shipping_address['phone']); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if(!empty($order->shipping_address['packeta_point_id'])): ?>
                    <!-- Packeta Pickup Point -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 p-4 rounded-lg">
                        <p class="font-medium text-sm text-blue-900 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z"/>
                            </svg>
                            Výdejní místo Packeta
                        </p>
                        <p class="font-bold text-blue-900"><?php echo e($order->shipping_address['packeta_point_name'] ?? 'N/A'); ?></p>
                        <p class="text-sm text-blue-800"><?php echo e($order->shipping_address['packeta_point_address'] ?? 'N/A'); ?></p>
                        <p class="text-xs text-blue-600 mt-1">ID: <?php echo e($order->shipping_address['packeta_point_id']); ?></p>
                    </div>
                    <?php endif; ?>

                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Fakturační adresa:</p>
                        <p><?php echo e($order->shipping_address['billing_address'] ?? 'N/A'); ?></p>
                        <p><?php echo e($order->shipping_address['billing_postal_code'] ?? ''); ?> <?php echo e($order->shipping_address['billing_city'] ?? ''); ?></p>
                        <p><?php echo e($order->shipping_address['country'] ?? 'CZ'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Delivery Notes -->
            <?php if($order->customer_notes): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Poznámka k objednávce</h3>
                <p class="text-gray-700"><?php echo e($order->customer_notes); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Správa stavu</h3>
                
                <form action="<?php echo e(route('admin.orders.update', $order)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stav objednávky</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                            <option value="pending" <?php echo e($order->status === 'pending' ? 'selected' : ''); ?>>Čeká</option>
                            <option value="processing" <?php echo e($order->status === 'processing' ? 'selected' : ''); ?>>Zpracovává se</option>
                            <option value="shipped" <?php echo e($order->status === 'shipped' ? 'selected' : ''); ?>>Odesláno</option>
                            <option value="delivered" <?php echo e($order->status === 'delivered' ? 'selected' : ''); ?>>Doručeno</option>
                            <option value="cancelled" <?php echo e($order->status === 'cancelled' ? 'selected' : ''); ?>>Zrušeno</option>
                        </select>
                    </div>
                </form>
                
                <div class="pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600 mb-2">Aktuální stav:</div>
                    <?php if($order->status === 'pending'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Čeká</span>
                    <?php elseif($order->status === 'processing'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Zpracovává se</span>
                    <?php elseif($order->status === 'shipped'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Odesláno</span>
                    <?php elseif($order->status === 'delivered'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Doručeno</span>
                    <?php elseif($order->status === 'cancelled'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Zrušeno</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Informace o zákazníkovi</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <div class="text-gray-600">Jméno:</div>
                        <div class="font-medium text-gray-900"><?php echo e($order->user->name ?? 'Host'); ?></div>
                    </div>
                    <div>
                        <div class="text-gray-600">Email:</div>
                        <div class="font-medium text-gray-900"><?php echo e($order->user->email ?? $order->email); ?></div>
                    </div>
                    <?php if($order->user): ?>
                    <div>
                        <div class="text-gray-600">Registrován:</div>
                        <div class="font-medium text-gray-900"><?php echo e($order->user->created_at->format('d.m.Y')); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Platba</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <div class="text-gray-600">Způsob platby:</div>
                        <div class="font-medium text-gray-900">
                            <?php if($order->payment_method === 'card'): ?>
                                Platební karta
                            <?php elseif($order->payment_method === 'transfer'): ?>
                                Bankovní převod
                            <?php else: ?>
                                <?php echo e($order->payment_method); ?>

                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-600">Stav platby:</div>
                        <div class="font-medium">
                            <?php if($order->payment_status === 'paid'): ?>
                            <span class="text-green-600">Zaplaceno</span>
                            <?php elseif($order->payment_status === 'pending'): ?>
                            <span class="text-yellow-600">Čeká na platbu</span>
                            <?php else: ?>
                            <span class="text-gray-600"><?php echo e($order->payment_status); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if($order->paid_at): ?>
                    <div>
                        <div class="text-gray-600">Zaplaceno:</div>
                        <div class="font-medium text-gray-900"><?php echo e($order->paid_at->format('d.m.Y H:i')); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Delete Order -->
            <?php if($order->status === 'pending'): ?>
            <form action="<?php echo e(route('admin.orders.destroy', $order)); ?>" method="POST" onsubmit="return confirm('Opravdu chcete zrušit tuto objednávku?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Zrušit objednávku
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>