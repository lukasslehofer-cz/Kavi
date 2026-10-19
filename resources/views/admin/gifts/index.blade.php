@extends('layouts.admin')

@section('title', 'Dárky - Admin Panel')

@php
    $statusLabels = [
        'pending' => ['Čeká na platbu', 'bg-gray-100 text-gray-700'],
        'expired' => ['Nezaplaceno', 'bg-gray-100 text-gray-500'],
        'issuing' => ['Vystavuje se', 'bg-yellow-100 text-yellow-800'],
        'issued' => ['Vystaveno', 'bg-blue-100 text-blue-800'],
        'redeemed' => ['Uplatněno', 'bg-green-100 text-green-800'],
        'cancelled' => ['Zneplatněno', 'bg-red-100 text-red-800'],
    ];
@endphp

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dárky</h1>
        <p class="text-gray-600 mt-1">Prodané dárkové předplatné a poukazy. Kód dohledáte podle jména, e-mailu nebo čísla objednávky.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4"><p class="text-2xl font-bold">{{ $stats['issued'] }}</p><p class="text-sm text-gray-600">Vystaveno, neuplatněno</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-4"><p class="text-2xl font-bold">{{ $stats['redeemed'] }}</p><p class="text-sm text-gray-600">Uplatněno</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-4"><p class="text-2xl font-bold">{{ $stats['pending'] }}</p><p class="text-sm text-gray-600">Čeká na platbu / vystavení</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-4"><p class="text-2xl font-bold {{ $stats['failed_delivery'] ? 'text-red-600' : '' }}">{{ $stats['failed_delivery'] }}</p><p class="text-sm text-gray-600">Chyba doručení</p></div>
    </div>

    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <input type="search" name="q" value="{{ $search }}" placeholder="Kód, jméno, e-mail, objednávka" class="flex-1 min-w-[240px] px-4 py-2 border border-gray-300 rounded-lg">
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Všechny stavy</option>
            @foreach($statusLabels as $value => [$label])
            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Předplatné i poukazy</option>
            <option value="subscription" @selected(request('type') === 'subscription')>Dárkové předplatné</option>
            <option value="voucher" @selected(request('type') === 'voucher')>Poukaz</option>
        </select>
        <button class="px-4 py-2 bg-gray-900 text-white rounded-lg">Hledat</button>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kód</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dárek</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Od / Pro</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kupující</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Objednávka</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stav</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Platí do</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($gifts as $gift)
                    @php [$label, $class] = $statusLabels[$gift->status] ?? [$gift->status, 'bg-gray-100']; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono"><a href="{{ route('admin.gifts.show', $gift) }}" class="text-blue-600 hover:underline">{{ $gift->code ?? '#' . $gift->id }}</a></td>
                        <td class="px-4 py-3">{{ $gift->itemName('cs') }}</td>
                        <td class="px-4 py-3">{{ $gift->giver_name }} &rarr; {{ $gift->recipient_name }}</td>
                        <td class="px-4 py-3">{{ $gift->buyer_name }}<br><span class="text-gray-500">{{ $gift->buyer_email }}</span></td>
                        <td class="px-4 py-3">@if($gift->order)<a href="{{ route('admin.orders.show', $gift->order) }}" class="text-blue-600 hover:underline">{{ $gift->order->order_number }}</a>@endif</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $class }}">{{ $label }}</span>
                            @if($gift->last_delivery_error)<span class="block text-xs text-red-600 mt-1">chyba doručení</span>@endif
                        </td>
                        <td class="px-4 py-3">{{ $gift->valid_until?->format('d.m.Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Zatím žádné dárky.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $gifts->links() }}</div>
</div>
@endsection
