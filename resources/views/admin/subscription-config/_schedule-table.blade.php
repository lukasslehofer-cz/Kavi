<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b-2 border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Měsíc</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Datum platby</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Datum rozesílky</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Káva měsíce</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Pražírna</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Poznámka</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($schedules as $schedule)
            @php
                $isPast = $schedule->isPast();
                $rowClass = $isPast ? 'bg-gray-50' : 'bg-white hover:bg-gray-50';
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="px-4 py-3 font-medium text-gray-900">
                    {{ $schedule->month_name }}
                    @if($isPast)
                    <span class="ml-2 text-xs text-gray-500">(minulost)</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($isPast)
                        <span class="text-gray-600">{{ $schedule->billing_date->format('d.m.Y') }}</span>
                    @else
                        <input 
                            type="date" 
                            name="schedules[{{ $loop->index }}][billing_date]" 
                            value="{{ $schedule->billing_date->format('Y-m-d') }}"
                            class="px-3 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            required
                        >
                        <input type="hidden" name="schedules[{{ $loop->index }}][id]" value="{{ $schedule->id }}">
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($isPast)
                        <span class="text-gray-600">{{ $schedule->shipment_date->format('d.m.Y') }}</span>
                    @else
                        <input 
                            type="date" 
                            name="schedules[{{ $loop->index }}][shipment_date]" 
                            value="{{ $schedule->shipment_date->format('Y-m-d') }}"
                            class="px-3 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            required
                        >
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($isPast)
                        <span class="text-gray-600">
                            {{ $schedule->coffeeProduct->name ?? '—' }}
                        </span>
                    @else
                        <select 
                            name="schedules[{{ $loop->index }}][coffee_product_id]" 
                            class="px-3 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm w-full"
                        >
                            <option value="">— Vyberte kávu —</option>
                            @foreach($coffeeProducts as $product)
                            <option 
                                value="{{ $product->id }}"
                                {{ $schedule->coffee_product_id == $product->id ? 'selected' : '' }}
                            >
                                {{ $product->name }}
                            </option>
                            @endforeach
                        </select>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($isPast)
                        <span class="text-gray-600">{{ $schedule->roastery_name ?? '—' }}</span>
                    @else
                        <input 
                            type="text" 
                            name="schedules[{{ $loop->index }}][roastery_name]" 
                            value="{{ $schedule->roastery_name }}"
                            placeholder="např. Nordbeans"
                            class="px-3 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm w-full"
                        >
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($isPast)
                        <span class="text-gray-600">{{ $schedule->notes ?? '—' }}</span>
                    @else
                        <input 
                            type="text" 
                            name="schedules[{{ $loop->index }}][notes]" 
                            value="{{ $schedule->notes }}"
                            placeholder="Poznámka"
                            class="px-3 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm w-full"
                        >
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($schedules->isEmpty())
<div class="text-center py-12 text-gray-500">
    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <p class="text-lg font-medium">Pro tento rok zatím není vytvořen harmonogram</p>
    <p class="mt-1 text-sm">Použijte tlačítko "Vytvořit rok {{ $year }}" pro vytvoření harmonogramu</p>
</div>
@endif

