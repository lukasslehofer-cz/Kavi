@extends('layouts.app')

@section('title', 'Potvrzení objednávky - Kavi Coffee')

@section('content')
<!-- Success Hero Header -->
<div class="relative bg-green-50 py-16 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-6">
            <div class="w-20 h-20 mx-auto rounded-full bg-green-500 flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Děkujeme za objednávku!</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-4 font-light">
            Vaše objednávka byla úspěšně vytvořena a brzy ji začneme zpracovávat.
        </p>
        <p class="text-base text-gray-600 font-light">
            Číslo objednávky: <span class="font-medium text-gray-900">#{{ $order->id }}</span>
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Objednané produkty</h2>
                </div>
                
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item->product_image)
                            <img src="{{ asset($item->product_image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 mb-1">{{ $item->product_name }}</div>
                            <div class="text-sm text-gray-600 font-light">{{ $item->quantity }}× {{ number_format($item->price, 0, ',', ' ') }} Kč</div>
                        </div>
                        <div class="text-lg font-medium text-gray-900">
                            {{ number_format($item->total, 0, ',', ' ') }} Kč
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Price Summary -->
                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    <div class="flex justify-between items-center text-gray-700">
                        <span class="font-light">Mezisoučet:</span>
                        <span class="font-medium">{{ number_format($order->subtotal, 0, ',', ' ') }} Kč</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-700">
                        <span class="font-light">Doprava:</span>
                        <span class="font-medium">
                            @if($order->shipping == 0)
                                <span class="text-green-600">Zdarma</span>
                            @else
                                {{ number_format($order->shipping, 0, ',', ' ') }} Kč
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-gray-700">
                        <span class="font-light">DPH (21%):</span>
                        <span class="font-medium">{{ number_format($order->tax, 2, ',', ' ') }} Kč</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <span class="text-lg font-medium text-gray-900">Celkem:</span>
                        <span class="text-2xl font-bold text-gray-900">
                            {{ number_format($order->total, 0, ',', ' ') }} Kč
                        </span>
                    </div>
                </div>
            </div>

            <!-- Contact & Billing Information -->
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Kontaktní a fakturační údaje</h2>
                </div>
                
                <div class="bg-gray-100 p-6 rounded-xl border border-gray-200">
                    <div class="space-y-3 text-gray-700">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500 mb-0.5 font-light">Jméno</div>
                                <div class="font-medium text-gray-900">{{ $order->shipping_address['name'] }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500 mb-0.5 font-light">Email</div>
                                <div class="font-medium text-gray-900">{{ $order->shipping_address['email'] }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500 mb-0.5 font-light">Telefon</div>
                                <div class="font-medium text-gray-900">{{ $order->shipping_address['phone'] }}</div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3 mt-3"></div>
                        
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <div>
                                <div class="text-xs text-gray-500 mb-0.5 font-light">Fakturační adresa</div>
                                <div class="font-medium text-gray-900">{{ $order->shipping_address['billing_address'] }}, {{ $order->shipping_address['billing_postal_code'] }} {{ $order->shipping_address['billing_city'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packeta Delivery Point -->
            @if(!empty($order->shipping_address['packeta_point_name']))
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">📦 Výdejní místo zásilky</h2>
                </div>
                
                <div class="bg-primary-50 p-6 rounded-xl border border-primary-200">
                    <div class="text-sm font-medium text-primary-600 mb-2">Zásilkovna</div>
                    <div class="text-lg font-medium text-gray-900 mb-1">{{ $order->shipping_address['packeta_point_name'] }}</div>
                    @if(!empty($order->shipping_address['packeta_point_address']))
                    <div class="text-sm text-gray-700 flex items-start gap-1 font-light">
                        <svg class="w-4 h-4 text-gray-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $order->shipping_address['packeta_point_address'] }}
                    </div>
                    @endif
                    
                    <div class="mt-4 pt-4 border-t border-primary-200">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">Objednávku si vyzvedněte na tomto výdejním místě</span>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">📦 Doručovací adresa</h2>
                </div>
                
                <div class="bg-gray-100 p-6 rounded-xl border border-gray-200">
                    <div class="font-medium text-gray-900 mb-1">{{ $order->shipping_address['name'] }}</div>
                    <div class="text-gray-700 font-light">{{ $order->shipping_address['billing_address'] }}</div>
                    <div class="text-gray-700 font-light">{{ $order->shipping_address['billing_postal_code'] }} {{ $order->shipping_address['billing_city'] }}</div>
                </div>
            </div>
            @endif

            @if($order->customer_notes)
            <div class="bg-white rounded-2xl p-8 border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Poznámka</h2>
                </div>
                
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 text-gray-700 font-light">
                    {{ $order->customer_notes }}
                </div>
            </div>
            @endif
        </div>

        <!-- Next Steps Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-8 sticky top-24 border border-gray-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Co dál?</h3>
                </div>
                
                <div class="space-y-4 mb-6">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-600 font-medium text-sm">1</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 mb-1">Potvrzení emailem</div>
                            <div class="text-sm text-gray-600 font-light">Na váš email jsme odeslali potvrzení s detaily objednávky</div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-600 font-medium text-sm">2</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 mb-1">Zpracování objednávky</div>
                            <div class="text-sm text-gray-600 font-light">Vaši objednávku připravujeme k odeslání</div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-600 font-medium text-sm">3</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 mb-1">Sledování zásilky</div>
                            <div class="text-sm text-gray-600 font-light">Jakmile odešleme, pošleme vám tracking číslo</div>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                @if($order->payment_status === 'paid')
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-2 text-green-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Platba proběhla úspěšně</span>
                    </div>
                </div>
                @endif

                <div class="space-y-3">
                    <a href="{{ route('dashboard.orders') }}" class="block w-full text-center bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200">
                        Zobrazit objednávky
                    </a>
                    
                    <a href="{{ route('products.index') }}" class="block w-full text-center bg-white hover:bg-gray-50 text-gray-900 font-medium px-6 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200">
                        Pokračovat v nákupu
                    </a>
                </div>

                <!-- Contact Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-2 font-light">Potřebujete pomoc?</div>
                        <a href="mailto:info@kavi.cz" class="text-primary-600 hover:text-primary-700 font-medium">info@kavi.cz</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
