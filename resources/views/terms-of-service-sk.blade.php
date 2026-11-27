@extends('layouts.app')

@section('title', 'Obchodné podmienky - KAVIbox.sk')

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
                Všeobecné obchodné podmienky
            </h1>
            
            <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light">
                Pravidlá nákupu a používania služieb v našom e-shope
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
                    Tieto všeobecné obchodné podmienky (ďalej len „obchodné podmienky“) sú vydané podľa § 1751 a nasl. zákona č. 89/2012 Sb., občiansky zákonník Českej republiky (ďalej len „občiansky zákonník“)
                </p>
                <div class="mt-4 space-y-2">
                    <p class="text-gray-900 font-medium">Lukáš Šlehofer</p>
                    <p class="text-gray-700 font-light">IČO: 66899095 | DIČ: CZ7912150191</p>
                    <p class="text-gray-700 font-light">so sídlom: Kurzova 2222/16, Praha 5, 155 00, Česká republika</p>
                    <p class="text-gray-700 font-light">zapísaný v živnostenskom registri vedenom Úradom mestskej časti Praha 13</p>
                    <div class="mt-3 pt-3 border-t border-gray-200 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:info@kavibox.sk" class="text-primary-600 hover:text-primary-700 font-medium">info@kavibox.sk</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- I. Základné ustanovenia -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">I</span>
                Základné ustanovenia
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Tieto obchodné podmienky upravujú vzájomné práva a povinnosti predávajúceho a fyzickej osoby, ktorá uzatvára kúpnu zmluvu mimo svojej podnikateľskej činnosti ako spotrebiteľ alebo v rámci svojej podnikateľskej činnosti (ďalej len „kupujúci“), prostredníctvom webového rozhrania umiestneného na webovej stránke dostupnej na internetovej adrese <a href="https://kavibox.sk" class="text-primary-600 hover:text-primary-700 font-medium">https://kavibox.sk</a> (ďalej len „internetový obchod“).
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Ustanovenia obchodných podmienok sú neoddeliteľnou súčasťou kúpnej zmluvy. Odchylné dojednania v kúpnej zmluve majú prednosť pred ustanoveniami týchto obchodných podmienok.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> Tieto obchodné podmienky a kúpna zmluva sa uzatvárajú v slovenskom jazyku.
                    </p>
                </div>
            </div>
        </section>

        <!-- II. Informácie o tovare a cenách -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">II</span>
                Informácie o tovare a cenách
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Informácie o tovare vrátane uvedenia cien jednotlivého tovaru a jeho hlavných vlastností sú uvedené pri jednotlivom tovare v katalógu internetového obchodu. Ceny tovaru sú uvedené vrátane dane z pridanej hodnoty, všetkých súvisiacich poplatkov a nákladov na vrátenie tovaru, ak tento tovar zo svojej podstaty nemôže byť vrátený obvyklou poštovou cestou. Ceny tovaru zostávajú v platnosti počas doby, keď sú zobrazované v internetovom obchode. Toto ustanovenie nevylučuje dojednanie kúpnej zmluvy za individuálne dohodnutých podmienok.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Všetka prezentácia tovaru umiestnená v katalógu internetového obchodu má informatívny charakter a predávajúci nie je povinný uzavrieť kúpnu zmluvu týkajúcu sa tohto tovaru.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> V internetovom obchode sú zverejnené informácie o nákladoch spojených s balením a dodaním tovaru. Informácie o nákladoch spojených s balením a dodaním tovaru uvedené v internetovom obchode platia iba v prípadoch, keď je tovar doručovaný v rámci územia Európskej únie.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> Prípadné zľavy z kúpnej ceny tovaru nie je možné navzájom kombinovať, ak sa predávajúci s kupujúcim nedohodne inak.
                    </p>
                </div>
            </div>
        </section>

        <!-- III. Objednávka a uzavretie kúpnej zmluvy -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">III</span>
                Objednávka a uzavretie kúpnej zmluvy
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Náklady, ktoré kupujúcemu vzniknú pri použití prostriedkov komunikácie na diaľku v súvislosti s uzavretím kúpnej zmluvy (náklady na internetové pripojenie, náklady na telefonické hovory), hradí kupujúci sám. Tieto náklady sa nelíšia od základnej sadzby.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">2)</strong> Kupujúci vykonáva objednávku tovaru týmito spôsobmi:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>prostredníctvom svojho zákazníckeho účtu, ak vykonal predchádzajúcu registráciu v internetovom obchode,</span>
                        </li>
                        <li class="text-gray-700 font-light flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            <span>vyplnením objednávkového formulára bez registrácie.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Pri zadávaní objednávky si kupujúci vyberie tovar, počet kusov tovaru, spôsob platby a doručenia.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">4)</strong> Pred odoslaním objednávky je kupujúcemu umožnené kontrolovať a meniť údaje, ktoré do objednávky vložil. Objednávku kupujúci odošle predávajúcemu kliknutím na tlačidlo „Odoslať objednávku“. Údaje uvedené v objednávke považuje predávajúci za správne. Podmienkou platnosti objednávky je vyplnenie všetkých povinných údajov v objednávkovom formulári a potvrdenie kupujúceho o tom, že sa s týmito obchodnými podmienkami oboznámil.
                    </p>
                </div>
                
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">5)</strong> Bezodkladne po prijatí objednávky zašle predávajúci kupujúcemu potvrdenie o prijatí objednávky na e-mailovú adresu, ktorú kupujúci pri objednávaní zadal. Toto potvrdenie sa považuje za uzavretie zmluvy. Prílohou potvrdenia sú aktuálne obchodné podmienky predávajúceho. Kúpna zmluva je uzavretá potvrdením objednávky predávajúcim na e-mailovú adresu kupujúceho.
                    </p>
                </div>
            </div>
        </section>

        <!-- IV. Ukončenie predplatného -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">IV</span>
                Ukončenie predplatného
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Predplatné je možné ukončiť po 3 mesiacoch od jeho začatia (t. j. od prvej objednávky).
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Zrušiť predplatné je možné prostredníctvom zákazníckej administrácie v rámci internetového obchodu, prípadne prostredníctvom kontaktného e-mailu predávajúceho.
                    </p>
                </div>
            </div>
        </section>

        <!-- V. Zákaznícky účet -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">V</span>
                Zákaznícky účet
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Na základe registrácie kupujúceho vykonanej v internetovom obchode môže kupujúci pristupovať do svojho zákazníckeho účtu. Zo svojho zákazníckeho účtu môže kupujúci objednávať tovar a vykonávať zmeny už zakúpeného predplatného. Kupujúci môže objednávať tovar aj bez registrácie.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Pri registrácii do zákazníckeho účtu a pri objednávaní tovaru je kupujúci povinný uvádzať správne a pravdivo všetky údaje. Údaje uvedené v používateľskom účte je kupujúci povinný pri akejkoľvek ich zmene aktualizovať. Údaje uvedené kupujúcim v zákazníckom účte a pri objednávaní tovaru považuje predávajúci za správne.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Prístup do zákazníckeho účtu je zabezpečený používateľským menom a heslom. Kupujúci je povinný zachovávať mlčanlivosť o informáciách nevyhnutných na prístup do jeho zákazníckeho účtu. Predávajúci nenesie zodpovednosť za prípadné zneužitie zákazníckeho účtu tretími osobami.
                    </p>
                </div>
            </div>
        </section>

        <!-- VI. Platobné podmienky a dodanie tovaru -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VI</span>
                Platobné podmienky a dodanie tovaru
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Cenu tovaru a prípadné náklady spojené s dodaním tovaru podľa kúpnej zmluvy môže kupujúci uhradiť nasledujúcimi spôsobmi: <strong class="font-medium text-gray-900">bezhotovostne platobnou kartou</strong>
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">5)</strong> Tovar je kupujúcemu dodaný na výdajné miesto určené kupujúcim v objednávke.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">9)</strong> Predávajúci vystaví kupujúcemu daňový doklad – faktúru. Daňový doklad je odoslaný po spracovaní objednávky na e-mailovú adresu kupujúceho. Daňový doklad je vystavovaný pravidelne každý mesiac.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">10)</strong> Kupujúci nadobúda vlastnícke právo k tovaru zaplatením celej kúpnej ceny za tovar vrátane nákladov na dodanie, najskôr však prevzatím tovaru.
                    </p>
                </div>
            </div>
        </section>

        <!-- VII. Odstúpenie od zmluvy -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VII</span>
                Odstúpenie od zmluvy
            </h2>
            
            <div class="space-y-6">
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">2)</strong> Ak nejde o výnimky uvedené v občianskom zákonníku, má kupujúci v súlade s ustanovením § 1829 ods. 1 občianskeho zákonníka právo od kúpnej zmluvy odstúpiť, a to do <strong class="font-medium text-gray-900">14 dní od prevzatia tovaru</strong>, pričom v prípade, že predmetom kúpnej zmluvy je niekoľko druhov tovaru alebo dodanie niekoľkých častí, plynie táto lehota odo dňa prevzatia poslednej dodávky tovaru.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Na odstúpenie od kúpnej zmluvy môže kupujúci využiť formulár na odstúpenie od zmluvy poskytovaný predávajúcim. Odstúpenie od kúpnej zmluvy zašle kupujúci na e-mailovú alebo doručovaciu adresu predávajúceho uvedenú v týchto obchodných podmienkach.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">5)</strong> Ak kupujúci odstúpi od zmluvy, vráti mu predávajúci bezodkladne, najneskôr však do 14 dní od odstúpenia od zmluvy, všetky peňažné prostriedky vrátane nákladov na dodanie, ktoré od neho prijal, a to rovnakým spôsobom.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">8)</strong> Tovar musí kupujúci vrátiť predávajúcemu nepoškodený, neopotrebovaný a neznečistený a v pôvodnom obale. Nárok na náhradu škody vzniknutej na tovare je predávajúci oprávnený jednostranne započítať proti nároku kupujúceho na vrátenie kúpnej ceny.
                    </p>
                </div>
            </div>
        </section>

        <!-- VIII. Práva z vadného plnenia -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">VIII</span>
                Práva z vadného plnenia
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Predávajúci zodpovedá kupujúcemu za to, že tovar pri prevzatí nemá vady. Predávajúci najmä zodpovedá kupujúcemu za to, že v čase, keď kupujúci tovar prevzal, má tovar vlastnosti, ktoré si strany dohodli, a ak dohoda chýba, má také vlastnosti, ktoré predávajúci alebo výrobca opísal alebo ktoré kupujúci očakával s ohľadom na povahu tovaru a na základe reklamy nimi uskutočňovanej.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Povinnosti z vadného plnenia má predávajúci najmenej v takom rozsahu, v akom trvajú povinnosti z vadného plnenia výrobcu. Kupujúci je inak oprávnený uplatniť právo z vady, ktorá sa vyskytne pri spotrebnom tovare v lehote <strong class="font-medium text-gray-900">dvadsiatich štyroch mesiacov od prevzatia</strong>.
                    </p>
                </div>
                
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed mb-3">
                        <strong class="font-medium text-gray-900">5)</strong> V prípade výskytu vady môže kupujúci uplatniť u predávajúceho reklamáciu a požadovať:
                    </p>
                    <ul class="space-y-2 ml-8">
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            výmenu za nový tovar,
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            primeranú zľavu z kúpnej ceny,
                        </li>
                        <li class="text-gray-700 font-light flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            odstúpenie od zmluvy.
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">15)</strong> Predávajúci alebo ním poverený pracovník rozhodne o reklamácii ihneď, v zložitých prípadoch do troch pracovných dní. Do tejto lehoty sa nezapočítava čas primeraný podľa druhu výrobku či služby potrebný na odborné posúdenie vady. Reklamácia vrátane odstránenia vady musí byť vybavená bezodkladne, najneskôr do <strong class="font-medium text-gray-900">30 dní odo dňa uplatnenia reklamácie</strong>, ak sa predávajúci s kupujúcim nedohodne na dlhšej lehote.
                    </p>
                </div>
            </div>
        </section>

        <!-- IX. Doručovanie -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">IX</span>
                Doručovanie
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Zmluvné strany si môžu všetku písomnú korešpondenciu vzájomne doručovať prostredníctvom elektronickej pošty.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Kupujúci doručuje predávajúcemu korešpondenciu na e-mailovú adresu uvedenú v týchto obchodných podmienkach. Predávajúci doručuje kupujúcemu korešpondenciu na e-mailovú adresu uvedenú v jeho zákazníckom účte alebo v objednávke.
                    </p>
                </div>
            </div>
        </section>

        <!-- X. Osobné údaje -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">X</span>
                Osobné údaje
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Všetky informácie, ktoré kupujúci pri spolupráci s predávajúcim uvedie, sú dôverné a bude sa s nimi tak zaobchádzať. Ak kupujúci nedá predávajúcemu písomný súhlas, predávajúci nebude údaje o kupujúcom používať iným spôsobom ako na účel plnenia zo zmluvy, s výnimkou e-mailovej adresy, na ktorú môžu byť zasielané obchodné oznámenia, keďže tento postup umožňuje zákon, ak nie je výslovne odmietnutý. Tieto oznámenia sa môžu týkať iba obdobného alebo súvisiaceho tovaru a je možné ich kedykoľvek jednoduchým spôsobom (zaslaním listu, e-mailu alebo kliknutím na odkaz v obchodnom oznámení) odhlásiť. E-mailová adresa bude na tento účel uchovávaná počas 3 rokov od uzavretia poslednej zmluvy medzi zmluvnými stranami.
                    </p>
                </div>
                
                <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">2)</strong> Podrobnejšie informácie o ochrane osobných údajov nájdete v <a href="{{ localizedRoute('privacy-policy') }}" class="text-primary-600 hover:text-primary-700 font-medium">Zásadách ochrany osobných údajov</a>.
                    </p>
                </div>
            </div>
        </section>

        <!-- XI. Mimosúdne riešenie sporov -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">XI</span>
                Mimosúdne riešenie sporov
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Na mimosúdne riešenie spotrebiteľských sporov z kúpnej zmluvy je príslušná Česká obchodná inšpekcia so sídlom Štěpánská 567/15, 120 00 Praha 2, IČO: 000 20 869, internetová adresa: <a href="https://adr.coi.cz/cs" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium">https://adr.coi.cz/cs</a>. Platformu na riešenie sporov online nachádzajúcu sa na internetovej adrese <a href="http://ec.europa.eu/consumers/odr" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium">http://ec.europa.eu/consumers/odr</a> je možné využiť pri riešení sporov medzi predávajúcim a kupujúcim z kúpnej zmluvy.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Predávajúci je oprávnený na predaj tovaru na základe živnostenského oprávnenia. Živnostenskú kontrolu vykonáva v rámci svojej pôsobnosti príslušný živnostenský úrad. Česká obchodná inšpekcia vykonáva vo vymedzenom rozsahu okrem iného dozor nad dodržiavaním zákona č. 634/1992 Sb., o ochrane spotrebiteľa.
                    </p>
                </div>
            </div>
        </section>

        <!-- XII. Záverečné ustanovenia -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-900 text-white text-sm font-medium flex-shrink-0">XII</span>
                Záverečné ustanovenia
            </h2>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">1)</strong> Všetky dojednania medzi predávajúcim a kupujúcim sa riadia právnym poriadkom Českej republiky. Ak vzťah založený kúpnou zmluvou obsahuje medzinárodný prvok, strany dojednávajú, že vzťah sa riadi právom Českej republiky. Týmto nie sú dotknuté práva spotrebiteľa vyplývajúce zo všeobecne záväzných právnych predpisov.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">3)</strong> Všetky práva k webovým stránkam predávajúceho, najmä autorské práva k obsahu vrátane rozloženia stránky, fotografií, filmov, grafík, ochranných známok, loga a ďalšieho obsahu a prvkov, patria predávajúcemu. Je zakázané kopírovať, upravovať alebo inak používať webové stránky alebo ich časť bez súhlasu predávajúceho.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">6)</strong> Kúpna zmluva vrátane obchodných podmienok je archivovaná predávajúcim v elektronickej podobe a nie je prístupná.
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <p class="text-gray-700 font-light leading-relaxed">
                        <strong class="font-medium text-gray-900">7)</strong> Znenie obchodných podmienok môže predávajúci meniť či dopĺňať. Týmto ustanovením nie sú dotknuté práva a povinnosti vzniknuté počas účinnosti predchádzajúceho znenia obchodných podmienok.
                    </p>
                </div>
            </div>
        </section>

        <!-- Effective Date -->
        <div class="bg-primary-500 rounded-2xl p-8 text-center">
            <p class="text-white font-medium">
                Tieto obchodné podmienky nadobúdajú účinnosť dňom <strong class="font-bold">1. 1. 2025</strong>
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
            V prípade akýchkoľvek otázok týkajúcich sa obchodných podmienok nás neváhajte kontaktovať
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

