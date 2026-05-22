@extends('layouts.dashboard')

@section('title', __('dashboard.title_dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ __('dashboard.welcome_back') }}</h1>
        <p class="text-base text-gray-600 font-light">{{ __('dashboard.welcome_message') }}, <span class="text-primary-600 font-medium">{{ auth()->user()->name }}</span></p>
    </div>

    <!-- Unpaid Subscriptions Alert -->
    @if($unpaidSubscriptions->isNotEmpty())
    <div class="bg-red-50 rounded-2xl border-2 border-red-200 p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-red-900 mb-2">
                    {{ $unpaidSubscriptions->count() > 1 ? __('dashboard.payment_issue_subscriptions') : __('dashboard.payment_issue_subscription') }}
                </h3>
                <p class="text-red-800 mb-4">
                    {{ $unpaidSubscriptions->count() > 1 
                        ? __('dashboard.subscriptions_payment_failed')
                        : __('dashboard.subscription_payment_failed')
                    }}
                </p>
                
                @foreach($unpaidSubscriptions as $unpaidSub)
                <div class="bg-white rounded-xl border border-red-200 p-4 mb-3">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="text-gray-900 font-bold text-base mb-2">
                                {{ __('dashboard.subscription_number', ['id' => $unpaidSub->id]) }}
                                @if($unpaidSub->subscription_number)
                                    <span class="text-sm font-normal text-gray-600">({{ $unpaidSub->subscription_number }})</span>
                                @endif
                            </div>
                            @if($unpaidSub->pending_invoice_amount)
                            <div class="text-gray-700 text-sm mb-1">
                                <span class="text-gray-600">{{ __('dashboard.amount_to_pay') }}</span> 
                                <span class="font-bold text-red-600">{{ \App\Helpers\CurrencyHelper::formatByCurrency($unpaidSub->pending_invoice_amount, $unpaidSub->currency) }}</span>
                            </div>
                            @endif
                            @if($unpaidSub->last_payment_failure_reason)
                            <div class="text-gray-600 text-xs">
                                <span class="font-medium">{{ __('dashboard.reason') }}</span> {{ $unpaidSub->last_payment_failure_reason }}
                            </div>
                            @endif
                        </div>
                        <form method="POST" action="{{ localizedRoute('dashboard.subscription.pay', $unpaidSub) }}">
                            @csrf
                            <button type="submit" class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2.5 rounded-full transition-colors">
                                {{ __('dashboard.pay_now') }}
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
                
                <div class="text-sm text-gray-600 mt-3">
                    {{ __('dashboard.secure_payment_stripe') }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Unpaid Orders Alert -->
    @if($unpaidOrders->isNotEmpty())
    <div class="bg-red-50 rounded-2xl border-2 border-red-200 p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-red-900 mb-2">
                    {{ $unpaidOrders->count() > 1 ? __('dashboard.payment_issue_orders') : __('dashboard.payment_issue_order') }}
                </h3>
                <p class="text-red-800 mb-4">
                    {{ $unpaidOrders->count() > 1 
                        ? __('dashboard.orders_payment_failed')
                        : __('dashboard.order_payment_failed')
                    }}
                </p>
                
                @foreach($unpaidOrders as $unpaidOrder)
                <div class="bg-white rounded-xl border border-red-200 p-4 mb-3">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="text-gray-900 font-bold text-base mb-2">
                                {{ __('dashboard.order', ['number' => $unpaidOrder->order_number]) }}
                            </div>
                            <div class="text-gray-700 text-sm mb-1">
                                <span class="text-gray-600">{{ __('dashboard.amount_to_pay') }}</span> 
                                <span class="font-bold text-red-600">{{ \App\Helpers\CurrencyHelper::formatByCurrency($unpaidOrder->total, $unpaidOrder->currency) }}</span>
                            </div>
                            @if($unpaidOrder->last_payment_failure_reason)
                            <div class="text-gray-600 text-xs">
                                <span class="font-medium">{{ __('dashboard.reason') }}</span> {{ $unpaidOrder->last_payment_failure_reason }}
                            </div>
                            @endif
                        </div>
                        <form method="POST" action="{{ localizedRoute('order.pay', $unpaidOrder) }}">
                            @csrf
                            <button type="submit" class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2.5 rounded-full transition-colors">
                                {{ __('dashboard.pay_now') }}
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
                
                <div class="text-sm text-gray-600 mt-3">
                    {{ __('dashboard.secure_payment_stripe') }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Active Subscription -->
    @if($activeSubscription)
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
        <div class="bg-gray-100 p-6 border-b border-gray-200">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 mb-1">
                        @if($activeSubscriptions->count() > 1)
                            {{ __('dashboard.your_subscriptions', ['count' => $activeSubscriptions->count()]) }}
                        @else
                            {{ __('dashboard.your_subscription') }}
                        @endif
                    </h2>
                    <p class="text-gray-600 font-light">
                        {{ $activeSubscription->plan ? $activeSubscription->plan->name : __('dashboard.coffee_subscription') }}
                    </p>
                </div>
                <a href="{{ localizedRoute('dashboard.subscription') }}" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200 inline-flex items-center gap-2">
                    @if($activeSubscriptions->count() > 1)
                        {{ __('dashboard.view_all') }}
                    @else
                        {{ __('dashboard.manage') }}
                    @endif
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">{{ __('dashboard.price') }}</p>
                        @if($activeSubscription->configured_price)
                        @php
                        // configured_price now contains FULL price (without discount)
                        // If active discount, subtract it
                        // Sleva je aktivní pokud: discount_amount > 0 A (neomezená NEBO zbývají měsíce)
                        $activeDiscount = ($activeSubscription->discount_amount > 0 && ($activeSubscription->discount_months_remaining === null || $activeSubscription->discount_months_remaining > 0)) ? $activeSubscription->discount_amount : 0;
                        $currentPrice = $activeSubscription->configured_price - $activeDiscount + ($activeSubscription->shipping_cost ?? 0);
                        @endphp
                        <p class="text-xl font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::formatByCurrency($currentPrice, $activeSubscription->currency) }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 font-light">
                            {{ $activeSubscription->frequency_months == 1 ? __('dashboard.per_month') : __('dashboard.per_months', ['count' => $activeSubscription->frequency_months]) }}
                            @if(($activeSubscription->shipping_cost ?? 0) > 0)
                            <span class="text-gray-400"> {{ __('dashboard.incl_shipping', ['amount' => \App\Helpers\CurrencyHelper::formatByCurrency($activeSubscription->shipping_cost, $activeSubscription->currency)]) }}</span>
                            @endif
                        </p>
                        @endif
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">{{ __('dashboard.status') }}</p>
                        @if($activeSubscription->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                {{ __('dashboard.status_active') }}
                            </span>
                        @elseif($activeSubscription->status === 'paused')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                {{ __('dashboard.status_paused') }}
                                @if($activeSubscription->paused_until_date)
                                    <span class="ml-1 text-yellow-700">{{ __('dashboard.status_paused_until', ['date' => $activeSubscription->paused_until_date->format('d.m.Y')]) }}</span>
                                @endif
                            </span>
                        @elseif($activeSubscription->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></span>
                                {{ __('dashboard.status_pending') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($activeSubscription->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                @php
                    $pauseInfo = $shipmentInfo?->pauseInfo;
                    if ($activeSubscription->status === 'paused' && $pauseInfo?->resumeDate) {
                        $nextShipment = $pauseInfo->resumeDate;
                        $shipmentLabel = __('dashboard.shipment_after_pause');
                    } else {
                        $nextShipment = $shipmentInfo?->nextShipmentDate() ?? $activeSubscription->next_shipment_date;
                        $shipmentLabel = __('dashboard.next_shipment');
                    }
                @endphp
                @if($nextShipment)
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium mb-1">{{ $shipmentLabel }}</p>
                        <p class="text-base font-bold text-gray-900">
                            {{ $nextShipment->format('d.m.Y') }}
                        </p>
                        @if($activeSubscription->status === 'paused' && $activeSubscription->paused_until_date)
                        <p class="text-xs text-yellow-600 mt-0.5 font-medium">
                            <!-- Pauza do {{ $activeSubscription->paused_until_date->format('d.m.Y') }} -->
                        </p>
                        @else
                        <!-- <p class="text-xs text-gray-500 mt-0.5 font-light">Rozesílka probíhá vždy 20. v měsíci</p> -->
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-gray-900 font-bold text-base mb-1">{{ __('dashboard.no_active_subscription') }}</p>
                <p class="text-gray-600 font-light mb-4">
                    {{ __('dashboard.start_coffee_journey') }}
                </p>
                <a href="{{ localizedRoute('subscriptions.index') }}" class="btn btn-primary inline-flex items-center gap-2 font-medium">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('dashboard.select_subscription') }}
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900">{{ __('dashboard.recent_orders') }}</h2>
                <a href="{{ localizedRoute('dashboard.orders') }}" class="text-primary-600 hover:text-primary-700 font-medium flex items-center gap-2">
                    {{ __('dashboard.view_all_orders') }}
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            {{ __('dashboard.order_number') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            {{ __('dashboard.date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            {{ __('dashboard.status') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            {{ __('dashboard.total') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            {{ __('dashboard.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $order->order_number ?? '#' . $order->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-light">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status === 'completed')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800">
                                    {{ __('dashboard.completed') }}
                                </span>
                            @elseif($order->status === 'pending')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    {{ __('dashboard.pending') }}
                                </span>
                            @elseif($order->status === 'processing')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ __('dashboard.processing') }}
                                </span>
                            @elseif($order->status === 'paid')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-emerald-100 text-emerald-800">
                                    {{ __('dashboard.paid') }}
                                </span>
                            @elseif($order->status === 'submitted')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-indigo-100 text-indigo-800">
                                    {{ __('dashboard.submitted') }}
                                </span>
                            @elseif($order->status === 'shipped')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-purple-100 text-purple-800">
                                    {{ __('dashboard.shipped') }}
                                </span>
                            @elseif($order->status === 'delivered')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800">
                                    {{ __('dashboard.delivered') }}
                                </span>
                            @elseif($order->status === 'cancelled')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-red-100 text-red-800">
                                    {{ __('dashboard.cancelled') }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ \App\Helpers\CurrencyHelper::formatByCurrency($order->total, $order->currency, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ localizedRoute('dashboard.order.detail', $order) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                {{ __('dashboard.detail') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50">
            {{ $orders->links() }}
        </div>
        @else
        <div class="text-center py-12 px-4">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('dashboard.no_orders') }}</h3>
            <p class="text-gray-600 font-light mb-6">{{ __('dashboard.no_orders_yet') }}</p>
            <a href="{{ localizedRoute('products.index') }}" class="btn btn-primary inline-flex items-center gap-2 font-medium">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                {{ __('dashboard.browse_products') }}
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
