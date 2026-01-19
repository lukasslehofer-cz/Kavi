@extends('layouts.app')

@section('title', __('cart.page_title'))

@section('flash-messages')
{{-- Flash messages are displayed in the header as technical text --}}
@endsection

@section('content')
<div style="background-color: rgb(245, 245, 244);">
<!-- Hero Header - Swiss Style -->
<div class="relative py-16 sm:py-20 lg:py-24 border-b border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-normal uppercase tracking-tight leading-[0.9]">
            <span class="text-dark-800">{{ $currentLocale === 'en' ? 'SHOPPING' : 'NÁKUPNÍ' }}</span> <span class="text-primary-500">{{ $currentLocale === 'en' ? 'CART' : 'KOŠÍK' }}</span>
        </h1>
        <div class="mt-6 flex flex-wrap items-baseline gap-x-6 gap-y-2">
            @php
                $count = count($cartItems ?? []);
                if ($currentLocale === 'en') {
                    $itemsWord = $count === 1 ? __('cart.items_count_1') : __('cart.items_count_5plus');
                } else {
                    $itemsWord = match(true) {
                        $count === 1 => __('cart.items_count_1'),
                        $count >= 2 && $count <= 4 => __('cart.items_count_234'),
                        default => __('cart.items_count_5plus')
                    };
                }
            @endphp
            <span class="text-xs uppercase tracking-widest text-warm-500">
                <span class="text-dark-800 font-display text-sm">{{ $count }}</span> {{ $itemsWord }}
            </span>
            @if(session('success'))
            <span class="text-xs uppercase tracking-widest text-green-600" style="letter-spacing: 0.15em;">
                STATUS / {{ strtoupper(session('success')) }}
            </span>
            @endif
            @if(session('error'))
            <span class="text-xs uppercase tracking-widest text-primary-500" style="letter-spacing: 0.15em;">
                STATUS / {{ strtoupper(session('error')) }}
            </span>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 overflow-x-hidden">
    @if(empty($cartItems))
    <!-- Empty Cart - Minimal Swiss Style -->
    <div class="max-w-2xl mx-auto text-center py-16">
        <div class="mb-8">
            <span class="font-display text-6xl sm:text-7xl text-warm-300">∅</span>
        </div>
        <h2 class="font-display text-3xl sm:text-4xl font-normal text-dark-800 uppercase tracking-tight mb-4">{{ __('cart.empty') }}</h2>
        <p class="text-warm-500 text-base mb-10 max-w-md mx-auto">{{ __('cart.empty_description') }}</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ localizedRoute('subscriptions.index') }}" class="inline-flex items-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-8 py-4 transition-all duration-200">
                <span>{{ __('cart.build_box') }}</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
            <a href="{{ localizedRoute('products.index') }}" class="text-xs uppercase tracking-widest text-warm-500 hover:text-dark-800 border-b border-warm-300 hover:border-dark-800 pb-1 transition-colors">
                {{ __('cart.view_shop') }}
            </a>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
        <!-- Cart Items - Inventory List Style -->
        <div class="lg:col-span-7">
            <!-- Free Shipping Status -->
            @if($shipping !== null && $shipping == 0)
            <div class="mb-8">
                <span class="text-xs uppercase tracking-widest text-dark-800">
                    <span class="inline-block w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                    {{ $currentLocale === 'en' ? 'GRATIS SHIPPING ACHIEVED' : 'DOPRAVA ZDARMA DOSAŽENA' }}
                </span>
            </div>
            @elseif($shipping !== null && $shipping > 0 && $remainingForFreeShipping !== null && $remainingForFreeShipping > 0)
            <div class="mb-8">
                <span class="text-xs uppercase tracking-widest text-warm-500">
                    @php
                        $remainingFormatted = $currentLocale === 'en' 
                            ? '€' . number_format($remainingForFreeShippingEur ?? 0, 2, '.', ' ')
                            : number_format($remainingForFreeShipping, 0, ',', ' ') . ' Kč';
                    @endphp
                    {!! __('cart.free_shipping_remaining', ['amount' => '<span class="text-dark-800">' . $remainingFormatted . '</span>']) !!}
                </span>
            </div>
            @endif

            <!-- Product List -->
            <div class="border-t border-dark-800">
                @foreach($cartItems as $item)
                <div class="py-6 border-b border-warm-300">
                    <div class="flex gap-4 sm:gap-6">
                        <!-- Product Image - Small and Sharp -->
                        <a href="{{ localizedRoute('products.show', $item['product']) }}" class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 bg-warm-100 overflow-hidden">
                            @if($item['product']->image)
                            <img src="{{ asset($item['product']->image) }}" alt="{{ $item['product']->getName() }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-warm-400 text-xs uppercase">{{ Str::limit($item['product']->getName(), 10) }}</span>
                            </div>
                            @endif
                        </a>

                        <!-- Product Info -->
                        <div class="flex-grow min-w-0 flex flex-col sm:flex-row sm:justify-between">
                            <div class="min-w-0 flex-grow">
                                <!-- Product Name -->
                                <h3 class="font-display text-lg sm:text-xl font-normal text-dark-800 uppercase tracking-tight mb-1">
                                    <a href="{{ localizedRoute('products.show', $item['product']) }}" class="hover:text-primary-500 transition-colors">
                                        {{ $item['product']->getName() }}
                                    </a>
                                </h3>
                                
                                <!-- Technical Details -->
                                <div class="text-xs uppercase tracking-widest text-warm-500 mb-3">
                                    @if($item['product']->roastery)
                                    {{ $item['product']->roastery->getName() }}
                                    @endif
                                    @if(!empty($item['product']->attributes['grind']))
                                    · {{ $item['product']->attributes['grind'] }}
                                    @endif
                                </div>

                                <!-- Quantity Controls - Simple Numbers -->
                                <div class="flex items-center gap-4">
                                    <form action="{{ localizedRoute('cart.update', $item['product']->id) }}" method="POST" class="flex items-center gap-1" id="quantity-form-{{ $item['product']->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] }}" id="quantity-input-{{ $item['product']->id }}">
                                        @for($i = 1; $i <= min(5, $item['product']->stock); $i++)
                                        <button type="button" 
                                                onclick="document.getElementById('quantity-input-{{ $item['product']->id }}').value = {{ $i }}; document.getElementById('quantity-form-{{ $item['product']->id }}').submit();"
                                                class="w-6 h-6 flex items-center justify-center text-xs font-display {{ $item['quantity'] == $i ? 'text-dark-800 border-b border-dark-800' : 'text-warm-400 hover:text-dark-800' }} transition-colors">
                                            {{ $i }}
                                        </button>
                                        @endfor
                                    </form>

                                    <!-- Remove Button -->
                                    <form action="{{ localizedRoute('cart.remove', $item['product']->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-warm-400 hover:text-dark-800 transition-colors uppercase tracking-widest" style="font-size: 8px;">
                                            {{ $currentLocale === 'en' ? 'REMOVE' : 'ODSTRANIT' }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Price - Right Side -->
                            <div class="mt-3 sm:mt-0 sm:ml-6 sm:text-right flex-shrink-0">
                                @if($item['product']->isOnSale())
                                <div class="flex sm:flex-col items-baseline sm:items-end gap-2">
                                    <span class="font-display text-lg sm:text-xl text-dark-800 uppercase tracking-tight">
                                        @if($currentLocale === 'en')
                                            €{{ number_format($item['subtotal_eur'] ?? 0, 2, '.', ' ') }} —
                                        @else
                                            {{ number_format($item['subtotal'], 0, ',', ' ') }} Kč —
                                        @endif
                                    </span>
                                    <span class="text-xs uppercase tracking-widest text-warm-400 line-through">
                                        {{ $item['product']->getFormattedOriginalPrice() }}
                                    </span>
                                </div>
                                @else
                                <span class="font-display text-lg sm:text-xl text-dark-800 uppercase tracking-tight">
                                    @if($currentLocale === 'en')
                                        €{{ number_format($item['subtotal_eur'] ?? 0, 2, '.', ' ') }} —
                                    @else
                                        {{ number_format($item['subtotal'], 0, ',', ' ') }} Kč —
                                    @endif
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Clear Cart -->
            <div class="mt-6">
                <form action="{{ localizedRoute('cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-warm-400 hover:text-dark-800 transition-colors uppercase tracking-widest" style="font-size: 8px;">
                        {{ $currentLocale === 'en' ? 'CLEAR ALL' : 'VYPRÁZDNIT VŠE' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary - Olive Background Style -->
        <div class="lg:col-span-5">
            <div class="sticky top-24 bg-olive-100 p-8">
                <h3 class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight mb-8">
                    {{ __('cart.order_summary') }}
                </h3>
                
                <dl class="space-y-3 mb-6">
                    <!-- Subtotal -->
                    <div class="flex justify-between items-baseline">
                        <dt class="text-xs uppercase tracking-widest text-olive-600">{{ __('cart.subtotal') }}</dt>
                        <dd class="text-sm text-dark-800 uppercase tracking-wide">
                            @if($currentLocale === 'en')
                                €{{ number_format($totalEur ?? 0, 2, '.', ' ') }}
                            @else
                                {{ number_format($total, 0, ',', ' ') }} Kč
                            @endif
                        </dd>
                    </div>
                    
                    <!-- Shipping -->
                    <div class="flex justify-between items-baseline">
                        <dt class="text-xs uppercase tracking-widest text-olive-600">{{ __('cart.shipping') }}</dt>
                        <dd class="text-sm uppercase tracking-wide">
                            @if($shipping !== null)
                                @if($shipping == 0)
                                    <span class="text-dark-800">{{ $currentLocale === 'en' ? 'FREE' : 'ZDARMA' }}</span>
                                @else
                                    <span class="text-dark-800">
                                        @if($currentLocale === 'en')
                                            €{{ number_format($shippingEur ?? 0, 2, '.', ' ') }}
                                        @else
                                            {{ number_format($shipping, 0, ',', ' ') }} Kč
                                        @endif
                                    </span>
                                @endif
                            @else
                                <span class="text-olive-500">{{ $shippingMessage ?? __('cart.at_checkout') }}</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                <!-- Total -->
                <div class="flex justify-between items-baseline py-6 border-t border-dark-800">
                    <dt class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight">{{ __('cart.grand_total') }}</dt>
                    <dd class="font-display text-2xl sm:text-3xl text-dark-800 uppercase tracking-tight">
                        @if($shipping !== null)
                            @if($currentLocale === 'en')
                                €{{ number_format(($totalEur ?? 0) + ($shippingEur ?? 0), 2, '.', ' ') }} —
                            @else
                                {{ number_format($total + $shipping, 0, ',', ' ') }} Kč —
                            @endif
                        @else
                            <span class="text-olive-500 text-sm">{{ __('cart.at_checkout') }}</span>
                        @endif
                    </dd>
                </div>

                <!-- CTA Buttons -->
                <div class="mt-8 space-y-4">
                    <a href="{{ localizedRoute('checkout.index') }}" class="group w-full flex items-center justify-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all duration-200">
                        <span>{{ $currentLocale === 'en' ? 'PROCEED TO PAYMENT' : 'POKRAČOVAT K PLATBĚ' }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    
                    <div class="text-center">
                        <a href="{{ localizedRoute('products.index') }}" class="inline-block text-xs uppercase tracking-widest text-olive-600 hover:text-dark-800 pb-1 transition-colors">
                            {{ __('cart.continue_shopping') }}
                        </a>
                    </div>
                </div>

                <!-- Trust Indicators - On Olive -->
                <div class="mt-10 pt-6 border-t border-dark-800 space-y-2">
                    <div class="text-xs uppercase tracking-widest text-olive-600">
                        <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                        {{ __('cart.secure_payment') }}
                    </div>
                    <div class="text-xs uppercase tracking-widest text-olive-600">
                        <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                        {{ __('cart.eco_packaging') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Recommendations - Swiss Style -->
@if(!empty($cartItems) && $recommendedProducts->isNotEmpty())
<section class="py-16 sm:py-20 lg:py-24 border-t border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal uppercase tracking-tight mb-4">
            <span class="text-dark-800">{{ $currentLocale === 'en' ? 'YOU MIGHT' : 'MOHLO BY VÁS' }}</span> <span class="text-primary-500">{{ $currentLocale === 'en' ? 'ALSO LIKE' : 'TAKÉ ZAJÍMAT' }}</span>
        </h2>
        <p class="text-warm-500 text-sm uppercase tracking-widest mb-12">{{ __('cart.recommendations_subtitle') }}</p>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-10 sm:gap-y-12">
            @foreach($recommendedProducts as $index => $product)
                <a href="{{ localizedRoute('products.show', $product) }}" class="group block {{ $index >= 2 ? 'hidden lg:block' : '' }}">
                    <!-- Image Container - No Frame -->
                    <div class="relative aspect-square overflow-hidden bg-warm-100 mb-4">
                        @if($product->image)
                        <img src="{{ asset($product->image) }}" loading="lazy" alt="{{ $product->getName() }}" class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105" />
                        @else
                        <div class="h-full w-full flex flex-col items-center justify-center p-8">
                            <span class="font-display text-4xl text-warm-300">{{ substr($product->getName(), 0, 1) }}</span>
                        </div>
                        @endif

                        <!-- Category Tag - Museum Catalog Code -->
                        @php
                            $categoryTags = [
                                'espresso' => 'ESP',
                                'filter' => 'FLT',
                                'decaf' => 'DCF',
                                'accessories' => 'ACC',
                            ];
                        @endphp
                        @if(is_array($product->category) && !empty($product->category))
                        <div class="absolute top-0 left-0 flex flex-col gap-1">
                            @foreach($product->category as $cat)
                                @if(isset($categoryTags[$cat]))
                                @php
                                    $tagColors = [
                                        'espresso' => 'border-amber-500',
                                        'filter' => 'border-blue-500',
                                        'decaf' => 'border-green-500',
                                        'accessories' => 'border-purple-500',
                                    ];
                                @endphp
                                <span class="text-[10px] uppercase tracking-widest text-dark-800 bg-[rgb(245,245,244)] px-2 py-1 border-b-2 {{ $tagColors[$cat] ?? 'border-dark-800' }}">{{ $categoryTags[$cat] }}</span>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        <!-- Discount Badge -->
                        @if($product->shouldShowDiscountPercentage())
                        <div class="absolute top-0 right-0">
                            <span class="text-[10px] uppercase tracking-widest text-white bg-primary-500 px-2 py-1">-{{ $product->getDiscountPercentage() }}%</span>
                        </div>
                        @endif
                    </div>

                    <!-- Product Info - Minimal Typography -->
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <!-- Price - Technical Label Above Name -->
                            <p class="text-xs uppercase tracking-widest text-warm-500 mb-1">
                                @if($product->isOnSale())
                                <span class="text-primary-500">{{ $product->getFormattedPrice() }}</span> —
                                @else
                                {{ $product->getFormattedPrice() }} —
                                @endif
                            </p>
                            
                            <!-- Product Name -->
                            <h3 class="font-display text-base sm:text-lg font-normal text-dark-800 uppercase tracking-tight pt-[5px] pb-[8px] group-hover:text-primary-500 transition-colors line-clamp-2" style="line-height: 1.25;">{{ $product->getName() }}</h3>
                            
                            <!-- Technical Info: Roastery · Flavor -->
                            <p class="text-xs uppercase tracking-widest text-warm-500 leading-tight">
                                @if($product->roastery)
                                    {{ $product->roastery->getName() }}
                                    @php
                                        $flavorNotes = $product->getTranslatedAttribute('flavor_notes') ?? $product->getTranslatedAttribute('flavor_profile');
                                    @endphp
                                    @if($flavorNotes)
                                        · {{ $flavorNotes }}
                                    @endif
                                @elseif(!empty($product->attributes['roaster']))
                                    {{ $product->attributes['roaster'] }}
                                @elseif(!empty($product->attributes['manufacturer']))
                                    {{ $product->attributes['manufacturer'] }}
                                @elseif(is_array($product->category) && in_array('accessories', $product->category) && $product->getShortDescription())
                                    {{ $product->getShortDescription() }}
                                @endif
                            </p>
                        </div>

                        <!-- Arrow -->
                        <div class="flex-shrink-0 mt-[26px]">
                            <svg class="w-5 h-5 text-warm-400 group-hover:text-dark-800 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
</div>
@endsection
