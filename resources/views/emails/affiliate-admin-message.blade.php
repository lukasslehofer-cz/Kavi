@extends('emails.layouts.kavi')

@section('title', $subjectLine)

@section('content')
    {{-- Tělo zprávy přichází z administrace jako prostý text, Blade ho escapuje --}}
    @foreach($paragraphs as $paragraph)
        <p style="font-size: 15px; color: #4a4a4a; line-height: 1.7; margin: 0 0 16px 0;">
            {!! nl2br(e($paragraph)) !!}
        </p>
    @endforeach

    @if($coupons->isNotEmpty())
    <!-- Kódy a odkazy partnera -->
    <div style="margin: 40px 0 32px 0; padding-top: 24px; border-top: 2px solid #CA4136;">
        <div style="font-size: 11px; font-weight: 400; color: #76716C; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.15em;">
            {{ __('emails.affiliate_admin_message.your_codes', [], $locale) }}
        </div>

        @foreach($coupons as $coupon)
            <div style="margin: 0 0 16px 0; padding: 16px 20px; background-color: #d5d7ca;">
                <div style="font-size: 18px; color: #1c1c1c; letter-spacing: 0.05em; margin-bottom: 6px;">
                    {{ $coupon->code }}
                </div>
                @foreach($coupon->affiliateLinks->where('is_active', true) as $link)
                    <div style="font-size: 13px; color: #5a5a5a; word-break: break-all;">
                        <a href="{{ $link->getFullUrl() }}" style="color: #CA4136; text-decoration: none;">{{ $link->getFullUrl() }}</a>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    @endif

    <!-- CTA Button -->
    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ $affiliateUrl }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
            {{ __('emails.affiliate_common.open_section', [], $locale) }} →
        </a>
    </div>
@endsection
