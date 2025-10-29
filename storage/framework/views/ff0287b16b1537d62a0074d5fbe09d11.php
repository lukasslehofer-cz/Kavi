<?php $__env->startSection('title', 'Přihlášení - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[calc(100vh-20rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl p-10 border border-gray-200">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Vítejte zpět</h2>
                <p class="text-gray-600 font-light">Přihlaste se ke svému účtu</p>
            </div>
            
            <form method="POST" action="/prihlaseni" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-300 transition-all <?php $__errorArgs = ['email'];
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
                    <label for="password" class="block text-sm font-medium text-gray-900 mb-2">Heslo</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-300 transition-all <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="••••••••">
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

                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-500 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700 font-light">Zapamatovat si mě</span>
                    </label>
                    <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Zapomenuté heslo?
                    </a>
                </div>

                <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                    Přihlásit se
                </button>
            </form>

            <!-- Magic Link Section -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-600 font-light">nebo</span>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('magic-link.send')); ?>" class="mt-6" id="magic-link-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="email" id="magic-link-email">
                    <button type="button" onclick="sendMagicLink()" class="w-full flex items-center justify-center gap-2 px-4 py-3 border border-gray-200 rounded-full text-gray-700 bg-white hover:bg-gray-50 font-medium transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Přihlásit se odkazem z emailu
                    </button>
                </form>

                <p class="mt-3 text-xs text-center text-gray-600 font-light">
                    💡 Bez hesla! Stačí zadat email a my vám pošleme přihlašovací odkaz.
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-gray-600 font-light">
                    Ještě nemáte účet?
                    <a href="<?php echo e(route('register')); ?>" class="text-primary-600 hover:text-primary-700 font-medium ml-1">
                        Zaregistrujte se zdarma
                    </a>
                </p>
            </div>
        </div>

        <!-- Benefits -->
        <div class="mt-8 text-center">
            <div class="flex justify-center items-center gap-6 text-sm text-gray-600 font-light">
                <span class="flex items-center">
                    <svg class="w-4 h-4 text-primary-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Bezpečné
                </span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 text-primary-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Rychlé
                </span>
            </div>
        </div>
    </div>
</div>

<script>
function sendMagicLink() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    
    if (!email) {
        alert('Prosím zadejte váš email');
        emailInput.focus();
        return;
    }
    
    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Prosím zadejte platný email');
        emailInput.focus();
        return;
    }
    
    // Copy email to hidden field and submit form
    document.getElementById('magic-link-email').value = email;
    document.getElementById('magic-link-form').submit();
}

// Allow Enter key to send magic link when email input is focused
document.getElementById('email').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && e.shiftKey) {
        e.preventDefault();
        sendMagicLink();
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>