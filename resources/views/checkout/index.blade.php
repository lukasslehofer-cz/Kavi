@extends('layouts.app')

@section('title', __('checkout.page_title'))

@section('content')
<style>
/* Swiss Style Form Inputs - Inline Labels with Light Lines */
.swiss-field {
    display: flex;
    align-items: baseline;
    border-bottom: 1px solid #BCBEB1;
    transition: border-color 0.2s;
}
.swiss-field:focus-within {
    border-bottom: 1px solid #636747;
}
.swiss-field-label {
    flex-shrink: 0;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #78716c;
    padding-right: 1rem;
    white-space: nowrap;
}
.swiss-field-label-required::after {
    content: ' *';
    color: #dc2626;
}
.swiss-field-input {
    flex-grow: 1;
    padding: 0.75rem 0;
    background: transparent;
    border: none;
    font-size: 0.875rem;
    color: #1c1c1c;
    -webkit-appearance: none;
}
.swiss-field-input:focus {
    outline: none;
    box-shadow: none;
    -webkit-box-shadow: none;
}
.swiss-field-input::placeholder {
    color: #a8a29e;
}
.swiss-field-select {
    flex-grow: 1;
    padding: 0.75rem 0;
    background: transparent;
    border: none;
    font-size: 0.875rem;
    color: #1c1c1c;
    cursor: pointer;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2378716c'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0 center;
    background-size: 1.25rem;
    padding-right: 1.5rem;
}
.swiss-field-select:focus {
    outline: none;
    box-shadow: none;
    -webkit-box-shadow: none;
}
.swiss-textarea {
    width: 100%;
    padding: 0.75rem 0;
    background: transparent;
    border: none;
    border-bottom: 1px solid #BCBEB1;
    font-size: 0.875rem;
    color: #1c1c1c;
    resize: none;
    -webkit-appearance: none;
}
.swiss-textarea:focus {
    outline: none;
    box-shadow: none;
    -webkit-box-shadow: none;
    border-bottom: 1px solid #636747;
}
.swiss-textarea::placeholder {
    color: #a8a29e;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-size: 0.75rem;
}
/* Legacy styles for coupon input */
.swiss-input {
    width: 100%;
    padding: 0.75rem 0;
    background: transparent;
    border: none;
    border-bottom: 1px solid #BCBEB1;
    font-size: 0.875rem;
    color: #1c1c1c;
    -webkit-appearance: none;
}
.swiss-input:focus {
    outline: none;
    box-shadow: none;
    -webkit-box-shadow: none;
    border-bottom: 1px solid #636747;
}
.swiss-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #78716c;
}
.swiss-label-required::after {
    content: ' *';
    color: #dc2626;
}
</style>
<div style="background-color: #e5e6df;">
<!-- Hero Header - Swiss Style -->
<div class="relative py-16 sm:py-20 lg:py-24 border-b border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-normal uppercase tracking-tight leading-[0.9]">
            <span class="text-dark-800">{{ $currentLocale === 'en' ? 'CHECKOUT' : 'POKLADNA' }}</span>
        </h1>
        <div class="mt-6">
            <span class="text-xs uppercase tracking-widest text-warm-500">{{ __('checkout.subtitle') }}</span>
        </div>
    </div>
</div>

@if($checkoutNotice ?? null)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
    @include('partials.checkout-notice', ['notice' => $checkoutNotice])
