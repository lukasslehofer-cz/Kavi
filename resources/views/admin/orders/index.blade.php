@extends('layouts.admin')

@section('title', 'Správa objednávek')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Správa objednávek</h1>
        <p class="text-gray-600 mt-1">Přehled a správa všech objednávek</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-sm text-gray-600 mt-1">Celkem</p>
        </div>
        <div class="bg-red-50 rounded-lg p-4 text-center shadow-sm border-2 border-red-300 {{ ($stats['unpaid'] ?? 0) > 0 ? 'ring-2 ring-red-500 ring-offset-2' : '' }}">
            <p class="text-3xl font-bold text-red-700">{{ $stats['unpaid'] ?? 0 }}</p>
            <p class="text-sm text-red-600 mt-1 font-semibold">⚠️ Neuhrazeno</p>
        </div>
        <div class="bg-amber-50 rounded-lg p-4 text-center shadow-sm border border-amber-200">
            <p class="text-3xl font-bold text-amber-700">{{ $stats['pending'] }}</p>
            <p class="text-sm text-amber-600 mt-1">Čeká</p>
        </div>
        <div class="bg-blue-50 rounded-lg p-4 text-center shadow-sm border border-blue-200">
            <p class="text-3xl font-bold text-blue-700">{{ $stats['processing'] }}</p>
            <p class="text-sm text-blue-600 mt-1">Zpracovává se</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-4 text-center shadow-sm border border-purple-200">
            <p class="text-3xl font-bold text-purple-700">{{ $stats['shipped'] }}</p>
            <p class="text-sm text-purple-600 mt-1">Odesláno</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4 text-center shadow-sm border border-green-200">
            <p class="text-3xl font-bold text-green-700">{{ $stats['delivered'] }}</p>
            <p class="text-sm text-green-600 mt-1">Doručeno</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-200">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hledat</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Číslo objednávky nebo jméno zákazníka"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Stav</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Všechny</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Čeká</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Zpracovává se</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Odesláno</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Doručeno</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Zrušeno</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Filtrovat
                </button>
                @if(request('search') || request('status'))
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Zrušit
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Číslo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celkem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platba</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors {{ $order->payment_status === 'unpaid' ? 'bg-red-50 border-l-4 border-red-500' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-medium text-gray-900">{{ $order->order_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $order->user->name ?? 'Host' }}</div>
                                <div class="text-gray-500">{{ $order->user->email ?? $order->email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">{{ number_format($order->total, 0, ',', ' ') }} Kč</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Čeká</span>
                            @elseif($order->status === 'processing')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Zpracovává se</span>
                            @elseif($order->status === 'shipped')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Odesláno</span>
                            @elseif($order->status === 'delivered')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Doručeno</span>
                            @elseif($order->status === 'cancelled')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Zrušeno</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->payment_status === 'paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Zaplaceno</span>
                            @elseif($order->payment_status === 'unpaid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                                ⚠️ Neuhrazeno
                            </span>
                            @if($order->payment_failure_count > 0)
                            <div class="text-xs text-red-700 mt-1">
                                Pokusů: {{ $order->payment_failure_count }}
                            </div>
                            @endif
                            @elseif($order->payment_status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Čeká</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $order->payment_status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Žádné objednávky nenalezeny
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection


