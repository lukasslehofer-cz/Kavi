<?php $__env->startSection('title', 'Kavi Coffee - Prémiová káva s předplatným'); ?>

<?php $__env->startSection('content'); ?>

<!-- Hero Section - Large Photo with Overlay -->
<section class="relative h-screen min-h-[600px] max-h-[900px] overflow-hidden">
    <!-- Hero Image Placeholder -->
    <div class="absolute inset-0 img-placeholder">
        <div class="absolute inset-0 bg-gradient-to-br from-dark-800 to-dark-900 flex items-center justify-center">
            <div class="text-center p-12">
                <svg class="w-32 h-32 text-bluegray-600 mx-auto mb-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                </svg>
                <p class="text-bluegray-400 text-xl font-black uppercase tracking-widest">HERO FOTO:<br/>Atmosférická fotka kávové kultury<br/> (barista, latte art, kávové zrna)</p>
            </div>
        </div>
    </div>
    
    <!-- Hero Content Overlay -->
    <div class="relative h-full flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-3xl">
                <div class="inline-block mb-6 bg-primary-500 px-8 py-4 shadow-2xl">
                    <span class="font-black uppercase tracking-widest text-white text-sm">Premium Coffee</span>
                </div>
                <h1 class="font-display text-6xl md:text-7xl lg:text-9xl font-black text-white mb-8 leading-none uppercase">
                    PRÉMIOVÁ<br/>
                    <span class="text-primary-500">KÁVA</span><br/>
                    PRO VÁS
                </h1>
                <p class="text-2xl md:text-3xl text-white mb-12 font-bold uppercase tracking-widest">
                    Čerstvě pražená • Měsíční předplatné
                </p>
                <div class="flex flex-col sm:flex-row gap-6">
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="btn btn-primary text-lg shadow-2xl">
                        Začít nyní →
                    </a>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-white text-lg shadow-2xl">
                        Prozkoumat kávy
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</section>

<!-- Stats Bar -->
<section class="bg-primary-500 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
            <div>
                <p class="text-5xl font-black mb-2">1900+</p>
                <p class="text-sm font-bold uppercase tracking-widest">Zákazníků</p>
            </div>
            <div>
                <p class="text-5xl font-black mb-2">100%</p>
                <p class="text-sm font-bold uppercase tracking-widest">Kvalita</p>
            </div>
            <div>
                <p class="text-5xl font-black mb-2">24/7</p>
                <p class="text-sm font-bold uppercase tracking-widest">Podpora</p>
            </div>
            <div>
                <p class="text-5xl font-black mb-2">0 Kč</p>
                <p class="text-sm font-bold uppercase tracking-widest">Doprava</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section - Clean Blocks -->
<section class="section bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 flex items-center justify-center mx-auto mb-8 shadow-xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-3xl font-black mb-4 uppercase tracking-wide">Čerstvost</h3>
                <p class="text-dark-600 font-bold text-lg">Praženo na objednávku, maxim álně 7 dní před expedicí</p>
            </div>
            
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 flex items-center justify-center mx-auto mb-8 shadow-xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <h3 class="font-display text-3xl font-black mb-4 uppercase tracking-wide">Doprava</h3>
                <p class="text-dark-600 font-bold text-lg">Zdarma při objednávce nad 1000 Kč přímo do vašich dveří</p>
            </div>
            
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 flex items-center justify-center mx-auto mb-8 shadow-xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-3xl font-black mb-4 uppercase tracking-wide">Kvalita</h3>
                <p class="text-dark-600 font-bold text-lg">100% Arabica z ověřených plantáží po celém světě</p>
            </div>
        </div>
    </div>
</section>

<!-- Photo Section with Split -->
<section class="py-0">
    <div class="grid grid-cols-1 lg:grid-cols-2">
        <!-- Photo -->
        <div class="h-[500px] lg:h-[700px] img-placeholder">
            <div class="w-full h-full bg-gradient-to-br from-bluegray-300 to-bluegray-400 flex items-center justify-center p-12">
                <div class="text-center">
                    <svg class="w-32 h-32 text-dark-800 mx-auto mb-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4z"/>
                    </svg>
                    <p class="font-black uppercase text-2xl text-dark-800 tracking-widest">FOTO:<br/>Detail balení kávy<br/>s kávovými zrny</p>
                </div>
            </div>
        </div>
        <!-- Content -->
        <div class="bg-bluegray-50 p-12 lg:p-20 flex items-center">
            <div>
                <h2 class="font-display text-5xl md:text-6xl font-black uppercase text-dark-800 mb-8 leading-tight">
                    Káva,<br/>kterou<br/>budete<br/>milovat
                </h2>
                <p class="text-xl text-dark-700 font-bold mb-8 leading-relaxed">
                    Pečlivě vybíráme nejkvalitnější kávu z malých pražíren. Každý měsíc nové chutě přímo k vám domů.
                </p>
                <a href="<?php echo e(route('subscriptions.index')); ?>" class="btn btn-primary text-lg">Zjistit více →</a>
            </div>
        </div>
    </div>
</section>

