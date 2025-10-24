@extends('layouts.app')

@section('title', 'Pokladna - Kávové předplatné')

@section('content')
<div class="bg-bluegray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <ol class="flex items-center space-x-2 text-dark-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-500 transition-colors">Domů</a></li>
                <li class="text-dark-400">/</li>
                <li><a href="{{ route('subscriptions.index') }}" class="hover:text-primary-500 transition-colors">Předplatné</a></li>
                <li class="text-dark-400">/</li>
                <li class="text-dark-800 font-medium">Pokladna</li>
            </ol>
        </nav>

        <h1 class="font-display text-4xl md:text-5xl font-bold text-dark-800 mb-2">
            Dokončení objednávky předplatného
        </h1>
        <p class="text-dark-600">Ještě pár informací a vaše káva bude pravidelně na cestě k vám</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('subscriptions.checkout.process') }}" method="POST" id="subscription-checkout-form" class="space-y-6">
                @csrf
                
                <!-- Contact Information -->
                <div class="card p-8">
                    <h2 class="font-display text-2xl font-bold text-dark-800 mb-6">Kontaktní údaje</h2>
                    
                    @guest
                    <!-- Login option for guests -->
                    <div class="mb-6 bg-primary-50  p-6 border-2 border-primary-200">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="font-bold text-dark-800 mb-2">Máte již účet?</h3>
                                <p class="text-sm text-dark-600 mb-3">Přihlaste se pro rychlejší dokončení objednávky.</p>
                                <a href="{{ route('login') }}?redirect={{ urlencode(route('subscriptions.checkout')) }}" class="btn btn-outline !px-4 !py-2 text-sm">
                                    Přihlásit se
                                </a>
                            </div>
                        </div>
                    </div>
                    @endguest
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-dark-700 mb-2">
                                Jméno a příjmení <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', auth()->user()->name ?? '') }}" 
                                required
                                class="input"
                            >
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-dark-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', auth()->user()->email ?? '') }}" 
                                required
                                class="input"
                            >
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @guest
                            <p class="text-xs text-dark-600 mt-1">Na tento email vám zašleme potvrzení a odkaz pro dokončení registrace.</p>
                            @endguest
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-dark-700 mb-2">
                                Telefon @guest<span class="text-dark-500">(volitelné)</span>@else<span class="text-red-500">*</span>@endguest
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                @auth required @endauth
                                class="input"
                                placeholder="+420 123 456 789"
                            >
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="card p-8">
                    <h2 class="font-display text-2xl font-bold text-dark-800 mb-6">Fakturační adresa</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-dark-700 mb-2">
                                Ulice a číslo popisné <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="billing_address" 
                                name="billing_address" 
                                value="{{ old('billing_address', auth()->user()->address ?? '') }}" 
                                required
                                class="input"
                                placeholder="Např. Karlova 123"
                            >
                            @error('billing_address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="billing_city" class="block text-sm font-medium text-dark-700 mb-2">
                                    Město <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="billing_city" 
                                    name="billing_city" 
                                    value="{{ old('billing_city', auth()->user()->city ?? '') }}" 
                                    required
                                    class="input"
                                    placeholder="Např. Praha"
                                >
                                @error('billing_city')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="billing_postal_code" class="block text-sm font-medium text-dark-700 mb-2">
                                    PSČ <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="billing_postal_code" 
                                    name="billing_postal_code" 
                                    value="{{ old('billing_postal_code', auth()->user()->postal_code ?? '') }}" 
                                    required
                                    class="input"
                                    placeholder="123 45"
                                >
                                @error('billing_postal_code')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Packeta Pickup Point -->
                <div class="card p-8">
                    <h2 class="font-display text-2xl font-bold text-dark-800 mb-6">Výběr výdejního místa</h2>
                    
                    <div class="space-y-4">
                        <!-- Hidden fields for Packeta data -->
                        <input type="hidden" id="packeta_point_id" name="packeta_point_id" value="{{ old('packeta_point_id', auth()->user()->packeta_point_id ?? '') }}">
                        <input type="hidden" id="packeta_point_name" name="packeta_point_name" value="{{ old('packeta_point_name', auth()->user()->packeta_point_name ?? '') }}">
                        <input type="hidden" id="packeta_point_address" name="packeta_point_address" value="{{ old('packeta_point_address', auth()->user()->packeta_point_address ?? '') }}">

                        <!-- Packeta selection display -->
                        <div id="packeta-selection">
                            @if(old('packeta_point_id', auth()->user()->packeta_point_id ?? ''))
                            <!-- Selected point display -->
                            <div id="selected-point" class="p-4 bg-primary-50 border-2 border-primary-500 rounded-xl">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-5 h-5 text-primary-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-semibold text-dark-800">Vybrané výdejní místo:</span>
                                        </div>
                                        <p class="text-dark-800 font-medium ml-7" id="selected-point-name">{{ old('packeta_point_name', auth()->user()->packeta_point_name ?? '') }}</p>
                                        <p class="text-sm text-dark-600 ml-7" id="selected-point-address">{{ old('packeta_point_address', auth()->user()->packeta_point_address ?? '') }}</p>
                                    </div>
                                    <button type="button" id="change-point-btn" class="text-sm text-primary-500 hover:text-primary-600 underline whitespace-nowrap ml-4">
                                        Změnit
                                    </button>
                                </div>
                            </div>
                            @else
                            <!-- Select button -->
                            <button type="button" id="select-point-btn" class="btn btn-primary w-full">
                                <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                Vybrat výdejní místo Zásilkovna
                            </button>
                            @endif
                        </div>

                        @error('packeta_point_id')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror

                        <p class="text-sm text-dark-600">
                            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Káva vám bude doručena na vybrané výdejní místo Zásilkovny
                        </p>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card p-8">
                    <h2 class="font-display text-2xl font-bold text-dark-800 mb-6">Způsob platby</h2>
                    
                    <div class="space-y-4">
                        <label class="flex items-start p-4 border-2 border-bluegray-200  cursor-pointer hover:border-primary-500 transition-colors has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                            <input type="radio" name="payment_method" value="card" checked required class="mt-1 w-5 h-5 text-primary-500">
                            <div class="ml-4 flex-1">
                                <div class="font-semibold text-dark-800">Platební kartou</div>
                                <div class="text-sm text-dark-600 mt-1">Visa, Mastercard, Apple Pay, Google Pay</div>
                            </div>
                            <svg class="w-12 h-8" viewBox="0 0 48 32" fill="none">
                                <rect width="48" height="32" rx="4" fill="#1E3A8A"/>
                                <circle cx="18" cy="16" r="8" fill="#EB001B"/>
                                <circle cx="30" cy="16" r="8" fill="#FF5F00" fill-opacity="0.8"/>
                            </svg>
                        </label>

                        <label class="flex items-start p-4 border-2 border-bluegray-200  cursor-pointer hover:border-primary-500 transition-colors has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                            <input type="radio" name="payment_method" value="transfer" required class="mt-1 w-5 h-5 text-primary-500">
                            <div class="ml-4 flex-1">
                                <div class="font-semibold text-dark-800">Bankovním převodem</div>
                                <div class="text-sm text-dark-600 mt-1">Zboží odešleme po připsání platby</div>
                            </div>
                            <svg class="w-12 h-8" viewBox="0 0 48 32" fill="none">
                                <rect width="48" height="32" rx="4" fill="#10B981"/>
                                <path d="M24 8L16 16L24 24M24 16H32" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Notes -->
                <div class="card p-8">
                    <h2 class="font-display text-2xl font-bold text-dark-800 mb-6">Poznámka k dodání (volitelné)</h2>
                    
                    <textarea 
                        id="delivery_notes" 
                        name="delivery_notes" 
                        rows="4" 
                        class="input"
                        placeholder="Např. 'Prosím zvonit na 2. patro' nebo 'Nechat u vrátnice'"
                    >{{ old('delivery_notes') }}</textarea>
                    @error('delivery_notes')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="card p-8 sticky top-24">
                <h3 class="font-display text-2xl font-bold text-dark-800 mb-6">Souhrn předplatného</h3>
                
                <!-- Subscription Details -->
                <div class="bg-bluegray-50  p-6 mb-6">
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-start">
                            <span class="text-dark-600 font-medium">Množství:</span>
                            <span class="font-bold text-right">{{ $configuration['amount'] }} balení<br><span class="text-xs font-normal text-dark-500">({{ $configuration['cups'] }} šálky/den)</span></span>
                        </div>
                        
                        <div class="border-t border-bluegray-200 pt-4">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-dark-600 font-medium">Typ kávy:</span>
                                <span class="font-bold text-right">
                                    @if($configuration['type'] === 'espresso')
                                        @if($configuration['isDecaf'] && isset($configuration['mix']['espressoDecaf']) && $configuration['mix']['espressoDecaf'] > 0)
                                            Espresso + Decaf
                                        @else
                                            Espresso
                                        @endif
                                    @elseif($configuration['type'] === 'filter')
                                        @if($configuration['isDecaf'] && isset($configuration['mix']['filterDecaf']) && $configuration['mix']['filterDecaf'] > 0)
                                            Filter + Decaf
                                        @else
                                            Filter
                                        @endif
                                    @else
                                        Kombinace
                                    @endif
                                </span>
                            </div>
                            
                            @php
                                $showMixDetails = false;
                                if ($configuration['type'] === 'mix') {
                                    $showMixDetails = true;
                                } elseif ($configuration['type'] === 'espresso' && $configuration['isDecaf']) {
                                    $showMixDetails = isset($configuration['mix']['espresso']) && $configuration['mix']['espresso'] > 0 && 
                                                     isset($configuration['mix']['espressoDecaf']) && $configuration['mix']['espressoDecaf'] > 0;
                                } elseif ($configuration['type'] === 'filter' && $configuration['isDecaf']) {
                                    $showMixDetails = isset($configuration['mix']['filter']) && $configuration['mix']['filter'] > 0 && 
                                                     isset($configuration['mix']['filterDecaf']) && $configuration['mix']['filterDecaf'] > 0;
                                }
                            @endphp
                            
                            @if($showMixDetails)
                            <div class="mt-2 pl-4 space-y-1 text-xs text-dark-600">
                                @if(isset($configuration['mix']['espresso']) && $configuration['mix']['espresso'] > 0)
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    {{ $configuration['mix']['espresso'] }}× Espresso
                                </div>
                                @endif
                                @if(isset($configuration['mix']['espressoDecaf']) && $configuration['mix']['espressoDecaf'] > 0)
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    {{ $configuration['mix']['espressoDecaf'] }}× Espresso Decaf
                                </div>
                                @endif
                                @if(isset($configuration['mix']['filter']) && $configuration['mix']['filter'] > 0)
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    {{ $configuration['mix']['filter'] }}× Filter
                                </div>
                                @endif
                                @if(isset($configuration['mix']['filterDecaf']) && $configuration['mix']['filterDecaf'] > 0)
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    {{ $configuration['mix']['filterDecaf'] }}× Filter Decaf
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-between items-center border-t border-bluegray-200 pt-4">
                            <span class="text-dark-600 font-medium">Frekvence:</span>
                            <span class="font-bold">{{ $configuration['frequencyText'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Date Info -->
                <div class="bg-primary-50 border-2 border-primary-200 rounded-lg p-6 mb-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="font-semibold text-dark-800 mb-2">Informace o dodání</h3>
                            <p class="text-sm text-dark-700 mb-2">
                                <strong>{{ $shippingInfo['cutoff_message'] }}</strong>
                            </p>
                            <p class="text-xs text-dark-600">
                                Rozesílka probíhá vždy <strong>20. den v měsíci</strong>. Objednávky do 15. v měsíci jsou zahrnuty v aktuální rozesílce, 
                                objednávky od 16. dne jsou zahrnuty až v následující rozesílce.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Price Summary -->
                <dl class="space-y-4 mb-6">
                    <div class="flex justify-between items-center">
                        <dt class="text-dark-600">{{ $configuration['amount'] }}× balení kávy (bez DPH):</dt>
                        <dd class="font-semibold text-dark-800">{{ number_format($priceWithoutVat, 2, ',', ' ') }} Kč</dd>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <dt class="text-dark-600">DPH (21%):</dt>
                        <dd class="font-semibold text-dark-800">{{ number_format($vat, 2, ',', ' ') }} Kč</dd>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <dt class="text-dark-600">Doprava:</dt>
                        <dd class="font-semibold">
                            <span class="text-green-600">Zdarma</span>
                        </dd>
                    </div>

                    <div class="border-t-2 border-bluegray-200 pt-4">
                        <div class="flex justify-between items-center">
                            <dt class="font-display font-bold text-dark-800 text-lg">Celkem (včetně DPH):</dt>
                            <dd class="font-bold text-primary-500 text-3xl">
                                {{ number_format($price, 0, ',', ' ') }} Kč
                            </dd>
                        </div>
                        <p class="text-xs text-dark-600 text-right mt-1">{{ $configuration['frequencyText'] }}</p>
                    </div>
                </dl>

                <button type="submit" form="subscription-checkout-form" class="btn btn-primary w-full text-center mb-3">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Dokončit objednávku
                </button>

                <div class="flex items-start mb-4">
                    <input type="checkbox" id="terms" required form="subscription-checkout-form" class="w-5 h-5 text-primary-500 border-gray-300 focus:ring-primary-500 mr-3 mt-0.5">
                    <label for="terms" class="text-sm text-dark-700">
                        Souhlasím s <a href="#" class="text-primary-500 hover:text-primary-600 underline">obchodními podmínkami</a> 
                        a <a href="#" class="text-primary-500 hover:text-primary-600 underline">zásadami ochrany osobních údajů</a>
                    </label>
                </div>

                <a href="{{ route('subscriptions.index') }}" class="btn btn-outline w-full text-center">
                    ← Zpět na konfigurátor
                </a>

                <!-- Trust Badges -->
                <div class="mt-6 pt-6 border-t border-bluegray-200 space-y-3">
                    <div class="flex items-center text-sm text-dark-600">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Bez závazků - kdykoli zrušte</span>
                    </div>
                    <div class="flex items-center text-sm text-dark-600">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Čerstvě pražená káva</span>
                    </div>
                    <div class="flex items-center text-sm text-dark-600">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Doprava vždy zdarma</span>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<script src="https://widget.packeta.com/v6/www/js/library.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Packeta Widget Configuration
    const packetaApiKey = '{{ config("services.packeta.widget_key") }}';
    
    function openPacketaWidget() {
        if (!packetaApiKey) {
            alert('Packeta widget není správně nakonfigurován. Kontaktujte administrátora.');
            return;
        }

        Packeta.Widget.pick(packetaApiKey, function(point) {
            if (point) {
                // Fill hidden fields with selected point data
                document.getElementById('packeta_point_id').value = point.id;
                document.getElementById('packeta_point_name').value = point.name;
                
                // Format address
                let address = point.street;
                if (point.city) {
                    address += ', ' + (point.zip ? point.zip + ' ' : '') + point.city;
                }
                document.getElementById('packeta_point_address').value = address;

                // Update UI to show selected point
                const selectionDiv = document.getElementById('packeta-selection');
                selectionDiv.innerHTML = `
                    <div id="selected-point" class="p-4 bg-primary-50 border-2 border-primary-500 rounded-xl">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-primary-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold text-dark-800">Vybrané výdejní místo:</span>
                                </div>
                                <p class="text-dark-800 font-medium ml-7">${point.name}</p>
                                <p class="text-sm text-dark-600 ml-7">${address}</p>
                            </div>
                            <button type="button" id="change-point-btn" class="text-sm text-primary-500 hover:text-primary-600 underline whitespace-nowrap ml-4">
                                Změnit
                            </button>
                        </div>
                    </div>
                `;

                // Re-attach event listener to the new change button
                document.getElementById('change-point-btn').addEventListener('click', openPacketaWidget);
            }
        }, {
            country: 'cz',
            language: 'cs',
            // Můžete zde přidat vendors pro omezení dopravců, např:
            // vendors: ['packeta', 'zasilkovna'],
        });
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

