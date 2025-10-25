@extends('layouts.app')

@section('title', 'Přidat produkt - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Přidat nový produkt</h1>
        <p class="text-coffee-600">Vytvořte nový produkt v eshopu</p>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="card p-8">
        @csrf

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Název produktu</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       class="input @error('name') border-red-500 @enderror">
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Fotka produktu</label>
                <input type="file" name="image" accept="image/*" 
                       class="input @error('image') border-red-500 @enderror"
                       onchange="previewImage(event)">
                
                <div id="image-preview" class="mt-4 hidden">
                    <p class="text-sm text-coffee-600 mb-2">Náhled:</p>
                    <img id="preview" src="" alt="Náhled" 
                         class="w-48 h-48 object-cover rounded-lg border-2 border-primary-300">
                </div>
                
                @error('image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-coffee-600 mt-1">Podporované formáty: JPG, PNG, GIF. Maximální velikost: 2MB</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Krátký popis</label>
                <input type="text" name="short_description" value="{{ old('short_description') }}" 
                       class="input @error('short_description') border-red-500 @enderror">
                @error('short_description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Detailní popis</label>
                <textarea name="description" rows="6" required 
                          class="input @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Cena (Kč) <span class="text-sm text-coffee-600">(volitelné pro kávu měsíce)</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" 
                           class="input @error('price') border-red-500 @enderror" id="price-input">
                    @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Skladem (ks) <span class="text-sm text-coffee-600">(volitelné pro kávu měsíce)</span></label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" 
                           class="input @error('stock') border-red-500 @enderror" id="stock-input">
                    @error('stock')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Kategorie</label>
                    <select name="category" required class="input @error('category') border-red-500 @enderror">
                        <option value="">Vyberte kategorii</option>
                        @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-primary-50 border-2 border-primary-200 p-6 rounded-lg">
                <div class="flex items-start gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_coffee_of_month" value="1" {{ old('is_coffee_of_month') ? 'checked' : '' }}
                                   class="rounded border-primary-300 text-primary-600 focus:ring-primary-500" id="coffee-of-month-checkbox">
                            <span class="ml-2 text-sm font-bold text-dark-800">Označit jako kávu měsíce</span>
                        </label>
                        <p class="text-xs text-dark-600 mt-1 ml-6">Kávy měsíce se nezobrazují v eshopu, ale na stránce předplatného</p>
                    </div>
                </div>

                <div id="coffee-of-month-date-container" style="display: none;">
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Datum rozesílky</label>
                    <input type="date" name="coffee_of_month_date" value="{{ old('coffee_of_month_date') }}" 
                           class="input @error('coffee_of_month_date') border-red-500 @enderror">
                    @error('coffee_of_month_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-coffee-600 mt-1">Pro kterou rozesílku je káva určena (20. dne v měsíci)</p>
                </div>
            </div>

            <div class="flex items-center space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="rounded border-cream-300 text-coffee-700 focus:ring-coffee-500">
                    <span class="ml-2 text-sm text-coffee-900">Aktivní produkt</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                           class="rounded border-cream-300 text-coffee-700 focus:ring-coffee-500">
                    <span class="ml-2 text-sm text-coffee-900">Zvýrazněný produkt</span>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="btn btn-primary">Vytvořit produkt</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Zrušit</a>
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




