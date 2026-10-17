<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Homepage Translations
    |--------------------------------------------------------------------------
    */

    'hero' => [
        'badge' => 'Každý měsíc něco nového',
        'title' => 'Objevte tu nejlepší kávu z celé Evropy',
        'subtitle' => 'Výběrová káva s pravidelným předplatným. Čerstvě pražená, pečlivě vybraná, doručená přímo k vám.',
        'cta_primary' => 'Sestavte si vlastní box',
        'cta_secondary' => 'Kávový obchod',
    ],
    
    'features' => [
        'title' => 'Proč si vybrat KAVI?',
        'subtitle' => 'Víme, co dělá kávu výjimečnou. A rádi se s vámi podělíme.',
        
        'fresh_title' => 'Čerstvá káva',
        'fresh_desc' => 'Každá pražírna nám dodává čerstvě praženou kávu do vašich kávových boxů.',
        
        'flavors_title' => 'Výjimečné chutě',
        'flavors_desc' => 'Žádné nudné a monotonní kávy. Každý měsíc objevíte nové chutě z různých koutů světa.',
        
        'shipping_title' => 'Doprava zdarma',
        'shipping_desc' => 'Doprava zdarma pro všechna předplatná. Ať už si vyberete jakoukoliv velikost boxu.',
    ],
    
    'love_coffee' => [
        'title' => 'Káva, kterou budete milovat',
        'subtitle' => 'Pečlivě vybíráme nejkvalitnější kávu z ověřených pražíren. Každý měsíc objevte nové chutě přímo u vás doma.',
        
        'feature1_title' => 'Nikde v ČR nekoupíte',
        'feature1_desc' => 'Dodáváme vám kávu z pražíren, které nejsou dostupné v ČR.',
        
        'feature2_title' => 'Bezkonkurenční cena',
        'feature2_desc' => 'Kávy v našich boxech jsou výhodnější než kdekoliv jinde.',
        
        'feature3_title' => 'Flexibilní předplatné',
        'feature3_desc' => 'Přizpůsobte množství a frekvenci podle svých potřeb.',
        
        'cta' => 'Začít předplatné',
    ],
    
    'plans' => [
        'title' => 'Vyberte si ideální kávový box',
        'subtitle' => 'Flexibilní předplatné přizpůsobené vašim potřebám. Zrušte kdykoliv bez poplatku.',
        'popular' => 'Nejoblíbenější',
        'select' => 'Vybrat',
        'note' => 'Další nastavení vašeho kávového předplatného následuje v dalším kroku.',
    ],
    
    'testimonials' => [
        'title' => 'Co říkají naši zákazníci',
        'subtitle' => 'Přidejte se k řadě spokojených milovníků kávy',
    ],
    
    'impact' => [
        'title' => 'Káva s pozitivním dopadem',
        'subtitle' => 'V KAVI věříme, že výběrová káva může měnit svět. Z každého kávového boxu proto věnujeme 5 Kč organizaci Water.org, která zajišťuje přístup k čisté vodě v rozvojových zemích. Každý box tak poskytne jednomu člověku vodu na půl roku.',
        'water_stat' => '6 měsíců',
        'water_label' => 'vody za jeden box',
        'partner_label' => 'darujeme napřímo',
    ],
    
    'cta_final' => [
        'title' => 'Začněte svou kávovou cestu ještě dnes',
        'subtitle' => 'Získejte přístup k nejlepší kávě z celé Evropy. Flexibilní předplatné, bez závazků.',
        'primary' => 'Vybrat předplatné',
        'secondary' => 'Procházet kávy',
    ],

    // Časté dotazy na homepage (redesign 2026-10). :cutoff a :shipment doplní
    // HomeController z aktuálního cyklu v ShipmentSchedule.
    'faq' => [
        ['q' => 'Kdy mi přijde první box?', 'a' => 'Objednávky uzavíráme :cutoff o půlnoci a rozesíláme :shipment, Zásilkovnou na výdejní místo. Další boxy pak vždy kolem 20. dne v měsíci.'],
        ['q' => 'Mohu předplatné pozastavit nebo zrušit?', 'a' => 'Ano, kdykoli v účtu a bez poplatků. Změna platí pro box, jehož uzávěrka ještě neproběhla.'],
        ['q' => 'Jaké kávy dostanu?', 'a' => 'Každý měsíc kávy ze tří evropských pražíren. Pražírna se vrací zhruba jednou za rok, takže se kávy opakují málokdy.'],
        ['q' => 'Espresso, nebo filtr?', 'a' => 'Espresso má plné tělo a tmavší pražení, filtr lehčí tělo a světlejší pražení. Kombinace pošle obojí.'],
        ['q' => 'Mohu předplatné darovat?', 'a' => 'Ano, na 3 nebo 6 měsíců předem, bez automatického obnovení.', 'link' => 'Dárkové předplatné'],
    ],

];
