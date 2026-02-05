@extends('layouts.app')

@section('title', $post->getTitle() . ' - ' . ($currentLocale === 'en' ? 'KAVI' : 'KAVI.cz'))

@section('meta_description')
{{ $post->getPerex() ?? Str::limit(strip_tags($post->getContent()), 160) }}
@endsection

@section('og_title')
{{ $post->getTitle() }} | {{ $currentLocale === 'en' ? 'KAVI' : 'KAVI.cz' }}
@endsection

@section('og_description')
{{ $post->getPerex() ?? Str::limit(strip_tags($post->getContent()), 160) }}
@endsection

@if($post->featured_image)
@section('og_image')
{{ asset($post->featured_image) }}
@endsection
@endif

@section('content')
<!-- Hero Section with Featured Image -->
<div class="relative" style="background-color: #e5e6df;">
  @if($post->featured_image)
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-8 lg:pt-12">
    <div class="aspect-[21/9] overflow-hidden">
      <img src="{{ asset($post->featured_image) }}" alt="{{ $post->getTitle() }}" class="w-full h-full object-cover">
    </div>
  </div>
  @endif

  <div class="max-w-screen-xl mx-auto px-4 md:px-8 {{ $post->featured_image ? 'pt-8 lg:pt-12' : 'pt-16 lg:pt-24' }} pb-8 lg:pb-12">
    <!-- Breadcrumb -->
    <nav class="mb-8">
      <ol class="flex items-center gap-2 text-xs uppercase tracking-widest text-warm-500">
        <li>
          <a href="{{ localizedRoute('home') }}" class="hover:text-dark-800 transition-colors">
            {{ $currentLocale === 'en' ? 'Home' : 'Domů' }}
          </a>
        </li>
        <li class="text-warm-300">—</li>
        <li>
          <a href="{{ localizedRoute('blog.index') }}" class="hover:text-dark-800 transition-colors">
            Blog
          </a>
        </li>
        <li class="text-warm-300">—</li>
        <li class="text-dark-800">{{ Str::limit($post->getTitle(), 40) }}</li>
      </ol>
    </nav>

    <!-- Meta Info -->
    <div class="flex flex-wrap items-center gap-4 mb-6">
      @if($post->published_at)
      <span class="text-xs uppercase tracking-widest text-warm-500">
        {{ $post->getFormattedDate() }}
      </span>
      @endif

      @if($post->author)
      <span class="text-warm-300">·</span>
      <span class="text-xs uppercase tracking-widest text-warm-500">
        {{ $currentLocale === 'en' ? 'By' : 'Autor' }} {{ $post->author }}
      </span>
      @endif

      @if($post->tags->isNotEmpty())
      <span class="text-warm-300">·</span>
      <div class="flex flex-wrap gap-2">
        @foreach($post->tags as $tag)
        <a href="{{ localizedRoute('blog.index', ['tag' => $tag->slug]) }}" 
           class="text-xs uppercase tracking-widest text-primary-500 hover:text-dark-800 transition-colors">
          {{ $tag->getName() }}
        </a>
        @endforeach
      </div>
      @endif
    </div>
    
    <!-- Main Heading -->
    <h1 class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase text-dark-800 max-w-5xl">
      {{ $post->getTitle() }}
    </h1>

    <!-- Perex -->
    @if($post->getPerex())
    <div class="mt-8 max-w-3xl">
      <p class="text-lg sm:text-xl text-warm-500 leading-relaxed font-light">
        {{ $post->getPerex() }}
      </p>
    </div>
    @endif
  </div>
</div>

