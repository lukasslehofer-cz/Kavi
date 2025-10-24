<?php $__env->startSection('title', 'Kávové předplatné - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-dark-800 via-dark-700 to-dark-800 text-white py-24 md:py-32 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-block mb-6">
            <span class="badge bg-primary-500 text-white px-6 py-2 text-sm uppercase tracking-wide">Kávové předplatné</span>
        </div>
        <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
            Čerstvá káva<br/>každý měsíc
        </h1>
        <p class="text-xl md:text-2xl text-bluegray-100 max-w-3xl mx-auto leading-relaxed">
            Objevte svět prémiové kávy s našimi měsíčními balíčky. Čerstvě pražená káva přímo k vám domů, bez starostí.
        </p>
    </div>
</section>

<!-- Subscription Configurator Section -->
<section class="section bg-white">
    <div class="container-custom">
        <div class="text-center mb-16">
            <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 mb-4">
                Nakonfigurujte si předplatné
            </h2>
            <p class="text-xl text-dark-600 max-w-2xl mx-auto">
                Flexibilní předplatné bez závazků. Kdykoli můžete změnit, pozastavit nebo zrušit.
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

<!-- How it works -->
<section class="section section-gray">
    <div class="container-custom">
        <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 text-center mb-16">Jak to funguje?</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 max-w-6xl mx-auto">
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl font-bold shadow-lg">
                    1
                </div>
                <h3 class="font-display text-xl font-bold text-dark-800 mb-3">Vyberte balíček</h3>
                <p class="text-dark-600 leading-relaxed">Zvolte předplatné, které vám nejvíce vyhovuje podle vašich preferencí</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl font-bold shadow-lg">
                    2
                </div>
                <h3 class="font-display text-xl font-bold text-dark-800 mb-3">Pražíme na míru</h3>
                <p class="text-dark-600 leading-relaxed">Kávu pražíme čerstvě podle vašeho výběru a preferencí</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl font-bold shadow-lg">
                    3
                </div>
                <h3 class="font-display text-xl font-bold text-dark-800 mb-3">Doručíme domů</h3>
                <p class="text-dark-600 leading-relaxed">Každý měsíc dostanete čerstvou kávu zdarma až domů</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl font-bold shadow-lg">
                    4
                </div>
                <h3 class="font-display text-xl font-bold text-dark-800 mb-3">Vychutnáte si</h3>
                <p class="text-dark-600 leading-relaxed">Užijte si tu nejlepší kávu, kterou jste kdy měli</p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="section bg-white">
    <div class="container-custom">
        <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 text-center mb-16">Proč předplatné?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-bold text-dark-800 mb-3">Výhodná cena</h3>
                <p class="text-dark-600 leading-relaxed">Ušetřete až 20% oproti jednorázovým nákupům a získejte dopravu zdarma</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-bold text-dark-800 mb-3">Bez starosti</h3>
                <p class="text-dark-600 leading-relaxed">Nikdy vám nedojde káva díky pravidelným měsíčním dodávkám</p>
            </div>
            <div class="text-center">
                <div class="w-20 h-20 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-bold text-dark-800 mb-3">Flexibilní</h3>
                <p class="text-dark-600 leading-relaxed">Změňte, pozastavte nebo zrušte kdykoli bez jakýchkoliv poplatků</p>
            </div>
        </div>
    </div>
</section>

<!-- Visual Section with Placeholder -->
<section class="section section-gray">
    <div class="container-custom">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 mb-6">
                    Kvalita, které můžete věřit
                </h2>
                <p class="text-xl text-dark-600 mb-8 leading-relaxed">
                    Spolupracujeme s nejlepšími pražírnami v České republice. Každá káva je pečlivě vybrána 
                    a čerstvě pražena, aby vám přinesla ten nejlepší zážitek.
                </p>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-dark-700"><strong>100% Arabica</strong> z etických zdrojů</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-dark-700"><strong>Čerstvě pražená</strong> maximálně 7 dní před expedicí</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-dark-700"><strong>Ekologické balení</strong> s možností recyklace</span>
                    </li>
                </ul>
            </div>
            <div class="aspect-square rounded-3xl overflow-hidden shadow-2xl img-placeholder">
                <div class="w-full h-full flex flex-col items-center justify-center p-12">
                    <svg class="w-32 h-32 text-bluegray-300 mb-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                    <p class="text-center leading-relaxed">Lifestyle fotografie: Osoba vychutnává si kávu z předplatného v útulném prostředí</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section bg-gradient-to-br from-primary-500 to-primary-700 text-white">
    <div class="container-custom text-center">
        <h2 class="font-display text-4xl md:text-5xl font-bold mb-6">
            Začněte svou kávovou cestu ještě dnes
        </h2>
        <p class="text-xl mb-10 max-w-2xl mx-auto opacity-95">
            Připojte se k tisícům spokojených zákazníků a objevte svět prémiové kávy
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#plans" class="btn btn-white text-lg">
                Vybrat předplatné
            </a>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary text-lg">
                Procházet kávy
            </a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/subscriptions/index.blade.php ENDPATH**/ ?>