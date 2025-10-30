<div class="space-y-6">
    @foreach($schedules as $schedule)
        @php
            $isPast = $schedule->isPast();
            $monthDate = sprintf('%04d-%02d', $year, $schedule->month);
            $monthCoffees = \App\Models\Product::where('is_coffee_of_month', true)
                ->where('coffee_of_month_date', $monthDate)
                ->orderBy('name')
                ->get();
        @endphp
        
        <div class="bg-white rounded-lg border-2 {{ $isPast ? 'border-gray-200 bg-gray-50' : 'border-blue-200' }} p-6">
            <form action="{{ route('admin.subscription-config.update-single-schedule', $schedule) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $schedule->month_name }} {{ $year }}
                        @if($isPast)
                        <span class="ml-2 text-sm text-gray-500 font-normal">(minulost - nelze editovat)</span>
                        @endif
                    </h3>
                    @if(!$isPast)
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        💾 Uložit {{ $schedule->month_name }}
                    </button>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Column 1: Dates -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Datum platby</label>
                        @if($isPast)
                            <div class="text-gray-600 py-2">{{ $schedule->billing_date->format('d.m.Y') }}</div>
                        @else
                            <input 
                                type="date" 
                                name="billing_date" 
                                value="{{ $schedule->billing_date->format('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required
                            >
                        @endif
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2 mt-4">Datum rozesílky</label>
                        @if($isPast)
                            <div class="text-gray-600 py-2">{{ $schedule->shipment_date->format('d.m.Y') }}</div>
                        @else
                            <input 
                                type="date" 
                                name="shipment_date" 
                                value="{{ $schedule->shipment_date->format('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                required
                            >
                        @endif
                        
                        <label class="block text-sm font-medium text-gray-700 mb-2 mt-4">Poznámka</label>
                        @if($isPast)
                            <div class="text-gray-600 py-2">{{ $schedule->notes ?? '—' }}</div>
                        @else
                            <input 
                                type="text" 
                                name="notes" 
                                value="{{ $schedule->notes }}"
                                placeholder="Poznámka"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                        @endif
                    </div>
                    
                    <!-- Column 2: Coffee Slots -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Kávy měsíce (sloty)</label>
                        
                        @if($isPast)
                            @php
                                $coffeeSlots = $schedule->getCoffeeSlotsArray();
                            @endphp
                            @if(!empty(array_filter($coffeeSlots)))
                                <div class="space-y-2 text-sm">
                                    @foreach(['e1', 'e2', 'e3', 'f1', 'f2', 'f3', 'd'] as $slot)
                                        @if(!empty($coffeeSlots[$slot]))
                                            @php
                                                $coffee = \App\Models\Product::find($coffeeSlots[$slot]);
                                            @endphp
                                            @if($coffee)
                                                <div class="flex items-center gap-2">
                                                    <span class="font-semibold w-8">{{ strtoupper($slot) }}:</span>
                                                    <span class="text-gray-700">{{ $coffee->name }}</span>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-gray-500">—</div>
                            @endif
                        @else
                            <div class="grid grid-cols-2 gap-3">
                                @foreach([
                                    'e1' => 'E1 (Espresso)', 
                                    'e2' => 'E2 (Espresso)', 
                                    'e3' => 'E3 (Espresso)', 
                                    'f1' => 'F1 (Filtr)', 
                                    'f2' => 'F2 (Filtr)', 
                                    'f3' => 'F3 (Filtr)', 
                                    'd' => 'D (Decaf)'
                                ] as $slotKey => $slotLabel)
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">{{ $slotLabel }}</label>
                                        <select 
                                            name="coffee_slot_{{ $slotKey }}" 
                                            class="w-full text-sm px-2 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                        >
                                            <option value="">-- Vyberte --</option>
                                            @foreach($monthCoffees as $coffee)
                                                <option value="{{ $coffee->id }}" 
                                                    {{ $schedule->{'coffee_slot_' . $slotKey} == $coffee->id ? 'selected' : '' }}>
                                                    {{ $coffee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    <!-- Column 3: Promo Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Promo obrázek</label>
                        
                        @if($isPast)
                            @if($schedule->promo_image)
                                <img src="{{ asset('storage/' . $schedule->promo_image) }}" alt="Promo" class="w-full rounded border border-gray-300">
                            @else
                                <div class="text-gray-500">—</div>
                            @endif
                        @else
                            @if($schedule->promo_image)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $schedule->promo_image) }}" alt="Promo" class="w-full rounded border border-gray-300 mb-2">
                                    <div class="flex gap-2">
                                        <a href="{{ asset('storage/' . $schedule->promo_image) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                            Zobrazit
                                        </a>
                                        <button 
                                            type="button" 
                                            onclick="if(confirm('Opravdu smazat?')) { document.getElementById('delete-promo-{{ $schedule->id }}').submit(); }"
                                            class="text-xs text-red-600 hover:text-red-800"
                                        >
                                            Smazat
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            <input 
                                type="file" 
                                name="promo_image" 
                                accept="image/*"
                                class="w-full text-xs border border-gray-300 rounded px-2 py-1.5 focus:ring-2 focus:ring-blue-500"
                            >
                        @endif
                    </div>
                </div>
            </form>
            
            @if(!$isPast && $schedule->promo_image)
                <form id="delete-promo-{{ $schedule->id }}" action="{{ route('admin.subscription-config.delete-promo-image', $schedule) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    @endforeach
</div>

@if($schedules->isEmpty())
<div class="text-center py-12 text-gray-500 bg-white rounded-xl border-2 border-gray-200">
    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <p class="text-lg font-medium">Pro tento rok zatím není vytvořen harmonogram</p>
    <p class="mt-1 text-sm">Použijte tlačítko "Vytvořit rok {{ $year }}" pro vytvoření harmonogramu</p>
</div>
@endif
