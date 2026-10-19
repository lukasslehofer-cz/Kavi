@extends('layouts.app')

@section('title', __('subscriptions.pay_skipped.page_title'))

@section('content')
@php
    // status: success | cancelled | expired | done | error
    $contactEmail = \App\Services\EmailService::getContactEmail(app()->getLocale());
    $dotClass = match ($status) {
        'success' => 'bg-green-500',
        'cancelled', 'done' => 'bg-yellow-500',
        default => 'bg-red-500',
    };
    $badgeClass = match ($status) {
        'success' => 'text-green-600',
        'cancelled', 'done' => 'text-yellow-700',
        default => 'text-red-600',
    };
@endphp

<div style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-16 lg:pb-24">
    <div class="flex items-center gap-3 mb-6">
      <span class="w-3 h-3 rounded-full {{ $dotClass }}"></span>
      <span class="text-xs uppercase tracking-widest {{ $badgeClass }}">{{ __('subscriptions.pay_skipped.'.$status.'_badge') }}</span>
    </div>

    <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-normal leading-[0.95] tracking-tight uppercase mb-8 text-dark-800">
      {{ __('subscriptions.pay_skipped.'.$status.'_heading') }}
    </h1>

    <p class="text-base text-dark-800 max-w-xl leading-relaxed mb-10">
      {{ __('subscriptions.pay_skipped.'.$status.'_text', ['email' => $contactEmail]) }}
    </p>

    <a href="{{ localizedRoute('dashboard.subscription') }}"
       class="inline-block bg-primary-500 hover:bg-primary-600 text-white px-7 py-3.5 text-sm uppercase tracking-widest transition-colors">
      {{ __('subscriptions.pay_skipped.account_link') }} &rarr;
    </a>
  </div>
</div>
@endsection
