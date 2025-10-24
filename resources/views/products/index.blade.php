@extends('layouts.app')

@section('title', 'Obchod - Kavi Coffee')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-bluegray-100 via-white to-bluegray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-display text-5xl md:text-6xl font-bold text-dark-800 mb-4">Naše kávy</h1>
        <p class="text-xl text-dark-600 max-w-2xl mx-auto">Objevte naši pečlivě vybranou kolekci prémiových káv z celého světa</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Filters -->
    <div class="flex flex-wrap gap-3 mb-12 justify-center">
        <a href="{{ route('products.index') }}" 
           class="px-6 py-3 rounded-full font-semibold transition-all {{ !request('category') ? 'bg-primary-500 text-white shadow-lg' : 'bg-white text-dark-700 border border-bluegray-200 hover:border-primary-500' }}">
            Vše
        </a>
        @foreach($categories as $key => $label)
        <a href="{{ route('products.index', ['category' => $key]) }}" 
           class="px-6 py-3 rounded-full font-semibold transition-all {{ request('category') == $key ? 'bg-primary-500 text-white shadow-lg' : 'bg-white text-dark-700 border border-bluegray-200 hover:border-primary-500' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
        @forelse($products as $product)
        <a href="{{ route('products.show', $product) }}" class="card card-hover group">
            <div class="aspect-square overflow-hidden img-placeholder">
                @if($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                <div class="w-full h-full flex flex-col items-center justify-center p-8">
                    <svg class="w-20 h-20 text-bluegray-300 mb-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                    <p class="text-center text-sm">Balení kávy: {{ $product->name }}</p>
                </div>
                @endif
            </div>
            <div class="p-6">
                <div class="mb-2">
                    <span class="badge badge-primary text-xs">{{ $product->category ?? 'Káva' }}</span>
                </div>
                <h3 class="font-display text-xl font-bold text-dark-800 mb-2 group-hover:text-primary-500 transition-colors">
                    {{ $product->name }}
                </h3>
                @if($product->short_description)
                <p class="text-sm text-dark-600 mb-4 line-clamp-2">{{ $product->short_description }}</p>
                @endif
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-dark-800">{{ number_format($product->price, 0, ',', ' ') }} Kč</span>
                    @if($product->isInStock())
                    <span class="badge badge-success text-xs">Skladem</span>
                    @else
                    <span class="badge badge-error text-xs">Vyprodáno</span>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-16">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 text-bluegray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-dark-600 text-lg">Žádné produkty nebyly nalezeny.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="flex justify-center">
        {{ $products->links() }}
    </div>
    @endif
</div>

<!-- CTA Section -->
<section class="bg-bluegray-50 py-16 mt-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-3xl md:text-4xl font-bold text-dark-800 mb-4">
            Chcete pravidelnou dodávku?
        </h2>
        <p class="text-xl text-dark-600 mb-8">
            Vyzkoušejte naše předplatné a ušetřete až 20%
        </p>
        <a href="{{ route('subscriptions.index') }}" class="btn btn-primary text-lg">
            Zjistit více o předplatném
        </a>
    </div>
</section>
@endsection
