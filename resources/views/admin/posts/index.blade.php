@extends('layouts.admin')

@section('title', 'Správa článků')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Správa článků</h1>
            <p class="text-gray-600 mt-1">Spravujte blogové články</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tags.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Správa tagů
            </a>
            <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Přidat článek
            </a>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">Celkem článků</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">Publikovaných</div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">Konceptů</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['drafts'] }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="flex flex-wrap items-center gap-4">
            <!-- Status Filter -->
            <div class="flex items-center gap-2">
                <label for="status" class="text-sm font-medium text-gray-700">Stav:</label>
                <select name="status" id="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Vše</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publikované</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Koncepty</option>
                </select>
            </div>

            <!-- Tag Filter -->
            <div class="flex items-center gap-2">
                <label for="tag" class="text-sm font-medium text-gray-700">Tag:</label>
                <select name="tag" id="tag" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                    <option value="">Všechny tagy</option>
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>{{ $tag->name_cs }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Hledat v názvu..." 
                       class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500 w-48">
                <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Hledat
                </button>
            </div>

            @if(request('status') && request('status') !== 'all' || request('tag') || request('search'))
            <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                Zrušit filtry
            </a>
            @endif
        </form>
    </div>

    <!-- Posts Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Název</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tagy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Publikováno</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-12 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($post->featured_image)
                                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title_cs }}" class="w-full h-full object-cover">
                                    @else
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900">{{ $post->title_cs }}</span>
                                    @if($post->author)
                                    <div class="text-xs text-gray-500">{{ $post->author }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex flex-wrap gap-1">
                                @forelse($post->tags as $tag)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $tag->name_cs }}
                                </span>
                                @empty
                                <span class="text-gray-400">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($post->status === 'published')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Publikováno</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Koncept</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($post->published_at)
                            {{ $post->published_at->format('j. n. Y H:i') }}
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                @if($post->status === 'published')
                                <a href="{{ localizedRoute('blog.show', ['post' => $post->getSlug()]) }}" target="_blank" class="text-gray-600 hover:text-gray-800 font-medium">
                                    Zobrazit
                                </a>
                                @endif
                                <a href="{{ route('admin.posts.edit', $post) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Upravit
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Opravdu smazat článek?')" class="text-red-600 hover:text-red-800 font-medium">
                                        Smazat
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            <p class="mb-2">Zatím žádné články</p>
                            <a href="{{ route('admin.posts.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">Přidat první článek</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-600">
            Zobrazeno {{ $posts->firstItem() ?? 0 }} - {{ $posts->lastItem() ?? 0 }} z {{ $posts->total() }} článků
        </div>
        <nav class="flex items-center gap-1">
            @if($posts->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">&laquo; Předchozí</span>
            @else
                <a href="{{ $posts->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">&laquo; Předchozí</a>
            @endif

            @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                @if($page == $posts->currentPage())
                    <span class="px-3 py-2 text-sm font-medium text-white bg-gray-900 border border-gray-900 rounded-lg">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            @if($posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Další &raquo;</a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">Další &raquo;</span>
            @endif
        </nav>
    </div>
    @endif
</div>
@endsection
