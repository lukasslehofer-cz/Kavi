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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Název produktu 🇨🇿</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                           class="input @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2 flex items-center justify-between">
                        <span>Product Name 🇬🇧</span>
                        <button type="button" onclick="translateField('name', 'name_en')" class="translate-btn text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                            Přeložit
                        </button>
                    </label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en') }}" 
                           placeholder="English product name"
                           class="input @error('name_en') border-red-500 @enderror">
                    @error('name_en')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Galerie produktu (max 4 fotky)</label>

                <input type="file" name="gallery[]" accept="image/*" multiple
                       class="input @error('gallery.*') border-red-500 @enderror"
                       onchange="previewGallery(event)">

                <div id="gallery-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>

                @error('gallery.*')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-coffee-600 mt-1">
                    <strong>První fotka bude hlavní.</strong> Podporované formáty: JPG, PNG, GIF, WebP. Max: 2MB na fotku, max 4 fotky celkem.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Obrázek pro Facebook katalog</label>
                <input type="file" name="facebook_image" accept="image/png,image/jpeg"
                       class="input @error('facebook_image') border-red-500 @enderror">
                @error('facebook_image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-coffee-600 mt-1">
                    Volitelný obrázek ve formátu PNG/JPG pro Facebook feed. Min. 500x500 px. Pokud není nahrán, použije se hlavní fotka z galerie.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Krátký popis 🇨🇿</label>
                    <input type="text" name="short_description" id="short_description" value="{{ old('short_description') }}" 
                           class="input @error('short_description') border-red-500 @enderror">
                    @error('short_description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2 flex items-center justify-between">
                        <span>Short Description 🇬🇧</span>
                        <button type="button" onclick="translateField('short_description', 'short_description_en')" class="translate-btn text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                            Přeložit
                        </button>
                    </label>
                    <input type="text" name="short_description_en" id="short_description_en" value="{{ old('short_description_en') }}" 
                           placeholder="English short description"
                           class="input @error('short_description_en') border-red-500 @enderror">
                    @error('short_description_en')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Detailní popis 🇨🇿</label>
                    <textarea name="description" id="description" rows="6" required 
                              class="input @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-coffee-600 mt-1">Povolené HTML tagy: &lt;a&gt;, &lt;strong&gt;, &lt;b&gt;, &lt;em&gt;, &lt;i&gt;, &lt;br&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2 flex items-center justify-between">
                        <span>Detailed Description 🇬🇧</span>
                        <button type="button" onclick="translateField('description', 'description_en')" class="translate-btn text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                            Přeložit
                        </button>
                    </label>
                    <textarea name="description_en" id="description_en" rows="6" 
                              placeholder="English detailed description"
                              class="input @error('description_en') border-red-500 @enderror">{{ old('description_en') }}</textarea>
                    @error('description_en')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Cena (Kč) <span class="text-sm text-coffee-600">(volitelné pro kávu měsíce)</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" 
                           class="input @error('price') border-red-500 @enderror" id="price-input">
                    @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Cena (EUR) <span class="text-sm text-coffee-600">(pro kavibox.com)</span></label>
                    <input type="number" name="price_eur" value="{{ old('price_eur') }}" step="0.01" min="0"
                           class="input @error('price_eur') border-red-500 @enderror">
                    @error('price_eur')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Sazba DPH (%) <span class="text-sm text-coffee-600">(12% káva, 21% accessories)</span></label>
                    <input type="number" name="vat_rate" value="{{ old('vat_rate', 21) }}" step="0.01" min="0" max="100"
                           class="input @error('vat_rate') border-red-500 @enderror">
                    @error('vat_rate')
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
                    <div class="space-y-2">
                        @foreach($categories as $key => $label)
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="{{ $key }}" 
                                   {{ in_array($key, old('categories', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('categories')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-coffee-600 mt-1">Můžete vybrat více kategorií (např. káva může být espresso i filtr)</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Pražírna</label>
                <select name="roastery_id" class="input @error('roastery_id') border-red-500 @enderror">
                    <option value="">Bez pražírny</option>
                    @foreach($roasteries as $roastery)
                    <option value="{{ $roastery->id }}" {{ old('roastery_id') == $roastery->id ? 'selected' : '' }}>
                        {{ $roastery->country_flag }} {{ $roastery->name }} ({{ $roastery->country }})
                    </option>
                    @endforeach
                </select>
                @error('roastery_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-coffee-600 mt-1">Vyberte pražírnu, od které je káva</p>
            </div>

            <!-- Coffee Attributes Section -->
            <div id="coffee-attributes-section" class="bg-cream-50 border-2 border-cream-200 p-6 rounded-lg space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-coffee-900">Informace o kávě</h3>
                    <span class="text-xs text-coffee-600 italic">Zobrazeno pro kategorii: Espresso, Filtr, Bezkofeinová</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-coffee-900 mb-2">Původ kávy 🇨🇿</label>
                        <input type="text" name="origin" id="origin" value="{{ old('origin') }}" 
                               placeholder="např. Etiopie, Keňa, Honduras..."
                               class="input @error('origin') border-red-500 @enderror">
                        @error('origin')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-coffee-900 mb-2 flex items-center justify-between">
                            <span>Origin 🇬🇧</span>
                            <button type="button" onclick="translateField('origin', 'origin_en')" class="translate-btn text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                Přeložit
                            </button>
                        </label>
                        <input type="text" name="origin_en" id="origin_en" value="{{ old('origin_en') }}" 
                               placeholder="e.g. Ethiopia, Kenya, Honduras..."
                               class="input @error('origin_en') border-red-500 @enderror">
                        @error('origin_en')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Nadmořská výška / Altitude</label>
                    <input type="text" name="altitude" value="{{ old('altitude') }}"
                           placeholder="např. 1200-1800"
                           class="input @error('altitude') border-red-500 @enderror">
                    @error('altitude')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-coffee-600 mt-1">Pouze číslo nebo rozsah v metrech, jednotka se na webu doplní automaticky (CZ: m n.m., EN: masl).</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-coffee-900 mb-2">Zpracování 🇨🇿</label>
                        <input type="text" name="processing" id="processing" value="{{ old('processing') }}" 
                               placeholder="např. Praná, Přírodní, Honey..."
                               class="input @error('processing') border-red-500 @enderror">
                        @error('processing')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-coffee-900 mb-2 flex items-center justify-between">
                            <span>Processing 🇬🇧</span>
                            <button type="button" onclick="translateField('processing', 'processing_en')" class="translate-btn text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                Přeložit
                            </button>
                        </label>
                        <input type="text" name="processing_en" id="processing_en" value="{{ old('processing_en') }}" 
                               placeholder="e.g. Washed, Natural, Honey..."
                               class="input @error('processing_en') border-red-500 @enderror">
                        @error('processing_en')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-coffee-900 mb-2">Odrůda 🇨🇿</label>
                        <input type="text" name="variety" id="variety" value="{{ old('variety') }}" 
                               placeholder="např. Arabica, Bourbon, Caturra..."
                               class="input @error('variety') border-red-500 @enderror">
                        @error('variety')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-coffee-900 mb-2 flex items-center justify-between">
                            <span>Variety 🇬🇧</span>
                            <button type="button" onclick="translateField('variety', 'variety_en')" class="translate-btn text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                Přeložit
                            </button>
                        </label>
                        <input type="text" name="variety_en" id="variety_en" value="{{ old('variety_en') }}" 
                               placeholder="e.g. Arabica, Bourbon, Caturra..."
                               class="input @error('variety_en') border-red-500 @enderror">
                        @error('variety_en')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-coffee-900 mb-2">Chuťové tóny 🇨🇿</label>
                        <textarea name="flavor_notes" id="flavor_notes" rows="3" 
                                  placeholder="např. citrus, čokoláda, karamel, oříšky..."
                                  class="input @error('flavor_notes') border-red-500 @enderror">{{ old('flavor_notes') }}</textarea>
                        @error('flavor_notes')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-coffee-900 mb-2 flex items-center justify-between">
                            <span>Flavor Notes 🇬🇧</span>
                            <button type="button" onclick="translateField('flavor_notes', 'flavor_notes_en')" class="translate-btn text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                Přeložit
                            </button>
                        </label>
                        <textarea name="flavor_notes_en" id="flavor_notes_en" rows="3" 
                                  placeholder="e.g. citrus, chocolate, caramel, nuts..."
                                  class="input @error('flavor_notes_en') border-red-500 @enderror">{{ old('flavor_notes_en') }}</textarea>
                        @error('flavor_notes_en')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-cream-300">
                    <div>
                        <label class="block text-sm font-medium text-coffee-900 mb-2">Hmotnost (g)</label>
                        <input type="number" name="weight" value="{{ old('weight') }}" min="1" 
                               placeholder="250"
                               class="input @error('weight') border-red-500 @enderror">
                        @error('weight')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-coffee-600 mt-1">Hmotnost balení v gramech</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-coffee-900 mb-2">Datum pražení</label>
                        <input type="date" name="roast_date" value="{{ old('roast_date') }}" 
                               class="input @error('roast_date') border-red-500 @enderror">
                        @error('roast_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-coffee-600 mt-1">Kdy byla káva upražena</p>
                    </div>
                </div>
            </div>

            <!-- Custom Attributes Section (for accessories) -->
            <div id="custom-attributes-section" class="bg-blue-50 border-2 border-blue-200 p-6 rounded-lg space-y-4" style="display: none;">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-coffee-900">Vlastní parametry produktu</h3>
                        <p class="text-xs text-coffee-600 mt-1">Pro příslušenství můžete definovat libovolné parametry</p>
                    </div>
                    <span class="text-xs text-coffee-600 italic">Zobrazeno pro kategorii: Příslušenství</span>
                </div>

                <div id="custom-attributes-container"></div>

                <button type="button" onclick="addCustomAttribute()" class="btn btn-outline btn-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Přidat parametr
                </button>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Pořadí řazení</label>
                <input type="number" name="sort_order" value="{{ old('sort_order') }}" min="0" placeholder="Automaticky na konec"
                       class="input @error('sort_order') border-red-500 @enderror">
                @error('sort_order')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-coffee-600 mt-1">Prázdné = produkt se zařadí na konec. Jinak čím nižší číslo, tím výše se produkt zobrazí. Pořadí lze pohodlněji měnit přetažením ve výpisu produktů.</p>
            </div>

            <!-- Discount Section -->
            <div class="bg-gradient-to-br from-red-50 to-orange-50 border-2 border-red-200 p-6 rounded-lg space-y-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Sleva na produktu</h3>
                        <p class="text-xs text-gray-600">Nastavte slevu přímo na tomto produktu</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Typ slevy</label>
                    <select name="discount_type" id="discount-type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">Bez slevy</option>
                        <option value="percent" {{ old('discount_type') === 'percent' ? 'selected' : '' }}>Procentuální sleva</option>
                        <option value="amount" {{ old('discount_type') === 'amount' ? 'selected' : '' }}>Sleva částkou</option>
                    </select>
                </div>

                <div id="discount-percent-container" style="display: none;">
                    <label class="block text-sm font-medium text-gray-900 mb-2">Sleva v procentech (%)</label>
                    <input type="number" name="discount_percent" value="{{ old('discount_percent') }}" step="0.01" min="0" max="100" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('discount_percent') border-red-500 @enderror">
                    @error('discount_percent')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-600 mt-1">Např. 20 pro 20% slevu</p>
                </div>

                <div id="discount-amount-container" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Sleva v Kč</label>
                            <input type="number" name="discount_amount_czk" value="{{ old('discount_amount_czk') }}" step="0.01" min="0" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('discount_amount_czk') border-red-500 @enderror">
                            @error('discount_amount_czk')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Sleva v EUR</label>
                            <input type="number" name="discount_amount_eur" value="{{ old('discount_amount_eur') }}" step="0.01" min="0" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('discount_amount_eur') border-red-500 @enderror">
                            @error('discount_amount_eur')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1">Zadejte částku, která se odečte od ceny produktu</p>
                </div>

                <div id="discount-dates-container" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Začátek slevy (volitelné)</label>
                            <input type="datetime-local" name="sale_start_date" value="{{ old('sale_start_date') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('sale_start_date') border-red-500 @enderror">
                            @error('sale_start_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">Konec slevy (volitelné)</label>
                            <input type="datetime-local" name="sale_end_date" value="{{ old('sale_end_date') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('sale_end_date') border-red-500 @enderror">
                            @error('sale_end_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1">Nechte prázdné pro trvalou slevu bez časového omezení</p>
                </div>

                <div id="discount-show-percentage-container" style="display: none;">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="show_discount_percentage" value="1" {{ old('show_discount_percentage', true) ? 'checked' : '' }}
                               class="rounded border-red-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-900">Zobrazit procentuální slevu u produktu</span>
                    </label>
                    <p class="text-xs text-gray-600 mt-1 ml-6">Pokud je zaškrtnuto, zobrazí se badge s % slevy vedle ceny</p>
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
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Rozesílka (Měsíc kávy)</label>
                    <select name="coffee_of_month_date" class="input @error('coffee_of_month_date') border-red-500 @enderror">
                        <option value="">Vyberte měsíc rozesílky</option>
                        @php
                            $currentDate = now();
                            $czechMonths = ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 
                                            'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'];
                            for ($i = -2; $i <= 12; $i++) {
                                $date = $currentDate->copy()->startOfMonth()->addMonths($i);
                                $value = $date->format('Y-m');
                                $label = $czechMonths[$date->month - 1] . ' ' . $date->year;
                                $selected = old('coffee_of_month_date') == $value ? 'selected' : '';
                                echo "<option value=\"{$value}\" {$selected}>{$label}</option>";
                            }
                        @endphp
                    </select>
                    @error('coffee_of_month_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-coffee-600 mt-1">Vyberte měsíc, kdy bude káva součástí rozesílky (zobrazuje se do 15. dne aktuálního měsíce, pak se přepne na následující měsíc)</p>
                </div>
            </div>

            <div class="flex items-center space-x-6 flex-wrap gap-y-3">
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

                <label class="flex items-center">
                    <input type="checkbox" name="free_shipping" value="1" {{ old('free_shipping') ? 'checked' : '' }}
                           class="rounded border-green-300 text-green-600 focus:ring-green-500">
                    <span class="ml-2 text-sm text-coffee-900">Doprava zdarma</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_digital" value="1" {{ old('is_digital') ? 'checked' : '' }}
                           class="rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-coffee-900">Digitální produkt</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="exclude_from_discounts" value="1" {{ old('exclude_from_discounts') ? 'checked' : '' }}
                           class="rounded border-orange-300 text-orange-600 focus:ring-orange-500">
                    <span class="ml-2 text-sm text-coffee-900">Vyloučit ze slev</span>
                </label>
            </div>
            <p class="text-xs text-coffee-600 mt-2">
                💡 <strong>Doprava zdarma</strong> - pokud budou v košíku pouze produkty s touto značkou, doprava se nebude účtovat.<br>
                📧 <strong>Digitální produkt</strong> - pokud budou v košíku pouze digitální produkty, nebude se zobrazovat výběr výdejního místa.<br>
                🏷️ <strong>Vyloučit ze slev</strong> - na tento produkt se nebudou aplikovat slevové kódy.
            </p>
        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="btn btn-primary">Vytvořit produkt</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Zrušit</a>
        </div>
    </form>
</div>

<script>
function previewGallery(event) {
    const preview = document.getElementById('gallery-preview');
    const files = Array.from(event.target.files).slice(0, 4); // Max 4

    preview.innerHTML = '';

    if (files.length === 0) return;

    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Gallery ${index + 1}"
                     class="w-full h-32 object-cover rounded-lg border-2 border-blue-500">
                <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                    ${index === 0 ? 'HLAVNÍ' : index + 1}
                </span>
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    });

    // Show capacity indicator
    if (files.length > 0) {
        const capacityDiv = document.createElement('div');
        capacityDiv.className = 'col-span-full text-xs text-coffee-600 text-center pt-2';
        capacityDiv.textContent = `${files.length}/4 fotky nahráno`;
        preview.appendChild(capacityDiv);
    }
}

// AI Translation function
async function translateField(sourceId, targetId) {
    const sourceElement = document.getElementById(sourceId);
    const targetElement = document.getElementById(targetId);
    const button = event.target.closest('button');
    
    if (!sourceElement || !targetElement) {
        alert('Chyba: Nelze najít zdrojové nebo cílové pole.');
        return;
    }
    
    const sourceText = sourceElement.value.trim();
    
    if (!sourceText) {
        alert('Zdrojové pole je prázdné. Nejprve vyplňte český text.');
        return;
    }
    
    // Show loading state
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `
        <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Překládám...
    `;
    
    try {
        const response = await fetch('{{ route("admin.translate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                text: sourceText,
                source_lang: 'CS',
                target_lang: 'EN'
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            targetElement.value = data.translation;
            // Flash success
            targetElement.classList.add('ring-2', 'ring-green-500');
            setTimeout(() => {
                targetElement.classList.remove('ring-2', 'ring-green-500');
            }, 1500);
        } else {
            alert('Chyba překladu: ' + (data.error || 'Neznámá chyba'));
        }
    } catch (error) {
        console.error('Translation error:', error);
        alert('Chyba při komunikaci s překladovou službou.');
    } finally {
        // Restore button
        button.disabled = false;
        button.innerHTML = originalContent;
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

    // Discount type toggle
    const discountTypeSelect = document.getElementById('discount-type');
    const discountPercentContainer = document.getElementById('discount-percent-container');
    const discountAmountContainer = document.getElementById('discount-amount-container');
    const discountDatesContainer = document.getElementById('discount-dates-container');
    const discountShowPercentageContainer = document.getElementById('discount-show-percentage-container');

    function toggleDiscountFields() {
        const discountType = discountTypeSelect.value;
        
        if (discountType === '') {
            discountPercentContainer.style.display = 'none';
            discountAmountContainer.style.display = 'none';
            discountDatesContainer.style.display = 'none';
            discountShowPercentageContainer.style.display = 'none';
        } else if (discountType === 'percent') {
            discountPercentContainer.style.display = 'block';
            discountAmountContainer.style.display = 'none';
            discountDatesContainer.style.display = 'block';
            discountShowPercentageContainer.style.display = 'block';
        } else if (discountType === 'amount') {
            discountPercentContainer.style.display = 'none';
            discountAmountContainer.style.display = 'block';
            discountDatesContainer.style.display = 'block';
            discountShowPercentageContainer.style.display = 'block';
        }
    }

    discountTypeSelect.addEventListener('change', toggleDiscountFields);
    toggleDiscountFields(); // Initial state

    // Category-based section visibility
    const categoryCheckboxes = document.querySelectorAll('input[name="categories[]"]');
    const coffeeSection = document.getElementById('coffee-attributes-section');
    const customSection = document.getElementById('custom-attributes-section');

    function updateAttributeSections() {
        const selectedCategories = Array.from(categoryCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        const isCoffee = selectedCategories.some(cat => ['espresso', 'filter', 'decaf'].includes(cat));
        const isAccessory = selectedCategories.includes('accessories');

        coffeeSection.style.display = isCoffee ? 'block' : 'none';
        customSection.style.display = isAccessory ? 'block' : 'none';
    }

    categoryCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateAttributeSections);
    });

    updateAttributeSections();
});

// Custom attributes management
let customAttrIndex = 0;

function addCustomAttribute(labelCs = '', labelEn = '', valueCs = '', valueEn = '') {
    const container = document.getElementById('custom-attributes-container');
    const index = customAttrIndex++;

    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 p-4 bg-white rounded-lg border border-blue-200 mb-3';
    row.id = `custom-attr-${index}`;

    row.innerHTML = `
        <div class="col-span-3">
            <label class="block text-xs font-medium text-coffee-900 mb-1">Název 🇨🇿</label>
            <input type="text"
                   name="custom_attributes[${index}][label_cs]"
                   value="${labelCs}"
                   placeholder="Materiál"
                   class="input text-sm"
                   required>
        </div>
        <div class="col-span-2">
            <label class="block text-xs font-medium text-coffee-900 mb-1 flex items-center justify-between">
                <span>Name 🇬🇧</span>
                <button type="button"
                        onclick="translateCustomLabel(${index})"
                        class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-1 py-0.5 rounded">
                    →
                </button>
            </label>
            <input type="text"
                   name="custom_attributes[${index}][label_en]"
                   value="${labelEn}"
                   placeholder="Material"
                   class="input text-sm"
                   id="custom-attr-label-en-${index}">
        </div>
        <div class="col-span-3">
            <label class="block text-xs font-medium text-coffee-900 mb-1">Hodnota 🇨🇿</label>
            <input type="text"
                   name="custom_attributes[${index}][value_cs]"
                   value="${valueCs}"
                   placeholder="Nerezová ocel"
                   class="input text-sm"
                   required
                   id="custom-attr-value-cs-${index}">
        </div>
        <div class="col-span-3">
            <label class="block text-xs font-medium text-coffee-900 mb-1 flex items-center justify-between">
                <span>Value 🇬🇧</span>
                <button type="button"
                        onclick="translateCustomValue(${index})"
                        class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-1 py-0.5 rounded">
                    →
                </button>
            </label>
            <input type="text"
                   name="custom_attributes[${index}][value_en]"
                   value="${valueEn}"
                   placeholder="Stainless steel"
                   class="input text-sm"
                   id="custom-attr-value-en-${index}">
        </div>
        <div class="col-span-1 flex items-end">
            <button type="button"
                    onclick="removeCustomAttribute(${index})"
                    class="btn btn-sm bg-red-100 hover:bg-red-200 text-red-700 w-full">×</button>
        </div>
    `;

    container.appendChild(row);
}

function removeCustomAttribute(index) {
    const row = document.getElementById(`custom-attr-${index}`);
    if (row) row.remove();
}

async function translateCustomLabel(index) {
    const csInput = document.querySelector(`input[name="custom_attributes[${index}][label_cs]"]`);
    const enInput = document.getElementById(`custom-attr-label-en-${index}`);

    if (!csInput || !enInput) return;

    const sourceText = csInput.value.trim();
    if (!sourceText) {
        alert('Nejprve vyplňte český název.');
        return;
    }

    try {
        const response = await fetch('{{ route("admin.translate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                text: sourceText,
                source_lang: 'CS',
                target_lang: 'EN'
            })
        });

        const data = await response.json();
        if (data.success) {
            enInput.value = data.translation;
            enInput.classList.add('ring-2', 'ring-green-500');
            setTimeout(() => enInput.classList.remove('ring-2', 'ring-green-500'), 1500);
        }
    } catch (error) {
        console.error('Translation error:', error);
    }
}

async function translateCustomValue(index) {
    const csInput = document.getElementById(`custom-attr-value-cs-${index}`);
    const enInput = document.getElementById(`custom-attr-value-en-${index}`);

    if (!csInput || !enInput) return;

    const sourceText = csInput.value.trim();
    if (!sourceText) {
        alert('Nejprve vyplňte českou hodnotu.');
        return;
    }

    try {
        const response = await fetch('{{ route("admin.translate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                text: sourceText,
                source_lang: 'CS',
                target_lang: 'EN'
            })
        });

        const data = await response.json();
        if (data.success) {
            enInput.value = data.translation;
            enInput.classList.add('ring-2', 'ring-green-500');
            setTimeout(() => enInput.classList.remove('ring-2', 'ring-green-500'), 1500);
        }
    } catch (error) {
        console.error('Translation error:', error);
    }
}
</script>
@endsection




