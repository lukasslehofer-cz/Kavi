<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dárky: dárkové předplatné a poukaz na e-shop
    |--------------------------------------------------------------------------
    |
    | Data (ceny, data uzávěrky a rozesílky, adresy) doplňuje kód z databáze.
    |
    */

    'months' => '{1} :count měsíc|[2,4] :count měsíce|[5,*] :count měsíců',
    'boxes' => '{1} :count box|[2,4] :count boxy|[5,*] :count boxů',
    'months_word' => [3 => 'Tři měsíce', 6 => 'Šest měsíců'],
    'box_label' => ':box Box · :count balíčky měsíčně',

    'item_name' => [
        'subscription' => 'Dárkové předplatné :box Box, :months',
        'voucher' => 'Dárkový poukaz :amount',
    ],

    'certificate' => [
        'eyebrow_subscription' => 'Dárkový certifikát',
        'eyebrow_voucher' => 'Dárkový poukaz',
        'chips_subscription' => ['Každý měsíc', 'Tři evropské pražírny'],
        'chips_voucher' => ['Výběrová káva', 'Z evropských pražíren'],
        'headline_middle' => 'výběrové',
        'headline_last' => 'kávy',
        'voucher_sub' => 'Na výběrovou kávu',
        'to' => 'Pro',
        'from' => 'Od',
        'box' => 'Box',
        'code' => 'Kód',
        'quote_open' => '„',
        'quote_close' => '“',
        'footer_subscription' => 'Aktivujte na :url · platí do :date',
        'footer_voucher' => 'Na celý e-shop :site · platí do :date',
        'sample' => [
            'recipient' => 'Petra',
            'giver' => 'Tomáš',
            'note' => 'Ať ti každé ráno chutná. Veselé Vánoce.',
        ],
        'back' => [
            'eyebrow' => 'Dárek od KAVI',
            'title' => 'Jak dárek',
            'title_accent' => 'uplatnit',
            'steps' => [
                ['t' => 'Otevřete :url', 'd' => 'Nebo naskenujte QR kód vpravo dole.'],
                ['t' => 'Zadejte kód', 'd' => 'Najdete ho na přední straně v černém pruhu.'],
                ['t' => 'Vyberte si kávu', 'd' => 'U předplatného typ kávy a výdejní místo. Poukaz se odečte v pokladně.'],
            ],
            'info_subscription' => 'Každý měsíc tři evropské pražírny, první box odchází v nejbližší rozesílce kolem 20. dne. Doprava zdarma. Z každého boxu posíláme 5 Kč na Water.org.',
            'info_voucher' => 'Výběrová káva z evropských pražíren: kávové boxy i jednotlivé kávy v e-shopu. Z každého boxu posíláme 5 Kč na Water.org.',
            'contact' => 'Dotazy: :email · :site',
        ],
    ],

    // Stránka nákupu dárků
    'page' => [
        'title' => 'Dárkové předplatné a poukazy na kávu | KAVI.cz',
        'meta_description' => 'Darujte výběrovou kávu: dárkové předplatné na 3 nebo 6 měsíců, nebo poukaz na celý e-shop. Certifikát přijde hned e-mailem v PDF k tisku.',
        'hero_title' => ['Darujte', 'kávu'],
        'hero_text' => 'Dárkové předplatné na 3 nebo 6 měsíců, nebo poukaz na celý e-shop. Certifikát přijde hned e-mailem v PDF k tisku.',

        'preview_label' => 'Náhled certifikátu',
        'preview_format' => 'PDF · A5 na šířku',
        'total' => 'Celková cena',
        'total_note_subscription' => ':box Box :price × :months · doprava zdarma',
        'total_note_voucher' => 'certifikát v PDF hned po zaplacení',
        'cta' => 'Koupit dárek',
        'bullets' => ['Certifikát hned', 'Bez obnovení'],

        'step_type' => 'Co chcete darovat',
        'step_type_sub' => 'Předplatné, nebo poukaz na cokoli z e-shopu',
        'type_subscription' => 'Dárkové předplatné',
        'type_subscription_desc' => 'Box s kávou každý měsíc, 3 nebo 6 měsíců',
        'type_voucher' => 'Dárkový poukaz',
        'type_voucher_desc' => 'Na cokoli z e-shopu, obdarovaný si vybere sám',
        'from' => 'od :price',

        'step_length' => 'Na jak dlouho',
        'step_length_sub' => 'Každý měsíc jeden box, platíte jednou předem',
        'length_desc' => ':boxes · platíte jednou',

        'step_size' => 'Množství kávy',
        'step_size_sub' => 'Kolik kávy má každý měsíc přijít',
        'size_desc' => [
            2 => 'Na dvě kávy denně',
            3 => 'Na tři kávy denně',
            4 => 'Pro domácnost nebo kancelář',
        ],

        'step_amount' => 'Hodnota poukazu',
        'step_amount_sub' => 'Platí na celý e-shop, na boxy i jednotlivé kávy',
        'amount_desc_all' => 'Na kávu z e-shopu nebo první platbu kteréhokoli boxu',
        'amount_desc_some' => 'Na :boxes Box, nebo na nákup v e-shopu',
        'amount_desc_none' => 'Na větší nákup v e-shopu, třeba víc káv najednou',
        'boxes_or' => ' nebo ',
        'amount_note' => 'Poukaz se uplatní najednou, na nákup v hodnotě alespoň jako poukaz. Platí 12 měsíců od nákupu.',

        'step_whom' => 'Pro koho',
        'step_whom_sub' => 'Jména a věnování se propíšou do certifikátu',
        'recipient_name' => 'Jméno obdarovaného',
        'giver_name' => 'Od koho',
        'note' => 'Věnování, nepovinné',
        'note_placeholder' => 'Ať ti každé ráno chutná',
        'delivery_buyer' => 'Pošlete ho mně',
        'delivery_buyer_desc' => 'Certifikát v PDF předám nebo vytisknu',
        'delivery_recipient' => 'Pošlete ho obdarovanému',
        'delivery_recipient_desc' => 'E-mailem ve vámi zvolený den',
        'recipient_email' => 'E-mail obdarovaného',
        'recipient_email_placeholder' => 'petra@email.cz',
        'send_at' => 'Kdy odeslat',

        'gets_title' => 'Co obdarovaný dostane',
        'gets_lead' => 'Každý měsíc box s výběrovou kávou z evropských pražíren, které v Česku běžně nekoupí.',
        'gets_arrows' => [
            '2 až 4 balíčky po 250 g podle velikosti boxu',
            'Pohlednici s profilem kávy a pražírny ke každé kávě',
            'Vlastní volbu espressa, filtru nebo kombinace a výdejního místa',
            'Žádný závazek, dárek skončí posledním boxem',
        ],
        'gets_coffee' => 'Káva měsíce',
        'gets_self' => 'Box pro sebe',

        'how_title' => 'Jak to funguje',
        'how_lead' => 'Od zaplacení k prvnímu boxu',
        'how_steps' => [
            ['t' => 'Vyberete a zaplatíte', 'd' => 'Předplatné nebo poukaz, věnování a komu certifikát poslat.'],
            ['t' => 'Certifikát hned', 'd' => 'PDF k vytištění, nebo ho pošleme rovnou obdarovanému ve vámi zvolený den.'],
            ['t' => 'Obdarovaný aktivuje', 'd' => 'Na :url zadá kód, typ kávy a výdejní místo.'],
            ['t' => 'První box :day. dne', 'd' => 'Odejde v nejbližší rozesílce. Uzávěrka je vždy :cutoff_day. dne v měsíci.'],
        ],

        'corporate' => 'Firemní dárky od 10 kusů, s fakturou na firmu a společným věnováním',
        'corporate_cta' => 'Napsat na :email',

        'faq_title' => 'Časté dotazy k dárkům',
        'faq' => [
            ['q' => 'Obnoví se předplatné samo?', 'a' => 'Ne. Dárek skončí posledním boxem. Obdarovanému pak nabídneme pokračování, nic se nestrhává automaticky.'],
            ['q' => 'Stihnu to do Vánoc?', 'a' => 'Certifikát ano, přijde hned po zaplacení. Krabice s kávou pak odchází podle data aktivace, v nejbližší rozesílce.'],
            ['q' => 'Co když obdarovaný nepije espresso?', 'a' => 'Typ kávy si při aktivaci zvolí sám: espresso, filtr, nebo kombinaci obojího.'],
            ['q' => 'Na co platí poukaz?', 'a' => 'Na kávu a příslušenství v e-shopu i na první platbu kávového boxu. Uplatníte ho najednou v pokladně, nákup musí mít hodnotu alespoň jako poukaz a nevyčerpaný zůstatek se nevrací. Platí 12 měsíců od nákupu.'],
        ],
    ],

    // Stránka uplatnění dárku
    'redeem' => [
        'title' => 'Uplatnit dárek | KAVI.cz',
        'meta_description' => 'Dostali jste kávu od KAVI? Zadejte kód z certifikátu a vyberte si typ kávy.',
        'hero_title' => ['Uplatnit', 'dárek'],
        'hero_text' => 'Dostali jste kávu od KAVI? Zadejte kód z certifikátu a vyberte si typ kávy. Výdejní místo a kontakt doplníte v pokladně, platit nic nebudete.',

        'intro_eyebrow' => 'Dárek od KAVI',
        'intro_title' => ['Kód najdete', 'na certifikátu'],
        'intro_text' => 'Na přední straně v černém pruhu. Má tvar XXXX-XXXX, velká a malá písmena nehrají roli.',
        'intro_steps' => [
            ['t' => 'Zadáte kód', 'd' => 'Ověříme ho a ukážeme, co vám kdo daroval.'],
            ['t' => 'Vyberete kávu', 'd' => 'Espresso, filtr nebo kombinaci a kdy začít.'],
            ['t' => 'Dokončíte v pokladně', 'd' => 'Výdejní místo a kontakt. Zaplatíte 0 Kč.'],
        ],

        'verified' => 'Kód ověřen',
        'title_subscription' => ['Předplatné', 'na :months'],
        'title_voucher' => ['Poukaz', 'na :amount'],
        'wish' => 'Přání od dárce',
        'from' => 'Od',
        'row_for' => 'Pro',
        'row_box' => 'Box',
        'row_count' => 'Počet boxů',
        'row_count_value' => ':count, jeden každý měsíc',
        'row_valid_on' => 'Platí na',
        'row_valid_on_value' => 'celý e-shop :site',
        'row_valid_until' => 'Platí do',

        'step_code' => 'Kód z certifikátu',
        'step_code_sub' => 'Velká a malá písmena nehrají roli',
        'code_label' => 'Dárkový kód',
        'verify' => 'Ověřit kód',
        'ok_subscription' => 'Kód je platný. Vyberte si kávu a kdy začít.',
        'ok_voucher' => 'Kód je platný. Poukaz uplatníte v pokladně.',
        'error_unknown' => 'Tento kód neznáme. Zkontrolujte ho podle certifikátu, nebo napište na :email.',
        'error_redeemed' => 'Tento dárek už byl uplatněn :date. Pokud jste to nebyli vy, napište na :email.',
        'error_expired' => 'Platnost dárku vypršela :date. Napište nám na :email, domluvíme se.',
        'error_other_domain' => 'Tento dárek uplatníte na :url.',
        'error_throttled' => 'Příliš mnoho pokusů. Zkuste to prosím znovu za chvíli.',
        'error_generic' => 'Ověření se nepovedlo. Zkuste to prosím znovu.',
        'error_not_verified' => 'Nejdřív prosím ověřte kód z certifikátu.',
        'error_sold_out' => 'Tuhle kombinaci kávy pro zvolený měsíc už nemáme. Vyberte prosím jiný typ kávy nebo pozdější start.',

        'step_type' => 'Typ kávy',
        'step_type_sub' => 'Platí pro všechny boxy dárku',
        'types' => [
            'espresso' => ['name' => 'Espresso', 'desc' => 'Plné tělo, tmavší pražení'],
            'filter' => ['name' => 'Filtr', 'desc' => 'Lehčí tělo, světlejší pražení'],
            'mix' => ['name' => 'Kombinace', 'desc' => 'Espresso i filtr'],
        ],
        'mix_title' => 'Rozdělení kávy',
        'mix_less' => 'Méně',
        'mix_more' => 'Více',

        'step_start' => 'Kdy začít',
        'step_start_sub' => 'Uzávěrka je vždy :cutoff_day. dne, rozesílka kolem :ship_day. dne v měsíci',
        'starts' => [
            ['name' => 'V nejbližší rozesílce', 'desc' => 'Objednávky do :cutoff o půlnoci'],
            ['name' => 'O měsíc později', 'desc' => 'Hodí se, když ještě dopíjíte zásoby'],
            ['name' => 'Za dva měsíce', 'desc' => 'Dárek počká, nic nepropadne'],
        ],

        'summary' => 'Shrnutí',
        'summary_box' => 'Box',
        'summary_type' => 'Typ kávy',
        'summary_count' => 'Počet boxů',
        'summary_first' => 'První box',
        'summary_last' => 'Poslední box',
        'continue' => 'Pokračovat do pokladny',
        'continue_note' => 'V pokladně vyberete výdejní místo a doplníte kontakt. Kód už bude uplatněný, zaplatíte 0 Kč.',

        'voucher_step' => 'Jak poukaz použít',
        'voucher_step_sub' => 'Kód zadáte v pokladně do pole pro slevový kód, částka se odečte sama',
        'voucher_box' => 'Sestavit kávový box',
        'voucher_box_desc' => 'Předplatné nebo jednorázový box, od :price',
        'voucher_box_link' => 'Konfigurátor',
        'voucher_shop' => 'Vybrat kávu v e-shopu',
        'voucher_shop_desc' => 'Jednotlivé kávy z pražíren, které vozíme',
        'voucher_shop_link' => 'E-shop',
        'voucher_rules' => 'Poukaz se uplatní najednou na nákup v hodnotě alespoň :amount. Nevyčerpaný zůstatek se nevrací. Platí do :date.',

        'faq' => [
            ['q' => 'Ztratil jsem kód', 'a' => 'Napište na :email jméno dárce nebo e-mail, ze kterého dárek koupil. Kód dohledáme.'],
            ['q' => 'Můžu později změnit kávu nebo výdejní místo?', 'a' => 'Výdejní místo změníte v účtu kdykoli před uzávěrkou boxu. Typ kávy si vyberete jednou při uplatnění kódu a platí pro všechny boxy dárku.'],
            ['q' => 'Předplatné už mám. Co s dárkem?', 'a' => 'Dárek poběží jako samostatné předplatné vedle vašeho. Vaše placené předplatné se nijak nezmění a boxy přijdou zvlášť. Pokud chcete, aby dárek navázal později, zvolte start o měsíc nebo dva později.'],
            ['q' => 'Co se stane po posledním boxu?', 'a' => 'Nic se neobnoví ani nestrhne. Napíšeme vám, jestli chcete pokračovat.'],
        ],
    ],

    // Pokladna předplatného v režimu dárku
    'checkout' => [
        'title' => 'Aktivace dárku',
        'summary_title' => 'Váš dárek',
        'cta' => 'Aktivovat dárek',
        'country' => 'Země výdejního místa',
        'free' => 'Zaplatíte 0 Kč',
        'free_note' => 'Dárek je zaplacený. Nic se neúčtuje ani neobnovuje.',
        'login_required' => 'Účet s e-mailem :email už existuje. Přihlaste se, dárek na vás počká.',
        'error_invalid' => 'Dárek se nepodařilo uplatnit. Ověřte prosím kód znovu.',
        'confirmation_title' => 'Dárek je aktivovaný',
        'confirmation_text' => 'První box odchází :first, poslední :last. Potvrzení jsme poslali na :email.',
        'confirmation_shipment' => 'První box odešleme :date na vybrané výdejní místo, další vždy o měsíc později.',
        'confirmation_manage' => 'Výdejní místo změníte v účtu před každou uzávěrkou. Dárek skončí sám posledním boxem, nic se neobnoví.',
    ],

    // Košík a pokladna e-shopu
    'cart' => [
        'section' => 'Dárky',
        'delivery' => 'Certifikát posíláme e-mailem v PDF',
        'remove' => 'Odebrat',
        'for' => 'Pro: :name',
        'send_to' => 'Odešleme na :email :date',
        'coupon_not_for_gifts' => 'Slevové kódy ani poukazy nejde použít na dárky.',
        'subscription_code_redeem_only' => 'Tento kód je dárkové předplatné. Uplatníte ho na :url.',
        'voucher_other_currency' => 'Tento poukaz platí jen na :site.',
        'zero_total' => 'Objednávku za 0 Kč nejde zaplatit kartou. Přidejte prosím do košíku další položku.',
        'added' => 'Dárek je v košíku.',
        'removed' => 'Dárek jsme z košíku odebrali.',
        'too_many' => 'Do jedné objednávky můžete vložit nejvýš :max dárků.',
        'currency_changed' => 'Dárek z jiné měny jsme z košíku odebrali.',
        'confirmation' => 'Certifikát posíláme na :email. Stáhnout ho můžete i tady:',
        'download' => 'Stáhnout certifikát (PDF)',
        'preparing' => 'Certifikát se připravuje, za pár minut ho najdete v e-mailu.',
    ],

    'mail' => [
        'buyer_subject' => 'Váš dárek od KAVI je připravený',
        'buyer_title' => 'Dárek je připravený',
        'buyer_intro' => 'Děkujeme za objednávku :order. Certifikát pro :recipient najdete v příloze, v PDF k tisku.',
        'buyer_scheduled' => 'Obdarovanému ho navíc pošleme e-mailem :date na adresu :email.',
        'buyer_code' => 'Kód dárku',
        'buyer_valid' => 'Platí do :date. Uplatní se na :url.',
        'recipient_subject' => ':giver vám posílá dárek od KAVI',
        'recipient_title' => 'Máte dárek od KAVI',
        'recipient_subtitle' => 'Dárkový certifikát na výběrovou kávu',
        'recipient_intro_subscription' => ':giver vám posílá předplatné výběrové kávy na :months. Každý měsíc vám přijde box čerstvě pražené kávy z evropských pražíren, vždy v zrnech.',
        'recipient_intro_voucher' => ':giver vám posílá poukaz na výběrovou kávu v hodnotě :amount. Použijete ho na kávu z e-shopu KAVI nebo na první platbu kávového boxu.',
        'recipient_section_gift' => 'Váš dárek',
        'recipient_section_steps' => 'Jak dárek uplatnit',
        'recipient_steps_subscription' => [
            ['t' => 'Otevřete stránku dárku', 'd' => 'Tlačítkem níže nebo přes QR kód na certifikátu. Kód se vyplní sám.'],
            ['t' => 'Vyberte si kávu a kdy začít', 'd' => 'Espresso, filtr nebo kombinaci obojího. Začít můžete hned, nebo o měsíc či dva později.'],
            ['t' => 'Zvolte výdejní místo', 'd' => 'Boxy vám budeme posílat na výdejní místo Zásilkovny. Nic neplatíte.'],
        ],
        'recipient_steps_voucher' => [
            ['t' => 'Vyberte si kávu', 'd' => 'V e-shopu, nebo si v konfigurátoru sestavte kávový box.'],
            ['t' => 'Zadejte kód v pokladně', 'd' => 'Do pole pro slevový kód. Když dárek otevřete tlačítkem níže, pokladna si kód zapamatuje sama.'],
            ['t' => 'Kávu vám pošleme', 'd' => 'Z e-shopu obvykle do 3 dnů, box v nejbližší měsíční rozesílce.'],
        ],
        'recipient_cta' => 'Uplatnit dárek',
        'recipient_pdf_note' => 'Certifikát v PDF k vytištění najdete v příloze.',
        'recipient_valid_title' => 'Platnost',
        'recipient_valid_subscription' => 'Dárek uplatněte do :date. Je celý zaplacený, nic se neúčtuje ani neobnovuje.',
        'recipient_about_title' => 'Co je KAVI',
        'recipient_about' => 'KAVI je předplatné výběrové kávy. Každý měsíc vybíráme kávy od tří evropských pražíren a posíláme je čerstvě pražené, vždy v zrnech.',
        'recipient_about_voucher' => 'KAVI je předplatné výběrové kávy a e-shop. V předplatném vám každý měsíc pošleme kávy od tří evropských pražíren, čerstvě pražené a vždy v zrnech. V e-shopu si kávu i příslušenství koupíte jednorázově, bez předplatného.',
        'recipient_lineup' => 'Tento měsíc v boxu: :names.',
        'recipient_features' => ['Čerstvě pražená káva', 'Tři evropské pražírny', 'Vždy v zrnech'],
        'activated_subject' => 'Dárek je aktivovaný, první box odchází :date',
        'activated_title' => 'Dárek je aktivovaný',
        'activated_intro' => 'Dárkové předplatné :box je aktivní. První box odchází :first, poslední :last.',
        'activated_note' => 'Výdejní místo můžete změnit v účtu před uzávěrkou každého boxu. Zvolený typ kávy platí pro všechny boxy dárku.',
        'activated_cta' => 'Přejít do účtu',
        'ending_subject' => 'Poslední box z dárku odchází :date',
        'ending_title' => 'Blíží se poslední box z dárku',
        'ending_intro' => 'Poslední box z dárkového předplatného odchází :date. Pak se nic neobnoví ani nestrhne.',
        'ending_offer' => 'Chutnalo vám? Předplatné si můžete nastavit tak, aby navázalo hned další měsíc.',
        'ending_cta' => 'Pokračovat s předplatným',
    ],

    'subscription' => [
        'label' => 'Dárkové předplatné',
        'progress' => 'Box :current z :total',
        'action_not_allowed' => 'Dárkové předplatné nejde pozastavit ani zrušit. Skončí samo posledním boxem.',
    ],

];
