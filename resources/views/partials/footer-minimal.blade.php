{{-- Minimální patička pro landing page z reklamy: copyright, kontakt a podmínky --}}
@php $footerEmail = ($currentLocale ?? 'cs') === 'en' ? 'info@kavibox.com' : 'info@kavi.cz'; @endphp
<footer class="bg-black">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8 py-6 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-white/60 font-light">
        <p>© {{ date('Y') }} {{ ($currentLocale ?? 'cs') === 'en' ? 'KAVI' : 'KAVI.cz' }}. {{ ($currentLocale ?? 'cs') === 'en' ? 'All rights reserved.' : 'Všechna práva vyhrazena.' }}</p>
        <div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
            <a href="mailto:{{ $footerEmail }}" class="hover:text-white transition-colors duration-200">{{ $footerEmail }}</a>
            <a href="{{ localizedRoute('terms-of-service') }}" class="hover:text-white transition-colors duration-200">{{ ($currentLocale ?? 'cs') === 'en' ? 'Terms of Service' : 'Obchodní podmínky' }}</a>
            <a href="{{ localizedRoute('privacy-policy') }}" class="hover:text-white transition-colors duration-200">{{ ($currentLocale ?? 'cs') === 'en' ? 'Privacy Policy' : 'Ochrana osobních údajů' }}</a>
        </div>
    </div>
</footer>
