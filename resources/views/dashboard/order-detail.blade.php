@extends('layouts.dashboard')

@section('title', __('dashboard.title_order_detail'))

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <a href="{{ localizedRoute('dashboard.orders') }}" class="text-primary-600 hover:text-primary-700 font-medium mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('dashboard.back_to_orders') }}
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2">{{ __('dashboard.order_detail', ['number' => $order->order_number ?? '#' . $order->id]) }}</h1>
        <p class="mt-2 text-gray-600 font-light">{{ __('dashboard.created_at', ['date' => $order->created_at->format('d.m.Y H:i')]) }}</p>
    </div>

    <!-- Order Status -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4">{{ __('dashboard.order_status') }}</h2>
        <div class="flex items-center">
            @if($order->status === 'completed')
                <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-green-100 text-green-800 border border-green-200">
                    {{ __('dashboard.status_completed_icon') }}
                </span>
            @elseif($order->status === 'pending')
                <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                    {{ __('dashboard.status_pending_icon') }}
                </span>
            @elseif($order->status === 'processing')
                <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                    {{ __('dashboard.status_processing_icon') }}
                </span>
            @elseif($order->status === 'cancelled')
                <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-red-100 text-red-800 border border-red-200">
                    {{ __('dashboard.status_cancelled_icon') }}
                </span>
            @else
                <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                    {{ ucfirst($order->status) }}
                </span>
            @endif
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-base font-bold text-gray-900">{{ __('dashboard.order_items') }}</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($order->items as $item)
            <div class="p-6 flex justify-between items-center hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-4 flex-1">
                    @if($item->product_image)
                    <img src="{{ asset($item->product_image) }}" alt="{{ $item->product_name }}" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                    @else
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                    <div>
                        <h3 class="text-base font-bold text-gray-900">{{ $item->product_name }}</h3>
                        @if($item->product?->roastery)
                        <p class="text-sm text-gray-500">{{ $item->product->roastery->name }}</p>
                        @endif
                        @if($item->product?->category && count($item->product->category) > 0)
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($item->product->category as $category)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                @if($category === 'espresso') bg-amber-100 text-amber-800
                                @elseif($category === 'filter') bg-blue-100 text-blue-800
                                @elseif($category === 'decaf') bg-green-100 text-green-800
                                @elseif($category === 'accessories') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($category) }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                        <p class="text-sm text-gray-600 font-light mt-1">
                            {{ __('dashboard.quantity') }} {{ $item->quantity }} × {{ \App\Helpers\CurrencyHelper::formatByCurrency($item->price, $order->currency, 2) }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-base font-bold text-primary-600">
                        {{ \App\Helpers\CurrencyHelper::formatByCurrency($item->price * $item->quantity, $order->currency, 2) }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-base font-bold text-gray-900">{{ __('dashboard.order_summary') }}</h2>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <div class="flex justify-between text-gray-600 font-light">
                    <span>{{ __('dashboard.subtotal') }}</span>
                    <span class="font-medium">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->subtotal, $order->currency, 2) }}</span>
                </div>
                @if($order->tax > 0)
                <div class="flex justify-between text-gray-600 font-light">
                    <span>{{ __('dashboard.vat') }}</span>
                    <span class="font-medium">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->tax, $order->currency, 2) }}</span>
                </div>
                @endif
                @if($order->shipping > 0)
                <div class="flex justify-between text-gray-600 font-light">
                    <span>{{ __('dashboard.shipping') }}</span>
                    <span class="font-medium">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->shipping, $order->currency, 2) }}</span>
                </div>
                @endif
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-green-600 font-light">
                    <span>{{ __('dashboard.discount') }}{{ $order->coupon_code ? ' (' . $order->coupon_code . ')' : '' }}:</span>
                    <span class="font-medium">-{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->discount_amount, $order->currency, 2) }}</span>
                </div>
                @endif
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex justify-between text-xl font-bold">
                        <span class="text-gray-900">{{ __('dashboard.total') }}:</span>
                        <span class="text-primary-600">{{ \App\Helpers\CurrencyHelper::formatByCurrency($order->total, $order->currency, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($order->stripe_payment_intent_id)
    <div class="bg-blue-50 border border-blue-200 bg-blue-50 rounded-xl p-4">
        <p class="text-sm text-gray-900">
            <span class="font-bold">{{ __('dashboard.payment_id') }}</span> {{ $order->stripe_payment_intent_id }}
        </p>
    </div>
    @endif

    @if($order->invoice_pdf_path)
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4">{{ __('dashboard.invoice') }}</h2>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ __('dashboard.tax_document') }}</p>
                    <p class="text-xs text-gray-600 font-light">{{ __('dashboard.order', ['number' => $order->order_number]) }}</p>
                </div>
            </div>
            <a href="{{ localizedRoute('dashboard.order.invoice', $order) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                {{ __('dashboard.download_invoice') }}
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
