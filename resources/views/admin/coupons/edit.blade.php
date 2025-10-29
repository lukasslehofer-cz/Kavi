@extends('layouts.admin')

@section('title', 'Upravit kupón - Admin Panel')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Zpět na kupóny
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Upravit kupón</h1>
        <p class="text-gray-600 mt-1">Kód: <span class="font-mono font-bold text-blue-600">{{ $coupon->code }}</span></p>
        @if($coupon->usages_count > 0)
        <p class="text-sm text-orange-600 mt-2">⚠️ Tento kupón byl již {{ $coupon->usages_count }}× použit. Změny mohou ovlivnit aktivní objednávky.</p>
        @endif
    </div>

    <!-- Form -->
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.coupons._form')
    </form>
</div>
@endsection

