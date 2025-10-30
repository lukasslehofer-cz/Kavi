@extends('layouts.dashboard')

@section('title', 'Můj profil - Kavi Coffee')

@section('content')
<div class="space-y-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-base font-bold text-green-900 mb-1">Úspěch!</h3>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-base font-bold text-red-900 mb-1">Chyba</h3>
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-gray-900 flex items-center justify-center text-white font-bold text-2xl">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900 mb-0.5">{{ auth()->user()->name }}</h1>
                <p class="text-base text-gray-600 font-light">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Osobní údaje</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('dashboard.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-900 mb-2">Celé jméno</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', auth()->user()->name) }}" 
                               class="input @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-900 mb-2">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', auth()->user()->email) }}" 
                               class="input @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-900 mb-2">Telefon</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                               class="input @error('phone') border-red-500 @enderror"
                               placeholder="+420 123 456 789">
                        @error('phone')
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mt-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">
                                <strong>Tip:</strong> Vyplňte si telefon a adresu pro rychlejší objednávky. Údaje budou automaticky předvyplněné v pokladně.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Uložit změny
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Platební metody</h2>
            <p class="text-sm text-gray-600 mt-1 font-light">Správa vašich platebních karet pro předplatné</p>
        </div>
        <div class="p-6">
            @if($paymentMethod)
                <!-- Display current payment method -->
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl mb-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-3 flex-1">
                            <!-- Card brand icon -->
                            <div class="w-12 h-12 rounded-lg bg-gray-900 flex items-center justify-center flex-shrink-0">
                                @if(strtolower($paymentMethod['brand']) === 'visa')
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.5 7h15v10h-15z" fill="none" stroke="currentColor" stroke-width="1.5"/>
                                        <text x="6" y="14" font-size="6" font-weight="bold" fill="currentColor">VISA</text>
                                    </svg>
                                @elseif(strtolower($paymentMethod['brand']) === 'mastercard')
                                    <svg class="w-8 h-8" viewBox="0 0 24 24">
                                        <circle cx="9" cy="12" r="5" fill="#EB001B"/>
                                        <circle cx="15" cy="12" r="5" fill="#F79E1B"/>
                                        <path d="M12 8.5a5 5 0 00-3 1.5 5 5 0 003 7 5 5 0 003-7 5 5 0 00-3-1.5z" fill="#FF5F00"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-900 text-base capitalize">{{ $paymentMethod['brand'] }}</span>
                                    <span class="text-gray-600">••••</span>
                                    <span class="font-medium text-gray-900">{{ $paymentMethod['last4'] }}</span>
                                </div>
                                <p class="text-sm text-gray-600">
                                    Vyprší {{ str_pad($paymentMethod['exp_month'], 2, '0', STR_PAD_LEFT) }}/{{ $paymentMethod['exp_year'] }}
                                </p>
                                
                                @php
                                    $expDate = \Carbon\Carbon::create($paymentMethod['exp_year'], $paymentMethod['exp_month'], 1)->endOfMonth();
                                    $daysUntilExpiry = now()->diffInDays($expDate, false);
                                @endphp
                                
                                @if($daysUntilExpiry < 0)
                                    <span class="inline-flex items-center gap-1 mt-2 px-2 py-1 text-xs font-medium text-red-700 bg-red-50 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Karta vypršela
                                    </span>
                                @elseif($daysUntilExpiry <= 30)
                                    <span class="inline-flex items-center gap-1 mt-2 px-2 py-1 text-xs font-medium text-orange-700 bg-orange-50 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Brzy vyprší
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('dashboard.payment-methods.manage') }}" 
                   class="w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Změnit platební metodu
                </a>
            @else
                <!-- No payment method -->
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl mb-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-blue-800 font-medium mb-1">Zatím nemáte nastavenou žádnou platební metodu</p>
                            <p class="text-xs text-blue-700">
                                Při vytvoření předplatného nebo první platbě bude karta automaticky uložena pro budoucí použití.
                            </p>
                        </div>
                    </div>
                </div>
                
                @if(auth()->user()->stripe_customer_id)
                    <a href="{{ route('dashboard.payment-methods.manage') }}" 
                       class="w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Přidat platební metodu
                    </a>
                @endif
            @endif
        </div>
    </div>

    <!-- Billing Address -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Fakturační adresa</h2>
            <p class="text-sm text-gray-600 mt-1 font-light">Tato adresa bude předvyplněná při objednávkách</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('dashboard.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-900 mb-2">Ulice a číslo popisné</label>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           value="{{ old('address', auth()->user()->address ?? '') }}" 
                           class="input @error('address') border-red-500 @enderror"
                           placeholder="Např. Karlova 123">
                    @error('address')
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-900 mb-2">Město</label>
                        <input type="text" 
                               id="city" 
                               name="city" 
                               value="{{ old('city', auth()->user()->city ?? '') }}" 
                               class="input @error('city') border-red-500 @enderror"
                               placeholder="Např. Praha">
                        @error('city')
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-900 mb-2">PSČ</label>
                        <input type="text" 
                               id="postal_code" 
                               name="postal_code" 
                               value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}" 
                               class="input @error('postal_code') border-red-500 @enderror"
                               placeholder="123 45">
                        @error('postal_code')
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-900 mb-2">Země</label>
                        <select id="country" 
                                name="country" 
                                class="input @error('country') border-red-500 @enderror">
                            <option value="">Vyberte zemi</option>
                            <option value="AT" {{ old('country', auth()->user()->country ?? '') == 'AT' ? 'selected' : '' }}>Rakousko</option>
                            <option value="BE" {{ old('country', auth()->user()->country ?? '') == 'BE' ? 'selected' : '' }}>Belgie</option>
                            <option value="BG" {{ old('country', auth()->user()->country ?? '') == 'BG' ? 'selected' : '' }}>Bulharsko</option>
                            <option value="HR" {{ old('country', auth()->user()->country ?? '') == 'HR' ? 'selected' : '' }}>Chorvatsko</option>
                            <option value="CY" {{ old('country', auth()->user()->country ?? '') == 'CY' ? 'selected' : '' }}>Kypr</option>
                            <option value="CZ" {{ old('country', auth()->user()->country ?? 'CZ') == 'CZ' ? 'selected' : '' }}>Česká republika</option>
                            <option value="DK" {{ old('country', auth()->user()->country ?? '') == 'DK' ? 'selected' : '' }}>Dánsko</option>
                            <option value="EE" {{ old('country', auth()->user()->country ?? '') == 'EE' ? 'selected' : '' }}>Estonsko</option>
                            <option value="FI" {{ old('country', auth()->user()->country ?? '') == 'FI' ? 'selected' : '' }}>Finsko</option>
                            <option value="FR" {{ old('country', auth()->user()->country ?? '') == 'FR' ? 'selected' : '' }}>Francie</option>
                            <option value="DE" {{ old('country', auth()->user()->country ?? '') == 'DE' ? 'selected' : '' }}>Německo</option>
                            <option value="GR" {{ old('country', auth()->user()->country ?? '') == 'GR' ? 'selected' : '' }}>Řecko</option>
                            <option value="HU" {{ old('country', auth()->user()->country ?? '') == 'HU' ? 'selected' : '' }}>Maďarsko</option>
                            <option value="IE" {{ old('country', auth()->user()->country ?? '') == 'IE' ? 'selected' : '' }}>Irsko</option>
                            <option value="IT" {{ old('country', auth()->user()->country ?? '') == 'IT' ? 'selected' : '' }}>Itálie</option>
                            <option value="LV" {{ old('country', auth()->user()->country ?? '') == 'LV' ? 'selected' : '' }}>Lotyšsko</option>
                            <option value="LT" {{ old('country', auth()->user()->country ?? '') == 'LT' ? 'selected' : '' }}>Litva</option>
                            <option value="LU" {{ old('country', auth()->user()->country ?? '') == 'LU' ? 'selected' : '' }}>Lucembursko</option>
                            <option value="MT" {{ old('country', auth()->user()->country ?? '') == 'MT' ? 'selected' : '' }}>Malta</option>
                            <option value="NL" {{ old('country', auth()->user()->country ?? '') == 'NL' ? 'selected' : '' }}>Nizozemsko</option>
                            <option value="PL" {{ old('country', auth()->user()->country ?? '') == 'PL' ? 'selected' : '' }}>Polsko</option>
                            <option value="PT" {{ old('country', auth()->user()->country ?? '') == 'PT' ? 'selected' : '' }}>Portugalsko</option>
                            <option value="RO" {{ old('country', auth()->user()->country ?? '') == 'RO' ? 'selected' : '' }}>Rumunsko</option>
                            <option value="SK" {{ old('country', auth()->user()->country ?? '') == 'SK' ? 'selected' : '' }}>Slovensko</option>
                            <option value="SI" {{ old('country', auth()->user()->country ?? '') == 'SI' ? 'selected' : '' }}>Slovinsko</option>
                            <option value="ES" {{ old('country', auth()->user()->country ?? '') == 'ES' ? 'selected' : '' }}>Španělsko</option>
                            <option value="SE" {{ old('country', auth()->user()->country ?? '') == 'SE' ? 'selected' : '' }}>Švédsko</option>
                        </select>
                        @error('country')
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Uložit změny
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Packeta Pickup Point -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Výdejní místo Zásilkovny</h2>
            <p class="text-sm text-gray-600 mt-1 font-light">Nastavte si preferované výdejní místo pro vaše objednávky</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('dashboard.profile.update') }}" id="packeta-form" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Hidden fields for Packeta data -->
                <input type="hidden" id="packeta_point_id" name="packeta_point_id" value="{{ old('packeta_point_id', auth()->user()->packeta_point_id ?? '') }}">
                <input type="hidden" id="packeta_point_name" name="packeta_point_name" value="{{ old('packeta_point_name', auth()->user()->packeta_point_name ?? '') }}">
                <input type="hidden" id="packeta_point_address" name="packeta_point_address" value="{{ old('packeta_point_address', auth()->user()->packeta_point_address ?? '') }}">

                <!-- Packeta selection display -->
                <div id="packeta-selection">
                    @if(auth()->user()->packeta_point_id)
                    <!-- Selected point display -->
                    <div id="selected-point" class="p-4 bg-primary-50 border border-primary-300 rounded-xl mb-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-1.5">
                                    <svg class="w-4 h-4 text-primary-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 text-sm">Aktuální výdejní místo:</span>
                                </div>
                                <p class="text-gray-900 font-medium ml-6" id="selected-point-name">{{ auth()->user()->packeta_point_name }}</p>
                                <p class="text-sm text-gray-600 ml-6 font-light" id="selected-point-address">{{ auth()->user()->packeta_point_address }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-gray-50 border border-gray-200 p-4 rounded-xl mb-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-gray-600">Zatím nemáte nastavené žádné výdejní místo.</p>
                        </div>
                    </div>
                    @endif

                    <button type="button" id="select-point-btn" class="w-full flex items-center justify-center gap-2 bg-[#ba1b02] hover:bg-[#a01701] text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ auth()->user()->packeta_point_id ? 'Změnit výdejní místo' : 'Vybrat výdejní místo' }}</span>
                    </button>
                </div>

                @error('packeta_point_id')
                <p class="text-red-600 text-sm mt-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Uložit změny
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Změna hesla</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('dashboard.password.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-900 mb-2">Současné heslo</label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="input @error('current_password') border-red-500 @enderror"
                           required>
                    @error('current_password')
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900 mb-2">Nové heslo</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="input @error('password') border-red-500 @enderror"
                               required>
                        @error('password')
                        <p class="text-red-600 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">Potvrdit nové heslo</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="input"
                               required>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">
                                Heslo musí obsahovat minimálně 8 znaků.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Změnit heslo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Account Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-light font-medium mb-1">Celkem objednávek</p>
                    <p class="text-3xl font-bold text-gray-900">{{ auth()->user()->orders()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-light font-medium mb-1">Aktivní předplatné</p>
                    <p class="text-3xl font-bold text-gray-900">{{ auth()->user()->subscriptions()->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-light font-medium mb-1">Členem od</p>
                    <p class="text-xl font-bold text-gray-900">{{ auth()->user()->created_at->format('m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden border border-red-200">
        <div class="bg-red-50 p-6 border-b border-red-200">
            <h2 class="text-xl font-bold text-red-800">Nebezpečná zóna</h2>
        </div>
        <div class="p-6">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Smazat účet</h3>
                    <p class="text-gray-600 font-light">
                        Trvale odstraní váš účet a všechna související data. Tato akce je nevratná.
                    </p>
                </div>
                <button type="button" 
                        onclick="openDeleteAccountModal()"
                        class="bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                    Smazat účet
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteAccountModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-red-50 p-6 border-b border-red-200">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-red-800">Smazání účtu</h3>
                        <p class="text-sm text-red-700 font-light">Tato akce je nevratná</p>
                    </div>
                </div>
                <button type="button" onclick="closeDeleteAccountModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-6">
            <!-- Warning Message -->
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h4 class="text-red-800 font-bold mb-1">POZOR: Smazání účtu je nevratné</h4>
                        <p class="text-red-700 text-sm font-light">
                            Po potvrzení nebude možné tuto akci vzít zpět.
                        </p>
                    </div>
                </div>
            </div>

            <!-- What will happen -->
            <div class="space-y-6 mb-6">
                <div>
                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Co bude smazáno:
                    </h4>
                    <ul class="space-y-2 text-gray-600 ml-7">
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2">✗</span>
                            <span class="font-light">Všechna vaše osobní data (jméno, email, telefon, adresa)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2">✗</span>
                            <span class="font-light">Přihlašovací údaje - už se nebudete moci přihlásit</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2">✗</span>
                            <span class="font-light">Všechny uložené platební metody</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2">✗</span>
                            <span class="font-light">Všechna aktivní předplatná budou zrušena</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-500 mr-2">✗</span>
                            <span class="font-light">Nezaplacené objednávky budou smazány</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Co zachováme (zákonná povinnost):
                    </h4>
                    <ul class="space-y-2 text-gray-600 ml-7">
                        <li class="flex items-start">
                            <span class="text-blue-500 mr-2">✓</span>
                            <span class="font-light">Faktury a historii plateb (10 let dle zákona o účetnictví)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-500 mr-2">✓</span>
                            <span class="font-light">Anonymizovaná data o doručených objednávkách</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-500 mr-2">✓</span>
                            <span class="font-light">Historie předplatných (anonymizovaná)</span>
                        </li>
                    </ul>
                    <p class="text-sm text-gray-500 mt-2 ml-7 font-light italic">
                        Všechny zachované údaje jsou plně anonymizované.
                    </p>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <h4 class="font-bold text-yellow-800 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Nelze smazat účet pokud:
                    </h4>
                    <ul class="space-y-1 text-yellow-800 text-sm ml-7">
                        <li class="font-light">• Máte aktivní nebo pozastavená předplatná</li>
                        <li class="font-light">• Máte nezaplacené objednávky</li>
                        <li class="font-light">• Máte zaplacené objednávky, které ještě nebyly doručeny</li>
                    </ul>
                </div>
            </div>

            <!-- Confirmation Form -->
            <form method="POST" action="{{ route('dashboard.profile.delete') }}" id="deleteAccountForm">
                @csrf
                @method('DELETE')

                <div class="space-y-4">
                    <div>
                        <label for="delete_password" class="block text-sm font-medium text-gray-900 mb-2">
                            Zadejte své heslo pro potvrzení
                        </label>
                        <input type="password" 
                               id="delete_password" 
                               name="password" 
                               class="input w-full"
                               placeholder="Vaše heslo"
                               required>
                        <p id="delete_password_error" class="text-red-600 text-sm mt-2 hidden flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span id="delete_password_error_text"></span>
                        </p>
                    </div>

                    <div>
                        <div class="flex items-start">
                            <input type="checkbox" 
                                   id="delete_confirmation" 
                                   name="confirmation" 
                                   value="1"
                                   class="mt-1 w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                   required>
                            <label for="delete_confirmation" class="ml-3 text-sm text-gray-700">
                                Potvrzuji, že jsem si vědom/a důsledků a chci <strong>trvale smazat</strong> svůj účet.
                            </label>
                        </div>
                        <p id="delete_confirmation_error" class="text-red-600 text-sm mt-2 ml-7 hidden flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span id="delete_confirmation_error_text"></span>
                        </p>
                    </div>

                    <!-- General error message -->
                    <div id="delete_general_error" class="bg-red-50 border-l-4 border-red-500 p-4 rounded hidden">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <p id="delete_general_error_text" class="text-red-700 text-sm"></p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button type="button" 
                            onclick="closeDeleteAccountModal()"
                            class="px-6 py-2.5 border border-gray-300 rounded-full font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        Zrušit
                    </button>
                    <button type="submit" 
                            id="delete_submit_btn"
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-full transition-all duration-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span id="delete_submit_text">Ano, smazat můj účet</span>
                    </button>
                </div>
            </form>
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
                    <div id="selected-point" class="p-4 bg-primary-50 border border-primary-300 rounded-xl mb-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-1.5">
                                    <svg class="w-4 h-4 text-primary-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium text-gray-900 text-sm">Aktuální výdejní místo:</span>
                                </div>
                                <p class="text-gray-900 font-medium ml-6">${point.name}</p>
                                <p class="text-sm text-gray-600 ml-6 font-light">${address}</p>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="select-point-btn" class="w-full flex items-center justify-center gap-2 bg-[#ba1b02] hover:bg-[#a01701] text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Změnit výdejní místo</span>
                    </button>
                `;

                // Re-attach event listener to the new button
                document.getElementById('select-point-btn').addEventListener('click', openPacketaWidget);
            }
        }, {
            country: 'cz',
            language: 'cs',
        });
    }

    // Event listener for opening widget
    const selectBtn = document.getElementById('select-point-btn');
    if (selectBtn) {
        selectBtn.addEventListener('click', openPacketaWidget);
    }

    // Delete Account Modal Functions
    window.openDeleteAccountModal = function() {
        const modal = document.getElementById('deleteAccountModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        // Clear any previous errors
        clearDeleteAccountErrors();
    };

    window.closeDeleteAccountModal = function() {
        const modal = document.getElementById('deleteAccountModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        
        // Reset form and errors
        document.getElementById('deleteAccountForm').reset();
        clearDeleteAccountErrors();
    };

    function clearDeleteAccountErrors() {
        // Clear all error messages
        document.getElementById('delete_password_error').classList.add('hidden');
        document.getElementById('delete_confirmation_error').classList.add('hidden');
        document.getElementById('delete_general_error').classList.add('hidden');
        
        // Remove error styling from inputs
        document.getElementById('delete_password').classList.remove('border-red-500');
        document.getElementById('delete_confirmation').classList.remove('border-red-500');
    }

    function showDeleteAccountError(field, message) {
        if (field === 'password') {
            const errorEl = document.getElementById('delete_password_error');
            const errorText = document.getElementById('delete_password_error_text');
            const inputEl = document.getElementById('delete_password');
            
            errorText.textContent = message;
            errorEl.classList.remove('hidden');
            inputEl.classList.add('border-red-500');
            inputEl.focus();
        } else if (field === 'confirmation') {
            const errorEl = document.getElementById('delete_confirmation_error');
            const errorText = document.getElementById('delete_confirmation_error_text');
            
            errorText.textContent = message;
            errorEl.classList.remove('hidden');
        } else {
            // General error
            const errorEl = document.getElementById('delete_general_error');
            const errorText = document.getElementById('delete_general_error_text');
            
            errorText.textContent = message;
            errorEl.classList.remove('hidden');
        }
    }

    // Handle form submission with AJAX
    document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        clearDeleteAccountErrors();
        
        // Disable submit button
        const submitBtn = document.getElementById('delete_submit_btn');
        const submitText = document.getElementById('delete_submit_text');
        const originalText = submitText.textContent;
        
        submitBtn.disabled = true;
        submitText.textContent = 'Mazání...';
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Get form data
        const formData = new FormData(this);
        
        // Send AJAX request
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success - redirect to home
                window.location.href = data.redirect || '{{ route("home") }}';
            } else {
                // Show errors
                if (data.errors) {
                    // Laravel validation errors
                    if (data.errors.password) {
                        showDeleteAccountError('password', data.errors.password[0]);
                    }
                    if (data.errors.confirmation) {
                        showDeleteAccountError('confirmation', data.errors.confirmation[0]);
                    }
                } else if (data.message) {
                    // General error message
                    showDeleteAccountError('general', data.message);
                }
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitText.textContent = originalText;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showDeleteAccountError('general', 'Došlo k chybě při mazání účtu. Zkuste to prosím znovu.');
            
            // Re-enable submit button
            submitBtn.disabled = false;
            submitText.textContent = originalText;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    });

    // Close modal on background click
    document.getElementById('deleteAccountModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteAccountModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('deleteAccountModal');
            if (!modal.classList.contains('hidden')) {
                closeDeleteAccountModal();
            }
        }
    });
});
</script>
@endsection

