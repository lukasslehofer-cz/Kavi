{{-- Minimální hlavička pro landing page z reklamy: logo a CTA (odkaz CTA nastaví stránka v sekci minimal_cta_href) --}}
<header class="border-b border-black" style="background-color: #e5e6df;">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="flex items-center justify-between gap-6 h-16">
            <a href="{{ route('home') }}" class="flex items-center" aria-label="{{ ($currentLocale ?? 'cs') === 'en' ? 'KAVI, home page' : 'KAVI.cz, úvodní stránka' }}">
                <img src="/images/kavi-logo-black.png" alt="{{ ($currentLocale ?? 'cs') === 'en' ? 'KAVI' : 'KAVI.cz' }}" class="h-9 w-auto">
            </a>
            <a href="@yield('minimal_cta_href', localizedRoute('subscriptions.index'))" class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 text-white text-sm px-5 py-2.5 uppercase tracking-wide transition-all duration-200">
                <span>{{ ($currentLocale ?? 'cs') === 'en' ? 'Build Your Box' : 'Sestavte si box' }}</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</header>
