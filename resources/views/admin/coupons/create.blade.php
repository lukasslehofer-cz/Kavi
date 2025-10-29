@extends('layouts.admin')

@section('title', 'Nový kupón - Admin Panel')

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
        <h1 class="text-3xl font-bold text-gray-900">Nový slevový kupón</h1>
        <p class="text-gray-600 mt-1">Vytvořte nový kupón pro objednávky nebo předplatné</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        @include('admin.coupons._form', ['coupon' => new \App\Models\Coupon()])
    </form>
</div>
@endsection

