@extends('layouts.admin')

@section('title', 'Rozesílka předplatných')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Rozesílka {{ $targetDate->format('d.m.Y') }}</h1>
            <p class="text-gray-600 mt-1">Seznam předplatných k rozeslání</p>
        </div>
        <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            Všechna předplatná
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-sm">
            <div class="relative z-10">
                <p class="text-4xl font-bold">{{ $stats['total'] }}</p>
                <p class="text-sm text-white/80 mt-1">Celkem k rozeslání</p>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['monthly'] }}</p>
            <p class="text-sm text-gray-600 mt-1">Měsíční</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['bimonthly'] }}</p>
            <p class="text-sm text-gray-600 mt-1">Jednou za 2 měsíce</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['quarterly'] }}</p>
            <p class="text-sm text-gray-600 mt-1">Jednou za 3 měsíce</p>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-200">
        <form action="{{ route('admin.subscriptions.shipments') }}" method="GET" class="flex items-end gap-4">
            <div class="flex-1 max-w-xs">
                <label class="block text-sm font-medium text-gray-700 mb-2">Zobrazit rozesílku pro:</label>
                <input 
                    type="date" 
                    name="date" 
                    value="{{ $targetDate->format('Y-m-d') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">Zobrazit</button>
        </form>
        <p class="flex items-center gap-2 text-sm text-gray-600 mt-4 bg-blue-50 p-3 rounded-lg">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <span>Rozesílka probíhá vždy <strong>20. den v měsíci</strong>. Zobrazují se jen předplatná, která mají být odeslána v daném termínu podle frekvence.</span>
        </p>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <form id="shipments-form" action="{{ route('admin.subscriptions.send-to-packeta') }}" method="POST">
                @csrf
                <input type="hidden" name="target_date" value="{{ $targetDate->format('Y-m-d') }}">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zákazník</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konfigurace</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frekvence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Výdejní místo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poslední dodávka</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50 transition-colors {{ $subscription->packeta_shipment_status === 'sent' ? 'bg-green-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($subscription->packeta_shipment_status !== 'sent')
                                <input type="checkbox" name="subscription_ids[]" value="{{ $subscription->id }}" class="shipment-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-medium text-gray-900">#{{ $subscription->id }}</span>
                            </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($subscription->user)
                                <div class="font-medium text-gray-900">{{ $subscription->user->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $subscription->user->email }}</div>
                                @else
                                <div class="text-gray-500 italic">Host</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($subscription->plan)
                                <div class="font-medium text-gray-900">{{ $subscription->plan->name }}</div>
                                @else
                                @if($subscription->configuration)
                                    @php
                                        $config = is_string($subscription->configuration) 
                                            ? json_decode($subscription->configuration, true) 
                                            : $subscription->configuration;
                                    @endphp
                                    <div class="font-medium text-gray-900">{{ $config['amount'] ?? '' }}× balení</div>
                                    <div class="text-xs text-gray-600">
                                        @if($config['type'] === 'espresso')
                                            Espresso
                                        @elseif($config['type'] === 'filter')
                                            Filter
                                        @else
                                            Mix
                                        @endif
                                    </div>
                                @endif
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($subscription->frequency_months == 1)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Měsíční</span>
                            @elseif($subscription->frequency_months == 2)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">2 měsíce</span>
                            @elseif($subscription->frequency_months == 3)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">3 měsíce</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($subscription->packeta_point_name)
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $subscription->packeta_point_name }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $subscription->packeta_point_id }}</div>
                            </div>
                            @else
                            <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($subscription->packeta_shipment_status === 'sent')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Podáno
                            </span>
                            @if($subscription->packeta_packet_id)
                            <div class="text-xs text-gray-600 mt-1">ID: {{ $subscription->packeta_packet_id }}</div>
                            @endif
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                Čeká
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($subscription->last_shipment_date)
                            {{ $subscription->last_shipment_date->format('d.m.Y') }}
                            @else
                            <span class="text-gray-500 italic">První dodávka</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Detail →
                            </a>
                        </td>
                    </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                                </svg>
                                Pro toto datum není naplánována žádná rozesílka
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    @if($subscriptions->isNotEmpty())
    <!-- Export/Print Options -->
    <div class="mt-6 bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <h3 class="font-semibold text-gray-900 mb-4">Akce</h3>
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
            <button class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors" onclick="alert('Export do CSV bude implementován')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportovat CSV
            </button>
        </div>
        <p class="flex items-center gap-2 text-sm text-gray-600 mt-4 bg-blue-50 p-3 rounded-lg">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <span>Zaškrtněte zásilky, které chcete odeslat do systému Packeta. Po odeslání budou označeny jako "Podáno".</span>
        </p>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const shipmentCheckboxes = document.querySelectorAll('.shipment-checkbox');
    const sendButton = document.getElementById('send-to-packeta-btn');
    const shipmentsForm = document.getElementById('shipments-form');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            shipmentCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    }

    // Send to Packeta button
    if (sendButton) {
        sendButton.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.shipment-checkbox:checked');
            
            if (checkedBoxes.length === 0) {
                alert('Prosím vyberte alespoň jednu zásilku k odeslání.');
                return;
            }

            const count = checkedBoxes.length;
            const message = `Opravdu chcete odeslat ${count} ${count === 1 ? 'zásilku' : (count < 5 ? 'zásilky' : 'zásilek')} do systému Packeta?`;
            
            if (confirm(message)) {
                // Show loading state
                sendButton.disabled = true;
                sendButton.innerHTML = '<svg class="animate-spin h-5 w-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Odesílání...';
                
                shipmentsForm.submit();
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

