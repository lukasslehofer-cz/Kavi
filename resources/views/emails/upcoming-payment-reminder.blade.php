{{--
    Upomínka tři dny před stržením platby (App\Mail\UpcomingPaymentReminder).
    Pořadí: co přijde v boxu, platba, přeskočení a změna.
--}}
@extends('emails.layouts.kavi')

{{-- Nadpis "Co přijde v boxu" jen když pod ním obsah opravdu je --}}
@section('title', $boxSource
    ? __('emails.upcoming_payment.title', ['month' => $monthLocative], $locale)
    : __('emails.upcoming_payment.title_generic', [], $locale))

@section('subtitle', $shipmentDate
    ? __('emails.upcoming_payment.subtitle', ['date' => $shipmentDate], $locale)
    : __('emails.upcoming_payment.subtitle_generic', [], $locale))

@section('content')
@include('emails.partials.upcoming-box', ['pendingText' => null])

@include('emails.partials.upcoming-box-actions', [
    'paymentNote' => null,
    'pauseLabel' => __('emails.upcoming_payment.pause', [], $locale),
    'deadlineText' => $deadline ? __('emails.upcoming_payment.deadline', ['date' => $deadline], $locale) : null,
])
@endsection
