@extends('layouts.app')

@section('title', 'Potvrzení objednávky - KAVI.cz')

@section('content')

@if($cancelled ?? false)
<!-- Cancelled Payment Header -->
<div style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-8">
      <span class="text-dark-800">Platba</span><br>
      <span class="text-amber-500">nebyla dokončena</span>
    </h1>
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
      <div>
        <p class="text-xs uppercase tracking-widest text-warm-400 mb-2">Číslo objednávky</p>
        <p class="font-display text-2xl text-dark-800 uppercase tracking-tight">#{{ $order->order_number ?? $order->id }}</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ localizedRoute('payment.card', $order) }}" class="inline-flex items-center justify-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-8 py-4 transition-all">
          <span>Zaplatit znovu</span>
          <span>→</span>
        </a>
        <a href="{{ route('home') }}" class="group inline-flex items-center gap-2 text-warm-500 font-display uppercase tracking-widest hover:text-dark-800 transition-all px-4 py-4">
          <span>Zpět na hlavní stránku</span>
        </a>
      </div>
    </div>
  </div>
</div>
@else
<!-- Success Header -->
<div style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    <div class="flex items-center gap-3 mb-6">
      <span class="w-3 h-3 rounded-full bg-green-500"></span>
      <span class="text-xs uppercase tracking-widest text-green-600">Objednávka úspěšně vytvořena</span>
    </div>
    
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-8">
      <span class="text-dark-800">Děkujeme za</span><br>
      <span class="text-primary-500">objednávku</span>
    </h1>
    
    <div class="flex justify-end">
      <div class="text-right">
        <p class="text-xs uppercase tracking-widest text-warm-400 mb-2">Číslo objednávky</p>
        <p class="font-display text-2xl text-dark-800 uppercase tracking-tight">#{{ $order->order_number ?? $order->id }}</p>
      </div>
    </div>
  </div>
</div>
@endif

