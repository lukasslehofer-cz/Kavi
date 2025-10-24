@extends('layouts.app')

@section('title', 'Správa produktů - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="font-display text-4xl font-bold text-coffee-900 mb-2">Správa produktů</h1>
            <p class="text-coffee-600">Spravujte produkty ve vašem eshopu</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Přidat produkt
        </a>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-cream-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Název</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Kategorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Cena</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Sklad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Stav</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-cream-200">
                    @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-cream-100 rounded-lg mr-3 flex-shrink-0">
                                    @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                                    @endif
                                </div>
                                <span class="font-medium text-coffee-900">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-coffee-600">{{ ucfirst($product->category) }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-coffee-900">{{ number_format($product->price, 0, ',', ' ') }} Kč</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                {{ $product->stock }} ks
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($product->is_active)
                            <span class="badge badge-success">Aktivní</span>
                            @else
                            <span class="badge">Neaktivní</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-coffee-700 hover:text-coffee-900 underline">
                                Upravit
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Opravdu smazat?')" class="text-red-600 hover:text-red-800 underline">
                                    Smazat
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-coffee-600">
                            Žádné produkty. <a href="{{ route('admin.products.create') }}" class="text-coffee-700 underline">Přidat první produkt</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection




