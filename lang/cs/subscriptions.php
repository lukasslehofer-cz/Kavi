<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subscription Page Translations
    |--------------------------------------------------------------------------
    */

    'title' => 'Kávové předplatné',
    'subtitle' => 'Objevte výjimečnou výběrovou kávu doručenou přímo k vám',
    
    'configurator' => [
        'title' => 'Sestavte si vlastní box',
        'step1' => 'Vyberte velikost boxu',
        'step2' => 'Zvolte typ kávy',
        'step3' => 'Vyberte frekvenci doručení',
        'step4' => 'Zadejte doručovací údaje',
    ],
    
    'box_sizes' => [
        'm_box' => 'M Box',
        'l_box' => 'L Box',
        'xl_box' => 'XL Box',
        'bags' => ':count balení × 250g',
        'coffees' => ':count druhy výběrové kávy',
    ],
    
    'coffee_types' => [
        'espresso' => 'Espresso',
        'filter' => 'Filtr',
        'mix' => 'Mix',
        'omni' => 'Omni-roast',
        'decaf' => 'Bezkofeinová',
    ],
    
    'frequency' => [
        'monthly' => 'Měsíčně',
        'bimonthly' => 'Každé 2 měsíce',
        'quarterly' => 'Každé 3 měsíce',
    ],
    
    'features' => [
        'free_shipping' => 'Doprava zdarma',
        'cancel_anytime' => 'Zrušení nebo přestávka kdykoliv',
        'fresh_roasted' => 'Čerstvě pražená káva',
        'curated' => 'Pečlivě vybraný výběr',
    ],
    
    'pricing' => [
        'per_box' => '/box',
        'per_month' => '/měsíc',
        'starting_from' => 'Od',
    ],
    
    'cta' => [
        'subscribe' => 'Objednat předplatné',
        'configure' => 'Sestavte si box',
        'one_time' => 'Vyzkoušet jednorázově',
    ],
    
    'coffee_of_month' => [
        'title' => 'Káva měsíce',
        'subtitle' => 'Objevte výběr pro tento měsíc',
        'explore' => 'Prozkoumat kávy na :month',
    ],
    
    'how_it_works' => [
        'title' => 'Jak to funguje',
        'step1_title' => 'Vyberte si plán',
        'step1_desc' => 'Zvolte množství kávy a frekvenci dodání podle vašich potřeb',
        'step2_title' => 'Personalizujte',
        'step2_desc' => 'Vyberte typ kávy, způsob přípravy a doručovací adresu',
        'step3_title' => 'Vyzvedněte si box',
        'step3_desc' => 'Čerstvě praženou kávu doručíme na vámi vybrané místo',
        'step4_title' => 'Vychutnejte si',
        'step4_desc' => 'Užijte si skvělou výběrovou kávu a těšte se na další kávový box',
    ],

    // Doplacení rozesílky vynechané kvůli neplacení (odkaz z e-mailu)
    'pay_skipped' => [
        'page_title' => 'Doplacení rozesílky',
        'success_badge' => 'Zaplaceno',
        'success_heading' => 'Děkujeme, box posíláme',
        'success_text' => 'Platba proběhla. Box vám pošleme s nejbližší rozesílkou a kartu jsme si uložili pro další platby předplatného. Potvrzení vám přijde e-mailem.',
        'processing_text' => 'Platbu ještě potvrzujeme. Jakmile ji Stripe potvrdí, pošleme vám e-mail.',
        'details_title' => 'Vaše předplatné',
        'subscription_number' => 'Číslo předplatného',
        'shipment' => 'Box odešleme',
        'paid' => 'Zaplaceno',
        'card' => 'Karta pro další platby',
        'next_payment' => 'Další platba',
        'cancelled_badge' => 'Platba zrušena',
        'cancelled_heading' => 'Platba neproběhla',
        'cancelled_text' => 'Platbu jste zrušili. Pokud box chcete, vraťte se k odkazu v e-mailu, platí do dnešní půlnoci.',
        'expired_badge' => 'Odkaz vypršel',
        'expired_heading' => 'Odkaz už neplatí',
        'expired_text' => 'Odkaz k platbě platil jen v den odeslání e-mailu. Předplatné pokračuje další rozesílkou. Pokud o box stále stojíte, napište nám na :email.',
        'done_badge' => 'Vyřízeno',
        'done_heading' => 'Tato rozesílka je vyřízená',
        'done_text' => 'Rozesílka je už zaplacená nebo ji nejde doplatit. Stav předplatného najdete ve svém účtu.',
        'error_badge' => 'Chyba',
        'error_heading' => 'Platbu se nepodařilo otevřít',
        'error_text' => 'Zkuste to prosím za chvíli znovu, nebo nám napište na :email.',
        'account_link' => 'Přejít do účtu',
    ],

    // Časté dotazy pod konfigurátorem (redesign 2026-10)
    'faq' => [
        ['q' => 'Co když mi káva nebude chutnat?', 'a' => 'Napište nám na info@kavi.cz. Typ kávy si můžete kdykoli změnit v účtu, například z filtru na espresso.'],
        ['q' => 'Jak předplatné zruším?', 'a' => 'V účtu jedním klikem, bez poplatků. Můžete také dát pauzu nebo zmenšit box.'],
        ['q' => 'Posíláte kávu mletou?', 'a' => 'Ne, posíláme jen zrnkovou kávu. Nejlépe chutná čerstvě namletá, proto ji doporučujeme mlít až těsně před přípravou.'],
    ],

    // Konfigurátor boxu (/predplatne, subscriptions/index.blade.php)
    'builder' => [
        'seo' => [
            'title' => 'Sestavte si kávový box – velikost, káva, frekvence | KAVI.cz',
            'description' => 'Vyberte velikost boxu, typ kávy a jak často ho chcete dostávat. Bez závazků, doprava zdarma.',
            'og_title' => 'Sestavte si svůj kávový box | KAVI.cz',
            'og_description' => 'Flexibilní kávové předplatné s dopravou zdarma. Vyberte si velikost boxu, typ kávy a frekvenci dodání. Zrušte kdykoliv.',
            // inLanguage v JSON-LD
            'in_language' => 'cs-CZ',
        ],
        'hero' => [
            'title_1' => 'Sestavte si',
            'title_2' => 'kávový box',
            'intro' => 'Každý měsíc vám pošleme kávy ze tří evropských pražíren, které v Česku běžně nekoupíte. Tady si nastavíte velikost a frekvenci. Poprvé u nás?',
            'intro_link' => 'Podívejte se, jak KAVI funguje',
            'lead' => 'Vyberte si množství, typ kávy a frekvenci dodání. Flexibilní předplatné přizpůsobené vašim potřebám. Zrušte kdykoliv bez poplatku.',
        ],
        'preparing' => [
            'title' => 'Připravujeme pro vás nové kávy',
            'text' => 'Pracujeme na výběru těch nejlepších káv pro nadcházející měsíc. Nové kávy budou dostupné v nejbližších dnech.',
        ],
        'sold_out' => [
            'title' => 'Omlouváme se, tento měsíc jsou naše kávy vyprodané',
            'text' => 'Děkujeme za velký zájem! Všechny naše kávy pro tento měsíc jsou bohužel již rozebrané.',
            'next_month' => 'Předplatné na další měsíc k dispozici od',
            'date_format' => 'd.m.Y',
        ],
        'browse_shop' => 'Prohlédnout obchod',
        'amount' => [
            'title' => 'Množství kávy',
            'subtitle' => 'Vyberte balíček, který vám vyhovuje',
            // „500g · 2 balíčky“ (2–4 balíčky)
            'bags' => 'balíčky',
            'popular' => 'Oblíbené',
            'm_desc' => 'Ideální pro jednotlivce nebo páry',
            'l_desc' => 'Nejpopulárnější volba',
            'xl_desc' => 'Pro kávové nadšence',
        ],
        'type' => [
            'title' => 'Preferovaný typ kávy',
            'subtitle' => 'Vyberte si váš oblíbený způsob přípravy',
            'espresso' => 'Espresso',
            'espresso_desc' => 'Plné tělo, tmavší pražení',
            'filter' => 'Filtr',
            'filter_desc' => 'Lehčí tělo, světlejší pražení',
            'mix' => 'Kombinace',
            'mix_desc' => 'Espresso i filtr',
            'decaf' => '1x bez kofeinu',
            'distribution' => 'Rozdělení kávy',
        ],
        'frequency' => [
            'title' => 'Frekvence dodání',
            'subtitle' => 'Jak často chcete kávu dostávat?',
            'monthly' => 'Každý měsíc',
            'monthly_desc' => 'Pro pravidelnou spotřebu',
            'bimonthly' => 'Jednou za 2 měsíce',
            'bimonthly_desc' => 'Pro střední spotřebu',
            'quarterly' => 'Jednou za 3 měsíce',
            'quarterly_desc' => 'Pro občasné pití',
            'one_time' => 'Jednorázově',
            'one_time_badge' => 'Zkuste to',
            'one_time_desc' => 'Bez předplatného',
            // Ve shrnutí po výběru
            'one_time_summary' => 'Jednorázově (bez předplatného)',
            // :amount = příplatek za jednorázový box (100 Kč / €5)
            'upsell' => 'S předplatným ušetříte :amount za každý box. Zrušit ho můžete kdykoli, jedním klikem.',
            'switch' => 'Přepnout na předplatné',
        ],
        'summary' => [
            'order_title' => 'Shrnutí objednávky',
            'subscription_title' => 'Shrnutí předplatného',
            'quantity' => 'Množství:',
            'type' => 'Typ kávy:',
            'frequency' => 'Frekvence:',
            'one_time_surcharge' => 'Příplatek jednorázový:',
            'price_per_box' => 'Cena za box:',
            'total_price' => 'Celková cena:',
            'per_delivery' => 'při každé dodávce',
            // Pod cenou po výběru frekvence (0 = jednorázově). Dopravu zdarma slibuje jen kavi.cz.
            'frequency_notes' => [
                0 => 'jednorázová objednávka · doprava zdarma',
                1 => 'každý měsíc · doprava zdarma',
                2 => 'jednou za 2 měsíce · doprava zdarma',
                3 => 'jednou za 3 měsíce · doprava zdarma',
            ],
            // „750g (3 balení)“
            'bags' => 'balení',
            'sold_out' => 'Tento měsíc vyprodáno',
            'submit' => 'Pokračovat k objednávce',
            'no_commitment' => 'Bez závazků',
            'cancel_anytime' => 'Kdykoli zrušte',
            // Formát čísel ceny v JS (toLocaleString)
            'number_locale' => 'cs-CZ',
        ],
        'shipping' => [
            'title' => 'Termín následující rozesílky',
            'text' => 'Objednávky na tento box uzavíráme <strong class="text-dark-800">:cutoff o půlnoci</strong>. Rozesílka kávy proběhne <strong class="text-dark-800">:shipment</strong>.',
        ],
        // Kávy měsíce pod konfigurátorem; :month = months.adjective_locative
        'lineup' => [
            'title' => 'V :month boxu',
            'lead' => 'Do boxu vám přijdou dvě až čtyři z těchto káv podle velikosti.',
            'link' => 'Více o kávě měsíce',
        ],
    ],

];
