@extends('layouts.admin')

@section('title', 'Detail předplatného')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Předplatné #{{ $subscription->id }}</h1>
            <p class="text-gray-600 mt-1">{{ $subscription->created_at->format('d.m.Y H:i') }}</p>
        </div>
        <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            ← Zpět na seznam
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Subscription Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Detail předplatného</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                @if($subscription->plan)
                                <h3 class="text-xl font-bold text-gray-900">{{ $subscription->plan->name }}</h3>
                                <p class="text-gray-600">{{ $subscription->plan->description }}</p>
                                @else
                                <h3 class="text-xl font-bold text-gray-900">Vlastní konfigurace</h3>
                                <p class="text-gray-600">Kávové předplatné podle přání zákazníka</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($subscription->configured_price ?? $subscription->plan->price ?? 0, 0, ',', ' ') }} Kč
                                </div>
                                <div class="text-sm text-gray-600">
                                    / {{ $subscription->frequency_months == 1 ? 'měsíc' : ($subscription->frequency_months . ' měsíce') }}
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Začátek:</span>
                                    <span class="font-medium text-gray-900 ml-2">
                                        {{ $subscription->starts_at ? $subscription->starts_at->format('d.m.Y') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Další dodávka:</span>
                                    <span class="font-medium text-gray-900 ml-2">
                                        {{ $subscription->next_billing_date ? $subscription->next_billing_date->format('d.m.Y') : '-' }}
                                    </span>
                                </div>
                                @if($subscription->trial_ends_at)
                                <div>
                                    <span class="text-gray-600">Konec zkušebního období:</span>
                                    <span class="font-medium text-gray-900 ml-2">
                                        {{ \Carbon\Carbon::parse($subscription->trial_ends_at)->format('d.m.Y') }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            @if($subscription->configuration)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="font-display text-xl font-bold text-gray-900">Konfigurace předplatného</h2>
                </div>
                <div class="p-6">
                    @php
                        $config = is_string($subscription->configuration) 
                            ? json_decode($subscription->configuration, true) 
                            : $subscription->configuration;
                    @endphp

                    <div class="space-y-3">
                        @if(isset($config['amount']))
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Množství:</span>
                            <span class="font-semibold text-gray-900">{{ $config['amount'] }} balení</span>
                        </div>
                        @endif

                        @if(isset($config['cups']))
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Spotřeba:</span>
                            <span class="font-semibold text-gray-900">{{ $config['cups'] }} šálky denně</span>
                        </div>
                        @endif

                        @if(isset($config['type']))
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Typ kávy:</span>
                            <span class="font-semibold text-gray-900">
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
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Decaf varianta:</span>
                            <span class="font-semibold text-gray-900">Ano (+100 Kč)</span>
                        </div>
                        @endif

                        @if(isset($config['mix']) && $config['type'] === 'mix')
                        <div class="py-2 border-b">
                            <span class="text-gray-600 block mb-2">Rozložení mixu:</span>
                            <div class="space-y-1 ml-4">
                                @if(isset($config['mix']['espresso']) && $config['mix']['espresso'] > 0)
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    <span>{{ $config['mix']['espresso'] }}× Espresso</span>
                                </div>
                                @endif
                                @if(isset($config['mix']['filter']) && $config['mix']['filter'] > 0)
                                <div class="flex items-center">
                                    <span class="text-primary-500 mr-2">•</span>
                                    <span>{{ $config['mix']['filter'] }}× Filtr</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if(isset($config['frequencyText']))
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Frekvence:</span>
                            <span class="font-semibold text-gray-900">{{ $config['frequencyText'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Shipping & Packeta -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="font-display text-xl font-bold text-gray-900">Dodací údaje</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Billing Address -->
                        @if($subscription->shipping_address)
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Fakturační adresa</h3>
                            @php
                                $address = is_string($subscription->shipping_address) 
                                    ? json_decode($subscription->shipping_address, true) 
                                    : $subscription->shipping_address;
                            @endphp
                            <div class="text-sm text-gray-700 space-y-1">
                                <p class="font-medium">{{ $address['name'] ?? '' }}</p>
                                @if(isset($address['billing_address']))
                                <p>{{ $address['billing_address'] }}</p>
                                <p>{{ $address['billing_postal_code'] }} {{ $address['billing_city'] }}</p>
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

                        <!-- Packeta -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-2">Výdejní místo Zásilkovna</h3>
                            @if($subscription->packeta_point_id)
                            <div class="text-sm text-gray-700">
                                <p class="font-medium">{{ $subscription->packeta_point_name }}</p>
                                <p class="text-gray-600 mt-1">{{ $subscription->packeta_point_address }}</p>
                                <p class="text-xs text-gray-500 mt-1">ID: {{ $subscription->packeta_point_id }}</p>
                            </div>
                            @else
                            <p class="text-sm text-gray-600">Není nastaveno</p>
                            @endif
                        </div>
                    </div>

                    @if($subscription->payment_method)
                    <div class="mt-4 pt-4 border-t">
                        <h3 class="font-semibold text-gray-900 mb-2">Platební metoda</h3>
                        <p class="text-sm text-gray-700">
                            @if($subscription->payment_method === 'card')
                                Platební karta
                            @elseif($subscription->payment_method === 'transfer')
                                Bankovní převod
                            @else
                                {{ $subscription->payment_method }}
                            @endif
                        </p>
                    </div>
                    @endif

                    @if($subscription->delivery_notes)
                    <div class="mt-4 pt-4 border-t">
                        <h3 class="font-semibold text-gray-900 mb-2">Poznámka k dodání</h3>
                        <p class="text-sm text-gray-700">{{ $subscription->delivery_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-display text-xl font-bold text-gray-900 mb-4">Správa stavu</h3>
                
                <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stav předplatného</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
                            <option value="pending" {{ $subscription->status === 'pending' ? 'selected' : '' }}>Čeká</option>
                            <option value="active" {{ $subscription->status === 'active' ? 'selected' : '' }}>Aktivní</option>
                            <option value="trialing" {{ $subscription->status === 'trialing' ? 'selected' : '' }}>Zkušební období</option>
                            <option value="past_due" {{ $subscription->status === 'past_due' ? 'selected' : '' }}>Po splatnosti</option>
                            <option value="canceled" {{ $subscription->status === 'canceled' ? 'selected' : '' }}>Zrušeno</option>
                        </select>
                    </div>
                </form>
                
                <div class="pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600 mb-2">Aktuální stav:</div>
                        @if($subscription->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktivní</span>
                            @elseif($subscription->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Čeká</span>
                            @elseif($subscription->status === 'trialing')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Zkušební</span>
                            @elseif($subscription->status === 'past_due')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Po splatnosti</span>
                            @elseif($subscription->status === 'canceled')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Zrušeno</span>
                            @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-display text-xl font-bold text-gray-900 mb-4">Informace o zákazníkovi</h3>
                <div class="space-y-3 text-sm">
                    @if($subscription->user)
                    <div>
                        <div class="text-gray-600">Jméno:</div>
                        <div class="font-medium text-gray-900">{{ $subscription->user->name }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600">Email:</div>
                        <div class="font-medium text-gray-900">{{ $subscription->user->email }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600">Registrován:</div>
                        <div class="font-medium text-gray-900">{{ $subscription->user->created_at->format('d.m.Y') }}</div>
                    </div>
                    <div class="pt-3 border-t">
                        <a href="{{ route('admin.dashboard') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            Zobrazit všechny objednávky zákazníka →
                        </a>
                    </div>
                    @else
                    <div class="text-gray-600 italic">
                        Předplatné vytvořeno bez registrace
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stripe Info -->
            @if($subscription->stripe_subscription_id)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-display text-xl font-bold text-gray-900 mb-4">Stripe informace</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <div class="text-gray-600">Subscription ID:</div>
                        <div class="font-mono text-xs text-gray-900 break-all">{{ $subscription->stripe_subscription_id }}</div>
                    </div>
                    @if($subscription->stripe_customer_id)
                    <div>
                        <div class="text-gray-600">Customer ID:</div>
                        <div class="font-mono text-xs text-gray-900 break-all">{{ $subscription->stripe_customer_id }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Cancel Subscription -->
            @if($subscription->status !== 'canceled')
            <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" onsubmit="return confirm('Opravdu chcete zrušit toto předplatné?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn w-full bg-red-600 text-white hover:bg-red-700">
                    Zrušit předplatné
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection


