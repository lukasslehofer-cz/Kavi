{{--
    Připomínka tři dny před koncem pauzy (App\Mail\PauseEndingReminder).
    Pořadí: co přijde v prvním boxu po pauze, kdy se za něj platí, prodloužení a změna.
--}}
@extends('emails.layouts.kavi')

@section('title', $monthAdjective
    ? __('emails.pause_ending_reminder.title', ['month' => $monthAdjective], $locale)
    : __('emails.pause_ending_reminder.title_generic', [], $locale))

@section('subtitle', $pauseEnd && $paymentDay
    ? __('emails.pause_ending_reminder.subtitle', ['pause_end' => $pauseEnd, 'payment_date' => $paymentDay], $locale)
    : __('emails.pause_ending_reminder.subtitle_generic', [], $locale))

@section('content')
@include('emails.partials.upcoming-box', ['pendingText' => __('emails.upcoming_box.pending', [], $locale)])

@include('emails.partials.upcoming-box-actions', [
    'paymentNote' => __('emails.pause_ending_reminder.payment_note', [], $locale),
    'pauseLabel' => __('emails.pause_ending_reminder.pause', [], $locale),
    'deadlineText' => $deadline ? __('emails.pause_ending_reminder.deadline', ['date' => $deadline], $locale) : null,
])
@endsection
