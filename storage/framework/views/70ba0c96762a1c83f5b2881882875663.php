<?php $__env->startSection('title', 'Potvrzení předplatného - Kavi Coffee'); ?>

<?php $__env->startSection('content'); ?>
<!-- Success Hero Header -->
<div class="relative bg-gradient-to-br from-green-50 via-emerald-50 to-green-100 py-16 border-b-2 border-green-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-6">
            <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-2xl">
                <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Děkujeme za objednávku!</h1>
        <p class="text-xl text-gray-700 max-w-2xl mx-auto">
            Vaše předplatné bylo úspěšně vytvořeno a je nyní aktivní. První zásilku vám odešleme v nejbližším termínu rozesílky.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Subscription Configuration -->
            <div class="bg-white rounded-2xl p-8 shadow-xl border-2 border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-pink-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900">Vaše předplatné</h2>
                </div>
                
                <?php
                $config = $subscription->configuration;
                $frequencyTexts = [
                    1 => 'Každý měsíc',
                    2 => 'Jednou za 2 měsíce',
                    3 => 'Jednou za 3 měsíce'
                ];
                $frequencyText = $frequencyTexts[$subscription->frequency_months] ?? '';
                
                // Get shipping date info
                $shippingInfo = \App\Helpers\SubscriptionHelper::getShippingDateInfo();
                
                // Calculate next payment date (15th of the next billing cycle)
                // Billing cycle: 16th of one month to 15th of next month
                // If subscription created between 16th-31st, current cycle ends on 15th of next month
                // If subscription created between 1st-15th, current cycle ends on 15th of current month
                $subscriptionDate = \Carbon\Carbon::parse($subscription->starts_at);
                $currentBillingCycleEnd = $subscriptionDate->day <= 15 
                    ? $subscriptionDate->copy()->setDay(15) 
                    : $subscriptionDate->copy()->addMonth()->setDay(15);
                
                // Next payment is frequency_months after current billing cycle end
                $nextPaymentDate = $currentBillingCycleEnd->copy()->addMonths($subscription->frequency_months);
                ?>
                
                <div class="bg-gradient-to-br from-gray-50 to-slate-50 p-6 rounded-xl border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Množství</div>
                            <div class="text-lg font-black text-gray-900">
                                <?php echo e($config['amount']); ?> balení (<?php echo e($config['amount'] * 250); ?>g)
                            </div>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Typ kávy</div>
                            <div class="text-lg font-black text-gray-900">
                                <?php if($config['type'] === 'espresso'): ?>
                                    Espresso <?php if($config['isDecaf'] ?? false): ?>(vč. 1× decaf)<?php endif; ?>
                                <?php elseif($config['type'] === 'filter'): ?>
                                    Filtr <?php if($config['isDecaf'] ?? false): ?>(vč. 1× decaf)<?php endif; ?>
                                <?php else: ?>
                                    Kombinace <?php if($config['isDecaf'] ?? false): ?>(vč. 1× decaf)<?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php if($config['type'] === 'mix' && isset($config['mix'])): ?>
                            <div class="mt-2 space-y-1 text-xs text-gray-700">
                                <?php if(($config['mix']['espresso'] ?? 0) > 0): ?>
                                <div>• <?php echo e($config['mix']['espresso']); ?>× Espresso</div>
                                <?php endif; ?>
                                <?php if(($config['mix']['filter'] ?? 0) > 0): ?>
                                <div>• <?php echo e($config['mix']['filter']); ?>× Filtr</div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Frekvence</div>
                            <div class="text-lg font-black text-gray-900"><?php echo e($frequencyText); ?></div>
                        </div>
                        
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Cena</div>
                            <div class="text-2xl font-black bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">
                                <?php echo e(number_format($subscription->configured_price, 0, ',', ' ')); ?> Kč
                            </div>
                            <div class="text-xs text-gray-600"><?php echo e($frequencyText); ?></div>
                        </div>
                    </div>
                    
                    <!-- Shipping and Payment Dates -->
                    <div class="mt-6 pt-6 border-t-2 border-gray-200 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-gray-900 mb-1">První rozesílka</div>
                                <div class="text-sm text-gray-700">
                                    <strong class="text-primary-600"><?php echo e($shippingInfo['next_shipping_date']->format('j. n. Y')); ?></strong>
                                    (20. den v měsíci)
                                </div>
                                <div class="text-xs text-gray-600 mt-1">
                                    <?php echo e($shippingInfo['cutoff_message']); ?>

                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-gray-900 mb-1">Další platba</div>
                                <div class="text-sm text-gray-700">
                                    <strong class="text-green-600"><?php echo e($nextPaymentDate->format('j. n. Y')); ?></strong>
                                    (15. den v měsíci)
                                </div>
                                <div class="text-xs text-gray-600 mt-1">
                                    Platby se automaticky strhávají z karty vždy 15. den v měsíci
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Billing Information -->
            <div class="bg-white rounded-2xl p-8 shadow-xl border-2 border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900">Kontaktní a fakturační údaje</h2>
                </div>
                
                <?php if($subscription->shipping_address): ?>
                <div class="bg-gradient-to-br from-gray-50 to-slate-50 p-6 rounded-xl border border-gray-200">
                    <div class="space-y-3 text-gray-700">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500 mb-0.5">Jméno</div>
                                <div class="font-bold text-gray-900"><?php echo e($subscription->shipping_address['name']); ?></div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500 mb-0.5">Email</div>
                                <div class="font-bold text-gray-900"><?php echo e($subscription->shipping_address['email']); ?></div>
                            </div>
                        </div>
                        
                        <?php if(!empty($subscription->shipping_address['phone'])): ?>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500 mb-0.5">Telefon</div>
                                <div class="font-bold text-gray-900"><?php echo e($subscription->shipping_address['phone']); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="border-t-2 border-gray-200 pt-3 mt-3"></div>
                        
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500 mb-0.5">Fakturační adresa</div>
                                <div class="font-bold text-gray-900"><?php echo e($subscription->shipping_address['billing_address']); ?>, <?php echo e($subscription->shipping_address['billing_postal_code']); ?> <?php echo e($subscription->shipping_address['billing_city']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Packeta Delivery Point -->
            <?php if($subscription->packeta_point_name): ?>
            <div class="bg-white rounded-2xl p-8 shadow-xl border-2 border-primary-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-[#ba1b02] flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900">📦 Výdejní místo zásilky</h2>
                </div>
                
                <div class="bg-gradient-to-r from-orange-50 to-red-50 p-6 rounded-xl border-2 border-[#ba1b02]/20 shadow-md">
                    <div class="text-sm font-semibold text-[#ba1b02] mb-2">Zásilkovna</div>
                    <div class="text-lg font-black text-gray-900 mb-1"><?php echo e($subscription->packeta_point_name); ?></div>
                    <?php if($subscription->packeta_point_address): ?>
                    <div class="text-sm text-gray-700 flex items-start gap-1">
                        <svg class="w-4 h-4 text-gray-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <?php echo e($subscription->packeta_point_address); ?>

                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-4 pt-4 border-t border-[#ba1b02]/20">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold">Objednávku si vyzvedněte na tomto výdejním místě</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($subscription->delivery_notes): ?>
            <div class="bg-white rounded-2xl p-8 shadow-xl border-2 border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-500 to-slate-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900">Poznámka</h2>
                </div>
                
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 text-gray-700">
                    <?php echo e($subscription->delivery_notes); ?>

                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Next Steps Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-8 sticky top-24 shadow-xl border-2 border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900">Co dál?</h3>
                </div>
                
                <div class="space-y-4 mb-6">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-600 font-bold text-sm">1</span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 mb-1">Potvrzení emailem</div>
                            <div class="text-sm text-gray-600">Na váš email jsme odeslali potvrzení s detaily předplatného</div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-600 font-bold text-sm">2</span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 mb-1">První zásilka</div>
                            <div class="text-sm text-gray-600">Kávu vám odešleme v nejbližším termínu rozesílky (20. den v měsíci)</div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-600 font-bold text-sm">3</span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 mb-1">Správa předplatného</div>
                            <div class="text-sm text-gray-600">V dashboardu můžete kdykoli upravit nebo zrušit předplatné</div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <a href="<?php echo e(route('dashboard.subscription')); ?>" class="block w-full text-center bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        Zobrazit předplatné
                    </a>
                    
                    <a href="<?php echo e(route('home')); ?>" class="block w-full text-center bg-white hover:bg-gray-50 text-gray-900 font-semibold px-8 py-3 rounded-xl shadow-md hover:shadow-lg border-2 border-gray-200 hover:border-gray-300 transition-all duration-200">
                        Zpět na homepage
                    </a>
                </div>

                <!-- Contact Info -->
                <div class="mt-6 pt-6 border-t-2 border-gray-200">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-2">Potřebujete pomoc?</div>
                        <a href="mailto:info@kavi.cz" class="text-primary-600 hover:text-primary-700 font-semibold">info@kavi.cz</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/subscriptions/confirmation.blade.php ENDPATH**/ ?>