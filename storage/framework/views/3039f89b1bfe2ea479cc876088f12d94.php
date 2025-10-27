<?php $__env->startSection('title', 'Kávové předplatné - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Header Section -->
<div class="relative bg-gradient-to-br from-gray-50 via-slate-50 to-gray-100 py-16 md:py-20 overflow-hidden">
  <!-- Background Decoration -->
  <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-br from-primary-300/10 to-pink-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-[36rem] h-[36rem] bg-gradient-to-tr from-primary-300/10 to-pink-400/10 rounded-full blur-3xl"></div>
    </div>
  
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="text-center max-w-4xl mx-auto">
      <!-- Badge -->
      <div class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-100 to-pink-100 rounded-full px-4 py-2 mb-6">
        <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        <span class="text-sm font-bold text-primary-700">Konfigurátor předplatného</span>
      </div>

      <!-- Main Heading -->
      <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-black text-gray-900">
        Sestavte si své <span class="bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">kávové předplatné</span>
      </h1>
      
      <p class="mx-auto max-w-2xl text-lg md:text-xl text-gray-600 mb-8">
        Vyberte si množství, typ kávy a frekvenci dodání. <span class="font-semibold text-gray-900">Jednoduše a bez závazků.</span>
      </p>

      <!-- Features Pills -->
      <div class="flex flex-wrap items-center justify-center gap-4 mb-8">
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-md border border-gray-200">
          <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span class="text-sm font-semibold text-gray-700">Doprava zdarma</span>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-md border border-gray-200">
          <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="text-sm font-semibold text-gray-700">Flexibilní platba</span>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-md border border-gray-200">
          <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          <span class="text-sm font-semibold text-gray-700">Zrušení kdykoliv</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Content Container -->
<div class="bg-white py-12 sm:py-16 lg:py-20">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">

    <!-- Error Messages -->
    <?php if($errors->any()): ?>
    <div class="max-w-4xl mx-auto mb-6">
      <div class="bg-red-100 border-2 border-red-400 text-red-700 px-6 py-4 rounded-lg">
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
      <div class="bg-red-100 border-2 border-red-400 text-red-700 px-6 py-4 rounded-lg">
        <p class="font-bold">⚠️ <?php echo e(session('error')); ?></p>
      </div>
    </div>
    <?php endif; ?>

      <!-- Jednoduchý formulář -->
    <form method="POST" action="<?php echo e(route('subscriptions.configure.checkout')); ?>" class="max-w-5xl mx-auto">
        <?php echo csrf_field(); ?>
        
        <!-- Hidden inputy pro mix rozdělení - budou aktualizované JS -->
        <input type="hidden" name="mix[espresso]" id="mix-espresso-value" value="0">
        <input type="hidden" name="mix[filter]" id="mix-filter-value" value="0">
        
        <!-- Step 1 Header -->
        <div class="relative mb-12 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-slate-50 to-gray-100 rounded-3xl"></div>
          <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary-200/20 to-pink-300/20 rounded-full blur-3xl"></div>
          
          <div class="relative flex flex-col items-center justify-center gap-4 rounded-3xl border-2 border-gray-200 p-8 md:p-10 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-pink-600 text-white text-2xl font-bold shadow-lg">
              1
            </div>
            <div>
              <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-2">Množství kávy</h2>
              <p class="text-lg text-gray-600 max-w-2xl">Vyberte balíček, který vám vyhovuje</p>
            </div>
          </div>
        </div>

        <div class="mb-12">
            <!-- Množství kávy -->
            <div class="mb-8">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- 500g plán -->
                    <label class="group flex flex-col rounded-2xl border-2 border-gray-200 p-6 pt-8 cursor-pointer transition-all duration-300 bg-white shadow-xl hover:shadow-2xl hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:scale-105 has-[:checked]:shadow-2xl transform hover:-translate-y-1">
                        <input type="radio" name="amount" value="2" class="hidden" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">500g</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Ideální pro jednotlivce nebo páry</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">2 balíčky po 250g</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Doprava zdarma</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Zrušení kdykoliv</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <div class="flex items-end justify-center gap-1">
                                <span class="text-4xl font-bold text-gray-800"><?php echo e(number_format($subscriptionPricing['2'], 0, ',', ' ')); ?>,-</span>
                                <span class="text-gray-500">Kč/box</span>
                            </div>
                            <span class="block rounded-xl bg-gray-700 hover:bg-gradient-to-r hover:from-primary-500 hover:to-pink-600 group-has-[:checked]:bg-gradient-to-r group-has-[:checked]:from-primary-500 group-has-[:checked]:to-pink-600 px-8 py-3 text-center text-sm font-bold text-white transition duration-200 shadow-lg group-has-[:checked]:shadow-xl group-has-[:checked]:scale-105 md:text-base">Vybrat plán</span>
                        </div>
                    </label>
                    
                    <!-- 750g plán -->
                    <label class="group flex flex-col rounded-2xl border-2 border-gray-200 p-6 pt-8 cursor-pointer transition-all duration-300 bg-white shadow-xl hover:shadow-2xl hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:scale-105 has-[:checked]:shadow-2xl transform hover:-translate-y-1">
                        <input type="radio" name="amount" value="3" class="hidden" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">750g</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Nejpopulárnější volba</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">3 balíčky po 250g</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Doprava zdarma</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Prioritní podpora</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <div class="flex items-end justify-center gap-1">
                                <span class="text-4xl font-bold text-gray-800"><?php echo e(number_format($subscriptionPricing['3'], 0, ',', ' ')); ?>,-</span>
                                <span class="text-gray-500">Kč/box</span>
                            </div>
                            <span class="block rounded-xl bg-gray-700 hover:bg-gradient-to-r hover:from-primary-500 hover:to-pink-600 group-has-[:checked]:bg-gradient-to-r group-has-[:checked]:from-primary-500 group-has-[:checked]:to-pink-600 px-8 py-3 text-center text-sm font-bold text-white transition duration-200 shadow-lg group-has-[:checked]:shadow-xl group-has-[:checked]:scale-105 md:text-base">Vybrat plán</span>
                        </div>
                    </label>

                    <!-- 1000g plán -->
                    <label class="group flex flex-col rounded-2xl border-2 border-gray-200 p-6 pt-8 cursor-pointer transition-all duration-300 bg-white shadow-xl hover:shadow-2xl hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:scale-105 has-[:checked]:shadow-2xl transform hover:-translate-y-1">
                        <input type="radio" name="amount" value="4" class="hidden" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">1000g</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Pro kávové nadšence</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">4 balíčky po 250g</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Doprava zdarma</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">VIP podpora</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <div class="flex items-end justify-center gap-1">
                                <span class="text-4xl font-bold text-gray-800"><?php echo e(number_format($subscriptionPricing['4'], 0, ',', ' ')); ?>,-</span>
                                <span class="text-gray-500">Kč/box</span>
                            </div>
                            <span class="block rounded-xl bg-gray-700 hover:bg-gradient-to-r hover:from-primary-500 hover:to-pink-600 group-has-[:checked]:bg-gradient-to-r group-has-[:checked]:from-primary-500 group-has-[:checked]:to-pink-600 px-8 py-3 text-center text-sm font-bold text-white transition duration-200 shadow-lg group-has-[:checked]:shadow-xl group-has-[:checked]:scale-105 md:text-base">Vybrat plán</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Step 2 Header -->
        <div class="relative mb-12 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-slate-50 to-gray-100 rounded-3xl"></div>
          <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-200/20 to-indigo-300/20 rounded-full blur-3xl"></div>
          
          <div class="relative flex flex-col items-center justify-center gap-4 rounded-3xl border-2 border-gray-200 p-8 md:p-10 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-2xl font-bold shadow-lg">
              2
            </div>
            <div>
              <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-2">Preferovaný typ kávy</h2>
              <p class="text-lg text-gray-600 max-w-2xl">Vyberte si váš oblíbený způsob přípravy</p>
            </div>
          </div>
        </div>

        <div class="mb-12">
            <!-- Typ kávy -->
            <div class="mb-8">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Espresso -->
                    <label class="group flex flex-col rounded-2xl border-2 border-gray-200 p-6 pt-8 cursor-pointer transition-all duration-300 bg-white shadow-xl hover:shadow-2xl hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:scale-105 has-[:checked]:shadow-2xl transform hover:-translate-y-1">
                        <input type="radio" name="type" value="espresso" class="hidden" required>
                        <div class="mb-12">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800">Espresso</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Ideální pro přípravu v kávovaru nebo moka konvici</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Plné tělo a intenzita</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Sladká chuť</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Tmavší pražení</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <!-- Decaf checkbox pro Espresso -->
                            <label class="flex items-center gap-3 px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors border border-gray-300" onclick="event.stopPropagation();">
                                <input type="checkbox" name="isDecaf" value="1" class="w-5 h-5 text-primary-500 rounded">
                                <span class="text-sm font-semibold text-gray-700">1x káva bez kofeinu</span>
                                <span class="ml-auto text-sm font-semibold text-gray-700">+ 100,-</span>
                            </label>
                            <span class="block rounded-xl bg-gray-700 hover:bg-gradient-to-r hover:from-primary-500 hover:to-pink-600 group-has-[:checked]:bg-gradient-to-r group-has-[:checked]:from-primary-500 group-has-[:checked]:to-pink-600 px-8 py-3 text-center text-sm font-bold text-white transition duration-200 shadow-lg group-has-[:checked]:shadow-xl group-has-[:checked]:scale-105 md:text-base">Vybrat metodu</span>
                        </div>
                    </label>

                    <!-- Filtr -->
                    <label class="group flex flex-col rounded-2xl border-2 border-gray-200 p-6 pt-8 cursor-pointer transition-all duration-300 bg-white shadow-xl hover:shadow-2xl hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:scale-105 has-[:checked]:shadow-2xl transform hover:-translate-y-1">
                        <input type="radio" name="type" value="filter" class="hidden" required>
                        <div class="mb-12">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800">Filtr</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Pro překapávanou kávu nebo french press</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Lehčí tělo, vyšší kyselost</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Ovocné tóny</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Světlejší pražení</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <!-- Decaf checkbox pro Filtr -->
                            <label class="flex items-center gap-3 px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors border border-gray-300" onclick="event.stopPropagation();">
                                <input type="checkbox" name="isDecaf" value="1" class="w-5 h-5 text-primary-500 rounded">
                                <span class="text-sm font-semibold text-gray-700">1x káva bez kofeinu</span>
                                <span class="ml-auto text-sm font-semibold text-gray-700">+ 100,-</span>
                            </label>
                            <span class="block rounded-xl bg-gray-700 hover:bg-gradient-to-r hover:from-primary-500 hover:to-pink-600 group-has-[:checked]:bg-gradient-to-r group-has-[:checked]:from-primary-500 group-has-[:checked]:to-pink-600 px-8 py-3 text-center text-sm font-bold text-white transition duration-200 shadow-lg group-has-[:checked]:shadow-xl group-has-[:checked]:scale-105 md:text-base">Vybrat metodu</span>
                        </div>
                    </label>

                    <!-- Kombinace -->
                    <label class="group flex flex-col rounded-2xl border-2 border-gray-200 p-6 pt-8 cursor-pointer transition-all duration-300 bg-white shadow-xl hover:shadow-2xl hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:scale-105 has-[:checked]:shadow-2xl transform hover:-translate-y-1">
                        <input type="radio" name="type" value="mix" class="hidden" required>
                        <div class="mb-12">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800">Kombinace</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Rozmanitost pro ty, kteří chtějí vyzkoušet obojí</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Espresso i filtr</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Možnost decaf variant</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Maximální rozmanitost</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <!-- Decaf checkbox pro Mix -->
                            <label class="flex items-center gap-3 px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors border border-gray-300" onclick="event.stopPropagation();">
                                <input type="checkbox" name="isDecaf" value="1" class="w-5 h-5 text-primary-500 rounded">
                                <span class="text-sm font-semibold text-gray-700">1x káva bez kofeinu</span>
                                <span class="ml-auto text-sm font-semibold text-gray-700">+ 100,-</span>
                            </label>
                            <span class="block rounded-xl bg-gray-700 hover:bg-gradient-to-r hover:from-primary-500 hover:to-pink-600 group-has-[:checked]:bg-gradient-to-r group-has-[:checked]:from-primary-500 group-has-[:checked]:to-pink-600 px-8 py-3 text-center text-sm font-bold text-white transition duration-200 shadow-lg group-has-[:checked]:shadow-xl group-has-[:checked]:scale-105 md:text-base">Vybrat metodu</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
                          
        <!-- Rozdělení kávy -->
        <div id="caffeine-distribution" class="hidden mb-8">
          <div class="bg-gray-50 p-6 rounded-lg border">
            <h4 class="text-3xl text-center font-bold text-gray-800 mb-4">Rozdělení kávy</h4>
            
            <!-- Layout pro Espresso/Filtr s decaf: 2 sloupce vedle sebe -->
            <div id="simple-distribution" class="hidden grid grid-cols-2 gap-4">
              <!-- Kofeinová verze -->
              <div class="flex flex-col p-4 bg-white rounded-lg border">
                <div class="mb-3">
                  <span class="font-semibold text-gray-800 block mb-1" id="caffeine-label">Espresso</span>
                  <p class="text-xs text-gray-500">250g balení</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mt-auto">
                  <button type="button" id="caffeine-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                  </button>
                  <span class="w-12 text-center text-2xl font-bold text-gray-800" id="caffeine-count">0</span>
                  <button type="button" id="caffeine-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                  </button>
                </div>
              </div>
              
              <!-- Bezkofeinová verze -->
              <div class="flex flex-col p-4 bg-white rounded-lg border">
                <div class="mb-3">
                  <span class="font-semibold text-gray-800 block mb-1" id="decaf-label">Decaf espresso</span>
                  <p class="text-xs text-gray-500">250g balení</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mt-auto">
                  <button type="button" id="decaf-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                  </button>
                  <span class="w-12 text-center text-2xl font-bold text-gray-800" id="decaf-count">0</span>
                  <button type="button" id="decaf-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Layout pro Kombinaci BEZ decaf: 2 sloupce (Espresso, Filtr) -->
            <div id="mix-no-decaf-distribution" class="hidden grid grid-cols-2 gap-4">
              <!-- Espresso -->
              <div class="flex flex-col p-4 bg-white rounded-lg border">
                <div class="mb-3">
                  <span class="font-semibold text-gray-800 block mb-1">Espresso</span>
                  <p class="text-xs text-gray-500">250g balení</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mt-auto">
                  <button type="button" id="mix-espresso-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                  </button>
                  <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-espresso-count">0</span>
                  <button type="button" id="mix-espresso-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                  </button>
                </div>
              </div>
              
              <!-- Filtr -->
              <div class="flex flex-col p-4 bg-white rounded-lg border">
                <div class="mb-3">
                  <span class="font-semibold text-gray-800 block mb-1">Filtr</span>
                  <p class="text-xs text-gray-500">250g balení</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mt-auto">
                  <button type="button" id="mix-filter-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                  </button>
                  <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-filter-count">0</span>
                  <button type="button" id="mix-filter-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Layout pro Kombinaci S decaf: 2 řádky po 2 sloupcích -->
            <div id="mix-with-decaf-distribution" class="hidden space-y-4">
              <!-- První řádek: Espresso + Decaf espresso -->
              <div class="grid grid-cols-2 gap-4">
                <!-- Espresso -->
                <div class="flex flex-col p-4 bg-white rounded-lg border">
                  <div class="mb-3">
                    <span class="font-semibold text-gray-800 block mb-1">Espresso</span>
                    <p class="text-xs text-gray-500">250g balení</p>
                  </div>
                  
                  <div class="flex items-center justify-center gap-3 mt-auto">
                    <button type="button" id="mix-espresso-caf-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-espresso-caf-count">0</span>
                    <button type="button" id="mix-espresso-caf-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
                
                <!-- Decaf espresso -->
                <div class="flex flex-col p-4 bg-white rounded-lg border">
                  <div class="mb-3">
                    <span class="font-semibold text-gray-800 block mb-1">Decaf espresso</span>
                    <p class="text-xs text-gray-500">250g balení</p>
                  </div>
                  
                  <div class="flex items-center justify-center gap-3 mt-auto">
                    <button type="button" id="mix-espresso-decaf-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-espresso-decaf-count">0</span>
                    <button type="button" id="mix-espresso-decaf-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Druhý řádek: Filtr + Decaf filtr -->
              <div class="grid grid-cols-2 gap-4">
                <!-- Filtr -->
                <div class="flex flex-col p-4 bg-white rounded-lg border">
                  <div class="mb-3">
                    <span class="font-semibold text-gray-800 block mb-1">Filtr</span>
                    <p class="text-xs text-gray-500">250g balení</p>
                  </div>
                  
                  <div class="flex items-center justify-center gap-3 mt-auto">
                    <button type="button" id="mix-filter-caf-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-filter-caf-count">0</span>
                    <button type="button" id="mix-filter-caf-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
                
                <!-- Decaf filtr -->
                <div class="flex flex-col p-4 bg-white rounded-lg border">
                  <div class="mb-3">
                    <span class="font-semibold text-gray-800 block mb-1">Decaf filtr</span>
                    <p class="text-xs text-gray-500">250g balení</p>
                  </div>
                  
                  <div class="flex items-center justify-center gap-3 mt-auto">
                    <button type="button" id="mix-filter-decaf-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-filter-decaf-count">0</span>
                    <button type="button" id="mix-filter-decaf-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Step 3 Header -->
        <div class="relative mb-12 overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-br from-slate-50 to-gray-100 rounded-3xl"></div>
          <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-green-200/20 to-emerald-300/20 rounded-full blur-3xl"></div>
          
          <div class="relative flex flex-col items-center justify-center gap-4 rounded-3xl border-2 border-gray-200 p-8 md:p-10 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 text-white text-2xl font-bold shadow-lg">
              3
            </div>
            <div>
              <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-2">Frekvence dodání</h2>
              <p class="text-lg text-gray-600 max-w-2xl">Jak často chcete kávu dostávat?</p>
            </div>
          </div>
        </div>

        <div class="mb-12">
            <!-- Frekvence -->
            <div class="mb-8">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Každý měsíc -->
                    <label class="group flex flex-col rounded-2xl border-2 border-gray-200 p-6 pt-8 cursor-pointer transition-all duration-300 bg-white shadow-xl hover:shadow-2xl hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:scale-105 has-[:checked]:shadow-2xl transform hover:-translate-y-1">
                        <input type="radio" name="frequency" value="1" class="hidden" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800 pt-6">Každý měsíc</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Pro pravidelnou spotřebu a čerstvou kávu</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Vždy čerstvá káva</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Pro pravidelné pití</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Nejpopulárnější volba</span></div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <span class="block rounded-xl bg-gray-700 hover:bg-gradient-to-r hover:from-primary-500 hover:to-pink-600 group-has-[:checked]:bg-gradient-to-r group-has-[:checked]:from-primary-500 group-has-[:checked]:to-pink-600 px-8 py-3 text-center text-sm font-bold text-white transition duration-200 shadow-lg group-has-[:checked]:shadow-xl group-has-[:checked]:scale-105 md:text-base">Vybrat frekvenci</span>
                        </div>
                    </label>

                    <!-- Jednou za 2 měsíce -->
                    <label class="group flex flex-col rounded-2xl border-2 border-gray-200 p-6 pt-8 cursor-pointer transition-all duration-300 bg-white shadow-xl hover:shadow-2xl hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:scale-105 has-[:checked]:shadow-2xl transform hover:-translate-y-1">
                        <input type="radio" name="frequency" value="2" class="hidden" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800 pt-6">Jednou za 2 měsíce</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Pro střední spotřebu</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Úspora peněz</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Flexibilní řešení</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Pro menší domácnosti</span></div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <span class="block rounded-xl bg-gray-700 hover:bg-gradient-to-r hover:from-primary-500 hover:to-pink-600 group-has-[:checked]:bg-gradient-to-r group-has-[:checked]:from-primary-500 group-has-[:checked]:to-pink-600 px-8 py-3 text-center text-sm font-bold text-white transition duration-200 shadow-lg group-has-[:checked]:shadow-xl group-has-[:checked]:scale-105 md:text-base">Vybrat frekvenci</span>
                        </div>
                    </label>

                    <!-- Jednou za 3 měsíce -->
                    <label class="group flex flex-col rounded-2xl border-2 border-gray-200 p-6 pt-8 cursor-pointer transition-all duration-300 bg-white shadow-xl hover:shadow-2xl hover:border-primary-300 has-[:checked]:border-primary-500 has-[:checked]:scale-105 has-[:checked]:shadow-2xl transform hover:-translate-y-1">
                        <input type="radio" name="frequency" value="3" class="hidden" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800 pt-6">Jednou za 3 měsíce</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Pro občasné pití</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Maximální úspora</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Pro příležitostné pití</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Ideální na zkoušku</span></div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <span class="block rounded-xl bg-gray-700 hover:bg-gradient-to-r hover:from-primary-500 hover:to-pink-600 group-has-[:checked]:bg-gradient-to-r group-has-[:checked]:from-primary-500 group-has-[:checked]:to-pink-600 px-8 py-3 text-center text-sm font-bold text-white transition duration-200 shadow-lg group-has-[:checked]:shadow-xl group-has-[:checked]:scale-105 md:text-base">Vybrat frekvenci</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

            <!-- Souhrn předplatného -->
            <div class="mt-16">
                <div class="relative overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 to-gray-100 rounded-3xl"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary-200/20 to-pink-300/20 rounded-full blur-3xl"></div>
                    
                    <div class="relative flex flex-col overflow-hidden rounded-3xl bg-white sm:flex-row md:min-h-80 border-2 border-gray-200 shadow-2xl">
                        <!-- image -->
                        <div class="order-first h-64 w-full bg-gray-300 sm:order-none sm:h-auto sm:w-1/2 lg:w-2/5 relative group">
                            <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&q=75&fit=crop&w=1000" loading="lazy" alt="Kávový box" class="h-full w-full object-cover object-center transform group-hover:scale-110 transition-transform duration-700" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                        </div>
                        <!-- content -->
                        <div class="flex w-full flex-col p-6 sm:w-1/2 sm:p-8 lg:w-3/5 lg:p-10">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-pink-600 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl md:text-3xl font-black text-gray-900">Shrnutí předplatného</h2>
                            </div>

                        <div class="mb-6 space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-300">
                                <span class="text-gray-600 font-semibold">Množství:</span>
                                <span class="font-bold text-gray-800" id="summary-amount">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-300">
                                <span class="text-gray-600 font-semibold">Typ kávy:</span>
                                <span class="font-bold text-gray-800" id="summary-type">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-300">
                                <span class="text-gray-600 font-semibold">Frekvence:</span>
                                <span class="font-bold text-gray-800" id="summary-frequency">-</span>
                            </div>
                            <div class="flex justify-between items-center py-4 mt-4 border-t-2 border-gray-200">
                                <span class="text-xl font-bold text-gray-800">Celková cena:</span>
                                <div class="text-right flex flex-col items-end">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-5xl font-bold bg-gradient-to-r from-primary-500 to-pink-600 bg-clip-text text-transparent" id="summary-price">-</span>
                                        <span class="text-xl text-gray-700 font-bold">Kč</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">při každé dodávce</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <button type="submit" id="submit-button" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 px-8 py-4 text-center text-base font-bold text-white outline-none ring-primary-300 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 md:text-lg opacity-50 cursor-not-allowed" disabled>
                                <span>Pokračovat k objednávce</span>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </button>
                            <div class="flex items-center justify-center gap-4 mt-4 text-xs text-gray-600">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Bez závazků
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Kdykoli zrušte
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Doprava zdarma
                                </span>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
    </div>
    </form>

<!-- Shipping Date Info -->
    <div class="max-w-5xl mx-auto mt-16 mb-12">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-50 to-pink-50 rounded-2xl"></div>
            <div class="relative bg-white border-2 border-primary-200 p-6 md:p-8 rounded-2xl shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-pink-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                    <h3 class="font-black text-xl text-gray-900 mb-3">Termín následující rozesílky</h3>
                    <div class="bg-gradient-to-r from-primary-100 to-pink-100 border-l-4 border-primary-500 rounded-lg p-4 mb-4">
                        <p class="text-gray-900 font-bold"><?php echo e($shippingInfo['cutoff_message']); ?></p>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        <strong class="text-gray-900">Jak to funguje:</strong> Rozesílka kávy probíhá vždy <strong class="text-primary-600">20. dne v měsíci</strong>. 
                        Objednávky uzavíráme <strong class="text-primary-600">15. dne v měsíci o půlnoci</strong>. 
                        Pokud si objednáte od 16. dne, vaše první zásilka dorazí až při následující rozesílce.
                    </p>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pricing = <?php echo json_encode($subscriptionPricing, 15, 512) ?>;
    
    let selectedAmount = null;
    let selectedType = null;
    let selectedFrequency = null;
    let isDecaf = false;
    
    // Pro Mix rozdělení
    let mixEspressoCount = 0;
    let mixFilterCount = 0;
    
    // Karty množství
    document.querySelectorAll('input[name="amount"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedAmount = parseInt(this.value);
            
            // Vizuální feedback
            document.querySelectorAll('input[name="amount"]').forEach(r => {
                const label = r.closest('label');
                const button = label.querySelector('span.block');
                if (r.checked) {
                    label.classList.add('border-primary-500', 'shadow-lg');
                    label.classList.remove('border-gray-200');
                    button.classList.remove('bg-gray-800');
                    button.classList.add('bg-primary-500');
                    button.textContent = 'Vybráno';
                } else {
                    label.classList.remove('border-primary-500', 'shadow-lg');
                    label.classList.add('border-gray-200');
                    button.classList.remove('bg-primary-500');
                    button.classList.add('bg-gray-800');
                    button.textContent = 'Vybrat plán';
                }
            });
            
            // Pokud je vybraný mix, přepočítej počty a zobraz sekci
            if (selectedType === 'mix') {
                mixEspressoCount = Math.floor(selectedAmount / 2);
                mixFilterCount = selectedAmount - mixEspressoCount;
                updateMixNoDecafDisplay();
                showDistributionLayout();
            }
            
            updateSummary();
        });
    });
    
    // Typ kávy
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedType = this.value;
            
            // Odškrtnout všechny decaf checkboxy kromě toho ve vybraném bloku
            document.querySelectorAll('input[name="isDecaf"]').forEach(checkbox => {
                const checkboxLabel = checkbox.closest('label.group');
                const radioLabel = this.closest('label.group');
                if (checkboxLabel !== radioLabel) {
                    checkbox.checked = false;
                }
            });
            
            // Aktualizovat isDecaf stav
            const currentDecafCheckbox = this.closest('label.group').querySelector('input[name="isDecaf"]');
            isDecaf = currentDecafCheckbox ? currentDecafCheckbox.checked : false;
            
            // Vizuální feedback
            document.querySelectorAll('input[name="type"]').forEach(r => {
                const label = r.closest('label');
                const button = label.querySelector('span.block');
                if (r.checked) {
                    label.classList.add('border-primary-500', 'shadow-lg');
                    label.classList.remove('border-gray-200');
                    button.classList.remove('bg-gray-800');
                    button.classList.add('bg-primary-500');
                    button.textContent = 'Vybráno';
                } else {
                    label.classList.remove('border-primary-500', 'shadow-lg');
                    label.classList.add('border-gray-200');
                    button.classList.remove('bg-primary-500');
                    button.classList.add('bg-gray-800');
                    button.textContent = 'Vybrat metodu';
                }
            });
            
            // Zobrazit/skrýt sekci Rozdělení kávy
            showDistributionLayout();
            
            updateSummary();
        });
    });
    
    // Decaf checkboxy - jeden pro každý typ kávy
    document.querySelectorAll('input[name="isDecaf"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Najít příslušný radio button v tomto bloku
            const radioButton = this.closest('label.group').querySelector('input[name="type"]');
            
            if (this.checked && radioButton) {
                // Automaticky vybrat příslušný typ kávy
                radioButton.checked = true;
                // Trigger change event pro aktualizaci UI
                radioButton.dispatchEvent(new Event('change'));
            } else {
                // Aktualizovat isDecaf stav
                isDecaf = this.checked;
                updateSummary();
            }
        });
    });
    
    // Frekvence
    document.querySelectorAll('input[name="frequency"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedFrequency = parseInt(this.value);
            
            // Vizuální feedback
            document.querySelectorAll('input[name="frequency"]').forEach(r => {
                const label = r.closest('label');
                const button = label.querySelector('span.block');
                if (r.checked) {
                    label.classList.add('border-primary-500', 'shadow-lg');
                    label.classList.remove('border-gray-200');
                    button.classList.remove('bg-gray-800');
                    button.classList.add('bg-primary-500');
                    button.textContent = 'Vybráno';
                } else {
                    label.classList.remove('border-primary-500', 'shadow-lg');
                    label.classList.add('border-gray-200');
                    button.classList.remove('bg-primary-500');
                    button.classList.add('bg-gray-800');
                    button.textContent = 'Vybrat frekvenci';
                }
            });
            
            updateSummary();
        });
    });
    
    // Funkce pro zobrazení/skrytí sekce Rozdělení kávy
    function showDistributionLayout() {
        const caffeineDistribution = document.getElementById('caffeine-distribution');
        const mixNoDecafDistribution = document.getElementById('mix-no-decaf-distribution');
        
        if (selectedType === 'mix') {
            // Zobraz sekci pro mix
            caffeineDistribution.classList.remove('hidden');
            mixNoDecafDistribution.classList.remove('hidden');
            
            // Inicializuj počty pokud je vybrané množství a počty ještě nejsou nastavené
            if (selectedAmount && (mixEspressoCount === 0 && mixFilterCount === 0)) {
                mixEspressoCount = Math.floor(selectedAmount / 2);
                mixFilterCount = selectedAmount - mixEspressoCount;
                updateMixNoDecafDisplay();
            }
        } else {
            // Skryj sekci
            caffeineDistribution.classList.add('hidden');
            mixNoDecafDistribution.classList.add('hidden');
        }
    }
    
    // Funkce pro update Mix NO decaf distribution
    function updateMixNoDecafDisplay() {
        document.getElementById('mix-espresso-count').textContent = mixEspressoCount;
        document.getElementById('mix-filter-count').textContent = mixFilterCount;
        
        // Aktualizuj hidden inputy
        document.getElementById('mix-espresso-value').value = mixEspressoCount;
        document.getElementById('mix-filter-value').value = mixFilterCount;
        
        const mixEspressoPlus = document.getElementById('mix-espresso-plus');
        const mixEspressoMinus = document.getElementById('mix-espresso-minus');
        const mixFilterPlus = document.getElementById('mix-filter-plus');
        const mixFilterMinus = document.getElementById('mix-filter-minus');
        
        if (mixEspressoPlus) mixEspressoPlus.disabled = mixFilterCount <= 0;
        if (mixEspressoMinus) mixEspressoMinus.disabled = mixEspressoCount <= 0;
        if (mixFilterPlus) mixFilterPlus.disabled = mixEspressoCount <= 0;
        if (mixFilterMinus) mixFilterMinus.disabled = mixFilterCount <= 0;
    }
    
    // Event listenery pro Mix NO decaf distribution tlačítka
    const mixEspressoPlus = document.getElementById('mix-espresso-plus');
    const mixEspressoMinus = document.getElementById('mix-espresso-minus');
    const mixFilterPlus = document.getElementById('mix-filter-plus');
    const mixFilterMinus = document.getElementById('mix-filter-minus');
    
    if (mixEspressoPlus) {
        mixEspressoPlus.addEventListener('click', function() {
            if (mixFilterCount > 0) {
                mixEspressoCount++;
                mixFilterCount--;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    if (mixEspressoMinus) {
        mixEspressoMinus.addEventListener('click', function() {
            if (mixEspressoCount > 0) {
                mixEspressoCount--;
                mixFilterCount++;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    if (mixFilterPlus) {
        mixFilterPlus.addEventListener('click', function() {
            if (mixEspressoCount > 0) {
                mixFilterCount++;
                mixEspressoCount--;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    if (mixFilterMinus) {
        mixFilterMinus.addEventListener('click', function() {
            if (mixFilterCount > 0) {
                mixFilterCount--;
                mixEspressoCount++;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    function updateSummary() {
        // Množství
        if (selectedAmount) {
            const grams = selectedAmount * 250;
            document.getElementById('summary-amount').textContent = grams + 'g (' + selectedAmount + ' balení)';
        } else {
            document.getElementById('summary-amount').textContent = '-';
        }
        
        // Typ
        let typeText = '-';
        if (selectedType) {
            const types = {
                'espresso': 'Espresso',
                'filter': 'Filtr',
                'mix': 'Kombinace'
            };
            typeText = types[selectedType];
            
            // Pro mix zobrazit rozdělení
            if (selectedType === 'mix' && (mixEspressoCount > 0 || mixFilterCount > 0)) {
                typeText += ` (${mixEspressoCount}x Espresso, ${mixFilterCount}x Filtr)`;
            }
            
            if (isDecaf) {
                typeText += ' + 1x decaf';
            }
        }
        document.getElementById('summary-type').textContent = typeText;
        
        // Frekvence
        const frequencies = {
            1: 'Každý měsíc',
            2: 'Jednou za 2 měsíce',
            3: 'Jednou za 3 měsíce'
        };
        document.getElementById('summary-frequency').textContent = frequencies[selectedFrequency] || '-';
        
        // Cena
        if (selectedAmount) {
            let price = pricing[selectedAmount] || 0;
            if (isDecaf) {
                price += 100;
            }
            document.getElementById('summary-price').textContent = price.toLocaleString('cs-CZ') + ' Kč';
        } else {
            document.getElementById('summary-price').textContent = '-';
        }
        
        // Povolit tlačítko submit pokud jsou všechny hodnoty vybrané
        const submitButton = document.getElementById('submit-button');
        if (selectedAmount && selectedType && selectedFrequency) {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/subscriptions/index.blade.php ENDPATH**/ ?>