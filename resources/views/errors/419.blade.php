@extends('errors.layout')

@section('title', ($currentLocale ?? 'cs') === 'en' ? 'Page Expired | KAVI' : 'Platnost stránky vypršela | KAVI.cz')

@section('content')
<div class="mx-auto max-w-screen-xl px-4 md:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <!-- Error Code -->
            <div class="mb-8">
                <span class="text-[120px] sm:text-[180px] md:text-[220px] font-bold leading-none text-red-600">
                    419
                </span>
            </div>

            <!-- Heading -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold uppercase tracking-tight mb-6 leading-[0.95]">
                {{ ($currentLocale ?? 'cs') === 'en' ? 'Page' : 'Platnost' }}<br>
                <span class="text-red-600">{{ ($currentLocale ?? 'cs') === 'en' ? 'Expired' : 'Vypršela' }}</span>
            </h1>

            <!-- Description -->
            <p class="text-lg md:text-xl text-[#1c1c1c]/70 mb-10 max-w-xl leading-relaxed">
                {{ ($currentLocale ?? 'cs') === 'en' 
                    ? 'Your session has expired for security reasons. Please refresh the page and try again.' 
                    : 'Vaše relace vypršela z bezpečnostních důvodů. Obnovte prosím stránku a zkuste to znovu.' }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap gap-4 mb-12">
                <button onclick="window.location.reload()" class="inline-flex items-center justify-center bg-[#1c1c1c] text-white text-xs uppercase tracking-widest px-8 py-4 hover:bg-[#1c1c1c]/80 transition-colors">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Refresh Page' : 'Obnovit stránku' }}
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
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Why did this happen?' : 'Proč se to stalo?' }}
                </p>
                <p class="text-sm text-[#1c1c1c]/70 max-w-lg">
                    {{ ($currentLocale ?? 'cs') === 'en' 
                        ? 'This error occurs when a page has been open for too long without activity. It\'s a security feature to protect your data. Simply refresh the page to continue.' 
                        : 'K této chybě dochází, když stránka zůstane otevřená příliš dlouho bez aktivity. Je to bezpečnostní opatření pro ochranu vašich dat. Stačí stránku obnovit a pokračovat.' }}
                </p>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-[#BCBEB1] p-8 h-full">
                <h2 class="text-xs uppercase tracking-widest mb-6 border-b border-[#1c1c1c] pb-4">
                    {{ ($currentLocale ?? 'cs') === 'en' ? 'Quick Fix' : 'Rychlé řešení' }}
                </h2>

                <div class="space-y-6">
                    <!-- Step 1 -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">01</span>
                        <div>
                            <p class="text-sm">
                                {{ ($currentLocale ?? 'cs') === 'en' ? 'Click the "Refresh Page" button above' : 'Klikněte na tlačítko "Obnovit stránku" výše' }}
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">02</span>
                        <div>
                            <p class="text-sm">
                                {{ ($currentLocale ?? 'cs') === 'en' ? 'Or press Ctrl+R (Cmd+R on Mac)' : 'Nebo stiskněte Ctrl+R (Cmd+R na Macu)' }}
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">03</span>
                        <div>
                            <p class="text-sm">
                                {{ ($currentLocale ?? 'cs') === 'en' ? 'Fill out the form again' : 'Vyplňte formulář znovu' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Error Info -->
                <div class="mt-8 pt-6 border-t border-[#1c1c1c]/20">
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50">
                        {{ ($currentLocale ?? 'cs') === 'en' ? 'Error Code' : 'Kód chyby' }}
                    </p>
                    <p class="text-sm mt-1">419 / {{ ($currentLocale ?? 'cs') === 'en' ? 'Session Expired' : 'Relace vypršela' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
