<?php $__env->startSection('title', 'Nastavení konfiguratoru - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-4xl font-bold text-dark-800 mb-2">Nastavení konfiguratoru předplatného</h1>
                <p class="text-dark-600">Upravte parametry pro konfigurátor kávového předplatného</p>
            </div>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline">
                ← Zpět na dashboard
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="card p-8">
        <form action="<?php echo e(route('admin.subscription-config.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="space-y-8">
                <?php $__currentLoopData = $configs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border-b pb-6 last:border-b-0">
                    <label class="block text-lg font-bold text-dark-800 mb-2">
                        <?php echo e($config->label); ?>

                    </label>
                    
                    <?php if($config->description): ?>
                    <p class="text-sm text-dark-600 mb-4"><?php echo e($config->description); ?></p>
                    <?php endif; ?>
                    
                    <div class="flex items-center space-x-4">
                        <?php if($config->type === 'boolean'): ?>
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="configs[<?php echo e($index); ?>][value]" 
                                value="1"
                                <?php echo e($config->value ? 'checked' : ''); ?>

                                class="w-5 h-5 text-primary-500 border-gray-300 rounded focus:ring-primary-500"
                            >
                            <span class="ml-3 text-dark-700">Zapnuto</span>
                        </label>
                        <?php elseif($config->type === 'decimal'): ?>
                        <div class="relative flex-1 max-w-xs">
                            <input 
                                type="number" 
                                step="0.01"
                                name="configs[<?php echo e($index); ?>][value]" 
                                value="<?php echo e($config->value); ?>"
                                required
                                class="input pr-12"
                            >
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-dark-600">
                                Kč
                            </span>
                        </div>
                        <?php elseif($config->type === 'integer'): ?>
                        <input 
                            type="number" 
                            name="configs[<?php echo e($index); ?>][value]" 
                            value="<?php echo e($config->value); ?>"
                            required
                            class="input max-w-xs"
                        >
                        <?php else: ?>
                        <input 
                            type="text" 
                            name="configs[<?php echo e($index); ?>][value]" 
                            value="<?php echo e($config->value); ?>"
                            required
                            class="input max-w-md"
                        >
                        <?php endif; ?>
                        
                        <input type="hidden" name="configs[<?php echo e($index); ?>][key]" value="<?php echo e($config->key); ?>">
                        
                        <div class="text-sm text-dark-500">
                            Typ: <code class="bg-bluegray-100 px-2 py-1 rounded"><?php echo e($config->type); ?></code>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="mt-8 flex items-center justify-between">
                <p class="text-sm text-dark-600">
                    <svg class="w-5 h-5 inline mr-1 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Změny se projeví okamžitě na frontendovém konfiguratoru.
                </p>
                
                <button type="submit" class="btn btn-primary">
                    Uložit změny
                </button>
            </div>
        </form>
    </div>

    <!-- Info Panel -->
    <div class="mt-8 card p-6 bg-bluegray-50">
        <h3 class="font-bold text-lg text-dark-800 mb-4">ℹ️ Informace o nastavení</h3>
        <div class="space-y-3 text-sm text-dark-700">
            <p>
                <strong>Cena za balení:</strong> Základní cena, kterou zákazník zaplatí za jedno balení kávy. 
                Celková cena se vypočítá jako: počet balení × cena za balení.
            </p>
            <p>
                <strong>Minimální/maximální počet balení:</strong> Tyto hodnoty určují rozsah posuvníků v konfiguratoru.
            </p>
            <p class="text-primary-600">
                💡 <strong>Tip:</strong> Po změně ceny doporučujeme otestovat konfigurátor na frontendové stránce.
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/subscription-config/index.blade.php ENDPATH**/ ?>