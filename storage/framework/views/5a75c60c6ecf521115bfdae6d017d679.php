<?php $__env->startSection('title', 'Pokladna - Kávové předplatné'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Header - Minimal -->
<div class="relative bg-gray-100 py-12 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 tracking-tight">Dokončení objednávky předplatného</h1>
                <p class="text-lg text-gray-600 font-light">Ještě pár informací a vaše káva bude pravidelně na cestě k vám</p>
            </div>
            <div class="hidden md:block">
                <div class="w-14 h-14 rounded-full bg-gray-900 flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form action="<?php echo e(route('subscriptions.checkout.process')); ?>" method="POST" id="subscription-checkout-form" class="lg:pt-0">
                <?php echo csrf_field(); ?>
                
                <!-- Contact Information -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Kontaktní údaje</h2>
                    </div>
                    
                    <?php if(auth()->guard()->guest()): ?>
                    <!-- Login option for guests - Minimal -->
                    <div class="mb-6 bg-blue-50 p-5 rounded-xl border border-blue-200">
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0 mr-3">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 mb-1.5">Máte již účet?</h3>
                                <p class="text-sm text-gray-600 mb-3 font-light">Přihlaste se pro rychlejší dokončení objednávky.</p>
                                <a href="<?php echo e(route('login')); ?>?redirect=<?php echo e(urlencode(route('subscriptions.checkout'))); ?>" class="inline-block bg-white hover:bg-gray-50 text-blue-600 font-medium px-5 py-2 rounded-full border border-blue-200 hover:border-blue-300 transition-all text-sm">
                                    Přihlásit se
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Jméno a příjmení <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="<?php echo e(old('name', auth()->user()->name ?? '')); ?>" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                            >
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
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="<?php echo e(old('email', auth()->user()->email ?? '')); ?>" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                            >
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php if(auth()->guard()->guest()): ?>
                            <p class="text-xs text-gray-600 mt-1">Na tento email vám zašleme potvrzení a odkaz pro dokončení registrace.</p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Telefon <?php if(auth()->guard()->guest()): ?><span class="text-gray-500">(volitelné)</span><?php else: ?><span class="text-red-500">*</span><?php endif; ?>
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                value="<?php echo e(old('phone', auth()->user()->phone ?? '')); ?>" 
                                <?php if(auth()->guard()->check()): ?> required <?php endif; ?>
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                placeholder="+420 123 456 789"
                            >
                            <?php $__errorArgs = ['phone'];
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
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Fakturační adresa</h2>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Ulice a číslo popisné <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="billing_address" 
                                name="billing_address" 
                                value="<?php echo e(old('billing_address', auth()->user()->address ?? '')); ?>" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                placeholder="Např. Karlova 123"
                            >
                            <?php $__errorArgs = ['billing_address'];
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
                                <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-2">
                                    Město <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="billing_city" 
                                    name="billing_city" 
                                    value="<?php echo e(old('billing_city', auth()->user()->city ?? '')); ?>" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    placeholder="Např. Praha"
                                >
                                <?php $__errorArgs = ['billing_city'];
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
                                <label for="billing_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    PSČ <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="billing_postal_code" 
                                    name="billing_postal_code" 
                                    value="<?php echo e(old('billing_postal_code', auth()->user()->postal_code ?? '')); ?>" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    placeholder="123 45"
                                >
                                <?php $__errorArgs = ['billing_postal_code'];
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
                                <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-2">
                                    Země <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="billing_country" 
                                    name="billing_country" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                >
                                    <option value="">Vyberte zemi</option>
                                    <option value="AT" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'AT' ? 'selected' : ''); ?>>Rakousko</option>
                                    <option value="BE" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'BE' ? 'selected' : ''); ?>>Belgie</option>
                                    <option value="BG" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'BG' ? 'selected' : ''); ?>>Bulharsko</option>
                                    <option value="HR" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'HR' ? 'selected' : ''); ?>>Chorvatsko</option>
                                    <option value="CY" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'CY' ? 'selected' : ''); ?>>Kypr</option>
                                    <option value="CZ" <?php echo e(old('billing_country', auth()->user()->country ?? 'CZ') == 'CZ' ? 'selected' : ''); ?>>Česká republika</option>
                                    <option value="DK" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'DK' ? 'selected' : ''); ?>>Dánsko</option>
                                    <option value="EE" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'EE' ? 'selected' : ''); ?>>Estonsko</option>
                                    <option value="FI" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'FI' ? 'selected' : ''); ?>>Finsko</option>
                                    <option value="FR" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'FR' ? 'selected' : ''); ?>>Francie</option>
                                    <option value="DE" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'DE' ? 'selected' : ''); ?>>Německo</option>
                                    <option value="GR" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'GR' ? 'selected' : ''); ?>>Řecko</option>
                                    <option value="HU" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'HU' ? 'selected' : ''); ?>>Maďarsko</option>
                                    <option value="IE" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'IE' ? 'selected' : ''); ?>>Irsko</option>
                                    <option value="IT" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'IT' ? 'selected' : ''); ?>>Itálie</option>
                                    <option value="LV" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'LV' ? 'selected' : ''); ?>>Lotyšsko</option>
                                    <option value="LT" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'LT' ? 'selected' : ''); ?>>Litva</option>
                                    <option value="LU" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'LU' ? 'selected' : ''); ?>>Lucembursko</option>
                                    <option value="MT" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'MT' ? 'selected' : ''); ?>>Malta</option>
                                    <option value="NL" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'NL' ? 'selected' : ''); ?>>Nizozemsko</option>
                                    <option value="PL" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'PL' ? 'selected' : ''); ?>>Polsko</option>
                                    <option value="PT" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'PT' ? 'selected' : ''); ?>>Portugalsko</option>
                                    <option value="RO" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'RO' ? 'selected' : ''); ?>>Rumunsko</option>
                                    <option value="SK" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'SK' ? 'selected' : ''); ?>>Slovensko</option>
                                    <option value="SI" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'SI' ? 'selected' : ''); ?>>Slovinsko</option>
                                    <option value="ES" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'ES' ? 'selected' : ''); ?>>Španělsko</option>
                                    <option value="SE" <?php echo e(old('billing_country', auth()->user()->country ?? '') == 'SE' ? 'selected' : ''); ?>>Švédsko</option>
                                </select>
                                <?php $__errorArgs = ['billing_country'];
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
                        </div>
                    </div>
                </div>

                <!-- Packeta Pickup Point -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Výběr výdejního místa</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Hidden fields for Packeta data -->
                        <input type="hidden" id="packeta_point_id" name="packeta_point_id" value="<?php echo e(old('packeta_point_id', auth()->user()->packeta_point_id ?? '')); ?>">
                        <input type="hidden" id="packeta_point_name" name="packeta_point_name" value="<?php echo e(old('packeta_point_name', auth()->user()->packeta_point_name ?? '')); ?>">
                        <input type="hidden" id="packeta_point_address" name="packeta_point_address" value="<?php echo e(old('packeta_point_address', auth()->user()->packeta_point_address ?? '')); ?>">

                        <!-- Packeta selection display -->
                        <div id="packeta-selection">
                            <?php if(old('packeta_point_id', auth()->user()->packeta_point_id ?? '')): ?>
                            <!-- Selected point display - Minimal -->
                            <div id="selected-point" class="p-4 bg-primary-50 border border-primary-300 rounded-xl">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1.5">
                                            <svg class="w-4 h-4 text-primary-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-medium text-gray-900 text-sm">Vybrané výdejní místo:</span>
                                        </div>
                                        <p class="text-gray-900 font-medium ml-6" id="selected-point-name"><?php echo e(old('packeta_point_name', auth()->user()->packeta_point_name ?? '')); ?></p>
                                        <p class="text-sm text-gray-600 ml-6 font-light" id="selected-point-address"><?php echo e(old('packeta_point_address', auth()->user()->packeta_point_address ?? '')); ?></p>
                                    </div>
                                    <button type="button" id="change-point-btn" class="text-sm bg-white hover:bg-gray-50 text-[#ba1b02] font-medium px-4 py-2 rounded-full border border-gray-200 whitespace-nowrap ml-4 transition-colors">
                                        Změnit
                                    </button>
                                </div>
                            </div>
                            <?php else: ?>
                            <!-- Select button - Minimal -->
                            <button type="button" id="select-point-btn" class="w-full flex items-center justify-center gap-2 bg-[#ba1b02] hover:bg-[#a01701] text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Vybrat výdejní místo Zásilkovna</span>
                            </button>
                            <?php endif; ?>
                        </div>

                        <?php $__errorArgs = ['packeta_point_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 text-sm mt-2"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-blue-700 font-light">
                                Káva vám bude doručena na vybrané výdejní místo Zásilkovny
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Method - Minimal -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Způsob platby</h2>
                    </div>
                    
                    <!-- Single Payment Method Info - Minimal -->
                    <div class="p-4 border border-primary-400 bg-primary-50 rounded-xl">
                        <input type="hidden" name="payment_method" value="card">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-gray-900 mb-1">Platební kartou</div>
                                <div class="text-sm text-gray-600 mb-3 font-light">Po odeslání objednávky budete přesměrováni na bezpečnou platební bránu</div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs text-gray-500 font-medium">Akceptujeme:</span>
                                    <div class="flex items-center gap-2">
                                        <div class="px-2.5 py-1 bg-white rounded-lg border border-gray-200">
                                            <span class="text-xs font-semibold text-blue-700">Visa</span>
                                        </div>
                                        <div class="px-2.5 py-1 bg-white rounded-lg border border-gray-200">
                                            <span class="text-xs font-semibold text-orange-600">Mastercard</span>
                                        </div>
                                        <div class="px-2.5 py-1 bg-white rounded-lg border border-gray-200">
                                            <span class="text-xs font-semibold text-gray-700">Apple Pay</span>
                                        </div>
                                        <div class="px-2.5 py-1 bg-white rounded-lg border border-gray-200">
                                            <span class="text-xs font-semibold text-blue-600">Google Pay</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes - Minimal -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Poznámka <span class="text-base font-light text-gray-500">(volitelné)</span></h2>
                    </div>
                    
                    <textarea 
                        id="delivery_notes" 
                        name="delivery_notes" 
                        rows="4" 
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                        placeholder="Např. 'Prosím zvonit na 2. patro' nebo 'Nechat u vrátnice'"
                    ><?php echo e(old('delivery_notes')); ?></textarea>
                    <?php $__errorArgs = ['delivery_notes'];
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
            </form>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-6 sticky top-24 border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Souhrn předplatného</h3>
                </div>
                
                <?php
                $frequencyTexts = [
                    1 => 'Každý měsíc',
                    2 => 'Jednou za 2 měsíce',
                    3 => 'Jednou za 3 měsíce'
                ];
                $frequencyText = $frequencyTexts[$configuration['frequency']] ?? '';
                ?>
                
                <!-- Subscription Details -->
                <div class="bg-gray-100 p-6 rounded-xl mb-6 border border-gray-200">
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-700 font-semibold">Množství:</span>
                            <span class="font-bold text-gray-900 text-right"><?php echo e($configuration['amount']); ?> balení (<?php echo e($configuration['amount'] * 250); ?>g)</span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-gray-700 font-semibold">Typ kávy:</span>
                                <span class="font-bold text-gray-900 text-right">
                                    <?php if($configuration['type'] === 'espresso'): ?>
                                        Espresso <?php if($configuration['isDecaf']): ?>(vč. 1× decaf)<?php endif; ?>
                                    <?php elseif($configuration['type'] === 'filter'): ?>
                                        Filtr <?php if($configuration['isDecaf']): ?>(vč. 1× decaf)<?php endif; ?>
                                    <?php else: ?>
                                        Kombinace <?php if($configuration['isDecaf']): ?>(vč. 1× decaf)<?php endif; ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <?php if($configuration['type'] === 'mix'): ?>
                            <div class="mt-2 pl-4 space-y-1 text-xs text-gray-700">
                                <?php if(isset($configuration['mix']['espresso']) && $configuration['mix']['espresso'] > 0): ?>
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2 font-bold">•</span>
                                    <?php echo e($configuration['mix']['espresso']); ?>× Espresso
                                </div>
                                <?php endif; ?>
                                <?php if(isset($configuration['mix']['filter']) && $configuration['mix']['filter'] > 0): ?>
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2 font-bold">•</span>
                                    <?php echo e($configuration['mix']['filter']); ?>× Filtr
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                            <span class="text-gray-700 font-semibold">Frekvence:</span>
                            <span class="font-bold text-gray-900"><?php echo e($frequencyText); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Date Info - Minimal -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0 mr-3">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 mb-2">Informace o dodání</h3>
                            <p class="text-sm text-gray-800 mb-2 font-medium">
                                <?php echo e($shippingInfo['cutoff_message']); ?>

                            </p>
                            <p class="text-xs text-gray-600 leading-relaxed font-light">
                                Rozesílka probíhá vždy <strong>20. den v měsíci</strong>. Objednávky do 15. v měsíci jsou zahrnuty v aktuální rozesílce, 
                                objednávky od 16. dne jsou zahrnuty až v následující rozesílce.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Coupon Section -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <?php if(session('coupon_error')): ?>
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-red-800"><?php echo e(session('coupon_error')); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($appliedCoupon ?? null): ?>
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium text-green-800">Kupón aplikován</span>
                                </div>
                                <a href="<?php echo e(route('subscriptions.checkout', ['remove_coupon' => 1])); ?>" class="text-xs text-red-600 hover:text-red-800 hover:underline">
                                    Odebrat
                                </a>
                            </div>
                            <p class="text-sm text-green-700 font-mono font-bold"><?php echo e($appliedCoupon->code); ?></p>
                            <p class="text-xs text-green-600 mt-1"><?php echo e($appliedCoupon->getSubscriptionDiscountDescription()); ?></p>
                        </div>
                    <?php else: ?>
                        <details class="group" <?php echo e(request()->has('coupon_code') ? 'open' : ''); ?>>
                            <summary class="flex items-center justify-between cursor-pointer p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <span class="text-sm font-medium text-gray-700">Mám slevový kupón</span>
                                <svg class="w-5 h-5 text-gray-600 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <form method="GET" action="<?php echo e(route('subscriptions.checkout')); ?>" class="mt-3">
                                <div class="flex gap-2">
                                    <input type="text" name="coupon_code" placeholder="SLEVOVYKOD" 
                                        class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 uppercase text-sm"
                                        value="<?php echo e(request('coupon_code')); ?>">
                                    <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                        Použít
                                    </button>
                                </div>
                            </form>
                        </details>
                    <?php endif; ?>
                </div>

                <!-- Price Summary -->
                <dl class="space-y-3 mb-6">
                    <!-- Doprava (first) -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-600 text-sm">Doprava:</dt>
                        <dd class="font-bold">
                            <span class="text-green-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Zdarma
                            </span>
                        </dd>
                    </div>
                    
                    <!-- Coupon discount (prominently displayed) -->
                    <?php if(($discount ?? 0) > 0): ?>
                    <div class="flex justify-between items-center py-3 border-b-2 border-green-200 bg-green-50 -mx-6 px-6">
                        <dt class="text-green-700 font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>Sleva <?php echo e($appliedCoupon->code ?? 'kupón'); ?>:</span>
                        </dt>
                        <dd class="font-bold text-green-600 text-lg">-<?php echo e(number_format($discount, 0, ',', ' ')); ?> Kč</dd>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Subtotal without VAT -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-600 text-sm"><?php echo e($configuration['amount']); ?>× balení kávy (bez DPH):</dt>
                        <dd class="font-bold text-gray-900"><?php echo e(number_format($priceWithoutVat, 2, ',', ' ')); ?> Kč</dd>
                    </div>
                    
                    <!-- VAT -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-600 text-sm">DPH (21%):</dt>
                        <dd class="font-bold text-gray-900"><?php echo e(number_format($vat, 2, ',', ' ')); ?> Kč</dd>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-5 mt-2">
                        <div class="flex justify-between items-center mb-1">
                            <dt class="font-bold text-gray-900 text-lg">Celkem / měsíc:</dt>
                            <dd class="text-3xl font-bold text-gray-900">
                                <?php echo e(number_format($price, 0, ',', ' ')); ?> Kč
                            </dd>
                        </div>
                        <p class="text-xs text-gray-500 text-right mt-1">
                            (<?php echo e($frequencyText); ?>, vč. DPH)
                        </p>
                    </div>
                </dl>

                <button type="submit" form="subscription-checkout-form" class="group w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200 mb-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Dokončit objednávku</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>

                <div class="flex items-start mb-4 p-3 bg-gray-50 rounded-xl">
                    <input type="checkbox" id="terms" required form="subscription-checkout-form" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 mr-2.5 mt-0.5 flex-shrink-0">
                    <label for="terms" class="text-xs text-gray-600 font-light">
                        Souhlasím s <a href="#" class="text-primary-600 hover:text-primary-700 font-medium underline">obchodními podmínkami</a> 
                        a <a href="#" class="text-primary-600 hover:text-primary-700 font-medium underline">zásadami ochrany osobních údajů</a>
                    </label>
                </div>

                <a href="<?php echo e(route('subscriptions.index')); ?>" class="block w-full text-center bg-white hover:bg-gray-50 text-gray-900 font-medium px-6 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Zpět na konfigurátor</span>
                </a>

                <!-- Trust Badges - Minimal -->
                <div class="mt-6 pt-6 border-t border-gray-100 space-y-2.5">
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Bez závazků - kdykoli zrušte</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Čerstvě pražená káva</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Doprava vždy zdarma</span>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<script src="https://widget.packeta.com/v6/www/js/library.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Packeta Widget Configuration
    const packetaApiKey = '<?php echo e(config("services.packeta.widget_key")); ?>';
    
    function openPacketaWidget() {
        if (!packetaApiKey) {
            alert('Packeta widget není správně nakonfigurován. Kontaktujte administrátora.');
            return;
        }

        Packeta.Widget.pick(packetaApiKey, function(point) {
            if (point) {
                // Fill hidden fields with selected point data
                document.getElementById('packeta_point_id').value = point.id;
                document.getElementById('packeta_point_name').value = point.name;
                
                // Format address
                let address = point.street;
                if (point.city) {
                    address += ', ' + (point.zip ? point.zip + ' ' : '') + point.city;
                }
                document.getElementById('packeta_point_address').value = address;

                // Update UI to show selected point - Minimal
                const selectionDiv = document.getElementById('packeta-selection');
                selectionDiv.innerHTML = `
                    <div id="selected-point" class="p-4 bg-primary-50 border border-primary-300 rounded-xl">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-1.5">
                                    <svg class="w-4 h-4 text-primary-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 text-sm">Vybrané výdejní místo:</span>
                                </div>
                                <p class="text-gray-900 font-medium ml-6">${point.name}</p>
                                <p class="text-sm text-gray-600 ml-6 font-light">${address}</p>
                            </div>
                            <button type="button" id="change-point-btn" class="text-sm bg-white hover:bg-gray-50 text-[#ba1b02] font-medium px-4 py-2 rounded-full border border-gray-200 whitespace-nowrap ml-4 transition-colors">
                                Změnit
                            </button>
                        </div>
                    </div>
                `;

                // Re-attach event listener to the new change button
                document.getElementById('change-point-btn').addEventListener('click', openPacketaWidget);
            }
        }, {
            country: 'cz',
            language: 'cs',
            // Můžete zde přidat vendors pro omezení dopravců, např:
            // vendors: ['packeta', 'zasilkovna'],
        });
    }

    // Event listeners for opening widget
    const selectBtn = document.getElementById('select-point-btn');
    if (selectBtn) {
        selectBtn.addEventListener('click', openPacketaWidget);
    }

    const changeBtn = document.getElementById('change-point-btn');
    if (changeBtn) {
        changeBtn.addEventListener('click', openPacketaWidget);
    }
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/subscriptions/checkout.blade.php ENDPATH**/ ?>