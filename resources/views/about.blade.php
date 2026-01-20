@extends('layouts.app')

@section('title', __('pages.about.title'))

@section('content')

<!-- Hero Header Section - Editorial Layout -->
<div style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    
    <!-- Main Heading - Large Editorial Typography, Left aligned -->
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
      <span class="text-dark-800">{{ $currentLocale === 'en' ? 'About' : 'O nás' }}</span><br>
      <span class="text-primary-500">{{ $currentLocale === 'en' ? 'KAVI' : '' }}</span>
    </h1>
      
    <!-- Description - Right aligned -->
    <div class="flex justify-end">
      <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-500 max-w-md text-right leading-relaxed">
        {{ __('pages.about.subtitle') }}
      </p>
    </div>
  
  </div>
</div>

<!-- Story Section -->
<div class="py-16 lg:py-24" style="background-color: #e5e6df;">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            <!-- Image - Left -->
            <div class="lg:col-span-5">
                <div class="relative">
                    <img 
                        src="/images/lukas.jpg" 
                        alt="Lukáš Šlehofer - {{ app()->getLocale() === 'en' ? 'founder of KAVI' : 'zakladatel KAVI' }}" 
                        class="w-full h-auto object-cover grayscale"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <!-- Fallback if image doesn't exist -->
                    <div class="hidden w-full aspect-[4/3] bg-warm-300 items-center justify-center">
                        <span class="font-display text-4xl text-warm-400 uppercase tracking-tight">LŠ</span>
                    </div>
                    <!-- Caption -->
                    <p class="mt-4 text-xs uppercase tracking-widest text-warm-400">
                        Lukáš Šlehofer · {{ $currentLocale === 'en' ? 'Founder' : 'Zakladatel' }}
                    </p>
                </div>
            </div>

            <!-- Content - Right -->
            <div class="lg:col-span-7">
                <!-- Section Heading -->
                <div class="flex items-baseline gap-4 mb-8 border-t-2 border-primary-500 pt-6">
                    <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">01</span>
                    <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ $currentLocale === 'en' ? 'Our Story' : 'Náš příběh' }}</h2>
                </div>

                <div class="space-y-6 text-warm-500 text-sm leading-relaxed">
                    <p>{!! __('pages.about.story1') !!}</p>
                    <p>{{ __('pages.about.story2') }}</p>
                    <p>{!! __('pages.about.story3') !!}</p>
                </div>

                <div class="mt-10 pt-6 border-t border-warm-300">
                    <p class="text-dark-800 text-sm leading-relaxed italic">
                        „{{ __('pages.about.story_highlight') }}"
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Values Section -->
<div class="py-16 lg:py-24" style="background-color: #e5e6df;">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Section Header -->
        <div class="flex items-baseline gap-4 mb-12 border-t-2 border-primary-500 pt-6">
            <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">02</span>
            <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('pages.about.values_title') }}</h2>
        </div>

        <div class="grid gap-px sm:grid-cols-3 border-t border-warm-300">
            <!-- Value 1 -->
            <div class="py-8 pr-8 border-b sm:border-b-0 sm:border-r border-warm-300">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                    <span class="text-xs uppercase tracking-widest text-warm-400">01</span>
                </div>
                <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight mb-3">{{ __('pages.about.value1_title') }}</h3>
                <p class="text-warm-500 text-sm leading-relaxed">{{ __('pages.about.value1_text') }}</p>
            </div>

            <!-- Value 2 -->
            <div class="py-8 px-8 border-b sm:border-b-0 sm:border-r border-warm-300">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                    <span class="text-xs uppercase tracking-widest text-warm-400">02</span>
                </div>
                <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight mb-3">{{ __('pages.about.value2_title') }}</h3>
                <p class="text-warm-500 text-sm leading-relaxed">{{ __('pages.about.value2_text') }}</p>
            </div>

            <!-- Value 3 -->
            <div class="py-8 pl-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                    <span class="text-xs uppercase tracking-widest text-warm-400">03</span>
                </div>
                <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight mb-3">{{ __('pages.about.value3_title') }}</h3>
                <p class="text-warm-500 text-sm leading-relaxed">{{ __('pages.about.value3_text') }}</p>
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
        {{ __('pages.about.cta_title') }}
      </h2>

      <p class="mb-10 text-sm uppercase tracking-widest text-warm-500 max-w-xl leading-relaxed">
        {{ __('pages.about.cta_text') }}
      </p>

      <!-- CTA Links -->
      <div class="flex flex-col sm:flex-row gap-6">
        <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
          <span>{{ __('pages.about.cta_subscription') }}</span>
          <span class="group-hover:translate-x-1 transition-transform">→</span>
        </a>

        <a href="{{ localizedRoute('products.index') }}" class="group inline-flex items-center gap-2 text-warm-500 font-display uppercase tracking-widest hover:text-dark-800 transition-all">
          <span>{{ __('pages.about.cta_browse') }}</span>
          <span class="group-hover:translate-x-1 transition-transform">→</span>
        </a>
      </div>
    </div>
  </div>
</div>

@endsection
