{{--
    Recenze z Googlu: karty se zobáčkem a červený pruh pod nimi.

    reviews: GoogleReviewsService::latest(3)
    summary: GoogleReviewsService::summary() nebo null (pak se celkové hodnocení nezobrazí)

    Dokud nejsou aspoň tři recenze s textem, místo karet se zobrazí prosba o hodnocení.
    Odkaz na veřejný profil je v obou stavech.
--}}
@props([
    'reviews' => [],
    'summary' => null,
    'id' => 'recenze',
])

@php
    $hasReviews = count($reviews) >= 3;
    $reviewLink = config('services.review_links.' . $currentLocale);
@endphp

<section id="{{ $id }}" aria-label="{{ __('components.reviews.title') }}" class="relative scroll-mt-20">

    <div class="pt-16 sm:pt-20 lg:pt-24" style="background-color: #e5e6df;">
        <div class="max-w-screen-xl mx-auto px-4 md:px-8">

            <div class="text-center mb-12 sm:mb-16">
                <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 tracking-tight uppercase leading-tight sm:leading-[0.95]">
                    {{ $hasReviews ? __('components.reviews.title') : __('components.reviews.title_empty') }}
                </h2>
                @if($hasReviews && $summary)
                <p class="mt-4 text-xs uppercase tracking-widest text-warm-600">
                    <span aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733; · </span>{{ __('components.rating.out_of', ['rating' => \App\Helpers\RatingFormat::average($summary['average'])]) }} · {{ trans_choice('components.rating.count', $summary['count'], ['count' => $summary['count']]) }}
                </p>
                @endif
            </div>

            @if(! $hasReviews)
            <div class="max-w-3xl mx-auto pb-12 sm:pb-16">
                <div class="bg-dark-800 p-8 sm:p-12">
                    <p class="text-xs uppercase tracking-widest text-white/50 mb-6">{{ __('components.reviews.ask_label') }}</p>
                    <p class="text-white text-xl sm:text-2xl leading-relaxed font-light mb-10">{{ __('components.reviews.ask_text') }}</p>
                    <a href="{{ $reviewLink }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-3 bg-primary-500 hover:bg-white text-dark-800 font-display uppercase tracking-widest text-xs px-8 py-4 transition-colors duration-200">
                        <span>{{ __('components.reviews.ask_cta') }}</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
            @else
            <div class="grid lg:grid-cols-3 gap-6">
                @foreach($reviews as $review)
                <div class="flex flex-col relative z-10">
                    <div class="bg-dark-800 p-6 sm:p-8 flex-grow relative flex flex-col">
                        <p class="text-xs uppercase tracking-widest text-white/50 mb-4">{{ __('components.reviews.source') }}</p>
                        @if($review['text'])
                        <p class="text-white leading-relaxed font-light flex-grow flex items-center" style="font-size: 1.15rem;">
                            "{{ $review['text'] }}"
                        </p>
                        @endif
                        <div class="absolute -bottom-4 left-6 w-8 h-4 bg-dark-800" style="clip-path: polygon(0 0, 100% 0, 50% 100%);" aria-hidden="true"></div>
                    </div>
                    <div class="p-6 sm:p-8 pt-10 pb-0 flex items-center gap-4">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 overflow-hidden flex items-center justify-center">
                            @if($review['photo'])
                                {{-- Fotka se hotlinkuje z Google CDN, lokální kopie by spadala pod 30denní limit --}}
                                <img src="{{ $review['photo'] }}" alt="" loading="lazy" referrerpolicy="no-referrer" class="h-full w-full object-cover grayscale" />
                            @else
                                <span class="font-display text-warm-500 text-xl sm:text-2xl tracking-wide" aria-hidden="true">&#9679;</span>
                            @endif
                        </div>
                        <div>
                            <div class="font-display text-dark-800 uppercase tracking-wide">
                                {{ $review['author'] ?? __('components.reviews.anonymous') }}
                            </div>
                            @if($review['created_at'])
                            <p class="text-sm text-warm-600">{{ $review['created_at']->format('m/Y') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="relative z-20 px-6 sm:px-8 mb-[-2rem]">
                        <div class="inline-flex px-4 py-3" style="background-color: #e5e6df;">
                            <div class="flex gap-0.5 text-lg" aria-label="{{ $review['rating'] }}/5">
                                @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $review['rating'] ? 'text-dark-800' : 'text-warm-500/40' }}" aria-hidden="true">&#9733;</span>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Informační povinnost o původu recenzí podle zákona 634/1992 Sb.
                 ve znění novely Omnibus účinné od 6. 1. 2023. --}}
            <p class="text-xs text-warm-600 text-center mt-12 pb-4 max-w-2xl mx-auto leading-relaxed">{{ __('components.reviews.disclosure') }}</p>
            @endif

        </div>
    </div>

    <div class="bg-primary-500 pt-10 pb-8 sm:pb-10">
        <div class="max-w-screen-xl mx-auto px-4 md:px-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <p class="text-xs uppercase tracking-widest text-dark-800">
                {{ $hasReviews ? __('components.reviews.bar') : __('components.reviews.bar_empty') }}
            </p>
            <a href="{{ $reviewLink }}" target="_blank" rel="noopener" class="text-xs uppercase tracking-widest text-dark-800 hover:text-white transition-colors">
                {{ __('components.reviews.bar_link') }} &rarr;
            </a>
        </div>
    </div>

</section>
