@extends('layouts.app')

@section('title', ($currentLocale === 'en' ? 'Blog' : 'Blog') . ' - ' . ($currentLocale === 'en' ? 'KAVI' : 'KAVI.cz'))

@section('meta_description')
{{ $currentLocale === 'en' ? 'Coffee blog with tips, recipes, and stories from the world of specialty coffee. Discover the art of brewing and learn from the best roasters.' : 'Kávový blog s tipy, recepty a příběhy ze světa výběrové kávy. Objevte umění přípravy kávy a učte se od nejlepších pražíren.' }}
@endsection

@section('og_title')
{{ $currentLocale === 'en' ? 'Coffee Blog | KAVI' : 'Kávový blog | KAVI.cz' }}
@endsection

@section('og_description')
{{ $currentLocale === 'en' ? 'Discover the art of specialty coffee. Tips, recipes, and stories from the best roasters.' : 'Objevte umění výběrové kávy. Tipy, recepty a příběhy od nejlepších pražíren.' }}
@endsection

@section('content')
<!-- Hero Header Section - Editorial Layout -->
<div class="relative" style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    
    <!-- Main Heading - Large Editorial Typography, Left aligned -->
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
      <span class="text-dark-800">{{ $currentLocale === 'en' ? 'Coffee' : 'Kávový' }}</span><br>
      <span class="text-primary-500">{{ $currentLocale === 'en' ? 'blog' : 'blog' }}</span>
    </h1>
      
    <!-- Description - Right aligned -->
    <div class="flex justify-end">
      <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-500 max-w-md text-right leading-relaxed">
        {{ $currentLocale === 'en' ? 'Tips, recipes, and stories from the world of specialty coffee. Discover the art of brewing.' : 'Tipy, recepty a příběhy ze světa výběrové kávy. Objevte umění přípravy kávy.' }}
      </p>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="py-10 sm:py-12 md:py-16 lg:py-20" style="background-color: #e5e6df;">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">

    <!-- Tags Filter -->
    @if($tags->isNotEmpty())
    <div class="mb-10 sm:mb-12">
      <div class="border-t border-b border-dark-800 py-4">
        <div class="flex flex-wrap items-center gap-x-8 gap-y-2">
          <a href="{{ localizedRoute('blog.index') }}" 
             class="text-sm uppercase tracking-widest transition-colors {{ !$currentTag ? 'text-primary-500 border-b border-primary-500 pb-0.5' : 'text-warm-500 hover:text-dark-800' }}">
            {{ $currentLocale === 'en' ? 'All' : 'Vše' }}<sup class="ml-0.5 text-[10px]">{{ str_pad($posts->total(), 2, '0', STR_PAD_LEFT) }}</sup>
          </a>
          @foreach($tags as $tag)
          <a href="{{ localizedRoute('blog.index', ['tag' => $tag->slug]) }}" 
             class="text-sm uppercase tracking-widest transition-colors {{ $currentTag && $currentTag->id === $tag->id ? 'text-primary-500 border-b border-primary-500 pb-0.5' : 'text-warm-500 hover:text-dark-800' }}">
            {{ $tag->getName() }}<sup class="ml-0.5 text-[10px]">{{ str_pad($tag->posts_count, 2, '0', STR_PAD_LEFT) }}</sup>
          </a>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    <!-- Current Tag Title -->
    @if($currentTag)
    <div class="mb-8">
      <p class="text-xs uppercase tracking-widest text-warm-500">
        {{ $currentLocale === 'en' ? 'Showing articles tagged' : 'Zobrazeny články s tagem' }}
      </p>
      <h2 class="font-display text-2xl text-dark-800 uppercase mt-1">{{ $currentTag->getName() }}</h2>
    </div>
    @endif

    <!-- Blog Posts Grid -->
    <div class="grid gap-x-4 sm:gap-x-8 lg:gap-x-10 gap-y-10 sm:gap-y-12 lg:gap-y-16 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
      @forelse($posts as $post)
      <!-- post - start -->
      <a href="{{ localizedRoute('blog.show', ['post' => $post->slug]) }}" class="group block">
        <!-- Image Container -->
        <div class="relative aspect-[16/10] overflow-hidden mb-4">
          @if($post->featured_image)
          <img src="{{ asset($post->featured_image) }}" loading="lazy" alt="{{ $post->getTitle() }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
          @else
          <div class="h-full w-full flex flex-col items-center justify-center bg-warm-200">
            <span class="font-display text-6xl text-warm-300">{{ mb_substr($post->getTitle(), 0, 1) }}</span>
          </div>
          @endif

          <!-- Date Badge -->
          @if($post->published_at)
          <div class="absolute top-0 left-0">
            <span class="text-[10px] uppercase tracking-widest text-dark-800 bg-[#e5e6df] px-2 py-1">
              {{ $post->getFormattedDate() }}
            </span>
          </div>
          @endif
        </div>

        <!-- Post Info -->
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1 min-w-0">
            <!-- Tags -->
            @if($post->tags->isNotEmpty())
            <p class="text-xs uppercase tracking-widest text-warm-500 mb-2">
              @foreach($post->tags->take(3) as $tag)
                {{ $tag->getName() }}{{ !$loop->last ? ' · ' : '' }}
              @endforeach
            </p>
            @endif
            
            <!-- Post Title -->
            <h3 class="font-display text-lg sm:text-xl font-normal text-dark-800 uppercase tracking-tight pt-[5px] pb-[8px] group-hover:text-primary-500 transition-colors line-clamp-2" style="line-height: 1.25;">
              {{ $post->getTitle() }}
            </h3>
            
            <!-- Perex -->
            @if($post->getPerex())
            <p class="text-sm text-warm-500 leading-relaxed line-clamp-2">
              {{ $post->getPerex() }}
            </p>
            @endif

            <!-- Author -->
            @if($post->author)
            <p class="text-xs uppercase tracking-widest text-warm-400 mt-3">
              {{ $currentLocale === 'en' ? 'By' : 'Autor' }} {{ $post->author }}
            </p>
            @endif
          </div>

          <!-- Arrow -->
          <div class="flex-shrink-0 mt-[26px]">
            <svg class="w-5 h-5 text-warm-400 group-hover:text-dark-800 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </div>
        </div>
      </a>
      <!-- post - end -->
      @empty
      <div class="col-span-full text-center py-16">
        <div class="max-w-md mx-auto">
          <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
          </svg>
          <p class="text-gray-600 text-lg font-bold">{{ $currentLocale === 'en' ? 'No articles found.' : 'Zatím žádné články.' }}</p>
          <p class="text-gray-500 mt-2">{{ $currentLocale === 'en' ? 'Check back soon for new content.' : 'Brzy přidáme nový obsah.' }}</p>
        </div>
      </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-16 border-t border-dark-800 pt-6">
      <div class="flex items-center justify-end gap-6">
        <!-- Page Counter -->
        <span class="text-xs uppercase tracking-widest text-warm-500">
          {{ $currentLocale === 'en' ? 'Page' : 'Strana' }} {{ str_pad($posts->currentPage(), 2, '0', STR_PAD_LEFT) }} / {{ str_pad($posts->lastPage(), 2, '0', STR_PAD_LEFT) }}
        </span>
        
        <!-- Navigation -->
        <div class="flex items-center">
          @if($posts->onFirstPage())
            <span class="text-xs uppercase tracking-widest text-warm-300 cursor-not-allowed">
              {{ $currentLocale === 'en' ? 'Previous' : 'Předchozí' }}
            </span>
          @else
            <a href="{{ $posts->previousPageUrl() }}" class="text-xs uppercase tracking-widest text-warm-500 hover:text-dark-800 transition-colors">
              {{ $currentLocale === 'en' ? 'Previous' : 'Předchozí' }}
            </a>
          @endif
          
          <span class="mx-4 text-warm-300">—</span>

          @if($posts->hasMorePages())
            <a href="{{ $posts->nextPageUrl() }}" class="text-xs uppercase tracking-widest text-warm-500 hover:text-dark-800 transition-colors">
              {{ $currentLocale === 'en' ? 'Next' : 'Další' }}
            </a>
          @else
            <span class="text-xs uppercase tracking-widest text-warm-300 cursor-not-allowed">
              {{ $currentLocale === 'en' ? 'Next' : 'Další' }}
            </span>
          @endif
        </div>
      </div>
    </div>
    @endif
  </div>
</div>

<!-- CTA Section -->
<div class="relative pt-12 sm:pt-16 lg:pt-20 pb-20 sm:pb-24 lg:pb-28" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
      <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase leading-tight sm:leading-[0.95]">
        {{ $currentLocale === 'en' ? 'Want to try our coffee?' : 'Chcete ochutnat naši kávu?' }}
      </h2>
      
      <p class="mb-10 sm:mb-12 text-lg sm:text-xl text-warm-500 max-w-2xl leading-relaxed font-light">
        {{ $currentLocale === 'en' ? 'Discover our selection of specialty coffee from the best European roasteries.' : 'Objevte naši kolekci výběrové kávy z nejlepších evropských pražíren.' }}
      </p>

      <a href="{{ localizedRoute('products.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
        <span>{{ $currentLocale === 'en' ? 'Browse coffee shop' : 'Prohlédnout obchod' }}</span>
        <span class="text-primary-500 group-hover:translate-x-1 transition-transform">→</span>
      </a>
    </div>
  </div>
</div>
@endsection
