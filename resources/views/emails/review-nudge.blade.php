@extends('emails.layouts.kavi')

{{-- Výzva k dokončení hodnocení: jeden krok, jedno tlačítko. Nic dalšího
     sem nepatří, cokoli navíc by od dokončení odvádělo. --}}

@php
    $t = fn (string $key) => __('emails.review_nudge.'.$key, [], $locale);
@endphp

@section('title', $t('title'))

@section('subtitle', $t('subtitle'))

@section('content')
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 16px 0;">
    {{ $t('intro') }}
</p>

<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 32px 0;">
    {{ $t('steps') }}
</p>

<div style="margin: 0 0 32px 0;">
    <a href="{{ $reviewLink }}" target="_blank" rel="noopener" style="display: inline-block; background-color: #ca4136; color: #ffffff; padding: 16px 32px; text-decoration: none; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em;">{{ $t('button') }} &rarr;</a>
</div>

<p style="font-size: 14px; color: #5a5a5a; line-height: 1.6; margin: 0;">
    {{ $t('done_note') }}
</p>
@endsection
