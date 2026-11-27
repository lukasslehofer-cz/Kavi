@extends('errors.layout')

@php
    $host = request()->getHost();
    $locale = $currentLocale ?? (\App\Helpers\Site::forHost($host)?->locale ?? 'cs');
    $routeFor = fn (string $name) => route(\App\Services\EmailService::routeName($locale, $name));
@endphp

@section('title', __('errors.forbidden.title', [], $locale))

@section('content')
<div class="mx-auto max-w-screen-xl px-4 md:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <!-- Error Code -->
            <div class="mb-8">
                <span class="text-[120px] sm:text-[180px] md:text-[220px] font-bold leading-none text-red-600">
                    403
                </span>
            </div>

            <!-- Heading -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold uppercase tracking-tight mb-6 leading-[0.95]">
                {{ __('errors.forbidden.heading_1', [], $locale) }}<br>
                <span class="text-red-600">{{ __('errors.forbidden.heading_2', [], $locale) }}</span>
            </h1>

            <!-- Description -->
            <p class="text-lg md:text-xl text-[#1c1c1c]/70 mb-10 max-w-xl leading-relaxed">
                {{ __('errors.forbidden.description', [], $locale) }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap gap-4 mb-12">
                @guest
                <a href="{{ $routeFor('login') }}" class="inline-flex items-center justify-center bg-[#1c1c1c] text-white text-xs uppercase tracking-widest px-8 py-4 hover:bg-[#1c1c1c]/80 transition-colors">
                    {{ __('errors.forbidden.sign_in_button', [], $locale) }}
                </a>
                @endguest
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-widest border-b border-[#1c1c1c] pb-1 hover:text-red-600 hover:border-red-600 transition-colors">
                    {{ __('errors.links.back_home', [], $locale) }}
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <!-- Info -->
            <div class="pt-8 border-t border-[#1c1c1c]/20">
                <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50 mb-4">
                    {{ __('errors.forbidden.reasons_title', [], $locale) }}
                </p>
                <ul class="space-y-2 text-sm text-[#1c1c1c]/70">
                    <li class="flex items-center gap-2">
                        <span class="text-red-600">●</span>
                        {{ __('errors.forbidden.reason_login', [], $locale) }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-red-600">●</span>
                        {{ __('errors.forbidden.reason_session', [], $locale) }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-red-600">●</span>
                        {{ __('errors.forbidden.reason_restricted', [], $locale) }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-[#BCBEB1] p-8 h-full">
                <h2 class="text-xs uppercase tracking-widest mb-6 border-b border-[#1c1c1c] pb-4">
                    {{ __('errors.common.need_help', [], $locale) }}
                </h2>

                <div class="space-y-6">
                    <!-- Login -->
                    @guest
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/60 mb-1">
                                {{ __('errors.forbidden.sign_in_label', [], $locale) }}
                            </p>
                            <a href="{{ $routeFor('login') }}" class="text-sm hover:text-red-600 transition-colors">
                                {{ __('errors.forbidden.sign_in_link', [], $locale) }}
                            </a>
                        </div>
                    </div>
                    @endguest

                    <!-- Email -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/60 mb-1">{{ __('errors.common.email', [], $locale) }}</p>
                            <a href="mailto:{{ \App\Helpers\Site::forLocale($locale)->contactEmail() }}" class="text-sm hover:text-red-600 transition-colors">
                                {{ \App\Helpers\Site::forLocale($locale)->contactEmail() }}
                            </a>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/60 mb-1">
                                {{ __('errors.common.contact_form', [], $locale) }}
                            </p>
                            <a href="{{ $routeFor('contact') }}" class="text-sm hover:text-red-600 transition-colors">
                                {{ __('errors.common.send_message', [], $locale) }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Error Info -->
                <div class="mt-8 pt-6 border-t border-[#1c1c1c]/20">
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50">
                        {{ __('errors.common.error_code', [], $locale) }}
                    </p>
                    <p class="text-sm mt-1">403 / {{ __('errors.forbidden.code_label', [], $locale) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
