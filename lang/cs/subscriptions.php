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

];
