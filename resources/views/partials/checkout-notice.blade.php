{{-- Informační hláška v pokladně (AnnouncementBanner). Očekává $notice. --}}
@php $notice = $notice ?? null; @endphp
@if($notice)
<div class="bg-dark-800 px-6 py-5 sm:px-8 sm:py-6">
    <div class="flex items-start gap-4">
        <svg class="w-6 h-6 text-primary-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $notice->getIconPath() }}" />
        </svg>
        <div>
            <p class="text-sm uppercase tracking-widest text-primary-400 mb-2">
                {{ $notice->getTitle($currentLocale ?? 'cs') ?? __('checkout.announcement.default_title') }}
            </p>
            <p class="text-base text-white leading-relaxed">{{ $notice->getMessage($currentLocale ?? 'cs') }}</p>
        </div>
    </div>
</div>
@endif
