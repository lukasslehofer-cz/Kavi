<?php $__env->startSection('title', 'Upravit produkt'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Upravit produkt</h1>
        <p class="text-gray-600 mt-1">Upravte informace o produktu</p>
    </div>

    <form action="<?php echo e(route('admin.products.update', $product)); ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl p-8 shadow-sm border border-gray-200">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Název produktu</label>
                <input type="text" name="name" value="<?php echo e(old('name', $product->name)); ?>" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Fotka produktu</label>
                
                <?php if($product->image): ?>
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Aktuální fotka:</p>
                    <img src="<?php echo e(asset($product->image)); ?>" alt="<?php echo e($product->name); ?>" 
                         class="w-48 h-48 object-cover rounded-lg border-2 border-gray-300">
                </div>
                <?php endif; ?>
                
                <input type="file" name="image" accept="image/*" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       onchange="previewImage(event)">
                
                <div id="image-preview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Náhled nové fotky:</p>
                    <img id="preview" src="" alt="Náhled" 
                         class="w-48 h-48 object-cover rounded-lg border-2 border-primary-300">
                </div>
                
                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="text-xs text-gray-600 mt-1">Podporované formáty: JPG, PNG, GIF. Maximální velikost: 2MB</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Krátký popis</label>
                <input type="text" name="short_description" value="<?php echo e(old('short_description', $product->short_description)); ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['short_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['short_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Detailní popis</label>
                <textarea name="description" rows="6" required 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description', $product->description)); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Cena (Kč) <span class="text-sm text-gray-600">(volitelné pro kávu měsíce)</span></label>
                    <input type="number" name="price" value="<?php echo e(old('price', $product->price)); ?>" step="0.01" min="0" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="price-input">
                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Skladem (ks) <span class="text-sm text-gray-600">(volitelné pro kávu měsíce)</span></label>
                    <input type="number" name="stock" value="<?php echo e(old('stock', $product->stock)); ?>" min="0" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="stock-input">
                    <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Kategorie</label>
                    <div class="space-y-2">
                        <?php
                            $productCategories = old('categories', is_array($product->category) ? $product->category : [$product->category]);
                        ?>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="<?php echo e($key); ?>" 
                                   <?php echo e(in_array($key, $productCategories) ? 'checked' : ''); ?>

                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700"><?php echo e($label); ?></span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php $__errorArgs = ['categories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-gray-600 mt-1">Můžete vybrat více kategorií (např. káva může být espresso i filtr)</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Pražírna</label>
                <select name="roastery_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['roastery_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">Bez pražírny</option>
                    <?php $__currentLoopData = $roasteries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roastery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($roastery->id); ?>" <?php echo e(old('roastery_id', $product->roastery_id) == $roastery->id ? 'selected' : ''); ?>>
                        <?php echo e($roastery->country_flag); ?> <?php echo e($roastery->name); ?> (<?php echo e($roastery->country); ?>)
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['roastery_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="text-xs text-gray-600 mt-1">Vyberte pražírnu, od které je káva</p>
            </div>

            <div id="preparation-methods-container" style="display: none;">
                <label class="block text-sm font-medium text-gray-900 mb-2">Typ pražení (pro kávu)</label>
                <div class="space-y-2">
                    <?php
                        $currentMethods = old('preparation_methods', $product->attributes['preparation_methods'] ?? []);
                    ?>
                    <label class="flex items-center">
                        <input type="checkbox" name="preparation_methods[]" value="espresso" 
                               <?php echo e(in_array('espresso', $currentMethods) ? 'checked' : ''); ?>

                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Espresso</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="preparation_methods[]" value="filter" 
                               <?php echo e(in_array('filter', $currentMethods) ? 'checked' : ''); ?>

                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Filtr</span>
                    </label>
                </div>
                <?php $__errorArgs = ['preparation_methods'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="text-xs text-gray-600 mt-1">Můžete vybrat obě možnosti, pokud je káva vhodná pro espresso i filtr</p>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 p-6 rounded-lg">
                <div class="flex items-start gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_coffee_of_month" value="1" <?php echo e(old('is_coffee_of_month', $product->is_coffee_of_month) ? 'checked' : ''); ?>

                                   class="rounded border-blue-300 text-blue-600 focus:ring-blue-500" id="coffee-of-month-checkbox">
                            <span class="ml-2 text-sm font-bold text-gray-900">Označit jako kávu měsíce</span>
                        </label>
                        <p class="text-xs text-gray-600 mt-1 ml-6">Kávy měsíce se nezobrazují v eshopu, ale na stránce předplatného</p>
                    </div>
                </div>

                <div id="coffee-of-month-date-container" style="display: none;">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Rozesílka (Měsíc kávy)</label>
                    <select name="coffee_of_month_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['coffee_of_month_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Vyberte měsíc rozesílky</option>
                        <?php
                            $currentDate = now();
                            // Get current value - handle both date object and string format
                            $currentValue = old('coffee_of_month_date', $product->coffee_of_month_date);
                            if ($currentValue instanceof \Carbon\Carbon) {
                                $currentValue = $currentValue->format('Y-m');
                            }
                            
                            for ($i = -2; $i <= 12; $i++) {
                                $date = $currentDate->copy()->addMonths($i);
                                $value = $date->format('Y-m');
                                $label = $date->locale('cs')->isoFormat('MMMM YYYY');
                                $selected = $currentValue == $value ? 'selected' : '';
                                echo "<option value=\"{$value}\" {$selected}>" . ucfirst($label) . "</option>";
                            }
                        ?>
                    </select>
                    <?php $__errorArgs = ['coffee_of_month_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-gray-600 mt-1">Vyberte měsíc, kdy bude káva součástí rozesílky (zobrazuje se do 15. dne aktuálního měsíce, pak se přepne na následující měsíc)</p>
                </div>
            </div>

            <div class="flex items-center space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $product->is_active) ? 'checked' : ''); ?>

                           class="rounded border-gray-300 text-gray-700 focus:ring-coffee-500">
                    <span class="ml-2 text-sm text-gray-900">Aktivní produkt</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" <?php echo e(old('is_featured', $product->is_featured) ? 'checked' : ''); ?>

                           class="rounded border-gray-300 text-gray-700 focus:ring-coffee-500">
                    <span class="ml-2 text-sm text-gray-900">Zvýrazněný produkt</span>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">Uložit změny</button>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Zrušit</a>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('image-preview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const coffeeOfMonthCheckbox = document.getElementById('coffee-of-month-checkbox');
    const coffeeOfMonthDateContainer = document.getElementById('coffee-of-month-date-container');
    const priceInput = document.getElementById('price-input');
    const stockInput = document.getElementById('stock-input');

    function toggleCoffeeOfMonth() {
        if (coffeeOfMonthCheckbox.checked) {
            coffeeOfMonthDateContainer.style.display = 'block';
            priceInput.removeAttribute('required');
            stockInput.removeAttribute('required');
        } else {
            coffeeOfMonthDateContainer.style.display = 'none';
            priceInput.setAttribute('required', 'required');
            stockInput.setAttribute('required', 'required');
        }
    }

    coffeeOfMonthCheckbox.addEventListener('change', toggleCoffeeOfMonth);
    toggleCoffeeOfMonth(); // Initial state
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/products/edit.blade.php ENDPATH**/ ?>