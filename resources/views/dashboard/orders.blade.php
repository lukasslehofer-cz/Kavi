@extends('layouts.dashboard')

@section('title', 'Objednávky - Kavi Coffee')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h1 class="text-3xl font-bold text-dark-800">Moje objednávky</h1>
        <p class="mt-2 text-dark-600">Historie všech vašich objednávek</p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-bluegray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Číslo objednávky
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Datum
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Stav
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Položky
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Celkem
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Akce
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr class="hover:bg-bluegray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-dark-800">
                            #{{ $order->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-dark-600">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status === 'completed')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">
                                    Dokončeno
                                </span>
                            @elseif($order->status === 'pending')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800">
                                    Čeká
                                </span>
                            @elseif($order->status === 'processing')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800">
                                    Zpracovává se
                                </span>
                            @elseif($order->status === 'cancelled')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">
                                    Zrušeno
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-dark-600">
                            {{ $order->items->count() }} {{ $order->items->count() == 1 ? 'položka' : 'položek' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-dark-800 font-bold">
                            {{ number_format($order->total, 2, ',', ' ') }} Kč
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('dashboard.order.detail', $order) }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-bluegray-50">
            {{ $orders->links() }}
        </div>
        @else
        <div class="text-center py-12 px-4">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-bluegray-100 flex items-center justify-center">
                <svg class="h-8 w-8 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-dark-800 mb-2">Žádné objednávky</h3>
            <p class="text-dark-600 mb-6">Zatím jste neprovedli žádnou objednávku.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Prohlédnout produkty
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
