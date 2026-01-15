@extends('layouts.admin')

@section('title', 'Správa produktů')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Správa produktů</h1>
            <p class="text-gray-600 mt-1">Spravujte produkty ve vašem eshopu</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.bulk-discount') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Hromadné slevy
            </a>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Přidat produkt
            </a>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <!-- Total Active -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">Aktivních celkem</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_active'] }}</div>
        </div>
        <!-- Total Stock -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">Kusů skladem</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_stock'] }}</div>
        </div>
        <!-- Espresso -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">Espresso</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['categories']['espresso']['active'] }}</div>
        </div>
        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">Filtr</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['categories']['filter']['active'] }}</div>
        </div>
        <!-- Decaf -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">Decaf</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['categories']['decaf']['active'] }}</div>
        </div>
        <!-- Accessories -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-500 mb-1">Příslušenství</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['categories']['accessories']['active'] }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-4">
            <!-- Category Filter -->
            <div class="flex items-center gap-2">
                <label for="category" class="text-sm font-medium text-gray-700">Kategorie:</label>
                <select name="category" id="category" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                    <option value="all" {{ request('category', 'all') === 'all' ? 'selected' : '' }}>Vše</option>
                    <option value="espresso" {{ request('category') === 'espresso' ? 'selected' : '' }}>Espresso</option>
                    <option value="filter" {{ request('category') === 'filter' ? 'selected' : '' }}>Filtr</option>
                    <option value="decaf" {{ request('category') === 'decaf' ? 'selected' : '' }}>Decaf</option>
                    <option value="accessories" {{ request('category') === 'accessories' ? 'selected' : '' }}>Příslušenství</option>
                </select>
            </div>

            <!-- Sort Filter -->
            <div class="flex items-center gap-2">
                <label for="sort" class="text-sm font-medium text-gray-700">Řazení:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-gray-500 focus:border-gray-500">
                    <option value="default" {{ request('sort', 'default') === 'default' ? 'selected' : '' }}>Výchozí (pořadí vložení)</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Abecedně</option>
                    <option value="stock" {{ request('sort') === 'stock' ? 'selected' : '' }}>Podle skladu</option>
                    <option value="roast_date" {{ request('sort') === 'roast_date' ? 'selected' : '' }}>Podle data pražení</option>
                </select>
            </div>

            @if(request('category') && request('category') !== 'all' || request('sort') && request('sort') !== 'default')
            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                Zrušit filtry
            </a>
            @endif
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Název</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sklad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    @endif
                                </div>
                                <span class="font-medium text-gray-900">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $categoryLabels = [
                                        'espresso' => 'Espresso',
                                        'filter' => 'Filtr',
                                        'decaf' => 'Bezkofeinová',
                                        'accessories' => 'Příslušenství',
                                    ];
                                    $categories = is_array($product->category) ? $product->category : [$product->category];
                                @endphp
                                @foreach($categories as $cat)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $categoryLabels[$cat] ?? ucfirst($cat) }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($product->isOnSale())
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-red-600">{{ number_format($product->getSalePrice(), 0, ',', ' ') }} Kč</span>
                                <span class="text-xs text-gray-500 line-through">{{ number_format($product->price, 0, ',', ' ') }} Kč</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">-{{ $product->getDiscountPercentage() }}%</span>
                            </div>
                            @elseif($product->discount_type)
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} Kč</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800" title="Sleva nastavena, ale není aktivní (časové omezení nebo vyloučeno ze slev)">
                                    <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                    Čeká
                                </span>
                            </div>
                            @else
                            <span class="font-bold text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} Kč</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->stock }} ks
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktivní</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Neaktivní</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Upravit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Opravdu smazat?')" class="text-red-600 hover:text-red-800 font-medium">
                                        Smazat
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="mb-2">Žádné produkty</p>
                            <a href="{{ route('admin.products.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">Přidat první produkt</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-600">
            Zobrazeno {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} z {{ $products->total() }} produktů
        </div>
        @if($products->hasPages())
        <nav class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if($products->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">
                    &laquo; Předchozí
                </span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    &laquo; Předchozí
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if($page == $products->currentPage())
                    <span class="px-3 py-2 text-sm font-medium text-white bg-gray-900 border border-gray-900 rounded-lg">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Další &raquo;
                </a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">
                    Další &raquo;
                </span>
            @endif
        </nav>
        @endif
    </div>
</div>
@endsection




