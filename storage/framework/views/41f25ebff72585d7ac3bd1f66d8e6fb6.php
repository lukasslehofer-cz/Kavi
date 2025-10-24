<?php $__env->startSection('title', 'Kavi Coffee - Prémiová káva s předplatným'); ?>

<?php $__env->startSection('content'); ?>

<!-- Hero Section - Kaffebox inspired -->
<section class="relative bg-gradient-to-br from-bluegray-100 via-white to-bluegray-50 overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="text-center lg:text-left">
                <div class="inline-block mb-4">
                    <span class="badge badge-primary text-xs uppercase tracking-wide">Nově v ČR</span>
                </div>
                <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold text-dark-800 mb-6 leading-tight">
                    Prémiová káva<br/>
                    <span class="text-gradient">přímo k vám</span>
                </h1>
                <p class="text-xl md:text-2xl text-dark-600 mb-8 max-w-xl mx-auto lg:mx-0">
                    Objevte nejlepší českou kávu s pravidelným předplatným. Čerstvě pražená, pečlivě vybraná.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="btn btn-primary text-lg">
                        Vybrat předplatné
                    </a>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline text-lg">
                        Prozkoumat kávy
                    </a>
                </div>
                
                <!-- Trust Badges -->
                <div class="mt-12 flex items-center justify-center lg:justify-start space-x-8 text-sm text-dark-600">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Doprava zdarma</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Bez závazku</span>
                    </div>
                </div>
            </div>

            <!-- Right Image -->
            <div class="relative lg:order-last">
                <div class="aspect-square rounded-3xl overflow-hidden shadow-2xl img-placeholder">
                    <div class="w-full h-full flex flex-col items-center justify-center p-8">
                        <svg class="w-32 h-32 text-bluegray-300 mb-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                        </svg>
                        <p class="text-center">Hero obrázek: Prémiový kávový šálek s čerstvou kávou na moderním pozadí</p>
                    </div>
                </div>
                <!-- Floating Badge -->
                <div class="absolute -top-4 -right-4 bg-primary-500 text-white rounded-2xl shadow-xl p-6 transform rotate-6">
                    <div class="text-center">
                        <p class="text-3xl font-bold">1900+</p>
                        <p class="text-sm">spokojených zákazníků</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section bg-white">
    <div class="container-custom">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center group">
                <div class="w-20 h-20 bg-bluegray-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-primary-100 transition-colors">
                    <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-bold text-dark-800 mb-3">Čerstvě pražená</h3>
                <p class="text-dark-600 leading-relaxed">Kávu pražíme na objednávku, abyste dostali tu nejčerstvější kávu přímo k vám domů.</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-bluegray-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-primary-100 transition-colors">
                    <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-bold text-dark-800 mb-3">Doručení zdarma</h3>
                <p class="text-dark-600 leading-relaxed">Při objednávce nad 1000 Kč vám kávu dopravíme zcela zdarma přímo do vašich dveří.</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-bluegray-100 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-primary-100 transition-colors">
                    <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-bold text-dark-800 mb-3">Prémiová kvalita</h3>
                <p class="text-dark-600 leading-relaxed">Vybíráme jen nejkvalitnější kávu z ověřených plantáží po celém světě.</p>
            </div>
        </div>
    </div>
</section>

