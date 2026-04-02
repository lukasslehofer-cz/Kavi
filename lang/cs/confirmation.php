<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Confirmation Page Translations (Czech)
    |--------------------------------------------------------------------------
    |
    | Shared by checkout/confirmation.blade.php and subscriptions/confirmation.blade.php
    |
    */

    // Page titles
    'page_title' => 'Potvrzení objednávky - KAVI.cz',
    'page_title_subscription' => 'Potvrzení předplatného - KAVI.cz',

    // Date format
    'date_format' => 'j. n. Y',

    // Cancelled payment
    'cancelled' => [
        'heading_1' => 'Platba',
        'heading_2' => 'nebyla dokončena',
        'order_number' => 'Číslo objednávky',
        'pay_again' => 'Zaplatit znovu',
        'back_home' => 'Zpět na hlavní stránku',
    ],

    // Success header
    'success' => [
        'badge' => 'Objednávka úspěšně vytvořena',
        'heading_1' => 'Děkujeme za',
        'heading_2' => 'objednávku',
        'order_number' => 'Číslo objednávky',
        'badge_order' => 'Objednávka potvrzena',
        'badge_subscription' => 'Předplatné aktivováno',
        'desc_onetime' => 'Vaše objednávka jednorázového boxu byla úspěšně potvrzena. Zásilku vám odešleme v nejbližším termínu rozesílky.',
        'desc_subscription' => 'Vaše předplatné bylo úspěšně vytvořeno a je nyní aktivní. První zásilku vám odešleme v nejbližším termínu rozesílky.',
    ],

    // Subscription addon
    'addon' => [
        'badge' => 'Odesláno s předplatným',
        'text' => 'Vaše zboží bude přidáno do příští rozesílky předplatného',
        'shipped_on' => 'a odesláno společně dne',
        'per_schedule' => 'dle plánu rozesílky',
        'free_shipping' => 'Doprava zdarma',
    ],

    // Products section
    'products' => [
        'section_title' => 'Objednané produkty',
    ],

    // Pricing
    'pricing' => [
        'discount_label' => 'Sleva :code',
        'vat' => 'DPH (:rate%)',
        'vat_generic' => 'DPH',
    ],

    // Contact section
    'contact' => [
        'section_title' => 'Kontaktní údaje',
    ],

    // Delivery
    'delivery' => [
        'pickup_point' => 'Výdejní místo',
        'delivery_address' => 'Doručovací adresa',
        'packeta' => 'Zásilkovna',
    ],

    // Subscription details
    'subscription_details' => [
        'section_title_order' => 'Vaše objednávka',
        'section_title_subscription' => 'Vaše předplatné',
        'quantity' => 'Množství',
        'bags_format' => ':count balení (:grams g)',
        'coffee_type' => 'Typ kávy',
        'espresso' => 'Espresso',
        'filter' => 'Filtr',
        'mix' => 'Kombinace',
        'incl_decaf' => '(vč. 1× decaf)',
        'frequency' => 'Frekvence',
        'frequency_0' => 'Jednorázový box',
        'frequency_1' => 'Každý měsíc',
        'frequency_2' => 'Jednou za 2 měsíce',
        'frequency_3' => 'Jednou za 3 měsíce',
        'price' => 'Cena',
        'first_shipment' => 'První rozesílka',
        'shipment' => 'Rozesílka',
        'shipping_day_note' => '20. den v měsíci',
        'next_payment' => 'Další platba',
        'payment_day_note' => '15. den v měsíci',
    ],

    // Coupon
    'coupon' => [
        'activated' => 'Sleva :code aktivována',
        'discount' => 'Sleva',
        'discounted_price' => 'Cena se slevou',
        'valid_until' => 'Sleva platí do',
        'full_price_from' => 'Plná cena od',
    ],

    // Next steps
    'next_steps' => [
        'title' => 'Co dál?',
        'email_title' => 'Potvrzení emailem',
        'email_desc' => 'Na váš email jsme odeslali potvrzení s detaily objednávky',
        'email_desc_order' => 'Na váš email jsme odeslali potvrzení s detaily objednávky',
        'email_desc_subscription' => 'Na váš email jsme odeslali potvrzení s detaily předplatného',
        'processing_title' => 'Zpracování objednávky',
        'processing_desc' => 'Vaši objednávku připravujeme k odeslání',
        'tracking_title' => 'Sledování zásilky',
        'tracking_desc' => 'Jakmile odešleme, pošleme vám tracking číslo',
        'shipment_title' => 'Zásilka',
        'first_shipment_title' => 'První zásilka',
        'shipment_desc' => 'Kávu vám odešleme v nejbližším termínu rozesílky (20. den v měsíci)',
        'no_commitment_title' => 'Bez závazku',
        'no_commitment_desc' => 'Jednorázový nákup bez předplatného. Žádné další platby neproběhnou.',
        'manage_title' => 'Správa předplatného',
        'manage_desc_auth' => 'V dashboardu můžete kdykoli upravit nebo zrušit předplatné',
        'manage_desc_guest' => 'Pro správu předplatného si vytvořte účet - link jsme vám poslali na email',
    ],

    // Action buttons
    'actions' => [
        'view_orders' => 'Zobrazit objednávky',
        'continue_shopping' => 'Pokračovat v nákupu',
        'view_order' => 'Zobrazit objednávku',
        'view_subscription' => 'Zobrazit předplatné',
        'login' => 'Přihlásit se',
        'back_home' => 'Zpět na homepage',
    ],

    // Payment status
    'payment_status' => [
        'paid' => 'Platba proběhla úspěšně',
        'pending' => 'Čeká na platbu',
        'pay_now' => 'Zaplatit',
    ],

    // Help
    'help' => [
        'need_help' => 'Potřebujete pomoc?',
    ],

];
