<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Confirmation Page Translations (Slovak)
    |--------------------------------------------------------------------------
    |
    | Shared by checkout/confirmation.blade.php and subscriptions/confirmation.blade.php
    |
    */

    // Page titles
    'page_title' => 'Potvrdenie objednávky - KAVIbox.sk',
    'page_title_subscription' => 'Potvrdenie predplatného - KAVIbox.sk',

    // Date format
    'date_format' => 'j. n. Y',

    // Cancelled payment
    'cancelled' => [
        'heading_1' => 'Platba',
        'heading_2' => 'nebola dokončená',
        'order_number' => 'Číslo objednávky',
        'pay_again' => 'Zaplatiť znova',
        'back_home' => 'Späť na hlavnú stránku',
    ],

    // Success header
    'success' => [
        'badge' => 'Objednávka bola úspešne vytvorená',
        'heading_1' => 'Ďakujeme za',
        'heading_2' => 'objednávku',
        'order_number' => 'Číslo objednávky',
        'badge_order' => 'Objednávka potvrdená',
        'badge_subscription' => 'Predplatné aktivované',
        'desc_onetime' => 'Vaša objednávka jednorazového boxu bola úspešne potvrdená. Zásielku vám odošleme v najbližšom termíne odoslania.',
        'desc_subscription' => 'Vaše predplatné bolo úspešne vytvorené a je teraz aktívne. Prvú zásielku vám odošleme v najbližšom termíne odoslania.',
    ],

    // Subscription addon
    'addon' => [
        'badge' => 'Odoslané s predplatným',
        'text' => 'Váš tovar bude pridaný do ďalšej zásielky predplatného',
        'shipped_on' => 'a odoslaný spolu s ňou dňa',
        'per_schedule' => 'podľa plánu odosielania',
        'free_shipping' => 'Doprava zadarmo',
    ],

    // Products section
    'products' => [
        'section_title' => 'Objednané produkty',
    ],

    // Pricing
    'pricing' => [
        'discount_label' => 'Zľava :code',
        'vat' => 'DPH (:rate %)',
        'vat_generic' => 'DPH',
    ],

    // Contact section
    'contact' => [
        'section_title' => 'Kontaktné údaje',
    ],

    // Delivery
    'delivery' => [
        'pickup_point' => 'Výdajné miesto',
        'delivery_address' => 'Doručovacia adresa',
        'packeta' => 'Packeta',
    ],

    // Subscription details
    'subscription_details' => [
        'section_title_order' => 'Vaša objednávka',
        'section_title_subscription' => 'Vaše predplatné',
        'quantity' => 'Množstvo',
        'bags_format' => ':count balenia (:grams g)',
        'coffee_type' => 'Typ kávy',
        'espresso' => 'Espresso',
        'filter' => 'Filter',
        'mix' => 'Kombinácia',
        'incl_decaf' => '(vr. 1× decaf)',
        'frequency' => 'Frekvencia',
        'frequency_0' => 'Jednorazový box',
        'frequency_1' => 'Každý mesiac',
        'frequency_2' => 'Raz za 2 mesiace',
        'frequency_3' => 'Raz za 3 mesiace',
        'price' => 'Cena',
        'first_shipment' => 'Prvá zásielka',
        'shipment' => 'Zásielka',
        'shipping_day_note' => '20. deň v mesiaci',
        'next_payment' => 'Ďalšia platba',
        'payment_day_note' => '15. deň v mesiaci',
    ],

    // Coupon
    'coupon' => [
        'activated' => 'Zľava :code aktivovaná',
        'gift_voucher_activated' => 'Darčekový voucher aktivovaný',
        'discount' => 'Zľava',
        'discounted_price' => 'Cena so zľavou',
        'valid_until' => 'Zľava platí do',
        'full_price_from' => 'Plná cena od',
    ],

    // Next steps
    'next_steps' => [
        'title' => 'Čo ďalej?',
        'email_title' => 'Potvrdenie e-mailom',
        'email_desc' => 'Na váš e-mail sme poslali potvrdenie s podrobnosťami objednávky',
        'email_desc_order' => 'Na váš e-mail sme poslali potvrdenie s podrobnosťami objednávky',
        'email_desc_subscription' => 'Na váš e-mail sme poslali potvrdenie s podrobnosťami predplatného',
        'processing_title' => 'Spracovanie objednávky',
        'processing_desc' => 'Vašu objednávku pripravujeme na odoslanie',
        'tracking_title' => 'Sledovanie zásielky',
        'tracking_desc' => 'Hneď ako zásielku odošleme, pošleme vám sledovacie číslo',
        'shipment_title' => 'Zásielka',
        'first_shipment_title' => 'Prvá zásielka',
        'shipment_desc' => 'Kávu vám odošleme v najbližšom termíne odoslania (20. deň v mesiaci)',
        'no_commitment_title' => 'Bez záväzku',
        'no_commitment_desc' => 'Jednorazový nákup bez predplatného. Žiadne ďalšie platby neprebehnú.',
        'manage_title' => 'Správa predplatného',
        'manage_desc_auth' => 'V dashboarde môžete predplatné kedykoľvek upraviť alebo zrušiť',
        'manage_desc_guest' => 'Na správu predplatného si vytvorte účet - odkaz sme vám poslali e-mailom',
    ],

    // Action buttons
    'actions' => [
        'view_orders' => 'Zobraziť objednávky',
        'continue_shopping' => 'Pokračovať v nákupe',
        'view_order' => 'Zobraziť objednávku',
        'view_subscription' => 'Zobraziť predplatné',
        'login' => 'Prihlásiť sa',
        'back_home' => 'Späť na úvodnú stránku',
    ],

    // Payment status
    'payment_status' => [
        'paid' => 'Platba prebehla úspešne',
        'pending' => 'Čaká sa na platbu',
        'pay_now' => 'Zaplatiť',
    ],

    // Help
    'help' => [
        'need_help' => 'Potrebujete pomôcť?',
    ],

];
