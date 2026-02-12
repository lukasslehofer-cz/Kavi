@extends('layouts.admin')

@section('title', 'Detail objednávky')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Objednávka {{ $order->order_number }}</h1>
            <p class="text-gray-600 mt-1">{{ $order->created_at->format('d.m.Y H:i') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            ← Zpět na seznam
        </a>
    </div>

    <!-- Payment Failure Warning -->
    @if($order->payment_status === 'unpaid')
    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-red-900 mb-2">Problém s platbou objednávky</h3>
                <div class="space-y-2 text-sm text-red-800">
                    <p><span class="font-semibold">Částka k úhradě:</span> {!! \App\Helpers\CurrencyHelper::formatByCurrency($order->total, $order->currency) !!}</p>
                    @if($order->payment_failure_count > 0)
                    <p><span class="font-semibold">Počet neúspěšných pokusů:</span> {{ $order->payment_failure_count }}×</p>
                    @endif
                    @if($order->last_payment_failure_at)
                    <p><span class="font-semibold">Poslední pokus:</span> {{ $order->last_payment_failure_at->format('d.m.Y H:i') }}</p>
                    @endif
                    @if($order->last_payment_failure_reason)
                    <p><span class="font-semibold">Důvod:</span> {{ $order->last_payment_failure_reason }}</p>
                    @endif
                </div>
                <p class="mt-3 text-sm text-gray-700">
                    Zákazník byl emailem informován o problému a může zaplatit manuálně ve svém dashboardu.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Subscription Addon Notice -->
    @if($order->shipped_with_subscription)
    <div class="bg-gradient-to-r from-purple-50 to-purple-100 border-2 border-purple-300 rounded-xl p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 rounded-full bg-purple-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-purple-900 mb-2">
                    Doplňkové zboží k předplatnému
                </h3>
                <div class="space-y-2 text-sm text-purple-800">
                    <p>
                        <span class="font-semibold">Tato objednávka bude odeslána společně s předplatným zákazníka.</span>
                    </p>
                    @if($order->subscription)
                    <p>
                        <span class="font-medium">Předplatné:</span> 
                        <a href="{{ route('admin.subscriptions.show', $order->subscription) }}" 
                           class="font-bold underline hover:text-purple-900">
                            {{ $order->subscription->subscription_number ?? '#' . $order->subscription->id }}
                        </a>
                    </p>
                    @endif
                    @if($order->shipmentSchedule)
                    <p>
                        <span class="font-medium">Plánovaná rozesílka:</span> 
                        <strong>{{ $order->shipmentSchedule->shipment_date->format('d.m.Y') }}</strong>
                    </p>
                    @endif
                    <p>
                        <span class="font-medium">Počet slotů využito:</span> 
                        <strong>{{ $order->subscription_addon_slots_used }} / 3</strong>
                    </p>
                </div>
                <div class="mt-3 inline-flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-purple-200">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-medium text-gray-900">Doprava zdarma</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Položky objednávky</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Produkt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Cena</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Množství</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Celkem</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($item->product_image)
                                        <img src="{{ asset($item->product_image) }}" alt="{{ $item->product_name }}" class="w-12 h-12 object-cover rounded mr-4">
                                        @endif
                                        <div>
                                            <span class="font-medium text-gray-900">{{ $item->product_name }}</span>
                                            @if($item->product?->roastery)
                                            <div class="text-sm text-gray-500">
                                                {{ $item->product->roastery->name }}
                                            </div>
                                            @endif
                                            @if($item->product?->category && count($item->product->category) > 0)
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach($item->product->category as $category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    @if($category === 'espresso') bg-amber-100 text-amber-800
                                                    @elseif($category === 'filter') bg-blue-100 text-blue-800
                                                    @elseif($category === 'decaf') bg-green-100 text-green-800
                                                    @elseif($category === 'accessories') bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($category) }}
                                                </span>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {!! \App\Helpers\CurrencyHelper::formatByCurrency($item->price, $order->currency) !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity }}×
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    {!! \App\Helpers\CurrencyHelper::formatByCurrency($item->price * $item->quantity, $order->currency) !!}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-gray-900">
                                    Mezisoučet:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    {!! \App\Helpers\CurrencyHelper::formatByCurrency($order->subtotal, $order->currency) !!}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-gray-900">
                                    Doprava:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    {!! \App\Helpers\CurrencyHelper::formatByCurrency($order->shipping, $order->currency) !!}
                                </td>
                            </tr>
                            @if($order->discount_amount > 0 && $order->coupon)
                            <tr class="bg-green-50">
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-green-700">
                                    Sleva ({{ $order->coupon_code }}):
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-700">
                                    -{!! \App\Helpers\CurrencyHelper::formatByCurrency($order->discount_amount, $order->currency) !!}
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-gray-900">
                                    @php
                                        $uniqueVatRates = $order->items->pluck('vat_rate')->unique();
                                        $vatLabel = $uniqueVatRates->count() === 1
                                            ? 'DPH (' . number_format($uniqueVatRates->first(), 0) . '%)'
                                            : 'DPH';
                                    @endphp
                                    {{ $vatLabel }}:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    {!! \App\Helpers\CurrencyHelper::formatByCurrency($order->tax, $order->currency, 2) !!}
                                </td>
                            </tr>
                            <tr class="border-t-2 border-gray-900">
                                <td colspan="3" class="px-6 py-4 text-right text-lg font-bold text-gray-900">
                                    Celkem (včetně DPH):
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-900">
                                    {!! \App\Helpers\CurrencyHelper::formatByCurrency($order->total, $order->currency) !!}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Doručovací adresa</h3>
                    <button onclick="toggleEditShippingAddress()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Upravit
                    </button>
                </div>
                
                <!-- Display Mode -->
                <div id="shipping-address-display" class="text-gray-700 space-y-4">
                    <div>
                        <p class="font-medium">{{ $order->shipping_address['name'] ?? 'N/A' }}</p>
                        @if(!empty($order->shipping_address['email']))
                        <p class="text-sm">{{ $order->shipping_address['email'] }}</p>
                        @endif
                        @if(!empty($order->shipping_address['phone']))
                        <p class="text-sm">Tel: {{ $order->shipping_address['phone'] }}</p>
                        @endif
                    </div>

                    @if(!empty($order->shipping_address['packeta_point_id']))
                    <!-- Packeta Pickup Point -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 p-4 rounded-lg">
                        <p class="font-medium text-sm text-blue-900 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z"/>
                            </svg>
                            Výdejní místo Packeta
                        </p>
                        <p class="font-bold text-blue-900">{{ $order->shipping_address['packeta_point_name'] ?? 'N/A' }}</p>
                        <p class="text-sm text-blue-800">{{ $order->shipping_address['packeta_point_address'] ?? 'N/A' }}</p>
                        <p class="text-xs text-blue-600 mt-1">ID: {{ $order->shipping_address['packeta_point_id'] }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Fakturační adresa:</p>
                        <p>{{ $order->shipping_address['billing_address'] ?? 'N/A' }}</p>
                        <p>{{ $order->shipping_address['billing_postal_code'] ?? '' }} {{ $order->shipping_address['billing_city'] ?? '' }}</p>
                        <p>{{ $order->shipping_address['country'] ?? 'CZ' }}</p>
                    </div>
                </div>
                
                <!-- Edit Mode -->
                <form id="shipping-address-edit" action="{{ route('admin.orders.update-address', $order) }}" method="POST" class="hidden space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jméno</label>
                        <input type="text" name="name" value="{{ $order->shipping_address['name'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ $order->shipping_address['email'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                        <input type="text" name="phone" value="{{ $order->shipping_address['phone'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-2">Fakturační adresa</p>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Ulice a číslo</label>
                                <input type="text" name="billing_address" value="{{ $order->shipping_address['billing_address'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">PSČ</label>
                                    <input type="text" name="billing_postal_code" value="{{ $order->shipping_address['billing_postal_code'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Město</label>
                                    <input type="text" name="billing_city" value="{{ $order->shipping_address['billing_city'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Země</label>
                                <input type="text" name="country" value="{{ $order->shipping_address['country'] ?? 'CZ' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-3">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                            Uložit
                        </button>
                        <button type="button" onclick="toggleEditShippingAddress()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300">
                            Zrušit
                        </button>
                    </div>
                </form>
            </div>
            
            <script>
            function toggleEditShippingAddress() {
                const display = document.getElementById('shipping-address-display');
                const edit = document.getElementById('shipping-address-edit');
                display.classList.toggle('hidden');
                edit.classList.toggle('hidden');
            }
            </script>

            <!-- Delivery Notes -->
            @if($order->customer_notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Poznámka k objednávce</h3>
                <p class="text-gray-700">{{ $order->customer_notes }}</p>
            </div>
            @endif

            <!-- Package Dimensions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Rozměry balíku pro Packetu</h3>
                    @if($order->canEditPackageDimensions())
                    <button onclick="openEditOrderDimensionsModal({{ $order->id }}, {{ $order->package_length ?? 'null' }}, {{ $order->package_width ?? 'null' }}, {{ $order->package_height ?? 'null' }}, {{ $order->package_weight ?? 'null' }})"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Upravit
                    </button>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Délka:</span>
                        <span class="font-medium text-gray-900">
                            @php
                                $dimensions = $order->getPackageDimensions();
                            @endphp
                            {{ number_format($dimensions['length'], 1) }} cm
                            @if(!$order->package_length)
                            <span class="text-xs text-gray-500">(počítáno)</span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Šířka:</span>
                        <span class="font-medium text-gray-900">
                            {{ number_format($dimensions['width'], 1) }} cm
                            @if(!$order->package_width)
                            <span class="text-xs text-gray-500">(počítáno)</span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Výška:</span>
                        <span class="font-medium text-gray-900">
                            {{ number_format($dimensions['height'], 1) }} cm
                            @if(!$order->package_height)
                            <span class="text-xs text-gray-500">(počítáno)</span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600">Hmotnost:</span>
                        <span class="font-medium text-gray-900">
                            {{ number_format($order->getPackageWeight(), 2) }} kg
                            @if(!$order->package_weight)
                            <span class="text-xs text-gray-500">(počítáno)</span>
                            @endif
                        </span>
                    </div>
                </div>

                @if($order->packeta_shipment_status === 'submitted')
                <div class="mt-3 text-xs text-gray-500 italic">
                    Rozměry nelze měnit po odeslání do Packety
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Správa stavu</h3>
                
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stav objednávky</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Čeká</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Zpracovává se</option>
                            <option value="submitted" {{ $order->status === 'submitted' ? 'selected' : '' }}>Podáno</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Odesláno</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Doručeno</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Zrušeno</option>
                        </select>
                    </div>
                </form>
                
                <div class="pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600 mb-2">Aktuální stav:</div>
                    @if($order->status === 'pending')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Čeká</span>
                    @elseif($order->status === 'processing')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Zpracovává se</span>
                    @elseif($order->status === 'submitted')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Podáno
                    </span>
                    @elseif($order->status === 'shipped')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Odesláno</span>
                    @elseif($order->status === 'delivered')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Doručeno</span>
                    @elseif($order->status === 'cancelled')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Zrušeno</span>
                    @endif
                </div>

                @if($order->packeta_shipment_status === 'submitted' && $order->packeta_packet_id)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z"/>
                            </svg>
                            <span class="text-sm font-semibold text-green-900">Packeta zásilka</span>
                        </div>
                        <div class="text-sm space-y-1">
                            <p><span class="font-medium text-green-900">ID zásilky:</span> <span class="font-mono text-green-800">{{ $order->packeta_packet_id }}</span></p>
                            @if($order->packeta_sent_at)
                            <p><span class="font-medium text-green-900">Podáno:</span> <span class="text-green-800">{{ $order->packeta_sent_at->format('d.m.Y H:i') }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Informace o zákazníkovi</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <div class="text-gray-600">Jméno:</div>
                        <div class="font-medium text-gray-900">{{ $order->user->name ?? 'Host' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600">Email:</div>
                        <div class="font-medium text-gray-900">{{ $order->user->email ?? $order->email }}</div>
                    </div>
                    @if($order->user)
                    <div>
                        <div class="text-gray-600">Registrován:</div>
                        <div class="font-medium text-gray-900">{{ $order->user->created_at->format('d.m.Y') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Platba</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <div class="text-gray-600">Způsob platby:</div>
                        <div class="font-medium text-gray-900">
                            @if($order->payment_method === 'card')
                                Platební karta
                            @elseif($order->payment_method === 'transfer')
                                Bankovní převod
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-600">Stav platby:</div>
                        <div class="font-medium">
                            @if($order->payment_status === 'paid')
                            <span class="text-green-600">Zaplaceno</span>
                            @elseif($order->payment_status === 'pending')
                            <span class="text-yellow-600">Čeká na platbu</span>
                            @else
                            <span class="text-gray-600">{{ $order->payment_status }}</span>
                            @endif
                        </div>
                    </div>
                    @if($order->paid_at)
                    <div>
                        <div class="text-gray-600">Zaplaceno:</div>
                        <div class="font-medium text-gray-900">{{ $order->paid_at->format('d.m.Y H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Delete Order -->
            @if($order->status === 'pending')
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Opravdu chcete zrušit tuto objednávku?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Zrušit objednávku
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<!-- Edit Order Dimensions Modal -->
<div id="editOrderDimensionsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Upravit rozměry balíku</h3>
                <button type="button" onclick="closeEditOrderDimensionsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editOrderDimensionsForm" class="space-y-4">
                <input type="hidden" id="orderId" name="order_id">

                <div>
                    <label for="order_package_length" class="block text-sm font-medium text-gray-700">Délka (cm)</label>
                    <input type="number" step="0.01" id="order_package_length" name="package_length" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="order_package_width" class="block text-sm font-medium text-gray-700">Šířka (cm)</label>
                    <input type="number" step="0.01" id="order_package_width" name="package_width" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="order_package_height" class="block text-sm font-medium text-gray-700">Výška (cm)</label>
                    <input type="number" step="0.01" id="order_package_height" name="package_height" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="order_package_weight" class="block text-sm font-medium text-gray-700">Hmotnost (kg)</label>
                    <input type="number" step="0.01" id="order_package_weight" name="package_weight" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="order_admin_notes" class="block text-sm font-medium text-gray-700">Admin poznámka</label>
                    <textarea id="order_admin_notes" name="admin_notes" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div class="flex gap-2 mt-6">
                    <button type="button" onclick="saveOrderDimensions()"
                            class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Uložit
                    </button>
                    <button type="button" onclick="closeEditOrderDimensionsModal()"
                            class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                        Zrušit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditOrderDimensionsModal(orderId, length, width, height, weight) {
    document.getElementById('orderId').value = orderId;

    // Get default calculated values from the dimensions display
    const dimensionsDisplay = @json($order->getPackageDimensions());
    const weightDisplay = {{ $order->getPackageWeight() }};

    document.getElementById('order_package_length').value = length || dimensionsDisplay.length;
    document.getElementById('order_package_width').value = width || dimensionsDisplay.width;
    document.getElementById('order_package_height').value = height || dimensionsDisplay.height;
    document.getElementById('order_package_weight').value = weight || weightDisplay;
    document.getElementById('order_admin_notes').value = '';
    document.getElementById('editOrderDimensionsModal').classList.remove('hidden');
}

function closeEditOrderDimensionsModal() {
    document.getElementById('editOrderDimensionsModal').classList.add('hidden');
}

async function saveOrderDimensions() {
    const orderId = document.getElementById('orderId').value;
    const formData = {
        package_length: parseFloat(document.getElementById('order_package_length').value),
        package_width: parseFloat(document.getElementById('order_package_width').value),
        package_height: parseFloat(document.getElementById('order_package_height').value),
        package_weight: parseFloat(document.getElementById('order_package_weight').value),
        admin_notes: document.getElementById('order_admin_notes').value,
    };

    try {
        const response = await fetch(`/admin/orders/${orderId}/update-dimensions`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            closeEditOrderDimensionsModal();
            location.reload(); // Refresh to show updated data
        } else {
            alert(data.message || 'Chyba při ukládání');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Nastala chyba při ukládání dat');
    }
}

// Close modal on outside click
document.getElementById('editOrderDimensionsModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditOrderDimensionsModal();
    }
});
</script>

@endsection