<div class="py-16 lg:py-24" style="background-color: #e5e6df;">
    <div class="max-w-screen-xl mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Order Details - Left Column -->
            <div class="lg:col-span-7 space-y-8">
                
                <!-- Subscription Addon Notice -->
                @if($order->shipped_with_subscription)
                <div class="bg-[#BCBEB1] p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                        <span class="text-xs uppercase tracking-widest text-dark-800">Odesláno s předplatným</span>
                    </div>
                    <p class="text-dark-800 text-sm leading-relaxed mb-4">
                        Vaše zboží bude přidáno do příští rozesílky předplatného 
                        @if($order->subscription)
                            <a href="{{ localizedRoute('dashboard.subscription', $order->subscription) }}" class="underline hover:text-primary-500">
                                {{ $order->subscription->subscription_number ?? '#' . $order->subscription->id }}
                            </a>
                        @endif
                        a odesláno společně dne 
                        <strong>{{ $order->shipmentSchedule ? $order->shipmentSchedule->shipment_date->format('d.m.Y') : 'dle plánu rozesílky' }}</strong>.
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-xs uppercase tracking-widest text-dark-800">Doprava zdarma</span>
                    </div>
                </div>
                @endif

                <!-- Order Items -->
                <div class="border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-8">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">01</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">Objednané produkty</h2>
                    </div>
                    
                    <div class="space-y-0">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-6 py-6 border-t border-warm-300">
                            <div class="w-20 h-20 flex-shrink-0 bg-warm-200">
                                @if($item->product_image)
                                <img src="{{ asset($item->product_image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain">
                                @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="font-display text-2xl text-warm-400">{{ substr($item->product_name, 0, 1) }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-display text-lg text-dark-800 uppercase tracking-tight mb-1">{{ $item->product_name }}</div>
                                <div class="text-xs uppercase tracking-widest text-warm-500">{{ $item->quantity }}× {{ number_format($item->price, 0, ',', ' ') }} Kč</div>
                            </div>
                            <div class="font-display text-lg text-dark-800">
                                {{ number_format($item->total, 0, ',', ' ') }} Kč
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Price Summary -->
                    <div class="mt-8 pt-6 border-t border-dark-800 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-xs uppercase tracking-widest text-warm-500">Mezisoučet</span>
                            <span class="text-dark-800">{{ number_format($order->subtotal, 0, ',', ' ') }} Kč</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-xs uppercase tracking-widest text-warm-500">Doprava</span>
                            <span class="text-dark-800">
                                @if($order->shipping == 0)
                                    <span class="text-green-600">Zdarma</span>
                                @else
                                    {{ number_format($order->shipping, 0, ',', ' ') }} Kč
                                @endif
                            </span>
                        </div>
                        
                        @if($order->discount_amount > 0 && $order->coupon)
                        <div class="flex justify-between items-center text-sm py-2">
                            <span class="text-xs uppercase tracking-widest text-green-600">Sleva {{ $order->coupon_code }}</span>
                            <span class="text-green-600">-{{ number_format($order->discount_amount, 0, ',', ' ') }} Kč</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center text-sm">
                            @php
                                $uniqueVatRates = $order->items->pluck('vat_rate')->unique();
                                $vatLabel = $uniqueVatRates->count() === 1
                                    ? 'DPH (' . number_format($uniqueVatRates->first(), 0) . '%)'
                                    : 'DPH';
                            @endphp
                            <span class="text-xs uppercase tracking-widest text-warm-500">{{ $vatLabel }}</span>
                            <span class="text-dark-800">{{ number_format($order->tax, 2, ',', ' ') }} Kč</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-dark-800">
                            <span class="font-display text-xl text-dark-800 uppercase tracking-tight">Celkem</span>
                            <span class="font-display text-3xl text-dark-800">
                                {{ number_format($order->total, 0, ',', ' ') }} Kč —
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact & Billing Information -->
                <div class="border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-8">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">02</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">Kontaktní údaje</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs uppercase tracking-widest text-warm-400 block mb-1">Jméno</span>
                                <span class="text-dark-800">{{ $order->shipping_address['name'] }}</span>
                            </div>
                            <div>
                                <span class="text-xs uppercase tracking-widest text-warm-400 block mb-1">Email</span>
                                <span class="text-dark-800">{{ $order->shipping_address['email'] }}</span>
                            </div>
                            <div>
                                <span class="text-xs uppercase tracking-widest text-warm-400 block mb-1">Telefon</span>
                                <span class="text-dark-800">{{ $order->shipping_address['phone'] }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs uppercase tracking-widest text-warm-400 block mb-1">Fakturační adresa</span>
                            <span class="text-dark-800">
                                {{ $order->shipping_address['billing_address'] }}<br>
                                {{ $order->shipping_address['billing_postal_code'] }} {{ $order->shipping_address['billing_city'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Delivery Point -->
                <div class="border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-8">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">03</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">
                            @if(!empty($order->shipping_address['packeta_point_name']))
                                Výdejní místo
                            @else
                                Doručovací adresa
                            @endif
                        </h2>
                    </div>
                    
                    @if(!empty($order->shipping_address['packeta_point_name']))
                    <div class="space-y-2">
                        <span class="text-xs uppercase tracking-widest text-warm-400 block">Zásilkovna</span>
                        <span class="font-display text-lg text-dark-800 uppercase tracking-tight block">{{ $order->shipping_address['packeta_point_name'] }}</span>
                        @if(!empty($order->shipping_address['packeta_point_address']))
                        <span class="text-warm-500 text-sm block">{{ $order->shipping_address['packeta_point_address'] }}</span>
                        @endif
                    </div>
                    @else
                    <div class="space-y-1">
                        <span class="text-dark-800">{{ $order->shipping_address['name'] }}</span><br>
                        <span class="text-warm-500">{{ $order->shipping_address['billing_address'] }}</span><br>
                        <span class="text-warm-500">{{ $order->shipping_address['billing_postal_code'] }} {{ $order->shipping_address['billing_city'] }}</span>
                    </div>
                    @endif
                </div>

                @if($order->customer_notes)
                <!-- Notes -->
                <div class="border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-8">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">04</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">Poznámka</h2>
                    </div>
                    <p class="text-warm-500 text-sm">{{ $order->customer_notes }}</p>
                </div>
                @endif
            </div>

            <!-- Next Steps Sidebar - Right Column -->
            <div class="lg:col-span-5">
                <div class="bg-[#BCBEB1] p-8 lg:sticky lg:top-24">
                    <h3 class="font-display text-2xl font-normal text-dark-800 uppercase tracking-tight mb-8">Co dál?</h3>
                    
                    <div class="space-y-6 mb-8">
                        <div class="flex gap-4">
                            <span class="text-xs uppercase tracking-widest text-primary-500 w-8">01</span>
                            <div>
                                <div class="font-display text-sm text-dark-800 uppercase tracking-tight mb-1">Potvrzení emailem</div>
                                <div class="text-xs text-dark-800/70">Na váš email jsme odeslali potvrzení s detaily objednávky</div>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <span class="text-xs uppercase tracking-widest text-primary-500 w-8">02</span>
                            <div>
                                <div class="font-display text-sm text-dark-800 uppercase tracking-tight mb-1">Zpracování objednávky</div>
                                <div class="text-xs text-dark-800/70">Vaši objednávku připravujeme k odeslání</div>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <span class="text-xs uppercase tracking-widest text-primary-500 w-8">03</span>
                            <div>
                                <div class="font-display text-sm text-dark-800 uppercase tracking-tight mb-1">Sledování zásilky</div>
                                <div class="text-xs text-dark-800/70">Jakmile odešleme, pošleme vám tracking číslo</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    @if($order->payment_status === 'paid')
                    <div class="flex items-center gap-2 mb-6 py-3 border-t border-dark-800/20">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-xs uppercase tracking-widest text-dark-800">Platba proběhla úspěšně</span>
                    </div>
                    @else
                    <div class="mb-6 py-3 border-t border-dark-800/20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span class="text-xs uppercase tracking-widest text-dark-800">Čeká na platbu</span>
                            </div>
                            @if($order->payment_method === 'card')
                            <a href="{{ localizedRoute('payment.card', $order) }}" class="text-xs uppercase tracking-widest text-primary-500 hover:text-dark-800 transition-colors underline">
                                Zaplatit
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="space-y-4">
                        <a href="{{ localizedRoute('dashboard.orders') }}" class="block w-full text-center bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all">
                            Zobrazit objednávky →
                        </a>
                        
                        <a href="{{ localizedRoute('products.index') }}" class="group flex items-center justify-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all py-2">
                            <span>Pokračovat v nákupu</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    </div>

                    <!-- Contact Info -->
                    <div class="mt-8 pt-6 border-t border-dark-800/20 text-center">
                        <span class="text-xs uppercase tracking-widest text-dark-800/60 block mb-2">Potřebujete pomoc?</span>
                        <a href="mailto:info@kavi.cz" class="text-xs uppercase tracking-widest text-dark-800 hover:text-primary-500 transition-colors">info@kavi.cz</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Google Ads Conversion Tracking --}}
@if($order->payment_status === 'paid' && !($cancelled ?? false))
<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'event': 'purchase',
        'ecommerce': {
            'transaction_id': '{{ $order->order_number ?? $order->id }}',
            'value': {{ $order->total }},
            'currency': '{{ $order->currency }}',
            'tax': {{ $order->tax }},
            'shipping': {{ $order->shipping }},
            @if($order->discount_amount > 0)
            'coupon': '{{ $order->coupon_code }}',
            'discount': {{ $order->discount_amount }},
            @endif
            'items': [
                @foreach($order->items as $item)
                {
                    'item_name': '{{ addslashes($item->product_name) }}',
                    'item_id': '{{ $item->product_id }}',
                    'price': {{ $item->price }},
                    'quantity': {{ $item->quantity }}
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
    });
</script>
@endif

@endsection
