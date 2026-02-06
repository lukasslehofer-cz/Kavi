@extends('layouts.app')

@section('title', 'Upravit článek - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Upravit článek</h1>
        <p class="text-coffee-600">{{ $post->title_cs }}</p>
    </div>

    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="card p-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Title -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Název článku 🇨🇿 *</label>
                    <input type="text" name="title_cs" id="title_cs" value="{{ old('title_cs', $post->title_cs) }}" required 
                           class="input @error('title_cs') border-red-500 @enderror">
                    @error('title_cs')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2 flex items-center justify-between">
                        <span>Article Title 🇬🇧</span>
                        <button type="button" onclick="translateField('title_cs', 'title_en')" class="translate-btn text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                            Přeložit
                        </button>
                    </label>
                    <input type="text" name="title_en" id="title_en" value="{{ old('title_en', $post->title_en) }}" 
                           placeholder="English article title"
                           class="input @error('title_en') border-red-500 @enderror">
                    @error('title_en')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Slugs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">URL slug 🇨🇿</label>
                    <input type="text" name="slug_cs" value="{{ old('slug_cs', $post->slug_cs) }}" 
                           class="input @error('slug_cs') border-red-500 @enderror">
                    @error('slug_cs')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-coffee-600 mt-1">např. "jak-spravne-varit-kavu"</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">URL slug 🇬🇧</label>
                    <input type="text" name="slug_en" value="{{ old('slug_en', $post->slug_en) }}" 
                           class="input @error('slug_en') border-red-500 @enderror">
                    @error('slug_en')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-coffee-600 mt-1">e.g. "how-to-brew-coffee"</p>
                </div>
            </div>

            <!-- Featured Image -->
            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Úvodní obrázek</label>
                
                @if($post->featured_image)
                <div class="mb-4">
                    <p class="text-sm text-coffee-600 mb-2">Aktuální obrázek:</p>
                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title_cs }}" 
                         class="w-full max-w-md h-48 object-cover rounded-lg border-2 border-gray-200">
                </div>
                @endif

                <input type="file" name="featured_image" accept="image/*" 
                       class="input @error('featured_image') border-red-500 @enderror"
                       onchange="previewImage(event)">
                
                <div id="image-preview" class="mt-4 hidden">
                    <p class="text-sm text-coffee-600 mb-2">Nový náhled:</p>
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
                              class="input @error('perex_cs') border-red-500 @enderror">{{ old('perex_cs', $post->perex_cs) }}</textarea>
                    @error('perex_cs')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2 flex items-center justify-between">
                        <span>Perex 🇬🇧</span>
                        <button type="button" onclick="translateField('perex_cs', 'perex_en')" class="translate-btn text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                            Přeložit
                        </button>
                    </label>
                    <textarea name="perex_en" id="perex_en" rows="3" 
                              placeholder="Short introduction..."
                              class="input @error('perex_en') border-red-500 @enderror">{{ old('perex_en', $post->perex_en) }}</textarea>
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
                              class="input @error('content_cs') border-red-500 @enderror">{{ old('content_cs', $post->content_cs) }}</textarea>
                    @error('content_cs')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-coffee-600 mt-1">Povolené HTML tagy: &lt;a&gt;, &lt;strong&gt;, &lt;b&gt;, &lt;em&gt;, &lt;i&gt;, &lt;br&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;, &lt;h2&gt;, &lt;h3&gt;, &lt;blockquote&gt;, &lt;img&gt;</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2 flex items-center justify-between">
                        <span>Article Content 🇬🇧</span>
                        <button type="button" onclick="translateField('content_cs', 'content_en')" class="translate-btn text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-2 py-1 rounded transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                            Přeložit
                        </button>
                    </label>
                    <textarea name="content_en" id="content_en" rows="15" 
                              placeholder="English content..."
                              class="input @error('content_en') border-red-500 @enderror">{{ old('content_en', $post->content_en) }}</textarea>
                    @error('content_en')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Author -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Autor</label>
                    <input type="text" name="author" value="{{ old('author', $post->author) }}" 
                           placeholder="Jméno autora"
                           class="input @error('author') border-red-500 @enderror">
                    @error('author')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-coffee-900 mb-2">Datum publikace</label>
                    <input type="datetime-local" name="published_at" 
                           value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}" 
                           class="input @error('published_at') border-red-500 @enderror">
                    @error('published_at')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-sm font-medium text-coffee-900 mb-2">Tagy</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($tags as $tag)
                    <label class="flex items-center">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                               {{ in_array($tag->id, old('tags', $selectedTags)) ? 'checked' : '' }}
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
                        <input type="radio" name="status" value="draft" {{ old('status', $post->status) === 'draft' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-coffee-900">Koncept</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="status" value="published" {{ old('status', $post->status) === 'published' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-coffee-900">Publikovat</span>
                    </label>
                </div>
                <p class="text-xs text-coffee-600 mt-2">Koncepty nejsou viditelné na webu</p>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-8">
            <button type="submit" class="btn btn-primary">Uložit změny</button>
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
</script>
@endsection
