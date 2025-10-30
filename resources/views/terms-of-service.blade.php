@extends('layouts.app')

@section('title', 'Obchodní podmínky - Kavi Coffee')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium text-gray-900">VOP</span>
            </div>
            
            <!-- Clean Heading -->
            <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                Všeobecné obchodní podmínky
            </h1>
            
            <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light">
                Pravidla pro nákup a užívání služeb v našem e-shopu
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
        
        <!-- Intro -->
        <div class="mb-16">
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                <p class="text-gray-700 font-light leading-relaxed mb-3">
                    Tyto všeobecné obchodní podmínky (dále jen „obchodní podmínky") jsou vydané dle § 1751 a násl. zákona č. 89/2012 Sb., občanský zákoník (dále jen „občanský zákoník")
                </p>
                <div class="mt-4 space-y-2">
                    <p class="text-gray-900 font-medium">Lukáš Šlehofer</p>
                    <p class="text-gray-700 font-light">IČ: 66899095 | DIČ: CZ7912150191</p>
                    <p class="text-gray-700 font-light">se sídlem: Kurzova 2222/16, Praha 5, 155 00</p>
                    <p class="text-gray-700 font-light">zapsaný v živnostenském rejstříku u Úřadu městské části Praha 13</p>
                    <div class="mt-3 pt-3 border-t border-gray-200 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:info@kavi.cz" class="text-primary-600 hover:text-primary-700 font-medium">info@kavi.cz</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- I. Základní ustanovení -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">I</span>
                Základní ustanovení
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Tyto obchodní podmínky upravují vzájemná práva a povinnosti prodávajícího a fyzické osoby, která uzavírá kupní smlouvu mimo svou podnikatelskou činnost jako spotřebitel, nebo v rámci své podnikatelské činnosti (dále jen: „kupující") prostřednictvím webového rozhraní umístěného na webové stránce dostupné na internetové adrese <a href="http://kavi.cz" class="text-primary-600 hover:text-primary-700 font-medium">http://kavi.cz</a> (dále jen „internetový obchod").
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Ustanovení obchodních podmínek jsou nedílnou součástí kupní smlouvy. Odchylná ujednání v kupní smlouvě mají přednost před ustanoveními těchto obchodních podmínek.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> Tyto obchodní podmínky a kupní smlouva se uzavírají v českém jazyce.
                    </p>
                </div>
            </div>
        </section>

        <!-- II. Informace o zboží a cenách -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">II</span>
                Informace o zboží a cenách
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Informace o zboží, včetně uvedení cen jednotlivého zboží a jeho hlavních vlastností, jsou uvedeny u jednotlivého zboží v katalogu internetového obchodu. Ceny zboží jsou uvedeny včetně daně z přidané hodnoty, všech souvisejících poplatků a nákladů za vrácení zboží, jestliže toto zboží ze své podstaty nemůže být vráceno obvyklou poštovní cestou. Ceny zboží zůstávají v platnosti po dobu, po kterou jsou zobrazovány v internetovém obchodě. Toto ustanovení nevylučuje sjednání kupní smlouvy za individuálně sjednaných podmínek.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Veškerá prezentace zboží umístěná v katalogu internetového obchodu je informativního charakteru a prodávající není povinen uzavřít kupní smlouvu ohledně tohoto zboží.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> V internetovém obchodě jsou zveřejněny informace o nákladech spojených s balením a dodáním zboží. Informace o nákladech spojených s balením a dodáním zboží uvedené v internetovém obchodě platí pouze v případech, kdy je zboží doručováno v rámci území České republiky.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> Případné slevy z kupní ceny zboží nelze navzájem kombinovat, nedohodne-li se prodávající s kupujícím jinak.
                    </p>
                </div>
            </div>
        </section>

        <!-- III. Objednávka a uzavření kupní smlouvy -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">III</span>
                Objednávka a uzavření kupní smlouvy
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Náklady vzniklé kupujícímu při použití komunikačních prostředků na dálku v souvislosti s uzavřením kupní smlouvy (náklady na internetové připojení, náklady na telefonní hovory), hradí kupující sám. Tyto náklady se neliší od základní sazby.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">2)</strong> Kupující provádí objednávku zboží těmito způsoby:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>prostřednictvím svého zákaznického účtu, provedl-li předchozí registraci v internetovém obchodě,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>vyplněním objednávkového formuláře bez registrace.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Při zadávání objednávky si kupující vybere zboží, počet kusů zboží, způsob platby a doručení.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> Před odesláním objednávky je kupujícímu umožněno kontrolovat a měnit údaje, které do objednávky vložil. Objednávku kupující odešle prodávajícímu kliknutím na tlačítko "Odeslat objednávku". Údaje uvedené v objednávce jsou prodávajícím považovány za správné. Podmínkou platnosti objednávky je vyplnění všech povinných údajů v objednávkovém formuláři a potvrzení kupujícího o tom, že se s těmito obchodními podmínkami seznámil.
                    </p>
                </div>
                
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">5)</strong> Neprodleně po obdržení objednávky zašle prodávající kupujícímu potvrzení o obdržení objednávky na e-mailovou adresu, kterou kupující při objednání zadal. Toto potvrzení se považuje za uzavření smlouvy. Přílohou potvrzení jsou aktuální obchodní podmínky prodávajícího. Kupní smlouva je uzavřena potvrzením objednávky prodávajícím na e-mailovou adresu kupujícího.
                    </p>
                </div>
            </div>
        </section>

        <!-- IV. Ukončení předplatného -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">IV</span>
                Ukončení předplatného
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Předplatné je možné ukončit po 3 měsících od jeho zahájení (tzn. od první objednávky)
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Zrušit předplatné je možné prostřednictvím zákaznické administrace v rámci internetového obchodu, případně na kontaktním e-mailu prodávajícího.
                    </p>
                </div>
            </div>
        </section>

        <!-- V. Zákaznický účet -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">V</span>
                Zákaznický účet
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Na základě registrace kupujícího provedené v internetovém obchodě může kupující přistupovat do svého zákaznického účtu. Ze svého zákaznického účtu může kupující provádět objednávání zboží a změny již zakoupeného předplatného. Kupující může objednávat zboží také bez registrace.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Při registraci do zákaznického účtu a při objednávání zboží je kupující povinen uvádět správně a pravdivě všechny údaje. Údaje uvedené v uživatelském účtu je kupující při jakékoliv jejich změně povinen aktualizovat. Údaje uvedené kupujícím v zákaznickém účtu a při objednávání zboží jsou prodávajícím považovány za správné.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Přístup k zákaznickému účtu je zabezpečen uživatelským jménem a heslem. Kupující je povinen zachovávat mlčenlivost ohledně informací nezbytných k přístupu do jeho zákaznického účtu. Prodávající nenese odpovědnost za případné zneužití zákaznického účtu třetími osobami.
                    </p>
                </div>
            </div>
        </section>

        <!-- VI. Platební podmínky a dodání zboží -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VI</span>
                Platební podmínky a dodání zboží
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Cenu zboží a případné náklady spojené s dodáním zboží dle kupní smlouvy může kupující uhradit následujícími způsoby: <strong class="font-medium text-gray-900">bezhotovostně platební kartou</strong>
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">5)</strong> Zboží je kupujícímu dodáno na výdejní místo určené kupujícím objednávce.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">9)</strong> Prodávající vystaví kupujícímu daňový doklad – fakturu. Daňový doklad je odeslán po zpracování objednávky na e-mailovou adresu kupujícího. Daňový doklad je vystaven pravidelně každý měsíc.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">10)</strong> Kupující nabývá vlastnické právo ke zboží zaplacením celé kupní ceny za zboží včetně nákladů na dodání, nejdříve však převzetím zboží.
                    </p>
                </div>
            </div>
        </section>

        <!-- VII. Odstoupení od smlouvy -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VII</span>
                Odstoupení od smlouvy
            </h2>
            
            <div class="space-y-6">
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">2)</strong> Nejedná-li se o výjimky uvedené v občanském zákoníku, má kupující v souladu s ustanovením § 1829 odst. 1 občanského zákoníku právo od kupní smlouvy odstoupit, a to do <strong class="font-medium text-gray-900">14 dnů od převzetí zboží</strong>, přičemž v případě, že předmětem kupní smlouvy je několik druhů zboží nebo dodání několika částí, běží tato lhůta ode dne převzetí poslední dodávky zboží.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Pro odstoupení od kupní smlouvy může kupující využít formulář k odstoupení od smlouvy poskytovaný prodávajícím. Odstoupení od kupní smlouvy zašle kupující na e-mailovou nebo doručovací adresu prodávajícího uvedenou v těchto obchodních podmínkách.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">5)</strong> Odstoupí-li kupující od smlouvy, vrátí mu prodávající bezodkladně, nejpozději však do 14 dnů od odstoupení od smlouvy všechny peněžní prostředky včetně nákladů na dodání, které od něho přijal, a to stejným způsobem.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">8)</strong> Zboží musí vrátit kupující prodávajícímu nepoškozené, neopotřebené a neznečištěné a v původním obalu. Nárok na náhradu škody vzniklé na zboží je prodávající oprávněn jednostranně započíst proti nároku kupujícího na vrácení kupní ceny.
                    </p>
                </div>
            </div>
        </section>

        <!-- VIII. Práva z vadného plnění -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VIII</span>
                Práva z vadného plnění
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Prodávající zodpovídá kupujícímu, že zboží při převzetí nemá vady. Zejména prodávající odpovídá kupujícímu, že v době, kdy kupující zboží převzal, má zboží vlastnosti, které si strany ujednaly, a chybí-li ujednání, má takové vlastnosti, které prodávající nebo výrobce popsal nebo které kupující očekával s ohledem na povahu zboží a na základě reklamy jimi prováděné.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Povinnosti z vadného plnění má prodávající nejméně v takovém rozsahu, v jakém trvají povinnosti z vadného plnění výrobce. Kupující je jinak oprávněn uplatnit právo z vady, která se vyskytne u spotřebního zboží v době <strong class="font-medium text-gray-900">dvaceti čtyř měsíců od převzetí</strong>.
                    </p>
                </div>
                
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">5)</strong> V případě výskytu vady může kupující prodávajícímu předložit reklamaci a požadovat:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            výměnu za nové zboží,
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            přiměřenou slevu z kupní ceny,
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            odstoupení od smlouvy.
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">15)</strong> Prodávající nebo jím pověřený pracovník rozhodne o reklamaci ihned, ve složitých případech do tří pracovních dnů. Do této lhůty se nezapočítává doba přiměřená podle druhu výrobku či služby potřebná k odbornému posouzení vady. Reklamace včetně odstranění vady musí být vyřízena bezodkladně, nejpozději do <strong class="font-medium text-gray-900">30 dnů ode dne uplatnění reklamace</strong>, pokud se prodávající s kupujícím nedohodne na delší lhůtě.
                    </p>
                </div>
            </div>
        </section>

        <!-- IX. Doručování -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">IX</span>
                Doručování
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Smluvní strany si mohou veškerou písemnou korespondenci vzájemně doručovat prostřednictvím elektronické pošty.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Kupující doručuje prodávajícímu korespondenci na e-mailovou adresu uvedenou v těchto obchodních podmínkách. Prodávající doručuje kupujícímu korespondenci na e-mailovou adresu uvedenou v jeho zákaznickém účtu nebo v objednávce.
                    </p>
                </div>
            </div>
        </section>

        <!-- X. Osobní údaje -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">X</span>
                Osobní údaje
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Všechny informace, které kupující při spolupráci s prodávajícím uvede, jsou důvěrné a bude s nimi tak zacházeno. Pokud kupující nedá prodávajícímu písemné svolení, údaje o kupujícím nebude prodávající jiným způsobem než za účelem plnění ze smlouvy používat, vyjma e-mailové adresy, na kterou mohou být zasílána obchodní sdělení, neboť tento postup umožňuje zákon, pokud není vysloveně odmítnut. Tato sdělení se mohou týkat pouze obdobného nebo souvisejícího zboží a lze je kdykoliv jednoduchým způsobem (zasláním dopisu, e-mailu nebo proklikem na odkaz v obchodním sdělení) odhlásit. E-mailová adresa bude za tímto účelem uchovávána po dobu 3 let od uzavření poslední smlouvy mezi smluvními stranami.
                    </p>
                </div>
                
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Podrobnější informace o ochraně osobních údajů naleznete v <a href="{{ route('privacy-policy') }}" class="text-primary-600 hover:text-primary-700 font-medium">Zásadách ochrany osobních údajů</a>.
                    </p>
                </div>
            </div>
        </section>

        <!-- XI. Mimosoudní řešení sporů -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">XI</span>
                Mimosoudní řešení sporů
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> K mimosoudnímu řešení spotřebitelských sporů z kupní smlouvy je příslušná Česká obchodní inspekce se sídlem Štěpánská 567/15, 120 00 Praha 2, IČ: 000 20 869, internetová adresa: <a href="https://adr.coi.cz/cs" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium">https://adr.coi.cz/cs</a>. Platformu pro řešení sporů on-line nacházející se na internetové adrese <a href="http://ec.europa.eu/consumers/odr" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium">http://ec.europa.eu/consumers/odr</a> je možné využít při řešení sporů mezi prodávajícím a kupujícím z kupní smlouvy.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Prodávající je oprávněn k prodeji zboží na základě živnostenského oprávnění. Živnostenskou kontrolu provádí v rámci své působnosti příslušný živnostenský úřad. Česká obchodní inspekce vykonává ve vymezeném rozsahu mimo jiné dozor nad dodržováním zákona č. 634/1992 Sb., o ochraně spotřebitele.
                    </p>
                </div>
            </div>
        </section>

        <!-- XII. Závěrečná ustanovení -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">XII</span>
                Závěrečná ustanovení
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Veškerá ujednání mezi prodávajícím a kupujícím se řídí právním řádem České republiky. Pokud vztah založený kupní smlouvou obsahuje mezinárodní prvek, pak strany sjednávají, že vztah se řídí právem České republiky. Tímto nejsou dotčena práva spotřebitele vyplývající z obecně závazných právních předpisů.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Všechna práva k webovým stránkám prodávajícího, zejména autorská práva k obsahu, včetně rozvržení stránky, fotek, filmů, grafik, ochranných známek, loga a dalšího obsahu a prvků, náleží prodávajícímu. Je zakázáno kopírovat, upravovat nebo jinak používat webové stránky nebo jejich část bez souhlasu prodávajícího.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">6)</strong> Kupní smlouva včetně obchodních podmínek je archivována prodávajícím v elektronické podobě a není přístupná.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">7)</strong> Znění obchodních podmínek může prodávající měnit či doplňovat. Tímto ustanovením nejsou dotčena práva a povinnosti vzniklé po dobu účinnosti předchozího znění obchodních podmínek.
                    </p>
                </div>
            </div>
        </section>

        <!-- Effective Date -->
        <div class="bg-primary-500 rounded-2xl p-8 text-center">
            <p class="text-white font-medium">
                Tyto obchodní podmínky nabývají účinnosti dnem <strong class="font-bold">1. 1. 2025</strong>
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
            V případě jakýchkoliv dotazů ohledně obchodních podmínek nás neváhejte kontaktovat
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

