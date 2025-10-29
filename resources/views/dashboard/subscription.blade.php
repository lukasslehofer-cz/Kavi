@extends('layouts.dashboard')

@section('title', 'Předplatné - Kavi Coffee')

@section('content')
<div class="space-y-6">
    <!-- Success Messages -->
    @if(request('payment') === 'success')
    <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-base font-bold text-green-900 mb-1">Platba byla úspěšná!</h3>
                <p class="text-sm text-green-800">Vaše předplatné bylo obnoveno a bude normálně pokračovat.</p>
            </div>
        </div>
    </div>
    @endif

    @if(request('payment') === 'cancelled')
    <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-base font-bold text-yellow-900 mb-1">Platba byla zrušena</h3>
                <p class="text-sm text-yellow-800">Platbu můžete zkusit znovu kdykoli.</p>
            </div>
        </div>
    </div>
    @endif
    <!-- Page Header -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h1 class="text-xl font-bold text-gray-900">Správa předplatných</h1>
        <p class="mt-2 text-gray-600 font-light">Přehled všech vašich aktivních předplatných ({{ $subscriptions->count() }})</p>
    </div>

    @foreach($subscriptions as $subscription)
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden border border-gray-200" id="subscription-{{ $subscription->id }}">
        <!-- Subscription Header -->
        <div class="bg-gray-100 p-6 border-b border-gray-200">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div class="flex-1">
                    @if($subscription->plan)
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $subscription->plan->name }}</h2>
                        <p class="text-gray-600 font-light">{{ $subscription->plan->description }}</p>
                    @else
                        <h2 class="text-xl font-bold text-gray-900 mb-1">Kávové předplatné {{ $subscription->subscription_number ?? '#' . $subscription->id }}</h2>
                        <p class="text-gray-600 font-light">Váš vlastní konfigurace</p>
                    @endif
                    
                    <div class="mt-3">
                        @if($subscription->status === 'active')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                Aktivní
                            </span>
                        @elseif($subscription->status === 'unpaid')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Neuhrazená platba
                            </span>
                        @elseif($subscription->status === 'paused')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                Pozastaveno
                                @if($subscription->paused_until_date)
                                    <span class="ml-2 text-yellow-700">do {{ $subscription->paused_until_date->format('d.m.Y') }}</span>
                                @endif
                            </span>
                        @elseif($subscription->status === 'cancelled')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                Zrušeno
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-primary-600">
                        {{ number_format($subscription->configured_price ?? $subscription->plan->price, 0, ',', ' ') }} Kč
                    </div>
                    <div class="text-sm text-gray-600 font-light mt-1">
                        @if($subscription->frequency_months)
                            / {{ $subscription->frequency_months == 1 ? 'měsíc' : ($subscription->frequency_months . ' měsíce') }}
                        @else
                            / {{ $subscription->plan->billing_period === 'monthly' ? 'měsíc' : 'rok' }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Body -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Dates -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Důležité datumy</h3>
                        <div class="space-y-2 text-sm">
                            @if($subscription->starts_at || $subscription->current_period_start)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Začátek období:</span>
                                <span class="font-medium">{{ ($subscription->starts_at ?? \Carbon\Carbon::parse($subscription->current_period_start))->format('d.m.Y') }}</span>
                            </div>
                            @endif
                            
                            @php
                                $nextShipment = $subscription->next_shipment_date;
                                $lastPrePause = null;
                                $postPause = null;
                                $lastShipmentForCancelled = null;
                                
                                if ($subscription->status === 'paused' && $subscription->paused_until_date) {
                                    // Derive last shipment before pause from the first unpaid shipment
                                    $firstUnpaid = \App\Helpers\SubscriptionHelper::getFirstUnpaidShipmentDate($subscription);
                                    $freq = max(1, (int)($subscription->frequency_months ?? 1));
                                    $lastPrePause = $firstUnpaid ? $firstUnpaid->copy()->subMonths($freq) : null;
                                    // First shipment after pause end
                                    $postPause = \App\Helpers\SubscriptionHelper::getNextShipmentAfterDate(
                                        $subscription,
                                        \Carbon\Carbon::parse($subscription->paused_until_date)->startOfDay()
                                    );
                                }
                                
                                if ($subscription->status === 'cancelled') {
                                    // For cancelled, find the last paid shipment (which may be in the future)
                                    $candidate = $nextShipment ?? \App\Helpers\SubscriptionHelper::getNextShippingDate();
                                    $freq = max(1, (int)($subscription->frequency_months ?? 1));
                                    $guard = 0;
                                    while ($guard < 12) {
                                        if (\App\Helpers\SubscriptionHelper::hasPaidCoverageForDate($subscription, $candidate)) {
                                            $lastShipmentForCancelled = $candidate;
                                            break;
                                        }
                                        $candidate = $candidate->copy()->subMonths($freq);
                                        $guard++;
                                    }
                                }
                            @endphp
                            @if($subscription->status === 'cancelled')
                            <div class="flex justify-between">
                                <span class="text-gray-600">Datum zrušení:</span>
                                <span class="font-medium text-red-700">{{ $subscription->ends_at ? $subscription->ends_at->format('d.m.Y') : '-' }}</span>
                            </div>
                            @if($lastShipmentForCancelled)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Poslední rozesílka:</span>
                                <span class="font-medium text-blue-600">{{ $lastShipmentForCancelled->format('d.m.Y') }}</span>
                            </div>
                            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg mt-2">
                                <p class="text-xs text-blue-800">
                                    ℹ️ Toto předplatné je zrušeno. Dostanete ještě poslední zaplacený box {{ $lastShipmentForCancelled->format('d.m.Y') }} a pak předplatné automaticky zmizí z dashboardu.
                                </p>
                            </div>
                            @endif
                            @elseif($subscription->status === 'paused' && $subscription->paused_until_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pauza do:</span>
                                <span class="font-medium text-yellow-700">{{ $subscription->paused_until_date->format('d.m.Y') }}</span>
                            </div>
                            @if($lastPrePause)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Poslední rozesílka před pauzou:</span>
                                <span class="font-medium text-blue-600">{{ $lastPrePause->format('d.m.Y') }}</span>
                            </div>
                            @endif
                            @if($postPause)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rozesílka po pauze:</span>
                                <span class="font-medium text-blue-600">{{ $postPause->format('d.m.Y') }}</span>
                            </div>
                            @endif
                            <div class="text-xs text-gray-500 text-right mt-1">Rozesílka probíhá vždy 20. v měsíci</div>
                            @elseif($nextShipment)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Další rozesílka:</span>
                                <span class="font-medium text-blue-600">{{ $nextShipment->format('d.m.Y') }}</span>
                            </div>
                            <div class="text-xs text-gray-500 text-right mt-1">Rozesílka probíhá vždy 20. v měsíci</div>
                            @endif
                        </div>
                    </div>

                    <!-- Configuration -->
                    @if($subscription->configuration)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Konfigurace</h3>
                        <div class="space-y-2 text-sm">
                            @php
                                $config = is_string($subscription->configuration) 
                                    ? json_decode($subscription->configuration, true) 
                                    : $subscription->configuration;
                            @endphp

                            @if(isset($config['amount']))
                            <div class="flex justify-between">
                                <span class="text-gray-600">Množství:</span>
                                <span class="font-medium">{{ $config['amount'] }} balení</span>
                            </div>
                            @endif

                            @if(isset($config['type']))
                            <div class="flex justify-between">
                                <span class="text-gray-600">Typ kávy:</span>
                                <span class="font-medium">
                                    @if($config['type'] === 'espresso')
                                        Espresso
                                    @elseif($config['type'] === 'filter')
                                        Filter
                                    @else
                                        Mix
                                    @endif
                                </span>
                            </div>
                            @endif

                            @if(isset($config['isDecaf']) && $config['isDecaf'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Decaf varianta:</span>
                                <span class="font-medium text-blue-600">Ano (+100 Kč)</span>
                            </div>
                            @endif

                            @if(isset($config['mix']) && $config['type'] === 'mix')
                            <div class="pt-2 border-t">
                                <span class="text-gray-600 block mb-2">Rozložení mixu:</span>
                                <div class="space-y-1 ml-2">
                                    @if(isset($config['mix']['espresso']) && $config['mix']['espresso'] > 0)
                                    <div class="flex items-center text-gray-700">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>{{ $config['mix']['espresso'] }}× Espresso</span>
                                    </div>
                                    @endif
                                    @if(isset($config['mix']['filter']) && $config['mix']['filter'] > 0)
                                    <div class="flex items-center text-gray-700">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>{{ $config['mix']['filter'] }}× Filtr</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if(isset($config['frequencyText']))
                            <div class="flex justify-between">
                                <span class="text-gray-600">Frekvence:</span>
                                <span class="font-medium">{{ $config['frequencyText'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Shipping Address -->
                    @if($subscription->shipping_address)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Dodací adresa</h3>
                        @php
                            $address = is_string($subscription->shipping_address) 
                                ? json_decode($subscription->shipping_address, true) 
                                : $subscription->shipping_address;
                        @endphp
                        <div class="text-sm text-gray-700 space-y-1">
                            <p class="font-medium">{{ $address['name'] ?? '' }}</p>
                            @if(isset($address['billing_address']))
                            <p>{{ $address['billing_address'] ?? '' }}</p>
                            <p>{{ $address['billing_postal_code'] ?? '' }} {{ $address['billing_city'] ?? '' }}</p>
                            @else
                            <p>{{ $address['address'] ?? '' }}</p>
                            <p>{{ $address['postal_code'] ?? '' }} {{ $address['city'] ?? '' }}</p>
                            @endif
                            @if(isset($address['phone']))
                            <p class="mt-2">Tel: {{ $address['phone'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Packeta Pickup Point -->
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-medium text-gray-900">Výdejní místo Zásilkovna</h3>
                            <button type="button" 
                                    class="change-packeta-point text-sm text-blue-600 hover:text-blue-700 underline"
                                    data-subscription-id="{{ $subscription->id }}"
                                    data-current-id="{{ $subscription->packeta_point_id }}"
                                    data-current-name="{{ $subscription->packeta_point_name }}"
                                    data-current-address="{{ $subscription->packeta_point_address }}">
                                Změnit
                            </button>
                        </div>
                        
                        @if($subscription->packeta_point_id)
                        <div class="flex items-start text-sm">
                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-gray-700">
                                <p class="font-medium text-gray-900 point-name-{{ $subscription->id }}">{{ $subscription->packeta_point_name }}</p>
                                <p class="text-gray-600 mt-1 point-address-{{ $subscription->id }}">{{ $subscription->packeta_point_address }}</p>
                                <p class="text-xs text-gray-500 mt-1">ID: {{ $subscription->packeta_point_id }}</p>
                            </div>
                        </div>
                        @else
                        <div class="text-sm text-gray-600">
                            <p>Výdejní místo není nastaveno</p>
                        </div>
                        @endif
                    </div>

                    <!-- Payment Issue Warning -->
                    @if($subscription->status === 'unpaid')
                    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-6 mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h4 class="text-base font-bold text-red-900 mb-2">Neuhrazená platba</h4>
                                <p class="text-sm text-red-800 mb-3">
                                    Poslední platba za vaše předplatné se nezdařila. 
                                    @if($subscription->last_payment_failure_at)
                                        <span class="block mt-1">
                                            Datum selhání: {{ $subscription->last_payment_failure_at->format('d.m.Y H:i') }}
                                        </span>
                                    @endif
                                    @if($subscription->last_payment_failure_reason)
                                        <span class="block mt-1">
                                            Důvod: {{ $subscription->last_payment_failure_reason }}
                                        </span>
                                    @endif
                                </p>
                                <p class="text-sm text-red-800 mb-4">
                                    <strong>Než neuhradíte platbu, neobdržíte další kávový box.</strong>
                                    Po uhrazení se předplatné automaticky obnoví.
                                </p>
                                @if($subscription->pending_invoice_amount)
                                <div class="bg-white rounded-lg p-4 mb-4 border border-red-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-700">Částka k úhradě:</span>
                                        <span class="text-xl font-bold text-red-600">{{ number_format($subscription->pending_invoice_amount, 0, ',', ' ') }} Kč</span>
                                    </div>
                                </div>
                                @endif
                                <form method="POST" action="{{ route('dashboard.subscription.pay', $subscription) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-3 rounded-full transition-colors">
                                        Zaplatit nyní
                                    </button>
                                </form>
                                <p class="text-xs text-gray-600 mt-3 text-center">
                                    Bezpečná platba kartou přes Stripe
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Akce</h3>
                        <div class="space-y-2">
                            @if($subscription->status === 'active')
                            @php
                                $firstUnpaidForModal = \App\Helpers\SubscriptionHelper::getFirstUnpaidShipmentDate($subscription);
                            @endphp
                            <button type="button"
                                    class="open-pause-modal w-full text-center px-4 py-2 text-sm border border-gray-300 text-gray-700 rounded-full hover:bg-gray-50 transition font-medium"
                                    data-subscription-id="{{ $subscription->id }}"
                                    data-frequency-months="{{ $subscription->frequency_months ?? 1 }}"
                                    data-first-unpaid="{{ $firstUnpaidForModal->format('Y-m-d') }}">
                                Pozastavit předplatné
                            </button>

                            <form method="POST" action="{{ route('dashboard.subscription.cancel') }}">
                                @csrf
                                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                                <button type="submit" 
                                        class="w-full text-center px-4 py-2 text-sm border border-red-600 text-red-600 rounded-full hover:bg-red-50 transition font-medium" 
                                        onclick="return confirm('⚠️ POZOR: Opravdu chcete zrušit toto předplatné?\n\nToto předplatné již nepůjde obnovit. Dostanete poslední zaplacený box a pak již nebudete dostávat další zásilky.\n\nPokračovat se zrušením?')">
                                    Zrušit předplatné
                                </button>
                            </form>
                            @elseif($subscription->status === 'paused')
                            <form method="POST" action="{{ route('dashboard.subscription.resume') }}">
                                @csrf
                                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                                <button type="submit" class="w-full text-center px-4 py-2 text-sm border border-green-600 text-green-700 rounded-full hover:bg-green-50 transition font-medium" onclick="return confirm('Opravdu chcete přerušit pauzu a obnovit předplatné?')">
                                    Obnovit předplatné
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        @php
            $payments = $subscription->payments()->orderBy('paid_at', 'desc')->get();
            // Identify the initial payment (oldest/first payment)
            $firstPaymentId = $payments->sortBy('paid_at')->first()?->id;
        @endphp
        
        @if($payments->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mt-6">
            <div class="bg-gray-50 p-6 border-b border-gray-200">
                <h3 class="text-base font-bold text-gray-900">Historie plateb</h3>
                <p class="text-sm text-gray-600 mt-1">Přehled všech plateb a faktur</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Datum platby
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Rozesílka
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Částka
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Stav
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Faktura
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->paid_at ? $payment->paid_at->format('d.m.Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-light">
                                @if($payment->paid_at)
                                    @php
                                        // Check if this is the initial (first) payment
                                        $isInitialPayment = ($payment->id === $firstPaymentId);
                                        
                                        if ($isInitialPayment) {
                                            // For initial payment, find the next shipment date (20th) after paid_at
                                            $shipmentDate = $payment->paid_at->copy();
                                            if ($shipmentDate->day >= 20) {
                                                $shipmentDate->addMonth()->day(20);
                                            } else {
                                                $shipmentDate->day(20);
                                            }
                                        } else {
                                            // For recurring payments, derive from period_end
                                            $shipmentDate = $payment->period_end ? $payment->period_end->copy() : $payment->paid_at->copy();
                                            if ($shipmentDate->day > 20) {
                                                $shipmentDate->addMonth()->day(20);
                                            } else {
                                                $shipmentDate->day(20);
                                            }
                                        }
                                    @endphp
                                    {{ $shipmentDate->format('d.m.Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($payment->amount, 0, ',', ' ') }} Kč
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($payment->status === 'paid')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                        ✓ Zaplaceno
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        ⏱ Čeká
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-red-100 text-red-800 border border-red-200">
                                        ✕ Selhalo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($payment->hasFaktura())
                                    <a href="{{ route('dashboard.subscription.payment.invoice', $payment) }}" 
                                       class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-700 font-medium">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Stáhnout
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">Není k dispozici</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    @endforeach

    <!-- Add New Subscription -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
        <h3 class="text-lg font-medium text-gray-900 mb-2">Chcete přidat další předplatné?</h3>
        <p class="text-gray-600 mb-4">Nakonfigurujte si další předplatné podle svých potřeb</p>
        <a href="{{ route('subscriptions.index') }}" class="inline-block px-6 py-3 bg-primary-500 text-white rounded-full hover:bg-primary-600 transition font-medium">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Přidat nové předplatné
        </a>
    </div>

    <!-- Help Section -->
    <div class="mt-8 p-4 bg-gray-50 rounded-lg text-center">
        <p class="text-sm text-gray-600">
            <strong>Potřebujete pomoc?</strong> Kontaktujte naši zákaznickou podporu na 
            <a href="mailto:podpora@kavi.cz" class="text-blue-600 hover:text-blue-700">podpora@kavi.cz</a>
        </p>
    </div>
</div>

<script src="https://widget.packeta.com/v6/www/js/library.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const packetaApiKey = '{{ config("services.packeta.widget_key") }}';
    
    function openPacketaWidget(subscriptionId) {
        if (!packetaApiKey) {
            alert('Packeta widget není správně nakonfigurován. Kontaktujte administrátora.');
            return;
        }

        Packeta.Widget.pick(packetaApiKey, function(point) {
            if (point) {
                // Format address
                let address = point.street;
                if (point.city) {
                    address += ', ' + (point.zip ? point.zip + ' ' : '') + point.city;
                }

                // Send update to server
                fetch('{{ route("dashboard.subscription.update-packeta") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        subscription_id: subscriptionId,
                        packeta_point_id: point.id,
                        packeta_point_name: point.name,
                        packeta_point_address: address
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the UI
                        const nameEl = document.querySelector('.point-name-' + subscriptionId);
                        const addressEl = document.querySelector('.point-address-' + subscriptionId);
                        
                        if (nameEl) nameEl.textContent = point.name;
                        if (addressEl) addressEl.textContent = address;
                        
                        alert('Výdejní místo bylo úspěšně změněno!');
                    } else {
                        alert('Nepodařilo se změnit výdejní místo. Zkuste to prosím znovu.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Došlo k chybě při ukládání výdejního místa.');
                });
            }
        }, {
            country: 'cz',
            language: 'cs',
        });
    }

    // Event listeners for all change buttons
    document.querySelectorAll('.change-packeta-point').forEach(button => {
        button.addEventListener('click', function() {
            const subscriptionId = this.dataset.subscriptionId;
            openPacketaWidget(subscriptionId);
        });
    });

    // Pause modal
    const pauseModal = createPauseModal();
    document.body.appendChild(pauseModal.container);

    document.querySelectorAll('.open-pause-modal').forEach(button => {
        button.addEventListener('click', function() {
            const subId = this.dataset.subscriptionId;
            const freq = parseInt(this.dataset.frequencyMonths || '1', 10);
            const firstUnpaid = this.dataset.firstUnpaid ? new Date(this.dataset.firstUnpaid) : null;
            openPauseModal(subId, freq, firstUnpaid);
        });
    });

    function createPauseModal() {
        const container = document.createElement('div');
        container.style.display = 'none';
        container.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 p-4';
        container.innerHTML = `
            <div class="bg-white rounded-2xl w-full max-w-lg border border-gray-200 shadow-xl">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pozastavit předplatné</h3>
                    <p class="text-sm text-gray-600 mt-1">Vyberte, na kolik rozesílek chcete pauzu.</p>
                </div>
                <form method="POST" action="{{ route('dashboard.subscription.pause') }}" class="p-6 space-y-4">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="subscription_id" id="pause-subscription-id" value="">
                    <div class="space-y-2">
                        <label class="text-sm text-gray-700">Délka pauzy (počet rozesílek)</label>
                        <div class="grid grid-cols-3 gap-2" id="pause-choices">
                            <label class="pause-choice border rounded-xl px-3 py-2 text-center text-sm cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="iterations" value="1" class="hidden" checked>
                                <span>1 rozesílka</span>
                            </label>
                            <label class="pause-choice border rounded-xl px-3 py-2 text-center text-sm cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="iterations" value="2" class="hidden">
                                <span>2 rozesílky</span>
                            </label>
                            <label class="pause-choice border rounded-xl px-3 py-2 text-center text-sm cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="iterations" value="3" class="hidden">
                                <span>3 rozesílky</span>
                            </label>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm">
                        <div class="text-gray-700"><strong>Reálně měsíců:</strong> <span id="pause-months">-</span></div>
                        <div class="text-gray-700 mt-1"><strong>Další box po pauze:</strong> <span id="pause-next-box-date">-</span></div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" id="pause-cancel" class="px-4 py-2 text-sm border border-gray-300 rounded-full hover:bg-gray-50">Zrušit</button>
                        <button type="submit" class="px-4 py-2 text-sm bg-primary-500 text-white rounded-full hover:bg-primary-600">Potvrdit pauzu</button>
                    </div>
                </form>
            </div>
        `;

        const state = { frequencyMonths: 1, firstUnpaid: null };

        function updateInfo() {
            const selected = container.querySelector('input[name="iterations"]:checked');
            const iterations = selected ? parseInt(selected.value, 10) : 1;
            const months = iterations * (state.frequencyMonths || 1);
            container.querySelector('#pause-months').textContent = months;
            let nextDateText = '-';
            try {
                if (state.firstUnpaid instanceof Date && !isNaN(state.firstUnpaid)) {
                    const next = new Date(state.firstUnpaid);
                    next.setMonth(next.getMonth() + months);
                    nextDateText = next.toLocaleDateString('cs-CZ');
                }
            } catch (e) {}
            container.querySelector('#pause-next-box-date').textContent = nextDateText;
        }

        function applySelectedStyles() {
            const labels = container.querySelectorAll('#pause-choices .pause-choice');
            labels.forEach(label => {
                const input = label.querySelector('input[type="radio"]');
                if (input && input.checked) {
                    label.classList.add('border-primary-500','ring-2','ring-primary-500','bg-primary-50','text-primary-700');
                } else {
                    label.classList.remove('border-primary-500','ring-2','ring-primary-500','bg-primary-50','text-primary-700');
                }
            });
        }

        container.addEventListener('change', (e) => {
            if (e.target && e.target.name === 'iterations') {
                updateInfo();
                applySelectedStyles();
            }
        });

        return {
            container,
            open: (subscriptionId, frequencyMonths, firstUnpaid) => {
                state.frequencyMonths = frequencyMonths || 1;
                state.firstUnpaid = firstUnpaid || null;
                container.querySelector('#pause-subscription-id').value = subscriptionId;
                updateInfo();
                applySelectedStyles();
                container.style.display = 'flex';
            },
            close: () => {
                container.style.display = 'none';
            }
        };
    }

    function openPauseModal(subscriptionId, frequencyMonths, firstUnpaid) {
        pauseModal.open(subscriptionId, frequencyMonths, firstUnpaid);
        const cancelBtn = pauseModal.container.querySelector('#pause-cancel');
        cancelBtn.onclick = () => pauseModal.close();
        pauseModal.container.addEventListener('click', (e) => {
            if (e.target === pauseModal.container) {
                pauseModal.close();
            }
        });
    }
});
</script>
@endsection
