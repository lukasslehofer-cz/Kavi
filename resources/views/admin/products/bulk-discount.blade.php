@extends('layouts.admin')

@section('title', 'Hromadné slevy')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Hromadné slevy</h1>
        </div>
        <p class="text-gray-600 mt-1">Aplikujte slevu na všechny produkty najednou</p>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $productsCount }}</p>
                    <p class="text-sm text-gray-600">Produktů k zlevnění</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $excludedCount }}</p>
                    <p class="text-sm text-gray-600">Vyloučeno ze slev</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $currentlyOnSale }}</p>
                    <p class="text-sm text-gray-600">Aktuálně ve slevě</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply Bulk Discount Form -->
    <form action="{{ route('admin.products.apply-bulk-discount') }}" method="POST" class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 mb-6">
        @csrf
        
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Aplikovat slevu na všechny produkty
        </h2>

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-900 mb-2">Typ slevy</label>
                <select name="discount_type" id="discount-type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="">Vyberte typ slevy</option>
                    <option value="percent" {{ old('discount_type') === 'percent' ? 'selected' : '' }}>Procentuální sleva</option>
                    <option value="amount" {{ old('discount_type') === 'amount' ? 'selected' : '' }}>Sleva částkou</option>
                </select>
            </div>

            <div id="discount-percent-container" style="display: none;">
                <label class="block text-sm font-medium text-gray-900 mb-2">Sleva v procentech (%)</label>
                <input type="number" name="discount_percent" value="{{ old('discount_percent') }}" step="0.01" min="0" max="100" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <p class="text-xs text-gray-600 mt-1">Např. 20 pro 20% slevu na všechny produkty</p>
            </div>

            <div id="discount-amount-container" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Sleva v Kč</label>
                        <input type="number" name="discount_amount_czk" value="{{ old('discount_amount_czk') }}" step="0.01" min="0" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-2">Sleva v EUR</label>
                        <input type="number" name="discount_amount_eur" value="{{ old('discount_amount_eur') }}" step="0.01" min="0" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>
                <p class="text-xs text-gray-600 mt-1">Zadejte částku, která se odečte od ceny každého produktu</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Začátek slevy (volitelné)</label>
                    <input type="datetime-local" name="sale_start_date" value="{{ old('sale_start_date') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">Konec slevy (volitelné)</label>
                    <input type="datetime-local" name="sale_end_date" value="{{ old('sale_end_date') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
            </div>
            <p class="text-xs text-gray-600">Nechte prázdné pro trvalou slevu bez časového omezení</p>

            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="show_discount_percentage" value="1" {{ old('show_discount_percentage', true) ? 'checked' : '' }}
                           class="rounded border-red-300 text-red-600 focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-900">Zobrazit procentuální slevu u produktů</span>
                </label>
                <p class="text-xs text-gray-600 mt-1 ml-6">Pokud je zaškrtnuto, zobrazí se badge s % slevy vedle ceny</p>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Upozornění</p>
                        <p class="text-sm text-yellow-700 mt-1">
                            Tato akce přepíše všechny stávající individuální slevy na produktech. 
                            Produkty označené jako "Vyloučit ze slev" nebudou ovlivněny.
                        </p>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Aplikovat slevu na {{ $productsCount }} produktů
            </button>
        </div>
    </form>

    <!-- Clear All Discounts -->
    <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Zrušit všechny slevy
        </h2>
        <p class="text-gray-600 mb-6">Odstraní všechny slevy ze všech produktů. Tato akce je nevratná.</p>
        
        <form action="{{ route('admin.products.clear-all-discounts') }}" method="POST" onsubmit="return confirm('Opravdu chcete zrušit všechny slevy? Tato akce je nevratná.');">
            @csrf
            <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Zrušit všechny slevy
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const discountTypeSelect = document.getElementById('discount-type');
    const discountPercentContainer = document.getElementById('discount-percent-container');
    const discountAmountContainer = document.getElementById('discount-amount-container');

    function toggleDiscountFields() {
        const discountType = discountTypeSelect.value;
        
        if (discountType === 'percent') {
            discountPercentContainer.style.display = 'block';
            discountAmountContainer.style.display = 'none';
        } else if (discountType === 'amount') {
            discountPercentContainer.style.display = 'none';
            discountAmountContainer.style.display = 'block';
        } else {
            discountPercentContainer.style.display = 'none';
            discountAmountContainer.style.display = 'none';
        }
    }

    discountTypeSelect.addEventListener('change', toggleDiscountFields);
    toggleDiscountFields(); // Initial state
});
</script>
@endsection

