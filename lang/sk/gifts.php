<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Darčeky: darčekové predplatné a poukážka na e-shop
    |--------------------------------------------------------------------------
    |
    | Údaje (ceny, dátumy uzávierky a zásielok, adresy) dopĺňa kód z databázy.
    |
    */

    'months' => '{1} :count mesiac|[2,4] :count mesiace|[5,*] :count mesiacov',
    'boxes' => '{1} :count box|[2,4] :count boxy|[5,*] :count boxov',
    'months_word' => [3 => 'Tri mesiace', 6 => 'Pol roka'],
    'box_label' => ':box Box · :count balíčky mesačne',

    'item_name' => [
        'subscription' => 'Darčekové predplatné :box Box, :months',
        'voucher' => 'Darčeková poukážka :amount',
    ],

    'certificate' => [
        'eyebrow_subscription' => 'Darčekový certifikát',
        'eyebrow_voucher' => 'Darčeková poukážka',
        'chips_subscription' => ['Každý mesiac', 'Tri európske pražiarne'],
        'chips_voucher' => ['Výberová káva', 'Z európskych pražiarní'],
        'headline_middle' => 'výberovej',
        'headline_last' => 'kávy',
        'voucher_sub' => 'Na výberovú kávu',
        'to' => 'Pre',
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
            'note' => 'Nech ti každé ráno chutí. Veselé Vianoce.',
        ],
        'back' => [
            'eyebrow' => 'Darček od KAVI',
            'title' => 'Ako darček',
            'title_accent' => 'uplatniť',
            'steps' => [
                ['t' => 'Otvorte :url', 'd' => 'Alebo naskenujte QR kód vpravo dole.'],
                ['t' => 'Zadajte kód', 'd' => 'Nájdete ho na prednej strane v čiernom páse.'],
                ['t' => 'Vyberte si kávu', 'd' => 'Pri predplatnom typ kávy a výdajné miesto. Poukážka sa odpočíta v pokladni.'],
            ],
            'info_subscription' => 'Každý mesiac tri európske pražiarne, prvý box odchádza s najbližšou zásielkou okolo 20. dňa. Doprava v cene. Z každého boxu prispievame na Water.org.',
            'info_voucher' => 'Výberová káva z európskych pražiarní: kávové boxy aj jednotlivé kávy v e-shope. Z každého boxu prispievame na Water.org.',
            'contact' => 'Otázky: :email · :site',
        ],
    ],

    // Stránka nákupu darčekov
    'page' => [
        'title' => 'Darčekové predplatné a poukážky na kávu | KAVIbox.sk',
        'meta_description' => 'Darujte výberovú kávu: darčekové predplatné na 3 alebo 6 mesiacov, alebo poukážku na celý e-shop. Certifikát príde hneď e-mailom v PDF na tlač.',
        'hero_kicker' => 'Darčekové predplatné kávy',
        'hero_title' => ['Darujte', 'kávu'],
        'hero_text' => 'Darčekové predplatné na 3 alebo 6 mesiacov, alebo poukážka na celý e-shop. Certifikát príde hneď e-mailom v PDF na tlač.',
        'hero_intro' => 'S darčekovým predplatným KAVI príde obdarovanému každý mesiac box s výberovou kávou z európskych pražiarní, akú v bežných obchodoch nenájde. Typ kávy a výdajné miesto si zvolí sám, vy len vyberiete, na ako dlho a koľko kávy. Hodí sa na Vianoce, k narodeninám aj ako poďakovanie.',

        'preview_label' => 'Náhľad certifikátu',
        'preview_format' => 'PDF · A5 na šírku',
        'total' => 'Celková cena',
        'total_note_subscription' => ':box Box :price × :months · doprava v cene',
        'total_note_voucher' => 'certifikát v PDF hneď po zaplatení',
        'cta' => 'Kúpiť darček',
        'bullets' => ['Certifikát hneď', 'Bez obnovenia'],

        'step_type' => 'Čo chcete darovať',
        'step_type_sub' => 'Predplatné, alebo poukážku na čokoľvek z e-shopu',
        'type_subscription' => 'Darčekové predplatné',
        'type_subscription_desc' => 'Box s kávou každý mesiac, 3 alebo 6 mesiacov',
        'type_voucher' => 'Darčeková poukážka',
        'type_voucher_desc' => 'Na čokoľvek z e-shopu, obdarovaný si vyberie sám',
        'from' => 'od :price',

        'step_length' => 'Na ako dlho',
        'step_length_sub' => 'Každý mesiac jeden box, platíte raz vopred',
        'length_desc' => ':boxes · platíte raz',

        'step_size' => 'Množstvo kávy',
        'step_size_sub' => 'Koľko kávy má každý mesiac prísť',
        'size_desc' => [
            2 => 'Na dve kávy denne',
            3 => 'Na tri kávy denne',
            4 => 'Pre domácnosť alebo kanceláriu',
        ],

        'step_amount' => 'Hodnota poukážky',
        'step_amount_sub' => 'Platí na celý e-shop, na boxy aj jednotlivé kávy',
        'amount_desc_all' => 'Na kávu z e-shopu alebo prvú platbu ktoréhokoľvek boxu',
        'amount_desc_some' => 'Na :boxes Box, alebo na nákup v e-shope',
        'amount_desc_none' => 'Na väčší nákup v e-shope, napríklad viac káv naraz',
        'boxes_or' => ' alebo ',
        'amount_note' => 'Poukážka sa uplatní naraz, na nákup v hodnote aspoň vo výške poukážky. Platí 12 mesiacov od kúpy.',

        'step_whom' => 'Pre koho',
        'step_whom_sub' => 'Mená a venovanie sa prenesú do certifikátu',
        'recipient_name' => 'Meno obdarovaného',
        'giver_name' => 'Od koho',
        'note' => 'Venovanie, nepovinné',
        'note_placeholder' => 'Nech ti každé ráno chutí',
        'delivery_buyer' => 'Pošlite ho mne',
        'delivery_buyer_desc' => 'Certifikát v PDF odovzdám alebo vytlačím',
        'delivery_recipient' => 'Pošlite ho obdarovanému',
        'delivery_recipient_desc' => 'E-mailom v deň, ktorý si zvolíte',
        'recipient_email' => 'E-mail obdarovaného',
        'recipient_email_placeholder' => 'petra@email.sk',
        'send_at' => 'Kedy odoslať',

        'gets_title' => 'Čo obdarovaný dostane',
        'gets_image_alt' => 'Otvorený KAVI box s tromi balíčkami kávy',
        'gets_lead' => 'Každý mesiac box s výberovou kávou z európskych pražiarní, akú v bežných obchodoch nenájde.',
        'gets_arrows' => [
            '2 až 4 balíčky po 250 g podľa veľkosti boxu',
            'Pohľadnicu s profilom kávy a pražiarne ku každej káve',
            'Vlastný výber espressa, filtra alebo kombinácie a výdajného miesta',
            'Žiadny záväzok, darček sa skončí posledným boxom',
        ],
        'gets_coffee' => 'Káva mesiaca',
        'gets_self' => 'Box pre seba',

        'how_title' => 'Ako to funguje',
        'how_lead' => 'Od zaplatenia k prvému boxu',
        'how_steps' => [
            ['t' => 'Vyberiete a zaplatíte', 'd' => 'Predplatné alebo poukážku, venovanie a komu poslať certifikát.'],
            ['t' => 'Certifikát hneď', 'd' => 'PDF na vytlačenie, alebo ho pošleme priamo obdarovanému v deň, ktorý si zvolíte.'],
            ['t' => 'Obdarovaný aktivuje', 'd' => 'Na :url zadá kód, typ kávy a výdajné miesto.'],
            ['t' => 'Prvý box :day. dňa', 'd' => 'Odíde s najbližšou mesačnou zásielkou. Uzávierka je vždy :cutoff_day. dňa v mesiaci.'],
        ],

        // Červený pás nad FAQ. Na kavi.cz odkaz na stránku Pro firmy, inde mailto
        'corporate' => 'Firemné darčeky od :min kusov, s faktúrou na firmu a spoločným venovaním',
        'corporate_cta' => 'Napíšte na :email',

        'faq_title' => 'Časté otázky o darčekoch',
        'faq' => [
            ['q' => 'Obnoví sa predplatné samo?', 'a' => 'Nie. Darček sa skončí posledným boxom. Obdarovanému potom ponúkneme pokračovanie, nič sa nestrháva automaticky.'],
            ['q' => 'Stihnem to do Vianoc?', 'a' => 'Certifikát áno, príde hneď po zaplatení. Krabica s kávou potom odchádza podľa dátumu aktivácie, s najbližšou mesačnou zásielkou.'],
            ['q' => 'Čo ak obdarovaný nepije espresso?', 'a' => 'Typ kávy si pri aktivácii zvolí sám: espresso, filter alebo kombináciu oboch.'],
            ['q' => 'Na čo platí poukážka?', 'a' => 'Na kávu a príslušenstvo v e-shope aj na prvú platbu kávového boxu. Uplatníte ju naraz v pokladni, nákup musí mať hodnotu aspoň vo výške poukážky a nevyčerpaný zostatok sa nevracia. Platí 12 mesiacov od kúpy.'],
            // Len kavi.cz (FaqHelper otázku vynechá, keď stránka Pro firmy nie je)
            ['q' => 'Ponúkate darčeky pre firmy?', 'a' => 'Áno, od :min kusov s logom vašej firmy na páske boxu a kartičkou s venovaním. Množstevná zľava až :max %, faktúra na firmu.', 'link' => ['label' => 'Firemné darčeky', 'route' => 'corporate.index']],
        ],
    ],

    // Stránka uplatnenia darčeka
    'redeem' => [
        'title' => 'Uplatniť darček | KAVIbox.sk',
        'meta_description' => 'Dostali ste kávu od KAVI? Zadajte kód z certifikátu a vyberte si typ kávy.',
        'hero_title' => ['Uplatniť', 'darček'],
        'hero_text' => 'Dostali ste kávu od KAVI? Zadajte kód z certifikátu a vyberte si typ kávy. Výdajné miesto a kontakt doplníte v pokladni, platiť nič nebudete.',

        'intro_eyebrow' => 'Darček od KAVI',
        'intro_title' => ['Kód nájdete', 'na certifikáte'],
        'intro_text' => 'Na prednej strane v čiernom páse. Má tvar XXXX-XXXX, na veľkých a malých písmenách nezáleží.',
        'intro_steps' => [
            ['t' => 'Zadáte kód', 'd' => 'Overíme ho a ukážeme, čo vám kto daroval.'],
            ['t' => 'Vyberiete kávu', 'd' => 'Espresso, filter alebo kombináciu a kedy začať.'],
            ['t' => 'Dokončíte v pokladni', 'd' => 'Výdajné miesto a kontakt. Zaplatíte 0 €.'],
        ],

        'verified' => 'Kód overený',
        'title_subscription' => ['Predplatné', 'na :months'],
        'title_voucher' => ['Poukážka', 'na :amount'],
        'wish' => 'Prianie od darcu',
        'from' => 'Od',
        'row_for' => 'Pre',
        'row_box' => 'Box',
        'row_count' => 'Počet boxov',
        'row_count_value' => ':count, jeden každý mesiac',
        'row_valid_on' => 'Platí na',
        'row_valid_on_value' => 'celý e-shop :site',
        'row_valid_until' => 'Platí do',

        'step_code' => 'Kód z certifikátu',
        'step_code_sub' => 'Na veľkých a malých písmenách nezáleží',
        'code_label' => 'Darčekový kód',
        'verify' => 'Overiť kód',
        'ok_subscription' => 'Kód je platný. Vyberte si kávu a kedy začať.',
        'ok_voucher' => 'Kód je platný. Poukážku uplatníte v pokladni.',
        'error_unknown' => 'Tento kód nepoznáme. Skontrolujte ho podľa certifikátu, alebo napíšte na :email.',
        'error_redeemed' => 'Tento darček už bol uplatnený :date. Ak ste to neboli vy, napíšte na :email.',
        'error_expired' => 'Platnosť darčeka vypršala :date. Napíšte nám na :email, dohodneme sa.',
        'error_other_domain' => 'Tento darček uplatníte na :url.',
        'error_throttled' => 'Príliš veľa pokusov. Skúste to prosím znova o chvíľu.',
        'error_generic' => 'Overenie sa nepodarilo. Skúste to prosím znova.',
        'error_not_verified' => 'Najprv prosím overte kód z certifikátu.',
        'error_sold_out' => 'Túto kombináciu kávy pre zvolený mesiac už nemáme. Vyberte si prosím iný typ kávy alebo neskorší začiatok.',

        'step_type' => 'Typ kávy',
        'step_type_sub' => 'Platí pre všetky boxy darčeka',
        'types' => [
            'espresso' => ['name' => 'Espresso', 'desc' => 'Plné telo, tmavšie praženie'],
            'filter' => ['name' => 'Filter', 'desc' => 'Ľahšie telo, svetlejšie praženie'],
            'mix' => ['name' => 'Kombinácia', 'desc' => 'Espresso aj filter'],
        ],
        'mix_title' => 'Rozdelenie kávy',
        'mix_less' => 'Menej',
        'mix_more' => 'Viac',

        'step_start' => 'Kedy začať',
        'step_start_sub' => 'Uzávierka je vždy :cutoff_day. dňa, zásielky odchádzajú okolo :ship_day. dňa v mesiaci',
        'starts' => [
            ['name' => 'S najbližšou zásielkou', 'desc' => 'Objednávky do :cutoff o polnoci'],
            ['name' => 'O mesiac neskôr', 'desc' => 'Hodí sa, keď ešte dopíjate zásoby'],
            ['name' => 'O dva mesiace', 'desc' => 'Darček počká, nič neprepadne'],
        ],

        'summary' => 'Zhrnutie',
        'summary_box' => 'Box',
        'summary_type' => 'Typ kávy',
        'summary_count' => 'Počet boxov',
        'summary_first' => 'Prvý box',
        'summary_last' => 'Posledný box',
        'continue' => 'Pokračovať do pokladne',
        'continue_note' => 'V pokladni vyberiete výdajné miesto a doplníte kontakt. Kód už bude uplatnený, zaplatíte 0 €.',

        'voucher_step' => 'Ako poukážku použiť',
        'voucher_step_sub' => 'Kód zadáte v pokladni do poľa pre zľavový kód, suma sa odpočíta sama',
        'voucher_box' => 'Zostaviť kávový box',
        'voucher_box_desc' => 'Predplatné alebo jednorazový box, od :price',
        'voucher_box_link' => 'Konfigurátor',
        'voucher_shop' => 'Vybrať kávu v e-shope',
        'voucher_shop_desc' => 'Jednotlivé kávy z pražiarní, ktoré vozíme',
        'voucher_shop_link' => 'E-shop',
        'voucher_rules' => 'Poukážka sa uplatní naraz na nákup v hodnote aspoň :amount. Nevyčerpaný zostatok sa nevracia. Platí do :date.',

        'faq' => [
            ['q' => 'Stratil som kód', 'a' => 'Napíšte na :email meno darcu alebo e-mail, z ktorého darček kúpil. Kód dohľadáme.'],
            ['q' => 'Môžem neskôr zmeniť kávu alebo výdajné miesto?', 'a' => 'Výdajné miesto zmeníte v účte kedykoľvek pred uzávierkou boxu. Typ kávy si vyberiete raz pri uplatnení kódu a platí pre všetky boxy darčeka.'],
            ['q' => 'Predplatné už mám. Čo s darčekom?', 'a' => 'Darček pobeží ako samostatné predplatné popri vašom. Vaše platené predplatné sa nijako nezmení a boxy prídu osobitne. Ak chcete, aby darček nadviazal neskôr, zvoľte začiatok o mesiac alebo dva neskôr.'],
            ['q' => 'Čo sa stane po poslednom boxe?', 'a' => 'Nič sa neobnoví ani nestrhne. Napíšeme vám, či chcete pokračovať.'],
        ],
    ],

    // Pokladňa predplatného v režime darčeka
    'checkout' => [
        'title' => 'Aktivácia darčeka',
        'summary_title' => 'Váš darček',
        'cta' => 'Aktivovať darček',
        'country' => 'Krajina výdajného miesta',
        'free' => 'Zaplatíte 0 €',
        'free_note' => 'Darček je zaplatený. Nič sa neúčtuje ani neobnovuje.',
        'login_required' => 'Účet s e-mailom :email už existuje. Prihláste sa, darček na vás počká.',
        'error_invalid' => 'Darček sa nepodarilo uplatniť. Overte prosím kód znova.',
        'confirmation_title' => 'Darček je aktivovaný',
        'confirmation_text' => 'Prvý box odchádza :first, posledný :last. Potvrdenie sme poslali na :email.',
        'confirmation_shipment' => 'Prvý box odošleme :date na vybrané výdajné miesto, ďalšie vždy o mesiac neskôr.',
        'confirmation_manage' => 'Výdajné miesto zmeníte v účte pred každou uzávierkou. Darček sa skončí sám posledným boxom, nič sa neobnoví.',
    ],

    // Košík a pokladňa e-shopu
    'cart' => [
        'section' => 'Darčeky',
        'delivery' => 'Certifikát posielame e-mailom v PDF',
        'remove' => 'Odobrať',
        'for' => 'Pre: :name',
        'send_to' => 'Odošleme na :email :date',
        'coupon_not_for_gifts' => 'Zľavové kódy ani poukážky nie je možné použiť na darčeky.',
        'subscription_code_redeem_only' => 'Tento kód je darčekové predplatné. Uplatníte ho na :url.',
        'voucher_other_currency' => 'Táto poukážka platí len na :site.',
        'zero_total' => 'Objednávku za 0 € nie je možné zaplatiť kartou. Pridajte prosím do košíka ďalšiu položku.',
        'added' => 'Darček je v košíku.',
        'removed' => 'Darček sme z košíka odobrali.',
        'too_many' => 'Do jednej objednávky môžete vložiť najviac :max darčekov.',
        'currency_changed' => 'Darček v inej mene sme z košíka odobrali.',
        'confirmation' => 'Certifikát posielame na :email. Stiahnuť si ho môžete aj tu:',
        'download' => 'Stiahnuť certifikát (PDF)',
        'preparing' => 'Certifikát sa pripravuje, o pár minút ho nájdete v e-maile.',
    ],

    'mail' => [
        'buyer_subject' => 'Váš darček od KAVI je pripravený',
        'buyer_title' => 'Darček je pripravený',
        'buyer_intro' => 'Ďakujeme za objednávku :order. Certifikát pre :recipient nájdete v prílohe, v PDF na tlač.',
        'buyer_scheduled' => 'Obdarovanému ho navyše pošleme e-mailom :date na adresu :email.',
        'buyer_code' => 'Kód darčeka',
        'buyer_valid' => 'Platí do :date. Uplatní sa na :url.',
        'recipient_subject' => ':giver vám posiela darček od KAVI',
        'recipient_title' => 'Máte darček od KAVI',
        'recipient_subtitle' => 'Darčekový certifikát na výberovú kávu',
        'recipient_intro_subscription' => ':giver vám posiela predplatné výberovej kávy na :months. Každý mesiac vám príde box čerstvo praženej kávy z európskych pražiarní, vždy v zrnách.',
        'recipient_intro_voucher' => ':giver vám posiela poukážku na výberovú kávu v hodnote :amount. Použijete ju na kávu z e-shopu KAVI alebo na prvú platbu kávového boxu.',
        'recipient_section_gift' => 'Váš darček',
        'recipient_section_steps' => 'Ako darček uplatniť',
        'recipient_steps_subscription' => [
            ['t' => 'Otvorte stránku darčeka', 'd' => 'Tlačidlom nižšie alebo cez QR kód na certifikáte. Kód sa vyplní sám.'],
            ['t' => 'Vyberte si kávu a kedy začať', 'd' => 'Espresso, filter alebo kombináciu oboch. Začať môžete hneď, alebo o mesiac či dva neskôr.'],
            ['t' => 'Zvoľte výdajné miesto', 'd' => 'Boxy vám budeme posielať na výdajné miesto Packeta. Nič neplatíte.'],
        ],
        'recipient_steps_voucher' => [
            ['t' => 'Vyberte si kávu', 'd' => 'V e-shope, alebo si v konfigurátore zostavte kávový box.'],
            ['t' => 'Zadajte kód v pokladni', 'd' => 'Do poľa pre zľavový kód. Keď darček otvoríte tlačidlom nižšie, pokladňa si kód zapamätá sama.'],
            ['t' => 'Kávu vám pošleme', 'd' => 'Z e-shopu zvyčajne do 3 dní, box s najbližšou mesačnou zásielkou.'],
        ],
        'recipient_cta' => 'Uplatniť darček',
        'recipient_pdf_note' => 'Certifikát v PDF na vytlačenie nájdete v prílohe.',
        'recipient_valid_title' => 'Platnosť',
        'recipient_valid_subscription' => 'Darček uplatnite do :date. Je celý zaplatený, nič sa neúčtuje ani neobnovuje.',
        'recipient_about_title' => 'Čo je KAVI',
        'recipient_about' => 'KAVI je predplatné výberovej kávy. Každý mesiac vyberáme kávy od troch európskych pražiarní a posielame ich čerstvo pražené, vždy v zrnách.',
        'recipient_about_voucher' => 'KAVI je predplatné výberovej kávy a e-shop. V predplatnom vám každý mesiac pošleme kávy od troch európskych pražiarní, čerstvo pražené a vždy v zrnách. V e-shope si kávu aj príslušenstvo kúpite jednorazovo, bez predplatného.',
        'recipient_lineup' => 'Tento mesiac v boxe: :names.',
        'recipient_features' => ['Čerstvo pražená káva', 'Tri európske pražiarne', 'Vždy v zrnách'],
        'activated_subject' => 'Darček je aktivovaný, prvý box odchádza :date',
        'activated_title' => 'Darček je aktivovaný',
        'activated_intro' => 'Darčekové predplatné :box je aktívne. Prvý box odchádza :first, posledný :last.',
        'activated_note' => 'Výdajné miesto môžete zmeniť v účte pred uzávierkou každého boxu. Zvolený typ kávy platí pre všetky boxy darčeka.',
        'activated_cta' => 'Prejsť do účtu',
        'ending_subject' => 'Posledný box z darčeka odchádza :date',
        'ending_title' => 'Blíži sa posledný box z darčeka',
        'ending_intro' => 'Posledný box z darčekového predplatného odchádza :date. Potom sa nič neobnoví ani nestrhne.',
        'ending_offer' => 'Chutilo vám? Predplatné si môžete nastaviť tak, aby nadviazalo hneď v ďalšom mesiaci.',
        'ending_cta' => 'Pokračovať s predplatným',
    ],

    'subscription' => [
        'label' => 'Darčekové predplatné',
        'progress' => 'Box :current z :total',
        'action_not_allowed' => 'Darčekové predplatné nie je možné pozastaviť ani zrušiť. Skončí sa samo posledným boxom.',
    ],

];
