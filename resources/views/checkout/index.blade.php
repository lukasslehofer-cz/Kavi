@extends('layouts.app')

@section('title', __('checkout.page_title'))

@section('content')
<!-- Hero Header - Minimal -->
<div class="relative bg-gray-100 py-12 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 tracking-tight">{{ __('checkout.title') }}</h1>
                <p class="text-lg text-gray-600 font-light">{{ __('checkout.subtitle') }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-14 h-14 rounded-full bg-gray-900 flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form action="{{ localizedRoute('checkout.store') }}" method="POST">
                @csrf

                <!-- Contact Information - Minimal -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('checkout.contact_info') }}</h2>
                    </div>
                    
                    @guest
                    <!-- Login option for guests - Minimal -->
                    <div class="mb-6 bg-blue-50 p-5 rounded-xl border border-blue-200">
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0 mr-3">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 mb-1.5">{{ __('checkout.have_account') }}</h3>
                                <p class="text-sm text-gray-600 mb-3 font-light">{{ __('checkout.login_faster') }}</p>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ localizedRoute('login') }}?redirect={{ urlencode(localizedRoute('checkout.index')) }}" class="inline-block bg-white hover:bg-gray-50 text-blue-600 font-medium px-5 py-2 rounded-full border border-blue-200 hover:border-blue-300 transition-all text-sm">
                                        {{ __('checkout.login') }}
                                    </a>
                                    <button type="button" onclick="showMagicLinkModal()" class="inline-flex items-center gap-1 bg-white hover:bg-gray-50 text-gray-700 font-medium px-5 py-2 rounded-full border border-gray-200 hover:border-gray-300 transition-all text-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ __('checkout.send_magic_link') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endguest
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('checkout.fields.name') }} <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', auth()->user()->name ?? '') }}" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                            >
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', auth()->user()->email ?? '') }}" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                            >
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('checkout.fields.phone') }} @guest<span class="text-gray-500">{{ __('checkout.fields.notes_optional') }}</span>@else<span class="text-red-500">*</span>@endguest
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                                @auth required @endauth
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                placeholder="+420 123 456 789"
                            >
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @guest
                            <p class="text-xs text-gray-600 mt-1">{{ __('checkout.phone_helps') }}</p>
                            @endguest
                        </div>
                    </div>
                </div>

                <!-- Billing Address - Minimal -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('checkout.billing_address') }}</h2>
                    </div>
                    
                    <div class="space-y-5">
                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('checkout.fields.street') }} <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="billing_address" 
                                name="billing_address" 
                                value="{{ old('billing_address', auth()->user()->address ?? '') }}" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                placeholder="{{ __('checkout.fields.street_placeholder') }}"
                            >
                            @error('billing_address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('checkout.fields.city') }} <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="billing_city" 
                                    name="billing_city" 
                                    value="{{ old('billing_city', auth()->user()->city ?? '') }}" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    placeholder="{{ __('checkout.fields.city_placeholder') }}"
                                >
                                @error('billing_city')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="billing_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('checkout.fields.postal_code') }} <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="billing_postal_code" 
                                    name="billing_postal_code" 
                                    value="{{ old('billing_postal_code', auth()->user()->postal_code ?? '') }}" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    placeholder="{{ __('checkout.fields.postal_code_placeholder') }}"
                                >
                                @error('billing_postal_code')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('checkout.fields.country') }} <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="billing_country" 
                                    name="billing_country" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                >
                                    <option value="">{{ __('checkout.fields.select_country') }}</option>
                                    @foreach($availableCountries as $code => $name)
                                        <option value="{{ $code }}" {{ old('billing_country', auth()->user()->country ?? ($code === 'CZ' ? 'CZ' : '')) == $code ? 'selected' : '' }}>
                                            {{ __('countries.' . $name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('billing_country')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription Addon Option - Only for logged in users with active subscription -->
                @auth
                @if($canShipWithSubscription && !empty($availableSubscriptions))
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('checkout.subscription_addon.title') }}</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 border-2 border-gray-200 rounded-xl bg-purple-50" id="subscription-addon-checkbox-wrapper">
                            <input 
                                type="checkbox" 
                                id="ship_with_subscription_checkbox" 
                                name="ship_with_subscription" 
                                value="1"
                                class="mt-1 w-5 h-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500"
                                onclick="toggleSubscriptionAddon(this)"
                            >
                            <div class="flex-1">
                                <label for="ship_with_subscription_checkbox" id="subscription-addon-label" class="font-medium text-gray-900 cursor-pointer">
                                    {{ __('checkout.subscription_addon.checkbox_label') }}
                                </label>
                                <div id="subscription-addon-status-message">
                                    @php
                                        // Check if any subscription has available slots
                                        $hasAnyAvailable = collect($availableSubscriptions)->contains('can_add_cart', true);
                                    @endphp
                                    @if($hasAnyAvailable)
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ __('checkout.subscription_addon.free_shipping') }}
                                    </p>
                                    @else
                                    <p class="text-sm text-red-700 mt-1 font-medium">
                                        {{ __('checkout.subscription_addon.capacity_full') }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Content shown only when checkbox is checked -->
                        <div id="subscription-addon-content" style="display: none;">
                            <!-- Subscription selector (only if multiple subscriptions) -->
                            @if(count($availableSubscriptions) > 1)
                            <div id="subscription-selector">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('checkout.subscription_addon.select_subscription') }}
                                </label>
                                <select 
                                    id="selected_subscription_id" 
                                    name="selected_subscription_id" 
                                    class="w-full px-4 py-2.5 border border-purple-200 rounded-xl focus:ring-1 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                >
                                    @foreach($availableSubscriptions as $subInfo)
                                    <option 
                                        value="{{ $subInfo['subscription']->id }}" 
                                        data-available="{{ $subInfo['available_slots'] }}"
                                        data-used="{{ $subInfo['used_slots'] }}"
                                        data-max="{{ $subInfo['max_slots'] }}"
                                        data-can-add="{{ $subInfo['can_add_cart'] ? '1' : '0' }}"
                                        data-date="{{ $subInfo['next_shipment_formatted'] }}"
                                    >
                                        {{ $subInfo['subscription']->subscription_number ?? 'Předplatné #' . $subInfo['subscription']->id }} 
                                        - Rozesílka: {{ $subInfo['next_shipment_formatted'] }} 
                                        ({{ $subInfo['available_slots'] }}/{{ $subInfo['max_slots'] }} volných slotů)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <!-- Single subscription - hidden field -->
                            <input type="hidden" id="selected_subscription_id" name="selected_subscription_id" value="{{ $availableSubscriptions[0]['subscription']->id }}"
                                data-available="{{ $availableSubscriptions[0]['available_slots'] }}"
                                data-used="{{ $availableSubscriptions[0]['used_slots'] }}"
                                data-max="{{ $availableSubscriptions[0]['max_slots'] }}"
                                data-can-add="{{ $availableSubscriptions[0]['can_add_cart'] ? '1' : '0' }}"
                                data-date="{{ $availableSubscriptions[0]['next_shipment_formatted'] }}">
                            @endif

                            <!-- Slot indicator -->
                            <div id="addon-slots-info" class="py-4">
                                @php
                                    $firstSub = $availableSubscriptions[0];
                                    $cartQuantity = array_sum(session()->get('cart', []));
                                @endphp
                                
                                <div class="bg-white border border-purple-200 rounded-xl p-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-sm font-medium text-gray-700">{{ __('checkout.subscription_addon.capacity_label') }}</span>
                                    </div>
                                    
                                    <!-- Visual slot indicator -->
                                    <div class="flex items-center gap-2 mb-3" id="slot-visual">
                                        @for($i = 0; $i < $firstSub['max_slots']; $i++)
                                            @if($i < $firstSub['used_slots'])
                                                <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center text-white text-xs font-bold" title="Použitý slot">✓</div>
                                            @elseif($i < $firstSub['used_slots'] + $cartQuantity)
                                                <div class="w-10 h-10 border-2 border-purple-500 rounded-lg flex items-center justify-center text-white text-xs font-bold" title="Košík">🛒</div>
                                            @else
                                                <div class="w-10 h-10 border-2 border-gray-300 rounded-lg flex items-center justify-center text-gray-400 text-xs" title="Volný slot">○</div>
                                            @endif
                                        @endfor
                                        <span class="text-sm text-gray-600 ml-2" id="slot-text">
                                            <span id="slot-available">{{ $firstSub['available_slots'] }}</span> / {{ $firstSub['max_slots'] }} {{ __('checkout.subscription_addon.slots_available') }}
                                        </span>
                                    </div>

                                    <div id="shipment-date-info" class="text-sm text-purple-700 bg-purple-50 rounded-lg p-3">
                                        {{ __('checkout.subscription_addon.planned_delivery') }} <strong id="shipment-date">{{ $firstSub['next_shipment_formatted'] }}</strong>
                                    </div>
                                    
                                    {{-- Warning is dynamically shown/hidden by JavaScript based on selected subscription --}}
                                    <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3" id="capacity-warning" style="display: {{ $firstSub['can_add_cart'] ? 'none' : 'block' }};">
                                        <p class="text-sm text-red-800" id="capacity-warning-text">
                                            {{ __('checkout.subscription_addon.capacity_warning') }} 
                                            @if(count($availableSubscriptions) > 1)
                                            {{ __('checkout.subscription_addon.try_another') }}
                                            @else
                                            {{ __('checkout.subscription_addon.cart_has') }} {{ $cartQuantity }} {{ __('checkout.subscription_addon.items') }}, 
                                            {{ __('checkout.subscription_addon.but_only') }} {{ $firstSub['available_slots'] }} 
                                            {{ $firstSub['available_slots'] === 1 ? __('checkout.subscription_addon.slot_available') : __('checkout.subscription_addon.slots_available_plural') }}.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 flex items-start gap-2">
                                <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-blue-700 font-light">
                                    {!! __('checkout.subscription_addon.info') !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                // Global function for inline onclick handler
                function toggleSubscriptionAddon(checkbox) {
                    console.log('toggleSubscriptionAddon called, checked:', checkbox.checked);
                    const addonContent = document.getElementById('subscription-addon-content');
                    const packetaSection = document.getElementById('packeta-section');
                    
                    if (checkbox.checked) {
                        // Show addon content when checked
                        if (addonContent) {
                            addonContent.style.setProperty('display', 'block', 'important');
                            console.log('Addon content shown');
                        }
                        
                        // IMPORTANT: Call updateSlotsInfo to check if current subscription has slots
                        // This will hide/show Packeta and update pricing accordingly
                        if (typeof updateSlotsInfo === 'function') {
                            updateSlotsInfo();
                        }
                    } else {
                        // Hide addon content and show Packeta when unchecked
                        if (addonContent) {
                            addonContent.style.setProperty('display', 'none', 'important');
                            console.log('Addon content hidden');
                        }
                        if (packetaSection) {
                            packetaSection.style.setProperty('display', 'block', 'important');
                            console.log('Packeta section shown');
                        }
                        // Restore original pricing
                        if (typeof updatePricing === 'function') {
                            updatePricing(false);
                        }
                    }
                }
                
                document.addEventListener('DOMContentLoaded', function() {
                    const checkbox = document.getElementById('ship_with_subscription_checkbox');
                    const addonContent = document.getElementById('subscription-addon-content');
                    const selectElement = document.getElementById('selected_subscription_id');
                    const packetaSection = document.getElementById('packeta-section');
                    
                    // Debug logging
                    console.log('Checkout addon JS initialized', {
                        checkbox: checkbox,
                        addonContent: addonContent,
                        selectElement: selectElement,
                        packetaSection: packetaSection
                    });
                    
                    // Store original values for price calculation
                    const originalShipping = {{ $shipping }};
                    const originalTotal = {{ $totalWithVat }};
                    const discount = {{ $discount ?? 0 }};
                    
                    // Checkbox change is handled by inline onclick="toggleSubscriptionAddon(this)"
                    // No need for addEventListener here
                    
                    // Currency formatting helper
                    const isEur = {{ \App\Helpers\CurrencyHelper::isEur() ? 'true' : 'false' }};
                    const currencySymbol = '{{ \App\Helpers\CurrencyHelper::symbol() }}';
                    
                    window.formatCurrency = function(amount) {
                        if (isEur) {
                            return '€' + amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        } else {
                            return amount.toLocaleString('cs-CZ') + ' Kč';
                        }
                    }
                    
                    // Make updatePricing global so it can be called from toggleSubscriptionAddon
                    window.updatePricing = function(shipWithSubscription) {
                        console.log('updatePricing called with:', shipWithSubscription);
                        const shippingCostEl = document.getElementById('shipping-cost');
                        const totalCostEl = document.getElementById('total-cost');
                        
                        if (shipWithSubscription) {
                            // Set shipping to 0 (free)
                            if (shippingCostEl) {
                                shippingCostEl.innerHTML = `
                                    <span class="text-green-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('checkout.shipping_free_subscription') }}
                                    </span>
                                `;
                            }
                            
                            // Calculate new total without shipping
                            const newTotal = originalTotal - originalShipping;
                            if (totalCostEl) {
                                totalCostEl.textContent = formatCurrency(newTotal);
                            }
                        } else {
                            // Restore original shipping
                            if (shippingCostEl) {
                                if (originalShipping === 0) {
                                    shippingCostEl.innerHTML = `
                                        <span class="text-green-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ __('checkout.shipping_free') }}
                                        </span>
                                    `;
                                } else {
                                    shippingCostEl.innerHTML = `<span class="text-gray-900">${formatCurrency(originalShipping)}</span>`;
                                }
                            }
                            
                            // Restore original total
                            if (totalCostEl) {
                                totalCostEl.textContent = formatCurrency(originalTotal);
                            }
                        }
                    }
                    
                    // Check if ANY subscription has available slots for the cart
                    function checkAnySubscriptionAvailable() {
                        @if(count($availableSubscriptions) > 1)
                        // Multiple subscriptions - check if at least one can handle the cart
                        const subscriptions = @json($availableSubscriptions);
                        const cartQuantity = {{ array_sum(session()->get('cart', [])) }};
                        const hasAnyAvailable = subscriptions.some(sub => sub.can_add_cart);
                        
                        if (checkbox) {
                            checkbox.disabled = !hasAnyAvailable;
                            updateCheckboxVisuals(!hasAnyAvailable);
                            console.log('Multiple subscriptions check:', {
                                hasAnyAvailable: hasAnyAvailable,
                                checkboxDisabled: checkbox.disabled
                            });
                        }
                        @else
                        // Single subscription - check if it can handle the cart
                        const canAdd = {{ $availableSubscriptions[0]['can_add_cart'] ? 'true' : 'false' }};
                        if (checkbox) {
                            checkbox.disabled = !canAdd;
                            updateCheckboxVisuals(!canAdd);
                            console.log('Single subscription check:', {
                                canAdd: canAdd,
                                checkboxDisabled: checkbox.disabled
                            });
                        }
                        @endif
                    }
                    
                    // Update visual styling when checkbox is disabled
                    function updateCheckboxVisuals(isDisabled) {
                        const wrapper = document.getElementById('subscription-addon-checkbox-wrapper');
                        const statusMessage = document.getElementById('subscription-addon-status-message');
                        const label = document.getElementById('subscription-addon-label');
                        
                        if (isDisabled) {
                            // Disabled state - gray out and show warning
                            if (wrapper) {
                                wrapper.classList.remove('bg-purple-50', 'border-gray-200');
                                wrapper.classList.add('bg-gray-100', 'border-gray-300');
                            }
                            if (label) {
                                label.classList.remove('cursor-pointer', 'text-gray-900');
                                label.classList.add('cursor-not-allowed', 'text-gray-500');
                            }
                            if (statusMessage) {
                                statusMessage.innerHTML = '<p class="text-sm text-red-700 mt-1 font-medium">{{ __('checkout.subscription_addon.capacity_full') }}</p>';
                            }
                        } else {
                            // Enabled state - purple background and free shipping message
                            if (wrapper) {
                                wrapper.classList.remove('bg-gray-100', 'border-gray-300');
                                wrapper.classList.add('bg-purple-50', 'border-gray-200');
                            }
                            if (label) {
                                label.classList.remove('cursor-not-allowed', 'text-gray-500');
                                label.classList.add('cursor-pointer', 'text-gray-900');
                            }
                            if (statusMessage) {
                                statusMessage.innerHTML = '<p class="text-sm text-gray-600 mt-1">{{ __('checkout.subscription_addon.free_shipping') }}</p>';
                            }
                        }
                    }
                    
                    // Run initial check
                    checkAnySubscriptionAvailable();
                    
                    // Update slots info when subscription changes
                    if (selectElement) {
                        selectElement.addEventListener('change', function() {
                            updateSlotsInfo(checkbox);
                        });
                    }
                    
                    // Make updateSlotsInfo global so it can be called from toggleSubscriptionAddon
                    window.updateSlotsInfo = function(checkboxElement) {
                        const selectEl = document.getElementById('selected_subscription_id');
                        if (!selectEl) return;
                        const checkbox = checkboxElement || document.getElementById('ship_with_subscription_checkbox');
                        
                        // Handle both select element and hidden input
                        let selectedData;
                        if (selectEl.options) {
                            // It's a select element
                            selectedData = selectEl.options[selectEl.selectedIndex];
                        } else {
                            // It's a hidden input - use the element itself
                            selectedData = selectEl;
                        }
                        
                        const available = parseInt(selectedData.dataset.available);
                        const used = parseInt(selectedData.dataset.used);
                        const max = parseInt(selectedData.dataset.max);
                        const canAdd = selectedData.dataset.canAdd === '1';
                        const date = selectedData.dataset.date;
                        const cartQuantity = {{ array_sum(session()->get('cart', [])) }};
                        
                        // Update slot text
                        const slotAvailableEl = document.getElementById('slot-available');
                        if (slotAvailableEl) {
                            slotAvailableEl.textContent = available;
                        }
                        
                        // Update date
                        const shipmentDateEl = document.getElementById('shipment-date');
                        if (shipmentDateEl) {
                            shipmentDateEl.textContent = date;
                        }
                        
                        // Update visual slots
                        const slotVisual = document.getElementById('slot-visual');
                        if (slotVisual) {
                            let html = '';
                            for (let i = 0; i < max; i++) {
                                if (i < used) {
                                    html += '<div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center text-white text-xs font-bold" title="{{ __('checkout.subscription_addon.used_slot') }}">✓</div>';
                                } else if (i < used + cartQuantity) {
                                    html += '<div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center text-white text-xs font-bold" title="{{ __('checkout.subscription_addon.cart_slot') }}">🛒</div>';
                                } else {
                                    html += '<div class="w-10 h-10 border-2 border-gray-300 rounded-lg flex items-center justify-center text-gray-400 text-xs" title="{{ __('checkout.subscription_addon.free_slot') }}">○</div>';
                                }
                            }
                            html += `<span class="text-sm text-gray-600 ml-2"><span id="slot-available">${available}</span> / ${max} {{ __('checkout.subscription_addon.slots_available') }}</span>`;
                            slotVisual.innerHTML = html;
                        }
                        
                        // Show/hide warning based on CURRENT subscription
                        const warning = document.getElementById('capacity-warning');
                        if (warning) {
                            warning.style.display = canAdd ? 'none' : 'block';
                        }
                        
                        // CRITICAL: Update Packeta visibility and pricing based on selected subscription
                        const packetaSection = document.getElementById('packeta-section');
                        
                        if (canAdd && checkbox && checkbox.checked) {
                            // Selected subscription has slots AND checkbox is checked
                            // -> Hide Packeta and set free shipping
                            if (packetaSection) {
                                packetaSection.style.setProperty('display', 'none', 'important');
                            }
                            if (typeof updatePricing === 'function') {
                                updatePricing(true);
                            }
                            console.log('Selected subscription has slots - Packeta hidden, free shipping enabled');
                        } else if (checkbox && checkbox.checked) {
                            // Selected subscription DOESN'T have slots but checkbox is checked
                            // -> Show Packeta and restore shipping cost
                            if (packetaSection) {
                                packetaSection.style.setProperty('display', 'block', 'important');
                            }
                            if (typeof updatePricing === 'function') {
                                updatePricing(false);
                            }
                            console.log('Selected subscription has NO slots - Packeta shown, normal shipping');
                        }
                    }
                });
                </script>
                @endif
                @endauth

                <!-- Packeta Pickup Point - Minimal (hidden for digital-only orders) -->
                @if(!$cartContainsOnlyDigitalProducts)
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6" id="packeta-section">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('checkout.pickup_point.title') }}</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Hidden fields for Packeta data -->
                        <input type="hidden" id="packeta_point_id" name="packeta_point_id" value="{{ old('packeta_point_id', auth()->user()->packeta_point_id ?? '') }}">
                        <input type="hidden" id="packeta_point_name" name="packeta_point_name" value="{{ old('packeta_point_name', auth()->user()->packeta_point_name ?? '') }}">
                        <input type="hidden" id="packeta_point_address" name="packeta_point_address" value="{{ old('packeta_point_address', auth()->user()->packeta_point_address ?? '') }}">
                        <input type="hidden" id="carrier_id" name="carrier_id" value="{{ old('carrier_id') }}">
                        <input type="hidden" id="carrier_pickup_point" name="carrier_pickup_point" value="{{ old('carrier_pickup_point') }}">

                        <!-- Packeta selection display -->
                        <div id="packeta-selection">
                            @if(old('packeta_point_id', auth()->user()->packeta_point_id ?? ''))
                            <!-- Selected point display - Minimal -->
                            <div id="selected-point" class="p-4 bg-primary-50 border border-primary-300 rounded-xl">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1.5">
                                            <svg class="w-4 h-4 text-primary-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-medium text-gray-900 text-sm">{{ __('checkout.pickup_point.selected') }}</span>
                                        </div>
                                        <p class="text-gray-900 font-medium ml-6" id="selected-point-name">{{ old('packeta_point_name', auth()->user()->packeta_point_name ?? '') }}</p>
                                        <p class="text-sm text-gray-600 ml-6 font-light" id="selected-point-address">{{ old('packeta_point_address', auth()->user()->packeta_point_address ?? '') }}</p>
                                    </div>
                                    <button type="button" id="change-point-btn" class="text-sm bg-white hover:bg-gray-50 text-[#ba1b02] font-medium px-4 py-2 rounded-full border border-gray-200 whitespace-nowrap ml-4 transition-colors">
                                        {{ __('checkout.pickup_point.change') }}
                                    </button>
                                </div>
                            </div>
                            @else
                            <!-- Select button - Minimal -->
                            <button type="button" id="select-point-btn" class="w-full flex items-center justify-center gap-2 bg-[#ba1b02] hover:bg-[#a01701] text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ __('checkout.pickup_point.select') }}</span>
                            </button>
                            @endif
                        </div>

                        @error('packeta_point_id')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-blue-700 font-light">
                                {{ __('checkout.pickup_point.info') }}
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <!-- Digital products info -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mt-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">{{ __('checkout.digital_product.title') }}</h3>
                            <p class="text-sm text-blue-700 font-light">
                                {{ __('checkout.digital_product.description') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Payment Method - Minimal -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('checkout.payment.title') }}</h2>
                    </div>
                    
                    <!-- Single Payment Method Info - Minimal -->
                    <div class="p-4 border border-primary-400 bg-primary-50 rounded-xl">
                        <input type="hidden" name="payment_method" value="card">
                        <div class="flex items-start gap-3">
                            
                            <div class="flex-1">
                                <div class="font-bold text-gray-900 mb-1">{{ __('checkout.payment.card') }}</div>
                                <div class="text-sm text-gray-600 mb-3 font-light">{{ __('checkout.payment.card_description') }}</div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs text-gray-500 font-medium">{{ __('checkout.payment.we_accept') }}</span>
                                    <div class="flex items-center gap-2">
                                        <div class="px-2.5 py-1 bg-white rounded-lg border border-gray-200">
                                            <span class="text-xs font-semibold text-blue-700">Visa</span>
                                        </div>
                                        <div class="px-2.5 py-1 bg-white rounded-lg border border-gray-200">
                                            <span class="text-xs font-semibold text-orange-600">Mastercard</span>
                                        </div>
                                        <div class="px-2.5 py-1 bg-white rounded-lg border border-gray-200">
                                            <span class="text-xs font-semibold text-gray-700">Apple Pay</span>
                                        </div>
                                        <div class="px-2.5 py-1 bg-white rounded-lg border border-gray-200">
                                            <span class="text-xs font-semibold text-blue-600">Google Pay</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes - Minimal -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('checkout.fields.notes') }} <span class="text-base font-light text-gray-500">{{ __('checkout.fields.notes_optional') }}</span></h2>
                    </div>
                    
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="4" 
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                        placeholder="{{ __('checkout.fields.notes_placeholder') }}"
                    >{{ old('notes') }}</textarea>
                </div>
            </form>
        </div>

        <!-- Order Summary - Minimal -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-6 sticky top-24 border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ __('checkout.order_summary') }}</h3>
                </div>
                
                <!-- Order Items - Minimal -->
                <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                    @foreach($cartItems as $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-14 h-14 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item['product']->image)
                            <img src="{{ asset($item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <svg class="w-7 h-7 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $item['product']->getName() }}</p>
                            <p class="text-xs text-gray-500 font-light">{{ $item['quantity'] }}× {{ $item['product']->getFormattedPrice() }}</p>
                        </div>
                        <p class="text-sm font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::formatAmount($item['subtotal']) }}</p>
                    </div>
                    @endforeach
                </div>

                <!-- Coupon Section -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    @if(session('coupon_error'))
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-red-800">{{ session('coupon_error') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if($appliedCoupon ?? null)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium text-green-800">{{ __('checkout.coupon.applied') }}</span>
                                </div>
                                <a href="{{ localizedRoute('checkout.index', ['remove_coupon' => 1]) }}" class="text-xs text-red-600 hover:text-red-800 hover:underline">
                                    {{ __('checkout.coupon.remove') }}
                                </a>
                            </div>
                            <p class="text-sm text-green-700 font-mono font-bold">{{ $appliedCoupon->code }}</p>
                            <p class="text-xs text-green-600 mt-1">{{ $appliedCoupon->getOrderDiscountDescription() }}</p>
                        </div>
                    @else
                        <details class="group" {{ request()->has('coupon_code') ? 'open' : '' }}>
                            <summary class="flex items-center justify-between cursor-pointer p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <span class="text-sm font-medium text-gray-700">{{ __('checkout.coupon.title') }}</span>
                                <svg class="w-5 h-5 text-gray-600 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <form method="GET" action="{{ localizedRoute('checkout.index') }}" class="mt-3">
                                <div class="flex gap-2">
                                    <input type="text" name="coupon_code" placeholder="{{ __('checkout.coupon.placeholder') }}" 
                                        class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 uppercase text-sm"
                                        value="{{ request('coupon_code') }}">
                                    <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                        {{ __('checkout.coupon.apply') }}
                                    </button>
                                </div>
                            </form>
                        </details>
                    @endif
                </div>

                <!-- Price Summary - Minimal -->
                <dl class="space-y-3 mb-6">
                    <!-- Doprava (first) -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-600 text-sm font-light">{{ __('checkout.shipping') }}:</dt>
                        <dd class="font-bold">
                            <span id="shipping-cost">
                                @if($shipping == 0)
                                <span class="text-green-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('checkout.shipping_free') }}
                                    @if($appliedCoupon && $appliedCoupon->free_shipping)
                                    <span class="text-xs">({{ __('checkout.coupon.discount_label') }})</span>
                                    @endif
                                </span>
                                @else
                                <span class="text-gray-900">{{ \App\Helpers\CurrencyHelper::formatAmount($shipping) }}</span>
                                @endif
                            </span>
                        </dd>
                    </div>
                    
                    <!-- Coupon discount (prominently displayed) -->
                    @if(($adjustedDiscount ?? 0) > 0)
                    <div class="flex justify-between items-center py-3 border-b-2 border-green-200 bg-green-50 -mx-6 px-6">
                        <dt class="text-green-700 font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>{{ __('checkout.discount') }} {{ $appliedCoupon->code ?? '' }}:</span>
                        </dt>
                        <dd class="font-bold text-green-600 text-lg">-{{ \App\Helpers\CurrencyHelper::formatAmount($adjustedDiscount) }}</dd>
                    </div>
                    @endif
                    
                    <!-- Subtotal without VAT -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-600 text-sm font-light">{{ __('checkout.subtotal_without_vat') }}:</dt>
                        <dd class="font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::formatAmount($totalWithoutVat, 2) }}</dd>
                    </div>
                    
                    <!-- VAT -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-600 text-sm font-light">{{ __('checkout.vat') }}:</dt>
                        <dd class="font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::formatAmount($vat, 2) }}</dd>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-4 mt-2">
                        <div class="flex justify-between items-center">
                            <dt class="font-bold text-gray-900 text-lg">{{ __('checkout.total') }}:</dt>
                            <dd class="text-3xl font-bold text-gray-900" id="total-cost">
                                {{ \App\Helpers\CurrencyHelper::formatAmount($totalWithVat) }}
                            </dd>
                        </div>
                        <p class="text-xs text-gray-500 text-right mt-1 font-light">{{ __('checkout.incl_vat') }}</p>
                    </div>
                </dl>

                <button type="submit" form="checkout-form" class="group w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200 mb-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ __('checkout.buttons.complete_order') }}</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>

                <div class="flex items-start mb-4 p-3 bg-gray-50 rounded-xl">
                    <input type="checkbox" id="terms" required form="checkout-form" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 mr-2.5 mt-0.5 flex-shrink-0">
                    <label for="terms" class="text-xs text-gray-600 font-light">
                        {{ __('checkout.terms.agree') }} <a href="{{ localizedRoute('terms-of-service') }}" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium underline">{{ __('checkout.terms.terms_of_service') }}</a> 
                        {{ __('checkout.terms.and') }} <a href="{{ localizedRoute('privacy-policy') }}" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium underline">{{ __('checkout.terms.privacy_policy') }}</a>
                    </label>
                </div>

                <a href="{{ localizedRoute('cart.index') }}" class="block w-full text-center bg-white hover:bg-gray-50 text-gray-900 font-medium px-6 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>{{ __('checkout.buttons.back_to_cart') }}</span>
                </a>

                <!-- Trust Badges - Minimal -->
                <div class="mt-6 pt-6 border-t border-gray-100 space-y-2.5">
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ __('checkout.trust.secure_payment') }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ __('checkout.trust.eco_packaging') }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ __('checkout.trust.coffee_from_europe') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Magic Link Modal -->
<div id="magic-link-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">{{ __('checkout.magic_link_title') }}</h3>
            <button onclick="closeMagicLinkModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="text-gray-600 mb-4">{{ __('checkout.magic_link_description') }}</p>
        <form method="POST" action="{{ localizedRoute('magic-link.send') }}" id="checkout-magic-link-form">
            @csrf
            <input type="hidden" name="redirect" value="{{ localizedRoute('checkout.index') }}">
            <div class="mb-4">
                <label for="magic-link-email-input" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="magic-link-email-input" name="email" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="vas@email.cz">
            </div>
            <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all">
                {{ __('checkout.send_magic_link') }}
            </button>
        </form>
    </div>
