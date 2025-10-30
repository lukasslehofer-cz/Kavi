@extends('layouts.app')

@section('title', 'Pokladna - Kavi Coffee')

@section('content')
<!-- Hero Header - Minimal -->
<div class="relative bg-gray-100 py-12 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 tracking-tight">Dokončení objednávky</h1>
                <p class="text-lg text-gray-600 font-light">Ještě pár informací a vaše káva bude na cestě k vám</p>
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
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <!-- Contact Information - Minimal -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Kontaktní údaje</h2>
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
                                <h3 class="font-medium text-gray-900 mb-1.5">Máte již účet?</h3>
                                <p class="text-sm text-gray-600 mb-3 font-light">Přihlaste se pro rychlejší dokončení objednávky nebo vám pošleme přihlašovací odkaz.</p>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout.index')) }}" class="inline-block bg-white hover:bg-gray-50 text-blue-600 font-medium px-5 py-2 rounded-full border border-blue-200 hover:border-blue-300 transition-all text-sm">
                                        Přihlásit se
                                    </a>
                                    <button type="button" onclick="showMagicLinkModal()" class="inline-flex items-center gap-1 bg-white hover:bg-gray-50 text-gray-700 font-medium px-5 py-2 rounded-full border border-gray-200 hover:border-gray-300 transition-all text-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        Poslat přihlašovací odkaz
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endguest
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Jméno a příjmení <span class="text-red-500">*</span>
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
                                Telefon @guest<span class="text-gray-500">(volitelné)</span>@else<span class="text-red-500">*</span>@endguest
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
                            <p class="text-xs text-gray-600 mt-1">Telefon pomůže doručovací službě při řešení případných problémů.</p>
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
                        <h2 class="text-xl font-bold text-gray-900">Fakturační adresa</h2>
                    </div>
                    
                    <div class="space-y-5">
                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Ulice a číslo popisné <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="billing_address" 
                                name="billing_address" 
                                value="{{ old('billing_address', auth()->user()->address ?? '') }}" 
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                placeholder="Např. Karlova 123"
                            >
                            @error('billing_address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-2">
                                    Město <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="billing_city" 
                                    name="billing_city" 
                                    value="{{ old('billing_city', auth()->user()->city ?? '') }}" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    placeholder="Např. Praha"
                                >
                                @error('billing_city')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="billing_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    PSČ <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="billing_postal_code" 
                                    name="billing_postal_code" 
                                    value="{{ old('billing_postal_code', auth()->user()->postal_code ?? '') }}" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    placeholder="123 45"
                                >
                                @error('billing_postal_code')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-2">
                                    Země <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="billing_country" 
                                    name="billing_country" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                >
                                    <option value="">Vyberte zemi</option>
                                    <option value="AT" {{ old('billing_country', auth()->user()->country ?? '') == 'AT' ? 'selected' : '' }}>Rakousko</option>
                                    <option value="BE" {{ old('billing_country', auth()->user()->country ?? '') == 'BE' ? 'selected' : '' }}>Belgie</option>
                                    <option value="BG" {{ old('billing_country', auth()->user()->country ?? '') == 'BG' ? 'selected' : '' }}>Bulharsko</option>
                                    <option value="HR" {{ old('billing_country', auth()->user()->country ?? '') == 'HR' ? 'selected' : '' }}>Chorvatsko</option>
                                    <option value="CY" {{ old('billing_country', auth()->user()->country ?? '') == 'CY' ? 'selected' : '' }}>Kypr</option>
                                    <option value="CZ" {{ old('billing_country', auth()->user()->country ?? 'CZ') == 'CZ' ? 'selected' : '' }}>Česká republika</option>
                                    <option value="DK" {{ old('billing_country', auth()->user()->country ?? '') == 'DK' ? 'selected' : '' }}>Dánsko</option>
                                    <option value="EE" {{ old('billing_country', auth()->user()->country ?? '') == 'EE' ? 'selected' : '' }}>Estonsko</option>
                                    <option value="FI" {{ old('billing_country', auth()->user()->country ?? '') == 'FI' ? 'selected' : '' }}>Finsko</option>
                                    <option value="FR" {{ old('billing_country', auth()->user()->country ?? '') == 'FR' ? 'selected' : '' }}>Francie</option>
                                    <option value="DE" {{ old('billing_country', auth()->user()->country ?? '') == 'DE' ? 'selected' : '' }}>Německo</option>
                                    <option value="GR" {{ old('billing_country', auth()->user()->country ?? '') == 'GR' ? 'selected' : '' }}>Řecko</option>
                                    <option value="HU" {{ old('billing_country', auth()->user()->country ?? '') == 'HU' ? 'selected' : '' }}>Maďarsko</option>
                                    <option value="IE" {{ old('billing_country', auth()->user()->country ?? '') == 'IE' ? 'selected' : '' }}>Irsko</option>
                                    <option value="IT" {{ old('billing_country', auth()->user()->country ?? '') == 'IT' ? 'selected' : '' }}>Itálie</option>
                                    <option value="LV" {{ old('billing_country', auth()->user()->country ?? '') == 'LV' ? 'selected' : '' }}>Lotyšsko</option>
                                    <option value="LT" {{ old('billing_country', auth()->user()->country ?? '') == 'LT' ? 'selected' : '' }}>Litva</option>
                                    <option value="LU" {{ old('billing_country', auth()->user()->country ?? '') == 'LU' ? 'selected' : '' }}>Lucembursko</option>
                                    <option value="MT" {{ old('billing_country', auth()->user()->country ?? '') == 'MT' ? 'selected' : '' }}>Malta</option>
                                    <option value="NL" {{ old('billing_country', auth()->user()->country ?? '') == 'NL' ? 'selected' : '' }}>Nizozemsko</option>
                                    <option value="PL" {{ old('billing_country', auth()->user()->country ?? '') == 'PL' ? 'selected' : '' }}>Polsko</option>
                                    <option value="PT" {{ old('billing_country', auth()->user()->country ?? '') == 'PT' ? 'selected' : '' }}>Portugalsko</option>
                                    <option value="RO" {{ old('billing_country', auth()->user()->country ?? '') == 'RO' ? 'selected' : '' }}>Rumunsko</option>
                                    <option value="SK" {{ old('billing_country', auth()->user()->country ?? '') == 'SK' ? 'selected' : '' }}>Slovensko</option>
                                    <option value="SI" {{ old('billing_country', auth()->user()->country ?? '') == 'SI' ? 'selected' : '' }}>Slovinsko</option>
                                    <option value="ES" {{ old('billing_country', auth()->user()->country ?? '') == 'ES' ? 'selected' : '' }}>Španělsko</option>
                                    <option value="SE" {{ old('billing_country', auth()->user()->country ?? '') == 'SE' ? 'selected' : '' }}>Švédsko</option>
                                </select>
                                @error('billing_country')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Packeta Pickup Point - Minimal -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Výběr výdejního místa</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Hidden fields for Packeta data -->
                        <input type="hidden" id="packeta_point_id" name="packeta_point_id" value="{{ old('packeta_point_id', auth()->user()->packeta_point_id ?? '') }}">
                        <input type="hidden" id="packeta_point_name" name="packeta_point_name" value="{{ old('packeta_point_name', auth()->user()->packeta_point_name ?? '') }}">
                        <input type="hidden" id="packeta_point_address" name="packeta_point_address" value="{{ old('packeta_point_address', auth()->user()->packeta_point_address ?? '') }}">

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
                                            <span class="font-medium text-gray-900 text-sm">Vybrané výdejní místo:</span>
                                        </div>
                                        <p class="text-gray-900 font-medium ml-6" id="selected-point-name">{{ old('packeta_point_name', auth()->user()->packeta_point_name ?? '') }}</p>
                                        <p class="text-sm text-gray-600 ml-6 font-light" id="selected-point-address">{{ old('packeta_point_address', auth()->user()->packeta_point_address ?? '') }}</p>
                                    </div>
                                    <button type="button" id="change-point-btn" class="text-sm bg-white hover:bg-gray-50 text-[#ba1b02] font-medium px-4 py-2 rounded-full border border-gray-200 whitespace-nowrap ml-4 transition-colors">
                                        Změnit
                                    </button>
                                </div>
                            </div>
                            @else
                            <!-- Select button - Minimal -->
                            <button type="button" id="select-point-btn" class="w-full flex items-center justify-center gap-2 bg-[#ba1b02] hover:bg-[#a01701] text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Vybrat výdejní místo Zásilkovna</span>
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
                                Káva vám bude doručena na vybrané výdejní místo Zásilkovny
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Method - Minimal -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Způsob platby</h2>
                    </div>
                    
                    <!-- Single Payment Method Info - Minimal -->
                    <div class="p-4 border border-primary-400 bg-primary-50 rounded-xl">
                        <input type="hidden" name="payment_method" value="card">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-gray-900 mb-1">Platební kartou</div>
                                <div class="text-sm text-gray-600 mb-3 font-light">Po odeslání objednávky budete přesměrováni na bezpečnou platební bránu</div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs text-gray-500 font-medium">Akceptujeme:</span>
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
                        <h2 class="text-xl font-bold text-gray-900">Poznámka <span class="text-base font-light text-gray-500">(volitelné)</span></h2>
                    </div>
                    
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="4" 
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all"
                        placeholder="Např. 'Prosím zvonit na 2. patro' nebo 'Nechat u vrátnice'"
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
                    <h3 class="text-xl font-bold text-gray-900">Souhrn objednávky</h3>
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
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $item['product']->name }}</p>
                            <p class="text-xs text-gray-500 font-light">{{ $item['quantity'] }}× {{ number_format($item['product']->price, 0, ',', ' ') }} Kč</p>
                        </div>
                        <p class="text-sm font-bold text-gray-900">{{ number_format($item['subtotal'], 0, ',', ' ') }} Kč</p>
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
                                    <span class="text-sm font-medium text-green-800">Kupón aplikován</span>
                                </div>
                                <a href="{{ route('checkout.index', ['remove_coupon' => 1]) }}" class="text-xs text-red-600 hover:text-red-800 hover:underline">
                                    Odebrat
                                </a>
                            </div>
                            <p class="text-sm text-green-700 font-mono font-bold">{{ $appliedCoupon->code }}</p>
                            <p class="text-xs text-green-600 mt-1">{{ $appliedCoupon->getOrderDiscountDescription() }}</p>
                        </div>
                    @else
                        <details class="group" {{ request()->has('coupon_code') ? 'open' : '' }}>
                            <summary class="flex items-center justify-between cursor-pointer p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <span class="text-sm font-medium text-gray-700">Mám slevový kupón</span>
                                <svg class="w-5 h-5 text-gray-600 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <form method="GET" action="{{ route('checkout.index') }}" class="mt-3">
                                <div class="flex gap-2">
                                    <input type="text" name="coupon_code" placeholder="SLEVOVYKOD" 
                                        class="flex-1 px-4 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 uppercase text-sm"
                                        value="{{ request('coupon_code') }}">
                                    <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                        Použít
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
                        <dt class="text-gray-600 text-sm font-light">Doprava:</dt>
                        <dd class="font-bold">
                            <span id="shipping-cost">
                                @if($shipping == 0)
                                <span class="text-green-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Zdarma
                                    @if($appliedCoupon && $appliedCoupon->free_shipping)
                                    <span class="text-xs">(kupón)</span>
                                    @endif
                                </span>
                                @else
                                <span class="text-gray-900">{{ number_format($shipping, 0, ',', ' ') }} Kč</span>
                                @endif
                            </span>
                        </dd>
                    </div>
                    
                    <!-- Coupon discount (prominently displayed) -->
                    @if(($discount ?? 0) > 0)
                    <div class="flex justify-between items-center py-3 border-b-2 border-green-200 bg-green-50 -mx-6 px-6">
                        <dt class="text-green-700 font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>Sleva {{ $appliedCoupon->code ?? 'kupón' }}:</span>
                        </dt>
                        <dd class="font-bold text-green-600 text-lg">-{{ number_format($discount, 0, ',', ' ') }} Kč</dd>
                    </div>
                    @endif
                    
                    <!-- Subtotal without VAT -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-600 text-sm font-light">Mezisoučet (bez DPH):</dt>
                        <dd class="font-bold text-gray-900">{{ number_format($totalWithoutVat, 2, ',', ' ') }} Kč</dd>
                    </div>
                    
                    <!-- VAT -->
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-600 text-sm font-light">DPH (21%):</dt>
                        <dd class="font-bold text-gray-900">{{ number_format($vat, 2, ',', ' ') }} Kč</dd>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-4 mt-2">
                        <div class="flex justify-between items-center">
                            <dt class="font-bold text-gray-900 text-lg">Celkem:</dt>
                            <dd class="text-3xl font-bold text-gray-900" id="total-cost">
                                {{ number_format($totalWithVat, 0, ',', ' ') }} Kč
                            </dd>
                        </div>
                        <p class="text-xs text-gray-500 text-right mt-1 font-light">(včetně DPH)</p>
                    </div>
                </dl>

                <button type="submit" form="checkout-form" class="group w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200 mb-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Dokončit objednávku</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </button>

                <div class="flex items-start mb-4 p-3 bg-gray-50 rounded-xl">
                    <input type="checkbox" id="terms" required form="checkout-form" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 mr-2.5 mt-0.5 flex-shrink-0">
                    <label for="terms" class="text-xs text-gray-600 font-light">
                        Souhlasím s <a href="{{ route('terms-of-service') }}" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium underline">obchodními podmínkami</a> 
                        a <a href="{{ route('privacy-policy') }}" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium underline">zásadami ochrany osobních údajů</a>
                    </label>
                </div>

                <a href="{{ route('cart.index') }}" class="block w-full text-center bg-white hover:bg-gray-50 text-gray-900 font-medium px-6 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Zpět do košíku</span>
                </a>

                <!-- Trust Badges - Minimal -->
                <div class="mt-6 pt-6 border-t border-gray-100 space-y-2.5">
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Bezpečná platba</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>14 dní na vrácení</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 font-light">
                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Zákaznická podpora 24/7</span>
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
            <h3 class="text-xl font-bold text-gray-900">Poslat přihlašovací odkaz</h3>
            <button onclick="closeMagicLinkModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="text-gray-600 mb-4">Zadejte váš email a my vám pošleme přihlašovací odkaz.</p>
        <form method="POST" action="{{ route('magic-link.send') }}" id="checkout-magic-link-form">
            @csrf
            <input type="hidden" name="redirect" value="{{ route('checkout.index') }}">
            <div class="mb-4">
                <label for="magic-link-email-input" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="magic-link-email-input" name="email" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="vas@email.cz">
            </div>
            <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all">
                Poslat přihlašovací odkaz
            </button>
        </form>
    </div>
