@extends('layouts.admin')

@section('title', 'Žádosti o hodnocení')

@section('content')
<div class="p-6">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Žádosti o hodnocení</h1>
        <p class="text-gray-600 mt-1">Recenze žijí na Googlu. Tady je vidět, komu jsme napsali a jak zareagoval.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg p-5 text-center shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['sent'] }}</p>
            <p class="text-xs text-gray-600 mt-1">Odeslaných žádostí</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-5 text-center shadow-sm border border-blue-200">
            <p class="text-3xl font-bold text-blue-700">{{ $stats['click_rate'] }}%</p>
            <p class="text-xs text-blue-600 mt-1">Prokliků ({{ $stats['clicked'] }})</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg p-5 text-center shadow-sm border border-emerald-200">
            <p class="text-3xl font-bold text-emerald-700">{{ $stats['rated'] }}</p>
            <p class="text-xs text-emerald-600 mt-1">S hvězdičkou</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-5 text-center shadow-sm border border-purple-200">
            <p class="text-3xl font-bold text-purple-700">{{ $stats['average'] ?? '—' }}</p>
            <p class="text-xs text-purple-600 mt-1">Průměr z e-mailů</p>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-5 text-center shadow-sm border border-amber-200">
            <p class="text-3xl font-bold text-amber-700">{{ $stats['reminders'] }}</p>
            <p class="text-xs text-amber-600 mt-1">Připomínek</p>
        </div>
    </div>

    <!-- Rozložení hvězdiček -->
    @if($stats['rated'] > 0)
    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Rozložení hvězdiček</h2>
        @for($i = 5; $i >= 1; $i--)
            @php $count = $distribution[$i] ?? 0; @endphp
            <div class="flex items-center gap-3 mb-2">
                <span class="w-16 text-sm text-gray-600">{{ $i }} {{ $i === 1 ? 'hvězda' : ($i < 5 ? 'hvězdy' : 'hvězd') }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-amber-400 h-full" style="width: {{ $stats['rated'] > 0 ? round($count / $stats['rated'] * 100) : 0 }}%"></div>
                </div>
                <span class="w-10 text-sm text-gray-700 text-right">{{ $count }}</span>
            </div>
        @endfor
    </div>
    @endif

    <!-- Poslední žádosti -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Poslední žádosti</h2>
        </div>

        @forelse($recent as $request)
            <div class="px-6 py-4 flex flex-wrap items-center justify-between gap-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div class="min-w-[220px]">
                    <p class="text-sm font-medium text-gray-900">{{ $request->email ?? $request->user?->email ?? '—' }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $request->review_type === 'order' ? 'Objednávka' : 'Předplatné' }}
                        &middot; {{ $request->milestone }}. doručení
                        &middot; {{ $request->email_sent_at?->format('d.m.Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if($request->rating)
                        <span class="text-amber-500 text-sm">{{ str_repeat('★', $request->rating) }}<span class="text-gray-300">{{ str_repeat('★', 5 - $request->rating) }}</span></span>
                    @endif
                    @if($request->clicked_at)
                        <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded">Kliknuto</span>
                    @elseif($request->reminded_at)
                        <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded">Připomenuto</span>
                    @else
                        <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded">Bez reakce</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <p class="text-gray-900 font-medium mb-1">Zatím žádné žádosti</p>
                <p class="text-sm text-gray-500">
                    Rozešle je <code class="px-1.5 py-0.5 bg-gray-100 rounded">reviews:send</code>,
                    až bude <code class="px-1.5 py-0.5 bg-gray-100 rounded">REVIEWS_ENABLED=true</code>.
                </p>
            </div>
        @endforelse
    </div>

</div>
@endsection
