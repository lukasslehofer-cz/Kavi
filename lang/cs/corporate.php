<?php

/*
|--------------------------------------------------------------------------
| Stránka Pro firmy (firemní dárky), jen kavi.cz
|--------------------------------------------------------------------------
|
| :min a :max se doplňují z config/corporate.php (minimální počet kusů,
| nejvyšší množstevní sleva). Šipky a další znaky jsou v šabloně.
|
*/

return [

    // Odkazy na stránku z jiných míst webu (patička, homepage, Dárky)
    'links' => [
        'footer' => 'Firemní dárky',
        'home_name' => 'Pro firmy',
        'home_desc' => 'Pro klienty i tým, s vaším logem',
        'home_from' => "od :min\u{00A0}ks",
        'gifts_banner_title' => 'Hledáte dárky pro klienty nebo tým?',
        'gifts_banner_sub' => 'Firemní dárky od :min kusů',
    ],

    'title' => 'Firemní dárky s logem: výběrová káva od :min kusů | KAVI.cz',
    'meta_description' => 'Výběrová káva z evropských pražíren v dárkovém boxu s vaším logem. Pro klienty i zaměstnance, od :min kusů, na fakturu, s množstevní slevou až :max %.',

    'hero' => [
        'label' => 'Úvod',
        'xmas' => 'Vánoce :year',
        'xmas_inquiry' => 'Objednejte do',
        'xmas_deliver' => 'doručíme do',
        'title' => 'Dárek, na který si klienti vzpomenou',
        'title_accent' => 'každé ráno.',
        'lead' => 'Výběrová káva z evropských pražíren v dárkovém balení s vaším logem. Od :min kusů, na fakturu, doručená do kanceláře nebo přímo klientům.',
        'cta' => 'Poptat firemní dárky',
        'variants' => 'Prohlédnout varianty',
        'trust' => [
            'Káva z pražíren jako Coffee Pirates z Vídně',
            'Část z každého boxu jde na Water.org',
        ],
        'image_alt' => 'Dárkový box KAVI s balíčky výběrové kávy',
        'image_label' => 'Firemní dárky',
    ],

    'why' => [
        'label' => 'Proč káva',
        'title' => 'Dárek, který neskončí v šuplíku',
        'items' => [
            ['t' => 'Opravdu se vypije', 'd' => 'Kávu pije skoro každý. Na rozdíl od dalšího hrnku nebo powerbanky s logem se nepřehlédne a nezapadne.', 'alt' => 'Příprava filtrované kávy'],
            ['t' => 'Káva, kterou v obchodě nekoupí', 'd' => 'Čerstvě pražená výběrová káva od evropských pražíren. Ke každé kávě kartička s příběhem pražírny a popisem chuti.', 'alt' => 'Ochutnávka výběrové kávy'],
            ['t' => 'Připomenete se znovu a znovu', 'd' => 'S dárkovým předplatným dostane klient nový box každý měsíc. A každý měsíc si vzpomene, od koho je.', 'alt' => 'Hrnek ranní kávy na okenním parapetu'],
        ],
    ],

    'variants' => [
        'label' => 'Varianty',
        'title' => 'Vyberte, jak velký dojem chcete udělat',
        'ideal_for' => 'Ideální pro',
        'box' => [
            'name' => 'Dárkový box',
            'for' => 'Klienty, obchodní partnery, zaměstnance',
            'desc' => 'Výběrová káva v boxu s vaším logem na pásce. Uvnitř kartička s vaším vzkazem a příběhem každé kávy.',
            'sizes_label' => 'Velikosti boxu',
            // Jen firemní pojmenování (1/2/3 kávy), bez vazby na BoxPricing ani M/L/XL předplatného
            'sizes' => [
                ['code' => 'S', 'desc' => '1 káva · 250 g'],
                ['code' => 'M', 'desc' => '2 kávy · 500 g', 'badge' => 'Oblíbené'],
                ['code' => 'L', 'desc' => '3 kávy · 750 g'],
            ],
            'note' => 'Box S se vejde do limitu 500 Kč bez DPH pro reklamní předměty.',
        ],
        'subscription' => [
            'name' => 'Dárkové předplatné',
            'for' => 'Nejdůležitější klienty',
            'desc' => 'Každý měsíc nový box se dvěma kávami z evropských pražíren, pokaždé jiných. První přijde s páskou s vaším logem a kartičkou.',
            'lengths_label' => 'Délka předplatného',
            'lengths' => [
                ['t' => '3 měsíce', 'd' => '3 boxy'],
                ['t' => '6 měsíců', 'd' => '6 boxů'],
            ],
            'note' => 'Předplatné skončí posledním boxem, nic se automaticky neobnovuje.',
        ],
        'offer' => 'Nabídku s cenou pošleme do 2 pracovních dnů',
    ],

    'custom' => [
        'label' => 'Personalizace a doručení',
        'title' => 'S vaším logem, tam, kam potřebujete',
        'image_alt' => 'Standardní box KAVI s páskou s logem firmy a kartičkou se vzkazem',
        'caption' => 'Ukázka pro fiktivní advokátní kancelář',
        'blocks' => [
            [
                't' => 'Personalizace',
                'rows' => [
                    ['t' => 'Páska s logem a kartička se vzkazem', 'd' => 'Logo na boxu, váš vzkaz uvnitř.', 'tag' => 'V ceně'],
                    ['t' => 'Náhled ke schválení', 'd' => 'Než cokoli vytiskneme, uvidíte pásku i kartičku s vaším logem.'],
                ],
            ],
            [
                't' => 'Doručení',
                'rows' => [
                    ['t' => 'Vše najednou do vaší kanceláře', 'd' => 'Jedna adresa, jedna zásilka.', 'tag' => 'Množstevní sleva'],
                    ['t' => 'Každý kus přímo klientovi', 'd' => 'Stačí seznam adres, každý dárek doručíme zvlášť.'],
                ],
            ],
        ],
    ],

    'pricing' => [
        'label' => 'Množstevní ceny',
        'title' => 'Čím víc, tím výhodnější',
        'range' => ':from–:to kusů',
        'range_open' => ':from a víc kusů',
        'sub' => 'sleva z ceníkové ceny',
        // Lukas zalomil řádek před "v ceně vždy." (na desktopu)
        'note' => 'Sleva platí, když všechny dárky doručíme na jednu adresu. Páska s logem a kartička jsou',
        'note_end' => 'v ceně vždy.',
        'cta' => 'Chci nabídku',
    ],

    'how' => [
        'label' => 'Jak to funguje',
        'title' => 'Od poptávky k dárku ve čtyřech krocích',
        'steps' => [
            ['t' => 'Poptávka', 'd' => 'Vyplňte krátký formulář: pro koho, kolik kusů a kdy.'],
            ['t' => 'Nabídka', 'd' => 'Do 2 pracovních dnů pošleme nabídku s cenou.'],
            ['t' => 'Logo a náhled', 'd' => 'Pošlete logo a vzkaz, připravíme náhled pásky a kartičky ke schválení.'],
            ['t' => 'Pražení a doručení', 'd' => 'Kávu necháme čerstvě napražit a doručíme ji do kanceláře nebo přímo klientům.'],
        ],
    ],

    'hr' => [
        'label' => 'Pro HR',
        'eyebrow' => 'Pro HR',
        'title' => 'Odměna, která týmu opravdu chutná',
        'lead' => 'Boxy a předplatné se hodí i pro vaše lidi. Stejné množstevní slevy, páska s logem a kartička se vzkazem od vedení.',
        'chips' => [
            'Uvítací balíček pro nové kolegy',
            'Pracovní výročí',
            'Poděkování za projekt',
            'Vánoční dárek pro tým',
        ],
        'cta' => 'Poptat dárky pro tým',
        'image_alt' => 'Kolegyně si v kuchyňce dolévají kávu z french pressu',
    ],

    'faq_title' => 'Časté dotazy',
    // a_christmas se připojí k odpovědi jen do termínu vánoční poptávky (config/corporate.php)
    'faq' => [
        ['q' => 'Jaký je minimální počet kusů?', 'a' => ':min kusů. Páska s vaším logem a kartička se vzkazem jsou u každého boxu.'],
        ['q' => 'Můžeme si vybrat konkrétní kávu?', 'a' => 'Kávu vybíráme my, vždy to nejlepší, co pražírny právě nabízejí. Domluvit se můžeme na espressu, filtru i kávě bez kofeinu a na chuťovém profilu. Vždy ale půjde o výběrovou kávu, která je ovocnější než běžná káva z obchodu.'],
        ['q' => 'Jak dlouho trvá doručení?', 'a' => 'Termín potvrdíme v nabídce.', 'a_christmas' => 'Na Vánoce platí: když objednáte do :inquiry_by, dárky doručíme do :deliver_by.'],
        ['q' => 'Jak probíhá platba?', 'a' => 'Na fakturu převodem. Vystavíme ji na vaši firmu po schválení náhledu.'],
        ['q' => 'Umíte poslat dárek přímo klientům?', 'a' => 'Ano. Pošlete nám seznam adres a každý dárek doručíme zvlášť. Množstevní sleva platí jen při doručení na jednu adresu.'],
        ['q' => 'Co se stane, když předplatné skončí?', 'a' => 'Nic se automaticky neobnovuje a nic dalšího neplatíte. Klient si může předplatné objednat sám, pokud mu káva zachutná.'],
    ],

    'form' => [
        'label' => 'Poptávka',
        'title' => 'Napište nám, co potřebujete',
        'lead' => 'Ozveme se do 2 pracovních dnů s nabídkou na míru.',
        'arrows' => [
            'Od :min kusů, množstevní sleva až :max %',
            'Faktura na firmu, platba převodem',
            'Doručení do kanceláře nebo přímo klientům',
        ],

        'name' => 'Jméno a příjmení',
        'name_placeholder' => 'Jana Nováková',
        'company' => 'Firma',
        'company_placeholder' => 'Novák & partneři',
        'email' => 'E-mail',
        'email_placeholder' => 'jana@firma.cz',
        'phone' => 'Telefon',
        'phone_placeholder' => '+420 777 123 456',
        'audience' => 'Pro koho',
        'audiences' => [
            'klienti' => 'Klienti',
            'zamestnanci' => 'Zaměstnanci',
            'oboji' => 'Obojí',
        ],
        'quantity' => 'Přibližný počet kusů',
        'quantity_range' => ':from–:to',
        'quantity_open' => ':from a víc',
        'delivery_date' => 'Kdy má dárek dorazit',
        'note' => 'Poznámka',
        'note_placeholder' => 'Například: dárky pro 30 klientů, polovinu rozeslat na adresy.',
        'required' => 'povinné',
        'honeypot' => 'Web (nevyplňujte)',

        'submit' => 'Odeslat poptávku',
        'sending' => 'Odesílám',
        'email_alt' => 'Radši e-mailem? Napište na',

        // Chyba je jedna věta: "Doplňte prosím jméno, firmu, platný e-mail."
        'error_prefix' => 'Doplňte prosím',
        'missing' => [
            'name' => 'jméno',
            'company' => 'firmu',
            'email' => 'platný e-mail',
            'phone' => 'platný telefon',
            'audience' => 'pro koho',
            'quantity' => 'počet kusů',
            'delivery_date' => 'platné datum doručení',
            'note' => 'kratší poznámku',
        ],
        'error_generic' => 'Poptávku se nepodařilo odeslat. Zkuste to prosím znovu, nebo napište na :email.',
        'error_throttled' => 'Odeslali jste několik poptávek za sebou. Zkuste to prosím za pár minut, nebo napište na :email.',
        'error_recaptcha' => 'Ověření proti robotům se nepovedlo. Zkuste to prosím znovu, nebo napište na :email.',

        'sent_badge' => 'Poptávka odeslána',
        'sent_title' => 'Děkujeme, poptávka dorazila.',
        'sent_lead' => 'Do 2 pracovních dnů se ozveme s nabídkou.',
    ],

];
