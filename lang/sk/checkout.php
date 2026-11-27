<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Checkout Page Translations
    |--------------------------------------------------------------------------
    */

    // Page titles
    'page_title' => 'Pokladňa - KAVIbox.sk',
    'page_title_subscription' => 'Pokladňa - Kávové predplatné',
    'title' => 'Dokončenie objednávky',
    'title_subscription' => 'Dokončenie objednávky predplatného',
    'subtitle' => 'Ešte pár informácií a vaša káva bude na ceste k vám',
    'subtitle_subscription' => 'Ešte pár informácií a vaša káva bude pravidelne na ceste k vám',

    // Order summary
    'order_summary' => 'Súhrn objednávky',
    'subscription_summary' => 'Súhrn predplatného',
    'subtotal' => 'Medzisúčet',
    'subtotal_without_vat' => 'Medzisúčet (bez DPH)',
    'shipping' => 'Doprava',
    'shipping_free' => 'Zadarmo',
    'shipping_free_subscription' => 'Zadarmo (predplatné)',
    'shipping_free_coupon' => 'Zadarmo (kupón)',
    'shipping_free_digital' => 'Zadarmo (digitálny produkt)',
    'shipping_calculating' => 'Počítam...',
    'shipping_at_checkout' => 'Dopočíta sa v pokladni',
    'shipping_unavailable' => 'Nedostupné',
    'shipping_error' => 'Chyba',
    'discount' => 'Zľava',
    'vat' => 'DPH (21 %)',
    'total' => 'Spolu',
    'total_per_month' => 'Spolu / mesiac',
    'incl_vat' => '(vrátane DPH)',
    'plus_shipping' => '+ doprava',
    'gift_voucher' => 'Darčekový voucher',
    'vat_before_voucher' => 'DPH je vypočítaná z ceny pred odpočítaním voucheru',

    // Pokladňa (veľkými písmenami podľa dizajnu)
    'hero_title' => 'POKLADŇA',
    'hero_title_subscription' => 'POKLADŇA',
    'free_caps' => 'ZADARMO',
    'complete_order_caps' => 'DOKONČIŤ OBJEDNÁVKU',

    // Formát čísel ceny v JS (toLocaleString)
    'number_locale' => 'sk-SK',

    // Contact information
    'contact_info' => 'Kontaktné údaje',
    'have_account' => 'Už máte účet?',
    'login_faster' => 'Prihláste sa pre rýchlejšie dokončenie objednávky alebo vám pošleme prihlasovací odkaz.',
    'login_faster_subscription' => 'Prihláste sa pre rýchlejšie dokončenie objednávky.',
    'login' => 'Prihlásiť sa',
    'send_magic_link' => 'Poslať prihlasovací odkaz',
    'magic_link_title' => 'Poslať prihlasovací odkaz',
    'magic_link_description' => 'Zadajte svoj e-mail a my vám pošleme prihlasovací odkaz.',
    'email_confirmation_note' => 'Na tento e-mail vám pošleme potvrdenie a odkaz na dokončenie registrácie.',
    'phone_helps' => 'Telefón pomôže doručovacej službe pri riešení prípadných problémov.',
    'phone_format_hint' => 'Pre CZ: +420 a 9 číslic, pre SK: +421 a 9 číslic',

    // Billing address
    'billing_address' => 'Fakturačná adresa',

    // Form fields
    'fields' => [
        'name' => 'Meno a priezvisko',
        'email' => 'E-mail',
        'phone' => 'Telefón',
        'phone_optional' => 'Telefón (nepovinné)',
        'street' => 'Ulica a číslo domu',
        'street_placeholder' => 'Napr. Hlavná 123',
        'city' => 'Mesto',
        'city_placeholder' => 'Napr. Bratislava',
        'postal_code' => 'PSČ',
        'postal_code_placeholder' => '123 45',
        'country' => 'Krajina',
        'select_country' => 'Vyberte krajinu',
        'notes' => 'Poznámka',
        'notes_optional' => '/ NEPOVINNÉ',
        'notes_placeholder' => 'NAPR. PROSÍM, ZAZVOŇTE NA 2. POSCHODÍ ALEBO NECHAJTE NA VRÁTNICI',
    ],

    // Nákup na firmu (nepovinné firemné údaje na faktúre)
    'company' => [
        'checkbox_label' => 'Nakupujem na firmu',
        'hint' => 'Faktúru vystavíme na firmu s uvedeným IČO a IČ DPH.',
        'name' => 'Názov firmy',
        'name_placeholder' => 'Napr. Kaviareň s.r.o.',
        'registration_no' => 'IČO',
        'vat_no' => 'IČ DPH',
        'different_address' => 'Sídlo firmy je na inej adrese',
        'address_title' => 'Sídlo firmy',
        'summary_title' => 'Firma',
    ],

    // Announcement notice (admin: Hlášky)
    'announcement' => [
        'default_title' => 'Informácia',
    ],

    // Pickup point
    'pickup_point' => [
        'title' => 'Výber výdajného miesta',
        'selected' => 'Vybrané výdajné miesto:',
        'change' => 'Zmeniť',
        'select' => 'Vybrať výdajné miesto Packeta',
        'info' => 'Kávu vám doručíme na vybrané výdajné miesto',
    ],

    // Digital product
    'digital_product' => [
        'title' => 'Digitálny produkt',
        'description' => 'Váš produkt vám doručíme elektronicky na zadaný e-mail. Výdajné miesto netreba vyberať.',
    ],

    // Subscription addon
    'subscription_addon' => [
        'title' => 'Doprava s predplatným',
        'checkbox_label' => 'Zaradiť do ďalšej zásielky predplatného',
        'free_shipping' => 'DOPRAVA ZADARMO',
        'capacity_full' => 'KAPACITA VYČERPANÁ',
        'select_subscription' => 'Vyberte predplatné:',
        'capacity_label' => 'Kapacita doplnkového tovaru:',
        'slots_available' => 'voľných slotov',
        'used_slot' => 'Použitý slot',
        'cart_slot' => 'Košík',
        'free_slot' => 'Voľný slot',
        'planned_delivery' => '📦 Plánované odoslanie:',
        'capacity_warning' => '❌ Kapacita doplnkového tovaru je pre toto predplatné naplnená.',
        'try_another' => 'Skúste vybrať iné predplatné.',
        'cart_has' => 'Košík obsahuje',
        'items' => 'kusov',
        'but_only' => 'ale k dispozícii sú len',
        'slot_available' => 'slot',
        'slots_available_plural' => 'sloty',
        'info' => 'Ak zaškrtnete túto možnosť, tovar odošleme spolu s vaším predplatným a neplatíte poštovné. Do jednej zásielky môžete pridať najviac 3 kusy tovaru. <strong>Tovar bude doručený na výdajné miesto nastavené pri vašom predplatnom.</strong>',
    ],

    // Payment
    'payment' => [
        'title' => 'Spôsob platby',
        'card' => 'Platobnou kartou',
        'card_description' => 'Po odoslaní objednávky vás presmerujeme na bezpečnú platobnú bránu',
        'we_accept' => 'Akceptujeme:',
        'card_info' => 'PLATBA KARTOU. PO ODOSLANÍ OBJEDNÁVKY VÁS PRESMERUJEME NA BEZPEČNÚ PLATOBNÚ BRÁNU. AKCEPTUJEME VISA, MASTERCARD, APPLE PAY A GOOGLE PAY.',
        'card_description_full' => 'PO ODOSLANÍ OBJEDNÁVKY VÁS PRESMERUJEME NA BEZPEČNÚ PLATOBNÚ BRÁNU. AKCEPTUJEME VISA, MASTERCARD, APPLE PAY A GOOGLE PAY.',
    ],

    // Coupon
    'coupon' => [
        'title' => 'Mám zľavový kupón',
        'placeholder' => 'ZLAVOVYKOD',
        'apply' => 'Použiť',
        'remove' => 'Odstrániť',
        'applied' => 'Kupón bol uplatnený',
        'discount_label' => 'Zľava',
        'valid' => 'Kupón je platný!',
        'invalid_fallback' => 'Neplatný kupón',
        'not_found' => 'Kupón sa nenašiel.',
        'expired' => 'Kupón už nie je platný.',
        'total_limit' => 'Kupón dosiahol maximálny počet použití.',
        'user_limit' => 'Tento kupón ste už použili maximálny počet ráz.',
        'not_for_orders' => 'Tento kupón nie je možné použiť na jednorazové objednávky.',
        'not_for_subscriptions' => 'Tento kupón nie je možné použiť na predplatné.',
        'subscription_already_used' => 'Zľavový kód na predplatné je možné použiť len raz. Zľavu na predplatné ste už v minulosti využili.',
        'min_order_value' => 'Minimálna hodnota objednávky pre tento kupón je :min.',
        'exceeds_cart_value' => 'Hodnota kupónu (:discount) presahuje hodnotu vášho košíka (:total).',
    ],

    // 100% discount notice
    'full_discount' => [
        'title' => '🎉 100% zľava!',
        'description' => 'Vaše predplatné je <strong>úplne zadarmo</strong> vďaka kupónu :code. Po dokončení objednávky sa predplatné okamžite aktivuje.',
    ],

    // Subscription details
    'subscription' => [
        'quantity' => 'Množstvo:',
        'bags' => 'balenia',
        'coffee_type' => 'Typ kávy:',
        'espresso' => 'Espresso',
        'filter' => 'Filter',
        'mix' => 'Kombinácia',
        'incl_decaf' => '(vr. 1× decaf)',
        'frequency' => 'Frekvencia:',
        'every_month' => 'Každý mesiac',
        'every_2_months' => 'Raz za 2 mesiace',
        'every_3_months' => 'Raz za 3 mesiace',
        'bags_without_vat' => 'balenia kávy (bez DPH):',
    ],

    // Delivery info
    'delivery_info' => [
        'title' => 'Informácie o doručení',
        'shipping_note' => 'Zásielky odosielame vždy <strong>20. deň v mesiaci</strong>. Objednávky do 15. v mesiaci zaradíme do aktuálnej zásielky, objednávky od 16. dňa až do nasledujúcej.',
    ],

    // Buttons
    'buttons' => [
        'complete_order' => 'Dokončiť objednávku',
        'back_to_cart' => 'Späť do košíka',
        'back_to_configurator' => 'Späť na konfigurátor',
    ],

    // Terms
    'terms' => [
        'agree' => 'Súhlasím s',
        'terms_of_service' => 'obchodnými podmienkami',
        'and' => 'a',
        'privacy_policy' => 'zásadami ochrany osobných údajov',
    ],

    // Trust badges
    'trust' => [
        'secure_payment' => 'Bezpečná platba',
        'eco_packaging' => 'Ekologické balenie',
        'coffee_from_europe' => 'Káva z celej Európy',
        'no_commitment' => 'Bez záväzkov - kedykoľvek zrušte',
        'fresh_coffee' => 'Čerstvo pražená káva',
        'free_shipping_always' => 'Starostlivo zabalené',
    ],

    // Errors
    'errors' => [
        'country_unavailable' => 'Do vybranej krajiny momentálne nedoručujeme.',
        'subscription_country_unavailable' => 'Do vybranej krajiny momentálne nedoručujeme predplatné.',
    ],

    // Messages
    'messages' => [
        'cart_empty' => 'Váš košík je prázdny.',
        'payment_cancelled' => 'Platba bola zrušená. Môžete pokračovať v objednávke alebo upraviť košík.',
    ],

];
