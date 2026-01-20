@extends('layouts.app')

@section('title', __('pages.how_it_works.title'))

@section('content')

<!-- Hero Header Section - Editorial Layout -->
<div style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    
    <!-- Main Heading - Large Editorial Typography, Left aligned -->
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
      <span class="text-dark-800">{{ $currentLocale === 'en' ? 'How it' : 'Jak to' }}</span><br>
      <span class="text-primary-500">{{ $currentLocale === 'en' ? 'works' : 'funguje' }}</span>
    </h1>
      
    <!-- Description - Right aligned -->
    <div class="flex justify-end">
      <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-500 max-w-md text-right leading-relaxed">
        {{ __('pages.how_it_works.subtitle') }}
      </p>
    </div>
  
  </div>
</div>

<!-- FAQ Section 1: Předplatné a doprava -->
<div id="predplatne" class="py-16 lg:py-24" style="background-color: #e5e6df;">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Section Header -->
        <div class="flex items-baseline gap-4 mb-12 border-t-2 border-primary-500 pt-6">
            <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">01</span>
            <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">
                {{ __('pages.how_it_works.section1_title') }}
            </h2>
        </div>

        <!-- FAQ Items -->
        <div class="space-y-0">
            @foreach([1,2,3,4,5,6,7,8] as $i)
            <div class="border-t border-warm-300 py-8 grid md:grid-cols-12 gap-6">
                <div class="md:col-span-1">
                    <span class="text-xs uppercase tracking-widest text-primary-500">0{{ $i }}</span>
                </div>
                <div class="md:col-span-4">
                    <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight">
                        {{ __('pages.how_it_works.faq'.$i.'_title') }}
                    </h3>
                </div>
                <div class="md:col-span-7">
                    <p class="text-warm-500 text-sm leading-relaxed">
                        {!! __('pages.how_it_works.faq'.$i.'_text') !!}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- FAQ Section 2: Výběr kávy -->
<div id="vyber-kavy" class="py-16 lg:py-24" style="background-color: #e5e6df;">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Section Header -->
        <div class="flex items-baseline gap-4 mb-12 border-t-2 border-primary-500 pt-6">
            <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">02</span>
            <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">
                {{ __('pages.how_it_works.section2_title') }}
            </h2>
        </div>

        <!-- FAQ Items -->
        <div class="space-y-0">
            @foreach([9,10,11,12,13,14,15,16] as $index => $i)
            <div class="border-t border-warm-300 py-8 grid md:grid-cols-12 gap-6">
                <div class="md:col-span-1">
                    <span class="text-xs uppercase tracking-widest text-primary-500">0{{ $index + 1 }}</span>
                </div>
                <div class="md:col-span-4">
                    <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight">
                        {{ __('pages.how_it_works.faq'.$i.'_title') }}
                    </h3>
                </div>
                <div class="md:col-span-7">
                    <p class="text-warm-500 text-sm leading-relaxed">
                        {!! __('pages.how_it_works.faq'.$i.'_text') !!}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="py-16 lg:py-24" style="background-color: #e5e6df;">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Section Header -->
        <div class="flex items-baseline gap-4 mb-12 border-t-2 border-primary-500 pt-6">
            <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">03</span>
            <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">
                {{ __('pages.how_it_works.benefits_title') }}
            </h2>
        </div>

        <div class="grid gap-px sm:grid-cols-3 border-t border-warm-300">
            <!-- Benefit 1 -->
            <div class="py-8 pr-8 border-b sm:border-b-0 sm:border-r border-warm-300">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                    <span class="text-xs uppercase tracking-widest text-warm-400">01</span>
                </div>
                <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight mb-3">{{ __('pages.how_it_works.benefit1_title') }}</h3>
                <p class="text-warm-500 text-sm leading-relaxed">{{ __('pages.how_it_works.benefit1_text') }}</p>
            </div>

            <!-- Benefit 2 -->
            <div class="py-8 px-8 border-b sm:border-b-0 sm:border-r border-warm-300">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                    <span class="text-xs uppercase tracking-widest text-warm-400">02</span>
                </div>
                <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight mb-3">{{ __('pages.how_it_works.benefit2_title') }}</h3>
                <p class="text-warm-500 text-sm leading-relaxed">{{ __('pages.how_it_works.benefit2_text') }}</p>
            </div>

            <!-- Benefit 3 -->
            <div class="py-8 pl-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                    <span class="text-xs uppercase tracking-widest text-warm-400">03</span>
                </div>
                <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight mb-3">{{ __('pages.how_it_works.benefit3_title') }}</h3>
                <p class="text-warm-500 text-sm leading-relaxed">{{ __('pages.how_it_works.benefit3_text') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="relative pt-12 sm:pt-16 lg:pt-20 pb-20 sm:pb-24 lg:pb-28" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
      <!-- Large Editorial Heading -->
      <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase leading-tight sm:leading-[0.95]">
        {{ __('pages.how_it_works.cta_title') }}
      </h2>

      <p class="mb-10 text-sm uppercase tracking-widest text-warm-500 max-w-xl leading-relaxed">
        {{ __('pages.how_it_works.cta_text') }}
      </p>

      <!-- CTA Links -->
      <div class="flex flex-col sm:flex-row gap-6">
        <a href="{{ localizedRoute('contact') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
          <span>{{ __('pages.how_it_works.cta_contact') }}</span>
          <span class="group-hover:translate-x-1 transition-transform">→</span>
        </a>

        <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-warm-500 font-display uppercase tracking-widest hover:text-dark-800 transition-all">
          <span>{{ __('pages.how_it_works.cta_subscription') }}</span>
          <span class="group-hover:translate-x-1 transition-transform">→</span>
        </a>
      </div>
    </div>
  </div>
</div>

@endsection
