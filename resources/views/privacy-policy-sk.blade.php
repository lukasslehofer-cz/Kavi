@extends('layouts.app')

@section('title', 'Zásady ochrany osobných údajov - KAVIbox.sk')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span class="text-sm font-medium text-gray-900">GDPR</span>
            </div>
            
            <!-- Clean Heading -->
            <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                Zásady ochrany osobných údajov
            </h1>
            
            <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light">
                Informácie o spracúvaní a ochrane vašich osobných údajov
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

<!-- Content Section -->
<div class="relative bg-white py-20">
    <div class="mx-auto max-w-4xl px-4 md:px-8">
        
        <!-- I. Základné ustanovenia -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">I</span>
                Základné ustanovenia
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Prevádzkovateľom osobných údajov podľa čl. 4 bodu 7 nariadenia Európskeho parlamentu a Rady (EÚ) 2016/679 o ochrane fyzických osôb pri spracúvaní osobných údajov a o voľnom pohybe takýchto údajov (ďalej len „GDPR“) je <strong class="font-medium text-gray-900">Lukáš Šlehofer, IČO 66899095</strong> so sídlom Kurzova 2222/16, Praha 5, 155 00, Česká republika, zapísaný v živnostenskom registri vedenom Úradom mestskej časti Praha 13 (ďalej len „prevádzkovateľ“).
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">2)</strong> Kontaktné údaje prevádzkovateľa sú:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            adresa: Lukáš Šlehofer, Kurzova 2222/16, Praha 5, 15500, Česká republika
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            e-mail: <a href="mailto:info@kavibox.sk" class="text-primary-600 hover:text-primary-700 font-medium">info@kavibox.sk</a>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Osobnými údajmi sa rozumejú všetky informácie o identifikovanej alebo identifikovateľnej fyzickej osobe; identifikovateľnou fyzickou osobou je fyzická osoba, ktorú možno priamo alebo nepriamo identifikovať, najmä odkazom na určitý identifikátor, napríklad meno, identifikačné číslo, lokalizačné údaje, online identifikátor, alebo na jeden či viacero osobitných prvkov fyzickej, fyziologickej, genetickej, psychickej, ekonomickej, kultúrnej alebo sociálnej identity tejto fyzickej osoby.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> Prevádzkovateľ nevymenoval zodpovednú osobu na ochranu osobných údajov.
                    </p>
                </div>
            </div>
        </section>

        <!-- II. Zdroje a kategórie spracúvaných osobných údajov -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">II</span>
                Zdroje a kategórie spracúvaných osobných údajov
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">1)</strong> Prevádzkovateľ spracúva osobné údaje, ktoré ste mu poskytli, alebo osobné údaje, ktoré prevádzkovateľ získal na základe plnenia Vašej objednávky:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                            meno a priezvisko
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                            e-mailová adresa
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                            poštová adresa
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Prevádzkovateľ spracúva Vaše identifikačné a kontaktné údaje a údaje nevyhnutné na plnenie zmluvy.
                    </p>
                </div>
            </div>
        </section>

        <!-- III. Právny základ a účel spracúvania osobných údajov -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">III</span>
                Právny základ a účel spracúvania osobných údajov
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">1)</strong> Právnym základom spracúvania osobných údajov je:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>plnenie zmluvy medzi Vami a prevádzkovateľom podľa čl. 6 ods. 1 písm. b) GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>splnenie zákonnej povinnosti prevádzkovateľa podľa čl. 6 ods. 1 písm. c) GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>oprávnený záujem prevádzkovateľa na poskytovaní priameho marketingu (najmä na zasielanie obchodných oznámení a newsletterov) podľa čl. 6 ods. 1 písm. f) GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>Váš súhlas so spracúvaním na účely poskytovania priameho marketingu (najmä na zasielanie obchodných oznámení a newsletterov) podľa čl. 6 ods. 1 písm. a) GDPR v spojení s § 7 ods. 2 zákona č. 480/2004 Sb., o niektorých službách informačnej spoločnosti, v prípade, že nedošlo k objednávke tovaru alebo služby.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">2)</strong> Účelom spracúvania osobných údajov je:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>vybavenie Vašej objednávky a výkon práv a povinností vyplývajúcich zo zmluvného vzťahu medzi Vami a prevádzkovateľom; pri objednávke sa vyžadujú osobné údaje, ktoré sú nevyhnutné na úspešné vybavenie objednávky (meno a adresa, kontakt), poskytnutie osobných údajov je nevyhnutnou požiadavkou na uzavretie a plnenie zmluvy, bez poskytnutia osobných údajov nie je možné zmluvu uzavrieť ani ju zo strany prevádzkovateľa plniť,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>plnenie zákonných povinností voči štátu,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>zasielanie obchodných oznámení a vykonávanie ďalších marketingových aktivít.</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>Zo strany prevádzkovateľa nedochádza k automatizovanému individuálnemu rozhodovaniu v zmysle čl. 22 GDPR.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- IV. Doba uchovávania údajov -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">IV</span>
                Doba uchovávania údajov
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">1)</strong> Prevádzkovateľ uchováva osobné údaje:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>počas doby nevyhnutnej na výkon práv a povinností vyplývajúcich zo zmluvného vzťahu medzi Vami a prevádzkovateľom a na uplatňovanie nárokov z týchto zmluvných vzťahov (počas 15 rokov od ukončenia zmluvného vzťahu).</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>do odvolania súhlasu so spracúvaním osobných údajov na účely marketingu, najdlhšie 15 rokov, ak sú osobné údaje spracúvané na základe súhlasu.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Po uplynutí doby uchovávania osobných údajov prevádzkovateľ osobné údaje vymaže.
                    </p>
                </div>
            </div>
        </section>

        <!-- V. Príjemcovia osobných údajov -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">V</span>
                Príjemcovia osobných údajov (subdodávatelia prevádzkovateľa)
            </h2>
            
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <p class="text-gray-700 font-light leading-relaxed mb-3">
                    <strong class="font-medium text-gray-900">1)</strong> Príjemcami osobných údajov sú osoby:
                </p>
                <ul class="space-y-2 ml-8">
                    <li class="text-gray-700 font-light flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                        <span>podieľajúce sa na dodaní tovaru/služieb/realizácii platieb na základe zmluvy,</span>
                    </li>
                    <li class="text-gray-700 font-light flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                        <span>zabezpečujúce služby prevádzkovania e-shopu a ďalšie služby v súvislosti s prevádzkovaním e-shopu,</span>
                    </li>
                    <li class="text-gray-700 font-light flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                        <span>zabezpečujúce marketingové služby.</span>
                    </li>
                    <li class="text-gray-700 font-light flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                        <span>prevádzkovateľ má v úmysle preniesť osobné údaje do tretej krajiny (do krajiny mimo EÚ) alebo medzinárodnej organizácii. Príjemcami osobných údajov v tretích krajinách sú poskytovatelia e-shopových služieb, mailingových služieb a cloudových služieb.</span>
                    </li>
                </ul>
            </div>
        </section>

        <!-- VI. Sprostredkovatelia osobných údajov -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VI</span>
                Sprostredkovatelia osobných údajov
            </h2>
            
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <p class="text-gray-700 font-light leading-relaxed mb-3">
                    <strong class="font-medium text-gray-900">1)</strong> Spracúvanie osobných údajov vykonáva prevádzkovateľ, osobné údaje však pre neho môžu spracúvať aj títo sprostredkovatelia:
                </p>
                <ul class="space-y-2 ml-8">
                    <li class="text-gray-700 font-light flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                        poskytovateľ služby Mailchimp,
                    </li>
                    <li class="text-gray-700 font-light flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                        poskytovateľ služby Printify,
                    </li>
                    <li class="text-gray-700 font-light flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                        prípadne ďalší poskytovatelia softvéru, služieb a aplikácií na spracúvanie údajov, ktoré však prevádzkovateľ v súčasnosti nevyužíva.
                    </li>
                </ul>
            </div>
        </section>

        <!-- VII. Vaše práva -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VII</span>
                Vaše práva
            </h2>
            
            <div class="space-y-6">
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">1)</strong> Za podmienok stanovených v GDPR máte:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo na prístup k svojim osobným údajom podľa čl. 15 GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo na opravu osobných údajov podľa čl. 16 GDPR, prípadne na obmedzenie spracúvania podľa čl. 18 GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo na vymazanie osobných údajov podľa čl. 17 GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo namietať proti spracúvaniu podľa čl. 21 GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo na prenosnosť údajov podľa čl. 20 GDPR a</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo odvolať súhlas so spracúvaním písomne alebo elektronicky na adresu alebo e-mail prevádzkovateľa uvedený v čl. I týchto podmienok.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Ďalej máte právo podať sťažnosť Úradu pre ochranu osobných údajov Českej republiky v prípade, že sa domnievate, že bolo porušené Vaše právo na ochranu osobných údajov, prípadne sa obrátiť na súd.
                    </p>
                </div>
            </div>
        </section>

        <!-- VIII. Podmienky zabezpečenia osobných údajov -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VIII</span>
                Podmienky zabezpečenia osobných údajov
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Prevádzkovateľ vyhlasuje, že prijal všetky vhodné technické a organizačné opatrenia na zabezpečenie osobných údajov.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Prevádzkovateľ prijal technické opatrenia na zabezpečenie dátových úložísk a úložísk osobných údajov v listinnej podobe.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Prevádzkovateľ vyhlasuje, že k osobným údajom majú prístup iba ním poverené osoby.
                    </p>
                </div>
            </div>
        </section>

        <!-- IX. Záverečné ustanovenia -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">IX</span>
                Záverečné ustanovenia
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Odoslaním objednávky z internetového objednávkového formulára potvrdzujete, že ste sa oboznámili s podmienkami ochrany osobných údajov a že ich v celom rozsahu prijímate.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> S týmito podmienkami súhlasíte zaškrtnutím súhlasu prostredníctvom internetového formulára. Zaškrtnutím súhlasu potvrdzujete, že ste sa oboznámili s podmienkami ochrany osobných údajov a že ich v celom rozsahu prijímate.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Prevádzkovateľ je oprávnený tieto podmienky zmeniť. Novú verziu podmienok ochrany osobných údajov zverejní na svojich internetových stránkach a zároveň Vám zašle novú verziu týchto podmienok na Vašu e-mailovú adresu, ktorú ste prevádzkovateľovi poskytli.
                    </p>
                </div>
            </div>
        </section>

        <!-- Effective Date -->
        <div class="bg-primary-500 rounded-2xl p-8 text-center">
            <p class="text-white font-medium">
                Tieto podmienky nadobúdajú účinnosť dňom <strong class="font-bold">1. 1. 2025</strong>
            </p>
        </div>

    </div>
</div>

<!-- Contact Section -->
<div class="relative bg-gray-100 py-16">
    <div class="mx-auto max-w-4xl px-4 md:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
            Máte otázky?
        </h2>
        <p class="text-gray-600 font-light mb-8">
            V prípade akýchkoľvek otázok týkajúcich sa ochrany osobných údajov nás neváhajte kontaktovať
        </p>
        <a href="mailto:info@kavibox.sk" class="inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-medium px-8 py-4 rounded-full transition-all duration-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span>Napíšte nám</span>
        </a>
    </div>
</div>

@endsection