<!-- Subscription Configurator Section -->
<section class="section section-gray">
    <div class="container-custom">
        <div class="text-center mb-16">
            <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-dark-800 mb-4">
                Vyberte si své předplatné
            </h2>
            <p class="text-xl text-dark-600 max-w-2xl mx-auto">
                Nakonfigurujte si předplatné přesně podle vašich potřeb. Kdykoli můžete změnit, pozastavit nebo zrušit.
            </p>
        </div>

        <!-- Error Messages -->
        <?php if($errors->any()): ?>
        <div class="max-w-4xl mx-auto mb-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg">
                <p class="font-bold mb-2">⚠️ Chyba při zpracování konfigurace:</p>
                <ul class="list-disc list-inside space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="max-w-4xl mx-auto mb-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg">
                <p class="font-bold">⚠️ <?php echo e(session('error')); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Configurator Card -->
        <div class="max-w-4xl mx-auto" id="subscription-configurator" data-pricing='<?php echo json_encode($subscriptionPricing, 15, 512) ?>'>
            <div class="card p-8 md:p-12">
                <!-- Progress Indicator -->
                <div class="mb-12">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-2 step-indicator active" data-step="1">
                            <div class="step-number">1</div>
                            <span class="hidden md:inline text-sm font-medium">Množství</span>
                        </div>
                        <div class="flex-1 h-1 bg-bluegray-200 mx-2 step-line"></div>
                        <div class="flex items-center space-x-2 step-indicator" data-step="2">
                            <div class="step-number">2</div>
                            <span class="hidden md:inline text-sm font-medium">Typ kávy</span>
                        </div>
                        <div class="flex-1 h-1 bg-bluegray-200 mx-2 step-line"></div>
                        <div class="flex items-center space-x-2 step-indicator" data-step="3">
                            <div class="step-number">3</div>
                            <span class="hidden md:inline text-sm font-medium">Frekvence</span>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Množství kávy -->
                <div id="step-1" class="config-step active">
                    <h3 class="font-display text-2xl md:text-3xl font-bold text-dark-800 mb-3 text-center">
                        Kolik kávy denně pijete?
                    </h3>
                    <p class="text-dark-600 text-center mb-8">Pomozte nám určit ideální množství kávy pro vás</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto">
                        <button type="button" class="coffee-amount-option p-6 rounded-xl border-2 border-bluegray-200 hover:border-primary-500 transition-all text-center" data-amount="2" data-cups="1-2">
                            <div class="text-4xl mb-3">☕</div>
                            <div class="font-bold text-lg mb-1">1-2 šálky</div>
                            <div class="text-sm text-dark-600 mb-3">Pro příležitostné pití</div>
                            <div class="text-primary-500 font-bold">2 balení</div>
                        </button>
                        <button type="button" class="coffee-amount-option p-6 rounded-xl border-2 border-bluegray-200 hover:border-primary-500 transition-all text-center" data-amount="3" data-cups="3-4">
                            <div class="text-4xl mb-3">☕☕</div>
                            <div class="font-bold text-lg mb-1">3-4 šálky</div>
                            <div class="text-sm text-dark-600 mb-3">Pro pravidelné milovníky</div>
                            <div class="text-primary-500 font-bold">3 balení</div>
                        </button>
                        <button type="button" class="coffee-amount-option p-6 rounded-xl border-2 border-bluegray-200 hover:border-primary-500 transition-all text-center" data-amount="4" data-cups="5+">
                            <div class="text-4xl mb-3">☕☕☕</div>
                            <div class="font-bold text-lg mb-1">5+ šálků</div>
                            <div class="text-sm text-dark-600 mb-3">Pro kávové nadšence</div>
                            <div class="text-primary-500 font-bold">4 balení</div>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Typ kávy -->
                <div id="step-2" class="config-step">
                    <h3 class="font-display text-2xl md:text-3xl font-bold text-dark-800 mb-3 text-center">
                        Jaký typ kávy preferujete?
                    </h3>
                    <p class="text-dark-600 text-center mb-8">Vyberte si váš oblíbený způsob přípravy</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto mb-8">
                        <button type="button" class="coffee-type-option p-6 rounded-xl border-2 border-bluegray-200 hover:border-primary-500 transition-all text-center" data-type="espresso">
                            <div class="text-4xl mb-3">🎯</div>
                            <div class="font-bold text-lg mb-1">Espresso</div>
                            <div class="text-sm text-dark-600">Intenzivní a plné chutě</div>
                        </button>
                        <button type="button" class="coffee-type-option p-6 rounded-xl border-2 border-bluegray-200 hover:border-primary-500 transition-all text-center" data-type="filter">
                            <div class="text-4xl mb-3">💧</div>
                            <div class="font-bold text-lg mb-1">Filter</div>
                            <div class="text-sm text-dark-600">Jemné a čisté chutě</div>
                        </button>
                        <button type="button" class="coffee-type-option p-6 rounded-xl border-2 border-bluegray-200 hover:border-primary-500 transition-all text-center" data-type="mix">
                            <div class="text-4xl mb-3">🎨</div>
                            <div class="font-bold text-lg mb-1">Kombinace</div>
                            <div class="text-sm text-dark-600">Mix podle vašich preferencí</div>
                        </button>
                    </div>

                    <!-- Decaf Option (shown for espresso or filter) -->
                    <div id="decaf-option" class="hidden max-w-2xl mx-auto mb-8">
                        <div class="bg-primary-50 rounded-xl p-6 border-2 border-primary-200">
                            <label class="flex items-center justify-center cursor-pointer">
                                <input type="checkbox" id="decaf-checkbox" class="w-6 h-6 text-primary-500 border-2 border-primary-300 rounded focus:ring-2 focus:ring-primary-500 mr-3">
                                <div class="flex items-center">
                                    <span class="text-3xl mr-3">🌙</span>
                                    <div class="text-left">
                                        <div class="font-bold text-lg">Chci decaf variantu</div>
                                        <div class="text-sm text-dark-600">Bez kofeinu, plná chuť</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Decaf Slider (shown when checkbox is checked) -->
                        <div id="decaf-slider-container" class="hidden mt-6">
                            <div class="bg-bluegray-50 rounded-xl p-6 border-2 border-bluegray-200">
                                <h4 class="font-bold text-lg mb-4 text-center">Kolik decaf balení chcete?</h4>
                                <p class="text-sm text-dark-600 text-center mb-6">
                                    Zbytek balení bude běžná <span id="coffee-type-name">káva</span>
                                </p>
                                
                                <div class="space-y-3">
                                    <div>
                                        <div class="flex justify-between items-center mb-2">
                                            <label class="font-medium flex items-center">
                                                <span class="text-2xl mr-2" id="single-decaf-icon">🎯🌙</span>
                                                <span id="single-decaf-label">Espresso Decaf</span>
                                            </label>
                                            <span class="font-bold text-primary-500"><span id="single-decaf-count">0</span> balení</span>
                                        </div>
                                        <input type="range" id="single-decaf-slider" min="1" max="4" value="1" class="w-full h-2 bg-bluegray-200 rounded-lg appearance-none cursor-pointer slider">
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 border border-bluegray-200">
                                        <div class="flex justify-between items-center">
                                            <label class="font-medium flex items-center text-dark-600">
                                                <span class="text-2xl mr-2" id="single-normal-icon">🎯</span>
                                                <span id="single-normal-label">Espresso</span>
                                            </label>
                                            <span class="font-bold text-dark-800"><span id="single-normal-count">0</span> balení</span>
                                        </div>
                                        <p class="text-xs text-dark-500 mt-1 text-right">Automaticky dopočítáno</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mix Options (shown only when "mix" is selected) -->
                    <div id="mix-options" class="hidden max-w-2xl mx-auto">
                        <div class="bg-bluegray-50 rounded-xl p-6 border-2 border-bluegray-200">
                            <h4 class="font-bold text-xl mb-2 text-center">Jak chcete kombinovat?</h4>
                            
                            <!-- Progress indicator -->
                            <div class="bg-white rounded-lg p-4 mb-6 border-2 border-primary-200">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-dark-600 font-medium">Vybráno balení:</span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-3xl font-bold text-primary-500" id="total-bags">0</span>
                                        <span class="text-lg text-dark-600">/</span>
                                        <span class="text-2xl font-bold text-dark-800" id="required-bags-display">0</span>
                                    </div>
                                </div>
                                <div class="w-full bg-bluegray-200 rounded-full h-3 overflow-hidden">
                                    <div id="mix-progress-bar" class="bg-gradient-to-r from-primary-400 to-primary-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <div id="mix-status" class="text-center mt-3 font-semibold"></div>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="font-medium flex items-center">
                                            <span class="text-2xl mr-2">🎯</span>
                                            <span>Espresso</span>
                                        </label>
                                        <span class="font-bold text-primary-500"><span id="espresso-count">0</span> balení</span>
                                    </div>
                                    <input type="range" id="espresso-slider" min="0" max="4" value="0" class="w-full h-2 bg-bluegray-200 rounded-lg appearance-none cursor-pointer slider">
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="font-medium flex items-center">
                                            <span class="text-2xl mr-2">🎯🌙</span>
                                            <span>Espresso Decaf</span>
                                        </label>
                                        <span class="font-bold text-primary-500"><span id="espresso-decaf-count">0</span> balení</span>
                                    </div>
                                    <input type="range" id="espresso-decaf-slider" min="0" max="4" value="0" class="w-full h-2 bg-bluegray-200 rounded-lg appearance-none cursor-pointer slider">
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="font-medium flex items-center">
                                            <span class="text-2xl mr-2">💧</span>
                                            <span>Filter</span>
                                        </label>
                                        <span class="font-bold text-primary-500"><span id="filter-count">0</span> balení</span>
                                    </div>
                                    <input type="range" id="filter-slider" min="0" max="4" value="0" class="w-full h-2 bg-bluegray-200 rounded-lg appearance-none cursor-pointer slider">
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="font-medium flex items-center">
                                            <span class="text-2xl mr-2">💧🌙</span>
                                            <span>Filter Decaf</span>
                                        </label>
                                        <span class="font-bold text-primary-500"><span id="filter-decaf-count">0</span> balení</span>
                                    </div>
                                    <input type="range" id="filter-decaf-slider" min="0" max="4" value="0" class="w-full h-2 bg-bluegray-200 rounded-lg appearance-none cursor-pointer slider">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" id="back-to-step-1" class="btn btn-outline !px-6 !py-3">
                            ← Zpět
                        </button>
                        <button type="button" id="next-to-step-3" class="btn btn-primary !px-6 !py-3" disabled>
                            Pokračovat →
                        </button>
                    </div>
                </div>

                <!-- Step 3: Frekvence -->
                <div id="step-3" class="config-step">
                    <h3 class="font-display text-2xl md:text-3xl font-bold text-dark-800 mb-3 text-center">
                        Jak často chcete kávu dostávat?
                    </h3>
                    <p class="text-dark-600 text-center mb-8">Vyberte interval dodávky</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto mb-8">
                        <button type="button" class="frequency-option p-6 rounded-xl border-2 border-bluegray-200 hover:border-primary-500 transition-all text-center" data-frequency="1" data-frequency-text="Každý měsíc">
                            <div class="text-4xl mb-3">📦</div>
                            <div class="font-bold text-lg mb-1">Každý měsíc</div>
                            <div class="text-sm text-dark-600">Pro pravidelnou spotřebu</div>
                        </button>
                        <button type="button" class="frequency-option p-6 rounded-xl border-2 border-bluegray-200 hover:border-primary-500 transition-all text-center" data-frequency="2" data-frequency-text="Jednou za 2 měsíce">
                            <div class="text-4xl mb-3">📦📦</div>
                            <div class="font-bold text-lg mb-1">Jednou za 2 měsíce</div>
                            <div class="text-sm text-dark-600">Pro střední spotřebu</div>
                        </button>
                        <button type="button" class="frequency-option p-6 rounded-xl border-2 border-bluegray-200 hover:border-primary-500 transition-all text-center" data-frequency="3" data-frequency-text="Jednou za 3 měsíce">
                            <div class="text-4xl mb-3">📦📦📦</div>
                            <div class="font-bold text-lg mb-1">Jednou za 3 měsíce</div>
                            <div class="text-sm text-dark-600">Pro občasné pití</div>
                        </button>
                    </div>

                    <!-- Summary & Price -->
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-8 max-w-2xl mx-auto">
                        <h4 class="font-display text-2xl font-bold text-center mb-6">Shrnutí vašeho předplatného</h4>
                        
                        <div class="bg-white rounded-xl p-6 mb-6">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-dark-600">Množství:</span>
                                    <span class="font-bold" id="summary-amount">-</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-dark-600">Typ kávy:</span>
                                    <span class="font-bold" id="summary-type">-</span>
                                </div>
                                <div id="summary-mix" class="hidden pl-4 text-sm text-dark-600">
                                    <div id="summary-mix-details"></div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-dark-600">Frekvence:</span>
                                    <span class="font-bold" id="summary-frequency">-</span>
                                </div>
                                <div class="border-t pt-3 mt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold">Cena:</span>
                                        <span class="text-3xl font-bold text-primary-500" id="summary-price">-</span>
                                    </div>
                                    <p class="text-sm text-dark-600 text-right mt-1">při každé dodávce</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="button" id="back-to-step-2" class="btn btn-outline !px-6 !py-3 flex-1">
                                ← Zpět
                            </button>
                            <button type="button" id="configure-subscription" class="btn btn-primary !px-6 !py-3 flex-1 opacity-50 cursor-not-allowed" disabled>
                                Pokračovat k objednávce →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-12 text-center">
            <p class="text-dark-600 mb-4">Máte otázky? <a href="#" class="text-primary-500 hover:text-primary-600 font-semibold">Kontaktujte nás</a></p>
            <div class="flex flex-wrap justify-center items-center gap-4 md:gap-8 text-sm text-dark-600">
                <span class="flex items-center">✓ Bez závazků</span>
                <span class="flex items-center">✓ Kdykoli zrušte</span>
                <span class="flex items-center">✓ Doprava zdarma</span>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<?php if($featuredProducts->count() > 0): ?>
