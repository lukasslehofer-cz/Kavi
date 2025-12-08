<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Email Translations
    |--------------------------------------------------------------------------
    */

    'common' => [
        'hello' => 'Dobrý den',
        'thank_you' => 'Děkujeme',
        'regards' => 'S pozdravem',
        'team' => 'Tým KAVI',
        'questions' => 'Pokud máte jakékoli dotazy, neváhejte nás kontaktovat.',
        'contact_email' => 'info@kavi.cz',
    ],

    'order_confirmation' => [
        'subject' => 'Potvrzení objednávky :order_number - KAVI.cz',
        'title' => 'Děkujeme za vaši objednávku!',
        'greeting' => 'Dobrý den :name,',
        'intro' => 'Děkujeme za vaši objednávku. Zde jsou detaily:',
        'order_number' => 'Číslo objednávky',
        'order_date' => 'Datum objednávky',
        'items' => 'Objednané položky',
        'subtotal' => 'Mezisoučet',
        'shipping' => 'Doprava',
        'discount' => 'Sleva',
        'total' => 'Celkem',
        'shipping_address' => 'Doručovací adresa',
        'pickup_point' => 'Výdejní místo',
        'invoice_attached' => 'Faktura v příloze',
        'track_order' => 'Jakmile bude vaše objednávka odeslána, obdržíte sledovací číslo.',
    ],

    'subscription_confirmation' => [
        'subject' => 'Potvrzení předplatného - KAVI.cz',
        'title' => 'Vítejte v KAVI!',
        'greeting' => 'Dobrý den :name,',
        'intro' => 'Děkujeme za objednání kávového předplatného. Zde jsou detaily vašeho předplatného:',
        'subscription_number' => 'Číslo předplatného',
        'plan' => 'Plán',
        'price' => 'Cena za box',
        'frequency' => 'Frekvence doručení',
        'next_delivery' => 'Příští doručení',
        'shipping_address' => 'Doručovací adresa',
        'manage_subscription' => 'Své předplatné můžete spravovat ve svém účtu.',
    ],

    'subscription_payment_success' => [
        'subject' => 'Platba proběhla úspěšně - KAVI.cz',
        'title' => 'Platba přijata!',
        'greeting' => 'Dobrý den :name,',
        'intro' => 'Přijali jsme platbu za vaše předplatné.',
        'amount' => 'Zaplacená částka',
        'next_payment' => 'Datum příští platby',
        'shipping_soon' => 'Váš kávový box bude brzy odeslán.',
    ],

    'subscription_payment_failed' => [
        'subject' => 'Platba se nezdařila - KAVI.cz',
        'title' => 'Platbu se nepodařilo zpracovat',
        'greeting' => 'Dobrý den :name,',
        'intro' => 'Bohužel se nám nepodařilo zpracovat platbu za vaše předplatné.',
        'reason' => 'Důvod',
        'action' => 'Prosím aktualizujte platební metodu pro pokračování předplatného.',
        'update_payment' => 'Aktualizovat platební metodu',
    ],

    'order_shipped' => [
        'subject' => 'Vaše objednávka byla odeslána - KAVI.cz',
        'title' => 'Vaše objednávka je na cestě!',
        'greeting' => 'Dobrý den :name,',
        'intro' => 'Skvělá zpráva! Vaše objednávka byla odeslána.',
        'tracking_number' => 'Sledovací číslo',
        'track_package' => 'Sledovat zásilku',
    ],

];