</div>

<script src="https://widget.packeta.com/v6/www/js/library.js"></script>
<script>
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

// Add form id to the form element
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('checkout.store') }}"]');
    if (form) {
        form.id = 'checkout-form';
    }

    // Dynamic shipping calculation
    let currentPacketaCarrier = '{{ $packetaCarrierId ?? "" }}';
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
                    Vybrat výdejní místo
                </button>
            `;
            // Re-attach event listener
            document.getElementById('select-point-btn').addEventListener('click', openPacketaWidget);
        }
        
        // Show loading state
        if (shippingCostElement) {
            shippingCostElement.textContent = 'Počítám...';
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
                    totalElement.textContent = new Intl.NumberFormat('cs-CZ').format(newTotal) + ' Kč';
                }
                
                // Update Packeta carrier for widget
                currentPacketaCarrier = data.packeta_carrier_id;
            } else {
                alert('Do vybrané země momentálně nedoručujeme.');
                if (shippingCostElement) {
                    shippingCostElement.textContent = '—';
                }
            }
        })
        .catch(error => {
            console.error('Error calculating shipping:', error);
            if (shippingCostElement) {
                shippingCostElement.textContent = 'Chyba';
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
            language: 'cs',
        };
        
        // Add vendor filter if carrier is set
        if (currentPacketaCarrier) {
            widgetOptions.vendors = [currentPacketaCarrier];
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
                                    <span class="font-medium text-gray-900 text-sm">Vybrané výdejní místo:</span>
                                </div>
                                <p class="text-gray-900 font-medium ml-6">${point.name}</p>
                                <p class="text-sm text-gray-600 ml-6 font-light">${address}</p>
                            </div>
                            <button type="button" id="change-point-btn" class="text-sm bg-white hover:bg-gray-50 text-[#ba1b02] font-medium px-4 py-2 rounded-full border border-gray-200 whitespace-nowrap ml-4 transition-colors">
                                Změnit
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

