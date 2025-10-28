<?php $__env->startSection('title', 'Můj profil - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-primary-500">
        <div class="flex items-center gap-4">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-500 to-pink-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

            </div>
            <div>
                <h1 class="text-4xl font-bold text-dark-800 mb-1"><?php echo e(auth()->user()->name); ?></h1>
                <p class="text-lg text-dark-600"><?php echo e(auth()->user()->email); ?></p>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-bluegray-50 p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-dark-800">Osobní údaje</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="<?php echo e(route('dashboard.profile.update')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-dark-800 mb-2">Celé jméno</label>
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
                        <label for="email" class="block text-sm font-bold text-dark-800 mb-2">Email</label>
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
                        <label for="phone" class="block text-sm font-bold text-dark-800 mb-2">Telefon</label>
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

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="btn btn-primary">
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
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-bluegray-50 p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-dark-800">Změna hesla</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="<?php echo e(route('dashboard.password.update')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div>
                    <label for="current_password" class="block text-sm font-bold text-dark-800 mb-2">Současné heslo</label>
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
                        <label for="password" class="block text-sm font-bold text-dark-800 mb-2">Nové heslo</label>
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
                        <label for="password_confirmation" class="block text-sm font-bold text-dark-800 mb-2">Potvrdit nové heslo</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="input"
                               required>
                    </div>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
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
                    <button type="submit" class="btn btn-primary">
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
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-dark-600 font-semibold mb-1">Celkem objednávek</p>
                    <p class="text-3xl font-bold text-dark-800"><?php echo e(auth()->user()->orders()->count()); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-dark-600 font-semibold mb-1">Aktivní předplatné</p>
                    <p class="text-3xl font-bold text-dark-800"><?php echo e(auth()->user()->subscriptions()->where('status', 'active')->count()); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-dark-600 font-semibold mb-1">Členem od</p>
                    <p class="text-xl font-bold text-dark-800"><?php echo e(auth()->user()->created_at->format('m/Y')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-red-200">
        <div class="bg-red-50 p-6 border-b border-red-200">
            <h2 class="text-2xl font-bold text-red-800">Nebezpečná zóna</h2>
        </div>
        <div class="p-6">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-lg font-bold text-dark-800 mb-1">Smazat účet</h3>
                    <p class="text-dark-600">
                        Trvale odstraní váš účet a všechna související data. Tato akce je nevratná.
                    </p>
                </div>
                <button type="button" 
                        onclick="alert('Tato funkce bude implementována později')"
                        class="btn bg-red-600 hover:bg-red-700 text-white">
                    Smazat účet
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/profile.blade.php ENDPATH**/ ?>