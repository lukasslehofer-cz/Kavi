@extends('emails.layouts.kavi')

@section('title', __('gifts.mail.ending_title', [], $locale))

@section('content')
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 24px 0;">
    {{ __('gifts.mail.ending_intro', ['date' => $lastBox], $locale) }}
</p>
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 32px 0;">
    {{ __('gifts.mail.ending_offer', [], $locale) }}
</p>
<a href="{{ $continueUrl }}" style="display: inline-block; background-color: #ca4136; color: #ffffff; padding: 14px 28px; text-decoration: none; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em;">{{ __('gifts.mail.ending_cta', [], $locale) }} &rarr;</a>
@endsection
