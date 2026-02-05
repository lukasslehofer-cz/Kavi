@extends('layouts.app')

@section('title', 'Přidat tag - Admin')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Přidat nový tag</h1>
        <p class="text-coffee-600">Vytvořte nový tag pro blogové články</p>
    </div>

    <form action="{{ route('admin.tags.store') }}" method="POST" class="card p-8">
        @csrf

        <div class="space-y-6">
            <!-- Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Název tagu 🇨🇿 *</label>
                    <input type="text" name="name_cs" id="name_cs" value="{{ old('name_cs') }}" required 
                           placeholder="např. Recepty, Tipy, Pražírny..."
                           class="input @error('name_cs') border-red-500 @enderror">
                    @error('name_cs')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Tag Name 🇬🇧</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en') }}" 
                           placeholder="e.g. Recipes, Tips, Roasteries..."
                           class="input @error('name_en') border-red-500 @enderror">
                    @error('name_en')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">URL slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}" 
                       placeholder="Ponechte prázdné pro automatické vygenerování"
                       class="input @error('slug') border-red-500 @enderror">
                @error('slug')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-coffee-600 mt-1">URL identifikátor tagu (např. "recepty")</p>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="btn btn-primary">Vytvořit tag</button>
            <a href="{{ route('admin.tags.index') }}" class="btn btn-outline">Zrušit</a>
        </div>
    </form>
</div>
@endsection
