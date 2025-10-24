@extends('layouts.app')

@section('title', 'Přidat produkt - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Přidat nový produkt</h1>
        <p class="text-coffee-600">Vytvořte nový produkt v eshopu</p>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" class="card p-8">
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
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Cena (Kč)</label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required 
                           class="input @error('price') border-red-500 @enderror">
                    @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Skladem (ks)</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required 
                           class="input @error('stock') border-red-500 @enderror">
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
@endsection



