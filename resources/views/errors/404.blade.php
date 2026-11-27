@extends('errors.layout')

@php
    $host = request()->getHost();
    $locale = $currentLocale ?? (\App\Helpers\Site::forHost($host)?->locale ?? 'cs');
    $routeFor = fn (string $name) => route(\App\Services\EmailService::routeName($locale, $name));
@endphp

@section('title', __('errors.not_found.title', [], $locale))

@section('content')
<div class="mx-auto max-w-screen-xl px-4 md:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <!-- Error Code -->
            <div class="mb-8">
                <span class="text-[120px] sm:text-[180px] md:text-[220px] font-bold leading-none text-red-600">
                    404
                </span>
            </div>

            <!-- Heading -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold uppercase tracking-tight mb-6 leading-[0.95]">
                {{ __('errors.not_found.heading_1', [], $locale) }}<br>
                <span class="text-red-600">{{ __('errors.not_found.heading_2', [], $locale) }}</span>
            </h1>

            <!-- Description -->
            <p class="text-lg md:text-xl text-[#1c1c1c]/70 mb-10 max-w-xl leading-relaxed">
                {{ __('errors.not_found.description', [], $locale) }}
            </p>

            <!-- CTA Button -->
            <div class="flex flex-wrap gap-4 mb-12">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center bg-[#1c1c1c] text-white text-xs uppercase tracking-widest px-8 py-4 hover:bg-[#1c1c1c]/80 transition-colors">
                    {{ __('errors.links.back_home', [], $locale) }}
                </a>
                <a href="{{ $routeFor('subscriptions.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-widest border-b border-[#1c1c1c] pb-1 hover:text-red-600 hover:border-red-600 transition-colors">
                    {{ __('errors.links.coffee_boxes', [], $locale) }}
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <!-- Quick Links -->
            <div class="pt-8 border-t border-[#1c1c1c]/20">
                <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50 mb-4">
                    {{ __('errors.not_found.maybe_looking_for', [], $locale) }}
                </p>
                <div class="flex flex-wrap gap-x-6 gap-y-2">
                    <a href="{{ $routeFor('products.index') }}" class="text-sm hover:text-red-600 transition-colors">
                        {{ __('errors.links.shop', [], $locale) }}
                    </a>
                    <a href="{{ $routeFor('roasteries.index') }}" class="text-sm hover:text-red-600 transition-colors">
                        {{ __('errors.links.roasteries', [], $locale) }}
                    </a>
                    <a href="{{ $routeFor('monthly-feature.index') }}" class="text-sm hover:text-red-600 transition-colors">
                        {{ __('errors.links.coffee_of_the_month', [], $locale) }}
                    </a>
                    <a href="{{ $routeFor('how-it-works') }}" class="text-sm hover:text-red-600 transition-colors">
                        {{ __('errors.links.how_it_works', [], $locale) }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-[#BCBEB1] p-8 h-full">
                <h2 class="text-xs uppercase tracking-widest mb-6 border-b border-[#1c1c1c] pb-4">
                    {{ __('errors.common.need_help', [], $locale) }}
                </h2>

                <div class="space-y-6">
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

                    <!-- Contact Page -->
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

                    <!-- FAQ -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/60 mb-1">
                                {{ __('errors.not_found.learn_more', [], $locale) }}
                            </p>
                            <a href="{{ $routeFor('how-it-works') }}" class="text-sm hover:text-red-600 transition-colors">
                                {{ __('errors.links.how_it_works', [], $locale) }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Error Info -->
                <div class="mt-8 pt-6 border-t border-[#1c1c1c]/20">
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50">
                        {{ __('errors.common.error_code', [], $locale) }}
                    </p>
                    <p class="text-sm mt-1">404 / {{ __('errors.not_found.code_label', [], $locale) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
