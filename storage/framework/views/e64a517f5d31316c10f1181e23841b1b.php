<?php $__env->startSection('title', 'Registrace - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[calc(100vh-20rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-bluegray-50">
    <div class="max-w-md w-full">
        <div class="card p-10 shadow-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                </div>
                <h2 class="font-display text-3xl font-bold text-dark-800 mb-2">Vytvořte si účet</h2>
                <p class="text-dark-600">Začněte svou kávovou cestu s námi</p>
            </div>
            
            <form method="POST" action="/registrace" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="name" class="block text-sm font-semibold text-dark-800 mb-2">Celé jméno</label>
                    <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" required autofocus
                           class="input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="Jan Novák">
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
                    <label for="email" class="block text-sm font-semibold text-dark-800 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required
                           class="input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="vas@email.cz">
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
                    <label for="password" class="block text-sm font-semibold text-dark-800 mb-2">Heslo</label>
                    <input id="password" type="password" name="password" required
                           class="input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="Minimálně 8 znaků">
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
                    <label for="password_confirmation" class="block text-sm font-semibold text-dark-800 mb-2">Potvrzení hesla</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="input"
                           placeholder="Zadejte heslo znovu">
                </div>

                <div class="bg-bluegray-50 rounded-xl p-4">
                    <p class="text-xs text-dark-600">
                        Registrací souhlasíte s našimi 
                        <a href="#" class="text-primary-500 hover:text-primary-600 font-semibold">obchodními podmínkami</a>
                        a
                        <a href="#" class="text-primary-500 hover:text-primary-600 font-semibold">zásadami ochrany osobních údajů</a>.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary w-full text-lg">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Zaregistrovat se zdarma
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-bluegray-200 text-center">
                <p class="text-dark-600">
                    Už máte účet?
                    <a href="<?php echo e(route('login')); ?>" class="text-primary-500 hover:text-primary-600 font-semibold ml-1">
                        Přihlaste se
                    </a>
                </p>
            </div>
        </div>

        <!-- Benefits -->
        <div class="mt-8">
            <p class="text-center text-dark-600 font-semibold mb-4">Co získáte registrací:</p>
            <div class="grid grid-cols-1 gap-3 text-sm">
                <div class="flex items-center bg-white rounded-xl p-4 shadow-sm">
                    <svg class="w-5 h-5 text-primary-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-dark-700">Přístup k exkluzivním nabídkám</span>
                </div>
                <div class="flex items-center bg-white rounded-xl p-4 shadow-sm">
                    <svg class="w-5 h-5 text-primary-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-dark-700">Správa předplatného online</span>
                </div>
                <div class="flex items-center bg-white rounded-xl p-4 shadow-sm">
                    <svg class="w-5 h-5 text-primary-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-dark-700">Historie objednávek a sledování zásilek</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/register.blade.php ENDPATH**/ ?>