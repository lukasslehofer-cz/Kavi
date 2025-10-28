@extends('layouts.admin')

@section('title', 'Přidat pražírnu')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Přidat novou pražírnu</h1>
            <p class="text-gray-600 mt-1">Vytvořte novou pražírnu v systému</p>
        </div>

        <form action="{{ route('admin.roasteries.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            @csrf

            <div class="space-y-6">
                <!-- Basic Info -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Základní informace</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Název pražírny *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Země *</label>
                                <select name="country" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('country') border-red-500 @enderror" id="country-select">
                                    <option value="">Vyberte zemi</option>
                                    @foreach($countries as $country => $flag)
                                    <option value="{{ $country }}" data-flag="{{ $flag }}" {{ old('country') == $country ? 'selected' : '' }}>
                                        {{ $flag }} {{ $country }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('country')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Město</label>
                                <input type="text" name="city" value="{{ old('city') }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('city') border-red-500 @enderror">
                                @error('city')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Plná adresa</label>
                            <textarea name="address" rows="2" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                            @error('address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact & Social -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Kontakt & Sociální sítě</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Web URL</label>
                            <input type="url" name="website_url" value="{{ old('website_url') }}" placeholder="https://..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('website_url') border-red-500 @enderror">
                            @error('website_url')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Instagram handle</label>
                            <input type="text" name="instagram" value="{{ old('instagram') }}" placeholder="@prazirna" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('instagram') border-red-500 @enderror">
                            @error('instagram')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Descriptions -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Popisy</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Krátký popis (max 500 znaků)</label>
                            <textarea name="short_description" rows="3" maxlength="500" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('short_description') border-red-500 @enderror">{{ old('short_description') }}</textarea>
                            @error('short_description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Zobrazí se v přehledu pražíren</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Plný popis</label>
                            <textarea name="full_description" rows="8" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('full_description') border-red-500 @enderror">{{ old('full_description') }}</textarea>
                            @error('full_description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Zobrazí se v detailu pražírny</p>
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="border-b border-gray-200 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Obrázky</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hlavní fotka</label>
                            <input type="file" name="image" accept="image/*" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror"
                                   onchange="previewImage(event, 'main-preview')">
                            
                            <div id="main-preview-container" class="mt-4 hidden">
                                <p class="text-sm text-gray-600 mb-2">Náhled:</p>
                                <img id="main-preview" src="" alt="Náhled" 
                                     class="w-48 h-48 object-cover rounded-lg border-2 border-gray-300">
                            </div>
                            
                            @error('image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Podporované formáty: JPG, PNG, GIF. Max: 2MB</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Galerie (max 4 fotky)</label>
                            <input type="file" name="gallery[]" accept="image/*" multiple 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gallery.*') border-red-500 @enderror"
                                   onchange="previewGallery(event)">
                            
                            <div id="gallery-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                            
                            @error('gallery.*')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Maximálně 4 další fotky pro galerii</p>
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Nastavení</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rozesílka (Pražírna měsíce)</label>
                            <select name="featured_month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('featured_month') border-red-500 @enderror">
                                <option value="">Není pražírnou měsíce</option>
                                @php
                                    $currentDate = now();
                                    for ($i = -2; $i <= 12; $i++) {
                                        $date = $currentDate->copy()->addMonths($i);
                                        $value = $date->format('Y-m');
                                        $label = $date->locale('cs')->isoFormat('MMMM YYYY');
                                        $selected = old('featured_month') == $value ? 'selected' : '';
                                        echo "<option value=\"{$value}\" {$selected}>" . ucfirst($label) . "</option>";
                                    }
                                @endphp
                            </select>
                            @error('featured_month')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Vyberte měsíc, kdy bude tato pražírna pražírnou měsíce</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pořadí zobrazení</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Nižší číslo = vyšší priorita</p>
                        </div>

                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Aktivní pražírna (zobrazovat na frontendu)</span>
                        </label>
                    </div>
                </div>
            </div>

            <input type="hidden" name="country_flag" id="country-flag-input" value="{{ old('country_flag') }}">

            <div class="flex items-center gap-4 mt-8 pt-6 border-t border-gray-200">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Vytvořit pražírnu
                </button>
                <a href="{{ route('admin.roasteries.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Zrušit
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event, previewId) {
    const preview = document.getElementById(previewId);
    const previewContainer = document.getElementById(previewId + '-container');
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

function previewGallery(event) {
    const preview = document.getElementById('gallery-preview');
    const files = Array.from(event.target.files).slice(0, 4); // Max 4
    
    preview.innerHTML = '';
    
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Gallery ${index + 1}" 
                     class="w-full h-32 object-cover rounded-lg border-2 border-gray-300">
                <span class="absolute top-2 right-2 bg-gray-900 text-white text-xs px-2 py-1 rounded">${index + 1}</span>
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('country-select');
    const countryFlagInput = document.getElementById('country-flag-input');
    
    countrySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const flag = selectedOption.getAttribute('data-flag');
        countryFlagInput.value = flag || '';
    });
    
    // Set initial flag value if country is already selected
    if (countrySelect.value) {
        const selectedOption = countrySelect.options[countrySelect.selectedIndex];
        const flag = selectedOption.getAttribute('data-flag');
        countryFlagInput.value = flag || '';
    }
});
</script>
@endsection

