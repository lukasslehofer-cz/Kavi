{{-- Minimální patička pro landing page z reklamy: copyright, kontakt a podmínky --}}
@php $footerEmail = \App\Helpers\Site::current()->contactEmail(); @endphp
<footer class="bg-black">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8 py-6 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-white/60 font-light">
        <p>© {{ date('Y') }} {{ \App\Helpers\Site::current()->name() }}. {{ __('layout.footer.rights') }}</p>
        <div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
            <a href="mailto:{{ $footerEmail }}" class="hover:text-white transition-colors duration-200">{{ $footerEmail }}</a>
            <a href="{{ localizedRoute('terms-of-service') }}" class="hover:text-white transition-colors duration-200">{{ __('layout.footer.terms') }}</a>
            <a href="{{ localizedRoute('privacy-policy') }}" class="hover:text-white transition-colors duration-200">{{ __('layout.footer.privacy') }}</a>
        </div>
    </div>
</footer>
