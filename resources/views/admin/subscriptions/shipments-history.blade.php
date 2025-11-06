@extends('layouts.admin')

@section('title', 'Historie odeslaných zásilek')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Historie odeslaných zásilek</h1>
            <p class="text-gray-600 mt-1">Přehled všech odeslaných předplatných</p>
        </div>
        <a href="{{ route('admin.subscriptions.shipments') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nová rozesílka
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="relative overflow-hidden bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-sm">
            <div class="relative z-10">
                <p class="text-4xl font-bold">{{ $stats['total'] }}</p>
                <p class="text-sm text-white/80 mt-1">Celkem odesláno</p>
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['this_month'] }}</p>
            <p class="text-sm text-gray-600 mt-1">Tento měsíc</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-200">
        <form action="{{ route('admin.subscriptions.shipments.history') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hledat</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Číslo předplatného nebo jméno zákazníka"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Měsíc/Rok</label>
                <select name="month_year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Všechny</option>
                    @foreach($availableMonths as $monthData)
                    <option value="{{ $monthData->year }}-{{ $monthData->month }}" 
                        {{ request('year') == $monthData->year && request('month') == $monthData->month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromDate($monthData->year, $monthData->month, 1)->format('F Y') }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Filtrovat
                </button>
                @if(request('search') || request('month_year'))
                <a href="{{ route('admin.subscriptions.shipments.history') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Zrušit
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Shipments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Předplatné</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zákazník</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum rozesílky</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Packeta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rozměry</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Faktura</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Odesláno</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($shipments as $shipment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-mono font-medium text-gray-900">
                                    {{ $shipment->subscription->subscription_number ?? '#' . $shipment->subscription_id }}
                                </div>
                                @if($shipment->subscription->plan)
                                <div class="text-xs text-gray-500">{{ $shipment->subscription->plan->name }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($shipment->subscription->user)
                                <div class="font-medium text-gray-900">{{ $shipment->subscription->user->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $shipment->subscription->user->email }}</div>
                                @else
                                <div class="text-gray-500 italic">Host</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $shipment->shipment_date->format('d.m.Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($shipment->packeta_packet_id)
                            <div class="text-sm">
                                <div class="font-mono text-gray-900">{{ $shipment->packeta_packet_id }}</div>
                                @if($shipment->packeta_tracking_url)
                                <a href="{{ $shipment->packeta_tracking_url }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                    Sledovat zásilku →
                                </a>
                                @endif
                            </div>
                            @else
                            <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $shipment->package_length }}×{{ $shipment->package_width }}×{{ $shipment->package_height }} cm</div>
                                <div class="text-xs text-gray-500">{{ $shipment->package_weight }} kg</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($shipment->payment)
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">#{{ $shipment->payment->id }}</div>
                                @if($shipment->payment->fakturoid_invoice_id)
                                <div class="text-xs text-gray-500">FID: {{ $shipment->payment->fakturoid_invoice_id }}</div>
                                @endif
                                @if($shipment->payment->invoice_pdf_path)
                                <a href="{{ Storage::url($shipment->payment->invoice_pdf_path) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                    Stáhnout PDF →
                                </a>
                                @endif
                            </div>
                            @else
                            <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($shipment->sent_at)
                            <span class="text-sm text-gray-900">{{ $shipment->sent_at->format('d.m.Y H:i') }}</span>
                            @else
                            <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.subscriptions.show', $shipment->subscription_id) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                Detail předplatného →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-lg font-medium">Zatím nebyly odeslány žádné zásilky</p>
                                <p class="mt-1">Odeslané zásilky se zde objeví po prvním odeslání</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($shipments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $shipments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

