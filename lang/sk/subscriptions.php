<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subscription Page Translations
    |--------------------------------------------------------------------------
    */

    'title' => 'Kávové predplatné',
    'subtitle' => 'Objavte výnimočnú výberovú kávu doručenú priamo k vám',

    'configurator' => [
        'title' => 'Zostavte si vlastný box',
        'step1' => 'Vyberte veľkosť boxu',
        'step2' => 'Zvoľte typ kávy',
        'step3' => 'Vyberte frekvenciu doručenia',
        'step4' => 'Zadajte doručovacie údaje',
    ],

    'box_sizes' => [
        'm_box' => 'M Box',
        'l_box' => 'L Box',
        'xl_box' => 'XL Box',
        'bags' => ':count balenia × 250g',
        'coffees' => ':count druhy výberovej kávy',
    ],

    'coffee_types' => [
        'espresso' => 'Espresso',
        'filter' => 'Filter',
        'mix' => 'Mix',
        'omni' => 'Omni-roast',
        'decaf' => 'Bezkofeínová',
    ],

    'frequency' => [
        'monthly' => 'Mesačne',
        'bimonthly' => 'Každé 2 mesiace',
        'quarterly' => 'Každé 3 mesiace',
    ],

    'features' => [
        'free_shipping' => 'Zabalené s láskou',
        'cancel_anytime' => 'Zrušenie alebo prestávka kedykoľvek',
        'fresh_roasted' => 'Čerstvo pražená káva',
        'curated' => 'Starostlivo vybraná ponuka',
    ],

    'pricing' => [
        'per_box' => '/box',
        'per_month' => '/mesiac',
        'starting_from' => 'Od',
    ],

    'cta' => [
        'subscribe' => 'Objednať predplatné',
        'configure' => 'Zostavte si box',
        'one_time' => 'Vyskúšať jednorazovo',
    ],

    'coffee_of_month' => [
        'title' => 'Káva mesiaca',
        'subtitle' => 'Objavte výber na tento mesiac',
        'explore' => 'Preskúmať kávy na :month',
    ],

    'how_it_works' => [
        'title' => 'Ako to funguje',
        'step1_title' => 'Vyberte si plán',
        'step1_desc' => 'Zvoľte množstvo kávy a frekvenciu doručenia podľa svojich potrieb',
        'step2_title' => 'Prispôsobte si',
        'step2_desc' => 'Vyberte typ kávy, spôsob prípravy a doručovaciu adresu',
        'step3_title' => 'Vyzdvihnite si box',
        'step3_desc' => 'Čerstvo praženú kávu doručíme na vami vybrané miesto',
        'step4_title' => 'Vychutnajte si',
        'step4_desc' => 'Užite si skvelú výberovú kávu a tešte sa na ďalší kávový box',
    ],

    // Doplatenie zásielky vynechanej pre neúspešnú platbu (odkaz z e-mailu)
    'pay_skipped' => [
        'page_title' => 'Doplatenie zásielky',
        'success_badge' => 'Zaplatené',
        'success_heading' => 'Ďakujeme, box posielame',
        'success_text' => 'Platba prebehla. Box vám pošleme s najbližšou zásielkou a kartu sme si uložili na ďalšie platby predplatného. Potvrdenie vám príde e-mailom.',
        'processing_text' => 'Platbu ešte overujeme. Hneď ako ju Stripe potvrdí, pošleme vám e-mail.',
        'details_title' => 'Vaše predplatné',
        'subscription_number' => 'Číslo predplatného',
        'shipment' => 'Box odošleme',
        'paid' => 'Zaplatené',
        'card' => 'Karta na ďalšie platby',
        'next_payment' => 'Ďalšia platba',
        'cancelled_badge' => 'Platba zrušená',
        'cancelled_heading' => 'Platba neprebehla',
        'cancelled_text' => 'Platbu ste zrušili. Ak box chcete, vráťte sa k odkazu v e-maile, platí do dnešnej polnoci.',
        'expired_badge' => 'Platnosť odkazu vypršala',
        'expired_heading' => 'Odkaz už neplatí',
        'expired_text' => 'Odkaz na platbu platil len v deň odoslania e-mailu. Predplatné pokračuje ďalšou zásielkou. Ak o box stále máte záujem, napíšte nám na :email.',
        'done_badge' => 'Vybavené',
        'done_heading' => 'Táto zásielka je vybavená',
        'done_text' => 'Zásielka je už zaplatená alebo ju nie je možné doplatiť. Stav predplatného nájdete vo svojom účte.',
        'error_badge' => 'Chyba',
        'error_heading' => 'Platbu sa nepodarilo otvoriť',
        'error_text' => 'Skúste to, prosím, o chvíľu znova alebo nám napíšte na :email.',
        'account_link' => 'Prejsť do účtu',
    ],

    // Časté otázky pod konfigurátorom (redesign 2026-10)
    'faq' => [
        ['q' => 'Čo ak mi káva nebude chutiť?', 'a' => 'Napíšte nám na info@kavibox.sk. Typ kávy si môžete kedykoľvek zmeniť v účte, napríklad z filtra na espresso.'],
        ['q' => 'Ako predplatné zruším?', 'a' => 'V účte jedným kliknutím, bez poplatkov. Môžete si dať aj pauzu alebo zmenšiť box.'],
        ['q' => 'Posielate kávu mletú?', 'a' => 'Nie, posielame len zrnkovú kávu. Najlepšie chutí čerstvo zomletá, preto ju odporúčame mlieť až tesne pred prípravou.'],
    ],

    // Konfigurátor boxu (/predplatne, subscriptions/index.blade.php)
    'builder' => [
        'seo' => [
            'title' => 'Zostavte si kávový box – veľkosť, káva, frekvencia | KAVIbox.sk',
            'description' => 'Vyberte si veľkosť boxu, typ kávy a ako často ho chcete dostávať. Bez záväzkov, zrušiť môžete kedykoľvek.',
            'og_title' => 'Zostavte si svoj kávový box | KAVIbox.sk',
            'og_description' => 'Flexibilné kávové predplatné. Vyberte si veľkosť boxu, typ kávy a frekvenciu doručenia. Zrušte kedykoľvek.',
            // inLanguage v JSON-LD
            'in_language' => 'sk-SK',
        ],
        'hero' => [
            'title_1' => 'Zostavte si',
            'title_2' => 'kávový box',
            'intro' => 'Každý mesiac vám pošleme kávy z troch európskych pražiarní, ktoré v bežných obchodoch len tak nenájdete. Tu si nastavíte veľkosť a frekvenciu. Ste u nás prvýkrát?',
            'intro_link' => 'Pozrite sa, ako KAVI funguje',
            'lead' => 'Vyberte si množstvo, typ kávy a frekvenciu doručenia. Flexibilné predplatné prispôsobené vašim potrebám. Zrušte kedykoľvek bez poplatku.',
        ],
        'preparing' => [
            'title' => 'Pripravujeme pre vás nové kávy',
            'text' => 'Pracujeme na výbere tých najlepších káv na nadchádzajúci mesiac. Nové kávy budú dostupné v najbližších dňoch.',
        ],
        'sold_out' => [
            'title' => 'Ospravedlňujeme sa, tento mesiac sú naše kávy vypredané',
            'text' => 'Ďakujeme za veľký záujem! Všetky naše kávy na tento mesiac sú, žiaľ, už rozobraté.',
            'next_month' => 'Predplatné na ďalší mesiac k dispozícii od',
            'date_format' => 'd.m.Y',
        ],
        'browse_shop' => 'Prezrieť obchod',
        'amount' => [
            'title' => 'Množstvo kávy',
            'subtitle' => 'Vyberte si balíček, ktorý vám vyhovuje',
            // „500g · 2 balíčky“ (2–4 balíčky)
            'bags' => 'balíčky',
            'popular' => 'Obľúbené',
            'm_desc' => 'Ideálne pre jednotlivcov alebo páry',
            'l_desc' => 'Najobľúbenejšia voľba',
            'xl_desc' => 'Pre kávových nadšencov',
        ],
        'type' => [
            'title' => 'Preferovaný typ kávy',
            'subtitle' => 'Vyberte si svoj obľúbený spôsob prípravy',
            'espresso' => 'Espresso',
            'espresso_desc' => 'Plné telo, tmavšie praženie',
            'filter' => 'Filter',
            'filter_desc' => 'Ľahšie telo, svetlejšie praženie',
            'mix' => 'Kombinácia',
            'mix_desc' => 'Espresso aj filter',
            'decaf' => '1x bez kofeínu',
            'distribution' => 'Rozdelenie kávy',
        ],
        'frequency' => [
            'title' => 'Frekvencia doručenia',
            'subtitle' => 'Ako často chcete kávu dostávať?',
            'monthly' => 'Každý mesiac',
            'monthly_desc' => 'Na pravidelnú spotrebu',
            'bimonthly' => 'Raz za 2 mesiace',
            'bimonthly_desc' => 'Na strednú spotrebu',
            'quarterly' => 'Raz za 3 mesiace',
            'quarterly_desc' => 'Na občasné pitie',
            'one_time' => 'Jednorazovo',
            'one_time_badge' => 'Vyskúšajte',
            'one_time_desc' => 'Bez predplatného',
            // V zhrnutí po výbere
            'one_time_summary' => 'Jednorazovo (bez predplatného)',
            // :amount = príplatok za jednorazový box (100 Kč / 5 €)
            'upsell' => 'S predplatným ušetríte :amount na každom boxe. Zrušiť ho môžete kedykoľvek, jedným kliknutím.',
            'switch' => 'Prepnúť na predplatné',
        ],
        'summary' => [
            'order_title' => 'Zhrnutie objednávky',
            'subscription_title' => 'Zhrnutie predplatného',
            'quantity' => 'Množstvo:',
            'type' => 'Typ kávy:',
            'frequency' => 'Frekvencia:',
            'one_time_surcharge' => 'Jednorazový príplatok:',
            'price_per_box' => 'Cena za box:',
            'total_price' => 'Celková cena:',
            'per_delivery' => 'pri každom doručení',
            // Pod cenou po výbere frekvencie (0 = jednorazovo). Dopravu zadarmo sľubuje len kavi.cz.
            'frequency_notes' => [
                0 => 'jednorazová objednávka',
                1 => 'každý mesiac',
                2 => 'raz za 2 mesiace',
                3 => 'raz za 3 mesiace',
            ],
            // „750g (3 balenia)“
            'bags' => 'balenia',
            'sold_out' => 'Tento mesiac vypredané',
            'submit' => 'Pokračovať k objednávke',
            'no_commitment' => 'Bez záväzkov',
            'cancel_anytime' => 'Kedykoľvek zrušte',
            // Formát čísel ceny v JS (toLocaleString)
            'number_locale' => 'sk-SK',
        ],
        'shipping' => [
            'title' => 'Termín ďalšej zásielky',
            'text' => 'Objednávky na tento box uzatvárame <strong class="text-dark-800">:cutoff o polnoci</strong>. Kávu odošleme <strong class="text-dark-800">:shipment</strong>.',
        ],
        // Kávy mesiaca pod konfigurátorom; :month = months.adjective_locative
        'lineup' => [
            // Číslo měsíce vybírá předložku: „Vo februárovom boxe“, jinak „V … boxe“
            'title' => '{2} Vo :month boxe|[1,*] V :month boxe',
            'lead' => 'Do boxu vám prídu dve až štyri z týchto káv podľa veľkosti.',
            'link' => 'Viac o káve mesiaca',
        ],
    ],

];
