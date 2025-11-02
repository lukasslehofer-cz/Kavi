@extends('layouts.dashboard')

@section('title', 'Detail objednávky - KAVI.cz')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <a href="{{ route('dashboard.orders') }}" class="text-primary-600 hover:text-primary-700 font-medium mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Zpět na objednávky
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2">Detail objednávky {{ $order->order_number ?? '#' . $order->id }}</h1>
        <p class="mt-2 text-gray-600 font-light">Vytvořeno: {{ $order->created_at->format('d.m.Y H:i') }}</p>
    </div>

    <!-- Order Status -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4">Stav objednávky</h2>
        <div class="flex items-center">
            @if($order->status === 'completed')
                <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                    ✓ Dokončeno
                </span>
            @elseif($order->status === 'pending')
                <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                    ⏱ Čeká na zpracování
                </span>
            @elseif($order->status === 'processing')
                <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                    🔄 Zpracovává se
                </span>
            @elseif($order->status === 'cancelled')
                <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-red-100 text-red-800 border border-red-200">
                    ✕ Zrušeno
                </span>
            @else
                <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                    {{ ucfirst($order->status) }}
                </span>
            @endif
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-base font-bold text-gray-900">Položky objednávky</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($order->items as $item)
            <div class="p-6 flex justify-between items-center hover:bg-gray-50 transition-colors">
                <div class="flex-1">
                    <h3 class="text-base font-bold text-gray-900">{{ $item->product_name }}</h3>
                    <p class="text-sm text-gray-600 font-light mt-1">
                        Množství: {{ $item->quantity }} × {{ number_format($item->price, 2, ',', ' ') }} Kč
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-base font-bold text-primary-600">
                        {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} Kč
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-base font-bold text-gray-900">Souhrn objednávky</h2>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <div class="flex justify-between text-gray-600 font-light">
                    <span>Mezisoučet:</span>
                    <span class="font-medium">{{ number_format($order->subtotal, 2, ',', ' ') }} Kč</span>
                </div>
                @if($order->tax > 0)
                <div class="flex justify-between text-gray-600 font-light">
                    <span>DPH:</span>
                    <span class="font-medium">{{ number_format($order->tax, 2, ',', ' ') }} Kč</span>
                </div>
                @endif
                @if($order->shipping > 0)
                <div class="flex justify-between text-gray-600 font-light">
                    <span>Doprava:</span>
                    <span class="font-medium">{{ number_format($order->shipping, 2, ',', ' ') }} Kč</span>
                </div>
                @endif
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex justify-between text-xl font-bold">
                        <span class="text-gray-900">Celkem:</span>
                        <span class="text-primary-600">{{ number_format($order->total, 2, ',', ' ') }} Kč</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($order->stripe_payment_intent_id)
    <div class="bg-blue-50 border border-blue-200 bg-blue-50 rounded-xl p-4">
        <p class="text-sm text-gray-900">
            <span class="font-bold">ID platby:</span> {{ $order->stripe_payment_intent_id }}
        </p>
    </div>
    @endif

    @if($order->invoice_pdf_path)
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4">Faktura</h2>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Daňový doklad</p>
                    <p class="text-xs text-gray-600 font-light">Objednávka {{ $order->order_number }}</p>
                </div>
            </div>
            <a href="{{ route('dashboard.order.invoice', $order) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Stáhnout fakturu
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
