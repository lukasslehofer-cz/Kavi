{{-- Přepínač jazyka mezi weby z config/sites.php: aktivní jazyk první, ostatní zapnuté weby jsou obyčejné odkazy na protějšek stránky.
     $variant: 'footer' (spodní lišta patičky) nebo 'menu' (mobilní menu) --}}
@php
    $lsCurrent = \App\Helpers\Site::current();
    $lsOthers = \App\Helpers\HreflangHelper::switchUrls();
    $lsStyles = ($variant ?? 'footer') === 'menu'
        ? ['wrap' => 'flex items-center gap-2 py-3 uppercase tracking-wide', 'active' => 'text-dark-800 font-medium', 'sep' => 'text-dark-800/40', 'link' => 'text-dark-800/60 hover:text-primary-500 transition-colors duration-200']
        : ['wrap' => 'flex items-center gap-2', 'active' => 'text-white/80', 'sep' => 'text-white/20', 'link' => 'hover:text-white transition-colors duration-200'];
@endphp
<div class="{{ $lsStyles['wrap'] }}" role="group" aria-label="{{ __('components.language_switcher.label') }}">
    <span class="{{ $lsStyles['active'] }}" aria-current="true">{{ $lsCurrent->label() }}</span>
@foreach($lsOthers as $lsLocale => $lsUrl)
@php $lsSite = \App\Helpers\Site::forLocale($lsLocale); @endphp
    <span class="{{ $lsStyles['sep'] }}" aria-hidden="true">|</span>
    <a href="{{ $lsUrl }}" hreflang="{{ $lsLocale }}" lang="{{ $lsLocale }}" title="{{ $lsSite->languageName() }}" class="{{ $lsStyles['link'] }}">{{ $lsSite->label() }}</a>
@endforeach
</div>
