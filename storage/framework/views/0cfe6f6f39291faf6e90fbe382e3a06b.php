<?php $__env->startSection('title', 'Můj profil - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-gray-900 flex items-center justify-center text-white font-bold text-2xl">
                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900 mb-0.5"><?php echo e(auth()->user()->name); ?></h1>
                <p class="text-base text-gray-600 font-light"><?php echo e(auth()->user()->email); ?></p>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Osobní údaje</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="<?php echo e(route('dashboard.profile.update')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-900 mb-2">Celé jméno</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="<?php echo e(old('name', auth()->user()->name)); ?>" 
                               class="input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e($message); ?>

                        </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-900 mb-2">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo e(old('email', auth()->user()->email)); ?>" 
                               class="input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e($message); ?>

                        </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-900 mb-2">Telefon</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="<?php echo e(old('phone', auth()->user()->phone ?? '')); ?>" 
                               class="input <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="+420 123 456 789">
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e($message); ?>

                        </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mt-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">
                                <strong>Tip:</strong> Vyplňte si telefon a adresu pro rychlejší objednávky. Údaje budou automaticky předvyplněné v pokladně.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Uložit změny
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Billing Address -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Fakturační adresa</h2>
            <p class="text-sm text-gray-600 mt-1 font-light">Tato adresa bude předvyplněná při objednávkách</p>
        </div>
        <div class="p-6">
            <form method="POST" action="<?php echo e(route('dashboard.profile.update')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-900 mb-2">Ulice a číslo popisné</label>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           value="<?php echo e(old('address', auth()->user()->address ?? '')); ?>" 
                           class="input <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="Např. Karlova 123">
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-900 mb-2">Město</label>
                        <input type="text" 
                               id="city" 
                               name="city" 
                               value="<?php echo e(old('city', auth()->user()->city ?? '')); ?>" 
                               class="input <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Např. Praha">
                        <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e($message); ?>

                        </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-900 mb-2">PSČ</label>
                        <input type="text" 
                               id="postal_code" 
                               name="postal_code" 
                               value="<?php echo e(old('postal_code', auth()->user()->postal_code ?? '')); ?>" 
                               class="input <?php $__errorArgs = ['postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="123 45">
                        <?php $__errorArgs = ['postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e($message); ?>

                        </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Uložit změny
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Packeta Pickup Point -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Výdejní místo Zásilkovny</h2>
            <p class="text-sm text-gray-600 mt-1 font-light">Nastavte si preferované výdejní místo pro vaše objednávky</p>
        </div>
        <div class="p-6">
            <form method="POST" action="<?php echo e(route('dashboard.profile.update')); ?>" id="packeta-form" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Hidden fields for Packeta data -->
                <input type="hidden" id="packeta_point_id" name="packeta_point_id" value="<?php echo e(old('packeta_point_id', auth()->user()->packeta_point_id ?? '')); ?>">
                <input type="hidden" id="packeta_point_name" name="packeta_point_name" value="<?php echo e(old('packeta_point_name', auth()->user()->packeta_point_name ?? '')); ?>">
                <input type="hidden" id="packeta_point_address" name="packeta_point_address" value="<?php echo e(old('packeta_point_address', auth()->user()->packeta_point_address ?? '')); ?>">

                <!-- Packeta selection display -->
                <div id="packeta-selection">
                    <?php if(auth()->user()->packeta_point_id): ?>
                    <!-- Selected point display -->
                    <div id="selected-point" class="p-4 bg-primary-50 border border-primary-300 rounded-xl mb-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-1.5">
                                    <svg class="w-4 h-4 text-primary-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 text-sm">Aktuální výdejní místo:</span>
                                </div>
                                <p class="text-gray-900 font-medium ml-6" id="selected-point-name"><?php echo e(auth()->user()->packeta_point_name); ?></p>
                                <p class="text-sm text-gray-600 ml-6 font-light" id="selected-point-address"><?php echo e(auth()->user()->packeta_point_address); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl mb-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-gray-600">Zatím nemáte nastavené žádné výdejní místo.</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <button type="button" id="select-point-btn" class="w-full flex items-center justify-center gap-2 bg-[#ba1b02] hover:bg-[#a01701] text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span><?php echo e(auth()->user()->packeta_point_id ? 'Změnit výdejní místo' : 'Vybrat výdejní místo'); ?></span>
                    </button>
                </div>

                <?php $__errorArgs = ['packeta_point_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <?php echo e($message); ?>

                </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Uložit změny
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Změna hesla</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="<?php echo e(route('dashboard.password.update')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-900 mb-2">Současné heslo</label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="input <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           required>
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900 mb-2">Nové heslo</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo e($message); ?>

                        </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">Potvrdit nové heslo</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="input"
                               required>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">
                                Heslo musí obsahovat minimálně 8 znaků.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Změnit heslo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Account Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-light font-medium mb-1">Celkem objednávek</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e(auth()->user()->orders()->count()); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-light font-medium mb-1">Aktivní předplatné</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e(auth()->user()->subscriptions()->where('status', 'active')->count()); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-light font-medium mb-1">Členem od</p>
                    <p class="text-xl font-bold text-gray-900"><?php echo e(auth()->user()->created_at->format('m/Y')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden border border-red-200">
        <div class="bg-red-50 p-6 border-b border-red-200">
            <h2 class="text-xl font-bold text-red-800">Nebezpečná zóna</h2>
        </div>
        <div class="p-6">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Smazat účet</h3>
                    <p class="text-gray-600 font-light">
                        Trvale odstraní váš účet a všechna související data. Tato akce je nevratná.
                    </p>
                </div>
                <button type="button" 
                        onclick="alert('Tato funkce bude implementována později')"
                        class="bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                    Smazat účet
                </button>
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

                // Update UI to show selected point
                const selectionDiv = document.getElementById('packeta-selection');
                selectionDiv.innerHTML = `
                    <div id="selected-point" class="p-4 bg-primary-50 border border-primary-300 rounded-xl mb-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-1.5">
                                    <svg class="w-4 h-4 text-primary-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 text-sm">Aktuální výdejní místo:</span>
                                </div>
                                <p class="text-gray-900 font-medium ml-6">${point.name}</p>
                                <p class="text-sm text-gray-600 ml-6 font-light">${address}</p>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="select-point-btn" class="w-full flex items-center justify-center gap-2 bg-[#ba1b02] hover:bg-[#a01701] text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Změnit výdejní místo</span>
                    </button>
                `;

                // Re-attach event listener to the new button
                document.getElementById('select-point-btn').addEventListener('click', openPacketaWidget);
            }
        }, {
            country: 'cz',
            language: 'cs',
        });
    }

    // Event listener for opening widget
    const selectBtn = document.getElementById('select-point-btn');
    if (selectBtn) {
        selectBtn.addEventListener('click', openPacketaWidget);
    }
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/profile.blade.php ENDPATH**/ ?>