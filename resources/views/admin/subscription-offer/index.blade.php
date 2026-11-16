@extends('layouts.admin')

@section('title', 'Nabídka předplatného')

@section('content')
@php
    $offered = $stats['offered'];
    $baseline = $stats['baseline'];
    $days = $stats['days'];
    $percent = fn ($rate) => $rate !== null ? number_format($rate, 1, ',', ' ').' %' : '—';
@endphp
<div class="p-6">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Nabídka předplatného</h1>
        <p class="text-gray-600 mt-1">
            E-mail o doručení objednávky z e-shopu nabízí kupcům bez předplatného kávový box.
            Tady je vidět, kolik z nich si do {{ $days }} dnů předplatné založilo.
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg p-5 text-center shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-900">{{ $offered['buyers'] }}</p>
            <p class="text-xs text-gray-600 mt-1">Kupců s nabídkou</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg p-5 text-center shadow-sm border border-emerald-200">
            <p class="text-3xl font-bold text-emerald-700">{{ $percent($offered['rate']) }}</p>
            <p class="text-xs text-emerald-600 mt-1">Přešlo do {{ $days }} dnů ({{ $offered['closed_converted'] }} z {{ $offered['closed'] }})</p>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-5 text-center shadow-sm border border-amber-200">
            <p class="text-3xl font-bold text-amber-700">{{ $offered['open'] }}</p>
            <p class="text-xs text-amber-600 mt-1">Lhůta ještě běží (přešlo {{ $offered['open_converted'] }})</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-5 text-center shadow-sm border border-blue-200">
            <p class="text-3xl font-bold text-blue-700">{{ $offered['closed_converted'] + $offered['open_converted'] }}</p>
            <p class="text-xs text-blue-600 mt-1">Přešlo celkem</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-5 text-center shadow-sm border border-purple-200">
            <p class="text-3xl font-bold text-purple-700">{{ $percent($baseline['rate']) }}</p>
            <p class="text-xs text-purple-600 mt-1">Bez nabídky ({{ $baseline['converted'] }} z {{ $baseline['buyers'] }})</p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Jak se počítá</h2>
        <p class="text-sm text-gray-600">
            Kupec se počítá jednou, od první nabídky. Přechod je pravidelné placené předplatné (ne dárkové ani bezplatné)
            založené do {{ $days }} dnů. Procento se bere jen z kupců, kterým lhůta už uběhla.
            „Bez nabídky“ je stejný výpočet pro objednávky doručené před zavedením nabídky, u kupců, kteří tehdy předplatné neměli.
        </p>
    </div>

    <!-- Po měsících -->
    @if($stats['months']->isNotEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Po měsících odeslání nabídky</h2>
        </div>
        @foreach($stats['months'] as $month)
            <div class="px-6 py-4 flex flex-wrap items-center justify-between gap-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div class="min-w-[220px]">
                    <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $month['month'])->format('m/Y') }}</p>
                    <p class="text-xs text-gray-500">Kupců s nabídkou: {{ $month['buyers'] }} &middot; přešlo: {{ $month['converted'] }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-700">{{ $percent($month['rate']) }}</span>
                    @if($month['open'])
                        <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded">Lhůta běží</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Poslední nabídky -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Poslední nabídky</h2>
        </div>

        @forelse($stats['recent'] as $row)
            <div class="px-6 py-4 flex flex-wrap items-center justify-between gap-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div class="min-w-[220px]">
                    <p class="text-sm font-medium text-gray-900">{{ $row['email'] ?? '—' }}</p>
                    <p class="text-xs text-gray-500">
                        <a href="{{ route('admin.orders.show', $row['order']) }}" class="text-blue-600 hover:text-blue-800 font-medium">{{ $row['order']->order_number }}</a>
                        &middot; nabídka {{ $row['offered_at']->format('d.m.Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if($row['subscription'])
                        <a href="{{ route('admin.subscriptions.show', $row['subscription']) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">{{ $row['subscription']->subscription_number }}</a>
                        <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded">Přešel, dnů od nabídky: {{ $row['days_to_convert'] }}</span>
                    @elseif($row['open'])
                        <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded">Lhůta běží do {{ $row['offered_at']->copy()->addDays($days)->format('d.m.Y') }}</span>
                    @else
                        <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded">Nepřešel</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <p class="text-gray-900 font-medium mb-1">Zatím žádná nabídka neodešla</p>
                <p class="text-sm text-gray-500">
                    Zapíše se s prvním e-mailem o doručení objednávky kupci, který nemá předplatné.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