</div>

<script src="https://widget.packeta.com/v6/www/js/library.js"></script>
<script>
// Currency formatting helper (must be global for all users)
const isEur = {{ \App\Helpers\CurrencyHelper::isEur() ? 'true' : 'false' }};
window.formatCurrency = function(amount) {
    if (isEur) {
        return '€' + amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    } else {
        return amount.toLocaleString('cs-CZ') + ' Kč';
    }
};

function showMagicLinkModal() {
    document.getElementById('magic-link-modal').classList.remove('hidden');
}

function closeMagicLinkModal() {
    document.getElementById('magic-link-modal').classList.add('hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMagicLinkModal();
    }
});

// Close modal on background click
document.getElementById('magic-link-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMagicLinkModal();
    }
});

// Packeta widget vendors configuration - MUST be global for widget access
let currentPacketaVendors = @json($packetaVendors ?? []);

// Add form id to the form element
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ localizedRoute('checkout.store') }}"]');
    if (form) {
        form.id = 'checkout-form';
    }

    // Dynamic shipping calculation
    const subtotal = {{ $subtotal }};
    const shippingCostElement = document.getElementById('shipping-cost');
    const totalElement = document.getElementById('total-cost');
    
    document.getElementById('billing_country').addEventListener('change', function() {
        const country = this.value;
        
        if (!country) {
            return;
        }
        
        // Clear Packeta selection when country changes
        document.getElementById('packeta_point_id').value = '';
        document.getElementById('packeta_point_name').value = '';
        document.getElementById('packeta_point_address').value = '';
        document.getElementById('carrier_id').value = '';
        document.getElementById('carrier_pickup_point').value = '';
        
                        // Reset Packeta UI to initial state
                        const selectionDiv = document.getElementById('packeta-selection');
                        const selectedPoint = document.getElementById('selected-point');
                        if (selectedPoint) {
                            selectionDiv.innerHTML = `
                                <button type="button" id="select-point-btn" class="w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ __('checkout.pickup_point.select') }}
                                </button>
                            `;
                            // Re-attach event listener
                            document.getElementById('select-point-btn').addEventListener('click', openPacketaWidget);
                        }
        
        // Show loading state
                        if (shippingCostElement) {
                            shippingCostElement.textContent = '{{ __('checkout.shipping_calculating') }}';
                        }
        
        // AJAX request to calculate shipping
        fetch('{{ route("api.calculate-shipping") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                country: country,
                subtotal: subtotal,
                is_subscription: false
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                // Update shipping cost
                if (shippingCostElement) {
                    shippingCostElement.textContent = data.shipping_formatted;
                }
                
                // Update total
                                if (totalElement) {
                                    const newTotal = subtotal + parseFloat(data.shipping);
                                    totalElement.textContent = formatCurrency(newTotal);
                                }
                                
                                // Update Packeta vendors for widget
                                currentPacketaVendors = data.packeta_vendors || [];
                            } else {
                                alert('{{ __('checkout.errors.country_unavailable') }}');
                                if (shippingCostElement) {
                                    shippingCostElement.textContent = '—';
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error calculating shipping:', error);
                            if (shippingCostElement) {
                                shippingCostElement.textContent = '{{ __('checkout.shipping_error') }}';
                            }
                        });
    });
    
    // Packeta Widget Configuration
    const packetaApiKey = '{{ config("services.packeta.widget_key") }}';
    
    function openPacketaWidget() {
        if (!packetaApiKey) {
            alert('Packeta widget není správně nakonfigurován. Kontaktujte administrátora.');
            return;
        }

                        const selectedCountry = document.getElementById('billing_country').value || 'cz';
                        const widgetOptions = {
                            country: selectedCountry.toLowerCase(),
                            language: '{{ app()->getLocale() }}',
                        };
        
        // Add vendor filter if vendors are set (supports multiple carriers and Packeta own network)
        if (currentPacketaVendors && currentPacketaVendors.length > 0) {
            // Packeta Widget v6 requires 'vendors' as array of vendor objects
            // Objects are already properly formatted by backend (carrierId for external, country+group for Packeta)
            widgetOptions.vendors = currentPacketaVendors;
            console.log('Widget vendors filter:', widgetOptions.vendors);
        }

        Packeta.Widget.pick(packetaApiKey, function(point) {
            if (point) {
                // DEBUG: Log complete point object from widget
                console.log('=== PACKETA WIDGET RETURNED POINT ===');
                console.log('Full point object:', point);
                console.log('point.id:', point.id);
                console.log('point.name:', point.name);
                console.log('point.carrierId:', point.carrierId);
                console.log('point.carrierPickupPointId:', point.carrierPickupPointId);
                console.log('point.nameStreet:', point.nameStreet);
                console.log('====================================');
                
                // Fill hidden fields with selected point data
                document.getElementById('packeta_point_id').value = point.id;
                document.getElementById('packeta_point_name').value = point.name;
                
                // Store carrier ID and carrierPickupPointId for Carriers PUDOs (international)
                if (point.carrierId) {
                    document.getElementById('carrier_id').value = point.carrierId;
                    console.log('✓ Saved carrier_id to hidden field:', point.carrierId);
                } else {
                    console.log('⚠ No carrierId in point object - field will be empty');
                }
                
                if (point.carrierPickupPointId) {
                    document.getElementById('carrier_pickup_point').value = point.carrierPickupPointId;
                    console.log('✓ Saved carrier_pickup_point to hidden field:', point.carrierPickupPointId);
                } else {
                    console.log('⚠ No carrierPickupPointId in point object - field will be empty');
                }
                
                // Verify what's actually in the form
                console.log('--- HIDDEN FIELDS CHECK ---');
                console.log('Form carrier_id value:', document.getElementById('carrier_id').value);
                console.log('Form carrier_pickup_point value:', document.getElementById('carrier_pickup_point').value);
                console.log('---------------------------');
                
                // Format address
                let address = point.street;
                if (point.city) {
                    address += ', ' + (point.zip ? point.zip + ' ' : '') + point.city;
                }
                document.getElementById('packeta_point_address').value = address;

                                // Update UI to show selected point - Minimal
                                const selectionDiv = document.getElementById('packeta-selection');
                                selectionDiv.innerHTML = `
                                    <div id="selected-point" class="p-4 bg-primary-50 border border-primary-300 rounded-xl">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-1.5">
                                                    <svg class="w-4 h-4 text-primary-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="font-medium text-gray-900 text-sm">{{ __('checkout.pickup_point.selected') }}</span>
                                                </div>
                                                <p class="text-gray-900 font-medium ml-6">${point.name}</p>
                                                <p class="text-sm text-gray-600 ml-6 font-light">${address}</p>
                                            </div>
                                            <button type="button" id="change-point-btn" class="text-sm bg-white hover:bg-gray-50 text-[#ba1b02] font-medium px-4 py-2 rounded-full border border-gray-200 whitespace-nowrap ml-4 transition-colors">
                                                {{ __('checkout.pickup_point.change') }}
                                            </button>
                                        </div>
                                    </div>
                                `;

                // Re-attach event listener to the new change button
                document.getElementById('change-point-btn').addEventListener('click', openPacketaWidget);
            }
        }, widgetOptions);
    }

    // Event listeners for opening widget
    const selectBtn = document.getElementById('select-point-btn');
    if (selectBtn) {
        selectBtn.addEventListener('click', openPacketaWidget);
    }

    const changeBtn = document.getElementById('change-point-btn');
    if (changeBtn) {
        changeBtn.addEventListener('click', openPacketaWidget);
    }
});
</script>
@endsection

