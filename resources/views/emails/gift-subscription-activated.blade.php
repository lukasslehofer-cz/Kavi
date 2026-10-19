@extends('emails.layouts.kavi')

@section('title', __('gifts.mail.activated_title', [], $locale))

@section('content')
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 24px 0;">
    {{ __('gifts.mail.activated_intro', ['box' => $box, 'first' => $first, 'last' => $last], $locale) }}
</p>
<p style="font-size: 14px; color: #5f5b57; line-height: 1.6; margin: 0 0 32px 0;">
    {{ __('gifts.mail.activated_note', [], $locale) }}
</p>
<a href="{{ $accountUrl }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff; padding: 14px 28px; text-decoration: none; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('gifts.mail.activated_cta', [], $locale) }} &rarr;</a>
@endsection
