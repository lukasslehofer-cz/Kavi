@extends('layouts.app')

@section('title', __('subscriptions.pay_skipped.page_title'))

@section('content')
@php
    // status: success | cancelled | expired | done | error
    $details = $details ?? null;
    $nextBilling = $nextBilling ?? null;
    $contactEmail = \App\Services\EmailService::getContactEmail(app()->getLocale());
    $processing = $status === 'success' && $details && ! $details['paid'];
    $dotClass = match ($status) {
        'success' => $processing ? 'bg-yellow-500' : 'bg-green-500',
        'cancelled', 'done' => 'bg-yellow-500',
        default => 'bg-red-500',
    };
    $badgeClass = match ($status) {
        'success' => $processing ? 'text-yellow-700' : 'text-green-600',
        'cancelled', 'done' => 'text-yellow-700',
        default => 'text-red-600',
    };
@endphp

<div style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    <div class="flex items-center gap-3 mb-6">
      <span class="w-3 h-3 rounded-full {{ $dotClass }}"></span>
      <span class="text-xs uppercase tracking-widest {{ $badgeClass }}">{{ __('subscriptions.pay_skipped.'.$status.'_badge') }}</span>
    </div>

    <h1 class="font-display text-4xl sm:text-5xl md:text-6xl font-normal leading-[0.95] tracking-tight uppercase mb-8 text-dark-800">
      {{ __('subscriptions.pay_skipped.'.$status.'_heading') }}
    </h1>

    <p class="text-base text-dark-800 max-w-xl leading-relaxed">
      {{ $processing
          ? __('subscriptions.pay_skipped.processing_text')
          : __('subscriptions.pay_skipped.'.$status.'_text', ['email' => $contactEmail]) }}
    </p>
  </div>
</div>

<div class="pt-4 pb-16 lg:pb-24" style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8">
    @if($details)
    @php
        $subscription = $details['subscription'];
        $summaryRows = \App\Helpers\SubscriptionSummary::rows($subscription);
    @endphp
    <div class="max-w-3xl border-t-2 border-primary-500 pt-6 mb-12">
      <div class="flex items-baseline gap-4 mb-8">
        <span class="text-primary-500 font-display text-2xl sm:text-3xl font-normal">01</span>
        <h2 class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight">{{ __('subscriptions.pay_skipped.details_title') }}</h2>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
          <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">{{ __('subscriptions.pay_skipped.subscription_number') }}</span>
          <span class="font-display text-lg text-dark-800 uppercase tracking-tight">{{ $subscription->subscription_number }}</span>
        </div>
        @foreach($summaryRows as $row)
        <div>
          <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">{{ $row['label'] }}</span>
          <span class="font-display text-lg text-dark-800 uppercase tracking-tight">{{ $row['value'] }}</span>
        </div>
        @endforeach
        <div>
          <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">{{ __('subscriptions.pay_skipped.shipment') }}</span>
          <span class="font-display text-lg text-dark-800 uppercase tracking-tight">{{ \App\Helpers\DateFormat::full($details['shipment']->shipment_date) }}</span>
        </div>
        @if($details['amount'])
        <div>
          <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">{{ __('subscriptions.pay_skipped.paid') }}</span>
          <span class="font-display text-lg text-dark-800 uppercase tracking-tight">{{ $details['amount'] }}</span>
        </div>
        @endif
        @if($details['card'])
        <div>
          <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">{{ __('subscriptions.pay_skipped.card') }}</span>
          <span class="font-display text-lg text-dark-800 uppercase tracking-tight">{{ $details['card'] }}</span>
        </div>
        @endif
        @if($nextBilling)
        <div>
          <span class="text-xs uppercase tracking-widest text-warm-400 block mb-2">{{ __('subscriptions.pay_skipped.next_payment') }}</span>
          <span class="font-display text-lg text-dark-800 uppercase tracking-tight">{{ \App\Helpers\DateFormat::full($nextBilling) }}</span>
        </div>
        @endif
      </div>
    </div>
    @endif

    <a href="{{ localizedRoute('dashboard.subscription') }}"
       class="inline-block bg-primary-500 hover:bg-primary-600 text-white px-7 py-3.5 text-sm uppercase tracking-widest transition-colors">
      {{ __('subscriptions.pay_skipped.account_link') }} &rarr;
    </a>
  </div>
</div>
@endsection
