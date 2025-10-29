<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b-2 border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Měsíc</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Datum platby</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Datum rozesílky</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Promo obrázek</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Poznámka</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $isPast = $schedule->isPast();
                $rowClass = $isPast ? 'bg-gray-50' : 'bg-white hover:bg-gray-50';
            ?>
            <tr class="<?php echo e($rowClass); ?>">
                <td class="px-4 py-3 font-medium text-gray-900">
                    <?php echo e($schedule->month_name); ?>

                    <?php if($isPast): ?>
                    <span class="ml-2 text-xs text-gray-500">(minulost)</span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <?php if($isPast): ?>
                        <span class="text-gray-600"><?php echo e($schedule->billing_date->format('d.m.Y')); ?></span>
                    <?php else: ?>
                        <input 
                            type="date" 
                            name="schedules[<?php echo e($loop->index); ?>][billing_date]" 
                            value="<?php echo e($schedule->billing_date->format('Y-m-d')); ?>"
                            class="px-3 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            required
                        >
                        <input type="hidden" name="schedules[<?php echo e($loop->index); ?>][id]" value="<?php echo e($schedule->id); ?>">
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <?php if($isPast): ?>
                        <span class="text-gray-600"><?php echo e($schedule->shipment_date->format('d.m.Y')); ?></span>
                    <?php else: ?>
                        <input 
                            type="date" 
                            name="schedules[<?php echo e($loop->index); ?>][shipment_date]" 
                            value="<?php echo e($schedule->shipment_date->format('Y-m-d')); ?>"
                            class="px-3 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            required
                        >
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <?php if($isPast): ?>
                        <?php if($schedule->promo_image): ?>
                            <div class="flex items-center gap-2">
                                <img src="<?php echo e(asset('storage/' . $schedule->promo_image)); ?>" alt="Promo" class="h-12 w-12 object-cover rounded border border-gray-300">
                                <div class="text-xs text-gray-600">
                                    <?php echo e(basename($schedule->promo_image)); ?>

                                </div>
                            </div>
                        <?php else: ?>
                            <span class="text-gray-600">—</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="space-y-2">
                            <?php if($schedule->promo_image): ?>
                                <div class="flex items-center gap-2 bg-gray-50 p-2 rounded border border-gray-200">
                                    <img src="<?php echo e(asset('storage/' . $schedule->promo_image)); ?>" alt="Promo" class="h-16 w-16 object-cover rounded border border-gray-300">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-medium text-gray-900 truncate">
                                            <?php echo e(basename($schedule->promo_image)); ?>

                                        </div>
                                        <div class="flex gap-2 mt-1">
                                            <a href="<?php echo e(asset('storage/' . $schedule->promo_image)); ?>" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                                Zobrazit
                                            </a>
                                            <button 
                                                type="button" 
                                                onclick="deletePromoImage(<?php echo e($schedule->id); ?>, '<?php echo e($schedule->month_name); ?>')"
                                                class="text-xs text-red-600 hover:text-red-800"
                                            >
                                                Smazat
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 mb-1">Nebo nahrajte nový:</div>
                            <?php endif; ?>
                            <input 
                                type="file" 
                                name="promo_images[<?php echo e($schedule->id); ?>]" 
                                accept="image/*"
                                class="text-xs w-full border border-gray-300 rounded px-2 py-1.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <?php if($isPast): ?>
                        <span class="text-gray-600"><?php echo e($schedule->notes ?? '—'); ?></span>
                    <?php else: ?>
                        <input 
                            type="text" 
                            name="schedules[<?php echo e($loop->index); ?>][notes]" 
                            value="<?php echo e($schedule->notes); ?>"
                            placeholder="Poznámka"
                            class="px-3 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm w-full"
                        >
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<?php if($schedules->isEmpty()): ?>
<div class="text-center py-12 text-gray-500">
    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <p class="text-lg font-medium">Pro tento rok zatím není vytvořen harmonogram</p>
    <p class="mt-1 text-sm">Použijte tlačítko "Vytvořit rok <?php echo e($year); ?>" pro vytvoření harmonogramu</p>
</div>
<?php endif; ?>



<?php /**PATH /var/www/html/resources/views/admin/subscription-config/_schedule-table.blade.php ENDPATH**/ ?>