@extends('layouts.dashboard')

@section('title', 'Nástěnka - Kavi Coffee')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-lg p-8 border-l-4 border-primary-500">
        <h1 class="text-4xl font-bold text-dark-800 mb-2">Vítejte zpět!</h1>
        <p class="text-lg text-dark-600">Jsme rádi, že vás opět vidíme, <span class="text-primary-600 font-semibold">{{ auth()->user()->name }}</span></p>
    </div>

    <!-- Active Subscription -->
    @if($activeSubscription)
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-l-4 border-primary-500">
        <div class="bg-gradient-to-r from-primary-50 to-pink-50 p-6 border-b border-primary-100">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-dark-800 mb-2">
                        @if($activeSubscriptions->count() > 1)
                            Vaše aktivní předplatná ({{ $activeSubscriptions->count() }})
                        @else
                            Vaše aktivní předplatné
                        @endif
                    </h2>
                    <p class="text-dark-600 text-lg">
                        {{ $activeSubscription->plan ? $activeSubscription->plan->name : 'Kávové předplatné' }}
                    </p>
                </div>
                <a href="{{ route('dashboard.subscription') }}" class="btn btn-primary font-semibold shadow-lg">
                    @if($activeSubscriptions->count() > 1)
                        Zobrazit všechna
                    @else
                        Spravovat
                    @endif
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-dark-600 font-semibold mb-1">Cena</p>
                        @if($activeSubscription->configured_price)
                        <p class="text-2xl font-bold text-dark-800">{{ number_format($activeSubscription->configured_price, 0, ',', ' ') }} Kč</p>
                        <p class="text-xs text-dark-500 mt-1">/ {{ $activeSubscription->frequency_months == 1 ? 'měsíc' : ($activeSubscription->frequency_months . ' měsíce') }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-dark-600 font-semibold mb-1">Stav</p>
                        @if($activeSubscription->status === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Aktivní
                            </span>
                        @elseif($activeSubscription->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                Čeká na aktivaci
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">
                                {{ ucfirst($activeSubscription->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                @if($activeSubscription->next_billing_date || $activeSubscription->current_period_end)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-dark-600 font-semibold mb-1">Další dodávka</p>
                        <p class="text-lg font-bold text-dark-800">
                            {{ ($activeSubscription->next_billing_date ?? \Carbon\Carbon::parse($activeSubscription->current_period_end))->format('d.m.Y') }}
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-400">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
                <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-dark-800 font-bold text-lg mb-1">Nemáte aktivní předplatné</p>
                <p class="text-dark-600 mb-4">
                    Začněte svou kávovou cestu s námi a objevte ty nejlepší kávy z prémiových pražíren.
                </p>
                <a href="{{ route('subscriptions.index') }}" class="btn btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Vybrat předplatné
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-bluegray-50 p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-dark-800">Poslední objednávky</h2>
                <a href="{{ route('dashboard.orders') }}" class="text-primary-600 hover:text-primary-700 font-semibold flex items-center gap-2">
                    Zobrazit všechny
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-bluegray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Číslo objednávky
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Datum
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Stav
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Celkem
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-dark-700 uppercase tracking-wider">
                            Akce
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr class="hover:bg-bluegray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-dark-800">
                            #{{ $order->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-dark-600">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status === 'completed')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">
                                    Dokončeno
                                </span>
                            @elseif($order->status === 'pending')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800">
                                    Čeká
                                </span>
                            @elseif($order->status === 'processing')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800">
                                    Zpracovává se
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-dark-800 font-bold">
                            {{ number_format($order->total, 2, ',', ' ') }} Kč
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('dashboard.order.detail', $order) }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-bluegray-50">
            {{ $orders->links() }}
        </div>
        @else
        <div class="text-center py-12 px-4">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-bluegray-100 flex items-center justify-center">
                <svg class="h-8 w-8 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-dark-800 mb-2">Žádné objednávky</h3>
            <p class="text-dark-600 mb-6">Zatím jste neprovedli žádnou objednávku.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Prohlédnout produkty
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
