@extends('layouts.dashboard')

@section('title', 'Detail objednávky - Kavi Coffee')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <a href="{{ route('dashboard.orders') }}" class="text-primary-600 hover:text-primary-700 font-semibold mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Zpět na objednávky
        </a>
        <h1 class="text-3xl font-bold text-dark-800 mt-2">Detail objednávky #{{ $order->id }}</h1>
        <p class="mt-2 text-dark-600">Vytvořeno: {{ $order->created_at->format('d.m.Y H:i') }}</p>
    </div>

    <!-- Order Status -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-dark-800 mb-4">Stav objednávky</h2>
        <div class="flex items-center">
            @if($order->status === 'completed')
                <span class="px-4 py-2 text-base font-bold rounded-full bg-green-100 text-green-800 border-2 border-green-500">
                    ✓ Dokončeno
                </span>
            @elseif($order->status === 'pending')
                <span class="px-4 py-2 text-base font-bold rounded-full bg-yellow-100 text-yellow-800 border-2 border-yellow-500">
                    ⏱ Čeká na zpracování
                </span>
            @elseif($order->status === 'processing')
                <span class="px-4 py-2 text-base font-bold rounded-full bg-blue-100 text-blue-800 border-2 border-blue-500">
                    🔄 Zpracovává se
                </span>
            @elseif($order->status === 'cancelled')
                <span class="px-4 py-2 text-base font-bold rounded-full bg-red-100 text-red-800 border-2 border-red-500">
                    ✕ Zrušeno
                </span>
            @else
                <span class="px-4 py-2 text-base font-bold rounded-full bg-gray-100 text-gray-800 border-2 border-gray-500">
                    {{ ucfirst($order->status) }}
                </span>
            @endif
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-bluegray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-dark-800">Položky objednávky</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($order->items as $item)
            <div class="p-6 flex justify-between items-center hover:bg-bluegray-50 transition-colors">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-dark-800">{{ $item->product_name }}</h3>
                    <p class="text-sm text-dark-600 mt-1">
                        Množství: {{ $item->quantity }} × {{ number_format($item->price, 2, ',', ' ') }} Kč
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-primary-600">
                        {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} Kč
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-bluegray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-dark-800">Souhrn objednávky</h2>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <div class="flex justify-between text-dark-600">
                    <span>Mezisoučet:</span>
                    <span class="font-semibold">{{ number_format($order->subtotal, 2, ',', ' ') }} Kč</span>
                </div>
                @if($order->tax > 0)
                <div class="flex justify-between text-dark-600">
                    <span>DPH:</span>
                    <span class="font-semibold">{{ number_format($order->tax, 2, ',', ' ') }} Kč</span>
                </div>
                @endif
                @if($order->shipping > 0)
                <div class="flex justify-between text-dark-600">
                    <span>Doprava:</span>
                    <span class="font-semibold">{{ number_format($order->shipping, 2, ',', ' ') }} Kč</span>
                </div>
                @endif
                <div class="border-t-2 border-gray-200 pt-3 mt-3">
                    <div class="flex justify-between text-2xl font-bold">
                        <span class="text-dark-800">Celkem:</span>
                        <span class="text-primary-600">{{ number_format($order->total, 2, ',', ' ') }} Kč</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($order->stripe_payment_intent_id)
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-xl p-4">
        <p class="text-sm text-dark-700">
            <span class="font-bold">ID platby:</span> {{ $order->stripe_payment_intent_id }}
        </p>
    </div>
    @endif
</div>
@endsection
