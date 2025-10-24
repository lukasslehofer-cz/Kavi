@extends('layouts.app')

@section('title', 'Pokladna - Kavi Coffee')

@section('content')
<div class="bg-bluegray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-6">
            <ol class="flex items-center space-x-2 text-dark-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-500 transition-colors">Domů</a></li>
                <li class="text-dark-400">/</li>
                <li><a href="{{ route('cart.index') }}" class="hover:text-primary-500 transition-colors">Košík</a></li>
                <li class="text-dark-400">/</li>
                <li class="text-dark-800 font-medium">Pokladna</li>
            </ol>
        </nav>

        <h1 class="font-display text-4xl md:text-5xl font-bold text-dark-800 mb-2">Dokončení objednávky</h1>
        <p class="text-dark-600">Ještě pár informací a vaše káva bude na cestě k vám</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('checkout.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Contact Information -->
                <div class="card p-8">
                    <h2 class="font-display text-2xl font-bold text-dark-800 mb-6">Kontaktní údaje</h2>
                    
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
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-dark-700 mb-2">
                                Telefon <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                required
                                class="input"
                                placeholder="+420 123 456 789"
                            >
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card p-8">
                    <h2 class="font-display text-2xl font-bold text-dark-800 mb-6">Dodací adresa</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="address" class="block text-sm font-medium text-dark-700 mb-2">
                                Ulice a číslo popisné <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="address" 
                                name="address" 
                                value="{{ old('address') }}" 
                                required
                                class="input"
                                placeholder="Např. Karlova 123"
                            >
                            @error('address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="city" class="block text-sm font-medium text-dark-700 mb-2">
                                    Město <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="city" 
                                    name="city" 
                                    value="{{ old('city') }}" 
                                    required
                                    class="input"
                                    placeholder="Např. Praha"
                                >
                                @error('city')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-dark-700 mb-2">
                                    PSČ <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="postal_code" 
                                    name="postal_code" 
                                    value="{{ old('postal_code') }}" 
                                    required
                                    class="input"
                                    placeholder="123 45"
                                >
                                @error('postal_code')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card p-8">
                    <h2 class="font-display text-2xl font-bold text-dark-800 mb-6">Způsob platby</h2>
                    
                    <div class="space-y-4">
                        <label class="flex items-start p-4 border-2 border-bluegray-200 rounded-xl cursor-pointer hover:border-primary-500 transition-colors has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
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

                        <label class="flex items-start p-4 border-2 border-bluegray-200 rounded-xl cursor-pointer hover:border-primary-500 transition-colors has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
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
                    <h2 class="font-display text-2xl font-bold text-dark-800 mb-6">Poznámka k objednávce (volitelné)</h2>
                    
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="4" 
                        class="input"
                        placeholder="Např. 'Prosím zvonit na 2. patro' nebo 'Nechat u vrátnice'"
                    >{{ old('notes') }}</textarea>
                </div>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="card p-8 sticky top-24">
                <h3 class="font-display text-2xl font-bold text-dark-800 mb-6">Souhrn objednávky</h3>
                
                <!-- Order Items -->
                <div class="space-y-4 mb-6 pb-6 border-b border-bluegray-200">
                    @foreach($cartItems as $item)
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-bluegray-100">
                            @if($item['product']->image)
                            <img src="{{ $item['product']->image }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-bluegray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-dark-800 truncate">{{ $item['product']->name }}</p>
                            <p class="text-sm text-dark-600">{{ $item['quantity'] }}× {{ number_format($item['product']->price, 0, ',', ' ') }} Kč</p>
                        </div>
                        <p class="text-sm font-bold text-dark-800">{{ number_format($item['subtotal'], 0, ',', ' ') }} Kč</p>
                    </div>
                    @endforeach
                </div>

                <!-- Price Summary -->
                <dl class="space-y-4 mb-6">
                    <div class="flex justify-between items-center">
                        <dt class="text-dark-600">Mezisoučet:</dt>
                        <dd class="font-semibold text-dark-800 text-lg">{{ number_format($subtotal, 0, ',', ' ') }} Kč</dd>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <dt class="text-dark-600">Doprava:</dt>
                        <dd class="font-semibold text-lg">
                            @if($shipping == 0)
                            <span class="text-green-600">Zdarma</span>
                            @else
                            <span class="text-dark-800">{{ number_format($shipping, 0, ',', ' ') }} Kč</span>
                            @endif
                        </dd>
                    </div>

                    <div class="border-t-2 border-bluegray-200 pt-4">
                        <div class="flex justify-between items-center">
                            <dt class="font-display font-bold text-dark-800 text-lg">Celkem:</dt>
                            <dd class="font-bold text-primary-500 text-3xl">
                                {{ number_format($total, 0, ',', ' ') }} Kč
                            </dd>
                        </div>
                    </div>
                </dl>

                <button type="submit" form="checkout-form" class="btn btn-primary w-full text-center mb-3">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Dokončit objednávku
                </button>

                <div class="flex items-start mb-4">
                    <input type="checkbox" id="terms" required form="checkout-form" class="w-5 h-5 text-primary-500 border-gray-300 rounded focus:ring-primary-500 mr-3 mt-0.5">
                    <label for="terms" class="text-sm text-dark-700">
                        Souhlasím s <a href="#" class="text-primary-500 hover:text-primary-600 underline">obchodními podmínkami</a> 
                        a <a href="#" class="text-primary-500 hover:text-primary-600 underline">zásadami ochrany osobních údajů</a>
                    </label>
                </div>

                <a href="{{ route('cart.index') }}" class="btn btn-outline w-full text-center">
                    ← Zpět do košíku
                </a>

                <!-- Trust Badges -->
                <div class="mt-6 pt-6 border-t border-bluegray-200 space-y-3">
                    <div class="flex items-center text-sm text-dark-600">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Bezpečná platba</span>
                    </div>
                    <div class="flex items-center text-sm text-dark-600">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>14 dní na vrácení</span>
                    </div>
                    <div class="flex items-center text-sm text-dark-600">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Zákaznická podpora 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add form id to the form element
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('checkout.store') }}"]');
    if (form) {
        form.id = 'checkout-form';
    }
});
</script>
@endsection

