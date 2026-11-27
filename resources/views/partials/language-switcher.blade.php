{{-- Přepínač jazyka kavi.cz ↔ kavibox.com: aktivní jazyk první, druhý je obyčejný odkaz na protějšek stránky.
     $variant: 'footer' (spodní lišta patičky) nebo 'menu' (mobilní menu) --}}
@php
    $lsCurrent = ($currentLocale ?? app()->getLocale()) === 'en' ? 'en' : 'cs';
    $lsOther = $lsCurrent === 'en' ? 'cs' : 'en';
    $lsLabels = ['cs' => 'CZ', 'en' => 'EN'];
    $lsNames = ['cs' => 'Čeština', 'en' => 'English'];
    $lsStyles = ($variant ?? 'footer') === 'menu'
        ? ['wrap' => 'flex items-center gap-2 py-3 uppercase tracking-wide', 'active' => 'text-dark-800 font-medium', 'sep' => 'text-dark-800/40', 'link' => 'text-dark-800/60 hover:text-primary-500 transition-colors duration-200']
        : ['wrap' => 'flex items-center gap-2', 'active' => 'text-white/80', 'sep' => 'text-white/20', 'link' => 'hover:text-white transition-colors duration-200'];
@endphp
<div class="{{ $lsStyles['wrap'] }}" role="group" aria-label="{{ $lsCurrent === 'en' ? 'Language' : 'Jazyk' }}">
    <span class="{{ $lsStyles['active'] }}" aria-current="true">{{ $lsLabels[$lsCurrent] }}</span>
    <span class="{{ $lsStyles['sep'] }}" aria-hidden="true">|</span>
    <a href="{{ \App\Helpers\HreflangHelper::switchUrl() }}" hreflang="{{ $lsOther }}" lang="{{ $lsOther }}" title="{{ $lsNames[$lsOther] }}" class="{{ $lsStyles['link'] }}">{{ $lsLabels[$lsOther] }}</a>
</div>
