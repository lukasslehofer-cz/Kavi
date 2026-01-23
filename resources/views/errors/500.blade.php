@extends('errors.layout')

@section('title', ($currentLocale ?? 'cs') === 'en' ? 'Something Went Wrong | KAVI' : 'Něco se pokazilo | KAVI.cz')

@section('content')
<div class="mx-auto max-w-screen-xl px-4 md:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <!-- Error Code -->
            <div class="mb-8">
                <span class="text-[120px] sm:text-[180px] md:text-[220px] font-bold leading-none text-red-600">
                    500
                </span>
            </div>

            <!-- Heading -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold uppercase tracking-tight mb-6 leading-[0.95]">
                {{ ($currentLocale ?? 'cs') === 'en' ? 'Something' : 'Něco se' }}<br>
                <span class="text-red-600">{{ ($currentLocale ?? 'cs') === 'en' ? 'Went Wrong' : 'Pokazilo' }}</span>
            </h1>

            <!-- Description -->
            <p class="text-lg md:text-xl text-[#1c1c1c]/70 mb-10 max-w-xl leading-relaxed">
                {{ ($currentLocale ?? 'cs') === 'en' 
                    ? 'Sorry, but there was a server error. Our team is already working on it. Please try again in a moment.' 
                    : 'Omlouváme se, ale na serveru došlo k chybě. Náš tým už na tom pracuje. Zkuste to prosím za chvíli znovu.' }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap gap-4 mb-12">
                <button onclick="window.location.reload()" class="inline-flex items-center justify-center bg-[#1c1c1c] text-white text-xs uppercase tracking-widest px-8 py-4 hover:bg-[#1c1c1c]/80 transition-colors">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Try Again' : 'Zkusit znovu' }}
                </button>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs uppercase tracking-widest border-b border-[#1c1c1c] pb-1 hover:text-red-600 hover:border-red-600 transition-colors">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Back to Home' : 'Zpět na úvod' }}
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <!-- Info -->
            <div class="pt-8 border-t border-[#1c1c1c]/20">
                <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50 mb-4">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'What can you do?' : 'Co můžete udělat?' }}
                </p>
                <ul class="space-y-2 text-sm text-[#1c1c1c]/70">
                    <li class="flex items-center gap-2">
                        <span class="text-red-600">●</span>
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Try refreshing the page' : 'Zkuste obnovit stránku' }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-red-600">●</span>
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Come back later' : 'Vraťte se později' }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-red-600">●</span>
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Contact us if the problem persists' : 'Kontaktujte nás, pokud problém přetrvává' }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-[#BCBEB1] p-8 h-full">
                <h2 class="text-xs uppercase tracking-widest mb-6 border-b border-[#1c1c1c] pb-4">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Contact Us' : 'Kontaktujte nás' }}
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

                    <!-- Status -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/60 mb-1">
                                {{ ($currentLocale ?? 'cs') === 'en' ? 'We\'re On It' : 'Pracujeme na tom' }}
                            </p>
                            <p class="text-sm text-[#1c1c1c]/70">
                                {{ ($currentLocale ?? 'cs') === 'en' ? 'Our team has been notified' : 'Náš tým byl informován' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Error Info -->
                <div class="mt-8 pt-6 border-t border-[#1c1c1c]/20">
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Error Code' : 'Kód chyby' }}
                    </p>
                    <p class="text-sm mt-1">500 / {{ ($currentLocale ?? 'cs') === 'en' ? 'Internal Server Error' : 'Interní chyba serveru' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
