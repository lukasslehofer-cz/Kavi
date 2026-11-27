<?php

/*
|--------------------------------------------------------------------------
| Chybové stránky (resources/views/errors)
|--------------------------------------------------------------------------
|
| Chybové stránky sa často vykresľujú bez middleware, ktorý nastavuje jazyk,
| preto ich šablóny volajú s explicitným jazykom: __('errors.x', [], $locale).
|
*/

return [
    'layout' => [
        'title' => 'Chyba | KAVIbox.sk',
    ],

    'links' => [
        'home' => 'Úvod',
        'coffee_boxes' => 'Kávové boxy',
        'shop' => 'Obchod',
        'gifts' => 'Darčeky',
        'contact' => 'Kontakt',
        'terms' => 'Podmienky',
        'privacy' => 'Ochrana údajov',
        'roasteries' => 'Pražiarne',
        'coffee_of_the_month' => 'Káva mesiaca',
        'how_it_works' => 'Ako to funguje',
        'back_home' => 'Späť na úvod',
    ],

    'common' => [
        'need_help' => 'Potrebujete pomoc?',
        'email' => 'E-mail',
        'contact_form' => 'Kontaktný formulár',
        'send_message' => 'Napíšte nám',
        'error_code' => 'Kód chyby',
    ],

    'forbidden' => [
        'title' => 'Prístup zamietnutý | KAVIbox.sk',
        'heading_1' => 'Prístup',
        'heading_2' => 'Zamietnutý',
        'description' => 'K tejto stránke nemáte oprávnenie. Ak si myslíte, že by ste mali mať prístup, prihláste sa alebo kontaktujte podporu.',
        'sign_in_button' => 'Prihlásiť sa',
        'reasons_title' => 'Časté príčiny tejto chyby',
        'reason_login' => 'Musíte byť prihlásení',
        'reason_session' => 'Platnosť vašej relácie vypršala',
        'reason_restricted' => 'Obsah je obmedzený',
        'sign_in_label' => 'Prihlásenie',
        'sign_in_link' => 'Prihláste sa do účtu',
        'code_label' => 'Zakázané',
    ],

    'not_found' => [
        'title' => 'Stránka sa nenašla | KAVIbox.sk',
        'heading_1' => 'Stránka',
        'heading_2' => 'Nenájdená',
        'description' => 'Stránka, ktorú hľadáte, neexistuje alebo bola presunutá. Možno ste sa pomýlili v adrese alebo odkaz už nie je platný.',
        'maybe_looking_for' => 'Možno hľadáte',
        'learn_more' => 'Zistite viac',
        'code_label' => 'Nenájdené',
    ],

    'page_expired' => [
        'title' => 'Platnosť stránky vypršala | KAVIbox.sk',
        'heading_1' => 'Platnosť',
        'heading_2' => 'Vypršala',
        'description' => 'Vaša relácia vypršala z bezpečnostných dôvodov. Obnovte prosím stránku a skúste to znova.',
        'refresh' => 'Obnoviť stránku',
        'why_title' => 'Prečo sa to stalo?',
        'why_text' => 'Táto chyba nastáva, keď stránka zostane otvorená príliš dlho bez aktivity. Je to bezpečnostné opatrenie na ochranu vašich údajov. Stačí stránku obnoviť a pokračovať.',
        'quick_fix' => 'Rýchle riešenie',
        'step_1' => 'Kliknite na tlačidlo "Obnoviť stránku" vyššie',
        'step_2' => 'Alebo stlačte Ctrl+R (Cmd+R na Macu)',
        'step_3' => 'Vyplňte formulár znova',
        'code_label' => 'Relácia vypršala',
    ],

    'server_error' => [
        'title' => 'Niečo sa pokazilo | KAVIbox.sk',
        'heading_1' => 'Niečo sa',
        'heading_2' => 'Pokazilo',
        'description' => 'Ospravedlňujeme sa, ale na serveri došlo k chybe. Náš tím už na tom pracuje. Skúste to prosím o chvíľu znova.',
        'try_again' => 'Skúsiť znova',
        'what_to_do' => 'Čo môžete urobiť?',
        'tip_refresh' => 'Skúste obnoviť stránku',
        'tip_later' => 'Vráťte sa neskôr',
        'tip_contact' => 'Kontaktujte nás, ak problém pretrváva',
        'contact_us' => 'Kontaktujte nás',
        'on_it_label' => 'Pracujeme na tom',
        'on_it_text' => 'Náš tím bol informovaný',
        'code_label' => 'Interná chyba servera',
    ],

    'maintenance' => [
        'title' => 'Údržba | KAVIbox.sk',
        'heading_1' => 'Prebieha',
        'heading_2' => 'Údržba',
        'description' => 'Na našom webe práve prebieha údržba, aby sme vám mohli ponúknuť ešte lepšie služby. Čoskoro sa vrátime s čerstvou kávou!',
        'estimated_return_label' => 'Predpokladaný návrat',
        'estimated_return' => 'O niekoľko minút',
        'check_availability' => 'Skontrolovať dostupnosť',
        'stay_updated' => 'Buďte v obraze',
        'newsletter_text' => 'Prihláste sa na odber newslettera a buďte prvý, kto sa dozvie o našom návrate.',
        'email_placeholder' => 'VÁŠ E-MAIL',
        'subscribe' => 'Odoberať',
        'sending' => 'ODOSIELAM...',
        'subscribe_error' => 'Došlo k chybe. Skúste to prosím neskôr.',
        'whats_happening' => 'Čo sa deje?',
        'update_performance' => 'Vylepšujeme výkon webu',
        'update_features' => 'Pridávame nové funkcie',
        'update_security' => 'Aktualizujeme zabezpečenie',
        'questions' => 'Otázky?',
        'status' => 'Stav',
        'code_label' => 'Služba nedostupná',
    ],
];
