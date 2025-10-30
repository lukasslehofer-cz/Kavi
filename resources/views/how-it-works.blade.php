@extends('layouts.app')

@section('title', 'Jak to funguje - Kavi Coffee')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-900">Nápověda & FAQ</span>
            </div>
            
            <!-- Clean Heading -->
            <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                Jak to funguje?
            </h1>
            
            <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light mb-8">
                Vše, co potřebujete vědět o kávovém předplatném
            </p>
            
            <!-- Quick Links -->
            <div class="flex flex-wrap justify-center gap-3">
                <a href="#predplatne" class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium px-6 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200">
                    <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Předplatné</span>
                </a>
                <a href="#vyber-kavy" class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium px-6 py-3 rounded-full border border-gray-200 hover:border-gray-300 transition-all duration-200">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    <span>Výběr kávy</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Wave Divider -->
    <div class="absolute bottom-[-1px] left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 80L60 73C120 67 240 53 360 48C480 43 600 47 720 53C840 59 960 67 1080 69C1200 71 1320 67 1380 65L1440 63V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#ffffff"/>
        </svg>
    </div>
</div>

<!-- FAQ Section 1: Předplatné a doprava -->
<div id="predplatne" class="relative bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Section Header -->
        <div class="mb-16 text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-900 rounded-full mb-6">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Předplatné a doprava
            </h2>
            <p class="text-lg text-gray-600 font-light">
                Vše o objednávkách, platbách a doručení
            </p>
        </div>

        <!-- FAQ Items -->
        <div class="grid md:grid-cols-2 gap-6 max-w-6xl mx-auto">
            <!-- FAQ Item 1 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">1</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Jak funguje předplatné kávy?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Předplatné si jednoduše nastavíte online – zvolíte typ boxu (espresso nebo filtr), vyberete si místo doručení a my se postaráme o zbytek. Každý měsíc vám automaticky dorazí čerstvý výběr káv na vámi zadané místo.
                </p>
            </div>

            <!-- FAQ Item 2 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">2</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Kdy mi dorazí balíček s kávou?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Objednávky pro daný měsíc končí vždy <strong class="text-gray-900 font-medium">15.</strong> Kávové boxy odesíláme v druhé polovině měsíce, nejpozději <strong class="text-gray-900 font-medium">28.</strong> Pokud si svůj první kávový box objednáte během 1.-15., první zásilka kávy vám přijde hned v nejbližší várce.
                </p>
            </div>

            <!-- FAQ Item 3 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">3</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Jakou dopravu nabízíte?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Doručujeme do <strong class="text-gray-900 font-medium">Z-boxů a na Z-pointy</strong> (výdejní místa) společnosti Zásilkovna. Jiná doprava není v tuto chvíli možná.
                </p>
            </div>

            <!-- FAQ Item 4 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">4</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Jak probíhá platba za předplatné?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Platba probíhá automaticky každý <strong class="text-gray-900 font-medium">15. den v měsíci</strong> formou opakované platby přes vaši platební kartu, kterou jste zadali při objednávce. O každé platbě dostanete potvrzení na e-mail.
                </p>
            </div>

            <!-- FAQ Item 5 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">5</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Co když mi nedorazí balíček?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Pokud se zásilka zpozdí nebo nedorazí, kontaktujte nás a vše rychle vyřešíme. Balíčky jsou vždy sledované.
                </p>
            </div>

            <!-- FAQ Item 6 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">6</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Dostanu kávu i když si nevyzvednu balíček včas?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Pokud si balíček nestihnete vyzvednout, Zásilkovna ho automaticky vrátí zpět. Následně vás kontaktujeme a domluvíme se na novém odeslání.
                </p>
            </div>

            <!-- FAQ Item 7 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-primary-600">7</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Posíláte i do zahraničí?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Momentálně doručujeme pouze v rámci České republiky přes Zásilkovnu.
                </p>
            </div>

            <!-- FAQ Item 8 - Featured -->
            <div class="group relative bg-primary-500 rounded-2xl p-8 hover:bg-primary-600 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-white">8</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-4 pr-14">
                    Je možné předplatné změnit či zrušit?
                </h3>
                <p class="text-white/90 leading-relaxed font-light">
                    Pochopitelně! Naše předplatné můžete kdykoliv zrušit nebo pozastavit. Stejně tak upravíme doručovací adresu. Stačí nás kontaktovat prostřednictvím vašeho zákaznického účtu nebo e-mailu.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section 2: Výběr kávy -->
