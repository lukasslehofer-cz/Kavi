@extends('layouts.app')

@section('title', 'Nákupní košík - Kavi Coffee')

@section('content')
<!-- Hero Header -->
<div class="relative bg-gradient-to-br from-gray-50 via-slate-50 to-gray-100 py-12 border-b-2 border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-2">Nákupní košík</h1>
                <p class="text-lg text-gray-600">
                    <span class="font-semibold">{{ count($cartItems ?? []) }}</span> 
                    {{ count($cartItems ?? []) === 1 ? 'položka' : (count($cartItems ?? []) < 5 ? 'položky' : 'položek') }}
                </p>
            </div>
            <div class="hidden md:block">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-pink-600 flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(empty($cartItems))
    <!-- Empty Cart -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl p-12 text-center shadow-xl border-2 border-gray-200">
            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-gray-100 to-slate-100 flex items-center justify-center mx-auto mb-6">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-4">Váš košík je prázdný</h2>
            <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">Objevte naši nabídku prémiových káv a vyberte si tu pravou pro vás.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <span>Procházet kávy</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
                <a href="{{ route('subscriptions.index') }}" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-semibold px-8 py-4 rounded-xl shadow-md hover:shadow-lg border-2 border-gray-200 hover:border-gray-300 transform hover:-translate-y-0.5 transition-all duration-200">
                    Zobrazit předplatné
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="space-y-4">
                @foreach($cartItems as $item)
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-200 border-2 border-gray-200">
                    <div class="flex gap-6">
                        <!-- Product Image -->
                        <div class="w-28 h-28 rounded-xl overflow-hidden flex-shrink-0 shadow-md">
                            @if($item['product']->image)
                            <img src="{{ asset($item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-4 bg-gradient-to-br from-primary-100 to-pink-100">
                                <svg class="w-12 h-12 text-primary-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                                </svg>
                            </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="flex-grow">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">
                                        <a href="{{ route('products.show', $item['product']) }}" class="hover:text-primary-600 transition-colors">
                                            {{ $item['product']->name }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 font-medium">{{ number_format($item['product']->price, 0, ',', ' ') }} Kč / ks</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-black bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">{{ number_format($item['subtotal'], 0, ',', ' ') }} Kč</p>
                                </div>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center justify-between gap-4 mt-4 pt-4 border-t border-gray-200">
                                <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" class="flex items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center border-2 border-gray-300 rounded-xl overflow-hidden bg-white shadow-sm">
                                        <button type="button" class="px-4 py-2 text-gray-700 hover:bg-primary-500 hover:text-white transition-colors font-bold">-</button>
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" 
                                               class="w-16 text-center text-lg font-bold border-0 focus:ring-0 py-2" data-quantity-input>
                                        <button type="button" class="px-4 py-2 text-gray-700 hover:bg-primary-500 hover:text-white transition-colors font-bold">+</button>
                                    </div>
                                    <button type="submit" class="text-sm bg-primary-50 hover:bg-primary-100 text-primary-700 font-semibold px-4 py-2 rounded-lg transition-colors">
                                        Aktualizovat
                                    </button>
                                </form>

                                <!-- Remove Button -->
                                <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors group" title="Odebrat z košíku">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Clear Cart -->
            <div class="mt-6">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 text-red-600 hover:bg-red-50 text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1v3M4 7h16"/>
                        </svg>
                        Vyprázdnit celý košík
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-8 sticky top-24 shadow-xl border-2 border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-pink-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900">Souhrn objednávky</h3>
                </div>
                
                <dl class="space-y-4 mb-6">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <dt class="text-gray-600 font-medium">Mezisoučet:</dt>
                        <dd class="font-bold text-gray-900 text-lg">{{ number_format($total, 0, ',', ' ') }} Kč</dd>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <dt class="text-gray-600 font-medium">Doprava:</dt>
                        <dd class="font-bold text-lg">
                            @if($total >= 1000)
                            <span class="text-green-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Zdarma
                            </span>
                            @else
                            <span class="text-gray-900">99 Kč</span>
                            @endif
                        </dd>
                    </div>

                    @if($total < 1000)
                    <div class="bg-gradient-to-r from-primary-50 to-pink-50 border-2 border-primary-200 rounded-xl p-4">
                        <p class="text-sm text-primary-700 font-semibold flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span>Do dopravy zdarma vám chybí <strong>{{ number_format(1000 - $total, 0, ',', ' ') }} Kč</strong></span>
                        </p>
                    </div>
                    @else
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-4">
                        <p class="text-sm text-green-700 font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Získali jste dopravu zdarma!
                        </p>
                    </div>
                    @endif

                    <div class="border-t-2 border-gray-200 pt-6 mt-4">
                        <div class="flex justify-between items-center">
                            <dt class="font-bold text-gray-900 text-lg">Celkem:</dt>
                            <dd class="text-4xl font-black bg-gradient-to-r from-primary-600 to-pink-600 bg-clip-text text-transparent">
                                {{ number_format($total >= 1000 ? $total : $total + 99, 0, ',', ' ') }} Kč
                            </dd>
                        </div>
                    </div>
                </dl>

                <a href="{{ route('checkout.index') }}" class="group w-full flex items-center justify-center gap-2 bg-gradient-to-r from-primary-500 to-pink-600 hover:from-primary-600 hover:to-pink-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 mb-3">
                    <span>Pokračovat k pokladně</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
                <a href="{{ route('products.index') }}" class="block w-full text-center bg-white hover:bg-gray-50 text-gray-900 font-semibold px-8 py-3 rounded-xl shadow-md hover:shadow-lg border-2 border-gray-200 hover:border-gray-300 transition-all duration-200">
                    Pokračovat v nákupu
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
    @endif
</div>

<!-- Recommendations -->
@if(!empty($cartItems))
<section class="bg-bluegray-50 py-16 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-3xl md:text-4xl font-bold text-dark-800 mb-8 text-center">
            Mohlo by vás také zajímat
        </h2>
        <p class="text-center text-dark-600 mb-12">Doplňte si objednávku o další produkty</p>
        <!-- Placeholder for recommendations -->
        <div class="text-center text-dark-400">
            <a href="{{ route('products.index') }}" class="btn btn-outline">Procházet další produkty</a>
        </div>
    </div>
</section>
@endif
@endsection
