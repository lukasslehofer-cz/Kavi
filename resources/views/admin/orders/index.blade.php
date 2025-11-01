@extends('layouts.admin')

@section('title', 'Správa objednávek')

@section('content')
<div class="p-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('warning'))
    <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ session('warning') }}</p>
        @if(session('errors'))
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach(session('errors') as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Správa objednávek</h1>
        <p class="text-gray-600 mt-1">Přehled a správa všech objednávek</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-7 gap-4 mb-8">
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
        <div class="bg-indigo-50 rounded-lg p-4 text-center shadow-sm border border-indigo-200">
            <p class="text-3xl font-bold text-indigo-700">{{ $stats['submitted'] ?? 0 }}</p>
            <p class="text-sm text-indigo-600 mt-1">Podáno</p>
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
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Podáno</option>
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
            <form id="orders-form" action="{{ route('admin.orders.send-to-packeta') }}" method="POST">
                @csrf
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Číslo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zákazník</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celkem</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doprava</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platba</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors {{ $order->payment_status === 'unpaid' ? 'bg-red-50 border-l-4 border-red-500' : '' }} {{ $order->packeta_shipment_status === 'submitted' ? 'bg-green-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->packeta_shipment_status !== 'submitted' && $order->payment_status === 'paid')
                                <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                @endif
                            </td>
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
                            @if($order->shipped_with_subscription)
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        📦 S předplatným
                                    </span>
                                    @if($order->subscription)
                                        <a href="{{ route('admin.subscriptions.show', $order->subscription) }}" 
                                           class="text-xs text-purple-600 hover:underline">
                                            {{ $order->subscription->subscription_number ?? '#' . $order->subscription->id }}
                                        </a>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm text-gray-600">Standardní</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                            @if($order->packeta_packet_id)
                            <div class="text-xs text-gray-600 mt-1">ID: {{ $order->packeta_packet_id }}</div>
                            @endif
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
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Žádné objednávky nenalezeny
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </form>
        </div>

        @if($orders->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

    @if($orders->isNotEmpty())
    <!-- Bulk Actions -->
    <div class="mt-6 bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <h3 class="font-semibold text-gray-900 mb-4">Hromadné akce</h3>
        <div class="flex gap-3 flex-wrap">
            <button type="button" id="send-to-packeta-btn" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Odeslat vybrané do Packety
            </button>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Tisknout seznam
            </button>
        </div>
        <p class="flex items-center gap-2 text-sm text-gray-600 mt-4 bg-blue-50 p-3 rounded-lg">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <span>Zaškrtněte objednávky, které chcete odeslat do systému Packeta. Lze vybrat pouze zaplacené objednávky, které ještě nebyly podány.</span>
        </p>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const sendButton = document.getElementById('send-to-packeta-btn');
    const ordersForm = document.getElementById('orders-form');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    }

    // Send to Packeta button
    if (sendButton) {
        sendButton.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                alert('Prosím vyberte alespoň jednu objednávku k odeslání.');
                return;
            }

            const count = checkedBoxes.length;
            const message = `Opravdu chcete odeslat ${count} ${count === 1 ? 'objednávku' : (count < 5 ? 'objednávky' : 'objednávek')} do systému Packeta?`;
            
            if (confirm(message)) {
                // Show loading state
                sendButton.disabled = true;
                sendButton.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Odesílání...';
                
                ordersForm.submit();
            }
        });
    }
});
</script>

<style>
@media print {
    .btn, nav, header, footer {
        display: none !important;
    }
}
</style>
@endsection


