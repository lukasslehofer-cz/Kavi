@extends('layouts.app')

@section('title', __('checkout.page_title_subscription'))

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
</style>
<div style="background-color: #e5e6df;">
<!-- Hero Header - Swiss Style -->
<div class="relative py-16 sm:py-20 lg:py-24 border-b border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-normal uppercase tracking-tight leading-[0.9]">
            <span class="text-dark-800">{{ $currentLocale === 'en' ? 'SUBSCRIPTION CHECKOUT' : 'POKLADNA' }}</span>
        </h1>
        <div class="mt-6">
            <span class="text-xs uppercase tracking-widest text-warm-500">{{ __('checkout.subtitle_subscription') }}</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
        <!-- Checkout Form -->
        <div class="lg:col-span-7">
            <form action="{{ localizedRoute('subscriptions.checkout.process') }}" method="POST" id="subscription-checkout-form">
                @csrf
                
                <!-- Hidden input for coupon code -->
                <input type="hidden" name="coupon_code" value="{{ $appliedCoupon ? $appliedCoupon->code : '' }}" id="coupon_code_input">
                
                <!-- Contact Information - Swiss Style -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">01</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.contact_info') }}</h2>
                    </div>
                    
                    @guest
                    <!-- Login option for guests - Swiss Style -->
                    <div class="mb-8 py-4 border-b border-warm-300">
                        <div class="flex flex-wrap items-baseline gap-x-6 gap-y-2">
                            <span class="text-xs uppercase tracking-widest text-warm-500">{{ __('checkout.have_account') }}</span>
                            <a href="{{ localizedRoute('login') }}?redirect={{ urlencode(localizedRoute('subscriptions.checkout')) }}" class="text-xs uppercase tracking-widest text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 pb-0.5 transition-colors">
                                {{ __('checkout.login') }}
                            </a>
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
                                @guest
                                <p class="text-xs text-warm-500 mt-2 uppercase tracking-widest">{{ __('checkout.email_confirmation_note') }}</p>
                                @endguest
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
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">02</span>
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

                <!-- Packeta Pickup Point - Swiss Style -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">03</span>
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

                <!-- Payment Method - Swiss Style -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">04</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.payment.title') }}</h2>
                    </div>
                    
                    <input type="hidden" name="payment_method" value="card">
                    
                    <div class="py-6">
                        <p class="text-xs uppercase tracking-widest text-dark-800 mb-4">{{ __('checkout.payment.card') }}</p>
                        <p class="text-xs uppercase tracking-widest text-warm-500">
                            {{ __('checkout.payment.card_description_full') ?? __('checkout.payment.card_description') . ' ' . __('checkout.payment.we_accept') . ' VISA, MASTERCARD, APPLE PAY, GOOGLE PAY.' }}
                        </p>
                    </div>
                </div>

                <!-- Additional Notes - Swiss Style -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">05</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">
                            {{ __('checkout.fields.notes') }}
                            <span class="text-xs uppercase tracking-widest text-warm-500 ml-2">/ {{ __('checkout.fields.notes_optional') }}</span>
                        </h2>
                    </div>
                    
                    <textarea 
                        id="delivery_notes" 
                        name="delivery_notes" 
                        rows="4" 
                        class="swiss-textarea"
                        placeholder="{{ __('checkout.fields.notes_placeholder') }}"
                    >{{ old('delivery_notes') }}</textarea>
                    @error('delivery_notes')
                        <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>

        <!-- Order Summary - On Olive Background -->
        <div class="lg:col-span-5">
            <div class="sticky top-24 bg-[#BCBEB1] p-8">
                <h3 class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight mb-8">{{ __('checkout.subscription_summary') }}</h3>
                
                @php
                $frequencyTexts = [
                    1 => __('checkout.subscription.every_month'),
                    2 => __('checkout.subscription.every_2_months'),
                    3 => __('checkout.subscription.every_3_months')
                ];
                $frequencyText = $frequencyTexts[$configuration['frequency']] ?? '';
                @endphp
                
                <!-- Subscription Details -->
                <div class="mb-8 space-y-4">
                    <div class="flex justify-between items-baseline">
                        <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('checkout.subscription.quantity') }}</span>
                        <span class="text-sm text-dark-800 uppercase tracking-wide text-right">{{ $configuration['amount'] }} {{ __('checkout.subscription.bags') }} ({{ $configuration['amount'] * 250 }}g)</span>
                    </div>
                    
                    <div class="flex justify-between items-baseline">
                        <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('checkout.subscription.coffee_type') }}</span>
                        <span class="text-sm text-dark-800 uppercase tracking-wide text-right">
                            @if($configuration['type'] === 'espresso')
                                {{ __('checkout.subscription.espresso') }} @if($configuration['isDecaf']){{ __('checkout.subscription.incl_decaf') }}@endif
                            @elseif($configuration['type'] === 'filter')
                                {{ __('checkout.subscription.filter') }} @if($configuration['isDecaf']){{ __('checkout.subscription.incl_decaf') }}@endif
                            @else
                                {{ __('checkout.subscription.mix') }} @if($configuration['isDecaf']){{ __('checkout.subscription.incl_decaf') }}@endif
                            @endif
                        </span>
                    </div>
                    
                    @if($configuration['type'] === 'mix')
                    <div class="text-xs text-olive-600 uppercase tracking-widest text-right">
                        @if(isset($configuration['mix']['espresso']) && $configuration['mix']['espresso'] > 0)
                        <div>{{ $configuration['mix']['espresso'] }}× ESPRESSO</div>
                        @endif
                        @if(isset($configuration['mix']['filter']) && $configuration['mix']['filter'] > 0)
                        <div>{{ $configuration['mix']['filter'] }}× FILTR</div>
                        @endif
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-baseline">
                        <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('checkout.subscription.frequency') }}</span>
                        <span class="text-sm text-dark-800 uppercase tracking-wide">{{ $frequencyText }}</span>
                    </div>
                </div>

                <!-- Shipping Date Info - Swiss Style -->
                <div class="mb-8">
                    <p class="text-xs uppercase tracking-widest text-olive-600 mb-2">{{ __('checkout.delivery_info.title') }}</p>
                    <p class="text-sm text-dark-800">
                        {{ $shippingInfo['cutoff_message'] }}
                    </p>
                </div>

                <!-- Coupon Section - On Olive -->
                <div class="mb-8">
                    @if(session('coupon_error'))
                        <p class="text-xs uppercase tracking-widest text-red-600 mb-4">STATUS / {{ strtoupper(session('coupon_error')) }}</p>
                    @endif
                    
                    @if($appliedCoupon ?? null)
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('checkout.coupon.applied') }}</span>
                            <a href="{{ localizedRoute('subscriptions.checkout', ['remove_coupon' => 1]) }}" class="text-xs uppercase tracking-widest text-olive-500 hover:text-primary-500 transition-colors" onclick="document.getElementById('coupon_code_input').value = '';">
                                {{ __('checkout.coupon.remove') }}
                            </a>
                        </div>
                        <p class="font-display text-lg text-dark-800 uppercase tracking-tight">{{ $appliedCoupon->code }}</p>
                        <p class="text-xs uppercase tracking-widest text-olive-600 mt-1">{{ $appliedCoupon->getSubscriptionDiscountDescription() }}</p>
                    @else
                        <details class="group" {{ request()->has('coupon_code') ? 'open' : '' }}>
                            <summary class="flex items-center justify-between cursor-pointer py-2 hover:text-primary-500 transition-colors">
                                <span class="text-xs uppercase tracking-widest text-dark-800 group-hover:text-primary-500">{{ __('checkout.coupon.title') }}</span>
                                <svg class="w-4 h-4 text-olive-500 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <form method="GET" action="{{ localizedRoute('subscriptions.checkout') }}" class="mt-4">
                                <div class="flex gap-3">
                                    <input type="text" name="coupon_code" placeholder="{{ __('checkout.coupon.placeholder') }}" 
                                        class="flex-1 uppercase py-2 bg-white border-none text-sm text-dark-800 px-3"
                                        value="{{ request('coupon_code') }}">
                                    <button type="submit" class="text-xs uppercase tracking-widest text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 pb-0.5 transition-colors whitespace-nowrap">
                                        {{ __('checkout.coupon.apply') }}
                                    </button>
                                </div>
                            </form>
                        </details>
                    @endif
                </div>

                <!-- 100% Discount Notice -->
                @if($price <= 0 && ($discount ?? 0) > 0)
                <div class="mb-8">
                    <p class="text-xs uppercase tracking-widest text-olive-600 mb-2">{{ __('checkout.full_discount.title') }}</p>
                    <p class="text-sm text-dark-800">
                        {!! __('checkout.full_discount.description', ['code' => $appliedCoupon->code]) !!}
                    </p>
                </div>
                @endif

                <!-- Price Summary - Clean on Olive -->
                <dl class="space-y-3">
                    <!-- Shipping -->
                    <div class="flex justify-between items-baseline">
                        <dt class="text-xs uppercase tracking-widest text-olive-600">{{ __('checkout.shipping') }}</dt>
                        <dd class="text-sm uppercase tracking-wide">
                            <span id="shipping-cost">
                                @if(isset($shipping) && $shipping == 0)
                                <span class="text-dark-800">{{ $currentLocale === 'en' ? 'FREE' : 'ZDARMA' }}</span>
                                @elseif(isset($shipping) && $shipping > 0)
                                <span class="text-dark-800">{{ \App\Helpers\CurrencyHelper::formatAmount($shipping) }}</span>
                                @else
                                <span class="text-olive-500">{{ __('checkout.shipping_at_checkout') }}</span>
                                @endif
                            </span>
                        </dd>
                    </div>
                    
                    <!-- Coupon discount -->
                    @if(($adjustedDiscount ?? 0) > 0)
                    <div class="flex justify-between items-baseline">
                        <dt class="text-xs uppercase tracking-widest text-olive-700">{{ __('checkout.discount') }} {{ $appliedCoupon->code ?? '' }}</dt>
                        <dd class="text-sm text-olive-700 uppercase tracking-wide">-{{ \App\Helpers\CurrencyHelper::formatAmount($adjustedDiscount) }}</dd>
                    </div>
                    @endif
                    
                    <!-- Subtotal without VAT -->
                    <div class="flex justify-between items-baseline">
                        <dt class="text-xs uppercase tracking-widest text-olive-600">{{ $configuration['amount'] }}× {{ __('checkout.subscription.bags_without_vat') }}</dt>
                        <dd class="text-sm text-dark-800 uppercase tracking-wide">{{ \App\Helpers\CurrencyHelper::formatAmount($priceWithoutVat, 2) }}</dd>
                    </div>
                    
                    <!-- VAT -->
                    <div class="flex justify-between items-baseline">
                        <dt class="text-xs uppercase tracking-widest text-olive-600">{{ __('checkout.vat') }}</dt>
                        <dd class="text-sm text-dark-800 uppercase tracking-wide">{{ \App\Helpers\CurrencyHelper::formatAmount($vat, 2) }}</dd>
                    </div>

                    <!-- Total -->
                    <div class="flex justify-between items-baseline pt-6 mt-4 border-t border-dark-800">
                        <dt class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight">{{ __('checkout.total_per_month') }}</dt>
                        <dd class="font-display text-2xl sm:text-3xl text-dark-800 uppercase tracking-tight" id="total-cost">
                            {{ \App\Helpers\CurrencyHelper::formatAmount($price + ($shipping ?? 0)) }}
                        </dd>
                    </div>
                    <p class="text-xs uppercase tracking-widest text-olive-500 text-right">{{ $frequencyText }} / {{ __('checkout.incl_vat') }}</p>
                </dl>

                <!-- CTA Buttons -->
                <div class="mt-8 space-y-4">
                    <button type="submit" form="subscription-checkout-form" class="group w-full flex items-center justify-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all duration-200">
                        <span>{{ __('checkout.buttons.complete_order') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>

                    <div class="flex items-start py-4">
                        <input type="checkbox" id="terms" required form="subscription-checkout-form" class="w-4 h-4 text-dark-800 border-dark-800 focus:ring-olive-500 mr-3 mt-0.5 flex-shrink-0">
                        <label for="terms" class="text-xs uppercase tracking-widest text-olive-600">
                            {{ __('checkout.terms.agree') }} <a href="{{ localizedRoute('terms-of-service') }}" target="_blank" class="text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 transition-colors">{{ __('checkout.terms.terms_of_service') }}</a> 
                            {{ __('checkout.terms.and') }} <a href="{{ localizedRoute('privacy-policy') }}" target="_blank" class="text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 transition-colors">{{ __('checkout.terms.privacy_policy') }}</a>
                        </label>
                    </div>
                    
                    <div class="text-center">
                        <a href="{{ localizedRoute('subscriptions.index') }}" class="inline-block text-xs uppercase tracking-widest text-olive-600 hover:text-dark-800 border-b border-olive-400 hover:border-dark-800 pb-1 transition-colors">
                            {{ __('checkout.buttons.back_to_configurator') }}
                        </a>
                    </div>
                </div>

                <!-- Trust Indicators - On Olive -->
                <div class="mt-10 pt-6 border-t border-dark-800 space-y-2">
                    <div class="text-xs uppercase tracking-widest text-olive-600">
                        <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                        {{ __('checkout.trust.no_commitment') }}
                    </div>
                    <div class="text-xs uppercase tracking-widest text-olive-600">
                        <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                        {{ __('checkout.trust.fresh_coffee') }}
                    </div>
                    <div class="text-xs uppercase tracking-widest text-olive-600">
                        <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                        {{ __('checkout.trust.free_shipping_always') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://widget.packeta.com/v6/www/js/library.js"></script>
<script>
// Currency formatting helper
const isEur = {{ \App\Helpers\CurrencyHelper::isEur() ? 'true' : 'false' }};

window.formatCurrency = function(amount) {
    if (isEur) {
        return '€' + amount.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 0});
    } else {
        return amount.toLocaleString('cs-CZ') + ' Kč';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Dynamic shipping calculation for subscriptions
    let currentPacketaVendors = @json($packetaVendors ?? []);
    const subscriptionPrice = {{ $price }};
    
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
                <button type="button" id="select-point-btn" class="group flex items-center gap-3 text-dark-800 hover:text-primary-500 transition-colors">
                    <span class="text-xs uppercase tracking-widest border-b border-dark-800 group-hover:border-primary-500 pb-0.5">{{ __('checkout.pickup_point.select') }}</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>
            `;
            // Re-attach event listener
            document.getElementById('select-point-btn').addEventListener('click', openPacketaWidget);
        }
        
        // Show loading state
        const shippingCostElement = document.getElementById('shipping-cost');
        const totalCostElement = document.getElementById('total-cost');
        
        if (shippingCostElement) {
            shippingCostElement.innerHTML = '<span class="text-xs uppercase tracking-widest text-olive-500">{{ __('checkout.shipping_calculating') }}</span>';
        }
        
        // AJAX request to get carrier and shipping cost for this country
        fetch('{{ route("api.calculate-shipping") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                country: country,
                subtotal: subscriptionPrice,
                is_subscription: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                // Update Packeta vendors for widget
                currentPacketaVendors = data.packeta_vendors || [];
                
                // Update shipping cost display
                const shippingCost = parseFloat(data.shipping) || 0;
                if (shippingCostElement) {
                    if (shippingCost === 0) {
                        shippingCostElement.innerHTML = '<span class="text-dark-800">{{ $currentLocale === "en" ? "FREE" : "ZDARMA" }}</span>';
                    } else {
                        shippingCostElement.innerHTML = `<span class="text-dark-800">${data.shipping_formatted}</span>`;
                    }
                }
                
                // Update total cost
                const frequencyText = '{{ $frequencyText }}';
                const newTotal = subscriptionPrice + shippingCost;
                if (totalCostElement) {
                    totalCostElement.textContent = formatCurrency(newTotal);
                }
            } else {
                alert('{{ __('checkout.errors.subscription_country_unavailable') }}');
                if (shippingCostElement) {
                    shippingCostElement.innerHTML = '<span class="text-xs uppercase tracking-widest text-olive-500">{{ __('checkout.shipping_unavailable') }}</span>';
                }
            }
        })
        .catch(error => {
            console.error('Error getting carrier info:', error);
            if (shippingCostElement) {
                shippingCostElement.innerHTML = '<span class="text-xs uppercase tracking-widest text-red-500">{{ __('checkout.shipping_error') }}</span>';
            }
        });
    });
    
    // Packeta Widget Configuration
    const packetaApiKey = '{{ config("services.packeta.widget_key") }}';
    
    function openPacketaWidget() {
        if (!packetaApiKey) {
            alert('{{ __('checkout.pickup_point.info') }}');
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
            console.log('Subscription widget vendors filter:', widgetOptions.vendors);
        }

        Packeta.Widget.pick(packetaApiKey, function(point) {
            if (point) {
                // Fill hidden fields with selected point data
                document.getElementById('packeta_point_id').value = point.id;
                document.getElementById('packeta_point_name').value = point.name;
                
                // Store carrier ID and carrierPickupPointId for Carriers PUDOs (international)
                if (point.carrierId) {
                    document.getElementById('carrier_id').value = point.carrierId;
                }
                if (point.carrierPickupPointId) {
                    document.getElementById('carrier_pickup_point').value = point.carrierPickupPointId;
                }
                
                // Format address
                let address = point.street;
                if (point.city) {
                    address += ', ' + (point.zip ? point.zip + ' ' : '') + point.city;
                }
                document.getElementById('packeta_point_address').value = address;

                // Update UI to show selected point - Swiss Style
                const selectionDiv = document.getElementById('packeta-selection');
                selectionDiv.innerHTML = `
                    <div id="selected-point" class="py-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <span class="text-xs uppercase tracking-widest text-olive-500 block mb-2">{{ __('checkout.pickup_point.selected') }}</span>
                                <p class="font-display text-lg text-dark-800 uppercase tracking-tight">${point.name}</p>
                                <p class="text-xs uppercase tracking-widest text-warm-500 mt-1">${address}</p>
                            </div>
                            <button type="button" id="change-point-btn" class="text-xs uppercase tracking-widest text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500 pb-0.5 transition-colors whitespace-nowrap ml-4">
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