<div id="vyber-kavy" class="relative bg-gray-100 py-20 lg:py-28">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <!-- Section Header -->
        <div class="mb-16 text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-900 rounded-full mb-6">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Výběr kávy pro předplatné
            </h2>
            <p class="text-lg text-gray-600 font-light">
                Jak vybíráme kávy a co můžete očekávat
            </p>
        </div>

        <!-- FAQ Items -->
        <div class="grid md:grid-cols-2 gap-6 max-w-6xl mx-auto">
            <!-- FAQ Item 1 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">1</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Jaké druhy kávy zařazujete do předplatného?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Vybíráme pouze tu <strong class="text-gray-900 font-medium">nejkvalitnější výběrovou kávu</strong> od těch nejzajímavějších pražíren z celé Evropy. Některé kupují kávu přímo od farmářů, jiné spolupracují s renomovanými dovozci. V současné době nenabízíme bezkofeinovou kávu ani kávu robusta.
                </p>
            </div>

            <!-- FAQ Item 2 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">2</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Jak vybíráte kávu?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Náš tým průběžně ochutnává kávy jednotlivých pražíren. Kávy dělíme do kategorií podle chuťového profilu a zařazujeme je v takové kombinaci, abyste dostali optimální chuťový zážitek. Kávy jsou vybírány čistě na základě řemesla, kvality a chuti.
                </p>
            </div>

            <!-- FAQ Item 3 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">3</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Můžete mi kávu dodat mletou?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Ne. Abychom zajistili čerstvost, dodáváme pouze <strong class="text-gray-900 font-medium">celá zrnka</strong>. Kávy dostanete v originálních vzduchotěsných sáčcích, abychom zachovali kávu čerstvou až do okamžiku, kdy si ji doma otevřete.
                </p>
            </div>

            <!-- FAQ Item 4 - Featured -->
            <div class="group relative bg-amber-500 rounded-2xl p-8 hover:bg-amber-600 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-white">4</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-4 pr-14">
                    Jaký je rozdíl mezi espresso a filtr boxem?
                </h3>
                <p class="text-white/90 leading-relaxed font-light">
                    <strong class="text-white font-medium">Espresso box</strong> obsahuje kávy ideální pro přípravu v kávovaru nebo moka konvičce – s vyšší intenzitou. <strong class="text-white font-medium">Filtr box</strong> je laděný pro V60, Chemex nebo Aeropress – s jemnější chutí.
                </p>
            </div>

            <!-- FAQ Item 5 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">5</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Můžu si vybrat konkrétní kávy?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Ne – překvapení je součástí kouzla předplatného. Každý měsíc ochutnáte něco nového, co byste si možná sami nevybrali.
                </p>
            </div>

            <!-- FAQ Item 6 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">6</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Jak čerstvá je káva, kterou dostanu?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Káva je vždy <strong class="text-gray-900 font-medium">čerstvě pražená</strong> – odebíráme ji přímo z pražíren těsně před odesláním vašich balíčků.
                </p>
            </div>

            <!-- FAQ Item 7 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">7</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Co když mi káva nechutná?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Chápeme, že chutě jsou individuální. Naším cílem je objevování. Pokud vám něco nesedne, dejte nám vědět – zpětná vazba nám pomáhá zlepšovat výběr.
                </p>
            </div>

            <!-- FAQ Item 8 -->
            <div class="group relative bg-white rounded-2xl p-8 border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="absolute top-8 right-8 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-lg font-medium text-amber-600">8</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 pr-14">
                    Jak velká balení kávy dostanu?
                </h3>
                <p class="text-gray-600 leading-relaxed font-light">
                    Standardně dodáváme <strong class="text-gray-900 font-medium">3 balení kávy po 250g</strong>. Ve výjimečných případech může být hmotnost sáčků odlišná, ale vždy v součtu minimálně 750g.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="relative bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="mb-16 max-w-2xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Proč zvolit Kavi?
            </h2>
            <p class="text-lg text-gray-600 font-light">
                Výhody našeho kávového předplatného
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-3 max-w-5xl mx-auto">
            <!-- Benefit 1 -->
            <div class="flex flex-col items-center text-center p-8 bg-white rounded-2xl border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-gray-900">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Ekologické balení</h3>
                <p class="text-gray-600 font-light leading-relaxed">Baleno do papíru, 100% recyklovatelné</p>
            </div>

            <!-- Benefit 2 -->
            <div class="flex flex-col items-center text-center p-8 bg-white rounded-2xl border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-gray-900">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Snadné změny</h3>
                <p class="text-gray-600 font-light leading-relaxed">Změna, pauza nebo zrušení kdykoliv</p>
            </div>

            <!-- Benefit 3 -->
            <div class="flex flex-col items-center text-center p-8 bg-white rounded-2xl border border-gray-200 hover:border-gray-300 transition-all duration-300">
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-gray-900">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Bezpečná platba</h3>
                <p class="text-gray-600 font-light leading-relaxed">Chráněno Stripe platební bránou</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="relative bg-gray-900 py-20 lg:py-28 overflow-hidden">
    <!-- Organic Shape Decoration -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-primary-500 rounded-full blur-3xl opacity-10"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-amber-500 rounded-full blur-3xl opacity-10"></div>
    
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="mx-auto flex max-w-3xl flex-col items-center text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-5 py-2 mb-8">
                <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="text-sm font-medium text-white">Potřebujete pomoc?</span>
            </div>
            
            <h2 class="mb-6 text-3xl md:text-4xl font-bold text-white leading-tight">
                Máte další otázky?
            </h2>
            <p class="mb-10 text-lg text-gray-300 max-w-2xl leading-relaxed font-light">
                Kontaktujte nás a my vám rádi odpovíme na všechny vaše dotazy ohledně předplatného nebo kávy.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="mailto:info@kavi.cz" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-100 text-gray-900 font-medium px-8 py-4 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Napište nám</span>
                </a>
                <a href="{{ route('subscriptions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-4 rounded-full transition-all duration-200">
                    <span>Vybrat předplatné</span>
                    <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
