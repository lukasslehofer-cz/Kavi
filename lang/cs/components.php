<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sdílené komponenty (resources/views/components)
    |--------------------------------------------------------------------------
    */

    'unit_price' => [
        'per_gram' => ':price za gram',
        'per_100g' => ':price za 100 g',
    ],

    'countdown' => [
        'cutoff' => 'Uzávěrka :date o půlnoci',
        'units' => [
            'days' => 'dní',
            'hours' => 'hod',
            'minutes' => 'min',
            'seconds' => 'sek',
        ],
        // Pro aria-label, tvary pro 1 / 2-4 / 5 a víc
        'aria' => 'Do uzávěrky zbývá :days, :hours a :minutes',
        'plural' => [
            'days' => ['den', 'dny', 'dní'],
            'hours' => ['hodina', 'hodiny', 'hodin'],
            'minutes' => ['minuta', 'minuty', 'minut'],
        ],
        'passed' => 'Uzávěrka právě proběhla',
    ],

    'tiers' => [
        'bags' => ':grams g · :count balíčky',
        'kinds' => [
            2 => '2 druhy výběrové kávy',
            3 => '3 druhy výběrové kávy',
            4 => '3 druhy výběrové kávy',
        ],
        'shipping_unit' => 'Doprava zdarma · :unit',
        'cancel' => 'Zrušení nebo přestávka kdykoliv',
        'select' => 'Vybrat :box Box',
        'popular' => 'Nejoblíbenější',
        'per_box' => '/box',
        'lead' => 'Flexibilní předplatné přizpůsobené vašim potřebám. Zrušte kdykoliv bez poplatku.',
        'lead_shipping' => 'Doprava zdarma ke každému boxu.',
        'note' => 'Cena za gram je včetně dopravy. Jednorázový box bez předplatného stojí o :surcharge víc.',
    ],

    'rating' => [
        'count' => '{1} :count recenze na Googlu|[2,4] :count recenze na Googlu|[0,*] :count recenzí na Googlu',
        'aria' => 'Hodnocení :rating z 5 podle :count recenzí na Googlu',
        'out_of' => ':rating z 5',
    ],

    'reviews' => [
        'title' => 'Co říkají naši zákazníci',
        'title_empty' => 'Podělte se o svou zkušenost',
        'source' => 'Recenze z Googlu',
        'anonymous' => 'Anonymní recenze',
        'ask_label' => 'Vaše hodnocení',
        'ask_text' => 'Nakupovali jste u nás? Napište, jak vám káva chutnala. Pomůžete tím ostatním s výběrem a nám ukážete, co zlepšit.',
        'ask_cta' => 'Hodnotit na Googlu',
        'disclosure' => 'Recenze pocházejí z našeho profilu na Googlu. Zobrazujeme je neupravené a nefiltrované, včetně negativních. Neověřujeme, kdo je napsal, to dělá Google.',
        'bar' => 'Přidejte se k řadě spokojených milovníků kávy',
        'bar_empty' => 'Nakupovali jste u nás? Řekněte to ostatním',
        'bar_link' => 'Google Reviews',
    ],

    'coffee_of_month' => [
        'title' => 'Káva měsíce',
        'eyebrow' => 'Každý měsíc tři evropské pražírny. Vracejí se zhruba jednou za rok, takže skoro každý box ochutnáte poprvé.',
        'explore' => 'Prozkoumat kávy na :month',
        'decaf' => 'bez kofeinu',
    ],

    'promo_card' => [
        'alt' => 'Kávy z :month boxu',
        'description' => 'Prémiové výběrové kávy z evropských mikropražíren, čerstvě pražené a doručené až k vašim dveřím.',
        'more' => 'Více info',
    ],

    'faq' => [
        'title' => 'Časté dotazy',
        'contact' => 'Nenašli jste odpověď? Napište na :email.',
    ],

];
