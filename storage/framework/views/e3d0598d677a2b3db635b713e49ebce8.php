<?php $__env->startSection('title', 'Nastavení konfiguratoru'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Nastavení konfiguratoru předplatného</h1>
        <p class="text-gray-600 mt-1">Upravte parametry pro konfigurátor kávového předplatného a harmonogram rozesílek</p>
    </div>

    <?php if(session('success')): ?>
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('info')): ?>
    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg">
        <?php echo e(session('info')); ?>

    </div>
    <?php endif; ?>

    <!-- Configurator Settings -->
    <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Nastavení cen a parametrů</h2>
        
        <form action="<?php echo e(route('admin.subscription-config.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="space-y-8">
                <?php $__currentLoopData = $configs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border-b pb-6 last:border-b-0 border-gray-200">
                    <label class="block text-lg font-bold text-gray-900 mb-2">
                        <?php echo e($config->label); ?>

                    </label>
                    
                    <?php if($config->description): ?>
                    <p class="text-sm text-gray-600 mb-4"><?php echo e($config->description); ?></p>
                    <?php endif; ?>
                    
                    <div class="flex items-center space-x-4">
                        <?php if($config->type === 'boolean'): ?>
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="configs[<?php echo e($index); ?>][value]" 
                                value="1"
                                <?php echo e($config->value ? 'checked' : ''); ?>

                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <span class="ml-3 text-gray-700 font-medium">Zapnuto</span>
                        </label>
                        <?php elseif($config->type === 'decimal'): ?>
                        <div class="relative flex-1 max-w-xs">
                            <input 
                                type="number" 
                                step="0.01"
                                name="configs[<?php echo e($index); ?>][value]" 
                                value="<?php echo e($config->value); ?>"
                                required
                                class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-600 font-medium">
                                Kč
                            </span>
                        </div>
                        <?php elseif($config->type === 'integer'): ?>
                        <input 
                            type="number" 
                            name="configs[<?php echo e($index); ?>][value]" 
                            value="<?php echo e($config->value); ?>"
                            required
                            class="w-full max-w-xs px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <?php else: ?>
                        <input 
                            type="text" 
                            name="configs[<?php echo e($index); ?>][value]" 
                            value="<?php echo e($config->value); ?>"
                            required
                            class="w-full max-w-md px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <?php endif; ?>
                        
                        <input type="hidden" name="configs[<?php echo e($index); ?>][key]" value="<?php echo e($config->key); ?>">
                        
                        <div class="text-sm text-gray-500">
                            Typ: <code class="bg-gray-100 px-2 py-1 rounded text-xs font-mono"><?php echo e($config->type); ?></code>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="mt-8 flex items-center justify-end">
                <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Uložit změny
                </button>
            </div>
        </form>
    </div>

    <!-- Shipment Schedule -->
    <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Harmonogram rozesílek</h2>
                <p class="text-sm text-gray-600 mt-1">Upravte datumy plateb a rozesílek, nastavte kávu a pražírnu měsíce</p>
            </div>
            <form action="<?php echo e(route('admin.subscription-config.create-next-year')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Vytvořit rok <?php echo e(now()->year + 2); ?>

                </button>
            </form>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-4" role="tablist">
                <button 
                    type="button"
                    class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-blue-600 text-blue-600"
                    data-year="<?php echo e($currentYear); ?>"
                    onclick="switchYear(<?php echo e($currentYear); ?>)"
                >
                    Rok <?php echo e($currentYear); ?>

                </button>
                <button 
                    type="button"
                    class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300"
                    data-year="<?php echo e($nextYear); ?>"
                    onclick="switchYear(<?php echo e($nextYear); ?>)"
                >
                    Rok <?php echo e($nextYear); ?>

                </button>
                <?php $__currentLoopData = $previousYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prevYear): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button 
                    type="button"
                    class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300"
                    data-year="<?php echo e($prevYear); ?>"
                    onclick="switchYear(<?php echo e($prevYear); ?>)"
                >
                    Rok <?php echo e($prevYear); ?> (archiv)
                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>
        </div>

        <form action="<?php echo e(route('admin.subscription-config.update-schedule')); ?>" method="POST" id="schedule-form">
            <?php echo csrf_field(); ?>
            
            <!-- Current Year Schedule -->
            <div id="schedule-<?php echo e($currentYear); ?>" class="schedule-year-content">
                <?php echo $__env->make('admin.subscription-config._schedule-table', ['schedules' => $currentYearSchedules, 'year' => $currentYear, 'coffeeProducts' => $coffeeProducts], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <!-- Next Year Schedule -->
            <div id="schedule-<?php echo e($nextYear); ?>" class="schedule-year-content hidden">
                <?php echo $__env->make('admin.subscription-config._schedule-table', ['schedules' => $nextYearSchedules, 'year' => $nextYear, 'coffeeProducts' => $coffeeProducts], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <!-- Previous Years (will be loaded via AJAX) -->
            <?php $__currentLoopData = $previousYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prevYear): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="schedule-<?php echo e($prevYear); ?>" class="schedule-year-content hidden">
                <div class="text-center py-8 text-gray-500">Načítám...</div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <div class="mt-8 flex items-center justify-end">
                <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Uložit harmonogram
                </button>
            </div>
        </form>
    </div>

    <!-- Info Panel -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
        <h3 class="flex items-center gap-2 font-bold text-lg text-gray-900 mb-4">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            Informace o nastavení
        </h3>
        <div class="space-y-3 text-sm text-gray-700">
            <p>
                <strong>Datum platby:</strong> Den v měsíci, kdy Stripe strhne platbu z karty zákazníka. Pro nové zákazníky se toto datum použije jako billing anchor date.
            </p>
            <p>
                <strong>Datum rozesílky:</strong> Den v měsíci, kdy budou odeslány balíčky zákazníkům přes Packetu.
            </p>
            <p>
                <strong>Káva měsíce:</strong> Produkt, který bude v daném měsíci rozesílán předplatitelům. Vybírejte pouze z produktů označených jako "Káva měsíce".
            </p>
            <p>
                <strong>Pražírna měsíce:</strong> Název pražírny, která dodává kávu pro daný měsíc (např. "Nordbeans", "Doubleshot").
            </p>
            <p class="flex items-center gap-2 text-blue-700 font-medium bg-white p-3 rounded-lg">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <strong>Tip:</strong> Minulé rozesílky nelze editovat. Budoucí rozesílky můžete upravovat podle potřeby (svátky, dovolené, atd.).
            </p>
        </div>
    </div>
</div>

<script>
let currentYear = <?php echo e($currentYear); ?>;

function switchYear(year) {
    // Hide all schedule contents
    document.querySelectorAll('.schedule-year-content').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-blue-600', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-600');
    });
    
    // Show selected year
    const content = document.getElementById(`schedule-${year}`);
    if (content) {
        content.classList.remove('hidden');
    }
    
    // Set active tab
    const activeTab = document.querySelector(`.tab-button[data-year="${year}"]`);
    if (activeTab) {
        activeTab.classList.remove('border-transparent', 'text-gray-600');
        activeTab.classList.add('border-blue-600', 'text-blue-600');
    }
    
    currentYear = year;
    
    // Load previous year data via AJAX if needed
    <?php $__currentLoopData = $previousYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prevYear): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    if (year === <?php echo e($prevYear); ?> && !content.dataset.loaded) {
        loadYearSchedules(<?php echo e($prevYear); ?>);
    }
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
}

function loadYearSchedules(year) {
    const content = document.getElementById(`schedule-${year}`);
    
    fetch(`<?php echo e(route('admin.subscription-config.year-schedules', ['year' => '__YEAR__'])); ?>`.replace('__YEAR__', year))
        .then(response => response.json())
        .then(data => {
            // Render schedules (simplified - you might want to use a template)
            content.innerHTML = '<div class="text-center py-8 text-gray-500">Archivní data pro rok ' + year + '</div>';
            content.dataset.loaded = 'true';
        })
        .catch(error => {
            console.error('Error loading schedules:', error);
            content.innerHTML = '<div class="text-center py-8 text-red-500">Chyba při načítání dat</div>';
        });
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/subscription-config/index.blade.php ENDPATH**/ ?>