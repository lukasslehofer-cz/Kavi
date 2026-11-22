@extends('layouts.admin')

@section('title', 'Firemní dárky - Admin Panel')

@php
    use App\Models\CorporateInquiry;
    $statuses = CorporateInquiry::STATUSES;
    $total = $counts->sum();
@endphp

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dárky</h1>
        <p class="text-gray-600 mt-1">Poptávky ze stránky Pro firmy. U každé nastavíte stav a doplníte detaily dealu.</p>
    </div>

    @include('admin.gifts._tabs')

    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 mb-6">
        @foreach($statuses as $value => $label)
        @php $active = $status === $value; @endphp
        <a href="{{ route('admin.gifts.corporate.index', array_filter(['status' => $active ? null : $value, 'q' => $search ?: null])) }}"
           class="block bg-white rounded-xl border p-4 transition-colors {{ $active ? 'border-gray-900 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-400' }}">
            <p class="text-2xl font-bold {{ $value === 'new' && ($counts[$value] ?? 0) ? 'text-red-600' : 'text-gray-900' }}">{{ $counts[$value] ?? 0 }}</p>
            <p class="text-sm text-gray-600">{{ $label }}</p>
        </a>
        @endforeach
    </div>

    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <input type="search" name="q" value="{{ $search }}" placeholder="Firma, jméno, e-mail, telefon, faktura, #číslo" class="flex-1 min-w-[240px] px-4 py-2 border border-gray-300 rounded-lg">
        <select name="status" class="pl-4 pr-10 py-2 border border-gray-300 rounded-lg">
            <option value="">Všechny stavy ({{ $total }})</option>
            @foreach($statuses as $value => $label)
            <option value="{{ $value }}" @selected($status === $value)>{{ $label }} ({{ $counts[$value] ?? 0 }})</option>
            @endforeach
        </select>
        <button class="px-4 py-2 bg-gray-900 text-white rounded-lg">Hledat</button>
        @if($search !== '' || $status)
        <a href="{{ route('admin.gifts.corporate.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 self-center">Zrušit filtr</a>
        @endif
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Přijato</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Firma</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontakt</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kusy</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Termín</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Celkem bez DPH</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stav</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($inquiries as $inquiry)
                    @php $deadline = $inquiry->agreed_delivery_date ?? $inquiry->delivery_date; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $inquiry->created_at->format('d.m.Y') }}
                            <span class="block text-xs text-gray-500">{{ $inquiry->created_at->format('H:i') }} · #{{ $inquiry->id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.gifts.corporate.show', $inquiry) }}" class="font-medium text-blue-600 hover:underline">{{ $inquiry->company }}</a>
                            <span class="block text-gray-500">{{ $inquiry->name }} · {{ $inquiry->audienceLabel() }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="mailto:{{ $inquiry->email }}" class="text-gray-900 hover:underline">{{ $inquiry->email }}</a>
                            @if($inquiry->phone)<span class="block text-gray-500">{{ $inquiry->phone }}</span>@endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($inquiry->box_count)
                            <strong>{{ $inquiry->box_count }}</strong>
                            <span class="block text-xs text-gray-500">poptávka {{ $inquiry->quantityLabel() }}</span>
                            @else
                            {{ $inquiry->quantityLabel() }}
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($deadline)
                            {{ $deadline->format('d.m.Y') }}
                            <span class="block text-xs text-gray-500">{{ $inquiry->agreed_delivery_date ? 'dohodnuto' : 'přání firmy' }}</span>
                            @else
                            –
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            {{ CorporateInquiry::formatCzk($inquiry->total_price) }}
                            @if($inquiry->paid_at)<span class="block text-xs text-green-700">zaplaceno</span>@elseif($inquiry->invoice_number)<span class="block text-xs text-gray-500">fa {{ $inquiry->invoice_number }}</span>@endif
                        </td>
                        <td class="px-4 py-3">
                            <x-admin.corporate-status :status="$inquiry->status" />
                            @if($inquiry->status_changed_at)<span class="block text-xs text-gray-500 mt-1">od {{ $inquiry->status_changed_at->format('d.m.') }}</span>@endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">{{ $search !== '' || $status ? 'Filtru nic neodpovídá.' : 'Zatím žádné poptávky.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $inquiries->links() }}</div>
</div>
@endsection