<!-- Article Content -->
<div class="py-12 sm:py-16 lg:py-20" style="background-color: #e5e6df;">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="max-w-3xl mx-auto">
      <!-- Article Body -->
      <article class="blog-article-content">
        {!! $post->getContent() !!}
      </article>

      <!-- Tags -->
      @if($post->tags->isNotEmpty())
      <div class="mt-12 pt-8 border-t border-warm-300">
        <p class="text-xs uppercase tracking-widest text-warm-500 mb-4">
          {{ $currentLocale === 'en' ? 'Tags' : 'Tagy' }}
        </p>
        <div class="flex flex-wrap gap-2">
          @foreach($post->tags as $tag)
          <a href="{{ localizedRoute('blog.index', ['tag' => $tag->slug]) }}" 
             class="inline-flex items-center px-3 py-1.5 bg-warm-200 text-dark-800 text-xs uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-colors">
            {{ $tag->getName() }}
          </a>
          @endforeach
        </div>
      </div>
      @endif

      <!-- Back to Blog -->
      <div class="mt-12">
        <a href="{{ localizedRoute('blog.index') }}" class="group inline-flex items-center gap-2 text-warm-500 font-display uppercase tracking-widest hover:text-dark-800 transition-all">
          <span class="text-primary-500 group-hover:-translate-x-1 transition-transform">←</span>
          <span>{{ $currentLocale === 'en' ? 'Back to blog' : 'Zpět na blog' }}</span>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Related Posts -->
@if($relatedPosts->isNotEmpty())
<div class="py-12 sm:py-16 lg:py-20 border-t border-warm-300" style="background-color: #e5e6df;">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <h2 class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight mb-10">
      {{ $currentLocale === 'en' ? 'Related articles' : 'Související články' }}
    </h2>

    <div class="grid gap-x-4 sm:gap-x-8 lg:gap-x-10 gap-y-10 sm:gap-y-12 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
      @foreach($relatedPosts as $relatedPost)
      <a href="{{ localizedRoute('blog.show', ['post' => $relatedPost->slug]) }}" class="group block">
        <!-- Image Container -->
        <div class="relative aspect-[16/10] overflow-hidden mb-4">
          @if($relatedPost->featured_image)
          <img src="{{ asset($relatedPost->featured_image) }}" loading="lazy" alt="{{ $relatedPost->getTitle() }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
          @else
          <div class="h-full w-full flex flex-col items-center justify-center bg-warm-200">
            <span class="font-display text-6xl text-warm-300">{{ mb_substr($relatedPost->getTitle(), 0, 1) }}</span>
          </div>
          @endif

          @if($relatedPost->published_at)
          <div class="absolute top-0 left-0">
            <span class="text-[10px] uppercase tracking-widest text-dark-800 bg-[#e5e6df] px-2 py-1">
              {{ $relatedPost->getFormattedDate() }}
            </span>
          </div>
          @endif
        </div>

        <!-- Post Info -->
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1 min-w-0">
            @if($relatedPost->tags->isNotEmpty())
            <p class="text-xs uppercase tracking-widest text-warm-500 mb-2">
              @foreach($relatedPost->tags->take(2) as $tag)
                {{ $tag->getName() }}{{ !$loop->last ? ' · ' : '' }}
              @endforeach
            </p>
            @endif
            
            <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight group-hover:text-primary-500 transition-colors line-clamp-2" style="line-height: 1.25;">
              {{ $relatedPost->getTitle() }}
            </h3>
          </div>

          <div class="flex-shrink-0 mt-2">
            <svg class="w-5 h-5 text-warm-400 group-hover:text-dark-800 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</div>
@endif

<!-- Promo CTA Section -->
<div class="relative py-20 sm:py-24 lg:py-32" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
      <!-- Large Editorial Heading -->
      <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase leading-tight sm:leading-[0.95]">
        {{ $currentLocale === 'en' ? 'Start your coffee journey today' : 'Začněte svou kávovou cestu ještě dnes' }}
      </h2>

      <p class="mb-10 sm:mb-12 text-lg sm:text-xl text-warm-500 max-w-2xl leading-relaxed font-light">
        {{ $currentLocale === 'en' ? 'Get access to the best coffee from all over Europe. Flexible subscription, no commitment.' : 'Získejte přístup k nejlepší kávě z celé Evropy. Flexibilní předplatné, bez závazků.' }}
      </p>

      <!-- CTA Links -->
      <div class="flex flex-col sm:flex-row gap-6 sm:gap-10">
        <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
          <span>{{ $currentLocale === 'en' ? 'Choose subscription' : 'Vybrat předplatné' }}</span>
          <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>

        <a href="{{ localizedRoute('products.index') }}" class="group inline-flex items-center gap-2 text-warm-400 font-display uppercase tracking-widest hover:text-dark-800 transition-all">
          <span>{{ $currentLocale === 'en' ? 'Browse coffees' : 'Procházet kávy' }}</span>
          <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
