@extends('layouts.admin')

@section('title', 'Detail předplatného')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Předplatné {{ $subscription->subscription_number ?? '#' . $subscription->id }}</h1>
            <p class="text-gray-600 mt-1">{{ $subscription->created_at->format('d.m.Y H:i') }}</p>
        </div>
        <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            ← Zpět na seznam
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <p class="text-red-700 font-medium mb-2">Chyba při zpracování:</p>
                <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Subscription Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Detail předplatného</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                @if($subscription->plan)
                                <h3 class="text-xl font-bold text-gray-900">{{ $subscription->plan->name }}</h3>
                                <p class="text-gray-600">{{ $subscription->plan->description }}</p>
                                @else
                                <h3 class="text-xl font-bold text-gray-900">Vlastní konfigurace</h3>
                                <p class="text-gray-600">Kávové předplatné podle přání zákazníka</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($subscription->configured_price ?? $subscription->plan->price ?? 0, 0, ',', ' ') }} Kč
                                </div>
                                <div class="text-sm text-gray-600">
                                    / {{ $subscription->frequency_months == 1 ? 'měsíc' : ($subscription->frequency_months . ' měsíce') }}
                                </div>
                            </div>
                        </div>

                        @if($subscription->discount_amount > 0 && $subscription->coupon)
                        <div class="border-t border-gray-200 pt-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        <span class="font-bold text-green-900">Aktivní sleva</span>
                                    </div>
                                    <span class="text-lg font-bold text-green-700">-{{ number_format($subscription->discount_amount, 0, ',', ' ') }} Kč</span>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-700">Kód kupónu:</span>
                                        <span class="font-mono font-bold text-gray-900">{{ $subscription->coupon_code }}</span>
                                    </div>
                                    @if($subscription->discount_months_total)
                                    @php
                                    $originalPrice = $subscription->configured_price + $subscription->discount_amount;
                                    // Calculate when discount ends
                                    $nextBillingDate = $subscription->next_billing_date ? \Carbon\Carbon::parse($subscription->next_billing_date) : now();
                                    $discountEndsAt = $nextBillingDate->copy()->addMonths(($subscription->discount_months_remaining - 1) * $subscription->frequency_months);
                                    @endphp
                                    <div class="flex justify-between">
                                        <span class="text-gray-700">Zbývá plateb se slevou:</span>
                                        <span class="font-semibold text-gray-900">{{ $subscription->discount_months_remaining }} z {{ $subscription->discount_months_total }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-700">Sleva platí do:</span>
                                        <span class="font-semibold text-gray-900">{{ $discountEndsAt->format('d.m.Y') }}</span>
                                    </div>
                                    <div class="flex justify-between pt-2 border-t border-green-300">
                                        <span class="text-gray-700">Plná cena od {{ $discountEndsAt->copy()->addMonths($subscription->frequency_months)->format('d.m.Y') }}:</span>
                                        <span class="font-bold text-gray-900">{{ number_format($originalPrice, 0, ',', ' ') }} Kč</span>
                                    </div>
                                    @else
                                    <div class="flex justify-between">
                                        <span class="text-gray-700">Trvání:</span>
                                        <span class="font-semibold text-green-700">Permanentní sleva</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="border-t border-gray-200 pt-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Začátek:</span>
                                    <span class="font-medium text-gray-900 ml-2">
                                        {{ $subscription->starts_at ? $subscription->starts_at->format('d.m.Y') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Další dodávka:</span>
                                    <span class="font-medium text-gray-900 ml-2">
                                        {{ $subscription->next_billing_date ? $subscription->next_billing_date->format('d.m.Y') : '-' }}
                                    </span>
                                </div>
                                @if($subscription->trial_ends_at)
                                <div>
                                    <span class="text-gray-600">Konec zkušebního období:</span>
                                    <span class="font-medium text-gray-900 ml-2">
                                        {{ \Carbon\Carbon::parse($subscription->trial_ends_at)->format('d.m.Y') }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            @if($subscription->configuration)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="font-display text-xl font-bold text-gray-900">Konfigurace předplatného</h2>
                </div>
                <div class="p-6">
                    @php
                        $config = is_string($subscription->configuration) 
                            ? json_decode($subscription->configuration, true) 
                            : $subscription->configuration;
                    @endphp

                    <div class="space-y-3">
                        @if(isset($config['amount']))
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Množství:</span>
                            <span class="font-semibold text-gray-900">{{ $config['amount'] }} balení</span>
                        </div>
                        @endif

                        @if(isset($config['cups']))
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Spotřeba:</span>
                            <span class="font-semibold text-gray-900">{{ $config['cups'] }} šálky denně</span>
                        </div>
                        @endif

                        @if(isset($config['type']))
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Typ kávy:</span>
                            <span class="font-semibold text-gray-900">
                                @if($config['type'] === 'espresso')
                                    Espresso
                                @elseif($config['type'] === 'filter')
                                    Filter
                                @else
                                    Mix
                                @endif
                            </span>
                        </div>
                        @endif

                        @if(isset($config['isDecaf']) && $config['isDecaf'])
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Decaf varianta:</span>
                            <span class="font-semibold text-gray-900">Ano (+100 Kč)</span>
                        </div>
                        @endif

                        @if(isset($config['mix']) && $config['type'] === 'mix')
                        <div class="py-2 border-b">
                            <span class="text-gray-600 block mb-2">Rozložení mixu:</span>
                            <div class="space-y-1 ml-4">
                                @if(isset($config['mix']['espresso']) && $config['mix']['espresso'] > 0)
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    <span>{{ $config['mix']['espresso'] }}× Espresso</span>
                                </div>
                                @endif
                                @if(isset($config['mix']['filter']) && $config['mix']['filter'] > 0)
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    <span>{{ $config['mix']['filter'] }}× Filtr</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if(isset($config['frequencyText']))
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Frekvence:</span>
                            <span class="font-semibold text-gray-900">{{ $config['frequencyText'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Shipping & Packeta -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-display text-xl font-bold text-gray-900">Dodací údaje</h2>
                    <button onclick="openEditAddressModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Upravit adresu
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Billing Address -->
                        @if($subscription->shipping_address)
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Fakturační adresa</h3>
                            @php
                                $address = is_string($subscription->shipping_address) 
                                    ? json_decode($subscription->shipping_address, true) 
                                    : $subscription->shipping_address;
                            @endphp
                            <div class="text-sm text-gray-700 space-y-1">
                                <p class="font-medium">{{ $address['name'] ?? '' }}</p>
                                @if(isset($address['billing_address']))
                                <p>{{ $address['billing_address'] }}</p>
                                <p>{{ $address['billing_postal_code'] }} {{ $address['billing_city'] }}</p>
                                @else
                                <p>{{ $address['address'] ?? '' }}</p>
                                <p>{{ $address['postal_code'] ?? '' }} {{ $address['city'] ?? '' }}</p>
                                @endif
                                @if(isset($address['phone']))
                                <p class="mt-2">Tel: {{ $address['phone'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Packeta -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Výdejní místo Zásilkovna</h3>
                            @if($subscription->packeta_point_id)
                            <div class="text-sm text-gray-700">
                                <p class="font-medium">{{ $subscription->packeta_point_name }}</p>
                                <p class="text-gray-600 mt-1">{{ $subscription->packeta_point_address }}</p>
                                <p class="text-xs text-gray-500 mt-1">ID: {{ $subscription->packeta_point_id }}</p>
                            </div>
                            @else
                            <p class="text-sm text-gray-600">Není nastaveno</p>
                            @endif
                        </div>
                    </div>

                    @if($subscription->payment_method)
                    <div class="mt-4 pt-4 border-t">
                        <h3 class="font-semibold text-gray-900 mb-2">Platební metoda</h3>
                        <p class="text-sm text-gray-700">
                            @if($subscription->payment_method === 'card')
                                Platební karta
                            @elseif($subscription->payment_method === 'transfer')
                                Bankovní převod
                            @else
                                {{ $subscription->payment_method }}
                            @endif
                        </p>
                    </div>
                    @endif

                    @if($subscription->delivery_notes)
                    <div class="mt-4 pt-4 border-t">
                        <h3 class="font-semibold text-gray-900 mb-2">Poznámka k dodání</h3>
                        <p class="text-sm text-gray-700">{{ $subscription->delivery_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-display text-xl font-bold text-gray-900 mb-4">Správa stavu</h3>
                
                <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stav předplatného</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                            <option value="pending" {{ $subscription->status === 'pending' ? 'selected' : '' }}>Čeká</option>
                            <option value="active" {{ $subscription->status === 'active' ? 'selected' : '' }}>Aktivní</option>
                            <option value="unpaid" {{ $subscription->status === 'unpaid' ? 'selected' : '' }}>⚠️ Neuhrazeno</option>
                            <option value="paused" {{ $subscription->status === 'paused' ? 'selected' : '' }}>Pozastaveno</option>
                            <option value="trialing" {{ $subscription->status === 'trialing' ? 'selected' : '' }}>Zkušební období</option>
                            <option value="past_due" {{ $subscription->status === 'past_due' ? 'selected' : '' }}>Po splatnosti</option>
                            <option value="canceled" {{ $subscription->status === 'canceled' ? 'selected' : '' }}>Zrušeno</option>
                        </select>
                    </div>
                </form>
                
                <div class="pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600 mb-2">Aktuální stav:</div>
                        @if($subscription->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktivní</span>
                        @elseif($subscription->status === 'unpaid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300">⚠️ Neuhrazeno</span>
                        @elseif($subscription->status === 'paused')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pozastaveno</span>
                        @elseif($subscription->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Čeká</span>
                        @elseif($subscription->status === 'trialing')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Zkušební</span>
                        @elseif($subscription->status === 'past_due')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Po splatnosti</span>
                        @elseif($subscription->status === 'canceled')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Zrušeno</span>
                        @endif
                </div>
                
                <!-- Payment Failure Warning -->
                @if($subscription->status === 'unpaid' && $subscription->pending_invoice_id)
                <div class="mt-4 p-4 bg-red-50 border-2 border-red-300 rounded-lg">
                    <h4 class="text-sm font-bold text-red-900 mb-2">⚠️ Neuhrazená platba</h4>
                    <div class="text-xs text-red-800 space-y-1">
                        @if($subscription->pending_invoice_amount)
                        <div class="flex justify-between font-semibold">
                            <span>K úhradě:</span>
                            <span>{{ number_format($subscription->pending_invoice_amount, 0, ',', ' ') }} Kč</span>
                        </div>
                        @endif
                        @if($subscription->payment_failure_count)
                        <div>Počet pokusů: {{ $subscription->payment_failure_count }}</div>
                        @endif
                        @if($subscription->last_payment_failure_at)
                        <div>Poslední pokus: {{ $subscription->last_payment_failure_at->format('d.m.Y H:i') }}</div>
                        @endif
                        @if($subscription->last_payment_failure_reason)
                        <div class="pt-2 border-t border-red-300 mt-2">
                            <strong>Důvod:</strong><br>
                            {{ $subscription->last_payment_failure_reason }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-display text-xl font-bold text-gray-900 mb-4">Informace o zákazníkovi</h3>
                <div class="space-y-3 text-sm">
                    @if($subscription->user)
                    <div>
                        <div class="text-gray-600">Jméno:</div>
                        <div class="font-medium text-gray-900">{{ $subscription->user->name }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600">Email:</div>
                        <div class="font-medium text-gray-900">{{ $subscription->user->email }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600">Registrován:</div>
                        <div class="font-medium text-gray-900">{{ $subscription->user->created_at->format('d.m.Y') }}</div>
                    </div>
                    <div class="pt-3 border-t">
                        <a href="{{ route('admin.dashboard') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            Zobrazit všechny objednávky zákazníka →
                        </a>
                    </div>
                    @else
                    <div class="text-gray-600 italic">
                        Předplatné vytvořeno bez registrace
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stripe Info -->
            @if($subscription->stripe_subscription_id)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-display text-xl font-bold text-gray-900 mb-4">Stripe informace</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <div class="text-gray-600">Subscription ID:</div>
                        <div class="font-mono text-xs text-gray-900 break-all">{{ $subscription->stripe_subscription_id }}</div>
                    </div>
                    @if($subscription->stripe_customer_id)
                    <div>
                        <div class="text-gray-600">Customer ID:</div>
                        <div class="font-mono text-xs text-gray-900 break-all">{{ $subscription->stripe_customer_id }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Cancel Subscription -->
            @if($subscription->status !== 'canceled')
            <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" onsubmit="return confirm('Opravdu chcete zrušit toto předplatné?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn w-full bg-red-600 text-white hover:bg-red-700">
                    Zrušit předplatné
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Shipment History Section -->
    @if($subscription->shipments->count() > 0)
    <div class="mt-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    Historie rozesílek ({{ $subscription->shipments->count() }})
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum rozesílky</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rozměry balíku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Packeta tracking</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Faktura</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poznámka</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($subscription->shipments as $shipment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $shipment->shipment_date->format('d.m.Y') }}</div>
                                @if($shipment->sent_at)
                                <div class="text-xs text-gray-500">Odesláno: {{ $shipment->sent_at->format('d.m.Y H:i') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($shipment->status === 'sent')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Odesláno
                                </span>
                                @elseif($shipment->status === 'delivered')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Doručeno
                                </span>
                                @elseif($shipment->status === 'cancelled')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Zrušeno
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Čeká
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $shipment->package_length }}×{{ $shipment->package_width }}×{{ $shipment->package_height }} cm
                                </div>
                                <div class="text-xs text-gray-500">{{ $shipment->package_weight }} kg</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($shipment->packeta_packet_id)
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $shipment->packeta_packet_id }}</div>
                                    @if($shipment->packeta_tracking_url)
                                    <a href="{{ $shipment->packeta_tracking_url }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                        Sledovat zásilku
                                    </a>
                                    @endif
                                </div>
                                @else
                                <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($shipment->payment)
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $shipment->payment->invoice_number ?? 'FID ' . $shipment->payment->fakturoid_invoice_id }}</div>
                                    @if($shipment->payment->paid_at)
                                    <div class="text-xs text-gray-500">{{ $shipment->payment->paid_at->format('d.m.Y') }}</div>
                                    @endif
                                    @if($shipment->payment->invoice_pdf_path)
                                    <a href="{{ Storage::url($shipment->payment->invoice_pdf_path) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                        Stáhnout PDF
                                    </a>
                                    @endif
                                </div>
                                @else
                                <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($shipment->notes)
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $shipment->notes }}">
                                    {{ $shipment->notes }}
                                </div>
                                @else
                                <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Edit Address Modal -->
<div id="editAddressModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-lg rounded-xl bg-white">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Upravit dodací adresu</h3>
            <button onclick="closeEditAddressModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.subscriptions.update-address', $subscription) }}" method="POST">
            @csrf
            @method('PUT')

            @php
                $address = is_string($subscription->shipping_address) 
                    ? json_decode($subscription->shipping_address, true) 
                    : ($subscription->shipping_address ?? []);
            @endphp

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jméno a příjmení *</label>
                    <input type="text" name="name" value="{{ $address['name'] ?? '' }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ $address['email'] ?? $subscription->user->email ?? '' }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telefon *</label>
                    <input type="tel" name="phone" value="{{ $address['phone'] ?? '' }}" required
                           placeholder="+420 123 456 789"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Billing Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fakturační adresa *</label>
                    <input type="text" name="billing_address" value="{{ $address['billing_address'] ?? $address['address'] ?? '' }}" required
                           placeholder="Ulice a číslo popisné"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- City and Postal Code -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Město *</label>
                        <input type="text" name="billing_city" value="{{ $address['billing_city'] ?? $address['city'] ?? '' }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PSČ *</label>
                        <input type="text" name="billing_postal_code" value="{{ $address['billing_postal_code'] ?? $address['postal_code'] ?? '' }}" required
                               placeholder="123 45"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Country -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Země *</label>
                    <select name="country" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="CZ" {{ ($address['country'] ?? 'CZ') === 'CZ' ? 'selected' : '' }}>Česká republika</option>
                        <option value="SK" {{ ($address['country'] ?? '') === 'SK' ? 'selected' : '' }}>Slovensko</option>
                        <option value="PL" {{ ($address['country'] ?? '') === 'PL' ? 'selected' : '' }}>Polsko</option>
                        <option value="HU" {{ ($address['country'] ?? '') === 'HU' ? 'selected' : '' }}>Maďarsko</option>
                        <option value="AT" {{ ($address['country'] ?? '') === 'AT' ? 'selected' : '' }}>Rakousko</option>
                        <option value="DE" {{ ($address['country'] ?? '') === 'DE' ? 'selected' : '' }}>Německo</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditAddressModal()"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Zrušit
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Uložit změny
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditAddressModal() {
    document.getElementById('editAddressModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditAddressModal() {
    document.getElementById('editAddressModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditAddressModal();
    }
});

// Close modal on background click
document.getElementById('editAddressModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditAddressModal();
    }
});
</script>

@endsection


