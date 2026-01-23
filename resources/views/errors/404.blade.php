@extends('errors.layout')

@section('title', ($currentLocale ?? 'cs') === 'en' ? 'Page Not Found | KAVI' : 'Stránka nenalezena | KAVI.cz')

@section('content')
<div class="mx-auto max-w-screen-xl px-4 md:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <!-- Error Code -->
            <div class="mb-8">
                <span class="text-[120px] sm:text-[180px] md:text-[220px] font-bold leading-none text-red-600">
                    404
                </span>
            </div>

            <!-- Heading -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold uppercase tracking-tight mb-6 leading-[0.95]">
                {{ ($currentLocale ?? 'cs') === 'en' ? 'Page' : 'Stránka' }}<br>
                <span class="text-red-600">{{ ($currentLocale ?? 'cs') === 'en' ? 'Not Found' : 'Nenalezena' }}</span>
            </h1>

            <!-- Description -->
            <p class="text-lg md:text-xl text-[#1c1c1c]/70 mb-10 max-w-xl leading-relaxed">
                {{ ($currentLocale ?? 'cs') === 'en' 
                    ? 'The page you\'re looking for doesn\'t exist or has been moved. You may have mistyped the address, or the link is no longer valid.' 
                    : 'Stránka, kterou hledáte, neexistuje nebo byla přesunuta. Možná jste se překlepli v adrese, nebo odkaz už není platný.' }}
            </p>

            <!-- CTA Button -->
            <div class="flex flex-wrap gap-4 mb-12">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center bg-[#1c1c1c] text-white text-xs uppercase tracking-widest px-8 py-4 hover:bg-[#1c1c1c]/80 transition-colors">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Back to Home' : 'Zpět na úvod' }}
                </a>
                <a href="{{ localizedRoute('subscriptions.index') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-widest border-b border-[#1c1c1c] pb-1 hover:text-red-600 hover:border-red-600 transition-colors">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Coffee Boxes' : 'Kávové boxy' }}
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <!-- Quick Links -->
            <div class="pt-8 border-t border-[#1c1c1c]/20">
                <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50 mb-4">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Maybe you\'re looking for' : 'Možná hledáte' }}
                </p>
                <div class="flex flex-wrap gap-x-6 gap-y-2">
                    <a href="{{ localizedRoute('products.index') }}" class="text-sm hover:text-red-600 transition-colors">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Shop' : 'Obchod' }}
                    </a>
                    <a href="{{ localizedRoute('roasteries.index') }}" class="text-sm hover:text-red-600 transition-colors">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Roasteries' : 'Pražírny' }}
                    </a>
                    <a href="{{ localizedRoute('monthly-feature.index') }}" class="text-sm hover:text-red-600 transition-colors">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Coffee of the Month' : 'Káva měsíce' }}
                    </a>
                    <a href="{{ localizedRoute('how-it-works') }}" class="text-sm hover:text-red-600 transition-colors">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'How It Works' : 'Jak to funguje' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-[#BCBEB1] p-8 h-full">
                <h2 class="text-xs uppercase tracking-widest mb-6 border-b border-[#1c1c1c] pb-4">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Need Help?' : 'Potřebujete pomoc?' }}
                </h2>

                <div class="space-y-6">
                    <!-- Email -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/60 mb-1">Email</p>
                            <a href="mailto:{{ ($currentLocale ?? 'cs') === 'en' ? 'info@kavibox.com' : 'info@kavi.cz' }}" class="text-sm hover:text-red-600 transition-colors">
                                {{ ($currentLocale ?? 'cs') === 'en' ? 'info@kavibox.com' : 'info@kavi.cz' }}
                            </a>
                        </div>
                    </div>

                    <!-- Contact Page -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/60 mb-1">
                                {{ ($currentLocale ?? 'cs') === 'en' ? 'Contact Form' : 'Kontaktní formulář' }}
                            </p>
                            <a href="{{ localizedRoute('contact') }}" class="text-sm hover:text-red-600 transition-colors">
                                {{ ($currentLocale ?? 'cs') === 'en' ? 'Send us a message' : 'Napište nám' }}
                            </a>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/60 mb-1">
                                {{ ($currentLocale ?? 'cs') === 'en' ? 'Learn More' : 'Zjistěte více' }}
                            </p>
                            <a href="{{ localizedRoute('how-it-works') }}" class="text-sm hover:text-red-600 transition-colors">
                                {{ ($currentLocale ?? 'cs') === 'en' ? 'How It Works' : 'Jak to funguje' }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Error Info -->
                <div class="mt-8 pt-6 border-t border-[#1c1c1c]/20">
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Error Code' : 'Kód chyby' }}
                    </p>
                    <p class="text-sm mt-1">404 / {{ ($currentLocale ?? 'cs') === 'en' ? 'Not Found' : 'Nenalezeno' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