<!-- Subscription Plans Section -->
<section class="section bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <div class="inline-block bg-primary-500 px-8 py-4 shadow-xl mb-8">
                <span class="font-black uppercase tracking-widest text-white">Předplatné</span>
            </div>
            <h2 class="font-display text-6xl md:text-7xl font-black uppercase text-dark-800 mb-6 leading-none">
                Vyberte si<br/>svůj plán
            </h2>
            <p class="text-2xl text-dark-600 font-bold uppercase tracking-widest">
                Bez závazků • Kdykoli zrušte
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            <?php $__currentLoopData = $subscriptionPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card card-hover <?php echo e($plan->is_popular ?? false ? 'shadow-2xl' : 'shadow-lg'); ?>">
                <?php if($plan->is_popular ?? false): ?>
                <div class="bg-primary-500 text-white text-center py-4">
                    <span class="font-black uppercase tracking-widest text-sm">Nejoblíbenější</span>
                </div>
                <?php endif; ?>
                
                <div class="p-12">
                    <h3 class="font-display text-3xl font-black uppercase text-dark-800 mb-6"><?php echo e($plan->name); ?></h3>
                    <div class="mb-8">
                        <span class="text-6xl font-black text-primary-500"><?php echo e(number_format($plan->price, 0, ',', ' ')); ?></span>
                        <span class="text-xl font-bold text-dark-600"> Kč/měs</span>
                    </div>
                    <p class="font-bold text-lg mb-10 uppercase tracking-wide text-dark-700"><?php echo e($plan->description); ?></p>
                    
                    <?php if($plan->features): ?>
                    <ul class="space-y-4 mb-12">
                        <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-start">
                            <div class="w-6 h-6 bg-primary-500 flex-shrink-0 mr-4 mt-1"></div>
                            <span class="font-bold text-dark-700"><?php echo e($feature); ?></span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('subscriptions.show', $plan)); ?>" class="btn <?php echo e($plan->is_popular ?? false ? 'btn-primary' : 'btn-secondary'); ?> w-full">
                        Vybrat →
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<!-- Large Photo Section -->
<section class="py-0">
    <div class="h-[600px] lg:h-[800px] relative img-placeholder">
        <div class="absolute inset-0 bg-gradient-to-r from-dark-900 via-dark-800 to-dark-900 flex items-center justify-center">
            <div class="text-center p-12">
                <svg class="w-40 h-40 text-bluegray-700 mx-auto mb-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                </svg>
                <p class="text-bluegray-500 text-3xl font-black uppercase tracking-widest">VELKOPLOŠNÁ FOTO:<br/>Lifestyle záběr s kávou<br/>(člověk s kávou, kavárna, interiér)</p>
            </div>
        </div>
        <div class="absolute inset-0 flex items-center justify-center text-white text-center">
            <div class="max-w-4xl px-4">
                <h2 class="font-display text-5xl md:text-7xl font-black uppercase mb-8 leading-tight">
                    Objevte svět<br/>prémiové kávy
                </h2>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-white text-lg shadow-2xl">Prozkoumat kávy →</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<?php if($featuredProducts->count() > 0): ?>
<section class="section bg-bluegray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-20">
            <div class="inline-block bg-primary-500 px-8 py-4 shadow-xl mb-8">
                <span class="font-black uppercase tracking-widest text-white">Naše kávy</span>
            </div>
            <h2 class="font-display text-6xl md:text-7xl font-black uppercase text-dark-800 leading-none">
                Premium<br/>selection
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
            <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('products.show', $product)); ?>" class="card card-hover group">
                <div class="aspect-square overflow-hidden img-placeholder">
                    <?php if($product->image): ?>
                    <img src="<?php echo e($product->image); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-bluegray-200 to-bluegray-300 flex items-center justify-center p-12">
                        <div class="text-center">
                            <svg class="w-24 h-24 text-dark-800 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4z"/>
                            </svg>
                            <p class="font-black uppercase text-sm text-dark-800">FOTO:<br/><?php echo e($product->name); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="p-8">
                    <div class="mb-3">
                        <span class="badge badge-primary text-xs"><?php echo e($product->category ?? 'KÁVA'); ?></span>
                    </div>
                    <h3 class="font-display text-2xl font-black uppercase mb-3 group-hover:text-primary-500 transition-colors">
                        <?php echo e($product->name); ?>

                    </h3>
                    <?php if($product->short_description): ?>
                    <p class="font-bold text-sm mb-6 line-clamp-2 text-dark-600"><?php echo e($product->short_description); ?></p>
                    <?php endif; ?>
                    <div class="flex items-center justify-between">
                        <span class="text-4xl font-black text-dark-800"><?php echo e(number_format($product->price, 0, ',', ' ')); ?></span>
                        <span class="badge badge-success text-xs">SKLADEM</span>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="text-center mt-16">
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary text-lg">Všechny produkty →</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section - Clean -->
<section class="section bg-primary-500 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-6xl md:text-8xl font-black uppercase mb-10 leading-none">
            Začněte<br/>dnes
        </h2>
        <p class="text-3xl mb-16 font-bold uppercase tracking-widest">
            Objevte prémiovou kávu • Bez závazků
        </p>
        <div class="flex flex-col sm:flex-row gap-8 justify-center">
            <a href="<?php echo e(route('subscriptions.index')); ?>" class="btn bg-dark-800 text-white hover:bg-dark-900 text-xl shadow-2xl">
                Vybrat předplatné →
            </a>
            <a href="<?php echo e(route('products.index')); ?>" class="btn bg-white text-dark-800 hover:bg-bluegray-50 text-xl shadow-2xl">
                Procházet kávy
            </a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/home.blade.php ENDPATH**/ ?>