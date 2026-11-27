<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted' => 'Pole :attribute musí byť prijaté.',
    'active_url' => 'Pole :attribute nie je platnou URL adresou.',
    'after' => 'Pole :attribute musí byť dátum po :date.',
    'after_or_equal' => 'Pole :attribute musí byť dátum :date alebo neskorší.',
    'alpha' => 'Pole :attribute môže obsahovať iba písmená.',
    'alpha_dash' => 'Pole :attribute môže obsahovať iba písmená, číslice, pomlčky a podčiarkovníky.',
    'alpha_num' => 'Pole :attribute môže obsahovať iba písmená a číslice.',
    'array' => 'Pole :attribute musí byť pole.',
    'before' => 'Pole :attribute musí byť dátum pred :date.',
    'before_or_equal' => 'Pole :attribute musí byť dátum :date alebo skorší.',
    'between' => [
        'numeric' => 'Pole :attribute musí byť medzi :min a :max.',
        'file' => 'Pole :attribute musí mať medzi :min a :max kilobajtmi.',
        'string' => 'Pole :attribute musí mať medzi :min a :max znakmi.',
        'array' => 'Pole :attribute musí mať medzi :min a :max položkami.',
    ],
    'boolean' => 'Pole :attribute musí byť true alebo false.',
    'confirmed' => 'Potvrdenie poľa :attribute sa nezhoduje.',
    'current_password' => 'Heslo je nesprávne.',
    'date' => 'Pole :attribute nie je platný dátum.',
    'date_equals' => 'Pole :attribute musí byť dátum :date.',
    'date_format' => 'Pole :attribute nezodpovedá formátu :format.',
    'different' => 'Polia :attribute a :other sa musia líšiť.',
    'digits' => 'Pole :attribute musí mať :digits číslic.',
    'digits_between' => 'Pole :attribute musí mať medzi :min a :max číslicami.',
    'dimensions' => 'Pole :attribute má neplatné rozmery obrázka.',
    'distinct' => 'Pole :attribute má duplicitnú hodnotu.',
    'email' => 'Pole :attribute musí byť platná e-mailová adresa.',
    'ends_with' => 'Pole :attribute musí končiť jednou z nasledujúcich hodnôt: :values',
    'exists' => 'Vybrané pole :attribute je neplatné.',
    'file' => 'Pole :attribute musí byť súbor.',
    'filled' => 'Pole :attribute musí mať hodnotu.',
    'gt' => [
        'numeric' => 'Pole :attribute musí byť väčšie ako :value.',
        'file' => 'Pole :attribute musí byť väčšie ako :value kilobajtov.',
        'string' => 'Pole :attribute musí byť dlhšie ako :value znakov.',
        'array' => 'Pole :attribute musí mať viac ako :value položiek.',
    ],
    'gte' => [
        'numeric' => 'Pole :attribute musí byť väčšie alebo rovné :value.',
        'file' => 'Pole :attribute musí byť väčšie alebo rovné :value kilobajtov.',
        'string' => 'Pole :attribute musí byť dlhšie alebo rovné :value znakov.',
        'array' => 'Pole :attribute musí mať :value položiek alebo viac.',
    ],
    'image' => 'Pole :attribute musí byť obrázok.',
    'in' => 'Vybrané pole :attribute je neplatné.',
    'in_array' => 'Pole :attribute neexistuje v :other.',
    'integer' => 'Pole :attribute musí byť celé číslo.',
    'ip' => 'Pole :attribute musí byť platná IP adresa.',
    'ipv4' => 'Pole :attribute musí byť platná IPv4 adresa.',
    'ipv6' => 'Pole :attribute musí byť platná IPv6 adresa.',
    'json' => 'Pole :attribute musí byť platný JSON reťazec.',
    'lt' => [
        'numeric' => 'Pole :attribute musí byť menšie ako :value.',
        'file' => 'Pole :attribute musí byť menšie ako :value kilobajtov.',
        'string' => 'Pole :attribute musí byť kratšie ako :value znakov.',
        'array' => 'Pole :attribute musí mať menej ako :value položiek.',
    ],
    'lte' => [
        'numeric' => 'Pole :attribute musí byť menšie alebo rovné :value.',
        'file' => 'Pole :attribute musí byť menšie alebo rovné :value kilobajtov.',
        'string' => 'Pole :attribute musí byť kratšie alebo rovné :value znakov.',
        'array' => 'Pole :attribute nesmie mať viac ako :value položiek.',
    ],
    'max' => [
        'numeric' => 'Pole :attribute nesmie byť väčšie ako :max.',
        'file' => 'Pole :attribute nesmie byť väčšie ako :max kilobajtov.',
        'string' => 'Pole :attribute nesmie byť dlhšie ako :max znakov.',
        'array' => 'Pole :attribute nesmie mať viac ako :max položiek.',
    ],
    'mimes' => 'Pole :attribute musí byť súbor typu: :values.',
    'mimetypes' => 'Pole :attribute musí byť súbor typu: :values.',
    'min' => [
        'numeric' => 'Pole :attribute musí byť aspoň :min.',
        'file' => 'Pole :attribute musí mať aspoň :min kilobajtov.',
        'string' => 'Pole :attribute musí mať aspoň :min znakov.',
        'array' => 'Pole :attribute musí mať aspoň :min položiek.',
    ],
    'multiple_of' => 'Pole :attribute musí byť násobkom :value',
    'not_in' => 'Vybrané pole :attribute je neplatné.',
    'not_regex' => 'Formát poľa :attribute je neplatný.',
    'numeric' => 'Pole :attribute musí byť číslo.',
    'password' => 'Heslo je nesprávne.',
    'present' => 'Pole :attribute musí byť prítomné.',
    'regex' => 'Formát poľa :attribute je neplatný.',
    'required' => 'Pole :attribute je povinné.',
    'required_if' => 'Pole :attribute je povinné, keď :other je :value.',
    'required_unless' => 'Pole :attribute je povinné, pokiaľ :other nie je v :values.',
    'required_with' => 'Pole :attribute je povinné, keď je prítomné :values.',
    'required_with_all' => 'Pole :attribute je povinné, keď sú prítomné :values.',
    'required_without' => 'Pole :attribute je povinné, keď nie je prítomné :values.',
    'required_without_all' => 'Pole :attribute je povinné, keď nie je prítomná žiadna z hodnôt :values.',
    'same' => 'Polia :attribute a :other sa musia zhodovať.',
    'size' => [
        'numeric' => 'Pole :attribute musí byť :size.',
        'file' => 'Pole :attribute musí mať :size kilobajtov.',
        'string' => 'Pole :attribute musí mať :size znakov.',
        'array' => 'Pole :attribute musí obsahovať :size položiek.',
    ],
    'starts_with' => 'Pole :attribute musí začínať jednou z nasledujúcich hodnôt: :values',
    'string' => 'Pole :attribute musí byť reťazec.',
    'timezone' => 'Pole :attribute musí byť platné časové pásmo.',
    'unique' => 'Pole :attribute už bolo použité.',
    'uploaded' => 'Pole :attribute sa nepodarilo nahrať.',
    'url' => 'Formát poľa :attribute je neplatný.',
    'uuid' => 'Pole :attribute musí byť platné UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'phone' => [
            'regex' => 'Telefónne číslo musí byť platné (napr. +421 912 345 678 alebo 912345678).',
            'required' => 'Telefónne číslo je povinné.',
            'cz_format' => 'České telefónne číslo musí mať formát +420 a 9 číslic (napr. +420 123 456 789).',
            'sk_format' => 'Slovenské telefónne číslo musí mať formát +421 a 9 číslic (napr. +421 912 345 678).',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name' => 'meno',
        'email' => 'e-mail',
        'phone' => 'telefón',
        'password' => 'heslo',
        'password_confirmation' => 'potvrdenie hesla',
        'token' => 'token',
        'invoice_company' => 'názov firmy',
        'invoice_registration_no' => 'IČO',
        'invoice_vat_no' => 'IČ DPH',
        'invoice_street' => 'ulica',
        'invoice_city' => 'mesto',
        'invoice_zip' => 'PSČ',
        'invoice_country' => 'krajina',
    ],

];
