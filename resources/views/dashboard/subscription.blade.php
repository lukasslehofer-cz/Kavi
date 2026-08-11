@extends('layouts.dashboard')

@section('title', __('dashboard.title_subscription'))

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
                <h3 class="text-base font-bold text-green-900 mb-1">{{ __('dashboard.payment_success') }}</h3>
                <p class="text-sm text-green-800">{{ __('dashboard.payment_success_message') }}</p>
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
                <h3 class="text-base font-bold text-yellow-900 mb-1">{{ __('dashboard.payment_cancelled') }}</h3>
                <p class="text-sm text-yellow-800">{{ __('dashboard.payment_cancelled_message') }}</p>
            </div>
        </div>
    </div>
    @endif
    <!-- Page Header -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h1 class="text-xl font-bold text-gray-900">{{ __('dashboard.manage_subscriptions') }}</h1>
        <p class="mt-2 text-gray-600 font-light">{{ __('dashboard.subscriptions_overview', ['count' => $subscriptions->count()]) }}</p>
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
                    @elseif($subscription->frequency_months == 0)
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('dashboard.one_time_box') }}</h2>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-600 text-white text-xs font-bold rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span>{{ __('dashboard.no_subscription_badge') }}</span>
                            </span>
                        </div>
                        <p class="text-gray-600 font-light">{{ $subscription->subscription_number ?? '#' . $subscription->id }}</p>
                    @else
                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('dashboard.coffee_subscription') }} {{ $subscription->subscription_number ?? '#' . $subscription->id }}</h2>
                        <p class="text-gray-600 font-light">{{ __('dashboard.your_configuration') }}</p>
                    @endif
                    
                    <div class="mt-3">
                        @if($subscription->status === 'active')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                {{ __('dashboard.status_active') }}
                            </span>
                        @elseif($subscription->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('dashboard.status_pending_payment') }}
                            </span>
                        @elseif($subscription->status === 'completed')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('dashboard.status_completed') }}
                            </span>
                        @elseif($subscription->status === 'unpaid')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('dashboard.status_unpaid') }}
                            </span>
                        @elseif($subscription->status === 'paused')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                {{ __('dashboard.status_paused') }}
                                @if($subscription->paused_until_date)
                                    <span class="ml-2 text-yellow-700">{{ __('dashboard.status_paused_until', ['date' => $subscription->paused_until_date->format('d.m.Y')]) }}</span>
                                @endif
                            </span>
                        @elseif($subscription->status === 'complimentary')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                <span class="mr-2">🎁</span>
                                Bezplatné předplatné
                            </span>
                        @elseif($subscription->status === 'cancelled')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                {{ __('dashboard.status_cancelled') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    @php
                    // Rozpad z jednoho místa – fallback na plan->price přes SubscriptionPricing,
                    // aby se u EUR předplatného nevzala syrová CZK cena plánu.
                    $priceBreakdown = \App\Helpers\SubscriptionPricing::forRecurringPayment($subscription);
                    $fullPrice = $priceBreakdown->box;
                    $activeDiscount = $priceBreakdown->discount;
                    $basePrice = $fullPrice - $activeDiscount;
                    $shippingCost = $priceBreakdown->shipping;
                    $totalPrice = $priceBreakdown->total;
                    @endphp
                    <div class="text-2xl font-bold text-primary-600">
                        {{ \App\Helpers\CurrencyHelper::formatByCurrency($totalPrice, $subscription->currency) }}
                    </div>
                    <div class="text-sm text-gray-600 font-light mt-1">
                        @if($subscription->frequency_months === 0)
                            {{ __('dashboard.one_time_box') }}
                        @elseif($subscription->frequency_months)
                            {{ $subscription->frequency_months == 1 ? __('dashboard.per_month') : __('dashboard.per_months', ['count' => $subscription->frequency_months]) }}
                        @else
                            {{ __('dashboard.per_month') }}
                        @endif
                        @if($shippingCost > 0)
                        <span class="block text-xs text-gray-500 mt-1">({{ \App\Helpers\CurrencyHelper::formatByCurrency($basePrice, $subscription->currency) }} {{ __('dashboard.subscription_price') }} + {{ \App\Helpers\CurrencyHelper::formatByCurrency($shippingCost, $subscription->currency) }} {{ __('dashboard.shipping') }})</span>
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
                        <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('dashboard.next_shipment') }}</h3>
                        <div class="space-y-2 text-sm">
                            @php
                                $shipmentInfo = $shipmentInfos[$subscription->id] ?? null;
                            @endphp
                            @if($subscription->frequency_months == 0)
                                <!-- One-time box dates -->
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('dashboard.date') }}:</span>
                                    <span class="font-medium">{{ $subscription->created_at->format('d.m.Y') }}</span>
                                </div>
                                @if($shipmentInfo && $shipmentInfo->lastSentDate())
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('dashboard.shipped') }}:</span>
                                    <span class="font-medium text-green-600">{{ $shipmentInfo->lastSentDate()->format('d.m.Y') }}</span>
                                </div>
                                @elseif($subscription->status === 'active' || $subscription->status === 'pending')
                                @if($shipmentInfo && $shipmentInfo->nextShipmentDate())
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('dashboard.next_shipment') }}:</span>
                                    <span class="font-medium text-blue-600">{{ $shipmentInfo->nextShipmentDate()->format('d.m.Y') }}</span>
                                </div>
                                @endif
                                @endif
                                
                                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg mt-3">
                                    <p class="text-xs text-blue-800">
                                        ℹ️ {{ __('dashboard.one_time_box') }}
                                    </p>
                                </div>
                            @else
                                <!-- Regular subscription dates -->
                                @if($subscription->starts_at || $subscription->current_period_start)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('dashboard.date') }}:</span>
                                    <span class="font-medium">{{ ($subscription->starts_at ?? \Carbon\Carbon::parse($subscription->current_period_start))->format('d.m.Y') }}</span>
                                </div>
                                @endif
                                
                                @php
                                // Use centralized shipment info from service
                                $nextShipment = $shipmentInfo?->nextShipmentDate();
                                $lastSent = $shipmentInfo?->lastSentDate();
                                $pauseInfo = $shipmentInfo?->pauseInfo;
                            @endphp
                            @if($subscription->status === 'cancelled')
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('dashboard.status_cancelled') }}:</span>
                                <span class="font-medium text-red-700">{{ $subscription->ends_at ? $subscription->ends_at->format('d.m.Y') : '-' }}</span>
                            </div>
                            @if($lastSent)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('dashboard.shipped') }}:</span>
                                <span class="font-medium text-green-600">{{ $lastSent->format('d.m.Y') }}</span>
                            </div>
                            @endif
                            @if($nextShipment && $nextShipment->isFuture())
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('dashboard.next_shipment') }}:</span>
                                <span class="font-medium text-blue-600">{{ $nextShipment->format('d.m.Y') }}</span>
                            </div>
                            @endif
                            @elseif($subscription->isAdminLocked())
                            {{-- Pauza od správce nemá koncové datum, nemá co zobrazovat --}}
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('dashboard.status') }}:</span>
                                <span class="font-medium text-yellow-700">{{ __('dashboard.paused_by_admin') }}</span>
                            </div>
                            @elseif($subscription->status === 'paused' && $pauseInfo)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('dashboard.status_paused_until', ['date' => '']) }}</span>
                                <span class="font-medium text-yellow-700">{{ $pauseInfo->pausedUntil?->format('d.m.Y') ?? '-' }}</span>
                            </div>
                            @if($pauseInfo->resumeDate)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('dashboard.shipment_after_pause') }}:</span>
                                <span class="font-medium text-blue-600">{{ $pauseInfo->resumeDate->format('d.m.Y') }}</span>
                            </div>
                            @endif
                            @if($pauseInfo->skippedCount > 0)
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>{{ __('dashboard.skipped_shipments') ?? 'Přeskočené rozesílky' }}:</span>
                                <span>{{ $pauseInfo->skippedCount }}</span>
                            </div>
                            @endif
                            @elseif($nextShipment)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('dashboard.next_shipment') }}:</span>
                                <span class="font-medium text-blue-600">{{ $nextShipment->format('d.m.Y') }}</span>
                            </div>
                            @endif
                            @endif {{-- End of else for regular subscription dates --}}
                        </div>
                    </div>

                    <!-- Configuration -->
                    @if($subscription->configuration)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('dashboard.subscription_config') }}</h3>
                        <div class="space-y-2 text-sm">
                            @php
                                $config = is_string($subscription->configuration) 
                                    ? json_decode($subscription->configuration, true) 
                                    : $subscription->configuration;
                            @endphp

                            @if(isset($config['amount']))
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('dashboard.coffee_amount') }}:</span>
                                <span class="font-medium">{{ __('dashboard.bags_per_shipment', ['count' => $config['amount']]) }}</span>
                            </div>
                            @endif

                            @if(isset($config['type']))
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('dashboard.coffee_type') }}:</span>
                                <span class="font-medium">
                                    @if($config['type'] === 'espresso')
                                        {{ __('dashboard.espresso') }}
                                    @elseif($config['type'] === 'filter')
                                        {{ __('dashboard.filter') }}
                                    @else
                                        {{ __('dashboard.mix') }}
                                    @endif
                                </span>
                            </div>
                            @endif

                            @if(isset($config['isDecaf']) && $config['isDecaf'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('dashboard.decaf') }}:</span>
                                <span class="font-medium text-blue-600">{{ __('dashboard.yes_extra', ['amount' => \App\Helpers\CurrencyHelper::formatByCurrency(100, $subscription->currency)]) }}</span>
                            </div>
                            @endif

                            @if(isset($config['mix']) && $config['type'] === 'mix')
                            <div class="pt-2 border-t">
                                <span class="text-gray-600 block mb-2">{{ __('dashboard.mix') }}:</span>
                                <div class="space-y-1 ml-2">
                                    @if(isset($config['mix']['espresso']) && $config['mix']['espresso'] > 0)
                                    <div class="flex items-center text-gray-700">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>{{ $config['mix']['espresso'] }}× {{ __('dashboard.espresso') }}</span>
                                    </div>
                                    @endif
                                    @if(isset($config['mix']['filter']) && $config['mix']['filter'] > 0)
                                    <div class="flex items-center text-gray-700">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>{{ $config['mix']['filter'] }}× {{ __('dashboard.filter') }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <!-- Pricing breakdown (hidden for complimentary subscriptions) -->
                            @if(!$subscription->isComplimentary() && $subscription->configured_price)
                            @php
                                $breakdown = \App\Helpers\SubscriptionPricing::forRecurringPayment($subscription);
                            @endphp
                            <div class="pt-3 border-t-2 border-gray-300">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">{{ __('dashboard.subscription_price') }}:</span>
                                    <span class="font-medium">{{ \App\Helpers\CurrencyHelper::formatByCurrency($breakdown->box, $breakdown->currency) }}</span>
                                </div>
                                @if($breakdown->discount > 0)
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">{{ __('dashboard.discount') }}:</span>
                                    <span class="font-medium text-green-600">-{{ \App\Helpers\CurrencyHelper::formatByCurrency($breakdown->discount, $breakdown->currency) }}</span>
                                </div>
                                @endif
                                @if($breakdown->shipping > 0)
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">{{ __('dashboard.shipping') }}:</span>
                                    <span class="font-medium">{{ \App\Helpers\CurrencyHelper::formatByCurrency($breakdown->shipping, $breakdown->currency) }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between pt-2 border-t border-gray-300">
                                    <span class="text-gray-700 font-semibold">{{ __('dashboard.total') }}:</span>
                                    <span class="font-bold text-lg text-primary-600">{{ \App\Helpers\CurrencyHelper::formatByCurrency($breakdown->total, $breakdown->currency) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Active Discount Information (hidden for complimentary) -->
                    @if(!$subscription->isComplimentary() && $subscription->discount_amount > 0 && $subscription->coupon_id && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0))
                    <div>                        
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span class="font-bold text-green-900">{{ __('dashboard.active_discount') }}</span>
                                </div>
                                <span class="text-lg font-bold text-green-700">-{{ \App\Helpers\CurrencyHelper::formatByCurrency($subscription->discount_amount, $subscription->currency) }}</span>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-700">{{ __('dashboard.discount') }}:</span>
                                    <span class="font-mono font-bold text-gray-900">{{ $subscription->coupon_code }}</span>
                                </div>
                                @if($subscription->discount_months_total)
                                @php
                                // configured_price now contains FULL price (without discount)
                                // So original price = configured_price + shipping (NOT adding discount_amount)
                                $originalPrice = $subscription->configured_price + ($subscription->shipping_cost ?? 0);
                                
                                // Calculate next billing date based on subscription state
                                $frequencyMonths = max(1, (int)($subscription->frequency_months ?? 1));
                                
                                // Use shipmentInfo from the centralized service
                                if ($subscription->status === 'paused' && $pauseInfo?->resumeDate) {
                                    $nextBillingDate = $pauseInfo->resumeDate;
                                } elseif ($subscription->status === 'cancelled') {
                                    $nextBillingDate = $subscription->next_billing_date 
                                        ? \Carbon\Carbon::parse($subscription->next_billing_date) 
                                        : now();
                                } else {
                                    $nextBillingDate = $subscription->next_billing_date 
                                        ? \Carbon\Carbon::parse($subscription->next_billing_date) 
                                        : now()->addMonths($frequencyMonths);
                                }
                                
                                // Calculate when discount ends (last payment with discount)
                                $discountEndsAt = $nextBillingDate->copy()->addMonths(($subscription->discount_months_remaining - 1) * $frequencyMonths);
                                $fullPriceStartsAt = $discountEndsAt->copy()->addMonths($frequencyMonths);
                                @endphp
                                <div class="flex justify-between">
                                    <span class="text-gray-700">{{ __('dashboard.discount_remaining', ['count' => $subscription->discount_months_remaining]) }}</span>
                                    <span class="font-semibold text-gray-900">{{ $subscription->discount_months_remaining }} / {{ $subscription->discount_months_total }}</span>
                                </div>
                                @if($subscription->status === 'paused' && $subscription->paused_until_date)
                                <div class="flex justify-between py-2 px-2 bg-yellow-50 rounded border border-yellow-200">
                                    <span class="text-yellow-800 text-xs">⏸️ {{ __('dashboard.status_paused_until', ['date' => $subscription->paused_until_date->format('d.m.Y')]) }}</span>
                                </div>
                                @endif
                                @if($subscription->status !== 'cancelled')
                                <div class="flex justify-between pt-2 border-t border-green-300">
                                    <span class="text-gray-700">{{ __('dashboard.original_price') }}</span>
                                    <span class="font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::formatByCurrency($originalPrice, $subscription->currency) }}</span>
                                </div>
                                @endif
                                @else
                                <div class="flex justify-between">
                                    <span class="text-gray-700">{{ __('dashboard.discount') }}:</span>
                                    <span class="font-semibold text-green-700">{{ __('dashboard.discount_unlimited') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Shipping Address -->
                    @if($subscription->shipping_address)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('dashboard.delivery_address') }}</h3>
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
                            <p class="mt-2">{{ __('dashboard.phone') }}: {{ $address['phone'] }}</p>
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
                            <h3 class="text-lg font-medium text-gray-900">{{ __('dashboard.pickup_point') }}</h3>
                            <button type="button" 
                                    class="change-packeta-point text-sm text-blue-600 hover:text-blue-700 underline"
                                    data-subscription-id="{{ $subscription->id }}"
                                    data-current-id="{{ $subscription->packeta_point_id }}"
                                    data-current-name="{{ $subscription->packeta_point_name }}"
                                    data-current-address="{{ $subscription->packeta_point_address }}">
                                {{ __('messages.shipping.change') }}
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
                            <p>{{ __('dashboard.no_payment_method') }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Payment Issue Warning (not shown for complimentary) -->
                    @if(!$subscription->isComplimentary() && $subscription->status === 'unpaid')
                    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-6 mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h4 class="text-base font-bold text-red-900 mb-2">{{ __('dashboard.status_unpaid') }}</h4>
                                <p class="text-sm text-red-800 mb-3">
                                    {{ __('dashboard.subscription_payment_failed') }}
                                    @if($subscription->last_payment_failure_at)
                                        <span class="block mt-1">
                                            {{ __('dashboard.date') }}: {{ $subscription->last_payment_failure_at->format('d.m.Y H:i') }}
                                        </span>
                                    @endif
                                    @if($subscription->last_payment_failure_reason)
                                        <span class="block mt-1">
                                            {{ __('dashboard.reason') }} {{ $subscription->last_payment_failure_reason }}
                                        </span>
                                    @endif
                                </p>
                                @if($subscription->pending_invoice_amount)
                                <div class="bg-white rounded-lg p-4 mb-4 border border-red-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-700">{{ __('dashboard.amount_to_pay') }}</span>
                                        <span class="text-xl font-bold text-red-600">{{ \App\Helpers\CurrencyHelper::formatByCurrency($subscription->pending_invoice_amount, $subscription->currency) }}</span>
                                    </div>
                                </div>
                                @endif
                                <form method="POST" action="{{ localizedRoute('dashboard.subscription.pay', $subscription) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-3 rounded-full transition-colors">
                                        {{ __('dashboard.pay_now') }}
                                    </button>
                                </form>
                                <p class="text-xs text-gray-600 mt-3 text-center">
                                    {{ __('dashboard.secure_payment_stripe') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions (hidden for complimentary subscriptions) -->
                    @if(!$subscription->isComplimentary() && $subscription->frequency_months != 0)
                    {{-- Only show actions for regular subscriptions, not one-time boxes or complimentary --}}
                    <div>
                    @if($subscription->status === 'active' || $subscription->status === 'paused')
                        <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('dashboard.actions') }}</h3>                    
                    @endif
                        <div class="space-y-2">
                            @if($subscription->status === 'active')
                            @php
                                // Use service to get first UNPAID shipment date (skips already paid shipments)
                                $firstUnpaidForModal = app(\App\Services\SubscriptionShipmentService::class)
                                    ->getFirstUnpaidShipmentDate($subscription) ?? now();
                            @endphp
                            <button type="button"
                                    class="open-pause-modal w-full text-center px-4 py-2 text-sm border border-gray-300 text-gray-700 rounded-full hover:bg-gray-50 transition font-medium"
                                    data-subscription-id="{{ $subscription->id }}"
                                    data-frequency-months="{{ $subscription->frequency_months ?? 1 }}"
                                    data-first-unpaid="{{ $firstUnpaidForModal->format('Y-m-d') }}">
                                {{ __('dashboard.pause_subscription') }}
                            </button>

                            <button type="button"
                                    class="w-full text-center px-4 py-2 text-sm border border-red-600 text-red-600 rounded-full hover:bg-red-50 transition font-medium"
                                    onclick="openCancelModal({{ $subscription->id }})">
                                {{ __('dashboard.cancel_subscription') }}
                            </button>
                            @elseif($subscription->status === 'paused')
                            @if($subscription->isAdminLocked())
                            {{-- Pauzu vyvolal správce, obnovit ji může jen on --}}
                            <div class="w-full px-4 py-3 text-sm bg-yellow-50 border border-yellow-200 rounded-2xl text-yellow-800">
                                <p class="font-medium">{{ __('dashboard.paused_by_admin') }}</p>
                                <p class="mt-1 text-yellow-700">{{ __('dashboard.paused_by_admin_help') }}</p>
                            </div>
                            @else
                            <form method="POST" action="{{ localizedRoute('dashboard.subscription.resume') }}">
                                @csrf
                                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                                <button type="submit" class="w-full text-center px-4 py-2 text-sm border border-green-600 text-green-700 rounded-full hover:bg-green-50 transition font-medium">
                                    {{ __('dashboard.resume_subscription') }}
                                </button>
                            </form>
                            @endif

                            <button type="button"
                                    class="w-full text-center px-4 py-2 text-sm border border-red-600 text-red-600 rounded-full hover:bg-red-50 transition font-medium"
                                    onclick="openCancelModal({{ $subscription->id }})">
                                {{ __('dashboard.cancel_subscription') }}
                            </button>
                            @endif
                        </div>
                    </div>

                    {{-- Cancel modal (jen pro zrušitelná předplatná) --}}
                    @if(in_array($subscription->status, ['active', 'paused']))
                    @php $cancelPreview = $cancellationPreviews[$subscription->id] ?? null; @endphp
                    <div id="cancel-modal-{{ $subscription->id }}" class="cancel-modal fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40 p-4">
                        <div class="bg-white rounded-2xl w-full max-w-lg border border-gray-200 shadow-xl">
                            <div class="p-6 border-b border-gray-200 flex items-start gap-3">
                                <span class="shrink-0 mt-0.5 inline-flex items-center justify-center w-9 h-9 rounded-full bg-red-100 text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                                </span>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ __('dashboard.cancel_modal_title') }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ __('dashboard.cancel_modal_intro') }}</p>
                                </div>
                            </div>

                            <div class="p-6 space-y-3 text-sm">
                                @if($cancelPreview && !$cancelPreview->endsImmediately)
                                    <div class="rounded-xl bg-green-50 border border-green-200 p-3 text-green-800">
                                        @if($cancelPreview->paidCount === 1)
                                            {{ __('dashboard.cancel_paid_notice', ['date' => $cancelPreview->lastPaidDate->format('j. n. Y')]) }}
                                        @else
                                            {{ __('dashboard.cancel_paid_notice_multiple', ['count' => $cancelPreview->paidCount, 'date' => $cancelPreview->lastPaidDate->format('j. n. Y')]) }}
                                        @endif
                                    </div>
                                @else
                                    <div class="rounded-xl bg-gray-50 border border-gray-200 p-3 text-gray-700">
                                        {{ __('dashboard.cancel_immediate_notice') }}
                                    </div>
                                @endif

                                @if($cancelPreview && $cancelPreview->addonToDetachCount > 0)
                                    <div class="rounded-xl bg-yellow-50 border border-yellow-200 p-3 text-yellow-800">
                                        {{ __('dashboard.cancel_addons_notice', ['count' => $cancelPreview->addonToDetachCount]) }}
                                    </div>
                                @endif

                                <p class="text-gray-500">{{ __('dashboard.cancel_irreversible') }}</p>
                            </div>

                            <div class="p-6 border-t border-gray-200 flex items-center justify-end gap-3">
                                <button type="button"
                                        class="px-4 py-2 text-sm border border-gray-300 rounded-full hover:bg-gray-50"
                                        onclick="closeCancelModal({{ $subscription->id }})">
                                    {{ __('dashboard.keep_subscription') }}
                                </button>
                                <form method="POST" action="{{ localizedRoute('dashboard.subscription.cancel') }}">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                                    <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded-full hover:bg-red-700 transition font-medium">
                                        {{ __('dashboard.cancel_confirm') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Shipment History -->
        @if($shipmentInfo && $shipmentInfo->history->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mt-6">
            <div class="bg-gray-50 p-6 border-b border-gray-200">
                <h3 class="text-base font-bold text-gray-900">{{ __('dashboard.shipment_history') ?? 'Historie rozesílek' }}</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('dashboard.date') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('dashboard.status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('dashboard.tracking') ?? 'Sledování' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" data-history-group="shipment-history-{{ $subscription->id }}">
                        @foreach($shipmentInfo->history as $index => $shipment)
                        <tr class="hover:bg-gray-50 transition-colors {{ $index >= 5 ? 'subscription-history-extra hidden' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $shipment->shipment_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($shipment->status === 'sent' || $shipment->status === 'delivered')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                        ✓ {{ __('dashboard.shipped') ?? 'Odesláno' }}
                                    </span>
                                @elseif($shipment->status === 'pending')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                        ⏱ {{ __('dashboard.pending') }}
                                    </span>
                                @elseif($shipment->status === 'skipped')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        ⏸ {{ __('dashboard.skipped') ?? 'Přeskočeno' }}
                                    </span>
                                @elseif($shipment->status === 'cancelled')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                        ✕ {{ __('dashboard.cancelled') ?? 'Zrušeno' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($shipment->packeta_tracking_url)
                                    <a href="{{ $shipment->packeta_tracking_url }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-700 font-medium">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ __('dashboard.track_package') ?? 'Sledovat zásilku' }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($shipmentInfo->history->count() > 5)
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 text-center">
                <button type="button"
                        class="subscription-history-toggle inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 hover:text-primary-700"
                        data-target="shipment-history-{{ $subscription->id }}"
                        data-show-text="{{ __('dashboard.show_all_history') }}"
                        data-hide-text="{{ __('dashboard.show_less_history') }}">
                    <span class="toggle-label">{{ __('dashboard.show_all_history') }}</span>
                    <svg class="w-4 h-4 toggle-icon transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            @endif
        </div>
        @endif

        <!-- Payment History -->
        @php
            $payments = $subscription->payments()->orderBy('paid_at', 'desc')->get();
            // Identify the initial payment (oldest/first payment)
            $firstPaymentId = $payments->sortBy('paid_at')->first()?->id;
        @endphp
        
        @if($payments->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mt-6">
            <div class="bg-gray-50 p-6 border-b border-gray-200">
                <h3 class="text-base font-bold text-gray-900">{{ __('dashboard.payment_history') }}</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('dashboard.date') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('dashboard.next_shipment') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('dashboard.total') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('dashboard.status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('dashboard.invoice') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" data-history-group="payment-history-{{ $subscription->id }}">
                        @foreach($payments as $index => $payment)
                        <tr class="hover:bg-gray-50 transition-colors {{ $index >= 5 ? 'subscription-history-extra hidden' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->paid_at ? $payment->paid_at->format('d.m.Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-light">
                                @if($payment->expected_shipment_date)
                                    {{ $payment->expected_shipment_date->format('d.m.Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ \App\Helpers\CurrencyHelper::formatByCurrency($payment->amount, $subscription->currency) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($payment->status === 'paid')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                                        ✓ {{ __('dashboard.paid') }}
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        ⏱ {{ __('dashboard.pending') }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-red-100 text-red-800 border border-red-200">
                                        ✕ {{ __('dashboard.failed') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($payment->hasFaktura())
                                    <a href="{{ localizedRoute('dashboard.subscription.payment.invoice', $payment) }}" 
                                       class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-700 font-medium">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        {{ __('dashboard.download_invoice') }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($payments->count() > 5)
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 text-center">
                <button type="button"
                        class="subscription-history-toggle inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 hover:text-primary-700"
                        data-target="payment-history-{{ $subscription->id }}"
                        data-show-text="{{ __('dashboard.show_all_history') }}"
                        data-hide-text="{{ __('dashboard.show_less_history') }}">
                    <span class="toggle-label">{{ __('dashboard.show_all_history') }}</span>
                    <svg class="w-4 h-4 toggle-icon transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            @endif
        </div>
        @endif
    </div>
    @endforeach

    <!-- Add New Subscription -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('dashboard.no_active_subscription') }}</h3>
        <p class="text-gray-600 mb-4">{{ __('dashboard.start_coffee_journey') }}</p>
        <a href="{{ localizedRoute('subscriptions.index') }}" class="inline-block px-6 py-3 bg-primary-500 text-white rounded-full hover:bg-primary-600 transition font-medium">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('dashboard.select_subscription') }}
        </a>
    </div>

    <!-- Help Section -->
    <div class="mt-8 p-4 bg-gray-50 rounded-lg text-center">
        <p class="text-sm text-gray-600">
            @php $supportEmail = app()->getLocale() === 'en' ? 'info@kavibox.com' : 'info@kavi.cz'; @endphp
            <a href="mailto:{{ $supportEmail }}" class="text-blue-600 hover:text-blue-700">{{ $supportEmail }}</a>
        </p>
    </div>
</div>

<script src="https://widget.packeta.com/v6/www/js/library.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const packetaApiKey = '{{ config("services.packeta.widget_key") }}';
    const locale = '{{ app()->getLocale() }}';
    
    function openPacketaWidget(subscriptionId) {
        if (!packetaApiKey) {
            alert('Packeta widget not configured.');
            return;
        }

        Packeta.Widget.pick(packetaApiKey, function(point) {
            if (point) {
                // Format address
                let address = point.street;
                if (point.city) {
                    address += ', ' + (point.zip ? point.zip + ' ' : '') + point.city;
                }

                // Prepare data object with carrier information
                const updateData = {
                    subscription_id: subscriptionId,
                    packeta_point_id: point.id,
                    packeta_point_name: point.name,
                    packeta_point_address: address,
                    carrier_id: point.carrierId || null,
                    carrier_pickup_point: point.carrierPickupPointId || point.id
                };

                console.log('Updating pickup point with carrier info:', updateData);

                // Send update to server
                fetch('{{ localizedRoute("dashboard.subscription.update-packeta") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(updateData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the UI
                        const nameEl = document.querySelector('.point-name-' + subscriptionId);
                        const addressEl = document.querySelector('.point-address-' + subscriptionId);
                        
                        if (nameEl) nameEl.textContent = point.name;
                        if (addressEl) addressEl.textContent = address;
                        
                        alert(locale === 'en' ? 'Pickup point changed successfully!' : 'Výdejní místo bylo úspěšně změněno!');
                    } else {
                        alert(locale === 'en' ? 'Failed to change pickup point. Please try again.' : 'Nepodařilo se změnit výdejní místo. Zkuste to prosím znovu.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(locale === 'en' ? 'An error occurred while saving the pickup point.' : 'Došlo k chybě při ukládání výdejního místa.');
                });
            }
        }, {
            country: locale === 'en' ? 'all' : 'cz',
            language: locale,
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

    // Cancel modal (server-rendered, one per subscription)
    window.openCancelModal = function (id) {
        const m = document.getElementById('cancel-modal-' + id);
        if (!m) return;
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };
    window.closeCancelModal = function (id) {
        const m = document.getElementById('cancel-modal-' + id);
        if (!m) return;
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = '';
    };
    const closeAllCancelModals = function () {
        document.querySelectorAll('.cancel-modal').forEach(function (m) {
            m.classList.add('hidden');
            m.classList.remove('flex');
        });
        document.body.style.overflow = '';
    };
    // Backdrop click + Escape (registrováno jednou, žádné duplicitní listenery)
    document.querySelectorAll('.cancel-modal').forEach(function (m) {
        m.addEventListener('click', function (e) {
            if (e.target === m) { closeAllCancelModals(); }
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeAllCancelModals(); }
    });

    function createPauseModal() {
        const container = document.createElement('div');
        container.style.display = 'none';
        container.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 p-4';
        
        const pauseTitle = locale === 'en' ? 'Pause subscription' : 'Pozastavit předplatné';
        const pauseDesc = locale === 'en' ? 'Select how many shipments you want to skip.' : 'Vyberte, na kolik rozesílek chcete pauzu.';
        const pauseLength = locale === 'en' ? 'Pause length (shipments)' : 'Délka pauzy (počet rozesílek)';
        const shipment1 = locale === 'en' ? '1 shipment' : '1 rozesílka';
        const shipment2 = locale === 'en' ? '2 shipments' : '2 rozesílky';
        const shipment3 = locale === 'en' ? '3 shipments' : '3 rozesílky';
        const monthsLabel = locale === 'en' ? 'Actual months:' : 'Reálně měsíců:';
        const nextBoxLabel = locale === 'en' ? 'Next box after pause:' : 'Další box po pauze:';
        const cancelBtn = locale === 'en' ? 'Cancel' : 'Zrušit';
        const confirmBtn = locale === 'en' ? 'Confirm pause' : 'Potvrdit pauzu';
        
        container.innerHTML = `
            <div class="bg-white rounded-2xl w-full max-w-lg border border-gray-200 shadow-xl">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">${pauseTitle}</h3>
                    <p class="text-sm text-gray-600 mt-1">${pauseDesc}</p>
                </div>
                <form method="POST" action="{{ localizedRoute('dashboard.subscription.pause') }}" class="p-6 space-y-4">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="subscription_id" id="pause-subscription-id" value="">
                    <div class="space-y-2">
                        <label class="text-sm text-gray-700">${pauseLength}</label>
                        <div class="grid grid-cols-3 gap-2" id="pause-choices">
                            <label class="pause-choice border rounded-xl px-3 py-2 text-center text-sm cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="iterations" value="1" class="hidden" checked>
                                <span>${shipment1}</span>
                            </label>
                            <label class="pause-choice border rounded-xl px-3 py-2 text-center text-sm cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="iterations" value="2" class="hidden">
                                <span>${shipment2}</span>
                            </label>
                            <label class="pause-choice border rounded-xl px-3 py-2 text-center text-sm cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="iterations" value="3" class="hidden">
                                <span>${shipment3}</span>
                            </label>
                        </div>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm">
                        <div class="text-gray-700"><strong>${monthsLabel}</strong> <span id="pause-months">-</span></div>
                        <div class="text-gray-700 mt-1"><strong>${nextBoxLabel}</strong> <span id="pause-next-box-date">-</span></div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" id="pause-cancel" class="px-4 py-2 text-sm border border-gray-300 rounded-full hover:bg-gray-50">${cancelBtn}</button>
                        <button type="submit" class="px-4 py-2 text-sm bg-primary-500 text-white rounded-full hover:bg-primary-600">${confirmBtn}</button>
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
                    nextDateText = next.toLocaleDateString(locale === 'en' ? 'en-GB' : 'cs-CZ');
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

    document.querySelectorAll('.subscription-history-toggle').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const targetId = btn.dataset.target;
            const rows = document.querySelectorAll(
                '[data-history-group="' + targetId + '"] .subscription-history-extra'
            );
            const expanded = btn.classList.toggle('is-expanded');
            rows.forEach(function(row) {
                row.classList.toggle('hidden', !expanded);
            });
            const label = btn.querySelector('.toggle-label');
            if (label) {
                label.textContent = expanded ? btn.dataset.hideText : btn.dataset.showText;
            }
            const icon = btn.querySelector('.toggle-icon');
            if (icon) {
                icon.classList.toggle('rotate-180', expanded);
            }
        });
    });
});
</script>
@endsection
