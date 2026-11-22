@props(['status'])
@php
    // Barvy jsou tady, ne v modelu: Tailwind prochází jen resources/**
    $class = [
        'new' => 'bg-red-100 text-red-800',
        'communication' => 'bg-blue-100 text-blue-800',
        'offer_sent' => 'bg-purple-100 text-purple-800',
        'realization' => 'bg-yellow-100 text-yellow-800',
        'done' => 'bg-green-100 text-green-800',
        'lost' => 'bg-gray-100 text-gray-600',
    ][$status] ?? 'bg-gray-100 text-gray-600';
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap ' . $class]) }}>{{ \App\Models\CorporateInquiry::STATUSES[$status] ?? $status }}</span>
