@extends('layouts.app')

@section('title', 'Přidat článek - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Přidat nový článek</h1>
        <p class="text-coffee-600">Vytvořte nový blogový článek</p>
    </div>

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="card p-8">
        @csrf

        <div class="space-y-6">
            <!-- Title -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Název článku 🇨🇿 *</label>
                    <input type="text" name="title_cs" id="title_cs" value="{{ old('title_cs') }}" required 
                           class="input @error('title_cs') border-red-500 @enderror">
                    @error('title_cs')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Article Title 🇬🇧</label>
                    <input type="text" name="title_en" id="title_en" value="{{ old('title_en') }}" 
                           placeholder="English article title"
                           class="input @error('title_en') border-red-500 @enderror">
                    @error('title_en')
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
                <p class="text-xs text-coffee-600 mt-1">URL adresa článku (např. "jak-spravne-varit-kavu")</p>
            </div>

            <!-- Featured Image -->
            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Úvodní obrázek</label>
                <input type="file" name="featured_image" accept="image/*" 
                       class="input @error('featured_image') border-red-500 @enderror"
                       onchange="previewImage(event)">
                
                <div id="image-preview" class="mt-4 hidden">
                    <p class="text-sm text-coffee-600 mb-2">Náhled:</p>
                    <img id="preview" src="" alt="Náhled" 
                         class="w-full max-w-md h-48 object-cover rounded-lg border-2 border-primary-300">
                </div>
                
                @error('featured_image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-coffee-600 mt-1">Podporované formáty: JPG, PNG, GIF, WebP. Maximální velikost: 2MB. Doporučený poměr stran 16:9.</p>
            </div>

            <!-- Perex -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Perex 🇨🇿</label>
                    <textarea name="perex_cs" id="perex_cs" rows="3" 
                              placeholder="Krátký úvod k článku..."
                              class="input @error('perex_cs') border-red-500 @enderror">{{ old('perex_cs') }}</textarea>
                    @error('perex_cs')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Perex 🇬🇧</label>
                    <textarea name="perex_en" id="perex_en" rows="3" 
                              placeholder="Short introduction..."
                              class="input @error('perex_en') border-red-500 @enderror">{{ old('perex_en') }}</textarea>
                    @error('perex_en')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Obsah článku 🇨🇿 *</label>
                    <textarea name="content_cs" id="content_cs" rows="15" required 
                              class="input @error('content_cs') border-red-500 @enderror">{{ old('content_cs') }}</textarea>
                    @error('content_cs')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-coffee-600 mt-1">Povolené HTML tagy: &lt;a&gt;, &lt;strong&gt;, &lt;b&gt;, &lt;em&gt;, &lt;i&gt;, &lt;br&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;, &lt;h2&gt;, &lt;h3&gt;, &lt;blockquote&gt;, &lt;img&gt;</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Article Content 🇬🇧</label>
                    <textarea name="content_en" id="content_en" rows="15" 
                              placeholder="English content..."
                              class="input @error('content_en') border-red-500 @enderror">{{ old('content_en') }}</textarea>
                    @error('content_en')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Author -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Autor</label>
                    <input type="text" name="author" value="{{ old('author') }}" 
                           placeholder="Jméno autora"
                           class="input @error('author') border-red-500 @enderror">
                    @error('author')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Datum publikace</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" 
                           class="input @error('published_at') border-red-500 @enderror">
                    @error('published_at')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-coffee-600 mt-1">Ponechte prázdné pro aktuální datum při publikaci</p>
                </div>
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Tagy</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($tags as $tag)
                    <label class="flex items-center">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                               {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $tag->name_cs }}</span>
                    </label>
                    @endforeach
                </div>
                @if($tags->isEmpty())
                <p class="text-sm text-gray-500 mt-2">Zatím nejsou vytvořeny žádné tagy. <a href="{{ route('admin.tags.create') }}" class="text-blue-600 hover:underline">Vytvořit tag</a></p>
                @endif
            </div>

            <!-- Status -->
            <div class="bg-primary-50 border-2 border-primary-200 p-6 rounded-lg">
                <label class="block text-sm font-medium text-coffee-900 mb-3">Stav článku</label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="status" value="draft" {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-coffee-900">Koncept</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="status" value="published" {{ old('status') === 'published' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-coffee-900">Publikovat</span>
                    </label>
                </div>
                <p class="text-xs text-coffee-600 mt-2">Koncepty nejsou viditelné na webu</p>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="btn btn-primary">Vytvořit článek</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline">Zrušit</a>
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
</script>
@endsection
