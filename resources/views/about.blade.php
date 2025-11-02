@extends('layouts.app')

@section('title', 'O nás - Jak vzniklo KAVI | KAVI.cz')

@section('content')

<!-- Hero Section -->
<div class="relative bg-gray-100 py-16 md:py-20 overflow-hidden">
    <!-- Subtle Organic Shapes -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary-100 rounded-full"></div>
        <div class="absolute -bottom-32 -left-32 w-[36rem] h-[36rem] bg-primary-50 rounded-full hidden md:block"></div>
    </div>
    
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <!-- Minimal Badge -->
            <div class="inline-flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 mb-6">
                <svg class="w-4 h-4 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-900">Seznamte se</span>
            </div>
            
            <!-- Clean Heading -->
            <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                Jak vzniklo KAVI?
            </h1>
            
            <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light mb-8">
                Příběh o lásce ke kávě a touze sdílet ty nejlepší chutě
            </p>
        </div>
    </div>
    
    <!-- Wave Divider -->
    <div class="absolute bottom-[-1px] left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 80L60 73C120 67 240 53 360 48C480 43 600 47 720 53C840 59 960 67 1080 69C1200 71 1320 67 1380 65L1440 63V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#ffffff"/>
        </svg>
    </div>
</div>

<!-- Story Section -->
<div class="relative bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center max-w-6xl mx-auto">
            <!-- Image -->
            <div class="order-2 lg:order-1">
                <div class="relative rounded-2xl overflow-hidden">
                    <img 
                        src="/images/lukas.jpg" 
                        alt="Lukáš Šlehofer - zakladatel KAVI" 
                        class="w-full h-auto object-cover"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <!-- Fallback if image doesn't exist -->
                    <div class="hidden w-full aspect-[4/3] bg-gradient-to-br from-primary-500 to-amber-500 items-center justify-center">
                        <svg class="w-24 h-24 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="order-1 lg:order-2 space-y-6">

                <p class="text-lg text-gray-600 leading-relaxed font-light">
                    Během své pracovní kariéry v <strong class="text-gray-900 font-medium">reklamní agentuře</strong> potřeboval náš zakladatel Lukáš pravidelné dávky kofeinu pro ideální pracovní výkon. A právě v té době objevil kouzlo <strong class="text-gray-900 font-medium">výběrové kávy</strong>.
                </p>

                <p class="text-lg text-gray-600 leading-relaxed font-light">
                    Poháněn nově objevenou zvědavostí dozvědět se co nejvíce o výběrové kávě a odhalit co nejvíce kaváren v ČR, které ji podávaly, založil KAVI jako místo, kde bude sdílet svá osobní doporučení kaváren.
                </p>

                <p class="text-lg text-gray-600 leading-relaxed font-light">
                    Z běžného kavárenského povaleče se poté stal <strong class="text-gray-900 font-medium">baristou</strong> a rozvíjel své znalosti ve světě výběrové kávy. A posledním krokem je vznik kávového předplatného s jediným jednoduchým cílem: <strong class="text-gray-900 font-medium">sdílet ty nejchutnější kávy na světě s lidmi, kteří milují kávu</strong>.
                </p>

                <div class="pt-4">
                    <div class="bg-primary-50 rounded-2xl p-6 border border-primary-100">
                        <p class="text-gray-900 font-light leading-relaxed">
                            Od svého startu KAVI neustále posiluje svou pozici a pevně se etabluje jako oblíbená služba předplatného kávy se zákazníky po celé ČR.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Values Section -->
<div class="relative bg-white py-20 lg:py-28">
    <!-- Organic shape decoration -->
    <div class="absolute top-20 right-0 w-72 h-72 bg-primary-50 rounded-full -translate-y-1/2 translate-x-1/2 opacity-60"></div>
    
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="max-w-6xl mx-auto -mt-12 lg:-mt-16">
            <div class="mb-16 max-w-2xl">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">Naše hodnoty</h2>
                <p class="text-xl text-gray-600 font-light">Co je pro nás důležité a čím se řídíme</p>
            </div>

            <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Value 1 -->
            <div class="group">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-gray-900 text-white">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mb-3 text-2xl font-semibold text-gray-900">Kvalita na prvním místě</h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Vybíráme pouze tu nejlepší výběrovou kávu od renomovaných evropských pražíren.
                </p>
            </div>

            <!-- Value 2 -->
            <div class="group">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-primary-500 text-white">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="mb-3 text-2xl font-semibold text-gray-900">Objevování nových chutí</h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Každý měsíc vám přinášíme nové kávové zážitky a pomáháme objevovat svět výběrové kávy.
                </p>
            </div>

            <!-- Value 3 -->
            <div class="group">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-gray-900 text-white">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mb-3 text-2xl font-semibold text-gray-900">Ekologický přístup</h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Používáme 100% recyklovatelné obaly a dbáme na udržitelnost v celém řetězci.
                </p>
            </div>
            </div>
        </div>
    </div>
    
    <!-- Wave Divider -->
    <div class="absolute bottom-[-1px] left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 80L60 75C120 70 240 60 360 55C480 50 600 50 720 53.3C840 56.7 960 63.3 1080 65C1200 66.7 1320 63.3 1380 61.7L1440 60V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#F3F4F6"/>
        </svg>
    </div>
</div>

<!-- CTA Section - Clean Minimal -->
<div class="relative bg-gray-100 py-20 lg:py-24">
    
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="mx-auto flex max-w-2xl flex-col items-center text-center">
            <!-- Heading -->
            <h2 class="mb-6 text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight tracking-tight">
                Začněte svou kávovou cestu ještě dnes
            </h2>

            <p class="mb-10 text-lg text-gray-600 max-w-xl leading-relaxed font-light">
                Získejte přístup k nejlepší kávě z celé Evropy. Flexibilní předplatné, bez závazků.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('subscriptions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-3 rounded-full transition-all duration-200">
                    <span>Vybrat předplatné</span>
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>

                <a href="{{ route('products.index') }}" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium px-8 py-3 rounded-full border border-gray-200 transition-all duration-200">
                    <span>Procházet kávy</span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

