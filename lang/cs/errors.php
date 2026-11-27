<?php

/*
|--------------------------------------------------------------------------
| Chybové stránky (resources/views/errors)
|--------------------------------------------------------------------------
|
| Chybové stránky se často vykreslují bez middleware, které nastavuje jazyk,
| proto je šablony volají s explicitním jazykem: __('errors.x', [], $locale).
|
*/

return [
    'layout' => [
        'title' => 'Chyba | KAVI.cz',
    ],

    'links' => [
        'home' => 'Úvod',
        'coffee_boxes' => 'Kávové boxy',
        'shop' => 'Obchod',
        'gifts' => 'Dárky',
        'contact' => 'Kontakt',
        'terms' => 'Podmínky',
        'privacy' => 'Ochrana údajů',
        'roasteries' => 'Pražírny',
        'coffee_of_the_month' => 'Káva měsíce',
        'how_it_works' => 'Jak to funguje',
        'back_home' => 'Zpět na úvod',
    ],

    'common' => [
        'need_help' => 'Potřebujete pomoc?',
        'email' => 'Email',
        'contact_form' => 'Kontaktní formulář',
        'send_message' => 'Napište nám',
        'error_code' => 'Kód chyby',
    ],

    'forbidden' => [
        'title' => 'Přístup odepřen | KAVI.cz',
        'heading_1' => 'Přístup',
        'heading_2' => 'Odepřen',
        'description' => 'K této stránce nemáte oprávnění. Pokud si myslíte, že byste měli mít přístup, přihlaste se nebo kontaktujte podporu.',
        'sign_in_button' => 'Přihlásit se',
        'reasons_title' => 'Časté příčiny této chyby',
        'reason_login' => 'Musíte být přihlášeni',
        'reason_session' => 'Platnost vaší relace vypršela',
        'reason_restricted' => 'Obsah je omezen',
        'sign_in_label' => 'Přihlášení',
        'sign_in_link' => 'Přihlaste se k účtu',
        'code_label' => 'Zakázáno',
    ],

    'not_found' => [
        'title' => 'Stránka nenalezena | KAVI.cz',
        'heading_1' => 'Stránka',
        'heading_2' => 'Nenalezena',
        'description' => 'Stránka, kterou hledáte, neexistuje nebo byla přesunuta. Možná jste se překlepli v adrese, nebo odkaz už není platný.',
        'maybe_looking_for' => 'Možná hledáte',
        'learn_more' => 'Zjistěte více',
        'code_label' => 'Nenalezeno',
    ],

    'page_expired' => [
        'title' => 'Platnost stránky vypršela | KAVI.cz',
        'heading_1' => 'Platnost',
        'heading_2' => 'Vypršela',
        'description' => 'Vaše relace vypršela z bezpečnostních důvodů. Obnovte prosím stránku a zkuste to znovu.',
        'refresh' => 'Obnovit stránku',
        'why_title' => 'Proč se to stalo?',
        'why_text' => 'K této chybě dochází, když stránka zůstane otevřená příliš dlouho bez aktivity. Je to bezpečnostní opatření pro ochranu vašich dat. Stačí stránku obnovit a pokračovat.',
        'quick_fix' => 'Rychlé řešení',
        'step_1' => 'Klikněte na tlačítko "Obnovit stránku" výše',
        'step_2' => 'Nebo stiskněte Ctrl+R (Cmd+R na Macu)',
        'step_3' => 'Vyplňte formulář znovu',
        'code_label' => 'Relace vypršela',
    ],

    'server_error' => [
        'title' => 'Něco se pokazilo | KAVI.cz',
        'heading_1' => 'Něco se',
        'heading_2' => 'Pokazilo',
        'description' => 'Omlouváme se, ale na serveru došlo k chybě. Náš tým už na tom pracuje. Zkuste to prosím za chvíli znovu.',
        'try_again' => 'Zkusit znovu',
        'what_to_do' => 'Co můžete udělat?',
        'tip_refresh' => 'Zkuste obnovit stránku',
        'tip_later' => 'Vraťte se později',
        'tip_contact' => 'Kontaktujte nás, pokud problém přetrvává',
        'contact_us' => 'Kontaktujte nás',
        'on_it_label' => 'Pracujeme na tom',
        'on_it_text' => 'Náš tým byl informován',
        'code_label' => 'Interní chyba serveru',
    ],

    'maintenance' => [
        'title' => 'Údržba | KAVI.cz',
        'heading_1' => 'Probíhá',
        'heading_2' => 'Údržba',
        'description' => 'Náš web právě prochází údržbou, abychom vám mohli nabídnout ještě lepší služby. Už brzy budeme zpět s čerstvou kávou!',
        'estimated_return_label' => 'Předpokládaný návrat',
        'estimated_return' => 'Během několika minut',
        'check_availability' => 'Zkontrolovat dostupnost',
        'stay_updated' => 'Zůstaňte v obraze',
        'newsletter_text' => 'Přihlaste se k odběru newsletteru a buďte první, kdo se dozví o našem návratu.',
        'email_placeholder' => 'VÁŠ E-MAIL',
        'subscribe' => 'Odebírat',
        'sending' => 'ODESÍLÁM...',
        'subscribe_error' => 'Došlo k chybě. Zkuste to prosím později.',
        'whats_happening' => 'Co se děje?',
        'update_performance' => 'Vylepšujeme výkon webu',
        'update_features' => 'Přidáváme nové funkce',
        'update_security' => 'Aktualizujeme zabezpečení',
        'questions' => 'Dotazy?',
        'status' => 'Stav',
        'code_label' => 'Služba nedostupná',
    ],
];
