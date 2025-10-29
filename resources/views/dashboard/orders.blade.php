@extends('layouts.dashboard')

@section('title', 'Objednávky - Kavi Coffee')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900">Moje objednávky</h1>
        <p class="mt-2 text-gray-600 font-light">Historie všech vašich objednávek</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Číslo objednávky
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Datum
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Stav
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Položky
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Celkem
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Akce
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors {{ $order->payment_status === 'unpaid' ? 'bg-red-50 border-l-4 border-red-500' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            {{ $order->order_number ?? '#' . $order->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-light">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->payment_status === 'unpaid')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 border border-red-300">
                                    ⚠️ Neuhrazeno
                                </span>
                            @elseif($order->status === 'completed')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800">
                                    Dokončeno
                                </span>
                            @elseif($order->status === 'pending')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    Čeká
                                </span>
                            @elseif($order->status === 'processing')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-blue-100 text-blue-800">
                                    Zpracovává se
                                </span>
                            @elseif($order->status === 'cancelled')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-red-100 text-red-800">
                                    Zrušeno
                                </span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-light">
                            {{ $order->items->count() }} {{ $order->items->count() == 1 ? 'položka' : 'položek' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                            {{ number_format($order->total, 2, ',', ' ') }} Kč
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($order->payment_status === 'unpaid')
                            <form method="POST" action="{{ route('order.pay', $order) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-full transition-colors text-xs">
                                    Zaplatit
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('dashboard.order.detail', $order) }}" class="text-primary-600 hover:text-primary-700 font-medium {{ $order->payment_status === 'unpaid' ? 'ml-2' : '' }}">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50">
            {{ $orders->links() }}
        </div>
        @else
        <div class="text-center py-12 px-4">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">Žádné objednávky</h3>
            <p class="text-gray-600 font-light mb-6">Zatím jste neprovedli žádnou objednávku.</p>
            <a href="{{ route('products.index') }}" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200 inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Prohlédnout produkty
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
