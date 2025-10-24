@extends('layouts.app')

@section('title', 'Rozesílka předplatných - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Rozesílka {{ $targetDate->format('d.m.Y') }}</h1>
                <p class="text-coffee-600">Seznam předplatných k rozeslání</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline">
                    Všechna předplatná
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
                    ← Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card p-6 text-center border-l-4 border-primary-500">
            <p class="text-3xl font-bold text-primary-700">{{ $stats['total'] }}</p>
            <p class="text-sm text-coffee-600 mt-1">Celkem k rozeslání</p>
        </div>
        <div class="card p-6 text-center">
            <p class="text-2xl font-bold text-coffee-900">{{ $stats['monthly'] }}</p>
            <p class="text-sm text-coffee-600 mt-1">Měsíční</p>
        </div>
        <div class="card p-6 text-center">
            <p class="text-2xl font-bold text-coffee-900">{{ $stats['bimonthly'] }}</p>
            <p class="text-sm text-coffee-600 mt-1">Jednou za 2 měsíce</p>
        </div>
        <div class="card p-6 text-center">
            <p class="text-2xl font-bold text-coffee-900">{{ $stats['quarterly'] }}</p>
            <p class="text-sm text-coffee-600 mt-1">Jednou za 3 měsíce</p>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="card p-6 mb-6">
        <form action="{{ route('admin.subscriptions.shipments') }}" method="GET" class="flex items-end gap-4">
            <div class="flex-1 max-w-xs">
                <label class="block text-sm font-medium text-coffee-700 mb-2">Zobrazit rozesílku pro:</label>
                <input 
                    type="date" 
                    name="date" 
                    value="{{ $targetDate->format('Y-m-d') }}" 
                    class="input"
                >
            </div>
            <button type="submit" class="btn btn-primary">Zobrazit</button>
        </form>
        <p class="text-xs text-coffee-600 mt-3">
            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            Rozesílka probíhá vždy <strong>20. den v měsíci</strong>. Zobrazují se jen předplatná, která mají být odeslána v daném termínu podle frekvence.
        </p>
    </div>

    <!-- Subscriptions Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-cream-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Konfigurace</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Frekvence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Výdejní místo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Poslední dodávka</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-cream-200">
                    @forelse($subscriptions as $subscription)
                    <tr class="hover:bg-cream-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-medium">#{{ $subscription->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($subscription->user)
                                <div class="font-medium text-coffee-900">{{ $subscription->user->name }}</div>
                                <div class="text-coffee-500 text-xs">{{ $subscription->user->email }}</div>
                                @else
                                <div class="text-coffee-500 italic">Host</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($subscription->plan)
                                <div class="font-medium text-coffee-900">{{ $subscription->plan->name }}</div>
                                @else
                                @if($subscription->configuration)
                                    @php
                                        $config = is_string($subscription->configuration) 
                                            ? json_decode($subscription->configuration, true) 
                                            : $subscription->configuration;
                                    @endphp
                                    <div class="font-medium text-coffee-900">{{ $config['amount'] ?? '' }}× balení</div>
                                    <div class="text-xs text-coffee-600">
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
                            <span class="badge badge-success">Měsíční</span>
                            @elseif($subscription->frequency_months == 2)
                            <span class="badge" style="background-color: #dbeafe; color: #1e40af;">2 měsíce</span>
                            @elseif($subscription->frequency_months == 3)
                            <span class="badge" style="background-color: #e0e7ff; color: #4338ca;">3 měsíce</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($subscription->packeta_point_name)
                            <div class="text-sm">
                                <div class="font-medium text-coffee-900">{{ $subscription->packeta_point_name }}</div>
                                <div class="text-xs text-coffee-600">ID: {{ $subscription->packeta_point_id }}</div>
                            </div>
                            @else
                            <span class="text-sm text-coffee-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-coffee-600">
                            @if($subscription->last_shipment_date)
                            {{ $subscription->last_shipment_date->format('d.m.Y') }}
                            @else
                            <span class="text-coffee-500 italic">První dodávka</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="text-coffee-700 hover:text-coffee-900 font-medium underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-coffee-600">
                            Pro toto datum není naplánována žádná rozesílka
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($subscriptions->isNotEmpty())
    <!-- Export/Print Options -->
    <div class="mt-6 card p-6">
        <h3 class="font-semibold text-coffee-900 mb-3">Akce</h3>
        <div class="flex gap-3">
            <button onclick="window.print()" class="btn btn-outline">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Tisknout seznam
            </button>
            <button class="btn btn-outline" onclick="alert('Export do CSV bude implementován')">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportovat CSV
            </button>
        </div>
    </div>
    @endif
</div>

<style>
@media print {
    .btn, nav, header, footer {
        display: none !important;
    }
}
</style>
@endsection

