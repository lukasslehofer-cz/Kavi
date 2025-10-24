@extends('layouts.app')

@section('title', 'Detail objednávky - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Objednávka {{ $order->order_number }}</h1>
                <p class="text-coffee-600">{{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">
                ← Zpět na seznam
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="card">
                <div class="p-6 border-b border-cream-200">
                    <h2 class="font-display text-2xl font-bold text-coffee-900">Položky objednávky</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-cream-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Produkt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Cena</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Množství</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Celkem</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-cream-200">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($item->product_image)
                                        <img src="{{ Storage::url($item->product_image) }}" alt="{{ $item->product_name }}" class="w-12 h-12 object-cover rounded mr-4">
                                        @endif
                                        <span class="font-medium text-coffee-900">{{ $item->product_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-coffee-900">
                                    {{ number_format($item->price, 0, ',', ' ') }} Kč
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-coffee-900">
                                    {{ $item->quantity }}×
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-coffee-900">
                                    {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} Kč
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-cream-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-coffee-900">
                                    Mezisoučet:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-coffee-900">
                                    {{ number_format($order->subtotal, 0, ',', ' ') }} Kč
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-coffee-900">
                                    Doprava:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-coffee-900">
                                    {{ number_format($order->shipping, 0, ',', ' ') }} Kč
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium text-coffee-900">
                                    DPH (21%):
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-coffee-900">
                                    {{ number_format($order->tax, 2, ',', ' ') }} Kč
                                </td>
                            </tr>
                            <tr class="border-t-2 border-coffee-900">
                                <td colspan="3" class="px-6 py-4 text-right text-lg font-bold text-coffee-900">
                                    Celkem (včetně DPH):
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-coffee-900">
                                    {{ number_format($order->total, 0, ',', ' ') }} Kč
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card p-6">
                <h3 class="font-display text-xl font-bold text-coffee-900 mb-4">Doručovací adresa</h3>
                <div class="text-coffee-700">
                    <p class="font-medium">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_postal_code }} {{ $order->shipping_city }}</p>
                    @if($order->shipping_phone)
                    <p class="mt-2">Tel: {{ $order->shipping_phone }}</p>
                    @endif
                </div>
            </div>

            <!-- Delivery Notes -->
            @if($order->delivery_notes)
            <div class="card p-6">
                <h3 class="font-display text-xl font-bold text-coffee-900 mb-4">Poznámka k objednávce</h3>
                <p class="text-coffee-700">{{ $order->delivery_notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="card p-6">
                <h3 class="font-display text-xl font-bold text-coffee-900 mb-4">Správa stavu</h3>
                
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-coffee-700 mb-2">Stav objednávky</label>
                        <select name="status" class="input" onchange="this.form.submit()">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Čeká</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Zpracovává se</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Odesláno</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Doručeno</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Zrušeno</option>
                        </select>
                    </div>
                </form>
                
                <div class="pt-4 border-t border-cream-200">
                    <div class="text-sm text-coffee-600 mb-2">Aktuální stav:</div>
                    @if($order->status === 'pending')
                    <span class="badge badge-warning">Čeká</span>
                    @elseif($order->status === 'processing')
                    <span class="badge" style="background-color: #dbeafe; color: #1e40af;">Zpracovává se</span>
                    @elseif($order->status === 'shipped')
                    <span class="badge" style="background-color: #e0e7ff; color: #4338ca;">Odesláno</span>
                    @elseif($order->status === 'delivered')
                    <span class="badge badge-success">Doručeno</span>
                    @elseif($order->status === 'cancelled')
                    <span class="badge" style="background-color: #fee2e2; color: #991b1b;">Zrušeno</span>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card p-6">
                <h3 class="font-display text-xl font-bold text-coffee-900 mb-4">Informace o zákazníkovi</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <div class="text-coffee-600">Jméno:</div>
                        <div class="font-medium text-coffee-900">{{ $order->user->name ?? 'Host' }}</div>
                    </div>
                    <div>
                        <div class="text-coffee-600">Email:</div>
                        <div class="font-medium text-coffee-900">{{ $order->user->email ?? $order->email }}</div>
                    </div>
                    @if($order->user)
                    <div>
                        <div class="text-coffee-600">Registrován:</div>
                        <div class="font-medium text-coffee-900">{{ $order->user->created_at->format('d.m.Y') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card p-6">
                <h3 class="font-display text-xl font-bold text-coffee-900 mb-4">Platba</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <div class="text-coffee-600">Způsob platby:</div>
                        <div class="font-medium text-coffee-900">
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
                        <div class="text-coffee-600">Stav platby:</div>
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
                        <div class="text-coffee-600">Zaplaceno:</div>
                        <div class="font-medium text-coffee-900">{{ $order->paid_at->format('d.m.Y H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Delete Order -->
            @if($order->status === 'pending')
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Opravdu chcete zrušit tuto objednávku?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn w-full bg-red-600 text-white hover:bg-red-700">
                    Zrušit objednávku
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