</div>
@endif

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
        <!-- Checkout Form -->
        <div class="lg:col-span-7">
            <form action="{{ localizedRoute('checkout.store') }}" method="POST">
                @csrf

                @php $sectionNum = 0; @endphp

                <!-- Contact Information - Swiss Style -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">{{ sprintf('%02d', ++$sectionNum) }}</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.contact_info') }}</h2>
                    </div>
                    
                    @guest
                    <!-- Login option for guests - Swiss Style -->
                    <div class="mb-8 py-4 border-b border-warm-300">
                        <div class="flex flex-wrap items-baseline gap-x-6 gap-y-2">
                            <span class="text-xs uppercase tracking-widest text-warm-500">{{ __('checkout.have_account') }}</span>
                            <a href="{{ localizedRoute('login') }}?redirect={{ urlencode(localizedRoute('checkout.index')) }}" class="text-xs uppercase tracking-widest text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 pb-0.5 transition-colors">
                                {{ __('checkout.login') }}
                            </a>
                            <button type="button" onclick="showMagicLinkModal()" class="text-xs uppercase tracking-widest text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 pb-0.5 transition-colors">
                                {{ __('checkout.send_magic_link') }}
                            </button>
                        </div>
                    </div>
                    @endguest
                    
                    <div class="space-y-4">
                        <div>
                            <div class="swiss-field">
                                <label for="name" class="swiss-field-label swiss-field-label-required">{{ __('checkout.fields.name') }}</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', auth()->user()->name ?? '') }}" 
                                    required
                                    class="swiss-field-input"
                                >
                            </div>
                            @error('name')
                                <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="swiss-field">
                                    <label for="email" class="swiss-field-label swiss-field-label-required">Email</label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email', auth()->user()->email ?? '') }}" 
                                        required
                                        class="swiss-field-input"
                                    >
                                </div>
                                @error('email')
                                    <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="swiss-field">
                                    <label for="phone" class="swiss-field-label swiss-field-label-required">{{ __('checkout.fields.phone') }}</label>
                                    <input 
                                        type="tel" 
                                        id="phone" 
                                        name="phone" 
                                        value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                                        required
                                        class="swiss-field-input"
                                        placeholder="+420 123 456 789"
                                        pattern="[\+]?[0-9\s\-\(\)]{9,20}"
                                    >
                                </div>
                                @error('phone')
                                    <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing Address - Swiss Style -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">{{ sprintf('%02d', ++$sectionNum) }}</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.billing_address') }}</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="swiss-field">
                                <label for="billing_address" class="swiss-field-label swiss-field-label-required">{{ __('checkout.fields.street') }}</label>
                                <input 
                                    type="text" 
                                    id="billing_address" 
                                    name="billing_address" 
                                    value="{{ old('billing_address', auth()->user()->address ?? '') }}" 
                                    required
                                    class="swiss-field-input"
                                    placeholder="{{ __('checkout.fields.street_placeholder') }}"
                                >
                            </div>
                            @error('billing_address')
                                <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <div class="swiss-field">
                                    <label for="billing_city" class="swiss-field-label swiss-field-label-required">{{ __('checkout.fields.city') }}</label>
                                    <input 
                                        type="text" 
                                        id="billing_city" 
                                        name="billing_city" 
                                        value="{{ old('billing_city', auth()->user()->city ?? '') }}" 
                                        required
                                        class="swiss-field-input"
                                        placeholder="{{ __('checkout.fields.city_placeholder') }}"
                                    >
                                </div>
                                @error('billing_city')
                                    <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="swiss-field">
                                    <label for="billing_postal_code" class="swiss-field-label swiss-field-label-required">{{ __('checkout.fields.postal_code') }}</label>
                                    <input 
                                        type="text" 
                                        id="billing_postal_code" 
                                        name="billing_postal_code" 
                                        value="{{ old('billing_postal_code', auth()->user()->postal_code ?? '') }}" 
                                        required
                                        class="swiss-field-input"
                                        placeholder="{{ __('checkout.fields.postal_code_placeholder') }}"
                                    >
                                </div>
                                @error('billing_postal_code')
                                    <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="swiss-field">
                                    <label for="billing_country" class="swiss-field-label swiss-field-label-required">{{ __('checkout.fields.country') }}</label>
                                    <select 
                                        id="billing_country" 
                                        name="billing_country" 
                                        required
                                        class="swiss-field-select"
                                    >
                                        <option value="">{{ __('checkout.fields.select_country') }}</option>
                                        @foreach($availableCountries as $code => $name)
                                            <option value="{{ $code }}" {{ old('billing_country', auth()->user()->country ?? ($code === 'CZ' ? 'CZ' : '')) == $code ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('billing_country')
                                    <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription Addon Option - Only for logged in users with active subscription (hidden for digital-only orders) -->
                @if(!$cartContainsOnlyDigitalProducts)
                @auth
                @if($canShipWithSubscription && !empty($availableSubscriptions))
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">{{ sprintf('%02d', ++$sectionNum) }}</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.subscription_addon.title') }}</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Checkbox row styled like terms checkbox -->
                        <div class="flex items-start py-4 border-b border-[#BCBEB1]" id="subscription-addon-checkbox-wrapper">
                            <input 
                                type="checkbox" 
                                id="ship_with_subscription_checkbox" 
                                name="ship_with_subscription" 
                                value="1"
                                class="w-4 h-4 text-dark-800 border-dark-800 focus:ring-olive-500 mr-3 mt-0.5 flex-shrink-0"
                                onclick="toggleSubscriptionAddon(this)"
                            >
                            <div class="flex-1">
                                <label for="ship_with_subscription_checkbox" class="text-xs uppercase tracking-widest text-dark-800 cursor-pointer" id="subscription-addon-label">
                                    {{ __('checkout.subscription_addon.checkbox_label') }}
                                    <span id="subscription-addon-status-message">
                                        @php
                                            $hasAnyAvailable = collect($availableSubscriptions)->contains('can_add_cart', true);
                                        @endphp
                                        @if($hasAnyAvailable)
                                        <span class="text-olive-500">/ {{ __('checkout.subscription_addon.free_shipping') }}</span>
                                        @else
                                        <span class="text-red-600">/ {{ __('checkout.subscription_addon.capacity_full') }}</span>
                                        @endif
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Content shown only when checkbox is checked -->
                        <div id="subscription-addon-content" style="display: none;">
                            <!-- Subscription selector (only if multiple subscriptions) -->
                            @if(count($availableSubscriptions) > 1)
                            <div id="subscription-selector" class="mb-4">
                                <div class="swiss-field">
                                    <label for="selected_subscription_id" class="swiss-field-label">{{ __('checkout.subscription_addon.select_subscription') }}</label>
                                    <select 
                                        id="selected_subscription_id" 
                                        name="selected_subscription_id" 
                                        class="swiss-field-select"
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
                                            — {{ $subInfo['next_shipment_formatted'] }} 
                                            ({{ $subInfo['available_slots'] }}/{{ $subInfo['max_slots'] }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
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

                            <!-- Slot indicator - Swiss Style -->
                            <div id="addon-slots-info" class="py-4">
                                @php
                                    $firstSub = $availableSubscriptions[0];
                                    $cartQuantity = array_sum(session()->get('cart', []));
                                    $remainingSlots = max(0, $firstSub['available_slots'] - $cartQuantity);
                                @endphp
                                
                                <div class="text-xs uppercase tracking-widest text-warm-500 mb-3">{{ __('checkout.subscription_addon.capacity_label') }}</div>
                                
                                <!-- Visual slot indicator - dots -->
                                <div class="flex items-center gap-2 mb-4" id="slot-visual">
                                    @for($i = 0; $i < $firstSub['max_slots']; $i++)
                                        @if($i < $firstSub['used_slots'] + $cartQuantity)
                                            <span class="text-primary-500 text-lg" title="Obsazený slot">●</span>
                                        @else
                                            <span class="text-warm-300 text-lg" title="Volný slot">●</span>
                                        @endif
                                    @endfor
                                    <span class="text-xs uppercase tracking-widest text-dark-800 ml-2" id="slot-text">
                                        ZBÝVÁ <span id="slot-available">{{ $remainingSlots }}</span> VOLNÝCH SLOTŮ
                                    </span>
                                </div>

                                <div id="shipment-date-info" class="text-xs uppercase tracking-widest text-warm-500">
                                    PLÁNOVANÉ ODESLÁNÍ / <span class="text-dark-800" id="shipment-date">{{ $firstSub['next_shipment_formatted'] }}</span>
                                </div>
                                
                                {{-- Warning --}}
                                <div class="mt-4" id="capacity-warning" style="display: {{ $firstSub['can_add_cart'] ? 'none' : 'block' }};">
                                    <p class="text-xs uppercase tracking-widest text-red-600" id="capacity-warning-text">
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

                            <p class="text-xs uppercase tracking-widest text-warm-400 py-4">
                                {!! __('checkout.subscription_addon.info') !!}
                            </p>
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
                        const statusMessage = document.getElementById('subscription-addon-status-message');
                        const label = document.getElementById('subscription-addon-label');
                        
                        if (isDisabled) {
                            // Disabled state - show warning
                            if (label) {
                                label.classList.remove('cursor-pointer');
                                label.classList.add('cursor-not-allowed', 'opacity-50');
                            }
                            if (statusMessage) {
                                statusMessage.innerHTML = '<span class="text-red-600">/ {{ __('checkout.subscription_addon.capacity_full') }}</span>';
                            }
                        } else {
                            // Enabled state - show free shipping
                            if (label) {
                                label.classList.remove('cursor-not-allowed', 'opacity-50');
                                label.classList.add('cursor-pointer');
                            }
                            if (statusMessage) {
                                statusMessage.innerHTML = '<span class="text-olive-500">/ {{ __('checkout.subscription_addon.free_shipping') }}</span>';
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
                        
                        // Update visual slots - dots style
                        const slotVisual = document.getElementById('slot-visual');
                        if (slotVisual) {
                            let html = '';
                            for (let i = 0; i < max; i++) {
                                if (i < used + cartQuantity) {
                                    html += '<span class="text-primary-500 text-lg" title="{{ __('checkout.subscription_addon.used_slot') }}">●</span>';
                                } else {
                                    html += '<span class="text-warm-300 text-lg" title="{{ __('checkout.subscription_addon.free_slot') }}">●</span>';
                                }
                            }
                            // Calculate remaining slots AFTER cart items
                            const remainingSlots = Math.max(0, available - cartQuantity);
                            html += `<span class="text-xs uppercase tracking-widest text-dark-800 ml-2">ZBÝVÁ <span id="slot-available">${remainingSlots}</span> VOLNÝCH SLOTŮ</span>`;
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
                @endif

                <!-- Packeta Pickup Point - Swiss Style (hidden for digital-only orders) -->
                @if(!$cartContainsOnlyDigitalProducts)
                <div class="mb-16 border-t-2 border-primary-500 pt-6" id="packeta-section">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">{{ sprintf('%02d', ++$sectionNum) }}</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.pickup_point.title') }}</h2>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Hidden fields for Packeta data -->
                        <input type="hidden" id="packeta_point_id" name="packeta_point_id" value="{{ old('packeta_point_id', auth()->user()->packeta_point_id ?? '') }}">
                        <input type="hidden" id="packeta_point_name" name="packeta_point_name" value="{{ old('packeta_point_name', auth()->user()->packeta_point_name ?? '') }}">
                        <input type="hidden" id="packeta_point_address" name="packeta_point_address" value="{{ old('packeta_point_address', auth()->user()->packeta_point_address ?? '') }}">
                        <input type="hidden" id="carrier_id" name="carrier_id" value="{{ old('carrier_id') }}">
                        <input type="hidden" id="carrier_pickup_point" name="carrier_pickup_point" value="{{ old('carrier_pickup_point') }}">

                        <!-- Packeta selection display -->
                        <div id="packeta-selection">
                            @if(old('packeta_point_id', auth()->user()->packeta_point_id ?? ''))
                            <!-- Selected point display - Swiss Style -->
                            <div id="selected-point" class="py-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <span class="text-xs uppercase tracking-widest text-olive-500 block mb-2">{{ __('checkout.pickup_point.selected') }}</span>
                                        <p class="font-display text-lg text-dark-800 uppercase tracking-tight" id="selected-point-name">{{ old('packeta_point_name', auth()->user()->packeta_point_name ?? '') }}</p>
                                        <p class="text-xs uppercase tracking-widest text-warm-500 mt-1" id="selected-point-address">{{ old('packeta_point_address', auth()->user()->packeta_point_address ?? '') }}</p>
                                    </div>
                                    <button type="button" id="change-point-btn" class="text-xs uppercase tracking-widest text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 pb-0.5 transition-colors whitespace-nowrap ml-4">
                                        {{ __('checkout.pickup_point.change') }}
                                    </button>
                                </div>
                            </div>
                            @else
                            <!-- Select button - Swiss Style -->
                            <button type="button" id="select-point-btn" class="group flex items-center gap-3 text-dark-800 hover:text-primary-500 transition-colors">
                                <span class="text-xs uppercase tracking-widest border-b border-dark-800 group-hover:border-primary-500 pb-0.5">{{ __('checkout.pickup_point.select') }}</span>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </button>
                            @endif
                        </div>

                        @error('packeta_point_id')
                            <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                        @enderror

                        <p class="text-xs uppercase tracking-widest text-warm-400">
                            {{ __('checkout.pickup_point.info') }}
                        </p>
                    </div>
                </div>
                @else
                <!-- Digital products info -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">{{ sprintf('%02d', ++$sectionNum) }}</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.digital_product.title') }}</h2>
                    </div>
                    <p class="text-xs uppercase tracking-widest text-warm-500">
                        {{ __('checkout.digital_product.description') }}
                    </p>
                </div>
                @endif

                <!-- Payment Method - Swiss Style -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">{{ sprintf('%02d', ++$sectionNum) }}</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.payment.title') }}</h2>
                    </div>
                    
                    <input type="hidden" name="payment_method" value="card">
                    <div class="py-4">
                        <p class="text-xs uppercase tracking-widest text-dark-800 mb-3">{{ __('checkout.payment.card') }}</p>
                        <p class="text-xs uppercase tracking-widest text-warm-500">
                            {{ __('checkout.payment.card_description_full') }}
                        </p>
                    </div>
                </div>

                <!-- Additional Notes - Swiss Style -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">{{ sprintf('%02d', ++$sectionNum) }}</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.fields.notes') }}</h2>
                        <span class="text-warm-400 text-xs uppercase tracking-widest">{{ __('checkout.fields.notes_optional') }}</span>
                    </div>
                    
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="4" 
                        class="swiss-textarea"
                        placeholder="{{ __('checkout.fields.notes_placeholder') }}"
                    >{{ old('notes') }}</textarea>
                </div>
            </form>
        </div>

        <!-- Order Summary - On Olive Background -->
        <div class="lg:col-span-5">
            <div class="sticky top-24 bg-[#BCBEB1] p-8">
                <h3 class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight mb-8">{{ __('checkout.order_summary') }}</h3>
                
                <!-- Order Items - Clean on Olive -->
                <div class="mb-8 space-y-4">
                    @foreach($cartItems as $item)
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 flex-shrink-0 bg-white overflow-hidden">
                            @if($item['product']->image)
                            <img src="{{ asset($item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-warm-400 text-xs">{{ substr($item['product']->getName(), 0, 1) }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-display text-sm text-dark-800 uppercase tracking-tight truncate">{{ $item['product']->getName() }}</p>
                            <p class="text-xs uppercase tracking-widest text-olive-600">{{ $item['quantity'] }}× {{ $item['product']->getFormattedPrice() }}</p>
                        </div>
                        <p class="text-xs uppercase tracking-widest text-dark-800">{{ \App\Helpers\CurrencyHelper::formatAmount($item['subtotal']) }}</p>
                    </div>
                    @endforeach
                </div>

                <!-- Coupon Section - On Olive -->
                <div class="mb-8">
                    @if(session('coupon_error'))
                        <p class="text-xs uppercase tracking-widest text-red-600 mb-4">STATUS / {{ strtoupper(session('coupon_error')) }}</p>
                    @endif
                    
                    @if($appliedCoupon ?? null)
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('checkout.coupon.applied') }}</span>
                            <a href="#" class="text-xs uppercase tracking-widest text-olive-500 hover:text-primary-500 transition-colors" id="remove-coupon-btn">
                                {{ __('checkout.coupon.remove') }}
                            </a>
                        </div>
                        <p class="font-display text-lg text-dark-800 uppercase tracking-tight">{{ $appliedCoupon->code }}</p>
                        <p class="text-xs uppercase tracking-widest text-olive-600 mt-1">{{ $appliedCoupon->getOrderDiscountDescription() }}</p>
                    @else
                        <details class="group" {{ request()->has('coupon_code') ? 'open' : '' }}>
                            <summary class="flex items-center justify-between cursor-pointer py-2 hover:text-primary-500 transition-colors">
                                <span class="text-xs uppercase tracking-widest text-dark-800 group-hover:text-primary-500">{{ __('checkout.coupon.title') }}</span>
                                <svg class="w-4 h-4 text-olive-500 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <form id="coupon-form" class="mt-4" onsubmit="return false;">
                                <div class="flex gap-3">
                                    <input type="text" id="coupon-code-input" placeholder="{{ __('checkout.coupon.placeholder') }}"
                                        class="flex-1 uppercase py-2 bg-white border-none text-sm text-dark-800 px-3"
                                        value="{{ request('coupon_code') }}">
                                    <button type="submit" id="apply-coupon-btn" class="text-xs uppercase tracking-widest text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 pb-0.5 transition-colors whitespace-nowrap">
                                        {{ __('checkout.coupon.apply') }}
                                    </button>
                                </div>
                                <p id="coupon-error" class="text-xs uppercase tracking-widest text-red-600 mt-2 hidden"></p>
                            </form>
                        </details>
                    @endif
                </div>

                <!-- Price Summary - Clean on Olive -->
                <dl class="space-y-3">
                    <!-- Shipping -->
                    <div class="flex justify-between items-baseline">
                        <dt class="text-xs uppercase tracking-widest text-olive-600">{{ __('checkout.shipping') }}</dt>
                        <dd class="text-sm uppercase tracking-wide">
                            <span id="shipping-cost">
                                @if($shipping == 0)
                                <span class="text-dark-800">{{ $currentLocale === 'en' ? 'FREE' : 'ZDARMA' }}</span>
                                @else
                                <span class="text-dark-800">{{ \App\Helpers\CurrencyHelper::formatAmount($shipping) }}</span>
                                @endif
                            </span>
                        </dd>
                    </div>
                    
                    <!-- Coupon discount -->
                    @if(($adjustedDiscount ?? 0) > 0)
                    <div class="flex justify-between items-baseline">
                        <dt class="text-xs uppercase tracking-widest text-olive-700">
                            {{ ($isGiftVoucher ?? false) ? (app()->getLocale() === 'cs' ? 'Dárkový voucher' : 'Gift voucher') : __('checkout.discount') }} {{ $appliedCoupon->code ?? '' }}
                        </dt>
                        <dd class="text-sm text-olive-700 uppercase tracking-wide">-{{ \App\Helpers\CurrencyHelper::formatAmount($adjustedDiscount) }}</dd>
                    </div>
                    @endif
                    
                    <!-- Subtotal without VAT -->
                    <div class="flex justify-between items-baseline">
                        <dt class="text-xs uppercase tracking-widest text-olive-600">{{ __('checkout.subtotal_without_vat') }}</dt>
                        <dd class="text-sm text-dark-800 uppercase tracking-wide">{{ \App\Helpers\CurrencyHelper::formatAmount($totalWithoutVat, 2) }}</dd>
                    </div>
                    
                    <!-- VAT -->
                    <div class="flex justify-between items-baseline">
                        @php
                            $uniqueVatRates = collect($cartItems)->pluck('product.vat_rate')->unique();
                            $vatWord = app()->getLocale() === 'cs' ? 'DPH' : 'VAT';
                            $vatLabel = $uniqueVatRates->count() === 1
                                ? $vatWord . ' (' . number_format($uniqueVatRates->first(), 0) . '%)'
                                : $vatWord;
                        @endphp
                        <dt class="text-xs uppercase tracking-widest text-olive-600">{{ $vatLabel }}</dt>
                        <dd class="text-sm text-dark-800 uppercase tracking-wide">{{ \App\Helpers\CurrencyHelper::formatAmount($vat, 2) }}</dd>
                    </div>
                    @if($isGiftVoucher ?? false)
                    <p class="text-xs text-olive-500">{{ app()->getLocale() === 'cs' ? 'DPH je vypočtena z ceny před odečtením voucheru' : 'VAT is calculated on the price before voucher deduction' }}</p>
                    @endif

                    <!-- Total -->
                    <div class="flex justify-between items-baseline pt-6 mt-4 border-t border-dark-800">
                        <dt class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.total') }}</dt>
                        <dd class="font-display text-2xl sm:text-3xl text-dark-800 uppercase tracking-tight" id="total-cost">
                            {{ \App\Helpers\CurrencyHelper::formatAmount($totalWithVat) }}
                        </dd>
                    </div>
                    <p class="text-xs uppercase tracking-widest text-olive-500 text-right">{{ __('checkout.incl_vat') }}</p>
                </dl>

                <!-- CTA Buttons -->
                <div class="mt-8 space-y-4">
                    <button type="submit" form="checkout-form" class="group w-full flex items-center justify-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all duration-200">
                        <span>{{ $currentLocale === 'en' ? 'COMPLETE ORDER' : 'DOKONČIT OBJEDNÁVKU' }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>

                    <div class="flex items-start py-4">
                        <input type="checkbox" id="terms" required form="checkout-form" class="w-4 h-4 text-dark-800 border-dark-800 focus:ring-olive-500 mr-3 mt-0.5 flex-shrink-0">
                        <label for="terms" class="text-xs uppercase tracking-widest text-olive-600">
                            {{ __('checkout.terms.agree') }} <a href="{{ localizedRoute('terms-of-service') }}" target="_blank" class="text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 transition-colors">{{ __('checkout.terms.terms_of_service') }}</a> 
                            {{ __('checkout.terms.and') }} <a href="{{ localizedRoute('privacy-policy') }}" target="_blank" class="text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 transition-colors">{{ __('checkout.terms.privacy_policy') }}</a>
                        </label>
                    </div>
                    
                    <div class="text-center">
                        <a href="{{ localizedRoute('cart.index') }}" class="inline-block text-xs uppercase tracking-widest text-olive-600 hover:text-dark-800 border-b border-olive-400 hover:border-dark-800 pb-1 transition-colors">
                            {{ __('checkout.buttons.back_to_cart') }}
                        </a>
                    </div>
                </div>

                <!-- Trust Indicators - On Olive -->
                <div class="mt-10 pt-6 border-t border-dark-800 space-y-2">
                    <div class="text-xs uppercase tracking-widest text-olive-600">
                        <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                        {{ __('checkout.trust.secure_payment') }}
                    </div>
                    <div class="text-xs uppercase tracking-widest text-olive-600">
                        <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                        {{ __('checkout.trust.eco_packaging') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Magic Link Modal - Swiss Style -->
<div id="magic-link-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="max-w-md w-full p-8" style="background-color: #e5e6df;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-display text-2xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.magic_link_title') }}</h3>
            <button onclick="closeMagicLinkModal()" class="text-warm-400 hover:text-dark-800 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="text-xs uppercase tracking-widest text-warm-500 mb-6">{{ __('checkout.magic_link_description') }}</p>
        <form method="POST" action="{{ localizedRoute('magic-link.send') }}" id="checkout-magic-link-form">
            @csrf
            <input type="hidden" name="redirect" value="{{ localizedRoute('checkout.index') }}">
            <div class="mb-6">
                <label for="magic-link-email-input" class="swiss-label swiss-label-required">Email</label>
                <input type="email" id="magic-link-email-input" name="email" required
                       class="swiss-input"
                       placeholder="vas@email.cz">
            </div>
            <button type="submit" class="group w-full flex items-center justify-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all duration-200">
                <span>{{ __('checkout.send_magic_link') }}</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
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
<script>
(function() {
    const STORAGE_KEY = 'checkout_form_data';

    function saveFormData() {
        const form = document.getElementById('checkout-form');
        if (!form) return;
        const data = {};
        const elements = form.querySelectorAll('input, select, textarea');
        elements.forEach(function(el) {
            if (!el.name || el.type === 'hidden' && el.name === '_token') return;
            if (el.type === 'checkbox' || el.type === 'radio') {
                data[el.name] = el.checked;
            } else {
                data[el.name] = el.value;
            }
        });
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    }

    function restoreFormData() {
        const raw = sessionStorage.getItem(STORAGE_KEY);
        if (!raw) return;
        sessionStorage.removeItem(STORAGE_KEY);
        const data = JSON.parse(raw);
        const form = document.getElementById('checkout-form');
        if (!form) return;
        Object.keys(data).forEach(function(name) {
            const el = form.querySelector('[name="' + name + '"]');
            if (!el) return;
            if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = data[name];
            } else {
                el.value = data[name];
            }
            el.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        restoreFormData();

        var couponForm = document.getElementById('coupon-form');
        if (couponForm) {
            couponForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var code = document.getElementById('coupon-code-input').value.trim();
                if (!code) return;
                var errorEl = document.getElementById('coupon-error');
                errorEl.classList.add('hidden');

                saveFormData();

                fetch('{{ localizedRoute("coupon.validate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ code: code, type: 'order', order_value: {{ $subtotal ?? 0 }} })
                }).then(function(res) {
                    return res.json().then(function(data) { return { ok: res.ok, data: data }; });
                }).then(function(result) {
                    if (result.ok && result.data.valid) {
                        window.location.reload();
                    } else {
                        sessionStorage.removeItem(STORAGE_KEY);
                        errorEl.textContent = result.data.message || '{{ __('checkout.coupon.invalid_fallback') }}';
                        errorEl.classList.remove('hidden');
                    }
                }).catch(function() {
                    sessionStorage.removeItem(STORAGE_KEY);
                });
            });
        }

        var removeBtn = document.getElementById('remove-coupon-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                saveFormData();

                fetch('{{ localizedRoute("coupon.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(function() {
                    window.location.reload();
                });
            });
        }
    });
})();
</script>

{{-- Tracking: InitiateCheckout (dataLayer + Meta Pixel) --}}
<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'event': 'begin_checkout',
        'ecommerce': {
            'currency': '{{ \App\Helpers\CurrencyHelper::code() }}',
            'value': {{ $totalWithVat }},
            'items': [
                @foreach($cartItems as $item)
                {
                    'item_id': '{{ $item['product']->id }}',
                    'item_name': '{{ addslashes($item['product']->getName()) }}',
                    'price': {{ $item['product']->getPrice() }},
                    'quantity': {{ $item['quantity'] }}
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
    });

    // Meta Pixel - InitiateCheckout
    if (typeof fbq !== 'undefined') {
        fbq('track', 'InitiateCheckout', {
            content_ids: [{!! collect($cartItems)->map(fn($item) => "'" . $item['product']->id . "'")->implode(',') !!}],
            content_type: 'product',
            value: {{ $totalWithVat }},
            currency: '{{ \App\Helpers\CurrencyHelper::code() }}',
            num_items: {{ collect($cartItems)->sum('quantity') }}
        });
    }
</script>
@endsection

