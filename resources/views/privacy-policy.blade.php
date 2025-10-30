@extends('layouts.app')

@section('title', 'Zásady ochrany osobních údajů - Kavi Coffee')

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
                Zásady ochrany osobních údajů
            </h1>
            
            <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light">
                Informace o zpracování a ochraně vašich osobních údajů
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
        
        <!-- I. Základní ustanovení -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">I</span>
                Základní ustanovení
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Správcem osobních údajů podle čl. 4 bod 7 nařízení Evropského parlamentu a Rady (EU) 2016/679 o ochraně fyzických osob v souvislosti se zpracováním osobních údajů a o volném pohybu těchto údajů (dále jen: „GDPR") je <strong class="font-medium text-gray-900">Lukáš Šlehofer, IČ 66899095</strong> se sídlem Kurzova 2222/16, Praha 5, 155 00, zapsaný v živnostenském rejstříku u Úřadu městské části Praha 13 (dále jen: „správce").
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">2)</strong> Kontaktní údaje správce jsou:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            adresa: Lukáš Šlehofer, Kurzova 2222/16, Praha 5, 15500
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            e-mail: <a href="mailto:info@kavi.cz" class="text-primary-600 hover:text-primary-700 font-medium">info@kavi.cz</a>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Osobními údaji se rozumí veškeré informace o identifikované nebo identifikovatelné fyzické osobě; identifikovatelnou fyzickou osobou je fyzická osoba, kterou lze přímo či nepřímo identifikovat, zejména odkazem na určitý identifikátor, například jméno, identifikační číslo, lokační údaje, síťový identifikátor nebo na jeden či více zvláštních prvků fyzické, fyziologické, genetické, psychické, ekonomické, kulturní nebo společenské identity této fyzické osoby.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> Správce nejmenoval pověřence pro ochranu osobních údajů.
                    </p>
                </div>
            </div>
        </section>

        <!-- II. Zdroje a kategorie zpracovávaných osobních údajů -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">II</span>
                Zdroje a kategorie zpracovávaných osobních údajů
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">1)</strong> Správce zpracovává osobní údaje, které jste mu poskytl/a nebo osobní údaje, které správce získal na základě plnění Vaší objednávky:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                            jméno a příjmení
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                            e-mailová adresa
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                            poštovní adresa
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Správce zpracovává Vaše identifikační a kontaktní údaje a údaje nezbytné pro plnění smlouvy.
                    </p>
                </div>
            </div>
        </section>

        <!-- III. Zákonný důvod a účel zpracování osobních údajů -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">III</span>
                Zákonný důvod a účel zpracování osobních údajů
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">1)</strong> Zákonným důvodem zpracování osobních údajů je:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>plnění smlouvy mezi Vámi a správcem podle čl. 6 odst. 1 písm. b) GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>splnění právní povinnosti správce podle čl. 6 odst. 1 písm. c) GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>oprávněný zájem správce na poskytování přímého marketingu (zejména pro zasílání obchodních sdělení a newsletterů) podle čl. 6 odst. 1 písm. f) GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>Váš souhlas se zpracováním pro účely poskytování přímého marketingu (zejména pro zasílání obchodních sdělení a newsletterů) podle čl. 6 odst. 1 písm. a) GDPR ve spojení s § 7 odst. 2 zákona č. 480/2004 Sb., o některých službách informační společnosti v případě, že nedošlo k objednávce zboží nebo služby.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">2)</strong> Účelem zpracování osobních údajů je:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>vyřízení Vaší objednávky a výkon práv a povinností vyplývajících ze smluvního vztahu mezi Vámi a správcem; při objednávce jsou vyžadovány osobní údaje, které jsou nutné pro úspěšné vyřízení objednávky (jméno a adresa, kontakt), poskytnutí osobních údajů je nutným požadavkem pro uzavření a plnění smlouvy, bez poskytnutí osobních údajů není možné smlouvu uzavřít či jí ze strany správce plnit,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>plnění právních povinností vůči státu,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>zasílání obchodních sdělení a činění dalších marketingových aktivit.</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>Ze strany správce nedochází k automatickému individuálnímu rozhodování ve smyslu čl. 22 GDPR.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- IV. Doba uchovávání údajů -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">IV</span>
                Doba uchovávání údajů
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">1)</strong> Správce uchovává osobní údaje:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>po dobu nezbytnou k výkonu práv a povinností vyplývajících ze smluvního vztahu mezi Vámi a správcem a uplatňování nároků z těchto smluvních vztahů (po dobu 15 let od ukončení smluvního vztahu).</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>po dobu, než je odvolán souhlas se zpracováním osobních údajů pro účely marketingu, nejdéle 15 let, jsou-li osobní údaje zpracovávány na základě souhlasu.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Po uplynutí doby uchovávání osobních údajů správce osobní údaje vymaže.
                    </p>
                </div>
            </div>
        </section>

        <!-- V. Příjemci osobních údajů -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">V</span>
                Příjemci osobních údajů (subdodavatelé správce)
            </h2>
            
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <p class="text-gray-700 font-light leading-relaxed mb-3">
                    <strong class="font-medium text-gray-900">1)</strong> Příjemci osobních údajů jsou osoby:
                </p>
                <ul class="space-y-2 ml-8">
                    <li class="text-gray-700 font-light flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                        <span>podílející se na dodání zboží/služeb/realizaci plateb na základě smlouvy,</span>
                    </li>
                    <li class="text-gray-700 font-light flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                        <span>zajišťující služby provozování e-shopu a další služby v souvislosti s provozováním e-shopu,</span>
                    </li>
                    <li class="text-gray-700 font-light flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                        <span>zajišťující marketingové služby.</span>
                    </li>
                    <li class="text-gray-700 font-light flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                        <span>správce má v úmyslu předat osobní údaje do třetí země (do země mimo EU) nebo mezinárodní organizaci. Příjemci osobních údajů ve třetích zemích jsou poskytovatelé e-shopových služeb, mailingových služeb a cloudových služeb.</span>
                    </li>
                </ul>
            </div>
        </section>

        <!-- VI. Zpracovatelé osobních údajů -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VI</span>
                Zpracovatelé osobních údajů
            </h2>
            
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <p class="text-gray-700 font-light leading-relaxed mb-3">
                    <strong class="font-medium text-gray-900">1)</strong> Zpracování osobních údajů je prováděno správcem, osobní údaje však pro něj mohou zpracovávat i tito zpracovatelé:
                </p>
                <ul class="space-y-2 ml-8">
                    <li class="text-gray-700 font-light flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                        poskytovatel služby Mailchimp,
                    </li>
                    <li class="text-gray-700 font-light flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                        poskytovatel služby Printify,
                    </li>
                    <li class="text-gray-700 font-light flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0"></span>
                        případně další poskytovatel zpracovatelských softwarům služeb a aplikací, které však v současné době správce nevyužívá.
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
                        <strong class="font-medium text-gray-900">1)</strong> Za podmínek stanovených v GDPR máte:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo na přístup ke svým osobním údajům dle čl. 15 GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo opravu osobních údajů dle čl. 16 GDPR, popřípadě omezení zpracování dle čl. 18 GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo na výmaz osobních údajů dle čl. 17 GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo vznést námitku proti zpracování dle čl. 21 GDPR,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo na přenositelnost údajů dle čl. 20 GDPR a</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>právo odvolat souhlas se zpracováním písemně nebo elektronicky na adresu nebo e-mail správce uvedený v čl. III těchto podmínek.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Dále máte právo podat stížnost u Úřadu pro ochranu osobních údajů v případě, že se domníváte, že bylo porušeno Vaše právo na ochranu osobních údajů, případně se obrátit na soud.
                    </p>
                </div>
            </div>
        </section>

        <!-- VIII. Podmínky zabezpečení osobních údajů -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VIII</span>
                Podmínky zabezpečení osobních údajů
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Správce prohlašuje, že přijal veškerá vhodná technická a organizační opatření k zabezpečení osobních údajů.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Správce přijal technická opatření k zabezpečení datových úložišť a úložišť osobních údajů v listinné podobě.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Správce prohlašuje, že k osobním údajům mají přístup pouze jím pověřené osoby.
                    </p>
                </div>
            </div>
        </section>

        <!-- IX. Závěrečná ustanovení -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">IX</span>
                Závěrečná ustanovení
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Odesláním objednávky z internetového objednávkového formuláře potvrzujete, že jste seznámen/a s podmínkami ochrany osobních údajů a že je v celém rozsahu přijímáte.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> S těmito podmínkami souhlasíte zaškrtnutím souhlasu prostřednictvím internetového formuláře. Zaškrtnutím souhlasu potvrzujete, že jste seznámen/a s podmínkami ochrany osobních údajů a že je v celém rozsahu přijímáte.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Správce je oprávněn tyto podmínky změnit. Novou verzi podmínek ochrany osobních údajů zveřejní na svých internetových stránkách a zároveň Vám zašle novou verzi těchto podmínek Vaši e-mailovou adresu, kterou jste správci poskytl/a.
                    </p>
                </div>
            </div>
        </section>

        <!-- Effective Date -->
        <div class="bg-primary-500 rounded-2xl p-8 text-center">
            <p class="text-white font-medium">
                Tyto podmínky nabývají účinnosti dnem <strong class="font-bold">1. 1. 2025</strong>
            </p>
        </div>

    </div>
</div>

<!-- Contact Section -->
<div class="relative bg-gray-100 py-16">
    <div class="mx-auto max-w-4xl px-4 md:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
            Máte dotazy?
        </h2>
        <p class="text-gray-600 font-light mb-8">
            V případě jakýchkoliv dotazů ohledně ochrany osobních údajů nás neváhejte kontaktovat
        </p>
        <a href="mailto:info@kavi.cz" class="inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-medium px-8 py-4 rounded-full transition-all duration-200">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span>Napište nám</span>
        </a>
    </div>
</div>

@endsection

