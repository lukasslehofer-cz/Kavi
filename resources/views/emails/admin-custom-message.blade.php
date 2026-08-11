@extends('emails.layouts.kavi')

@section('title', $subjectLine)

@section('content')
    {{-- Bloky přicházejí z LocalizedMailable::parseBlocks() už escapované
         a vyrenderované, proto {!! !!}. HTML z administrace se dovnitř nepouští. --}}
    @foreach($blocks as $block)
        @if($block['type'] === 'list')
            @php($listTag = $block['ordered'] ? 'ol' : 'ul')
            <{{ $listTag }} style="margin: 0 0 16px 0; padding-left: 22px; font-size: 15px; color: #4a4a4a; line-height: 1.7;">
                @foreach($block['items'] as $item)
                    <li style="margin: 0 0 8px 0;">{!! $item !!}</li>
                @endforeach
            </{{ $listTag }}>
        @else
            <p style="font-size: 15px; color: #4a4a4a; line-height: 1.7; margin: 0 0 16px 0;">
                {!! $block['html'] !!}
            </p>
        @endif
    @endforeach

    @if($buttonLabel && $buttonUrl)
    <!-- CTA Button -->
    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ $buttonUrl }}" style="display: inline-block; background-color: #1c1c1c; color: #ffffff !important; text-decoration: none; padding: 16px 32px; font-weight: 400; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
            {{ $buttonLabel }} →
        </a>
    </div>
    @endif
@endsection
