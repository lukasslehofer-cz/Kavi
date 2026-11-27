<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Zdieľané komponenty (resources/views/components)
    |--------------------------------------------------------------------------
    */

    'unit_price' => [
        'per_gram' => ':price za gram',
        'per_100g' => ':price za 100 g',
    ],

    'countdown' => [
        'cutoff' => 'Uzávierka :date o polnoci',
        'units' => [
            'days' => 'dní',
            'hours' => 'hod',
            'minutes' => 'min',
            'seconds' => 'sek',
        ],
        // Pre aria-label, tvary pre 1 / 2-4 / 5 a viac
        'aria' => 'Do uzávierky zostáva :days, :hours a :minutes',
        'plural' => [
            'days' => ['deň', 'dni', 'dní'],
            'hours' => ['hodina', 'hodiny', 'hodín'],
            'minutes' => ['minúta', 'minúty', 'minút'],
        ],
        'passed' => 'Uzávierka práve prebehla',
    ],

    'tiers' => [
        'title' => 'Vyberte si ideálny kávový box',
        'bags' => ':grams g · :count balíčky',
        'kinds' => [
            2 => '2 druhy výberovej kávy',
            3 => '3 druhy výberovej kávy',
            4 => '3 druhy výberovej kávy',
        ],
        // Doprava predplatného nie je zadarmo do všetkých krajín (rovnako ako na kavibox.com)
        'shipping_unit' => 'Balené s láskou · :unit',
        'cancel' => 'Zrušenie alebo prestávka kedykoľvek',
        'select' => 'Vybrať :box Box',
        'popular' => 'Najobľúbenejší',
        'per_box' => '/box',
        'lead' => 'Flexibilné predplatné prispôsobené vašim potrebám. Zrušte kedykoľvek bez poplatku.',
        'lead_shipping' => '',
        'note' => 'Jednorazový box bez predplatného stojí o :surcharge viac.',
    ],

    'rating' => [
        'count' => '{1} :count recenzia na Google|[2,4] :count recenzie na Google|[0,*] :count recenzií na Google',
        'aria' => 'Hodnotenie :rating z 5 podľa :count recenzií na Google',
        'out_of' => ':rating z 5',
    ],

    'reviews' => [
        'title' => 'Čo hovoria naši zákazníci',
        'title_empty' => 'Podeľte sa o svoju skúsenosť',
        'source' => 'Recenzia z Google',
        'anonymous' => 'Anonymná recenzia',
        'ask_label' => 'Vaše hodnotenie',
        'ask_text' => 'Nakupovali ste u nás? Napíšte, ako vám káva chutila. Pomôžete tým ostatným s výberom a nám ukážete, čo zlepšiť.',
        'ask_cta' => 'Ohodnotiť na Google',
        'disclosure' => 'Recenzie pochádzajú z nášho profilu na Google. Zobrazujeme ich neupravené a nefiltrované, vrátane negatívnych. Neoverujeme, kto ich napísal, to robí Google.',
        'bar' => 'Pridajte sa k mnohým spokojným milovníkom kávy',
        'bar_empty' => 'Nakupovali ste u nás? Povedzte to ostatným',
        'bar_link' => 'Google Reviews',
    ],

    'coffee_of_month' => [
        'title' => 'Káva mesiaca',
        'eyebrow' => 'Každý mesiac tri európske pražiarne. Vracajú sa zhruba raz za rok, takže takmer každý box ochutnáte po prvý raz.',
        'explore' => 'Preskúmať kávy na :month',
        'decaf' => 'bez kofeínu',
    ],

    'promo_card' => [
        // :month je prídavné meno v nominatíve („októbrový“)
        'alt' => 'Kávy, ktoré prináša :month box',
        'description' => 'Prémiové výberové kávy z európskych mikropražiarní, čerstvo pražené a doručené až k vašim dverám.',
        'more' => 'Viac info',
    ],

    'faq' => [
        'title' => 'Časté otázky',
        'contact' => 'Nenašli ste odpoveď? Napíšte na :email.',
    ],

    'language_switcher' => [
        'label' => 'Jazyk',
    ],

];
