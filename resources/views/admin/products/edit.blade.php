@extends('layouts.admin')

@section('title', 'Upravit produkt')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Upravit produkt</h1>
        <p class="text-gray-600 mt-1">Upravte informace o produktu</p>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl p-8 shadow-sm border border-gray-200">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Název produktu</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Fotka produktu</label>
                
                @if($product->image)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Aktuální fotka:</p>
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                         class="w-48 h-48 object-cover rounded-lg border-2 border-gray-300">
                </div>
                @endif
                
                <input type="file" name="image" accept="image/*" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror"
                       onchange="previewImage(event)">
                
                <div id="image-preview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Náhled nové fotky:</p>
                    <img id="preview" src="" alt="Náhled" 
                         class="w-48 h-48 object-cover rounded-lg border-2 border-primary-300">
                </div>
                
                @error('image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-600 mt-1">Podporované formáty: JPG, PNG, GIF. Maximální velikost: 2MB</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Krátký popis</label>
                <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('short_description') border-red-500 @enderror">
                @error('short_description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Detailní popis</label>
                <textarea name="description" rows="6" required 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Cena (Kč) <span class="text-sm text-gray-600">(volitelné pro kávu měsíce)</span></label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror" id="price-input">
                    @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Skladem (ks) <span class="text-sm text-gray-600">(volitelné pro kávu měsíce)</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock') border-red-500 @enderror" id="stock-input">
                    @error('stock')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Kategorie</label>
                    <div class="space-y-2">
                        @php
                            $productCategories = old('categories', is_array($product->category) ? $product->category : [$product->category]);
                        @endphp
                        @foreach($categories as $key => $label)
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="{{ $key }}" 
                                   {{ in_array($key, $productCategories) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('categories')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-600 mt-1">Můžete vybrat více kategorií (např. káva může být espresso i filtr)</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Pražírna</label>
                <select name="roastery_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('roastery_id') border-red-500 @enderror">
                    <option value="">Bez pražírny</option>
                    @foreach($roasteries as $roastery)
                    <option value="{{ $roastery->id }}" {{ old('roastery_id', $product->roastery_id) == $roastery->id ? 'selected' : '' }}>
                        {{ $roastery->country_flag }} {{ $roastery->name }} ({{ $roastery->country }})
                    </option>
                    @endforeach
                </select>
                @error('roastery_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-600 mt-1">Vyberte pražírnu, od které je káva</p>
            </div>

            <!-- Coffee Attributes Section -->
            <div class="bg-gray-50 border-2 border-gray-200 p-6 rounded-lg space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informace o kávě (volitelné)</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Původ kávy</label>
                    <input type="text" name="origin" value="{{ old('origin', $product->attributes['origin'] ?? '') }}" 
                           placeholder="např. Etiopie, Keňa, Honduras..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('origin') border-red-500 @enderror">
                    @error('origin')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-600 mt-1">Zadejte zemi nebo region původu kávy</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Nadmořská výška</label>
                    <input type="text" name="altitude" value="{{ old('altitude', $product->attributes['altitude'] ?? '') }}" 
                           placeholder="např. 1200-1800 m n.m."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('altitude') border-red-500 @enderror">
                    @error('altitude')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-600 mt-1">Nadmořská výška pěstování v metrech</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Zpracování</label>
                    <input type="text" name="processing" value="{{ old('processing', $product->attributes['processing'] ?? '') }}" 
                           placeholder="např. Washed, Natural, Honey..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('processing') border-red-500 @enderror">
                    @error('processing')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-600 mt-1">Způsob zpracování kávy</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Odrůda</label>
                    <input type="text" name="variety" value="{{ old('variety', $product->attributes['variety'] ?? '') }}" 
                           placeholder="např. Arabica, Bourbon, Caturra..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('variety') border-red-500 @enderror">
                    @error('variety')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-600 mt-1">Odrůda kávovníku</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Chuťové tóny</label>
                    <textarea name="flavor_notes" rows="3" 
                              placeholder="např. citrus, čokoláda, karamel, oříšky..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('flavor_notes') border-red-500 @enderror">{{ old('flavor_notes', $product->attributes['flavor_notes'] ?? '') }}</textarea>
                    @error('flavor_notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-600 mt-1">Popište chuťový profil kávy</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-300">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Hmotnost (g)</label>
                        <input type="number" name="weight" value="{{ old('weight', $product->attributes['weight'] ?? '') }}" min="1" 
                               placeholder="250"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('weight') border-red-500 @enderror">
                        @error('weight')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-600 mt-1">Hmotnost balení v gramech</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Datum pražení</label>
                        <input type="date" name="roast_date" value="{{ old('roast_date', $product->attributes['roast_date'] ?? '') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('roast_date') border-red-500 @enderror">
                        @error('roast_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-600 mt-1">Kdy byla káva upražena</p>
                    </div>
                </div>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Pořadí řazení</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}" min="0" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-500 @enderror">
                @error('sort_order')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-600 mt-1">Čím nižší číslo, tím výše se produkt zobrazí (0 = výchozí)</p>
            </div>

            <div id="preparation-methods-container" style="display: none;">
                <label class="block text-sm font-medium text-gray-900 mb-2">Typ pražení (pro kávu)</label>
                <div class="space-y-2">
                    @php
                        $currentMethods = old('preparation_methods', $product->attributes['preparation_methods'] ?? []);
                    @endphp
                    <label class="flex items-center">
                        <input type="checkbox" name="preparation_methods[]" value="espresso" 
                               {{ in_array('espresso', $currentMethods) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Espresso</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="preparation_methods[]" value="filter" 
                               {{ in_array('filter', $currentMethods) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Filtr</span>
                    </label>
                </div>
                @error('preparation_methods')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-600 mt-1">Můžete vybrat obě možnosti, pokud je káva vhodná pro espresso i filtr</p>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 p-6 rounded-lg">
                <div class="flex items-start gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_coffee_of_month" value="1" {{ old('is_coffee_of_month', $product->is_coffee_of_month) ? 'checked' : '' }}
                                   class="rounded border-blue-300 text-blue-600 focus:ring-blue-500" id="coffee-of-month-checkbox">
                            <span class="ml-2 text-sm font-bold text-gray-900">Označit jako kávu měsíce</span>
                        </label>
                        <p class="text-xs text-gray-600 mt-1 ml-6">Kávy měsíce se nezobrazují v eshopu, ale na stránce předplatného</p>
                    </div>
                </div>

                <div id="coffee-of-month-date-container" style="display: none;">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Rozesílka (Měsíc kávy)</label>
                    <select name="coffee_of_month_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('coffee_of_month_date') border-red-500 @enderror">
                        <option value="">Vyberte měsíc rozesílky</option>
                        @php
                            $currentDate = now();
                            // Get current value - handle both date object and string format
                            $currentValue = old('coffee_of_month_date', $product->coffee_of_month_date);
                            if ($currentValue instanceof \Carbon\Carbon) {
                                $currentValue = $currentValue->format('Y-m');
                            }
                            
                            for ($i = -2; $i <= 12; $i++) {
                                $date = $currentDate->copy()->addMonths($i);
                                $value = $date->format('Y-m');
                                $label = $date->locale('cs')->isoFormat('MMMM YYYY');
                                $selected = $currentValue == $value ? 'selected' : '';
                                echo "<option value=\"{$value}\" {$selected}>" . ucfirst($label) . "</option>";
                            }
                        @endphp
                    </select>
                    @error('coffee_of_month_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-600 mt-1">Vyberte měsíc, kdy bude káva součástí rozesílky (zobrazuje se do 15. dne aktuálního měsíce, pak se přepne na následující měsíc)</p>
                </div>
            </div>

            <div class="flex items-center space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-gray-700 focus:ring-coffee-500">
                    <span class="ml-2 text-sm text-gray-900">Aktivní produkt</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-gray-700 focus:ring-coffee-500">
                    <span class="ml-2 text-sm text-gray-900">Zvýrazněný produkt</span>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">Uložit změny</button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Zrušit</a>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('image-preview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const coffeeOfMonthCheckbox = document.getElementById('coffee-of-month-checkbox');
    const coffeeOfMonthDateContainer = document.getElementById('coffee-of-month-date-container');
    const priceInput = document.getElementById('price-input');
    const stockInput = document.getElementById('stock-input');

    function toggleCoffeeOfMonth() {
        if (coffeeOfMonthCheckbox.checked) {
            coffeeOfMonthDateContainer.style.display = 'block';
            priceInput.removeAttribute('required');
            stockInput.removeAttribute('required');
        } else {
            coffeeOfMonthDateContainer.style.display = 'none';
            priceInput.setAttribute('required', 'required');
            stockInput.setAttribute('required', 'required');
        }
    }

    coffeeOfMonthCheckbox.addEventListener('change', toggleCoffeeOfMonth);
    toggleCoffeeOfMonth(); // Initial state
});
</script>
@endsection

