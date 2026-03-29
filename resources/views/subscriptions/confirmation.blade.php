@extends('layouts.app')

@section('title', $subscription->frequency_months == 0 ? 'Potvrzení objednávky - KAVI.cz' : 'Potvrzení předplatného - KAVI.cz')

@section('content')

<!-- Success Header -->
<div style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    <div class="flex items-center gap-3 mb-6">
      <span class="w-3 h-3 rounded-full bg-green-500"></span>
      <span class="text-xs uppercase tracking-widest text-green-600">{{ $subscription->frequency_months == 0 ? 'Objednávka potvrzena' : 'Předplatné aktivováno' }}</span>
    </div>
    
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-8">
      <span class="text-dark-800">Děkujeme za</span><br>
      <span class="text-primary-500">objednávku</span>
    </h1>
    
    <div class="flex justify-end">
      <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-500 max-w-md text-right leading-relaxed">
        @if($subscription->frequency_months == 0)
        Vaše objednávka jednorázového boxu byla úspěšně potvrzena. Zásilku vám odešleme v nejbližším termínu rozesílky.
        @else
        Vaše předplatné bylo úspěšně vytvořeno a je nyní aktivní. První zásilku vám odešleme v nejbližším termínu rozesílky.
        @endif
      </p>
    </div>
  </div>
</div>