<section class="section bg-white">
    <div class="container-custom">
        <div class="text-center mb-16">
            <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-dark-800 mb-4">
                Naše nejoblíbenější kávy
            </h2>
            <p class="text-xl text-dark-600 max-w-2xl mx-auto">
                Objevte výběr našich nejlepších káv z prémiových plantáží
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('products.show', $product)); ?>" class="card card-hover group">
                <div class="aspect-square overflow-hidden img-placeholder">
                    <?php if($product->image): ?>
                    <img src="<?php echo e($product->image); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                    <div class="w-full h-full flex flex-col items-center justify-center p-8">
                        <svg class="w-20 h-20 text-bluegray-300 mb-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4z"/>
                        </svg>
                        <p class="text-center text-sm">Obrázek balení kávy: <?php echo e($product->name); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <div class="mb-2">
                        <span class="badge badge-primary text-xs"><?php echo e($product->category ?? 'Káva'); ?></span>
                    </div>
                    <h3 class="font-display text-xl font-bold text-dark-800 mb-2 group-hover:text-primary-500 transition-colors">
                        <?php echo e($product->name); ?>

                    </h3>
                    <?php if($product->short_description): ?>
                    <p class="text-dark-600 mb-4 line-clamp-2 text-sm"><?php echo e($product->short_description); ?></p>
                    <?php endif; ?>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-dark-800"><?php echo e(number_format($product->price, 0, ',', ' ')); ?> Kč</span>
                        <span class="badge badge-success text-xs">Skladem</span>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="text-center mt-12">
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline text-lg">Zobrazit všechny produkty</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- How It Works Section -->
<section class="section section-gray">
    <div class="container-custom">
        <div class="text-center mb-16">
            <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 mb-4">
                Jak to funguje?
            </h2>
            <p class="text-xl text-dark-600 max-w-2xl mx-auto">
                Tři jednoduché kroky k vaší dokonalé kávě
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
            <div class="text-center relative">
                <div class="w-16 h-16 bg-primary-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-bold shadow-lg">
                    1
                </div>
                <h3 class="font-display text-xl font-bold text-dark-800 mb-3">Vyberte balíček</h3>
                <p class="text-dark-600">Zvolte předplatné, které vám nejlépe vyhovuje. Můžete kdykoliv změnit nebo zrušit.</p>
            </div>

            <div class="text-center relative">
                <div class="w-16 h-16 bg-primary-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-bold shadow-lg">
                    2
                </div>
                <h3 class="font-display text-xl font-bold text-dark-800 mb-3">Dostanete kávu</h3>
                <p class="text-dark-600">Každý měsíc vám dodáme čerstvě praženou kávu přímo domů, zdarma.</p>
            </div>

            <div class="text-center relative">
                <div class="w-16 h-16 bg-primary-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl font-bold shadow-lg">
                    3
                </div>
                <h3 class="font-display text-xl font-bold text-dark-800 mb-3">Užívejte si</h3>
                <p class="text-dark-600">Vychutnejte si prémiovou kávu a objevujte nové chutě každý měsíc.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section bg-white">
    <div class="container-custom">
        <div class="text-center mb-16">
            <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 mb-4">
                Co říkají naši zákazníci
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <div class="card p-8">
                <div class="flex items-center mb-4">
                    <div class="flex text-primary-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                </div>
                <p class="text-dark-700 mb-6 leading-relaxed">"Nejlepší káva, kterou jsem kdy měl. Předplatné je skvělý nápad a ušetří mi čas!"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-bluegray-200 rounded-full flex items-center justify-center text-dark-600 font-semibold mr-3">
                        JN
                    </div>
                    <div>
                        <p class="font-semibold text-dark-800">Jan Novák</p>
                        <p class="text-sm text-dark-600">Praha</p>
                    </div>
                </div>
            </div>

            <div class="card p-8">
                <div class="flex items-center mb-4">
                    <div class="flex text-primary-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                </div>
                <p class="text-dark-700 mb-6 leading-relaxed">"Skvělá kvalita kávy a perfektní zákaznický servis. Vřele doporučuji!"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-bluegray-200 rounded-full flex items-center justify-center text-dark-600 font-semibold mr-3">
                        PS
                    </div>
                    <div>
                        <p class="font-semibold text-dark-800">Petra Svobodová</p>
                        <p class="text-sm text-dark-600">Brno</p>
                    </div>
                </div>
            </div>

            <div class="card p-8">
                <div class="flex items-center mb-4">
                    <div class="flex text-primary-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                </div>
                <p class="text-dark-700 mb-6 leading-relaxed">"Konečně káva, která má skutečnou chuť. Předplatné používám už půl roku!"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-bluegray-200 rounded-full flex items-center justify-center text-dark-600 font-semibold mr-3">
                        MK
                    </div>
                    <div>
                        <p class="font-semibold text-dark-800">Martin Král</p>
                        <p class="text-sm text-dark-600">Ostrava</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section bg-gradient-to-br from-primary-500 to-primary-700 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    <div class="relative container-custom text-center">
        <h2 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
            Připraveni na lepší kávu?
        </h2>
        <p class="text-xl md:text-2xl mb-10 max-w-2xl mx-auto opacity-95">
            Začněte své kávové předplatné ještě dnes a objevte svět prémiových káv.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('subscriptions.index')); ?>" class="btn btn-white text-lg">
                Začít nyní
            </a>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary text-lg">
                Prozkoumat obchod
            </a>
        </div>
        
        <!-- Stats -->
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto">
            <div>
                <p class="text-4xl font-bold mb-1">1900+</p>
                <p class="opacity-90">Spokojených zákazníků</p>
            </div>
            <div>
                <p class="text-4xl font-bold mb-1">100%</p>
                <p class="opacity-90">Prémiová kvalita</p>
            </div>
            <div>
                <p class="text-4xl font-bold mb-1">24/7</p>
                <p class="opacity-90">Zákaznická podpora</p>
            </div>
            <div>
                <p class="text-4xl font-bold mb-1">0 Kč</p>
                <p class="opacity-90">Doprava nad 1000 Kč</p>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/home.blade.php ENDPATH**/ ?>