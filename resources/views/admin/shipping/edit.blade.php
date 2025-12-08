@extends('layouts.admin')

@section('title', 'Upravit dopravu - ' . $rate->country_name)

@section('content')
<div class="p-6">
    <!-- Header with Back Button -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <a href="{{ route('admin.shipping.index') }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Zpět na přehled
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Nastavení dopravy pro {{ $rate->country_name }}</h1>
        <p class="text-gray-600 mt-1">Upravte ceny dopravy a dopravce pro zásilky do této země</p>
    </div>

    <!-- Form -->
    <div class="max-w-3xl">
        <form action="{{ route('admin.shipping.update', $rate) }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-6">
            @csrf
            @method('PUT')

            <!-- Country Info (Read-only) -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <span class="text-5xl">{{ $rate->country_code === 'CZ' ? '🇨🇿' : ($rate->country_code === 'SK' ? '🇸🇰' : '🌍') }}</span>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $rate->country_name }}</h3>
                        <p class="text-sm text-gray-500">Kód země: <span class="font-mono">{{ $rate->country_code }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Enabled Toggle -->
            <div class="mb-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="enabled" value="0">
                    <input 
                        type="checkbox" 
                        name="enabled" 
                        value="1" 
                        {{ old('enabled', $rate->enabled) ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer"
                    >
                    <div>
                        <span class="text-sm font-medium text-gray-900">Povolit dopravu do této země</span>
                        <p class="text-xs text-gray-500 mt-0.5">Zákazníci mohou objednávat do této země pouze pokud je zapnuto</p>
                    </div>
                </label>
                @error('enabled')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Region Availability -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h4 class="text-lg font-bold text-gray-900 mb-2">Dostupnost podle regionu</h4>
                <p class="text-sm text-gray-600 mb-4">Nastavte, na které verzi webu bude tato doprava dostupná</p>
                
                <div class="flex flex-col md:flex-row gap-4">
                    <label class="flex items-center gap-3 cursor-pointer bg-blue-50 rounded-lg px-4 py-3 border border-blue-200">
                        <input type="hidden" name="available_on_cz" value="0">
                        <input 
                            type="checkbox" 
                            name="available_on_cz" 
                            value="1" 
                            {{ old('available_on_cz', $rate->available_on_cz) ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer"
                        >
                        <div>
                            <span class="text-sm font-medium text-gray-900">🇨🇿 kavi.cz</span>
                            <p class="text-xs text-gray-500">Česká verze webu</p>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 cursor-pointer bg-purple-50 rounded-lg px-4 py-3 border border-purple-200">
                        <input type="hidden" name="available_on_com" value="0">
                        <input 
                            type="checkbox" 
                            name="available_on_com" 
                            value="1" 
                            {{ old('available_on_com', $rate->available_on_com) ? 'checked' : '' }}
                            class="w-5 h-5 text-purple-600 rounded focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 cursor-pointer"
                        >
                        <div>
                            <span class="text-sm font-medium text-gray-900">🌍 kavibox.com</span>
                            <p class="text-xs text-gray-500">Mezinárodní EN verze</p>
                        </div>
                    </label>
                </div>
                
                @error('available_on_cz')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('available_on_com')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pricing Section -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h4 class="text-lg font-bold text-gray-900 mb-4">Ceny dopravy</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Price CZK -->
                    <div>
                        <label for="price_czk" class="block text-sm font-medium text-gray-700 mb-2">
                            Cena v CZK <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                id="price_czk" 
                                name="price_czk" 
                                value="{{ old('price_czk', $rate->price_czk) }}" 
                                step="0.01"
                                min="0"
                                required
                                class="w-full px-4 py-2.5 pr-12 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            >
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">
                                Kč
                            </div>
                        </div>
                        @error('price_czk')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price EUR -->
                    <div>
                        <label for="price_eur" class="block text-sm font-medium text-gray-700 mb-2">
                            Cena v EUR <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                id="price_eur" 
                                name="price_eur" 
                                value="{{ old('price_eur', $rate->price_eur) }}" 
                                step="0.01"
                                min="0"
                                required
                                class="w-full px-4 py-2.5 pr-12 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            >
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">
                                €
                            </div>
                        </div>
                        @error('price_eur')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Free Shipping Threshold -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <h4 class="text-lg font-bold text-gray-900 mb-2">Doprava zdarma</h4>
                <p class="text-sm text-gray-600 mb-4">Nastavte hranici pro dopravu zdarma u jednorázových objednávek (neplatí pro předplatné)</p>
                
                <div>
                    <label for="free_shipping_threshold_czk" class="block text-sm font-medium text-gray-700 mb-2">
                        Hranice v CZK (volitelné)
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            id="free_shipping_threshold_czk" 
                            name="free_shipping_threshold_czk" 
                            value="{{ old('free_shipping_threshold_czk', $rate->free_shipping_threshold_czk) }}" 
                            step="0.01"
                            min="0"
                            placeholder="Např. 1000"
                            class="w-full px-4 py-2.5 pr-12 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        >
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">
                            Kč
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Ponechte prázdné pokud nechcete nabízet dopravu zdarma</p>
                    @error('free_shipping_threshold_czk')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subscription Checkbox -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="applies_to_subscriptions" value="0">
                    <input 
                        type="checkbox" 
                        name="applies_to_subscriptions" 
                        value="1" 
                        {{ old('applies_to_subscriptions', $rate->applies_to_subscriptions) ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer"
                    >
                    <div>
                        <span class="text-sm font-medium text-gray-900">Aplikovat na předplatné</span>
                        <p class="text-xs text-gray-500 mt-0.5">Pokud zapnuto, cena dopravy se uplatní i na objednávky předplatného</p>
                    </div>
                </label>
                @error('applies_to_subscriptions')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Packeta Carriers (Multiple Selection) -->
            <div class="mb-6">
                <h4 class="text-lg font-bold text-gray-900 mb-2">Dopravci Zásilkovna</h4>
                <p class="text-sm text-gray-600 mb-4">
                    Vyberte jednoho nebo více dopravců. Zákazníci uvidí výdejní místa všech vybraných dopravců.
                    <span class="text-blue-600 font-medium">💡 Tip: Pro ČR můžete vybrat Z-BOX i Zásilkovna PP najednou!</span>
                </p>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Dostupní dopravci ({{ count($carriers) }})
                    </label>
                    
                    @php
                        $selectedCarrierIds = old('packeta_carrier_ids', $rate->packetaCarriers->pluck('id')->toArray());
                    @endphp
                    
                    @if(!empty($carriers))
                        <!-- Search/Filter -->
                        <div class="mb-3">
                            <input 
                                type="text" 
                                id="carrier-search" 
                                placeholder="🔍 Hledat dopravce..." 
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            >
                        </div>
                        
                        <!-- Carriers List with Checkboxes -->
                        <div class="border border-gray-200 rounded-xl max-h-96 overflow-y-auto bg-gray-50">
                            <div id="carriers-list" class="divide-y divide-gray-200">
                                @php
                                    $currentCountry = null;
                                @endphp
                                @foreach($carriers as $carrier)
                                    @php
                                        $countryCode = substr($carrier['name'], 0, 2);
                                        $isSelected = in_array($carrier['id'], $selectedCarrierIds);
                                    @endphp
                                    
                                    @if($countryCode !== $currentCountry)
                                        @php $currentCountry = $countryCode; @endphp
                                        <div class="bg-blue-50 px-4 py-2 font-semibold text-sm text-blue-900 sticky top-0">
                                            {{ $countryCode }} - {{ $carrier['country'] }}
                                        </div>
                                    @endif
                                    
                                    <label class="flex items-center gap-3 px-4 py-3 hover:bg-white cursor-pointer transition-colors carrier-item" data-carrier-name="{{ strtolower($carrier['name']) }}">
                                        <input 
                                            type="checkbox" 
                                            name="packeta_carrier_ids[]" 
                                            value="{{ $carrier['id'] }}"
                                            {{ $isSelected ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                                        >
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-gray-900">{{ $carrier['name'] }}</span>
                                            <span class="text-xs text-gray-500 ml-2">(Packeta ID: {{ $carrier['carrier_id'] ?? $carrier['id'] }})</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-2">
                            💡 Můžete vybrat více dopravců najednou. Widget zobrazí všechna jejich výdejní místa.
                        </p>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-yellow-800">Nejsou dostupní žádní dopravci.</p>
                                    <p class="text-xs text-yellow-700 mt-1">Zkontrolujte, zda jsou dopravci načteni v databázi.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @error('packeta_carrier_ids')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-3 pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-xl transition-all"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Uložit změny
                </button>
                <a 
                    href="{{ route('admin.shipping.index') }}" 
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-6 py-2.5 rounded-xl transition-all"
                >
                    Zrušit
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Live search for carriers
const searchInput = document.getElementById('carrier-search');
if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const carrierItems = document.querySelectorAll('.carrier-item');
        
        carrierItems.forEach(item => {
            const carrierName = item.getAttribute('data-carrier-name');
            if (carrierName.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
}
</script>
@endsection