<div class="py-16 lg:py-24" style="background-color: #e5e6df;">
    <div class="max-w-screen-xl mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Subscription Details - Left Column -->
            <div class="lg:col-span-7 space-y-8">
                
                @php
                $config = $subscription->configuration;
                if (is_string($config)) {
                    $config = json_decode($config, true);
                }
                $isOneTime = $subscription->frequency_months == 0;
                $frequencyTexts = [
                    0 => 'Jednorázový box',
                    1 => 'Každý měsíc',
                    2 => 'Jednou za 2 měsíce',
                    3 => 'Jednou za 3 měsíce'
                ];
                $frequencyText = $frequencyTexts[$subscription->frequency_months] ?? '';
                
                $shippingInfo = \App\Helpers\SubscriptionHelper::getShippingDateInfo();
                
                $subscriptionDate = \Carbon\Carbon::parse($subscription->starts_at);
                $currentBillingCycleEnd = $subscriptionDate->day <= 15 
                    ? $subscriptionDate->copy()->setDay(15) 
                    : $subscriptionDate->copy()->addMonthNoOverflow()->setDay(15);
                
                $nextPaymentDate = !$isOneTime 
                    ? $currentBillingCycleEnd->copy()->addMonths($subscription->frequency_months)
                    : null;
                @endphp

                <!-- Subscription Configuration -->
                <div class="border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-8">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">01</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ $isOneTime ? 'Vaše objednávka' : 'Vaše předplatné' }}</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">Množství</span>
                            <span class="font-display text-lg text-dark-800 uppercase tracking-tight">
                                {{ $config['amount'] }} balení ({{ $config['amount'] * 250 }}g)
                            </span>
                        </div>
                        
                        <div>
                            <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">Typ kávy</span>
                            <span class="font-display text-lg text-dark-800 uppercase tracking-tight">
                                @if($config['type'] === 'espresso')
                                    Espresso @if($config['isDecaf'] ?? false)(vč. 1× decaf)@endif
                                @elseif($config['type'] === 'filter')
                                    Filtr @if($config['isDecaf'] ?? false)(vč. 1× decaf)@endif
                                @else
                                    Kombinace @if($config['isDecaf'] ?? false)(vč. 1× decaf)@endif
                                @endif
                            </span>
                            @if($config['type'] === 'mix' && isset($config['mix']))
                            <div class="mt-2 text-xs text-warm-500 uppercase tracking-widest">
                                @if(($config['mix']['espresso'] ?? 0) > 0)
                                {{ $config['mix']['espresso'] }}× ESP
                                @endif
                                @if(($config['mix']['filter'] ?? 0) > 0)
                                · {{ $config['mix']['filter'] }}× FLT
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <div>
                            <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">Frekvence</span>
                            <span class="font-display text-lg text-dark-800 uppercase tracking-tight">{{ $frequencyText }}</span>
                        </div>
                        
                        <div>
                            <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">Cena</span>
                            <span class="font-display text-2xl text-dark-800">
                                {{ number_format($subscription->configured_price + ($subscription->shipping_cost ?? 0), 0, ',', ' ') }} Kč{{ $isOneTime ? '' : ' —' }}
                            </span>
                            @if(!$isOneTime)
                            <span class="text-xs text-warm-500 uppercase tracking-widest block mt-1">{{ $frequencyText }}</span>
                            @endif
                        </div>
                    </div>
                    
                    @if($subscription->discount_amount > 0 && $subscription->coupon)
                    @php
                    $originalPrice = $subscription->configured_price;
                    $discountEndsAt = (!$isOneTime && $nextPaymentDate) ? $nextPaymentDate->copy()->addMonths(($subscription->discount_months_remaining - 1) * $subscription->frequency_months) : null;
                    @endphp
                    
                    <!-- Coupon Discount Info -->
                    <div class="bg-[#BCBEB1] p-6 mb-8">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-xs uppercase tracking-widest text-dark-800">Sleva {{ $subscription->coupon_code }} aktivována</span>
                        </div>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-xs uppercase tracking-widest text-dark-800/70">Sleva</span>
                                <span class="text-dark-800">-{{ number_format($subscription->discount_amount, 0, ',', ' ') }} Kč</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs uppercase tracking-widest text-dark-800/70">Cena se slevou</span>
                                <span class="font-display text-dark-800">{{ number_format($subscription->configured_price - $subscription->discount_amount, 0, ',', ' ') }} Kč</span>
                            </div>
                            @if(!$isOneTime && $subscription->discount_months_total && $discountEndsAt)
                            <div class="flex justify-between items-center pt-3 border-t border-dark-800/20">
                                <span class="text-xs uppercase tracking-widest text-dark-800/70">Sleva platí do</span>
                                <span class="text-dark-800">{{ $discountEndsAt->format('j. n. Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs uppercase tracking-widest text-dark-800/70">Plná cena od</span>
                                <span class="font-display text-dark-800">{{ number_format($originalPrice, 0, ',', ' ') }} Kč</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Shipping and Payment Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-warm-300">
                        <div>
                            <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">{{ $isOneTime ? 'Rozesílka' : 'První rozesílka' }}</span>
                            <span class="font-display text-lg text-primary-500">{{ $shippingInfo['next_shipping_date']->format('j. n. Y') }}</span>
                            <span class="text-xs text-warm-500 block mt-1">20. den v měsíci</span>
                        </div>
                        
                        @if(!$isOneTime && $nextPaymentDate)
                        <div>
                            <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">Další platba</span>
                            <span class="font-display text-lg text-dark-800">{{ $nextPaymentDate->format('j. n. Y') }}</span>
                            <span class="text-xs text-warm-500 block mt-1">15. den v měsíci</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Contact & Billing Information -->
                @if($subscription->shipping_address)
                <div class="border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-8">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">02</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">Kontaktní údaje</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs uppercase tracking-widest text-warm-400 block mb-1">Jméno</span>
                                <span class="text-dark-800">{{ $subscription->shipping_address['name'] }}</span>
                            </div>
                            <div>
                                <span class="text-xs uppercase tracking-widest text-warm-400 block mb-1">Email</span>
                                <span class="text-dark-800">{{ $subscription->shipping_address['email'] }}</span>
                            </div>
                            @if(!empty($subscription->shipping_address['phone']))
                            <div>
                                <span class="text-xs uppercase tracking-widest text-warm-400 block mb-1">Telefon</span>
                                <span class="text-dark-800">{{ $subscription->shipping_address['phone'] }}</span>
                            </div>
                            @endif
                        </div>
                        <div>
                            <span class="text-xs uppercase tracking-widest text-warm-400 block mb-1">Fakturační adresa</span>
                            <span class="text-dark-800">
                                {{ $subscription->shipping_address['billing_address'] }}<br>
                                {{ $subscription->shipping_address['billing_postal_code'] }} {{ $subscription->shipping_address['billing_city'] }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Packeta Delivery Point -->
                @if($subscription->packeta_point_name)
                <div class="border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-8">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">03</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">Výdejní místo</h2>
                    </div>
                    
                    <div class="space-y-2">
                        <span class="text-xs uppercase tracking-widest text-warm-400 block">Zásilkovna</span>
                        <span class="font-display text-lg text-dark-800 uppercase tracking-tight block">{{ $subscription->packeta_point_name }}</span>
                        @if($subscription->packeta_point_address)
                        <span class="text-warm-500 text-sm block">{{ $subscription->packeta_point_address }}</span>
                        @endif
                    </div>
                </div>
                @endif

                @if($subscription->delivery_notes)
                <div class="border-t-2 border-primary-500 pt-6">
                    <div class="flex items-baseline gap-4 mb-8">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">04</span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">Poznámka</h2>
                    </div>
                    <p class="text-warm-500 text-sm">{{ $subscription->delivery_notes }}</p>
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
                                <div class="text-xs text-dark-800/70">Na váš email jsme odeslali potvrzení s detaily {{ $isOneTime ? 'objednávky' : 'předplatného' }}</div>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <span class="text-xs uppercase tracking-widest text-primary-500 w-8">02</span>
                            <div>
                                <div class="font-display text-sm text-dark-800 uppercase tracking-tight mb-1">{{ $isOneTime ? 'Zásilka' : 'První zásilka' }}</div>
                                <div class="text-xs text-dark-800/70">Kávu vám odešleme v nejbližším termínu rozesílky (20. den v měsíci)</div>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <span class="text-xs uppercase tracking-widest text-primary-500 w-8">03</span>
                            <div>
                                @if($isOneTime)
                                <div class="font-display text-sm text-dark-800 uppercase tracking-tight mb-1">Bez závazku</div>
                                <div class="text-xs text-dark-800/70">Jednorázový nákup bez předplatného. Žádné další platby neproběhnou.</div>
                                @else
                                <div class="font-display text-sm text-dark-800 uppercase tracking-tight mb-1">Správa předplatného</div>
                                <div class="text-xs text-dark-800/70">
                                    @auth
                                    V dashboardu můžete kdykoli upravit nebo zrušit předplatné
                                    @else
                                    Pro správu předplatného si vytvořte účet - link jsme vám poslali na email
                                    @endauth
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @auth
                        <a href="{{ localizedRoute('dashboard.subscription') }}" class="block w-full text-center bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all">
                            {{ $isOneTime ? 'Zobrazit objednávku' : 'Zobrazit předplatné' }} →
                        </a>
                        @else
                        <a href="{{ localizedRoute('login') }}" class="block w-full text-center bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all">
                            Přihlásit se →
                        </a>
                        @endauth
                        
                        <a href="{{ route('home') }}" class="group flex items-center justify-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all py-2">
                            <span>Zpět na homepage</span>
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

{{-- Conversion Tracking: dataLayer + Meta Pixel --}}
@if($subscription->status === 'active' && ($shouldFirePixel ?? false))
@php
    $trackingValue = $subscription->configured_price - ($subscription->discount_amount ?? 0) + ($subscription->shipping_cost ?? 0);
    $trackingCurrency = $subscription->currency ?? 'CZK';
    $trackingItemId = 'subscription-' . ($config['amount'] ?? 3);
    $trackingItemName = 'Subscription ' . (($config['amount'] ?? 3) == 2 ? 'M' : (($config['amount'] ?? 3) == 4 ? 'XL' : 'L')) . ' Box';
@endphp
<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'event': 'purchase',
        'ecommerce': {
            'transaction_id': 'SUB-{{ $subscription->id }}',
            'value': {{ $trackingValue }},
            'currency': '{{ $trackingCurrency }}',
            'shipping': {{ $subscription->shipping_cost ?? 0 }},
            @if(($subscription->discount_amount ?? 0) > 0)
            'coupon': '{{ $subscription->coupon_code }}',
            'discount': {{ $subscription->discount_amount }},
            @endif
            'items': [{
                'item_id': '{{ $trackingItemId }}',
                'item_name': '{{ $trackingItemName }}',
                'price': {{ $subscription->configured_price - ($subscription->discount_amount ?? 0) }},
                'quantity': 1,
                'item_category': 'subscription'
            }]
        }
    });

    // Meta Pixel - Purchase (with eventID for CAPI deduplication)
    if (typeof fbq !== 'undefined') {
        fbq('track', 'Purchase', {
            content_ids: ['{{ $trackingItemId }}'],
            content_type: 'product',
            value: {{ $trackingValue }},
            currency: '{{ $trackingCurrency }}',
            num_items: 1
        }, {eventID: '{{ $subscription->meta_event_id }}'});
    }
</script>
@endif

@endsection
