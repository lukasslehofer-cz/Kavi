@extends('layouts.app')

@section('title', __('pages.how_it_works.title'))

@section('content')

<!-- Hero Section -->
<div class="relative bg-gray-100 py-16 md:py-20 overflow-hidden">
    <!-- Subtle Organic Shapes -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary-100 rounded-full"></div>
        <div class="absolute -bottom-32 -left-32 w-[36rem] h-[36rem] bg-primary-50 rounded-full hidden md:block"></div>
    </div>
    
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <!-- Minimal Badge -->
            <div class="inline-flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 mb-6">
                <svg class="w-4 h-4 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-900">{{ __('pages.how_it_works.badge') }}</span>
            </div>
            
            <!-- Clean Heading -->
            <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                {{ __('pages.how_it_works.heading') }}
            </h1>
            
            <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light mb-8">
                {{ __('pages.how_it_works.subtitle') }}
            </p>
            
            <!-- Quick Links -->
            <div class="flex flex-wrap justify-center gap-3">
                <a href="#predplatne" class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium px-6 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200">
                    <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>{{ __('pages.how_it_works.subscription') }}</span>
                </a>
                <a href="#vyber-kavy" class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium px-6 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span>{{ __('pages.how_it_works.coffee_selection') }}</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Wave Divider -->
    <div class="absolute bottom-[-1px] left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 80L60 73C120 67 240 53 360 48C480 43 600 47 720 53C840 59 960 67 1080 69C1200 71 1320 67 1380 65L1440 63V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#ffffff"/>
        </svg>
    </div>
</div>

<!-- FAQ Section 1: Předplatné a doprava -->
<div id="predplatne" class="relative bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Section Header -->
        <div class="mb-16 text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-900 rounded-full mb-6">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('pages.how_it_works.section1_title') }}
            </h2>
            <p class="text-lg text-gray-600 font-light">
                {{ __('pages.how_it_works.section1_subtitle') }}
            </p>
        </div>

        <!-- FAQ Items -->
        <div class="grid md:grid-cols-2 gap-6 max-w-6xl mx-auto">
            <!-- FAQ Item 1 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">1</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq1_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {{ __('pages.how_it_works.faq1_text') }}
                </p>
            </div>

            <!-- FAQ Item 2 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">2</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq2_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {!! __('pages.how_it_works.faq2_text') !!}
                </p>
            </div>

            <!-- FAQ Item 3 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">3</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq3_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {!! __('pages.how_it_works.faq3_text') !!}
                </p>
            </div>

            <!-- FAQ Item 4 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">4</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq4_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {!! __('pages.how_it_works.faq4_text') !!}
                </p>
            </div>

            <!-- FAQ Item 5 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">5</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq5_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {{ __('pages.how_it_works.faq5_text') }}
                </p>
            </div>

            <!-- FAQ Item 6 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">6</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq6_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {{ __('pages.how_it_works.faq6_text') }}
                </p>
            </div>

            <!-- FAQ Item 7 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">7</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq7_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {{ __('pages.how_it_works.faq7_text') }}
                </p>
            </div>

            <!-- FAQ Item 8 - Featured -->
            <div class="group relative bg-primary-500 rounded-2xl p-8 hover:bg-primary-600 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-white">8</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-4 pr-14">
                    {{ __('pages.how_it_works.faq8_title') }}
                </h3>
                <p class="text-white/90 leading-relaxed font-light">
                    {{ __('pages.how_it_works.faq8_text') }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section 2: Výběr kávy -->
<div id="vyber-kavy" class="relative bg-gray-100 py-20 lg:py-28">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Section Header -->
        <div class="mb-16 text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-900 rounded-full mb-6">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('pages.how_it_works.section2_title') }}
            </h2>
            <p class="text-lg text-gray-600 font-light">
                {{ __('pages.how_it_works.section2_subtitle') }}
            </p>
        </div>

        <!-- FAQ Items -->
        <div class="grid md:grid-cols-2 gap-6 max-w-6xl mx-auto">
            <!-- FAQ Item 1 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">1</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq9_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {!! __('pages.how_it_works.faq9_text') !!}
                </p>
            </div>

            <!-- FAQ Item 2 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">2</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq10_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {{ __('pages.how_it_works.faq10_text') }}
                </p>
            </div>

            <!-- FAQ Item 3 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">3</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq11_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {!! __('pages.how_it_works.faq11_text') !!}
                </p>
            </div>

            <!-- FAQ Item 4 - Featured -->
            <div class="group relative bg-amber-500 rounded-2xl p-8 hover:bg-amber-600 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-white">4</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-4 pr-14">
                    {{ __('pages.how_it_works.faq12_title') }}
                </h3>
                <p class="text-white/90 leading-relaxed font-light">
                    {!! __('pages.how_it_works.faq12_text') !!}
                </p>
            </div>

            <!-- FAQ Item 5 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">5</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq13_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {{ __('pages.how_it_works.faq13_text') }}
                </p>
            </div>

            <!-- FAQ Item 6 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">6</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq14_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {!! __('pages.how_it_works.faq14_text') !!}
                </p>
            </div>

            <!-- FAQ Item 7 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">7</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq15_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {{ __('pages.how_it_works.faq15_text') }}
                </p>
            </div>

            <!-- FAQ Item 8 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">8</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    {{ __('pages.how_it_works.faq16_title') }}
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    {!! __('pages.how_it_works.faq16_text') !!}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="relative bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="mb-16 max-w-2xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ __('pages.how_it_works.benefits_title') }}
            </h2>
            <p class="text-lg text-gray-600 font-light">
                {{ __('pages.how_it_works.benefits_subtitle') }}
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-3 max-w-5xl mx-auto">
            <!-- Benefit 1 -->
            <div class="flex flex-col items-center text-center p-8 bg-white rounded-2xl border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-gray-900">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">{{ __('pages.how_it_works.benefit1_title') }}</h3>
                <p class="text-gray-600 font-light leading-relaxed">{{ __('pages.how_it_works.benefit1_text') }}</p>
            </div>

            <!-- Benefit 2 -->
            <div class="flex flex-col items-center text-center p-8 bg-white rounded-2xl border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-gray-900">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">{{ __('pages.how_it_works.benefit2_title') }}</h3>
                <p class="text-gray-600 font-light leading-relaxed">{{ __('pages.how_it_works.benefit2_text') }}</p>
            </div>

            <!-- Benefit 3 -->
            <div class="flex flex-col items-center text-center p-8 bg-white rounded-2xl border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-gray-900">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">{{ __('pages.how_it_works.benefit3_title') }}</h3>
                <p class="text-gray-600 font-light leading-relaxed">{{ __('pages.how_it_works.benefit3_text') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="relative bg-gray-100 py-20 lg:py-24">
    
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="mx-auto flex max-w-2xl flex-col items-center text-center">
            <!-- Heading -->
            <h2 class="mb-6 text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight tracking-tight">
                {{ __('pages.how_it_works.cta_title') }}
            </h2>

            <p class="mb-10 text-lg text-gray-600 max-w-xl leading-relaxed font-light">
                {{ __('pages.how_it_works.cta_text') }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ localizedRoute('contact') }}" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-3 rounded-full transition-all duration-200">
                    <span>{{ __('pages.how_it_works.cta_contact') }}</span>
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>

                <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium px-8 py-3 rounded-full border border-gray-200 transition-all duration-200">
                    <span>{{ __('pages.how_it_works.cta_subscription') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
